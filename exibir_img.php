<?php
// NUNCA deixe espaços em branco antes do PHP; senão o header falha.
include_once 'conecta.php';

// Verifica se veio um id válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    exit('ID inválido');
}

$id = intval($_GET['id']);

global $conn;
$stmt = $conn->prepare("SELECT imagem FROM imagens_produto WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($imagem);
    $stmt->fetch();

    header('Content-Type: image/jpeg'); // ou image/png se for o caso
    header('Content-Length: ' . strlen($imagem));
    echo $imagem;
    exit;
}

$placeholder = __DIR__ . '/img/placeholder.png';
if (is_file($placeholder)) {
    header('Content-Type: image/png');
    readfile($placeholder);
    exit;
}

http_response_code(404);
echo 'Imagem não encontrada.';
?>
