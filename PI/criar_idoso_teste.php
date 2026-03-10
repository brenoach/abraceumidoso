<?php
require_once 'includes/db.php';

echo "<h2>👴 Gerador de Idoso para Teste</h2>";

try {
    $pdo->beginTransaction();

    // 1. Pega a primeira instituição que existe no banco para morar o idoso
    $stmtInst = $pdo->query("SELECT idinstituicao FROM instituicao LIMIT 1");
    $instituicao = $stmtInst->fetch(PDO::FETCH_ASSOC);

    if (!$instituicao) {
        die("<h3 style='color:red;'>Erro: Nenhuma instituição encontrada no banco.</h3>");
    }
    $idInstituicao = $instituicao['idinstituicao'];

    // 2. Cria a Pessoa (Geramos um CPF aleatório para não dar erro de duplicidade se você rodar 2 vezes)
    $cpfFalso = '987654' . rand(10000, 99999); 
    
    $stmtPessoa = $pdo->prepare("INSERT INTO pessoa (nmPessoa, cpf, dtNascimento, sobre) VALUES (?, ?, ?, ?)");
    $stmtPessoa->execute([
        'Seu Joaquim Silva', 
        $cpfFalso, 
        '1945-08-20', 
        'Adora jogar dominó, ouvir músicas antigas e contar histórias da sua época de marinheiro em Santos.'
    ]);
    $idPessoa = $pdo->lastInsertId();

    // 3. Cadastra como Idoso (Amarrando a pessoa e a instituição)
    $stmtIdoso = $pdo->prepare("INSERT INTO idoso (pessoa_idPessoa, instituicao_idinstituicao, necessidades, aceita_visita) VALUES (?, ?, ?, ?)");
    $stmtIdoso->execute([
        $idPessoa, 
        $idInstituicao, 
        'Hipertensão controlada. Usa andador.', 
        1 // 1 significa que a caixinha "aceita visita" está marcada
    ]);

    // Confirma tudo no banco
    $pdo->commit();

    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; font-family: sans-serif;'>";
    echo "<h3>✅ Sucesso! Seu Joaquim já está na Vitrine!</h3>";
    echo "<p>Agora você pode fazer login como <strong>Voluntário</strong> e testar o agendamento.</p>";
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