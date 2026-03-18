<?php
session_start();
require "proteger.php";
require "conexao.php";
require "log.php";

/* =========================
   PERMISSÃO
========================= */
if ($_SESSION['nivel'] !== 'admin') {
    http_response_code(403);
    exit("Acesso negado.");
}

/* =========================
   RECEBER DADOS
========================= */
$venda_id = intval($_GET['id'] ?? 0);
$motivo   = trim($_GET['motivo'] ?? '');

if ($venda_id <= 0 || $motivo === '') {
    die("Dados inválidos.");
}

$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    die("Usuário não identificado.");
}

/* =========================
   CANCELAR VENDA
========================= */
$stmt = $conn->prepare("
    UPDATE vendas
    SET 
        status = 'cancelada',
        motivo_cancelamento = ?,
        cancelado_por = ?,
        cancelado_em = NOW()
    WHERE id = ?
");

$stmt->bind_param("sii", $motivo, $usuario_id, $venda_id);

if (!$stmt->execute()) {
    die("Erro ao cancelar venda: " . $stmt->error);
}

/* =========================
   ESTORNAR ESTOQUE E LOG
========================= */
$itens = $conn->query("
    SELECT material_id, quantidade
    FROM itens_venda
    WHERE venda_id = $venda_id
");

while ($item = $itens->fetch_assoc()) {
    $material_id = (int)$item['material_id'];
    $quantidade  = (int)$item['quantidade'];

    // Atualiza estoque
    $stmtEstorno = $conn->prepare("
        UPDATE materiais
        SET quantidade = quantidade + ?
        WHERE id = ?
    ");
    $stmtEstorno->bind_param("ii", $quantidade, $material_id);
    $stmtEstorno->execute();

    // Log individual do item estornado
    registrarLog(
        acao: "Estornou $quantidade unidades do material ID $material_id da venda $venda_id",
        material_id: $material_id,
        quantidade: $quantidade
    );
}

/* =========================
   LOG GERAL DO CANCELAMENTO
========================= */
registrarLog(
    acao: "Cancelou a venda ID $venda_id. Motivo: $motivo"
);

/* =========================
   REDIRECIONAR
========================= */
header("Location: listar_estatus_venda.php");
exit;
