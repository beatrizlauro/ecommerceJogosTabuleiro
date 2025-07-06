<?php
session_start();
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}
?>

<?php
    include_once("conecta.php");
    $usuarios = listarUsuarios();
    include_once("topo.php");
?>

<div class="container">
    <div class="text-center">
        <h3 style="color: white;">Lista de Usuários</h3>
        <a href="formusuario.php" class="btn btn-success mb-3">Novo Usuário</a>
        <a href="index.php" class="btn btn-secondary mb-3">Voltar</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['nome']; ?></td>
                    <td><?= $usuario['email']; ?></td>
                    <td>
                        <a href="formusuario.php?id=<?= $usuario['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="excluir_usuario.php?id=<?= $usuario['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir este usuário?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once("rodape.php"); ?>
