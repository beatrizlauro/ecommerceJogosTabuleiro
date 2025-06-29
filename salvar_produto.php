<?php
session_start();
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}
?>

<?php
    include_once("conecta.php");

    // Dados do formulário
    $id = $_POST["id"];
    $nome = $_POST["txtNome"];
    $descricao = $_POST["txtDescricao"];
    $preco = $_POST["txtPreco"];
    $estoque = $_POST["txtEstoque"];

    $imagem = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
    }

    // Salvar no banco
    if ($id == 0) {
        cadastrarProduto($nome, $descricao, $preco, $estoque, $imagem);
    } else {
        editarProduto($id, $nome, $descricao, $preco, $estoque, $imagem);
    }

    // Redirecionar
    header("Location: lista_produtos.php");
    exit();
?>