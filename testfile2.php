-----------explorer
<?php
    require_once("../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    
    $gurl = "fetch_public_approved_businesses_api.php";
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $page = !empty($exploded[1]) ? (int)$exploded[1] : 1;
            $per_page = 40;
            $total_count = count($businesses->data);
            
            $pagination = new Pagination($page, $per_page, $total_count);
            $buses = array_slice($businesses->data, $pagination->offset(), $per_page);
        } else {
            redirect_to("https://yenreach.com");
        }
    } else {
        die("Businesses Link Broken");
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Yenreach||Explorer</title>
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
  fbq('track', 'Yenreach Explore Page');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1136570877108121&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->

  <?php include_layout_template("links.php") ?>
   <style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap');
            *{
              margin: 0;
              padding: 0;
              box-sizing: border-box;
  font-family: 'Open Sans', sans-serif;
            }
           
            /*.card-content{
              display: flex ;
              justify-content: center;
              align-items: center;
              flex-wrap: wrap;
            }*/
            body{
                overflow-x:hidden;
            }
            
            .card{
              background: #fff;
              margin: 5px;
              box-shadow: 0 5px 25px rgb(1 1 1 / 20%);
              border-radius: 5px;
              overflow: hidden;
            }
            @media only screen and min-width: 768px {
              .card{
                height: 30.5rem;
              }
            }
            
            .card-image{
              background-color: none;
              width: 100%;
              height:15rem;
             /*padding-top: 100%  1:1 Aspect Ratio */
              /* If you want text inside of it */
            }
            
            .card-img {
            height:100%;
            width:100%;
            background-size:contain;
            object-fit: fill;
              background-repeat: no-repeat;
              background-position: center center;
              
            }
            
            .card-image img{
              max-width: 100%;
              height: 15rem;
              object-fit:contain;
              /*background-color:red;*/
            }
            .info-container{
            height:15rem;
            display:flex;
            flex-direction:column;
            justify-content:space-evenly;
            align-items:flex-start;

            }
            
            .card-info{
              height: 7rem;
              padding: 0 10px;
              width:100%;
            }
            
            .card-info h3{
              color: #083640;
              font-size: 16px;
              font-weight: bold;
              text-align: left;
               font-family: 'Open Sans', sans-serif;
              padding:5px 0;
            }
            
            .card-info p{
                font-family: 'Open Sans', sans-serif;
              font-size: 14px;
              text-align:left;
              color:#222;
              margin-right:20px;
              /*padding-bottom:5px;*/
            }
            
            .pagination{
            width:100%;
              text-align: center;
              margin: 30px 0 60px;
              user-select: none;
            }
            
            .pagination li{
              display: inline-block;
              margin: 3px;
              box-shadow: 0 5px 25px rgb(1 1 1 / 10%);
            }
            
            .pagination li a{
              color: #00c853;
              text-decoration: none;
              font-size: 1rem;
            }
            .btn{
                background:#00c853;
                color:#fff;
            }
            
            .previous-page, .next-page{
              background: #00c853!important;
              width: 80px;
              border-radius: 45px;
              cursor: pointer;
              transition: 0.3s ease;
            }
            
            .previous-page:hover{
              transform: translateX(-5px);
            }
            
            .next-page:hover{
              transform: translateX(5px);
            }
            
            .current-page, .dots{
              background: #00c853;
              width: 45px;
              border-radius: 50%;
              cursor: pointer;
            }
            
            .pagination-active{
              background-color: #00c853!important;
              color:#00c853;
            }
            
            .disable{
              background: #ccc;
            }
      
        </style>
</head>
<body>

    <!-- ======= Header ======= -->
    <?php include_layout_template("header.php"); ?>
    <!-- End Header -->

  <main id="main bg-danger">
    <section class="row " style="margin-top: 80px !important">
            <div class="d-flex flex-column justify-content-center align-items-center">
              <form action="search" method="POST" enctype="multipart/form-data" class=' col-10 col-md-10 col-lg-6 mx-md-0'>
                <div class="input-group">
                  <input class="form-control search-text py-2 w-50" list="datalistOptions" id="exampleDataList" placeholder="e.g carpenter, restaurant, stylist, doctor, etc" name="category">
                  <datalist id="datalistOptions">
                        <?php
                            foreach($bus_categories->data as $categ){
                                echo '<option value="'.$categ->category.'">'.$categ->category.'</option>';
                            }
                        ?>
                  </datalist>
                  <select class="form-select py-2 w-25" id="inputGroupSelect04" aria-label="Example select with button addon" name="location">
                    <option value="">--Any Location--</option>
                        <?php
                            if($states->status == "success"){
                                foreach($states->data as $state){
                                    echo '<option value="'.$state->name.'">'.$state->name.'</option>';
                                }
                            }
                        ?>
                  </select>
                  <button class='btn py-2 px-2 col-3 col-md-3 text-white' type="submit" name="search" style='background:#00C853'>Search</button>
                </div>
              </form>
            </div>
    </section> 
          <!-- End Breadcrumbs -->
    <div class="container">
      <div class="row" style="padding-top: 30px;">
                <div class="card-content d-flex flex-wrap flex-lg-wrap justify-content-start align-items-start ">
                    <?php
                        foreach($buses as $business){
                    ?>
                        <div class="col-12 col-lg-3 col-md-4" >
                            <div class="card">
                                <div class="card-image" loading="lazy">
                                    <div class="card-img" style="background-image: url(<?php
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
                                    ?>)">&nbsp;
                                    </div> 
                                </div>
                                <div class='info-container'>
                                  <div class="card-info">
                                      <h3><?php echo $business->name; ?></h3>
                                      <p>
                                          <?php
                                              $desc_array = array();
                                              $explode_desc = explode(' ', $business->description);
                                              $explode_count = count($explode_desc);
                                              if($explode_count >= 15){
                                                  for($i=0; $i<=15; $i++){
                                                      $desc_array[] = $explode_desc[$i];
                                                  }
                                              } else {
                                                  foreach($explode_desc as $desc){
                                                      $desc_array[] = $desc;
                                                  }
                                              }
                                              echo join(' ', $desc_array)." ...";
                                              $name_array = explode(' ', $business->name);
                                              $name = join('_', $name_array);
                                          ?>
                                      </p>
                                  </div>
                                  <div class=''>
                                        
                                    <?php
                                        if(!empty($business->categories)){
                                            echo '<p>';
                                            foreach($business->categories as $category){
                                                echo ' <a href="category?category='.urlencode($category->category).'" style="margin-top: 1px; font-size:12px;color:#00C853">#'.$category->category.'</a> ';
                                            }
                                            echo '</p>';
                                        }
                                    ?>

                                  </div>
                                  <div class="ms-2 w-100">
                                      <a href="business?<?php echo $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $name.".html"; ?>"
                                      class="btn">View Business</a>
                                  </div>
                                </div>
                            </div>
                        </div>      
                    <?php
                        }
                    ?>
                </div>
                <?php
                    if($pagination->total_pages() > 1){
                ?>
                        <div class="pagination mx-lg-auto d-flex flex-wrap justify-content-center align-items-center">
                            <?php
                                if($pagination->has_previous_page()){
                            ?>
                                    <li class="page-item previous-page"><a class="page-link text-dark" href="explorer?<?php echo $pagination->previous_page(); ?>">Prev</a></li>
                            <?php
                                }
                            ?>
                            <?php
                                for($i=1; $i<=$pagination->total_pages(); $i++){
                            ?>
                                    <li class="page-item <?php if($page == $i){ echo "pagination-active current-page"; } ?>"><a class="page-link <?php if($page != $i){ echo "text-dark"; } ?>" href="explorer?<?php echo $i; ?>"><?php echo $i; ?></a></li>
                            <?php
                                }
                            ?>
                            <?php
                                if($pagination->has_next_page()){
                            ?>
                                    <li class="page-item next-page"><a class="page-link text-dark" href="explorer?<?php echo $pagination->next_page(); ?>">Next</a></li>
                            <?php
                                }
                            ?>
                        </div>
                <?php
                    }
                ?>
      </div>
    </div>
</main>
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
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/index.js"></script>
</body>
</html>

---alll business

<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $gurl = "fetch_all_businesses_api.php";
    $businesses = perform_get_curl($gurl);
    if($businesses){
        
    } else {
        die("Businesses Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>All Businesses</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Businesses</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">All Businesses</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">All Businesses</h4>
                        </div>
                        <div class="card-body card-block">
                            <div class="table-responsive text-dark">
                                <?php
                                    if($businesses->status == "success"){
                                ?>
                                        <table class="table table-striped table-bordered text-dark" id="businesses_list">
                                            <thead>
                                                <tr>
                                                    <th>Business Name</th>
                                                    <th>State</th>
                                                    <th>Categories</th>
                                                    <th>Business Owner</th>
                                                    <th>Date Registered</th>
                                                    <th>Registration Stage</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($businesses->data as $business){
                                                        $cgurl = "fetch_business_categories_api.php?string=".$business->verify_string;
                                                        $categories = perform_get_curl($cgurl);
                                                        if($categories && $categories->status == "success"){
                                                            $categ_array = array();
                                                            foreach($categories->data as $category){
                                                                $categ_array[] = '<a href="business_category?'.$category->category_string.'/'.$category->category.'.html">'.$category->category.'</a>';
                                                            }
                                                            $categ = join(', ', $categ_array);
                                                        } else {
                                                            $categ = "";
                                                        }
                                                ?>
                                                        <tr>
                                                            <td><?php echo $business->name; ?></td>
                                                            <td><?php echo $business->state." State"; ?></td>
                                                            <td><?php echo $categ; ?></td>
                                                            <td><a href="user?<?php echo $business->user_string ?>/<?php echo $business->owner_name.".html"; ?>" class="text-primary"><?php
                                                                echo $business->owner_name;
                                                            ?></a></td>
                                                            <td><?php echo strftime("%A, %d %B %Y %I:%M:%S%p", $business->created); ?></td>
                                                            <td><?php
                                                                if($business->reg_stage == 0){
                                                                    echo '<span class="text-danger  rounded">Suspended/Deactivated</span>';
                                                                } elseif($business->reg_stage < 3){
                                                                    echo '<span class="text-warning   rounded ">Incomplete Registration</span>';
                                                                } elseif($business->reg_stage == 3){
                                                                    echo '<span class="text-primary  rounded">Pending Approval</span>';
                                                                } elseif($business->reg_stage == 4){
                                                                    echo '<span class="text-success  rounded">Approved</span>';
                                                                }
                                                            ?></td>
                                                            <td><a href="business?<?php echo $business->verify_string; ?>" class="text-primary">More Details</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                <?php
                                    } else {
                                        echo $businesses->message;
                                    }
                                ?> 
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>

-------index.php

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

<!--</header>< End Header -->
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
        </div>

    </section><!-- End Counts Section -->


    <?php
        if($weeks->status == "success"){
            $busy = $weeks->data;
    ?>
            <!-- ======= Services Section ======= -->
            <section id="services" class="services section-bg">
                <div class="container" data-aos="fade-up">
        
                    <div class="section-title">
                        <h2 class="text-capitalize">Business Of the week</h2>
                        <p>This business was the most visited business on Yenreach in the past week</p>
                    </div>
            
                    <div id="home-list" class="">
                        <div class="business-week my-4 p-0 bg-white" >
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
                            <div class="ads-text-container overflow-auto text-center py-4">
                                <h4 class="text-center"><?php echo $busy->name; ?></h4>
                                <p class="text-center w-75 mx-auto">
                                    <?php echo substr($busy->description, 0, 400)."..."; ?>
                                </p> 
                                <a href="business?<?php echo $busy->verify_string; ?>/<?php echo $busy->state ?>/<?php echo $busy->town ?>/<?php echo $busy->name.".html"; ?>" class="btn btn-success mx-auto px-4 py-2">View business</a>
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
          <li><i class="bx bx-chevron-right"></i> <a href="terms.html">Terms of service</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="policy.html">Privacy policy</a></li>
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


ifms
                                        <div class="card-image card-business mb-3" loading="lazy" style="height:150px">
                                            <div class="card-img" style="background-image: url(<?php
                                                if(!empty($related->photos)){
                                                    $photo = array_shift($related->photos);
                                                    if(file_exists($photo->filepath)){
                                                        echo $photo->filepath;
                                                    } else {
                                                        if(!empty($related->filename)){
                                                            if(file_exists("images/thumbnails/".$related->filename.".jpg")){
                                                                echo "images/thumbnails/".$related->filename.".jpg";
                                                            } else {
                                                                if(file_exists("images/".$related->filename.".jpg")){
                                                                    echo "images/".$related->filename.".jpg";
                                                                } else {
                                                                    echo "assets/img/office_building.png";
                                                                }
                                                            }
                                                        } else {
                                                            echo "assets/img/office_building.png";
                                                        }
                                                    }
                                                } else {
                                                    if(!empty($related->filename)){
                                                        if(file_exists("images/thumbnails/".$related->filename.".jpg")){
                                                            echo "images/thumbnails/".$related->filename.".jpg";
                                                        } else {
                                                            if(file_exists("images/".$related->filename.".jpg")){
                                                                echo "images/".$related->filename.".jpg";
                                                            } else {
                                                                echo "assets/img/office_building.png";
                                                            }
                                                        }
                                                    } else {
                                                        echo "assets/img/office_building.png";
                                                    }
                                                }
                                            ?>)">&nbsp;
                                            </div> 
                                        </div>
---blogpost
<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("login");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $agurl = "fetch_admin_by_string_api.php?string=".$session->verify_string;
    $admins = perform_get_curl($agurl);
    if($admins){
        if($admins->status == "success"){
            $admin = $admins->data;
        } else {
            die($admins->message);
        }
    } else {
        die("Admin Link Broken");
    }
    
    if(isset($_POST['submit'])){
        $title = !empty($_POST['title']) ? (string)$_POST['title'] : "";
        $author = !empty($_POST['author']) ? (string)$_POST['author'] : "";;
        $content = !empty($_POST['content']) ? (string)$_POST['content'] : "";
        $snippet = !empty($_POST['snippet']) ? (string)$_POST['snippet'] : "";
        $admin_string = !empty($session->verify_string) ? (string)$session->verify_string : "";

        $purl = "add_blog_post_api.php";
        $pdata = [
                'title' => $title,
                'author' => $author,
                'post' => $content,
                'snippet' => $snippet,
                'admin_string' => $admin_string
            ];
        $add_blog = perform_post_curl($purl, $pdata);
        if($add_blog){
            if($add_blog->status == "success"){
                $response = $add_blog->data;
                $session->message("Blogost has been added successfully");
                $message = "Blogost has been added successfully";
            } else {
                $message = $add_blog->message;
                
            }
        } else {
            $message = "Something went wrong";
        }
    } else {
        $title =  "";
        $author = "";
        $content = "";
        $snippet = "";
    }

    
    
    include_admin_template("header.php");
?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi, <?php echo $admin->name; ?></h4>
                            <p class="mb-0">Make a blog post today</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"><a href="javascript:void(0)"><i class="fa fa-home"></i></a></li>
                        </ol>
                    </div>
                </div>
                
                <!-- <div id="editor" class=""></div> -->
                <form action="" method="post">
                    <div class="d-flex flex-column mb-2">
                        <label for="title" class="form-label text-primary mb-0 font-weight-bold">Title of Blog:</label>
                        <input class="form-control mb-1" type="text" name="title" id="title" placeholder="Blog Title" required>
                        <label for="author" class="form-label text-primary mb-0 font-weight-bold">Name of Author:</label>
                        <input class="form-control" type="text" name="author" id="author" placeholder="Author" required>
                        <label for="snippet" class="form-label text-primary mb-0 font-weight-bold">Snippet:</label>
                        <input class="form-control" type="text" name="snippet" id="snippet" placeholder="Snippet(120 characters)" maxlength="120" required>
                    </div>
                    <label for="content" class="form-label text-primary mb-0 font-weight-bold">Post details:</label>
                    <textarea name="content" id="editor" required>
                        &lt;p&gt;This is some sample content.&lt;/p&gt;
                    </textarea>
                    <p><input class="btn btn-primary btn mt-2" type="submit" name="submit" value="Submit" required></p>
                    <?php 
                        if(!empty($message)){
                            echo "<p class='text-danger'>{$message}</p>";
                        }
                    ?>
                </form>
            </div>
        </div>
        <!-- <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script> -->
        <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/super-build/ckeditor.js"></script>
        <!--
            Uncomment to load the Spanish translation
            <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/super-build/translations/es.js"></script>
        -->
        <script>
            // This sample still does not showcase all CKEditor 5 features (!)
            // Visit https://ckeditor.com/docs/ckeditor5/latest/features/index.html to browse all the features.
            CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
                // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
                toolbar: {
                    items: [
                        'exportPDF','exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                // Changing the language of the interface requires loading the language file using the <script> tag.
                // language: 'es',
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
                placeholder: 'Welcome to CKEditor 5!',
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
                fontSize: {
                    options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                    supportAllValues: true
                },
                // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
                // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                },
                // Be careful with enabling previews
                // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
                htmlEmbed: {
                    showPreviews: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }
                    ]
                },
                // The "super-build" contains more premium features that require additional configuration, disable them below.
                // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
                removePlugins: [
                    // These two are commercial, but you can try them out without registering to a trial.
                    // 'ExportPdf',
                    // 'ExportWord',
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                    // Storing images as Base64 is usually a very bad idea.
                    // Replace it on production website with other solutions:
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                    // 'Base64UploadAdapter',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                    // from a local file system (file://) - load this site via HTTP server if you enable MathType
                    'MathType'
                ]
            });
        </script>

<?php include_admin_template("footer.php"); ?>

--edit_blog --<%- <?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("login");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    $agurl = "fetch_admin_by_string_api.php?string=".$session->verify_string;
    $admins = perform_get_curl($agurl);
    if($admins){
        if($admins->status == "success"){
            $admin = $admins->data;
        } else {
            die($admins->message);
        }
    } else {
        die("Admin Link Broken");
    }

    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $blog_string = !empty($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($blog_string)){
        $gurl = "fetch_one_blog_post_api.php?string=".$blog_string;
        $blog = perform_get_curl($gurl);
        if($blog){
            if($blog->status == "success"){         
                $blog = $blog->data;
            } else {
                redirect_to("blogs.php");
            }
        } else {
                die("Blog Link Broken");
            }
    } else {
        die("Wrong Path");
    }
    
    $gurl = "fetch_comments_api.php?string=".$blog->blog_string;
    $comments = perform_get_curl($gurl);
    if($comments){
        if($comments->status == "success"){
            $comments = $comments->data;
        } else {
            $comments = [];
        }
    } else {
        die("Comment Link Broken");
    } 

    

    if(isset($_POST['submit'])){
        $title = !empty($_POST['title']) ? (string)$_POST['title'] : "";
        $author = !empty($_POST['author']) ? (string)$_POST['author'] : "";;
        $content = !empty($_POST['content']) ? (string)$_POST['content'] : "";
        $snippet = !empty($_POST['snippet']) ? (string)$_POST['snippet'] : "";
        $admin_string = !empty($session->verify_string) ? (string)$session->verify_string : "";
        $blog_string = !empty($blog->blog_string) ? (string)$blog->blog_string : "";

        $purl = "update_blog_post_api.php";
        $pdata = [
                'title' => $title,
                'author' => $author,
                'post' => $content,
                'snippet' => $snippet,
                'admin_string' => $admin_string,
                'blog_string' => $blog_string
            ];
        $update_blog = perform_post_curl($purl, $pdata);
        if($update_blog){
            if($update_blog->status == "success"){
                $response = $update_blog->data;
                $session->message("Blogost has been updated successfully");
                $message = "Blogost has been updated successfully";
                redirect_to("blogs");
            } else {
                $message = $update_blog->message;
            }
        } else {
            $message = "Something went wrong during update";
        }
    } else {
        $title =  "";
        $author = "";
        $content = "";
        $snippet = "";
        $admin_string = '';
        $blog_string = ' ';
}
    
    
    include_admin_template("header.php");
?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi, <?php echo $admin->name; ?></h4>
                            <p class="mb-0">Make a blog post today</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"><a href="javascript:void(0)"><i class="fa fa-home"></i></a></li>
                        </ol>
                    </div>
                </div>
                
                <!-- <div id="editor" class=""></div> -->
                <form action="" method="post">
                    <div class="d-flex flex-column mb-2">
                        <label for="title" class="form-label text-primary mb-0 font-weight-bold">Title of Blog:</label>
                        <input class="form-control mb-1" type="text" name="title" id="title" placeholder="Blog Title" value="<?php echo $blog->title;  ?>">
                        <label for="author" class="form-label text-primary mb-0 font-weight-bold">Name of Author:</label>
                        <input class="form-control" type="text" name="author" id="author" placeholder="Author" value="<?php echo $blog->author; ?>">
                        <label for="snippet" class="form-label text-primary mb-0 font-weight-bold">Snippet:</label>
                        <input class="form-control" type="text" name="snippet" id="snippet" placeholder="Snippet(120 characters)" maxlength="120" required>
                    </div>
                    <label for="content" class="form-label text-primary mb-0 font-weight-bold">Post details:</label>
                    <textarea name="content" id="editor">
                        <?php echo $blog->post ?>
                    </textarea>
                    <p><input class="btn btn-primary btn mt-2" type="submit" name="submit" value="Submit"></p>
                    <?php 
                        if(!empty($message)){
                            echo "<p class='text-danger'>{$message}</p>";
                        }
                    ?>
                </form>
            </div>
        </div>
        <!-- <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script> -->
        <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/super-build/ckeditor.js"></script>
        <!--
            Uncomment to load the Spanish translation
            <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/super-build/translations/es.js"></script>
        -->
        <script>
            // This sample still does not showcase all CKEditor 5 features (!)
            // Visit https://ckeditor.com/docs/ckeditor5/latest/features/index.html to browse all the features.
            CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
                // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
                toolbar: {
                    items: [
                        'exportPDF','exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                // Changing the language of the interface requires loading the language file using the <script> tag.
                // language: 'es',
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
                placeholder: 'Welcome to CKEditor 5!',
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
                fontSize: {
                    options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                    supportAllValues: true
                },
                // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
                // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                },
                // Be careful with enabling previews
                // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
                htmlEmbed: {
                    showPreviews: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }
                    ]
                },
                // The "super-build" contains more premium features that require additional configuration, disable them below.
                // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
                removePlugins: [
                    // These two are commercial, but you can try them out without registering to a trial.
                    // 'ExportPdf',
                    // 'ExportWord',
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                    // Storing images as Base64 is usually a very bad idea.
                    // Replace it on production website with other solutions:
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                    // 'Base64UploadAdapter',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                    // from a local file system (file://) - load this site via HTTP server if you enable MathType
                    'MathType'
                ]
            });
        </script>
<?php include_admin_template("footer.php"); ?> %> 

part of explorer
<div class="card">
    <div class="card-image" loading="lazy">
    <?php
        if(!empty($business->photos)){
            $photo = array_shift($business->photos);
            if(file_exists($photo->filepath)){
                $main_img = $photo->filepath;
            } else {
                if(!empty($business->filename)){
                    if(file_exists("images/thumbnails/".$business->filename.".jpg")){
                        $main_img = "images/thumbnails/".$business->filename.".jpg";
                    } else {
                        if(file_exists("images/".$business->filename.".jpg")){
                            $main_img = "images/".$business->filename.".jpg";
                        } else {
                            $main_img = "";
                        }
                    }
                } else {
                    $main_img = "";
                }
            }
        } else {
            if(!empty($business->filename)){
                if(file_exists("images/thumbnails/".$business->filename.".jpg")){
                    $main_img = "images/thumbnails/".$business->filename.".jpg";
                } else {
                    if(file_exists("images/".$business->filename.".jpg")){
                        $main_img = "images/".$business->filename.".jpg";
                    } else {
                        $main_img = "";
                    }
                }
            } else {
                $main_img = "";
            }
        }
    ?>
        <?php if (!empty($main_img)) {  ?>
        <div class="card-img" style="background-image: url(<?php echo $main_img; ?>)">
            <span style="display:none"></span>
        </div> 
        <?php $main_img=''; } else {
            echo setBusinessImage($business->name); }?>
        
    </div>
    <div class='info-container'>
    <div class="card-info">
        <h3><?php echo $business->name; ?></h3>
        <p>
            <?php
                $desc_array = array();
                $explode_desc = explode(' ', $business->description);
                $explode_count = count($explode_desc);
                if($explode_count >= 15){
                    for($i=0; $i<=15; $i++){
                        $desc_array[] = $explode_desc[$i];
                    }
                } else {
                    foreach($explode_desc as $desc){
                        $desc_array[] = $desc;
                    }
                }
                echo join(' ', $desc_array)." ...";
                $name_array = explode(' ', $business->name);
                $name = join('_', $name_array);
            ?>
        </p>
    </div>
    <div class=''>
            
        <?php
            if(!empty($business->categories)){
                echo '<p>';
                foreach($business->categories as $category){
                    echo ' <a href="category?category='.urlencode($category->category).'" style="margin-top: 1px; font-size:12px;color:#00C853">#'.$category->category.'</a> ';
                }
                echo '</p>';
            }
        ?>

    </div>
    <div class="ms-2 w-100">
        <a href="business?<?php echo $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $name.".html"; ?>"
        class="btn">View Business</a>
    </div>
    </div>
</div>