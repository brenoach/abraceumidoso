<?php
// --- CONFIGURAÇÃO DO BANCO ---
$host = 'localhost';
$user = 'root';      // Seu usuário do MariaDB
$pass = '';          // Sua senha do MariaDB
$db   = 'teste'; 

// 1. Cria a conexão com o banco
$conn = new mysqli($host, $user, $pass, $db);

// 2. Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// 3. Verifica se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 4. Recebe os dados do formulário
    $nome   = $_POST['nome'];
    $email  = $_POST['email'];
    $cep    = $_POST['cep'];
    $rua    = $_POST['rua'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    // 5. Prepara o SQL (Prepared Statements aumentam a segurança contra invasões)
    // Os '?' são espaços reservados para os valores que virão a seguir
    $sql = "INSERT INTO voluntarios (nome, email, cep, rua, bairro, cidade, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    // 6. Inicializa o statement
    $stmt = $conn->prepare($sql);

    // 7. "Bind" (Vincula) as variáveis aos '?' do SQL
    // "sssssss" significa que são 7 strings (s = string)
    $stmt->bind_param("sssssss", $nome, $email, $cep, $rua, $bairro, $cidade, $estado);

    // 8. Executa o comando no banco
    if ($stmt->execute()) {
        echo "Voluntário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    // 9. Fecha a conexão para liberar memória
    $stmt->close();
    $conn->close();
}
?>