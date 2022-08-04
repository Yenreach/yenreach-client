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
    
    $gurl = "fetch_business_available_facilities_api.php?string=".$session->business_string;
    $facilities = perform_get_curl($gurl);
    if($facilities){
        
    } else {
        die("Facilities Link Broken");
    }
    
    $agurl = "fetch_business_other_facilities_api.php?string=".$session->business_string;
    $others = perform_get_curl($agurl);
    if($others){
        
    } else {
        die("Other Facilities Broken");
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
                            <li>Facilities</li>
                        </ol>
                        <h2>Business Facilities</h2>
                        <p>The List of Facilities available at your Business</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Please Select which Facility is Available at your Place of Business</h6>
                        <p id="rem_facility"></p>
                    </div>
                    <div class="card-body card-block">
                        <?php
                            if($facilities->status == "success"){
                        ?>
                                <div class="row p-3">
                                    <?php
                                        foreach($facilities->data as $facility){
                                            if(strpos($business->facilities, $facility->verify_string) !== FALSE){
                                                $checked = "yes";
                                            } else {
                                                $checked = "no";
                                            }
                                    ?>
                                            <div class="col-12 col-lg-3 col-md-4 col-sm-6 col-xs-12 mt-3">
                                                <div class="form-group">
                                                    <input type="checkbox" class="facility" id="<?php echo $facility->verify_string; ?>" value="<?php echo $facility->verify_string ?>"<?php if($checked == "yes"){ echo " checked"; } ?>>
                                                    <span><label for="<?php echo $facility->verify_string; ?>"><?php echo $facility->facility; ?></label></span>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="row">
                                    <div class="col-12 mt-3">
                                        <button class="btn btn-success" id="update_facility">Update</button>
                                    </div>
                                </div>
                        <?php
                            } else {
                                echo "<p>".$facilities->message."</p>";
                            }
                        ?>    
                    </div>
                </div>
            </div>
        </div>
        <?php
            if($others->status == "success"){
        ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-header">Other Facilities</h4>
                                <p>Upgrade your Subscription Plan to be able to have access to these facilities</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                        foreach($others->data as $other){
                                    ?>
                                            <div class="col-12 col-lg-3 col-md-4 col-sm-6 col-xs-12 mt-3 text-center">
                                                <p><?php echo $other->facility; ?></p>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        ?>
    </main>

<?php include_portal_template("footer.php"); ?>