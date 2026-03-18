<?php
session_start();
require "proteger.php";
require "conexao.php";
require "fpdf/fpdf.php";

/* =====================
   SEGURANÇA
===================== */
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'admin') {
    http_response_code(403);
    die("Acesso negado.");
}

$cliente = trim($_GET['cliente'] ?? '');

if ($cliente === '') {
    die("Cliente não informado.");
}

/* =====================
   DADOS DA FERRAGEM
===================== */
$ferragem = [
    'nome'      => 'Loja Swasswa',
    'descricao' => 'Venda de Materiais de Construção',
    'telefone'  => '+258 84 438 8815',
    'email'     => 'chafimchinamulungo@gmail.com',
    'cidade'    => 'Pemba - Moçambique'
];

/* =====================
   BUSCAR VENDAS DO CLIENTE
===================== */
$stmt = $conn->prepare("
    SELECT 
        v.id,
        v.cliente,
        v.data_venda,
        v.status,
        (
            SELECT SUM(i.quantidade * i.preco_unitario)
            FROM itens_venda i
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

/* =====================
   CALCULAR TOTAL E QTD DE VENDAS DO CLIENTE
===================== */
$totalCliente = 0;
$qtdVendasCliente = 0;
while ($row = $result->fetch_assoc()) {
    $totalCliente += $row['total'] ?? 0;
    $qtdVendasCliente++;
}
$stmt->free_result();
$stmt->close();

/* =====================
   BUSCAR TOTAL E QTD DE VENDAS POR VENDEDOR (USUÁRIO)
===================== */
$stmt = $conn->prepare("
    SELECT 
        u.usuario,
        COUNT(v.id) AS qtd_vendas,
        SUM(
            (SELECT SUM(i.quantidade * i.preco_unitario) FROM itens_venda i WHERE i.venda_id = v.id)
        ) AS total_vendas
    FROM vendas v
    JOIN usuarios u ON u.id = v.usuario_id
    GROUP BY u.usuario
    ORDER BY total_vendas DESC
");

$stmt->execute();
$resultVendedores = $stmt->get_result();

/* =====================
   CRIAR PDF
===================== */
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

/* LOGO */
$logo = __DIR__ . '/logo.png';
if (file_exists($logo)) {
    $pdf->Image($logo, 10, 10, 25);
}

/* CABEÇALHO */
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetXY(40, 10);
$pdf->Cell(0, 7, utf8_decode($ferragem['nome']), 0, 1);

$pdf->SetFont('Arial', '', 9);
$pdf->SetX(40);
$pdf->Cell(0, 5, utf8_decode($ferragem['descricao']), 0, 1);
$pdf->SetX(40);
$pdf->Cell(0, 5, 'Tel: ' . $ferragem['telefone'] . ' | ' . $ferragem['email'], 0, 1);
$pdf->SetX(40);
$pdf->Cell(0, 5, utf8_decode($ferragem['cidade']), 0, 1);

$pdf->Ln(12);

/* TÍTULO */
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, utf8_decode('Histórico de Vendas por Cliente'), 0, 1, 'C');

$pdf->Ln(3);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, utf8_decode('Cliente: ') . utf8_decode($cliente), 0, 1);
$pdf->Cell(0, 6, 'Data de emissão: ' . date('d/m/Y H:i'), 0, 1);

$pdf->Ln(8);

/* RESUMO DO CLIENTE */
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(90, 7, 'Total de Vendas do Cliente:', 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 7, $qtdVendasCliente . ' vendas', 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(90, 7, 'Total Vendido pelo Cliente:', 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 7, 'MZN ' . number_format($totalCliente, 2, ',', '.'), 0, 1);

$pdf->Ln(10);

/* TABELA VENDAS DETALHADAS DO CLIENTE */
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 8, 'ID', 1);
$pdf->Cell(60, 8, 'Cliente', 1);
$pdf->Cell(35, 8, 'Data', 1);
$pdf->Cell(35, 8, 'Total', 1, 0, 'R');
$pdf->Cell(30, 8, 'Status', 1, 1);

$pdf->SetFont('Arial', '', 10);
$stmt = $conn->prepare("
    SELECT 
        v.id,
        v.cliente,
        v.data_venda,
        v.status,
        (
            SELECT SUM(i.quantidade * i.preco_unitario)
            FROM itens_venda i
            WHERE i.venda_id = v.id
        ) AS total
    FROM vendas v
    WHERE v.cliente LIKE ?
    ORDER BY v.data_venda DESC
");
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $pdf->Cell(0, 10, utf8_decode('Nenhuma venda encontrada.'), 1, 1, 'C');
} else {
    while ($v = $result->fetch_assoc()) {
        $pdf->Cell(20, 8, '#' . $v['id'], 1);
        $pdf->Cell(60, 8, utf8_decode($v['cliente']), 1);
        $pdf->Cell(35, 8, date('d/m/Y', strtotime($v['data_venda'])), 1);
        $pdf->Cell(35, 8, 'MZN ' . number_format($v['total'] ?? 0, 2, ',', '.'), 1, 0, 'R');
        $pdf->Cell(30, 8, ucfirst($v['status']), 1, 1);
    }
}
$stmt->close();

$pdf->Ln(12);

/* TABELA RESUMO POR VENDEDOR */
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, utf8_decode('Ranking de Vendas por Vendedor'), 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(70, 8, 'Vendedor', 1);
$pdf->Cell(40, 8, 'Qtd Vendas', 1, 0, 'R');
$pdf->Cell(50, 8, 'Total Vendido (MZN)', 1, 1, 'R');

$pdf->SetFont('Arial', '', 10);

while ($vendedor = $resultVendedores->fetch_assoc()) {
    $pdf->Cell(70, 8, utf8_decode($vendedor['usuario']), 1);
    $pdf->Cell(40, 8, $vendedor['qtd_vendas'], 1, 0, 'R');
    $pdf->Cell(50, 8, number_format($vendedor['total_vendas'] ?? 0, 2, ',', '.'), 1, 1, 'R');
}
$stmt->close();

/* RODAPÉ */
$pdf->Ln(8);
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 6, utf8_decode('Documento emitido pelo sistema'), 0, 1, 'C');

/* SAÍDA */
$nomeArquivo = 'historico_cliente_' . date('Ymd_His') . '.pdf';
$pdf->Output('I', $nomeArquivo);
exit;
