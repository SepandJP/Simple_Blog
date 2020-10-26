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

<body>

<div class="row container-fluid">

<!-- add navigation menu -->
<?php include 'menu.php';


//show message from add / edit page
if (isset($_GET['action']))
{
    echo '<h3>Post is '.$_GET['action'].'</h3>';
}
?>

<!--<p><a href="add_post.php">Add new Post</a></p>-->

<div class="col-sm-10">

    <h1 class="display-4">Posts</h1>

    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

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

                <td>
                    <a class="btn btn-warning" href="edit_post.php?id=<?php echo $posts['postID'];?>">Edit</a>
                    <a class="btn btn-danger" href="javascript:delPost('<?php echo $posts['postID'];?>','<?php echo $posts['postTitle'];?>')">Delete</a>
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
