<?php 
foreach ($orders as $order) {
    // current url path to append additional query params to (status)
    //TODO: needs to be fixed = "/mancave/?page=admin/orders&id=1&status_id=1&id=3&status_id=2&id=1&status_id=2"
    $uri = $_SERVER['REQUEST_URI'];
    $html = <<<HTML
        <tr>
            <th scope="row">$order[id]</th>
            <td>$order[order_date]</td>
            <td>$order[customer_name]</td>
            <td>$order[status_name]</td>
            <td class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Change Status
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="{$uri}&id={$order['id']}&status_id=1">Draft</a></li>
                    <li><a class="dropdown-item" href="{$uri}&id={$order['id']}&status_id=2">Shipped</a></li>
                    <li><a class="dropdown-item" href="{$uri}&id={$order['id']}&status_id=3">Pending</a></li>
                    <li><a class="dropdown-item" href="{$uri}&id={$order['id']}&status_id=4">Cancelled</a></li>
                </ul>
            </td>
            <td>
                <a href="#" class="btn btn-sm btn-outline-primary">View Order</a>
            </td>
            <td>
                <a href="{$uri}&id={$order['id']}&action=delete" class="btn btn-sm btn-outline-danger">Delete Order</a>
            </td>
        </tr>
    HTML;
    echo $html;
}
?>