<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    if(isset($_POST['submit'])){
        $file = !empty($_FILES['file']) ? $_FILES['file'] : array();
        if(!empty($file)){
            if(($file['type'] == "image/jpg") || ($file['type'] == "image/jpeg") || ($file['type'] == "image/png") || ($file['type'] == "image/png")){
                if(($file['size'] <= 204800) && ($file['size'] > 0)){
                    $purl = "change_user_profile_photo_api.php";
                    $pdata = array(
                            'verify_string' => $session->verify_string
                        );
                    
                    $change_photo = perform_post_curl($purl, $pdata);
                    if($change_photo){
                        if($change_photo->status == "success"){
                            $change = $change_photo->data;
                            
                            $photo = new Photo();
                            if(!empty($change->old_filename)){
                                if(file_exists("../".$change->old_filename)){
                                    $photo->destroy_path($change->old_filename);
                                }
                            }
                            $photo->filename = $change->filename;
                            $photo->load($file['tmp_name']);
                            if($photo->save_compressed()){
                                $lpurl = "add_activity_log_api.php";
                                $lpdata = [
                                        'agent_type' => "user",
                                        'agent_string' => $session->verify_string,
                                        'object_type' => "Users",
                                        'object_string' => $session->verify_string,
                                        "activity" => "Update",
                                        "details" => "Profile Photo was changed"
                                    ];
                                perform_post_curl($lpurl, $lpdata);
                                $session->message("Photo Change was successful");
                                redirect_to("user_profile");
                            } else {
                                $message = "Wrong Uploaded File Format";
                            }
                        } else {
                            $message = $change_photo->message;
                        }
                    } else {
                        $message = "Profile Photo Link Broken";
                    }
                } else {
                    $message = "File must NOT be more than 200KB";
                }
            } else {
                $message = "Wrong File Format";
            }
        } else {
            $message = "No file was uploaded";
        }
    }
    
    include_portal_template("header.php");
?>

    <main id="main" class="main">
        <div class="row">
            <div class="container">
                <!-- ======= Breadcrumbs ======= -->
                <section class="breadcrumbs">
                    <div class="container">
                        <ol>
                            <li><a href="dashboard">Dashboard</a></li>
                            <li><a href="user_profile">Profile</a></li>
                            <li>Upload Profile Picture</li>
                        </ol>
                        <h2>Upload Profile Picture</h2>
                        <p>Upload a Profile Photo for your Yenreach Account</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Upload Photo</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card-block">
                        <form action="change_profile_picture" enctype="multipart/form-data" class="row g-3 needs-validation" method="POST">
                            <div class="col-12">
                                <label for="file_upload" class="form-label mt-1">Photo</label>
                                <div class="input-group has-validation">
                                    <p>
                                        The Photo should not be more than 200KB. We further recommend that the aspect ratio of the Photo should be 1:1
                                        (This means the height and the width of the Photo should be the same length). The Image should also be in any of 
                                        JPG, JPEG, PNG or GIF format
                                    </p>
                                    <input type="file" accept="image/jpg, image/jpeg, image/png, image/gif" id="file_upload" class="form-control" name="file" required>
                                    <div class="invalid-feedback">Please select the Image File</div>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button class="btn w-100 text-white py-2" style="background: #00C853;" id="photo_submit" name="submit" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>