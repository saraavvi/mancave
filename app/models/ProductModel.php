<?php

/* innehåller följande metoder:
fetchAllProducts
deleteProduct
updateProduct
delete product */

class ProductModel
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

        // return to controller
        return $product[0] ?? false;
    }

    /***
     * Fetch all products, return an array with all products.
     */
    public function fetchAllProducts()
    {
        $statement = "SELECT * FROM products";
        $products = $this->db->select($statement);

        // return to controller
        return $products ?? false;
    }

    public function fetchProductsByCategory($category)
    {
        $statement = "SELECT * FROM products WHERE category_id = :category";
        $params = array(":category" => $category);
        $products = $this->db->select($statement, $params);

        // return to controller
        return $products ?? false;
    }

    //TODO: Create update products function
    /*   public function updateProductById($id)
    {
        $statement = 
        $params =

    } */

    /***
     * Delete product by id and return no of rows affected
     */
    public function deleteProductById($id)
    {
        $statement = "DELETE FROM products WHERE id = :id";
        $params = array(':id' => $id);
        $row_count = $this->db->delete($statement, $params);

        // return number of rows deleted to controller
        return $row_count;
    }

    /***
     * Create new product, return last insert index
     */
    public function createProduct($data)
    {
        $statement = "INSERT INTO products (
                name, 
                price, 
                description, 
                category_id, 
                brand_id, 
                stock, 
                image, 
                specification
            ) 
            VALUES 
            ( 
                :name,
                :price,
                :description, 
                :category_id,
                :brand_id, 
                :stock,
                :image,
                :specification
            )";

        $params = array(
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':description' => $data['description'],
            ':category_id' => $data['category_id'],
            ':brand_id' => $data['brand_id'],
            ':stock' => $data['stock'],
            ':image' => $data['image'],
            ':specification' => $data['specification']
        );


        $last_insert_id = $this->db->insert($statement, $params);

        return $last_insert_id;
    }
}
