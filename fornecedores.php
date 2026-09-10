<?php 
require "conexao.php";

?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Fornecedores</title>
<link rel="stylesheet" href="fornecedores.css">
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h3>📦 Sistema</h3>
        <a href="devedores.php">💲 Dashboard devedores</a>
        <a href="fornecedores.php" class="active">👥 Fornecedores</a>
        <a href="listar.php">📋 Produtos</a>
    </aside>

    <!-- CONTEÚDO -->
    <main class="main">

        <!-- TOPO -->
        <div class="top-bar">
            <h2>👥 Gestão de Fornecedores</h2>

            <div class="top-actions">
                <input type="text" id="busca" placeholder="Buscar por ID, nome, telefone ou material...">
                <a class="btn novo" href="cadastrar_fornecedor.php">➕ Novo</a>
            </div>
        </div>

        <!-- TABELA DINÂMICA -->
        <div class="container">
            <div id="tabela"></div>
        </div>

    </main>

</div>

<!-- ✅ SCRIPT NO FINAL -->
<script>
function carregar(pagina = 1){
    let busca = document.getElementById("busca").value;

    fetch(`buscar_fornecedor.php?q=${busca}&pagina=${pagina}`)
    .then(res => res.text())
    .then(data => {
        document.getElementById("tabela").innerHTML = data;
    });
}

function confirmarDelete(id) {
    if (confirm("Tem certeza que deseja deletar este fornecedor?")) {
        window.location.href = "deletar_fornecedor.php?id=" + id;
    }
}
/* BUSCA COM DELAY (PROFISSIONAL) */
let tempo;
document.getElementById("busca").addEventListener("keyup", () => {
    clearTimeout(tempo);
    tempo = setTimeout(() => {
        carregar(1);
    }, 400);
});

/* CARREGA AO ABRIR */
carregar();
</script>

</body>
</html>