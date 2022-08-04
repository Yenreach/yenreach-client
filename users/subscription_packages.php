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
    
    $gurl = "fetch_all_business_subscriptions_api.php";
    $subscriptions = perform_get_curl($gurl);
    if($subscriptions){
        
    } else {
        die("Subscriptions Link Broken");
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
                            <li>Subscription Packages</li>
                        </ol>
                        <h2>Subscription Packages</h2>
                        <p>Subscribe to any of our Packages to have access to more features for your Business</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <?php
                if($subscriptions->status == "success"){
                    foreach($subscriptions->data as $package){
                        $pgurl = "fetch_subscription_plans_api.php?string=".$package->verify_string;
                        $plans = perform_get_curl($pgurl);
                        if($plans){
                            if($plans->status == "success"){
                                $plan = array_shift($plans->data);
                                $price = $plan->price;
                            } else {
                                $price = 0;
                            }
                        } else {
                            $price = 0;
                        }
            ?>
                        <div class="col-10 col-lg-4 col-md-6 col-sm-6 mx-auto mx-sm-0">
                            <div class="card text-center">
                                <div class="card-header">
                                    <h3 class="card-title" style="font-weight:bold" ><?php echo strtoupper($package->package)." Package"; ?></h3>
                                </div>
                                <div class="card-body pt-2">
                                    <h4 style="font-size: 1.7rem; font-weight:bold" class="text-success "><?php echo "NGN".number_format($price, 2); ?></h4>
                                    <h4 class="text-muted" style="font-weight:bold">Features</h4>
                                    <div class="h-50 w-100 d-flex flex-column align-items-center justify-content-around">
                                        <div class='d-flex col-10 mx-auto align-items-center justify-content-between py-2'>
                                            <span class='text-muted' style="font-size: 16px; font-weight:bold">Logo Upload:</span>
                                            <span class='text-success'>Yes</span>
                                            </div>
                                        <div class='d-flex col-10 mx-auto align-items-center justify-content-between py-2'>
                                            <span class='text-muted'  style="font-size: 16px; font-weight:bold">Picture Uploads:</span>
                                            <span class='text-success'><?php echo $package->photos." Photo"; if($package->photos > 1){ echo "s"; } ?></span>
                                            </div>
                                        <div class='d-flex col-10 mx-auto align-items-center justify-content-between py-2'>
                                            <span  class='text-muted'style="font-size: 16px; font-weight:bold">Video Uploads:</span>
                                            <span class='text-success'><?php echo $package->videos." Video"; if($package->videos > 1){ echo "s"; } ?></span>
                                            </div>
                                        <div class='d-flex col-10 mx-auto align-items-center justify-content-between py-2'>
                                            <span class='text-muted'  style="font-size: 16px; font-weight:bold">Branches: </span>
                                            <span class='text-success'><?php echo $package->branches." Branch"; if($package->branches > 1){ echo "es"; } ?></span>
                                            </div>
                                        <div class='d-flex col-10 mx-auto align-items-center justify-content-between py-2'>
                                            <span class='text-muted' style="font-size: 16px; font-weight:bold">Social Media/Website: </span>
                                            <span class='text-success'><?php if($package->socialmedia == 0){ echo "No"; } elseif($package->socialmedia == 1){ echo "Yes"; } ?></span>
                                            </div>
                                    </div>
                                    <div>
                                        <a href="subscription_details?<?php echo $package->verify_string."/{$package->package}.html"; ?>" class="col-12 btn btn-primary py-2 mt-2 px-3">See More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php
                    }
                } else {
            ?>
                    <p><?php echo $subscriptions->message; ?></p>
            <?php
                }
            ?>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>