<?php

namespace App\Models;

use mysqli;

class DBClass
{
    public static mysqli $connection;
    private array $config = [
        'hostname' => 'localhost',
        'username' => 'phpmyadmin',
        'password' => 'root',
        'dbname' => 'toDo-List'
    ];

    private function __construct()
    {
            $connection = new mysqli($this->config['hostname'], $this->config['username'], $this->config['password'], $this->config['dbname']);
            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            } else {
                self::$connection = $connection;
            }
    }

    public static function getConnection(): mysqli
    {
        if (!isset(self::$connection)) {
            new DBClass();
        }
        return self::$connection;
    }

    public static function closeConnection()
    {
        self::$connection->close();
    }
}