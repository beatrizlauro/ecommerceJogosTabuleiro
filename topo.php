<?php
if (!isset($_SESSION)) session_start();

$total_itens = 0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $total_itens += $item['quantidade'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>D20 Emporium</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header class="main-header">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <a href="index.php" class="logo d-flex align-items-center text-white text-decoration-none">
            <img src="img/logo_d20.png" alt="D20 Logo" style="width: 40px; height: auto; margin-right: 10px;">
            <span>D20 Emporium</span>
        </a>

        <!-- Navegação principal -->
        <nav class="main-nav">
            <ul class="nav">
                <li class="nav-item"><a class="nav-link text-white" href="index.php">Início</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="loja.php">Loja</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="carrinho.php">🛒 Carrinho (<?= $total_itens ?>)</a></li>

                <?php if (!isset($_SESSION["idusuario"])): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="formUsuario.php">Criar Conta</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link text-white" href="logout.php">Sair</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
