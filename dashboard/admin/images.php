<?php
  require_once('../../config/connect.php');
  require_once('includes/session.php');
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');

  $query = mysqli_query($link, "SELECT * FROM `images`");
  $total = mysqli_num_rows($query);
  $tid = $_SESSION['tid'];
 ?>

<main id="main" class="main">
  <section class="section dashboard">
  <div class="col-12">
    <div class="card recent-sales">
      <div id="msg"></div>

      <div class="card-body">
        <h5 class="card-title">Subscriptions <span>| <?php echo $total; ?></span></h5>

        <table class="table table-borderless datatable">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Image</th>
              <th scope="col">Business Name</th>
              <th scope="col">Email</th>
              <th scope="col">Phone Number</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php while ($results = mysqli_fetch_assoc($query)) {
              $id = $results['id'];
              $bus_id = $results['business'];
              $bus_query = mysqli_query($link, "SELECT * FROM `businesses` WHERE `id` = '$bus_id'");
              $bus_result = mysqli_fetch_assoc($bus_query);
              ?>
            <tr>
              <th scope="row"><a href="#">#<?php echo $results['id']; ?></a></th>
              <td><img src="<?php echo "../../".$results['image_path']; ?>" class="img-thumbnail" alt="Business-image" height="100" width="100"></td>
              <td><?php echo $bus_result['name']; ?></td>
              <td><a href="mail.php?email=<?php echo $bus_result['email']; ?>" class="text-primary"><?php echo $bus_result['email']; ?> <i class="bx bx-envelope"></i></a></td>
              <td><a href="tel:<?php echo $bus_result['phonenumber']; ?>" class="text-primary"><?php echo $bus_result['phonenumber']; ?> <i class="bx bx-phone"></i></a></td>
              <td scope="row"><a id="<?php echo $id;?>" href="includes/delete.php?tid=<?php echo $tid;?>&id=<?php echo $id;?>&tran=img">Delete</a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

      <form role="form" method="post" enctype="multipart/form-data" class="row g-3 needs-validation">
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-fullscreen-md-down">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Edit Business Subscrption Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
                <div id="modal-body" class="modal-body">
                  <?php
                  if(isset($_POST['send'])) {
                    $type = $_POST['type'];
                    $id = $_POST['id'];
                    $bus = $_POST['bus'];
                    $expiry_date = strtotime("+30 days");
                    $expiry_date = date('Y-m-d h:m:s', $expiry_date);

                    $insert = mysqli_query($link, "UPDATE `subscriptions` SET `subscription_type` = '$type', `subscription_date` = NOW(), `expiry_date` = '$expiry_date', `lastmodified` = NOW(), `modifiedby` = '$tid' WHERE `id` = '$id'") or die (mysqli_error($link));
                    $insert = mysqli_query($link, "UPDATE `businesses` SET `subscription_type` = '$type', `lastmodified` = NOW(), `modifiedby` = '$tid' WHERE `id` = '$bus'") or die (mysqli_error($link));
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
  	data: 'sub='+str,
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
