<!-- todo: anpassa så att formuläret kan användas som edit form också -->
<?php
$name = $data['name'] ?? "";
$category_id = $data['category_id'] ?? "";
$brand_id = $data['brand_id'] ?? "";
$price = $data['price'] ?? "";
$stock = $data['stock'] ?? "";
$description = $data['description'] ?? "";
$specification = $data['specification'] ?? "";
$image = $data['image'] ?? "";
?>

<div class="row d-flex justify-content-center">
    <div class="col-md-6 col-lg-4">
        <form action="#" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $name ?>">
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category:</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option selected value="" disabled hidden>Make a selection</option>
                    <?php
                    foreach ($categories as $category) {
                        $selected = ($data['category_id'] === $category['id']) ? "selected" : "";
                        echo "<option value='$category[id]' $selected >$category[name]</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="brand_id" class="form-label">Brand:</label>
                <select id="brand_id" name="brand_id" class="form-select">
                    <option selected value="" disabled hidden>Make a selection or add new</option>
                    <?php
                    foreach ($brands as $brand) {
                        $selected = ($data['brand_id'] === $brand['id']) ? "selected" : "";
                        echo "<option value='$brand[id]' $selected >$brand[name]</option>";
                    }
                    ?>
                    <option value='NEW'>Add New Brand</option>
                </select>
            </div>
            <div class='mb-3'>
                <label for='new_brand' class='form-label'>Add new brand:</label>
                <input type='text' class='form-control' id='new_brand' name='new_brand'>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="number" class="form-control" id="price" name="price" value="<?= $price ?>">
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock:</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?= $stock ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" id="description" rows="3" id="description" name="description"><?= $description ?></textarea>
            </div>
            <div class="mb-3">
                <label for="specification" class="form-label">Specification:</label>
                <textarea class="form-control" id="specification" rows="3" id="specification" name="specification"><?= $specification ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image url:</label>
                <input type="text" class="form-control" id="image" name="image" value="<?= $image ?>">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>