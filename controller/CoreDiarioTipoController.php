<?php

class CoreDiarioTipoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	
	
	public function consulta_temp_diario_tipo()
	{
	    
	    session_start();
	    $_id_usuarios = $_SESSION["id_usuarios"];
	    $core_temp_diario_tipo   = new CoreTempDiarioTipoModel();
	    $where_to="";
	  
	    $columnas = "core_temp_diario_tipo.id_temp_diario_tipo, 
                     core_temp_diario_tipo.observacion_temp_diario_tipo, 
                     plan_cuentas.codigo_plan_cuentas, 
                     plan_cuentas.nombre_plan_cuentas,
                    core_temp_diario_tipo.debe_temp_diario_tipo,
                    core_temp_diario_tipo.haber_temp_diario_tipo,
                    core_temp_diario_tipo.destino_temp_diario_tipo";
	    $tablas = "public.core_temp_diario_tipo, 
                    public.plan_cuentas";
	    $where = "core_temp_diario_tipo.id_plan_cuentas = plan_cuentas.id_plan_cuentas AND core_temp_diario_tipo.id_usuarios='$_id_usuarios'";
	    $id="core_temp_diario_tipo.id_temp_diario_tipo";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        
	        if(!empty($search)){
	            
	            $where1=" AND (plan_cuentas.codigo_plan_cuentas LIKE '".$search."%' OR plan_cuentas.nombre_plan_cuentas LIKE '".$search."%' OR core_temp_diario_tipo.observacion_temp_diario_tipo LIKE '".$search."%')";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	       
	        }
	        
	        
	        $html="";
	        $resultSet=$core_temp_diario_tipo->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$core_temp_diario_tipo->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:150px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_temp_diario_tipo_registrados' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 13px;">Cuenta</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Descripción</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Destino</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;"></th>';
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody >';
	            
	            $i=0;
	          
	            foreach ($resultSet as $res)
	            {
	                
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 12px;">'.$res->codigo_plan_cuentas.'</td>';
	                $html.='<td style="font-size: 12px;">'.$res->nombre_plan_cuentas.'</td>';
	                $html.='<td style="font-size: 12px;">'.$res->observacion_temp_diario_tipo.'</td>';
	                $html.='<td style="font-size: 12px;">'.strtoupper($res->destino_temp_diario_tipo).'</td>';
	                $html.='<td style="font-size: 16px;"><a href="#" data-toggle="tooltip" title="Eliminar" onclick="eliminar_temp_diario_tipo('.$res->id_temp_diario_tipo.')"><i class="glyphicon glyphicon-trash"></i></a></td>';
	                $html.='</tr>';
	            }
	            
	        
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_temp_diario_tipo("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay cuentas registradas...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	    
	}
	
	
	
	public function consulta_diarios_tipo(){
	    
	    session_start();
	    
	    $diario_cabeza = new CoreDiarioTipoCabezaModel();
	    $diario_detalle = new CoreDiarioTipoDetalleModel();
	
	    
	    $where_to="";
	    $columnas = "   core_diario_tipo_cabeza.id_diario_tipo_cabeza, 
                          core_diario_tipo_cabeza.id_tipo_procesos, 
                          core_diario_tipo_cabeza.descripcion_diario_tipo_cabeza, 
                          core_estatus.nombre_estatus, 
                          estado.nombre_estado, 
                          modulos.nombre_modulos, 
                          core_diario_tipo_cabeza.creado, 
                          core_diario_tipo_cabeza.modificado";
	    
	    $tablas = "public.core_diario_tipo_cabeza, 
                  public.estado, 
                  public.core_estatus, 
                  public.modulos";
	    
	    
	    $where    = "  core_diario_tipo_cabeza.id_estado = estado.id_estado AND
  core_diario_tipo_cabeza.id_estatus = core_estatus.id_estatus AND
  modulos.id_modulos = core_diario_tipo_cabeza.id_modulos";
	    
	    $id       = "core_diario_tipo_cabeza.id_diario_tipo_cabeza";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        //estado_usuario
	         
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (core_diario_tipo_cabeza.descripcion_diario_tipo_cabeza LIKE '".$search."%')";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$diario_cabeza->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$diario_cabeza->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:250px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_diarios_tipo' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">Ord</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Proceso</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Descripción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Modulo</th>';
	       
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            
	            
	            foreach ($resultSet as $res)
	            
	     
	            {
	               $id_diario_tipo_cabeza = $res->id_diario_tipo_cabeza;
	               
	               $columnas1 = "    core_diario_tipo_detalle.id_diario_tipo_detalle,
                                      plan_cuentas.codigo_plan_cuentas, 
                                      plan_cuentas.nombre_plan_cuentas, 
                                      core_diario_tipo_detalle.observacion_diario_tipo_detalle, 
                                      core_diario_tipo_detalle.destino_diario_tipo_detalle";
	                
	               $tablas1 = "public.core_diario_tipo_detalle, 
                                  public.plan_cuentas";
	                
            	                
            	   $where1 = "plan_cuentas.id_plan_cuentas = core_diario_tipo_detalle.id_plan_cuentas AND core_diario_tipo_detalle.id_diario_tipo_cabeza = $id_diario_tipo_cabeza";
            	                
                   $id1       = "core_diario_tipo_detalle.id_diario_tipo_detalle";
                   
            	           
                   $resultSet1=$diario_detalle->getCondiciones($columnas1, $tablas1, $where1, $id1);
            	                
            	                
            	                
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->id_tipo_procesos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->descripcion_diario_tipo_cabeza.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_estado.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_modulos.'</td>';
                    $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_diarios_tipo("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay diarios tipo registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
	
	
	
	
	public function paginate_temp_diario_tipo($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_temp_diario_tipo_registrados(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_temp_diario_tipo_registrados(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_temp_diario_tipo_registrados(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_temp_diario_tipo_registrados(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_temp_diario_tipo_registrados(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_temp_diario_tipo_registrados($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_temp_diario_tipo_registrados(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	
	public function paginate_diarios_tipo($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_diarios_tipo(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_diarios_tipo(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_diarios_tipo(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_diarios_tipo(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_diarios_tipo(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_diarios_tipo($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_diarios_tipo(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	
	public function  consulta_consecutivos(){
	    
	    
	    session_start();
	    $_id_usuarios = $_SESSION["id_usuarios"];
	    $d_comprobantes = new DComprobantesModel();
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $id_tipo_comprobantes =  (isset($_REQUEST['id_tipo_comprobantes'])&& $_REQUEST['id_tipo_comprobantes'] !=NULL)?$_REQUEST['id_tipo_comprobantes']:0;
	    
	    if($action == 'ajax' && $_id_usuarios>0 && $id_tipo_comprobantes > 0)
	    {
	        
    	    $columnas_enc = "entidades.id_entidades,
      							entidades.nombre_entidades,
    		    		        consecutivos.numero_consecutivos,
                                consecutivos.nombre_consecutivos";
    	    $tablas_enc ="public.usuarios,
    						  public.entidades,
    		    		      public.consecutivos";
    	    $where_enc ="consecutivos.id_entidades = entidades.id_entidades AND entidades.id_entidades = usuarios.id_entidades 
                         AND consecutivos.id_tipo_comprobantes='$id_tipo_comprobantes' 
                         AND usuarios.id_usuarios='$_id_usuarios'";
    	    $id_enc="entidades.nombre_entidades";
    	    $resultSet=$d_comprobantes->getCondiciones($columnas_enc ,$tablas_enc ,$where_enc, $id_enc);
    	    
    	    if(!empty($resultSet)){
    	        
    	        $_numero               = $resultSet[0]->numero_consecutivos;    	        
    	        $_nombre_consecutivo   = $resultSet[0]->nombre_consecutivos;
    	        
    	        echo json_encode(array('numero'=>$_numero,'nombre'=>$_nombre_consecutivo));
    	        //echo '{"numero":"'.$_numero.'","nombre":"'.$_nombre_consecutivo.'"}';
    	    }
    	    
	    
	   }
	    
	}

	
	
	
	public function index(){
	
		session_start();
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		    
		    $_id_usuarios= $_SESSION['id_usuarios'];		    
			
			$core_temp_diario_tipo   = new ComprobantesTemporalModel();
			
			$core_diario_tipo_credito=new CoreTipoCreditoModel();
			$resultTipCre = $core_diario_tipo_credito->getAll("nombre_tipo_creditos");

			$modulos = new ModulosModel();
			$rsModulos = $modulos->getBy("1=1");
			
			$estado = new EstadoModel();
			$whereEstado = "tabla_estado = 'core_diario_tipo_cabeza'";
			$rsEstado = $estado->getBy($whereEstado);
			//print_r($rsEstado); die();
			
			$nombre_controladores = "CoreDiarioTipo";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $core_temp_diario_tipo->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
				
			if (!empty($resultPer))
			{
					
				$this->view_Contable("DiarioTipo",array(
				    "resultTipCre"=>$resultTipCre,"rsModulos"=>$rsModulos,"rsEstado"=>$rsEstado
					));
			
			
			}else{
				
			    $this->view_Contable("Error",array(
						"resultado"=>"No tiene Permisos"
				
					
				));
				exit();
			}
			
			
		}
		else
		{
	
		    $this->redirect("Usuarios","sesion_caducada");
		}
	
	}
	 
	public function insertDiarioTipo(){
	    
	    session_start();
	    $_id_usuarios = $_SESSION['id_usuarios'];
	    $respuesta = array();
	    $diario_tipo = new CoreDiarioTipoCabezaModel();
	    $nombre_controladores = "CoreDiarioTipo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $diario_tipo->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
	    
	    if( empty($resultPer) ){
	        
	        echo "<message> No tiene permisos para insertar diario <message>"; die();
	    }
	    
	    try{
	        
	        $_id_modulos = $_POST['id_modulos'];
	        $_id_tipo_procesos = $_POST['id_tipo_procesos'];
	        $_descripcion_diario = $_POST['descripcion_diario'];
	        $_id_estado = $_POST['id_estado'];
	        
	        $funcion = "ins_core_diario_tipo_cabeza";
	        $parametros = "'$_id_modulos',
                '$_id_tipo_procesos',
                '$_descripcion_diario',
                '$_id_estado'";
	        
	        $diario_tipo->setFuncion($funcion);
	        $diario_tipo->setParametros($parametros); 
	        
	        $resultado = null;
	        $resultado = $diario_tipo->llamafuncionPG();
	        
	        //tomo cabeceradiario
	        $cabeceraDiarioTipo = 0;
	       
	        if( is_null($resultado) )
	            throw new Exception( "Error insercion cabeza diario tipo");	        
	       
            $_fecha = date('Y-m-d');
	            
            //para insertado de detalle
            if( $resultado[0] > 0 ){
                
                $cabeceraDiarioTipo = $resultado[0];
                
                $funcionDetalle = "ins_core_diario_tipo_detalle";
                $parametrosDetalle = "$cabeceraDiarioTipo,$_id_usuarios,'$_fecha'";
                
                $queryDetalle = "SELECT ".$funcionDetalle."(".$parametrosDetalle.")";
                
                $rsTempDiario = null;
                                
                $rsTempDiario = $diario_tipo->llamarconsultaPG($queryDetalle);      
                
                //echo $queryDetalle; print_r( $rsTempDiario ); 
               
                if( is_null($rsTempDiario) ){
                    throw new Exception( "Error insercion detalle diario tipo");                    
                }
                
                $respuesta['mensaje'] = " Diario Tipo generado Exitosamente";
                
            }else{
                $respuesta['mensaje'] = " Diario Tipo ya ha sido Generado";
            }
            
            echo json_encode($respuesta);
	        
	    }catch (Exception $Ex){
	        
	        //falta implementacion de cuando haya error 
	        
	        echo "<message> Error insertar Diario Tipo. \n". $Ex->getMessage()." <message>";	        
	    }
	    	    	    
	}
	
   public function InsertaComprobanteContable(){
   
   	session_start();
   	$resultado = null;
   	$diario_tipo = new CoreDiarioTipoCabezaModel(); 
   	$permisos_rol=new PermisosRolesModel();
   	$plan_cuentas= new PlanCuentasModel();
   	$tipo_credito = new CoreTipoCreditoModel();
  	$resulTipoCredito=$tipo_credito -> getAll("nombre_tipo_credito");
   	
   	$estado = new EstadoModel();
   
   	
   	
   	$nombre_controladores = "CoreDiarioTipo";
   	$id_rol= $_SESSION['id_rol'];
   	$resultPer = $diario_tipo->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
   
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
   			$_ruc_ccomprobantes ="ruc_ccomprobantes";
   			$_nombres_ccomprobantes ="nombres_ccomprobantes";
   			$_retencion_ccomprobantes ="retencion_ccomprobantes";
   			$_valor_ccomprobantes =$_POST['valor_ccomprobantes'];
   			$_concepto_ccomprobantes =$_POST['concepto_ccomprobantes'];
   			$_id_usuario_creador=$_SESSION['id_usuarios'];
   			$_valor_letras =$_POST['valor_letras'];
            $_fecha_ccomprobantes = $_POST['fecha_ccomprobantes']; 
            $_referencia_doc_ccomprobantes ="referencia_doc_ccomprobantes";
            $resultFormaPago = $forma_pago->getBy("nombre_forma_pago LIKE '%NINGUNA%'");
            $_id_forma_pago=$resultFormaPago[0]->id_forma_pago;
            $_numero_cuenta_banco_ccomprobantes="numero_cuenta_banco_ccomprobantes";
            $_numero_cheque_ccomprobantes="numero_cheque_ccomprobantes";
            $_observaciones_ccomprobantes="observaciones_ccomprobantes";
            
            
            
            
   			
   			
   			///PRIMERO INSERTAMOS LA CABEZA DEL COMPROBANTE
   			try
   			{
   					
   				 $funcion = "ins_ccomprobantes";
   				 $parametros = "'$_id_entidades',
                '$_id_tipo_comprobantes',
                '$_numero_consecutivos',
                '$_ruc_ccomprobantes',
                '$_nombres_ccomprobantes',
                '$_retencion_ccomprobantes',
                '$_valor_ccomprobantes',
                '$_concepto_ccomprobantes',
                '$_id_usuario_creador',
                '$_valor_letras',
                '$_fecha_ccomprobantes',
                '$_id_forma_pago',
                '$_referencia_doc_ccomprobantes',
                '$_numero_cuenta_banco_ccomprobantes',
                '$_numero_cheque_ccomprobantes',
                '$_observaciones_ccomprobantes' ";
   				 
   				 
   				 
   				$ccomprobantes->setFuncion($funcion);
   				$ccomprobantes->setParametros($parametros);
   				$resultado=$ccomprobantes->Insert();
   				
   				
   				$resultConsecutivo=$consecutivos->UpdateBy("numero_consecutivos='$_update_numero_consecutivo'", "consecutivos", "id_consecutivos='$_id_consecutivos'");
   
   				
   				///INSERTAMOS DETALLE  DEL MOVIMIENTO
   					
   				foreach($resultCom as $res)
   				{
   					
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
   						//$resul = $dcomprobantes->Mayoriza($_id_plan_cuentas, $_id_ccomprobantes, $_fecha_actual, $_debe_dcomprobantes, $_haber_dcomprobantes, $_saldo_ini);
   						//$_cadena = $_id_plan_cuentas .'-'. $_id_ccomprobantes .'-'. $_fecha_actual .'-'. $_debe_dcomprobantes .'-'. $_haber_dcomprobantes .'-'. $_saldo_ini;
   							
   							
   						///borro de las solicitudes el carton
   						$where_del = "id_usuario_registra= '$_id_usuarios'";
   						$tem_comprobantes->deleteByWhere($where_del);
   							
   					   
   					} catch (Exception $e)
   					{
   						$this->view("Error",array(
   						    "resultado"=>"Eror al Insertar Comprobante Contable ->". $id, "resulTipoCredito"=>$resulTipoCredito
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
   
    
   
       public function eliminar_temp_diario_tipo(){
           
           session_start();
           
           $_id_usuarios = $_SESSION['id_usuarios'];
           
           $id_temp_diario_tipo = (isset($_REQUEST['id_temp_diario_tipo'])&& $_REQUEST['id_temp_diario_tipo'] !=NULL)?$_REQUEST['id_temp_diario_tipo']:0;
           
           if($_id_usuarios!='' && $id_temp_diario_tipo>0){
               
               $temp_diario_tipo = new CoreTempDiarioTipoModel();
               
               $where = "id_temp_diario_tipo = $id_temp_diario_tipo AND id_usuarios = $_id_usuarios ";
               $resultado=$temp_diario_tipo->deleteById($where);
               
               echo "1";
              
           }
       }
       
       
       
       
       public function insertar_temp_diario_tipo(){
           
           session_start();
           $_id_plan_cuentas = (isset($_REQUEST['plan_cuentas'])&& $_REQUEST['plan_cuentas'] !=NULL)?$_REQUEST['plan_cuentas']:0;
           $_descripcion_dcomprobantes = (isset($_REQUEST['descripcion_dcomprobantes'])&& $_REQUEST['descripcion_dcomprobantes'] !=NULL)?$_REQUEST['descripcion_dcomprobantes']:'';
           $_id_usuarios = $_SESSION['id_usuarios'];
           $_debe_dcomprobantes = (isset($_REQUEST['debe_dcomprobantes'])&& $_REQUEST['debe_dcomprobantes'] !=NULL)?$_REQUEST['debe_dcomprobantes']:0;
           $_haber_dcomprobantes = (isset($_REQUEST['haber_dcomprobantes'])&& $_REQUEST['haber_dcomprobantes'] !=NULL)?$_REQUEST['haber_dcomprobantes']:0;
           $_destino_diario_tipo = ( isset( $_REQUEST['destino_diario'] ) && $_REQUEST['destino_diario'] != NULL ) ? strtoupper($_REQUEST['destino_diario']) : null;
           
           
           $temp_diario_tipo = new CoreTempDiarioTipoModel();
           
           if($_id_usuarios!='' && $_id_plan_cuentas>0){
           
           $_debe_dcomprobantes= str_replace(',', '', $_debe_dcomprobantes);
           $_haber_dcomprobantes= str_replace(',', '', $_haber_dcomprobantes);
           
                       $funcion = "ins_temp_core_diario_tipo";
                       $parametros = "'$_id_plan_cuentas','$_descripcion_dcomprobantes','$_id_usuarios','$_debe_dcomprobantes','$_haber_dcomprobantes','$_destino_diario_tipo'";
                       $temp_diario_tipo->setFuncion($funcion);
                       $temp_diario_tipo->setParametros($parametros);
                       $resultado=$temp_diario_tipo->Insert();
                   
                       echo "1";
           }
           
       }
       
       
		      
	
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
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
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
		plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
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
		plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
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
		plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
		$id ="plan_cuentas.codigo_plan_cuentas";
		
		
		$resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
		
	
		$respuesta = new stdClass();
	
		if(!empty($resultSet)){
	
			$respuesta->codigo_plan_cuentas = $resultSet[0]->codigo_plan_cuentas;
			$respuesta->id_plan_cuentas = $resultSet[0]->id_plan_cuentas;
	
			echo json_encode($respuesta);
		}
	
	}
	
	/***
	 * dc 2019-07-30
	 *
	 */
	public function autompletePlanCuentasByCodigo(){
	    
	    $planCuentas = new PlanCuentasModel();	   
	    
	    if(isset($_GET['term'])){
	        
	        $parametro = $_GET['term'];
	        
	        $columnas = "id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas";
	        $tablas = "public.plan_cuentas";
	        $where = " codigo_plan_cuentas LIKE '$parametro%' AND nivel_plan_cuentas > 3";
	        $id = "codigo_plan_cuentas ";
	        $limit = "LIMIT 10";
	        
	        $rsPlanCuentas = $planCuentas->getCondicionesPag($columnas,$tablas,$where,$id,$limit);
	        
	        $respuesta = array();
	        
	        if(!empty($rsPlanCuentas) ){
	            
	            foreach ($rsPlanCuentas as $res){
	                
	                $_cls_plan_cuentas = new stdClass;
	                $_cls_plan_cuentas->id = $res->id_plan_cuentas;
	                $_cls_plan_cuentas->value = $res->codigo_plan_cuentas;
	                $_cls_plan_cuentas->label = $res->codigo_plan_cuentas.' | '.$res->nombre_plan_cuentas;
	                $_cls_plan_cuentas->nombre = $res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cls_plan_cuentas;
	            }
	            
	            echo json_encode($respuesta);
	            
	        }else{
	            
	            echo '[{"id":"","value":"Cuenta No Encontrada"}]';
	        }
	        
	    }
	}
	
	/***
	 * dc 2019-07-30
	 *
	 */
	public function autompletePlanCuentasByNombre(){
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    if(isset($_GET['term'])){
	        
	        $parametro = $_GET['term'];
	        
	        $columnas = "id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas";
	        $tablas = "public.plan_cuentas";
	        $where = " nombre_plan_cuentas LIKE '%$parametro%' AND nivel_plan_cuentas > 3";
	        $id = "codigo_plan_cuentas ";
	        $limit = "LIMIT 10";
	        
	        $rsPlanCuentas = $planCuentas->getCondicionesPag($columnas,$tablas,$where,$id,$limit);
	        
	        $respuesta = array();
	        
	        if(!empty($rsPlanCuentas) ){
	            
	            foreach ($rsPlanCuentas as $res){
	                
	                $_cls_plan_cuentas = new stdClass;
	                $_cls_plan_cuentas->id = $res->id_plan_cuentas;
	                $_cls_plan_cuentas->value = $res->nombre_plan_cuentas;
	                $_cls_plan_cuentas->label = $res->codigo_plan_cuentas.' | '.$res->nombre_plan_cuentas;
	                $_cls_plan_cuentas->nombre = $res->nombre_plan_cuentas;
	                $_cls_plan_cuentas->codigo = $res->codigo_plan_cuentas;
	                
	                $respuesta[] = $_cls_plan_cuentas;
	            }
	            
	            echo json_encode($respuesta);
	            
	        }else{
	            
	            echo '[{"id":"","value":"Cuenta No Encontrada"}]';
	        }
	        
	    }
	}
	
	
	
	public function InsertaDiarioTipo(){
	    
	    session_start();
	    $resultado = null;
	    
	    $core_diario_tipo = new CoreDiarioTipoCabezaModel();    
	    
	    $nombre_controladores = "CoreDiarioTipo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $core_diario_tipo->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
	    
	    
	    if (!empty($resultPer))
	    {
	        
	        if(isset($_POST['action']) && $_POST['action']=='ajax'){
	            
	            //datos de la vista
	            $_id_tipo_comprobantes         = $_POST['id_tipo_comprobantes'];	            
	            $_id_proveedores               = $_POST['id_proveedores'];
	            $_retencion_ccomprobantes      = $_POST['retencion_proveedor']; 
	            $_valor_letras                 = $_POST['valor_letras'];	            
	            $_concepto_ccomprobantes       = $_POST['concepto_ccomprobantes'];	            
	            $_fecha_ccomprobantes          = $_POST['fecha_ccomprobantes'];	            
	            $_referencia_doc_ccomprobantes = $_POST['referencia_ccomprobantes'];
	            $_id_forma_pago                = $_POST['id_forma_pago'];
	            $_num_cuenta_ban_ccomprobantes = $_POST['num_cuenta_ccomprobantes'];
	            $_num_cheque_ccomprobantes     = $_POST['num_cheque_ccomprobantes'];
	            $_observacion_ccomprobantes    = $_POST['observacion_ccomprobantes'];
	            $_id_usuarios                  = $_SESSION['id_usuarios'];
	            //comienza insertado de comprobante
	            
	            $funcion = "fn_con_agrega_comprobante";
	            
	            $parametros = "'$_id_usuarios',
                '$_id_tipo_comprobantes', 
                '$_retencion_ccomprobantes',
                '$_concepto_ccomprobantes',
                '$_valor_letras',
                '$_fecha_ccomprobantes',
                '$_id_forma_pago',
                '$_referencia_doc_ccomprobantes',
                '$_num_cuenta_ban_ccomprobantes',
                '$_num_cheque_ccomprobantes',
                '$_observacion_ccomprobantes',
                '$_id_proveedores' ";
	            
	            //print $parametros;
	            //die();
	            $ccomprobantes->setFuncion($funcion);
	            $ccomprobantes->setParametros($parametros);
	            $resultado=$ccomprobantes->llamafuncion();	            
	            
	            //print_r($parametros);
	            $respuesta='';
	            
	            if(!empty($resultado) && count($resultado) > 0)  {
	                
	                foreach ($resultado[0] as $k => $v)
	                    $respuesta = $v;
	            }
	            
	            echo json_encode(array('success'=>0,'mensaje'=>$respuesta));
	           
	        }
	        
	    }
	    else
	    {
	            echo "No tiene Permisos de Guardar Comprobante Contable";
	       
	    }
	    
	}
	
	
	
	public function generapdf(){
	    
	        require dirname(__FILE__).'\..\view\fpdf\fpdf.php';
	        
	        
	        $pdf = new FPDF();
	        
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(true, 20);
            
            //para ubicaciones
            
            $y = $pdf->GetY();
            //$x = $pdf->GetX();
            $mid_x = $pdf->GetPageWidth() / 2;
            $octavo_y = $pdf->GetPageHeight()/3;
           
            $tama�o = 10; //Tama�o de Pixel
            $level = 'L'; //Precisi�n Baja
            $framSize = 3; //Tama�o en blanco
            $contenido = 'pasante'; //Texto
            
           
            
            $pdf->SetFont('Arial','',50);
            //$y = $pdf->GetY();
            $pdf->SetXY(20, $y+90);
            $pdf->MultiCell(0,30, utf8_decode($contenido) ,1,'L');
            
	        $pdf->Output();
	    
	    
	}
	
    //para consultas con js    
	public function consultaTipoProcesos(){
	    
	    $modulos = new ModulosModel();
	    
	    $_id_modulos = (isset($_POST['id_modulos'])) ? $_POST['id_modulos'] : 0;
	    
	    $columnas = "id_tipo_procesos, nombre_tipo_procesos";
	    $tablas = "core_tipo_procesos"; 
	    $where = " diarios_tipo_procesos = 't' AND id_modulos = $_id_modulos ";
	    $id = "id_modulos";	    
	    
	    $rsTipoProceso = $modulos->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $cantidad = count($rsTipoProceso);
	    
	    echo json_encode(array('cantidad'=>$cantidad, 'data'=>$rsTipoProceso));
	}
    
	
	
}
?>