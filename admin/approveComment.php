<?php
//include config
require_once'../includes/config.php';

//if not logged in redirect to login page
if(!$user->is_logged_in())
{
    header('Location: login.php');
}


if (isset($_GET['id']))
{
    $commentId = $_GET['id'];
    $commentNewStatus = true;
    $sql = 'UPDATE blog_comments
            SET commentStatus = :commentNewStatus
            WHERE commentId = :commentId';
    $result = $db->prepare($sql);
    $result->bindParam(':commentNewStatus' , $commentNewStatus);
    $result->bindParam(':commentId', $commentId);
    $result->execute();

    header('Location: comments.php?action=Approved');
    exit();
}
