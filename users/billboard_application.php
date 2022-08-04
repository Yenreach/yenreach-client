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
    $gurl = "fetch_advert_payment_type_by_string_api.php?string=".$string;
    $adverts = perform_get_curl($gurl);
    if($adverts){
        if($adverts->status == "success"){
            $advert = $adverts->data;
            
            if(isset($_POST['submit'])){
                $title = !empty($_POST['title']) ? (string)$_POST['title'] : "";
                $text = !empty($_POST['text']) ? (string)$_POST['text'] : "";
                $action_type = !empty($_POST['action_type']) ? (string)$_POST['action_type'] : "";
                $action_link = !empty($_POST['action_link']) ? (string)$_POST['action_link'] : "";
                $proposed_start = !empty($_POST['proposed_start']) ? (string)$_POST['proposed_start'] : "";
                $file = !empty($_FILES['file_upload']) ? $_FILES['file_upload'] : [];
                if(!empty($file)){
                    if(($file['type'] == "image/jpg") || ($file['type'] == "image/jpeg") || ($file['type'] == "image/png")){
                        if(($file['size'] <= 307200) && ($file['size'] > 0)){
                            $purl = "initiate_billboard_application_api.php";
                            $pdata = [
                                    'user_string' => $session->verify_string,
                                    'advert_type' => $advert->verify_string,
                                    'title' => $title,
                                    'text' => $text,
                                    'action_type' => $action_type,
                                    'action_link' => $action_link,
                                    'proposed_start' => $proposed_start
                                ];
                            
                            $applyed = perform_post_curl($purl, $pdata);
                            if($applyed){
                                if($applyed->status == "success"){
                                    $apply = $applyed->data;
                                    
                                    $photo = new Photo();
                                    $photo->filename = $apply->filename;
                                    $photo->attach_file($file);
                                    if($photo->save()){
                                        $subject = "Yenreach Billboard Application - {$apply->code}";
                                        $content = 'Hello <i>'.$apply->name.'</i>;';
                                        $content .= '<p>';
                                        $content .=     'Your Application for an Advert Space on Yenreach Billboard has been recieved';
                                        $content .=     '<br />';
                                        $content .=     'We will get back to you in less than two working days about the progress of this Application';
                                        $content .=     '<br />';
                                        $content .=     'You can also check your <a href="https://yenreach.com/users/yenreach_billboard#my_applications">Billboard Applications</a> menu on your Users Dashboard to see the progress of your Application';
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
                                        redirect_to("yenreach_billboard#my_applications");
                                    } else {
                                        $message = join($photo->errors);
                                    }
                                } else {
                                    $message = $applyed->message;
                                }
                            } else {
                                $message = "Billboard Application Link Broken";
                            }
                        } else {
                            $message = "File CANNOT be more than 300KB";
                        }
                    } else {
                        $message = "Wrong File Type was uploaded";
                    }
                } else {
                    $message = "No Photo was uploaded";
                }
            } else {
                $title = "";
                $text = "";
                $action_type = "";
                $action_link = "";
                $proposed_start = "";
            }
        } else {
            die($adverts->message);
        }
    } else {
        die("Adverts Link Broken");
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
                            <li><a href="yenreach_billboard">Yenreach Billboard</a></li>
                            <li>Apply</li>
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
                        <h4 class="card-title">Apply for Yenreach Billboard</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card block">
                        <form role="form" action="billboard_application?<?php echo $string; ?>" id="billboard_aplication" enctype="multipart/form-data" method="POST" class="row g-3 needs-validation">
                            <div class="col-12">
                                <label for="file_upload" class="form-label">Display Photo</label>
                                <div class="input-group has-validation">
                                    <input type="file" name="file_upload" id="file_upload" accept="image/jpg, image/jpeg, image/png" class="form-control" required>
                                    <div class="invalid-feedback">Please Upload a File</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="advert_title" class="form-label">Headline (Not more than 50 Characters)</label>
                                <div class="input-group has-validation">
                                    <input type="text" name="title" id="advert_title" class="form-control" placeholder="Advert's Heading" value="<?php echo $title; ?>" />
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="advert_text" class="form-label">Short Description (Not more than 160 Characters)</label>
                                <div class="input-group has-validation">
                                    <textarea name="text" id="text" class="form-control" rows="5" placeholder="Advert Text" required><?php echo $text; ?></textarea>
                                    <div class="invalid-feedback">Please add an Advert Text</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="action_type" class="form-label">Call To Action</label>
                                <div class="input-group has-validation">
                                    <select name="action_type" class="form-control" id="action_type">
                                        <option value="">--Call To Action Type--</option>
                                        <?php call_to_action_select($action_type); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="action_link" class="form-label">Enter Link</label>
                                <div class="input-group has-validation">
                                    <input name="action_link" id="action_link" class="form-control" placeholder="" value="<?php echo $action_link; ?>" >
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="proposed_start" class="form-label">Start Date</label>
                                <div class="input-group has-validation">
                                    <input type="date" id="proposed_start" name="proposed_start" class="form-control" value="<?php echo $proposed_start; ?>" required />
                                    <div class="invalid-feedback">Please provide a Starting Date</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" id="advert_submit" name="submit" value="Submit" class="btn btn-block btn-success">Submit Application</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </main>

<?php include_portal_template("footer.php"); ?>