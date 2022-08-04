<?php
    require_once("../../includes/initialize.php");
    
    $return_array = array();
    
    $total = 0;
    $moved = 0;
    $failed = 0;
    $data_array = array();
    $images = BusinessImages::find_all();
    if(!empty($images)){
        foreach($images as $image){
            $total += 1;
            $user = Users::find_by_id($image->user);
            $image->user_string = $user->verify_string;
            $business = Businesses::find_by_id($image->business);
            $image->business_string = $business->verify_string;
            if(!empty($image->datecreated)){
                $image->created = strtotime($image->datecreated);
            }
            if($image->insert()){
                $purl = "add_bus_image_api.php";
                $pdata = [
                        'verify_string' => $image->verify_string,
                        'filepath' => $image->image_path,
                        'user_string' => $image->user_string,
                        'business_string' => $image->business_string,
                        'created' => $image->created
                    ];
                $add_image = perform_post_curl($purl, $pdata);
                if($add_image){
                    if($add_image->status == "success"){
                        $moved += 1;
                        $data_array[] = array(
                                'status' => 'moved',
                                'id' => $image->id,
                                'message' => 'Image moved successfully'
                            );
                    } else {
                        $failed += 1;
                        $data_array[] = array(
                                'status' => 'failed',
                                'id' => $image->id,
                                'errors' => $add_image->message
                            ); 
                    }
                } else {
                    $failed += 1;
                    $data_array[] = array(
                            'status' => 'failed',
                            'id' => $image->id,
                            'errors' => 'Image Moving Link Broken'
                        );
                }
            } else {
                $failed += 1;
                $data_array[] = array(
                        'status' => 'failed',
                        'id' => $image->id,
                        'errors' => $image->errors
                    );
            }
        }
    }
    
    $return_array = array('total'=>$total, 'failed'=>$failed, 'moved'=>$moved, 'data_array'=>$data_array);
    print_r($return_array);
?>