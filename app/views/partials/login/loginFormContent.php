<div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
</div>
<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password">
</div>
<div class="d-grid gap-2 col-6 mx-auto">
    <input type="hidden" name="current_page" value="<?= $_SERVER[
        "QUERY_STRING"
    ] ?>">
    <button type="submit" class="btn btn-primary" type="button">Log In</button>
</div>