<?php

ob_start();
session_start();

// 1. Configurações iniciais
ini_set('display_errors', 1);
error_reporting(E_ALL);

// require __DIR__ .'connection/config.php';
// require_once ROOT_PATH .'/includes/db.php';
// require_once ROOT_PATH .'includes/helpers.php'; 

require_once 'connection/config.php';
require_once 'includes/db.php';
require_once 'includes/helpers.php';

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// 2. Captura a URL
$url = $_GET['url'] ?? 'home'; 
$url = rtrim($url, '/');

// 3. O Roteador
switch ($url) {
    
// ==========================================
    // 1. ROTAS PÚBLICAS (Sem restrição de acesso)
    // ==========================================
    case 'home':
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/home_content.php'; 
        include ROOT_PATH .'includes/footer.php';
        break;

    case 'login':
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/login.php';
        include ROOT_PATH .'includes/footer.php';
        break;

    case 'cadastro-voluntario':
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/cadastro_voluntario.php';
        include ROOT_PATH .'includes/footer.php';
        break;

    case 'cadastro-funcionario':
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/cadastro_funcionario.php';
        include ROOT_PATH .'includes/footer.php';
        break;

    case 'esqueci-senha':
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/esqueci_senha.php';
        include ROOT_PATH .'includes/footer.php';
        break;

    case 'redefinir-senha':
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/redefinir_senha.php';
        include ROOT_PATH .'includes/footer.php';
        break;

    case 'processa-envio':
        // Rota que processa o envio do e-mail de recuperação de senha
        require __DIR__ . '/actions/processa_envio.php'; 
        break;

    // ==========================================
    // 2. ROTAS PROTEGIDAS: FUNCIONÁRIO DA INSTITUIÇÃO
    // ==========================================
    case 'painel-funcionario':
        validarSessao('funcionario'); 
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/painel_funcionario.php';
        include ROOT_PATH .'includes/footer.php';
        break;

    case 'cadastrar-idoso':
        validarSessao('funcionario'); 
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/cadastrar_idoso.php';
        include ROOT_PATH .'includes/footer.php';
        break;

    case 'listar-idosos':
        validarSessao('funcionario'); 
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/listar_idosos.php'; 
        include ROOT_PATH .'includes/footer.php';
        break;
    
    case 'editar-idoso':
        validarSessao('funcionario');
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/editar_idoso.php'; 
        include ROOT_PATH .'includes/footer.php';
        break;

    case 'agendamento': 
        // Presumi que "agendamento.php" seja a tela onde o funcionário GERENCIA as visitas.
        validarSessao('funcionario');
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/agendamento.php'; 
        include ROOT_PATH .'includes/footer.php';
        break;

    // ==========================================
    // 3. ROTAS PROTEGIDAS: VOLUNTÁRIO
    // ==========================================
    case 'painel-voluntario':
        validarSessao('voluntario'); 
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/painel_voluntario.php';
        include ROOT_PATH .'includes/footer.php';
        break;

    case 'agendar-visita':
        // Presumi que "agendar_visita.php" seja o formulário onde o voluntário MARCA a visita.
        validarSessao('voluntario'); 
        include ROOT_PATH .'includes/header.php';
        require __DIR__ . '/pages/agendar_visita.php';
        include ROOT_PATH .'includes/footer.php';
        break;
// ==========================================
    // 4. AÇÕES PÚBLICAS (Login, Cadastros e Senhas)
    // ==========================================
    case 'autenticar':
        // Não precisa validar sessão porque o usuário está tentando entrar
        require __DIR__ . '/actions/autenticar.php'; 
        break;

     case 'logout':
        if (session_status() === PHP_SESSION_NONE) { 
            session_start(); 
        }
        session_unset();     // Esvazia as variáveis
        session_destroy();   // Queima o cookie de sessão
        header("Location: " . BASE_URL . "/login");
        exit;
        break;

    case 'salvar-voluntario':
        // Rota que processa o formulário de cadastro do voluntário
        require __DIR__ . '/actions/salvar_voluntario.php'; 
        break;

    case 'salvar-funcionario':
        // Rota que processa o formulário de cadastro do funcionário
        require __DIR__ . '/actions/salvar_funcionario.php'; 
        break;

    case 'enviar-recuperacao':
        // Rota do "Esqueci minha senha"
        require __DIR__ . '/actions/enviar_recuperacao.php'; 
        break;

    case 'salvar-nova-senha':
        // Rota que grava a nova senha no banco
        require __DIR__ . '/actions/salvar_nova_senha.php'; 
        break;

    case 'callback-google':
        // Retorno do login com o Google (se você estiver implementando OAuth)
        require __DIR__ . '/actions/callback_google.php'; 
        break;

    // ==========================================
    // 5. AÇÕES PROTEGIDAS: FUNCIONÁRIO
    // ==========================================
    case 'salvar-idoso':
        validarSessao('funcionario');
        require __DIR__ . '/actions/salvar_idoso.php'; 
        break;

    case 'atualizar-idoso':
        validarSessao('funcionario');
        require __DIR__ . '/actions/atualizar_idoso.php'; 
        break;

    case 'excluir-idoso':
        validarSessao('funcionario');
        require __DIR__ . '/actions/excluir_idoso.php'; 
        break;

    case 'processar-visita':
        // Presumo que o funcionário usa essa rota para aprovar/recusar o agendamento
        validarSessao('funcionario');
        require __DIR__ . '/actions/processar_visita.php'; 
        break;

    // ==========================================
    // 6. AÇÕES PROTEGIDAS: VOLUNTÁRIO
    // ==========================================
    case 'salvar-agendamento':
        // Rota onde o voluntário envia o pedido de visita
        validarSessao('voluntario');
        require __DIR__ . '/actions/salvar_agendamento.php'; 
        break;

    // ==========================================
    // 7. APIS E SERVIÇOS INTERNOS (AJAX/JSON)
    // ==========================================
    case 'api-buscar-cep':
        // Rota chamada via JavaScript para preencher endereço
        require __DIR__ . '/actions/buscar_cep.php'; 
        break;

    // ==========================================
    // NÃO ENCONTRADO
    // ==========================================
    default:
        http_response_code(404);
        include ROOT_PATH .'includes/header.php';
        echo "<h2 style='text-align:center; padding: 50px;'>Página não encontrada!</h2>";
        include ROOT_PATH .'includes/footer.php';
        break;
}

ob_end_flush();
?>