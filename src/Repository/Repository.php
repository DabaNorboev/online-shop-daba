<?php

namespace Repository;

use PDO;

class Repository
{
    protected static PDO $pdo;

    /**
     * @return PDO
     */
    public static function getPdo(): PDO
    {
        if (isset(self::$pdo)){
            return self::$pdo;
        }

        $dbName = getenv('DB_DATABASE');
        $dbUserName = getenv('DB_USERNAME');
        $dbPassword = getenv('DB_PASSWORD');

        self::$pdo = new PDO("pgsql:host=db; port=5432; dbname=" . $dbName, $dbUserName, $dbPassword);

        return self::$pdo;
    }
}