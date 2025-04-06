<?php

require_once __DIR__ . '/../models/Card.php';

class CardController
{
    public function index() {
        $query = $_GET['q'] ?? null;
        $orderBy = $_GET['orderBy'] ?? 'name';
        $set = $_GET['set'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    
        $cardModel = new Card();
        $cards = $cardModel->getAllCards($page, 20, $query, $orderBy, $set);
        $sets = $cardModel->getAllSets(); // <- fetch sets
    
        $userCollection = $_SESSION['user_collection'] ?? [];
    
        include __DIR__ . '/../views/browse.php';
    }

    public function addToCollection()
    {
        require_once __DIR__ . '/../../config/config.php';

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Invalid request.";
            return;
        }

        $userId = $_SESSION['user_id'];
        $cardId = $_POST['card_id'] ?? null;

        if (!$cardId) {
            echo "Missing card ID.";
            return;
        }

        try {
            $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insert into user_collection only
            $stmt = $pdo->prepare("INSERT IGNORE INTO user_collection (user_id, card_id) VALUES (?, ?)");
            $stmt->execute([$userId, $cardId]);

            // Refresh user's collection in session
            $refresh = $pdo->prepare("SELECT card_id FROM user_collection WHERE user_id = ?");
            $refresh->execute([$userId]);
            $_SESSION['user_collection'] = $refresh->fetchAll(PDO::FETCH_COLUMN);

            header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php?action=browse'));
            exit;

        } catch (PDOException $e) {
            echo "DB Error: " . $e->getMessage();
        }
    }

    public function removeFromCollection()
    {
        require_once __DIR__ . '/../../config/config.php';

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Invalid request.";
            return;
        }

        $userId = $_SESSION['user_id'];
        $cardId = $_POST['card_id'] ?? null;

        if (!$cardId) {
            echo "Missing card ID.";
            return;
        }

        try {
            $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("DELETE FROM user_collection WHERE user_id = ? AND card_id = ?");
            $stmt->execute([$userId, $cardId]);

            // Refresh user's collection in session
            $refresh = $pdo->prepare("SELECT card_id FROM user_collection WHERE user_id = ?");
            $refresh->execute([$userId]);
            $_SESSION['user_collection'] = $refresh->fetchAll(PDO::FETCH_COLUMN);

            header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php?action=myCollection'));
            exit;

        } catch (PDOException $e) {
            echo "DB Error: " . $e->getMessage();
        }
    }
}
