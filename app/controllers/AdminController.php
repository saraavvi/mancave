<?php

require_once "Controller.php";

// INHERITED METHODS:
// conditionForExit()
// getAndValidatePost()
// sanitize()
// setAlert()

class AdminController extends Controller
{
    private $order_model;
    private $product_model;
    private $admin_model;
    private $admin_view;

    public function __construct(
        $order_model,
        $product_model,
        $admin_model,
        $admin_view
    ) {
        $this->order_model = $order_model;
        $this->product_model = $product_model;
        $this->admin_model = $admin_model;
        $this->admin_view = $admin_view;
    }

    // MAIN METHODS:

    public function handleLogin()
    {
        $this->validateLoginForm();
    }

    public function handleLogout()
    {
        $this->logOutAdmin();
    }

    public function handleIndex()
    {
        $this->ensureAuthenticated();
        $this->initializeAddToStock();
        $products = $this->product_model->fetchAllProducts();
        if (empty($products)) {
            $this->setAlert("info", "No products to show.");
        }
        $this->admin_view->renderIndexPage($products);
    }

    public function handleProductCreate()
    {
        $this->ensureAuthenticated();
        $this->handleProductCreatePostRequest();
        $brands = $this->product_model->fetchAllBrands();
        $categories = $this->product_model->fetchAllCategories();
        $this->admin_view->renderProductCreatePage($brands, $categories);
    }

    //Hard to refactor into smaller method
    public function handleProductUpdate()
    {
        $this->ensureAuthenticated();
        $this->conditionForExit(empty($_GET["id"]));
        $id = (int) $this->sanitize($_GET["id"]);
        $this->initializeProductUpdateById($id);
        $brands = $this->product_model->fetchAllBrands();
        $categories = $this->product_model->fetchAllCategories();
        $product_data = $this->product_model->fetchProductById($id);
        //TODO: Better error handling
        if (!$product_data) echo 'Product id does not exist.';
        $this->admin_view->renderProductUpdatePage(
            $brands,
            $categories,
            $product_data
        );
    }

    public function handleProductDelete()
    {
        $this->ensureAuthenticated();
        $this->deleteProduct();
    }

    public function handleOrderList()
    {
        $this->ensureAuthenticated();
        $this->initalizeOrderStatusChange();
        //TODO: create order functionality
        //$statuses = $this->order_model->fetchAllStatuses(); //värt?
        $orders = $this->order_model->fetchAllOrders();
        if (empty($orders)) {
            $this->setAlert("info", "No orders to show.");
        }
        $this->admin_view->renderOrderListPage($orders);
    }

    public function handleOrderDelete()
    {
        $this->ensureAuthenticated();
        $this->deleteOrder();
    }

    // HELPER METHODS:

    private function validateLoginForm()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (empty($_POST["email"]) || empty($_POST["password"])) {
                $this->returnToLoginWithAlert(
                    "Please enter username and password."
                );
            }
            $admin = $this->admin_model->fetchAdminByEmail($_POST["email"]);
            if (!$admin) {
                $this->returnToLoginWithAlert("Incorrect username/email.");
            }
            $hashed_password = $admin["password"];
            $entered_password = $_POST["password"];
            if (!password_verify($entered_password, $hashed_password)) {
                $this->returnToLoginWithAlert("Incorrect password.");
            } else {
                //To prevent storing the password in session storage
                $admin["password"] = null;
                $_SESSION["loggedinadmin"] = $admin;
                $this->setAlert("success", "Successfully Logged In!");
                header("Location: ?page=admin");
            }
            $this->returnToLoginWithAlert("Unexpected error!");
        }
        $this->admin_view->renderLoginPage();
        exit();
    }

    private function logOutAdmin()
    {
        $_SESSION["loggedinadmin"] = null;
        $this->returnToLoginWithAlert("Successfully Logged Out!", "success");
    }

    private function ensureAuthenticated()
    {
        if (empty($_SESSION["loggedinadmin"])) {
            $this->admin_view->renderLoginPage();
            exit();
        }
    }

    private function returnToLoginWithAlert($message, $style = "danger")
    {
        $this->setAlert($style, $message);
        $this->admin_view->renderLoginPage();
        exit();
    }

    private function initializeAddToStock()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $this->getAndValidatePost("id", true);
            $qty = $this->getAndValidatePost("qty", true);
            if ($id && $qty) {
                try {
                    $row_count = $this->product_model->addProductStock(
                        $id,
                        $qty
                    );
                    if ($row_count > 0) {
                        $this->setAlert(
                            "success",
                            "$qty items added to stock for product #$id"
                        );
                    } else {
                        $this->setAlert(
                            "warning",
                            "Could not find product with ID #$id"
                        );
                    }
                } catch (Exception $error) {
                    $this->setAlert(
                        "danger",
                        "An error occured trying to add stock to product #$id"
                    );
                }
            }
        }
    }

    private function handleProductCreatePostRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $product_data = [];
            try {
                $product_data = $this->validateProductForm();
                $product_id = $this->product_model->createProduct(
                    $product_data
                );
                $this->setAlert(
                    "success",
                    "Product successfully created with #id: $product_id"
                );
                header("Location: ?page=admin/products");
                exit();
            } catch (Exception $error) {
                $errors_array = json_decode($error->getMessage(), true);
                foreach ($errors_array as $message) {
                    $this->setAlert("danger", $message);
                }
            }
        }
    }

    private function validateProductForm()
    {
        $errors = [];

        $name = $this->getAndValidatePost("name");
        $price = $this->getAndValidatePost("price", true);
        $description = $this->getAndValidatePost("description");
        $category_id = $this->getAndValidatePost("category_id", true);
        $stock = $this->getAndValidatePost("stock", true);
        $image = $this->getAndValidatePost("image");
        $specification = $this->getAndValidatePost("specification");

        $chosen_brand = $this->getAndValidatePost("brand_id", true);
        $new_brand_chosen = $this->getAndValidatePost("brand_id") === "NEW";
        $new_brand = $this->getAndValidatePost("new_brand");

        if (
            (!$new_brand_chosen && $new_brand) ||
            ($new_brand_chosen && !$new_brand) ||
            (!$chosen_brand && !$new_brand)
        ) {
            array_push(
                $errors,
                "To add a new brand, please pick option 'Add New Brand' and enter a brand name below."
            );
        } elseif ($new_brand_chosen && $new_brand) {
            $product_data["brand_id"] = $this->product_model->createBrand(
                $new_brand
            );
        } else {
            $product_data["brand_id"] = $chosen_brand;
        }

        if ($name && $price && $category_id) {
            $product_data["name"] = $name;
            $product_data["price"] = $price;
            $product_data["description"] = $description;
            $product_data["category_id"] = $category_id;
            $product_data["stock"] = $stock;
            $product_data["image"] = $image;
            $product_data["specification"] = $specification;
        } else {
            array_push($errors, "Please fill in all required fields");
        }

        if (count($errors) === 0) {
            return $product_data;
        } else {
            throw new Exception(json_encode($errors));
        }
    }

    private function initializeProductUpdateById($id)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $product_data = [];
            try {
                $product_data = $this->validateProductForm();
                $this->product_model->updateProductById($id, $product_data);
                $this->setAlert("success", "Product successfully updated");
                header("Location: ?page=admin/products");
                exit();
            } catch (Exception $error) {
                $errors_array = json_decode($error->getMessage(), true);
                foreach ($errors_array as $message) {
                    $this->setAlert("danger", $message);
                }
            }
        }
    }

    private function deleteProduct()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $product_id = (int) $_POST["id"];
                $row_count = $this->product_model->deleteProductById(
                    $product_id
                );
                if ($row_count > 0) {
                    $this->setAlert("success", "Product successfully deleted.");
                } else {
                    $this->setAlert(
                        "warning",
                        "No product found with this ID."
                    );
                }
            } catch (Exception $error) {
                $this->setAlert("danger", "Product could not be deleted.");
            }
        }
        header("Location: ?page=admin/products");
    }

    private function initalizeOrderStatusChange()
    {
        if (isset($_GET["status_id"])) {
            try {
                $row_count = $this->handleOrderStatusUpdate();
                if ($row_count > 0) {
                    $this->setAlert(
                        "success",
                        "Status was updated for $row_count order(s)."
                    );
                } else {
                    $this->setAlert(
                        "warning",
                        "Unable to update to this status for this order."
                    );
                }
            } catch (Exception $error) {
                $this->setAlert("danger", "Unable to update status.");
            }
        }
    }

    private function handleOrderStatusUpdate()
    {
        $order_id = (int) $this->sanitize($_GET["id"]);
        $status_id = (int) $this->sanitize($_GET["status_id"]);
        if ($order_id && $status_id) {
            if ($status_id === 2) {
                $this->order_model->updateOrderShippedDate($order_id);
            }
            $row_count = $this->order_model->updateOrderStatus(
                $order_id,
                $status_id
            );
            return $row_count;
        }
        return false;
    }

    private function deleteOrder()
    {
        try {
            $order_id = (int) $_GET["id"];
            if ($order_id) {
                $row_count = $this->order_model->deleteOrder($order_id);
                if ($row_count > 0) {
                    $this->setAlert(
                        "success",
                        "Successfully deleted $row_count order(s)."
                    );
                } else {
                    $this->setAlert(
                        "warning",
                        "Order with id #$order_id could not be found."
                    );
                }
            }
        } catch (Exception $error) {
            $this->setAlert("danger", "This order can not be deleted.");
        }
        header("Location: ?page=admin/orders");
    }
}
