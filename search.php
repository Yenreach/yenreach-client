<?php
    require_once("../includes_public/initialize.php");
    
    $category = !empty($_POST['category']) ? (string)$_POST['category'] : "";
    $location = !empty($_POST['location']) ? (string)$_POST['location'] : "";
    
    if(empty($location) && empty($category)){
        redirect_to("explorer");
    } else {
        if(empty($location)){
            $new_location = "any_location";
        } else {
            $new_location = $location;
        }
        if(empty($category)){
            $new_category = "any_business";
        } else {
            $new_category = $category;
        }
        
        redirect_to("business_search?search=".urlencode($new_category)."&location=".urlencode($location));
    }
?>