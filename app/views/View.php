<?php
class View
{
    //HELPER METHODS:

    protected function renderHeader($title, $admin = false)
    {
        include_once "app/views/partials/head.php";
        if ($admin) {
            include_once "app/views/partials/adminNav.php";
        } else {
            include_once "app/views/partials/customerNav.php";
        }
    }

    protected function renderFooter()
    {
        include_once "app/views/partials/footer.php";
    }

    protected function renderAlerts($alerts)
    {
        foreach ($alerts as $category => $messages) {
            foreach ($messages as $message) {
                echo "
                    <div class='d-flex justify-content-center'>
                        <div class='col-md-10 text-center alert alert-$category' role='alert'>
                            $message
                        </div>
                    </div>
                ";
            }
        }
    }

    protected function renderButton($text, $href, $style = "primary")
    {
        $html = <<<HTML
                <div class="d-flex justify-content-center p-1">
                    <a class="btn btn-$style" href="$href">$text</a>
                </div>
            HTML;
        echo $html;
    }
}
