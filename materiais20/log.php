
<?php
require_once "conexao.php";

function registrarLog(
    string $acao,
    ?int $material_id = null,
    ?int $quantidade = null
) {
    global $conn;

    // Garante sessão ativa
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Usuário vem da sessão (mais seguro)
    $usuario = $_SESSION['usuario'] ?? 'sistema';

    $stmt = $conn->prepare("
        INSERT INTO logs (usuario, acao, material_id, quantidade)
        VALUES (?, ?, ?, ?)
    ");

    if (!$stmt) {
        // opcional: registrar erro em arquivo
        return false;
    }

    $stmt->bind_param(
        "ssii",
        $usuario,
        $acao,
        $material_id,
        $quantidade
    );

    return $stmt->execute();
}
