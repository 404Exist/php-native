<?php

namespace App\Core;

/**
 * @property-read ?array $db
 */
class Config
{
    protected array $config = [];

    public function __construct()
    {
        $this->config = [
            "db" => [
                "database" => $_ENV["DB_DATABASE"] ?? "",
                "host" => $_ENV["DB_HOST"] ?? "127.0.0.1",
                "port" => $_ENV["DB_PORT"] ?? "3306",
                "user" => $_ENV["DB_USERNAME"] ?? "root",
                "pass" => $_ENV["DB_PASSWORD"] ?? "",
            ],
            "mailer" => [
                "dsn" => $_ENV["MAIL_DSN"] ?? "smtp://mailhog:1025",
            ]
        ];
    }

    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }
}
