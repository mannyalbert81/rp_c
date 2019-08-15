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
	    
	    //tomo datos de vista
	    $_lista_distribucion = json_decode($_POST['lista_distribucion']);	    
	    $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	    $_fecha_transferencia =  $_POST['fecha_transferencia'];
	    $_total_cuentas_pagar = $_POST['total_cuentas_pagar'];
	    $_tipo_cuenta_banco = $_POST['tipo_cuenta_banco'];
	    $_nombre_cuenta_banco = $_POST['nombre_cuenta_banco'];
	    $_numero_cuenta_banco = $_POST['numero_cuenta_banco'];

	    foreach ($_lista_distribucion as $data){	        
	        
	        $columnas = "id_plan_cuentas = ".$data->id_plan_cuentas.",
                        referencia_distribucion_cuentas_pagar = '".$data->referencia_distribucion."'";
	        
	        $tabla = "tes_distribucion_cuentas_pagar";
	        
	        $where = "id_distribucion_cuentas_pagar = '".$data->id_distribucion."' ";	        
	        
	        $actualizado = $cuentasPagar->editBy($columnas, $tabla, $where);	        
	        
	        if(!is_int($actualizado)){
	            $respuesta = false;
	            $cuentasPagar->endTran('ROLLBACK');
	            break;
	            
	        }
	        
	    }
	    
	    $nombreProcesos = "Pago Transferencia CxP";
	    $modulo = "TESORERIA";
	    
	    $queryCxP = "SELECT id_cuentas_pagar, id_proveedor, id_lote, fecha_cuentas_pagar, compras_cuentas_pagar, impuesto_cuentas_pagar, total_cuentas_pagar
                FROM tes_cuentas_pagar
                WHERE id_cuentas_pagar = $_id_cuentas_pagar";
	    
	    $rsCuentasPagar = $CuentasPagar->enviaquery($queryCxP);
	    
	    $_total_cuentas_pagar = $rsCuentasPagar[0]->total_cuentas_pagar;
	    $_id_proveedores = $rsCuentasPagar[0]->id_proveedor;
	    $_total_en_letras = $CuentasPagar->numtoletras($_total_cuentas_pagar);
	    
	    $queryFormaPago = "SELECT * FROM forma_pago WHERE nombre_forma_pago = 'CHEQUE' LIMIT 1";
	    $rsFormaPago = $CuentasPagar->enviaquery($queryFormaPago);
	    
	    $_id_formadePago = $rsFormaPago[0]->id_forma_pago;
	    
	    //para buscar el diario
	    $queryCabezaDiario = "SELECT cdtc.id_diario_tipo_cabeza
                    FROM core_diario_tipo_cabeza cdtc
                    INNER JOIN modulos m
                    ON cdtc.id_modulos = m.id_modulos
                    INNER JOIN core_tipo_procesos ctp
                    ON ctp.id_modulos = m.id_modulos
                    WHERE 1 = 1
                    AND m.nombre_modulos = '$modulo'
                    AND ctp.nombre_tipo_procesos = '$nombreProcesos'";
	    
	    $rsCabezaDiario = $CuentasPagar -> enviaquery($queryCabezaDiario);
	    
	    if(empty($rsCabezaDiario))
	        throw new Exception("No se puede identificar DIARIO CONTABLE de Pago");
	        
	        $_id_diario_tipo = $rsCabezaDiario[0]->id_diario_tipo_cabeza;
	        
	        
	        $funcion = "tes_agrega_comprobante_pago_cheque";
	        $parametros = "$_id_usuarios,$_id_bancos,$_id_cuentas_pagar,$_id_proveedores,$_id_formadePago,$_id_diario_tipo,$_total_cuentas_pagar,
                           '$_total_en_letras','$_fecha_cheque','$_numero_cheque','$_numero_cuenta_banco','$_numero_cheque',
                           '$_observaciones','$_transaccion','$_retencion','$_concepto'";
	        
	        $queryFuncion = "SELECT $funcion ( $parametros )";
	        
	        $rsComprobante = $CuentasPagar->llamarconsultaPG($queryFuncion);
	    
	    
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
}
?>