<?php
  require_once('config/connect.php');
  require_once('includes/header.php');

  if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $do_page_visit = mysqli_query($link, "INSERT INTO `page_visits` (`id`, `business_id`, `datecreated`) VALUES (NULL, '$id', NOW())") or die (mysqli_error($link));
  }

  $query = mysqli_query($link, "SELECT * FROM `businesses` WHERE `id`='$id'");
  $results = mysqli_fetch_assoc($query);

  $facilities_query = mysqli_query($link, "SELECT * FROM `facilities` WHERE `business`='$id'");
  $facilities_result = mysqli_fetch_assoc($facilities_query);

  $branches_query = mysqli_query($link, "SELECT * FROM `branches` WHERE `business`='$id'");
  $numberOfRows2 = mysqli_num_rows($branches_query);

  $query4 = mysqli_query($link, "SELECT AVG(`ratings`) as average, COUNT(`ratings`) as number FROM (SELECT `ratings`, `business` FROM `reviews` WHERE `business`=$id) as the_averag");
  $results4 = mysqli_fetch_assoc($query4);

  $review_query = mysqli_query($link, "SELECT * FROM `reviews` WHERE `business`='$id'");
  $results5 = mysqli_num_rows($review_query);
 ?>


  <main id="main">
      

    <!-- ======= Breadcrumbs ======= -->
    <!--<section class="breadcrumbs">-->
    <!--  <div class="container">-->

    <!--    <ol>-->
    <!--      <li><a href="index.php">Home</a></li>-->
    <!--      <li>Business Details</li>-->
    <!--    </ol>-->
    <!--    <h2>Business Details</h2>-->

    <!--  </div>-->
    <!--</section>-->
    <!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details"; class="portfolio-details">
        <header id="header" class="fixed-top d-flex align-items-center">
  <div class="container d-flex align-items-center">
    <!-- Uncomment below if you prefer to use an text logo -->
    <!-- <h1 class="logo me-auto"><a href="index.php">Yenreach<span>.</span>com</a></h1>-->
    <a href="index.php" class="logo me-auto"><img src="assets/img/logo.png" alt=""></a>

    <nav id="navbar" class="navbar order-last order-lg-0">
      <ul>
        <li><a class="active" href="index.php">Home</a></li>
        <li><a class="" href="explorer.php">Explore</a></li>
        <li><a class="" href="about.php">About</a></li>
        <li><a class="" href="blog.php">Blog</a></li>
        <li><a class="" href="contact.php">Contact</a></li>
      </ul>
      <i class="bi bi-list mobile-nav-toggle"></i>
    </nav><!-- .navbar -->

    <a href="users/signup" class="get-started-btn">Add My Business</a>
  </div>
</header>
          
      <div class="" style='width:100%';>

        <div class="row gy-4">

          <div class="col-lg-12">
              <div class="col">
						
						<div class="card">
							<div class="card-body">
								<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
									<div class="carousel-inner">
										<div class="carousel-item active">
										    <div style='height:80vh;' class=' w-100 bg-danger d-flex justify-content-center align-items-center '>
	<div class="d-none d-md-block">
												<h5 class='font-weight-bolder text-center font-success' style='font-size: 3.13rem;'>SAVIOURS ASSEMBLY INT.</h5>
												<p class='text-center' style='font-size:1rem'>Worship with us in an atmosphere of the heavenly. We are kin on your spiritual growth and all round upliftment. God bless you

</p>

											</div>
										    </div>
											<!--<img src="https://unsplash.com/photos/KfwwlE8HknI" class="d-block w-100" alt="...">-->
										</div>
										<div class="carousel-item">
										    
										    <div style='height:80vh;' class=' w-100 bg-primary d-flex justify-content-center align-items-center '>
	<div class="d-none d-md-block">
												<h5 class='font-weight-bolder text-center font-success' style='font-size: 3.13rem;'>SAVIOURS ASSEMBLY INT.</h5>
												<p class='text-center' style='font-size:1rem'>Worship with us in an atmosphere of the heavenly. We are kin on your spiritual growth and all round upliftment. God bless you

</p>

											</div>
										    </div>
											<!--<img src="https://unsplash.com/photos/KfwwlE8HknI" class="d-block w-100" alt="...">-->
										</div>
										<div class="carousel-item ">
										    
										    <div style='height:80vh;' class=' w-100 bg-warning d-flex justify-content-center align-items-center '>
	<div class="d-none d-md-block">
												<h5 class='font-weight-bolder text-center font-success' style='font-size: 3.13rem;'>SAVIOURS ASSEMBLY INT.</h5>
												<p class='text-center' style='font-size:1rem'>Worship with us in an atmosphere of the heavenly. We are kin on your spiritual growth and all round upliftment. God bless you

</p>

											</div>
										    </div>
											<!--<img src="https://unsplash.com/photos/KfwwlE8HknI" class="d-block w-100" alt="...">-->
										</div>
										
										
									<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">	<span class="carousel-control-prev-icon" aria-hidden="true"></span>
										<span class="visually-hidden">Previous</span>
									</a>
									<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">	<span class="carousel-control-next-icon" aria-hidden="true"></span>
										<span class="visually-hidden">Next</span>
									</a>
								</div>
							</div>
						</div>
					</div>
            <div class="portfolio-details-slider" id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" h-50">
              <div class="carousel-inner align-items-center>
               
              </div>
              <div class="swiper-pagination"></div>
            </div>

          

            <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Share</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div id="modal-body" class="modal-body">
                    Select Social Media Platform to Share This Page.
                    <div class="social-links text-center pt-3">
                      <a href="https://www.facebook.com/sharer/sharer.php?u=https://yenreach/portfolio-details.php?id=<?php echo $id ?>" target="_blank" class="facebook"><i class="bx bxl-facebook" ></i></a>
                      <a href="https://twitter.com/intent/tweet?url=https://yenreach/portfolio-details.php?id=<?php echo $id ?>&text=View My Business on Yenreach.com" target="_blank" class="twitter"><i class="bx bxl-twitter"></i></a>
                      <a href="https://www.linkedin.com/shareArticle?mini=true&url=https://yenreach/portfolio-details.php?id=<?php echo $id ?>&title=&summary=View My Business on Yenreach.com&source=" target="_blank" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                      <a href="https://api.whatsapp.com/send?text=https://yenreach/portfolio-details.php?id=<?php echo $id ?>" target="_blank" class="whatsapp"><i class="fa-brands fa-whatsapp"></i></i></a>
                    </div>
                  </div>
                  <div class="modal-footer">

                  </div>
                </div>
              </div>
            </div>

            <div class="portfolio-info">
              <h3><?php echo strtoupper($results['name']); ?></h3>
              <hr>
              <h2>Business Description</h2>
              <p><?php if(!empty($results4['average'])) { $rating = $results4['average']; for($i=0; $i<round($rating); $i++) {echo "<i class=\"bi bi-star-fill\"></i>" ?>
              <?php } echo " ".$results5;}  else{ echo "No ";}?> review</p>
              <p>
                <?php if($results['description']=="") {echo "No description yet!";}
                  else {echo $results['description'];} ?>
              </p>
              <hr>
              

            </div>
            <?php if($results['whatsapp']!=""){ ?>
            <div class="portfolio-description">
              <h2>Send <?php echo strtoupper($results['name']); ?> a Message on WhatsApp</h2>
              <p class="justify-content-center">
                <a href="https://api.whatsapp.com/send?phone=<?php echo $results['whatsapp'] ?>" class="whatsapp text-center" style=""><i class="fa-brands fa-whatsapp"></i></i></a>
              </p>
            </div>
            <hr>
          <?php } ?>



            <?php if(strtolower($results['category'])!="job seekers") { ?>
              <div class="portfolio-description">
                <h2>Avalaible Facilities</h2>
                 <ul>
                   <?php if($facilities_result['nose_mask']==1) { ?>
                   <li>
                     <p><?php echo "Nose Mask Required";?></p>
                   </li>
                 <?php } ?>
                   <?php if($facilities_result['delivery']==1) { ?>
                   <li>
                     <p><?php echo "Free Delivery Service";?></p>
                   </li>
                 <?php } ?>
                   <?php if($facilities_result['debit_card']==1) { ?>
                   <li>
                     <p><?php echo "Bank Transfer and POS available";?></p>
                   </li>
                 <?php } ?>
                   <?php if($facilities_result['parking_space']==1) { ?>
                   <li>
                     <p><?php echo "Parking Space Available";?></p>
                   </li>
                 <?php } ?>
                 <?php if($facilities_result['others']!="") { ?>
                 <li>
                   <p><?php echo $facilities_result['others'];?></p>
                 </li>
               <?php } ?>
                 </ul>
              </div>
            <?php } else { ?>
              <div class="portfolio-description">
                <h2>Download CV</h2>
                <p>
                  <a href="<?php echo $results['cv']; ?>" download="myCV">Click to Download</a>
                </p>
              </div>
            <?php }?>
            <hr>
            <div class=' d-flex justify-content-between align-items-center flex-wrap py-5'>
                <ul class='card rounded-lg shadow-lg d-flex flex-column align-items-start justify-content-around py-3 px-5 text-dark' style='height:25rem';>
                <li><strong>Address</strong>: <?php echo strtoupper($results['address']); ?></li>
                <li><strong>Phone Number</strong>:  <a href="tel:'.strtoupper($results['phonenumber']).'"><?php echo strtoupper($results['phonenumber']).'</a>'; ?></li>
                <li><strong>Email</strong>: <a href="mailto:'.strtolower($results['email']).'"><?php echo strtolower($results['email']).'</a>'; ?></li>
              <?php if($results['website']!=""){ ?>
                <li><strong>Website</strong>: <a href="<?php echo strtolower($results['website']) ?>"><?php echo strtolower($results['website']); ?></a></li>
              <?php } ?>
              <?php if($results['working_hours']!=""){ ?>
                <li><strong>Business Hours</strong>: <?php echo strtoupper($results['working_hours']); ?></li>
              <?php } ?>
              <?php if($results['experience']!=""){ ?>
                <li><strong>Years of Experience</strong>: <?php echo strtoupper($results['experience']); ?></li>
              <?php } ?>
              <hr>
              <?php if($numberOfRows2!=false){ ?>
                <li><strong>Branches</strong>:
                  <ul class="mt-1">
                    <?php while ($branches_result = mysqli_fetch_assoc($branches_query)) {?>
                      <li>
                        <p>Manager - <?php echo $branches_result['manager'] ?></p>
                        <p>Address - <?php echo $branches_result['address'] ?></p>
                        <p>Phone Number - <?php echo $branches_result['phonenumber'] ?></p>
                        <p>Location - <?php echo $branches_result['location'] ?></p>
                      </li>
                    <?php }  ?>
                  </ul>
           
                </li>
              <?php } ?>
               <?php if($results['facebook_link']!="" || $results['instagram_link']!="" || $results['linkedin_link']!="") { ?>
                <div class='d-flex w-25 ms-auto justify-content-between align-items-center'><a href="<?php echo $results['facebook_link'] ?>" class="facebook bg-primary d-block p-1 rounded"><i style='display:block; font-size:2rem' class="bx bxl-facebook"></i></a>
                <a href="<?php echo $results['instagram_link'] ?>" class="instagram bg-danger d-block p-1 rounded"><i style='display:block; font-size:2rem' class="bx bxl-instagram"></i></a> <a href="<?php echo $results['linkedin_link'] ?>" class="linkedin"><i style='display:block; font-size:2rem' class="bx bxl-linkedin"></i></a>
                
                </div>
              <?php } ?>
              </ul>
            <div id="review" class="portfolio-description col-6">
              <h2>Write a Review</h2>
              <div class="contact">
                <form action="#" method="post" role="form" class="php-email-form">
                  <div class="row">
                    <!--<div class="mb-3">-->
                    <!--  <label for="customRange2" class="form-label">Rating</label>-->
                      
                    <!--  <input name="rating" type="range" class="form-range" min="0" max="5" step="1" id="customRange2">-->
                    <!--</div>-->
                    <div class="col form-group">
                      <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                    </div>
                    <div class="col form-group">
                      <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <textarea class="form-control" name="review" rows="5" placeholder="Write your review here." required></textarea>
                  </div>
                  <div class="text-center"><button type="submit" name="submit">Send Review</button></div>
                  <?php
                    if(isset($_POST['submit'])) {
                      $name = $_POST['name'];
                      $email = $_POST['email'];
                      $review = $_POST['review'];
                      $rating = $_POST['rating'];
                      $insert = mysqli_query($link, "INSERT INTO `reviews` (`id`, `ratings`, `business`, `name`, `email`, `message`, `user`, `datecreated`, `lastmodified`, `modifiedby`) VALUES (NULL, '$rating', '$id', '$name', '$eamil', '$review', NULL, NOW(), NOW(), 0)") or die (mysqli_error($link));
                      if($insert) {
                        echo '<div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Review Sent!</h4>
                        <hr>
                        <p class="mb-0">Thank you for sharing your review!</p>
                      </div>';
                      }
                    }
                   ?>
                </form>
              </div>
            </div>
            </div>
          </div>

          <div class="col-lg-4 order-2 order-sm-1">
            <?php $video_query = mysqli_query($link, "SELECT `id`, `video_path` FROM `videos` WHERE `business`='$id'");
             $numberOfRows5 = mysqli_num_rows($video_query);
             ?>
            <?php if($numberOfRows5==0){} else{  ?>
              <p class="text-center mb-1">Watch these Videos to Learn More About </p>
              <?php while($video_result = mysqli_fetch_assoc($video_query)) { ?>
          <div class="mb-3">
            <video width="100%" height="auto" controls>
              <source src="<?php echo $video_result['video_path']; ?>" type='video/mp4'>
             </video>
          </div>
        <?php }} ?>
            <div class="portfolio-description">
              <?php
                $get_reviews = mysqli_query($link, "SELECT * FROM `reviews` WHERE `business`='$id'");
                  while($reviews_result = mysqli_fetch_assoc($get_reviews)) {
                   $string=(strtotime('now + 1 hour')-strtotime($reviews_result['datecreated']))/60/60/24;
                   if($string<1) {
                     $string=(strtotime('now + 1 hour')-strtotime($reviews_result['datecreated']))/60/60;
                      if($string<1){
                        $string=(strtotime('now + 1 hour')-strtotime($reviews_result['datecreated']))/60;
                        if($string<1){
                          $time = round($string)." seconds ago";
                        } else {
                          $time = round($string)." minutes ago";
                        }
                      }else {
                        $time = round($string)." hours ago";
                      }
                    }else {
                      $time = round($string)." days ago";
                    }
                  ?>
                    <div class="card mb-3">
                      <div class="card-header">Reviews</div>
                      <div class="card-body">
                        <h5 class="card-title"><?php echo $reviews_result['name']; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">rating: <?php $rating = $reviews_result['ratings']; for($i=0; $i<$rating; $i++) {echo "<i class=\"bi bi-star-fill\"></i>";}?></h6>
                        <p class="card-text"><?php echo $reviews_result['message']; ?></p>
                        <a href="#" class="card-link"><small> <?php echo $time; ?></small></a>
                        <a href="#" class="card-link"><?php echo $reviews_result['email']; ?></a>
                      </div>
                    </div>
                  <?php } ?>
            </div>


    </section><!-- End Portfolio Details Section -->

  </main><!-- End #main -->

  <?php
    require_once('includes/footer.php');
   ?>
