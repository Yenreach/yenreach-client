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
    
    $blog_string = !empty($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($blog_string)){
        $gurl = "fetch_one_blog_post_api.php?string=".$blog_string;
        $blog = perform_get_curl($gurl);
        if($blog){
            if($blog->status == "success"){         
                $blog = $blog->data;
            } else {
                redirect_to("all_blogs.php");
            }
        } else {
                die("Blog Link Broken");
            }
    } else {
        die("Wrong Path");
    }
    
    $gurl = "fetch_comments_api.php?string=".$blog->blog_string;
    $comments = perform_get_curl($gurl);
    if($comments){
        if($comments->status == "success"){
            $comments = $comments->data;
        } else {
            $comments = [];
        }
    } else {
        die("Comment Link Broken");
    } 

    

    if(isset($_POST['submit'])){
        $title = !empty($_POST['title']) ? (string)$_POST['title'] : "";
        $author = !empty($_POST['author']) ? (string)$_POST['author'] : "";;
        $content = !empty($_POST['content']) ? (string)$_POST['content'] : "";
        $snippet = !empty($_POST['snippet']) ? (string)$_POST['snippet'] : "";
        $admin_string = !empty($session->verify_string) ? (string)$session->verify_string : "";
        $blog_string = !empty($blog->blog_string) ? (string)$blog->blog_string : "";
        $file_path = $blog->file_path;
        $file = !empty($_FILES['file']) ? $_FILES['file'] : array();

        if (!empty($title) && !empty($author) && !empty($content) && !empty($snippet) && !empty($admin_string) && !empty($file_path)) {
            if (!empty($file)) {
                if($file['size'] <= 204800){
                    $photo = new Photo();
                    $photo->filename = $file_path;
                    $photo->load($file['tmp_name']);
                    $photo->save_logo_compressed();
                    $photo->scale(30);
                    $photo->save_logo_thumbnail("jpg", 40, null);
                }  else {
                    $message = "Image must not be more than 200KB";
                }  
            }

            $purl = "update_blog_post_api.php";
            $pdata = [
                    'title' => $title,
                    'author' => $author,
                    'post' => $content,
                    'snippet' => $snippet,
                    'admin_string' => $admin_string,
                    'blog_string' => $blog_string,
                    'file_path' => $file_path
                ];
            $update_blog = perform_post_curl($purl, $pdata);
            if($update_blog){
                if($update_blog->status == "success"){
                    $response = $update_blog->data;

                    $lpurl = "add_activity_log_api.php";
                    $lpdata = [
                        'agent_type' => $session->user_type,
                        'agent_string' => $session->verify_string,
                        'object_type' => "Blog",
                        'object_string' => $response->blog_string,
                        "activity" => "Edit",
                        "details" => "Blogpost Edited Successfully"
                    ];
                    perform_post_curl($lpurl, $lpdata);

                    $session->message("Blogost has been updated successfully");
                    $message = "Blogost has been updated successfully";
                    redirect_to("all_blogs");
                } else {
                    $message = $update_blog->message;
                }
            } else {
                $message = "Something went wrong during update";
            }
        }
        
    } else {
        $title =  "";
        $author = "";
        $content = "";
        $snippet = "";
        $admin_string = '';
        $blog_string = ' ';
    }
    
    
    include_admin_template("header.php");
?>
<style>
:root {
    --circleSize: 165px;
    --radius: 100px;
    --shadow: 0 0 10px 0 rgba(255,255,255,.35);
    --fontColor: rgb(250,250,250);
}


.profile-pic {
  color: transparent;
  transition: all .3s ease;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  transition: all .3s ease;
}
  
  .img-input {
    display: none;
  }
  
  img {
    position: absolute;
    object-fit: cover;
    width: var(--circleSize);
    height: var(--circleSize);
    box-shadow: var(--shadow);
    border-radius: var(--radius);
    z-index: 0;
  }
  
  .-label {
    cursor: pointer;
    height: var(--circleSize);
    width: var(--circleSize);
  }
  

  .profile-pic:hover .-label {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0,0,0,.8);
    z-index: 10000;
    color: var(--fontColor);
    transition: var(--background-color) .2s ease-in-out;
    border-radius: var(--radius);
    margin-bottom: 0;
    }
  .text-img {
    display: inline-flex;
    padding: .2em;
    height: 2em;
  }

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
                        <input class="form-control mb-1" type="text" name="title" id="title" placeholder="Blog Title" value="<?php echo $blog->title;  ?>">
                        <label for="author" class="form-label text-primary mb-0 font-weight-bold">Name of Author:</label>
                        <input class="form-control" type="text" name="author" id="author" placeholder="Author" value="<?php echo $blog->author; ?>">
                        <label for="snippet" class="form-label text-primary mb-0 font-weight-bold">Snippet:</label>
                        <input class="form-control" type="text" name="snippet" id="snippet" placeholder="Snippet(120 characters)" maxlength="120" value="<?php echo $blog->snippet; ?>" required>
                        <div class="profile-pic">
                            <label class="-label" for="file">
                                <span class="glyphicon glyphicon-camera"></span>
                                <span class="text-img">Change Image</span>
                            </label>
                            <input class="img-input" id="file" name="file" type="file" accept="image/jpg, image/jpeg, image/png" onchange="loadFile(event)" />
                            <img src="<?php echo "https://yenreach.com/images/".$blog->file_path.".jpg"; ?>" id="output" width="200" />
                        </div>
                    </div>
                    <label for="content" class="form-label text-primary mb-0 font-weight-bold">Post details:</label>
                    <textarea name="content" id="editor">
                        <?php echo $blog->post ?>
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
        <script type="text/javascript">
            function loadFile (event) {
                var image = document.getElementById("output");
                image.src = URL.createObjectURL(event.target.files[0]);
                };
        </script>
  <?php include("tinymceditor.php") ?>
<?php include_admin_template("footer.php"); ?>