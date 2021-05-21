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
    "index" => [$customer_controller, "handleIndex"],
    "register" => [$customer_controller, "handleRegister"],
    "login" => [$customer_controller, "handleLogin"],
    "logout" => [$customer_controller, "handleLogout"],
    "products" => [$customer_controller, "handleProductsByCategory"],
    "products/details" => [$customer_controller, "handleProductDetails"],
    "shoppingcart" => [$customer_controller, "handleShoppingCart"],
    "checkout" => [$customer_controller, "handleCheckout"],
    "checkout/process-order" => [$customer_controller, "handlePlaceOrder"],

    // Admin routes
    "admin/login" => [$admin_controller, "handleLogin"],
    "admin/logout" => [$admin_controller, "handleLogout"],
    "admin" => [$admin_controller, "handleIndex"],
    "admin/products" => [$admin_controller, "handleIndex"],
    "admin/products/index" => [$admin_controller, "handleIndex"],
    "admin/products/create" => [$admin_controller, "handleProductCreate"],
    "admin/products/update" => [$admin_controller, "handleProductUpdate"],
    "admin/products/delete" => [$admin_controller, "handleProductDelete"],
    "admin/orders" => [$admin_controller, "handleOrderList"],
    "admin/orders/delete" => [$admin_controller, "handleOrderDelete"],
];

$router = new Router($customer_controller, $admin_controller, $routes);
