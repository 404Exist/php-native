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
                "host" => $_ENV["DB_HOST"] ?? "localhost",
                "port" => $_ENV["DB_PORT"] ?? "3306",
                "username" => $_ENV["DB_USERNAME"] ?? "root",
                "password" => $_ENV["DB_PASSWORD"] ?? "",
                "driver" => $_ENV["DB_DRIVER"] ?? "mysql",
                "charset" => "utf8",
                "collation" => "utf8_unicode_ci",
                "prefix" => "",
                'strict'    => false,
                'engine'    => null,
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
