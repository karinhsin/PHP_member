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
    'postData' => $_POST,
];

if (empty($_POST['name'])) {  //必要欄位是name
    echo json_encode($output);
    exit;
}

// 預設是沒有上傳資料，沒有上傳成功
$isSaved = false;

// 如果有上傳檔案
if (!empty($_FILES) and !empty($_FILES['avatar'])) {

    $ext = isset($imgTypes[$_FILES['avatar']['type']]) ? $imgTypes[$_FILES['avatar']['type']] : null; // 取得副檔名

    // 如果是允許的檔案類型
    if (!empty($ext)) {
        $filename = sha1($_FILES['avatar']['name'] . rand()) . $ext;  //隨機檔名

        if (move_uploaded_file(
            $_FILES['avatar']['tmp_name'],
            $folder . $filename
        )) {
            $sql = "UPDATE `members` SET `avatar`=?, `name`=? WHERE sid=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $filename,
                $_POST['name'],
                $_SESSION['user']['sid'],
            ]);

            if ($stmt->rowCount()) {
                $isSaved = true;

                $_SESSION['user']['avatar'] = $filename;
                $_SESSION['user']['name'] = $_POST['name'];

                $output['filename'] = $filename;
                $output['error'] = '';
                $output['success'] = true;

                echo json_encode($output);
                exit;
            }
        }
    }
}

if (!$isSaved) {
    $sql = "UPDATE `members` SET `name`=? WHERE sid=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['name'],
        $_SESSION['user']['sid'],
    ]);

    if ($stmt->rowCount()) {
        $_SESSION['user']['name'] = $_POST['name'];
        $output['error'] = '';
        $output['success'] = true;
    }
}

echo json_encode($output);