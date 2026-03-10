<?php
/**
 * Função inteligente para mostrar a foto do usuário (Funcionário ou Voluntário)
 */
function exibirFotoUsuario($caminhoBanco, $nomeUsuario) {
    // Verifica se o caminho não está vazio e se o arquivo realmente existe na pasta
    if (!empty($caminhoBanco) && file_exists(__DIR__ . '/../' . $caminhoBanco)) {
        $urlFoto = $caminhoBanco;
    } else {
        // Se não tem foto, cria um avatar verde com a inicial do nome usando uma API gratuita
        $nomeLimpo = urlencode(trim($nomeUsuario));
        $urlFoto = "https://ui-avatars.com/api/?name={$nomeLimpo}&background=4caf50&color=fff&size=150&rounded=true";
    }
    
    // Retorna a tag de imagem pronta
    return "<img src='{$urlFoto}' alt='Foto de {$nomeUsuario}' class='foto-avatar usuario'>";
}

/**
 * Função inteligente para mostrar a foto do Idoso
 */
function exibirFotoIdoso($caminhoBanco, $nomeIdoso) {
    if (!empty($caminhoBanco) && file_exists(__DIR__ . '/../' . $caminhoBanco)) {
        $urlFoto = $caminhoBanco;
    } else {
        // Se não tem foto, cria um avatar marrom elegante com a inicial do idoso
        $nomeLimpo = urlencode(trim($nomeIdoso));
        $urlFoto = "https://ui-avatars.com/api/?name={$nomeLimpo}&background=5b3a26&color=fff&size=150&rounded=true";
    }
    
    return "<img src='{$urlFoto}' alt='Foto do Residente {$nomeIdoso}' class='foto-avatar idoso'>";
}
?>