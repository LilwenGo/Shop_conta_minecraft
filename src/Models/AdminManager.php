<?php
namespace Project\Models;

/**Class AdminManager */
class AdminManager extends Manager {
    /**
     * Return all the admins
     */
    public function getAll(): array {
        $stmt = $this->db->query('SELECT * FROM admin');
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Admin::class);
    }

    /**
     * Return the admin with matching id
     */
    public function find(int $id): Admin|bool {
        $stmt = $this->db->prepare('SELECT * FROM admin WHERE id = ?');
        $stmt->execute([
            $id
        ]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Admin::class);
        return $stmt->fetch();
    }

    /**
     * Return the admin with matching id_team
     */
    public function getFromTeam(int $id): array {
        $stmt = $this->db->prepare('SELECT * FROM admin WHERE id_team = ?');
        $stmt->execute([
            $id
        ]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Admin::class);
    }

    /**
     * Return the admin with matching login
     */
    public function getByLogin(string $login): Admin|bool {
        $stmt = $this->db->prepare('SELECT * FROM admin WHERE login = ? AND id_team = ?');
        $stmt->execute([
            $login,
            $_SESSION['team']['id']
        ]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Admin::class);
        return $stmt->fetch();
    }

    /**
     * Store a admin in the database
     */
    public function create(int $id_team, string $login, string $password): int {
        $stmt = $this->db->prepare('INSERT INTO admin(id_team, login, password) VALUES (?,?,?)');
        $stmt->execute([
            $id_team,
            $login,
            $password
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Update a admin from the database
     */
    public function update(int $id, string $login, string $password): void {
        $stmt = $this->db->prepare('UPDATE admin SET login = ?, password = ? WHERE id = ?');
        $stmt->execute([
            $login,
            $password,
            $id
        ]);
    }

    /**
     * Delete an admin from the database
     */
    public function delete(int $id, int $id_team): int {
        $stmt = $this->db->prepare('DELETE FROM admin WHERE id = ? AND id_team = ?');
        $stmt->execute([
            $id,
            $id_team
        ]);
        return $stmt->rowCount();
    }
}