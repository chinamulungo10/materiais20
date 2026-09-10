<?php 
require "proteger.php";
require "conexao.php";


if (
    empty($_POST['id']) ||
    empty($_POST['codigo_barra']) ||
    empty($_POST['nome']) ||
    !isset($_POST['quantidade']) ||
    !isset($_POST['custo_compra']) ||
    !isset($_POST['custo_venda'])
) {
    die("Dados incompletos.");
}

$id            = intval($_POST['id']);
$codigo_barra  = trim($_POST['codigo_barra']);
$nome          = trim($_POST['nome']);
$quantidade    = intval($_POST['quantidade']);
$custo_compra  = floatval($_POST['custo_compra']);
$custo_venda   = floatval($_POST['custo_venda']);


// 🔍 BUSCAR DADOS ANTIGOS (NOVO)
$stmtOld = $conn->prepare("SELECT * FROM materiais WHERE id = ?");
$stmtOld->bind_param("i", $id);
$stmtOld->execute();
$antigo = $stmtOld->get_result()->fetch_assoc();


// Evitar código de barras duplicado em OUTRO produto
$stmt = $conn->prepare(
    "SELECT id FROM materiais 
     WHERE codigo_barra = ? AND id != ?"
);
$stmt->bind_param("si", $codigo_barra, $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    die("Erro: Código de barras já está em uso.");
}

// Atualizar material
$stmt = $conn->prepare("
    UPDATE materiais
    SET codigo_barra = ?, nome = ?, quantidade = ?, custo_compra = ?, custo_venda = ?
    WHERE id = ?
");

$stmt->bind_param(
    "ssiddi",
    $codigo_barra,
    $nome,
    $quantidade,
    $custo_compra,
    $custo_venda,
    $id
);

if (!$stmt->execute()) {
    die("Erro ao atualizar material.");
}


// 🔥 🧠 GERAR LOG (NOVO)
$alteracoes = [];

if ($antigo['nome'] != $nome) {
    $alteracoes[] = "Nome alterado";
}

if ($antigo['quantidade'] != $quantidade) {
    $alteracoes[] = "Quantidade alterada ({$antigo['quantidade']} → $quantidade)";
}

if ($antigo['custo_venda'] != $custo_venda) {
    $alteracoes[] = "Preço alterado";
}

if ($antigo['codigo_barra'] != $codigo_barra) {
    $alteracoes[] = "Código de barras alterado";
}

// Só grava se houve mudança
if (!empty($alteracoes)) {

    $usuario = $_SESSION['usuario'];
    $acao = "Editou: " . implode(", ", $alteracoes);

    $stmtLog = $conn->prepare("
        INSERT INTO logs (usuario, acao, material_id, quantidade)
        VALUES (?, ?, ?, ?)
    ");

    $stmtLog->bind_param(
        "ssii",
        $usuario,
        $acao,
        $id,
        $quantidade
    );

    $stmtLog->execute();
}


header("Location: listar.php?edit=1");
exit;