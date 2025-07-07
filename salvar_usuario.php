<?php
session_start();
include_once("conecta.php");

// pega a conexão (supondo que no conecta.php exista a função abreConexao() retornando PDO)
$con = abreConexao();

// Verifica se é um visitante ou um admin
$acesso_admin = isset($_SESSION["idusuario"]) 
             && isset($_SESSION["is_admin"]) 
             && $_SESSION["is_admin"] == 1;

// Se o usuário estiver logado mas não for admin, bloqueia
if (isset($_SESSION["idusuario"]) && !$acesso_admin) {
    header("Location: errosessao.php");
    exit();
}

// Captura dados do POST
$id     = isset($_POST['id'])       ? intval($_POST['id'])     : 0;
$nome   = trim($_POST['txtNome']);
$login  = trim($_POST['txtLogin']);
$email  = trim($_POST['txtEmail']);
$senha  = $_POST['txtSenha'];

// Decide INSERT ou UPDATE
try {
    if ($id === 0) {
        // Novo cadastro
        $sql  = "INSERT INTO usuarios (nome, login, email, senha) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt->execute([$nome, $login, $email, $hash]);

    } else {
        // Edição de usuário (só admin)
        if (!$acesso_admin) {
            throw new Exception("Sem permissão para editar.");
        }
        // Se passou senha, atualiza também, senão mantém a antiga
        if (!empty($senha)) {
            $hash   = password_hash($senha, PASSWORD_DEFAULT);
            $sql    = "UPDATE usuarios SET nome=?, login=?, email=?, senha=? WHERE id=?";
            $params = [$nome, $login, $email, $hash, $id];
        } else {
            $sql    = "UPDATE usuarios SET nome=?, login=?, email=? WHERE id=?";
            $params = [$nome, $login, $email, $id];
        }
        $stmt = $con->prepare($sql);
        $stmt->execute($params);
    }

    // Se chegou aqui, deu sucesso — exibe alerta e JS de redirect
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
      <meta charset="UTF-8">
      <title>Sucesso!</title>
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <style>
        :root {
          --cor-fundo: #000935;
          --cor-texto: #f5f5f5;
        }
        body {
          background-color: var(--cor-fundo);
          color: var(--cor-texto);
        }
      </style>
      <script>
        // após 3 segundos manda para index.php
        setTimeout(function(){
          window.location.href = 'index.php';
        }, 3000);
      </script>
    </head>
    <body class="d-flex justify-content-center align-items-center" style="height:100vh;">
      <div class="alert alert-success text-center" style="min-width:300px;">
        <h4>✔️ Cadastro realizado com sucesso!</h4>
        <p>Você será redirecionado para a página inicial em instantes.</p>
        <p><small>Caso não seja redirecionado, <a href="index.php">clique aqui</a>.</small></p>
      </div>
    </body>
    </html>
    <?php
    exit();

} catch (Exception $e) {
    die("Erro ao salvar usuário: " . $e->getMessage());
}
