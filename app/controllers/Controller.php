<?php

class Controller
{
    private $model;
    private $view;
    private $routes;

    /**
     *  
     */
    public function __construct($model, $view, $routes)
    {
        $this->model = $model;
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
        echo call_user_func([$this, $function]); // + $this ?
    }

    private function index($data)
    {
        echo "This is index";
    }

}