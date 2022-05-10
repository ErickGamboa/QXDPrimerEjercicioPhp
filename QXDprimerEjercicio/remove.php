<?php

$code = $_POST['col1'];
$qty = $_POST['col4'];

if(!empty($code)){
    session_start();
    $products = $_SESSION['infoCarrito'];
        foreach($products  as $product){
          if($product['code'] == $code){
            unset($products[$product['code']]);
            unset($_SESSION['infoCarrito']);
            $_SESSION['infoCarrito']=$products;
          }
      
        }
    $message="Removed!";
    echo json_encode($message);
}

