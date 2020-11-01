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

    <title>Admin | New Post</title>
</head>

<body class="bg-dark">

<!-- menu & content -->
<div class="container-fluid row m-0 p-0">

<!-- navigation menu -->
<?php
include 'menu.php';
// todo delete <br> tags
?>

    <!--  main content  -->
    <div class="col-sm-9 col-lg-10">

    <h1 class="display-4 text-white">Add New Post</h1>

<!--
    PHP code
    for validation and process input data
-->
<?php

//if form has been submitted process it
if (isset($_POST['submit'])) {
    function strMapFunction($v)
    {
        $v = htmlentities($v);
        return strtok($v, '&');
    }

    $_POST = array_map('strMapFunction', $_POST);

    //collect form data
    extract($_POST);

    // todo in this text editor if <textarea> empty in $_POST type <br>
    //very basic validation
    if($postTitle == '' || $postTitle == '<br>'){
        $error[] = 'Please enter the title.';
    }

    if($postDesc == '' || $postDesc == '<br>'){
        $error[] = 'Please enter the description.';
    }

    if($postCont == '' || $postCont == '<br>'){
        $error[] = 'Please enter the content.';
    }

    /*
    if no error has been set then
    insert the data into the database
    */
    if (!isset($error))
    {
        try
        {
            $postDate = date('Y-m-d H:i:s');

            $sql = 'INSERT INTO blog_posts (postTitle,postDesc,postCont,postDate) 
                VALUES (:postTitle, :postDesc, :postCont, :postDate)';
            $result = $db->prepare($sql);
            $result->bindParam(':postTitle', $postTitle);
            $result->bindParam(':postDesc', $postDesc);
            $result->bindParam(':postCont', $postCont);
            $result->bindParam(':postDate', $postDate);
            $result->execute();

            //redirect to posts page
            header('Location: posts.php?action=added');
            exit();
        }

        catch (PDOException $e)
        {
            echo $e->getMessage();
        }

    }
}

?>

<div class="jumbotron">

    <?php
        /*
    * check for any errors
    * if has been any errors
    * display them
    * */
        if (isset($error))
        {
            echo '<div class="container-fluid m-4">';
            foreach ($error as $err) {
                echo '<div class="alert alert-danger alert-dismissible fade show col-sm-7 col-md-5 col-lg-4" role="alert">';
                echo '    <span>' . $err . '</span>';
                echo '    <button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                echo '        <span aria-hidden="true">&times;</span>';
                echo '    </button>';
                echo '</div>';
            }
            echo '</div>';
        }
    ?>

    <form method="post">
        <div class="form-group">
            <label for="inTitle">Title</label>
            <input class="form-control" type="text" id="inTitle" name="postTitle" value="<?php if (isset($error)){ echo $_POST['postTitle'];}?>">
        </div>

        <div class="form-group">
            <label for="inDesc">Description</label>
            <textarea class="form-control" id="inDesc" name="postDesc" cols="60" rows="10"><?php if (isset($error)){ echo $_POST['postDesc'];}?></textarea>
        </div>

        <div class="form-group">
            <label for="inCont">Content</label>
            <textarea class="form-control" id="inCont" name="postCont" cols="60" rows="10"><?php if (isset($error)){ echo $_POST['postCont'];}?></textarea>
        </div>

        <input class="btn btn-outline-success" type="submit" name="submit" value="Submit">
    </form>
</div>

    </div>
</div>

<!-- JavaScript files -->
<?php include '../pages/footer.html';?>

</body>
</html>
