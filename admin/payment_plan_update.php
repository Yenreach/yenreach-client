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
    $gurl = "fetch_payment_plan_by_string_api.php?string=".$string;
    $plans = perform_get_curl($gurl);
    if($plans){
        if($plans->status == "success"){
            $paymentplan = $plans->data;
            
            if(isset($_POST['submit'])){
                $plan = !empty($_POST['plan']) ? (string)$_POST['plan'] : "";
                $duration_type = !empty($_POST['duration_type']) ? (int)$_POST['duration_type'] : "";
                $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : "";
                $price = !empty($_POST['price']) ? (double)$_POST['price'] : "";
                $description = !empty($_POST['description']) ? (string)$_POST['description'] : "";
                
                $purl = "update_payment_plan_api.php";
                $pdata = [
                        'verify_string' => $paymentplan->verify_string,
                        'plan' => $plan,
                        'duration_type' => $duration_type,
                        'duration' => $duration,
                        'price' => $price,
                        'description' => $description
                    ];
                
                $update_plan = perform_post_curl($purl, $pdata);
                if($update_plan){
                    if($update_plan->status == "success"){
                        $add = $update_plan->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "PaymentPlans",
                                'object_string' => $add->verify_string,
                                "activity" => "Update",
                                "details" => "{$add->plan} being Updated"
                            ];
                                
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Payment Plan Update was successful!");
                        redirect_to("subscription?{$add->subscription_string}#payment_plans_section");
                    } else {
                        $message = $update_plan->message;
                    }
                } else {
                    $message = "Plan Update Link Broken";
                }
            } else {
                $plan = $paymentplan->plan;
                $duration_type = $paymentplan->duration_type;
                $duration = $paymentplan->duration;
                $price = $paymentplan->price;
                $description = $paymentplan->description;
            }
        } else {
            die($plans->message);
        }
    } else {
        die("Payment Plan Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Payment Plan</h4>
                            <p class="mb-0"><?php echo output_message($message); ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Components</a></li>
                            <li class="breadcrumb-item"><a href="business_subscriptions">Business Subscriptions</a></li>
                            <li class="breadcrumb-item"><a href="subscription?<?php echo $paymentplan->subscription_string; ?>"><?php echo $paymentplan->subscription; ?></a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Payment Plan</a></li>
                        </ol>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-9 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-primary"><?php echo $paymentplan->plan ?></h4>
                                <p><?php echo output_message($message); ?></p>
                            </div>
                            <div class="card-body card-block">
                                <form action="payment_plan_update?<?php echo $string ?>" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="plan" id="plan_plan" class="form-control" placeholder="Plan" value="<?php echo $plan; ?>">
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