<?php
session_start();
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}
?>

<?php
    include_once("conecta.php");
    $produtos = listarProdutos();
    include_once("topo.php");
?>

<div class="container">
    <div class="text-center">
        <h3>Lista de Produtos</h3>
        <a href="formproduto.php" class="btn btn-success mb-3">Novo Produto</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Imagem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= $produto['nome']; ?></td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></td>
                    <td><?= $produto['estoque']; ?></td>
                    <td>
                        <?php if (!empty($produto['imagem'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagem']); ?>" alt="<?= $produto['nome']; ?>" style="height: 50px;">
                        <?php else: ?>
                            <span>Sem imagem</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="formproduto.php?id=<?= $produto['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="excluir_produto.php?id=<?= $produto['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir este produto?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once("rodape.php"); ?>
