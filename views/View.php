<?php
class View
{
  public function renderHeader($title)
  {
    include_once 'views/partials/header.php';
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
    $this->renderForm();
    include_once 'views/partials/footer.php';
  }

  public function renderAdminHeader()
  {
    echo '<h1 class="text-center">ManCave</h1>';
    echo '<h2 class="text-center">Admin</h2>';
    echo '<h3 class="text-center">Nav placeholder</h3>';
  }

  public function renderForm()
  {
    $html = <<<HTML

    <div class="row d-flex justify-content-center">
      <div class="col-md-6 col-lg-4">
        <form>
          <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name">
          </div>
          <div class="mb-3">
            <label for="category" class="form-label">Category:</label>
            <select class="form-select" id="category" name="category">
              <option selected value="">Make a selection</option>
              <option value="1">Hobbies</option>
              <option value="2">Books</option>
              <option value="3">Interior Decoration</option>
              <option value="4">Health & Beauty</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="brand" class="form-label">Brand:</label>
            <select class="form-select" id="brand" name="brand">
              <option selected value="">Make a selection</option>
              <option value="1">First</option>
              <option value="2">Second</option>
              <option value="3">Third</option>
              <option value="4">Miscellaneous</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="price" class="form-label">Price:</label>
            <input type="number" class="form-control" id="price" name="price">
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" id="description" rows="3" id="description" name="description"></textarea>
          </div>
          <div class="mb-3">
            <label for="specification" class="form-label">Specification:</label>
            <textarea class="form-control" id="specification" rows="3" id="specification" name="specification"></textarea>
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">Image url:</label>
            <input type="text" class="form-control" id="image" name="image">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>

HTML;

    echo $html;
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
