<?php

/*codes start for getting list*/
$file_name='data.json';
$response = [];
if(file_exists("$file_name")) {
    $current_data=file_get_contents("$file_name");
    $array_data=json_decode($current_data, true);
    if(!empty($array_data)){
       $response= $array_data;
    }
}
echo json_encode($response);
?>