<?php

require_once 'View.php';

// INHERITED METHODS:
// renderHead()
// renderNav() 
// renderFooter()
// renderAlerts()
// renderButton()
// cleanAlerts()
class AdminView extends View
{
    // MAIN METHODS:

    public function renderLoginPage()
    {
        $this->renderHead("Admin - Log in");
        $this->renderAlerts();
        include_once "pages/adminLogin.php";
        $this->renderFooter();
    }

    public function renderIndexPage($products)
    {
        $this->renderHead("Admin - Home");
        $this->renderNav();
        $this->renderButton("Add new product", "?page=admin/products/create");

        $this->renderAlerts();
        if (!empty($products)) {
            $this->renderListStart(["#", "Name", "Stock", "Edit", "Add to stock", "Delete"]);
            $this->renderListItemsProducts($products);
            $this->renderListEnd();
        }
        $this->renderFooter();
    }

    public function renderProductCreatePage($brands, $categories)
    {
        $this->renderHead("Admin - Create Product");
        $this->renderNav();
        $this->renderButton(
            "Go back to product list",
            "?page=admin",
            "secondary"
        );
        $this->renderButton(
            "Go to order list",
            "?page=admin/orders",
            "secondary"
        );
        $this->renderAlerts();
        $this->renderForm($brands, $categories);
        $this->renderFooter();
    }

    public function renderProductUpdatePage(
        $brands,
        $categories,
        $product_data,
        $errors = []
    ) {
        $this->renderHead("Admin - Update Product");
        $this->renderNav();
        $this->renderButton(
            "Go back to product list",
            "?page=admin",
            "secondary"
        );
        $this->renderAlerts($errors);
        $this->renderForm($brands, $categories, $product_data);
        $this->renderFooter();
    }

    public function renderOrderListPage($orders)
    {
        $this->renderHead("Admin - Order List");
        $this->renderNav();
        $this->renderButton(
            "Go back to product list",
            "?page=admin",
            "secondary"
        );

        $this->renderAlerts();
        // avoid rendering list if orders is empty (CODE READABILITY?)
        if (!empty($orders)) {
            $this->renderListStart([
                "#",
                "Date Placed",
                "Customer Name",
                "Status",
                "Change Status",
                "View Order",
                "Delete Order"
            ]);
            $this->renderListItemsOrders($orders);
            $this->renderListEnd();
        }
        $this->renderFooter();
    }

    public function renderOrderDetails($order, $order_content)
    {
        $this->renderHead("Admin - Order Details");
        $this->renderNav();
        $this->renderButton(
            "Go back to order list",
            "?page=admin/orders",
            "secondary"
        );

        $this->renderAlerts();
        if (!empty($order)) {
            $this->renderListStart([
                "Order row ID",
                "Product ID",
                "Name",
                "Price each",
                "Row total",
                "Quantity",
                "Delete Order Row"
            ]);
            foreach ($order_content as $order_row) {
                include "partials/list/adminOrderDetails.php";
            }
        
            include_once "partials/list/adminOrderSummary.php";
            $this->renderListEnd();
        }

        $this->renderFooter();
    }

    // HELPER METHODS:
    public function renderListStart($column_name_array)
    {
        include_once "app/views/partials/list/listStart.php";
    }

    public function renderListEnd()
    {
        include_once "app/views/partials/list/listEnd.php";
    }

    /**
     * Receive null by default for create form, or data for update form
     */
    public function renderForm($brands, $categories, $data = null)
    {
        include_once "app/views/partials/form/productForm.php";
    }

    public function renderListItemsOrders($orders)
    {
        include_once "app/views/partials/list/orderList.php";
    }

    public function renderListItemsProducts($products)
    {
        include_once "app/views/partials/list/productList.php";
    }
}
