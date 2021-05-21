<?php
if (!empty($_SESSION["loggedinuser"])) {
    $username = $_SESSION["loggedinuser"]["first_name"];
    $username .= " ";
    $username .= $_SESSION["loggedinuser"]["last_name"];
}
?>


<header class="sticky-top">
    <div class=" border-bottom d-flex justify-content-end bg-light">
        <div class="d-flex px-3">
            <?php
            if (empty($_SESSION["loggedinuser"])) {
                echo '<div class="d-flex">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn fw-bold" data-bs-toggle="modal" data-bs-target="#loginModal">
                                Log In
                            </button>
                        </div>';
            } else {
                echo '<div class="d-flex">
                            <span class="align-self-center px-3"> Logged in as ';
                echo $username;
                echo '</span><a type="button" class="btn py-3 fw-bold" href="?page=logout">
                            Log out
                        </a></div>';
            }
            ?>
            <a class="nav-link" href="?page=shoppingcart"><i class="fa fa-shopping-cart fa-2x"></i>
            </a>
        </div>
    </div>
    <div class="bg-white rounded">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid text-center">
                <a class="navbar-brand fs-1 mx-5" href="?page=index">
                    <span class="brand">ManCave.<span class="brand-by">by</span><span class="brand-founder">Bob Franker Transatlantic Fellows</span></span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Products
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <!-- hämta kategorier från från databasen och skriv ut här istället sen -->
                                <li><a class="dropdown-item" href="?page=products&category=1">Hobbies</a></li>
                                <li><a class="dropdown-item" href="?page=products&category=2">Books</a></li>
                                <li><a class="dropdown-item" href="?page=products&category=3">Interior</a></li>
                                <li><a class="dropdown-item" href="?page=products&category=4">Health & Beauty</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Brands</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=about">About us</a>
                        </li>
                    </ul>

                    <!-- <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                    </form> -->
                </div>
            </div>
        </nav>
    </div>

</header>

<?php
if (empty($_SESSION["loggedinuser"])) {
    include_once "app/views/partials/loginModal.php";
}
?>

<div class="container">
    <div class="row d-flex justify-content-center">