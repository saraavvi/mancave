<?php
$adminname = $_SESSION["loggedinadmin"]["name"]; ?>

<header>
    <h1 class="text-center mt-5">ManCave ADMIN</h1>
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

<!-- LoginModal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <h3 class="modal-title text-center" id="loginModalLabel">Log in to your account:</h3>
                <div class="d-flex justify-content-center">
                    <p class="text-muted">Placehoder for messages</p>
                </div>
                <form>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button type="submit" class="btn btn-primary" type="button">Log In</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light d-flex justify-content-center">
                <a class="" href="?page=register">
                    <span class="">New to Mancave? </span>
                    <span class="">Sign Up</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">