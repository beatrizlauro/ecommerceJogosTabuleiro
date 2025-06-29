<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo - D20 Emporium</title>
</head>
<body>
<?php
if (isset($_SESSION["login"])) {
    echo "<h2>Bem-vindo, " . htmlspecialchars($_SESSION["login"]) . "!</h2>";
} else {
    header("Location: errosessao.php");
    exit();
}
?>
    <form action="logout.php" method="POST">
        <input type="submit" value="Sair" name="btnSair">
    </form>
</body>
</html>