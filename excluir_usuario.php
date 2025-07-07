<?php
session_start();
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}

include_once("conecta.php");

$id = $_GET["id"];

try {
    excluirUsuario($id);
    header("Location: lista_usuarios.php?msg=sucesso");
    exit();
} catch (mysqli_sql_exception $e) {
    if (str_contains($e->getMessage(), 'a foreign key constraint fails')) {
        $erro = "Erro: O usuário não pode ser excluído porque possui pedidos associados.";
    } else {
        $erro = "Erro ao excluir o usuário: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Erro ao Excluir Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h4 class="alert-heading">Erro ao excluir usuário</h4>
            <p><?= $erro ?></p>
            <hr>
            <a href="lista_usuarios.php" class="btn btn-secondary">Voltar à lista de usuários</a>
        </div>
    </div>
</body>
</html>
