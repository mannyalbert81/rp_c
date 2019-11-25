<?php

class LibroMayorAuxiliarController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
	    $proveedores=new ProveedoresModel();
					//Conseguimos todos los usuarios
	    $resultSet=$proveedores->getAll("id_proveedores");
				
		$resultEdit = "";
		
	
		
		session_start();
        
	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "ReporteMayorAuxiliar";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $proveedores->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_proveedores"])   )
				{

					$nombre_controladores = "Proveedores";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $proveedores->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_proveedores = $_GET["id_proveedores"];
						$columnas = " id_proveedores, nombre_proveedores, identificacion_proveedores, contactos_proveedores, direccion_proveedores, telefono_proveedores, email_proveedores, fecha_nacimiento_proveedores ";
						$tablas   = "proveedores";
						$where    = "id_proveedores = '$_id_proveedores' "; 
						$id       = "nombre_proveedores";
							
						$resultEdit = $proveedores->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
					    $this->view_Contable("Error",array(
								"resultado"=>"No tiene Permisos de Editar Proveedores"
					
						));
					
					
					}
					
				}
		
				
				$this->view_Contable("LibroMayorAuxiliar",array(
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit
			
				));
		
				
				
			}
			else
			{
			    $this->view_Contable("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Mayor Auxuliar"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	
	
	public function paginate_grupos($reload, $page, $tpages, $adjacents,$funcion='') {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>1</a></li>";
	    }
	    // interval
	    if($page>($adjacents+2)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // pages
	    
	    $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	    $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	    for($i=$pmin; $i<=$pmax; $i++) {
	        if($i==$page) {
	            $out.= "<li class='active'><a>$i</a></li>";
	        }else if($i==1) {
	            $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='$funcion(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='$funcion($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}

	
	public function ins_proveedor(){
	    
	    session_start();
	    $proveedores=new ProveedoresModel();
	    
	    $nombre_controladores = "Proveedores";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $proveedores->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer))
	    {  
	        
	        $resultado = null;
	        $proveedores=new ProveedoresModel();
	        
	        if (isset ($_POST["nombre_proveedores"])   )
	        {
	            $_nombre_proveedores = $_POST["nombre_proveedores"];
	            $_identificacion_proveedores = $_POST["identificacion_proveedores"];
	            $_contactos_proveedores = $_POST["contactos_proveedores"];
	            $_direccion_proveedores = $_POST["direccion_proveedores"];
	            $_telefono_proveedores = $_POST["telefono_proveedores"];
	            $_email_proveedores = $_POST["email_proveedores"];
	              
                $funcion = "ins_proveedores";
                $parametros = " '$_nombre_proveedores','$_identificacion_proveedores','$_contactos_proveedores','$_direccion_proveedores','$_telefono_proveedores','$_email_proveedores'";
                $proveedores->setFuncion($funcion);
                $proveedores->setParametros($parametros);
                $resultado=$proveedores->llamafuncion();
	           
                $respuesta=0;
                
                //print_r($resultado);
                
                if(!empty($resultado) && count($resultado)>0)
                {
                    foreach ($resultado[0] as $k => $v)
                        $respuesta=$v;
                }
                
                if($respuesta==0){
                    echo json_encode(array('success'=>$respuesta,'mensaje'=>'Error al insertar proveedores'));
                    
                }else{
                    echo json_encode(array('success'=>$respuesta,'mensaje'=>'Proveedor ingresado con exito'));
                }
                
             }
	       
	        
	    }
	    else
	    {
	        echo json_encode(array('success'=>0,'mensaje'=>'Error de permisos'));
	    }
	    
	}
	
	
	
	public function AutocompleteCodigo(){
	    
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    
	    $usuarios = new UsuariosModel();
	    $plan_cuentas = new PlanCuentasModel();
	    
	    if(isset($_GET['term'])){
	        
	        $codigo_plan_cuentas = $_GET['term'];
	        
	        $columnas ="plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	        $where ="plan_cuentas.codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5') AND mayor_auxiliar = 'TRUE' ";
	        $id ="plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	              
	            foreach ($resultSet as $res){
	                    
	                    $_cuenta = new stdClass;
	                    $_cuenta->id=$res->id_plan_cuentas;
	                    $_cuenta->value=$res->codigo_plan_cuentas;
	                    $_cuenta->label=$res->codigo_plan_cuentas;
	                    $_cuenta->nombre=$res->nombre_plan_cuentas;
	                    
	                    $respuesta[] = $_cuenta;
	                }
	                
	                echo json_encode($respuesta);
	           
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }else{
	        
	        $codigo_plan_cuentas = (isset($_POST['term']))?$_POST['term']:'';
	        
	        $columnas ="plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	        $where ="plan_cuentas.codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5') AND mayor_auxiliar = 'TRUE' ";
	        $id ="plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	            
	            foreach ($resultSet as $res){
	                
	                $_cuenta = new stdClass;
	                $_cuenta->id=$res->id_plan_cuentas;
	                $_cuenta->value=$res->codigo_plan_cuentas;
	                $_cuenta->label=$res->codigo_plan_cuentas;
	                $_cuenta->nombre_cuenta=$res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cuenta;
	            }
	            
	            echo json_encode($respuesta);
	            
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }
	    
	    
	    
	}
	
	public function AutocompleteNombre(){
	    
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    
	    $plan_cuentas = new PlanCuentasModel();
	    
	    if(isset($_GET['term'])){
	        
	        $nombre_plan_cuentas = $_GET['term'];
	        
	        $columnas ="plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	        $where ="plan_cuentas.nombre_plan_cuentas LIKE '$nombre_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
	        $id ="plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	            
	            foreach ($resultSet as $res){
	                
	                $_cuenta = new stdClass;
	                $_cuenta->id=$res->id_plan_cuentas;
	                $_cuenta->value=$res->nombre_plan_cuentas;
	                $_cuenta->label=$res->nombre_plan_cuentas;
	                $_cuenta->nombre=$res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cuenta;
	            }
	            
	            echo json_encode($respuesta);
	            
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }else{
	        
	        $nombre_plan_cuentas = (isset($_POST['term']))?$_POST['term']:'';
	        
	        $columnas ="plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	        $where ="plan_cuentas.nombre_plan_cuentas LIKE '$nombre_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
	        $id ="plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	            
	            foreach ($resultSet as $res){
	                
	                $_cuenta = new stdClass;
	                $_cuenta->id=$res->id_plan_cuentas;
	                $_cuenta->value=$res->codigo_plan_cuentas;
	                $_cuenta->label=$res->codigo_plan_cuentas;
	                $_cuenta->nombre_cuenta=$res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cuenta;
	            }
	            
	            echo json_encode($respuesta);
	            
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }
	    
	    
	    
	}
	
	
	
	public function mayorContableAuxiliar(){
	

		session_start();
		 
		$mayor = new MayorModel();
		

		
		
		//variables
		$_id_creditos = "";
		$_numero_creditos = "";
		$_id_participes = "";
		$_monto_otorgado_creditos = "";
		$_apellido_participes ="";
		$_nombre_participes = "";
		$_cedula_participes = "";
		$_nombre_estado_creditos = "";
	
		$_saldo_actual_creditos = "";
	
		$_fecha_concesion_creditos = "";
		$_plazo_creditos	= "";
		
		
		
		$_codigo_cuenta = (isset($_REQUEST['codigo_cuenta'])&& $_REQUEST['codigo_cuenta'] !=NULL)?$_REQUEST['codigo_cuenta']:'';
		
		$columnas = " core_creditos.id_creditos,	
					core_creditos.numero_creditos, 
					  core_creditos.id_participes, 
					  core_creditos.id_creditos_productos, 
					  core_creditos.monto_otorgado_creditos, 
					  con_cuentas_auxiliar_mayor_relacion.nombre_tabla, 
					  plan_cuentas.codigo_plan_cuentas, 
					  plan_cuentas.nombre_plan_cuentas, 
					  core_participes.apellido_participes, 
					  core_participes.nombre_participes, 
					  core_participes.cedula_participes, 
					  core_estado_creditos.nombre_estado_creditos, 
					   
					  core_estado_creditos.id_estado_creditos, 
					  core_creditos.saldo_actual_creditos, 
					  core_creditos.fecha_concesion_creditos, 
					  core_creditos.id_estado_creditos, 
					  core_creditos.plazo_creditos, 
					  core_creditos.monto_neto_entregado_creditos, 
					  core_creditos.numero_solicitud_creditos, 
					  core_creditos.id_tipo_creditos, 
					  core_creditos.interes_creditos, 
					  core_creditos.impuesto_exento_seguro_creditos, 
					  core_creditos.base_calculo_participes_creditos, 
					  core_creditos.cuota_creditos, 
					  core_creditos.id_forma_pago, 
					  core_creditos.id_ccomprobantes, 
					  core_creditos.incluido_reporte_creditos";
		$tablas = " public.core_creditos, 
					  public.con_cuentas_auxiliar_mayor_relacion, 
					  public.plan_cuentas, 
					  public.core_participes, 
					  public.core_estado_creditos";
		$where = " con_cuentas_auxiliar_mayor_relacion.id_operacion = core_creditos.id_tipo_creditos AND
  plan_cuentas.id_plan_cuentas = con_cuentas_auxiliar_mayor_relacion.id_plan_cuentas AND
  core_participes.id_participes = core_creditos.id_participes AND
  core_estado_creditos.id_estado_creditos = core_creditos.id_estado_creditos
  AND core_creditos.id_estado_creditos = 4 
  AND plan_cuentas.codigo_plan_cuentas = '$_codigo_cuenta'  ";
		$id = " core_creditos.id_creditos";
		
		
		$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
		$search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
		
		

		
		if($action == 'ajax')
		{
			//estado_usuario
			//$wherecatalogo = "tabla_catalogo='usuarios' AND columna_catalogo='estado_usuarios'";
			//$resultCatalogo = $catalogo->getCondiciones('valor_catalogo,nombre_catalogo' ,'public.catalogo' , $wherecatalogo , 'tabla_catalogo');
			
			$resultSet=$mayor->getCondiciones($columnas, $tablas, $where, $id);
				
		
			if(!empty($search)){
		
		
				$where1=" AND (core_participes.cedula_participes LIKE '".$search."%' OR core_creditos.numero_creditos LIKE '".$search."%' OR core_participes.apellido_participes LIKE '".$search."%' OR core_participes.nombre_participes LIKE '".$search."%' )";
		
				$where_to=$where.$where1;
			}else{
		
				$where_to=$where;
		
			}
		
			$html="";
			$resultSet=$mayor->getCantidad("core_creditos.id_creditos", $tablas, $where_to);
			$cantidadResult=(int)$resultSet[0]->total;
		
			$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		
			$per_page = 10; //la cantidad de registros que desea mostrar
			$adjacents  = 9; //brecha entre páginas después de varios adyacentes
			$offset = ($page - 1) * $per_page;
		
			$limit = " LIMIT   '$per_page' OFFSET '$offset'";
		
			$resultSet=$mayor->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
			$count_query   = $cantidadResult;
			$total_pages = ceil($cantidadResult/$per_page);
		
		
			if($cantidadResult>0)
			{
		
				$html.='<div class="pull-left" style="margin-left:15px;">';
				$html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
				$html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
				$html.='</div>';
				$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
				$html.='<section style="height:425px; overflow-y:scroll;">';
				$html.= "<table id='tabla_usuarios' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
				$html.= "<thead>";
				$html.= "<tr>";
				$html.='<th style="text-align: left;  font-size: 12px;"></th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Crédito</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Fecha Concedido</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Plazo</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Saldo Capital</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
		
				$html.='</tr>';
				$html.='</thead>';
				$html.='<tbody>';
				 
				 
				$i=0;
		
				foreach ($resultSet as $res)
				{
					$_id_creditos = $res->id_creditos;
					
					
					$_saldo_actual_creditos = $this->devuelve_saldo_capital($_id_creditos); ////DEVOLVER DESDE FUNCION  $_balance_tabla_amortizacion;
					$i++;
					$html.='<tr>';
				
					$html.='<td style="font-size: 11px;">'.$i.'</td>';
					$html.='<td style="font-size: 11px;">'.$_codigo_cuenta.$res->cedula_participes.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->apellido_participes. " " . $res->nombre_participes . '</td>';
					$html.='<td style="font-size: 11px;">'.$res->numero_creditos.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->fecha_concesion_creditos.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->plazo_creditos.'</td>';
					$html.='<td style="font-size: 11px;">'.$_saldo_actual_creditos.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->nombre_estado_creditos.'</td>';
					
					
					
					
								
					
					 
					$html.='</tr>';
				}
		
		
		
				$html.='</tbody>';
				$html.='</table>';
				$html.='</section></div>';
				$html.='<div class="table-pagination pull-right">';
				$html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents , "").'';
				$html.='</div>';
		
		
				 
			}else{
				$html.='<div class="col-lg-6 col-md-6 col-xs-12">';
				$html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
				$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				$html.='<h4>Aviso!!!</h4> <b>Actualmente no hay usuarios registrados...</b>';
				$html.='</div>';
				$html.='</div>';
			}
			 
			 
			echo $html;
			die();
		
		}
		

			
	}	
	
	
	
	public function paginate($reload, $page, $tpages, $adjacents,$funcion='') {
		 
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
		 
		// previous label
		 
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page-1).")'>$prevlabel</a></span></li>";
			 
		}
		 
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>1</a></li>";
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= "<li><a>...</a></li>";
		}
		 
		// pages
		 
		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= "<li class='active'><a>$i</a></li>";
			}else if($i==1) {
				$out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='$funcion(".$i.")'>$i</a></li>";
			}
		}
		 
		// interval
		 
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
		 
		// last
		 
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='$funcion($tpages)'>$tpages</a></li>";
		}
		 
		// next
		 
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
		 
		$out.= "</ul>";
		return $out;
	}
	
	
	
	
	
	public function devuelve_saldo_capital($id_creditos){
		 
		//session_start();
		$creditos=new CoreCreditoModel();
		$monto=0;
		$total_pagos_capital=0;
		$saldo_credito=0;
		 
		 
		// consigo monto del credito
		$columnas="c.monto_neto_entregado_creditos as monto";
		$tablas="core_creditos c";
		$where="c.id_creditos='$id_creditos' and c.id_estatus=1";
		$id="c.id_creditos";
		$resultCred=$creditos->getCondiciones($columnas, $tablas, $where, $id);
		 
		if(!empty($resultCred)){
			 
			$monto=$resultCred[0]->monto;
			 
			 
			// verifico pagos de capital
			 
			$columnas_pag="coalesce(sum(ctd.valor_transaccion_detalle),0) as total_pagos_capital";
			$tablas_pag="core_transacciones ct inner join core_transacciones_detalle ctd on ct.id_transacciones=ctd.id_transacciones
                        inner join core_tabla_amortizacion_pagos ctap on ctd.id_tabla_amortizacion_pago=ctap.id_tabla_amortizacion_pagos
                        inner join core_tabla_amortizacion_parametrizacion ctapara on ctap.id_tabla_amortizacion_parametrizacion=ctapara.id_tabla_amortizacion_parametrizacion";
			$where_pag="ct.id_creditos='$id_creditos'  and ct.id_status=1 and ctapara.tipo_tabla_amortizacion_parametrizacion=0";
			 
			$resultPagos=$creditos->getCondicionesSinOrden($columnas_pag, $tablas_pag, $where_pag, "");
			 
			 
			if(!empty($resultPagos)){
				 
				 
				$total_pagos_capital=$resultPagos[0]->total_pagos_capital;
				 
				 
			}
			 
			 
		}
		 
		 
		$saldo_credito= $monto-$total_pagos_capital;
	
		return $saldo_credito;
		 
		 
		 
	}
	 
	
	
	
	
}
?>