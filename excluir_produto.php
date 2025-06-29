<?php
session_start();
if (!isset($_SESSION["idusuario"])) {
    header("Location: login.php");
    exit();
}
?>
<?php
    include_once("conecta.php");
    $id = $_GET["id"];
    excluirProduto($id);
    header("Location: lista_produtos.php");
    exit();
?>