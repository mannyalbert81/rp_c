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
                      core_carga_recaudaciones.lineas_carga_recuadaciones,
                      core_carga_recaudaciones.suma_carga_recuadaciones,
                      core_carga_recaudaciones.usuario_usuarios, 
                      core_carga_recaudaciones.generado_carga_recaudaciones, 
                      core_carga_recaudaciones.formato_carga_recaudaciones";
	    
	    $tablas    = "public.core_carga_recaudaciones, 
                      public.core_entidad_patronal";
	    
	    $where     = "core_entidad_patronal.id_entidad_patronal = core_carga_recaudaciones.id_entidad_patronal";
	    
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
	            
	            $html.= "<table id='tbl_documentos_recaudaciones' class='table table-striped table-bordered'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Mes</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Año</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ruta</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Formato</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">lineas</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Total</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	     
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            
	            
	            {
	           
	                
	                $i++;
	                $ruta = '..'.substr($res->ruta_carga_recaudaciones, 0);
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->mes_carga_recaudaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->anio_carga_recaudaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$ruta.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_carga_recaudaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->usuario_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->formato_carga_recaudaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->lineas_carga_recuadaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->suma_carga_recuadaciones.'</td>';
	                $html.='<td style="font-size: 18px;">';
	                $html.='<span class="pull-right ">
                                    <a onclick="verArchivo(this)" id="" data-idarchivo="'.$res->id_carga_recaudaciones.'"
                                    href="#" class="btn btn-sm btn-default label label-info">
                                    <i class="fa  fa-file-text" aria-hidden="true" ></i>
                                    </a></span></td>';
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
	        $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	        
	        $error = error_get_last();
	        if(!empty($error)){    throw new Exception('Variables no recibidas'); }
	        
	        /* validar archivo */
	        $nombre = "";
	        $tipo = "";
	        $tamano = "";
	        $_archivo_procesar = ""; 
	        
	        if ($_FILES['nombre_carga_recaudaciones']['tmp_name']!="")
	        {
	            
	            $directorio = $this->crearPath($_anio_carga_recaudaciones, $_mes_carga_recaudaciones, "CARGAARCHIVOS");
	            $_ruta_archivo_recaudaciones   = $directorio['ruta'];
	            
	            $nombre = $_FILES['nombre_carga_recaudaciones']['name'];
	            $tipo = $_FILES['nombre_carga_recaudaciones']['type'];
	            $tamano = $_FILES['nombre_carga_recaudaciones']['size'];
	            move_uploaded_file($_FILES['nombre_carga_recaudaciones']['tmp_name'],$_ruta_archivo_recaudaciones.'/'.$nombre);
	            
	            $_archivo_procesar = $_ruta_archivo_recaudaciones.'/'.$nombre;
	            
	        }else{
	            throw new Exception('Archivo txt no recibido/Valido');
	        }
	        	        
	        $_array_archivo = $this->DevuelveLineasTxt($_archivo_procesar);
	        $_cantidad_lineas = 0;
	        $_suma_linea = 0;
	        
	        if(is_array($_array_archivo)){
	            $_cantidad_lineas= $_array_archivo['cantidad_lineas'];
	            $_suma_linea= $_array_archivo['suma_lineas'];
	        }else{
	            throw new Exception("El contenido del Archivo no es correcto");
	        }	        
	        
	        $_mes_carga_recaudaciones = str_pad($_mes_carga_recaudaciones, 2, "0", STR_PAD_LEFT);
	        
	        $_nombre_carga_formato_recaudacion = "";
	        $columnas1 = "id_carga_recaudaciones, nombre_carga_recaudaciones";
	        $tablas1   = "core_carga_recaudaciones";
	        $where1    = "id_entidad_patronal = $_id_entidad_patronal AND anio_carga_recaudaciones = $_anio_carga_recaudaciones";
	        $where1    .= " AND mes_carga_recaudaciones = $_mes_carga_recaudaciones";
	        $id1       = "id_carga_recaudaciones";
	        
	        switch ( $_formato_carga_recaudaciones ){
	            
	            case '1':
	                $_nombre_carga_formato_recaudacion = "CARGA APORTES";
	                $where1    .= " AND formato_carga_recaudaciones = '$_nombre_carga_formato_recaudacion'";
	                $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	                
	                $_id_carga_recaudaciones = 0;
	                
	                $error = pg_last_error();
	                if(!empty($error)){ throw new Exception('datos no validos'); }
	                
	                if(empty($rsConsulta1)){
	                    
	                    $columnas2 = "id_entidad_patronal, nombre_entidad_patronal";
	                    $tablas2   = "core_entidad_patronal";
	                    $where2    = "id_entidad_patronal = $_id_entidad_patronal";
	                    $id2       = "id_entidad_patronal";
	                    $rsConsulta2   = $Contribucion->getCondiciones($columnas2, $tablas2, $where2, $id2);
	                    $_nombre_entidad_patronal  = $this->limpiarCaracteresEspeciales($rsConsulta2[0]->nombre_entidad_patronal);
	                    	                    
	                    $funcion = "ins_core_carga_recaudaciones";
	                    $parametros = "'$_id_entidad_patronal','$_mes_carga_recaudaciones','$_anio_carga_recaudaciones','$_ruta_archivo_recaudaciones','$nombre','$_usuario_usuarios','FALSE', '$_nombre_carga_formato_recaudacion','$_cantidad_lineas','$_suma_linea'";
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
	                
	                $_nombre_carga_formato_recaudacion = "CARGA CREDITOS";
	                $where1    .= " AND formato_carga_recaudaciones = '$_nombre_carga_formato_recaudacion'";
	                $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	                
	                $_id_carga_recaudaciones = 0;
	                
	                $error = pg_last_error();
	                if(!empty($error)){ throw new Exception('datos no validos'); }
	                
	                if(empty($rsConsulta1)){
	                    
	                    $columnas2 = "id_entidad_patronal, nombre_entidad_patronal";
	                    $tablas2   = "core_entidad_patronal";
	                    $where2    = "id_entidad_patronal = $_id_entidad_patronal";
	                    $id2       = "id_entidad_patronal";
	                    $rsConsulta2   = $Contribucion->getCondiciones($columnas2, $tablas2, $where2, $id2);
	                    $_nombre_entidad_patronal  = $this->limpiarCaracteresEspeciales($rsConsulta2[0]->nombre_entidad_patronal);
	                    
	                  
	                    $funcion = "ins_core_carga_recaudaciones";
	                    $parametros = "'$_id_entidad_patronal','$_mes_carga_recaudaciones','$_anio_carga_recaudaciones','$_ruta_archivo_recaudaciones','$nombre','$_usuario_usuarios','FALSE', '$_nombre_carga_formato_recaudacion','$_cantidad_lineas','$_suma_linea'";
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
	
	/***
	 * ingresa ruta del archivo y si no es valida ruta devuelve 0 lineas
	 * @param string $_archivo
	 * return array de valores cant lineas, suma de columnas especifica;
	 */
	public function DevuelveLineasTxt($_archivo){
	    
	    if( !is_file($_archivo)){ return 0; }
	    
	    $file = fopen($_archivo, "r") or exit("0");
	    $_i_linea = 0;
	    $_cantidad_lineas = 0;
	    $_suma_linea = 0.00;
	    while(!feof($file))
	    {
	        $_fila = fgets($file);
	        $_fila = trim($_fila);
	        if($_i_linea>0){
	            if(!empty($_fila)){
	                $_cantidad_lineas++;
	                $error = true;
	                $_array_fila   = explode(";", $_fila);
	                $error = $error = is_numeric($_array_fila[6]) ? false : true;
	                if($error){
	                    throw new  Exception("Contenido no Valido Revise el archivo.. linea ".$_cantidad_lineas);
	                }
	                $_suma_linea += (float)$_array_fila[6];
	            }
	        }	       
	       $_i_linea++;
	    }
	    fclose($file);
	    
	    $_respuesta = array();
	    $_respuesta['cantidad_lineas'] = $_cantidad_lineas;
	    $_respuesta['suma_lineas'] = $_suma_linea;
	    return $_respuesta;
	}
	


public function descargarArchivo(){
    
    $_id_carga_recaudaciones = $_POST['id_carga_recaudaciones'];
    $Participes = new ParticipesModel();
    
    $columnas1 = "id_carga_recaudaciones, nombre_carga_recaudaciones, ruta_carga_recaudaciones";
    $tablas1   = "core_carga_recaudaciones";
    $where1 = "id_carga_recaudaciones = $_id_carga_recaudaciones";
    $id1 = "id_carga_recaudaciones";
    
    $rsConsulta1 = $Participes->getCondiciones($columnas1,$tablas1,$where1,$id1);
    
    $nombre_archivo    = $rsConsulta1[0]->nombre_carga_recaudaciones;
    $ruta_archivo      = $rsConsulta1[0]->ruta_carga_recaudaciones;
    
    $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\";
    $ubicacion = $ubicacionServer.$ruta_archivo."\\".$nombre_archivo;
    
    // Define headers
    header("Content-disposition: attachment; filename=$nombre_archivo");
    header("Content-type: MIME");
    ob_clean();
    flush();
    // Read the file
    readfile($ubicacion);
    exit;
    
}

public function GetCedulas($_archivo)
{
    $participes = new ParticipesModel();
    $columna = "core_participes.cedula_participes, core_participes.nombre_participes, estado.nombre_estado";
    
    $tablas = "public.core_participes INNER JOIN public.estado
                   ON core_participes.id_estado = estado.id_estado";
    
    $where = "estado.nombre_estado='ACTIVO'";
    
    $resultSet=$participes->getCondiciones($columna,$tablas,$where,"core_participes.cedula_participes");
  
    if( !is_file($_archivo)){ return 0; }
    
    $file = fopen($_archivo, "r") or exit("0");
    
}

public function ConsultaCedulas($_archivo){
    
    if( !is_file($_archivo)){ return 0; }
    
    $file = fopen($_archivo, "r") or exit("0");
    $_i_linea = 0;
    $_cantidad_lineas = 0;
    $_suma_linea = 0.00;
    $cdedula_base = 0;
    
    while(!feof($file))//dfd
    {
        $_fila = fgets($file);
        $_fila = trim($_fila);
        if($_i_linea>0){
            if(!empty($_fila)){
                $_cantidad_lineas++;
                $error = true;
                $_array_fila   = explode(";", $_fila);
                
                $error = $error = is_numeric($_array_fila[6]) ? false : true;
                if($error){
                    throw new  Exception("La cedula no existe ".$cdedula_base);
                }
                $_suma_linea += (float)$_array_fila[6];
            }
        }
        $_i_linea++;
    }
    fclose($file);
    
    $_respuesta = array();
    $_respuesta['cantidad_lineas'] = $_cantidad_lineas;
    $_respuesta['suma_lineas'] = $_suma_linea;
    return $_respuesta;
}



public function inserta_datos(){
    
    session_start();
    $resultado = null;
    $participes = new ParticipesModel();
    
    
    if (isset(  $_SESSION['nombre_usuarios']) )
    {
        $_id_entidad_patronal = $_POST["id_entidad_patronal"];
        $_mes_carga_recaudaciones = $_POST["mes_carga_recaudaciones"];
        $_anio_carga_recaudaciones = $_POST["anio_carga_recaudaciones"];
        
        if ($_FILES['nombre_carga_recaudaciones']['tmp_name']!="")
        {
            
            $_lectura_biometrico->deleteById("id_entidad_patronal='$_id_entidad_patronal' AND anio_carga_recaudaciones='$_anio_carga_recaudaciones' AND mes_carga_recaudaciones='$_mes_carga_recaudaciones'");
            
            $_id_usuarios= $_SESSION['id_usuarios'];
            
            $directorio = $_SERVER['DOCUMENT_ROOT'].'/nomina/view/biometrico/';
            
            $nombre = $_FILES['nombre_carga_recaudaciones']['name'];
            $tipo = $_FILES['nombre_carga_recaudaciones']['type'];
            $tamano = $_FILES['nombre_carga_recaudaciones']['size'];
            move_uploaded_file($_FILES['nombre_carga_recaudaciones']['tmp_name'],$directorio.$nombre);
            
            
            $lineas = file($directorio.$nombre);
            $numero_linea=0;
            $errores=false;
            $error_encontrado="";
            
            if(!empty($lineas)){
                
                
                foreach ($lineas as $linea_num => $linea)
                {
                    $numero_linea++;
                    $error_encontrado="";
                    $datos = explode("\t",$linea);
                    
                    if(count($datos) == 3 && !empty(trim($datos[0])) && !empty(trim($datos[1]))){
                        
                        $cedula_participes              = trim($datos[0]);
                        $monto                = trim($datos[1]);
                        
                    }else{
                        
                        $errores=true;
                        
                        $error_encontrado="Esta linea no contiene el formato establecido (cedula, fecha, hora), las columnas no estan separadas por tabulaciones.";
                        
                    }
                    
                }
                 
                // cuando pasa la primera validacion de verificar tabulaciones.
                if($errores==false){
                    
                    $numero_linea=0;
                    
                    $cedula_participes="";
                    $monto="";
               
                    // AQUI VALIDAMOS QUE LA CEDULA EXISTA REGISTRADA EN LA BASE DE DATOS.
                    foreach ($lineas as $linea_num => $linea)
                    {
                        $numero_linea++;
                        $error_encontrado="";
                        $datos = explode("\t",$linea);
                        
                        if(count($datos) == 3 && !empty(trim($datos[0])) && !empty(trim($datos[1])) && !empty(trim($datos[2]))){
                            
                            $cedula_participes              = trim($datos[0]);
                            $monto                = trim($datos[1]);
                            
                            if(!is_numeric($cedula_participes)){
                                
                                $errores=true;
                                
                                $error_encontrado="La cedula $cedula_participes debe ser solo numeros.";
                                
                       
                            }else{
                                
                                
                                if(strlen($cedula_participes)==10 || strlen($cedula_participes)==13){
                                    
                                }else{
                                    
                                    $errores=true;
                                    
                                    $error_encontrado="La cedula $cedula_participes debe estar compuesta de 10 dígitos o 13 dígitos.";
                                
                                }
                                
                            }
                            
                            $columnas="core_participes.cedula_participes";
                            $tablas ="core_participes";
                            $where ="core_participes.cedula_participes= '$cedula_participes'";
                            $id="core_participes.cedula_participes";
                            
                            $resultEmple=$participes->getCondiciones($columnas,$tablas,$where,$id);
                            
                            if(!empty($resultEmple)){
                                
                            }else{
                                
                                $errores=true;
                                
                                $error_encontrado="La cedula número $cedula_participes no existe registrada en la base de datos.";
                             
                            }
                                  
                        }else{
                            
                            $errores=true;
                            
                            $error_encontrado="Esta linea no contiene el formato establecido, las columnas no estan separadas por tabulaciones.";
                        
                        }
                        
                    }
                    
                }
                
            }else{
                
                $errores=true;
                
                $error_encontrado="El archivo seleccionado no contiene registros, esta vacio.";
                
            }
            
        }
        
        
        $this->redirect("CargaRecaudaciones", "index");
         
    }else{
        
        $error = TRUE;
        $mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
        
        $this->view("Login",array(
            "resultSet"=>"$mensaje", "error"=>$error
        ));
        
        
        die();
        
    }
    
    
}


                            
                            
}

?>