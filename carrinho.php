<?php
session_start();
include_once("conecta.php");

// Inicializa o carrinho
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Função para buscar produto no banco, incluindo estoque
function buscarProdutoPorId($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, nome, preco, estoque FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Ações do carrinho
if (isset($_GET['acao'])) {
    $id = $_GET['id'] ?? null;

    if ($id) {
        $produtoBD = buscarProdutoPorId($id);
        if (!$produtoBD) {
            // Produto não encontrado, apenas redireciona
            header("Location: carrinho.php");
            exit;
        }

        $estoque = intval($produtoBD['estoque']);

        switch ($_GET['acao']) {
            case 'adicionar':
                $quantidadeAtual = $_SESSION['carrinho'][$id]['quantidade'] ?? 0;
                if ($quantidadeAtual + 1 <= $estoque) {
                    if (isset($_SESSION['carrinho'][$id])) {
                        $_SESSION['carrinho'][$id]['quantidade']++;
                    } else {
                        $_SESSION['carrinho'][$id] = [
                            'nome' => $produtoBD['nome'],
                            'preco' => $produtoBD['preco'],
                            'quantidade' => 1,
                            'imagem' => "exibir_img.php?id=$id"
                        ];
                    }
                }
                break;

            case 'incrementar':
                if (!isset($_SESSION['carrinho'][$id])) break;

                $quantidadeAtual = $_SESSION['carrinho'][$id]['quantidade'];
                if ($quantidadeAtual + 1 <= $estoque) {
                    $_SESSION['carrinho'][$id]['quantidade']++;
                }
                break;

            case 'diminuir':
                if (isset($_SESSION['carrinho'][$id])) {
                    if ($_SESSION['carrinho'][$id]['quantidade'] > 1) {
                        $_SESSION['carrinho'][$id]['quantidade']--;
                    } else {
                        unset($_SESSION['carrinho'][$id]);
                    }
                }
                break;

            case 'remover':
                unset($_SESSION['carrinho'][$id]);
                break;
        }
    }

    header("Location: carrinho.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho de Compras</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include("topo.php"); ?>

<div class="container my-5">
    <h2 class="mb-4">Carrinho de Compras</h2>

    <?php if (empty($_SESSION['carrinho'])): ?>
        <p style="color: white;">Seu carrinho está vazio.</p>
        <a href="loja.php" class="btn btn-outline-primary">Voltar à loja</a>
    <?php else: ?>
        <?php
        $total = 0;
        foreach ($_SESSION['carrinho'] as $id => $produto):
            $subtotal = $produto['preco'] * $produto['quantidade'];
            $total += $subtotal;

            // Busca estoque atual para controle do botão
            $produtoBD = buscarProdutoPorId($id);
            $estoque = intval($produtoBD['estoque']);
        ?>
            <div class="card mb-3">
                <div class="row g-0 align-items-center">
                    <div class="col-md-2">
                        <img src="<?= $produto['imagem'] ?>" class="img-fluid rounded-start" alt="<?= $produto['nome'] ?>">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <h5 class="card-title"><?= $produto['nome'] ?></h5>
                            <p class="card-text">Preço: R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                            <p class="card-text">Quantidade: <?= $produto['quantidade'] ?></p>
                            <div>
                                <a href="?acao=incrementar&id=<?= $id ?>" class="btn btn-sm btn-outline-success <?= ($produto['quantidade'] >= $estoque) ? 'disabled' : '' ?>">+</a>
                                <a href="?acao=diminuir&id=<?= $id ?>" class="btn btn-sm btn-outline-warning">-</a>
                                <a href="?acao=remover&id=<?= $id ?>" class="btn btn-sm btn-outline-danger">Remover</a>
                            </div>
                            <?php if ($produto['quantidade'] >= $estoque): ?>
                                <small class="text-danger">Estoque máximo atingido</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-3 text-end pe-4">
                        <strong>Subtotal:</strong>
                        <p>R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-end">
            <h4>Total: R$ <?= number_format($total, 2, ',', '.') ?></h4>
        </div>

        <form action="finalizar_pedido.php" method="POST" class="mt-4">
            <h5>Endereço de Entrega</h5>
            <div class="row mb-2">
                <div class="col-md-6">
                    <label>CEP</label>
                    <input type="text" name="cep" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Rua</label>
                    <input type="text" name="rua" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Número</label>
                    <input type="text" name="numero" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Cidade</label>
                    <input type="text" name="cidade" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Estado</label>
                    <input type="text" name="estado" class="form-control" required>
                </div>
            </div>

            <h5>Forma de Pagamento</h5>
            <select name="pagamento" class="form-select" required>
                <option value="">Selecione</option>
                <option value="pix">PIX</option>
                <option value="cartao">Cartão de Crédito</option>
                <option value="boleto">Boleto Bancário</option>
            </select>

            <input type="hidden" name="confirmar" value="1">

            <div class="mt-4 d-flex justify-content-between">
                <a href="loja.php" class="btn btn-outline-secondary">← Continuar Comprando</a>
                <button type="submit" class="btn btn-primary">Finalizar Compra</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include("rodape.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
