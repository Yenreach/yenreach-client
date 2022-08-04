<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_branch_by_string_api.php?string=".$string;
    $branches = perform_get_curl($gurl);
    if($branches){
        if($branches->status == "success"){
            $branch = $branches->data;
            
            if(isset($_POST['submit'])){
                $address = !empty($_POST['address']) ? (string)$_POST['address'] : "";
                $town = !empty($_POST['town']) ? (string)$_POST['town'] : "";
                $lga = !empty($_POST['lga']) ? (string)$_POST['lga'] : "";
                $state_id = !empty($_POST['state_id']) ? (string)$_POST['state_id'] : "";
                $phone = !empty($_POST['phone']) ? (string)$_POST['phone'] : "";
                $email = !empty($_POST['email']) ? (string)$_POST['email'] : "";
                $head_designation = !empty($_POST['head_designation']) ? (string)$_POST['head_designation'] : "";
                $head_name = !empty($_POST['head_name']) ? (string)$_POST['head_name'] : "";
                
                $purl = "update_branch_api.php";
                $pdata = [
                        'verify_string' => $branch->verify_string,
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
                                'agent_type' => "admin",
                                'agent_string' => $session->verify_string,
                                'object_type' => "Branches",
                                'object_string' => $branched->verify_string,
                                "activity" => "Update",
                                "details" => "Branch edited successfully"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Branch updated successfully");
                        redirect_to("business?{$branched->business_string}");
                    } else {
                        $message = $add_branch->message;
                    }
                } else {
                    $message = "Update Branch Link Broken!";
                }
            } else {
                $address = $branch->address;
                $town = $branch->town;
                $lga = $branch->lga;
                $state_id = $branch->state_id;
                $phone = $branch->phone;
                $email = $branch->email;
                $head_designation = $branch->head_designation;
                $head_name = $branch->head_name;
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
        } else {
            die($branches->message);
        }
    } else {
        die("Branch Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4><?php echo $branch->business; ?></h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Businesses</a></li>
                        <li class="breadcrumb-item"><a href="all_businesses">All Businesses</a></li>
                        <li class="breadcrumb-item"><a href="business?<?php echo $branch->business_string; ?>"><?php echo $branch->business; ?></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Business Branch</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Business Branch</h4>
                        </div>
                        <div class="card-body card-block">
                            <form action="edit_branch?<?php echo $branch->verify_string; ?>" method="POST">
                                <div class="col-md-6 mb-2 float-left">
                                    <div class="form-group">
                                        <label class="form-label" for="bus_state">State</label>
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
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2 float-left">
                                    <div class="form-group">
                                        <label class="form-label" for="bus_lga">Local Government Area</label>
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
                                    </div>
                                </div>
                                <div class="col-12 mb-2 float-left">
                                    <div class="form-group">
                                        <label for="bus_town" class="form-label">Town</label>
                                        <input type='text' id="bus_town" name="town" class="form-control" value="<?php echo $town; ?>" required>
                                    </div>
                                </div>
                                <div class="col-12 mb-2 float-left">
                                    <div class="form-group">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea id="address" name="address" rows="5" class="form-control" required><?php echo $address; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-12 mb-2 float-left">
                                    <div class="form-group">
                                        <label class="form-label" for="head_designation">Designation of the Person In Charge</label>
                                        <input type="text" id="head_designation" name="head_designation" class="form-control" list="designation_list" value="<?php echo $head_designation ?>" placeholder="e.g Branch Manager" required>
                                        <datalist id="designation_list">
                                            <?php
                                                if($designations->status == "success"){
                                                    foreach($designations->data as $designation){
                                                        echo '<option value="'.$designation->designation.'">';
                                                    }
                                                }
                                            ?>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-12 mb-2 float-left">
                                    <div class="form-group">
                                        <label class="form-label" for="head_name">Name of Person In Charge</label>
                                        <input type="text" id="head_name" name="head_name" class="form-control" value="<?php echo $head_name; ?>"  required>
                                    </div>
                                </div>
                                <div class="col-12 mb-2 float-left">
                                    <div class="form-group">
                                        <label for="contact" class="form-label">Contact Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" required>
                                    </div>
                                </div>
                                <div class="col-12 mb-2 float-left">
                                    <div class="form-group">
                                        <label for="email" class="form-label mt-1">Contact Email</label>
                                        <input type="email"  class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-2 float-left">
                                    <div class="text-center">
                                        <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>