<?php
session_start();
if (empty($_SESSION['shopping_cart'])) {
    $_SESSION['shopping_cart'] = array();
}

require_once "app/models/Database.php";
require_once "app/models/ProductModel.php";
require_once "app/models/CustomerModel.php";
require_once "app/models/OrderModel.php";
require_once "app/views/View.php";
require_once "app/controllers/Controller.php";
require_once "app/controllers/CustomerLoginController.php";

require_once "app/controllers/Router.php";
require_once "app/controllers/CustomerController.php";

$database = new Database("mancaveshop_db");
$product_model = new ProductModel($database);
$customer_model = new CustomerModel($database);
$order_model = new OrderModel($database);
$view = new View();

$customer_controller = new CustomerController($order_model, $product_model, $customer_model, $customer_view);
// $admin_controller = new AdminController($order_model, $product_model, $customer_model, $admin_view);

$routes = array(
    // Customer routes
    '' => [$customer_controller, 'index'],
    'login' => [$customer_controller, 'login'], // In case no /?page=...
    'logout' => [$customer_controller, 'logout'], // In case no /?page=...
    'products' => [$customer_controller, 'getProductsByCategory'],
    'products/details' => [$customer_controller, 'getProductById'],
    'shoppingcart' => [$customer_controller, 'getShoppingCart'],
    'register' => [$customer_controller, 'customerRegister'],
    // Admin routes
    'admin' => 'adminIndex',
    'admin/products' => 'adminIndex',
    'admin/products/index' => 'adminIndex',
    'admin/products/create' => 'adminProductCreate',
    'admin/products/update' => 'adminProductUpdate',
    'admin/products/delete' => 'adminProductDelete',
    'admin/orders/delete' => 'adminOrderDelete',
    'admin/orders' => 'adminOrderList',
);

// $customer_login_controller = new CustomerLoginController($database, $view);
// $controller = new Controller($order_model, $product_model, $customer_model);

$router = new Router($customer_controller/* , $admin_controller */, $routes);
/* Routern måste vara boss över två Controllers.
new AdminController($controller_helper) och CustomerController.


Dom två controllers har relevanta models injicerade.
ControllerHelper klass där gemensamma resurser deklareras.


new Router($admin_controller, $customer_controller) 

1. initiera objekt av alla resurser som används
2. initiera objekt av helperController som har gemensamma resurser
3. initiera objekt av de två controllerna som är injecerade med helperController.
4. initiera objekt av router som har de två åvan injicerade*/