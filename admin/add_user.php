<?php
//include config
require_once('../includes/config.php');

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
    <title>Admin | Add User</title>
</head>

<body>

<?php include 'menu.php';?>

<p><a href="users.php">User Admin Index</a></p>

<h2>Add New User</h2>

<?php

//if form has been submitted process it
if (isset($_POST['submit'])) {

    function strMapFunction($v)
    {
        return strtok($v, '&');
    }

    $_POST = array_map('strMapFunction', $_POST);

    //collect form data
    extract($_POST);

    //very basic validation
    if ($username == '' || $username == '<br>') {
        $error[] = 'Please enter the username';
    }

    if ($email == '' || $email == '<br>') {
        $error[] = 'Please enter the email address.';
    }

    if ($password == '' || $password == '<br>') {
        $error[] = 'Please enter the password';
    }

    if ($passwordConfirm == '' || $passwordConfirm == '<br>') {
        $error[] = 'Please confirm the password.';
    }

    if ($password != $passwordConfirm) {
        $error[] = 'Password do not match.';
    }


    if (!isset($error)) {
        $hashedPassword = $user->create_hash($password);

        try {
            //insert into database
            $sql = 'INSERT INTO blog_members (username, password, email)
                VALUES (:username, :password, :email)';
            $result = $db->prepare($sql);
            $result->bindParam(':username', $username);
            $result->bindParam('password', $hashedPassword);
            $result->bindParam('email', $email);
            $result->execute();

            //redirect to Users page
            header('Location: users.php?action=added');
            exit();

        } catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }
}

//show errors
if (isset($error))
{
    foreach ($error as $err) {
        echo '<p>'.$err.'</p>';
    }
}
?>

<div>
    <form method="post">
        <label for="memberUserName">
            <input type="text" name="username" id="memberUserName" placeholder="Username" value="<?php if (isset($error)){ echo $_POST['username'];}?>">
        </label>

        <label for="memberEmail">
            <input type="email" name="email" id="memberEmail" placeholder="Email" value="<?php if (isset($error)){ echo $_POST['email'];}?>"
        </label>

        <label for="memberPassword">
            <input type="password" name="password" id="memberPassword" placeholder="Password" value="<?php if (isset($error)){ echo $_POST['password'];}?>">
        </label>

        <label for="memberPasswordConfirm">
            <input type="password" name="passwordConfirm" id="memberPasswordConfirm" placeholder="Password Confirm" value="<?php if (isset($error)){ echo $_POST['passwordConfirm'];}?>">
        </label>

        <input type="submit" name="submit" value="Add User">
    </form>
</div>

</body>
</html>