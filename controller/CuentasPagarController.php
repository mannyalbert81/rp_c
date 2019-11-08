<?php

class CuentasPagarController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	    
	    session_start();
	    
	    $CxPagar = new CuentasPagarModel();  
	    
	    if(empty($_SESSION)){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        return;
	    }
	    
	    $nombre_controladores = "Estados";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $CxPagar->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso a Pagos Manuales Cuentas x Pagar"	            
	        ));
	        exit();
	    }
	    
	    $this->view_tesoreria("EntradaTransaccion",array());
	    	    
	
	}
	/***
	 * dc 2019-04-24
	 * desc: ingresar cuentas por pagar
	 */
	public function CuentasPagarIndex(){
	    
	    session_start();
	    
	    $CxPagar = new CuentasPagarModel();
	    
	    if(empty($_SESSION)){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        exit();
	    }
	    
	    $nombre_controladores = "IngresoCuentasPagar";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $CxPagar->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso a Pagos Manuales Cuentas x Pagar"
	        ));
	        exit();
	    }
	    
	    $this->view_tesoreria("EntradaCuentasPagar",array());
	    
	}
	
	public function PagosManualesIndex(){
	    
	    session_start();
	    
	    $CxPagar = new CuentasPagarModel();
	    
	    if(empty($_SESSION)){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        return;
	    }
	    
	    $nombre_controladores = "EntradaPagosManuales";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $CxPagar->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso a Pagos Manuales Cuentas x Pagar"
	        ));
	        exit();
	    }
	    
	    $this->view_tesoreria("EntradaPagosManuales",array());        
	    
	}
	
	
	
	
	/***
	 * dc 2019-05-21
	 * return jason,
	 * desc: insertar una cuenta por pagar 
	 */
	public function InsertCuentasPagar(){
			
		session_start();
		$cuentasPagar = new CuentasPagarModel();
		
		$nombre_controladores = "IngresoCuentasPagar";
		
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $cuentasPagar->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
		
		if(empty($resultPer)){
		    
		    echo 'Usuario no tine Permisos Insertar Cuentas Pagar';
		    exit();
		}
		
		//toma de datos
		
		$_id_lote = (isset($_POST['id_lote'])) ? $_POST['id_lote'] : null;
		$_id_consecutivo = (isset($_POST['id_consecutivo'])) ? $_POST['id_consecutivo'] : null;
		$_id_cuentas_pagar = (isset($_POST['id_cuentas_pagar'])) ? $_POST['id_cuentas_pagar'] : null;
		$_id_tipo_documento = (isset($_POST['id_tipo_documento'])) ? $_POST['id_tipo_documento'] : null;
		$_id_proveedor = (isset($_POST['id_proveedor'])) ? $_POST['id_proveedor'] : null;
		$_id_bancos = (isset($_POST['id_bancos'])) ? $_POST['id_bancos'] : null;
		$_id_moneda = (isset($_POST['id_moneda'])) ? $_POST['id_moneda'] : null;
		$_descripcion_cuentas_pagar = (isset($_POST['descripcion_cuentas_pagar'])) ? $_POST['descripcion_cuentas_pagar'] : null;
		$_fecha_cuentas_pagar = (isset($_POST['fecha_cuentas_pagar'])) ? $_POST['fecha_cuentas_pagar'] : null;
		$_condiciones_pago_cuentas_pagar = (isset($_POST['condiciones_pago_cuentas_pagar'])) ? $_POST['condiciones_pago_cuentas_pagar'] : null;
		$_num_documento_cuentas_pagar = (isset($_POST['numero_documento'])) ? $_POST['numero_documento'] : null;
		$_num_ord_compra_cuentas_pagar = (isset($_POST['numero_ord_compra'])) ? $_POST['numero_ord_compra'] : null;
		$_metodo_envio_cuentas_pagar = (isset($_POST['metodo_envio_cuentas_pagar'])) ? $_POST['metodo_envio_cuentas_pagar'] : null;
		$_compra_cuentas_pagar = (isset($_POST['monto_cuentas_pagar'])) ? $_POST['monto_cuentas_pagar'] : null;
		$_desc_comercial = (isset($_POST['desc_comercial_cuentas_pagar'])) ? $_POST['desc_comercial_cuentas_pagar'] : null;
		$_flete_cuentas_pagar = (isset($_POST['flete_cuentas_pagar'])) ? $_POST['flete_cuentas_pagar'] : null;
		$_miscelaneos_cuentas_pagar = (isset($_POST['miscelaneos_cuentas_pagar'])) ? $_POST['miscelaneos_cuentas_pagar'] : null;
		$_impuesto_cuentas_pagar = (isset($_POST['impuesto_cuentas_pagar'])) ? $_POST['impuesto_cuentas_pagar'] : null;
		$_total_cuentas_pagar = (isset($_POST['total_cuentas_pagar'])) ? $_POST['total_cuentas_pagar'] : null;
		$_monto1099_cuentas_pagar = (isset($_POST['monto1099_cuentas_pagar'])) ? $_POST['monto1099_cuentas_pagar'] : null;
		$_efectivo_cuentas_pagar = (isset($_POST['efectivo_cuentas_pagar'])) ? $_POST['efectivo_cuentas_pagar'] : null;
		$_cheque_cuentas_pagar = (isset($_POST['cheque_cuentas_pagar'])) ? $_POST['cheque_cuentas_pagar'] : null;
		$_tarjeta_credito_cuentas_pagar = (isset($_POST['tarjeta_credito_cuentas_pagar'])) ? $_POST['tarjeta_credito_cuentas_pagar'] : null;
		$_condonaciones_cuentas_pagar = (isset($_POST['condonaciones_cuentas_pagar'])) ? $_POST['condonaciones_cuentas_pagar'] : null;
		$_saldo_cuentas_pagar = (isset($_POST['saldo_cuentas_pagar'])) ? $_POST['saldo_cuentas_pagar'] : null;
		
		//validar datos de dsitribucion de datos
		$query = "SELECT 1 FROM tes_distribucion_cuentas_pagar WHERE id_lote = $_id_lote ";
		
        $rsDistribucion = $cuentasPagar->enviaquery($query);
        
        if(!empty($rsDistribucion)){
            
            //validacion cuentas vacias en distribucion
            $query = "SELECT 1 FROM tes_distribucion_cuentas_pagar WHERE id_plan_cuentas is null AND id_lote = $_id_lote ";
            $rsDistribucionVacias = $cuentasPagar->enviaquery($query);
            
            if(!empty($rsDistribucionVacias)){
                
                echo  json_encode(array('error'=>'Cuentas no definidas en Distribucion Cuentas x Pagar'));
                exit();
            }
            
        }else{
            
            echo  json_encode(array('error'=>'No existe distribución de Cuentas'));
            exit();
        }
		
		//para tranformar a datos solicitdos por funcion postgresql a numeric
		$_compra_cuentas_pagar = ( is_numeric($_compra_cuentas_pagar)) ? $_compra_cuentas_pagar : 0.00;
		$_desc_comercial = ( is_numeric($_desc_comercial)) ? $_desc_comercial : 0.00;
		$_flete_cuentas_pagar = ( is_numeric($_flete_cuentas_pagar)) ? $_flete_cuentas_pagar : 0.00;
		$_miscelaneos_cuentas_pagar = ( is_numeric($_miscelaneos_cuentas_pagar)) ? $_miscelaneos_cuentas_pagar : 0.00;
		$_impuesto_cuentas_pagar = ( is_numeric($_impuesto_cuentas_pagar)) ? $_impuesto_cuentas_pagar : 0.00;
		$_total_cuentas_pagar = ( is_numeric($_total_cuentas_pagar)) ? $_total_cuentas_pagar : 0.00;
		$_monto1099_cuentas_pagar = ( is_numeric($_monto1099_cuentas_pagar)) ? $_monto1099_cuentas_pagar : 0.00;
		$_efectivo_cuentas_pagar = ( is_numeric($_efectivo_cuentas_pagar)) ? $_efectivo_cuentas_pagar : 0.00;
		$_cheque_cuentas_pagar = ( is_numeric($_cheque_cuentas_pagar)) ? $_cheque_cuentas_pagar : 0.00;
		$_tarjeta_credito_cuentas_pagar = ( is_numeric($_tarjeta_credito_cuentas_pagar)) ? $_tarjeta_credito_cuentas_pagar : 0.00;
		$_condonaciones_cuentas_pagar = ( is_numeric($_condonaciones_cuentas_pagar)) ? $_condonaciones_cuentas_pagar : 0.00;
		$_saldo_cuentas_pagar = ( is_numeric($_saldo_cuentas_pagar)) ? $_saldo_cuentas_pagar : 0.00;
		
		$_origen_cuentas_pagar  = "MANUAL";
		
		$funcion = "tes_ins_cuentas_pagar";
		$parametros = "
                    '$_id_lote',
                    '$_id_consecutivo',
                    '$_id_tipo_documento',
                    '$_id_proveedor',
                    '$_id_bancos',
                    '$_id_moneda',
                    '$_descripcion_cuentas_pagar',
                    '$_fecha_cuentas_pagar',
                    '$_condiciones_pago_cuentas_pagar',
                    '$_num_documento_cuentas_pagar',
                    '$_num_ord_compra_cuentas_pagar',
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
                    '$_id_cuentas_pagar'                    
                    ";
		
		$cuentasPagar->setFuncion($funcion);
		$cuentasPagar->setParametros($parametros);
		
		$resultado = $cuentasPagar->llamafuncionPG();
		
		
		if(is_null($resultado)){
		    
		    echo json_encode(array('error'=>'Error al insertar Cuenta por Pagar'));
		    exit();
		}
		
		//variable se llena con el resultado de informacion
		$_id_cuentas_pagar = $resultado[0];		
		
		//genero valores para crear cuenta contable
		$_id_usuario = (isset($_SESSION['id_usuarios'])) ?  $_SESSION['id_usuarios'] : null;
		//lote ya viene de cuentas por pagar
		//proveedor viene de cuentas por pagar
		$_retencion_ccomprobantes = '';
		//concepto viene a ser la descripcion cuentas pagar
		$_concepto_ccomprobantes = 'Cuentas por Pagar | '.$_descripcion_cuentas_pagar;
		$_valor_ccomprobantes = $_compra_cuentas_pagar;
		$_valor_letras_ccomprobantes  = $cuentasPagar->numtoletras($_compra_cuentas_pagar);
		$_fecha_ccomprobantes = $_fecha_cuentas_pagar;
		//buscar formas de pago
		$_id_forma_pago_ccomprobantes = 1;
		$_referencia_ccomprobantes = $_num_documento_cuentas_pagar;
		$_numero_cuenta_ccomprobantes = "";
		$_numero_cheque_ccomprobantes = "";
		$_observaciones_ccomprobantes = "";
		
		$funcion = "tes_agrega_comprobante_cuentas_pagar";
		
		$parametros = "
                    '$_id_usuario',
                    '$_id_lote',
                    '$_id_proveedor',
                    '$_retencion_ccomprobantes',
                    '$_concepto_ccomprobantes',
                    '$_valor_ccomprobantes',
                    '$_valor_letras_ccomprobantes',                    
                    '$_fecha_ccomprobantes',
                    '$_id_forma_pago_ccomprobantes',
                    '$_referencia_ccomprobantes',
                    '$_numero_cuenta_ccomprobantes',
                    '$_numero_cheque_ccomprobantes',
                    '$_observaciones_ccomprobantes'
                    ";
		
		
		$cuentasPagar->setFuncion($funcion);
		$cuentasPagar->setParametros($parametros);
		
		$resultadoccomprobantes = $cuentasPagar->llamafuncionPG();
				
		//print_r($resultadoccomprobantes);
		if((int)($resultadoccomprobantes[0]) == 0 || is_null($resultadoccomprobantes)){
		    
		    //realizar eliminacion de CXP si hay error
		    echo json_encode(array('error'=>"Error Actualizando comprobante"));
		    exit();
		}
		
		if(empty($resultadoccomprobantes) && empty($resultado) ){
		    echo json_encode(array('error'=>"Error al Registrar 'Cuenta por Pagar'"));
		    exit();
    		
		}else{
		    
		    /*actualizar cuentas pagar*/
		    $_id_comprobante = (int)($resultadoccomprobantes[0]);
		    $colvalCuentas = " id_ccomprobantes = $_id_comprobante";
		    $tabvalCuentas = " tes_cuentas_pagar";
		    $whevalCuentas = " id_cuentas_pagar = $_id_cuentas_pagar";
		    
		    $cuentasPagar ->ActualizarBy($colvalCuentas, $tabvalCuentas, $whevalCuentas);
		        
		    echo json_encode(array('respuesta'=>1,'mensaje'=>"Cuenta por Pagar Ingresada Correctamente"));
		    exit();
		}
		
		echo json_encode(array('error'=>"Revisar Datos Enviados"));	
		
	}
	
	
	/**
	 * mod: tesoreria
	 * title: cargar tablas de BD
	 * ajax: si
	 * dc:2019-04-18
	 */	
	public function cargaFormasPago(){
	    
	    $estados = null;
	    $estados = new EstadoModel();
	    
	    $query = " SELECT id_forma_pago,nombre_forma_pago FROM forma_pago ORDER BY nombre_forma_pago";
	    
	    $resulset = $estados->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
            echo json_encode(array('data'=>$resulset));
	       
	    }
	}
	
	
	/**
	 * mod: tesoreria
	 * title: cargaTipoDocumento  
	 * ajax: si
	 * dc:2019-05-09
	 * desc: carga todos los tipos de documento
	 */
	public function cargaTipoDocumento(){
	    
	    $tipoDocumento = null;
	    $tipoDocumento = new TipoDocumentoModel();
	    
	    $query = " SELECT id_tipo_documento, abreviacion_tipo_documento, nombre_tipo_documento 
                FROM public.tes_tipo_documento	    
                WHERE 1 = 1";
	    
	    $resulset = $tipoDocumento->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	/**
	 * mod: tesoreria
	 * title: cargar tablas de BD
	 * ajax: si
	 * dc:2019-04-18
	 */
	public function cargaBancos(){
	    
	    $estados = null;
	    $estados = new EstadoModel();
	    
	    $query = " SELECT id_bancos,nombre_bancos 
                FROM tes_bancos ban INNER JOIN estado ON ban.id_estado = estado.id_estado 
                WHERE estado.nombre_estado='ACTIVO' AND tabla_estado = 'tes_bancos' AND local_bancos = true AND pago_bancos_chequera = true";
	    
	    $resulset = $estados->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	/**
	 * mod: tesoreria
	 * title: cargar frecuencia_lote 
	 * ajax: si
	 * dc:2019-04-29
	 */
	public function cargaFrecuenciaLote(){
	    
	    $frecuenciaLote = null;
	    $frecuenciaLote = new FrecuenciaLoteModel();
	    
	    $query = " SELECT id_frecuencia_lote, nombre_frecuencia_lote
                FROM tes_frecuencia_lote
                ORDER BY creado";
	    
	    $resulset = $frecuenciaLote->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	/**
	 * mod: tesoreria
	 * title: cargar tablas de BD
	 * ajax: si
	 * dc:2019-04-18
	 */
	public function DevuelveConsecutivoCxP(){
	    
	    $consecutivos = null;
	    $consecutivos = new ConsecutivosModel();
	    
	    $query = "SELECT id_consecutivos, LPAD(valor_consecutivos::TEXT,espacio_consecutivos,'0') AS numero_consecutivos FROM consecutivos 
                WHERE id_entidades = 1 AND nombre_consecutivos='CxP'";
	    
	    $resulset = $consecutivos->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	/**
	 * mod: tesoreria
	 * title: cargar tablas de BD
	 * ajax: si
	 * dc:2019-04-22
	 */
	public function cargaMoneda(){
	    
	    $estados = null;
	    $estados = new EstadoModel();
	    
	    $query = " SELECT id_moneda,nombre_moneda,signo_moneda FROM tes_moneda ORDER BY creado";
	    
	    $resulset = $estados->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	
	
	/***
	 * return:html
	 * desc: traer datos de estados
	 * dc 2019-04-15
	 */
	public function consultaEstados(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $estados = new EstadoModel();
	    
	    $where_to="";
	    $columnas  = " id_estado, nombre_estado, tabla_estado, DATE(creado) creado";
	    
	    $tablas    = "public.estado";
	    
	    $where     = " 1 = 1";
	    
	    $id        = "estado.tabla_estado";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND ( nombre_estado ILIKE '".$search."%' OR tabla_estado ILIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet = $estados->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$estados->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_estados' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 15px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Nombre Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Tabla </th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Creado</th>';
	            
	            if($id_rol==1){
	                
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                
	            }
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 14px;">'.$i.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->tabla_estado.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->creado.'</td>';
	                
	                if($id_rol==1){
	                    
	                    $html.='<td style="font-size: 18px;">
                                <a onclick="editEstado('.$res->id_estado.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
	                    $html.='<td style="font-size: 18px;">
                                <a onclick="delEstado('.$res->id_estado.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
	                    
	                }
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaEstados").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay empleados registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	}
	
	public function paginate($reload, $page, $tpages, $adjacents, $funcion) {
	    
	    
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
	
	public function editEstado(){
	    
	    session_start();
	    $estados = new TipoActivosModel();
	    $nombre_controladores = "Estados";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $estados->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer))
	    {
	        
	        
	        if(isset($_POST["id_estado"])){
	            
	            $id_estado = (int)$_POST["id_estado"];
	            
	            $query = "SELECT * FROM estado WHERE id_estado = $id_estado";
	            
	            $resultado  = $estados->enviaquery($query);
	            
	            echo json_encode(array('data'=>$resultado));
	            
	        }
	        
	        
	    }
	    else
	    {
	        echo "Usuario no tiene permisos-Editar";
	    }
	    
	}
	
	/***
	 * return: json
	 * title: delEstados
	 * fcha: 2019-04-15
	 */
	public function delEstados(){
	    
	    session_start();
	    $estado = new EstadoModel();
	    $nombre_controladores = "Estados";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $estado->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer)){
	        
	        if(isset($_POST["id_estado"])){
	            
	            $id_tipo = (int)$_POST["id_estado"];
	            
	            $resultado  = $estado->eliminarBy(" id_estado ",$id_tipo);
	            
	            if( $resultado > 0 ){
	                
	                echo json_encode(array('data'=>$resultado));
	                
	            }else{
	                
	                echo $resultado;
	            }
	            
	            
	            
	        }
	        
	        
	    }else{
	        
	        echo "Usuario no tiene permisos-Eliminar";
	    }
	    
	    
	    
	}
	
	/***
	 * dc 2019-04-29
	 * agregar lote
	 * return json
	 * desc agrega un lote y devuelve valor trx
	 */
	public function generaLote(){
	    
	    session_start();
	    $lote = new LoteModel();
	    
	    //controlador IngresoCuentasPagar
	    
	    //valida usuario logeado  dc 2019-05-20
	    if(!isset($_SESSION['id_usuarios'])){
	        echo json_encode(array('error'=>'Session Caducada')) ;
	        exit();
	    }	    
	    //variables desde fn JS submit frm_genera_lote
	    //dc 2019-04-30
	    
	    $_id_usuarios = $_SESSION['id_usuarios']; 
	    
	    $_id_lote =(isset($_POST['id_lote'])) ? $_POST['id_lote'] : 0;
	    $_nombre_lote =(isset($_POST['nombre_lote'])) ? $_POST['nombre_lote'] : "";
	    $_descripcion_lote =(isset($_POST['decripcion_lote'])) ? $_POST['decripcion_lote'] : "";
	    $_id_frecuencia =(isset($_POST['id_frecuencia'])) ? $_POST['id_frecuencia'] : 0;	  
	    
	    if( $_id_lote > 0){  echo "lote no generado. "; return;}
	    
        $funcion = "tes_genera_lote";
        $parametros = "'$_nombre_lote','$_descripcion_lote', $_id_frecuencia , $_id_usuarios";
        
        $lote->setFuncion($funcion);
        $lote->setParametros($parametros);
        
        $resultado = $lote->llamafuncion();
        
        $respuesta = -1;
        
        if(!empty($resultado) && count($resultado) > 0 ){
            
            foreach ($resultado[0] as $k => $v){
                
                $respuesta = $v;
                
            }
        }
        
        if( $respuesta > 0 ){
            echo json_encode(array('valor' => $respuesta));
           return;
        }
        
        $pgError = pg_last_error();  
        
        echo "lote no generado. ".$pgError;
	  
	    
	}
	
	/***
	 * return:html
	 * desc: lista de impuestoa
	 * dc 2019-05-02
	 */
	public function listarImpuestos(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $estados = new EstadoModel();
	    
	    $where_to="";
	    $columnas  = " id_estado, nombre_estado, tabla_estado, DATE(creado) creado";
	    
	    $tablas    = "public.estado";
	    
	    $where     = " 1 = 1";
	    
	    $id        = "estado.tabla_estado";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND ( nombre_estado ILIKE '".$search."%' OR tabla_estado ILIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet = $estados->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$estados->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_estados' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 15px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Nombre Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Tabla </th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Creado</th>';
	            
	            if($id_rol==1){
	                
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                
	            }
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 14px;">'.$i.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->tabla_estado.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->creado.'</td>';
	                
	                if($id_rol==1){
	                    
	                    $html.='<td style="font-size: 18px;">
                                <a onclick="editEstado('.$res->id_estado.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
	                    $html.='<td style="font-size: 18px;">
                                <a onclick="delEstado('.$res->id_estado.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
	                    
	                }
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaEstados").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay empleados registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	}
	
	/***
	 * dc 2019-05-06
	 * desc: realiza carga de datos en modal (en select)
	 * return: json
	 */
	public function cargaModImpuestos(){
	    
	    $impuestos = null;
	    $impuestos = new ModeloModel();
	    
	    $query = " SELECT id_impuestos, nombre_impuestos
                FROM tes_impuestos
                ORDER BY nombre_impuestos";
	    
	    $resulset = $impuestos->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	/***
	 * dc 2019-05-06
	 * mod: Tesoreria
	 * desc: agregar impuesto a la cuenta por pagar
	 */
	public function ModAgregaImpuestos(){
	    
	    session_start();
	    $impuestosCxP = new ModeloModel();
	    $impuestosCxP->setTable("tes_cuentas_pagar_impuestos");
	    
	    $nombre_controladores = "ImpuestosCxP";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $impuestosCxP->getPermisosEditar(" controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	        
	        echo  json_encode(array('error'=>'Usuario no tiene permisos'));
	        return;
	    }	    
	        
	    $_id_lote = (isset($_POST['id_lote'])) ? $_POST['id_lote'] : 0 ;
	    $_id_impuestos = (isset($_POST['id_impuestos'])) ? $_POST['id_impuestos'] : 0 ;
	    $_base_cxp_impuestos = (isset($_POST['base_impuestos'])) ? $_POST['base_impuestos'] : 0 ; 	    
      
        $funcion = "tes_ins_cuentas_pagar_impuestos";
        $parametros = "'$_id_impuestos','$_id_lote','$_base_cxp_impuestos'";
        
        $impuestosCxP->setFuncion($funcion);
        $impuestosCxP->setParametros($parametros);
	        
        @$resultado = $impuestosCxP->llamafuncionPG();
        
        if(empty($resultado)){
            
            //$error = pg_last_error();            
            //echo $error;
            echo 'Se genero un error';
            exit();
        }
        
        if(!is_int((int)$resultado[0])){
            
            echo "error en la conversion de respuesta Servidor";
            exit();
        }
        
        //se toma la respuesta de la funcion
        $respuesta = $resultado[0];
        
        $mensaje = ($respuesta == 0) ? "Impuesto ya se encuentra registrado" : (($respuesta > 0 ) ? "Impuesto agregado correctamente":"");
        
        //para devolver los resultados de ingresar un impuesto
        $arrayResultados = array();
        $query = " select 
    	(	select sum(cpi.valor_cuentas_pagar_impuestos) from tes_cuentas_pagar_impuestos cpi
        	inner join tes_impuestos imp 
        	on cpi.id_impuestos = imp.id_impuestos
        	where  ( imp.tipo_impuestos = 'retencion' or imp.tipo_impuestos = 'retencionIva' )
        	and cpi.id_lote = $_id_lote
    	) as suma_retenciones ,
    	(	select sum(cpi.valor_cuentas_pagar_impuestos) from tes_cuentas_pagar_impuestos cpi
        	inner join tes_impuestos imp 
        	on cpi.id_impuestos = imp.id_impuestos
        	where  imp.tipo_impuestos = 'iva'
        	and cpi.id_lote = $_id_lote
    	) as suma_impuestos";
        
        $rsResultados = $impuestosCxP->enviaquery($query);
        
        if( !empty($rsResultados)){
            
            $resultadoImp = floatval( $rsResultados[0]->suma_impuestos );
            $resultadoRet = floatval( $rsResultados[0]->suma_retenciones );
            $resultadoCuenta = $_base_cxp_impuestos + $resultadoRet + $resultadoImp;
            
            $resultadoImpuestos = $resultadoImp + $resultadoRet ;
            
            $arrayResultados['impuestos'] = $resultadoImpuestos;
            $arrayResultados['saldo'] = $resultadoCuenta;
            
        }
        
        echo json_encode(array('respuesta'=> 1 , 'valor' => $respuesta, 'mensaje' => $mensaje, 'resultados' => $arrayResultados ));
	    
	}
	
	/***
	 * dc 2019-05-07
	 * mod: tesoreria
	 * desc: enlista los impuests que van a una cuenta por pagar
	 */
	public function modListaImpuestosCxP(){
	    
	    $impuestosCxP = new ModeloModel();
	    $impuestosCxP->setTable("tes_cuentas_pagar_impuestos");
	    
	    $id_lote = (isset($_POST['id_lote'])) ? $_POST['id_lote'] : 0;
	    
	    $columnas = "cpi.id_cuentas_pagar_impuestos, pn.codigo_plan_cuentas, imp.nombre_impuestos, imp.porcentaje_impuestos
	       ,cpi.base_cuentas_pagar_impuestos, cpi.valor_cuentas_pagar_impuestos, DATE(cpi.creado) AS creado";
	    
	    $tablas = "tes_cuentas_pagar_impuestos cpi
            INNER JOIN tes_impuestos imp 
            ON cpi.id_impuestos = imp.id_impuestos
            INNER JOIN plan_cuentas pn
            ON pn.id_plan_cuentas = imp.id_plan_cuentas";
	    
	    $where = "1 = 1 AND cpi.id_lote = $id_lote ";
	    
	    $id = " cpi.creado";
	    
	    $where_to = "";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND ( nombre_estado ILIKE '".$search."%' OR tabla_estado ILIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet = $impuestosCxP->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet = $impuestosCxP->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:200px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_impuestos_cxp' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 11px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Codigo Cta.</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Nombre Imp. </th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">%</th>';	            
	            $html.='<th style="text-align: left;  font-size: 11px;">Base</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Valor Imp.</th>';   
                $html.='<th style="text-align: left;  font-size: 11px;"></th>';	           
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->codigo_plan_cuentas.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_impuestos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->porcentaje_impuestos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->base_cuentas_pagar_impuestos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->valor_cuentas_pagar_impuestos.'</td>';
                    $html.='<td style="font-size: 15px;">
                            <a onclick="delImpuestosCxP('.$res->id_cuentas_pagar_impuestos.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
	               
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"modListaImpuestosCxP").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            
	            
	        }
	        
	        
	        echo $html;
	        
	    }
	    
	}
	
	/***
	 * dc 2019-05-07
	 * mod: tesoreria
	 * desc: eliminar regitro de impuestos referenciados a ctas x pagar
	 */
	public function modDelImpuestosCxP(){
	    
	    session_start();
	    
	    $impuestosCxP = new CuentasPagarImpuestosModel();
	    	    
	    $nombre_controladores = "ImpuestosCxP";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $impuestosCxP->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer)){
	        
	        if(isset($_POST["id_cuentas_pagar_impuestos"])){
	            
	            $id_cuentas_pagar_cxp = (int)$_POST["id_cuentas_pagar_impuestos"];
	            $_id_distribucion_cuentas_pagar = 0;
	            
	            $queryBuscaIdDistribucion = "SELECT imp.id_impuestos, cpi.id_lote, pc.id_plan_cuentas, dcp.id_distribucion_cuentas_pagar
                    FROM tes_cuentas_pagar_impuestos cpi
                    INNER JOIN tes_impuestos imp
                    ON cpi.id_impuestos = imp.id_impuestos
                    INNER JOIN plan_cuentas pc
                    ON pc.id_plan_cuentas = imp.id_plan_cuentas
                    INNER JOIN tes_distribucion_cuentas_pagar dcp
                    ON dcp.id_lote = cpi.id_lote
                    AND dcp.id_plan_cuentas = imp.id_plan_cuentas
                    WHERE cpi.id_cuentas_pagar_impuestos = $id_cuentas_pagar_cxp ";
	            
	            $rs_ImpuestoDistribucion = $impuestosCxP->enviaquery($queryBuscaIdDistribucion);
	            
	            if(!empty($rs_ImpuestoDistribucion))	            
	               $_id_distribucion_cuentas_pagar = $rs_ImpuestoDistribucion[0]->id_distribucion_cuentas_pagar;	            
	            
	            $resultado  = $impuestosCxP->eliminarBy(" id_cuentas_pagar_impuestos ",$id_cuentas_pagar_cxp);
	            
	            if( (int)$resultado > 0 ){
	                
	                $queryEliminacion = " DELETE FROM tes_distribucion_cuentas_pagar WHERE id_distribucion_cuentas_pagar = $_id_distribucion_cuentas_pagar ";
	                
	                $rs_eliminarDistribucion = $impuestosCxP->enviarNonQuery($queryEliminacion);
	                
	                echo json_encode(array('data'=>$resultado));
	                
	            }else{
	                
	                echo $resultado;
	            }
	            
	            
	        }
	        
	        
	    }else{
	        
	        echo "Usuario no tiene permisos-Eliminar";
	    }
	    
	    
	    
	}
	
	/***
	 * dc 2019-05-13
	 * desc: para generar dsitribucion
	 * title: generaDistribucion
	 */
	public function generaDistribucion(){
	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    $id_lote = ( isset($_POST['id_lote']) ) ? $_POST['id_lote'] : 0;
	    
	    $base_compra = ( isset($_POST['monto_cuentas_pagar']) ) ? $_POST['monto_cuentas_pagar'] : 0;
	     
	    if($id_lote == 0 || $id_lote == "" ){
	        echo 'datos no enviados';
	        exit();
	    }
	    
	    $funcion = "tes_ins_distribucion_cuentas_pagar";
	    $parametros = "'$id_lote', '$base_compra'";
	    
	    $cuentasPagar->setFuncion($funcion);
	    $cuentasPagar->setParametros($parametros);
	    
	    $resultado = $cuentasPagar->llamafuncionPG();
	    
	    echo json_encode($resultado[0]);
	    
	}
	
	/***
	 * dc 2019-05-13
	 * desc: para listar distribucion
	 * title: listarDistribucion
	 */
	public function listaDistribucion(){
	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    session_start();
	    $id_rol = $_SESSION["id_rol"];
	    
	    $_id_lote =  (isset($_REQUEST['id_lote']) && $_REQUEST['id_lote'] != NULL) ? $_REQUEST['id_lote']: 0;
	        
	    $where_to = "";
	    
	    $columnas  = "dis.id_distribucion_cuentas_pagar, dis.id_lote, pc.id_plan_cuentas, pc.codigo_plan_cuentas, pc.nombre_plan_cuentas,
            dis.tipo_distribucion_cuentas_pagar, round(dis.debito_distribucion_cuentas_pagar,2) AS debito_distribucion,
            round(dis.credito_distribucion_cuentas_pagar,2) AS credito_distribucion, dis.ord_distribucion_cuentas_pagar";
	    
	    $tablas    = "tes_distribucion_cuentas_pagar dis
                LEFT JOIN plan_cuentas pc
                ON dis.id_plan_cuentas = pc.id_plan_cuentas";
	    
	    $where     = " dis.id_lote = $_id_lote ";
	    
	    $id        = "dis.ord_distribucion_cuentas_pagar";	    
	    
	    $action = (isset($_REQUEST['peticion']) && $_REQUEST['peticion'] != NULL) ? $_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search']) && $_REQUEST['search'] != NULL) ? $_REQUEST['search']:'';
	    
	    //declaracion de variable de salida 
	    $html = ""; 
	    
	    if( $action != "ajax" ){
	        echo $html;
	        exit();
	    }
	    
	    if(empty($search)){
	        $where_to=$where;
	    }else{
	        
	        $where_to=$where;
	        
	    }
	    
	    
	    
	    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet = $cuentasPagar->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	    
	    if(empty($resultSet)){
	        
	        $html = '';	        
	        echo $html;
	        exit();
	    }
	    
	    $cantidadResult=count($resultSet);
	    
	    if( $cantidadResult <= 0 ){
	        
	        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	        $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	        $html.='<h4>Aviso!!!</h4> <b></b>';
	        $html.='</div>';
	        $html.='</div>';
	        
	        echo $html;
	        exit();
	    }
	    
        $total_pages = ceil($cantidadResult/$per_page);
	            
        $html.='<div class="pull-left" style="margin-left:15px;">';
        $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
        $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
        $html.='</div>';
        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
        $html.='<section style="height:200px; overflow-y:scroll;">';
        $html.= "<table id='tabla_estados' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
        $html.= "<thead>";
        $html.= "<tr>";
        $html.='<th style="text-align: left;  font-size: 13px;">#</th>';
        $html.='<th style="text-align: left;  font-size: 13px;">Referencia</th>';
        $html.='<th style="text-align: left;  font-size: 13px;">Codigo Cuenta</th>';
        $html.='<th style="text-align: left;  font-size: 13px;">Nombre</th>';
        $html.='<th style="text-align: left;  font-size: 13px;">Tipo </th>';
        $html.='<th style="text-align: left;  font-size: 13px;">debito</th>';
        $html.='<th style="text-align: left;  font-size: 13px;">credito</th>';	        
        $html.='</tr>';
        
        $html.='</thead>';
        $html.='<tbody>';	            
	            
        $i=0;
        
        //select para tipo
        $selectHtml = '<select><option value="PAGOS">PAGOS</option><option value="PAGOS">PAGOS</option></select>';
        //no implementado
        
        
        foreach ($resultSet as $res)
        {
            $i++;
            $html.='<tr id="tr_'.$res->id_distribucion_cuentas_pagar.'">';
            $html.='<td style="font-size: 12px;">'.$i.'</td>'; 
            $html.='<td style="font-size: 12px;"><input type="text" class="form-control input-sm distribucion" name="mod_dis_referencia" value=""></td>';
            $html.='<td style="font-size: 12px;"><input type="text" class="form-control input-sm distribucion distribucion_autocomplete" name="mod_dis_codigo" value="'.$res->codigo_plan_cuentas.'"></td>';
            $html.='<td style="font-size: 12px;"><input type="text" style="border: 0;" class="form-control input-sm" value="'.$res->nombre_plan_cuentas.'" name="mod_dis_nombre">
                    <input type="hidden" name="mod_dis_id_plan_cuentas" value="'.$res->id_plan_cuentas.'" ></td>';
            $html.='<td style="font-size: 12px;">'.$res->tipo_distribucion_cuentas_pagar.'</td>';
            $html.='<td style="font-size: 12px;">'.$res->debito_distribucion.'</td>';
            $html.='<td style="font-size: 12px;">'.$res->credito_distribucion.'</td>';
            $html.='</tr>';
            
           
        }    
	            
        $html.='</tbody>';
        $html.='</table>';
        $html.='</section></div>';
        $html.='<div class="table-pagination pull-right">';
        $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"ListaDistribucion").'';
        $html.='</div>';	      
	        
	    echo $html;
	        
	   
	}
	
	/***
	 * dc 2019-05-14
	 * 
	 */
	public function autompletePlanCuentas(){
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    //print_r($_REQUEST);
	    
	    if(isset($_GET['term'])){
	        
	        $codigo_plan_cuentas = $_GET['term'];
	        
	        $columnas = "id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas";
	        $tablas = "public.plan_cuentas";
	        $where = "codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND nivel_plan_cuentas > 3";
	        $id = "codigo_plan_cuentas ";
	        $limit = "LIMIT 10";
	        
	        $rsPlanCuentas = $planCuentas->getCondicionesPag($columnas,$tablas,$where,$id,$limit);
	        
	        $respuesta = array();
	        
	        if(!empty($rsPlanCuentas) ){
	            
	            foreach ($rsPlanCuentas as $res){
	                
	                $_cls_plan_cuentas = new stdClass;
	                $_cls_plan_cuentas->id = $res->id_plan_cuentas;
	                $_cls_plan_cuentas->value = $res->codigo_plan_cuentas;
	                $_cls_plan_cuentas->label = $res->codigo_plan_cuentas.' | '.$res->nombre_plan_cuentas;
	                $_cls_plan_cuentas->nombre = $res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cls_plan_cuentas;
	            }
	            
	            echo json_encode($respuesta);
	            
	        }else{
	            
	            echo '[{"id":"","value":"Cuenta No Encontrada"}]';
	        }
	        
	    }
	}
	
	public function InsertaDistribucion(){
	    
	    // se utiliza transacciones de pg para php 
	    //para multiples transacciones
	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    //validar respuesta
	    $respuesta = true;
	    
	    $cuentasPagar->beginTran();	
	    
	    $datos = json_decode($_POST['lista_distribucion']);	    
	   
	    foreach ($datos as $data){	        
	        
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
	    
	    if($respuesta){
	       $cuentasPagar->endTran('COMMIT');
	    }
	    
	    echo json_encode(array("respuesta"=>$respuesta));
	  
	}
	
	public function CambiarMontoCompra(){
	    
	    $cuentasPagar = new CuentasPagarModel();	    
	    $id_lote = (isset($_POST['id_lote'])) ? $_POST['id_lote'] : 0 ;
	    
	    if($id_lote ==0 ){
	        echo 'lote no identificado';
	    }
	    
	    $error = 0;
	    	   
	    $cuentasPagar->beginTran();
	    
	    $resultado = $cuentasPagar->eliminarByColumn("tes_cuentas_pagar_impuestos","id_lote",$id_lote);
	    
	    if(is_null($resultado)){
	        $error = 1;
	        $cuentasPagar->endTran('ROLLBACK');
	        echo 'error al cambiar monto' ;
	        exit();
	    }
	    
	    $resultado = $cuentasPagar->eliminarByColumn("tes_distribucion_cuentas_pagar","id_lote",$id_lote);
	    
	    if(is_null($resultado)){
	        $error = 1;
	        $cuentasPagar->endTran('ROLLBACK');
	        echo 'error al cambiar monto' ;
	        exit();
	    }	       
	    
	    if($error == 0){
	        
	        $cuentasPagar->endTran('COMMIT');
	        echo json_encode(array('respuesta'=>1));
	        
	    }else{
	        echo 'error al cambiar monto' ;
	    }
	    
	}
	
	public function CancelarCuentasPagar(){
	    
	    $cuentasPagar = new CuentasPagarModel();
	    $id_lote = (isset($_POST['id_lote'])) ? $_POST['id_lote'] : 0 ;
	    
	    if($id_lote ==0 ){
	        echo 'lote no identificado';
	    }
	    
	    $error = 0;
	    
	    $cuentasPagar->beginTran();
	    
	    $resultado = $cuentasPagar->eliminarByColumn("tes_cuentas_pagar_impuestos","id_lote",$id_lote);
	    
	    if(is_null($resultado)){
	        $error = 1;
	        $cuentasPagar->endTran('ROLLBACK');
	        echo 'error al cancelar' ;
	        exit();
	    }
	    
	    $resultado = $cuentasPagar->eliminarByColumn("tes_distribucion_cuentas_pagar","id_lote",$id_lote);
	    
	    if(is_null($resultado)){
	        $error = 1;
	        $cuentasPagar->endTran('ROLLBACK');
	        echo 'error al cancelar' ;
	        exit();
	    }
	    
	    if($error == 0){
	        
	        $cuentasPagar->endTran('COMMIT');
	        echo json_encode(array('respuesta'=>1));
	        
	    }else{
	        echo 'error al cancelar peticion' ;
	    }
	    
	}
	
	public function conexionview(){
	    
	    $variable = '1';
	    
	    if(!is_int($variable))
	        echo "es integrer";
	}
	
	public function ReporteIndex(){
	    
	    $cuentasPagar = new CuentasPagarModel();
	   
	    session_start();
	    
	    if(empty( $_SESSION)){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        return;
	    }
	    
	    $nombre_controladores = "ReportesTesoreria";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $cuentasPagar->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso Reporte de Cuentas Pagar"
	            
	        ));
	        exit();
	    }
	    
	    $this->view_tesoreria("CuentasPagar",array( ));
	    
	}
	
	public function nodatapdf($mensaje=""){
	    
	    $texto = ($mensaje=="") ? "Documento No Encontrado" : $mensaje;
	    
	    include dirname(__FILE__).'\..\view\fpdf\fpdf.php';
	    $pdf = new FPDF();
	    $pdf->AddPage();
	    $pdf->SetFont("Times", "B", 14);
	    $ancho = $pdf->GetPageWidth()-20;
	    $alto = $pdf->GetPageHeight()/3;
	    $pdf->Cell( $ancho, $alto,$texto,0,1,'C');
	    
	    $pdf->Output();
	}
	
	Public function Reporte_Cuentas_Por_Pagar(){
	    
	    $cuentasPagar = new CuentasPagarModel();
	    $entidades = new EntidadesModel();	
	    
	    session_start();
	    
	    $_id_lote = (isset($_GET['id_lote'])) ? $_GET['id_lote'] : null;
	    
	    $_id_cuentas_pagar = (isset($_GET['id_cuentas_pagar'])) ? $_GET['id_cuentas_pagar'] : null;
	    
	    if(!is_null($_id_lote)){
	        
	        $query = "SELECT id_cuentas_pagar FROM public.tes_cuentas_pagar where id_lote = $_id_lote LIMIT 1";
	        
	        $rs_Cuentas_pagar = $cuentasPagar->enviaquery($query);
	        
	        if(!empty($rs_Cuentas_pagar))
	            $_id_cuentas_pagar = $rs_Cuentas_pagar[0]->id_cuentas_pagar;
	    }
	    
	    if(is_null($_id_cuentas_pagar) ){
	        
	        $this->nodatapdf();
	            
	        exit();
	    }
	    
	    //PARA OBTENER DATOS DE LA EMPRESA	        
	    $datos_empresa = array();
	    $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
	    
	    if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0){
	        //llenar nombres con variables que va en html de reporte
	        $datos_empresa['NOMBREEMPRESA']=$rsdatosEmpresa[0]->nombre_entidades;
	        $datos_empresa['DIRECCIONEMPRESA']=$rsdatosEmpresa[0]->direccion_entidades;
	        $datos_empresa['TELEFONOEMPRESA']=$rsdatosEmpresa[0]->telefono_entidades;
	        $datos_empresa['RUCEMPRESA']=$rsdatosEmpresa[0]->ruc_entidades;
	        $datos_empresa['FECHAEMPRESA']=date('Y-m-d H:i');
	        $datos_empresa['USUARIOEMPRESA']=(isset($_SESSION['usuario_usuarios']))?$_SESSION['usuario_usuarios']:'';
	    }
	    
	    //NOTICE DATA
	    $datos_cabecera = array();
	    $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
	    $datos_cabecera['FECHA'] = date('Y/m/d');
	    $datos_cabecera['HORA'] = date('h:i:s');
	    
	    $datos_cuentas_pagar = array();
	    
	    $columnascxp = "id_cuentas_pagar, numero_cuentas_pagar, descripcion_cuentas_pagar, fecha_cuentas_pagar,		
                  numero_documento_cuentas_pagar, compras_cuentas_pagar, condonaciones_cuentas_pagar, 
                  saldo_cuenta_cuentas_pagar, descuento_comercial_cuentas_pagar, flete_cuentas_pagar, 
                  miscelaneos_cuentas_pagar,impuesto_cuentas_pagar, cp.id_tipo_documento, td.abreviacion_tipo_documento,
                  lo.id_lote, lo.nombre_lote, lo.descripcion_lote, lo.numero_lote, fre.nombre_frecuencia_lote, 
                  cp.id_proveedor, pro.nombre_proveedores, pro.identificacion_proveedores";
	    
	    $tablascxp = "public.tes_cuentas_pagar cp
                INNER JOIN tes_tipo_documento td
                ON cp.id_tipo_documento = td.id_tipo_documento
                INNER JOIN tes_lote lo
                ON lo.id_lote = cp.id_lote
                INNER JOIN proveedores pro
                ON pro.id_proveedores = cp.id_proveedor
                INNER JOIN tes_frecuencia_lote fre
                ON fre.id_frecuencia_lote = lo.id_frecuencia";
	    
	    $wherecxp = "cp.id_cuentas_pagar = $_id_cuentas_pagar ";
	    
	    $idcxp = "cp.id_cuentas_pagar";
	    
	    $rsDatosCxp = $cuentasPagar->getCondiciones($columnascxp, $tablascxp, $wherecxp, $idcxp);
	    
	    if(empty($rsDatosCxp)){
	        
	        $this->nodatapdf("Cuenta x pagar no encontrada");
	        exit();
	    }
	    
	    $datos_cuentas_pagar['NOMBRELOTE'] = $rsDatosCxp[0]->nombre_lote;
	    $datos_cuentas_pagar['DESCLOTE'] = $rsDatosCxp[0]->descripcion_lote;
	    $datos_cuentas_pagar['FRECUENCIA'] = $rsDatosCxp[0]->nombre_frecuencia_lote;
	    $datos_cuentas_pagar['NUMEROLOTE'] = $rsDatosCxp[0]->numero_lote;
	    $datos_cuentas_pagar['TIPODOCUMENTO'] = $rsDatosCxp[0]->abreviacion_tipo_documento;
	    $datos_cuentas_pagar['NUMEROCOMPROBANTE'] = $rsDatosCxp[0]->numero_cuentas_pagar;
	    $datos_cuentas_pagar['NUMERODOCUMENTO'] = $rsDatosCxp[0]->numero_documento_cuentas_pagar;
	    $datos_cuentas_pagar['FECHADOCUMENTO'] = $rsDatosCxp[0]->fecha_cuentas_pagar;
	    $datos_cuentas_pagar['IDEPROVEEDOR'] = $rsDatosCxp[0]->identificacion_proveedores;
	    $datos_cuentas_pagar['NOMBREPROVEEDOR'] = $rsDatosCxp[0]->nombre_proveedores;
	    $datos_cuentas_pagar['CONDONACIONES'] = $rsDatosCxp[0]->condonaciones_cuentas_pagar;
	    $datos_cuentas_pagar['SALDOCUENTA'] =number_format((float)$rsDatosCxp[0]->saldo_cuenta_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['COMPRAS'] = number_format((float)$rsDatosCxp[0]->compras_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['DESCCOMERCIAL'] = number_format((float)$rsDatosCxp[0]->descuento_comercial_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['FLETE'] = number_format((float)$rsDatosCxp[0]->flete_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['MISCELANEOS'] = number_format((float)$rsDatosCxp[0]->miscelaneos_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['IMPUESTO'] = number_format((float)$rsDatosCxp[0]->impuesto_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['DF'] = $rsDatosCxp[0]->descripcion_lote;
	    $datos_cuentas_pagar['GH'] = $rsDatosCxp[0]->nombre_frecuencia_lote;
	    $datos_cuentas_pagar['KL'] = $rsDatosCxp[0]->numero_lote;
	    
	    //DISTRIBUCION DE CONTABILIDAD
	    
	    $id_lote = $rsDatosCxp[0]->id_lote;
	    
	    $columnasDistribucion= "id_distribucion_cuentas_pagar, id_lote, pc.id_plan_cuentas, pc.codigo_plan_cuentas,
    		pc.nombre_plan_cuentas, tipo_distribucion_cuentas_pagar, 
    		debito_distribucion_cuentas_pagar,  credito_distribucion_cuentas_pagar";
	    
	    $tablasDistribucion = "tes_distribucion_cuentas_pagar dis
            inner join plan_cuentas pc 
            on dis.id_plan_cuentas = pc.id_plan_cuentas";
	    
	    $whereDistribucion = " dis.id_lote = $id_lote ";
	    
	    $idDistribucion = " dis.ord_distribucion_cuentas_pagar ";
	    
	    $rsdatosDistribucion = $cuentasPagar->getCondiciones($columnasDistribucion, $tablasDistribucion, $whereDistribucion, $idDistribucion);
	    
	    if( empty($rsdatosDistribucion) ){
	        
	        $this->nodatapdf("Distribucion No Realizada");
	        exit();
	        
	    }
	    
	    if(!empty($rsdatosDistribucion)){
	        
	        $tabladistribucion = "<table class=\"tab3datos\"> <caption> Distribuciones de Contabilidad </caption> ";
	        $sumaDebito = 0.00;
	        $sumaCredito = 0.00;
	        $tabladistribucion .= "<tr>";
	        $tabladistribucion .= "<th>Cuenta</th>";
	        $tabladistribucion .= "<th>Descripción Cuenta</th>";
	        $tabladistribucion .= "<th>Tipo de Cuenta</th>";
	        $tabladistribucion .= "<th>Monto débito</th>";
	        $tabladistribucion .= "<th>Monto crédito</th>";
	        $tabladistribucion .= "</tr>";	        
	                
	        foreach ($rsdatosDistribucion as $res){
	            $tabladistribucion .= "<tr>";
	            $tabladistribucion .= "<td>".$res->codigo_plan_cuentas."</td>";
	            $tabladistribucion .= "<td>".$res->nombre_plan_cuentas."</td>";
	            $tabladistribucion .= "<td>".$res->tipo_distribucion_cuentas_pagar."</td>";
	            $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$res->debito_distribucion_cuentas_pagar, 2, ',', '.')."</td>";
	            $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$res->credito_distribucion_cuentas_pagar, 2, ',', '.')."</td>";
	            $tabladistribucion .= "</tr>";
	            
	            $sumaCredito += $res->credito_distribucion_cuentas_pagar;
	            $sumaDebito += $res->debito_distribucion_cuentas_pagar;
	        }
	        
	        $tabladistribucion .= "<tr>";
	        $tabladistribucion .= "<td colspan=\"3\"></td>";
	        $tabladistribucion .= "<td class=\"decimales\" >----------------</td>";
	        $tabladistribucion .= "<td class=\"decimales\" >----------------</td>";
	        $tabladistribucion .= "</tr>";
	        
	        $tabladistribucion .= "<tr>";
	        $tabladistribucion .= "<td colspan=\"3\"></td>";
	        $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$sumaDebito, 2, ',', '.')."</td>";
	        $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$sumaCredito, 2, ',', '.')."</td>";
	        $tabladistribucion .= "</tr>";
	        
	        $tabladistribucion .= "</table>";
	    }
	    
	    $datos_cuentas_pagar['TABLADISTRIBUCION'] = $tabladistribucion;
	    
	    //DISTRIBUCION DETALLE IMPUESTOS
	   
	    $columnasImpuestos= "imp.id_impuestos, imp.nombre_impuestos, id_lote, base_cuentas_pagar_impuestos, valor_cuentas_pagar_impuestos";
	    
	    $tablasImpuestos = "public.tes_cuentas_pagar_impuestos icp 
                    INNER JOIN public.tes_impuestos imp
                    ON icp.id_impuestos = imp.id_impuestos";
	    
	    $whereImpuestos = " icp.id_lote = $id_lote ";
	    
	    $idImpuestos = " imp.id_impuestos ";
	    
	    $rsdatosImpuestos = $cuentasPagar->getCondiciones($columnasImpuestos, $tablasImpuestos, $whereImpuestos, $idImpuestos);
	    	    	    
	    if(!empty($rsdatosImpuestos)){
	        
	        $tablaImpuesto = "<table class=\"tab3datos\"> <caption> Distribuciones de detalle de impuestos </caption> ";
	        $sumaImpuesto = 0.00;
	        $tablaImpuesto .= "<tr>";
	        $tablaImpuesto .= "<th>Id. detalle impuesto</th>";
	        $tablaImpuesto .= "<th>Descripción detalle impuesto</th>";
	        $tablaImpuesto .= "<th>Monto impuesto</th>";
	        $tablaImpuesto .= "</tr>";
	        
	        foreach ($rsdatosImpuestos as $res){
	            $tablaImpuesto .= "<tr>";
	            $tablaImpuesto .= "<td>".$res->id_impuestos."</td>";
	            $tablaImpuesto .= "<td>".$res->nombre_impuestos."</td>";
	            $tablaImpuesto .= "<td class=\"decimales\" >$ ".number_format((float)$res->valor_cuentas_pagar_impuestos, 2, ',', '.')."</td>";
	            $tablaImpuesto .= "</tr>";
	            
	            $sumaImpuesto += $res->valor_cuentas_pagar_impuestos;
	        }
	        
	        $tablaImpuesto .= "<tr>";
	        $tablaImpuesto .= "<td colspan=\"2\"></td>";
	        $tablaImpuesto .= "<td class=\"decimales\" >----------------</td>";
	        $tablaImpuesto .= "</tr>";
	        
	        $tablaImpuesto .= "<tr>";
	        $tablaImpuesto .= "<td colspan=\"2\"></td>";
	        $tablaImpuesto .= "<td class=\"decimales\" >$ ".number_format((float)$sumaImpuesto, 2, ',', '.')."</td>";
	        $tablaImpuesto .= "</tr>";
	        
	        $tablaImpuesto .= "</table>";
	    }
	    
	    $datos_cuentas_pagar['TABLAIMPUESTOS'] = $tablaImpuesto;    
	  
	    
	    $this->verReporte("CuentasPagar", array('datos_cuentas_pagar'=>$datos_cuentas_pagar,'datos_empresa'=>$datos_empresa,'datos_cabecera'=>$datos_cabecera));
	   
	}
	
	public function ListaCuentasPagar(){
	    
	    $cuentasPagar = new CuentasPagarModel();	    
	    
	    $id_lote = (isset($_POST['id_lote'])) ? $_POST['id_lote'] : 0;
	    
	    $columnas = " id_cuentas_pagar, numero_cuentas_pagar, id_tipo_documento,
                    descripcion_cuentas_pagar, id_lote,  DATE(fecha_cuentas_pagar) fecha_cuentas_pagar, 
                    id_proveedor, condiciones_pago, id_banco, id_moneda, numero_documento_cuentas_pagar,
                    numero_orden_compra_cuentas_pagar, compras_cuentas_pagar";
	    
	    $tablas = "tes_cuentas_pagar ";
	    
	    $where = "1 = 1 ";
	    
	    $id = " fecha_cuentas_pagar";
	    
	    $where_to = "";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND ( numero_documento_cuentas_pagar LIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	        }
	        
	        $html="";
	        $resultSet = $cuentasPagar->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet = $cuentasPagar->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:200px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_impuestos_cxp' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 11px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Numero</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Documento </th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Fecha Ingreso</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Proveedor</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Banco</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Documento Num.</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Compra</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;"></th>';
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_cuentas_pagar.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->id_tipo_documento.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_cuentas_pagar.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->id_proveedor.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->id_banco.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_documento_cuentas_pagar.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->compras_cuentas_pagar.'</td>';
	                $html.='<td style="font-size: 15px;">
                            <a data-id="'.$res->id_cuentas_pagar.'"   href="#" class="btn btn-default input-sm showpdf" style="font-size:65%;" data-toggle="tooltip" title="Ver pdf"><i class="glyphicon glyphicon-print"></i></a></td>';
	                
	                $html.='</tr>';
	                
	            }
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"cargaCuentasPagar").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            
	            
	        }
	        
	        echo $html;
	        
	    }
	}
	
	/***
	 * dc 2019-06-06
	 * mod: Tesoreria
	 * desc: devolver resultados de la compra
	 */
	public function devolverResultados(){
	    
	    session_start();
	    $impuestosCxP = new ImpuestosModel();
	   
	    $_id_lote = (isset($_POST['id_lote'])) ? $_POST['id_lote'] : 0 ;	    
	    $_base_compra = (isset($_POST['base_compra'])) ? $_POST['base_compra'] : 0 ;
	   
	    //para devolver los resultados de ingresar un impuesto
	    $arrayResultados = array();
	    
	    $query = " SELECT
    	(	SELECT SUM(cpi.valor_cuentas_pagar_impuestos) FROM tes_cuentas_pagar_impuestos cpi
        	INNER JOIN  tes_impuestos imp
        	ON cpi.id_impuestos = imp.id_impuestos
        	WHERE  ( imp.tipo_impuestos = 'retencion' or imp.tipo_impuestos = 'retencionIva' )
        	AND cpi.id_lote = $_id_lote
    	) AS suma_retenciones ,
    	(	select sum(cpi.valor_cuentas_pagar_impuestos) from tes_cuentas_pagar_impuestos cpi
        	inner join tes_impuestos imp
        	on cpi.id_impuestos = imp.id_impuestos
        	where  imp.tipo_impuestos = 'iva'
        	and cpi.id_lote = $_id_lote
    	) AS suma_impuestos";
	    
	    $rsResultados = $impuestosCxP->enviaquery($query);
	    
	    if( !empty($rsResultados)){
	        
	        $resultadoImp = floatval( $rsResultados[0]->suma_impuestos );
	        $resultadoRet = floatval( $rsResultados[0]->suma_retenciones );
	        $resultadoCuenta = $_base_compra + $resultadoRet + $resultadoImp;
	        
	        $resultadoImpuestos = $resultadoImp + $resultadoRet ;
	        
	        $arrayResultados['impuestos'] = $resultadoImpuestos;
	        $arrayResultados['saldo'] = $resultadoCuenta;
	        
	    }
	    
	    echo json_encode(array( 'resultados' => $arrayResultados ));
	}
	
}
?>