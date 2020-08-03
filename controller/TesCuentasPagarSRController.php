<?php

class TesCuentasPagarSRController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
	    
	    $Productos = new ProductosModel();
			
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
		
		$this->view_tesoreria("IngresoTransaccionesSR",array(
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
	    $columnas1 .= "aa.razon_social_proveedores, aa.tipo_identificacion_proveedores ";
	    $tablas1   = " proveedores aa
    	    INNER JOIN tes_tipo_proveedores bb ON aa.id_tipo_proveedores = bb.id_tipo_proveedores";
	    $where1    = " bb.nombre_tipo_proveedores in( 'PAGO PROVEEDORES', 'EMPLEADO' )";
	    $id1       = " aa.nombre_proveedores";
	    
	    if( strlen($busqueda) > 0 )
	    {
	        $where1 .= " AND ( aa.identificacion_proveedores ILIKE '$busqueda%' OR aa.nombre_proveedores ILIKE '%$busqueda%' ) ";
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
	        
	        //$estiloConfigProveedores = "style=\"color:#E37B71;\"";
	        
	        if( $res->id_tipo_proveedores == 3 && $res->razon_social_proveedores == "" && $res->tipo_identificacion_proveedores == "04")
	            $estiloConfigProveedores = "style=\"color:red;\"";
	        
	        $i++;
	        
	        $razonSocial = empty($res->razon_social_proveedores) ? "N/D" : $res->razon_social_proveedores;
	        $direccion = empty($res->direccion_proveedores) ? "N/D" : $res->direccion_proveedores;
	        $celular = empty($res->celular_proveedores) ? "N/D" : $res->celular_proveedores;
	        
	        $btonSelect = "<button onclick=\"SelecionarProveedor(this)\" value=\"$res->id_proveedores\" class=\"btn btn-default\"> 
                        <i aria-hidden=\"true\" class=\"fa fa-external-link\"></i> </button>";
	        $htmlTr    .= "<tr $estiloConfigProveedores >";
	        $htmlTr    .= "<td>" . $i . "</td>";
	        $htmlTr    .= "<td>" . $res->identificacion_proveedores . "</td>";
	        $htmlTr    .= "<td>" . $razonSocial . "</td>";
	        $htmlTr    .= "<td>" . $res->nombre_proveedores . "</td>";	
	        $htmlTr    .= "<td>" . $direccion . "</td>";	
	        $htmlTr    .= "<td>" . $celular . "</td>";	
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
	           INNER JOIN plan_cuentas bb ON bb.id_plan_cuentas = aa.id_plan_cuentas";
	    $wheImpuestos  = " 1 = 1 ";
	    $idIMpuestos   = " bb.codigo_plan_cuentas";
	    //$rsImpuestos   = $impuestos->getCondiciones($colImpuestos, $tabImpuestos, $wheImpuestos, $idIMpuestos);
	         
	    if( strlen($busqueda) > 0 ){
	        $wheImpuestos .= " AND ( aa.nombre_impuestos ILIKE '%$busqueda%' OR aa.codigo_impuestos ILIKE '$busqueda%' OR bb.codigo_plan_cuentas ILIKE '$busqueda%' ) ";
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
	       
	        //$estiloConfigImpuestos = "bgcolor=\"#E37B71\"";
	        
	        /** siguiente validacion es solo hasta que se arreglen los impuestos **/
	        if( $res->id_plan_cuentas == null || ( empty($res->codigo_impuestos) && $res->tipo_impuestos == "ret" ) || (empty($res->codigo_impuestos) && $res->tipo_impuestos == "retiva" )  ){
	            //echo ""," PLAN CUENTAS --> ",$res->id_plan_cuentas,"   CODIGO IMPUESTOS --> ",$res->codigo_impuestos, "    TIPO IMPUESTO ---> ", $res->tipo_impuestos," <br> \n";
	            $estiloConfigImpuestos = "style=\"color:red\"";
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
	        
	        $orden_insert ++;
	        	        
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
	        
	        $numero_autorizacion_cuentas_pagar = $_POST['numero_autorizacion'];
	        
	        if( !empty(error_get_last()) ){ throw new Exception(" Variable no definidas"); }
	        
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
	        
	        /*actualizar cuentas pagar Numero Autorizacion */ //Parche numero uno
	        $colvalAutorizacion = " numero_autorizacion_cuentas_pagar = $numero_autorizacion_cuentas_pagar";
	        $tabvalAutorizacion = " tes_cuentas_pagar";
	        $whevalAutorizacion = " id_cuentas_pagar = $_id_cuentas_pagar";
	        
	        $cuentasPagar ->ActualizarBy($colvalAutorizacion, $tabvalAutorizacion, $whevalAutorizacion);
	        
	        if( !empty(pg_last_error()) ){ throw new Exception(" Numero de autorizacion no Ingresado"); }
	        
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
            
            $error_pg = pg_last_error(); if( !empty($error_pg) ){ throw new Exception("no se inserto la cuenta por pagar ".$error_pg ); }
            $error_php = error_get_last(); if( !empty($error_php) ){ throw new Exception("no se inserto la cuenta por pagar".$error_php['message'] ); }
            
            //PROCESO TERMINA SIN GENERACION DE ARCHIVO XML
            
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
	
	
	
	public function verExpresiones(){
	    
	    $_expresion = "/^[0-9]{3}-[0-9]{3}-[0-9]{9}$/";
	    $_texto = "051-001-123456789";	    
	    
	    if (preg_match($_expresion, $_texto )) {
	        echo "Se encontró una coincidencia.";
	    } else {
	        echo "No se encontró ninguna coincidencia.";
	    }
	    
	    echo "variables es cero <br>";
	    
	    $numero = "";
	    
	    var_dump(is_int((int)$numero));
	    
	    $numero = "0";
	    
	    echo "el numero transformado es --> '",$numero,"' <br>";
	    
	    if( $numero === "" ){
	        echo "ingreso vacio<br>";
	    }else{
	        echo "no nngreso vacio <br>";
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
	
	/***
	 * @author dc 2020/05/30
	 * @desc funcion para cambiar la base de la retencion
	 */
	public function modificarBaseRetencion()
	{
	    ob_start();
	    
	    $response  = array();
	    $cpagar    = new CuentasPagarModel();
	    $id_lote   = $_POST['id_lote'];
	    
	    $col1  = " aa.id_lote, aa.id_cuentas_pagar_impuestos, bb.id_impuestos, bb.nombre_impuestos, bb.porcentaje_impuestos, aa.base_cuentas_pagar_impuestos, 
            aa.valor_base_cuentas_pagar_impuestos, aa.valor_cuentas_pagar_impuestos ";
	    $tab1  = " tes_cuentas_pagar_impuestos aa
	       INNER JOIN tes_impuestos bb ON bb.id_impuestos = aa.id_impuestos";
	    $whe1  = " aa.id_lote = $id_lote
	       AND bb.tipo_impuestos in ( 'ret', 'retiva' )";
	    $id1   = " aa.id_cuentas_pagar_impuestos";
	    
	    $rsConsulta1   = $cpagar->getCondiciones($col1, $tab1, $whe1, $id1);
	    $html  = "";
	    if( !empty( $rsConsulta1 ) )
	    {
	        $html  .= '<h3 class="text-center bg-info"> Valores Retencion </h3>';
	        $html  .= '<table id="tbl_modificar_base_retencion" >';
	        $html  .= '<thead>';
	        $html  .= '<tr>';
	        $html  .= '<th>#</th>';
	        $html  .= '<th>Nombre</th>';
	        $html  .= '<th>Valor Compra</th>';
	        $html  .= '<th>Base Imp</th>';
	        $html  .= '<th>% Imp</th>';
	        $html  .= '<th>Valor Impuesto</th>';
	        $html  .= '</tr>';
	        $html  .= '</thead>';
	        $html  .= '<tbody>';
	        
	        $index = 0;
	        foreach ( $rsConsulta1 as $res )
	        {
	            $input1 = '<input type="text" class="form-control" name="base" value="'.number_format( $res->valor_base_cuentas_pagar_impuestos , 2 , '.' , '' ).'" >';
	            $input2 = '<input type="text" class="form-control" name="pocentage" value="'.number_format( $res->porcentaje_impuestos , 2 , '.' , '' ).'" readonly >';
	            $input3 = '<input type="text" class="form-control" name="valor" value="'.number_format( $res->valor_cuentas_pagar_impuestos , 2 , '.' , '' ).'" readonly >';
	            $index ++;
	            $html  .= '<tr data-id="'.$res->id_cuentas_pagar_impuestos.'">';
	            $html  .= '<td>'.$index.'</td>';
	            $html  .= '<td>'.$res->nombre_impuestos.'</td>';
	            $html  .= '<td>'.number_format( $res->base_cuentas_pagar_impuestos , 2 , '.' , '' ).'</td>';
	            $html  .= '<td>'.$input1.'</td>';
	            $html  .= '<td>'.$input2.'</td>';
	            $html  .= '<td>'.$input3.'</td>';
	            $html  .= '</tr>';	            
	        }
	        	        
	        $html  .= '</tbody>';
	        
	        $input3 = ' ';
	        $input3 = '<div class="input-group"> 
                       <input type="text" class="form-control" name="total" value="0" > 
                       <span class="input-group-btn">
        	           <button type="button" title="Validar Retencion" class="btn btn-info"  id="btn_enviar_retencion" onclick="enviar_cambio_retencion()">
        	           <i class="glyphicon glyphicon-ok"></i>
        	           </button>
        	           </span>        
            	       </div>';
	        
	        $html  .= '<tfoot>';
	        $html  .= '<tr>';
	        $html  .= '<th></th>';
	        $html  .= '<th></th>';
	        $html  .= '<th></th>';
	        $html  .= '<th></th>';
	        $html  .= '<th>Total</th>';
	        $html  .= '<th>'.$input3.'</th>';
	        $html  .= '</tr>';
	        $html  .= '</tfoot>';
	        $html  .= '</table>';
	    }
	    
	    $salida    = ob_get_contents();
	    
	    $response['html'] = $html;
	    $response['estatus'] = "OK";
	    echo json_encode( $response );
	      
	    
	}
	
	/***
	 * @author dc 2020/05/31
	 * @desc function para cambiar valor base retencion
	 */
	public function  setValorRetencionNuevo()
	{
	    //inicio variables
	    session_start();
	    $cpagar    = new CuentasPagarModel();
	    $resp  = array();
	    ob_start();
	    
	    //variables de vista
	    $data_retencion = json_decode( $_POST['data_retencion']);
	    $id_lote   = $_POST['id_lote'];
	    $validador  = false;
	    if( sizeof( $data_retencion ) )
	    {
	        $cpagar->beginTran();
	        $valor_base    = 0.00;
	        $valor_total   = 0.00;
	        $id_cuentas_pagar_impuestos    = 0;
	        $strUpdate = "UPDATE tes_cuentas_pagar_impuestos SET valor_base_cuentas_pagar_impuestos = $valor_base, valor_cuentas_pagar_impuestos = $valor_total
WHERE id_cuentas_pagar_impuestos = $id_cuentas_pagar_impuestos";
	        foreach ( $data_retencion as $res)
	        {
	            $valor_base    = $res->base;
	            $valor_total   = $res->valor;
	            $id_cuentas_pagar_impuestos    = $res->id;
	            
	            $strUpdate = "UPDATE tes_cuentas_pagar_impuestos SET valor_base_cuentas_pagar_impuestos = $valor_base, valor_cuentas_pagar_impuestos = $valor_total
WHERE id_cuentas_pagar_impuestos = $id_cuentas_pagar_impuestos";
	            
	            $cpagar->executeNonQuery($strUpdate);
	            
	            $error = error_get_last();
	            if( !empty( $error ) )
	            {
	                $validador = true;
	            }
	        }//end de foreach
	        
	        $col1 = " aa.id_lote, aa.id_cuentas_pagar_impuestos, bb.nombre_impuestos, bb.tipo_impuestos, cc.credito_distribucion_cuentas_pagar,
	            cc.debito_distribucion_cuentas_pagar, cc.id_distribucion_cuentas_pagar, cc.tipo_distribucion_cuentas_pagar, aa.valor_cuentas_pagar_impuestos";
	        $tab1 = " tes_cuentas_pagar_impuestos aa
    	        INNER JOIN tes_impuestos bb ON bb.id_impuestos = aa.id_impuestos
    	        INNER JOIN tes_distribucion_cuentas_pagar cc ON  cc.id_plan_cuentas = bb.id_plan_cuentas";
	        $whe1 = " cc.id_lote = aa.id_lote
	            AND aa.id_lote = $id_lote";
	        $id1  = " cc.ord_distribucion_cuentas_pagar";
	        
	        $rsConsulta1   = $cpagar->getCondiciones( $col1, $tab1, $whe1, $id1);
	        
	        if ( !empty( $rsConsulta1 ) )
	        {
	            $sumaparciales = 0; //variable para sumar valores de distribucion
	            foreach ( $rsConsulta1 as $res )
	            {
	                if ( strtoupper( $res->tipo_impuestos ) == "RET" )
	                {
	                    $id_distribucion   = $res->id_distribucion_cuentas_pagar;
	                    $valor_distribucion= abs( $res->valor_cuentas_pagar_impuestos );
	                    $strAct    = "UPDATE tes_distribucion_cuentas_pagar SET credito_distribucion_cuentas_pagar = $valor_distribucion WHERE id_distribucion_cuentas_pagar = $id_distribucion";
	                    $cpagar->executeNonQuery( $strAct );

	                    if( !empty( error_get_last() ) )
	                    {
	                        $validador = true;
	                    }
	                    
	                    $sumaparciales += $valor_distribucion;
	                }
	                
	            }// end bucle de consulta
	            
	            if( !$validador )
	            {
	                $col2  = " debito_distribucion_cuentas_pagar ";
	                $tab2  = " tes_distribucion_cuentas_pagar";
	                $whe2  = " tipo_distribucion_cuentas_pagar = 'COMPRA' AND id_lote = $id_lote";
	                $rsConsulta2   = $cpagar->getCondicionesSinOrden($col2, $tab2, $whe2, "LIMIT 1");
	               
	                $valorCompra   = $rsConsulta2[0]->debito_distribucion_cuentas_pagar;
	                $valorPago     = abs( $valorCompra -  $sumaparciales );
	                
	                $strAct    = "UPDATE tes_distribucion_cuentas_pagar SET credito_distribucion_cuentas_pagar = $valorPago WHERE tipo_distribucion_cuentas_pagar = 'PAGO' id_lote = $id_lote";
	                if( !empty( error_get_last() ) )
	                {
	                    $validador = true;
	                }
	            }
	        }// end if de busqueda 
	        
	        $resp['estatus'] = "OK";
	        $resp['mensaje'] = "Modificacion Base Retencion Realizada";
	        
	        $salida = trim( ob_get_clean() ); 
	        if( $validador || !empty( $salida ) )
	        {
	            $cpagar->endTran();
	            echo "Error Revisar Valores Enviados";
	        }
	        
	        $cpagar->endTran('COMMIT');
	        echo json_encode($resp);
	    }
	    
	}
	
	/***
	 * @desc funcion que permite elimnar la distribucion de la cuenta por pagar
	 * @author dc 2020/06/01
	 */
	public function EliminarDistribucion()
	{
	    //variables de inicio
	    $cpagar    = new CuentasPagarModel();
	    ob_start();
	    $mensaje   = "";
	    $resp  = array();
	    $validador = false;
	    $cpagar->beginTran();
	    
	    //variables vista
	    $id_lote   = $_POST['id_lote'];
	    
	    $col1  = " id_cuentas_pagar";
	    $tab1  = " tes_cuentas_pagar";
	    $whe1  = " id_lote = $id_lote";
	    
	    $rsConsulta1   = $cpagar->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	    
	    if( !empty( $rsConsulta1 ) )
	    {
	        $mensaje   = "existe cuenta por pagar registrada con dato enviado";
	        $validador = true;
	    }else
	    {
	        $strEliminar = " DELETE FROM tes_distribucion_cuentas_pagar WHERE id_lote = $id_lote ";
	        $cpagar->executeNonQuery( $strEliminar );
	        if( !empty( error_get_last() ) )
	        {
	            $validador = true;
	        }
	    }
	    
	    $resp['estatus'] = "OK";
	    $resp['mensaje'] = "peticion realizada";
	    
	    $salida    = trim(ob_get_clean());
	    if( $validador || !empty( $salida ) )
	    {
	        $cpagar->endTran();
	        echo $mensaje;
	        //echo $salida;
	    }else
	    {	
	        $cpagar->endTran("COMMIT");
	        echo json_encode($resp); 
	    }
	    
	}
	
	public function reloadValoresTransacciones()
	{
	    //variables de inicio
	    $cpagar    = new CuentasPagarModel();
	    ob_start();
	    $resp  = array();
	    
	    //variables vista
	    $id_lote        = $_POST['id_lote'];
	    $_compra_cero   = $_POST['compra_cero'];
	    $_compra_iva    = $_POST['compra_iva'];
	    
	    $base_compras = $_compra_cero + $_compra_iva;
	    $base_compras = round($base_compras,2);
	    
	    /** enviar valores de impuesto que genera **/
	    $col1 = " base_cuentas_pagar_impuestos, valor_base_cuentas_pagar_impuestos, valor_cuentas_pagar_impuestos";
	    $tab1 = " public.tes_cuentas_pagar_impuestos ";
	    $Whe1 = " id_lote = $id_lote ";
	    $id1  = " creado ";
	    $rsConsulta1  = $cpagar->getCondiciones($col1, $tab1, $Whe1, $id1);
	    
	    if( !empty($rsConsulta1) )
	    {
	        $_total = $base_compras;
	        $_total_impuesto = 0;
	        $_saldo_documento = 0;
	        foreach ($rsConsulta1 as $res) {
	            $_total_impuesto += $res-> valor_cuentas_pagar_impuestos;
	        }
	        $_saldo_documento = $_total + $_total_impuesto;
	        
	        $resp['total_impuestos'] = number_format( $_total_impuesto, 2, ".", "");
	        $resp['saldo_total'] = number_format( $_saldo_documento, 2, ".", "");
	    }
	    
	    $salida    = trim(ob_get_clean());
	    if( !empty( $salida ) )
	    {	        
	        echo $salida;
	        //echo $salida;
	    }else
	    {
	        $resp['estatus'] = "OK";
	        echo json_encode($resp);
	    }
	    
	}
	
	/***
	 * dc 2020-07-14
	 *
	 */
	public function autompletePlanCuentas(){
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    if(isset($_GET['term'])){
	        
	        $codigo_plan_cuentas = $_GET['term'];
	        
	        $columnas = "id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas";
	        $tablas = "public.plan_cuentas";
	        $where = "codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND nivel_plan_cuentas > 3";
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
	
		
}
?>