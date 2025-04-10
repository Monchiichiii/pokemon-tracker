<?php


//gets the users information and stores into the database
class User {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function findOrCreateUser($google_id, $name, $email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE google_id = ?");
        $stmt->execute([$google_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $user['id'];
        } else {
            $stmt = $this->db->prepare("INSERT INTO users (google_id, name, email) VALUES (?, ?, ?)");
            $stmt->execute([$google_id, $name, $email]);
            return $this->db->lastInsertId();
        }
    }
}
