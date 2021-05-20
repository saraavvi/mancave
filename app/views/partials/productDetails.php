<div class="col-md-6 border">
    <img src="<?= $product['image'] ?>" class="img-fluid p-4" alt="<?= $product['name'] ?>">
</div>
<div class="col-md-4 border">
    <h2><?= $product['name'] ?></h2>
    <p class="fs-1"><?= $product['price'] ?> SEK</p>
    <form action="#" method="POST">
        <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to Cart</button>
    </form>
    <div class="mt-3">
        <p><?= $product['description'] ?></p>
        <div>
            <div class="mt-3">
                <p><?= $product['specification'] ?></p>
                <div>
                </div>