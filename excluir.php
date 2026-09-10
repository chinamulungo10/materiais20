<?php
session_start();
require "proteger.php";
require "conexao.php";
require "log.php";

if ($_SESSION['nivel'] !== 'admin') {
    exit("Acesso negado.");
}

$id = intval($_GET['id']);

// Buscar antes de excluir
$stmt = $conn->prepare("SELECT * FROM materiais WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$material = $stmt->get_result()->fetch_assoc();

if (!$material) {
    die("Material não encontrado.");
}

// Excluir
$stmt = $conn->prepare("DELETE FROM materiais WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();


// 🔥 LOG
registrarLog(
    acao: "Exclusão de material",
    material_id: $id,
    quantidade: $material['quantidade']
);

header("Location: listar.php");
exit;