<?php
include "conexao.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $id_devedor = intval($_POST['id_devedor']);
    $senha_admin = $_POST['senha_admin'];

    // Buscar admin na tabela de usuários
    $sql = "SELECT senha FROM usuarios WHERE nivel='admin' LIMIT 1"; // ajuste nome da tabela se necessário
    $result = $conn->query($sql);

    if($result->num_rows == 0){
        die("Nenhum admin encontrado no sistema.");
    }

    $row = $result->fetch_assoc();
    $hash = $row['senha'];

    // Verifica se a senha digitada bate com o hash
    if(!password_verify($senha_admin, $hash)){
        die("<script>alert('Senha incorreta. Exclusão não realizada.'); window.history.back();</script>");
    }

    // Se a senha estiver correta, apagar a dívida
    $sql = "DELETE FROM devedores WHERE id_devedor = $id_devedor";
    if($conn->query($sql)){
        echo "<script>alert('Dívida apagada com sucesso'); window.location='devedores.php';</script>";
    } else {
        echo "Erro ao apagar: ".$conn->error;
    }
}
?>