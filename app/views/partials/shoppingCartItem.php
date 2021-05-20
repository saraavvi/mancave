<tr>
    <td><?= $product['name'] ?></td>
    <td><?= $product['price'] ?> SEK</td>
    <td><?= $qty ?></td>
    <td><a href="?page=shoppingcart&id=<?= $product['id'] ?>&action=delete" class='btn btn-danger'>x</a></td>
</tr>