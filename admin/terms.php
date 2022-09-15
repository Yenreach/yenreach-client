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


    $gurl = "fetch_terms_api.php";
    $terms = perform_get_curl($gurl);
    if($terms){
        if($terms->status == "success"){         
            $terms = $terms->data;
        } else {
            $message = "No terms available";
        }
    } else {
        $message = "No privacy policy available";
        }
    

    if(isset($_POST['submit'])){
        $content = !empty($_POST['content']) ? (string)$_POST['content'] : "";
        $admin_string = !empty($session->verify_string) ? (string)$session->verify_string : "";

        if (!empty($terms)) {
            $purl = "update_terms_api.php";
            $pdata = [
                'admin_string' => $admin_string,
                'id' => $terms->id,
                'content' => $content
            ];
        } else {
            $purl = "add_terms_api.php";
            $pdata = [
                'admin_string' => $admin_string,
                'content' => $content
            ];
        }
   
        $update_terms = perform_post_curl($purl, $pdata);
        if($update_terms){
            if($update_terms->status == "success"){
                $response = $update_terms->data;

                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "Terms",
                    'object_string' => $response->id,
                    "activity" => "Update",
                    "details" => "Terms updated Successfully"
                ];
                perform_post_curl($lpurl, $lpdata);

                $session->message("Terms has been updated successfully");
                $message = "Terms has been updated successfully";
                redirect_to("dashboard");
            } else {
                $message = $update_terms->message;
            }
        } else {
            $message = "Something went wrong during update";
        }
        
    } else {
        $content = "";
    }

    include_admin_template("header.php");
?>
<style>
    .ck-rounded-corners .ck.ck-editor__main>.ck-editor__editable, .ck.ck-editor__main>.ck-editor__editable.ck-rounded-corners { color: #454545;}
    .cke_wysiwyg_frame, .cke_wysiwyg_div {
        min-height: 60vh;
    }
    .cke_top, .cke_contents, .cke_bottom {
        height: 100%;
    }
</style>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi, <?php echo $admin->name; ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"><a href="javascript:void(0)"><i class="fa fa-home"></i></a></li>
                        </ol>
                    </div>
                </div>
                
                <!-- <div id="editor" class=""></div> -->
                <form action="" method="post">
                    <label for="content" class="form-label text-primary mb-0 font-weight-bold">Terms:</label>
                    <!--<textarea name="content" id="editor">-->
            
                    <!--</textarea>-->
                    <textarea id="open-source-plugins" name="content"><?php echo $terms->content ?></textarea>
                    <p><input class="btn btn-primary btn mt-2" type="submit" name="submit" value="Submit"></p>
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