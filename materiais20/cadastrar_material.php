<?php
require "proteger.php";

bloquearNivel(['admin']);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastrar Material</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
</head>

<body class="bg-light">

<div class="card shadow-sm" style="max-width: 420px; width:100%;">

    <!-- 🔹 Cabeçalho -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Cadastrar Material</strong>

        <a href="logout.php"
           class="btn btn-outline-danger btn-sm"
           onclick="return confirm('Deseja realmente sair do sistema?')">
            🚪 Sair
        </a>
    </div>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success text-center m-2">
            ✅ Material cadastrado com sucesso!
        </div>
    <?php endif; ?>

    <div class="card-body">

        <form action="salvar_material.php" method="POST">

            <div class="mb-2">
                <input class="form-control form-control-sm"
                       name="codigo_barra"
                       placeholder="Código de barras"
                       required>
            </div>

            <div class="mb-2">
                <input class="form-control form-control-sm"
                       name="nome"
                       placeholder="Nome do material"
                       required>
            </div>

            <div class="mb-2">
                <input class="form-control form-control-sm"
                       name="cor"
                       placeholder="Cor">
            </div>

            <div class="row g-2 mb-2">
                <div class="col">
                    <input class="form-control form-control-sm"
                           name="peso"
                           type="number"
                           step="0.01"
                           min="0"
                           placeholder="Peso (kg)">
                </div>

                <div class="col">
                    <input class="form-control form-control-sm"
                           name="altura"
                           type="number"
                           step="0.01"
                           min="0"
                           placeholder="Altura (cm)">
                </div>
            </div>

            <div class="mb-2">
                <input class="form-control form-control-sm"
                       name="largura"
                       type="number"
                       step="0.01"
                       min="0"
                       placeholder="Largura (cm)">
            </div>

            <div class="mb-2">
                <input class="form-control form-control-sm"
                       name="quantidade"
                       type="number"
                       min="0"
                       placeholder="Quantidade"
                       required>
            </div>

            <div class="mb-2">
                <input class="form-control form-control-sm"
                       name="custo_compra"
                       type="number"
                       step="0.01"
                       min="0"
                       placeholder="Custo de compra"
                       required>
            </div>

            <div class="mb-3">
                <input class="form-control form-control-sm"
                       name="custo_venda"
                       type="number"
                       step="0.01"
                       min="0"
                       placeholder="Custo de venda"
                       required>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-success btn-sm">
                    💾 Salvar Material
                </button>

                <a href="listar.php" class="btn btn-secondary btn-sm">
                    ⬅ Voltar
                </a>
            </div>

        </form>

    </div>
</div>

</body>
</html>
   