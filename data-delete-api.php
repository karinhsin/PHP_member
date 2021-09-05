<?php
include __DIR__ . '/partials/init.php';

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
$output = [
    'success' => false,
    'error' => '沒有給 sid',
    'sid' => $sid,
];

if (!empty($sid)) {
    $sql = "DELETE FROM `members` WHERE sid=$sid";
    $stmt = $pdo->query($sql);

    if ($stmt->rowCount() == 1) {
        $output['success'] = true;
        $output['error'] = '';
    } else {
        $output['error'] = '沒有刪除成功（可能沒有該筆資料）';
    }
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
