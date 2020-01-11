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
		$html="";
		$_codigo_plan_cuentas = (isset($_REQUEST['codigo_plan_cuentas'])&& $_REQUEST['codigo_plan_cuentas'] !=NULL)?$_REQUEST['codigo_plan_cuentas']:'';
		$_codigo_sub_plan_cuentas = (isset($_REQUEST['codigo_sub_plan_cuentas'])&& $_REQUEST['codigo_sub_plan_cuentas'] !=NULL)?$_REQUEST['codigo_sub_plan_cuentas']:'';
		$_codigo_plan_cuentas_hijos = (isset($_REQUEST['codigo_plan_cuentas_hijos'])&& $_REQUEST['codigo_plan_cuentas_hijos'] !=NULL)?$_REQUEST['codigo_plan_cuentas_hijos']:'';
		$_desde_diario = (isset($_REQUEST['desde_diario'])&& $_REQUEST['desde_diario'] !=NULL)?$_REQUEST['desde_diario']:'';
		$_hasta_diario = (isset($_REQUEST['hasta_diario'])&& $_REQUEST['hasta_diario'] !=NULL)?$_REQUEST['hasta_diario']:'';
		
		
		$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
		$search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		
		if(!empty($_codigo_sub_plan_cuentas)){
		    
		    /*if($_codigo_plan_cuentas_hijos==0){
		        
		        $_codigo_plan_cuentas_hijos=$_codigo_sub_plan_cuentas;
		        
		    }*/
		    
		    
		    
		    // EMPIEZA CUENTA INDIVIDUAL
		    
		   
		    if($_codigo_plan_cuentas == '2.1'){
		        
		        // devuelve aportes
		       
		        $html.= $this->devuelve_aportes($_codigo_plan_cuentas_hijos, $_desde_diario, $_hasta_diario, $action, $search, $page);
    		        
		        echo $html;
		        die();
		        
		    }
		   
		    
		    
		    
		    
		    
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
		    
		    
		    
		    
		    
		    if($action == 'ajax')
		    {
		        
		        if(!empty($search)){
		            
		            
		            $where1=" AND (core_participes.cedula_participes LIKE '".$search."%' OR core_creditos.numero_creditos LIKE '".$search."%' OR core_participes.apellido_participes LIKE '".$search."%' OR core_participes.nombre_participes LIKE '".$search."%' )";
		            
		            $where_to=$where.$where1;
		        }else{
		            
		            $where_to=$where;
		            
		        }
		        
		        $html="";
		        $resultSet=$mayor->getCantidad("core_creditos.id_creditos", $tablas, $where_to);
		        $cantidadResult=(int)$resultSet[0]->total;
		        
		       
		        
		        $per_page = 10; //la cantidad de registros que desea mostrar
		        $adjacents  = 5; //brecha entre páginas después de varios adyacentes
		        $offset = ($page - 1) * $per_page;
		        
		        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
		        
		        $resultSet=$mayor->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
		            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents , "load_tabla").'';
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
	 
	    $creditos=new CoreCreditoModel();
	    $saldo_credito=0;
	    
	    $columnas_pag="coalesce(sum(tap.saldo_cuota_tabla_amortizacion_pagos),0) as saldo";
	    $tablas_pag="core_creditos c
                        inner join core_tabla_amortizacion at on c.id_creditos=at.id_creditos
                        inner join core_tabla_amortizacion_pagos tap on at.id_tabla_amortizacion=tap.id_tabla_amortizacion
                        inner join core_tabla_amortizacion_parametrizacion tapa on tap.id_tabla_amortizacion_parametrizacion=tapa.id_tabla_amortizacion_parametrizacion";
	    $where_pag="c.id_creditos='$id_creditos' and c.id_estatus=1 and at.id_estatus=1 and tapa.tipo_tabla_amortizacion_parametrizacion=0";
	    
	    $resultPagos=$creditos->getCondicionesSinOrden($columnas_pag, $tablas_pag, $where_pag, "");
	    
	    
	    if(!empty($resultPagos)){
	        
	        
	        $saldo_credito=$resultPagos[0]->saldo;
	        
	        
	    }
	    
	    
	    
	    return $saldo_credito;
	    
	}
	// desde aqui maycol

	public function cargaCuentas(){
	    
        $plan_cuentas= new PlanCuentasModel();
	    
	    $columnas="codigo_plan_cuentas, concat(codigo_plan_cuentas,'     ',nombre_plan_cuentas) as nombre_plan_cuentas";
	    $tabla = "plan_cuentas p";
	    $where = "p.codigo_plan_cuentas in ('2.1', '1.3')";
	    $id="codigo_plan_cuentas";
	    $resulset = $plan_cuentas->getCondiciones($columnas,$tabla,$where,$id);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	
	
	public function cargaSubCuentas(){
	    
	    $plan_cuentas= new PlanCuentasModel();
	    
	    $codigo_plan_cuentas = (isset($_POST['codigo_plan_cuentas'])) ? $_POST['codigo_plan_cuentas'] : 0;
	    
	    if(!empty($codigo_plan_cuentas && $codigo_plan_cuentas != 0)){
	        
	        //consulto el nivel de la cuenta para buscar hijos
	        $result = $plan_cuentas->getBy("codigo_plan_cuentas='$codigo_plan_cuentas'");
	        
	        if(!empty($result)){
	            
	            $nivel = $result[0]->nivel_plan_cuentas;
	            $nivel=$nivel+1;
	        
	            $columnas="codigo_plan_cuentas, concat(codigo_plan_cuentas,'     ',nombre_plan_cuentas) as nombre_plan_cuentas";
	            $tabla = "plan_cuentas";
	            $where = "nivel_plan_cuentas = '$nivel' and codigo_plan_cuentas like '$codigo_plan_cuentas%'";
	            $id="codigo_plan_cuentas";
	            $resulset = $plan_cuentas->getCondiciones($columnas,$tabla,$where,$id);
	            
	            if(!empty($resulset) && count($resulset)>0){
	                
	                echo json_encode(array('data'=>$resulset));
	                
	            }
	        
	        }
	        
	    }
	    
	}
	
	
	
	
	
	public function cargaCuentasHijos(){
	    
	    $plan_cuentas= new PlanCuentasModel();
	    
	    $codigo_plan_cuentas = (isset($_POST['codigo_sub_plan_cuentas'])) ? $_POST['codigo_sub_plan_cuentas'] : 0;
	    
	    if(!empty($codigo_plan_cuentas && $codigo_plan_cuentas != 0)){
	        
	        //consulto el nivel de la cuenta para buscar hijos
	        $result = $plan_cuentas->getBy("codigo_plan_cuentas='$codigo_plan_cuentas'");
	        
	        if(!empty($result)){
	            
	            $nivel = $result[0]->nivel_plan_cuentas;
	            $nivel=$nivel+1;
	            
	            $columnas="codigo_plan_cuentas, concat(codigo_plan_cuentas,'     ',nombre_plan_cuentas) as nombre_plan_cuentas";
	            $tabla = "plan_cuentas";
	            $where = "nivel_plan_cuentas = '$nivel' and codigo_plan_cuentas like '$codigo_plan_cuentas%'";
	            $id="codigo_plan_cuentas";
	            $resulset = $plan_cuentas->getCondiciones($columnas,$tabla,$where,$id);
	            
	            if(!empty($resulset) && count($resulset)>0){
	                
	                echo json_encode(array('data'=>$resulset));
	                
	            }
	            
	        }
	        
	    }
	    
	}
	
	
	
	
	public function devuelve_aportes($_codigo_plan_cuentas_hijos, $_desde_diario, $_hasta_diario, $action, $search, $page){
	    
	    $aportes=new CoreContribucionModel();
	    $plan_cuentas = new PlanCuentasModel();
	    $arrayA = array();
	    $stringArray="";
	    $html="";
	    $where_to="";
	    
	    $result = $plan_cuentas->getBy("codigo_plan_cuentas='$_codigo_plan_cuentas_hijos'");
	    
	    if(!empty($result)){
	        
	        $nivel = $result[0]->nivel_plan_cuentas;
	        $nivel=$nivel+1;
	        
	        $columnas_1="id_plan_cuentas";
	        $tabla_1 = "plan_cuentas";
	        $where_1 = "nivel_plan_cuentas = '$nivel' and codigo_plan_cuentas like '$_codigo_plan_cuentas_hijos%'";
	        $id_1="codigo_plan_cuentas";
	        $resulset = $plan_cuentas->getCondiciones($columnas_1, $tabla_1, $where_1, $id_1);
	        
	       
	        
	        if(!empty($resulset)){
	            
	            foreach ($resulset as $res) {
	             
	                $arrayA[] = $res->id_plan_cuentas;
	                
	            }
	            
	            $stringArray = join( ",", $arrayA);
	            
	            $columnas="pl.codigo_plan_cuentas, pl.nombre_plan_cuentas, coalesce(sum(c.valor_personal_contribucion+c.valor_patronal_contribucion),0) as total";
	            $tablas="core_contribucion c
                        inner join core_participes p on c.id_participes=p.id_participes
                        inner join core_contribucion_tipo ct on c.id_contribucion_tipo=ct.id_contribucion_tipo
                        inner join plan_cuentas pl on ct.id_plan_cuentas_cta_individual=pl.id_plan_cuentas";
	            $where="ct.id_plan_cuentas_cta_individual in (".$stringArray.") and c.id_estatus=1 and p.id_estatus=1 AND date(c.fecha_contable_distribucion) between '$_desde_diario' and '$_hasta_diario'";
	            $grupo="pl.codigo_plan_cuentas, pl.nombre_plan_cuentas";
	            $having="sum(c.valor_personal_contribucion+c.valor_patronal_contribucion) <> 0";
	            
	            
	            
	            
	            if($action == 'ajax')
	            {
	                
	                if(!empty($search)){
	                    
	                    
	                    $where1=" AND (pl.codigo_plan_cuentas ILIKE '".$search."%' OR pl.nombre_plan_cuentas ILIKE '".$search."%')";
	                    
	                    $where_to=$where.$where1;
	                }else{
	                    
	                    $where_to=$where;
	                    
	                }
	                
	                $resultAportes=$aportes->getCondiciones_Grupo_Having($columnas, $tablas, $where_to, $grupo, $having);
	                $cantidadResult=count($resultAportes);
	                
	                
	                
	                $per_page = 10; //la cantidad de registros que desea mostrar
	                $adjacents  = 5; //brecha entre páginas después de varios adyacentes
	                $offset = ($page - 1) * $per_page;
	                
	                $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	                
	                $resultAportes=$aportes->getCondiciones_Grupo_Having_Limit($columnas, $tablas, $where_to, $grupo, $having, $limit);
	                $total_pages = ceil($cantidadResult/$per_page);
	                
	                
	                if($cantidadResult>0)
	                {
	                    
	                    
	                    $html.='<div class="pull-left" style="margin-left:15px;">';
	                    $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	                    $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	                    $html.='</div>';
	                    $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	                    $html.='<section style="height:425px; overflow-y:scroll;">';
	                    $html.= "<table id='tabla_consulta' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	                    $html.= "<thead>";
	                    $html.= "<tr>";
	                    $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                    $html.='<th style="text-align: left;  font-size: 12px;">Codigo</th>';
	                    $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	                    $html.='<th style="text-align: right;  font-size: 12px;">Saldo</th>';
	                    $html.='</tr>';
	                    $html.='</thead>';
	                    $html.='<tbody>';
	                    
	                    
	                    $i=0;
	                      $total=0;
	                    foreach ($resultAportes as $res)
	                    {
	                        $i++;
	                        
	                        $total=$total+$res->total;
	                        
	                        $html.='<tr>';
	                        
	                        $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                        $html.='<td style="font-size: 11px;">'.$res->codigo_plan_cuentas.'</td>';
	                        $html.='<td style="font-size: 11px;">'.$res->nombre_plan_cuentas.'</td>';
	                        $html.='<td style="font-size: 11px; text-align: right;">'.number_format((float)$res->total, 2, '.', ',').'</td>';
	                        $html.='</tr>';
	                    }
	                    
	                    $html.='</tbody>';
	                    $html.='</table>';
	                    $html.='</section></div>';
	                    
	                    
	                    
	                    $html.='<div class="table-pagination pull-right">';
	                    $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents , "load_tabla").'';
	                    $html.='</div>';
	                    
	                    
	                    
	                }else{
	                    $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	                    $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	                    $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	                    $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay movimientos en las cuentas seleccionadas...</b>';
	                    $html.='</div>';
	                    $html.='</div>';
	                }
	                
	                
	            }
	            
	            
	            
	        }else{
	            
	            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay movimientos en las cuentas seleccionadas...</b>';
	            $html.='</div>';
	            $html.='</div>';
	            
	        }
	        
	    }
	    
	    
	    return  $html;
	    
	}
	
	
	public function TablaPlanCuentas()
	{
	    session_start();
	    
	    if (isset(  $_SESSION['usuario_usuarios']) )
	    {
	        
	        $plan_cuentas= new PlanCuentasModel();
	        
	        $tablas= "public.plan_cuentas";
	        
	        $where= "1=1";
	        
	        $id= "plan_cuentas.codigo_plan_cuentas";
	        
	        $resultSet=$plan_cuentas->getCondiciones("*", $tablas, $where, $id);
	        
	        $tablas= "public.plan_cuentas";
	        
	        $where= "1=1";
	        
	        $id= "max";
	        
	        $resultMAX=$plan_cuentas->getCondiciones("MAX(nivel_plan_cuentas)", $tablas, $where, $id);
	        
	        $headerfont="16px";
	        $tdfont="14px";
	        $boldi="";
	        $boldf="";
	        
	        $colores= array();
	        $colores[0]="#D6EAF8";
	        $colores[1]="#D1F2EB";
	        $colores[2]="#FCF3CF";
	        $colores[3]="#F8C471";
	        $colores[4]="#EDBB99";
	        $colores[5]="#FDFEFE";
	        
	        $datos_tabla= "<table id='tabla_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	        $datos_tabla.='<tr  bgcolor="'.$colores[0].'">';
	        $datos_tabla.='<th bgcolor="'.$colores[0].'" width="1%"  style="width:130px; text-align: center;  font-size: '.$headerfont.';">CÓDIGO</th>';
	        $datos_tabla.='<th bgcolor="'.$colores[0].'" width="83%" style="text-align: center;  font-size: '.$headerfont.';">CUENTA</th>';
	        $datos_tabla.='</tr>';
	        
	        $datos_tabla.=$this->Balance(1, $resultSet, $resultMAX[0]->max, "");
	        
	        $datos_tabla.= "</table>";
	        
	        echo $datos_tabla;
	    }
	    else
	    {
	        
	        $this->redirect("Usuarios","sesion_caducada");
	    }
	    
	    
	}
	
	//termina
	
	
	
	
}
?>