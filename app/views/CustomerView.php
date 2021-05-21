<?php

require_once 'View.php';

// INHERITED METHODS:
// renderHead()
// renderNav()
// renderFooter()
// renderAlerts()
// renderButton()

class CustomerView extends View
{

    //CUSTOMER MAIN METHODS:

    public function renderIndexPage()
    {
        $this->renderHead("Mancave - Home");
        $this->renderNav();
        $this->renderAlerts();
        include_once "app/views/partials/indexContent.php";
        $this->renderFooter();
    }

    public function renderRegisterPage($customer_data = null)
    {
        $this->renderHead("Mancave - New Customer");
        $this->renderNav();
        $this->renderAlerts();
        $this->renderRegisterForm($customer_data);
        include_once "app/views/partials/footer.php";
    }
    /**
     * display the whole shopping cart page
     */
    public function renderShoppingCartPage($products, $logged_in = false)
    {
        $this->renderHead("ManCave - Shopping Cart");
        $this->renderNav();
        $this->renderAlerts();
        $this->renderShoppingCartList($products);
        if (empty($_SESSION["shopping_cart"])) {
            include_once "app/views/partials/emptyCart.php";
        } else {
            if ($logged_in) {
                $this->renderButton("Continue to checkout", "?page=checkout/process-order");
            } else {
                $this->renderModalButton();
                $_SESSION['next_page'] = "?page=order/confirmation";
            }
        }
        $this->renderFooter();
    }

    public function renderOrderConfirmationPage($customer, $order_id)
    {
        $this->renderHead("Mancave - Order Successful");
        $this->renderNav();
        include_once "app/views/partials/orderConfirmation.php";
        $this->renderFooter();
    }

    /**
     * help method for shopping cart page - lists all products in the cart. 
     */
    public function renderShoppingCartList($products)
    {
        // print_r($_SESSION['shopping_cart']);
        // använda list start och list end sen istället

        echo "
        <div class='col-md-6 mt-5'>
            <table class='table table-borderless'>
                <tbody> ";
        foreach ($products as $product) {
            $qty = $_SESSION['shopping_cart'][$product['id']];
            include "partials/shoppingCartItem.php";
        }
        echo "  </tbody>
                </table>
            </div>";
    }


    public function renderCheckoutPage($products, $total, $customer)
    {

        $column_name_array = array("Product name", "Amount", "Price each");
        $this->renderHead("Mancave - Checkout");
        $this->renderNav();
        include_once "partials/list/listStart.php";
        foreach ($products as $product) {
            $qty = $_SESSION['shopping_cart'][$product['id']];
            include "partials/list/productListCheckout.php";
        }
        include_once "partials/list/productCheckoutTotal.php";
        include_once "partials/list/listEnd.php";
        include_once "partials/customerCheckoutInfo.php";
        $this->renderButton("Confirm Order", "?page=checkout/process-order");
        $this->renderFooter();
    }

    public function renderProductPage($products)
    {
        $this->renderHead("Mancave - Products");
        $this->renderNav();
        $this->renderCustomerProductList($products);
        $this->renderFooter();
    }

    public function renderDetailPage($product)
    {
        $this->renderHead("Mancave - Product Details");
        $this->renderNav();
        $this->renderProductDetails($product);
        $this->renderFooter();
    }


    //CUSTOMER HELPER METHODS:

    private function renderRegisterForm($customer_data = null)
    {
        include_once "app/views/partials/registerform.php";
    }

    private function renderCustomerProductList($products)
    {
        include_once "app/views/partials/customerProductList.php";
    }


    private function renderProductDetails($product)
    {

        //  Bara för att visa produkten just nu - byt ut detta mot vad vi vill visa på den här sidan.

        include_once "app/views/partials/productDetails.php";
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
