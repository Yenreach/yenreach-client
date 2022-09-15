<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("login");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $agurl = "fetch_admin_by_string_api.php?string=".$session->verify_string;
    $admins = perform_get_curl($agurl);
    if($admins){
        if($admins->status == "success"){
            $admin = $admins->data;
        } else {
            die($admins->message);
        }
    } else {
        die("Admin Link Broken");
    }
    
    if(isset($_POST['submit'])){
        $title = !empty($_POST['title']) ? (string)$_POST['title'] : "";
        $content = !empty($_POST['content']) ? (string)$_POST['content'] : "";
        $admin_string = !empty($session->verify_string) ? (string)$session->verify_string : "";

        $purl = "add_email_sequence_api.php";
        $pdata = [
                'title' => $title,
                'content' => $content,
                'admin_string' => $admin_string
            ];
        $add_email = perform_post_curl($purl, $pdata);
        if($add_email){
            if($add_email->status == "success"){
                $response = $add_email->data;

                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => $session->user_type,
                        'agent_string' => $session->verify_string,
                        'object_type' => "Email",
                        'object_string' => $response->id,
                        "activity" => "Create",
                        "details" => "Email Added Successfully"
                    ];
                        
                perform_post_curl($lpurl, $lpdata);

                $session->message("Email has been added` successfully");
                $message = "Email has been added successfully";
                redirect_to("emails");
            } else {
                $message = $add_email->message;
            }
        } else {
            $message = "Something went wrong";
        }
    } else {
        $title =  "";
        $content = "";
    }
    
    
    include_admin_template("header.php");

?>
<style>
    .ck-rounded-corners .ck.ck-editor__main>.ck-editor__editable, .ck.ck-editor__main>.ck-editor__editable.ck-rounded-corners { color: #454545;}

</style>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi, <?php echo $admin->name; ?></h4>
                            <!-- <p class="mb-0">Make a blog post today</p> -->
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"><a href="javascript:void(0)"><i class="fa fa-home"></i></a></li>
                        </ol>
                    </div>
                </div>
                
                <!-- <div id="editor" class=""></div> -->
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="d-flex flex-column mb-2">
                        <label for="title" class="form-label text-primary mb-0 font-weight-bold">Email Title:</label>
                        <input class="form-control mb-1" type="text" name="title" id="title" placeholder="Email Title" required>
                    </div>
                    <label for="content" class="form-label text-primary mb-0 font-weight-bold">Email Content:</label>
                    <textarea name="content" id="editor" required>
                    </textarea>
                    <p><input class="btn btn-primary btn mt-2" type="submit" name="submit" value="Submit" required></p>
                    <?php 
                        if(!empty($message)){
                            echo "<p class='text-danger'>{$message}</p>";
                        }
                    ?>
                </form>
            </div>
        </div>
 <?php include("tinymceditor.php") ?>
<?php include_admin_template("footer.php"); ?>