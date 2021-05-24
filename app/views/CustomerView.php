<?php

require_once "View.php";

// INHERITED METHODS:
// renderHead()
// renderNav($brands)
// renderFooter()
// renderAlerts()
// renderButton()

class CustomerView extends View
{
    //CUSTOMER MAIN METHODS:

    public function renderIndexPage($brands)
    {
        $this->renderHead("Mancave - Home");
        $this->renderNav($brands);
        $this->renderAlerts();
        include_once "app/views/pages/indexContent.php";
        $this->renderFooter();
    }

    public function renderAboutPage($brands)
    {
        $this->renderHead("Mancave - About");
        $this->renderNav($brands);
        $this->renderAlerts();
        include_once "app/views/pages/about.php";
        $this->renderFooter();
    }

    public function renderRegisterPage($customer_data = null, $brands)
    {
        $this->renderHead("Mancave - New Customer");
        $this->renderNav($brands);
        $this->renderAlerts();
        $this->renderRegisterForm($customer_data);
        $this->renderFooter();
    }
    /**
     * display the whole shopping cart page
     */
    public function renderShoppingCartPage($products, $customer = false, $brands)
    {
        $this->renderHead("ManCave - Shopping Cart");
        $this->renderNav($brands);
        $this->renderAlerts();

        if (empty($_SESSION["shopping_cart"])) {
            include_once "app/views/partials/emptyCart.php";
        } else {
            $items_in_stock = $this->renderShoppingCartList($products);
            if ($customer && $items_in_stock) {
                $this->renderButton("Continue to checkout", "?page=checkout");
            } else if ($items_in_stock) {
                $this->renderModalButton();
            } else {
                $this->renderButton("Some items are out of stock, please update your order to continue.", "");
            }
        }
        $this->renderFooter();
    }

    public function renderCheckoutPage($products, $customer, $total, $brands)
    {
        $column_name_array = ["Product name", "Amount", "Price each"];
        $this->renderHead("Mancave - Checkout");
        $this->renderNav($brands);
        $this->renderAlerts();
        include_once "partials/list/listStart.php";
        foreach ($products as $product) {
            $qty = $_SESSION["shopping_cart"][$product["id"]];
            include "partials/list/productListCheckout.php";
        }
        include_once "partials/list/productCheckoutTotal.php";
        include_once "partials/list/listEnd.php";
        include_once "partials/customerCheckoutInfo.php";
        $this->renderButton("Confirm Order", "?page=checkout/process-order");
        $this->renderFooter();
    }

    public function renderOrderConfirmationPage($customer, $order_id, $brands)
    {
        $this->renderHead("Mancave - Order Successful");
        $this->renderNav($brands);
        include_once "app/views/pages/orderConfirmation.php";
        $this->renderFooter();
    }

    public function renderProductPage($products, $title, $brands)
    {
        $this->renderHead("Mancave - $title");
        $this->renderNav($brands);
        $this->renderAlerts();
        $this->renderCustomerProductList($products);
        $this->renderFooter();
    }

    public function renderDetailPage($product, $brand, $brands)
    {
        $this->renderHead("Mancave - Product Details");
        $this->renderNav($brands);
        $this->renderAlerts();
        $this->renderProductDetails($product, $brand);
        $this->renderFooter();
    }

    //CUSTOMER HELPER METHODS:

    /**
     * help method for shopping cart page - lists all products in the cart.
     */
    private function renderShoppingCartList($products)
    {
        $items_in_stock = true;
        $column_name_array = ["Product name", "Price each", "Amount", "Delete"];

        include_once "partials/list/listStart.php";
        foreach ($products as $product) {
            $qty = $_SESSION["shopping_cart"][$product["id"]];
            $out_of_stock = $qty > $product['stock'];
            if ($out_of_stock) {
                $items_in_stock = false;
            }
            
            include "partials/list/shoppingCartItem.php";
        }
        include_once "partials/list/listEnd.php";
        return $items_in_stock;
    }

    private function renderRegisterForm($customer_data = null)
    {
        include_once "app/views/partials/form/registerform.php";
    }

    private function renderCustomerProductList($products)
    {
        include_once "app/views/partials/list/customerProductList.php";
    }

    private function renderProductDetails($product, $brand)
    {
        include_once "app/views/pages/productDetails.php";
    }

    private function renderModalButton()
    {
        $html = <<<HTML
            <div class="d-flex justify-content-center p-1">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Log in to continue to checkout</a>
            </div>
        HTML;
        echo $html;
    }
}
