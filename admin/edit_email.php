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

    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $id = !empty($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($id)){
        $gurl = "fetch_one_email_sequence_api.php?id=".$id;
        $email = perform_get_curl($gurl);
        if($email){
            if($email->status == "success"){         
                $email = $email->data;
            } else {
                redirect_to("emails.php");
            }
        } else {
                die("Email Link Broken");
            }
    } else {
        die("Wrong Path");
    }

    

    if(isset($_POST['submit'])){
        $title = !empty($_POST['title']) ? (string)$_POST['title'] : "";
        $content = !empty($_POST['content']) ? (string)$_POST['content'] : "";
        $admin_string = !empty($session->verify_string) ? (string)$session->verify_string : "";
        $id = !empty($email->id) ? (string)$email->id : "";

        $purl = "update_email_sequence_api.php";
        $pdata = [
                'title' => $title,
                'content' => $content,
                'admin_string' => $admin_string,
                'id' => $id
            ];
        $update_email = perform_post_curl($purl, $pdata);
        if($update_email){
            if($update_email->status == "success"){
                $response = $update_email->data;

                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "Email",
                    'object_string' => $response->id,
                    "activity" => "Edit",
                    "details" => "Email Edited Successfully"
                ];
                perform_post_curl($lpurl, $lpdata);

                $session->message("Email has been updated successfully");
                $message = "Email has been updated successfully";
                redirect_to("emails");
            } else {
                $message = $update_email->message;
            }
        } else {
            $message = "Something went wrong during update";
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
                            <!-- <p class="mb-0">Make a email post today</p> -->
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
                        <input class="form-control mb-1" type="text" name="title" id="title" placeholder="Email Title" value="<?php echo $email->title; ?>" required>
                    </div>
                    <label for="content" class="form-label text-primary mb-0 font-weight-bold">Email Content:</label>
                    <textarea name="content" id="editor" required>
                        <?php echo $email->content; ?>
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