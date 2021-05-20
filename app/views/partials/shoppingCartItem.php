<tr>
    <td><?= $product['name'] ?></td>
    <td><?= $product['price'] ?> SEK</td>
    <td>
        <form method="post" action="#" style="display: inline-block">
            <input  type="hidden" name="id" value="<?= $product['id'] ?>"/>
            <select name="qty" onchange="this.form.submit()" class="form-control" style="min-width: 4em;">
                <?php 
                for ($i = 1; $i <= $product['stock']; $i++) {
                    $selected = $i === $qty ? "selected" : "";
                    echo "<option $selected value='$i'>$i</option>";
                };
                ?>
            </select>
        </form>
    </td>
    <td>
        <a href="?page=shoppingcart&id=<?= $product['id'] ?>&action=delete" class='btn btn-outline-danger'>Delete</a>
    </td>
</tr>