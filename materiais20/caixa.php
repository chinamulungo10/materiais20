<?php
require_once "proteger.php";
require_once "conexao.php";


$usuario_id = (int) $_SESSION['usuario_id'];


if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}


/* =========================
   BUSCAR CAIXA ABERTO
========================= */
$stmt = $conn->prepare("
    SELECT * FROM caixa
    WHERE usuario_id = ?
    AND status = 'aberto'
    LIMIT 1
");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$caixa = $stmt->get_result()->fetch_assoc();

/* =========================
   AÇÕES DO FORMULÁRIO
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* 🔓 ABRIR CAIXA */
    if (isset($_POST['abrir_caixa'])) {

        $valor_abertura = floatval($_POST['valor_abertura']);

        if ($valor_abertura < 0) {
            $erro = "Valor inválido.";
        } elseif ($caixa) {
            $erro = "Já existe um caixa aberto para este usuário.";
        } else {

            $stmt = $conn->prepare("
                INSERT INTO caixa
                (usuario_id, data_abertura, valor_abertura, status)
                VALUES (?, NOW(), ?, 'aberto')
            ");
            $stmt->bind_param("id", $usuario_id, $valor_abertura);
            $stmt->execute();

            header("Location: vender.php?caixa=aberto");
            exit;
        }
    }

    /* 🔒 FECHAR CAIXA */
    if (isset($_POST['fechar_caixa'])) {

        if (!$caixa) {
            $erro = "Nenhum caixa aberto.";
        } else {

            $stmtTotal = $conn->prepare("
                SELECT IFNULL(SUM(total),0) AS total
                FROM vendas
                WHERE usuario_id = ?
                AND status = 'concluida'
                AND data_venda BETWEEN ? AND NOW()
            ");
            $stmtTotal->bind_param("is", $usuario_id, $caixa['data_abertura']);
            $stmtTotal->execute();
            $totalVendas = $stmtTotal->get_result()->fetch_assoc()['total'];

            $valor_fechamento = $caixa['valor_abertura'] + $totalVendas;
            $valor_abertura = max(0, floatval($_POST['valor_abertura']));


            $stmt = $conn->prepare("
                UPDATE caixa
                SET data_fechamento = NOW(),
                    valor_fechamento = ?,
                    status = 'fechado'
                WHERE id = ?
            ");
            $stmt->bind_param("di", $valor_fechamento, $caixa['id']);
            $stmt->execute();

              unset($_SESSION['logado']);
            unset($_SESSION['usuario_id']);
            unset($_SESSION['nivel']);

            session_regenerate_id(true);

            header("Location: login.php?caixa=fechado");
            exit;

        }
    }
}
$erro = '';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Controle de Caixa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5 col-md-4 bg-white p-4 shadow rounded">

<h4 class="mb-3">Controle de Caixa</h4>
<?php if (!empty($erro)): ?>
<div class="alert alert-danger"><?= $erro ?></div>
<?php endif; ?>


<?php if (!$caixa): ?>
    <!-- 🔓 Abrir Caixa -->
    <form method="POST">
        <label>Valor de Abertura (R$)</label>
        <input type="number" step="0.01" name="valor_abertura"
               class="form-control mb-3" required>

        <button name="abrir_caixa" class="btn btn-success w-100">
            Abrir Caixa
        </button>
    </form>
<?php else: ?>
    <!-- 🔒 Fechar Caixa -->
    <p><strong>Aberto em:</strong> <?= date('d/m/Y H:i', strtotime($caixa['data_abertura'])) ?></p>
    <p><strong>Valor inicial:</strong> R$ <?= number_format($caixa['valor_abertura'],2,',','.') ?></p>

    <form method="POST">
        <button name="fechar_caixa" class="btn btn-danger w-100">
            Fechar Caixa
        </button>
    </form>
<?php endif; ?>

</div>
</body>
</html>
