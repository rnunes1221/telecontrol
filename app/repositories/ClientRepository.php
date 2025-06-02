<?php
require_once 'data/db.php';

class ClientRepository {
    public function all() {
        $db = getDb();
        $stmt = $db->query("SELECT * FROM clients");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $db = getDb();
        $stmt = $db->prepare("SELECT * FROM clients where id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByCpf($cpf) {
        $db = getDb();
        $stmt = $db->prepare("SELECT * FROM clients WHERE cpf = ?");
        $stmt->execute([$cpf]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($data) {
        $db = getDb();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("INSERT INTO clients (name, cpf, address) VALUES (?, ?, ?)");
          
            $stmt->execute([
                isset($data['consumer_name']) ? $data['consumer_name'] :  $data['name'],
                isset($data['consumer_cpf']) ? $data['consumer_cpf'] : $data['cpf'],
                isset($data['address']) ? $data['address'] : null 
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
            $stmt = $db->prepare("UPDATE clients SET name = ?, cpf = ?, address = ? WHERE id = ?");
            $stmt->execute([$data['name'], $data['cpf'], $data['address'], $id]);
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
        $stmt = $db->prepare("DELETE FROM clients WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
