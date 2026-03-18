<?php
require "proteger.php";
require "conexao.php";
require "log.php";

if(!isset($_SESSION['usuario_id'])) {
    die("Usuário não autenticado.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

/* =====================
   DADOS DA VENDA
===================== */
$cliente         = trim($_POST['cliente'] ?? '');
$forma_pagamento = $_POST['forma_pagamento'] ?? '';
$desconto_tipo   = $_POST['desconto_tipo'] ?? 'valor';
$desconto_valor  = floatval($_POST['desconto_valor'] ?? 0);
$materiais       = $_POST['materiais'] ?? [];

$formas_validas = [
    'dinheiro',
    'pix',
    'cartao',
    'cheque',
    'transf_bancaria',
    'mpesa',
    'emola'
];

if (
    $cliente === '' ||
    empty($materiais) ||
    !is_array($materiais) ||
    !in_array($forma_pagamento, $formas_validas, true)
) {
    die("Venda inválida.");
}

/* =====================
   TRANSACTION
===================== */
$conn->begin_transaction();

try {

    /* =====================
       CALCULAR TOTAL + VALIDAR ESTOQUE
    ===================== */
    $total = 0;
    $itens = [];

    $stmtMaterial = $conn->prepare(
        "SELECT nome, custo_venda, quantidade
         FROM materiais
         WHERE id = ? FOR UPDATE"
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
            throw new Exception(
                "Estoque insuficiente para o produto: {$m['nome']}"
            );
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

    /* =====================
       DESCONTO
    ===================== */
    $desconto_aplicado = ($desconto_tipo === 'percentual')
        ? ($total * ($desconto_valor / 100))
        : $desconto_valor;

    $desconto_aplicado = max(0, min($desconto_aplicado, $total));
    $total_final = $total - $desconto_aplicado;

    /* =====================
       SALVAR VENDA
    ===================== */
    $stmtVenda = $conn->prepare("
        INSERT INTO vendas
        (cliente, usuario_id, total, forma_pagamento, desconto, status, data_venda)
        VALUES (?, ?, ?, ?, ?, 'concluida', NOW())
    ");

    $stmtVenda->bind_param(
        "sidsd",
        $cliente,
        $usuario_id,
        $total_final,
        $forma_pagamento,
        $desconto_aplicado
    );

    $stmtVenda->execute();
    $venda_id = $stmtVenda->insert_id;

    /* =====================
       ITENS + ESTOQUE
    ===================== */
    $stmtItem = $conn->prepare("
        INSERT INTO itens_venda
        (venda_id, material_id, quantidade, preco_unitario, total)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmtEstoque = $conn->prepare("
        UPDATE materiais
        SET quantidade = quantidade - ?
        WHERE id = ?
    ");

    foreach ($itens as $i) {

        $stmtItem->bind_param(
            "iiidd",
            $venda_id,
            $i['id'],
            $i['qtd'],
            $i['unit'],
            $i['total']
        );
        $stmtItem->execute();

        $stmtEstoque->bind_param("ii", $i['qtd'], $i['id']);
        $stmtEstoque->execute();
    }

    /* =====================
       LOG
    ===================== */
    registrarLog(
        acao: "Registrou venda ID $venda_id com total R$ ".number_format($total_final,2,',','.')." e desconto R$ ".number_format($desconto_aplicado,2,',','.'),
        material_id: null,
        quantidade: null
    );

    /* =====================
       COMMIT
    ===================== */
    $conn->commit();

    /* =====================
       REDIRECIONAR PARA PDF
    ===================== */
    echo '
    <form id="pdfForm" method="POST" action="gerar_pdf_venda.php">
        <input type="hidden" name="venda_id" value="'.$venda_id.'">
    </form>
    <script>
        document.getElementById("pdfForm").submit();
    </script>';
    exit;

} catch (Exception $e) {
    $conn->rollback();
    die("Erro na venda: " . $e->getMessage());
}
