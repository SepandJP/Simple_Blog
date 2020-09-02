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
    <meta charset="utf-8">
    <title>Admin | New Post</title>
</head>

<body>

<!-- navigation menu -->
<?php
include 'menu.php';
// todo delete <br> tags
?>

<h1>Add New Post</h1>

<!--
    PHP code
    for validation and process input data
-->
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

    // todo in this text editor if <textarea> empty in $_POST type <br>
    //very basic validation
    if($postTitle == ''){
        $error[] = 'Please enter the title.';
    }

    if($postDesc == '' || $postDesc == '<br>'){
        $error[] = 'Please enter the description.';
    }

    if($postCont == '' || $postDesc == '<br>'){
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
?>

<div>
    <form method="post">
        <label for="inTitle">Title
            <br />
            <input type="text" id="inTitle" name="postTitle">
        </label>

        <br />
        <br />

        <label for="inDesc">Description
            <textarea id="inDesc" name="postDesc" cols="60" rows="10"></textarea>
        </label>

        <br />

        <label>Content
            <textarea id="inCont" name="postCont" cols="60" rows="10"></textarea>
        </label>

        <br />

        <input type="submit" name="submit" value="Submit">
    </form>
</div>


<!-- text editor CDN -->
<script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
    //<![CDATA[
    bkLib.onDomLoaded(function() {
        new nicEditor({fullPanel : true}).panelInstance('inDesc');
        new nicEditor({fullPanel : true}).panelInstance('inCont');
    });
    //]]>
</script>

</body>
</html>
