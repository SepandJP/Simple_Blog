<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--  meta tags and include Bootstrap stylesheet  -->
    <?php include '../pages/head.html';?>
    <title>Admin | Edit User</title>
</head>

<body class="bg-dark">

<div class="container-fluid row m-0 p-0">

<?php include 'menu.php';?>

<div class="col-sm-9 col-lg-10">

<h1 class="display-4 text-white">Edit User</h1>

<?php

//if form has been submitted process it
if (isset($_POST['submit']))
{
    function strMapFunction($v)
    {
        return strtok($v, '&');
    }

    $_POST = array_map('strMapFunction', $_POST);

    //collect form data
    extract($_POST);

    //very basic validation
    if ($username == '' || $username == '<br>')
    {
        $error[] = 'Please enter the username.';
    }

    if($email == '' || $email == '<br>')
    {
        $error[] = 'Please enter the email address.';
    }

    if ((strlen($password > 0) || $password != '' || $password != '<br>') || strlen($passwordConfirm > 0) || $passwordConfirm != '' || $passwordConfirm != '<br>')
    {
        if($password == '' || $password == '<br>')
        {
            $error[] = 'Please enter the password.';
        }

        if($passwordConfirm == '' || $passwordConfirm == '<br>')
        {
            $error[] = 'Please confirm the password.';
        }

        if($password != $passwordConfirm)
        {
            $error[] = 'Passwords do not match.';
        }
    }

    if (!isset($error))
    {
        try
        {
            //if user want change the password
            if (isset($password))
            {
                $hashedPassword = $user->create_hash($password);

                //update into database
                $sql = 'UPDATE blog_members SET username = :username, password = :password, email = :email WHERE memberID = :memberID';
                $result = $db->prepare($sql);
                $result->bindParam(':username',$username);
                $result->bindParam(':password',$hashedPassword);
                $result->bindParam(':email',$email);
                $result->bindParam(':memberID',$memberID);
                $result->execute();
            }

            else
            {
                //update database
                $sql = 'UPDATE blog_members SET username = :username, email = :email WHERE memberID = :memberID';
                $result = $db->prepare($sql);
                $result->bindParam(':username',$username);
                $result->bindParam(':email',$email);
                $result->bindParam(':memberID',$memberID);
                $result->execute();
            }

            //redirect to index page
            header('Location: users.php?action=updated');
            exit();
        }

        catch (PDOException $e)
        {
            $e->getMessage();
        }
    }
}

//show errors
if (isset($error))
{
    foreach ($error as $err) {
        echo '<div class="alert alert-danger alert-dismissible fade show">';
        echo '<span>'.$err.'</span>';
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>';
        echo '</div>';
    }
}

//show User Information
try
{
    $sql = 'SELECT memberID, username, email FROM blog_members WHERE memberID=:memberID';
    $result = $db->prepare($sql);
    $result->bindParam(':memberID', $_GET['id']);
    $result->execute();
    $userInfo = $result->fetch(PDO::FETCH_ASSOC);
}

catch (PDOException $e)
{
    $e->getMessage();
}
?>

<div class="jumbotron">
    <form method="post">
        <input type="hidden" name="memberID" value="<?php echo $userInfo['memberID'];?>">

        <div class="form-group">
            <label for="memberUsername">Username
                <input class="form-control" type="text" name="username" id="memberUsername" placeholder="Username" value="<?php echo $userInfo['username'];?>">
            </label>
        </div>

        <div class="form-group">
            <label for="memberEmail">Email
                <input class="form-control" type="email" name="email" id="memberEmail" placeholder="Email" value="<?php echo $userInfo['email'];?>">
            </label>
        </div>

        <div class="form-group">
            <label for="memberPassword">Password
                <input class="form-control" type="password" name="password" id="memberPassword" placeholder="Password">
            </label>
        </div>

        <div class="form-group">
            <label for="memberPasswordConfirm">Confirm Password
                <input class="form-control" type="password" name="passwordConfirm" id="memberPasswordConfirm" placeholder="Confirm Password">
            </label>
        </div>

        <input type="submit" name="submit" value="Update User">
    </form>
</div>

</div>
</div>

<!-- JavaScript files -->
<?php include '../pages/footer.html';?>

</body>
</html>
