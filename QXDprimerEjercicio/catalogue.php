<!DOCTYPE html>
<html lang="en">
<head>
<title>Catalogue</title>
<link rel="stylesheet" href="Style.css" type="text/css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<?php
ob_start();
$message= NULL;
session_start();
if(isset($_SESSION['message'])){
    $message = $_SESSION['message'];
}
include('functions.php');
$user = $_SESSION['user'];
$_SESSION['message'] = NULL;
if (!$user) {
  $_SESSION['message'] = "YOU HAVE TO LOG IN";
  header('Location: login.php');
  }
$products = getProducts();
foreach($products as $product){

?>
<form method="POST" action"functions.php">
<div class="conteiner">
<div class="box">
<img src="<?php echo $product['image']?>" alt="yoast seo" height="210" width="260"/>
<h4><?php echo $product['name']?></h4>
<h4> $ <?php echo $product['price']?></h4>
<label  name = "code" style="text-align:center"><?php echo $product['codeProduct']?></label>
<Br>
<label  name = "code" style="text-align:center">Quantity Available: <?php echo $product['quantity']?></label>
<input  name = "<?php echo $product['codeProduct']?>"type="number" pattern="[^e\x22]+" style="text-align:center"></div></div>
<?php
}
?>
<div class = "AddToCartPosition">
<button name = "AddButton" type="submit" class="btn btn-primary">Add to cart</button>
</div>
</form>

<form method="POST">
<div class = "logoutButtonPosition">
<button name = "LogoutButton" type="submit" class="btn btn-primary">Logout</button>
</div>
</form>

<div class = "qtyMessage">
  <h5><?php 
    echo $message;
  ?></h5>
  </div>
  </body>

<?php

  
if (isset($_POST["LogoutButton"])){
  session_destroy();
  header('Location:login.php');
}

$infoCarrito=NULL;
if (isset($_POST["AddButton"])){
  $productsCarrito=$_SESSION['infoCarrito'];
  if(empty($productsCarrito)){
    if(!empty(getCodeCatalogue())){
      $codeAndQtySelected=getCodeCatalogue();
      foreach($codeAndQtySelected as $product){
        $productsInfo = getProducts();
        foreach($productsInfo as $jsonProduct){
          if($jsonProduct['codeProduct']==$product['code']){
            if($jsonProduct['quantity']>=$product['qty']){
              $infoCarrito[$jsonProduct['codeProduct']]=
              [
                "code" => $jsonProduct['codeProduct'],
                "name" => $jsonProduct['name'],
                "price" => $jsonProduct['price'],
                "quantity" => $product['qty'],
                "image" => $jsonProduct['image'],
              ];
              $newQtyProduct = $jsonProduct['quantity']-$product['qty'];
              $data = file_get_contents('./catalogo.json');
              $decodeData = json_decode($data, true);
              $contador = 0;
              foreach($decodeData  as $decodeProduct){
                $contador+=1;
                if($decodeProduct['codeProduct'] == $jsonProduct['codeProduct']){
                  $decodeData[$contador-1]['quantity'] = $newQtyProduct;
                  $json = json_encode($decodeData);
                  file_put_contents('./catalogo.json', $json);
                }
              }
            }else{ 
              $codesQtyError[]=$jsonProduct['codeProduct'];
              $stringcodesQtyError=implode("-",$codesQtyError);
              $_SESSION['message'] = "Qty not availble of: ".$stringcodesQtyError;
            } 
          }
        }
      }
    }
    if(!empty($infoCarrito)AND!isset($_SESSION['message'])){
      $_SESSION['infoCarrito'] = $infoCarrito;
      $infoCarrito=NULL;
      header('Location:carrito.php');
    }else{
      header('Location:catalogue.php');
    }
            
  }else{
    if(!empty(getCodeCatalogue())){
      $codeAndQtySelected=getCodeCatalogue();
      foreach($codeAndQtySelected as $product){
        $productsInfo = getProducts();
        foreach($productsInfo as $jsonProduct){
          if($jsonProduct['codeProduct']==$product['code']){
            if($jsonProduct['quantity']>=$product['qty']){
              foreach($productsCarrito as $productCarrito){
                if($productCarrito['code']==$product['code']){
                  $productsCarrito[$product['code']]['quantity'] += $product['qty'];
                  $_SESSION['infoCarrito'] = $productsCarrito;
                  header('Location:carrito.php');
                  $newQtyProduct = $jsonProduct['quantity']-$product['qty'];
                  $data = file_get_contents('./catalogo.json');
                  $decodeData = json_decode($data, true);
                  $contador = 0;
                  foreach($decodeData  as $decodeProduct){
                    $contador+=1;
                    if($decodeProduct['codeProduct'] == $jsonProduct['codeProduct']){
                      $decodeData[$contador-1]['quantity'] = $newQtyProduct;
                      $json = json_encode($decodeData);
                      file_put_contents('./catalogo.json', $json);
                    }else{};
                  }
                }
              }
            }else{ 
              $codesQtyError[]=$jsonProduct['codeProduct'];
              $stringcodesQtyError=implode("-",$codesQtyError);
              $_SESSION['message'] = "Qty not availble of: ".$stringcodesQtyError;
            } 
          }
        }$infoCarrito=$_SESSION['infoCarrito'];
        foreach($infoCarrito as $productCarrito){
          if($product['code']!=$productCarrito['code']){
            $productsInfo = getProducts();
            foreach($productsInfo as $jsonProduct){
              if($jsonProduct['codeProduct']==$product['code']){
                if($jsonProduct['quantity']>=$product['qty']){
                  $infoFinalCarrito=$_SESSION['infoCarrito'];
                  $infoFinalCarrito[$jsonProduct['codeProduct']]=
                  [
                    "code" => $jsonProduct['codeProduct'],
                    "name" => $jsonProduct['name'],
                    "price" => $jsonProduct['price'],
                    "quantity" => $product['qty'],
                    "image" => $jsonProduct['image'],
                  ];
                  $newQtyProduct = $jsonProduct['quantity']-$product['qty'];
                  $data = file_get_contents('./catalogo.json');
                  $decodeData = json_decode($data, true);
                  $contador = 0;
                  foreach($decodeData  as $decodeProduct){
                    $contador+=1;
                    if($decodeProduct['codeProduct'] == $jsonProduct['codeProduct']){
                      $decodeData[$contador-1]['quantity'] = $newQtyProduct;
                      $json = json_encode($decodeData);
                      file_put_contents('./catalogo.json', $json);
                    }
                  }
                }else{ 
                  $codesQtyError[]=$jsonProduct['codeProduct'];
                  $stringcodesQtyError=implode("-",$codesQtyError);
                  $_SESSION['message'] = "Qty not availble of: ".$stringcodesQtyError;
                  header('Location:catalogue.php');
                } 
              }
            }
          }
        }if(!empty($infoFinalCarrito)){
          $_SESSION['infoCarrito'] = $infoFinalCarrito;
          header('Location:carrito.php');
        } 
      }
    }
  }
}    

                           
        /** 
        
        if(!empty(getCodeCatalogue())){
            $codeAndQtySelected=getCodeCatalogue();
            print_r($codeAndQtySelected);
          foreach($codeAndQtySelected as $product){
              foreach($productsInfo as $jsonProduct){
                if($jsonProduct['codeProduct']==$product['code']){
                  
                  if($jsonProduct['quantity']>=$product['qty']){
                    
                    
                    $infoCarrito=$_SESSION['infoCarrito'];
                  
                    $infoCarrito[]=$jsonProduct['codeProduct']=
                          [
                            "code" => $jsonProduct['codeProduct'],
                            "name" => $jsonProduct['name'],
                            "price" => $jsonProduct['price'],
                            "quantity" => $product['qty'],
                            "image" => $jsonProduct['image'],
                          ];
                          
                        
                      $newQtyProduct = $jsonProduct['quantity']-$product['qty'];
                      $data = file_get_contents('./catalogo.json');
                      $decodeData = json_decode($data, true);
                      $contador = 0;
                        foreach($decodeData  as $decodeProduct){
                            $contador+=1;
                            if($decodeProduct['codeProduct'] == $jsonProduct['codeProduct']){
                              $decodeData[$contador-1]['quantity'] = $newQtyProduct;
                              $json = json_encode($decodeData);
                              file_put_contents('./catalogo.json', $json);
                              }
                          }
                  }
                  
                  else{ 
                    $codesQtyError[]=$jsonProduct['codeProduct'];
                    $stringcodesQtyError=implode("-",$codesQtyError);
                    $_SESSION['message'] = "Qty not availble of: ".$stringcodesQtyError;
                  } 
                }
              }
            
          
          if(!empty($infoCarrito)AND!isset($_SESSION['message']) )
          {
            $_SESSION['infoCarrito'] = $infoCarrito;
            //$infoCarrito=NULL;
            header('Location:carrito.php');
          } else{
            //header('Location:catalogue.php');
          }
          
        //header('Location:carrito.php');
      
    */

?>
  