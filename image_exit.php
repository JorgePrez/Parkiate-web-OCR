

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


  $objeto = '1';

    if((str_contains($objeto, '1')))
    {
    //echo "HAY OBJETO PROCESANNDO.......................:";echo "\n";
    $success=true;

    //OBTENER IMAGEN DE CÁMARA
    $url = $endpoint_image_salida;
     $ch = curl_init($url);
    $dir = './';$file_name = basename('placa_salida_p.jpeg');
    $save_file_loc = $dir . $file_name;
    $fp = fopen($save_file_loc, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);curl_setopt($ch, CURLOPT_HEADER, 0);curl_exec($ch);fclose($fp);

    //ENVIAR IMAGEN A API Y OBTENER RESPUESTA
 

    $file = realpath('placa_salida_p.jpeg');  
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

    //¿RESPUESTA ES VÁLIDA?
    if((count($response)==0)){
      echo "\n";echo "warning: en la foto no hay ninguna placa"; $success=false;
}

    else{


      $placa_detectada = $response[0]->license_plate;
      $h= $response[0]->h;
      $w= $response[0]->w;
      $x= $response[0]->x;
      $y =$response[0]->y;


        $placa_detectada = strtoupper($placa_detectada);

        include('IOT_scripts/placa_procesando.php');



        $img= $file;         
         $rutafull='/parqueos/'.$id_parqueo.'/camara_salida/full';
        $rutaplaca='/parqueos/'.$id_parqueo.'/camara_salida/placa'; 

        $response_full=json_encode($uploader->upload($img,['folder' => $rutafull]));
        $response_placa=json_encode($uploader->upload($img,['folder' => $rutaplaca,'width' => $w, 'height' => $h, 'crop' => 'crop' , 'x' => $x, 'y' => $y]));

    

        $imagen_full = json_decode($response_full);   $imagen_full=$imagen_full->secure_url;
        $imagen_placa = json_decode($response_placa); $imagen_placa=$imagen_placa->secure_url;
        $imagen_auto = json_decode($response_full);   $imagen_auto =$imagen_auto->secure_url;


        include('IOT_scripts/autosdentro.php');


        if($resultado_autosdentro=='N'){
          echo "\n";echo "warning: Nada que registrar";$success=false;
        }

        else{

          include('IOT_scripts/ultima_placa_salida.php');



          if( $resultado_ultima_salida=='N'){
            echo "\n";echo "warning: Imagen repetida";$success=false;
          }
  
          else{


            include('IOT_scripts/placas_autos_dentro.php');


            
                  if( $resultado_placa_dentro=='N'){
                    echo "\n";echo "warning: No hay placa dentro que coincida";$success=false;
                  }

                  else{  
                      include('IOT_scripts/salida_register.php');
                      include('IOT_scripts/salida_tables.php');
                      include('IOT_scripts/finalizar_usuario_app.php');
                      include('IOT_scripts/auto_actualizar.php');
                        echo "\n";echo "Success: camara_salida registrando";$success=true;
                        echo "\n";  echo $placa_detectada;  echo "-->"; echo $placa_necesita_correccion; 
                  }

          }

     
            }

    }

  

    }



  


?>

