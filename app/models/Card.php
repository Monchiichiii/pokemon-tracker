<?php

//cards and Pokemon TCG API
class Card {
    private $apiBase = "https://api.pokemontcg.io/v2/cards";
    private $apiKey = "61a65de2-37c0-4afc-a39b-66bf7364bfe2"; 

    public function getAllCards($page = 1, $pageSize = 20, $query = null, $orderBy = 'name', $set = null) {
        $url = $this->apiBase . "?page=$page&pageSize=$pageSize";

        $qParts = [];

        if ($query) {
            $qParts[] = "name:$query";
        }

        if ($set) {
            $qParts[] = "set.id:$set";
        }

        if (!empty($qParts)) {
            $url .= "&q=" . urlencode(implode(" AND ", $qParts));
        }

        if ($orderBy) {
            $url .= "&orderBy=" . urlencode($orderBy);
        }

        $headers = ["X-Api-Key: " . $this->apiKey];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        return $data['data'] ?? [];
    }

    //retrieves all sets
    public function getAllSets() {
        $url = "https://api.pokemontcg.io/v2/sets?orderBy=releaseDate";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["X-Api-Key: " . $this->apiKey]
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        return $data['data'] ?? [];
    }
}

