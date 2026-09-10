<?php
session_start();

$redirect = $_GET['redirect'];
$id = $_GET['id'];
?>

<form method="POST" action="confirmar_admin.php">
    <input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <label>Usuário Admin</label>
    <input type="text" name="usuario_admin" required>

    <label>Senha</label>
    <input type="password" name="senha_admin" required>

    <button type="submit">Confirmar</button>
    <a href="pedir_confirmacao.php?redirect=adicionar_pagamento.php&id=<?php echo $row['id_devedor']; ?>">
</form>