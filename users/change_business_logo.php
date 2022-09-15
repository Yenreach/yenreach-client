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
    
    if(isset($_POST['submit'])){
        $file = !empty($_FILES['file']) ? $_FILES['file'] : array();
        if(!empty($file['name'])){
            if($file['size'] <= 204800){
                $gurl = "upload_business_logo_api.php?string=".$session->business_string;
                $uploaded = perform_get_curl($gurl);
                if($uploaded){
                    if($uploaded->status == "success"){
                        $upload = $uploaded->data;
                        
                        $photo = new Photo();
                        if(!empty($upload->old_filename)){
                            if(file_exists("../images/thumbnails/{$upload->old_filename}.jpg")){
                                $photo->destroy_thumbnail($upload->old_filename);
                            }
                            if(file_exists("../images/{$upload->old_filename}.jpg")){
                                $photo->destroy($upload->old_filename);
                            }
                        }
                        $photo->filename = $upload->filename;
                        $photo->load($file['tmp_name']);
                        $photo->save_logo_compressed();
                        $photo->scale(30);
                        $photo->save_logo_thumbnail("jpg", 40, null);
                            
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => "user",
                                'agent_string' => $session->verify_string,
                                'object_type' => "Businesses",
                                'object_string' => $session->business_string,
                                "activity" => "Update",
                                "details" => "Business Logo Upload"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $message = "Photo Update Successful";
                    } else {
                        $message = $uploaded->message;
                    }
                } else {
                    $message = "Upload Link Broken";
                }
            } else {
                $message = "File Size CANNOT be more than 200KB";
            }
        } else {
            $message = "No Picture was uploaded";
        }
    }
    
    include_portal_template("header.php");
?>
<!-- 
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
                width: 400px;
                height: 445px;
                margin: 20px;
                box-shadow: 0 0 20px rgba(0,0,0,.3);
            }
            
            .file__upload .header {
                width: 100%;
                height: 145px;
                background: #00C853;
                padding: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 5px 5px 0 0;
            }
            
            .file__upload .header p {
                color: #FFF;
            }
            
            .file__upload .header p i.fa {
                margin-right: 10px;
            }
            
            .file__upload .header p span {
                font-size: 2rem;
                font-weight: 100;
            }
            
            .file__upload .header p span span {
                font-weight: 600;
            }
            
            .file__upload .body {
                background: #FFF;
                width: 100%;
                height: calc(100% - 145px);
                border-radius: 0 0 5px 5px;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                text-align: center;
            }
            
            .file__upload .body input[type="file"] {
                opacity: 0.3;
            
            }
            
            .file__upload .body i.fa {
                color: #d3d3d3;
                margin-bottom: 20px;
            }
            
            .file__upload .body p strong {
                color: #00C853;
            }
            
            .file__upload .body p span {
                color: #00C853;
                text-decoration: underline;
            }
            
            .file__upload button.btn {
                background: #00C853;
                border: none;
                outline: none;
                margin: 20px 0;
                padding: .7rem 2rem;
                font-size: 1.3rem;
                color: #FFF;
                border-radius: 3px;
                opacity: .8;
                cursor: pointer;
                transition: .3s;
            }
            
            .file__upload button.btn:hover {
                opacity: 1;
            }
            
            #link_checkbox {
                display: none;
            }
            
            #link {
                border: 1px solid;
                color: #00C853;
                background: none;
                width: calc(100% - 20px);
                border-radius: 0;
                outline: none;
                padding: 10px;
                font-size: 1rem;
                margin: 10px 0;
                display: none;
            }
            
            #link_checkbox:checked ~ #link {
                display: block;
            }
            
            label[for="link_checkbox"] {
                padding: .5rem 2rem;
                background: #00C853;
                color: #FFF;
                outline: none;
                cursor: pointer;
            }
            
            .download .download_link {
                text-decoration: none;
                color: #FFF;
                background: #00C853;
                padding: .5rem 2rem;
                border-radius: 3px;
                opacity: .8;
                transition: .3s;
            }
            
            .download .download_link:hover {
                opacity: 1;
            }
            #upload{
                background-color:black;
            }
        </style> -->
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
        <!-- <main class="main" id="main">
          <div class="file__upload">
            <div class="header">
              <p><i class="fa fa-cloud-upload fa-2x"></i><span><span>up</span>load</span></p>			
            </div>
            <form action="add_business_comp" method="POST" enctype="multipart/form-data" class="body">
              
              <input type="file" name="file" id="upload" accept="image/jpg, image/jpeg, image/png" required>
              <label for="upload">
                <i class="fa fa-file-text-o fa-3x"></i>
                <p>
                <br>

                  <span>browse</span> to begin the upload. Please only (JPG, JPEG, PNG format allowed) note that 
                  Photo must not be more that 200KB
                </p>
              </label>
              <button type="submit" name="submit" class="btn">Upload</button>
            </form>
          </div>
        </main> -->
        <main class="main" id="main">
          <div class="file__upload">
                <div class="header">
                    <span>Photo Upload</span>
                    <!-- <a href="skip_logo_upload" class="btn btn-outline-warning">Skip</a>			 -->
                </div>
                <div class="bg-white container">
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
                            <a href="business_profile" class="btn btn-danger mb-1 btn-lg"><< Back</a>
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