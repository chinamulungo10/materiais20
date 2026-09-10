<?php
require "conexao.php";

$id = intval($_POST['id']);
$qtd = intval($_POST['qtd']);

$conn->query("
UPDATE materiais 
SET quantidade = quantidade - $qtd 
WHERE id = $id AND quantidade >= $qtd
");

header("Location: gerar_pdf_venda.php");

// 1. Buscar estoque atual
$sql = "SELECT quantidade FROM materiais WHERE id = $material_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();
    $estoque_anterior = $row['quantidade'];

    $quantidade_saida = $quantidade_vendida;

    // 🚨 Validação (muito importante)
    if ($quantidade_saida > $estoque_anterior) {
        die("Erro: estoque insuficiente!");
    }

    $estoque_final = $estoque_anterior - $quantidade_saida;

    // 2. Atualiza estoque
    $conn->query("UPDATE materiais 
                  SET quantidade = $estoque_final 
                  WHERE id = $material_id");

    // 3. Salva histórico
    $conn->query("INSERT INTO movimentacao_estoque 
    (material_id, estoque_anterior, quantidade_adicionada, estoque_final, tipo_movimento, data_movimentacao)
    VALUES 
    ('$material_id', '$estoque_anterior', '$quantidade_saida', '$estoque_final', 'Saída', NOW())");

} else {
    die("Material não encontrado!");
}
