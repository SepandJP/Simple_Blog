<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>

<!--
    navigation menu
    include in all admin panel pages
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin | </title>
</head>

<body>

<header>
    <!--  show user name  -->
<!--    <p>Logged in as --><?//=$_SESSION['username'];?><!--</p>-->
</header>

<div>
    <nav>
        <ul>
            <li><a href="index.php">Main</a></li>
            <li><a href="posts.php">Posts</a></li>
            <li><a href="users.php">Users</li>
            <li><a href="../">View Blog</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
</div>

</body>
</html>