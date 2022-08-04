<?php
  require_once('../config/connect.php');
  require_once('includes/header.php');

  $email = $_GET['email'];
  $tran = $_GET['tran'];
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
              <a href="../index.php" class="logo d-flex align-items-center w-auto">
                <img src="../assets/img/logo.png" alt="">
                <span class="d-none d-lg-block text-white">yenreach.com</span>
              </a>
            </div><!-- End Logo -->

            <div class="card mb-3">

              <div class="card-body">

                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Reset Password</h5>

                  <?php
                  if(isset($_POST['save']))
                  {
                    $newpassword= $_POST['newpassword'];
                    $confirmpassword= $_POST['confirmpassword'];
                    $token = $_POST['token'];
                    $check = ($token/2)*999;

                    if($tran!=$check){
                      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>Invalid Token!</strong> Please enter correct token.
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  } else if($newpassword!=$confirmpassword){
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Password Do Not Match!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                } else {
                  $insert_password = mysqli_query($link, "UPDATE `users` SET `password` = '$newpassword' WHERE `email` = '$email'") or die (mysqli_error($link));
                  if($insert_password) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Password Changed Successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                  echo '<meta http-equiv="refresh" content="1;url=auth.php">';
                  } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>An error occured!</strong> Try again Later!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                  }
                }
              }
            ?>

                </div>

                <form role="form" method="post" enctype="multipart/form-data" class="row g-3 needs-validation">

                  <div class="col-12">
                    <label for="yourUsername" class="form-label">Token</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-key"></i></span>
                      <input type="text" name="token" class="form-control" id="username" placeholder="Enter Token from Your Mail" required>
                      <div class="invalid-feedback">Invalid Token.</div>
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="yourUsername" class="form-label">New Password</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                      <input type="password" name="newpassword" class="form-control" id="newpassword" placeholder="Enter your registered" required>
                      <span class="input-group-text" id="inputGroupAppend"><i class="bi bi-eye-slash" onclick="myFunction(this);"></i></span>
                      <div class="invalid-feedback">Please enter your username.</div>
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="yourPassword" class="form-label">Confirm Password</label>
                    <div class="input-group has-validation mb-2">
                      <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                      <input type="password" name="confirmpassword" class="form-control" id="confirmpassword" placeholder="Password" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>
                  </div>

                  <div class="col-12">
                    <button class="btn w-100 text-white" style="background: #00C853;" id="save" name="save" type="submit">Change Password</button>
                  </div>
                </form>

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
      var x = document.getElementById("newpassword");
      if(x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
      e.classList.toggle('bi-eye');
  }
</script>

<?php require_once('includes/footer.php'); ?>
