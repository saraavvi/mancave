<?php

class Controller
{
    private $model;
    private $view;
    private $routes;

    /**
     *  
     */
    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;
    }
    
    public function addRoutes($routes)
    {
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
        echo call_user_func($function);
    }

    private function index()
    {
        echo "This is index";
    }

}