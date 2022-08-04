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
                        <p>Subscribe to this Package to have access to more features for your Business</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Subscription Confirmation</h4>
                    </div>
                    <div class="card-body">
                        <p class="p-3">
                            You are about to Subscribe your Business, <strong><?php echo $business->name; ?></strong> to the <strong><?php echo $plan->subscription; ?> Subscription Package</strong>
                            <br />
                            You are to pay the sum of <strong>NGN<?php echo number_format($plan->price, 2); ?></strong> and the subscription will be for the duration of
                            <strong><?php
                                echo $plan->duration." ";
                                echo classify_duration($plan->duration_type);
                                if($plan->duration > 1){
                                    echo "s";
                                }
                            ?></strong>
                        </p>
                        <?php
                            if($subscribes->status == "success"){
                                $subscribe = $subscribes->data;
                                $time = time();
                                if(($subscribe->status == 1) && ($subscribe->expired > $time)){
                        ?>
                                    <p class="p-3">
                                        <strong>
                                            Please note that you still have an Active Subscription on this Business which will be cancelled if you continue
                                        </strong>
                                        <br />
                                        <div class="table-responsive">
                                            <table class="table table-responsive-sm table-striped text-dark">
                                                <tr>
                                                    <th scope="row">Package</th>
                                                    <td><a href="subscription_details?<?php echo $subscribe->subscription_string ?>/<?php echo $subscribe->subscription.".html"; ?>"
                                                    target="_blank"><?php echo $subscribe->subscription; ?> Subscription Package</a></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Duration</th>
                                                    <td><?php
                                                        echo $subscribe->duration." ";
                                                        echo classify_duration($subscribe->duration_type);
                                                        if($subscribe->duration > 1){
                                                            echo "s";
                                                        }
                                                    ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Expiry</th>
                                                    <td><?php echo strftime("%A, %d %B %Y; %I:%M:%S%p", $subscribe->expired); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </p>
                        <?php
                                }
                            }
                        ?>
                    </div>
                    <div class="card-footer">
                        <a href="subscribe_completion?<?php echo $plan->verify_string ?>" class="btn btn-primary">Continue</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>