<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Loja Swasswa</title>

<style>

body{
  margin:0;
  font-family:Arial, sans-serif;
}

/* HEADER */
header{
  display:flex;
  align-items:center;
  background:#14df58;
  color:#0d09e9;
  padding:10px 15px;
  position:sticky;
  top:0;
  z-index:1000;
}

.menu-btn{
  font-size:26px;
  background:none;
  border:none;
  color:white;
  cursor:pointer;
  margin-right:15px;
}

/* MENU LATERAL */
.menu{
  position:fixed;
  top:0;
  left:-250px;
  width:230px;
  height:100vh;
  background:#2c3e50;
  padding-top:60px;
  transition:0.3s;
  display:flex;
  flex-direction:column;

  overflow-y:auto; /* SCROLL AUTOMÁTICO */
}

/* Scroll mais bonito */
.menu::-webkit-scrollbar{
  width:6px;
}

.menu::-webkit-scrollbar-thumb{
  background:#888;
  border-radius:10px;
}

/* LINKS DO MENU */
.menu a{
  color:white;
  padding:14px 18px;
  text-decoration:none;
  font-size:15px;
  border-bottom:1px solid rgba(255,255,255,0.1);
  transition:0.2s;
}

.menu a:hover{
  background:#34495e;
  padding-left:25px;
}

/* MENU ATIVO */
.menu.active{
  left:0;
}

/* CONTEÚDO */
main{
  padding:20px;
}

</style>
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
<a href="gerar_senha_hash.php">🔑 Criar senha</a>
<a href="historico_estoque.php">📚 Histórico Estoque</a>
<a href="fornecedores.php">🚚 Fornecedores</a>
<a href="cadastrar_fornecedor.php">➕ Cadastrar Fornecedor</a>
<a href="devedores.php">💳 Devedores</a>
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