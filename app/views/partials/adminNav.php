<?php
$adminname = $_SESSION["loggedinadmin"]["name"]; ?>

<header>
    <h1 class="text-center mt-5"><a href="?page=admin" class="header-name">ManCave ADMIN</a></h1>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="?page=admin">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=admin/orders">Orders</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <span class="align-self-center px-3"> Logged in as <?= $adminname ?></span>
                    <a type="button" class="btn btn-outline-primary"  href="?page=admin/logout">
                        Log Out
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="row">