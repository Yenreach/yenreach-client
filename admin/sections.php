<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout.php");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout.php");
    }
    
    if(isset($_POST['submit'])){
        $section = !empty($_POST['section']) ? (string)$_POST['section'] : "";
        $details = !empty($_POST['details']) ? (string)$_POST['details'] : "";
        
        $purl = "add_section_api.php";
        $pdata = [
                'section' => $section,
                'details' => $details
            ];
        
        $add_section = perform_post_curl($purl, $pdata);
        if($add_section){
            if($add_section->status == "success"){
                $sect = $add_section->data;
                
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => $session->user_type,
                        'agent_string' => $session->verify_string,
                        'object_type' => "Sections",
                        'object_string' => $sect->verify_string,
                        "activity" => "Creation",
                        "details" => "{$sect->section} added as a Section"
                    ];
                perform_post_curl($lpurl, $lpdata);
                
                $session->message("{$section} has been added as a Section");
                redirect_to("sections");
            } else {
                $message = $add_section->message;
            }
        } else {
            $message = "Section Addition Link Broken";
        }
    } else {
        $section = "";
        $details = "";
    }
    
    $gurl = "fetch_all_sections_api.php";
    $sections = perform_get_curl($gurl);
    if($sections){
        
    } else {
        die("Sections Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Sections</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Sections</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Sections</h4>
                        </div>
                        <div class="card-body card-block text-dark">
                            <?php
                                if($sections->status == "success"){
                            ?>
                                    <div class="table-responsive text-dark">
                                        <table class="table table-striped table-responsive-sm text-dark">
                                            <thead>
                                                <tr>
                                                    <th>Section</th>
                                                    <th>Date Added</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($sections->data as $sec){
                                                ?>
                                                        <tr>
                                                            <td><?php echo $sec->section; ?></td>
                                                            <td><?php echo strftime("%Y.%m.%d", $sec->created); ?></td>
                                                            <td><a href="section?string=<?php echo $sec->verify_string; ?>" class="text-primary">View Section</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                                <tr>
                                                    <td>Others</td>
                                                    <td></td>
                                                    <td><a href="section?string=others" class="text-primary">View Section</a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                            <?php
                                } else {
                                    echo $sections->message;
                                }
                            ?>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Section</h4>
                            <?php echo output_message($message); ?>
                        </div>
                        <div class="card-body">
                            <form action="sections" method="POST">
                                <div class="form-group">
                                    <input type="text" name="section" id="section_section" class="form-control" placeholder="Section" value="<?php echo $section; ?>">
                                </div>
                                <div class="form-group">
                                    <textarea name="details" row="5" id="section_details" rows="5" class="form-control" placeholder="Details"><?php echo $details; ?></textarea>
                                </div>
                                <div class="text-center">
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