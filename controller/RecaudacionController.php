<?php

class RecaudacionController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
		
     	$EntidadPatronal = new EntidadPatronalParticipesModel();
     		
		if (isset(  $_SESSION['nombre_usuarios']) ){

			$nombre_controladores = "Grupos";
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
	
	public function RecaudacionSimular(){
	    
	    $EntidadPatronal = new EntidadPatronalParticipesModel();
	    $respuesta = array();
	    $error="";
	    
	    try{
	       	        
	        session_start();
	        $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	        $_id_entidad_patronal = $_POST['id_entidad_patronal'];
	        $_anio_recaudacion = $_POST['anio_recaudacion'];
	        $_mes_recaudacion = $_POST['mes_recaudacion'];
	        
	        $error = error_get_last();
	        if(!empty($error))
	            throw new Exception('Variables Desconocidas'.$error['message']);
	            
	            //buscar en tabla si hay datos de recaudacion
	            $queryValidacion = "SELECT * FROM core_archivo_recaudaciones
                WHERE mes_archivo_recaudaciones = '".$_mes_recaudacion."'
                AND anio_archivo_recaudaciones = '".$_anio_recaudacion."'
                AND id_entidad_patronal = '$_id_entidad_patronal'";
	            
	            $rsEntidadPatronal = $EntidadPatronal->enviaquery($queryValidacion);
	            $error = pg_last_error();
	            if(!empty($error))
	                throw new Exception('Error busqueda BD '.$error['message']);
	                
                //para graficar la tabla
                $funcion = "ins_archivo_recaudaciones";
                $parametros = "'$_usuario_usuarios','$_id_entidad_patronal','$_anio_recaudacion','$_mes_recaudacion'";
	                
                if(empty($rsEntidadPatronal)){
                    //realiza insertado de archivo recaudaciones
                    $consultaPG = "SELECT ".$funcion."(".$parametros.")";
                    $resultado = $EntidadPatronal->llamarconsultaPG($consultaPG);
                    $error = pg_last_error();
                    if(is_null($resultado) || !empty($error))
                        throw new Exception("Revisar tabla archivo recaudacion ");
                    
                     //volver a consultar 
                    $rsEntidadPatronal = $EntidadPatronal->enviaquery($queryValidacion);                    
                    if(!empty($rsEntidadPatronal)){                        
                        $respuesta['respuesta'] = 1;
                        $respuesta['mensaje'] = 'Distribucion Realizada';
                    }else{
                        $respuesta['respuesta'] = 2;
                        $respuesta['mensaje'] = 'Distribucion Realizada con cero participes';
                    }
                            
                }else{
                    
                    $generado = $rsEntidadPatronal[0]->generado_archivo_recaudaciones;
                    $mensaje =  ($generado=="t") ? "Atencion Archivo ya se encuentra generado" : "Revise aportacion participe" ;                 
                    $respuesta['respuesta']=2;
                    $respuesta['mensaje'] = $mensaje;
                    
                }
	                
	                echo json_encode($respuesta);
	                
	    } catch (Exception $ex) {
	        
	        echo '<message>Error Archivo Recaudacion \n'.$ex->getMessage().' <message>';
	    }	    
	    
	}
	
	public function editAporte(){
	    
	    session_start();
	    $error="";
	    $respuesta=array();
	    try {
	        
	        $Participes = new ParticipesModel(); 
	        $_id_archivo_recaudacion = $_POST['id_archivo_recaudaciones'];
	        $_valor_archivo_recaudacion = $_POST['valor_final_archivo_recaudaciones'];
	        
	        $error=error_get_last();
	        if(!empty($error))
	            throw new Exception("Variables no definidas");
	        
            $consulta = $Participes->enviaquery("SELECT 1  FROM core_archivo_recaudaciones 
                        WHERE id_archivo_recaudaciones = '$_id_archivo_recaudacion' AND generado_archivo_recaudaciones = 'f'");
            
            $error=pg_last_error();
            if(!empty($error))
                throw new Exception("Fila no reconocida");
            
            if(sizeof($consulta) > 0 ){                
                
                $colval = "valor_final_archivo_recaudaciones = '$_valor_archivo_recaudacion' ";
                $tabla = "core_archivo_recaudaciones";
                $where = "id_archivo_recaudaciones = '$_id_archivo_recaudacion'";
                
                $resultado = $Participes->ActualizarBy($colval, $tabla, $where);
                
                if((int)$resultado < 0)
                    throw new Exception('Error Actualizar Datos');
                    
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
	    //$ubicacionServer = $_SERVER['DOCUMENT_ROOT'];
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
	    	    
	    $archivo = fopen($ubicacionLocal.'/'.$my_file, 'w');
	    fwrite($archivo, $data);
	    fclose($archivo);
	    
	    $error = error_get_last();
	    if(!empty($error)){
	        echo '<message>Archivo no generado<message>';
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
	
	public function descargarArchivo(){
	    
	    $_id_documento = $_POST['id_documentos_recaudaciones'];
	    $Participes = new ParticipesModel();
	    
	    $query = " SELECT id_documentos_recaudaciones,
                nombre_documentos_recaudaciones,
                ruta_documentos_recaudaciones,
                usuario_usuarios                
                FROM core_documentos_recaudaciones                
                WHERE id_documentos_recaudaciones = '$_id_documento'";
	    
	    $rsDocumentos = $Participes->enviaquery($query);
	    
	    $nombre_documento = $rsDocumentos[0]->nombre_documentos_recaudaciones;
	    $ruta_documento = $rsDocumentos[0]->ruta_documentos_recaudaciones;
	    
	    $ubicacionServer = $_SERVER['DOCUMENT_ROOT'];
	    $ubicacion = $ubicacionServer.$ruta_documento.'/'.$nombre_documento;
	    
	    // Define headers
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	    header("Content-Disposition: attachment; filename=$nombre_documento");
	    header("Content-Type: application/zip");
	    header("Content-Transfer-Encoding: binary");
	    
	    // Read the file
	    readfile($ubicacion);
	    exit;
	    
	}
	
	function limpiarCaracteresEspeciales($string ){
	    $string = htmlentities($string);
	    $string = preg_replace('/\&(.)[^;]*;/', '', $string);
	    return $string;
	}
	
	
	
}
?>