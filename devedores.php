<?php
include "conexao.php";

$hoje = date("Y-m-d");
$filtro = $_GET['filtro'] ?? '';
$busca = $_GET['busca'] ?? '';

$sql = "
SELECT 
d.id_venda,
d.id_devedor,
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
WHERE d.status != 'CANCELADA'
";

$params = [];
$types = "";

/* =========================
   BUSCA POR CLIENTE
========================= */
if ($busca != "") {
    $sql .= " AND d.nome_cliente LIKE ?";
    $params[] = "%$busca%";
    $types .= "s";
}

/* =========================
   FILTRO VENCIDAS
========================= */
if ($filtro == "vencidas") {
    $sql .= " AND d.data_vencimento < ?";
    $params[] = $hoje;
    $types .= "s";
}

$sql .= "
GROUP BY 
d.id_devedor,
d.id_venda,
d.nome_cliente,
d.telefone,
d.material,
d.valor_divida,
d.data_divida,
d.data_vencimento
ORDER BY d.data_vencimento
";

$stmt = $conn->prepare($sql);

/* =========================
   BIND DINÂMICO
========================= */
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

/* =========================
   TOTAIS DO PAINEL
========================= */
$total_vencido = 0;
$total_divida = 0;
$total_pago = 0;
$total_clientes = 0;
$vencidas = 0;

$devedores = [];

while ($row = $result->fetch_assoc()) {

    $row['valor_restante'] =
        $row['valor_divida'] - $row['total_pago'];

    if (
        $row['data_vencimento'] < $hoje &&
        $row['valor_restante'] > 0
    ) {
        $vencidas++;
        $total_vencido += $row['valor_restante'];
    }

    $total_divida += $row['valor_divida'];
    $total_pago += $row['total_pago'];
    $total_clientes++;

    $devedores[] = $row;
}
?>

<!DOCTYPE html> 
<html lang="pt-BR">
<head> <meta charset="UTF-8">

<!-- =====================================
     MOSTRAR TODAS
===================================== -->

<?php if (isset($_GET['filtro']) || $busca != "") { ?>

<div class="mostrar-todas">
    <a href="devedores.php">
        ↩ Mostrar todas as dívidas
    </a>
</div>

<?php } ?>


<!-- =====================================
     TABELA DE DEVEDORES
===================================== -->

<div class="tabela-container">

<table>

<thead>
<tr>
    <th>ID</th>
    <th>Cliente</th>
    <th>Telefone</th>
    <th>Material</th>
    <th>Valor Total</th>
    <th>Total Pago</th>
    <th>Valor Restante</th>
    <th>Vencimento</th>
    <th>Status</th>
    <th>Ações</th>
</tr>
</thead>

<tbody>

<?php foreach ($devedores as $row): ?>

<?php

/* =========================
   DETERMINAR STATUS
========================= */

if ($row['valor_restante'] <= 0) {

    $status = "PAGO";

} elseif ($row['data_vencimento'] < $hoje) {

    $status = "VENCIDO";

} elseif ($row['total_pago'] > 0) {

    $status = "PARCIAL";

} else {

    $status = "ABERTO";

}

$classe = ($status == "VENCIDO") ? "vencido" : "";


/* =========================
   BUSCAR MATERIAIS DA VENDA
========================= */

$id_venda = (int)$row['id_venda'];

$res = $conn->query("
    SELECT m.nome, iv.quantidade
    FROM itens_venda iv
    LEFT JOIN materiais m
        ON iv.material_id = m.id
    WHERE iv.venda_id = $id_venda
");

$materiais = [];

while ($m = $res->fetch_assoc()) {

    $materiais[] =
        htmlspecialchars($m['nome']) .
        " (x" .
        (int)$m['quantidade'] .
        ")";

}

?>

<tr class="<?php echo $classe; ?>">

<!-- ID -->
<td>
    #<?php echo (int)$row['id_devedor']; ?>
</td>


<!-- CLIENTE -->
<td>
    <strong>
        <?php echo htmlspecialchars($row['nome_cliente']); ?>
    </strong>
</td>


<!-- TELEFONE -->
<td>
    <?php echo htmlspecialchars($row['telefone']); ?>
</td>


<!-- MATERIAIS -->
<td>

<div class="materiais">

<?php

if (!empty($materiais)) {

    echo implode("<br>", $materiais);

} else {

    echo "Nenhum material";

}

?>

</div>

</td>


<!-- VALOR TOTAL -->
<td>

<strong>

R$ <?php

echo number_format(
    $row['valor_divida'],
    2,
    ',',
    '.'
);

?>

</strong>

</td>


<!-- TOTAL PAGO -->
<td>

<span style="color:#16a34a;font-weight:bold;">

R$ <?php

echo number_format(
    $row['total_pago'],
    2,
    ',',
    '.'
);

?>

</span>

</td>


<!-- VALOR RESTANTE -->
<td>

<?php if ($row['valor_restante'] > 0) { ?>

<span class="valor-restante valor-alto">

R$ <?php

echo number_format(
    $row['valor_restante'],
    2,
    ',',
    '.'
);

?>

</span>

<?php } else { ?>

<span class="valor-restante valor-zero">

R$ 0,00

</span>

<?php } ?>

</td>


<!-- VENCIMENTO -->
<td>

📅 <?php

echo date(
    "d/m/Y",
    strtotime($row['data_vencimento'])
);

?>

</td>


<!-- STATUS -->
<td>

<?php

if ($status == "PAGO") {

    echo '<span class="status status-pago">
            ✓ PAGO
          </span>';

} elseif ($status == "VENCIDO") {

    echo '<span class="status status-vencido">
            ⚠ VENCIDO
          </span>';

} elseif ($status == "PARCIAL") {

    echo '<span class="status status-parcial">
            ◐ PARCIAL
          </span>';

} else {

    echo '<span class="status status-aberto">
            ● ABERTO
          </span>';

}

?>

</td>


<!-- AÇÕES -->
<td class="actions">

<div class="acoes-grid">

<a
   <a href="confirmar_editar_divida.php?id=<?= (int)$row['id_devedor'] ?>" 
   class="btn-acao btn-editar">
    ✏️ Editar
</a>

<button
    type="button"
    class="btn-acao btn-apagar"
    onclick="abrirModal(<?php echo (int)$row['id_devedor']; ?>)"
>
    ❌ Cancelar
</button>


<a
    href="adicionar_pagamento.php?id=<?php echo (int)$row['id_devedor']; ?>"
    class="btn-acao btn-pagar"
>
    💵 Pagamento
</a>
<a href="/materiais20/confirmar_pagamento_divida.php?id=<?= (int)$row['id_devedor'] ?>"
   class="btn-acao">
    💰 Adicionar Pagamento
</a>
<a href="/materiais20/confirmar_editar_divida.php?id=<?= (int)$row['id_devedor'] ?>"
   class="btn-acao btn-editar">
    ✏️ Editar
</a>


<a
    href="gerar_pdf_divida.php?id=<?php echo (int)$row['id_devedor']; ?>"
    target="_blank"
    class="btn-acao btn-pdf"
>
    📄 PDF
</a>


<a
    href="https://wa.me/55<?php echo $row['telefone']; ?>?text=<?php echo urlencode(
        "Olá ".$row['nome_cliente'].
        ", estamos entrando em contato sobre sua dívida no valor de R$ ".
        number_format(
            $row['valor_restante'],
            2,
            ',',
            '.'
        ).
        " com vencimento em ".
        date(
            "d/m/Y",
            strtotime($row['data_vencimento'])
        ).
        ". Por favor regularize. Obrigado."
    ); ?>"
    target="_blank"
    class="btn-acao btn-whatsapp"
>
    📱 WhatsApp
</a>


</div>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>


<!-- =====================================
     GRÁFICO
===================================== -->

<div class="grafico-card">

    <h2>📊 Resumo Financeiro</h2>

    <div class="grafico-area">

        <canvas id="graficoFinanceiro"></canvas>

    </div>

</div>


<!-- =====================================
     MODAL CANCELAR DÍVIDA
===================================== -->

<div id="modalCancelamento" class="modal">

    <div class="modal-conteudo">

        <h2>
            ⚠️ Cancelar Dívida
        </h2>

        <p>
            Esta operação irá cancelar a dívida selecionada.
            Digite a senha do administrador para continuar.
        </p>

        <form
    method="POST"
    action="cancelar_divida.php"
>

    <input
        type="hidden"
        name="id_devedor"
        id="id_devedor_modal"
    >

    <label>
        Usuário administrador
    </label>

    <input
        type="text"
        name="usuario_admin"
        placeholder="Usuário do administrador"
        required
    >

    <label>
        Senha do administrador
    </label>

    <input
        type="password"
        name="senha_admin"
        id="senha_admin_modal"
        placeholder="Senha do administrador"
        required
    >

    <div class="modal-botoes">

        <button
            type="button"
            class="btn-fechar"
            onclick="fecharModal()"
        >
            Voltar
        </button>

        <button
            type="submit"
            class="btn-confirmar"
        >
            Confirmar Cancelamento
        </button>

    </div>

</form>

    </div>

</div>


<!-- =====================================
     CHART.JS
===================================== -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

function abrirModal(idDevedor) {

    const modal = document.getElementById("modalCancelamento");
    const campoId = document.getElementById("id_devedor_modal");
    const campoSenha = document.getElementById("senha_admin_modal");

    if (!modal) {
        alert("Erro: janela de cancelamento não encontrada.");
        return;
    }

    if (!campoId) {
        alert("Erro: campo do devedor não encontrado.");
        return;
    }

    campoId.value = idDevedor;

    modal.style.display = "flex";

    if (campoSenha) {
        setTimeout(function() {
            campoSenha.focus();
        }, 100);
    }
}


function fecharModal() {

    const modal = document.getElementById("modalCancelamento");

    if (modal) {
        modal.style.display = "none";
    }

    const campoSenha =
        document.getElementById("senha_admin_modal");

    if (campoSenha) {
        campoSenha.value = "";
    }
}


/* Fechar clicando fora do modal */

window.addEventListener("click", function(event) {

    const modal =
        document.getElementById("modalCancelamento");

    if (modal && event.target === modal) {
        fecharModal();
    }

});


/* Fechar com ESC */

document.addEventListener("keydown", function(event) {

    if (event.key === "Escape") {
        fecharModal();
    }

});

</script>

</body>

</html>








