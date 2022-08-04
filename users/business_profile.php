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
            
            $cgurl = "fetch_business_categories_api.php?string=".$session->business_string;
            $categories = perform_get_curl($cgurl);
            if($categories){
                
            } else {
                die("Categories Link Broken");
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Business Profile</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card-block p-3">
                       
                        <div class="col-12">
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Business Name</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $business->name; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Business Description</strong></div>
                                <div class="col-lg-9 col-md-8 small fst-italic"><?php echo $business->description; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Logo</strong></div>
                                <div class="col-lg-9 col-md-8"><?php
                                    if(file_exists("../images/{$business->filename}.jpg")){
                                ?>
                                        <p>
                                            <img src="../images/<?php echo $business->filename.".jpg"; ?>" style="width: 80%; max-width: 300px;" alt="<?php echo $business->name; ?>" />
                                        </p>
                                        <p>
                                            <a href="change_business_logo" class="btn btn-primary"><strong>Change Logo</strong></a>
                                            &nbsp;
                                            <a href="remove_business_logo" class="btn btn-danger" onclick="return confirm('Do you want to remove this Logo from the Business')">Remove Logo</a>
                                        </p>
                                <?php
                                    } else {
                                ?>
                                        <a href="change_business_logo" class="btn btn-primary">Upload Logo</a>
                                <?php
                                    }   
                                ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Business Categories</strong></div>
                                <div class="col-lg-9 col-md-8"><?php
                                    if($categories->status == "success"){
                                        $categ_array = array();
                                        foreach($categories->data as $category){
                                            $categ_array[] = $category->category;
                                        }
                                        echo join(', ', $categ_array);
                                    }
                                ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Phone Number</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $business->phonenumber; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Email Address</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $business->email; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Business Address</strong></div>
                                <div class="col-lg-9 col-md-8"><?php
                                    echo $business->address.", <br />";
                                    if(!empty($business->town)){
                                        echo $business->town.", ";
                                    }
                                    if(!empty($business->lga)){
                                        echo $business->lga." Local Government Area,<br />";
                                    }
                                    echo $business->state." State.";
                                ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-9 col-md-8">
                                    <p><a href="edit_business_profile" class="btn btn-primary">Edit Business Profile</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>