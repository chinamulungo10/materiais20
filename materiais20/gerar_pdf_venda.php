<?php
session_start();
require "conexao.php";
require "fpdf/fpdf.php";
require "proteger.php";

/* =====================
   NOVO: CLASSE PDF COM ROTAÇÃO
===================== */
class PDF extends FPDF
{
    protected $angle = 0;

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) $x = $this->x;
        if ($y == -1) $y = $this->y;
        if ($this->angle != 0) {
            $this->_out('Q');
        }
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf(
                'q %.5F %.5F %.5F %.5F %.5F %.5F cm 1 0 0 1 %.5F %.5F cm',
                $c, $s, -$s, $c,
                $cx, $cy,
                -$cx, -$cy
            ));
        }
    }

    function RotatedText($x, $y, $txt, $angle)
    {
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }
}


function t($s){ return utf8_decode($s); }

/*  Dados da loja */
$loja = [
    'nome' => 'Loja Swasswa',
    'descricao' => 'VENDA DE MATERIAIS DE CONSTRUCAO',
    'telefone' => '+258 844388815',
    'email' => 'chafimchinamulungo@gmail.com',
    'cidade' => 'Pemba - Moçambique'
];

/* Verifica POST válido */
if (!isset($_POST['venda_id']) || !is_numeric($_POST['venda_id'])) {
    die("Dados inválidos.");
}
$venda_id = (int) $_POST['venda_id'];

/*  Venda */
$stmt = $conn->prepare(
    "SELECT id, cliente, data_venda, total, forma_pagamento, desconto, status
     FROM vendas
     WHERE id = ?"
);
$stmt->bind_param("i", $venda_id);
$stmt->execute();
$venda = $stmt->get_result()->fetch_assoc();

if (!$venda) {
    die("Venda não encontrada.");
}

/* Número da fatura baseado no ID */
$numeroFatura = 'FT-' . date('Y') . '-' . str_pad($venda['id'], 6, '0', STR_PAD_LEFT);

/*  Itens da venda */
$stmtItens = $conn->prepare(
    "SELECT m.nome, i.quantidade, i.preco_unitario
     FROM itens_venda i
     JOIN materiais m ON m.id = i.material_id
     WHERE i.venda_id = ?"
);
$stmtItens->bind_param("i", $venda_id);
$stmtItens->execute();
$itens = $stmtItens->get_result();

/* PDF */
$pdf = new PDF('P','mm','A4');
$pdf->AddPage();
/* =====================
   NOVO: MARCA D’ÁGUA SE CANCELADA
===================== */
if (($venda['status'] ?? '') === 'cancelada') {
    $pdf->SetFont('Arial', 'B', 50);
    $pdf->SetTextColor(230, 230, 230); // cinza claro
    $pdf->RotatedText(55, 150, t('CANCELADA'), 45);
    $pdf->SetTextColor(0, 0, 0); // volta ao normal
}

/* LOGO */
$logo = __DIR__ . "/logo.png";
if (file_exists($logo)) {
    $pdf->Image($logo, 10, 10, 25);
}

/* CABEÇALHO */
$pdf->SetFont('Arial','B',14);
$pdf->SetXY(40, 10);
$pdf->Cell(0,7,t($loja['nome']),0,1);

$pdf->SetFont('Arial','',9);
$pdf->SetX(40);
$pdf->Cell(0,5,t($loja['descricao']),0,1);
$pdf->SetX(40);
$pdf->Cell(0,5,'Tel: '.$loja['telefone'].' | '.$loja['email'],0,1);
$pdf->SetX(40);
$pdf->Cell(0,5,t($loja['cidade']),0,1);

$pdf->Ln(8);

/* DADOS DA FATURA */
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0, 8, "FATURA N: $numeroFatura", 0, 1, 'R');

$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,"Cliente: ".t($venda['cliente']),0,1);
$pdf->Cell(0,6,"Data: ".date('d/m/Y H:i', strtotime($venda['data_venda'])),0,1);
$pdf->Cell(0,6,"Forma de Pagamento: ".strtoupper($venda['forma_pagamento']),0,1);

$pdf->Ln(4);

/* TABELA DE ITENS */
$pdf->SetFont('Arial','B',10);
$pdf->Cell(85,8,'Produto',1);
$pdf->Cell(15,8,'Qtd',1,0,'C');
$pdf->Cell(40,8,'Preco Unit.',1,0,'R');
$pdf->Cell(40,8,'Total',1,1,'R');

$pdf->SetFont('Arial','',10);
$totalProdutos = 0;

while ($i = $itens->fetch_assoc()) {
    $totalItem = $i['quantidade'] * $i['preco_unitario'];
    $totalProdutos += $totalItem;

    $pdf->Cell(85,8,t($i['nome']),1);
    $pdf->Cell(15,8,$i['quantidade'],1,0,'C');
    $pdf->Cell(40,8,'MZN '.number_format($i['preco_unitario'],2,',','.'),1,0,'R');
    $pdf->Cell(40,8,'MZN '.number_format($totalItem,2,',','.'),1,1,'R');
}

/* DESCONTO E TOTAL FINAL */
$pdf->Ln(3);
$pdf->SetFont('Arial','B',11);

// Mostrar desconto
$desconto = floatval($venda['desconto'] ?? 0);
$pdf->Cell(140,8,'DESCONTO',1);
$pdf->Cell(40,8,'MZN '.number_format($desconto,2,',','.'),1,1,'R');

// Total final já calculado no banco
$totalFinal = $venda['total'];
$pdf->Cell(140,10,'TOTAL FINAL',1);
$pdf->Cell(40,10,'MZN '.number_format($totalFinal,2,',','.'),1,1,'R');

/* RODAPÉ */
$pdf->Ln(10);
$pdf->SetFont('Arial','I',9);
$pdf->Cell(0,6,'Obrigado pela preferencia - volte mais!',0,1,'C');
if (($venda['status'] ?? '') === 'cancelada') {
    $pdf->Cell(0,6,'Documento cancelado - sem valor fiscal',0,1,'C');
} else {
    $pdf->Cell(0,6,'Documento emitido pelo sistema',0,1,'C');
}
$pdf->Cell(0,6,'Sistema Desenvolvido Por: Chafim Chinamulungo',0,1,'C');

/* =====================
   SALVAR PDF NO SERVIDOR
===================== */
$pasta = __DIR__ . "/upload/vendas/";
if (!is_dir($pasta)) {
    mkdir($pasta, 0777, true);
}

$nomeArquivo = "fatura_$numeroFatura.pdf";
$caminhoPdf = $pasta . $nomeArquivo;

/* salva no servidor */
$pdf->Output("F", $caminhoPdf);

/* exibe no navegador */
$pdf->Output("I", $nomeArquivo);
