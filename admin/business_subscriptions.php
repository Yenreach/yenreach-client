<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    if(isset($_POST['submit'])){
        $packaged = !empty($_POST['package']) ? (string)$_POST['package'] : "";
        $description = !empty($_POST['description']) ? (string)$_POST['description'] : "";
        $position = !empty($_POST['position']) ? (int)$_POST['position'] : "";
        $photos = !empty($_POST['photos']) ? (int)$_POST['photos'] : "";
        $videos = !empty($_POST['videos']) ? (int)$_POST['videos'] : "";
        $slider = !empty($_POST['slider']) ? (int)$_POST['slider'] : "";
        $socialmedia = !empty($_POST['socialmedia']) ? (int)$_POST['socialmedia'] : "";
        $branches = !empty($_POST['branches']) ? (int)$_POST['branches'] : "";
        
        $purl = "add_business_subscription_api.php";
        $pdata = [
                'package' => $packaged,
                'description' => $description,
                'position' => $position,
                'photos' => $photos,
                'videos' => $videos,
                'slider' => $slider,
                'socialmedia' => $socialmedia,
                'branches' => $branches
            ];
        $add_subscription = perform_post_curl($purl, $pdata);
        if($add_subscription){
            if($add_subscription->status == "success"){
                $subscription = $add_subscription->data;
                
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => $session->user_type,
                        'agent_string' => $session->verify_string,
                        'object_type' => "BusinessSubscriptions",
                        'object_string' => $subscription->verify_string,
                        "activity" => "Creation",
                        "details" => "{$subscription->package} being added to the Subscription List"
                    ];
                        
                perform_post_curl($lpurl, $lpdata);
                
                $session->message($subscription->package." has been added to the Business Subscription Packages");
                redirect_to("business_subscriptions");
            } else {
                $message = $add_subscription->message;
            }
        } else {
            $message = "Subscription Addition Link Broken";
        }
    } else {
        $packaged = "";
        $description = "";
        $position = "";
        $photos = "";
        $videos = "";
        $slider = "";
        $socialmedia = "";
        $branches = "";
    }
    
    $gurl = "fetch_all_business_subscriptions_api.php";
    $subscriptions = perform_get_curl($gurl);
    if($subscriptions){
        
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
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Business Subscriptions</a></li>
                        </ol>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-primary">Business Subscriptions</h4>
                                <p><?php echo output_message($message); ?></p>
                            </div>
                            <div class="card-body card-block">
                                <div class="table-responsive text-dark">
                                    <?php
                                        if($subscriptions->status == "success"){
                                            $counted = count($subscriptions->data);
                                    ?>
                                            <table class="table table-responsive-sm table-striped table-bordered text-dark">
                                                <thead>
                                                    <tr>
                                                        <th>Package</th>
                                                        <th>Short Description</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach($subscriptions->data as $package){
                                                            if(!empty($package->description)){
                                                                $arrayed = array();
                                                                $desc_array = explode(' ', $package->description);
                                                                $desc_count = count($desc_array);
                                                                if($desc_count >= 20){
                                                                    for($i=0; $i<=19; $i++){
                                                                        $arrayed[] = $desc_array[$i];
                                                                    }   
                                                                } else {
                                                                    for($i=0; $i<=$desc_count-1; $i++){
                                                                        $arrayed[] = $desc_array[$i];
                                                                    }
                                                                }
                                                                $pdescription = join(' ', $arrayed);
                                                            } else {
                                                                $description = "";
                                                            }
                                                    ?>
                                                            <tr>
                                                                <td><?php echo $package->package ?></td>
                                                                <td><?php echo $pdescription; ?></td>
                                                                <td><a href="subscription?<?php echo $package->verify_string; ?>" class="text-primary">Details</a></td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                    <?php
                                        } else {
                                            $counted = 0;
                                            echo $subscriptions->message;
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
                                <h4 class="card-title text-primary">Add Subscription Package</h4>
                                <p><?php echo output_message($message); ?></p>
                            </div>
                            <div class="card-body card-block">
                                <form action="business_subscriptions" id="sub_add" method="POST">
                                    <div class="form-group">
                                        <select name="position" id="sub_position" class="form-control">
                                            <option value="">--Position--</option>
                                            <?php
                                                for($i=1; $i<=$counted+1; $i++){
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
                                        <input type="text" name="package" id="sub_package" class="form-control" placeholder="Package" value="<?php echo $packaged; ?>">
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
                                            <option value="0" <?php if($slider === 0){ echo "selected"; } ?>>No</option>
                                            <option value="1" <?php if($slider == 1){ echo "selected"; } ?>>Yes</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select name="socialmedia" id="sub_slider" class="form-control">
                                            <option value="">--Social Media Option--</option>
                                            <option value="0" <?php if($socialmedia === 0){ echo "selected"; } ?>>No</option>
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