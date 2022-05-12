<!DOCTYPE html>
<html lang="en">
  <head>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>    
    <script type="text/javascript" src="https://rawcdn.githack.com/franz1628/validacionKeyCampo/bce0e442ee71a4cf8e5954c27b44bc88ff0a8eeb/validCampoFranz.js"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-sacale=1.0">

    <title>Carrito</title>
    <link rel="stylesheet" href="Style.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  </head>

  <?php
    ob_start();
    include('functions.php'); 

    $message= NULL;

    session_start();
    $user = $_SESSION['user'];
    $_SESSION['message'] = NULL;

    if (!$user){
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
            <th scope="col"></th>
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
              $productsJson=getProducts();
              foreach($productsJson as $productJson):
              if (isset($_SESSION[$productJson['codeProduct']])){
              $orderTotal+=$productJson['price']*intval($_SESSION[$productJson['codeProduct']]['quantity']);
            ?>
            <tr>
              <td><input type="hidden" value="<?php echo $_SESSION[$productJson['codeProduct']]['code'] ?>" /></td>
              <td><img src="<?php echo $productJson['image']?>" alt="yoast seo" height="110" width="160"/> <?php echo $productJson['name'] ?> </td>
              <td>$<?php echo $productJson['price'] ?></td>
              <td><input type="text" style="text-align:center" value="<?php echo $_SESSION[$productJson['codeProduct']]['quantity']?>" id="soloNumeros"></td>
              <td>$<?php echo $productJson['price']*intval($_SESSION[$productJson['codeProduct']]['quantity']) ?></td>
              <td>
                <button class="btn btn-primary btnUpdate">Update</button>
                <button class="btn btn-primary btnRemove">Remove</button>
              </td>
            </tr>
            <?php
              }
              endforeach;
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
      <label  name = "orderTotal" style="text-align:center">Order total: $<?php echo $orderTotal?></label>
    </div>
  </body>
</html>
<script>
  $(function(){  
    $('#soloNumeros').validCampoFranz('0123456789'); });
</script>

<script>
$(document).ready(function(){
  $(".btnUpdate").on('click',function(){
    var currentRow =$(this).closest("tr");
    var col1=currentRow.find("td:eq(0) input").val();
    var col4=currentRow.find("td:eq(3) input").val();
    $.ajax(
      {
        url:'./update.php',
        method:'POST',
        data:{
          col1:col1,
          col4:col4
        }
      }).done(function(res){
        var datos=JSON.parse(res);
        alert(datos);
        window.location.reload(true);
      });
  });
});
</script>

<script>
$(document).ready(function(){
  $(".btnRemove").on('click',function(){
    var currentRow =$(this).closest("tr");
    var col1=currentRow.find("td:eq(0) input").val();
    var col4=currentRow.find("td:eq(3) input").val();
    $.ajax(
      {
        url:'./remove.php',
        method:'POST',
        data:{
          col1:col1,
          col4:col4
        }
      }).done(function(res){
        var datos=JSON.parse(res);
        alert(datos);
        window.location.reload(true);
      });
  });
});
</script>

<?php
if (isset($_POST["LogoutButton"])){
  session_destroy();
  header('Location:login.php');
}

if (isset($_POST["back"])){
  header('Location:catalogue.php');
}

if (isset($_POST["clearAll"])){

  $data = file_get_contents('./catalogo.json');
  $decodeData = json_decode($data, true);
  $contador = 0;
  foreach($decodeData  as $decodeProduct){
    $contador+=1;
      if (isset($_SESSION[$decodeProduct['codeProduct']])){
        $decodeData[$contador-1]['quantity'] += $_SESSION[$decodeProduct['codeProduct']]['quantity'];
        $json = json_encode($decodeData);
        file_put_contents('./catalogo.json', $json);
        unset($_SESSION[$decodeProduct['codeProduct']]);
      }
  }
  unset($_SESSION['infoCarrito']);
  header('Location:catalogue.php');
}

if (isset($_POST["placeOrder"])){
  header('Location:orderPlaced.php');
}

?>