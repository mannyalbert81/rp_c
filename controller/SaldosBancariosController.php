<?php
class SaldosBancariosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}
	
	public function index_mensuales(){
	    
	    session_start();
	    
	    if (isset(  $_SESSION['usuario_usuarios']) )
	    {
	        
	        
	        $temp_comprobantes=new ComprobantesTemporalModel();
	        
	        $tipo_comprobante=new TipoComprobantesModel();
	        //$resultTipCom = $tipo_comprobante->getAll("nombre_tipo_comprobantes");
	        
	        $forma_pago=new FormaPagoModel();
	        $resultFormaPago = $forma_pago->getAll("nombre_forma_pago");
	        
	        /** buscar tipo comprobantes **/
	        $TipoComprobantes = new TipoComprobantesModel();
	        $columnas1 = " id_tipo_comprobantes,nombre_tipo_comprobantes";
	        $tablas1 = " tipo_comprobantes";
	        $where1 = " 1 = 1  AND  nombre_tipo_comprobantes = 'CONTABLE' ";
	        $id1 = "id_tipo_comprobantes";
	        $rsConsulta1 = $TipoComprobantes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        
	        $nombre_controladores = "inv_ingreso";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $temp_comprobantes->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        
	        
	        if (!empty($resultPer))
	        {
	            
	            $this->view_inversiones("SaldosMensuales",array(
	                "resultTipCom"=>"" , "resultFormaPago"=>"", "resultTipoComprobantes"=>""
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
	
	/*** dc 2021-02-18	 **/
	public function cargaTipoInstrumento(){
	    
	    $entidad_patronal = null;
	    $entidad_patronal = new EntidadPatronalParticipesModel();
	    
	    $query = "SELECT id_tipos_instrumentos, nombre_tipos_instrumentos FROM inver_tipos_instrumentos WHERE 1=1 ORDER BY nombre_tipos_instrumentos ";
	    
	    $resulset = $entidad_patronal->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	
	public function cargaCalificaciones(){
	    
	    $entidad_patronal = null;
	    $entidad_patronal = new EntidadPatronalParticipesModel();
	    
	    $query = "SELECT id_calificaciones, descripcion_calificaciones as nombre_calificaciones FROM inver_calificaciones WHERE 1=1 ORDER BY nombre_calificaciones ";
	    
	    $resulset = $entidad_patronal->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	
	public function cargaCalificacionesRiesgos(){
	    
	    $entidad_patronal = null;
	    $entidad_patronal = new EntidadPatronalParticipesModel();
	    
	    $query = "SELECT id_calificaciones_riesgos, descripcion_calificaciones_riesgos as nombre_calificaciones_riesgos FROM inver_calificaciones_riesgos WHERE 1=1 ORDER BY nombre_calificaciones_riesgos ";
	    
	    $resulset = $entidad_patronal->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	
	public function cargaDenominacionesMonedas(){
	    
	    $entidad_patronal = null;
	    $entidad_patronal = new EntidadPatronalParticipesModel();
	    
	    $query = "SELECT id_denominaciones_monedas, descripcion_denominaciones_monedas as nombre_denominaciones_monedas FROM inver_denominaciones_monedas WHERE 1=1 ORDER BY nombre_denominaciones_monedas ";
	    
	    $resulset = $entidad_patronal->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	
	/*** end dc 2021-02-18	 **/
	
	/*** dc 2021-02-18	 **/
	public function autompleteEmisores(){
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    //print_r($_REQUEST);
	    
	    if(isset($_GET['term'])){
	        
	        $codigo_emisores = $_GET['term'];
	        
	        $columnas = " id_emisores, ruc_emisores, nombre_emisores";
	        $tablas = "public.inver_emisores";
	        $where = " ruc_emisores LIKE '$codigo_emisores%' ";
	        $id = "nombre_emisores ";
	        $limit = "LIMIT 10";
	      
	        $rsEmisores = $planCuentas->getCondicionesPag($columnas,$tablas,$where,$id,$limit);
	        
	        $respuesta = array();
	        
	        if(!empty($rsEmisores) ){
	            
	            foreach ($rsEmisores as $res){
	                
	                $_emisor = new stdClass;
	                $_emisor->id = $res->id_emisores;
	                $_emisor->value = $res->ruc_emisores;
	                $_emisor->label = $res->ruc_emisores.' | '.$res->nombre_emisores;
	                $_emisor->nombre = $res->nombre_emisores;
	                
	                $respuesta[] = $_emisor;
	            }
	            
	            echo json_encode($respuesta);
	            
	        }else{
	            
	            echo '[{"id":"","value":"Emisor No Encontrado"}]';
	        }
	        
	    }
	}
	/*** end dc 2021-02-18	 **/
	
	/** dc 2021/02/12 **/
	public function dtMostrarSaldosBancariosMensuales()
	{
	    if( !isset( $_SESSION ) ){
	        session_start();
	    }
	    
	    try {
	        ob_start();
	        
	        $saldos_bancarios_mensuales = new SaldosBancariosMensualesModel();
	        
	        //dato que viene de parte del plugin DataTable
	        $requestData = $_REQUEST;
	        $searchDataTable   = $requestData['search']['value'];
	        
	        
	        $columnas1 = " id_saldos_bancarios_mensuales,
                	        isbm.tipo_identificacion_saldos_bancarios_mensuales,
                	        isbm.id_emisores,
                	        ie.ruc_emisores,
                	        ie.nombre_emisores,
                	        isbm.tipo_cuenta_saldos_bancarios_mensuales,
                	        isbm.numero_cuenta_saldos_bancarios_mensuales,
                	        isbm.cuenta_contable_saldos_bancarios_mensuales,
                	        isbm.id_denominaciones_monedas,
                	        idm.codigo_denominaciones_monedas ,
                	        idm.descripcion_denominaciones_monedas  as nombre_denominaciones_monedas,
                	        isbm.valor_moneda_saldos_bancarios_mensuales,
                	        isbm.valor_libros_saldos_bancarios_mensuales,
                	        isbm.id_calificaciones,
                	        ic.codigo_calificaciones ,
                	        ic.descripcion_calificaciones as nombre_calificaciones,
                	        isbm.id_calificaciones_riesgos,
                	        icr.codigo_calificaciones_riesgos,
                	        icr.descripcion_calificaciones_riesgos as nombre_calificaciones_riesgos,
                	        isbm.fecha_ult_calificacion_saldos_bancarios_mensuales,
                	        isbm.tasa_interes_saldos_bancarios_mensuales,
                	        isbm.fecha_corte_saldos_bancarios_mensuales";
	        $tablas1   = " public.inver_saldos_bancarios_mensuales isbm,
                	        public.inver_emisores ie ,
                	        public.inver_denominaciones_monedas idm ,
                	        public.inver_calificaciones ic ,
                	        public.inver_calificaciones_riesgos icr";
	        $where1    = " isbm.id_emisores = ie.id_emisores and
                	        isbm.id_denominaciones_monedas = idm.id_denominaciones_monedas and
                	        isbm.id_calificaciones  = ic.id_calificaciones and
                	        isbm.id_calificaciones_riesgos = icr.id_calificaciones_riesgos ";
                	       
	        /* PARA FILTROS DE CONSULTA */
	        
	        if( strlen( $searchDataTable ) > 0 )
	        {
	            $where1 .= " AND ( ";
	            $where1 .= " ie.ruc_emisores like '%$searchDataTable%' ";
	            $where1 .= " OR isbm.numero_cuenta_saldos_bancarios_mensuales LIKE '%$searchDataTable%' ";
	            $where1 .= " OR fecha_corte_saldos_bancarios_mensuales = '$searchDataTable' ";
	            $where1 .= " ) ";
	            
	        }
	        
	        /** PARA DATOS EXTERNOS **/
	        #$id_entidades  = $_POST['id_entidades'];
	        #if($id_entidades!=0){ $where1 .= " AND bb.id_entidades='$id_entidades'";}
	        
	        
	        $rsCantidad    = $saldos_bancarios_mensuales->getCantidad("*", $tablas1, $where1);
	        $cantidadBusqueda = (int)$rsCantidad[0]->total;
	        
	        /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
	        
	        // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	        // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	        $columns = array(
	            0 => '1',
	            1 => '1',
	            2 => '1',
	            3 => '1',
	            4 => '1',
	            5 => '5',
	            6 => '1',
	            7 => '1',
	            8 => '1',
	            9 => '1'
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
	        
	        $resultSet=$saldos_bancarios_mensuales->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
	        
	        /** crear el array data que contiene columnas en plugins **/
	        $data = array();
	        $dataFila = array();
	        $columnIndex = 0;
	        foreach ( $resultSet as $res){
	            $columnIndex++;
	            
	            $opciones = ""; //variable donde guardare los datos creados automaticamente
	            	            
	            if( 1 == 1 ){
	                $opciones = '<div class="pull-right ">
                        <span >
                            <a onclick="detalle(this)" id="" data-id_comprobantes="'.$res->id_emisores.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Anular"> <i class="fa  fa-ban fa-2x fa-fw" aria-hidden="true" ></i>
                           </a>
                        </span>
                        </div>';
	            }
	            
	          
	            $dataFila['numfila'] = $columnIndex;
	            $dataFila['tipo_identificacion_saldos_bancarios_mensuales']  = $res->tipo_identificacion_saldos_bancarios_mensuales;
	            $dataFila['ruc_emisores'] =  $res->ruc_emisores;
	            $dataFila['nombre_emisores'] = $res->nombre_emisores;
	            $dataFila['tipo_cuenta_saldos_bancarios_mensuales']  = $res->tipo_cuenta_saldos_bancarios_mensuales;
	            $dataFila['numero_cuenta_saldos_bancarios_mensuales']  = $res->numero_cuenta_saldos_bancarios_mensuales;
	            $dataFila['cuenta_contable_saldos_bancarios_mensuales']  = $res->cuenta_contable_saldos_bancarios_mensuales;
	            $dataFila['nombre_denominaciones_monedas'] = $res->nombre_denominaciones_monedas;
	            $dataFila['valor_moneda_saldos_bancarios_mensuales'] = $res->valor_moneda_saldos_bancarios_mensuales;
	            $dataFila['valor_libros_saldos_bancarios_mensuales']  = $res->valor_libros_saldos_bancarios_mensuales;
	            $dataFila['nombre_calificaciones'] =  $res->nombre_calificaciones;
	            $dataFila['nombre_calificaciones_riesgos'] = $res->nombre_calificaciones_riesgos;
	            $dataFila['fecha_ult_calificacion_saldos_bancarios_mensuales']  = $res->fecha_ult_calificacion_saldos_bancarios_mensuales;
	            $dataFila['tasa_interes_saldos_bancarios_mensuales']  = $res->tasa_interes_saldos_bancarios_mensuales;
	            $dataFila['fecha_corte_saldos_bancarios_mensuales']  = $res->fecha_corte_saldos_bancarios_mensuales;
	                    
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
	                "sql" => $sql
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
	/** end dc 2021/02/12 **/
	
	
	/** end dc 2021/02/19 **/
	public function IngresaSaldosBancariosMensuales(){
	    
	    session_start();
	    $inversiones   = new InversionesModel();
	    $mensaje   = "";
	    $resp  = array();
	    
	    $inversiones->beginTran();
	    
	    try {
	        
	        #valores de la vista
	        
	        
	        
	        $tipo_identificacion_saldos_bancarios_mensuales   = $_POST['tipo_identificacion_saldos_bancarios_mensuales'];
	        $id_emisor             = $_POST['id_emisor'];
	        $tipo_cuenta_saldos_bancarios_mensuales    = $_POST['tipo_cuenta_saldos_bancarios_mensuales'];
	        $numero_cuenta_saldos_bancarios_mensuales   = $_POST['numero_cuenta_saldos_bancarios_mensuales'];
	        $cuenta_contable_saldos_bancarios_mensuales        = $_POST['cuenta_contable_saldos_bancarios_mensuales'];
	        $id_denominaciones_monedas     = $_POST['id_denominaciones_monedas'];
	        $valor_moneda_saldos_bancarios_mensuales      = $_POST['valor_moneda_saldos_bancarios_mensuales'];
	        $valor_libros_saldos_bancarios_mensuales = $_POST['valor_libros_saldos_bancarios_mensuales'];
	        $id_calificaciones      = $_POST['id_calificaciones'];
	        $id_calificaciones_riesgos     = $_POST['id_calificaciones_riesgos'];
	        $fecha_ult_calificacion_saldos_bancarios_mensuales     = $_POST['fecha_ult_calificacion_saldos_bancarios_mensuales'];
	        $tasa_interes_saldos_bancarios_mensuales   = $_POST['tasa_interes_saldos_bancarios_mensuales'];
	        $fecha_corte_saldos_bancarios_mensuales     = $_POST['fecha_corte_saldos_bancarios_mensuales'];
	       
	        if( !empty(error_get_last()) ){ throw new Exception('Variables no Recibidas'); $mensaje = error_get_last()['message']; }
	        
	        $col1  = " 1 existe";
	        $tab1  = " inver_saldos_bancarios_mensuales";
	        $whe1  = " id_emisores = '$id_emisor' AND numero_cuenta_saldos_bancarios_mensuales = '$numero_cuenta_saldos_bancarios_mensuales'
                        and  fecha_corte_saldos_bancarios_mensuales = '$fecha_corte_saldos_bancarios_mensuales' ";
	        $id1   = " id_emisores";
	        $rsEmisor  = $inversiones->getCondiciones($col1, $tab1, $whe1, $id1);
	        
	        if( !empty($rsEmisor) ){ throw new Exception('validacion'); $mensaje = "Emisor con numero de cuenta y fecha de corte se encuentran registrados"; }
	        
	        $parametros = "";
	        $parametros .= "'".$tipo_identificacion_saldos_bancarios_mensuales."',";
	        $parametros .= $id_emisor.",";
	        $parametros .= "'".$tipo_cuenta_saldos_bancarios_mensuales."',";
	        $parametros .= "'".$numero_cuenta_saldos_bancarios_mensuales."'".",";
	        $parametros .= "'".$cuenta_contable_saldos_bancarios_mensuales."',";
	        $parametros .= "'".$id_denominaciones_monedas."',";
	        $parametros .= $valor_moneda_saldos_bancarios_mensuales.",";
	        $parametros .= $valor_libros_saldos_bancarios_mensuales.",";
	        $parametros .= $id_calificaciones.",";
	        $parametros .= $id_calificaciones_riesgos.",";
	        $parametros .= "'".$fecha_ult_calificacion_saldos_bancarios_mensuales."'".",";
	        $parametros .= $tasa_interes_saldos_bancarios_mensuales.",";
	        $parametros .= "'".$fecha_corte_saldos_bancarios_mensuales."'";
	  
	        /*
	        echo json_encode($parametros);
	        die();
	        */
	        
	        $funcion = "inver_ins_saldos_bancarios_mensuales";
	        
	        #sql de insert
	        $sqInversiones    = $inversiones->getconsultaPG($funcion, $parametros);
	        $resultado  = $inversiones->llamarconsultaPG($sqInversiones);
	        
	        if( !empty(error_get_last()) ){
	            throw new Exception( 'FALLO INSERTAR REGISTO' );
	            $mensaje   = error_get_last()['message'];
	        }
	        
	        $id_numero_cuenta_saldos_bancarios_mensualesso_inversiones = $numero_cuenta_saldos_bancarios_mensuales;
	        
	        $mensaje = "Datos Ingresados";
	        
	        $resp['estatus']   = "OK";
	        $resp['icon']      = "success";
	        $resp['mensaje']   = $mensaje;
	        $resp['identificador']   = $id_numero_cuenta_saldos_bancarios_mensualesso_inversiones;
	        $inversiones->endTran('COMMIT');
	        echo json_encode($resp);
	        
	        
	    } catch (Exception $e) {
	        $inversiones->endTran();
	        echo '<message> Error Ingreso Saldos Bancarios Mensuales '.$e->getMessage().' <message>';
	    }
	    
	    	    
	}
	/** end dc 2021/02/19 **/

	
	

	
}
?>