<?php
session_start();
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - D20 Emporium</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <header class="bg-dark text-white py-3 mb-4">
        <div class="container text-center">
            <h1>D20 Emporium</h1>
            <p class="lead">Sistema de Gerenciamento de E-commerce</p>
            <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION["login"]); ?>!</p>
        </div>
    </header>

    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-3 mb-3">
                <a href="formusuario.php" class="btn btn-primary btn-block">Cadastrar Usuário</a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="lista_usuarios.php" class="btn btn-secondary btn-block">Lista de Usuários</a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="formproduto.php" class="btn btn-success btn-block">Cadastrar Produto</a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="lista_produtos.php" class="btn btn-warning btn-block">Lista de Produtos</a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="logout.php" class="btn btn-danger btn-block">Sair</a>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.min.js"></script>
</body>
</html>