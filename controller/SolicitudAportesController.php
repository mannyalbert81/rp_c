<?php

class SolicitudAportesController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}
		  
    
	public function index(){
	
	    session_start();
		
     	$EntidadPatronal = new EntidadPatronalParticipesModel();
     		
		if( isset(  $_SESSION['nombre_usuarios'] ) ){

			$nombre_controladores = "SolicitudAportes";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $EntidadPatronal->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer)){
			    
			    $queryEntidad = "SELECT * FROM core_entidad_patronal ORDER BY nombre_entidad_patronal";			    
			    $rsEntidadPatronal = $EntidadPatronal->enviaquery($queryEntidad);
			
			    $this->view_Recaudaciones("SolicitudAportes",array(
			        'rsEntidadPatronal' => $rsEntidadPatronal
			    ));
				
			}else{
			    
			    $this->view("Error",array(
			        "resultado"=>"No tiene Permisos de Acceso a Solicitud de Aportaciones"
			        
			    ));
			    
			    exit();				    
			}
				
		}else{
       	
		    $this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	
	public function LoadSolicitudesAportes(){
	    
	    $Participes = new ParticipesModel();
	    
	    if( !isset($_SESSION) ){
	        session_start();
	    }
	    
	    $_id_rol = $_SESSION['id_rol'];
	    
	    /** datos recibidos de vista **/
	    //$_cedula_participe         = $_GET['cedula'];
	    $page = (isset($_REQUEST['pagina']) && !empty($_REQUEST['pagina']))?$_REQUEST['pagina']:1;
	    
	    $error = error_get_last();
	    if( !empty($error)){ echo "Datos no enviados"; exit();}
	    
	    /** datos de los roles */
	    // 51 Jefe de recaudaciones
	    // 61 Gerente
	    
	    /** buscar roles */	    
        //consulta para buscar el estado
        $columnas1 = " aa.id_solicitud_valor_aportacion, cc.id_participes, cc.cedula_participes, cc.nombre_participes, cc.apellido_participes, bb.id_tipo_aportacion, bb.nombre_tipo_aportacion,
                	aa.valor_solicitud_valor_aportacion, aa.porcentaje_solicitud_valor_aportacion, aa.observacion_solicitud_valor_aportacion,
                	to_char(aa.creado,'YYYY-MM-DD') creado, dd.id_estado, dd.nombre_estado";
        $tablas1   = " solicitud_valor_aportacion aa
            	INNER JOIN core_tipo_aportacion bb ON bb.id_tipo_aportacion = aa.id_tipo_aportacion
            	INNER JOIN core_participes cc ON cc.id_participes = aa.id_participes
            	INNER JOIN estado dd ON dd.id_estado = aa.id_estado";
        $where1    = " 1 = 1 ";
        $id1       = " cc.id_participes";
        
        $resultSet=$Participes->getCantidad("*", $tablas1, $where1);
        $cantidadResult=(int)$resultSet[0]->total;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$Participes->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
        $total_pages = ceil($cantidadResult/$per_page);	        
        
        if( !empty($resultSet) ){
            
            $_html_filas = "";           
            $_indice = 0;
            $_valor_print = "";
            $_nombre_tipo = "";
            $_html_btn_Aprobar = "";
            $_html_btn_Negar = "";
            foreach ( $resultSet as $res ){
                $_html_filas .= "<tr>";
                $_indice ++;
                $_nombre_tipo = $res->nombre_tipo_aportacion;
                $_nombre_estado = $res->nombre_estado;
                
                if( $_nombre_tipo == "VALOR" ){
                    $_valor_print = number_format((float)$res->valor_solicitud_valor_aportacion,2,".",",");
                }else{
                    $_valor_print = number_format((float)$res->porcentaje_solicitud_valor_aportacion,2,".",",");
                    $_valor_print = $_valor_print."%";
                }
                
                $_html_filas .= '<td style="font-size: 11px;" >'.$_indice.'</td>';
                $_html_filas .= '<td style="font-size: 11px;" >'.$res->cedula_participes.'</td>';
                $_html_filas .= '<td style="font-size: 11px;" >'.$res->nombre_participes.'</td>';
                $_html_filas .= '<td style="font-size: 11px;" >'.$res->apellido_participes.'</td>';
                $_html_filas .= '<td style="font-size: 11px;" >'.$_nombre_tipo.'</td>';
                $_html_filas .= '<td style="text-align: right; font-size: 11px;">'.$_valor_print.'</td>';
                $_html_filas .= '<td style="font-size: 11px;" >'.$res->observacion_solicitud_valor_aportacion.'</td>';
                $_html_filas .= '<td style="font-size: 11px;" >'.$res->creado.'</td>';
                $_html_filas .= '<td style="font-size: 11px;" >'.$_nombre_estado.'</td>';
                
                /** validar roles para dar prioridades **/
                if( $_nombre_estado == 'EN REVISION' && $_id_rol == 51 ){
                    
                    $_html_btn_Aprobar =  '<span class="pull-right "> <a onclick="AprobarRecaudaciones(this)" id="" data-identificador="'.$res->id_solicitud_valor_aportacion.'"
                                    href="#" class="btn btn-sm  label label-success"> <i class="fa  fa-check " aria-hidden="true" ></i></a></span>';
                    $_html_btn_Negar =  '<span class="pull-right "> <a onclick="AnularRecaudaciones(this)" id="" data-identificador="'.$res->id_solicitud_valor_aportacion.'"
                                    href="#" class="btn btn-sm label label-danger"> <i class="fa  fa-times" aria-hidden="true" ></i></a></span>';
                    $_html_filas .= '<td>'.$_html_btn_Aprobar.'</td>';
                    $_html_filas .= '<td>'.$_html_btn_Negar.'</td>';
                }else if( $_nombre_estado == 'APROBADO RECAUDACIONES' && $_id_rol == 61 ){
                    $_html_btn_Aprobar =  '<span class="pull-right "> <a onclick="AprobarGerencia(this)" id="" data-identificador="'.$res->id_solicitud_valor_aportacion.'"
                                    href="#" class="btn btn-sm  label label-success"> <i class="fa  fa-check " aria-hidden="true" ></i></a></span>';
                    $_html_btn_Negar =  '<span class="pull-right "> <a onclick="AnularGerencia(this)" id="" data-identificador="'.$res->id_solicitud_valor_aportacion.'"
                                    href="#" class="btn btn-sm label label-danger"> <i class="fa  fa-times" aria-hidden="true" ></i></a></span>';
                    $_html_filas .= '<td>'.$_html_btn_Aprobar.'</td>';
                    $_html_filas .= '<td>'.$_html_btn_Negar.'</td>';
                }else{
                    $_html_filas .= '<td>'.''.'</td>';
                    $_html_filas .= '<td>'.''.'</td>';
                }
                
                $_html_filas .= "</tr>";
            }
            
            
            $_html_paginacion = $this->paginate("index.php", $page, $total_pages, $adjacents,"loadSolicitudAportes").'';
            
            echo json_encode(array("respuesta"=>"1","dataFilas"=>$_html_filas,"paginacion"=>$_html_paginacion));
            exit();
            
        }else{
            $_html_filas = "<tr>";
            $_html_filas .= '<td colspan="9"> Actualmente no existe Solicitudes </td>';
            $_html_filas .= "</tr>";
            
            $_html_paginacion = "";
            echo json_encode(array("respuesta"=>"1","dataFilas"=>$_html_filas,"paginacion"=>$_html_paginacion));
            exit();
        }
	    
	    
	}
	
	public function AprobarSolicitud(){
	    
	    /** tomar variables de la vista */	    
	    $_departamento = $_POST['departamento'];
	    $_id_solicitud_aportes = $_POST['id_solicitud'];
	    
	    /** variables locales **/
	    $respuesta = array();
	    
	    $Participes = new ParticipesModel();
	    
	    if( $_departamento == "recaudaciones" ){
	        
	        $columnas1 = "*";
	        $tablas1 = "estado";
	        $where1 = "nombre_estado = 'APROBADO RECAUDACIONES' AND tabla_estado = 'solicitud_valor_aportacion' ";
	        $id1 = "id_estado";
	        $rsConsulta1 = $Participes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        $_id_estado = $rsConsulta1[0]->id_estado;
	        
	        $colval = " id_estado = '$_id_estado' ";
	        $tabla = " solicitud_valor_aportacion";
	        $where = "id_solicitud_valor_aportacion = '$_id_solicitud_aportes'";
	        
	        $resultado = $Participes->ActualizarBy($colval, $tabla, $where);
	        
	        if((int)$resultado < 0){ echo ('Error Actualizar Fila Seleccionada');}
	        
	        $respuesta['respuesta']=1;
	        $respuesta['mensaje']="Valor Aporte Actualizado";
	        
	        echo json_encode($respuesta);
	        exit();
	        
	    }
	    
	    if( $_departamento == "gerencia" ){
	        
	        $Participes->beginTran();
	        
	        $columnas1 = "*";
	        $tablas1 = "estado";
	        $where1 = "nombre_estado = 'APROBADO GERENCIA' AND tabla_estado = 'solicitud_valor_aportacion'";
	        $id1 = "id_estado";
	        $rsConsulta1 = $Participes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        $_id_estado = $rsConsulta1[0]->id_estado;
	        
	        $colval = " id_estado = '$_id_estado' ";
	        $tabla = " solicitud_valor_aportacion";
	        $where = "id_solicitud_valor_aportacion = '$_id_solicitud_aportes'";	        
	        $resultado = $Participes->ActualizarBy($colval, $tabla, $where);
	        
	        /** Aqui va actualizacion en core_contribucion_participes **/
	        
	        $where1 = "nombre_estado = 'ACTIVO' AND tabla_estado = 'core_contribucion_tipo_participes'";
	        $rsConsulta1 = $Participes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        $_id_estado = $rsConsulta1[0]->id_estado;
	        
	        $columnas2 = "id_participes,id_tipo_aportacion,valor_solicitud_valor_aportacion,porcentaje_solicitud_valor_aportacion";
	        $tablas2 = "solicitud_valor_aportacion";
	        $where2 = "id_solicitud_valor_aportacion = $_id_solicitud_aportes";
	        $id2 = "id_participes";
	        $rsConsulta2 = $Participes->getCondiciones($columnas2, $tablas2, $where2, $id2);
	        $_id_participe = $rsConsulta2[0]->id_participes;
	        $_id_tipo_aportacion = $rsConsulta2[0]->id_tipo_aportacion;
	        $_valor_aportacion = $rsConsulta2[0]->valor_solicitud_valor_aportacion;
	        $_porcentaje_aportacion = $rsConsulta2[0]->porcentaje_solicitud_valor_aportacion;
	        
	        $columnas3 = "id_contribucion_tipo,nombre_contribucion_tipo";
	        $tablas3 = "core_contribucion_tipo";
	        $where3 = "UPPER(nombre_contribucion_tipo) = 'APORTE PERSONAL'";
	        $id3 = "id_contribucion_tipo";
	        $rsConsulta3 = $Participes->getCondiciones($columnas3, $tablas3, $where3, $id3);
	        $_id_contribucion_tipo = $rsConsulta3[0]->id_contribucion_tipo;
	        
	        $columnas4 = " COALESCE(sueldo_liquido_contribucion_tipo_participes,0.00) sueldo_participes";
	        $tablas4 = " core_contribucion_tipo_participes";
	        $where4 = " id_participes = $_id_participe AND id_contribucion_tipo = $_id_contribucion_tipo ";
	        $id4 = " id_contribucion_tipo_participes";
	        $rsConsulta4 = $Participes->getCondiciones($columnas4, $tablas4, $where4, $id4);
	        $_sueldo_participe = ( !empty($rsConsulta4) ) ?  $rsConsulta4[0]->sueldo_participes : 0.00;	        
	        
	        /** insertado de contribucion tipo participes */
	        $_funcion = "ins_core_contribucion_tipo_participes";
	        $_parametros = "$_id_contribucion_tipo,$_id_participe,$_id_tipo_aportacion,$_valor_aportacion,$_sueldo_participe,$_id_estado,$_porcentaje_aportacion";	
	        $_queryConsulta = $Participes->getconsultaPG($_funcion, $_parametros);
	        $insertado = $Participes->llamarconsultaPG($_queryConsulta);
	        
	        $errorPg = pg_last_error();
	        if( !empty($errorPg) ){
	            $Participes->endTran();
	            echo ('Error Actualizar Fila Seleccionada');
	            exit();
	        }
	        
	        $Participes->endTran("COMMIT");
	        $respuesta['respuesta']=1;
	        $respuesta['mensaje']="Valor Aporte Actualizado";
	        echo json_encode($respuesta);
	        exit();	        
	       
	        
	    }
	    
	    
	    
	}
	
	public function NegarSolicitud(){
	    
	    /** tomar variables de la vista */
	    $_departamento = $_POST['departamento'];
	    $_id_solicitud_aportes = $_POST['id_solicitud'];
	    
	    /** variables locales **/
	    $respuesta = array();
	    
	    $Participes = new ParticipesModel();
	    
	    if( $_departamento == "recaudaciones" ){
	        
	        $columnas1 = "*";
	        $tablas1 = "estado";
	        $where1 = "nombre_estado = 'ANULADO RECAUDACIONES' AND tabla_estado = 'solicitud_valor_aportacion' ";
	        $id1 = "id_estado";
	        $rsConsulta1 = $Participes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        $_id_estado = $rsConsulta1[0]->id_estado;
	        
	        $colval = " id_estado = '$_id_estado' ";
	        $tabla = " solicitud_valor_aportacion";
	        $where = "id_solicitud_valor_aportacion = '$_id_solicitud_aportes'";
	        
	        $resultado = $Participes->ActualizarBy($colval, $tabla, $where);
	        
	        if((int)$resultado < 0){ echo ('Error Actualizar Fila Seleccionada');}
	        
	        $respuesta['respuesta']=1;
	        $respuesta['mensaje']="Valor Aporte Actualizado";
	        
	        echo json_encode($respuesta);
	        exit();
	        
	    }
	    
	    if( $_departamento == "gerencia" ){
	        
	        $columnas1 = "*";
	        $tablas1 = "estado";
	        $where1 = "nombre_estado = 'ANULADO GERENCIA' AND tabla_estado = 'solicitud_valor_aportacion'";
	        $id1 = "id_estado";
	        $rsConsulta1 = $Participes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        $_id_estado = $rsConsulta1[0]->id_estado;
	        
	        $colval = " id_estado = '$_id_estado' ";
	        $tabla = " solicitud_valor_aportacion";
	        $where = "id_solicitud_valor_aportacion = '$_id_solicitud_aportes'";
	        
	        $resultado = $Participes->ActualizarBy($colval, $tabla, $where);
	        
	        if((int)$resultado < 0){ echo ('Error Actualizar Fila Seleccionada');}
	        
	        $respuesta['respuesta']=1;
	        $respuesta['mensaje']="Valor Aporte Actualizado";
	        
	        echo json_encode($respuesta);
	        exit();
	        
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
		
	
	
	

	
	/*************************************************** PARA CONEXIONES CROSS DOMAIN CON APP webcapremci **************************************************/
	public function crss_ingresar_solicitud(){
	    
	    $Participes = new ParticipesModel();
	    
	    if( $_SERVER['REQUEST_METHOD'] == "GET" ){
	        
	        if( isset($_GET['metodo']) && $_GET['metodo'] == "GUARDAR" ){
	            
	            /** datos recibidos de webcapremci **/
	            $_cedula_participe         = $_GET['cedula'];
	            $_id_tipo_aportacion       = $_GET['id_tipo_aportacion'];
	            $_valor_aportacion         = $_GET['valor_aportacion'];
	            $_porcentaje_aportacion    = $_GET['porcentaje_aportacion'];
	            $_observacion_aportacion   = $_GET['observacion_aportacion'];
	            
	            $_valor_aportacion = $_valor_aportacion == "" ? 0 : $_valor_aportacion;
	            
	            $error = error_get_last();
	            if( !empty($error)){ echo "Datos no enviados"; exit();}
	            
	            $columnas1 = " aa.id_participes, aa.cedula_participes, aa.nombre_participes";
	            $tablas1   = " core_participes aa
                   INNER JOIN core_estado_participes bb ON bb.id_estado_participes = aa.id_estado_participes";
	            $where1    = " aa.id_estatus = 1
    	            AND upper(bb.nombre_estado_participes) = 'ACTIVO'
    	            AND aa.cedula_participes = '$_cedula_participe'";
	            $id1       = " aa.id_participes";
	            $rsConsulta1 = $Participes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	            
	            if( !empty($rsConsulta1) ){
	                
	                $_id_participes = $rsConsulta1[0]->id_participes;
	                //consulta para buscar el estado
	                $columnas2 = " id_estado, nombre_estado";
	                $tablas2   = " public.estado";
	                $where2    = " tabla_estado = 'solicitud_valor_aportacion' AND nombre_estado = 'EN REVISION'";
	                $id2       = " id_estado";
	                $rsConsulta2 = $Participes->getCondiciones($columnas2, $tablas2, $where2, $id2);
	                
	                $_id_estado_solicitud = $rsConsulta2[0]->id_estado;
	                
	                $_funcion = "core_ins_solicitud_valor_aportes";
	                $_parametros = "'$_cedula_participe',$_id_participes,$_id_tipo_aportacion,$_valor_aportacion, $_porcentaje_aportacion, '$_observacion_aportacion', $_id_estado_solicitud";
	                $_query = $Participes->getconsultaPG( $_funcion, $_parametros );
	                $resultado = $Participes->llamarconsultaPG($_query);
	                
	                if( (int)$resultado[0] == 1 ){
	                    
	                    echo json_encode(array("respuesta"=>1,"mensaje"=>'solicitud ingresada'));
	                    
	                    exit();
	                    
	                }else{
	                    
	                    echo "E001"; 
	                    exit();
	                }
	                                
	                
	            }
	            //echo "llego ACA";
	            header("HTTP/1.0 404 Not Found");
	        }
	       
	    }
	    //echo "llego ACA2";
	    header("HTTP/1.0 405 Not Found");
	    
	}
	
	public function crss_ValidarSolicitudes(){
	    
	    $Participes = new ParticipesModel();
	    
	    if( $_SERVER['REQUEST_METHOD'] == "GET" ){
	        
	        if( isset($_GET['metodo']) && $_GET['metodo'] == "BUSCAR" ){
	            
	            /** datos recibidos de webcapremci **/
	            $_cedula_participe         = $_GET['cedula'];
	            
	            $error = error_get_last();
	            if( !empty($error)){ echo "Datos no enviados"; exit();}
	            
	            $columnas1 = " aa.id_participes, aa.cedula_participes, aa.nombre_participes";
	            $tablas1   = " core_participes aa
                   INNER JOIN core_estado_participes bb ON bb.id_estado_participes = aa.id_estado_participes";
	            $where1    = " aa.id_estatus = 1
    	            AND upper(bb.nombre_estado_participes) = 'ACTIVO'
    	            AND aa.cedula_participes = '$_cedula_participe'";
	            $id1       = " aa.id_participes";
	            $rsConsulta1 = $Participes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	            	            	            
	            if( !empty($rsConsulta1) ){
	                
	                $_id_participes = $rsConsulta1[0]->id_participes;
	                //consulta para buscar el estado
	                $columnas2 = " 1 ";
	                $tablas2   = " solicitud_valor_aportacion aa
	                   INNER JOIN estado bb ON bb.id_estado = aa.id_estado";
	                $where2    = " bb.nombre_estado IN ('APROBADO RECAUDACIONES','EN REVISION') AND aa.id_participes = $_id_participes";
	                $id2       = " aa.id_participes";
	                $rsConsulta2 = $Participes->getCondiciones($columnas2, $tablas2, $where2, $id2);
	                	               
	                if( !empty($rsConsulta2) ){
	                    	                   
	                    echo json_encode(array("respuesta"=>"error","mensaje"=>"no puede generar mas solicitudes"));
	                    exit();
	                    
	                }else{
	                    
	                    echo json_encode(array("respuesta"=>"1","mensaje"=>"continue con la solicitud"));
	                    exit();
	                }
	                
	                
	            }
	            
	            header("HTTP/1.0 404 Not Found");
	            
	        }
	    }
	    header("HTTP/1.0 404 Not Found");
	}
	
	public function crss_MostrarSolicitudes(){
	    
	    $Participes = new ParticipesModel();
	    
	    if( $_SERVER['REQUEST_METHOD'] == "GET" ){
	        
	        if( isset($_GET['metodo']) && $_GET['metodo'] == "LISTAR" ){
	            
	            /** datos recibidos de webcapremci **/
	            $_cedula_participe         = $_GET['cedula'];
	            
	            $error = error_get_last();
	            if( !empty($error)){ echo "Datos no enviados"; exit();}
	            
	            $columnas1 = " aa.id_participes, aa.cedula_participes, aa.nombre_participes";
	            $tablas1   = " core_participes aa
                   INNER JOIN core_estado_participes bb ON bb.id_estado_participes = aa.id_estado_participes";
	            $where1    = " aa.id_estatus = 1
    	            AND upper(bb.nombre_estado_participes) = 'ACTIVO'
    	            AND aa.cedula_participes = '$_cedula_participe'";
	            $id1       = " aa.id_participes";
	            $rsConsulta1 = $Participes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	            
	            if( !empty($rsConsulta1) ){
	                
	                $_id_participes = $rsConsulta1[0]->id_participes;
	                //consulta para buscar el estado
	                $columnas2 = " cc.id_participes, cc.cedula_participes, cc.nombre_participes, cc.apellido_participes, bb.id_tipo_aportacion, bb.nombre_tipo_aportacion,
                            	aa.valor_solicitud_valor_aportacion, aa.porcentaje_solicitud_valor_aportacion, aa.observacion_solicitud_valor_aportacion,
                            	to_char(aa.creado,'YYYY-MM-DD') creado, dd.id_estado, dd.nombre_estado";
	                $tablas2   = " solicitud_valor_aportacion aa
                        	INNER JOIN core_tipo_aportacion bb ON bb.id_tipo_aportacion = aa.id_tipo_aportacion
                        	INNER JOIN core_participes cc ON cc.id_participes = aa.id_participes
                        	INNER JOIN estado dd ON dd.id_estado = aa.id_estado";
	                $where2    = " dd.nombre_estado <> 'ANULADO' AND cc.id_participes = $_id_participes";
	                $id2       = " cc.id_participes";
	                $rsConsulta2 = $Participes->getCondiciones($columnas2, $tablas2, $where2, $id2);
	                
	                if( !empty($rsConsulta2) ){
	                    
	                    $_html_filas = "";	                    
	                    $_indice = 0;
	                    $_valor_print = "";
	                    $_nombre_tipo = "";
	                    foreach ( $rsConsulta2 as $res ){
	                        $_html_filas .= "<tr>";
	                        $_indice ++;
	                        $_nombre_tipo = $res->nombre_tipo_aportacion;
	                        
	                        if( $_nombre_tipo == "VALOR" ){ 
	                            $_valor_print = number_format((float)$res->valor_solicitud_valor_aportacion,2,".",",");
	                        }else{
	                            $_valor_print = number_format((float)$res->porcentaje_solicitud_valor_aportacion,2,".",",");
	                            $_valor_print = $_valor_print."%";
	                        }	                   
	                        
	                        $_html_filas .= '<td>'.$_indice.'</td>';
	                        $_html_filas .= '<td>'.$res->cedula_participes.'</td>';
	                        $_html_filas .= '<td>'.$res->nombre_participes.'</td>';
	                        $_html_filas .= '<td>'.$res->apellido_participes.'</td>';
	                        $_html_filas .= '<td>'.$_nombre_tipo.'</td>';
	                        $_html_filas .= '<td style="text-align: right;">'.$_valor_print.'</td>';
	                        $_html_filas .= '<td>'.$res->observacion_solicitud_valor_aportacion.'</td>';
	                        $_html_filas .= '<td>'.$res->creado.'</td>';
	                        $_html_filas .= '<td>'.$res->nombre_estado.'</td>';
	                        $_html_filas .= "</tr>";
	                    }
	                   
	                    
	                    echo json_encode(array("respuesta"=>"1","dataFilas"=>$_html_filas));
	                    exit();
	                    
	                }else{
	                    $_html_filas = "<tr>";
	                    $_html_filas .= '<td colspan="9"> Actualmente no existe Solicitudes </td>';
	                    $_html_filas .= "</tr>";
	                    echo json_encode(array("respuesta"=>"1","dataFilas"=>$_html_filas));
	                    exit();
	                }
	                
	                
	            }
	            
	            header("HTTP/1.0 404 Not Found");
	            
	        }
	    }
	    header("HTTP/1.0 404 Not Found");
	}
	
	
	/*************************************************** END PARA CONEXIONES CROSS DOMAIN CON APP webcapremci **************************************************/
	
		
}
?>