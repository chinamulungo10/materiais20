<?php
session_start();
require "proteger.php";
require "conexao.php";
require "log.php";

$usuario = $_SESSION['usuario'] ?? 'Usuário';
$nivel   = $_SESSION['nivel'] ?? '';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Verificar caixa aberto
$stmt = $conn->prepare("
    SELECT id FROM caixa
    WHERE usuario_id = ?
      AND status = 'aberto'
    LIMIT 1
");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    header("Location: caixa.php?erro=caixa_fechado");
    exit;
}

// Materiais disponíveis
$materiais = [];
$result = $conn->query("
    SELECT id, nome, custo_venda, codigo_barra
    FROM materiais
    ORDER BY nome
");
while ($row = $result->fetch_assoc()) {
    $materiais[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Venda de Materiais</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="vender.css" rel="stylesheet">

<script>
const materiais = <?= json_encode($materiais, JSON_UNESCAPED_UNICODE) ?>;
let vendaEnviada = false;

function normalizar(texto) {
    return texto
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .trim();
}

function adicionarMaterial() {
    let campo = document.getElementById('buscaMaterial');
    let busca = normalizar(campo.value);
    let tbody = document.getElementById('materiaisBody');
    if (!busca) return;

    let encontrados = [];
    if (!isNaN(busca)) {
        encontrados = materiais.filter(m =>
            String(m.id) === busca || String(m.codigo_barra) === busca
        );
    } else {
        let item = materiais.find(m => normalizar(m.nome).includes(busca));
        if (item) encontrados.push(item);
    }

    if (!encontrados.length) {
        alert("Material não encontrado!");
        return;
    }

    encontrados.forEach(m => {
        if (document.getElementById('materialRow' + m.id)) return;

        let row = document.createElement('tr');
        row.id = 'materialRow' + m.id;
        row.className = 'material-row';

        row.innerHTML = `
<td>
    <button type="button" class="btn btn-sm btn-danger" onclick="removerMaterial(${m.id})">✖</button>
    <input type="hidden" name="materiais[${m.id}][id]" value="${m.id}">
    <input type="hidden" name="materiais[${m.id}][nome]" value="${m.nome}">
    <input type="hidden" name="materiais[${m.id}][preco]" value="${m.custo_venda}">
</td>
<td>${m.nome}</td>
<td class="preco">${parseFloat(m.custo_venda).toFixed(2)}</td>
<td><input type="number" name="materiais[${m.id}][quantidade]" class="form-control qtd" min="1" value="1" oninput="calcularTotal()"></td>
<td class="total">${parseFloat(m.custo_venda).toFixed(2)}</td>
        `;
        tbody.appendChild(row);
    });

    campo.value = '';
    calcularTotal();
    campo.focus();
}

function removerMaterial(id) {
    let row = document.getElementById('materialRow' + id);
    if (row) {
        row.remove();
        calcularTotal();
        document.getElementById('buscaMaterial').focus();
    }
}

function calcularTotal() {
    let totalCompra = 0;
    document.querySelectorAll('.material-row').forEach(row => {
        let preco = parseFloat(row.querySelector('.preco').innerText.replace(',', '.')) || 0;
        let qtd = parseInt(row.querySelector('.qtd').value) || 0;
        if (qtd < 1) {
            qtd = 1;
            row.querySelector('.qtd').value = 1;
        }
        let total = preco * qtd;
        row.querySelector('.total').innerText = total.toFixed(2);
        totalCompra += total;
    });
    document.getElementById('totalCompra').innerText = totalCompra.toFixed(2);
}

function validarVenda() {
    if (vendaEnviada) return false;
    if (!document.querySelectorAll('.material-row').length) {
        alert("Nenhum produto adicionado à venda.");
        return false;
    }
    vendaEnviada = true;
    return true;
}

function confirmarVenda() {
    if (!validarVenda()) return false;
    if (!confirm("Deseja finalizar a venda e gerar o PDF?")) return false;
    return true;
}

// Limpar campos (após venda concluída, se necessário)
function limparCampos() {
    document.querySelector('form').reset();
    document.getElementById('materiaisBody').innerHTML = '';
    document.getElementById('totalCompra').innerText = '0.00';
    document.getElementById('buscaMaterial').focus();
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('buscaMaterial').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            adicionarMaterial();
        }
    });
});
</script>
</head>

<body class="bg-light">
<div class="container-fluid pdv-container">

<!-- HEADER -->
<div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
    <strong>🛒 Venda de Materiais</strong>
    <div class="d-flex align-items-center gap-3">
        <span class="fw-semibold">
            👤 <?= htmlspecialchars($usuario) ?>
            <span class="badge <?= $nivel === 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                <?= strtoupper($nivel) ?>
            </span>
        </span>
        <a href="logout.php" class="btn btn-sm btn-outline-light"
           onclick="return confirm('Deseja realmente sair do sistema?')">
            🔓 Sair
        </a>
    </div>
</div>

<!-- CORPO -->
<div class="pdv-body p-3">

<?php
// Mensagem de sucesso após venda
if (!empty($_SESSION['venda_sucesso'])) {
    echo '<div class="alert alert-success">' . $_SESSION['venda_sucesso'] . '</div>';
    unset($_SESSION['venda_sucesso']);
}
?>

<form action="salvar_vendas.php" method="POST" onsubmit="return confirmarVenda()" class="d-flex flex-column h-100">

    <!-- CLIENTE + TOTAL -->
    <div class="row mb-3">
        <div class="col-md-8">
            <label class="form-label">Cliente</label>
            <input type="text" name="cliente" class="form-control form-control-lg" placeholder="Nome do cliente" required>
        </div>
        <div class="col-md-4 text-end">
            <label class="form-label">Total da Venda</label>
            <div class="fs-1 fw-bold text-success">
                R$ <span id="totalCompra">0.00</span>
            </div>
        </div>
    </div>

    <!-- BUSCA -->
    <div class="row mb-3">
        <div class="col-md-8">
            <input type="text" id="buscaMaterial" class="form-control form-control-lg"
                   placeholder="Digite nome, código ou ID e ENTER">
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-primary btn-lg w-100" onclick="adicionarMaterial()">
                ➕ Adicionar Material
            </button>
        </div>
    </div>

    <!-- PAGAMENTO / DESCONTO -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label>Forma de Pagamento</label>
            <select name="forma_pagamento" class="form-control" required>
                <option value="">Selecione</option>
                <option value="dinheiro">Dinheiro</option>
                <option value="pix">PIX</option>
                <option value="cartao">Cartão</option>
                <option value="cheque">Cheque</option>
                <option value="transf_bancaria">Transferencia Bancaria</option>
                <option value="mpesa">Mpesa</option>
                <option value="emola">Emola</option>
            </select>
        </div>

        <div class="col-md-4">
            <label>Tipo de Desconto</label>
            <select name="desconto_tipo" class="form-control">
                <option value="valor">Valor (R$)</option>
                <option value="percentual">Percentual (%)</option>
            </select>
        </div>

        <div class="col-md-4">
            <label>Valor do Desconto</label>
            <input type="number" name="desconto_valor" class="form-control" step="0.01" value="0">
        </div>
    </div>

    <!-- LISTA DE ITENS -->
    <div class="pdv-itens mb-3">
        <table class="table table-bordered mb-0">
            <thead class="table-secondary sticky-top">
                <tr>
                    <th>Remover</th>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th width="120">Qtd</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="materiaisBody"></tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="pdv-footer pt-2">
        <button type="submit" id="btnConfirmar" class="btn btn-success btn-lg w-100">
            ✔ Confirmar Venda e Gerar PDF
        </button>
    </div>

</form>
</div>
</div>
</body>
</html>
