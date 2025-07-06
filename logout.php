<?php
session_start();       // Inicia a sessão (caso não esteja iniciada)
session_unset();       // Limpa todas as variáveis da sessão
session_destroy();     // Destroi a sessão

// Redireciona para a página pública (index)
header("Location: index.php");
exit();
?>