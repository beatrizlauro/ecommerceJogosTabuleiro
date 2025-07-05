<?php
// NUNCA deixe espaços em branco antes do PHP; senão o header falha.
include_once 'conecta.php';
// 1) Verifica se veio um id válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    exit('ID inválido');
}

// 2) Busca o BLOB no banco
$id = intval($_GET['id']);
$produto = obterProdutoPorId($id);   // Precisa devolver ['imagem'=> <binário>]

if ($produto && !empty($produto['imagem'])) {
    // Descubra o tipo se você salva o mime junto; senão,
    // defina fixo (ex: image/jpeg) ou use getimagesizefromstring
    header('Content-Type: image/jpeg');   // OU image/png, se seus uploads são png
    header('Content-Length: ' . strlen($produto['imagem'])); // opcional
    echo $produto['imagem'];              // binário puro!
    exit;
}

/* ------------ PLACEHOLDER -------------- */
$placeholder = __DIR__ . '/img/placeholder.png';
if (is_file($placeholder)) {
    header('Content-Type: image/png');
    readfile($placeholder);
    exit;
}

http_response_code(404);
echo 'Imagem não encontrada.';
