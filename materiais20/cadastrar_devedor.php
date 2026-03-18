<?php
include "conexao.php";

if(isset($_POST['salvar'])){

$cliente = $_POST['cliente'];
$telefone = $_POST['telefone'];
$material = $_POST['material'];
$valor = $_POST['valor'];
$data = $_POST['data'];
$vencimento = $_POST['vencimento'];
$status = "ABERTO";

/* validações */
if($valor <= 0){
die("Valor da dívida inválido");
}

if($vencimento < $data){
die("Data de vencimento não pode ser menor que a data da dívida");
}

$sql = "INSERT INTO devedores 
(nome_cliente, telefone, material, valor_divida, data_divida, data_vencimento, status)
VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
"sssdsss",
$cliente,
$telefone,
$material,
$valor,
$data,
$vencimento,
$status
);

$stmt->execute();

header("Location: devedores.php");
exit;

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Cadastrar Devedor</title>
<link rel="stylesheet" href="cadastrar_devedor.css">

</head>

<body>

<div class="container">

<h2>Cadastrar Devedor</h2>

<form method="POST">

Cliente
<input type="text" name="cliente" required>

Telefone
<input type="text" name="telefone">

Material
<input type="text" name="material">

Valor
<input type="number" step="0.01" name="valor" required>

Data da dívida
<input type="date" name="data" required>

Data vencimento
<input type="date" name="vencimento" required>

<button name="salvar">Salvar</button>

</form>

</div>

</body>
</html>