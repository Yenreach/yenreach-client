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
    
    $gurl = "fetch_business_by_string_api.php?string=".$session->business_string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
            
            if(isset($_POST['submit'])){
                $month_started = !empty($_POST['month_started']) ? (string)$_POST['month_started'] : "";
                $year_started = !empty($_POST['year_started']) ? (string)$_POST['year_started'] : "";
                
                $purl = "update_business_api_level2_api.php";
                $pdata = [
                        'verify_string' => $business->verify_string,
                        'month_started' => $month_started,
                        'year_started' => $year_started
                    ];
                
                $update_business = perform_post_curl($purl, $pdata);
                if($update_business){
                    if($update_business->status == "success"){
                        $updated = $update_business->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => "user",
                                'agent_string' => $session->verify_string,
                                'object_type' => "Businesses",
                                'object_string' => $business->verify_string,
                                "activity" => "Update",
                                "details" => "Second Stage of Business Registration carried out by the User"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                
                        $session->business_login($business->verify_string);
                        redirect_to("add_business_comp");
                    } else {
                        $message = join(' ', $update_business->message);
                    }
                } else {
                    $message = "Business Update Link Broken";
                }
            } else {
                $month_started = $business->month_started;
                $year_started = $business->year_started;
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
                        <ul class="row p-0">
                            <li class='col-4 list-unstyled d-flex align-items-center justify-content-center text-white' style='height:3rem;background-color:#00C853;font-size:12px'>Business 
                            details</li>
                            <li class=' col-4 list-unstyled d-flex align-items-center justify-content-center text-white' style='height:3rem;background-color:#00C853; font-size:10px'>Business category</li>
                            <li class=' col-4  list-unstyled d-flex align-items-center justify-content-center' style='height:3rem;background-color:rgba(101, 156, 240, 0.1);font-size:10px'>Business file</li>
                        </ul>
                        <div class="col-12">
                            <!--<form role="form" action="add_business_cont" id="add_category" method="POST" id="add_category_business" class="row g-3 needs-validation">-->
                                <div class="col-12">
                                    <label for="bus_category" class="form-label" id="categ_label">Categories(You can choose up to 5 Categories)</label>
                                    <div class="col-12" style="height: auto" id="category_list"></div>
                                    <div class="input-group has-validation">
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
                            <!--</form>-->
                            <form role="form" action="add_business_cont" id="add_category" method="POST" class="mt-3 row g-3 needs-validation">
                                <div class="col-md-6">
                                    <label for="bus_month_started" class="form-label">Month Business Started</label>
                                    <div class="input-group">
                                        <select name="month_started" id="bus_month_started" class="form-control">
                                            <option value="">--Month--</option>
                                            <?php months($month_started); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="bus_year_started" class="form-label">Year Business Stated</label>
                                    <select name="year_started" id="bus_year_started" class="form-control">
                                        <option value="">--Year--</option>
                                        <?php echo gallery_year($year_started); ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <input type="submit" name="submit" class="btn btn-primary" style="float: right !important" value="Continue &gt; &gt;">
                                    <a href="add_business" class="btn btn-danger" onclick="return confirm('Do you want to go back? Please note that details entered into at this Stage will be lost')"
                                    style="float: left !important">&lt; &lt; Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>