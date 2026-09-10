<?php
session_start();

require_once "proteger.php";
require_once "conexao.php";

if ($_SESSION['nivel'] !== 'admin') {
    http_response_code(403);
    exit("Acesso negado.");
}

/* =========================
   VALIDAR ID DA VENDA
========================= */

$venda_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$venda_id || $venda_id <= 0) {
    exit("Venda inválida.");
}

/* =========================
   BUSCAR DADOS DA VENDA
========================= */

$sqlVenda = "
    SELECT
        v.id,
        v.cliente,
        v.usuario_id,
        v.total,
        v.forma_pagamento,
        v.desconto,
        v.status,
        v.data_venda,
        u.usuario AS vendedor
    FROM vendas v
    LEFT JOIN usuarios u
        ON u.id = v.usuario_id
    WHERE v.id = ?
    LIMIT 1
";

$stmtVenda = $conn->prepare($sqlVenda);

if (!$stmtVenda) {
    exit("Erro ao preparar consulta da venda.");
}

$stmtVenda->bind_param("i", $venda_id);
$stmtVenda->execute();

$resultVenda = $stmtVenda->get_result();
$venda = $resultVenda->fetch_assoc();

if (!$venda) {
    exit("Venda não encontrada.");
}

/* =========================
   BUSCAR ITENS DA VENDA
========================= */

$sqlItens = "
    SELECT
        iv.material_id,
        iv.quantidade,
        iv.preco_unitario,
        iv.total,
        m.nome AS material_nome
    FROM itens_venda iv
    LEFT JOIN materiais m
        ON m.id = iv.material_id
    WHERE iv.venda_id = ?
    ORDER BY iv.id ASC
";

$stmtItens = $conn->prepare($sqlItens);

if (!$stmtItens) {
    exit("Erro ao preparar consulta dos itens.");
}

$stmtItens->bind_param("i", $venda_id);
$stmtItens->execute();

$resultItens = $stmtItens->get_result();

$itens = [];

while ($item = $resultItens->fetch_assoc()) {
    $itens[] = $item;
}

/* =========================
   CALCULAR SUBTOTAL
========================= */

$subtotal = 0;

foreach ($itens as $item) {
    $subtotal += (float)$item['total'];
}

$desconto = (float)($venda['desconto'] ?? 0);
$total = (float)$venda['total'];

/* =========================
   FORMA DE PAGAMENTO
========================= */

$formasPagamento = [
    'dinheiro' => 'Dinheiro',
    'pix' => 'PIX',
    'cartao' => 'Cartão',
    'cheque' => 'Cheque',
    'transf_bancaria' => 'Transferência bancária',
    'mpesa' => 'M-Pesa',
    'emola' => 'e-Mola',
    'divida' => 'Dívida'
];

$formaPagamento = $formasPagamento[$venda['forma_pagamento']]
    ?? ucfirst($venda['forma_pagamento']);

/* =========================
   STATUS
========================= */

$status = ucfirst($venda['status']);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Detalhes da Venda #<?= (int)$venda['id'] ?></title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<style>

body {
    background: #f4f6f9;
}

.container-venda {
    max-width: 1000px;
    margin: 30px auto;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
}

.titulo {
    font-weight: 700;
}

.total-final {
    font-size: 24px;
    font-weight: bold;
}

@media print {

    .nao-imprimir {
        display: none !important;
    }

    body {
        background: white;
    }

    .card {
        box-shadow: none;
    }

}

</style>

</head>

<body>

<div class="container-venda">

    <!-- CABEÇALHO -->

    <div class="card mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h2 class="titulo">
                        Detalhes da Venda
                    </h2>

                    <p class="mb-0">
                        Venda #<?= (int)$venda['id'] ?>
                    </p>

                </div>

                <div class="nao-imprimir">

                    <button
                        onclick="window.print()"
                        class="btn btn-secondary">
                        🖨️ Imprimir
                    </button>

                    <a
                        href="log.php"
                        class="btn btn-primary">
                        ← Voltar
                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- INFORMAÇÕES DA VENDA -->

    <div class="card mb-4">

        <div class="card-body">

            <h5 class="mb-3">
                Informações da venda
            </h5>

            <div class="row">

                <div class="col-md-4 mb-3">

                    <strong>Venda:</strong><br>

                    #<?= (int)$venda['id'] ?>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Cliente:</strong><br>

                    <?= htmlspecialchars(
                        $venda['cliente'] ?? 'Não informado'
                    ) ?>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Vendedor:</strong><br>

                    <?= htmlspecialchars(
                        $venda['vendedor'] ?? 'Não informado'
                    ) ?>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Data:</strong><br>

                    <?= date(
                        'd/m/Y H:i',
                        strtotime($venda['data_venda'])
                    ) ?>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Pagamento:</strong><br>

                    <?= htmlspecialchars($formaPagamento) ?>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Status:</strong><br>

                    <?= htmlspecialchars($status) ?>

                </div>

            </div>

        </div>

    </div>


    <!-- PRODUTOS -->

    <div class="card mb-4">

        <div class="card-body">

            <h5 class="mb-3">
                Produtos da venda
            </h5>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>

                            <th>Produto</th>

                            <th class="text-center">
                                Quantidade
                            </th>

                            <th class="text-end">
                                Preço unitário
                            </th>

                            <th class="text-end">
                                Total
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if (!empty($itens)): ?>

                        <?php foreach ($itens as $item): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars(
                                        $item['material_nome']
                                        ?? 'Material não encontrado'
                                    ) ?>
                                </td>

                                <td class="text-center">
                                    <?= (int)$item['quantidade'] ?>
                                </td>

                                <td class="text-end">
                                    MZN
                                    <?= number_format(
                                        $item['preco_unitario'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                                <td class="text-end">
                                    MZN
                                    <?= number_format(
                                        $item['total'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="4"
                                class="text-center">

                                Nenhum item encontrado.

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- TOTAIS -->

    <div class="card mb-4">

        <div class="card-body">

            <div class="row justify-content-end">

                <div class="col-md-5">

                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Subtotal:
                        </span>

                        <strong>
                            MZN
                            <?= number_format(
                                $subtotal,
                                2,
                                ',',
                                '.'
                            ) ?>
                        </strong>

                    </div>

                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            Desconto:
                        </span>

                        <strong>
                            MZN
                            <?= number_format(
                                $desconto,
                                2,
                                ',',
                                '.'
                            ) ?>
                        </strong>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">

                        <span class="total-final">
                            TOTAL:
                        </span>

                        <span class="total-final">
                            MZN
                            <?= number_format(
                                $total,
                                2,
                                ',',
                                '.'
                            ) ?>
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- BOTÕES -->

    <div class="text-center nao-imprimir">

        <a
            href="log.php"
            class="btn btn-outline-secondary">
            ← Voltar ao histórico
        </a>

        <button
            onclick="window.print()"
            class="btn btn-secondary">
            🖨️ Imprimir venda
        </button>

    </div>

</div>

</body>

</html>