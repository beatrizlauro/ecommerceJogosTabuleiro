<?php
session_start();
include_once("conecta.php");

// Captura os dados do POST
$idusuario = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
$nome = $_POST["txtNome"] ?? '';
$email = $_POST["txtEmail"] ?? '';
$senha = $_POST["txtSenha"] ?? '';

// Verifica se o usuário logado é admin
$usuario_logado_e_admin = isset($_SESSION["idusuario"]) && isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1;

// Se for edição (id > 0) e não for admin, bloqueia acesso
if ($idusuario > 0 && !$usuario_logado_e_admin) {
    header("Location: errosessao.php");
    exit();
}

// Valida campos obrigatórios
if (empty($nome) || empty($email)) {
    die("Nome e email são obrigatórios.");
}

// Para novo cadastro, senha é obrigatória
if ($idusuario == 0 && empty($senha)) {
    die("Senha é obrigatória para novo cadastro.");
}

$con = abreConexao();

if ($idusuario == 0) {
    // Novo cadastro - permite qualquer visitante
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senhaHash);
    $stmt->execute();
    $stmt->close();
} else {
    // Atualização - só admins passam no if acima, aqui só atualiza
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

// Redireciona para lista de usuários se admin, ou para página inicial se visitante
if ($usuario_logado_e_admin) {
    header("Location: lista_usuarios.php");
} else {
    header("Location: index.php");
}
exit();
?>
