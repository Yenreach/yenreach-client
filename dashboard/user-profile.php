<?php
  require_once('../config/connect.php');
  require_once('../config/session.php');
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');

  $id = $_SESSION['tid'];
  $query = mysqli_query($link, "SELECT * FROM `users` WHERE `id`='$id'");
  $result = mysqli_fetch_assoc($query);
  $user_email = $result['email'];
 ?>

 <style media="screen">
   form i {
     cursor: pointer;
   }
 </style>
   <main id="main" class="main">

     <section class="section profile">
       <div class="row">
         <?php
            if(isset($_POST['save_photo'])){
              $target_dir = "../assets/img/clients/user/";
              $target_file = $target_dir.basename($_FILES["photo"]["name"]);
              $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
              $check = getimagesize($_FILES["photo"]["tmp_name"]);

            if($check == false)
            {
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Invalid FIle Type!
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
            elseif(file_exists($target_file))
            {
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Image Already Exist!
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
            elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
            {
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>File type not Supported!<br>Supported File types: jpg, png, jpeg, gif.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
            else {
              $sourcepath = $_FILES["photo"]["tmp_name"];
              $targetpath = "../assets/img/clients/user/" . $_FILES["photo"]["name"];
              $didUpload = move_uploaded_file($sourcepath,$targetpath);

              if($didUpload) {
                $img_location = "assets/img/clients/user/".$_FILES['photo']['name'];
                $insert_image = mysqli_query($link, "UPDATE `users` SET `image` = '$img_location' WHERE `users`.`id` = '$id' AND `users`.`email` = '$user_email'") or die (mysqli_error($link));

                if($insert_image){
                  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Profile Photo Added Successfully!
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>An Error occured!</strong> Please try again Later.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
              }
              }
            }
            }
          ?>
          <?php
             if(isset($_POST['save'])){
               $name = $_POST['name'];
               $dob = $$_POST['dob'];
               $insert = mysqli_query($link, "UPDATE `users` SET `name` = '$name', `dob`='$dob' WHERE `users`.`id` = '$id' AND `users`.`email` = '$user_email'") or die (mysqli_error($link));
               if($insert) {
                 echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                 <strong>Profile Updated Successfully!
                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>';
               }
             }
           ?>
           <?php
              if(isset($_POST['save_password'])){
                  $old_password = $_POST['password'];
                  $new_password = $_POST['newpassword'];
                  $check_password = $_POST['renewpassword'];

                  if($old_password!=$result['password']){
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Password Incorrect!</strong> Please enter your correct password.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                } else if($new_password!=$check_password){
                  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Password Do Not Match!</strong>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else {
                $insert_password = mysqli_query($link, "UPDATE `users` SET `password` = '$new_password' WHERE `id` = '$id' AND `email` = '$user_email'") or die (mysqli_error($link));
                if($insert_password) {
                  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Password Changed Successfully!
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>An error occured!</strong> Try agaian Later!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
              }
              }
              }
            ?>
         <div class="col-xl-4">

           <div class="card">
             <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

               <img src="<?php if($result['image']==""){echo "../assets/img/no_image.png";} else {echo "../".$result['image'];} ?>" alt="" class="rounded-circle">
               <h2><?php echo $result['name'] ?></h2>
               <h3><?php echo $result['email'] ?></h3>
             </div>
           </div>

         </div>

         <div class="col-xl-8">

           <div class="card">
             <div class="card-body pt-3">
               <!-- Bordered Tabs -->
               <ul class="nav nav-tabs nav-tabs-bordered">

                 <li class="nav-item">
                   <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                 </li>

                 <li class="nav-item">
                   <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-photo">Add Profile Photo</button>
                 </li>

                 <li class="nav-item">
                   <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                 </li>

               </ul>
               <div class="tab-content pt-2">

                 <div class="tab-pane fade profile-edit pt-3" id="profile-photo">

                   <!-- Profile Edit Form -->
                   <form role="form" method="post" enctype="multipart/form-data">
                     <div class="row mb-3">
                       <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Add Photo</label>
                       <div class="col-md-8 col-lg-9">
                         <?php if(empty($result['image'])){ ?>
                           <img id="photo" src="../assets/img/user.png" alt="Profile">
                         <?php }else { ?>
                           <img id="photo" src="<?php echo "../".$result['image']; ?>" alt="Profile">
                         <?php } ?>
                         <div class="pt-2">
                           <div class="col-sm-10">
                             <input name="photo" class="form-control" type="file" id="formFile" required>
                           </div>
                         </div>
                       </div>
                     </div>

                     <div class="text-center">
                       <button name="save_photo" type="submit" class="btn text-white" style="background: #00C853;">Save Changes</button>
                     </div>
                   </form><!-- End Profile Edit Form -->

                 </div>

                 <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                   <!-- Profile Edit Form -->
                   <form role="form" method="post">
                     <div class="row mb-3">
                       <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                       <div class="col-md-8 col-lg-9">
                         <input name="name" type="text" class="form-control" id="fullName" value="<?php echo $result['name']; ?>">
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                       <div class="col-md-8 col-lg-9">
                         <input name="email" type="email" class="form-control" id="Email" value="<?php echo $result['email']; ?>" disabled>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="dob" class="col-md-4 col-lg-3 col-form-label">Date of Birth</label>
                       <div class="col-md-8 col-lg-9">
                         <input name="dob" type="date" class="form-control" id="dob" value="<?php echo $result['dob']; ?>" >
                       </div>
                     </div>

                     <div class="text-center">
                       <button name="save" type="submit" class="btn text-white" style="background: #00C853;">Save Changes</button>
                     </div>
                   </form><!-- End Profile Edit Form -->
                 </div>

                 <div class="tab-pane fade pt-3" id="profile-change-password">
                   <!-- Change Password Form -->
                   <form role="form" method="post">

                     <div class="row mb-3">
                       <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                       <div class="col-md-8 col-lg-9 ">
                         <input name="password" type="text" class="form-control" id="currentPassword">
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                       <div class="col-md-8 col-lg-9">
                         <div class="input-group">
                           <input name="newpassword" type="password" class="form-control" id="newpassword">
                           <span class="input-group-text" id="inputGroupAppend"><i class="bi bi-eye-slash" onclick="myFunction(this);"></i></span>
                         </div>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                       <div class="col-md-8 col-lg-9">
                         <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                       </div>
                     </div>

                     <div class="text-center">
                       <button name="save_password" type="submit" class="btn text-white" style="background: #00C853;">Change Password</button>
                     </div>
                   </form><!-- End Change Password Form -->
                 </div>

               </div><!-- End Bordered Tabs -->

             </div>
           </div>

         </div>
       </div>
     </section>

   </main><!-- End #main -->
   <!-- ======= Footer ======= -->
   <footer id="footer" class="footer">
     <div class="copyright">
       &copy; 2021 Copyright <strong><span>yenreach.com</span></strong>. All Rights Reserved
     </div>
   </footer><!-- End Footer -->

   <script>
     document.querySelector('#category').addEventListener('click', load_it)
     function load_it(e) {
       str = e.value;
       var val=document.getElementById('others');
     if(!(str === 'others')) {
       val.innerHTML = '<input type="text" class="form-control" id="other_category" name="other_category" placeholder="Type of Business" required>';
     }
     }
   </script>

   <script type="text/javascript">

         function readthisURL(input) {
             if (input.files && input.files[0]) {
                 var reader = new FileReader();
                 reader.onload = function (e) {
                   document.getElementById(input.name).src = e.target.result;
                 }
                 reader.readAsDataURL(input.files[0]);
             }
         }
     </script>

     <script type="text/javascript">
         function myFunction(e){
           var x = document.getElementById("newpassword");
           if(x.type === "password") {
             x.type = "text";
           } else {
             x.type = "password";
           }
           e.classList.toggle('bi-eye');
       }
     </script>

 <?php
 require_once('includes/footer.php');
  ?>
