<?php
session_start();

require_once 'config/google_config.php';
require_once 'config/config.php';
require_once 'app/models/User.php';

try {
    //goes to the database
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['code'])) {
        throw new Exception("No code provided from Google OAuth.");
    }

    //access token
    $tokenUrl = "https://oauth2.googleapis.com/token";
    $data = [
        "code" => $_GET['code'],
        "client_id" => GOOGLE_CLIENT_ID,
        "client_secret" => GOOGLE_CLIENT_SECRET,
        "redirect_uri" => GOOGLE_REDIRECT_URI,
        "grant_type" => "authorization_code"
    ];

    $ch = curl_init($tokenUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new Exception("cURL error: " . curl_error($ch));
    }
    curl_close($ch);

    $tokenData = json_decode($response, true);
    if (empty($tokenData['access_token'])) {
        throw new Exception("No access token received. Full response: " . $response);
    }

    $access_token = $tokenData['access_token'];

    //gets users google information
    $userInfo = file_get_contents("https://www.googleapis.com/oauth2/v2/userinfo?access_token={$access_token}");
    if (!$userInfo) {
        throw new Exception("Failed to fetch user info from Google.");
    }

    $user = json_decode($userInfo, true);
    if (empty($user['id'])) {
        throw new Exception("Incomplete user info: " . $userInfo);
    }

    //updates the users information into the database
    $userModel = new User($pdo);
    $userModel->findOrCreateUser(
        $user['id'],            
        $user['name'],          
        $user['email'],
        $user['picture'] ?? '' 
    );

    //grabs the users id
    $stmt = $pdo->prepare("SELECT id FROM users WHERE google_id = ?");
    $stmt->execute([$user['id']]);
    $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dbUser) {
        throw new Exception("Failed to retrieve user after insert.");
    }

    //saves
    $_SESSION['user'] = $user;
    $_SESSION['user_id'] = $dbUser['id'];

    //goes back to the dashboard
    header("Location: index.php");
    exit;

} catch (Exception $e) {
    echo "<pre>Error: " . htmlspecialchars($e->getMessage()) . "</pre>";
    file_put_contents("oauth_error_log.txt", "[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . "\n", FILE_APPEND);
}
