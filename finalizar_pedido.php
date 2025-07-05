<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com banco (ajuste se necessário)
include_once("conecta.php");

// Verifica se há dados no carrinho
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<p>Carrinho vazio. <a href='index.php'>Voltar</a></p>";
    exit;
}

// Recebe dados do formulário
$cep = $_POST['cep'] ?? '';
$rua = $_POST['rua'] ?? '';
$numero = $_POST['numero'] ?? '';
$cidade = $_POST['cidade'] ?? '';
$estado = $_POST['estado'] ?? '';
$pagamento = $_POST['pagamento'] ?? '';
$data_pedido = date('Y-m-d H:i:s');

// Validação básica
if (!$cep || !$rua || !$numero || !$cidade || !$estado || !$pagamento) {
    echo "<p>Todos os campos são obrigatórios. <a href='carrinho.php'>Voltar</a></p>";
    exit;
}

// Calcula total
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

// Salvar no banco (exemplo genérico, ajuste os nomes de tabela conforme seu BD)

// Inserir pedido
$sql_pedido = "INSERT INTO pedidos (data_pedido, total, forma_pagamento, cep, rua, numero, cidade, estado) 
               VALUES ('$data_pedido', '$total', '$pagamento', '$cep', '$rua', '$numero', '$cidade', '$estado')";

if (mysqli_query($conn, $sql_pedido)) {
    $id_pedido = mysqli_insert_id($conn); // Pega o ID do pedido inserido

    // Inserir os produtos do pedido
    foreach ($_SESSION['carrinho'] as $id_produto => $item) {
        $nome = $item['nome'];
        $preco = $item['preco'];
        $quantidade = $item['quantidade'];

        $sql_item = "INSERT INTO itens_pedido (id_pedido, id_produto, nome_produto, preco, quantidade) 
                     VALUES ('$id_pedido', '$id_produto', '$nome', '$preco', '$quantidade')";

        mysqli_query($conn, $sql_item);
    }

    // Limpa o carrinho
    unset($_SESSION['carrinho']);

    echo "<h2>Pedido finalizado com sucesso!</h2>";
    echo "<p>ID do Pedido: <strong>$id_pedido</strong></p>";
    echo "<p>Total: R$ " . number_format($total, 2, ',', '.') . "</p>";
    echo "<a href='index.php'>Voltar à loja</a>";

} else {
    echo "<p>Erro ao salvar pedido. <a href='carrinho.php'>Tentar novamente</a></p>";
    echo "<p>Erro: " . mysqli_error($conn) . "</p>";
}
?>
