<!DOCTYPE html>
<html lang="en">
<head>
<title>Login</title>
<link rel="stylesheet" href="Style.css" type="text/css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<?php
include('functions.php'); 

$message= NULL;

session_start();

if (isset($_POST["LoginButton"])) 
{
  /**Con esta validación se logra que el usuaio ingrese todos los datos requeridos */
  if(empty($_POST['EmailLogin']) ||  empty($_POST['PasswordLogin'])) {
      $_SESSION['message'] = "YOU HAVE TO PUT ALL INFORMATION";
  }

    else{
      /**Acá se valida si el método de autentificación retornó el usuario o no lo hizo.
      * Esto con la intención de hacer que el usuario ingrese o no al sistema */
      $Result = authenticate();
      if ($Result){
        session_start();
        $_SESSION['user'] = $Result;
        header('Location: catalogue.php');
      }
      
      else {
        $_SESSION['message'] = "INVALID CREDENTIALS";
      }

    }
}

if(isset($_SESSION['message'])){
  $message = $_SESSION['message'];
  unset($_SESSION['message']);
}
?>

<body>
<form method="POST" action"functions.php" >
<div class = "loginConteiner">  
  <div><h1>Login</h1></div>
  <div class="mb-3">
    <input name = "EmailLogin" type="email" class="form-control"   placeholder="Email address">
    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
  </div>
  <div class="mb-3">
    <input  name = "PasswordLogin" type="password" class="form-control"  placeholder="Password">
  </div>    
  <button name = "LoginButton" type="submit" class="btn btn-primary">Login</button>
  <br>
  <br>
  <div id="messageLogin" class="labelMessage">
    <?php 
      print $message;
      unset($_SESSION['message']);
    ?></div>
</form>
</div>



</body>
</html>
