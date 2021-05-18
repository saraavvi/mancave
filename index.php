<?php

require_once "app/models/Database.php";
require_once "app/models/ProductModel.php";
require_once "app/models/CustomerModel.php";
require_once "app/models/OrderModel.php";
require_once "app/views/View.php";
require_once "app/controllers/Controller.php";
require_once "app/controllers/CustomerLoginController.php";

$database = new Database("mancaveshop_db");
$product_model = new ProductModel($database);
$customer_model = new CustomerModel($database);
$order_model = new OrderModel($database);
$view = new View();

$routes = array(
    // Customer routes
    '' => 'index',
    'login' => 'login', // In case no /?page=...
    'logout' => 'logout', // In case no /?page=...
    'products' => 'getProductsByCategory',
    'products/details' => 'getProductById',
    'register' => 'customerRegister',
    // Admin routes
    'admin' => 'adminIndex',
    'admin/products' => 'adminIndex',
    'admin/products/index' => 'adminIndex',
    'admin/products/create' => 'adminProductCreate',
    'admin/products/update' => 'adminProductUpdate',
    'admin/products/delete' => 'adminProductDelete',
    'admin/orders' => 'adminOrderList',
);

$customer_login_controller = new CustomerLoginController($database, $view);
$controller = new Controller($customer_login_controller, $order_model, $product_model, $customer_model, $view, $routes);
