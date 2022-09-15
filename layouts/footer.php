<!-- ======= Footer ======= -->
<footer id="footer">

  <div class="footer-top">
    <div class="container">
      <div class="row">

        <div class="col-lg-3 col-md-6 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="index">Home</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="explorer">Explore</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="about">About Us</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="contact">Contact Us</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="users/signup">Add My Business</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="faqs">FAQs</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6 footer-links">
          <h4>Our Brands</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#dcl">Dordorian Concept LTD</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#bgs">BYS Graduate School</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#bmc">BusiTech Model College</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#tec">TEC Industrial Park</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#btu">BusiTech University</a></li>
          </ul>
        </div>

        <div class="col-lg-6 col-md-6 footer-newsletter">
          <h4>Sign Up For Updates Now!</h4>
          <p>Never miss out on the hottest offers and deals from your favourite local businesses.</p>
          <form action="index.php#footer" role="form" method="post">
            <input type="email" name="email" placeholder="name@example.com" required><input type="submit" name="newsletter" value="Submit">
          </form>
          <?php
              if(isset($_POST['newsletter'])){
                $email = $_POST['email'];
                $news_query = mysqli_query($link, "INSERT INTO `newsletter_subscribers` (`id`, `email`, `datecreated`, `lastmodified`, `modifiedby`) VALUES (NULL, '$email', NOW(), NOW(), 0)") or die (mysqli_error($link));
                if($news_query) {
                  ini_set( 'display_errors', 1 );
                  error_reporting( E_ALL );
                  $from = "support@yenreach.com";
                  $subject = "Thank You From Yenreach.com";
                  $message = "Thank You for Subscribing to Our Newsletter!\n\nTake advanatge of our juicy and affordable packages and give your business the marketing boost it needs. Your business is one step away from the reach of the global market!\n\n\n\nThanks.\nYenreach Support Team.";

                  $headers = "From: Yenreach <" . $from . ">";
                  $to = $email;
                  if(mail($to,$subject,$message, $headers)) {
                      echo "<script>console.log('Message was sent')</script>";
                  } else {
                    echo "<script>console.log('Message was not sent')</script>";
                  }
                  echo '<div class="alert alert-success" role="alert">
                  <h4 class="alert-heading">Thank you for Subscribing to our Newsletter!</h4>
                </div>';
                }
              }
             ?>
        </div>

      </div>
    </div>
  </div>

  <div class="container d-md-flex py-4">

    <div class="me-md-auto text-center text-md-start">
      <div class="copyright" style="color: #083640">
        &copy; Copyright <strong><span><a href="https://yenreach.com">yenreach.com</a></span></strong>. All Rights Reserved
      </div>
    </div>
    <div class="social-links text-center text-md-end pt-3 pt-md-0">
      <a href="https://www.facebook.com/yenreachng/" class="facebook"><i class="bx bxl-facebook"></i></a>
      <a href="https://instagram.com/yenreach?utm_medium=copy_link" class="insta"><i class="bx bxl-instagram"></i></a>
      <a href="https://www.linkedin.com/company/yenreach" class="linkedin"><i class="bx bxl-linkedin"></i></a>
      <a href="https://api.whatsapp.com/send?phone=+2349024401562" class="whatsapp"><i class="fa-brands fa-whatsapp"></i></i></a>
    </div>
  </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<!--<script src="assets/vendor/aos/aos.js"></script>-->
<!--<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>-->
<!--<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>-->
<!--<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>-->
<!--<script src="assets/vendor/purecounter/purecounter.js"></script>-->
<!--<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>-->
<script src="assets/lightbox/dist/js/lightbox-plus-jquery.min.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/index.js"></script>
    <script>
        lightbox.option({
          'resizeDuration': 200,
          'wrapAround': true,
          'alwaysShowNavOnTouchDevices': true,
          'fitImagesInViewport': true,
          'maxWidth': 600,
        })
    </script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="assets/js/extra_script.js"></script>
</body>
</html>