<?php
  require_once('../config/connect.php');
  require_once('../config/session.php');
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');
 ?>

 <main id="main" class="main">
<div class="row">
<div class="col-lg-6">
 <div class="card">
   <div class="card-body">
     <h5 class="card-title">Customer Reviews</h5>

     <!-- List group with Advanced Contents -->
     <div class="list-group">
       <?php
         $get_reviews = mysqli_query($link, "SELECT * FROM `reviews` WHERE `business`='$id'");
           while($reviews_result = mysqli_fetch_assoc($get_reviews)) {
            $string=(strtotime('now + 1 hour')-strtotime($reviews_result['datecreated']))/60/60/24;
            if($string<1) {
              $string=(strtotime('now + 1 hour')-strtotime($reviews_result['datecreated']))/60/60;
               if($string<1){
                 $string=(strtotime('now + 1 hour')-strtotime($reviews_result['datecreated']))/60;
                 if($string<1){
                   $time = round($string)." seconds ago";
                 } else {
                   $time = round($string)." minutes ago";
                 }
               }else {
                 $time = round($string)." hours ago";
               }
             }else {
               $time = round($string)." days ago";
             }
           ?>
           <a href="#" class="list-group-item list-group-item-action">
             <div class="d-flex w-100 justify-content-between">
               <h5 class="mb-1"><?php echo $reviews_result['name']; ?></h5>
               <small class="text-muted"><?php echo $time; ?></small>
             </div>
             <p class="mb-1"><?php echo $reviews_result['message']; ?></p>
             <p class="mb-1"><?php echo $reviews_result['email']; ?></p>
             <small class="text-muted">rating: <?php $rating = $reviews_result['ratings']; for($i=0; $i<$rating; $i++) {echo "<i class=\"bi bi-star-fill\"></i>";}?></small>
           </a>
           <?php } ?>
     </div><!-- End List group Advanced Content -->

   </div>
 </div>
</div>

<div class="col-lg-6">
  <form action="" method="post" enctype="multipart/form-data">
    <div class="form-floating mb-3">
      <textarea name="comment" class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 200px;" required></textarea>
      <label for="floatingTextarea">Post a Comment</label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" name="save" type="submit">Comment</button>
  </form>
  <?php
    if(isset($_POST['save'])) {
      $id = $_SESSION['tid'];
      $query = mysqli_query($link, "SELECT * FROM `users` WHERE `id`='$id'");
      $result = mysqli_fetch_assoc($query);
      $comment = $_POST['comment'];
      $email = $result['email'];
      $name = $result['name'];
      $query2 = mysqli_query($link, "SELECT * FROM `businesses` WHERE `user`='$id'");
      $result2 = mysqli_fetch_assoc($query2);
      $bus = $result2['id'];

      $insert = mysqli_query($link, "INSERT INTO `reviews` (`id`, `ratings`, `business`, `name`, `email`, `message`, `user`, `datecreated`, `lastmodified`, `modifiedby`) VALUES (NULL, NULL, '$bus', '$name', '$eamil', '$comment', NULL, NOW(), NOW(), 0)") or die (mysqli_error($link));
      if($insert) {
        echo '<div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Review Sent!</h4>
        <hr>
        <p class="mb-0">Thank you for sharing your review!</p>
      </div>';
      }else {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>An Error occured!</strong> please try again Later.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
      }
    }
   ?>
</div>
</div>

 </main><!-- End #main -->
 <!-- ======= Footer ======= -->
 <footer id="footer" class="footer">
   <div class="copyright">
     &copy; 2021 Copyright <strong><span>yenreach.com</span></strong>. All Rights Reserved
   </div>
 </footer><!-- End Footer -->

 <?php
 require_once('includes/footer.php');
 ?>
