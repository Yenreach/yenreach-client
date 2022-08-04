<?php
    require_once("../../includes_public/initialize.php");
    
    $return_array = call_to_action_types();
    
    echo json_encode($return_array);
?>