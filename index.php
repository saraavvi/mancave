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



$controller = new Controller($product_model, $view);


$routes = array(
    '' => 'index', // behövs den?
    'products' => [$controller, 'index'],
    'products/index' => [$controller, 'index'],
    'products/create' => [$controller, 'create'],
    'products/update' => [$controller, 'update'],
    'products/delete' => [$controller, 'delete']
);

$controller->addRoutes($routes);
