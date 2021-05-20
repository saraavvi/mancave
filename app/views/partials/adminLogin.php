<div class="row justify-content-center">
    <div class="col-md-6 col-sm-8 col-xs-12 p-4">
        <h3 class="modal-title text-center" id="loginModalLabel">Log in to your account:</h3>
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
</div>