<?php

class TransferenciasController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
	    
		$CuentasPagar = new CuentasPagarModel();
		
		
		if( empty( $_SESSION['usuario_usuarios'] ) ){
		    $this->redirect("Usuarios","sesion_caducada");
		    exit();
		}
		
		$nombre_controladores = "GenerarTranferencias";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $CuentasPagar->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Transferencias"
		        
		    ));
		    exit();
		}
		
		if( !isset($_GET['id_cuentas_pagar']) ){
		    $this->redirect("Pagos","index");
		    exit();
		}
		
		$_id_cuentas_pagar = $_GET['id_cuentas_pagar'];
		
		$datosVista = array(); //variable para almacenar varaiables que se pasaran a la vista 
		
		/*traer datos de la cuenta por pagar*/
		$col1 = " aa.id_cuentas_pagar, aa.total_cuentas_pagar, aa.saldo_cuenta_cuentas_pagar, aa.origen_cuentas_pagar,
    		aa.descripcion_cuentas_pagar, aa.id_estado, aa.id_forma_pago, bb.concepto_ccomprobantes, bb.id_ccomprobantes, cc.nombre_lote,
    		cc.id_lote, cc.descripcion_lote, dd.nombre_proveedores, dd.id_bancos, dd.id_tipo_cuentas, ee.nombre_tipo_proveedores,
    		dd.id_proveedores, dd.numero_cuenta_proveedores, dd.identificacion_proveedores";
		$tab1 = "tes_cuentas_pagar aa
        		INNER JOIN ccomprobantes bb ON aa.id_ccomprobantes = bb.id_ccomprobantes
        		INNER JOIN tes_lote cc ON cc.id_lote = aa.id_lote
                INNER JOIN proveedores dd ON aa.id_proveedor = dd.id_proveedores
                INNER JOIN tes_tipo_proveedores ee ON dd.id_tipo_proveedores = ee.id_tipo_proveedores";
		$whe1 = " aa.id_cuentas_pagar = $_id_cuentas_pagar ";
		$id1 = "aa.id_cuentas_pagar";		
		
		$rsConsulta1 = $CuentasPagar->getCondiciones($col1, $tab1, $whe1, $id1);
		
		//si la consulta devuelve resultado vacio no esta bien generado la cuenta por pagar
		if( empty($rsConsulta1) ){
		    $this->redirect("Pagos","index");
		}
		
		$id_proveedores               = $rsConsulta1[0]->id_proveedores;
		$id_tipo_cuenta               = $rsConsulta1[0]->id_tipo_cuentas;
		$id_ccomprobantes             = $rsConsulta1[0]->id_ccomprobantes;
		$identificacion_proveedores   = $rsConsulta1[0]->identificacion_proveedores;
		
		$datosVista['id_lote']            = $rsConsulta1[0]->id_lote;
		$datosVista['id_cuentas_pagar']   = $_id_cuentas_pagar;
		$datosVista['descripcion']        = $rsConsulta1[0]->descripcion_cuentas_pagar;
		$datosVista['id_proveedores']     = $id_proveedores;
		$datosVista['identificacion_proveedores']     = $identificacion_proveedores;
		$datosVista['total_cuentas_pagar']            = $rsConsulta1[0]->total_cuentas_pagar;
		$datosVista['nombre_lote']                    = $rsConsulta1[0]->nombre_lote;
		$datosVista['saldo_cuenta_cuentas_pagar']     = $rsConsulta1[0]->saldo_cuenta_cuentas_pagar; 
		$datosVista['id_tipo_cuentas']    = $id_tipo_cuenta;
		
		$nombre_tipo_proveedores = $rsConsulta1[0]->nombre_tipo_proveedores;
		
		if( is_null($nombre_tipo_proveedores) || $nombre_tipo_proveedores == "PAGO PROVEEDORES"  || $nombre_tipo_proveedores == "EMPLEADO" ){
		    
		    //cuando es pago a proveedores
		    $datosVista['nombre_beneficiario'] = $rsConsulta1[0]->nombre_proveedores;
		    $datosVista['apellido_beneficiario'] = "";
		    $datosVista['numero_cuenta_banco'] = $rsConsulta1[0]->numero_cuenta_proveedores;
		    
		    $col2 = "aa.id_bancos, aa.nombre_bancos";
		    $tab2 = "tes_bancos aa LEFT JOIN proveedores bb ON aa.id_bancos = bb.id_bancos";
		    $whe2 = " bb.id_proveedores = $id_proveedores ";
		    $id2 = "aa.id_bancos";
		    $rsConsulta2 = $CuentasPagar->getCondiciones($col2, $tab2, $whe2, $id2);
		    if(!empty($rsConsulta2)){
		        $datosVista['nombre_bancos'] = $rsConsulta2[0]->nombre_bancos;
		        $datosVista['id_bancos'] = $rsConsulta2[0]->id_bancos;
		    }else{
		        $datosVista['nombre_bancos'] = "";
		        $datosVista['id_bancos'] = 0;
		    }
		    
		    $col3 = "*";
		    $tab3 = "core_tipo_cuentas";
		    $whe3 = "id_tipo_cuentas = $id_tipo_cuenta ";
		    $id3 = "id_tipo_cuentas";
		    $rsConsulta3 = $CuentasPagar->getCondiciones($col3, $tab3, $whe3, $id3);
		    if(!empty($rsConsulta3)){
		        $datosVista['nombre_tipo_cuenta_banco'] = $rsConsulta3[0]->nombre_tipo_cuentas;
		    }else{
		        $datosVista['nombre_tipo_cuenta_banco'] = "";
		    }
		    
		}else if($nombre_tipo_proveedores == "PARTICIPE"){
		    
		    $col4 = "aa.nombre_participes,aa.apellido_participes,bb.numero_participes_cuentas,cc.nombre_tipo_cuentas,dd.id_bancos,dd.nombre_bancos";
		    $tab4 = "core_participes aa
    		        LEFT JOIN core_participes_cuentas bb ON aa.id_participes = bb.id_participes AND bb.cuenta_principal = true
        		    LEFT JOIN core_tipo_cuentas cc ON cc.id_tipo_cuentas = bb.id_tipo_cuentas
        		    LEFT JOIN tes_bancos dd ON bb.id_bancos = dd.id_bancos";
		    $whe4 = "  aa.id_estatus = 1 AND cedula_participes = '$identificacion_proveedores'";
		    $id4 = "aa.id_participes";
		    $rsConsulta4 = $CuentasPagar->getCondiciones($col4, $tab4, $whe4, $id4);
		    
		    if(!empty($rsConsulta4)){
		        $datosVista['nombre_beneficiario']        = $rsConsulta4[0]->nombre_participes;
		        $datosVista['apellido_beneficiario']      = $rsConsulta4[0]->apellido_participes;
		        $datosVista['numero_cuenta_banco']        = $rsConsulta4[0]->numero_participes_cuentas;
		        $datosVista['nombre_bancos']               = $rsConsulta4[0]->nombre_bancos;
		        $datosVista['nombre_tipo_cuenta_banco']   = $rsConsulta4[0]->nombre_tipo_cuentas;
		        $datosVista['id_bancos']                  = $rsConsulta4[0]->id_bancos;
		    }else{
		        $datosVista['nombre_beneficiario'] = "";
		        $datosVista['apellido_beneficiario'] = "";
		        $datosVista['numero_cuenta_banco'] = "";
		        $datosVista['nombre_banco'] = "";
		        $datosVista['nombre_tipo_cuenta_banco'] = "";
		    }
		    
		    
		}//aqui aumentar para mas opciones de transferencias
		
		//buscar datos de credito
		$col5 = " aa.id_creditos, aa.numero_creditos, bb.id_ccomprobantes, cc.id_tipo_creditos, cc.nombre_tipo_creditos, cc.codigo_tipo_creditos ";
		$tab5 = " core_creditos aa
    		INNER JOIN ccomprobantes bb on bb.id_ccomprobantes = aa.id_ccomprobantes
    		INNER JOIN core_tipo_creditos cc on cc.id_tipo_creditos = aa.id_tipo_creditos";
		$whe5 = " bb.id_ccomprobantes = $id_ccomprobantes ";
		$id5  = " aa.id_creditos";
		
		$rsConsulta5 = $CuentasPagar->getCondiciones($col5, $tab5, $whe5, $id5);
		
		if( !empty($rsConsulta5) ){
		    $datosVista['iscredito']              = true;
		    $datosVista['id_creditos']            = $rsConsulta5[0]->id_creditos;
		    $datosVista['numero_creditos']        = $rsConsulta5[0]->numero_creditos;
		    $datosVista['id_tipo_creditos']       = $rsConsulta5[0]->id_tipo_creditos;
		    $datosVista['nombre_tipo_creditos']   = $rsConsulta5[0]->nombre_tipo_creditos;
		    $datosVista['codigo_tipo_creditos']   = $rsConsulta5[0]->codigo_tipo_creditos;
		}
		
		//generar array de respuesta a vista
		$resultset =  array((object)$datosVista);
		
		//var_dump( error_get_last() ); die();
		
		$this->view_tesoreria("Transferencias",array(
		    "resultset"=>$resultset
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
	
	public function GeneraTransferencia(){
	    
	    session_start();
	    $resp  = null;
	    $pagos = new PagosModel(); 
	    
	    
	    /* REVISON DE PARAMETROS RECIBIDOS */
	    /* toma de datos */
	    $_lista_distribucion   = json_decode($_POST['lista_distribucion']);
	    $_id_bancos_local      = $_POST['id_bancos_local'];
	    $_id_bancos_transferir = $_POST['id_bancos_transferir'];
	    $_id_cuentas_pagar     = $_POST['id_cuentas_pagar'];
	    $_fecha_transferencia  = $_POST['fecha_transferencia'];
	    $_numero_cuenta_banco  = $_POST['numero_cuenta_banco'];
	    $_isCredito            = $_POST['is_credito'];
	    $_id_tipo_cuentas      = $_POST['id_tipo_cuentas'];
	    $_descripcion_pago       = $_POST['descripcion_pago'];
	    
	    //variable usada para determinar el tipo de archivo de pago
	    $_id_tipo_archivo_pago = $_POST['id_tipo_archivo_pago'];
	    
	    //dc 2020/07/22
	    $chk_pago_parcial  = $_POST['check_pago_parcial'];
	    $valor_pago_parcial    = $_POST['valor_pago_parcial'];	    
	        
	    $_isCredito = filter_var($_isCredito, FILTER_VALIDATE_BOOLEAN); //se realiza la conversion de string a un boleano 
	   
	    try {
	        
	        $error = error_get_last();
	        if( !empty($error) ){
	            throw new Exception("variables no de definidas! ");
	        }
	        
	        /** VALIDACION QUE NO EXISTA CUENTAS POR PAGAR REPETIDAS **/ 
	        $colPago   = " bb.id_pagos, bb.valor_pagos, bb.metodo_pagos";
	        $tabPago   = " tes_cuentas_pagar aa
    	        INNER JOIN tes_pagos bb ON bb.id_cuentas_pagar = aa.id_cuentas_pagar
    	        INNER JOIN estado cc ON cc.id_estado = aa.id_estado ";
	        $whePago   = " cc.nombre_estado = 'APLICADO' AND aa.id_cuentas_pagar = $_id_cuentas_pagar";
	        $rsPago    = $pagos->getCondicionesSinOrden( $colPago, $tabPago, $whePago, "");
	        	        
	        if( !empty($rsPago) ){
	            $resp['icon'] = "info";
	            throw new Exception("Pago ya se encuentra generado! Favor revisar");
	        }	
	        
	        $pagos->beginTran();
	        
	        //buscar datos de tipo de pago
	        $colBancos     = " id_bancos, nombre_bancos ";
	        $tabBancos     = " public.tes_bancos ";
	        $wheBancos     = " id_bancos = $_id_bancos_transferir ";
	        $rsConsultaBancos  = $pagos->getCondicionesSinOrden($colBancos, $tabBancos, $wheBancos, "");
	        
	        if( empty($rsConsultaBancos) ){
	            $resp['icon'] = "warning";
	            throw new Exception(" Datos Bancos Transferir encontrada!");
	        }
	        $nombre_bancos_transferir = $rsConsultaBancos[0]->nombre_bancos;
	        
	        
	        //buscar datos de tipo de pago
	        $col1  = " id_forma_pago,nombre_forma_pago ";
	        $tab1  = " public.forma_pago ";
	        $whe1  = " nombre_forma_pago = 'TRANSFERENCIA' ";
	        $rsConsulta1   = $pagos->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	        
	        if( empty($rsConsulta1) ){
	            $resp['icon'] = "warning";
	            throw new Exception(" Forma de pago no encontrada!");
	        }
	        $id_forma_pago = $rsConsulta1[0]->id_forma_pago;
	        
	        //buscar datos de cuentas por pagar y comprobantes generados
	        $col2  = " aa.id_cuentas_pagar, aa.total_cuentas_pagar, aa.saldo_cuenta_cuentas_pagar, aa.origen_cuentas_pagar, aa.descripcion_cuentas_pagar, aa.id_estado,".
                        "aa.id_forma_pago, bb.id_ccomprobantes, bb.concepto_ccomprobantes, cc.id_lote, cc.descripcion_lote, dd.id_proveedores, dd.identificacion_proveedores,".
                        "nombre_proveedores";
	        $tab2  =  " tes_cuentas_pagar aa ".
	           " INNER JOIN ccomprobantes bb  ON bb.id_ccomprobantes = aa.id_ccomprobantes ".
	           " INNER JOIN tes_lote cc ON cc.id_lote = aa.id_lote ".
	           " INNER JOIN proveedores dd  ON  dd.id_proveedores = aa.id_proveedor ";
	        $whe2  = " 1 = 1 ".
	           " AND aa.id_cuentas_pagar = $_id_cuentas_pagar ";
	        $rsConsulta2   = $pagos->getCondicionesSinOrden($col2, $tab2, $whe2,"");
	        
	        if( empty( $rsConsulta2 ) ){
	            $resp['icon'] = "warning";
	            throw new Exception(" Datos Relacionados a la cuenta por pagar no encontrados!");
	        }
	        
	        //seteamos variables que necesitamos 
	        $total_cuentas_pagar   = 0.00;
	        $valor_aplicado_cuentas_pagar = 0.00; //dc 2020/07/22
	        
	        //$descripcion_cuentas_pagar = "";
	        $id_ccomprobantes      = $rsConsulta2[0]->id_ccomprobantes;
	        $id_proveedores        = $rsConsulta2[0]->id_proveedores;
	        $total_cuentas_pagar   = $rsConsulta2[0]->total_cuentas_pagar; 
	        $saldo_cuentas_pagar   = $rsConsulta2[0]->saldo_cuenta_cuentas_pagar;
	        $id_creditos       = 'null';
	        $beneficiario      = $rsConsulta2[0]->nombre_proveedores;
	        $concepto_credito  = "";
	        $observacion_comprobantes  = "PAGO  PROVEEDORES";
	        $datosMsgParticipe         = null;	        
	        
	        //dc 2020/07/22
	        $valor_aplicado_cuentas_pagar  = $saldo_cuentas_pagar;
	        
	        if( $chk_pago_parcial == "1" ){
	            $valor_aplicado_cuentas_pagar  = $valor_pago_parcial;
	        }
	        
	        
	        //throw new Exception("error de prueba");
	                                                                       
	        /* ********************************************************** */
	        /** viene la insercion del pago en bd **/
	        
	        /* validacion para ver si es credito el pago */
	        if( (bool)$_isCredito == true ){	            
	            // buscamos el credito
	            $col3  = " bb.id_creditos, bb.cuota_creditos, bb.numero_creditos, cc.id_participes, cc.nombre_participes, cc.apellido_participes, celular_participes ";
	            $tab3  = " ccomprobantes aa ".
	               " INNER JOIN core_creditos bb on bb.id_ccomprobantes = aa.id_ccomprobantes".
	               " INNER JOIN core_participes cc on cc.id_participes = bb.id_participes";
	            $whe3  = " 1 = 1 ".
	               " AND aa.id_ccomprobantes = $id_ccomprobantes";
	            
	            //$resp['sql'] = " SELECT ".$col3."  FROM ". $tab3." WHERE  ". $whe3;
	            
	            $rsConsulta3   = $pagos->getCondicionesSinOrden($col3, $tab3, $whe3,"");
	            
	            if( empty($rsConsulta3) ){
	                throw new Exception("Comprobante de credito no encontrado!");
	            }
	            
	            $id_creditos   = $rsConsulta3[0]->id_creditos;
	            $beneficiario  = $rsConsulta3[0]->apellido_participes. " ".$rsConsulta3[0]->nombre_participes;
	            $concepto_credito  = " DEL CREDITO ".$rsConsulta3[0]->numero_creditos;
	            $observacion_comprobantes  = "PAGO CREDITO Nro ".$rsConsulta3[0]->numero_creditos;
	            
	            // llenar datos para envio de mensaje de texto al celular
	            $datosMsgParticipe['celular_participes']   = $rsConsulta3[0]->celular_participes;
	            $datosMsgParticipe['nombre_participes']    = $rsConsulta3[0]->nombre_participes;
	            $datosMsgParticipe['apellido_participes']    = $rsConsulta3[0]->apellido_participes;
	            $datosMsgParticipe['numero_cuenta']    = $_numero_cuenta_banco;
	            $datosMsgParticipe['nombre_banco']     = $nombre_bancos_transferir;	            
	            
	        }
	        	        	        
	        $auxPago = $this->auxInsertPago($_id_cuentas_pagar, $id_creditos,$id_proveedores, $id_forma_pago, $_fecha_transferencia, $_id_bancos_transferir, $_numero_cuenta_banco,$_id_tipo_cuentas,$_id_bancos_local,$valor_aplicado_cuentas_pagar);
	        
	        if( $auxPago['error'] === true ){
	            throw new Exception( "Inserción Pagos! ".$auxPago['mensaje'] );
	        }
	        
	        //tomo datos de la insercion de pagos
	        $id_pagos = $auxPago['id_pagos'];
	        
	        /* ********************************************************** */
	        /** viene la distribucion del pago **/
	        $auxDistribucion = $this->auxInsertDistribucionPago($_lista_distribucion, $id_pagos, $_fecha_transferencia, $valor_aplicado_cuentas_pagar);
	        if( $auxDistribucion['error'] === true ){
	            throw new Exception( "Distribucion Pagos!".$auxDistribucion['mensaje'] );
	        }
	        // no se toma datos de la distribucion solo valida 
	        
	        /* ********************************************************** */
	        /** viene insercion comprobante de pago **/
	        $datos = array(
	            'id_pagos' => $id_pagos,
	            'beneficiario' => $beneficiario,
	            'total_pago' => $valor_aplicado_cuentas_pagar,
	            'concepto_credito' =>$concepto_credito,
	            'id_proveedores' => $id_proveedores,
	            'id_forma_pago' => $id_forma_pago,
	            'id_bancos' => $_id_bancos_transferir,
	            'fecha' => $_fecha_transferencia,
	            'numero_cuenta' => $_numero_cuenta_banco,
	            'observacion' => $observacion_comprobantes,
	            'concepto' => $_descripcion_pago
	        );
	        
	        $auxComprobante = $this->auxInsertComprobante($datos);
	        
	        if( $auxComprobante['error'] === true ){
	            throw new Exception( "Inserción Comprobante! ".$auxComprobante['mensaje'] );
	        }
	        
	        //tomo datos de la insercion de pagos
	        $id_comprobantes = $auxComprobante['id_ccomprobantes'];
	        
	        /* ********************************************************** */
	        /** viene actualizacion de tablas **/
	        $columnaPago = "id_ccomprobantes = $id_comprobantes ";
	        $tablasPago = "tes_pagos";
	        $wherePago = "id_pagos = $id_pagos";
	        $pagos -> ActualizarBy($columnaPago, $tablasPago, $wherePago);	       
	        
	        //dc 2020/07/22
	        //buscar y actualizar estado de cuentas por pagar
	        $residuo_cuentas_pagar = $saldo_cuentas_pagar - $valor_aplicado_cuentas_pagar;
	        if( empty($residuo_cuentas_pagar) ){
	            $queryEstado = "SELECT id_estado FROM estado WHERE tabla_estado='tes_cuentas_pagar' AND nombre_estado = 'APLICADO'";
	        }else{
	            $queryEstado = "SELECT id_estado FROM estado WHERE tabla_estado='tes_cuentas_pagar' AND nombre_estado = 'PARCIAL'";
	        }
	        	        
	        $rsEstado = $pagos -> enviaquery($queryEstado);
	        $_id_estado = $rsEstado[0]->id_estado;
	        $pagos->ActualizarBy("id_estado = $_id_estado, saldo_cuenta_cuentas_pagar = $residuo_cuentas_pagar", "tes_cuentas_pagar", "id_cuentas_pagar = $_id_cuentas_pagar");
	        
	        /** proceso de envio de mensaje se realizara si es credito **/ 
	        if( $_isCredito ){
	            
	            //comentado para no gastar servicio de mensajeria
	            /*
	            $auxMsg = $this->auxEnviarMsgMovil(true, $datosMsgParticipe);
	            
	            if( $auxMsg['error'] == true ){
	                $resp['msgtexto'] = array('estatus'=>'ERROR','mensaje'=>"mensaje de texto no enviado!"); //este proceso no genera una excepcion
	            }
	            */
	        }
	        
	        /** viene generacion de archivo plano con valores para envio de datos**/
	        /* analizar proceso de generacion archivo plano **/
	        //se viene deacuerdo al tipo de archivo que se necesite 
	        // se guardara los detalles del archivo pago 
	        // viene en funcion subfuncion
	        $datos = array(
	            'id_tipo_archivo_pago'=>$_id_tipo_archivo_pago,
	            'id_pagos'=>$id_pagos
	        );
	        $auxArchivo = $this->auxRegistraArchivoPago($datos,$_fecha_transferencia);
	        
	        if( $auxArchivo['error'] === true ){
	            throw new Exception( "Inserción Archivo! ".$auxArchivo['mensaje'] );
	        }
	       	       
	        $resp['icon'] = "success";
	        $resp['mensaje'] = "Pago generado con exito";
	        $resp['estatus'] = "OK";
	        
	        $pagos->endTran('COMMIT'); //inserta valores todos los generados
	        
	    } catch (Exception $e) {
            $pagos->endTran(); //regresa valores en caso de generarse un error 
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
	
	function auxInsertPago($id_cuentas_pagar,$id_creditos,$id_proveedores,$id_forma_pago,$fecha_transaccion,$id_bancos,$num_cuenta_banco,$id_t_cuenta,$id_bancos_local,$valor_pagos){
	    $Pagoresp = null;
	    $pagos = new PagosModel();
	    
	    if( !isset($_SESSION) ){
	        session_start();
	    }
	    
	    $usuario_usuarios = $_SESSION['usuario_usuarios'];
	   	    
	    //para ingresar pago
	    $funcionPago = "ins_tes_pagos";
	    $parametrosPago = "$id_cuentas_pagar,
            	        $id_creditos,
            	        $id_proveedores,
            	        null,
            	        $id_forma_pago,
            	        '$fecha_transaccion',
            	        'TRANSFERENCIA',
                        '$valor_pagos',
            	        $id_bancos ,
            	        null,
            	        '$num_cuenta_banco',
                        null,
            	        $id_t_cuenta,
                        $id_bancos_local,
                        '$usuario_usuarios'";
	    
        $consultaPago = $pagos->getconsultaPG($funcionPago, $parametrosPago);
        $ResulatadoPago = $pagos->llamarconsultaPG($consultaPago);
	    
	    $error = "";
	    $error = pg_last_error();
	    if(!empty($error)){
	        $Pagoresp['error']     = true;
	        $Pagoresp['mensaje']   = $error;
	        return $Pagoresp;
	    }
	    
	    $_id_pagos = (int)$ResulatadoPago[0];
	    
	    $Pagoresp['error']     = false;
	    $Pagoresp['mensaje']   = "";
	    $Pagoresp['id_pagos']  = $_id_pagos;    
	    
	    return $Pagoresp;
	}
	
	function auxInsertDistribucionPago( array $LstDistribucion, $id_pagos, $fecha_transaccion, $total_pago){
	    $Distribucionresp = null;
	    $pagos = new PagosModel();
	    
	    if( empty($LstDistribucion) ){
	        $Distribucionresp['error']     = true;
	        $Distribucionresp['mensaje']   = "Array de distribucion se encuentra vacio!";
	        return $Distribucionresp;
	    }
	    
	    foreach ($LstDistribucion as $data){
	        
	        $destino_distribucion = "";
	        if($data->tipo_pago == "debito"){
	            $destino_distribucion = "DEBE";
	        }else{
	            $destino_distribucion = "HABER";
	        }
	        
	        $queryDistribucionPagos = "INSERT INTO tes_distribucion_pagos
    	        (id_pagos, id_plan_cuentas, fecha_distribucion_pagos, valor_distibucion_pagos, destino_distribucion_pagos)
    	        VALUES('$id_pagos', '$data->id_plan_cuentas' , '$fecha_transaccion', $total_pago, '$destino_distribucion')";
	        
	        $ResultDistribucionPagos = $pagos -> executeNonQuery($queryDistribucionPagos);
	        
	        if(!is_int($ResultDistribucionPagos) || $ResultDistribucionPagos <= 0 ){	            
	            break;
	            
	        }
	        
	    }
	    
	    $error = pg_last_error();
	    if( !empty($error) ){
	        $Distribucionresp['error']     = true;
	        $Distribucionresp['mensaje']   = $error;
	        return $Distribucionresp;
	    }
	    
	    $Distribucionresp['error']     = false;
	    $Distribucionresp['mensaje']   = "";  
	    
	    return $Distribucionresp;
	}
	
	function auxInsertComprobante( array $datos ){
	    $Comprobanteresp = null;
	    $pagos = new PagosModel();
	    if( !isset( $_SESSION) ){
	        session_start();
	    }
	    $id_usuarios  = $_SESSION['id_usuarios'];
	    
	    $total_pago   = $datos['total_pago'];
	    $_concepto_comprobante = " TRANSACCION TRANSFERENCIA A .".$datos['beneficiario'].". ".$datos['concepto_credito'];
	    $valor_letras_pago = $pagos->numtoletras($total_pago);
	    
	    $funcionComprobante = "tes_agrega_comprobante_pagos_transferencia";
	    $parametrosComprobante = "'$id_usuarios',
            	        ".$datos['id_bancos'].",
            	        ".$datos['id_pagos'].",
            	        ".$datos['id_proveedores'].",
            	        ".$datos['id_forma_pago'].",
                        '$total_pago',
                        '$valor_letras_pago',
            	        '".$datos['fecha']."',
            	        'TRANSFERENCIA',
            	        '".$datos['numero_cuenta']."',
            	        null,
            	        '".$datos['observacion']."',
            	        'PAGO',
            	        null,
                          '".$datos['concepto']."'
                        ";
	    
	    $consultaComprobante = $pagos->getconsultaPG($funcionComprobante, $parametrosComprobante);
	    $ResulatadoComprobante = $pagos->llamarconsultaPG($consultaComprobante);
	    
	    $error = "";
	    $error = pg_last_error();
	    if( !empty($error) ){
	        $Comprobanteresp['error']     = true;
	        $Comprobanteresp['mensaje']   = $error;
	        return $Comprobanteresp;
	    }
	    
	    $Comprobanteresp['error']     = false;
	    $Comprobanteresp['id_ccomprobantes']   = $ResulatadoComprobante[0];
	    return $Comprobanteresp;
	}
	
	/***
	 * @param bool $pruebas //variable para definir si la expresion se va a pruebas o a produccion
	 * @param array $datos  //array con datos predefinidos para llenar datos en el mensaje de texto
	 * @return boolean[]|mixed[]|boolean[]|string[]
	 */
	function auxEnviarMsgMovil( bool $pruebas, array $datos ){
	    
	    if( $pruebas ){
	        $_celular_mensaje = "0987474892";
	    }else{
	        $_celular_mensaje = $datos['celular_participes'];
	    }
	    
	    $_nombres_mensajes = $datos['nombre_participes']." ".$datos['apellido_participes'];
	    $_num_cuenta = "XXXXXX".substr( $datos['numero_cuenta'], 6);
	    $_nombre_banco = $datos['nombre_banco'];
	    $_codigo_mensajes = str_replace(' ','_',$_num_cuenta.'-'.$_nombre_banco);
	    $_id_mensaje_mensajes = "22443";
	    $this->comsumir_mensaje_plus($_celular_mensaje, $_nombres_mensajes, $_codigo_mensajes, $_id_mensaje_mensajes);
	    
	    $error = error_get_last();
	    if( !empty($error)){
	        return array('error'=>true,'mensaje'=>$error['message']);
	    }
	    
	    return array('error'=>false,'mensaje'=>"");
	}
	
	function auxRegistraArchivoPago(array $datos, string $fecha_proceso){
	    
	    $pagos = new PagosModel();
	    $aux=null;
	    //variables para el insertado del archivo de pago	    
	    $id_tipo_archivo_pago = $datos['id_tipo_archivo_pago'];
	    $id_pagos = $datos['id_pagos'];	    
	    $pago_archivo_pago = "PA";
	    $contrapartida_pago = ""; //esta columna puede ser configurable .. puede ser un secuencial o la cedula como en los ejemplos
	    $moneda_pago = "USD";
	    $valor_pago = "0"; // tipo dato string y formato sin punto decimal toma dos ultimos digitos como decimal del valor
	    $cuenta_pago = "CTA";
	    $tipo_cuenta_pago = "";
	    $numero_cuenta_pago = "";
	    $referencia_pago = "";
	    $tipo_identificacion_pago = "";
	    $numero_identificacion_pago = "";
	    $beneficiario_pago = "";
	    $codigo_banco_pago = "";
	    $fecha_proceso_pago = $fecha_proceso;
	    $tipo_pago_archivo_pago = ""; // si la variable es Directa D -- Interbancaria I ;
	    
	    if( (int)$datos['id_bancos_local'] === (int)$datos['id_bancos_archivo'] ){
	        $tipo_pago_archivo_pago = "D";
	    }else if( (int)$datos['id_bancos_local'] !== (int)$datos['id_bancos_archivo'] ){
	        $tipo_pago_archivo_pago = "I";
	    }
	    
	    //para diferenciar el tipo de pago que se realiza por concepto
	    $col1 = " id_tipo_pago_archivo,nombre_tipo_pago_archivo ";
	    $tab1 = " public.tes_tipo_pago_archivo";
	    $whe1 = " id_tipo_pago_archivo = $id_tipo_archivo_pago";
	    $rsConsulta1 = $pagos->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	    if(empty($rsConsulta1) ){
	       return array('error'=>true,'mensaje'=>'tipo de archivo pago no definido'); 
	    }
	    
	    $nombre_tipo_archivo_pago = $rsConsulta1[0]->nombre_tipo_pago_archivo;
	    $nombre_tipo_archivo_pago = strtoupper($nombre_tipo_archivo_pago);
	    
	    //definir si la refencia se tomara de la descripcion de la transferencia
	    $referencia_pago = (array_key_exists('referencia_pago', $datos) ) ? $datos['referencia_pago'] : "";
	    switch ($nombre_tipo_archivo_pago) {
	        case 'PROVEEDORES':
	            $referencia_pago = "PAGO";
	        break;
	        
	        default:
	            ;
	        break;
	    }
	    
	    $col2 = " aa.id_pagos, cc.codigo_tipo_cuentas, aa.numero_cuenta_bancos_pagos, bb.identificacion_proveedores, bb.nombre_proveedores, dd.codigo_bancos, 
                aa.valor_pagos";
	    $tab2 = " tes_pagos aa
    	    INNER JOIN proveedores bb ON bb.id_proveedores = aa.id_proveedores
    	    INNER JOIN core_tipo_cuentas cc ON cc.id_tipo_cuentas = aa.id_tipo_cuenta
    	    INNER JOIN tes_bancos dd ON dd.id_bancos = aa.id_bancos";
	    $whe2 = " 1 = 1
	        AND aa.id_pagos = $id_pagos";
	    $rsConsulta2   = $pagos->getCondicionesSinOrden($col2, $tab2, $whe2, "");
	    
	    $numero_identificacion_pago = $rsConsulta2[0]->identificacion_proveedores;
	    $numero_identificacion_pago = trim($numero_identificacion_pago); // numero identificacion
	    $beneficiario_pago     = trim( $rsConsulta2[0]->nombre_proveedores );  //beneficiario 
	    $codigo_banco_pago     = trim( $rsConsulta2[0]->codigo_bancos );
	    $tipo_cuenta_pago      = trim( $rsConsulta2[0]->codigo_tipo_cuentas );
	    $numero_cuenta_pago    = trim( $rsConsulta2[0]->numero_cuenta_bancos_pagos );
	    $valor_pago            = $rsConsulta2[0]->valor_pagos;
	    $lengthIdentificacion  = strlen($numero_identificacion_pago);
	    $tipo_identificacion_pago  = ( $lengthIdentificacion == 10 ) ? "C" : ( ( $lengthIdentificacion == 13 ) ? "R" : "P" ); // tipo de identificacion
	    
	    //variable de contrapartida donde debe configurar para que sea sea la cedula o un secuencial
	    $contrapartida_pago    = $numero_identificacion_pago;
	    
	    //configurar el pago al formato del archivo 
	    $valor_pago    = str_replace(",", "", $valor_pago);
	    $valor_pago    = str_replace(".", "", $valor_pago);
	    	    
	    $funcion       = 'ins_tes_archivo_pago';
	    $parametros    = "$id_tipo_archivo_pago,$id_pagos,'$pago_archivo_pago','$contrapartida_pago','$moneda_pago','$valor_pago','$cuenta_pago','$tipo_cuenta_pago',
                        '$numero_cuenta_pago','$referencia_pago','$tipo_identificacion_pago','$numero_identificacion_pago','$beneficiario_pago','$codigo_banco_pago',
                        '$fecha_proceso_pago','$tipo_pago_archivo_pago'";
	    
	    $queryInsert   = $pagos->getconsultaPG($funcion, $parametros);
	    $result        = $pagos->llamarconsultaPG($queryInsert);
	    
	    $error = pg_last_error();
	    if(!empty($error)){
	        return array('error'=>true,'mensaje'=>$error);
	    }
	    
	    $id_archivo_pago   = $result[0];
	    
	    $aux['error']=false;
	    $aux['id_archivo']=$id_archivo_pago;	    
	    
	    return $aux;
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
	
	public function generaTxt(){
	    
	    $Pagos = new PagosModel();
	    
	    try {
	        //buscar datos de pago
	        $id_pago = 13; //seteado para pruebas
	        $anio = 2019;
	        $mes  = 2;
	        
	        $id_bancos_transferir = 0;
	        $_nombre_tipo_cuenta = "";
	        //datos para archivo
	        $_identificador = "PA";
	        $_cedula_beneficiario = "";
	        $_moneda = "USD";
	        $_valor_pago = "";
	        $_tipo = "CTA";
	        $_abrv_tipo_cuenta = "";
	        $_numero_cuenta = "";
	        $_descripcion_archivo = "";
	        $_nombre_beneficiario = "";
	        $_codigo_banco = "";
	        
	        $columnas1 = "aa.id_pagos, aa.valor_pagos, aa.numero_cuenta_bancos_pagos, bb.descripcion_cuentas_pagar, cc.id_tipo_cuentas, cc.nombre_tipo_cuentas,
            	    dd.id_bancos, dd.nombre_bancos, dd.id_bancos_transferir, ee.id_proveedores, ee.identificacion_proveedores, ee.nombre_proveedores,
            	    ff.id_tipo_proveedores, ff.nombre_tipo_proveedores, gg.nombre_participes, gg.apellido_participes, hh.id_forma_pago, hh.nombre_forma_pago";
	        $tablas1   = "tes_pagos aa
            	    INNER JOIN tes_cuentas_pagar bb
            	    ON aa.id_cuentas_pagar = bb.id_cuentas_pagar
            	    INNER JOIN core_tipo_cuentas cc
            	    ON cc.id_tipo_cuentas = aa.id_tipo_cuenta
            	    INNER JOIN tes_bancos dd
            	    ON dd.id_bancos = aa.id_bancos
            	    INNER JOIN proveedores ee
            	    ON ee.id_proveedores = aa.id_proveedores
            	    INNER JOIN tes_tipo_proveedores ff
            	    ON ff.id_tipo_proveedores = ee.id_tipo_proveedores
            	    LEFT JOIN core_participes gg
            	    ON gg.cedula_participes = ee.identificacion_proveedores
                    AND gg.id_estatus = 1
            	    INNER JOIN forma_pago hh
            	    ON hh.id_forma_pago = aa.id_forma_pago";
	        $where1    = "hh.nombre_forma_pago = 'TRANSFERENCIA' AND aa.id_pagos = $id_pago ";
	        $id1       = "aa.id_pagos";
	        
	        $rsConsulta1 = $Pagos->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        
	        //se obtine valores
	        $_nombre_tipo_proveedor    = $rsConsulta1[0]->nombre_tipo_proveedores;
	        $_valor_pago               = number_format((float)$rsConsulta1[0]->valor_pagos, 2, '', '');
	        $_nombre_tipo_cuenta       = $rsConsulta1[0]->nombre_tipo_cuentas;
	        $_nombre_beneficiario      = $rsConsulta1[0]->nombre_proveedores;
	        $_cedula_beneficiario      = $rsConsulta1[0]->identificacion_proveedores;
	        $_numero_cuenta            = $rsConsulta1[0]->numero_cuenta_bancos_pagos;
	        $_descripcion_archivo      = substr($rsConsulta1[0]->descripcion_cuentas_pagar, 0, 50);
	        $id_bancos_transferir      = $rsConsulta1[0]->id_bancos_transferir;
	        
	        if( $_nombre_tipo_proveedor == "PARTICIPE" ){
	            
	            $_nombre_beneficiario = $rsConsulta1[0]->nombre_participes." ".$rsConsulta1[0]->apellido_participes;
	        }
	        
	        //obtener tipo cuenta abreviada
	        if( $_nombre_tipo_cuenta == "AHORROS" ){
	            $_abrv_tipo_cuenta = "AHO";
	        }else{
	            $_abrv_tipo_cuenta = "CTE";
	        }
	        
	        
	        //buscar codigo de banco a transferir
	        $columnas2 = "id_bancos, nombre_bancos, codigo_bancos";
	        $tablas2   = "tes_bancos";
	        $where2    = "id_bancos = $id_bancos_transferir";
	        $id2       = "id_bancos";
	        $rsConsulta2 = $Pagos->getCondiciones($columnas2, $tablas2, $where2, $id2);
	        
	        $_codigo_banco = $rsConsulta2[0]->codigo_bancos;
	        
	        $filaArchivo = array($_identificador,$_cedula_beneficiario,$_moneda,$_valor_pago,$_tipo,$_abrv_tipo_cuenta,$_numero_cuenta,$_descripcion_archivo,$_cedula_beneficiario,$_nombre_beneficiario,$_codigo_banco);
	        $_string_fila = implode("\t", $filaArchivo);
	        /*Generar arcrivo txt*/
	        $url = $this->obtienePath($anio, $mes);
	        
	        $exists = is_file( $url);
	        
	        if(!$exists){
	            
	            $file = fopen($url, "r");
	            fwrite($file, $_string_fila);
	            fwrite($file, PHP_EOL);
	            
	        }else{
	            
	            $file = fopen($url, "a");
	            fwrite($file, $_string_fila);
	            fwrite($file, PHP_EOL);
	        }
	        
	        $error = ""; $error = error_get_last();
	        if(!empty($error)) throw new Exception('error generando el archivo');
	        
	        return 1;
	        
	    } catch (Exception $e) {
	        
	        return 0;
	    }
	    
	    
	}
	
	public function DevuelveConsecutivos(){
	    
	    $Consecutivos = new ConsecutivosModel();
	    
	    $query = "SELECT LPAD(valor_consecutivos::text,espacio_consecutivos,'0') numero_consecutivos, id_consecutivos 
                FROM public.consecutivos WHERE nombre_consecutivos = 'PAGOS'";
	    
	    $rsConsecutivos = $Consecutivos->enviaquery($query);
	    
	    $respuesta = array();
	    
	    $respuesta['pagos'] = array('id'=>$rsConsecutivos[0]->id_consecutivos,'numero'=>$rsConsecutivos[0]->numero_consecutivos);
	    
	    echo json_encode($respuesta);
	}
		
	public function comsumir_mensaje_plus($celular, $nombres, $codigo, $id_mensaje){
	    
	   /*si mensaje es para transferencia el id_mensaje = 22443
	    
	    --$nombres = poner el nombre unidos por guion bajo
	    --$codigo = # cuenta y banco unidos por guion bajo	    
	    --si mensaje es para cheque el id_mensaje = 22451
	    
	    --$nombres = poner el nombre unidos por guion bajo
	    --$codigo = enviar vacio;*/
	    
	    
	    $cadena_recortada ="";
	    $nombres_final="";
	    $mensaje_retorna="";
	    
	    // quito el primero 0
	    $celular_final=ltrim($celular, "0");
	    
	    // relleno espacios en blanco por _
	    $nombres_final= str_replace(' ','_',$nombres);
	    // $nombres_final= str_replace('Ñ','N',$nombres);
	    // genero codigo de verificacion
	    
	    
	    $variables="";
	    $variables.="<pedido>";
	    
	    $variables.="<metodo>SMSEnvio</metodo>";
	    $variables.="<id_cbm>767</id_cbm>";
	    $variables.="<token>yPoJWsNjcThx2o0I</token>";
	    $variables.="<id_transaccion>2002</id_transaccion>";
	    $variables.="<telefono>$celular_final</telefono>";
	    
	    // poner el id_mensaje parametrizado en el sistema
	    
	    $variables.="<id_mensaje>$id_mensaje</id_mensaje>";
	    
	    // poner 1 si va con variables
	    // poner 0 si va sin variables y sin la etiquetas datos
	    $variables.="<dt_variable>1</dt_variable>";
	    $variables.="<datos>";
	    
	    
	    /// el numero de valores va dependiendo del mensaje si usa 1 o 2 variables.
	    $variables.="<valor>$nombres_final</valor>";
	    if (!empty($codigo)){
	        $variables.="<valor>$codigo</valor>";
	    }
	    $variables.="</datos>";
	    $variables.="</pedido>";
	    
	    
	    $SMSPlusUrl = "https://smsplus.net.ec/smsplus/ws/mensajeria.php?xml={$variables}";
	    $ResponseData = file_get_contents($SMSPlusUrl);
	    
	    $xml = simplexml_load_string($ResponseData);
	    
	}
	
	public function transferirParticipe( $_id_cuentas_pagar,$_fecha_transaccion,$_lista_distribucion){
	    
	    $CuentasPagar = new CuentasPagarModel();
	    $respuesta_funcion = array();
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    try {
	        
	        $CuentasPagar->beginTran();
	        
	        $_id_usuarios = $_SESSION['id_usuarios'];
	        
	        //buscar datos a tranferir
	        $col1 = "aa.id_cuentas_pagar, aa.saldo_cuenta_cuentas_pagar, aa.numero_cuentas_pagar, aa.id_tipo_documento, aa.descripcion_cuentas_pagar,
        	        aa.id_forma_pago, aa.total_cuentas_pagar, aa.id_proveedor, bb.id_ccomprobantes, cc.id_creditos, dd.id_participes,
        	        cc.numero_creditos, cc.saldo_actual_creditos, dd.cedula_participes, dd.nombre_participes, dd.apellido_participes, dd.celular_participes,
        	        ee.id_lote, ee.nombre_lote";
	        $tab1 = "tes_cuentas_pagar aa
                    INNER JOIN ccomprobantes bb
                    ON aa.id_ccomprobantes = bb.id_ccomprobantes
                    INNER JOIN core_creditos cc
                    ON cc.id_ccomprobantes = bb.id_ccomprobantes
                    INNER JOIN core_participes dd
                    ON dd.id_participes = cc.id_participes
                    INNER JOIN tes_lote ee
                    ON ee.id_lote = aa.id_lote
                    ";
	        $whe1 = "cc.id_estatus = 1 AND dd.id_estatus=1 AND aa.id_cuentas_pagar = $_id_cuentas_pagar";
	        $id1 = "aa.id_cuentas_pagar";	        
	        
	        $rsConsulta1 = $CuentasPagar->getCondiciones($col1, $tab1, $whe1, $id1);
	        
	        if(!empty($rsConsulta1)){
	            
	            $_numero_credito       = $rsConsulta1[0]->numero_creditos;
	            $_id_creditos          = $rsConsulta1[0]->id_creditos;
                $_id_participes        = $rsConsulta1[0]->id_participes ;
                $_id_proveedores        = $rsConsulta1[0]->id_proveedor ;
	            $_nombre_participes    = $rsConsulta1[0]->nombre_participes ;
	            $_apellidos_participes = $rsConsulta1[0]->apellido_participes ;
	            $_celular_participes = $rsConsulta1[0]->apellido_participes ;
	            $_saldo_cuentas_pagar  = $rsConsulta1[0]->saldo_cuenta_cuentas_pagar ;
	            
	            
	        }else{
	            throw new Exception('Datos no encontrados');
	        }
	        
	        //para traer datos de bancos de participe
	        $col2 = "aa.id_participes,bb.numero_participes_cuentas,cc.id_tipo_cuentas,cc.nombre_tipo_cuentas,
                    dd.nombre_bancos, dd.id_bancos";
	        $tab2 = " core_participes aa
                    LEFT JOIN core_participes_cuentas bb
                    ON bb.id_participes = aa.id_participes
                    AND bb.cuenta_principal = true
                    AND bb.id_estatus=1
                    LEFT JOIN core_tipo_cuentas cc
                    ON cc.id_tipo_cuentas = bb.id_tipo_cuentas
                    LEFT JOIN tes_bancos dd
                    ON dd.id_bancos = bb.id_tipo_cuentas";
	        $whe2 = "aa.id_estatus = 1  AND aa.id_participes = $_id_participes";
	        $id2 = "aa.id_participes";
	        
	        $rsConsulta2 = $CuentasPagar->getCondiciones($col2, $tab2, $whe2, $id2);
	        
	        if(!empty($rsConsulta2)){
	            
	            $_numero_cuenta_banco      = $rsConsulta2[0]->numero_participes_cuentas;
	            $_id_bancos                = ( empty($rsConsulta2[0]->id_bancos ) ) ? 0 : $rsConsulta2[0]->id_bancos ;
	            $_nombre_bancos            = $rsConsulta2[0]->nombre_bancos ;
	            $_id_tipo_cuenta_banco     = ( empty($rsConsulta2[0]->id_tipo_cuentas ) ) ? 0 : $rsConsulta2[0]->id_tipo_cuentas ;	           
	            $_nombre_tipo_cuenta_banco = $rsConsulta2[0]->nombre_tipo_cuentas ;
	            
	        }else{
	            $_numero_cuenta_banco      = "";
	            $_id_bancos                = 0;
	            $_nombre_bancos            = "" ;
	            $_id_tipo_cuenta_banco     = 0 ;
	            $_nombre_tipo_cuenta_banco = "";
	        }
	        	       
	        //traer forma de pago
	        $col3 = "id_forma_pago,nombre_forma_pago";
	        $tab3 = "forma_pago";
	        $whe3 = " nombre_forma_pago = 'TRANSFERENCIA'";
	        $id3 = "id_forma_pago";
	        $rsConsulta3 = $CuentasPagar->getCondiciones($col3, $tab3, $whe3, $id3);
	        
	        $_id_forma_pago = $rsConsulta3[0]->id_forma_pago;
	        	        
	        foreach ($_lista_distribucion as $data){
	            
	            $destino_distribucion = "";
	            if($data->tipo_pago == "debito"){
	                $destino_distribucion = "DEBE";
	            }else{
	                $destino_distribucion = "HABER";
	            }
	            
	            $queryDistribucionPagos = "INSERT INTO tes_distribucion_pagos
    	        (id_cuentas_pagar, id_plan_cuentas, fecha_distribucion_pagos, valor_distibucion_pagos, destino_distribucion_pagos)
    	        VALUES('$_id_cuentas_pagar', '$data->id_plan_cuentas' , '$_fecha_transaccion', $_saldo_cuentas_pagar, '$destino_distribucion')";
	            
	            $ResultDistribucionPagos = $CuentasPagar -> executeNonQuery($queryDistribucionPagos);
	            
	            if(!is_int($ResultDistribucionPagos) || $ResultDistribucionPagos <= 0 ){
	                throw new Exception("Error distribucion pagos");
	                break;
	                
	            }
	            
	        }
	        
	        //para ingresar pago
	        $funcionPago = "ins_tes_pagos";
	        $parametrosPago = "'$_id_cuentas_pagar',
            	        '$_id_creditos',
            	        '$_id_proveedores',
            	        null,
            	        '$_id_forma_pago',
            	        '$_fecha_transaccion',
            	        'TRANSFERENCIA',
            	        '$_id_bancos' ,
            	        '$_nombre_bancos',
            	        '$_numero_cuenta_banco',
                        '$_nombre_tipo_cuenta_banco',
            	        '$_id_tipo_cuenta_banco'";
	        
	        $consultaPago = $CuentasPagar->getconsultaPG($funcionPago, $parametrosPago);
	        $ResulatadoPago = $CuentasPagar->llamarconsultaPG($consultaPago);
	        
	        $error = "";
	        $error = pg_last_error();
	        if(!empty($error)){
	            throw new Exception("Error ingresando pagos");
	        }
	        
	        $_id_pagos = (int)$ResulatadoPago[0];
	        
	        //Datos para Comprobante
	        $_concepto_comprobante = " TRANSACCION TRANSFERENCIA A .".$_nombre_participes." ".$_apellidos_participes.". DEL CREDITO $_numero_credito ";
	        $valor_letras_pago = $CuentasPagar->numtoletras($_saldo_cuentas_pagar);
	        $funcionComprobante = "tes_agrega_comprobante_pago_transferencia";
	        $parametrosComprobante = "'$_id_usuarios',
            	        '$_id_bancos',
            	        '$_id_cuentas_pagar',
            	        '$_id_proveedores',
            	        '$_id_forma_pago',
                        '$_saldo_cuentas_pagar',
                        '$valor_letras_pago',
            	        '$_fecha_transaccion',
            	        'TRANSFERENCIA',
            	        '$_numero_cuenta_banco' ,
            	        null,
            	        'PAGO CREDITO $_numero_credito ',
            	        'PAGO',
            	        null,
                        '$_concepto_comprobante'";
	        
	        $consultaComprobante = $CuentasPagar->getconsultaPG($funcionComprobante, $parametrosComprobante);
	        $ResulatadoComprobante = $CuentasPagar->llamarconsultaPG($consultaComprobante);
	        
	        $error = "";
	        $error = pg_last_error();
	        if( !empty($error) ){
	            throw new Exception('Error ingresado comprobante');
	        }
	        
	        $_id_comprobante = $ResulatadoComprobante[0];
	        
	        $columnaPago = "id_ccomprobantes = $_id_comprobante ";
	        $tablasPago = "tes_pagos";
	        $wherePago = "id_pagos = $_id_pagos";
	        $Update_tes_pago = $CuentasPagar -> ActualizarBy($columnaPago, $tablasPago, $wherePago);
	        
	        /*actualizacion de Cuenta por pagar*/
	        //buscar estado de cuentas por pagar
	        $queryEstado = "SELECT id_estado FROM estado WHERE tabla_estado='tes_cuentas_pagar' AND nombre_estado = 'APLICADO'";
	        $rsEstado = $CuentasPagar -> enviaquery($queryEstado);
	        $_id_estado = $rsEstado[0]->id_estado;
	        $rsActualizacionCuentaPagar = $CuentasPagar->ActualizarBy("id_estado = $_id_estado", "tes_cuentas_pagar", "id_cuentas_pagar = $_id_cuentas_pagar");
	        
	        /*para enviara a celular*/
	        $_celular_mensaje = "0987968467"; //para rpoduccion $_celular_participes
	        $_nombres_mensajes = $_nombre_participes." ".$_apellidos_participes;
	        $_num_cuenta = "XXXXXX".substr($_numero_cuenta_banco, 6);
	        $_codigo_mensajes = str_replace(' ','_',$_num_cuenta.'-'.$_nombre_bancos);
	        $_id_mensaje_mensajes = "22443";
	        $this->comsumir_mensaje_plus($_celular_mensaje, $_nombres_mensajes, $_codigo_mensajes, $_id_mensaje_mensajes);
	        
	        $CuentasPagar->endTran("COMMIT");
	        $respuesta_funcion['respuesta']=true;	        
	        return $respuesta_funcion;
	        
	    } catch (Exception $e) {
	        $CuentasPagar->endTran();
	        $respuesta_funcion['respuesta'] = false;
	        $respuesta_funcion['mensaje'] = $e->getMessage();
	        return $respuesta_funcion;
	    }
	    
	}
	
	public function transferirProveedor($_id_cuentas_pagar,$_fecha_transaccion,$_lista_distribucion){
	    
	    $CuentasPagar = new CuentasPagarModel();
	    
	     $respuesta_funcion = array();
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    try {
	        
	        $CuentasPagar->beginTran();
	        
	        //variables de session
	        $_id_usuario = $_SESSION['id_usuarios'];
	        
	        //buscar datos a tranferir
	        /*traer datos de la cuenta por pagar*/
	        $col1 = "aa.total_cuentas_pagar, aa.saldo_cuenta_cuentas_pagar, aa.origen_cuentas_pagar, aa.descripcion_cuentas_pagar, aa.id_estado, aa.id_forma_pago,
		          bb.concepto_ccomprobantes, cc.nombre_lote, cc.id_lote, cc.descripcion_lote, dd.nombre_proveedores, dd.id_bancos,
                  dd.id_tipo_cuentas, ee.nombre_tipo_proveedores,dd.id_proveedores, dd.numero_cuenta_proveedores,dd.identificacion_proveedores";
	        $tab1 = "tes_cuentas_pagar aa
        		INNER JOIN ccomprobantes bb
        		ON aa.id_ccomprobantes = bb.id_ccomprobantes
        		INNER JOIN tes_lote cc
        		ON cc.id_lote = aa.id_lote
                INNER JOIN proveedores dd
                ON aa.id_proveedor = dd.id_proveedores
                INNER JOIN tes_tipo_proveedores ee
    		    ON dd.id_tipo_proveedores = ee.id_tipo_proveedores";
	        $whe1 = " aa.id_cuentas_pagar = $_id_cuentas_pagar ";
	        $id1 = "aa.id_cuentas_pagar";	        
	        $rsConsulta1 = $CuentasPagar->getCondiciones($col1, $tab1, $whe1, $id1);
	        
	        $id_proveedores                = $rsConsulta1[0]->id_proveedores;
	        $id_tipo_cuenta                = $rsConsulta1[0]->id_tipo_cuentas;
	        $numero_cuenta_proveedores     = $rsConsulta1[0]->numero_cuenta_proveedores;
	        $nombre_proveedores            = $rsConsulta1[0]->nombre_proveedores;
	        $total_cuentas_pagar            = $rsConsulta1[0]->total_cuentas_pagar;
	        $saldo_cuentas_pagar            = $rsConsulta1[0]->saldo_cuenta_cuentas_pagar;
	        
	        //traer forma de pago 
	        $col2 = "id_forma_pago,nombre_forma_pago";
	        $tab2 = "forma_pago";
	        $whe2 = " nombre_forma_pago = 'TRANSFERENCIA'";
	        $id2 = "id_forma_pago";	       
	        $rsConsulta2 = $CuentasPagar->getCondiciones($col2, $tab2, $whe2, $id2);
	        
	        $_id_forma_pago = $rsConsulta2[0]->id_forma_pago;
	        
	        //consulta banco 
	        $col3 = "aa.id_bancos, aa.nombre_bancos";
	        $tab3 = "tes_bancos aa LEFT JOIN proveedores bb ON aa.id_bancos = bb.id_bancos";
	        $whe3 = " bb.id_proveedores = $id_proveedores ";
	        $id3 = "aa.id_bancos";
	        $rsConsulta3 = $CuentasPagar->getCondiciones($col3, $tab3, $whe3, $id3);
	        
	        $_id_bancos = is_null($rsConsulta3[0]->id_bancos) ? 0 : $rsConsulta3[0]->id_bancos ;
	        $_nombre_cuenta_banco = is_null($rsConsulta3[0]->nombre_bancos) ? "" : $rsConsulta3[0]->nombre_bancos ;
	        
	        foreach ($_lista_distribucion as $data){
	            
	            $destino_distribucion = "";
	            if($data->tipo_pago == "debito"){
	                $destino_distribucion = "DEBE";
	            }else{
	                $destino_distribucion = "HABER";
	            }
	            
	            $queryDistribucionPagos = "INSERT INTO tes_distribucion_pagos
    	        (id_cuentas_pagar, id_plan_cuentas, fecha_distribucion_pagos, valor_distibucion_pagos, destino_distribucion_pagos)
    	        VALUES('$_id_cuentas_pagar', '$data->id_plan_cuentas' , '$_fecha_transaccion', $saldo_cuentas_pagar, '$destino_distribucion')";
	            
	            $ResultDistribucionPagos = $CuentasPagar -> executeNonQuery($queryDistribucionPagos);
	            
	            if(!is_int($ResultDistribucionPagos) || $ResultDistribucionPagos <= 0 ){
	                throw new Exception("Error distribucion pagos");
	                break;
	                
	            }
	            
	        }
	        
	        
	        //para ingresar pago
	        $funcionPago = "ins_tes_pagos";
	        $parametrosPago = "'$_id_cuentas_pagar',
            	        null,
            	        '$id_proveedores',
            	        null,
            	        '$_id_forma_pago',
            	        '$_fecha_transaccion',
            	        'TRANSFERENCIA',
            	        '$_id_bancos' ,
            	        '$_nombre_cuenta_banco',
            	        '$numero_cuenta_proveedores',
                        '',
            	        '$id_tipo_cuenta'";
	        
	        $consultaPago = $CuentasPagar->getconsultaPG($funcionPago, $parametrosPago);
	        $ResulatadoPago = $CuentasPagar->llamarconsultaPG($consultaPago);
	        
	        $error = "";
	        $error = pg_last_error();
	        if(!empty($error)){
	            throw new Exception("Error ingresando Pago");
	        }
	        
	        $_id_pagos = (int)$ResulatadoPago[0];
	        
	        //Datos para Comprobante
	        $_concepto_comprobante = " TRANSACCION TRANSFERENCIA A PROVEEDOR .".$nombre_proveedores;
	        $valor_letras_pago = $CuentasPagar->numtoletras($saldo_cuentas_pagar);
	        $funcionComprobante = "tes_agrega_comprobante_pago_transferencia";
	        $parametrosComprobante = "'$_id_usuario',
            	        '$_id_bancos',
            	        '$_id_cuentas_pagar',
            	        '$id_proveedores',
            	        '$_id_forma_pago',
                        '$saldo_cuentas_pagar',
                        '$valor_letras_pago',
            	        '$_fecha_transaccion',
            	        'TRANSFERENCIA',
            	        '$numero_cuenta_proveedores' ,
            	        null,
            	        'PAGO PROVEEDOR',
            	        'PAGO',
            	        null,
                        '$_concepto_comprobante'";
	        
	        $consultaComprobante = $CuentasPagar->getconsultaPG($funcionComprobante, $parametrosComprobante);
	        $ResulatadoComprobante = $CuentasPagar->llamarconsultaPG($consultaComprobante);
	        
	        $error = "";
	        $error = pg_last_error();
	        if( !empty($error) ){
	            throw new Exception('Error ingresando comprobante');
	        }
	        
	        $_id_comprobante = $ResulatadoComprobante[0];
	        
	        $columnaPago = "id_ccomprobantes = $_id_comprobante ";
	        $tablasPago = "tes_pagos";
	        $wherePago = "id_pagos = $_id_pagos";
	        $Update_tes_pago = $CuentasPagar -> ActualizarBy($columnaPago, $tablasPago, $wherePago);
	        
	        /*actualizacion de Cuenta por pagar*/
	        //buscar estado de cuentas por pagar
	        $queryEstado = "SELECT id_estado FROM estado WHERE tabla_estado='tes_cuentas_pagar' AND nombre_estado = 'APLICADO'";
	        $rsEstado = $CuentasPagar -> enviaquery($queryEstado);
	        $_id_estado = $rsEstado[0]->id_estado;
	        $rsActualizacionCuentaPagar = $CuentasPagar->ActualizarBy("id_estado = $_id_estado", "tes_cuentas_pagar", "id_cuentas_pagar = $_id_cuentas_pagar");
	        
	        $CuentasPagar->endTran("COMMIT");
	        
	        $respuesta_funcion['respuesta']=true;	        
	        
	        return $respuesta_funcion;
	        
	    } catch (Exception $e) {
	        
	        $CuentasPagar->endTran();
	        $respuesta_funcion['respuesta'] = false;
	        $respuesta_funcion['mensaje'] = $e->getMessage();
	        return $respuesta_funcion;
	    }
	    
	}
	
	/**
	 * funcion que devuele el nombre de archivo 'cash_pago' con su respectiva ruta
	 * @param int $anioArchivo
	 * @param int $mesArchivo
	 */
	private function obtienePath($anioArchivo,$mesArchivo){
	    
	    $nombreArchivo     = "CASH_PAGOS_".$mesArchivo.$anioArchivo.".txt";;
	    $carpeta_base      = __DIR__.'\\..\\view\\tesoreria\\documentos\\transferencias\\';
	    $_carpeta_buscar   = $carpeta_base.$anioArchivo;
	    $file_buscar       = "";
	    if( file_exists($_carpeta_buscar)){
	        
	        $_carpeta_buscar   = $carpeta_base.$anioArchivo."\\".$mesArchivo;
	        if( file_exists($_carpeta_buscar)){
	            
	            $file_buscar = $_carpeta_buscar."\\".$nombreArchivo;
	             
	           
	        }else{
	          
	            mkdir($_carpeta_buscar, 0777, true);
	            $file_buscar = $_carpeta_buscar."\\".$nombreArchivo;	            
	                       
	        }
	        
	    }else{
	        
	        mkdir($_carpeta_buscar."\\".$mesArchivo, 0777, true);
	        $file_buscar = $_carpeta_buscar."\\".$mesArchivo."\\".$nombreArchivo;
	    }
	   	   
	    return $file_buscar;
	}
	
	public function sri(){
	    $this->view_tesoreria("testvalfirmarenviar", array());
	}
	
	/***
	 * @param none
	 * @desc funcion para traer la cuenta contable del banco seleccionado
	 */
	public function getContablePago(){
	    
	    $pagos = new PagosModel();
	    
	    $_id_bancos = $_POST['id_bancos'];
	    $resp = null;
	    
	    $col1 = " aa.id_bancos, bb.id_parametrizacion_cuentas, bb.id_plan_cuentas_haber, cc.id_plan_cuentas, cc.nombre_plan_cuentas, cc.codigo_plan_cuentas ";
	    $tab1 = " tes_bancos aa
	    INNER JOIN core_parametrizacion_cuentas bb ON bb.id_principal_parametrizacion_cuentas = aa.id_bancos
	    INNER JOIN plan_cuentas cc ON cc.id_plan_cuentas = bb.id_plan_cuentas_haber";
	    $whe1 = "1 = 1
	    AND bb.modulo_parametrizacion_cuentas = 'PAGO'
	    AND bb.operacion_parametrizacion_cuentas = 'TRANSFERENCIA'
	    AND aa.id_bancos = $_id_bancos";
	    $id1  = " aa.id_bancos";

	    try {
	        
	        $rsConsulta1 = $pagos->getCondiciones($col1, $tab1, $whe1, $id1);
	        
	        if( !empty($rsConsulta1) ){
	            
	            $resp['icon'] = "success";
	            $resp['mensaje'] = "";//buscar guardar buffer y guaradr en variable
	            $resp['estatus'] = "OK";
	            $resp['data'] = $rsConsulta1;
	        }
	        
	    } catch (Exception $e) {
	        
	        $buffer =  error_get_last();
	        $resp['icon'] = isset($resp['icon']) ? $resp['icon'] : "error";
	        $resp['mensaje'] = $e->getMessage();
	        $resp['msgServer'] = $buffer; //buscar guardar buffer y guaradr en variable
	        $resp['estatus'] = "ERROR";
	    }
	    
	    if (ob_get_contents()) ob_end_clean();	    
	    
	    echo json_encode($resp);
	    
	}
	
	public function getContablePagoProveedor(){
	    
	    $pagos = new PagosModel();
	    $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	    $resp = null;
	    
	    $query = " SELECT cc.id_plan_cuentas, cc.nombre_plan_cuentas, cc.codigo_plan_cuentas FROM tes_cuentas_pagar p
    	    INNER JOIN tes_distribucion_cuentas_pagar c on p.id_lote = c.id_lote
    	    INNER JOIN plan_cuentas cc ON cc.id_plan_cuentas = c.id_plan_cuentas
    	    WHERE p.id_cuentas_pagar = $_id_cuentas_pagar AND c.tipo_distribucion_cuentas_pagar = 'PAGO' ";
	    
	    try {
	        
	        $rsConsulta1 = $pagos->enviaquery( $query );
	        
	        if( !empty($rsConsulta1) )
	        {	            
	            $resp['icon'] = "success";
	            $resp['mensaje'] = "";//buscar guardar buffer y guaradr en variable
	            $resp['estatus'] = "OK";
	            $resp['data'] = $rsConsulta1;
	        }
	        
	    } catch (Exception $e) {
	        
	        $buffer =  error_get_last();
	        $resp['icon'] = isset($resp['icon']) ? $resp['icon'] : "error";
	        $resp['mensaje'] = $e->getMessage();
	        $resp['msgServer'] = $buffer; //buscar guardar buffer y guaradr en variable
	        $resp['estatus'] = "ERROR";
	    }
	    
	    if (ob_get_contents()) ob_end_clean();
	    
	    echo json_encode($resp);
	    
	}
	
	
	/******************************************************************** METODOS PARA LA GENERACION DE ARCHIVOS PAGO ***********************************/
	
	function auxArchivoProveedores(array $datos){
	    $pagos = new PagosModel();
	    $aux = null;
	    return $aux;
	}
	
	/************************************************************ TERMINA METODOS PARA LA GENERACION DE ARCHIVOS PAGO ***********************************/
	
	/*********************************************************** FUNCIONES PARA PROBAR METODOS ************************************************************/
	function prueba(){
	  $var1 = "28,930.00";
	  
	  echo str_replace(".", "", str_replace(",", "", $var1) ); echo "<br>";
	  
	  echo str_replace(".", "", $var1); echo "<br>";
	  
	  echo str_replace(",", "", $var1); echo "<br>";
	  
	  echo trim($var1,".");  echo "<br>";
	  
	  echo trim( trim($var1,'.'),','); echo "<br>";
	  
	  echo number_format((double)$var1,2,'','');
	}
	/*********************************************************** FUNCIONES PARA PROBAR METODOS ************************************************************/




	public function CargaBancosGeneral(){
	    
	    $pagos = new PagosModel();
	    $resp  = null;
	    
	    $col1  = " id_bancos, nombre_bancos";
	    $tab1  = " tes_bancos ";
	    $whe1  = " (local_bancos = 'TRUE' or id_bancos = 1522) ";
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

	
	
	public function CargaTipoCuentaGeneral(){
	    
	    $pagos = new PagosModel();
	    $resp  = null;
	    
	    $col1  = " id_tipo_cuentas, nombre_tipo_cuentas";
	    $tab1  = " core_tipo_cuentas ";
	    $whe1  = " 1 = 1";
	    $id1   = " nombre_tipo_cuentas ";
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
	
	public function EditarCuentasProveedores()
	{
	   $pagos = new PagosModel();
	   
	   $id_bancos = $_POST['id_bancos'];
	   $id_tipo_cuentas    = $_POST['id_tipo_cuentas'];
	   $id_proveedores = $_POST['id_proveedores'];
	   $numero_cuentas = $_POST['numero_cuenta_bancaria'];
	   
	   $val    = " id_bancos=$id_bancos, id_tipo_cuentas=$id_tipo_cuentas, numero_cuenta_proveedores = '$numero_cuentas'";
	   $tab    = " proveedores ";
	   $whe    = " id_proveedores = $id_proveedores";

	   $resultado = $pagos->ActualizarBy($val, $tab, $whe);
	   
	   if( !empty( pg_last_error() ) ){
	       throw new Exception("Actualizacion No realizada");
	   }
	   
	   $resp = array();
	   $resp['estatus']   = "OK";
	   $resp['mensaje']   = " Filas Actualizadas (".$resultado.")";
	   
	   echo json_encode($resp);
	}



















}
?>