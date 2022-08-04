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
    if(isset($exploded[1])){
        $exploding = explode("/", $exploded[1]);
        
        $string = isset($exploding[0]) ? (string)$exploding[0] : "";
        $gurl = "fetch_business_subscription_by_string_api.php?string=".$string;
        $subscriptions = perform_get_curl($gurl);
        if($subscriptions){
            if($subscriptions->status == "success"){
                $package = $subscriptions->data;
                
                $pgurl = "fetch_subscription_plans_api.php?string=".$package->verify_string;
                $plans = perform_get_curl($pgurl);
                if($plans){
                    
                } else {
                    die("Subscription Link Payment Plans Broken");
                }
            } else {
                die($subscriptions->message);
            }
        } else {
            die("Subscription Package Link Broken");
        }
    } else {
        die("Wrong Path");
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
                            <li><?php echo $package->package." Package"; ?></li>
                        </ol>
                        <h2><?php echo $package->package ?> Subscription Package</h2>
                        <p>Subscribe to this Package to have access to more features for your Business</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="shadow-none w-100 ml-5">
                    <div class="card">
                        <div class="card-body card-block">
                            <div class='col-10 mx-auto'>
                                <ul class=" w-75 px-2 py-3 mt-4">
                                    <div class="table-responsive">
                                        <?php echo output_message($message); ?>
                                        <table class="table table-hover  table-border table-responsive-sm">
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
                                </ul>
                                <div class="py-3">
                                    <p style="font-size: 0.95rem;" class="text-muted text-justify mt-1 ms-4 ">
                                       <?php echo html_entity_decode($package->description); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                if($plans->status == "success"){
                    foreach($plans->data as $plan){
            ?>
                        <div class="col-lg-3 col-md-4 col-sm-4">
                            <div class="card text-center">
                                <div class="card-header">
                                    <h4 class="card-title fw-bold"><?php echo $plan->plan; ?></h4>
                                </div>
                                <div class="card-body">
                                    <h4 style="font-size: 1.5rem; font-weight:bold" class="text-success py-2"><?php echo "NGN".number_format($plan->price, 2); ?></h4>
                                    <h5 class="text-muted">
                                        <?php
                                            echo $plan->duration." ";
                                            echo classify_duration($plan->duration_type);
                                            if($plan->duration > 1){
                                                echo "s";
                                            }
                                        ?>
                                    </h5>
                                </div>
                                <div class="card-footer text-center">
                                    <?php
                                        if($subscribes->status == "success"){
                                            $sub = $subscribes->data;
                                            $time = time();
                                            if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                                                if($sub->subscription_string == $package->verify_string){
                                                    $renew = "yes";
                                                } else {
                                                    $renew = "no";
                                                }
                                            } else {
                                                $renew = "no";
                                            }
                                        } else {
                                            $renew = "no";
                                        }
                                        if($renew == "no"){
                                    ?>
                                            <a href="subscribe?<?php echo $plan->verify_string; ?>"class="btn py-2 w-50 mx-auto bg-success text-white" style="font-size: 14px;">Subscribe</a>        
                                    <?php
                                        } else {
                                    ?>
                                            <a href="renew?<?php echo $plan->verify_string ?>" class="btn py-2 w-50 mx-auto bg-success text-white" style="font-size: 14px;">Renew</a>
                                    <?php
                                        }
                                    ?>
                                    
                                </div>
                            </div>
                        </div>
            <?php
                    }
                } else {
                    echo $plans->message;
                }
            ?>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>