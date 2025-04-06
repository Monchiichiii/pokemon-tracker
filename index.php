<?php
session_start();

require_once 'config/google_config.php';
require_once 'app/controllers/CardController.php';
require_once 'app/controllers/CollectionController.php';
require_once 'app/controllers/HomeController.php';

// Generate Google login URL for header or views
$login_url = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
    "scope" => "email profile",
    "access_type" => "online",
    "include_granted_scopes" => "true",
    "response_type" => "code",
    "state" => "pass-through-value",
    "redirect_uri" => GOOGLE_REDIRECT_URI,
    "client_id" => GOOGLE_CLIENT_ID,
    "prompt" => "select_account"  // ← forces Google to show account chooser
]);


$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'browse':
        (new CardController())->index(); // Show all cards
        break;

    case 'addCard':
        (new CardController())->addToCollection();
        break;

    case 'removeCard':
        (new CardController())->removeFromCollection();
        break;

    case 'myCollection':
        (new CollectionController())->showMyCollection();
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php");
        exit;

    case 'home':
    default:
        (new HomeController())->index(); // Show homepage/dashboard
        break;
}
