<?php
//include config
require_once '../includes/config.php';

//check if already logged in
if ($user->is_logged_in())
{
    header('Location: index.php');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Logon</title>
</head>

<body>

<div id="login">


    <?php

    // todo add validation
    //process login form if submitted
    if (isset($_POST['submit']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($user->login($username, $password))
        {
            //logged in return to index page
            header('Location: index.php');
            exit();
        }

        else
        {
            $message = 'Wrong username or password';
            echo "<p>$message</p>";
        }
    }
    ?>

    <form action="" method="post">
        <label for="inAdUser">Usename
            <input id="inAdUser" type="text" name="username">
        </label>
        <label for="inAdPass">Password
            <input id="inAdPass" type="password" name="password">
        </label>
        <input type="submit" name="submit" value="Login">
    </form>

</div>

</body>
</html>

