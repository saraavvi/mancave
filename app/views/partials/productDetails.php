<div class="col-xl-6 col-lg-11 col-md-10 border row justify-content-center">
    <img src="<?= $product['image'] ?>" class="img-fluid p-4" alt="<?= $product['name'] ?>" style='max-height: 480px; max-width: 480px; object-fit: scale-down'>
</div>

<div class="col-xl-4 col-lg-11 col-md-10 border p-4">
    <h2><?= $product['name'] ?></h2>
    <p class="fs-1"><?= $product['price'] ?> SEK</p>
    <form action="#" method="POST">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="hidden" name="product_name" value="<?= $product['name'] ?>">
        <input type="hidden" name="current_page" value="<?= $_SERVER["QUERY_STRING"] ?>">
        <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to Cart</button>
    </form>
    <div class="mt-3">
        <p><?= $product['description'] ?></p>
    </div>
    <div class="mt-3">
        <p><?= $product['specification'] ?></p>
    </div>
</div>