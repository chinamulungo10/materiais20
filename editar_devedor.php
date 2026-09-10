<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "conexao.php";

/*
 * Verifica se a edição foi autorizada pelo administrador
 */
if (
    !isset($_SESSION['edicao_divida_autorizada']) ||
    $_SESSION['edicao_divida_autorizada'] !== true
) {
    header("Location: /materiais20/devedores.php");
    exit;
}


/*
 * Verifica se recebeu o ID
 */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /materiais20/devedores.php");
    exit;
}

$id = (int) $_GET['id'];


/*
 * Busca a dívida
 */
$stmt = $conn->prepare("
    SELECT *
    FROM devedores
    WHERE id_devedor = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Dívida não encontrada.");
}

$row = $result->fetch_assoc();

$stmt->close();


/*
 * Quando clicar em SALVAR
 */
if (isset($_POST['salvar'])) {

    $cliente = trim($_POST['cliente']);
    $telefone = trim($_POST['telefone']);
    $material = trim($_POST['material']);
    $valor = (float) $_POST['valor'];
    $vencimento = $_POST['vencimento'];


    /*
     * Guarda os valores antigos
     */
    $cliente_antigo = $row['nome_cliente'];
    $telefone_antigo = $row['telefone'];
    $material_antigo = $row['material'];
    $valor_antigo = (float) $row['valor_divida'];
    $vencimento_antigo = $row['data_vencimento'];


    /*
     * Identifica quais campos foram alterados
     */
    $alteracoes = [];


    if ($cliente != $cliente_antigo) {
        $alteracoes[] =
            "Cliente: \"$cliente_antigo\" → \"$cliente\"";
    }


    if ($telefone != $telefone_antigo) {
        $alteracoes[] =
            "Telefone: \"$telefone_antigo\" → \"$telefone\"";
    }


    if ($material != $material_antigo) {
        $alteracoes[] =
            "Material alterado";
    }


    if ($valor != $valor_antigo) {
        $alteracoes[] =
            "Valor: " .
            number_format($valor_antigo, 2, ',', '.') .
            " → " .
            number_format($valor, 2, ',', '.');
    }


    if ($vencimento != $vencimento_antigo) {
        $alteracoes[] =
            "Vencimento: \"$vencimento_antigo\" → \"$vencimento\"";
    }


    /*
     * Se nada foi alterado
     */
    if (empty($alteracoes)) {

        $_SESSION['edicao_divida_autorizada'] = false;
        unset($_SESSION['usuario_admin']);

        header("Location: /materiais20/devedores.php");
        exit;
    }


    /*
     * Atualiza a dívida
     */
    $stmt = $conn->prepare("
        UPDATE devedores SET
            nome_cliente = ?,
            telefone = ?,
            material = ?,
            valor_divida = ?,
            data_vencimento = ?
        WHERE id_devedor = ?
    ");

    $stmt->bind_param(
        "sssdsi",
        $cliente,
        $telefone,
        $material,
        $valor,
        $vencimento,
        $id
    );


    if (!$stmt->execute()) {

        die("Erro ao atualizar a dívida: " . $stmt->error);

    }

    $stmt->close();


    /*
     * Nome do administrador que autorizou a edição
     */
    $usuario_admin = $_SESSION['usuario_admin'] ?? 'Desconhecido';


    /*
     * Descrição do que foi alterado
     */
    $descricao =
        "Dívida do cliente \"$cliente_antigo\" editada. " .
        implode(" | ", $alteracoes);


    /*
     * IP do computador
     */
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;


    /*
     * Registra a alteração no log
     */
    $stmt_log = $conn->prepare("
        INSERT INTO log_dividas
        (
            id_devedor,
            usuario_admin,
            acao,
            descricao,
            valor_anterior,
            valor_novo,
            data_hora,
            ip
        )
        VALUES
        (?, ?, ?, ?, ?, ?, NOW(), ?)
    ");


    $acao = "EDITOU";


    $stmt_log->bind_param(
        "isssdds",
        $id,
        $usuario_admin,
        $acao,
        $descricao,
        $valor_antigo,
        $valor,
        $ip
    );


    if (!$stmt_log->execute()) {

        die("A dívida foi atualizada, mas ocorreu um erro ao registrar o log: " . $stmt_log->error);

    }

    $stmt_log->close();


    /*
     * Remove a autorização depois da edição
     */
    $_SESSION['edicao_divida_autorizada'] = false;
    unset($_SESSION['usuario_admin']);


    /*
     * Volta para a página de dívidas
     */
    header("Location: /materiais20/devedores.php");
    exit;
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

        .input-group input,
        .input-group textarea,
        .input-group select {

            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;

        }

        .input-group input:focus,
        .input-group textarea:focus,
        .input-group select:focus {

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

            <label for="cliente">
                Cliente
            </label>

            <input
                type="text"
                name="cliente"
                value="<?= htmlspecialchars($row['nome_cliente']) ?>"
                required
            >

        </div>


        <div class="input-group">

            <label for="telefone">
                Telefone
            </label>

            <input
                type="text"
                name="telefone"
                value="<?= htmlspecialchars($row['telefone']) ?>"
                required
            >

        </div>


        <div class="input-group">

            <label for="material">
                Material
            </label>

            <textarea
                name="material"
                required
            ><?= htmlspecialchars($row['material']) ?></textarea>

        </div>


        <div class="input-group">

            <label for="valor">
                Valor
            </label>

            <input
                type="number"
                step="0.01"
                name="valor"
                value="<?= htmlspecialchars($row['valor_divida']) ?>"
                required
            >

        </div>


        <div class="input-group">

            <label for="vencimento">
                Data de Vencimento
            </label>

            <input
                type="date"
                name="vencimento"
                value="<?= htmlspecialchars($row['data_vencimento']) ?>"
                required
            >

        </div>


        <button type="submit" name="salvar">
            Salvar
        </button>

    </form>


    <a href="/materiais20/devedores.php" class="back-link">
        Voltar
    </a>

</div>

</body>

</html>