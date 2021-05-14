<?php
class View
{
    public function renderHeader($title)
    {
        include_once 'app/views/partials/header.php';
    }

    /**
     * Receive null by default for create form, or data for update form
     */
    public function renderForm($data = null)
    {
        include_once 'app/views/partials/form.php';
    }

    public function renderFooter()
    {
        include_once 'app/views/partials/footer.php';
    }

    public function renderAdminPage($products)
    {
        $this->renderHeader('Admin Page - Products');
        $this->renderAdminHeader();
        echo '<a class="btn btn-primary d-flex justify-content-center" href="?page=admin/products/create">Add new product</a></br>';
        $this->renderProductsListStart();
        $this->renderProducts($products);
        $this->renderProductsListEnd();
        include_once 'app/views/partials/footer.php';
    }

    public function renderCreatePage()
    {
        $this->renderHeader('Admin Page - Create');
        $this->renderAdminHeader();
        echo '<a class="btn btn-secondary d-flex justify-content-center" href="?page=admin">Go back to product list</a></br>';
        $myArray = array(
            'name' => 'Big'
        );
        $this->renderForm($myArray);
        include_once 'app/views/partials/footer.php';
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
      <div class="col-md-6">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">First</th>
              <th scope="col">Last</th>
              <th scope="col">Handle</th>
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
}
