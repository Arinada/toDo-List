<?php

namespace App\Controllers;

class AdminUtils
{
    public static function isAdmin(string $login,string $password): bool
    {
        if ($login === "admin" && $password === "123") {
            return true;
        }
        return false;
    }
}