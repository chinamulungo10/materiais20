<? 
require "fpdf/fpdf.php";
require "conexao.php";

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Ranking de Vendas',0,1,'C');

$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,'Top Clientes',0,1);

$res = $conn->query("SELECT cliente, SUM(total) total FROM vendas WHERE status='Concluida' GROUP BY cliente ORDER BY total DESC LIMIT 5");
$pdf->SetFont('Arial','',10);
while($r=$res->fetch_assoc()){
    $pdf->Cell(0,7,$r['cliente'].' - R$ '.number_format($r['total'],2,',','.'),0,1);
}

$pdf->Output();
