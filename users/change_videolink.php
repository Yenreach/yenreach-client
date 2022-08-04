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
    $gurl = "fetch_videolink_by_string_api.php?string=".$string;
    $links = perform_get_curl($gurl);
    if($links){
        if($links->status == "success"){
            $link = $links->data;
            
            if(isset($_POST['submit'])){
                $platform = !empty($_POST['platform']) ? (string)$_POST['platform'] : "";
                $video_link = !empty($_POST['video_link']) ? (string)$_POST['video_link'] : "";
                
                $purl = "update_business_video_link_api.php";
                $pdata = array(
                        'verify_string' => $link->verify_string,
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
                                "details" => "Business Video Changed successfully"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Video Changed successfully");
                        redirect_to("videos");
                    } else {
                        $message = $add_video->message;
                    }
                } else {
                    $message = "Video Adding Link Broken";
                }
            } else {
                $platform = $link->platform;
                $video_link = $link->real_link;
            }
        } else {
            die($links->message);
        }
    } else {
        die("Video Link Broken");
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
                            <li><a href="videos">Videos</a></li>
                            <li>Change Video</li>
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
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Video Link</h4>
                    </div>
                    <div class="card-body card-block">
                        <form action="change_videolink?<?php echo $string; ?>" role="form" method="POST" class="row g-3 needs-validation">
                            <div class="col-12 mb-2">
                                <label for="platform" class="form-label mt-1">Platform</label>
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
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>