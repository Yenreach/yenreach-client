<?php
  require_once('../../config/connect.php');
  require_once('includes/session.php');
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');

  $query = mysqli_query($link, "SELECT * FROM `users`");
  $total = mysqli_num_rows($query);
  $tid = $_SESSION['tid'];
 ?>

<main id="main" class="main">
  <section class="section dashboard">
  <div class="col-12">
    <div class="card recent-sales">
      <div id="msg"></div>

      <div class="card-body">
        <h5 class="card-title">Users <span>| <?php echo $total; ?></span></h5>

        <table class="table table-borderless datatable">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Phone Number</th>
              <th scope="col">Verification</th>
              <th scope="col"></th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php while ($results = mysqli_fetch_assoc($query)) {
              $id = $results['id'];
              $user_query = mysqli_query($link, "SELECT `phonenumber` FROM `businesses` WHERE `user` = '$id'");
              $phone = mysqli_fetch_assoc($user_query);
              ?>
            <tr>
              <th scope="row"><a href="#">#<?php echo $results['id']; ?></a></th>
              <td><?php echo $results['name']; ?></td>
              <td><a href="mail.php?email=<?php echo $results['email']; ?>" class="text-primary"><?php echo $results['email']; ?> <i class="bx bx-envelope"></i></a></td>
              <td><a href="tel:<?php echo $phone['phonenumber']; ?>" class="text-primary"><?php echo $phone['phonenumber']; ?> <i class="bx bx-phone"></i></a></td>
              <?php if($results['confirmed_email']==1){ ?>
              <td><span class="badge bg-success">Verified</span></td>
            <?php } else{ ?>
              <td><span class="badge bg-danger">Not Verified</span></td>
            <?php } ?>
              <td scope="row"><a id="<?php echo $id;?>" href="#" onclick="getInfo(this);" data-bs-toggle="modal" data-bs-target="#exampleModalToggle">Edit</a></td>
              <td scope="row"><a id="<?php echo $id;?>" href="includes/delete.php?tid=<?php echo $tid;?>&id=<?php echo $id;?>&tran=user">Delete</a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

      <form role="form" method="post" enctype="multipart/form-data" class="row g-3 needs-validation">
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-fullscreen-md-down">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Edit User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
                <div id="modal-body" class="modal-body">
                  <?php
                  if(isset($_POST['send'])) {
                    $name = $_POST['company'];
                    $phone = $_POST['phone'];
                    $email = $_POST['email'];
                    $verify = $_POST['verify'];
                    $id = $_POST['id'];

                    $insert = mysqli_query($link, "UPDATE `users` SET `name` = '$name', `email`='$email', `lastmodified` = NOW(), `modifiedby` = '$tid', `confirmed_email`='$verify' WHERE `id` = '$id'") or die (mysqli_error($link));
                    $insert2 = mysqli_query($link, "UPDATE `businesses` SET `phonenumber` = '$phone', `lastmodified` = NOW(), `modifiedby` = '$tid' WHERE `user` = '$id'") or die (mysqli_error($link));
                    if($insert && $insert2) {
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
  	data: 'user='+str,
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
