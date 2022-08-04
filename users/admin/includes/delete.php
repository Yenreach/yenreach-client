<?php
  require_once('../../../config/connect.php');
  require_once('../../../config/session.php');

  $id = $_GET['id'];
  $tran = $_GET['tran'];
  $tid = $_SESSION['tid'];

  if($tran=='user'){
    $del = mysqli_query($link,"DELETE FROM `users` where `id` = '$id'"); // delete query
    $del2 = mysqli_query($link,"DELETE FROM `businesses` where `user` = '$id'");
    $del3 = mysqli_query($link,"DELETE FROM `subscriptions` where `user` = '$id'");

    if($del && $del2 && $del3)
    { ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Record Deleted Successfully!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
         <?php
        header("location:../users.php?tid=$tid"); // redirects to all records page
        exit;
    }
    else { ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>An Error occured!</strong> Please try again Later.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php }}
      if($tran=='bus'){
        $del = mysqli_query($link,"DELETE FROM `businesses` where `id` = '$id'");
        $del2 = mysqli_query($link,"DELETE FROM `subscriptions` where `business` = '$id'");

        if($del && $del2)
        { ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Record Deleted Successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
             <?php
            header("location:../businesses.php?tid=$tid"); // redirects to all records page
            exit;
        }
        else { ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>An Error occured!</strong> Please try again Later.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php }
      }
       if($tran=='cat'){
        $del = mysqli_query($link,"DELETE FROM `categories` where `id` = '$id'");

        if($del)
        { ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Record Deleted Successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
             <?php
            header("location:../categories.php?tid=$tid"); // redirects to all records page
            exit;
        }
        else { ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>An Error occured!</strong> Please try again Later.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php }
      }
      if($tran=='faq'){
       $del = mysqli_query($link,"DELETE FROM `faqs` where `id` = '$id'");

       if($del)
       { ?>
         <div class="alert alert-success alert-dismissible fade show" role="alert">
         <strong>Record Deleted Successfully!
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
       </div>
            <?php
           header("location:../faqs.php?tid=$tid"); // redirects to all records page
           exit;
       }
       else { ?>
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
         <strong>An Error occured!</strong> Please try again Later.
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
       </div>
       <?php }
     }
     if($tran=='blo'){
      $del = mysqli_query($link,"DELETE FROM `blog` where `id` = '$id'");

      if($del)
      { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Record Deleted Successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
           <?php
          header("location:../blogs.php?tid=$tid"); // redirects to all records page
          exit;
      }
      else { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>An Error occured!</strong> Please try again Later.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php }
    }
    if($tran=='not'){
     $del = mysqli_query($link,"DELETE FROM `messages` where `id` = '$id'");

     if($del)
     { ?>
       <div class="alert alert-success alert-dismissible fade show" role="alert">
       <strong>Record Deleted Successfully!
       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
     </div>
          <?php
         header("location:../notifications.php?tid=$tid"); // redirects to all records page
         exit;
     }
     else { ?>
       <div class="alert alert-danger alert-dismissible fade show" role="alert">
       <strong>An Error occured!</strong> Please try again Later.
       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
     </div>
     <?php }
   }
   if($tran=='img'){
    $del = mysqli_query($link,"DELETE FROM `images` where `id` = '$id'");

    if($del)
    { ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Image Deleted Successfully!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
         <?php
        header("location:../images.php?tid=$tid"); // redirects to all records page
        exit;
    }
    else { ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>An Error occured!</strong> Please try again Later.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php }
  }
     ?>
