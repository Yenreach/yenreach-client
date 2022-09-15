<?php
    require_once("../includes_public/initialize.php");
    include("default_img.php");

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
    function formatString($str) {
        $pattern = "/&lt;/i";
        $pattern2 = "/&gt;/i";
        $pattern3 = "/;nbsp;/i";
        $pattern4 = "/&/i";
        $pattern5 = "/amp;/i";
        $pattern6 = "/#039;/i";
        
        $str = preg_replace($pattern, "<", $str);
        $str = preg_replace($pattern2, ">", $str);
        $str = preg_replace($pattern3, " ", $str);
        $str = preg_replace($pattern4, "", $str);
        $str = preg_replace($pattern5, "", $str);
        $str = preg_replace($pattern6, "", $str);
        return $str;
      };
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
        }
        /* @media only screen and (min-width:576px) {
            .card-image{
                padding-top: 100%;
            }
        } */
        .card-business {
            height: 300px;
        }
        @media only screen and (min-width:576px) {
            .card-cont {
            height: 370px;
        }
            .card-business{
                height:150px;
            }

        }
        
        .card-img {
            /* position: absolute; */
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-repeat: no-repeat;
            background-position: center center;
            background-size:cover;
            object-fit: cover;


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
                        <div class="media-center d-flex mx-auto align-items-center justify-content-center mb-4 mb-md-0" style="width: 7rem; height:7rem">
                            <?php
                                if(!empty($business->filename)){
                                    if(file_exists("images/thumbnails/".$business->filename.".jpg")){
                                        $img = "images/thumbnails/".$business->filename.".jpg";
                                    } else {
                                        if(file_exists("images/".$business->filename.".jpg")){
                                            $img =  "images/".$business->filename.".jpg";
                                        } else {
                                            if(!empty($business->photos)){
                                                $photod = array_shift($business->photos);
                                                if(!empty($photod->filename)){
                                                    if(file_exists("images/thumbnails/{$photod->filename}.jpg")){
                                                        $img = "images/thumbnails/{$photod->filename}.jpg";
                                                    } elseif(file_exists($photod->filepath)){
                                                        $img = $photod->filepath;
                                                    } else {
                                                        $img = "";
                                                    }
                                                } else {
                                                    if(file_exists($photod->filepath)){
                                                        $img = $photod->filepath;
                                                    } else {
                                                        $img = "";   
                                                    }
                                                }
                                            } else {
                                                $img = "";
                                            }
                                        }
                                    }
                                } else {
                                    if(!empty($business->photos)){
                                        $photod = array_shift($business->photos);
                                        if(!empty($photod->filename)){
                                            if(file_exists("images/thumbnails/{$photod->filename}.jpg")){
                                                $img = "images/thumbnails/{$photod->filename}.jpg";
                                            } elseif(file_exists($photod->filepath)){
                                                $img = $photod->filepath;
                                            } else {
                                                $img = "";
                                            }
                                        } else {
                                            if(file_exists($photod->filepath)){
                                                echo $photod->filepath;
                                            } else {
                                                $img = "";   
                                            }
                                        }
                                    } else {
                                        $img = "";
                                    }
                                }
                                ?>
                                <?php if (!empty($img)) {  ?>
                                    <img class="position-relative" src="<?php echo $img; ?>" alt="<?php echo $business->name;  ?>" style="width:100%; height:100%; border-radius:50%">
                                <?php $img=''; } else { 
                                    echo '<div class="position-relative w-100 h-100 overflow-hidden rounded-circle">';
                                        setBusinessImage($business->name);
                                    echo '</div>';}
                                    // echo setBusinessImage($business->name); }?>
                                
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
                            $description_text = formatString($business->description);
                            $desc_array = array();
                            $explode_desc = explode(' ', $description_text);
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
                    <div class="" style="padding-top: 10px">
                        <h3 class="text-center text-success fw-bold fs-2">Recommended Businesses</h3>
                        <div class= "row">
                            <?php
                                foreach($relateds->data as $related){
                            ?>
                                <div class="col-12 col-lg-2 col-md-3 col-sm-4 mb-3">
                                    <div class="card pb-3 card-cont">
                                            <div class="card-image" loading="lazy">
                                        <?php
                                            if(!empty($related->photos)){
                                                $photo = array_shift($related->photos);
                                                if(file_exists($photo->filepath)){
                                                $main_img = $photo->filepath;
                                                } else {
                                                    if(!empty($related->filename)){
                                                        if(file_exists("images/thumbnails/".$related->filename.".jpg")){
                                                        $main_img = "images/thumbnails/".$related->filename.".jpg";
                                                        } else {
                                                            if(file_exists("images/".$related->filename.".jpg")){
                                                            $main_img = "images/".$related->filename.".jpg";
                                                            } else {
                                                            $main_img = "";
                                                            }
                                                        }
                                                    } else {
                                                    $main_img = "";
                                                    }
                                                }
                                            } else {
                                                if(!empty($related->filename)){
                                                    if(file_exists("images/thumbnails/".$related->filename.".jpg")){
                                                    $main_img = "images/thumbnails/".$related->filename.".jpg";
                                                    } else {
                                                        if(file_exists("images/".$related->filename.".jpg")){
                                                        $main_img = "images/".$related->filename.".jpg";
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
                                        </div> 
                                        <?php $main_img=''; } else {
                                            echo setBusinessImage($related->name); }?>
                                        </div>
                                        <div class='d-flex justify-content-between flex-column bd-highlight h-100  px-2 pt-4'>
                                            <div class="d-flex flex-column gap-2">
                                                <div class="" style="">
                                                    <h6><small><?php echo $related->name; ?></small></h6>
                                                </div>
                                                <div class='' style="">
                                                        
                                                    <?php
                                                        if(!empty($related->categories)){
                                                            echo '<p>';
                                                            foreach($related->categories as $category){
                                                                echo '<a href="#" style="margin-top: 1px; font-size:12px;color:#00C853" class="">#'.$category->category.'</a>';
                                                            }
                                                            echo '</p>';
                                                        }
                                                    ?>
                
                                                </div>
                                            </div>
                                            
                                            <div class="w-100" style="">
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
                </div>
        <?php
            }
        ?>
    </main>
    <script>
        let text =<?php echo json_encode($description_text); ?>;
        // console.log("text0",text)
        let count = true;
        function readMore() {
            // console.log("clicked")
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