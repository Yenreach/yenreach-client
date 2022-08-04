<?php
  require_once('config/connect.php');

  $select_businesses = mysqli_query($link, "SELECT * FROM `businesses`");
  while($select_result = mysqli_fetch_assoc($select_businesses)) {
    $bus_id = $select_result['id'];
    $select_sub = mysqli_query($link, "SELECT `expiry_date` FROM `subscriptions` WHERE `business`='$bus_id'");
    $sub_result = mysqli_fetch_assoc($select_sub);
    if((strtotime($sub_result['expiry_date']) - strtotime('today')) < 0) {
      $update_sub = mysqli_query($link, "UPDATE `subscriptions` SET `subscription_type` = 1, `lastmodified` = NOW(), `modifiedby` = 0 WHERE `business` = '$bus_id'") or die (mysqli_error($link));
      $update_bus = mysqli_query($link, "UPDATE `businesses` SET `subscription_type` = 1, `lastmodified` = NOW(), `modifiedby` = 0 WHERE `id` = '$bus_id'") or die (mysqli_error($link));
    }
  }
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
  fbq('track', 'HomePageVisit');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=235061024369195&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<style>
    .hero-section {
        width: 100%;
  height: 100vh;
background: linear-gradient(90deg, rgba(8,54,64,0.8911659663865546) 35%, rgba(8,54,64,0.7643032212885154) 100%),url(assets/img/hero.jpg)!important;
        background-size:cover!important;
        background-repeat:center right!important;
        background-repeat:no-repeat!important;
    }
</style>
</head>

<body>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top d-flex align-items-center">
  <div class="container d-flex align-items-center">
    <!-- Uncomment below if you prefer to use an text logo -->
    <!-- <h1 class="logo me-auto"><a href="index.php">Yenreach<span>.</span>com</a></h1>-->
    <a href="index.php" class="logo me-auto"><img src="assets/img/logo.png" alt=""></a>

    <nav id="navbar" class="navbar order-last order-lg-0">
      <ul>
        <li><a class="active" href="index.php">Home</a></li>
        <li><a class="" href="explorer.php">Explore</a></li>
        <li><a class="" href="about.php">About</a></li>
        <li><a class="" href="blog.php">Blog</a></li>
        <li><a class="" href="contact.php">Contact</a></li>
        <li><a class="" href="users/dashboard">Log In</a></li>
      </ul>
      <i class="bi bi-list mobile-nav-toggle"></i>
    </nav><!-- .navbar -->

    <a href="users/signup" class="get-started-btn">Add My Business</a>
  </div>
</header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center hero-section">

    <div class="container" data-aos="zoom-out" data-aos-delay="100">
      <div class="row ">
        <div class="col-10 col-md-12 col-lg-10 mx-auto flex d-flex flex-column align-items-center justify-content-center">
          <h1 class='fw-bold' >Welcome to yenreach.com</h1>
          <p id="header-caption">The Fastest Growing Business Directory Platform in Nigeria</p>
          <h2>What are you searching for today?</h2>
          <form action="includes/search.php" method="POST" enctype="multipart/form-data" class='col-12 col-md-10 col-lg-10 mx-auto mx-md-0'>
            <div class="input-group">
              <input class="form-control search-text py-2" list="datalistOptions" id="exampleDataList" placeholder="e.g carpenter, restaurant, stylist, doctor, etc" name="category" required>
              <datalist id="datalistOptions">
                <?php $query8 = mysqli_query($link, "SELECT `category` FROM `categories` ORDER BY `category` ASC");
                while($results8 = mysqli_fetch_assoc($query8)) { ?>
                <option value="<?php echo strtoupper($results8['category']) ?>">
                <?php } ?>
              </datalist>
              <select class="form-select py-2" id="inputGroupSelect04" aria-label="Example select with button addon" name="location" required>
                <option selected>Location...</option>
                <?php $query7 = mysqli_query($link, "SELECT * FROM `states` ORDER BY `state` ASC");
                while($results7 = mysqli_fetch_assoc($query7)) { ?>
                  <option><?php echo strtoupper($results7['state']) ?></option>
                <?php } ?>
              </select>
              <button class='btn py-2 px-2  col-4 col-md-2 text-white d-none d-md-block'  type="submit" name="search" style='background:#00C853'>Search</button>
            </div>
            <div>
             <button class='btn py-2 mt-3 col-12 text-white d-md-none'  type="submit" name="search" style='background:#00C853'>Search</button>
            </div>
        </form>
        </div>
        
    </div>

  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= Tabs Section ======= -->
    <section id="tabs" class="tabs">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Browse Businesses By Category</h2>
        </div>

        <ul class="nav nav-tabs row d-flex">
          <li class="nav-item col-3 "   data-bs-toggle="tooltip" title="Hotels" >
            <div class="nav-link active border-0 show" style='border-radius:5px'  data-bs-toggle="tab" data-bs-target="#tab-1">
                   <i class="fa-solid fa-hotel"  style='font-size:16px;'></i>
              <h4 class="d-none d-lg-block" style='font-weight:400'>Hotels</h4>
            </div>
          </li>
          <li class="nav-item col-3 "   data-bs-toggle="tooltip" title="Restaurants & Bar" >
            <div class="nav-link border-0" data-bs-toggle="tab" data-bs-target="#tab-2" style='border-radius:5px'>
              <i class="fa-solid fa-utensils fa-sm" style='font-size:16px;'></i>
              <h4 class="d-none d-lg-block" style='font-weight:400'>Restaurants & Bars</h4>
            </div>
          </li>
          <li class="nav-item col-3 "    data-bs-toggle="tooltip" title="Schools">
            <div class="nav-link border-0" data-bs-toggle="tab" data-bs-target="#tab-3" style='border-radius:5px'>
              <i class="fa-solid fa-school fa-sm" style='font-size:16px;'></i>
              <h4 class="d-none d-lg-block" style='font-weight:400'>Schools</h4>
            </div>
          </li>
          <li class="nav-item col-3 "  data-bs-toggle="tooltip" title="Fashion Designers">
            <div class="nav-link border-0" data-bs-toggle="tab" data-bs-target="#tab-4" style='border-radius:5px'>
              <i class="fa-solid fa-shirt fa-sm" style='font-size:16px'></i>
              <h4 class="d-none d-lg-block" style='font-weight:400'>Fashion Designers</h4>
            </div>
          </li>
        </ul>
        <div class='w-100 h-100 d-flex justify-content-center align-items-center'>
            
          <select class="w-50 border-dark py-2 px-2 mt-4 mb-4" style='border-radius:5px;' id="inputGroupSelect04" aria-label="Example select with button addon" name="location" id="location" onChange="getState(this);" required>
            <option  selected>See More Categories</option>
            <?php $cat_query = mysqli_query($link, "SELECT `category` FROM `categories` ORDER BY `category` ASC");
            while($cat_result = mysqli_fetch_assoc($cat_query)) { ?>
              <option class='w-50' value="<?php echo $cat_result['category']; ?>"><?php echo strtoupper($cat_result['category']); ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="tab-content">
          <div class="tab-pane active show" id="tab-1">
            <div id="home-list" class="row">
              <?php
                $search_string = "%hotel%";
                $call = mysqli_query($link, "SELECT * FROM `businesses` WHERE `category` LIKE '$search_string' ORDER BY `subscription_type` DESC");
                $count = 0;
                while($results = mysqli_fetch_assoc($call)) {
                  $result = $results['id'];
                  $query = mysqli_query($link, "SELECT `subscription_status`, `business` FROM `subscriptions` WHERE `subscription_status`=1 AND `business`=$result");
                  $result1 =  mysqli_fetch_assoc($query);
                  $status = $result1['business'];
                  if($status==0) {
                    continue;
                  }else{
                    $query2 = mysqli_query($link, "SELECT `image_path`, `business` FROM `images` WHERE `business`=$result");
                    $results2 = mysqli_fetch_assoc($query2);
                    $categories = explode(",", $results3['category']);
                    $query4 = mysqli_query($link, "SELECT AVG(`ratings`) as average, COUNT(`ratings`) as number FROM (SELECT `ratings`, `business` FROM `reviews` WHERE `business`=$result) as the_averag");
                    $results4 = mysqli_fetch_assoc($query4);

                    $review_query = mysqli_query($link, "SELECT * FROM `reviews` WHERE `business`='$result'");
                    $results5 = mysqli_num_rows($review_query);
                  ?>
                  <div class="col-md-4">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                      <div class="card d-flex flex-column justify-content-around align-items-start mb-3" style='height:30rem'>
                          <div class='w-100' style='height:18rem'>
                    <img src="<?php echo $results2['image_path']?>" class="card-img-top h-100 w-100"  alt="<?php if($result2['image_path']=="") {echo "";} else {echo $result2['image_path'];} ?>">
                              
                          </div>
                        <div class="card-body w-100 d-flex flex-column justify-content-around align-items-start" style='height:12rem'>
                          <h4 class="my-0 text-wrap" style="color: #00C853;"><?php echo strtoupper($results['name'])?></h4>
                          <p class="text-muted" style="font-size: 14px;"><?php if(!empty($results4['average'])) { $rating = $results4['average']; for($i=0; $i<round($rating); $i++) {echo "<i class=\"bi bi-star-fill\"></i>" ?>
                          <?php } echo " ".$results5['number'];}  else{ echo "No ";}?> review</p>
                            <p class="mb-1 mt-0 text-wrap text-dark" style="font-size: 14px;"><?php echo strtoupper($results['category']); ?></p>
                            <a href="portfolio-details.php?id=<?php echo $result; ?>" class="btn btn-success text-white px-4 py-2 mt-2" style='font-size:14px'>View Business</a>
                        </div>
                      </div>
                    </div>
                  </div>

                <?php $count++; if($count==6) {break;} } }?>
            </div>
            <button class="btn bg-success mx-auto d-flex justify-content-center mt-3"><a class='text-white' href="explorer.php?category=hotel">View All</a></button>
          </div>
          <div class="tab-pane" id="tab-2">
            <div id="home-list" class="row">
              <?php
                $search_string = "%restaurant%";
                $call = mysqli_query($link, "SELECT * FROM `businesses` WHERE `category` LIKE '$search_string' ORDER BY `subscription_type` DESC");
                $count = 0;
                while($results = mysqli_fetch_assoc($call)) {
                  $result = $results['id'];
                  $query = mysqli_query($link, "SELECT `subscription_status`, `business` FROM `subscriptions` WHERE `subscription_status`=1 AND `business`=$result");
                  $result1 =  mysqli_fetch_assoc($query);
                  $status = $result1['business'];
                  if($status==0) {
                    continue;
                  }else{
                    $query2 = mysqli_query($link, "SELECT `image_path`, `business` FROM `images` WHERE `business`=$result");
                    $results2 = mysqli_fetch_assoc($query2);
                    $categories = explode(",", $results3['category']);
                    $query4 = mysqli_query($link, "SELECT AVG(`ratings`) as average, COUNT(`ratings`) as number FROM (SELECT `ratings`, `business` FROM `reviews` WHERE `business`=$result) as the_averag");
                    $results4 = mysqli_fetch_assoc($query4);
                  ?>
                  <div class="col-md-4">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                      <div class="card d-flex flex-column justify-content-around align-items-start mb-3" style='height:30rem'">
            <div class='w-100' style='height:18rem'>
                    <img src="<?php echo $results2['image_path']?>" class="card-img-top h-100 w-100" alt="<?php if($result2['image_path']=="") {echo "";} else {echo $result2['image_path'];} ?>">
            </div>    
                        <div class="card-body w-100 d-flex flex-column align-items-start justify-content-around" style='height:12rem'>
                          <h4 class="my-0 text-wrap" style="color: #00C853;"><?php echo strtoupper($results['name'])?></h4>
                          <p class="mb-1 mt-0 text-wrap text-dark" style="font-size: 14px;"><?php if(!empty($results4['average'])) { $rating = $results4['average']; for($i=0; $i<round($rating); $i++) {echo "<i class=\"bi bi-star-fill\"></i>" ?>
                          <?php } echo " ".$results4['number'];}  else{ echo "No ";}?> review</p>
                            <p class="mb-1 mt-0 text-wrap text-warning" style="font-size: 14px;"><?php echo strtoupper($results['category']); ?></p>
                            <a href="portfolio-details.php?id=<?php echo $result; ?>" class="btn bg-success text-white px-4 py-2 mt-2" style='font-size:14px'>View Business</a>
                        </div>
                      </div>
                    </div>
                  </div>

                <?php $count++; if($count==6) {break;} } }?>
            </div>
        <button class="btn bg-success mx-auto d-flex justify-content-center mt-3"><a class='text-white' href="explorer.php?category=restaurant">View All</a></button>
          </div>
          <div class="tab-pane" id="tab-3">
            <div id="home-list" class="row">
              <?php
                $search_string = "%school%";
                $call = mysqli_query($link, "SELECT * FROM `businesses` WHERE `category` LIKE '$search_string' ORDER BY `subscription_type` DESC");
                $count = 0;
                while($results = mysqli_fetch_assoc($call)) {
                  $result = $results['id'];
                  $query = mysqli_query($link, "SELECT `subscription_status`, `business` FROM `subscriptions` WHERE `subscription_status`=1 AND `business`=$result");
                  $result1 =  mysqli_fetch_assoc($query);
                  $status = $result1['business'];
                  if($status==0) {
                    continue;
                  }else{
                    $query2 = mysqli_query($link, "SELECT `image_path`, `business` FROM `images` WHERE `business`=$result");
                    $results2 = mysqli_fetch_assoc($query2);
                    $categories = explode(",", $results3['category']);
                    $query4 = mysqli_query($link, "SELECT AVG(`ratings`) as average, COUNT(`ratings`) as number FROM (SELECT `ratings`, `business` FROM `reviews` WHERE `business`=$result) as the_averag");
                    $results4 = mysqli_fetch_assoc($query4);
                  ?>
                  <div class="col-md-4">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                      <div class="card d-flex flex-column justify-content-around align-items-start mb-3" style='height:30rem'>
                                  <div class='w-100' style='height:18rem'>

                        <img src="<?php echo $results2['image_path']?>" class="card-img-top w-100 h-100" alt="<?php if($result2['image_path']=="") {echo "";} else {echo $result2['image_path'];} ?>">
                                  </div>
                        <div class="card-body w-100 d-flex flex-column justify-content-around align-items-start" style='height:12rem'>
                          <h4 class="my-0 text-wrap" style="color: #00C853;"><?php echo strtoupper($results['name'])?></h4>
                          <p class="mb-1 mt-0 text-wrap text-dark" style="font-size: 14px;"><?php if(!empty($results4['average'])) { $rating = $results4['average']; for($i=0; $i<round($rating); $i++) {echo "<i class=\"bi bi-star-fill\"></i>" ?>
                          <?php } echo " ".$results4['number'];}  else{ echo "No ";}?> review</p>
                            <p class="mb-1 mt-0 text-wrap text-dark" style="font-size: 14px;"><?php echo strtoupper($results['category']); ?></p>
                            <a href="portfolio-details.php?id=<?php echo $result; ?>" class="btn bg-success text-white px-4 py-2 mt-4" style='font-size:14px'>View Business</a>
                        </div>
                      </div>
                    </div>
                  </div>

                <?php $count++; if($count==6) {break;} } }?>
            </div>
                    <button class="btn bg-success mx-auto d-flex justify-content-center mt-3"><a class='text-white' href="explorer.php?category=school">View All</a></button>

          </div>
          <div class="tab-pane" id="tab-4">
            <div id="home-list" class="row">
              <?php
                $search_string = "%fashion%";
                $call = mysqli_query($link, "SELECT * FROM `businesses` WHERE `category` LIKE '$search_string' ORDER BY `subscription_type` DESC");
                $count = 0;
                while($results = mysqli_fetch_assoc($call)) {
                  $result = $results['id'];
                  $query = mysqli_query($link, "SELECT `subscription_status`, `business` FROM `subscriptions` WHERE `subscription_status`=1 AND `business`=$result");
                  $result1 =  mysqli_fetch_assoc($query);
                  $status = $result1['business'];
                  if($status==0) {
                    continue;
                  }else{
                    $query2 = mysqli_query($link, "SELECT `image_path`, `business` FROM `images` WHERE `business`=$result");
                    $results2 = mysqli_fetch_assoc($query2);
                    $categories = explode(",", $results3['category']);
                    $query4 = mysqli_query($link, "SELECT AVG(`ratings`) as average, COUNT(`ratings`) as number FROM (SELECT `ratings`, `business` FROM `reviews` WHERE `business`=$result) as the_averag");
                    $results4 = mysqli_fetch_assoc($query4);
                  ?>
                  <div class="col-md-4">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                      <div class="card d-flex flex-column justify-content-around align-items-start mb-3" style='height:30rem'>
                      <div class="w-100" style='height:18rem'>
                        <img src="<?php echo $results2['image_path']?>" class="card-img-top h-100 w-100" alt="<?php if($result2['image_path']=="") {echo "";} else {echo $result2['image_path'];} ?>">
                        </div>

                        <div class="card-body w-100 d-flex flex-column justify-content-around align-items-start " style='height:12rem'>
                          <h4 class="my-0 text-wrap" style="color: #00C853;"><?php echo strtoupper($results['name'])?></h4>
                          <p><?php if(!empty($results4['average'])) { $rating = $results4['average']; for($i=0; $i<round($rating); $i++) {echo "<i class=\"bi bi-star-fill\"></i>" ?>
                          <?php } echo " ".$results5;}  else{ echo "No ";}?> review</p>
                            <p class="mb-1 mt-0 text-wrap text-dark" style="font-size: 14px;"><?php echo strtoupper($results['category']); ?></p>
                            <a href="portfolio-details.php?id=<?php echo $result; ?>" class="btn bg-success text-white px-4 py-2 mt-2" style='font-size:14px'>View Business</a>
                        </div>
                      </div>
                    </div>
                  </div>

                <?php $count++; if($count==6) {break;} } }?>
            </div>
                    <button class="btn bg-success mx-auto d-flex justify-content-center mt-3"><a class='text-white' href="explorer.php?category=fashion">View All</a></button>

            
          </div>

        </div>

      </div>
     
    </section><!-- End Tabs Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg ">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Hot and Trending Businesses</h2>
          <p>Check out Some of The Most Visited Business Pages On Yenreach.</p>
        </div>

        <div id="home-list" class="row">

          <?php
            $query = mysqli_query($link, "SELECT `business` FROM `subscriptions` WHERE `subscription_status`=1 AND`subscription_type`=4");
            $count = 0;
            $rowss = array();
            while($results = mysqli_fetch_assoc($query)) {
              $rowss[] = $results['business'];
            }
            shuffle($rowss);
            foreach($rowss as $data) {
              $result = $data;
              $query2 = mysqli_query($link, "SELECT `image_path`, `business` FROM `images` WHERE `business`=$result");
              $results2 = mysqli_fetch_assoc($query2);
              $query3 = mysqli_query($link, "SELECT * FROM `businesses` WHERE `id`=$result");
              $results3 = mysqli_fetch_assoc($query3);
              $categories = explode(",", $results3['category']);
              $query4 = mysqli_query($link, "SELECT AVG(`ratings`) as average, COUNT(`ratings`) as number FROM (SELECT `ratings`, `business` FROM `reviews` WHERE `business`=$result) as the_averag");
              $results4 = mysqli_fetch_assoc($query4);
          ?>
          <div class="col-md-4">
            <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
              <div class="card">
                <img src="<?php echo $results2['image_path']?>" class="card-img-top" alt="<?php if($result2['image_path']=="") {echo "";} else {echo $result2['image_path'];} ?>">
                <div class="card-body">
                  <h4 class="my-0 text-wrap" style="color: #00C853;"><?php echo strtoupper($results3['name'])?></h4>
                  <p class="text-muted" style="font-size: 14px;"><?php if(!empty($results4['average'])) { $rating = $results4['average']; for($i=0; $i<round($rating); $i++) {echo "<i class=\"bi bi-star-fill\"></i>" ?>
                  <?php } echo " ".$results4['number'];}  else{ echo "No ";}?> review</p>
                    <p class="mb-1 mt-0 text-wrap text-warning" style="font-size: 14px;"><?php echo strtoupper($results3['category']); ?></p>
                    <a href="portfolio-details.php?id=<?php echo $result; ?>" class="btn btn-sm btn-secondary stretched-link">View Business</a>
                </div>
              </div>
            </div>
          </div>

          <?php $count++; if($count==12) {break;} }?>

        </div>
      </div>
      <div class="col py-4">
						<h6 class="mb-0 text-uppercase">With captions</h6>
						<hr/>
						<div class="card">
							<div class="card-body">
								<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
									<ol class="carousel-indicators">
										<li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"></li>
										<li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"></li>
										<li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"></li>
									</ol>
									<div class="carousel-inner">
										<div class="carousel-item active " style='height:20rem'>
											  <!--<a href="index.php" class="logo me-auto"><img src="assets/img/logo.png" class="d-block w-100" alt=""></a>-->
										  <div class="d-flex flex-column justify-content-center align-items-center h-100 mx-auto">

												<h5 class='font-weight-bolder text-center text-success mb-4' style='font-size: 3.13rem;'>Business Name</h5>
												<a href='#'>View Details<a/>
											</div>


										</div>
										<div class="carousel-item" style='height:20rem' >
										    <div class="carousel-caption h-100" >
                                            <div class="d-flex flex-column justify-content-center align-items-center h-100 mx-auto">

												<h5 class='font-weight-bolder text-center text-success mb-4' style='font-size: 3.13rem;'>Business Name</h5>
												<a href='#'>View Details<a/>
											</div>
											</div>
											    <!--<a href="index.php" class="logo d-block mx-auto"><img src="assets/img/logo.png" class="d-block w-50" alt=""></a>-->

										</div>
										
									</div>
									<a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-bs-slide="prev">	
									<span class="carousel-control-prev-icon" aria-hidden="true"></span>
										<!--<span class="visually-hidden">Previous</span>-->
									</a>
									<a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-bs-slide="next">	
									<span class="carousel-control-next-icon" aria-hidden="true"></span>
										<!--<span class="visually-hidden">Next</span>-->
									</a>
								</div>
							</div>
						</div>
					</div>
    </section><!-- End Services Section -->

    <?php
      $call1 = mysqli_query($link, "SELECT COUNT(`id`) as total FROM `businesses`");
      $answer = mysqli_fetch_assoc($call1);
     ?>

    <!-- ======= Counts Section ======= -->
    <section id="counts" class="counts">
      <div class="container" data-aos="fade-up">

        <div class="row">

          <div class="col-lg-4 col-md-6">
            <div class="count-box">
              <i class="bi bi-people" style='font-size:50px'></i>
              <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
              <p>Visitors Today</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mt-5 mt-md-0">
            <div class="count-box">
            <i class="bi bi-emoji-smile"  style='font-size:50px'></i>
              <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
              <p>Live Users</p>
            </div>
          </div>

          <!--<div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
              <div class="count-box">
                <i class="bi bi-journal-richtext"></i>
              <span data-purecounter-start="0" data-purecounter-end="<?php //echo $answer['total'];?>" data-purecounter-duration="1" class="purecounter"></span>
              <p>Listed Businesses</p>
            </div>
          </div>-->

          <div class="col-lg-4 col-md-6 mt-5 mt-lg-0">
            <div class="count-box">
              <i class="bi bi-headset"  style='font-size:50px'></i>
              <span data-purecounter-start="0" data-purecounter-end="24" data-purecounter-duration="1" class="purecounter"></span>
              <p>Hours of Support</p>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Counts Section -->

  </main><!-- End #main -->


  <script>
  function getState(e) {
      var str='';
      for (i=0;i< e.length;i++) {
          if(e[i].selected){
              str = e[i].value;
              console.log(str);
              window.location = 'explorer.php?category='+str;
          }
      }

    }
    </script>
  <?php require_once('includes/footer.php'); ?>
