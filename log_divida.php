<?php
session_start();
require_once "conexao.php";

/*
 * Apenas administradores podem visualizar os logs
 */
if (!isset($_SESSION['usuario'])) {
    header("Location: /materiais20/login.php");
    exit;
}

if (($_SESSION['nivel'] ?? '') !== 'admin') {
    exit("Acesso negado.");
}


/*
 * Buscar os logs
 */
$sql = "
    SELECT 
        l.id_log,
        l.id_devedor,
        l.usuario_admin,
        l.acao,
        l.descricao,
        l.valor_anterior,
        l.valor_novo,
        l.data_hora,
        l.ip,
        d.nome_cliente
    FROM log_dividas l
    LEFT JOIN devedores d 
        ON d.id_devedor = l.id_devedor
    ORDER BY l.data_hora DESC
";

$result = $conn->query($sql);

if (!$result) {
    die("Erro ao consultar os logs: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="log_divida.css">

    <title>Logs das Dívidas</title>


</head>

<body>

<header>

    <h1>📋 Logs das Dívidas</h1>

</header>


<div class="container">

    <div class="card">

        <div class="topo">

            <h2>Histórico de alterações</h2>

            <a href="/materiais20/devedores.php" class="btn-voltar">
                ← Voltar para Dívidas
            </a>

        </div>


        <?php if ($result->num_rows > 0): ?>

        <table>

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Administrador</th>
                    <th>Ação</th>
                    <th>Descrição</th>
                    <th>Valor anterior</th>
                    <th>Valor novo</th>
                    <th>Data/Hora</th>
                    <th>IP</th>
                </tr>

            </thead>

            <tbody>

                <?php while ($log = $result->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?= (int)$log['id_log'] ?>
                    </td>

                    <td>
                      <a href="detalhes_divida.php?id=<?= (int)$log['id_devedor'] ?>">
                       <?= htmlspecialchars($log['nome_cliente'] ?? 'Cliente não encontrado') ?>
                      </a>
                    </td>

                    <td>
                        👤 <?= htmlspecialchars($log['usuario_admin'] ?? 'Desconhecido') ?>
                    </td>

                    <td>

                        <?php if (strtoupper($log['acao']) === 'CANCELOU'): ?>

                            <span class="cancelou">
                                CANCELOU
                            </span>

                        <?php else: ?>

                            <span class="editou">
                                <?= htmlspecialchars($log['acao']) ?>
                            </span>

                        <?php endif; ?>

                    </td>

                    <td>
                        <?= nl2br(htmlspecialchars($log['descricao'] ?? '')) ?>
                    </td>

                    <td class="valor">

                        <?php if ($log['valor_anterior'] !== null): ?>

                            <?= number_format(
                                (float)$log['valor_anterior'],
                                2,
                                ',',
                                '.'
                            ) ?>

                        <?php else: ?>

                            -

                        <?php endif; ?>

                    </td>

                    <td class="valor">

                        <?php if ($log['valor_novo'] !== null): ?>

                            <?= number_format(
                                (float)$log['valor_novo'],
                                2,
                                ',',
                                '.'
                            ) ?>

                        <?php else: ?>

                            -

                        <?php endif; ?>

                    </td>

                    <td>
                        <?= date(
                            'd/m/Y H:i:s',
                            strtotime($log['data_hora'])
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($log['ip'] ?? '-') ?>
                    </td>

                </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

        <?php else: ?>

            <div class="vazio">
                Nenhum registro de alteração de dívida foi encontrado.
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>