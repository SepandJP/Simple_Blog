<?php require('includes/config.php'); ?>

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

        <title>My first Blog</title>
    </head>

    <body>

        <header class="container mt-3">
            <div class="row">
                <div class="col-sm-12 col-md-6 my-2">
                    <a class="d-inline-block btn btn-secondary" href="admin/index.php">Admin Panel</a>
                </div>
                <h1 class="cil-sm-12 col-md-6 d-inline-block mt-2">Simple Blog</h1>
            </div>
                <hr />
        </header>

        <main class="container">
            <div class="row">

        <?php
            try {

                //show all posts
                $sql = "SELECT * FROM `blog_posts` ORDER BY `postID` DESC";
                $result = $db->prepare($sql);
                $result->execute();

                while ($posts = $result->fetch())
                {
                    echo '<div class="col-sm-12 col-md-6 col-lg-4 mt-3">';
                        echo '<div class="card">';
                            echo '<h2 class="card-header"><a href="viewpost.php?id='.$posts['postID'].'">'.$posts['postTitle'].'</a></h2>';
                            echo '<div class="card-body">';
                                echo '<p class="card-text">'.htmlentities($posts['postDesc']).'</p>';
                            echo '</div>';
                            echo '<div class="card-footer">';
                                echo '<a href="viewpost.php?id='.$posts['postID'].'" class="btn btn-primary">Read More...</a>';
                            echo '</div>';
                        echo '</div>';
                    echo "</div>";
                }
            }

           catch(PDOException $e)
           {
               echo $e->getMessage();
           }
        ?>
            </div>
        </main>

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
