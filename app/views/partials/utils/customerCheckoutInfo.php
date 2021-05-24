<div class="row d-flex justify-content-center">
    <div class="col-md-8 border p-4 mt-5 mb-4">
        <dl class="row">
        <dt class="col-sm-4">Name:</dt>
        <dd class="col-sm-8"><?= $customer['first_name']?> <?= $customer['last_name']?></dd>
        
        <dt class="col-sm-4">Email:</dt>
        <dd class="col-sm-8"><?= $customer['email']?></dd>

        <dt class="col-sm-4">Shipping address:</dt>
        <dd class="col-sm-8"><?= $customer['address']?></dd>

        <dt class="col-sm-4">Payment method:</dt>
        <dd class="col-sm-8">Invoice 30 days</dd>
        </dl>
    </div>
</div>
