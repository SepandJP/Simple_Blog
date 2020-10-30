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
<body>


    <h1>Blog</h1>
    <hr />
    <p><a href="./">Home</a></p>


    <?php
    echo '<div>';
    echo '<h1>'.$thisPost['postTitle'].'</h1>';
    echo '<p>Posted on '.date('jS M Y', strtotime($thisPost['postDate'])).'</p>';
    echo '<p>'.$thisPost['postCont'].'</p>';
    echo '</div>';
    ?>

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

                echo "Comment Submitted Successfully";
            }

            catch (PDOException $e)
            {
                echo $e->getMessage();
            }
        }


        /*
         * check for any errors
         * if has been any errors
         * display them
        * */
        if (isset($error))
        {
            foreach ($error as $err) {
                echo '<p>'.$err.'</p>';
            }
        }
    }

    ?>

<!--  add comment form  -->
    <p>Share your thoughts about this post</p>
<form method="post">
    <label for="commentName">
        <input type="text" name="commentName" id="commentName" placeholder="Name" value="<?php if (isset($error)){ echo $_POST['commentName'];}?>">
        <br />
    </label>

    <label for="commentEmail">
        <input type="email" name="commentEmail" id="commentEmail" placeholder="Email" value="<?php if (isset($error)){ echo $_POST['commentEmail'];}?>">
        <br />
    </label>

    <label for="commentText">
        <textarea name="commentText" id="commentText" placeholder="Comment"><?php if (isset($error)){ echo $_POST['commentText'];}?></textarea>
        <br />
    </label>

    <input type="submit" name="submit" value="Add Comment">
</form>

<!-- show comments -->
    <p>Comments</p>

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
            echo '<div>';
                echo '<h4>'.$comments['commentName'].'</h4>';
                echo '<p>'.date('jS F Y H:i:s', strtotime($comments['commentDate'])).'</p>';
                echo '<p>'.$comments['commentText'].'</p>';
            echo '</div>';
        }
    }

    catch (PDOException $e)
    {
        $e->getMessage();
    }
    ?>

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