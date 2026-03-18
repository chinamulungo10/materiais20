<?php
include "conexao.php";
require('fpdf/fpdf.php');

if(!isset($_GET['id'])){
    die("ID do devedor não informado.");
}

$id = intval($_GET['id']);

$sql = "
SELECT 
d.nome_cliente,
d.telefone,
d.material,
d.valor_divida,
d.data_divida,
d.data_vencimento,
IFNULL(SUM(p.valor_pago),0) as total_pago

FROM devedores d

LEFT JOIN pagamentos_divida p
ON d.id_devedor = p.id_devedor

WHERE d.id_devedor = $id
";

$result = $conn->query($sql);

if(!$result){
    die("Erro na consulta: ".$conn->error);
}

$dados = $result->fetch_assoc();

$restante = $dados['valor_divida'] - $dados['total_pago'];

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Relatorio de Divida',0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','',12);

$pdf->Cell(0,8,"Cliente: ".$dados['nome_cliente'],0,1);
$pdf->Cell(0,8,"Telefone: ".$dados['telefone'],0,1);
$pdf->Cell(0,8,"Material: ".$dados['material'],0,1);

$pdf->Ln(5);

$pdf->Cell(0,8,"Data da Divida: ".$dados['data_divida'],0,1);
$pdf->Cell(0,8,"Data de Vencimento: ".$dados['data_vencimento'],0,1);

$pdf->Ln(5);

$pdf->Cell(0,8,"Valor Total da Divida: R$ ".number_format($dados['valor_divida'],2,',','.'),0,1);
$pdf->Cell(0,8,"Total Pago: R$ ".number_format($dados['total_pago'],2,',','.'),0,1);
$pdf->Cell(0,8,"Valor Restante: R$ ".number_format($restante,2,',','.'),0,1);

$pdf->Ln(10);

$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,8,"Historico de Pagamentos",0,1);

$pdf->SetFont('Arial','',12);

$sql_pag = "
SELECT valor_pago, data_pagamento 
FROM pagamentos_divida 
WHERE id_devedor = $id
";

$pagamentos = $conn->query($sql_pag);

while($p = $pagamentos->fetch_assoc()){

$pdf->Cell(0,8,
"Pago: R$ ".number_format($p['valor_pago'],2,',','.').
" | Data: ".date("d/m/Y", strtotime($p['data_pagamento']))
,0,1);

}

$pdf->Output();
?>