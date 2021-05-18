<div class="col-md-6 border">
    <img src="<?= $product['image'] ?>" class="img-fluid p-4" alt="<?= $product['name'] ?>">
</div>
<div class="col-md-4 border p-4">
    <h2><?= $product['name'] ?></h2>
    <p class="fs-1"><?= $product['price'] ?> SEK</p>
    <button class="btn btn-primary w-100">Add to cart</button>
    <div class="mt-3">
        <p class="fw-bold">Product Information:</p>
        <p><?= $product['description'] ?></p>
    <div>
    <div class="mt-3">
        <p class="fw-bold">Specifications:</p>
        <p><?= $product['specification'] ?></p>
    <div>
</div>