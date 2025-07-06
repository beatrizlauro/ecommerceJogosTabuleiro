<?php
session_start();
include_once("conecta.php");
include("topo.php");

$produtos = listarProdutos();
$destaques = array_slice($produtos, 0, 4);
?>

<section class="hero-section">
  <div class="hero-content">
    <h1>Bem-vindo à D20 Emporium</h1>
    <p>Descubra os jogos de tabuleiro mais épicos para desafiar sua mente!</p>
    <?php if (!isset($_SESSION["idusuario"])): ?>
      <a href="login.php" class="btn-hero">Entrar</a>
      <a href="formUsuario.php" class="btn-hero" style="background-color: transparent; border: 2px solid white; margin-left: 15px;">Criar Conta</a>
    <?php else: ?>
      <a href="loja.php" class="btn-hero">Ver Loja Completa</a>
    <?php endif; ?>
  </div>
</section>

<div class="container mt-5">
  <h2 class="section-title">Destaques</h2>
  <div class="product-grid">
    <?php foreach ($destaques as $produto): ?>
      <div class="product-card">
        <div id="carousel<?= $produto['id'] ?>" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php if (!empty($produto['imagem'])): ?>
              <div class="carousel-item active">
                <img src="exibir_img_principal.php?id=<?= $produto['id'] ?>" class="d-block w-100 product-img" alt="Imagem Principal" style="height: 250px; object-fit: contain; background-color: #fff;">
              </div>
            <?php endif; ?>

            <?php
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

  <div class="text-center mt-4">
    <a href="loja.php" class="btn-outline">Ver Todos os Produtos</a>
  </div>
</div>

<?php include("rodape.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
