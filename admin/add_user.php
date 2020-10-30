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
    <!--  meta tags and include Bootstrap stylesheet  -->
    <?php include '../pages/head.html';?>
    <title>Admin | Add User</title>
</head>

<body class="bg-dark">

<div class="container-fluid row m-0 p-0">

<?php include 'menu.php';?>

    <div class="col-sm-9 col-lg-10">

<h1 class="text-white display-4">Add New User</h1>

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
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        echo '<span>'.$err.'</span>';
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>';
        echo '</div>';
    }
}
?>

<div class="jumbotron">
    <form method="post">

        <div class="form-group">
            <label for="memberUserName">
                <input class="form-control" type="text" name="username" id="memberUserName" placeholder="Username" value="<?php if (isset($error)){ echo $_POST['username'];}?>">
            </label>
        </div>

        <div class="form-group">
            <label for="memberEmail">
                <input class="form-control" type="email" name="email" id="memberEmail" placeholder="Email" value="<?php if (isset($error)){ echo $_POST['email'];}?>"
            </label>
        </div>

        <div class="form-group">
            <label for="memberPassword">
                <input class="form-control" type="password" name="password" id="memberPassword" placeholder="Password" value="<?php if (isset($error)){ echo $_POST['password'];}?>">
            </label>
        </div>

        <div class="form-group">
            <label for="memberPasswordConfirm">
                <input class="form-control" type="password" name="passwordConfirm" id="memberPasswordConfirm" placeholder="Password Confirm" value="<?php if (isset($error)){ echo $_POST['passwordConfirm'];}?>">
            </label>
        </div>

        <input type="submit" name="submit" value="Add User">
    </form>
</div>

    </div>
</div>

<!-- JavaScript files -->
<?php include '../pages/footer.html';?>

</body>
</html>