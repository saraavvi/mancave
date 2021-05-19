<?php

require_once "Controller.php";

class CustomerController extends Controller
{
    private $order_model;
    private $product_model;
    private $customer_model;
    private $customer_view;

    public function __construct($order_model, $product_model, $customer_model, $customer_view)
    {
        $this->order_model = $order_model;
        $this->product_model = $product_model;
        $this->customer_model = $customer_model;
        $this->customer_view = $customer_view;
    }

    // MAIN METHODS:

    public function index()
    {
        /* // Rendera random produkt på förstasidan:
        $products = $this->product_model->fetchAllProducts();
        shuffle($products);
        $product = $products[0];
        $this->view->renderCustomerIndexPage($product); */
        
        $this->customer_view->renderCustomerIndexPage();
    }

    public function register()
    {
        [$customer_data, $alerts] = $this->handleRegister();
        $this->customer_view->renderCustomerRegisterPage($alerts, $customer_data);
    }

    public function login()
    {
        $this->handleLogin();
    }

    public function logout()
    {
        $this->handleLogout();
    }

    /**
     * list all products from the chosen category.
     */
    public function getProductsByCategory()
    {
        // if any add button is klicked on category page: get id from $_POST and edit shopping_cart in session.
        if (isset($_POST["add_to_cart"])) {
            $id = $_POST["product_id"];
            $this->handleShoppingCartAdd($id);
        }
        $category = $this->sanitize($_GET["category"]);
        $products = $this->product_model->fetchProductsByCategory($category);
        $this->customer_view->renderProductPage($products);
    }

    /**
     * display details about a specific product.
     */
    public function getProductById()
    {
        $id = $this->sanitize($_GET['id']);
        // if add button is clicked on detail page edit shopping_cart in session.
        if (isset($_POST["add_to_cart"])) {
            $this->handleShoppingCartAdd($id);
        }

        $product = $this->product_model->fetchProductById($id);

        if (!$product) echo 'Product id does not exist.';
        else $this->customer_view->renderDetailPage($product);
    }

    /**
     * get all products using the id:s inside shopping_cart array in session, then send them to the customer_view.
     */
    public function getShoppingCart()
    {
        // print_r($_SESSION["shopping_cart"]);
        if (isset($_GET['action']) && $_GET['action'] === "delete") {
            $id = $_GET['id'];
            $this->handleShoppingCartDelete($id);
        }

        $ids = $_SESSION['shopping_cart'];
        $products = array();
        foreach ($ids as $key => $value) {
            $product = $this->product_model->fetchProductById($key);
            array_push($products, $product);
        }
        $this->customer_view->renderShoppingCartPage($products);
    }
    
    //CUSTOMER HELPER METHODS:

    private function handleRegister()
    {
        $customer_data = array();
        $alerts = array();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $customer_data = $this->handleCustomerPost();
                $customer_id = $this->customer_model->createCustomer($customer_data);
                $alerts['success'][] = "Customer successfully created! New customer id: $customer_id. Please Log In.";
                $this->customer_view->renderCustomerIndexPage($alerts);
                exit;
            } catch (Exception $error) {
                $error_message = json_decode($error->getMessage(), true);
                if ($error_message) $alerts['danger'] = $error_message;
            }
        }
        return [$customer_data, $alerts];
    }

    private function handleCustomerPost()
    {
        $errors = array();

        $first_name = $this->getAndValidatePost('first_name');
        $last_name = $this->getAndValidatePost('last_name');
        $email = $this->getAndValidatePost('email');
        $password = $this->getAndValidatePost('password');
        $password_confirm = $this->getAndValidatePost('password_confirm');
        if ($password !== $password_confirm) {
            array_push($errors, 'Passwords do not match.');
        }
        if (strlen($password) < 6) {
            array_push($errors, 'Password must be at least six characters.');
        }
        $address = $this->getAndValidatePost('address');
        if (empty($email) || empty($password) || empty($password_confirm)) {
            array_push($errors, 'Please fill in all required fields');
        }

        if (count($errors) === 0) {
            $customer_data = array();
            $customer_data['first_name'] = $first_name;
            $customer_data['last_name'] = $last_name;
            $customer_data['email'] = $email;
            //Hash password before save to db TODO:
            //$password = password_hash($password, PASSWORD_DEFAULT);
            $customer_data['password'] = $password;
            $customer_data['address'] = $address;
            return $customer_data;
        } else {
            throw new Exception(json_encode($errors));
        }
    }
  
    /**
     * method that edits the shopping cart in the session.
     * look if product id already exists. if it does - increase qty by 1, if not - add the item.
     */
    private function handleShoppingCartAdd($id)
    {
        if (!array_key_exists($id, $_SESSION["shopping_cart"])) {
            // om produkten ej finns i session - lägg till den
            $_SESSION["shopping_cart"][$id] = 1;
        } else {
            //annars öka qty med 1
            $_SESSION["shopping_cart"][$id]++;
        }
    }

    private function handleShoppingCartDelete($id)
    {
        unset($_SESSION["shopping_cart"][$id]);
    }

    /***
     * Handle new order placed by customer
     * take info from session and send to order_model
     * send success/error msg to customer_view
     */
    private function handleNewOrder()
    {
        $alerts = array();
        // $shopping_cart = $_SESSION['shopping_cart']; eller hur man nu får den
        // array_push($_SESSION['shopping_cart'], array(orderraden))
        // $customer_id = $_SESSION['customer_id'];
        $customer_id = 1;
        // Vi tänker att shopping_cart ser ut såhär:
        $shopping_cart = array(
            array(
                'product_id' => 1,
                'quantity' => 2,
                'price_each' => 30
            ),
            array(
                'product_id' => 2,
                'quantity' => 3,
                'price_each' => 40
            ),
        );
        try {
            $order_id = $this->order_model->createNewOrder($customer_id);//order_id (lastInsertId)
            foreach ($shopping_cart as $order_row) {
                $this->order_model->createNewOrderContent($order_id, $order_row);
            }
            $alerts['success'][] = 'Order successfully placed. Thank you come again:)))';
        } catch (Exception $e) {
            $alerts['danger'][] = 'Failed to place order, please try again later or contact our customer service.';
        }
    }

    public function handleLogin()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (empty($_POST["email"]) || empty($_POST["password"])) {
                $this->returnToIndexWithAlert("Please enter username and password.");
            }
            $customer = $this->customer_model->fetchCustomerByEmail($_POST["email"]);
            if (!$customer) {
                $this->returnToIndexWithAlert("Incorrect username/email.");
            }
            if ($_POST["password"] !== $customer["password"]) {
                $errors[] = "Incorrect password.";
            }
            if ($_POST["password"] === $customer["password"]) {
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
        $_SESSION["loggedinuser"] = null;
        $this->returnToIndexWithAlert("Successfully Logged Out!", "success");
    }

    private function returnToIndexWithAlert($message, $style = 'danger')
    {
        $alert[$style][] = $message;
        $this->customer_view->renderCustomerIndexPage($alert);
        exit;
    }

}