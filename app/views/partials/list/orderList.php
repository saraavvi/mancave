<?php 
foreach ($orders as $order) {
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
                    <li><a class="dropdown-item" href="?page=admin/orders&id={$order['id']}&status_id=1">Draft</a></li>
                    <li><a class="dropdown-item" href="?page=admin/orders&id={$order['id']}&status_id=2">Shipped</a></li>
                    <li><a class="dropdown-item" href="?page=admin/orders&id={$order['id']}&status_id=3">Pending</a></li>
                    <li><a class="dropdown-item" href="?page=admin/orders&id={$order['id']}&status_id=4">Cancelled</a></li>
                </ul>
            </td>
            <td>
                <a href="?page=admin/orders/details&id=$order[id]" class="btn btn-sm btn-outline-primary">View Order</a>
            </td>
            <td>
                <a href="?page=admin/orders/delete&id={$order['id']}" class="btn btn-sm btn-outline-danger">Delete Order</a>
            </td>
        </tr>
    HTML;
    echo $html;
}
?>