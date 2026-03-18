<?php
session_start();
require "proteger.php";
require "conexao.php";
require "fpdf/fpdf.php";

if ($_SESSION['nivel'] !== 'admin') {
    exit("Acesso negado.");
}

$cliente = $_GET['cliente'] ?? '';
if (empty($cliente)) {
    exit("Cliente não informado.");
}

$stmt = $conn->prepare("
    SELECT 
        v.id,
        v.cliente,
        v.data_venda,
        v.status,
        (
            SELECT SUM(i.quantidade * m.custo_venda)
            FROM itens_venda i
            JOIN materiais m ON m.id = i.material_id
            WHERE i.venda_id = v.id
        ) AS total
    FROM vendas v
    WHERE v.cliente LIKE ?
    ORDER BY v.data_venda DESC
");

$like = "%$cliente%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $pdf->SetFont("Arial", "", 12);
    $pdf->Cell(0, 10, utf8_decode("Nenhuma venda encontrada."), 0, 1, "C");
    $pdf->Output();
    exit;
}


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial", "B", 14);

$pdf->Cell(0, 10, utf8_decode("Histórico de Vendas - $cliente"), 0, 1, "C");
$pdf->Ln(5);

$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(20, 8, "ID", 1);
$pdf->Cell(60, 8, "Data", 1);
$pdf->Cell(40, 8, "Total", 1);
$pdf->Cell(30, 8, "Status", 1);
$pdf->Ln();

$pdf->SetFont("Arial", "", 10);

while ($v = $result->fetch_assoc()) {
    $pdf->Cell(20, 8, "#".$v['id'], 1);
    $pdf->Cell(60, 8, date("d/m/Y H:i", strtotime($v['data_venda'])), 1);
    $pdf->Cell(40, 8, "R$ ".number_format($v['total'] ?? 0, 2, ',', '.'), 1);
    $pdf->Cell(30, 8, ucfirst($v['status']), 1);
    $pdf->Ln();
}

$pdf->Output("I", "historico_$cliente.pdf");
exit;
