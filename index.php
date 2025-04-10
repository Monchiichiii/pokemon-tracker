<?php
session_start();

require_once 'config/google_config.php';
require_once 'app/controllers/CardController.php';
require_once 'app/controllers/CollectionController.php';
require_once 'app/controllers/HomeController.php';

// shows google login url so the user can login
$login_url = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
    "scope" => "email profile",
    "access_type" => "online",
    "include_granted_scopes" => "true",
    "response_type" => "code",
    "state" => "pass-through-value",
    "redirect_uri" => GOOGLE_REDIRECT_URI,
    "client_id" => GOOGLE_CLIENT_ID,
    "prompt" => "select_account" 
]);


$action = $_GET['action'] ?? 'home';

switch ($action) {
    //shows all the cards
    case 'browse':
        (new CardController())->index();
        break;

    case 'addCard':
        (new CardController())->addToCollection();
        break;

    case 'removeCard':
        (new CardController())->removeFromCollection();
        break;
        //shows the users collections
    case 'myCollection':
        (new CollectionController())->showMyCollection();
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php");
        exit;

    case 'home':
    default:
    //shows dashboard
        (new HomeController())->index(); 
        break;
}
