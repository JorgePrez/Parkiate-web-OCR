


<?php




$conn = pg_connect("host=db-instancia.ccwm7dhw4cau.us-east-1.rds.amazonaws.com port=5432 user=postgres password=56721449 dbname=postgres");
if (!$conn){
    die("PostgreSQL connection failed");
   
}


if(!isset($_COOKIE["id_parqueo"])){

   $id_parqueo='N';

}

else{

  $id_parqueo= $_COOKIE["id_parqueo"];


}



if(!isset($_COOKIE["id_usuario"])){
  header("Location: login.php");

}



else{    
  $id_usuario= $_COOKIE["id_usuario"];

}  
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  
  <title>Parkiate-ki (Administrador)</title>

<!-- Favicons -->
<link href="img/favicon1.png" rel="icon">
<link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Bootstrap core CSS -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="lib/bootstrap-datepicker/css/datepicker.css" />
  <link rel="stylesheet" type="text/css" href="lib/bootstrap-daterangepicker/daterangepicker.css" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">

  <!-- =======================================================
    Template Name: Dashio
    Template URL: https://templatemag.com/dashio-bootstrap-admin-template/
    Author: TemplateMag.com
    License: https://templatemag.com/license/
  ======================================================= -->
</head>

<body>
  <section id="container">
    <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->

        <header class="header black-bg">
            <div class="sidebar-toggle-box">
              <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
            </div>
            <!--logo start-->
            <a href="index.html" class="logo"><b><span>PARK</span>IATE<span>-KI</span></b></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
              <!--  notification start -->
              <ul class="nav top-menu">
                <!-- settings start -->
               
      
          
                <!-- inbox dropdown end -->
                <!-- notification dropdown start-->
            
                <!-- notification dropdown end -->
              </ul>
              <!--  notification end -->
            </div>
              
        <div class="top-menu">
          <ul class="nav pull-right top-menu">
            <li><a class="logout" href="formularios/logout.php">Cerrar Sesión</a></li>
          </ul>
        </div>
          </header>

    <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
        
    

<aside>
      <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
          <p class="centered"><img src="img/park_icon2.jpg" class="img-circle" width="80"></a></p>
          <h5 class="centered">
        
          
    <?php
          if(strlen($id_parqueo) >1)
          {
            echo 'Parqueo la sexta'; //TODO: modificar el nombre del parqueo
          }
          else{
            echo 'Parece que áun no has registrado todos los detalles de tu parqueo, hazlo para poder ver todas nuestras opciones';

          }
     

          ?>
        
        </h5> 
        
      
        <li class="mt">
<a class="active" >
  <i class="fa fa-columns"></i>
  <span>Registrar mi parqueo</span>
  </a>

</li>

        </ul>
        <!-- sidebar menu end-->
      </div>
    </aside>


<?php

$id_parqueo = $_GET['id_parqueo'];


?>


    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
      <h3><i class="fa fa-columns"></i> Agrega la información de tu parqueo </h3>
        <!-- BASIC FORM ELELEMNTS -->


        <!-- /row -->
       
        <!-- /row -->
        <!-- INPUT MESSAGES -->
         
        <form action="formularios/detalles.php" method="get">


        <div class="row mt">
          <div class="col-lg-12">
            <div class="form-panel">
            
                     <h4 class="mb"><i class="fa fa-picture-o"></i> Agregue alguna foto para que sus clientes tengan una mejor referencia</h4>




             
               <div class="form-group"> 


               <input type="hidden" name="id_parqueo" id="id_parqueo" value="<?php echo $id_parqueo; ?>">

                         <!--  <div class="col-lg-6">
                              <input type="file" name="fotografia" id="exampleInputFile" class="file-pos">
                            </div>  --> 
                     <!--       <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 desc"> -->


                            <div class="project-wrapper">
                              <div class="project">
                                <div class="photo-wrapper">
                                  <div class="photo">
                                        <img id=user-photo class="img-responsive" src="img/portfolio/Portrait_Placeholder.png" alt="">
                                  </div>
                                </div>
                              </div>
                            </div>

                            <button type="button" class="btn btn-success" id="btn-foto" >Subir foto</button>

                            </div>

                            
    


        <div class="showback">

            

<button class="btn btn-primary btn-lg btn-block" type="button" onclick="retornar()"> 
  Registrar Parqueo</button>
</div>




           
              </form>

    
            
       
            </div>
            <!-- /form-panel -->
          </div>
          <!-- /col-lg-12 -->
          <!-- CUSTOM TOGGLES -->
          
        </div>
        <!-- /row -->
      </section>
      <!-- /wrapper -->
    </section>
    <!-- /MAIN CONTENT -->
    <!--main content end-->
    <!--footer start-->
   
    <!--footer end-->
  </section>
  <!-- js placed at the end of the document so the pages load faster -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="lib/jquery.scrollTo.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <!--common script for all pages-->
  <script src="lib/common-scripts.js"></script>
  <!--script for this page-->
  <script src="lib/jquery-ui-1.9.2.custom.min.js"></script>
  <!--custom switch-->
  <script src="lib/bootstrap-switch.js"></script>
  <!--custom tagsinput-->
  <script src="lib/jquery.tagsinput.js"></script>
  <!--custom checkbox & radio-->
  <script type="text/javascript" src="lib/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="lib/bootstrap-daterangepicker/date.js"></script>
  <script type="text/javascript" src="lib/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script type="text/javascript" src="lib/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
  <script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>
  <script src="js/servicio-imagen.js"></script>
  <script src="lib/form-component.js"></script>
  
  <script type="text/javascript">


   

function retornar() {


  var id_parqueo = document.getElementById("id_parqueo");

  const foto= document.querySelector('#user-photo');


  var linkimagen= foto.src;


  response="formularios/detalles_crear.php?id_parqueo="+id_parqueo.value+"&fotografia="+linkimagen;

//response="formularios/detalles.php?imagen="+"+linkimagen;
window.location.href = response;

}




</script>




</body>

</html>
