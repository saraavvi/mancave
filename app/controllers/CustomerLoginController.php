<?php

class CustomerLoginController
{
    private $db;

    public function __construct($database, $view)
    {
        $this->db = $database;
        $this->view = $view;
    }

    //MAIN METHODS

    public function handleLogin()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (empty($_POST["email"]) || empty($_POST["password"])) {
                $this->returnToIndexWithAlert("Please enter username and password.");
            }
            $customer = $this->fetchCustomerByEmail($_POST["email"]);
            if (!$customer) {
                $this->returnToIndexWithAlert("Incorrect username/email.");
            }
            if ($_POST["password"] !== $customer["password"]) {
                $errors[] = "Incorrect password.";
            }
            if ($_POST["password"] === $customer["password"]) {
                session_start();
                $_SESSION["loggedinuser"] = $customer;
                $this->returnToIndexWithAlert("Successfully Logged In!", "success");
            }
            $this->returnToIndexWithAlert("Unexpected error!");
        }
        echo "Page not found";
        exit();
    }

    public function handleLogout()
    {
        session_start();
        session_unset();
        $this->returnToIndexWithAlert("Successfully Logged Out!", "success");
    }

    //HELPER METHODS

    private function returnToIndexWithAlert($message, $style = 'danger')
    {
        $alert[$style][] = $message;
        $this->view->renderCustomerIndexPage($alert);
        exit;
    }

    private function fetchCustomerByEmail($email)
    {
        $statement = "SELECT * FROM customers WHERE email = :email";
        $params = array(":email" => $email);
        $customer = $this->db->select($statement, $params);
        return $customer[0] ?? false;
    }
}

