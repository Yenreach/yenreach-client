<?php
    require_once("../../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    if(!$session->is_logged_in()){
        redirect_to("auth?page={$url}");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_billboard_application_by_string_api.php?string={$string}";
    $applications = perform_get_curl($gurl);
    if($applications){
        if($applications->status == "success"){
            $application = $applications->data;
            
            $tgurl = "fetch_all_advert_payment_types_api.php";
            $types = perform_get_curl($gurl);
            if($types){
                
            } else {
                die("Advert Payment Types Link Broken");
            }
            
            if(isset($_POST['submit'])){
                $title = !empty($_POST['title']) ? (string)$_POST['title'] : "";
                $text = !empty($_POST['text']) ? (string)$_POST['text'] : "";
                $action_type = !empty($_POST['action_type']) ? (string)$_POST['action_type'] : "";
                $action_link = !empty($_POST['action_link']) ? (string)$_POST['action_link'] : "";
                $proposed_start = !empty($_POST['proposed_start']) ? (string)$_POST['proposed_start'] : "";
                $advert_type = !empty($_POST['advert_type']) ? (string)$_POST['advert_type'] : "";
                $file = !empty($_FILES['file_upload']) ? $_FILES['file_upload'] : [];
                if(empty($file)){
                    $upload = "no";
                } else {
                    if(($file['type'] == "image/jpg") || ($file['type'] == "image/jpeg") || ($file['type'] == "image/png")){
                        if(($file['size'] <= 307200) && ($file['size'] > 0)){
                            $upload = "yes";
                        } else {
                            $upload = "no";
                        }
                    } else {
                        $upload = "no";
                    }
                }
                
                $purl = "update_billboard_application_api.php";
                $pdata = [
                        'verify_string' => $application->verify_string,
                        'advert_type' => $advert_type,
                        'title' => $title,
                        'text' => $text,
                        'action_type' => $action_type,
                        'action_link' => $action_link,
                        'proposed_start' => $proposed_start,
                        'advert_type' => $advert_type,
                        'upload' => $upload
                    ];
                
                $applyed = perform_post_curl($purl, $pdata);
                if($applyed){
                    if($applyed->status == "success"){
                        $apply = $applyed->data;
                        if($upload == "yes"){
                            $photo = new Photo();
                            if(file_exists("../images/{$apply->old_filename}.jpg")){
                                $photo->destroy($photo->old_filename);
                            }
                            $photo->filename = $apply->filename;
                            $photo->attach_file($file);
                            if($photo->save()){
                                $success = "yes";
                            } else {
                                $success = "no";
                                $messaged = join('<br />', $photo->errors);
                            }
                        } else {
                            $success = "yes";
                        }
                        
                        if($success == "yes"){
                            $subject = "Yenreach Billboard Application - {$apply->code}";
                            $content = 'Hello <i>'.$apply->name.'</i>;';
                            $content .= '<p>';
                            $content .=     'Your update on the Yenreach Billboard Application has been recieved';
                            $content .=     '<br />';
                            $content .=     'We will get back to you in less than two working days';
                            $content .=     '<br />';
                            $content .=     'You can also check your <a href="https://yenreach.com/users/billboard_applications">Billboard Applications</a> menu on your Users Dashboard to see the progress of your Application';
                            $content .= '</p>';
                            $content .= '<p>';
                            $content .=     'For further enquiries or complaints, you can send a mail to <a href="mailto:support@yenreach.com">support@yenreach.com</a>';
                            $content .= '</p>';
                            $content .= '<p>';
                            $content .=     '<i>Yenreach Support Team</i>';
                            $content .= '</p>';
                            
                            $spurl = "send_mail_api.php";
                            $spdata = [
                                    'ticket_id' => '',
                                    'movement' => 'outgoing',
                                    'from_name' => 'Yenreach',
                                    'from_mail' => 'info@yenreach.com',
                                    'recipient_name' => $apply->name,
                                    'recipient_mail' => $apply->email,
                                    'subject' => $subject,
                                    'content' => $content,
                                    'reply_name' => 'Yenreach',
                                    'reply_mail' => 'info@yenreach.com'
                                ];
                            
                            perform_post_curl($spurl, $spdata);
                            $session->message("Billboard Application was successful! We will reach back to your mail concerning the outcome of your application");
                            redirect_to("billboard_applied?{$apply->verify_string}");
                        } else {
                            $message = $messaged;
                        }
                    } else {
                        $message = $applyed->message;
                    }
                } else {
                    $message = "Update Link Broken";
                }
            } else {
                $title = $application->title;
                $text = $application->text;
                $action_type = $application->call_to_action_type;
                $action_link = $application->call_to_action_link;
                $proposed_start = $application->proposed_start_date;
                $advert_type = $application->advert_type->verify_string;
            }
        } else {
            die($applications->message);
        }
    } else {
        die("Billboard Applications Link Broken");
    }
    
    $gurl = "fetch_all_advert_payment_types_api.php";
    $types = perform_get_curl($gurl);
    if($types){
        
    } else {
        die("Advert Payment Types Link Broken");
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
                            <li><a href="billboard_applications">Yenreach Billboard Applications</a></li>
                            <li><a href="billboard_applied?<?php echo $string; ?>">Application - <?php echo $application->code; ?></a></li>
                            <li>Edit</li>
                            
                        </ol>
                        <h2>Yenreach Billboard Application</h2>
                        <p>Application to place an Advert on the Yenreach Online Billboard</p>
                        
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit your Yenreach Billboard Application</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card block">
                        <form role="form" action="billboard_application_edit?<?php echo $string; ?>" id="billboard_aplication" method="POST" class="row g-3 needs-validation">
                            <div class="col-12">
                                <label for="file_upload" class="form-label">Display Photo Photo</label>
                                <div class="input-group has-validation">
                                    <input type="file" name="file_upload" id="file_upload" accept="image/jpg, image/jpeg, image/png" class="form-control">
                                    <div class="invalid-feedback">Please Upload a File</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="advert_title" class="form-label">Advert Headline</label>
                                <div class="input-group has-validation">
                                    <input type="text" name="title" id="advert_title" class="form-control" placeholder="Advert's Heading" value="<?php echo $title; ?>" required />
                                    <div class="invalid-feedback">Please add a Title to your Ad</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="advert_text" class="form-label">Short Description</label>
                                <div class="input-group has-validation">
                                    <textarea name="text" id="text" class="form-control" rows="5" placeholder="Advert Text" required><?php echo $text; ?></textarea>
                                    <div class="invalid-feedback">Please add an Advert Text</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="action_type" class="form-label">Call To Action Type</label>
                                <p>When People click on the Link on your Advert, which Platform will it lead them to, or which Action will you want them to take?</p>
                                <div class="input-group has-validation">
                                    <select name="action_type" class="form-control" id="action_type">
                                        <option value="">--Call To Action Type--</option>
                                        <?php call_to_action_select($action_type); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="action_link" class="form-label">Call To Action Link</label>
                                <div class="input-group has-validation">
                                    <input name="action_link" id="action_link" class="form-control" placeholder="" value="<?php echo $action_link ?>" >
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="proposed_start" class="form-label">Proposed Start Date</label>
                                <div class="input-group has-validation">
                                    <input type="date" id="proposed_start" name="proposed_start" class="form-control" value="<?php echo $proposed_start; ?>" required />
                                    <div class="invalid-feedback">Please provide a Starting Date</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="advert_type" class="form-label">Advert Type/Duration</label>
                                <div class="input-group has-validation">
                                    <select name="advert_type" id="advert_type" class="form-control">
                                        <option value="">--Advert Type/Duration--</option>
                                        <?php
                                            foreach($types->data as $type){
                                        ?>
                                                <option value="<?php echo $type->verify_string; ?>"<?php
                                                    if($advert_type == $type->verify_string){
                                                        echo " selected";
                                                    }
                                                ?>><?php
                                                    echo $type->title." - ".$type->duration." ".classify_duration($type->duration_type);
                                                    if($type->duration > 1){
                                                        echo "s";
                                                    }
                                                ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" id="advert_submit" name="submit" value="Submit" class="btn btn-block btn-primary">Update Application</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </main>

<?php include_portal_template("footer.php"); ?>