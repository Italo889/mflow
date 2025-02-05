<?php
// Gerar token CSRF
function gerarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verificar token CSRF
function verificarTokenCSRF($token) {
    if (!empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}

// Proteção contra XSS
function sanitizarEntrada($dados) {
    return htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');
}

// Redirecionar com mensagem
function redirecionar($url, $mensagem = null) {
    if ($mensagem) {
        $_SESSION['mensagem'] = $mensagem;
    }
    header("Location: $url");
    exit;
}
?>