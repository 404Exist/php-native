<?php

namespace App\Core;

abstract class Model
{
    protected DB $db;

    protected $table = null;

    protected $primaryKey = "id";

    public function __construct()
    {
        $this->db = App::db()->table($this->table);
    }

    public function find(int $id)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?", [$id])->first() ?: null;
    }

    public function create(array $params = [])
    {
        $this->db->insert($params);

        return $this->find($this->db->lastInsertId());
    }

    public function connection(array $connection = [])
    {
        $this->db = DB::instance($connection)->table($this->table);

        return $this;
    }
}
