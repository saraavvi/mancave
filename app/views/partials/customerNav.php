<?php
    if (!empty($_SESSION["loggedinuser"])) {
        $username = $_SESSION["loggedinuser"]["first_name"];
        $username .= " ";
        $username .= $_SESSION["loggedinuser"]["last_name"];
    }
?>

<header>
    <h1 class="text-center mt-5">ManCave</h1>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/mancave">Home</a>
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
                    <li>
                        <a class="nav-link" href="?page=shoppingcart">Shopping cart</a>
                    </li>
                </ul>
                <?php
                    if (empty($_SESSION["loggedinuser"])) {
                        echo '<div class="d-flex">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                                Log In
                            </button>
                        </div>';
                    } else {
                        echo '<div class="d-flex">
                            <span class="align-self-center px-3"> Logged in as ';
                        echo $username;
                        echo '</span><a type="button" class="btn btn-outline-primary"  href="?page=logout">
                            Log Out
                        </a></div>';
                    }
                ?>

                <!-- <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                    </form> -->
            </div>
        </div>
    </nav>
</header>

<?php
    if (empty($_SESSION["loggedinuser"])) {
        include_once "app/views/partials/loginModal.php";
    }
?>

<div class="row d-flex justify-content-center">