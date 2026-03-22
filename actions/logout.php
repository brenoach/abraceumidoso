<?php
session_start(); // Encontra a sessão atual
session_unset(); // Limpa todas as variáveis
session_destroy(); // Destrói a sessão no servidor

// Manda de volta para a tela de login
header("Location:../pages/login.php");
exit;
?>