<?php
session_start();
if (empty($_SESSION['shopping_cart'])) {
    $_SESSION['shopping_cart'] = array();
}

require_once "app/models/Database.php";
require_once "app/models/OrderModel.php";
require_once "app/models/ProductModel.php";
require_once "app/models/CustomerModel.php";
require_once "app/models/AdminModel.php";
require_once "app/controllers/AdminController.php";
require_once "app/controllers/CustomerController.php";
require_once "app/controllers/Router.php";

$database = new Database("mancaveshop_db");
$order_model = new OrderModel($database);
$product_model = new ProductModel($database);
$customer_model = new CustomerModel($database);
$admin_model = new AdminModel($database);

$customer_controller = new CustomerController($order_model, $product_model, $customer_model, $customer_view);
$admin_controller = new AdminController($order_model, $product_model, $admin_model, $admin_view);

$routes = array(
    // Customer routes
    '' => [$customer_controller, 'index'],
    'register' => [$customer_controller, 'register'],
    'login' => [$customer_controller, 'login'], // In case no /?page=...
    'logout' => [$customer_controller, 'logout'], // In case no /?page=...
    'products' => [$customer_controller, 'getProductsByCategory'],
    'products/details' => [$customer_controller, 'getProductById'],
    'shoppingcart' => [$customer_controller, 'getShoppingCart'],
    // Admin routes
    'admin' => [$admin_controller, 'index'],
    'admin/products' => [$admin_controller, 'index'],
    'admin/products/index' => [$admin_controller, 'index'],
    'admin/products/create' => [$admin_controller, 'productCreate'],
    'admin/products/update' => [$admin_controller, 'productUpdate'],
    'admin/products/delete' => [$admin_controller, 'productDelete'],
    'admin/orders/delete' => [$admin_controller, 'orderDelete'],
    'admin/orders' => [$admin_controller, 'orderList'],
);

// $customer_login_controller = new CustomerLoginController($database, $view);
// $controller = new Controller($order_model, $product_model, $customer_model);

$router = new Router($customer_controller, $admin_controller, $routes);
/* Routern måste vara boss över två Controllers.
new AdminController($controller_helper) och CustomerController.


Dom två controllers har relevanta models injicerade.
ControllerHelper klass där gemensamma resurser deklareras.


new Router($admin_controller, $customer_controller) 

1. initiera objekt av alla resurser som används
2. initiera objekt av helperController som har gemensamma resurser
3. initiera objekt av de två controllerna som är injecerade med helperController.
4. initiera objekt av router som har de två åvan injicerade*/