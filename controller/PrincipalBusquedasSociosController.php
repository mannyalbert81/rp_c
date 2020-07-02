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
	 * 
	 */
	public function CargaDatosAportes(){
	    
	    $busquedas = new PrincipalBusquedasModel();
	    $resp  = null;
	    
	    try {
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	        
	        $id_participes = $_POST['id_participes'];
	        $anio_registro = $_POST['anio_registro'];
	        $mes_registro  = $_POST['mes_registro'];
	        
	        if( !empty( error_get_last() ) ){ throw new Exception("Variable no recibida"); }
	        
	        $col1  = " aa.id_contribucion,aa.fecha_registro_contribucion,aa.fecha_contable_distribucion,aa.observacion_contribucion,aa.descripcion_contribucion,
	           aa.valor_personal_contribucion, aa.valor_patronal_contribucion, extract(month from aa.fecha_registro_contribucion) mes";
	        $tab1  = " core_contribucion aa
	        INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes
	        INNER JOIN core_contribucion_tipo cc ON cc.id_contribucion_tipo = aa.id_contribucion_tipo ";
	        $whe1  = " bb.id_estatus	= 1
	        AND UPPER(cc.nombre_contribucion_tipo) = 'APORTE PERSONAL'
	        AND extract(year from aa.fecha_registro_contribucion) = $anio_registro
	        AND extract(month from aa.fecha_registro_contribucion) <= $mes_registro
	        AND bb.id_participes	= $id_participes ";
	        $id1   = "aa.fecha_contable_distribucion";
	        $rsConsulta1   = $busquedas->getCondicionesDesc($col1, $tab1, $whe1, $id1);
	        
	        $colCantidad = " COUNT(1) AS cantidad " ;
	        $resultSet = $busquedas->getCondicionesSinOrden( $colCantidad , $tab1, $whe1,"");
	        $cantidadResult=(int)$resultSet[0]->cantidad;
	        
	        $per_page = 2; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet = $busquedas->getCondicionesPagDesc($col1, $tab1, $whe1, $id1, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        $i = 0;
	        
	        $htmlHead = "";
	        $htmlHead .= "<thead>";
	        $htmlHead .= "<tr>";
	        $htmlHead .= "<th><label>MES</label></th>";
	        $htmlHead .= "<th><label>Aporte Personal</label></td>";
	        $htmlHead .= "<th><label>Aporte Patronal</label></td>";
	        $htmlHead .= "</tr>";
	        $htmlHead .= "</thead>";	        
	       
	        //para los datos de la tabla
	        $htmlBody = "<tbody>";
	        foreach ($resultSet as $res){
	            /** variables a utilizar **/
	            $i++;
	            $valorpersonal = number_format ( $res->valor_personal_contribucion , 2 , "." , "" );
	            $valorpatronal = number_format ( $res->valor_patronal_contribucion , 2 , "." , "" );
	            
	            //proceso de generacion de opciones
// 	            $opcionesTd  = "";	            
	             
// 	            $opcionesTd .= "<div class=\"btn-group\">";
// 	            $opcionesTd .= "<button type=\"button\" value=\"".$res->id_participes."\" onclick=\"fnRegistroAportesManuel(this)\" class=\"btn btn-default\"><i class=\"fa fa-edit\"></i></button>";
// 	            $opcionesTd .= "</div>";	           
	            
	            $htmlBody    .= "<tr>";
	            $htmlBody    .= "<td>".$this->getNombreMes($res->mes)."</td>";
	            $htmlBody    .= "<td class=\"text-right\">".$valorpersonal."</td>";
	            $htmlBody    .= "<td class=\"text-right\">".$valorpatronal."</td>";
	            //$htmlBody    .= "<td class=\"col-md-2 col-lg-2\" >".$opcionesTd."</td>";
	            $htmlBody    .= "</tr>";
	            
	        }
	        
	        $htmlBody .= "</tbody>";
	        
	        $htmlFoot = "<tfoot>";
	        //$htmlFoot .= "<tr>";
	        //$htmlFoot .= "<th colspan=\"3\"></th>";
	        //$htmlFoot .= "</tr>";
	        $htmlFoot .= "</tfoot>";
	        
	        $resp['tabla'] = $htmlHead.$htmlBody.$htmlFoot;
	        	       	        
	        $htmlPaginacion  = '<div class="table-pagination pull-right">';
	        $htmlPaginacion .= ''. $busquedas->allpaginate("index.php", $page, $total_pages, $adjacents,"loadDatosAportes").'';
	        $htmlPaginacion .= '</div>';
	        
	        $resp['paginacion'] = $htmlPaginacion;
	        $resp['cantidadDatos'] = $cantidadResult;
	        
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
	
	
	
	public function ReporteKardexSocios(){
	    
	    
	    session_start();
	    
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    
	    
	    $entidades = new EntidadesModel();
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
	    
	   //empieza consulta para aportes patronales  
	   
	    $contribucion = new CoreContribucionModel();
	    $participes=new ParticipesModel();
	    
	    $id_participes =  (isset($_REQUEST['id_participes'])&& $_REQUEST['id_participes'] !=NULL)?$_REQUEST['id_participes']:0;
	    
	    
	    //$id_participes =908;
	
	    
	    
	    
	    
	    //$productos=new SaldoProductosModel();
	    $datos_reporte = array();
	    
	    
	    $columnas = " core_entidad_patronal.id_entidad_patronal, 
                      core_entidad_patronal.nombre_entidad_patronal, 
                      core_participes.cedula_participes, 
                      core_participes.nombre_participes, 
                      core_participes.apellido_participes, 
                      core_participes.ocupacion_participes, 
                      core_participes.fecha_ingreso_participes, 
                      core_participes.fecha_salida_participes, 
                      core_participes.id_participes";
	    
	    $tablas = "   public.core_participes, 
                      public.core_entidad_patronal";
	    $where= "     core_participes.id_entidad_patronal = core_entidad_patronal.id_entidad_patronal AND core_participes.id_participes= '$id_participes'";
	    $id="core_entidad_patronal.nombre_entidad_patronal";
	    
	    $rsdatos = $participes->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $datos_reporte['ENTIDAD_PATRONAL']=$rsdatos[0]->nombre_entidad_patronal;
	    $datos_reporte['CEDULA_PARTICIPE']=$rsdatos[0]->cedula_participes;
	    $datos_reporte['NOMBRE_PARTICIPE']=$rsdatos[0]->nombre_participes;
	    $datos_reporte['APELLIDO_PARTICIPE']=$rsdatos[0]->apellido_participes;
	    $datos_reporte['OCUPACION_PARTICIPE']=$rsdatos[0]->ocupacion_participes;
	    $datos_reporte['FECHA_INGRESO']=$rsdatos[0]->fecha_salida_participes;
	    $datos_reporte['FECHA_SALIDA']=$rsdatos[0]->fecha_salida_participes;
	    
	    
	    ////// APORTES PATRONALES
	    $condicion_aporte_patronal=" and c1.id_contribucion_tipo = 3";
	    
	    
	    $columnas = " aa.anio,
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 1 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"enero\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 2 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"febrero\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 3 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"marzo\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 4 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"abril\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 5 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"mayo\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 6 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"junio\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 7 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"julio\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 8 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"agosto\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 9 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"septiembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 10 and id_participes = '$id_participes'$condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"octubre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 11 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"noviembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 12 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"diciembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"acumulado\",
                
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"total\"
";
	    $tablas   = " (select to_char(fecha_registro_contribucion,'YYYY') as anio
                	from core_contribucion
                	where id_participes = '$id_participes' and  id_contribucion_tipo=3
                	group by to_char(fecha_registro_contribucion,'YYYY')
                	order by to_char(fecha_registro_contribucion,'YYYY')
                	) aa";
	    $where    = "1=1";
	    
	    
	    $id="aa.anio";
	    
	    $aporte_patronal = $contribucion->getCondiciones($columnas, $tablas, $where, $id);
	    $html='';
	    
	    
	    $html.='<table class="1" border=1>';
	    
	    $html.= "<tr>";
	    $html.='<th style="text-align: center;  font-size: 12px;">Año</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Enero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Febrero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Marzo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Abril</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Mayo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Junio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Julio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Agosto</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Septiembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Octubre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Noviembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Diciembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Acumulado</th>';
	    
	    
	    
	    $html.='</tr>';
	    
	    
	    
	    
	    foreach ($aporte_patronal as $res)
	    {
	        
	        
	        
	        if(($res->enero==0)&&($res->febrero==0)&&($res->marzo==0)&&($res->abril==0)&&($res->mayo==0)&&($res->junio==0)&&($res->julio==0)&&($res->agosto==0)&&($res->septiembre==0)&&($res->octubre==0)&&($res->noviembre==0)&&($res->diciembre==0)){
	            
	            $res->anio="";
	            
	            
	        }
	        
	        
	        $i++;
	        $html.='<tr>';
	        
	        $html.='<td style="font-size: 10px;">'.$res->anio.'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->enero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->febrero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->marzo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->abril, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->mayo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->junio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->julio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->agosto, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->septiembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->octubre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->noviembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->diciembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->acumulado, 2, ",", ".").'</td>';
	        
	        
	        
	        
	        $html.='</tr>';
	    }
	    
	    
	    
	    
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_APORTE_PATRONAL']= $html;
	    
	    
	    $condicion_aporte_patronal=" and c1.id_contribucion_tipo = 8";
	    
	    
	    $columnas = " aa.anio,
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 1 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"enero\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 2 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"febrero\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 3 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"marzo\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 4 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"abril\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 5 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"mayo\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 6 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"junio\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 7 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"julio\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 8 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"agosto\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 9 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"septiembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 10 and id_participes = '$id_participes'$condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"octubre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 11 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"noviembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 12 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"diciembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"acumulado\",
                
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"total\"
";
	    $tablas   = " (select to_char(fecha_registro_contribucion,'YYYY') as anio
                	from core_contribucion
                	where id_participes = '$id_participes' and  id_contribucion_tipo=8
                	group by to_char(fecha_registro_contribucion,'YYYY')
                	order by to_char(fecha_registro_contribucion,'YYYY')
                	) aa";
	    $where    = "1=1";
	    
	    
	    $id="aa.anio";
	    
	    $aporte_patronal_exedente = $contribucion->getCondiciones($columnas, $tablas, $where, $id);
	    $html='';
	    
	    
	    $html.='<table class="1" border=1>';
	    
	    $html.= "<tr>";
	    $html.='<th style="text-align: center;  font-size: 12px;">Año</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Enero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Febrero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Marzo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Abril</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Mayo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Junio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Julio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Agosto</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Septiembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Octubre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Noviembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Diciembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Acumulado</th>';
	    
	    
	    
	    $html.='</tr>';
	    
	    
	    
	    
	    foreach ($aporte_patronal_exedente as $res)
	    {
	        
	        
	        
	        if(($res->enero==0)&&($res->febrero==0)&&($res->marzo==0)&&($res->abril==0)&&($res->mayo==0)&&($res->junio==0)&&($res->julio==0)&&($res->agosto==0)&&($res->septiembre==0)&&($res->octubre==0)&&($res->noviembre==0)&&($res->diciembre==0)){
	            
	            $res->anio="";
	            
	            
	        }
	        
	        
	        $i++;
	        $html.='<tr>';
	        
	        $html.='<td style="font-size: 10px;">'.$res->anio.'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->enero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->febrero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->marzo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->abril, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->mayo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->junio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->julio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->agosto, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->septiembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->octubre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->noviembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->diciembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->acumulado, 2, ",", ".").'</td>';
	        
	        
	        
	        
	        $html.='</tr>';
	    }
	    
	    
	    
	    
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_APORTE_PATRONAL_EXEDENTE']= $html;
	    
	    
	    $condicion_aporte_patronal=" and c1.id_contribucion_tipo = 2";
	    
	    
	    $columnas = " aa.anio,
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 1 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"enero\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 2 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"febrero\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 3 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"marzo\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 4 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"abril\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 5 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"mayo\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 6 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"junio\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 7 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"julio\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 8 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"agosto\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 9 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"septiembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 10 and id_participes = '$id_participes'$condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"octubre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 11 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"noviembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 12 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"diciembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"acumulado\",
                
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"total\"
";
	    $tablas   = " (select to_char(fecha_registro_contribucion,'YYYY') as anio
                	from core_contribucion
                	where id_participes = '$id_participes' and  id_contribucion_tipo=2
                	group by to_char(fecha_registro_contribucion,'YYYY')
                	order by to_char(fecha_registro_contribucion,'YYYY')
                	) aa";
	    $where    = "1=1";
	    
	    
	    $id="aa.anio";
	    
	    $aporte_patronal_interes = $contribucion->getCondiciones($columnas, $tablas, $where, $id);
	    $html='';
	    
	    
	    $html.='<table class="1" border=1>';
	    
	    $html.= "<tr>";
	    $html.='<th style="text-align: center;  font-size: 12px;">Año</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Enero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Febrero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Marzo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Abril</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Mayo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Junio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Julio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Agosto</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Septiembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Octubre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Noviembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Diciembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Acumulado</th>';
	    
	    
	    
	    $html.='</tr>';
	    
	    
	    
	    
	    foreach ($aporte_patronal_interes as $res)
	    {
	        
	        
	        
	        if(($res->enero==0)&&($res->febrero==0)&&($res->marzo==0)&&($res->abril==0)&&($res->mayo==0)&&($res->junio==0)&&($res->julio==0)&&($res->agosto==0)&&($res->septiembre==0)&&($res->octubre==0)&&($res->noviembre==0)&&($res->diciembre==0)){
	            
	            $res->anio="";
	            
	            
	        }
	        
	        
	        $i++;
	        $html.='<tr>';
	        
	        $html.='<td style="font-size: 10px;">'.$res->anio.'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->enero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->febrero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->marzo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->abril, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->mayo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->junio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->julio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->agosto, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->septiembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->octubre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->noviembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->diciembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->acumulado, 2, ",", ".").'</td>';
	        
	        
	        
	        
	        $html.='</tr>';
	    }
	    
	    
	    
	    
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_APORTE_PATRONAL_INTERES']= $html;
	    
	    
	    
	    
	    $condicion_aporte_patronal=" and c1.id_contribucion_tipo = 49";
	    
	    
	    $columnas = " aa.anio,
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 1 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"enero\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 2 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"febrero\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 3 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"marzo\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 4 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"abril\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 5 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"mayo\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 6 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"junio\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 7 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"julio\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 8 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"agosto\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 9 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"septiembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 10 and id_participes = '$id_participes'$condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"octubre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 11 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"noviembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 12 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"diciembre\",
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	 and id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"acumulado\",
                
                (select sum(c1.valor_patronal_contribucion)
                	from core_contribucion c1 where id_participes = '$id_participes' $condicion_aporte_patronal and id_estatus=1 limit 1
                ) as \"total\"
";
	    $tablas   = " (select to_char(fecha_registro_contribucion,'YYYY') as anio
                	from core_contribucion
                	where id_participes = '$id_participes' and  id_contribucion_tipo=49
                	group by to_char(fecha_registro_contribucion,'YYYY')
                	order by to_char(fecha_registro_contribucion,'YYYY')
                	) aa";
	    $where    = "1=1";
	    
	    
	    $id="aa.anio";
	    
	    $aporte_patronal_supervait = $contribucion->getCondiciones($columnas, $tablas, $where, $id);
	    $html='';
	    
	    
	    $html.='<table class="1" border=1>';
	    
	    $html.= "<tr>";
	    $html.='<th style="text-align: center;  font-size: 12px;">Año</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Enero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Febrero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Marzo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Abril</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Mayo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Junio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Julio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Agosto</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Septiembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Octubre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Noviembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Diciembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Acumulado</th>';
	    
	    
	    
	    $html.='</tr>';
	    
	    
	    
	    
	    foreach ($aporte_patronal_supervait as $res)
	    {
	        
	        
	        
	        if(($res->enero==0)&&($res->febrero==0)&&($res->marzo==0)&&($res->abril==0)&&($res->mayo==0)&&($res->junio==0)&&($res->julio==0)&&($res->agosto==0)&&($res->septiembre==0)&&($res->octubre==0)&&($res->noviembre==0)&&($res->diciembre==0)){
	            
	            $res->anio="";
	            
	            
	        }
	        
	        
	        $i++;
	        $html.='<tr>';
	        
	        $html.='<td style="font-size: 10px;">'.$res->anio.'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->enero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->febrero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->marzo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->abril, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->mayo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->junio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->julio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->agosto, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->septiembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->octubre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->noviembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->diciembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->acumulado, 2, ",", ".").'</td>';
	        
	        
	        
	        
	        $html.='</tr>';
	    }
	    
	    
	    
	    
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_APORTE_PATRONAL_SUPERAVIT']= $html;
	    
	    
	    
         ////// APORTES PERSONALES
	    
	    
	    $condicion_aporte_personal=" and c1.id_contribucion_tipo = 1";
	    
	    
	    $columnas = " aa.anio,
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 1 and id_participes = '$id_participes'  $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"enero\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 2 and id_participes = '$id_participes'  $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"febrero\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 3 and id_participes = '$id_participes' $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"marzo\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 4 and id_participes = '$id_participes' $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"abril\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 5 and id_participes = '$id_participes' $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"mayo\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 6 and id_participes = '$id_participes' $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"junio\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 7 and id_participes = '$id_participes' $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"julio\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 8 and id_participes = '$id_participes' $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"agosto\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 9 and id_participes = '$id_participes' $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"septiembre\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 10 and id_participes = '$id_participes' $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"octubre\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 11 and id_participes = '$id_participes' $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"noviembre\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 12 and id_participes = '$id_participes' $condicion_aporte_personal  and id_estatus=1 limit 1
                ) as \"diciembre\",
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	 and id_participes = '$id_participes' $condicion_aporte_personal and id_estatus=1 limit 1
                ) as \"acumulado\",
                
                (select sum(c1.valor_personal_contribucion) 
                	from core_contribucion c1 where id_participes = '$id_participes' $condicion_aporte_personal  and id_estatus=1 limit 1
                ) as \"total\" 
";
	    
	    $tablas = " (select to_char(fecha_registro_contribucion,'YYYY') as anio
                	from core_contribucion
                	where id_participes = '$id_participes' and  id_contribucion_tipo=1
                	group by to_char(fecha_registro_contribucion,'YYYY')
                	order by to_char(fecha_registro_contribucion,'YYYY')
                	) aa";
	    $where= "1=1";
	    
	    
	    $id="aa.anio";
	    
	    $personales_detalle = $contribucion->getCondiciones($columnas, $tablas, $where, $id);
	    $html='';
	    
	    
	    $html.='<table class="1" border=1>';
	    
	    $html.= "<tr>";
	     $html.='<th style="text-align: center;  font-size: 12px;">Año</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Enero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Febrero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Marzo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Abril</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Mayo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Junio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Julio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Agosto</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Septiembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Octubre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Noviembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Diciembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Acumulado</th>';
	    
	    
	    
	    $html.='</tr>';
	    
	    
	    
	    
	    foreach ($personales_detalle as $res)
	    {
	        
	        
	        
	        if(($res->enero==0)&&($res->febrero==0)&&($res->marzo==0)&&($res->abril==0)&&($res->mayo==0)&&($res->junio==0)&&($res->julio==0)&&($res->agosto==0)&&($res->septiembre==0)&&($res->octubre==0)&&($res->noviembre==0)&&($res->diciembre==0)){
	            
	            $res->anio="";
	            
	            
	        }
	        
	        
	    
	        
	        
	        $i++;
	        $html.='<tr>';
	        
	        $html.='<td style="font-size: 10px;">'.$res->anio.'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->enero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->febrero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->marzo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->abril, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->mayo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->junio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->julio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->agosto, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->septiembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->octubre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->noviembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->diciembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->acumulado, 2, ",", ".").'</td>';
	        
	        
	        
	        
	        $html.='</tr>';
	    }
	    

	    
	    
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_PERSONAL']= $html;
	    
	    ///exedente personal
	    
	    
	    $condicion_id_contribucion_tipo=" and c1.id_contribucion_tipo = 7";
	    
	    
	    $columnas = " aa.anio,
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 1 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"enero\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 2 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"febrero\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 3 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"marzo\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 4 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"abril\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 5 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"mayo\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 6 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"junio\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 7 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"julio\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 8 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"agosto\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 9 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"septiembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 10 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"octubre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 11 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"noviembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 12 and id_participes = '$id_participes' $condicion_id_contribucion_tipo and id_estatus=1 limit 1
                ) as \"diciembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	 and id_participes = '$id_participes' $condicion_id_contribucion_tipo  and id_estatus=1 limit 1
                ) as \"acumulado\",
                
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where id_participes = '$id_participes'  and id_estatus=1 limit 1
                ) as \"total\"
";
	    
	    $tablas = " (select to_char(fecha_registro_contribucion,'YYYY') as anio
                	from core_contribucion
                	where id_participes = '$id_participes' and  id_contribucion_tipo=7
                	group by to_char(fecha_registro_contribucion,'YYYY')
                	order by to_char(fecha_registro_contribucion,'YYYY')
                	) aa";
	    $where= "1=1";
	    
	    
	    $id="aa.anio";
	    
	    $personales_detalle_personal = $contribucion->getCondiciones($columnas, $tablas, $where, $id);
	    $html='';
	    
	    
	    $html.='<table class="1" border=1>';
	    
	    $html.= "<tr>";
	    $html.='<th style="text-align: center;  font-size: 12px;">Año</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Enero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Febrero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Marzo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Abril</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Mayo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Junio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Julio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Agosto</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Septiembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Octubre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Noviembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Diciembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Acumulado</th>';
	    
	    
	    
	    $html.='</tr>';
	    
	    
	    
	    
	    foreach ($personales_detalle_personal as $res)
	    {
	        
	        
	        $total_enero_exedente_personal=$total_enero_exedente_personal+$res->enero;
	        $total_febrero_exedente_personal=$total_febrero_exedente_personal+$res->febrero;
	        $total_marzo_exedente_personal=$total_marzo_exedente_personal+$res->marzo;
	        $total_abril_exedente_personal=$total_abril_exedente_personal+$res->abril;
	        $total_mayo_exedente_personal=$total_mayo_exedente_personal+$res->mayo;
	        $total_junio_exedente_personal=$total_junio_exedente_personal+$res->junio;
	        $total_julio_exedente_personal=$total_julio_exedente_personal+$res->julio;
	        $total_agosto_exedente_personal=$total_agosto_exedente_personal+$res->agosto;
	        $total_septiembre_exedente_personal=$total_septiembre_exedente_personal+$res->septiembre;
	        $total_octubre_exedente_personal=$total_octubre_exedente_personal+$res->octubre;
	        $total_noviembre_exedente_personal=$total_noviembre_exedente_personal+$res->noviembre;
	        $total_diciembre_exedente_personal=$total_diciembre_exedente_personal+$res->diciembre;
	        $total_exedente_personal=$total_exedente_personal+$res->acumulado;
	        
	        
	        if(($res->enero==0)&&($res->febrero==0)&&($res->marzo==0)&&($res->abril==0)&&($res->mayo==0)&&($res->junio==0)&&($res->julio==0)&&($res->agosto==0)&&($res->septiembre==0)&&($res->octubre==0)&&($res->noviembre==0)&&($res->diciembre==0)){
	            
	            $res->anio="";
	            
	            
	        }
	        
	        
	        $i++;
	        $html.='<tr>';
	        
	        $html.='<td style="font-size: 10px;">'.$res->anio.'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->enero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->febrero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->marzo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->abril, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->mayo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->junio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->julio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->agosto, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->septiembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->octubre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->noviembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->diciembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->acumulado, 2, ",", ".").'</td>';
	        
	        
	        
	        
	        $html.='</tr>';
	    }
	    
	    
	    
	    
	    $html.='<tr>';
	    
	    $html.='<td style="font-size: 10px;">TOTAL</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_enero_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_febrero_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_marzo_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_abril_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_mayo_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_junio_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_julio_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_agosto_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_septiembre_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_octubre_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_noviembre_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_diciembre_exedente_personal, 2, ",", ".").'</td>';
	    $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$total_exedente_personal, 2, ",", ".").'</td>';
	    
	    
	    
	    
	    $html.='</tr>';
	    
	    
	    
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_EXCEDENTE_PERSONAL']= $html;
	    
	    
	    
	    
	    
	    $condicion_id_contribucion_tipo_impuesto_ir=" and c1.id_contribucion_tipo = 10";
	    
	    
	    $columnas = " aa.anio,
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 1 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"enero\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 2 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"febrero\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 3 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"marzo\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 4 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"abril\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 5 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"mayo\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 6 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"junio\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 7 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"julio\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 8 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"agosto\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 9 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"septiembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 10 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"octubre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 11 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"noviembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 12 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir and id_estatus=1 limit 1
                ) as \"diciembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	 and id_participes = '$id_participes' $condicion_id_contribucion_tipo_impuesto_ir  and id_estatus=1 limit 1
                ) as \"acumulado\",
                
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where id_participes = '$id_participes'  and id_estatus=1 limit 1
                ) as \"total\"
";
	    
	    $tablas = " (select to_char(fecha_registro_contribucion,'YYYY') as anio
                	from core_contribucion
                	where id_participes = '$id_participes' and id_contribucion_tipo = 10
                	group by to_char(fecha_registro_contribucion,'YYYY')
                	order by to_char(fecha_registro_contribucion,'YYYY')
                	) aa";
	    $where= "1=1";
	    
	    
	    $id="aa.anio";
	    
	    $personales_impuesto_ir = $contribucion->getCondiciones($columnas, $tablas, $where, $id);
	    $html='';
	    
	    
	    $html.='<table class="1" border=1>';
	    
	    $html.= "<tr>";
	    $html.='<th style="text-align: center;  font-size: 12px;">Año</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Enero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Febrero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Marzo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Abril</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Mayo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Junio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Julio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Agosto</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Septiembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Octubre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Noviembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Diciembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Acumulado</th>';
	    
	    
	    
	    $html.='</tr>';
	    
	    
	    
	    
	    foreach ($personales_impuesto_ir as $res)
	    {
	        
	        
	        
	        if(($res->enero==0)&&($res->febrero==0)&&($res->marzo==0)&&($res->abril==0)&&($res->mayo==0)&&($res->junio==0)&&($res->julio==0)&&($res->agosto==0)&&($res->septiembre==0)&&($res->octubre==0)&&($res->noviembre==0)&&($res->diciembre==0)){
	            
	            $res->anio="";
	            
	            
	        }
	        
	        
	        $i++;
	        $html.='<tr>';
	        
	        $html.='<td style="font-size: 10px;">'.$res->anio.'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->enero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->febrero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->marzo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->abril, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->mayo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->junio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->julio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->agosto, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->septiembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->octubre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->noviembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->diciembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->acumulado, 2, ",", ".").'</td>';
	        
	        
	        
	        
	        $html.='</tr>';
	    }
	    
	    

	    
	    
	    
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_IMPUESTO_IR_PERSONAL']= $html;
	    
	   
	    $condicion_interes_personal=" and c1.id_contribucion_tipo = 9";
	    
	    
	    $columnas = " aa.anio,
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 1 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"enero\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 2 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"febrero\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 3 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"marzo\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 4 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"abril\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 5 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"mayo\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 6 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"junio\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 7 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"julio\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 8 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"agosto\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 9 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"septiembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 10 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"octubre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 11 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"noviembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 12 and id_participes = '$id_participes' $condicion_interes_personal and id_estatus=1 limit 1
                ) as \"diciembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	 and id_participes = '$id_participes' $condicion_interes_personal  and id_estatus=1 limit 1
                ) as \"acumulado\",
                
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where id_participes = '$id_participes'  and id_estatus=1 limit 1
                ) as \"total\"
";
	    
	    $tablas = " (select to_char(fecha_registro_contribucion,'YYYY') as anio
                	from core_contribucion
                	where id_participes = '$id_participes' and id_contribucion_tipo = 9
                	group by to_char(fecha_registro_contribucion,'YYYY')
                	order by to_char(fecha_registro_contribucion,'YYYY')
                	) aa";
	    $where= "1=1";
	    
	    
	    $id="aa.anio";
	    
	    $personales_interes_personal = $contribucion->getCondiciones($columnas, $tablas, $where, $id);
	    $html='';
	    
	    
	    $html.='<table class="1" border=1>';
	    
	    $html.= "<tr>";
	    $html.='<th style="text-align: center;  font-size: 12px;">Año</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Enero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Febrero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Marzo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Abril</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Mayo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Junio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Julio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Agosto</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Septiembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Octubre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Noviembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Diciembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Acumulado</th>';
	    
	    
	    
	    $html.='</tr>';
	    
	    
	    
	    
	    foreach ($personales_interes_personal as $res)
	    {
	        
	        
	        
	        if(($res->enero==0)&&($res->febrero==0)&&($res->marzo==0)&&($res->abril==0)&&($res->mayo==0)&&($res->junio==0)&&($res->julio==0)&&($res->agosto==0)&&($res->septiembre==0)&&($res->octubre==0)&&($res->noviembre==0)&&($res->diciembre==0)){
	            
	            $res->anio="";
	            
	            
	        }
	        
	        
	        $i++;
	        $html.='<tr>';
	        
	        $html.='<td style="font-size: 10px;">'.$res->anio.'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->enero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->febrero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->marzo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->abril, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->mayo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->junio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->julio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->agosto, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->septiembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->octubre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->noviembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->diciembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->acumulado, 2, ",", ".").'</td>';
	        
	        
	        
	        
	        $html.='</tr>';
	    }
	    
	    
	    
	    
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_INTERES_PERSONAL']= $html;
	    
	    
	    $condicion_retroactivo_personal=" and c1.id_contribucion_tipo = 5";
	    
	    
	    $columnas = " aa.anio,
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 1 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"enero\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 2 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"febrero\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 3 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"marzo\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 4 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"abril\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 5 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"mayo\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 6 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"junio\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 7 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"julio\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 8 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"agosto\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 9 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"septiembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 10 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"octubre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 11 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"noviembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 12 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"diciembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	 and id_participes = '$id_participes' $condicion_retroactivo_personal  and id_estatus=1 limit 1
                ) as \"acumulado\",
                
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where id_participes = '$id_participes'  and id_estatus=1 limit 1
                ) as \"total\"
";
	    
	    $tablas = " (select to_char(fecha_registro_contribucion,'YYYY') as anio
                	from core_contribucion
                	where id_participes = '$id_participes' and id_contribucion_tipo = 5
                	group by to_char(fecha_registro_contribucion,'YYYY')
                	order by to_char(fecha_registro_contribucion,'YYYY')
                	) aa";
	    $where= "1=1";
	    
	    
	    $id="aa.anio";
	    
	    $personales_retroactivo_personal = $contribucion->getCondiciones($columnas, $tablas, $where, $id);
	    $html='';
	    
	    
	    $html.='<table class="1" border=1>';
	    
	    $html.= "<tr>";
	    $html.='<th style="text-align: center;  font-size: 12px;">Año</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Enero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Febrero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Marzo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Abril</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Mayo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Junio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Julio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Agosto</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Septiembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Octubre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Noviembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Diciembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Acumulado</th>';
	    
	    
	    
	    $html.='</tr>';
	    
	    
	    
	    
	    foreach ($personales_retroactivo_personal as $res)
	    {
	        
	        
	        
	        if(($res->enero==0)&&($res->febrero==0)&&($res->marzo==0)&&($res->abril==0)&&($res->mayo==0)&&($res->junio==0)&&($res->julio==0)&&($res->agosto==0)&&($res->septiembre==0)&&($res->octubre==0)&&($res->noviembre==0)&&($res->diciembre==0)){
	            
	            $res->anio="";
	            
	            
	        }
	        
	        
	        $i++;
	        $html.='<tr>';
	        
	        $html.='<td style="font-size: 10px;">'.$res->anio.'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->enero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->febrero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->marzo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->abril, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->mayo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->junio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->julio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->agosto, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->septiembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->octubre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->noviembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->diciembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->acumulado, 2, ",", ".").'</td>';
	        
	        
	        
	        
	        $html.='</tr>';
	    }
	    
	    
	    
	    
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_RETROACTIVO_PERSONAL']= $html;
	    
	    
	    $condicion_retroactivo_personal=" and c1.id_contribucion_tipo = 50";
	    
	    
	    $columnas = " aa.anio,
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 1 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"enero\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 2 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"febrero\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 3 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"marzo\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 4 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"abril\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 5 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"mayo\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 6 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"junio\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 7 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"julio\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 8 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"agosto\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 9 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"septiembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 10 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"octubre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 11 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"noviembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	and extract(month from c1.fecha_registro_contribucion) = 12 and id_participes = '$id_participes' $condicion_retroactivo_personal and id_estatus=1 limit 1
                ) as \"diciembre\",
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where to_char(c1.fecha_registro_contribucion,'YYYY') = aa.anio
                	 and id_participes = '$id_participes' $condicion_retroactivo_personal  and id_estatus=1 limit 1
                ) as \"acumulado\",
                
                (select sum(c1.valor_personal_contribucion)
                	from core_contribucion c1 where id_participes = '$id_participes'  and id_estatus=1 limit 1
                ) as \"total\"
";
	    
	    $tablas = " (select to_char(fecha_registro_contribucion,'YYYY') as anio
                	from core_contribucion
                	where id_participes = '$id_participes' and id_contribucion_tipo = 50
                	group by to_char(fecha_registro_contribucion,'YYYY')
                	order by to_char(fecha_registro_contribucion,'YYYY')
                	) aa";
	    $where= "1=1";
	    
	    
	    $id="aa.anio";
	    
	    $personales_superavit_personal = $contribucion->getCondiciones($columnas, $tablas, $where, $id);
	    $html='';
	    
	    
	    $html.='<table class="1" border=1>';
	    
	    $html.= "<tr>";
	    $html.='<th style="text-align: center;  font-size: 12px;">Año</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Enero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Febrero</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Marzo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Abril</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Mayo</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Junio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Julio</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Agosto</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Septiembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Octubre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Noviembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Diciembre</th>';
	    $html.='<th style="text-align: center;  font-size: 12px;">Acumulado</th>';
	    
	    
	    
	    $html.='</tr>';
	    
	    
	    
	    
	    foreach ($personales_superavit_personal as $res)
	    {
	        
	        
	        
	        if(($res->enero==0)&&($res->febrero==0)&&($res->marzo==0)&&($res->abril==0)&&($res->mayo==0)&&($res->junio==0)&&($res->julio==0)&&($res->agosto==0)&&($res->septiembre==0)&&($res->octubre==0)&&($res->noviembre==0)&&($res->diciembre==0)){
	            
	            $res->anio="";
	            
	            
	        }
	        
	        
	        $i++;
	        $html.='<tr>';
	        
	        $html.='<td style="font-size: 10px;">'.$res->anio.'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->enero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->febrero, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->marzo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->abril, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->mayo, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->junio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->julio, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->agosto, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->septiembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->octubre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->noviembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->diciembre, 2, ",", ".").'</td>';
	        $html.='<td style="font-size: 10px;"align="right">'.number_format((float)$res->acumulado, 2, ",", ".").'</td>';
	        
	        
	        
	        
	        $html.='</tr>';
	    }
	    
	    
	    
	    
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_SUPERAVIT_PERSONAL']= $html;
	    
	    
	    
	    
	    
	    $this->verReporte("ReporteKardexSocios", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
	    
	    
	    
	    
	    
	}
	
	
	
	
	
	
	
	
}
?>