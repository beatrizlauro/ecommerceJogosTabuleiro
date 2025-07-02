<?php
session_start();
include_once("conecta.php");
$produtos = listarProdutos(); // Função já existente no conecta.php
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início - D20 Emporium</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <!-- Cabeçalho -->
    <header class="bg-dark text-white py-3 mb-4">
        <div class="container text-center">
            <h1>D20 Emporium</h1>
            <p class="lead">Bem-vindo ao nosso catálogo de jogos de tabuleiro!</p>
            <?php if (isset($_SESSION["idusuario"])): ?>
                <p>Logado como: <?= htmlspecialchars($_SESSION["login"]); ?></p>
            <?php endif; ?>
        </div>
    </header>

    <div class="container text-center mb-4">
        <!-- Botões de navegação -->
        <?php if (!isset($_SESSION["idusuario"])): ?>
            <a href="login.php" class="btn btn-primary mb-2">Login</a>
            <a href="formUsuario.php" class="btn btn-success mb-2">Criar Conta</a>
        <?php else: ?>
            <a href="logout.php" class="btn btn-danger mb-2">Sair</a>
        <?php endif; ?>

        <!-- Botões visíveis apenas para administradores -->
        <?php if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1): ?>
            <div class="mt-3">
                <a href="formusuario.php" class="btn btn-primary mb-2">Cadastrar Usuário</a>
                <a href="lista_usuarios.php" class="btn btn-secondary mb-2">Lista de Usuários</a>
                <a href="formproduto.php" class="btn btn-success mb-2">Cadastrar Produto</a>
                <a href="lista_produtos.php" class="btn btn-warning mb-2">Lista de Produtos</a>
                <a href="admin_pedidos.php" class="btn btn-info mb-2">Pedidos</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Vitrine de produtos -->
    <div class="container">
        <h3 class="text-center mb-4">Produtos em destaque</h3>
        <div class="row">
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($produto["imagem"])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($produto["imagem"]) ?>" class="card-img-top" alt="<?= $produto["nome"] ?>">
                        <?php else: ?>
                            <img src="img/sem-imagem.png" class="card-img-top" alt="Sem imagem">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= $produto["nome"] ?></h5>
                            <p class="card-text"><?= $produto["descricao"] ?></p>
                            <p class="card-text"><strong>Preço:</strong> R$ <?= number_format($produto["preco"], 2, ',', '.') ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="js/bootstrap.min.js"></script>
</body>
</html>
