<?php
    require_once("../../includes_public/initialize.php");
    $return_array = array();
   
    $state_id = !empty($_GET['state_id']) ? (int)$_GET['state_id'] : "";
    
    $gurl = "fetch_lgas_by_state_api.php?state_id=".$state_id;
    $lgas = perform_get_curl($gurl);
    if($lgas){
        $return_array = $lgas;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'LGAs Link Broken';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>