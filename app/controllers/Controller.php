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
        $this->view->renderHeader("mancave - home");
        $this->view->renderFooter();
    }

    private function getProductsByCategory()
    {
        $category = $_GET['category'] ?? "";
        $this->view->renderHeader("mancave - home");
        $products = $this->product_model->fetchProductsByCategory($category);
        $this->view->renderCustomerProducts($products);
        $this->view->renderFooter();
    }

    private function adminIndex()
    {
        $products = $this->product_model->fetchAllProducts();
        $this->view->renderAdminIndexPage($products);
    }

    private function adminProductCreate()
    {
        $product_data = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $product_data["name"] = $this->sanitize($_POST["name"]);
            $product_data["price"] = (int)$this->sanitize($_POST["price"]);
            $product_data["description"] = $this->sanitize(
                $_POST["description"]
            );
            $product_data["category_id"] = (int)$this->sanitize(
                $_POST["category_id"]
            );
            if ($_POST["brand_id"] !== "") {
                $product_data["brand_id"] = (int)$this->sanitize(
                    $_POST["brand_id"]
                );
            } else {
                $product_data["brand_id"] = null;
            }
            $product_data["stock"] = (int)$this->sanitize($_POST["stock"]);
            $product_data["image"] = $this->sanitize($_POST["image"]);
            $product_data["specification"] = $this->sanitize(
                $_POST["specification"]
            );
            $this->product_model->createProduct($product_data);
            header("Location: ?page=admin/products");
            exit();
        }

        $brands = $this->product_model->fetchAllBrands();
        $categories = $this->product_model->fetchAllCategories();
        $this->view->renderAdminProductCreatePage($brands, $categories);
    }

    private function adminProductUpdate()
    {
        $this->conditionForExit(empty($_GET['id']));
        $id = (int)$this->sanitize($_GET['id']);
    
        $product_data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_data['name'] = $this->sanitize($_POST['name']);
            $product_data['price'] = (int)$this->sanitize($_POST['price']);
            $product_data['description'] = $this->sanitize($_POST['description']);
            $product_data['category_id'] = (int)$this->sanitize($_POST['category_id']);
            $product_data['brand_id'] = (int)$this->sanitize($_POST['brand_id']);
            $product_data['stock'] = (int)$this->sanitize($_POST['stock']);
            $product_data['image'] = $this->sanitize($_POST['image']);
            $product_data['specification'] = $this->sanitize($_POST['specification']);

            $this->product_model->updateProductById($id, $product_data);
            header('Location: ?page=admin/products');
            exit;
        }
        $brands = $this->product_model->fetchAllBrands();
        $categories = $this->product_model->fetchAllCategories();
        $product_data = $this->product_model->fetchProductById($id);
        //TODO: Better error handling
        if (!$product_data) echo 'Product id does not exist.';
        else $this->view->renderAdminProductUpdatePage($product_data, $brands, $categories);
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
