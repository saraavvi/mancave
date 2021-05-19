<?php 
foreach ($products as $product) {
    $html = <<<HTML
        <tr>
            <th scope="row">$product[id]</th>
            <td>$product[name]</td>
            <td>$product[stock]</td>
            <td>
            <a href="?page=admin/products/update&id=$product[id]" class="btn btn-sm btn-outline-primary">Edit</a>
            </td>
            <td>
                <form method="post" action="?page=admin/products/delete" style="display: inline-block">
                    <input  type="hidden" name="id" value="$product[id]"/>
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </td>
        </tr>
        HTML;
    echo $html;
}
?>