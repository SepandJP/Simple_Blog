<?php require('includes/config.php'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>My first Blog</title>
        <link>
    </head>

    <body>

        <h1>Simple Blog</h1>
        <hr />

        <?php
            try {

                //show all posts
                $sql = "SELECT * FROM `blog_posts` ORDER BY `postID` DESC";
                $result = $db->prepare($sql);
                $result->execute();

                while ($posts = $result->fetch())
                {
                    echo '<div>';
                        echo '<h1><a href="viewpost.php?id='.$posts['postID'].'">'.$posts['postTitle'].'</a></h1>';
                        echo '<p>'.$posts['postDesc'].'</p>';
                        echo '<p><a href="viewpost.php?id=>'.$posts['postID'].'">Read More...</a></p>';
                    echo '</div>';
                }
            }

           catch(PDOException $e)
           {
               echo $e->getMessage();
           }
        ?>

    </body>
</html>
