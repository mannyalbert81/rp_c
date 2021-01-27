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
// 	    $where1    = " 1 = 1
//     	    AND aa.fecha_proceso_archivo_pago = '$fecha_proceso'
//     	    AND aa.id_tipo_pago_archivo = $id_tipo_archivo_pago ";
	    $where1    = " 1 = 1";
	    $id1       = " aa.id_archivo_pago";
	    
	    if( !empty( $id_bancos ) )
	    {
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
	    $htmlHead .= "<th></th>";
	    $htmlHead .= "<th>#</th>";
	    $htmlHead .= "<th>Tipo</th>";
	    $htmlHead .= "<th>Fecha</th>";
	    $htmlHead .= "<th>Banco Beneficiario</th>";
	    $htmlHead .= "<th>Tipo Pago</th>";
	    $htmlHead .= "<th>Identificacion</th>";
	    $htmlHead .= "<th>Beneficiario</th>";
	    $htmlHead .= "<th>Cod. Banco</th>";
	    $htmlHead .= "<th>Valor</th>";
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
	        $htmlBody    .= "<td>" . $res->codigo_banco_archivo_pago . "</td>";
	        $htmlBody    .= "<td>" . $res->valor_pagos . "</td>";	       
	        $htmlBody    .= "</tr>";
	        
	        $sumatoriaParcial += (double)$res->valor_pagos;
                
	    }
	    
	    $htmlBody .= "</tbody>";
	    
	    $htmlFoot = "<tfoot>";
	    $htmlFoot .= "<tr>";
	    $htmlFoot .= "<th>TOTAL</th>";
	    $htmlFoot .= "<th>".$sumatoriaTotal."</th>";
	    $htmlFoot .= "<th></th>";
	    $htmlFoot .= "<th colspan=\"4\"></th>";
	    $htmlFoot .= "<th>V. PARCIAL</th>";
	    $htmlFoot .= "<th>".$sumatoriaParcial."</th>";	   
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
	        //$fecha_proceso         = ( isset( $_POST['fecha_proceso'] ) ) ? $_POST['fecha_proceso'] : "";
	        //$id_tipo_archivo_pago  = ( isset( $_POST['id_tipo_archivo_pago'] ) ) ? $_POST['id_tipo_archivo_pago'] : "0";
	        //$id_bancos             = ( isset( $_POST['id_bancos'] ) ) ? $_POST['id_bancos'] : "0";
	        //$tipo_pago_archivo_pago= ( isset( $_POST['tipo_pago_archivo'] ) ) ? strtoupper($_POST['tipo_pago_archivo']) : "";
	        $listaArchivoPagos     = $_POST['lista_archivo_pagos'];	        
	        $listaArchivoPagos     = json_decode($listaArchivoPagos);
	        
	        //$oFecha = new DateTime( str_replace("/", "-", $fecha_proceso) );
	        
	        $anioProceso   = date('Y');
	        $mesProceso    = date('m');
	        //$diaProceso    = $oFecha->format('d');
	        //echo "FECHA DE PROCESO ES -->",$anioProceso,"\n";
	        
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
	        $where1    = " 1 = 1 ";
	        
	        $lista_id_archivo_pago = "";
	        $tipoPagoArchivo       = "";
	        $baseTipoPago          = "";
	        $baseNombreTxt         = "";
	        
	        $databody  = "";
	        $contFila  = 0;
	        $index     = 0; 
	        
	        foreach( $listaArchivoPagos as $res )
	        {
	            $id_archivo_pago   = $res->id_archivo_pago;	            
	            $whereid   = " AND aa.id_archivo_pago = $id_archivo_pago ";
	            $wherefin  = $where1.$whereid;	            
	            $rsConsulta1 = $archivoPago->getCondicionesSinOrden($columnas1, $tablas1, $wherefin, "");
	            
	            if( !empty($rsConsulta1) )
	            {	                              
	                $lista_id_archivo_pago .= $id_archivo_pago.",";
	                $tipoPagoArchivo   = $rsConsulta1[0]->tipo_pago_archivo_pago;
	                if( strtoupper($tipoPagoArchivo) == "I" )
	                {
	                    $baseTipoPago          = "INTER";
	                }
	                $baseNombreTxt     = $rsConsulta1[0]->nombre_tipo_pago_archivo;
	                $baseNombreTxt = mb_strtoupper($baseNombreTxt);
	                
	                $tmpcol1   = $rsConsulta1[$index]->pago_archivo_pago;
	                $tmpcol2   = $rsConsulta1[$index]->contrapartida_archivo_pago;
	                $tmpcol3   = $rsConsulta1[$index]->moneda_archivo_pago;
	                $tmpcol4   = $rsConsulta1[$index]->valor_archivo_pago;
	                $tmpcol5   = $rsConsulta1[$index]->cuenta_archivo_pago;
	                $tmpcol6   = $rsConsulta1[$index]->tipo_cuenta_archivo_pago;
	                $tmpcol7   = $rsConsulta1[$index]->numero_cuenta_archivo_pago;
	                $tmpcol8   = $rsConsulta1[$index]->referencia_archivo_pago;
	                $tmpcol9   = $rsConsulta1[$index]->tipo_identificacion_archivo_pago;
	                $tmpcol10  = $rsConsulta1[$index]->numero_identificacion_archivo_pago;
	                $tmpcol11  = $rsConsulta1[$index]->beneficiario_archivo_pago;
	                $tmpcol12  = $rsConsulta1[$index]->codigo_banco_archivo_pago;
	                
	                $databody  .=  trim($tmpcol1)."\t".trim($tmpcol2)."\t".trim($tmpcol3)." ".trim($tmpcol4)."\t".trim($tmpcol5)."\t".trim($tmpcol6)."\t";
	                $databody  .=  trim($tmpcol7)."\t".trim($tmpcol8)."\t".trim($tmpcol9)." ".trim($tmpcol10)."\t".trim($tmpcol11)."\t".trim($tmpcol12).PHP_EOL;
	                
	                $contFila ++;
	            }
	            
	        }
	        
	        #TRANSACCIONABILIDAD --comienza cambios a la base de datos
	        $archivoPago->beginTran();
	        
	        $sqlSecuencialAP  = "SELECT nextval('index_consecutivo_archivo_pago_seq')";
	        $rsSecuencialAP   = $archivoPago->llamarconsultaPG($sqlSecuencialAP);
	        $SecuencialAP     = $rsSecuencialAP[0];
	        
	        $baseNombreTxt = (!empty($baseTipoPago) ) ? $baseNombreTxt.'_'.$baseTipoPago.'_' : $baseNombreTxt.'_';
	        	        
	        #ESTABLESCO nombre de archivo con secuencial
	        $baseNombreTxt = "AP".$SecuencialAP."_".$baseNombreTxt;
	        //datos archivo txt
	        $_TXT_Pago = $this->obtienePath($baseNombreTxt, $anioProceso, $mesProceso);
	        $_nombre_archivo_pago  = $_TXT_Pago['nombre'];
	        $_ruta_archivo_pago    = $_TXT_Pago['ruta'];
	        
	        /* estructurar el archivo */
	        $datahead	= ""; //archivo no tiene cabecera
	        
	        $fechaHoy  = date('Y-m-d H:i:s');
	        
	        $lista_id_archivo_pago = trim($lista_id_archivo_pago,',');
	        $lista_id_archivo_pago = $lista_id_archivo_pago;
	        	        	        
	        
	        $sqlUpdate = " UPDATE tes_archivo_pago SET generado_archivo_pago = 't', nombre_txt_archivo_pago = '$_nombre_archivo_pago' , fecha_generado_archivo_pago = '$fechaHoy'  WHERE id_archivo_pago IN ($lista_id_archivo_pago) ";
	        $archivoPago->executeNonQuery($sqlUpdate);
	        
	        /*** buscar otro metodo para archivos grandes evitar acumulacion memoria al generar todo en una variable */
	        $archivo = fopen($_ruta_archivo_pago, 'w');
	        fwrite($archivo, $datahead.$databody);
	        fclose($archivo);
	        
	        $error = error_get_last();
	        var_dump($error);
	        
	        if(!empty($error)){
	            $archivoPago->endTran('ROLLBACK');
	            throw new Exception('Archivo no generado');
	        }
	        
	        $resp['nombreFile'] = $_nombre_archivo_pago;
	        $resp['urlFile'] = $_ruta_archivo_pago;
	        $resp['estatus'] = "OK";
	        
	        #TRANSACCIONABILIDAD termina
	        $archivoPago->endTran('COMMIT');
	        
	        
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
	    
	    $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\".CARPETA_APP."\\";
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
	    $carpeta_base      = 'DOCUMENTOS_GENERADOS\\ARCHIVOPAGO\\transferencias\\';
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
	
	/** dc 2020/09/22 */
	public function CargaUsuarioDepartamento()
	{
	    ob_start();
	    $pagos = new PagosModel();
	    $resp  = null;
	    
	    $col1  = " bb.id_usuarios, bb.nombre_usuarios, bb.apellidos_usuarios, aa.id_rol, bb.id_oficina";
	    $tab1  = " public.rol aa
	       INNER JOIN public.usuarios bb ON bb.id_rol = aa.id_rol";
	    $whe1  = " bb.id_estado = 1
	       AND aa.nombre_rol = 'Jefe de crédito y prestaciones'";
	    $id1   = " bb.nombre_usuarios ";
	    $rsConsulta1   = $pagos->getCondiciones($col1, $tab1, $whe1, $id1);
	    	    
	    try {
	        
	        $error_pg = pg_last_error();
	        if( !empty($error_pg) ){
	            throw new Exception( $error_pg );
	        }
	        
	        $resp['data']  = $rsConsulta1 ?? null;
	       	        
	    } catch (Exception $e) {
	        $buffer =  error_get_last();
	        $resp['icon'] = isset($resp['icon']) ? $resp['icon'] : "error";
	        $resp['mensaje'] = $e->getMessage();
	        $resp['msgServer'] = $buffer; //buscar guardar buffer y guaradr en variable
	        $resp['estatus'] = "ERROR";
	    }
	    
	    $salida    = trim(ob_get_clean());
	    $resp['buffer'] = isset($resp['buffer']) ? $resp['buffer'] : "$salida";

	    echo json_encode($resp);
	}
	/** end dc 2020/09/22 **/
	
	/** dc 2020/09/22 **/
	public function dtdatosArchivoPago()
	{
	    ob_start();
	    $archivo   = new ArchivoPagoModel();
	    
	    try {
	        
	        $requestData = $_REQUEST;
	        	        
	        #ESTABLECEMOS variables de vista
	        $id_tipo_archivo   = $_POST['tipo_archivo'];
	        $fecha_proceso     = $_POST['fecha_proceso'];
	        $id_bancos         = $_POST['id_bancos'];
	        $id_usuario        = $_POST['id_usuario']; // el usuario obtenido es de jefe de area
	        $id_oficina        = $_POST['id_oficina']; // oficina del usuario seleccionado
	        
	        $col1  = " id_tipo_pago_archivo, nombre_tipo_pago_archivo";
	        $tab1  = " public.tes_tipo_pago_archivo";
	        $whe1  = " id_tipo_pago_archivo = $id_tipo_archivo ";
	        
	        $rsConsulta1   = $archivo->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	        
	        if( empty($rsConsulta1) ) throw new Exception("Revisar Variables enviadas");
	        
	        $nombre_archivo    = strtoupper($rsConsulta1[0]->nombre_tipo_pago_archivo); 
	        $fecha_proceso     = str_replace("/","-",$fecha_proceso);
	        
	        $data  = array();
	        $cantidad  = 0;
	        $sql   = "";
	        #Ejemplo array a llenar
	        
	        #los metodos solo devuelven array con datos de acuerdo a la estrucutra en el ejemplo
	        switch ($nombre_archivo)
	        {
	            case 'CREDITOS':
	                # aqui llenar datos enviar a vista
	                $parametros = array();
	                $parametros['id_bancos']   = $id_bancos;
	                $parametros['id_oficina']  = $id_oficina;
	                $parametros['fecha_proceso'] = $fecha_proceso;
	                
	                $resultado = $this->getArchivoPagoCreditos($requestData, $parametros); 
	                
	                $data      = $resultado['data'];
	                $cantidad  = $resultado['cantidad'];  
	                $sql       = $resultado['sql'];
	                
                break;
	            case 'PROVEEDORES':
	                # aqui llenar datos enviar a vista
	                $parametros = array();
	                $parametros['id_bancos']   = $id_bancos;
	                $parametros['id_oficina']  = $id_oficina;
	                $parametros['fecha_proceso'] = $fecha_proceso;
	                $parametros['id_tipo_pago_archivo'] = $id_tipo_archivo;
	                
	                $resultado = $this->getArchivoPagoProveedores($requestData, $parametros);
	                
	                $data      = $resultado['data'];
	                $cantidad  = $resultado['cantidad'];
	                $sql       = $resultado['sql'];
	                
                break;
	            default:
	                $data  = array();
                break;
	                
	        }
	        	        	        
	        $salida = ob_get_clean();
	        if( !empty($salida ) ) throw new Exception();
	        
	        $json_data = array(
	            "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	            "recordsTotal" => intval($cantidad),  // total number of records
	            "recordsFiltered" => intval($cantidad), // total number of records after searching, if there is no searching then totalFiltered = totalData
	            "data" => $data,   // total data array
	            "sql" => $sql
	        );
	        
    	} catch (Exception $e) {
    	    
    	    $salida = ob_get_clean();
    	    
    	    $json_data = array(
    	        "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
    	        "recordsTotal" => intval("0"),  // total number of records
    	        "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
    	        "data" => array(),   // total data array
    	        "sql" => $sql ?? "" ,
    	        "buffer" => error_get_last(),
    	        "ERRORDATATABLE" => $e->getMessage()
    	    );
    	}	
	    
	    echo json_encode($json_data);
	}
	/** end dc 2020/09/22 **/
	
	/** dc 2020/09/22 */
	public function getArchivoPagoCreditos(array $dtRequest, array $datos)
	{
	    $archivo   = new ArchivoPagoModel();
	    
	    #DATOS plugin datatable
	    $searchDataTable   = $dtRequest['search']['value'];
	    
	    #DATOS parametros vista
	    $id_bancos     = $datos['id_bancos'];
	    $id_oficina    = $datos['id_oficina'];
	    $fecha_proceso = $datos['fecha_proceso'];
	    
	    $columnas1 = " aa.id_archivo_pago, cc.nombre_tipo_pago_archivo,aa.fecha_proceso_archivo_pago,
    	    (SELECT nombre_bancos FROM tes_bancos WHERE id_bancos = bb.id_bancos) nombre_bancos,
    	    aa.tipo_pago_archivo_pago, aa.numero_identificacion_archivo_pago, aa.beneficiario_archivo_pago, bb.valor_pagos,
    	    aa.codigo_banco_archivo_pago";
	    $tablas1   = " tes_archivo_pago aa
    	    INNER JOIN tes_pagos bb ON bb.id_pagos = aa.id_pagos
    	    INNER JOIN tes_tipo_pago_archivo cc ON cc.id_tipo_pago_archivo = aa.id_tipo_pago_archivo";
	    $where1    = " aa.id_tipo_pago_archivo = 1
    	    AND aa.generado_archivo_pago = false
    	    AND bb.id_bancos_local = $id_bancos
    	    --AND aa.fecha_proceso_archivo_pago = '$fecha_proceso'
    	    AND bb.id_pagos IN (
    	        SELECT ee.id_pagos
    	        FROM core_creditos_trabajados_cabeza aa
    	        INNER JOIN core_creditos_trabajados_detalle bb
    	        ON bb.id_cabeza_creditos_trabajados = aa.id_creditos_trabajados_cabeza
    	        INNER JOIN core_creditos cc on cc.id_creditos = bb.id_creditos
    	        INNER JOIN tes_cuentas_pagar dd on dd.id_ccomprobantes = cc.id_ccomprobantes
    	        INNER JOIN tes_pagos ee on ee.id_cuentas_pagar = dd.id_cuentas_pagar
    	        WHERE  aa.anio_creditos_trabajados_cabeza||'-'||lpad(aa.mes_creditos_trabajados_cabeza::text,2,'0')||'-'||lpad(aa.dia_creditos_trabajados_cabeza::text,2,'0') = '$fecha_proceso'
    	        AND aa.id_oficina = $id_oficina
    	        )";
	    
	    if( strlen( $searchDataTable ) > 0 )
	    {
	        $where1 .= " AND ( ";
	        $where1 .= " aa.numero_identificacion_archivo_pago ILIKE '%$searchDataTable%' ";
	        $where1 .= " OR aa.beneficiario_archivo_pago ILIKE '%$searchDataTable%' ";
	        $where1 .= " ) ";
	        
	    }
	    
	    $rsCantidad    = $archivo->getCantidad("*", $tablas1, $where1);
	    $cantidadBusqueda = (int)$rsCantidad[0]->total;
	    
	    /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/	    
	    // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	    $columns = array(
	        0 => '1',
	        1 => '1',
	        2 => '1',
	        3 => '1',
	        4 => '1',
	        5 => '1',
	        6 => '1',
	        7 => '1',
	        8 => '1',
	        9 => '1'
	    );
	    
	    $orderby   = $columns[$dtRequest['order'][0]['column']];
	    $orderdir  = $dtRequest['order'][0]['dir'];
	    $orderdir  = strtoupper($orderdir);
	    /**PAGINACION QUE VIEN DESDE DATATABLE**/
	    $per_page  = $dtRequest['length'];
	    $offset    = $dtRequest['start'];
	    
	    //para validar que consulte todos
	    $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
	    
	    $limit = " ORDER BY $orderby $orderdir LIMIT   $per_page OFFSET '$offset'";	    
	    $sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
	    //$sql = "";
	    
	    $resultSet=$archivo->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
	    
	    /** crear el array data que contiene columnas en plugins **/
	    $data = array();
	    $dataFila = array();
	    $columnIndex = 0;
	    foreach ( $resultSet as $res){
	        $columnIndex++;
	        
	        $opciones = '<input type="checkbox" class="chk_pago_seleccionado" value="'.$res->id_archivo_pago.'">';
	        	        
	        $dataFila['numfila'] = $columnIndex;
	        $dataFila['tipo']  = $res->nombre_tipo_pago_archivo;
	        $dataFila['fecha'] = $res->fecha_proceso_archivo_pago;
	        $dataFila['banco'] = $res->nombre_bancos;
	        $dataFila['tipo_pago']  = $res->tipo_pago_archivo_pago;
	        $dataFila['identificacion']  = $res->numero_identificacion_archivo_pago;
	        $dataFila['beneficiario']  = $res->beneficiario_archivo_pago;
	        $dataFila['codigo_banco']= $res->codigo_banco_archivo_pago;
	        $dataFila['valor']  = $res->valor_pagos;
	        $dataFila['opciones'] = $opciones;
	        	        
	        $data[] = $dataFila;
	    }
	    	    
	    return array('cantidad'=> $cantidadBusqueda, 'sql'=>$sql, 'data'=>$data);
	}
	/** end dc 2020/09/22 */
	
	/** end dc 2020/09/23 */
	public function getArchivoPagoProveedores(array $dtRequest, array $datos)
	{
	    $archivo   = new ArchivoPagoModel();
	    
	    #DATOS plugin datatable
	    $searchDataTable   = $dtRequest['search']['value'];
	    
	    #DATOS parametros vista
	    $id_tipo_pago_archivo  = $datos['id_tipo_pago_archivo'];
	    $id_bancos     = $datos['id_bancos'];
	    //$id_oficina    = $datos['id_oficina'];
	    $fecha_proceso = $datos['fecha_proceso'];
	    
	    $columnas1 = " aa.id_archivo_pago, cc.nombre_tipo_pago_archivo,aa.fecha_proceso_archivo_pago,
    	    (SELECT nombre_bancos FROM tes_bancos WHERE id_bancos = bb.id_bancos) nombre_bancos,
    	    aa.tipo_pago_archivo_pago, aa.numero_identificacion_archivo_pago, aa.beneficiario_archivo_pago, bb.valor_pagos,
    	    aa.codigo_banco_archivo_pago";
	    $tablas1   = " tes_archivo_pago aa
    	    INNER JOIN tes_pagos bb ON bb.id_pagos = aa.id_pagos
    	    INNER JOIN tes_tipo_pago_archivo cc ON cc.id_tipo_pago_archivo = aa.id_tipo_pago_archivo";
	    $where1    = " aa.id_tipo_pago_archivo = $id_tipo_pago_archivo
    	    AND aa.generado_archivo_pago = false
    	    AND bb.id_bancos_local = $id_bancos
    	    AND aa.fecha_proceso_archivo_pago = '$fecha_proceso' ";
	    
	    if( strlen( $searchDataTable ) > 0 )
	    {
	        $where1 .= " AND ( ";
	        $where1 .= " aa.numero_identificacion_archivo_pago ILIKE '%$searchDataTable%' ";
	        $where1 .= " OR aa.beneficiario_archivo_pago ILIKE '%$searchDataTable%' ";
	        $where1 .= " ) ";
	        
	    }
	    
	    $rsCantidad    = $archivo->getCantidad("*", $tablas1, $where1);
	    $cantidadBusqueda = (int)$rsCantidad[0]->total;
	    
	    /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
	    // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	    $columns = array(
	        0 => '1',
	        1 => '1',
	        2 => '1',
	        3 => '1',
	        4 => '1',
	        5 => '1',
	        6 => '1',
	        7 => '1',
	        8 => '1',
	        9 => '1'
	    );
	    
	    $orderby   = $columns[$dtRequest['order'][0]['column']];
	    $orderdir  = $dtRequest['order'][0]['dir'];
	    $orderdir  = strtoupper($orderdir);
	    /**PAGINACION QUE VIEN DESDE DATATABLE**/
	    $per_page  = $dtRequest['length'];
	    $offset    = $dtRequest['start'];
	    
	    //para validar que consulte todos
	    $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
	    
	    $limit = " ORDER BY $orderby $orderdir LIMIT   $per_page OFFSET '$offset'";
	    $sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
	    //$sql = "";
	    
	    $resultSet=$archivo->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
	    
	    /** crear el array data que contiene columnas en plugins **/
	    $data = array();
	    $dataFila = array();
	    $columnIndex = 0;
	    foreach ( $resultSet as $res){
	        $columnIndex++;
	        
	        $opciones = '<input type="checkbox" class="chk_pago_seleccionado" value="'.$res->id_archivo_pago.'">';
	        
	        $dataFila['numfila'] = $columnIndex;
	        $dataFila['tipo']  = $res->nombre_tipo_pago_archivo;
	        $dataFila['fecha'] = $res->fecha_proceso_archivo_pago;
	        $dataFila['banco'] = $res->nombre_bancos;
	        $dataFila['tipo_pago']  = $res->tipo_pago_archivo_pago;
	        $dataFila['identificacion']  = $res->numero_identificacion_archivo_pago;
	        $dataFila['beneficiario']  = $res->beneficiario_archivo_pago;
	        $dataFila['codigo_banco']= $res->codigo_banco_archivo_pago;
	        $dataFila['valor']  = $res->valor_pagos;
	        $dataFila['opciones'] = $opciones;
	        
	        $data[] = $dataFila;
	    }
	    
	    return array('cantidad'=> $cantidadBusqueda, 'sql'=>$sql, 'data'=>$data);
	}
	/** end dc 2020/09/23 */
	
}
?>