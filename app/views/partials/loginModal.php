<!-- LoginModal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <h3 class="modal-title text-center" id="loginModalLabel">Log in to your account:</h3>
                <form action="?page=login" method="POST">
                    <?php include_once "loginFormContent.php"; ?>
                </form>
            </div>
            <div class="modal-footer bg-light d-flex justify-content-center">
                <a class="" href="?page=register">
                    <span class="">New to Mancave?</span>
                    <span class="">Sign Up</span>
                </a>
            </div>
        </div>
    </div>
</div>