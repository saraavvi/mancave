<?php

/* require_once ‘./views/View.php’;
$view = new View();
$view->renderAdminPage();
*/
require_once "app/models/Database.php";
require_once "app/models/ProductModel.php";
require_once "app/views/View.php";
require_once "app/controllers/Controller.php";

$database = new Database("mancaveshop_db");
$product_model = new ProductModel($database);
$view = new View();

$routes = array(
    // Customer routes
    '' => 'index', // In case no /?page=...
    'products' => 'getProductsByCategory',
    // Admin routes
    'admin' => 'adminIndex',
    'admin/products' => 'adminIndex',
    'admin/products/index' => 'adminIndex',
    'admin/products/create' => 'adminProductCreate',
    'admin/products/update' => 'adminProductUpdate',
    'admin/products/delete' => 'adminProductDelete'
);

$controller = new Controller($product_model, $view, $routes);
