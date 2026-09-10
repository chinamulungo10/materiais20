<?php

require_once "proteger.php";
include "conexao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ==========================================================
// OBTER ID DA DÍVIDA
// ==========================================================

$id_devedor = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($id_devedor <= 0) {
    header("Location: /materiais20/devedores.php");
    exit;
}


// ==========================================================
// PROCESSAR AUTORIZAÇÃO
// ==========================================================

if (isset($_POST['autorizar'])) {

    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];


    if ($usuario === '' || $senha === '') {

        $erro = "Informe o usuário e a senha do administrador.";

    } else {

        // Buscar usuário
        $stmt = $conn->prepare("
            SELECT 
                id,
                usuario,
                senha,
                nivel,
                bloqueado_ate
            FROM usuarios
            WHERE usuario = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $usuario);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $admin = $resultado->fetch_assoc();

        $stmt->close();


        if (!$admin) {

            $erro = "Usuário ou senha incorretos.";

        } else {

            // Verificar bloqueio
            if (
                !empty($admin['bloqueado_ate']) &&
                strtotime($admin['bloqueado_ate']) > time()
            ) {

                $erro = "Este usuário está temporariamente bloqueado.";

            } elseif (
                strtolower($admin['nivel']) !== 'admin' &&
                strtolower($admin['nivel']) !== 'administrador'
            ) {

                $erro = "Apenas um administrador pode autorizar o pagamento.";

            } elseif (!password_verify($senha, $admin['senha'])) {

                $erro = "Usuário ou senha incorretos.";

            } else {

                // ==================================================
                // AUTORIZAÇÃO CONCEDIDA
                // ==================================================

                $_SESSION['pagamento_divida_autorizado'] = true;

                // Guardar quem autorizou
                $_SESSION['usuario_admin_pagamento'] = $admin['usuario'];

                // Redirecionar para adicionar pagamento
                header(
                    "Location: /materiais20/adicionar_pagamento.php?id="
                    . $id_devedor
                );

                exit;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Autorização - Pagamento</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .aviso {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
        }

        .erro {
            background: #f8d7da;
            color: #842029;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: #198754;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #157347;
        }

        .voltar {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #555;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>🔐 Autorização necessária</h2>

    <div class="aviso">
        Para registrar um pagamento, é necessária a autorização de um administrador.
    </div>


    <?php if (isset($erro)): ?>

        <div class="erro">
            <?= htmlspecialchars($erro) ?>
        </div>

    <?php endif; ?>


    <form method="POST">

        <label for="usuario">
            Usuário do Administrador
        </label>

        <input
            type="text"
            id="usuario"
            name="usuario"
            required
        >


        <label for="senha">
            Senha do Administrador
        </label>

        <input
            type="password"
            id="senha"
            name="senha"
            required
        >


        <button
            type="submit"
            name="autorizar"
        >
            🔓 Autorizar Pagamento
        </button>

    </form>


    <a
        href="/materiais20/devedores.php"
        class="voltar"
    >
        ← Voltar
    </a>

</div>

</body>

</html>