<?php
    require_once("../../includes/initialize.php");
    $return_array = array();
    
    $business = !empty($_GET['business']) ? (string)$_GET['business'] : "";
    if(!empty($business)){
        $sub = Subscriptions::find_business_last($business);
        if(!empty($sub)){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $sub->id,
                    'verify_string' => $sub->verify_string,
                    'subscription_status' => $sub->subscription_status,
                    'subscription_date' => $sub->subscription_date,
                    'expiry_date' => $sub->expiry_date,
                    'subscription_type' => $sub->subscription_type,
                    'business' => $sub->business,
                    'user' => $sub->user,
                    'lastmodified' => $sub->lastmodified,
                    'datecreated' => $sub->datecreated,
                    'modifiedby' => $sub->modifiedby,
                    'created' => $sub->created,
                    'last_updated' => $sub->last_updated
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Subscription was found for this Business';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>