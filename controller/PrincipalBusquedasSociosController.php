<?php

class PrincipalBusquedasSociosController extends ControladorBase{

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
	    
	    $nombre_controladores = "PrincipalBusquedaSocios";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $busquedas->getPermisosVer(" controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso al proceso de Generar el Archivo de pago"
	            
	        ));
	        exit();
	    }
	    
	    //buscar el estado civil
	    $colCivil    = " id_estado_civil_participes,nombre_estado_civil_participes ";
	    $tabCivil    = " public.core_estado_civil_participes ";
	    $wheCivil    = " 1 = 1";
	    $rsCivil     = $busquedas->getCondicionesSinOrden($colCivil, $tabCivil, $wheCivil, "");
	    
	    //buscar el Genero
	    $colGen    = " id_genero_participes, nombre_genero_participes ";
	    $tabGen    = " public.core_genero_participes ";
	    $wheGen    = " 1 = 1";
	    $rsGen     = $busquedas->getCondicionesSinOrden($colGen, $tabGen, $wheGen, "");
	    
	    $datos = null;
	    $datos['rsCivil'] = $rsCivil;
	    $datos['rsGen']   = $rsGen;
	    
	    $this->view_principal("PrincipalPagoAportes",$datos);
	
	}
	
	public function CargaDatosParticipe(){
	    
	    $busquedas = new PrincipalBusquedasModel();
	    $resp  = null;
	    
	    try {
	        
	        $id_participes = $_POST['id_participes'];
	        
	        if( !empty( error_get_last() ) ){ throw new Exception("Variable no recibida"); }
	        
	        $col1  = " aa.id_participes ,aa.cedula_participes ,aa.nombre_participes ,aa.apellido_participes ,bb.id_entidad_patronal ,bb.nombre_entidad_patronal";
	        $tab1  = " core_participes aa
	        INNER JOIN core_entidad_patronal bb ON bb.id_entidad_patronal = aa.id_entidad_patronal ";
	        $whe1  = " aa.id_participes = $id_participes ";
	        $rsConsulta1   = $busquedas->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	        
	        $resp['dataParticipe'] = ( empty($rsConsulta1) ) ? null : $rsConsulta1;
	        
	        $col2  = " id_contribucion_categoria ,nombre_contribucion_categoria ";
	        $tab2  = " public.core_contribucion_categoria ";
	        $whe2  = " nombre_contribucion_categoria = 'APORTES PERSONALES' ";
	        $rsConsulta2   = $busquedas->getCondicionesSinOrden($col2, $tab2, $whe2, "");
	        
	        $resp['dataContribucion'] = ( empty($rsConsulta2) ) ? null : $rsConsulta2;
	        
	        $id_contribucion_categoria = $rsConsulta2[0]->id_contribucion_categoria;
	        $col3  = " id_contribucion_tipo ,nombre_contribucion_tipo ";
	        $tab3  = " public.core_contribucion_tipo ";
	        $whe3  = " id_contribucion_categoria  = $id_contribucion_categoria
	        AND UPPER(nombre_contribucion_tipo ) = 'APORTE PERSONAL'";
	        $rsConsulta3   = $busquedas->getCondicionesSinOrden($col3, $tab3, $whe3, "");
	        
	        $resp['dataTipoAporte'] = ( empty($rsConsulta3) ) ? null : $rsConsulta3;
	        
	        $col4  = " aa.id_contribucion_tipo_participes ,aa.porcentaje_contribucion_tipo_participes ,aa.sueldo_liquido_contribucion_tipo_participes,
            aa.valor_contribucion_tipo_participes ,bb.id_tipo_aportacion ,bb.nombre_tipo_aportacion ,cc.id_contribucion_tipo ,cc.nombre_contribucion_tipo ";
	        $tab4  = " core_contribucion_tipo_participes aa
	        INNER JOIN core_tipo_aportacion bb ON bb.id_tipo_aportacion = aa.id_tipo_aportacion
	        INNER JOIN core_contribucion_tipo cc ON cc.id_contribucion_tipo  = aa.id_contribucion_tipo ";
	        $whe4  = " cc.id_contribucion_categoria = $id_contribucion_categoria
	        and aa.id_participes = $id_participes";
	        $rsConsulta4   = $busquedas->getCondicionesSinOrden($col4, $tab4, $whe4, "");
	        
	        $resp['dataContribucionParticipe'] = ( empty($rsConsulta4) ) ? null : $rsConsulta4;
	        
	        $col5  = " id_tipo_ingresos_contribucion ,nombre_tipo_ingresos_contribucion ";
	        $tab5  = " public.core_tipo_ingresos_contribucion ";
	        $whe5  = " 1=1 ";
	        $rsConsulta5   = $busquedas->getCondicionesSinOrden($col5, $tab5, $whe5, "");
	        
	        $resp['dataTipoIngresos'] = ( empty($rsConsulta5) ) ? null : $rsConsulta5;
	        
	        $col6  = " aa.anio_periodo , aa.mes_periodo ";
	        $tab6  = " con_periodo aa
	        INNER JOIN estado bb ON bb.id_estado = aa.id_estado ";
	        $whe6  = " bb.nombre_estado = 'ABIERTO' ";
	        $rsConsulta6   = $busquedas->getCondicionesSinOrden($col6, $tab6, $whe6, "");
	        	        
	        $resp['dataPeriodo'] = ( empty($rsConsulta6) ) ? null : $rsConsulta6;
	        
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
	
	/***
	 * @desc function para dibujar bancos locales de la entidad por medio de request ajax
	 * @param none
	 * @author dc 2020/04/13
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
	
	
	public function IngresaRegistroAporte(){
	     
	    $participes = new ParticipesModel();
	    $resp  = null;
	    
	    try {
	        
	        $id_participes             = $_POST['id_participes'];
	        $file_imagen_registro      = $_FILES['imagen_registro'];
	        $numero_documento_registro = $_POST['numero_documento_registro'];
	        $id_tipo_aportacion        = $_POST['id_tipo_aportacion'];
	        $id_contribucion_tipo      = $_POST['id_contribucion_tipo'];
	        $ultimo_sueldo_registro    = $_POST['ultimo_sueldo_registro'];
	        $valor_calculado_registro  = $_POST['valor_calculado_registro'];
	        $valor_aporte_registro     = $_POST['valor_aporte_registro'];
	        $observacion_registro      = $_POST['observacion_registro'];
	        $fecha_contable_registro   = $_POST['fecha_contable_registro'];
	        $fecha_transaccion_registro= $_POST['fecha_transaccion_registro'];
	        $id_tipo_transaccion       = $_POST['id_tipo_transaccion'];
	        $id_tipo_ingresos_contribucion     = $_POST['id_tipo_ingresos_contribucion'];
	        $id_bancos_registro        = $_POST['id_bancos_registro'];
	        $id_entidad_patronal       = $_POST['id_entidad_patronal'];
	        
	        //tomar variables de la session
	        if( !isset($_SESSION)){
	            session_start();
	        }
	        
	        $id_usuarios       = $_SESSION['id_usuarios'];
	        $usuario_usuarios  = $_SESSION['usuario_usuarios'];
	        
	        if( !empty( error_get_last() ) ){ throw new Exception("Variable no recibida"); }
	        
	        $imagen_registro   = 'null';
	        if( $file_imagen_registro['tmp_name'] != "" ){
	            $directorio = $_SERVER['DOCUMENT_ROOT'].'/rp_c/fotografias_documentos/';
	            
	            $nombre    = $file_imagen_registro['name'];
	            //$tipo      = $_FILES['imagen_registro']['type'];
	            //$tamano    = $_FILES['imagen_registro']['size'];
	            
	            move_uploaded_file($file_imagen_registro['tmp_name'],$directorio.$nombre);
	            $data = file_get_contents($directorio.$nombre);
	            $imagen_registro = pg_escape_bytea($data);
	        }else{
	            
	            $directorio = dirname(__FILE__).'\..\view\images\usuario.jpg';	            
	            $imagen_registro   = is_file( $directorio ) ? pg_escape_bytea( file_get_contents( $directorio ) ) : "null";	            
	        }        
	        
	        $participes->beginTran();
	        
	        /** valores seteados **/
	        $id_estatus    = 1;
	        $valor_patronal_contribucion   = 0.00;
	        $id_ccomprobantes  = 'null';   //esta variable se llenara para realizar actualizado de la tabla
	        $id_estado_contribucion    = 1;
	        $descripcion_contribucion_registro = "REGISTO MANUAL";
	        $id_liquidacion    = 0;
	        $id_distribucion   = 0;
	        $tipo_descuento_contribucion   = 1; 
	        
	        $funcion    = "core_ins_contribucion";
	        
	        $parametros = "$id_participes,'$fecha_transaccion_registro',$valor_aporte_registro,$valor_patronal_contribucion,$id_estatus,'$descripcion_contribucion_registro',
            $id_ccomprobantes,$id_usuarios,'$usuario_usuarios',$id_contribucion_tipo,$id_estado_contribucion,$id_entidad_patronal,'$fecha_contable_registro',
            '$numero_documento_registro','$observacion_registro',$id_liquidacion,$id_distribucion,$tipo_descuento_contribucion";
	        
            $StringQuery    = $participes->getconsultaPG($funcion, $parametros);
            
            $resultado  = $participes->llamarconsultaPG($StringQuery);
            
            $id_contribucion    = $resultado[0];
            
            if( !empty( pg_last_error() )){ throw  new Exception("ERROR EN EL INSERTADO DE CONTIBUCION"); }
            
                        
            /** valores seteados para contribucion bancos **/
            $id_estatus_contribucion    = 1;
            $fnContribucionBancos   = "core_ins_contribucion_bancos";            
            $paramContribucionBancos    = "$id_contribucion,$id_tipo_ingresos_contribucion,'$imagen_registro',$id_estatus_contribucion";            
            $sqContribucionBancos    = $participes->getconsultaPG($fnContribucionBancos, $paramContribucionBancos);
            
            $resultado  = $participes->llamarconsultaPG($sqContribucionBancos);
            
            $id_contribucion_bancos    = $resultado[0];
            
            if( !empty( pg_last_error() )){ throw  new Exception("ERROR EN EL INSERTADO DE CONTIBUCION-BANCOS"); }
	        
            $resp['estatus']    = "OK";
            $resp['icon']       = "success";
            $resp['mensaje']    = "Registro Manual Realizada";
            
	        $error_pg = pg_last_error();
	        if( !empty($error_pg) ){
	            throw new Exception( $error_pg );
	        }
	        
	        $participes->endTran("COMMIT");
	        
	    } catch (Exception $e) {
	        $buffer =  error_get_last();
	        $resp['icon'] = isset($resp['icon']) ? $resp['icon'] : "error";
	        $resp['mensaje'] = $e->getMessage();
	        $resp['msgServer'] = $buffer; //buscar guardar buffer y guaradr en variable
	        $resp['estatus'] = "ERROR";
	        $participes->endTran();
	    }
	    
	    error_clear_last();
	    if (ob_get_contents()) ob_end_clean();
	    
	    echo json_encode($resp);
	}
	
}
?>