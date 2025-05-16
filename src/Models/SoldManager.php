<?php
namespace Project\Models;

/** Class SoldManager */
class SoldManager extends Manager {
    /**
     * Return all the items
     */
    public function getAll(): array {
        $stmt = $this->db->query('SELECT id_item, item.libelle AS item, id_membre, membre.name AS membre, quantity, refunded FROM item_membre JOIN item ON item.id = item_membre.id_item JOIN membre ON membre.id = item_membre.id_membre');
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Sold::class);
    }

    /**
     * Return the sold with matching id_item
     */
    public function find(int $id_item, int $id_membre): Sold|false {
        $stmt = $this->db->prepare('SELECT id_item, item.libelle AS item, id_membre, membre.name AS membre, quantity, refunded FROM item_membre JOIN item ON item.id = item_membre.id_item JOIN membre ON membre.id = item_membre.id_membre WHERE id_item = ? AND id_membre = ?');
        $stmt->execute([
            $id_item,
            $id_membre
        ]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Sold::class);
        return $stmt->fetch();
    }

    /**
     * Return the sold with matching id_item
     */
    public function getFromTeam(int $id): array {
        $stmt = $this->db->prepare('SELECT id_item, item.libelle AS item, id_membre, membre.name AS membre, quantity, refunded FROM item_membre JOIN item ON item.id = item_membre.id_item JOIN membre ON membre.id = item_membre.id_membre JOIN team ON item.id_team = team.id WHERE item.id_team = ?');
        $stmt->execute([
            $id
        ]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Sold::class);
    }

    /**
     * Return the sold with matching id_item
     */
    public function getFromItem(int $id): array {
        $stmt = $this->db->prepare('SELECT id_item, item.libelle AS item, id_membre, membre.name AS membre, quantity, refunded FROM item_membre JOIN item ON item.id = item_membre.id_item JOIN membre ON membre.id = item_membre.id_membre WHERE id_item = ?');
        $stmt->execute([
            $id
        ]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Sold::class);
    }

    /**
     * Return the sold with matching id_membre
     */
    public function getFromMembre(int $id): array {
        $stmt = $this->db->prepare('SELECT id_item, item.libelle AS item, id_membre, membre.name AS membre, quantity, refunded FROM item_membre JOIN item ON item.id = item_membre.id_item JOIN membre ON membre.id = item_membre.id_membre WHERE id_item = ?');
        $stmt->execute([
            $id
        ]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Sold::class);
    }

    /**
     * Return the sold with matching id_category
     */
    public function getFromCategory(int $id): array {
        $stmt = $this->db->prepare('SELECT id_item, item.libelle AS item, id_membre, membre.name AS membre, quantity, refunded FROM item_membre JOIN item ON item.id = item_membre.id_item JOIN membre ON membre.id = item_membre.id_membre WHERE id_category = ?');
        $stmt->execute([
            $id
        ]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Sold::class);
    }

    /**
     * Create a sold
     */
    public function create(int $id_item, int $id_membre, int $quantity, int $refunded): int {
        $stmt = $this->db->prepare('INSERT INTO item_membre(id_item, id_membre, quantity, refunded) VALUES (?,?,?,?)');
        $stmt->execute([
            $id_item,
            $id_membre,
            $quantity,
            $refunded
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Update the sold
     */
    public function update(int $id_item, int $id_membre, int $quantity, int $refunded): int {
        $stmt = $this->db->prepare('UPDATE item_membre SET quantity = ?, refunded = ? WHERE id_item = ? AND id_membre = ?');
        $stmt->execute([
            $quantity,
            $refunded,
            $id_item,
            $id_membre
        ]);
        return $stmt->rowCount();
    }

    /**
     * Delete the sold
     */
    public function delete(int $id_item, int $id_membre): int {
        $stmt = $this->db->prepare('DELETE FROM item_membre WHERE id_item = ? AND id_membre = ?');
        $stmt->execute([
            $id_item,
            $id_membre
        ]);
        return $stmt->rowCount();
    }
}