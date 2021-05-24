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
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $admin = $this->admin_model->fetchAdminByEmail($_POST["email"]);
            $this->validateLoginForm($admin, "admin", "page=admin");
        }
        $this->admin_view->renderLoginPage();
        exit();
    }

    public function handleLogout()
    {
        $this->logOutAndGoToPage("admin", "page=admin/login");
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
        if (!$product_data) {
            echo "Product id does not exist.";
        }
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
        $orders = $this->order_model->fetchAllOrders();
        if (empty($orders)) {
            $this->setAlert("info", "No orders to show.");
        }
        $this->admin_view->renderOrderListPage($orders);
    }

    public function handleOrderDetails()
    {
        $this->ensureAuthenticated();
        $this->initializeOrderUpdate();
        [$order, $order_content] = $this->getOrderDetails();
        $this->admin_view->renderOrderDetails($order, $order_content);
    }

    public function handleOrderDelete()
    {
        $this->ensureAuthenticated();
        $this->deleteOrder();
    }

    // HELPER METHODS:

    private function ensureAuthenticated()
    {
        if (empty($_SESSION["admin"])) {
            $this->goToPageWithAlert(
                "You need to be logged in to access this page.",
                "page=admin/login"
            );
        }
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
        $new_brand = $this->getAndValidatePost("new_brand");

        if (!$chosen_brand && !$new_brand) {
            array_push(
                $errors,
                "Please add a new brand or choose an existing one."
            );
        } else if ($new_brand) {
            try {
                $product_data["brand_id"] = $this->product_model->createBrand(
                    $new_brand
                );
            } catch (Exception $e) {
                array_push(
                    $errors,
                    "Failed to create new brand, please try again later."
                );
            }
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
        $order_id = (int)$this->sanitize($_GET["id"]);
        $status_id = (int)$this->sanitize($_GET["status_id"]);
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

    private function getOrderDetails()
    {
        if (isset($_GET['id'])) {
            $order_id = (int)$this->sanitize($_GET['id']);
            try {
                $order = $this->order_model->fetchOrderById($order_id);
                $order_content = $this->order_model->fetchOrderContentsByOrderId($order_id);
                return [$order, $order_content];
            } catch (Exception $error) {
                $this->goToPageWithAlert("No order details to show.", "page=admin/orders");
            }
        } else {
            $this->goToPageWithAlert("Order ID not found", "page=admin/orders");
        }
    }

    private function initializeOrderUpdate() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (isset($_POST['delete_row_id'])) {
                $id = $this->getAndValidatePost('delete_row_id', true);
                try {
                    $this->order_model->deleteOrderContent($id);
                    $this->setAlert("success", "Order row deleted.");
                } catch (Exception $error) {
                    $this->setAlert("danger", "Order row could now be deleted.");
                }
            }
            if (isset($_POST['update_row_qty_id'])) {
                $id = $this->getAndValidatePost('update_row_qty_id', true);
                $qty = $this->getAndValidatePost('qty', true);
                try {
                    $this->order_model->updateOrderContentQuantity($id, $qty);
                    $this->setAlert("success", "Order row quantity updated.");
                } catch (Exception $error) {
                    $this->setAlert("danger", "Order row could now be updated.");
                }
            }
        }
    }

    private function deleteOrder()
    {
        try {
            $order_id = (int)$_GET["id"];
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
