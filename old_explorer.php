<?php
    require_once('config/connect.php');
    require_once('includes/header.php');

?>

  <main id="main">

<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
  <div class="container">
    <ol>
      <li><a href="index.php">Home</a></li>
      <li>Explore</li>
    </ol>
    <h2>Explore</h2>
  </div>
</section><!-- End Breadcrumbs -->

<div class="container">
  <div class="row" style="padding-top: 30px;">
<!-- Side widgets-->
    <div class="col-lg-4 order-2">
        <!-- Search widget-->
        <div class="card mb-4">
            <div class="card-header">Filter</div>
            <div class="card-body">
              <form action="includes/search.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                  <input class="form-control search-text" list="datalistOptions" id="exampleDataList" placeholder="e.g carpenter, restaurant, stylist, doctor, etc" name="category" value="<?php if(isset($_GET['category'])){echo strtoupper($_GET['category']);}?>" required>
                  <datalist id="datalistOptions">
                    <?php $query8 = mysqli_query($link, "SELECT `category` FROM `categories` ORDER BY `category` ASC");
                    while($results8 = mysqli_fetch_assoc($query8)) { ?>
                    <option value="<?php echo strtoupper($results8['category']) ?>">
                    <?php } ?>
                  </datalist>
                </div>
                <select id="inputState" class="form-select" name="location" required>
                  <option <?php if(isset($_GET['location'])){} else {echo "selected";} ?>>Location</option>
                  <?php $query7 = mysqli_query($link, "SELECT * FROM `states` ORDER BY `state` ASC");
                  while($results7 = mysqli_fetch_assoc($query7)) { ?>
                    <option <?php if(isset($_GET['location']) && $_GET['location']==strtolower($results7['state'])){echo "selected";} ?>><?php echo strtoupper($results7['state']); ?></option>
                  <?php } ?>
                </select>
                <label for="inputState"></label>
            <div class="form-text">Enter category and location to search.</div>
              <div><button type="submit" name="search" class="btn btn-primary mt-3">Search</button></div>
            </form>
            </div>
        </div>
        <!-- Categories widget-->
        <div class="card mb-4">
          <div class="card-header">Recommended Businesses</div>
            <div class="card-body">
              <div>
                <ul class="list-unstyled mb-0">
                  <?php $count = 0;
                  if(!isset($_GET['category'])) {
                    $query10 = mysqli_query($link, "SELECT * FROM `businesses` ORDER BY `subscription_type` DESC");
                  } else if(isset($_GET['category']) && !isset($_GET['location'])) {
                    $search_string = "%".$_GET['category']."%";
                      $query10 = mysqli_query($link, "SELECT * FROM `businesses` WHERE `category` LIKE '$search_string' ORDER BY `subscription_type` DESC");
                  } else if(isset($_GET['category']) && isset($_GET['location'])) {
                    $search_string = "%".$_GET['category']."%";
                    $location = $_GET['location'];
                    $query10 = mysqli_query($link, "SELECT * FROM `businesses` WHERE `state`='$location' AND `category` LIKE '$search_string' ORDER BY `subscription_type` DESC");
                  }
                    while($results = mysqli_fetch_assoc($query10)) {
                      $business = $results['id'];
                      $query2 = mysqli_query($link, "SELECT `subscription_status` FROM `subscriptions` WHERE `business`='$business'");
                      $result2 = mysqli_fetch_assoc($query2);
                      $count++;
                      if($result2['subscription_status']==0) {
                          continue;
                      } else {
                      ?>
                    <li><a href="portfolio-details.php?id=<?php echo $business ?>"><?php echo strtoupper($results['name']); ?></a></li>
                  <?php } if($count==20) {break;}} ?>
                </ul>
              </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 order-1">
      <!-- Nested row for non-featured blog posts-->
      <div class="row">
        <?php $count = 0;
        if(!isset($_GET['category'])) {
          $query = mysqli_query($link, "SELECT * FROM `businesses` ORDER BY `datecreated` ASC");
        } else if(isset($_GET['category']) && !isset($_GET['location'])) {
            $search_string = "%".$_GET['category']."%";
            $query = mysqli_query($link, "SELECT * FROM `businesses` WHERE `category` LIKE '$search_string' ORDER BY `subscription_type` DESC");
        } else if(isset($_GET['category']) && isset($_GET['location'])) {
          $search_string = "%".$_GET['category']."%";
          $query = mysqli_query($link, "SELECT * FROM `businesses` WHERE `state`='$location' AND `category` LIKE '$search_string' ORDER BY `subscription_type` DESC");
        }
           while($result6 = mysqli_fetch_assoc($query)) {
              $business2 = $result6['id'];
              $query3 = mysqli_query($link, "SELECT `subscription_status` FROM `subscriptions` WHERE `business`='$business2'");
              $result3 = mysqli_fetch_assoc($query3);
              if($result3['subscription_status']==0) {
                continue;
              } else {
                $query4 = mysqli_query($link, "SELECT `image_path`, `business` FROM `images` WHERE `business`='$business2'");
                $result4 = mysqli_fetch_assoc($query4);
                $query5 = mysqli_query($link, "SELECT AVG(`ratings`) as average, COUNT(`ratings`) as number FROM (SELECT `ratings`, `business` FROM `reviews` WHERE `business`='$business2') as the_averag");
                $results5 = mysqli_fetch_assoc($query5);
                ?>
               <!-- Blog post-->
              <div class="card col-lg-6 mb-4">
                <a href="#!"><img class="card-img-top" src="<?php echo $result4['image_path']; ?>" alt="<?php if($result4['image_path']=="") {echo "";} else {echo $result4['image_path'];} ?>" /></a>
                <div class="card-body">
                  <h2 class="card-title h4"><?php echo strtoupper($result6['name']); ?></h2>
                  <div class="small text-muted"><?php if(!empty($results5['average'])) { $rating = $results5['average']; for($i=0; $i<round($rating); $i++) {echo "<i class=\"bi bi-star-fill\"></i>" ?>
                  <?php } echo " ".$results5['number'];}  else{ echo "No ";}?> review</div>
                  <p class="card-text"><?php echo strtoupper($result6['category']); ?></p>
                  <a class="btn btn-primary" href="portfolio-details.php?id=<?php echo $business2; ?>">View Business →</a>
                </div>
              </div>
        <?php } $count++;} ?>
      </div>
    </div>

  </div>
</div>

  </main><!-- End #main -->

  <?php
    require_once('includes/footer.php');
  ?>
