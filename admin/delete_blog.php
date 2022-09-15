<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $blog_string = !empty($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($blog_string)){
        $gurl = "delete_blog_post_api.php/?blog_string=".$blog_string."&admin_string=".$session->verify_string;
        $delete_blog = perform_get_curl($gurl);
        if($delete_blog){
            if($delete_blog->status == "success"){  
                $response = $delete_blog->data;

                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "Blog",
                    'object_string' => $response->blog_string,
                    "activity" => "Delete",
                    "details" => "Blogpost Deleted Successfully"
                ];
                perform_post_curl($lpurl, $lpdata);

                redirect_to("all_blogs.php");
            } else {
                redirect_to("all_blogs.php");
            }
        } else {
                die("Delete Blog Link Broken");
            }
    } else {
        die("Wrong Path");
    }
?>