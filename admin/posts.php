<?php

//include config
require_once '../includes/config.php';

//if not logged in redirect to login page
if(!$user->is_logged_in()){
    header('Location: login.php');
}

//delete selected post
if (isset($_GET['delPost']))
{
    $sql = 'DELETE FROM blog_posts WHERE postID = :postID';
    $result = $db->prepare($sql);
    $result->bindParam(':postID', $_GET['delPost']);
    $result->execute();

    header('Location: posts.php?action=deleted');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <!-- meta tags -->
    <?php include '../pages/head.html'?>

    <title>Admin | Posts</title>
</head>

<body class="bg-dark">

<div class="container-fluid row m-0 p-0">

<!-- add navigation menu -->
<?php include 'menu.php';


//show message from add / edit page
if (isset($_GET['action']))
{
    echo '<h3>Post is '.$_GET['action'].'</h3>';
}
?>

<!--<p><a href="add_post.php">Add new Post</a></p>-->

<div class="col-sm-9 col-lg-10">

    <h1 class="display-4 text-white">Posts</h1>

    <table class="table table-light table-striped table-hover">
        <thead class="thead-light">
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
        <?php
        try {
            $sql = "SELECT * FROM blog_posts ORDER BY postID DESC";
            $result = $db->prepare($sql);
            $result->execute();


            while ($posts = $result->fetch(PDO::FETCH_ASSOC))
            {
                echo '<tr>';
                echo '<td>'.$posts['postTitle'].'</td>';
                echo '<td>'.$posts['postDesc'].'</td>';
                echo '<td>'.date('jS M Y', strtotime($posts['postDate'])).'</td>';
                ?>

                <td class="d-inline-flex">
                    <a class="mr-1" href="edit_post.php?id=<?php echo $posts['postID'];?>">
                        <span class="btn btn-warning">Edit</span>
                    </a>
                    <a class="ml-1" href="javascript:delPost('<?php echo $posts['postID'];?>','<?php echo $posts['postTitle'];?>')">
                        <span class="btn btn-danger">Delete</span>
                    </a>
                </td>

        <?php
                echo '</tr>';
            }
        }

        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
        ?>
        </tbody>

    </table>
</div>

</div>

<script type="text/javascript">
    function delPost(id, title) {
        if (confirm("Are you sure you want to delete " + title + " ?"))
        {
            window.location.href = 'posts.php?delPost=' + id;
        }
    }
</script>

<!-- footer section-->
<!-- Bootstrap core JavaScript -->
<?php include "../pages/footer.html"; ?>

</body>
</html>
