<?php

class ImpuestosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		$impuestos = new ModeloModel();
		$impuestos->setTable("tes_impuestos");
				
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "ImpuestosCxP";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $impuestos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Impuestos"
		        
		    ));
		    exit();
		}		    
			
		$rsImpuestos = $impuestos->getBy(" 1 = 1 ");		
				
		$this->view_tesoreria("Impuestos",array(
		    "resultSet" => $rsImpuestos
	
		));
			
	
	}
	
	/***
	 * dc 2019-05-06
	 * desc Autocompletar busqueda plan cuentas
	 * mod: Tesoreria
	 */
	public function AutocompletePlanCuentas(){
	    
	    $impuestos = new ModeloModel();
	    $impuestos->setTable("tes_impuestos");
	    
	    $buscador = (isset($_GET['term'])) ? $_GET['term'] : "";
	    
	    $columnas = "id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas";
	    
	    $tablas = "plan_cuentas";
	    
	    $where = "1 = 1
                AND nivel_plan_cuentas > 4
                AND ( codigo_plan_cuentas LIKE '$buscador%'
                	 OR nombre_plan_cuentas LIKE '$buscador%'
                	 )";
	    
	    $id = "codigo_plan_cuentas";
	    
	    $limit = "LIMIT 10";
	  
	    $rsPlanCuentas = $impuestos->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
	    
	    if( !empty($rsPlanCuentas) && count($rsPlanCuentas)>0 ){
	        
	        $respuesta = array();
	        
	        foreach ($rsPlanCuentas as $res){
	            
	            $_cls_respuesta = new stdClass;
	            $_cls_respuesta->id=$res->id_plan_cuentas;
	            $_cls_respuesta->value=$res->codigo_plan_cuentas;
	            $_cls_respuesta->label=$res->nombre_plan_cuentas.' | '.$res->codigo_plan_cuentas;
	            $_cls_respuesta->nombre=$res->nombre_plan_cuentas;
	            
	            $respuesta[] = $_cls_respuesta;
	        }
	        
	        echo json_encode($respuesta);
	        
	    }else{
	        
	        echo '[{"id":0,"value":"Datos no Encontrados"}]';
	    }
	    
	    
	}
	
	
	
	public function InsertaImpuestos(){
			
		session_start();
		
		$impuestos = new ModeloModel();
		$impuestos->setTable("tes_impuestos");
		
		$nombre_controladores = "ImpuestosCxP";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $impuestos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
		
		$resp = null;
		
		if(empty($resultPer)){
		    
		    echo 'Usuario no tiene permisos Agregar Impuestos';
		    return;
		}
		
		$buffer = null; 
		
		try {
		    
		    $_id_impuestos            = $_POST["id_impuestos"] ;
		    $_fuente_impuestos        = $_POST["fuente_impuestos"] ;
		    $_id_plan_cuentas         = $_POST["id_plan_cuentas"];
		    $_nombre_impuestos        = $_POST["nombre_impuestos"];
		    $_descripcion_impuestos   = $_POST["descripcion_impuestos"];
		    $_porcentaje_impuestos    = $_POST["porcentaje_impuestos"];
		    $_tipo_impuestos          = $_POST["tipo_impuestos"];
		    $_codigo_impuestos        = $_POST["codigo_impuestos"];
		    $_codretencion_impuestos  = $_POST["codretencion_impuestos"];
		    $_codigo_impuestos_texto  = $_POST["codigo_texto_impuestos"];
		    		    
		    
		    $error_php = error_get_last();
		    if( !empty($error_php) ){
		        $resp['icon'] = "warning";
		        throw new Exception("Variables No recibidas");
		    }
		    
		    $_operacion_impuestos = "-";
		    $_type_impuestos = "";
		    
		    //$mapTipoImpuesto   = array( 'iva' =>'iva', 'renta' => 'rent', 'isd' => 'isd');
		    //$_tipoImpuesto     = "";
		    //$_tipoImpuesto = (array_key_exists($res->tipo_impuestos, $mapTipoImpuesto) ) ?  $mapTipoImpuesto["$res->tipo_impuestos"] : "";
		    
		    if( $_id_impuestos == 0){
		        
    		    /** validacion de plan de cuentas **/
    		    $_columna1    = " 1";
    		    $_tablas1     = " tes_impuestos";
    		    $_where1      = " id_plan_cuentas = $_id_plan_cuentas ";
    		    $rsConsulta1  = $impuestos->getCondicionesmenosid($_columna1, $_tablas1, $_where1);
    		    
    		    if( !empty($rsConsulta1) ){
    		        $resp['icon'] = "info";
    		        throw new Exception("Cuenta ya se encuentra Relacionada");
    		    }
    		    
		    }
		    
		    
		    
		    if( $_fuente_impuestos == "compra" ){
		        
		        if( $_tipo_impuestos == "retencion" ){
		            
		            $_operacion_impuestos = "-";
		            $_porcentaje_impuestos = (-1)*$_porcentaje_impuestos;
		            
		            if( strtolower($_codigo_impuestos_texto)  == "iva" ){
		                $_type_impuestos = "retiva";
		            }elseif (strtolower($_codigo_impuestos_texto)  == "renta" ){
		                $_type_impuestos = "ret";
		            }elseif ( strtolower($_codigo_impuestos_texto)  == "isd" ){
		                $_type_impuestos = "isd";
		            }
		            
		        }elseif( $_tipo_impuestos == "iva" ){
		            
		            $_operacion_impuestos = "+";
		            $_porcentaje_impuestos = (+1)*$_porcentaje_impuestos;
		            $_type_impuestos = "iva";
		            $_codigo_impuestos  = "";
		            $_codretencion_impuestos = "";		            
		        }
		        
		    }
		    
		    $codSysOld = ""; //es una variable para llenar todos los parametros de la funcion si los datos son del sistema nuevo van vacios .
		    
		  		    
		    $funcion = "tes_ins_impuestos";
		    $mensaje = "";
		    
		    $parametros = " $_id_impuestos, '$_id_plan_cuentas','$_nombre_impuestos', '$_porcentaje_impuestos', '$_type_impuestos',";
		    $parametros .= " '$_codigo_impuestos', '$_operacion_impuestos', '$_fuente_impuestos', '$_codretencion_impuestos', '$_descripcion_impuestos', '$codSysOld' ";
		    
		    
		    if($_id_impuestos == 0){		        
		        
		        $queryInsertImpuestos = $impuestos->getconsultaPG($funcion, $parametros);
		        $resultado = $impuestos->llamarconsultaPG($queryInsertImpuestos);
		        
		        if( $resultado[0] != null ){
		            $resp['icon'] = "success";
		            $resp['mensaje'] = " Impuesto Registrado Correctamente";
		        }else
		            $mensaje = "Error al Ingresar Impuesto";
		            
		        
		    }elseif ($_id_impuestos > 0){
		        
		        $queryInsertImpuestos = $impuestos->getconsultaPG($funcion, $parametros);
		        $resultado = $impuestos->llamarconsultaPG($queryInsertImpuestos);
		        
		        if( $resultado[0] == 1 ){
		            $resp['icon'] = "success";
		            $resp['mensaje'] = " Impuesto Actualizado Correctamente";
		        }else 
		            $mensaje = "Error al actualizar Impuesto";
		        
		        
		    }
		    
		    $error_pg = pg_last_error();
		    
		    if(!empty($error_pg)){
		        $resp['icon'] = "warning";
		        throw new Exception($mensaje);
		    }		    
		    
		} catch (Exception $e) {
		    
		    $buffer =  error_get_last();
		    $resp['icon'] = isset($resp['icon']) ? $resp['icon'] : "error";
		    $resp['mensaje'] = $e->getMessage();
		    $resp['msgServer'] = $buffer; //buscar guardar buffer y guaradr en variable
		    $resp['estatus'] = "ERROR";
		}
		
		error_clear_last();
		if (ob_get_contents()) ob_end_clean();
				
		echo json_encode($resp);
		
	}
	
	
	
	public function paginate($reload, $page, $tpages, $adjacents, $funcion = "") {
	    
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
	
	/***
	 * return: json
	 * title: editBancos
	 * fcha: 2019-04-22
	 */
	public function editImpuesto(){
	    
	    session_start();
	    $impuestos = new ModeloModel();
	    $impuestos->setTable("tes_impuestos");
	    
	    $nombre_controladores = "ImpuestosCxP";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $impuestos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if(empty($resultPer)){
	        
	        echo 'Usuario no tiene permisos - Actualizar Impuestos';
	        return;
	    }
	    
	    if(isset($_POST["id_impuestos"])){
	        
	        $id_impuestos = (int)$_POST["id_impuestos"];
	        
	        $query = " SELECT imp.id_impuestos, pc.codigo_plan_cuentas, pc.id_plan_cuentas, imp.codigo_impuestos, imp.fuente_impuestos,";
            $query .= " imp.nombre_impuestos, imp.porcentaje_impuestos, imp.tipo_impuestos, imp.codretencion_impuestos, imp.descripcion_impuestos";
            $query .= " FROM tes_impuestos imp ";
            $query .= " INNER JOIN plan_cuentas pc ON imp.id_plan_cuentas = pc.id_plan_cuentas ";
            $query .= " WHERE 1=1 AND imp.id_impuestos = '$id_impuestos' ";
	      
	        $resultado  = $impuestos->enviaquery($query);
	        
	        echo json_encode(array('data'=>$resultado));
	        
	    }
	    
	    
	}
	
	
	/***
	 * return: json
	 * title: delBancos
	 * fcha: 2019-05-06
	 */
	public function delImpuesto(){
	    
	    session_start();
	    $impuestos = new ImpuestosModel();
	    $nombre_controladores = "ImpuestosCxP";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $impuestos->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer)){	        
	        
	        if(isset($_POST["id_impuestos"])){
	            
	            $id_bancos = (int)$_POST["id_impuestos"];
	            
	            $resultado  = $impuestos->eliminarBy(" id_impuestos ",$id_bancos);
	           
	            if( $resultado > 0 ){
	                
	                echo json_encode(array('data'=>$resultado));
	                
	            }else{
	                
	                echo $resultado;
	            }
	            
	            
	            
	        }
	        
	        
	    }else{
	        
	        echo "Usuario no tiene permisos-Eliminar";
	    }
	    
	    
	    
	}
	
	
	public function consultaImpuestos(){
	    
	    session_start();
	    
	    $impuestos = new ModeloModel();
	    
	    $where_to="";
	    $columnas  = " imp.id_impuestos, pc.codigo_plan_cuentas, pc.nombre_plan_cuentas, imp.nombre_impuestos, imp.porcentaje_impuestos,
                       to_char(imp.creado, 'YYYY-MM-DD') AS creado, imp.tipo_impuestos ";
	    
	    $tablas    = " tes_impuestos imp 
                    INNER JOIN plan_cuentas pc
                    ON imp.id_plan_cuentas = pc.id_plan_cuentas ";
	    
	    $where     = " 1 = 1";
	    
	    $id        = "imp.nombre_impuestos";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';	    
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND ( imp.nombre_impuestos LIKE '".$search."%' OR pc.codigo_plan_cuentas LIKE '".$search."%' OR imp.tipo_impuestos LIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet = $impuestos->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet = $impuestos->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);	  
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_impuestos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 15px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Cod. Cuenta</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Afectación Plan Cuentas</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Tipo</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Creado</th>';
	            
	            /*para administracion definir administrador MenuOperaciones Edit - Eliminar*/
	                
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            // para definir el tipo de impuesto	            
	            $mapTipoImpuesto   = array( 'retiva' =>'RETENCION IVA', 'ret' => 'RETENCION FUENTE', 'iva' => 'IVA');
	            $_tipoImpuesto     = "";	 
	            
	            foreach ($resultSet as $res)
	            {
	                $_tipoImpuesto = (array_key_exists($res->tipo_impuestos, $mapTipoImpuesto) ) ?  $mapTipoImpuesto["$res->tipo_impuestos"] : "N/D";
	                
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 14px;">'.$i.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->codigo_plan_cuentas.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_plan_cuentas.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_impuestos.'</td>';
	                $html.='<td style="font-size: 14px;">'.$_tipoImpuesto.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->creado.'</td>';
	               
	                /*comentario up */
	                
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editImpuestos('.$res->id_impuestos.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                    $html.='<td style="font-size: 18px;">
                            <a onclick="delImpuestos('.$res->id_impuestos.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
	                    
	               
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaImpuestos").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay empleados registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	       
	    }
	    
	     
	}
	
	/**
	 * mod: tesoreria
	 * title: cargar datos estado bancos
	 * ajax: si
	 * dc:2019-04-22
	 */
	public function cargaEstadoBancos(){
	    
	    $bancos = null;
	    $bancos = new BancosModel();
	    
	    $query = " SELECT id_estado,nombre_estado FROM estado WHERE tabla_estado = 'tes_bancos' ORDER BY nombre_estado";
	    
	    $resulset = $bancos->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	
	public function verArray(){
	    
	    
	    $arr1 = array("valor1"=>23,"valor2"=>24);
	    $arr2 = array("valor1"=>12,"valor2"=>21);
	    $array1 = array("iva"=>$arr1,"rete"=>$arr2);
	    $array2 = array("valor1"=>1,"valor2"=>2);
	    $array3 = array("valor1"=>1,"valor2"=>2);
	    $arrayTot = array("arr1"=>$array1,"arr2"=>$array2,"arr3"=>$array3);
	    
	    echo json_encode($arrayTot);
	}
	
}
?>