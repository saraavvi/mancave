<?php
class View
{
    public function renderHeader($title)
    {
        include_once "app/views/partials/header.php";
    }

    /**
     * Receive null by default for create form, or data for update form
     */
    public function renderForm($data = null, $brands, $categories)
    {
        include_once "app/views/partials/form.php";
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
                    <img src="$product[image]" class="card-img-top p-3" alt="...">
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

    public function renderButton($text, $href, $primary = true)
    {
        $class = $primary ? "btn-primary" : "btn-secondary";
        $html = <<<HTML
            <div class="d-flex justify-content-center p-1">
                <a class="btn $class" href="$href">$text</a>
            </div>
        HTML;
        echo $html;
    }

    public function renderAdminIndexPage($products)
    {
        $this->renderHeader("Admin Page - Products");
        $this->renderAdminHeader();
        $this->renderButton("Add new product", "?page=admin/products/create");
        $this->renderButton("Go to order list", "?page=admin/orders", false);
        $this->renderProductsListStart();
        $this->renderProducts($products);
        $this->renderProductsListEnd();
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminProductCreatePage($brands, $categories)
    {
        $this->renderHeader("Admin Page - Create");
        $this->renderAdminHeader();
        $this->renderButton("Go back to product list", "?page=admin", false);
        $this->renderButton("Go to order list", "?page=admin/orders", false);
        $this->renderForm(null, $brands, $categories);
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminProductUpdatePage($product_data, $brands, $categories)
    {
        $this->renderHeader("Admin Page - Update");
        $this->renderAdminHeader();
        $this->renderButton("Go back to product list", "?page=admin", false);
        $this->renderForm($product_data, $brands, $categories);
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminOrderListPage(/* $orders */)
    {
        $this->renderHeader("Admin - Order List");
        $this->renderAdminHeader();
        $this->renderButton("Go back to product list", "?page=admin", false);
        echo "Placeholder for order list";
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminHeader()
    {
        echo '<h1 class="text-center">ManCave</h1>';
        echo '<h2 class="text-center">Admin</h2>';
        echo '<h3 class="text-center">Nav placeholder</h3>';
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
