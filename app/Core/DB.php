<?php

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class DB
{
    private ?PDO $pdo = null;

    private static ?DB $instance = null;

    private ?PDOStatement $stmt = null;

    final private function __construct(public object $config)
    {
        try {
            $this->pdo = new PDO(
                "mysql:host={$config->host};port={$config->port};dbname={$config->database}",
                "$config->username",
                "$config->password"
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public static function instance(array $config = []): DB
    {
        $config = array_merge((new Config())->db, $config);

        if (self::shouldMakeNewConnection($config)) {
            self::$instance = new DB((object) $config);
        }

        return self::$instance;
    }

    public function query(string $sql, array $params = [])
    {
        $this->stmt = $this->pdo->prepare($sql);

        $this->stmt->execute($params);

        return $this;
    }

    public function insert(string $table, array $params = [])
    {
        if ($params) {
            $columns = implode(", ", array_map(fn ($param) => "`$param`", array_keys($params)));

            $binds = str_repeat("?,", count($params) - 1) . "?";

            $this->query("INSERT INTO $table ($columns) VALUES ($binds)", array_values($params));
        }

        return $this;
    }

    public function first()
    {
        return $this->stmt->fetch(PDO::FETCH_OBJ) ?: null;
    }

    public function get()
    {
        return $this->stmt->fetchAll(PDO::FETCH_CLASS);
    }

    public function count()
    {
        return $this->stmt->rowCount();
    }

    public function transaction(callable $callback)
    {
        $this->beginTransaction();

        try {
            $callback();
            $this->commit();
        } catch (\Throwable $e) {
            if ($this->inTransaction()) {
                $this->rollBack();
            }
            dd($e);
        }
    }

    public function disconnect()
    {
        $this->pdo = null;
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function __call($name, $arguments)
    {
        if (! method_exists(__CLASS__, $name)) {
            return call_user_func_array([$this->pdo, $name], $arguments);
        }
    }

    protected static function shouldMakeNewConnection($config)
    {
        return !self::$instance || array_diff($config, (array) self::$instance->config);
    }
}
