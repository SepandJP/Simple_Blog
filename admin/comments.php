<?php

//include config
require_once'../includes/config.php';

//if not logged in redirect to login page
if(!$user->is_logged_in())
{
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | comments</title>
</head>

<body>

<!-- add navigation menu -->
<?php include 'menu.php';

//show message from add / edit page
if (isset($_GET['action']))
{
echo '<h3>Comment is '.$_GET['action'].'</h3>';
}
?>

<h2>Un-Approved Comments</h2>

<div>
    <table>
        <tr>
            <th>No</th>
<!--        <th>Post Title</th>-->
            <th>Date & Time</th>
            <th>Name</th>
            <th>Email</th>
            <th>Comment</th>
            <th>Action</th>
<!--            <th>Preview</th>-->
        </tr>

            <!--    show un-approved comments    -->
        <?php
        try
        {
            $commentStatus = false;
            $sql = 'SELECT * FROM blog_comments WHERE commentStatus = :commentStatus ORDER BY commentId DESC';
            $result = $db->prepare($sql);
            $result->bindParam(':commentStatus', $commentStatus);
            $result->execute();

            $counter = 0;
            while($comments = $result->fetch(PDO::FETCH_ASSOC))
            {
                $commentId = $comments['commentId'];

                echo '<tr>';
                echo '<td>'.++$counter.'</td>';
//              echo '<td>'.'</td>';
                echo '<td>'.$comments['commentDate'].'</td>';
                echo '<td>'.$comments['commentName'].'</td>';
                echo '<td>'.$comments['commentEmail'].'</td>';
                echo '<td>'.$comments['commentText'].'</td>';
                echo '<td>';
                echo '<a href="approveComment.php?id='.$commentId.'">Approve</a>';
                echo ' | ';
                echo '<a href="deleteComment.php?id='.$commentId.'">Delete</a>';                echo '</td>';
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

<br />

        <!-- show approved comments -->
<h2>Approved Comments</h2>

<div>
    <table>
        <tr>
            <th>No</th>
            <!--            <th>Post Title</th>-->
            <th>Date & Time</th>
            <th>Name</th>
            <th>Email</th>
            <th>Comment</th>
            <th>Action</th>
            <!--            <th>Preview</th>-->
        </tr>

        <?php
        //        show approved comment
        try
        {
            $commentStatus = true;
            $sql = 'SELECT * FROM blog_comments WHERE commentStatus = :commentStatus ORDER BY commentId DESC';
            $result = $db->prepare($sql);
            $result->bindParam(':commentStatus', $commentStatus);
            $result->execute();

            $counter = 0;
            while($comments = $result->fetch(PDO::FETCH_ASSOC))
            {
                $commentId = $comments['commentId'];

                echo '<tr>';
                echo '<td>'.++$counter.'</td>';
//              echo '<td>'.'</td>';
                echo '<td>'.$comments['commentDate'].'</td>';
                echo '<td>'.$comments['commentName'].'</td>';
                echo '<td>'.$comments['commentEmail'].'</td>';
                echo '<td>'.$comments['commentText'].'</td>';
                echo '<td>';
                echo '<a href="disApproveComment.php?id='.$commentId.'">Un-Approve</a>';
                echo ' | ';
                echo '<a href="deleteComment.php?id='.$commentId.'">Delete</a>';
                echo '</td>';
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

</body>
</html>
