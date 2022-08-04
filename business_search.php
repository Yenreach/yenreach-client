<?php
    require_once("../includes_public/initialize.php");
    $search_string = !empty($_GET['search']) ? urldecode($_GET['search']) : "";
    $location = !empty($_GET['location']) ? urldecode($_GET['location']) : "";
    
    if($search_string == "any_business"){
        $search = "";
    } else {
        $search = $search_string;
    }
    if($location == "any_location"){
        $the_location = "";
    } else {
        $the_location = $location;
    }
    
    $gurl = "business_search_api.php?search_string=".urlencode($search)."&location=".urlencode($the_location);
    $search_results = perform_get_curl($gurl);
    if($search_results){
        if($search_results->status == "success"){
            $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
            $per_page = 40;
            $total_count = count($search_results->data);
            
            $pagination = new Pagination($page, $per_page, $total_count);
            $buses = array_slice($search_results->data, $pagination->offset(), $per_page);   
        }
    } else {
        die("Search Result Link Broken");
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

  <title>Yenreach.com - Online Business Directory</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <!--<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">-->

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

<link rel="stylesheet" href="/assets/css/index.css" />
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
            height:20rem;
            display:flex;
            flex-direction:column;
            justify-content:space-evenly;
            align-items:flex-start;

            }
            
            .card-info{
              /*height: 7rem;*/
              padding: 0 10px;
              width:100%;
            }
            
            .card-info h3{
              color: #083640;
              font-size: 15px;
              font-weight: bold;
              text-align: left;
               font-family: 'Open Sans', sans-serif;
              padding:5px 0;
            }
            
               p{
              font-family: 'Open Sans', sans-serif;
              font-size: 13px;
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
              <input class="form-control search-text w-50 py-2" list="datalistOptions" id="exampleDataList" placeholder="e.g carpenter, restaurant, stylist, doctor, etc" name="category" value="<?php echo $search; ?>">
              <datalist id="datalistOptions">
                <?php
                    if($bus_categories->status == "success"){
                        foreach($bus_categories->data as $bus_categ){
                             echo '<option value="'.$categ->category.'">'.$categ->category.'</option>';
                        }
                    }
                ?>
              </datalist>
              <select class="form-select py-2 w-25" id="inputGroupSelect04" aria-label="Example select with button addon" name="location">
                <option value="">--Location--</option>
                <?php
                    if($states->status == "success"){
                        foreach($states->data as $state){
                            echo '<option value="'.$state->name.'"';
                            if($the_location == $state->name){
                                echo ' selected';
                            }
                            echo '>'.$state->name.'</option>';
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
                        if($search_results->status == "success"){
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
                                                
                                            
                                            
                                         
                                        </div>
                                        <div class='col-12 px-2'>
                                                
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
                                        <div class='px-2'>
                                               <p>
                                                <strong>Location: </strong><?php echo $business->state." State"; ?>
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
                            <p><?php echo $search_results->message; ?><br /><a href="explorer" class="text-success text-center">See All Businesses</a></p>
                    <?php
                        }
                    ?>
                </div>
                <?php
                    if(($search_results->status == "success") && ($pagination->total_pages() > 1)){
                ?>
                        <div class="pagination mx-lg-auto">
                            <?php
                                if($pagination->has_previous_page()){
                            ?>
                                    <li class="page-item previous-page"><a class="page-link text-dark" href="business_search?search=<?php echo $search_string ?>location=<?php echo $location; ?>&page=<?php echo $pagination->previous_page(); ?>">Prev</a></li>
                            <?php
                                }
                            ?>
                            <?php
                                for($i=1; $i<=$pagination->total_pages(); $i++){
                            ?>
                                    <li class="page-item <?php if($page == $i){ echo "pagination-active current-page"; } ?>"><a class="page-link <?php if($page != $i){ echo "text-dark"; } ?>" href="business_search?search=<?php echo $search_string ?>&location=<?php echo $location; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                            <?php
                                }
                            ?>
                            <?php
                                if($pagination->has_next_page()){
                            ?>
                                    <li class="page-item next-page"><a class="page-link text-dark" href="business_search?search=<?php echo $search_string; ?>&location=<?php echo $location; ?>&page=<?php echo $pagination->next_page(); ?>">Next</a></li>
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