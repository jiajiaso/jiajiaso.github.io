<?php
header('Content-Type: application/json; charset=utf-8');

$url = 'https://so.slowread.net/';
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) ".
                    "AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"
    ]
];
$context = stream_context_create($opts);
$html = file_get_contents($url, false, $context);

if ($html === false) {
    http_response_code(500);
    echo json_encode(['error' => '无法获取网页内容']);
    exit;
}

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);

// 选中热门搜索词的 a 标签：
// 在 class 为 hot-search-keywords 的 div 下的所有 a 标签
$nodes = $xpath->query("//section[contains(@class,'hot-search-section')]//div[contains(@class,'hot-search-keywords')]/a");

$hot_searches = [];
foreach ($nodes as $node) {
    $hot_searches[] = trim($node->textContent);
}

echo json_encode(['hot_searches' => $hot_searches], JSON_UNESCAPED_UNICODE);
