<?php

require_once "proteger.php"; // valida sessão
include "conexao.php";

// Garantir que a sessão esteja iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ==========================================================
// VERIFICAR AUTORIZAÇÃO DO ADMINISTRADOR
// ==========================================================

if (
    !isset($_SESSION['pagamento_divida_autorizado']) ||
    $_SESSION['pagamento_divida_autorizado'] !== true
) {
    header("Location: /materiais20/devedores.php");
    exit;
}


// ==========================================================
// OBTER ID DO DEVEDOR
// ==========================================================

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: /materiais20/devedores.php");
    exit;
}

// ==========================================================
// SALVAR PAGAMENTO
// ==========================================================

if (isset($_POST['salvar_pagamento'])) {

    $valor_pago = (float) $_POST['valor_pago'];
    $data_pagamento = $_POST['data_pagamento'];
    $id_devedor = $id;
     // Consumir a autorização após o pagamento
   $_SESSION['pagamento_divida_autorizado'] = false;
   unset($_SESSION['usuario_admin_pagamento']);

    // Validar valor
    if ($valor_pago <= 0) {
        die("O valor do pagamento deve ser maior que zero.");
    }


    // ======================================================
    // 1. BUSCAR DADOS DA DÍVIDA ANTES DO PAGAMENTO
    // ======================================================

    $stmt = $conn->prepare("
        SELECT 
            d.id_devedor,
            d.id_venda,
            d.nome_cliente,
            d.valor_divida,
            IFNULL(SUM(p.valor_pago), 0) AS total_pago
        FROM devedores d
        LEFT JOIN pagamentos_divida p 
            ON d.id_devedor = p.id_devedor
        WHERE d.id_devedor = ?
        GROUP BY 
            d.id_devedor,
            d.id_venda,
            d.nome_cliente,
            d.valor_divida
    ");

    $stmt->bind_param("i", $id_devedor);
    $stmt->execute();

    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();


    if (!$res) {
        die("Dívida não encontrada.");
    }


    // Dados antes do pagamento
    $valor_divida = (float) $res['valor_divida'];
    $total_pago_anterior = (float) $res['total_pago'];

    $saldo_anterior = $valor_divida - $total_pago_anterior;


    // ======================================================
    // 2. SALVAR PAGAMENTO
    // ======================================================

    $stmt = $conn->prepare("
        INSERT INTO pagamentos_divida 
        (
            id_devedor,
            valor_pago,
            data_pagamento
        )
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param(
        "ids",
        $id_devedor,
        $valor_pago,
        $data_pagamento
    );

    $stmt->execute();
    $stmt->close();


    // ======================================================
    // 3. BUSCAR TOTAL ATUALIZADO
    // ======================================================

    $stmt = $conn->prepare("
        SELECT 
            d.id_venda,
            d.valor_divida,
            d.nome_cliente,
            IFNULL(SUM(p.valor_pago), 0) AS total_pago
        FROM devedores d
        LEFT JOIN pagamentos_divida p 
            ON d.id_devedor = p.id_devedor
        WHERE d.id_devedor = ?
        GROUP BY 
            d.id_venda,
            d.valor_divida,
            d.nome_cliente
    ");

    $stmt->bind_param("i", $id_devedor);
    $stmt->execute();

    $res_atualizado = $stmt->get_result()->fetch_assoc();
    $stmt->close();


    // ======================================================
    // 4. DEFINIR STATUS
    // ======================================================

    $total_pago_atual = (float) $res_atualizado['total_pago'];
    $saldo_atual = $valor_divida - $total_pago_atual;

    $status = "ABERTO";

    if ($total_pago_atual >= $valor_divida) {

        $status = "PAGO";

    } elseif ($total_pago_atual > 0) {

        $status = "PARCIAL";
    }


    // ======================================================
    // 5. ATUALIZAR STATUS DA VENDA
    // ======================================================

    $stmt = $conn->prepare("
        UPDATE vendas 
        SET status = ? 
        WHERE id = ?
    ");

    $stmt->bind_param(
        "si",
        $status,
        $res_atualizado['id_venda']
    );

    $stmt->execute();
    $stmt->close();

    // ======================================================
// CONSUMIR A AUTORIZAÇÃO APÓS O PAGAMENTO CONCLUÍDO
// ======================================================

    $_SESSION['pagamento_divida_autorizado'] = false;
    unset($_SESSION['usuario_admin_pagamento']);


    // ======================================================
    // 6. REGISTRAR PAGAMENTO NO LOG
    // ======================================================

    $usuario_admin = $_SESSION['usuario_admin_pagamento']
    ?? 'Desconhecido';

    $acao = "PAGOU";

    $descricao =
        "Pagamento registrado para o cliente \"" .
        $res_atualizado['nome_cliente'] .
        "\". " .
        "Valor pago: " . number_format($valor_pago, 2, ',', '.') .
        ". " .
        "Total pago anteriormente: " .
        number_format($total_pago_anterior, 2, ',', '.') .
        ". " .
        "Total pago após pagamento: " .
        number_format($total_pago_atual, 2, ',', '.') .
        ". " .
        "Saldo restante: " .
        number_format(max(0, $saldo_atual), 2, ',', '.') .
        ". " .
        "Status: " . $status . ".";

    $ip = $_SERVER['REMOTE_ADDR'] ?? null;


    $stmt_log = $conn->prepare("
        INSERT INTO log_dividas
        (
            id_devedor,
            usuario_admin,
            acao,
            descricao,
            valor_anterior,
            valor_novo,
            data_hora,
            ip
        )
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)
    ");

    $stmt_log->bind_param(
        "isssdds",
        $id_devedor,
        $usuario_admin,
        $acao,
        $descricao,
        $saldo_anterior,
        $saldo_atual,
        $ip
    );

    $stmt_log->execute();
    $stmt_log->close();


    // ======================================================
    // 7. REDIRECIONAR
    // ======================================================

    header("Location: /materiais20/devedores.php");
    exit;
}


// ==========================================================
// BUSCAR DADOS PARA EXIBIR NO FORMULÁRIO
// ==========================================================

$stmt = $conn->prepare("
    SELECT 
        d.*,
        IFNULL(SUM(p.valor_pago), 0) AS total_pago
    FROM devedores d
    LEFT JOIN pagamentos_divida p 
        ON d.id_devedor = p.id_devedor
    WHERE d.id_devedor = ?
    GROUP BY d.id_devedor
");

$stmt->bind_param("i", $id);
$stmt->execute();

$row = $stmt->get_result()->fetch_assoc();
$stmt->close();


if (!$row) {
    die("Devedor não encontrado.");
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Adicionar Pagamento - Devedor</title>

    <link rel="stylesheet" href="adicionar_pagamento.css">

</head>

<body>

<div class="container">

    <h2>
        Adicionar Pagamento para
        <?php echo htmlspecialchars($row['nome_cliente']); ?>
    </h2>


    <form method="POST">

        <div class="input-group">

            <label for="valor_pago">
                Valor do Pagamento
            </label>

            <input
                type="number"
                name="valor_pago"
                step="0.01"
                min="0.01"
                required
            >

        </div>


        <div class="input-group">

            <label for="data_pagamento">
                Data do Pagamento
            </label>

            <input
                type="date"
                name="data_pagamento"
                required
            >

        </div>


        <button
            type="submit"
            name="salvar_pagamento"
        >
            Salvar Pagamento
        </button>

    </form>

</div>

</body>

</html>