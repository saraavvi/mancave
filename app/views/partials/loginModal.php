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
                <form action="?page=login" method="POST">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
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