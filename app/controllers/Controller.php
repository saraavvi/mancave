<?php

class Controller
{
    private $product_model;
    private $order_model; // ta senare in som parameter i constr
    private $customer_model; // ta senare in som parameter i constr
    private $view;
    private $routes;

    /**
     *
     */
    public function __construct($product_model, $view, $routes)
    {
        $this->product_model = $product_model;
        $this->view = $view;
        $this->routes = $routes;
        $this->resolveRoute();
    }

    /**
     *
     */
    private function resolveRoute()
    {
        $page = $_GET["page"] ?? "";

        $function = $this->routes[$page] ?? null; // 'create'

        $this->conditionForExit(!$function);
        echo call_user_func([$this, $function]);
    }

    private function index()
    {
        echo "This is index";
    }

    private function adminIndex()
    {
        $products = $this->product_model->fetchAllProducts();
        $this->view->renderAdminIndexPage($products);
    }

    private function adminProductCreate()
    {
        $product_data = array();
        $errors = array();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $product_data = $this->handleProductPost();
                $this->product_model->createProduct($product_data);
                header("Location: ?page=admin/products");
                exit();
            } catch (Exception $e) {
                $error = json_decode($e->getMessage(), true);
                $errors = $error;
            }
        }

        $brands = $this->product_model->fetchAllBrands();
        $categories = $this->product_model->fetchAllCategories();
        $this->view->renderAdminProductCreatePage($brands, $categories, $errors);
    }

    private function adminProductUpdate()
    {
        $this->conditionForExit(empty($_GET['id']));
        $id = (int)$this->sanitize($_GET['id']);
        $product_data = array();
        $errors = array();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $product_data = $this->handleProductPost();
                $this->product_model->updateProductById($id, $product_data);
                header('Location: ?page=admin/products');
                exit;
                echo "<pre>";
                var_dump($product_data);
                echo "</pre>";
            } catch (Exception $e) {
                $error = json_decode($e->getMessage(), true);
                $errors = $error;
            }
        }

        $brands = $this->product_model->fetchAllBrands();
        $categories = $this->product_model->fetchAllCategories();
        $product_data = $this->product_model->fetchProductById($id);
        //TODO: Better error handling
        if (!$product_data) echo 'Product id does not exist.';
        else $this->view->renderAdminProductUpdatePage($brands, $categories, $product_data, $errors);
    }

    private function handleProductPost() {
        $errors = array();
        
        $name = $this->getPost('name');
        $price = $this->getPost('price', true);
        $description = $this->getPost('description');
        $category_id = $this->getPost('category_id', true);
        $stock = $this->getPost('stock', true);
        $image = $this->getPost('image');
        $specification = $this->getPost('specification');

        $chosen_brand = $this->getPost('brand_id', true);
        $new_brand_chosen = $this->getPost('brand_id') === 'NEW';
        $new_brand = $this->getPost('new_brand');

        if ((!$new_brand_chosen && $new_brand) || ($new_brand_chosen && !$new_brand) || (!$chosen_brand && !$new_brand)) {
            array_push($errors, "To add a new brand, please pick option 'Add New Brand' and enter a brand name below.");
        } else if ($new_brand_chosen && $new_brand) {
            $product_data['brand_id'] = $this->product_model->createBrand($new_brand);
        } else {
            $product_data['brand_id'] = $chosen_brand;
        }

        if ($name && $price && $category_id) {
            $product_data['name'] = $name;
            $product_data['price'] = $price;
            $product_data['description'] = $description;
            $product_data['category_id'] = $category_id;
            $product_data['stock'] = $stock;
            $product_data['image'] = $image;
            $product_data['specification'] = $specification;
        } else {
            array_push($errors, 'Please fill in all required feilds');
        }

        if (count($errors) === 0) {
            return $product_data;
        } else {
            throw new Exception(json_encode($errors));
        }
    }

    /**
     * Expects name of post key, 
     * optional bool (true for int values, default false) 
     * returns value or false
    */
    private function getPost($name, $int = false) {
        if (isset($_POST[$name])) {
            $value = $this->sanitize($_POST[$name]);
            if ($int) return (int)$value;
            return $value;
        }
        return false;
    }

    private function sanitize($text)
    {
        $text = trim($text);
        $text = stripslashes($text);
        $text = htmlspecialchars($text);
        return $text;
    }

    private function conditionForExit($condition)
    {
        if ($condition) {
            echo "Page not found";
            exit();
        }
    }
}
