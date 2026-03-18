<?php

include "conexao.php";

$id = $_GET['id'];

$sql = "UPDATE devedores SET status='PAGO' WHERE id_devedor=$id";

$conn->query($sql);

header("Location: devedores.php");

?>