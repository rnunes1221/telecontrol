<?php
require_once 'data/db.php';

class OrderRepository {
    public function all() {
        $db = getDb();
        $stmt = $db->query("SELECT * FROM service_order");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $db = getDb();
        $stmt = $db->prepare("SELECT * FROM service_order where id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($data) {
        $db = getDb();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("INSERT INTO service_order (consumer_cpf, consumer_name, product_id) VALUES (?, ?, ?)");
            $stmt->execute([
                $data['consumer_cpf'],
                $data['consumer_name'],
                $data['product_id']
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
            $stmt = $db->prepare("UPDATE service_order SET consumer_cpf = ?, consumer_name = ?, product_id = ? WHERE id = ?");
            $stmt->execute([$data['consumer_cpf'], $data['consumer_name'], $data['product_id'], $id]);
            $db->commit();
            return $data;
        }
        catch (PDOException $e) {
            $db->rollBack();
            throw new HttpException("Error : " . $e->getMessage(), 500);
        }
    }

    public function delete($id) {
        $db = getDb();
        $stmt = $db->prepare("DELETE FROM service_order WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>