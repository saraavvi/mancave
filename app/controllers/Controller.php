<?php

class Controller
{
    private $customer_login_controller;
    private $product_model;
    private $order_model;
    private $customer_model;
    private $view;
    private $routes;

    /**
     *
     */
    public function __construct($customer_login_controller, $order_model, $product_model, $customer_model, $view, $routes)
    {
        $this->customer_login_controller = $customer_login_controller;
        $this->product_model = $product_model;
        $this->order_model = $order_model;
        $this->customer_model = $customer_model;
        $this->view = $view;
        $this->routes = $routes;
        $this->resolveRoute();
    }

    //CONTENT:
    //ROUTER METHODS:
    //COMMON MAIN METHODS:
    //COMMON HELPER METHODS:
    //CUSTOMER MAIN METHODS:
    //CUSTOMER HELPER METHODS:
    //ADMIN MAIN METHODS:
    //ADMIN HELPER METHODS:
    
    
    //ROUTER METHODS:
    private function resolveRoute()
    {
        $page = $_GET["page"] ?? "";

        $function = $this->routes[$page] ?? null; // 'create'

        $this->conditionForExit(!$function);
        echo call_user_func([$this, $function]);
    }
    //COMMON MAIN METHODS:

    //COMMON HELPER METHODS:

    private function conditionForExit($condition)
    {
        if ($condition) {
            echo "Page not found";
            exit();
        }
    }

    /**
     * Expects name of post key, 
     * optional bool (true for int values, default false) 
     * returns value or false
     */
    private function getAndValidatePost($name, $int = false)
    {
        if (isset($_POST[$name])) {
            $value = $this->sanitize($_POST[$name]);
            if ($int) return (int)$value;
            return $value;
        }
        return false;
    }

    private function sanitize($text)
    {
        $text = trim($text);
        $text = stripslashes($text);
        $text = htmlspecialchars($text);
        return $text;
    }

    //CUSTOMER MAIN METHODS:

    private function index()
    {
        /* // Rendera random produkt på förstasidan:
        $products = $this->product_model->fetchAllProducts();
        shuffle($products);
        $product = $products[0];
        $this->view->renderCustomerIndexPage($product); */
        
        $this->view->renderCustomerIndexPage();
    }

    private function login()
    {
        $this->customer_login_controller->handleLogin();
    }

    private function logout()
    {
        $this->customer_login_controller->handleLogout();
    }
    /**
     * list all products from the chosen category.
     */
    private function getProductsByCategory()
    {
        // if any add button is klicked on category page: get id from $_POST and edit shopping_cart in session.
        if (isset($_POST["add_to_cart"])) {
            $id = $_POST["product_id"];
            $this->handleShoppingCartAdd($id);
        }
        $category = $this->sanitize($_GET["category"]);
        $products = $this->product_model->fetchProductsByCategory($category);
        $this->view->renderProductPage($products);
    }

    /**
     * display details about a specific product.
     */
    private function getProductById()
    {
        $id = $this->sanitize($_GET['id']);
        // if add button is clicked on detail page edit shopping_cart in session.
        if (isset($_POST["add_to_cart"])) {
            $this->handleShoppingCartAdd($id);
        }

        $product = $this->product_model->fetchProductById($id);

        if (!$product) echo 'Product id does not exist.';
        else $this->view->renderDetailPage($product);
    }
  
  /**
     * get all products using the id:s inside shopping_cart array in session, then send them to the view.
     */
    private function getShoppingCart()
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
        $this->view->renderShoppingCartPage($products);
    }

    private function customerRegister()
    {
        $customer_data = array();
        $alerts = array();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $customer_data = $this->handleCustomerPost();
                $customer_id = $this->customer_model->createCustomer($customer_data);
                $alerts['success'][] = "Customer successfully created! New customer id: $customer_id. Please Log In.";
                $this->view->renderCustomerIndexPage($alerts);
                exit;
            } catch (Exception $error) {
                $error_message = json_decode($error->getMessage(), true);
                if ($error_message) $alerts['danger'] = $error_message;
            }
        }
        $this->view->renderCustomerRegisterPage($alerts, $customer_data);
    }
    
    //CUSTOMER HELPER METHODS:

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
     * send success/error msg to view
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

    //ADMIN MAIN METHODS:


    private function adminIndex()
    {
        $alerts = array();
        $products = $this->product_model->fetchAllProducts();

        if (empty($products)) {
            $alerts['warning'][] = "No products to show.";
        }
        
        $this->view->renderAdminIndexPage($products, $alerts);
    }

    private function adminProductCreate()
    {
        $product_data = array();
        $alerts = array();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $product_data = $this->handleProductPost();
                $this->product_model->createProduct($product_data);
                header("Location: ?page=admin/products");
                exit();
            } catch (Exception $error) {
                $error_message = json_decode($error->getMessage(), true);
                $alerts['danger'] = $error_message;
            }
        }

        $brands = $this->product_model->fetchAllBrands();
        $categories = $this->product_model->fetchAllCategories();
        $this->view->renderAdminProductCreatePage($brands, $categories, $alerts);
    }


    private function adminProductUpdate()
    {
        $this->conditionForExit(empty($_GET['id']));

        $id = (int)$this->sanitize($_GET['id']);
        $product_data = array();
        $alerts = array();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $product_data = $this->handleProductPost();
                $this->product_model->updateProductById($id, $product_data);
                header('Location: ?page=admin/products');
                exit;
            } catch (Exception $error) {
                $error_message = json_decode($error->getMessage(), true);
                $alerts['danger'] = $error_message;
            }
        }

        $brands = $this->product_model->fetchAllBrands();
        $categories = $this->product_model->fetchAllCategories();
        $product_data = $this->product_model->fetchProductById($id);
        //TODO: Better error handling
        if (!$product_data) echo 'Product id does not exist.';
        else $this->view->renderAdminProductUpdatePage($brands, $categories, $product_data, $alerts);
    }

    public function adminProductDelete() {
        if ($_GET['action'] === "delete")
        $product_id = (int)$_GET['id'];
        $row_count = $this->product_model->deleteProductById($product_id);
        return $row_count;
    }

    private function adminOrderList($alerts = array())
    {
        $alerts = $alerts;
        if (isset($_GET['status_id'])) {
            try {
                $row_count = $this->handleOrderStatusUpdate();
                if ($row_count > 0) {
                    $alerts['success'][] = "Status was updated for $row_count order(s).";
                } else {
                    $alerts['warning'][] = "Unable to update to this status for this order.";
                }
            } catch (Exception $error) {
                $alerts['danger'][] = "Unable to update status.";
            }
        }
        
        //TODO: create order functionality
        //$statuses = $this->order_model->fetchAllStatuses(); //värt?
        $orders = $this->order_model->fetchAllOrders();
        if (empty($orders)) {
            $alerts['warning'][] = "No orders to show.";
        }
        $this->view->renderAdminOrderListPage($orders, $alerts);
    }

    //ADMIN HELPER METHODS:

    private function handleProductPost()
    {
        $errors = array();

        $name = $this->getAndValidatePost('name');
        $price = $this->getAndValidatePost('price', true);
        $description = $this->getAndValidatePost('description');
        $category_id = $this->getAndValidatePost('category_id', true);
        $stock = $this->getAndValidatePost('stock', true);
        $image = $this->getAndValidatePost('image');
        $specification = $this->getAndValidatePost('specification');

        $chosen_brand = $this->getAndValidatePost('brand_id', true);
        $new_brand_chosen = $this->getAndValidatePost('brand_id') === 'NEW';
        $new_brand = $this->getAndValidatePost('new_brand');

        if ((!$new_brand_chosen && $new_brand) || ($new_brand_chosen && !$new_brand) || (!$chosen_brand && !$new_brand)) {
            array_push($errors, "To add a new brand, please pick option 'Add New Brand' and enter a brand name below.");
        } else if ($new_brand_chosen && $new_brand) {
            $product_data['brand_id'] = $this->product_model->createBrand($new_brand);
        } else {
            $product_data['brand_id'] = $chosen_brand;
        }

        if ($name && $price && $category_id) {
            $product_data['name'] = $name;
            $product_data['price'] = $price;
            $product_data['description'] = $description;
            $product_data['category_id'] = $category_id;
            $product_data['stock'] = $stock;
            $product_data['image'] = $image;
            $product_data['specification'] = $specification;
        } else {
            array_push($errors, 'Please fill in all required fields');
        }

        if (count($errors) === 0) {
            return $product_data;
        } else {
            throw new Exception(json_encode($errors));
        }
    }

    public function handleOrderStatusUpdate() 
    {
        $order_id = (int)$this->sanitize($_GET['id']);
        $status_id = (int)$this->sanitize($_GET['status_id']);
        if ($order_id && $status_id) {
            if ($status_id === 2) {
                $this->order_model->updateOrderShippedDate($order_id);
            }
            $row_count = $this->order_model->updateOrderStatus($order_id, $status_id);
            return $row_count;
        }
        return false;
    }

    public function adminOrderDelete() 
    {
        $alerts = array();
        try {
            $order_id = (int)$_GET['id'];
            if ($order_id) {
                $row_count = $this->order_model->deleteOrder($order_id);
                if ($row_count > 0) {
                    $alerts['success'][] = "Successfully deleted $row_count order(s).";
                } else {
                    $alerts['warning'][] = " Order with id #$order_id could not be found.";
                }
            }
            $this->adminOrderList($alerts);
        } catch (Exception $error) {
            $alerts['danger'][] = "This order can not be deleted.";
            $this->adminOrderList($alerts);
        }
    }
}
