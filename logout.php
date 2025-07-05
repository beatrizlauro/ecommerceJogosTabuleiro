<?php
session_unset();  // limpa todas as variáveis da sessão
session_destroy(); // destrói a sessão
session_start();

if (isset($_SESSION["Sair"])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
// Redireciona para a página de login
header("Location: login.php");
exit();
?>
