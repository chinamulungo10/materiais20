<?php
session_start();
require "proteger.php";
require "conexao.php";

// Somente administrador
if (($_SESSION['nivel'] ?? '') !== 'admin') {
    exit("Acesso negado.");
}

// ==========================================================
// OBTER ID DA DÍVIDA
// ==========================================================

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: /materiais20/devedores.php");
    exit;
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
    ORDER BY data_hora DESC
");
$stmt->bind_param("i", $id);
$stmt->execute();
$logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$classe_status = strtolower($status);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Detalhes da Dívida #<?= (int) $divida['id_devedor'] ?></title>
<link rel="stylesheet" href="detalhes_divida.css">
</head>

<body>
<div class="pagina">

    <!-- TOPO -->
    <div class="topo">
        <div class="topo-info">
            <small>Dívida #<?= (int) $divida['id_devedor'] ?></small>
            <h1><?= htmlspecialchars($divida['nome_cliente']) ?></h1>
        </div>

        <div class="topo-acoes">
            <a href="imprimir_divida.php?id=<?= (int) $id ?>" target="_blank" class="btn btn-primario">
                🖨️ Imprimir (PDF)
            </a>
            <a href="/materiais20/devedores.php" class="btn btn-claro">
                ← Voltar para Dívidas
            </a>
        </div>
    </div>


    <!-- RESUMO -->
    <div class="resumo">
        <div class="resumo-item">
            <span>Valor da dívida</span>
            <strong><?= dinheiro($valor_divida) ?></strong>
        </div>
        <div class="resumo-item pago">
            <span>Total pago</span>
            <strong><?= dinheiro($total_pago) ?></strong>
        </div>
        <div class="resumo-item saldo">
            <span>Saldo restante</span>
            <strong><?= dinheiro($saldo) ?></strong>
        </div>
    </div>


    <!-- DADOS DA DÍVIDA -->
    <div class="card">
        <div class="card-titulo">Dados da Dívida</div>
        <div class="card-corpo">
            <div class="dados">
                <div class="dado">
                    <label>Cliente</label>
                    <div><?= htmlspecialchars($divida['nome_cliente']) ?></div>
                </div>
                <div class="dado">
                    <label>Telefone</label>
                    <div><?= htmlspecialchars($divida['telefone']) ?></div>
                </div>
                <div class="dado">
                    <label>Status</label>
                    <div><span class="badge <?= $classe_status ?>"><?= $status ?></span></div>
                </div>
                <div class="dado">
                    <label>Data da dívida</label>
                    <div><?= dataBR($divida['data_divida']) ?></div>
                </div>
                <div class="dado">
                    <label>Vencimento</label>
                    <div><?= dataBR($divida['data_vencimento']) ?></div>
                </div>
                <div class="dado">
                    <label>Material</label>
                    <div><?= nl2br(htmlspecialchars($divida['material'])) ?></div>
                </div>
            </div>
        </div>
    </div>


    <!-- PAGAMENTOS -->
    <div class="card">
        <div class="card-titulo">Pagamentos Realizados</div>
        <div class="tabela-wrap">
            <table>
                <thead>
                    <tr>
                        <th width="80">N</th>
                        <th>Data do pagamento</th>
                        <th>Valor pago</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($pagamentos)): ?>
                    <tr>
                        <td colspan="3" class="vazio">Nenhum pagamento registrado.</td>
                    </tr>
                <?php else: ?>
                    <?php $n = 1; foreach ($pagamentos as $p): ?>
                    <tr>
                        <td><?= $n++ ?></td>
                        <td><?= dataBR($p['data_pagamento']) ?></td>
                        <td><?= dinheiro($p['valor_pago']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- HISTÓRICO -->
    <div class="card">
        <div class="card-titulo">Histórico de Alterações e Autorizações</div>

        <?php if (empty($logs)): ?>
            <div class="card-corpo vazio">Nenhum registro no histórico.</div>
        <?php else: ?>
        <ul class="linha-tempo">
            <?php foreach ($logs as $l): ?>
            <?php
                $classe_acao = strtolower($l['acao']);
                if (!in_array($classe_acao, ['editou', 'pagou', 'cancelou'])) {
                    $classe_acao = 'outro';
                }
                $admin = !empty($l['usuario_admin']) ? $l['usuario_admin'] : 'Desconhecido';
            ?>
            <li class="evento <?= $classe_acao ?>">
                <div class="evento-topo">
                    <span class="badge <?= $classe_acao ?>"><?= htmlspecialchars($l['acao']) ?></span>
                    <span class="evento-data"><?= dataBR($l['data_hora'], true) ?></span>
                    <span class="evento-admin">👤 <?= htmlspecialchars($admin) ?></span>
                </div>
                <div class="evento-desc"><?= htmlspecialchars($l['descricao']) ?></div>
                <div class="evento-valores">
                    Valor anterior: <strong><?= dinheiro($l['valor_anterior']) ?></strong>
                    &nbsp;→&nbsp;
                    Valor novo: <strong><?= dinheiro($l['valor_novo']) ?></strong>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>


    <!-- SUGESTÃO DE IMPRESSÃO -->
    <div class="aviso-impressao">
        <span>Deseja guardar ou entregar este extrato? Gere o PDF completo desta dívida.</span>
        <a href="imprimir_divida.php?id=<?= (int) $id ?>" target="_blank" class="btn btn-primario">
            🖨️ Imprimir (PDF)
        </a>
    </div>

</div>
</body>
</html>