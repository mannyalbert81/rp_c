<!DOCTYPE html>
<html lang="en">
  <head>
    

    <title>Capremci</title>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    
    
   <?php include("view/modulos/links_css.php"); ?>
    
  </head>

  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        
        <img src="view/images/logoerp2.png" class="img-fluid" alt="Logo ERP">
      </div>


   <div class="login-box-body">
    <p class="login-box-msg">Inicia tu sesión</p>

 
    <form id="form-login" action="<?php echo $helper->url("Usuarios","Loguear"); ?>" method="post" >
      <div class="form-group has-feedback">
        <input type="text"  id="usuario" name="usuario" class="form-control" placeholder="..cedula">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input id="clave" name="clave"   type="password" class="form-control" placeholder="password.." >
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-4">
        </div>
        <div class="col-xs-4">
         
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
      
      
      
    </form>

  

    <a href="<?php echo $helper->url("Usuarios","resetear_clave_inicio"); ?>">Olvidaste tu Clave</a><br>
    
                          
  		</div>

   
    </div>
    
    <div class="login-box">
    
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
		 </div>			        
					        
     <?php include("view/modulos/links_js.php"); ?>
   
  </body>
</html>
