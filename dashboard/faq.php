<?php
  require_once('../config/connect.php');
  require_once('../config/session.php');
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');
 ?>

 <main id="main" class="main">

   <section class="section faq">
     <div class="row">
       <div class="col-lg-6">

         <div class="card basic">
           <div class="card-body">
             <h5 class="card-title">Basic Questions</h5>
              <?php
              $query = mysqli_query($link, "SELECT * FROM `faqs`");
              while($result = mysqli_fetch_assoc($query)){
               ?>
             <div class="pb-2">
               <h6><?php echo $result['question']; ?></h6>
               <p><?php echo $result['answer']; ?></p>
             </div>
           <?php } ?>

           </div>
         </div>

       </section>

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
