<?php

declare(strict_types=1);

namespace App;

use PDO;

/**
 * @mixin PDO
 */
class Db
{
    private PDO $pdo;

    public function __construct() {
        try {
            $this->pdo = new \PDO($_ENV['DB_DSN'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);

        } catch (\PDOException $e) {
            die('Подключение не удалось: ' . $e->getMessage());
        }
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->pdo, $name], $arguments);
    }

}