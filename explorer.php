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
                transition: all 0.3s ease-in-out;
                
              }
            
              .card-image img{
                max-width: 100%;
                height: 15rem;
                object-fit:cover;
                background-size: cover;
                transition: all 0.3s ease-in-out;
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

            .business-cards{
              padding-top: 3rem;
              /* width: 80%; */
              display: grid;
              gap: 1rem;
              /* grid-template-columns: repeat(3, 1fr); */
              grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
              /* grid-auto-flow: dense; */
            }

            .business-card{
                display: flex;
                flex-direction: column;
                gap: 1rem;
                padding-bottom: 0.7rem;
                justify-content: flex-start;
                
            }

            .business-card-pic {
                width: 100%;
                height: 10rem;
                background: rgb(139, 51, 51);
                border-radius: .5rem;
                display: flex;
                justify-content: center;
                align-items: center;
                overflow: hidden;
            }

            .business-card-pic img{
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: all 0.3s ease-in-out;
            }

            .business-card-body{
                width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                gap: 0.5rem;
                width: 90%;
                transition: all 0.3s ease-in-out;
            }

            .card-body-wrap{
                display: flex;
                gap: 1rem;
                flex-direction: column;
                justify-content: space-between;
                align-items: flex-start;
                height: 17rem;
                padding: 0 0.5rem;
            }

            .business-card-title{
                font-size: 1.1rem;
                font-weight: bold;
            }

            .business-card-desc{
                font-size: 0.94rem;
            }

            .business-card-category{
                font-size: 0.8rem;
                font-weight: bold;
                color: rgb(124, 124, 124);
            }

            .business-card-button{
                width: fit-content;
                padding: 0.5rem 2.5rem;
                background-color: #333;
                border: none;
                border-radius: 0.4rem;
                font-weight: bold;
                color: #fff;
                transition: all 0.3s ease-in-out;
            }

            .business-card:hover{
                cursor: pointer;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
            }

            /* .business-card:hover .business-card-body{
                padding: 0 0.5rem;
            } */

            .business-card:hover .business-card-button{
                margin-left: 0.5rem;
            }


            .business-card:hover img{
                transform: scale(1.04);
                opacity: 0.8;
            }
            .business-card:hover .card-img{
                transform: scale(1.04);
                opacity: 0.8;
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
        <div class="business-cards">
          <?php
            foreach($buses as $business){
          ?>
          <!-- card start    -->

            <div class="business-card">
              <div class="business-card-pic">
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
              <div class="card-body-wrap">
                <div class="business-card-title">
                  <?php echo $business->name; ?>
                </div>
                <div class="business-card-body">
                  <div class="business-card-desc">
                    <?php
                      $desc_array = array();
                      $explode_desc = explode(' ', $business->description);
                      $explode_count = count($explode_desc);
                      if($explode_count >= 9){
                          for($i=0; $i<=8; $i++){
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
                  </div>
                  <div class="business-card-category">
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
                <a href="business?<?php echo $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $name.".html"; ?>"
                class="business-card-button">
                  View Business
                </a>
              </div>
            </div>
            <!-- card end     -->
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

