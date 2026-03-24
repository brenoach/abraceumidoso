<?php
// includes/auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Função para proteger as páginas do sistema
 * @param string|null $tipoPermitido 'funcionario', 'voluntario' ou null
 */
function verificarAcesso($tipoPermitido = null) {
    
    // 1. Verifica se existe o crachá básico (idPessoa)
    if (!isset($_SESSION['idPessoa'])) {
        // Se não está logado, manda para o login usando o BASE_URL para não errar o caminho
        header("Location: " . BASE_URL . "pages/login.php"); 
        exit;
    }

    // 2. Se a página exige um tipo específico (ex: voluntario)
    if ($tipoPermitido !== null) {
        // Se o tipo do usuário for diferente do que a página exige
        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== $tipoPermitido) {
            
            // Em vez de só dar erro, vamos mandar para o login com uma mensagem
            header("Location: " . BASE_URL . "pages/login.php?erro=acesso_negado");
            exit;
        }
    }
}
?>