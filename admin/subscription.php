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
            $package = $subscriptions->data;
            
            if(isset($_POST['submit'])){
                $planned = !empty($_POST['plan']) ? (string)$_POST['plan'] : "";
                $duration_type = !empty($_POST['duration_type']) ? (int)$_POST['duration_type'] : "";
                $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : "";
                $price = !empty($_POST['price']) ? (double)$_POST['price'] : "";
                $description = !empty($_POST['description']) ? (string)$_POST['description'] : "";
                
                $purl = "add_payment_plan_api.php";
                $pdata = [
                        'subscription_string' => $package->verify_string,
                        'plan' => $planned,
                        'duration_type' => $duration_type,
                        'duration' => $duration,
                        'price' => $price,
                        'description' => $description
                    ];
                
                $add_plan = perform_post_curl($purl, $pdata);
                if($add_plan){
                    if($add_plan->status == "success"){
                        $add = $add_plan->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "PaymentPlans",
                                'object_string' => $add->verify_string,
                                "activity" => "Creation",
                                "details" => "{$add->plan} being added to the {$package->package} Subscription Package"
                            ];
                                
                        perform_post_curl($lpurl, $lpdata);
                        
                        $session->message("Payment Plan successfully added");
                        redirect_to("subscription?{$string}#payment_plans_section");
                    } else {
                        $message = $add_plan->errors;
                    }
                } else {
                    $message = "Payment Plan Addition Link Broken";
                }
            } else {
                $planned = "";
                $duration_type = "";
                $duration = $type;
                $price = "";
                $description = "";
            }
            
            $pgurl = "fetch_subscription_plans_api.php?string=".$package->verify_string;
            $plans = perform_get_curl($pgurl);
            if($plans){
                
            } else {
                die("Plans Link Broken");
            }
        } else {
            die($subscriptions->message);
        }
    } else {
        die("Subscription Link Broken");
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
                            <li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $package->package; ?></a></li>
                        </ol>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-primary"><?php echo $package->package; ?></h4>
                                <p><?php echo output_message($message); ?></p>
                            </div>
                            <div class="card-body card-block text-dark">
                                <p><?php echo $package->description; ?></p>
                                <p>
                                    <strong>Features</strong>
                                    <br />
                                    <br />
                                    Number of Photos: <?php echo $package->photos; ?>
                                    <br />
                                    Number of Videos: <?php echo $package->videos; ?>
                                    <br />
                                    Number of Branches: <?php echo $package->branches; ?>
                                    <br />
                                    Slider Option: <?php if($package->slider == 1){ echo "Yes"; } else { echo "No"; }; ?>
                                    <br />
                                    Social Media Option: <?php if($package->socialmedia == 1){ echo "Yes"; } else { echo "No"; } ?>
                                </p>
                                <p>
                                    <a href="subscription_edit?<?php echo $package->verify_string; ?>" class="btn btn-primary">Edit</a>
                                    &nbsp;
                                    <a href="subscription_delete?<?php echo $package->verify_string ?>" class="btn btn-danger"
                                    onclick="return confirm('Do you really want to Delete this Subscription Package?')">Delete</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="payment_plans_section">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-primary">Payment Plans</h4>
                                <?php echo output_message($message); ?>
                            </div>
                            <div class="card-body">
                                <div class="table-responnsive text-dark">
                                    <table class="table table-responsive-sm table-striped table-bordered text-dark">
                                        <thead>
                                            <tr>
                                                <th>Plan</th>
                                                <th>Duration</th>
                                                <th>Price(NGN)</th>
                                                <th>Remarks</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($plans->data as $plan){
                                            ?>
                                                    <tr>
                                                        <td><?php echo $plan->plan; ?></td>
                                                        <td><?php
                                                            echo $plan->duration." ";
                                                            echo classify_duration($plan->duration_type);
                                                            if($plan->duration > 1){
                                                                echo "s";
                                                            }
                                                        ?></td>
                                                        <td><?php echo number_format($plan->price, 2); ?></td>
                                                        <td><?php echo $plan->description; ?></td>
                                                        <td><a href="payment_plan_update?<?php echo $plan->verify_string; ?>" class="btn btn-primary">Edit</a></td>
                                                        <td><a href="payment_plan_delete?<?php echo $plan->verify_string; ?>" class="btn btn-danger"
                                                        onclick="return confirm('Do you really want to delete this Plan?')">Delete</a></td>
                                                    </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add Payment Plan</h4>
                                <?php echo output_message($message); ?>
                            </div>
                            <div class="card-body">
                                <form action="subscription?<?php echo $string ?>" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="plan" id="plan_plan" class="form-control" placeholder="Plan" value="<?php echo $planned; ?>">
                                    </div>
                                    <div class="form-group">
                                        <select name="duration_type" id="plan_durationtype" class="form-control">
                                            <option value="">--Duration Type--</option>
                                            <?php trial_type($duration_type); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="duration" id="plan_duration" class="form-control" placeholder="Duration" value="<?php echo $duration; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="price" id="plan_price" class="form-control" placeholder="Price" value="<?php echo $price; ?>">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="description" id="plan_description" class="form-control" placeholder="Remarks"><?php echo $description; ?></textarea>
                                    </div>
                                    <div class="form-group text-center">
                                        <button type="submit" name="submit" id="plan_submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>