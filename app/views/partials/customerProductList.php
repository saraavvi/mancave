<?php
foreach ($products as $product) {
    // print_r($product['stock']);
    $status = "";
    if (!$product['stock'] > 0) {
        $status = 'disabled';
    }
    echo "
        <div class='col-xl-3 col-lg-4 col-md-5 col-sm-6 mt-3 mb-5'>
            <div class='card bg-light border-0' style='min-height: 30vh; height: 100%;'>
                <a href='?page=products/details&id=$product[id]'> 
                    <img src='$product[image]' class='card-img-top p-3' alt='$product[name]' style='height: 20rem; object-fit: contain;'>
                </a>
                <div class='card-body d-flex flex-column'>
                    <h5 class='card-title'>$product[name]</h5>
                    <p class='card-text'>$product[price] SEK</p>
                    <form class='mt-auto' action='#' method='POST'>
                        <input type='hidden' name='product_id' value='$product[id]'>
                        <input type='hidden' name='product_name' value='$product[name]'>
                        <input type='hidden' name='current_page' value='$_SERVER[QUERY_STRING]'>
<button type='submit' name='add_to_cart' class='btn btn-outline-dark' $status>Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>";
}
