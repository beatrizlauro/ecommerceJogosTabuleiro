<?php
session_start();
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}

include_once("conecta.php");

$id = $_GET["id"];

try {
    excluirProduto($id);
    header("Location: lista_produtos.php?msg=sucesso");
    exit();
} catch (mysqli_sql_exception $e) {
    // Verifica se o erro é de restrição de chave estrangeira
    if (str_contains($e->getMessage(), 'a foreign key constraint fails')) {
        $erro = "Erro: O produto não pode ser excluído porque já foi usado em um pedido.";
    } else {
        $erro = "Erro ao excluir o produto: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Erro ao Excluir Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h4 class="alert-heading">Erro ao excluir produto</h4>
            <p><?= $erro ?></p>
            <hr>
            <a href="lista_produtos.php" class="btn btn-secondary">Voltar à lista de produtos</a>
        </div>
    </div>
</body>
</html>
