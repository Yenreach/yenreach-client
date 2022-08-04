<?php
    /*require_once("../../includes_public/initialize.php");
    $gurl = "fetch_user_by_string_api.php?string=".$_SESSION['verify_string'];
    $users = perform_get_curl($gurl);
    if($users){
        if($users->status == "success"){
            $user = $users->data;
        } else {
            die($users->message);
        }
    } else {
        die("User Link Broken");
    }*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Yenreach || Dashboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.1.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="profile" class="logo d-flex align-items-center">
      <img src="../assets/img/logo.png" alt="">
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle " href="#">
          <i class="bi bi-search"></i>
        </a>
      </li><!-- End Search Icon-->

      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <?php //if(empty($user->image)) {?>
           <img src="../assets/img/user.png" alt="Profile" class="rounded-circle">
         <?php //} else { ?>
           <img src="<?php //echo "../".$user->image; ?>" alt="Profile" class="rounded-circle">
         <?php //} ?>
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $user->name; ?></span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?php echo $user->name; ?></h6>
            <span>User</span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="profile">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="contact">
              <i class="bi bi-question-circle"></i>
              <span>Need Help?</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="logout">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">
      
      <li class="nav-item"

    <li class="nav-heading">Others</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="faq">
        <i class="bi bi-question-circle"></i>
        <span>F.A.Q</span>
      </a>
    </li><!-- End F.A.Q Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="https://yenreach.com">
        <i class="bi bi-house"></i>
        <span>Back to Website</span>
      </a>
    </li><!-- End Home Page Page Nav -->


  </ul>

</aside><!-- End Sidebar-->

        <main id="main" class="main">
          <div class="file__upload">
            <div class="header">
              <p><i class="fa fa-cloud-upload fa-2x"></i><span><span>up</span>load</span></p>			
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="body">
              <!-- Sharable Link Code -->
              
        
              <input type="file" name="file" id="upload" required>
              <label for="upload">
                <i class="fa fa-file-text-o fa-3x"></i>
                <p>
                  <strong>Drag and drop</strong> files here<br>
                  or <span>browse</span> to begin the upload
                </p>
              </label>
              <button name="upload" class="btn">Upload</button>
            </form>
          </div>
        </main>
     <!-- ======= Footer ======= -->
     <footer id="footer" class="footer" style="margin-top:100px ">
     <div class="copyright">
       &copy; 2021 Copyright <strong><span>yenreach.com</span></strong>. All Rights Reserved
     </div>
   </footer><!-- End Footer -->
        <!-- Vendor JS Files -->
        <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
        <script src="../assets/vendor/php-email-form/validate.js"></script>
        <script src="../assets/vendor/quill/quill.min.js"></script>
        <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
        <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
        <script src="../assets/vendor/chart.js/chart.min.js"></script>
        <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
        <script src="../assets/vendor/echarts/echarts.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        
        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>
        <script src="assets/js/extra_script.js"></script>
    </body>
</html>

