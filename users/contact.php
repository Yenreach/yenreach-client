<?php
  require_once('../config/connect.php');
  require_once('../config/session.php');
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');
 ?>

 <main id="main" class="main">

<section class="section contact">

  <div class="row gy-4">
    <?php
        if(isset($_POST['save'])) {
          $id = $_SESSION['tid'];
          $query = mysqli_query($link, "SELECT * FROM `users` WHERE `id`='$id'");
          $result = mysqli_fetch_assoc($query);
          $subject = $_POST['subject'];
          $email = $result['email'];
          $message = $_POST['message'];

          $insert = mysqli_query($link, "INSERT INTO `messages` (`id`, `email`, `subject`, `message`, `status`, `datecreated`, `lastmodified`, `modifiedby`) VALUES (NULL, '$email', '$subject', '$message', 0, NOW(), NOW(), 0)") or die (mysqli_error($link));

          if($insert) {
              ini_set( 'display_errors', 1 );
              error_reporting( E_ALL );
              $to = $email;
              $from = "support@yenreach.com";
              $subject = "RE: [".strtoupper($subject)."]";
              $message = "Thank you for contacting us on the subject matter - ".strtoupper($subject).". We are looking into the matter and will get back to you as soon as possible.\n\n\n\nThanks.\nYenreach Support Team.";

              $headers = "From: Yenreach <" . $from . ">";
              if(mail($to,$subject,$message, $headers)) {
                echo "<script>console.log('Message was sent')</script>";
              } else {
                echo "<script>console.log('Message was not sent')</script>";
              }
              echo '<div class="alert alert-success" role="alert">
              <h4 class="alert-heading">Message Sent!</h4>
              <hr>
              <p class="mb-0">Thank you for contacting us. We will get back to you shortly!</p>
            </div>';
          } else {
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>An Error occured!</strong> please try again Later.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
          }

        }
    ?>

    <div class="col-xl-6">

      <div class="row">
        <div class="col-lg-6">
          <div class="info-box card">
            <i class="bi bi-geo-alt"></i>
            <h3>Our Address</h3>
            <p>58, Azikoro Road (Authentic Plaza),<br>Opposite NUJ, Ekeki, Yenegoa. Bayelsa State.</p>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="info-box card">
            <i class="bi bi-telephone"></i>
            <h3>Call Us</h3>
            <p>(+234) 809 443 1727<br>(+234) 703 719 3301</p>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="info-box card">
            <i class="bi bi-envelope"></i>
            <h3>Email Us</h3>
            <p>support@yenreach.com</p>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="info-box card">
            <i class="bi bi-clock"></i>
            <h3>Open Hours</h3>
            <p>Monday - Friday<br>9:00AM - 05:00PM</p>
          </div>
        </div>
      </div>

    </div>

    <div class="col-xl-6">
      <div class="card p-4">
        <form action="forms/contact.php" method="post" class="php-email-form">
          <div class="row gy-4">

            <div class="col-md-12">
              <input type="text" class="form-control" name="subject" placeholder="Subject" required>
            </div>

            <div class="col-md-12">
              <textarea class="form-control" name="message" rows="6" placeholder="Message" required></textarea>
            </div>

            <div class="col-md-12 text-center">
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">Your message has been sent. Thank you!</div>

              <button name="save" type="submit">Send Message</button>
            </div>

          </div>
        </form>
      </div>

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
