<?php

class PrincipalBusquedasRecaudacionesController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}
		  
    
	public function index(){
	
	    session_start();
		
     	$EntidadPatronal = new EntidadPatronalParticipesModel();
     		
		if( isset(  $_SESSION['nombre_usuarios'] ) ){

			$nombre_controladores = "PrincipalBusquedasRecaudaciones";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $EntidadPatronal->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer)){
			    
			    $queryEntidad = "SELECT * FROM core_entidad_patronal ORDER BY nombre_entidad_patronal";			    
			    $rsEntidadPatronal = $EntidadPatronal->enviaquery($queryEntidad);
			
			    $this->view_Recaudaciones("PrincipalBusquedasRecaudaciones",array(
			        'rsEntidadPatronal' => $rsEntidadPatronal
			    ));
				
			}else{
			    
			    $this->view("Error",array(
			        "resultado"=>"No tiene Permisoss"
			        
			    ));
			    
			    exit();				    
			}
				
		}else{
       	
		    $this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function cargaEntidadPatronal(){
	    
	    $entidad_patronal = null;
	    $entidad_patronal = new EntidadPatronalParticipesModel();
	    
	    $query = "SELECT id_entidad_patronal,nombre_entidad_patronal FROM core_entidad_patronal WHERE 1=1 ORDER BY nombre_entidad_patronal ";
	    
	    $resulset = $entidad_patronal->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	public function dtIngresoBancos()
	
	{
	    if( !isset( $_SESSION ) ){
	        session_start();
	    }
	    
	    try {
	        ob_start();
	        
	        $ingreso_bancos = new RecaudacionesModel();
	        
	        //dato que viene de parte del plugin DataTable
	        $requestData = $_REQUEST;
	        $searchDataTable   = $requestData['search']['value'];
	        
	        /** buscar por el usuario que se encuentra logueado */
	         
	        $id_entidad_patronal = $_POST['id_entidad_patronal'];
	        $anio_ingreso_bancos_cabeza = $_POST['anio_ingreso_bancos_cabeza'];
	        $mes_ingreso_bancos_cabeza = $_POST['mes_ingreso_bancos_cabeza'];
	        
	      $columnas1 = "id_ingreso_bancos_cabeza,
                        id_entidad_patronal,
                        id_bancos,
                        mes_ingreso_bancos_cabeza,
                        anio_ingreso_bancos_cabeza,
                        fecha_deposito_ingreso_bancos_cabeza,
                        fecha_coleccion_ingreso_bancos_cabeza,
                        numero_referencia_ingreso_bancos_cabeza,
                        valor_transaccion_ingreso_bancos_cabeza,
                        diferencia_ingreso_bancos_cabeza,
                        comentario_ingreso_bancos_cabeza,
                        id_ccomprobantes,
                        id_ccomprobantes_reverso,
                        fecha_servidor_ingreso_bancos_cabeza,
                        usuario_usuarios,
                        es_banco_entrada_ingreso_bancos_cabeza,
                        id_estatus";
	        $tablas1   = "core_ingreso_bancos_cabeza";
	        $where1    = "id_entidad_patronal = $id_entidad_patronal
                        and anio_ingreso_bancos_cabeza = $anio_ingreso_bancos_cabeza
                        and mes_ingreso_bancos_cabeza = $mes_ingreso_bancos_cabeza
                        and id_estatus = 1";
	        
	        /* PARA FILTROS DE CONSULTA */
	        
	        if( strlen( $searchDataTable ) > 0 )
	        {
	            #$where1 .= " AND ( ";
	            #$where1 .= " id_entidad_patronal ILIKE '%$searchDataTable%' ";
	            #$where1 .= " OR TO_CHAR(anio_ingreso_bancos_cabeza,'9999') ilike '%$searchDataTable%' ";
	            #$where1 .= " ) ";
	            
	        }
	        
	        $rsCantidad    = $ingreso_bancos->getCantidad("*", $tablas1, $where1);
	        $cantidadBusqueda = (int)$rsCantidad[0]->total;
	        
	        /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
	        
	        // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	        $columns = array(
	            0 => '1',
	            1 => '1',
	            2 => '1',
	            3 => '1'
	        );
	        
	        $orderby   = $columns[$requestData['order'][0]['column']];
	        $orderdir  = $requestData['order'][0]['dir'];
	        $orderdir  = strtoupper($orderdir);
	        /**PAGINACION QUE VIEN DESDE DATATABLE**/
	        $per_page  = $requestData['length'];
	        $offset    = $requestData['start'];
	        
	        //para validar que consulte todos
	        $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
	        
	        $limit = " ORDER BY $orderby $orderdir LIMIT   $per_page OFFSET '$offset'";
	        
	        $sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
	        //$sql = "";
	        
	        $resultSet=$ingreso_bancos->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
	        
	        /** crear el array data que contiene columnas en plugins **/
	        $data = array();
	        $dataFila = array();
	        $columnIndex = 0;
	        foreach ( $resultSet as $res){
	            $columnIndex++;
	            
	            $opciones = "";
	            
	            $opciones = '<div class="pull-right ">
                            <span >
                                <a onclick="mostrar_detalle_modal(this)" id="" data-id_ingreso_bancos_cabeza="'.$res->id_ingreso_bancos_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle Modal"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                                    
                            </div>';
	            
	            $mes = "";
	            if($res->mes_ingreso_bancos_cabeza == 1){
	                $mes = 'Enero';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 2){
	                $mes = 'Febrero';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 3){
	                $mes = 'Marzo';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 4){
	                $mes = 'Abril';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 5){
	                $mes = 'Mayo';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 6){
	                $mes = 'Junio';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 7){
	                $mes = 'Julio';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 8){
	                $mes = 'Agosto';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 9){
	                $mes = 'Septiembre';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 10){
	                $mes = 'Octubre';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 11){
	                $mes = 'Noviembre';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 12){
	                $mes = 'Diciembre';
	            };
	            
	            
	        
	            $dataFila['numfila'] = $columnIndex;
	            $dataFila['mes_ingreso_bancos_cabeza']  = $mes;
	            $dataFila['valor_ingreso_bancos_cabeza'] = $res->valor_transaccion_ingreso_bancos_cabeza;
	            $dataFila['diario_ingreso_bancos_cabeza'] = $res->diferencia_ingreso_bancos_cabeza;
	            $dataFila['opciones'] = $opciones;
	            
	            $data[] = $dataFila;
	        }
	        
	        $salida = ob_get_clean();
	        
	        if( !empty($salida) )
	            throw new Exception($salida);
	            
	            $json_data = array(
	                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	                "recordsTotal" => intval($cantidadBusqueda),  // total number of records
	                "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
	                "data" => $data,   // total data array
	                "sql" => "",//$sql
	            );
	            
	    } catch (Exception $e) {
	        
	        $json_data = array(
	            "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	            "recordsTotal" => intval("0"),  // total number of records
	            "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
	            "data" => array(),   // total data array
	            "sql" => $sql,
	            "buffer" => error_get_last(),
	            "ERRORDATATABLE" => $e->getMessage()
	        );
	    }
	    
	    
	    echo json_encode($json_data);
	}
	
	/**
	 * @author dc
	 * @desc 2020-12-18
	 */
	public function cargaBancosLocales(){
	    
	    $bancos = null;
	    $bancos = new BancosModel();
	    
	    $query = "SELECT id_bancos, nombre_bancos FROM public.tes_bancos WHERE local_bancos = true ORDER BY nombre_bancos ";
	    
	    $resulset = $bancos->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){	        
	        echo json_encode(array('data'=>$resulset));	        
	    }
	}
	
	/**
	 * @author dc
	 * @desc 2020-12-18
	 */
	public function cargaTiposCreditos(){
	    
	    $bancos = null;
	    $bancos = new BancosModel();
	    
	    $query = "SELECT id_tipo_creditos, nombre_tipo_creditos FROM public.core_tipo_creditos WHERE 1 = 1 ORDER BY nombre_tipo_creditos ";
	    
	    $resulset = $bancos->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        echo json_encode(array('data'=>$resulset));
	    }
	    
	}
	
	/**
	 * @author dc
	 * @desc 2020-12-21
	 */
	public function obtenerResumenDescuentosContable()
	{
	    ob_start();
	    session_start();	    
	    $html = "";
	    $recaudaciones = new RecaudacionesModel();
	    $response = array();
	    
	    /**parametros post **/
	    $id_entidad_patronal   = $_POST['id_entidad_patronal'];
	    $mes_recaudacion   = $_POST['mes'];
	    $anio_recaudacion  = $_POST['anio'];
	    
	    $mes_recaudacion   = str_pad($mes_recaudacion, 2,'0'); 
	    
	    $qryAportes    = "SELECT cc.id_ccomprobantes,
        	trunc( sum(dd.debe_dcomprobantes),2) \"valor\", 
            dd.descripcion_dcomprobantes,
        	ee.codigo_plan_cuentas, 
            ee.nombre_plan_cuentas
        FROM core_descuentos_registrados_cabeza aa
        INNER JOIN core_descuentos_registrados_detalle_aportes bb
        	ON bb.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza
        INNER JOIN  ccomprobantes cc
        	ON coalesce( split_part(coalesce(cc.referencia_doc_ccomprobantes,''),'|',2)::int8,0) = aa.id_descuentos_registrados_cabeza
        INNER JOIN dcomprobantes dd
        	ON dd.id_ccomprobantes = cc.id_ccomprobantes
        INNER JOIN plan_cuentas ee
        	ON ee.id_plan_cuentas = dd.id_plan_cuentas
        WHERE 1 = 1 
        	AND aa.procesado_descuentos_registrados_cabeza = true
        	AND aa.erro_descuentos_registrados_cabeza = false
        	AND cc.referencia_doc_ccomprobantes like '%|%'
        	AND cc.aprobado_ccomprobantes = true
        	AND dd.debe_dcomprobantes > 0
        	AND aa.year_descuentos_registrados_cabeza = $anio_recaudacion
        	AND aa.mes_descuentos_registrados_cabeza = 9 --$mes_recaudacion
        	AND to_char(cc.fecha_ccomprobantes,'YYYYMM') = '202012'--'$anio_recaudacion.$mes_recaudacion'
        	AND aa.id_entidad_patronal = $id_entidad_patronal
        GROUP BY cc.id_ccomprobantes, dd.descripcion_dcomprobantes, ee.codigo_plan_cuentas, ee.nombre_plan_cuentas
        ORDER BY cc.id_ccomprobantes";
	    
	    $rsAportes = $recaudaciones->enviaquery( $qryAportes );
	    
	    $qryCreditos    = "SELECT cc.id_ccomprobantes,
        	trunc(sum(dd.debe_dcomprobantes),2) \"valor\",
            dd.descripcion_dcomprobantes,
        	ee.codigo_plan_cuentas,
            ee.nombre_plan_cuentas
        FROM core_descuentos_registrados_cabeza aa
        INNER JOIN core_descuentos_registrados_detalle_creditos bb
        	ON bb.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza
        INNER JOIN  ccomprobantes cc
        	ON coalesce( split_part(coalesce(cc.referencia_doc_ccomprobantes,''),'|',2)::int8,0) = aa.id_descuentos_registrados_cabeza
        INNER JOIN dcomprobantes dd
        	ON dd.id_ccomprobantes = cc.id_ccomprobantes
        INNER JOIN plan_cuentas ee
        	ON ee.id_plan_cuentas = dd.id_plan_cuentas
        WHERE 1 = 1
        	AND aa.procesado_descuentos_registrados_cabeza = true
        	AND aa.erro_descuentos_registrados_cabeza = false
        	AND cc.referencia_doc_ccomprobantes like '%|%'
        	AND cc.aprobado_ccomprobantes = true
        	AND dd.debe_dcomprobantes > 0
        	AND aa.year_descuentos_registrados_cabeza =  $anio_recaudacion
        	AND aa.mes_descuentos_registrados_cabeza = 9 --$mes_recaudacion
        	AND to_char(cc.fecha_ccomprobantes,'YYYYMM') = '202012'--'$anio_recaudacion.$mes_recaudacion'
        	AND aa.id_entidad_patronal = $id_entidad_patronal
        GROUP BY cc.id_ccomprobantes, dd.descripcion_dcomprobantes, ee.codigo_plan_cuentas, ee.nombre_plan_cuentas
        ORDER BY cc.id_ccomprobantes";
	   	    
	    $rsCreditos = $recaudaciones->enviaquery( $qryCreditos );	       
	    
	    $html = '<div class="col-lg-12 col-md-12 col-sm-12"><div id="divtblHistorialMoras" class="">';
	    $html .= '<table id="tbl_contable" class="table table-hover table-bordered">';
	    $html .= '<thead>';
	    $html .= '<thead>';
	    $html .= '<tr style="">';
	    $html .= '<th class="info">#</th>';
	    $html .= '<th class="info">descripcion</th>';
	    $html .= '<th class="info">valor</th>';
	    $html .= '</tr>';
	    $html .= '<tbody style="">';
	    
	    if (sizeof($rsAportes) > 0) {
	        $contador = 1;
	        $html .= '<tr>';
	        $html .= '<td colspan="3">Resumen Aportes</td>';
	        $html .= '</tr>';
	        foreach ($rsAportes as $res) {
	            
	            $html .= '<tr>';
	            $html .= '<td>' . $contador . '</td>';
	            $html .= '<td>' . $res->codigo_plan_cuentas.' '.$res->nombre_plan_cuentas . '</td>';
	            $html .= '<td>' . $res->valor . '</td>';
	            $html .= '</tr>';
	            $contador ++;
	        }
	    } else {
	        $html .= '<tr>';
	        $html .= '<td colspan="3">-</td>';
	        $html .= '</tr>';
	    }
	    
	    if (sizeof($rsCreditos) > 0) {
	        $contador = 1;
	        $html .= '<tr>';
	        $html .= '<td colspan="3">Resumen Creditos</td>';
	        $html .= '</tr>';
	        foreach ($rsCreditos as $res) {
	            
	            $html .= '<tr>';
	            $html .= '<td>' . $contador . '</td>';
	            $html .= '<td>' . $res->codigo_plan_cuentas.' '.$res->nombre_plan_cuentas . '</td>';
	            $html .= '<td>' . $res->valor . '</td>';
	            $html .= '</tr>';
	            $contador ++;
	        }
	    } else {
	        $html .= '<tr>';
	        $html .= '<td colspan="3">-</td>';
	        $html .= '</tr>';
	    }
	    
	    $html .= '</tbody>';
	    $html .= '</table>';
	    $html .= '</div></div>';
	    
	    $salida = ob_get_clean();
	    if ( !empty($salida) ) {
	        echo "Error en la generacion de Aportes" . $salida;
	    } else {
	        $response['html'] = $html;
	        echo json_encode($response);
	    }
	}
	
	/**
	 * @author dc
	 * @desc 2020-12-22
	 * @name getResumenDescuentosContable
	 */
	public function getResumenDescuentosContable()
	{
	    ob_start();
	    session_start();
	    $recaudaciones = new RecaudacionesModel();
	    
	    $data  = array();
	    
	    /**parametros post **/
	    $id_entidad_patronal   = $_POST['id_entidad_patronal'];
	    $mes_recaudacion   = $_POST['mes'];
	    $anio_recaudacion  = $_POST['anio'];
	    
	    $mes_recaudacion   = str_pad($mes_recaudacion, 2,'0');
	    
	    $qryAportes    = "SELECT cc.id_ccomprobantes,
        	trunc( sum(dd.debe_dcomprobantes),2) \"valor\",
            dd.descripcion_dcomprobantes,
        	ee.codigo_plan_cuentas,
            ee.nombre_plan_cuentas
        FROM core_descuentos_registrados_cabeza aa
        INNER JOIN core_descuentos_registrados_detalle_aportes bb
        	ON bb.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza
        INNER JOIN  ccomprobantes cc
        	ON coalesce( SPLIT_PART(COALESCE(cc.referencia_doc_ccomprobantes,''),'|',2)::int8,0) = aa.id_descuentos_registrados_cabeza
        INNER JOIN dcomprobantes dd
        	ON dd.id_ccomprobantes = cc.id_ccomprobantes
        INNER JOIN plan_cuentas ee
        	ON ee.id_plan_cuentas = dd.id_plan_cuentas
        WHERE 1 = 1
        	AND aa.procesado_descuentos_registrados_cabeza = true
        	AND aa.erro_descuentos_registrados_cabeza = false
        	AND cc.referencia_doc_ccomprobantes like '%|%'
        	AND cc.aprobado_ccomprobantes = true
        	AND dd.debe_dcomprobantes > 0
        	AND aa.year_descuentos_registrados_cabeza = $anio_recaudacion
        	AND aa.mes_descuentos_registrados_cabeza = 9 --$mes_recaudacion
        	AND to_char(cc.fecha_ccomprobantes,'YYYYMM') = '202012'--'$anio_recaudacion.$mes_recaudacion'
        	AND aa.id_entidad_patronal = $id_entidad_patronal
        GROUP BY cc.id_ccomprobantes, dd.descripcion_dcomprobantes, ee.codigo_plan_cuentas, ee.nombre_plan_cuentas
        ORDER BY cc.id_ccomprobantes";
	    
	    $rsAportes = $recaudaciones->enviaquery( $qryAportes );    
	    
	    
	    $qryCreditos    = "SELECT
        aa.id_descuentos_registrados_cabeza,
	    cc.id_ccomprobantes,
	    SUM(trunc( dd.haber_dcomprobantes,2) )\"valor\",
	    dd.descripcion_dcomprobantes,
	    ee.codigo_plan_cuentas,
	    ee.nombre_plan_cuentas,
	    ff.id_tipo_creditos
	    FROM core_descuentos_registrados_cabeza aa
	    INNER JOIN core_descuentos_registrados_detalle_creditos bb
	    ON bb.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza
	    INNER JOIN  ccomprobantes cc
	    ON coalesce( split_part(coalesce(cc.referencia_doc_ccomprobantes,''),'|',2)::int8,0) = aa.id_descuentos_registrados_cabeza
	    INNER JOIN dcomprobantes dd
	    ON dd.id_ccomprobantes = cc.id_ccomprobantes
	    INNER JOIN plan_cuentas ee
	    ON ee.id_plan_cuentas = dd.id_plan_cuentas
	    inner join core_tabla_amortizacion_parametrizacion ff
	    on ff.id_plan_cuentas = dd.id_plan_cuentas
	    WHERE 1 = 1
	    AND aa.procesado_descuentos_registrados_cabeza = true
	    AND aa.erro_descuentos_registrados_cabeza = false
	    AND cc.referencia_doc_ccomprobantes like '%|%'
	        AND cc.aprobado_ccomprobantes = true
	        --AND dd.ha_dcomprobantes > 0
	        and ff.tipo_tabla_amortizacion_parametrizacion = 0
	        AND aa.year_descuentos_registrados_cabeza = $anio_recaudacion
	        AND aa.mes_descuentos_registrados_cabeza = 9 --$mes_recaudacion
	        AND to_char(cc.fecha_ccomprobantes,'YYYYMM') = '202012'--'$anio_recaudacion.$mes_recaudacion'
	            AND aa.id_entidad_patronal = $id_entidad_patronal
        GROUP BY aa.id_descuentos_registrados_cabeza,
            cc.id_ccomprobantes,
            dd.descripcion_dcomprobantes,
            ee.codigo_plan_cuentas,
            ee.nombre_plan_cuentas,
            ff.id_tipo_creditos
        ORDER BY cc.id_ccomprobantes";
	    
	    $rsCreditos = $recaudaciones->enviaquery( $qryCreditos );
	    
	    $data['aportes']   = sizeof($rsAportes) > 0 ? $rsAportes : array();
	    $data['creditos']  = sizeof($rsCreditos) > 0 ? $rsCreditos : array();
	    
	    $salida = ob_get_clean();
	    if ( !empty($salida) ) {
	        echo "Error en la Busqueda de Resumen Contable" . $salida;
	    } else {
	        echo json_encode( $data );
	    }
	}
	
	/**
	 * @author dc
	 * @desc 2020-12-22
	 * @name getResumenDescuentosContable
	 */
	public function cruzarResumenDescuentosContable()
	{
	    ob_start();
	    session_start();
	   
	    $data  = array();
	    
	    /**parametros post **/
	    #$id_entidad_patronal   = $_POST['id_entidad_patronal'];
	    #$mes_recaudacion   = $_POST['mes'];
	    #$anio_recaudacion  = $_POST['anio'];
	    
	    $data['value']=123456789;
	    
	    $salida = ob_get_clean();
	    if ( !empty($salida) ) {
	        echo "Error en la generacion de Aportes" . $salida;
	    } else {
	        echo json_encode( $data );
	    }
	}
	
	/***
	 * @author dc
	 * @desc 2020-12-28	 
	 */
	public function insertar_valores_ingreso_bancos(){
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    $recaudaciones = New RecaudacionesModel();
	    
	    $recaudaciones->beginTran();
	    
	    try{
	        
	        $resp  = array();
	        
	        /** llegada de paramatros de la vista **/
	        $id_entidad_patronal   = $_POST['id_entidad_patronal'];
	        $id_comprobantes   = $_POST['id_comprobantes'];
	        $id_bancos     = $_POST['id_bancos'];
	        $anio          = $_POST['anio'];
	        $mes           = $_POST['mes'];
	        $fecha_deposito   = $_POST['fecha_deposito'];
	        $fecha_contable   = $_POST['fecha_contable'];
	        $referencia    = $_POST['referencia'];
	        $valor         = $_POST['valor'];
	        $descripcion   = $_POST['descripcion'];
	        $diferencia    = $_POST['diferencia'];
	        
	        $usuario_usuarios  = $_SESSION['id_usuarios'];
	        
	        $detalle = json_decode($_POST["detalle"]);        
	        
	        #validar el id comprobantes para cuando sea ingreso o actualizacion
	        
	        //creacion de la variable parametros
	        $parametros = "";
	        $parametros .= $id_entidad_patronal.",";
	        $parametros .= $id_bancos.",";
	        $parametros .= $mes.",";
	        $parametros .= $anio.",";
	        $parametros .= "'".$fecha_deposito."',";
	        $parametros .= "'".$fecha_contable."',";
	        $parametros .= "'".$referencia."',";
	        $parametros .= "'".$valor."',";
	        $parametros .= "'".$diferencia."',";
	        $parametros .= "'".$descripcion."',";
	        $parametros .= "null,";	//id comprobantes
	        $parametros .= "null,";	//id comprobantes reverso
	        $parametros .= "'".date('Y-m-d')."',";
	        $parametros .= "'".$usuario_usuarios."',";
	        $parametros .= "1,"; //es_banco_ingreso
	        $parametros .= "1"; //estatus
	        
	        $funcion = "core_ins_ingreso_bancos_cabeza";	        
	        
	        // la cabecera se genera
	        $sqRecaudaciones    = $recaudaciones->getconsultaPG($funcion, $parametros);
	        $resultado  = $recaudaciones->llamarconsultaPG($sqRecaudaciones);
	        
	        $id_ingreso_bancos_cabeza = $resultado[0];
	        
	        if( !empty(error_get_last()) ){
	            throw new Exception( error_get_last()['message'] );
	        }
	        	        
	        $auxDetalle    = $this->ingreso_bancos_detalle($id_ingreso_bancos_cabeza, $detalle);
	        
	        if( $auxDetalle['error'] ){
	            throw new Exception( $auxDetalle['mensaje'] );
	        }
	       
	        
	        $resp['estatus']   = "OK";
	        $resp['icon']      = "success";
	        $resp['mensaje']   = "Datos generados";
	        $recaudaciones->endTran('COMMIT');
	        echo json_encode($resp);
	      	        
	    } catch (Exception $ex) {
	        $recaudaciones->endTran();
	        echo '<message> Error Ingreso bancos '.$ex->getMessage().' <message>';
	    }
	    	    
	}
	
	/**
	 * @author dc
	 * @desc 2020-12-28
	 * @param array $paramsCab
	 * @return boolean[]|string[]|boolean[]|mixed[]|boolean[]|string[]|mixed[]
	 */
	public function ingreso_bancos_detalle( int $id_cabecera, array $detalle_pagos ){
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    $recaudaciones = new RecaudacionesModel();
	    
	    $response  = array();	    
	    $detalle   = array();	    
	    
	    if( empty( $detalle_pagos )  ){ return array( 'error'=>true, 'mensaje'=>"Detalle pago se encuentra vacia"); }
	    
	    $funcionDetalle    = "core_ins_ingreso_bancos_detalle";
	    foreach($detalle_pagos as $res){
	        
	        $detalle['id_cabeza']      = $id_cabecera;
	        $detalle['identificador']  = $res->identificador;
	        $detalle['identificador_descripcion']  = $res->nombre_identificador;
	        $detalle['descripcion']    = $res->razon;
	        $detalle['valor']          = $res->valor;
	        $detalle['id_estatus']     = 1;
	        
	        $parametrosDetalle  = "'".join("','", $detalle)."'";
	        $sqDetalle  = $recaudaciones->getconsultaPG($funcionDetalle, $parametrosDetalle);
	        
	        $recaudaciones->llamarconsultaPG($sqDetalle);
	        
	        echo "ingresa aqui";
	        
	        if( !empty( pg_last_error() ) ){
	            break;
	        }
	    }
	    
	    $error = error_get_last();
	    if( !empty( $error ) ){
	        $response['error']     = true;
	        $response['mensaje']   = $error['message'];
	        return $response;
	    }
	    
	    $response['error']     = false;
	    $response['mensaje']   = "";
	    
	    return $response;
	    
	}
	
}

	
?>