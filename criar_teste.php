<?php
require_once 'includes/db.php';

echo "<h2>🔧 Criador de Dados de Teste</h2>";

try {
    $pdo->beginTransaction();

    // 1. Cria o Endereço
    $pdo->exec("INSERT INTO endereco (nmLogradouro) VALUES ('Avenida da Praia, 100')");
    $idEndereco = $pdo->lastInsertId();

    // 2. Cria o Contato da Instituição (obrigatório pela sua tabela)
    $stmtContatoInst = $pdo->prepare("INSERT INTO contatos (email) VALUES (?)");
    $stmtContatoInst->execute(['contato@laresperanca.com']);
    $idContatoInst = $pdo->lastInsertId();

    // 3. Cria a Instituição (Agora com TODOS os campos obrigatórios preenchidos)
    $senhaInstHash = password_hash('123456', PASSWORD_DEFAULT); // Senha da instituição
    $stmtInst = $pdo->prepare("INSERT INTO instituicao (nmInstituicao, cnpj, senha, endereco_idEndereco, contatos_idcontatos) VALUES (?, ?, ?, ?, ?)");
    $stmtInst->execute(['Lar Esperança de Santos', '12345678000199', $senhaInstHash, $idEndereco, $idContatoInst]);
    $idInstituicao = $pdo->lastInsertId();

    // 4. Cria a Pessoa (O Funcionário)
    $stmtPessoa = $pdo->prepare("INSERT INTO pessoa (nmPessoa) VALUES (?)");
    $stmtPessoa->execute(['Administrador Teste']);
    $idPessoa = $pdo->lastInsertId();

    // 5. Cria o Contato do Funcionário (Este é o e-mail do login!)
    $emailTeste = 'funcionario@teste.com';
    $stmtContatoFunc = $pdo->prepare("INSERT INTO contatos (email) VALUES (?)");
    $stmtContatoFunc->execute([$emailTeste]);
    $idContatoFunc = $pdo->lastInsertId();

    // 6. Gera a Senha do Funcionário e amarra tudo
    $senhaLimpa = '123456';
    $senhaHash = password_hash($senhaLimpa, PASSWORD_DEFAULT);
    
    $stmtFunc = $pdo->prepare("INSERT INTO funcionario (senha, pessoa_idPessoa, contatos_idcontatos, instituicao_idinstituicao) VALUES (?, ?, ?, ?)");
    $stmtFunc->execute([$senhaHash, $idPessoa, $idContatoFunc, $idInstituicao]);

    // Confirma as inserções no banco
    $pdo->commit();

    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; font-family: sans-serif;'>";
    echo "<h3>✅ Sucesso absoluto! A linha de montagem funcionou.</h3>";
    echo "<p><strong>Instituição Criada:</strong> Lar Esperança de Santos</p>";
    echo "<p><strong>E-mail de Login (Funcionário):</strong> $emailTeste</p>";
    echo "<p><strong>Senha:</strong> $senhaLimpa</p>";
    echo "<br><a href='login.php' style='background: #5b3a26; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block;'>Ir para o Login</a>";
    echo "</div>";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; font-family: sans-serif;'>";
    echo "<h3>❌ Ocorreu um erro no banco:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
?>