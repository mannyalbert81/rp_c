<?php

use FontLib\Table\Type\post;

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
	        
	    
	    $columnas1 = " aa.id_tipo_proveedores,aa.id_proveedores, aa.nombre_proveedores, aa.identificacion_proveedores, aa.direccion_proveedores, aa.celular_proveedores,";
	    $columnas1 .= "razon_social_proveedores, tipo_identificacion_proveedores ";
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
	    $estiloConfigProveedores = ""; // variable donde se guarda estilo css para indicar al usuario que debe configurar datos de proveedor 
	    foreach ($resultSet as $res){
	        
	        if( $res->id_tipo_proveedores == 3 && $res->razon_social_proveedores == "" && $res->tipo_identificacion_proveedores == "04")
	            $estiloConfigProveedores = "bgcolor=\"#E37B71\"";
	        
	        $i++;
	        $btonSelect = "<button onclick=\"SelecionarProveedor(this)\" value=\"$res->id_proveedores\" class=\"btn btn-default\"> 
                        <i aria-hidden=\"true\" class=\"fa fa-external-link\"></i> </button>";
	        $htmlTr    .= "<tr $estiloConfigProveedores >";
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
	        $_descripcion_lote = "Módulo Cuentas Pagar";
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
	    
	    $colImpuestos  = " aa.id_impuestos,aa.tipo_impuestos,aa.codigo_impuestos,aa.nombre_impuestos,aa.id_plan_cuentas, bb.nombre_plan_cuentas, 
                bb.codigo_plan_cuentas, aa.porcentaje_impuestos, aa.operacion_impuestos";
	    $tabImpuestos  = " tes_impuestos aa
	           LEFT JOIN plan_cuentas bb ON bb.id_plan_cuentas = aa.id_plan_cuentas";
	    $wheImpuestos  = " 1 = 1 ";
	    $idIMpuestos   = " bb.codigo_plan_cuentas";
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
	    $mapTipoImpuesto   = array( 'retiva' =>'RETENCION IVA', 'ret' => 'RETENCION FUENTE', 'iva' => 'IVA'); 
	    $_tipoImpuesto     = "";
	    $htmlTr = "";
	    $i = 0;
	    $estiloConfigImpuestos = "";
	   
	    foreach ($resultSet as $res){
	        $i++;	        
	       
	        /** siguiente validacion es solo hasta que se arreglen los impuestos **/
	        if( $res->id_plan_cuentas == null || ( empty($res->codigo_impuestos) && $res->tipo_impuestos == "ret" ) || (empty($res->codigo_impuestos) && $res->tipo_impuestos == "retiva" )  ){
	            //echo ""," PLAN CUENTAS --> ",$res->id_plan_cuentas,"   CODIGO IMPUESTOS --> ",$res->codigo_impuestos, "    TIPO IMPUESTO ---> ", $res->tipo_impuestos," <br> \n";
	            $estiloConfigImpuestos = "bgcolor=\"#E37B71\"";
	        }
	        
	        $btonSelect = "<button onclick=\"AgregarImpuesto(this)\" value=\"$res->id_impuestos\" class=\"btn btn-default\">
                        <i aria-hidden=\"true\" class=\"fa fa-external-link\"></i> </button>";
	        
	        $_tipoImpuesto = (array_key_exists($res->tipo_impuestos, $mapTipoImpuesto) ) ?  $mapTipoImpuesto["$res->tipo_impuestos"] : ""; 
	        $htmlTr    .= "<tr $estiloConfigImpuestos >";
	        $htmlTr    .= "<td>" . $i . "</td>";
	        $htmlTr    .= "<td>" . $_tipoImpuesto;
	        $htmlTr    .= "<td>" . $res->nombre_impuestos . "</td>";
	        $htmlTr    .= "<td>" . $res->codigo_plan_cuentas . "</td>";
	        $htmlTr    .= "<td>" . $res->nombre_plan_cuentas . "</td>";
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
	        $_id_impuestos  = $_POST['id_impuestos'];
	        $_compra_cero   = $_POST['compra_cero'];
	        $_compra_iva    = $_POST['compra_iva'];
	        
	        $base_compras = $_compra_cero + $_compra_iva;
	        $base_compras = round($base_compras,2);
	        
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
	            
	            $totalValor    = $_compra_iva;
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
	        
	        $totalImpuesto = round($totalImpuesto,2);
	        
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
	    $id1  = " cc.codigo_plan_cuentas ";
	    $rsConsulta1  = $impuestos->getCondiciones($col1, $tab1, $Whe1, $id1);
	    
	    $error = error_get_last();
	    if( !empty($error) ){
	        echo $error['message'];
	        exit();
	    }
	    
	    $cantidadResult = sizeof($rsConsulta1);
	    $mapTipoImpuesto   = array( 'retiva' =>'RETENCION IVA', 'ret' => 'RETENCION FUENTE', 'iva' => 'IVA');
	    $_tipoImpuesto     = "";
	    $htmlTr = "";
	    $i = 0;
	    foreach ($rsConsulta1 as $res){
	        $i++;
	        $btonSelect = "<button onclick=\"RemoveImpuesto(this)\" value=\"$res->id_cuentas_pagar_impuestos\" class=\"btn btn-default\">
                        <i aria-hidden=\"true\" class=\"fa fa-trash text-danger\"></i> </button>";
	        $_tipoImpuesto = (array_key_exists($res->tipo_impuestos, $mapTipoImpuesto) ) ?  $mapTipoImpuesto["$res->tipo_impuestos"] : "";
	        $htmlTr    .= "<tr>";
	        $htmlTr    .= "<td style=\"text-align: left;  font-size: 11px;\" >" . $i . "</td>";
	        $htmlTr    .= "<td style=\"text-align: left;  font-size: 11px;\" >" . $_tipoImpuesto . "</td>";
	        $htmlTr    .= "<td style=\"text-align: left;  font-size: 11px;\" >" . $res->nombre_impuestos . "</td>";
	        $htmlTr    .= "<td style=\"text-align: left;  font-size: 11px;\" >" . $res->codigo_plan_cuentas . "</td>";
	        $htmlTr    .= "<td style=\"text-align: left;  font-size: 11px;\" >" . $res->base_cuentas_pagar_impuestos . "</td>";
	        $htmlTr    .= "<td style=\"text-align: left;  font-size: 11px;\" >" . $res->valor_base_cuentas_pagar_impuestos . "</td>";
	        $htmlTr    .= "<td style=\"text-align: left;  font-size: 11px;\" >" . $res->valor_cuentas_pagar_impuestos . "</td>";
	        $htmlTr    .= "<td style=\"text-align: left;  font-size: 11px;\" >" . $btonSelect . "</td>";
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
	
	/***
	 * @return array
	 * @param post
	 * @desc fn permite realizar la eliminacion de la relacion de un impuesto a la cuenta por pagar 
	 */
	public function QuitarImpuesto(){
	    
	    $impuestos = new ImpuestosModel();
	    $respuesta   = null;
	    
	    try {
	        
	        $id_lote = $_POST['id_lote'];
	        $_id_impuestos  = $_POST['id_impuestos'];
	        $_compra_cero   = $_POST['compra_cero'];
	        $_compra_iva    = $_POST['compra_iva'];
	        
	        $base_compras = $_compra_cero + $_compra_iva;
	        
	        /** realizar validacion de impuesto **/
	        $colImpuestos = " aa.id_impuestos, aa.nombre_impuestos, aa.porcentaje_impuestos, aa.tipo_impuestos, aa.operacion_impuestos,
                bb.valor_base_cuentas_pagar_impuestos,bb.valor_cuentas_pagar_impuestos, bb.id_cuentas_pagar_impuestos";
	        $tabIMpuestos = " tes_impuestos aa
	           INNER JOIN tes_cuentas_pagar_impuestos bb ON bb.id_impuestos = aa.id_impuestos";
	        $wheImpuestos = " bb.id_cuentas_pagar_impuestos = $_id_impuestos AND bb.id_lote = $id_lote";
	        $idImpuestos  = " aa.id_impuestos";
	        $rsImpuesto  = $impuestos->getCondiciones($colImpuestos, $tabIMpuestos, $wheImpuestos, $idImpuestos);
	        
	        if( empty($rsImpuesto) ){
	            $respuesta['respuesta'] = 'ERROR';
	            $respuesta['icon'] = 'warning';
	            throw new Exception('Detalles de impuesto no identificado');
	        }
	        
	        $_tipo_impuesto = $rsImpuesto[0]->tipo_impuestos;
	        $_pctge_impuesto = $rsImpuesto[0]->porcentaje_impuestos;
	        $_id_cuentas_pagar_impuestos   =  $rsImpuesto[0]->id_cuentas_pagar_impuestos;
	        
	        /** todo impuesto es dividido para 100 **/
	        $_pctge_impuesto = $_pctge_impuesto/100;
	        
	        /** una vez validado se comienza con los procesos de impuestos de compra **/
	        $resEliminacion = null;
	        if( strtoupper( $_tipo_impuesto )  == "IVA" ){
	            
	            $colretencion = " 1 ";
	            $tabretencion = " tes_impuestos aa
	               INNER JOIN tes_cuentas_pagar_impuestos bb ON bb.id_impuestos = aa.id_impuestos";
	            $Wheretencion = " bb.id_lote = $id_lote AND UPPER(aa.tipo_impuestos) = 'RETIVA' ";
	            $idretencion  = " bb.id_lote ";
	            $rsRetencion  = $impuestos->getCondiciones($colretencion, $tabretencion, $Wheretencion, $idretencion);
	            
	            if( !empty($rsRetencion) ){
	                $respuesta['icon'] = 'info';
	                throw new Exception('Impuesto tiene una fuente de retencion');
	            }
	            
	            $delTabla  = "tes_cuentas_pagar_impuestos";
	            $delWhere  = "id_cuentas_pagar_impuestos = $_id_cuentas_pagar_impuestos";
	            $resEliminacion = $impuestos->eliminarFila($delTabla, $delWhere);
	            
	        }else if( strtoupper( $_tipo_impuesto )  == "RETIVA" ){
	            
	            $delTabla  = "tes_cuentas_pagar_impuestos";
	            $delWhere  = "id_cuentas_pagar_impuestos = $_id_cuentas_pagar_impuestos";
	            $resEliminacion = $impuestos->eliminarFila($delTabla, $delWhere);
	            
	        }else if( strtoupper( $_tipo_impuesto )  == "RET" ){
	            
	            //eliminacion de impuesto solo restando valores
	            $delTabla  = "tes_cuentas_pagar_impuestos";
	            $delWhere  = "id_cuentas_pagar_impuestos = $_id_cuentas_pagar_impuestos"; 
	            $resEliminacion = $impuestos->eliminarFila($delTabla, $delWhere);
	            
	            
	        }else{	            
	            $respuesta['icon'] = 'warning';
	            throw new Exception('Datos Impuesto no definido');
	        }
	        
	        if( empty($resEliminacion) ){	            
	            $respuesta['icon'] = 'error';
	            throw new Exception('Impuesto selecionado no fue posible eliminar');
	        }
	        
	        $respuesta['respuesta'] = 'OK';
	        $respuesta['icon'] = 'warning';
	        $respuesta['texto']= 'Relacion impuesto eliminado';
	        
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
	        }else{
	            $_total = $base_compras;
	            $_saldo_documento = 0;
	            $_saldo_documento = $_total + 0;
	            $respuesta['total_impuesto'] = 0;
	            $respuesta['saldo_impuesto'] = $_saldo_documento;
	        }
	        	       
	        
	    } catch (Exception $e) {
	        
	        $respuesta['respuesta'] = 'ERROR';
	        $respuesta['texto']= $e->getMessage();
	    }
	    	    
	    
	    echo json_encode($respuesta);
	    
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
		              VALUES($_id_lote, null, 'PAGO' , 0.00, $valor_proveedores, $orden_insert)";
	        
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
	    $htmlPaginacion .= ''. $this->paginate("index.php", 1, 1, 20,"").'';
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
	        
	        $respuesta['icon'] = 'warning';
	        $respuesta['mensaje'] = 'Usuario no tine Permisos Insertar Cuentas Pagar';
	        $respuesta['estatus'] = 'ERROR';
	        echo json_encode($respuesta);
	        exit();
	    }
	
	    try {
	        
	        $cuentasPagar->beginTran();
	        	                
	        //toma de datos
	        $_id_lote          = (isset($_POST['id_lote'])) ? $_POST['id_lote'] : null;
	        $_id_consecutivo   = (isset($_POST['id_consecutivo'])) ? $_POST['id_consecutivo'] : null;
	        $_id_cuentas_pagar = (isset($_POST['id_cuentas_pagar'])) ? $_POST['id_cuentas_pagar'] : null;
	        $_id_tipo_documento    = (isset($_POST['id_tipo_documento'])) ? $_POST['id_tipo_documento'] : null;
	        $_id_proveedor     = (isset($_POST['id_proveedor'])) ? $_POST['id_proveedor'] : null;
	        $_id_bancos        = (isset($_POST['id_bancos'])) ? $_POST['id_bancos'] : 'null';
	        $_id_moneda        = (isset($_POST['id_moneda'])) ? $_POST['id_moneda'] : 'null';
	        $_descripcion_cuentas_pagar        = (isset($_POST['descripcion_cuentas_pagar'])) ? $_POST['descripcion_cuentas_pagar'] : null;
	        $_fecha_cuentas_pagar  = (isset($_POST['fecha_cuentas_pagar'])) ? $_POST['fecha_cuentas_pagar'] : null;
	        $_condiciones_pago_cuentas_pagar   = (isset($_POST['condiciones_pago_cuentas_pagar'])) ? $_POST['condiciones_pago_cuentas_pagar'] : 'null';
	        $_num_documento_cuentas_pagar      = (isset($_POST['numero_documento'])) ? $_POST['numero_documento'] : null;
	        $_num_ord_compra_cuentas_pagar     = (isset($_POST['numero_ord_compra'])) ? $_POST['numero_ord_compra'] : 'null';
	        $_metodo_envio_cuentas_pagar       = (isset($_POST['metodo_envio_cuentas_pagar'])) ? $_POST['metodo_envio_cuentas_pagar'] : 'null';
	        $_compra_cuentas_pagar             = (isset($_POST['monto_cuentas_pagar'])) ? $_POST['monto_cuentas_pagar'] : 0.00;
	        $_desc_comercial       = (isset($_POST['desc_comercial_cuentas_pagar'])) ? $_POST['desc_comercial_cuentas_pagar'] : 0.00;
	        $_flete_cuentas_pagar  = (isset($_POST['flete_cuentas_pagar'])) ? $_POST['flete_cuentas_pagar'] : 0.00;
	        $_miscelaneos_cuentas_pagar        = (isset($_POST['miscelaneos_cuentas_pagar'])) ? $_POST['miscelaneos_cuentas_pagar'] : 0.00;
	        $_impuesto_cuentas_pagar           = (isset($_POST['impuesto_cuentas_pagar'])) ? $_POST['impuesto_cuentas_pagar'] : 0.00;
	        $_total_cuentas_pagar  = (isset($_POST['total_cuentas_pagar'])) ? $_POST['total_cuentas_pagar'] : 0.00;
	        $_monto1099_cuentas_pagar          = (isset($_POST['monto1099_cuentas_pagar'])) ? $_POST['monto1099_cuentas_pagar'] : 0.00;
	        $_efectivo_cuentas_pagar           = (isset($_POST['efectivo_cuentas_pagar'])) ? $_POST['efectivo_cuentas_pagar'] : 0.00;
	        $_cheque_cuentas_pagar             = (isset($_POST['cheque_cuentas_pagar'])) ? $_POST['cheque_cuentas_pagar'] : 0.00;
	        $_tarjeta_credito_cuentas_pagar    = (isset($_POST['tarjeta_credito_cuentas_pagar'])) ? $_POST['tarjeta_credito_cuentas_pagar'] : null;
	        $_condonaciones_cuentas_pagar      = (isset($_POST['condonaciones_cuentas_pagar'])) ? $_POST['condonaciones_cuentas_pagar'] : null;
	        $_saldo_cuentas_pagar              = $_total_cuentas_pagar;
	        $_compra_cero  = (isset($_POST['monto_compra_cero'])) ? $_POST['monto_compra_cero'] : 0.00;
	        $_compra_iva   = (isset($_POST['monto_compra_iva'])) ? $_POST['monto_compra_iva'] : 0.00;
	        
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
	        
	        $_expresion = "/^[0-9]{3}-[0-9]{3}-[0-9]{9}$/";
	        $_texto = $_num_documento_cuentas_pagar;
	        
	        if ( !preg_match($_expresion, $_texto )) {
	            throw new ErrorException("Formato Documento Soporte no Válido");
	        } 
	        
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
                        $_compra_cero,
                        $_compra_iva
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
	        
	        $error_pg = pg_last_error(); if( !empty($error_pg) ){ throw new Exception(" Error insertando el comprobante Contable"); }
	        
	            
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
            $error_php = error_get_last(); if( !empty($error_php) ){ throw new Exception("no se inserto la cuenta por pagar".$error_php['message'] ); }
            /** PARA LA GENERACION DEL XML **/
            
            $resp = $this->genXmlRetencion($_id_lote);
            $respuesta['xml'] = '';
            $respuesta['file']= $resp;
            
            if( $resp['error'] === true ){   
                /** parametrizar pa guardar en tabla error retencion **/
                if( array_key_exists('mensaje', $resp) && $resp['mensaje'] == "XML NO GENERADO" ){
                    /** poner estado no generado **/
                    $respuesta['xml'] = 'XML NO GENERADO';
                }
                if (array_key_exists('claveAcceso', $resp) && strlen( $resp['claveAcceso'] ) == 49 ) {
                    
                    $respuesta['xml'] = 'DATOS xml EN BD No fueron ingresados';
                    
                    $claveAcceso = $resp['claveAcceso'];
                    $_columnaActualizar = " autorizado_retenciones = false ";
                    $_tablaActualizar   = " tri_retenciones";
                    $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                    
                    $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                }
                
                
               
            }else{
                $respuesta['xml'] = " ARCHIVO ENTRO XML";
                
                if( array_key_exists('mensaje', $resp) && $resp['mensaje'] == "XML GENERADO" ){
                    /** COMIENZA PROCESO XML CON SRI**/
                    $errorXml = false; 
                    
                    $respuesta['xml'] = " ARCHIVO ENTRO XML IF";
                    
                    $clave = ( array_key_exists('claveAcceso', $resp) ) ? $resp['claveAcceso'] : '' ;         
                    
                    require_once __DIR__ . '/../vendor/autoload.php';
                    
                    $config = $this->getConfigXml();
                    
                    $comprobante = new \Shara\ComprobantesController($config); //*configurar ruta en carpeta vendor**/-- autoload_static.php
                    /** tener en cuenta la ruta de archivos **/
                   
                    $xml = file_get_contents($config['generados'] . DIRECTORY_SEPARATOR . $clave.'.xml', FILE_USE_INCLUDE_PATH);
                    
                    $aux = $comprobante->validarFirmarXml($xml, $clave);
                    
                    $respuesta['Archivo'] = "";
                    $respuesta['xml'] = $aux;
                    
                    if($aux['error'] === false){
                        
                        $aux = $comprobante->enviarXml($clave);
                        //$aux['recibido'] = true; //para pruebas
                        
                        if($aux['recibido'] === true){
                            
                            $respuesta['xml'] = " Archivo Xml RECIBIDO";
                            
                            $finalresp = $comprobante->autorizacionXml($clave);
                            //$finalresp = null;. //para pruebas
                            //$finalresp['error'] = false; //para pruebas
                            if($finalresp['error'] === true ){                                
                                /** aqui poner senetecia en caso de haber errror **/
                                $respuesta['xml'] = " Archivo Xml RECIBIDO NO AUTORIZADO";
                                $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;  
                                $errorXml = true;
                            }else{
                                
                                $respuesta['xml'] = " Archivo Xml RECIBIDO AUTORIZADO";
                                $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;  
                                
                                //$fechaAutorizado = $finalresp['fecauto'];
                            }
                            
                            
                        }else{
                            /** aqui poner senetecia en caso de haber errror **/
                            $respuesta['xml'] = " Archivo Xml NO RECIBIDO";
                            $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;
                            $errorXml = true;
                        }
                            
                    }else{                        
                        /** aqui poner senetecia en caso de haber errror **/
                        $respuesta['xml'] = " Archivo Xml NO FIRMADO";
                        $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;
                        $errorXml = true;
                    }
                    
                    /** actualizacion si existe algun error **/
                    if( $errorXml ){
                        
                        $claveAcceso = $resp['claveAcceso'];
                        $_columnaActualizar = " autorizado_retenciones = false ";
                        $_tablaActualizar   = " tri_retenciones";
                        $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                        $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                        
                        /** agregar datos a tabla errores de retenciones **/ 
                    }
                        
                }else{
                    $respuesta['xml'] = " ARCHIVO NO ENTRO XML";
                }
            }
            
            $cuentasPagar->endTran('COMMIT');
            
            $respuesta['icon'] = 'success';
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
	
	/***
	 * @return boolean 
	 * @desc met que permite la generacion xml
	 * @param integer $_id_lote
	 */
	
	public function genXmlRetencion($intLote=null){
	    
	    
	    if( $intLote == null )  // descomentar para produccion
	       return array('error' => true, 'mensaje' => 'LOTE NO IDENTIFICADO'); // descomentar para produccion
	    
	    $_id_lote =  $intLote ;	    
	    //$_id_lote = ( $intLote == null ) ? 149 : $intLote ;//para pruebas descomentar
	    	    
	    $cuentasPagar  = new CuentasPagarModel();
	    /** buscar los datos de la retencion **/
	    
	    //impuestos de tipo retencion
	    $col1  = " aa.id_impuestos,aa.base_cuentas_pagar_impuestos,aa.valor_base_cuentas_pagar_impuestos,aa.valor_cuentas_pagar_impuestos,
               bb.codigo_impuestos, bb.codretencion_impuestos, bb.codigo_impuestos,bb.porcentaje_impuestos,bb.tipo_impuestos";
	    $tab1  = " tes_cuentas_pagar_impuestos aa
	           INNER JOIN tes_impuestos bb ON bb.id_impuestos = aa.id_impuestos";
	    $whe1  = " 1 = 1
	           AND UPPER(bb.tipo_impuestos) in ('RETIVA','RET')
               AND aa.id_lote = $_id_lote";
	    $id1   = " aa.creado";
	    $rsConsulta1   = $cuentasPagar->getCondiciones($col1, $tab1, $whe1, $id1); //array de impuestos
	    
	    //datos de proveedor para el comprobante 
	    $col2  = " aa.id_cuentas_pagar,  aa.numero_cuentas_pagar, aa.id_tipo_documento, aa.descripcion_cuentas_pagar, aa.fecha_cuentas_pagar,
                aa.numero_documento_cuentas_pagar,aa.compras_cuentas_pagar, bb.id_proveedores, bb.identificacion_proveedores, bb.nombre_proveedores,
                bb.razon_social_proveedores,bb.tipo_identificacion_proveedores, bb.direccion_proveedores, bb.telefono_proveedores, bb.email_proveedores";
	    $tab2  = " tes_cuentas_pagar aa
	           INNER JOIN proveedores bb ON bb.id_proveedores = aa.id_proveedor";
	    $whe2  = " 1 = 1
               AND aa.id_lote = $_id_lote";
	    $id2   = " aa.creado";
	    $rsConsulta2   = $cuentasPagar->getCondiciones($col2, $tab2, $whe2, $id2); //array de proveedor
	    
	    //datos de la empresa
	    $col3  = " id_entidades, ruc_entidades, nombre_entidades, telefono_entidades, direccion_entidades, ciudad_entidades, razon_social_entidades";
	    $tab3  = " entidades";
	    $whe3  = " 1 = 1
               AND nombre_entidades = 'CAPREMCI'";
	    $id3   = " creado";
	    $rsConsulta3   = $cuentasPagar->getCondiciones($col3, $tab3, $whe3, $id3); //array de empresa
	    
	    //datos de consecutivo 
	    $col4  = " LPAD( valor_consecutivos::TEXT,espacio_consecutivos,'0') secuencial";
	    $tab4  = " consecutivos";
	    $whe4  = " 1 = 1
               AND nombre_consecutivos = 'RETENCION'";
	    $id4   = " creado";
	    $rsConsulta4   = $cuentasPagar->getCondiciones($col4, $tab4, $whe4, $id4); //array de empresa
	    
	    //actualizar el codigo de retencion
	    $_actCol = " valor_consecutivos = valor_consecutivos + 1, numero_consecutivos = LPAD( ( valor_consecutivos + 1)::TEXT,espacio_consecutivos,'0')";
	    $_actTab = " consecutivos ";
	    $_actWhe = " nombre_consecutivos = 'RETENCION' ";
	    $resultadoAct =  $cuentasPagar->ActualizarBy($_actCol, $_actTab, $_actWhe);
	    if( $resultadoAct == -1 ){
	        return array('error' => true, 'mensaje' => 'Numero Retencion no actualizada');
	    }
	 
	    /** validacion de parametros **/
	    if( empty($rsConsulta1) || empty($rsConsulta2) || empty($rsConsulta3) || empty($rsConsulta4) ){
	        //echo "Error validacion llego ";
	        return array('error' => true, 'mensaje' => 'Consultas no contiene todos los datos');
	    }
	    	   
	    /** AUX de VARIABLES **/
	    $_auxFecha = $rsConsulta2[0]->fecha_cuentas_pagar;
	    $_fechaDocumento = new DateTime($_auxFecha); 
	            
	    /** VARIABLES DE XML **/
	    $_ambiente = 1; //1 pruebas  2 produccion
	    $_tipoEmision = 1; //1 emision normal deacuerdo a la tabla 2 SRI
	    $_rucEmisor  = $rsConsulta3[0]->ruc_entidades;
	    $_razonSocial = $rsConsulta3[0]->razon_social_entidades;
	    $_nomComercial= $rsConsulta3[0]->nombre_entidades;
	    $_codDocumento= "07"; // referenciado a la tabla 4 del sri
	    $_establecimiento = "001"; //definir de la estructura  001-001-000000 -- factura !!!!------>NOTA
	    $_puntoEmision    = "001"; //solo existe un establecimiento
	    $_secuencial      = $rsConsulta4[0]->secuencial;   // es un secuencial tiene que definirse
	    $_dirMatriz       = $rsConsulta3[0]->direccion_entidades;
	    $_fechaEmision    = date_format($_fechaDocumento, 'd/m/Y'); //definir la fecha 
	    $_dirEstablecimiento   = $rsConsulta3[0]->direccion_entidades;
	    
	    // /** informacion rtencion **/ //datos obtener de la tabla proveedores
	    $_contriEspecial  = "624";  //numero definir para otra empresa !!!!------>NOTA ----- OJO -- tomara de la tabla entidades
	    $_obligadoContabilidad = "SI"; //TEXTO definir para otra empresa !!!!------>NOTA ----- OJO --tomara de la tabla entidades
	    $_tipoIdentificacionRetenido   = $rsConsulta2[0]->tipo_identificacion_proveedores; // deacuerdo a la tabla 7 --> ruc 04
	    $_razonSocialRetenido  = $rsConsulta2[0]->razon_social_proveedores;
	    $_identificacionSujetoRetenido = $rsConsulta2[0]->identificacion_proveedores;
	    $_periodoFiscal        = date_format($_fechaDocumento, 'm/Y'); 
	    
	    $_claveAcceso = $this->genClaveAcceso($_fechaEmision, $_rucEmisor, $_ambiente, $_establecimiento, $_puntoEmision, $_secuencial, $_tipoEmision);
	    
	    if( $_claveAcceso == "" || strlen($_claveAcceso) != 49 ){
	        return array('error' => true, 'mensaje' => 'Clave de acceso no generada');
	    }	    
	    
	    $texto = "";
	    $texto .='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';	    
	    $texto .= '<comprobanteRetencion id="comprobante" version="1.0.0">';	    
	    $texto .= '<infoTributaria>';
	    $texto .= '<ambiente>'.$_ambiente.'</ambiente>'; //conforme a la tabla 4
	    $texto .= '<tipoEmision>'.$_tipoEmision.'</tipoEmision>'; //conforme a la tabla 2
	    $texto .= '<razonSocial>'.htmlspecialchars($_razonSocial).'</razonSocial>';
	    $texto .= '<nombreComercial>'.htmlspecialchars($_nomComercial).'</nombreComercial>';
	    $texto .= '<ruc>'.$_rucEmisor.'</ruc>';
	    $texto .= '<claveAcceso>'.$_claveAcceso.'</claveAcceso>'; //conforme a la tabla 1
	    $texto .= '<codDoc>'.$_codDocumento.'</codDoc>'; //conforme a la tabla 3
	    $texto .= '<estab>'.$_establecimiento.'</estab>';
	    $texto .= '<ptoEmi>'.$_puntoEmision.'</ptoEmi>';
	    $texto .= '<secuencial>'.$_secuencial.'</secuencial>';
	    $texto .= '<dirMatriz>'.$_dirMatriz.'</dirMatriz>';
	    $texto .= '</infoTributaria>';
	    
	    $texto .= '<infoCompRetencion>';
	    $texto .= '<fechaEmision>'.$_fechaEmision.'</fechaEmision>'; //conforme al formato -- dd/mm/aaaa
	    $texto .= '<dirEstablecimiento>'.$_dirEstablecimiento.'</dirEstablecimiento>';
	    $texto .= '<contribuyenteEspecial>'.$_contriEspecial.'</contribuyenteEspecial>';
	    $texto .= '<obligadoContabilidad>'.$_obligadoContabilidad.'</obligadoContabilidad>';
	    $texto .= '<tipoIdentificacionSujetoRetenido>'.$_tipoIdentificacionRetenido.'</tipoIdentificacionSujetoRetenido>'; // conforme a la tabla 6
	    $texto .= '<razonSocialSujetoRetenido>'.$_razonSocialRetenido.'</razonSocialSujetoRetenido>';
	    $texto .= '<identificacionSujetoRetenido>'.$_identificacionSujetoRetenido.'</identificacionSujetoRetenido>';
	    $texto .= '<periodoFiscal>'.$_periodoFiscal.'</periodoFiscal>'; //conforme a formato mm/aaaa
	    $texto .= '</infoCompRetencion>';
	    
	    $texto .= '<impuestos>'; //aqui comienza el foreach de impuestos
	    
	    /** VARIABLES PARA CADA IMPUESTO **/
	    $_impCodigo = "";
	    $_impCodRetencion = "";
	    $_impBaseImponible = "";
	    $_impPorcetajeRet  = "";
	    $_impValorRet      = "";
	    $_impCodDocumentoSustentoRet = "01"; //!NOTA
	    $_impNumDocumentoSustentoRet = "";
	    $_impfechaEmisionRet   = $_fechaEmision;
	    
	    $_impNumDocumentoSustentoRet = $rsConsulta2[0]->numero_documento_cuentas_pagar;
	    $_impNumDocumentoSustentoRet = str_replace("-", "", $_impNumDocumentoSustentoRet);
	    
	    foreach ($rsConsulta1 as $res) {
	        
	        $_impCodigo = $res->codigo_impuestos;
	        $_impCodRetencion = $res->codretencion_impuestos;
	        $_impBaseImponible = $res->valor_base_cuentas_pagar_impuestos;
	        $_impPorcetajeRet = $res->porcentaje_impuestos;
	        $_impValorRet = $res->valor_cuentas_pagar_impuestos;
	        
	        $texto .= '<impuesto>';
	        $texto .= '<codigo>'.$_impCodigo.'</codigo>'; //conforme a la tabla 20
	        $texto .= '<codigoRetencion>'.$_impCodRetencion.'</codigoRetencion>'; //conforme a la tabla 21
	        $texto .= '<baseImponible>'.round($_impBaseImponible,2).'</baseImponible>';
	        $texto .= '<porcentajeRetener>'.abs($_impPorcetajeRet).'</porcentajeRetener>';//conforme a la tabla 21
	        $texto .= '<valorRetenido>'.round(abs($_impValorRet),2).'</valorRetenido>';
	        $texto .= '<codDocSustento>'.$_impCodDocumentoSustentoRet.'</codDocSustento>';
	        $texto .= '<numDocSustento>'.$_impNumDocumentoSustentoRet.'</numDocSustento>'; //num documento soporte sin '-'
	        $texto .= '<fechaEmisionDocSustento>'.$_impfechaEmisionRet.'</fechaEmisionDocSustento>'; //obligatorio cuando corresponda **formato dd/mm/aaaa
	        $texto .= '</impuesto>';
	        
	        
	    }
	    
	    $texto .= '</impuestos>';
	    
	    /** obligatorio cuando corresponda **/
	    // se toma datos de proveedor -- Direccion. Telefono. Correo
	    /**CAMPOS ADICIONALES **/
	    $_adicional1 = $rsConsulta2[0]->direccion_proveedores;
	    $_adicional2 = $rsConsulta2[0]->telefono_proveedores;
	    $_adicional3 = $rsConsulta2[0]->email_proveedores;
	    $texto .= '<infoAdicional>';
	    $texto .= '<campoAdicional nombre="Dirección">'.$_adicional1.'</campoAdicional>';
	    $texto .= '<campoAdicional nombre="Teléfono">'.$_adicional2.'</campoAdicional>';
	    $texto .= '<campoAdicional nombre="Email">'.$_adicional3.'</campoAdicional>';
	    $texto .= '</infoAdicional>';
	    /** termina obligatorio cuando corresponda **/
	    
	    $texto .= '</comprobanteRetencion>';
	    
	    $resp = null;
	    
	    try {
	        
	        $nombre_archivo = $_claveAcceso.".xml";
	        $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\DOCUMENTOSELECTRONICOS\\docGenerados\\";
	        $ubicacion = $ubicacionServer.$nombre_archivo;
	        
	        $textoXML = mb_convert_encoding($texto, "UTF-8");
	        
	        $gestor = fopen($ubicacionServer.$nombre_archivo, 'w');
	        fwrite($gestor, $textoXML);
	        fclose($gestor);
	        	        
	        if( file_exists( $ubicacion ) ){
	            //echo "archivo existe";
	            /** SE GENERA UN INSERT A LA TABLA tri_retenciones con la columnName autorizado_retenciones en true **/
	            
	            $_trifuncion = "ins_tri_retenciones";
	            $_triparametros =  "$_ambiente,$_tipoEmision,'$_razonSocial','$_nomComercial','$_rucEmisor','$_claveAcceso','$_codDocumento','$_establecimiento',";
	            $_triparametros .= "'$_puntoEmision','$_secuencial','$_dirMatriz','$_fechaEmision','$_dirEstablecimiento',$_contriEspecial,'$_obligadoContabilidad',";
	            $_triparametros .= "'$_tipoIdentificacionRetenido','$_razonSocialRetenido','$_identificacionSujetoRetenido','$_periodoFiscal',0,0,0.00,0.00,0.00,";
	            $_triparametros .= "'','','$_fechaEmision',0,0,0.00,0.00,0.00,'','','$_fechaEmision','$_adicional1','$_adicional2','$_adicional3','$_fechaEmision'";
	            
	            $_qryTriRetenciones    = $cuentasPagar->getconsultaPG($_trifuncion, $_triparametros);
	            $resultado     = $cuentasPagar->llamarconsultaPG($_qryTriRetenciones);
	            
	            $error = pg_last_error();
	            if( !empty($error) ){	                
	                throw new Exception('Error al guardar datos Xml en BD');
	            }
	            
	            if( $resultado[0] == 1 ){
	                /** SE GENERA INSERTADO DEL DETALLE DEL ARCHIVO XML **/
	                $_triCol1  = " id_tri_retenciones ";
	                $_triTab1  = " tri_retenciones ";
	                $_triWhe1  = " infotributaria_claveacceso = '$_claveAcceso'";
	                $_rstriConsulta1   = $cuentasPagar->getCondicionesSinOrden($_triCol1, $_triTab1, $_triWhe1, "");
	                
	                if( !empty($_rstriConsulta1) ){
	                    
	                    $_tri_detallefuncion       = "ins_tri_retenciones_detalle";
	                    $_id_tri_retenciones       = $_rstriConsulta1[0]->id_tri_retenciones;
	                    
	                    foreach ($rsConsulta1 as $res) {
	                        
	                        $_tri_detalleparametros    = "";
	                        $_impCodigo = $res->codigo_impuestos;
	                        $_impCodRetencion = $res->codretencion_impuestos;
	                        $_impBaseImponible = abs($res->valor_base_cuentas_pagar_impuestos);
	                        $_impPorcetajeRet = abs($res->porcentaje_impuestos);
	                        $_impValorRet = abs($res->valor_cuentas_pagar_impuestos);
	                        
	                        $_tri_detalleparametros    .= "$_id_tri_retenciones,$_impCodigo,'$_impCodRetencion',$_impBaseImponible,$_impPorcetajeRet,$_impValorRet,";
	                        $_tri_detalleparametros    .= "'$_impCodDocumentoSustentoRet','$_impNumDocumentoSustentoRet','$_impfechaEmisionRet'";
	                        $_qryTriDetalleRetenciones = $cuentasPagar->getconsultaPG($_tri_detallefuncion, $_tri_detalleparametros);
	                        
	                        $resultadoDetalle  = $cuentasPagar->llamarconsultaPG($_qryTriDetalleRetenciones); /** insertado del detalle de retenciones **/
	                        
	                        $error = pg_last_error();
	                        if( !empty($error) ){
	                            $ins_detalle = false;
	                            
	                        }
	                    }
	                    
	                    if( !empty($error) && isset($ins_detalle) && !$ins_detalle){
	                        throw new Exception('Error al guardar Detalles Impuestos datos Xml en BD');
	                    }
	                    
	                }
	                
	            }
	            
	            $resp['error'] = false;
	            $resp['mensaje'] = 'XML GENERADO';
	            $resp['claveAcceso'] = $_claveAcceso;
	            
	        }else{
	            throw new Exception('XML NO GENERADO');
	            
	        }
	        
	    } catch (Exception $e) {
	        
	        $resp['error'] = true;
	        $resp['mensaje'] = $e->getMessage();
	        $resp['claveAcceso'] = $_claveAcceso;
	    }
	   
	    return $resp;
	    /*
	    header("Content-disposition: attachment; filename=$nombre_archivo");
	    header("Content-type: MIME");
	    ob_clean();
	    flush();
	    
	    readfile($ubicacion);
	    exit;
	    */
	    
	}
	
	/***
	 * @desc metodo retorna la cadena de Clave Acceso Xml
	 * @return string
	 */
	public function genClaveAcceso($_fechaEmision,$_identificacionRet,$_tipoAmbiente,$_sec1,$_sec2,$sec_3,$_tipoEmision){
	    
	    $_tipoDocumento = "07"; //de acuerdo con la tabla Sri 4 --comprobanteRetencion
	    $_digitoVerificador = "";
	    $_codNumerico = "12345678";
	    
	    $_fechaEmision = str_replace( array( '/', '-' ), '' , $_fechaEmision );
	    
	    $_strClaveAcceso = $_fechaEmision.$_tipoDocumento.$_identificacionRet.$_tipoAmbiente.$_sec1.$_sec2.$sec_3.$_codNumerico.$_tipoEmision;
	    
	    if( strlen( $_strClaveAcceso ) != 48 ){
	        //echo "longitud de caracteres  para ver digito verificador no cumplida";
	        return "";
	    }
	   	    
	    $_digitoVerificador = $this->getDigVerificador($_strClaveAcceso);
	    
	    if( $_digitoVerificador == "")
	        return "";
	    
        $_strClaveAcceso = $_strClaveAcceso.$_digitoVerificador;
	    
	    return $_strClaveAcceso;
	}	
	
	/***
	 * @desc metodo 
	 * @param string $num
	 * @return string
	 */
	function getDigVerificador( $num = "" ){
	    
	    if( $num == "" )
	        return "";
	    /* --------------------------------------------------------------------------------------- */
	    $digits = str_replace( array( '.', ',' ), array( ''.'' ), strrev($num ) );
	    
	    
	    if ( ! ctype_digit( $digits ) ){
	        return "";
	    }
	    
	    $sum = 0;
	    $factor = 2;
	    
	    for( $i=0;$i<strlen( $digits ); $i++ ){
	        $sum += substr( $digits,$i,1 ) * $factor;
	        if ( $factor == 7 ){
	            $factor = 2;
	        }else{
	            $factor++;
	        }
	    }
	    
	    $dv = 11 - ($sum % 11);
	    if ( $dv < 10 )
	        return $dv;
	    
        if ( $dv == 10 )
            return 1;
        
        if ( $dv == 11 )
            return 0;
	    
	    return  "";
	}
	
	public function verExpresiones(){
	    
	    $_expresion = "/^[0-9]{3}-[0-9]{3}-[0-9]{9}$/";
	    $_texto = "051-001-123456789";	    
	    
	    if (preg_match($_expresion, $_texto )) {
	        echo "Se encontró una coincidencia.";
	    } else {
	        echo "No se encontró ninguna coincidencia.";
	    }
	}
	
	public function pRetenciones(){
	    
	    
	    $date = date_create('2000-1-1');
	    echo date_format($date, 'd/m/Y');
	    
	    $_impNumDocumentoSustentoRet = "001-001-5623";
	    echo $_impNumDocumentoSustentoRet;
	    $_impNumDocumentoSustentoRet = str_replace("-", "", $_impNumDocumentoSustentoRet);
	    
	    echo $_impNumDocumentoSustentoRet;
	
	}
	
	
	public function verBuffer(){
	    
	    echo "hola mundo";
	    
	    $var1 = "";
	}
	
	public function enviarXMLSRI(){
	    	    
	    require_once __DIR__ . '/../vendor/autoload.php';
	    
	    $config = $this->getConfigXml();    
	    
	    $comprobante = new \Shara\ComprobantesController($config); //*configurar ruta en carpeta vendor**/-- autoload_static.php
	    /** tener en cuenta la ruta de archivos **/
	    $clave = '0402202007179260854600110010010000050071234567812';	    
	    $xml = file_get_contents($config['generados'] . DIRECTORY_SEPARATOR . $clave.'.xml', FILE_USE_INCLUDE_PATH);
	    $aux = $comprobante->validarFirmarXml($xml, $clave);	    
	    var_dump($aux);
	    exit();
	    
	    $clave = '0402202007179260854600110010010000050071234567812';
	    
	    $aux = $comprobante->autorizacionXml($clave);
	    var_dump($aux);
	    exit();
	    $xml = file_get_contents($config['generados'] . DIRECTORY_SEPARATOR . $clave.'.xml', FILE_USE_INCLUDE_PATH);
	    
	    $aux = $comprobante->validarFirmarXml($xml, $clave);
	    
	    if($aux['error'] === false){
	        
	        //$aux = $comprobante->enviarXml($clave);
	        
	        if($aux['recibido'] === true){
	            var_dump($aux);
	            echo "<br>";
	            echo "<br>";
	            //$aux = $comprobante->autorizacionXml($clave);
	            var_dump($aux);
	        }
	        else
	            var_dump($aux);
	    }
	    else
	        var_dump($aux);
	}
	
	public function getConfigXml(){
	    $configuracionesPath = array(
	        'url_pruebas' => 'https://celcer.sri.gob.ec',
	        'url_produccion' => 'https://celcer.sri.gob.ec',
	        'firmados' => 'DOCUMENTOSELECTRONICOS/docFirmados',
	        'autorizados' => 'DOCUMENTOSELECTRONICOS/docAutorizados',
	        'noautorizados' => 'docNoAutorizados',
	        'generados' => 'DOCUMENTOSELECTRONICOS/docGenerados',
	        'pdf' => 'DOCUMENTOSELECTRONICOS/docPdf',
	        'logo' => 'DOCUMENTOSELECTRONICOS/logo.png1',
	        'xsd' => 'DOCUMENTOSELECTRONICOS/docXsd',
	        'pathFirma' => 'firma/byron_stalin_bolanos_palma.p12',
	        'passFirma' => 'BPbs1715'
	    );
	    return $configuracionesPath;
	}
	
	Public function RptCuentasPagar(){
	    
	    $cuentasPagar = new CuentasPagarModel();
	    $entidades = new EntidadesModel();
	    
	    session_start();
	    
	    $_id_lote = (isset($_GET['id_lote'])) ? $_GET['id_lote'] : null;
	    
	    $_id_cuentas_pagar = (isset($_GET['id_cuentas_pagar'])) ? $_GET['id_cuentas_pagar'] : null;
	    
	    if(!is_null($_id_lote)){
	        
	        $query = "SELECT id_cuentas_pagar FROM public.tes_cuentas_pagar where id_lote = $_id_lote LIMIT 1";
	        
	        $rs_Cuentas_pagar = $cuentasPagar->enviaquery($query);
	        
	        if(!empty($rs_Cuentas_pagar))
	            $_id_cuentas_pagar = $rs_Cuentas_pagar[0]->id_cuentas_pagar;
	    }
	    
	    if(is_null($_id_cuentas_pagar) ){
	        
	        $this->nodatapdf("PARAMETROS NO ENCONTRADOS PARA GENERACION DE REPORTE");        
	        
	    }
	    
	    //PARA OBTENER DATOS DE LA EMPRESA
	    $datos_empresa = array();
	    $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
	    
	    if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0){
	        //llenar nombres con variables que va en html de reporte
	        $datos_empresa['NOMBREEMPRESA']=$rsdatosEmpresa[0]->nombre_entidades;
	        $datos_empresa['DIRECCIONEMPRESA']=$rsdatosEmpresa[0]->direccion_entidades;
	        $datos_empresa['TELEFONOEMPRESA']=$rsdatosEmpresa[0]->telefono_entidades;
	        $datos_empresa['RUCEMPRESA']=$rsdatosEmpresa[0]->ruc_entidades;
	        $datos_empresa['FECHAEMPRESA']=date('Y-m-d H:i');
	        $datos_empresa['USUARIOEMPRESA']=(isset($_SESSION['usuario_usuarios']))?$_SESSION['usuario_usuarios']:'';
	    }
	    
	    //NOTICE DATA
	    $datos_cabecera = array();
	    $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
	    $datos_cabecera['FECHA'] = date('Y/m/d');
	    $datos_cabecera['HORA'] = date('h:i:s');
	    
	    $datos_cuentas_pagar = array();
	    
	    $columnascxp = "id_cuentas_pagar, numero_cuentas_pagar, descripcion_cuentas_pagar, fecha_cuentas_pagar,
                  numero_documento_cuentas_pagar, compras_cuentas_pagar, condonaciones_cuentas_pagar,
                  saldo_cuenta_cuentas_pagar, descuento_comercial_cuentas_pagar, flete_cuentas_pagar,
                  miscelaneos_cuentas_pagar,impuesto_cuentas_pagar, cp.id_tipo_documento, td.abreviacion_tipo_documento,
                  lo.id_lote, lo.nombre_lote, lo.descripcion_lote, lo.numero_lote, fre.nombre_frecuencia_lote,
                  cp.id_proveedor, pro.nombre_proveedores, pro.identificacion_proveedores";
	    
	    $tablascxp = "public.tes_cuentas_pagar cp
                INNER JOIN tes_tipo_documento td
                ON cp.id_tipo_documento = td.id_tipo_documento
                INNER JOIN tes_lote lo
                ON lo.id_lote = cp.id_lote
                INNER JOIN proveedores pro
                ON pro.id_proveedores = cp.id_proveedor
                INNER JOIN tes_frecuencia_lote fre
                ON fre.id_frecuencia_lote = lo.id_frecuencia";
	    
	    $wherecxp = "cp.id_cuentas_pagar = $_id_cuentas_pagar ";
	    
	    $idcxp = "cp.id_cuentas_pagar";
	    
	    $rsDatosCxp = $cuentasPagar->getCondiciones($columnascxp, $tablascxp, $wherecxp, $idcxp);
	    
	    
	    
	    if(empty($rsDatosCxp)){
	        
	        $this->nodatapdf("Cuenta por pagar no encontrada");
	        
	    }
	    
	    /** PARA DATOS DE LOTE **/
	    $_id_lote = $rsDatosCxp[0]->id_lote;
	    $col1  = "aa.id_usuarios,bb.usuario_usuarios,aa.id_lote";
	    $tab1  = " tes_lote aa LEFT JOIN usuarios bb ON bb.id_usuarios = aa.id_usuarios ";
	    $whe1  = " aa.id_lote = $_id_lote";
	    $rsConsulta1 = $cuentasPagar->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	    
	    if(!empty($rsConsulta1)){
	        $_usuario_documento = ( $rsConsulta1[0]->usuario_usuarios != null || $rsConsulta1[0]->usuario_usuarios != "" ) ?  $rsConsulta1[0]->usuario_usuarios : "N/D";
	    }
	    /** TERMINA DATOS LOTE **/
	    
	    $datos_cuentas_pagar['USUARIODOCUMENTO'] = $_usuario_documento;
	    $datos_cuentas_pagar['NOMBRELOTE'] = $rsDatosCxp[0]->nombre_lote;
	    $datos_cuentas_pagar['DESCLOTE'] = $rsDatosCxp[0]->descripcion_lote;
	    $datos_cuentas_pagar['FRECUENCIA'] = $rsDatosCxp[0]->nombre_frecuencia_lote;
	    $datos_cuentas_pagar['NUMEROLOTE'] = $rsDatosCxp[0]->numero_lote;
	    //$datos_cuentas_pagar['TIPODOCUMENTO'] = $rsDatosCxp[0]->abreviacion_tipo_documento;
	    $datos_cuentas_pagar['NUMEROCOMPROBANTE'] = $rsDatosCxp[0]->numero_cuentas_pagar;
	    $datos_cuentas_pagar['NUMERODOCUMENTO'] = $rsDatosCxp[0]->numero_documento_cuentas_pagar;
	    $datos_cuentas_pagar['FECHADOCUMENTO'] = $rsDatosCxp[0]->fecha_cuentas_pagar;
	    $datos_cuentas_pagar['IDEPROVEEDOR'] = $rsDatosCxp[0]->identificacion_proveedores;
	    $datos_cuentas_pagar['NOMBREPROVEEDOR'] = $rsDatosCxp[0]->nombre_proveedores;
	    $datos_cuentas_pagar['CONDONACIONES'] = $rsDatosCxp[0]->condonaciones_cuentas_pagar;
	    $datos_cuentas_pagar['SALDOCUENTA'] =number_format((float)$rsDatosCxp[0]->saldo_cuenta_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['COMPRAS'] = number_format((float)$rsDatosCxp[0]->compras_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['DESCCOMERCIAL'] = number_format((float)$rsDatosCxp[0]->descuento_comercial_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['FLETE'] = number_format((float)$rsDatosCxp[0]->flete_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['MISCELANEOS'] = number_format((float)$rsDatosCxp[0]->miscelaneos_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['IMPUESTO'] = number_format((float)$rsDatosCxp[0]->impuesto_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['DF'] = $rsDatosCxp[0]->descripcion_lote;
	    $datos_cuentas_pagar['GH'] = $rsDatosCxp[0]->nombre_frecuencia_lote;
	    $datos_cuentas_pagar['KL'] = $rsDatosCxp[0]->numero_lote;
	    $datos_cuentas_pagar['DESCRIPCIONDOCUMENTO'] = $rsDatosCxp[0]->descripcion_cuentas_pagar;
	    
	    $fechaDocumento = $rsDatosCxp[0]->fecha_cuentas_pagar;
	    $fechaPeriodo      =  new DateTime($fechaDocumento);
	    $mesPeriodo        = $fechaPeriodo->format('m');
	    $peridoDocumento   = $this->getNameMonth($mesPeriodo)."/".$fechaPeriodo->format('Y');	    
	    $datos_cuentas_pagar['PERIODOFISCAL'] = $peridoDocumento;
	    
	    //DISTRIBUCION DE CONTABILIDAD
	    
	    $id_lote = $rsDatosCxp[0]->id_lote;
	    
	    $columnasDistribucion= "id_distribucion_cuentas_pagar, id_lote, pc.id_plan_cuentas, pc.codigo_plan_cuentas,
    		pc.nombre_plan_cuentas, tipo_distribucion_cuentas_pagar,
    		debito_distribucion_cuentas_pagar,  credito_distribucion_cuentas_pagar";
	    
	    $tablasDistribucion = "tes_distribucion_cuentas_pagar dis
            inner join plan_cuentas pc
            on dis.id_plan_cuentas = pc.id_plan_cuentas";
	    
	    $whereDistribucion = " dis.id_lote = $id_lote ";
	    
	    $idDistribucion = " dis.ord_distribucion_cuentas_pagar ";
	    
	    $rsdatosDistribucion = $cuentasPagar->getCondiciones($columnasDistribucion, $tablasDistribucion, $whereDistribucion, $idDistribucion);
	    
	    if( empty($rsdatosDistribucion) ){
	        
	        $this->nodatapdf("Distribucion en Cuenta por Pagar No Realizada");
	        
	    }
	    
	    if(!empty($rsdatosDistribucion)){
	        
	        $tabladistribucion = "<table class=\"tab3datos\"> <caption> Distribuciones de Contabilidad </caption> ";
	        $sumaDebito = 0.00;
	        $sumaCredito = 0.00;
	        $tabladistribucion .= "<tr>";
	        $tabladistribucion .= "<th>Cuenta</th>";
	        $tabladistribucion .= "<th>Descripción Cuenta</th>";
	        $tabladistribucion .= "<th>Tipo de Cuenta</th>";
	        $tabladistribucion .= "<th>Monto débito</th>";
	        $tabladistribucion .= "<th>Monto crédito</th>";
	        $tabladistribucion .= "</tr>";
	        
	        foreach ($rsdatosDistribucion as $res){
	            $tabladistribucion .= "<tr>";
	            $tabladistribucion .= "<td>".$res->codigo_plan_cuentas."</td>";
	            $tabladistribucion .= "<td>".$res->nombre_plan_cuentas."</td>";
	            $tabladistribucion .= "<td>".$res->tipo_distribucion_cuentas_pagar."</td>";
	            $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$res->debito_distribucion_cuentas_pagar, 2, ',', '.')."</td>";
	            $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$res->credito_distribucion_cuentas_pagar, 2, ',', '.')."</td>";
	            $tabladistribucion .= "</tr>";
	            
	            $sumaCredito += $res->credito_distribucion_cuentas_pagar;
	            $sumaDebito += $res->debito_distribucion_cuentas_pagar;
	        }
	        
	        $tabladistribucion .= "<tr>";
	        $tabladistribucion .= "<td colspan=\"3\"></td>";
	        $tabladistribucion .= "<td class=\"decimales\" >----------------</td>";
	        $tabladistribucion .= "<td class=\"decimales\" >----------------</td>";
	        $tabladistribucion .= "</tr>";
	        
	        $tabladistribucion .= "<tr>";
	        $tabladistribucion .= "<td colspan=\"3\"></td>";
	        $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$sumaDebito, 2, ',', '.')."</td>";
	        $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$sumaCredito, 2, ',', '.')."</td>";
	        $tabladistribucion .= "</tr>";
	        
	        $tabladistribucion .= "</table>";
	    }
	    
	    $datos_cuentas_pagar['TABLADISTRIBUCION'] = $tabladistribucion;
	    
	    //DISTRIBUCION DETALLE IMPUESTOS
	    
	    $columnasImpuestos= "imp.id_impuestos, imp.nombre_impuestos, id_lote, base_cuentas_pagar_impuestos, valor_cuentas_pagar_impuestos,imp.descripcion_impuestos";
	    
	    $tablasImpuestos = "public.tes_cuentas_pagar_impuestos icp
                    INNER JOIN public.tes_impuestos imp
                    ON icp.id_impuestos = imp.id_impuestos";
	    
	    $whereImpuestos = " icp.id_lote = $id_lote ";
	    
	    $idImpuestos = " imp.id_impuestos ";
	    
	    $rsdatosImpuestos = $cuentasPagar->getCondiciones($columnasImpuestos, $tablasImpuestos, $whereImpuestos, $idImpuestos);
	    
	    if(!empty($rsdatosImpuestos)){
	        
	        $tablaImpuesto = "<table class=\"tab3datos\"> <caption> Distribuciones de detalle de impuestos </caption> ";
	        $sumaImpuesto = 0.00;
	        $tablaImpuesto .= "<tr>";
	        $tablaImpuesto .= "<th>Nombre. detalle impuesto</th>";
	        $tablaImpuesto .= "<th>Descripción detalle impuesto</th>";
	        $tablaImpuesto .= "<th>Monto impuesto</th>";
	        $tablaImpuesto .= "</tr>";
	        
	        foreach ($rsdatosImpuestos as $res){
	            $tablaImpuesto .= "<tr>";
	            $tablaImpuesto .= "<td>".$res->nombre_impuestos."</td>";
	            $tablaImpuesto .= "<td>".$res->descripcion_impuestos."</td>";
	            $tablaImpuesto .= "<td class=\"decimales\" >$ ".number_format((float)$res->valor_cuentas_pagar_impuestos, 2, ',', '.')."</td>";
	            $tablaImpuesto .= "</tr>";
	            
	            $sumaImpuesto += $res->valor_cuentas_pagar_impuestos;
	        }
	        
	        $tablaImpuesto .= "<tr>";
	        $tablaImpuesto .= "<td colspan=\"2\"></td>";
	        $tablaImpuesto .= "<td class=\"decimales\" >----------------</td>";
	        $tablaImpuesto .= "</tr>";
	        
	        $tablaImpuesto .= "<tr>";
	        $tablaImpuesto .= "<td colspan=\"2\"></td>";
	        $tablaImpuesto .= "<td class=\"decimales\" >$ ".number_format((float)$sumaImpuesto, 2, ',', '.')."</td>";
	        $tablaImpuesto .= "</tr>";
	        
	        $tablaImpuesto .= "</table>";
	    }
	    
	    $datos_cuentas_pagar['TABLAIMPUESTOS'] = $tablaImpuesto;
	    
	    
	    $this->verReporte("CuentasPagar", array('datos_cuentas_pagar'=>$datos_cuentas_pagar,'datos_empresa'=>$datos_empresa,'datos_cabecera'=>$datos_cabecera));
	    
	}
	
	public function nodatapdf($mensaje=""){
	    
	    $texto = ($mensaje=="") ? "Documento No Encontrado" : $mensaje;
	    
	    include dirname(__FILE__).'\..\view\fpdf\fpdf.php';
	    $pdf = new FPDF();
	    $pdf->AddPage();
	    $pdf->SetFont("Times", "B", 14);
	    $ancho = $pdf->GetPageWidth()-20;
	    $alto = $pdf->GetPageHeight()/3;
	    $pdf->Cell( $ancho, $alto,$texto,0,1,'C');
	    
	    $pdf->Output();
	}
	
	function getNameMonth($numMes){
	    $nombreMes = "N/D";
	    $arrayMes = array('01'=>'ENERO','02'=>'FEBRERO','03'=>'MARZO','04'=>'ABRIL','05'=>'MAYO','06'=>'JUNIO','07'=>'JULIO','08'=>'AGOSTO','09'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
	    for( $i = 0; $i < sizeof($arrayMes); $i++){
	        if( array_key_exists($numMes, $arrayMes) ){
	            $nombreMes = $arrayMes["$numMes"];
	            break;
	        }
	    }
	    return $nombreMes;
	}
	
	public function ReporteIndex(){
	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    session_start();
	    
	    if(empty( $_SESSION)){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        return;
	    }
	    
	    $nombre_controladores = "ReportesTesoreria";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $cuentasPagar->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso Reporte de Cuentas Pagar"
	            
	        ));
	        exit();
	    }
	    
	    $this->view_tesoreria("CuentasPagar",array( ));
	    
	}
	
	public function ListaCuentasPagar(){
	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    $id_lote = (isset($_POST['id_lote'])) ? $_POST['id_lote'] : 0;
	    
	    $columnas = " id_cuentas_pagar, numero_cuentas_pagar, id_tipo_documento,
                    descripcion_cuentas_pagar, id_lote,  DATE(fecha_cuentas_pagar) fecha_cuentas_pagar,
                    id_proveedor, condiciones_pago, id_banco, id_moneda, numero_documento_cuentas_pagar,
                    numero_orden_compra_cuentas_pagar, compras_cuentas_pagar";
	    
	    $tablas = "tes_cuentas_pagar ";
	    
	    $where = "1 = 1 ";
	    
	    $id = " fecha_cuentas_pagar";
	    
	    $where_to = "";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND ( numero_documento_cuentas_pagar LIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	        }
	        
	        $html="";
	        $resultSet = $cuentasPagar->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet = $cuentasPagar->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:200px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_impuestos_cxp' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 11px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Numero</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Documento </th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Fecha Ingreso</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Proveedor</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Banco</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Documento Num.</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Compra</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;"></th>';
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_cuentas_pagar.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->id_tipo_documento.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_cuentas_pagar.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->id_proveedor.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->id_banco.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_documento_cuentas_pagar.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->compras_cuentas_pagar.'</td>';
	                $html.='<td style="font-size: 15px;">
                            <a data-id="'.$res->id_cuentas_pagar.'"   href="#" class="btn btn-default input-sm showpdf" style="font-size:65%;" data-toggle="tooltip" title="Ver pdf"><i class="glyphicon glyphicon-print"></i></a></td>';
	                
	                $html.='</tr>';
	                
	            }
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"cargaCuentasPagar").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            
	            
	        }
	        
	        echo $html;
	        
	    }
	}
		
	/****************************************************** funciones de pruebas *****************************/
	function verGenFileXml(){
	    
	    var_dump($this->genXmlRetencion(150));
	}
	
	
	public function validateValores(){
	    
	    echo "valor --> 0.228 ",round('0.228',2),"\n";
	    echo "valor --> 0.227 ",round('0.227',2),"\n";
	    echo "valor --> 0.226 ",round('0.226',2),"\n";
	    echo "valor --> 0.225 ",round('0.225',2),"\n";
	    echo "valor --> 0.224 ",round('0.224',2),"\n";
	    
	    $incremento = 0.1;
	    $base = 110;
	    for($i=0; $i<1000; $i++){
	        $imp = 0.12;
	        $base += $incremento;
	        $valor = $base*$imp;
	        echo "base --->",$base," valor --> ",$valor, " redondeado ---> ",round($valor,2),"\n";
	    }
	    
	}
	
	
}
?>