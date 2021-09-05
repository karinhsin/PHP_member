<?php
include __DIR__ . '/partials/init.php';
header('Content-Type: application/json');

$output = [
    'success' => false,
    'error' => '',
    'code' => 0,
    'rowCount' => 0,
    'postData' => $_POST,
];

// 資料格式檢查
if (!filter_var($_POST['account'], FILTER_VALIDATE_EMAIL)) {
    $output['error'] = 'email 格式錯誤';
    $output['code'] = 420;
    echo json_encode($output);
    exit;
}

$sql = "INSERT INTO `members`(
                `email`,`password`
               ) VALUES (
                     ?, ?
               )";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['account'],
    password_hash( $_POST['password'], PASSWORD_DEFAULT),
]);

$insertID = $pdo->lastInsertId();

$output['rowCount'] = $stmt->rowCount();
if ($stmt->rowCount() == 1) {
    $output['sid'] = $insertID;
    $output['success'] = true;
}

echo json_encode($output);
