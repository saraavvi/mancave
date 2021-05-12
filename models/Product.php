<?php

/* innehåller följande metoder:
fetchAllProducts
deleteProduct
updateProduct
delete product */

class Product
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    /***
     * Fetch one product by ID, return an array with the matching product if it exists, otherwise return false.
     */
    public function fetchProductById($id)
    {
        $statement = "SELECT * FROM products WHERE id = :id";
        $params = array(':id' => $id);
        $product = $this->db->select($statement, $params);

        // returns to controller
        return $product[0] ?? false;
    }

    /***
     * Fetch all products, return an array with all products.
     */
    public function fetchAllProducts()
    {
        $statement = "SELECT * FROM products";
        $products = $this->db->select($statement);

        // returns to controller
        return $products ?? false;
    }


  /*   public function updateProductById($id)
    {
        $statement = 
        $params =

    } */

    public function deleteProductById($id)
    {
        $statement = "DELETE FROM products WHERE id = :id";
        $params = array(':id' => $id);
        $row_count = $this->db->delete($statement, $params);

        return $row_count;
    }

}
