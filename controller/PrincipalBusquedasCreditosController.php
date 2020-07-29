<?php

class PrincipalBusquedasCreditosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
	
	    session_start();
	    
	    $busquedas = new PrincipalBusquedasModel();
	    
	    if( empty( $_SESSION['usuario_usuarios'] ) ){
	        $this->redirect("Usuarios","sesion_caducada");
	        exit();
	    }
	    
	    $nombre_controladores = "PrincipalBusquedaCreditos";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $busquedas->getPermisosVer(" controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Ingreso a Busqueda Creditos"	            
	        ));
	        exit();
	    }
	    
	    	    
	    $this->view_principal("PrincipalDatosCreditos",array());
	
	}
	
	public function CargaDatosParticipe(){
	    
	    $busquedas = new PrincipalBusquedasModel();
	    $resp  = null;
	    
	    try {
	        
	        $id_participes = $_POST['id_participes'];
	        
	        if( !empty( error_get_last() ) ){ throw new Exception("Variable no recibida"); }
	        
	        $col1  = " aa.id_participes ,aa.cedula_participes ,aa.nombre_participes ,aa.apellido_participes ,bb.id_entidad_patronal,
                bb.nombre_entidad_patronal, aa.direccion_participes";
	        $tab1  = " core_participes aa
	        INNER JOIN core_entidad_patronal bb ON bb.id_entidad_patronal = aa.id_entidad_patronal ";
	        $whe1  = " aa.id_participes = $id_participes ";
	        $rsConsulta1   = $busquedas->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	        
	        $resp['dataParticipe'] = ( empty($rsConsulta1) ) ? null : $rsConsulta1;
	        
	        // AQUI OBTENGO TOTAL DE CUENTA INDIVIDUAL
	        $col2 = " COALESCE(SUM(valor_personal_contribucion),0) + COALESCE(SUM(valor_patronal_contribucion),0) AS total ";
	        $tab2 = "core_contribucion 
                INNER JOIN core_participes ON core_contribucion.id_participes  = core_participes.id_participes";
	        $whe2 = " core_contribucion.id_estatus=1  
            AND core_participes.id_participes = ". $id_participes ;
	        $rsCuentaIndividual = $busquedas->getCondicionesSinOrden($col2, $tab2, $whe2, "");
	        
	        $resp['cuentaIndividual'] = ( empty($rsCuentaIndividual) ) ? null : $rsCuentaIndividual;
	        
	        // AQUI OBTENGO EL SALDO DE CAPITAL CREDITOS
	        $col3 = " COALESCE( SUM( COALESCE( cc.saldo_cuota_tabla_amortizacion_pagos,0 ) ) , 0 ) AS total";
	        $tab3 = "core_creditos aa
    	        INNER JOIN core_tabla_amortizacion bb ON bb.id_creditos = aa.id_creditos
    	        INNER JOIN core_tabla_amortizacion_pagos cc ON cc.id_tabla_amortizacion = bb.id_tabla_amortizacion";
	        $whe3 = "cc.id_estatus = 1
    	        AND bb.id_estatus = 1
    	        AND aa.id_estatus = 1
    	        AND aa.id_estado_creditos = 4
    	        AND cc.id_tabla_amortizacion_parametrizacion = 15
    	        AND bb.id_estado_tabla_amortizacion <> 2
    	        AND aa.id_participes = ".$id_participes;
	        $rsSaldoCredito = $busquedas->getCondicionesSinOrden( $col3, $tab3, $whe3, "");
	        
	        $resp['saldoCreditos'] = ( empty($rsSaldoCredito) ) ? null : $rsSaldoCredito;
	        
	        // AQUI AGRUPAR POR MES valor_personal_contribucion PARA VER 3 ULTIMAS APORTACIONES
	        $col4 = " TO_CHAR( aa.fecha_registro_contribucion,'YYYYMM') ";
	        $tab4 = "core_contribucion aa
	           INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes";
	        $whe4 = " aa.id_contribucion_tipo = 1
    	        AND aa.id_estatus = 1
    	        AND bb.id_estatus = 1
    	        AND bb.id_participes = ".$id_participes;
	        $gru4 = " TO_CHAR( aa.fecha_registro_contribucion,'YYYYMM')";
	        $id4  = " TO_CHAR( aa.fecha_registro_contribucion,'YYYYMM')";
	        $rsAportes = $busquedas->getCondiciones_grupo($col4, $tab4, $whe4, $gru4, $id4);
	        
	        $resp['numeroAportes'] = ( empty($rsAportes) ) ? 0 : sizeof($rsAportes);
	       	        	       
	        
	        $error_pg = pg_last_error();
	        if( !empty($error_pg) ){
	            throw new Exception( $error_pg );
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
	
	/**
	 * dc 2020/07/21 *
	 */
	public function obtenerTipoCredito()
	{
	    session_start();
	    ob_start();
	    $response = array();
	    $rp_capremci = new PlanCuentasModel();
	    $columnas = "aa.id_tipo_creditos, aa.codigo_tipo_creditos, aa.nombre_tipo_creditos";
	    $tablas = "core_tipo_creditos aa INNER JOIN estado bb ON bb.id_estado = aa.id_estado";
	    $where = "aa.id_estatus=1 AND bb.nombre_estado='ACTIVO'";
	    $id = "aa.id_tipo_creditos";
	    $resultSet = $rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $salida = ob_get_clean();
	    
	    if (! empty($salida)) {
	        $response['estatus'] = "ERROR";
	        $response['mensaje'] = "ERROR AL BUSCAR TIPO CREDITOS.(PRODUCTOS)";
	        $response['buffer'] = $salida;
	    } else {
	        $response['estatus'] = "OK";
	        $response['mensaje'] = "";
	        $response['data'] = $resultSet;
	    }
	    echo json_encode($response);
	}
	
	/**
	 * dc 2020/07/20 *
	 */
	public function buscarPlazoCuotas()
	{
	    ob_start();
	    session_start();
	    $modelo = new ModeloModel();
	    $monto_credito = $_POST['monto_credito'];
	    $id_participes = $_POST['id_participes'];
	    $tipo_creditos = $_POST['tipo_creditos'];
	    
	    //local variables
	    $plazo_maximo = 0;
	    $data = array();
	    
	    // traigo informacion del participe para veriricar la edad
	    $col1 = " nombre_participes, apellido_participes, cedula_participes, fecha_nacimiento_participes";
	    $tab1 = " public.core_participes";	    
	    $whe1 = " id_participes = ".$id_participes;
	    
	    $rsParticipe   = $modelo->getCondicionesSinOrden( $col1, $tab1, $whe1, "");
	   
	    // consigo la edad del participe
	    $hoy = date("Y-m-d");
	    $dias_hasta = $this->dateDifference( $rsParticipe[0]->fecha_nacimiento_participes, $hoy);
	    $dias_75 = 365 * 75;
	    $diferencia_dias   = $dias_75 - $dias_hasta;
	    $meses_disponibles = $diferencia_dias / 30;
	    $meses_disponibles = floor( $diferencia_dias * 1 ) / 1;
	    
	    // consigo la tasa de interes y plazo maximo del credito seleccionado
	    $col2 = " interes_tipo_creditos, plazo_maximo_tipo_creditos";
	    $tab2 = " core_tipo_creditos";
	    $whe2 = " codigo_tipo_creditos = '" . $tipo_creditos . "'";
	    $rsConsulta2   = $modelo->getCondicionesSinOrden($col2, $tab2, $whe2, "");
	    
	    $plazo_maximo_tipo_creditos    = $rsConsulta2[0]->plazo_maximo_tipo_creditos;
	    
	    // calculo interes mensual
	    $tasa_interes = $rsConsulta2[0]->interes_tipo_creditos;
	    $tasa_interes = $tasa_interes / 100;
	    $interes_mensual = $tasa_interes / 12;
	    
	    //buscar los plazo maximo aceptado por el valor solicitado
	    $col3  = " cuotas_rango_plazos_creditos";
	    $tab3  = " public.core_plazos_creditos";
	    $whe3  = " maximo_rango_plazos_creditos >= $monto_credito
	       AND minimo_rango_plazos_creditos <= $monto_credito";
	    
	    $rsConsulta3   = $modelo->getCondicionesSinOrden($col3, $tab3, $whe3, "");
	    	   
	    if( !empty( $rsConsulta3 ) ){
	        
	        
	        
	        $plazo_maximo_monto    = $rsConsulta3[0]->cuotas_rango_plazos_creditos;
	        
	        if ( $plazo_maximo_monto > $plazo_maximo_tipo_creditos) {
	            
	            $plazo_maximo = $plazo_maximo_tipo_creditos;
	        }else{
	            $plazo_maximo = $plazo_maximo_monto;
	        }
	        
	        //buscar los plazos aceptados por el valor solicitado
	        $col4  = " cuotas_rango_plazos_creditos, maximo_rango_plazos_creditos, minimo_rango_plazos_creditos";
	        $tab4  = " public.core_plazos_creditos";
	        $whe4  = " cuotas_rango_plazos_creditos <= $plazo_maximo";
	        $id4   = " ORDER BY cuotas_rango_plazos_creditos DESC "; 
	        
	        $rsConsulta4   = $modelo->getCondicionesSinOrden($col4, $tab4, $whe4, $id4 );
	        
	       
	        foreach ( $rsConsulta4 as $res ){
	            
	            $plazo_cuotas = $res->cuotas_rango_plazos_creditos;
	            
	            $valor_cuota = ($monto_credito * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $plazo_cuotas));
	            $valor_cuota = round($valor_cuota, 2);
	            
	            $data[] = array('plazo'=>$plazo_cuotas, 'valor'=>$valor_cuota);
	            
	        }
	        
	    }
	    
	    $response = array();
	    $response['estatus'] = "OK";
	    $response['cuotas'] = $data;
	    $response['monto'] = $monto_credito;
	    
	    $salida = ob_get_clean();
	    if (! empty($salida)) {
	        $response = array();
	        $response['estatus'] = "ERROR";
	        $response['buffer'] = error_get_last();
	        $response['mensaje'] = "ERROR al obtener cuotas disponibles";
	        $response['salida'] = $salida;
	    }
	    
	    echo json_encode($response);
	}
	
	
	/********************************************************************************* UTILS ******************************************************/
	function getNombreMes($mes){
	    
	    $meses = array(
	        '1'=>"ENERO",
	        '2'=>"ENERO",
	        '3'=>"ENERO",
	        '4'=>"ENERO",
	        '5'=>"ENERO",
	        '6'=>"ENERO",
	        '7'=>"ENERO",
	        '8'=>"ENERO",
	        '9'=>"ENERO",
	        '10'=>"ENERO",
	        '11'=>"ENERO",
	        '12'=>"ENERO"
	    );
	    
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    
	    $numMes = (int)$mes;
	    
	    return $meses[ $numMes-1];
	}
	
	 
	public function dateDifference($date_1, $date_2, $differenceFormat = '%a')
	{
	    $datetime1 = date_create($date_1);
	    $datetime2 = date_create($date_2);
	    
	    $interval = date_diff($datetime1, $datetime2);
	    
	    return $interval->format($differenceFormat);
	}
	
	
}
?>