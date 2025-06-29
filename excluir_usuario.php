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
    excluirUsuario($id);
    header("Location: lista_usuarios.php");
    exit();
?>