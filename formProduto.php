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
            <input type="hidden" name="id" value="<?php echo($idproduto); ?>">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="pNome">Nome</label>
                <div class="col-sm-8">
                    <input type="text" name="txtNome" class="form-control" id="pNome" required value="<?php echo($nome); ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="pDescricao">Descrição</label>
                <div class="col-sm-8">
                    <textarea name="txtDescricao" class="form-control" id="pDescricao" rows="4" required><?php echo($descricao); ?></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="pPreco">Preço</label>
                <div class="col-sm-4">
                    <input type="number" name="txtPreco" class="form-control" id="pPreco" step="0.01" required value="<?php echo($preco); ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="pEstoque">Estoque</label>
                <div class="col-sm-4">
                    <input type="number" name="txtEstoque" class="form-control" id="pEstoque" required value="<?php echo($estoque); ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="pImagem">Imagem</label>
                <div class="col-sm-8">
                    <input type="file" name="imagem" class="form-control" id="pImagem" accept="image/*">
                </div>
            </div>

            <div class="text-center">
                <input type="submit" class="btn btn-primary" value="Salvar" onclick="alert('Produto salvo com sucesso!');">
                <a href="lista_produtos.php" class="btn btn-warning">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include_once("rodape.php"); ?>
