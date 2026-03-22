<?php
// includes/helpers.php

/**
 * Função inteligente para mostrar a foto do usuário (Funcionário ou Voluntário)
 */
// function exibirFotoUsuario($caminhoBanco, $nomeUsuario) {
//     // 1. O PHP verifica se o arquivo existe no servidor
//     // 'file_exists(__DIR__ . '/../' . $caminhoBanco)' -> Correto para o PHP achar no servidor
//     if (!empty($caminhoBanco) && file_exists(__DIR__ . '/../' . $caminhoBanco)) {
//         $urlFoto = $caminhoBanco;
//     } else {
//         // Se não tem foto, cria um avatar padrão usando a API do Google (Sempre Gratuita)
//         // Isso cria um círculo com as iniciais do nome com o fundo verde.
//         $nomeLimpo = urlencode(trim($nomeUsuario));
//         // Nota: Esta URL é para o navegador, então não precisa de file_exists.
//         $urlFoto = "https://ui-avatars.com/api/?name={$nomeLimpo}&background=4caf50&color=fff&size=150&rounded=true";
//     }
    
//     // 2. O PHP monta o endereço completo para o navegador usando a BASE_URL!
//     // Se a foto é 'assets/img/fotoPerfil.png', o navegador receberá:
//     // 'http://localhost/abraceumidoso/assets/img/fotoPerfil.png', que é o endereço CORRETO.
//     // Se for o avatar do Google, usamos a URL direta deles.
//     $srcFinal = (strpos($urlFoto, 'http') === 0) ? $urlFoto : BASE_URL . $urlFoto;

//     return "<img src='{$srcFinal}' alt='Foto de {$nomeUsuario}' class='foto-avatar usuario'>";
// }

// /**
//  * Função inteligente para mostrar a foto do Idoso
//  */
// function exibirFotoIdoso($caminhoBanco, $nomeIdoso) {
//     if (!empty($caminhoBanco) && file_exists(__DIR__ . '/../' . $caminhoBanco)) {
//         $urlFoto = $caminhoBanco;
//     } else {
//         // Se não tem foto, cria um avatar padrão com o fundo marrom elegante do projeto.
//         $nomeLimpo = urlencode(trim($nomeIdoso));
//         $urlFoto = "https://ui-avatars.com/api/?name={$nomeLimpo}&background=5b3a26&color=fff&size=150&rounded=true";
//     }
    
//     // Monta o endereço completo para o navegador
//     $srcFinal = (strpos($urlFoto, 'http') === 0) ? $urlFoto : BASE_URL . $urlFoto;

//     return "<img src='{$srcFinal}' alt='Foto do Residente {$nomeIdoso}' class='foto-avatar idoso'>";
// }
function exibirFoto($caminhoBanco, $nome, $tipo = 'usuario') {
    // 1. É um link externo? (Começa com http)
    if (!empty($caminhoBanco) && strpos($caminhoBanco, 'http') === 0) {
        $srcFinal = $caminhoBanco;
    } 
    // 2. É um arquivo local e ele existe no servidor?
    elseif (!empty($caminhoBanco) && file_exists(ROOT_PATH . $caminhoBanco)) {
        $srcFinal = BASE_URL . $caminhoBanco;
    } 
    // 3. Não tem foto ou arquivo sumiu? Gera Avatar
    else {
        $corFundo = ($tipo == 'idoso') ? '5b3a26' : '4caf50'; // Marrom para idoso, Verde para usuário
        $nomeLimpo = urlencode(trim($nome));
        $srcFinal = "https://ui-avatars.com/api/?name={$nomeLimpo}&background={$corFundo}&color=fff&size=150&rounded=true";
    }

    $classe = ($tipo == 'idoso') ? 'foto-avatar idoso' : 'foto-avatar usuario';
    return "<img src='{$srcFinal}' alt='Foto de {$nome}' class='{$classe}'>";
}
?>