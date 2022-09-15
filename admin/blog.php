<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("login");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }

    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $explode = !empty($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($explode)){
        $gurl = "fetch_one_blog_post_api.php?string=".$explode;
        $blog = perform_get_curl($gurl);
        if($blog){
            if($blog->status == "success"){         
                $blog = $blog->data;
            } else {
                redirect_to("blogs.php");
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
        if(!$session->is_logged_in()){
            redirect_to("login");   
        }
        $author_string = !empty($session->verify_string) ? (string)$session->verify_string : "";
        $comment = !empty($_POST['comment']) ? (string)$_POST['comment'] : "";
        $blog_string = !empty($explode) ? (string)$explode : "";

        $purl = "add_comment_api.php";
        $pdata = [
                'author_string' => $author_string,
                'comment' => $comment,
                'blog_string' => $blog_string
            ];
        $add_comment = perform_post_curl($purl, $pdata);
        if($add_comment){
            if($add_comment->status == "success"){
                $response = $add_comment->data;
                $session->message("comment has been added successfully");
                $message = "comment has been added successfully";
            } else {
                $message = $add_comment->message;     
            }
        } else {
            $message = "Something went wrong";
        }
    } else {
        $author_string = "";
        $comment = "";
        $blog_string = "";
    }

    function formatString($str) {
        $pattern = "/&lt;/i";
        $pattern2 = "/&gt;/i";
        $str = preg_replace($pattern, "<", $str);
        $str = preg_replace($pattern2, ">", $str);
        echo $str;
    };
  

    
    
    include_admin_template("header.php");
?>

<div class="content-body">
    <div class="container-fluid bg-white" style="color: #525252; font-size: 1rem;">
                <div class='mb-4'>
                    <h1 class='fw-bolder text-capitalize blog-title mb-3 text-black' style="font-size: 50px"><?php echo $blog->title; ?></h1>
                    <div class='d-flex align-items-center' style="gap: 1.5rem">
                        <div class="rounded-circle" style="width:40px; height:40px; background: gray">
                            
                        </div>
                        <div class='d-flex flex-column'>
                            <span class='fw-bold'>Author: <?php echo $blog->author; ?></span>
                            <span>posted_at: <?php echo(date("Y-m-d", $blog->created_at)); ?></span>
                        </div>
                    </div>
                </div>
                <div class='blog-img rounded-top mb-3' style="height: 400px; width:100%; background: #efecff">
                    <?php if (!empty($blog->file_path)) {  ?>
                        <img src="<?php echo"https://yenreach.com/images/".$blog->file_path.".jpg"; ?>" style="width:100%; height:100%;object-fit: cover;">
                    <?php }?>
                </div>

                    <div class=''>
                        <div class=''>
                            <div class='blog-text'>
                                <?php
                                    formatString($blog->post);
                                ?>
                            </div>
                        </div>
                    </div>
                <form action="" method="post" class="">
                    <h3 class="fw-bold">Leave a Reply</h3>
                    <textarea name="comment" id="coment" cols="30" rows="10" class="form-control">Your comment here</textarea>
                    <button name="submit" type="submit" class="d-block btn btn-success my-3">Post Comment</button>
                </form>
                <div class='border rounded p-4'>
                    <?php
                        if (!empty($comments)) {
                            foreach($comments as $comment){
                    ?>
                            <div class='border rounded'>
                                <div class='d-flex align-items-center gap-4 justify-content-between'>
                                    <div class='d-flex align-items-center gap-4'>
                                        <div class="rounded-circle" style="width:40px; height:40px; background: gray"></div>
                                        <span class='fw-bold'><?php echo $comment->author; ?></span>
                                    </div>
                                    <span>posted_at: <?php echo(date("Y-m-d", $comment->created_at)); ?></span>
                                </div>
                                <div><?php echo $comment->comment; ?></div>
                            </div>
                    <?php }} ?> 
                </div>
    </div>
</div>
<script>
    let text =<?php echo json_encode($blog_list); ?>;
    console.log(text)
</script>
<?php include_admin_template("footer.php"); ?>