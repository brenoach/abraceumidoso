<?php

class Html {
    
    // Método Estático para desenhar o cabeçalho
    public static function cabecalho($titulo = "Lar dos Idosos") {
        // Verifica se existe sessão (para mudar o menu)
        $logado = isset($_SESSION['usuario_id']);
        
        echo '<!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <title>' . $titulo . '</title>
            <link rel="stylesheet" href="assets/css/style.css">
        </head>
        <body>
            <header class="cabecalho">
                <a href="index.php" class="logo"><img src="assets/img/logo.jpg" alt="Logo"></a>
                <nav class="nav-menu">
                    <ul>';
                        
        if ($logado) {
            echo '<li><a href="painel.php">Meu Painel</a></li>';
            echo '<li><a href="actions/logout.php">Sair</a></li>';
        } else {
            echo '<li><a href="login.php">Login</a></li>';
            echo '<li><a href="cadastro_voluntario.php">Seja Voluntário</a></li>';
        }

        echo '      </ul>
                </nav>
            </header>';
    }

    public static function rodape() {
        echo '<footer class="rodape"><p>&copy; 2026 Lar dos Idosos</p></footer>';
        echo '</body></html>';
    }
}
?>