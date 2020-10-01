<?php
ob_start();
session_start();

//connect to database
define('DB_SERVER','localhost');
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

//spl_autoload_register('my_autoloader');


//load classes as needed
function __autoload($class)
{
    $class = strtolower($class);

    //if call from within /assets adjust the path
    $classpath = 'classes/'.$class . '.php';
    if ( file_exists($classpath)) {
        require_once $classpath;
    }

    //if call from within admin adjust the path
    $classpath = '../classes/'.$class . '.php';
    if ( file_exists($classpath)) {
        require_once $classpath;
    }

    //if call from within admin adjust the path
    $classpath = '../../classes/'.$class . '.php';
    if ( file_exists($classpath)) {
        require_once $classpath;
    }

}

$user = new class_user($db);