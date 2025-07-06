<?php
session_start();
include_once("conecta.php");
include("topo.php");

$produtos = listarProdutos();
?>

<div class="container mt-5">
  <h2 class="section-title">Todos os Produtos</h2>
  <div class="product-grid">
    <?php foreach ($produtos as $produto): ?>
      <div class="product-card">
        <div id="carousel<?= $produto['id'] ?>" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php
            // Adiciona a imagem principal como primeiro item do carrossel
            if (!empty($produto['imagem'])):
            ?>
              <div class="carousel-item active">
                <img src="exibir_img_principal.php?id=<?= $produto['id'] ?>" class="d-block w-100 product-img" alt="Imagem Principal" style="height: 250px; object-fit: contain; background-color: #fff;">
              </div>
            <?php
            endif;

            // Adiciona imagens adicionais da tabela imagens_produto
            $imagens = listarImagensProduto($produto['id']);
            foreach ($imagens as $img): ?>
              <div class="carousel-item">
                <img src="exibir_img.php?id=<?= $img['id'] ?>" class="d-block w-100 product-img" alt="Imagem Adicional" style="height: 250px; object-fit: contain; background-color: #fff;">
              </div>
            <?php endforeach; ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $produto['id'] ?>" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $produto['id'] ?>" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>

        <div class="product-info">
          <h5 class="product-title"><?= htmlspecialchars($produto['nome']) ?></h5>
          <p class="product-description"><?= htmlspecialchars($produto['descricao']) ?></p>
          <p class="product-price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
          <?php if ($produto['estoque'] > 0): ?>
          <form action="carrinho.php" method="GET">
            <input type="hidden" name="acao" value="adicionar">
            <input type="hidden" name="id" value="<?= $produto['id'] ?>">
            <button type="submit" class="btn-adicionar">Adicionar ao Carrinho</button>
          </form>
        <?php else: ?>
          <button class="btn-adicionar btn btn-secondary" disabled>Produto Esgotado</button>
        <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include("rodape.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
