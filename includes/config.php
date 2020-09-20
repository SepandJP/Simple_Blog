<?php
ob_start();
session_start();

//for Auto loading classes
require '../classes/Autoload.php';

//connect to database
define('DB_SERVER','127.0.0.1');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','simple_blog');

try {
    $db = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

//set timezone
date_default_timezone_set('Asia/Tehran');


$user = new class_user($db);