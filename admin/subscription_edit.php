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
    $gurl = "fetch_business_subscription_by_string_api.php?string=".$string;
    $subscriptions = perform_get_curl($gurl);
    if($subscriptions){
        if($subscriptions->status == "success"){
            $sub = $subscriptions->data;
            
            if(isset($_POST['submit'])){
                $package = !empty($_POST['package']) ? (string)$_POST['package'] : "";
                $description = !empty($_POST['description']) ? (string)$_POST['description'] : "";
                $position = !empty($_POST['position']) ? (int)$_POST['position'] : "";
                $photos = !empty($_POST['photos']) ? (int)$_POST['photos'] : "";
                $videos = !empty($_POST['videos']) ? (int)$_POST['videos'] : "";
                $slider = !empty($_POST['slider']) ? (int)$_POST['slider'] : "";
                $socialmedia = !empty($_POST['socialmedia']) ? (int)$_POST['socialmedia'] : "";
                $branches = !empty($_POST['branches']) ? (int)$_POST['branches'] : "";
                
                $purl = "update_business_subscription_api.php";
                $pdata = [
                        'verify_string' => $sub->verify_string,
                        'package' => $package,
                        'description' => $description,
                        'position' => $position,
                        'photos' => $photos,
                        'videos' => $videos,
                        'slider' => $slider,
                        'socialmedia' => $socialmedia,
                        'branches' => $branches
                    ];
                
                $update_subscription = perform_post_curl($purl, $pdata);
                if($update_subscription){
                    if($update_subscription->status == "success"){
                        $subscription = $update_subscription->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "BusinessSubscriptions",
                                'object_string' => $subscription->verify_string,
                                "activity" => "Update",
                                "details" => "Updating the Subscription Package List"
                            ];
                                
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Subscription Package Update successful");
                        redirect_to("subscription?".$subscription->verify_string);
                    } else {
                        $message = $update_subscription->message;
                    }
                } else {
                    $message = "Subscription Update Link Broken";
                }
            } else {
                $package = $sub->package;
                $description = $sub->description;
                $position = $sub->position;
                $photos = $sub->photos;
                $videos = $sub->videos;
                $slider = $sub->slider;
                $socialmedia = $sub->socialmedia;
                $branches = $sub->branches;
            }
        } else {
            die($subscriptions->message);
        }
    } else {
        die("Subscription Link Broken");
    }
    
    $gurl = "fetch_all_business_subscriptions_api.php";
    $packagings = perform_get_curl($gurl);
    if($packagings){
        if($packagings->status == "success"){
            $counted = count($packagings->data);
        } else {
            die($packagings->message);
        }
    } else {
        die("Subscriptions Link Broken");
    }
    
    include_admin_template("header.php");
?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Business Subscriptions</h4>
                            <p class="mb-0">Different Subscription Packages that a Business can Subscribe to.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Components</a></li>
                            <li class="breadcrumb-item"><a href="business_subscriptions">Business Subscriptions</a></li>
                            <li class="breadcrumb-item"><a href="business_subscriptions"><?php echo $sub->package; ?></a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Packagee</a></li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-primary">Add Subscription Package</h4>
                                <p><?php echo output_message($message); ?></p>
                            </div>
                            <div class="card-body card-block">
                                <form action="subscription_edit?<?php echo $string; ?>" id="sub_edit" method="POST">
                                    <div class="form-group">
                                        <select name="position" id="sub_position" class="form-control">
                                            <option value="">--Position--</option>
                                            <?php
                                                for($i=1; $i<=$counted; $i++){
                                                    echo '<option value="'.$i.'"';
                                                    if($position == $i){
                                                        echo " selected";
                                                    }
                                                    echo '>'.$i.'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="package" id="sub_package" class="form-control" placeholder="Package" value="<?php echo $package; ?>">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="description" id="sub_description" class="form-control" rows="5" placeholder="Description"><?php echo !empty($description) ? $description : ""; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="photos" id="sub_photos" class="form-control" placeholder="Number of Photos" value="<?php echo $photos; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="videos" id="sub_videos" class="form-control" placeholder="Number of Videos" value="<?php echo $videos; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="branches" id="sub_branches" class="form-control" placeholder="Number of Branches" value="<?php echo $branches; ?>">
                                    </div>
                                    <div class="form-group">
                                        <select name="slider" id="sub_slider" class="form-control">
                                            <option value="">--Slider Option--</option>
                                            <option value="0" <?php if($slider == 0){ echo "selected"; } ?>>No</option>
                                            <option value="1" <?php if($slider == 1){ echo "selected"; } ?>>Yes</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select name="socialmedia" id="sub_slider" class="form-control">
                                            <option value="">--Social Media Option--</option>
                                            <option value="0" <?php if($socialmedia == 0){ echo "selected"; } ?>>No</option>
                                            <option value="1" <?php if($socialmedia == 1){ echo "selected"; } ?>>Yes</option>
                                        </select>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" name="submit" id="sub_submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php include_admin_template("footer.php"); ?>