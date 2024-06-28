<?php
namespace Project\Models;

/**Class TeamManager */
class TeamManager extends Manager {
    /**
     * Return all the teams
     */
    public function getAll(): array {
        $stmt = $this->db->query('SELECT * FROM team');
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Team::class);
    }

    /**
     * Return the team with matching id
     */
    public function find(int $id): Team|bool {
        $stmt = $this->db->prepare('SELECT * FROM team WHERE id = ?');
        $stmt->execute([
            $id
        ]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Team::class);
        return $stmt->fetch();
    }

    /**
     * Return the team with matching login
     */
    public function getByLogin(string $login): Team|bool {
        $stmt = $this->db->prepare('SELECT * FROM team WHERE login = ?');
        $stmt->execute([
            $login
        ]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Team::class);
        return $stmt->fetch();
    }

    /**
     * Store a team in the database
     */
    public function create(string $login, string $password): int {
        $stmt = $this->db->prepare('INSERT INTO team(login, password) VALUES (?,?)');
        $stmt->execute([
            $login,
            $password
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Update a team from the database
     */
    public function update(int $id, string $login, string $password): void {
        $stmt = $this->db->prepare('UPDATE team SET login = ?, password = ? WHERE id = ?');
        $stmt->execute([
            $login,
            $password,
            $id
        ]);
    }
}