<?php
$output = [];
function get_data() {
    $file_name='data.json';
    $date = date("d-m-Y h:i:sa");
    if(file_exists("$file_name")) {
        $current_data=file_get_contents("$file_name");
        $array_data=json_decode($current_data, true);
        $extra=array(
            "product" => $_POST['product'],
            "stock" => $_POST['stock'],
            "price" => $_POST['price'],
            "date" => $date,
        );
        if(!empty($array_data)){
            $pname = $_POST['product'];
            $flag = 0;
            $array = [];
            foreach($array_data as $arrData){
                if($arrData['product'] == $pname){
                    $arrData['stock'] = $_POST['stock'];
                    $arrData['price'] = $_POST['price'];
                    $arrData['date'] = $date;
                    $array[] = $arrData;
                    $flag = 1;
                }else {
                   $array[] = $arrData; 
                }
            }
            $array_data = $array;
            if($flag != 1){
                $array_data[]=$extra;   
            }
        }else{
            $array_data[]=$extra;   
        }
        return json_encode($array_data);
    }
    else {
        $data=array();
        $data[]=array(
            "product" => $_POST['product'],
            "stock" => $_POST['stock'],
            "price" => $_POST['price'],
            "date" => $date,
        );
        return json_encode($data);
    }
}

$file_name = "data.json";
$fileData = get_data();
$result = file_put_contents($file_name, $fileData);
if($result != false) {
    $output['msg'] = 'Record Added Successfully';
}
else {
    $output['msg'] = 'Something went wrong';
}
echo json_encode($output);
?>