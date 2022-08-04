<?php
    require_once("../../includes_public/initialize.php");
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
    }
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
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <!--<link href="assets/css/main.css" rel="stylesheet">-->

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
    <a href="https://yenreach.com" class="logo d-flex align-items-center">
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
          <?php if(empty($user->image)) {?>
           <img src="../assets/img/user.png" alt="Profile" class="rounded-circle">
         <?php } else { ?>
           <img src="<?php echo "../".$user->image; ?>" alt="Profile" class="rounded-circle">
         <?php } ?>
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $user->name; ?></span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6></h6>
            <span>User</span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="user_profile">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="user_security">
              <i class="bi bi-question-circle"></i>
              <span>Security</span>
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
      
      <li class="nav-item">
        <li class="nav-item">
          <a class="nav-link collapsed" href="dashboard">
          <i class="bi bi-house"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="nav-heading">Business</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="businesses">
                    <i class="bx bxs-business"></i>
                    <span>My Businesses</span>
                </a>
            </li>
        <?php
            if(isset($_SESSION['business_string']) && !empty($_SESSION['business_string'])){
        ?>    
                <li class="nav-item">
                  <a class="nav-link collapsed" href="business_profile">
                  <i class="bi bi-person-badge"></i>
                    <span>Business Profile</span>
                  </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="facilities">
                  <i class="bi bi-bricks"></i>
                    <span>Facilities</span>
                  </a>
                </li><!-- End Facilities Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="working_hours">
                  <i class="bi bi-clock"></i>
                    <span>Working Hours</span>
                  </a>
                </li><!-- End Facilities Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="branches">
                  <i class="bx bx-store-alt"></i>
                    <span>Branches</span>
                  </a>
                </li><!-- End Facilities Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="socialmedia">
                  <i class="bx bx-globe"></i>
                    <span>SocialMedia/Website</span>
                  </a>
                </li><!-- End Facilities Page Nav -->
                
                <li class="nav-heading">Subscriptions</li>
                <li class="nav-item">
                  <a class="nav-link collapsed" href="subscription_packages">
                  <i class="bx bxs-grid-alt"></i>
                    <span>Subscription Packages</span>
                  </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="business_subscriptions">
                  <i class="bi bi-collection"></i>
                    <span>My Subscriptions</span>
                  </a>
                </li><!-- End Profile Page Nav -->
                
                <li class="nav-heading">Media</li>
                
                <li class="nav-item">
                  <a class="nav-link collapsed" href="photos">
                  <i class="bi bi-image"></i>
                    <span>Photos</span>
                  </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="videos">
                  <i class="bi bi-camera-video"></i>
                    <span>Videos</span>
                  </a>
                </li><!-- End Profile Page Nav -->
            
                <li class="nav-heading">Stat</li>
            
                <li class="nav-item">
                  <a class="nav-link collapsed" href="business_reviews">
                    <i class='bx bxs-message-rounded'></i>
                    <span>Business Review</span>
                  </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="report">
                  <i class="bx bx-line-chart"></i>
                    <span>Business Report</span>
                  </a>
                </li><!-- End Profile Page Nav -->
        <?php
            }
        ?>
    <li class="nav-heading">Miscellaneous</li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="yenreach_billboard">
            <i class="bi bi-easel2"></i>
            <span>Yenreach Billboard</span>
        </a>
    </li>
    <li class="nav-heading">User</li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="user_profile">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="saved_businesses">
            <i class="bi bi-archive"></i>
            <span>Favourites</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="my_reviews">
            <i class="bx bxs-message"></i>
            <span>My Reviews</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="user_security">
            <i class="bi bi-key"></i>
            <span>Security</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </li>
   

  </ul>


</aside><!-- End Sidebar-->


</aside><!-- End Sidebar-->


