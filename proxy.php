<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // 允许跨域，如果你需要限制来源，可改成具体域名

$url = $_GET['url'] ?? '';
if (!$url) {
    echo json_encode(['error' => '缺少url参数']);
    exit;
}

// 简单白名单防止滥用
$allowedHosts = ['feapi.xyz'];
$parsed = parse_url($url);
if (!$parsed || !in_array($parsed['host'], $allowedHosts)) {
    echo json_encode(['error' => '不允许访问的域名']);
    exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$data = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo json_encode(['error' => '请求错误: ' . $err]);
} else {
    echo $data;
}
