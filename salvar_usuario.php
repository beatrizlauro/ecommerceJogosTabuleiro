<?php
include_once("conecta.php");

$idusuario = $_POST["id"];
$nome = $_POST["txtNome"];
$email = $_POST["txtEmail"];
$senha = $_POST["txtSenha"];

$con = abreConexao();

if ($idusuario == 0) { // Novo cadastro
    if (!empty($senha)) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senhaHash);
        $stmt->execute();
        $stmt->close();
    }
} else { // Atualiza cadastro existente
    if (!empty($senha)) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssi", $nome, $email, $senhaHash, $idusuario);
    } else {
        $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $nome, $email, $idusuario);
    }
    $stmt->execute();
    $stmt->close();
}

$con->close();
header("Location: lista_usuarios.php");
?>
