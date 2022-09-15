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


    $gurl = "fetch_privacy_policy_api.php";
    $privacy_policy = perform_get_curl($gurl);
    if($privacy_policy){
        if($privacy_policy->status == "success"){         
            $privacy_policy = $privacy_policy->data;
        } else {
            $message = "No privacy policy available";
        }
    } else {
        $message = "No privacy policy available";
        }
    

    if(isset($_POST['submit'])){
        $content = !empty($_POST['content']) ? (string)$_POST['content'] : "";
        $admin_string = !empty($session->verify_string) ? (string)$session->verify_string : "";

        if (!empty($privacy_policy)) {
            $purl = "update_privacy_policy_api.php";
            $pdata = [
                'admin_string' => $admin_string,
                'id' => $privacy_policy->id,
                'content' => $content
            ];
        } else {
            $purl = "add_privacy_policy_api.php";
            $pdata = [
                'admin_string' => $admin_string,
                'content' => $content
            ];
        }
   
        $update_privacy_policy = perform_post_curl($purl, $pdata);
        if($update_privacy_policy){
            if($update_privacy_policy->status == "success"){
                $response = $update_privacy_policy->data;

                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "PrivacyandPolicy",
                    'object_string' => $response->id,
                    "activity" => "Update",
                    "details" => "Privacy and Policy updated Successfully"
                ];
                perform_post_curl($lpurl, $lpdata);

                $session->message("privacy_policy has been updated successfully");
                $message = "privacy_policy has been updated successfully";
                redirect_to("dashboard");
            } else {
                $message = $update_privacy_policy->message;
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
                    <label for="content" class="form-label text-primary mb-0 font-weight-bold">Privacy/Policy:</label>
                    <textarea name="content" id="editor">
                        <?php echo $privacy_policy->content ?>
                    </textarea>
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