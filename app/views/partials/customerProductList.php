<?php
foreach ($products as $product) {
    echo "
        <div class='col-md-3 mt-3'>
            <div class='card' style='width: 18rem;'>
            <a href='?page=products/details&id=$product[id]'> 
                <img src='$product[image]' class='card-img-top p-3' alt='$product[name]'>
            </a>
            <div class='card-body'>
                <h5 class='card-title'>$product[name]</h5>
                <p class='card-text'>$product[price] sek</p>
                <form action='#' method='POST'>
                    <input type='hidden' name='product_id' value='$product[id]'>
                        <button type='submit' name='add_to_cart' class='btn btn-primary'>add to cart</button>
                    </form>
            </div>
        </div>
    </div>";

}
?>