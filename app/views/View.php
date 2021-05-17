<?php
class View
{
    //CONTENT:
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

    //CUSTOMER MAIN METHODS:

    public function renderCustomerIndexPage($alerts = [])
    {
        $this->renderHeader("ManCave - Home");
        $this->renderAlerts($alerts);
        echo 'placeholder for landing page';
        $this->renderFooter();
    }

    public function renderCustomerRegisterPage($alerts = [], $customer_data = null)
    {
        $this->renderHeader("New Customer");
        $this->renderAlerts($alerts);
        $this->renderRegisterForm($customer_data);
        include_once "app/views/partials/footer.php";
    }

    //CUSTOMER HELPER METHODS:

    public function renderRegisterForm($customer_data = null)
    {
        include_once "app/views/partials/registerform.php";
    }

    //ADMIN MAIN METHODS:
    //ADMIN HELPER METHODS:

    public function renderFooter()
    {
        include_once "app/views/partials/footer.php";
    }

    public function renderProductsListStart()
    {
    $html = <<<HTML
            <div class="row d-flex justify-content-center">
                <div class="col-md-10">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                            </tr>
                        </thead>
                    <tbody>
        HTML;
    echo $html;
    }

    /**
     * Receive null by default for create form, or data for update form
     */
    public function renderForm($brands, $categories, $data = null)
    {
        include_once "app/views/partials/form.php";
    }

    public function renderProductPage($products)
    {
        $this->renderHeader("mancave - products");
        $this->renderCustomerProducts($products);
        $this->renderFooter();
    }

    public function renderDetailPage($product)
    {
        $this->renderHeader("mancave - products");
        $this->renderProductDetails($product);
        $this->renderFooter();
    }

    public function renderProductDetails($product)
    {

        //  Bara för att visa produkten just nu - byt ut detta mot vad vi vill visa på den här sidan.
        $html = <<<HTML
            <div class="col-md-6 border">
                <img src="$product[image]" class="img-fluid" alt="...">
            </div>
            <div class="col-md-4 border">
                <h2>$product[name]</h2>
                <p class="fs-1">$product[price] SEK</p>
                <button class="btn btn-primary w-100">add to cart</button>
                <div class="mt-3">
                    <p class="fw-bold">product information</p>
                    <p>$product[description]</p>
                <div>
                <div class="mt-3">
                    <p>$product[specification]</p>
                <div>
            </div>
        HTML;
    echo $html;
    }

    public function renderCustomerProducts($products)
    {
        foreach ($products as $product) {
        $this->renderOneCustomerProduct($product);
        }
    }

    public function renderOneCustomerProduct($product)
    {
        $html = <<<HTML
                <div class="col-md-3 mt-3">
                    <div class="card" style="width: 18rem;">
                    <a href="?page=products/details&id=$product[id]"> 
                        <img src="$product[image]" class="card-img-top p-3" alt="...">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">$product[name]</h5>
                        <p class="card-text">$product[price] sek</p>
                        <a href="#" class="btn btn-primary">add to cart</a>
                    </div>
                </div>
            </div>
        HTML;
    echo $html;
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

    public function renderAdminIndexPage($products)
    {
        $this->renderHeader("admin - home", true);
        $this->renderButton("Add new product", "?page=admin/products/create");
        $this->renderListStart(["#", "Name", "Stock", "Edit", "Delete"]);
        $this->renderListItemsProducts($products);
        $this->renderListEnd();
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
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminPage($products)
    {
        $this->renderHeader("Admin Page - Products", true);
        echo '<a class="btn btn-primary d-flex justify-content-center" href="?page=admin/products/create">Add new product</a></br>';
        $this->renderProductsListStart();
        $this->renderProducts($products);
        $this->renderProductsListEnd();
        include_once "app/views/partials/footer.php";
    }

    public function renderAlerts($alerts)
    {
        foreach ($alerts as $category => $messages) {
            foreach ($messages as $message) {
                echo "<div class='d-flex justify-content-center alert alert-$category' role='alert'>
                    $message
                </div>";
            }
        }
    }

    public function renderListStart($column_name_array)
    {
        $html = <<<HTML
        <div class="row d-flex justify-content-center">
            <div class="col-md-10">
                <table class="table">
                    <thead>
                        <tr>
    HTML;
        foreach ($column_name_array as $column_name) {
        $html .= "<th scope='col'>$column_name</th>";
        }
        $html .= <<<HTML
                </tr>
            </thead>
        <tbody>
    HTML;
        echo $html;
    }

    public function renderListEnd()
    {
        $html = <<<HTML
                    </tbody>
                </table>
            </div>
        </div>
    HTML;
        echo $html;
    }

    public function renderListItemsOrders($orders)
    {
        foreach ($orders as $order) {
            // current url path to append additional query params to (status)
            $uri = $_SERVER['REQUEST_URI'];
            $html = <<<HTML
                <tr>
                    <th scope="row">$order[id]</th>
                    <td>$order[order_date]</td>
                    <td>$order[customer_name]</td>
                    <td>$order[status_name]</td>
                    <td class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Change Status
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="{$uri}&id={$order['id']}&status_id=1">Draft</a></li>
                            <li><a class="dropdown-item" href="{$uri}&id={$order['id']}&status_id=2">Shipped</a></li>
                            <li><a class="dropdown-item" href="{$uri}&id={$order['id']}&status_id=3">Pending</a></li>
                            <li><a class="dropdown-item" href="{$uri}&id={$order['id']}&status_id=4">Cancelled</a></li>
                        </ul>
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-primary">View Order</a>
                    </td>
                    <td>
                        <a href="{$uri}&id={$order['id']}&action=delete" class="btn btn-sm btn-outline-danger">Delete Order</a>
                    </td>
                </tr>
            HTML;
            echo $html;
        }
    }

    public function renderListItemsProducts($products)
    {
        foreach ($products as $product) {
        $html = <<<HTML
                    <tr>
                        <th scope="row">$product[id]</th>
                        <td>$product[name]</td>
                        <td>$product[stock]</td>
                        <td>
                            <a href="?page=admin/products/update&id=$product[id]" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                        <td>
                            <form method="post" action="?page=admin/products/delete" style="display: inline-block">
                                <input  type="hidden" name="id" value="$product[id]"/>
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                HTML;
        echo $html;
        }
    }

    public function renderProductsListEnd()
    {
        $html = <<<HTML

                    </tbody>
                </table>
            </div>
        </div>

    HTML;

        echo $html;
    }

    public function renderProducts($products)
    {
        foreach ($products as $product) {
            $html = <<<HTML
                <tr>
                    <th scope="row">$product[id]</th>
                    <td>$product[name]</td>
                    <td>$product[stock]</td>
                    <td>
                    <a href="?page=admin/products/update&id=$product[id]" class="btn btn-sm btn-outline-primary">Edit</a>
                    </td>
                    <td>
                        <form method="post" action="?page=admin/products/delete" style="display: inline-block">
                            <input  type="hidden" name="id" value="$product[id]"/>
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                HTML;
            echo $html;
        }
    }
}
