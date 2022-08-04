<?php
    require_once('includes/header.php');
    require_once('config/connect.php');

    if(isset($_GET['id'])){
      $id = $_GET['id'];

      $query = mysqli_query($link, "SELECT * FROM `blog` where `id`='$id'");
      $results = mysqli_fetch_assoc($query);
    }
?>


<?php
    require_once('layouts/header.php');
?>

  <main id="main ">

    <!-- ======= Breadcrumbs ======= -->
    <!--<section class="breadcrumbs">-->
    <!--  <div class="container">-->

    <!--    <ol>-->
    <!--      <li><a href="index.php">Home</a></li>-->
    <!--      <li><a href="blog.php">Blog</a></li>-->
    <!--      <li>Blog Post</li>-->
    <!--    </ol>-->
    <!--    <h2>Full Blog Post</h2>-->

    <!--  </div>-->
    <!--</section>-->
    <!-- End Breadcrumbs -->

    <!-- ======= Blog Single Section ======= -->
    <section id="blog" class="blog mt-5">
      <div class="container" data-aos="fade-up">

        <div class="row">

          <div class="col-lg-8 entries">

            <article class="entry entry-single">

              <div class="entry-img">
                <img src="<?php echo $results['image_path']; ?>" alt="<?php if($results['image_path']==""){echo "";} ?>" class="img-fluid">
              </div>

              <div class="entry-title py-4 ">
                <h4 class=' fw-bolder fs-1' style='color:#00C853'><?php echo $results['title']; ?></h4>
              </div>

              <div class="entry-meta">
                <ul>
                  <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="#">ADMIN</a></li>
                  <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="#"><time datetime="<?php echo $results['datecreated']; ?>"><?php echo date('d M, Y', strtotime($results['datecreated'])); ?></time></a></li>
                </ul>
              </div>

              <div class="entry-content">
                <?php echo $results['content']; ?>

              </div>

              <div class="entry-footer">
                <?php $categories = explode(",", $results['categories']); ?>
                <i class="bi bi-folder"></i>
                <ul class="cats">
                  <?php foreach ($categories as $cat) {
                    echo '<li><a href="#">'.$cat.'</a></li>';
                  } ?>
                </ul>

                <?php $tags = explode(",", $results['tags']); ?>
                <i class="bi bi-tags"></i>
                <ul class="tags">
                  <?php foreach ($tags as $tag) {
                    echo '<li><a href="#">'.$tag.'</a></li>';
                  } ?>
                </ul>
              </div>

            </article><!-- End blog entry -->

          </div><!-- End blog entries list -->

          <div class="col-lg-4">

            <div class="sidebar">

              <h3 class="sidebar-title">Search</h3>
              <div class="sidebar-item search-form">
                <form action="">
                  <input type="text">
                  <button type="submit"><i class="bi bi-search"></i></button>
                </form>
              </div><!-- End sidebar search form-->

              <h3 class="sidebar-title">Recent Posts</h3>
              <div class="sidebar-item recent-posts">
                <?php
                $query2 = mysqli_query($link, "SELECT * FROM `blog`");
                while($result2 = mysqli_fetch_assoc($query2)) { ?>
                <div class="post-item clearfix">
                  <img src="<?php if($result2['image_path']=="") {echo "assets/img/no_image.png";} else {echo $result2['image_path'];} ?>" alt="">
                  <h4><a href="blog-single.php?id=<?php echo $result2['id']; ?>"><?php echo $result2['title']; ?></a></h4>
                  <time datetime="<?php echo $result2['datecreated']; ?>"><?php echo date('d M, Y', strtotime($result2['datecreated'])); ?></time>
                </div>
              <?php } ?>
              </div><!-- End sidebar recent posts-->

            </div><!-- End sidebar -->

          </div><!-- End blog sidebar -->

        </div>

      </div>
    </section><!-- End Blog Single Section -->

  </main><!-- End #main -->

  <?php
    require_once('includes/footer.php');
?>
