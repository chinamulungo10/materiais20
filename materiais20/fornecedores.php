<?php
include "conexao.php";

$pesquisa = "";

if(isset($_GET['pesquisa'])){

$pesquisa = $_GET['pesquisa'];

$sql = "SELECT * FROM fornecedores 
WHERE nome LIKE '%$pesquisa%' 
OR tipo_material LIKE '%$pesquisa%'
ORDER BY nome";

}else{

$sql = "SELECT * FROM fornecedores ORDER BY nome";

}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>

<title>Fornecedores</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
}

h2{
text-align:center;
}

table{
width:95%;
margin:auto;
border-collapse:collapse;
background:white;
}

th,td{
border:1px solid #ccc;
padding:8px;
text-align:center;
}

th{
background:#2c3e50;
color:white;
}

.btn{
padding:5px 8px;
text-decoration:none;
color:white;
border-radius:4px;
}

.whatsapp{background:#25D366;}
.email{background:#3498db;}
.editar{background:#f39c12;}
.apagar{background:#e74c3c;}
.novo{background:#2ecc71;}

.topo{
width:95%;
margin:auto;
margin-bottom:10px;
}

</style>

</head>

<body>

<h2>Gestão de Fornecedores</h2>

<div class="topo">

<form method="GET">

<input type="text" name="pesquisa" placeholder="Buscar por fornecedor ou material">

<button type="submit">Buscar</button>

<a class="btn novo" href="cadastrar_fornecedor.php">Novo Fornecedor</a>

</form>

</div>

<table>

<tr>

<th>ID</th>
<th>Nome</th>
<th>CNPJ/CPF</th>
<th>Telefone</th>
<th>Email</th>
<th>Cidade</th>
<th>Estado</th>
<th>Material</th>
<th>Ações</th>

</tr>

<?php

while($row = $result->fetch_assoc()){

?>

<tr>

<td><?php echo $row['id_fornecedor']; ?></td>

<td><?php echo $row['nome']; ?></td>

<td><?php echo $row['cnpj_cpf']; ?></td>

<td>
<a class="btn whatsapp" target="_blank"
href="https://wa.me/<?php echo $row['telefone']; ?>">
WhatsApp
</a>
</td>

<td>
<a class="btn email"
href="mailto:<?php echo $row['email']; ?>">
Email
</a>
</td>

<td><?php echo $row['cidade']; ?></td>

<td><?php echo $row['estado']; ?></td>

<td><?php echo $row['tipo_material']; ?></td>

<td>

<a class="btn editar"
href="editar_fornecedor.php?id=<?php echo $row['id_fornecedor']; ?>">
Editar
</a>

<a class="btn apagar"
href="apagar_fornecedor.php?id=<?php echo $row['id_fornecedor']; ?>"
onclick="return confirm('Deseja realmente excluir este fornecedor?')">
Apagar
</a>
</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>