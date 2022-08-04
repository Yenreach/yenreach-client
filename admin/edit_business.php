<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    $gurl = "fetch_business_by_string_api.php?string=".$string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
            
            if(isset($_POST['submit'])){
                $name = !empty($_POST['name']) ? (string)$_POST['name'] : "";
                $description = !empty($_POST['description']) ? (string)$_POST['description'] : "";
                $phonenumber = !empty($_POST['phone']) ? (string)$_POST['phone'] : "";
                $email = !empty($_POST['email']) ? (string)$_POST['email'] : "";
                $address = !empty($_POST['address']) ? (string)$_POST['address'] : "";
                $town = !empty($_POST['town']) ? (string)$_POST['town'] : "";
                $lga = !empty($_POST['lga']) ? (string)$_POST['lga'] : "";
                $state_id = !empty($_POST['state_id']) ? (int)$_POST['state_id'] : "";
                $month_started = !empty($_POST['month_started']) ? (string)$_POST['month_started'] : "";
                $year_started = !empty($_POST['year_started']) ? (string)$_POST['year_started'] : "";
                
                $purl = "edit_business_profile_api.php";
                $pdata = [
                        'verify_string' => $business->verify_string,
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
                                'agent_type' => "admin",
                                'agent_string' => $session->verify_string,
                                'object_type' => "Businesses",
                                'object_string' => $business->verify_string,
                                "activity" => "Update",
                                "details" => "Business Profile Edited"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Profile Edit was siccessful");
                        redirect_to("business?".$business->verify_string);
                    } else {
                        $message = $edit_profile->message;
                    }
                } else {
                    $message = "Profile Edit Link Broken";
                }
            } else {
                $name = $business->name;
                $description = $business->description;
                $phone = $business->phonenumber;
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
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4><?php echo $business->name; ?></h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Businesses</a></li>
                        <li class="breadcrumb-item"><a href="all_businesses">All Businesses</a></li>
                        <li class="breadcrumb-item"><a href="business?<?php echo $business->verify_string; ?>"><?php echo $business->name; ?></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Business Profile</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Business</h4>
                        </div>
                        <div class="card-body card-block">
                            <div class="col-12 float-left">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>Business Categories</label>
                                        <input type="hidden" name="business_string" id="business_string" value="<?php echo $business->verify_string; ?>">
                                        <div class="col-12" style="height: auto;" id="category_list"></div>
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
                            </div>
                            <form action="edit_business?string=<?php echo $business->verify_string; ?>" method="POST">
                                <div class="col-12 float-left">
                                    <div class="form-group">
                                        <label class="form-label">Business Name</label>
                                        <input type="text" name="name" id="bus_name" class="form-control" placeholder="Name of the Business" value="<?php echo $name; ?>">
                                    </div>
                                </div>
                                <div class="col-12 float-left">
                                    <div class="form-group">
                                        <label class="form-label">Business Description</label>
                                        <textarea name="description" id="bus_description" class="form-control" rows="7" placeholder="Business Description"><?php echo $description; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-12 float-left">
                                    <div class="form-group">
                                        <label class="form-label">Business Email</label>
                                        <input type="email" name="email" id="bus_email" class="form-control" placeholder="Email Address of the Business" value="<?php echo $email; ?>">
                                    </div>
                                </div>
                                <div class="col-12 float-left">
                                    <div class="form-group">
                                        <label class="form-label">Business Phone</label>
                                        <input type="tel" name="phone" id="bus_phone" class="form-control" placeholder="Business' Phone Number" value="<?php echo $phone; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6 float-left">
                                    <div class="form-group">
                                        <label class="form-label">State</label>
                                        <select name="state_id" id="bus_state" class="form-control">
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
                                    </div>
                                </div>
                                <div class="col-md-6 float-left">
                                    <div class="form-group">
                                        <label class="form-label">Local Gov't Area</label>
                                        <select name="lga" id="bus_lga" class="form-control">
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
                                    </div>
                                </div>
                                <div class="col-12 float-left">
                                    <div class="form-group">
                                        <label class="form-label">City/Town</label>
                                        <input type="text" name="town" id="bus_town" class="form-control" placeholder="City where the Business is Situated" value="<?php echo $town; ?>">
                                    </div>
                                </div>
                                <div class="col-12 float-left">
                                    <div class="form-group">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" id="bus_address" class="form-control" placeholder="Business Address"><?php echo $address; ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 float-left">
                                    <div class="form-group">
                                        <label class="form-label" for="bus_month_started">Month Business Started</label>
                                        <select name="month_started" id="bus_month_started" class="form-control">
                                            <option value="">--Month--</option>
                                            <?php months($month_started); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 float-left">
                                    <div class="form-group">
                                        <label for="bus_year_started" class="form-label">Year Business Stated</label>
                                        <select name="year_started" id="bus_year_started" class="form-control">
                                            <option value="">--Year--</option>
                                            <?php echo gallery_year($year_started); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 text-center float-left">
                                    <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                                </div>
                            </form>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>