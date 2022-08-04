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
    
    if(isset($_POST['submit'])){
        $file = !empty($_FILES['file']) ? $_FILES['file'] : array();
        if(!empty($file)){
            if(($file['type'] == "image/jpg") || ($file['type'] == "image/jpeg") || ($file['type'] == "image/png") || ($file['type'] == "image/png")){
                if(($file['size'] <= 204800) && ($file['size'] > 0)){
                    $purl = "add_business_photo_api.php";
                    $pdata = [
                            'user_string' => $session->verify_string,
                            'business_string' => $session->business_string,
                            'size' => $file['size']
                        ];
                    $add_photo = perform_post_curl($purl, $pdata);
                    if($add_photo){
                        if($add_photo->status == "success"){
                            $add = $add_photo->data;
                            
                            $photo = new Photo();
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
                                        "details" => "Business Photo Added successfully"
                                    ];
                                perform_post_curl($lpurl, $lpdata);
                                $session->message("Photo added to Business succesfully");
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
    
    $gurl = "fetch_business_photos_api.php?string=".$session->business_string;
    $photos = perform_get_curl($gurl);
    if($photos){
        if($photos->status == "success"){
            $counted = count($photos->data);
        } else {
            $counted = 0;
        }
    } else {
        die("Business Photo Links Broken");
    }
    
    $sgurl = "fetch_business_latest_subscription_api.php?string=".$session->business_string;
    $subscribes = perform_get_curl($sgurl);
    if($subscribes){
        
    } else {
        die("Subscription Check Link Broken");
    }
    
    include_portal_template("header.php");
?>

    
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');
            .card{
              background: #fff;
              margin: 5px;
              box-shadow: 0 5px 25px rgb(1 1 1 / 20%);
              border-radius: 5px;
              overflow: hidden;
            }
            
            .card-image{
              background-color: none;
              width: 100%;
              position: relative;
              padding-top: 100%;
            }
            
            .card-img {
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                background-size:cover;
                background-repeat: no-repeat;
                background-position: center center;
            }
        </style>
    <main id="main" class="main">
        <div class="row">
            <div class="container">
                <!-- ======= Breadcrumbs ======= -->
                <section class="breadcrumbs">
                    <div class="container">
                        <ol>
                            <li><a href="dashboard">Dashboard</a></li>
                            <li>Photos</li>
                        </ol>
                        <h2>Business Photos</h2>
                        <p>Pictures Uploaded for this Business</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <?php
                if($photos->status == "success"){
            ?>
                    <div class="card-content d-flex flex-wrap flex-lg-wrap justify-content-start align-items-start">
                        <?php
                            foreach($photos->data as $photo){
                        ?>
                                <div class="col-12 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                    <div class="card">
                                        <div class="card-image">
                                            <div class="card-img" style="background-image: url(../<?php echo $photo->filepath; ?>)">&nbsp;</div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="change_business_photo?<?php echo $photo->verify_string ?>" class="btn btn-primary mb-2">Change</a>
                                            <a href="delete_business_photo?<?php echo $photo->verify_string ?>" class="btn btn-danger mb-2"
                                            onclick="return confirm('Do you really want to delete this Photo?')">Delete</a>
                                        </div>
                                    </div>
                                </div>   
                        <?php
                            }
                        ?>
                    </div>
            <?php
                } else {
            ?>
                    <div class="col-12">
                        <div class="text-light">
                            <div class="card-body card-block">
                                <p><?php echo $photos->message; ?></p>
                            </div>
                        </div>
                    </div>
            <?php
                }
            ?>
        </div>
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Photo</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card-block">
                        <?php
                            if($counted < 2){
                                $upload = "yes";
                            } else {
                                if($subscribes->status == "success"){
                                    $sub = $subscribes->data;
                                    $time = time();
                                    if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                                        $pgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                                        $subscriptions = perform_get_curl($pgurl);
                                        if(($subscriptions) && ($subscriptions->status == "success")){
                                            $subscription = $subscriptions->data;
                                            if($counted < $subscription->photos){
                                                $upload = "yes";
                                            } else {
                                                $upload = "no";
                                                $the_message = "You have reached the limit of your Photo Uploads for your Subscription Package. ";
                                                $the_message .= "To learn more, please visit <a href=\"subscription_packages\">Subscription Packages Page.</a>";
                                            }
                                        } else {
                                            $upload = "no";
                                            $the_message = "To upload more photos, you need to need to subscribe this Business to a Subscription Package. ";
                                            $the_message .= "To learn more, please visit <a href=\"subscription_packages\">Subscription Packages Page.</a>";    
                                        }
                                    } else {
                                        $upload = "no";
                                        $the_message = "To upload more photos, you need to need to subscribe this Business to a Subscription Package. ";
                                        $the_message .= "To learn more, please visit <a href=\"subscription_packages\">Subscription Packages Page.</a>";    
                                    }
                                } else {
                                    $upload = "no";
                                    $the_message = "To upload more photos, you need to need to subscribe this Business to a Subscription Package. ";
                                    $the_message .= "To learn more, please visit <a href=\"subscription_packages\">Subscription Packages Page.</a>";
                                }
                            }
                            if($upload == "yes"){
                        ?>
                                <form action="photos" enctype="multipart/form-data" class="row g-3 needs-validation" method="POST">
                                    <div class="col-12">
                                        <label for="file_upload" class="form-label mt-1">
                                            The Photo should not be more than 200KB. We further recommend that the aspect ratio of the Photo should be 1:1
                                            (This means the height and the width of the Photo should be the same length).
                                        </label>
                                        <div class="input-group has-validation">
                                            <input type="file" accept="image/jpg, image/jpeg, image/png, image/gif" id="file_upload" class="form-control" name="file" required>
                                            <div class="invalid-feedback">Please select the State</div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button class="btn w-100 text-white py-2" style="background: #00C853;" id="photo_submit" name="submit" type="submit">Submit</button>
                                    </div>
                                </form>
                        <?php
                            } else {
                                echo "<p>".$the_message."</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>