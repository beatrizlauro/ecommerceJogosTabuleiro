<?php
session_start();
include_once("conecta.php"); // já define $conn globalmente

$nome = $_POST["txtNome"];
$login = $_POST["txtLogin"];
$email = $_POST["txtEmail"];
$senha = $_POST["txtSenha"];
$is_admin = isset($_POST["is_admin"]) ? 1 : 0;

// Criptografa a senha (boa prática)
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Usa diretamente a variável $conn que já foi criada em conecta.php
$stmt = $conn->prepare("INSERT INTO usuarios (nome, login, email, senha, is_admin) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $nome, $login, $email, $senha_hash, $is_admin);

if ($stmt->execute()) {
    // Cadastro com sucesso
    header("Location: login.php");
    exit();
} else {
    echo "Erro ao cadastrar usuário: " . $stmt->error;
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
