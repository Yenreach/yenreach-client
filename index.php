<?php
    require_once("../includes_public/initialize.php");
    
    $gurl = "fetch_home_businesses_api.php";
    $categories = perform_get_curl($gurl);
    if($categories){
        
    } else {
        die("Home Businesses Link Broken");
    }
    
    $wgurl = "fetch_business_of_the_week_api.php";
    $weeks = perform_get_curl($wgurl);
    if($weeks){
        
    } else {
        die("Business of the Week Link Broken");
    }
    
    
    $cgurl = "fetch_filled_categories_api.php";
    $bus_categories = perform_get_curl($cgurl);
    if($bus_categories){
        
    } else {
        die("Categories Link Broken");
    }
    
    $sgurl = "fetch_business_states_api.php";
    $states = perform_get_curl($sgurl);
    if($states){
        
    } else {
        die("States Link Broken");
    }
    
    $bgurl = "fetch_active_billboards_api.php";
    $billboards = perform_get_curl($bgurl);
    if($billboards){
        
    } else {
        die("Billboard Link Broken");
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
  
 <!-- Meta Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1136570877108121');
  fbq('track', 'PageView');
  fbq('track', 'Yenreach Homepage');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1136570877108121&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->

  <?php include_layout_template("links.php"); ?>
</head>

<body>

<!-- ======= Header ======= -->
<?php include_layout_template("header.php"); ?>

<!--</header><!-- End Header -->
  <!-- ======= Hero Section ======= -->
  <section id="hero">
    <div class=" hero-container"  data-aos="zoom-out" data-aos-delay="100">
      <div class="col-10 hero-text-container col-md-12 h-100 col-lg-10 mx-auto flex d-flex flex-column align-items-center justify-content-center">
        <h1 class='fw-bold' >Welcome to <span style="color:#00C853">Yenreach.com</span></h1>
        <p id="header-caption">The Fastest Growing Business Directory Platform in Nigeria</p>
        <h2>What are you searching for today?</h2>
        <form action="search" method="POST" enctype="multipart/form-data" class='col-10 col-lg-6 mx-auto mx-md-0'>
              <div class="input-group">
            <input class="form-control search-text w-25 py-2" list="datalistOptions" id="exampleDataList" placeholder="e.g carpenter, restaurant, stylist, doctor, etc" name="category" required>
            <datalist id="datalistOptions">
               <?php
                        foreach($bus_categories->data as $categ){
                            echo '<option value="'.$categ->category.'">'.$categ->category.'</option>';
                        }
                    ?>
            </datalist>
            <select class="form-select col-4 py-2" id="inputGroupSelect04" aria-label="Example select with button addon" name="location">
                                    <option value="">--Any Location--</option>

              <?php
                        if($states->status == "success"){
                            foreach($states->data as $state){
                                echo '<option value="'.$state->name.'">'.$state->name.'</option>';
                            }
                        }
                    ?>
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
    <div class="row row-top" id="third-slider">
  </div>
  <!--  <div class="row row-top" id="third-slider">-->
  <!--</div>-->
    </div>


    <!-- HERO CONTAINER SECOND SLLIDER -->
      <!-- <img src="https://res.cloudinary.com/dxfq3iotg/image/upload/v1557204663/park-4174278_640.jpg" alt="hero-section-image" > -->
      

  </section><!-- End Hero -->

<main id="main">
      
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
                            $the_categories = $categories->data;
                            $top_categories = array_shift($the_categories);
                        ?>
                        <li class="nav-item col-3"   data-bs-toggle="tooltip" title="<?php echo $top_categories->category; ?>">
                            <div class=" nav-link active border-0 show py-md-3 pb-lg-3 d-flex justify-content-evenly" style='border-radius:5px'  data-bs-toggle="tab" data-bs-target="#tab-<?php echo $top_categories->category_id; ?>">
                                <h4 class="d-block fw-light"><?php echo $top_categories->category; ?></h4>
                            </div>
                        </li>
                        <?php
                            foreach($the_categories as $category){
                        ?>
                                <li class="nav-item col-3"   data-bs-toggle="tooltip" title="<?php echo $category->category ?>" >
                                    <div class="nav-link border-0 d-flex py-md-3 pb-lg-3 justify-content-evenly" data-bs-toggle="tab" data-bs-target="#tab-<?php echo $category->category_id; ?>" style='border-radius:5px'>
                                      <!-- <i class="fa-solid fa-utensils fa-sm" style='font-size:16px;'></i> -->
                                      <h4 class="d-block fw-light"><?php echo $category->category ?></h4>
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
                                                                    ?>
                                                                            <span class="m-2"><a href="category?category=<?php echo urlencode($categ->category); ?>" class="text-success">#<?php echo $categ->category; ?></a></span>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </p>
                                                        <?php
                                                            }
                                                        ?>
                                                        <!--<div class="col-12 pb-2 mb-2">
                                                            <i class="bi bi-star-fill text-warning"></i>
                                                            <i class="bi bi-star-fill text-warning"></i>
                                                            <i class="bi bi-star-fill text-warning"></i>
                                                            <i class="bi bi-star-fill text-warning"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                        </div>-->
                                                        <a href="business?<?php echo $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $business->name.".html"; ?>" class="btn btn-success text-white px-4 py-2" style='font-size:14px'>View Business</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                ?>
                            </div>
                            <button class="btn px-5 mx-auto d-flex justify-content-center mt-3"><a class='text-white py-2' href="explorer">More businesses</a></button>
                        </div>
                        <?php
                            foreach($categories->data as $category){
                        ?>
                                <div class="tab-pane" id="tab-<?php echo $category->category_id ?>">
                                    <div id="home-list" class="row">
                                        <?php
                                            foreach($category->businesses as $business){
                                                
                                            }
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
                                                                    ?>
                                                                            <span class="m-2"><a href="category?category=<?php echo urlencode($categ->category); ?>" class="text-success">#<?php echo $categ->category; ?></a></span>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </p>
                                                        <?php
                                                            }
                                                        ?>
                                                
                                                        <a href="business?<?php echo $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $business->name.".html"; ?>" class="btn btn-success text-white px-4 py-2" style='font-size:14px'>View Business</a>
                                                    </div>
                                                </div>
                                            </div>
                                          </div>
                                    </div>
                                    <!-- <button class="btn bg-success mx-auto d-flex justify-content-center mt-3"><a class='text-white px-5' >More Business</a></button> -->
                                    <button class="btn px-5 mx-auto d-flex justify-content-center mt-3"><a class='text-white py-2' href="explorer">More businesses</a></button>
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
    

<!--<div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">-->
<!--  <div class="carousel-inner shadow">-->
<!--    <div class="carousel-item item active d-flex ">-->
<!--      <div class="ads-image-container">-->
<!--              <img src="./assets/img/ads-image.jpg" alt="advert banner" >-->
<!--      </div>-->
<!--      <div class="ads-text-container-->
<!--      d-flex flex-column align-items-center justify-content-center">-->
<!--       <p class="fs-4 text-center">Advertise your business here</p>-->
<!--       <a href="yenreach_billboard" class="btn px-4 py-2">Learn more</a>-->
<!--     </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->
<!--<div id="carouselExampleInterval" class="carousel slide mt-4" data-bs-ride="carousel">-->
<!--  <div class="carousel-inner shadow">-->
<!--    <div class="carousel-item item active d-flex  ">-->
<!--      <div class="ads-image-container">-->
<!--        <img src="./assets/img/hero-two.jpg" alt="advert-banner" >-->
<!--      </div>-->
<!--      <div class="ads-text-container-->
<!--      d-flex flex-column align-items-center justify-content-center">-->
<!--           <h2 class="text-capitalize fw-bold">Omotolani Olurotimi.</h2>-->
<!--       <p class="fs-6 w-75 text-center fw-light text-dark">-->
<!--        We currently live in a global village, and this had been made possible through digital and web based technology, thus making it really easy for brands and businesses to reach their target audience. Omotolani Olurotimi is here to ensure that your business, aspirations, plans and projects are achieved alongside the the goal of getting them to the right people.-->
<!--       </p>-->
<!--       <p class="fs-4 text-center"></p>-->
<!--       <button class="btn px-4 py-2">Learn more</button>-->
<!--     </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->

<div
        id="carouselExampleCaptions"
        class="carousel slide mt-4"
        data-bs-ride="carousel"
      >
      
        <div class="carousel-inner">
          <!--  <div class="carousel-item active" style='' >-->
          <!--      <div class='h-100  d-flex justify-content-center align-items-center row'>-->
                    
                
          <!--  <div class="h-50 col-lg-6 col-md-6 col-sm-12 my-3">-->
          <!--    <img src="./assets/img/hero-two.jpg" alt="advert-banner" class='w-100' />-->
          <!--  </div>-->
          <!--  <div class="d-flex flex-column justify-content-center align-items-center h-50 col-lg-6 col-md-6 col-sm-12">-->
          <!--     <h2 class=" text-capitalize fw-bold text-black">Omotolani Olurotimi.</h2>-->
          <!--    <p class="fs-6 w-75 text-center fw-light text-dark">-->
          <!--      We currently live in a global village, and this had been made-->
          <!--      possible through digital and web based technology, thus making-->
          <!--      it really easy for brands and businesses to reach their target-->
          <!--      audience. Omotolani Olurotimi is here to ensure that your-->
          <!--      business, aspirations, plans and projects are achieved alongside-->
          <!--      the the goal of getting them to the right people.-->
          <!--    </p>-->
          <!--    <p class="fs-4 text-center"></p>-->
          <!--    <button class="btn px-4 py-2">Learn more</button>-->
          <!--  </div>-->
          <!--  </div>-->
          <!--</div>-->
          <div class="carousel-item active">
                <div class='h-100  d-flex justify-content-center align-items-center row'>
                    
                
            <div class="h-50 col-lg-6 col-md-6 col-sm-12 my-3">
              <img src="./assets/img/DEC_Image.jpg" alt="advert-banner" class='w-100' />
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center h-50 col-lg-6 col-md-6 col-sm-12">
               <h2 class=" text-capitalize fw-bold text-black">Dordorian Estate Limited</h2>
              <p class="fs-6 w-75 text-center fw-light text-dark">
                Own your dream Property in Fast developing locations across Yenagoa, Bayelsa State.
              </p>
              <p class="fs-4 text-center"></p>
              <a href="https://dordorianestate.com" target="_blank" class="btn px-4 py-2">Learn more</a>
            </div>
            </div>
          </div>
        <?php
            if($billboards->status == "success"){
                foreach($billboards->data as $billboard){
        ?>
                    <div class="carousel-item">
                        <div class="h-100 d-flex justify-content-center align-items-center row">
                            <div class="h-50 col-lg-6 col-md-6 col-sm-12 my-3">
                                <img src="images/<?php echo $billboard->filename.".jpg"; ?>" alt="<?php echo $billboard->title; ?>" class="w-100" />
                            </div>
                            <div class="d-flex flex-column justify-content-center align-items-center h-50 col-lg-6 col-md-6 col-sm-12">
                                <h2 class="text-capitalize fw-bold text-black"><?php echo $billboard->title; ?></h2>
                                <p class="fs-6 w-75 text-center fw-light text-dark"><?php echo nl2br($billboard->text); ?></p>
                                <p class="fs-4 text-center">
                                    <a href="<?php echo call_to_action_link($billboard->call_to_action_link, $billboard->call_to_action_type) ?>" target="_blank" class="btn px-4 py-2"><?php echo $billboard->call_to_action_type; ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
        <?php
                }
            }  
        ?>
          
          
          <div class="carousel-item">
                <div class='h-100  d-flex justify-content-center align-items-center row'>
                    
                
            <div class="h-50 col-lg-6 col-md-6 col-sm-12 my-3">
              <img src="./assets/img/ads-image.jpg" alt="advert-banner" class='w-100' />
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center h-50 col-lg-6 col-md-6 col-sm-12">
               <!--<h2 class=" text-capitalize fw-bold text-black">Omotolani Olurotimi.</h2>-->
              <p class="fs-6 w-75 text-center fw-light text-dark">
                Advertise your business here
              </p>
              <p class="fs-4 text-center"></p>
              <a href="yenreach_billboard" class="btn px-4 py-2">Learn more</a>
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


    <?php
        if($weeks->status == "success"){
            $busy = $weeks->data;
    ?>
            <!-- ======= Services Section ======= -->
            <section id="services" class="services section-bg ">
              <div class="container" data-aos="fade-up">
        
                <div class="section-title">
                  <h2 class="text-capitalize">Business Of the week</h2>
                  <p>This business was the most visited business on Yenreach in the past week</p>
                </div>
        
                <div id="home-list" class="row">
                  <div class="business-week shadow carousel-item d-flex bg-white my-4" >
                    <div class="ads-image-container">
                      <img src="<?php
                        if(!empty($busy->filename)){
                            if(file_exists("images/thumbnails/".$busy->filename.".jpg")){
                                echo "images/thumbnails/".$busy->filename.".jpg";
                            } else {
                                if(file_exists("images/".$busy->filename.".jpg")){
                                    echo "images/".$busy->filename.".jpg";
                                } else {
                                    if(!empty($busy->photos)){
                                        $photod = array_shift($busy->photos);
                                        if(!empty($photod->filename)){
                                            if(file_exists("images/thumbnails/{$photod->filename}.jpg")){
                                                echo "images/thumbnails/{$photod->filename}.jpg";
                                            } elseif(file_exists($photod->filepath)){
                                                echo $photod->filepath;
                                            } else {
                                                echo "assets/img/office_building.png";
                                            }
                                        } else {
                                            if(file_exists($photod->filepath)){
                                                echo $photod->filepath;
                                            } else {
                                                echo "assets/img/office_building.png";   
                                            }
                                        }
                                    } else {
                                        echo "assets/img/office_building.png";
                                    }
                                }
                            }
                        } else {
                            if(!empty($busy->photos)){
                                $photod = array_shift($busy->photos);
                                if(!empty($photod->filename)){
                                    if(file_exists("images/thumbnails/{$photod->filename}.jpg")){
                                        echo "images/thumbnails/{$photod->filename}.jpg";
                                    } elseif(file_exists($photod->filepath)){
                                        echo $photod->filepath;
                                    } else {
                                        echo "assets/img/office_building.png";
                                    }
                                } else {
                                    if(file_exists($photod->filepath)){
                                        echo $photod->filepath;
                                    } else {
                                        "assets/img/office_building.png";   
                                    }
                                }
                            } else {
                                echo "assets/img/office_building.png";
                            }
                        }
                      ?>" alt="<?php echo $busy->name; ?>" >
                    </div>
                    <div class="ads-text-container flex-column align-items-center justify-content-center overflow-auto ">
                      <h4 class="text-center"><?php echo $busy->name; ?></h4>
                     <p class="text-center w-75">
                         <?php echo $busy->description; ?>
                     </p> 
                     
                     <a href="business?<?php echo $busy->verify_string; ?>/<?php echo $busy->state ?>/<?php echo $busy->town ?>/<?php echo $busy->name.".html"; ?>" class="btn btn-success px-4 py-2">View business</a>
                   </div>
                  </div>
                </div>
              </div> 
            
            </section><!-- End Services Section -->
    <?php
        }
    ?>

  </main><!-- End #main -->

<footer id="footer">

<div class="footer-top">
  <div class="container">
    <div class="row">

      <div class="col-lg-3 col-md-6 footer-links">
        <h4>Useful Links</h4>
        <ul>
          <li><i class="bx bx-chevron-right"></i> <a href="https://yenreach.com">Home</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="explorer">Explore</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="about">About Us</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="contact">Contact Us</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="add-business">Add My Business</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="faqs">FAQs</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6 footer-links">
        <h4>Our Brands</h4>
        <ul>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries#dcl">Dordorian Concept LTD</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries#bgs">BYS Graduate School</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries#bmc">BusiTech Model College</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries#tec">TEC Industrial Park</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries#btu">BusiTech University</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries#bbm">BYS Business Magazine</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries#de">Dordorian Estate</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="professional_courses">Professional Certification Programs</a></li>
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