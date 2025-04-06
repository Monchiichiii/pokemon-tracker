<?php

require_once __DIR__ . '/../models/Collection.php';

class CollectionController {
    public function showMyCollection() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        require_once __DIR__ . '/../../config/config.php';

        $cards = [];
        $sets = [];

        try {
            $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $collectionModel = new Collection($pdo);
            $cardIds = $collectionModel->getCardIdsForUser($_SESSION['user_id']);

            $apiKey = '61a65de2-37c0-4afc-a39b-66bf7364bfe2';

            // ✅ Fetch card data from API
            foreach ($cardIds as $cardId) {
                $url = "https://api.pokemontcg.io/v2/cards/$cardId";
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => ["X-Api-Key: $apiKey"]
                ]);
                $response = curl_exec($ch);
                curl_close($ch);

                $cardData = json_decode($response, true);
                if (!empty($cardData['data'])) {
                    $cards[] = $cardData['data'];
                }
            }

            // ✅ Debug log (view in PHP error log or console)
            error_log("[DEBUG] Total cards fetched: " . count($cards));

            $selectedSet = $_GET['set_id'] ?? null;

            if ($selectedSet) {
                // 🔍 Filter cards by selected set
                $cards = array_filter($cards, function($card) use ($selectedSet) {
                    return ($card['set']['id'] ?? '') === $selectedSet;
                });

                error_log("[DEBUG] Filtered cards for set $selectedSet: " . count($cards));
            } else {
                // ✅ Group by set and count
                foreach ($cards as $card) {
                    $setId = $card['set']['id'] ?? 'unknown';

                    if (!isset($sets[$setId])) {
                        $setUrl = "https://api.pokemontcg.io/v2/sets/{$setId}";
                        $ch = curl_init();
                        curl_setopt_array($ch, [
                            CURLOPT_URL => $setUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_HTTPHEADER => ["X-Api-Key: $apiKey"]
                        ]);
                        $response = curl_exec($ch);
                        curl_close($ch);

                        $setData = json_decode($response, true)['data'] ?? [];

                        $sets[$setId] = [
                            'id' => $setId,
                            'name' => $setData['name'] ?? 'Unknown Set',
                            'logo' => $setData['images']['logo'] ?? '',
                            'total' => $setData['total'] ?? 0,
                            'collected' => 0
                        ];
                    }

                    $sets[$setId]['collected']++;
                }

                error_log("[DEBUG] Total sets: " . count($sets));
            }

        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            echo "Database Error: " . $e->getMessage();
        }

        include __DIR__ . '/../views/myCollection.php';
    }
}
