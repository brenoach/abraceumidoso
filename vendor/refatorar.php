<?php
// LISTA DE SUBSTITUIÇÕES (Antigo => Novo)
$substituicoes = [
    // Banco de Dados
    'nmPessoa' => 'nomePessoa',
    'nmInstituicao' => 'nomeInstituicao',
    'idcontatos' => 'idContato',
    'id_pessoa' => 'idPessoa',
    'tipo_usuario' => 'tipoUsuario',
    
    // Caminhos de Pastas (ajustando para o novo padrão)
    'assets/img/' => 'assets/images/',
    '../includes/db.php' => __DIR__ . '/includes/db.php',
];

// Pastas que o script vai ignorar (não mexer no vendor!)
$pastasIgnoradas = ['vendor', '.git', 'node_modules'];

$diretorio = new RecursiveDirectoryIterator(__DIR__);
$iterator = new RecursiveIteratorIterator($diretorio);

foreach ($iterator as $arquivo) {
    if ($arquivo->isFile() && $arquivo->getExtension() === 'php') {
        
        // Pular pastas ignoradas
        foreach ($pastasIgnoradas as $ignorada) {
            if (strpos($arquivo->getPathname(), $ignorada) !== false) continue 2;
        }

        $conteudoOriginal = file_get_contents($arquivo->getPathname());
        $novoConteudo = strtr($conteudoOriginal, $substituicoes);

        if ($conteudoOriginal !== $novoConteudo) {
            file_put_contents($arquivo->getPathname(), $novoConteudo);
            echo "✅ Atualizado: " . $arquivo->getFilename() . "<br>";
        }
    }
}
echo "--- Refatoração Concluída ---";