<?php
include "conexao.php";
require_once "proteger.php";

if (!isset($_SESSION['admin_confirmado'])) {
    header("Location: pedir_confirmacao.php?redirect=adicionar_pagamento.php&id=".$_GET['id']);
    exit;
}

$id = $_GET['id'];

if(isset($_POST['pagar'])){

$valor = $_POST['valor'];

$sql = "INSERT INTO pagamentos_divida
(id_devedor,valor_pago,data_pagamento)
VALUES
('$id','$valor',NOW())";

$conn->query($sql);

header("Location: devedores.php");
}
?>

<form method="POST">

<h2>Registrar Pagamento</h2>

Valor pago

<input type="number" step="0.01" name="valor" required>

<button name="pagar">Salvar pagamento</button>

</form>