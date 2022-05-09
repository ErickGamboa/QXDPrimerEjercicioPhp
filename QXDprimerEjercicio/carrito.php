<!DOCTYPE html>
<html lang="en">
<head>
<title>Carrito</title>
<link rel="stylesheet" href="Style.css" type="text/css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<?php
include('functions.php'); 

$message= NULL;

session_start();
$user = $_SESSION['user'];
$_SESSION['message'] = NULL;
if (!$user) {
  $_SESSION['message'] = "YOU HAVE TO LOG IN";
  header('Location: login.php');
  }
?>
<body>

<form method="POST">
  <div class = "logoutButtonPosition">
    <button name = "LogoutButton" type="submit" class="btn btn-primary">Logout</button>
  </div>
</form>
<div class = "tableCarPosition">

  <div><h3>Cart</h3></div>

  <div>
      <table class="table">
          <thead>
              <tr>
              <th scope="col">Product</th>
              <th scope="col">Price</th>
              <th scope="col">Qty</th>
              <th scope="col">orderTotal</th>
              <th scope="col">Actions</th>
              </tr>
          </thead>
          <tbody>

            <?php
              $orderTotal=0;
              if(isset($_SESSION['infoCarrito'])){
              $products = $_SESSION['infoCarrito'];
              foreach($products as $product):
              $orderTotal+=$product['price']*intval($product['quantity']);
            ?>
            <tr>
              <form id="<?php echo $product['code']?>"><input type="hidden" name="code" value="<?php echo $product['code']?>" /></form>
              <td><img src="<?php echo $product['image']?>" alt="yoast seo" height="110" width="160"/> <?php echo $product['name'] ?> </td>
              <td>$<?php echo $product['price'] ?></td>
              <td><input form="<?php echo $product['code']?>" type="number" name="qty" value="<?php echo $product['quantity']?>" /></td>
              <td>$<?php echo $product['price']*intval($product['quantity']) ?></td>
              <td><input form="<?php echo $product['code']?>" type="submit" class="btn btn-primary" value="Update" /></td>
              <td><a href="" class="btn btn-primary">Remove</a></td>
            </tr>
            <?php
              endforeach;
              }
            ?>
          </tbody>
      </table>
  </div>
      <form method="POST">
        <div class = "buttonsTableLow">
          <button name = "back" type="submit" class="btn btn-primary"><-Back</button>
          <button name = "clearAll" type="submit" class="btn btn-primary">Clear All</button>
          <button name = "placeOrder" type="submit" class="btn btn-primary">Place Order</button>
        </div>
      </form>
  <input  name = "orderTotal" class="form-control" type="text" value="Order total: $<?php echo $orderTotal?>" style="text-align:center" disabled>

</div>


<?php
if (isset($_POST["LogoutButton"])){
  session_destroy();
  header('Location:login.php');
}


if (isset($_POST["back"])){
  header('Location:catalogue.php');
}

if (isset($_POST["clearAll"])){
  unset($_SESSION['infoCarrito']);
  header('Location:carrito.php');
}
if (isset($_POST["clearAll"])){
  unset($_SESSION['infoCarrito']);
  header('Location:carrito.php');
}
if (isset($_POST["T1"])){
  updateCarrito();
}
  

if (isset($_GET["code"])){
  $code = $_GET["code"];
  $qty = $_GET["qty"];
  $products = $_SESSION['infoCarrito'];
  $contador=0;
  foreach($products  as $product){
    $contador+=1;
    if($product['code'] == $code){
      $products[$contador-1]['quantity']=intval($qty);
      unset($_SESSION['infoCarrito']);
      $_SESSION['infoCarrito']=$products;
      header('Location:carrito.php');
    }

  }
}
?>

