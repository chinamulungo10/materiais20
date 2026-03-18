<?php
include "conexao.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

$id_devedor = intval($_POST['id_devedor']);
$senha_admin = $_POST['senha_admin'];

// Buscar senha do admin
$sql = "SELECT senha FROM usuarios WHERE nivel='admin' LIMIT 1";
$result = $conn->query($sql);

if($result->num_rows == 0){
die("Admin não encontrado");
}

$row = $result->fetch_assoc();
$hash = $row['senha'];

// Verificar senha
if(!password_verify($senha_admin, $hash)){
echo "<script>alert('Senha incorreta'); window.history.back();</script>";
exit;
}

// Cancelar dívida
$sql = "UPDATE devedores SET status_divida='CANCELADA' WHERE id_devedor=$id_devedor";

if($conn->query($sql)){
echo "<script>alert('Dívida cancelada com sucesso'); window.location='devedores.php';</script>";
}else{
echo "Erro: ".$conn->error;
}

}
?>