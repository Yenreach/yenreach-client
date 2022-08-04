<?php
  require_once('../../config/connect.php');
  require_once('includes/session.php');
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');

  $query = mysqli_query($link, "SELECT * FROM `businesses`");
  $total = mysqli_num_rows($query);
  $tid = $_SESSION['tid'];
 ?>

<main id="main" class="main">
  <section class="section dashboard">
  <div class="col-12">
    <div class="card recent-sales">
      <div id="msg"></div>

      <div class="card-body">
        <h5 class="card-title">Businesses <span>| <?php echo $total; ?></span></h5>

        <table class="table table-borderless datatable">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Business Name</th>
              <th scope="col">Category</th>
              <th scope="col">Email</th>
              <th scope="col">Phone Number</th>
              <th scope="col"></th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php while ($results = mysqli_fetch_assoc($query)) { ?>
            <tr>
              <th scope="row"><a href="#">#<?php echo $results['id']; ?></a></th>
              <td><?php echo $results['name']; ?></td>
              <td><?php echo $results['category']; ?></td>
              <td><a href="mail.php?email=<?php echo $results['email']; ?>" class="text-primary"><?php echo $results['email']; ?> <i class="bx bx-envelope"></i></a></td>
              <td><a href="tel:<?php echo $results['phonenumber']; ?>" class="text-primary"><?php echo $results['phonenumber']; ?> <i class="bx bx-phone"></i></a></td>
              <td scope="row"><a id="<?php echo $results['id'];?>" href="#" onclick="getInfo(this);" data-bs-toggle="modal" data-bs-target="#exampleModalToggle">Edit</a></td>
              <td scope="row"><a id="<?php echo $results['id'];?>" href="includes/delete.php?tid=<?php echo $tid;?>&id=<?php echo $results['id'];?>&tran=bus">Delete</a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

      <form role="form" method="post" enctype="multipart/form-data" class="row g-3 needs-validation">
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-fullscreen-md-down">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Edit Business Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
                <div id="modal-body" class="modal-body">
                  <?php
                  if(isset($_POST['send'])) {
                    $id = $_POST['id'];
                    $query2 = mysqli_query($link, "SELECT * FROM `businesses` WHERE `id`='$id'");
                    $result = mysqli_fetch_assoc($query2);

                    $business_name = $_POST['company'];
                    $description = $_POST['about'];
                    if($_POST['state'] == "Select") {
                      $state = $result['state'];
                    } else {
                      $state = $_POST['state'];
                    }
                    $address = $_POST['address'];
                    $phonenumber = $_POST['phone'];
                    $email = $_POST['email'];
                    $whatsapp_number = $_POST['whatsapp'];
                    $web_link = $_POST['web_link'];
                    $youtube_link = $_POST['youtube'];
                    $facebook_link = $_POST['facebook'];
                    $instagram_link = $_POST['instagram'];
                    $linkedin = $_POST['linkedin'];
                    $experience = $_POST['experience'];

                    if($_POST['days']=="" || $_POST['opening']=="" || $_POST['closing']==""){
                      $working_hours = $result['working_hours'];
                    } else {
                      $working_days = $_POST['days'];
                      $opening_time = $_POST['opening'];
                      $closing_time = $_POST['closing'];
                      $working_hours = $working_days.", ".$opening_time." - ".$closing_time;
                    }

                    $insert = mysqli_query($link, "UPDATE `businesses` SET `name` = '$business_name', `description` = '$description', `address` = '$address', `state` = '$state', `phonenumber` = '$phonenumber', `whatsapp` = '$whatsapp_number', `email` = '$email', `website` = '$web_link', `facebook_link` = '$facebook_link', `instagram_link` = '$instagram_link', `youtube_link` = '$youtube_link', `linkedin_link` = '$linkedin', `lastmodified` = NOW(), `modifiedby` = '$tid', `experience` = '$experience', `working_hours` = '$working_hours' WHERE `id` = '$id'") or die (mysqli_error($link));

                    if($insert) {
                      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      <strong>Profile Updated Successfully!
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
  	data: 'bus='+str,
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
