<?php

class ArchivoPagoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
	    
	    $archivoPago = new ArchivoPagoModel();
	    		
		if( empty( $_SESSION['usuario_usuarios'] ) ){
		    $this->redirect("Usuarios","sesion_caducada");
		    exit();
		}
		
		$nombre_controladores = "ArchivoPago";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $archivoPago->getPermisosVer(" controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso al proceso de Generar el Archivo de pago"
		        
		    ));
		    exit();
		}
		
		$this->view_tesoreria("ArchivoPago",array(		    
		));
		
	
	}
	
	/***
	 * @desc function para dibujar bancos locales de la entidad por medio de request ajax
	 * @param none
	 */
	public function CargaBancosLocal(){
	    
	    $pagos = new PagosModel();
	    $resp  = null;
	    
	    $col1  = " id_bancos, nombre_bancos, lpad(index_bancos_chequera::text,espacio_bancos_chequera,'0') consecutivo_cheque , abrev_bancos_chequera";
	    $tab1  = " tes_bancos ";
	    $whe1  = " local_bancos = 't'";
	    $id1   = " nombre_bancos ";
	    $rsConsulta1   = $pagos->getCondiciones($col1, $tab1, $whe1, $id1);
	    
	    try {
	        
	        $error_pg = pg_last_error();
	        if( !empty($error_pg) ){
	            throw new Exception( $error_pg );
	        }
	        
	        if( !empty($rsConsulta1) ){
	            $resp['data'] = $rsConsulta1;
	        }else{
	            $resp['data'] = null;
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
	 * @desc function para dibujar tipo archivo para generar archivo plano
	 * @param none
	 */
	public function CargaTipoArchivo(){
	    
	    $archivoPago   = new ArchivoPagoModel();
	    $resp  = null;
	    
	    $col1  = " id_tipo_pago_archivo, UPPER(nombre_tipo_pago_archivo) nombre_tipo_pago_archivo";
	    $tab1  = " public.tes_tipo_pago_archivo ";
	    $whe1  = " 1 = 1";
	    $id1   = " id_tipo_pago_archivo";
	    $rsConsulta1   = $archivoPago->getCondiciones($col1, $tab1, $whe1, $id1);
	    
	    try {
	        
	        $error_pg = pg_last_error();
	        if( !empty($error_pg) ){
	            throw new Exception( $error_pg );
	        }
	        
	        if( !empty($rsConsulta1) ){
	            $resp['data'] = $rsConsulta1;
	        }else{
	            $resp['data'] = null;
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
	
	public function buscarDatosArchivo(){
	    
	    session_start();
	    $archivoPago = new ArchivoPagoModel();
	    $resp = null;
	    
	    try {
	        
	        $id_tipo_archivo_pago  = $_POST['id_tipo_archivo_pago'];
	        $fecha_proceso         = $_POST['fecha_proceso'];
	        $id_bancos_local       = $_POST['id_bancos'];
	        	        
	        if( !empty(error_get_last()) ) {
	            throw new Exception("Variables no definidas");
	        }
	        
	        $col1  = " id_tipo_pago_archivo, UPPER(nombre_tipo_pago_archivo) nombre_tipo_pago_archivo ";
	        $tab1  = " public.tes_tipo_pago_archivo";
	        $whe1  = " id_tipo_pago_archivo = $id_tipo_archivo_pago";
            $rsConsulta1    = $archivoPago->getCondicionesSinOrden($col1, $tab1, $whe1, "");
            
            if( empty($rsConsulta1) ){                
                throw new Exception("Tipo de archivo no definido");
            }
            
            //tomo datos de tipo pago archivo
            $nombre_pago_archivo    = $rsConsulta1[0]->nombre_tipo_pago_archivo;
            $Auxiliar = null;
            switch ($nombre_pago_archivo){
                case "CREDITOS":
                    $Auxiliar = $this->auxDatosCreditos($fecha_proceso);
                    break;
                case "PROVEEDORES":
                    $Auxiliar = $this->auxDatosProveedores($fecha_proceso);
                    break;
                default:
                    $resp['mensaje']    = "llego al default";
            }
            
            if( $Auxiliar['error'] === true ){
                throw new Exception($Auxiliar['mensaje']);
            }
            
            $resp['tabla'] = $Auxiliar['data'];
	        
	        $resp['icon'] = "success";
	        $resp['estatus'] = "OK";
	        
	    } catch (Exception $e) {
	        
	        $buffer =  error_get_last();
	        $pg_error  = pg_last_error();
	        $resp['icon'] = isset($resp['icon']) ? $resp['icon'] : "error";
	        $resp['mensaje'] = $e->getMessage();
	        $resp['msgServer'] = $buffer; //buscar guardar buffer y guaradr en variable
	        $resp['bdmensaje'] = $pg_error;
	        $resp['estatus'] = "ERROR";
	    }
	    
	    error_clear_last();
	    if (ob_get_contents()) ob_end_clean();
	    
	    echo json_encode($resp);
	}
	
	function auxDatosCreditos($fecha_proceso){
	    
	    $pagos = new PagosModel();
	    $aux = null;
	    
	    // tomamos los datos individuales para enviar a la consulta
	    $fecha = DateTime::createFromFormat('d/m/Y', $fecha_proceso);
	    $anio_parametros = $fecha->format('Y');
	    $mes_parametros = $fecha->format('m');
	    $dia_parametros = $fecha->format('d');
	    
	    $col1  = " 'creditos' \"tipo\", ee.cedula_participes, ee.nombre_participes, ee.apellido_participes, dd.numero_creditos, aa.anio_creditos_trabajados_cabeza || '-' ||
            aa.mes_creditos_trabajados_cabeza || '-' || aa.dia_creditos_trabajados_cabeza \"fecha_creditos_trabajados\", dd.monto_neto_entregado_creditos,
            bb.id_detalle_creditos_trabajados, ff.id_cuentas_pagar";
	    $tab1  = " core_creditos_trabajados_cabeza aa
    	    INNER JOIN core_creditos_trabajados_detalle bb ON bb.id_cabeza_creditos_trabajados = aa.id_creditos_trabajados_cabeza
    	    INNER JOIN estado cc ON cc.id_estado = bb.id_estado_detalle_creditos_trabajados
    	    INNER JOIN core_creditos dd ON dd.id_creditos = bb.id_creditos
    	    INNER JOIN core_participes ee ON ee.id_participes = dd.id_participes
            INNER JOIN tes_cuentas_pagar ff on ff.id_ccomprobantes = dd.id_ccomprobantes
            INNER JOIN estado gg on gg.id_estado = ff.id_estado";
	    $whe1  = " 1=1
            AND cc.nombre_estado = 'APROBADO GERENTE'
            AND cc.tabla_estado = 'core_creditos_trabajados_detalle'
            AND gg.nombre_estado = 'GENERADO'
            AND gg.tabla_estado  = 'tes_cuentas_pagar'
    	    AND aa.anio_creditos_trabajados_cabeza = $anio_parametros
    	    AND aa.mes_creditos_trabajados_cabeza = $mes_parametros
    	    AND aa.dia_creditos_trabajados_cabeza = $dia_parametros";
	    
	    $rsConsulta1 = $pagos->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	    
	    if( empty($rsConsulta1) ){
	        // si datos estan vacios devolver array con error
	        return array('error'=>true,'mensaje'=>"No existe datos con parametros solicitados");
	    }
	    
	    //creacion de columnas para cabecera tabla
	    $htmlHead = "";//variables html thead
	    $htmlHead .= "<thead>";
	    $htmlHead .= "<tr>";
	    $htmlHead .= "<th>#</th>";
	    $htmlHead .= "<th>Tipo</th>";
	    $htmlHead .= "<th>Identificacion</th>";
	    $htmlHead .= "<th>Nombre</th>";
	    $htmlHead .= "<th>Apellido</th>";
	    $htmlHead .= "<th>Numero Credito</th>";
	    $htmlHead .= "<th>Fecha</th>";
	    $htmlHead .= "<th>Valor</th>";
	    $htmlHead .= "</tr>";
	    $htmlHead .= "</thead>";
	    $htmlBody = "<tbody>"; // variables html tbody
	    
	    $i = 0;
	    $sumaTotales = 0.00;
	    
	    foreach ($rsConsulta1 as $res) {
	        $i++;
	        $htmlBody .= "<tr>";
	        $htmlBody .= "<td>".$i."</td>";
	        $htmlBody .= "<td>CREDITOS</td>";
	        $htmlBody .= "<td>".$res->cedula_participes."</td>";
	        $htmlBody .= "<td>".$res->apellido_participes."</td>";
	        $htmlBody .= "<td>".$res->nombre_participes."</td>";
	        $htmlBody .= "<td>".$res->numero_creditos ."</td>";
	        $htmlBody .= "<td>".$res->fecha_creditos_trabajados ."</td>";
	        $htmlBody .= "<td>".$res->monto_neto_entregado_creditos ."</td>";
	        $htmlBody .= "</tr>";
	        
	        $sumaTotales += (double)$res->monto_neto_entregado_creditos;
	    }
	    $htmlBody .= "</tbody>";
	    
	    //dependiendo del numero de columnas el rowspan cambia para el diseno de la tabla en la vista
	    $htmlFoot = "<tfoot><tr><td colspan=\"7\"></td><td>".$sumaTotales."</td></tr></tfoot>";
	    
	    $aux['error'] = false;
	    $aux['data'] = $htmlHead.$htmlBody.$htmlFoot;
	    $aux['totales'] = $sumaTotales;
	    
	    return $aux;
	}
	
	function auxDatosProveedores($fecha_proceso){
	    
	    $pagos = new PagosModel();
	    $aux = null;
	    
	    // tomamos los datos individuales para enviar a la consulta
	    $fecha = DateTime::createFromFormat('d/m/Y', $fecha_proceso);
	    $fecha_buscar = $fecha->format('Y-m-d');
	    
	    $col1  = "'PROVEEDORES' \"tipo\", bb.identificacion_proveedores, bb.nombre_proveedores, aa.numero_documento_cuentas_pagar, aa.fecha_cuentas_pagar,
            aa.total_cuentas_pagar, aa.id_cuentas_pagar";	    
        $tab1  = " tes_cuentas_pagar aa
            INNER JOIN proveedores bb ON bb.id_proveedores = aa.id_proveedor
            INNER JOIN estado  cc ON cc.id_estado = aa.id_estado
            INNER JOIN tes_tipo_proveedores dd ON dd.id_tipo_proveedores = bb.id_tipo_proveedores";
        $whe1  = " cc.tabla_estado = 'tes_cuentas_pagar'
            AND cc.nombre_estado = 'GENERADO'
            AND dd.nombre_tipo_proveedores = 'PAGO PROVEEDORES'
            AND aa.fecha_cuentas_pagar = '$fecha_buscar'";
        	        
        $rsConsulta1 = $pagos->getCondicionesSinOrden($col1, $tab1, $whe1, "");
        
        if( empty($rsConsulta1) ){
            // si datos estan vacios devolver array con error
            return array('error'=>true,'mensaje'=>"No existe datos con parametros solicitados");
        }
        
        //creacion de columnas para cabecera tabla
        $htmlHead = "";//variables html thead
        $htmlHead .= "<thead>";
        $htmlHead .= "<tr>";
        $htmlHead .= "<th>#</th>";
        $htmlHead .= "<th>Tipo</th>";
        $htmlHead .= "<th>Identificacion</th>";
        $htmlHead .= "<th>Nombres</th>";
        $htmlHead .= "<th>Numero Documento</th>";
        $htmlHead .= "<th>Fecha</th>";
        $htmlHead .= "<th>Valor Pago</th>";
        $htmlHead .= "</tr>";
        $htmlHead .= "</thead>";
        $htmlBody = "<tbody>"; // variables html tbody
        
        $i = 0;
        $sumaTotales = 0.00;
	        
        foreach ($rsConsulta1 as $res) {
            $i++;
            $htmlBody .= "<tr>";
            $htmlBody .= "<td>".$i."</td>";
            $htmlBody .= "<td>".$res->tipo."</td>";
            $htmlBody .= "<td>".$res->identificacion_proveedores."</td>";
            $htmlBody .= "<td>".$res->nombre_proveedores."</td>";
            $htmlBody .= "<td>".$res->numero_documento_cuentas_pagar ."</td>";
            $htmlBody .= "<td>".$res->fecha_cuentas_pagar ."</td>";
            $htmlBody .= "<td>".$res->total_cuentas_pagar ."</td>";
            $htmlBody .= "</tr>";
            
            $sumaTotales += (double)$res->total_cuentas_pagar;
        }
        $htmlBody .= "</tbody>";
        
        //dependiendo del numero de columnas el rowspan cambia para el diseno de la tabla en la vista
        $htmlFoot = "<tfoot><tr><td colspan=\"6\"></td><td>".$sumaTotales."</td></tr></tfoot>";
        
        $aux['error'] = false;
        $aux['data'] = $htmlHead.$htmlBody.$htmlFoot;
        $aux['totales'] = $sumaTotales;
        
        return $aux;
	}
	
	
	/****************************************************** BEGIN VER ARCHIVOS CREADOS *************************************/
	
	public function index2(){
	    
	    session_start();
	    
	    $archivoPago = new ArchivoPagoModel();
	    
	    if( empty( $_SESSION['usuario_usuarios'] ) ){
	        $this->redirect("Usuarios","sesion_caducada");
	        exit();
	    }
	    
	    $nombre_controladores = "genArchivoPago";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $archivoPago->getPermisosVer(" controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso al proceso de Generar el Archivo de pago"
	            
	        ));
	        exit();
	    }
	    
	    $this->view_tesoreria("ListaArchivoPago",array(
	    ));
	}
	
	public function showArchivoPago(){
	    
	    $pagos = new PagosModel();
	    $respuesta = null;
	    
	    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	    
	    //varaibles de parametros de busqueda
	    $fecha_proceso         = ( isset( $_POST['fecha_proceso'] ) ) ? $_POST['fecha_proceso'] : "";
	    $id_tipo_archivo_pago  = ( isset( $_POST['id_tipo_archivo_pago'] ) ) ? $_POST['id_tipo_archivo_pago'] : "0";
	    $id_bancos             = ( isset( $_POST['id_bancos'] ) ) ? $_POST['id_bancos'] : "0";	    
	    
	    $columnas1 = " aa.id_archivo_pago, cc.nombre_tipo_pago_archivo,aa.fecha_proceso_archivo_pago, (select nombre_bancos from tes_bancos where id_bancos = bb.id_bancos) nombre_bancos,
	       aa.tipo_pago_archivo_pago, aa.numero_identificacion_archivo_pago, aa.beneficiario_archivo_pago, bb.valor_pagos, aa.codigo_banco_archivo_pago";
	    $tablas1   = " tes_archivo_pago aa
	       INNER JOIN tes_pagos bb ON bb.id_pagos = aa.id_pagos
	       INNER JOIN tes_tipo_pago_archivo cc ON cc.id_tipo_pago_archivo = aa.id_tipo_pago_archivo";
	    $where1    = " 1 = 1
    	    AND aa.fecha_proceso_archivo_pago = '$fecha_proceso'
    	    AND aa.id_tipo_pago_archivo = $id_tipo_archivo_pago ";
	    $id1       = " aa.id_archivo_pago";
	    
	    if( !empty($id_bancos) ){
	        $where1 .= " AND bb.id_bancos_local = $id_bancos";
	    }
	    
	    //var_dump( $_POST);
	    
	    $columnasSumatorias = " COUNT(1) AS cantidad, SUM( bb.valor_pagos ) AS total" ;
	    $resultSet = $pagos->getCondicionesSinOrden( $columnasSumatorias , $tablas1, $where1,"");
	    $cantidadResult=(int)$resultSet[0]->cantidad;
	    
	    //para las sumatorias del archivo 
	    $sumatoriaTotal   = (double)$resultSet[0]->total;
	    $sumatoriaParcial = 0.00;	    
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet = $pagos->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    //echo "SELECT ",$columnas1," FROM ", $tablas1," WHERE ", $where1, " ORDER BY ", $id1, $limit;
	    
	    $error = error_get_last();
	    if( !empty($error) ){
	        echo $error['message'];
	        exit();
	    }
	    $htmlTr = "";
	    $i = 0;
	    //$estiloConfigProveedores = ""; // variable donde se guarda estilo css para indicar al usuario que debe configurar datos de proveedor
	    
	    $htmlHead = "";
	    $htmlHead .= "<thead>";
	    $htmlHead .= "<tr>";
	    $htmlHead .= "<th>#</td>";
	    $htmlHead .= "<th>Tipo</td>";
	    $htmlHead .= "<th>Fecha</td>";
	    $htmlHead .= "<th>Banco Beneficiario</td>";
	    $htmlHead .= "<th>Tipo Pago</td>";
	    $htmlHead .= "<th>Identificacion</td>";
	    $htmlHead .= "<th>Beneficiario</td>";
	    $htmlHead .= "<th>Valor</td>";
	    $htmlHead .= "<th>Cod. Banco</td>";
	    $htmlHead .= "</tr>";
	    $htmlHead .= "</thead>";
	    
	    //para los datos de la tabla	    
	    $htmlBody = "<tbody>";
	    foreach ($resultSet as $res){
	        
	        $i++;
	        $tipoPago  = ( strtoupper($res->tipo_pago_archivo_pago) == "D" ) ? "DIRECTA" : "INTERBANCARIA";
	        $htmlBody    .= "<tr data-archivo-id=\"". $res->id_archivo_pago ."\" >";
	        $htmlBody    .= "<td>" . $i . "</td>";
	        $htmlBody    .= "<td>" . $res->nombre_tipo_pago_archivo . "</td>";
	        $htmlBody    .= "<td>" . $res->fecha_proceso_archivo_pago . "</td>";
	        $htmlBody    .= "<td>" . $res->nombre_bancos . "</td>";
	        $htmlBody    .= "<td>" . $tipoPago . "</td>";
	        $htmlBody    .= "<td>" . $res->numero_identificacion_archivo_pago . "</td>";
	        $htmlBody    .= "<td>" . $res->beneficiario_archivo_pago . "</td>";
	        $htmlBody    .= "<td>" . $res->valor_pagos . "</td>";
	        $htmlBody    .= "<td>" . $res->codigo_banco_archivo_pago . "</td>";
	        $htmlBody    .= "</tr>";
	        
	        $sumatoriaParcial += (double)$res->valor_pagos;
                
	    }
	    
	    $htmlBody .= "</tbody>";
	    
	    $htmlFoot = "<tfoot>";
	    $htmlFoot .= "<tr>";
	    $htmlFoot .= "<th>TOTAL</th>";
	    $htmlFoot .= "<th>".$sumatoriaTotal."</th>";
	    $htmlFoot .= "<th colspan=\"4\"></th>";
	    $htmlFoot .= "<th>VALOR PARCIAL</th>";
	    $htmlFoot .= "<th>".$sumatoriaParcial."</th>";
	    $htmlFoot .= "<th></th>";
	    $htmlFoot .= "</tr>";
	    $htmlFoot .= "</tfoot>";
	    
	    $respuesta['tabla'] = $htmlHead.$htmlBody.$htmlFoot;
	    
	    $respuesta['filas']    = $htmlTr;
	    
	    $htmlPaginacion  = '<div class="table-pagination pull-right">';
	    $htmlPaginacion .= ''. $this->paginate("index.php", $page, $total_pages, $adjacents,"loadArchivoPago").'';
	    $htmlPaginacion .= '</div>';
	    
	    $respuesta['paginacion'] = $htmlPaginacion;
	    $respuesta['cantidadDatos'] = $cantidadResult;
	    
	    echo json_encode( $respuesta );
	}
	
	/***
	 * @param POST
	 * @desc funcion que permite creacion del archivo txt
	 */
	public function GenerarArchivoPago(){
	    
	    $archivoPago = new ArchivoPagoModel();
	    $resp = null;	    
	    
	    try {
	        //varaibles de parametros de busqueda
	        $fecha_proceso         = ( isset( $_POST['fecha_proceso'] ) ) ? $_POST['fecha_proceso'] : "";
	        $id_tipo_archivo_pago  = ( isset( $_POST['id_tipo_archivo_pago'] ) ) ? $_POST['id_tipo_archivo_pago'] : "0";
	        $id_bancos             = ( isset( $_POST['id_bancos'] ) ) ? $_POST['id_bancos'] : "0";
	        $tipo_pago_archivo_pago= ( isset( $_POST['tipo_pago_archivo'] ) ) ? strtoupper($_POST['tipo_pago_archivo']) : "";	        
	        
	        $oFecha = new DateTime( str_replace("/", "-", $fecha_proceso) );
	        $anioProceso   = $oFecha->format('Y');
	        $mesProceso    = $oFecha->format('m');
	        //$diaProceso    = $oFecha->format('d');
	        echo "FECHA DE PROCESO ES -->",$anioProceso,"\n";
	        
	        $error = error_get_last();
	        if( !empty($error) ){
	            throw new Exception("Variables no definidas");
	        }
	        
	        $columnas1 = " aa.id_archivo_pago, aa.pago_archivo_pago, aa.contrapartida_archivo_pago, aa.moneda_archivo_pago, aa.valor_archivo_pago, aa.cuenta_archivo_pago,
            	aa.tipo_cuenta_archivo_pago, aa.numero_cuenta_archivo_pago, aa.referencia_archivo_pago, aa.tipo_identificacion_archivo_pago, aa.numero_identificacion_archivo_pago,
            	aa.beneficiario_archivo_pago,codigo_banco_archivo_pago,aa.tipo_pago_archivo_pago, cc.nombre_tipo_pago_archivo  ";
	        $tablas1   = " tes_archivo_pago aa
	           INNER JOIN tes_pagos bb ON bb.id_pagos = aa.id_pagos
	           INNER JOIN tes_tipo_pago_archivo cc ON cc.id_tipo_pago_archivo = aa.id_tipo_pago_archivo";
	        $where1    = " 1 = 1
    	       AND aa.fecha_proceso_archivo_pago = '$fecha_proceso'
    	       AND aa.id_tipo_pago_archivo = $id_tipo_archivo_pago 
               AND aa.tipo_pago_archivo_pago = '$tipo_pago_archivo_pago' ";
	        
	        //$resp['mensajeECHO'] = "SELECT ".$columnas1." FROM ".$tablas1." WHERE ".$where1;
	        
	        if( !empty($id_bancos) ){
	            $where1 .= " AND bb.id_bancos_local = $id_bancos";
	        }
	        
	        $rsConsulta1 = $archivoPago->getCondicionesSinOrden($columnas1, $tablas1, $where1, "");
	        
	        $error = error_get_last();
	        if( !empty($error) ||  empty( $rsConsulta1 ) ){
	            throw new Exception("Consulta a la Bd No definida! LLamar al Administrador Sistema");
	        }
	      	
	        
	        $baseNombreTxt = $rsConsulta1[0]->nombre_tipo_pago_archivo; // se toma el nombre del archivo
	        $baseNombreTxt = strtoupper($baseNombreTxt);
	        $tipoPagoArchivo   = $rsConsulta1[0]->tipo_pago_archivo_pago; // variable para almacenar tipo pago Directa|Interbancaria 
	        
	        if( strtoupper($tipoPagoArchivo) == "D" ){
	            $baseNombreTxt = $baseNombreTxt."_";
	        }else{
	            $baseNombreTxt = $baseNombreTxt."_Inter_";
	        }	        
	        
	        //datos archivo txt
	        $_TXT_Pago = $this->obtienePath($baseNombreTxt, $anioProceso, $mesProceso);
	        $_nombre_archivo_pago  = $_TXT_Pago['nombre'];
	        $_ruta_archivo_pago    = $_TXT_Pago['ruta'];
	        
	        $_cantidad_registros   = sizeof($rsConsulta1);
	        /* para generar grupos */
	        $_ultima_fila = $_cantidad_registros-1;
	        
	        $databody	= "";
	        $contFila = 0;
	        for( $i=0; $i<$_cantidad_registros; $i++){
	            
	            $tmpcol1   = $rsConsulta1[$i]->pago_archivo_pago;
	            $tmpcol2   = $rsConsulta1[$i]->contrapartida_archivo_pago;
	            $tmpcol3   = $rsConsulta1[$i]->moneda_archivo_pago;
	            $tmpcol4   = $rsConsulta1[$i]->valor_archivo_pago;
	            $tmpcol5   = $rsConsulta1[$i]->cuenta_archivo_pago;
	            $tmpcol6   = $rsConsulta1[$i]->tipo_cuenta_archivo_pago;
	            $tmpcol7   = $rsConsulta1[$i]->numero_cuenta_archivo_pago;
	            $tmpcol8   = $rsConsulta1[$i]->referencia_archivo_pago;
	            $tmpcol9   = $rsConsulta1[$i]->tipo_identificacion_archivo_pago;
	            $tmpcol10  = $rsConsulta1[$i]->numero_identificacion_archivo_pago;
	            $tmpcol11  = $rsConsulta1[$i]->beneficiario_archivo_pago;
	            $tmpcol12  = $rsConsulta1[$i]->codigo_banco_archivo_pago;
	            	            
	            $databody  .=  trim($tmpcol1)."\t".trim($tmpcol2)."\t".trim($tmpcol3)." ".trim($tmpcol4)."\t".trim($tmpcol5)."\t".trim($tmpcol6)."\t";
	            $databody  .=  trim($tmpcol7)."\t".trim($tmpcol8)."\t".trim($tmpcol9)." ".trim($tmpcol10)."\t".trim($tmpcol11)."\t".trim($tmpcol12).PHP_EOL;
	            
	            $contFila ++;                       
	            
	        }
	        
	        /* estructurar el archivo */
	        $datahead	= ""; //archivo no tiene cabecera 
	        
	        /*** buscar otro metodo para archivos grandes evitar acumulacion memoria al generar todo en una variable */
	        $archivo = fopen($_ruta_archivo_pago, 'w');
	        fwrite($archivo, $datahead.$databody);
	        fclose($archivo);
	        
	        $error = error_get_last();
	        if(!empty($error)){
	            throw new Exception('Archivo no generado');
	        }
	        
	        $resp['nombreFile'] = $_nombre_archivo_pago;
	        $resp['urlFile'] = $_ruta_archivo_pago; 
	        $resp['estatus'] = "OK";	        
	       
	        
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
	
	public function DescargarArchivoPago(){
	    
	    $ubicacionArchivo  = $_POST['urlFile'];
	    $nombreArchivo     = $_POST['nombreFile'];
	    
	    $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\";
	    $ubicacion = $ubicacionServer.$ubicacionArchivo;
	    
	    // Define headers
	    header("Content-disposition: attachment; filename=$nombreArchivo");
	    header("Content-type: MIME");
	    ob_clean();
	    flush();
	    // Read the file
	    //echo $ubicacion;
	    //print_r($_POST);
	    //echo  "******llego--",$_tipo_archivo_recaudaciones,"***" ;
	    //echo "parametro id ---",$_id_archivo_recaudaciones,"**";
	    readfile($ubicacion);
	    
	}
	
	/**
	 * funcion que devuele array con el nombre y la ruta de archivo
	 * @param int $anioArchivo
	 * @param int $mesArchivo
	 */
	private function obtienePath($nombreArchivo,$anioArchivo,$mesArchivo){
	    
	    $respuesta     = array();
	    $nArchivo      = $nombreArchivo.$mesArchivo.$anioArchivo.".txt";
	    $carpeta_base      = 'view\\tesoreria\\documentos\\transferencias\\';
	    $_carpeta_buscar   = $carpeta_base.$anioArchivo;
	    $file_buscar       = "";
	    if( file_exists($_carpeta_buscar)){
	        
	        $_carpeta_buscar   = $carpeta_base.$anioArchivo."\\".$mesArchivo;
	        if( file_exists($_carpeta_buscar)){
	            
	            $file_buscar = $_carpeta_buscar."\\".$nArchivo;
	            
	            
	        }else{
	            
	            mkdir($_carpeta_buscar, 0777, true);
	            $file_buscar = $_carpeta_buscar."\\".$nArchivo;
	            
	        }
	        
	    }else{
	        
	        mkdir($_carpeta_buscar."\\".$mesArchivo, 0777, true);
	        $file_buscar = $_carpeta_buscar."\\".$mesArchivo."\\".$nArchivo;
	    }
	    
	    $respuesta['nombre']   = $nArchivo;
	    $respuesta['ruta']     = $file_buscar;
	    
	    return $respuesta;
	}
	
	/****************************************************** END VER ARCHIVOS CREADOS *************************************/
	
}
?>