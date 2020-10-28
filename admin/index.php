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

    <!-- meta tags -->
    <?php include '../pages/head.html'?>

    <title> Admin Panel </title>
</head>

<body class="container-fluid bg-dark">

<!-- add navigation menu -->
<?php include 'menu.php'; ?>


<div class="fixed-bottom">

<!-- footer section-->
<!-- Bootstrap core JavaScript -->
<?php include "../pages/footer.html"; ?>

</div>

</body>