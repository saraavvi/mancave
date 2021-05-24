<?php 
$border = $out_of_stock ? "border-danger" : "";
?>
<tr>
    <td>
        <a href="?page=products/details&id=<?= $product['id'] ?>">
            <img src="<?= $product['image'] ?>" class="rounded img-fluid mx-2" style="height: 3em;"/>
                <?= $product['name'] ?>
        </a>
    </td>
    <td><?= $product['price'] ?> SEK</td>
    <td>
        <form method="post" action="#" style="display: inline-block">
            <input  type="hidden" name="id" value="<?= $product['id'] ?>"/>
            <select name="qty" onchange="this.form.submit()" class="form-control <?= $border ?>" style="width: 4em;">
                <?php 
                for ($i = 1; ($i <= $product['stock'] || $i <= $qty ); $i++) {
                    $over_stock = $i > $product['stock'] ? "disabled" : "";
                    $selected = $i === $qty ? "selected" : "";
                    echo "<option $selected value='$i' $over_stock>$i</option>";
                };
                ?>
            </select>
        </form>
    </td>
    <td>
        <a href="?page=shoppingcart&id=<?= $product['id'] ?>&action=delete" class='btn btn-outline-danger'>Delete</a>
    </td>
</tr>