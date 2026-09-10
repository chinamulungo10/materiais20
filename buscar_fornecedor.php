<?php
include "conexao.php";

$pesquisa = isset($_GET['q']) ? $_GET['q'] : "";
$pesquisaSQL = "%" . $pesquisa . "%";
$id = is_numeric($pesquisa) ? (int)$pesquisa : 0;

$stmt = $conn->prepare("SELECT * FROM fornecedores 
WHERE 
    nome LIKE ? OR
    telefone LIKE ? OR
    tipo_material LIKE ? OR
    id_fornecedor = ?
ORDER BY nome");

$stmt->bind_param("sssi", 
    $pesquisaSQL, 
    $pesquisaSQL, 
    $pesquisaSQL, 
    $id
);

$stmt->execute();
$result = $stmt->get_result();
?>

<table id="tabelaFornecedores" class="display">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Material</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { 
            $tel = preg_replace('/[^0-9]/', '', $row['telefone']);
        ?>
        <tr>
            <td><?= $row['id_fornecedor'] ?></td>
            <td><?= $row['nome'] ?></td>
            <td><?= $row['telefone'] ?></td>
            <td><?= $row['email'] ?? '-' ?></td>
            <td><?= $row['tipo_material'] ?? '-' ?></td>
            <td>
                <a target="_blank" href="https://wa.me/55<?= $tel ?>">📱</a>
                <a href="mailto:<?= $row['email'] ?>">✉️</a>
                <a href="editar_fornecedor.php?id=<?= $row['id_fornecedor'] ?>">✏️</a>
                <a href="apagar_fornecedor.php?id=<?= $row['id_fornecedor'] ?>" onclick="return confirm('Tem certeza?')">🗑️</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<div class="table-container">
    <div id="tabela"></div>
</div>

<div class="pagination" id="paginacao"></div>
<script>
let paginaAtual = 1;

function carregar(pagina = 1) {
    paginaAtual = pagina;

    let pesquisa = document.getElementById("pesquisa")?.value || "";

    fetch(`listar_fornecedor.php?q=${pesquisa}&pagina=${pagina}`)
        .then(res => res.text())
        .then(data => {
            document.getElementById("tabela").innerHTML = data;
            atualizarBotoes();
        });
}

function atualizarBotoes() {
    let botoes = document.querySelectorAll(".page-btn");

    botoes.forEach(btn => {
        btn.classList.remove("active");
        if (btn.innerText == paginaAtual) {
            btn.classList.add("active");
        }
    });
}

/* Carregar ao abrir */
carregar();

$(document).ready(function () {
    $('#tabelaFornecedores').DataTable({
        "pageLength": 5, // itens por página
        "lengthMenu": [5, 10, 20, 50],
        "scrollY": "300px", // altura do scroll
        "scrollCollapse": true,
        "paging": true,
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "Nada encontrado",
            "info": "Mostrando _PAGE_ de _PAGES_",
            "infoEmpty": "Sem dados",
            "search": "Pesquisar:",
            "paginate": {
                "next": "Próximo",
                "previous": "Anterior"
            }
        }
    });
});
echo "<div class='pagination'>";
for ($i = 1; $i <= $totalPaginas; $i++) {
    echo "<button class='page-btn' onclick='carregar($i)'>$i</button>";
}
echo "</div>";
</script>

