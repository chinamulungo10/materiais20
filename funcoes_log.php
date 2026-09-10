<?php
require_once "conexao.php";

function registrarLog($acao, $material_id = null, $quantidade = null) {
    global $conn;

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $usuario = $_SESSION['usuario'] ?? 'sistema';

    // 🔧 evitar problema com NULL
    if ($material_id === null) {
        $material_id = 0;
    }

    if ($quantidade === null) {
        $quantidade = 0;
    }

    $stmt = $conn->prepare("
        INSERT INTO logs (usuario, acao, material_id, quantidade)
        VALUES (?, ?, ?, ?)
    ");

    if (!$stmt) return false;

    $stmt->bind_param("ssii", $usuario, $acao, $material_id, $quantidade);
    return $stmt->execute();
}