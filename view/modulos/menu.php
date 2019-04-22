
<?php 
$controladores=$_SESSION['controladores'];
 function getcontrolador($controlador,$controladores){
 	$display="display:none";
 	
 	if (!empty($controladores))
 	{
 	foreach ($controladores as $res)
 	{
 		if($res->nombre_controladores==$controlador)
 		{
 			$display= "display:block";
 			break;
 			
 		}
 	}
 	}
 	
 	return $display;
 }
 
?>



   <ul class="sidebar-menu" data-widget="tree">
       <li class="header">MAIN NAVIGATION</li>
         <li class="treeview"  style="<?php echo getcontrolador("MenuAdministracion",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder"></i> <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li style="<?php echo getcontrolador("Usuarios",$controladores) ?>"><a href="index.php?controller=Usuarios&action=index"><i class="fa fa-circle-o"></i> Usuarios</a></li>
            <li style="<?php echo getcontrolador("Controladores",$controladores) ?>"><a href="index.php?controller=Controladores&action=index"><i class="fa fa-circle-o"></i> Controladores</a></li>
            <li style="<?php echo getcontrolador("Roles",$controladores) ?>"><a href="index.php?controller=Roles&action=index"><i class="fa fa-circle-o"></i> Roles de Usuario</a></li>
            <li style="<?php echo getcontrolador("PermisosRoles",$controladores) ?>"><a href="index.php?controller=PermisosRoles&action=index"><i class="fa fa-circle-o"></i> Permisos Roles</a></li>
            <li style="<?php echo getcontrolador("Estado",$controladores) ?>"><a href="index.php?controller=Estado&action=index"><i class="fa fa-circle-o"></i> Estado</a></li>
            <li style="<?php echo getcontrolador("Privilegios",$controladores) ?>"><a href="index.php?controller=Privilegios&action=index"><i class="fa fa-circle-o"></i> Privilegios</a></li>
            <li style="<?php echo getcontrolador("Actividades",$controladores) ?>"><a href="index.php?controller=Actividades&action=index"><i class="fa fa-circle-o"></i> Actividades</a></li>
            <li style="<?php echo getcontrolador("DepartamentosAdmin",$controladores) ?>"><a href="index.php?controller=DepartamentosAdmin&action=index"><i class="fa fa-circle-o"></i>Departamentos</a></li>
            <li style="<?php echo getcontrolador("Estados",$controladores) ?>"><a href="index.php?controller=Estados&action=index"><i class="fa fa-circle-o"></i>Estados</a></li>
           
           </ul>
        </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("MenuInventario",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder"></i> <span>Nomina</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Administración</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("Departamentos",$controladores) ?>"><a href="index.php?controller=Departamentos&action=index"><i class="fa fa-circle-o"></i> Departamentos</a></li>
                <li style="<?php echo getcontrolador("Empleados",$controladores) ?>"><a href="index.php?controller=Empleados&action=index"><i class="fa fa-circle-o"></i> Empleados</a></li>
                <li style="<?php echo getcontrolador("Horarios",$controladores) ?>"><a href="index.php?controller=Horarios&action=index"><i class="fa fa-circle-o"></i> Horarios</a></li>
                <li style="<?php echo getcontrolador("CuentasEmpleados",$controladores) ?>"><a href="index.php?controller=CuentasEmpleados&action=index"><i class="fa fa-circle-o"></i> Cuentas Bancarias</a></li>
    		  </ul>
            </li>
            
            
            <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("Marcaciones",$controladores) ?>"><a href="index.php?controller=Marcaciones&action=index"><i class="fa fa-circle-o"></i> Marcaciones</a></li>
                <li style="<?php echo getcontrolador("PermisosEmpleados",$controladores) ?>"><a href="index.php?controller=PermisosEmpleados&action=index"><i class="fa fa-circle-o"></i>Solicitud Permiso</a></li>
				<li style="<?php echo getcontrolador("VacacionesEmpleados",$controladores) ?>"><a href="index.php?controller=VacacionesEmpleados&action=index"><i class="fa fa-circle-o"></i>Solicitud Vacaciones</a></li>             
            	<li style="<?php echo getcontrolador("HorasExtrasEmpleados",$controladores) ?>"><a href="index.php?controller=HorasExtrasEmpleados&action=index"><i class="fa fa-circle-o"></i>Solicitud Horas Extra</a></li>
             </ul>
            </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            
		  </ul>
        </li>
       </ul>
      </li>
        
        
         <li class="treeview"  style="<?php echo getcontrolador("MenuInventario",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder"></i> <span>Inventario de Materiales</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Administración</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("Grupos",$controladores) ?>"><a href="index.php?controller=Grupos&action=index"><i class="fa fa-circle-o"></i> Grupos</a></li>
        	    <li style="<?php echo getcontrolador("Productos",$controladores) ?>"><a href="index.php?controller=Productos&action=index"><i class="fa fa-circle-o"></i> Productos</a></li>
      			<li style="<?php echo getcontrolador("Bodegas",$controladores) ?>"><a href="index.php?controller=Bodegas&action=index"><i class="fa fa-circle-o"></i> Bodegas</a></li>
    		  </ul>
            </li>
            
            
            <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("SolicitudCabeza",$controladores) ?>"><a href="index.php?controller=MovimientosInv&action=index_solicitudes"><i class="fa fa-circle-o"></i> Solicitud de Materiales</a></li>
    			<li style="<?php echo getcontrolador("Productos",$controladores) ?>"><a href="index.php?controller=MovimientosInv&action=IngresoMateriales"><i class="fa fa-circle-o"></i>Ingreso de Materiales</a></li>
    			<li style="<?php echo getcontrolador("SolicitudCabeza",$controladores) ?>"><a href="index.php?controller=MovimientosInv&action=indexsalida"><i class="fa fa-circle-o"></i> Salida de Materiales</a></li>
             </ul>
            </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li style="<?php echo getcontrolador("BuscarProducto",$controladores) ?>"><a href="index.php?controller=BuscarProducto&action=index"><i class="fa fa-circle-o"></i> Consultar Productos</a></li>
		 
		  </ul>
        </li>
       </ul>
      </li>
        
       
         <li class="treeview"  style="<?php echo getcontrolador("MenuContabilidad",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder"></i> <span>Contabilidad</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
      
         <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li style="<?php echo getcontrolador("TipoComprobantes",$controladores) ?>"><a href="index.php?controller=TipoComprobantes&action=index"><i class="fa fa-circle-o"></i>Tipo Comprobantes</a></li>
            <li style="<?php echo getcontrolador("Proveedores",$controladores) ?>"><a href="index.php?controller=Proveedores&action=index"><i class="fa fa-circle-o"></i> Proveedores</a></li>
      	  </ul>
        </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
      		<li style="<?php echo getcontrolador("ComprobanteContable",$controladores) ?>"><a href="index.php?controller=ComprobanteContable&action=index"><i class="fa fa-circle-o"></i>Comprobantes Contable</a></li>
     		<li style="<?php echo getcontrolador("ComprobanteTipo",$controladores) ?>"><a href="index.php?controller=ComprobanteTipo&action=index"><i class="fa fa-circle-o"></i>Comprobantes Tipo</a></li>
    
    	  </ul>
        </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Reporte</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li style="<?php echo getcontrolador("ReporteComprobante",$controladores) ?>"><a href="index.php?controller=ReporteComprobante&action=index"><i class="fa fa-circle-o"></i>Consultar Comprobantes</a></li>
        	  <li style="<?php echo getcontrolador("ReporteMayor",$controladores) ?>"><a href="index.php?controller=LibroMayor&action=index"><i class="fa fa-circle-o"></i>Mayor Contable</a></li>
        	  <li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=LibroDiario&action=index"><i class="fa fa-circle-o"></i>Diario Contable</a></li>
              <li style="<?php echo getcontrolador("PlanCuentas",$controladores) ?>"><a href="index.php?controller=PlanCuentas&action=index"><i class="fa fa-circle-o"></i>Plan Cuentas</a></li>
          </ul>
        </li>
        
          
        </ul>
       
       <ul class="treeview-menu">
      
         <li class="treeview"  style="<?php echo getcontrolador("ProcesosContabilidad",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder"></i> <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li style="<?php echo getcontrolador("TipoComprobantes",$controladores) ?>"><a href="index.php?controller=TipoComprobantes&action=index"><i class="fa fa-circle-o"></i>Tipo Comprobantes</a></li>
              <li style="<?php echo getcontrolador("ComprobanteContable",$controladores) ?>"><a href="index.php?controller=ComprobanteContable&action=index"><i class="fa fa-circle-o"></i>Comprobantes Contable</a></li>
              <li style="<?php echo getcontrolador("ReporteComprobante",$controladores) ?>"><a href="index.php?controller=ReporteComprobante&action=index"><i class="fa fa-circle-o"></i>Consultar Comprobantes</a></li>
        	  <li style="<?php echo getcontrolador("ReporteMayor",$controladores) ?>"><a href="index.php?controller=ReporteMayor&action=index"><i class="fa fa-circle-o"></i>Consultar Mayor</a></li>
        	   <li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=LibroMayor&action=index"><i class="fa fa-circle-o"></i>Mayor Contable</a></li>
        	  <li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=LibroDiario&action=index"><i class="fa fa-circle-o"></i>Diario Contable</a></li>
              <li style="<?php echo getcontrolador("PlanCuentas",$controladores) ?>"><a href="index.php?controller=PlanCuentas&action=index"><i class="fa fa-circle-o"></i>Plan Cuentas</a></li>
        	  <li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=ActivosFijos&action=index"><i class="fa fa-circle-o"></i>Activos Fijos</a></li>
              <li style="<?php echo getcontrolador("ActivosFijosDetalle",$controladores) ?>"><a href="index.php?controller=ActivosFijosDetalle&action=index"><i class="fa fa-circle-o"></i>Detalle de Activos Fijos</a></li>
         </ul>
        </li>
        
          
            </ul>
     <ul class="treeview-menu">
       <li class="treeview"  style="<?php echo getcontrolador("ReportesContabilidad",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder"></i> <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li style="<?php echo getcontrolador("TipoComprobantes",$controladores) ?>"><a href="index.php?controller=TipoComprobantes&action=index"><i class="fa fa-circle-o"></i>Tipo Comprobantes</a></li>
              <li style="<?php echo getcontrolador("ComprobanteContable",$controladores) ?>"><a href="index.php?controller=ComprobanteContable&action=index"><i class="fa fa-circle-o"></i>Comprobantes Contable</a></li>
              <li style="<?php echo getcontrolador("ReporteComprobante",$controladores) ?>"><a href="index.php?controller=ReporteComprobante&action=index"><i class="fa fa-circle-o"></i>Consultar Comprobantes</a></li>
        	  <li style="<?php echo getcontrolador("ReporteMayor",$controladores) ?>"><a href="index.php?controller=ReporteMayor&action=index"><i class="fa fa-circle-o"></i>Consultar Mayor</a></li>
        	  <li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=LibroMayor&action=index"><i class="fa fa-circle-o"></i>Mayor Contable</a></li>
        	  <li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=LibroDiario&action=index"><i class="fa fa-circle-o"></i>Diario Contable</a></li>
              <li style="<?php echo getcontrolador("PlanCuentas",$controladores) ?>"><a href="index.php?controller=PlanCuentas&action=index"><i class="fa fa-circle-o"></i>Plan Cuentas</a></li>
        	  <li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=ActivosFijos&action=index"><i class="fa fa-circle-o"></i>Activos Fijos</a></li>
              <li style="<?php echo getcontrolador("ActivosFijosDetalle",$controladores) ?>"><a href="index.php?controller=ActivosFijosDetalle&action=index"><i class="fa fa-circle-o"></i>Detalle de Activos Fijos</a></li>
          </ul>
        </li>
       </ul>
     </li>
     
      
        <li class="treeview"  style="<?php echo getcontrolador("MenuInventario",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder"></i> <span>Activos Fijos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Administración</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                 <li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=TipoActivos&action=index"><i class="fa fa-circle-o"></i>Tipo Activos Fijos</a></li>
                 <li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=ActivosFijos&action=index1"><i class="fa fa-circle-o"></i>Ingresar Activos Fijos</a></li>
              </ul>
            </li>
            
            
            <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=ActivosFijos&action=depreciacionActivosIndex"><i class="fa fa-circle-o"></i>Depreciacion Activos</a></li>
    		  </ul>
            </li>
            
            <li class="treeview"  style="<?php echo getcontrolador("AdministracionContabilidad",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Reportes</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
              
              	<li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=ActivosFijos&action=VerDepreciacion"><i class="fa fa-circle-o"></i>Depreciacion Activos</a></li>
              
              </ul>
           </li>
        </ul>
      </li>
      
      <li class="treeview"  style="<?php echo getcontrolador("MenuTesoreria",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder"></i> <span>Tesoreria</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("AdministracionTesoreria",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Administración</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("Bancos",$controladores) ?>"><a href="index.php?controller=Bancos&action=index"><i class="fa fa-circle-o"></i> Bancos </a></li>
                <li style="<?php echo getcontrolador("FormaPago",$controladores) ?>"><a href="index.php?controller=Bancos&action=index"><i class="fa fa-circle-o"></i> Forma Pago </a></li>
        	    
    		  </ul>
            </li>
            
            
            <li class="treeview"  style="<?php echo getcontrolador("ProcesosTesoreria",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("PagosManuales",$controladores) ?>"><a href="index.php?controller=CuentasPagar&action=EntradaPagosIndex"><i class="fa fa-circle-o"></i> Pagos Manuales</a></li>
    			
             </ul>
            </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("ReportesTesoreria",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li style="<?php echo getcontrolador("BuscarProducto",$controladores) ?>"><a href="index.php?controller=BuscarProducto&action=index"><i class="fa fa-circle-o"></i> Consultar Productos</a></li>
		 
		  </ul>
        </li>
       </ul>
      </li>
      
    </ul>
    