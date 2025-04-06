<?php
session_start();

require_once 'config/google_config.php';
require_once 'config/config.php';
require_once 'app/models/User.php';

try {
    // Step 1: Connect to the database
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Step 2: Check for OAuth code
    if (!isset($_GET['code'])) {
        throw new Exception("No code provided from Google OAuth.");
    }

    // Step 3: Exchange code for access token
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

    // Step 4: Retrieve user info from Google
    $userInfo = file_get_contents("https://www.googleapis.com/oauth2/v2/userinfo?access_token={$access_token}");
    if (!$userInfo) {
        throw new Exception("Failed to fetch user info from Google.");
    }

    $user = json_decode($userInfo, true);
    if (empty($user['id'])) {
        throw new Exception("Incomplete user info: " . $userInfo);
    }

    // Step 5: Insert or update user in DB
    $userModel = new User($pdo);
    $userModel->findOrCreateUser(
        $user['id'],            // google_id
        $user['name'],          // username
        $user['email'],
        $user['picture'] ?? ''  // profile_pic
    );

    // Step 6: Get local user ID
    $stmt = $pdo->prepare("SELECT id FROM users WHERE google_id = ?");
    $stmt->execute([$user['id']]);
    $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dbUser) {
        throw new Exception("Failed to retrieve user after insert.");
    }

    // Step 7: Save to session
    $_SESSION['user'] = $user;
    $_SESSION['user_id'] = $dbUser['id'];

    // Step 8: Redirect to app
    header("Location: index.php");
    exit;

} catch (Exception $e) {
    echo "<pre>Error: " . htmlspecialchars($e->getMessage()) . "</pre>";
    file_put_contents("oauth_error_log.txt", "[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . "\n", FILE_APPEND);
}
