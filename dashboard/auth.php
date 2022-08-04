<?php
  require_once('../config/connect.php');
  require_once('includes/header.php');
  ?>
<style media="screen">
  form i {
    cursor: pointer;
  }
</style>

<main>
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            <div class="d-flex justify-content-center py-4">
              <a href="../index.php" class="logo d-flex align-items-center justify-content-center ">
                <img src="../assets/img/logo.png" alt="">
                <!--<span class="d-none d-lg-block text-white">yenreach.com</span>-->
              </a>
            </div><!-- End Logo -->

            <div class="card mb-3">

              <div class="card-body">

                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>

                  <?php
                  if(isset($_POST['save']))
                  {
                    $username= $_POST['username'];
                    $password= $_POST['password'];
                    $encrypt = base64_encode($password);

                    $query = mysqli_query($link, "SELECT * FROM `users` WHERE `email`='$username' AND `password`='$password'");
                    $row = mysqli_fetch_array($query);

                    if (empty($row))
                    {
                      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Invalid Email or Password!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                    } else if($row['confirmed_email']==0){
                      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Please click on the link sent to your registered email to verify your email before proceeding!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                    }
                    else
                    {
                    //$sql = mysqli_query($link,"UPDATE user SET Signal='On' WHERE Email = '$email'") or die(mysqli_error($link));
                    echo '<hr>';
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      Log In Successfully!
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    session_start();
                    $_SESSION['tid'] = $row['id'];

                    echo "<script>window.location='includes/loader.php?tid=".$_SESSION['tid']."';</script>";

                    }
                  }
                  ?>

                </div>

                <form role="form" method="post" enctype="multipart/form-data" class="row g-3 needs-validation">

                  <div class="col-12">
                    <label for="yourUsername" class="form-label">Username</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-circle"></i></span>
                      <input type="text" name="username" class="form-control" id="username" placeholder="Email" required>
                      <div class="invalid-feedback">Please enter your username.</div>
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="yourPassword" class="form-label">Password</label>
                    <div class="input-group has-validation mb-2">
                      <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                      <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                      <span class="input-group-text" id="inputGroupAppend"><i class="bi bi-eye-slash" onclick="myFunction(this);"></i></span>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>
                  </div>

                  <div class="col-12">
                    <button class="btn w-100 text-white" style="background: #00C853;" id="save" name="save" type="submit">Login</button>
                  </div>
                  <div class="col-12 ">
                    <p class="small mb-0 text-center"><a href="../add-business.php">Create an account</a> || <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalToggle">Reset Password</a></p>
                  </div>
                </form>

                <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel">Reset Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                        <div id="modal-body" class="modal-body">
                          <div class="mx-5">
                            <?php
                              $success = false;
                              if(isset($_POST['send'])) {
                                $email = $_POST['email'];
                                $email_query = mysqli_query($link, "SELECT * FROM `users` WHERE `email`='$email'");
                                $resulted = mysqli_fetch_assoc($email_query);
                                $row_num = mysqli_num_rows($email_query);
                                if($row_num!=0){
                                  $token = Rand(10000,1000);
                                  ini_set( 'display_errors', 1 );
                                  error_reporting( E_ALL );
                                  $to = $email;
                                  $from = "support@yenreach.com";
                                  $subject = "Password Reset";
                                  $username = $resulted['name'];
                                  $message = "Hello $username,\n\nUse the token $token to reset your passwoed.\n\n\n\nThanks.\nYenreach Support Team.";

                                  $headers = "From: Yenreach <" . $from . ">";
                                  if(mail($to,$subject,$message, $headers)) {
                                    echo "<script>console.log('Message was sent')</script>";
                                  } else {
                                    echo "<script>console.log('Message was not sent')</script>";
                                  }
                                  $success = true;
                                  $encrypt = ($token/2)*999;
                                  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    Token has been sent to your mail! Use token to reset password!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
                                  echo '<meta http-equiv="refresh" content="1;url=reset_password.php?email='.$email.'&tran='.$encrypt.'">';
                                }else {
                                  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    Invalid Email! Please enter a registered email address.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
                                }
                              } if(!$success) {
                             ?>
                            <form role="form" method="post" enctype="multipart/form-data" class="row g-3 needs-validation">
                              <div class="col-12">
                                <div class="input-group has-validation">
                                  <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-circle"></i></span>
                                  <input type="text" name="email" class="form-control" id="email" placeholder="Registered Email" required>
                                  <div class="invalid-feedback">Invalid Email Address.</div>
                                </div>
                              </div>

                            <div class="col-12">
                              <button class="btn w-100 text-white" style="background: #00C853;" id="send" name="send" type="submit">Submit</button>
                            </div>
                            </form>
                          <?php } ?>
                          </div>
                        </div>
                      <div class="modal-footer">

                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <div class="credits text-center text-white" style="font-size: 12px;">
              &copy; 2021 Copyright <strong><span>yenreach.com</span></strong>. All Rights Reserved
            </div>

          </div>
        </div>
      </div>

    </section>

  </div>
</main><!-- End #main -->

<script type="text/javascript">
    function myFunction(e){
      var x = document.getElementById("password");
      if(x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
      e.classList.toggle('bi-eye');
  }
</script>

<?php require_once('includes/footer.php'); ?>
