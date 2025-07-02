<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - D20 Emporium</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white text-center">
                        <h4>Login - D20 Emporium</h4>
                    </div>
                    <div class="card-body">
                        <form action="verificalogin.php" method="POST">
                            <div class="form-group">
                                <label for="txtLogin">Usuário (e-mail):</label>
                                <input type="email" name="txtLogin" id="txtLogin" class="form-control" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="txtSenha">Senha:</label>
                                <input type="password" name="txtSenha" id="txtSenha" class="form-control" required>
                            </div>
                            <div class="text-center mt-4">
                                <input type="submit" name="btnEntrar" value="Entrar" class="btn btn-primary">
                                <a href="index.php" class="btn btn-secondary ms-2">Voltar</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">D20 Emporium - Sistema de E-commerce</small>
                </div>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>