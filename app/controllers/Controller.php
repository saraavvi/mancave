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
            if ($int) {
                return (int) $value;
            }
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
        $_SESSION["alerts"][$category][] = $message;
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

    protected function validateLoginForm(
        $user,
        $session_variable,
        $success_redirect
    ) {
        $current_page = $_POST["current_page"];
        if (empty($_POST["email"]) || empty($_POST["password"])) {
            $this->goToPageWithAlert(
                "Please enter username and password.",
                $current_page
            );
        }
        if (!$user) {
            $this->goToPageWithAlert(
                "Incorrect username/email.",
                $current_page
            );
        }
        $hashed_password = $user["password"];
        $entered_password = $_POST["password"];
        if (!password_verify($entered_password, $hashed_password)) {
            $this->goToPageWithAlert("Incorrect password.", $current_page);
        } else {
            //To prevent storing the password in session storage
            $user["password"] = null;
            $_SESSION[$session_variable] = $user;
            $this->goToPageWithAlert(
                "Successfully Logged In!",
                $success_redirect,
                "success"
            );
        }
        $this->goToPageWithAlert("Unexpected error!", $current_page);
    }

    protected function logOutAndGoToPage(
        $user = "customer",
        $page = "page=index"
    ) {
        unset($_SESSION["$user"]);
        $this->goToPageWithAlert("Successfully Logged Out!", $page, "success");
    }
}
