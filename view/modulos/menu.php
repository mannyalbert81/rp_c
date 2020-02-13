
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
            <i class="glyphicon glyphicon-cog"></i> <span>Administración</span>
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
        
        <li class="treeview"  style="<?php echo getcontrolador("MenuNomina",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-user"></i> <span>Recursos Humanos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("AdministracionNomina",$controladores) ?>"  >
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
                <li style="<?php echo getcontrolador("NominaAnticiposCuentas",$controladores) ?>"><a href="index.php?controller=Empleados&action=index1"><i class="fa fa-circle-o"></i> Cuentas Contables Empleados</a></li>
    		  </ul>
            </li>
            
            
            <li class="treeview"  style="<?php echo getcontrolador("ProcesosNomina",$controladores) ?>"  >
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
            	<li style="<?php echo getcontrolador("AvancesEmpleados",$controladores) ?>"><a href="index.php?controller=AvancesEmpleados&action=index"><i class="fa fa-circle-o"></i>Solicitud Avance</a></li>
             </ul>
            </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("ReportesNomina",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li style="<?php echo getcontrolador("ReporteNomina",$controladores) ?>"><a href="index.php?controller=ReporteNomina&action=index"><i class="fa fa-circle-o"></i>Reporte Nomina</a></li>
		  </ul>
        </li>
       </ul>
      </li>
        
        
         <li class="treeview"  style="<?php echo getcontrolador("MenuInventario",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-check"></i> <span>Inventario de Materiales</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("AdministracionInventario",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Administración</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("Grupos",$controladores) ?>"><a href="index.php?controller=Grupos&action=index"><i class="fa fa-circle-o"></i> Grupos</a></li>
        	    <li style="<?php echo getcontrolador("Productos",$controladores) ?>"><a href="index.php?controller=Productos&action=index"><i class="fa fa-circle-o"></i> Productos</a></li>
      			</ul>
            </li>
            
            
            <li class="treeview"  style="<?php echo getcontrolador("ProcesosInventario",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("SolicitudMateriales",$controladores) ?>"><a href="index.php?controller=SolicitudMateriales&action=index_solicitudes"><i class="fa fa-circle-o"></i> Solicitud de Materiales</a></li>
    			<li style="<?php echo getcontrolador("Productos",$controladores) ?>"><a href="index.php?controller=MovimientosInv&action=IngresoMateriales"><i class="fa fa-circle-o"></i>Ingreso de Materiales</a></li>
    			<li style="<?php echo getcontrolador("SolicitudCabeza",$controladores) ?>"><a href="index.php?controller=MovimientosInv&action=indexsalida"><i class="fa fa-circle-o"></i> Salida de Materiales</a></li> 
    			<li style="<?php echo getcontrolador("InvDocCompras",$controladores) ?>"><a href="index.php?controller=InvDocCompras&action=index"><i class="fa fa-circle-o"></i> Compras</a></li>   			
             </ul>
            </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("ReportesInventario",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li style="<?php echo getcontrolador("BuscarProducto",$controladores) ?>"><a href="index.php?controller=BuscarProducto&action=index"><i class="fa fa-circle-o"></i> Inventario de Productos</a></li>
		 	</ul>
        </li>
       </ul>
      </li>
        
       
         <li class="treeview"  style="<?php echo getcontrolador("MenuContabilidad",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-book"></i> <span>Contabilidad</span>
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
       		<li style="<?php echo getcontrolador("CoreTipoCredito",$controladores) ?>"><a href="index.php?controller=CoreTipoCredito&action=index"><i class="fa fa-circle-o"></i> Tipo Crédito</a></li>
    		<li style="<?php echo getcontrolador("CoreDiarioTipoCabeza",$controladores) ?>"><a href="index.php?controller=CoreDiarioTipoCabeza&action=index"><i class="fa fa-circle-o"></i> Diarios Tipo</a></li>
    		<li style="<?php echo getcontrolador("PlanCuentas",$controladores) ?>"><a href="index.php?controller=PlanCuentas&action=indexAdmin"><i class="fa fa-circle-o"></i> Plan cuentas</a></li>
    		<li style="<?php echo getcontrolador("CoreDiarioTipo",$controladores) ?>"><a href="index.php?controller=CoreDiarioTipo&action=index"><i class="fa fa-circle-o"></i> Diario Tipo</a></li>
 		    <li style="<?php echo getcontrolador("Periodo",$controladores) ?>"><a href="index.php?controller=Periodo&action=index"><i class="fa fa-circle-o"></i> Periodo </a></li>
      	    <li style="<?php echo getcontrolador("Presupuestos",$controladores) ?>"><a href="index.php?controller=Presupuestos&action=index"><i class="fa fa-circle-o"></i> Presupuestos </a></li>
      	   
      	  
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
      		<li style="<?php echo getcontrolador("ComprobanteContable",$controladores) ?>"><a href="index.php?controller=ComprobanteContable&action=index"><i class="fa fa-circle-o"></i>Diario Contables</a></li>
     		<li style="<?php echo getcontrolador("ProcesosMayorizacion",$controladores) ?>"><a href="index.php?controller=ProcesosMayorizacion&action=index"><i class="fa fa-circle-o"></i> Procesos Mensuales</a></li>
     		<li style="<?php echo getcontrolador("PagoNomina",$controladores) ?>"><a href="index.php?controller=Nomina&action=index1"><i class="fa fa-circle-o"></i> Pago Nomina</a></li>
     		<li style="<?php echo getcontrolador("AperturaMes",$controladores) ?>"><a href="index.php?controller=Periodo&action=indexApertura"><i class="fa fa-circle-o"></i> Apertura Mes</a></li>
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
        	  <li style="<?php echo getcontrolador("ReporteMayor",$controladores) ?>"><a href="index.php?controller=LibroMayorAuxiliar&action=index"><i class="fa fa-circle-o"></i>Mayor Auxiliar Contable</a></li>
        	  <li style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"><a href="index.php?controller=LibroDiario&action=index"><i class="fa fa-circle-o"></i>Diario Contable</a></li>
              <li style="<?php echo getcontrolador("PlanCuentas",$controladores) ?>"><a href="index.php?controller=PlanCuentas&action=index"><i class="fa fa-circle-o"></i>Plan Cuentas</a></li>
              <li style="<?php echo getcontrolador("BalanceComprobacion",$controladores) ?>"><a href="index.php?controller=BalanceComprobacion&action=index"><i class="fa fa-circle-o"></i>Balance Comprobación</a></li>
              <li style="<?php echo getcontrolador("ReporteMovimientos",$controladores) ?>"><a href="index.php?controller=MovimientosContable&action=index"><i class="fa fa-circle-o"></i>Movimientos Contables</a></li>
      	
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
     
      
        <li class="treeview"  style="<?php echo getcontrolador("ActivosFijos",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-blackboard"></i> <span>Activos Fijos</span>
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
      
      <li class="treeview"  style="<?php echo getcontrolador("MenuRecaudaciones",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-usd"></i> <span>Recaudaciones</span>
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
               </ul>
            </li>
            
            
            <li class="treeview"  style="<?php echo getcontrolador("ProcesosRecaudaciones",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("GenArchRecaudacion",$controladores) ?>"><a href="index.php?controller=Recaudacion&action=index"><i class="fa fa-circle-o"></i> Arc. Entidad Patronal</a></li>                
                <li style="<?php echo getcontrolador("CargaRecaudaciones",$controladores) ?>"><a href="index.php?controller=CargaRecaudaciones&action=index"><i class="fa fa-circle-o"></i> Carga Recaudaciones</a></li>   
                <li style="<?php echo getcontrolador("SolicitudAportes",$controladores) ?>"><a href="index.php?controller=SolicitudAportes&action=index"><i class="fa fa-circle-o"></i> Solicitud Aportes</a></li>             
             
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
      
      <li class="treeview"  style="<?php echo getcontrolador("MenuTesoreria",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-piggy-bank"></i> <span>Tesoreria</span>
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
                <li style="<?php echo getcontrolador("FormasPago",$controladores) ?>"><a href="index.php?controller=FormasPago&action=index"><i class="fa fa-circle-o"></i> Formas Pago </a></li>
                <li style="<?php echo getcontrolador("TipoDocumento",$controladores) ?>"><a href="index.php?controller=TipoDocumento&action=index"><i class="fa fa-circle-o"></i> Tipo Documento </a></li>
                <li style="<?php echo getcontrolador("ImpuestosCxP",$controladores) ?>"><a href="index.php?controller=Impuestos&action=index"><i class="fa fa-circle-o"></i> Impuestos CxP </a></li>
        	    
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
              	<li style="<?php echo getcontrolador("IngresoCuentasPagar",$controladores) ?>"><a href="index.php?controller=CuentasPagar&action=CuentasPagarIndex"><i class="fa fa-circle-o"></i> Ingreso Cuentas Pagar</a></li>
              	<li style="<?php echo getcontrolador("PagosManuales",$controladores) ?>"><a href="index.php?controller=Pagos&action=Index"><i class="fa fa-circle-o"></i> Pagos Manuales</a></li>
              	<li style="<?php echo getcontrolador("compras",$controladores) ?>"><a href="index.php?controller=Compras&action=Index"><i class="fa fa-circle-o"></i> Compras</a></li>
    			
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
          	<li style="<?php echo getcontrolador("ReportesTesoreria",$controladores) ?>"><a href="index.php?controller=CuentasPagar&action=ReporteIndex"><i class="fa fa-circle-o"></i> Consultar Cuentas Pagar</a></li>
            <li style="<?php echo getcontrolador("ReportesTesoreria",$controladores) ?>"><a href="index.php?controller=GenerarCheque&action=reporteCheques"><i class="fa fa-circle-o"></i> Consultar Cheques</a></li>
            <li style="<?php echo getcontrolador("Retencion",$controladores) ?>"><a href="index.php?controller=Retencion&action=index"><i class="fa fa-circle-o"></i> Generar Retención</a></li>
		 
		  </ul>
        </li>
        <li class="treeview"  style="<?php echo getcontrolador("AdministracionTesoreria",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Bancos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
       
            </li>
       </ul>
      </li>
            <li class="treeview"  style="<?php echo getcontrolador("MenuCore",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-briefcase"></i> <span>Crédito</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

         <li class="treeview"  style="<?php echo getcontrolador("AdministracionCore",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          	  <li style="<?php echo getcontrolador("ContribucionCategoria",$controladores) ?>"><a href="index.php?controller=ContribucionCategoria&action=index"><i class="fa fa-circle-o"></i>Contribucion Categoria</a></li>
       	  		<li style="<?php echo getcontrolador("Estatus",$controladores) ?>"><a href="index.php?controller=Estatus&action=index"><i class="fa fa-circle-o"></i>Estatus</a></li>
       	  		<li style="<?php echo getcontrolador("ContribucionTipo",$controladores) ?>"><a href="index.php?controller=ContribucionTipo&action=index"><i class="fa fa-circle-o"></i>Contribucion Tipo</a></li>
     			<li style="<?php echo getcontrolador("Participes",$controladores) ?>"><a href="index.php?controller=Participes&action=index"><i class="fa fa-circle-o"></i>Participes</a></li>
				<li style="<?php echo getcontrolador("CoreEntidadPatronal",$controladores) ?>"><a href="index.php?controller=CoreEntidadPatronal&action=index"><i class="fa fa-circle-o"></i>Entidad Patronal</a></li>
				<li style="<?php echo getcontrolador("Bancos",$controladores) ?>"><a href="index.php?controller=Bancos&action=index_cuentas"><i class="fa fa-circle-o"></i>Tipo Cuentas</a></li>
    			<li style="<?php echo getcontrolador("SolicitudPrestamo",$controladores) ?>"><a href="index.php?controller=SolicitudPrestamo&action=index5"><i class="fa fa-circle-o"></i>Solicitudes</a></li>
    		    <li style="<?php echo getcontrolador("GarantiaCredito",$controladores) ?>"><a href="index.php?controller=GarantiaCredito&action=index"><i class="fa fa-circle-o"></i>Garantia Credito</a></li>
    		    <li style="<?php echo getcontrolador("TipoCredito",$controladores) ?>"><a href="index.php?controller=TipoCredito&action=index"><i class="fa fa-circle-o"></i>Tipo Crédito</a></li>
    
           </ul>           	 
        </li>
        <li class="treeview"  style="<?php echo getcontrolador("ProcesosCore",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          	  <li style="<?php echo getcontrolador("BuscarParticipes",$controladores) ?>"><a href="index.php?controller=BuscarParticipes&action=index"><i class="fa fa-circle-o"></i> Buscar Participes</a></li>
          	  <li style="<?php echo getcontrolador("BuscarParticipesCesantes",$controladores) ?>"><a href="index.php?controller=BuscarParticipesCesantes&action=index"><i class="fa fa-circle-o"></i> Buscar Participes Cesantes</a></li>
              <li style="<?php echo getcontrolador("CoreInformacionParticipes",$controladores) ?>"><a href="index.php?controller=CoreInformacionParticipes&action=index"><i class="fa fa-circle-o"></i>Consultar Información Participes</a></li>
              <li style="<?php echo getcontrolador("AnalisisCreditos",$controladores) ?>"><a href="index.php?controller=AnalisisCreditos&action=index"><i class="fa fa-circle-o"></i>Análisis Crédito</a></li>
              <li style="<?php echo getcontrolador("SimulacionCreditos",$controladores) ?>"><a href="index.php?controller=SimulacionCreditos&action=index"><i class="fa fa-circle-o"></i>Simulación Crédito</a></li>
              <li style="<?php echo getcontrolador("RevisionCreditos",$controladores) ?>"><a href="index.php?controller=RevisionCreditos&action=index"><i class="fa fa-circle-o"></i>Revisión de Crédito</a></li>
              <li style="<?php echo getcontrolador("AcuerdoPago",$controladores) ?>"><a href="index.php?controller=AcuerdoPagos&action=index"><i class="fa fa-circle-o"></i>Acuerdos de Pago</a></li>      
		  </ul>
        </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("ReportesCore",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Reporte</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <li style="<?php echo getcontrolador("CoreFirmasParticipe",$controladores) ?>"><a href="index.php?controller=CoreFirmasParticipe&action=index"><i class="fa fa-circle-o"></i>Firmas</a></li>
          <li style="<?php echo getcontrolador("ReporteCierreMes",$controladores) ?>"><a href="index.php?controller=ReporteCierreMes&action=index5"><i class="fa fa-circle-o"></i>Cierre Mes</a></li>
        
            </ul>
        </li>
        </ul>
     </li>
      <li class="treeview"  style="<?php echo getcontrolador("MenuTributario",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-th-list"></i> <span>Tributario</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("AdministracionTributario",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Administración</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("DeclaracionGastos",$controladores) ?>"><a href="index.php?controller=DeclaracionGastos&action=index"><i class="fa fa-circle-o"></i> Formulario Gastos </a></li>
    		  </ul>
            </li>
            
            
            <li class="treeview"  style="<?php echo getcontrolador("ProcesosTributario",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
              	 <li style="<?php echo getcontrolador("TributarioGeneraAts",$controladores) ?>"><a href="index.php?controller=TributarioGeneraAts&action=index"><i class="fa fa-circle-o"></i> Generar ATS Compras</a></li>
             </ul>
            </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("ReportesTributario",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder-open-o"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           <li style="<?php echo getcontrolador("Retencion",$controladores) ?>"><a href="index.php?controller=Retencion&action=index"><i class="fa fa-circle-o"></i> Generar Retención</a></li>
		  </ul>
        </li>
       </ul>
      </li>
         <li class="treeview"  style="<?php echo getcontrolador("MenuInformacion",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-info-sign"></i> <span>Informacion BIESS / SB</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("SBSInformacion",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>SB</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
             <ul class="treeview-menu">
               <li class="treeview"  style="<?php echo getcontrolador("AdministracionInformacion",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Reporte</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("B17",$controladores) ?>"><a href="index.php?controller=B17&action=index"><i class="fa fa-circle-o"></i>B17</a></li>
              </ul>
            </ul>
              
              
            </li>
            
            
            
             <li class="treeview"  style="<?php echo getcontrolador("BIESSInformacion",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>BIESS</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
             <ul class="treeview-menu">
               <li class="treeview"  style="<?php echo getcontrolador("AdministracionInformacion",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Reporte</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li style="<?php echo getcontrolador("EstructurasBiess",$controladores) ?>"><a href="index.php?controller=EstructurasBiess&action=index"><i class="fa fa-circle-o"></i>G41</a></li>
                <li style="<?php echo getcontrolador("EstructurasBiess",$controladores) ?>"><a href="index.php?controller=EstructurasBiess&action=index2"><i class="fa fa-circle-o"></i>G42</a></li>
              </ul>
            </ul>
              
              
            </li>
            
       </ul>
       
 
       
      </li>
      
   
      
<li class="treeview"  style="<?php echo getcontrolador("MenuGestionDoc",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-file"></i> <span>Gestion Documental</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("AdministracionGestionDoc",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Administración</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                
    		  </ul>
            </li>
            
            
            <li class="treeview"  style="<?php echo getcontrolador("ProcesosGestionDoc",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
           <li style="<?php echo getcontrolador("Indexacion",$controladores) ?>"><a href="index.php?controller=Indexacion&action=index"><i class="fa fa-circle-o"></i>Indexación de Documentos</a></li>
			    <li style="<?php echo getcontrolador("Documentos",$controladores) ?>"><a href="index.php?controller=Documentos&action=index5"><i class="fa fa-circle-o"></i>Búsqueda de Documentos</a></li>
	        	
             </ul>
            </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("ReporteGestionDoc",$controladores) ?>"  >
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
      
           <li class="treeview"  style="<?php echo getcontrolador("MenuJuridico",$controladores) ?>"  >
          <a href="#">
            <i class="fa fa-folder"></i> <span>Jurídico</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("AdministracionJuridico",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Administración</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                
    		  </ul>
            </li>
            
            
            <li class="treeview"  style="<?php echo getcontrolador("ProcesosJuridico",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
         <li style="<?php echo getcontrolador("MatrizJuicios",$controladores) ?>"><a href="index.php?controller=MatrizJuicios&action=index5"><i class="fa fa-circle-o"></i>Matriz de Juicios</a></li>
		         	
             </ul>
            </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("ReporteJuridico",$controladores) ?>"  >
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
          
      
      <li class="treeview"  style="<?php echo getcontrolador("MenuTurnos",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-hand-up"></i> <span>Trámites</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("AdministracionTurnos",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Administración</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                
    		  </ul>
            </li>
            
            
            <li class="treeview"  style="<?php echo getcontrolador("ProcesosTurnos",$controladores) ?>"  >
              <a href="#">
                <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
         <li style="<?php echo getcontrolador("Turnos",$controladores) ?>"><a href="index.php?controller=Turnos&action=index5"><i class="fa fa-circle-o"></i>Turnos</a></li>
	         </ul>
            </li>
        
        <li class="treeview"  style="<?php echo getcontrolador("ReporteTurnos",$controladores) ?>"  >
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
      
 
      

    </ul>
    