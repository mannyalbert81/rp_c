<?php

class RecaudacionController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}
   

	public function index(){
	
	    session_start();
		
     	$EntidadPatronal = new EntidadPatronalParticipesModel();
     		
		if( isset(  $_SESSION['nombre_usuarios'] ) ){

			$nombre_controladores = "Recaudaciones";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $EntidadPatronal->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer)){
			    
			    $queryEntidad = "SELECT * FROM core_entidad_patronal ORDER BY nombre_entidad_patronal";			    
			    $rsEntidadPatronal = $EntidadPatronal->enviaquery($queryEntidad);
			
			    $this->view_Recaudaciones("ArchivoEntidadPatronal",array(
			        'rsEntidadPatronal' => $rsEntidadPatronal
			    ));
				
			}else{
			    
			    $this->view("Error",array(
			        "resultado"=>"No tiene Permisos de Acceso a Grupos"
			        
			    ));
			    
			    exit();				    
			}
				
		}else{
       	
		    $this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function GenerarRecaudacion(){
	     
	    $Contribucion      = new CoreContribucionModel();
	    $respuesta         = array();
	    $error             = "";
	    
	    try{
	        $Contribucion->beginTran();
	        session_start();	        
	        $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
	        $_anio_recaudacion     = $_POST['anio_recaudacion'];
	        $_mes_recaudacion      = $_POST['mes_recaudacion'];
	        $_formato_recaudacion  = $_POST['formato_recaudacion'];
	        
	        $error = error_get_last();
	        if(!empty($error)){    throw new Exception('Variables no recibidas'); }
	        
	        /*configurar estructura mes de consulta*/
	        $_mes_recaudacion = str_pad($_mes_recaudacion, 2, "0", STR_PAD_LEFT);
	        
	        $_nombre_formato_recaudacion = "";
	        $columnas1 = "id_archivo_recaudaciones, nombre_archivo_recaudaciones";
	        $tablas1   = "core_archivo_recaudaciones";
	        $where1    = "id_entidad_patronal = $_id_entidad_patronal AND anio_archivo_recaudaciones = $_anio_recaudacion";
	        $where1    .= " AND mes_archivo_recaudaciones = $_mes_recaudacion";
	        $id1       = "id_archivo_recaudaciones";
	        	        
	        //diferenciar el tipo de recaudacion que va a realizar 
	        switch ( $_formato_recaudacion ){
	           
	            case '1':
	                //para cuando sea para cuenta individual
	                $_nombre_formato_recaudacion = "DESCUENTOS APORTES";
	                $where1    .= " AND formato_archivo_recaudaciones = '$_nombre_formato_recaudacion'";
	                $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	                
	                $_id_archivo_recaudaciones = 0;
	                
	                $error = pg_last_error();
	                if(!empty($error)){ throw new Exception('datos no validos'); }
	                
	                if(empty($rsConsulta1)){
	                    
	                    $respuestaArchivo           = $this->RecaudacionAportes($_id_entidad_patronal, $_anio_recaudacion, $_mes_recaudacion);
	                    $_id_archivo_recaudaciones  = $respuestaArchivo;
	                    
	                    if((int)$respuestaArchivo > 0){
	                        
	                        $respuesta['mensaje']   = "Distribucion Generada Revise el archivo";
	                        $respuesta['id_archivo']= $_id_archivo_recaudaciones;
	                        $respuesta['respuesta'] = 1;
	                    }
	                    
	                }else{
	                    
	                    $respuesta['mensaje']   = "Revise el Archivo";
	                    $respuesta['id_archivo']= $rsConsulta1[0]->id_archivo_recaudaciones;
	                    $respuesta['respuesta'] = 2;
	                    
	                }
	                
	            break;
                case '2':
                    /*para realizar recaudacion por creditos*/
                    //primero validar que no exista
                    $_nombre_formato_recaudacion = "DESCUENTOS CREDITOS";
                    $where1    .= " AND formato_archivo_recaudaciones = '$_nombre_formato_recaudacion'";
                    
                    $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
                    
                    $_id_archivo_recaudaciones = 0;
                    
                    $error = pg_last_error();
                    if(!empty($error)){ throw new Exception('datos no validos'); }
                    
                    if(empty($rsConsulta1)){
                        
                        $respuestaArchivo           = $this->RecaudacionCreditos($_id_entidad_patronal, $_anio_recaudacion, $_mes_recaudacion);
                        $_id_archivo_recaudaciones  = $respuestaArchivo;
                        
                        if((int)$respuestaArchivo > 0){
                            
                            $respuesta['mensaje']   = "Distribucion Generada Revise el archivo";
                            $respuesta['id_archivo']= $_id_archivo_recaudaciones;
                            $respuesta['respuesta'] = 1;
                        }
                        
                    }else{
                        
                        $respuesta['mensaje']   = "Revise el Archivo";
                        $respuesta['id_archivo']= $rsConsulta1[0]->id_archivo_recaudaciones;
                        $respuesta['respuesta'] = 2;
                                                
                    }
                    
                     
                break;
                default:
	            break;
	        }
                
            $Contribucion->endTran('COMMIT');
            echo json_encode($respuesta);
	                
	    } catch (Exception $ex) {
	        $Contribucion->endTran();
	        echo '<message> Error Archivo Recaudacion '.$ex->getMessage().' <message>';
	    }	    
	    
	}
	
	
	public function RecaudacionAportes( $_id_entidad_patronal,$_anio,$_mes){
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $Contribucion  = new CoreContribucionModel();
	    
	    $formato_archivo_recaudaciones = "DESCUENTOS APORTES";
	    
	    $columnas1 = "aa.id_contribucion_tipo_participes, aa.valor_contribucion_tipo_participes, bb.id_contribucion_tipo, bb.nombre_contribucion_tipo,
	               cc.id_tipo_aportacion, cc.nombre_tipo_aportacion, dd.cedula_participes, dd.id_participes, dd.apellido_participes, dd.nombre_participes";
	    $tablas1   = "core_contribucion_tipo_participes aa
            	    INNER JOIN core_contribucion_tipo bb
            	    ON bb.id_contribucion_tipo = aa.id_contribucion_tipo
            	    INNER JOIN core_tipo_aportacion cc
            	    ON cc.id_tipo_aportacion = aa.id_tipo_aportacion
            	    INNER JOIN core_participes dd
            	    ON dd.id_participes = aa.id_participes
            	    INNER JOIN estado ee
            	    ON ee.id_estado = aa.id_estado";
	    $where1    = "bb.nombre_contribucion_tipo = 'Aporte Personal'
                    AND dd.id_estatus = 1
            	    AND ee.nombre_estado = 'ACTIVO'
            	    AND dd.id_entidad_patronal = '$_id_entidad_patronal'";
	    $id1       = "dd.id_participes";
	    
	   
	    $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    if(empty($rsConsulta1)){ throw new Exception('No se encontro datos con los parametros enviados');}
	    
	    $funcionArchivo    = "core_ins_core_archivo_recaudaciones";
	    $parametrosArchivo = "'$_anio','$_mes','$_id_entidad_patronal',null,null,'$formato_archivo_recaudaciones','$_usuario_usuarios'";
	    
	    $queryFuncion  = $Contribucion->getconsultaPG($funcionArchivo, $parametrosArchivo);
	    $Resultado1    = $Contribucion->llamarconsultaPG($queryFuncion);
	    
	    $error = "";
	    $error = pg_last_error();
	    if( !empty($error) ){ throw new Exception('Error en la funcion de insertado');}
	    
	    $_id_archivo_recaudaciones  = $Resultado1[0];
	    
	    $funcionDetalle = "core_ins_core_archivo_recaudaciones_detalle";
	    $parametrosDetalle = "";
	    	   	    
	    foreach ($rsConsulta1 as $res){
	        
	        $_id_participes = $res->id_participes;
	        $_valor_sistema = $res->valor_contribucion_tipo_participes;
	        $_valor_final   = $res->valor_contribucion_tipo_participes;
	        
	        $parametrosDetalle  = "'$_id_archivo_recaudaciones','$_id_participes',null,'$_valor_sistema','$_valor_final','APORTES PERSONALES'";
	        $queryFuncion   = $Contribucion->getconsultaPG($funcionDetalle, $parametrosDetalle);
	        $Resultado2     = $Contribucion->llamarconsultaPG($queryFuncion);
	        
	        $error = pg_last_error();
	        if( !empty($error) ){ break; throw new Exception('Error en la funcion de insertado detalle');}
	        
	    }
	    
	    return $_id_archivo_recaudaciones;
	    
	}
	
	public function RecaudacionCreditos( $_id_entidad_patronal, $_anio, $_mes){
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    $_fecha_buscar = $_anio.$_mes;
	    $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $Contribucion  = new CoreContribucionModel();
	    
	    $formato_archivo_recaudaciones = "DESCUENTOS CREDITOS";
	    
	    $columnas1 = "aa.id_tabla_amortizacion,aa.fecha_tabla_amortizacion, aa.total_valor_tabla_amortizacion,
            	    bb.id_creditos, bb.numero_creditos, bb.id_tipo_creditos, bb.fecha_concesion_creditos,
            	    cc.id_participes, cc.cedula_participes, cc.nombre_participes, cc.apellido_participes";
	    $tablas1   = "core_tabla_amortizacion aa
            	    INNER JOIN core_creditos bb
            	    ON bb.id_creditos = aa.id_creditos
            	    INNER JOIN core_participes cc
            	    ON cc.id_participes = bb.id_participes
            	    INNER JOIN core_estado_creditos dd
            	    ON dd.id_estado_creditos = bb.id_estado_creditos";
	    $where1    = "aa.id_estatus = 1
            	    AND bb.id_estatus = 1
                    AND cc.id_estatus = 1 
            	    AND aa.id_estado_tabla_amortizacion <> 2
                    AND bb.id_estado_creditos = 4
                    AND cc.id_entidad_patronal = 1 
            	    AND TO_CHAR(aa.fecha_tabla_amortizacion,'YYYYMM') = '$_fecha_buscar'
            	    AND dd.nombre_estado_creditos = 'Activo'";
	    $id1       = "cc.id_participes, aa.id_tabla_amortizacion";
	    
	    //echo $columnas1, $tablas1, $where1, $id1, '<br>'; throw new Exception('Nprueba');
	    
	    $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    if(empty($rsConsulta1)){ throw new Exception('No existe datos para los parametros enviados');}
	    
        $funcionArchivo    = "core_ins_core_archivo_recaudaciones";
        $parametrosArchivo = "'$_anio','$_mes','$_id_entidad_patronal',null,null,'$formato_archivo_recaudaciones','$_usuario_usuarios'";
        
        $queryFuncion  = $Contribucion->getconsultaPG($funcionArchivo, $parametrosArchivo);
        $Resultado1    = $Contribucion->llamarconsultaPG($queryFuncion);
        
        $error = "";
        $error = pg_last_error();
        if( !empty($error) ){ throw new Exception('Error en la funcion de insertado');}
        
        $_id_archivo_recaudaciones  = $Resultado1[0];
        
        $funcionDetalle = "core_ins_core_archivo_recaudaciones_detalle";
        $parametrosDetalle = "";
        
        foreach ($rsConsulta1 as $res){
        
            $_id_participes = $res->id_participes;
            $_id_creditos   = $res->id_creditos;
            $_valor_sistema = $res->total_valor_tabla_amortizacion;
            $_valor_final   = $res->total_valor_tabla_amortizacion;
            
            $parametrosDetalle  = "'$_id_archivo_recaudaciones','$_id_participes','$_id_creditos','$_valor_sistema','$_valor_final','CUOTA MENSUAL'";
            $queryFuncion   = $Contribucion->getconsultaPG($funcionDetalle, $parametrosDetalle);
            $Resultado2     = $Contribucion->llamarconsultaPG($queryFuncion);
            
            $error = pg_last_error();
            if( !empty($error) ){ break; throw new Exception('Error en la funcion de insertado detalle');}
            
        }
        
        /* para buscar valores anteriores de credito*/ 
	    
        return $_id_archivo_recaudaciones;
        
	}
		
	public function ConsultaAportes(){
	    
	    $Contribucion  = new CoreContribucionModel();
	    /*toma de variables*/
	    $page                  = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	    $_busqueda             = $_POST['busqueda'];
	    $_anio_recaudaciones   = $_POST['anio_recaudaciones'];
	    $_mes_recaudaciones    = $_POST['mes_recaudaciones'];
	    $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
	    
	    
	    $_nombre_formato_recaudacion = "DESCUENTOS APORTES";
	    $columnas1 = "id_archivo_recaudaciones, nombre_archivo_recaudaciones";
	    $tablas1   = "core_archivo_recaudaciones";
	    $where1    = "id_entidad_patronal = $_id_entidad_patronal AND anio_archivo_recaudaciones = $_anio_recaudaciones
                    AND mes_archivo_recaudaciones = $_mes_recaudaciones
                    AND formato_archivo_recaudaciones = '$_nombre_formato_recaudacion' ";
	    $id1       = "id_archivo_recaudaciones";
	    
	    $rsConsulta1    = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    $_id_archivo_recaudaciones  = $rsConsulta1[0]->id_archivo_recaudaciones;
	    
	    
	    $columnas2 = "aa.id_archivo_recaudaciones, aa.valor_sistema_archivo_recaudaciones_detalle, aa.valor_final_archivo_recaudaciones_detalle,
            	   bb.formato_archivo_recaudaciones, bb.usuario_usuarios, cc.id_participes, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes,
                   aa.id_archivo_recaudaciones_detalle";
	    
	    $tablas2    = "core_archivo_recaudaciones_detalle aa
            	   INNER JOIN core_archivo_recaudaciones bb
            	   ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones
            	   INNER JOIN core_participes cc
            	   ON cc.id_participes = aa.id_participes";
	    
	    $where2     = "cc.id_estatus = 1
            	   AND aa.id_archivo_recaudaciones = $_id_archivo_recaudaciones";
	    
	    $id2        = "cc.id_participes";
	    
	    
	    if(!empty($_busqueda)){
	        // metodos de busqueda
	        $where2 .= " AND ( cc.cedula_participes ILIKE '$_busqueda%' )";
	    }
	    
	    //echo $columnas2, $tablas2, $where2, $id2, '1','<br>'; die();
	    
	    $html = "";
	    $resultSet=$Contribucion->getCantidad("*", $tablas2, $where2);
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    /* para obtener Sumas*/
	    $rsSumatoria1           = $Contribucion->getSumaColumna("aa.valor_sistema_archivo_recaudaciones_detalle", $tablas2, $where2);
	    $_total_archivo_sistema = $rsSumatoria1[0]->suma;
	    $rsSumatoria2           = $Contribucion->getSumaColumna("aa.valor_final_archivo_recaudaciones_detalle", $tablas2, $where2);
	    $_total_archivo_final   = $rsSumatoria1[0]->suma;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet=$Contribucion->getCondicionesPag($columnas2, $tablas2, $where2, $id2, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    if($cantidadResult>0){
	        
	        $html.= "<table id='tbl_archivo_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $i=0;
	        $_tipo_recaudacion    = "";
	        foreach ($resultSet as $res){
	            $i++;
	            
	            $_tipo_recaudacion  = "Aportes Personales";
	            
	            $html.='<tr>';
	            $html.='<td style="font-size: 18px;">';
	            $html.='<span class="pull-right ">
                                <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones_detalle.'"
                                href="#" class="btn btn-sm btn-default label label-warning">
                                <i class="fa fa-edit" aria-hidden="true" ></i>
                                </a></span></td>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->usuario_usuarios.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->formato_archivo_recaudaciones.'</td>';
	            $html.='<td style="font-size: 11px;">'.$_tipo_recaudacion.'</td>';
	            $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_sistema_archivo_recaudaciones_detalle.'</td>';
	            $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_final_archivo_recaudaciones_detalle.'</td>';
	            
	            $html.='</tr>';
	        }
	        
	        $html.='</tbody>';
	        /*para totalizar las filas*/
	        $html.='<tfoot>';
	        $html.='<tr>';
	        $html.='<th colspan="7" ></th>';
	        $html.='<th style="text-align: right"; >TOTALES</th>';
	        $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_sistema.'</th>';
	        $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_final.'</th>';
	        $html.='</tr>';
	        $html.='</tfoot>';
	        $html.='</table>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"buscaAportesCreditos").'';
	        $html.='</div>';
	        
	    }else{
	        
	        $html.= "<table id='tbl_archivo_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        $html.='</tbody>';
	        $html.='</table>';
	    }
	    
	    echo json_encode(array('tablaHtml'=>$html,'cantidadRegistros'=>$cantidadResult));
	    
	}
	
	public function ConsultaAportesCreditos(){
	    
	   $Contribucion  = new CoreContribucionModel();
	   /*toma de variables*/
	   $page                  = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	   $_busqueda             = $_POST['busqueda'];
	   $_anio_recaudaciones   = $_POST['anio_recaudaciones'];
	   $_mes_recaudaciones    = $_POST['mes_recaudaciones'];
	   $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
	   
	   
	   $_nombre_formato_recaudacion = "DESCUENTOS CREDITOS";
	   $columnas1 = "id_archivo_recaudaciones, nombre_archivo_recaudaciones";
	   $tablas1   = "core_archivo_recaudaciones";
	   $where1    = "id_entidad_patronal = $_id_entidad_patronal AND anio_archivo_recaudaciones = $_anio_recaudaciones
                    AND mes_archivo_recaudaciones = $_mes_recaudaciones 
                    AND formato_archivo_recaudaciones = '$_nombre_formato_recaudacion' ";
	   $id1       = "id_archivo_recaudaciones";
	   
	   $rsConsulta1    = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);	   
	   $_id_archivo_recaudaciones  = $rsConsulta1[0]->id_archivo_recaudaciones;
	   
	   
	   $columnas2 = "aa.id_archivo_recaudaciones, aa.valor_sistema_archivo_recaudaciones_detalle, aa.valor_final_archivo_recaudaciones_detalle,
            	   bb.formato_archivo_recaudaciones, bb.usuario_usuarios, cc.id_participes, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes,
            	   dd.id_creditos, dd.monto_neto_entregado_creditos, ee.id_tipo_creditos, ee.nombre_tipo_creditos,
                   aa.id_archivo_recaudaciones_detalle";
	   
	   $tablas2    = "core_archivo_recaudaciones_detalle aa
            	   INNER JOIN core_archivo_recaudaciones bb
            	   ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones
            	   INNER JOIN core_participes cc
            	   ON cc.id_participes = aa.id_participes
            	   INNER JOIN core_creditos dd
            	   ON dd.id_creditos = aa.id_creditos
            	   INNER JOIN core_tipo_creditos ee
            	   ON ee.id_tipo_creditos = dd.id_tipo_creditos";
	   
	   $where2     = "cc.id_estatus = 1
            	   AND dd.id_estatus = 1
            	   AND aa.id_archivo_recaudaciones = $_id_archivo_recaudaciones";
	   
	   $id2        = "cc.id_participes";
	   	   
	   
	   if(!empty($_busqueda)){
	       // metodos de busqueda
	       $where2 .= " AND ( cc.cedula_participes ILIKE '$_busqueda%' )";
	   }
	   
	    //echo $columnas2, $tablas2, $where2, $id2, '1','<br>'; die();
	   
        $html = "";
        $resultSet=$Contribucion->getCantidad("*", $tablas2, $where2);
        $cantidadResult=(int)$resultSet[0]->total;
        
        /* para obtener Sumas*/
        $rsSumatoria1           = $Contribucion->getSumaColumna("aa.valor_sistema_archivo_recaudaciones_detalle", $tablas2, $where2);
        $_total_archivo_sistema = $rsSumatoria1[0]->suma;
        $rsSumatoria2           = $Contribucion->getSumaColumna("aa.valor_final_archivo_recaudaciones_detalle", $tablas2, $where2);
        $_total_archivo_final   = $rsSumatoria1[0]->suma;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$Contribucion->getCondicionesPag($columnas2, $tablas2, $where2, $id2, $limit);
        $total_pages = ceil($cantidadResult/$per_page);
        
        if($cantidadResult>0){
            
            $html.= "<table id='tbl_archivo_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            $i=0;
            $_tipo_recaudacion    = "";
            foreach ($resultSet as $res){
                $i++;
                
                $_tipo_recaudacion  = "CREDITO - ".$res->nombre_tipo_creditos;
                
                $html.='<tr>';
                $html.='<td style="font-size: 18px;">';
                $html.='<span class="pull-right ">
                            <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones_detalle.'"
                            href="#" class="btn btn-sm btn-default label label-warning">
                            <i class="fa fa-edit" aria-hidden="true" ></i>
                            </a></span></td>';
                $html.='<td style="font-size: 11px;">'.$i.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->usuario_usuarios.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->formato_archivo_recaudaciones.'</td>';
                $html.='<td style="font-size: 11px;">'.$_tipo_recaudacion.'</td>';
                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_sistema_archivo_recaudaciones_detalle.'</td>';
                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_final_archivo_recaudaciones_detalle.'</td>';
                
                $html.='</tr>';
            }
            
            
            
            $html.='</tbody>';
            /*para totalizar las filas*/
            $html.='<tfoot>';
            $html.='<tr>';
            $html.='<th colspan="7" ></th>';
            $html.='<th style="text-align: right"; >TOTALES</th>';
            $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_sistema.'</th>';
            $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_final.'</th>';
            $html.='</tr>';
            $html.='</tfoot>';
            $html.='</table>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"buscaAportesCreditos").'';
            $html.='</div>';
            
        }else{
            
            $html.= "<table id='tbl_archivo_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $html.='</tbody>';
            $html.='</table>';
        }
        
        echo json_encode(array('tablaHtml'=>$html,'cantidadRegistros'=>$cantidadResult));
	    
	}
	
	public function BuscarDatosArchivo(){
	    
	    $Contribucion  = new CoreContribucionModel();
	    
	    /* tomar datos de la web */
	    
	    $_id_archivo_recaudaciones_detalle = $_POST['id_archivo_rcaudaciones_detalle'];
	    
	    $columnas1 = "aa.id_archivo_recaudaciones_detalle, aa.valor_sistema_archivo_recaudaciones_detalle, 
                    aa.valor_final_archivo_recaudaciones_detalle, bb.formato_archivo_recaudaciones, cc.cedula_participes, 
                    cc.nombre_participes, cc.apellido_participes";
	    $tablas1   = "core_archivo_recaudaciones_detalle aa
            	    INNER JOIN core_archivo_recaudaciones bb
            	    ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones
            	    INNER JOIN core_participes cc
            	    ON cc.id_participes = aa.id_participes";
	    $where1    = "cc.id_estatus = 1
	                AND aa.id_archivo_recaudaciones_detalle = '$_id_archivo_recaudaciones_detalle'";
	    $id1       = "aa.id_archivo_recaudaciones_detalle ";
	    
	    $rsConsulta1   = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    if(empty($rsConsulta1)){
	        
	        echo json_encode(array('rsRecaudaciones'=>null));
	    }else{
	        echo json_encode(array('rsRecaudaciones'=>$rsConsulta1));
	    }
	}
	
	public function editAporte(){
	    
	    session_start();
	    $error="";
	    $respuesta=array();
	    try {
	        
	        $Participes = new ParticipesModel();
	        $_id_archivo_recaudacion_detalle   = $_POST['id_archivo_recaudaciones_detalle'];
	        $_valor_archivo_recaudacion        = $_POST['valor_final_archivo_recaudaciones_detalle'];
	        
	        $error=error_get_last();
	        if(!empty($error)){    throw new Exception("Variables no definidas"); }
	        
	        $columnas1 = " bb.generado_archivo_recaudaciones, bb.formato_archivo_recaudaciones";
	        $tablas1   = " core_archivo_recaudaciones_detalle aa
            	        INNER JOIN core_archivo_recaudaciones bb
            	        ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones";
	        $where1    = " aa.id_archivo_recaudaciones_detalle = $_id_archivo_recaudacion_detalle ";
	        $id1       = " aa.id_archivo_recaudaciones_detalle";
	        
	        $rsConsulta1   = $Participes->getCondiciones($columnas1, $tablas1, $where1, $id1);	
	            
            $error=pg_last_error();
            if(!empty($error)){ throw new Exception("Fila no reconocida"); }
            
            if( $rsConsulta1[0]->generado_archivo_recaudaciones == 't' ){ throw new Exception("No se puede modificar archivo ya generado"); }
                
            if( sizeof($rsConsulta1) > 0 ){
                
                $colval = "valor_final_archivo_recaudaciones_detalle = '$_valor_archivo_recaudacion' ";
                $tabla = "core_archivo_recaudaciones_detalle";
                $where = "id_archivo_recaudaciones_detalle = '$_id_archivo_recaudacion_detalle'";
                
                $resultado = $Participes->ActualizarBy($colval, $tabla, $where);
                                
                if((int)$resultado < 0){throw new Exception('Error Actualizar Fila Seleccionada');}
                    
                $respuesta['respuesta']=1;
                $respuesta['mensaje']="Valor Aporte Actualizado";
                    
            }else{
                
                $respuesta['respuesta']=1;
                $respuesta['mensaje'] = "Archivo generado no puede modificar el archivo";
            }
            
            
            echo json_encode($respuesta);
	                
	                
	    } catch (Exception $e) {
	        echo '<message> Error Recaudacion \n '.$e->getMessage().'<message>';
	    }
	}
	
	public function GeneraArchivo(){
	    
	    $Contribucion  = new CoreContribucionModel();
	    
	    try {
	        
	        $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
	        $_mes_recaudaciones    = $_POST['mes_recaudaciones'];
	        $_anio_recaudaciones   = $_POST['anio_recaudaciones'];
	        $_formato_recaudacion  = $_POST['formato_recaudaciones'];
	        
	        $error = error_get_last();
	        if(!empty($error)){ throw new Exception('Variables no definidos');}
	        	        
	        /*configurar estructura mes de consulta*/
	        $_mes_recaudaciones = str_pad($_mes_recaudaciones, 2, "0", STR_PAD_LEFT);
	        
	        $_nombre_formato_recaudacion = "";
	        //diferenciar el tipo de recaudacion que va a realizar
	        switch ( $_formato_recaudacion ){
	            
	            case '1':
	                //para cuando sea para cuenta individual
	                $_nombre_formato_recaudacion = "DESCUENTOS APORTES";	                
	                break;
	            case '2':
	                /*para realizar recaudacion por creditos*/	                
	                $_nombre_formato_recaudacion = "DESCUENTOS CREDITOS";
	                break;
	            default:
	                $_nombre_formato_recaudacion = "";
	                break;
	        }
	        	        
	        $columnas1 = "id_archivo_recaudaciones, nombre_archivo_recaudaciones, generado_archivo_recaudaciones";
	        $tablas1   = "core_archivo_recaudaciones";
	        $where1    = "id_entidad_patronal = $_id_entidad_patronal AND anio_archivo_recaudaciones = $_anio_recaudaciones
                          AND mes_archivo_recaudaciones = $_mes_recaudaciones AND formato_archivo_recaudaciones = '$_nombre_formato_recaudacion' ";
	        $id1       = "id_archivo_recaudaciones";
	        
	        $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        
	        $error = pg_last_error();
	        if(!empty($error)){ throw new Exception('Datos enviados no validos'); }
	        
	        if(!empty($rsConsulta1)){
	            $_generado_arhivo          = $rsConsulta1[0]->generado_archivo_recaudaciones;
	            $_id_archivo_recaudaciones = $rsConsulta1[0]->id_archivo_recaudaciones;
	            if( $_generado_arhivo == 't' ){
	                /*buscar el archivo en el directorio*/
	            }else{	                
	                
	                /* buscar nombre entidad patronal */
	                $columnas2 = "id_entidad_patronal, nombre_entidad_patronal";
	                $tablas2   = "core_entidad_patronal";
	                $where2    = "id_entidad_patronal = $_id_entidad_patronal";
	                $id2       = "id_entidad_patronal";
	                $rsConsulta2   = $Contribucion->getCondiciones($columnas2, $tablas2, $where2, $id2);
	                $_nombre_entidad_patronal  = $this->limpiarCaracteresEspeciales($rsConsulta2[0]->nombre_entidad_patronal);
	                $datos_archivo = $this->obtienePath($_nombre_entidad_patronal, $_anio_recaudaciones, $_mes_recaudaciones, "ARCHIVOSENVIAR");
	                $_nombre_archivo_recaudaciones = $datos_archivo['nombre'];
	                $_ruta_archivo_recaudaciones   = $datos_archivo['ruta'];
	                
	                /*buscar datos de vista para generar el archivo*/
	                $columnas3 = "id_archivo_recaudaciones,id_participes, formato_archivo_recaudaciones, cedula_participes, nombre_participes, apellido_participes,
                                valor_recaudaciones, sueldo_liquido_contribucion_tipo_participes, anio_archivo_recaudaciones, mes_archivo_recaudaciones
                                anio_archivo_recaudaciones, mes_archivo_recaudaciones";
	                $tablas3   = "public.vw_archivo_recaudaciones";
	                $where3    = "id_archivo_recaudaciones = $_id_archivo_recaudaciones";
	                $id3       = "id_participes";
	                
	                
	                $rsConsulta3   = $Contribucion->getCondiciones($columnas3, $tablas3, $where3, $id3);
	                
	                $data = 'NUMERO'.";".'TIPO DESCUENTO'.";".'CEDULA'.";".'NOMBRE'.";".'SUELDO LIQUIDO'.";".'DESCUENTO'.";".'TOTAL'.";".'AÑO DESCUENTO'.";".'MES DESCUENTO'.PHP_EOL;
	                $numero = 0;
	                foreach($rsConsulta3 as $res){
	                    $numero += 1;
	                    $tipo_contribucion     = $res->formato_archivo_recaudaciones;
	                    $cedula_participe      =  $res->cedula_participes;
	                    $apellido_participe    =  $res->apellido_participes;
	                    $nombre_participe      =  $res->nombre_participes;
	                    $sueldo_participe      =  $res->sueldo_liquido_contribucion_tipo_participes;
	                    $valor_descuento       =  $res->valor_recaudaciones;
	                    $total_descuento       =  $res->valor_recaudaciones;
	                    $anio_recaudacion      =  $res->anio_archivo_recaudaciones;
	                    $mes_recaudacion       =  $res->mes_archivo_recaudaciones;
	                    
	                    $data.=$numero.";".$tipo_contribucion.";".$cedula_participe.";".$apellido_participe." ".$nombre_participe.";";
	                    $data.=$sueldo_participe.";".$valor_descuento.";".$total_descuento.";".$anio_recaudacion.";".$mes_recaudacion.PHP_EOL;
	                    
	                }
	                
	                $archivo = fopen($_ruta_archivo_recaudaciones, 'w');
	                fwrite($archivo, $data);
	                fclose($archivo);
	                
	                $error = error_get_last();
	                if(!empty($error)){
	                    throw new Exception('Archivo no generado');
	                }
	                
	                //para actualizacion
	                $actColumnas = "generado_archivo_recaudaciones = 't', 
                                ruta_archivo_recaudaciones = '$_ruta_archivo_recaudaciones',
                                nombre_archivo_recaudaciones = '$_nombre_archivo_recaudaciones'";
	                $actTablas = "core_archivo_recaudaciones";
	                $actWhere = "id_archivo_recaudaciones = $_id_archivo_recaudaciones ";
	                
	                $resultado = $Contribucion->ActualizarBy($actColumnas, $actTablas, $actWhere);
	                
	                echo json_encode(array('mensaje'=>'archivo generado'));
	                
	            }
	            
	            
	        }else{
	            
	            /*para validar si esxite el archivo*/
	            throw new Exception('Distribucion No generada !Favor Realizar primero');
	        }
	        
	       
	        
	    } catch (Exception $e) {
	        
	        echo '<message>'.$e->getMessage().'<message>';
	    }
	    
	    
	}
	
	public function ConsultaArchivosGenerados(){
	    
	    $EntidadPatronal = new EntidadPatronalParticipesModel();
	    
	    $columnas = " id_archivo_recaudaciones,
                    formato_archivo_recaudaciones,
                    nombre_archivo_recaudaciones,
                    ruta_archivo_recaudaciones,
                    usuario_usuarios,
                    date(creado) creado,
                    date(modificado) modificado";
	    
	    $tablas = " public.core_archivo_recaudaciones";
	    
	    $where    = " 1 = 1 AND generado_archivo_recaudaciones = true ";
	    
	    $id = "creado";
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['busqueda'])&& $_REQUEST['busqueda'] !=NULL)?$_REQUEST['busqueda']:'';
	    
	    if($action == 'ajax'){
	        
	        if(!empty($search)){
	            $where1=" AND ( nombre_documentos_recaudaciones ILIKE '".$search."%' )";
	            $where_to=$where.$where1;
	        }else{
	            $where_to=$where;
	        }
	        
	        $html = "";
	        $resultSet=$EntidadPatronal->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$EntidadPatronal->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult>0){
	            
	            $html.= "<table id='tbl_documentos_recaudaciones' class='table table-striped table-bordered'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Formato</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ruta</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">creado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">modificado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res){
	                $i++;
	                $ruta = '..'.substr($res->ruta_archivo_recaudaciones, 0);
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->formato_archivo_recaudaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_archivo_recaudaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$ruta.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->usuario_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->creado.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->modificado.'</td>';
	                $html.='<td style="font-size: 18px;">';
	                $html.='<span class="pull-right ">
                                    <a onclick="verArchivo(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones.'"
                                    href="#" class="btn btn-sm btn-default label label-info">
                                    <i class="fa  fa-file-text" aria-hidden="true" ></i>
                                    </a></span></td>';
	                $html.='</tr>';
	               
	            }
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaArchivos").'';
	            $html.='</div>';
	            
	        }else{
	            
	            $html.= "<table id='tbl_documentos_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Formato</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ruta</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">creado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">modificado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	        }
	        
	        echo json_encode(array('tablaHtml'=>$html));
	    }
	    
	}
	
	public function descargarArchivo(){
	    
	    $_id_archivo_recaudaciones = $_POST['id_archivo_recaudaciones'];
	    $Participes = new ParticipesModel();
	    
	    /* consulta para traer datos del archivo de recaudacones*/
	    $columnas1 = "id_archivo_recaudaciones, nombre_archivo_recaudaciones, ruta_archivo_recaudaciones";
	    $tablas1   = "core_archivo_recaudaciones";
	    $where1 = " id_archivo_recaudaciones = $_id_archivo_recaudaciones";
	    $id1 = "id_archivo_recaudaciones";
	    
	    $rsConsulta1 = $Participes->getCondiciones($columnas1,$tablas1,$where1,$id1);
	    
	    $nombre_archivo    = $rsConsulta1[0]->nombre_archivo_recaudaciones;
	    $ruta_archivo      = $rsConsulta1[0]->ruta_archivo_recaudaciones;       
	    
	    $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\";
	    $ubicacion = $ubicacionServer.$ruta_archivo;
	    	    
	    
	    // Define headers
	    header("Content-disposition: attachment; filename=$nombre_archivo");
	    header("Content-type: MIME");
	    ob_clean();
	    flush();
	    // Read the file
	    readfile($ubicacion);
	    exit;
	    
	}
	
	
	public function gen1(){
	    
	    
	    session_start();
	    $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $Participes = new ParticipesModel();
	    
	    $_id_entidad_patronal = $_POST['id_entidad_patronal'];
	    $_anio_recaudacion = $_POST['anio_recaudacion'];
	    $_mes_recaudacion = $_POST['mes_recaudacion'];
	    
	    $queryEntidadPatronal = "SELECT * FROM core_entidad_patronal WHERE id_entidad_patronal = '$_id_entidad_patronal'";
	    $rsEntidad=$Participes->enviaquery($queryEntidadPatronal);	    
	    $nombre_entidad = $this->limpiarCaracteresEspeciales($rsEntidad[0]->nombre_entidad_patronal);
	    
	    $columnas = "car.id_archivo_recaudaciones,
    	    cct.nombre_contribucion_tipo,
    	    cp.cedula_participes,
    	    cp.apellido_participes,
    	    cp.nombre_participes,
    	    cctp.sueldo_liquido_contribucion_tipo_participes,
    	    car.valor_final_archivo_recaudaciones,
    	    (cctp.sueldo_liquido_contribucion_tipo_participes-cctp.valor_contribucion_tipo_participes) \"total\",
    	    car.anio_archivo_recaudaciones,
    	    car.mes_archivo_recaudaciones,
            car.generado_archivo_recaudaciones";
	    
	    $tablas= "core_archivo_recaudaciones car
    	    INNER JOIN core_contribucion_tipo_participes cctp
    	    ON car.id_participes = cctp.id_participes
    	    INNER JOIN core_contribucion_tipo cct
    	    ON cctp.id_contribucion_tipo = cct.id_contribucion_tipo
    	    INNER JOIN core_participes cp
    	    ON cp.id_participes = cctp.id_participes
    	    INNER JOIN core_tipo_aportacion ctp
    	    ON ctp.id_tipo_aportacion = cctp.id_tipo_aportacion";
	    
	    $where= " 1 = 1
	       AND cct.nombre_contribucion_tipo = 'Aporte Personal'
           AND car.anio_archivo_recaudaciones = '$_anio_recaudacion'
           AND car.mes_archivo_recaudaciones = '$_mes_recaudacion'
           AND car.id_entidad_patronal = '$_id_entidad_patronal'";
	    
	    $id= "cp.nombre_participes";	   
	    $resultSet=$Participes->getCondiciones($columnas, $tablas, $where, $id);
	    
	    if( $resultSet[0]->generado_archivo_recaudaciones =="t"){
	       echo '<message>Archivo ya se encuentra generado<message>';
	       exit();
	    }
	    
	    $fecha = date('Yd');
	    $my_file = $nombre_entidad.$fecha.'.txt';
	    //no guardar con 'document_root'
	    $ubicacionServer = $_SERVER['DOCUMENT_ROOT'];
	    $ubicacionLocal = '/rp_c/DOCUMENTOS_GENERADOS/RECAUDACIONES';
	    $data = 'NUMERO'.";".'TIPO DESCUENTO'.";".'CEDULA'.";".'NOMBRE'.";".'SUELDO LIQUIDO'.";".'DESCUENTO'.";".'TOTAL'.";".'AÑO DESCUENTO'.";".'MES DESCUENTO'.PHP_EOL;
	    
	    //para ubicacion del archivo
	    $funcionDocumentos = "ins_documentos_recaudaciones";
	    $parametrosDocumentos = " '$_usuario_usuarios','$my_file', '$ubicacionLocal' ";
	    $consultaPG = "SELECT ".$funcionDocumentos."(".$parametrosDocumentos.")";
	    $resultado = $Participes->llamarconsultaPG($consultaPG);
	    $_id_documentos = ((int)$resultado[0] > 0) ? $resultado[0] : -1;
	    
	    //para actualizacion
	    $actColumnas = "generado_archivo_recaudaciones = 't', id_documentos_recaudaciones = '$_id_documentos'";
	    $actTablas = "core_archivo_recaudaciones";
	    foreach ($resultSet as $res){
	        
	        $id_archivo = $res->id_archivo_recaudaciones;
	        $actWhere = "id_archivo_recaudaciones = $id_archivo ";
	        
	        $resultado = $Participes->ActualizarBy($actColumnas, $actTablas, $actWhere);
	    }
	    
	    $numero = 0;
	    foreach($resultSet as $res){
	        $numero += 1;	        
	        $tipo_contribucion = $res->nombre_contribucion_tipo;
	        $cedula_participe =  $res->cedula_participes;
	        $apellido_participe =  $res->apellido_participes;
	        $nombre_participe =  $res->nombre_participes;
	        $sueldo_participe =  $res->sueldo_liquido_contribucion_tipo_participes;
	        $valor_descuento =  $res->valor_final_archivo_recaudaciones;
	        $total_descuento =  $res->total;
	        $anio_recaudacion =  $res->anio_archivo_recaudaciones;
	        $mes_recaudacion =  $res->mes_archivo_recaudaciones;
	        
	        $data.=$numero.";".$tipo_contribucion.";".$cedula_participe.";".$apellido_participe." ".$nombre_participe.";";
	        $data.=$sueldo_participe.";".$valor_descuento.";".$total_descuento.";".$anio_recaudacion.";".$mes_recaudacion.PHP_EOL;
	       
	    }
	    	    
	    $archivo = fopen($ubicacionServer.$ubicacionLocal.'/'.$my_file, 'w');
	    fwrite($archivo, $data);
	    fclose($archivo);
	    
	    $error = error_get_last();
	    if(!empty($error)){
	        echo '<message>Archivo no generado<message>'; die();
	    }
	    
	    echo json_encode(array("respuesta"=>1,"mensaje"=>"Archivo generado"));
	    
	}
	
	public function indexRecaudacionAP(){
	    
	    $EntidadPatronal = new EntidadPatronalParticipesModel();
	    
	    $_anio_recaudacion = $_POST['anio_recaudacion'];
	    $_mes_recaudacion = $_POST['mes_recaudacion'];
	    $_id_entidad_patronal = $_POST['id_entidad_patronal'];	        
	        	    
	    $columnas = " car.id_archivo_recaudaciones,
            		car.usuario_usuarios,
                    car.valor_final_archivo_recaudaciones,
                    car.valor_sistema_archivo_recaudaciones,
            		cctp.id_contribucion_tipo_participes,
            		cctp.valor_contribucion_tipo_participes,
            		cctp.sueldo_liquido_contribucion_tipo_participes,
            		cp.id_participes,
            		cp.cedula_participes,
            		cp.apellido_participes,
            		cp.nombre_participes,
            		cct.id_contribucion_tipo,
            		cct.nombre_contribucion_tipo,
            		ctp.id_tipo_aportacion,
            		ctp.nombre_tipo_aportacion";
	    
	    $tablas = " core_archivo_recaudaciones car
                    inner join core_contribucion_tipo_participes cctp
                    on car.id_participes = cctp.id_participes
                    inner join core_contribucion_tipo cct
                    on cctp.id_contribucion_tipo = cct.id_contribucion_tipo
                    inner join core_participes cp
                    on cp.id_participes = cctp.id_participes
                    inner join core_tipo_aportacion ctp
                    on ctp.id_tipo_aportacion = cctp.id_tipo_aportacion
                    inner join estado e
                    on e.id_estado = cctp.id_estado";
	    
	    $where    = " 1 = 1
                    AND e.nombre_estado = 'ACTIVO'
                    AND cct.nombre_contribucion_tipo = 'Aporte Personal'
                    AND car.anio_archivo_recaudaciones = '$_anio_recaudacion'
                   AND car.mes_archivo_recaudaciones = '$_mes_recaudacion'
                   AND car.id_entidad_patronal = '$_id_entidad_patronal'";
	    
	    $id = "cp.apellido_participes";
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['busqueda'])&& $_REQUEST['busqueda'] !=NULL)?$_REQUEST['busqueda']:'';
	    
	    if($action == 'ajax'){
	        
	        if(!empty($search)){
	            $where1=" AND (cp.cedula_participes ILIKE '".$search."%' OR cp.apellido_participes  ILIKE '".$search."%' OR cp.nombre_participes ILIKE '".$search."%' )";
	            $where_to=$where.$where1;
	        }else{
	            $where_to=$where;
	        }
	        
	        $html = "";
	        $resultSet=$EntidadPatronal->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$EntidadPatronal->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult>0){
	            
	            $html.= "<table id='tbl_archivo_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            $i=0;
	            
	            foreach ($resultSet as $res){
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->usuario_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_contribucion_tipo.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_aportacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->valor_sistema_archivo_recaudaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->valor_final_archivo_recaudaciones.'</td>';
	                $html.='<td style="font-size: 18px;">';
	                $html.='<span class="pull-right ">
                                    <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones.'"
                                    data-valorinicial="'.$res->valor_sistema_archivo_recaudaciones.'" data-valorfinal="'.$res->valor_final_archivo_recaudaciones.'"
                                    data-metodo_descuento="'.$res->nombre_tipo_aportacion.'"
                                    href="#" class="btn btn-sm btn-default label label-warning">
                                    <i class="fa fa-edit" aria-hidden="true" ></i>
                                    </a></span></td>';
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"").'';
	            $html.='</div>'; 
	            
	        }else{
	            
	            $html.= "<table id='tbl_archivo_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            $html.='</tbody>';
	            $html.='</table>';
	        }
	        
	        echo json_encode(array('tablaHtml'=>$html));
	    }
	    
	}
	
	public function indexArchivosAP(){
	    
	    $EntidadPatronal = new EntidadPatronalParticipesModel();	    
	    
	    $columnas = " id_documentos_recaudaciones,
                    nombre_documentos_recaudaciones,
                    ruta_documentos_recaudaciones,
                    usuario_usuarios,
                    date(creado) creado,
                    date(modificado) modificado";
	    
	    $tablas = " public.core_documentos_recaudaciones";
	    
	    $where    = " 1 = 1";
	    
	    $id = "creado";
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['busqueda'])&& $_REQUEST['busqueda'] !=NULL)?$_REQUEST['busqueda']:'';
	    
	    if($action == 'ajax'){
	        
	        if(!empty($search)){
	            $where1=" AND ( nombre_documentos_recaudaciones ILIKE '".$search."%' )";
	            $where_to=$where.$where1;
	        }else{
	            $where_to=$where;
	        }
	        
	        $html = "";
	        $resultSet=$EntidadPatronal->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$EntidadPatronal->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult>0){
	            
	            $html.= "<table id='tbl_documentos_recaudaciones' class='table table-striped table-bordered'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ruta</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">creado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">modificado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	           
	            
	            $i=0;
	            
	            foreach ($resultSet as $res){
	                $i++;
	                $ruta = '..'.substr($res->ruta_documentos_recaudaciones, -35).'/..';
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_documentos_recaudaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$ruta.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->usuario_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->creado.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->modificado.'</td>';
	                $html.='<td style="font-size: 18px;">';
	                $html.='<span class="pull-right ">
                                    <a onclick="verArchivo(this)" id="" data-idarchivo="'.$res->id_documentos_recaudaciones.'"                                    
                                    href="#" class="btn btn-sm btn-default label label-info">
                                    <i class="fa  fa-file-text" aria-hidden="true" ></i>
                                    </a></span></td>';
	                $html.='</tr>';
	            }	            
	          
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaArchivos").'';
	            $html.='</div>';
	            
	        }else{
	            
	            $html.= "<table id='tbl_documentos_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ruta</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">creado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">modificado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	        }
	        
	        echo json_encode(array('tablaHtml'=>$html));
	    }
	    
	}
	
	//para paginacion
	public function paginate($reload, $page, $tpages, $adjacents, $funcion="") {
	    
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
	
	
	
	/**
	 * funcion que devuele array con el nombre y la ruta de archivo
	 * @param int $anioArchivo
	 * @param int $mesArchivo
	 */
	private function obtienePath($nombreArchivo,$anioArchivo,$mesArchivo,$folder){
	    
	    $respuesta     = array();
	    $nArchivo      = $nombreArchivo.$mesArchivo.$anioArchivo.".txt";
	    $carpeta_base      = 'view\\Recaudaciones\\documentos\\'.$folder.'\\';
	    $_carpeta_buscar   = $carpeta_base.$anioArchivo;
	    $file_buscar       = "";
	    if( file_exists($_carpeta_buscar)){
	        
	        $_carpeta_buscar   = $carpeta_base.$anioArchivo."\\".$mesArchivo;
	        if( file_exists($_carpeta_buscar)){
	            
	            $file_buscar = $_carpeta_buscar."\\".$nArchivo;
	            
	            
	        }else{
	            
	            mkdir($_carpeta_buscar, 0777, true);
	            $file_buscar = $_carpeta_buscar."\\".$nArchivo;
	            
	        }
	        
	    }else{
	        
	        mkdir($_carpeta_buscar."\\".$mesArchivo, 0777, true);
	        $file_buscar = $_carpeta_buscar."\\".$mesArchivo."\\".$nArchivo;
	    }
	    
	    $respuesta['nombre']   = $nArchivo;
	    $respuesta['ruta']     = $file_buscar;
	    
	    return $respuesta;
	}
	
	function limpiarCaracteresEspeciales($string ){
	    $string = htmlentities($string);
	    $string = preg_replace('/\&(.)[^;]*;/', '', $string);
	    return $string;
	}
	
	function verPath(){
	    echo $_SERVER['DOCUMENT_ROOT']."\\rp_c\\";
	}
	
	
	
}
?>