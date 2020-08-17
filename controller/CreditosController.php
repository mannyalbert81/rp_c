
<?php

class CreditosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
		
	}
	
	public function ActivaCreditoprueba($paramIdCredito){
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    $Credito = new CreditosModel();
	    $Consecutivos = new ConsecutivosModel();
	    $TipoComprobantes = new TipoComprobantesModel();
	    
	    $id_creditos = $paramIdCredito;
	    
	    if(is_null($id_creditos)){
	        echo '<message> parametros no recibidos <message>';
	        return;
	    }
	    
	    try {
	        
	        /*variables para validar los creditos*/ 
	        $_es_nuevo_credito = true;
	        
	        //creacion de lote
	        $nombreLote = "CxP-Creditos";
	        $descripcionLote = "GENERACION CREDITO";
	        $id_frecuencia = 1;
	        $id_usuarios = $_SESSION['id_usuarios'];
	        $usuario_usuarios = $_SESSION['usuario_usuarios'];
	        $funcionLote = "tes_genera_lote";
	        $paramLote = "'$nombreLote','$descripcionLote','$id_frecuencia','$id_usuarios'";
	        $consultaLote = $Credito->getconsultaPG($funcionLote, $paramLote);
	        $ResultLote = $Credito->llamarconsultaPG($consultaLote);
	        $_id_lote = 0; // es cero para que la funcion reconosca como un ingreso de nuevo lote
	        $error = "";
	        $error = pg_last_error();
	        if (!empty($error) || (int)$ResultLote[0] <= 0){
	            throw new Exception('error ingresando lote');
	        }
	        
	        $_id_lote = (int)$ResultLote[0];
	        
	        /*insertado de cuentas por pagar*/
	        //busca consecutivo
	        $ResultConsecutivo= $Consecutivos->getConsecutivoByNombre("CxP");
	        $_id_consecutivos = $ResultConsecutivo[0]->id_consecutivos;
	        
	        //busca tipo documento
	        $queryTipoDoc = "SELECT id_tipo_documento, nombre_tipo_documento FROM tes_tipo_documento
                WHERE abreviacion_tipo_documento = 'MIS' LIMIT 1";
	        $ResultTipoDoc= $Credito->enviaquery($queryTipoDoc);
	        $_id_tipo_documento = $ResultTipoDoc[0]->id_tipo_documento;
	        
	        //busca tipo moneda
	        $queryMoneda = "SELECT id_moneda, nombre_moneda FROM tes_moneda
                WHERE nombre_moneda = 'DOLAR' LIMIT 1";
	        $ResultMoneda= $Credito->enviaquery($queryMoneda);
	        $_id_moneda = $ResultMoneda[0]->id_moneda;
	        
	        //datos de participes
	        $funcionProveedor = "ins_proveedores_participes";
	        $parametrosProveedor = " '$id_creditos' ";
	        $consultaProveedor = $Credito->getconsultaPG($funcionProveedor, $parametrosProveedor);
	        $ResultadoProveedor= $Credito->llamarconsultaPG($consultaProveedor);
	        $error = "";
	        $error = pg_last_error();
	        if (!empty($error) ){
	            throw new Exception('error proveedores');
	        }
	        $_id_proveedor = 0;
	        if( (int)$ResultadoProveedor[0] > 0 ){
	            $_id_proveedor = $ResultadoProveedor[0];
	        }else{
	            throw new Exception("Error en proveedor-participe");
	        }
	        
	        //para datos de banco
	        $_id_bancos = 0 ; //mas adelante se modifica con la solicitud del participe
	        
	        //datos Cuenta por pagar
	        $_descripcion_cuentas_pagar = ""; //se llena mas adelante
	        $_fecha_cuentas_pagar = date('Y-m-d');
	        $_condiciones_pago_cuentas_pagar = "";
	        $_num_documento_cuentas_pagar = "";
	        $_num_ord_compra = "";
	        $_metodo_envio_cuentas_pagar = "";
	        $_compra_cuentas_pagar = ""; //valor de credito
	        $_desc_comercial = 0.00;
	        $_flete_cuentas_pagar = 0.00;
	        $_miscelaneos_cuentas_pagar = 0.00;
	        $_impuesto_cuentas_pagar = 0.00;
	        $_total_cuentas_pagar = 0.00;
	        $_monto1099_cuentas_pagar = 0.00;
	        $_efectivo_cuentas_pagar = 0.00;
	        $_cheque_cuentas_pagar = 0.00;
	        $_tarjeta_credito_cuentas_pagar = 0.00;
	        $_condonaciones_cuentas_pagar = 0.00;
	        $_saldo_cuentas_pagar = 0.00;
	        $_id_cuentas_pagar = 0;
	        
	        /*valores para cuenta por pagar*/
	        //busca datos de credito
	        $queryCredito = "SELECT cc.id_creditos, cc.monto_otorgado_creditos, cc.monto_neto_entregado_creditos,
                      cc.saldo_actual_creditos, cc.numero_creditos,
		              ctc.id_tipo_creditos, ctc.nombre_tipo_creditos, ctc.codigo_tipo_creditos
                    FROM core_creditos cc
                    INNER JOIN core_tipo_creditos ctc
                    ON cc.id_tipo_creditos = ctc.id_tipo_creditos
                    WHERE cc.id_creditos = $id_creditos ";
	        $ResultCredito= $Credito->enviaquery($queryCredito);
	        
	        $codigo_credito = "";
	        $monto_credito = 0;
	        $monto_entregado_credito = 0;
	        $id_tipo_credito = 0;
	        $numero_credito = !empty($ResultCredito) ? $ResultCredito[0]->numero_creditos : 0 ;
	        
	        $_descripcion_cuentas_pagar = "Cuenta x Pagar Credito $numero_credito ";
	        
	        foreach ($ResultCredito as $res){
	            $codigo_credito=$res->codigo_tipo_creditos;
	            $monto_credito = $res->monto_otorgado_creditos;
	            $monto_entregado_credito = $res->monto_neto_entregado_creditos;
	            $id_tipo_credito = $res->id_tipo_creditos;
	            
	        }
	        
	        //valores de cuentas por pagar
	        $_compra_cuentas_pagar = $monto_credito;
	        $_total_cuentas_pagar = $_compra_cuentas_pagar;
	        $_saldo_cuentas_pagar = $_compra_cuentas_pagar - $_impuesto_cuentas_pagar;
	        
	        /*validacion de creditos*/
	        if($_es_nuevo_credito){
	            
	            
	            //para insertado normal
	            $queryParametrizacion = "SELECT * FROM core_parametrizacion_cuentas
                                    WHERE id_principal_parametrizacion_cuentas = $id_tipo_credito";
	            $ResultParametrizacion = $Credito -> enviaquery($queryParametrizacion);
	            
	            //buscar de tabla parametrizacion
	            $iorden=0;
	            foreach ($ResultParametrizacion as $res){
	                $iorden = 1;
	                $queryDistribucion = "INSERT INTO tes_distribucion_cuentas_pagar
                                        (id_lote,
                                        id_plan_cuentas,
                                        tipo_distribucion_cuentas_pagar,
                                        debito_distribucion_cuentas_pagar,
                                        credito_distribucion_cuentas_pagar,
                                         ord_distribucion_cuentas_pagar,
                                        referencia_distribucion_cuentas_pagar)
                        VALUES ( '$_id_lote','$res->id_plan_cuentas_debe','COMPRA','0.00','$monto_credito','$iorden','$_descripcion_cuentas_pagar')";
	                
	                $iorden = $iorden + 2;
	                $ResultDistribucion = $Credito -> executeNonQuery($queryDistribucion);
	                $error = "";
	                $error ="";
	                $error = pg_last_error();
	                if(!empty($error) || $ResultDistribucion <= 0 )
	                    throw new Exception('error distribucion cuentas pagar debe   '.$error);
	            }
	            
	            foreach ($ResultParametrizacion as $res){
	                $iorden = 2;
	                $queryDistribucion = "INSERT INTO tes_distribucion_cuentas_pagar
                        (id_lote,id_plan_cuentas,tipo_distribucion_cuentas_pagar,debito_distribucion_cuentas_pagar,credito_distribucion_cuentas_pagar,ord_distribucion_cuentas_pagar,referencia_distribucion_cuentas_pagar)
                        VALUES ( '$_id_lote','$res->id_plan_cuentas_haber','PAGOS','$monto_credito','0.00','$iorden','$_descripcion_cuentas_pagar')";
	                $iorden = $iorden + 2;
	                $ResultDistribucion = $Credito -> executeNonQuery($queryDistribucion);
	                $error = "";
	                $error = pg_last_error();
	                if(!empty($error) || $ResultDistribucion <= 0 )
	                    throw new Exception('error distribucion cuentas pagar haber');
	            }
	            
	            
	            
	            
	            switch ($codigo_credito){
	                case "EME":
	                    $_descripcion_cuentas_pagar .= " Tipo EMERGENTE";
	                    
	                    break;
	                case "ORD":
	                    
	                    $_descripcion_cuentas_pagar .= "Tipo ORDINARIO";
	                    break;
	            }
	            
	            
	        }
	        
	        //DIFERENCIAR MONTO SOLICITADO MONTO ENTREGADO
	        if($monto_credito != $monto_entregado_credito){
	            //para monto en refinaciacion y otras
	        }else{}
	        
	        
	        $_origen_cuentas_pagar = "CREDITOS";
	        $_id_usuarios = $id_usuarios;
	        //datos de cuentas x pagar
	        $funcionCuentasPagar = "tes_ins_cuentas_pagar";
	        $paramCuentasPagar = "'$_id_lote', '$_id_consecutivos', '$_id_tipo_documento', '$_id_proveedor', '$_id_bancos',
            '$_id_moneda', '$_descripcion_cuentas_pagar', '$_fecha_cuentas_pagar', '$_condiciones_pago_cuentas_pagar', '$_num_documento_cuentas_pagar',
            '$_num_ord_compra','$_metodo_envio_cuentas_pagar', '$_compra_cuentas_pagar', '$_desc_comercial','$_flete_cuentas_pagar',
            '$_miscelaneos_cuentas_pagar','$_impuesto_cuentas_pagar', '$_total_cuentas_pagar','$_monto1099_cuentas_pagar','$_efectivo_cuentas_pagar',
            '$_cheque_cuentas_pagar', '$_tarjeta_credito_cuentas_pagar', '$_condonaciones_cuentas_pagar', '$_saldo_cuentas_pagar', '$_origen_cuentas_pagar', '$_id_cuentas_pagar'";
	        
	        
	        $consultaCuentasPagar = $Credito->getconsultaPG($funcionCuentasPagar, $paramCuentasPagar);
	        $ResultCuentaPagar = $Credito -> llamarconsultaPG($consultaCuentasPagar);
	        
	        $error = "";
	        $error = pg_last_error();
	        if(!empty($error) || $ResultCuentaPagar[0] <= 0 ){ throw new Exception('error inserccion cuentas pagar');}
	        
	        // secuencial de cuenta por pagar
	        $_id_cuentas_pagar = $ResultCuentaPagar[0];
	        
	        //para actualizar la forma de pago y el banco en cuentas por pagar
	        //--buscar
	        $columnas1 = "aa.id_creditos, bb.id_forma_pago, bb.nombre_forma_pago,cc.id_bancos";
	        $tabla1 = "core_creditos aa
                    INNER JOIN forma_pago bb
                    ON aa.id_forma_pago = bb.id_forma_pago
                    INNER JOIN core_participes_cuentas cc
                    ON cc.id_participes = aa.id_participes
                    AND cc.cuenta_principal = true";
	        $where1 = "aa.id_estatus = 1 AND aa.id_creditos = $id_creditos";
	        $id1 = "aa.id_creditos";
	        $rsFormaPago = $Credito->getCondiciones($columnas1, $tabla1, $where1, $id1);
	        $_id_forma_pago = $rsFormaPago[0]->id_forma_pago;
	        $_id_bancos = $rsFormaPago[0]->id_bancos;
	        
	        $columnaPago = "id_forma_pago = $_id_forma_pago , id_banco = $_id_bancos ";
	        $tablasPago = "tes_cuentas_pagar";
	        $wherePago = "id_cuentas_pagar = $_id_cuentas_pagar";
	        $Credito -> ActualizarBy($columnaPago, $tablasPago, $wherePago);
	        
	        //buscar tipo de comprobante
	        $rsTipoComprobantes = $TipoComprobantes->getTipoComprobanteByNombre("CONTABLE");
	        $_id_tipo_comprobantes = (!empty($rsTipoComprobantes)) ? $rsTipoComprobantes[0]->id_tipo_comprobantes : null;
	        
	        $funcionComprobante     = "core_ins_ccomprobantes_activacion_credito";
	        $valor_letras           = $Credito->numtoletras($_total_cuentas_pagar);
	        $_concepto_comprobantes = "Consecion Creditos Sol:$numero_credito";
	        //para parametros hay valores seteados
	        $parametrosComprobante = "
                1,
                $_id_tipo_comprobantes,
                '',
                '',
                '',
                '$_total_cuentas_pagar',
                '$_concepto_comprobantes',
                '$_id_usuarios',
                '$valor_letras',
                '$_fecha_cuentas_pagar',
                '$_id_forma_pago',
                null,
                null,
                null,
                null,
                '$_id_proveedor',
                'cxp',
                '$usuario_usuarios',
                'credito',
                '$_id_lote'
                ";
                
                $consultaComprobante = $Credito ->getconsultaPG($funcionComprobante, $parametrosComprobante);
                $resultadComprobantes = $Credito->llamarconsultaPG($consultaComprobante);
                
                $error = "";
                $error = pg_last_error();
                if(!empty($error) || $resultadComprobantes[0] <= 0 ){   throw new Exception('error insercion comprobante contable '); }
                
                // secuencial de comprobante
                $_id_ccomprobantes = $resultadComprobantes[0];
                
                //se actualiza la cuenta por pagar con la relacion al comprobante
                $columnaCxP = "id_ccomprobantes = $_id_ccomprobantes ";
                $tablasCxP = "tes_cuentas_pagar";
                $whereCxP = "id_cuentas_pagar = $_id_cuentas_pagar";
                $Credito -> ActualizarBy($columnaCxP, $tablasCxP, $whereCxP);
                
                //se actualiza el credito con su comprobante
                $columnaCre = "id_ccomprobantes = $_id_ccomprobantes ";
                $tablasCre = "core_creditos";
                $whereCre = "id_creditos = $id_creditos";
                $Credito -> ActualizarBy($columnaCre, $tablasCre, $whereCre);
                
                //$Credito->endTran('COMMIT');
                return 'OK';
                
	    } catch (Exception $e) {
	        
	        //$Credito->endTran();
	        return $e->getMessage();
	    }
	}
	
	public function ActivarCredito($paramIdCredito=0){
	    
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    if( (int)$paramIdCredito <= 0 || is_null($paramIdCredito) ){
	        return "Datos de credito no recibido";
	    }
	    
	    $Credito       = new CreditosModel();	    
	    $id_creditos = $paramIdCredito;
	    $_es_renovacion    = false;
	    	    	    
	    $columasCreditoNuevo   = " id_creditos_nuevo";
	    $tablasCreditoNuevo    = " public.core_creditos_renovaciones";
	    $whereCreditoNuevo     = " id_creditos_nuevo = $id_creditos";
	    $idCreditoNuevo        = " id_creditos_nuevo ";
	    
	    $rsCreditoNuevo    = $Credito->getCondiciones($columasCreditoNuevo, $tablasCreditoNuevo, $whereCreditoNuevo, $idCreditoNuevo);
	    
	    if(empty($rsCreditoNuevo)){
	        /* no hay renovacion*/
	        $_es_renovacion = false;
	    }else{
	        /* hay renovacion */
	        $_es_renovacion = true;
	    }
	    
	    try {
	        
	        //$Credito->beginTran();
	        $_id_lote  = 0;
	        
	        /* ingresar participe como proveedor */	        
	        $funcionProveedor = "ins_proveedores_participes";
	        $parametrosProveedor = " '$id_creditos' ";
	        $consultaProveedor = $Credito->getconsultaPG($funcionProveedor, $parametrosProveedor);
	        $ResultadoProveedor= $Credito->llamarconsultaPG($consultaProveedor);
	        $_id_proveedor = 0;
	        $error = "";
	        $error = pg_last_error();
	        if (!empty($error) ){
	            throw new Exception('ingresar participe - proveedor');
	        }else{
	            if( (int)$ResultadoProveedor[0] > 0 ){
	                $_id_proveedor = $ResultadoProveedor[0];
	            }else{
	                throw new Exception(" Participe-credito no Encontrado");
	            }
	            
	        }	        
	        
	        
	        /* generar metodos locales */
	        $_id_lote  = $this->generaLote();	        
	        
	        /* comienza validacion de creditos para diferentes casos */
	        $Columnas1 = " bb.id_tipo_creditos, bb.nombre_tipo_creditos, bb.codigo_tipo_creditos";
	        $Tablas1   = " core_creditos aa
            	        INNER JOIN core_tipo_creditos bb
            	        ON bb.id_tipo_creditos = aa.id_tipo_creditos";
	        $Where1    = "id_creditos = $id_creditos ";
	        $Id1       = "aa.id_creditos";
	        
	        $rsConsulta1   = $Credito->getCondiciones($Columnas1, $Tablas1, $Where1, $Id1);
	        
	        $_codigo_tipo_credito  = $rsConsulta1[0]->codigo_tipo_creditos;
	             
	        
	        switch ($_codigo_tipo_credito){
	            case 'EME':
	                if($_es_renovacion){
	                    $this->EmergenteRenovacion($id_creditos, $_id_lote, $_id_proveedor);
	                }else {	                    
	                    $this->EmergenteNuevo($id_creditos, $_id_lote, $_id_proveedor);
	                }
	            break;
	            case 'ORD':
	                if($_es_renovacion){	                    
	                    $this->OrdinarioRenovacion($id_creditos,$_id_lote, $_id_proveedor);
	                }else {	                    
	                    $this->OrdinarioNuevo($id_creditos,$_id_lote, $_id_proveedor);
	                }
	            break;
	            case 'PH':
	                if($_es_renovacion){
	                    $this->HipotecarioRenovacion($id_creditos,$_id_lote, $_id_proveedor);	                    
	                }else {
	                    $this->HipotecarioNuevo($id_creditos,$_id_lote, $_id_proveedor);
	                }
	            break;
	            default:    
	                
	        }
	        //$Credito->endTran();
	        return 'OK';
	    } catch (Exception $e) {
	        //$Credito->endTran();
	        return $e->getMessage();
	    }
	}
	
	/***
	 * funcion devuelve el id de  lote creado
	 * @throws Exception
	 */
	public function generaLote(){
	    
	    $Credito           = new CreditosModel();
	    //creacion de lote
	    
	    $nombreLote        = "CxP-Creditos";
	    $descripcionLote   = "GENERACION CREDITO";
	    $id_frecuencia     = 1;
	    $id_usuarios       = $_SESSION['id_usuarios'];
	    $funcionLote       = "tes_genera_lote";
	    $paramLote         = "'$nombreLote','$descripcionLote','$id_frecuencia','$id_usuarios'";
	    $consultaLote      = $Credito->getconsultaPG($funcionLote, $paramLote);
	    $ResultLote        = $Credito->llamarconsultaPG($consultaLote);
	    $_id_lote = 0; // es cero para que la funcion reconosca como un ingreso de nuevo lote
	    $error = pg_last_error();
	    if (!empty($error) || (int)$ResultLote[0] <= 0){
	        throw new Exception('error ingresando lote');
	    }
	    $_id_lote  = (int)$ResultLote[0];
	    return $_id_lote;
	    
	}
	
	/***
	 * 
	 * @param int $_id_lote
	 * @throws Exception
	 */
	public function generaCuentaPagarCredito($_id_lote,$_id_creditos,$_id_proveedor,$ext_descripcion){
	    
	    $Consecutivos = new ConsecutivosModel();
	    $Credito      = new CreditosModel();
	    
	    //datos Cuenta por pagar
	    $_descripcion_cuentas_pagar        = ""; //se llena mas adelante
	    $_fecha_cuentas_pagar              = date('Y-m-d');
	    $_condiciones_pago_cuentas_pagar   = "";
	    $_num_documento_cuentas_pagar      = "";
	    $_num_ord_compra                   = "";
	    $_metodo_envio_cuentas_pagar       = "";
	    $_compra_cuentas_pagar             = ""; //valor de credito
	    $_desc_comercial                   = 0.00;
	    $_flete_cuentas_pagar              = 0.00;
	    $_miscelaneos_cuentas_pagar        = 0.00;
	    $_impuesto_cuentas_pagar           = 0.00;
	    $_total_cuentas_pagar              = 0.00;
	    $_monto1099_cuentas_pagar          = 0.00;
	    $_efectivo_cuentas_pagar           = 0.00;
	    $_cheque_cuentas_pagar             = 0.00;
	    $_tarjeta_credito_cuentas_pagar    = 0.00;
	    $_condonaciones_cuentas_pagar      = 0.00;
	    $_saldo_cuentas_pagar              = 0.00;
	    $_id_cuentas_pagar                 = 0;
	    
	    //busca consecutivo
	    $ResultConsecutivo= $Consecutivos->getConsecutivoByNombre("CxP");
	    $_id_consecutivos = $ResultConsecutivo[0]->id_consecutivos;
	    
	    //busca tipo documento
	    $queryTipoDoc = "SELECT id_tipo_documento, nombre_tipo_documento FROM tes_tipo_documento
                WHERE abreviacion_tipo_documento = 'MIS' LIMIT 1";
	    $ResultTipoDoc= $Credito->enviaquery($queryTipoDoc);
	    $_id_tipo_documento = $ResultTipoDoc[0]->id_tipo_documento;
	    
	    //busca tipo moneda
	    $queryMoneda = "SELECT id_moneda, nombre_moneda FROM tes_moneda
                WHERE nombre_moneda = 'DOLAR' LIMIT 1";
	    $ResultMoneda= $Credito->enviaquery($queryMoneda);
	    $_id_moneda = $ResultMoneda[0]->id_moneda;
	    
	    /* busca valores de credito para ingresar en la cuenta por pagar */
	    $Columnas1 = "id_creditos, monto_otorgado_creditos, monto_neto_entregado_creditos, saldo_actual_creditos, numero_creditos";
	    $Tablas1   = "public.core_creditos";
	    $Where1    = "id_creditos = $_id_creditos";
	    $id        = "id_creditos";	    
	   
	    $rsConsulta1   = $Credito->getCondiciones($Columnas1, $Tablas1, $Where1, $id);
	    
	    if(empty($rsConsulta1)){ throw new Exception('Datos credito en CxP no encontrados'); }	    
	    
	    $monto_credito             = $rsConsulta1[0]->monto_otorgado_creditos;
	    $monto_entregado_credito   = $rsConsulta1[0]->monto_neto_entregado_creditos;
	    $numero_credito            = $rsConsulta1[0]->numero_creditos;
	    
	    $_compra_cuentas_pagar = $monto_credito;
	    $_total_cuentas_pagar  = $_compra_cuentas_pagar;
	    $_saldo_cuentas_pagar  = $monto_entregado_credito - $_impuesto_cuentas_pagar;
	    $_descripcion_cuentas_pagar = "Cuenta x Pagar Credito Num $numero_credito ".$ext_descripcion;
	    $_origen_cuentas_pagar      = "CREDITOS";
	    
	    $_id_bancos = "null"; /* se setea nulo porq luego se actualiza*/
	    
	    /* ingreso de Cuenta x Pagar*/
	    $funcionCuentasPagar = "tes_ins_cuentas_pagar";
	    $paramCuentasPagar = "'$_id_lote', 
                            '$_id_consecutivos', 
                            '$_id_tipo_documento',
                            '$_id_proveedor', 
                             $_id_bancos,
                            '$_id_moneda', 
                            '$_descripcion_cuentas_pagar', 
                            '$_fecha_cuentas_pagar', 
                            '$_condiciones_pago_cuentas_pagar', 
                            '$_num_documento_cuentas_pagar',
                            '$_num_ord_compra',
                            '$_metodo_envio_cuentas_pagar', 
                            '$_compra_cuentas_pagar', 
                            '$_desc_comercial',
                            '$_flete_cuentas_pagar',
                            '$_miscelaneos_cuentas_pagar',
                            '$_impuesto_cuentas_pagar', 
                            '$_total_cuentas_pagar',    
                            '$_monto1099_cuentas_pagar',
                            '$_efectivo_cuentas_pagar',
                            '$_cheque_cuentas_pagar', 
                            '$_tarjeta_credito_cuentas_pagar', 
                            '$_condonaciones_cuentas_pagar', 
                            '$_saldo_cuentas_pagar', 
                            '$_origen_cuentas_pagar', 
                            '$_id_cuentas_pagar'";
	    
	    
	    $consultaCuentasPagar = $Credito->getconsultaPG($funcionCuentasPagar, $paramCuentasPagar);
	    $ResultCuentaPagar = $Credito -> llamarconsultaPG($consultaCuentasPagar);
	    
	    $error = "";
	    $error = pg_last_error();
	    if(!empty($error) || $ResultCuentaPagar[0] <= 0 ){ throw new Exception('error inserccion cuentas pagar');}
	    
	    return $ResultCuentaPagar[0];
	   
	}
	
	public function generaComprobante($_id_lote, $_id_proveedor, $_id_forma_pago, $_valor_comprobantes, $_concepto_comprobantes, $_fecha_comprobantes){
	    
	    if( !isset($_SESSION) ){
	        session_start();
	    }
	    
	    $Credito = new CreditosModel();
	    $_id_usuarios      = $_SESSION['id_usuarios'];
	    $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $TipoComprobantes  = new TipoComprobantesModel();
	    //buscar tipo de comprobante
	    $rsTipoComprobantes = $TipoComprobantes->getTipoComprobanteByNombre("CONTABLE");
	    $_id_tipo_comprobantes = (!empty($rsTipoComprobantes)) ? $rsTipoComprobantes[0]->id_tipo_comprobantes : "null";
	    
	    
	    $funcionComprobante     = "core_ins_ccomprobantes_activacion_credito";
	    $valor_letras           = $Credito->numtoletras($_valor_comprobantes);
	   
	    //para parametros hay valores seteados
	    $parametrosComprobante = "
                1,
                $_id_tipo_comprobantes,
                '',
                '',
                '',
                '$_valor_comprobantes',
                '$_concepto_comprobantes',
                '$_id_usuarios',
                '$valor_letras',
                '$_fecha_comprobantes',
                '$_id_forma_pago',
                null,
                null,
                null,
                null,
                '$_id_proveedor',
                'cxp',
                '$_usuario_usuarios',
                'credito',
                '$_id_lote'";
        
        $consultaComprobante = $Credito ->getconsultaPG($funcionComprobante, $parametrosComprobante);
        $resultadComprobantes = $Credito->llamarconsultaPG($consultaComprobante);
        
        $error = "";
        $error = pg_last_error();
        if(!empty($error) || $resultadComprobantes[0] <= 0 ){   throw new Exception('error insercion comprobante contable '); }
        
        return $resultadComprobantes[0];
	}
	
	public function EmergenteNuevo($_id_credito , $_id_lote, $_id_proveedor){
	    
	    $Credito   = new CreditosModel();
	    
	    $_fecha_proceso = date('Y-m-d');
	    
	    /* datos de credito */
	    $columnas1 = " bb.id_tipo_creditos, bb.nombre_tipo_creditos, bb.codigo_tipo_creditos, aa.numero_creditos,
                      aa.id_creditos, aa.monto_neto_entregado_creditos, aa.monto_otorgado_creditos, aa.id_participes,
                      aa.id_forma_pago";
	    $tablas1   = " core_creditos aa
            	        INNER JOIN core_tipo_creditos bb
            	        ON bb.id_tipo_creditos = aa.id_tipo_creditos";
	    $where1    = "id_creditos = $_id_credito ";
	    $id1       = "aa.id_creditos";
	    
	    $rsConsulta1   = $Credito->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    $_id_tipo_creditos  = $rsConsulta1[0]->id_tipo_creditos;
	    $_monto_otorgado_credito    = $rsConsulta1[0]->monto_otorgado_creditos;
	    $_monto_neto_credito        = $rsConsulta1[0]->monto_neto_entregado_creditos;
	    $_numero_creditos           = $rsConsulta1[0]->numero_creditos;
	    $_id_forma_pago             = $rsConsulta1[0]->id_forma_pago;
	    
	    
	    /* buscar parametrizacion de credito */
	   $columnas2  = " id_modulos, id_plan_cuentas_debe, id_plan_cuentas_haber";
	   $tablas2    = " public.core_parametrizacion_cuentas";
	   $where2     = " tabla_parametrizacion_cuentas = 'core_tipo_creditos'
                        AND modulo_parametrizacion_cuentas = 'CREDITO'
                        AND operacion_parametrizacion_cuentas = 'NUEVO'
                        AND id_principal_parametrizacion_cuentas = $_id_tipo_creditos";
	   $id2        = " id_parametrizacion_cuentas";
	    
	    $rsConsulta2   = $Credito->getCondiciones($columnas2, $tablas2, $where2, $id2);
	    
	    $_id_cuenta_debe    = $rsConsulta2[0]->id_plan_cuentas_debe;
	    $_id_cuenta_haber   = $rsConsulta2[0]->id_plan_cuentas_haber;
	    
	    /* buscar retencion de credito */
	    $columnas3  = " id_creditos_retenciones, monto_creditos_retenciones, id_creditos";
	    $tablas3    = " public.core_creditos_retenciones";
	    $where3     = " id_creditos = $_id_credito ";
	    $id3        = " id_creditos_retenciones";
	    
	    $rsConsulta3   = $Credito->getCondiciones($columnas3, $tablas3, $where3, $id3);
	    
	    $_monto_retencion   = 0.00;
	    if( !empty($rsConsulta3) ){
	        $_monto_retencion    = $rsConsulta3[0]->monto_creditos_retenciones;
	    }
	    
	    /* viene insertado de distribucion cuentas por pagar */
	    /*valores de credito son valores de credito*/
	    $_monto_debito  = $_monto_otorgado_credito;
	    $_monto_credito = $_monto_neto_credito;
	    $_descripcion_distribucion  = "Concesion Credito Num [".$_numero_creditos."]";
	    /* se inserta 3 cuentas para ordinario nuevo */
	    /* cuenta de debito */
	    $queryCuentaCredito = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_debe, "COMPRA", $_monto_debito, '0.00', 1, $_descripcion_distribucion);
	    /* cuenta de credito */
	    $queryCuentaDebito  = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_haber, "PAGOS", '0.00', $_monto_credito, 2, $_descripcion_distribucion);
	    /* validar si existe retencion */
	    if( $_monto_retencion > 0 ){
	        
	        /* buscar cuenta retencion*/
	        $columnas4  = " id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas";
	        $tablas4    = " public.plan_cuentas";
	        $where4     = " nombre_plan_cuentas LIKE 'Interes Crédito Emergente'";
	        $id4        = " id_plan_cuentas";
	        $limit4     = " LIMIT 1";
	        
	        $rsConsulta4   = $Credito->getCondicionesPag($columnas4, $tablas4, $where4, $id4,$limit4);
	        
	        $_id_cuenta_interes = $rsConsulta4[0]->id_plan_cuentas;
	        
	        $queryCuentaRetencion  = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_interes, "IMPTOS", '0.00', $_monto_retencion, 3, $_descripcion_distribucion);
	    }
	    
	    /* viene insertado en base de datos*/
	    $Credito -> executeNonQuery($queryCuentaCredito);
	    $Credito -> executeNonQuery($queryCuentaDebito);
	    if( $_monto_retencion > 0 ){
	        $Credito -> executeNonQuery($queryCuentaRetencion);
	    }
	    
	    $_error_pg  = pg_last_error();
	    $_error_php = error_get_last();
	    
	    if( !empty($_error_pg) || !empty($_error_php) ){
	        throw new Exception('error en validacion de datos distribucion CxP - Emergente');
	    }
	    
	    /* viene insertado de la CxP */
	    $_descripcion_cuentas_pagar = " Tipo EMERGENTE";
	    $_id_cuentas_pagar  = $this->generaCuentaPagarCredito($_id_lote, $_id_credito, $_id_proveedor, $_descripcion_cuentas_pagar );
	    	    
	    /* viene insertado del comprobante */
	    $_concepto_comprobantes = "Consecion Creditos Sol:$_numero_creditos";
	    $_id_comprobantes   = $this->generaComprobante($_id_lote, $_id_proveedor, $_id_forma_pago, $_monto_otorgado_credito, $_concepto_comprobantes, $_fecha_proceso);
	    
	    /* se actualiza el comprobante generado en las tes_cuentas_pagar y credito*/
	    $this->ActualizacionesTablas($_id_credito, $_id_cuentas_pagar, $_id_comprobantes, $_id_forma_pago);
	}
	
	public function EmergenteRenovacion($_id_credito , $_id_lote, $_id_proveedor){    
	    
	    
	    
	    if( !isset($_SESSION) ){
	        session_start();
	    }
	    
	    $Credito   = new CreditosModel();
	    
	    $_id_usuarios      = $_SESSION['id_usuarios'];
	    $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $_fecha_proceso = date('Y-m-d');
	    
	    /* datos de credito */
	    $columnas1 = " bb.id_tipo_creditos, bb.nombre_tipo_creditos, bb.codigo_tipo_creditos, aa.numero_creditos,
                      aa.id_creditos, aa.monto_neto_entregado_creditos, aa.monto_otorgado_creditos, aa.id_participes,
                      aa.bb.id_forma_pago";
	    $tablas1   = " core_creditos aa
            	        INNER JOIN core_tipo_creditos bb
            	        ON bb.id_tipo_creditos = aa.id_tipo_creditos";
	    $where1    = "aa.id_creditos = $_id_credito ";
	    $id1       = "aa.id_creditos";
	    
	    $rsConsulta1   = $Credito->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    $_id_tipo_creditos         = $rsConsulta1[0]->id_tipo_creditos;
	    $_monto_otorgado_credito   = $rsConsulta1[0]->monto_otorgado_creditos;
	    $_monto_neto_credito       = $rsConsulta1[0]->monto_neto_entregado_creditos;
	    $_numero_creditos          = $rsConsulta1[0]->numero_creditos;
	    $_id_forma_pago            = $rsConsulta1[0]->id_forma_pago;
	    
	    /* buscar parametrizacion de credito */
	    $columnas2  = " id_modulos, id_plan_cuentas_debe, id_plan_cuentas_haber";
	    $tablas2    = " public.core_parametrizacion_cuentas";
	    $where2     = " tabla_parametrizacion_cuentas = 'core_tipo_creditos'
                        AND modulo_parametrizacion_cuentas = 'CREDITO'
                        AND operacion_parametrizacion_cuentas = 'NUEVO'
                        AND id_principal_parametrizacion_cuentas = $_id_tipo_creditos";
	    $id2        = " id_parametrizacion_cuentas";
	    
	    $rsConsulta2   = $Credito->getCondiciones($columnas2, $tablas2, $where2, $id2);
	    
	    $_id_cuenta_debe    = $rsConsulta2[0]->id_plan_cuentas_debe;
	    $_id_cuenta_haber   = $rsConsulta2[0]->id_plan_cuentas_haber;
	    
	    /* viene generacion comprobantes de creditos renovados */
	    $columnas3 = "id_creditos_renovaciones, id_creditos_nuevo, id_creditos_renovado, saldo_credito_renovado_creditos_renovaciones,
                    seguro_desgravamen_creditos_renovaciones";
	    $tablas3   = " core_creditos_renovaciones";
	    $where3    = " id_creditos_nuevo = $_id_credito";
	    $id3       = " id_creditos_renovaciones";
	    
	    $rsConsulta3   = $Credito->getCondiciones($columnas3, $tablas3, $where3, $id3);
	    
	    // valores de creditos renovados
	    $_suma_de_saldos_creditos_renovados = 0.00;
	    $_suma_de_desgravamen_creditos_renovados = 0.00;
	    
	    /* se valida si hubo creditos a ser renovados */
	    if( !empty($rsConsulta3) ){
	        
	        /* se genera el comprobante contable de creditos renovados */
	        $_id_credito_renovacion= 0;
	        $funcionRenovados    = "core_renovacion_credito";
	        $parametrosRenovados = "";
	        foreach ( $rsConsulta3 as $res ){
	            $_suma_de_saldos_creditos_renovados += (float)$res->saldo_credito_renovado_creditos_renovaciones;
	            $_suma_de_desgravamen_creditos_renovados += (float)$res->seguro_desgravamen_creditos_renovaciones;
	            $_id_credito_renovacion    = $res->id_creditos_renovado;
	            $parametrosRenovados       = "$_id_credito_renovacion, '$_fecha_proceso', $_id_usuarios, '$_usuario_usuarios', $_id_proveedor ";
	            $_query_renovados          = $Credito->getconsultaPG($funcionRenovados, $parametrosRenovados);
	            $Credito->llamarconsultaPG($_query_renovados);
	            $errorRenovados            = pg_last_error();
	            if(!empty($errorRenovados)){ throw new Exception('Error en creditos Renovados CRE['.$_id_credito_renovacion.']'); }
	            
	        }
	        
	    }
	    
	    /* viene registro de distribucion de CXP */
	    /* parametrizacion de variables*/
	    $_monto_credito_renovacion = $_monto_otorgado_credito;
	    $_monto_saldo_credito_renovacion  = $_monto_credito_renovacion - ( $_suma_de_saldos_creditos_renovados + $_suma_de_desgravamen_creditos_renovados );
	    $_descripcion_distribucion  = "Concesion Credito Num [".$_numero_creditos."]";
	    
	    /* obtener query de insercion */
	    $queryCuentaDebito  = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_haber, "COMPRA", $_monto_credito_renovacion, '0.00', 1, $_descripcion_distribucion);
	    $queryCuentaCredito = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_debe, "PAGOS", '0.00', $_monto_saldo_credito_renovacion ,2, $_descripcion_distribucion);
	    
	    $_error_pg  = pg_last_error();
	    $_error_php = error_get_last();
	    
	    if( empty($_error_pg) || empty($_error_php) ){
	        
	        /* viene insertado en base de datos*/
	        $Credito -> executeNonQuery($queryCuentaCredito);
	        $Credito -> executeNonQuery($queryCuentaDebito);
	        
	        if( !empty($rsConsulta3) ){
	            
	            $_id_credito_renovacion= 0;
	            $_i = 2;
	            foreach ( $rsConsulta3 as $res ){
	                
	                $id_credito_renovacion                 = $res->id_creditos_renovado;
	                $valor_saldo_credito_renovacion        = $res->saldo_credito_renovado_creditos_renovaciones;
	                $valor_desgrvamen_credito_renovacion   = $res->seguro_desgravamen_creditos_renovaciones;
	                $suma_credito_renovacion               = $valor_saldo_credito_renovacion + $valor_desgrvamen_credito_renovacion;
	                $_i ++; //variable para ordenas las cuentas en la distribucion
	                
	                /* buscar parametrizacion de credito renovado */
	                $columnas4  = " id_modulos, id_plan_cuentas_debe, id_plan_cuentas_haber";
	                $tablas4    = " public.core_parametrizacion_cuentas";
	                $where4     = " tabla_parametrizacion_cuentas = 'core_tipo_creditos'
                        AND modulo_parametrizacion_cuentas = 'CREDITO'
                        AND operacion_parametrizacion_cuentas = 'RENOVACION'
                        AND id_principal_parametrizacion_cuentas =
                        ( select id_tipo_creditos from core_creditos where id_creditos = $id_credito_renovacion limit 1)";
	                $id4        = " id_parametrizacion_cuentas";
	                
	                $rsConsulta4   = $Credito->getCondiciones($columnas4, $tablas4, $where4, $id4);
	                
	               
	                if( empty($rsConsulta4) ){
	                    continue;
	                }
	                
	                $id_plan_cuenta_renovacion = $rsConsulta4[0]->id_plan_cuentas_debe;
	                $queryCuentaCreditoRenovaciones  = $this->getQueryInsertDistribucion($_id_lote, $id_plan_cuenta_renovacion, "PAGOS", '0.00', $suma_credito_renovacion, $_i, $_descripcion_distribucion);
	                $Credito -> executeNonQuery($queryCuentaCreditoRenovaciones);
	                $_error_pg  = pg_last_error();
	                $_error_php = error_get_last();
	                
	                if( !empty($_error_pg) || !empty($_error_php) ){
	                    throw new Exception("Error en insertado de detalle distribucion con creditos a ser renovados ".$_error_pg.$_error_php['message']);
	                }
	                
	            }
	            
	        }
	        
	        
	    }else{
	        throw new Exception('error en validacion de datos');
	    }
	    
	    
	    
	    /* viene insertado de la CxP */
	    $_descripcion_cuentas_pagar = " Tipo EMERGENTE";
	    $_id_cuentas_pagar  = $this->generaCuentaPagarCredito($_id_lote, $_id_credito, $_id_proveedor, $_descripcion_cuentas_pagar );
	    
	    
	    /* viene insertado del comprobante */
	    $_concepto_comprobantes = "Consecion Creditos Sol:$_numero_creditos";
	    $_id_comprobantes   = $this->generaComprobante($_id_lote, $_id_proveedor, $_id_forma_pago, $_monto_otorgado_credito, $_concepto_comprobantes, $_fecha_proceso);
	    	    
	    /* se actualiza el comprobante generado en las tes_cuentas_pagar y credito*/
	    $this->ActualizacionesTablas($_id_credito, $_id_cuentas_pagar, $_id_comprobantes, $_id_forma_pago);
	    
	    
	}
	
	public function OrdinarioNuevo($_id_credito , $_id_lote, $_id_proveedor){
	    
	   $Credito   = new CreditosModel();
	   
	   $_fecha_proceso = date('Y-m-d');
	   
	   /* datos de credito */
	   $columnas1 = " bb.id_tipo_creditos, bb.nombre_tipo_creditos, bb.codigo_tipo_creditos, aa.numero_creditos,
                      aa.id_creditos, aa.monto_neto_entregado_creditos, aa.monto_otorgado_creditos, aa.id_participes,
                      aa.id_forma_pago";
	   $tablas1   = " core_creditos aa
            	        INNER JOIN core_tipo_creditos bb
            	        ON bb.id_tipo_creditos = aa.id_tipo_creditos";
	   $where1    = "id_creditos = $_id_credito ";
	   $id1       = "aa.id_creditos";
	   
	   $rsConsulta1   = $Credito->getCondiciones($columnas1, $tablas1, $where1, $id1);
	   
	   $_id_tipo_creditos  = $rsConsulta1[0]->id_tipo_creditos;
	   $_monto_otorgado_credito    = $rsConsulta1[0]->monto_otorgado_creditos;
	   $_monto_neto_credito        = $rsConsulta1[0]->monto_neto_entregado_creditos;
	   $_numero_creditos           = $rsConsulta1[0]->numero_creditos;
	   $_id_forma_pago             = $rsConsulta1[0]->id_forma_pago;
	     
	   /* buscar parametrizacion de credito */
	   $columnas2  = " id_modulos, id_plan_cuentas_debe, id_plan_cuentas_haber";
	   $tablas2    = " public.core_parametrizacion_cuentas";
	   $where2     = " tabla_parametrizacion_cuentas = 'core_tipo_creditos'
                        AND modulo_parametrizacion_cuentas = 'CREDITO'
                        AND operacion_parametrizacion_cuentas = 'NUEVO'
                        AND id_principal_parametrizacion_cuentas = $_id_tipo_creditos";
	   $id2        = " id_parametrizacion_cuentas";
	   
	   $rsConsulta2   = $Credito->getCondiciones($columnas2, $tablas2, $where2, $id2);
	   
	   $_id_cuenta_debe    = $rsConsulta2[0]->id_plan_cuentas_debe;
	   $_id_cuenta_haber   = $rsConsulta2[0]->id_plan_cuentas_haber;
	   
	   /* buscar retencion de credito */
	   $columnas3  = " id_creditos_retenciones, monto_creditos_retenciones, id_creditos";
	   $tablas3    = " public.core_creditos_retenciones";
	   $where3     = " id_creditos = $_id_credito ";
	   $id3        = " id_creditos_retenciones";
	   
	   $rsConsulta3   = $Credito->getCondiciones($columnas3, $tablas3, $where3, $id3);
	   
	   $_monto_retencion   = 0.00;
	   if( !empty($rsConsulta3) ){
	       $_monto_retencion    = $rsConsulta3[0]->monto_creditos_retenciones;
	   }
	   
	   /* viene insertado de distribucion cuentas por pagar */	
	   /*valores de credito son valores de credito*/
	   $_monto_debito  = $_monto_otorgado_credito;
	   $_monto_credito = $_monto_neto_credito;
	   $_descripcion_distribucion  = "Concesion Credito Num [".$_numero_creditos."]";
	   /* se inserta 3 cuentas para ordinario nuevo */
	   /* cuenta de debito */
	   $queryCuentaCredito = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_debe, "COMPRA", $_monto_debito, '0.00', 1, $_descripcion_distribucion);
	   /* cuenta de credito */
	   $queryCuentaDebito  = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_haber, "PAGOS", '0.00', $_monto_credito, 2, $_descripcion_distribucion);
	   /* validar si existe retencion */
	   if( $_monto_retencion > 0 ){
	       
	       /* buscar cuenta retencion*/
	       $columnas4  = " id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas";
	       $tablas4    = " public.plan_cuentas";
	       $where4     = " nombre_plan_cuentas LIKE 'Interes Crédito Ordinario'";
	       $id4        = " id_plan_cuentas";
	       $limit4     = " LIMIT 1";
	       
	       $rsConsulta4   = $Credito->getCondicionesPag($columnas4, $tablas4, $where4, $id4,$limit4);
	       
	       $_id_cuenta_interes = $rsConsulta4[0]->id_plan_cuentas;
	       
	       $queryCuentaRetencion  = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_interes, "PAGOS", '0.00', $_monto_retencion, 3, $_descripcion_distribucion);
	   }
	   
	   $_error_pg  = pg_last_error();
	   $_error_php = error_get_last();
	   
	   if( !empty($_error_pg) || !empty($_error_php) ){
	       throw new Exception('error en validacion de datos');
	   }
	   
	   /* viene insertado en base de datos*/
	   $Credito -> executeNonQuery($queryCuentaCredito);
	   $Credito -> executeNonQuery($queryCuentaDebito);
	   if( $_monto_retencion > 0 ){
	       $Credito -> executeNonQuery($queryCuentaRetencion);
	   }	   
	   
	   /* viene insertado de la CxP */
	   $_descripcion_cuentas_pagar = " Tipo ORDINARIO";
	   $_id_cuentas_pagar  = $this->generaCuentaPagarCredito($_id_lote, $_id_credito, $_id_proveedor, $_descripcion_cuentas_pagar );
	   	   
	   /* viene insertado del comprobante */
	   $_concepto_comprobantes = "Consecion Creditos Sol:$_numero_creditos";
	   $_id_comprobantes   = $this->generaComprobante($_id_lote, $_id_proveedor, $_id_forma_pago, $_monto_otorgado_credito, $_concepto_comprobantes, $_fecha_proceso);
	   
	   /* se actualiza el comprobante generado en las tes_cuentas_pagar y credito*/
	   $this->ActualizacionesTablas($_id_credito, $_id_cuentas_pagar, $_id_comprobantes, $_id_forma_pago);
	   
	   	    
	}
	
	public function OrdinarioRenovacion( $_id_credito , $_id_lote, $_id_proveedor ){
	    
	    if( !isset($_SESSION) ){
	        session_start();
	    }
	    
	    $Credito   = new CreditosModel();
	    
	    $_id_usuarios      = $_SESSION['id_usuarios'];
	    $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $_fecha_proceso = date('Y-m-d');
	    
	    /* datos de credito */
	    $columnas1 = " bb.id_tipo_creditos, bb.nombre_tipo_creditos, bb.codigo_tipo_creditos, aa.numero_creditos,
                      aa.id_creditos, aa.monto_neto_entregado_creditos, aa.monto_otorgado_creditos, aa.id_participes,
                      aa.id_forma_pago";
	    $tablas1   = " core_creditos aa
            	        INNER JOIN core_tipo_creditos bb
            	        ON bb.id_tipo_creditos = aa.id_tipo_creditos";
	    $where1    = "aa.id_creditos = $_id_credito ";
	    $id1       = "aa.id_creditos";
	    
	    $rsConsulta1   = $Credito->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    $_id_tipo_creditos         = $rsConsulta1[0]->id_tipo_creditos;
	    $_monto_otorgado_credito   = $rsConsulta1[0]->monto_otorgado_creditos;
	    $_monto_neto_credito       = $rsConsulta1[0]->monto_neto_entregado_creditos;
	    $_numero_creditos          = $rsConsulta1[0]->numero_creditos;
	    $_id_forma_pago             = $rsConsulta1[0]->id_forma_pago;
	    
	    /* buscar parametrizacion de credito */
	    $columnas2  = " id_modulos, id_plan_cuentas_debe, id_plan_cuentas_haber";
	    $tablas2    = " public.core_parametrizacion_cuentas";
	    $where2     = " tabla_parametrizacion_cuentas = 'core_tipo_creditos' 
                        AND modulo_parametrizacion_cuentas = 'CREDITO'
                        AND operacion_parametrizacion_cuentas = 'NUEVO'
                        AND id_principal_parametrizacion_cuentas = $_id_tipo_creditos";
	    $id2        = " id_parametrizacion_cuentas";
	    
	    $rsConsulta2   = $Credito->getCondiciones($columnas2, $tablas2, $where2, $id2);
	    
	    $_id_cuenta_debe    = $rsConsulta2[0]->id_plan_cuentas_debe;
	    $_id_cuenta_haber   = $rsConsulta2[0]->id_plan_cuentas_haber;
	    
	    /* viene generacion comprobantes de creditos renovados */
	    $columnas3 = "id_creditos_renovaciones, id_creditos_nuevo, id_creditos_renovado, saldo_credito_renovado_creditos_renovaciones, 
                    seguro_desgravamen_creditos_renovaciones";
	    $tablas3   = " core_creditos_renovaciones";
	    $where3    = " id_creditos_nuevo = $_id_credito";
	    $id3       = " id_creditos_renovaciones";
	    
	    $rsConsulta3   = $Credito->getCondiciones($columnas3, $tablas3, $where3, $id3);
	    
	    // valores de creditos renovados
	    $_suma_de_saldos_creditos_renovados = 0.00;
	    $_suma_de_desgravamen_creditos_renovados = 0.00;
	    
	    /* se valida si hubo creditos a ser renovados */
	    if( !empty($rsConsulta3) ){
	        
	        /* se genera el comprobante contable de creditos renovados */
	        $_id_credito_renovacion= 0;
	        $funcionRenovados    = "core_renovacion_credito";
	        $parametrosRenovados = "";
	        foreach ( $rsConsulta3 as $res ){
	            $_suma_de_saldos_creditos_renovados += (float)$res->saldo_credito_renovado_creditos_renovaciones;
	            $_suma_de_desgravamen_creditos_renovados += (float)$res->seguro_desgravamen_creditos_renovaciones;
	            $_id_credito_renovacion    = $res->id_creditos_renovado;
	            $parametrosRenovados       = "$_id_credito_renovacion, '$_fecha_proceso', $_id_usuarios, '$_usuario_usuarios', $_id_proveedor ";
	            $_query_renovados          = $Credito->getconsultaPG($funcionRenovados, $parametrosRenovados);
	            $Credito->llamarconsultaPG($_query_renovados);
	            $errorRenovados            = pg_last_error();
	            if(!empty($errorRenovados)){ throw new Exception('Error en creditos Renovados CRE['.$_id_credito_renovacion.']'); }
	            
	        }
	       
	    }
	    
	    /* viene registro de distribucion de CXP */
	    /* parametrizacion de variables*/
	    $_monto_credito_renovacion = $_monto_otorgado_credito;
	    $_monto_saldo_credito_renovacion  = $_monto_credito_renovacion - ( $_suma_de_saldos_creditos_renovados + $_suma_de_desgravamen_creditos_renovados );
	    $_descripcion_distribucion  = "Concesion Credito Num [".$_numero_creditos."]";
	    
	    /* obtener query de insercion */
	    $queryCuentaDebito  = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_haber, "COMPRA", $_monto_credito_renovacion, '0.00', 1, $_descripcion_distribucion);	    
	    $queryCuentaCredito = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_debe, "PAGOS", '0.00', $_monto_saldo_credito_renovacion ,2, $_descripcion_distribucion);
	    
	    //echo $queryCuentaDebito,'\n',$queryCuentaCredito;
	    //throw new Exception('prueba');
	    
	    $_error_pg  = pg_last_error();
	    $_error_php = error_get_last();
	    
	    if( empty($_error_pg) || empty($_error_php) ){
	        
	        /* viene insertado en base de datos*/
	        $Credito -> executeNonQuery($queryCuentaCredito);
	        $Credito -> executeNonQuery($queryCuentaDebito);
	        
	        if( !empty($rsConsulta3) ){	            
	           
	            $_id_credito_renovacion= 0;
	            $_i = 2;
	            foreach ( $rsConsulta3 as $res ){
	                
                    $id_credito_renovacion                 = $res->id_creditos_renovado;
                    $valor_saldo_credito_renovacion        = $res->saldo_credito_renovado_creditos_renovaciones;
                    $valor_desgrvamen_credito_renovacion   = $res->seguro_desgravamen_creditos_renovaciones;
                    $suma_credito_renovacion               = $valor_saldo_credito_renovacion + $valor_desgrvamen_credito_renovacion;
                    $_i ++; //variable para ordenas las cuentas en la distribucion
	                
	                /* buscar parametrizacion de credito renovado */
	                $columnas4  = " id_modulos, id_plan_cuentas_debe, id_plan_cuentas_haber";
	                $tablas4    = " public.core_parametrizacion_cuentas";
	                $where4     = " tabla_parametrizacion_cuentas = 'core_tipo_creditos'
                        AND modulo_parametrizacion_cuentas = 'CREDITO'
                        AND operacion_parametrizacion_cuentas = 'RENOVACION'
                        AND id_principal_parametrizacion_cuentas = 
                        ( select id_tipo_creditos from core_creditos where id_creditos = $id_credito_renovacion limit 1)";
	                $id4        = " id_parametrizacion_cuentas";
	                
	                $rsConsulta4   = $Credito->getCondiciones($columnas4, $tablas4, $where4, $id4);
	                
	                if( empty($rsConsulta4) ){
	                    continue;
	                }
	                
	                $id_plan_cuenta_renovacion = $rsConsulta4[0]->id_plan_cuentas_debe;
	                $queryCuentaCreditoRenovaciones  = $this->getQueryInsertDistribucion($_id_lote, $id_plan_cuenta_renovacion, "PAGOS", '0.00', $suma_credito_renovacion, $_i, $_descripcion_distribucion);
	                $Credito -> executeNonQuery($queryCuentaCreditoRenovaciones);
	                $_error_pg  = pg_last_error();
	                $_error_php = error_get_last();	                
	                
	                if( !empty($_error_pg) || !empty($_error_php) ){
	                    throw new Exception("Error en insertado de detalle distribucion con creditos a ser renovados");
	                }
	                
	            }
	            
	        }
	        
	       
	    }else{
	        throw new Exception('error en validacion de datos');
	    }
	    
	    /* viene insertado de la CxP */
	    $_descripcion_cuentas_pagar = " Tipo ORDINARIO";
	    $_id_cuentas_pagar  = $this->generaCuentaPagarCredito($_id_lote, $_id_credito, $_id_proveedor, $_descripcion_cuentas_pagar );
	    
	    /* viene insertado del comprobante */
	    $_concepto_comprobantes = "Consecion Creditos Sol:$_numero_creditos";
	    $_id_comprobantes   = $this->generaComprobante($_id_lote, $_id_proveedor, $_id_forma_pago, $_monto_otorgado_credito, $_concepto_comprobantes, $_fecha_proceso);
	    	    
	    /* se actualiza el comprobante generado en las tes_cuentas_pagar y credito*/
	    $this->ActualizacionesTablas($_id_credito, $_id_cuentas_pagar, $_id_comprobantes, $_id_forma_pago);
	    	   
	}
	
	public function HipotecarioNuevo($_id_credito, $_id_lote, $_id_proveedor){
	    
	    
	    $Credito   = new CreditosModel();
	    
	    $_fecha_proceso = date('Y-m-d');
	    
	    /* datos de credito */
	    $columnas1 = " bb.id_tipo_creditos, bb.nombre_tipo_creditos, bb.codigo_tipo_creditos, aa.numero_creditos,
                      aa.id_creditos, aa.monto_neto_entregado_creditos, aa.monto_otorgado_creditos, aa.id_participes,
                      aa.id_forma_pago";
	    $tablas1   = " core_creditos aa
            	        INNER JOIN core_tipo_creditos bb
            	        ON bb.id_tipo_creditos = aa.id_tipo_creditos";
	    $where1    = "id_creditos = $_id_credito ";
	    $id1       = "aa.id_creditos";
	    
	    $rsConsulta1   = $Credito->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    $_id_tipo_creditos  = $rsConsulta1[0]->id_tipo_creditos;
	    $_monto_otorgado_credito    = $rsConsulta1[0]->monto_otorgado_creditos;
	    $_monto_neto_credito        = $rsConsulta1[0]->monto_neto_entregado_creditos;
	    $_numero_creditos           = $rsConsulta1[0]->numero_creditos;
	    $_id_forma_pago             = $rsConsulta1[0]->id_forma_pago;
	    
	    
	    /* buscar parametrizacion de credito */
	    $columnas2  = " id_modulos, id_plan_cuentas_debe, id_plan_cuentas_haber";
	    $tablas2    = " public.core_parametrizacion_cuentas";
	    $where2     = " tabla_parametrizacion_cuentas = 'core_tipo_creditos'
                        AND modulo_parametrizacion_cuentas = 'CREDITO'
                        AND operacion_parametrizacion_cuentas = 'NUEVO'
                        AND id_principal_parametrizacion_cuentas = $_id_tipo_creditos";
	    $id2        = " id_parametrizacion_cuentas";
	    
	    $rsConsulta2   = $Credito->getCondiciones($columnas2, $tablas2, $where2, $id2);
	    
	    $_id_cuenta_debe    = $rsConsulta2[0]->id_plan_cuentas_debe;
	    $_id_cuenta_haber   = $rsConsulta2[0]->id_plan_cuentas_haber;
	    
	    /* buscar retencion de credito */
	    $columnas3  = " id_creditos_retenciones, monto_creditos_retenciones, id_creditos";
	    $tablas3    = " public.core_creditos_retenciones";
	    $where3     = " id_creditos = $_id_credito ";
	    $id3        = " id_creditos_retenciones";
	    
	    $rsConsulta3   = $Credito->getCondiciones($columnas3, $tablas3, $where3, $id3);
	    
	    $_monto_retencion   = 0.00;
	    if( !empty($rsConsulta3) ){
	        $_monto_retencion    = $rsConsulta3[0]->monto_creditos_retenciones;
	    }
	    
	    /* viene insertado de distribucion cuentas por pagar */
	    /*valores de credito son valores de credito*/
	    $_monto_debito  = $_monto_otorgado_credito;
	    $_monto_credito = $_monto_neto_credito;
	    $_descripcion_distribucion  = "Concesion Credito Num [".$_numero_creditos."]";
	    /* se inserta 3 cuentas para ordinario nuevo */
	    /* cuenta de debito */
	    $queryCuentaCredito = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_debe, "COMPRA", $_monto_debito, '0.00', 1, $_descripcion_distribucion);
	    /* cuenta de credito */
	    $queryCuentaDebito  = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_haber, "PAGOS", '0.00', $_monto_credito, 2, $_descripcion_distribucion);
	    /* validar si existe retencion */
	    if( $_monto_retencion > 0 ){
	        
	        /* buscar cuenta retencion*/
	        $columnas4  = " id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas";
	        $tablas4    = " public.plan_cuentas";
	        $where4     = " nombre_plan_cuentas LIKE 'Intereses Crédito Hipotecario";
	        $id4        = " id_plan_cuentas";
	        $limit4     = " LIMIT 1";
	        
	        $rsConsulta4   = $Credito->getCondicionesPag($columnas4, $tablas4, $where4, $id4,$limit4);
	        
	        $_id_cuenta_interes = $rsConsulta4[0]->id_plan_cuentas;
	        
	        $queryCuentaRetencion  = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_interes, "PAGOS", '0.00', $_monto_retencion, 3, $_descripcion_distribucion);
	    }
	    
	    $_error_pg  = pg_last_error();
	    $_error_php = error_get_last();
	    
	    if( !empty($_error_pg) || !empty($_error_php) ){
	        throw new Exception('error en validacion de datos');
	    }
	    
	    /* viene insertado en base de datos*/
	    $Credito -> executeNonQuery($queryCuentaCredito);
	    $Credito -> executeNonQuery($queryCuentaDebito);
	    if( $_monto_retencion > 0 ){
	        $Credito -> executeNonQuery($queryCuentaRetencion);
	    }
	    
	    /* viene insertado de la CxP */
	    $_descripcion_cuentas_pagar = " Tipo HIPOTECARIO";
	    $_id_cuentas_pagar  = $this->generaCuentaPagarCredito($_id_lote, $_id_credito, $_id_proveedor, $_descripcion_cuentas_pagar );
	    
	    /* viene insertado del comprobante */
	    $_concepto_comprobantes = "Consecion Creditos Sol:$_numero_creditos";
	    $_id_comprobantes   = $this->generaComprobante($_id_lote, $_id_proveedor, $_id_forma_pago, $_monto_otorgado_credito, $_concepto_comprobantes, $_fecha_proceso);
	    
	    /* se actualiza el comprobante generado en las tes_cuentas_pagar y credito*/
	    $this->ActualizacionesTablas($_id_credito, $_id_cuentas_pagar, $_id_comprobantes, $_id_forma_pago);
	    
	    
	}
	
	public function HipotecarioRenovacion($_id_credito, $_id_lote, $_id_proveedor){	    
	    
	    
	    if( !isset($_SESSION) ){
	        session_start();
	    }
	    
	    $Credito   = new CreditosModel();
	    
	    $_id_usuarios      = $_SESSION['id_usuarios'];
	    $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $_fecha_proceso = date('Y-m-d');
	    
	    /* datos de credito */
	    $columnas1 = " bb.id_tipo_creditos, bb.nombre_tipo_creditos, bb.codigo_tipo_creditos, aa.numero_creditos,
                      aa.id_creditos, aa.monto_neto_entregado_creditos, aa.monto_otorgado_creditos, aa.id_participes,
                      aa.id_forma_pago";
	    $tablas1   = " core_creditos aa
            	        INNER JOIN core_tipo_creditos bb
            	        ON bb.id_tipo_creditos = aa.id_tipo_creditos";
	    $where1    = "aa.id_creditos = $_id_credito ";
	    $id1       = "aa.id_creditos";
	    
	    $rsConsulta1   = $Credito->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    $_id_tipo_creditos         = $rsConsulta1[0]->id_tipo_creditos;
	    $_monto_otorgado_credito   = $rsConsulta1[0]->monto_otorgado_creditos;
	    $_monto_neto_credito       = $rsConsulta1[0]->monto_neto_entregado_creditos;
	    $_numero_creditos          = $rsConsulta1[0]->numero_creditos;
	    $_id_forma_pago            = $rsConsulta1[0]->id_forma_pago;
	    
	    /* buscar parametrizacion de credito */
	    $columnas2  = " id_modulos, id_plan_cuentas_debe, id_plan_cuentas_haber";
	    $tablas2    = " public.core_parametrizacion_cuentas";
	    $where2     = " tabla_parametrizacion_cuentas = 'core_tipo_creditos'
                        AND modulo_parametrizacion_cuentas = 'CREDITO'
                        AND operacion_parametrizacion_cuentas = 'NUEVO'
                        AND id_principal_parametrizacion_cuentas = $_id_tipo_creditos";
	    $id2        = " id_parametrizacion_cuentas";
	    
	    $rsConsulta2   = $Credito->getCondiciones($columnas2, $tablas2, $where2, $id2);
	    
	    $_id_cuenta_debe    = $rsConsulta2[0]->id_plan_cuentas_debe;
	    $_id_cuenta_haber   = $rsConsulta2[0]->id_plan_cuentas_haber;
	    
	    /* viene generacion comprobantes de creditos renovados */
	    $columnas3 = "id_creditos_renovaciones, id_creditos_nuevo, id_creditos_renovado, saldo_credito_renovado_creditos_renovaciones,
                    seguro_desgravamen_creditos_renovaciones";
	    $tablas3   = " core_creditos_renovaciones";
	    $where3    = " id_creditos_nuevo = $_id_credito";
	    $id3       = " id_creditos_renovaciones";
	    
	    $rsConsulta3   = $Credito->getCondiciones($columnas3, $tablas3, $where3, $id3);
	    
	    // valores de creditos renovados
	    $_suma_de_saldos_creditos_renovados = 0.00;
	    $_suma_de_desgravamen_creditos_renovados = 0.00;
	    
	    /* se valida si hubo creditos a ser renovados */
	    if( !empty($rsConsulta3) ){
	        
	        /* se genera el comprobante contable de creditos renovados */
	        $_id_credito_renovacion= 0;
	        $funcionRenovados    = "core_renovacion_credito";
	        $parametrosRenovados = "";
	        foreach ( $rsConsulta3 as $res ){
	            $_suma_de_saldos_creditos_renovados += (float)$res->saldo_credito_renovado_creditos_renovaciones;
	            $_suma_de_desgravamen_creditos_renovados += (float)$res->seguro_desgravamen_creditos_renovaciones;
	            $_id_credito_renovacion    = $res->id_creditos_renovado;
	            $parametrosRenovados       = "$_id_credito_renovacion, '$_fecha_proceso', $_id_usuarios, '$_usuario_usuarios', $_id_proveedor ";
	            $_query_renovados          = $Credito->getconsultaPG($funcionRenovados, $parametrosRenovados);
	            $Credito->llamarconsultaPG($_query_renovados);
	            $errorRenovados            = pg_last_error();
	            if(!empty($errorRenovados)){ throw new Exception('Error en creditos Renovados CRE['.$_id_credito_renovacion.']'); }
	            
	        }
	        
	    }
	    
	    /* viene registro de distribucion de CXP */
	    /* parametrizacion de variables*/
	    $_monto_credito_renovacion = $_monto_otorgado_credito;
	    $_monto_saldo_credito_renovacion  = $_monto_credito_renovacion - ( $_suma_de_saldos_creditos_renovados + $_suma_de_desgravamen_creditos_renovados );
	    $_descripcion_distribucion  = "Concesion Credito Num [".$_numero_creditos."]";
	    
	    /* obtener query de insercion */
	    $queryCuentaDebito  = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_haber, "COMPRA", $_monto_credito_renovacion, '0.00', 1, $_descripcion_distribucion);
	    $queryCuentaCredito = $this->getQueryInsertDistribucion($_id_lote, $_id_cuenta_debe, "PAGOS", '0.00', $_monto_saldo_credito_renovacion ,2, $_descripcion_distribucion);
	    
	    //echo $queryCuentaDebito,'\n',$queryCuentaCredito;
	    //throw new Exception('prueba');
	    
	    $_error_pg  = pg_last_error();
	    $_error_php = error_get_last();
	    
	    if( empty($_error_pg) || empty($_error_php) ){
	        
	        /* viene insertado en base de datos*/
	        $Credito -> executeNonQuery($queryCuentaCredito);
	        $Credito -> executeNonQuery($queryCuentaDebito);
	        
	        if( !empty($rsConsulta3) ){
	            
	            $_id_credito_renovacion= 0;
	            $_i = 2;
	            foreach ( $rsConsulta3 as $res ){
	                
	                $id_credito_renovacion                 = $res->id_creditos_renovado;
	                $valor_saldo_credito_renovacion        = $res->saldo_credito_renovado_creditos_renovaciones;
	                $valor_desgrvamen_credito_renovacion   = $res->seguro_desgravamen_creditos_renovaciones;
	                $suma_credito_renovacion               = $valor_saldo_credito_renovacion + $valor_desgrvamen_credito_renovacion;
	                $_i ++; //variable para ordenas las cuentas en la distribucion
	                
	                /* buscar parametrizacion de credito renovado */
	                $columnas4  = " id_modulos, id_plan_cuentas_debe, id_plan_cuentas_haber";
	                $tablas4    = " public.core_parametrizacion_cuentas";
	                $where4     = " tabla_parametrizacion_cuentas = 'core_tipo_creditos'
                        AND modulo_parametrizacion_cuentas = 'CREDITO'
                        AND operacion_parametrizacion_cuentas = 'RENOVACION'
                        AND id_principal_parametrizacion_cuentas =
                        ( select id_tipo_creditos from core_creditos where id_creditos = $id_credito_renovacion limit 1)";
	                $id4        = " id_parametrizacion_cuentas";
	                
	                $rsConsulta4   = $Credito->getCondiciones($columnas4, $tablas4, $where4, $id4);
	                
	                if( empty($rsConsulta4) ){
	                    continue;
	                }
	                
	                $id_plan_cuenta_renovacion = $rsConsulta4[0]->id_plan_cuentas_debe;
	                $queryCuentaCreditoRenovaciones  = $this->getQueryInsertDistribucion($_id_lote, $id_plan_cuenta_renovacion, "PAGOS", '0.00', $suma_credito_renovacion, $_i, $_descripcion_distribucion);
	                $Credito -> executeNonQuery($queryCuentaCreditoRenovaciones);
	                $_error_pg  = pg_last_error();
	                $_error_php = error_get_last();
	                
	                if( !empty($_error_pg) || !empty($_error_php) ){
	                    throw new Exception("Error en insertado de detalle distribucion con creditos a ser renovados");
	                }
	                
	            }
	            
	        }
	        
	        
	    }else{
	        throw new Exception('error en validacion de datos');
	    }
	    
	    
	    
	    /* viene insertado de la CxP */
	    $_descripcion_cuentas_pagar = " Tipo HIPOTECARIO";
	    $_id_cuentas_pagar  = $this->generaCuentaPagarCredito($_id_lote, $_id_credito, $_id_proveedor, $_descripcion_cuentas_pagar );
	    
	    /* viene insertado del comprobante */
	    $_concepto_comprobantes = "Consecion Creditos Sol:$_numero_creditos";
	    $_id_comprobantes   = $this->generaComprobante($_id_lote, $_id_proveedor, $_id_forma_pago, $_monto_otorgado_credito, $_concepto_comprobantes, $_fecha_proceso);
	    
	    /* se actualiza el comprobante generado en las tes_cuentas_pagar y credito*/
	    $this->ActualizacionesTablas($_id_credito, $_id_cuentas_pagar, $_id_comprobantes, $_id_forma_pago);
	    
	}
	
	public function getQueryInsertDistribucion( $_id_lote,$_id_cuenta,$_tipo,$_monto_debito,$_monto_credito, $_orden, $_descripcion){
	    
	    return "INSERT INTO tes_distribucion_cuentas_pagar
                (id_lote,
                id_plan_cuentas,
                tipo_distribucion_cuentas_pagar,
                debito_distribucion_cuentas_pagar,
                credito_distribucion_cuentas_pagar,
                ord_distribucion_cuentas_pagar,
                referencia_distribucion_cuentas_pagar)
                VALUES ( 
                '$_id_lote',
                '$_id_cuenta',
                '$_tipo',
                '$_monto_debito',
                '$_monto_credito',
                '$_orden',
                '$_descripcion')";
	    
	}
	
	public function ActualizacionesTablas( $_id_credito, $_id_cuentas_pagar, $_id_comprobantes, $_id_forma_pago){
	    
	    $Credito = new CreditosModel();
	    
	    $columnas1 = "aa.id_creditos,cc.id_bancos";
	    $tablas1   = "core_creditos aa
                    iNNER JOIN core_participes_cuentas cc
                    ON cc.id_participes = aa.id_participes
                    AND cc.cuenta_principal = true";
	    $where1     = "aa.id_estatus = 1 AND aa.id_creditos = $_id_credito";
	    $id1        = "aa.id_creditos";
	    
	    $rsConsulta1   = $Credito->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    /* si la consulta esta vacia el socio pidio creedito por medio del cheque */
	    if(empty($rsConsulta1)){
	        $columnaPago = " id_ccomprobantes = $_id_comprobantes, id_forma_pago = $_id_forma_pago ";
	    }else{
	        $_id_bancos    = $rsConsulta1[0]->id_bancos;
	        $columnaPago   = "id_ccomprobantes = $_id_comprobantes, id_forma_pago = $_id_forma_pago , id_banco = $_id_bancos ";
	    }
	    /* actualizacion CXP */	    
	    $tablasPago = "tes_cuentas_pagar";
	    $wherePago = "id_cuentas_pagar = $_id_cuentas_pagar";
	    $Credito -> ActualizarBy($columnaPago, $tablasPago, $wherePago);	    
	    	    
	    //se actualiza el credito con su comprobante
	    $columnaCre = "id_ccomprobantes = $_id_comprobantes ";
	    $tablasCre = "core_creditos";
	    $whereCre = "id_creditos = $_id_credito";
	    $Credito -> ActualizarBy($columnaCre, $tablasCre, $whereCre);
	    
	    $error_pg = pg_last_error();
	    $error_ph = error_get_last();
	    
	    if(!empty($error_pg) || !empty($error_ph)){
	        throw  new Exception(' en la actualizacion de tablas');
	    }
	    
	}
	
	public function MayorizaComprobanteCredito($id_credito){
	    
	    $Credito = new CreditosModel();
	    
	    try {
	        
	        $columas1  = "id_creditos, numero_creditos, id_ccomprobantes";
	        $tablas1   = "core_creditos";
	        $where1    = "id_creditos = $id_credito ";
	        $id1       = "id_creditos";
	        $rsConsulta1 = $Credito->getCondiciones($columas1, $tablas1, $where1, $id1);
	        
	        if(empty($rsConsulta1)){ throw new Exception('credito no encontrado');}
	        
	        $_id_comprobante       = $rsConsulta1[0]->id_ccomprobantes;
	        $funcionMayoriza       = "core_ins_mayoriza_activa_credito";
	        $parametrosMayoriza    = "$_id_comprobante,null";
	        $consultaMayoriza      = $Credito->getconsultaPG($funcionMayoriza, $parametrosMayoriza);
	        $Credito->llamarconsultaPG($consultaMayoriza);
	        
	        $error = "";
	        $error = pg_last_error();
	        if(!empty($error)){ throw new Exception('mayoriza comprobante credito');}
	        
	        /* valida si el credito tiene renovaciones de credito */
	        $columnas2 = " id_creditos_renovado,id_ccomprobantes";
	        $tablas2   = " core_creditos_renovaciones";
	        $where2    = " id_creditos_nuevo  = $_id_comprobante";
	        $id2       = " id_creditos_renovaciones";
	        $rsConsulta2   = $Credito->getCondiciones($columnas2, $tablas2, $where2, $id2);
	        if(!empty($rsConsulta2)){
	            foreach ($rsConsulta2 as $res ){
	                $_id_credito_renovado  = $res->id_creditos_renovado;
	                $funcionMayorizaRenovados       = "core_ins_mayoriza_activa_credito";
	                $parametrosMayorizaRenovados    = "$_id_credito_renovado,null";
	                $consultaMayorizaRenovados      = $Credito->getconsultaPG($funcionMayorizaRenovados, $parametrosMayorizaRenovados);
	                $Credito->llamarconsultaPG($consultaMayorizaRenovados);
	                
	                $error = "";
	                $error = pg_last_error();
	                if(!empty($error)){ throw new Exception('comprobante Credito ['.$_id_credito_renovado.'] Renovado');}
	            }
	        }
	        
	        return 'OK';
	        
	    } catch (Exception $e) {
	        return "ERROR".$e->getMessage();
	    }
	    
	}
	
	
	public function RenovarCredito( $_id_creditos){
	    
	}
	
	public function verValorLetras(){
	    
	    $_valor = $_POST['valor'];
	    $credito = new CreditosModel();
	    
	    echo $credito->numtoletras($_valor);
	}
	
	/******************************************************** EMPIEZA PROCESOS DE ANULAR REPORTES CREDITOS ****************************************/
	//dc 2019-09-27
	
	public function EliminarReporteCredito( $_id_credito ){
	    
	    $Credito = new CreditosModel();
	    
	    try {
	        
	       /** variables a utilizar **/
	       $arrayComprobantes = array();
	       $_id_comprobante    = 0;
	       $_id_comprobante_sub= 0;
	       $_error = "";
	        
	       /** buscar credito si tiene comprobante **/
	       $columnas1  = "id_creditos, id_ccomprobantes";
	       $tablas1    = " core_creditos";
	       $where1     = " id_creditos = $_id_credito";
	       $id1        = " id_creditos ";
	       $rsConsulta1= $Credito->getCondiciones($columnas1, $tablas1, $where1, $id1);
	       $_error = pg_last_error();
	       if(!empty($_error)){ throw new Exception(' error buscando credito ['.$_id_credito.'] ');}
	       if(!empty($rsConsulta1)){ 
	           $_id_comprobante    = $rsConsulta1[0]->id_ccomprobantes;
	           array_push($arrayComprobantes, $_id_comprobante);
	       }else{
	           throw new Exception(' credito ['.$_id_credito.'] no encontrado');
	       }
	       /** buscar si tiene renovaciones **/ 
	       $columnas2  = " id_creditos_renovado,id_creditos_nuevo,id_ccomprobantes";
	       $tablas2    = " public.core_creditos_renovaciones";
	       $where2     = " id_creditos_nuevo = $_id_credito";
	       $id2        = " id_creditos_renovado";
	       $rsConsulta2= $Credito->getCondiciones($columnas2, $tablas2, $where2, $id2);
	       $_error = pg_last_error();
	       if(!empty($_error)){ throw new Exception(' error buscando credito renovados ['.$_id_credito.'] ');}
	       if(!empty($rsConsulta2)){
	           foreach ( $rsConsulta2 as $res){
	               /** agregar comprobantes al array de comprobates **/
	               $_id_comprobante_sub = $res->id_ccomprobantes;
	               array_push($arrayComprobantes, $_id_comprobante_sub);
	           }
	       }
	       /** recorrer el array de comprobantes **/
	       for ($i=0; $i<sizeof($arrayComprobantes); $i++){
	           
	           $this->RevertirComprobanteMayor($arrayComprobantes[$i]);	           
	       }
	       
	       return "OK";
	        
	    } catch (Exception $e) {
	           
	        return $e->getMessage();
	    }
	     
	}
	/**
	 * fn que permite revertir los valores ya cambiaron en el mayor 
	 */
	private function RevertirComprobanteMayor($id_comprobante){
	    
	    $Credito = new CreditosModel();
        
	    /** enviar parametros para la actualizacion **/
	    $funcion   = "core_regresa_valor_comprobante"; 
	    $parametros= "$id_comprobante";
        $queryFuncion   = $Credito->getconsultaPG($funcion, $parametros);
        $Credito->llamarconsultaPG($queryFuncion);
        $error = pg_last_error();
        if(!empty($error)){ throw new Exception('comprobante no reversado ['.$id_comprobante.'] ');}
	    
	    
	}
	
	/******************************************************** TERMINA PROCESOS DE ANULAR REPORTES CREDITOS ****************************************/
}
?>