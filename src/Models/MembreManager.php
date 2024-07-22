<?php
namespace Project\Models;

/**Class MembreManager */
class MembreManager extends Manager {
    /**
     * Return all the membres
     */
    public function getAll(): array {
        $stmt = $this->db->query('SELECT * FROM membre');
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Membre::class);
    }

    /**
     * Return the membre with matching id
     */
    public function find(int $id): Membre|bool {
        $stmt = $this->db->prepare('SELECT * FROM membre WHERE id = ?');
        $stmt->execute([
            $id
        ]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Membre::class);
        return $stmt->fetch();
    }

    /**
     * Return the membre with matching id_team
     */
    public function getFromTeam(int $id): array {
        $stmt = $this->db->prepare('SELECT * FROM membre WHERE id_team = ?');
        $stmt->execute([
            $id
        ]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Membre::class);
    }

    /**
     * Store a membre in the database
     */
    public function create(int $id_team,string $name): int {
        $stmt = $this->db->prepare('INSERT INTO membre(id_team, name) VALUES (?,?)');
        $stmt->execute([
            $id_team,
            $name
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Update a membre from the database
     */
    public function update(int $id, string $name): void {
        $stmt = $this->db->prepare('UPDATE membre SET name = ? WHERE id = ?');
        $stmt->execute([
            $name,
            $id
        ]);
    }
}