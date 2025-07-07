<?php
session_start();
include_once("conecta.php"); // expõe $conn

if (!isset($_POST["txtLogin"], $_POST["txtSenha"])) {
    header("Location: errologin.php");
    exit();
}

$login = trim($_POST["txtLogin"]);
$senha = $_POST["txtSenha"];

// busca usuário
$sql  = "SELECT id, nome, login, senha, is_admin FROM usuarios WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$resultado = $stmt->get_result();

// não encontrou login?
if ($resultado->num_rows !== 1) {
    header("Location: errologin.php");
    exit();
}

$usuario = $resultado->fetch_assoc();

// DEBUG — remova depois
// var_dump('digitada='.$senha, 'armazenada='.$usuario['senha']); exit;

$hash = $usuario['senha'];
$logou = false;

// se houver hash válido, usa password_verify
if (password_get_info($hash)['algo']) {
    $logou = password_verify($senha, $hash);
} else {
    // fallback (texto puro)
//    $logou = ($senha === $hash);
    $logou = false; // ou true se você quiser habilitar o texto puro
}

if ($logou) {
    $_SESSION["login"]     = $usuario["nome"];
    $_SESSION["idusuario"] = $usuario["id"];
    $_SESSION["is_admin"]  = $usuario["is_admin"];
    header("Location: index.php");
} else {
    header("Location: errologin.php");
}
exit();
