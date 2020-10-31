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

    <!-- meta tags -->
    <!-- add Bootstrap stylesheet -->
    <?php include '../pages/head.html'?>

    <title>Admin | comments</title>
</head>

<body class="bg-dark">

<div class="container-fluid row m-0 p-0">

<!-- add navigation menu -->
<?php include 'menu.php'; ?>

<div class="col-sm-9 col-lg-10">

    <?php
//show message from add / edit page
if (isset($_GET['action']))
{
    echo '<div class="mt-3 alert alert-success alert-dismissible fade show" role="alert">';
    echo '<span>Comment is '.$_GET['action'].'</span>';
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '</div>';
}
?>

<h2 class="display-4 text-white">Un-Approved Comments</h2>

<div>
    <table class="table table-light table-striped table-hover">
        <thead class="thead-light">
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
        </thead>

        <tbody>
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
                echo '<td width="50%">'.$comments['commentText'].'</td>';
                echo '<td>';
                echo '<a href="approveComment.php?id='.$commentId.'"><span class="mr-1 btn btn-success">Approve</span></a>';
                echo '<a href="deleteComment.php?id='.$commentId.'"><span class="ml-1 btn btn-danger">Delete</span></a>';                echo '</td>';
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

<br />

        <!-- show approved comments -->
<h2 class="display-4 text-white">Approved Comments</h2>

<div>
    <table class="table table-light table-striped table-hover">
        <thead class="thead-light">
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
        </thead>

        <tbody>
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
                echo '<td width="45%">'.$comments['commentText'].'</td>';
                echo '<td>';
                echo '<a href="disApproveComment.php?id='.$commentId.'"><span class="mr-1 btn btn-warning">Un-Approve</span></a>';
                echo '<a href="deleteComment.php?id='.$commentId.'"><span class="ml-1 btn btn-danger">Delete</span></a>';
                echo '</td>';
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
</div>

<!-- footer section-->
<!-- Bootstrap core JavaScript -->
<?php include "../pages/footer.html"; ?>

</body>
</html>
