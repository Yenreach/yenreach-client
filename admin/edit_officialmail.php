<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to('logout.php');   
    }
    if($session->user_type != 'admin'){
        redirect_to('logout.php');
    }
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $verify_string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_officialmail_by_string_api.php?string=".$verify_string;
    $mails = perform_get_curl($gurl);
    if($mails){
        if($mails->status == "success"){
            $mail = $mails->data;
            
            if(isset($_POST['submit'])){
                $email = !empty($_POST['email']) ? (string)$_POST['email'] : "";
                $password = !empty($_POST['password']) ? (string)$_POST['password'] : "";
                $incoming_server = !empty($_POST['incoming_server']) ? (string)$_POST['incoming_server'] : "";
                $outgoing_server = !empty($_POST['outgoing_server']) ? (string)$_POST['outgoing_server'] : "";
                $smtp_port = !empty($_POST['smtp_port']) ? (string)$_POST['smtp_port'] : "";
                $imap_port = !empty($_POST['imap_port']) ? (string)$_POST['imap_port'] : "";
                $pop3_port = !empty($_POST['pop3_port']) ? (string)$_POST['pop3_port'] : "";
                
                $purl = "update_officialmail_api.php";
                $pdata = [
                        'verify_string' => $mail->verify_string,
                        'email' => $email,
                        'password' => $password,
                        'incoming_server' => $incoming_server,
                        'outgoing_server' => $outgoing_server,
                        'smtp_port' => $smtp_port,
                        'imap_port' => $imap_port,
                        'pop3_port' => $pop3_port
                    ];
                $update_mail = perform_post_curl($purl, $pdata);
                if($update_mail){
                    if($update_mail->status == "success"){
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "MailPasswords",
                                'object_string' => $update_mail->data->verify_string,
                                "activity" => "Update",
                                "details" => "{$email} being updated"
                            ];
                            
                        perform_post_curl($lpurl, $lpdata);
                    
                        $session->message("Official Mail updated successfully!");
                        redirect_to("officialmails");
                    } else {
                        $message = $update_mail->message;
                    }
                } else {
                    $message = "Mail Update Link Broken";
                }
            } else {
                $email = $mail->email;
                $password = $mail->password;
                $incoming_server = $mail->incoming_server;
                $outgoing_server = $mail->outgoing_server;
                $smtp_port = $mail->smtp_port;
                $imap_port = $mail->imap_port;
                $pop3_port = $mail->pop3_port;
            }
        } else {
            die($mails->message);
        }
    } else {
        die("Official Mail Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Official Mail Addresses</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Components</a></li>
                        <li class="breadcrumb-item"><a href="officialmails.php">Official Mails</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $mail->email; ?></a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Official Email Address</h4>
                        </div>
                        <div class="card-body card-block">
                            <form action="edit_officialmail?<?php echo $verify_string; ?>" id="add_officialmail" method="POST">
                                <div class="form-group">
                                    <label for="mail_email" class="text-dark"><strong>Email</strong></label>
                                    <input type="email" name="email" id="mail_email" class="form-control" placeholder="Email" value="<?php echo $email; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="mail_password" class="text-dark"><strong>Password</strong></label>
                                    <input type="text" name="password" id="mail_password" class="form-control" placeholder="Password" value="<?php echo $password; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="mail_incomingserver" class="text-dark"><strong>Incoming Server</strong></label>
                                    <input type="text" name="incoming_server" id="mail_incomingserver" class="form-control" placeholder="Incoming Server" value="<?php echo $incoming_server ?>">
                                </div>
                                <div class="form-group">
                                    <label for="mail_outgoingserver" class="text-dark"><strong>Outgoing Server</strong></label>
                                    <input type="text" name="outgoing_server" id="mail_outgoingserver" class="form-control" placeholder="Outgoing Server" value="<?php echo $outgoing_server; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="mail_smtpport" class="text-dark"><strong>SMTP Port</strong></label>
                                    <input type="text" name="smtp_port" id="mail_smtpport" class="form-control" placeholder="SMTP Port" value="<?php echo $smtp_port; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="mail_imapport" class="text-dark"><strong>IMAP Port</strong></label>
                                    <input type="text" name="imap_port" id="mail_imapport" class="form-control" placeholder="IMAP Port" value="<?php echo $imap_port ?>">
                                </div>
                                <div class="form-group">
                                    <label for="mail_pop3port" class="text-dark"><strong>POP3 Port</strong></label>
                                    <input type="text" name="pop3_port" id="mail_pop3port" class="form-control" placeholder="POP3 Port" value="<?php echo $pop3_port; ?>">
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