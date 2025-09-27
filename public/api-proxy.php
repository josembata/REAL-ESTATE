<?php
// public/api-proxy.php
header('Access-Control-Allow-Origin: http://127.0.0.1:8000');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$path = isset($_GET['path']) ? $_GET['path'] : '';
$apiBaseUrl = 'http://127.0.0.1:500';

if (empty($path)) {
    http_response_code(400);
    echo json_encode(['error' => 'Path parameter is required']);
    exit;
}

$url = $apiBaseUrl . '/api/' . $path;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($httpCode);
echo $response;
?>