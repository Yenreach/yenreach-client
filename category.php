<?php
    require_once("../includes_public/initialize.php");
    
    $string = !empty($_GET['category']) ? (string)$_GET['category'] : "";
    $thestring = urldecode($string);
    $gurl = "fetch_businesses_by_category_api.php?string=".urlencode($thestring);
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $page = !empty($_GET['page']) ? (int)$_GET['page']: 1;
            $per_page = 40;
            $total_count = count($businesses->data);
            
            $pagination = new Pagination($page, $per_page, $total_count);
            $buses = array_slice($businesses->data, $pagination->offset(), $per_page);
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
    
    $rgurl = "fetch_related_categories_api.php?string=".urlencode($thestring);
    $relateds = perform_get_curl($rgurl);
    if($relateds){
        
    } else {
        die("Related Categories Link Broken");
    }
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Yenreach.com||<?php echo $string; ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <?php include_layout_template("links.php"); ?>
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
              height: 30.5rem;
              margin: 5px;
              box-shadow: 0 5px 25px rgb(1 1 1 / 20%);
              border-radius: 5px;
              overflow: hidden;
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
              background-repeat: no-repeat;
              background-position: center center;
              
            }
            
            .card-image img{
              max-width: 100%;
              height: 15rem;
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
        <!-- ======= Breadcrumbs ======= -->
        <section class="row " style="margin-top: 80px !important">
            <div class="container  d-flex flex-column justify-content-center align-items-center">
             <form action="search" method="POST" enctype="multipart/form-data" class=' col-10 col-md-10 col-lg-6 mx-md-0'>
            <div class="input-group">
              <input class="form-control search-text py-2" list="datalistOptions" id="exampleDataList" placeholder="e.g carpenter, restaurant, stylist, doctor, etc" name="category" value="<?php echo $thestring; ?>">
              <datalist id="datalistOptions">
                <?php
                    if($bus_categories->status == "success"){
                        foreach($bus_categories->data as $bus_categ){
                             echo '<option value="'.$bus_categ->category.'">'.$bus_categ->category.'</option>';
                        }
                    }
                ?>
              </datalist>
              <select class="form-select py-2" id="inputGroupSelect04" aria-label="Example select with button addon" name="location">
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
            <div>
            
            </div>
        </form>
      
              </div>
        </section><!-- End Breadcrumbs -->
        <div class="container">
            <div class="row" style="padding-top: 30px;">
                <div class="card-content d-flex flex-wrap flex-lg-wrap justify-content-start align-items-start ">
                    <?php
                        if($businesses->status == "success"){
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
                                            <div class=' mx-2'>
                                                
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
                        } else {
                    ?>
                            <p><?php echo $businesses->message; ?><br /><a href="explorer" class="text-success text-center">See All Businesses</a></p>
                    <?php
                        }
                    ?>
                </div>
                <?php
                    if(($businesses->status == "success") && ($pagination->total_pages() > 1)){
                ?>
                        <div class="pagination mx-lg-auto">
                            <?php
                                if($pagination->has_previous_page()){
                            ?>
                                    <li class="page-item previous-page"><a class="page-link text-dark" href="category?category=<?php echo urlencode($thestring); ?>&page=<?php echo $pagination->previous_page(); ?>">Prev</a></li>
                            <?php
                                }
                            ?>
                            <?php
                                for($i=1; $i<=$pagination->total_pages(); $i++){
                            ?>
                                    <li class="page-item <?php if($page == $i){ echo "pagination-active current-page"; } ?>"><a class="page-link <?php if($page != $i){ echo "text-dark"; } ?>" href="category?category=<?php echo urlencode($thestring); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                            <?php
                                }
                            ?>
                            <?php
                                if($pagination->has_next_page()){
                            ?>
                                    <li class="page-item next-page"><a class="page-link text-dark" href="category?category=<?php echo urlencode($thestring); ?>&page=<?php echo $pagination->next_page(); ?>">Next</a></li>
                            <?php
                                }
                            ?>
                        </div>
                <?php
                    }
                ?>
            </div>
            <?php
                if($relateds->status == "success"){
            ?>
                    <div class="row my-5">
                        <div class="col-12 text-center">
                            <h5><small>Related Categories</small></h5>
                            <?php
                                foreach($relateds->data as $other_category){
                            ?>
                                    <span class="m-2">
                                        <a href="category?category=<?php echo urlencode($other_category->category); ?>" class="text-success">#<?php echo $other_category->category; ?></a>
                                    </span>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
            <?php
                }
            ?>
        </div>
    </main>
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
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/index.js"></script>
</body>
</html>