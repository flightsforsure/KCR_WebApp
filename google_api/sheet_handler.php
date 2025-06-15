<?php
/**
 * Minimal Google Sheets API handler using a service account.
 * Works without external libraries so it can run on shared hosting.
 */
require_once __DIR__ . '/../config.php';

/**
 * Obtain an OAuth2 access token using a service account JSON.
 */
function google_get_access_token()
{
    $creds = json_decode(file_get_contents(SERVICE_ACCOUNT_FILE), true);
    $header = ['alg' => 'RS256', 'typ' => 'JWT'];
    $now = time();
    $claims = [
        'iss' => $creds['client_email'],
        'scope' => 'https://www.googleapis.com/auth/spreadsheets',
        'aud' => 'https://oauth2.googleapis.com/token',
        'exp' => $now + 3600,
        'iat' => $now
    ];
    $jwtHeader = base64_encode(json_encode($header));
    $jwtClaims = base64_encode(json_encode($claims));
    $signatureInput = $jwtHeader . '.' . $jwtClaims;
    openssl_sign($signatureInput, $signature, $creds['private_key'], 'sha256');
    $jwt = $signatureInput . '.' . base64_encode($signature);

    $post = http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ]);
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['access_token'] ?? null;
}

/**
 * Read all rows from the lead sheet.
 */
function sheet_get_all()
{
    $token = google_get_access_token();
    $url = 'https://sheets.googleapis.com/v4/spreadsheets/' . SHEET_ID . '/values/A1:Z1000?majorDimension=ROWS';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['values'] ?? [];
}

/**
 * Update a specific row.
 * $rowNumber is 1-indexed.
 */
function sheet_update_row($rowNumber, $rowData)
{
    $token = google_get_access_token();
    $range = 'A' . $rowNumber . ':Z' . $rowNumber;
    $body = json_encode(['range' => $range, 'majorDimension' => 'ROWS', 'values' => [$rowData]]);
    $url = 'https://sheets.googleapis.com/v4/spreadsheets/' . SHEET_ID . '/values/' . $range . '?valueInputOption=RAW';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}
?>
