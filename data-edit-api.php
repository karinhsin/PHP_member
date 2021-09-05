<?php
include __DIR__ . '/partials/init.php';
header('Content-Type: application/json');

//要存放圖檔的資料夾，上傳後的大頭貼放在img資料夾
$folder = __DIR__ . '/imgs/';

// 允許的檔案類型
$imgTypes = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
];

$output = [
    'success' => false,
    'error' => '資料欄位不足',
    'code' => 0,
    'rowCount' => 0,
    'postData' => $_POST,
];

if (
    empty($_POST['sid']) or
    empty($_POST['name']) or
    empty($_POST['email']) or
    empty($_POST['password']) or
    empty($_POST['mobile']) or
    empty($_POST['birthday']) or
    empty($_POST['address'])
) {
    echo json_encode($output);
    exit;
}

// 預設是沒有上傳資料，沒有上傳成功
$isSaved = false;

// 資料格式檢查
if (mb_strlen($_POST['name']) < 2) {
    $output['error'] = '姓名長度太短';
    $output['code'] = 410;
    echo json_encode($output);
    exit;
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $output['error'] = 'email 格式錯誤';
    $output['code'] = 420;
    echo json_encode($output);
    exit;
}

$sql = "UPDATE `members` SET 
                        `name`=?,
                        `email`=?,
                        `password`=?,
                        `mobile`=?,
                        `birthday`=?,
                        `address`=?
                        WHERE `sid`=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['name'],
    $_POST['email'],
    $_POST['password'],
    $_POST['mobile'],
    $_POST['birthday'],
    $_POST['address'],
    $_POST['sid'],
]);

// 如果有上傳檔案
if (!empty($_FILES) and !empty($_FILES['avatar'])) {

    $ext = isset($imgTypes[$_FILES['avatar']['type']]) ? $imgTypes[$_FILES['avatar']['type']] : null; // 取得副檔名

    // 如果是允許的檔案類型
    if (!empty($ext)) {
        $filename = $_FILES['avatar']['name'];  //隨機檔名

        if (move_uploaded_file(
            $_FILES['avatar']['tmp_name'],
            $folder . $filename
        )) {
            $sql = "UPDATE `members` SET `avatar`=? WHERE `sid`=? ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $filename,
                $_POST['sid'],
            ]);
        }
    }
}

$output['rowCount'] = $stmt->rowCount(); // 修改的筆數
if ($stmt->rowCount() == 1) {
    $output['success'] = true;
    $output['error'] = '';
}else{
    $output['error'] = '資料沒有修改';
}

echo json_encode($output);
