<?php
namespace Project\Models;

/**
 * Class Manager
 */
abstract class Manager {
    /**
     * PDO object that represents database connexion
     */
    protected \PDO $db;

    /**
     * Init the connexion
     */
    public function __construct() {
        $this->db = new \PDO('mysql:host='.HOST.';dbname=' . DATABASE . ';charset=utf8;' , USER, PASSWORD);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Return the connexion
     */
    public function getDb(): \PDO {
        return $this->db;
    }
}