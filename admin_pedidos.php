<?php
session_start();
if (!isset($_SESSION["idusuario"]) || $_SESSION["is_admin"] != 1) {
    header("Location: errosessao.php");
    exit();
}

include "conecta.php";
$con = abreConexao();

// Consulta: pedidos + nomes de usuários, ordenados por ID (do maior para o menor)
$sql = "SELECT p.id AS pedido_id, u.nome AS usuario, p.data_pedido, p.status 
        FROM pedidos p 
        JOIN usuarios u ON p.usuario_id = u.id 
        ORDER BY p.id";

$resultado = $con->query($sql);

include "topo.php";
?>

<div class="container">
    <h3 style="color: white;">Pedidos Realizados</h3>
    <a href="index.php" class="btn btn-secondary mb-3">Voltar</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID do Pedido</th>
                <th>Usuário</th>
                <th>Data</th>
                <th>Ações</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["pedido_id"] ?></td>
                    <td><?= $row["usuario"] ?></td>
                    <td><?= $row["data_pedido"] ?></td>
                    <td>
                        <a href="detalhes_pedido.php?id=<?= $row["pedido_id"] ?>" class="btn btn-info btn-sm">Ver Detalhes</a>
                        <a href="editar_status.php?id=<?= $row["pedido_id"] ?>" class="btn btn-warning btn-sm mt-1">Alterar Status</a>
                    </td>
                    <td><?= $row["status"] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "rodape.php"; ?>
