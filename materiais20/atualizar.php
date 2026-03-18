<?php
require "proteger.php";
require "conexao.php";

if (
    empty($_POST['id']) ||
    empty($_POST['codigo_barra']) ||
    empty($_POST['nome']) ||
    !isset($_POST['quantidade']) ||
    !isset($_POST['custo_compra']) ||
    !isset($_POST['custo_venda'])
) {
    die("Dados incompletos.");
}

$id            = intval($_POST['id']);
$codigo_barra  = trim($_POST['codigo_barra']);
$nome          = trim($_POST['nome']);
$quantidade    = intval($_POST['quantidade']);
$custo_compra  = floatval($_POST['custo_compra']);
$custo_venda   = floatval($_POST['custo_venda']);

// Evitar código de barras duplicado em OUTRO produto
$stmt = $conn->prepare(
    "SELECT id FROM materiais 
     WHERE codigo_barra = ? AND id != ?"
);
$stmt->bind_param("si", $codigo_barra, $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    die("Erro: Código de barras já está em uso.");
}

// Atualizar material
$stmt = $conn->prepare("
    UPDATE materiais
    SET codigo_barra = ?, nome = ?, quantidade = ?, custo_compra = ?, custo_venda = ?
    WHERE id = ?
");

$stmt->bind_param(
    "ssiddi",
    $codigo_barra,
    $nome,
    $quantidade,
    $custo_compra,
    $custo_venda,
    $id
);

if (!$stmt->execute()) {
    die("Erro ao atualizar material.");
}

header("Location: listar.php?edit=1");
exit;
