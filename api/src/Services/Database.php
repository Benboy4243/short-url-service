<?php

class Database
{
    public static function connect(): PDO
    {
        $config = require __DIR__ . '/../Config/database.php';

        return new PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
            $config['user'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
}
