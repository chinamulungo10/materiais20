<?php
include "conexao.php";
session_start();

$usuario = $_POST['usuario_admin'];
$senha = $_POST['senha_admin'];

// Buscar usuário
$stmt = $conn->prepare("
    SELECT id, senha, nivel 
    FROM usuarios 
    WHERE usuario = ?
");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows == 0){
    die("Usuário não encontrado");
}

$user = $res->fetch_assoc();

// Verificar senha
if(!password_verify($senha, $user['senha'])){
    die("Senha incorreta");
}

// Verificar admin
if($user['nivel'] !== 'admin'){
    die("Apenas admin pode executar esta ação");
}

// Autorização válida
$_SESSION['admin_confirmado'] = true;

// Redireciona para ação
header("Location: ".$_POST['redirect']."?id=".$_POST['id']);
exit;