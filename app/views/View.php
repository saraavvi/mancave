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
  public function renderForm($data = null)
  {
    include_once 'app/views/partials/form.php';
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

    public function renderFooter()
    {
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminIndexPage($products)
    {
        $this->renderHeader("Admin Page - Products");
        $this->renderAdminHeader();
        echo '<a class="btn btn-primary d-flex justify-content-center" href="?page=admin/products/create">Add new product</a></br>';
        $this->renderProductsListStart();
        $this->renderProducts($products);
        $this->renderProductsListEnd();
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminProductCreatePage($brands, $categories)
    {
        $this->renderHeader("Admin Page - Create");
        $this->renderAdminHeader();
        echo '<a class="btn btn-secondary d-flex justify-content-center" href="?page=admin">Go back to product list</a></br>';
        $this->renderForm(null, $brands, $categories);
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminProductUpdatePage(
        $product_data,
        $brands,
        $categories
    ) {
        $this->renderHeader("Admin Page - Update");
        $this->renderAdminHeader();
        echo '<a class="btn btn-secondary d-flex justify-content-center" href="?page=admin">Go back to product list</a></br>';
        $this->renderForm($product_data, $brands, $categories);
        include_once "app/views/partials/footer.php";
    }

    public function renderAdminHeader()
    {
        echo '<h1 class="text-center">ManCave</h1>';
        echo '<h2 class="text-center">Admin</h2>';
        echo '<h3 class="text-center">Nav placeholder</h3>';
    }
    
    public function renderAdminPage($products)
    {
        $this->renderHeader("Admin Page - Products");
        $this->renderAdminHeader();
        echo '<a class="btn btn-primary d-flex justify-content-center" href="?page=admin/products/create">Add new product</a></br>';
        $this->renderProductsListStart();
        $this->renderProducts($products);
        $this->renderProductsListEnd();
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
        
    public function renderProducts($products)
    {
        foreach ($products as $product) {
            $html = <<<HTML
                <tr>
                    <th scope="row">$product[id]</th>
                    <td>$product[name]</td>
                    <td>$product[stock]</td>
                    <td>
                        <a href="index.php/products/update?id=$product[id]" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="post" action="index.php/products/delete" style="display: inline-block">
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
}
