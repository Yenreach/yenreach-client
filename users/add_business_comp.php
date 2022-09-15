<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    if(!$session->is_business_logged()){
        redirect_to("dashboard");
    }
    
    $gurl = "fetch_business_by_string_api.php?string=".$session->business_string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
            
            if(isset($_POST['submit'])){
                $file = !empty($_FILES['file']) ? $_FILES['file'] : array();
                if(!empty($file['name'])){
                    $upload = "yes";
                } else {
                    $upload = "no";
                }
                
                $purl = "update_business_api_level3_api.php";
                $pdata = [
                        'business_string' => $session->business_string,
                        'upload' => $upload
                    ];
                $complete_registration = perform_post_curl($purl, $pdata);
                if($complete_registration){
                    if($complete_registration->status == "success"){
                        $complete = $complete_registration->data;
                        
                        if($upload == "yes"){
                            if($file['size'] <= 1204800){
                                $photo = new Photo();
                                $photo->filename = $complete->filename;
                                $photo->load($file['tmp_name']);
                                if($file['size'] <= 304800){
                                    $photo->save_logo_compressed();
                                }
                                elseif ($file['size'] <= 700800) {
                                    $photo->save_logo_compressed($extension="jpg", $compression=50, $permissions=null);
                                }
                                else {
                                    $photo->save_logo_compressed($extension="jpg", $compression=30, $permissions=null);
                                }
                                $photo->scale(30);
                                $photo->save_logo_thumbnail("jpg", 40, null);
                                $lpurl = "add_activity_log_api.php";
                                $lpdata = [
                                        'agent_type' => "user",
                                        'agent_string' => $session->verify_string,
                                        'object_type' => "Businesses",
                                        'object_string' => $session->business_string,
                                        "activity" => "Update",
                                        "details" => "Third Stage of Business Registration carried out by the User"
                                    ];
                                perform_post_curl($lpurl, $lpdata);
                                $session->message("Business has been registered succesfully");
                                redirect_to("business_profile");
                            } else {
                                $message = "Image must not be more than 1MB";
                            }
                        } else {
                            $session->message("Business has been registered succesfully");
                            redirect_to("business_profile");
                        }
                    } else {
                        $message = $complete_registration->message;
                    }
                } else {
                    $message = "Registration Link Broken";
                }
            }
        } else {
            die($businesses->message);
        }
    } else {
        die("Business Link Broken");
    }
    
    include_portal_template("header.php");
?>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Roboto', sans-serif;
            }
            
            body {
                width: 100%;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
            }
            
           .file__upload {
                max-width: 400px;
                /* height: 445px; */
                box-shadow: 0 0 20px rgba(0,0,0,.3);
            }  
            
            .header {
                width: 100%;
                background: white;
                padding: 40px 20px 40px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #e0e0e0;
                border-radius: 5px 5px 0 0;
            }
            
            .header span {
                color: #00C853;
                font-size: 1.5rem;
            }
            .container {
                background: #FFF;
                padding: 30px 5px;
                width: 100%;
                /* height: calc(100% - 145px); */
                border-radius: 0 0 5px 5px;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                text-align: center;
            }
            input {
                opacity: 0;
            }

            label {
            display:inline-block;
            padding: 15px 15px;
            text-align:center;
            background:#00C853;
            color:#fff;
            font-size:15px;
            font-weight:600;
            border-radius:10px;
            cursor:pointer;
            }

            .preview {
                margin-top: 20px;
                width: 100%;
            }
            .preview-file {
                width: 90%;
                height: 100%;
                margin-left: auto;
                margin-right: auto;
                display: none;

            }
            .btn-green {
                background: #00C853;
            }
            .bottom {
                width: 100%;
                margin-top: 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        
        </style>
        <main class="main" id="main">
          <div class="file__upload">
                <div class="header">
                    <span>Photo Upload</span>
                    <a href="skip_logo_upload" class="btn btn-outline-warning">Skip</a>			
                </div>
                <div class="bg-white container">
                    <ul class="row p-0 w-100">
                            <li class='col-4 list-unstyled d-flex align-items-center justify-content-center text-white' style='height:3rem;background-color:#00C853;font-size:12px'>Business 
                            details</li>
                            <li class=' col-4 list-unstyled d-flex align-items-center justify-content-center text-white' style='height:3rem;background-color:#00C853; font-size:12px'>Business category</li>
                            <li class=' col-4  list-unstyled d-flex align-items-center justify-content-center text-white' style='height:3rem;background-color:#00C853;font-size:12px'>Business file</li>
                        </ul>
                    <br>
                    <!-- <strong>Drag and drop</strong> files here<br> -->
                    Please only (JPG, JPEG, PNG format allowed) note that 
                    Photo must not be more than 1MB <br>
                    Click on the button below to select file to upload

                    <form action="add_business_comp" method="POST" enctype="multipart/form-data" class="body h-75 pb-2 mb-1">
                        <div class="preview">
                            <img class='preview-file' id="file-preview">
                        </div>
                        <!-- Sharable Link Code -->
                
                        <input type="file" name="file" id="upload" accept="image/jpg, image/jpeg, image/png" onchange="showPreview(event)" required>
                        <label for='upload' class='mb-4'>Upload Image</label>
                        
                        <?php 
                            if(!$message == ''){
                                echo '<br>';
                                echo "<script type='text/javascript'>alert('$message');</script>";
                            }
                            $message = '';
                        ?>
                        
                        <div class="bottom">
                            <a href="add_business_cont" class="btn btn-danger mb-1 btn-lg"><< Back</a>
                            <button type="submit" name="submit" class="btn btn-success btn-green btn-lg">Done</button>
                        </div>
                        
                    </form> 
                </div>
          </div>
        </main>
        <script>
            function showPreview(event){
                if(event.target.files.length > 0){
                   
                    var src = URL.createObjectURL(event.target.files[0]);
                    console.log(src)
                    var preview = document.getElementById("file-preview");
                    preview.src = src;
                    preview.style.height = "300px"
                    preview.style.display = "block";
                }
                }
        </script>

<?php include_portal_template("footer.php"); ?>