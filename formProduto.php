<?php
session_start();
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}
?>

<?php
if (!isset($_GET["id"])) { // Novo produto
    $idproduto = 0;
    $nome = "";
    $descricao = "";
    $preco = "";
    $estoque = "";
    $imagem = "";
} else { // Editar produto
    $idproduto = $_GET["id"];
    include_once("conecta.php");
    $produto = obterProdutoPorId($idproduto);
    if ($produto != null) {
        $nome = $produto["nome"];
        $descricao = $produto["descricao"];
        $preco = $produto["preco"];
        $estoque = $produto["estoque"];
        $imagem = $produto["imagem"];
    }
}

include_once("topo.php");
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="w-100" style="max-width: 600px;">
        <div class="text-center mb-4">
            <h3 style="color: white;">Cadastro de Produto</h3>
        </div>
        <div class="painel bg-dark p-4 rounded shadow">
            <form action="salvar_produto.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($idproduto) ?>">

                <label style="color: white;">Nome:</label>
                <input type="text" name="txtNome" class="form-control mb-3" value="<?= htmlspecialchars($nome) ?>" required>

                <label style="color: white;">Descrição:</label>
                <textarea name="txtDescricao" class="form-control mb-3" required><?= htmlspecialchars($descricao) ?></textarea>

                <label style="color: white;">Preço:</label>
                <input type="number" name="txtPreco" step="0.01" class="form-control mb-3" value="<?= htmlspecialchars($preco) ?>" required>

                <label style="color: white;">Estoque:</label>
                <input type="number" name="txtEstoque" class="form-control mb-3" value="<?= htmlspecialchars($estoque) ?>" required>

                <label style="color: white;">Imagem principal:</label>
                <input type="file" name="imagem" class="form-control mb-3" <?= $idproduto == 0 ? 'required' : '' ?>>

                <label style="color: white;">Imagens adicionais:</label>
                <input type="file" name="imagens[]" class="form-control mb-3" multiple>

                <div class="text-center mt-4">
                    <input type="submit" class="btn btn-primary" value="Salvar" onclick="alert('Produto salvo com sucesso!');">
                    <a href="lista_produtos.php" class="btn btn-warning">Cancelar</a>
                    <a href="index.php" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once("rodape.php"); ?>
