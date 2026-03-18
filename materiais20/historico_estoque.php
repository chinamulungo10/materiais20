<?php
include("conexao.php");

$sql = "SELECT 
m.nome,
e.estoque_anterior,
e.quantidade_adicionada,
e.estoque_final,
e.tipo_movimento,
e.data_movimentacao
FROM movimentacao_estoque e
JOIN materiais m ON m.id = e.material_id
ORDER BY e.data_movimentacao DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Histórico de Estoque</title>

<style>

body{
font-family: Arial;
background:#f4f4f4;
}

table{
width:90%;
margin:auto;
border-collapse: collapse;
background:white;
}

th, td{
padding:10px;
border:1px solid #ccc;
text-align:center;
}

th{
background:#2c3e50;
color:white;
}

h2{
text-align:center;
}

</style>

</head>

<body>

<h2>Histórico de Movimentação de Estoque</h2>

<table>

<tr>
<th>Produto</th>
<th>Estoque Anterior</th>
<th>Quantidade Adicionada</th>
<th>Estoque Final</th>
<th>Tipo</th>
<th>Data</th>
</tr>

<?php
if($result->num_rows > 0){

while($row = $result->fetch_assoc()){
?>

<tr>
<td><?php echo $row['nome']; ?></td>
<td><?php echo $row['estoque_anterior']; ?></td>
<td><?php echo $row['quantidade_adicionada']; ?></td>
<td><?php echo $row['estoque_final']; ?></td>
<td><?php echo $row['tipo_movimento']; ?></td>
<td><?php echo date("d/m/Y H:i", strtotime($row['data_movimentacao'])); ?></td>
</tr>

<?php
}
}
?>

</table>

</body>
</html>