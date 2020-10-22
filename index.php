<?php require('includes/config.php'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>

    <!-- meta tags -->
        <?php include 'pages/head.html'?>

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
                    echo PHP_EOL;
                        echo '<h2><a href="viewpost.php?id='.$posts['postID'].'">'.$posts['postTitle'].'</a></h2>';
                        echo PHP_EOL;
                        echo '<p>'.$posts['postDesc'].'</p>';
                        echo PHP_EOL;
                        echo '<p><a href="viewpost.php?id=>'.$posts['postID'].'">Read More...</a></p>';
                        echo PHP_EOL;
                    echo "</div>";
                    echo PHP_EOL;
                }
            }

           catch(PDOException $e)
           {
               echo $e->getMessage();
           }
        ?>

    </body>
</html>
