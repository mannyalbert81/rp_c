<?php

class TransferenciasController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
	    
		$entidad = new CoreEntidadPatronalModel();
		$CuentasPagar = new CuentasPagarModel();
		
		require_once 'core/DB_Functions.php';
		$db = new DB_Functions();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    exit();
		}
		
		if( !isset($_GET['id_cuentas_pagar']) ){
		    
		    $this->redirect("Pagos","index");
		    exit();
		}
		
		$nombre_controladores = "GenerarTranferencias";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $entidad->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Empleo"
		        
		    ));
		    exit();
		}
		
		$_id_cuentas_pagar = $_GET['id_cuentas_pagar'];
		
		$datos=null;
		
		//buscar datos a tranferir
		$col1 = "aa.id_cuentas_pagar,aa.numero_cuentas_pagar,aa.id_tipo_documento,aa.descripcion_cuentas_pagar,
            aa.id_forma_pago,aa.total_cuentas_pagar,bb.id_ccomprobantes, cc.id_creditos, dd.id_participes,
            cc.numero_creditos,cc.saldo_actual_creditos, dd.cedula_participes, dd.nombre_participes,
            dd.apellido_participes,ee.id_lote, ee.nombre_lote";
		$tab1 = "tes_cuentas_pagar aa
        	INNER JOIN ccomprobantes bb
        	ON aa.id_ccomprobantes = bb.id_ccomprobantes
        	INNER JOIN core_creditos cc
        	ON cc.id_ccomprobantes = bb.id_ccomprobantes
        	INNER JOIN core_participes dd
        	ON dd.id_participes = cc.id_participes
        	INNER JOIN tes_lote ee
        	ON ee.id_lote = aa.id_lote";
		$whe1 = "aa.id_cuentas_pagar = $_id_cuentas_pagar";
		$id1 = "aa.id_cuentas_pagar"; 
		
		$rsConsulta1 = $CuentasPagar->getCondiciones($col1, $tab1, $whe1, $id1);
		
		//variables		
		$_numero_credito = !empty($rsConsulta1) ? $rsConsulta1[0]->numero_creditos : 0 ;
		
		//buscar en solicitud
		$colDb = " id_solicitud_prestamo, nombre_banco_cuenta_bancaria, numero_cuenta_cuenta_bancaria,tipo_cuenta_cuenta_bancaria ";
		$tabDb = " solicitud_prestamo ";
		$wheDb = " identificador_consecutivos='".$_numero_credito."'";
		$rsSolicitud = $db->getCondiciones($colDb, $tabDb, $wheDb);
		
		
		//print_r($RsSolicitud);
				
		$this->view_tesoreria("Transferencias",array(
		    "resultSet"=>$rsConsulta1,"rsSolicitud" => $rsSolicitud	
		));
			
	
	}
	
	public function GeneraTransferencia(){
	    
	    session_start();
	   
	    if(!isset($_POST)){
	        echo '<message>Variables no Identificadas <message>'; exit();
	    }
	    
	    $CuentasPagar = new CuentasPagarModel();
	    
	    //tomo datos de session
	    $_id_usuario = $_SESSION['id_usuarios'];
	    
	    //tomo datos de vista
	    $_lista_distribucion = json_decode($_POST['lista_distribucion']);	    
	    $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	    $_fecha_transferencia =  $_POST['fecha_transferencia'];
	    $_total_cuentas_pagar = $_POST['total_cuentas_pagar'];
	    $_tipo_cuenta_banco = $_POST['tipo_cuenta_banco'];
	    $_nombre_cuenta_banco = $_POST['nombre_cuenta_banco'];
	    $_numero_cuenta_banco = $_POST['numero_cuenta_banco'];
	    
	    $respuesta = false;

	    foreach ($_lista_distribucion as $data){
	        
	        $destino_distribucion = "";
	        if($data->tipo_pago == "debito"){
	            $destino_distribucion = "DEBE";
	        }else{  
	            $destino_distribucion = "HABER"; 
	        }
	        
	        $queryDistribucionPagos = "INSERT INTO tes_distribucion_pagos
	        (id_cuentas_pagar, id_plan_cuentas, fecha_distribucion_pagos, valor_distibucion_pagos, destino_distribucion_pagos)
	        VALUES('$_id_cuentas_pagar', '$data->id_plan_cuentas' , '$_fecha_transferencia', $_total_cuentas_pagar, '$destino_distribucion')";	        
	        
	        $ResultDistribucionPagos = $CuentasPagar -> executeNonQuery($queryDistribucionPagos);
	       
	        if(!is_int($ResultDistribucionPagos) || $ResultDistribucionPagos <= 0 ){
	            $respuesta = false;
	            //$cuentasPagar->endTran('ROLLBACK');
	            break;
	            
	        }
	        
	    }
	    
	    //buscar datos a tranferir
	    $col1 = "aa.id_cuentas_pagar,aa.numero_cuentas_pagar,aa.id_tipo_documento,aa.descripcion_cuentas_pagar,
            aa.id_forma_pago,aa.total_cuentas_pagar,bb.id_ccomprobantes, cc.id_creditos, dd.id_participes,
            cc.numero_creditos,cc.saldo_actual_creditos, dd.cedula_participes, dd.nombre_participes,
            dd.apellido_participes,ee.id_lote, ee.nombre_lote, ff.numero_cuenta_proveedores, ff.id_tipo_cuentas, ff.id_bancos,
            ff.id_proveedores";
	    $tab1 = "tes_cuentas_pagar aa
        	INNER JOIN ccomprobantes bb
        	ON aa.id_ccomprobantes = bb.id_ccomprobantes
        	INNER JOIN core_creditos cc
        	ON cc.id_ccomprobantes = bb.id_ccomprobantes
        	INNER JOIN core_participes dd
        	ON dd.id_participes = cc.id_participes
        	INNER JOIN tes_lote ee
        	ON ee.id_lote = aa.id_lote
            INNER JOIN proveedores ff
            ON ff.id_proveedores = aa.id_proveedor";	    
	    $whe1 = "aa.id_cuentas_pagar = $_id_cuentas_pagar";
	    $id1 = "aa.id_cuentas_pagar";
	    
	    $rsConsulta1 = $CuentasPagar->getCondiciones($col1, $tab1, $whe1, $id1);
	    
	    //variables
	    $_numero_credito = !empty($rsConsulta1) ? $rsConsulta1[0]->numero_creditos : 0 ;
	    $_id_creditos = !empty($rsConsulta1) ? $rsConsulta1[0]->id_creditos : 0 ;
	    //esta variable esta relacionada a la tabla participes con proveedores
	    $_id_participes = !empty($rsConsulta1) ? $rsConsulta1[0]->id_proveedores : 0 ;
	    $_id_forma_pago = !empty($rsConsulta1) ? $rsConsulta1[0]->id_forma_pago : 0 ;
	    $_id_bancos = !empty($rsConsulta1) ? $rsConsulta1[0]->id_bancos : 0 ;
	    $_numero_cuenta_banco = !empty($rsConsulta1) ? $rsConsulta1[0]->numero_cuenta_proveedores : '' ;
	    $_id_tipo_cuenta_banco = !empty($rsConsulta1) ? $rsConsulta1[0]->id_tipo_cuentas : 0 ;
	    $_nombre_participes = !empty($rsConsulta1) ? $rsConsulta1[0]->nombre_participes : '' ;
	    $_apellidos_participes = !empty($rsConsulta1) ? $rsConsulta1[0]->apellido_participes : 0 ;
	    
	    $_id_tipo_cuenta_banco = ( $_id_tipo_cuenta_banco == " " || is_null($_id_tipo_cuenta_banco) ) ? 0 : $_id_tipo_cuenta_banco;
	    $_id_bancos = ( $_id_bancos == " " || is_null($_id_bancos) ) ? 0 : $_id_bancos;
	    
	    //para ingresar pago 
	    $funcionPago = "ins_tes_pagos";
	    $parametrosPago = "'$_id_cuentas_pagar',
            	        '$_id_creditos',
            	        '$_id_participes',
            	        null,
            	        '$_id_forma_pago',
            	        '$_fecha_transferencia',
            	        'TRANSFERENCIA',
            	        '0' ,
            	        '$_nombre_cuenta_banco',
            	        '$_numero_cuenta_banco',
                        '$_tipo_cuenta_banco',
            	        '$_id_tipo_cuenta_banco'";
	  
        $consultaPago = $CuentasPagar->getconsultaPG($funcionPago, $parametrosPago);        
       
        $ResulatadoPago = $CuentasPagar->llamarconsultaPG($consultaPago);
        
        $_id_pagos = (int)$ResulatadoPago[0];
        
        //Datos para Comprobante
        $_concepto_comprobante = " TRANSACCION DE ".$_nombre_cuenta_banco.". TRANSFERENCIA A .".$_nombre_participes." ".$_apellidos_participes.". DEL CREDITO $_numero_credito ";
        $valor_letras_pago = $CuentasPagar->numtoletras($_total_cuentas_pagar);
        $funcionComprobante = "tes_agrega_comprobante_pago_transferencia";
        $parametrosComprobante = "'$_id_usuario',
            	        '$_id_bancos',
            	        '$_id_cuentas_pagar',
            	        '$_id_participes',
            	        '$_id_forma_pago',
                        '$_total_cuentas_pagar',
                        '$valor_letras_pago',
            	        '$_fecha_transferencia',
            	        'TRANSFERENCIA',
            	        '$_numero_cuenta_banco' ,
            	        null,
            	        'PAGO CREDITO $_numero_credito ',
            	        'PAGO',
            	        null,
                        '$_concepto_comprobante'";
            	        
        $consultaComprobante = $CuentasPagar->getconsultaPG($funcionComprobante, $parametrosComprobante);
        $ResulatadoComprobante = $CuentasPagar->llamarconsultaPG($consultaComprobante);
        
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
        $_celular_mensaje = "0987968467";
        $_nombres_mensajes = $_nombre_participes." ".$_apellidos_participes;
        $_codigo_mensajes = str_replace(' ','_',$_numero_cuenta_banco.'-'.$_nombre_cuenta_banco);
        $_id_mensaje_mensajes = "22443";
        $this->comsumir_mensaje_plus($_celular_mensaje, $_nombres_mensajes, $_codigo_mensajes, $_id_mensaje_mensajes);
	   
	    echo json_encode(array('respuesta'=>1,'mensaje'=>'TRANSACCION REALIZADA'));
	    
	}
	
	public function indexCheque(){
	    
	    session_start();
	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    $_id_usuarios = (isset($_SESSION['id_usuarios'])) ? $_SESSION['id_usuarios'] : null;
	    
	    if( !isset($_GET['id_cuentas_pagar']) ){
	        
	        $this->redirect("Pagos","index");
	        exit();
	    }
	    	    
	    $_id_cuentas_pagar = $_GET['id_cuentas_pagar'];
	    
	    $datos=null;
	    $datos['id_cuentas_pagar'] = $_id_cuentas_pagar;
	    
	    $query = "SELECT l.id_lote, l.nombre_lote, cp.id_cuentas_pagar, cp.numero_cuentas_pagar, cp.descripcion_cuentas_pagar, cp.fecha_cuentas_pagar, 
                    cp.compras_cuentas_pagar, cp.total_cuentas_pagar, p.id_proveedores, p.nombre_proveedores, p.identificacion_proveedores,
                    b.id_bancos, b.nombre_bancos, m.id_moneda, m.signo_moneda || '-' || m.nombre_moneda AS moneda
                FROM tes_cuentas_pagar cp
                INNER JOIN tes_lote l        
                ON cp.id_lote = l.id_lote
                INNER JOIN proveedores p
                ON p.id_proveedores = cp.id_proveedor
                INNER JOIN tes_bancos b
                ON b.id_bancos = cp.id_banco
                INNER JOIN tes_moneda m
                ON m.id_moneda = cp.id_moneda
                WHERE 1 = 1
                AND cp.id_cuentas_pagar = $_id_cuentas_pagar ";
	    
	    $rsCuentasPagar = $cuentasPagar->enviaquery($query);
	    
	    // PARA BUSCAR CONSECUTIVO DE PAGO 
	    
	    $queryConsecutivo = "SELECT numero_consecutivos FROM consecutivos WHERE nombre_consecutivos = 'PAGOS' AND id_entidades = 1";
	    
	    $rsConsecutivos = $cuentasPagar->enviaquery($queryConsecutivo);
	    
	    //para buscar cheque
	    $queryBanco = "SELECT id_bancos, lpad(index_bancos::text,espacio_bancos,'0') numero_cheque 
                FROM tes_bancos ban
                INNER JOIN tes_cuentas_pagar cp
                ON ban.id_bancos = cp.id_banco
                WHERE id_cuentas_pagar = $_id_cuentas_pagar";
        
	    $rsBanco= $cuentasPagar->enviaquery($queryBanco);
	    
	    $this->view_tesoreria("GenerarCheque",array(
	        "resultSet"=>$rsCuentasPagar,"rsConsecutivos"=>$rsConsecutivos,"datos"=>$datos,"rsBanco"=>$rsBanco
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
	
	/***
	 * return: json
	 * title: editBancos
	 * fcha: 2019-04-22
	 */
	public function editEntidad(){
	    
	    session_start();
	    $entidad = new CoreEntidadPatronalModel();
	    $nombre_controladores = "CoreEntidadPatronal";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $entidad->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    	     
	    if (!empty($resultPer))
	    {
	        
	        
	        if(isset($_POST["id_entidad_patronal"])){
	            
	            $_id_entidad_patronal = (int)$_POST["id_entidad_patronal"];
	            
	            $query = "SELECT * FROM core_entidad_patronal WHERE id_entidad_patronal = $_id_entidad_patronal";

	            $resultado  = $entidad->enviaquery($query);	            
	           
	            echo json_encode(array('data'=>$resultado));	            
	            
	        }
	       	        
	        
	    }
	    else
	    {
	        echo "Usuario no tiene permisos-Editar";
	    }
	    
	}
	
	public function generaTxt(){
	    
	    $fecha = date('my');
	    $nombreArchivo = "CASH_PAGOS_".$fecha."txt";
	    $archivo = __DIR__.'\\..\\view\\tesoreria\\documentos\\transferencias\\'.$nombreArchivo;
	    //validar archivo si existe en directorio
	    
	    $CuentasPagar = new CuentasPagarModel();
	    $query = "SELECT * FROM public.tes_cuentas_pagar";
	    $rsCuentasPagar = $CuentasPagar->enviaquery($query);
	    if( file_exists($archivo)){
	        
	        if(!empty($rsCuentasPagar)){
	            
	            $file = fopen($archivo, "a");
	            
	            foreach ($rsCuentasPagar as $res){
	                fwrite($file, $res->id_cuentas_pagar ."\t");
	                fwrite($file, number_format((float)$res->total_cuentas_pagar, 2, '', '')."\t");
	                //fwrite($file, "Esto es una nueva linea de texto" ."\t");
	                fwrite($file, PHP_EOL);
	            }
	            
	            fclose($file);
	        }
	        
	        $file = fopen($archivo, "a");
	        
	        fwrite($file, "Esto es una nueva linea de texto" ."\t");
	        
	        fwrite($file, "Otra más" . PHP_EOL);
	        
	        fclose($file);
	        
	        $file = fopen($archivo, "r");
	        
	        while(!feof($file)) {
	            
	            echo fgets($file). "<br />";
	            
	        }
	        
	        fclose($file);
	        
	    }else{
	        
	        if(!empty($rsCuentasPagar)){
	            
	            $file = fopen($archivo, "a");
	            
	            foreach ($rsCuentasPagar as $res){
	                fwrite($file, $res->id_cuentas_pagar ."\t");
	                fwrite($file, number_format((float)$res->total_cuentas_pagar, 2, '', '')."\t");
	                //fwrite($file, "Esto es una nueva linea de texto" ."\t");
	                fwrite($file, PHP_EOL);
	            }
	            
	            fclose($file);
	        }
	        
	        $file = fopen($archivo, "a");
	        
	        fwrite($file, "Esto es una nueva linea de texto" ."\t");
	        
	        fwrite($file, "Otra más" . PHP_EOL);
	        
	        fclose($file);
	        
	        $file = fopen($archivo, "r");
	        
	        while(!feof($file)) {
	            
	            echo fgets($file). "<br />";
	            
	        }
	        
	        fclose($file);
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
	
}
?>