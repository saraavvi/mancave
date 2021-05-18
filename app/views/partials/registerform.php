<!-- todo: anpassa så att formuläret kan användas som edit form också -->
<?php
$first_name = $customer_data['first_name'] ?? "";
$last_name = $customer_data['last_name'] ?? "";
$email = $customer_data['email'] ?? "";
$password = $customer_data['password'] ?? "";
$address = $customer_data['address'] ?? "";
?>

<div class="row d-flex justify-content-center">
    <div class="col-md-6 col-lg-4">
        <h3 class="text-center">Register as a ManCave Customer:</h3>
        <form action="#" method="POST">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $first_name ?>">
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $last_name ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" value="<?= $password ?>">
            </div>
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <textarea class="form-control" id="address" rows="3" id="address" name="address"><?= $address ?></textarea>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="btn btn-primary" type="button">Register</button>
            </div>
        </form>
    </div>
</div>