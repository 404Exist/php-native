<?php

namespace App\Core;

abstract class Model
{
    protected DB $db;

    protected $table = null;

    protected $primaryKey = "id";

    protected $connection = [];

    public function __construct()
    {
        $this->db = DB::instance($this->connection);
    }

    public function find(int $id)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?", [$id])->first() ?: null;
    }

    public function get(array $columns = [])
    {
        $columns = implode(", ", array_map(fn ($column) => "`$column`", $columns)) ?: "*";

        return $this->db->query("SELECT $columns FROM {$this->table}")->get();
    }

    public function create(array $params = [])
    {
        $this->db->insert($this->table, $params);

        return $this->find($this->db->lastInsertId());
    }

    public function connection(array $connection = [])
    {
        $this->db = DB::instance($connection);

        return $this;
    }
}
