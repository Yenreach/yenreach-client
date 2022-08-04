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
    $gurl = "fetch_facility_by_string_api.php?string=".$string;
    $facilities = perform_get_curl($gurl);
    if($facilities){
        if($facilities->status == "success"){
            $facility = $facilities->data;
            
            if(isset($_POST['submit'])){
                $facilitied = !empty($_POST['facility']) ? (string)$_POST['facility'] : "";
                $minimum_subscription = !empty($_POST['minimum_subscription']) ? (string)$_POST['minimum_subscription'] : "";
                
                $purl = "update_facility_api.php";
                $pdata = [
                        'verify_string' => $facility->verify_string,
                        'facility' => $facilitied,
                        'minimum_subscription' => $minimum_subscription
                    ];
                
                $update_facility = perform_post_curl($purl, $pdata);
                if($update_facility){
                    if($update_facility->status == "success"){
                        $update = $update_facility->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "Facilities",
                                'object_string' => $update_facility->verify_string,
                                "activity" => "Update",
                                "details" => "{$update->facility} was edited"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Facility Update successful");
                        redirect_to("business_facilities");
                    } else {
                        $message = $update_facility->message;
                    }
                } else {
                    $message = "Facility Update Link Broken";
                }
            } else {
                $facilitied = $facility->facility;
                $minimum_subscription = $facility->minimum_subscription;
            }
        } else {
            die($facilities->message);
        }
    } else {
        die("Facilities Link Broken");
    }
    
    $sgurl = "fetch_all_business_subscriptions_api.php";
    $subscriptions = perform_get_curl($sgurl);
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
                            <h4>Business Facilities</h4>
                            <p class="mb-0">Different Facilities available to a Business.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Components</a></li>
                            <li class="breadcrumb-item"><a href="business_facilities">Business Facilities</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Facility</a></li>
                        </ol>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-9 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Business Facility</h4>
                                <?php echo output_message($message); ?>
                            </div>
                            <div class="card-body card-block">
                                <form action="change_facility?<?php echo $string; ?>" id="sub_add" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="facility" id="fac_facility" class="form-control" placeholder="Facility" value="<?php echo $facilitied; ?>">
                                    </div>
                                    <div class="form-group">
                                        <select name="minimum_subscription" id="fac_subs" class="form-control">
                                            <option value="">--Minimum Subscription Package--</option>
                                            <?php
                                                if($subscriptions->status == "success"){
                                                    foreach($subscriptions->data as $subscription){
                                                        echo '<option value="'.$subscription->verify_string.'"';
                                                        if($minimum_subscription == $subscription->verify_string){
                                                            echo ' selected';
                                                        }
                                                        echo '>'.$subscription->package.'</option>';
                                                    }
                                                }
                                            ?>
                                            <option value="free" <?php if($minimum_subscription == "free"){ echo 'selected'; } ?>>Free Package</option>
                                        </select>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" name="submit" id="fac_submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php include_admin_template("footer.php"); ?>