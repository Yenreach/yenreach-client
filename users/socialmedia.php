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
    
    $gurl = "fetch_business_by_string_api.php?string=".$session->business_string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
            
            if(isset($_POST['submit'])){
                $facebook_link = !empty($_POST['facebook_link']) ? (string)$_POST['facebook_link'] : "";
                $twitter_link = !empty($_POST['twitter_link']) ? (string)$_POST['twitter_link'] : "";
                $youtube_link = !empty($_POST['youtube_link']) ? (string)$_POST['youtube_link'] : "";
                $instagram_link = !empty($_POST['instagram_link']) ? (string)$_POST['instagram_link'] : "";
                $linkedin_link = !empty($_POST['linkedin_link']) ? (string)$_POST['linkedin_link'] : "";
                $whatsapp = !empty($_POST['whatsapp']) ? (string)$_POST['whatsapp'] : "";
                $website = !empty($_POST['website']) ? (string)$_POST['website'] : "";
                
                $purl = "update_business_socialmedia_links_api.php";
                $pdata = [
                        'verify_string' => $business->verify_string,
                        'facebook_link' => $facebook_link,
                        'twitter_link' => $twitter_link,
                        'instagram_link' => $instagram_link, 
                        'youtube_link' => $youtube_link,
                        'linkedin_link' => $linkedin_link,
                        'whatsapp' => $whatsapp,
                        'website' => $website
                    ];
                
                $update_socialmedia = perform_post_curl($purl, $pdata);
                if($update_socialmedia){
                    if($update_socialmedia->status == "success"){
                        $social = $update_socialmedia->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => "user",
                                'agent_string' => $session->verify_string,
                                'object_type' => "Businesses",
                                'object_string' => $session->business_string,
                                "activity" => "Update",
                                "details" => "Business' Social Media Links Updated"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Social Media Links updated successfully");
                        redirect_to("socialmedia");
                    } else {
                        $message = $update_socialmedia->message;
                    }
                } else {
                    $message = "Social Media Update Link Broken";
                }
            } else {
                $facebook_link = $business->facebook_link;
                $twitter_link = $business->twitter_link;
                $instagram_link = $business->instagram_link;
                $youtube_link = $business->youtube_link;
                $linkedin_link = $business->linkedin_link;
                $whatsapp = $business->whatsapp;
                $website = $business->website;
            }
            
            $sgurl = "fetch_business_latest_subscription_api.php?string=".$session->business_string;
            $subscribes = perform_get_curl($sgurl);
            if($subscribes){
                
            } else {
                die("Subscrption Check Link Broken");
            }
        } else {
            die($businesses->message);
        } 
    } else {
        die("Business Link Broken");
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
                            <li>Branches</li>
                        </ol>
                        <h2>Business Branches</h2>
                        <p>Other Outlets/Branches of this Business</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body card-block pt-3">
                        <div class="table-responsive text-dark">
                            <table class="table table-responsive-sm table-striped table0bordered text-dark">
                                <tbody>
                                    <tr>
                                        <th scopr="row">WhatsApp Number</th>
                                        <td><?php echo $business->whatsapp; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Facebook Link</th>
                                        <td><?php echo $business->facebook_link ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Twitter Link</th>
                                        <td><?php echo $business->twitter_link ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Instagram Link</th>
                                        <td><?php echo $business->instagram_link ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">LinkedIn Link</th>
                                        <td><?php echo $business->linkedin_link ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Youtube Link</th>
                                        <td><?php echo $business->youtube_link ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Website</th>
                                        <td><?php echo $business->website ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            if($subscribes->status == "success"){
                $sub = $subscribes->data;
                $time = time();
                if(($sub->status == 1) && ($sub->expired >= $time)){
                    $pgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                    $subscriptions = perform_get_curl($pgurl);
                    if(($subscriptions) && ($subscriptions->status == "success")){
                        $subscription = $subscriptions->data;
                        if($subscription->socialmedia == 1){
        ?>
                            <div class="row">
                                <div class="col-lg-9 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title text-success">Copy and paste the link to your social media page here</h4>
                                            <?php echo output_message($message); ?>
                                        </div>
                                        <div class="card-body card-block">
                                            <form role="form" action="socialmedia" method="POST" class="row g-3 needs-validation">
                                                <div class="col-12 mb-2">
                                                    <label for="website" class="form-label mt-1">Website Link</label>
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-internet"></i></span>
                                                        <input type='url' name="website" id="website" class="form-control" value="<?php echo $website; ?>" placeholder="Link to your Website e.g. https://yourwebsite.com">
                                                        <!--<div class="invalid-feedback">Please Select the Work Day.</div>-->
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="facebook_link" class="form-label">Facebook Link</label>
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-facebook"></i></span>
                                                        <input type='url' name="facebook_link" id="facebook_link" class="form-control" value="<?php echo $facebook_link; ?>" placeholder="Link to your Facebook Page e.g. https://facebook.com/------">
                                                        <!--<div class="invalid-feedback">Please Select the Work Day.</div>-->
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="twitter_link" class="form-label mt-1">Twitter Link</label>
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-twitter"></i></span>
                                                        <input type='url' name="twitter_link" id="twitter_link" class="form-control" value="<?php echo $twitter_link; ?>" placeholder="Link to your Twitter Page e.g. https://twitter.com/------">
                                                        <!--<div class="invalid-feedback">Please Select the Work Day.</div>-->
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="instagram_link" class="form-label mt-1">Instagram Link</label>
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-instagram"></i></span>
                                                        <input type='url' name="instagram_link" id="instagram_link" class="form-control" value="<?php echo $instagram_link; ?>" placeholder="Link to your Instagram Page e.g. https://twitter.com/------">
                                                        <!--<div class="invalid-feedback">Please Select the Work Day.</div>-->
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="linkedin_link" class="form-label mt-1">LinkedIn Link</label>
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-linkedin"></i></span>
                                                        <input type='url' name="linkedin_link" id="linkedin_link" class="form-control" value="<?php echo $linkedin_link; ?>" placeholder="Link to your LinkedIn Page e.g. https://twitter.com/------">
                                                        <!--<div class="invalid-feedback">Please Select the Work Day.</div>-->
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="youtube_link" class="form-label mt-1">Youtube Link</label>
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-youtube"></i></span>
                                                        <input type='url' name="youtube_link" id="youtube_link" class="form-control" value="<?php echo $youtube_link; ?>" placeholder="Link to your Youtube Page e.g. https://youtube.com/------">
                                                        <!--<div class="invalid-feedback">Please Select the Work Day.</div>-->
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="whatsapp" class="form-label mt-1">WhatsApp Number</label>
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-whatsapp"></i></span>
                                                        <input type='tel' name="whatsapp" id="whatsapp" class="form-control" value="<?php echo $whatsapp; ?>" placeholder="Your WhatsApp contact e.g. +2348031234567">
                                                        <!--<div class="invalid-feedback">Please Select the Work Day.</div>-->
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <button class="btn w-100 text-white py-2" style="background: #00C853;" id="submit" name="submit" type="submit">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
        <?php
                        } else {
        ?>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Add Branch</h4>
                                        </div>
                                        <div class="card-body card-block">
                                            <p>
                                                You have reached the Limit of the Branches that can be added to this Business.
                                                To add more branches, please visit the <a href="subscription_packages" class="text-primary">
                                                    Subscription Packages Page
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
        <?php
                        }
                    }
                } else {
        ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Copy and paste the link to your social media page here</h4>
                                </div>
                                <div class="card-body card-block">
                                    <p>
                                        For you to update/edit this Business' Social Media Links, you'll need to have subscribed this Business to a Subscription Package.
                                        To learn more please visit the <a href="subscription_packages" class="text-primary">
                                            Subscription Packages Page
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
        <?php
                }
            } else {
        ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Copy and paste the link to your social media page here</h4>
                            </div>
                            <div class="card-body card-block">
                                <p>
                                    For you to update/edit this Business' Social Media Links, you'll need to have subscribed this Business to a Subscription Package.
                                    To learn more please visit the <a href="subscription_packages" class="text-primary">
                                        Subscription Packages Page
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        ?>
    </main>

<?php include_portal_template("header.php"); ?>