<?php
include __DIR__ . '/partials/init.php';
header('Content-Type: application/json');

$folder = __DIR__ . '/imgs/';

$imgTypes = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
];

$output = [
    'success' => false,
    'error' => '',
    'code' => 0,
    'rowCount' => 0,
    'postData' => $_POST,
];

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

$sql = "INSERT INTO `members`(
                `name`, `avatar`, `email`,`password`, `mobile`,
               `birthday`, `address`
               ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?
               )";

$filename = "";

if (!empty($_FILES) and !empty($_FILES['avatar'])) {
    $ext = isset($imgTypes[$_FILES['avatar']['type']]) ? $imgTypes[$_FILES['avatar']['type']] : null; // 取得副檔名
    
    // 如果是允許的檔案類型
    if (!empty($ext)) {
        $filename = $_FILES['avatar']['name'] . $ext; 

        if (!move_uploaded_file(
            $_FILES['avatar']['tmp_name'],
            $folder.$filename
        )) {
            $filename = "";
        }
    }
}
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['name'],
    $filename,  //Binery不能這樣寫
    $_POST['email'],
    password_hash($_POST['password'],PASSWORD_DEFAULT),
    $_POST['mobile'],
    $_POST['birthday'],
    $_POST['address']
]);

$output['rowCount'] = $stmt->rowCount();
if ($stmt->rowCount() == 1) {
    $output['success'] = true;
}

echo json_encode($output);
