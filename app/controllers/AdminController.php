<?php

require_once "Controller.php";

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

    public function index()
    {
        $this->ensureAuthenticated();

        $products = $this->product_model->fetchAllProducts();

        if (empty($products)) {
            $_SESSION['alerts']['warning'][] = "No products to show.";
        }
        $this->admin_view->renderIndexPage($products);
    }

    public function productCreate()
    {
        $this->ensureAuthenticated();
        $product_data = array();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $product_data = $this->handleProductPost();
                $product_id = $this->product_model->createProduct($product_data);
                $_SESSION['alerts']['success'][] = "Product successfully created with #id: $product_id";
                header("Location: ?page=admin/products");
                exit();
            } catch (Exception $error) {
                $errors_array = json_decode($error->getMessage(), true);
                foreach ($errors_array as $message) {
                    $_SESSION['alerts']['danger'][] = $message;
                }                
            }
        }

        $brands = $this->product_model->fetchAllBrands();
        $categories = $this->product_model->fetchAllCategories();
        $this->admin_view->renderProductCreatePage($brands, $categories);
    }


    public function productUpdate()
    {
        $this->ensureAuthenticated();
        $this->conditionForExit(empty($_GET['id']));

        $id = (int)$this->sanitize($_GET['id']);
        $product_data = array();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $product_data = $this->handleProductPost();
                $this->product_model->updateProductById($id, $product_data);
                $_SESSION['alerts']['success'][] = "Product successfully updated";
                header('Location: ?page=admin/products');
                exit;
            } catch (Exception $error) {
                $errors_array = json_decode($error->getMessage(), true);
                foreach ($errors_array as $message) {
                    $_SESSION['alerts']['danger'][] = $message;
                }
            }
        }

        $brands = $this->product_model->fetchAllBrands();
        $categories = $this->product_model->fetchAllCategories();
        $product_data = $this->product_model->fetchProductById($id);
        //TODO: Better error handling
        if (!$product_data) echo 'Product id does not exist.';
        else $this->admin_view->renderProductUpdatePage($brands, $categories, $product_data);
    }

    public function productDelete()
    {
        $this->ensureAuthenticated();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $product_id = (int)$_POST['id'];
                $row_count = $this->product_model->deleteProductById($product_id);
                if($row_count > 0) {
                    $_SESSION['alerts']['success'][] = "Product successfully deleted.";
                } else {
                    $_SESSION['alerts']['warning'][] = "No product found with this ID.";
                }
            } catch (Exception $error) {
                $_SESSION['alerts']['danger'][] = "Product could not be deleted.";
            }
        }
        header('Location: ?page=admin/products');
    }

    public function orderDelete() 
    {
        $this->ensureAuthenticated();
        try {
            $order_id = (int)$_GET['id'];
            if ($order_id) {
                $row_count = $this->order_model->deleteOrder($order_id);
                if ($row_count > 0) {
                    $_SESSION['alerts']['success'][] = "Successfully deleted $row_count order(s).";
                } else {
                    $_SESSION['alerts']['warning'][] = " Order with id #$order_id could not be found.";
                }
            }
        } catch (Exception $error) {
            $_SESSION['alerts']['danger'][] = "This order can not be deleted.";
        }
        $this->orderList();
    }

    public function orderList()
    {
        $this->ensureAuthenticated();
        if (isset($_GET['status_id'])) {
            try {
                $row_count = $this->handleOrderStatusUpdate();
                if ($row_count > 0) {
                    $_SESSION['alerts']['success'][] = "Status was updated for $row_count order(s).";
                } else {
                    $_SESSION['alerts']['warning'][] = "Unable to update to this status for this order.";
                }
            } catch (Exception $error) {
                $_SESSION['alerts']['danger'][] = "Unable to update status.";
            }
        }
        
        //TODO: create order functionality
        //$statuses = $this->order_model->fetchAllStatuses(); //värt?
        $orders = $this->order_model->fetchAllOrders();
        if (empty($orders)) {
            $_SESSION['alerts']['warning'][] = "No orders to show.";
        }
        $this->admin_view->renderOrderListPage($orders);
    }

    // HELPER METHODS:

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

    private function handleOrderStatusUpdate() 
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

    private function ensureAuthenticated()
    {
        // remove exclamation mark when admin login is fixed.
        if (!empty($_SESSION["loggedinadmin"])) {
            $this->admin_view->renderLoginPage();
            exit;
        }
    }
}
