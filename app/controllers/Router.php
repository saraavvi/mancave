<?php

class Router
{
    private $customer_controller;
    private $admin_controller;
    private $routes;


    /**
     *
     */
    public function __construct($customer_controller, $admin_controller, $routes)
    {
        $this->customer_controller = $customer_controller;
        $this->admin_controller = $admin_controller;
        $this->routes = $routes;
        $this->resolveRoute();
    }

    //ROUTER METHODS:
    private function resolveRoute()
    {
        $page = $_GET["page"] ?? "";

        $function = $this->routes[$page] ?? null;

        $this->conditionForExit(!$function);
        call_user_func($function);
    }

    // flytta ev till helper class
    private function conditionForExit($condition)
    {
        if ($condition) {
            echo "Page not found";
            exit();
        }
    }
}