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
        $params = array(":id" => $id);
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
        if (count($products) === 0)
            return false;

        return $products; // ?? false;
    }

    /***
     * Fetch products from a specific category, return an array with all products.
     */
    public function fetchProductsByCategory($category)
    {
        $statement = "SELECT 
                products.*, 
                categories.name AS category_name 
            FROM products 
            LEFT JOIN categories ON products.category_id = categories.id
            WHERE products.category_id = :category";
        $params = array(":category" => $category);
        $products = $this->db->select($statement, $params);

        // return to controller
        return $products ?? false;
    }

    /***
     * Fetch products from a specific brand, return an array with all products.
     */
    public function fetchProductsByBrand($brand)
    {
        $statement = "SELECT 
                products.*, 
                brands.name AS brand_name 
            FROM products 
            LEFT JOIN brands ON products.brand_id = brands.id
            WHERE products.brand_id = :brand";
        $params = array(":brand" => $brand);
        $products = $this->db->select($statement, $params);

        // return to controller
        return $products ?? false;
    }

    public function updateProductById($id, $data)
    {
        $statement = "UPDATE products SET
            name = :name, 
            price = :price, 
            description = :description, 
            category_id = :category_id, 
            brand_id = :brand_id, 
            stock = :stock, 
            image = :image, 
            specification = :specification
        WHERE id = :id";
        $params = array(
            ':id' => $id,
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':description' => $data['description'],
            ':category_id' => $data['category_id'],
            ':brand_id' => $data['brand_id'],
            ':stock' => $data['stock'],
            ':image' => $data['image'],
            ':specification' => $data['specification']
        );
        $this->db->update($statement, $params);
    }

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

    public function addProductStock($id, $qty)
    {
        $statement = "UPDATE products 
            SET stock = stock + :qty 
            WHERE products.id = :id;";
        $params = array(':id' => $id, ':qty' => $qty);
        $row_count = $this->db->update($statement, $params);
        return $row_count;
    }
    
    public function reduceProductStock($id, $qty)
    {
        $statement = "UPDATE products 
            SET stock = stock - :qty 
            WHERE products.id = :id;";
        $params = array(':id' => $id, ':qty' => $qty);
        $row_count = $this->db->update($statement, $params);
        return $row_count;
    }
    
    public function fetchAllBrands()
    {
        $statement = "SELECT * FROM brands";
        $brands = $this->db->select($statement);
        return $brands;
    }

    public function fetchBrandById($id)
    {
        $statement = "SELECT * FROM brands WHERE id = :id";
        $params = array(":id" => $id);
        $brand = $this->db->select($statement, $params);
        return $brand;
    }

    public function fetchAllCategories()
    {
        $statement = "SELECT * FROM categories";
        $categories = $this->db->select($statement);
        return $categories;
    }

    public function createBrand($name)
    {
        $statement = "INSERT INTO brands ( name ) VALUES ( :name )";
        $params = array(':name' => $name);

        $last_insert_id = $this->db->insert($statement, $params);

        return $last_insert_id;
    }
}
