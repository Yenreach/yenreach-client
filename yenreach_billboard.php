<?php
    require_once("../includes_public/initialize.php");
    
    $gurl = "fetch_all_advert_payment_types_api.php";
    $types = perform_get_curl($gurl);
    if($types){
        
    } else {
        die("Advert Payment Types Link Broken");
    }
?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Yenreach||Billboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
 <?php include_layout_template("links.php") ?>
</head>
<body>
    <!-- ======= Header ======= -->
    <?php include_layout_template("header.php"); ?>
    <!-- End Header -->
    
    <main id="main">
        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs" style="margin-top: 85px">
          <div class="container">
    
            <ol>
              <li><a href="https://yenreach.com">Home</a></li>
              <li>Yenreach Billboard</li>
            </ol>
            <h2>Yenreach Billboard</h2>
    
          </div>
        </section>
        <!-- End Breadcrumbs -->
        
        <section class="inner-page">
            <div class="row">
                <div class="col-lg-7 col-md-9 com-sm-12 py-3 px-5 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center">Get Much More Eyeballs, Get MUCH MORE SALES</h4>
                        </div>
                        <div class="card-body">
                            <p>
                                The only way your business can survive is by getting the right attention in the right place, at the right time and to the right people.
                            </p>
                            <p>
                                By showcasing your business on our homepage billboard, you get the ultimate advantage of putting your business in front of thousands of customers who might just be searching for your product or service and are ready to do business with you, RIGHT-AWAY.
                            </p>
                            <p>
                                Here are some of the things you enjoy just by having your business displayed on our Yenreach billboard: 
                                <ul>
                                    <li>Drive traffic directly to your Yenreach page so customers can contact you with ease.</li>
                                    <li>Build massive brand awareness</li>
                                    <li>Showcase your business to thousands of our daily website visitors</li>
                                    <li>Get your business displayed 24 hours a day and gain <u>STEADY</u> visibility and eyeballs that can turn into cash.</li>
                                    <li>Reach a wider demographic of potential customers.</li>
                                    <li>Build trust and credibility among potential customers</li>
                                </ul>
                            </p>
                            <p>To get Raving Customers with the Yenreach Billboard, <br /><br />
                                <a href="users/yenreach_billboard" class="btn btn-success btn-lg">Click Here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
</body>
</html>