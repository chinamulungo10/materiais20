<?php
include "conexao.php";

$hoje = date("Y-m-d");
$filtro = $_GET['filtro'] ?? '';
$busca = $_GET['busca'] ?? '';

$sql = "
SELECT 
d.id_devedor,
d.nome_cliente,
d.telefone,
d.material,
d.valor_divida,
d.data_divida,
d.data_vencimento,
IFNULL(SUM(p.valor_pago),0) as total_pago
FROM devedores d
LEFT JOIN pagamentos_divida p
ON d.id_devedor = p.id_devedor
WHERE d.status_divida != 'CANCELADA'
";

$params = [];
$types = "";

/* busca por cliente */
if($busca != ""){
    $sql .= " AND d.nome_cliente LIKE ?";
    $params[] = "%$busca%";
    $types .= "s";
}

/* filtro vencidas */
if($filtro == "vencidas"){
    $sql .= " AND d.data_vencimento < ?";
    $params[] = $hoje;
    $types .= "s";
}

$sql .= "
GROUP BY d.id_devedor
ORDER BY d.data_vencimento
";

$stmt = $conn->prepare($sql);

/* bind dinâmico */
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Totais do painel
$total_vencido = 0;
$total_divida = 0;
$total_pago = 0;
$total_clientes = 0;
$vencidas = 0;

$devedores = [];

while($row = $result->fetch_assoc()){
    $row['valor_restante'] = $row['valor_divida'] - $row['total_pago'];

    if($row['data_vencimento'] < $hoje && $row['valor_restante'] > 0){
        $vencidas++;
        $total_vencido += $row['valor_restante'];
    }

    $total_divida += $row['valor_divida'];
    $total_pago += $row['total_pago'];
    $total_clientes++;

    $devedores[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Controle de Devedores</title>
<link rel="stylesheet" href="devedor.css">
<script>
function confirmarCancelamento(){
    return confirm("Tem certeza que deseja cancelar esta dívida⚠️?");
}
</script>
</head>
<body>

<header>
    <div class="topo-acoes">
        <a href="cadastrar_devedor.php" class="btn-novo">➕ Novo Devedor</a>
    </div>
    <h1>Controle de Devedores</h1>
</header>

<!-- Alerta de dívidas vencidas -->
<?php if($vencidas > 0){ ?>
<div class="alerta-vencidas">
    ⚠️ ATENÇÃO: Existem <?php echo $vencidas; ?> dívidas vencidas
    Total: R$ <?php echo number_format($total_vencido,2,',','.'); ?>
</div>
<?php } ?>

<!-- Painel de resumo -->
<div class="painel">
    <a href="devedores.php?filtro=abertas"><div class="card divida">
        <h3>Total em dívida</h3>
        <p>R$ <?php echo number_format($total_divida,2,',','.'); ?></p>
    </div></a>
    <a href="devedores.php?filtro=pagas"><div class="card pago">
        <h3>Total pago</h3>
        <p>R$ <?php echo number_format($total_pago,2,',','.'); ?></p>
    </div></a>
    <a href="devedores.php"><div class="card clientes">
        <h3>Clientes devedores</h3>
        <p><?php echo $total_clientes; ?></p>
    </div></a>
    <a href="devedores.php?filtro=vencidas"><div class="card vencidas">
        <h3>Dívidas vencidas</h3>
        <p><?php echo $vencidas; ?></p>
        <small>R$ <?php echo number_format($total_vencido,2,',','.'); ?></small>
    </div></a>
</div>

<!-- Campo de busca -->
<form method="GET" class="busca-form">
    <input type="text" name="busca" placeholder="Buscar cliente por nome..." value="<?php echo htmlspecialchars($busca); ?>">
    <button type="submit">Buscar</button>
</form>

<!-- Botão mostrar todas -->
<?php if(isset($_GET['filtro']) || $busca != ""){ ?>
<div class="mostrar-todas">
    <a href="devedores.php">Mostrar todas as dívidas</a>
</div>
<?php } ?>

<!-- Tabela de devedores -->
<table>
<tr>
<th>ID</th>
<th>Cliente</th>
<th>Telefone</th>
<th>Material</th>
<th>Valor Total</th>
<th>Total Pago</th>
<th>Valor Restante</th>
<th>Vencimento</th>
<th>Status</th>
<th>Ações</th>
</tr>

<?php foreach($devedores as $row): 
    if ($row['valor_restante'] <= 0) {
        $status = "PAGO";
    } elseif ($row['data_vencimento'] < $hoje) {
        $status = "VENCIDO";
    } elseif ($row['total_pago'] > 0) {
        $status = "PARCIAL";
    } else {
        $status = "ABERTO";
    }

    $classe = ($status=="VENCIDO") ? "vencido" : "";
?>
<tr class="<?php echo $classe; ?>">
<td><?php echo $row['id_devedor']; ?></td>
<td><?php echo $row['nome_cliente']; ?></td>
<td><?php echo $row['telefone']; ?></td>
<td class="material"><?php echo $row['material']; ?></td>
<td>R$ <?php echo number_format($row['valor_divida'],2,',','.'); ?></td>
<td>R$ <?php echo number_format($row['total_pago'],2,',','.'); ?></td>
<td>R$ <?php echo number_format($row['valor_restante'],2,',','.'); ?></td>
<td><?php echo date("d/m/Y", strtotime($row['data_vencimento'])); ?></td>
<td><?php echo $status; ?></td>
<td class="actions">
    <a href='editar_devedor.php?id=<?php echo $row['id_devedor']; ?>' class='btn-editar'>Editar</a>
    <form method="POST" action="cancelar_divida.php" style="display:inline;" onsubmit="return confirmarCancelamento();">
        <input type="hidden" name="id_devedor" value="<?php echo $row['id_devedor']; ?>">
        <input type="password" name="senha_admin" placeholder="Senha Admin" required>
        <button type="submit" class="btn-apagar">Cancelar Dívida❌</button>
    </form>
    <a href='adicionar_pagamento.php?id=<?php echo $row['id_devedor']; ?>' class='btn-pagar'>Adicionar Pagamento➕</a>
    <a href='gerar_pdf_divida.php?id=<?php echo $row['id_devedor']; ?>' target="_blank" class='btn-pdf'>Baixar PDF</a>
    <a href="https://wa.me/55<?php echo $row['telefone']; ?>?text=<?php echo urlencode(
        "Olá ".$row['nome_cliente'].
        ", estamos entrando em contato sobre sua dívida no valor de R$ ".
        number_format($row['valor_restante'],2,',','.').
        " com vencimento em ".
        date("d/m/Y", strtotime($row['data_vencimento'])).". Por favor regularize. Obrigado."
    ); ?>" target="_blank" class="btn-whatsapp">Cobrar WhatsApp</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<!-- Gráfico -->
<canvas id="graficoFinanceiro" width="350" height="350" style="display:block;margin:40px auto;"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('graficoFinanceiro');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Total em Dívida','Total Pago','Valor Vencido'],
        datasets: [{
            data: [
                <?php echo $total_divida; ?>,
                <?php echo $total_pago; ?>,
                <?php echo $total_vencido; ?>
            ],
            backgroundColor:['#e74c3c','#27ae60','#f39c12'],
            borderWidth:1
        }]
    },
    options:{
        responsive:true,
        plugins:{
            legend:{position:'bottom'}
        }
    }
});
</script>

</body>
</html>