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
                            if($file['size'] <= 204800){
                                $photo = new Photo();
                                $photo->filename = $complete->filename;
                                $photo->load($file['tmp_name']);
                                $photo->save_logo_compressed();
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
                                $message = "Image must not be more than 200KB";
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
                padding: 30px 20px;
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
                    <br>
                    <!-- <strong>Drag and drop</strong> files here<br> -->
                    Please only (JPG, JPEG, PNG format allowed) note that 
                    Photo must not be more that 200KB <br>
                    Click on the button below to select file to upload

                    <form action="add_business_comp" method="POST" enctype="multipart/form-data" class="body h-75 pb-2 mb-1">
                        <div class="preview">
                            <img class='preview-file' id="file-preview">
                        </div>
                        <!-- Sharable Link Code -->
                
                        <input type="file" name="file" id="upload" accept="image/jpg, image/jpeg, image/png" onchange="showPreview(event)" required>
                        <label for='upload' class='mb-4'>Upload Image</label>
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
                            if($file['size'] <= 204800){
                                $photo = new Photo();
                                $photo->filename = $complete->filename;
                                $photo->load($file['tmp_name']);
                                $photo->save_logo_compressed();
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
                                $message = "Image must not be more than 200KB";
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
                padding: 30px 20px;
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
                    <br>
                    <!-- <strong>Drag and drop</strong> files here<br> -->
                    Please only (JPG, JPEG, PNG format allowed) note that 
                    Photo must not be more that 200KB <br>
                    Click on the button below to select file to upload

                    <form action="add_business_comp" method="POST" enctype="multipart/form-data" class="body h-75 pb-2 mb-1">
                        <div class="preview">
                            <img class='preview-file' id="file-preview">
                        </div>
                        <!-- Sharable Link Code -->
                
                        <input type="file" name="file" id="upload" accept="image/jpg, image/jpeg, image/png" onchange="showPreview(event)" required>
                        <label for='upload' class='mb-4'>Upload Image</label>
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


oldestdsssssssssssssssssssssssss

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
                            if($file['size'] <= 204800){
                                $photo = new Photo();
                                $photo->filename = $complete->filename;
                                $photo->load($file['tmp_name']);
                                $photo->save_logo_compressed();
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
                                $message = "Image must not be more than 200KB";
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
                width: 400px;
                height: 445px;
                margin: 20px;
                box-shadow: 0 0 20px rgba(0,0,0,.3);
            }
            
            .file__upload .header {
                width: 100%;
                height: 145px;
                background: #00C853;
                padding: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 5px 5px 0 0;
            }
            
            .file__upload .header p {
                color: #FFF;
            }
            
            .file__upload .header p i.fa {
                margin-right: 10px;
            }
            
            .file__upload .header p span {
                font-size: 2rem;
                font-weight: 100;
            }
            
            .file__upload .header p span span {
                font-weight: 600;
            }
            
            .file__upload .body {
                background: #FFF;
                width: 100%;
                height: calc(100% - 145px);
                border-radius: 0 0 5px 5px;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                text-align: center;
            }
            
            .file__upload .body input[type="file"] {
                opacity: 0.3;
            
            }
            
            .file__upload .body i.fa {
                color: #d3d3d3;
                margin-bottom: 20px;
            }
            
            .file__upload .body p strong {
                color: #00C853;
            }
            
            .file__upload .body p span {
                color: #00C853;
                text-decoration: underline;
            }
            
            .file__upload button.button {
                background: #00C853;
                border: none;
                outline: none;
                margin: 20px 0;
                padding: .7rem 2rem;
                font-size: 1.3rem;
                color: #FFF;
                border-radius: 3px;
                opacity: .8;
                cursor: pointer;
                transition: .3s;
            }
            
            .file__upload button.button:hover {
                opacity: 1;
            }
            
            #link_checkbox {
                display: none;
            }
            
            #link {
                border: 1px solid;
                color: #00C853;
                background: none;
                width: calc(100% - 20px);
                border-radius: 0;
                outline: none;
                padding: 10px;
                font-size: 1rem;
                margin: 10px 0;
                display: none;
            }
            
            #link_checkbox:checked ~ #link {
                display: block;
            }
            
            label[for="link_checkbox"] {
                padding: .5rem 2rem;
                background: #00C853;
                color: #FFF;
                outline: none;
                cursor: pointer;
            }
            
            .download .download_link {
                text-decoration: none;
                color: #FFF;
                background: #00C853;
                padding: .5rem 2rem;
                border-radius: 3px;
                opacity: .8;
                transition: .3s;
            }
            
            .download .download_link:hover {
                opacity: 1;
            }
            #upload{
                background-color:black;
            }
        </style>
        <main class="main" id="main">
          <div class="file__upload">
            <div class="header">
              <p><i class="fa fa-cloud-upload fa-2x"></i><span><span>up</span>load</span></p>			
            </div>
            <form action="add_business_comp" method="POST" enctype="multipart/form-data" class="body h-75 pb-3 mb-2">
              <!-- Sharable Link Code -->
              
              <input type="file" name="file" id="upload" accept="image/jpg, image/jpeg, image/png" required>
              <label for="upload">
                <i class="fa fa-file-text-o fa-3x"></i>
                <p>
                <br>
                  <!-- <strong>Drag and drop</strong> files here<br> -->
                  <span>browse</span> to begin the upload. Please only (JPG, JPEG, PNG format allowed) note that 
                  Photo must not be more that 200KB
                </p>
              </label>
              <button type="submit" name="submit" class="btn button">Upload</button>
              <br />
              <a href="skip_logo_upload" class="btn btn-warning">Skip</a>
              <br />
              <a href="add_business_cont" class="btn btn-danger mb-1"><< Back</a>
            </form>
          </div>
        </main>

<?php include_portal_template("footer.php"); ?>

<ul class="d-flex align-items-center justify-content-center col-11 mx-auto pe-5">
                                    <li class='col-5 list-unstyled d-flex align-items-center justify-content-center text-white' style='height:3rem;background-color:#00C853;font-size:12px'>Business 
                                    details</li>
                                    <li class=' col-5 list-unstyled d-flex align-items-center justify-content-center' style='height:3rem;background-color:rgba(101, 156, 240, 0.1); font-size:10px'>Business category</li>
                                    <li class=' col-5  list-unstyled d-flex align-items-center justify-content-center' style='height:3rem;background-color:rgba(101, 156, 240, 0.1);font-size:10px'>Business file</li>
                                </ul>



                                index   ------------------<%- 
                                
                            
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



index css ------------<%-  %> 
/**
* Template Name: Presento - v3.5.0
* Template URL: https://bootstrapmade.com/presento-bootstrap-corporate-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}



#home-list .card img {
  width: 100%;
  height:50%;
  object-fit: cover;
}

/*--------------------------------------------------------------
# General
--------------------------------------------------------------*/

body {
  background-color: #fff;
  max-width: 100vw;
  overflow-x: hidden;
  color:#444444;
}

a {
  color: #00C853;
  text-decoration: none;
}

a:hover {
  color: #00E676;
  text-decoration: none;
}

h1, h2, h3, h4, h5, h6 {
  font-family: "Open Sans", sans-serif;
}


/*--------------------------------------------------------------
# Back to top button
--------------------------------------------------------------*/
.back-to-top {
  position: fixed;
  visibility: hidden;
  opacity: 0;
  right: 15px;
  bottom: 15px;
  z-index: 996;
  background: #00C853;
  width: 40px;
  height: 40px;
  border-radius: 4px;
  transition: all 0.4s;
}
.back-to-top i {
  font-size: 28px;
  color: #fff;
  line-height: 0;
}
.back-to-top:hover {
  background: #00E676;
  color: #fff;
}
.back-to-top.active {
  visibility: visible;
  opacity: 1;
}

/*--------------------------------------------------------------
# Disable aos animation delay on mobile devices
--------------------------------------------------------------*/
@media screen and (max-width: 768px) {
  [data-aos-delay] {
    transition-delay: 0 !important;
  }
}
/*--------------------------------------------------------------
# Header
--------------------------------------------------------------*/
#header {
  /* background: #fff; */
  background-color: #fff;
  transition: all 0.5s;
  z-index: 997;
  padding: 10px 0;
}

#header.header-scrolled {
  padding: 12px 0;
  box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
  background-color: #fff;
}
#header .logo {
  font-size: 30px;
  margin: 0;
  padding: 0;
  font-weight: 600;
  font-family: 'Open Sans', sans-serif;
}
#header .logo a {
  color: #111111;
}
#header .logo a span {
  color: #00c853;
}
#header .logo img {
  max-height: 60px;
}

/*--------------------------------------------------------------
# Get Startet Button
--------------------------------------------------------------*/
.get-started-btn {
  margin-left: 30px;
  background: #00c853;
  color: #fff;
  border-radius: 4px;
  padding: 8px 25px;
  white-space: nowrap;
  transition: 0.3s;
  font-size: 14px;
  font-weight: 600;
  display: inline-block;
}
.active-btn {
  color: #00c853;
  background-color: #fff;
}
.get-started-btn:hover {
  background: #111111;
  color: #fff;
}
@media (max-width: 992px) {
  .get-started-btn {
    margin: 0 15px 0 0;
    padding: 6px 18px;
  }
}


.overlay {
  position: fixed;
  top: 0;
  width: 100vw;
  height: 100vh;

  background-color: rgba(0, 0, 0, 0.6);
  animation: disappear 0.3s ease-in;
  z-index: 2000;
  display: none;
}

@keyframes disappear {
  0% {
    transform: translateY(100vh);
  }
  100% {
    transform: translateY(0);
  }
}
.add-overlay {
  display: flex;
  align-items: center;
  justify-content: center;
}

/*--------------------------------------------------------------
# Navigation Menu
--------------------------------------------------------------*/
/**
* Desktop Navigation
*/
.navbar {
  padding: 0;
}
.navbar ul {
  margin: 0;
  padding: 0;
  display: flex;
  list-style: none;
  align-items: center;
}
.navbar li {
  position: relative;
}
.navbar a,
.navbar a:focus {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 0 10px 30px;
  font-family: 'Open Sans', sans-serif;
  font-size: 15px;
  font-weight: 600;
  color: #111111;
  white-space: nowrap;
  transition: 0.3s;
}
.navbar a i,
.navbar a:focus i {
  font-size: 12px;
  line-height: 0;
  margin-left: 5px;
}
.navbar a:hover,
.navbar .active,
.navbar .active:focus,
.navbar li:hover > a {
  color: #00c853;
}
.navbar .dropdown ul {
  display: block;
  position: absolute;
  left: 30px;
  top: calc(100% + 30px);
  margin: 0;
  padding: 10px 0;
  z-index: 99;
  opacity: 0;
  visibility: hidden;
  background: #fff;
  box-shadow: 0px 0px 30px rgba(127, 137, 161, 0.25);
  transition: 0.3s;
}
.navbar .dropdown ul li {
  min-width: 200px;
}
.navbar .dropdown ul a {
  padding: 10px 20px;
  font-size: 14px;
}
.navbar .dropdown ul a i {
  font-size: 12px;
}
.navbar .dropdown ul a:hover,
.navbar .dropdown ul .active:hover,
.navbar .dropdown ul li:hover > a {
  color: #00c853;
}
.navbar .dropdown:hover > ul {
  opacity: 1;
  top: 100%;
  visibility: visible;
}
.navbar .dropdown .dropdown ul {
  top: 0;
  left: calc(100% - 30px);
  visibility: hidden;
}
.navbar .dropdown .dropdown:hover > ul {
  opacity: 1;
  top: 0;
  left: 100%;
  visibility: visible;
}
@media (max-width: 1366px) {
  .navbar .dropdown .dropdown ul {
    left: -90%;
  }
  .navbar .dropdown .dropdown:hover > ul {
    left: -100%;
  }
}

/**
* Mobile Navigation
*/
.mobile-nav-toggle {
  display: none;
  color: #fff;
  font-size: 28px;
  cursor: pointer;
  line-height: 0;
  transition: 0.5s;
}

.bi-x {
  font-size: 28px;
  cursor: pointer;
  display: none;
  line-height: 0;
  transition: 0.5s;
  color: #111111;
}

@media (max-width: 991px) {
  .mobile-nav-toggle {
    display: block;
  }
   .toggle {
    display: none;
  }


  .navbar ul {
    display: none;
    /* background-color: red; */
  }
}
.navbar-mobile {
  position: fixed;
  overflow: hidden;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  background-color: #fff;
  transition: 0.3s;
  z-index: 999;
}
.navbar-mobile .mobile-nav-toggle,
.close {
  position: absolute;
  top: 15px;
  right: 15px;
}
.navbar-mobile ul {
  display: block;
  position: absolute;
  top: 55px;
  right: 15px;
  bottom: 15px;
  left: 15px;
  padding: 10px 0;
  background-color: #fff;
  overflow-y: auto;
  transition: 0.3s;
}
.navbar-mobile a,
.navbar-mobile a:focus {
  padding: 10px 20px;
  font-size: 15px;
  color: #111111;
}
.navbar-mobile a:hover,
.navbar-mobile .active,
.navbar-mobile li:hover > a {
  color: #00c853;
}
.navbar-mobile .getstarted,
.navbar-mobile .getstarted:focus {
  margin: 15px;
}
.navbar-mobile .dropdown ul {
  position: static;
  display: none;
  margin: 10px 20px;
  padding: 10px 0;
  z-index: 99;
  opacity: 1;
  visibility: visible;
  background: #fff;
  box-shadow: 0px 0px 30px rgba(127, 137, 161, 0.25);
}
.navbar-mobile .dropdown ul li {
  min-width: 200px;
}
.navbar-mobile .dropdown ul a {
  padding: 10px 20px;
}
.navbar-mobile .dropdown ul a i {
  font-size: 12px;
}
.navbar-mobile .dropdown ul a:hover,
.navbar-mobile .dropdown ul .active:hover,
.navbar-mobile .dropdown ul li:hover > a {
  color: #00c853;
}
.navbar-mobile .dropdown > .dropdown-active {
  display: block;
}

/*--------------------------------------------------------------
# Hero Section
--------------------------------------------------------------*/
#hero {
  width: 100%;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.6);
  /* background: #00c853; */
  /* background-color: #fff; */
  background-size: cover;
  display: flex;
  justify-content: center;
  align-items: center;
   position: relative; 
}

.hero-container {
  width: 100%;
  height: 100vh;
}
.hero-text-container {
  height: inherit;
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  z-index: 100;
}

@keyframes slider {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}
.row-top {
  height: 100vh;
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  animation-name: slider;
  animation-duration: 0.5s;
  animation-timing-function: cubic-bezier(0.075, 0.82, 0.165, 1);
}

#first-slider {
      height: 100vh;
  background: linear-gradient(
      rgba(0, 0, 0, 0.6),
      rgba(0, 0, 0, 0.6),
      rgba(0, 0, 0, 0.6)
    ),
    url('/assets/img/hero-two.jpg');
    background-repeat: no-repeat;
      background-position: center;
  background-size: cover;
  animation-name: slider;
  animation-duration: 0.4s;
  animation-timing-function: cubic-bezier(0.075, 0.82, 0.165, 1);
}
#second-slider {
      height: 100vh;
  background: linear-gradient(
      rgba(0, 0, 0, 0.6),
      rgba(0, 0, 0, 0.6),
      rgba(0, 0, 0, 0.6)
    ),
    url('/assets/img/hero-one.jpg');
    background-repeat: no-repeat;
      background-position: center;
  background-size: cover;
  animation-name: slider;
  animation-duration: 0.4s;
  animation-timing-function: cubic-bezier(0.075, 0.82, 0.165, 1);
}
#third-slider {
      height: 100vh;
  background: linear-gradient(
      rgba(0, 0, 0, 0.6),
      rgba(0, 0, 0, 0.6),
      rgba(0, 0, 0, 0.6)
    ),
    url('/assets/img/Banner-two.jpeg');
    background-repeat: no-repeat;
      background-position: center;
  background-size: cover;
  animation-name: slider;
  animation-duration: 0.4s;
  animation-timing-function: cubic-bezier(0.075, 0.82, 0.165, 1);
}
/*#third-slider {*/
/*      height: 100vh;*/
/*  background: linear-gradient(*/
/*      rgba(0, 0, 0, 0.6),*/
/*      rgba(0, 0, 0, 0.6),*/
/*      rgba(0, 0, 0, 0.6)*/
/*    ),*/
/*    url('/assets/img/hero-two.jpg');*/
/*    background-repeat: no-repeat;*/
/*      background-position: center;*/
/*  background-size: cover;*/
/*  animation-name: slider;*/
/*  animation-duration: 0.4s;*/
/*  animation-timing-function: cubic-bezier(0.075, 0.82, 0.165, 1);*/
/*}*/

#hero:before {
  content: '';
  /* background: rgba(0, 0, 0, 0.6); */
  position: absolute;
  bottom: 0;
  top: 0;
  left: 0;
  right: 0;
}
#hero h1 {
  margin-bottom: 10px;
  font-size: 50px;
  font-weight: 900;
  color: #fff;
  text-align: center;
}
#hero p {
  margin-bottom: 30px;
  font-size: 16px;
  color: #00c853;
}
#hero h2 {
  color: #fff;
  margin: 10px 0 20px 0;
  font-size: 24px;
}
#hero .btn-get-started {
  font-family: 'Open Sans', sans-serif;
  font-weight: 500;
  font-size: 16px;
  letter-spacing: 1px;
  display: inline-block;
  padding: 10px 30px;
  border-radius: 4px;
  transition: 0.5s;
  margin-top: 30px;
  color: #fff;
  background: #00c853;
  border: 2px solid #00c853;
}
#hero .btn-get-started:hover {
  background: transparent;
  border-color: #fff;
}

@media (max-width: 768px) {
  #hero {
    height: 120vh!important;
  }
  /* .hero-section {
    width: 100%;
    height: 100vh;
    background: linear-gradient(
        90deg,
        rgba(8, 54, 64, 0.8911659663865546) 35%,
        rgba(8, 54, 64, 0.7643032212885154) 100%
      ),
      url(assets/img/hero.jpg) !important;
    background-size: cover !important;
    background-repeat: center right !important;
    background-repeat: no-repeat !important;
  } */
  #hero {
    text-align: center;
    padding-top: 35px;
  }
  #hero h1 {
    font-size: 28px;
  }
  #hero p {
    display: none;
  }
  #hero h2 {
    font-size: 14px;
    line-height: 24px;
    color: #00c853;
  }
}
@media (max-height: 500px) {
  #hero {
    height: 100vh;
  }
}

.hero-section-input {
  position: relative;
  width: 100%;
  border: none;
  padding: 0.5rem 0;
}
.hero-container .hero-section-button {
  position: absolute;
  right: 0;
}
.hero-section-input::placeholder {
  font-size: 0.8rem;
  margin-left: 1rem;
  text-indent: 1rem;
}
.hero-section-input:active,
.hero-section-input:focus {
  outline-color: transparent;
}

/*--------------------------------------------------------------
# Sections General
--------------------------------------------------------------*/
section {
  padding: 60px 0;
  overflow: hidden;
  position: relative;
}

.section-title {
  text-align: center;
  padding-bottom: 30px;
  position: relative;
}
.section-title h2 {
  font-size: 32px;
  font-weight: bold;
  text-transform: uppercase;
  margin-bottom: 20px;
  padding-bottom: 20px;
  position: relative;
}
.section-title h2::after {
  content: '';
  position: absolute;
  display: block;
  width: 50px;
  height: 3px;
  background: #00c853;
  bottom: 0;
  left: calc(50% - 25px);
}
.section-title p {
  margin-bottom: 0;
}

.section-bg {
  padding: 120px 0;
  color: #fff;
}
.section-bg:before {
  content: '';
  background: #00c853;
  position: absolute;
  bottom: 60px;
  top: 60px;
  left: 0;
  right: 0;
  transform: skewY(-3deg);
}

@media (max-width: 768px) {
  .section-title h2 {
    font-size: 24px;
  }
}

/* #home-list {
  background-color: red;
} */
.filter-nav {
  height: 7rem;
}

.tabs .nav-link {
  border: 1px solid #b9b9b9;
  /* padding: 15px; */
  transition: 0.3s;
  color: #111111;
  border-radius: 0;

  cursor: pointer;
}

.tabs .nav-link h4 {
  font-size: 0.9rem;
  font-family: 'Open Sans', sans-serif;
  text-align: center;
}
.tabs .nav-link:hover {
  color: #00c853;
}
.tabs .nav-link.active {
  background: #00c853;
  color: #fff;
  border-color: #00c853;
}

.business-card-section {
  height: 30rem;
  /* background-color: red; */
}
.business-card-section .card-body h4 {
  font-size: 1rem;
  font-family: 'Open Sans', sans-serif;
  font-weight: 600;
  color: #083640;
}
.business-card-section .card-body p {
  font-size: 0.8rem;
  font-family: 'Open Sans', sans-serif;
  text-overflow: ellipsis;
}
.business-image-container {
  height: 18rem;
}
.ads-section {
  /* height: 50vh; */
  /* display: flex; */
  justify-content: space-evenly;
  align-items: center;
}

/* .ads-section div {
  width: 50%;
  height: 10;
} */

.business-week {
  height: 50vh;
  width: 100% !important;
}
.business-week .ads-image-container img {
  object-fit: contain;
  height: 100%;
  width: 100%;
}
.business-week h4 {
  font-family: 'Open Sans', sans-serif;
  font-weight: 700;
  font-size: 2rem;
  color: #083640;
}
.business-week p {
  font-size: 0.8rem;
  font-family: 'Open Sans', sans-serif;
  font-weight: normal !important;
}
.business-week div {
  width: 50%;
  height: 100%;
}
.item {
  min-height: 80vh;
  max-height: fit-content;
}

.item div {
  width: 50%;
  min-height: 100%;
}

.ads-image-container img {
  object-fit: contain;
  height: 100%;
  width: 100%;
}
.ads-text-container p {
  font-family: 'Open Sans', sans-serif;
  font-weight: bold;
  color: #111111;
}
.btn {
  border-color: #00c853;
  background-color: #00c853;
  color: #fff;
  transition: all 0.2s ease-in;
  font-size: 0.9rem;
  font-weight: normal;
}
.ads-text-container button:hover {
  background-color: #00c853;
  color: #fff;
}
.ads-text-container button:active,
.ads-text-container button:focus {
  background-color: #00c853;
  color: #fff;
}

.subscribe-image-container {
  width: 100%;
  height: 100%;
}
@media (max-width: 768px) {
  .business-week {
    display: flex !important;
    flex-direction: column;
    justify-content: center;
    height: 100vh;
  }
  .ads-image-container img {
    object-fit: cover;
    height: 100%;
    width: 100%;
  }
  .item {
    display: flex !important;
    flex-direction: column;
    justify-content: center;
  }
  .business-week div,
  .item div {
    width: 100%;
    height: 100%;
  }

  .ads-image-container {
    /*background-color: #083640;*/
  }
  .ads-text-container {
    padding-bottom: 1rem;
  }
  .ads-image-container img {
    object-fit: contain;
    height: 100%;
    width: 100%;
  }
  .ads-text-container p {
    font-family: 'Open Sans', sans-serif;
    font-weight: bold;
    color: #111111;
    font-size: 1.5rem !important;
  }
  .business-week .ads-text-container p {
    font-family: 'Open Sans', sans-serif;
    font-weight: bold;
    color: #111111;
    font-size: 0.8rem !important;
  }
}

.tabs select {
  border: 1px solid #083640;
}

/*--------------------------------------------------------------
# Breadcrumbs
--------------------------------------------------------------*/
.breadcrumbs {
  padding: 15px 0;
  background: #083640;
  min-height: 40px;
  color: #fff;
}
.breadcrumbs h2 {
  font-size: 28px;
  font-weight: 500;
}
.breadcrumbs ol {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  padding: 0 0 10px 0;
  margin: 0;
  font-size: 14px;
}
.breadcrumbs ol a {
  color: #aaaaaa;
}
.breadcrumbs ol a:hover {
  color: #fff;
  transition: 0.3s;
}
.breadcrumbs ol li + li {
  padding-left: 10px;
}
.breadcrumbs ol li + li::before {
  display: inline-block;
  padding-right: 10px;
  color: #00C853;
  content: "/";
}

/*--------------------------------------------------------------
# Clients
--------------------------------------------------------------*/
.clients .swiper-pagination {
  margin-top: 20px;
  position: relative;
}
.clients .swiper-pagination .swiper-pagination-bullet {
  width: 12px;
  height: 12px;
  background-color: #fff;
  opacity: 1;
  border: 1px solid #00C853;
}
.clients .swiper-pagination .swiper-pagination-bullet-active {
  background-color: #00C853;
}
.clients .swiper-slide img {
  opacity: 0.5;
  transition: 0.3s;
  filter: grayscale(100);
}
.clients .swiper-slide img:hover {
  opacity: 1;
  filter: none;
}

/*--------------------------------------------------------------
# About
--------------------------------------------------------------*/
.about .container {
  position: relative;
  z-index: 10;
}
.about .content {
  padding: 30px 30px 30px 0;
}
.about .content h3 {
  font-weight: 700;
  font-size: 34px;
  margin-bottom: 30px;
}
.about .content p {
  margin-bottom: 30px;
}
.about .content .about-btn {
  padding: 8px 30px 9px 30px;
  color: #fff;
  border-radius: 50px;
  transition: 0.3s;
  text-transform: uppercase;
  font-weight: 600;
  font-size: 13px;
  display: inline-flex;
  align-items: center;
  border: 2px solid #00C853;
}
.about .content .about-btn i {
  font-size: 16px;
  padding-left: 5px;
}
.about .content .about-btn:hover {
  background: #e35052;
  background: #00C853;
}
.about .icon-boxes .icon-box {
  margin-top: 30px;
}
.about .icon-boxes .icon-box i {
  font-size: 40px;
  color: #00C853;
  margin-bottom: 10px;
}
.about .icon-boxes .icon-box h4 {
  font-size: 20px;
  font-weight: 700;
  margin: 0 0 10px 0;
}
.about .icon-boxes .icon-box p {
  font-size: 15px;
  color: #848484;
}
@media (max-width: 1200px) {
  .about .content {
    padding-right: 0;
  }
}
@media (max-width: 768px) {
  .about {
    text-align: center;
  }
}

/*--------------------------------------------------------------
# Counts
--------------------------------------------------------------*/
.counts {
  padding-top: 80px;
}
.counts .count-box {
  padding: 30px 30px 25px 30px;
  width: 100%;
  position: relative;
  text-align: center;
  box-shadow: 0px 2px 35px rgba(0, 0, 0, 0.06);
  border-radius: 4px;
}
.counts .count-box i {
  position: absolute;
  width: 54px;
  height: 54px;
  top: -27px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 24px;
  background: #fff;
  color: #00C853;
  border-radius: 50px;
  border: 2px solid #fff;
  box-shadow: 0px 2px 25px rgba(0, 0, 0, 0.1);
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
.counts .count-box span {
  font-size: 36px;
  display: block;
  font-weight: 700;
  color: #111111;
}
.counts .count-box p {
  padding: 0;
  margin: 0;
    font-family: 'Open Sans', sans-serif;
  font-size: 14px;
}

/*--------------------------------------------------------------
# Tabs
--------------------------------------------------------------*/
#tabs .more a{
  padding: 15px;
  background: #00C853;
  border: 2px solid #00C853;
  border-radius: 10px;
  color: white;
  font-weight: bold;
}
#tabs .more a:hover{
  background: white;
  color: #00C853;
}
.tabs .nav-tabs {
  border: 0;
}
.tabs .nav-link {
  border: 1px solid #b9b9b9;
  padding: 15px;
  transition: 0.3s;
  color: #111111;
  border-radius: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}
.tabs .nav-link i {
  padding-right: 15px;
  font-size: 48px;
}
.tabs .nav-link h4 {
  font-size: 18px;
  font-weight: 600;
  margin: 0;
}
.tabs .nav-link:hover {
  color: #00C853;
}
.tabs .nav-link.active {
  background: #00C853;
  color: #fff;
  border-color: #00C853;
}
@media (max-width: 768px) {
  .tabs .nav-link i {
    padding: 0;
    line-height: 1;
    font-size: 36px;
  }
}
@media (max-width: 575px) {
  .tabs .nav-link {
    padding: 15px;
  }
  .tabs .nav-link i {
    font-size: 24px;
  }
}
.tabs .tab-content {
  margin-top: 30px;
}
.tabs .tab-pane h3 {
  font-weight: 600;
  font-size: 26px;
}
.tabs .tab-pane ul {
  list-style: none;
  padding: 0;
}
.tabs .tab-pane ul li {
  padding-bottom: 10px;
}
.tabs .tab-pane ul i {
  font-size: 20px;
  padding-right: 4px;
  color: #00C853;
}
.tabs .tab-pane p:last-child {
  margin-bottom: 0;
}

/*--------------------------------------------------------------
# Services
--------------------------------------------------------------*/
.services .icon-box {
  margin-bottom: 20px;
  padding: 30px;
  border-radius: 6px;
  background: #083640;
  transition: 0.3s;
}
.services .icon-box:hover {
  background: #00C853;
}

.services .icon-box h4 {
  margin-left: 2px;
  font-weight: 700;
  font-size: 18px;
}
.services .icon-box h4 a {
  color: #fff;
  transition: 0.3s;
}
.services .icon-box h4 a:hover {
  text-decoration: underline;
}
.services .icon-box .icon-box:hover h4 a {
  color: #00C853;
}
.services .icon-box p {
  margin-left: 2px;
  line-height: 20px;
  font-size: 14px;
}

/*--------------------------------------------------------------
# Portfolio
--------------------------------------------------------------*/
.portfolio .portfolio-item {
  margin-bottom: 30px;
}
.portfolio #portfolio-flters {
  padding: 0;
  margin: 0 auto 20px auto;
  list-style: none;
  text-align: center;
}
.portfolio #portfolio-flters li {
  cursor: pointer;
  display: inline-block;
  padding: 8px 15px 10px 15px;
  font-size: 14px;
  font-weight: 600;
  line-height: 1;
  text-transform: uppercase;
  color: #444444;
  margin-bottom: 5px;
  transition: all 0.3s ease-in-out;
  border-radius: 3px;
}
.portfolio #portfolio-flters li:hover, .portfolio #portfolio-flters li.filter-active {
  color: #fff;
  background: #00C853;
}
.portfolio #portfolio-flters li:last-child {
  margin-right: 0;
}
.portfolio .portfolio-wrap {
  transition: 0.3s;
  position: relative;
  overflow: hidden;
  z-index: 1;
  background: rgba(17, 17, 17, 0.6);
}
.portfolio .portfolio-wrap::before {
  content: "";
  background: rgba(17, 17, 17, 0.6);
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  transition: all ease-in-out 0.3s;
  z-index: 2;
  opacity: 0;
}
.portfolio .portfolio-wrap img {
  transition: all ease-in-out 0.3s;
}
.portfolio .portfolio-wrap .portfolio-info {
  opacity: 0;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 3;
  transition: all ease-in-out 0.3s;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  align-items: flex-start;
  padding: 20px;
}
.portfolio .portfolio-wrap .portfolio-info h4 {
  font-size: 20px;
  color: #fff;
  font-weight: 600;
}
.portfolio .portfolio-wrap .portfolio-info p {
  color: rgba(255, 255, 255, 0.7);
  font-size: 14px;
  text-transform: uppercase;
  padding: 0;
  margin: 0;
  font-style: italic;
}
.portfolio .portfolio-wrap .portfolio-links {
  text-align: center;
  z-index: 4;
}
.portfolio .portfolio-wrap .portfolio-links a {
  color: rgba(255, 255, 255, 0.4);
  margin: 0 5px 0 0;
  font-size: 28px;
  display: inline-block;
  transition: 0.3s;
}
.portfolio .portfolio-wrap .portfolio-links a:hover {
  color: #fff;
}
.portfolio .portfolio-wrap:hover::before {
  opacity: 1;
}
.portfolio .portfolio-wrap:hover img {
  transform: scale(1.2);
}
.portfolio .portfolio-wrap:hover .portfolio-info {
  opacity: 1;
}

/*--------------------------------------------------------------
# Portfolio Details
--------------------------------------------------------------*/
.portfolio-details {
  padding-top: 40px;
}
.portfolio-details .portfolio-details-slider img {
  width: 100%;
}
.portfolio-details .portfolio-details-slider .swiper-pagination {
  margin-top: 20px;
  position: relative;
}
.portfolio-details .portfolio-details-slider .swiper-pagination .swiper-pagination-bullet {
  width: 12px;
  height: 12px;
  background-color: #fff;
  opacity: 1;
  border: 1px solid #00C853;
}
.portfolio-details .portfolio-details-slider .swiper-pagination .swiper-pagination-bullet-active {
  background-color: #00C853;
}
.portfolio-details .portfolio-info {
  padding: 30px;
  box-shadow: 0px 0 30px rgba(17, 17, 17, 0.08);
}
.portfolio-details .portfolio-info h3 {
  font-size: 22px;
  font-weight: 700;
  margin-bottom: 20px;
  padding-bottom: 20px;
  border-bottom: 1px solid #eee;
}
.portfolio-details .portfolio-info ul {
  list-style: none;
  padding: 0;
  font-size: 15px;
}
.portfolio-details .portfolio-info ul li + li {
  margin-top: 10px;
}
.portfolio-details .portfolio-description {
  padding-top: 30px;
}
.portfolio-details .portfolio-description h2 {
  font-size: 26px;
  font-weight: 700;
  margin-bottom: 20px;
}
.portfolio-details .portfolio-description p {
  padding: 0;
}

.portfolio-details .portfolio-description .whatsapp {
  font-size: 36px;
  border-radius: 4px;
  background: green;
  color: #fff;
  padding: 10px 150px;
}

@media (max-width: 768px) {
  .portfolio-details .portfolio-description h2 {
    font-size: 16px;
  }

  .portfolio-details .portfolio-description .whatsapp {
      font-size: 24px;
      padding: 5px 150px;
  }
}

/*--------------------------------------------------------------
# Testimonials
--------------------------------------------------------------*/
.testimonials .testimonials-carousel, .testimonials .testimonials-slider {
  overflow: hidden;
}
.testimonials .testimonial-item {
  box-sizing: content-box;
  padding: 30px;
  margin: 30px 15px;
  min-height: 200px;
  box-shadow: 0px 2px 12px rgba(0, 0, 0, 0.08);
  position: relative;
  background: #fff;
  border-radius: 15px;
}
.testimonials .testimonial-item .testimonial-img {
  width: 90px;
  border-radius: 10px;
  border: 6px solid #fff;
  float: left;
  margin: 0 10px 0 0;
}
.testimonials .testimonial-item h3 {
  font-size: 18px;
  font-weight: bold;
  margin: 25px 0 5px 0;
  color: #111;
}
.testimonials .testimonial-item h4 {
  font-size: 14px;
  color: #999;
  margin: 0;
}
.testimonials .testimonial-item .quote-icon-left, .testimonials .testimonial-item .quote-icon-right {
  color: #fceaea;
  font-size: 26px;
}
.testimonials .testimonial-item .quote-icon-left {
  display: inline-block;
  left: -5px;
  position: relative;
}
.testimonials .testimonial-item .quote-icon-right {
  display: inline-block;
  right: -5px;
  position: relative;
  top: 10px;
}
.testimonials .testimonial-item p {
  font-style: italic;
  margin: 30px auto 15px auto;
}
.testimonials .swiper-pagination {
  margin-top: 20px;
  position: relative;
}
.testimonials .swiper-pagination .swiper-pagination-bullet {
  width: 12px;
  height: 12px;
  background-color: #fff;
  opacity: 1;
  border: 1px solid #00C853;
}
.testimonials .swiper-pagination .swiper-pagination-bullet-active {
  background-color: #00C853;
}

/*--------------------------------------------------------------
# Pricing
--------------------------------------------------------------*/
.pricing .box-silver{
    background: #B71C1C;
}

.pricing .box-gold{
    background: #FF9800;
}

.pricing .box-premium{
    background: #00C853;
}

.pricing .box {
  padding: 20px;
  text-align: center;
  border-radius: 8px;
  position: relative;
  overflow: hidden;
}
.pricing .box h3 {
  font-weight: 400;
  padding: 15px;
  font-size: 18px;
  text-transform: uppercase;
  font-weight: 600;
}
.pricing .box h4 {
  font-size: 42px;
  font-weight: 500;
  font-family: "Open Sans", sans-serif;
  margin-bottom: 20px;
}
.pricing .box h4 sup {
  font-size: 20px;
  top: -15px;
  left: -3px;
}
.pricing .box h4 span {
  font-size: 16px;
  font-weight: 300;
}
.pricing .box ul {
  padding: 0;
  list-style: none;
  text-align: center;
  line-height: 20px;
  font-size: 14px;
}
.pricing .box ul li {
  padding-bottom: 16px;
}
.pricing .box ul i {
  color: #00C853;
  font-size: 18px;
  padding-right: 4px;
}
.pricing .box ul .na {
  color: rgba(255, 255, 255, 0.5);
  text-decoration: line-through;
}
.pricing .box .btn-wrap {
  padding: 15px;
  text-align: center;
}
.pricing .box .btn-buy {
  display: inline-block;
  padding: 10px 40px 12px 40px;
  border-radius: 4px;
  color: #fff;
  transition: none;
  font-size: 14px;
  font-weight: 400;
    font-family: 'Open Sans', sans-serif;
  font-weight: 600;
  transition: 0.3s;
  border: 2px solid rgba(255, 255, 255, 0.3);
}
.pricing .box .btn-buy:hover {
  border-color: #fff;
}
.pricing .featured {
  background: #00C853;
}

/*--------------------------------------------------------------
# Frequently Asked Questions
--------------------------------------------------------------*/
.faq .faq-list {
  padding: 0;
  list-style: none;
}
.faq .faq-list li {
  border-bottom: 1px solid #eee;
  margin-bottom: 20px;
  padding-bottom: 20px;
}
.faq .faq-list a {
  display: block;
  position: relative;
  font-family: #00C853;
  font-size: 18px;
  line-height: 24px;
  font-weight: 400;
  padding-right: 25px;
  cursor: pointer;
}
.faq .faq-list i {
  font-size: 24px;
  position: absolute;
  right: 0;
  top: 0;
}
.faq .faq-list p {
  margin-bottom: 0;
  padding: 10px 0 0 0;
}
.faq .faq-list .icon-show {
  display: none;
}
.faq .faq-list a.collapsed {
  color: #343a40;
}
.faq .faq-list a.collapsed:hover {
  color: #00C853;
}
.faq .faq-list a.collapsed .icon-show {
  display: inline-block;
}
.faq .faq-list a.collapsed .icon-close {
  display: none;
}

/*--------------------------------------------------------------
# Team
--------------------------------------------------------------*/
.team .member {
  margin-bottom: 20px;
  overflow: hidden;
  border-radius: 5px;
  background: #fff;
}
.team .member .member-img {
  position: relative;
  overflow: hidden;
}
.team .member .social {
  position: absolute;
  left: 0;
  bottom: 30px;
  right: 0;
  opacity: 0;
  transition: ease-in-out 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
}
.team .member .social a {
  transition: color 0.3s;
  color: #111111;
  margin: 0 3px;
  border-radius: 50px;
  width: 36px;
  height: 36px;
  background: #00C853;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: ease-in-out 0.3s;
  color: #fff;
}
.team .member .social a:hover {
  background: #111111;
}
.team .member .social i {
  font-size: 18px;
  line-height: 0;
}
.team .member .member-info {
  padding: 25px 15px;
}
.team .member .member-info h4 {
  font-weight: 700;
  margin-bottom: 5px;
  font-size: 18px;
  color: #111111;
}
.team .member .member-info span {
  display: block;
  font-size: 13px;
  font-weight: 400;
  color: #aaaaaa;
}
.team .member .member-info p {
  font-style: italic;
  font-size: 14px;
  line-height: 26px;
  color: #777777;
}
.team .member:hover .social {
  opacity: 1;
  bottom: 15px;
}

/*--------------------------------------------------------------
# Contact
--------------------------------------------------------------*/
.contact .info-box {
height:30rem;
  color: #444444;
  text-align: center;
  border-radius: 4px;
}
.contact .info-box i {
  font-size: 32px;
  color: #00C853;
  border-radius: 50%;
  padding: 8px;
  border: 2px dotted #f8d4d5;
}
.contact .info-box h3 {
  font-size: 20px;
  color: #777777;
  font-weight: 700;
  margin: 10px 0;
}
.contact .info-box p {
  padding: 0;
  line-height: 24px;
  font-size: 14px;
  margin-bottom: 0;
}
.contact .php-email-form {
  box-shadow: 0 0 30px rgba(214, 215, 216, 0.6);
  padding: 30px;
  border-radius: 4px;
}
.contact .php-email-form .error-message {
  display: none;
  color: #fff;
  background: #ed3c0d;
  text-align: left;
  padding: 15px;
  font-weight: 600;
}
.contact .php-email-form .error-message br + br {
  margin-top: 25px;
}
.contact .php-email-form .sent-message {
  display: none;
  color: #fff;
  background: #18d26e;
  text-align: center;
  padding: 15px;
  font-weight: 600;
}
.contact .php-email-form .loading {
  display: none;
  background: #fff;
  text-align: center;
  padding: 15px;
}
.contact .php-email-form .loading:before {
  content: "";
  display: inline-block;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  margin: 0 10px -6px 0;
  border: 3px solid #18d26e;
  border-top-color: #eee;
  -webkit-animation: animate-loading 1s linear infinite;
  animation: animate-loading 1s linear infinite;
}
.contact .php-email-form .form-group {
  margin-bottom: 25px;
}
.contact .php-email-form input, .contact .php-email-form textarea {
  box-shadow: none;
  font-size: 14px;
  border-radius: 4px;
}
.contact .php-email-form input:focus, .contact .php-email-form textarea:focus {
  border-color: #111111;
}
.contact .php-email-form input {
  padding: 10px 15px;
}
.contact .php-email-form textarea {
  padding: 12px 15px;
}
.contact .php-email-form button[type=submit] {
  background: #00C853;
  border: 0;
  padding: 10px 32px;
  color: #fff;
  transition: 0.4s;
  border-radius: 4px;
}
.contact .php-email-form button[type=submit]:hover {
  background: #e35052;
}
@-webkit-keyframes animate-loading {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
@keyframes animate-loading {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

/*--------------------------------------------------------------
# Blog
--------------------------------------------------------------*/
.blog {
  padding: 40px 0 20px 0;
}
.blog .entry {
  padding: 30px;
  margin-bottom: 60px;
  transition: all 0.3s linear;
}
.blog .entry:hover{
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    
}
.blog .entry .entry-img {
  max-height: 440px;
  margin: -30px -30px 20px -30px;
  overflow: hidden;
}
.blog .entry .entry-title {
  font-size: 28px;
  font-weight: bold;
  padding: 0;
  margin: 0 0 20px 0;
}
.blog .entry .entry-title a {
  color: #00C853;
  /*transition:color 0.3s ease;*/
}
/*.blog .entry .entry-title a:hover {*/
/*  color: #00C853;*/
/*}*/
.blog .entry .entry-meta {
  margin-bottom: 15px;
  color: #777777;
}
.blog .entry .entry-meta ul {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  align-items: center;
  padding: 0;
  margin: 0;
}
.blog .entry .entry-meta ul li + li {
  padding-left: 20px;
}
.blog .entry .entry-meta i {
  font-size: 16px;
  margin-right: 8px;
  line-height: 0;
}
.blog .entry .entry-meta a {
  color: #777777;
  font-size: 14px;
  display: inline-block;
  line-height: 1;
}
.blog .entry .entry-content p {
  line-height: 24px;
}
.blog .entry .entry-content .read-more {
  -moz-text-align-last: left;
  text-align-last: left;
}
.blog .entry .entry-content .read-more a {
  display: inline-block;
  background: #00C853;
  color: #fff;
  padding: 8px 22px;
  transition: 0.3s;
  font-size: 14px;
  border-radius: 4px;
}
.blog .entry .entry-content .read-more a:hover {
  background: #00C844;
}
.blog .entry .entry-content h3 {
  font-size: 22px;
  margin-top: 30px;
  font-weight: bold;
}
.blog .entry .entry-content blockquote {
  overflow: hidden;
  background-color: #fafafa;
  padding: 60px;
  position: relative;
  text-align: center;
  margin: 20px 0;
}
.blog .entry .entry-content blockquote p {
  color: #444444;
  line-height: 1.6;
  margin-bottom: 0;
  font-style: italic;
  font-weight: 500;
  font-size: 22px;
}
.blog .entry .entry-content blockquote::after {
  content: "";
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 3px;
  background-color: #111111;
  margin-top: 20px;
  margin-bottom: 20px;
}
.blog .entry .entry-footer {
  padding-top: 10px;
  border-top: 1px solid #e6e6e6;
}
.blog .entry .entry-footer i {
  color: #5e5e5e;
  display: inline;
}
.blog .entry .entry-footer a {
  color: #1e1e1e;
  transition: 0.3s;
}
.blog .entry .entry-footer a:hover {
  color: #00C853;
}
.blog .entry .entry-footer .cats {
  list-style: none;
  display: inline;
  padding: 0 20px 0 0;
  font-size: 14px;
}
.blog .entry .entry-footer .cats li {
  display: inline-block;
}
.blog .entry .entry-footer .tags {
  list-style: none;
  display: inline;
  padding: 0;
  font-size: 14px;
}
.blog .entry .entry-footer .tags li {
  display: inline-block;
}
.blog .entry .entry-footer .tags li + li::before {
  padding-right: 6px;
  color: #6c757d;
  content: ",";
}
.blog .entry .entry-footer .share {
  font-size: 16px;
}
.blog .entry .entry-footer .share i {
  padding-left: 5px;
}
.blog .entry-single {
  margin-bottom: 30px;
}
.blog .blog-author {
  padding: 20px;
  margin-bottom: 30px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}
.blog .blog-author img {
  width: 120px;
  margin-right: 20px;
}
.blog .blog-author h4 {
  font-weight: 600;
  font-size: 22px;
  margin-bottom: 0px;
  padding: 0;
  color: #111111;
}

.entry-header{
    font-weight:900;
    margin-bottom:0.5rem;
}
.blog .blog-author .social-links {
  margin: 0 10px 10px 0;
}
.blog .blog-author .social-links a {
  color: rgba(17, 17, 17, 0.5);
  margin-right: 5px;
}
.blog .blog-author p {
  font-style: italic;
  color: #b7b7b7;
}
.blog .blog-comments {
  margin-bottom: 30px;
}
.blog .blog-comments .comments-count {
  font-weight: bold;
}
.blog .blog-comments .comment {
  margin-top: 30px;
  position: relative;
}
.blog .blog-comments .comment .comment-img {
  margin-right: 14px;
}
.blog .blog-comments .comment .comment-img img {
  width: 60px;
}
.blog .blog-comments .comment h5 {
  font-size: 16px;
  margin-bottom: 2px;
}
.blog .blog-comments .comment h5 a {
  font-weight: bold;
  color: #444444;
  transition: 0.3s;
}
.blog .blog-comments .comment h5 a:hover {
  color: #00C853;
}
.blog .blog-comments .comment h5 .reply {
  padding-left: 10px;
  color: #111111;
}
.blog .blog-comments .comment h5 .reply i {
  font-size: 20px;
}
.blog .blog-comments .comment time {
  display: block;
  font-size: 14px;
  color: #083640;
  margin-bottom: 5px;
}
.blog .blog-comments .comment.comment-reply {
  padding-left: 40px;
}
.blog .blog-comments .reply-form {
  margin-top: 30px;
  padding: 30px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}
.blog .blog-comments .reply-form h4 {
  font-weight: bold;
  font-size: 22px;
}
.blog .blog-comments .reply-form p {
  font-size: 14px;
}
.blog .blog-comments .reply-form input {
  border-radius: 4px;
  padding: 10px 10px;
  font-size: 14px;
}
.blog .blog-comments .reply-form input:focus {
  box-shadow: none;
  border-color: #ee9293;
}
.blog .blog-comments .reply-form textarea {
  border-radius: 4px;
  padding: 10px 10px;
  font-size: 14px;
}
.blog .blog-comments .reply-form textarea:focus {
  box-shadow: none;
  border-color: #ee9293;
}
.blog .blog-comments .reply-form .form-group {
  margin-bottom: 25px;
}
.blog .blog-comments .reply-form .btn-primary {
  border-radius: 4px;
  padding: 10px 20px;
  border: 0;
  background-color: #111111;
}
.blog .blog-comments .reply-form .btn-primary:hover {
  background-color: #1e1e1e;
}
.blog .blog-pagination {
  color: #444444;
}
.blog .blog-pagination ul {
  display: flex;
  padding: 0;
  margin: 0;
  list-style: none;
}
.blog .blog-pagination li {
  margin: 0 5px;
  transition: 0.3s;
}
.blog .blog-pagination li a {
  color: #111111;
  padding: 7px 16px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.blog .blog-pagination li.active, .blog .blog-pagination li:hover {
  background: #00C853;
}
.blog .blog-pagination li.active a, .blog .blog-pagination li:hover a {
  color: #fff;
}
.blog .sidebar {
  padding: 30px;
  margin: 0 0 60px 20px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}
.blog .sidebar .sidebar-title {
  font-size: 20px;
  font-weight: 700;
  padding: 0 0 0 0;
  margin: 0 0 15px 0;
  color: #111111;
  position: relative;
}
.blog .sidebar .sidebar-item {
  margin-bottom: 30px;
}
.blog .sidebar .search-form form {
  background: #fff;
  border: 1px solid #ddd;
  padding: 3px 10px;
  position: relative;
}
.blog .sidebar .search-form form input[type=text] {
  border: 0;
  padding: 4px;
  border-radius: 4px;
  width: calc(100% - 40px);
}
.blog .sidebar .search-form form button {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  border: 0;
  background: none;
  font-size: 16px;
  padding: 0 15px;
  margin: -1px;
  background: #00C853;
  color: #fff;
  transition: 0.3s;
  border-radius: 0 4px 4px 0;
  line-height: 0;
}
.blog .sidebar .search-form form button i {
  line-height: 0;
}
.blog .sidebar .search-form form button:hover {
  background: #e34c4d;
}
.blog .sidebar .categories ul {
  list-style: none;
  padding: 0;
}
.blog .sidebar .categories ul li + li {
  padding-top: 10px;
}
.blog .sidebar .categories ul a {
  color: #111111;
  transition: 0.3s;
}
.blog .sidebar .categories ul a:hover {
  color: #00C853;
}
.blog .sidebar .categories ul a span {
  padding-left: 5px;
  color: #aaaaaa;
  font-size: 14px;
}
.blog .sidebar .recent-posts .post-item + .post-item {
  margin-top: 15px;
}
.recent-post{
      font-family: 'Open Sans', sans-serif;

}
.blog .sidebar .recent-posts img {
  width: 80px;
  float: left;
}
.blog .sidebar .recent-posts h4 {
  font-size: 15px;
  margin-left: 95px;
  font-weight: bold;
}
.blog .sidebar .recent-posts h4 a {
  color: #111111;
  transition: 0.3s;
}
.blog .sidebar .recent-posts h4 a:hover {
  color: #00C853;
}
.blog .sidebar .recent-posts time {
  display: block;
  margin-left: 95px;
  font-style: italic;
  font-size: 14px;
  color: #aaaaaa;
}
.blog .sidebar .tags {
  margin-bottom: -10px;
}
.blog .sidebar .tags ul {
  list-style: none;
  padding: 0;
}
.blog .sidebar .tags ul li {
  display: inline-block;
}
.blog .sidebar .tags ul a {
  color: #515151;
  font-size: 14px;
  padding: 6px 14px;
  margin: 0 6px 8px 0;
  border: 1px solid #c4c4c4;
  display: inline-block;
  transition: 0.3s;
}
.blog .sidebar .tags ul a:hover {
  color: #fff;
  border: 1px solid #00C853;
  background: #00C853;
}
.blog .sidebar .tags ul a span {
  padding-left: 5px;
  color: #aaaaaa;
  font-size: 14px;
}

/*--------------------------------------------------------------
# Footer
--------------------------------------------------------------*/
#footer {
  color: #fff;
  font-size: 14px;
  background: #fff;
}
#footer .footer-top {
  padding: 60px 0 30px 0;
  background: #083640;
}
#footer .footer-top .footer-contact {
  margin-bottom: 30px;
}
#footer .footer-top .footer-contact h3 {
  font-size: 26px;
  line-height: 1;
  font-weight: 700;
}
#footer .footer-top .footer-contact h3 span {
  color: #00c853;
}
#footer .footer-top .footer-contact p {
  font-size: 14px;
  line-height: 24px;
  margin-bottom: 0;
  font-family: 'Open Sans', sans-serif;
}
#footer .footer-top h4 {
  font-size: 16px;
  font-weight: bold;
  position: relative;
  padding-bottom: 12px;
}
#footer .footer-top h4::after {
  content: '';
  position: absolute;
  display: block;
  width: 20px;
  height: 2px;
  background: #00c853;
  bottom: 0;
  left: 0;
}
#footer .footer-top .footer-links {
  margin-bottom: 30px;
}
#footer .footer-top .footer-links ul {
  list-style: none;
  padding: 0;
  margin: 0;
}
#footer .footer-top .footer-links ul i {
  padding-right: 2px;
  color: white;
  font-size: 18px;
  line-height: 1;
}
#footer .footer-top .footer-links ul li {
  padding: 10px 0;
  display: flex;
  align-items: center;
}
#footer .footer-top .footer-links ul li:first-child {
  padding-top: 0;
}
#footer .footer-top .footer-links ul a {
  color: #aaaaaa;
  transition: 0.3s;
  display: inline-block;
  line-height: 1;
}
#footer .footer-top .footer-links ul a:hover {
  text-decoration: none;
  color: #fff;
}
#footer .footer-newsletter {
  font-size: 15px;
}
#footer .footer-newsletter h4 {
  font-size: 16px;
  font-weight: bold;
  position: relative;
  padding-bottom: 12px;
}
#footer .footer-newsletter form {
  margin-top: 30px;
  background: #fff;
  padding: 5px 10px;
  position: relative;
  border-radius: 4px;
  text-align: left;
}
#footer .footer-newsletter form input[type='email'] {
  border: 0;
  padding: 4px 8px;
  width: calc(100% - 100px);
}
#footer .footer-newsletter form input[type='submit'] {
  position: absolute;
  top: 0;
  right: -1px;
  bottom: 0;
  border: 0;
  background: none;
  font-size: 16px;
  padding: 0 20px;
  background: #00c853;
  color: #fff;
  transition: 0.3s;
  border-radius: 0 4px 4px 0;
  box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
}
#footer .footer-newsletter form input[type='submit']:hover {
  background: #e35052;
}
#footer .credits {
  padding-top: 5px;
  font-size: 13px;
}
#footer .social-links a {
  font-size: 18px;
  display: inline-block;
  line-height: 1;
  padding: 8px 0;
  margin-right: 4px;
  border-radius: 4px;
  text-align: center;
  width: 36px;
  height: 36px;
  transition: 0.3s;
}
#footer .social-links .facebook {
  background: #304ffe;
  color: #fff;
}
#footer .social-links .insta {
  background: #ef5350;
  color: #fff;
}
#footer .social-links .linkedin {
  background: #448aff;
  color: #fff;
}
#footer .social-links .whatsapp {
  background: green;
  color: #fff;
}

#footer .social-links a:hover {
  background: #00c853;
  color: #fff;
  text-decoration: none;
}

#modal-body .social-links a {
  font-size: 48px;
  display: inline-block;
  line-height: 1;
  padding: 8px 0;
  margin-right: 4px;
  border-radius: 4px;
  text-align: center;
  width: 60px;
  height: 60px;
  transition: 0.3s;
}
#modal-body .social-links .facebook {
  background: #304ffe;
  color: #fff;
}
#modal-body .social-links .insta {
  background: #ef5350;
  color: #fff;
}
#modal-body .social-links .linkedin {
  background: #448aff;
  color: #fff;
}
#modal-body .social-links a:hover {
  background: #00c853;
  color: #fff;
  text-decoration: none;
}


.cookie-container {
  height: 10rem;
}
.button-container {
  height: 4rem;
}



if(!empty($hour->closing_time)){
                                                            $closing_time=strval(((int)writeMsg($hour->closing_time))-12);
                                                            echo " - ".$closing_time.":00pm";
                                                        } else {
                                                            echo $hour
                                                            ->timing;
                                                        }

                                                        const text = `Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus imperdiet, nulla et dictum interdum, nisi lorem egestas vitae scel erisque enim ligula venenatis dolor. Maecenas nisl est, ultrices nec congue eget, auctor vitae massa. Fusce luctus vestibulum augue ut aliquet. Nunc sagittis dictum nisi, sed ullamcorper ipsum dignissim ac. In at libero sed nunc venenatis imperdiet sed ornare turpis. Donec vitae dui eget tellus gravida venenatis. Integer fringilla congue eros non fermentum. Sed dapibus pulvinar nibh tempor porta.`
let count = true;
function myFunction() {
  var para = document.getElementById("para");
  var btn = document.getElementById('read');
  const textList = text.split(' ');
  let disp = ``;
  if (count) {
    btn.innerHTML = 'Read Less';
    para.innerHTML = text; 
    count = false;
  } 
  else {
  	btn.innerHTML = 'Read More';
    for (let i=0; i<15; i++) {
    	disp +=  textList[i] + ' ';
    }
  	para.innerHTML = disp;
    count = true;
  }
}

..............business.php'''''''''''''''''''''
<?php
    require_once("../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $exploding = !empty($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($exploding)){
        $explode = explode("/", $exploding);
        $string = array_shift($explode);
        
        $gurl = "fetch_business_by_string_api.php?string=".$string;
        $businesses = perform_get_curl($gurl);
        if($businesses){
            if($businesses->status == "success"){
                $business = $businesses->data;
                
                if($business->reg_stage >= 4){
                    if($session->is_logged_in()){
                        $user_string = $session->verify_string;
                    } else {
                        if(isset($_COOKIE['yenreach'])){
                            $user_string = $_COOKIE['yenreach'];
                        } else {
                            $gurl = "fetch_new_cookie_api.php";
                            $cookies = perform_get_curl($gurl);
                            if($cookies){
                                if($cookies->status == "success"){
                                    $cookie = $cookies->data;
                                    $user_string = $cookie->cookie;
                                    setcookie("yenreach", $user_string, time() + (86400 * 365), "/"); // 86400 = 1 day
                                } else {
                                    die($cookies->message);
                                }
                            } else {
                                die("Cookie Link Broken");
                            }
                        }
                    }
                    $purl = "create_page_visit_api.php";
                    $pdata = [
                            'business_string' => $business->verify_string,
                            'user_string' => $user_string
                        ];
                        
                    perform_post_curl($purl, $pdata);
                    
                    $cgurl = "fetch_business_categories_api.php?string=".$business->verify_string;
                    $categories = perform_get_curl($cgurl);
                    if($categories){
                        
                    } else {
                        die("Categories Link Broken");
                    }
                    
                    $pgurl = "fetch_business_public_photos_api.php?string=".$business->verify_string;
                    $photos = perform_get_curl($pgurl);
                    if($photos){
                        
                    } else {
                        die("Photos Link Broken");
                    }
                    
                    $vgurl = "fetch_business_public_videolinks_api.php?string=".$business->verify_string;
                    $links = perform_get_curl($vgurl);
                    if($links){
                        
                    } else {
                        die("Video Link Broken");
                    }
                    
                    $wgurl = "fetch_business_working_hours_api.php?string=".$business->verify_string;
                    $hours = perform_get_curl($wgurl);
                    if($hours){
                        
                    } else {
                        die("Working Hours Link Broken");
                    }
                    
                    $bgurl = "fetch_business_public_branches_api.php?string=".$business->verify_string;
                    $branches = perform_get_curl($bgurl);
                    if($branches){
                        
                    } else {
                        die("Branches Link Broken");
                    }
                    
                    $sgurl = "fetch_business_latest_subscription_api.php?string=".$business->verify_string;
                    $subs = perform_get_curl($sgurl);
                    if($subs){
                        
                    } else {
                        die("Subscriptions Link Broken");
                    }
                    
                    $rgurl = "fetch_related_businesses_api.php?string=".$business->verify_string;
                    $relateds = perform_get_curl($rgurl);
                    if($relateds->status == "success"){
                        
                    } else {
                        die("Related Businesses Link Broken");
                    }
                } else {
                    die("Business is not Approved");
                }
            } else {
                die($businesses->message);
            }
        } else {
            die("Businesses Link Broken");
        }
    } else {
        die("Wrong Path");
    }
?>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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
  fbq('track', 'Yenreach Portfolio Page');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1136570877108121&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    
    <title>Yenreach||<?php echo $business->name; ?></title>
    <meta content="<?php echo $business->description; ?>" name="description">
    <meta content="" name="keywords">
    
    <?php include_layout_template("links.php"); ?>
    <link rel="stylesheet" href="assets/lightbox/dist/css/lightbox.min.css">
    <style>
      body {
        overflow-x: hidden;
      }
      .bg-main {
        background-color: #083640;
      }
      .text-main {
        color: #083640;
      }
      .border-main {
        border: 1px solid #083640;
      }
      .desc-image {
        height: 20rem;
        width: 25rem;
      }
      .row p {
        font-size: 14px;
        /* text-justify: auto; */
      }
      .stretch-card > .card {
        width: 100%;
        min-width: 100%;
      }

      .flex {
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
      }
      .business-desc h1 p {
        text-align: justify;
      }
      .business-desc img {
        height: 100%;
        width: 100%;
      }
      
        .carded{
          background: #fff;
          margin: 5px;
          box-shadow: 0 5px 25px rgb(1 1 1 / 20%);
          border-radius: 5px;
          overflow: hidden;
        }
        
        .card-image{
          background-color: none;
          width: 100%;
          position: relative;
          padding-top: 100%;
        }
        
        .card-img {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-size:cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

      @media (max-width: 991.98px) {
        .padding {
          padding: 1.5rem;
        }
      }

      @media (max-width: 767.98px) {
        .padding {
          padding: 1rem;
        }
        .business-desc h1 {
          text-align: center;
        }
      }

      .owl-carousel .item {
        position: relative;
        margin: 3px;
        height: 20rem;
      }

      .owl-carousel .item img {
        display: block;
      }

      .owl-carousel .item {
        margin: 3px;
      }

      .owl-carousel {
        margin-bottom: 15px;
      }
      .facility {
        height: 50rem;
      }
      .content {
        height: 20rem;
        padding-bottom: 1rem;
      }
      .list {
        list-style: square !important;
        font-size: 0.95rem;
        padding: 5px 0;
      }

      .contact-info {
        height: 16rem;
      }

      .contact-info *,
      .contact-info p {
        font-size: 1rem;
      }
      .contact-info h2 {
        font-size: 1.7rem;
      }
      .social-media i {
        font-size: 1.1rem;
      }
      .recommended-container {
        height: 120vh;
        /* position: absolute;
        top: 70rem;
        right: 7vw; */
      }
      .recommended-section span {
        font-size: 1.3rem;
      }

      .review-container i {
        font-size: 30px;
        cursor: pointer;
        color: #e3e3e3;
      }
      .review-container i:hover {
        color: #e1ad01;
      }

      .card-item {
        height: 10rem;
        width: 20rem;
      }
      .video-controller {
        height: 24rem;
      }
      .image-container {
        height: 12rem;
        object-fit: contain;
      }
    </style>
</head>
<body>
    <?php include_layout_template("header.php"); ?>
    <main class="col-12 mx-auto">
        <div class="row">
            <div class="carousel-inner" style="height: 35vh; background: #f1f1f1">
                <div class="col-12 mx-auto d-flex h-75 mt-5 flex-column flex-lg-row justify-content-center align-items-center pt-lg-0">
                    <div class="col-4 mx-auto py-1">
                        <div class="media-center d-flex mx-auto align-items-center justify-content-center mb-4 mb-lg-0" style="width: 7rem">
                            <?php
                                if(!empty($business->filename)){
                                    if(file_exists("images/{$business->filename}.jpg")){
                            ?>
                                        <img
                                          src="images/<?php echo $business->filename.".jpg"; ?>"
                                          alt="<?php echo $business->name; ?>"
                                          width="100%"
                                          height="auto"
                                        />
                            <?php
                                    } else {
                            ?>
                                        <img
                                          src="assets/img/office_building.png"
                                          alt="<?php echo $business->name; ?>"
                                          width="100%"
                                          height="100%"
                                          style="border-radius: 50%"
                                        />
                            <?php
                                    }
                                } else {
                            ?>
                                    <img
                                      src="assets/img/office_building.png"
                                      alt="<?php echo $business->name; ?>"
                                      width="100%"
                                      height="100%"
                                      style="border-radius: 50%"
                                    />
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class='col-8'>
                        <h1 class="text-success font-weight-bold mb-4 text-center px-lg-3 fs-6-sm fs-6 ">
                            <?php echo $business->name; ?>
                            <?php echo output_message($message); ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="h-100 col-12 col-lg-11 mt-4 mx-auto d-flex align-items-center justify-content-between flex-wrap py-3">
                <div class="business-desc col-12 col-md-8 col-lg-6 mt-md-4 mx-md-auto py-3 text-center">
                    <h1 class="text-success mb-4 mb-md-2 fw-bold">Business Description</h1>
                    <p class="text-white text-wrap px-lg-2 text-justify mx-auto pr-0 text-muted">
                        <?php
                            echo nl2br($business->description);
                            if($categories->status == "success"){
                        ?>
                                <p>
                                    <?php
                                        foreach($categories->data as $category){
                                    ?>
                                            <span class="m-2">
                                                <a href="category?category=<?php echo urlencode($category->category); ?>" class="text-success">#<?php echo $category->category; ?></a>
                                            </span>
                                    <?php
                                        }
                                    ?>
                                </p>
                        <?php
                            }
                        ?>
                        <p>
                            <?php
                                if($session->is_logged_in()){
                                    $csgurl = "check_saved_business_api.php?user={$session->verify_string}&business={$business->verify_string}";
                                    $check_saved = perform_get_curl($csgurl);
                                    if($check_saved && $check_saved->status == "success"){
                            ?>
                                        <button class="btn btn-secondary" disabled>Added to Favourites</button>    
                            <?php
                                    } else {
                            ?>
                                        <a href="save_business?<?php echo $exploding; ?>" class="btn btn-primary">Add to Favourites</a> 
                            <?php
                                    }
                                } else {
                            ?>
                                    <a href="users/signup?page=<?php echo $url; ?>" class="btn btn-primary"
                                    onclick="return confirm('You have to Signup/Login to save this Business')">
                                        Save Business
                                    </a>
                            <?php
                                }
                            ?>
                                        
                        </p>
                    </p>
                </div>
            </div>
        </div>
        <?php
            if($photos->status == "success"){
        ?>
                <div class="row mx-auto mt-5">
                    <div class="card-content d-flex flex-wrap flex-lg-wrap justify-content-start align-items-start col-lg-8 mx-auto text-center">
                        <?php
                            foreach($photos->data as $photo){
                        ?>
                                <div class="col-12 col-lg-3 col-md-4 col-sm-6 col-xs-12 p-3">
                                    <a class="example-image-link" href="<?php echo $photo->filepath; ?>" data-lightbox="example-set">
                                        <div class="carded">
                                            <div class="card-image">
                                                <div class="card-img" style="background-image: url(<?php 
                                                    if(!empty($photo->filename)){
                                                        if(file_exists("images/thumbnails/{$photo->filename}.jpg")){
                                                            echo "images/thumbnails/{$photo->filename}.jpg";
                                                        } else {
                                                            echo $photo->filepath;
                                                        }
                                                    } else {
                                                        echo $photo->filepath;
                                                    }
                                                ?>)">&nbsp;</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>   
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <div class="row contact-container mx-auto py-5 mt-5">
            <hr/>
            <div class="col-12 col-lg-6 col-md-6 col-sm-12 d-flex flex-column justify-content-around">
                <div class="contact-info ps-4 mt-3 d-flex flex-column mb-5 mb-md-0 justify-content-around align-items-center" style="height: fit-content">
                    <h2 class="text-success text-center fs-2 fw-bold">Business Contact Details</h2>
                    <div class="phone-number d-flex flex-column align-items-start h-50 py-3 my-2 ">
                        <p class="text-main">
                            <i class="bi bi-geo me-2 py-2 rounded-md"></i>
                            <?php echo $business->address; ?>
                            <br />
                            <?php
                                if(!empty($business->town)){
                                    echo $business->town;
                                }
                                if(!empty($business->lga)){
                                    echo ", ".$business->lga." LGA";
                                }
                                if(!empty($business->state)){
                                    echo ", ".$business->state." State";
                                }
                            ?>
                        </p>
                        <?php
                            if($hours->status == "success"){
                        ?>
                                <table class="table">
                                    <tr>
                                        <td style="padding-left: 0 !important"><i class="bi bi-clock"></i></td>
                                        <td>
                                        <table class='table table-sm'>
                                            <thead >
                                                <tr>
                                                <th class='text-success' scope="col">Day</th>
                                                <th class='text-success' scope="col">Opens</th>
                                                <th class='text-success' scope="col">Closes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                function writeMsg($al){return substr($al,0,2);}
                                                
                                                foreach($hours->data as $hour){
                                            ?>    
                                                <tr>
                                                    <td class="px-2"><?php echo $hour->day; ?></td>
                                                    <td class="px-2"><?php
                                                        if(!empty($hour->opening_time)){
                                                            echo " ".$hour->opening_time."am";
                                                        } else {
                                                            echo $hour->timing;
                                                        }
                                                    ?></td>
                                                    <td class="px-2"><?php
                                                        if(!empty($hour->closing_time)){
                                                            $closing_time=strval(((int)writeMsg($hour->closing_time))-12);
                                                            echo $closing_time.":00pm";
                                                        } else {
                                                            echo $hour->timing;
                                                        }
                                                    ?></td>
                                                </tr>
                                            <?php
                                                }
                                            ?>
                                            </tbody>
                                        </table>
                                        </td>
                                    </tr>
                                </table>
                        <?php
                            }
                        ?>
                        <a href="tel:<?php echo $business->phonenumber ?>" class="text-main d-block py-2 rounded-md text-decoration-none ">
                            <i class="bi bi-telephone me-3"></i><?php echo $business->phonenumber ?>
                        </a>
                            
                        <a href="mailto:<?php echo $business->email; ?>" class="text-main py-2 rounded-md text-decoration-none">
                            <i class="bi bi-envelope me-3"></i><?php echo $business->email; ?>
                        </a>
                        <?php
                            if($subs->status == "success"){
                                $time = time();
                                $sub = $subs->data;
                                if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                                    $subgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                                    $subscriptions = perform_get_curl($subgurl);
                                    if($subscriptions && $subscriptions->status=="success"){
                                        $subscription = $subscriptions->data;
                                        if($subscription->socialmedia == 1){
                                            if(!empty($business->website)){
                                                if($session->is_logged_in()){
                        ?>
                        
                                                    <a href="<?php echo $business->website; ?>" class="text-main py-2 rounded-md text-decoration-none">
                                                        <i class="bi bi-globe me-3"></i><?php echo $business->website; ?>
                                                    </a>
                        <?php
                                                } else {
                                                    $webfirst = substr($business->website, 0, 10);
                                                    $weblast = substr($business->website, -4);
                                                    $website = $webfirst."****".$weblast;
                        ?>
                                                    <a href="users/signup?page=<?php echo $url; ?>" class="text-main py-2 rounded-md text-decoration-none"
                                                    onclick="return confirm('You have to Signup/Login to have access to this Business\' Website')">
                                                        <i class="bi bi-globe me-3"></i><?php echo $website; ?>
                                                    </a>
                        <?php
                                            
                                                }    
                                            }
                                        }
                                    }
                                }
                            }
                        ?>
                    </div>
                    <?php
                        if($subs->status == "success"){
                            $time = time();
                            $sub = $subs->data;
                            if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                                $subgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                                $subscriptions = perform_get_curl($subgurl);
                                if($subscriptions && $subscriptions->status=="success"){
                                    $subscription = $subscriptions->data;
                                    if($subscription->socialmedia == 1){
                                        if(!empty($business->whatsapp)){
                                            $whatsapp = trim($business->whatsapp);
                                            $number = substr($whatsapp, -10);
                                            $realwhatsapp = "+234".$number;
                    ?>
                                            <div class="address col-sm-10 mb-4 col-11 col-lg-4 d-flex align-items-center justify-content-center ">
                                                <?php
                                                    if($session->is_logged_in()){
                                                ?>
                                                        <a href="https://api.whatsapp.com/send?phone=<?php echo $realwhatsapp; ?>"
                                                        class="col-12 col-md-8 col-lg-10 mx-auto py-3 px-3 rounded text-white text-decoration-none" 
                                                        style='background-color:#00C853' target="_blank">
                                                            <center><span>Reach us on</span> <i class="bi bi-whatsapp fs-4 ms-2"></i></center>
                                                        </a>                                        
                                                <?php
                                                    } else {
                                                ?>
                                                        <a href="users/signup?page=<?php echo $url; ?>"
                                                        onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name ?> on WhatsApp')"
                                                        class="col-12 col-md-8 col-lg-10 mx-auto py-3 px-3 rounded text-white text-decoration-none" 
                                                        style='background-color:#00C853' target="_blank">
                                                            <center><span>Reach us on</span> <i class="bi bi-whatsapp fs-4 ms-2"></i></center>
                                                        </a>
                                                <?php
                                                    }    
                                                ?>
                                            </div>
                                            <div>
                                                 <span class='text-success'>Or you can reach us on social media.</span>
                                            </div>
                                            <div class="social-media col-12 col-lg-8 mx-auto d-flex align-items-center justify-content-between justify-content-lg-evenly mt-3">
                                                <?php
                                                    if(!empty($business->facebook_link)){
                                                ?>
                                                            <a href="<?php echo $business->facebook_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-primary text-white">
                                                                <i class="bi bi-facebook"></i>
                                                            </a>
                                                
                                                <?php
                                                    
                                                    }
                                                    if(!empty($business->twitter_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->twitter_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-secondary text-white">
                                                                <i class="bi bi-twitter"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-secondary text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on Twitter')">
                                                                <i class="bi bi-twitter"></i>
                                                            </a>
                                                <?php
                                                        }
                                                    }
                                                    if(!empty($business->instagram_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->instagram_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-warning text-white">
                                                                <i class="bi bi-instagram"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-warning text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on Instagram')">
                                                                <i class="bi bi-instagram"></i>
                                                            </a>
                                                <?php
                                                        }     
                                                    }
                                                    if(!empty($business->linkedin_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->linkedin_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-success text-white">
                                                                <i class="bi bi-linkedin"></i>
                                                            </a>                                                
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-success text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on LinkedIn')">
                                                                <i class="bi bi-linkedin"></i>
                                                            </a>
                                                <?php
                                                       }     
                                                    }
                                                    if(!empty($business->youtube_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->youtube_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-danger text-white">
                                                                <i class="bi bi-youtube"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-danger text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on YouTube')">
                                                                <i class="bi bi-youtube"></i>
                                                            </a>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </div>
                    <?php
                                        }
                                    }
                                }
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-md-6 col-sm-12 d-flex flex-column justify-content-around">
                <div class="contact-info ps-4 mt-3 d-flex flex-column mb-5 mb-md-0 justify-content-around align-items-center" style="height: fit-content">
                    <ul class="h-100 pb-4">
                        <h2 class="text-success text-center fs-2 fw-bold">Available Facilities</h2>
                        <div class="col-11 mx-auto d-flex align-items-center justify-content-center px-lg-4">
                            <div class="d-block me-5">
                                <?php
                                    if(!empty($business->facilities)){
                                        $strings = explode(",", $business->facilities);
                                        foreach($strings as $facil_string){
                                            $fgurl = "fetch_facility_by_string_api.php?string=".$facil_string;
                                            $facilities = perform_get_curl($fgurl);
                                            if(($facilities) && ($facilities->status == "success")){
                                                $facility = $facilities->data;
                                ?>
                                                <p class="list text-muted text-center"><?php echo $facility->facility; ?></p>
                                <?php
                                            }
                                        }
                                    } else {
                                        echo '<p>No Registered Facility for this Business</p>';
                                    }
                                ?>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <?php
            if($links->status == "success"){
        ?>
                <div class="row pt-2 mt-5">
                    <hr />
                    <h3 class="text-center text-success fw-bold fs-2">Business Videos</h3>
                    <div class="row p-5">
                        <?php
                            foreach($links->data as $link){
                        ?>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-3 mx-auto">
                                    <iframe src="<?php echo $link->video_link; ?>" title="YouTube video player" 
                                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen style="width: 100%; height: auto"></iframe>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <?php
            if($branches->status == "success"){
        ?>
                <div class="row pt-2 mt-5">
                    <hr />
                    <h3 class="text-center text-success fw-bold fs-2">Branches</h3>
                    <div class="row p-5">
                        <?php
                            foreach($branches->data as $branch){
                        ?>
                                <div class="col-lg-4 col-md-6 col-sm-12 mt-4 mx-auto">
                                    <div class="card" style="height: 350px; overflow: auto;">
                                        <div class="card-body text-center">
                                            <p class="text-center">
                                                <h4 class="text-success text-center"><small>Address</small></h4>
                                                <?php
                                                    echo $branch->address;
                                                    if(!empty($branch->town)){
                                                        echo "<br />".$branch->town;
                                                    }
                                                    if(!empty($branch->lga)){
                                                        echo  "<br />".$branch->lga." Local Gov't Area";
                                                    }
                                                    if(!empty($branch->state)){
                                                        echo "<br />".$branch->state.' State';
                                                    }
                                                    echo ".";
                                                ?>
                                            </p>
                                            <p class="text-center">
                                                <h4 class="text-success text-center"><small><?php echo $branch->head_designation; ?></small></h4>
                                                <?php echo $branch->head_name; ?>
                                            </p>
                                            <?php
                                                if(!empty($branch->phone) || !empty($branch->email)){
                                            ?>
                                                    
                                                    <?php
                                                        if($session->is_logged_in()){
                                                    ?>
                                                            <p>
                                                                <?php
                                                                    if(!empty($branch->phone)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-phone text-success"></i><span>
                                                                                <a href="tel:<?php echo $branch->phone ?>"><?php echo $branch->phone; ?></a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                    if(!empty($branch->email)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-envelope text-success"></i><span>
                                                                                <a href="mailto:<?php echo $branch->email; ?>"><?php echo $branch->email; ?></a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </p>
                                                    <?php
                                                        } else {
                                                            $bprefix = substr($branch->phone, 0, 4);
                                                            $bsuffix = substr($branch->phone, -3);
                                                            $bphone = $bprefix."****".$bsuffix;
                                                            
                                                            $bprestring = substr($branch->email, 0, 4);
                                                            $bpoststring = substr($branch->email, -3);
                                                            $bemail = $bprestring."****".$bpoststring;
                                                    ?>
                                                            <p>
                                                                <?php
                                                                    if(!empty($branch->phone)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-phone text-success"></i><span>
                                                                                <a href="users/signup?page=<?php echo $url; ?>"
                                                                                onclick="return confirm('You have to Signup/Login to have access to this Business\' Contact')">
                                                                                    <?php echo $bphone; ?>
                                                                                </a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                    if(!empty($branch->email)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-envelope text-success"></i><span>
                                                                                <a href="users/signup?page=<?php echo $url; ?>"
                                                                                onclick="return confirm('You have to Signup/Login to have access to this Business\' Contact')">
                                                                                    <?php echo $bemail; ?>
                                                                                </a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </p>
                                                    <?php
                                                        }
                                                    ?>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <div class="container" id="reviews_container">
            <hr />
            <div class="row" style="padding-top: 30px; padding-bottom: 30px">
                <h3 class="text-center text-success fw-bold fs-2">Reviews</h3>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <div class="row" id="review_summary">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <div class="row" id="reviews">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <input type="hidden" name="business_string" id="business_string" value="<?php echo $string; ?>" />
                        <?php
                            if($session->is_logged_in()){
                        ?>
                                <div class="form-group my-2">
                                    <textarea name="review" id="review_text" class="form-control" placeholder="You can drop your view for <?php echo $business->name; ?>" rows="7"></textarea>
                                </div>
                                <div class="form-group my-2 row" id="rating">
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="1" id="1" /><label for="1">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2"></i>
                                            <i class="bi bi-star-fill" id="3"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="2" id="2" /><label for="2">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="3" id="3" /><label for="3">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="4" id="4" /><label for="4">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="5" id="5" /><label for="5">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="5" style="color: #e1ad01"></i>
                                        </label>
                                    </p>
                                </div>
                                <div class="form-group my2">
                                    <button class="btn btn-block btn-success col-12" id="review_submit">Submit</button>
                                </div>
                        <?php
                            } else {
                        ?>
                                <div class="form-group text-center">
                                    <a href="users/signup?page=<?php echo $url."#reviews_container"; ?>" class="btn btn-success btn-block col-lg-6 mx-auto">Drop Review</a>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
            <hr />
        </div>
        <?php
            if($relateds->status == "success"){
        ?>
                <div class="container">
                    <div class="row" style="padding-top: 10px">
                        <h3 class="text-center text-success fw-bold fs-2">Recommended Businesses</h3>
                        <?php
                            foreach($relateds->data as $related){
                        ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 mb-3">
                                    <div class="card">
                                        <div class="card-image" loading="lazy">
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
                                        <div class='info-container'>
                                        <div class="card-info">
                                            <h6><small><?php echo $related->name; ?></small></h6>
                                        </div>
                                        <div class=' mx-2'>
                                                
                                            <?php
                                                if(!empty($related->categories)){
                                                    echo '<p>';
                                                    foreach($related->categories as $category){
                                                        echo '<a href="#" style="margin-top: 1px; font-size:12px;color:#00C853" class="m-2">#'.$category->category.'</a>';
                                                    }
                                                    echo '</p>';
                                                }
                                            ?>
        
                                        </div>
                                        <div class="ms-2 w-100">
                                            <a href="business?<?php echo $related->verify_string; ?>/<?php echo $related->state ?>/<?php echo $related->town ?>/<?php echo $related->name.".html"; ?>"
                                            class="btn">View Business</a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>

        <?php
            }
        ?>
    </main>

<?php include_layout_template("footer.php"); ?>\





desc addrwss

<?php
                                                    if($session->is_logged_in()){
                                                ?>
                                                        <a href="https://api.whatsapp.com/send?phone=<?php echo $realwhatsapp; ?>"
                                                        class="col-12 col-md-8 col-lg-10 mx-auto py-3 px-3 rounded text-white text-decoration-none" 
                                                        style='background-color:#00C853' target="_blank">
                                                            <center><span>Reach us on</span> <i class="bi bi-whatsapp fs-4 ms-2"></i></center>
                                                        </a>                                        
                                                <?php
                                                    } else {
                                                ?>
                                                        <a href="users/signup?page=<?php echo $url; ?>"
                                                        onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name ?> on WhatsApp')"
                                                        class="col-12 col-md-8 col-lg-10 mx-auto py-3 px-3 rounded text-white text-decoration-none" 
                                                        style='background-color:#00C853' target="_blank">
                                                            <center><span>Reach us on</span> <i class="bi bi-whatsapp fs-4 ms-2"></i></center>
                                                        </a>
                                                <?php
                                                    }    
                                                ?>


business.php =-------------------

<?php
    require_once("../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $exploding = !empty($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($exploding)){
        $explode = explode("/", $exploding);
        $string = array_shift($explode);
        
        $gurl = "fetch_business_by_string_api.php?string=".$string;
        $businesses = perform_get_curl($gurl);
        if($businesses){
            if($businesses->status == "success"){
                $business = $businesses->data;
                
                if($business->reg_stage >= 4){
                    if($session->is_logged_in()){
                        $user_string = $session->verify_string;
                    } else {
                        if(isset($_COOKIE['yenreach'])){
                            $user_string = $_COOKIE['yenreach'];
                        } else {
                            $gurl = "fetch_new_cookie_api.php";
                            $cookies = perform_get_curl($gurl);
                            if($cookies){
                                if($cookies->status == "success"){
                                    $cookie = $cookies->data;
                                    $user_string = $cookie->cookie;
                                    setcookie("yenreach", $user_string, time() + (86400 * 365), "/"); // 86400 = 1 day
                                } else {
                                    die($cookies->message);
                                }
                            } else {
                                die("Cookie Link Broken");
                            }
                        }
                    }
                    $purl = "create_page_visit_api.php";
                    $pdata = [
                            'business_string' => $business->verify_string,
                            'user_string' => $user_string
                        ];
                        
                    perform_post_curl($purl, $pdata);
                    
                    $cgurl = "fetch_business_categories_api.php?string=".$business->verify_string;
                    $categories = perform_get_curl($cgurl);
                    if($categories){
                        
                    } else {
                        die("Categories Link Broken");
                    }
                    
                    $pgurl = "fetch_business_public_photos_api.php?string=".$business->verify_string;
                    $photos = perform_get_curl($pgurl);
                    if($photos){
                        
                    } else {
                        die("Photos Link Broken");
                    }
                    
                    $vgurl = "fetch_business_public_videolinks_api.php?string=".$business->verify_string;
                    $links = perform_get_curl($vgurl);
                    if($links){
                        
                    } else {
                        die("Video Link Broken");
                    }
                    
                    $wgurl = "fetch_business_working_hours_api.php?string=".$business->verify_string;
                    $hours = perform_get_curl($wgurl);
                    if($hours){
                        
                    } else {
                        die("Working Hours Link Broken");
                    }
                    
                    $bgurl = "fetch_business_public_branches_api.php?string=".$business->verify_string;
                    $branches = perform_get_curl($bgurl);
                    if($branches){
                        
                    } else {
                        die("Branches Link Broken");
                    }
                    
                    $sgurl = "fetch_business_latest_subscription_api.php?string=".$business->verify_string;
                    $subs = perform_get_curl($sgurl);
                    if($subs){
                        
                    } else {
                        die("Subscriptions Link Broken");
                    }
                    
                    $rgurl = "fetch_related_businesses_api.php?string=".$business->verify_string;
                    $relateds = perform_get_curl($rgurl);
                    if($relateds->status == "success"){
                        
                    } else {
                        die("Related Businesses Link Broken");
                    }
                } else {
                    die("Business is not Approved");
                }
            } else {
                die($businesses->message);
            }
        } else {
            die("Businesses Link Broken");
        }
    } else {
        die("Wrong Path");
    }
?>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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
  fbq('track', 'Yenreach Portfolio Page');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1136570877108121&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    
    <title>Yenreach||<?php echo $business->name; ?></title>
    <meta content="<?php echo $business->description; ?>" name="description">
    <meta content="" name="keywords">
    
    <?php include_layout_template("links.php"); ?>
    <link rel="stylesheet" href="assets/lightbox/dist/css/lightbox.min.css">
    <style>
      body {
        overflow-x: hidden;
      }
      .bg-main {
        background-color: #083640;
      }
      .text-main {
        color: #083640;
      }
      .border-main {
        border: 1px solid #083640;
      }
      .desc-image {
        height: 20rem;
        width: 25rem;
      }
      .row p {
        font-size: 14px;
        /* text-justify: auto; */
      }
      .stretch-card > .card {
        width: 100%;
        min-width: 100%;
      }

      .flex {
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
      }
      .business-desc h1 p {
        text-align: justify;
      }
      .business-desc img {
        height: 100%;
        width: 100%;
      }
      
        .carded{
          background: #fff;
          margin: 5px;
          box-shadow: 0 5px 25px rgb(1 1 1 / 20%);
          border-radius: 5px;
          overflow: hidden;
        }
        
        .card-image{
          background-color: none;
          width: 100%;
          position: relative;
          padding-top: 100%;
        }
        
        .card-img {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-size:cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

      @media (max-width: 991.98px) {
        .padding {
          padding: 1.5rem;
        }
      }

      @media (max-width: 767.98px) {
        .padding {
          padding: 1rem;
        }
        .business-desc h1 {
          text-align: center;
        }
      }

      .owl-carousel .item {
        position: relative;
        margin: 3px;
        height: 20rem;
      }

      .owl-carousel .item img {
        display: block;
      }

      .owl-carousel .item {
        margin: 3px;
      }

      .owl-carousel {
        margin-bottom: 15px;
      }
      .facility {
        height: 50rem;
      }
      .content {
        height: 20rem;
        padding-bottom: 1rem;
      }
      .list {
        list-style: square !important;
        font-size: 0.95rem;
        padding: 5px 0;
      }

      .contact-info {
        height: 16rem;
      }

      .contact-info *,
      .contact-info p {
        font-size: 1rem;
      }
      .contact-info h2 {
        font-size: 1.7rem;
      }
      .social-media i {
        font-size: 1.1rem;
      }
      .recommended-container {
        height: 120vh;
        /* position: absolute;
        top: 70rem;
        right: 7vw; */
      }
      .recommended-section span {
        font-size: 1.3rem;
      }

      .review-container i {
        font-size: 30px;
        cursor: pointer;
        color: #e3e3e3;
      }
      .review-container i:hover {
        color: #e1ad01;
      }

      .card-item {
        height: 10rem;
        width: 20rem;
      }
      .video-controller {
        height: 24rem;
      }
      .image-container {
        height: 12rem;
        object-fit: contain;
      }
    </style>
</head>
<body>
    <?php include_layout_template("header.php"); ?>
    <main class="col-12 mx-auto">
        <div class="row">
            <div class="carousel-inner" style="height: 35vh; background: #f1f1f1">
                <div class="col-12 mx-auto d-flex h-75 mt-5 flex-column flex-lg-row justify-content-center align-items-center pt-lg-0">
                    <div class="col-4 mx-auto py-1">
                        <div class="media-center d-flex mx-auto align-items-center justify-content-center mb-4 mb-lg-0" style="width: 7rem">
                            <?php
                                if(!empty($business->filename)){
                                    if(file_exists("images/{$business->filename}.jpg")){
                            ?>
                                        <img
                                          src="images/<?php echo $business->filename.".jpg"; ?>"
                                          alt="<?php echo $business->name; ?>"
                                          width="100%"
                                          height="auto"
                                        />
                            <?php
                                    } else {
                            ?>
                                        <img
                                          src="assets/img/office_building.png"
                                          alt="<?php echo $business->name; ?>"
                                          width="100%"
                                          height="100%"
                                          style="border-radius: 50%"
                                        />
                            <?php
                                    }
                                } else {
                            ?>
                                    <img
                                      src="assets/img/office_building.png"
                                      alt="<?php echo $business->name; ?>"
                                      width="100%"
                                      height="100%"
                                      style="border-radius: 50%"
                                    />
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class='col-8'>
                        <h1 class="text-success font-weight-bold mb-4 text-center px-lg-3 fs-6-sm fs-6 ">
                            <?php echo $business->name; ?>
                            <?php echo output_message($message); ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="h-100 col-12 col-lg-11 mt-4 mx-auto d-flex align-items-center justify-content-between flex-wrap py-3">
                <div class="business-desc col-12 col-md-8 col-lg-6 mt-md-4 mx-md-auto py-3 text-center">
                    <h1 class="text-success mb-4 mb-md-2 fw-bold">Business Description</h1>
                    <p class="text-white text-wrap px-lg-2 text-justify mx-auto pr-0 text-muted">
                        <p id='para'><?php
                            $description_text = $business->description;
                            $desc_array = array();
                            $explode_desc = explode(' ', $business->description);
                            $explode_count = count($explode_desc);
                            if($explode_count >= 30){
                                for($i=0; $i<=30; $i++){
                                    $desc_array[] = $explode_desc[$i];
                                }
                            } else {
                                foreach($explode_desc as $desc){
                                    $desc_array[] = $desc;
                                }
                            }
                            echo join(' ', $desc_array)." ...";
                            $name_array = explode(' ', $business->name);
                            // $name = join('_', $name_array);
                            // echo nl2br($business->description);
                            if($categories->status == "success"){
                        ?></p>
                            <button onclick="readMore()" id="read" class= 'btn btn-success mb-4'>Read more</button>
                                <p>
                                    <?php
                                        foreach($categories->data as $category){
                                    ?>
                                            <span class="m-2">
                                                <a href="category?category=<?php echo urlencode($category->category); ?>" class="text-success">#<?php echo $category->category; ?></a>
                                            </span>
                                    <?php
                                        }
                                    ?>
                                </p>
                        <?php
                            }
                        ?>
                        <p>
                            <?php
                                if($session->is_logged_in()){
                                    $csgurl = "check_saved_business_api.php?user={$session->verify_string}&business={$business->verify_string}";
                                    $check_saved = perform_get_curl($csgurl);
                                    if($check_saved && $check_saved->status == "success"){
                            ?>
                                        <button class="btn btn-secondary" disabled>Added to Favourites</button>    
                            <?php
                                    } else {
                            ?>
                                        <a href="save_business?<?php echo $exploding; ?>" class="btn btn-primary">Add to Favourites</a> 
                            <?php
                                    }
                                } else {
                            ?>
                                    <a href="users/signup?page=<?php echo $url; ?>" class="btn btn-primary"
                                    onclick="return confirm('You have to Signup/Login to save this Business')">
                                        Save Business
                                    </a>
                            <?php
                                }
                            ?>
                                        
                        </p>
                    </p>
                </div>
            </div>
        </div>
        <?php
            if($photos->status == "success"){
        ?>
                <div class="row mx-auto mt-5">
                    <div class="card-content d-flex flex-wrap flex-lg-wrap justify-content-start align-items-start col-lg-8 mx-auto text-center">
                        <?php
                            foreach($photos->data as $photo){
                        ?>
                                <div class="col-12 col-lg-3 col-md-4 col-sm-6 col-xs-12 p-3">
                                    <a class="example-image-link" href="<?php echo $photo->filepath; ?>" data-lightbox="example-set">
                                        <div class="carded">
                                            <div class="card-image">
                                                <div class="card-img" style="background-image: url(<?php 
                                                    if(!empty($photo->filename)){
                                                        if(file_exists("images/thumbnails/{$photo->filename}.jpg")){
                                                            echo "images/thumbnails/{$photo->filename}.jpg";
                                                        } else {
                                                            echo $photo->filepath;
                                                        }
                                                    } else {
                                                        echo $photo->filepath;
                                                    }
                                                ?>)">&nbsp;</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>   
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <div class="row contact-container mx-auto py-5 mt-5">
            <hr/>
            <div class="col-12 col-lg-6 col-md-6 col-sm-12 d-flex flex-column justify-content-around">
                <div class="contact-info ps-4 mt-3 d-flex flex-column mb-5 mb-md-0 justify-content-around align-items-center" style="height: fit-content">
                    <h2 class="text-success text-center fs-2 fw-bold">Business Contact Details</h2>
                    <div class="phone-number d-flex flex-column align-items-start h-50 py-3 my-2 ">
                        <p class="text-main">
                            <i class="bi bi-geo me-2 py-2 rounded-md"></i>
                            <?php echo $business->address; ?>
                            <br />
                            <?php
                                if(!empty($business->town)){
                                    echo $business->town;
                                }
                                if(!empty($business->lga)){
                                    echo ", ".$business->lga." LGA";
                                }
                                if(!empty($business->state)){
                                    echo ", ".$business->state." State";
                                }
                            ?>
                        </p>
                        <?php
                            if($hours->status == "success"){
                        ?>
                                <table class="table">
                                    <tr>
                                        <td style="padding-left: 0 !important"><i class="bi bi-clock"></i></td>
                                        <td>
                                        <table class='table table-sm'>
                                            <thead >
                                                <tr>
                                                <th class='text-success' scope="col">Day</th>
                                                <th class='text-success' scope="col">Opens</th>
                                                <th class='text-success' scope="col">Closes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                function writeMsg($al){return substr($al,0,2);}
                                                
                                                foreach($hours->data as $hour){
                                            ?>    
                                                <tr>
                                                    <td class="px-2"><?php echo $hour->day; ?></td>
                                                    <td class="px-2"><?php
                                                        if(!empty($hour->opening_time)){
                                                            echo " ".$hour->opening_time."am";
                                                        } else {
                                                            echo $hour->timing;
                                                        }
                                                    ?></td>
                                                    <td class="px-2"><?php
                                                        if(!empty($hour->closing_time)){
                                                            $closing_time=strval(((int)writeMsg($hour->closing_time))-12);
                                                            echo $closing_time.":00pm";
                                                        } else {
                                                            echo $hour->timing;
                                                        }
                                                    ?></td>
                                                </tr>
                                            <?php
                                                }
                                            ?>
                                            </tbody>
                                        </table>
                                        </td>
                                    </tr>
                                </table>
                        <?php
                            }
                        ?>
                        <a href="tel:<?php echo $business->phonenumber ?>" class="text-main d-block py-2 rounded-md text-decoration-none ">
                            <i class="bi bi-telephone me-3"></i><?php echo $business->phonenumber ?>
                        </a>
                            
                        <a href="mailto:<?php echo $business->email; ?>" class="text-main py-2 rounded-md text-decoration-none">
                            <i class="bi bi-envelope me-3"></i><?php echo $business->email; ?>
                        </a>
                        <?php
                            if($subs->status == "success"){
                                $time = time();
                                $sub = $subs->data;
                                if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                                    $subgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                                    $subscriptions = perform_get_curl($subgurl);
                                    if($subscriptions && $subscriptions->status=="success"){
                                        $subscription = $subscriptions->data;
                                        if($subscription->socialmedia == 1){
                                            if(!empty($business->website)){
                                                if($session->is_logged_in()){
                        ?>
                        
                                                    <a href="<?php echo $business->website; ?>" class="text-main py-2 rounded-md text-decoration-none">
                                                        <i class="bi bi-globe me-3"></i><?php echo $business->website; ?>
                                                    </a>
                        <?php
                                                } else {
                                                    $webfirst = substr($business->website, 0, 10);
                                                    $weblast = substr($business->website, -4);
                                                    $website = $webfirst."****".$weblast;
                        ?>
                                                    <a href="users/signup?page=<?php echo $url; ?>" class="text-main py-2 rounded-md text-decoration-none"
                                                    onclick="return confirm('You have to Signup/Login to have access to this Business\' Website')">
                                                        <i class="bi bi-globe me-3"></i><?php echo $website; ?>
                                                    </a>
                        <?php
                                            
                                                }    
                                            }
                                        }
                                    }
                                }
                            }
                        ?>
                    </div>
                    <?php
                        if($subs->status == "success"){
                            $time = time();
                            $sub = $subs->data;
                            if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                                $subgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                                $subscriptions = perform_get_curl($subgurl);
                                if($subscriptions && $subscriptions->status=="success"){
                                    $subscription = $subscriptions->data;
                                    if($subscription->socialmedia == 1){
                                        if(!empty($business->whatsapp)){
                                            $whatsapp = trim($business->whatsapp);
                                            $number = substr($whatsapp, -10);
                                            $realwhatsapp = "+234".$number;
                    ?>
                                            <div class="address col-sm-10 mb-4 col-11 col-lg-4 d-flex align-items-center justify-content-center ">
                                                <?php
                                                    if($session->is_logged_in()){
                                                ?>
                                                        <a href="https://api.whatsapp.com/send?phone=<?php echo $realwhatsapp; ?>"
                                                        class="col-12 col-md-8 col-lg-10 mx-auto py-3 px-3 rounded text-white text-decoration-none" 
                                                        style='background-color:#00C853' target="_blank">
                                                            <center><span>Reach us on</span> <i class="bi bi-whatsapp fs-4 ms-2"></i></center>
                                                        </a>                                        
                                                <?php
                                                    } else {
                                                ?>
                                                        <a href="users/signup?page=<?php echo $url; ?>"
                                                        onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name ?> on WhatsApp')"
                                                        class="col-12 col-md-8 col-lg-10 mx-auto py-3 px-3 rounded text-white text-decoration-none" 
                                                        style='background-color:#00C853' target="_blank">
                                                            <center><span>Reach us on</span> <i class="bi bi-whatsapp fs-4 ms-2"></i></center>
                                                        </a>
                                                <?php
                                                    }    
                                                ?>
                                            </div>
                                            <div>
                                                 <span class='text-success'>Or you can reach us on social media.</span>
                                            </div>
                                            <div class="social-media col-12 col-lg-8 mx-auto d-flex align-items-center justify-content-between justify-content-lg-evenly mt-3">
                                                <?php
                                                    if(!empty($business->facebook_link)){
                                                ?>
                                                            <a href="<?php echo $business->facebook_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-primary text-white">
                                                                <i class="bi bi-facebook"></i>
                                                            </a>
                                                
                                                <?php
                                                    
                                                    }
                                                    if(!empty($business->twitter_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->twitter_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-secondary text-white">
                                                                <i class="bi bi-twitter"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-secondary text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on Twitter')">
                                                                <i class="bi bi-twitter"></i>
                                                            </a>
                                                <?php
                                                        }
                                                    }
                                                    if(!empty($business->instagram_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->instagram_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-warning text-white">
                                                                <i class="bi bi-instagram"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-warning text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on Instagram')">
                                                                <i class="bi bi-instagram"></i>
                                                            </a>
                                                <?php
                                                        }     
                                                    }
                                                    if(!empty($business->linkedin_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->linkedin_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-success text-white">
                                                                <i class="bi bi-linkedin"></i>
                                                            </a>                                                
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-success text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on LinkedIn')">
                                                                <i class="bi bi-linkedin"></i>
                                                            </a>
                                                <?php
                                                       }     
                                                    }
                                                    if(!empty($business->youtube_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->youtube_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-danger text-white">
                                                                <i class="bi bi-youtube"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-danger text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on YouTube')">
                                                                <i class="bi bi-youtube"></i>
                                                            </a>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </div>
                    <?php
                                        }
                                    }
                                }
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-md-6 col-sm-12 d-flex flex-column justify-content-around">
                <div class="contact-info ps-4 mt-3 d-flex flex-column mb-5 mb-md-0 justify-content-around align-items-center" style="height: fit-content">
                    <ul class="h-100 pb-4">
                        <h2 class="text-success text-center fs-2 fw-bold">Available Facilities</h2>
                        <div class="col-11 mx-auto d-flex align-items-center justify-content-center px-lg-4">
                            <div class="d-block me-5">
                                <?php
                                    if(!empty($business->facilities)){
                                        $strings = explode(",", $business->facilities);
                                        foreach($strings as $facil_string){
                                            $fgurl = "fetch_facility_by_string_api.php?string=".$facil_string;
                                            $facilities = perform_get_curl($fgurl);
                                            if(($facilities) && ($facilities->status == "success")){
                                                $facility = $facilities->data;
                                ?>
                                                <p class="list text-muted text-center"><?php echo $facility->facility; ?></p>
                                <?php
                                            }
                                        }
                                    } else {
                                        echo '<p>No Registered Facility for this Business</p>';
                                    }
                                ?>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <?php
            if($links->status == "success"){
        ?>
                <div class="row pt-2 mt-5">
                    <hr />
                    <h3 class="text-center text-success fw-bold fs-2">Business Videos</h3>
                    <div class="row p-5">
                        <?php
                            foreach($links->data as $link){
                        ?>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-3 mx-auto">
                                    <iframe src="<?php echo $link->video_link; ?>" title="YouTube video player" 
                                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen style="width: 100%; height: auto"></iframe>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <?php
            if($branches->status == "success"){
        ?>
                <div class="row pt-2 mt-5">
                    <hr />
                    <h3 class="text-center text-success fw-bold fs-2">Branches</h3>
                    <div class="row p-5">
                        <?php
                            foreach($branches->data as $branch){
                        ?>
                                <div class="col-lg-4 col-md-6 col-sm-12 mt-4 mx-auto">
                                    <div class="card" style="height: 350px; overflow: auto;">
                                        <div class="card-body text-center">
                                            <p class="text-center">
                                                <h4 class="text-success text-center"><small>Address</small></h4>
                                                <?php
                                                    echo $branch->address;
                                                    if(!empty($branch->town)){
                                                        echo "<br />".$branch->town;
                                                    }
                                                    if(!empty($branch->lga)){
                                                        echo  "<br />".$branch->lga." Local Gov't Area";
                                                    }
                                                    if(!empty($branch->state)){
                                                        echo "<br />".$branch->state.' State';
                                                    }
                                                    echo ".";
                                                ?>
                                            </p>
                                            <p class="text-center">
                                                <h4 class="text-success text-center"><small><?php echo $branch->head_designation; ?></small></h4>
                                                <?php echo $branch->head_name; ?>
                                            </p>
                                            <?php
                                                if(!empty($branch->phone) || !empty($branch->email)){
                                            ?>
                                                    
                                                    <?php
                                                        if($session->is_logged_in()){
                                                    ?>
                                                            <p>
                                                                <?php
                                                                    if(!empty($branch->phone)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-phone text-success"></i><span>
                                                                                <a href="tel:<?php echo $branch->phone ?>"><?php echo $branch->phone; ?></a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                    if(!empty($branch->email)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-envelope text-success"></i><span>
                                                                                <a href="mailto:<?php echo $branch->email; ?>"><?php echo $branch->email; ?></a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </p>
                                                    <?php
                                                        } else {
                                                            $bprefix = substr($branch->phone, 0, 4);
                                                            $bsuffix = substr($branch->phone, -3);
                                                            $bphone = $bprefix."****".$bsuffix;
                                                            
                                                            $bprestring = substr($branch->email, 0, 4);
                                                            $bpoststring = substr($branch->email, -3);
                                                            $bemail = $bprestring."****".$bpoststring;
                                                    ?>
                                                            <p>
                                                                <?php
                                                                    if(!empty($branch->phone)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-phone text-success"></i><span>
                                                                                <a href="users/signup?page=<?php echo $url; ?>"
                                                                                onclick="return confirm('You have to Signup/Login to have access to this Business\' Contact')">
                                                                                    <?php echo $bphone; ?>
                                                                                </a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                    if(!empty($branch->email)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-envelope text-success"></i><span>
                                                                                <a href="users/signup?page=<?php echo $url; ?>"
                                                                                onclick="return confirm('You have to Signup/Login to have access to this Business\' Contact')">
                                                                                    <?php echo $bemail; ?>
                                                                                </a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </p>
                                                    <?php
                                                        }
                                                    ?>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <div class="container" id="reviews_container">
            <hr />
            <div class="row" style="padding-top: 30px; padding-bottom: 30px">
                <h3 class="text-center text-success fw-bold fs-2">Reviews</h3>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <div class="row" id="review_summary">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <div class="row" id="reviews">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <input type="hidden" name="business_string" id="business_string" value="<?php echo $string; ?>" />
                        <?php
                            if($session->is_logged_in()){
                        ?>
                                <div class="form-group my-2">
                                    <textarea name="review" id="review_text" class="form-control" placeholder="You can drop your view for <?php echo $business->name; ?>" rows="7"></textarea>
                                </div>
                                <div class="form-group my-2 row" id="rating">
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="1" id="1" /><label for="1">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2"></i>
                                            <i class="bi bi-star-fill" id="3"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="2" id="2" /><label for="2">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="3" id="3" /><label for="3">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="4" id="4" /><label for="4">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="5" id="5" /><label for="5">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="5" style="color: #e1ad01"></i>
                                        </label>
                                    </p>
                                </div>
                                <div class="form-group my2">
                                    <button class="btn btn-block btn-success col-12" id="review_submit">Submit</button>
                                </div>
                        <?php
                            } else {
                        ?>
                                <div class="form-group text-center">
                                    <a href="users/signup?page=<?php echo $url."#reviews_container"; ?>" class="btn btn-success btn-block col-lg-6 mx-auto">Drop Review</a>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
            <hr />
        </div>
        <?php
            if($relateds->status == "success"){
        ?>
                <div class="container">
                    <div class="row" style="padding-top: 10px">
                        <h3 class="text-center text-success fw-bold fs-2">Recommended Businesses</h3>
                        <?php
                            foreach($relateds->data as $related){
                        ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 mb-3">
                                    <div class="card">
                                        <div class="card-image" loading="lazy">
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
                                        <div class='info-container'>
                                        <div class="card-info">
                                            <h6><small><?php echo $related->name; ?></small></h6>
                                        </div>
                                        <div class=' mx-2'>
                                                
                                            <?php
                                                if(!empty($related->categories)){
                                                    echo '<p>';
                                                    foreach($related->categories as $category){
                                                        echo '<a href="#" style="margin-top: 1px; font-size:12px;color:#00C853" class="m-2">#'.$category->category.'</a>';
                                                    }
                                                    echo '</p>';
                                                }
                                            ?>
        
                                        </div>
                                        <div class="ms-2 w-100">
                                            <a href="business?<?php echo $related->verify_string; ?>/<?php echo $related->state ?>/<?php echo $related->town ?>/<?php echo $related->name.".html"; ?>"
                                            class="btn">View Business</a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
    </main>
    <script>
        let text =<?php echo json_encode($description_text); ?>;
        let count = true;
        function readMore() {
        var para = document.getElementById("para");
        var btn = document.getElementById('read');
        const textList = text.split(' ');
        let disp = ``;
        if (count) {
            btn.innerHTML = 'Read Less';
            para.innerHTML = text; 
            count = false;
        } 
        else {
            btn.innerHTML = 'Read More';
            for (let i=0; i<31; i++) {
                disp +=  textList[i] + ' ';
            }
            para.innerHTML = disp + ' ...';
            count = true;
        }
        }
    </script>

<?php include_layout_template("footer.php"); ?>


index ---
<li><i class="bx bx-chevron-right"></i> <a href="subsidiaries#bbm">BYS Business Magazine</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="subsidiaries#de">Dordorian Estate</a></li>
          <li><i class="bx bx-chevron-right"></i> <a href="professional_courses">Professional Certification Programs</a></li>


          ----business.php
          <?php
    require_once("../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $exploding = !empty($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($exploding)){
        $explode = explode("/", $exploding);
        $string = array_shift($explode);
        
        $gurl = "fetch_business_by_string_api.php?string=".$string;
        $businesses = perform_get_curl($gurl);
        if($businesses){
            if($businesses->status == "success"){
                $business = $businesses->data;
                
                if($business->reg_stage >= 4){
                    if($session->is_logged_in()){
                        $user_string = $session->verify_string;
                    } else {
                        if(isset($_COOKIE['yenreach'])){
                            $user_string = $_COOKIE['yenreach'];
                        } else {
                            $gurl = "fetch_new_cookie_api.php";
                            $cookies = perform_get_curl($gurl);
                            if($cookies){
                                if($cookies->status == "success"){
                                    $cookie = $cookies->data;
                                    $user_string = $cookie->cookie;
                                    setcookie("yenreach", $user_string, time() + (86400 * 365), "/"); // 86400 = 1 day
                                } else {
                                    die($cookies->message);
                                }
                            } else {
                                die("Cookie Link Broken");
                            }
                        }
                    }
                    $purl = "create_page_visit_api.php";
                    $pdata = [
                            'business_string' => $business->verify_string,
                            'user_string' => $user_string
                        ];
                        
                    perform_post_curl($purl, $pdata);
                    
                    $cgurl = "fetch_business_categories_api.php?string=".$business->verify_string;
                    $categories = perform_get_curl($cgurl);
                    if($categories){
                        
                    } else {
                        die("Categories Link Broken");
                    }
                    
                    $pgurl = "fetch_business_public_photos_api.php?string=".$business->verify_string;
                    $photos = perform_get_curl($pgurl);
                    if($photos){
                        
                    } else {
                        die("Photos Link Broken");
                    }
                    
                    $vgurl = "fetch_business_public_videolinks_api.php?string=".$business->verify_string;
                    $links = perform_get_curl($vgurl);
                    if($links){
                        
                    } else {
                        die("Video Link Broken");
                    }
                    
                    $wgurl = "fetch_business_working_hours_api.php?string=".$business->verify_string;
                    $hours = perform_get_curl($wgurl);
                    if($hours){
                        
                    } else {
                        die("Working Hours Link Broken");
                    }
                    
                    $bgurl = "fetch_business_public_branches_api.php?string=".$business->verify_string;
                    $branches = perform_get_curl($bgurl);
                    if($branches){
                        
                    } else {
                        die("Branches Link Broken");
                    }
                    
                    $sgurl = "fetch_business_latest_subscription_api.php?string=".$business->verify_string;
                    $subs = perform_get_curl($sgurl);
                    if($subs){
                        
                    } else {
                        die("Subscriptions Link Broken");
                    }
                    
                    $rgurl = "fetch_related_businesses_api.php?string=".$business->verify_string;
                    $relateds = perform_get_curl($rgurl);
                    if($relateds->status == "success"){
                        
                    } else {
                        die("Related Businesses Link Broken");
                    }
                } else {
                    die("Business is not Approved");
                }
            } else {
                die($businesses->message);
            }
        } else {
            die("Businesses Link Broken");
        }
    } else {
        die("Wrong Path");
    }
?>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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
  fbq('track', 'Yenreach Portfolio Page');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1136570877108121&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    
    <title>Yenreach||<?php echo $business->name; ?></title>
    <meta content="<?php echo $business->description; ?>" name="description">
    <meta content="" name="keywords">
    
    <?php include_layout_template("links.php"); ?>
    <link rel="stylesheet" href="assets/lightbox/dist/css/lightbox.min.css">
    <style>
      body {
        overflow-x: hidden;
      }
      .bg-main {
        background-color: #083640;
      }
      .text-main {
        color: #083640;
      }
      .border-main {
        border: 1px solid #083640;
      }
      .desc-image {
        height: 20rem;
        width: 25rem;
      }
      .row p {
        font-size: 14px;
        /* text-justify: auto; */
      }
      .stretch-card > .card {
        width: 100%;
        min-width: 100%;
      }

      .flex {
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
      }
      .business-desc h1 p {
        text-align: justify;
      }
      .business-desc img {
        height: 100%;
        width: 100%;
      }
      
        .carded{
          background: #fff;
          margin: 5px;
          box-shadow: 0 5px 25px rgb(1 1 1 / 20%);
          border-radius: 5px;
          overflow: hidden;
        }
        
        .card-image{
          background-color: none;
          width: 100%;
          position: relative;
          padding-top: 100%;
        }
        
        .card-img {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-size:cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

      @media (max-width: 991.98px) {
        .padding {
          padding: 1.5rem;
        }
      }

      @media (max-width: 767.98px) {
        .padding {
          padding: 1rem;
        }
        .business-desc h1 {
          text-align: center;
        }
      }

      .owl-carousel .item {
        position: relative;
        margin: 3px;
        height: 20rem;
      }

      .owl-carousel .item img {
        display: block;
      }

      .owl-carousel .item {
        margin: 3px;
      }

      .owl-carousel {
        margin-bottom: 15px;
      }
      .facility {
        height: 50rem;
      }
      .content {
        height: 20rem;
        padding-bottom: 1rem;
      }
      .list {
        list-style: square !important;
        font-size: 0.95rem;
        padding: 5px 0;
      }

      .contact-info {
        height: 16rem;
      }

      .contact-info *,
      .contact-info p {
        font-size: 1rem;
      }
      .contact-info h2 {
        font-size: 1.7rem;
      }
      .social-media i {
        font-size: 1.1rem;
      }
      .recommended-container {
        height: 120vh;
        /* position: absolute;
        top: 70rem;
        right: 7vw; */
      }
      .recommended-section span {
        font-size: 1.3rem;
      }

      .review-container i {
        font-size: 30px;
        cursor: pointer;
        color: #e3e3e3;
      }
      .review-container i:hover {
        color: #e1ad01;
      }

      .card-item {
        height: 10rem;
        width: 20rem;
      }
      .video-controller {
        height: 24rem;
      }
      .image-container {
        height: 12rem;
        object-fit: contain;
      }
    </style>
</head>
<body>
    <?php include_layout_template("header.php"); ?>
    <main class="col-12 mx-auto">
        <div class="row">
            <div class="carousel-inner" style="height: 35vh; background: #f1f1f1">
                <div class="col-12 mx-auto d-flex h-75 mt-5 flex-column flex-lg-row justify-content-center align-items-center pt-lg-0">
                    <div class="col-4 mx-auto py-1">
                        <div class="media-center d-flex mx-auto align-items-center justify-content-center mb-4 mb-lg-0" style="width: 7rem">
                            <?php
                                if(!empty($business->filename)){
                                    if(file_exists("images/{$business->filename}.jpg")){
                            ?>
                                        <img
                                          src="images/<?php echo $business->filename.".jpg"; ?>"
                                          alt="<?php echo $business->name; ?>"
                                          width="100%"
                                          height="auto"
                                        />
                            <?php
                                    } else {
                            ?>
                                        <img
                                          src="assets/img/office_building.png"
                                          alt="<?php echo $business->name; ?>"
                                          width="100%"
                                          height="100%"
                                          style="border-radius: 50%"
                                        />
                            <?php
                                    }
                                } else {
                            ?>
                                    <img
                                      src="assets/img/office_building.png"
                                      alt="<?php echo $business->name; ?>"
                                      width="100%"
                                      height="100%"
                                      style="border-radius: 50%"
                                    />
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class='col-8'>
                        <h1 class="text-success font-weight-bold mb-4 text-center px-lg-3 fs-6-sm fs-6 ">
                            <?php echo $business->name; ?>
                            <?php echo output_message($message); ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="h-100 col-12 col-lg-11 mt-4 mx-auto d-flex align-items-center justify-content-between flex-wrap py-3">
                <div class="business-desc col-12 col-md-8 col-lg-6 mt-md-4 mx-md-auto py-3 text-center">
                    <h1 class="text-success mb-4 mb-md-2 fw-bold">Business Description</h1>
                    <p class="text-white text-wrap px-lg-2 text-justify mx-auto pr-0 text-muted">
                        <p id='para'><?php
                            $description_text = $business->description;
                            $desc_array = array();
                            $explode_desc = explode(' ', $business->description);
                            $explode_count = count($explode_desc);
                            if($explode_count >= 30){
                                for($i=0; $i<=30; $i++){
                                    $desc_array[] = $explode_desc[$i];
                                }
                            } else {
                                foreach($explode_desc as $desc){
                                    $desc_array[] = $desc;
                                }
                            }
                            echo join(' ', $desc_array)." ...";
                            $name_array = explode(' ', $business->name);
                            // $name = join('_', $name_array);
                            // echo nl2br($business->description);
                            if($categories->status == "success"){
                        ?></p>
                            <button onclick="readMore()" id="read" class= 'btn btn-success mb-4'>Read more</button>
                                <p>
                                    <?php
                                        foreach($categories->data as $category){
                                    ?>
                                            <span class="m-2">
                                                <a href="category?category=<?php echo urlencode($category->category); ?>" class="text-success">#<?php echo $category->category; ?></a>
                                            </span>
                                    <?php
                                        }
                                    ?>
                                </p>
                        <?php
                            }
                        ?>
                        <p>
                            <?php
                                if($session->is_logged_in()){
                                    $csgurl = "check_saved_business_api.php?user={$session->verify_string}&business={$business->verify_string}";
                                    $check_saved = perform_get_curl($csgurl);
                                    if($check_saved && $check_saved->status == "success"){
                            ?>
                                        <button class="btn btn-secondary" disabled>Added to Favourites</button>    
                            <?php
                                    } else {
                            ?>
                                        <a href="save_business?<?php echo $exploding; ?>" class="btn btn-primary">Add to Favourites</a> 
                            <?php
                                    }
                                } else {
                            ?>
                                    <a href="users/signup?page=<?php echo $url; ?>" class="btn btn-primary"
                                    onclick="return confirm('You have to Signup/Login to save this Business')">
                                        Save Business
                                    </a>
                            <?php
                                }
                            ?>
                                        
                        </p>
                    </p>
                </div>
            </div>
        </div>
        <?php
            if($photos->status == "success"){
        ?>
                <div class="row mx-auto mt-5">
                    <div class="card-content d-flex flex-wrap flex-lg-wrap justify-content-start align-items-start col-lg-8 mx-auto text-center">
                        <?php
                            foreach($photos->data as $photo){
                        ?>
                                <div class="col-12 col-lg-3 col-md-4 col-sm-6 col-xs-12 p-3">
                                    <a class="example-image-link" href="<?php echo $photo->filepath; ?>" data-lightbox="example-set">
                                        <div class="carded">
                                            <div class="card-image">
                                                <div class="card-img" style="background-image: url(<?php 
                                                    if(!empty($photo->filename)){
                                                        if(file_exists("images/thumbnails/{$photo->filename}.jpg")){
                                                            echo "images/thumbnails/{$photo->filename}.jpg";
                                                        } else {
                                                            echo $photo->filepath;
                                                        }
                                                    } else {
                                                        echo $photo->filepath;
                                                    }
                                                ?>)">&nbsp;</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>   
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <div class="row contact-container mx-auto mt-5">
            <hr/>
            <div class="col-12 col-lg-6 col-md-6 col-sm-12 d-flex flex-column justify-content-around">
                <div class="contact-info d-flex flex-column mb-5 mb-md-0 justify-content-around align-items-center" style="height: fit-content">
                    <h2 class="text-success text-center fs-2 fw-bold">Business Contact Details</h2>
                    <div class="phone-number d-flex flex-column align-items-start py-3 my-2 ">
                        <p class="text-main">
                            <i class="bi bi-geo me-2 py-2 rounded-md"></i>
                            <?php echo $business->address; ?>
                            <br />
                            <?php
                                if(!empty($business->town)){
                                    echo $business->town;
                                }
                                if(!empty($business->lga)){
                                    echo ", ".$business->lga." LGA";
                                }
                                if(!empty($business->state)){
                                    echo ", ".$business->state." State";
                                }
                            ?>
                        </p>
                        <?php
                            if($hours->status == "success"){
                        ?>
                                <table class="table">
                                    <tr>
                                        <td style="padding-left: 0 !important"><i class="bi bi-clock"></i></td>
                                        <td>
                                        <table class='table table-sm'>
                                            <thead >
                                                <tr>
                                                <th class='text-success' scope="col">Day</th>
                                                <th class='text-success' scope="col">Opens</th>
                                                <th class='text-success' scope="col">Closes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                function writeMsg($al){return substr($al,0,2);}
                                                
                                                foreach($hours->data as $hour){
                                            ?>    
                                                <tr>
                                                    <td class="px-2"><?php echo $hour->day; ?></td>
                                                    <td class="px-2"><?php
                                                        if(!empty($hour->opening_time)){
                                                            echo " ".$hour->opening_time."am";
                                                        } else {
                                                            echo $hour->timing;
                                                        }
                                                    ?></td>
                                                    <td class="px-2"><?php
                                                        if(!empty($hour->closing_time)){
                                                            $closing_time=strval(((int)writeMsg($hour->closing_time))-12);
                                                            echo $closing_time.":00pm";
                                                        } else {
                                                            echo $hour->timing;
                                                        }
                                                    ?></td>
                                                </tr>
                                            <?php
                                                }
                                            ?>
                                            </tbody>
                                        </table>
                                        </td>
                                    </tr>
                                </table>
                        <?php
                            }
                        ?>
                        <a href="tel:<?php echo $business->phonenumber ?>" class="text-main d-block py-2 rounded-md text-decoration-none ">
                            <i class="bi bi-telephone me-3"></i><?php echo $business->phonenumber ?>
                        </a>
                            
                        <a href="mailto:<?php echo $business->email; ?>" class="text-main py-2 rounded-md text-decoration-none">
                            <i class="bi bi-envelope me-3"></i><?php echo $business->email; ?>
                        </a>
                        <?php
                            if($subs->status == "success"){
                                $time = time();
                                $sub = $subs->data;
                                if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                                    $subgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                                    $subscriptions = perform_get_curl($subgurl);
                                    if($subscriptions && $subscriptions->status=="success"){
                                        $subscription = $subscriptions->data;
                                        if($subscription->socialmedia == 1){
                                            if(!empty($business->website)){
                                                if($session->is_logged_in()){
                        ?>
                        
                                                    <a href="<?php echo $business->website; ?>" class="text-main py-2 rounded-md text-decoration-none">
                                                        <i class="bi bi-globe me-3"></i><?php echo $business->website; ?>
                                                    </a>
                        <?php
                                                } else {
                                                    $webfirst = substr($business->website, 0, 10);
                                                    $weblast = substr($business->website, -4);
                                                    $website = $webfirst."****".$weblast;
                        ?>
                                                    <a href="users/signup?page=<?php echo $url; ?>" class="text-main py-2 rounded-md text-decoration-none"
                                                    onclick="return confirm('You have to Signup/Login to have access to this Business\' Website')">
                                                        <i class="bi bi-globe me-3"></i><?php echo $website; ?>
                                                    </a>
                        <?php
                                            
                                                }    
                                            }
                                        }
                                    }
                                }
                            }
                        ?>
                    </div>
                    <?php
                        if($subs->status == "success"){
                            $time = time();
                            $sub = $subs->data;
                            if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                                $subgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                                $subscriptions = perform_get_curl($subgurl);
                                if($subscriptions && $subscriptions->status=="success"){
                                    $subscription = $subscriptions->data;
                                    if($subscription->socialmedia == 1){
                                        if(!empty($business->whatsapp)){
                                            $whatsapp = trim($business->whatsapp);
                                            $number = substr($whatsapp, -10);
                                            $realwhatsapp = "+234".$number;
                    ?>
                                            <div class="address col-sm-10 mb-4 col-11 col-lg-4 d-flex align-items-center justify-content-center ">
                                                <?php
                                                    if($session->is_logged_in()){
                                                ?>
                                                        <a href="https://api.whatsapp.com/send?phone=<?php echo $realwhatsapp; ?>"
                                                        class="col-12 col-md-8 col-lg-10 mx-auto py-3 px-3 rounded text-white text-decoration-none" 
                                                        style='background-color:#00C853' target="_blank">
                                                            <center><span>Reach us on</span> <i class="bi bi-whatsapp fs-4 ms-2"></i></center>
                                                        </a>                                        
                                                <?php
                                                    } else {
                                                ?>
                                                        <a href="users/signup?page=<?php echo $url; ?>"
                                                        onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name ?> on WhatsApp')"
                                                        class="col-12 col-md-8 col-lg-10 mx-auto py-3 px-3 rounded text-white text-decoration-none" 
                                                        style='background-color:#00C853' target="_blank">
                                                            <center><span>Reach us on</span> <i class="bi bi-whatsapp fs-4 ms-2"></i></center>
                                                        </a>
                                                <?php
                                                    }    
                                                ?>
                                            </div>
                                            <div>
                                                <span class='text-success'>Or you can reach us on social media.</span>
                                            </div>
                                            <div class="social-media col-12 col-lg-8 mx-auto d-flex align-items-center justify-content-between justify-content-lg-evenly mt-3">
                                                <?php
                                                    if(!empty($business->facebook_link)){
                                                ?>
                                                            <a href="<?php echo $business->facebook_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-primary text-white">
                                                                <i class="bi bi-facebook"></i>
                                                            </a>
                                                
                                                <?php
                                                    
                                                    }
                                                    if(!empty($business->twitter_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->twitter_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-secondary text-white">
                                                                <i class="bi bi-twitter"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-secondary text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on Twitter')">
                                                                <i class="bi bi-twitter"></i>
                                                            </a>
                                                <?php
                                                        }
                                                    }
                                                    if(!empty($business->instagram_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->instagram_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-warning text-white">
                                                                <i class="bi bi-instagram"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-warning text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on Instagram')">
                                                                <i class="bi bi-instagram"></i>
                                                            </a>
                                                <?php
                                                        }     
                                                    }
                                                    if(!empty($business->linkedin_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->linkedin_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-success text-white">
                                                                <i class="bi bi-linkedin"></i>
                                                            </a>                                                
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-success text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on LinkedIn')">
                                                                <i class="bi bi-linkedin"></i>
                                                            </a>
                                                <?php
                                                       }     
                                                    }
                                                    if(!empty($business->youtube_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->youtube_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-danger text-white">
                                                                <i class="bi bi-youtube"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-danger text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on YouTube')">
                                                                <i class="bi bi-youtube"></i>
                                                            </a>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </div>
                    <?php
                                        }
                                    }
                                }
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-md-6 col-sm-12 d-flex flex-column">
                <div class="contact-info d-flex flex-column mb-5 mb-md-0 align-items-center" style="height: fit-content">
                    <ul class="p-0 m-0">
                        <h2 class="text-success text-center fs-2 fw-bold">Available Facilities</h2>
                        <div class="d-flex align-items-center justify-content-center text-center">
                            <div class="d-block">
                                <?php
                                    if(!empty($business->facilities)){
                                        $strings = explode(",", $business->facilities);
                                        foreach($strings as $facil_string){
                                            $fgurl = "fetch_facility_by_string_api.php?string=".$facil_string;
                                            $facilities = perform_get_curl($fgurl);
                                            if(($facilities) && ($facilities->status == "success")){
                                                $facility = $facilities->data;
                                ?>
                                                <p class="list text-muted text-center"><?php echo $facility->facility; ?></p>
                                <?php
                                            }
                                        }
                                    } else {
                                        echo '<p>No Registered Facility for this Business</p>';
                                    }
                                ?>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <?php
            if($links->status == "success"){
        ?>
                <div class="row pt-2 mt-5">
                    <hr />
                    <h3 class="text-center text-success fw-bold fs-2">Business Videos</h3>
                    <div class="row p-5">
                        <?php
                            foreach($links->data as $link){
                        ?>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-3 mx-auto">
                                    <iframe src="<?php echo $link->video_link; ?>" title="YouTube video player" 
                                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen style="width: 100%; height: auto"></iframe>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <?php
            if($branches->status == "success"){
        ?>
                <div class="row pt-2 mt-5">
                    <hr />
                    <h3 class="text-center text-success fw-bold fs-2">Branches</h3>
                    <div class="row p-5">
                        <?php
                            foreach($branches->data as $branch){
                        ?>
                                <div class="col-lg-4 col-md-6 col-sm-12 mt-4 mx-auto">
                                    <div class="card" style="height: 350px; overflow: auto;">
                                        <div class="card-body text-center">
                                            <p class="text-center">
                                                <h4 class="text-success text-center"><small>Address</small></h4>
                                                <?php
                                                    echo $branch->address;
                                                    if(!empty($branch->town)){
                                                        echo "<br />".$branch->town;
                                                    }
                                                    if(!empty($branch->lga)){
                                                        echo  "<br />".$branch->lga." Local Gov't Area";
                                                    }
                                                    if(!empty($branch->state)){
                                                        echo "<br />".$branch->state.' State';
                                                    }
                                                    echo ".";
                                                ?>
                                            </p>
                                            <p class="text-center">
                                                <h4 class="text-success text-center"><small><?php echo $branch->head_designation; ?></small></h4>
                                                <?php echo $branch->head_name; ?>
                                            </p>
                                            <?php
                                                if(!empty($branch->phone) || !empty($branch->email)){
                                            ?>
                                                    
                                                    <?php
                                                        if($session->is_logged_in()){
                                                    ?>
                                                            <p>
                                                                <?php
                                                                    if(!empty($branch->phone)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-phone text-success"></i><span>
                                                                                <a href="tel:<?php echo $branch->phone ?>"><?php echo $branch->phone; ?></a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                    if(!empty($branch->email)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-envelope text-success"></i><span>
                                                                                <a href="mailto:<?php echo $branch->email; ?>"><?php echo $branch->email; ?></a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </p>
                                                    <?php
                                                        } else {
                                                            $bprefix = substr($branch->phone, 0, 4);
                                                            $bsuffix = substr($branch->phone, -3);
                                                            $bphone = $bprefix."****".$bsuffix;
                                                            
                                                            $bprestring = substr($branch->email, 0, 4);
                                                            $bpoststring = substr($branch->email, -3);
                                                            $bemail = $bprestring."****".$bpoststring;
                                                    ?>
                                                            <p>
                                                                <?php
                                                                    if(!empty($branch->phone)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-phone text-success"></i><span>
                                                                                <a href="users/signup?page=<?php echo $url; ?>"
                                                                                onclick="return confirm('You have to Signup/Login to have access to this Business\' Contact')">
                                                                                    <?php echo $bphone; ?>
                                                                                </a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                    if(!empty($branch->email)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-envelope text-success"></i><span>
                                                                                <a href="users/signup?page=<?php echo $url; ?>"
                                                                                onclick="return confirm('You have to Signup/Login to have access to this Business\' Contact')">
                                                                                    <?php echo $bemail; ?>
                                                                                </a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </p>
                                                    <?php
                                                        }
                                                    ?>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <div class="container" id="reviews_container">
            <hr />
            <div class="row" style="padding-top: 30px; padding-bottom: 30px">
                <h3 class="text-center text-success fw-bold fs-2">Reviews</h3>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <div class="row" id="review_summary">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <div class="row" id="reviews">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <input type="hidden" name="business_string" id="business_string" value="<?php echo $string; ?>" />
                        <?php
                            if($session->is_logged_in()){
                        ?>
                                <div class="form-group my-2">
                                    <textarea name="review" id="review_text" class="form-control" placeholder="You can drop your view for <?php echo $business->name; ?>" rows="7"></textarea>
                                </div>
                                <div class="form-group my-2 row" id="rating">
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="1" id="1" /><label for="1">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2"></i>
                                            <i class="bi bi-star-fill" id="3"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="2" id="2" /><label for="2">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="3" id="3" /><label for="3">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="4" id="4" /><label for="4">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="5" id="5" /><label for="5">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="5" style="color: #e1ad01"></i>
                                        </label>
                                    </p>
                                </div>
                                <div class="form-group my2">
                                    <button class="btn btn-block btn-success col-12" id="review_submit">Submit</button>
                                </div>
                        <?php
                            } else {
                        ?>
                                <div class="form-group text-center">
                                    <a href="users/signup?page=<?php echo $url."#reviews_container"; ?>" class="btn btn-success btn-block col-lg-6 mx-auto">Drop Review</a>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
            <hr />
        </div>
        <?php
            if($relateds->status == "success"){
        ?>
                <div class="container">
                    <div class="row" style="padding-top: 10px">
                        <h3 class="text-center text-success fw-bold fs-2">Recommended Businesses</h3>
                        <?php
                            foreach($relateds->data as $related){
                        ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 mb-3">
                                    <div class="card">
                                        <div class="card-image" loading="lazy">
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
                                        <div class='info-container'>
                                        <div class="card-info">
                                            <h6><small><?php echo $related->name; ?></small></h6>
                                        </div>
                                        <div class=' mx-2'>
                                                
                                            <?php
                                                if(!empty($related->categories)){
                                                    echo '<p>';
                                                    foreach($related->categories as $category){
                                                        echo '<a href="#" style="margin-top: 1px; font-size:12px;color:#00C853" class="m-2">#'.$category->category.'</a>';
                                                    }
                                                    echo '</p>';
                                                }
                                            ?>
        
                                        </div>
                                        <div class="ms-2 w-100">
                                            <a href="business?<?php echo $related->verify_string; ?>/<?php echo $related->state ?>/<?php echo $related->town ?>/<?php echo $related->name.".html"; ?>"
                                            class="btn">View Business</a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
    </main>
    <script>
        let text =<?php echo json_encode($description_text); ?>;
        let count = true;
        function readMore() {
        var para = document.getElementById("para");
        var btn = document.getElementById('read');
        const textList = text.split(' ');
        let disp = ``;
        if (count) {
            btn.innerHTML = 'Read Less';
            para.innerHTML = text; 
            count = false;
        } 
        else {
            btn.innerHTML = 'Read More';
            for (let i=0; i<31; i++) {
                disp +=  textList[i] + ' ';
            }
            para.innerHTML = disp + ' ...';
            count = true;
        }
        }
    </script>

<?php include_layout_template("footer.php"); ?>

<?php
    require_once("../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $exploding = !empty($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($exploding)){
        $explode = explode("/", $exploding);
        $string = array_shift($explode);
        
        $gurl = "fetch_business_by_string_api.php?string=".$string;
        $businesses = perform_get_curl($gurl);
        if($businesses){
            if($businesses->status == "success"){
                $business = $businesses->data;
                
                if($business->reg_stage >= 4){
                    if($session->is_logged_in()){
                        $user_string = $session->verify_string;
                    } else {
                        if(isset($_COOKIE['yenreach'])){
                            $user_string = $_COOKIE['yenreach'];
                        } else {
                            $gurl = "fetch_new_cookie_api.php";
                            $cookies = perform_get_curl($gurl);
                            if($cookies){
                                if($cookies->status == "success"){
                                    $cookie = $cookies->data;
                                    $user_string = $cookie->cookie;
                                    setcookie("yenreach", $user_string, time() + (86400 * 365), "/"); // 86400 = 1 day
                                } else {
                                    die($cookies->message);
                                }
                            } else {
                                die("Cookie Link Broken");
                            }
                        }
                    }
                    $purl = "create_page_visit_api.php";
                    $pdata = [
                            'business_string' => $business->verify_string,
                            'user_string' => $user_string
                        ];
                        
                    perform_post_curl($purl, $pdata);
                    
                    $cgurl = "fetch_business_categories_api.php?string=".$business->verify_string;
                    $categories = perform_get_curl($cgurl);
                    if($categories){
                        
                    } else {
                        die("Categories Link Broken");
                    }
                    
                    $pgurl = "fetch_business_public_photos_api.php?string=".$business->verify_string;
                    $photos = perform_get_curl($pgurl);
                    if($photos){
                        
                    } else {
                        die("Photos Link Broken");
                    }
                    
                    $vgurl = "fetch_business_public_videolinks_api.php?string=".$business->verify_string;
                    $links = perform_get_curl($vgurl);
                    if($links){
                        
                    } else {
                        die("Video Link Broken");
                    }
                    
                    $wgurl = "fetch_business_working_hours_api.php?string=".$business->verify_string;
                    $hours = perform_get_curl($wgurl);
                    if($hours){
                        
                    } else {
                        die("Working Hours Link Broken");
                    }
                    
                    $bgurl = "fetch_business_public_branches_api.php?string=".$business->verify_string;
                    $branches = perform_get_curl($bgurl);
                    if($branches){
                        
                    } else {
                        die("Branches Link Broken");
                    }
                    
                    $sgurl = "fetch_business_latest_subscription_api.php?string=".$business->verify_string;
                    $subs = perform_get_curl($sgurl);
                    if($subs){
                        
                    } else {
                        die("Subscriptions Link Broken");
                    }
                    
                    $rgurl = "fetch_related_businesses_api.php?string=".$business->verify_string;
                    $relateds = perform_get_curl($rgurl);
                    if($relateds->status == "success"){
                        
                    } else {
                        die("Related Businesses Link Broken");
                    }
                } else {
                    die("Business is not Approved");
                }
            } else {
                die($businesses->message);
            }
        } else {
            die("Businesses Link Broken");
        }
    } else {
        die("Wrong Path");
    }
?>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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
  fbq('track', 'Yenreach Portfolio Page');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1136570877108121&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    
    <title>Yenreach||<?php echo $business->name; ?></title>
    <meta content="<?php echo $business->description; ?>" name="description">
    <meta content="" name="keywords">
    
    <?php include_layout_template("links.php"); ?>
    <link rel="stylesheet" href="assets/lightbox/dist/css/lightbox.min.css">
    <style>
      body {
        overflow-x: hidden;
      }
      .bg-main {
        background-color: #083640;
      }
      .text-main {
        color: #083640;
      }
      .border-main {
        border: 1px solid #083640;
      }
      .desc-image {
        height: 20rem;
        width: 25rem;
      }
      .row p {
        font-size: 14px;
        /* text-justify: auto; */
      }
      .stretch-card > .card {
        width: 100%;
        min-width: 100%;
      }

      .flex {
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
      }
      .business-desc h1 p {
        text-align: justify;
      }
      .business-desc img {
        height: 100%;
        width: 100%;
      }
      
        .carded{
          background: #fff;
          margin: 5px;
          box-shadow: 0 5px 25px rgb(1 1 1 / 20%);
          border-radius: 5px;
          overflow: hidden;
        }
        
        .card-image{
          background-color: none;
          width: 100%;
          position: relative;
          padding-top: 100%;
        }
        
        .card-img {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-size:cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

      @media (max-width: 991.98px) {
        .padding {
          padding: 1.5rem;
        }
      }

      @media (max-width: 767.98px) {
        .padding {
          padding: 1rem;
        }
        .business-desc h1 {
          text-align: center;
        }
      }

      .owl-carousel .item {
        position: relative;
        margin: 3px;
        height: 20rem;
      }

      .owl-carousel .item img {
        display: block;
      }

      .owl-carousel .item {
        margin: 3px;
      }

      .owl-carousel {
        margin-bottom: 15px;
      }
      .facility {
        height: 50rem;
      }
      .content {
        height: 20rem;
        padding-bottom: 1rem;
      }
      .list {
        list-style: square !important;
        font-size: 0.95rem;
        padding: 5px 0;
      }

      .contact-info {
        height: 16rem;
      }

      .contact-info *,
      .contact-info p {
        font-size: 1rem;
      }
      .contact-info h2 {
        font-size: 1.7rem;
      }
      .social-media i {
        font-size: 1.1rem;
      }
      .recommended-container {
        height: 120vh;
        /* position: absolute;
        top: 70rem;
        right: 7vw; */
      }
      .recommended-section span {
        font-size: 1.3rem;
      }

      .review-container i {
        font-size: 30px;
        cursor: pointer;
        color: #e3e3e3;
      }
      .review-container i:hover {
        color: #e1ad01;
      }

      .card-item {
        height: 10rem;
        width: 20rem;
      }
      .video-controller {
        height: 24rem;
      }
      .image-container {
        height: 12rem;
        object-fit: contain;
      }
    </style>
</head>
<body>
    <?php include_layout_template("header.php"); ?>
    <main class="col-12 mx-auto" style="margin-top:6rem">
        <div class="" style="background: #f1f1f1">
            <div class="container py-4 d-flex-md justify-content-center align-items-center" style="background: #f1f1f1">
                <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-between align-items-center pt-lg-0">
                    <div class="">
                        <div class="media-center d-flex mx-auto align-items-center justify-content-center mb-4 mb-md-0" style="width: 7rem">
                            <?php
                                if(!empty($business->filename)){
                                    if(file_exists("images/{$business->filename}.jpg")){
                            ?>
                                        <img
                                          src="images/<?php echo $business->filename.".jpg"; ?>"
                                          alt="<?php echo $business->name; ?>"
                                          width="100%"
                                          height="auto"
                                        />
                            <?php
                                    } else {
                            ?>
                                        <img
                                          src="assets/img/office_building.png"
                                          alt="<?php echo $business->name; ?>"
                                          width="100%"
                                          height="100%"
                                          style="border-radius: 50%"
                                        />
                            <?php
                                    }
                                } else {
                            ?>
                                    <img
                                      src="assets/img/office_building.png"
                                      alt="<?php echo $business->name; ?>"
                                      width="100%"
                                      height="100%"
                                      style="border-radius: 50%"
                                    />
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class=''>
                        <h1 class="text-success font-weight-bold mb-4 mb-md-0 text-center fs-6-sm fs-6 ">
                            <?php echo $business->name; ?>
                            <?php echo output_message($message); ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mx-auto">
            <div class="h-100 col-12 col-lg-11 mt-4 mx-auto d-flex align-items-center justify-content-between flex-wrap py-3">
                <div class="business-desc col-12 col-md-8 col-lg-6 mt-md-4 mx-md-auto py-3 text-center">
                    <h1 class="text-success mb-4 mb-md-2 fw-bold">Business Description</h1>
                    <p class="text-white text-wrap px-lg-2 text-justify mx-auto pr-0 text-muted">
                        <p id='para'><?php
                            $description_text = $business->description;
                            $desc_array = array();
                            $explode_desc = explode(' ', $business->description);
                            $explode_count = count($explode_desc);
                            if($explode_count >= 30){
                                for($i=0; $i<=30; $i++){
                                    $desc_array[] = $explode_desc[$i];
                                }
                            } else {
                                foreach($explode_desc as $desc){
                                    $desc_array[] = $desc;
                                }
                            }
                            echo join(' ', $desc_array)." ...";
                            $name_array = explode(' ', $business->name);
                            // $name = join('_', $name_array);
                            // echo nl2br($business->description);
                            if($categories->status == "success"){
                        ?></p>
                            <button onclick="readMore()" id="read" class= 'btn btn-success mb-4'>Read more</button>
                                <p>
                                    <?php
                                        foreach($categories->data as $category){
                                    ?>
                                            <span class="m-2">
                                                <a href="category?category=<?php echo urlencode($category->category); ?>" class="text-success">#<?php echo $category->category; ?></a>
                                            </span>
                                    <?php
                                        }
                                    ?>
                                </p>
                        <?php
                            }
                        ?>
                        <p>
                            <?php
                                if($session->is_logged_in()){
                                    $csgurl = "check_saved_business_api.php?user={$session->verify_string}&business={$business->verify_string}";
                                    $check_saved = perform_get_curl($csgurl);
                                    if($check_saved && $check_saved->status == "success"){
                            ?>
                                        <button class="btn btn-secondary" disabled>Added to Favourites</button>    
                            <?php
                                    } else {
                            ?>
                                        <a href="save_business?<?php echo $exploding; ?>" class="btn btn-primary">Add to Favourites</a> 
                            <?php
                                    }
                                } else {
                            ?>
                                    <a href="users/signup?page=<?php echo $url; ?>" class="btn btn-primary"
                                    onclick="return confirm('You have to Signup/Login to save this Business')">
                                        Save Business
                                    </a>
                            <?php
                                }
                            ?>
                                        
                        </p>
                    </p>
                </div>
            </div>
        </div>
        <?php
            if($photos->status == "success"){
        ?>
                <div class="row mx-auto mt-5">
                    <div class="card-content d-flex flex-wrap flex-lg-wrap justify-content-start align-items-start col-lg-8 mx-auto text-center">
                        <?php
                            foreach($photos->data as $photo){
                        ?>
                                <div class="col-12 col-lg-3 col-md-4 col-sm-6 col-xs-12 p-3">
                                    <a class="example-image-link" href="<?php echo $photo->filepath; ?>" data-lightbox="example-set">
                                        <div class="carded">
                                            <div class="card-image">
                                                <div class="card-img" style="background-image: url(<?php 
                                                    if(!empty($photo->filename)){
                                                        if(file_exists("images/thumbnails/{$photo->filename}.jpg")){
                                                            echo "images/thumbnails/{$photo->filename}.jpg";
                                                        } else {
                                                            echo $photo->filepath;
                                                        }
                                                    } else {
                                                        echo $photo->filepath;
                                                    }
                                                ?>)">&nbsp;</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>   
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <div class="row contact-container mx-auto mt-5">
            <hr/>
            <div class="col-12 col-lg-6 col-md-6 col-sm-12 d-flex flex-column justify-content-around">
                <div class="contact-info d-flex flex-column mb-5 mb-md-0 justify-content-around align-items-center" style="height: fit-content">
                    <h2 class="text-success text-center fs-2 fw-bold">Business Contact Details</h2>
                    <div class="phone-number d-flex flex-column align-items-start py-3 my-2 ">
                        <p class="text-main">
                            <i class="bi bi-geo me-2 py-2 rounded-md"></i>
                            <?php echo $business->address; ?>
                            <br />
                            <?php
                                if(!empty($business->town)){
                                    echo $business->town;
                                }
                                if(!empty($business->lga)){
                                    echo ", ".$business->lga." LGA";
                                }
                                if(!empty($business->state)){
                                    echo ", ".$business->state." State";
                                }
                            ?>
                        </p>
                        <?php
                            if($hours->status == "success"){
                        ?>
                                <table class="table">
                                    <tr>
                                        <td style="padding-left: 0 !important"><i class="bi bi-clock"></i></td>
                                        <td>
                                        <table class='table table-sm'>
                                            <thead >
                                                <tr>
                                                <th class='text-success' scope="col">Day</th>
                                                <th class='text-success' scope="col">Opens</th>
                                                <th class='text-success' scope="col">Closes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                function writeMsg($al){return substr($al,0,2);}
                                                
                                                foreach($hours->data as $hour){
                                            ?>    
                                                <tr>
                                                    <td class="px-2"><?php echo $hour->day; ?></td>
                                                    <td class="px-2"><?php
                                                        if(!empty($hour->opening_time)){
                                                            echo " ".$hour->opening_time."am";
                                                        } else {
                                                            echo $hour->timing;
                                                        }
                                                    ?></td>
                                                    <td class="px-2"><?php
                                                        if(!empty($hour->closing_time)){
                                                            $closing_time=strval(((int)writeMsg($hour->closing_time))-12);
                                                            echo $closing_time.":00pm";
                                                        } else {
                                                            echo $hour->timing;
                                                        }
                                                    ?></td>
                                                </tr>
                                            <?php
                                                }
                                            ?>
                                            </tbody>
                                        </table>
                                        </td>
                                    </tr>
                                </table>
                        <?php
                            }
                        ?>
                        <a href="tel:<?php echo $business->phonenumber ?>" class="text-main d-block py-2 rounded-md text-decoration-none ">
                            <i class="bi bi-telephone me-3"></i><?php echo $business->phonenumber ?>
                        </a>
                            
                        <a href="mailto:<?php echo $business->email; ?>" class="text-main py-2 rounded-md text-decoration-none">
                            <i class="bi bi-envelope me-3"></i><?php echo $business->email; ?>
                        </a>
                        <?php
                            if($subs->status == "success"){
                                $time = time();
                                $sub = $subs->data;
                                if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                                    $subgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                                    $subscriptions = perform_get_curl($subgurl);
                                    if($subscriptions && $subscriptions->status=="success"){
                                        $subscription = $subscriptions->data;
                                        if($subscription->socialmedia == 1){
                                            if(!empty($business->website)){
                                                if($session->is_logged_in()){
                        ?>
                        
                                                    <a href="<?php echo $business->website; ?>" class="text-main py-2 rounded-md text-decoration-none">
                                                        <i class="bi bi-globe me-3"></i><?php echo $business->website; ?>
                                                    </a>
                        <?php
                                                } else {
                                                    $webfirst = substr($business->website, 0, 10);
                                                    $weblast = substr($business->website, -4);
                                                    $website = $webfirst."****".$weblast;
                        ?>
                                                    <a href="users/signup?page=<?php echo $url; ?>" class="text-main py-2 rounded-md text-decoration-none"
                                                    onclick="return confirm('You have to Signup/Login to have access to this Business\' Website')">
                                                        <i class="bi bi-globe me-3"></i><?php echo $website; ?>
                                                    </a>
                        <?php
                                            
                                                }    
                                            }
                                        }
                                    }
                                }
                            }
                        ?>
                    </div>
                    <?php
                        if($subs->status == "success"){
                            $time = time();
                            $sub = $subs->data;
                            if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                                $subgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                                $subscriptions = perform_get_curl($subgurl);
                                if($subscriptions && $subscriptions->status=="success"){
                                    $subscription = $subscriptions->data;
                                    if($subscription->socialmedia == 1){
                                        if(!empty($business->whatsapp)){
                                            $whatsapp = trim($business->whatsapp);
                                            $number = substr($whatsapp, -10);
                                            $realwhatsapp = "+234".$number;
                    ?>
                                            <div class="address col-sm-10 mb-4 col-11 col-lg-4 d-flex align-items-center justify-content-center ">
                                                <?php
                                                    if($session->is_logged_in()){
                                                ?>
                                                        <a href="https://api.whatsapp.com/send?phone=<?php echo $realwhatsapp; ?>"
                                                        class="col-12 col-md-8 col-lg-10 mx-auto py-3 px-3 rounded text-white text-decoration-none" 
                                                        style='background-color:#00C853' target="_blank">
                                                            <center><span>Reach us on</span> <i class="bi bi-whatsapp fs-4 ms-2"></i></center>
                                                        </a>                                        
                                                <?php
                                                    } else {
                                                ?>
                                                        <a href="users/signup?page=<?php echo $url; ?>"
                                                        onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name ?> on WhatsApp')"
                                                        class="col-12 col-md-8 col-lg-10 mx-auto py-3 px-3 rounded text-white text-decoration-none" 
                                                        style='background-color:#00C853' target="_blank">
                                                            <center><span>Reach us on</span> <i class="bi bi-whatsapp fs-4 ms-2"></i></center>
                                                        </a>
                                                <?php
                                                    }    
                                                ?>
                                            </div>
                                            <div>
                                                <span class='text-success'>Or you can reach us on social media.</span>
                                            </div>
                                            <div class="social-media col-12 col-lg-8 mx-auto d-flex align-items-center justify-content-between justify-content-lg-evenly mt-3">
                                                <?php
                                                    if(!empty($business->facebook_link)){
                                                ?>
                                                            <a href="<?php echo $business->facebook_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-primary text-white">
                                                                <i class="bi bi-facebook"></i>
                                                            </a>
                                                
                                                <?php
                                                    
                                                    }
                                                    if(!empty($business->twitter_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->twitter_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-secondary text-white">
                                                                <i class="bi bi-twitter"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-secondary text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on Twitter')">
                                                                <i class="bi bi-twitter"></i>
                                                            </a>
                                                <?php
                                                        }
                                                    }
                                                    if(!empty($business->instagram_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->instagram_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-warning text-white">
                                                                <i class="bi bi-instagram"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-warning text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on Instagram')">
                                                                <i class="bi bi-instagram"></i>
                                                            </a>
                                                <?php
                                                        }     
                                                    }
                                                    if(!empty($business->linkedin_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->linkedin_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-success text-white">
                                                                <i class="bi bi-linkedin"></i>
                                                            </a>                                                
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-success text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on LinkedIn')">
                                                                <i class="bi bi-linkedin"></i>
                                                            </a>
                                                <?php
                                                       }     
                                                    }
                                                    if(!empty($business->youtube_link)){
                                                        if($session->is_logged_in()){
                                                ?>
                                                            <a href="<?php echo $business->youtube_link; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-danger text-white">
                                                                <i class="bi bi-youtube"></i>
                                                            </a>
                                                <?php
                                                        } else {
                                                ?>
                                                            <a href="users/signup?page=<?php echo $url; ?>" target="_blank" class="d-block py-2 px-3  rounded bg-danger text-white"
                                                            onclick="return confirm('You have to Signup/Login to reach <?php echo $business->name; ?> on YouTube')">
                                                                <i class="bi bi-youtube"></i>
                                                            </a>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </div>
                    <?php
                                        }
                                    }
                                }
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-md-6 col-sm-12 d-flex flex-column">
                <div class="contact-info d-flex flex-column mb-5 mb-md-0 align-items-center" style="height: fit-content">
                    <ul class="p-0 m-0">
                        <h2 class="text-success text-center fs-2 fw-bold">Available Facilities</h2>
                        <div class="d-flex align-items-center justify-content-center text-center">
                            <div class="d-block">
                                <?php
                                    if(!empty($business->facilities)){
                                        $strings = explode(",", $business->facilities);
                                        foreach($strings as $facil_string){
                                            $fgurl = "fetch_facility_by_string_api.php?string=".$facil_string;
                                            $facilities = perform_get_curl($fgurl);
                                            if(($facilities) && ($facilities->status == "success")){
                                                $facility = $facilities->data;
                                ?>
                                                <p class="list text-muted text-center"><?php echo $facility->facility; ?></p>
                                <?php
                                            }
                                        }
                                    } else {
                                        echo '<p>No Registered Facility for this Business</p>';
                                    }
                                ?>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <?php
            if($links->status == "success"){
        ?>
                <div class="row pt-2 mt-5">
                    <hr />
                    <h3 class="text-center text-success fw-bold fs-2">Business Videos</h3>
                    <div class="row p-5">
                        <?php
                            foreach($links->data as $link){
                        ?>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-3 mx-auto">
                                    <iframe src="<?php echo $link->video_link; ?>" title="YouTube video player" 
                                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen style="width: 100%; height: auto"></iframe>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <?php
            if($branches->status == "success"){
        ?>
                <div class="row pt-2 mt-5">
                    <hr />
                    <h3 class="text-center text-success fw-bold fs-2">Branches</h3>
                    <div class="row p-5">
                        <?php
                            foreach($branches->data as $branch){
                        ?>
                                <div class="col-lg-4 col-md-6 col-sm-12 mt-4 mx-auto">
                                    <div class="card" style="height: 350px; overflow: auto;">
                                        <div class="card-body text-center">
                                            <p class="text-center">
                                                <h4 class="text-success text-center"><small>Address</small></h4>
                                                <?php
                                                    echo $branch->address;
                                                    if(!empty($branch->town)){
                                                        echo "<br />".$branch->town;
                                                    }
                                                    if(!empty($branch->lga)){
                                                        echo  "<br />".$branch->lga." Local Gov't Area";
                                                    }
                                                    if(!empty($branch->state)){
                                                        echo "<br />".$branch->state.' State';
                                                    }
                                                    echo ".";
                                                ?>
                                            </p>
                                            <p class="text-center">
                                                <h4 class="text-success text-center"><small><?php echo $branch->head_designation; ?></small></h4>
                                                <?php echo $branch->head_name; ?>
                                            </p>
                                            <?php
                                                if(!empty($branch->phone) || !empty($branch->email)){
                                            ?>
                                                    
                                                    <?php
                                                        if($session->is_logged_in()){
                                                    ?>
                                                            <p>
                                                                <?php
                                                                    if(!empty($branch->phone)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-phone text-success"></i><span>
                                                                                <a href="tel:<?php echo $branch->phone ?>"><?php echo $branch->phone; ?></a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                    if(!empty($branch->email)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-envelope text-success"></i><span>
                                                                                <a href="mailto:<?php echo $branch->email; ?>"><?php echo $branch->email; ?></a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </p>
                                                    <?php
                                                        } else {
                                                            $bprefix = substr($branch->phone, 0, 4);
                                                            $bsuffix = substr($branch->phone, -3);
                                                            $bphone = $bprefix."****".$bsuffix;
                                                            
                                                            $bprestring = substr($branch->email, 0, 4);
                                                            $bpoststring = substr($branch->email, -3);
                                                            $bemail = $bprestring."****".$bpoststring;
                                                    ?>
                                                            <p>
                                                                <?php
                                                                    if(!empty($branch->phone)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-phone text-success"></i><span>
                                                                                <a href="users/signup?page=<?php echo $url; ?>"
                                                                                onclick="return confirm('You have to Signup/Login to have access to this Business\' Contact')">
                                                                                    <?php echo $bphone; ?>
                                                                                </a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                    if(!empty($branch->email)){
                                                                ?>
                                                                        <p>
                                                                            <i class="bi bi-envelope text-success"></i><span>
                                                                                <a href="users/signup?page=<?php echo $url; ?>"
                                                                                onclick="return confirm('You have to Signup/Login to have access to this Business\' Contact')">
                                                                                    <?php echo $bemail; ?>
                                                                                </a>
                                                                            </span>
                                                                        </p>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </p>
                                                    <?php
                                                        }
                                                    ?>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
        <div class="container" id="reviews_container">
            <hr />
            <div class="row" style="padding-top: 30px; padding-bottom: 30px">
                <h3 class="text-center text-success fw-bold fs-2">Reviews</h3>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <div class="row" id="review_summary">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <div class="row" id="reviews">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12 mx-auto">
                        <input type="hidden" name="business_string" id="business_string" value="<?php echo $string; ?>" />
                        <?php
                            if($session->is_logged_in()){
                        ?>
                                <div class="form-group my-2">
                                    <textarea name="review" id="review_text" class="form-control" placeholder="You can drop your view for <?php echo $business->name; ?>" rows="7"></textarea>
                                </div>
                                <div class="form-group my-2 row" id="rating">
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="1" id="1" /><label for="1">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2"></i>
                                            <i class="bi bi-star-fill" id="3"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="2" id="2" /><label for="2">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="3" id="3" /><label for="3">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="4" id="4" /><label for="4">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="5"></i>
                                        </label>
                                    </p>
                                    <p class="col-lg-3 col-md-4 col-sm-6">
                                        <input type="radio" name="star" class="star" value="5" id="5" /><label for="5">
                                            <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="4" style="color: #e1ad01"></i>
                                            <i class="bi bi-star-fill" id="5" style="color: #e1ad01"></i>
                                        </label>
                                    </p>
                                </div>
                                <div class="form-group my2">
                                    <button class="btn btn-block btn-success col-12" id="review_submit">Submit</button>
                                </div>
                        <?php
                            } else {
                        ?>
                                <div class="form-group text-center">
                                    <a href="users/signup?page=<?php echo $url."#reviews_container"; ?>" class="btn btn-success btn-block col-lg-6 mx-auto">Drop Review</a>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
            <hr />
        </div>
        <?php
            if($relateds->status == "success"){
        ?>
                <div class="container">
                    <div class="row" style="padding-top: 10px">
                        <h3 class="text-center text-success fw-bold fs-2">Recommended Businesses</h3>
                        <?php
                            foreach($relateds->data as $related){
                        ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 mb-3">
                                    <div class="card">
                                        <div class="card-image" loading="lazy">
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
                                        <div class='info-container'>
                                        <div class="card-info">
                                            <h6><small><?php echo $related->name; ?></small></h6>
                                        </div>
                                        <div class=' mx-2'>
                                                
                                            <?php
                                                if(!empty($related->categories)){
                                                    echo '<p>';
                                                    foreach($related->categories as $category){
                                                        echo '<a href="#" style="margin-top: 1px; font-size:12px;color:#00C853" class="m-2">#'.$category->category.'</a>';
                                                    }
                                                    echo '</p>';
                                                }
                                            ?>
        
                                        </div>
                                        <div class="ms-2 w-100">
                                            <a href="business?<?php echo $related->verify_string; ?>/<?php echo $related->state ?>/<?php echo $related->town ?>/<?php echo $related->name.".html"; ?>"
                                            class="btn">View Business</a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
        <?php
            }
        ?>
    </main>
    <script>
        let text =<?php echo json_encode($description_text); ?>;
        let count = true;
        function readMore() {
        var para = document.getElementById("para");
        var btn = document.getElementById('read');
        const textList = text.split(' ');
        let disp = ``;
        if (count) {
            btn.innerHTML = 'Read Less';
            para.innerHTML = text; 
            count = false;
        } 
        else {
            btn.innerHTML = 'Read More';
            for (let i=0; i<31; i++) {
                disp +=  textList[i] + ' ';
            }
            para.innerHTML = disp + ' ...';
            count = true;
        }
        }
    </script>

<?php include_layout_template("footer.php"); ?>




-----BLOGPOST

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
    
    $ugurl = "fetch_all_users_api.php";
    $users = perform_get_curl($ugurl);
    if($users){
        if($users->status == "success"){
            $user_count = count($users->data);
        } else {
            $user_count = 0;
        }
    } else {
        die("Users Link Broken");
    }
    
    $bgurl = "fetch_all_businesses_api.php";
    $businesses = perform_get_curl($bgurl);
    if($businesses){
        if($businesses->status == "success"){
            $bus_count = count($businesses->data);
        } else {
            $bus_count = 0;
        }
    } else {
        die("Businesess Link Broken");
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
                <form action="[URL]" method="post">
                    <textarea name="content" id="editor">
                        &lt;p&gt;This is some sample content.&lt;/p&gt;
                    </textarea>
                    <p><input class="btn btn-primary btn mt-2" type="submit" value="Submit"></p>
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