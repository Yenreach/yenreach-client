<?php
    require_once("../../includes/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $link = new BusinessVideoLinks();
        $link->platform = !empty($post->platform) ? (string)$post->platform : "";
        $video_link = !empty($post->video_link) ? (string)$post->video_link : "";
        $extract = substr($video_link, 17);
        $link->video_link = "https://www.youtube.com/embed/".$extract;
        $link->user_id = !empty($post->user_id) ? (string)$post->user_id : "";
        $link->business_id = !empty($post->business_id) ? (string)$post->business_id : "";
        $formerlinks = BusinessVideoLinks::find_by_business_id($link->business_id);
        if(!empty($formerlinks)){
            $count = count($formerlinks);
        } else {
            $count = 0;
        }
        $sub = Subscriptions::find_business_last($link->business_id);
        if(!empty($sub)){
            if($sub->subscription_type < 3){
                if($sub->subscription_status == 1){
                    if((($sub->sub->subscription_type == 3) && ($count < 1)) || (($sub->subscription_type == 4) && ($count < 2))){
                        if($link->insert()){
                            $return_array['status'] = 'success';
                            $return_array['data'] = array(
                                    'id' => $link->id,
                                    'verify_string' => $link->verify_string,
                                    'user_id' => $link->user_id,
                                    'business_id' => $link->business_id,
                                    'video_link' => $link->video_link,
                                    'platform' => $link->platform,
                                    'created' => $link->created,
                                    'last_updated' => $link->last_updated
                                );
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = join(' ', $link->errors);
                        }
                    } else {
                        $return_array['status'] = "failed";
                        $return_array['message'] = "You have exceeded your Video Upload Limit";
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'Subscription has expired';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'This feature is only available to Gold and Premium Subscribers';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Subscription for this business';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>