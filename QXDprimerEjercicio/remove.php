<?php

$code = $_POST['col1'];
$qty = $_POST['col4'];

session_start();
if (isset($_SESSION[$code])){
    $data = file_get_contents('./catalogo.json');
    $decodeData = json_decode($data, true);
    $contador = 0;
    foreach($decodeData  as $decodeProduct){
        $contador+=1;
        if($decodeProduct['codeProduct'] == $code){
            $decodeData[$contador-1]['quantity']=$decodeProduct['quantity']+=$_SESSION[$code]['quantity'];
            $json = json_encode($decodeData);
            file_put_contents('./catalogo.json',$json);
            $message="Removed!";
            echo json_encode($message);
            unset($_SESSION[$code]);
        }
    }
}

