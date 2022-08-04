<?php
    require_once("../../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    
    if(!$session->is_logged_in()){
        redirect_to("auth?page=".$url);   
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    if($session->is_business_logged()){
        $business_string = $session->business_string;
    } else {
        $business_string = "";
    }
    
    if(isset($_POST['submit'])){
        $name = !empty($_POST['name']) ? (string)$_POST['name'] : "";
        $description = !empty($_POST['description']) ? (string)$_POST['description'] : "";
        $phone = !empty($_POST['phone']) ? (string)$_POST['phone'] : "";
        $email = !empty($_POST['email']) ? (string)$_POST['email'] : "";
        $address = !empty($_POST['address']) ? (string)$_POST['address'] : "";
        $town = !empty($_POST['town']) ? (string)$_POST['town'] : "";
        $lga = !empty($_POST['lga']) ? (string)$_POST['lga'] : "";
        $state_id = !empty($_POST['state_id']) ? (string)$_POST['state_id'] : "";
        
        if(!empty($business_string)){
            $activity = "Update";
            $purl = "update_business_api_level1_api.php";
            $pdata = [
                    'verify_string' => $business_string,
                    'name' => $name,
                    'description' => $description,
                    'phone' => $phone,
                    'email' => $email,
                    'address' => $address,
                    'phone' => $phone,
                    'town' => $town,
                    'email' => $email,
                    'lga' => $lga,
                    'state_id' => $state_id
                ];
        } else {
            $activity = "Creation";
            $purl = "add_business_api.php";
            $pdata = [
                    'user_string' => $session->verify_string,
                    'name' => $name,
                    'description' => $description,
                    'phone' => $phone,
                    'email' => $email,
                    'address' => $address,
                    'phone' => $phone,
                    'email' => $email,
                    'town' => $town,
                    'lga' => $lga,
                    'state_id' => $state_id
                ];   
        }
        
        $add_business = perform_post_curl($purl, $pdata);
        if($add_business){
            if($add_business->status == "success"){
                $business = $add_business->data;
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => "user",
                        'agent_string' => $session->verify_string,
                        'object_type' => "Businesses",
                        'object_string' => $business->verify_string,
                        "activity" => $activity,
                        "details" => "First Stage of Business Registration carried out by the User"
                    ];
                perform_post_curl($lpurl, $lpdata);
                $session->business_login($business->verify_string);
                redirect_to("add_business_cont");
            } else {
                $message = $add_business->message;
            }
        } else {
            $message = "Business Adding Link Broken";
        }
    } else {
        if(!empty($business_string)){
            $bgurl = "fetch_business_by_string_api.php?string=".$business_string;
            $businesses = perform_get_curl($bgurl);
            if($businesses){
                if($businesses->status == "success"){
                    $bus = $businesses->data;
                    $name = $bus->name;
                    $description = $bus->description;
                    $phone = $bus->phonenumber;
                    $email = $bus->email;
                    $address = $bus->address;
                    $town = $bus->town;
                    $lga = $bus->lga;
                    $state_id = $bus->state_id; 
                } else {
                    die($businesses->message);
                }
            } else {
                die("Business Link Broken");
            }
        } else {
            $name = "";
            $description = "";
            $phone = "";
            $email = "";
            $address = "";
            $town = "";
            $lga = "";
            $state_id = "";   
        }
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
                                <ul class="d-flex align-items-center justify-content-center col-11 mx-auto pe-5">
                                    <li class='col-5 list-unstyled d-flex align-items-center justify-content-center text-white' style='height:3rem;background-color:#00C853;font-size:12px'>Business 
                                    details</li>
                                    <li class=' col-5 list-unstyled d-flex align-items-center justify-content-center' style='height:3rem;background-color:rgba(101, 156, 240, 0.1); font-size:10px'>Business category</li>
                                    <li class=' col-5  list-unstyled d-flex align-items-center justify-content-center' style='height:3rem;background-color:rgba(101, 156, 240, 0.1);font-size:10px'>Business file</li>
                                </ul>
                        <div class="col-12">
                            <form role="form" action="add_business" method="POST" class="row g-3 needs-validation">
                                <div class="col-12">
                                    <label for="bus_name" class="form-label">Business Name</label>
                                    <div class="input-group has-validation">
    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-briefcase"></i></span>                                     
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
                                    <label for="bus_phone" class="form-label">Phone</label>
                                    <div class="input-group has-validation">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-telephone"></i></span>                                     
                                        <input type="tel" name="phone" id="bus_phone" class="form-control" placeholder="Business Phone Number" value="<?php echo $phone; ?>" required>
                                        <div class="invalid-feedback">Please provide the Business Phone Number</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="bus_email" class="form-label">Email</label>
                                    <div class="input-group has-validation">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-envelope"></i></span>                                     

                                        <input type="email" name="email" id="bus_email" class="form-control" placeholder="Business Email Address" value="<?php echo $email; ?>" required>
                                        <div class="invalid-feedback">Please provide the Business Email Address</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-2 float-left">
                                    <label for="bus_state" class="form-label">State</label>
                                     <div class="input-group has-validation">  
                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-geo"></i></span>                                      
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
                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-geo-alt"></i></span> 
                                        
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
                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-geo-alt"></i></span>                                     

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
                                <div class="col-12">
                                    <input type="submit" name="submit" class="btn btn-primary" style="float: right !important" value="Continue >>">
                                </div>    
                                    
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main> 

<?php include_portal_template("footer.php"); ?>