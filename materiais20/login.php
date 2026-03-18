<?php
session_start();


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

require "conexao.php";


$erro = "";

/* =========================
   PROCESSA LOGIN (POST)
========================= */
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = $_POST['senha'] ?? '';

    if ($usuario === "" || $senha === "") {
        $_SESSION['erro_login'] = "Preencha todos os campos.";
        header("Location: login.php");
        exit;
    }

    $stmt = $conn->prepare("
        SELECT id, usuario, senha, nivel 
        FROM usuarios 
        WHERE usuario = ?
        LIMIT 1
    ");

    if (!$stmt) {
        $_SESSION['erro_login'] = "Erro interno no sistema.";
        header("Location: login.php");
        exit;
    }

    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $u = $result->fetch_assoc();

        if (password_verify($senha, $u['senha'])) {

            // 🔐 Segurança
            session_regenerate_id(true);

            $_SESSION['logado']     = true;
            $_SESSION['usuario']    = $u['usuario'];
            $_SESSION['usuario_id'] = $u['id'];
            $_SESSION['nivel']      = $u['nivel'];

           $destino = $_SESSION['redirect'] ?? 'caixa.php';
            unset($_SESSION['redirect']);

            // 🚫 Evita loop para login.php
            if (strpos($destino, 'login.php') !== false) {
                $destino = 'caixa.php';
            }

            header("Location: $destino");
            exit;


        } else {
            $_SESSION['erro_login'] = "Senha incorreta.";
        }

    } else {
        $_SESSION['erro_login'] = "Usuário não encontrado.";
    }

    header("Location: login.php");
    exit;
}

/* =========================
   MOSTRA FORMULÁRIO (GET)
========================= */
if (isset($_SESSION['erro_login'])) {
    $erro = $_SESSION['erro_login'];
    unset($_SESSION['erro_login']);
}

require "login_html_view.php";
