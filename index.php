<?php
session_start();
if (empty($_SESSION['shopping_cart'])) {
    $_SESSION['shopping_cart'] = array();
}

/* require_once ‘./views/View.php’;
$view = new View();
$view->renderAdminPage();
*/
require_once "app/models/Database.php";
require_once "app/models/ProductModel.php";
require_once "app/models/CustomerModel.php";
require_once "app/models/OrderModel.php";
require_once "app/views/View.php";
require_once "app/controllers/Controller.php";

$database = new Database("mancaveshop_db");
$product_model = new ProductModel($database);
$customer_model = new CustomerModel($database);
$order_model = new OrderModel($database);
$view = new View();

$routes = array(
    // Customer routes
    '' => 'index', // In case no /?page=...
    'products' => 'getProductsByCategory',
    'products/details' => 'getProductById',
    'shoppingcart' => 'getShoppingCart',
    'register' => 'customerRegister',
    // Admin routes
    'admin' => 'adminIndex',
    'admin/products' => 'adminIndex',
    'admin/products/index' => 'adminIndex',
    'admin/products/create' => 'adminProductCreate',
    'admin/products/update' => 'adminProductUpdate',
    'admin/products/delete' => 'adminProductDelete',
    'admin/orders' => 'adminOrderList',
    'admin/orders/' => 'adminOrderList',
);

$controller = new Controller($order_model, $product_model, $customer_model, $view, $routes);
