<?php

require_once "Controller.php";

class CustomerController extends Controller
{
    private $order_model;
    private $product_model;
    private $customer_model;
    private $customer_view;

    public function __construct(
        $order_model,
        $product_model,
        $customer_model,
        $customer_view
    ) {
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

        $this->customer_view->renderIndexPage();
    }

    public function register()
    {
        $customer_data = $this->handleRegister();
        $this->customer_view->renderRegisterPage($customer_data);
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
        $id = $this->sanitize($_GET["id"]);
        // if add button is clicked on detail page edit shopping_cart in session.
        if (isset($_POST["add_to_cart"])) {
            $this->handleShoppingCartAdd($id);
        }

        $product = $this->product_model->fetchProductById($id);

        if (!$product) {
            echo "Product id does not exist.";
        } else {
            $this->customer_view->renderDetailPage($product);
        }
    }

    /**
     * get all products using the id:s inside shopping_cart array in session, then send them to the customer_view.
     */
    public function getShoppingCart()
    {
        print_r($_SESSION["shopping_cart"]);
        if (isset($_GET["action"]) && $_GET["action"] === "delete") {
            $id = $_GET["id"];
            $this->handleShoppingCartDelete($id);
        }

        $ids = $_SESSION["shopping_cart"];
        $products = [];
        foreach ($ids as $key => $value) {
            $product = $this->product_model->fetchProductById($key);
            array_push($products, $product);
        }
        $this->customer_view->renderShoppingCartPage($products);
    }

    public function getCheckout()
    {
        print_r($_SESSION["shopping_cart"]);
        $ids = $_SESSION["shopping_cart"];
        $total = 0;
        $products = [];
        foreach ($ids as $key => $qty) {
            $product = $this->product_model->fetchProductById($key);
            array_push($products, $product);
            $total += $product["price"] * $qty;
        }
        $this->customer_view->renderCheckoutPage($products, $total);
    }

    /***
     * Handle new order placed by customer
     * take info from session and send to order_model
     * send success/error msg to customer_view
     */
    public function handleNewOrder()
    {
        $customer_id = $_SESSION["loggedinuser"]["id"];
        $shopping_cart = $_SESSION["shopping_cart"];

        try {
            $order_id = $this->order_model->createNewOrder($customer_id); //order_id (lastInsertId)
            foreach ($shopping_cart as $product_id => $qty) {
                $product = $this->product_model->fetchProductById($product_id);
                $current_price = $product['price'];
                $this->order_model->createNewOrderContent(
                    $order_id,
                    $product_id,
                    $qty,
                    $current_price
                );
            }
            $this->setAlert("success", "Order successfully placed. Thank you come again:)))");
        } catch (Exception $e) {
            $this->setAlert("danger", "Failed to place order, please try again later or contact our customer service.");
        }
        // Skicka vidare till Anton.io's confirm order sida
    }

    // HELPER METHODS:

    private function handleRegister()
    {
        $customer_data = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $customer_data = $this->handleRegisterPost();
                $customer_id = $this->customer_model->createCustomer(
                    $customer_data
                );
                $this->returnToIndexWithAlert(
                    "Customer successfully created! New customer id: $customer_id. Please Log In.",
                    "success"
                );
            } catch (Exception $error) {
                $errors_array = json_decode($error->getMessage(), true);
                if ($errors_array) {
                    foreach ($errors_array as $message) {
                        $this->setAlert("danger", $message);
                    }
                }
            }
        }
        return $customer_data;
    }

    private function handleRegisterPost()
    {
        $errors = [];

        $first_name = $this->getAndValidatePost("first_name");
        $last_name = $this->getAndValidatePost("last_name");
        $email = $this->getAndValidatePost("email");
        $customer = $this->customer_model->fetchCustomerByEmail($email);
        if ($customer) {
            array_push(
                $errors,
                "A user with this email is already registered."
            );
        }
        $password = $this->getAndValidatePost("password");
        $password_confirm = $this->getAndValidatePost("password_confirm");
        if ($password !== $password_confirm) {
            array_push($errors, "Passwords do not match.");
        }
        if (strlen($password) < 6) {
            array_push($errors, "Password must be at least six characters.");
        }
        $address = $this->getAndValidatePost("address");
        if (empty($email) || empty($password) || empty($password_confirm)) {
            array_push($errors, "Please fill in all required fields");
        }

        if (count($errors) === 0) {
            $customer_data = [];
            $customer_data["first_name"] = $first_name;
            $customer_data["last_name"] = $last_name;
            $customer_data["email"] = $email;
            $password = password_hash($password, PASSWORD_DEFAULT);
            $customer_data["password"] = $password;
            $customer_data["address"] = $address;
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

    public function handleLogin()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (empty($_POST["email"]) || empty($_POST["password"])) {
                $this->returnToIndexWithAlert(
                    "Please enter username and password."
                );
            }
            $customer = $this->customer_model->fetchCustomerByEmail(
                $_POST["email"]
            );
            if (!$customer) {
                $this->returnToIndexWithAlert("Incorrect username/email.");
            }
            $hashed_password = $customer["password"];
            $entered_password = $_POST["password"];
            if (!password_verify($entered_password, $hashed_password)) {
                $this->returnToIndexWithAlert("Incorrect password.");
            } else {
                $_SESSION["loggedinuser"] = $customer;
                $this->returnToIndexWithAlert(
                    "Successfully Logged In!",
                    "success"
                );
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

    private function returnToIndexWithAlert($message, $style = "danger")
    {
        $this->setAlert($style, $message);
        $this->customer_view->renderIndexPage();
        exit();
    }
}
