<?php
session_start();
require_once "proteger.php";
require_once "conexao.php";
require "fpdf/fpdf.php";

if ($_SESSION['nivel'] !== 'admin') {
    http_response_code(403);
    exit("Acesso negado.");
}

$inicio = $_GET['inicio'] ?? '';
$fim    = $_GET['fim'] ?? '';

$totalPeriodo     = 0; // soma total das vendas concluídas
$totalCanceladas  = 0; // soma vendas canceladas
$totalDesconto    = 0; // soma SOMENTE o valor do desconto
$vendas = [];

if ($inicio && $fim) {

    $sql = "
        SELECT 
            v.id,
            v.cliente,
            v.data_venda,
            v.total,
            v.status,
            v.desconto,
            u.usuario AS usuario
        FROM vendas v
        JOIN usuarios u ON u.id = v.usuario_id
        WHERE DATE(v.data_venda) BETWEEN ? AND ?
        ORDER BY v.data_venda DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $inicio, $fim);
    $stmt->execute();
    $result = $stmt->get_result();

   while ($venda = $result->fetch_assoc()) {

    if ($venda['status'] === 'concluida') {
        $totalPeriodo += $venda['total'];
    }

    if ($venda['status'] === 'cancelada') {
        $totalCanceladas += $venda['total'];
    }

    // SOMA APENAS O DESCONTO (CORREÇÃO PRINCIPAL)
    if (!empty($venda['desconto']) && $venda['desconto'] > 0) {
        $totalDesconto += $venda['desconto'];
    }

    $vendas[] = $venda;
}
}

// Agora $totalPeriodo + $totalComDesconto é o total real de faturamento, canceladas separadas
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,"Relatorio de Vendas: $inicio a $fim",0,1,'C');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,8,'ID',1);
$pdf->Cell(50,8,'Cliente',1);
$pdf->Cell(30,8,'Usuario',1);
$pdf->Cell(30,8,'Data',1);
$pdf->Cell(30,8,'Status',1);
$pdf->Cell(30,8,'Total',1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);
foreach ($vendas as $venda) {
    $pdf->Cell(10,8,$venda['id'],1);
    $pdf->Cell(50,8,utf8_decode($venda['cliente']),1);
    $pdf->Cell(30,8,utf8_decode($venda['usuario']),1);
    $pdf->Cell(30,8,date('d/m/Y H:i', strtotime($venda['data_venda'])),1);
    $pdf->Cell(30,8,ucfirst($venda['status']),1);
    $pdf->Cell(30,8,'R$ '.number_format($venda['total'],2,',','.'),1);
    $pdf->Ln();
}

// Totais
$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);

$pdf->Cell(0,8,
    "TOTAL DO PERIODO (Somente Concluidas): R$ "
    .number_format($totalPeriodo,2,',','.'),
0,1);

$pdf->Cell(0,8,
    "TOTAL DE DESCONTOS CONCEDIDOS: R$ "
    .number_format($totalDesconto,2,',','.'),
0,1);

$pdf->Cell(0,8,
    "TOTAL LIQUIDO: R$ "
    .number_format($totalPeriodo - $totalDesconto,2,',','.'),
0,1);

$pdf->Cell(0,8,
    "TOTAL CANCELADAS: R$ "
    .number_format($totalCanceladas,2,',','.'),
0,1);

$pdf->Output();
