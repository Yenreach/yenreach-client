<?php
    
  require_once('../config/connect.php');
  require_once('../config/session.php');
  require_once("../../includes_public/initialize.php");
  

  if(isset($_GET['bus'])) {
    $id = $_GET["bus"];
    $bus_id = $_GET['bus'];
  	$query = mysqli_query($link, "SELECT * FROM `businesses` WHERE `id`='$id'");
    $result = mysqli_fetch_assoc($query);
    $user = $_SESSION['tid'];
    $user_query = mysqli_query($link, "SELECT * FROM `users` WHERE `id`='$user'");
    $user_result = mysqli_fetch_assoc($query);
    $facilities_query = mysqli_query($link, "SELECT * FROM `facilities` WHERE `business`='$id'");
    $numberOfRows6 = mysqli_num_rows($facilities_query);
    $facilities_result = mysqli_fetch_assoc($facilities_query);
    $sub_query = mysqli_query($link, "SELECT * FROM `subscriptions` WHERE `business`='$id'");
    $sub_result = mysqli_fetch_assoc($sub_query);
    $branches_query = mysqli_query($link, "SELECT * FROM `branches` WHERE `business`='$id'");
    $numberOfRows2 = mysqli_num_rows($branches_query);
    $get_image_query = mysqli_query($link, "SELECT * FROM `images` WHERE `business`='$id'");
    $check_image = mysqli_num_rows($get_image_query);
    
    $vgurl = "fetch_business_videolinks_api.php?business=".$id;
    $videolinks = perform_get_curl($vgurl);
    if($videolinks){
        if($videolinks->status == "success"){
            $vid_count = count($videolinks->data);
        } else {
            $vid_count = 0;
            $vid_message = $videolinks->message;
        }
    } else {
        die("Videolinks Link Broken");
    }
    
    $sgurl = "fetch_business_subscription_api.php?business=".$id;
    $subscriptions = perform_get_curl($sgurl);
    if($subscriptions){
        if($subscriptions->status == "success"){
            $subscription = $subscriptions->data;  
        } else {
            die($subscriptions->message);
        }
    } else {
        die("Subscriptions Link Broken");
    }
    
    if(isset($_POST['add_video_link'])){
        $platform = !empty($_POST['platform']) ? (string)$_POST['platform'] : "";
        $video_link = !empty($_POST['video_link']) ? (string)$_POST['video_link'] : "";
        
        $purl = "add_businessvideolink_api.php";
        $pdata = [
                'platform' => $platform,
                'video_link' => $video_link,
                'user_id' => $user,
                'business_id' => $id
            ];
        
        $add_video_link = perform_post_curl($purl, $pdata);
        if($add_video_link){
            if($add_video_link->status == "success"){
                redirect_to("profile.php?tid={$user}&bus={$id}");
            } else {
                $message = $add_video_link->message;
            }
        } else {
            $message = "VideoLink Adding Page Broken";
        }
    }
  }
  
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');
 ?>
   <main id="main" class="main">

     <section class="section profile">
       <div class="row">
         <?php
            if(isset($_POST['add_image'])) {
              $target_dir = "../assets/img/clients/business/";
              $target_file = $target_dir.basename($_FILES["business_image"]["name"]);
              $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
              $check = getimagesize($_FILES["business_image"]["tmp_name"]);

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
          } elseif($result['subscription_type']==1 && $check_image>=2){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Maximum Photo upload reached!<br>You can only upload 2 product photos for this package. Upgrade to upload more product photos!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        } elseif($result['subscription_type']==2 && $check_image>=5){
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Maximum Photo upload reached!<br>You can only upload 5 product photos for this package. Upgrade to upload more product photos!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
      } elseif($result['subscription_type']==3 && $check_image>=10){
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Maximum Photo upload reached!<br>You can only upload 10 product photos for this package. Upgrade to upload more product photos!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
      }  elseif($result['subscription_type']==4 && $check_image>=20){
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Maximum Photo upload reached!<br>You can only upload 20 product photos for this package. Upgrade to upload more product photos!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
      }
            else {
              $sourcepath = $_FILES["business_image"]["tmp_name"];
              $targetpath = "../assets/img/clients/business/" . $_FILES["business_image"]["name"];
              $didUpload = move_uploaded_file($sourcepath,$targetpath);

              if($didUpload) {
                $img_location = "assets/img/clients/business/".$_FILES['business_image']['name'];
                $insert_image = mysqli_query($link, "INSERT INTO `images` (`id`, `image_path`, `business`, `user`, `datecreated`, `lastmodified`, `modifiedby`) VALUES (NULL, '$img_location', '$id', '$user', NOW(), NOW(), '$user')") or die (mysqli_error($link));

                if($insert_image){
                  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Product Photo Added Successfully!
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
            if(isset($_POST['save_branch'])) {
              $manager = $_POST['manager'];
              $branch_address = $_POST['branch_address'];
              $branch_phone = $_POST['branch_phone'];
              $branch_state = $_POST['branch_state'];

              if($result['subscription_type']==4 && $numberOfRows2>=5) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>An Error occured!</strong> Branch Limit reached.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }else if($result['subscription_type']==3 && $numberOfRows2>=2) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>An Error occured!</strong> Branch Limit reached.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }else {
              $insert_branch = mysqli_query($link, "INSERT INTO `branches` (`id`, `manager`, `phonenumber`, `address`, `location`, `user`, `business`, `datecreated`, `lastmodified`, `modifiedby`) VALUES (NULL, '$manager', '$branch_phone', '$branch_address', '$branch_state', '$user', '$id', NOW(), NOW(), '$user')") or die (mysqli_error($link));
              if($insert_branch){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Branch Added Successfully!
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
       ?>

       <?php
          if(isset($_POST['save_category'])) {
            $category = $_POST['category'];
            if($category=="others"){
              $category = $_POST['others'];
            }

            $get_cat = $result['category'];
            $get_cat .= ", ".$category;
            $insert_cat = mysqli_query($link, "UPDATE `businesses` SET `category` = '$get_cat' WHERE `businesses`.`id` = '$id'") or die (mysqli_error($link));

            if($insert_cat){
              echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Category Added Successfully!
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
          } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>An Error occured!</strong> Please try again Later.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
          }
          }
        ?>

        <?php
           if(isset($_POST['remove_photo'])) {
             $image_id = $_POST['image_id'];
             $delete_image = mysqli_query($link, "DELETE FROM `images` WHERE `id` = '$image_id'") or die (mysqli_error($link));

             if($delete_image){
               echo '<div class="alert alert-success alert-dismissible fade show col-lg-8" role="alert">
               <strong>Product Photo deleted Successfully!
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
           } else {
             echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
             <strong>An Error occured!</strong> Please try again Later.
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
           </div>';
           }
           }
         ?>

         <?php
            if(isset($_POST['remove_video'])) {
              $video_id = $_POST['video_id'];
              $delete_video = mysqli_query($link, "DELETE FROM `videos` WHERE `id` = '$video_id'") or die (mysqli_error($link));

              if($delete_video){
                echo '<div class="alert alert-success alert-dismissible fade show col-lg-8" role="alert">
                <strong>Product Video deleted Successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            } else {
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>An Error occured!</strong> Please try again Later.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
            }
          ?>

          <?php
             if(isset($_POST['add_video'])) {
               $target_dir = "../assets/video/";
               $target_file = $target_dir.basename($_FILES["business_video"]["name"]);
               $videoFileType = pathinfo($target_file,PATHINFO_EXTENSION);

             if(file_exists($target_file))
             {
               echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
               <strong>Video Already Exist!
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
             }
             elseif(strtolower($videoFileType) != "mp4" && strtolower($videoFileType) != "avi" && strtolower($videoFileType) != "3gp" && strtolower($videoFileType) != "mov" && strtolower($videoFileType) != "mpeg")
             {
               echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
               <strong>File type not Supported!<br>Supported File types: mp4.
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
           }elseif($_FILES["business_video"]["size"] > 5242880)
           {
             echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
             <strong>Video too Large<br>Maximum file size - 5mb.
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
           </div>';
         }   else if($result['subscription_type']==3 && $numberOfRows>=1){
           echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
           <strong>Maximum video upload reached!<br>You can only upload 1 product video for this package. Upgrade to upload more product photos!
           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';
       }  else if($result['subscription_type']==4 && $numberOfRows>=2){
           echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
           <strong>Maximum video upload reached!<br>You can only upload 2 product videos for this package. Upgrade to upload more product photos!
           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';
       }
             else {
               $sourcepath = $_FILES["business_video"]["tmp_name"];
               $targetpath = "../assets/videos/" . $_FILES["business_video"]["name"];
               $didUpload = move_uploaded_file($sourcepath,$targetpath);

               if($didUpload) {
                 $video_location = "assets/videos/".$_FILES['business_video']['name'];
                 $insert_video = mysqli_query($link, "INSERT INTO `videos` (`id`, `video_path`, `business`, `user`, `datecreated`, `lastmodified`, `modifiedby`) VALUES (NULL, '$video_location', '$id', '$user', NOW(), NOW(), '$user')") or die (mysqli_error($link));

                 if($insert_video){
                   echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                   <strong>Product Video Added Successfully!
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
          if(isset($_POST['save_facilities'])) {
            if(isset($_POST['nose_mask'])){$nose_mask = 1;}else{$nose_mask = 0;}
           if(isset($_POST['delivery'])){$delivery = 1;}else{$delivery = 0;}
            if(isset($_POST['parking'])){$parking = 1;}else{$parking = 0;}
            if(isset($_POST['debit'])){$debit = 1;}else{$debit = 0;}
            $others = $_POST['others'];
            if($numberOfRows6==false) {
              $insert_facilities = mysqli_query($link, "INSERT INTO `facilities` (`id`, `nose_mask`, `delivery`, `parking_space`, `debit_card`, `datecreated`, `lastmodified`, `modifiedby`, `business`, `others`) VALUES (NULL, '$nose_mask', '$delivery', '$parking', '$debit', NOW(), NOW(), '$user', '$id', '$others')") or die (mysqli_error($link));
            } else {
              $insert_facilities = mysqli_query($link, "UPDATE `facilities` SET `nose_mask` = '$nose_mask', `delivery` = '$delivery', `parking_space` = '$parking', `debit_card` = '$debit', `others` = '$others', `lastmodified` = NOW(), `modifiedby` = '$user' WHERE `business` = '$id'") or die (mysqli_error($link));
            }
            if($insert_facilities){
              echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Facilites Added Successfully!
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
          } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>An Error occured!</strong> Please try again Later.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
          }
          }
         ?>

         <?php
            if(isset($_POST['save_profile'])) {
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

              $insert_profile = mysqli_query($link, "UPDATE `businesses` SET `name` = '$business_name', `description` = '$description', `address` = '$address', `state` = '$state', `phonenumber` = '$phonenumber', `whatsapp` = '$whatsapp_number', `email` = '$email', `website` = '$web_link', `facebook_link` = '$facebook_link', `instagram_link` = '$instagram_link', `youtube_link` = '$youtube_link', `linkedin_link` = '$linkedin', `lastmodified` = NOW(), `modifiedby` = '$user', `experience` = '$experience', `working_hours` = '$working_hours' WHERE `businesses`.`id` = '$id'") or die (mysqli_error($link));

              if($insert_profile){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Profile Updated Successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
              } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>An Error occured!</strong> Please try again Later.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
              }
          }
          ?>
          <?php
          if(isset($_POST['save_cv'])){
            $sourcepath = $_FILES["job_cv"]["tmp_name"];
            $targetpath = "../assets/cvs/" . $_FILES["job_cv"]["name"];
            $didUpload = move_uploaded_file($sourcepath,$targetpath);
            $insert_cv = mysqli_query($link, "UPDATE `businesses` SET `cv` = '$targetpath' WHERE `businesses`.`id` = '$id'") or die (mysqli_error($link));
            if($insert_cv){
              echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>CV Upload Successfully!
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            } else {
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>An Error occured!</strong> Please try again Later.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
          }
           ?>
         <div class="col-xl-4">

           <div class="card">
             <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

               <h2>Select Business</h2>
               <h3 class="text-center">Select business you wish to view/edit.</h3>
               <form role="form" method="POST" enctype="multipart/form-data">
                 <select class="form-select my-3" name="select_bus" id="select_bus" style="width: 100%;" aria-label="Floating label select example">
                   <option value="choose" selected>Choose</option>
                   <?php $id = $_SESSION['tid'];
                   $bus_query = mysqli_query($link, "SELECT `id`, `name` FROM `businesses` WHERE `user`='$id'");
                   while($bus_result = mysqli_fetch_assoc($bus_query)) { ?>
                     <option value="<?php echo $bus_result['id']; ?>" <?php if(isset($_GET['bus']) && $_GET['bus']==strtolower($bus_result['id'])){echo "selected";} ?>><?php echo strtoupper($bus_result['name']) ?></option>
                   <?php } ?>
                 </select>
                 <button class="btn w-100 text-white" style="background: #00C853;" id="get_info" type="submit" name="get_info">Load Info</button>
                </form>
             </div>
           </div>

         </div>
         <?php if(isset($_POST['get_info'])){
           $bus = $_POST['select_bus'];
           if($bus=="choose"){
             echo "<script>window.location='profile.php?tid=$id';</script>";
           }else {
             echo "<script>window.location='profile.php?tid=$id&bus=".$bus."';</script>";
           }
         }
         ?>

         <?php 
            if(isset($_GET['bus'])){ 
         ?>

         <div id="show" class="col-xl-8">
           <div class="card">
             <div class="card-body pt-3">
               <!-- Bordered Tabs -->
               <ul class="nav nav-tabs nav-tabs-bordered">

                 <li class="nav-item">
                   <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                 </li>

                 <li class="nav-item">
                   <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                 </li>

                 <li class="nav-item">
                   <button class="nav-link" data-bs-toggle="tab" data-bs-target="#add-category">Add Category</button>
                 </li>

                 <li class="nav-item">
                   <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Add Facilities</button>
                 </li>

                 <li class="nav-item">
                   <button class="nav-link" data-bs-toggle="tab" data-bs-target="#image-settings">Add Product Photo</button>
                 </li>

                 <li class="nav-item">
                   <button class="nav-link" data-bs-toggle="tab" data-bs-target="#add-branch">Add Branch</button>
                 </li>

                 <li class="nav-item">
                   <button class="nav-link" data-bs-toggle="tab" data-bs-target="#add-video">Add Product Video</button>
                 </li>

                 <?php if(strtolower($result['category'])=="job seekers") {?>
                   <li class="nav-item">
                     <button class="nav-link" data-bs-toggle="tab" data-bs-target="#add-cv">Update CV</button>
                   </li>
                 <?php }?>

               </ul>

               <div class="tab-content pt-2">

                 <div class="tab-pane fade show active profile-overview" id="profile-overview">
                   <h5 class="card-title">Description</h5>
                   <p class="small fst-italic"><?php if($result['description']!="") {echo $result['description'];} else{echo 'How will you describe your business? This will help customers know what you business is about.';} ?></p>

                   <h5 class="card-title">Business Details</h5>

                   <div class="row">
                     <div class="col-lg-3 col-md-4 label">Business Name</div>
                     <div class="col-lg-9 col-md-8"><?php if($result['name']!="") { echo $result['name'];} else {echo 'What is the name of your business?';} ?></div>
                   </div>

                   <div class="row">
                     <div class="col-lg-3 col-md-4 label">Categories</div>
                     <div class="col-lg-9 col-md-8"><?php if($result['category']!="") {echo $result['category'];} else{echo 'What kind of service deos your business offer?';} ?></div>
                   </div>

                   <div class="row">
                     <div class="col-lg-3 col-md-4 label">State</div>
                     <div class="col-lg-9 col-md-8"><?php if($result['state']!="") {echo $result['state'];} else{echo 'Which state is your business located';} ?></div>
                   </div>

                   <div class="row">
                     <div class="col-lg-3 col-md-4 label">Address</div>
                     <div class="col-lg-9 col-md-8"><?php if($result['address']!="") {echo $result['address'];} else{'What is your business address?';} ?></div>
                   </div>

                   <div class="row">
                     <div class="col-lg-3 col-md-4 label">Phone</div>
                     <div class="col-lg-9 col-md-8"><?php if($result['phonenumber']!="") {echo $result['phonenumber'];} else{'What is your business phone number?';} ?></div>
                   </div>

                   <div class="row">
                     <div class="col-lg-3 col-md-4 label">Whatsapp Number</div>
                     <div class="col-lg-9 col-md-8"><?php if($result['whatsapp'] != "") {echo $result['whatsapp'];} else{echo 'What is your business WhatsApp Number?';} ?></div>
                   </div>

                   <div class="row">
                     <div class="col-lg-3 col-md-4 label">Email</div>
                     <div class="col-lg-9 col-md-8"><?php if($result['email'] != "") {echo $result['email'];} else{'What is your business email address?';} ?></div>
                   </div>

                   <?php if(strtolower($result["category"])=="job seekers") { ?>
                     <div class="row">
                       <div class="col-lg-3 col-md-4 label">Download CV</div>
                       <div class="col-lg-9 col-md-8"><a href="../<?php echo $result['cv']; ?>" download="myCV">Click to Download</a></div>
                     </div>
                   <?php } else {?>
                     <div class="row">
                       <div class="col-lg-3 col-md-4 label">Working Period</div>
                       <div class="col-lg-9 col-md-8"><?php if($result['working_hours'] != '') {echo $result['working_hours'];} else{'When is your business open for service?';} ?></div>
                     </div>

                     <div class="row">
                       <div class="col-lg-3 col-md-4 label">Branches</div>
                       <div class="col-lg-9 col-md-8">
                         <?php
                         if (isset($_GET['bus'])) {
                           $numberOfRows3 = mysqli_num_rows($branches_query);
                           if($numberOfRows3==false){
                             echo "Where else is your business located?";
                           } else { ?>
                             <ul>
                               <?php while ($branches_result = mysqli_fetch_assoc($branches_query)) {?>
                                 <li>
                                   <p>Manager - <?php echo $branches_result['manager'] ?></p>
                                   <p>Address - <?php echo $branches_result['address'] ?></p>
                                   <p>Phone Number - <?php echo $branches_result['phonenumber'] ?></p>
                                   <p>Location - <?php echo $branches_result['location'] ?></p>
                                 </li>
                               <?php }  ?>
                             </ul>
                           <?php }}?>
                         </div>
                       </div>
                   <?php } ?>

                   <div class="row">
                     <?php if(isset($_GET['bus'])) { ?>
                       <div class="col-lg-3 col-md-4 label">Subscription Status</div>
                       <div class="col-lg-6 col-md-4"><?php if($sub_result['subscription_type']==1) {echo "Free Trial";} else
                       if ($sub_result['subscription_type']==2){echo "Silver";} else if ($sub_result['subscription_type']==3) {
                         echo "Gold";} else {echo "Premium"; }?><span class="badge bg-success"><?php if ($sub_result['subscription_status']==1) {
                           echo "ACTIVE";} else {echo "EXPIRED";}?></span></div>
                       <?php if ($sub_result['subscription_type']<4) {?><div class="col-lg-3 col-md-4 mt-2 label"><a href="../optin.php" class="btn btn-warning py-1 px-2">Upgrade</a></div><?php } ?>
                     <?php } ?>
                   </div>

                 </div>

                 <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                   <!-- Profile Edit Form -->
                   <form role="form" method="POST">

                     <div class="row mb-3">
                       <label for="company" class="col-md-4 col-lg-3 col-form-label">Business Name</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <input name="company" type="text" class="form-control" id="company" value="<?php echo $result['name']; ?>">
                         <label for="company">What is the name of your business?</label>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="about" class="col-md-4 col-lg-3 col-form-label">Business Description</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <textarea name="about" class="form-control" id="about" style="height: 200px"><?php echo $result['description']; ?></textarea>
                         <label for="about">How will you describe your business?</label>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="Country" class="col-md-4 col-lg-3 col-form-label">State</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <select id="inputState" class="form-select" name="state">
                           <option <?php if($result['state']==""){echo "selected";} else {} ?>>Select</option>
                           <?php $query7 = mysqli_query($link, "SELECT * FROM `states` ORDER BY `state` ASC");
                           while($results7 = mysqli_fetch_assoc($query7)) { ?>
                             <option value="<?php echo $results7['state']; ?>" <?php if($result['state']==strtolower($results7['state'])){echo "selected";} ?>><?php echo strtoupper($results7['state']); ?></option>
                           <?php } ?>
                         </select>
                         <label for="Country">Which state is your business located?</label>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <input name="address" type="text" class="form-control" id="Address" value="<?php echo $result['address']; ?>">
                         <label for="Address">What is your business address?</label>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <input name="phone" type="text" class="form-control" id="Phone" value="<?php echo $result['phonenumber']; ?>">
                         <label for="Phone">What is your business phone number?</label>
                         <div class="form-text">Phone Number Format: +234XXXXXXXX</div>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <input name="email" type="email" class="form-control" id="Email" value="<?php echo $result['email']; ?>">
                         <label for="Phone">What is your business email address?</label>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Whatsapp Number</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <input name="whatsapp" type="text" class="form-control" id="Twitter" value="<?php echo $result['whatsapp']; ?>">
                         <label for="Phone">What is your business WhatsApp number?</label>
                         <div class="form-text">Phone Number Format: +234XXXXXXXX</div>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="experience" class="col-md-4 col-lg-3 col-form-label">Experience</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <input name="experience" type="text" class="form-control" id="experience" value="<?php echo $result['experience']; ?>">
                         <label for="experience">How long have you been in business?</label>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="Job" class="col-md-4 col-lg-3 col-form-label">Website Link</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <input name="web_link" type="text" class="form-control" id="Job" value="<?php echo $result['website_link']; ?>">
                         <label for="Job">What is your business website?</label>
                         <div class="form">https://yourwebsite.com</div>
                       </div>
                     </div>


                     <div class="row mb-3">
                       <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <?php if($sub_result['subscription_type']>1) { ?>
                         <input name="facebook" type="text" class="form-control" id="Facebook" value="<?php echo $result['facebook_link']; ?>">
                         <label for="facebook">What is the Link to your business Facebook Page?</label>
                         <div class="form">Ensure the link has https:// in it.</div>
                       <?php } else { echo '<a href="https://yenreach.com/optin.php" class="btn btn-warning"><i class="bi bi-lock"></i> Upgrade to Unlock Feature!</a>';} ?>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <?php if($sub_result['subscription_type']>1) { ?>
                         <input name="instagram" type="text" class="form-control" id="Instagram" value="<?php echo $result['instagram_link']; ?>">
                         <label for="instagram">What is the Link to your business Instagram Page?</label>
                         <div class="form">Ensure the link has https:// in it.</div>
                       <?php } else { echo '<a href="https://yenreach.com/optin.php" class="btn btn-warning"><i class="bi bi-lock"></i> Upgrade to Unlock Feature!</a>';} ?>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="youtube" class="col-md-4 col-lg-3 col-form-label">Youtube Link</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <?php if($sub_result['subscription_type']>1) { ?>
                         <input name="youtube" type="text" class="form-control" id="youtube" value="<?php echo $result['youtube_link']; ?>">
                         <label for="youtube">What is  your business Youtube Link?</label>
                         <div class="form">Ensure the link has https:// in it.</div>
                       <?php } else { echo '<a href="https://yenreach.com/optin.php" class="btn btn-warning"><i class="bi bi-lock"></i> Upgrade to Unlock Feature!</a>';} ?>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Link</label>
                       <div class="col-md-8 col-lg-9 form-floating">
                         <?php if($sub_result['subscription_type']>1) { ?>
                         <input name="linkedin" type="text" class="form-control" id="Linkedin" value="<?php echo $result['linkedin_link']; ?>">
                         <label for="linkedin">What is the Link to your business Linkedin Page?</label>
                         <div class="form">Ensure the link has https:// in it.</div>
                       <?php } else { echo '<a href="https://yenreach.com/optin.php" class="btn btn-warning"><i class="bi bi-lock"></i> Upgrade to Unlock Feature!</a>';} ?>
                       </div>
                     </div>

                     <?php if(strtolower($result['category'])!="job seekers"){ ?>
                       <div class="row mb-3">
                         <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Working Hours</label>
                         <div class="col-md-8 col-lg-9">
                           <ul>
                             <li>
                               <div class="input-group">
                                 <span class="input-group-text" id="basic-addon1">Opening</span>
                                 <input type="time" class="form-control" aria-describedby="basic-addon1" name="opening">
                               </div>
                             </li>
                             <li>
                               <div class="input-group">
                                 <span class="input-group-text" id="basic-addon2">Closing</span>
                                 <input type="time" class="form-control" aria-describedby="basic-addon2" name="closing">
                               </div>
                             </li>
                           </ul>
                         </div>
                       </div>

                       <div class="row mb-3">
                         <label for="days" class="col-md-4 col-lg-3 col-form-label">Days of The Week</label>
                         <div class="col-md-8 col-lg-9 form-floating">
                           <input name="days" type="text" class="form-control" id="days" placeholder="e.g Monday - Tuesday">
                           <label for="days">What days of the week is your business open e.g Monday - Tuesday?</label>
                         </div>
                       </div>
                      <?php } ?>

                     <div class="text-center">
                       <?php if(isset($_GET['bus'])) {
                         echo '<button type="submit" class="btn text-white" style="background: #00C853;" name="save_profile">Save Changes</button>';
                       } else {
                         echo '<button type="submit" class="btn text-white" style="background: #00C853;" name="save_profile" disabled>Save Changes</button>';
                       } ?>
                     </div>
                   </form><!-- End Profile Edit Form -->
                 </div>

                 <div class="tab-pane fade pt-3" id="add-category">

                   <!-- Category Form -->
                   <form role="form" method="post">
                     <div class="row mb-3">
                       <label for="business_cat" class="col-md-4 col-lg-3 col-form-label">Business Categories</label>
                       <div class="col-md-8 col-lg-9">
                         <?php
                            if(strpos($result['category'], ',') != false) {
                              $categories = explode(",", $result['category']);
                              for ($i=0; $i < sizeof($categories); $i++) {
                                echo '<div class="d-inline ml-1 mt-1"><a href="delete.php?id='.$id.'&cat='.$categories[$i].'&user='.$user.'">'.$categories[$i].' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></a></div>';
                              }
                            } else {
                              echo '<div class="d-inline"><a href="delete.php?id='.$id.'&cat='.$result['category'].'&user='.$user.'">'.$result['category'].' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></a></div>';
                            }
                          ?>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="category" class="col-md-4 col-lg-3 col-form-label">Select Category</label>
                       <div class="col-md-8 col-lg-9">
                         <select name="category" id="category" class="form-select" aria-label="Default select example" onChange="load_it(this);">
                           <option selected>Select</option>
                           <?php $cat_query = mysqli_query($link, "SELECT `category` FROM `categories` ORDER BY `category` ASC");
                             while($cat_result = mysqli_fetch_assoc($cat_query)) { ?>
                             <option value="<?php echo strtolower($cat_result['category']); ?>"><?php echo strtoupper($cat_result['category']) ?></option>
                           <?php } ?>
                           <option value="others">Others</option>
                         </select>
                         <div id="others" class="mt-3">

                         </div>
                       </div>
                     </div>

                     <div class="text-center">
                       <?php if(isset($_GET['bus'])) {
                         echo '<button type="submit" class="btn text-white" style="background: #00C853;" name="save_category">Add Category</button>';
                       } else {
                         echo '<button type="submit" class="btn text-white" style="background: #00C853;" name="save_category" disabled>Add Category</button>';
                       } ?>
                     </div>
                   </form><!-- End category Form -->

                 </div>

                 <div class="tab-pane fade pt-3" id="profile-settings">

                   <!-- Settings Form -->
                   <form role="form" method="post">

                     <div class="row mb-3">
                       <div class="col-md-8 col-lg-9">
                         <div class="form-check">
                           <input name="nose_mask" class="form-check-input" type="checkbox" id="changesMade" <?php if($facilities_result['nose_mask']==1) {echo "checked";} ?>>
                           <label class="form-check-label" for="changesMade">
                             Nose Mask Required
                           </label>
                         </div>
                         <div class="form-check">
                           <input name="delivery" class="form-check-input" type="checkbox" id="newProducts" <?php if($facilities_result['delivery']==1) {echo "checked";} ?>>
                           <label class="form-check-label" for="newProducts">
                             Free Delivery
                           </label>
                         </div>
                         <div class="form-check">
                           <input name="parking" class="form-check-input" type="checkbox" id="proOffers" <?php if($facilities_result['parking_space']==1) {echo "checked";} ?>>
                           <label class="form-check-label" for="proOffers">
                             Parking Space
                           </label>
                         </div>
                         <div class="form-check">
                            <input name="debit" class="form-check-input" type="checkbox" id="securityNotify" <?php if($facilities_result['debit_card']==1) {echo "checked";} ?>>
                           <label class="form-check-label" for="securityNotify">
                             Accept Card or Transfer
                           </label>
                         </div>
                         <div class="mt-3">
                           <label class="col-md-4 col-lg-3 col-form-label">Other Facilities</label>
                           <div class="form-floating">
                             <textarea name="others" class="form-control" placeholder="Write Facilites in New Lines" id="floatingTextarea" style="height: 200px;"><?php if($facilities_result['others']!="") {echo $facilities_result['others'];} else {echo "Write New Facilites in New Lines";} ?></textarea>
                             <label for="floatingTextarea"></label>
                             <div class="form-text">Write New Facilites in New Lines</div>
                           </div>
                         </div>
                       </div>
                     </div>

                     <div class="text-center">
                       <?php if(isset($_GET['bus'])) {
                         echo '<button type="submit" class="btn text-white" style="background: #00C853;" name="save_facilities">Save Changes</button>';
                       } else {
                         echo '<button type="submit" class="btn text-white" style="background: #00C853;" name="save_facilities" disabled>Save Changes</button>';
                       } ?>
                     </div>
                   </form><!-- End settings Form -->
                 </div>

                 <div class="tab-pane fade pt-3" id="image-settings">
                     <?php if($check_image != false) { ?>
                       <p class="text-center mb-1">Product Photos</p>
                   <div id="carouselExampleDark" class="carousel carousel-dark slide px-4 mb-4" data-bs-ride="carousel" style="height: 400px;">
                     <div class="carousel-inner">
                       <?php
                         $count = 1;
                         while($get_image_result = mysqli_fetch_assoc($get_image_query)) {
                       ?>
                       <div class="carousel-item <?php if($count==1) {echo "active";} else {}?>" data-bs-interval="3000">
                         <img src="<?php echo "../".$get_image_result['image_path']?>" alt="" class="w-100" style="height: 400px;">
                         <div class="carousel-caption d-md-block d-sm-block">
                           <form role="form" method="post">
                             <button class="btn btn-warning"><i class="bi bi-unlock"></i> Unlocked</button>
                             <input class="visually-hidden" type="tel" name="image_id" value="<?php echo $get_image_result['id'];?>">
                             <button name="remove_photo" type="submit" class="btn btn-danger">Remove Photo</button>
                         </form>
                         </div>
                       </div>
                     <?php $count++;} ?>
                     </div>
                     <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                       <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                       <span class="visually-hidden">Previous</span>
                     </button>
                     <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                       <span class="carousel-control-next-icon" aria-hidden="true"></span>
                       <span class="visually-hidden">Next</span>
                     </button>
                   </div>
                 <?php } ?>
                   <!-- Category Form -->
                   <form role="form" method="post" enctype="multipart/form-data">

                     <div class="row mb-3 pt-3">
                       <div class="col-md-12 row">
                         <p class="text-center">Add Product Photo</p>
                         <div class="col-md-5"><img id="business_image" class="border" src="../assets/img/user.png" alt="Profile" width="200" height="200"></div>
                         <div class="col-md-7 align-item-center">
                           <div class="mb-3">
                             <input class="form-control" type="file" id="formFile" name="business_image" onChange="readthisURL(this)" lang="en" required>
                           </div>
                           <div class="mb-3">
                             <?php if(isset($_GET['bus'])) {
                               echo '<button id="add_button" type="submit" onclick="load(this);" class="btn text-white" style="background: #00C853;" name="add_image">Add Photo</button>';
                             } else {
                               echo '<button id="add_button" type="submit" onclick="load(this);" class="btn text-white" style="background: #00C853;" name="add_image" disabled>Add Photo</button>';
                             } ?>
                           </div>
                         </div>
                       </div>
                     </div>
                   </form><!-- End category Form -->

                 </div>

                 <div class="tab-pane fade pt-3" id="add-branch">
                   <!-- Branch Form -->
                   <?php if(isset($_GET['bus'])) {
                     if($sub_result['subscription_type']<3){
                       echo '<div class="text-center" style="font-weight: 600"><i class="bi bi-lock"></i> Availabe to Gold and Premium Subscribers only!</div>';
                     } else{ ?>
                   <form action="#" method="post">

                     <div class="row mb-3">
                       <label for="manager" class="col-md-4 col-lg-3 col-form-label">Manager Name</label>
                       <div class="col-md-8 col-lg-9">
                         <input name="manager" type="text" class="form-control" id="manager">
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                       <div class="col-md-8 col-lg-9">
                         <input name="branch_address" type="text" class="form-control" id="address">
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="Country" class="col-md-4 col-lg-3 col-form-label">State</label>
                       <div class="col-md-8 col-lg-9">
                         <select id="inputState" class="form-select" name="branch_state">
                           <option>Select</option>
                           <?php $query7 = mysqli_query($link, "SELECT * FROM `states` ORDER BY `state` ASC");
                           while($results7 = mysqli_fetch_assoc($query7)) { ?>
                             <option value="<?php echo $results7['state']; ?>"><?php echo strtoupper($results7['state']); ?></option>
                           <?php } ?>
                         </select>
                       </div>
                     </div>

                     <div class="row mb-3">
                       <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone Number</label>
                       <div class="col-md-8 col-lg-9">
                         <input name="branch_phone" type="text" class="form-control" id="phone">
                       </div>
                     </div>

                     <div class="text-center">
                       <button type="submit" class="btn text-white" style="background: #00C853;" name="save_branch">Add Branch</button>
                     </div>
                   </form><!-- End branch Form -->
                 <?php }} ?>
                 </div>

                <div class="tab-pane fade pt-3" id="add-video">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Videos</h4>
                                    <span><?php echo output_message($message); ?></span>
                                </div>
                                <div class="card-body pt-3">
                                    <?php
                                        if($vid_count > 0){
                                    ?>
                                            <div class="table-responsive text-dark">
                                                <table class="table table-striped table-bordered table-responsive-sm text-dark">
                                                    <thead>
                                                        <tr>
                                                            <th>Video</th>
                                                            <th>Platform</th>
                                                            <th>Link</th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach($videolinks->data as $videolink){
                                                        ?>
                                                                <tr>
                                                                    <td><?php
                                                                        if($videolink->platform == "Youtube"){
                                                                    ?>
                                                                            <iframe height="200" width="auto" src="<?php echo $videolink->video_link; ?>" title="YouTube video player" 
                                                                            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                            allowfullscreen></iframe>
                                                                    <?php
                                                                        } 
                                                                    ?></td>
                                                                    <td><?php echo $videolink->platform; ?></td>
                                                                    <td><a href="<?php echo $videolink->video_link ?>" target="_blank" class="text-primary"><?php echo $videolink->video_link; ?></a></td>
                                                                    <td><a href="edit_business_videolink.php?string=<?php echo $videolink->verify_string ?>" class="btn btn-primary">Change Video</a></td>
                                                                    <td><a href="delete_business_videolink.php?string=<?php echo $videolink->verify_string ?>" class="btn btn-danger"
                                                                    onclick="return confirm('Do you really want to delete this Link>')">Delete Video</a></td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                    <?php
                                        } else {
                                            echo '<p class="text-center text-dark">'.$vid_message.'</p>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"><span>Add Video</span></div>
                                </div>
                                <div class="card-body pt-3">
                                    <?php
                                        if($subscription->subscription_type < 3){
                                            $upload = "no";
                                            $vid_uploadmsg = "This feature is only available to Gold and Premium Package Subscribers. Click <a href=\"https://yenreach.com/optin.php\">here</a> to upgrade your Subscription";
                                        } else {
                                            if($subscription->subscription_status != 1){
                                                $vidlinkupload = "no";
                                                $vid_uploadmsg = "Your Subscription has expired. Please click <a href=f=\"https://yenreach.com/optin.php\">here</a> to renew your Subscription";
                                            } else {
                                                if(($subscription->subscription_type == 3) && ($vid_count >= 1)){
                                                    $vidlinkupload = "no";
                                                    $vid_uploadmsg = "You have reached the Video Upload Limit of this Subscription Package. Please click <a href=f=\"https://yenreach.com/optin.php\">here</a> to upgrade your Subscription";
                                                } elseif(($subscription->subscription_type == 4) && ($vid_count >= 2)){
                                                    $vidlinkupload = "no";
                                                    $vid_uploadmsg = "You have reached the Video Upload Limit of this Subscription Package.";
                                                } else {
                                                    $vidlinkupload = "yes";
                                                }
                                            }
                                        }
                                        
                                        if($vidlinkupload == "yes"){
                                    ?>
                                            <form action="profile.php?tid=<?php echo $user ?>&bus=<?php echo $bus_id ?>" id="add_video_link" class="row g-3 needs-validation" method="POST">
                                                <div class="col-12">
                                                    <label for="vidlink_platform" class="form-label">Video Platform</label>
                                                    <div class="input-group has-validation">
                                                        <select name="platform" class="form-control" id="vidlink_platform">
                                                            <option value="Youtube">Youtube</option>
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="vidlink_videolink" class="form-label">Video Link</label>
                                                    <div class="input-group has-validation">
                                                        <input type="text" name="video_link" class="form-control" id="vidlink_videolink" placeholder="https://www.--------------" required>
                                                        <div class="invalid-feedback">Please enter the Link to the Video</div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button class="btn w-100 text-white" style="background: #00C853;" id="vidlink_submit" name="add_video_link" type="submit">Submit</button>
                                                </div>
                                            </form>
                                    <?php
                                        } else {
                                    ?>
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                               <strong><?php echo $vid_uploadmsg ?></strong>
                                               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                   <?php $video_query = mysqli_query($link, "SELECT `id`, `video_path` FROM `videos` WHERE `business`='$id'");
                    $numberOfRows5 = mysqli_num_rows($video_query);
                    ?>
                   <?php if(isset($_GET['bus'])) {
                     if($sub_result['subscription_type']<3){
                       echo '<div class="text-center" style="font-weight: 600"><i class="bi bi-lock"></i> Availabe to Gold and Premium Subscribers only!</div>';
                     } else{
                       if($numberOfRows5==false){} else{  ?>
                         <p class="text-center mb-1">Product Videos</p>
                         <?php while($video_result = mysqli_fetch_assoc($video_query)) { ?>
                     <div class="mb-3">
                       <video width="100%" height="auto" controls>
                         <source src="<?php echo "../".$video_result['video_path'] ?>" type="video/mp4">
                       </video>
                     </div>
                      <form class="mb-5" role="form" method="post">
                        <input class="visually-hidden" type="tel" name="video_id" value="<?php echo $video_result['id'];?>">
                        <button name="remove_video" type="submit" class="btn btn-danger">Remove Video</button>
                        <?php if($sub_result['subscription_type']==3){ ?>
                          <a href="https://yenreach.com/optin.php" class="btn btn-warning"><i class="bi bi-lock"></i> Unlock to add more videos.</a>
                        <?php } ?>
                    </form>
                 <?php }} ?>
                   <!-- Category Form -->
                   <form role="form" method="post" enctype="multipart/form-data">

                     <div class="row mb-3 pt-3">
                       <div class="col-md-8 row">
                         <p class="text-center">Add Product Video</p>
                           <div class="mb-3">
                             <input class="form-control" type="file" id="formFile" name="business_video" lang="en" required/>
                             <div class="form-text">Max File Size: 5MB, File Formats: mp4, 3gp, avi, mov, mpeg</div>
                           </div>
                           <div class="mb-3">
                             <?php if(isset($_GET['bus'])) {
                               echo '<button id="add_button" type="submit" onclick="load(this);" class="btn text-white" style="background: #00C853;" name="add_video">Add Video</button>';
                             } else {
                               echo '<button id="add_button" type="submit" onclick="load(this);" class="btn text-white" style="background: #00C853;" name="add_video" disabled>Add Video</button>';
                             } ?>
                           </div>
                         </div>
                     </div>
                   </form><!-- End category Form -->
                     <?php }} ?>
                </div>

                   <div class="tab-pane fade pt-3" id="add-cv">
                     <form role="form" method="post" enctype="multipart/form-data">

                       <div class="row mb-3">
                         <div class="col-md-8 row">
                           <p class="text-center">Upload CV</p>
                             <div class="mb-3">
                               <input class="form-control" type="file" id="formFile" name="job_cv" lang="en" required>
                             </div>
                             <div class="mb-3">
                               <?php if(isset($_GET['bus'])) {
                                 echo '<button id="add_button" type="submit" class="btn text-white" style="background: #00C853;" name="save_cv">Upload CV</button>';
                               } else {
                                 echo '<button id="add_button" type="submit" class="btn text-white" style="background: #00C853;" name="save_cv" disabled>Upload CV</button>';
                               } ?>
                             </div>
                           </div>
                       </div>
                     </form><!-- End category Form -->
                     </div>

             </div>
           </div>

         </div>
       </div>
     <?php } ?>
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
   function load_it(e) {
     let val=document.getElementById('others');
     if(!(e.value === "others")) {
       val.innerHTML = '';
     }
     else {
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
           function load(e) {
             document.getElementById(add_button).innerText = 'Loading...';
           }
       </script>

 <?php
 require_once('includes/footer.php');
  ?>
