<?php
require('fpdf/fpdf.php');

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

// Fundo do logo (retângulo no topo)
$pdf->SetFillColor(20, 60, 100); // azul escuro
$pdf->Rect(10, 10, 190, 30, 'F'); // retângulo preenchido

// Texto do logo
$pdf->SetFont('Arial','B',16);
$pdf->SetTextColor(255,255,255); // branco
$pdf->SetXY(15, 18); // posição dentro do retângulo
$pdf->Cell(0,10,'FERRAGEM SWASSWA PEMBA-EXPANSAO',0,1,'L');

// Corpo do PDF
$pdf->Ln(45);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Comprovante de teste com logo da Ferragem SWASSWA PEMBA-EXPANSAO',0,1);

$pdf->Output('I','teste_logo.pdf');
