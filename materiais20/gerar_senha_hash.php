<?php
$hash = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';

    if (!empty($senha)) {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Gerar Hash de Senha</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
function copiarHash() {
    const campo = document.getElementById("hash");
    campo.select();
    campo.setSelectionRange(0, 99999);
    document.execCommand("copy");
    alert("Hash copiado com sucesso!");
}

function limparCampos() {
    document.getElementById("senha").value = "";
    document.getElementById("hash").value = "";
}
</script>

</head>

<body class="bg-light">

<div class="card shadow-sm" style="max-width: 420px; width:100%;">
    <div class="card-body">

        <h5 class="text-center mb-4">🔐 Gerar Hash de Senha</h5>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="text"
                       id="senha"
                       name="senha"
                       class="form-control form-control-sm"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Hash gerado</label>
                <textarea id="hash"
                          class="form-control form-control-sm"
                          rows="3"
                          readonly><?= htmlspecialchars($hash) ?></textarea>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-success btn-sm">
                    🔄 Gerar Hash
                </button>

                <button type="button"
                        onclick="copiarHash()"
                        class="btn btn-primary btn-sm">
                    📋 Copiar Hash
                </button>

                <button type="button"
                        onclick="limparCampos()"
                        class="btn btn-secondary btn-sm">
                    🧹 Limpar Campos
                </button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
