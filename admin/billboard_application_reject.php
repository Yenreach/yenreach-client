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
    $gurl = "fetch_billboard_application_by_string_api.php?string=".$string;
    $applications = perform_get_curl($gurl);
    if($applications){
        if($applications->data == "success"){
            $application = $applications->data;
            
            if(isset($_POST['submit'])){
                $remarks = !empty($_POST['remarks']) ? (string)$_POST['remarks'] : "";
                
                $purl = "rejectt_billboard_application_api.php";
                $pdata = [
                        "verify_string" => $application->verify_string,
                        "remarks" => $remarks,
                        "agent_type" => $session->user_type,
                        "agent_string" => $session->verify_string
                    ];
                
                $reverts = perform_post_curl($purl, $pdata);
                if($reverts){
                    if($reverts->status == "success"){
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "BillboardApplications",
                                'object_string' => $application->verify_string,
                                "activity" => "Update",
                                "details" => "Billboard Application - {$application->code} was rejected"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Billboard Application has been rejected");
                        redirect_to("billboard_applications_pending");
                    } else {
                        $message = $reverts->message;
                    }
                } else {
                    $message = "Reject Application Link Broken";
                }
            } else {
                $remarks = "";
            }
        } else {
            die($applications->message);
        }
    } else {
        die("Pending Application Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Pending Billboard Applications</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Billboard Applications</a></li>
                        <li class="breadcrumb-item"><a href="billboard_applications_pending">Pending Billboard Application</a></li>
                        <li class="breadcrumb-item"><a href="billboard_application_pending?<?php echo $application->verify_stringl ?>">Application - <?php echo $application->code; ?></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Reject Application</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Reject Pending Application</h4>
                        </div>
                        <div class="card-body card-block">
                            <form action="billboard_application_reject?<?php echo $application->verify_string; ?>" method="POST">
                                <div class="form-group">
                                    <label for="revert_msg">Please state your reason for rejecting this Application. <br />
                                    Please note that this will also be sent as a message to the Applicant</label>
                                    <textarea name="remarks" class="form-control" rows="10" required placeholder="Remarks"></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>