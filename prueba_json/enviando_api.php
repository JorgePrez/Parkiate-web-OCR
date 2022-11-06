

<?php

 


$conn = pg_connect("host=db-instancia.ccwm7dhw4cau.us-east-1.rds.amazonaws.com port=5432 user=postgres password=56721449 dbname=postgres");
if (!$conn){
    die("PostgreSQL connection failed");
   
}

#print("EXITO");



    $endpoint_image_entrada ='https://res.cloudinary.com/parkiate-ki/image/upload/v1656822676/placas_guatemala_pruebas/P698JNM.jpg';

    $endpoint_image_entrada ='https://res.cloudinary.com/parkiate-ki/image/upload/v1656822676/placas_guatemala_pruebas/P698JNM.jpg';
    

   
    $url = $endpoint_image_entrada;
    $ch = curl_init($url);
    $dir = './';$file_name = basename('placa_entrada_p.jpeg');
    $save_file_loc = $dir . $file_name;
    $fp = fopen($save_file_loc, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);curl_setopt($ch, CURLOPT_HEADER, 0);curl_exec($ch);fclose($fp);



  $file = realpath('placa_entrada_p.jpeg');  
    if (function_exists('curl_file_create')) { $cFile = curl_file_create($file);} // php 5.5+
    else {$cFile = '@' . realpath($file);}
    $data = array(
    'imagen_subida' => $cFile,
     );

    $ch = curl_init('http://127.0.0.1:5000/api/license_plate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true)
    ;curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2TLS);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   // "Authorization: Token 222f6c6970669f844c36f2b342a45cae3d88f73c"  //API KEY
    ));

    $now = new Datetime('now');$now = $now->format('Y-m-d H:i:s');
    $result = curl_exec($ch);
    $response = json_decode($result);

    print_r($response);

    $placa='';
    $h='';
    $w='';
    $x='';
    $y='';


/*    if(($response[0]===null)){ */


    if((count($response)==0)){



        echo "\n";echo "warning: en la foto no hay ninguna placa";
}
else{
    $placa = $response[0]->license_plate;
    $h= $response[0]->h;
    $w= $response[0]->w;
    $x= $response[0]->x;
    $y =$response[0]->y;
    
    echo "\n";
    echo "placa: "; echo $placa;
    echo "\n";
    echo "h: "; echo $h;
    echo "\n";
    echo "w: "; echo $w;
    echo "\n";
    echo "x: "; echo $x;
    echo "\n";
    echo "y: "; echo $y;


}




    

  /*  
    $numero_detectado
    $h
    $now
    $x
    $y

    */




    ?>