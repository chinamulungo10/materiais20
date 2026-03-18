<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$nivel = $_SESSION['nivel'] ?? '';

// Verifica se há caixa aberto
$stmt = $conn->prepare("
    SELECT id FROM caixa
    WHERE usuario_id = ?
      AND status = 'aberto'
    LIMIT 1
");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->store_result();

// Se vendedor e caixa aberto → força fechamento
if ($stmt->num_rows > 0 && $nivel !== 'admin') {
    $_SESSION['erro_caixa'] = "Você tem um caixa aberto! Feche-o antes de sair.";
    header("Location: caixa.php");
    exit;
}

// Admin ou sem caixa aberto → logout normal
$_SESSION = [];
session_destroy();
header("Location: login.php");
exit;
