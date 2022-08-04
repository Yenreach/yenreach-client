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
    
    $gurl = "fetch_business_subscriptions_api.php?string=".$session->business_string;
    $subs = perform_get_curl($gurl);
    if($subs){
        
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
                            <li>Business Subscriptions</li>
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
                        <h4 class="card-title"><?php echo $business->name ?> Subscriptions</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card-block text-dark">
                        <?php
                            if($subs->status == "success"){
                        ?>
                                <div class="table-responsive text-dark">
                                    <table class="table table-responsive-sm table-striped text-dark" id="subscription_table">
                                        <thead>
                                            <tr>
                                                <th>Subscription</th>
                                                <th>Date Started</th>
                                                <th>Expiry Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($subs->data as $sub){
                                            ?>
                                                    <tr>
                                                        <td><a href="subscription_details?<?php echo $sub->subscription_string ?>/<?php echo $sub->subscription.".html"; ?>"><?php echo $sub->subscription; ?>
                                                        <br />(<?php echo $sub->payment_plan; ?>)</a></td>
                                                        <td><?php 
                                                            //echo strftime("%A, %d %B %Y; %I:%M:%S%p", $sub->started); 
                                                            echo strftime("%Y.%m.%d %H:%M:%S", $sub->started);
                                                        ?></td>
                                                        <td><?php 
                                                            //echo strftime("%A, %d %B %Y; %I:%M:%S%p", $sub->expired); 
                                                            echo strftime("%Y.%m.%d %H:%M:%S", $sub->expired);
                                                        ?></td>
                                                        <?php
                                                            $time = time();
                                                            if($time > $sub->expired){
                                                        ?>
                                                                <td><span class="text-danger">Expired</span></td>
                                                        <?php
                                                            } else {
                                                                if($sub->status != 1){
                                                                    if($sub->status == 2){
                                                        ?>
                                                                        <td><span class="text-warning">Renewed</span></td>
                                                        <?php
                                                                    } else {
                                                        ?>
                                                                        <td><span class="text-danger">Cancelled</span></td>
                                                        <?php
                                                                    }
                                                                } else {
                                                        ?>
                                                                    <td><span class="text-success">Active</span><br />
                                                                    <a href="subscription?<?php echo $sub->verify_string; ?>" class="text-primary">More Details</a></td>
                                                        <?php
                                                                }
                                                            }
                                                        ?>
                                                    </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                        <?php
                            } else {
                                echo '<p>'.$subs->message.'</p>';
                            }
                        ?>    
                    </div>
                </div>    
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>