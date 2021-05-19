<?php
class View
{
    //CONTENT:
    //COMMON MAIN METHODS:
    //COMMON HELPER METHODS:
    //CUSTOMER MAIN METHODS:
    //CUSTOMER HELPER METHODS:
    //ADMIN MAIN METHODS:
    //ADMIN HELPER METHODS:

    //COMMON MAIN METHODS:
    //COMMON HELPER METHODS:

    public function renderHeader($title, $admin = false)
    {
        include_once "app/views/partials/head.php";
        if ($admin) {
            include_once "app/views/partials/adminNav.php";
        } else {
            include_once "app/views/partials/customerNav.php";
        }
    }

    public function renderFooter()
    {
        include_once "app/views/partials/footer.php";
    }

    public function renderAlerts($alerts)
    {
        foreach ($alerts as $category => $messages) {
            foreach ($messages as $message) {
                echo "
                    <div class='d-flex justify-content-center'>
                        <div class='col-md-10 text-center alert alert-$category' role='alert'>
                            $message
                        </div>
                    </div>
                ";
            }
        }
    }

    //CUSTOMER MAIN METHODS:

    public function renderCustomerIndexPage($alerts = [])
    {
        $this->renderHeader("ManCave - Home");
        $this->renderAlerts($alerts);
        include_once "app/views/partials/indexContent.php";
        $this->renderFooter();
    }

    public function renderCustomerRegisterPage($alerts = [], $customer_data = null)
    {
        $this->renderHeader("New Customer");
        $this->renderAlerts($alerts);
        $this->renderRegisterForm($customer_data);
        include_once "app/views/partials/footer.php";
    }
    /**
     * display the whole shopping cart page
     */
    public function renderShoppingCartPage($products)
    {
        $this->renderHeader("ManCave - Shopping Cart");
        $this->renderShoppingCartList($products);
        //skickar med en tom sträng som href nu. Ändra sen
        $this->renderButton("Continue to checkout", "");
        $this->renderFooter();
    }

    /**
     * help method for shopping cart page - lists all products in the cart. 
     */
    public function renderShoppingCartList($products)
    {
        // print_r($_SESSION['shopping_cart']);


        $html = <<<HTML
        <div class="col-md-6 mt-5">
            <table class="table table-borderless">
                <tbody> 
        HTML;
        foreach ($products as $product) {
            $qty = $_SESSION['shopping_cart'][$product['id']];
            $html .= <<<HTML
                    <tr>
                        <td>$product[name]</td>
                        <td>$product[price] SEK</td>
                        <td>$qty</td>
                        <td><a href="?page=shoppingcart&id=$product[id]&action=delete" class='btn btn-danger'>x</a></td>
                    </tr>
            HTML;
        }
        $html .= <<<HTML
                    </tbody>
                </table>
            </div>
            HTML;
        echo $html;
    }

    public function renderProductPage($products)
    {
        $this->renderHeader("mancave - products");
        $this->renderCustomerProductList($products);
        $this->renderFooter();
    }

    public function renderDetailPage($product)
    {
        $this->renderHeader("mancave - products");
        $this->renderProductDetails($product);
        $this->renderFooter();
    }


    //CUSTOMER HELPER METHODS:

    public function renderRegisterForm($customer_data = null)
    {
        include_once "app/views/partials/registerform.php";
    }

    public function renderCustomerProductList($products)
    {
        include_once "app/views/partials/customerProductList.php";
    }


    public function renderProductDetails($product)
    {

        //  Bara för att visa produkten just nu - byt ut detta mot vad vi vill visa på den här sidan.

        include_once "app/views/partials/productDetails.php";
    }

    //ADMIN MAIN METHODS:

    public function renderAdminIndexPage($products, $alerts = [])
    {

        $this->renderHeader("admin - home", true);
        $this->renderButton("Add new product", "?page=admin/products/create");

        // other possible errors than "No products to show"?
        if ($alerts) {
            $this->renderAlerts($alerts);
        } else {
            $this->renderListStart(["#", "Name", "Stock", "Edit", "Delete"]);
            $this->renderListItemsProducts($products);
            $this->renderListEnd();
        }
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminProductCreatePage($brands, $categories, $alerts)
    {
        $this->renderHeader("Admin Page - Create", true);
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
        $this->renderAlerts($alerts);
        $this->renderForm($brands, $categories);
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminProductUpdatePage(
        $brands,
        $categories,
        $product_data,
        $errors = []
    ) {
        $this->renderHeader("Admin Page - Update", true);
        $this->renderButton(
            "Go back to product list",
            "?page=admin",
            "secondary"
        );
        $this->renderAlerts($errors);
        $this->renderForm($brands, $categories, $product_data);
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminOrderListPage($orders, $alerts)
    {
        $this->renderHeader("Admin - Order List", true);
        $this->renderButton(
            "Go back to product list",
            "?page=admin",
            "secondary"
        );

        $this->renderAlerts($alerts);
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
        include_once "app/views/partials/footer.php";
    }

    //ADMIN HELPER METHODS:

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
        include_once "app/views/partials/form.php";
    }

    public function renderButton($text, $href, $style = "primary")
    {
        $html = <<<HTML
                <div class="d-flex justify-content-center p-1">
                    <a class="btn btn-$style" href="$href">$text</a>
                </div>
            HTML;
        echo $html;
    }

    public function renderListItemsOrders($orders)
    {
        //TODO: needs to be fixed = "/mancave/?page=admin/orders&id=1&status_id=1&id=3&status_id=2&id=1&status_id=2"
        include_once "app/views/partials/list/orderList.php";
    }

    public function renderListItemsProducts($products)
    {
        include_once "app/views/partials/list/productList.php";
    }
}
