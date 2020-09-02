<?php
//include config
require_once'../includes/config.php';

//if not logged in redirect to login page
if(!$user->is_logged_in())
{
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title> Posts</title>
</head>

<body>

<!-- add navigation menu -->
<?php include 'menu.php'; ?>
