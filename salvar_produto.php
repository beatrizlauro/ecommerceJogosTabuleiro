<?php
session_start();
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}

include_once("conecta.php");
$conn = abreConexao();

// Dados do formulário
$idProduto = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$nome = $_POST["txtNome"];
$descricao = $_POST["txtDescricao"];
$preco = floatval($_POST["txtPreco"]);
$estoque = intval($_POST["txtEstoque"]);

$imagemBinario = null;

// Verifica se foi enviada uma imagem principal nova
if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] === 0) {
    $imagemBinario = file_get_contents($_FILES["imagem"]["tmp_name"]);
}

// Se for cadastro (id == 0)
if ($idProduto == 0) {
    if ($imagemBinario === null) {
        echo "Imagem principal obrigatória para novos produtos.";
        exit();
    }

    // Cadastrar novo produto
    $idProduto = cadastrarProduto($nome, $descricao, $preco, $estoque, $imagemBinario);

} else {
    // Editar produto existente
    if ($imagemBinario !== null) {
        editarProduto($idProduto, $nome, $descricao, $preco, $estoque, $imagemBinario);
    } else {
        editarProdutoSemImagem($idProduto, $nome, $descricao, $preco, $estoque);
    }
}

// Agora salva imagens adicionais, se houver
if (!empty($_FILES['imagens']['tmp_name'][0])) {
    foreach ($_FILES['imagens']['tmp_name'] as $index => $tmpName) {
        if ($_FILES['imagens']['error'][$index] === 0) {
            $imagem = file_get_contents($tmpName);
            $stmt = $conn->prepare("INSERT INTO imagens_produto (id_produto, imagem) VALUES (?, ?)");
            $stmt->bind_param("ib", $idProduto, $imagem);
            $stmt->send_long_data(1, $imagem);
            $stmt->execute();
            $stmt->close();
        }
    }
}

$conn->close();

// Redireciona ao final
header("Location: lista_produtos.php");
exit();
