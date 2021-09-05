<?php
include __DIR__ . '/partials/init.php';

// 輸出的格式
$output = [
    'success' => false,
    'error' => '',
    'code' => 0,
];

// 判斷有沒有帳號和密碼
if (!isset($_POST['account']) or !isset($_POST['password'])) {
    $output['error'] = '沒有帳號資料或沒有密碼';
    $output['code'] = 400;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit; // 直接離開 (中斷) 程式
}

$sql = "SELECT * FROM members WHERE email=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_POST['account']]);
$m = $stmt->fetch();

// 查看有沒有這個帳號
if (empty($m)) {
    $output['error'] = '帳號錯誤';
    $output['code'] = 401;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!password_verify($_POST['password'], $m['password'])) {
    $output['error'] = '密碼錯誤';
    $output['code'] = 405;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit; // 直接離開 (中斷) 程式
}

$output['success'] = true;
$output['code'] = 200;
$output['sid'] = $m['sid'];

$_SESSION['user'] = $m;

echo json_encode($output, JSON_UNESCAPED_UNICODE);
