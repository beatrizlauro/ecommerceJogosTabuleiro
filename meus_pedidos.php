<?php
session_start();
include "conecta.php";
$con = abreConexao();

// Protege: só usuários logados
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION["idusuario"];

// 1. Busca todos os pedidos do usuário
$sql_pedidos = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY data_pedido DESC";
$stmt = $con->prepare($sql_pedidos);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result_pedidos = $stmt->get_result();

include "topo.php";
?>

<div class="container mt-5">
    <h2>📦 Meus Pedidos</h2>

    <?php if ($result_pedidos->num_rows == 0): ?>
        <p>Você ainda não fez nenhum pedido.</p>
    <?php else: ?>
        <?php while ($pedido = $result_pedidos->fetch_assoc()): ?>
            <div class="card my-4">
                <div class="card-header">
                    <strong>Pedido #<?= $pedido['id'] ?></strong> - 
                    Data: <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?> - 
                    Status: <span class="badge bg-secondary"><?= ucfirst($pedido['status']) ?></span>
                </div>
                <div class="card-body">
                    <p><strong>Forma de Pagamento:</strong> <?= $pedido['forma_pagamento'] ?></p>
                    <p><strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>

                    <h6>Itens do Pedido:</h6>
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
                            <?php
                            $sql_itens = "SELECT i.id_produto, i.nome_produto, i.preco, i.quantidade 
                                          FROM itens_pedido i
                                          WHERE i.id_pedido = ?";
                            $stmt_itens = $con->prepare($sql_itens);
                            $stmt_itens->bind_param("i", $pedido['id']);
                            $stmt_itens->execute();
                            $result_itens = $stmt_itens->get_result();

                            while ($item = $result_itens->fetch_assoc()):
                                $subtotal = $item['preco'] * $item['quantidade'];
                            ?>
                                <tr>
                                    <td><?= $item['nome_produto'] ?></td>
                                    <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                    <td><?= $item['quantidade'] ?></td>
                                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-outline-primary">← Voltar para a Loja</a>
    </div>
</div>
?>
