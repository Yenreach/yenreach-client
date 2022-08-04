<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    if(isset($_POST['submit'])){
        $facilitied = !empty($_POST['facility']) ? (string)$_POST['facility'] : "";
        $minimum_subscription = !empty($_POST['minimum_subscription']) ? (string)$_POST['minimum_subscription'] : "";
        
        $purl = "add_facility_api.php";
        $pdata = [
                'facility' => $facilitied,
                'minimum_subscription' => $minimum_subscription
            ];
        
        $add_facility = perform_post_curl($purl, $pdata);
        if($add_facility){
            if($add_facility->status == "success"){
                $add = $add_facility->data;
                
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => $session->user_type,
                        'agent_string' => $session->verify_string,
                        'object_type' => "Facilities",
                        'object_string' => $add->verify_string,
                        "activity" => "Create",
                        "details" => "{$add->facility} added as a Business Facility"
                    ];
                perform_post_curl($lpurl, $lpdata);
                $session->message("Facility was added successfully!");
                redirect_to("business_facilities");
            } else {
                $message = $add_facility->message;
            }
        } else {
            $message = "Add Facility Link Broken";
        }
    } else {
        $facilitied = "";
        $minimum_subscription = "";
    }
    
    $gurl = "fetch_all_facilities_api.php";
    $facilities = perform_get_curl($gurl);
    if($facilities){
        
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
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Business Facilities</a></li>
                        </ol>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-primary">Business Facilities</h4>
                                <p><?php echo output_message($message); ?></p>
                            </div>
                            <div class="card-body card-block">
                                <div class="table-responsive text-dark">
                                    <?php
                                        if($facilities->status == "success"){
                                    ?>
                                            <table class="table table-responsive-sm table-striped table-bordered text-dark">
                                                <thead>
                                                    <tr>
                                                        <th>Facility</th>
                                                        <th>Minimum Subscription Package</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach($facilities->data as $facility){
                                                    ?> 
                                                            <tr>
                                                                <td><?php echo $facility->facility; ?></td>
                                                                <td><?php echo $facility->subscription; ?></td>
                                                                <td><a href="change_facility?<?php echo $facility->verify_string; ?>" class="btn btn-primary">Change</a></td>
                                                                <td><a href="delete_facility?<?php echo $facility->verify_string; ?>" class="btn btn-danger"
                                                                onclick="return confirm('Do you really want to delete this Business Facility?')">Delete</a></td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                    <?php
                                        } else {
                                            echo '<p>'.$facilities->message.'</p>';
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
                                <h4 class="card-title">Add Business Facility</h4>
                                <?php echo output_message($message); ?>
                            </div>
                            <div class="card-body card-block">
                                <form action="business_facilities" id="sub_add" method="POST">
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