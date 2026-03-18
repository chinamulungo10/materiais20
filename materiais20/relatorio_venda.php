<?php
require_once "proteger.php";
require_once "conexao.php";
require "fpdf/fpdf.php";

if ($_SESSION['nivel'] !== 'admin') {
    http_response_code(403);
    exit("Acesso negado.");
}

$inicio = $_GET['inicio'] ?? '';
$fim    = $_GET['fim'] ?? '';

// Totais
$totalPeriodo = 0;      // Somente vendas concluídas
$totalCanceladas = 0;   // Somente canceladas
$totalDesconto = 0;     // Somente o valor do desconto
$vendas = [];

if ($inicio && $fim) {
    $sql = "
        SELECT 
            v.id,
            v.cliente,
            v.data_venda,
            v.total,
            v.status,
            v.desconto,
            u.usuario AS usuario
        FROM vendas v
        JOIN usuarios u ON u.id = v.usuario_id
        WHERE DATE(v.data_venda) BETWEEN ? AND ?
        ORDER BY v.data_venda DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $inicio, $fim);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($venda = $result->fetch_assoc()) {
        // Somente vendas concluídas entram no total
        if ($venda['status'] === 'concluida') {
            $totalPeriodo += $venda['total'];
        }

        // Canceladas
        if ($venda['status'] === 'cancelada') {
            $totalCanceladas += $venda['total'];
        }

        // Soma somente o valor do desconto concedido
        if (!empty($venda['desconto']) && $venda['desconto'] > 0) {
            $totalDesconto += $venda['desconto'];
        }

        $vendas[] = $venda;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatório de Vendas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h4>Relatório de Vendas por Período</h4>

<form class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="date" name="inicio" class="form-control" value="<?= htmlspecialchars($inicio) ?>" required>
    </div>
    <div class="col-md-4">
        <input type="date" name="fim" class="form-control" value="<?= htmlspecialchars($fim) ?>" required>
    </div>
    <div class="col-md-4">
        <button class="btn btn-primary w-100">Buscar</button>
    </div>
</form>

<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Cliente</th>
    <th>Usuário</th> 
    <th>Data</th>
    <th>Status</th>
    <th>Total</th>
</tr>
</thead>

<tbody>

<?php if (!empty($vendas)): ?>
    <?php foreach ($vendas as $venda): ?>
    <tr>
        <td><?= $venda['id'] ?></td>
        <td><?= htmlspecialchars($venda['cliente'] ?? '—') ?></td>
        <td><?= htmlspecialchars($venda['usuario']) ?></td>
        <td><?= date('d/m/Y H:i', strtotime($venda['data_venda'])) ?></td>
        <td><?= ucfirst($venda['status']) ?></td>
        <td>R$ <?= number_format($venda['total'], 2, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="6" class="text-center">Nenhuma venda encontrada para este período.</td>
</tr>
<?php endif; ?>

</tbody>
<tfoot>
<tr>
    <th colspan="5">TOTAL DO PERÍODO (Concluídas)</th>
    <th>R$ <?= number_format($totalPeriodo, 2, ',', '.') ?></th>
</tr>
<tr>
    <th colspan="5">TOTAL CANCELADAS</th>
    <th>R$ <?= number_format($totalCanceladas, 2, ',', '.') ?></th>
</tr>
<tr>
    <th colspan="5">TOTAL DESCONTOS CONCEDIDOS</th>
    <th>R$ <?= number_format($totalDesconto, 2, ',', '.') ?></th>
</tr>

</tfoot>

</table>

<?php if ($inicio && $fim): ?>
<a class="btn btn-danger mt-3"
   href="relatorio_periodo_vendas.php?inicio=<?= htmlspecialchars($inicio) ?>&fim=<?= htmlspecialchars($fim) ?>">
   Gerar PDF
</a>
<?php endif; ?>

</body>
</html>
