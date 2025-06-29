<?php
session_start();
include_once("conecta.php");

if (isset($_POST["txtLogin"]) && isset($_POST["txtSenha"])) {
    $email = $_POST["txtLogin"];
    $senha = $_POST["txtSenha"];

    $con = abreConexao();
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($senha, $usuario["senha"])) {
            $_SESSION["login"] = $usuario["nome"];
            $_SESSION["idusuario"] = $usuario["id"];
            header("Location: inicio.php");
            exit();
        } else {
            header("Location: errologin.php");
            exit();
        }
    } else {
        header("Location: errologin.php");
        exit();
    }
} else {
    header("Location: errologin.php");
    exit();
}
?>