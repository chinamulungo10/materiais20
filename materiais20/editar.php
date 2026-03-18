<?php
require "proteger.php";
require "conexao.php";
require "log.php";

if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['nivel'] != 'admin') {
    die("Acesso negado! Somente administrador.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM materiais WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$m = $stmt->get_result()->fetch_assoc();

if (!$m) {
    die("Material não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Material</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5">
<div class="col-md-6 mx-auto bg-white p-4 shadow rounded">

<h4 class="mb-4">Editar Material</h4>

<form action="atualizar.php" method="POST">

    <input type="hidden" name="id" value="<?= $m['id'] ?>">

    <!-- CÓDIGO DE BARRAS -->
    <div class="mb-3">
        <label class="form-label">Código de Barras</label>
        <input type="text" name="codigo_barra" class="form-control"
               value="<?= $m['codigo_barra'] ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nome do Material</label>
        <input type="text" name="nome" class="form-control"
               value="<?= $m['nome'] ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Quantidade em Estoque</label>
        <input type="number" name="quantidade" class="form-control"
               min="0"
               value="<?= $m['quantidade'] ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Custo de Compra</label>
        <input type="number" step="0.01" name="custo_compra" class="form-control"
               min="0"
               value="<?= $m['custo_compra'] ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Preço de Venda</label>
        <input type="number" step="0.01" name="custo_venda" class="form-control"
               min="0"
               value="<?= $m['custo_venda'] ?>" required>
    </div>

    <button class="btn btn-primary w-100">Salvar Alterações</button>

</form>

</div>
</div>
</body>
</html>

