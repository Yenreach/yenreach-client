<?php
  require_once('../config/connect.php');
  $id = $_SESSION['tid'];
  $bar_user_query = mysqli_query($link, "SELECT * FROM `users` WHERE `id`='$id'");
  $bar_user_result = mysqli_fetch_assoc($bar_user_query);
 ?>
<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="profile.php?tid=<?php echo $id; ?>" class="logo d-flex align-items-center">
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
          <?php if($bar_user_result['image']=="") {?>
           <img src="../assets/img/user.png" alt="Profile" class="rounded-circle">
         <?php } else { ?>
           <img src="<?php echo "../".$bar_user_result['image']; ?>" alt="Profile" class="rounded-circle">
         <?php } ?>
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $bar_user_result['name'] ?></span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?php echo $bar_user_result['name'] ?></h6>
            <span>Administrator</span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="user-profile.php?tid=<?php echo $id; ?>">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="contact.php?tid=<?php echo $id; ?>">
              <i class="bi bi-question-circle"></i>
              <span>Need Help?</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="logout.php?tid=<?php echo $id; ?>">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->

</header><!-- End Header -->
