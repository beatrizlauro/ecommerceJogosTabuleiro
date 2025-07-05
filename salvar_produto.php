<?php
echo "<pre>";
print_r($_FILES['imagens']);
echo "</pre>";
session_start();
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}

include_once("conecta.php");

// Dados do formulário
$nome = $_POST["txtNome"];
$descricao = $_POST["txtDescricao"];
$preco = floatval($_POST["txtPreco"]);
$estoque = intval($_POST["txtEstoque"]);

// Imagem principal
if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] == 0) {
    $imagemTempPath = $_FILES["imagem"]["tmp_name"];
    $imagemBinario = file_get_contents($imagemTempPath);

    // Cadastra produto e obtém o ID
    $idProduto = cadastrarProduto($nome, $descricao, $preco, $estoque, $imagemBinario);

    // Agora salva as imagens adicionais
    if (!empty($_FILES['imagens']['tmp_name'][0])) {
        foreach ($_FILES['imagens']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['imagens']['error'][$index] === 0) {
                $imagem = file_get_contents($tmpName);

                $stmt = $conn->prepare("INSERT INTO imagens_produto (id_produto, imagem) VALUES (?, ?)");
                $stmt->bind_param("ib", $idProduto, $imagem);
                $stmt->send_long_data(1, $imagem);
                $stmt->execute();
            }
        }
    }

    // Redireciona ao final
    header("Location: lista_produtos.php");
    exit();
} else {
    echo "Nenhuma imagem principal foi enviada.";
}
