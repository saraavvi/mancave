<?php

class Controller
{
    protected function conditionForExit($condition)
    {
        if ($condition) {
            echo "Page not found";
            exit();
        }
    }

    /**
     * Expects name of post key, 
     * optional bool (true for int values, default false) 
     * returns value or false
     */
    protected function getAndValidatePost($name, $int = false)
    {
        if (isset($_POST[$name])) {
            $value = $this->sanitize($_POST[$name]);
            if ($int) return (int)$value;
            return $value;
        }
        return false;
    }

    protected function sanitize($text)
    {
        $text = trim($text);
        $text = stripslashes($text);
        $text = htmlspecialchars($text);
        return $text;
    }

    /**
     * Store new alerts in session.
     * View method will clean up after rendering
     */
    protected function setAlert($category, $message)
    {
        $_SESSION['alerts'][$category][] = $message;
    }

    protected function goToPageWithAlert(
        $message,
        $page = "page=index",
        $style = "danger"
    ) {
        $this->setAlert($style, $message);
        header("Location: ?$page");
        exit();
    }
}
