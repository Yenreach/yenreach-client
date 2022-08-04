<?php
    require_once("../includes_public/initialize.php");
    $return_array = array();
    
    $business_string = !empty($_POST['business_string']) ? (string)$_POST['business_string'] : "";
    $review = !empty($_POST['review']) ? (string)$_POST['review'] : "";
    $star = !empty($_POST['star']) ? (int)$_POST['star'] : "";
    $user_string = $session->verify_string;
    
    $purl = "add_business_review_api.php";
    $pdata = [
            'user_string' => $user_string,
            'business_string' => $business_string,
            'review' => $review,
            'star' => $star
        ];
    $add_review = perform_post_curl($purl, $pdata);
    if($add_review){
        $return_array = $add_review;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'Review Link Broken';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>