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
    
    if(isset($_POST['submit'])){
        $title = !empty($_POST['title']) ? (string)$_POST['title'] : "";
        $author = !empty($_POST['author']) ? (string)$_POST['author'] : "";;
        $content = !empty($_POST['content']) ? (string)$_POST['content'] : "";
        $snippet = !empty($_POST['snippet']) ? (string)$_POST['snippet'] : "";
        $admin_string = !empty($session->verify_string) ? (string)$session->verify_string : "";
        $file_path = "BLOG_PHOTO_".substr($admin_string,0,4).time();
        $file = !empty($_FILES['file']) ? $_FILES['file'] : array();

        if (!empty($title) && !empty($author) && !empty($content) && !empty($snippet) && !empty($admin_string) && !empty($file)) {
            if($file['size'] <= 204800){
                $photo = new Photo();
                $photo->filename = $file_path;
                $photo->load($file['tmp_name']);
                $photo->save_logo_compressed();
                $photo->scale(30);
                $photo->save_logo_thumbnail("jpg", 40, null);
            } else {
                $message = "Image must not be more than 200KB";
            }

            $purl = "add_blog_post_api.php";
            $pdata = [
                    'title' => $title,
                    'author' => $author,
                    'post' => $content,
                    'snippet' => $snippet,
                    'admin_string' => $admin_string,
                    'file_path' => $file_path,
                ];
            $add_blog = perform_post_curl($purl, $pdata);
            if($add_blog){
                if($add_blog->status == "success"){
                    $response = $add_blog->data;

                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "Blog",
                    'object_string' => $response->blog_string,
                    "activity" => "Create",
                    "details" => "Blogpost Added Successfully"
                ];
                perform_post_curl($lpurl, $lpdata);

                    $session->message("Blogost has been added successfully");
                    $message = "Blogost has been added successfully";
                } else {
                    $message = $add_blog->message;
                    
                }
            } else {
                $message = "Something went wrong";
            }
        }
        
    } else {
        $title =  "";
        $author = "";
        $content = "";
        $snippet = "";
        $file_path = "";
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
                            <p class="mb-0">Make a blog post today</p>
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
                        <label for="title" class="form-label text-primary mb-0 font-weight-bold">Title of Blog:</label>
                        <input class="form-control mb-1" type="text" name="title" id="title" placeholder="Blog Title" required>
                        <label for="author" class="form-label text-primary mb-0 font-weight-bold">Name of Author:</label>
                        <input class="form-control" type="text" name="author" id="author" placeholder="Author" required>
                        <label for="snippet" class="form-label text-primary mb-0 font-weight-bold">Snippet:</label>
                        <input class="form-control" type="text" name="snippet" id="snippet" placeholder="Snippet(120 characters)" maxlength="120" required>
                        <input class="form-control" type="file" name="file" id="file" accept="image/jpg, image/jpeg, image/png" required>
                    </div>
                    <label for="content" class="form-label text-primary mb-0 font-weight-bold">Post details:</label>
                    <textarea name="content" id="editor" required>
                        &lt;p&gt;This is some sample content.&lt;/p&gt;
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