<div class="col-sm-12 col-md-5 row mt-5">
    <img src="<?= $product['image'] ?>" class="img-fluid p-4" alt="<?= $product['name'] ?>" style='max-height: 480px; max-width: 480px; object-fit: scale-down'>
</div>

<div class="col-sm-12 col-md-7 mt-5">
    <h2><?= $product['name'] ?></h2>
    <p><?= $brand[0]['name'] ?></p>
    <p class="fs-3"><?= $product['price'] ?> SEK</p>
    <form action="#" method="POST">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="hidden" name="product_name" value="<?= $product['name'] ?>">
        <input type="hidden" name="current_page" value="<?= $_SERVER["QUERY_STRING"] ?>">
        <button type="submit" name="add_to_cart" class="btn btn-outline-dark w-100" <?php if (!$product["stock"] > 0) { ?> disabled <?php } ?>>Add to Cart</button>
    </form>
    <div class="mt-3">
        <p><?= $product['description'] ?></p>
    </div>
    <div class="mt-3">
        <p><?= $product['specification'] ?></p>
    </div>
</div>