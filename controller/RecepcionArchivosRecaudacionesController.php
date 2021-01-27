<?php

class RecepcionArchivosRecaudacionesController extends ControladorBase{
    
    private $_nombre_formato="";
    private $_nombre_creditos_formato  = "DESCUENTOS CREDITOS";
    private $_nombre_aportes_formato   = "DESCUENTOS APORTES";
    private $_nombre_combinado_formato = "DESCUENTOS APORTES Y CREDITOS";	
    private $datosCabecera  = array(); //variable para guardar datos cabecera parametros BD
    
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
			
	
				
		$this->view_Recaudaciones("RecepcionArchivosRecaudaciones",array(
		    "resultSet"=>""
	
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
        $recaudaciones      = new RecaudacionesModel();
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
        $_usuario_usuarios             = "";
        $_arhivo_carga_datos           = array();
        $_archivo_procesar             = "";
        $_error_cabecera               = "ARCHIVO NO FUE PROCESADO";
        $_numero_lineas_archivo        = 0;
        //$_suma_total_archivo           = 0.00;
        $_nombre_archivo_guardar       = "";
        $_ruta_archivo_guardar         = "";
        //$_filas_archivo                = array();
        
        /**
         ****************************************************** validar las variables a procesar ****************************************************
         **/
        try {
            
            $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
            $_id_descuentos_formatos       = $_POST['id_descuentos_formatos'];
            $_anio_carga_recaudaciones     = $_POST['anio_carga_recaudaciones'];
            $_mes_carga_recaudaciones      = $_POST['mes_carga_recaudaciones'];
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
                'id_descuentos_formatos'   =>$_id_descuentos_formatos,
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
            
            $_mes_carga_recaudaciones = str_pad($_mes_carga_recaudaciones, 2, "0", STR_PAD_LEFT);
            
            $directorio = $this->crearPath($_anio_carga_recaudaciones, $_mes_carga_recaudaciones, "CARGAARCHIVOS");
            $_ruta_archivo_recaudaciones   = $directorio['ruta'];
            $nombre = $_arhivo_carga_datos['name'];
            $_ruta_archivo_guardar = $_ruta_archivo_recaudaciones;
            $_nombre_archivo_guardar = $nombre;
            $_nombre_archivo_guardar_descuentos = "";
            
            $colEntidad = " id_entidad_patronal, nombre_entidad_patronal";
            $tabEntidad = " public.core_entidad_patronal";
            $wheEntidad = " id_entidad_patronal = $_id_entidad_patronal";
            $idEntidad  = " id_entidad_patronal";
            
            $rsEntidad  = $recaudaciones->getCondiciones($colEntidad, $tabEntidad, $wheEntidad, $idEntidad);
            if( !empty( $rsEntidad ) )
            {
                $nombreEntidad  = $this->limpiarCaracteresEspeciales( $rsEntidad[0]->nombre_entidad_patronal );
                $_nombre_archivo_guardar_descuentos = $nombreEntidad.$_anio_carga_recaudaciones.$_mes_carga_recaudaciones;
            }else
            {
                $_nombre_archivo_guardar_descuentos = $_nombre_archivo_guardar;
            }
            
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
            //$_suma_total_archivo    = 0.00;
            foreach ( $dataFilasArchivo as $res ){
                $_numero_lineas_archivo ++;
                //$_suma_total_archivo    += $res['valor'];
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
            $col1   = " id_descuentos_formatos, nombre_descuentos_formatos, parametro_uno_descuentos_formatos";
            $tab1   = " public.core_descuentos_formatos";
            $whe1   = " id_descuentos_formatos = $_id_descuentos_formatos";
            
            $rsConsulta1    = $recaudaciones->getCondicionesSinOrden( $col1, $tab1, $whe1, "");
            
            $tipoFormatoDescuentos  = $rsConsulta1[0]->parametro_uno_descuentos_formatos;  //se obtiene el formato de descuento  
                        
            $Contribucion->beginTran();
            
            //para obtener fecha actual
            $Ofecha_actual = new DateTime();
            //$fecha->modify('last day of this month');
            $fecha_actual  = $Ofecha_actual->format('Y-m-d');
            $fec_dia_actual= $Ofecha_actual->format('d');
            
            //para objeto de fecha actual
            $Ofecha_contable  = new DateTime();
            
            //validacion de fecha mora
            if( (int)$fec_dia_actual > 0 && (int)$fec_dia_actual < 6 )
            {
                if( (int)$_mes_carga_recaudaciones == 1 )
                {
                    $_anio_carga_recaudaciones  = (int)$_anio_carga_recaudaciones - 1;
                    $_mes_carga_recaudaciones   = 12;
                }
                //SINTAXERROR
                $fec_mes_moras = (int)$_mes_carga_recaudaciones - 1;
                $str_fec_moras = $_anio_carga_recaudaciones."-".$fec_mes_moras."-01";
                $Ofecha_contable  = new DateTime($str_fec_moras);
                
            }else
            {
                $str_fec_moras = $_anio_carga_recaudaciones."-".$_mes_carga_recaudaciones."-01";
                $Ofecha_contable  = new DateTime($str_fec_moras);
            }
            
            //fecha de moras
            $fecha_contable  = $Ofecha_contable->format('Y-m-t');
            
            /** aqui realizar insertado de aportes en la tablas de contribucion **/
            $dataCabecera   = array();
            $dataCabecera['id_entidad_patronal']    = $_id_entidad_patronal;
            $dataCabecera['anio_recaudaciones']     = $_anio_carga_recaudaciones;
            $dataCabecera['mes_recaudaciones']      = $_mes_carga_recaudaciones;
            $dataCabecera['usuario_usuarios']       = $_usuario_usuarios;
            $dataCabecera['id_usuarios']            = $_id_usuarios;
            $dataCabecera['fecha_descuentos_registrados']   = $Ofecha_actual->format('Y-m-01');
            $dataCabecera['nombre_archivo_registrados']     = $_nombre_archivo_guardar;
            $dataCabecera['id_descuentos_formatos']         = $_id_descuentos_formatos;
            $dataCabecera['procesado_descuentos_registrados']   = 'f';
            $dataCabecera['error_descuentos_registrados']       = 'f';
            $dataCabecera['tipo_credito']           = "null"; //aqui poner el valor de tipo_credito ..cambiaria si es tipo credito
            $dataCabecera['observacion_descuentos_registrados']     = "";
            $dataCabecera['fecha_proceso_descuentos_registrados']   = $fecha_actual;
            $dataCabecera['fecha_contable_descuentos_registrados']  = $fecha_contable;
            $dataCabecera['is_debito_bancario']     = 'f';
            $dataCabecera['comentario_descuentos']  = '';
                        
                        
            $auxDescuentos  = $this->InsertDescuentos( $tipoFormatoDescuentos, $dataCabecera, $dataFilasArchivo );
            
            if( $auxDescuentos['error'] ){ throw new Exception( $auxDescuentos['mensaje'] ); }
            
            $id_descuentos_cabeza   = $auxDescuentos['id_desccuentos_cabeza'];
            
            /** INSERTAR VALORES DE ARCHIVO **/
            $auxDescuentosArchivo   = $this->InsertarArchivoDescuentos( $id_descuentos_cabeza, $_nombre_archivo_guardar_descuentos, $_ruta_archivo_guardar, $_numero_lineas_archivo);
            
            if( $auxDescuentosArchivo['error'] ){ throw new Exception( $auxDescuentosArchivo['mensaje'] ); }
            
            $respuesta['mensaje']   = "Carga Generada";
            $respuesta['respuesta'] = 1;
            //$respuesta['id_archivo']= $_id_carga_recaudaciones;
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
        
        $funcion = "core_ins_descuentos_registrados_cabeza_recepcion";
        $id_cabecera    = 0;
                
        //creacion de la variable parametros
        //para cambiar datos de cabecera cambiar en el array que se forrma en el metodo que manada a  llamar
        
        $parametros  = "'".join("','", $paramsCab)."'";
        $parametros  = str_replace("'null'","null",$parametros);
        $sqRecaudaciones    = $recaudaciones->getconsultaPG($funcion, $parametros);
        
        if( $formato == "A" )
        { 
            $resultadoCabecera  = $recaudaciones->llamarconsultaPG($sqRecaudaciones);
            $id_cabecera    = $resultadoCabecera[0];
            
            $id_entidad_patronal    = $paramsCab['id_entidad_patronal']; 
            $id_formatos_descuentos = $paramsCab['id_descuentos_formatos'];
            $anio_recaudacion       = $paramsCab['anio_recaudaciones'];
            $mes_recaudacion        = $paramsCab['mes_recaudaciones'];
            
            /** formato standar revisar definicion code ant 'linea'=>1,'cedula'=>'00000000000','valor'=>0.00,'id_participes'=>1 **/
            $detalle    = array();
            $funcionDetalle = "core_ins_descuentos_registrados_detalle_aportes";
            
            foreach ( $datos as $res )
            {                                                
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
                $detalle['procesado_descuentos']                = "f";
                $detalle['saldo_descuentos']                    = "0.00";
                $detalle['valor_usuario']                       = "null";
                
                $parametrosDetalle  = "'".join("','", $detalle)."'";
                $parametrosDetalle  = str_replace("'null'","null",$parametrosDetalle);
                $sqDetalle  = $recaudaciones->getconsultaPG($funcionDetalle, $parametrosDetalle);
                
                $recaudaciones->llamarconsultaPG($sqDetalle);
                
                if( !empty( pg_last_error() ) ){
                    break;
                }
                
            }            
           
            
        }elseif ( $formato == "C")
        {
            
            $resultadoCabecera  = $recaudaciones->llamarconsultaPG($sqRecaudaciones);
            $id_cabecera    = $resultadoCabecera[0];
            
            $id_entidad_patronal    = $paramsCab['id_entidad_patronal'];
            $id_formatos_descuentos = $paramsCab['id_descuentos_formatos'];
            $anio_recaudacion       = $paramsCab['anio_recaudaciones'];
            $mes_recaudacion        = $paramsCab['mes_recaudaciones'];
            
            /** formato standar revisar definicion code ant 'linea'=>1,'cedula'=>'00000000000','valor'=>0.00,'id_participes'=>1 **/
            $detalle    = array();
            $funcionDetalle = "core_ins_descuentos_registrados_detalle_creditos";
                        
            foreach ( $datos as $res )
            {               
                $detalle['id_descuentos_registrados_cabeza']    = $id_cabecera;
                $detalle['id_entidad_patronal']                 = $id_entidad_patronal;
                $detalle['anio_descuentos']                     = $anio_recaudacion;
                $detalle['mes_descuentos']                      = $mes_recaudacion;
                $detalle['id_tipo_descuento']                   = 0;
                $detalle['id_participes']                       = $res['id_participes'];
                $detalle['id_creditos']                         = "0";
                $detalle['cuota_descuentos']                    = $res['valor'];
                $detalle['monto_descuentos']                    = "0.00";
                $detalle['plazo_descuentos']                    = "0";
                $detalle['alta_descuentos']                     = "t";
                $detalle['id_descuentos_formatos']              = $id_formatos_descuentos;
                $detalle['procesado_descuentos']                = "f";
                $detalle['saldo_descuentos']                    = "0.00";
                $detalle['mora_descuentos']                     = "0.00";
                $detalle['credito_pay_descuentos']              = "0";
                $detalle['mes_desc_descuentos']                 = "null";
                $detalle['valor_usuario_descuentos']            = "0.00";
                
                $parametrosDetalle  = "'".join("','", $detalle)."'";
                $parametrosDetalle  = str_replace("'null'","null",$parametrosDetalle);
                $sqDetalle  = $recaudaciones->getconsultaPG($funcionDetalle, $parametrosDetalle);
                
                $recaudaciones->llamarconsultaPG($sqDetalle);
                
                if( !empty( pg_last_error() ) ){
                    break;
                }
                
            }
            
        }else
        {
            $resp['error'] = true;
            $resp['mensaje'] = "Tipo Descuento no Definido";
            return $resp;
        }      
        
        if( !empty( error_get_last() ) || !empty( pg_last_error() ) )
        {
            $resp['error'] = true;
            $resp['mensaje'] = "Error en insertado de Descuentos";
            return $resp;
        }
        
        $resp['error']=false;
        $resp['mensaje']="";
        $resp['id_desccuentos_cabeza']  = $id_cabecera;
        return $resp;
        
    }
    /** dc end 2020/04/17 **/
    
    /** begin dc 2020/04/20 **/
    public function validarArchivoenBD(array $datos)
    {
        
        $recaudaciones = new RecaudacionesModel();
        
        $response = array();
        $rsConsulta1   = array();
        
        $_mes_carga_recaudaciones       = array_key_exists('mes_recaudacion', $datos) ? $datos['mes_recaudacion'] : null;
        $_anio_carga_recaudaciones      = array_key_exists('anio_recaudacion', $datos) ? $datos['anio_recaudacion'] : null;
        $_id_descuentos_formatos        = array_key_exists('id_descuentos_formatos', $datos) ? $datos['id_descuentos_formatos'] : null;
        $_id_entidad_patronal           = array_key_exists('id_entidad_patronal', $datos) ? $datos['id_entidad_patronal'] : null;
        
        $col1  = " COUNT(1) cantidad";
        $tab1  = " public.core_descuentos_registrados_cabeza";
        $whe1  = " year_descuentos_registrados_cabeza = $_anio_carga_recaudaciones
    	    AND mes_descuentos_registrados_cabeza = $_mes_carga_recaudaciones
    	    AND id_entidad_patronal = $_id_entidad_patronal
    	    AND id_descuentos_formatos = $_id_descuentos_formatos";
        
        $rsConsulta1 = $recaudaciones->getCondicionesSinOrden($col1, $tab1, $whe1, "");
        
        $error = error_get_last();
        if( !empty( $error ) ){
            $response['error']  = true;
            $response['mensaje']= $error['message'];
        }
        
        if( (int)$rsConsulta1[0]->cantidad > 0 ){
            $response['error']  = true;
            $response['mensaje']= 'ya existen datos con los valores recibidos';
        }else{
            $response['error']  = false;
        }
        
        return $response;        
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
                
                $_array_fila   = explode("\t", $_fila);
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
    
    /** dc 2020/06/30 **/
    private function InsertarArchivoDescuentos(int $id_descuentos_cabeza, string $nombre_archivo, string $ruta_archivo, int $lineas_archivo )
    {
        $recaudaciones  = new RecaudacionesModel();
        $resp   = array();
        
        ob_start();
        
        /** VALIDACION PARA SABER SI YA EXISTE EL ARCHIVO GENERADO **/
        $colvalidacion = " ubicacion_descuentos_registrados_archivo, nombre_descuentos_registrados_archivo";
        $tabvalidacion = " public.core_descuentos_registrados_archivo";
        $twhevalidacion = " id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
        $rsValidacion  = $recaudaciones->getCondicionesSinOrden($colvalidacion, $tabvalidacion, $twhevalidacion, "LIMIT 1");
        
        $usuario_usuarios   = "";
        if( !isset( $_SESSION ) )
        {
            session_start();
        }
        $usuario_usuarios   = $_SESSION['usuario_usuarios'];
        
        if( empty( $rsValidacion ) )
        {
            $funcion = "core_ins_descuentos_registrados_archivo";
            $parametros    = " $id_descuentos_cabeza, ";
            $parametros    .= "'$nombre_archivo',";
            $parametros    .= "'$ruta_archivo',";
            $parametros    .= "'$lineas_archivo',";
            $parametros    .= "'$usuario_usuarios',";
            $parametros    .= "'',";
            $parametros    .= "null";
            $spRecaudacionesArchivo    = $recaudaciones->getconsultaPG( $funcion, $parametros );
            $recaudaciones->llamarconsultaPG( $spRecaudacionesArchivo );
        }
        
        $buffer = ob_get_clean();
        
        if( !empty( $buffer ) )
        {
            $resp['error'] = true;
            $resp['mensaje']    = error_get_last()['message'];
        }else
        {
            $resp['error'] = false;
            $resp['mensaje']    = "";
        }
        
        return $resp;
    }
    /** end dc 2020/06/30 **/
    
    /** dc 2020/06/30 **/
    public function dtMostrarDescuentosPendientes()
    {
        if( !isset( $_SESSION ) ){
            session_start();
        }
        
        try {
            ob_start();
            
            $recaudaciones = new RecaudacionesModel();
                        
            //dato que viene de parte del plugin DataTable
            $requestData = $_REQUEST;
            $searchDataTable   = $requestData['search']['value'];
            
            /** buscar por el usuario que se encuentra logueado */
            $_usuario_logueado = $_SESSION['usuario_usuarios'];
            
            $id_entidad_patronal = $_POST['id_entidad_patronal'];
            
            $columnas1 = " aa.id_descuentos_registrados_cabeza, bb.nombre_entidad_patronal, aa.year_descuentos_registrados_cabeza, aa.mes_descuentos_registrados_cabeza,
                cc.parametro_uno_descuentos_formatos, dd.nombre_descuentos_registrados_archivo, aa.nombre_archivo_descuentos_registrados_cabeza,
                TO_CHAR(aa.fecha_descuentos_registrados_cabeza,'YYYY-MM-DD') fecha_descuentos, aa.fecha_contable_descuentos_registrados_cabeza";
            $tablas1   = " core_descuentos_registrados_cabeza aa
                INNER JOIN core_entidad_patronal bb on bb.id_entidad_patronal = aa.id_entidad_patronal
                INNER JOIN core_descuentos_formatos cc on cc.id_descuentos_formatos = aa.id_descuentos_formatos
                LEFT JOIN core_descuentos_registrados_archivo dd on dd.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza";
            $where1    = " cc.entrada_descuentos_formatos = true
                AND aa.procesado_descuentos_registrados_cabeza = false
                AND aa.erro_descuentos_registrados_cabeza = false
                AND aa.id_entidad_patronal = $id_entidad_patronal ";
                        
            /* PARA FILTROS DE CONSULTA */
            
            if( strlen( $searchDataTable ) > 0 )
            {
                $where1 .= " AND ( ";
                $where1 .= " bb.nombre_entidad_patronal ILIKE '%$searchDataTable%' ";
                $where1 .= " OR TO_CHAR(aa.year_descuentos_registrados_cabeza,'9999') ilike '%$searchDataTable%' ";
                $where1 .= " ) ";
                
            }
            
            $rsCantidad    = $recaudaciones->getCantidad("*", $tablas1, $where1);
            $cantidadBusqueda = (int)$rsCantidad[0]->total;
            
            /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
            
            // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
            $columns = array(
                0 => '1',
                1 => '1',
                2 => '1',
                3 => '1',
                4 => '1',
                5 => '1',
                6 => '1',
                7 => '1'
            );
            
            $orderby   = $columns[$requestData['order'][0]['column']];
            $orderdir  = $requestData['order'][0]['dir'];
            $orderdir  = strtoupper($orderdir);
            /**PAGINACION QUE VIEN DESDE DATATABLE**/
            $per_page  = $requestData['length'];
            $offset    = $requestData['start'];
            
            //para validar que consulte todos
            $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
            
            $limit = " ORDER BY $orderby $orderdir LIMIT   $per_page OFFSET '$offset'";
            
            $sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
            //$sql = "";
            
            $resultSet=$recaudaciones->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
                        
            /** crear el array data que contiene columnas en plugins **/
            $data = array();
            $dataFila = array();
            $columnIndex = 0;
            foreach ( $resultSet as $res){
                $columnIndex++;
                
                $opciones = ""; //variable donde guardare los datos creados automaticamente                
                                
                $nombretipo_descuentos = "";
                if( $res->parametro_uno_descuentos_formatos == "C")
                {
                    $nombretipo_descuentos  = "CREDITOS";
                    $opciones = '<div class="pull-right ">
                            <span >
                                <a onclick="mostrar_detalle(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            <span >
                                <a onclick="mostrar_detalle_modal_creditos(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle Modal"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                                    
                            </div>';
                }else
                {
                    $nombretipo_descuentos  = "APORTES";
                    $opciones = '<div class="pull-right ">
                            <span >
                                <a onclick="mostrar_detalle(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            <span >
                                <a onclick="mostrar_detalle_modal(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle Modal"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                                    
                            </div>';
                }
                
                $dataFila['numfila'] = $columnIndex;
                $dataFila['nombre_entidad']  = $res->nombre_entidad_patronal;
                $dataFila['nombre_usuarios'] = $_usuario_logueado;
                $dataFila['anio_descuentos'] = $res->year_descuentos_registrados_cabeza;
                $dataFila['mes_descuentos']  = $res->mes_descuentos_registrados_cabeza;
                $dataFila['nombre_formato']  = $nombretipo_descuentos;
                $dataFila['nombre_archivo']  = $res->nombre_descuentos_registrados_archivo;
                $dataFila['fecha_descuentos']= $res->fecha_descuentos;
                $dataFila['fecha_contable']  = $res->fecha_contable_descuentos_registrados_cabeza;                
                $dataFila['opciones'] = $opciones;
                               
                $data[] = $dataFila;
            }
          
            $salida = ob_get_clean();
            
            if( !empty($salida) )
                throw new Exception($salida);
                
                $json_data = array(
                    "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                    "recordsTotal" => intval($cantidadBusqueda),  // total number of records
                    "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
                    "data" => $data,   // total data array
                    "sql" => $sql
                );
                
        } catch (Exception $e) {
            
            $json_data = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval("0"),  // total number of records
                "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => array(),   // total data array
                "sql" => $sql,
                "buffer" => error_get_last(),
                "ERRORDATATABLE" => $e->getMessage()
            );
        }
        
        
        echo json_encode($json_data);
    }
    /** end dc 2020/06/30 **/
    
    /** dc 2020/06/30 **/
    public function dtMostrarDescuentosProcesados()
    {
        if( !isset( $_SESSION ) ){
            session_start();
        }
        
        try {
            ob_start();
            
            $recaudaciones = new RecaudacionesModel();
            
            //dato que viene de parte del plugin DataTable
            $requestData = $_REQUEST;
            $searchDataTable   = $requestData['search']['value'];
            
            /** buscar por el usuario que se encuentra logueado */
            $_usuario_logueado = $_SESSION['usuario_usuarios'];
            
            $id_entidad_patronal = $_POST['id_entidad_patronal'];
            
            $columnas1 = " aa.id_descuentos_registrados_cabeza, bb.nombre_entidad_patronal, aa.year_descuentos_registrados_cabeza, aa.mes_descuentos_registrados_cabeza,
                cc.parametro_uno_descuentos_formatos, dd.nombre_descuentos_registrados_archivo, aa.nombre_archivo_descuentos_registrados_cabeza,
                TO_CHAR(aa.fecha_descuentos_registrados_cabeza,'YYYY-MM-DD') fecha_descuentos, aa.fecha_contable_descuentos_registrados_cabeza";
            $tablas1   = " core_descuentos_registrados_cabeza aa
                INNER JOIN core_entidad_patronal bb on bb.id_entidad_patronal = aa.id_entidad_patronal
                INNER JOIN core_descuentos_formatos cc on cc.id_descuentos_formatos = aa.id_descuentos_formatos
                LEFT JOIN core_descuentos_registrados_archivo dd on dd.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza";
            $where1    = " cc.entrada_descuentos_formatos = true
                AND aa.procesado_descuentos_registrados_cabeza = true
                AND aa.erro_descuentos_registrados_cabeza = false
                AND aa.id_entidad_patronal = $id_entidad_patronal ";
            
            /* PARA FILTROS DE CONSULTA */
            
            if( strlen( $searchDataTable ) > 0 )
            {
                $where1 .= " AND ( ";
                $where1 .= " bb.nombre_entidad_patronal ILIKE '%$searchDataTable%' ";
                $where1 .= " OR TO_CHAR(aa.year_descuentos_registrados_cabeza,'9999') ilike '%$searchDataTable%' ";
                $where1 .= " ) ";
                
            }
            
            $rsCantidad    = $recaudaciones->getCantidad("*", $tablas1, $where1);
            $cantidadBusqueda = (int)$rsCantidad[0]->total;
            
            /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
            
            // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
            $columns = array(
                0 => '1',
                1 => '1',
                2 => '1',
                3 => '1',
                4 => '1',
                5 => '1',
                6 => '1',
                7 => '1'
            );
            
            $orderby   = $columns[$requestData['order'][0]['column']];
            $orderdir  = $requestData['order'][0]['dir'];
            $orderdir  = strtoupper($orderdir);
            /**PAGINACION QUE VIEN DESDE DATATABLE**/
            $per_page  = $requestData['length'];
            $offset    = $requestData['start'];
            
            //para validar que consulte todos
            $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
            
            $limit = " ORDER BY $orderby $orderdir LIMIT   $per_page OFFSET '$offset'";
            
            //$sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
            $sql = "";
            
            $resultSet=$recaudaciones->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
            
            /** crear el array data que contiene columnas en plugins **/
            $data = array();
            $dataFila = array();
            $columnIndex = 0;
            foreach ( $resultSet as $res){
                $columnIndex++;
                
                $opciones = ""; //variable donde guardare los datos creados automaticamente
                
             /*   $opciones = '<div class="pull-right ">
                           <span >
                                <a onclick="mostrar_detalle(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span> 
                            <span >
                                <a onclick="mostrar_detalle_modal(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle Modal"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            </div>';*/
                
                $nombretipo_descuentos = "";
                if( $res->parametro_uno_descuentos_formatos == "C")
                {
                    $nombretipo_descuentos  = "CREDITOS";
                    
                    $opciones = '<div class="pull-right ">
                              <span >
                                <a onclick="mostrar_detalle_modal_creditos(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle Modal"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            </div>';
                }else
                {
                    $nombretipo_descuentos  = "APORTES";
                    
                    $opciones = '<div class="pull-right ">
                              <span >
                                <a onclick="mostrar_detalle_modal(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle Modal"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            </div>';
                }
                
                $dataFila['numfila'] = $columnIndex;
                $dataFila['nombre_entidad']  = $res->nombre_entidad_patronal;
                $dataFila['nombre_usuarios'] = $_usuario_logueado;
                $dataFila['anio_descuentos'] = $res->year_descuentos_registrados_cabeza;
                $dataFila['mes_descuentos']  = $res->mes_descuentos_registrados_cabeza;
                $dataFila['nombre_formato']  = $nombretipo_descuentos;
                $dataFila['nombre_archivo']  = $res->nombre_descuentos_registrados_archivo;
                $dataFila['fecha_descuentos']= $res->fecha_descuentos;
                $dataFila['fecha_contable']  = $res->fecha_contable_descuentos_registrados_cabeza;
                $dataFila['opciones'] = $opciones;
                
                $data[] = $dataFila;
            }
            
            $salida = ob_get_clean();
            
            if( !empty($salida) )
                throw new Exception($salida);
                
                $json_data = array(
                    "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                    "recordsTotal" => intval($cantidadBusqueda),  // total number of records
                    "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
                    "data" => $data,   // total data array
                    "sql" => "",//$sql
                );
                
        } catch (Exception $e) {
            
            $json_data = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval("0"),  // total number of records
                "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => array(),   // total data array
                "sql" => $sql,
                "buffer" => error_get_last(),
                "ERRORDATATABLE" => $e->getMessage()
            );
        }
        
        
        echo json_encode($json_data);
    }
    /** end dc 2020/06/30 **/
    
    /** dc 2020/06/30 **/
    public function dtMostrarDescuentosError()
    {
        if( !isset( $_SESSION ) ){
            session_start();
        }
        
        try {
            ob_start();
            
            $recaudaciones = new RecaudacionesModel();
            
            //dato que viene de parte del plugin DataTable
            $requestData = $_REQUEST;
            $searchDataTable   = $requestData['search']['value'];
            
            /** buscar por el usuario que se encuentra logueado */
            $_usuario_logueado = $_SESSION['usuario_usuarios'];
            
            $id_entidad_patronal = $_POST['id_entidad_patronal'];
            
            $columnas1 = " aa.id_descuentos_registrados_cabeza, bb.nombre_entidad_patronal, aa.year_descuentos_registrados_cabeza, aa.mes_descuentos_registrados_cabeza,
                cc.parametro_uno_descuentos_formatos, dd.nombre_descuentos_registrados_archivo, aa.nombre_archivo_descuentos_registrados_cabeza,
                TO_CHAR(aa.fecha_descuentos_registrados_cabeza,'YYYY-MM-DD') fecha_descuentos, aa.fecha_contable_descuentos_registrados_cabeza";
            $tablas1   = " core_descuentos_registrados_cabeza aa
                INNER JOIN core_entidad_patronal bb on bb.id_entidad_patronal = aa.id_entidad_patronal
                INNER JOIN core_descuentos_formatos cc on cc.id_descuentos_formatos = aa.id_descuentos_formatos
                LEFT JOIN core_descuentos_registrados_archivo dd on dd.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza";
            $where1    = " cc.entrada_descuentos_formatos = true
                AND aa.procesado_descuentos_registrados_cabeza = true
                AND aa.erro_descuentos_registrados_cabeza = true
                AND aa.id_entidad_patronal = $id_entidad_patronal ";
            
            /* PARA FILTROS DE CONSULTA */
            
            if( strlen( $searchDataTable ) > 0 )
            {
                $where1 .= " AND ( ";
                $where1 .= " bb.nombre_entidad_patronal ILIKE '%$searchDataTable%' ";
                $where1 .= " OR TO_CHAR(aa.year_descuentos_registrados_cabeza,'9999') ilike '%$searchDataTable%' ";
                $where1 .= " ) ";
                
            }
            
            $rsCantidad    = $recaudaciones->getCantidad("*", $tablas1, $where1);
            $cantidadBusqueda = (int)$rsCantidad[0]->total;
            
            /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
            
            // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
            $columns = array(
                0 => '1',
                1 => '1',
                2 => '1',
                3 => '1',
                4 => '1',
                5 => '1',
                6 => '1',
                7 => '1'
            );
            
            $orderby   = $columns[$requestData['order'][0]['column']];
            $orderdir  = $requestData['order'][0]['dir'];
            $orderdir  = strtoupper($orderdir);
            /**PAGINACION QUE VIEN DESDE DATATABLE**/
            $per_page  = $requestData['length'];
            $offset    = $requestData['start'];
            
            //para validar que consulte todos
            $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
            
            $limit = " ORDER BY $orderby $orderdir LIMIT   $per_page OFFSET '$offset'";
            
            //$sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
            $sql = "";
            
            $resultSet=$recaudaciones->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
            
            /** crear el array data que contiene columnas en plugins **/
            $data = array();
            $dataFila = array();
            $columnIndex = 0;
            foreach ( $resultSet as $res){
                $columnIndex++;
                
                $opciones = ""; //variable donde guardare los datos creados automaticamente
                
                //se comento esos archivos no necesian ser descargados solo mostrar detalles
//                 $opciones = '<div class="pull-right ">
//                             <span >
//                                 <a onclick="mostrar_detalle(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
// 	                           </a>
//                             </span>
//                             <span >
//                                 <a onclick="mostrar_detalle_modal(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle Modal"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
// 	                           </a>
//                             </span>
              
//                             </div>';
                              
                $nombretipo_descuentos = "";
                if( $res->parametro_uno_descuentos_formatos == "C")
                {
                    $nombretipo_descuentos  = "CREDITOS";
                    
                    $opciones = '<div class="pull-right ">
                              <span >
                                <a onclick="mostrar_detalle_modal_creditos(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle Modal"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            </div>';
                }else
                {
                    $nombretipo_descuentos  = "APORTES";
                    
                    $opciones = '<div class="pull-right ">
                              <span >
                                <a onclick="mostrar_detalle_modal(this)" id="" data-id_descuentos_cabeza="'.$res->id_descuentos_registrados_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle Modal"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            </div>';
                }
                
                $dataFila['numfila'] = $columnIndex;
                $dataFila['nombre_entidad']  = $res->nombre_entidad_patronal;
                $dataFila['nombre_usuarios'] = $_usuario_logueado;
                $dataFila['anio_descuentos'] = $res->year_descuentos_registrados_cabeza;
                $dataFila['mes_descuentos']  = $res->mes_descuentos_registrados_cabeza;
                $dataFila['nombre_formato']  = $nombretipo_descuentos;
                $dataFila['nombre_archivo']  = $res->nombre_descuentos_registrados_archivo;
                $dataFila['fecha_descuentos']= $res->fecha_descuentos;
                $dataFila['fecha_contable']  = $res->fecha_contable_descuentos_registrados_cabeza;
                $dataFila['opciones'] = $opciones;
                
                $data[] = $dataFila;
            }
            
            $salida = ob_get_clean();
            
            if( !empty($salida) )
                throw new Exception($salida);
                
                $json_data = array(
                    "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                    "recordsTotal" => intval($cantidadBusqueda),  // total number of records
                    "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
                    "data" => $data,   // total data array
                    "sql" => "",//$sql
                );
                
        } catch (Exception $e) {
            
            $json_data = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval("0"),  // total number of records
                "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => array(),   // total data array
                "sql" => $sql,
                "buffer" => error_get_last(),
                "ERRORDATATABLE" => $e->getMessage()
            );
        }
        
        
        echo json_encode($json_data);
    }
    /** end dc 2020/06/30 **/
    
    /** dc 2020/06/30 **/
    public function mostrarArchivoTxt()
    {
        $recaudaciones = new RecaudacionesModel();
        
        try {
            
            if( !isset( $_SESSION ) )
            {
                session_start();
            }
            
            $id_descuentos_cabeza  = $_POST['id_descuentos_cabeza'];
            
            if( error_get_last() ){
                throw new Exception( "Datos no recibidos" );
            }
            
            /** VALIDACION PARA SABER SI YA EXISTE EL ARCHIVO GENERADO **/
            $colvalidacion = " ubicacion_descuentos_registrados_archivo, nombre_descuentos_registrados_archivo";
            $tabvalidacion = " public.core_descuentos_registrados_archivo";
            $twhevalidacion = " id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
            $rsValidacion  = $recaudaciones->getCondicionesSinOrden($colvalidacion, $tabvalidacion, $twhevalidacion, "LIMIT 1");
            
            if( !empty( $rsValidacion ) )
            {
                
                $ubicacionFile = $rsValidacion[0]->ubicacion_descuentos_registrados_archivo;
                $nombreFile    = $rsValidacion[0]->nombre_descuentos_registrados_archivo;
                
                $archivoDescargar   = $ubicacionFile."/".$nombreFile.".txt";
                
                header('Content-type: text/plain');
                header("Content-disposition: attachment; filename=$nombreFile.txt");
                ob_clean();
                flush();
                readfile($archivoDescargar);
                exit;
            }
                        
            /* estructurar el archivo */
            //$datahead	= "DATOS NO SE ENCUENTRAN CARGADOS EN EL SISTEMA".PHP_EOL;
            $datahead	= "DATOS NO SE ENCUENTRAN CARGADOS EN EL SISTEMA";
            
            /*** buscar otro metodo para archivos grandes evitar acumulacion memoria al generar todo en una variable */            
           
            // Define headers
            header('Content-type: text/plain');
            header("Content-disposition: attachment; filename=ArchivoSubir.txt");
            ob_clean();
            flush();
            print $datahead;
            exit;
            
        } catch (Exception $e) {
            echo '<message>'.$e->getMessage().' <message>';
            exit();
        }
    }
    /** end dc 2020/06/30 **/
    
    
    
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

    
    /** STEVEN */
    
    public function dtMostrarDetallesModal()
    {
        if( !isset( $_SESSION ) ){
            session_start();
        }
        
        try {
            ob_start();
            
            $recaudaciones = new RecaudacionesModel();
            
            //dato que viene de parte del plugin DataTable
            $requestData = $_REQUEST;
            $searchDataTable   = $requestData['search']['value'];
            
            /** buscar por el usuario que se encuentra logueado */
            $id_descuentos_registrados_cabeza = $_POST['id_cabeza_descuentos'];
            
          $columnas1 = "b.nombre_entidad_patronal,
                        a.year_descuentos_registrados_detalle_aportes,
                        a.mes_descuentos_registrados_detalle_aportes,
                        c.cedula_participes,
                        c.apellido_participes,
                        c.nombre_participes,
                        a.aporte_personal_descuentos_registrados_detalle_aportes,
                        a.aporte_patronal_descuentos_registrados_detalle_aportes,
                        a.rmu_descuentos_registrados_detalle_aportes,
                        a.liquido_descuentos_registrados_detalle_aportes,
                        a.multas_descuentos_registrados_detalle_aportes,
                        a.antiguedad_descuentos_registrados_detalle_aportes,
                        a.procesado_descuentos_registrados_detalle_aportes";
          $tablas1   = "core_descuentos_registrados_detalle_aportes a 
                        inner join core_entidad_patronal b on a.id_entidad_patronal = b.id_entidad_patronal
                        inner join core_participes c on a.id_participes = c.id_participes";
          $where1    = "a.id_descuentos_registrados_cabeza = $id_descuentos_registrados_cabeza ";
          
          
            /* PARA FILTROS DE CONSULTA */
            
            if( strlen( $searchDataTable ) > 0 )
            {
                $where1 .= " AND ( ";
                $where1 .= " c.cedula_participes ILIKE '%$searchDataTable%' ";
                $where1 .= " ) ";
                
            }
            
            $rsCantidad    = $recaudaciones->getCantidad("*", $tablas1, $where1);
            $cantidadBusqueda = (int)$rsCantidad[0]->total;
            
            /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
            
            // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
            $columns = array(
                0 => '1',
                1 => '1',
                2 => '1',
                3 => '1',
                4 => '1',
                5 => '1',
                6 => '1',
                7 => '1',
                8 => '1',
                9 => '1',
                10 => '1',
                11 => '1',
                12 => '1'
            );
            
            $orderby   = $columns[$requestData['order'][0]['column']];
            $orderdir  = $requestData['order'][0]['dir'];
            $orderdir  = strtoupper($orderdir);
            /**PAGINACION QUE VIEN DESDE DATATABLE**/
            $per_page  = $requestData['length'];
            $offset    = $requestData['start'];
            
            //para validar que consulte todos
            $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
            
            $limit = " ORDER BY $orderby $orderdir LIMIT   $per_page OFFSET '$offset'";
            
            $sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
            //$sql = "";
            
            $resultSet=$recaudaciones->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
            
            /** crear el array data que contiene columnas en plugins **/
            $data = array();
            $dataFila = array();
            $columnIndex = 0;
            foreach ( $resultSet as $res){
                $columnIndex++;
                
                
                
                
                $dataFila['numfila'] = $columnIndex;
                $dataFila['nombre_entidad']  = $res->nombre_entidad_patronal;
                $dataFila['anio_descuentos'] = $res->year_descuentos_registrados_detalle_aportes;
                $dataFila['mes_descuentos']  = $res->mes_descuentos_registrados_detalle_aportes;
                $dataFila['cedula_participe']  = $res->cedula_participes;
                $dataFila['participe']  = $res->apellido_participes.' '.$res->nombre_participes;
                $dataFila['aporte_personal']= $res->aporte_personal_descuentos_registrados_detalle_aportes;
                $dataFila['aporte_patronal']  = $res->aporte_patronal_descuentos_registrados_detalle_aportes;
                $dataFila['rmu']  = $res->rmu_descuentos_registrados_detalle_aportes;
                $dataFila['liquido']  = $res->liquido_descuentos_registrados_detalle_aportes;
              
            
                $data[] = $dataFila;
            }
            
            $salida = ob_get_clean();
            
            if( !empty($salida) )
                throw new Exception($salida);
                
                $json_data = array(
                    "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                    "recordsTotal" => intval($cantidadBusqueda),  // total number of records
                    "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
                    "data" => $data,   // total data array
                    "sql" => $sql
                );
                
        } catch (Exception $e) {
            
            $json_data = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval("0"),  // total number of records
                "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => array(),   // total data array
                "sql" => $sql,
                "buffer" => error_get_last(),
                "ERRORDATATABLE" => $e->getMessage()
            );
        }
        
        
        echo json_encode($json_data);
    }
    public function dtMostrarDetallesCreditosModal()
    {
        if( !isset( $_SESSION ) ){
            session_start();
        }
        
        try {
            ob_start();
            
            $recaudaciones = new RecaudacionesModel();
            
            //dato que viene de parte del plugin DataTable
            $requestData = $_REQUEST;
            $searchDataTable   = $requestData['search']['value'];
            
            /** buscar por el usuario que se encuentra logueado */
            $id_descuentos_registrados_cabeza = $_POST['id_cabeza_descuentos'];
            
            $columnas1 = "b.nombre_entidad_patronal,
            a.year_descuentos_registrados_detalle_creditos,
            a.mes_descuentos_registrados_detalle_creditos,
            c.cedula_participes,
            c.apellido_participes,
            c.nombre_participes,
            a.cuota_descuentos_registrados_detalle_creditos,
            a.monto_descuentos_registrados_detalle_creditos,
            a.mora_descuentos_registrados_detalle_creditos,
            a.plazo_descuentos_registrados_detalle_creditos,
            a.saldo_descuentos_registrados_detalle_creditos,
            a.valor_usuario_descuentos_registrados_detalle_creditos";
            $tablas1   = "core_descuentos_registrados_detalle_creditos a
            inner join core_entidad_patronal b on a.id_entidad_patronal = b.id_entidad_patronal 
            inner join core_participes c on a.id_participes = c.id_participes";
            $where1    = "a.id_descuentos_registrados_cabeza = $id_descuentos_registrados_cabeza ";
            
            /* PARA FILTROS DE CONSULTA */
            
            if( strlen( $searchDataTable ) > 0 )
            {
                $where1 .= " AND ( ";
                $where1 .= " c.cedula_participes ILIKE '%$searchDataTable%' ";
                $where1 .= " ) ";
                
            }
            
            $rsCantidad    = $recaudaciones->getCantidad("*", $tablas1, $where1);
            $cantidadBusqueda = (int)$rsCantidad[0]->total;
            
            /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
            
            // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
            $columns = array(
                0 => '1',
                1 => '1',
                2 => '1',
                3 => '1',
                4 => '1',
                5 => '1',
                6 => '1',
                7 => '1'
                
                
            );
            
            $orderby   = $columns[$requestData['order'][0]['column']];
            $orderdir  = $requestData['order'][0]['dir'];
            $orderdir  = strtoupper($orderdir);
            /**PAGINACION QUE VIEN DESDE DATATABLE**/
            $per_page  = $requestData['length'];
            $offset    = $requestData['start'];
            
            //para validar que consulte todos
            $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
            
            $limit = " ORDER BY $orderby $orderdir LIMIT   $per_page OFFSET '$offset'";
            
            $sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
            //$sql = "";
            
            $resultSet=$recaudaciones->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
            
            /** crear el array data que contiene columnas en plugins **/
            $data = array();
            $dataFila = array();
            $columnIndex = 0;
            foreach ( $resultSet as $res){
                $columnIndex++;
                
          
                
                
                $dataFila['numfila'] = $columnIndex;
                $dataFila['nombre_entidad']  = $res->nombre_entidad_patronal;
                $dataFila['anio_descuentos'] = $res->year_descuentos_registrados_detalle_creditos;
                $dataFila['mes_descuentos']  = $res->mes_descuentos_registrados_detalle_creditos;
                $dataFila['cedula_participe']  = $res->cedula_participes;
                $dataFila['participe']  = $res->apellido_participes.' '.$res->nombre_participes;
                $dataFila['cuota']= $res->cuota_descuentos_registrados_detalle_creditos;
                $dataFila['mora']  = $res->mora_descuentos_registrados_detalle_creditos;
                
                $data[] = $dataFila;
            }
            
            $salida = ob_get_clean();
            
            if( !empty($salida) )
                throw new Exception($salida);
                
                $json_data = array(
                    "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                    "recordsTotal" => intval($cantidadBusqueda),  // total number of records
                    "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
                    "data" => $data,   // total data array
                    "sql" => $sql
                );
                
        } catch (Exception $e) {
            
            $json_data = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval("0"),  // total number of records
                "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => array(),   // total data array
                "sql" => $sql,
                "buffer" => error_get_last(),
                "ERRORDATATABLE" => $e->getMessage()
            );
        }
        
        
        echo json_encode($json_data);
    }
    
  
}

?>