<?php
include "conexao.php";

// 🔒 Validar ID
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    die("ID inválido");
}

$id = $_GET['id'];

// 🔍 Buscar fornecedor
$stmt = $conn->prepare("SELECT * FROM fornecedores WHERE id_fornecedor = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if(!$row){
    die("Fornecedor não encontrado");
}

// 🔄 Atualizar
if(isset($_POST['atualizar'])){

    $nome = trim($_POST['nome']);
    $cnpj = trim($_POST['cnpj']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $endereco = trim($_POST['endereco']);
    $cidade = trim($_POST['cidade']);
    $estado = trim($_POST['estado']);
    $tipo_material = trim($_POST['tipo_material']);

    // 🚫 Verificar duplicidade (exceto o próprio ID)
    $sql_verifica = "SELECT id_fornecedor FROM fornecedores 
    WHERE (nome = ? OR telefone = ?) AND id_fornecedor != ?";
    
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param("ssi", $nome, $telefone, $id);
    $stmt_verifica->execute();
    $result_verifica = $stmt_verifica->get_result();

    if($result_verifica->num_rows > 0){
        echo "<script>alert('Já existe fornecedor com este nome ou telefone!');</script>";
    } else {

        // ✅ Atualizar com segurança
        $sql = "UPDATE fornecedores SET
        nome=?,
        cnpj_cpf=?,
        telefone=?,
        email=?,
        endereco=?,
        cidade=?,
        estado=?,
        tipo_material=?
        WHERE id_fornecedor=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", 
            $nome, $cnpj, $telefone, $email, 
            $endereco, $cidade, $estado, $tipo_material, $id
        );

        if($stmt->execute()){
            header("Location: fornecedores.php");
            exit;
        } else {
            echo "Erro ao atualizar!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Editar Fornecedor</title>

<style>
body{
    font-family:Arial;
    background:#f4f4f4;
}

.container{
    width:400px;
    margin:auto;
    background:white;
    padding:20px;
    border-radius:8px;
    margin-top:30px;
}

h2{
    text-align:center;
}

input{
    width:100%;
    padding:8px;
    margin:5px 0;
}

button{
    width:100%;
    padding:10px;
    background:#f39c12;
    color:white;
    border:none;
    border-radius:5px;
}

.voltar{
    display:block;
    text-align:center;
    margin-top:10px;
}
</style>

</head>

<body>

<div class="container">

<h2>Editar Fornecedor</h2>

<form method="POST">

<label>Nome</label>
<input type="text" name="nome" value="<?php echo $row['nome']; ?>" required>

<label>CNPJ / CPF</label>
<input type="text" name="cnpj" value="<?php echo $row['cnpj_cpf']; ?>">

<label>Telefone</label>
<input type="text" name="telefone" value="<?php echo $row['telefone']; ?>" required>

<label>Email</label>
<input type="email" name="email" value="<?php echo $row['email']; ?>">

<label>Endereço</label>
<input type="text" name="endereco" value="<?php echo $row['endereco']; ?>">

<label>Cidade</label>
<input type="text" name="cidade" value="<?php echo $row['cidade']; ?>">

<label>Estado</label>
<input type="text" name="estado" value="<?php echo $row['estado']; ?>">

<label>Materiais fornecidos</label>
<input type="text" name="tipo_material" value="<?php echo $row['tipo_material']; ?>">

<button name="atualizar">Atualizar Fornecedor</button>

</form>

<a class="voltar" href="fornecedores.php">← Voltar</a>

</div>

</body>
</html>