<?php
session_start();
if (empty($_SESSION["shopping_cart"])) {
    $_SESSION["shopping_cart"] = [];
}

//Model
require_once "app/models/Database.php";
require_once "app/models/OrderModel.php";
require_once "app/models/ProductModel.php";
require_once "app/models/CustomerModel.php";
require_once "app/models/AdminModel.php";
//View
require_once "app/views/CustomerView.php";
require_once "app/views/AdminView.php";
//Controller
require_once "app/controllers/AdminController.php";
require_once "app/controllers/CustomerController.php";
require_once "app/controllers/Router.php";

//Model
$database = new Database("mancaveshop_db");
$order_model = new OrderModel($database);
$product_model = new ProductModel($database);
$customer_model = new CustomerModel($database);
$admin_model = new AdminModel($database);
//View
$customer_view = new CustomerView();
$admin_view = new AdminView();
//Controller
$customer_controller = new CustomerController(
    $order_model,
    $product_model,
    $customer_model,
    $customer_view
);
$admin_controller = new AdminController(
    $order_model,
    $product_model,
    $admin_model,
    $admin_view
);

$routes = [
    // Customer routes
    "" => [$customer_controller, "handleIndex"], // In case no /?page=...
    "register" => [$customer_controller, "handleRegister"],
    "login" => [$customer_controller, "handleLogin"],
    "logout" => [$customer_controller, "handleLogout"],
    "products" => [$customer_controller, "handleProducts"],
    "products/details" => [$customer_controller, "handleProductDetails"],
    "shoppingcart" => [$customer_controller, "getShoppingCart"],
    "checkout/process-order" => [$customer_controller, "orderConfirmation"],
    "checkout" => [$customer_controller, "getCheckout"],

    // Admin routes
    "admin/login" => [$admin_controller, "login"],
    "admin/logout" => [$admin_controller, "logout"],
    "admin" => [$admin_controller, "index"],
    "admin/products" => [$admin_controller, "index"],
    "admin/products/index" => [$admin_controller, "index"],
    "admin/products/create" => [$admin_controller, "productCreate"],
    "admin/products/update" => [$admin_controller, "productUpdate"],
    "admin/products/delete" => [$admin_controller, "productDelete"],
    "admin/orders/delete" => [$admin_controller, "orderDelete"],
    "admin/orders" => [$admin_controller, "orderList"],
];

$router = new Router($customer_controller, $admin_controller, $routes);
