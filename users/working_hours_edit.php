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
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $string = !empty($exploded[1]) ? (string)$exploded[1] : "";
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
                                'agent_type' => "user",
                                'agent_string' => $session->verify_string,
                                'object_type' => "Businesses",
                                'object_string' => $session->business_string,
                                "activity" => "Update",
                                "details" => "Business Working Hour Edited"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Working Hour edited successfully");
                        redirect_to("working_hours");
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
                            <li><a href="working_hours">Business Working Hours</a></li>
                            <li>Update Working Hours</li>
                        </ol>
                        <h2>Business Working Hours</h2>
                        <p>The Time your Business is opened and you attending to Clients</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Business Working Hours</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card block">
                        <form role="form" action="working_hours_edit?<?php echo $string; ?>" method="POST" class="row g-3 needs-validation">
                            <div class="col-12">
                                <label for="work_day" class="form-label">Work Day</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-calendar"></i></span>
                                    <select name="day" id="work_day" class="form-control" required>
                                        <option value="">--Work Day--</option>
                                        <?php workdays_options($day); ?>
                                    </select>
                                    <div class="invalid-feedback">Please Select the Work Day.</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="opening_time" class="form-label">Opening Time</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-clock"></i></span>
                                    <select name="opening_time" id="opening_time"class="form-control" required>
                                        <option value="">--Opening Time--</option>
                                        <?php time_option($opening_time); ?>
                                    </select>
                                    <div class="invalid-feedback">Please Select the Opening Time.</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="closing_time" class="form-label">Closing Time</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-clock"></i></span>
                                    <select name="closing_time" id="closing_time"class="form-control">
                                        <option value="">--Closing Time--</option>
                                        <?php time_option($closing_time); ?>
                                    </select>
                                    <div class="invalid-feedback">Please Select the Closing Time.</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn w-100 text-white" style="background: #00C853;" id="work_submit" name="submit" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>