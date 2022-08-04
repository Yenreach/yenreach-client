<?php
  require_once('config/connect.php');

  if(isset($_GET['tid'])) {
    $id = $_GET['tid'];

    $query = mysqli_query($link, "UPDATE `users` SET `confirmed_email`=1 WHERE `id`='$id'");
    if($query){
      header("Location: dashboard/auth.php");
      echo "<script>console.log('status updated')</script>";
    }
  }

?>