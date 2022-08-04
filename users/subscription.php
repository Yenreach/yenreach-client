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
    
    $bgurl = "fetch_business_by_string_api.php?string=".$session->business_string;
    $businesses = perform_get_curl($bgurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
        } else {
            die($businesses->message);
        }
    } else {
        die("Business Link Broken");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    $string = !empty($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_business_subscription_api.php?string=".$string;
    $bus_subscriptions = perform_get_curl($gurl);
    if($bus_subscriptions){
        if($bus_subscriptions->status == "success"){
            $subscription = $bus_subscriptions->data;
            $sgurl = "fetch_business_subscription_by_string_api.php?string=".$subscription->subscription_string;
            $subs = perform_get_curl($sgurl);
            if($subs){
                if($subs->status == "success"){
                    $package = $subs->data;
                } else {
                    die($subs->message);
                }
            } else {
                die("Subscription Package Link Broken");
            }
        } else {
            die($bus_subscriptions->message);
        }
    } else {
        die("Subscription Link Broken");
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
                            <li><a href="business_subscriptions">Business Subscriptions</a></li>
                            <li><?php echo $subscription->subscription; ?></li>
                        </ol>
                        <h2>Business Subscriptions</h2>
                        <p>The Various Subscriptions that has been done for this Business over time</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?php echo $business->name ?> Subscription (<?php echo $subscription->subscription; ?>)</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card-block text-dark">
                        <div class="col-12 mt-3">
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Subscription Package</strong></div>
                                <div class="col-lg-9 col-md-8">
                                    <a href="subscription_details?<?php echo $subscription->subscription_string."/".$subscription->subscription.".html"; ?>">
                                        <?php echo $subscription->subscription." Subscription Package"; ?>
                                    </a>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Payment Plan</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $subscription->payment_plan." Payment Plan"; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Amount Paid</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo "NGN".number_format($subscription->amount_paid, 2); ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Duration</strong></div>
                                <div class="col-lg-9 col-md-8"><?php
                                            echo $subscription->duration." ";
                                            echo classify_duration($subscription->duration_type);
                                            if($subscription->duration > 1){
                                                echo "s";
                                            }
                                        ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Subscription Duration</strong></div>
                                <div class="col-lg-9 col-md-8">
                                    From <b><?php echo strftime("%A, %d %B %Y; %I:%M:%S%p", $subscription->started); ?></b>
                                    to <b><?php echo strftime("%A, %d %B %Y; %I:%M:%S%p", $subscription->expired); ?></b>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Subscription Details</strong></div>
                                <div class="col-lg-9 col-md-8">
                                    <div class="table-responsive-sm text-sm text-dark">
                                        <table class="table table-responsive-sm table-striped table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td class='border-0 text-muted py-3 fw-bold' style="font-size: 1rem">Logo Upload:</td>
                                                    <td class='border-0  py-3 text-success' style="font-size: 1rem;">Yes</td>
                                                </tr>
                                                <tr>
                                                    <td class='border-0 text-muted fw-bold py-3' style="font-size: 1rem;">Photo Uploads:</td>
                                                    <td class='border-0  py-3 text-success' style="font-size: 1rem;"><?php echo $package->photos." Photo"; if($package->photos > 1){ echo "s"; } ?></td>
                                                </tr>
                                                <tr>
                                                    <td class='border-0 text-muted fw-bold py-3' style="font-size: 1rem;">Video Uploads:</td>
                                                    <td class='border-0  py-3 text-success' style="font-size: 1rem;"><?php echo $package->videos." Video"; if($package->videos > 1){ echo "s"; } ?></td>
                                                </tr>
                                                <tr>
                                                    <td class='border-0 text-muted fw-bold  py-3' style="font-size: 1rem;">Number of Branches:</td>
                                                    <td class='border-0  py-3 text-success' style="font-size: 1rem;"><?php echo $package->branches." Branch"; if($package->branches > 1){ echo "es"; } ?></td>
                                                </tr>
                                                <tr>
                                                    <td class='border-0 text-muted fw-bold py-3' style="font-size: 1rem;">Website/Social Media:</td>
                                                    <td class='border-0  py-3 text-success' style="font-size: 1rem;"><?php if($package->socialmedia == 0){ echo "No"; } elseif($package->socialmedia == 1){ echo "Yes"; } ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Auto Renew</strong></div>
                                <div class="col-lg-9 col-md-8">
                                    <?php
                                        if($subscription->auto_renew == 1){
                                    ?>
                                            <span class="m-3">
                                                <button class="btn btn-success" href="javascript:void(0)">Enabled</button>
                                            </span>
                                            <span class="m-3">
                                                <a href="subscription_auto_renew?<?php echo $subscription->verify_string; ?>" class="text-danger text-underline"
                                                onclick="return confirm('Do you want to disable the Subscription Auto Renewal?')">Disable</a>
                                            </span>
                                    <?php
                                        } else {
                                    ?>
                                            <span class="m-3">
                                                <a href="subscription_auto_renew?<?php echo $subscription->verify_string; ?>" class="text-success text-underline"
                                                onclick="return confirm('Do you really want to Enable Subscription Auto Renewal? Please note that we will automatically charge your Card as soon as this Subscription Expires')">Enable</a>
                                            </span>
                                            <span class="m-3">
                                                <button class="btn btn-danger" href="javascript:void(0)">Disabled</button>
                                            </span>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 text-center">
                                    <a href="subscription_details?<?php echo $subscription->subscription_string."/".$subscription->subscription.".html"; ?>"
                                    class="btn btn-primary btn-block">Renew</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>