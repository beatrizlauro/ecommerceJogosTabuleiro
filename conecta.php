<?php
    define("SERVIDOR", "localhost");
    define("USUARIO", "root");
    define("SENHA", "");
    define("BANCO", "d20_emporium");

    // Conecta ao banco
    $conn = new mysqli("localhost", "root", "", "d20_emporium");
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    function abreConexao() {
        $conn = new mysqli(SERVIDOR, USUARIO, SENHA, BANCO);
        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        }
        return $conn;
    }
    
    function listarImagensProduto($produto_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM imagens_produto WHERE id_produto = ?");
    $stmt->bind_param("i", $produto_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// USUÁRIOS

    function cadastrarUsuario($nome, $email, $senha) {
        global $conn;
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT); // segurança
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senhaHash);
        $stmt->execute();
        $stmt->close();
        $con->close();
    }

    function loginUsuario($email, $senha) {
        global $conn;
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
        global $conn;

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
        global $conn;
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $con->close();
    }

    function listarUsuarios() {
       global $conn;
        $sql = "SELECT id, nome, email FROM usuarios";
        $resultado = $conn->query($sql);
        $usuarios = [];

        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }
        $conn->close();
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
    global $conn; // ou passe $conn como parâmetro se preferir

    $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, imagem) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdib", $nome, $descricao, $preco, $estoque, $imagem);
    $stmt->send_long_data(4, $imagem);
    $stmt->execute();

    return $conn->insert_id; // <- Retorna o ID do novo produto
}

    function listarProdutos() {
    global $conn;
    $produtos = [];

    $sql = "SELECT * FROM produtos";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $produtos[] = $row;
        }
    }

    return $produtos;
}

    function editarProduto($id, $nome, $descricao, $preco, $estoque, $imagem = null) {
        global $conn;

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
        global $conn;
        $sql = "DELETE FROM produtos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    function obterProdutoPorId(int $id): ?array {
        global $conn;
        $stmt = $conn->prepare("SELECT id, nome, descricao, preco, estoque, imagem FROM produtos WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }


    function editarProdutoSemImagem($id, $nome, $descricao, $preco, $estoque) {
        global $conn;
        $sql = "UPDATE produtos SET nome=?, descricao=?, preco=?, estoque=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii", $nome, $descricao, $preco, $estoque, $id);
        $stmt->execute();
}
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "d20_emporium";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
}

?>