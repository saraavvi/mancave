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
        $page = $_GET['page'] ?? "";

        $function = $this->routes[$page] ?? null; // 'create'

        if (!$function) {
            echo 'Page not found';
            exit;
        }
        echo call_user_func([$this, $function]);
    }

    private function index()
    {
        echo "This is index";
    }

    private function adminIndex()
    {
        $products = $this->product_model->fetchAllProducts();
        $this->view->renderAdminPage($products);
    }

    private function adminProductCreate()
    {
        $product_data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_data['name'] = $this->sanitize($_POST['name']);
            $product_data['price'] = (int)$this->sanitize($_POST['price']);
            $product_data['description'] = $this->sanitize($_POST['description']);
            $product_data['category_id'] = (int)$this->sanitize($_POST['category']);
            if ($_POST['brand'] !== "") {
                $product_data['brand_id'] = (int)$this->sanitize($_POST['brand']);
            }
            else $product_data['brand_id'] = null;
            $product_data['stock'] = (int)$this->sanitize($_POST['stock']);
            $product_data['image'] = $this->sanitize($_POST['image']);
            $product_data['specification'] = $this->sanitize($_POST['specification']);
            $this->product_model->createProduct($product_data);
            header('Location: ?page=admin/products');
            exit;
        }

        $this->view->renderCreatePage();
    }

    private function sanitize($text)
    {
        $text = trim($text);
        $text = stripslashes($text);
        $text = htmlspecialchars($text);
        return $text;
    }

}