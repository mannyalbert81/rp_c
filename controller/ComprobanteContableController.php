<?php

class ComprobanteContableController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

//maycol

	
	
	public function consulta_plan_cuentas(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $usuarios = new UsuariosModel();
	    $catalogo = null; $catalogo = new CatalogoModel();
	    $where_to="";
	    $columnas = "  plan_cuentas.codigo_plan_cuentas, 
                        plan_cuentas.nombre_plan_cuentas";
	    
	    $tablas = "public.plan_cuentas";
	    
	    
	    $where    = " 1=1";
	    
	    $id       = "plan_cuentas.id_plan_cuentas";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        //estado_usuario
	         
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (plan_cuentas.codigo_plan_cuentas LIKE '".$search."%' OR plan_cuentas.nombre_plan_cuentas LIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$usuarios->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$usuarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
	            $html.= "<table id='tabla_plan_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">Nº</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Código Cuenta</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre Cuenta</th>';
	  
	            
	            if($id_rol==1){
	                
	               }
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->codigo_plan_cuentas.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_plan_cuentas.'</td>';
	                
	                
	                
	                if($id_rol==1){
	                    
	                    }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_plan_cuentas("index.php", $page, $total_pages, $adjacents).'';
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
	
	
	public function paginate_plan_cuentas($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_comprobantes(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_comprobantes(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_comprobantes(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	

	public function index(){
	
		session_start();
		
		
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		    
		    $_id_usuarios= $_SESSION['id_usuarios'];
		    
			$arrayGet=array();
			$temp_comprobantes=new ComprobantesTemporalModel();
			$d_comprobantes = new DComprobantesModel();
			
			$tipo_comprobante=new TipoComprobantesModel();
			$resultTipCom = $tipo_comprobante->getBy("nombre_tipo_comprobantes='CONTABLE'");
			
			$columnas_enc = "entidades.id_entidades,
  							entidades.nombre_entidades,
		    		        consecutivos.numero_consecutivos";
			$tablas_enc ="public.usuarios,
						  public.entidades,
		    		      public.consecutivos";
			$where_enc ="consecutivos.id_entidades = entidades.id_entidades AND entidades.id_entidades = usuarios.id_entidades AND consecutivos.nombre_consecutivos='CONTABLE' AND usuarios.id_usuarios='$_id_usuarios'";
			$id_enc="entidades.nombre_entidades";
			$resultSet=$d_comprobantes->getCondiciones($columnas_enc ,$tablas_enc ,$where_enc, $id_enc);
			
				
		    $permisos_rol = new PermisosRolesModel();
			$nombre_controladores = "ComprobanteContable";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $permisos_rol->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
				
			if (!empty($resultPer))
			{
				
				if (isset($_POST['concepto_ccomprobantes'])){
						
					$_concepto_ccomprobantes =$_POST['concepto_ccomprobantes'];
					$_fecha_ccomprobantes =$_POST['fecha_ccomprobantes'];
					$arrayGet['array_concepto_ccomprobantes']=$_concepto_ccomprobantes;
					$arrayGet['array_fecha_ccomprobantes']=$_fecha_ccomprobantes;
					
				}
				
				
					
				if(isset($_GET["id_temp_comprobantes"]))
				{
					$_id_usuarios= $_SESSION['id_usuarios'];
					$id_temp_comprobantes=(int)$_GET["id_temp_comprobantes"];
						
					$where = "id_usuario_registra = '$_id_usuarios' AND id_temp_comprobantes = '$id_temp_comprobantes'  ";
					$resultado = $temp_comprobantes->deleteByWhere($where);
						
				
					$traza=new TrazasModel();
					$_nombre_controlador = "ComprobanteContable";
					$_accion_trazas  = "Borrar";
					$_parametros_trazas = $id_temp_comprobantes;
					$resultado = $traza->AuditoriaControladores($_accion_trazas, $_parametros_trazas, $_nombre_controlador);
				}
				
				if(isset($_POST["plan_cuentas"])){
				$_id_plan_cuentas= $_POST["plan_cuentas"];
				
				if($_id_plan_cuentas==""){
					
				}else 
				{
						$_descripcion_dcomprobantes= $_POST["descripcion_dcomprobantes"];
						
						$_debe_dcomprobantes= $_POST["debe_dcomprobantes"];
					
						if ($_debe_dcomprobantes=="")
						{
							$_debe_dcomprobantes=0;
								
						}
						$_haber_dcomprobantes= $_POST["haber_dcomprobantes"];
					
						if ($_haber_dcomprobantes=="")
						{
							$_haber_dcomprobantes=0;
					
						}
					
						$funcion = "ins_temp_comprobantes";
						$parametros = "'$_id_usuarios','$_id_plan_cuentas','$_descripcion_dcomprobantes','$_debe_dcomprobantes','$_haber_dcomprobantes'";
						$temp_comprobantes->setFuncion($funcion);
						$temp_comprobantes->setParametros($parametros);
						$resultado=$temp_comprobantes->Insert();
					
				}
				}
				
				
				
				 
					
				$this->view("ComprobanteContable",array(
				    "resultSet"=>$resultSet, "resultRes"=>$resultRes, "resultTipCom"=>$resultTipCom, "arrayGet"=>$arrayGet
					));
			
			
			}else{
				
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Generar Comprobantes"
				
					
				));
				exit();
			}
			
			
		}
		else
		{
	
		    $this->redirect("Usuarios","sesion_caducada");
		}
	
	}
	 
	
	
	/*
	public function InsertarTemporal(){
		
		session_start();
		$_id_usuarios= $_SESSION['id_usuarios'];
		
		
		$temp_comprobantes=new ComprobantesTemporalModel();
		$nombre_controladores = "Comprobantes";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $temp_comprobantes->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
		
		
		
		
		if (isset ($_POST["plan_cuentas"]) && isset ($_POST["descripcion_dcomprobantes"]) && isset ($_POST["debe_dcomprobantes"]) && isset($_POST["haber_dcomprobantes"])  )
		{
		
		
			$_id_plan_cuentas= $_POST["plan_cuentas"];
			$_descripcion_dcomprobantes= $_POST["descripcion_dcomprobantes"];
			$_debe_dcomprobantes= $_POST["debe_dcomprobantes"];
		
			if ($_debe_dcomprobantes=="")
			{
				$_debe_dcomprobantes=0;
					
			}
			$_haber_dcomprobantes= $_POST["haber_dcomprobantes"];
		
			if ($_haber_dcomprobantes=="")
			{
				$_haber_dcomprobantes=0;
		
			}
		
			$funcion = "ins_temp_comprobantes";
			$parametros = "'$_id_usuarios','$_id_plan_cuentas','$_descripcion_dcomprobantes','$_debe_dcomprobantes','$_haber_dcomprobantes'";
			$temp_comprobantes->setFuncion($funcion);
			$temp_comprobantes->setParametros($parametros);
			$resultado=$temp_comprobantes->Insert();
		
		}
	}
		
   */
	
   public function InsertaComprobanteContable(){
   
   	session_start();
   
   	$resultado = null;
   	$permisos_rol=new PermisosRolesModel();
   
   	$plan_cuentas= new PlanCuentasModel();
   	 
   	$forma_pago = new FormaPagoModel();
   	$consecutivos = new ConsecutivosModel();
    $ccomprobantes = new CComprobantesModel();
   	$dcomprobantes = new DComprobantesModel();
   	$tem_comprobantes = new ComprobantesTemporalModel();
   	$tipo_comprobantes = new TipoComprobantesModel();
   
   
   	$nombre_controladores = "ComprobanteContable";
   	$id_rol= $_SESSION['id_rol'];
   	$resultPer = $ccomprobantes->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
   
   	if (!empty($resultPer))
   	{
   		
   		if (isset ($_POST["id_entidades"]))
   		{
   
   			$_id_usuarios = $_SESSION['id_usuarios'];
   			
   			$where =  "id_usuario_registra= '$_id_usuarios' ";
   			$resultCom =  $tem_comprobantes->getBy($where);
   			
   			
   			$_id_entidades =$_POST['id_entidades'];
   			$_id_tipo_comprobantes =$_POST['id_tipo_comprobantes'];
   			
   			$resultConsecutivos = $consecutivos->getBy("nombre_consecutivos LIKE '%CONTABLE%' AND id_entidades='$_id_entidades' AND id_tipo_comprobantes='$_id_tipo_comprobantes'");
   			$_id_consecutivos=$resultConsecutivos[0]->id_consecutivos;
   			
   			$_numero_consecutivos=$resultConsecutivos[0]->numero_consecutivos;
   			$_update_numero_consecutivo=((int)$_numero_consecutivos)+1;
   			$_update_numero_consecutivo=str_pad($_update_numero_consecutivo,6,"0",STR_PAD_LEFT);
   			
   			$_ruc_ccomprobantes ="";
   			$_nombres_ccomprobantes ="";
   			$_retencion_ccomprobantes ="";
   			$_valor_ccomprobantes =$_POST['valor_ccomprobantes'];
   			$_concepto_ccomprobantes =$_POST['concepto_ccomprobantes'];
   			$_id_usuario_creador=$_SESSION['id_usuarios'];
   			$_valor_letras =$_POST['valor_letras'];
            $_fecha_ccomprobantes = $_POST['fecha_ccomprobantes']; 
            $_referencia_doc_ccomprobantes ="";
            $resultFormaPago = $forma_pago->getBy("nombre_forma_pago LIKE '%NINGUNA%'");
            $_id_forma_pago=$resultFormaPago[0]->id_forma_pago;
            $_numero_cuenta_banco_ccomprobantes="";
            $_numero_cheque_ccomprobantes="";
            $_observaciones_ccomprobantes="";
   			
   			
   			///PRIMERO INSERTAMOS LA CABEZA DEL COMPROBANTE
   			try
   			{
   					
   				$funcion = "ins_ccomprobantes";
   				$parametros = "'$_id_entidades','$_id_tipo_comprobantes', '$_numero_consecutivos','$_ruc_ccomprobantes','$_nombres_ccomprobantes' ,'$_retencion_ccomprobantes' ,'$_valor_ccomprobantes' ,'$_concepto_ccomprobantes', '$_id_usuario_creador', '$_valor_letras' , '$_fecha_ccomprobantes', '$_id_forma_pago', '$_referencia_doc_ccomprobantes', '$_numero_cuenta_banco_ccomprobantes', '$_numero_cheque_ccomprobantes', '$_observaciones_ccomprobantes' ";
   				$ccomprobantes->setFuncion($funcion);
   				$ccomprobantes->setParametros($parametros);
   				$resultado=$ccomprobantes->Insert();
   				
   				
   				$resultConsecutivo=$consecutivos->UpdateBy("numero_consecutivos='$_update_numero_consecutivo'", "consecutivos", "id_consecutivos='$_id_consecutivos'");
   				
   				
   				//$print="'$_id_entidades','$_id_tipo_comprobantes', '$_numero_consecutivos','$_ruc_ccomprobantes','$_nombres_ccomprobantes' ,'$_retencion_ccomprobantes' ,'$_valor_ccomprobantes' ,'$_concepto_ccomprobantes', '$_id_usuario_creador'";
   				//$this->view("Error",array("resultado"=>$print));	
   				//die();
   
   				///INSERTAMOS DETALLE  DEL MOVIMIENTO
   					
   				foreach($resultCom as $res)
   				{
   
   					//busco si existe este nuevo id
   					try
   					{
   						$_id_plan_cuentas = $res->id_plan_cuentas;
   						$_descripcion_dcomprobantes = $res->observacion_temp_comprobantes;
   						$_debe_dcomprobantes = $res->debe_temp_comprobantes;
   						$_haber_dcomprobantes = $res->haber_temp_comprobantes;
   
   						$resultComprobantes = $ccomprobantes->getBy("numero_ccomprobantes ='$_numero_consecutivos' AND id_entidades ='$_id_entidades' AND id_tipo_comprobantes='$_id_tipo_comprobantes'");
   						$_id_ccomprobantes=$resultComprobantes[0]->id_ccomprobantes;
   						
   						
   						
   						$funcion = "ins_dcomprobantes";
   						$parametros = "'$_id_ccomprobantes','$_numero_consecutivos','$_id_plan_cuentas', '$_descripcion_dcomprobantes', '$_debe_dcomprobantes', '$_haber_dcomprobantes'";
   						$dcomprobantes->setFuncion($funcion);
   						$dcomprobantes->setParametros($parametros);
   						$resultado=$dcomprobantes->Insert();
   						
   						$resultSaldoIni = $plan_cuentas->getBy("id_plan_cuentas ='$_id_plan_cuentas' AND id_entidades ='$_id_entidades'");
   						$_saldo_ini=$resultSaldoIni[0]->saldo_fin_plan_cuentas;
   						
   						$_fecha_mayor = getdate();
   						$_fecha_año=$_fecha_mayor['year'];
   						$_fecha_mes=$_fecha_mayor['mon'];
   						$_fecha_dia=$_fecha_mayor['mday'];
   							
   						$_fecha_actual=$_fecha_año.'-'.$_fecha_mes.'-'.$_fecha_dia;
   							
   						////llamas a la funcion mayoriza();
   						$resul = $dcomprobantes->Mayoriza($_id_plan_cuentas, $_id_ccomprobantes, $_fecha_actual, $_debe_dcomprobantes, $_haber_dcomprobantes, $_saldo_ini);
   						$_cadena = $_id_plan_cuentas .'-'. $_id_ccomprobantes .'-'. $_fecha_actual .'-'. $_debe_dcomprobantes .'-'. $_haber_dcomprobantes .'-'. $_saldo_ini;
   							
   							
   						///LAS TRAZAS
   						$traza=new TrazasModel();
   						$_nombre_controlador = "ComprobanteContable";
   						$_accion_trazas  = "Guardar";
   						$_parametros_trazas = $_id_plan_cuentas;
   						$resulta = $traza->AuditoriaControladores($_accion_trazas, $_parametros_trazas, $_nombre_controlador);
   							
   						
   						///borro de las solicitudes el carton
   						$where_del = "id_usuario_registra= '$_id_usuarios'";
   						$tem_comprobantes->deleteByWhere($where_del);
   							
   					   
   					} catch (Exception $e)
   					{
   						$this->view("Error",array(
   								"resultado"=>"Eror al Insertar Comprobante Contable ->". $id
   						));
   						exit();
   					}
   						
   				}					
   					
   					
   			}
   			catch (Exception $e)
   			{
   
   			}
   		
   
   		}	
   		
   		$this->redirect("ComprobanteContable","index")	;
   	}
   	else
   	{
   		$this->view("Error",array(
   				"resultado"=>"No tiene Permisos de Guardar Comprobante Contable"
   
   		));
   
   
   	}
   
   
   
   }
   
    
   /*
   
   public function borrarId()
   {
   
   	session_start();
   
   	$permisos_rol=new PermisosRolesModel();
   	$nombre_controladores = "Comprobantes";
   	$id_rol= $_SESSION['id_rol'];
   	$resultPer = $permisos_rol->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
   		
   	if (!empty($resultPer))
   	{
   		if(isset($_GET["id_temp_comprobantes"]))
   		{
   			$id_temp_comprobantes=(int)$_GET["id_temp_comprobantes"];
   
   			$temp_comprobantes=new ComprobantesTemporalModel();
   			
   			$temp_comprobantes->deleteBy(" id_temp_comprobantes",$id_temp_comprobantes);
   
   			$traza=new TrazasModel();
   			$_nombre_controlador = "Comprobantes";
   			$_accion_trazas  = "Borrar";
   			$_parametros_trazas = $id_temp_comprobantes;
   			$resultado = $traza->AuditoriaControladores($_accion_trazas, $_parametros_trazas, $_nombre_controlador);
   		}
   			
   		$this->redirect("Comprobantes", "index");
   			
   			
   	}
   	else
   	{
   		$this->view("Error",array(
   				"resultado"=>"No tiene Permisos de Borrar Comprobantes"
		
   		));
   	}
   
   }
   
    
   */
   
  
		
	
	public function AutocompleteComprobantesCodigo(){
		
		session_start();
		$_id_usuarios= $_SESSION['id_usuarios'];
		$plan_cuentas = new PlanCuentasModel();
	    $codigo_plan_cuentas = $_GET['term'];
	
	    $columnas ="plan_cuentas.codigo_plan_cuentas";
		$tablas =" public.usuarios, 
				  public.entidades, 
				  public.plan_cuentas";
		$where ="plan_cuentas.codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas='4'";
		$id ="plan_cuentas.codigo_plan_cuentas";
		
		
		$resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	
	
		if(!empty($resultSet)){
				
			foreach ($resultSet as $res){
	
			    $_respuesta[] = $res->codigo_plan_cuentas;
			}
			echo json_encode($_respuesta);
		}
	
	}
	
	
	
	
	public function AutocompleteComprobantesDevuelveNombre(){
		session_start();
		$_id_usuarios= $_SESSION['id_usuarios'];
		
		
		$plan_cuentas = new PlanCuentasModel();
		$codigo_plan_cuentas = $_POST['codigo_plan_cuentas'];
		
		
		$columnas ="plan_cuentas.codigo_plan_cuentas,
				  plan_cuentas.nombre_plan_cuentas,
				  plan_cuentas.id_plan_cuentas";
		$tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
		$where ="plan_cuentas.codigo_plan_cuentas = '$codigo_plan_cuentas' AND entidades.id_entidades = usuarios.id_entidades AND
		plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas='4'";
		$id ="plan_cuentas.codigo_plan_cuentas";
		
		
		$resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
		
	
		$respuesta = new stdClass();
	
		if(!empty($resultSet)){
				
			$respuesta->nombre_plan_cuentas = $resultSet[0]->nombre_plan_cuentas;
			$respuesta->id_plan_cuentas = $resultSet[0]->id_plan_cuentas;
				
			echo json_encode($respuesta);
		}
	
	}
	
	
	
	
	public function AutocompleteComprobantesNombre(){
	
		session_start();
		$_id_usuarios= $_SESSION['id_usuarios'];
		$plan_cuentas = new PlanCuentasModel();
		$nombre_plan_cuentas = $_GET['term'];
	
		//$resultSet=$plan_cuentas->getBy("codigo_plan_cuentas LIKE '$codigo_plan_cuentas%'");
		 
		 
		 
		$columnas ="plan_cuentas.codigo_plan_cuentas,
				  plan_cuentas.nombre_plan_cuentas,
				  plan_cuentas.id_plan_cuentas";
		$tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
		$where ="plan_cuentas.nombre_plan_cuentas LIKE '$nombre_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
		plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas='4'";
		$id ="plan_cuentas.codigo_plan_cuentas";
	
	
		$resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	
	
		if(!empty($resultSet)){
	
			foreach ($resultSet as $res){
	
				$_nombre_plan_cuentas[] = $res->nombre_plan_cuentas;
			}
			echo json_encode($_nombre_plan_cuentas);
		}
	
	}
	
	
	
	
	public function AutocompleteComprobantesDevuelveCodigo(){
	
		session_start();
		$_id_usuarios= $_SESSION['id_usuarios'];
		
		$plan_cuentas = new PlanCuentasModel();
	
		$nombre_plan_cuentas = $_POST['nombre_plan_cuentas'];
	

		$columnas ="plan_cuentas.codigo_plan_cuentas,
				  plan_cuentas.nombre_plan_cuentas,
				  plan_cuentas.id_plan_cuentas";
		$tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
		$where ="plan_cuentas.nombre_plan_cuentas = '$nombre_plan_cuentas' AND entidades.id_entidades = usuarios.id_entidades AND
		plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas='4'";
		$id ="plan_cuentas.codigo_plan_cuentas";
		
		
		$resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
		
	
		$respuesta = new stdClass();
	
		if(!empty($resultSet)){
	
			$respuesta->codigo_plan_cuentas = $resultSet[0]->codigo_plan_cuentas;
			$respuesta->id_plan_cuentas = $resultSet[0]->id_plan_cuentas;
	
			echo json_encode($respuesta);
		}
	
	}
	
	
	
	
}
?>