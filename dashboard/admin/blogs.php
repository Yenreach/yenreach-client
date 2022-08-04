<?php
  require_once('../../config/connect.php');
  require_once('includes/session.php');
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');

  $query = mysqli_query($link, "SELECT * FROM `blog`");
  $total = mysqli_num_rows($query);
  $tid = $_SESSION['tid'];
 ?>

<main id="main" class="main">
  <section class="section dashboard">
  <div class="col-12">
    <div class="card recent-sales">
      <div id="msg"></div>

      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Blog Posts | <?php echo $total; ?> <span style="font-weight: 600;"><a href="#" onclick="getInfo(this);" data-bs-toggle="modal" data-bs-target="#exampleModalToggle">Add Blog Post</a></span></h5>

        <table class="table table-borderless datatable">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Title</th>
              <th scope="col">Content</th>
              <th scope="col">Tags</th>
              <th scope="col">Categories</th>
              <th scope="col"></th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php while ($results = mysqli_fetch_assoc($query)) { ?>
            <tr>
              <th scope="row"><a href="#">#<?php echo $results['id']; ?></a></th>
              <td><?php echo $results['title']; ?></td>
              <td><?php echo $results['content']; ?></td>
              <td><?php echo $results['tags']; ?></td>
              <td><?php echo $results['category']; ?></td>
              <td scope="row"><a id="<?php echo $results['id'];?>" href="#" onclick="getInfo(this);" data-bs-toggle="modal" data-bs-target="#exampleModalToggle">Edit</a></td>
              <td scope="row"><a id="<?php echo $results['id'];?>" href="includes/delete.php?tid=<?php echo $tid;?>&id=<?php echo $results['id'];?>&tran=blo">Delete</a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

      <form role="form" method="post" enctype="multipart/form-data" class="row g-3 needs-validation">
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-fullscreen-md-down">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Blog Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
                <div id="modal-body" class="modal-body">
                  <?php
                  if(isset($_POST['save'])) {
                    $title = $_POST['title'];
                    $content = $_POST['content'];
                    $tag = $_POST['tag'];
                    $category = $_POST['category'];
                    $id = $_POST['id'];

                    $insert = mysqli_query($link, "UPDATE `blog` SET `title` = '$title', `content`='$content', `tags` = 'tag', `category`='$category', `lastmodified` = NOW(), `modifiedby` = '$tid' WHERE `id` = '$id'") or die (mysqli_error($link));
                    if($insert) {
                      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      <strong>Record Updated Successfully!
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Try again Later.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                  }
                  }
                  if(isset($_POST['send'])) {
                    $title = $_POST['title'];
                    $content = $_POST['content'];
                    $tag = $_POST['tag'];
                    $category = $_POST['category'];

                    $insert = mysqli_query($link, "INSERT INTO `blog` (`id`, `image_path`, `title`, `content`, `tags`, `category`, `datecreated`, `lastmodified`, `modifiedby`) VALUES (NULL, '', '$title', '$content', '$tag', '$category', NOW(), NOW(), '$tid')") or die (mysqli_error($link));
                    if($insert) {
                      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      <strong>Record Added Successfully!
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Try again Later.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                  }
                  }
                   ?>

                </div>
              <div class="modal-footer">

              </div>
            </div>
          </div>
        </div>
        </form>

      </div>

    </div>

  </div><!-- End Recent Sales -->
  </section>
</main><!-- End #main -->

<script>
  function getInfo(e) {
		var str = e.id;

	$.ajax({
  	type: "GET",
  	url: "includes/edit.php",
  	data: 'blo='+str,
  	success: function(data){
  		$("#modal-body").html(data);
  	}
   });
  }
</script>

<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
  <div class="copyright">
    &copy; 2021 Copyright <strong><span>yenreach.com</span></strong>. All Rights Reserved
  </div>
</footer><!-- End Footer -->

<?php
  require_once('includes/footer.php');
?>
