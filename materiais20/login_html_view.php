<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">

            <div class="card shadow">
                <div class="card-body">

                    <h4 class="text-center mb-3">Login</h4>

                    <?php if (!empty($erro)): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($erro) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="login.php" autocomplete="off">

                        <div class="mb-3">
                            <label class="form-label">Usuário</label>
                            <input type="text" name="usuario" class="form-control" autocomplete="off" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" autocomplete="new-password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Entrar
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
