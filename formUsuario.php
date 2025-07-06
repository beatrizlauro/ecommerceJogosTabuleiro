<?php
session_start();
include_once("conecta.php");

// Verifica se é um visitante ou um admin
$acesso_admin = isset($_SESSION["idusuario"]) && isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1;


// Se o usuário estiver logado, mas **não for admin**, bloquear acesso
if (isset($_SESSION["idusuario"]) && !$acesso_admin) {
    header("Location: errosessao.php"); // ou outra página de erro
    exit();
}

// Se estiver acessando para editar (via ?id=), só admins podem
if (isset($_GET["id"])) {
    if (!$acesso_admin) {
        header("Location: errosessao.php");
        exit();
    }

    $idusuario = $_GET["id"];
    $usuario = retornaUsuarioPorId($idusuario);

    if ($usuario != null) {
        $nome = $usuario["nome"];
        $email = $usuario["email"];
        $senha = ""; // Por segurança
    }
} else {
    // Cadastro novo (visitante ou admin)
    $idusuario = 0;
    $nome = "";
    $email = "";
    $senha = "";
}

include_once("topo.php");
?>

<div class="container">
    <div class="text-center">
        <h3 style="color: white;"><?= $idusuario == 0 ? "Criar Conta" : "Cadastro de Usuário" ?></h3>
    </div>
    <div class="painel">
        <form action="salvar_usuario.php" method="POST">
            <input type="hidden" name="id" value="<?= $idusuario ?>">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="cNome">Nome</label>
                <div class="col-sm-8">
                    <input type="text" name="txtNome" class="form-control" id="cNome" required value="<?= $nome ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="cLogin">Login</label>
                <div class="col-sm-8">
                    <input type="text" name="txtLogin" class="form-control" id="cLogin" required value="<?= isset($usuario["login"]) ? $usuario["login"] : "" ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="cEmail">Email</label>
                <div class="col-sm-8">
                    <input type="email" name="txtEmail" class="form-control" id="cEmail" required value="<?= $email ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="cSenha">Senha</label>
                <div class="col-sm-8">
                    <input type="password" name="txtSenha" class="form-control" id="cSenha">
                    <small class="text-muted">Preencha apenas se desejar alterar a senha.</small>
                </div>
            </div>

            <div class="text-center">
                <input type="submit" class="btn btn-primary" value="Salvar">
                <?php if ($acesso_admin): ?>
                    <a href="lista_usuarios.php" class="btn btn-warning">Cancelar</a>
                <?php else: ?>
                    <a href="index.php" class="btn btn-secondary">Voltar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php include_once("rodape.php"); ?>
