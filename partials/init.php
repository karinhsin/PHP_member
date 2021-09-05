<?php
require __DIR__ . '/db_connect.php';

if(! isset($_SESSION)){
    session_start();
}