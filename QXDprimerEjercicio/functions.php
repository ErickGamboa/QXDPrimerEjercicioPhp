<?php
/**Función que consume los datos del json y los compara con los datos de login ingresados por el usuario */
/**Si la vadilación es correcta retorna los  datos  del usuario */
function authenticate (){
    if(file_exists("./users.json")){
        $string = file_get_contents("./users.json");
        $json = json_decode($string, true);
        if(!empty($json)){
            $email = $_POST['EmailLogin'];
            $password = $_POST['PasswordLogin'];
            foreach($json as $user)
            {
                if ($user ['email'] == $email AND $user ['password'] == $password ){
                return $user;
                }
            }
        }
    }
}

function getProducts(){
    if(file_exists("./users.json")){
        $string = file_get_contents("./catalogo.json");
        $json = json_decode($string, true);
        if(!empty($json)){

            foreach($json as $product)
            {
                if($product['quantity']>0){
                $products[]=$product;
                }
            }
            return $products;
        }
    }

}
function getCodeCatalogue(){
    $products = getProducts();
    $arrayCodes =NULL;
    foreach($products as $product){
        $qty = $_POST[$product['codeProduct']];
        if(!empty($qty)){
            $arrayCodes[] =
            [
                "code" => $product['codeProduct'],
                "qty" => $qty
            ];
        }   
    }return $arrayCodes;
    
}



/** 
function saveCarrito($newData){
    if(!empty($newData)){
        //$data = file_get_contents('./carrito.json');
        //$decodeData = json_decode($data, true);
        //if(!empty($decodeData)){ 
            //$decodeData = array_push($decodeData, $newData); 
        //}else{ 
            //$decodeData[] = $newData; 
        //}
        $json = json_encode($newData);
        file_put_contents('./carrito.json', $json);

                
        
    }
   


function showCarrito(){
    $data = file_get_contents('./carrito.json');
    $decodeData = json_decode($data, true);
    return $decodeData;
}
*/



