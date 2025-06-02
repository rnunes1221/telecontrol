<?php
require_once 'data/db.php';

class ProductRepository {
    public function all() {
        $db = getDb();
        $stmt = $db->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     public function find($id) {
        $db = getDb();
        $stmt = $db->prepare("SELECT * FROM products where id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($data) {
        $db = getDb();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("INSERT INTO products (description, status, warranty_time) VALUES (?, ?, ?)");
            $stmt->execute([
                $data['description'],
                $data['status'],
                $data['warranty_time']
            ]);
            $db->commit();
        } 
        catch (PDOException $e) {
            $db->rollBack();
            throw new HttpException("Error: " . $e->getMessage(), 500);
        }
    }


    public function update($id, $data) {
        $db = getDb();

        try {
            $db->beginTransaction();
            $stmt = $db->prepare("UPDATE products SET description = ?, status = ?, warranty_time = ? WHERE id = ?");
            $stmt->execute([$data['description'], $data['status'], $data['warranty_time'], $id]);
            $db->commit();
            return $data;
        }
        catch (PDOException $e) {
            $db->rollBack();
            throw new HttpException("Error: " . $e->getMessage(), 500);
        }
    }

    public function delete($id) {
        $db = getDb();
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
