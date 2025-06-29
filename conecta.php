<?php
    define("SERVIDOR", "localhost");
    define("USUARIO", "root");
    define("SENHA", "");
    define("BANCO", "d20_emporium");

    // Conecta ao banco
    function abreConexao() {
        $con = new mysqli(SERVIDOR, USUARIO, SENHA, BANCO);
        if ($con->connect_error) {
            die("Erro de conexão: " . $con->connect_error);
        }
        return $con;
    }

// USUÁRIOS

    function cadastrarUsuario($nome, $email, $senha) {
        $con = abreConexao();
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT); // segurança
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senhaHash);
        $stmt->execute();
        $stmt->close();
        $con->close();
    }

    function loginUsuario($email, $senha) {
        $con = abreConexao();
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();
        $con->close();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario; // login bem-sucedido
        } else {
            return null; // falha no login
        }
    }

    function editarUsuario($id, $nome, $email, $senha = null) {
        $con = abreConexao();

        if ($senha) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssi", $nome, $email, $senhaHash, $id);
        } else {
            $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssi", $nome, $email, $id);
        }

        $stmt->execute();
        $stmt->close();
        $con->close();
    }

    function excluirUsuario($id) {
        $con = abreConexao();
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $con->close();
    }

    function listarUsuarios() {
        $con = abreConexao();
        $sql = "SELECT id, nome, email FROM usuarios";
        $resultado = $con->query($sql);
        $usuarios = [];

        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }
        $con->close();
        return $usuarios;
    }

    function retornaUsuarioPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id = " . intval($id);
        $conexao = abreConexao(); // Usa a função existente para conectar
        $resultado = $conexao->query($sql);
        $conexao->close();

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            return mysqli_fetch_array($resultado);
        } else {
            return null;
        }
}


// PRODUTOS

    function cadastrarProduto($nome, $descricao, $preco, $estoque, $imagem) {
        $con = abreConexao();
        $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, imagem) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $null = NULL;
        $stmt->bind_param("ssdib", $nome, $descricao, $preco, $estoque, $null);
        $stmt->send_long_data(4, $imagem);
        $stmt->execute();
        $stmt->close();
        $con->close();
    }

    function listarProdutos() {
        $con = abreConexao();
        $sql = "SELECT * FROM produtos ORDER BY nome";
        $resultado = $con->query($sql);
        $produtos = [];

        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $produtos[] = $row;
            }
        }
        $con->close();
        return $produtos;
    }

    function editarProduto($id, $nome, $descricao, $preco, $estoque, $imagem = null) {
        $con = abreConexao();

        if ($imagem !== null) {
            $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, imagem = ? WHERE id = ?";
            $stmt = $con->prepare($sql);

            // Primeiro faz o bind com um marcador nulo temporário para a imagem
            $stmt->bind_param("ssdibi", $nome, $descricao, $preco, $estoque, $null, $id);

            // Depois envia os dados reais da imagem para o marcador de BLOB
            $stmt->send_long_data(4, $imagem); // índice 4 = imagem (começa em 0)

        } else {
            $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssdii", $nome, $descricao, $preco, $estoque, $id);
        }

        $stmt->execute();
        $stmt->close();
        $con->close();
    }

    function excluirProduto($id) {
        $con = abreConexao();
        $sql = "DELETE FROM produtos WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $con->close();
    }

    function obterProdutoPorId($id) {
        $con = abreConexao();
        $sql = "SELECT * FROM produtos WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $produto = $resultado->fetch_assoc();
        $stmt->close();
        $con->close();
        return $produto;
    }
?>