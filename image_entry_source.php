

<?php

require_once __DIR__ . '/vendor/autoload.php';

use Cloudinary\Cloudinary;

use Cloudinary\Transformation\Resize;

use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Format;



$conn = pg_connect("host=db-instancia.ccwm7dhw4cau.us-east-1.rds.amazonaws.com port=5432 user=postgres password=56721449 dbname=postgres");
if (!$conn){
    die("PostgreSQL connection failed");
   
}

date_default_timezone_set('America/Guatemala');



 $cloudinary = new Cloudinary(
    [
        "cloud" =>
            [
                'cloud_name' => 'parkiate-ki',
                'api_key'    => '794241658481217',
                'api_secret' => 'qRQnXnrfL-xqXug4sfFlMgGfeAY',
            ],
            'url' => [
                       'secure' => true //default
            ]
    ]
);

$uploader = $cloudinary->uploadApi();

include('dbcon.php');
include 'image_sources.php';

$id_parqueo =$id_parqueo_actual;




while(true){

    echo "\n";
    echo "\n";



echo "Esperando imagen (entrada): ";
fscanf(STDIN, "%s", $tocheck);
//echo "LEIDO:, $tocheck";
echo "\n";

//1. OBTENER PARAMETROS

if (str_contains($tocheck, 'res.cloudinary.com')) {




   $real_name =    strrchr($tocheck , '/');

   $real_name = substr($real_name, 1);

   //Hello World



   echo "\n";
   echo $real_name;
   echo "\n";

   $real_name = "Entrada_" . $real_name;



    $success=true;



     /*
    echo "\n";
    echo $tocheck;
    echo "\n"; */

    $url = $tocheck;
    $ch = curl_init($url);
    $dir = './';$file_name = basename($real_name);
    $save_file_loc = $dir . $file_name;
    $fp = fopen($save_file_loc, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);curl_setopt($ch, CURLOPT_HEADER, 0);curl_exec($ch);fclose($fp);


    $file = realpath($real_name);  
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

    $placa='';  $h='';  $w='';  $x='';  $y='';


    //PROCESANO RESPUESTA DE API

    //??RESPUESTA ES V??LIDA?

    if((count($response)==0)){
      echo "\n";echo "warning: en la foto no hay ninguna placa"; $success=false;
}


    else{


        $placa_detectada = $response[0]->license_plate;
        $h= $response[0]->h;
        $w= $response[0]->w;
        $x= $response[0]->x;
        $y =$response[0]->y;
      /*
        $xmin_placa =$response->results[0]->box->xmin;  $ymin_placa =$response->results[0]->box->ymin;
        $xmax_placa =$response->results[0]->box->xmax;  $ymax_placa=$response->results[0]->box->ymax;*/

      //  $x=$xmin_placa; $y= $ymin_placa;  $w= $xmax_placa-$xmin_placa;  $h= $ymax_placa-$ymin_placa;

      //  $placa_detectada=$response->results[0]->plate;
        $placa_detectada = strtoupper($placa_detectada);

        include('IOT_scripts/placa_procesando.php');


        /*
        $xmin_auto =$response->results[0]->vehicle->box->xmin;  $ymin_auto =$response->results[0]->vehicle->box->ymin;
        $xmax_auto =$response->results[0]->vehicle->box->xmax;  $ymax_auto=$response->results[0]->vehicle->box->ymax;
        $x_a=$xmin_auto;  $y_a= $ymin_auto; $w_a= $xmax_auto-$xmin_auto; $h_a= $ymax_auto-$ymin_auto;*/
        
    //    echo "\n";  echo $placa_detectada;  echo "-->"; echo $placa_necesita_correccion;


        $img= $file;          
        $rutafull='/parqueos/'.$id_parqueo.'/entrada/full';
        $rutaplaca='/parqueos/'.$id_parqueo.'/camara_entrada/placa'; 
       // $rutavehiculo='/parqueos/'.$id_parqueo.'/camara_entrada/vehiculo';

        $response_full=json_encode($uploader->upload($img,['folder' => $rutafull]));
        $response_placa=json_encode($uploader->upload($img,['folder' => $rutaplaca,'width' => $w, 'height' => $h, 'crop' => 'crop' , 'x' => $x, 'y' => $y]));
       /*
        if($xmin_auto > 0 && $ymin_auto > 0 && $xmax_auto > 0 && $ymax_auto > 0){
            $response_auto=json_encode($uploader->upload($img,['folder' => $rutavehiculo,'width' => $w_a, 'height' => $h_a, 'crop' => 'crop' , 'x' => $x_a, 'y' => $y_a]));}
        else{
             $response_auto=json_encode($uploader->upload($img,['folder' => $rutavehiculo]));}*/

        $imagen_full = json_decode($response_full);   $imagen_full=$imagen_full->secure_url;
        $imagen_placa = json_decode($response_placa); $imagen_placa=$imagen_placa->secure_url;
        $imagen_auto = json_decode($response_full);   $imagen_auto =$imagen_auto->secure_url;


        include('IOT_scripts/ultima_placa.php');


        if($resultado_ultima_placa=='N'){
          echo "\n";echo "warning: imagen repetida";$success=false;
        }

        else{

        

          include('IOT_scripts/entrada_register.php');

            


          //??PLACA CUMPLE FORMTATO?

            if($placa_necesita_correccion=='S'){

              include('IOT_scripts/registro.php');



            }

            else{

              include('IOT_scripts/registro.php');



              include('IOT_scripts/comprobar_auto.php');

              if($condicion_auto=='N'){

                include('IOT_scripts/crear_auto.php');


              }
              else{

                include('IOT_scripts/obtener_auto.php');


              }

              include('IOT_scripts/update_registro_autos.php');




            }

              

              echo "\n";echo "Success: imagen_entrada registrando";$success=true;
              echo "\n";  echo $placa_detectada;  echo "-->"; echo $placa_necesita_correccion; 
        }

  

    }




  
              
                                                               
                                                         
                                                      
                                                      


        


}


}




?>



