<?php
    require_once("../includes_public/initialize.php");
    require_once('includes/header.php');
    // require_once('config/connect.php');
    function formatString($str) {
      $pattern = "/&lt;/i";
      $pattern2 = "/&gt;/i";
      $pattern3 = "/;nbsp;/i";
      $pattern4 = "/&/i";
      $pattern5 = "/amp/i";
      $pattern6 = "/039;/i";
      
      $str = preg_replace($pattern, "<", $str);
      $str = preg_replace($pattern2, ">", $str);
      $str = preg_replace($pattern3, " ", $str);
      $str = preg_replace($pattern4, "", $str);
      $str = preg_replace($pattern5, "", $str);
      $str = preg_replace($pattern5, "'", $str);
      echo $str;
    };

    $gurl = "fetch_all_blog_post_api.php";
    $blogs = perform_get_curl($gurl);
    if($blogs){
        if($blogs->status == "success"){
            $page = !empty($exploded[1]) ? (int)$exploded[1] : 1;
            $per_page = 40;
            $total_count = count($blogs->data);
            
            $pagination = new Pagination($page, $per_page, $total_count);
            $blog_list = array_slice($blogs->data, $pagination->offset(), $per_page);
        } else {
            redirect_to("https://yenreach.com");
        }
    } else {
        die("No Blogs Found");
    }
    
?>
  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
<?php
    require_once('layouts/header.php');
?>

    <!-- ======= Blog Section ======= -->
    <section id="blog" class="mt-4">
      <!-- <h1 class='text-center my-4 mb-5' style="color: #00c853;
">OUR BLOG</h1> -->
      <div class="container" data-aos="fade-up">
        <h1 class="fw-bolder py-4 text-center mb-4 text-success">OUR BLOGS</h1>
        <div class="row">
          <?php
              foreach($blog_list as $blog){  ?>
                  <a href="blog?<?php echo $blog->blog_string; ?>" class="col-12 col-md-6 col-lg-4 mb-4 p-0 p-2 rounded-top" style="color: black">
                       <div class='rounded-top blog-card'>
                          <div class='blog-img rounded-3'>
                            <?php if (!empty($blog->file_path)) {  ?>
                                    <img src="<?php echo"images/".$blog->file_path.".jpg"; ?>" alt="<?php echo $business->name;  ?>" style="width:100%; height:100%;object-fit: cover;">
                            <?php }?>
                          </div>
                          <div class='d-flex flex-column justify-content-between blog-body'>
                              <div class='' style="font-family: 'Open Sans', sans-serif;">
                                  <div class='blog-title fw-bolder'>
                                      <span><?php echo $blog->title; ?></span>
                                  </div>
                                  <div class='' style="height:75px; overflow: hidden; font-size: 0.95rem;">
                                      <?php
                                        formatString($blog->snippet);
                                      ?>
                                  </div>
                              </div>
                              <div class='text-end'>
                                <svg width="30" height="20" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 4.99965C0 4.86705 0.0526784 4.73987 0.146447 4.6461C0.240215 4.55233 0.367392 4.49965 0.5 4.49965H12.293L9.146 1.35366C9.05211 1.25977 8.99937 1.13243 8.99937 0.999655C8.99937 0.866879 9.05211 0.739542 9.146 0.645655C9.23989 0.551768 9.36722 0.499023 9.5 0.499023C9.63278 0.499023 9.76011 0.551768 9.854 0.645655L13.854 4.64565C13.9006 4.6921 13.9375 4.74728 13.9627 4.80802C13.9879 4.86877 14.0009 4.93389 14.0009 4.99965C14.0009 5.06542 13.9879 5.13054 13.9627 5.19129C13.9375 5.25203 13.9006 5.30721 13.854 5.35365L9.854 9.35365C9.76011 9.44754 9.63278 9.50029 9.5 9.50029C9.36722 9.50029 9.23989 9.44754 9.146 9.35365C9.05211 9.25977 8.99937 9.13243 8.99937 8.99965C8.99937 8.86688 9.05211 8.73954 9.146 8.64565L12.293 5.49965H0.5C0.367392 5.49965 0.240215 5.44698 0.146447 5.35321C0.0526784 5.25944 0 5.13226 0 4.99965V4.99965Z" fill="gray"/></svg>
                              </div>
                          </div>
                       </div>
                   </a>
           
          <?php
            };
          ?>
        </div>
      </div>
    </section>
    <!-- End Blog Section -->
    <!-- <section id="blog" class="blog">
      <div class="container" data-aos="fade-up">
        <?php
            // foreach($blogs as $blog){
            //     echo "<div class='row'>";
            //         echo "<div class='col-lg-12'>";
            //             echo "<div class='card'>";
            //                 echo "<div class='card-body'>";
            //                     echo "<div class='card-title'>";
            //                         echo "<h3>{$blog->title}</h3>";
            //                     echo "</div>";
            //                     echo "<div class='card-text'>";
            //                         echo "<p>{$blog->content}</p>";
            //                     echo "</div>";
            //                 echo "</div>";
            //             echo "</div>";
            //         echo "</div>";
            //     echo "</div>";
          ?>
        <?php
            // };
          ?>

      </div>
    </section> -->

  </main><!-- End #main -->

  <?php
    require_once('includes/footer.php');
?>
<script>
    let text =<?php echo json_encode($blog_list); ?>;
    console.log(text)
</script>
