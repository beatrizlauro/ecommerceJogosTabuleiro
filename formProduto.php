<?php
session_start();
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}
?>

<?php
    if(!isset($_GET["id"])){ // Novo produto
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

<div class="container">
    <div class="text-center">
        <h3>Cadastro de Produto</h3>
    </div>
    <div class="painel">
        <form action="salvar_produto.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="0">

            <label>Nome:</label>
            <input type="text" name="txtNome" required><br>

            <label>Descrição:</label>
            <textarea name="txtDescricao" required></textarea><br>

            <label>Preço:</label>
            <input type="number" name="txtPreco" step="0.01" required><br>

            <label>Estoque:</label>
            <input type="number" name="txtEstoque" required><br>

            <label>Imagem principal:</label>
            <input type="file" name="imagem" required><br><br>

            <label>Imagens adicionais:</label>
            <input type="file" name="imagens[]" multiple><br><br>

            <div class="text-center">
                <input type="submit" class="btn btn-primary" value="Salvar" onclick="alert('Produto salvo com sucesso!');">
                <a href="lista_produtos.php" class="btn btn-warning">Cancelar</a>
                <a href="index.php" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>
</div>

<?php include_once("rodape.php"); ?>
