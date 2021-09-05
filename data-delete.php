<?php
include __DIR__ . '/partials/init.php';

$sid = isset($_GET['sid']) ? intval($_GET['sid']) :0;
//empty：測試這個是不是空的（空字串或空陣列等），沒有定義的話不會跳提示
if(!empty($sid)){  //判斷給的是不是空的
    $sql = "DELETE FROM `members` WHERE sid=$sid"; //接收primary key 有的話就刪除
    $stmt = $pdo->query($sql);
}

//$_SERVER['HTTP_REFERER'] 從哪個頁面連過來的，從哪裡來就跳轉回哪裡，因為chrome效能很好所以看不出來有跳轉過，雖然像ajax留在同頁面但其實有閃一下跳轉過
//不一定有資料
if(isset($_SERVER['HTTP_REFERER'])){
    header("Location: ".$_SERVER['HTTP_REFERER']);  
} else{
    header('Location: data-list.php');
}