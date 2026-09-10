<?php
session_start();
require_once "proteger.php";
require_once "conexao.php";

if ($_SESSION['nivel'] !== 'admin') {
    http_response_code(403);
    exit("Acesso negado.");
}

// filtros
$busca = $_GET['busca'] ?? '';
$dataInicio = $_GET['data_inicio'] ?? '';
$dataFim = $_GET['data_fim'] ?? '';

// query base
$sql = "
SELECT 
    logs.*, 
    materiais.nome AS material_nome
FROM logs
LEFT JOIN materiais ON logs.material_id = materiais.id
WHERE 1=1
";

// filtros dinâmicos
if (!empty($busca)) {
    $busca = $conn->real_escape_string($busca);
    $sql .= " AND (logs.usuario LIKE '%$busca%' OR logs.acao LIKE '%$busca%')";
}

if (!empty($dataInicio)) {
    $sql .= " AND DATE(logs.data_hora) >= '$dataInicio'";
}

if (!empty($dataFim)) {
    $sql .= " AND DATE(logs.data_hora) <= '$dataFim'";
}

$sql .= " ORDER BY logs.data_hora DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Logs do Sistema</title>
<link rel="stylesheet" href="log.css">
</head>

<body>

<div class="container">
<h2>Logs do Sistema</h2>

<form method="GET">
    <input type="text" name="busca" placeholder="Usuário ou ação"
        value="<?= htmlspecialchars($busca) ?>">

    <input type="date" name="data_inicio" value="<?= $dataInicio ?>">
    <input type="date" name="data_fim" value="<?= $dataFim ?>">

    <button type="submit">Filtrar</button>
    <a href="log.php"><button type="button">Limpar</button></a>
</form>
<div class="table-container">
<table>
<tr>
    
    <th>ID</th>
    <th>Usuário</th>
    <th>Ação</th>
    <th>Material</th>
    <th>Quantidade</th>
    <th>Data</th>
    <th>Detalhes</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>

<?php
$venda_id = null;

if (preg_match('/Venda\s+(\d+)/i', $row['acao'], $matches)) {
    $venda_id = (int)$matches[1];
}
?>

<tr>

    <td><?= (int)$row['id'] ?></td>

    <td>
        <?= htmlspecialchars($row['usuario']) ?>
    </td>

    <td>
        <?= htmlspecialchars($row['acao']) ?>
    </td>

    <td>
        <?= htmlspecialchars($row['material_nome'] ?? 'N/A') ?>
    </td>

    <td>
        <?= (int)$row['quantidade'] ?>
    </td>

    <td>
        <?= date('d/m/Y H:i', strtotime($row['data_hora'])) ?>
    </td>

    <td>

        <?php if ($venda_id): ?>

            <a href="detalhes_venda.php?id=<?= $venda_id ?>"
               class="btn-detalhes">
                Ver venda
            </a>

        <?php else: ?>

            —

        <?php endif; ?>

    </td>

</tr>

<?php endwhile; ?>

</table>
</div>
</div>

</body>
</html>
