<?php
    require_once('includes/header.php');
    require_once('config/connect.php');
?>
  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
<?php
    require_once('layouts/header.php');
?>
    <section class="breadcrumbs mb-">
      <!--<div class="container">-->

      <!--  <ol>-->
      <!--    <li><a href="index.php">Home</a></li>-->
      <!--    <li>Blog</li>-->
      <!--  </ol>-->
      <!--  <h2>Blog</h2>-->

      <!--</div>-->
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
      <div class="container" data-aos="fade-up">

        <div class="row">

          <div class="col-lg-6 entries py-2 px-1">
            <?php
            $query = mysqli_query($link, "SELECT * FROM `blog`");
            while($results = mysqli_fetch_assoc($query)) { ?>

            <article class="entry">

              <div class="entry-img">
                <img src="<?php echo $results['image_path']; ?>" alt="<?php if($results['image_path']==""){echo "";} ?>" class="img-fluid">
              </div>

              <h2 class="entry-title">
                <a href="blog-single.php?id=<?php echo $results['id'] ?>"><?php echo $results['title']; ?></a>
              </h2>

              <div class="entry-meta">
                <ul>
                  <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="blog-single.php?id=<?php echo $results['id']; ?>"><time datetime="<?php echo $results['datecreated']; ?>"><?php echo date('d M, Y', strtotime($results['datecreated'])); ?></time></a></li>
                </ul>
              </div>

              <div class="entry-content">
                <?php $text = substr($results['content'], 0, 500); echo $text."...";  ?>
                <div class="read-more">
                  <a class='ms-lg-auto' href="blog-single.php?id=<?php echo $results['id']; ?>">Read More</a>
                </div>
              </div>

            </article><!-- End blog entry -->
          <?php }?>

          </div><!-- End blog entries list -->

          <div class="col-lg-4 ms-lg-auto">

            <div class="sidebar col-lg-10 ">

              <h3 class="sidebar-title">Search</h3>
              <div class="sidebar-item search-form">
                <form action="">
                  <input type="text">
                  <button type="submit"><i class="bi bi-search"></i></button>
                </form>
              </div><!-- End sidebar search formn-->

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
    </section><!-- End Blog Section -->

  </main><!-- End #main -->

  <?php
    require_once('includes/footer.php');
?>
