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

    //ADMIN MAIN METHODS:


    private function index()
    {
        $alerts = array();
        $products = $this->product_model->fetchAllProducts();

        if (empty($products)) {
            $alerts['warning'][] = "No products to show.";
        }
        
        $this->view->renderAdminIndexPage($products, $alerts);
    }

    private function productCreate()
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


    private function productUpdate()
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

    public function productDelete() {
        if ($_GET['action'] === "delete")
        $product_id = (int)$_GET['id'];
        $row_count = $this->product_model->deleteProductById($product_id);
        return $row_count;
    }

    public function orderDelete() 
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
            $this->orderList($alerts);
        } catch (Exception $error) {
            $alerts['danger'][] = "This order can not be deleted.";
            $this->orderList($alerts);
        }
    }

    private function orderList($alerts = array())
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
}
