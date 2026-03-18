<?php
/**
 * proteger.php
 * Protege páginas que exigem login e define controle por nível
 */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 🔒 Verifica se o usuário está logado
if (empty($_SESSION['usuario_id']) || empty($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

// 🔐 Verifica se existe nível definido
if (!isset($_SESSION['nivel'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: login.php");
    exit;
}

/**
 * Verifica se o usuário tem um dos níveis permitidos
 */
function verificarNivel(array $niveisPermitidos = []): bool {
    if (empty($niveisPermitidos)) return true; // sem restrição
    return in_array($_SESSION['nivel'], $niveisPermitidos, true);
}

/**
 * Bloqueia acesso por nível
 */
function bloquearNivel(array $niveisPermitidos = [], string $mensagem = "Acesso negado") {
    if (!verificarNivel($niveisPermitidos)) {
        echo $mensagem;
        exit;
    }
}

/**
 * Valida páginas atuais
 * Admin: acesso total
 * Vendedor: acesso limitado
 */
function validarPaginaAtual() {
    $pagina = basename($_SERVER['PHP_SELF']);
    $nivel  = $_SESSION['nivel'] ?? '';

    // Páginas que vendedor pode acessar
    $paginasVendedor = [
        'caixa.php',
        'vender.php',
        'salvar_vendas.php',    // permite salvar venda
        'gerar_pdf_venda.php'   // permite gerar PDF
    ];

    if ($nivel === 'vendedor' && !in_array($pagina, $paginasVendedor)) {
        // Redireciona vendedor para caixa.php se tentar acessar página proibida
        header("Location: caixa.php?erro=acesso_negado");
        exit;
    }
}

// Chama automaticamente ao incluir
validarPaginaAtual();
