<?php

$url = 'https://turnmark.github.io/api/v1/2026/20260627.json';

// JSON取得
$json = file_get_contents($url);
if ($json === false) {
    die('JSONの取得に失敗しました');
}

// 配列としてデコード
$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('JSONの解析に失敗しました: ' . json_last_error_msg());
}

// 配列の内容を確認
echo "<pre>";
var_dump($data['programs']['stadiums'][1]['races'][1]);
echo "</pre>";
