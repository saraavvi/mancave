<tr>
    <td>
        <?= $order_row['order_contents_id'] ?>
    </td>
    <td>
        <?= $order_row['product_id'] ?>
    </td>
    <td>
        <?= $order_row['name'] ?>
    </td>
    <td>
        <?= $order_row['price_each'] ?> SEK
    </td>
    <td>
        <?= $order_row['row_total'] ?> SEK
    </td>
    <?php
        if ($order_row['status_id'] == 2) {
            echo "<td>$order_row[quantity]</td>";
        } else {
            $output = "<td>
                            <form method='post' action='#' style='display: inline-block'>
                                <input type='hidden' name='update_row_qty_id' value='$order_row[order_contents_id]'/>
                                <select name='qty' onchange='this.form.submit()' class='form-control' style='width: 4em;'>";
            for ($i = 1; ($i <= $order_row['stock'] || $i <= $order_row['quantity']); $i++) {
                $over_stock = $i > $order_row['stock'] ? 'disabled' : '';
                $selected = $i == $order_row['quantity'] ? 'selected' : '';
                $output .= "<option $selected value='$i' $over_stock>$i</option>";
            };
            $output .= "</select></form></td>";
            echo $output;
        }
    ?>
    <td>
        <form action="#" method="post">
            <input type="hidden" name="delete_row_id" value="<?= $order_row['order_contents_id'] ?>">
            <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
    </td>
</tr>