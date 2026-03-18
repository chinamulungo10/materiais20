<?php
include "conexao.php";

$id = $_GET['id'];

$sql = "SELECT * FROM fornecedores WHERE id_fornecedor=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if(isset($_POST['atualizar'])){

$nome = $_POST['nome'];
$cnpj = $_POST['cnpj'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$endereco = $_POST['endereco'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$tipo_material = $_POST['tipo_material'];

$sql = "UPDATE fornecedores SET
nome='$nome',
cnpj_cpf='$cnpj',
telefone='$telefone',
email='$email',
endereco='$endereco',
cidade='$cidade',
estado='$estado',
tipo_material='$tipo_material'
WHERE id_fornecedor=$id";

$conn->query($sql);

header("Location: fornecedores.php");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Editar Fornecedor</title>
<link rel="stylesheet" href="fornecedor.css">

</head>

<body>

<div class="container">

<h2>Editar Fornecedor</h2>

<form method="POST">

<label>Nome</label>
<input type="text" name="nome" value="<?php echo $row['nome']; ?>">

<label>CNPJ / CPF</label>
<input type="text" name="cnpj" value="<?php echo $row['cnpj_cpf']; ?>">

<label>Telefone</label>
<input type="text" name="telefone" value="<?php echo $row['telefone']; ?>">

<label>Email</label>
<input type="email" name="email" value="<?php echo $row['email']; ?>">

<label>Endereço</label>
<input type="text" name="endereco" value="<?php echo $row['endereco']; ?>">

<label>Cidade</label>
<input type="text" name="cidade" value="<?php echo $row['cidade']; ?>">

<label>Estado</label>
<input type="text" name="estado" value="<?php echo $row['estado']; ?>">
<label>Tipo de material - fornece</label>
<input type="text" name="tipo_material" value="<?php echo $row['tipo_material']; ?>">

<button name="atualizar">Atualizar Fornecedor</button>

</form>

<a class="voltar" href="fornecedores.php">Voltar</a>

</div>

</body>
</html>