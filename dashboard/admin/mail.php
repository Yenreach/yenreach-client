<?php
  require_once('../../config/connect.php');
  require_once('includes/session.php');
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');

  $tid = $_SESSION['tid'];
 ?>

<main id="main" class="main">
  <section class="section dashboard">
  <div class="col-12">
    <div class="card recent-sales">
      <div id="msg">
        <?php
          if(isset($_POST['send'])) {
            ini_set( 'display_errors', 1 );
            error_reporting( E_ALL );
            $to = $_POST['recipient'];
            $from = $_POST['sender'];
            $subject = $_POST['subject'];

            $message = $_POST['message'];

            $headers = "From: Yenreach <" . $from . ">";
            if(mail($to,$subject,$message, $headers)) {
              echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Message Sent Successfully!
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
              echo "<script>console.log('Message was sent')</script>";
            } else {
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>An Error occured!</strong> Please try again Later.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
              echo "<script>console.log('Message was not sent')</script>";
            }
          }
         ?>
      </div>

      <div class="card-body">
        <h5 class="card-title"></h5>
        <form role="form" method="post" enctype="multipart/form-data" class="row g-3 needs-validation">
          <div class="row mb-3">
            <label for="company" class="col-md-4 col-lg-3 col-form-label">Sender:</label>
            <div class="col-md-8 col-lg-9">
              <input name="sender" type="text" class="form-control form-control-sm" id="company" value="support@yenreach.com" required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="company" class="col-md-4 col-lg-3 col-form-label">Recipient(s):</label>
            <div class="col-md-8 col-lg-9">
              <input name="recipient" type="text" class="form-control form-control-sm" id="company" value="<?php if(isset($_GET['email'])){echo $_GET['email'];} ?>" placeholder="e.g name@example.com,anothername@example.com ..." required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="company" class="col-md-4 col-lg-3 col-form-label">Subject:</label>
            <div class="col-md-8 col-lg-9">
              <input name="subject" type="text" class="form-control" id="company" placeholder="" required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="company" class="col-md-4 col-lg-3 col-form-label">Message:</label>
            <div class="col-md-8 col-lg-9">
              <textarea name="message" type="text" class="form-control" id="company" style="height: 400px" placeholder="Type message here..." required></textarea>
            </div>
          </div>
          <div class="row mb-3">
            <label for="company" class="col-md-4 col-lg-3 col-form-label"></label>
            <div class="col-md-8 col-lg-9">
                <button class="btn w-100 text-white" style="background: #00C853;" id="send" name="send" type="submit">Send</button>
            </div>
          </div>
        </form>

      </div>

    </div>

  </div><!-- End Recent Sales -->
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
