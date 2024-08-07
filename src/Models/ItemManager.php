<?php
namespace Project\Models;

/** Class ItemManager */
class ItemManager extends Manager {
    /**
     * Return all the items
     */
    public function getAll(): array {
        $stmt = $this->db->query('SELECT item.id AS id, item.id_team AS id_team, category.libelle AS category, item.libelle AS libelle, price, total_selled FROM item JOIN category ON item.id = category.id_item');
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Item::class);
    }

    /**
     * Return the item with matching id
     */
    public function find(int $id): Item|bool {
        $stmt = $this->db->prepare('SELECT item.id AS id, item.id_team AS id_team, category.libelle AS category, item.libelle AS libelle, price, total_selled FROM item JOIN category ON item.id_category = category.id WHERE item.id = ?');
        $stmt->execute([
            $id
        ]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Item::class);
        return $stmt->fetch();
    }

    /**
     * Return the item with matching id_team
     */
    public function getFromTeam(int $id): array {
        $stmt = $this->db->prepare('SELECT item.id AS id, item.id_team AS id_team, category.libelle AS category, item.libelle AS libelle, price, total_selled FROM item JOIN category ON item.id_category = category.id WHERE item.id_team = ?');
        $stmt->execute([
            $id
        ]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Item::class);
    }

    /**
     * Return the items with matching id_membre
     */
    public function getFromMembre(int $id): array {
        $stmt = $this->db->prepare('SELECT item.id AS id, item.id_team AS id_team, category.libelle AS category, item.libelle AS libelle, price, total_selled, quantity, refunded FROM item JOIN category ON item.id_category = category.id JOIN item_membre ON item_membre.id_item = item.id WHERE id_membre = ?');
        $stmt->execute([
            $id
        ]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Item::class);
    }

    /**
     * Store a item in the database
     */
    public function create(int $id_team, int $id_category, string $libelle, float $price, int $total_selled): int {
        $stmt = $this->db->prepare('INSERT INTO item(id_team, id_category, libelle, price, total_selled) VALUES (?,?,?,?,?)');
        $stmt->execute([
            $id_team,
            $id_category,
            $libelle,
            $price,
            $total_selled
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Update a item from the database
     */
    public function update(int $id, string $libelle, float $price, int $total_selled): int {
        $stmt = $this->db->prepare('UPDATE item SET libelle = ?, price = ?, total_selled = ? WHERE id = ?');
        $stmt->execute([
            $libelle,
            $price,
            $total_selled,
            $id
        ]);
        return $stmt->rowCount();
    }

    /**
     * Delete a item from the database
     */
    public function delete(int $id, int $id_team): int {
        $stmt = $this->db->prepare('DELETE FROM item WHERE id = ? AND id_team = ?');
        $stmt->execute([
            $id,
            $id_team
        ]);
        return $stmt->rowCount();
    }

    /**
     * Only update the total_selled of the item
     */
    public function updateTotal(int $id, int $total_selled): int {
        $stmt = $this->db->prepare('UPDATE item SET total_selled = ? WHERE id = ?');
        $stmt->execute([
            $total_selled,
            $id
        ]);
        return $stmt->rowCount();
    }
}