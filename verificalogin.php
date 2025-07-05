<?php
session_start();
include_once("conecta.php"); // Garante acesso à $conn

if (isset($_POST["txtLogin"]) && isset($_POST["txtSenha"])) {
    $login = $_POST["txtLogin"];
    $senha = $_POST["txtSenha"];

    // Prepara SQL para buscar pelo campo login
    $sql = "SELECT * FROM usuarios WHERE login = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verifica a senha criptografada
        if (password_verify($senha, $usuario["senha"])) {
            $_SESSION["login"] = $usuario["nome"];
            $_SESSION["idusuario"] = $usuario["id"];
            $_SESSION["is_admin"] = $usuario["is_admin"];

            header("Location: index.php");
            exit();
        }
    }

    // Se falhou em qualquer parte, vai para erro
    header("Location: errologin.php");
    exit();

} else {
    header("Location: errologin.php");
    exit();
}
?>
