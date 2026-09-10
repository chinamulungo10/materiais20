<?php
session_start();
include "conexao.php";

$id_devedor = intval($_GET['id'] ?? 0);

if ($id_devedor <= 0) {
    die("ID da dívida inválido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_devedor = intval($_POST['id_devedor'] ?? 0);
    $usuario = trim($_POST['usuario_admin'] ?? '');
    $senha = $_POST['senha_admin'] ?? '';
    

    if ($id_devedor <= 0) {
        die("ID da dívida inválido.");
    }

    if ($usuario === '' || $senha === '') {
        die("Usuário e senha são obrigatórios.");
    }

    // Buscar usuário
    $stmt = $conn->prepare("
        SELECT id, senha, nivel, tentativas, bloqueado_ate
        FROM usuarios
        WHERE usuario = ?
        LIMIT 1
    ");

    $stmt->bind_param("s", $usuario);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>
            alert('Usuário não encontrado.');
            window.history.back();
        </script>";
        exit;
    }

    $user = $result->fetch_assoc();

    // Verificar bloqueio
    if (
        !empty($user['bloqueado_ate']) &&
        strtotime($user['bloqueado_ate']) > time()
    ) {
        die("Usuário bloqueado temporariamente.");
    }

    // Verificar senha
    if (!password_verify($senha, $user['senha'])) {

        $tentativas = $user['tentativas'] + 1;
        $bloqueado_ate = NULL;

        if ($tentativas >= 3) {
            $bloqueado_ate = date(
                "Y-m-d H:i:s",
                strtotime("+5 minutes")
            );
        }

        $stmt = $conn->prepare("
            UPDATE usuarios
            SET tentativas = ?, bloqueado_ate = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "isi",
            $tentativas,
            $bloqueado_ate,
            $user['id']
        );

        $stmt->execute();

        echo "<script>
            alert('Senha incorreta.');
            window.history.back();
        </script>";
        exit;
    }

    // Verificar se é administrador
    if ($user['nivel'] !== 'admin') {
        echo "<script>
            alert('Apenas administradores podem editar dívidas.');
            window.history.back();
        </script>";
        exit;
    }
   // Registra na sessão quem autorizou a edição
$_SESSION['usuario_admin'] = $usuario;
$_SESSION['edicao_divida_autorizada'] = true;

// Envia para a página de edição
header("Location: /materiais20/editar_devedor.php?id=" . $id_devedor);
exit;

    // Resetar tentativas
    $stmt = $conn->prepare("
        UPDATE usuarios
        SET tentativas = 0, bloqueado_ate = NULL
        WHERE id = ?
    ");

    $stmt->bind_param("i", $user['id']);
    $stmt->execute();

    // Acesso autorizado
    header(
        "Location: editar_devedor.php?id=" . $id_devedor
    );
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Autorização de Administrador</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;

            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .caixa {
            width: 420px;
            max-width: 95%;

            background: white;

            padding: 30px;

            border-radius: 15px;

            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        h2 {
            margin-top: 0;
            text-align: center;
            color: #1e293b;
        }

        .descricao {
            text-align: center;
            color: #64748b;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            color: #334155;
        }

        input {
            width: 100%;
            padding: 12px;

            border: 1px solid #cbd5e1;
            border-radius: 8px;

            margin-bottom: 18px;

            font-size: 15px;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .botoes {
            display: flex;
            gap: 10px;
        }

        button,
        .cancelar {
            flex: 1;

            padding: 12px;

            border: none;
            border-radius: 8px;

            cursor: pointer;

            text-align: center;

            text-decoration: none;

            font-size: 15px;
        }

        button {
            background: #2563eb;
            color: white;
        }

        button:hover {
            background: #1d4ed8;
        }

        .cancelar {
            background: #e2e8f0;
            color: #334155;
        }

        .cancelar:hover {
            background: #cbd5e1;
        }

    </style>

</head>

<body>

<div class="caixa">

    <h2>🔐 Autorização necessária</h2>

    <p class="descricao">
        Para editar esta dívida, informe as credenciais de um administrador.
    </p>

    <form method="POST">

        <input
            type="hidden"
            name="id_devedor"
            value="<?= $id_devedor ?>"
        >

        <label>Usuário Admin</label>

        <input
            type="text"
            name="usuario_admin"
            placeholder="Digite o usuário"
            required
            autocomplete="username"
        >

        <label>Senha Admin</label>

        <input
            type="password"
            name="senha_admin"
            placeholder="Digite a senha"
            required
            autocomplete="current-password"
        >

        <div class="botoes">

            <a
                href="devedores.php"
                class="cancelar"
            >
                Voltar
            </a>

            <button type="submit">
                🔓 Autorizar edição
            </button>

        </div>

    </form>

</div>

</body>
</html>