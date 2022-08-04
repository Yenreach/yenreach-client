<!-- ======= Header ======= -->

<header id="header" class="fixed-top d-flex align-items-center">
  <div class="container d-flex align-items-center">
    <!-- Uncomment below if you prefer to use an text logo -->
    <!-- <h1 class="logo me-auto"><a href="index.php">Yenreach<span>.</span>com</a></h1>-->
    <a href="https://yenreach.com" class="logo me-auto"><img src="./assets/img/logo.png" alt=""></a>

    <nav id="navbar" class="navbar order-last order-lg-0">
      <ul>
        <li><a class="active" href="https://yenreach.com">Home</a></li>
        <li><a class="" href="explorer">Explore</a></li>
        <li><a class="" href="about">About</a></li>
        <li><a class="" href="blog">Blog</a></li>
        <li><a class="" href="contact">Contact</a></li>
        <?php
            if(isset($_SESSION['verify_string']) && !empty($_SESSION['verify_string'])){
                
            } else {
        ?>
                <li><a class="" href="users/dashboard">Log In</a></li> 
        <?php
            }
        ?>
      </ul>
      <i class="bi bi-list mobile-nav-toggle text-dark"></i>
      <i class="bi bi-x mobile-nav-toggle close "></i>
    </nav><!-- .navbar -->
    <?php
        if(isset($_SESSION['verify_string']) && !empty($_SESSION['verify_string'])){
    ?>
            <a href="users/dashboard" class="get-started-btn">My Dashboard</a>    
    <?php
        } else {
    ?>
            <a href="users/signup?page=/users/add_new_business" class="get-started-btn">Add My Business</a>
    <?php
        }
    ?>
  </div>
</header><!-- End Header -->
