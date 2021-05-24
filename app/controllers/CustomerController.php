<?php

require_once "Controller.php";

// INHERITED METHODS:
// conditionForExit()
// getAndValidatePost()
// sanitize()
// setAlert()
// goToPageWithAlert()
// validateLoginForm()
// logOutAndGoToPage()

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
        $brands = $this->getBrands();
        $this->customer_view->renderIndexPage($brands);
    }

    public function handleAbout()
    {
        $brands = $this->getBrands();
        $this->customer_view->renderAboutPage($brands);
    }

    public function handleRegister()
    {
        $brands = $this->getBrands();
        $customer_data = $this->processRegisterForm();
        $this->customer_view->renderRegisterPage($customer_data, $brands);
    }

    public function handleLogin()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $customer = $this->customer_model->fetchCustomerByEmail(
                $_POST["email"]
            );
            $this->validateLoginForm(
                $customer,
                "customer",
                $_POST["current_page"]
            );
        }
        echo "Page not found";
        exit();
    }

    public function handleLogout()
    {
        $this->logOutAndGoToPage();
    }

    public function handleProducts() {
        $this->initializeShoppingCartAdd();
        $brands = $this->getBrands();
        if (isset($_GET['category'])) {
            [$products, $title] = $this->getProductsByCategory();
            $this->customer_view->renderProductPage($products, $title, $brands);
        } else if (isset($_GET['brand'])) {
            [$products, $title] = $this->getProductsByBrand();
            $this->customer_view->renderProductPage($products, $title, $brands);
        } else {
            header('Location: ?page=index');
        }
    }

    /**
     * display details about a specific product.
     */
    public function handleProductDetails()
    {
        $brands = $this->getBrands();
        $this->initializeShoppingCartAdd();
        $id = $this->sanitize($_GET["id"]);
        $product = $this->product_model->fetchProductById($id);
        $brand = $this->product_model->fetchBrandById($product["brand_id"]);
        if (!$product) {
            $this->goToPageWithAlert("Product id does not exist.");
        }
        $this->customer_view->renderDetailPage($product, $brand, $brands);
    }

    /**
     * get all products using the id:s inside shopping_cart array in session, then send them to the customer_view.
     */
    public function handleShoppingCart()
    {
        $brands = $this->getBrands();
        $this->initializeShoppingCartQtyUpdate();
        $this->initializeShoppingCartDelete();
        [$products, $customer] = $this->getShoppingCartDetailsAndCustomer();
        $this->customer_view->renderShoppingCartPage($products, $customer, $brands);
    }

    public function handleCheckout()
    {
        $brands = $this->getBrands();
        [
            $products,
            $customer,
            $total_price,
        ] = $this->getShoppingCartDetailsAndCustomer();
        $this->customer_view->renderCheckoutPage(
            $products,
            $customer,
            $total_price,
            $brands
        );
    }

    public function handlePlaceOrder()
    {
        $brands = $this->getBrands();
        [$customer, $order_id] = $this->processNewOrder();
        if ($order_id) {
            $this->customer_view->renderOrderConfirmationPage(
                $customer,
                $order_id,
                $brands
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
                //Log in new customer
                $customer_data["password"] = null;
                $customer_data["id"] = $customer_id;
                $_SESSION['customer'] = $customer_data;
                $this->goToPageWithAlert(
                    "Customer successfully created! New customer id: $customer_id.",
                    "page=index",
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
            $this->goToPageWithAlert(
                "$name added to cart",
                $_POST["current_page"],
                "info"
            );
        }
    }

    private function initializeShoppingCartQtyUpdate()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $this->getAndValidatePost("id", true);
            $qty = $this->getAndValidatePost("qty", true);
            if ($id && $qty) {
                $_SESSION["shopping_cart"][$id] = $qty;
            }
            $this->setAlert("success", "Shopping cart updated");
        }
    }

    private function initializeShoppingCartDelete()
    {
        if (isset($_GET["action"]) && $_GET["action"] === "delete") {
            $id = $this->sanitize($_GET["id"]);
            unset($_SESSION["shopping_cart"][$id]);
            $this->setAlert("success", "Removed from shopping cart");
        }
    }

    private function getShoppingCartDetailsAndCustomer()
    {
        $customer = $_SESSION["customer"] ?? false;
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
    private function processNewOrder()
    {
        $customer = $_SESSION["customer"];
        $shopping_cart = $_SESSION["shopping_cart"];

        try {
            // check each products stock against shopping cart quantity
            $available_products = [];
            foreach ($shopping_cart as $product_id => $qty) {
                $product = $this->product_model->fetchProductById($product_id);
                if ($product["stock"] >= $qty) {
                    array_push($available_products, $product);
                } else {
                    $this->setAlert(
                        "danger",
                        "Failed to place order, you tried to order $qty $product[name] but unfortunately we've got $product[stock] :("
                    );
                }
            }

            // only execute order contents if all products are available
            if (count($available_products) === count($shopping_cart)) {
                $order_id = $this->order_model->createNewOrder($customer["id"]);

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
                exit();
            }
        } catch (Exception $e) {
            $this->setAlert(
                "danger",
                "Failed to place order, please try again later or contact our customer service."
            );
            return false;
        }
    }

    /**
     * list all products from the chosen category.
     */
    private function getProductsByCategory()
    {
        $category = (int)$this->sanitize($_GET["category"]);
        $products = [];
        $title = "Category";
        if ($category) {
            $products = $this->product_model->fetchProductsByCategory($category);
            if (!$products) {
                $this->setAlert("warning", "No products found in this category.");
            } else {
                $title = $products[0]['category_name'];
            }
        } else {
            $this->setAlert("warning", "Category id does not exist.");
        }
        return [$products, $title];
    }

    /**
     * get all products from the chosen brand.
     */
    private function getProductsByBrand()
    {
        $brand = (int)$this->sanitize($_GET["brand"]);
        $products = [];
        $title = "Brand";
        if ($brand) {
            $products = $this->product_model->fetchProductsByBrand($brand);
            if (!$products) {
                $this->setAlert("warning", "No products found by this brand.");
            } else {
                $title = $products[0]['brand_name'];
            }
        } else {
            $this->setAlert("warning", "Brand id does not exist.");
        }
        return [$products, $title];
    }

    private function getBrands() {
        $brands = $this->product_model->fetchAllBrands();
        return $brands;
    }
}
