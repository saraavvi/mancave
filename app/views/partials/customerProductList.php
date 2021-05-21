<?php
foreach ($products as $product) {
    echo "
        <div class='col-xl-3 col-lg-4 col-md-5 col-sm-6 mt-3'>
            <div class='card' style='height: 30rem;'>
                <a href='?page=products/details&id=$product[id]'> 
                    <img src='$product[image]' class='card-img-top p-3' alt='$product[name]' style='height: 20rem; object-fit: contain;'>
                </a>
                <div class='card-body'>
                    <h5 class='card-title'>$product[name]</h5>
                    <p class='card-text'>$product[price] SEK</p>
                    <form action='?page=admin/products/delete' method='POST'>
                        <input type='hidden' name='product_id' value='$product[id]'>
                        <input type='hidden' name='product_name' value='$product[name]'>
                        <input type='hidden' name='current_page' value='$_SERVER[QUERY_STRING]'>
                        <button type='submit' name='add_to_cart' class='btn btn-primary'>Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>";

}
