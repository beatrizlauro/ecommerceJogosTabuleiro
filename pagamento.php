<?php
session_start();

// Apenas usuários logados podem comprar
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}

include "conecta.php";

// Simulação do carrinho (você pode substituir isso depois por um carrinho real em sessão)
$carrinho = [
    ['produto_id' => 2, 'quantidade' => 1],
    ['produto_id' => 4, 'quantidade' => 2]
];

$con = abreConexao();
$total = 0;
$produtos = [];

foreach ($carrinho as $item) {
    $sql = "SELECT nome, preco FROM produtos WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $item["produto_id"]);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    $result["quantidade"] = $item["quantidade"];
    $result["subtotal"] = $result["preco"] * $item["quantidade"];
    $total += $result["subtotal"];
    $produtos[] = $result;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Simulação de Pagamento</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="text-center mb-4">Confirmar Pagamento</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= $p["nome"] ?></td>
                <td>R$ <?= number_format($p["preco"], 2, ',', '.') ?></td>
                <td><?= $p["quantidade"] ?></td>
                <td>R$ <?= number_format($p["subtotal"], 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total:</strong></td>
                <td><strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Enviar para finalizar_compra.php -->
    <form action="finalizar_compra.php" method="post" class="text-center mt-4">
        <input type="hidden" name="confirmar" value="1">
        <button type="submit" class="btn btn-success btn-lg">Confirmar Pagamento</button>
    </form>

    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
</body>
</html>
