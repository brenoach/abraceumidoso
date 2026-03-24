<?php
// includes/auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Função para proteger páginas
 * @param string|null $tipoPermitido 'funcionario', 'voluntario' ou null (para qualquer um)
 */
function verificarAcesso($tipoPermitido = null) {
    
    // 1. A MUDANÇA ESTÁ AQUI: Procurar por 'idPessoa' e não mais nomes antigos
    if (!isset($_SESSION['idPessoa'])) {
        // Se não tiver o crachá novo, manda pro login
        header("Location: ../pages/login.php"); 
        exit;
    }

    // 2. Verifica se o usuário tem o cargo correto (se exigido)
    if ($tipoPermitido !== null) {
        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== $tipoPermitido) {
            echo "<script>
                    alert('Acesso Negado! Você não tem permissão para acessar esta página.');
                    window.location.href = '../pages/login.php';
                  </script>";
            exit;
        }
    }
}
?>