<?php
// includes/auth.php

// 1. Inicia a sessão se ela ainda não existir
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Função para proteger páginas
 * @param string|null $tipoPermitido 'funcionario', 'voluntario' ou null (para qualquer um)
 */
function verificarAcesso($tipoPermitido = null) {
    
    // VERIFICAÇÃO 1: O usuário está logado?
    if (!isset($_SESSION['usuario_id'])) {
        // Se não estiver, manda pro login
        header("Location: /abraceumidoso/PI/login.php"); // Ajuste o caminho se necessário
        exit;
    }

    // VERIFICAÇÃO 2: O usuário tem o cargo correto?
    // Só verificamos isso se passarmos um tipo específico na função
    if ($tipoPermitido !== null) {
        if ($_SESSION['usuario_tipo'] !== $tipoPermitido) {
            // Se ele logou, mas tentou entrar onde não devia:
            echo "<script>
                    alert('Acesso Negado! Você não tem permissão para acessar esta página.');
                    window.history.back(); // Volta para a página anterior
                  </script>";
            
            // Ou redireciona para o painel dele
            // header("Location: ../index.php");
            exit;
        }
    }
}
?>