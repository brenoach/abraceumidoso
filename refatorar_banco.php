<?php
// ==========================================
// SCRIPT DE REFATORAÇÃO DE BANCO DE DADOS
// ==========================================

// TRAVA DE SEGURANÇA: Mude para 'false' apenas quando tiver certeza!
$modoTeste = false; 

// Pasta onde o script vai rodar ( __DIR__ pega a pasta atual )
$diretorioAlvo = __DIR__; 

// Dicionário de Substituição (De -> Para)
// A ORDEM IMPORTA: As palavras maiores devem vir primeiro para não substituir pedaços de palavras.
$substituicoes = [
    // Chaves Estrangeiras (Maior risco, vêm primeiro)
    'contatos_idcontatos'       => 'idContato',
    'instituicao_idinstituicao' => 'idInstituicao',
    'endereco_idEndereco'       => 'idEndereco',
    'voluntario_idVoluntario'   => 'idVoluntario',
    'pessoa_idPessoa'           => 'idPessoa',
    'idoso_idIdoso'             => 'idIdoso',

    // Colunas Refatoradas
    'reset_token'   => 'resetToken',
    'token_expira'  => 'tokenExpira',
    'aceita_visita' => 'aceitaVisita',
    'aceita_carta'  => 'aceitaCarta',
    'dtAgendamento' => 'dataAgendamento',
    'hrAgendamento' => 'horaAgendamento',
    'nmInstituicao' => 'nomeInstituicao',
    'nmLogradouro'  => 'nomeLogradouro',
    'tpLogradouro'  => 'tipoLogradouro',
    'dtNascimento'  => 'dataNascimento',
    'nmPessoa'      => 'nomePessoa',
    
    // Tabelas (Cuidado com variáveis que tenham o mesmo nome)
    // Usamos espaços ao redor em alguns casos para evitar mudar variáveis como $minhas_cartas
    ' cartas '   => ' carta ',
    ' contatos ' => ' contato '
];

echo "<h1>🛠️ Refatorador de Código</h1>";

if ($modoTeste) {
    echo "<h2 style='color: orange;'>⚠️ MODO TESTE ATIVADO: Nenhum arquivo será alterado.</h2>";
    echo "<p>Verifique o log abaixo. Se estiver tudo certo, mude <b>\$modoTeste = false;</b> no código.</p><hr>";
} else {
    echo "<h2 style='color: red;'>🚨 MODO REAL ATIVADO: Alterando arquivos...</h2><hr>";
}

// Prepara o leitor de diretórios (ignora pastas padrão do sistema e dependências)
$iterador = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($diretorioAlvo));

$arquivosAlterados = 0;

foreach ($iterador as $arquivo) {
    // Ignora pastas, foca só nos arquivos PHP (Ignora o vendor do Composer se houver)
    if ($arquivo->isDir() || $arquivo->getExtension() !== 'php' || strpos($arquivo->getPathname(), 'vendor') !== false) {
        continue;
    }

    // Não deixa o script alterar a si mesmo
    if ($arquivo->getFilename() === 'refatorar_banco.php') {
        continue;
    }

    $caminhoCompleto = $arquivo->getPathname();
    $conteudoOriginal = file_get_contents($caminhoCompleto);
    $conteudoNovo = $conteudoOriginal;

    // Aplica todas as substituições do nosso dicionário
    foreach ($substituicoes as $de => $para) {
        $conteudoNovo = str_replace($de, $para, $conteudoNovo);
    }

    // Se o conteúdo mudou, significa que achamos palavras antigas
    if ($conteudoOriginal !== $conteudoNovo) {
        $arquivosAlterados++;
        echo "<strong>Arquivo encontrado:</strong> " . str_replace(__DIR__, '', $caminhoCompleto) . "<br>";
        
        if (!$modoTeste) {
            // SALVA AS ALTERAÇÕES NO ARQUIVO
            file_put_contents($caminhoCompleto, $conteudoNovo);
            echo "<span style='color: green;'>✔ Atualizado com sucesso.</span><br><br>";
        } else {
            echo "<span style='color: gray;'><i>(Seria atualizado no modo real)</i></span><br><br>";
        }
    }
}

echo "<hr>";
if ($arquivosAlterados === 0) {
    echo "<h3>Nenhum arquivo precisou ser alterado.</h3>";
} else {
    echo "<h3>Total de arquivos que ". ($modoTeste ? "seriam" : "foram") ." alterados: $arquivosAlterados</h3>";
}
?>