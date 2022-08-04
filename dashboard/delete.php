<?php
  require_once('../config/connect.php');

  if(isset($_GET['id']) && isset($_GET['cat']) && isset($_GET['user'])){
    $id = $_GET['id'];
    $cat = $_GET['cat'];
    $user = $_GET['user'];
  }

  $query = mysqli_query($link, "SELECT * FROM `businesses` WHERE `id`='$id'");
  $result = mysqli_fetch_assoc($query);
  if(strpos($result['category'], ',') != false) {
    if(strpos($result['category'], $cat)==0){
      $cat = $cat.",";
      $new_cat = str_replace($cat, '', $result['category']);
    } else {
      $cat = ",".$cat;
      $new_cat = str_replace($cat, '', $result['category']);
    }
  } else {
    $new_cat = '';
  }

  $insert_cat = mysqli_query($link, "UPDATE `businesses` SET `category` = '$new_cat' WHERE `businesses`.`id` = '$id'") or die (mysqli_error($link));

  if($insert_cat){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Category Deleted Successfully!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  echo '<meta http-equiv="refresh" content="1;url=profile.php?tid='.$user.'&bus='.$id.'">';
} else {
  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>An Error occured!</strong> Please try again Later.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
echo '<meta http-equiv="refresh" content="1;url=profile.php?tid='.$user.'&bus='.$id.'">';
}
 ?>
