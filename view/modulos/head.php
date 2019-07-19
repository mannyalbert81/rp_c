    
    
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
       
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
         
         <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning"></span>
            </a>
            <ul class="dropdown-menu">
            </ul>
           
        </li>
        <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger"></span>
            </a>
            <ul class="dropdown-menu">
            </ul>
		</li>
         
          <?php  
			     $status = session_status();
			     if  (isset( $_SESSION['nombre_usuarios'] ))  {  
              ?>
              	
              	
              	
              	
              	  <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="view/Administracion/DevuelveImagenView.php?id_valor=<?php echo $_SESSION['id_usuarios']; ?>&id_nombre=id_usuarios&tabla=usuarios&campo=fotografia_usuarios" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['nombre_usuarios'];?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="view/Administracion/DevuelveImagenView.php?id_valor=<?php echo $_SESSION['id_usuarios']; ?>&id_nombre=id_usuarios&tabla=usuarios&campo=fotografia_usuarios" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['nombre_usuarios'];?> - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="index.php?controller=Usuarios&action=Actualiza" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="index.php?controller=Usuarios&action=cerrar_sesion" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
              
             
              <?php }?>
         
         
          
         
        </ul>
      </div>
    </nav>
    
    
    
    
    
    
    
        
        
  






  