<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST['nome']);
    $cnpj_cpf = trim($_POST['cnpj_cpf']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
     $endereco = trim($_POST['endereco'] ?? '');
    $cidade = trim($_POST['cidade']);
    $estado = trim($_POST['estado']);
    $tipo_material = trim($_POST['tipo_material']);
    $data_cadastro = $_POST['data_cadastro'] ?? date('Y-m-d');

    // Verificar duplicidade
    $sql_verifica = "SELECT id_fornecedor FROM fornecedores WHERE nome = ? OR telefone = ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param("ss", $nome, $telefone);
    $stmt_verifica->execute();
    $result_verifica = $stmt_verifica->get_result();

      if (empty($nome) || empty($telefone)) {
        die("Preencha os campos obrigatórios!");
}

    if ($result_verifica->num_rows > 0) {
        echo "<script>alert('Fornecedor já cadastrado!');</script>";
    } else {

    $sql = "INSERT INTO fornecedores 
(nome, cnpj_cpf, telefone, email, endereco, cidade, estado, data_cadastro, tipo_material)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}

$stmt->bind_param(
    "sssssssss",
    $nome,
    $cnpj_cpf,
    $telefone,
    $email,
    $endereco,
    $cidade,
    $estado,
    $data_cadastro,
    $tipo_material
);

if ($stmt->execute()) {
    header("Location: fornecedores.php");
    exit;
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

        $stmt->bind_param(
             "sssssssss", $nome, $cnpj_cpf, $telefone, $email, $endereco, $cidade, $estado, $data_cadastro, $tipo_material);

        if ($stmt->execute()) {
            header("Location: fornecedores.php");
            exit;
        } else {
            echo "Erro ao cadastrar!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cadastrar Fornecedor</title>

</head>

<body>

<div class="container">

<h2>Novo Fornecedor</h2>

<form method="POST">

<input type="text" name="nome" placeholder="Nome do fornecedor" required>

<input type="text" name="cnpj_cpf" placeholder="CNPJ ou CPF ou NUIT">

<input type="text" name="telefone" placeholder="Telefone" required>

<input type="email" name="email" placeholder="Email">
<input type="text" name="endereco" placeholder="Endereço">

<input type="text" name="cidade" placeholder="Cidade">

<input type="text" name="estado" placeholder="Estado/Província">

<input type="date" name="data_cadastro">

<input type="text" name="tipo_material" placeholder="Materiais (ex: Areia, Cimento, Brita)">

<button type="submit">Cadastrar</button>
<button onclick="confirmarDelete(<?= $id ?>)">Apagar</button>

</form>

<a class="voltar" href="fornecedores.php">← Voltar</a>
<link rel="stylesheet" href="cadastrar_fornecedor.css">

</div>


</body>
</html>