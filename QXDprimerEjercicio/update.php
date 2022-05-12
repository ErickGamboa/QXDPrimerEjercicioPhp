<?php

include('functions.php'); 
$code = $_POST['col1'];
$qty = $_POST['col4'];

if(!empty($code)){
    session_start();
    $productsInfo = getProducts();
    foreach($productsInfo as $jsonProduct){
        if($jsonProduct['codeProduct']==$code){
            if($jsonProduct['quantity']>=intval($qty)){
                $products = $_SESSION['infoCarrito'];
                foreach($products as $product){
                    if($product['code'] == $code){
                        $products[$product['code']]['quantity']=intval($qty);
                        unset($_SESSION['infoCarrito']);
                        $_SESSION['infoCarrito']=$products;
                        $data = file_get_contents('./catalogo.json');
                        $decodeData = json_decode($data, true);
                        $contador = 0;
                        foreach($decodeData  as $decodeProduct){
                            $contador+=1;
                            if($decodeProduct['codeProduct'] == $code){
                                $decodeData[$contador-1]['quantity']=($decodeProduct['quantity']+$product['quantity'])-$qty;
                                $json = json_encode($decodeData);
                                file_put_contents('./catalogo.json', $json);
                            }
                        }$message="Updated!";
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





