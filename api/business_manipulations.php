<?php
    require_once("../../includes/initialize.php");
    
    $businesses = Businesses::find_all();
    $total = 0;
    $moved = 0;
    $failed = 0;
    $data_array = array();
    foreach($businesses as $business){
        $total += 1;
        $user = Users::find_by_id($business->user);
        $business->user_string = $user->verify_string;
        if(!empty($business->datecreated)){
            $business->created = strtotime($business->datecreated);
        }
        $old_state = strtolower($business->state);
        $business->state = ucfirst($old_state);
        
        if($business->insert()){
            $purl = "add_temp_business_api.php";
            $pdata = [
                    "verify_string" => $business->verify_string,
                    "name" => $business->name,
                    "description" => $business->description,
                    "user_string" => $business->user_string,
                    "category" => $business->category,
                    "address" => $business->address,
                    "state" => $business->state,
                    "phonenumber" => $business->phonenumber,
                    "whatsapp" => $business->whatsapp,
                    "email" => $business->email,
                    "website" => $business->website,
                    "facebook_link" => $business->facebook_link,
                    "instagram_link" => $business->instagram_link,
                    "youtube_link" => $business->youtube_link,
                    "linkedin_link" => $business->linkedin_link,
                    "working_hours" => $business->working_hours,
                    "cv" => $business->cv,
                    "modifiedby" => $business->modifiedby,
                    "experience" => $business->experience,
                    "created" => $business->created
                ];
                
            $addbus = perform_post_curl($purl, $pdata);
            if($addbus){
                if($addbus->status == "success"){
                    $moved += 1;
                    $data_array[] = array(
                            'id' => $business->id,
                            'status' => 'moved',
                            'name' => $business->name,
                            'message' => 'Moved successfully'
                        );
                } else {
                    $failed += 1;
                    $data_array[] = array(
                            'id' => $business->id,
                            'status' => 'failed',
                            'name' => $business->name,
                            'phonenumber' => $business->phonenumber,
                            'email' => $business->email,
                            'whatsapp' => $business->whatsapp,
                            'message' => $addbus->message
                        );
                }
            } else {
                $failed += 1;
                $data_array[] = array(
                        'id' => $business->id,
                        'status' => 'failed',
                        'name' => $business->name,
                        'phonenumber' => $business->phonenumber,
                        'email' => $business->email,
                        'whatsapp' => $business->whatsapp,
                        'message' => 'Add Link Broken'
                    );
            }
        } else {
            $failed += 1;
            $data_array[] = array(
                    'id' => $business->id,
                    'status' => 'failed',
                    'name' => $business->name,
                    'phonenumber' => $business->phonenumber,
                    'email' => $business->email,
                    'whatsapp' => $business->whatsapp,
                    'message' => $business->errors
                );
        }
    }
    
    echo "Total: ".$total.". Failed: ".$failed.". Moved: ".$moved;
    echo "<hr />";
    print_r($data_array);
?>