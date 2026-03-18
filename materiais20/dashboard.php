<?php
require "proteger.php";
require "conexao.php";

if ($_SESSION['nivel'] !== 'admin') {
    http_response_code(403);
    exit("Acesso negado.");
}

/* =====================
   FILTROS
===================== */
$inicio   = $_GET['inicio']   ?? date('Y-m-01');
$fim      = $_GET['fim']      ?? date('Y-m-d');
$cliente  = trim($_GET['cliente'] ?? '');
$vendedor = $_GET['vendedor'] ?? '';

$inicioData = $inicio . " 00:00:00";
$fimData    = $fim    . " 23:59:59";

/* =====================
   LISTA DE VENDEDORES
===================== */
$vendedores = $conn->query("
    SELECT id, usuario 
    FROM usuarios 
    ORDER BY usuario
");

/* =====================
   CONSULTA PRINCIPAL
===================== */
$sql = "
    SELECT 
        v.id,
        v.cliente,
        v.data_venda,
        v.total,
        v.status,
        v.desconto,
        u.usuario
    FROM vendas v
    JOIN usuarios u ON u.id = v.usuario_id
    WHERE DATE(v.data_venda) BETWEEN ? AND ?
";

$params = [$inicioData, $fimData];
$types  = "ss";

if ($cliente !== '') {
    $sql .= " AND v.cliente LIKE ?";
    $params[] = "%$cliente%";
    $types   .= "s";
}

if ($vendedor !== '') {
    $sql .= " AND v.usuario_id = ?";
    $params[] = $vendedor;
    $types   .= "i";
}

$sql .= " ORDER BY v.data_venda DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

/* =====================
   TOTAL VENDAS CONCLUÍDAS
===================== */
$sqlTotalConcluidas = "
    SELECT COUNT(*) qtd, IFNULL(SUM(total),0) total
    FROM vendas
    WHERE status='concluida'
      AND DATE(data_venda) BETWEEN ? AND ?
";

$stmt = $conn->prepare($sqlTotalConcluidas);
$stmt->bind_param("ss", $inicioData, $fimData);
$stmt->execute();
$totalConcluidas = $stmt->get_result()->fetch_assoc();

/* =====================
   TOTAL CANCELADAS
===================== */
$sqlCanceladas = "
    SELECT COUNT(*) qtd, IFNULL(SUM(total),0) total
    FROM vendas
    WHERE status='cancelada'
      AND DATE(data_venda) BETWEEN ? AND ?
";

$stmt = $conn->prepare($sqlCanceladas);
$stmt->bind_param("ss", $inicioData, $fimData);
$stmt->execute();
$canceladasData = $stmt->get_result()->fetch_assoc();

/* =====================
   TOTAL COM DESCONTO
===================== */
$sqlDesconto = "
    SELECT COUNT(*) qtd, IFNULL(SUM(desconto),0) total
    FROM vendas
    WHERE desconto > 0
      AND DATE(data_venda) BETWEEN ? AND ?
";

$stmt = $conn->prepare($sqlDesconto);
$stmt->bind_param("ss", $inicioData, $fimData);
$stmt->execute();
$descontoData = $stmt->get_result()->fetch_assoc();

/* =====================
   RANKING CLIENTES
===================== */
$rankingClientes = $conn->prepare("
    SELECT cliente, COUNT(*) qtd, SUM(total) total
    FROM vendas
    WHERE status='concluida'
      AND DATE(data_venda) BETWEEN ? AND ?
    GROUP BY cliente
    ORDER BY total DESC
    LIMIT 5
");
$rankingClientes->bind_param("ss", $inicioData, $fimData);
$rankingClientes->execute();
$rankingClientes = $rankingClientes->get_result();

/* =====================
   RANKING VENDEDORES
===================== */
$rankingVendedores = $conn->prepare("
    SELECT u.usuario, COUNT(v.id) qtd, SUM(v.total) total
    FROM vendas v
    JOIN usuarios u ON u.id=v.usuario_id
    WHERE v.status='concluida'
      AND DATE(v.data_venda) BETWEEN ? AND ?
    GROUP BY u.id
    ORDER BY total DESC
    LIMIT 5
");
$rankingVendedores->bind_param("ss", $inicioData, $fimData);
$rankingVendedores->execute();
$rankingVendedores = $rankingVendedores->get_result();

/* =====================
   GRÁFICO
===================== */
$labels = [];
$valores = [];

$grafico = $conn->prepare("
    SELECT DATE(data_venda) dia, SUM(total) total
    FROM vendas
    WHERE status='concluida'
      AND DATE(data_venda) BETWEEN ? AND ?
    GROUP BY dia
");
$grafico->bind_param("ss", $inicioData, $fimData);
$grafico->execute();
$resGrafico = $grafico->get_result();

while ($g = $resGrafico->fetch_assoc()) {
    $labels[]  = $g['dia'];
    $valores[] = $g['total'];
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Dashboard de Vendas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.badge-cancelada { background-color: #dc3545; }
.badge-desconto { background-color: #ffc107; color: #000; }
</style>
</head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body class="container mt-4">

<h3 class="mb-4">📊 Dashboard de Vendas</h3>

<form class="row g-2 mb-4">
    <div class="col-md-3">
        <input type="date" name="inicio" class="form-control" value="<?= $inicio ?>">
    </div>
    <div class="col-md-3">
        <input type="date" name="fim" class="form-control" value="<?= $fim ?>">
    </div>
    <div class="col-md-3">
        <input type="text" name="cliente" class="form-control" placeholder="Nome do cliente" value="<?= htmlspecialchars($cliente) ?>"  autocomplete="off">
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary w-100">Filtrar</button>
    </div>

    <div class="col-md-3">
    <select name="vendedor" class="form-select">
        <option value="">Todos vendedores</option>
        <?php while($u = $vendedores->fetch_assoc()): ?>
            <option value="<?= $u['id'] ?>" <?= ($vendedor==$u['id'])?'selected':'' ?>>
                <?= htmlspecialchars($u['usuario']) ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>

</form>

<!-- ===================== TOTAl ===================== -->
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card text-center"><div class="card-body">
        <h6>Vendas Concluidas</h6>
        <h5>R$ <?= number_format($totalConcluidas['total'],2,',','.') ?> (<?= $totalConcluidas['qtd'] ?>)</h5>
    </div></div></div>

    <div class="col-md-4"><div class="card text-center"><div class="card-body">
        <h6>Vendas Canceladas</h6>
        <h5>R$ <?= number_format($canceladasData['total'],2,',','.') ?> (<?= $canceladasData['qtd'] ?>)</h5>
    </div></div></div>

    <div class="col-md-4"><div class="card text-center"><div class="card-body">
        <h6>Vendas com Desconto</h6>
        <h5>R$ <?= number_format($descontoData['total'],2,',','.') ?> (<?= $descontoData['qtd'] ?>)</h5>
    </div></div></div>
</div>

<div class="row mt-4">
<div class="col-md-6">
<div class="card">
<div class="card-header">🏆 Top Clientes</div>
<table class="table table-sm">
<?php while($c=$rankingClientes->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($c['cliente']) ?></td>
<td><?= $c['qtd'] ?></td>
<td>R$ <?= number_format($c['total'],2,',','.') ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>
</div>

<div class="col-md-6">
<div class="card">
<div class="card-header">🏅 Top Vendedores</div>
<table class="table table-sm">
<?php while($v=$rankingVendedores->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($v['usuario']) ?></td>
<td><?= $v['qtd'] ?></td>
<td>R$ <?= number_format($v['total'],2,',','.') ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>
</div>
</div>
<div class="card mt-4">
<div class="card-header">📈 Evolução de Vendas</div>
<div class="card-body">
<canvas id="graficoVendas"></canvas>
</div>
</div>

<script>
new Chart(document.getElementById('graficoVendas'),{
    type:'line',
    data:{
        labels:<?= json_encode($labels) ?>,
        datasets:[{
            label:'Vendas',
            data:<?= json_encode($valores) ?>,
            borderColor:'#0d6efd',
            fill:false
        }]
    }
});
</script>

<!-- ===================== TABELA VENDAS ===================== -->
<div class="card">
<div class="card-header">🧾 Histórico de Vendas</div>
<div class="card-body">
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
<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($venda = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $venda['id'] ?></td>
        <td><?= htmlspecialchars($venda['cliente']) ?></td>
        <td><?= htmlspecialchars($venda['usuario']) ?></td>
        <td><?= date('d/m/Y H:i', strtotime($venda['data_venda'])) ?></td>
        <td>
            <?= ucfirst($venda['status']) ?>
            <?php if($venda['desconto'] > 0): ?>
                <span class="badge badge-desconto">Desconto</span>
            <?php endif; ?>
            <?php if($venda['status'] === 'cancelada'): ?>
                <span class="badge badge-cancelada">Cancelada</span>
            <?php endif; ?>
        </td>
        <td>R$ <?= number_format($venda['total'], 2, ',', '.') ?></td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="6" class="text-center">Nenhuma venda encontrada para este período.</td>
</tr>
<a class="btn btn-danger mt-3"
href="pdf_rankings.php?inicio=<?= $inicio ?>&fim=<?= $fim ?>">
📄 PDF Rankings
</a>

<?php endif; ?>
</tbody>
</table>
</div>
</div>

</body>
</html>
