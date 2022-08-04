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
        $platform = !empty($_POST['platform']) ? (string)$_POST['platform'] : "";
        $video_link = !empty($_POST['video_link']) ? (string)$_POST['video_link'] : "";
        
        $purl = "add_businessvideolink_api.php";
        $pdata = array(
                'user_string' => $session->verify_string,
                'business_string' => $session->business_string,
                'platform' => $platform,
                'video_link' => $video_link
            );
            
        $add_video = perform_post_curl($purl, $pdata);
        if($add_video){
            if($add_video->status == "success"){
                $video = $add_video->data;
                
                 $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => "user",
                        'agent_string' => $session->verify_string,
                        'object_type' => "Businesses",
                        'object_string' => $session->business_string,
                        "activity" => "Update",
                        "details" => "Business Video Added successfully"
                    ];
                perform_post_curl($lpurl, $lpdata);
                $session->message("Video added to Business succesfully");
                redirect_to("videos");
            } else {
                $message = $add_video->message;
            }
        } else {
            $message = "Video Adding Link Broken";
        }
    } else {
        $platform = "";
        $video_link = "";
    }
    
    $gurl = "fetch_business_videolinks_api.php?string=".$session->business_string;
    $links = perform_get_curl($gurl);
    if(!empty($links)){
        if($links->status == "success"){
            $counted = count($links->data);
        } else {
            $counted = 0;
        }
    } else {
        die("Videos Link Broken");
    }
    
    $sgurl = "fetch_business_latest_subscription_api.php?string=".$session->business_string;
    $subscribes = perform_get_curl($sgurl);
    if($subscribes){
        
    } else {
        die("Subscrption Check Link Broken");
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
                            <li>Videos</li>
                        </ol>
                        <h2>Business Videos</h2>
                        <p>Links to your Uploaded Videos Online</p>
                        <?php
                            echo output_message($message);
                        ?>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body card-block">
                        <div class="table-responsive text-dark">
                            <?php
                                if($links->status == "success"){
                            ?>
                                    <table class="table table-responsive-sm table-striped text-dark">
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
                                                foreach($links->data as $link){
                                            ?>
                                                    <tr>
                                                        <td><?php
                                                            if($link->platform == "YouTube"){
                                                        ?>
                                                                <iframe height="200" width="auto" src="<?php echo $link->video_link; ?>" title="YouTube video player" 
                                                                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                allowfullscreen></iframe>
                                                        <?php
                                                            }
                                                        ?></td>
                                                        <td><?php echo $link->platform; ?></td>
                                                        <td><?php echo $link->real_link; ?></td>
                                                        <td><a href="change_videolink?<?php echo $link->verify_string; ?>" class="text-primary">Change</a></td>
                                                        <td><a href="delete_videolink?<?php echo $link->verify_string; ?>" class="text-danger"
                                                        onclick="return confirm('Do you really want to delete this Video Link?')">Delete</a></td>
                                                    </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                            <?php
                                } else {
                                    echo '<p>'.$links->message.'</p>';
                                }
                            ?>    
                        </div>
                    </div>
                </div>
            </div>  
        </div>
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Video Link</h4>
                        <p>Add the Link to your Video on YouTube</p>
                    </div>
                    <div class="card-body card-block">
                        <?php
                            if($subscribes->status == "success"){
                                $sub = $subscribes->data;
                                $time = time();
                                if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                                    $pgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                                    $subscriptions = perform_get_curl($pgurl);
                                    if(($subscriptions) && ($subscriptions->status == "success")){
                                        $subscription = $subscriptions->data;
                                        if($counted < $subscription->videos){
                        ?>
                                            <form action="videos" role="form" method="POST" class="row g-3 needs-validation">
                                                <div class="col-12 mb-2">
                                                    <label for="LGA" class="form-label mt-1">Platform</label>
                                                    <div class="input-group has-validation">
                                                        <select name="platform" id="platform" class="form-control" required>
                                                            <option value="YouTube">YouTube</option>
                                                        </select>
                                                        <div class="invalid-feedback">Please provide the Platform the Video is Hosted on</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="video_link" class="form-label mt-1">Video Link</label>
                                                    <div class="input-group has-validation">
                                                        <input type='url' name="video_link" class="form-control" value="<?php echo $video_link; ?>" required>
                                                        <div class="invalid-feedback">Please Provide the Link to the Video.</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <button class="btn w-100 text-white py-2" style="background: #00C853;" id="video_submit" name="submit" type="submit">Submit</button>
                                                </div>
                                            </form>                  
                        <?php
                                        } else {
                        ?>
                                            <p>
                                                You have reached the Limit of the Videos that can be added for this Business.
                                                To add more Videos, please visit the <a href="subscription_packages" class="text-primary">
                                                    Subscription Packages Page
                                                </a>
                                            </p>                        
                        <?php
                                        }
                                    } else {
                        ?>
                                        <p>
                                            For you to add a Video, you'll need to have subscribed this Business to a Subscription Package.
                                            To learn more please visit the <a href="subscription_packages" class="text-primary">
                                                Subscription Packages Page
                                            </a>
                                        </p>
                        <?php
                                    }
                                } else {
                        ?>
                                    <p>
                                        For you to add a Video, you'll need to have subscribed this Business to a Subscription Package.
                                        To learn more please visit the <a href="subscription_packages" class="text-primary">
                                            Subscription Packages Page
                                        </a>
                                    </p>
                        <?php
                                }
                            } else {
                        ?>
                                <p>
                                    For you to add a Video, you'll need to have subscribed this Business to a Subscription Package.
                                    To learn more please visit the <a href="subscription_packages" class="text-primary">
                                        Subscription Packages Page
                                    </a>
                                </p>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>