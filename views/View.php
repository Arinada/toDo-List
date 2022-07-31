<?php

namespace App\Views;

class View
{
    private \Twig\Environment $twig;

    public function __construct()
    {
        if (!isset($this->twig)) {
                $loader = new \Twig\Loader\FilesystemLoader( __DIR__ . '/templates');
                $this->twig = new \Twig\Environment($loader);
        }
    }

    public function renderTemplate($viewName, $context = [], $authorized = null)
    {
        $templateName = $this->normalizeName($viewName) . ".html";
        $template = $this->twig->load($templateName);
        echo $template->render($context);
    }

    private function normalizeName($name)
    {
        $normalizedName = strtolower($name[0]);
        for ($i = 1; $i < strlen($name); $i++) {
            if (ctype_upper($name[$i])) {
                $normalizedName = $normalizedName . '_';
            }
            $normalizedName = $normalizedName . strtolower($name[$i]);
        }
        return $normalizedName;
    }
}