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

    <!-- meta tags -->
    <?php include '../pages/head.html'?>

    <title>Admin Logon</title>
</head>

<body>

<div id="login" class="container d-flex justify-content-center flex-column">

    <div class="clo-lg-6 d-flex justify-content-center mt-5">

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
            ?>

            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span class="d-flex justify-content-center"><?=$message?></span>
            </div>

        <?php
        }
    }
    ?>

    </div>

    <div class="d-flex justify-content-center">
    <div class="jumbotron">
        <form action="<?php // todo separate php section ?>" method="post">
            <div class="form-group">
                <label for="inAdUser">Usename</label>
                    <input id="inAdUser" class="form-control" type="text" name="username">
            </div>
            <div class="form-group">
                <label for="inAdPass">Password</label>
                    <input id="inAdPass" class="form-control" type="password" name="password">
            </div>
            <input class="btn btn-primary" type="submit" name="submit" value="Login">
        </form>
    </div>
    </div>

</div>

<!-- Bootstrap core JavaScript -->
<?php include "../pages/footer.html"; ?>

</body>
</html>

