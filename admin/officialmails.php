<?php
    require_once('../../includes_public/initialize.php');
    if(!$session->is_logged_in()){
        redirect_to('logout.php');   
    }
    if($session->user_type != 'admin'){
        redirect_to('logout.php');
    }
    
    $user_string = !empty($_GET['user_string']) ? (string)$_GET['user_string'] : "";
    if(isset($_POST['submit'])){
        $email = !empty($_POST['email']) ? (string)$_POST['email'] : "";
        $password = !empty($_POST['password']) ? (string)$_POST['password'] : "";
        $incoming_server = !empty($_POST['incoming_server']) ? (string)$_POST['incoming_server'] : "";
        $outgoing_server = !empty($_POST['outgoing_server']) ? (string)$_POST['outgoing_server'] : "";
        $smtp_port = !empty($_POST['smtp_port']) ? (string)$_POST['smtp_port'] : "";
        $imap_port = !empty($_POST['imap_port']) ? (string)$_POST['imap_port'] : "";
        $pop3_port = !empty($_POST['pop3_port']) ? (string)$_POST['pop3_port'] : "";
        
        if(!empty($email) && !empty($password) && !empty($outgoing_server) && !empty($smtp_port)){
            $purl = "add_mailpassword_api.php";
            $pdata = [
                    'user_string' => $user_string,
                    'email' => $email,
                    'password' => $password,
                    'incoming_server' => $incoming_server,
                    'outgoing_server' => $outgoing_server,
                    'smtp_port' => $smtp_port,
                    'imap_port' => $imap_port,
                    'pop3_port' => $pop3_port
                ];
            
            $add_mail = perform_post_curl($purl, $pdata);
            if($add_mail){
                if($add_mail->status == 'success'){
                    $lpurl = "add_activity_log_api.php";
                    $lpdata = [
                            'agent_type' => $session->user_type,
                            'agent_string' => $session->verify_string,
                            'object_type' => "MailPasswords",
                            'object_string' => $add_mail->data->verify_string,
                            "activity" => "Creation",
                            "details" => "{$email} being added to the list of Official Mails"
                        ];
                        
                    perform_post_curl($lpurl, $lpdata);
                    
                    $session->message("Official Mail Added successfully!");
                    redirect_to('officialmails.php');
                } else {
                    $message = $add_mail->message;
                    //die($message);
                }
            } else {
                $message = "Add Link Broken!";
                //die($message);
            }
        } else {
            $message = "Please make sure all input fields are filled";
            //die($message);
        }
    } else {
        $email = "";
        $password = "";
        $incoming_server = "";
        $outgoing_server = "";
        $smtp_port = "";
        $imap_port = "";
        $pop3_port = "";
    }
    
    $gurl = "fetch_mailpasswords_api.php";
    $mails = perform_get_curl($gurl);
    if($mails){
        
    } else {
        die('Fetch Mails Link');
    }
    
    include_admin_template('header.php');
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Official Mails</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Admins</h4>
                        </div>
                        <div class="card-body card-block">
                            <?php
                                if($mails->status == "success"){
                            ?>
                                    <div class="table-responsive text-dark">
                                        <table class="table table-responsive-sm table-striped table-bordered text-dark">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Incoming Server</th>
                                                    <th scope="col">Outgoing Server</th>
                                                    <th scope="col">SMTP Port</th>
                                                    <th scope="col">IMAP Port</th>
                                                    <th scope="col">POP3 Port</th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($mails->data as $mail){
                                                ?>
                                                        <tr>
                                                            <td><?php echo $mail->email; ?></td>
                                                            <td><?php echo $mail->incoming_server; ?></td>
                                                            <td><?php echo $mail->outgoing_server; ?></td>
                                                            <td><?php echo $mail->smtp_port; ?></td>
                                                            <td><?php echo $mail->imap_port ?></td>
                                                            <td><?php echo $mail->pop3_port; ?></td>
                                                            <td>
                                                                <a href="edit_officialmail?<?php echo $mail->verify_string; ?>" class="btn btn-primary">Edit</a>
                                                            </td>
                                                            <td>
                                                                <a href="delete_officialmail?<?php echo $mail->verify_string; ?>" class="btn btn-danger" onclick="return confirm('Do you reallly want to delete this Official Mail?')">Delete</a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                            <?php
                                } else {
                                    echo '<p>'.$mails->message.'</p>';
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
                            <h4 class="card-title">Add Official Email Address</h4>
                        </div>
                        <div class="card-body card-block">
                            <form action="officialmails.php" id="add_officialmail" method="POST">
                                <div class="form-group">
                                    <input type="email" name="email" id="mail_email" class="form-control" placeholder="Email" value="<?php echo $email; ?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="password" id="mail_password" class="form-control" placeholder="Password" value="<?php echo $password; ?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="incoming_server" id="mail_incomingserver" class="form-control" placeholder="Incoming Server" value="<?php echo $incoming_server ?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="outgoing_server" id="mail_outgoingserver" class="form-control" placeholder="Outgoing Server" value="<?php echo $outgoing_server; ?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="smtp_port" id="mail_smtpport" class="form-control" placeholder="SMTP Port" value="<?php echo $smtp_port; ?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="imap_port" id="mail_imapport" class="form-control" placeholder="IMAP Port" value="<?php echo $imap_port ?>">
                                </div>
                                <div class="form-group">
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

<?php include_admin_template('footer.php'); ?>