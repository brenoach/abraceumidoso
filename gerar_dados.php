<?php
// Liga o Raio-X de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Puxa a sua conexão com o banco (ajuste o caminho se necessário)
require_once './includes/db.php'; 

try {
    // Inicia a transação (se der erro no meio, ele desfaz tudo para não sujar o banco)
    $pdo->beginTransaction();

    echo "<h1>Gerando Dados para o Abrace um Idoso...</h1>";

    $senhaPadrao = password_hash('123456', PASSWORD_DEFAULT);

    // ==========================================================
    // 1. CRIAR UMA INSTITUIÇÃO BASE (Obrigatório para Idosos e Funcionários)
    // ==========================================================
    // Cria Endereço
    $stmt = $pdo->prepare("INSERT INTO endereco (cep, estado, cidade, bairro, numero, nomeLogradouro) VALUES ('11015000', 'SP', 'Santos', 'Centro', '100', 'Rua General Câmara')");
    $stmt->execute();
    $idEnderecoInst = $pdo->lastInsertId();

    // Cria Contato
    $stmt = $pdo->prepare("INSERT INTO contato (email, telefone) VALUES ('contato@lardosavos.com.br', '1332220000')");
    $stmt->execute();
    $idContatoInst = $pdo->lastInsertId();

    // Cria Instituição
    $stmt = $pdo->prepare("INSERT INTO instituicao (nomeInstituicao, fotoInstituicao, cnpj, senha, idContato, idEndereco) VALUES ('Lar dos Avós', 'https://images.unsplash.com/photo-1538356111053-748a48e1acb8?auto=format&fit=crop&w=300&q=80', '11222333000199', :senha, :idContato, :idEndereco)");
    $stmt->execute([':senha' => $senhaPadrao, ':idContato' => $idContatoInst, ':idEndereco' => $idEnderecoInst]);
    $idInstituicao = $pdo->lastInsertId();

    // ==========================================================
    // 2. CRIAR FUNCIONÁRIO TESTE (Breno Cunha)
    // ==========================================================
    $stmt = $pdo->prepare("INSERT INTO pessoa (nomePessoa, cpf, dataNascimento, fotoPerfil, sobre) VALUES ('Breno Cunha', '12345678901', '1983-05-15', 'https://randomuser.me/api/portraits/men/32.jpg', 'Desenvolvedor do sistema e gestor do Lar.')");
    $stmt->execute();
    $idPessoaBreno = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO contato (email, celular) VALUES ('breno@abraceumidoso.com', '13999999999')");
    $stmt->execute();
    $idContatoBreno = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO funcionario (cargo, senha, idPessoa, idInstituicao, idContato) VALUES ('Administrador', :senha, :idPessoa, :idInst, :idContato)");
    $stmt->execute([':senha' => $senhaPadrao, ':idPessoa' => $idPessoaBreno, ':idInst' => $idInstituicao, ':idContato' => $idContatoBreno]);
    echo "<p>✅ Funcionário Breno Cunha criado!</p>";

    // ==========================================================
    // 3. CRIAR VOLUNTÁRIO TESTE
    // ==========================================================
    $stmt = $pdo->prepare("INSERT INTO pessoa (nomePessoa, cpf, dataNascimento, fotoPerfil, sobre) VALUES ('Aline Silva', '98765432100', '1990-10-20', 'https://randomuser.me/api/portraits/women/44.jpg', 'Adoro ouvir histórias antigas e tomar café da tarde.')");
    $stmt->execute();
    $idPessoaVolun = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO contato (email, celular) VALUES ('aline.voluntaria@teste.com', '13988888888')");
    $stmt->execute();
    $idContatoVolun = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO voluntario (senha, idContato, idEndereco, idPessoa) VALUES (:senha, :idContato, :idEndereco, :idPessoa)");
    $stmt->execute([':senha' => $senhaPadrao, ':idContato' => $idContatoVolun, ':idEndereco' => $idEnderecoInst /*usando o mesmo endereco base*/, ':idPessoa' => $idPessoaVolun]);
    echo "<p>✅ Voluntária Aline criada!</p>";

    // ==========================================================
    // 4. CRIAR 10 IDOSOS (Loop)
    // ==========================================================
    $idosos = [
        ['Seu Joaquim', '11111111111', '1945-03-12', 'https://randomuser.me/api/portraits/men/78.jpg', 'Trabalhou a vida toda no porto. Adora conversar sobre os navios antigos e jogar dominó.'],
        ['Dona Maria Helena', '22222222222', '1939-08-25', 'https://randomuser.me/api/portraits/women/68.jpg', 'Foi professora do ensino fundamental. Perdeu parte da visão e procura alguém para ler poesias para ela.'],
        ['Senhor Antônio', '33333333333', '1935-11-03', 'https://randomuser.me/api/portraits/men/65.jpg', 'Ex-músico, tocava sanfona nos bailes da região. Gosta de ouvir rádio e cantarolar velhas melodias.'],
        ['Dona Odete', '44444444444', '1942-02-18', 'https://randomuser.me/api/portraits/women/72.jpg', 'Dedicou a vida aos quatro filhos. Hoje sonha com visitas para tomar um chá e aprender a usar o WhatsApp.'],
        ['Seu Benedito', '55555555555', '1940-07-09', 'https://randomuser.me/api/portraits/men/80.jpg', 'Pescador caiçara aposentado. Tem as melhores histórias de pescaria e ensina a fazer nós de marinheiro.'],
        ['Dona Lourdes', '66666666666', '1938-12-30', 'https://randomuser.me/api/portraits/women/89.jpg', 'Uma cozinheira de mão cheia. Infelizmente não pode mais cozinhar, mas adora passar suas receitas secretas adiante.'],
        ['Professor Carlos', '77777777777', '1941-05-22', 'https://randomuser.me/api/portraits/men/55.jpg', 'Apaixonado por história do Brasil. Procura jovens com quem possa debater sobre o mundo e jogar xadrez.'],
        ['Dona Zilda', '88888888888', '1946-09-14', 'https://randomuser.me/api/portraits/women/50.jpg', 'Super vaidosa, adora pintar as unhas e arrumar os cabelos. Quer companhia para tardes de "salão de beleza" e fofocas.'],
        ['Seu Francisco', '99999999999', '1933-01-05', 'https://randomuser.me/api/portraits/men/90.jpg', 'O mais velhinho da turma. Fala pouco, mas seu sorriso ilumina a sala quando alguém simplesmente senta ao lado dele.'],
        ['Dona Carmem', '10101010101', '1948-04-11', 'https://randomuser.me/api/portraits/women/40.jpg', 'Ama plantas e suculentas. Precisa de ajuda para cuidar de sua pequena horta na janela e adora música clássica.']
    ];

    foreach ($idosos as $i => $idoso) {
        $stmt = $pdo->prepare("INSERT INTO pessoa (nomePessoa, cpf, dataNascimento, fotoPerfil, sobre) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$idoso[0], $idoso[1], $idoso[2], $idoso[3], $idoso[4]]);
        $idPessoaIdoso = $pdo->lastInsertId();

        // Insere o Idoso ligando com a pessoa e a Instituição base
        $stmt = $pdo->prepare("INSERT INTO idoso (idPessoa, idInstituicao, aceitaVisita, aceitaCarta) VALUES (?, ?, 1, 1)");
        $stmt->execute([$idPessoaIdoso, $idInstituicao]);
        
        echo "<p>✅ Idoso " . ($i+1) . " cadastrado: {$idoso[0]}</p>";
    }

    $pdo->commit();
    echo "<h2 style='color:green;'>🎉 Todos os dados foram inseridos com sucesso!</h2>";
    echo "<p><strong>Email Funcionário:</strong> breno@abraceumidoso.com | <strong>Senha:</strong> 123456</p>";
    echo "<p><strong>Email Voluntária:</strong> aline.voluntaria@teste.com | <strong>Senha:</strong> 123456</p>";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "<h2 style='color:red;'>Erro ao inserir dados: " . $e->getMessage() . "</h2>";
}
?>