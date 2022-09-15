<?php 
    require_once("../includes_public/initialize.php");
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    $name = !empty($exploded[1]) ? (string)$exploded[1] : "";
    function generateColor (){
        $colors = array('#ecb731','#537bc4','#ff4500','#162221','#666666','#355ebe','#0732a2','#8aba56','#40b3ff');
        $colors2  = array(array("bg"=>'#EBE6EF',"col"=>"#AB133E"), array("bg"=>'#FFF7E0',"col"=>"#935F10"), array("bg"=>'#D8E8F3',"col"=>"#222A54"), array("bg"=>'#E0E6EB',"col"=>"#2D3A46"), array("bg"=>'#FFF7E0',"col"=>"#935F10"), array("bg"=>'#E7F9F3',"col"=>"#216E55"), array("bg"=>'#E6DFEC',"col"=>"#37364F"), array("bg"=>'#FFEBEE',"col"=>"#BD0F2C"), array("bg"=>'#EEEDFD',"col"=>"#4409B9"), array("bg"=>'#EAEFE6',"col"=>"#69785E"), array("bg"=>'#E2F4E8',"col"=>"#363548"), array("bg"=>'#ECE1FE',"col"=>"#192251"), array("bg"=>'#E8DEF6',"col"=>"#420790"), array("bg"=>'#FDF1F7',"col"=>"#973562"));
        $c = rand(0,13);
        $col = $colors2[$c];
        return $col;
        }
        
    function cleaner($name) {
    	$clean = "";
    	foreach (str_split($name) as $char) {
                if (ctype_alpha($char)) {
                	$clean = $clean.$char;
				}
            }
		return $clean;
    }
    
    function setBusinessImage($name) {
        $color = generateColor();
        $name = trim($name);
        if (strpos($name, ' ') !== false) {
        	$exploded = explode(" ", $name);
            $clean1 = cleaner($exploded[0]); 
            $clean2 = cleaner($exploded[1]); 
            $res1 = substr($clean1, 0, 1);
            $res2 = substr($clean2, 0, 1);
            $res = strtoupper($res1.$res2);  // Two char
        } else {
        	$clean = cleaner($name); 
        	$res = strtoupper(substr($clean,0,2));  // Two char
		}
    
        if (!empty($res)){
        	echo "<div class='card-img custom-image text-wrap text-uppercase' style='background: {$color["bg"]}; color: {$color["col"]};'>{$res}</div>";
        }
        else {
        return null;
        
        } 
    }

    // function setBusinessImage($name) {
    //     $bgColor = generateColor();
    //     $res = substr($name,0,2);  // Two char
    //     if (!empty($res)){
    //     echo "<div class='custom-image text-wrap text-uppercase' style='background: {$bgColor}'>{$res}</div>";
    //     }
    //     else {
    //     return null;
    //     } 
    // }
    
?>