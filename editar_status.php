<?php
session_start();

// Protege acesso: apenas admins
if (!isset($_SESSION["idusuario"]) || $_SESSION["is_admin"] != 1) {
    header("Location: errosessao.php");
    exit();
}

include "conecta.php";
$con = abreConexao();

$pedido_id = $_GET["id"] ?? null;
$mensagem = "";

// Atualização de status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["novo_status"])) {
    $novo_status = $_POST["novo_status"];

    $sql_update = "UPDATE pedidos SET status = ? WHERE id = ?";
    $stmt = $con->prepare($sql_update);
    $stmt->bind_param("si", $novo_status, $pedido_id);
    $stmt->execute();

    $mensagem = "✅ Status atualizado com sucesso!";
}

// Buscar dados do pedido
$sql = "SELECT p.id, u.nome AS usuario, p.status, p.data
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();
$stmt->close();

include "topo.php";
?>

<div class="container">
    <h3>Editar Status do Pedido #<?= $pedido["id"] ?></h3>
    <p><strong>Usuário:</strong> <?= $pedido["usuario"] ?></p>
    <p><strong>Data:</strong> <?= $pedido["data"] ?></p>

    <?php if ($mensagem): ?>
        <div class="alert alert-success"><?= $mensagem ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="novo_status"><strong>Status atual:</strong> <?= $pedido["status"] ?></label>
            <select name="novo_status" id="novo_status" class="form-control mt-2" required>
                <option value="pendente" <?= $pedido["status"] == "pendente" ? "selected" : "" ?>>Pendente</option>
                <option value="em preparo" <?= $pedido["status"] == "em preparo" ? "selected" : "" ?>>Em preparo</option>
                <option value="enviado" <?= $pedido["status"] == "enviado" ? "selected" : "" ?>>Enviado</option>
                <option value="entregue" <?= $pedido["status"] == "entregue" ? "selected" : "" ?>>Entregue</option>
                <option value="cancelado" <?= $pedido["status"] == "cancelado" ? "selected" : "" ?>>Cancelado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Atualizar</button>
        <a href="admin_pedidos.php" class="btn btn-secondary mt-3">Voltar</a>
    </form>
</div>

<?php include "rodape.php"; ?>
