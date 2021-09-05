<?php
session_start();

// session_destroy(); // 清除所有的 session
unset($_SESSION['user']); // 移除某個 session 變數 //管理者的話變數名稱可能就用admin

header('Location: homepage.php');