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

    $verify_string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_business_by_string_api.php?string=".$verify_string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
            
            $sgurl = "fetch_all_business_subscriptions_api.php";
            $subscriptions = perform_get_curl($sgurl);
            if($subscriptions){
                
            } else {
                die("Business Subscription Link Broken");
            }
        } else {
            die($businesses->message);
        }
    } else {
        die("Business Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4><?php echo $business->name ?> Subscription</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Businesses</a></li>
                        <li class="breadcrumb-item"><a href="all_businesses">All Businesses</a></li>
                        <li class="breadcrumb-item"><a href="business?<?php echo $business->verify_string ?>"><?php echo $business->name; ?></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $business->name; ?></a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Subscribe Business</h4>
                            <?php echo output_message($message); ?>
                        </div>
                        <div class="card-body card-block text-dark">
                            <?php
                                foreach($subscriptions->data as $subscription){
                            ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title"><?php echo $subscription->package; ?></h4>
                                                </div>
                                                <div class="card-body">
                                                <?php
                                                    $pgurl = "fetch_subscription_plans_api.php?string=".$subscription->verify_string;
                                                    $plans = perform_get_curl($pgurl);
                                                    if($plans){
                                                        if($plans->status == "success"){
                                                ?>
                                                            <div class="row">
                                                            <?php
                                                                foreach($plans->data as $plan){
                                                            ?>
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <div class="card">
                                                                            <div class="card-header">
                                                                                <h4 class="card-title"><?php echo $plan->plan; ?></h4>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <p>
                                                                                    <?php
                                                                                        echo $plan->duration." ";
                                                                                        echo classify_duration($plan->duration_type);
                                                                                        if($plan->duration > 1){
                                                                                            echo "s";
                                                                                        }
                                                                                    ?>
                                                                                </p>
                                                                                <p>
                                                                                    <?php echo "NGN".number_format($plan->price, 2); ?>
                                                                                </p>
                                                                                <p>
                                                                                    <a href="business_subscribe?business=<?php echo $business->verify_string ?>&package=<?php echo $plan->verify_string ?>" class="btn btn-primary"
                                                                                    onclick="return confirm('Do you really want to subscribe <?php echo $business->name ?> to the <?php echo $subscription->package ?> Subscription Plan? Please note that this Subscription will override any existing subscription for this Business and it will last for a duration of <?php echo $plan->duration." "; echo classify_duration($plan->duration_type)."(s)"; ?>')">
                                                                                        Subscribe
                                                                                    </a>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                
                                                            <?php
                                                                }
                                                            ?>
                                                            </div>
                                                <?php
                                                        } else {
                                                            echo '<p>'.$plans->message.'</p>';
                                                        }
                                                    }
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            ?>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>