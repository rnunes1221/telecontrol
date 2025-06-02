<?php
require_once 'data/db.php'; 
class LogRepository {
    public function log($message) {
        $db = getDb();
        $stmt = $db->prepare("INSERT INTO logs (message, created_at) VALUES (?, NOW())");
        $stmt->execute([$message]);
    }
}
?>