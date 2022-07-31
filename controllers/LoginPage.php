<?php

namespace App\Controllers;

use App\Views\View;

class LoginPage
{
    private string $viewName;
    private View $view;

    public function __construct(string $viewName)
    {
        $this->viewName = $viewName;
        $this->view = new View();
    }

    public function showLoginPage(array $params = null): void
    {
       $this->view->renderTemplate($this->viewName);
        DBClass::getConnection()->close();
    }

    public function showLoginResult(array $params = null): void
    {
        $context["message"] = "You are not logged in";
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $_SESSION['is_admin'] = AdminUtils::isAdmin($_POST['login'], $_POST['password']);
        }
        $isAdmin = $_SESSION['is_admin'];
        if ($isAdmin) {
            $context["message"] = "You are logged in successful";
        }

        $this->view->renderTemplate("login_result_page", $context);
        DBClass::getConnection()->close();
    }

    public function logout(): void
    {
        unset($_SESSION['is_admin']);
        $previousPageUrl =  $_SERVER['HTTP_REFERER'] ?? "/";
        header('Location: ' . $previousPageUrl);
    }
}