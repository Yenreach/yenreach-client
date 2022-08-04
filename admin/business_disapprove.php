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

    $verify_string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_business_by_string_api.php?string=".$verify_string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
            
            if(isset($_POST['submit'])){
                $remarks = !empty($_POST['remarks']) ? (string)$_POST['remarks'] : "";
                
                $purl = "disapprove_business_api.php";
                $pdata = [
                        'verify_string' => $business->verify_string,
                        'remarks' => $remarks
                    ];
                
                $disapprove = perform_post_curl($purl, $pdata);
                if($disapprove){
                    if($disapprove->status == "success"){
                        $bus = $disapprove->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "Businesses",
                                'object_string' => $bus->verify_string,
                                "activity" => "Business Disapproval",
                                "details" => "{$bus->name} was disapproved"
                            ];
                                        
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Business was successfully disapproved");
                        redirect_to("pending_businesses");
                    } else {
                        $message = $disapprove->message;
                    }
                } else {
                    $message = "Business Disapproval Link Broken";
                }
            } else {
                $remarks = "";
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
                        <h4>Pending Businesses</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Businesses</a></li>
                        <li class="breadcrumb-item"><a href="officialmails.php">Pending Businesses</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $business->name; ?></a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Disapprove Business</h4>
                        </div>
                        <div class="card-body card-block">
                            <form action="business_disapprove?<?php echo $verify_string; ?>" method="POST">
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea name="remarks" id="disapprove_remarks" rows="10" class="form-control" placeholder="Reason for Approval. Please note that this will be sent as part of the mail to the Business Owner"><?php echo $remarks; ?></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="submit" class="btn btn-primary btn-block">Disapprove</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>