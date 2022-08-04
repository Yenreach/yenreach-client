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
    
    if(isset($_POST['submit'])){
        $address = !empty($_POST['address']) ? (string)$_POST['address'] : "";
        $town = !empty($_POST['town']) ? (string)$_POST['town'] : "";
        $lga = !empty($_POST['lga']) ? (string)$_POST['lga'] : "";
        $state_id = !empty($_POST['state_id']) ? (string)$_POST['state_id'] : "";
        $phone = !empty($_POST['phone']) ? (string)$_POST['phone'] : "";
        $email = !empty($_POST['email']) ? (string)$_POST['email'] : "";
        $head_designation = !empty($_POST['head_designation']) ? (string)$_POST['head_designation'] : "";
        $head_name = !empty($_POST['head_name']) ? (string)$_POST['head_name'] : "";
        
        $purl = "add_branch_api.php";
        $pdata = [
                'business_string' => $session->business_string,
                'address' => $address,
                'town' => $town,
                'lga' => $lga,
                'state_id' => $state_id,
                'phone' => $phone,
                'email' => $email,
                'head_designation' => $head_designation,
                'head_name' => $head_name
            ];
            
        $add_branch = perform_post_curl($purl, $pdata);
        if($add_branch){
            if($add_branch->status == "success"){
                $branched = $add_branch->data;
                
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => "user",
                        'agent_string' => $session->verify_string,
                        'object_type' => "Branches",
                        'object_string' => $branched->verify_string,
                        "activity" => "Create",
                        "details" => "Branch Added to the Business"
                    ];
                perform_post_curl($lpurl, $lpdata);
                $session->message("Branch added successfully");
                redirect_to("branches");
            } else {
                $message = $add_branch->message;
            }
        } else {
            $message = "Add Branch Link Broken!";
        }
    } else {
        $address = "";
        $town = "";
        $lga = "";
        $state_id = "";
        $phone = "";
        $email = "";
        $head_designation = "";
        $head_name = "";
    }
    
    $gurl = "fetch_business_branches_api.php?string=".$session->business_string;
    $branches = perform_get_curl($gurl);
    if($branches){
        
    } else {
        die("Branches Link Broken");
    }
    
    $sgurl = "fetch_business_latest_subscription_api.php?string=".$session->business_string;
    $subscribes = perform_get_curl($sgurl);
    if($subscribes){
        
    } else {
        die("Subscrption Check Link Broken");
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
    
    $dgurl = "fetch_all_designations_api.php";
    $designations = perform_get_curl($dgurl);
    if($designations){
        
    } else {
        die("Designation Link Broken");
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
                    <div class="card-body card-block">
                        <div class="table-responsive text-dark">
                            <?php
                                if($branches->status == "success"){
                            ?>
                                    <table class="table table-responsive-sm table-striped table0bordered text-dark">
                                        <thead>
                                            <tr>
                                                <th>Address</th>
                                                <th>Branch Head</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($branches->data as $branch){
                                            ?>
                                                    <tr>
                                                        <td><?php
                                                            echo nl2br($branch->address).",";
                                                            echo "<br />".$branch->town.", ".$branch->lga." LGA,";
                                                            echo "<br />".$branch->state;
                                                        ?></td>
                                                        <td><?php
                                                            echo $branch->head_name."<br />";
                                                            echo "({$branch->head_designation})";
                                                        ?></td>
                                                        <td><?php echo $branch->phone; ?></td>
                                                        <td><?php echo $branch->email; ?></td>
                                                        <td><a href="branch_edit?<?php echo $branch->verify_string; ?>" class="text-primary">Edit</a></td>
                                                        <td><a href="branch_delete?<?php echo $branch->verify_string; ?>" class="text-danger"
                                                        onclick="return confirm('Do you really want to delete this Branch?')">Delete</a></td>
                                                    </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                            <?php
                                } else {
                                    echo '<p>'.$branches->message.'</p>';
                                }
                            ?>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            if($subscribes->status == "success"){
                $sub = $subscribes->data;
                $time = time();
                if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                    $pgurl = "fetch_business_subscription_by_string_api.php?string=".$sub->subscription_string;
                    $subscriptions = perform_get_curl($pgurl);
                    if(($subscriptions) && ($subscriptions->status == "success")){
                        if($branches->status == "success"){
                            $counted = count($branches->data);   
                        } else {
                            $counted = 0;
                        }
                        $subscription = $subscriptions->data;
                        if($counted < $subscription->branches){
                        
        ?>
                            <div class="row">
                                <div class="col-lg-9 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title text-success">Add a Branch</h4>
                                            <p>
                                                <?php
                                                    $remainder = $subscription->branches - $counted;
                                                    echo "You can still add {$remainder} more branch(es)";
                                                ?>
                                            </p>
                                        </div>
                                        <div class="card-body card-block">
                                            <form role="form" action="branches" method="POST" class="row g-3 needs-validation">
                                                <div class="col-md-6 mb-2">
                                                    <label for="State" class="form-label mt-1">State</label>
                                                    <div class="input-group has-validation">
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
                                                        <div class="invalid-feedback">Please select the State</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label for="LGA" class="form-label mt-1">Local Government Area</label>
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
                                                <div class="col-12 mb-2">
                                                    <label for="Town" class="form-label mt-1">Town</label>
                                                    <div class="input-group has-validation">
                                                        <input type='text' name="town" class="form-control" value="<?php echo $town; ?>" required>
                                                        <div class="invalid-feedback">Please Select the Town the Branch is situated.</div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 ">
                                                    <label for="Town" class="form-label">Address</label>
                                                    <div class="input-group has-validation">
                                                        <textarea id="address" name="address" rows="5" class="form-control" required><?php echo $address; ?></textarea>
                                                        <div class="invalid-feedback">Please Select the Town the Branch is situated.</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="head_designation" class="form-label">Position</label>
                                                    <div class="input-group has-validation">
                                                        <input type="text" id="head_designation" name="head_designation" class="form-control" list="designation_list" value="<?php echo $head_designation ?>" placeholder="e.g Branch Manager" required>
                                                        <datalist id="categ_list">
                                                            <?php
                                                                if($designations->status == "success"){
                                                                    foreach($designations->data as $designation){
                                                                        echo '<option value="'.$designation->designation.'">';
                                                                    }
                                                                }
                                                            ?>
                                                        </datalist>
                                                        <div class="invalid-feedback">Please provide the Designation of the Person in charge of this Branch.</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="name" class="form-label mt-1">Name of Person in Charge</label>
                                                    <div class="input-group has-validation">
                                                        <input type="text" id="head_name" name="head_name" class="form-control" value="<?php echo $head_name; ?>"  required>
                                                        <div class="invalid-feedback">Please Provide the Name of the Person in Charge of this branch.</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="contact" class="form-label mt-1">Contact Number</label>
                                                    <div class="input-group has-validation">
                                                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" required>
                                                        <div class="invalid-feedback">Please Provide the Phone Number.</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="email" class="form-label mt-1">Contact Email</label>
                                                    <div class="input-group has-validation">
                                                        <input type="email"  class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <button class="btn w-100 text-white py-2" style="background: #00C853;" id="work_submit" name="submit" type="submit">Submit</button>
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
                                    <h4 class="card-title">Add Branch</h4>
                                </div>
                                <div class="card-body card-block">
                                    <p>
                                        To add a Branch of your Business, you will need to upgrade to any of our subscription packages.
                                        To learn more, click <a href="subscription_packages" class="text-primary">
                                            here
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
                                <h4 class="card-title">Add Branch</h4>
                            </div>
                            <div class="card-body card-block">
                                <p>
                                    To add a Branch of your Business, you will need to upgrade to any of our subscription packages.
                                    To learn more, click <a href="subscription_packages" class="text-primary">
                                        here
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

<?php include_portal_template("footer.php"); ?>