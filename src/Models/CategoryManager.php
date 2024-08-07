<?php
namespace Project\Models;

/** Class CategoryManager */
class CategoryManager extends Manager {
    /**
     * Return all the categories
     */
    public function getAll(): array {
        $stmt = $this->db->query('SELECT * FROM category');
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Category::class);
    }

    /**
     * Return the category with matching id
     */
    public function find(int $id): Category|bool {
        $stmt = $this->db->prepare('SELECT * FROM category WHERE id = ?');
        $stmt->execute([
            $id
        ]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Category::class);
        return $stmt->fetch();
    }

    /**
     * Return the category with matching id_team
     */
    public function getFromTeam(int $id): array {
        $stmt = $this->db->prepare('SELECT * FROM category WHERE id_team = ?');
        $stmt->execute([
            $id
        ]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Category::class);
    }

    /**
     * Return the categories with matching id_membre
     */
    public function getFromMembre(int $id): array {
        $stmt = $this->db->prepare('SELECT id, id_team, libelle, role FROM category_membre JOIN category ON category_membre.id_category = category.id WHERE id_membre = ?');
        $stmt->execute([
            $id
        ]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Category::class);
    }

    /**
     * Store a category in the database
     */
    public function create(int $id_team, string $libelle): int {
        $stmt = $this->db->prepare('INSERT INTO category(id_team, libelle) VALUES (?,?)');
        $stmt->execute([
            $id_team,
            $libelle
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Update a category from the database
     */
    public function update(int $id, string $libelle): int {
        $stmt = $this->db->prepare('UPDATE category SET libelle = ? WHERE id = ?');
        $stmt->execute([
            $libelle,
            $id
        ]);
        return $stmt->rowCount();
    }

    /**
     * Delete a category from the database
     */
    public function delete(int $id, int $id_team): int {
        $stmt = $this->db->prepare('DELETE FROM category WHERE id = ? AND id_team = ?');
        $stmt->execute([
            $id,
            $id_team
        ]);
        return $stmt->rowCount();
    }
}