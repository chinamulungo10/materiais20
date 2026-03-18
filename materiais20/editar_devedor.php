<?php
include "conexao.php";

$id = $_GET['id'];

$sql = "SELECT * FROM devedores WHERE id_devedor=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if(isset($_POST['salvar'])){

    $cliente = $_POST['cliente'];
    $telefone = $_POST['telefone'];
    $material = $_POST['material'];
    $valor = $_POST['valor'];
    $vencimento = $_POST['vencimento'];

    $sql = "UPDATE devedores SET
    nome_cliente='$cliente',
    telefone='$telefone',
    material='$material',
    valor_divida='$valor',
    data_vencimento='$vencimento'
    WHERE id_devedor=$id";

    $conn->query($sql);

    header("Location: devedores.php");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Devedor</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
        }

        header {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #2c3e50;
            padding: 10px;
            color: white;
        }

        h2 {
            margin: 0;
            font-size: 24px;
        }

        .container {
            width: 400px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .input-group input, .input-group textarea, .input-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .input-group input:focus, .input-group textarea:focus, .input-group select:focus {
            border-color: #3498db;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #27ae60;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #219150;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Responsividade */
        @media (max-width: 600px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<header>
    <h2>Editar Devedor</h2>
</header>

<div class="container">

    <form method="POST">

        <div class="input-group">
            <label for="cliente">Cliente</label>
            <input type="text" name="cliente" value="<?php echo $row['nome_cliente']; ?>" required>
        </div>

        <div class="input-group">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" value="<?php echo $row['telefone']; ?>" required>
        </div>

        <div class="input-group">
            <label for="material">Material</label>
            <textarea name="material" required><?php echo $row['material']; ?></textarea>
        </div>

        <div class="input-group">
            <label for="valor">Valor</label>
            <input type="number" name="valor" value="<?php echo $row['valor_divida']; ?>" required>
        </div>

        <div class="input-group">
            <label for="vencimento">Data de Vencimento</label>
            <input type="date" name="vencimento" value="<?php echo $row['data_vencimento']; ?>" required>
        </div>

        <button type="submit" name="salvar">Salvar</button>

    </form>

    <a href="devedores.php" class="back-link">Voltar</a>

</div>

</body>
</html>