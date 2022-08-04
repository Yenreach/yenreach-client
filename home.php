 
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
  <link
  rel="icon"
  type="image/png"
  sizes="16x16"
  href="./images/favicon.png"
/>
<link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"
  rel="stylesheet"
/>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
/>

<link rel="stylesheet" href="/assets/css/style.css" />
</head>

<body>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top d-flex align-items-center">
  <div class="container d-flex align-items-center">
    <!-- Uncomment below if you prefer to use an text logo -->
    <!-- <h1 class="logo me-auto"><a href="index.php">Yenreach<span>.</span>com</a></h1>-->
    <a href="index.php" class="logo me-auto"><img src="./assets/img/logo.png" alt=""></a>

    <nav id="navbar" class="navbar order-last order-lg-0">
      <ul>
        <li><a class="active" href="index.php">Home</a></li>
        <li><a class="" href="explorer.php">Explore</a></li>
        <li><a class="" href="about.php">About</a></li>
        <li><a class="" href="blog.php">Blog</a></li>
        <li><a class="" href="contact.php">Contact</a></li>
        <li><a class="" href="users/dashboard">Log In</a></li>
      </ul>
      <i class="bi bi-list mobile-nav-toggle text-dark"></i>
      <i class="bi bi-x mobile-nav-toggle close "></i>
    </nav><!-- .navbar -->

    <a href="users/signup" class="get-started-btn">Add My Business</a>
  </div>
</header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero">
    <div class=" hero-container"  data-aos="zoom-out" data-aos-delay="100">
      <div class="col-10 hero-text-container col-md-12 h-100 col-lg-10 mx-auto flex d-flex flex-column align-items-center justify-content-center">
        <h1 class='fw-bold' >Welcome to <span style="color:#00C853">Yenreach.com</span></h1>
        <p id="header-caption">The Fastest Growing Business Directory Platform in Nigeria</p>
        <h2>What are you searching for today?</h2>
        <form action="includes/search.php" method="POST" enctype="multipart/form-data" class='col-10 col-md-10 col-lg-6 mx-auto mx-md-0'>
          <div class="input-group">
            <input class="form-control search-text py-2" list="datalistOptions" id="exampleDataList" placeholder="e.g carpenter, restaurant, stylist, doctor, etc" name="category" required>
            <datalist id="datalistOptions">
             
              <option></option>
            </datalist>
            <select class="form-select py-2" id="inputGroupSelect04" aria-label="Example select with button addon" name="location" required>
              <option selected>Location...</option>
                <option></option>
            </select>
            <button class='btn py-2 px-2 col-4 col-md-3 text-white d-none d-md-block' type="submit" name="search" style='background:#00C853'>Search</button>
          </div>
            <button class='btn py-2 px-2 col-12 col-md-3 mt-3 text-white d-block d-md-none' type="submit" name="search" style='background:#00C853'>Search</button>
          <div>
          </div>
      </form>     
      </div>
      <!-- <img src="https://res.cloudinary.com/dxfq3iotg/image/upload/v1557204663/park-4174278_640.jpg" alt="hero-section-image" > -->
      <div class="row row-top" id="first-slider"> 
    </div>
    <div class="row row-top" id="second-slider">
  </div>
    </div>

    <!-- HERO CONTAINER SECOND SLLIDER -->
      <!-- <img src="https://res.cloudinary.com/dxfq3iotg/image/upload/v1557204663/park-4174278_640.jpg" alt="hero-section-image" > -->
      

  </section><!-- End Hero -->

  <main id="main">
    <!-- ======= Tabs Section ======= -->
    <section id="tabs" class="tabs">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Browse By our Recommended Category</h2>
        </div>
        <ul class="nav nav-tabs filter-nav row border-bottom-0 d-flex justify-content-center">
      
          <li class="nav-item col-2"   data-bs-toggle="tooltip" title="Hotels" >
            <div class=" nav-link active border-0 show py-md-3 pb-lg-2 d-flex justify-content-evenly" style='border-radius:5px'  data-bs-toggle="tab" data-bs-target="#tab-1">
              <svg width="20" class="" height="20" viewBox="0 0 22 15" fill="none" xmlns="http://www.w3.org/2000/svg" >
                <path fill-rule="evenodd" clip-rule="evenodd" d="M6 8C7.66 8 9 6.66 9 5C9 3.34 7.66 2 6 2C4.34 2 3 3.34 3 5C3 6.66 4.34 8 6 8ZM18 2H12C10.9 2 10 2.9 10 4V9H2V1C2 0.45 1.55 0 1 0C0.45 0 0 0.45 0 1V14C0 14.55 0.45 15 1 15C1.55 15 2 14.55 2 14V12H20V14C20 14.55 20.45 15 21 15C21.55 15 22 14.55 22 14V6C22 3.79 20.21 2 18 2Z" fill="currentColor"/>
                </svg>
              <h4 class="d-none d-lg-block " >Hotels</h4>
            </div>
          </li>
          <li class="nav-item col-3"   data-bs-toggle="tooltip" title="Restaurants & Bar" >
            <div class="nav-link border-0 d-flex py-md-3 pb-lg-2 justify-content-evenly" data-bs-toggle="tab" data-bs-target="#tab-2" style='border-radius:5px'>
              <!-- <i class="fa-solid fa-utensils fa-sm" style='font-size:16px;'></i> -->
              <svg width="20" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M13 4V10C13 11.1 13.9 12 15 12H16V19C16 19.55 16.45 20 17 20C17.55 20 18 19.55 18 19V1.13C18 0.48 17.39 -5.58794e-09 16.76 0.15C14.6 0.68 13 2.51 13 4ZM8 7H6V1C6 0.45 5.55 0 5 0C4.45 0 4 0.45 4 1V7H2V1C2 0.45 1.55 0 1 0C0.45 0 0 0.45 0 1V7C0 9.21 1.79 11 4 11V19C4 19.55 4.45 20 5 20C5.55 20 6 19.55 6 19V11C8.21 11 10 9.21 10 7V1C10 0.45 9.55 0 9 0C8.45 0 8 0.45 8 1V7Z" fill="currentColor"/>
                </svg>
              <h4 class="d-none d-lg-block">Restaurants & Bars</h4>
            </div>
          </li>
          <li class="nav-item col-2 "  data-bs-toggle="tooltip" title="Schools">
            <div class="nav-link border-0 py-md-3 pb-lg-2 d-flex justify-content-evenly" data-bs-toggle="tab" data-bs-target="#tab-3" style='border-radius:5px'>
              <!-- <i class="fa-solid fa-school fa-sm" style='font-size:16px;'></i> -->
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
              </svg>
                
              <h4 class="d-none d-lg-block">Schools</h4>
            </div>
          </li>
          <li class="nav-item col-3"  data-bs-toggle="tooltip" title="Fashion Designers">
            <div class="nav-link border-0 py-md-3 pb-lg-2 d-flex justify-content-evenly" data-bs-toggle="tab" data-bs-target="#tab-4" style='border-radius:5px'>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-scissors" viewBox="0 0 16 16">
  <path d="M3.5 3.5c-.614-.884-.074-1.962.858-2.5L8 7.226 11.642 1c.932.538 1.472 1.616.858 2.5L8.81 8.61l1.556 2.661a2.5 2.5 0 1 1-.794.637L8 9.73l-1.572 2.177a2.5 2.5 0 1 1-.794-.637L7.19 8.61 3.5 3.5zm2.5 10a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0zm7 0a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0z"/>
</svg>
              <h4 class="d-none d-lg-block">Fashion Designers</h4>
            </div>
          </li>
        </ul>
        <!-- <div class='w-100 row h-100 d-flex justify-content-center align-items-center bg-danger'>
          <select class=" col-lg-4 border-dark py-2 px-2 mt-4 mb-4" style='border-radius:5px;' id="inputGroupSelect04" aria-label="Example select with button addon" name="location" id="location" onChange="getState(this);" required>
            <option  selected>Search more businesses</option>
              <option class='w-50' value=""></option>
          </select>
          <select class=" col-lg-4 py-2" id="inputGroupSelect04" aria-label="Example select with button addon" name="location" required>
                <option selected>Location...</option>
                  <option></option>
              </select>
        </div> -->

        <div class="tab-content">
          <div class="tab-pane active show" id="tab-1">
            <div id="home-list" class="row">
                  <div class="col-md-4 mb-4">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                      <div class="card business-card-section d-flex flex-column justify-content-around align-items-start">
                          <div class='w-100 business-image-container'>
                    <img src="http://www.urbanui.com/fily/template/images/carousel/banner_2.jpg" class="card-img-top h-100 w-100"  alt="">
                          </div>
                        <div class="card-body w-100 d-flex flex-column justify-content-evenly align-items-start">
                          <h4 class="text-wrap text-uppercase">Yenreach Hotels</h4>
                          <p class="text-muted">ONLINE MARKETING, CONSULTING, CONSULTING, SCHOOLS,ONLINE MARKETING</p>
                          <div
                          class="col-12 pb-2 mb-2"
                        >
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill"></i>
                        </div>
                            <a href="portfolio-details.php?id=<?php echo $result; ?>" class="btn btn-success text-white px-4 py-2" style='font-size:14px'>View Business</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-4">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                      <div class="card business-card-section d-flex flex-column justify-content-around align-items-start">
                          <div class='w-100 business-image-container'>
                    <img src="http://www.urbanui.com/fily/template/images/carousel/banner_2.jpg" class="card-img-top h-100 w-100"  alt="">
                          </div>
                        <div class="card-body w-100 d-flex flex-column justify-content-evenly align-items-start">
                          <h4 class="text-wrap text-uppercase">Yenreach Hotels</h4>
                          <p class="text-muted">ONLINE MARKETING, CONSULTING, CONSULTING, SCHOOLS,ONLINE MARKETING</p>
                          <div
                          class="col-12 pb-2 mb-2"
                        >
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill"></i>
                        </div>
                            <a href="portfolio-details.php?id=<?php echo $result; ?>" class="btn btn-success text-white px-4 py-2" style='font-size:14px'>View Business</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-4">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                      <div class="card business-card-section d-flex flex-column justify-content-around align-items-start">
                          <div class='w-100 business-image-container'>
                    <img src="http://www.urbanui.com/fily/template/images/carousel/banner_2.jpg" class="card-img-top h-100 w-100"  alt="">
                          </div>
                        <div class="card-body w-100 d-flex flex-column justify-content-evenly align-items-start">
                          <h4 class="text-wrap text-uppercase">Yenreach Hotels</h4>
                          <p class="text-muted">ONLINE MARKETING, CONSULTING, CONSULTING, SCHOOLS,ONLINE MARKETING</p>
                          <div
                          class="col-12 pb-2 mb-2"
                        >
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill"></i>
                        </div>
                            <a href="portfolio-details.php?id=<?php echo $result; ?>" class="btn btn-success text-white px-4 py-2" style='font-size:14px'>View Business</a>
                        </div>
                      </div>
                    </div>
                  </div>

            </div>
            <button class="btn px-5 mx-auto d-flex justify-content-center mt-3"><a class='text-white py-2' href="explorer.php?category=hotel">More businesses</a></button>
          </div>
          <div class="tab-pane" id="tab-2">
            <div id="home-list" class="row">
              
                  <div class="col-md-4 mb-4">
                    <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                      <div class="card business-card-section d-flex flex-column justify-content-around align-items-start">
                          <div class='w-100 business-image-container'>
                    <img src="http://www.urbanui.com/fily/template/images/carousel/banner_2.jpg" class="card-img-top h-100 w-100"  alt="">
                          </div>
                        <div class="card-body w-100 d-flex flex-column justify-content-evenly align-items-start">
                          <h4 class="text-wrap text-uppercase">Mama Put</h4>
                          <p class="text-muted">ONLINE MARKETING, CONSULTING, CONSULTING, SCHOOLS,ONLINE MARKETING</p>
                          <div
                          class="col-12 pb-2 mb-2"
                        >
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill"></i>
                        </div>
                        
                            <a href="portfolio-details.php?id=<?php echo $result; ?>" class="btn btn-success text-white px-4 py-2" style='font-size:14px'>View Business</a>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
        <!-- <button class="btn bg-success mx-auto d-flex justify-content-center mt-3"><a class='text-white px-5' >More Business</a></button> -->
        <button class="btn px-5 mx-auto d-flex justify-content-center mt-3"><a class='text-white py-2' href="explorer.php?category=restaurant">More businesses</a></button>
          </div>


          <div class="tab-pane" id="tab-3">
            <div id="home-list" class="row">
              
              <div class="col-md-4 mb-4">
                <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                  <div class="card business-card-section d-flex flex-column justify-content-around align-items-start">
                      <div class='w-100 business-image-container'>
                <img src="http://www.urbanui.com/fily/template/images/carousel/banner_2.jpg" class="card-img-top h-100 w-100"  alt="">
                      </div>
                    <div class="card-body w-100 d-flex flex-column justify-content-evenly align-items-start">
                      <h4 class="text-wrap text-uppercase">YenReach College</h4>
                      <p class="text-muted">ONLINE MARKETING, CONSULTING, CONSULTING, SCHOOLS</p>
                      <div
                      class="col-12 pb-2 mb-2"
                    >
                      <i class="bi bi-star-fill text-warning"></i>
                      <i class="bi bi-star-fill text-warning"></i>
                      <i class="bi bi-star-fill text-warning"></i>
                      <i class="bi bi-star-fill text-warning"></i>
                      <i class="bi bi-star-fill"></i>
                    </div>
                    
                        <a href="portfolio-details.php?id=<?php echo $result; ?>" class="btn btn-success text-white px-4 py-2" style='font-size:14px'>View Business</a>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <button class="btn px-5 mx-auto d-flex justify-content-center mt-3"><a class='text-white py-2' href="explorer.php?category=school">More businesses</a></button>

          </div>
          <div class="tab-pane" id="tab-4">
            <div id="home-list" class="row">
              
              <div class="col-md-4 mb-4">
                <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                  <div class="card business-card-section d-flex flex-column justify-content-around align-items-start">
                      <div class='w-100 business-image-container'>
                <img src="http://www.urbanui.com/fily/template/images/carousel/banner_2.jpg" class="card-img-top h-100 w-100"  alt="">
                      </div>
                    <div class="card-body w-100 d-flex flex-column justify-content-evenly align-items-start">
                      <h4 class="text-wrap text-uppercase">Yenreach Fashion world</h4>
                      <p class="text-muted">ONLINE CLOTHING STORE, FASHION HOUSE</p>
                      <div
                      class="col-12 pb-2 mb-2"
                    >
                      <i class="bi bi-star-fill text-warning"></i>
                      <i class="bi bi-star-fill text-warning"></i>
                      <i class="bi bi-star-fill text-warning"></i>
                      <i class="bi bi-star-fill text-warning"></i>
                      <i class="bi bi-star-fill"></i>
                    </div>
                        <a href="portfolio-details.php?id=<?php echo $result; ?>" class="btn btn-success text-white px-4 py-2" style='font-size:14px'>View Business</a>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <button class="btn px-5 mx-auto d-flex justify-content-center mt-3"><a class='text-white py-2' href="explorer.php?category=fashion">More businesses</a></button>
                    <button class="btn mx-auto d-flex justify-content-center mt-3"><a class='text-white  px-5' href="explorer.php?category=fashion">View All</a></button>

            
          </div>

        </div>

      </div>
     
    </section><!-- End Tabs Section -->
    
    <?php
        if($categories->status == "success"){
    ?>
            <section id="tabs" class="tabs">
                <div class="container" data-aos="fade-up">
                    <div class="section-title">
                        <h2>Browse By our Recommended Category</h2>
                    </div>
                    <ul class="nav nav-tabs filter-nav row border-bottom-0 d-flex justify-content-center">
                        <?php
                            $all_categories = $categories->data;
                            $thefirst = array_shift($all_categories);
                        ?>
                        <li class="nav-item col-3"   data-bs-toggle="tooltip" title="<?php echo $thefirst->category ?>">
                            <div class=" nav-link active border-0 show py-md-3 pb-lg-2 d-flex justify-content-evenly" style='border-radius:5px'  data-bs-toggle="tab" data-bs-target="#tab-<?php echo $thefirst->category_id; ?>">
                                <h4 class="d-lg-block"><?php echo trim($thefirst->category); ?></h4>    
                            </div>
                        </li>
                        <?php
                            foreach($all_categories as $category){
                        ?>
                                <li class="nav-item col-3"   data-bs-toggle="tooltip" title="<?php echo $category->category ?>">
                                    <div class=" nav-link border-0 py-md-3 pb-lg-2 d-flex justify-content-evenly" style='border-radius:5px'  data-bs-toggle="tab" data-bs-target="#tab-<?php echo $category->category_id; ?>">
                                        <h4 class="d-lg-block"><?php echo trim($category->category); ?></h4>    
                                    </div>
                                </li>
                        <?php
                            }
                        ?>
                    </ul>
                    <div class="tab-content">
                        <?php
                            $first_category = array_shift($categories->data);
                        ?>
                        <div class="tab-pane active show" id="tab-<?php echo $first_category->category_id; ?>">
                            <div id="home-list" class="row">
                                <?php
                                    foreach($first_category->businesses as $business){
                                ?>
                                        <div class="col-md-4 mb-4">
                                            <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                                                <div class="card business-card-section d-flex flex-column justify-content-around align-items-start">
                                                    <div class='w-100 business-image-container'>
                                                        <img src="<?php
                                                            if(!empty($business->photos)){
                                                                $photo = array_shift($business->photos);
                                                                if(file_exists($photo->filepath)){
                                                                    echo $photo->filepath;
                                                                } else {
                                                                    if(!empty($business->filename)){
                                                                        if(file_exists("images/thumbnails/".$business->filename.".jpg")){
                                                                            echo "images/thumbnails/".$business->filename.".jpg";
                                                                        } else {
                                                                            if(file_exists("images/".$business->filename.".jpg")){
                                                                                echo "images/".$business->filename.".jpg";
                                                                            } else {
                                                                                echo "assets/img/office_building.png";
                                                                            }
                                                                        }
                                                                    } else {
                                                                        echo "assets/img/office_building.png";
                                                                    }
                                                                }
                                                            } else {
                                                                if(!empty($business->filename)){
                                                                    if(file_exists("images/thumbnails/".$business->filename.".jpg")){
                                                                        echo "images/thumbnails/".$business->filename.".jpg";
                                                                    } else {
                                                                        if(file_exists("images/".$business->filename.".jpg")){
                                                                            echo "images/".$business->filename.".jpg";
                                                                        } else {
                                                                            echo "assets/img/office_building.png";
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo "assets/img/office_building.png";
                                                                }
                                                            }
                                                        ?>" class="card-img-top h-100 w-100"  alt="<?php echo $business->name; ?>">
                                                    </div>
                                                    <div class="card-body w-100 d-flex flex-column justify-content-evenly align-items-start">
                                                        <h4 class="text-wrap text-uppercase"><?php echo $business->name; ?></h4>
                                                        <?php
                                                            if(!empty($business->categories)){
                                                        ?>
                                                                <p class="text-muted">
                                                                    <?php
                                                                        foreach($business->categories as $categ){
                                                                            echo '<span class="m-2"><a href="#" class="text-success">#'.strtoupper($categ->category).'</a></span>';
                                                                        }
                                                                    ?>
                                                                </p>    
                                                        <?php
                                                            }
                                                        ?>
                                                        <a href="business.php?id=<?php $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $name.".html"; ?>" class="btn btn-success text-white px-4 py-2" style='font-size:14px'>View Business</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                ?>
                            </div>
                            <button class="btn px-5 mx-auto d-flex justify-content-center mt-3"><a class='text-white py-2' href="explorer.php?category=school">More businesses</a></button>
                        </div>
                        <?php
                            foreach($categories->data as $category){
                        ?>
                                <div class="tab-pane" id="tab-<?php echo $category->category_id; ?>">
                                    <div id="home-list" class="row">
                                        <?php
                                            foreach($category->businesses as $business){
                                        ?>
                                                <div class="col-md-4 mb-4">
                                                    <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                                                        <div class="card business-card-section d-flex flex-column justify-content-around align-items-start">
                                                            <div class='w-100 business-image-container'>
                                                                <img src="<?php
                                                                    if(!empty($business->photos)){
                                                                        $photo = array_shift($business->photos);
                                                                        if(file_exists($photo->filepath)){
                                                                            echo $photo->filepath;
                                                                        } else {
                                                                            if(!empty($business->filename)){
                                                                                if(file_exists("images/thumbnails/".$business->filename.".jpg")){
                                                                                    echo "images/thumbnails/".$business->filename.".jpg";
                                                                                } else {
                                                                                    if(file_exists("images/".$business->filename.".jpg")){
                                                                                        echo "images/".$business->filename.".jpg";
                                                                                    } else {
                                                                                        echo "assets/img/office_building.png";
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                echo "assets/img/office_building.png";
                                                                            }
                                                                        }
                                                                    } else {
                                                                        if(!empty($business->filename)){
                                                                            if(file_exists("images/thumbnails/".$business->filename.".jpg")){
                                                                                echo "images/thumbnails/".$business->filename.".jpg";
                                                                            } else {
                                                                                if(file_exists("images/".$business->filename.".jpg")){
                                                                                    echo "images/".$business->filename.".jpg";
                                                                                } else {
                                                                                    echo "assets/img/office_building.png";
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo "assets/img/office_building.png";
                                                                        }
                                                                    }
                                                                ?>" class="card-img-top h-100 w-100"  alt="<?php echo $business->name; ?>">
                                                            </div>
                                                            <div class="card-body w-100 d-flex flex-column justify-content-evenly align-items-start">
                                                                <h4 class="text-wrap text-uppercase"><?php echo $business->name; ?></h4>
                                                                <?php
                                                                    if(!empty($business->categories)){
                                                                ?>
                                                                        <p class="text-muted">
                                                                            <?php
                                                                                foreach($business->categories as $categ){
                                                                                    echo '<span class="m-2"><a href="#" class="text-success">#'.strtoupper($categ->category).'</a></span>';
                                                                                }
                                                                            ?>
                                                                        </p>    
                                                                <?php
                                                                    }
                                                                ?>
                                                                <a href="business.php?id=<?php $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $name.".html"; ?>" class="btn btn-success text-white px-4 py-2" style='font-size:14px'>View Business</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                    <button class="btn px-5 mx-auto d-flex justify-content-center mt-3"><a class='text-white py-2' href="explorer.php?category=school">More businesses</a></button>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </section>
    <?php
        }
    ?>






<div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner rounded shadow px-4 py-1">
    <div class="carousel-item item active d-flex ">
      <div class="ads-image-container">
              <img src="./assets/img/ads-image.jpg" alt="advert banner" >
      </div>
      <div class="ads-text-container
      d-flex flex-column align-items-center justify-content-center">
       <p class="fs-4 text-center">Advertise your business here</p>
       <button class="btn px-4 py-2 mt-1">Learn more</button>
     </div>
    </div>
  </div>
</div>

 <div
        id="carouselExampleCaptions"
        class="carousel slide mt-4"
        data-bs-ride="carousel"
      >
      
        <div class="carousel-inner">
            <div class="carousel-item active" style='' >
                <div class='h-100  d-flex justify-content-center align-items-center'>
                    
                
            <div class="w-50 h-50">
              <img src="./assets/img/hero-two.jpg" alt="advert-banner" class='w-100' />
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center h-50 w-50">
               <h2 class=" text-capitalize fw-bold text-black">Omotolani Olurotimi.</h2>
              <p class="fs-6 w-75 text-center fw-light text-dark">
                We currently live in a global village, and this had been made
                possible through digital and web based technology, thus making
                it really easy for brands and businesses to reach their target
                audience. Omotolani Olurotimi is here to ensure that your
                business, aspirations, plans and projects are achieved alongside
                the the goal of getting them to the right people.
              </p>
              <p class="fs-4 text-center"></p>
              <button class="btn px-4 py-2">Learn more</button>
            </div>
            </div>
          </div>
          
          <div class="carousel-item">
                <div class='h-100  d-flex justify-content-center align-items-center'>
                    
                
            <div class="w-50 h-50">
              <img src="./assets/img/hero-two.jpg" alt="advert-banner" class='w-100' />
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center h-50 w-50">
               <h2 class=" text-capitalize fw-bold text-black">Omotolani Olurotimi.</h2>
              <p class="fs-6 w-75 text-center fw-light text-dark">
                We currently live in a global village, and this had been made
                possible through digital and web based technology, thus making
                it really easy for brands and businesses to reach their target
                audience. Omotolani Olurotimi is here to ensure that your
                business, aspirations, plans and projects are achieved alongside
                the the goal of getting them to the right people.
              </p>
              <p class="fs-4 text-center"></p>
              <button class="btn px-4 py-2">Learn more</button>
            </div>
            </div>
          </div>
         
        </div>
        <button
          class="carousel-control-prev"
          type="button"
          data-bs-target="#carouselExampleCaptions"
          data-bs-slide="prev"
        >
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button
          class="carousel-control-next"
          type="button"
          data-bs-target="#carouselExampleCaptions"
          data-bs-slide="next"
        >
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
      
<!--<div id="carouselExampleInterval" class="carousel slide mt-4" data-bs-ride="carousel">-->
<!--  <div class="carousel-inner rounded px-4 py-1 shadow">-->
<!--    <div class="carousel-item item active d-flex  ">-->
<!--      <div class="ads-image-container">-->
<!--        <img src="./assets/img/hero-two.jpg" alt="advert-banner" >-->
<!--      </div>-->
<!--      <div class="ads-text-container mt-4 mt-md-0-->
<!--      d-flex flex-column align-items-center justify-content-center">-->
<!--           <h2 class="text-capitalize fw-bold">Omotolani Olurotimi.</h2>-->
       <!--<p class="fs-6 w-75 text-center fw-light text-dark"></p>-->
       <!--<p class="fs-4 text-center"></p>-->
<!--       <button class="btn px-4 py-2 mt-3">Learn more</button>-->
<!--     </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->
    <!-- ======= Counts Section ======= -->
    <section id="counts" class="counts">
      <div class="container" data-aos="fade-up">

        <div class="row">

          <!-- <div class="col-lg-4 col-md-6">
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

          <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
              <div class="count-box">
                <i class="bi bi-journal-richtext"></i>
              <span data-purecounter-start="0" data-purecounter-end="<?php //echo $answer['total'];?>" data-purecounter-duration="1" class="purecounter"></span>
              <p>Listed Businesses</p>
            </div>
          </div>-->

          <!-- <div class="col-lg-4 col-md-6 mt-5 mt-lg-0">
            <div class="count-box">
              <i class="bi bi-headset"  style='font-size:50px'></i>
              <span data-purecounter-start="0" data-purecounter-end="24" data-purecounter-duration="1" class="purecounter"></span>
              <p>Hours of Support</p>
            </div>
          </div>

        </div>  -->

      </div>
    </section><!-- End Counts Section -->


    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg ">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2 class="text-capitalize">Business Of the week</h2>
          <p>This business was the most visited business on Yenreach in the past week</p>
        </div>

        <div id="home-list" class="row">
          <div class="business-week shadow carousel-item rounded d-flex bg-white" >
            <div class="ads-image-container">
              <img src="./assets/img/ads-image.jpg" alt="advert banner" >
            </div>
            <div class="ads-text-container
            d-flex flex-column align-items-center justify-content-center ">
              <h4 class="text-center">Omotolani Olurotimi</h4>
             <p class="text-center w-75">We currently live in a global village, and this had been made possible through digital and web based technology, thus making it really easy for brands and businesses to reach their target audience. Omotolani Olurotimi is here to ensure that your business, aspirations, plans and projects are achieved alongside the the goal of getting them to the right people.
             </p> 
             
             <button class="btn px-4 py-2">View business</button>
           </div>
          </div>
        </div>
      </div> 
    
    </section><!-- End Services Section -->
    <!-- Vertically centered scrollable modal -->
<!-- Button trigger modal -->


<!-- Modal -->
<div class= "overlay">

<div style="height:20rem;background-color: #083640" class=" col-8 cookie-container col-lg-4 rounded mb-4 py-2">

  <div class=" col-11 mx-auto h-100 d-flex flex-column align-items-start justify-content-evenly"> 
    <div>
      <svg aria-hidden="true" class="mln4 mb24 sm:d-none svg-spot spotCookieLg " style="color:#fff" width="96" height="96" viewBox="0 0 96 96">
        <path d="M35 45.5a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0zM63.5 63a7.5 7.5 0 100-15 7.5 7.5 0 000 15zm-19 19a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" opacity=".2"/>
        <path d="M56.99 2.53a23.1 23.1 0 0114.66 6.15h.01l.01.02c.57.55.61 1.27.5 1.74v.07a10.95 10.95 0 01-3.07 4.77 9 9 0 01-6.9 2.5 10.34 10.34 0 01-9.72-10.44v-.08a10 10 0 011.03-3.74l.01-.03.02-.02c.28-.5.82-.92 1.52-.95.63-.02 1.27-.02 1.93.01zm12.04 7.83a20.1 20.1 0 00-12.2-4.83l-.92-.03c-.23.6-.38 1.25-.43 1.94a7.34 7.34 0 006.95 7.34 6 6 0 004.64-1.7c.94-.88 1.6-1.9 1.96-2.72zm15.3 8.76a6.84 6.84 0 00-5.09-.24 7.9 7.9 0 00-3.28 2.05 1.8 1.8 0 00-.3 1.95l.02.02v.02a15.16 15.16 0 008.74 7.47c.64.23 1.32.08 1.8-.33a6.63 6.63 0 001.63-1.97l.01-.03.01-.03c1.67-3.5-.12-7.32-3.54-8.91zm-5.5 3.28c.36-.25.82-.5 1.35-.67.92-.3 1.92-.35 2.89.1 2.14 1 2.92 3.14 2.11 4.88-.12.21-.26.41-.43.6l-.26-.1a12.29 12.29 0 01-5.66-4.81zM32 24a2 2 0 11-4 0 2 2 0 014 0zm12 21a2 2 0 11-4 0 2 2 0 014 0zm36 4a2 2 0 11-4 0 2 2 0 014 0zm-7 21a2 2 0 11-4 0 2 2 0 014 0zM59 81a2 2 0 11-4 0 2 2 0 014 0zM22 63a2 2 0 11-4 0 2 2 0 014 0zm27 7a9 9 0 11-18 0 9 9 0 0118 0zm-3 0a6 6 0 10-12 0 6 6 0 0012 0zM33 41a9 9 0 11-18 0 9 9 0 0118 0zm-15 0a6 6 0 1012 0 6 6 0 00-12 0zm50 11a9 9 0 11-18 0 9 9 0 0118 0zm-3 0a6 6 0 10-12 0 6 6 0 0012 0zM44.08 4.24c.31.48.33 1.09.05 1.58a17.46 17.46 0 00-2.36 8.8c0 9.55 7.58 17.24 16.85 17.24 2.97 0 5.75-.78 8.16-2.15a1.5 1.5 0 012.1.66 12.08 12.08 0 0011 6.74 12.4 12.4 0 007.85-2.75 1.5 1.5 0 012.38.74A45.76 45.76 0 0192 48.16c0 24.77-19.67 44.9-44 44.9S4 72.93 4 48.16C4 25.23 20.84 6.28 42.64 3.58a1.5 1.5 0 011.44.66zM40.22 7C21.32 10.71 7 27.7 7 48.16c0 23.17 18.39 41.9 41 41.9s41-18.73 41-41.9c0-3.52-.42-6.93-1.22-10.2a15.5 15.5 0 01-7.9 2.15c-5.5 0-10.36-2.83-12.97-7.1a19.46 19.46 0 01-8.28 1.85c-11 0-19.86-9.1-19.86-20.24 0-2.7.52-5.26 1.45-7.62zM92 91a2 2 0 100-4 2 2 0 000 4zM7 8.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM82.5 90a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm9.5-7.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM13.5 8a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM80 14.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM53.5 20a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
    </svg>
    </div>
    <div class="px-1 text-white">
      <h5>
        Your privacy
      </h5>
      <p>
        By clicking “Accept all cookies”, you agree Yenreach can store cookies on your device and disclose information in accordance with our 
        <a href="">Cookie Policy.</a>
         
      </p>
  </div>

  <div class="col-12">
    
  </div>
  
  <div class="col-12">
    <button class="btn col-12 py-2 mx-auto text-white accept-button" style="background: #00C853;" id="login_submit" name="submit" type="submit">Accept all cookies</button>
  </div>
  
</div>
</div>
</div>

  </main><!-- End #main -->

<footer id="footer">

<div class="footer-top">
  <div class="container">
    <div class="row">

      <div class="col-lg-3 col-md-6 footer-links">
        <h4>Useful Links</h4>
        <ul>
          <li><i class="bx bx-chevron-right"></i> <a href="index.php">Home</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="explorer.php">Explore</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="about.php">About Us</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="contact.php">Contact Us</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="add-business.php">Add My Business</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="faqs.php">FAQs</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6 footer-links">
        <h4>Our Brands</h4>
        <ul>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#dcl">Dordorian Concept LTD</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#bgs">BYS Graduate School</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#bmc">BusiTech Model College</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#tec">TEC Industrial Park</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#btu">BusiTech University</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#bbm">BYS Business Magazine</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries.php#de">Dordorian Estate</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="professional_courses.php">Professional Certification Programs</a></li>
        </ul>
      </div>
      <div class="col-lg-6 col-md-6 footer-newsletter">
        <h4>Sign Up For Updates Now!</h4>
        <p>Never miss out on the hottest offers and deals from your favourite local businesses.</p>
        <form action="index.php#footer" role="form" method="post">
          <input type="email" name="email" placeholder="name@example.com" required><input type="submit" name="newsletter" value="Submit">
        </form>
      </div>

    </div>
  </div>
</div>

<div class="container-fluid d-md-flex py-4" style="background-color: #083640" >

  <div class="me-md-auto text-center text-md-start">
    <div class="copyright" style="color: #083640">
      &copy; Copyright <strong><span>yenreach.com</span></strong>. All Rights Reserved
    </div>
  </div>
  <div class="social-links text-center text-md-end pt-3 pt-md-0">
    <a href="https://www.facebook.com/yenreachng/" class="facebook"><i class="bi bi-facebook"></i></a>
    <a href="https://instagram.com/yenreach?utm_medium=copy_link" class="insta"><i class="bi bi-instagram"></i></a>
    <a href="https://www.linkedin.com/company/yenreach" class="linkedin"><i class="bi bi-linkedin"></i></a>
    <a href="https://api.whatsapp.com/send?phone=+2349024401562" class="whatsapp"><i class="bi bi-whatsapp"></i></i></a>
  </div>
</div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/index.js"></script>
</body>

</html>




 
 


  

