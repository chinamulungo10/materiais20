<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);

require "proteger.php";
require "conexao.php";
require "funcoes_log.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function responder($dados) {
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_SESSION['usuario_id'])) {
    responder(['ok' => false, 'erro' => 'Usuário não autenticado.']);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

/* DADOS DA VENDA */
$cliente         = trim($_POST['cliente'] ?? '');
$forma_pagamento = $_POST['forma_pagamento'] ?? '';
$desconto_tipo   = $_POST['desconto_tipo'] ?? 'valor';
$desconto_valor  = floatval($_POST['desconto_valor'] ?? 0);
$materiais       = $_POST['materiais'] ?? [];
$token           = $_POST['token'] ?? '';

$formas_validas = ['dinheiro','pix','cartao','cheque','transf_bancaria','mpesa','emola','divida'];

/* Token de uso único: impede gravar a mesma venda duas vezes */
if ($token === '' || !hash_equals($_SESSION['token_venda'] ?? '', $token)) {
    responder(['ok' => false, 'erro' => 'Esta venda já foi processada. Recarregue a página (Ctrl+F5) para uma nova venda.']);
}

if (
    $cliente === '' ||
    empty($materiais) ||
    !is_array($materiais) ||
    !in_array($forma_pagamento, $formas_validas, true)
) {
    responder(['ok' => false, 'erro' => 'Venda inválida.']);
}

$conn->begin_transaction();

try {

    /* CALCULAR TOTAL + VALIDAR ESTOQUE */
    $total = 0;
    $itens = [];

    $stmtMaterial = $conn->prepare(
        "SELECT nome, custo_venda, quantidade FROM materiais WHERE id = ? FOR UPDATE"
    );

    foreach ($materiais as $item) {
        $material_id = (int)($item['id'] ?? 0);
        $qtd         = (int)($item['quantidade'] ?? 0);

        if ($material_id <= 0 || $qtd <= 0) {
            throw new Exception("Dados inválidos do material.");
        }

        $stmtMaterial->bind_param("i", $material_id);
        $stmtMaterial->execute();
        $m = $stmtMaterial->get_result()->fetch_assoc();

        if (!$m) {
            throw new Exception("Material não encontrado.");
        }
        if ($m['quantidade'] < $qtd) {
            throw new Exception("Estoque insuficiente para o produto: {$m['nome']}");
        }

        $linha = $m['custo_venda'] * $qtd;
        $total += $linha;

        $itens[] = [
            'id'    => $material_id,
            'qtd'   => $qtd,
            'unit'  => $m['custo_venda'],
            'total' => $linha,
            'nome'  => $m['nome']
        ];
    }

    if ($total <= 0) {
        throw new Exception("Total inválido.");
    }

    /* DESCONTO */
    $desconto_aplicado = ($desconto_tipo === 'percentual')
        ? ($total * ($desconto_valor / 100))
        : $desconto_valor;

    $desconto_aplicado = max(0, min($desconto_aplicado, $total));
    $total_final = $total - $desconto_aplicado;

    /* SALVAR VENDA */
    $stmtVenda = $conn->prepare("
        INSERT INTO vendas
        (cliente, usuario_id, total, forma_pagamento, desconto, status, data_venda)
        VALUES (?, ?, ?, ?, ?, 'concluida', NOW())
    ");
    $stmtVenda->bind_param("sidsd", $cliente, $usuario_id, $total_final, $forma_pagamento, $desconto_aplicado);
    $stmtVenda->execute();
    $venda_id = $stmtVenda->insert_id;

    /* ITENS + ESTOQUE */
    $stmtItem = $conn->prepare("
        INSERT INTO itens_venda (venda_id, material_id, quantidade, preco_unitario, total)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmtEstoque = $conn->prepare("UPDATE materiais SET quantidade = quantidade - ? WHERE id = ?");

    foreach ($itens as $i) {
        $stmtItem->bind_param("iiidd", $venda_id, $i['id'], $i['qtd'], $i['unit'], $i['total']);
        if (!$stmtItem->execute()) {
            throw new Exception("Erro ao gravar item da venda: " . $stmtItem->error);
        }

        $stmtEstoque->bind_param("ii", $i['qtd'], $i['id']);
        if (!$stmtEstoque->execute()) {
            throw new Exception("Erro ao atualizar estoque: " . $stmtEstoque->error);
        }
    }

    /* DÍVIDA */
    if ($forma_pagamento == 'divida') {
        $data_hoje  = date("Y-m-d");
        $vencimento = date("Y-m-d", strtotime("+30 days"));

        $materiais_texto = '';
        foreach ($itens as $i) {
            $materiais_texto .= $i['nome'] . ' x' . $i['qtd'] . ', ';
        }

        $stmtDevedor = $conn->prepare("
            INSERT INTO devedores
            (id_venda, nome_cliente, telefone, material, valor_divida, data_divida, data_vencimento, status)
            VALUES (?, ?, '', ?, ?, ?, ?, 'ABERTO')
        ");
        $stmtDevedor->bind_param("issdss", $venda_id, $cliente, $materiais_texto, $total_final, $data_hoje, $vencimento);
        $stmtDevedor->execute();
    }

    /* LOG */
    foreach ($itens as $i) {
        registrarLog("Venda $venda_id realizada", $i['id'], $i['qtd']);
    }

    $conn->commit();

    /* Novo token para a próxima venda */
    $_SESSION['token_venda'] = bin2hex(random_bytes(16));

    responder([
        'ok'       => true,
        'venda_id' => (int)$venda_id,
        'token'    => $_SESSION['token_venda']
    ]);

} catch (Exception $e) {
    $conn->rollback();
    responder(['ok' => false, 'erro' => 'Erro na venda: ' . $e->getMessage()]);
}