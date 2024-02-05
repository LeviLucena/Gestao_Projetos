<?php
// Inicie a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Remova todas as variáveis de sessão
session_unset();

// Destrua todas as variáveis de sessão
session_destroy();

// Impede o armazenamento em cache da página
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Verifique se há uma referência à página atual e obtenha o caminho
$currentPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// Redirecione para a página de login usando a URL completa
// Se a referência não contiver "login.php" (evita redirecionamento infinito)
if (strpos($currentPage, 'login.php') === false) {
    header("Location: login.php");
} else {
    header("Location: $currentPage");
}

exit();
?>