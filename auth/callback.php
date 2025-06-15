<?php
session_start();
require_once __DIR__ . '/../config.php';

// Verify state parameter
if (!isset($_GET['state']) || $_GET['state'] !== ($_SESSION['oauth_state'] ?? '')) {
    die('Invalid state');
}

// Exchange authorization code for access token
$code = $_GET['code'] ?? '';
$post = [
    'code' => $code,
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'grant_type' => 'authorization_code'
];
$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);

if (!isset($data['access_token'])) {
    die('Authentication failed');
}
$accessToken = $data['access_token'];

// Retrieve user info
$ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $accessToken"]);
$infoResponse = curl_exec($ch);
curl_close($ch);
$userInfo = json_decode($infoResponse, true);

$_SESSION['user'] = [
    'email' => $userInfo['email'] ?? '',
    'name' => $userInfo['name'] ?? '',
];

// Redirect to dashboard based on role
$email = $_SESSION['user']['email'];
if (strpos($email, '@') !== false && stripos($email, 'admin') !== false) {
    header('Location: /crm/admin/dashboard.php');
} else {
    header('Location: /crm/broker/dashboard.php');
}
exit;
?>
