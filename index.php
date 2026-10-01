<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">
<title>Loja Swasswa</title>
<link rel="stylesheet" href="index.css">

</head>

<body>

<header>
<button class="menu-btn" onclick="toggleMenu()">☰</button>
<h1>Mercado Swasswa Pemba</h1>
</header>

<nav id="menu" class="menu">

<a href="caixa.php">💰 Caixa</a>
<a href="vender.php">🛒 Vender</a>
<a href="listar_estatus_venda.php">📄 Estatus Vda | 2ª V.fatura</a>
<a href="listar.php">📦 Lista de Materiais</a>
<a href="dashboard.php">📊 Gráfico e Histórico Geral</a>
<a href="cadastrar_material.php">➕ Cadastrar Material</a>
<a href="historico_estoque.php">📚 Histórico Estoque</a>
<a href="fornecedores.php">🚚 Fornecedores</a>
<a href="log.php">👨🏿‍💻 Log_Vendas | Acções </a>
<a href="devedores.php">💳 Detalhes Devedores pdf</a>
<a href="gerar_senha_hash.php">🔑 Criar senha</a>
<a href="log_divida.php">🔑 Log divida</a>
<a href="contatos.php">📞 Contactos</a>

</nav>

<main>
<h2>Bem-vindo!</h2>
<p>Loja Swasswa 🏡</p>
</main>

<script>

function toggleMenu(){
  document.getElementById("menu").classList.toggle("active");
}

</script>

</body>
</html>