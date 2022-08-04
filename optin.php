<?php
  require_once('config/connect.php');

  if(isset($_GET['tid'])) {
    $id = $_GET['tid'];

    /*$query = mysqli_query($link, "UPDATE `users` SET `confirmed_email`=1 WHERE `id`='$id'");
    if($query){
      header("Location: dashboard/auth.php");
      echo "<script>console.log('status updated')</script>";
    }*/
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
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome/css/all.css" rel="stylesheet" />

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Presento - v3.5.0
  * Template URL: https://bootstrapmade.com/presento-bootstrap-corporate-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '235061024369195');
fbq('track', 'EmailVerificationPage');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=235061024369195&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
</head>

<body>
<style>
  #exit a {
    background: #00C853;
    color: #fff;
    border: 2px solid #00C853;
    font-weight: bold;
    padding-top: 20px;
    padding-bottom: 20px;
    padding-left: 20px;
    padding-right: 20px;
  }
  #exit a:hover {
    color: #00C853;
    background: #fff;
  }
</style>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="check" viewBox="0 0 16 16">
    <title>Check</title>
    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
  </symbol>
</svg>

  <main id="main">

    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing">
      <div class="d-flex justify-content-center">
        <img src="assets/img/logo.png" alt="" width="300" height="150">
      </div>
      <div class="container" data-aos="fade-up">

        <div class="d-flex justify-content-center px-5">
          <h1 style="color: #00C853">Upgrade Your yenreach Business Page <span style="color: #083640; font-weight: 700">Right Now </span>to Enjoy More Benefits At <span style="color: #083640; font-weight: 700">50% Discount </span>For The First Month!</h1>
          </div>

        <div class="row mt-5 p-5 shadow-lg">

          <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
            <div class="box box-premium" style="color: #fff" data-aos="fade-up" data-aos-delay="300">
              <h3>Premium</h3>
              <h4><sup>₦</sup>4999.99 / <span><del>9999.99</del></span></h4>
              <ul>
                <li>Featured in our Facebook and Instagram ads</li>
                <li>Free promotion across all our social media pages</li>
                <li>Free promotion to our email list</li>
                <li>Free 20 minutes business consultation</li>
                <li>Featured on the slider</li>
                <li>20 product photos</li>
                <li>Top listing in business category</li>
                <li><a class="btn text-white dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#show-more" aria-expanded="false" aria-controls="show-more" >View More</a></li>
              </ul>

              <div id="show-more" class="collapse">
                <ul>
                  <li>Featured on BYS  Business Magazine</li>
                  <li>Display in suggested business list</li>
                  <li>Dedicated customer support</li>
                  <li>2 product video upload</li>
                  <li>Free monthly performance report</li>
                  <li>List additional branches</li>
                  <li>Include social media links</li>
                  <li>Brief business description</li>
                  <li>Link to business website</li>
                  <li>Business address and phone number</li>
                  <li>Business hours</li>
                </ul>
              </div>
              <div class="btn-wrap">
                <a href="payout.php?sub=premium" class="btn-buy">Upgrade Now</a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mt-4">
            <div class="box box-gold" style="color: #fff" data-aos="fade-up" data-aos-delay="200">
              <h3>Gold</h3>
              <h4><sup>₦</sup>3499.99 / <span><del>6999.99</del> </span></h4>
              <ul>
                <li>Listing on the site only</li>
                <li>10 business photos upload</li>
                <li>Brief business description</li>
                <li>Link to business website</li>
                <li>Business address & phone number</li>
                <li>Free Monthly performance report</li>
                <li><a class="btn text-white dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#show-more2" aria-expanded="false" aria-controls="show-more2" >View More</a></li>
                </ul>
                <div id="show-more2" class="collapse">
                  <ul>
                    <li>Free promotion across all our social media pages</li>
                    <li>Include social media links</li>
                    <li>Display business hours</li>
                    <li>Display on suggested businesses</li>
                    <li>Add 2 more branches</li>
                    <li>1 product video upload</li>
                    <li>1 month duration</li>
                    <li>Dedicated customer support</li>
                  </ul>
                </div>
              <div class="btn-wrap">
                <a href="payout.php?sub=gold" class="btn-buy">Upgrade Now</a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mt-4">
          <div class="box box-silver" style="color: #fff" data-aos="fade-up" data-aos-delay="100">
            <h3>Silver</h3>
            <h4><sup>₦</sup>1499.99<span> / <del>2999.99</del></span></h4>
            <ul>
              <li>Listing on the site only</li>
              <li>5 business photos upload</li>
              <li>Brief business subscription</li>
              <li><a class="btn text-white dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#show-more3" aria-expanded="false" aria-controls="show-more3" >View More</a></li>
              </ul>
              <div id="show-more3" class="collapse">
                <ul>
                  <li>Link to business website</li>
                  <li>Business address & phone number</li>
                  <li>Free monthly performance report</li>
                  <li>Free promotion across all our social media pages</li>
                  <li>Include social media links</li>
                  <li>Display business hours</li>
                  <li>1 month duration</li>
                </ul>
              </div>
            <div class="btn-wrap">
              <a href="payout.php?sub=silver" class="btn-buy">Upgrade Now</a>
            </div>
          </div>
        </div>

        </div>
        <div class="d-flex justify-content-center mt-5">
          <h2 class="display-6 text-center mb-4">Compare plans</h2>
        </div>

    <div class="table-responsive">
      <table class="table text-center">
        <thead>
          <tr>
            <th style="width: 32%;"></th>
            <th style="width: 17%;">Premium</th>
            <th style="width: 17%;">Gold</th>
            <th style="width: 17%;">Silver</th>
            <th style="width: 17%;">Free</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row" class="text-start">	Featured in our Facebook and Instagram ads</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td>Listing on the website only</td>
            <td>Listing on the website only</td>
            <td>Listing on the website only</td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Free promotion across all our social media page</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><i class="bi bi-x-lg"></i></td>
          </tr>
        </tbody>

        <tbody>
          <tr>
            <th scope="row" class="text-start">Free promotion to our email list</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Free 20 minutes business consultation</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Featured on the slider</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Product photo Uploads</th>
            <td>20</td>
            <td>10</td>
            <td>5</td>
            <td>2</td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Top listing in business category</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td>2</td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Featured on BYS Magazine</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Display in suggested business list</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Dedicated customer support</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Product Video Uploads</th>
            <td>2</td>
            <td>1</td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Free monthly performance report</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">List additional branches</th>
            <td>4</td>
            <td>2</td>
            <td><i class="bi bi-x-lg"></i></td>
            <td><i class="bi bi-x-lg"></i></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Include social media links</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><i class="bi bi-x-lg"></i></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Brief business description</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Link to business website</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Business address and phone number</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
          </tr>
          <tr>
            <th scope="row" class="text-start">Display Business hours</th>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
            <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div id="exit" class="d-flex justify-content-center mt-5">
      <a href="dashboard/auth.php" class="btn btn-lg">Continue With Free Account!</a>
    </div>

      </div>
    </section><!-- End Pricing Section -->

  </main><!-- End #main -->


  <?php
    require_once('includes/footer.php');
  ?>
