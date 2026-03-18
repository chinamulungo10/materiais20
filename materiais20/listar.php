<?php
session_start();
require_once "proteger.php";
require_once "conexao.php";



if ($_SESSION['nivel'] !== 'admin') {
    http_response_code(403);
    exit("Acesso negado.");
}

$buscar = $_GET['buscar'] ?? "";
$baixoEstoque = $_GET['baixo_estoque'] ?? false;

if ($baixoEstoque) {

    // 🔴 Somente baixo estoque
    $stmt = $conn->prepare(
        "SELECT * FROM materiais
         WHERE quantidade <= 100
         ORDER BY quantidade ASC"
    );
    $stmt->execute();
    $result = $stmt->get_result();

} elseif ($buscar != "") {

    if (is_numeric($buscar)) {
        $stmt = $conn->prepare(
            "SELECT * FROM materiais
             WHERE id = ? OR codigo_barra = ?"
        );
        $stmt->bind_param("is", $buscar, $buscar);
    } else {
        $like = "%$buscar%";
        $stmt = $conn->prepare(
            "SELECT * FROM materiais
             WHERE nome LIKE ?"
        );
        $stmt->bind_param("s", $like);
    }

    $stmt->execute();
    $result = $stmt->get_result();

} else {

    // 🔵 Listagem normal
    $result = $conn->query(
        "SELECT * FROM materiais ORDER BY id DESC"
    );
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Materiais</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Materiais</h4>
    <div>
        Usuário: <strong><?= $_SESSION['usuario'] ?></strong>
        (<?= $_SESSION['nivel'] ?>)
        <a href="logout.php" class="btn btn-danger btn-sm ms-2">Sair</a>
    </div>
</div>

<!-- 🔎 BUSCA + FILTROS -->
<form class="row g-2 mb-3">

    <div class="col-md-4">
        <input class="form-control"
               name="buscar"
               value="<?= htmlspecialchars($buscar) ?>"
               placeholder="Buscar por nome, ID ou código de barras">
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">
            🔍 Buscar
        </button>
    </div>

    <div class="col-md-2">
        <a href="listar.php" class="btn btn-secondary w-100">
            🔄 Limpar
        </a>
    </div>

    <div class="col-md-3">
        <a href="listar.php?baixo_estoque=1"
           class="btn btn-warning w-100">
            ⚠️ Baixo Estoque
        </a>
    </div>

</form>

<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Cód. Barra</th>
    <th>Nome</th>
    <th>Qtd</th>
    <th>Compra</th>
    <th>Venda</th>
    <th>Ações</th>
</tr>
</thead>

<tbody>
<?php while ($m = $result->fetch_assoc()): ?>
<tr class="<?= $m['quantidade'] <= 100 ? 'table-danger' : '' ?>">
    <td><?= $m['id'] ?></td>
    <td><?= $m['codigo_barra'] ?></td>
    <td><?= $m['nome'] ?></td>
    <td><?= $m['quantidade'] ?></td>
    <td>R$ <?= number_format($m['custo_compra'],2,',','.') ?></td>
    <td>R$ <?= number_format($m['custo_venda'],2,',','.') ?></td>
    <td>
        <?php if ($_SESSION['nivel'] === 'admin'): ?>
            <a class="btn btn-warning btn-sm"
               href="editar.php?id=<?= $m['id'] ?>">
               Editar
            </a>
        <?php endif; ?>

        <a class="btn btn-success btn-sm"
           href="vender.php?id=<?= $m['id'] ?>">
           Vender
        </a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</div>
</body>
</html>
