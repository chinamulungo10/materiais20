<?php
include "conexao.php";

if (!isset($_GET['id'])) {
    die("ID não informado!");
}

$id = intval($_GET['id']);

if(isset($_GET['id'])){

    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM fornecedores WHERE id_fornecedor = ?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        header("Location: fornecedores.php");
    } else {
        echo "Erro ao excluir!";
    }
}
