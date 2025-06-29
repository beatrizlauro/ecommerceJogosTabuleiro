<?php
    include_once("conecta.php");
    $id = $_GET["id"];
    excluirProduto($id);
    header("Location: lista_produtos.php");
    exit();
?>