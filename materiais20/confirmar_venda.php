<?php
require "conexao.php";

$id = intval($_POST['id']);
$qtd = intval($_POST['qtd']);

$conn->query("
UPDATE materiais 
SET quantidade = quantidade - $qtd 
WHERE id = $id AND quantidade >= $qtd
");

header("Location: gerar_pdf_venda.php");
