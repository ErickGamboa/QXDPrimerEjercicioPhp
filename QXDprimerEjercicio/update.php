<?php

include('functions.php'); 
$code = $_POST['col1'];
$qty = $_POST['col4'];
if(!empty($code)){
    session_start();
    $productsInfo = getProducts();
    if (isset($_SESSION[$code])){
        foreach($productsInfo as $jsonProduct){
            if($jsonProduct['codeProduct']==$_SESSION[$code]['code']){
                if($jsonProduct['quantity']>=intval($qty)){
                    $_SESSION[$code]['quantity']=intval($qty);
                    $data = file_get_contents('./catalogo.json');
                    $decodeData = json_decode($data, true);
                    $contador = 0;
                    foreach($decodeData  as $decodeProduct){
                        $contador+=1;
                        if($decodeProduct['codeProduct'] == $code){
                            $decodeData[$contador-1]['quantity']=$decodeProduct['quantity']+$_SESSION[$code]['quantity']-$qty;
                            $json = json_encode($decodeData);
                            file_put_contents('./catalogo.json',$json);
                            $message="Updated!";
                            echo json_encode($message);
                        }
                    }
                }else{
                    $message="Ivalid Qty!";
                    echo json_encode($message);
                }
            }     
        }
    }        
    
}





