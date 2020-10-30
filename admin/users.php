<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in())
{
    header('Location: login.php');
}

//show message from add / edit page
if (isset($_GET['delUser']))
{
    //if user id is 1 ignore
    //because it admin
    if ($_GET['delUser'] != 1)
    {
        $sql = 'DELETE FROM blog_members WHERE memberID = :memberID';
        $stm = $db->prepare($sql);
        $stm->bindParam(':memberID', $_GET['delUser']);
        $stm->execute();

        header('Location: users.php?action=deleted');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <!-- meta tags -->
    <!-- add Bootstrap stylesheet -->
    <?php include '../pages/head.html'?>

    <title>Admin | Users</title>
</head>

<body class="bg-dark">

<div class="container-fluid row m-0 p-0">

<?php include 'menu.php'; ?>

<div class="col-sm-9 col-lg-10">

    <?php
//show message from add / edit page
if (isset($_GET['action']))
{
    echo '<div class="mt-3 alert alert-success alert-dismissible fade show">';
    echo '<span>User '.$_GET['action'].' </span>';
    echo ' <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>';
    echo '</div>';
}
?>



    <h1 class="display-4 text-white">Users</h1>

    <table class="table table-light table-striped table-hover">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>

<?php

try {
    $sql = 'SELECT memberID, username, email FROM blog_members ORDER BY username';
    $result = $db->prepare($sql);
    $result->execute();

    while ($members = $result->fetch(PDO::FETCH_ASSOC))
    {
        echo '<td>'.$members['memberID'].'</td>';
        echo '<td>'.$members['username'].'</td>';
        echo '<td>'.$members['email'].'</td>';
        ?>

        <!--
        action field
        edit and delete

        if id=1 dont show delete
        -->
        <td>
            <a href="edit_user.php?id=<?php echo $members['memberID'];?>"><span class="btn btn-warning">Edit</span></a>
            <?php
                if ($members['memberID'] != 1)
            { ?>
              <a href="javascript:delUser(<?php echo $members['memberID'];?>,'<?php echo $members['username'];?>')"><span class="btn btn-danger">Delete</span></a>
            <?php } ?>
        </td>

        <?php
        echo '</tr>';
    }
}

catch (PDOException $e)
{
    $e->getMessage();
}

?>

    </table>

    <a href="add_user.php"><span class="btn btn-success">Add User</span></a>

</div>
</div>

<!-- footer section-->
<!-- Bootstrap core JavaScript -->
<?php include "../pages/footer.html"; ?>

<script type="text/javascript">
    function delUser(id, title) {
        if (confirm("Are you sure you want to delete '" + title + "'"))
        {
            window.location.href = 'users.php?delUser=' + id;
        }
    }
</script>

</body>
</html>
