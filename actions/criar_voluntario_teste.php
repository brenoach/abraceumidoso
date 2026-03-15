<?php
require_once '../includes/db.php';

echo "<h2>🙋‍♂️ Gerador de Voluntário para Teste</h2>";

try {
    $pdo->beginTransaction();

    // 1. Cria o Endereço (Preenchendo os campos obrigatórios da sua tabela)
    $pdo->exec("INSERT INTO endereco (cep, estado, cidade, bairro, nomeLogradouro, numero) 
                VALUES ('11000000', 'SP', 'Santos', 'Gonzaga', 'Avenida Ana Costa', '100')");
    $idEndereco = $pdo->lastInsertId();

    // 2. Cria o Contato (Este será o e-mail de login do Voluntário)
    $emailVoluntario = 'voluntario@teste.com';
    $stmtContato = $pdo->prepare("INSERT INTO contato (email) VALUES (?)");
    $stmtContato->execute([$emailVoluntario]);
    $idContato = $pdo->lastInsertId();

    // 3. Cria a Pessoa (Gerando CPF aleatório para evitar erros se rodar mais de uma vez)
    $cpfFalso = '111222' . rand(10000, 99999);
    $stmtPessoa = $pdo->prepare("INSERT INTO pessoa (nomePessoa, cpf, dataNascimento) VALUES (?, ?, ?)");
    $stmtPessoa->execute(['João Coração Bom', $cpfFalso, '1995-05-10']);
    $idPessoa = $pdo->lastInsertId();

    // 4. Cria o Voluntário amarrando tudo e gerando a senha
    $senhaLimpa = '123456';
    $senhaHash = password_hash($senhaLimpa, PASSWORD_DEFAULT);

    $stmtVoluntario = $pdo->prepare("INSERT INTO voluntario (senha, idPessoa, idEndereco, idContato) VALUES (?, ?, ?, ?)");
    $stmtVoluntario->execute([$senhaHash, $idPessoa, $idEndereco, $idContato]);

    $pdo->commit();

    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; font-family: sans-serif;'>";
    echo "<h3>✅ Sucesso! O Voluntário 'João' está pronto para doar amor!</h3>";
    echo "<p><strong>E-mail (Login):</strong> $emailVoluntario</p>";
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
