<?php
// exibir_img_principal.php
include_once 'conecta.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    exit('ID inválido');
}

$id = intval($_GET['id']);
global $conn;
$stmt = $conn->prepare("SELECT imagem FROM produtos WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($imagem);
$stmt->fetch();
$stmt->close();

if ($imagem) {
    header('Content-Type: image/jpeg'); // ou image/png se for png
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
