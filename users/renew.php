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
    $gurl = "fetch_payment_plan_by_string_api.php?string=".$string;
    $plans = perform_get_curl($gurl);
    if($plans){
        if($plans->status == "success"){
            $plan = $plans->data;
            
        } else {
            die($plans->message);
        }
    } else {
        die("Payment Plan Link Broken");
    }
    
    $bgurl = "fetch_business_by_string_api.php?string=".$session->business_string;
    $businesses = perform_get_curl($bgurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
            if($business->reg_stage == 4){
                
            } else {
                $session->message("A Business not yet approved CANNOT subscribe to any Subscription Package");
                redirect_to("subscription_details?".$plan->subscription_string."/".$plan->subscription.".html");
            }
        } else {
            die($businesses->message);
        }
    } else {
        die("Business Link Broken");
    }
    
    $sgurl = "fetch_business_latest_subscription_api.php?string=".$session->business_string;
    $subscribes = perform_get_curl($sgurl);
    if($subscribes){
        $subscribe = $subscribes->data;
        
        if($subscribe->subscription_string == $plan->subscription_string){
            $time = time();
            
            if($subscribe->true_expiry >= $time){
                
            } else {
                redirect_to("subscribe?{$string}");
            }
        } else {
            redirect_to("subscribe?{$string}");
        }
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
                            <li><a href="subscription_packages">Subscription Packages</a></li>
                            <li><a href="subscription_details?<?php echo $plan->subscription_string; ?>/<?php echo $plan->subscription ?>.html"><?php echo $plan->subscription." Package"; ?></a></li>
                            <li><?php echo $plan->plan; ?></li>
                        </ol>
                        <h2><?php echo $plan->subscription; ?> Subscription Package</h2>
                        <p>Renew this Subscription Package to enjoy uninterrupted access to more features for your Business</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Subscription Renewal Confirmation</h4>
                    </div>
                    <div class="card-body">
                        <p class="p-3">
                            You are about to renew your Business - <strong><?php echo $business->name."'s"; ?></strong> current Subscription 
                            <div class="table-responsive text-dark">
                                <table class="table table-responsive-sm table-striped table-bordered">
                                    <tr>
                                        <th scope="row">Subscription Package</th>
                                        <td><?php echo $plan->subscription; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Payment Plan</th>
                                        <td><?php echo $plan->plan ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Duration</th>
                                        <td><?php
                                            echo $plan->duration." ";
                                            echo classify_duration($plan->duration_type);
                                            if($plan->duration > 1){
                                                echo "s";
                                            }
                                        ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Amount</th>
                                        <td><?php echo "NGN".number_format($plan->price, 2); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="renewal_completion?<?php echo $plan->verify_string ?>" class="btn btn-primary">Continue</a>
                    </div>
                </div>
            </div>
        </div>
    </main>  

<?php include_portal_template("footer.php"); ?>