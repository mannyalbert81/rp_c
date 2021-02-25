<?php
class SaldosInversionesController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}
	
	public function index(){
	    
	    session_start();
	    
	    if (isset(  $_SESSION['usuario_usuarios']) )
	    {
	        $inversiones   = new InversionesModel();
	        
	        $nombre_controladores = "inversiones_saldos";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $inversiones->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        	       
	        if (!empty($resultPer))
	        {
	            
	            $this->view_inversiones("SaldosInversiones",array());
	            
	            
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
	    
	    $inversiones = null;
	    $inversiones = new InversionesModel();
	    
	    $query = "SELECT id_tipos_instrumentos, nombre_tipos_instrumentos FROM inver_tipos_instrumentos WHERE 1=1 ORDER BY nombre_tipos_instrumentos ";
	    
	    $resulset = $inversiones->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	/*** end dc 2021-02-18	 **/ 
	
	/*** dc 2021-02-23	 **/
	public function cargaCalificacionEmisor(){
	    
	    $inversiones = null;
	    $inversiones = new InversionesModel();
	    
	    $query = "SELECT id_calificaciones, codigo_calificaciones, descripcion_calificaciones FROM inver_calificaciones WHERE 1=1 ORDER BY id_calificaciones ";
	    
	    $resulset = $inversiones->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	/*** end dc 2021-02-23	 **/ 
	
	/*** dc 2021-02-23	 **/
	public function cargaCalificacionRiesgos(){
	    
	    $inversiones = null;
	    $inversiones = new InversionesModel();
	    
	    $query = "SELECT id_calificaciones_riesgos, descripcion_calificaciones_riesgos FROM public.inver_calificaciones_riesgos WHERE 1=1 ORDER BY id_calificaciones_riesgos ";
	    
	    $resulset = $inversiones->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	/*** end dc 2021-02-23	 **/ 
	
	/*** dc 2021-02-23	 **/
	public function verDetallesNumeroInstrumento(){
	    
	    $inversiones = null;
	    $inversiones = new InversionesModel();
	    
	    $id_ingresos_inversiones   = isset($_POST['id_ingresos_inversiones']) ? $_POST['id_ingresos_inversiones'] : 0 ;
	    
	    if ( $id_ingresos_inversiones == 0 ){ echo "<error> Parametros Recibidos <error>"; return; }
	    
	    $col1  = " aa.id_ingreso_inversiones, bb.id_emisores, bb.ruc_emisores, bb.nombre_emisores, 
                aa.numero_instrumento_ingreso_inversiones, aa.tipo_identificacion_ingreso_inversiones, 
                aa.tipo_renta_ingreso_inversiones, aa.fecha_compra_ingreso_inversiones, aa.valor_compra_ingreso_inversiones";
	    $tab1  = " public.inver_ingreso_inversiones aa INNER JOIN public.inver_emisores bb ON bb.id_emisores = aa.id_emisores";
	    $whe1  = " aa.id_ingreso_inversiones = $id_ingresos_inversiones ";
	    $id1   = " bb.nombre_emisores";
	    
	    $rsInversiones = $inversiones->getCondiciones($col1, $tab1, $whe1, $id1);   
	    
	    if(!empty($rsInversiones) && count($rsInversiones)>0){
	        
	        echo json_encode(array('data'=>$rsInversiones));
	        
	    }
	}
	/*** end dc 2021-02-23	 **/
	
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
	public function dtMostrarInversiones()
	{
	    if( !isset( $_SESSION ) ){
	        session_start();
	    }
	    
	    try {
	        ob_start();
	        
	        $inversiones = new InversionesModel();
	        
	        //dato que viene de parte del plugin DataTable
	        $requestData = $_REQUEST;
	        $searchDataTable   = $requestData['search']['value'];
	        
	        $columnas1 = " aa.id_ingreso_inversiones, aa.tipo_identificacion_ingreso_inversiones, aa.id_emisores, bb.ruc_emisores, bb.nombre_emisores,
    	        aa.numero_instrumento_ingreso_inversiones, cc.id_tipos_instrumentos, cc.codigo_tipos_instrumentos,aa.tipo_renta_ingreso_inversiones,
    	        aa.fecha_emision_ingreso_inversiones, aa.fecha_compra_ingreso_inversiones, aa.fecha_vencimiento_ingreso_inversiones,
    	        aa.tasa_nominal_ingreso_inversiones, aa.plazo_pactado_ingreso_inversiones, aa.valor_nominal_ingreso_inversiones,
    	        aa.numero_acciones_ingreso_inversiones, aa.precio_compra_accion_ingreso_inversiones, aa.valor_compra_ingreso_inversiones,
    	        aa.periodo_pago_ingreso_inversiones, aa.amortization_capital_ingreso_inversiones, aa.base_tasa_capital_ingreso_inversiones,
    	        aa.base_tasa_interes_ingreso_inversiones, aa.periodo_gracia_ingreso_inversiones, aa.estado_registro_ingreso_inversiones,
                aa.amortization_interes_ingreso_inversiones";
	        $tablas1   = " inver_ingreso_inversiones aa
    	        INNER JOIN inver_emisores bb
    	           ON bb.id_emisores = aa.id_emisores
    	        INNER JOIN inver_tipos_instrumentos cc
    	           ON cc.id_tipos_instrumentos = aa.id_tipos_instrumentos";
	        $where1    = " 1 = 1 ";
	       
	        /* PARA FILTROS DE CONSULTA */
	        
	        if( strlen( $searchDataTable ) > 0 )
	        {
	            $where1 .= " AND ( ";
	            $where1 .= " bb.ruc_emisores like '%$searchDataTable%' ";
	            $where1 .= " OR cc.codigo_tipos_instrumentos ILIKE '%$searchDataTable%' ";
	            $where1 .= " OR aa.numero_instrumento_ingreso_inversiones ILIKE '%$searchDataTable%' ";
	            $where1 .= " ) ";
	            
	        }
	        
	        /** PARA DATOS EXTERNOS **/
	        #$id_entidades  = $_POST['id_entidades'];
	        #if($id_entidades!=0){ $where1 .= " AND bb.id_entidades='$id_entidades'";}
	        
	        
	        $rsCantidad    = $inversiones->getCantidad("*", $tablas1, $where1);
	        $cantidadBusqueda = (int)$rsCantidad[0]->total;
	        
	        /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
	        
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
	        
	        $resultSet=$inversiones->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
	        
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
	            $dataFila['tipo_identificacion']  = $res->tipo_identificacion_ingreso_inversiones;
	            $dataFila['identificacion_emisor'] =  $res->ruc_emisores;
	            $dataFila['nombre_emisor'] = $res->nombre_emisores;
	            $dataFila['numero_instrumento']  = $res->numero_instrumento_ingreso_inversiones;
	            $dataFila['tipo_instrumento']  = $res->codigo_tipos_instrumentos;
	            $dataFila['tipo_renta']  = $res->tipo_renta_ingreso_inversiones;
	            $dataFila['fecha_emision']= $res->fecha_emision_ingreso_inversiones;
	            $dataFila['fecha_compra'] = $res->fecha_compra_ingreso_inversiones;
	            $dataFila['fecha_vencimiento'] = $res->fecha_vencimiento_ingreso_inversiones;
	            $dataFila['tasa_nominal']  = $res->tasa_nominal_ingreso_inversiones;
	            $dataFila['plazo_pactado'] =  $res->plazo_pactado_ingreso_inversiones;
	            $dataFila['valor_nominal'] = $res->valor_nominal_ingreso_inversiones;
	            $dataFila['numero_acciones']  = $res->numero_acciones_ingreso_inversiones;
	            $dataFila['precio_compra']  = $res->precio_compra_accion_ingreso_inversiones;
	            $dataFila['valor_compra']  = $res->valor_compra_ingreso_inversiones;
	            $dataFila['periodo_pago']= $res->periodo_pago_ingreso_inversiones;
	            $dataFila['amortizacion_capital'] = $res->amortization_capital_ingreso_inversiones;
	            $dataFila['amortizacion_interes'] = $res->amortization_interes_ingreso_inversiones;
	            $dataFila['base_tasa_capital']  = $res->base_tasa_capital_ingreso_inversiones;
	            $dataFila['base_tasa_interes'] =  $res->base_tasa_interes_ingreso_inversiones;
	            $dataFila['periodo_gracia'] = $res->periodo_gracia_ingreso_inversiones;
	            $dataFila['estado_registro']  = $res->estado_registro_ingreso_inversiones;	           
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
	
	
	/** end dc 2021/02/25 **/
	public function IngresaSaldosInversiones(){
	    
	    session_start();
	    $inversiones   = new InversionesModel();
	    $mensaje   = "";
	    $resp  = array();
	    
	    $inversiones->beginTran();
	    
	    try {
	        
	        #valores de la vista
	        //$numero_instrumento    = $_POST['numero_instrumento'];
	        $id_ingreso_inversiones= $_POST['id_ingreso_inversiones'];
	        $estado_inversion      = $_POST['estado_inversion'];
	        $rango_vencimiento     = $_POST['rango_vencimiento'];
	        $valor_contable        = $_POST['valor_contable'];
	        $tasa_nominal      = $_POST['tasa_nominal'];
	        $tasa_cupon        = $_POST['tasa_cupon'];
	        $fecha_ultimo_cupon= $_POST['fecha_ult_cupon'];
	        $precio_compra_porcentaje  = $_POST['precio_compra_porcentaje'];
	        $valor_efectivo     = $_POST['valor_efectivo'];
	        $rendimiento_porcentaje    = $_POST['rendimiento_porcentaje'];
	        $precio_anio_renta = $_POST['precio_anio_renta_fija'];
	        $interes_acumulado_cobrar  = $_POST['interes_acumulado_cobrar'];
	        $monto_generados_interes   = $_POST['monto_generados_interes'];
	        $valor_mercado     = $_POST['valor_mercado'];
	        $numero_acciones_corte  = $_POST['numero_acciones_corte'];
	        $precio_mercado_actual  = $_POST['precio_mercado_actual'];
	        $precio_mercado_hace_anio  = $_POST['precio_mercado_hace_anio'];
	        $dividendo_accion  = $_POST['dividendo_accion']; 
	        $codigo_vecto_precio   = $_POST['codigo_vecto_precio'];
	        $id_calificacion_emisor    = $_POST['calificacion_emisor'];
	        $id_calificacion_riesgos   = $_POST['calificacion_riesgos'];	        
	        $fecha_ultima_calificacion = $_POST['fecha_ultima_calificacion'];
	        $provision_constituida     = $_POST['provision_constituida'];
	        $estado_vencimiento    = $_POST['estado_vencimiento'];
	        $valor_nominal_vencido = $_POST['valor_nominal_vencido'];
	        $interes_acumulado_cobrar_vencido     = $_POST['interes_acumulado_cobrar_vencido'];
	        $numero_cuotas_vencidas= $_POST['numero_cuotas_vencidas'];
	        $cuenta_contable_cap_vencido = $_POST['cuenta_contable_cap_vencido'];
	        $valor_dolares     = $_POST['valor_dolares'];
	        $cuenta_contable_ren_vencido = $_POST['cuenta_contable_ren_vencido'];
	        $valor_dolares_dos = $_POST['valor_dolares_dos'];
	        $cuenta_contable_provision_acumulada_capital       = $_POST['cuenta_contable_provision_acumulada_capital'];
	        $valor_dolares_tres= $_POST['valor_dolares_tres'];
	        $cuenta_contable_provision_acumulada_rendimiento   = $_POST['cuenta_contable_provision_acumulada_rendimiento'];
	        $valor_dolares_cuatro   = $_POST['valor_dolares_cuatro'];
	        $valor_liquidado   = $_POST['valor_liquidado'];
	        $fecha_liquidacion = $_POST['fecha_liquidacion'];
	        $precio_liquidacion= $_POST['precio_liquidacion'];
	        $valor_liquidacion = $_POST['valor_liquidacion'];
	        $motivo_liquidacion= $_POST['motivo_liquidacion'];
	        	        
	        if( !empty(error_get_last()) ){ throw new Exception('Variables no Recibidas'); $mensaje = error_get_last()['message']; }
	        	       
	        $parametros = "";
	        $parametros .= "'".$id_ingreso_inversiones."',";
	        $parametros .= "'".$estado_inversion."',";
	        $parametros .= "'".$rango_vencimiento."',";
	        $parametros .= "'".$valor_contable."',";
	        $parametros .= "'".$tasa_nominal."',";
	        $parametros .= "'".$tasa_cupon."',";
	        $parametros .= "'".$fecha_ultimo_cupon."',";
	        $parametros .= "'".$precio_compra_porcentaje."',";
	        $parametros .= "'".$valor_efectivo."',";
	        $parametros .= "'".$rendimiento_porcentaje."',";
	        $parametros .= $precio_anio_renta.",";
	        $parametros .= $interes_acumulado_cobrar.",";
	        $parametros .= $monto_generados_interes.",";
	        $parametros .= $valor_mercado.",";
	        $parametros .= $numero_acciones_corte.",";
	        $parametros .= $precio_mercado_actual.",";
	        $parametros .= $precio_mercado_hace_anio.",";
	        $parametros .= $dividendo_accion.",";
	        $parametros .= "'".$codigo_vecto_precio."',";
	        $parametros .= $id_calificacion_emisor.",";
	        $parametros .= $id_calificacion_riesgos.",";
	        $parametros .= "'".$fecha_ultima_calificacion."',";
	        $parametros .= "'".$provision_constituida."',";
	        $parametros .= "'".$estado_vencimiento."',";
	        $parametros .= "'".$valor_nominal_vencido."',";
	        $parametros .= "'".$interes_acumulado_cobrar_vencido."',";
	        $parametros .= "'".$numero_cuotas_vencidas."',";
	        $parametros .= "'".$cuenta_contable_cap_vencido."',";
	        $parametros .= "'".$valor_dolares."',";
	        $parametros .= "'".$cuenta_contable_ren_vencido."',";
	        $parametros .= "'".$valor_dolares_dos."',";
	        $parametros .= "'".$cuenta_contable_provision_acumulada_capital."',";
	        $parametros .= "'".$valor_dolares_tres."',";
	        $parametros .= "'".$cuenta_contable_provision_acumulada_rendimiento."',";
	        $parametros .= "'".$valor_dolares_cuatro."',";
	        $parametros .= "'".$valor_liquidado."',";
	        $parametros .= "'".$fecha_liquidacion."',";
	        $parametros .= "'".$precio_liquidacion."',";
	        $parametros .= "'".$valor_liquidacion."',";
	        $parametros .= "'".$motivo_liquidacion."'";
	        	        
	        
	        $funcion = "inver_ins_ingreso_saldos_inversiones";
	        	        
	        #sql de insert
	        $sqInversiones    = $inversiones->getconsultaPG($funcion, $parametros);
	        
	        //echo $sqInversiones; die();
	        
	        $resultado  = $inversiones->llamarconsultaPG($sqInversiones);
	        
	        if( !empty(error_get_last()) ){
	            throw new Exception( 'FALLO INSERTAR REGISTO' );
	            $mensaje   = error_get_last()['message'];
	        }
	        
	        $id_ingreso_inversiones = $resultado[0];
	        
	        $mensaje = "Datos Ingresados";
	        
	        $resp['estatus']   = "OK";
	        $resp['icon']      = "success";
	        $resp['mensaje']   = $mensaje;
	        $resp['identificador']   = $id_ingreso_inversiones;
	        $inversiones->endTran('COMMIT');
	        echo json_encode($resp);
	        
	        
	    } catch (Exception $e) {
	        $inversiones->endTran();
	        echo '<message> Error Saldos Inversiones '.$e->getMessage().' <message>';
	    }
	    
	    	    
	}
	/** end dc 2021/02/25 **/

	/*** dc 2021-02-23	 **/
	public function autompleteNumeroInstrumento(){
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    //print_r($_REQUEST);
	    
	    if(isset($_GET['term'])){
	        
	        $numero_instrumento    = $_GET['term'];
	        
	        $columnas = " aa.id_ingreso_inversiones, bb.id_emisores, bb.ruc_emisores, bb.nombre_emisores, aa.numero_instrumento_ingreso_inversiones";
	        $tablas = " public.inver_ingreso_inversiones aa INNER JOIN public.inver_emisores bb ON bb.id_emisores = aa.id_emisores ";
	        $where = " numero_instrumento_ingreso_inversiones LIKE '$numero_instrumento%' ";
	        $id = " aa.numero_instrumento_ingreso_inversiones ";
	        $limit = " LIMIT 10";
	       
	        $rsEmisores = $planCuentas->getCondicionesPag($columnas,$tablas,$where,$id,$limit);
	        
	        $respuesta = array();
	        
	        if(!empty($rsEmisores) ){
	            
	            foreach ($rsEmisores as $res){
	                
	                $_emisor = new stdClass;
	                $_emisor->id = $res->id_ingreso_inversiones;
	                $_emisor->value = $res->numero_instrumento_ingreso_inversiones;
	                $_emisor->label = $res->ruc_emisores.' | '.$res->numero_instrumento_ingreso_inversiones;
	                $_emisor->nombre = $res->nombre_emisores;
	                
	                $respuesta[] = $_emisor;
	            }
	            
	            echo json_encode($respuesta);
	            
	        }else{
	            
	            echo '[{"id":"","value":"Inversion No Encontrado"}]';
	        }
	        
	    }
	}
	/*** end dc 2021-02-23	 **/
	
}
?>