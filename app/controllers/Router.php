<?php

class Router
{
    private $customer_controller;
    private $admin_controller;
    private $routes;


    /***
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
        $url = $this->getArrayFromUrl();
        $page = $url[0] ?? "";
        $firstparam = $url[1] ?? "";
        $_SESSION['firstparam'] = $firstparam;
        /* $secondparam = $url[2] ?? "";
        $_SESSION['secondparam'] = $secondparam; */
        /* echo $page;
        echo $param;
        exit; */
        /* $page = $_GET["page"] ?? ""; */

        $function = $this->routes[$page] ?? null; // 'create'

        $this->conditionForExit(!$function);
        echo call_user_func($function);
    }

    // flytta ev till helper class
    private function conditionForExit($condition)
    {
        if ($condition) {
            echo "Page not found";
            exit();
        }
    }

    private function getArrayFromUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            //print_r($url);
            return $url;
        }
    }
}