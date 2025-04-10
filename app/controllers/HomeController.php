<?php

//this controller takes you to the home page
class HomeController {
    public function index() {
        global $login_url; 
        include __DIR__ . '/../views/home.php';
    }
}
