<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $gurl = "fetch_business_by_user_string_api.php?string=".$session->verify_string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            
        } else {
            $session->message("Please add your Business details");
            redirect_to("add_new_business");
        }
    } else {
        die("Businesses Link Broken");
    }
    
    include_portal_template("header.php");
?>

    <main id="main" class="main">
        <div class="row">
            <div class="col-12">
                <div class="bg-light p-4 rounded text-center">
                    <a href="add_new_business" class="btn btn-primary">Add New Business</a>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <?php
                if($businesses->status == "success"){
                    foreach($businesses->data as $business){
                        $desc_array = explode(' ', $business->description);
                        $counted = count($desc_array);
                        $array_desc = array();
                        if($counted >= 20){
                            for($i=0; $i<=19; $i++){
                                $array_desc[] = $desc_array[$i];
                            }
                        } else {
                            for($i=0; $i<=$counted-1; $i++){
                                $array_desc[] = $desc_array[$i];
                            }
                        }
                        $description = join(' ', $array_desc);
            ?>
                        <div class="col-lg-6 col-md-8 col-sm-12">
                            <div class="card" style="height: 650px !important">
                                <div class="card-header">
                                    <h4 class="card-title">Business</h4>
                                </div>
                                <div class="card-body card-block">
                                    <div class="table-responsive">
                                        <table class="table table-responsive-sm">
                                            <thead>
                                                <tr>
                                                    <th class='text-muted'>Business Name</th>
                                                    <th class='text-muted'>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody class='text-muted'>
                                            
                                                    <tr>
                                                        <td class='h-50 w-50'><?php echo $business->name; ?></td>
                                                        <td class='h-50 w-50'><?php echo $description; ?></td>
                                                    </tr>
                                            </tbody>
                                        </table>
                                        <div class='col-10 d-flex flex-column justify-content-between align-items-center py-2 border-b-1' style='height:8rem'>
                                            <div class='col-12 d-flex align-items-center justify-content-between'>
                                                <h4 style='font-size:16px' class='font-weight-bold'>Business Location:</h4>
                                                <span class='d-block col-4 font-weight-bold'><?php echo $business->town.", ".$business->state ?></span>
                                            </div>
                                            <div class='col-12 d-flex align-items-center justify-content-between'>
                                                <h4 style='font-size:16px'>Date Registered:</h4>    
                                                <span class='d-block col-4'><?php echo strftime("%Y.%m.%d", $business->created); ?></span>
                                            </div>
                                            <div class='col-12 d-flex align-items-center justify-content-between'>
                                                <h4 style='font-size:16px'>Status:</h4>
                                                <div class='d-flex justify-content-start col-4'>
                                                    <?php
                                                        if($business->reg_stage == 0){
                                                            echo '<span class="text-danger  rounded">Suspended/Deactivated</span>';
                                                        } elseif($business->reg_stage < 3){
                                                            echo '<span class="text-warning   rounded ">Incomplete Registration</span>';
                                                        } elseif($business->reg_stage == 3){
                                                            echo '<span class="text-primary  rounded">Pending Approval</span>';
                                                        } elseif($business->reg_stage == 4){
                                                            echo '<span class="text-success  rounded">Approved</span>';
                                                        }
                                                    ?>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="load_business?<?php echo $business->verify_string; ?>" class="btn btn-success btn-block">Load Business</a>
                                </div>
                            </div>    
                        </div>
            <?php
                    }
                } else {
                    echo '<p class="text-light">'.$businesses->message.'</p>';
                }
            ?>
        </div>
        
    </main>   

<?php include_portal_template("footer.php"); ?>