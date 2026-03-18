<?php
$loja = [
    'nome' => 'Loja Swasswa',
    'descricao' => 'VENDA DE MATERIAIS DE CONSTRUCAO',
    'telefone' => '+258 844388815',
    'email' => 'chafimchinamulungo@gmail.com',
    'cidade' => 'Pemba - Expansao - Moçambique'
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?php echo $loja['nome']; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="topo">
    <button class="menu-btn" onclick="toggleMenu()">☰</button>
    <div>
        <h1><?php echo $loja['nome']; ?></h1>
        <small><?php echo $loja['descricao']; ?></small>
    </div>
</header>

<nav id="menu" class="menu">
    <a href="index.php">Início</a>
    <a href="contatos.php">Contactos</a>
</nav>
</html>
