<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - D20 Emporium</title>
</head>
<body>
    <h2>Login</h2>
    <form action="verificalogin.php" method="POST">
        <label>Usuário (e-mail):</label><br>
        <input type="email" name="txtLogin" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="txtSenha" required><br><br>

        <input type="submit" name="btnEntrar" value="Entrar">
    </form>
</body>
</html>