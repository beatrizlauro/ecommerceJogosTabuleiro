<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Verifica se o usuário está logado
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}

// 2. Verifica se há dados no carrinho
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<p>Carrinho vazio. <a href='index.php'>Voltar</a></p>";
    exit;
}

// 3. Verifica se o formulário foi enviado corretamente
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["confirmar"])) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Acesso inválido.</div></div>";
    exit();
}

include "conecta.php";
$con = abreConexao();

// 4. Recebe os dados do formulário
$cep = $_POST['cep'] ?? '';
$rua = $_POST['rua'] ?? '';
$numero = $_POST['numero'] ?? '';
$cidade = $_POST['cidade'] ?? '';
$estado = $_POST['estado'] ?? '';
$pagamento = $_POST['pagamento'] ?? '';
$data_pedido = date('Y-m-d H:i:s');
$usuario_id = $_SESSION["idusuario"];
$status = "pendente"; // status padrão

// 5. Validação básica
if (!$cep || !$rua || !$numero || !$cidade || !$estado || !$pagamento) {
    echo "<p>Todos os campos são obrigatórios. <a href='carrinho.php'>Voltar</a></p>";
    exit;
}

// 6. Calcula total
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

// 7. Inserir pedido
$sql_pedido = "INSERT INTO pedidos (usuario_id, data_pedido, total, forma_pagamento, cep, rua, numero, cidade, estado, status) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql_pedido);
$stmt->bind_param("isdsssssss", $usuario_id, $data_pedido, $total, $pagamento, $cep, $rua, $numero, $cidade, $estado, $status);
$stmt->execute();
$pedido_id = $stmt->insert_id;
$stmt->close();

// 8. Inserir os itens do pedido
$sql_item = "INSERT INTO itens_pedido (id_pedido, id_produto, nome_produto, preco, quantidade) 
             VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql_item);

foreach ($_SESSION['carrinho'] as $id_produto => $item) {
    $nome = $item['nome'];
    $preco = $item['preco'];
    $quantidade = $item['quantidade'];
    $stmt->bind_param("iisdi", $pedido_id, $id_produto, $nome, $preco, $quantidade);
    $stmt->execute();
}
$stmt->close();

// 9. Limpa o carrinho
unset($_SESSION['carrinho']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Compra Finalizada</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5 text-center">
        <div class="alert alert-success">
            <h4 class="alert-heading">✅ Compra realizada com sucesso!</h4>
            <p>Seu pedido foi registrado com ID <strong><?= $pedido_id ?></strong> e está com status <strong><?= $status ?></strong>.</p>
            <p>Total: <strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></p>
            <hr>
            <a href="index.php" class="btn btn-primary mt-3">Voltar para a loja</a>
        </div>
    </div>
</body>
</html>
