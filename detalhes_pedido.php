<?php
session_start();

// Protege acesso: somente administradores
if (!isset($_SESSION["idusuario"]) || $_SESSION["is_admin"] != 1) {
    header("Location: errosessao.php");
    exit();
}

include "conecta.php";
$con = abreConexao();

// Protege contra ausência ou valores inválidos no GET
$pedido_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
if ($pedido_id <= 0) {
    echo "<div class='container'><div class='alert alert-danger'>Pedido inválido.</div></div>";
    exit();
}

// Consulta o pedido e o usuário
$sql = "SELECT p.id, p.data_pedido, p.status, u.nome AS usuario 
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$pedido) {
    echo "<div class='container'><div class='alert alert-warning'>Pedido não encontrado.</div></div>";
    exit();
}

// Consulta os itens do pedido
$sql_itens = "SELECT pr.nome, pr.preco, i.quantidade
              FROM itens_pedido i
              JOIN produtos pr ON i.id_produto = pr.id
              WHERE i.id_pedido = ?";
$stmt = $con->prepare($sql_itens);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$itens = $stmt->get_result();

include "topo.php";
?>

<div class="container">
    <h3 style="color: white;">Detalhes do Pedido #<?= $pedido["id"] ?></h3>
    <p style="color: white;"><strong>Usuário:</strong> <?= $pedido["usuario"] ?></p>
    <p style="color: white;"><strong>Data:</strong> <?= $pedido["data_pedido"] ?></p>
    <p style="color: white;"><strong>Status:</strong>
        <span class="badge 
            <?php
                switch ($pedido["status"]) {
                    case "pendente": echo 'bg-secondary'; break;
                    case "em preparo": echo 'bg-warning text-dark'; break;
                    case "enviado": echo 'bg-info text-dark'; break;
                    case "entregue": echo 'bg-success'; break;
                    case "cancelado": echo 'bg-danger'; break;
                    default: echo 'bg-light text-dark';
                }
            ?>">
            <?= ucfirst($pedido["status"]) ?>
        </span>
    </p>

    <table class="table table-bordered mt-4">
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
            $total = 0;
            while ($item = $itens->fetch_assoc()): 
                $subtotal = $item["preco"] * $item["quantidade"];
                $total += $subtotal;
            ?>
                <tr>
                    <td><?= $item["nome"] ?></td>
                    <td>R$ <?= number_format($item["preco"], 2, ',', '.') ?></td>
                    <td><?= $item["quantidade"] ?></td>
                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-4">
        <a href="admin_pedidos.php" class="btn btn-secondary">Voltar para Pedidos</a>
        <a href="editar_status.php?id=<?= $pedido["id"] ?>" class="btn btn-warning">Alterar Status</a>
    </div>
</div>

<?php include "rodape.php"; ?>
