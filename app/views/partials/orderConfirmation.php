
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 mt-5 card">
        <div class="card-body">
            <h3 class="card-title text-center">Order Successfully Placed!</h3>
            <p class="card-tex text-center">
                Thanks <?= $order['customer_name'] ?>!<br>
                We're happy to let you know that we've received your order.
            </p>
            <p class="card-tex text-center">
                Your order number is <?= $order['orders.id'] ?>.<br>
                Order details has been sent to <?= $order['customers.email'] ?>.
            </p>
            <p class="card-tex text-center">
                Once your package ships, we will send you an email with a tracking number and link so you can see the movement of your package.
            </p>
            <p class="card-tex text-center">
                If you need help with anything please don't hesitate to drop us an email:
            </p>
            <p class="card-tex text-center">
                <a href="mailto:contact@manca.ve">contact@manca.ve</a>
            </p>
            <p class="card-tex text-center">
                Peace out Bro/Brodette! 🤜
            </p>
        </div>
    </div>
</div>