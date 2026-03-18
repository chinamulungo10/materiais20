<?php
include "conexao.php";

if(isset($_POST['salvar'])){

$nome = $_POST['nome'];
$cnpj = $_POST['cnpj'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$endereco = $_POST['endereco'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$tipo = $_POST['tipo_material'];

$sql = "INSERT INTO fornecedores
(nome,cnpj_cpf,telefone,email,endereco,cidade,estado,tipo_material,data_cadastro)
VALUES
('$nome','$cnpj','$telefone','$email','$endereco','$cidade','$estado','$tipo',NOW())";

$conn->query($sql);

header("Location: fornecedores.php");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Cadastrar Fornecedor</title>
<link rel="stylesheet" href="fornecedor.css">

</head>

<body>

<div class="container">

<h2>Novo Fornecedor</h2>

<form method="POST">

<label>Nome</label>
<input type="text" name="nome" required>

<label>CNPJ / CPF</label>
<input type="text" name="cnpj">

<label>Telefone</label>
<input type="text" name="telefone">

<label>Email</label>
<input type="email" name="email">

<label>Endereço</label>
<input type="text" name="endereco">

<label>Cidade</label>
<input type="text" name="cidade">

<label>Estado</label>
<input type="text" name="estado">

<label>Tipo de material que fornece</label>
<input type="text" name="tipo_material">

<button name="salvar">Salvar Fornecedor</button>

</form>

<a class="voltar" href="fornecedores.php">Voltar</a>

</div>

</body>
</html>