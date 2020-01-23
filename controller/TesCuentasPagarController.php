<?php

class TesCuentasPagarController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	    
	    $Productos = new ProductosModel();
	
		$bancos = new BancosModel();
				
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "compras";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $Productos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Bancos"
		        
		    ));
		    exit();
		}		
		
		$this->view_tesoreria("IngresoTransacciones",array(
		));
			
	
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
	
	public function buscaProveedores(){
	    
	    $Proveedores = new ProveedoresModel();
	    $respuesta   = array(); 
	    
	    $busqueda = ( isset($_POST['buscador']) ) ? $_POST['buscador'] : "";
	    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	    
	    $columnas1 = " aa.id_tipo_proveedores,aa.id_proveedores, aa.nombre_proveedores, aa.identificacion_proveedores, aa.direccion_proveedores, aa.celular_proveedores";
	    $tablas1   = " proveedores aa
    	    INNER JOIN tes_tipo_proveedores bb ON aa.id_tipo_proveedores = bb.id_tipo_proveedores";
	    $where1    = " bb.nombre_tipo_proveedores = 'PAGO PROVEEDORES'";
	    $id1       = " aa.nombre_proveedores";
	    
	    if( strlen($busqueda) > 0 ){
	        $where1 .= " AND ( aa.identificacion_proveedores ILIKE '$busqueda%' OR aa.nombre_proveedores ILIKE '$busqueda%' ) ";
	    }
	    
	    $resultSet = $Proveedores->getCantidad("*", $tablas1, $where1);
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet = $Proveedores->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    	    
	    $error = error_get_last();
	    if( !empty($error) ){
	        echo $error['message'];
	        exit();
	    }
	    $htmlTr = "";
	    $i = 0;
	    foreach ($resultSet as $res){
	        $i++;
	        $btonSelect = "<button onclick=\"SelecionarProveedor(this)\" value=\"$res->id_proveedores\" class=\"btn btn-default\"> 
                        <i aria-hidden=\"true\" class=\"fa fa-external-link\"></i> </button>";
	        $htmlTr    .= "<tr>";
	        $htmlTr    .= "<td>" . $i . "</td>";
	        $htmlTr    .= "<td>" . $res->identificacion_proveedores . "</td>";
	        $htmlTr    .= "<td>" . $res->nombre_proveedores . "</td>";	        
	        $htmlTr    .= "<td>" . $btonSelect . "</td>";
	        $htmlTr    .= "</tr>";	        
	        	        
	    }
	    
	    $respuesta['filas']    = $htmlTr;
	    
	    $htmlPaginacion  = '<div class="table-pagination pull-right">';
	    $htmlPaginacion .= ''. $this->paginate("index.php", $page, $total_pages, $adjacents,"loadProveedores").'';
	    $htmlPaginacion .= '</div>';
	    
	    $respuesta['paginacion'] = $htmlPaginacion;
	    $respuesta['cantidadDatos'] = $cantidadResult;
	    
	    echo json_encode( $respuesta );
	}

	
	/** INGRESAR UN NUEVO LOTE **/
	public function RegistrarLote(){
	    	    
	    if( empty( $_SESSION ) ){
	        session_start();
	    }
	    	    
	    $lote = new LoteModel();
	    
	    $respuesta = array();
	    	   
	    try {
	        
	        $_id_usuarios = $_SESSION['id_usuarios'];
	        
	        $_id_lote      = ( isset($_POST['id_lote']) ) ? $_POST['id_lote'] : 0;
	        $_nombre_lote  = ( isset($_POST['nombre_lote']) ) ? $_POST['nombre_lote'] : "";
	        $_descripcion_lote = "Mod Cuentas Pagar";
	        $_id_frecuencia = 1; //cambiara si en la tabla frecuencia lote cambia 
	        
	        $funcion = "tes_genera_lote";
	        $parametros = "'$_nombre_lote','$_descripcion_lote', $_id_frecuencia , $_id_usuarios";
	        $queryFuncion  = $lote->getconsultaPG($funcion, $parametros);
	        
	        $resultado = $lote->llamarconsultaPG($queryFuncion);
	        
	        $pgError = pg_last_error();
	        
	        if( !empty($pgError) ){ throw new Exception($pgError); }
	        
	        $_id_lote  = $resultado[0];
	        $respuesta['icon']     = "success";
	        $respuesta['respuesta']= "OK";
	        $respuesta['mensaje']  = "lote generado";
	        $respuesta['id_lote']  = $_id_lote;
	        
	        echo json_encode($respuesta);	        
	        
	    } catch (Exception $e) {
	        
	        $respuesta['icon']     = "error";
	        $respuesta['respuesta']  = "ERROR";
	        $respuesta['mensaje']  = $e->getMessage();
	        echo json_encode($respuesta);
	    }	    
	   
	}
	
	/**
	 * funcion que permite seleci9onar el proveedor en el modal 
	 */
	public function SelecionarProveedor(){
	    
	    $Proveedores =  new ProveedoresModel();
	    
	    $_id_proveedor = $_POST['id_proveedores'];	    
	   	    
	    $error = error_get_last();
	    if(!empty($error)){ echo "proveedor no seleccionado"; exit();}
	    
	    $columnas1 = " id_proveedores, nombre_proveedores, identificacion_proveedores, direccion_proveedores, celular_proveedores";
	    $tablas1   = " proveedores";
	    $where1    = " id_proveedores = $_id_proveedor";
	    $id1       = " id_proveedores";
	    
	    $consulta1 = $Proveedores->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    echo json_encode(array("data"=>$consulta1));
	}
	
	/**
	 * funcion que envia el secuencial de documento ..consecutivos
	 */
	public function getSecuencialDocumento(){
	    
	    $Consecutivos = new ConsecutivosModel();
	    	    
	    $query = "SELECT id_consecutivos, LPAD(valor_consecutivos::TEXT,espacio_consecutivos,'0') AS secuencial FROM consecutivos
                WHERE id_entidades = 1 AND nombre_consecutivos='CxP'";
	    
	    $resulset = $Consecutivos->enviaquery($query);
	    
	    echo json_encode(array('data'=>$resulset));	        
	   
	}
	
	/**
	 * mod: tesoreria
	 * title: cargaTipoDocumento
	 * ajax: si
	 * dc:2019-05-09
	 * desc: carga todos los tipos de documento
	 */
	public function getTipoDocumento(){
	    
	    $tipoDocumento = null;
	    $tipoDocumento = new TipoDocumentoModel();
	    
	    $query = " SELECT id_tipo_documento, abreviacion_tipo_documento, nombre_tipo_documento
                FROM public.tes_tipo_documento
                WHERE 1 = 1";
	    
	    $resulset = $tipoDocumento->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	public function buscaImpuestos(){
	    
	    $impuestos = new ImpuestosModel();
	    $respuesta   = array();
	    
	    $busqueda = ( isset($_POST['buscador']) ) ? $_POST['buscador'] : "";
	    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	    
	    $colImpuestos  = " aa.id_impuestos,aa.codigo_impuestos,aa.nombre_impuestos,bb.id_plan_cuentas, bb.nombre_plan_cuentas, bb.codigo_plan_cuentas,
               aa.porcentaje_impuestos, aa.operacion_impuestos";
	    $tabImpuestos  = " tes_impuestos aa
	           LEFT JOIN plan_cuentas bb ON bb.id_plan_cuentas = aa.id_plan_cuentas";
	    $wheImpuestos  = " 1 = 1 ";
	    $idIMpuestos   = " aa.id_impuestos";
	    //$rsImpuestos   = $impuestos->getCondiciones($colImpuestos, $tabImpuestos, $wheImpuestos, $idIMpuestos);
	         
	    if( strlen($busqueda) > 0 ){
	        $wheImpuestos .= " AND ( aa.nombre_impuestos ILIKE '$busqueda%' OR aa.codigo_impuestos ILIKE '$busqueda%' OR bb.codigo_plan_cuentas ILIKE '$busqueda%' ) ";
	    }
	    
	    $resultSet = $impuestos->getCantidad("*", $tabImpuestos, $wheImpuestos);
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet = $impuestos->getCondicionesPag($colImpuestos, $tabImpuestos, $wheImpuestos, $idIMpuestos, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    $error = error_get_last();
	    if( !empty($error) ){
	        echo $error['message'];
	        exit();
	    }
	    $htmlTr = "";
	    $i = 0;
	    foreach ($resultSet as $res){
	        $i++;
	        $btonSelect = "<button onclick=\"AgregarImpuesto(this)\" value=\"$res->id_impuestos\" class=\"btn btn-default\">
                        <i aria-hidden=\"true\" class=\"fa fa-external-link\"></i> </button>";
	        $htmlTr    .= "<tr>";
	        $htmlTr    .= "<td>" . $i . "</td>";
	        $htmlTr    .= "<td>" . $res->codigo_impuestos . "</td>";
	        $htmlTr    .= "<td>" . $res->nombre_impuestos . "</td>";
	        $htmlTr    .= "<td>" . $res->codigo_plan_cuentas . "</td>";
	        $htmlTr    .= "<td>" . $btonSelect . "</td>";
	        $htmlTr    .= "</tr>";
	        
	    }
	    
	    $respuesta['filas']    = $htmlTr;
	    
	    $htmlPaginacion  = '<div class="table-pagination pull-right">';
	    $htmlPaginacion .= ''. $this->paginate("index.php", $page, $total_pages, $adjacents,"loadImpuestos").'';
	    $htmlPaginacion .= '</div>';
	     
	    $respuesta['paginacion'] = $htmlPaginacion;
	    $respuesta['cantidadDatos'] = $cantidadResult;
	    
	    echo json_encode( $respuesta );
	}
	
	public function AgregarImpuesto(){
	    
	    $impuestos = new ImpuestosModel();
	    $respuesta   = array();
	    
	    try {
	        
	        $id_lote = $_POST['id_lote'];
	        $base_compras   = $_POST['base_compra'];
	        $_id_impuestos  = $_POST['id_impuestos'];
	        
	        /** realizar validacion de impuesto **/
	        $colValidacion = " 1 ";
	        $tabValidacion = " public.tes_cuentas_pagar_impuestos";
	        $WheValidacion = " id_impuestos = $_id_impuestos AND id_lote = $id_lote";
	        $idValidacion  = " id_impuestos";
	        $rsValidacion  = $impuestos->getCondiciones($colValidacion, $tabValidacion, $WheValidacion, $idValidacion);
	        
	        if(!empty($rsValidacion)){
	            /** si ingresa es a causa de que ya existe un  impuesto **/
	            $respuesta['respuesta'] = 'ERROR';
	            $respuesta['icon'] = 'info';
	            $respuesta['texto']= 'Impuesto ya se encuentra ingresado';
	            throw new Exception();
	        }
	        
	        $colImpuestos = " id_impuestos, nombre_impuestos, porcentaje_impuestos, tipo_impuestos, operacion_impuestos";
	        $tabIMpuestos = " public.tes_impuestos";
	        $wheImpuestos = " id_impuestos = $_id_impuestos";
	        $idImpuestos  = " nombre_impuestos";
	        
	        $rsImpuesto   = $impuestos->getCondiciones($colImpuestos, $tabIMpuestos, $wheImpuestos, $idImpuestos);
	        
	        if( empty($rsImpuesto) ){
	            $respuesta['respuesta'] = 'ERROR';
	            $respuesta['icon'] = 'warning';
	            $respuesta['texto']= 'Impuesto no encontrado';
	            throw new Exception();
	        }
	        
	        $_tipo_impuesto = $rsImpuesto[0]->tipo_impuestos;
	        $_pctge_impuesto = $rsImpuesto[0]->porcentaje_impuestos;
	        
	        /** todo impuesto es dividido para 100 **/
	        $_pctge_impuesto = $_pctge_impuesto/100;
	        
	        $totalImpuesto = 0;
	        $totalValor = 0;
	        $naturalezaImpuesto = 'debe'; //si el impuesto va para el debe o el haber
	        
	        /** una vez validado se comienza con los procesos de impuestos de compra **/
	        
	        if( strtoupper( $_tipo_impuesto )  == "IVA" ){
	            
	            $totalValor    = $base_compras;
	            $totalImpuesto = ( $totalValor * $_pctge_impuesto );
	            $naturalezaImpuesto = "debe";
	            
	        }else if( strtoupper( $_tipo_impuesto )  == "RETIVA" ){
	            
	            $colImpCxp = " aa.valor_cuentas_pagar_impuestos ";
	            $tabImpCxp = " public.tes_cuentas_pagar_impuestos aa
	               INNER JOIN public.tes_impuestos bb ON bb.id_impuestos = aa.id_impuestos";
	            $WheImpCxp = " aa.id_lote = $id_lote AND UPPER(bb.tipo_impuestos) = 'IVA' ";
	            $idImpCxp  = " aa.id_lote ";
	            $rsImpCxp  = $impuestos->getCondiciones($colImpCxp, $tabImpCxp, $WheImpCxp, $idImpCxp);
	            
	            if( empty($rsImpCxp) ){
	                $respuesta['respuesta'] = 'ERROR';
	                $respuesta['icon'] = 'info';
	                $respuesta['texto']= 'Necesita una fuente de retencion';
	                throw new Exception();
	            }
	            
	            $totalValor = $rsImpCxp[0]->valor_cuentas_pagar_impuestos;
	            $totalImpuesto = ( $totalValor * $_pctge_impuesto );
	            $naturalezaImpuesto = "haber";
	            
	        }else if( strtoupper( $_tipo_impuesto )  == "RET" ){
	            
	            $totalValor    = $base_compras;
	            $totalImpuesto = ( $totalValor * $_pctge_impuesto );
	            $naturalezaImpuesto = "haber";
	            
	        }else{
	            
	            
	            $respuesta['respuesta'] = 'ERROR';
	            $respuesta['icon'] = 'warning';
	            $respuesta['texto']= 'Datos Impuesto no definido';
	            throw new Exception();
	        }
	        
	        /** se relaciona el impuesto a la cuenta x pagar **/
	        $QueryInsImpuesto = "INSERT INTO tes_cuentas_pagar_impuestos
    	    (id_lote, id_impuestos, base_cuentas_pagar_impuestos,valor_base_cuentas_pagar_impuestos, valor_cuentas_pagar_impuestos, naturaleza_cuentas_pagar_impuestos)
    	    VALUES($id_lote, $_id_impuestos, $base_compras, $totalValor, $totalImpuesto, '$naturalezaImpuesto')";
	        
	        $resultado = $impuestos->executeInsertQuery($QueryInsImpuesto);
	        
	        if( $resultado == -1 ){
	            $respuesta['respuesta'] = 'ERROR';
	            $respuesta['icon'] = 'error';
	            $respuesta['texto']= 'No fue posible relacionar el impuesto seleccionado';
	            throw new Exception();
	        }
	        
	        $respuesta['respuesta'] = 'OK';
	        $respuesta['icon'] = 'success';
	        $respuesta['texto']= 'Impuesto ingresado correctamente';
	        
	        /** enviar valores de impuesto que genera **/
	        $col1 = " id_cuentas_pagar_impuestos,base_cuentas_pagar_impuestos, valor_base_cuentas_pagar_impuestos, valor_cuentas_pagar_impuestos, id_lote, id_impuestos";
	        $tab1 = " public.tes_cuentas_pagar_impuestos ";
	        $Whe1 = " id_lote = $id_lote ";
	        $id1  = " creado ";
	        $rsConsulta1  = $impuestos->getCondiciones($col1, $tab1, $Whe1, $id1);
	        
	        if( !empty($rsConsulta1) ){
	            $_total = $base_compras;
	            $_total_impuesto = 0;
	            $_saldo_documento = 0;
	            foreach ($rsConsulta1 as $res) {
	                $_total_impuesto += $res-> valor_cuentas_pagar_impuestos;
	            }
	            $_saldo_documento = $_total + $_total_impuesto;
	            
	            $respuesta['total_impuesto'] = $_total_impuesto;
	            $respuesta['saldo_impuesto'] = $_saldo_documento;
	        }
	        
	        echo json_encode($respuesta);
	       
	        
	    } catch (Exception $e) {
	        
	        echo json_encode($respuesta);
	        exit();
	    }
	    
	    
	  
	}
	
	
	
	public function CargaImpuestos(){
	    
	    $impuestos = new ImpuestosModel();
	    $_id_lote = $_POST['id_lote'];
	    
	    $respuesta = array();
	    
	    $col1 = " aa.id_cuentas_pagar_impuestos, bb.id_impuestos, cc.codigo_plan_cuentas, bb.nombre_impuestos, bb.porcentaje_impuestos,
	       aa.base_cuentas_pagar_impuestos, aa.valor_cuentas_pagar_impuestos,aa.valor_base_cuentas_pagar_impuestos,bb.tipo_impuestos";
	    $tab1 = " tes_cuentas_pagar_impuestos aa
    	    INNER JOIN tes_impuestos bb ON bb.id_impuestos = aa.id_impuestos
    	    LEFT JOIN plan_cuentas cc ON cc.id_plan_cuentas = bb.id_plan_cuentas";
	    $Whe1 = " aa.id_lote = $_id_lote ";
	    $id1  = " aa.creado ";
	    $rsConsulta1  = $impuestos->getCondiciones($col1, $tab1, $Whe1, $id1);
	    
	    $error = error_get_last();
	    if( !empty($error) ){
	        echo $error['message'];
	        exit();
	    }
	    
	    $cantidadResult = sizeof($rsConsulta1);
	    
	    $htmlTr = "";
	    $i = 0;
	    foreach ($rsConsulta1 as $res){
	        $i++;
	        $btonSelect = "<button onclick=\"RemoveImpuesto(this)\" value=\"$res->id_cuentas_pagar_impuestos\" class=\"btn btn-default\">
                        <i aria-hidden=\"true\" class=\"fa fa-trash text-danger\"></i> </button>";
	        $htmlTr    .= "<tr>";
	        $htmlTr    .= "<td>" . $i . "</td>";
	        $htmlTr    .= "<td>" . $res->id_cuentas_pagar_impuestos . "</td>";
	        $htmlTr    .= "<td>" . $res->id_impuestos . "</td>";
	        $htmlTr    .= "<td>" . $res->codigo_plan_cuentas . "</td>";
	        $htmlTr    .= "<td>" . $btonSelect . "</td>";
	        $htmlTr    .= "</tr>";
	        
	    }
	    
	    $respuesta['filas']    = $htmlTr;
	    
	    $htmlPaginacion  = '<div class="table-pagination pull-right">';
	    $htmlPaginacion .= ''. $this->paginate("index.php", 1, 1, 20,"cargaImpuestos").'';
	    $htmlPaginacion .= '</div>';
	    
	    $respuesta['paginacion'] = $htmlPaginacion;
	    $respuesta['cantidadDatos'] = $cantidadResult;
	    
	    echo json_encode( $respuesta );	   
	    
	}
	
	public function DistribucionTransaccionCompras(){
	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    $_id_lote = $_POST['id_lote'];
	    $_base_compras = $_POST['base_compras'];
	    
	    $respuesta = array();
	    
	    $orden_insert  = 1;
	    $valor_proveedores = $_base_compras;
	    
	    try {
	        
	        $col = " 1 ";
	        $tab = " tes_distribucion_cuentas_pagar ";
	        $whe = " id_lote =  $_id_lote";
	        $id  = " creado";
	        $rsConsulta = $cuentasPagar->getCondiciones( $col, $tab, $whe, $id);
	        
	        if( !empty( $rsConsulta )){
	            
	            $respuesta['estatus'] = 'OK';	            
	            echo json_encode($respuesta);
	            exit();
	        }
	        
	        
	        $cuentasPagar->beginTran();
	        
	        $col1 = " imp.tipo_impuestos,imp.id_plan_cuentas, cxp_imp.id_lote, cxp_imp.valor_cuentas_pagar_impuestos, cxp_imp.naturaleza_cuentas_pagar_impuestos ";
	        $tab1 = " tes_cuentas_pagar_impuestos cxp_imp
    	       INNER JOIN tes_impuestos imp ON imp.id_impuestos = cxp_imp.id_impuestos";
	        $whe1 = " cxp_imp.id_lote =  $_id_lote";
	        $id1  = " imp.creado";
	        $rsConsulta1 = $cuentasPagar->getCondiciones( $col1, $tab1, $whe1, $id1);
	        
	        $QueryIns1 = " INSERT INTO tes_distribucion_cuentas_pagar( id_lote, id_plan_cuentas, tipo_distribucion_cuentas_pagar, debito_distribucion_cuentas_pagar,
			             credito_distribucion_cuentas_pagar, ord_distribucion_cuentas_pagar )
		              VALUES($_id_lote, null, 'COMPRA' , $_base_compras, 0.00, $orden_insert)";
	        
	        $cuentasPagar->executeInsertQuery($QueryIns1);
            
	        $_naturaleza_impuesto = "";
	        $_valor_credito = 0.00;
	        $_valor_debito  = 0.00;
	        $_id_plan_cuentas = 'null';
	        foreach ($rsConsulta1 as $res) {
	            $orden_insert ++;
	            
	            $_naturaleza_impuesto = $res->naturaleza_cuentas_pagar_impuestos;
	            $_id_plan_cuentas = !empty( $res->id_plan_cuentas ) ? $res->id_plan_cuentas : 'null';
	            
	            if( strtoupper($_naturaleza_impuesto) == 'HABER'){
	                
	                $_valor_credito = $res->valor_cuentas_pagar_impuestos;
	                $_valor_debito  = 0.00;
	                $QueryInsImp = " INSERT INTO tes_distribucion_cuentas_pagar( id_lote, id_plan_cuentas, tipo_distribucion_cuentas_pagar, debito_distribucion_cuentas_pagar,
			             credito_distribucion_cuentas_pagar, ord_distribucion_cuentas_pagar )
		              VALUES($_id_lote, $_id_plan_cuentas, 'IMPTOS' ,abs($_valor_debito) , abs($_valor_credito)  , $orden_insert)";
	                
	                $cuentasPagar->executeInsertQuery($QueryInsImp);
	            }
	            
	            if( strtoupper($_naturaleza_impuesto) == 'DEBE'){
	                
	                $_valor_credito = 0.00;
	                $_valor_debito  = $res->valor_cuentas_pagar_impuestos;
	                $QueryInsImp = " INSERT INTO tes_distribucion_cuentas_pagar( id_lote, id_plan_cuentas, tipo_distribucion_cuentas_pagar, debito_distribucion_cuentas_pagar,
			             credito_distribucion_cuentas_pagar, ord_distribucion_cuentas_pagar )
		              VALUES($_id_lote, $_id_plan_cuentas, 'IMPTOS' , abs($_valor_debito), abs($_valor_credito)  , $orden_insert)";
	                
	                $cuentasPagar->executeInsertQuery($QueryInsImp);
	            }
	            
	            /** para obtener valor para  proveedores **/
	            $valor_proveedores +=  $res->valor_cuentas_pagar_impuestos;
	        }       
	        
	        
	        
	        $QueryIns1 = " INSERT INTO tes_distribucion_cuentas_pagar( id_lote, id_plan_cuentas, tipo_distribucion_cuentas_pagar, debito_distribucion_cuentas_pagar,
			             credito_distribucion_cuentas_pagar, ord_distribucion_cuentas_pagar )
		              VALUES($_id_lote, null, 'COMPRA' , 0.00, $valor_proveedores, $orden_insert)";
	        
	        $cuentasPagar->executeInsertQuery($QueryIns1);
	        
	        $error = pg_last_error();
	        
	        if( !empty($error)){ throw new Exception();}
	                    
	        $cuentasPagar->endTran("COMMIT");
	        
	        $respuesta['estatus'] = 'OK';
	        
	        echo json_encode($respuesta);
	        exit();
	        
	    } catch (Exception $e) {
	        
	        $cuentasPagar->endTran();
	        
	        $respuesta['respuesta'] = 'ERROR';
	        
	        echo json_encode($respuesta);
	        exit();
	    }
	    	  	    
	}
	
	public function cargaDistribucion(){
	    
	    $cuentasPagar = new CuentasPagarModel();
	    $respuesta = array();
	    
	    $_id_lote = $_POST['id_lote'];
	    
	    $col = "  aa.id_distribucion_cuentas_pagar, aa.id_lote, pc.id_plan_cuentas, pc.codigo_plan_cuentas, pc.nombre_plan_cuentas,
        	    aa.tipo_distribucion_cuentas_pagar, round(aa.debito_distribucion_cuentas_pagar,2) AS debito_distribucion,
        	    round(aa.credito_distribucion_cuentas_pagar,2) AS credito_distribucion, aa.ord_distribucion_cuentas_pagar, aa.referencia_distribucion_cuentas_pagar ";
	    $tab = " tes_distribucion_cuentas_pagar aa
        	    LEFT JOIN plan_cuentas pc
        	    ON aa.id_plan_cuentas = pc.id_plan_cuentas ";
	    $whe = " aa.id_lote =  $_id_lote";
	    $id  = " aa.ord_distribucion_cuentas_pagar";
	    $rsConsulta = $cuentasPagar->getCondiciones( $col, $tab, $whe, $id);
	    
	    $error = error_get_last();
	    if( !empty($error) ){
	        echo $error['message'];
	        exit();
	    }
	    
	    $cantidadResult = sizeof($rsConsulta);
	    
	    $htmlTr = "";
	    $i = 0;
	    foreach ($rsConsulta as $res){
	        $i++;	         
	        
	        $htmlTr.='<tr id="tr_'.$res->id_distribucion_cuentas_pagar.'">';
	        $htmlTr.='<td style="font-size: 12px;">'.$i.'</td>';
	        $htmlTr.='<td style="font-size: 12px;"><input type="text" class="form-control input-sm distribucion" name="mod_dis_referencia" value="'.$res->referencia_distribucion_cuentas_pagar.'"></td>';
	        $htmlTr.='<td style="font-size: 12px;"><input type="text" class="form-control input-sm distribucion distribucion_autocomplete" name="mod_dis_codigo" value="'.$res->codigo_plan_cuentas.'"></td>';
	        $htmlTr.='<td style="font-size: 12px;"><input type="text" style="border: 0;" class="form-control input-sm" value="'.$res->nombre_plan_cuentas.'" name="mod_dis_nombre">
                    <input type="hidden" name="mod_dis_id_plan_cuentas" value="'.$res->id_plan_cuentas.'" ></td>';
	        $htmlTr.='<td style="font-size: 12px;">'.$res->tipo_distribucion_cuentas_pagar.'</td>';
	        $htmlTr.='<td style="font-size: 12px;">'.$res->debito_distribucion.'</td>';
	        $htmlTr.='<td style="font-size: 12px;">'.$res->credito_distribucion.'</td>';
	        $htmlTr.='</tr>';
	        	        
	    }
	    
	    $respuesta['filas']    = $htmlTr;
	    
	    $htmlPaginacion  = '<div class="table-pagination pull-right">';
	    $htmlPaginacion .= ''. $this->paginate("index.php", 1, 1, 20,"cargaImpuestos").'';
	    $htmlPaginacion .= '</div>';
	    
	    $respuesta['paginacion'] = $htmlPaginacion;
	    $respuesta['cantidadDatos'] = $cantidadResult;
	    
	    echo json_encode( $respuesta );	
	      
	    
	}
	
	public function InsertaDistribucion(){
	    	    	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    //validar respuesta
	    $respuesta = true;
	    
	    $cuentasPagar->beginTran();
	    
	    $datos = json_decode($_POST['lista_distribucion']);
	    
	    foreach ($datos as $data){
	        
	        $columnas = "id_plan_cuentas = ".$data->id_plan_cuentas.",
                        referencia_distribucion_cuentas_pagar = '".$data->referencia_distribucion."'";
	        
	        $tabla = "tes_distribucion_cuentas_pagar";
	        
	        $where = "id_distribucion_cuentas_pagar = '".$data->id_distribucion."' ";
	        
	        $actualizado = $cuentasPagar->editBy($columnas, $tabla, $where);
	        
	        if(!is_int($actualizado)){
	            $respuesta = false;
	            $cuentasPagar->endTran('ROLLBACK');
	            break;
	            
	        }
	        
	    }
	    
	    if($respuesta){
	        $cuentasPagar->endTran('COMMIT');
	    }
	    
	    echo json_encode(array("respuesta"=>$respuesta));
	    
	}
	
	
	public function InsertaTransaccion(){
	    
	    session_start();
	    $cuentasPagar = new CuentasPagarModel();
	    
	    $nombre_controladores = "IngresoCuentasPagar";
	    $respuesta = array();
	    
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $cuentasPagar->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if(empty($resultPer)){
	        
	        echo 'Usuario no tine Permisos Insertar Cuentas Pagar';
	        exit();
	    }
	
	    try {
	        
	        $cuentasPagar->beginTran();
	        	                
	        //toma de datos
	        $_id_lote = (isset($_POST['id_lote'])) ? $_POST['id_lote'] : null;
	        $_id_consecutivo = (isset($_POST['id_consecutivo'])) ? $_POST['id_consecutivo'] : null;
	        $_id_cuentas_pagar = (isset($_POST['id_cuentas_pagar'])) ? $_POST['id_cuentas_pagar'] : null;
	        $_id_tipo_documento = (isset($_POST['id_tipo_documento'])) ? $_POST['id_tipo_documento'] : null;
	        $_id_proveedor = (isset($_POST['id_proveedor'])) ? $_POST['id_proveedor'] : null;
	        $_id_bancos = (isset($_POST['id_bancos'])) ? $_POST['id_bancos'] : 'null';
	        $_id_moneda = (isset($_POST['id_moneda'])) ? $_POST['id_moneda'] : 'null';
	        $_descripcion_cuentas_pagar = (isset($_POST['descripcion_cuentas_pagar'])) ? $_POST['descripcion_cuentas_pagar'] : null;
	        $_fecha_cuentas_pagar = (isset($_POST['fecha_cuentas_pagar'])) ? $_POST['fecha_cuentas_pagar'] : null;
	        $_condiciones_pago_cuentas_pagar = (isset($_POST['condiciones_pago_cuentas_pagar'])) ? $_POST['condiciones_pago_cuentas_pagar'] : 'null';
	        $_num_documento_cuentas_pagar = (isset($_POST['numero_documento'])) ? $_POST['numero_documento'] : null;
	        $_num_ord_compra_cuentas_pagar = (isset($_POST['numero_ord_compra'])) ? $_POST['numero_ord_compra'] : 'null';
	        $_metodo_envio_cuentas_pagar = (isset($_POST['metodo_envio_cuentas_pagar'])) ? $_POST['metodo_envio_cuentas_pagar'] : 'null';
	        $_compra_cuentas_pagar = (isset($_POST['monto_cuentas_pagar'])) ? $_POST['monto_cuentas_pagar'] : 0.00;
	        $_desc_comercial = (isset($_POST['desc_comercial_cuentas_pagar'])) ? $_POST['desc_comercial_cuentas_pagar'] : 0.00;
	        $_flete_cuentas_pagar = (isset($_POST['flete_cuentas_pagar'])) ? $_POST['flete_cuentas_pagar'] : 0.00;
	        $_miscelaneos_cuentas_pagar = (isset($_POST['miscelaneos_cuentas_pagar'])) ? $_POST['miscelaneos_cuentas_pagar'] : 0.00;
	        $_impuesto_cuentas_pagar = (isset($_POST['impuesto_cuentas_pagar'])) ? $_POST['impuesto_cuentas_pagar'] : 0.00;
	        $_total_cuentas_pagar = (isset($_POST['total_cuentas_pagar'])) ? $_POST['total_cuentas_pagar'] : 0.00;
	        $_monto1099_cuentas_pagar = (isset($_POST['monto1099_cuentas_pagar'])) ? $_POST['monto1099_cuentas_pagar'] : 0.00;
	        $_efectivo_cuentas_pagar = (isset($_POST['efectivo_cuentas_pagar'])) ? $_POST['efectivo_cuentas_pagar'] : 0.00;
	        $_cheque_cuentas_pagar = (isset($_POST['cheque_cuentas_pagar'])) ? $_POST['cheque_cuentas_pagar'] : 0.00;
	        $_tarjeta_credito_cuentas_pagar = (isset($_POST['tarjeta_credito_cuentas_pagar'])) ? $_POST['tarjeta_credito_cuentas_pagar'] : null;
	        $_condonaciones_cuentas_pagar = (isset($_POST['condonaciones_cuentas_pagar'])) ? $_POST['condonaciones_cuentas_pagar'] : null;
	        $_saldo_cuentas_pagar = $_total_cuentas_pagar;
	        
	        //para tranformar a datos solicitdos por funcion postgresql a numeric
	        $_compra_cuentas_pagar = ( is_numeric($_compra_cuentas_pagar)) ? $_compra_cuentas_pagar : 0.00;
	        $_desc_comercial = ( is_numeric($_desc_comercial)) ? $_desc_comercial : 0.00;
	        $_flete_cuentas_pagar = ( is_numeric($_flete_cuentas_pagar)) ? $_flete_cuentas_pagar : 0.00;
	        $_miscelaneos_cuentas_pagar = ( is_numeric($_miscelaneos_cuentas_pagar)) ? $_miscelaneos_cuentas_pagar : 0.00;
	        $_impuesto_cuentas_pagar = ( is_numeric($_impuesto_cuentas_pagar)) ? $_impuesto_cuentas_pagar : 0.00;
	        $_total_cuentas_pagar = ( is_numeric($_total_cuentas_pagar)) ? $_total_cuentas_pagar : 0.00;
	        $_monto1099_cuentas_pagar = ( is_numeric($_monto1099_cuentas_pagar)) ? $_monto1099_cuentas_pagar : 0.00;
	        $_efectivo_cuentas_pagar = ( is_numeric($_efectivo_cuentas_pagar)) ? $_efectivo_cuentas_pagar : 0.00;
	        $_cheque_cuentas_pagar = ( is_numeric($_cheque_cuentas_pagar)) ? $_cheque_cuentas_pagar : 0.00;
	        $_tarjeta_credito_cuentas_pagar = ( is_numeric($_tarjeta_credito_cuentas_pagar)) ? $_tarjeta_credito_cuentas_pagar : 0.00;
	        $_condonaciones_cuentas_pagar = ( is_numeric($_condonaciones_cuentas_pagar)) ? $_condonaciones_cuentas_pagar : 0.00;
	        $_saldo_cuentas_pagar = ( is_numeric($_saldo_cuentas_pagar)) ? $_saldo_cuentas_pagar : 0.00;
	        
	        /** VALIDACION QUE NO HAYA LOTES REPETIDOS **/
	        $colLote = "1";
	        $tabLote = " tes_cuentas_pagar";
	        $wheLote = " id_lote = $_id_lote";
	        $rsLote  = $cuentasPagar->getCondicionesSinOrden($colLote, $tabLote, $wheLote, "");
	        if(!empty($rsLote) ){ throw new Exception("Lote ya se encuentra ingresado. Comuniquese con el administrador"); } 
	        
	        /** validacion de la distribucion de cuentas **/
	        $col1  = " id_distribucion_cuentas_pagar,id_plan_cuentas";
	        $tab1  = " tes_distribucion_cuentas_pagar";
	        $whe1  = " id_lote = $_id_lote";
	        $rsConsulta1   = $cuentasPagar->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	        
	        if( empty($rsConsulta1) ){
	            throw new Exception(" No existe distribución de Cuentas ");
	        }else{
	            foreach ($rsConsulta1 as $res) {
	                if( empty($res->id_plan_cuentas) ){
	                    throw new ErrorException("Exiten Cuentas no definidas en Distribucion Cuentas x Pagar");
	                }
	            }
	        }
	        
	        $_origen_cuentas_pagar  = "MANUAL";
	        
	        /** SE GENERA LA INSERCCION DE LAS CUENTAS X PAGAR **/
	        $funcion = "tes_ins_cuentas_pagar";
	        $parametros = "
                        '$_id_lote',
                        '$_id_consecutivo',
                        '$_id_tipo_documento',
                        '$_id_proveedor',
                        $_id_bancos,
                        $_id_moneda,
                        '$_descripcion_cuentas_pagar',
                        '$_fecha_cuentas_pagar',
                        '$_condiciones_pago_cuentas_pagar',
                        '$_num_documento_cuentas_pagar',
                        '$_num_ord_compra_cuentas_pagar',
                        '$_metodo_envio_cuentas_pagar',
                        '$_compra_cuentas_pagar',
                        '$_desc_comercial',
                        '$_flete_cuentas_pagar',
                        '$_miscelaneos_cuentas_pagar',
                        '$_impuesto_cuentas_pagar',
                        '$_total_cuentas_pagar',
                        $_monto1099_cuentas_pagar,
                        $_efectivo_cuentas_pagar,
                        $_cheque_cuentas_pagar,
                        $_tarjeta_credito_cuentas_pagar,
                        $_condonaciones_cuentas_pagar,
                        '$_saldo_cuentas_pagar',
                        '$_origen_cuentas_pagar',
                        '$_id_cuentas_pagar'
                        ";
	        
	        $cuentasPagar->setFuncion($funcion);
	        $cuentasPagar->setParametros($parametros);
	        $resultado = $cuentasPagar->llamafuncionPG();
	        
	        if( is_null($resultado) ) { throw new Exception(" Error en la insercion de la Cuenta x Pagar"); }
	        
	        $_id_cuentas_pagar = $resultado[0]; // se obtiene el resultado de la funcion 
	        $_id_usuario = (isset($_SESSION['id_usuarios'])) ?  $_SESSION['id_usuarios'] : null;
	        $_retencion_ccomprobantes = ''; // este valor se actualizara despues de realizar la respectiva retencion
	        $_concepto_ccomprobantes = 'Cuentas por Pagar | '.$_descripcion_cuentas_pagar;
	        
	        $_valor_ccomprobantes = $_compra_cuentas_pagar;
	        $_valor_letras_ccomprobantes  = $cuentasPagar->numtoletras($_compra_cuentas_pagar);
	        $_fecha_ccomprobantes = $_fecha_cuentas_pagar;
	        //buscar formas de pago
	        $_id_forma_pago_ccomprobantes = 1;
	        $_referencia_ccomprobantes = $_num_documento_cuentas_pagar;
	        $_numero_cuenta_ccomprobantes = "";
	        $_numero_cheque_ccomprobantes = "";
	        $_observaciones_ccomprobantes = "";
	        
	        $funcionComprobantes       = "tes_agrega_comprobante_cuentas_pagar";
	        $parametrosComprobantes    = "
                                    '$_id_usuario',
                                    '$_id_lote',
                                    '$_id_proveedor',
                                    '$_retencion_ccomprobantes',
                                    '$_concepto_ccomprobantes',
                                    '$_valor_ccomprobantes',
                                    '$_valor_letras_ccomprobantes',
                                    '$_fecha_ccomprobantes',
                                    '$_id_forma_pago_ccomprobantes',
                                    '$_referencia_ccomprobantes',
                                    '$_numero_cuenta_ccomprobantes',
                                    '$_numero_cheque_ccomprobantes',
                                    '$_observaciones_ccomprobantes'
                                    ";
	        
	        $QueryFuncion = $cuentasPagar->getconsultaPG($funcionComprobantes, $parametrosComprobantes);
	        $resultadoccomprobantes = $cuentasPagar->llamarconsultaPG($QueryFuncion);
	        
	        $error_pg = pg_last_error(); if( !empty($error_pg) ){ throw new Exception(" Error insetando el comprobante Contable"); }
	        
	            
            /*actualizar cuentas pagar*/
            $_id_comprobante = (int)($resultadoccomprobantes[0]);
            $colvalCuentas = " id_ccomprobantes = $_id_comprobante";
            $tabvalCuentas = " tes_cuentas_pagar";
            $whevalCuentas = " id_cuentas_pagar = $_id_cuentas_pagar";
            
            $cuentasPagar ->ActualizarBy($colvalCuentas, $tabvalCuentas, $whevalCuentas);
                        
            /** AQUI VIENE LA INSERCION DE MATERIALES **/
            if( isset($_POST['compra_materiales']) && $_POST['compra_materiales'] == "1" ){
                
                $col2   = " SUM(debe_dcomprobantes) valorcompra ";
                $tab2   = " dcomprobantes";
                $whe2   = " id_ccomprobantes = $_id_comprobante";
                $rsConsulta2 = $cuentasPagar->getCondicionesSinOrden($col2, $tab2, $whe2, "");
                
                $valorCompra = $rsConsulta2[0]->valorcompra;
                
                $col3   = " id_estado ";
                $tab3   = " estado";
                $whe3   = " UPPER(nombre_estado) = 'PENDIENTE' AND tabla_estado = 'inv_documento_compras'";
                $rsConsulta3 = $cuentasPagar->getCondicionesSinOrden($col3, $tab3, $whe3, "");
                
                $IdestadoCompra = $rsConsulta3[0]->id_estado;                
                
                $QueryInsertCompra = "INSERT INTO inv_documento_compras
                (id_ccomprobantes, id_estado, valor_documento_compras, valor_base_documento_compras, valor_impuesto_documento_compras )
                VALUES($_id_comprobante, $IdestadoCompra, $valorCompra, $_compra_cuentas_pagar, 0)" ; 
                
                $cuentasPagar->executeInsertQuery($QueryInsertCompra);
            }
                
            $error_pg = pg_last_error(); if( !empty($error_pg) ){ throw new Exception("no se inserto la cuenta por pagar".$error_pg ); }
            
            $cuentasPagar->endTran('COMMIT');
            
            $respuesta['icon'] = 'warning';
            $respuesta['mensaje'] = "Cuenta por Pagar Ingresada Correctamente";
            $respuesta['estatus'] = 'OK';
            echo json_encode($respuesta);
                       
	        
	    } catch (Exception $e) {
	        
	        $cuentasPagar->endTran();
	        $respuesta['icon'] = 'warning';
	        $respuesta['mensaje'] = $e->getMessage();
	        $respuesta['estatus'] = 'ERROR';
	        echo json_encode($respuesta);
	    }
    	   	
	}
	
	
}
?>