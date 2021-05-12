<?php
class View
{

    public function renderHeader($title)
    {
        include_once("views/partials/header.php");
    }

    public function renderFooter()
    {
        include_once("views/partials/footer.php");
    }

    public function renderAdminCreate()
    {
        include_once("views/partials/footer.php");
    }
}
