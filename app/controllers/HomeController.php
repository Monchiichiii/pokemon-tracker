<?php

class HomeController {
    public function index() {
        global $login_url; // <- add this line to make the variable available
        include __DIR__ . '/../views/home.php';
    }
}
