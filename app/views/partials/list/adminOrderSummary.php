
<tr>
    <th class="pt-4" scope="row">Order ID</th>
    <th class="pt-4" >Order Status</th>
    <th colspan="2" class="pt-4" >Customer Name</th>
    <th colspan="2" class="pt-4" >Total price</th>
    <th class="pt-4" >Date Placed</th>
</tr>
<tr>
    <th scope="row"><?= $order['id'] ?></th>
    <td><?= $order['status_name'] ?></td>
    <td colspan="2"><?= $order['customer_name'] ?></td>
    <td colspan="2"><?= $order['order_total'] ?> SEK</td>
    <td><?= $order['order_date'] ?></td>
</tr>