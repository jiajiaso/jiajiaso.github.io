<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$url = 'https://feapi.xyz/remen.php';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);

if(curl_errno($ch)){
    http_response_code(500);
    echo json_encode(['error' => '请求失败: '.curl_error($ch)]);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($httpCode);
echo $response;
