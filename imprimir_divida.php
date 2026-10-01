<?php
session_start();
require "proteger.php";
require "conexao.php";
require "fpdf/fpdf.php";

// Somente administrador
if (($_SESSION['nivel'] ?? '') !== 'admin') {
    exit("Acesso negado.");
}

// ==========================================================
// OBTER ID DA DÍVIDA
// ==========================================================

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    exit("Dívida não informada.");
}

// Converte texto UTF-8 para o FPDF
function t($texto)
{
    $texto = str_replace("→", "->", (string) $texto);
    return iconv('UTF-8', 'windows-1252//TRANSLIT', $texto);
}

function dinheiro($v)
{
    return "R$ " . number_format((float) $v, 2, ',', '.');
}

function dataBR($d, $comHora = false)
{
    if (empty($d)) {
        return "-";
    }
    return date($comHora ? "d/m/Y H:i" : "d/m/Y", strtotime($d));
}


// ==========================================================
// DADOS DA DÍVIDA
// ==========================================================

$stmt = $conn->prepare("
    SELECT *
    FROM devedores
    WHERE id_devedor = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$divida = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$divida) {
    exit("Dívida não encontrada.");
}


// ==========================================================
// PAGAMENTOS
// ==========================================================

$stmt = $conn->prepare("
    SELECT valor_pago, data_pagamento
    FROM pagamentos_divida
    WHERE id_devedor = ?
    ORDER BY data_pagamento ASC, id_pagamento ASC
");
$stmt->bind_param("i", $id);
$stmt->execute();
$pagamentos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$total_pago = 0;
foreach ($pagamentos as $p) {
    $total_pago += (float) $p['valor_pago'];
}

$valor_divida = (float) $divida['valor_divida'];
$saldo = max(0, $valor_divida - $total_pago);

$status = "ABERTO";
if ($total_pago >= $valor_divida) {
    $status = "PAGO";
} elseif ($total_pago > 0) {
    $status = "PARCIAL";
}


// ==========================================================
// HISTÓRICO (editou, pagou, cancelou, quem autorizou)
// ==========================================================

$stmt = $conn->prepare("
    SELECT usuario_admin, acao, descricao, valor_anterior, valor_novo, data_hora, ip
    FROM log_dividas
    WHERE id_devedor = ?
    ORDER BY data_hora ASC
");
$stmt->bind_param("i", $id);
$stmt->execute();
$logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();


// ==========================================================
// MONTAR O PDF
// ==========================================================

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// Título
$pdf->SetFont("Arial", "B", 14);
$pdf->Cell(0, 10, t("Extrato da Dívida #" . $divida['id_devedor']), 0, 1, "C");
$pdf->Ln(3);


// ---------- Dados da dívida ----------
$pdf->SetFont("Arial", "B", 11);
$pdf->Cell(0, 8, t("Dados da Dívida"), 0, 1);

$pdf->SetFont("Arial", "", 10);
$pdf->Cell(45, 7, "Cliente:", 1);
$pdf->Cell(0, 7, t($divida['nome_cliente']), 1, 1);

$pdf->Cell(45, 7, "Telefone:", 1);
$pdf->Cell(0, 7, t($divida['telefone']), 1, 1);

$pdf->Cell(45, 7, "Data da dívida:", 1);
$pdf->Cell(0, 7, t(dataBR($divida['data_divida'])), 1, 1);

$pdf->Cell(45, 7, "Vencimento:", 1);
$pdf->Cell(0, 7, t(dataBR($divida['data_vencimento'])), 1, 1);

$pdf->Cell(45, 7, "Valor da dívida:", 1);
$pdf->Cell(0, 7, t(dinheiro($valor_divida)), 1, 1);

$pdf->Cell(45, 7, "Total pago:", 1);
$pdf->Cell(0, 7, t(dinheiro($total_pago)), 1, 1);

$pdf->Cell(45, 7, "Saldo restante:", 1);
$pdf->Cell(0, 7, t(dinheiro($saldo)), 1, 1);

$pdf->Cell(45, 7, "Status:", 1);
$pdf->Cell(0, 7, t($status), 1, 1);

// Material (pode ser longo)
$pdf->Cell(45, 7, "Material:", 1);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->MultiCell(0, 7, t($divida['material']), 1);

$pdf->Ln(6);


// ---------- Pagamentos ----------
$pdf->SetFont("Arial", "B", 11);
$pdf->Cell(0, 8, "Pagamentos Realizados", 0, 1);

$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(20, 8, "N", 1, 0, "C");
$pdf->Cell(60, 8, "Data do pagamento", 1);
$pdf->Cell(50, 8, "Valor pago", 1);
$pdf->Ln();

$pdf->SetFont("Arial", "", 10);

if (empty($pagamentos)) {
    $pdf->Cell(130, 8, "Nenhum pagamento registrado.", 1, 1, "C");
} else {
    $n = 1;
    foreach ($pagamentos as $p) {
        $pdf->Cell(20, 8, $n++, 1, 0, "C");
        $pdf->Cell(60, 8, t(dataBR($p['data_pagamento'])), 1);
        $pdf->Cell(50, 8, t(dinheiro($p['valor_pago'])), 1);
        $pdf->Ln();
    }
}

$pdf->Ln(6);


// ---------- Histórico de alterações ----------
$pdf->SetFont("Arial", "B", 11);
$pdf->Cell(0, 8, t("Histórico de Alterações e Autorizações"), 0, 1);

if (empty($logs)) {
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 8, t("Nenhum registro no histórico."), 1, 1, "C");
} else {
    foreach ($logs as $l) {

        $admin = !empty($l['usuario_admin']) ? $l['usuario_admin'] : "Desconhecido";

        // Linha de cabeçalho do registro
        $pdf->SetFont("Arial", "B", 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(40, 7, t(dataBR($l['data_hora'], true)), 1, 0, "L", true);
        $pdf->Cell(35, 7, t($l['acao']), 1, 0, "L", true);
        $pdf->Cell(0, 7, t("Administrador: " . $admin), 1, 1, "L", true);

        // Descrição
        $pdf->SetFont("Arial", "", 9);
        $pdf->MultiCell(0, 6, t($l['descricao']), 1);

        // Valores
        $pdf->Cell(
            0,
            6,
            t("Valor anterior: " . dinheiro($l['valor_anterior']) .
              "   |   Valor novo: " . dinheiro($l['valor_novo'])),
            1,
            1
        );

        $pdf->Ln(3);
    }
}

// Rodapé
$pdf->Ln(4);
$pdf->SetFont("Arial", "I", 8);
$pdf->Cell(0, 6, t("Emitido em " . date("d/m/Y H:i")), 0, 1, "R");

$pdf->Output("I", "divida_" . $id . ".pdf");
exit;