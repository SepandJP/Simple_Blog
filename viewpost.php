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
    <meta charset="utf-8">
    <title>Blog - <?php echo $thisPost['postTitle'];?></title>
    <link rel="stylesheet" href="style/">
    <link rel="stylesheet" href="style/">
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


</body>
</html>