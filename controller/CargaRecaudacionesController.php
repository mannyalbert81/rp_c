<?php

class CargaRecaudacionesController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
	
	    $carga_recaudaciones = new CargaRecaudacionesModel();
	    
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "CargaRecaudaciones";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $carga_recaudaciones->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Carga Recaudaciones"
		        
		    ));
		    exit();
		}		    
			
		$rsCargaRecaudaciones = $carga_recaudaciones->getBy(" 1 = 1 ");
		
				
		$this->view_Recaudaciones("CargaRecaudaciones",array(
		    "resultSet"=>$rsCargaRecaudaciones
	
		));
			
	
	}
	

	
	public function InsertaCargaRecaudaciones(){
	    
	    session_start();
		
	    $carga_recaudaciones = new CargaRecaudacionesModel();
		
		$nombre_controladores = "CargaRecaudaciones";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $carga_recaudaciones->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer)){
		    
		    $_id_carga_recaudaciones = (isset($_POST["id_carga_recaudaciones"])) ? $_POST["id_carga_recaudaciones"] : "0";
		    $_id_entidad_patronal = (isset($_POST["id_entidad_patronal"])) ? $_POST["id_entidad_patronal"] : 0 ;
		    $_mes_carga_recaudaciones = (isset($_POST["mes_carga_recaudaciones"])) ? $_POST["mes_carga_recaudaciones"] : 0 ;
		    $_anio_carga_recaudaciones = (isset($_POST["anio_carga_recaudaciones"])) ? $_POST["anio_carga_recaudaciones"] : 0 ;
		    $_ruta_carga_recaudaciones = (isset($_POST["ruta_carga_recaudaciones"])) ? $_POST["ruta_carga_recaudaciones"] : 0 ;
		    $_nombre_carga_recaudaciones = (isset($_POST["nombre_carga_recaudaciones"])) ? $_POST["nombre_carga_recaudaciones"] : 0 ;
		    $_usuario_usuarios = (isset($_POST["usuario_usuarios"])) ? $_POST["usuario_usuarios"] : 0 ;
		    $_generado_carga_recaudaciones = (isset($_POST["generado_carga_recaudaciones"])) ? $_POST["generado_carga_recaudaciones"] : 0 ;
		    

			$funcion = "ins_core_carga_recaudaciones";
			$respuesta = 0 ;
			$mensaje = ""; 
			
	 //echo '<message>llego<message>';die();
			
			if($_id_carga_recaudaciones == 0){
			    
			    $parametros = "'$_id_entidad_patronal','$_mes_carga_recaudaciones','$_anio_carga_recaudaciones','$_ruta_carga_recaudaciones','$_nombre_carga_recaudaciones','$_usuario_usuarios','$_generado_carga_recaudaciones','$_id_carga_recaudaciones'";
			    $carga_recaudaciones->setFuncion($funcion);
			    $carga_recaudaciones->setParametros($parametros);
			    $resultado = $carga_recaudaciones->llamafuncionPG();
			    
			    if(is_int((int)$resultado[0])){
			        $respuesta = $resultado[0];
			        $mensaje = "Carga Recaudaciones Ingresado Correctamente";
			    }	
			    
			
			    
			}elseif ($_id_carga_recaudaciones > 0){
			    
			    $parametros = "'$_id_entidad_patronal','$_mes_carga_recaudaciones','$_anio_carga_recaudaciones','$_ruta_carga_recaudaciones','$_nombre_carga_recaudaciones','$_usuario_usuarios','$_generado_carga_recaudaciones','$_id_carga_recaudaciones'";
			    $carga_recaudaciones->setFuncion($funcion);
			    $carga_recaudaciones->setParametros($parametros);
			    $resultado = $carga_recaudaciones->llamafuncionPG();
			    
			    if(is_int((int)$resultado[0])){
			        $respuesta = $resultado[0];
			        $mensaje = "Carga Recaudaciones Actualizado Correctamente";
			    }	
			    
			    
			}
			
	
			if(is_int((int)$respuesta)){
			    
			    echo json_encode(array('respuesta'=>$respuesta,'mensaje'=>$mensaje));
			    exit();
			}
			
			echo "Error al Ingresar Carga Recaudaciones";
			exit();
			
		}
		else
		{
		    $this->view_Recaudaciones("Error",array(
					"resultado"=>"No tiene Permisos de Insertar Carga Recaudaciones"
		
			));
		
		
		}
		
	}

	

	
	public function paginate($reload, $page, $tpages, $adjacents, $funcion = "") {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>1</a></li>";
	    }
	    if($page>($adjacents+2)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
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
	    
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='$funcion($tpages)'>$tpages</a></li>";
	    }
	    
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	

	public function editCargaRecaudaciones(){
	    
	    session_start();
	    $carga_recaudaciones = new CargaRecaudacionesModel();
	    $nombre_controladores = "CargaRecaudaciones";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $carga_recaudaciones->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    	     
	    if (!empty($resultPer))
	    {
	        
	        
	        if(isset($_POST["id_carga_recaudaciones"])){
	            
	            $id_carga_recaudaciones = (int)$_POST["id_carga_recaudaciones"];
	            
	            $query = "SELECT * FROM core_carga_recaudaciones WHERE id_carga_recaudaciones = $id_carga_recaudaciones";

	            $resultado  = $carga_recaudaciones->enviaquery($query);	            
	           
	            echo json_encode(array('data'=>$resultado));	            
	            
	        }
	       	        
	        
	    }
	    else
	    {
	        echo "Usuario no tiene permisos-Editar";
	    }
	    
	}
	

	public function delCargaRecaudaciones(){
	    
	    session_start();
	    $carga_recaudaciones = new CargaRecaudacionesModel();
	    $nombre_controladores = "CargaRecaudaciones";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $carga_recaudaciones->getPermisosBorrar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer)){	        
	        
	        if(isset($_POST["id_carga_recaudaciones"])){
	            
	            $id_carga_recaudaciones = (int)$_POST["id_carga_recaudaciones"];
	            
	            $resultado  = $carga_recaudaciones->eliminarBy("id_carga_recaudaciones",$id_carga_recaudaciones);
	           
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
	
	
	public function consultaCargaRecaudaciones(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	
	    $carga_recaudaciones = new CargaRecaudacionesModel();
	    
	    $where_to="";
	    $columnas  = "core_carga_recaudaciones.id_carga_recaudaciones, 
                      core_carga_recaudaciones.id_entidad_patronal, 
                      core_entidad_patronal.nombre_entidad_patronal, 
                      core_carga_recaudaciones.mes_carga_recaudaciones, 
                      core_carga_recaudaciones.anio_carga_recaudaciones, 
                      core_carga_recaudaciones.ruta_carga_recaudaciones, 
                      core_carga_recaudaciones.nombre_carga_recaudaciones, 
                      core_carga_recaudaciones.usuario_usuarios, 
                      core_carga_recaudaciones.generado_carga_recaudaciones";
                    	    
	    $tablas    = "public.core_carga_recaudaciones, 
                      public.core_entidad_patronal";
	    
	    $where     = "1=1";
	    
	    $id        = "core_carga_recaudaciones.id_carga_recaudaciones";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';	    
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND nombre_carga_recaudaciones LIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$carga_recaudaciones->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$carga_recaudaciones->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);	        
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_carga_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 15px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Entidad</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Mes</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Año</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Ruta</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Usuarios</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Generado</th>';
	            
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            
	      
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 14px;">'.$i.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->mes_carga_recaudaciones.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->anio_carga_recaudaciones.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->ruta_carga_recaudaciones.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_carga_recaudaciones.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->usuario_usuarios.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->generado_carga_recaudaciones.'</td>';
	                
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editCargaRecaudaciones('.$res->id_carga_recaudaciones.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                    $html.='<td style="font-size: 18px;">
                            <a onclick="delCargaRecaudaciones('.$res->id_carga_recaudaciones.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
                   
	                $html.='</tr>';
	            }
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaCargaRecaudaciones").'';
	            $html.='</div>';
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registros...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        echo $html;
	       
	    }
	     
	}

	public function cargaEntidadPatronal(){
	    
	    $entidad_patronal = null;
	    $entidad_patronal = new EntidadPatronalParticipesModel();
	    
	    $query = "SELECT id_entidad_patronal,nombre_entidad_patronal FROM core_entidad_patronal WHERE 1=1";
	    
	    $resulset = $entidad_patronal->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	
	
	public function GenerarCargaRecaudaciones(){
	    
	    $Contribucion      = new CoreContribucionModel();
	    $carga_recaudaciones = new CargaRecaudacionesModel();
	    $respuesta         = array();
	    $error             = "";
	    
	    try{
	        $Contribucion->beginTran();
	        session_start();
	        $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
	        $_anio_carga_recaudaciones     = $_POST['anio_carga_recaudaciones'];
	        $_mes_carga_recaudaciones      = $_POST['mes_carga_recaudaciones'];
	        $_formato_carga_recaudaciones  = $_POST['formato_carga_recaudaciones'];
	        //$_nombre_carga_recaudaciones  = $_POST['nombre_carga_recaudaciones'];
	        
	        $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	        
	        $error = error_get_last();
	        if(!empty($error)){    throw new Exception('Variables no recibidas'); }
	        
	        /*configurar estructura mes de consulta*/
	        $_mes_carga_recaudaciones = str_pad($_mes_carga_recaudaciones, 2, "0", STR_PAD_LEFT);
	        
	        $_nombre_carga_formato_recaudacion = "";
	        $columnas1 = "id_carga_recaudaciones, nombre_carga_recaudaciones";
	        $tablas1   = "core_carga_recaudaciones";
	        $where1    = "id_entidad_patronal = $_id_entidad_patronal AND anio_carga_recaudaciones = $_anio_carga_recaudaciones";
	        $where1    .= " AND mes_carga_recaudaciones = $_mes_carga_recaudaciones";
	        $id1       = "id_carga_recaudaciones";
	        
	        //diferenciar el tipo de recaudacion que va a realizar
	        switch ( $_formato_carga_recaudaciones ){
	            
	            case '1':
	                //para cuando sea para cuenta individual
	                $_nombre_carga_formato_recaudacion = "CARGA APORTES";
	                $where1    .= " AND formato_carga_recaudaciones = '$_nombre_carga_formato_recaudacion'";
	                $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	                
	                $_id_carga_recaudaciones = 0;
	                
	                $error = pg_last_error();
	                if(!empty($error)){ throw new Exception('datos no validos'); }
	                
	                if(empty($rsConsulta1)){
	                    
	                    
	                    
	                    /* buscar nombre entidad patronal */
	                    $columnas2 = "id_entidad_patronal, nombre_entidad_patronal";
	                    $tablas2   = "core_entidad_patronal";
	                    $where2    = "id_entidad_patronal = $_id_entidad_patronal";
	                    $id2       = "id_entidad_patronal";
	                    $rsConsulta2   = $Contribucion->getCondiciones($columnas2, $tablas2, $where2, $id2);
	                    $_nombre_entidad_patronal  = $this->limpiarCaracteresEspeciales($rsConsulta2[0]->nombre_entidad_patronal);
	                    
	                    
	                    
	                    
	                    if ($_FILES['nombre_carga_recaudaciones']['tmp_name']!="")
	                    {
	                        
	                        $directorio = $this->crearPath($_anio_carga_recaudaciones, $_mes_carga_recaudaciones, "CARGAARCHIVOS");
	                        $_ruta_archivo_recaudaciones   = $directorio['ruta'];
	                        
	                        $nombre = $_FILES['nombre_carga_recaudaciones']['name'];
	                        $tipo = $_FILES['nombre_carga_recaudaciones']['type'];
	                        $tamano = $_FILES['nombre_carga_recaudaciones']['size'];
	                        
	                        move_uploaded_file($_FILES['nombre_carga_recaudaciones']['tmp_name'],$_ruta_archivo_recaudaciones.'/'.$nombre);
	                       // $data = file_get_contents($directorio.$nombre);
	                        
	                    }
	                    
	                    
	                    
	                    
	                 
	                    $funcion = "ins_core_carga_recaudaciones";
	                    $parametros = "'$_id_entidad_patronal','$_mes_carga_recaudaciones','$_anio_carga_recaudaciones','$_ruta_archivo_recaudaciones','$nombre','$_usuario_usuarios','FALSE', $_nombre_carga_formato_recaudacion";
	                    $carga_recaudaciones->setFuncion($funcion);
	                    $carga_recaudaciones->setParametros($parametros);
	                    $resultado = $carga_recaudaciones->llamafuncionPG();
	                    
	                    $erro= pg_last_error();
	                    if(!empty($erro)){ throw new Exception($erro); }
	                    
	                    
	                    if((int)$resultado > 0){
	                        
	                        $respuesta['mensaje']   = "Carga Generada Revise el archivo";
	                         $respuesta['respuesta'] = 1;
	                    }else{
	                        
	                        $respuesta['mensaje']   = "Error al insertar";
	                        $respuesta['respuesta'] = 2;
	                        
	                    }
	                    
	                }else{
	                    
	                    $respuesta['mensaje']   = "Ya existe el Archivo";
	                    $respuesta['respuesta'] = 2;
	                    
	                }
	                
	                break;
	            case '2':
	                /*para realizar recaudacion por creditos*/
	                //primero validar que no exista
	                $_nombre_formato_recaudacion = "CREDITOS";
	                $where1    .= " AND formato_carga_recaudaciones = '$_nombre_formato_recaudacion'";
	                
	                $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	                
	                $_id_carga_recaudaciones = 0;
	                
	                $error = pg_last_error();
	                if(!empty($error)){ throw new Exception('datos no validos'); }
	                
	                if(empty($rsConsulta1)){
	                    
	                    $respuestaArchivo           = $this->RecaudacionAportes($_id_entidad_patronal, $_anio_carga_recaudaciones, $_mes_carga_recaudaciones, $_nombre_carga_recaudaciones);
	                    $_id_carga_recaudaciones  = $respuestaArchivo;
	                    
	                    if((int)$respuestaArchivo > 0){
	                        
	                        $respuesta['mensaje']   = "Carga Generada Revise el archivo";
	                        $respuesta['id_archivo']= $_id_carga_recaudaciones;
	                        $respuesta['respuesta'] = 1;
	                    }
	                    
	                }else{
	                    
	                    $respuesta['mensaje']   = "Revise el Archivo";
	                    $respuesta['id_archivo']= $rsConsulta1[0]->id_carga_recaudaciones;
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
	        echo '<message> Error Carga Archivo Recaudacion '.$ex->getMessage().' <message>';
	    }
	    
	}
	
	
	public function RecaudacionAportes( $_id_entidad_patronal,$_anio,$_mes, $nombreArchivo){
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $Contribucion  = new CoreContribucionModel();
	    
	    /* tomar datos de archivo*/
	    //nombre
	    /* setaer datos para vbase*/
	    //nombre
	    //url o path relativo
	    //
	   
	    $_url_path = $this->obtienePath($nombreArchivo, $_anio, $_mes, $folder);
	    
	    
	    
	    $formato_carga_recaudaciones = "DESCUENTOS APORTES";
	    
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
	    $parametrosArchivo = "'$_anio','$_mes','$_id_entidad_patronal',null,null,'$formato_carga_recaudaciones','$_usuario_usuarios'";
	    
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
	
	
	
	
	
	private function crearPath($anioArchivo, $mes, $folder){
	    
	    $respuesta     = array();
	    
	    $carpeta_base      = 'view\\Recaudaciones\\documentos\\'.$folder.'\\';
	    $_carpeta_buscar   = $carpeta_base.$anioArchivo;
	    $file_buscar       = "";
	    if( file_exists($_carpeta_buscar)){
	        
	        $_carpeta_buscar1   = $carpeta_base.$anioArchivo."\\".$mes;
	        if( file_exists($_carpeta_buscar1)){
	            
	            $file_buscar = $_carpeta_buscar1;
	            
	            
	        }else{
	            
	            mkdir($_carpeta_buscar1, 0777, true);
	            $file_buscar = $_carpeta_buscar1;
	            
	        }
	        
	    }else{
	        
	        mkdir($_carpeta_buscar."\\".$mes, 0777, true);
	        $file_buscar = $_carpeta_buscar."\\".$mes;
	    }
	    
	  
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