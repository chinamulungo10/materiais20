<?php
session_start();
require_once "proteger.php";
require_once "conexao.php";
require "log.php";

if ($_SESSION['nivel'] !== 'admin') {
    http_response_code(403);
    exit("Acesso negado.");
}


// Buscar todas as vendas
$result = $conn->query("SELECT * FROM vendas ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Listar Vendas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script>
function cancelarVenda(id) {
    const motivo = prompt("Motivo do cancelamento:");
    if (!motivo) return;

    window.location.href =
        "cancelar_venda.php?id=" + id + "&motivo=" + encodeURIComponent(motivo);
}
</script>

</head>
<body class="bg-light">

<div class="container mt-4">
<h3>Vendas Registradas</h3>

<div class="mb-3">
    <span class="me-2">
        Usuário: <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong>
        (<?= htmlspecialchars($_SESSION['nivel']) ?>)
    </span>
    <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
</div>

<table class="table table-bordered">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Cliente</th>
<th>Data</th>
<th>Status</th>
<th>Itens</th>
<th>Total</th>
<th>Ações</th>
</tr>
</thead>
<tbody>
<?php while($venda = $result->fetch_assoc()): ?>
<tr class="<?= $venda['status'] === 'cancelada' ? 'table-secondary' : '' ?>">
    <td><?= $venda['id'] ?></td>
    <td><?= htmlspecialchars($venda['cliente'] ?? '—') ?></td>
    <td><?= $venda['data_venda'] ?></td>
    <td><?= ucfirst($venda['status']) ?></td>
    <td>
        <?php
        $totalVenda = 0;
        $itensRes = $conn->query("
            SELECT i.*, m.nome, m.custo_venda AS preco_unitario
            FROM itens_venda i
            JOIN materiais m ON i.material_id = m.id
            WHERE venda_id = ".$venda['id']
        );

        while($item = $itensRes->fetch_assoc()) {
            $preco = isset($item['preco_unitario']) ? $item['preco_unitario'] : 0;
            $totalItem = $preco * $item['quantidade'];
            $totalVenda += $totalItem;
            echo htmlspecialchars($item['nome'])." (".$item['quantidade']." x R$ ".number_format($preco,2,',','.').")<br>";
        }
        ?>
    </td>
    <td>R$ <?= number_format($totalVenda,2,',','.') ?></td>
    <td>
        <?php if($venda['status'] !== 'cancelada'): ?>
            <button class="btn btn-danger btn-sm mb-1" onclick="cancelarVenda(<?= $venda['id'] ?>)">Cancelar</button>
        <?php else: ?>
            <span class="text-muted mb-1 d-block">Venda cancelada</span>
        <?php endif; ?>
        <form action="gerar_pdf_venda.php" method="POST" style="display:inline;">
            <input type="hidden" name="venda_id" value="<?= $venda['id'] ?>">
            <button type="submit" class="btn btn-primary btn-sm">Gerar PDF</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<a href="listar.php" class="btn btn-secondary">Voltar</a>
</div>

</body>
</html>
