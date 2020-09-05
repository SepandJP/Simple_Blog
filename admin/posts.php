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
    <meta charset="utf-8">
    <title>Admin | Posts</title>
</head>

<body>

<!-- add navigation menu -->
<?php include 'menu.php';


//show message from add / edit page
if (isset($_GET['action']))
{
    echo '<h3>Post is '.$_GET['action'].'</h3>';
}
?>

<p><a href="add_post.php">Add new Post</a></p>

<div>
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
                    <a href="edit_post.php?id=<?php echo $posts['postID'];?>">Edit</a>
                    <a href="javascript:delPost('<?php echo $posts['postID'];?>','<?php echo $posts['postTitle'];?>')">Delete</a>
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


<script type="text/javascript">
    function delPost(id, title) {
        if (confirm("Are you sure you want to delete " + title + " ?"))
        {
            window.location.href = 'posts.php?delPost=' + id;
        }
    }
</script>

</body>
</html>
