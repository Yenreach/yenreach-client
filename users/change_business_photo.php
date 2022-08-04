<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    if(!$session->is_business_logged()){
        redirect_to("dashboard");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_business_photo_by_string_api.php?string=".$string;
    $photos = perform_get_curl($gurl);
    if($photos){
        if($photos->status == "success"){
            $picture = $photos->data;
            
            if(isset($_POST['submit'])){
                $file = !empty($_FILES['file']) ? $_FILES['file'] : array();
                if(!empty($file)){
                    if(($file['type'] == "image/jpg") || ($file['type'] == "image/jpeg") || ($file['type'] == "image/png") || ($file['type'] == "image/png")){
                        if(($file['size'] <= 204800) && ($file['size'] > 0)){
                            $purl = "update_business_photo_api.php";
                            $pdata = [
                                    'verify_string' => $picture->verify_string,
                                    'size' => $file['size']
                                ];
                            $add_photo = perform_post_curl($purl, $pdata);
                            if($add_photo){
                                if($add_photo->status == "success"){
                                    $add = $add_photo->data;
                                    
                                    $photo = new Photo();
                                    if(file_exists("../images/{$add->old_filename}.jpg")){
                                        $photo->destroy($add->old_filename);
                                    }
                                    if(file_exists("../images/thumbnails/{$add->old_filename}.jpg")){
                                        $photo->destroy_thumbnail($add->old_filename);
                                    }
                                    $photo->filename = $add->filename;
                                    $photo->load($file['tmp_name']);
                                    if($photo->save_compressed()){
                                        $photo->scale(70);
                                        $photo->save_thumbnail("jpg", 50, null);   
                                        
                                        $lpurl = "add_activity_log_api.php";
                                        $lpdata = [
                                                'agent_type' => "user",
                                                'agent_string' => $session->verify_string,
                                                'object_type' => "Businesses",
                                                'object_string' => $session->business_string,
                                                "activity" => "Update",
                                                "details" => "Business Photo was changed"
                                            ];
                                        perform_post_curl($lpurl, $lpdata);
                                        $session->message("Photo Change was successful");
                                        redirect_to("photos");
                                    } else {
                                        $message = "Wrong File Format";
                                    }
                                } else {
                                    $message = $add_photo->message;
                                }
                            } else {
                                $message = "Add Photo Link Broken";
                            }
                        } else {
                            $message = "File Size must not be more than 200KB";
                        }
                    } else {
                        $message = "Wrong File Format";
                    }
                } else {
                    $message = "No file was uploaded";
                }
            }
        } else {
            die($photos->message);
        }
    } else {
        die("Photos Link Broken");
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
                            <li><a href="photos">Photos</a></li>
                            <li>Change Photo</li>
                        </ol>
                        <h2>Business Photos</h2>
                        <p>Pictures Uploaded for this Business</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Photo</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card-block">
                        <form action="change_business_photo?<?php echo $string; ?>" enctype="multipart/form-data" class="row g-3 needs-validation" method="POST">
                            <div class="col-12">
                                <label for="file_upload" class="form-label mt-1">Photo</label>
                                <div class="input-group has-validation">
                                    <p>
                                        The Photo should not be more than 200KB. We further recommend that the aspect ratio of the Photo should be 1:1
                                        (This means the height and the width of the Photo should be the same length).The Image should also be in any of 
                                        JPG, JPEG, PNG or GIF format
                                    </p>
                                    <input type="file" accept="image/jpg, image/jpeg, image/png, image/gif" id="file_upload" class="form-control" name="file" required>
                                    <div class="invalid-feedback">Please select the Photo File</div>
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