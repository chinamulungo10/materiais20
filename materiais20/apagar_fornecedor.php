<?php

include "conexao.php";

$id = $_GET['id'];

$conn->query("DELETE FROM fornecedores WHERE id_fornecedor=$id");

header("Location: fornecedores.php");

?>