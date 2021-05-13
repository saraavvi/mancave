<?php
class View
{
    public function renderHeader($title)
    {
        include_once 'views/partials/header.php';
    }

    public function renderForm($data = null)
    {
        include_once 'views/partials/form.php';
    }

    public function renderFooter()
    {
        include_once 'views/partials/footer.php';
    }

    public function renderAdminPage()
    {
        $this->renderHeader('Admin Page - Products');
        $this->renderAdminHeader();
        echo '<a class="btn btn-primary d-flex justify-content-center" href="./create.php">Add new product</a></br>';
        $this->renderProductListStart();
        $this->renderProduct();
        $this->renderProduct();
        $this->renderProductListEnd();
        include_once 'views/partials/footer.php';
    }

    public function renderCreatePage()
    {
        $this->renderHeader('Admin Page - Create');
        $this->renderAdminHeader();
        echo '<a class="btn btn-secondary d-flex justify-content-center" href="./">Go back to product list</a></br>';
        $myArray = array(
            'name' => 'Big'
        );
        $this->renderForm($myArray);
        include_once 'views/partials/footer.php';
    }

    public function renderAdminHeader()
    {
        echo '<h1 class="text-center">ManCave</h1>';
        echo '<h2 class="text-center">Admin</h2>';
        echo '<h3 class="text-center">Nav placeholder</h3>';
    }


    public function renderProductListStart()
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

    public function renderProductListEnd()
    {
        $html = <<<HTML

          </tbody>
        </table>
      </div>
    </div>

    HTML;

        echo $html;
    }

    public function renderProduct()
    {
        $html = <<<HTML

  <tr>
    <th scope="row">1</th>
    <td>Mark</td>
    <td>Otto</td>
    <td>@mdo</td>
  </tr>

HTML;

        echo $html;
    }
}
