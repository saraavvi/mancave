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
        $statement = "SELECT orders.id, products.name, order_contents.id AS order_contents_id, order_contents.quantity, order_contents.price_each, order_contents.product_id, (order_contents.quantity * order_contents.price_each) AS row_total
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
        $statement = "SELECT orders.id, SUM(order_contents.quantity * order_contents.price_each) AS order_total, customers.id AS customer_id, customers.first_name, customers.last_name, customers.email, customers.address
        FROM `orders` 
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
        $statement = "SELECT * FROM products";
        $products = $this->db->select($statement);

        // return to controller
        return $products ?? false;
    }

}