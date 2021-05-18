<?php

if (isset($_POST["add_to_cart"])) {
    // echo $_GET['id'];
    array_push($_SESSION['shopping_cart'], $_GET['id']);
}

?>

<div class="col-md-6 border">
    <img src="<?= $product['image'] ?>" class="img-fluid" alt="...">
</div>
<div class="col-md-4 border">
    <h2><?= $product['name'] ?></h2>
    <p class="fs-1"><?= $product['price'] ?> SEK</p>
    <form action="#" method="POST">
        <button type="submit" name="add_to_cart" class="btn btn-primary w-100">add to cart</button>
    </form>
    <div class="mt-3">
        <p class="fw-bold">product information</p>
        <p><?= $product['description'] ?></p>
        <div>
            <div class="mt-3">
                <p><?= $product['specification'] ?></p>
                <div>
                </div>