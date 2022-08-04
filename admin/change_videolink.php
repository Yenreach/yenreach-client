<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
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
                                'agent_type' => "admin",
                                'agent_string' => $session->verify_string,
                                'object_type' => "Businesses",
                                'object_string' => $video->business_string,
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
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4><?php echo $link->business; ?></h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Businesses</a></li>
                        <li class="breadcrumb-item"><a href="all_businesses">All Businesses</a></li>
                        <li class="breadcrumb-item"><a href="business?<?php echo $link->business_string; ?>"><?php echo $link->business; ?></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Change Video</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Change Business Video</h4>
                        </div>
                        <div class="card-body card-block">
                            <form action="change_videolink?<?php echo $string; ?>" method="POST">
                                <div class="form-group">
                                    <label for="platform" class="form-label">Platform</label>
                                    <select name="platform" id="platform" class="form-control" required>
                                        <option value="YouTube">YouTube</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="video_link" class="form-label">Video Link</label>
                                    <input type='url' name="video_link" class="form-control" value="<?php echo $video_link; ?>" required>
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                        <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include_admin_template("footer.php"); ?>