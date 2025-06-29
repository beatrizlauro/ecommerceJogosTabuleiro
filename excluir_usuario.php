<?php
    include_once("conecta.php");
    $id = $_GET["id"];
    excluirUsuario($id);
    header("Location: lista_usuarios.php");
    exit();
?>