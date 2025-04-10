<?php

class Collection {
    private $pdo;
    private $apiKey = '61a65de2-37c0-4afc-a39b-66bf7364bfe2';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    //retrieves the cards id for the users collection
    public function getCardIdsForUser($userId) {
        $stmt = $this->pdo->prepare("SELECT card_id FROM user_collection WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getCardsForUser($userId) {
        $cardIds = $this->getCardIdsForUser($userId);
        $cards = [];

        foreach ($cardIds as $cardId) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => "https://api.pokemontcg.io/v2/cards/{$cardId}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ["X-Api-Key: {$this->apiKey}"]
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $cardData = json_decode($response, true)['data'] ?? null;

            if ($cardData) {
                $cards[] = $cardData;
            }
        }

        return $cards;
    }
}
