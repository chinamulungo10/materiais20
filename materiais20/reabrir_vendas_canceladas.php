<?php
require "proteger.php";
require "conexao.php";
require "log.php";

/* =====================
   SOMENTE ADMIN
===================== */
if ($_SESSION['nivel'] !== 'admin') {
    http_response_code(403);
    exit("Acesso negado.");
}

if (!isset($_GET['id'])) {
    die("Venda não informada.");
}

$venda_id   = (int) $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];

/* =====================
   BUSCAR VENDA
===================== */
$stmtVenda = $conn->prepare("
    SELECT status, cliente, total
    FROM vendas
    WHERE id = ?
");
$stmtVenda->bind_param("i", $venda_id);
$stmtVenda->execute();
$venda = $stmtVenda->get_result()->fetch_assoc();

if (!$venda) {
    die("Venda não encontrada.");
}

if ($venda['status'] !== 'cancelada') {
    die("Apenas vendas canceladas podem ser reabertas.");
}

/* =====================
   BUSCAR ITENS DA VENDA
===================== */
$stmtItens = $conn->prepare("
    SELECT iv.material_id, iv.quantidade, m.quantidade AS estoque
    FROM itens_venda iv
    JOIN materiais m ON m.id = iv.material_id
    WHERE iv.venda_id = ?
");
$stmtItens->bind_param("i", $venda_id);
$stmtItens->execute();
$itens = $stmtItens->get_result();

/* =====================
   VERIFICAR ESTOQUE
===================== */
while ($item = $itens->fetch_assoc()) {
    if ($item['estoque'] < $item['quantidade']) {
        die(
            "Estoque insuficiente para reabrir a venda. Material ID: "
            . $item['material_id']
        );
    }
}

/* =====================
   TRANSAÇÃO
===================== */
$conn->begin_transaction();

try {

    /* 🔻 RETIRAR ESTOQUE NOVAMENTE */
    $stmtEstoque = $conn->prepare("
        UPDATE materiais
        SET quantidade = quantidade - ?
        WHERE id = ?
    ");

    $stmtItens->execute();
    $itens = $stmtItens->get_result();

    while ($item = $itens->fetch_assoc()) {
        $stmtEstoque->bind_param(
            "ii",
            $item['quantidade'],
            $item['material_id']
        );
        $stmtEstoque->execute();
    }

    /* 🔄 REABRIR VENDA */
    $stmtReabrir = $conn->prepare("
        UPDATE vendas
        SET status = 'concluida',
            data_cancelamento = NULL
        WHERE id = ?
    ");
    $stmtReabrir->bind_param("i", $venda_id);
    $stmtReabrir->execute();

    /* 🧾 LOG */
    registrarLog(
        $usuario_id,
        "REABERTURA DE VENDA",
        "Venda #{$venda_id} | Cliente: {$venda['cliente']} | Total: {$venda['total']}"
    );

    $conn->commit();

    header("Location: listar_vendas.php?sucesso=reaberta");
    exit;

} catch (Exception $e) {

    $conn->rollback();

    registrarLog(
        $usuario_id,
        "ERRO AO REABRIR VENDA",
        "Venda #{$venda_id} | Erro: " . $e->getMessage()
    );

    die("Erro ao reabrir venda.");
}
