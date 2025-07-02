<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}

// Verifica se o formulário veio da confirmação de pagamento
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["confirmar"])) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Acesso inválido.</div></div>";
    exit();
}

include "conecta.php";
$con = abreConexao();

// Simulação de carrinho (igual ao pagamento.php)
$carrinho = [
    ['produto_id' => 2, 'quantidade' => 1],
    ['produto_id' => 4, 'quantidade' => 2]
];

$usuario_id = $_SESSION["idusuario"];

// 1. Inserir o pedido
$sql_pedido = "INSERT INTO pedidos (usuario_id) VALUES (?)";
$stmt = $con->prepare($sql_pedido);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$pedido_id = $stmt->insert_id;
$stmt->close();

// 2. Inserir os itens do pedido
$sql_item = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade) VALUES (?, ?, ?)";
$stmt = $con->prepare($sql_item);

foreach ($carrinho as $item) {
    $stmt->bind_param("iii", $pedido_id, $item["produto_id"], $item["quantidade"]);
    $stmt->execute();
}
$stmt->close();

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
            <p>Seu pedido foi registrado e está com status <strong>pendente</strong>.</p>
            <hr>
            <a href="index.php" class="btn btn-primary mt-3">Voltar para a loja</a>
        </div>
    </div>
</body>
</html>
