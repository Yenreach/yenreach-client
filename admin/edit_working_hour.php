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
    $gurl = "fetch_business_working_hours_by_string_api.php?string=".$string;
    $hours = perform_get_curl($gurl);
    if($hours){
        if($hours->status == "success"){
            $hour = $hours->data;
            
            if(isset($_POST['submit'])){
                $day = !empty($_POST['day']) ? (string)$_POST['day'] : "";
                $opening_time = !empty($_POST['opening_time']) ? (string)$_POST['opening_time'] : "";
                $closing_time = !empty($_POST['closing_time']) ? (string)$_POST['closing_time'] : "";
                
                $purl = "update_business_working_hours_api.php";
                $pdata = [
                        'verify_string' => $hour->verify_string,
                        'day' => $day,
                        'opening_time' => $opening_time,
                        'closing_time' => $closing_time
                    ];
                    
                $update_hours = perform_post_curl($purl, $pdata);
                if($update_hours){
                    if($update_hours->status == "success"){
                        $update = $update_hours->data;
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => "admin",
                                'agent_string' => $session->verify_string,
                                'object_type' => "Businesses",
                                'object_string' => $update->business_string,
                                "activity" => "Update",
                                "details" => "Business Working Hour Edited"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Working Hour edited successfully");
                        redirect_to("business?".$update->business_string);
                    } else {
                        $message = $update_hours->message;
                    }
                } else {
                    $message = "Business Working Hours Update Link Broken";
                }
            } else {
                $day = $hour->day;
                $opening_time = $hour->opening_time;
                $closing_time = $hour->closing_time;
            }
        }
    } else {
        die("Business Working Hours Link Broken");
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Working Hours</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Business Working Hour</h4>
                        </div>
                        <div class="card-body card-block">
                            <form action="edit_working_hour?<?php echo $string; ?>" method="POST">
                                <div class="col-12 float-left">
                                    <div class="form-group">
                                        <label class="form-label" for="work_day">Work Day</label>
                                        <select name="day" id="work_day" class="form-control">
                                            <option value="">--Work Day--</option>
                                            <?php workdays_options($day); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 float-left">
                                    <div class="form-group">
                                        <label class="form-label" for="closing_time">Opening Time</label>
                                        <select name="opening_time" id="opening_time"class="form-control" required>
                                            <option value="">--Opening Time--</option>
                                            <?php time_option($opening_time); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 float-left">
                                    <div class="form-group">
                                        <label class="form-label" for="closing_time">Opening Time</label>
                                        <select name="closing_time" id="closing_time"class="form-control" required>
                                            <option value="">--Closing Time--</option>
                                            <?php time_option($closing_time); ?>
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