<?php
include("conexao.php");

// Query
$sql = "SELECT 
m.nome,
e.estoque_anterior,
e.quantidade_adicionada,
e.estoque_final,
e.tipo_movimento,
e.data_movimentacao
FROM movimentacao_estoque e
LEFT JOIN materiais m ON m.id = e.material_id
ORDER BY e.data_movimentacao DESC";

$result = $conn->query($sql);

// Verificar erro SQL
if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Histórico de Estoque</title>
<link rel="stylesheet" href="historico_estoque.css">

</head>

<body>

<h2>Histórico de Movimentação de Estoque</h2>

<table>

<tr>
<th>Produto</th>
<th>Estoque Anterior</th>
<th>Quantidade</th>
<th>Estoque Final</th>
<th>Tipo</th>
<th>Data</th>
</tr>

<?php
if($result->num_rows > 0){

    while($row = $result->fetch_assoc()){
?>

<tr>
<td><?php echo $row['nome'] ?? 'Sem nome'; ?></td>
<td><?php echo $row['estoque_anterior']; ?></td>
<td><?php echo $row['quantidade_adicionada']; ?></td>
<td><?php echo $row['estoque_final']; ?></td>
<td><?php echo $row['tipo_movimento']; ?></td>
<td><?php echo date("d/m/Y H:i", strtotime($row['data_movimentacao'])); ?></td>
</tr>

<?php
    }

} else {
?>

<tr>
<td colspan="6" class="sem-dados">Nenhuma movimentação encontrada</td>
</tr>

<?php
}
?>

</table>

</body>
</html>