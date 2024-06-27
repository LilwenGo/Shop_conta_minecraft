<?php
namespace Project\Models;

abstract class Manager {
    protected \PDO $db;

    public function __construct() {
        $this->db = new \PDO('mysql:host='.HOST.';dbname=' . DATABASE . ';charset=utf8;' , USER, PASSWORD);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getDb(): \PDO {
        return $this->db;
    }
}