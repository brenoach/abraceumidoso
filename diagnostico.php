<?php
// Exibir erros caso algo quebre feio
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🩺 Raio-X do Sistema: Abrace um Idoso</h1><hr>";

// ==========================================
// 1. TESTE DE ARQUIVOS VITAIS
// ==========================================
echo "<h3>📁 1. Verificando Arquivos Essenciais</h3>";
$arquivosVitais = [
    'includes/db.php',
    'includes/helpers.php',
    'includes/header.php',
    'includes/footer.php',
    'actions/callback_google.php',
    'pages/login.php',
    'pages/painel_voluntario.php',
    'pages/painel_funcionario.php'
];

$arquivosFaltando = 0;
foreach ($arquivosVitais as $arquivo) {
    if (file_exists(__DIR__ . '/' . $arquivo)) {
        echo "<span style='color: green;'>✅ Encontrado:</span> $arquivo <br>";
    } else {
        echo "<span style='color: red;'>❌ FALTANDO:</span> $arquivo <br>";
        $arquivosFaltando++;
    }
}

if ($arquivosFaltando === 0) {
    echo "<p style='color: green; font-weight: bold;'>Todos os arquivos vitais estão no lugar!</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>Atenção: Você tem $arquivosFaltando arquivo(s) faltando. Isso vai gerar erros no site.</p>";
}
echo "<hr>";

// ==========================================
// 2. TESTE DE CONEXÃO E TABELAS DO BANCO
// ==========================================
echo "<h3>🗄️ 2. Verificando Banco de Dados</h3>";

if (file_exists(__DIR__ . '/includes/db.php')) {
    try {
        require_once 'includes/db.php';
        echo "<span style='color: green;'>✅ Conexão com o Banco estabelecida com sucesso!</span><br><br>";

        // Testando se as tabelas da refatoração existem
        $tabelasEsperadas = ['contato', 'endereco', 'pessoa', 'voluntario', 'instituicao', 'idoso', 'carta', 'funcionario', 'agendamento', 'disponibilidade'];
        
        $tabelasFaltando = 0;
        foreach ($tabelasEsperadas as $tabela) {
            $stmt = $pdo->query("SHOW TABLES LIKE '$tabela'");
            if ($stmt->rowCount() > 0) {
                echo "<span style='color: green;'>✅ Tabela OK:</span> $tabela <br>";
            } else {
                echo "<span style='color: red;'>❌ TABELA NÃO ENCONTRADA:</span> $tabela <br>";
                $tabelasFaltando++;
            }
        }

        if ($tabelasFaltando === 0) {
            echo "<p style='color: green; font-weight: bold;'>O Banco de Dados está com todas as tabelas no novo padrão!</p>";
        }

    } catch (PDOException $e) {
        echo "<span style='color: red;'>❌ ERRO CRÍTICO NO BANCO:</span> Não foi possível conectar. <br>";
        echo "Detalhe: " . $e->getMessage();
    }
} else {
    echo "<span style='color: red;'>❌ Não foi possível testar o banco porque o arquivo includes/db.php sumiu!</span>";
}

echo "<hr>";
echo "<h3>🏁 Conclusão</h3>";
echo "<p>Se tudo acima estiver verde, a estrutura do seu código e do banco estão prontas para rodar o site. Se houver algum 'X' vermelho, é lá que o sistema vai quebrar quando o usuário clicar.</p>";
?>