<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to('logout.php');
    }
    if($session->user_type != "admin"){
        redirect_to("logout.php");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_business_by_string_api.php?string=".$string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
            
            if(isset($_POST['submit'])){
                $state_id = !empty($_POST['state_id']) ? (int)$_POST['state_id'] : "";
                $lga = !empty($_POST['lga']) ? (string)$_POST['lga'] : "";
                $town = !empty($_POST['town']) ? (string)$_POST['town'] : "";
                $address = !empty($_POST['address']) ? (string)$_POST['address'] : "";
                
                $purl = "edit_pending_business_api.php";
                $pdata = [
                        'verify_string' => $business->verify_string,
                        'state_id' => $state_id,
                        'lga' => $lga,
                        'town' => $town,
                        'address' => $address
                    ];
                
                $edit_business = perform_post_curl($purl, $pdata);
                if($edit_business){
                    if($edit_business->status == "success"){
                        $bus = $edit_business->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "Businesses",
                                'object_string' => $bus->verify_string,
                                "activity" => "Update",
                                "details" => "{$bus->name} being edited by Admin"
                            ];
                                        
                        perform_post_curl($lpurl, $lpdata);
                        
                        $session->message("Business Edited successfully");
                        redirect_to("pending_busness?".$bus->verify_string);
                    } else {
                        $message = $edit_business->message;
                    }
                } else {
                    $message = "Business Edit Link Broken";
                }
            } else {
                $state_id = $business->state_id;
                $lga = $business->lga;
                $town = $business->town;
                $address = $business->address;
            }
            $cgurl = "fetch_all_categories_api.php";
            $categories = perform_get_curl($cgurl);
            if($categories){
                
            } else {
                die("Categories Link Broken");
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
        } else {
            die($businesses->message);
        }
    } else {
        die("Businesses Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Pending Businesses</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Businesses</a></li>
                        <li class="breadcrumb-item"><a href="pending_businesses">Pending Businesses</a></li>
                        <li class="breadcrumb-item"><a href="business?<?php echo $business->verify_string; ?>"><?php $business->name; ?></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Business</a></li>
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
                            <form action="edit_pending_business?<?php echo $business->verify_string; ?>" method="POST">
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
                                <div class="col-12">
                                    <div class="form-group float-left">
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