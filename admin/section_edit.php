<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout.php");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout.php");
    }
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    $gurl = "fetch_section_by_string_api.php?string=".$string;
    $sections = perform_get_curl($gurl);
    if($sections){
        if($sections->status == "success"){
            $sect = $sections->data;
            
            if(isset($_POST['submit'])){
                $section = !empty($_POST['section']) ? (string)$_POST['section'] : "";
                $details = !empty($_POST['details']) ? (string)$_POST['details'] : "";
                
                $purl = "update_section_api.php";
                $pdata = [
                        "verify_string" => $sect->verify_string,
                        "section" => $section,
                        "details" => $details
                    ];
                
                $update_sect = perform_post_curl($purl, $pdata);
                if($update_sect){
                    if($update_sect->status == "success"){
                        $update = $update_sect->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "Sections",
                                'object_string' => $sect->verify_string,
                                "activity" => "Update",
                                "details" => "{$sect->section} being Updated"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        
                        $session->message("Edit was successful");
                        redirect_to("section?string=".$sect->verify_string);
                    } else {
                        $message = $update_sect->message;
                    }
                } else {
                    $message = "Section Update Link Broken";
                }
            } else {
                $section = $sect->section;
                $details = $sect->details;
            }
        } else {
            die($sections->message);
        }
    } else {
        die("Section Link Broken");
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
                        <li class="breadcrumb-item"><a href="sections">Sections</a></li>
                        <li class="breadcrumb-item"><a href="section?string=<?php echo $string; ?>"><?php echo $sect->section; ?></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Section</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Section</h4>
                            <?php echo output_message($message); ?>
                        </div>
                        <div class="card-body">
                            <form action="section_edit?string=<?php echo $string; ?>" method="POST">
                                <div class="form-group">
                                    <input type="text" name="section" id="section_section" class="form-control" placeholder="Section" value="<?php echo $section; ?>">
                                </div>
                                <div class="form-group">
                                    <textarea name="details" row="5" id="section_details" class="form-control" placeholder="Details"><?php echo $details; ?></textarea>
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