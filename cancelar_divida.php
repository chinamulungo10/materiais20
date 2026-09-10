<?php

session_start();
include "conexao.php";
require_once "proteger.php";


// =====================================================
// ACEITAR SOMENTE POST
// =====================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /materiais20/devedores.php");
    exit;
}


// =====================================================
// RECEBER DADOS
// =====================================================

$id_devedor = intval($_POST['id_devedor'] ?? 0);
$usuario = trim($_POST['usuario_admin'] ?? '');
$senha = $_POST['senha_admin'] ?? '';

if ($id_devedor <= 0) {
    die("ID da dívida inválido.");
}

if ($usuario === '' || $senha === '') {
    die("Usuário e senha são obrigatórios.");
}


// =====================================================
// BUSCAR USUÁRIO
// =====================================================

$stmt = $conn->prepare("
    SELECT id, senha, nivel, tentativas, bloqueado_ate
    FROM usuarios
    WHERE usuario = ?
    LIMIT 1
");

$stmt->bind_param("s", $usuario);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {

    echo "<script>
        alert('Usuário não encontrado.');
        window.history.back();
    </script>";

    exit;
}

$user = $result->fetch_assoc();


// =====================================================
// VERIFICAR SE USUÁRIO ESTÁ BLOQUEADO
// =====================================================

if (
    !empty($user['bloqueado_ate']) &&
    strtotime($user['bloqueado_ate']) > time()
) {

    echo "<script>
        alert('Usuário bloqueado temporariamente.');
        window.history.back();
    </script>";

    exit;
}


// =====================================================
// VERIFICAR SENHA
// =====================================================

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


// =====================================================
// VERIFICAR SE É ADMINISTRADOR
// =====================================================

if ($user['nivel'] !== 'admin') {

    echo "<script>
        alert('Apenas administradores podem cancelar dívida.');
        window.history.back();
    </script>";

    exit;
}


// =====================================================
// RESETAR TENTATIVAS
// =====================================================

$stmt = $conn->prepare("
    UPDATE usuarios
    SET tentativas = 0,
        bloqueado_ate = NULL
    WHERE id = ?
");

$stmt->bind_param("i", $user['id']);
$stmt->execute();


// =====================================================
// BUSCAR DADOS DA DÍVIDA ANTES DE CANCELAR
// =====================================================

$stmt = $conn->prepare("
    SELECT
        id_devedor,
        nome_cliente,
        valor_divida,
        data_divida,
        data_vencimento,
        status
    FROM devedores
    WHERE id_devedor = ?
    LIMIT 1
");

$stmt->bind_param("i", $id_devedor);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {

    echo "<script>
        alert('Dívida não encontrada.');
        window.location='/materiais20/devedores.php';
    </script>";

    exit;
}

$divida = $result->fetch_assoc();


// =====================================================
// VERIFICAR SE JÁ ESTÁ CANCELADA
// =====================================================

if ($divida['status'] === 'CANCELADA') {

    echo "<script>
        alert('Esta dívida já está cancelada.');
        window.location='/materiais20/devedores.php';
    </script>";

    exit;
}


// =====================================================
// CANCELAR A DÍVIDA
// =====================================================

$stmt = $conn->prepare("
    UPDATE devedores
    SET status = 'CANCELADA'
    WHERE id_devedor = ?
");

$stmt->bind_param("i", $id_devedor);


if ($stmt->execute()) {

    // =================================================
    // REGISTRAR LOG
    // =================================================

    $descricao = "Dívida cancelada. Cliente: "
        . $divida['nome_cliente']
        . ". Valor da dívida: R$ "
        . number_format(
            $divida['valor_divida'],
            2,
            ',',
            '.'
        )
        . ". Status anterior: "
        . $divida['status']
        . ".";

    $valor_anterior = $divida['valor_divida'];
    $valor_novo = 0;

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Desconhecido';

    $acao = "CANCELou";

    $stmt_log = $conn->prepare("
        INSERT INTO log_dividas (
            id_devedor,
            usuario_admin,
            acao,
            descricao,
            valor_anterior,
            valor_novo,
            data_hora,
            ip
        )
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)
    ");

    $stmt_log->bind_param(
        "isssdds",
        $id_devedor,
        $usuario,
        $acao,
        $descricao,
        $valor_anterior,
        $valor_novo,
        $ip
    );

    $stmt_log->execute();


    // =================================================
    // MENSAGEM DE SUCESSO
    // =================================================

    echo "<script>
        alert('Dívida cancelada com sucesso.');
        window.location='/materiais20/devedores.php';
    </script>";

    exit;

} else {

    echo "Erro ao cancelar dívida: " . $stmt->error;
}

?>