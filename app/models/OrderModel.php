<?php

class OrderModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    /***
     * Fetch order contents for one order by ID, return an array with the matching order contents if it exists, otherwise return false.
     */
    public function fetchOrderContentsByOrderId($id)
    {
        $statement = "SELECT orders.id, 
            orders.status_id,
            products.name, 
            products.stock,
            order_contents.id AS order_contents_id, 
            order_contents.quantity, 
            order_contents.price_each, 
            order_contents.product_id, 
            (order_contents.quantity * order_contents.price_each) AS row_total
        FROM `orders`
        INNER JOIN order_contents ON orders.id = order_contents.order_id
        INNER JOIN products ON order_contents.product_id = products.id
        WHERE orders.id = :id
        GROUP BY order_contents.id";
        $params = array(':id' => $id);
        $order_contents = $this->db->select($statement, $params);

        // return to controller
        if (count($order_contents) === 0)
            return false;

        return $order_contents;
    }

    /***
     * Fetch one order with customer info and †otal order value. 
     */
    public function fetchOrderById($id)
    {
        $statement = "SELECT 
            orders.id, orders.order_date, 
            statuses.id AS status_id, statuses.name AS status_name, 
            SUM(order_contents.quantity * order_contents.price_each) AS order_total, 
            customers.id AS customer_id, CONCAT(customers.first_name, ' ', customers.last_name) AS customer_name, customers.email, customers.address
        FROM `orders` 
        INNER JOIN statuses ON orders.status_id = statuses.id 
        INNER JOIN order_contents ON orders.id = order_contents.order_id 
        INNER JOIN customers ON orders.customer_id = customers.id 
        WHERE orders.id = :id GROUP BY orders.id ";
        $params = array(':id' => $id);
        $order = $this->db->select($statement, $params);

        // return to controller
        return $order[0] ?? false;
    }

    /***
     * Fetch all orders, return an array with all orders.
     */
    public function fetchAllOrders()
    {
        $statement = "SELECT 
            orders.id, orders.order_date, 
            statuses.id AS status_id, statuses.name AS status_name, 
            SUM(order_contents.quantity * order_contents.price_each) AS order_total, 
            customers.id AS customer_id, CONCAT(customers.first_name, ' ', customers.last_name) AS customer_name, customers.email, customers.address
        FROM orders 
        LEFT JOIN statuses ON orders.status_id = statuses.id 
        LEFT JOIN order_contents ON orders.id = order_contents.order_id 
        LEFT JOIN customers ON orders.customer_id = customers.id 
        GROUP BY orders.id";
        $order = $this->db->select($statement);

        // return to controller
        if (count($order) === 0)

            return false;

        return $order;
    }

    /***
     * Create new order, return last insert index
     */
    public function createNewOrder($customer_id)
    {
        $statement = "INSERT INTO orders ( customer_id ) VALUES ( :customer_id )";
        $params = array(':customer_id' => $customer_id);

        $last_insert_id = $this->db->insert($statement, $params);

        return $last_insert_id;
    }

    /***
     * Create new order content, return last insert index
     */
    public function createNewOrderContent($order_id, $product_id, $qty, $current_price)
    {
        $statement = "INSERT INTO order_contents (
                order_id,
                product_id, 
                quantity,
                price_each
            ) 
            VALUES 
            ( 
                :order_id,
                :product_id, 
                :quantity,
                :price_each
            )";

        $params = array(
            ':order_id' => $order_id,
            ':product_id' => $product_id,
            ':quantity' => $qty,
            ':price_each' => $current_price
        );

        $this->db->insert($statement, $params);
    }

    public function updateOrderStatus($order_id, $status_id) {
        $statement = "UPDATE orders 
            SET status_id = :status_id 
            WHERE orders.id = :order_id;";
        $params = array(
            ':status_id' => $status_id,
            ':order_id' => $order_id
        );
        $row_count = $this->db->update($statement, $params);
        return $row_count;
    }

    public function updateOrderShippedDate($order_id) {
        $statement = "UPDATE orders 
            SET shipped_date = CURRENT_TIMESTAMP()
            WHERE orders.id = :order_id;";
        $params = array(':order_id' => $order_id);
        $this->db->update($statement, $params);
    }

    public function updateOrderContentQuantity($id, $qty) {
        $statement = "UPDATE order_contents 
            SET quantity = :qty
            WHERE order_contents.id = :id;";
        $params = array(':id' => $id, ':qty' => $qty);
        $this->db->update($statement, $params);
    }

    public function deleteOrder($order_id) {
        
        $statement = "DELETE FROM orders WHERE orders.id = :order_id;";
        $params = array(':order_id' => $order_id);
        $row_count = $this->db->update($statement, $params);
        return $row_count;
    }

    public function deleteOrderContent($id) {
        $statement = "DELETE FROM order_contents WHERE order_contents.id = :id;";
        $params = array(':id' => $id);
        $row_count = $this->db->update($statement, $params);
        return $row_count;
    }

    //TODO: Update order content
    //TODO: Update order
    
}
