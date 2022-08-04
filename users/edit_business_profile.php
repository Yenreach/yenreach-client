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
                $name = !empty($_POST['name']) ? (string)$_POST['name'] : "";
                $description = !empty($_POST['description']) ? (string)$_POST['description'] : "";
                $phonenumber = !empty($_POST['phonenumber']) ? (string)$_POST['phonenumber'] : "";
                $email = !empty($_POST['email']) ? (string)$_POST['email'] : "";
                $address = !empty($_POST['address']) ? (string)$_POST['address'] : "";
                $town = !empty($_POST['town']) ? (string)$_POST['town'] : "";
                $lga = !empty($_POST['lga']) ? (string)$_POST['lga'] : "";
                $state_id = !empty($_POST['state_id']) ? (int)$_POST['state_id'] : "";
                $month_started = !empty($_POST['month_started']) ? (string)$_POST['month_started'] : "";
                $year_started = !empty($_POST['year_started']) ? (string)$_POST['year_started'] : "";
                
                $purl = "edit_business_profile_api.php";
                $pdata = [
                        'verify_string' => $session->business_string,
                        'name' => $name,
                        'description' => $description,
                        'email' => $email,
                        'phonenumber' => $phonenumber,
                        'address' => $address,
                        'town' => $town,
                        'lga' => $lga,
                        'state_id' => $state_id,
                        'month_started' => $month_started,
                        'year_started' => $year_started
                    ];
                
                $edit_profile = perform_post_curl($purl, $pdata);
                if($edit_profile){
                    if($edit_profile->status == "success"){
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => "user",
                                'agent_string' => $session->verify_string,
                                'object_type' => "Businesses",
                                'object_string' => $session->business_string,
                                "activity" => "Update",
                                "details" => "Business Profile Edited"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Profile Edit was siccessful");
                        redirect_to("business_profile");
                    } else {
                        $message = $edit_profile->message;
                    }
                } else {
                    $message = "Profile Edit Link Broken";
                }
            } else {
                $name = $business->name;
                $description = $business->description;
                $phonenumber = $business->phonenumber;
                $email = $business->email;
                $address = $business->address;
                $town = $business->town;
                $lga = $business->lga;
                $state_id = $business->state_id;
                $month_started = $business->month_started;
                $year_started = $business->year_started;
            }
            
            $sgurl = "fetch_all_states_api.php";
            $states = perform_get_curl($sgurl);
            if($states){
                
            } else {
                die("States Link Broken");
            }
            
            $lgurl = "fetch_all_lgas_api.php";
            $lgas = perform_get_curl($lgurl);
            if($lgas){
                
            } else {
                die("Local Governments Link Broken");
            }
            
            $cgurl = "fetch_all_categories_api.php";
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
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Business</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card-block p-3">
                        <div class="col-12">
                            <form role="form" action="edit_business_profile" method="POST" class="row g-3 needs-validation">
                                <div class="col-12">
                                    <label for="bus_name" class="form-label">Business Name</label>
                                    <div class="input-group has-validation">
                                        <input type="text" name="name" id="bus_name" class="form-control" placeholder="Business Name" value="<?php echo $name; ?>" required>
                                        <div class="invalid-feedback">Please Enter the Name of the Business</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="bus_description" class="form-label">Business Description</label>
                                    <div class="input-group has-validation">
                                        <textarea name="description" id="bus_description" class="form-control" rows="6" placeholder="Short Description of your Business" required><?php echo $description; ?></textarea>
                                        <div class="invalid-feedback">Please provide a Short Description of the Business</div>
                                    </div>
                                </div>   
                                <div class="col-12">
                                    <label for="bus_category" class="form-label" id="categ_label">Categories(You can choose up to 5 Categories)</label>
                                    <div class="col-12" style="height: auto" id="category_list"></div>
                                    <div class="input-group has-validation">
                                        <input type="text" list="categ_list" id="bus_category" class="form-control">
                                        <datalist id="categ_list">
                                            <?php
                                                if($categories->status == "success"){
                                                    foreach($categories->data as $category){
                                                        echo '<option value="'.$category->category.'">';
                                                    }
                                                }
                                            ?>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="bus_phone" class="form-label">Phone</label>
                                    <div class="input-group has-validation">
                                        <input type="tel" name="phonenumber" id="bus_phone" class="form-control" placeholder="Business Phone Number" value="<?php echo $phonenumber; ?>" required>
                                        <div class="invalid-feedback">Please provide the Business Phone Number</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="bus_email" class="form-label">Email</label>
                                    <div class="input-group has-validation">
                                        <input type="email" name="email" id="bus_email" class="form-control" placeholder="Business Email Address" value="<?php echo $email; ?>" required>
                                        <div class="invalid-feedback">Please provide the Business Email Address</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-2 float-left">
                                    <label for="bus_state" class="form-label">State</label>
                                    <div class="input group has-validation">
                                        <select name="state_id" id="bus_state" class="form-control" required>
                                            <option value="">--State--</option>
                                            <?php
                                                if($states->status == "success"){
                                                    foreach($states->data as $stated){
                                                        echo '<option value="'.$stated->id.'"';
                                                        if($stated->id == $state_id){
                                                            echo ' selected';
                                                        }
                                                        echo '>'.$stated->name.'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">Please provide the State the Business is Situated in</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2 float-right">
                                    <label for="bus_lga" class="form-label">Local Gov't Area</label>
                                    <div class="input-group has-validation">    
                                        <select name="lga" id="bus_lga" class="form-control" required>
                                            <option value="">--Local Gov't Area--</option>
                                            <?php
                                                if($lgas->status == "success"){
                                                    foreach($lgas->data as $lgad){
                                                        echo '<option value="'.$lgad->name.'"';
                                                        if($lgad->name == $lga){
                                                            echo ' selected';
                                                        }
                                                        echo '>'.$lgad->name.'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">Please provide the LGA the Business is Situated in</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="bus_town" class="form-label">Town/City</label>
                                    <div class="input-group has-validation">
                                        <input type="text" name="town" id="bus_town" class="form-control" placeholder="Town/City" value="<?php echo $town; ?>" required>
                                        <div class="invalid-feedback">Please provide the Town or City the Business is Situated in</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="bus_address" class="form-label">Address</label>
                                    <div class="input-group has-validation">
                                        <textarea name="address" id="bus_address" class="form-control" placeholder="Address(House No and Street Name)"><?php echo $address; ?></textarea>
                                        <div class="invalid-feedback">Please provide the Address the Business is Situated in</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="bus_month_started" class="form-label">Month Business Started</label>
                                    <div class="input-group">
                                        <select name="month_started" id="bus_month_started" class="form-control">
                                            <option value="">--Month--</option>
                                            <?php months($month_started); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="bus_year_started" class="form-label">Year Business Stated</label>
                                    <select name="year_started" id="bus_year_started" class="form-control">
                                        <option value="">--Year--</option>
                                        <?php echo gallery_year($year_started); ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <input type="submit" name="submit" class="btn btn-primary btn-block" value="Submit">
                                </div>    
                                    
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main> 

<?php include_portal_template("footer.php"); ?>