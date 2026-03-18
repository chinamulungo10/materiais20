<?php
include "conexao.php";

// Obter ID do devedor
$id = $_GET['id'];

// Verificar se o pagamento foi enviado
if (isset($_POST['salvar_pagamento'])) {

    $valor_pago = $_POST['valor_pago'];
    $data_pagamento = $_POST['data_pagamento'];

    // Registrar o pagamento na tabela pagamentos_divida
    $sql = "INSERT INTO pagamentos_divida (id_devedor, valor_pago, data_pagamento)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dss", $id, $valor_pago, $data_pagamento);
    $stmt->execute();
    $stmt->close();

    // Redirecionar para a página de devedores
    header("Location: devedores.php");
    exit;
}

// Obter informações do devedor para exibir no formulário
$sql = "SELECT * FROM devedores WHERE id_devedor = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Pagamento - Devedor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
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

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
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
    </style>
</head>
<body>

<div class="container">

    <h2>Adicionar Pagamento para <?php echo $row['nome_cliente']; ?></h2>

    <form method="POST">
        <div class="input-group">
            <label for="valor_pago">Valor do Pagamento</label>
            <input type="number" name="valor_pago" step="0.01" required>
        </div>

        <div class="input-group">
            <label for="data_pagamento">Data do Pagamento</label>
            <input type="date" name="data_pagamento" required>
        </div>

        <button type="submit" name="salvar_pagamento">Salvar Pagamento</button>
    </form>

</div>

</body>
</html>