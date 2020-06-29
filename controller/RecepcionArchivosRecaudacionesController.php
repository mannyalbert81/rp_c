<?php

class RecepcionArchivosRecaudacionesController extends ControladorBase{
    
    private $_nombre_formato="";
    private $_nombre_creditos_formato  = "DESCUENTOS CREDITOS";
    private $_nombre_aportes_formato   = "DESCUENTOS APORTES";
    private $_nombre_combinado_formato = "DESCUENTOS APORTES Y CREDITOS";	
    
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
		
		$nombre_controladores = "RecepcionArchivosRecaudaciones";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $carga_recaudaciones->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso"
		        
		    ));
		    exit();
		}		    
			
		$rsCargaRecaudaciones = $carga_recaudaciones->getBy(" 1 = 1 ");
		
				
		$this->view_Recaudaciones("RecepcionArchivosRecaudaciones",array(
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
	    
	    $query = "SELECT id_entidad_patronal,nombre_entidad_patronal FROM core_entidad_patronal WHERE 1=1 ORDER BY nombre_entidad_patronal ";
	    
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
    
    /** BEGIN DC 22-10-2019 CONFIG*/
    public function listaArchivosRecaudacion(){
        
        //echo "llego";
        
        if( !isset($_SESSION)){
            session_start();            
        }
        
        /* tomar variables de la web */
        //$_id_entidad_patronal = $_POST['id_entidad_patronal'];
        //$_anio_recaudaciones  = $_POST['id_entidad_patronal'];
        //$_mes_recaudaciones   = $_POST['id_entidad_patronal'];
        //$_tipo_formato_recaudaciones    = $_POST['tipo_formato_recaudaciones'];
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $_busqueda  = $_POST['busqueda'];
        
        $Contribucion = new CoreContribucionModel();
        
        /* variables locales */
        //$_array_respuesta = array();
        $_usuario_usuarios = $_SESSION['usuario_usuarios'];
                
        $columnas1  = " aa.id_carga_recaudaciones, aa.id_entidad_patronal, bb.nombre_entidad_patronal,
            aa.mes_carga_recaudaciones, aa.anio_carga_recaudaciones, aa.ruta_carga_recaudaciones,
            aa.nombre_carga_recaudaciones, aa.lineas_carga_recuadaciones, aa.suma_carga_recuadaciones,
            aa.usuario_usuarios, aa.generado_carga_recaudaciones, aa.formato_carga_recaudaciones";
        $tablas1    = " public.core_carga_recaudaciones aa
            INNER JOIN public.core_entidad_patronal bb ON bb.id_entidad_patronal = aa.id_entidad_patronal";
        $where1     = " 1 = 1  AND id_estatus = 1 AND aa.usuario_usuarios = '$_usuario_usuarios' ";
        $id1        = " aa.id_carga_recaudaciones";
        
        if(strlen($_busqueda) > 0){
            $where1.=" AND nombre_carga_recaudaciones LIKE '".$_busqueda."%'";
        }
       
        $html="";
        $resultSet=$Contribucion->getCantidad("*", $tablas1, $where1);
        $cantidadResult=(int)$resultSet[0]->total;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$Contribucion->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
        $total_pages = ceil($cantidadResult/$per_page);       
        
        
        if($cantidadResult>0){
            
            $html.= "<table id='tbl_lista_archivos_leidos' class='table tablesorter table-striped table-bordered dt-responsive nowrap'>";
            $html.= "<thead>";            
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Año</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Mes</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';            
            $html.='<th style="text-align: left;  font-size: 12px;">Formato</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Lineas Archivo</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Total</th>';
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.= '</tr>';
            $html.= '</thead>';
            $html.= '<tbody>';
            
            
            
            $i=0;
            foreach ($resultSet as $res){
                $i++;
                
                $html.='<tr>';
                $html.='<td style="font-size: 11px;">'.$i.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->usuario_usuarios.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->anio_carga_recaudaciones.'</td>';
                $html.='<td style="font-size: 11px;">'.$this->devuelveMesNombre($res->mes_carga_recaudaciones).'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_entidad_patronal.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->formato_carga_recaudaciones.'</td>';
                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->lineas_carga_recuadaciones.'</td>';
                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->suma_carga_recuadaciones.'</td>';
                $html.='<td style="font-size: 18px;">';
                $html.='<span class="pull-right ">
                            <a onclick="verArchivo(this)" id="" data-idarchivo="'.$res->id_carga_recaudaciones.'"
                            href="#" class="btn btn-sm btn-default label label-info">
                                <i class="fa  fa-file-text" aria-hidden="true" ></i>
                            </a>
                        </span>';
                $html.='</td>';
                $html.='</tr>';
            }
            
            $html.='</tbody>';
            /*para totalizar las filas*/
            $html.='<tfoot>';
            $html.='<tr>';
            $html.='<th colspan="9" ></th>';           
            $html.='</tr>';
            $html.='</tfoot>';
            $html.='</table>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"listaArchivosRecaudacion").'';
            $html.='</div>';
            
        }else{
            
            $html.= "<table id='tbl_lista_archivos_leidos' class='table tablesorter  table-striped table-bordered dt-responsive nowrap'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Año</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Mes</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Formato</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Lineas Archivo</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Total</th>';
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='</thead>';
            $html.='<tbody>';
            $html.='</tbody>';
            $html.='</table>';
        }
        
        echo json_encode(array('tablaHtml'=>$html,'cantidadRegistros'=>$cantidadResult));
       
        
    }
    
    public function cargaArchivoRecaudacion()
    {       
        
        $Contribucion      = new CoreContribucionModel();
        $carga_recaudaciones = new CargaRecaudacionesModel();
        $respuesta         = array();
        $error             = "";
        
        if( !isset($_SESSION) )
        {
            session_start();
        }
        
        /** variables funcion */
        $_id_entidad_patronal  = 0;
        $_anio_carga_recaudaciones     = 0;
        $_mes_carga_recaudaciones      = 0;
        $_formato_carga_recaudaciones  = 0;
        $_usuario_usuarios             = "";
        $_arhivo_carga_datos           = array();
        $_archivo_procesar             = "";
        $_error_cabecera               = "ARCHIVO NO FUE PROCESADO";
        $_numero_lineas_archivo        = 0;
        $_suma_total_archivo           = 0.00;
        $_nombre_archivo_guardar       = "";
        $_ruta_archivo_guardar         = "";
        $_filas_archivo                = array();
        
        /**
         ****************************************************** validar las variables a procesar ****************************************************
         **/
        try {
            
            $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
            $_id_descuentos_formatos       = $_POST['id_descuentos_formatos'];
            $_anio_carga_recaudaciones     = $_POST['anio_carga_recaudaciones'];
            $_mes_carga_recaudaciones      = $_POST['mes_carga_recaudaciones'];
            $_formato_carga_recaudaciones  = $_POST['formato_carga_recaudaciones'];
            $_usuario_usuarios  = $_SESSION['usuario_usuarios'];
            $_id_usuarios       = $_SESSION['id_usuarios'];
            $_arhivo_carga_datos   = $_FILES["nombre_carga_recaudaciones"];
            
            $error = error_get_last();
            if(!empty($error)){    throw new Exception("Variables no definidas"); }
                        
            if ($_arhivo_carga_datos['tmp_name'] == null ||  $_arhivo_carga_datos['tmp_name'] == "") {
                throw new Exception("Archivo txt no recibido/Valido"); 
            }
            
            /** consulta a bd de los parametros que recibe.
             * antes de validar archivo. valida si el archivo ya fue cargado
             */
            $datos =  array(
                'mes_recaudacion'   =>$_mes_carga_recaudaciones,
                'anio_recaudacion'  =>$_anio_carga_recaudaciones,
                'formato_recaudacion'   =>$_formato_carga_recaudaciones,
                'id_entidad_patronal'   => $_id_entidad_patronal
            );
            $auxValidaBd    = $this->validarArchivoenBD($datos);
                       
            if( $auxValidaBd['error'] ){
                throw new Exception($auxValidaBd['mensaje']);
            }
                                   
        }catch (Exception $e) {
            echo "<message>".$e->getMessage()."<message>";
            exit();
        }
        
       
        /**
         ****************************************************** validar la creacion del archivo ****************************************************
         **/
        try {
            
            $directorio = $this->crearPath($_anio_carga_recaudaciones, $_mes_carga_recaudaciones, "CARGAARCHIVOS");
            $_ruta_archivo_recaudaciones   = $directorio['ruta'];
            $nombre = $_arhivo_carga_datos['name'];
            $_ruta_archivo_guardar = $_ruta_archivo_recaudaciones;
            $_nombre_archivo_guardar = $nombre;           
            
            $_archivo_procesar = $_ruta_archivo_recaudaciones.'/'.$nombre;
            $archivo_move = move_uploaded_file($_arhivo_carga_datos ['tmp_name'],$_archivo_procesar);
            if( !$archivo_move ){
                throw new Exception("Error al leer Archivo. Por Favor subir nuevamente");
            }
            
        } catch (Exception $e) {
            echo "<message>".$e->getMessage()."<message>";
            exit();
        }
        
        /**
         ****************************************************** validar formato del archivo ***********************************************************
         **/
        $dataTableData      = array(); //variable donde se guarda los datos leidos
        /** formato standar es multiarray 'linea'=>1,'cedula'=>'00000000000','valor'=>0.00,'id_participes'=>1 **/
        
        try {
            
            /** validacion lineas archivo **/
            $dataTableError     = array(); //variable donde se guarda si existe error en la lectura del archivo            
            //$_archivo_procesar
            $rowError   = true;
            
            /** funcion que valida lineas del archivo a procesar **/
            $this->validacionArchivoLineas($_archivo_procesar, $rowError, $dataTableError, $dataTableData);
            
            if( $rowError ){
                if( sizeof( $dataTableError ) > 0 ){
                    echo json_encode(array("cabecera"=>"$_error_cabecera","dataerror"=>$dataTableError) );
                }else{
                    $arr    = array( 'linea'=>"",'error'=>"Archivo se encuentra vacio",'cantidad'=>0);
                    echo json_encode(array("cabecera"=>"$_error_cabecera","dataerror"=>$arr) );
                }
                
                throw new Exception();
            }
            
            /** variables para funcion de validacion de cedulas **/
            $dataFilasArchivo       = array(); //objeto donde guarda datos leidos y aprobados 
            $rowError   = false; //se establece en false en caso de generar error se produce dentro de la funcion
            
            /** funcion que valida cedulas repetidas **/
            $this->validacionCedulasRepetidas($rowError, $dataTableData, $dataTableError);
            
            if( $rowError ){
                $_error_cabecera    = "ERROR LECTURA CEDULAS";
                echo json_encode(array("cabecera"=>"$_error_cabecera","dataerror"=>$dataTableError) );                
                throw new Exception();
            }
            
            /** funcion que valida cedulas contra BD y genera nuevo datatable **/
            $rowError   = true;
            $dataTableError = array();
            $this->validacionArchivoCedulas( $_id_entidad_patronal, $rowError, $dataTableData, $dataTableError, $dataFilasArchivo);
                        
            if( $rowError ){
                $_error_cabecera    = "ERROR LECTURA VS BD";
                echo json_encode(array("cabecera"=>"$_error_cabecera","dataerror"=>$dataTableError) );
                throw new Exception();
            }
            
            /** lectura de archivos para obtener numero de lineas y suma total **/
            $_numero_lineas_archivo = 0;
            $_suma_total_archivo    = 0.00;
            foreach ( $dataFilasArchivo as $res ){
                $_numero_lineas_archivo ++;
                $_suma_total_archivo    += $res['valor'];
            }
            
                
        } catch (Exception $e) {
            //echo "<message>".$e->getMessage()."<message>"; 
            exit();
        }
        
        /**
         ****************************************************** insercion del archivo en tabla ***********************************************************
         **/
        try{
            
            //buscar formatos decuentos
            $col1   = "";
            $tab
            
            $Contribucion->beginTran();
            
            /**variables para trabajar en el insertado */
            $_id_carga_recaudaciones = 0;
            $respuesta = array();
            
            $funcion = "ins_core_carga_recaudaciones";
            $parametros = "'$_id_entidad_patronal','$_mes_carga_recaudaciones','$_anio_carga_recaudaciones','$_ruta_archivo_guardar','$_nombre_archivo_guardar','$_usuario_usuarios','FALSE', '$this->_nombre_formato','$_numero_lineas_archivo','$_suma_total_archivo'";
            $_queryInsercionCarga = $Contribucion->getconsultaPG($funcion, $parametros);
            $resultado = $carga_recaudaciones->llamarconsultaPG($_queryInsercionCarga);            
            $error= pg_last_error();
            if(!empty($error)){ throw new Exception($error); }
            
            $_id_carga_recaudaciones = $resultado[0];   
            
            /*************** Aqui comienza a recorrer el array de filas del archivo **********************/
            $funcion = "ins_core_carga_recaudaciones_detalle";
            $parametros = "";
            $_error_detalle = false;
            foreach ( $dataFilasArchivo as $res ){
                
                // set up vars.
                $_det_linea  = $res['linea'];
                $_det_cedula = $res['cedula'];
                $_det_valor = $res['valor'];                                
                $parametros = "$_id_carga_recaudaciones,$_det_linea,'$_det_cedula',$_det_valor";
                //echo $parametros;
                $_queryInserciondetalle = $Contribucion->getconsultaPG($funcion, $parametros);                
                $resultado_detalle = $carga_recaudaciones->llamarconsultaPG($_queryInserciondetalle); 
                $_det_error = pg_last_error();
                if(!empty($_det_error)){ $_error_detalle = true; break;}
            }
            
            if( $_error_detalle ){ throw new Exception("Error insertado detalle de archivo"); }
            
            /** aqui realizar insertado de aportes en la tablas de contribucion **/
            $dataCabecera   = array();
            $dataCabecera['id_entidad_patronal']    = $_id_entidad_patronal;
            $dataCabecera['anio_recaudaciones']     = $_anio_carga_recaudaciones;
            $dataCabecera['mes_recaudaciones']      = $_mes_carga_recaudaciones;
            $dataCabecera['usuario_usuarios']       = $_usuario_usuarios;
            $dataCabecera['id_usuarios']            = $_id_usuarios;
            $dataCabecera['fecha_descuentos_registrados']   = date('Y-m-d H:i:s');
            $dataCabecera['nombre_archivo_registrados']     = $_nombre_archivo_guardar;
            $dataCabecera['id_descuentos_formatos']         = $_id_descuentos_formatos;
            $dataCabecera['procesado_descuentos_registrados']   = 't';
            $dataCabecera['error_descuentos_registrados']       = 'f';
            $dataCabecera['tipo_credito']           = "null"; //aqui poner el valor de tipo_credito ..cambiaria si es tipo credito
            $dataCabecera['observacion_descuentos_registrados']     = "";
            $dataCabecera['fecha_proceso_descuentos_registrados']   = date('Y-m-d H:i:s');
                        
            $auxDescuentos  = $this->InsertDescuentos( $this->_nombre_formato, $dataCabecera, $dataFilasArchivo );
            
            var_dump($auxDescuentos);
            
            if( $auxDescuentos['error'] ){ throw new Exception( $auxDescuentos['mensaje'] ); }
            
            /*****************************************************************************************/
            
            $respuesta['mensaje']   = "Carga Generada";
            $respuesta['respuesta'] = 1;
            $respuesta['id_archivo']= $_id_carga_recaudaciones;
            echo json_encode( $respuesta );
            $Contribucion->endTran('COMMIT');
            
        } catch (Exception $ex) {
            $Contribucion->endTran();
            echo '<message> Error Carga Archivo Recaudacion \n'.$ex->getMessage().' <message>';
        }
        
    }
    /** END DC CONFIG*/
    
    /** dc begin 2020/04/17 **/
    private function InsertDescuentos( string $formato, array $paramsCab, array $datos){
        
        $resp   = null;  
        $recaudaciones = new RecaudacionesModel();
        
        $funcion = "core_ins_descuentos_registrados_cabeza";
        
        //creacion de la variable parametros
        $parametros = "";
        $parametros .= $paramsCab['id_entidad_patronal'].",";
        $parametros .= $paramsCab['anio_recaudaciones'].",";
        $parametros .= $paramsCab['mes_recaudaciones'].",";
        $parametros .= "'".$paramsCab['usuario_usuarios']."',";
        $parametros .= $paramsCab['id_usuarios'].",";
        $parametros .= "'".$paramsCab['fecha_descuentos_registrados']."',";
        $parametros .= "'".$paramsCab['nombre_archivo_registrados']."',";
        $parametros .= $paramsCab['id_descuentos_formatos'].",";
        $parametros .= "'".$paramsCab['procesado_descuentos_registrados']."',";
        $parametros .= "'".$paramsCab['error_descuentos_registrados']."',";
        $parametros .= $paramsCab['tipo_credito'].",";
        $parametros .= "'".$paramsCab['observacion_descuentos_registrados']."',";
        $parametros .= "'".$paramsCab['fecha_proceso_descuentos_registrados']."'";
        
        $sqRecaudaciones    = $recaudaciones->getconsultaPG($funcion, $parametros);
        
        $resultadoCabecera  = $recaudaciones->llamarconsultaPG($sqRecaudaciones);
        $id_cabecera    = $resultadoCabecera[0];
        
        if( $this->_nombre_aportes_formato == $formato )
        { 
            
            $id_entidad_patronal    = $paramsCab['id_entidad_patronal']; 
            $id_formatos_descuentos = $paramsCab['id_descuentos_formatos'];
            $anio_recaudacion       = $paramsCab['anio_recaudaciones'];
            $mes_recaudacion        = $paramsCab['mes_recaudaciones'];
            /** formato standar revisar definicion code ant 'linea'=>1,'cedula'=>'00000000000','valor'=>0.00,'id_participes'=>1 **/
            $detalle    = array();
            $funcionDetalle = "core_ins_descuentos_registrados_detalle_aportes";
            
            foreach ( $datos as $res ){
                                
                $detalle['id_descuentos_registrados_cabeza']    = $id_cabecera;
                $detalle['id_entidad_patronal']                 = $id_entidad_patronal;
                $detalle['anio_descuentos']                     = $anio_recaudacion;
                $detalle['mes_descuentos']                      = $mes_recaudacion;
                $detalle['id_participes']                       = $res['id_participes'];
                $detalle['aporte_personal']                     = $res['valor'];
                $detalle['aporte_patronal']                     = "0.00";
                $detalle['rmu_descuentos']                      = "0.00";
                $detalle['liquido_descuentos']                  = "0.00";
                $detalle['multas_descuentos']                   = "0.00";
                $detalle['antiguedad_descuentos']               = "0.00";
                $detalle['alta_descuentos']                     = "t";
                $detalle['id_descuentos_formatos']              = $id_formatos_descuentos;
                $detalle['procesado_descuentos']                = "t";
                $detalle['saldo_descuentos']                    = "0.00";
                
                $parametrosDetalle  = "'".join("','", $detalle)."'";
                $sqDetalle  = $recaudaciones->getconsultaPG($funcionDetalle, $parametrosDetalle);
                
                $recaudaciones->llamarconsultaPG($sqDetalle);
                
                if( !empty( pg_last_error() ) ){
                    break;
                }
                
            }
            
           
            
        }elseif ($this->_nombre_creditos_formato == $formato ){
            
        }else{
            
        }      
        
        if( !empty( error_get_last() ) || !empty( pg_last_error() ) ){
            $resp['error'] = true;
            $resp['mensaje'] = "Error en insertado de Descuentos";
            return $resp;
        }
        
        $resp['error']=false;
        $resp['mensaje']="";
        return $resp;
        
    }
    /** dc end 2020/04/17 **/
    
    /** begin dc 2020/04/20 **/
    public function validarArchivoenBD(array $datos){
        
        $resp = array();
        $recaudaciones = new RecaudacionesModel();
        
        $_mes_carga_recaudaciones       = array_key_exists('mes_recaudacion', $datos) ? $datos['mes_recaudacion'] : null;
        $_anio_carga_recaudaciones      = array_key_exists('anio_recaudacion', $datos) ? $datos['anio_recaudacion'] : null;
        $_formato_carga_recaudaciones   = array_key_exists('formato_recaudacion', $datos) ? $datos['formato_recaudacion'] : null;
        $_id_entidad_patronal           = array_key_exists('id_entidad_patronal', $datos) ? $datos['id_entidad_patronal'] : null;
        
        $_mes_carga_recaudaciones = str_pad($_mes_carga_recaudaciones, 2, "0", STR_PAD_LEFT);
        
        $this->devuelveNombreFormato($_formato_carga_recaudaciones); //aqui se setea las variables para comparar        
        $_nombre_formato = $this->_nombre_formato;
        
        if( $_formato_carga_recaudaciones == 1 || $_formato_carga_recaudaciones == 2 ){
            
            $columnas1 = " id_carga_recaudaciones, nombre_carga_recaudaciones";
            $tablas1   = " core_carga_recaudaciones";
            $where1    = " id_estatus = 1 AND id_entidad_patronal = $_id_entidad_patronal AND anio_carga_recaudaciones = $_anio_carga_recaudaciones".
                " AND mes_carga_recaudaciones = $_mes_carga_recaudaciones AND formato_carga_recaudaciones = '".$_nombre_formato."' ";
            $id1       = "id_carga_recaudaciones";
            $rsConsulta1 = $recaudaciones->getCondiciones($columnas1, $tablas1, $where1, $id1);
            
            if( !empty($rsConsulta1) ){
                $resp['mensaje']    = " tipo de formato ya se encuentra cargado ";                
            }else{
                
                $columnas2 = " id_carga_recaudaciones, nombre_carga_recaudaciones";
                $tablas2   = " core_carga_recaudaciones";
                $where2    = " id_estatus = 1 AND id_entidad_patronal = $_id_entidad_patronal AND anio_carga_recaudaciones = $_anio_carga_recaudaciones".
                    " AND mes_carga_recaudaciones = $_mes_carga_recaudaciones AND formato_carga_recaudaciones = '".$this->_nombre_combinado_formato."' ";
                $id2       = " id_carga_recaudaciones";
                $rsConsulta2 = $recaudaciones->getCondiciones($columnas2, $tablas2, $where2, $id2);
                
                if( !empty($rsConsulta2) ){
                    $resp['mensaje']    = "Archivo no cargado . Razon: Existe un archivo Combinado cargado ";
                }
                
            }
            
        }else if( $_formato_carga_recaudaciones == 3){
            
            $columnas1 = " id_carga_recaudaciones, nombre_carga_recaudaciones";
            $tablas1   = " core_carga_recaudaciones";
            $where1    = " id_estatus = 1 AND id_entidad_patronal = $_id_entidad_patronal AND anio_carga_recaudaciones = $_anio_carga_recaudaciones".
                " AND mes_carga_recaudaciones = $_mes_carga_recaudaciones AND formato_carga_recaudaciones = '$_nombre_formato' ";
            $id1       = " id_carga_recaudaciones";
            $rsConsulta1 = $recaudaciones->getCondiciones($columnas1, $tablas1, $where1, $id1);
            
            if( !empty($rsConsulta1) ){
                $resp['mensaje']    = "tipo de formato ya se encuentra cargado ";
            }else{
                $columnas2 = " id_carga_recaudaciones, nombre_carga_recaudaciones";
                $tablas2   = " core_carga_recaudaciones";
                $where2    = " id_estatus = 1 AND id_entidad_patronal = $_id_entidad_patronal AND anio_carga_recaudaciones = $_anio_carga_recaudaciones".
                    " AND mes_carga_recaudaciones = $_mes_carga_recaudaciones AND formato_carga_recaudaciones in ('DESCUENTOS CREDITOS','DESCUENTOS APORTES')";
                $id2       = " id_carga_recaudaciones";
                $rsConsulta1 = $recaudaciones->getCondiciones($columnas2, $tablas2, $where2, $id2);
                if( !empty($rsConsulta1) ){
                    $resp['mensaje']    = "tipo de formato no se puede cargar existe un archivo individual.";
                }
            }
        }
        
        if( array_key_exists('mensaje', $resp) ){
            $resp['error']  = true;
            return $resp;
        }
        
        return array('error'=>false,'mensaje'=>"");
        
    }
    
    /** 2020-06-29 **/
    public function cargaDescuentosFormatos()
    {
        
        $recaudaciones = new RecaudacionesModel();
        $resp  = null;
        
        $col1  = " id_descuentos_formatos, nombre_descuentos_formatos ";
        $tab1  = " public.core_descuentos_formatos ";
        $whe1  = "entrada_descuentos_formatos = 't'
        	    AND sp_descuentos_formatos = 'RP'";
        $id1   = " nombre_descuentos_formatos ";
        
        $rsConsulta1   = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
        
        if( !empty( pg_last_error() )  || !empty( error_get_last() ) ){
            
            $error = error_get_last();
            error_clear_last();
            if (ob_get_contents()) ob_end_clean();
            
            echo "ERROR ENCONTRADO \n";
            var_dump($error);
            
            return;
        }
        
        $resp['data'] = ( !empty( $rsConsulta1 ) ) ? $rsConsulta1 : null;
        
        echo json_encode($resp);       
       
    }
    /** end 2020-06-29 **/

    private function validacionArchivoLineas( string $file, string &$rowError, array  &$dataTableError, array &$dataTableData){
        
        /** variables para la lectura del archivo **/
        $_archivo_cedulas = array();
        $_suma_total_archivo = 0.00;
        
        $rowError   = false;
        
        $file_abierto   = fopen($file, "r");    // comienza lectura de archivo
        if( !$file_abierto ){ $rowError = true; }
       
        /** AQUI VALIDACION DE FORMATOS */
        $_linea = 0;
        $_linea_llena = 0;
        
        while( !feof($file_abierto) )
        {
            $_valor     = 0.00;
            $_fila = fgets($file_abierto);
            $_fila = trim($_fila);
            $_fila = trim($_fila,"\n");
            
            if( $_linea > 0 && $_fila != "" ){
                
                $_array_fila   = explode(";", $_fila);
                if( is_array($_array_fila) && sizeof( $_array_fila ) == 3 ){
                    
                    $_cedula    = $_array_fila[0];
                    $_valor     = $_array_fila[2];
                    if( strlen($_cedula) != 10 ){
                       //validacion del 
                        array_push($dataTableError, array("linea"=>$_linea,"error"=>"cedula no tiene formato correcto","cantidad"=>0));
                        $rowError   = true;
                    }
                    if( !is_numeric( $_valor ) ){
                        // se valida si el valor es numerico
                        array_push($dataTableError, array("linea"=>$_linea,"error"=>"valor no tiene formato numerico","cantidad"=>0));
                        $rowError   = true;
                    }
                    
                    //procesos para la fila que paso los filtros
                    array_push($_archivo_cedulas, array("linea"=>$_linea,"cedula"=>$_cedula));
                    array_push($dataTableData, array("linea"=>$_linea,"cedula"=>$_cedula,"valor"=>$_valor));
                    
                    $_linea_llena++;
                    $_suma_total_archivo = $_suma_total_archivo + (float)$_valor;
                    
                }else{
                    
                    array_push($dataTableError, array("linea"=>$_linea,"error"=>"Numero de columnas no Validas --> <span class='text-danger'> Columnas Solicitadas es 3 <span> ","cantidad"=>0));
                    $rowError   = true;
                }
                
            }else if( $_linea > 0 && $_fila == "" ){
                
                array_push($dataTableError, array("linea"=>$_linea,"error"=>"linea se encuentra vacia","cantidad"=>0));
                $rowError   = true;
            }
            $_linea++;
        }
        fclose($file_abierto); 
        
        
        //         $resp   = array();
        //         $resp['error']  = $rowError;
        //         $resp['dtError']    = ( !empty( $_archivo_errores ) ) ? $_archivo_errores : null;
        //         $resp['dtArchivo']  = ( !empty( $_filas_archivo ) ) ? $_filas_archivo : null;
                
        //         return $resp;
        
    }
    
    private function validacionCedulasRepetidas(string &$rowError, array $data,  array &$errorFilas){
        
        if( sizeof( $data ) > 0 ){
            
            $_cantidad_repeticiones_cedulas = 0;
            $_array_buscar_cedulas  = $data; //ser realiza una copia del array de cedulas
            $_i = 0;
            
            foreach ( $data as $res_i ){
                
                $cedula_main = $res_i['cedula']; //cedula a validar
                $j = 0;
                $_array_disponibles = array(); // variable para reorganizar nuevamente el array copia de cedulas recogidas en el primer recorrido del archivo
                
                $_linea_error="[";                
                foreach ( $_array_buscar_cedulas as $res_j ){
                    
                    if( $cedula_main == $res_j['cedula'] ){
                        $_linea_error .= $res_j['linea'].", "; //coleccionando en las filas donde ese encuentra repetida la cedula
                        $_cantidad_repeticiones_cedulas ++; //se suma las cantidades de veces de repeticiones
                        unset($_array_buscar_cedulas[$j]); //eliminar el alemento del array ya encontrado
                    }else{
                        $_array_disponibles[] = $_array_buscar_cedulas[$j]; //se agrega al array para resetear los indices
                    }
                    $j++;
                }
                $_linea_error.="]";
                
                if( $_cantidad_repeticiones_cedulas > 1 ){
                    $rowError   = true;
                    array_push($errorFilas, array("linea"=>$_linea_error,"error"=>"cedula repetida - $cedula_main","cantidad"=>$_cantidad_repeticiones_cedulas));
                }
                
                $_array_buscar_cedulas = $_array_disponibles; //variable seteada                
                
                $_cantidad_repeticiones_cedulas = 0;
                $_i ++;
            }
        }else{
            $rowError   = true;
            array_push($errorFilas, array('linea'=>1,'error'=>"Data Vacia Validacion Cedulas",'Cantidad'=>0));
        }
        
    }
    
    private function validacionArchivoCedulas( string $id_entidad_patronal,string &$rowError, array $data,  array &$errorFilas, array &$ArchivoFilas ){
        
        $recaudaciones = new RecaudacionesModel();
        
        /** recorrer cedula validas contra BD **/
        $columnas1  = " aa.id_participes, aa.cedula_participes";
        $tablas1    = " core_participes aa
                            INNER JOIN core_entidad_patronal bb ON bb.id_entidad_patronal = aa.id_entidad_patronal";
        $where1     = " 1 = 1 AND bb.id_entidad_patronal = $id_entidad_patronal";
        $id1        = "aa.id_participes";
        
        $_bd = true;
        foreach(  $data as $res){
            
            $_linea_archivo  = $res['linea'];
            $_cedula_archivo = $res['cedula'];
            $_valor_archivo  = $res['valor'];
            $whereCedula     = " AND aa.cedula_participes = '$_cedula_archivo'";
            $wherefinal      = $where1.$whereCedula;
            $rsConsulta1 = $recaudaciones->getCondiciones($columnas1, $tablas1, $wherefinal, $id1); 
            if( empty($rsConsulta1) ){
                $_bd = false;                
                $_error_bd      = "cedula [$_cedula_archivo] no pertenece a la Entidad Patronal";
                array_push( $errorFilas, array( 'linea'=>$_linea_archivo,'error'=>$_error_bd,'cantidad'=>0));
            }else{
                $id_participes = $rsConsulta1[0]->id_participes;
                array_push($ArchivoFilas, array('linea'=>$_linea_archivo,'cedula'=>$_cedula_archivo,'valor'=>$_valor_archivo,'id_participes'=>$id_participes));
            }
            
        }
        
        if( $_bd ){
            
            $rowError   = false;
        }
        
    }
    /** end dc 2020/04/21 **/
    
    /** BEGIN FUNCIONES UTILITARIAS PARA LA CLASE */
    private function devuelveMesNombre($_mes){
        
        $meses = array('enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
        $_intMes = (int)$_mes;
        return $meses[$_intMes-1];
        
    }
    
    public function imprimirarray(){
        
        $meses = array('enero'=>'1','febrero'=>1256,'marzo'=>'1','abril'=>'1','mayo'=>'1','junio'=>'1','julio'=>'34','f'=>'1','septiembre'=>'1','octubre'=>'1','noviembre'=>'1','diciembre'=>'1');
        echo "'".join("','", $meses)."'";
    }
    
    private function devuelveNombreFormato($_formato){
        
        if($_formato == 1 ){
            $this->_nombre_formato = $this->_nombre_aportes_formato;
        }else if($_formato == 2 ){
            $this->_nombre_formato = $this->_nombre_creditos_formato;
        }else if($_formato == 3 ){
            $this->_nombre_formato = $this->_nombre_combinado_formato;
        }
        
    }
    /** END FUNCIONES UTILITARIAS PARA LA CLASE */
    
    
    /**codigos reutilizados*/
    public function fn_olvidados(){
        /** AQUI VALIDACION DE FORMATOS */
        $_linea = 0;
        $file_abierto = null;
        $_sumatoria = null;
        $_cedula_ant = null;
        while(!feof($file_abierto))
        {
            
            $_valor     = 0.00;
            $_fila = fgets($file_abierto);
            $_fila = trim($_fila);
            if( $_linea > 0 && $_fila != "" ){
                
                $_array_fila   = explode(";", $_fila);
                if( is_array($_array_fila) && sizeof( $_array_fila ) == 3 ){
                    
                    $_cedula    = $_array_fila[0];
                    $_valor     = $_array_fila[2];
                    if( strlen($_cedula) != 10 ){
                        echo "\n","linea--**--",$_linea,"--**--","error cedula no tiene formato";
                    }
                    if( is_numeric( $_valor ) ){
                        echo "\n","linea--**--",$_linea,"--**--","valor no tiene formato";
                    }
                    
                    
                    $_sumatoria = $_sumatoria + $_valor;
                    
                    
                    
                    if( $_cedula_ant != $_cedula  ){
                        
                        echo "\n";
                        echo $_linea,"--**--";
                        echo $_fila,"---****---<br>";
                        echo $_cedula,"---****---<br>";
                        echo $_valor,"---****---<br>";
                        echo $_sumatoria,"---****---<br>";
                    }else{
                        
                        echo "\n";
                        echo "error linea",$_linea,"--**--";
                        echo "Cedula repetida--->",$_cedula;
                        
                    }
                    
                    
                }else{
                    echo "\n";
                    echo "error linea",$_linea,"--**--";
                    echo "no es array";
                }
                
            }
            
            $_cedula_ant = $_cedula;
            
            /*if($_i_linea>0){
             if(!empty($_fila)){
             $_cantidad_lineas++;
             $error = true;
             
             $error = $error = is_numeric($_array_fila[6]) ? false : true;
             if($error){
             throw new  Exception("Contenido no Valido Revise el archivo.. linea ".$_cantidad_lineas);
             }
             $_suma_linea += (float)$_array_fila[6];
             }
             }
             $_i_linea++;*/
            $_linea++;
        }
        fclose($file_abierto);
    }
    /** termina funcion de codigos reutilizads*/
    
    public function verapi(){
         echo json_encode(array('error'=>true,'mensaje'=>'ha llegado conexion exitosa fectch'));
    }
    
}

?>