<?php
    if(!isset($_GET["id"])){ // Novo usuário
        $idusuario = 0;
        $nome = "";
        $email = "";
        $senha = "";
    } else { // Editar usuário
        $idusuario = $_GET["id"];
        include_once("conecta.php");
        $usuario = retornaUsuarioPorId($idusuario); // Adicione esta função se ainda não tiver
        if ($usuario != null) {
            $nome = $usuario["nome"];
            $email = $usuario["email"];
            $senha = ""; // Senha não deve ser exibida por segurança
        }
    }

    include_once("topo.php");
?>

<div class="container">
    <div class="text-center">
        <h3>Cadastro de Usuário</h3>
    </div>
    <div class="painel">
        <form action="salvar_usuario.php" method="POST">
            <input type="hidden" name="id" value="<?php echo($idusuario); ?>">
            
            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="cNome">Nome</label>
                <div class="col-sm-8">
                    <input type="text" name="txtNome" class="form-control" id="cNome" placeholder="Nome" required value="<?php echo($nome); ?>">
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="cEmail">Email</label>
                <div class="col-sm-8">
                    <input type="email" name="txtEmail" class="form-control" id="cEmail" placeholder="Email" required value="<?php echo($email); ?>">
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-right" for="cSenha">Senha</label>
                <div class="col-sm-8">
                    <input type="password" name="txtSenha" class="form-control" id="cSenha" placeholder="Senha">
                    <small class="text-muted">Preencha apenas se desejar alterar a senha.</small>
                </div>
            </div>

            <div class="text-center">
                <input type="submit" class="btn btn-primary" value="Salvar" onclick="alert('Dados salvos com sucesso!');">
                <a href="lista_usuarios.php" class="btn btn-warning">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include_once("rodape.php"); ?>
