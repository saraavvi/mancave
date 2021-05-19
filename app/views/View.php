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

    protected function renderAlerts()
    {
        $alerts = $_SESSION['alerts'] ?? array();

        foreach ($alerts as $category => $messages) {
            foreach ($messages as $message) {
                include "partials/alert.php";
            }
        }
        $this->cleanAlerts();
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

    private function cleanAlerts() {
        $_SESSION['alerts'] = array();
    }
}
