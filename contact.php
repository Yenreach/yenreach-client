<?php
    // require_once('config/connect.php');
    require_once('includes/header.php');
?>

<main id="main">
<?php
    require_once('layouts/header.php');
?>

    <!-- ======= Breadcrumbs ======= -->
    <!-- <section class="breadcrumbs">
      <div class="container">

        <ol>
          <li><a href="index.php">Home</a></li>
          <li>Contact Us</li>
        </ol>
        <h2>Conatct Us</h2>

      </div>
    </section> -->
    <!-- End Breadcrumbs -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact mt-5">
      <div class="container" data-aos="fade-up">

        <div class="row" data-aos="fade-up" data-aos-delay="100">

          <div class="col-lg-6">

            <div class="row">

              <div class="col-md-12">
                <div class="info-box">
                  <i class="bx bx-map"></i>
                  <h3>Our Address</h3>
                  <p>58, Azikoro Road (Authentic Plaza),<br>Opposite NUJ, Ekeki, Yenegoa. Bayelsa State.</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-box mt-4">
                    <a href="mailto:support@yenreach.com"><i class="bx bx-envelope"></i></a>
                  <h3>Email Us</h3>
                  <p>support@yenreach.com</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-box mt-4">
                  <a href="Tel:09024401562"><i class="bx bx-phone-call"></i></a>
                  <h3>Call Us</h3>
                  <p>(+234) 902 440 1562<br>(+234) 703 719 3301</p>
                </div>
              </div>
            </div>

          </div>

          <div class="col-lg-6">
            <form action="contact.php#sent" method="post" role="form" class="php-email-form">
              <div class="justify-content-center">
                <p class="text-center">Do You Have Any Feedback Or Request? We Will Love To Hear From You!</p>
              </div>
                <div class="form-group">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                </div>
              <div class="form-group">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
              </div>
              <div class="my-3">
                <div class="loading">Loading</div>
                <div class="error-message">Error sending message. Try again Later!</div>
                <div class="sent-message">Your message has been sent. Thank you!</div>
              </div>
              <div class="text-center"><button name="save" type="submit">Send Message</button></div>
            </form>
            <div id="sent">
            <?php
                if(isset($_POST['save'])) {
                  $subject = $_POST['subject'];
                  $email = $_POST['email'];
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
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

<?php
    require_once('includes/footer.php');
?>
