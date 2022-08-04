<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout.php");
    }
    if($session->user_type != "admin"){
        redirect_to("logout.php");
    }
    if($session->user_autho_level > 2){
        redirect_to("logout.php");
    }
    
    $agurl = "fetch_admin_by_string_api.php?string=".$session->verify_string;
    $admins = perform_get_curl($agurl);
    if($admins){
        if($admins->status == "success"){
            $admin = $admins->data;
            
            if(isset($_POST['submit'])){
                $sender = !empty($_POST['sender']) ? (string)$_POST['sender'] : "";
                $subject = !empty($_POST['subject']) ? (string)$_POST['subject'] : "";
                $content = !empty($_POST['content']) ? (string)$_POST['content'] : "";
                
                $purl = "send_bulk_mail_api.php";
                //$purl = "send_users_without_businesses_mail_api.php";
                $pdata = [
                        'sender' => $sender,
                        'subject' => $subject,
                        'content' => $content
                    ];
                    
                $send_mail = perform_post_curl($purl, $pdata);
                if($send_mail){
                    if($send_mail->status == "success"){
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "Users",
                                'object_string' => "",
                                "activity" => "Send Mail",
                                "details" => "Bulk Mail sent to Users without Businesses"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        
                        $session->message("Message was successfully sent");
                        redirect_to("send_bulk_mail");
                    } else {
                        $message = $send_mail->message;
                    }
                } else {
                    $message = "Message Sending Link Broken";
                }
            } else {
                $sender = "";
                $subject = "";
                $content = "";
            }
        } else {
            die($admins->message);
        }
    } else {
        $die("Admin Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Send Bulk Mail</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Components</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Send Bulk Mail</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Send Mail</h4>
                        </div>
                        <div class="card-body card-block">
                            <form action="send_bulk_mail" method="POST">
                                <div class="form-group">
                                    <select name="sender" id="mail_sender" class="form-control">
                                        <option value="">--Sender--</option>
                                        <option value="info@yenreach.com"<?php
                                            if($sender == "info@yenreach.com"){
                                                echo " selected";
                                            }
                                        ?>>info@yenreach.com</option>
                                        <option value="<?php echo $admin->official_email ?>"<?php
                                            if($sender == $admin->official_email){
                                                echo " selected";
                                            }
                                        ?>><?php echo $admin->official_email ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="subject" id="mail_subject" class="form-control" placeholder="Mail Subject" value="<?php echo $subject; ?>">
                                </div>
                                <div class="form-group">
                                    <textarea name="content" id="mail_content" class="form-control" rows="12" placeholder="Mail Content"><?php echo $content; ?></textarea>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" name="submit" class="btn btn-primary btn-block" id="mail_submit">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>