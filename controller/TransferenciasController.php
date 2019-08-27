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
		$col1 = "aa.total_cuentas_pagar, aa.origen_cuentas_pagar, aa.descripcion_cuentas_pagar, aa.id_estado, aa.id_forma_pago,
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
		
		//en caso de generar que no ingresa a la generacion de transferencia no tine todo los solicitado en el inner 
		
		if( empty($rsConsulta1) ){
		    
		    $this->redirect("Pagos","index");
		    exit();
		    
		}else{
		    
		    $id_proveedores = $rsConsulta1[0]->id_proveedores;
		    $id_tipo_cuenta = $rsConsulta1[0]->id_tipo_cuentas;
		    $identificacion_proveedores = $rsConsulta1[0]->identificacion_proveedores;
		    
		    $datosVista['id_lote'] = $rsConsulta1[0]->id_lote;
		    $datosVista['id_cuentas_pagar'] = $_id_cuentas_pagar;
		    $datosVista['descripcion'] = "";
		    $datosVista['id_proveedores'] = $id_proveedores;
		    $datosVista['identificacion_proveedores'] = $identificacion_proveedores;
		    $datosVista['total_cuentas_pagar'] = $rsConsulta1[0]->total_cuentas_pagar;
		    $datosVista['nombre_lote'] = $rsConsulta1[0]->nombre_lote;
		    
		    $nombre_tipo_proveedores = $rsConsulta1[0]->nombre_tipo_proveedores;		    
		    if( is_null($nombre_tipo_proveedores) || $nombre_tipo_proveedores == "PAGO PROVEEDORES" || $nombre_tipo_proveedores == "PAGO PROVEEDORES"  ){
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
		            $datosVista['nombre_banco'] = $rsConsulta2[0]->nombre_bancos;
		        }else{
		            $datosVista['nombre_banco'] = "";
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
		       
		        $col4 = "aa.nombre_participes,aa.apellido_participes,bb.numero_participes_cuentas,cc.nombre_tipo_cuentas,dd.nombre_bancos";
		        $tab4 = "core_participes aa
        		        LEFT JOIN core_participes_cuentas bb
        		        ON aa.id_participes = bb.id_participes
        		        AND bb.cuenta_principal = true
        		        LEFT JOIN core_tipo_cuentas cc
        		        ON cc.id_tipo_cuentas = bb.id_tipo_cuentas
        		        LEFT JOIN tes_bancos dd
        		        ON bb.id_bancos = dd.id_bancos";
		        $whe4 = "  aa.id_estatus = 1 AND cedula_participes = '$identificacion_proveedores'";
		        $id4 = "aa.id_participes";
		        $rsConsulta4 = $CuentasPagar->getCondiciones($col4, $tab4, $whe4, $id4);
		        if(!empty($rsConsulta4)){
		            $datosVista['nombre_beneficiario'] = $rsConsulta4[0]->nombre_participes;
		            $datosVista['apellido_beneficiario'] = $rsConsulta4[0]->apellido_participes;
		            $datosVista['numero_cuenta_banco'] = $rsConsulta4[0]->numero_participes_cuentas;
		            $datosVista['nombre_banco'] = $rsConsulta4[0]->nombre_bancos;
		            $datosVista['nombre_tipo_cuenta_banco'] = $rsConsulta4[0]->nombre_tipo_cuentas;
		        }else{
		            $datosVista['nombre_beneficiario'] = "";
		            $datosVista['apellido_beneficiario'] = "";
		            $datosVista['numero_cuenta_banco'] = "";
		            $datosVista['nombre_banco'] = "";
		            $datosVista['nombre_tipo_cuenta_banco'] = "";
		        }
		        
		        
		    }//aqui aumentar para mas opciones de transferencias
		    
		    //generar array de respuesta a vista
		    $resultset =  array((object)$datosVista);
		    
		    //print_r($resultset);
		    
		    $this->view_tesoreria("Transferencias",array(
		        "resultset"=>$resultset
		    ));
 		   
		}
		
	
	}
	
	public function GeneraTransferencia(){
	    
	    session_start();
	   
	    if(!isset($_POST)){
	        echo '<message>Variables no Identificadas <message>'; exit();
	    }
	    
	    $CuentasPagar = new CuentasPagarModel();	    
	   
	    //tomo datos de vista
	    $_lista_distribucion = json_decode($_POST['lista_distribucion']);	    
	    $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	    $_fecha_transferencia =  $_POST['fecha_transferencia'];	   
	    
	    $col1 = "origen_cuentas_pagar";
	    $tab1 = "tes_cuentas_pagar";
	    $whe1 = "id_cuentas_pagar = $_id_cuentas_pagar";
	    $id1 = "id_cuentas_pagar";
	    $rsConsulta1 = $CuentasPagar -> getCondiciones($col1, $tab1, $whe1, $id1);
	    
	    $validacion = false;
	    
	    if( !empty($rsConsulta1) ){
	        
	        //recibir respuesta con mensaje en error
	        switch ($rsConsulta1[0]->origen_cuentas_pagar){
	            
	            case "MANUAL":
	                $validacion = $this->transferirProveedor($_id_cuentas_pagar, $_fecha_transferencia, $_lista_distribucion);
	                break;
	            case "CREDITOS":
	                $validacion = $this->transferirParticipe($_id_cuentas_pagar, $_fecha_transferencia, $_lista_distribucion);
	                break;
	        }
	    }
	    
	    if($validacion['respuesta']){
	        echo json_encode(array('respuesta'=>1,'mensaje'=>'TRANSACCION REALIZADA'));
	        exit();
	    }else{
	        echo "<message>".$validacion['mensaje']."<message>";
	        exit();
	    }
        
	        
	 
	    
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
	            $_abrv_tipo_cuenta = "CORR";
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
	        $_celular_mensaje = "0987474892"; //para rpoduccion $_celular_participes
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
	
	
}
?>