<?php
  require_once('config/connect.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Yenreach.com - Online Business Directory</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome/css/all.css" rel="stylesheet" />

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Presento - v3.5.0
  * Template URL: https://bootstrapmade.com/presento-bootstrap-corporate-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <!-- Facebook Pixel Code -->
  <script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '235061024369195');
  fbq('track', 'AddMyBusiness');
  </script>
  <noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=235061024369195&ev=PageView&noscript=1"
  /></noscript>
<!-- End Facebook Pixel Code -->
</head>

<body>

<style media="screen">
  form i {
    cursor: pointer;
  }
</style>

  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <ol>
          <li><a href="index.php">Home</a></li>
          <li>Add My Business</li>
        </ol>
        <h2>Add My Business</h2>

      </div>
    </section><!-- End Breadcrumbs -->

    <section class="inner-page">
      <div class="container col-xl-10 col-xxl-8 p-4 " data-aos="fade-up">
        <div class="row g-lg-5">
            <?php
            $success = "false";
            if(isset($_POST['save'])) {
              $account_user = $_POST['account_user'];
              if($account_user=="new"){
                $name = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
              }else{
                $email = $_POST['email'];
              }
              $bus_name = $_POST['bus_name'];
              $address = $_POST['address'];
              $state = $_POST['state'];
              $phonenumber = $_POST['phonenumber'];
              $category = $_POST['category'];
              $encrypt = base64_encode($password);

              if($account_user == 'user'){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Please Select User.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }else if($state=='state'){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Please Select State Where Business is Located.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }else if($category=='category'){
                  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Error!</strong> Please Select Business Category.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                }
              else if($account_user=="new" && !empty(mysqli_fetch_assoc(mysqli_query($link, "SELECT `email` FROM `users` where `email` = '$email'")))) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> A user with the registered email already exist.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
              } else {
                if($account_user=="new"){
                  $insert1 = mysqli_query($link, "INSERT INTO `users` (`id`, `name`, `email`, `password`, `listed`, `admin`, `image`, `datecreated`, `lastmodified`, `modifiedby`, `confirmed_email`) VALUES (NULL, '$name', '$email', '$password', '1', '0', '', NOW(), NOW(), 0, 0)") or die (mysqli_error($link));
                }
                $query8 = mysqli_query($link, "SELECT `name`, `id` FROM `users` WHERE `email`='$email'");
                $results8 = mysqli_fetch_assoc($query8);
                $id = $results8['id'];
                if($category=="others"){
                  $category = $_POST['other_category'];
                  $insert4 = mysqli_query($link, "INSERT INTO `categories` (`id`, `category`, `icon`, `listings`, `ranking`, `datecreated`, `lastmodified`, `modifiedby`) VALUES (NULL, '$category', '', NULL, NULL, NOW(), NOW(), '$id')") or die (mysqli_error($link));
                }
                if($category=="job seekers"){
                  $sourcepath = $_FILES["job_cv"]["tmp_name"];
                  $targetpath = "assets/cvs/" . $_FILES["job_cv"]["name"];
                  $didUpload = move_uploaded_file($sourcepath,$targetpath);
                } else {
                  $targetpath = "";
                }
                $insert2 = mysqli_query($link, "INSERT INTO `businesses` (`id`, `name`, `description`, `user`, `subscription_type`, `category`, `address`, `state`, `phonenumber`, `whatsapp`, `email`, `website`, `facebook_link`, `instagram_link`, `youtube_link`, `linkedin_link`, `working_hours`, `cv`, `datecreated`, `lastmodified`, `modifiedby`, `experience`) VALUES (NULL, '$bus_name', '', '$id', '1', '$category', '$address', '$state', '$phonenumber', '', '$email', '', '', '', '', '', '', '$targetpath', NOW(), NOW(), '$id', '')") or die (mysqli_error($link));
                $query9 = mysqli_query($link, "SELECT `id` FROM `businesses` WHERE `name`='$bus_name'");
                $results9 = mysqli_fetch_assoc($query9);
                $id2 = $results9['id'];
                $expiry_date = strtotime("+30 days");
                $expiry_date = date('Y-m-d h:m:s', $expiry_date);
                $insert3 = mysqli_query($link, "INSERT INTO `subscriptions` (`id`, `subscription_status`, `subscription_date`, `expiry_date`, `subscription_type`, `user`, `business`, `datecreated`, `lastmodified`, `modifiedby`) VALUES (NULL, '1', NOW(), '$expiry_date', '1', '$id', '$id2', NOW(), NOW(), '$id')") or die (mysqli_error($link));

                if($account_user=="new" && !($insert1) )
                {
                  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Unable to register User!</strong> please try again Later.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';

              } else if( !($insert2) ) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Unable to Add Business!</strong> please try again Later.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }else if(!($insert3)){
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Unable to Add Subscription!</strong> please try again Later.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
                else{
                  ini_set( 'display_errors', 1 );
                  error_reporting( E_ALL );
                  $to = $email;
                  $from = "support@yenreach.com";
                  $subject = "Welcome To Yenreach";
                  $username = $results8['name'];
                  $message = "Dear $username,\n\nThank you for signing up on Yenreach. Your Business is just one step from your customers.\n\nPlease click on the link https://www.yenreach.com/confirm_mail.php?tid=$id to verify your email.\n\n\n\nThanks.\nYenreach Support Team.";

                  $headers = "From: Yenreach <" . $from . ">";
                  if(mail($to,$subject,$message, $headers)) {
                    echo "<script>console.log('Message was sent')</script>";
                    echo '<div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Registration Successful!</h4>
                    <hr>
                    <p class="mb-0"><strong>Check your mailbox</strong><br>We\'ve sent to you with a link to confirm your account.<br><br><strong>Didn\'t get the email</strong><br>If you don\'t see an within 5 minutes. One of these things could have happened:<ol><li>The email might be in your spam folder. (If you use Gmail, please check your Promotions folder as well.)</li><li>The email address you entered had a typo.</li><li>You accidentally entered another email address. (Usually happens with auto-complete.)</li><li>We can\'t deliver the email the address. (Usually because of corporate firewalls or filtering.)</li></ol></p>
                  </div>';
                  } else {
                    echo "<script>console.log('Message was not sent')</script>";
                    echo '<div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Registration Successful!</h4>
                    <hr>
                    <p class="mb-0">System was unable to send verification Link at the moment. Please contact us using the contact form or via email: support@yenreach.com to fix these.</p>
                  </div>';
                  }
                  $success = "true";
                }
              }
            } if($success == 'false') {
           ?>

          <div class="col-lg-7 text-left text-lg-start">
            <h1 class="display-3 fw-bold lh-1 mb-3" style="color: #00C853">Add Your Business For<span style="color: #083640"> FREE</span></h1>
          </div>
          <div class="col-md-10 mx-auto col-lg-5" style="border-left: 2px solid #00C853;">
            <div class="d-flex justify-content-center">
              <p class="col-lg-10 fs-6 text-center" style="font-size: 14px;">Tell Us About Your Business So Customers Can Find You Easily!</p>
            </div>
            <form method="POST" role="form" class="p-4 p-md-5 border rounded-3 bg-light" style="font-size: 14px;" enctype="multipart/form-data">
              <p style="font-siz: 14px;">PERSONAL DETAILS</p>
              <div class="form-floating mb-3">
                <select id="account_user" name="account_user" class="form-control" onchange="load(this);" required>
                  <option value="user" selected>Select User</option>
                  <option value="new">New User</option>
                  <option value="existing">Existing User</option>
                </select>
                <label for="account_user"></label>
              </div>
              <div id="personal">

              </div>
              <hr class="py-2">
              <p style="font-siz: 14px;">BUSINESS DETAILS</p>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="company_name" name="bus_name" placeholder="Business Name" required>
                <label for="company_name">Business Name <span class="text-danger">*</span></label>
                <div class="form-text">Use all Characters except:  ' " </div>
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="address" name="address" placeholder="Business Address" required>
                <label for="address">Business Address <span class="text-danger">*</span></label>
              </div>
              <div class="row mb-3">
                <div class="form-floating col-md-6 mb-3">
                  <select id="state" name="state" class="form-control form-select" required>
                    <option value="state" selected>State*</option>
                    <?php $query6 = mysqli_query($link, "SELECT * FROM `states` ORDER BY `state` ASC");
                      while($results6 = mysqli_fetch_assoc($query6)) { ?>
                      <option value="<?php echo strtolower($results6['state']); ?>"><?php echo strtoupper($results6['state']) ?></option>
                    <?php } ?>
                  </select>
                  <label for="inputState"></label>
                </div>
                <div class="form-floating col-md-6">
                  <input type="text" class="form-control" id="phomenumber" name="phonenumber" placeholder="Phone Number" required>
                  <label for="phomenumber">Phone Number <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="mb-4">
                <select id="category" class="form-control form-select" name="category" onChange="load_it(this);" required>
                  <option value="category" selected>Select Category*</option>
                  <?php $query7 = mysqli_query($link, "SELECT `category` FROM `categories` ORDER BY `category` ASC");
                    while($results7 = mysqli_fetch_assoc($query7)) { ?>
                    <option value="<?php echo strtolower($results7['category']); ?>"><?php echo strtoupper($results7['category']) ?></option>
                  <?php } ?>
                  <option value="others">Others</option>
                </select>
                <div class="form-text">You can select more than one categories.</div>
              </div>
              <div id="others" class="mb-3">
                <span class="text-danger">* Required</span>
                <br />
                * Required
              </div>
              <button class="w-100 btn btn-lg btn-primary" name="save" type="submit">Sign up</button>
              <hr class="my-4">
              <small class="text-muted">By clicking Sign up, you agree to the terms of use.</small>
            </form>
          </div>
          <?php } ?>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <script>
    function load_it(e) {
      let val=document.getElementById('others');
      if(e.value === "others") {
        val.innerHTML = '<input type="text" class="form-control" id="other_category" name="other_category" placeholder="Type of Business" required>';
      } else if(e.value === "job seekers"){
        val.innerHTML = '<input class="form-control" type="file" id="formFile" name="job_cv" lang="en" required><label class="mt-2" for="formFile">Upload CV (.docx or pdf)</label>';
      }
      else {
        val.innerHTML = '';
      }
    }
  </script>


  <script>
  function load(e) {
    var val2=document.getElementById('personal');
    if(e.value === "new") {
      val2.innerHTML = '<div class="form-floating mb-3"><input type="text" class="form-control" id="name" name="username" placeholder="Your Name" required><label for="name">Your Name <span class="text-danger">*</span></label><div class="form-text">This is your personal name. e.g Daniel Dordor</div></div><div class="form-floating mb-3"><input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required><label for="email">Email address <span class="text-danger">*</span></label></div><div class="form-floating input-group mb-3"><input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required><label for="floatingPassword">Password <span class="text-danger">*</span></label><span class="input-group-text" id="inputGroupAppend"><i class="bi bi-eye-slash" onclick="myFunction(this);"></i></span></div><div class="form-floating mb-3"><input type="password" class="form-control" id="floatingPassword2" name="con_password" placeholder="Confirm Password" required><label for="floatingPassword2">Confirm Password <span class="text-danger">*</span></label></div>';
    }
    else if(e.value==="existing"){
      val2.innerHTML = '<div class="form-floating mb-3"><input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required><label for="email">Email address <span class="text-danger">*</span></label></div>';
    } else {
      val2.innerHTML = '';
    }
  }
  </script>

  <script type="text/javascript">
      function myFunction(e){
        var x = document.getElementById("floatingPassword");
        if(x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }
        e.classList.toggle('bi-eye');
    }
  </script>

  <?php
    require_once('includes/footer.php');
  ?>
