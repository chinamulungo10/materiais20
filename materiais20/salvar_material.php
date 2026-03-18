<?php
require "proteger.php";
require "conexao.php";

if ($_SESSION['nivel'] !== 'admin') {
    http_response_code(403);
    exit("Acesso negado.");
}

$codigo_barra  = $_POST['codigo_barra'];
$nome          = $_POST['nome'];
$cor           = $_POST['cor'] ?? null;
$peso          = $_POST['peso'] ?? null;
$altura        = $_POST['altura'] ?? null;
$largura       = $_POST['largura'] ?? null;
$quantidade    = (int)$_POST['quantidade'];
$custo_compra  = (float)$_POST['custo_compra'];
$custo_venda   = (float)$_POST['custo_venda'];

$stmt = $conn->prepare("
    INSERT INTO materiais
    (codigo_barra, nome, cor, peso, altura, largura, quantidade, custo_compra, custo_venda)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssdddidd",
    $codigo_barra,
    $nome,
    $cor,
    $peso,
    $altura,
    $largura,
    $quantidade,
    $custo_compra,
    $custo_venda
);

$stmt->execute();

header("Location: cadastrar_material.php?sucesso=1");
exit;
