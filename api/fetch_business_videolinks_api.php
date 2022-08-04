<?php
    require_once("../../includes/initialize.php");
    $return_array = array();
    
    $business = !empty($_GET['business']) ? (string)$_GET['business'] : "";
    if(!empty($business)){
        $videolinks = BusinessVideoLinks::find_by_business_id($business);
        if(!empty($videolinks)){
            $data_array = array();
            foreach($videolinks as $link){
                $data_array[] = array(
                        'id' => $link->id,
                        'verify_string' => $link->verify_string,
                        'user_id' => $link->user_id,
                        'business_id' => $link->business_id,
                        'video_link' => $link->video_link,
                        'platform' => $link->platform,
                        'created' => $link->created,
                        'last_updated' => $link->last_updated
                    );
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Video Link was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>