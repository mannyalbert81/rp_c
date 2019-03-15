<!DOCTYPE html>
<html lang="en">
  <head>
    

    <title>Template 2018</title>


	  		<link rel="stylesheet" href="view/css/estilos.css">
	  
  	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  	<link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="view/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="view/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="view/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="view/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="view/build/css/custom.min.css" rel="stylesheet">
    
			<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <img src="view/images/logo.png" width="320" height="190">
            <form  action="<?php echo $helper->url("Usuarios","resetear_clave_inicio"); ?>" method="post" ">
             
             
             
              <h1>Recuperar Clave</h1>
              <div>
                <input id="cedula_usuarios" name="cedula_usuarios" type="number" class="form-control" placeholder="cedula.."/>
                <div id="mensaje_cedula_usuarios" class="errores"></div>
              </div>
             
              <div style="text-align: center; margin-top:20px">
              
              	<button type="submit" id="Guardar" name="Guardar" class="btn btn-success" ><i class="fa fa-unlock" aria-hidden="true"></i> Recuperar</button>
                <button type="submit" id="Cancelar" name="Cancelar" onclick="this.form.action='<?php echo $helper->url("Usuarios","Inicio"); ?>'" class="btn btn-primary"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
              </div>

              <div class="clearfix"></div>
			   <div class="separator">
                <div class="clearfix"></div>
                <div>
              
                 <p>©2018 All Rights Reserved</p>
                </div>
              </div>
              
                       
                    	
                              <?php if (isset($resultSet)) {?>
							<?php if ($resultSet != "") {?>
						
								 <?php if ($error == TRUE) {?>
								    <div class="row">
								    <div class="col-lg-12 col-md-12 col-xs-12">
								 	<div class="alert alert-danger" role="alert"><?php echo $resultSet; ?></div>
								 	</div>
								 	</div>
								 <?php } else {?>
								    <div class="row">		
								    <div class="col-lg-12 col-md-12 col-xs-12">	
								    <div class="alert alert-success" role="alert"><?php echo $resultSet; ?></div>
								    </div>
								    </div>
								 <?php sleep(5); ?>
				     
				     			 <?php }?>
							
					        <?php } ?>
					        <?php } ?>  
                    		   
              
            </form>
          </section>
        </div>

              </div>
    </div>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>  
   <script src="view/Administracion/js/ResetUsuariosInicio.js?1.0"></script> 
  
  </body>
</html>
