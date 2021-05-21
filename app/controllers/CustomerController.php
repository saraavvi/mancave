<?php

require_once "Controller.php";

// INHERITED METHODS:
// conditionForExit()
// getAndValidatePost()
// sanitize()
// setAlert()

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

    public function handleIndex()
    {
        /* // Rendera random produkt på förstasidan:
        $products = $this->product_model->fetchAllProducts();
        shuffle($products);
        $product = $products[0];
        $this->view->renderCustomerIndexPage($product); */
        $this->customer_view->renderIndexPage();
    }

    public function handleRegister()
    {
        $customer_data = $this->processRegisterForm();
        $this->customer_view->renderRegisterPage($customer_data);
    }

    public function handleLogin()
    {
        $this->validateLoginForm();
    }

    public function handleLogout()
    {
        $this->logOutCustomer();
    }

    /**
     * list all products from the chosen category.
     */
    public function handleProductsByCategory()
    {
        $this->initializeShoppingCartAdd();
        $category = $this->sanitize($_GET["category"]);
        $products = $this->product_model->fetchProductsByCategory($category);
        if (!$products) {
            $this->rerenderPageWithAlert("Category id does not exist.");
        }
        $this->customer_view->renderProductPage($products);
    }

    /**
     * display details about a specific product.
     */
    public function handleProductDetails()
    {
        $this->initializeShoppingCartAdd();
        $id = $this->sanitize($_GET["id"]);
        $product = $this->product_model->fetchProductById($id);
        if (!$product) {
            $this->rerenderPageWithAlert("Product id does not exist.");
        }
        $this->customer_view->renderDetailPage($product);
    }

    /**
     * get all products using the id:s inside shopping_cart array in session, then send them to the customer_view.
     */
    public function handleShoppingCart()
    {
        // update quantity:
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->handleShoppingCartQtyUpdate();
        }
        $this->initializeShoppingCartDelete();
        [$products, $customer] = $this->getShoppingCartDetailsAndCustomer();
        $this->customer_view->renderShoppingCartPage($products, $customer);
    }

    public function handleCheckout()
    {
        [
            $products,
            $customer,
            $total_price,
        ] = $this->getShoppingCartDetailsAndCustomer();
        $this->customer_view->renderCheckoutPage(
            $products,
            $total_price,
            $customer
        );
    }

    public function handlePlaceOrder()
    {
        [$customer, $order_id] = $this->processNewOrder();
        if ($order_id) {
            $this->customer_view->renderOrderConfirmationPage(
                $customer,
                $order_id
            );
        } else {
            header("Location: ?page=checkout");
        }
    }

    // HELPER METHODS:

    private function processRegisterForm()
    {
        $customer_data = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $customer_data = $this->validateRegisterForm();
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

    private function validateRegisterForm()
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

    public function validateLoginForm()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (empty($_POST["email"]) || empty($_POST["password"])) {
                $this->rerenderPageWithAlert(
                    "Please enter username and password."
                );
            }
            $customer = $this->customer_model->fetchCustomerByEmail(
                $_POST["email"]
            );
            if (!$customer) {
                $this->rerenderPageWithAlert("Incorrect username/email.");
            }
            $hashed_password = $customer["password"];
            $entered_password = $_POST["password"];
            if (!password_verify($entered_password, $hashed_password)) {
                $this->rerenderPageWithAlert("Incorrect password.");
            } else {
                //To prevent storing the password in session storage
                $customer["password"] = null;
                $_SESSION["loggedinuser"] = $customer;
                $this->rerenderPageWithAlert(
                    "Successfully Logged In!",
                    "success"
                );
            }
            $this->rerenderPageWithAlert("Unexpected error!");
        }
        echo "Page not found";
        exit();
    }

    private function logOutCustomer()
    {
        $_SESSION["loggedinuser"] = null;
        $this->returnToIndexWithAlert("Successfully Logged Out!", "success");
    }

    private function initializeShoppingCartAdd()
    {
        // If add button is klicked, get info from $_POST and add to cart in session.
        if (isset($_POST["add_to_cart"])) {
            $id = $_POST["product_id"];
            $name = $_POST["product_name"];
            //Add product or increase by one.
            if (!array_key_exists($id, $_SESSION["shopping_cart"])) {
                $_SESSION["shopping_cart"][$id] = 1;
            } else {
                $_SESSION["shopping_cart"][$id]++;
            }
            $this->rerenderPageWithAlert("$name added to cart", "info");
        }
    }

    private function initializeShoppingCartDelete()
    {
        if (isset($_GET["action"]) && $_GET["action"] === "delete") {
            $id = $_GET["id"];
            unset($_SESSION["shopping_cart"][$id]);
            $this->setAlert("success", "Removed from shopping cart");
        }
    }

    private function handleShoppingCartQtyUpdate()
    {
        $id = $this->getAndValidatePost('id', true);
        $qty = $this->getAndValidatePost('qty', true);
        if ($id && $qty) {
            $_SESSION["shopping_cart"][$id] = $qty;
        }
        $this->setAlert("success", "Shopping cart updated");
    }

    private function getShoppingCartDetailsAndCustomer()
    {
        $customer = $_SESSION["loggedinuser"] ?? false;
        $shopping_cart = $_SESSION["shopping_cart"];
        $total_price = 0;
        $products = [];
        foreach ($shopping_cart as $product_id => $qty) {
            $product = $this->product_model->fetchProductById($product_id);
            array_push($products, $product);
            $total_price += $product["price"] * $qty;
        }
        return [$products, $customer, $total_price];
    }

    /***
     * Handle new order placed by customer
     * take info from session and send to order_model
     * send success/error msg to customer_view
     */
    public function processNewOrder()
    {
        $customer = $_SESSION["loggedinuser"];
        $shopping_cart = $_SESSION["shopping_cart"];

        try {
            $order_id = $this->order_model->createNewOrder($customer["id"]); //order_id (lastInsertId)

            // check each products stock against shopping cart quantity
            $available_products = [];
            foreach ($shopping_cart as $product_id => $qty) {
                $product = $this->product_model->fetchProductById($product_id);
                if ($product['stock'] >= $qty) {
                    array_push($available_products, $product);
                } else {
                    $this->setAlert(
                        "danger",
                        "Failed to place order, you tried to order $qty $product[name] but unfortunately we've only got $product[stock] :("
                    );
                }
            }

            // only execute order contents if all products are available
            if (count($available_products) === count($shopping_cart)) {
                foreach ($available_products as $product) {
                    try {
                        $current_price = $product["price"];
                        $this->order_model->createNewOrderContent(
                            $order_id,
                            $product_id,
                            $qty,
                            $current_price
                        );
                    } catch (Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                    $this->product_model->reduceProductStock($product_id, $qty);
                }
                unset($_SESSION["shopping_cart"]);
                return [$customer, $order_id];
            } else {
                // send back to shopping cart with alert(s).
                header("Location: ?page=shoppingcart");
                exit;
            }
        } catch (Exception $e) {
            $this->setAlert(
                "danger",
                "Failed to place order, please try again later or contact our customer service."
            );
            return false;
        }
    }

    private function rerenderPageWithAlert($message, $style = "danger")
    {
        $this->setAlert($style, $message);
        $current_page = $_POST["current_page"] ?? "";
        header("Location: ?$current_page");
        exit();
    }
    private function returnToIndexWithAlert($message, $style = "danger")
    {
        $this->setAlert($style, $message);
        $this->customer_view->renderIndexPage();
        exit();
    }
}
