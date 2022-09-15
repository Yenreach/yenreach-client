


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
                echo setBusinessImage($business->name); }
            ?>       
    </div>
    <div class='info-container'>
        <div class="d-flex flex-column gap-2">
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
        </div>
        <div class="ms-2 w-100">
            <a href="business?<?php echo $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $name.".html"; ?>"
            class="btn">View Business</a>
        </div>
    </div>
</div>



-explore cards
            <div class="col-12 col-lg-3 col-md-4" >
              <div class="card">
                <!-- image -->
                <div class="card-image" loading="lazy">
                  <!-- photo logic -->
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
                  <?php 
                    if (!empty($main_img)) {  
                  ?>
                    <div class="card-img" style="background-image: url(<?php echo $main_img; ?>)">
                    </div> 
                  <?php $main_img=''; } else {
                    echo setBusinessImage($business->name); }
                  ?>
                </div>

                <div class='info-container'>
                  <div class="card-info">
                    <h3 class="business-name"><?php echo $business->name; ?></h3>
                    <p class="bussiness-description">
                      <!-- business description logic -->
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
                  <div class='business-category'>
                    <!-- business category logic -->
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
                  <div class="business-button ms-2 w-100">
                    <a href="business?<?php echo $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $name.".html"; ?>"
                      class="btn">View Business
                    </a>
                  </div>
                </div>
              </div>
            </div>  

            ------explorer--
            <?php
    require_once("../includes_public/initialize.php");
    include("default_img.php");

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
            .custom-image {
              display: flex;
              width: 100%;
              height: 100%;
              justify-content: center ;
              align-items: center;
              pointer-events: none;
              text-align: center;
              text-transform: uppercase;
              font-size: 5rem;
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
              background-size:cover;
              object-fit: cover;
                background-repeat: no-repeat;
                background-position: center center;
                
              }
            
              .card-image img{
                max-width: 100%;
                height: 15rem;
                object-fit:cover;
                background-size: cover;
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
                <!-- image -->
                <div class="card-image" loading="lazy">
                  <!-- photo logic -->
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
                  <?php 
                    if (!empty($main_img)) {  
                  ?>
                    <div class="card-img" style="background-image: url(<?php echo $main_img; ?>)">
                    </div> 
                  <?php $main_img=''; } else {
                    echo setBusinessImage($business->name); }
                  ?>
                </div>

                <div class='info-container'>
                  <div class="card-info">
                    <h3 class="business-name"><?php echo $business->name; ?></h3>
                    <p class="bussiness-description">
                      <!-- business description logic -->
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
                  <div class='business-category'>
                    <!-- business category logic -->
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
                  <div class="business-button ms-2 w-100">
                    <a href="business?<?php echo $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $name.".html"; ?>"
                      class="btn">View Business
                    </a>
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
              <li><i class="bx bx-chevron-right"></i> <a href="terms.html">Terms of service</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="policy.html">Privacy policy</a></li>
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

<-------------add business ->>>>>>>>

<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    if(!$session->is_business_logged()){
        redirect_to("dashboard");
    }
    
    $gurl = "fetch_business_by_string_api.php?string=".$session->business_string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
            
            if(isset($_POST['submit'])){
                $file = !empty($_FILES['file']) ? $_FILES['file'] : array();
                if(!empty($file['name'])){
                    $upload = "yes";
                } else {
                    $upload = "no";
                }
                
                $purl = "update_business_api_level3_api.php";
                $pdata = [
                        'business_string' => $session->business_string,
                        'upload' => $upload
                    ];
                $complete_registration = perform_post_curl($purl, $pdata);
                if($complete_registration){
                    if($complete_registration->status == "success"){
                        $complete = $complete_registration->data;
                        
                        if($upload == "yes"){
                            if($file['size'] <= 1204800){
                                $photo = new Photo();
                                $photo->filename = $complete->filename;
                                $photo->load($file['tmp_name']);
                                if($file['size'] <= 304800){
                                    $photo->save_logo_compressed();
                                }
                                elseif ($file['size'] <= 700800) {
                                    $photo->save_logo_compressed($extension="jpg", $compression=50, $permissions=null);
                                }
                                else {
                                    $photo->save_logo_compressed($extension="jpg", $compression=30, $permissions=null);
                                }
                                $photo->scale(30);
                                $photo->save_logo_thumbnail("jpg", 40, null);
                                $lpurl = "add_activity_log_api.php";
                                $lpdata = [
                                        'agent_type' => "user",
                                        'agent_string' => $session->verify_string,
                                        'object_type' => "Businesses",
                                        'object_string' => $session->business_string,
                                        "activity" => "Update",
                                        "details" => "Third Stage of Business Registration carried out by the User"
                                    ];
                                perform_post_curl($lpurl, $lpdata);
                                $session->message("Business has been registered succesfully");
                                redirect_to("business_profile");
                            } else {
                                $message = "Image must not be more than 1MB";
                            }
                        } else {
                            $session->message("Business has been registered succesfully");
                            redirect_to("business_profile");
                        }
                    } else {
                        $message = $complete_registration->message;
                    }
                } else {
                    $message = "Registration Link Broken";
                }
            }
        } else {
            die($businesses->message);
        }
    } else {
        die("Business Link Broken");
    }
    
    include_portal_template("header.php");
?>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Roboto', sans-serif;
            }
            
            body {
                width: 100%;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
            }
            
           .file__upload {
                max-width: 400px;
                /* height: 445px; */
                box-shadow: 0 0 20px rgba(0,0,0,.3);
            }  
            
            .header {
                width: 100%;
                background: white;
                padding: 40px 20px 40px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #e0e0e0;
                border-radius: 5px 5px 0 0;
            }
            
            .header span {
                color: #00C853;
                font-size: 1.5rem;
            }
            .container {
                background: #FFF;
                padding: 30px 5px;
                width: 100%;
                /* height: calc(100% - 145px); */
                border-radius: 0 0 5px 5px;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                text-align: center;
            }
            input {
                opacity: 0;
            }

            label {
            display:inline-block;
            padding: 15px 15px;
            text-align:center;
            background:#00C853;
            color:#fff;
            font-size:15px;
            font-weight:600;
            border-radius:10px;
            cursor:pointer;
            }

            .preview {
                margin-top: 20px;
                width: 100%;
            }
            .preview-file {
                width: 90%;
                height: 100%;
                margin-left: auto;
                margin-right: auto;
                display: none;

            }
            .btn-green {
                background: #00C853;
            }
            .bottom {
                width: 100%;
                margin-top: 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        
        </style>
        <main class="main" id="main">
          <div class="file__upload">
                <div class="header">
                    <span>Photo Upload</span>
                    <a href="skip_logo_upload" class="btn btn-outline-warning">Skip</a>			
                </div>
                <div class="bg-white container">
                    <ul class="row p-0 w-100">
                            <li class='col-4 list-unstyled d-flex align-items-center justify-content-center text-white' style='height:3rem;background-color:#00C853;font-size:12px'>Business 
                            details</li>
                            <li class=' col-4 list-unstyled d-flex align-items-center justify-content-center text-white' style='height:3rem;background-color:#00C853; font-size:12px'>Business category</li>
                            <li class=' col-4  list-unstyled d-flex align-items-center justify-content-center text-white' style='height:3rem;background-color:#00C853;font-size:12px'>Business file</li>
                        </ul>
                    <br>
                    <!-- <strong>Drag and drop</strong> files here<br> -->
                    Please only (JPG, JPEG, PNG format allowed) note that 
                    Photo must not be more than 1MB <br>
                    Click on the button below to select file to upload

                    <form action="add_business_comp" method="POST" enctype="multipart/form-data" class="body h-75 pb-2 mb-1">
                        <div class="preview">
                            <img class='preview-file' id="file-preview">
                        </div>
                        <!-- Sharable Link Code -->
                
                        <input type="file" name="file" id="upload" accept="image/jpg, image/jpeg, image/png" onchange="showPreview(event)" required>
                        <label for='upload' class='mb-4'>Upload Image</label>
                        
                        <?php 
                            if(!$message == ''){
                                echo '<br>';
                                echo "<script type='text/javascript'>alert('$message');</script>";
                            }
                            $message = '';
                        ?>
                        
                        <div class="bottom">
                            <a href="add_business_cont" class="btn btn-danger mb-1 btn-lg"><< Back</a>
                            <button type="submit" name="submit" class="btn btn-success btn-green btn-lg">Done</button>
                        </div>
                        
                    </form> 
                </div>
          </div>
        </main>
        <script>
            function showPreview(event){
                if(event.target.files.length > 0){
                   
                    var src = URL.createObjectURL(event.target.files[0]);
                    console.log(src)
                    var preview = document.getElementById("file-preview");
                    preview.src = src;
                    preview.style.height = "300px"
                    preview.style.display = "block";
                }
                }
        </script>

<?php include_portal_template("footer.php"); ?>


,=--------------------blogpost----------
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
        $file_path = "BLOG_PHOTO_".substr($admin_string,0,4).time();
        $file = !empty($_FILES['file']) ? $_FILES['file'] : array();
        print_r($_FILES);

        if (!empty($title) && !empty($author) && !empty($content) && !empty($snippet) && !empty($admin_string) && !empty($file)) {
            if($file['size'] <= 204800){
                $photo = new Photo();
                $photo->filename = $file_path;
                $photo->load($file['tmp_name']);
                $photo->save_logo_compressed();
                $photo->scale(30);
                $photo->save_logo_thumbnail("jpg", 40, null);
                
                // $lpurl = "add_activity_log_api.php";
                // $lpdata = [
                //         'agent_type' => "user",
                //         'agent_string' => $session->verify_string,
                //         'object_type' => "Businesses",
                //         'object_string' => $session->business_string,
                //         "activity" => "Update",
                //         "details" => "Third Stage of Business Registration carried out by the User"
                //     ];
                // perform_post_curl($lpurl, $lpdata);
                // $session->message("Business has been registered succesfully");
                // redirect_to("business_profile");
            } else {
                $message = "Image must not be more than 200KB";
            }

            $purl = "add_blog_post_api.php";
            $pdata = [
                    'title' => $title,
                    'author' => $author,
                    'post' => $content,
                    'snippet' => $snippet,
                    'admin_string' => $admin_string,
                    'file_path' => $file_path,
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
        }
        
    } else {
        $title =  "";
        $author = "";
        $content = "";
        $snippet = "";
        $file_path = "";
    }

    
    
    include_admin_template("header.php");
?>
<style>
    .ck-rounded-corners .ck.ck-editor__main>.ck-editor__editable, .ck.ck-editor__main>.ck-editor__editable.ck-rounded-corners { color: #454545;}

</style>
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
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="d-flex flex-column mb-2">
                        <label for="title" class="form-label text-primary mb-0 font-weight-bold">Title of Blog:</label>
                        <input class="form-control mb-1" type="text" name="title" id="title" placeholder="Blog Title" required>
                        <label for="author" class="form-label text-primary mb-0 font-weight-bold">Name of Author:</label>
                        <input class="form-control" type="text" name="author" id="author" placeholder="Author" required>
                        <label for="snippet" class="form-label text-primary mb-0 font-weight-bold">Snippet:</label>
                        <input class="form-control" type="text" name="snippet" id="snippet" placeholder="Snippet(120 characters)" maxlength="120" required>
                        <input class="form-control" type="file" name="file" id="file" accept="image/jpg, image/jpeg, image/png">
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


index carousel

<div class="carousel" id="carousel"> 
  <div class="carousel__container">
    <ul class="carousel__indicators">   
      <li class="active" data-slide-to="0" data-flag="true"></li>
      <li data-slide-to="1"></li>
      <li data-slide-to="2"></li>
      <li data-slide-to="3"></li>
    </ul>
    <div class="carousel__inner">
      <div class="carousel__item active"><img src="https://picsum.photos/1100/500?image=10"/></div>
      <div class="carousel__item"> <img src="https://picsum.photos/1100/500?image=362"/></div>
      <div class="carousel__item"> <img src="https://picsum.photos/1100/500?image=134"/></div>
      <div class="carousel__item"> <img src="https://picsum.photos/1100/500?image=248"/></div>
    </div>
    <a class="carousel__control carousel__control--prev">
      <span class="carousel__control--prev__icon">
        <svg class="svg-inline--fa fa-angle-left fa-w-8" aria-hidden="true" data-prefix="fas" data-icon="angle-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
          <path fill="currentColor" d="M31.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L127.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L201.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34z"></path>
        </svg>
      </span>
    </a><a class="carousel__control carousel__control--next"><span class="carousel__control--next__icon">
    <svg class="svg-inline--fa fa-angle-right fa-w-8" aria-hidden="true" data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
      <path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path>
    </svg></span></a>
  </div>
</div>