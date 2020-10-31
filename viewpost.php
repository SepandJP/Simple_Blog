<?php
require ('includes/config.php');

//get post by ID query from database.
$sql = 'SELECT * FROM  `blog_posts` WHERE `postID` = :postID ';
$result = $db->prepare($sql);
$result->bindParam(':postID', $_GET['id']);
$result->execute();

//save post contents in $thisPost.
$thisPost = $result->fetch();

//if post does not exists redirect user.
if ($thisPost['postID'] == '')
{

    header('Location: error404.html');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <!-- meta tags -->
    <meta name="author" content="Sepand JamshidPour">
    <meta name="description" content="A Simple Blog with PHP and Bootstrap">
    <meta name="keywords" content="html, css, php, Bootstrap">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">

    <link rel="stylesheet" type="text/css" href="files/stylesheets/bootstrap/bootstrap.min.css">

    <title>Blog - <?php echo $thisPost['postTitle'];?></title>
    </head>
<body class="bg-dark">


<div class="container">

    <div class="pt-5">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">My Blog</li>
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="#"><?=$thisPost['postTitle']?></a></li>
        </ul>
    </div>

<!-- main content -->
<!-- show post-->
    <main class="container bg-light py-3 my-5">
    <?php
    echo '<div>';
    echo '<h1>'.$thisPost['postTitle'].'</h1>';
    echo '<p>Posted on '.date('jS M Y', strtotime($thisPost['postDate'])).'</p>';
    echo '<p>'.$thisPost['postCont'].'</p>';
    echo '</div>';
    ?>
    </main>
<!-- end post -->


<!--  comments section  -->

<!-- add comment -->
<!--  php code for validation and process new comment  -->
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
        if ($commentName == '')
        {
            $error[] = 'Please enter the name.';
        }

        if ($commentEmail == '')
        {
            $error[] = 'Please enter the email.';
        }

        if ($commentText == '')
        {
            $error[] = 'Please enter the comment.';
        }

        /*
        if no error has been set then
        insert the data into the database
        */
        if (!isset($error))
        {
            try {
                $commentDate = date('Y-m-d H:i:s');
                $commentStatus = false;
                $postID = $_GET['id'];

                $sql = 'INSERT INTO blog_comments (commentName, commentEmail, commentText, commentDate, commentStatus, postID)
                        value (:commentName, :commentEmail, :commentText, :commentDate, :commentStatus, :postID)';
                $result = $db->prepare($sql);
                $result->bindParam(':commentName', $commentName);
                $result->bindParam(':commentEmail', $commentEmail);
                $result->bindParam(':commentText', $commentText);
                $result->bindParam(':commentDate', $commentDate);
                $result->bindParam(':commentStatus', $commentStatus);
                $result->bindParam(':postID', $postID);
                $result->execute();
            }

            catch (PDOException $e)
            {
                echo $e->getMessage();
            }
        }
    }

    ?>

<!--  add comment form  -->
    <div class="bg-light rounded">

    <h4 class="font-italic font-weight-bold card-header border-bottom-0">Share your thoughts about this post:</h4>

        <?php
            /*
             * check for any errors
             * if has been any errors
             * display them
            * */
        if (isset($_POST['submit']))
        {
            if (isset($error)) {
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
            } else {
                $message = "Comment Submitted Successfully.";
                echo '<div class="container-fluid m-4">';

                echo '<div class="alert alert-success alert-dismissible fade show col-sm-7 col-md-5 col-lg-4" role="alert">';
                echo '    <span>' . $message . '</span>';
                echo '    <button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                echo '        <span aria-hidden="true">&times;</span>';
                echo '    </button>';
                echo '</div>';

                echo '</div>';
            }
        }
        ?>


        <form method="post" class="card-body">
            <div class="row ml-1">
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="commentName">Name</label>
                        <input class="form-control" type="text" name="commentName" id="commentName" value="<?php if (isset($error)){ echo $_POST['commentName'];}?>">
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="commentEmail">Email</label>
                        <input class="form-control" type="email" name="commentEmail" id="commentEmail" value="<?php if (isset($error)){ echo $_POST['commentEmail'];}?>" aria-describedby="emailHelp">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-8">
                <div class="form-group">
                    <label for="commentText">Comment</label>
                    <textarea class="form-control" name="commentText" id="commentText" rows="5"><?php if (isset($error)){ echo $_POST['commentText'];}?></textarea>
                </div>
            </div>

            <div class="form-group ml-3">
                <input type="submit" name="submit" value="Add Comment" class="btn btn-outline-success">
            </div>
        </form>
    </div>

<!-- show comments -->
    <section class="bg-light my-5 rounded">
        <h3 class="font-italic font-weight-bold card-header border-bottom-0">Comments</h3>

        <div class="card-body">
        <?php
        try {
            //show all comments of this post

            $postID = $_GET['id'];
            $commentStatus = true;

            $sql = 'SELECT commentName, commentText, commentDate FROM blog_comments WHERE postID = :postID AND commentStatus = :commentStatus ORDER BY commentDate DESC';
            $result = $db->prepare($sql);
            $result->bindParam(':postID', $postID);
            $result->bindParam(':commentStatus', $commentStatus);
            $result->execute();

            while ($comments = $result->fetch())
            {
                echo '<div class="jumbotron p-3 pl-5">';
                    echo '    <span class="d-block font-weight-bold">'.$comments['commentName'].'</span>';
                    echo '    <small class="d-block font-weight-light">'.date('jS F Y H:i:s', strtotime($comments['commentDate'])).'</small>';
                    echo '    <p class="m-2">'.$comments['commentText'].'</p>';
                echo '</div>';
            }
        }

        catch (PDOException $e)
        {
            $e->getMessage();
        }
        ?>
        </div>
    </section>
</div>
<!-- end content -->


    <!--footer section-->

    <footer class="py-5 bg-secondary">
        <div class="container text-center text-white">
            <p>This weblog is for practice the PHP</p>
            <p>Sepand JamshidPour | 2020</p>
        </div>
    </footer>

    <!--add Bootstrap and jQuery JavaScript files-->
    <script type="text/javascript" rel="script" src="files/scripts/bootstrap/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" rel="script" src="files/scripts/bootstrap/bootstrap.min.js"></script>

</body>
</html>