<?php

class ProcesosMayorizacionController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
     	$estados=new EstadoModel();     	
					
     	$resultEdit = "";
	
		session_start();
        
	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "Estados";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $estados->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
			    
			    $queryModulos = "SELECT * FROM modulos WHERE 1=1";
			    $rsModulos = $estados->enviaquery($queryModulos);
			    
				
				
				$this->view_Contable("ProcesosMayorizacion",array(
				    "resultEdit" =>$resultEdit,"rsModulos"=>$rsModulos
			
				));
		
				
				
			}
			else
			{
			    $this->view_Contable("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Grupos"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	//para consultas con js
	//dc 2019-07-30
	public function consultaTipoProcesos(){
	    
	    $modulos = new ModulosModel();
	    
	    $_id_modulos = (isset($_POST['id_modulos'])) ? $_POST['id_modulos'] : 0;
	    
	    $columnas = "id_tipo_procesos, nombre_tipo_procesos";
	    $tablas = "core_tipo_procesos";
	    $where = " diarios_tipo_procesos = 't' AND id_modulos = $_id_modulos ";
	    $id = "id_modulos";
	    
	    $rsTipoProceso = $modulos->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $cantidad = count($rsTipoProceso);
	    
	    echo json_encode(array('cantidad'=>$cantidad, 'data'=>$rsTipoProceso));
	}
	
	
	/***
	 * dc 2019-07-09
	 * mod: Contabilidad
	 * desc: lista los diario tipo detalle
	 */
	public function detallesDiarioTipo(){
	    
	    $idTipoProcesos = (isset($_POST['id_tipo_procesos'])) ? $_POST['id_tipo_procesos'] : "";
	    
	    if(empty($idTipoProcesos)){
	        echo 'Variables no enviadas';
	        return;
	    }
	    //tipos de procesos si se aumentan agregar mas metodos
	    switch ($idTipoProcesos){
	        case "1":
	            
	        break;
	        case "2":
	            
	        break;   
	            
	    }
	    
	    $page = (isset($_REQUEST['page']))?isset($_REQUEST['page']):1;	    
	    $Participes = new ParticipesModel();
	 	    
	    $columnas = "cdtd.id_diario_tipo_detalle,
                    pc.id_plan_cuentas,pc.codigo_plan_cuentas,
                    pc.nombre_plan_cuentas, 
                    0.00 \"debito\", 0.00 \"credito\"";
	    
	    $tablas = "core_diario_tipo_detalle cdtd
        	    inner join core_diario_tipo_cabeza cdtc
        	    on cdtc.id_diario_tipo_cabeza = cdtd.id_diario_tipo_cabeza
        	    inner join plan_cuentas pc
        	    on pc.id_plan_cuentas = cdtd.id_plan_cuentas";
	    
	    $where = " cdtc.id_tipo_procesos = '$idTipoProcesos' ";
	    
	    $id = "cdtd.creado";
	    
	    //para obtener cantidad
	    $rsResultado = $Participes->getCantidad("1", $tablas, $where, $id);
	    
	    $cantidad = 0;
	    $html = "";
	    $per_page = 20; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    if(!is_null($rsResultado) && !empty($rsResultado) && count($rsResultado)>0){
	        $cantidad = $rsResultado[0]->total;
	    }
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet = $Participes->getCondicionesPag( $columnas, $tablas, $where, $id, $limit);
	    
	    $tpages = ceil($cantidad/$per_page);
	    $html= "";
	    if( $cantidad > 0 ){	        
	        
	        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CODIGO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">NOMBRE</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">DEBITO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CREDITO</th>';
	        //$html.='<th style="text-align: left;  font-size: 12px;"></th>';	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $i=0;
	        
	        foreach ($resultSet as $res){
	            
	            $i++;
	            $html.='<tr>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->codigo_plan_cuentas.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_plan_cuentas.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->debito.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->credito.'</td>';
	            //$html.='<td style="color:#000000;font-size:80%;"><span class="pull-right">';
	            //$html.='<a title="Editar Proveedores" href="index.php?controller=ProcesosMayorizacion&action=index&codido='.$res->id_diario_tipo_detalle.'" class="btn-sm btn-warning" style="font-size:65%;"data-toggle="tooltip" >';
	            //$html.='<i class="fa  fa-edit" aria-hidden="true" ></i></a></td>';
	            $html.='</tr>';	            
	           
	        }
	        
	        
	        $html.='</tbody>';
	        $html.='</table>';
	        
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate("index.php", $page, $tpages, $adjacents,"").'';
	        $html.='</div>';
	        
	        
	        
	    }else{
	        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CODIGO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">NOMBRE</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">DEBITO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CREDITO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='</table>';
	    }
	    
	    //array de datos
	    $respuesta = array();
	    $respuesta['tabladatos'] = $html;
	    $respuesta['valores'] = array('cantidad'=>$cantidad);
	    echo json_encode($respuesta);
	    
	}
	
	/***
	 * dc 2019-07-09
	 * mod: Contabilidad
	 * desc: lista los diario tipo detalle
	 */
	function generaDiarioActivos($idTipoProceso=1,$paramAnio=2019,$paramMes=7){
	  	    
	    $Activos = new ActivosFijosModel();
	    $columnaMes = "1";
	    $arrayColDepreciacion = array("enero_depreciacion","febrero_depreciacion","marzo_depreciacion","abril_depreciacion","mayo_depreciacion",
	        "junio_depreciacion","julio_depreciacion","agosto_depreciacion","septiembre_depreciacion","octubre_depreciacion","noviembre_depreciacion",
	        "diciembre_depreciacion");	
	    //obtener columna de busqueda
	    for($i=0; $i<sizeof($arrayColDepreciacion);$i++){
	        if(($i+1) == $paramMes){
	            $columnaMes = $arrayColDepreciacion[$i];
	        }
	    }
	    
	    //traer activos para depreciacion 
	    $columnas ="aaf.id_activos_fijos,aaf.id_tipo_activos_fijos,aaf.codigo_activos_fijos,aaf.nombre_activos_fijos,
                     aaf.valor_activos_fijos, ad.id_depreciacion, $columnaMes \"valor_depreciacion\"";
	    $tablas ="act_activos_fijos aaf
        	    INNER JOIN act_depreciacion ad
        	    ON aaf.id_activos_fijos = ad.id_activos_fijos";
	    $where = " 1=1
	           AND ad.anio_depreciacion = $paramAnio";
        $id="aaf.id_tipo_activos_fijos";
        
        $rsActivos = $Activos->getCondiciones($columnas, $tablas, $where, $id);
        $arrayTipoActivos = array();
        $_id_tipo_activo_fijo = 0;
        $_sumatoria_valor = 0;
        //recorrer el array 
        foreach ($rsActivos as $res){
            echo 'nevo valor';
            //validar de acuerdo a su tipo de activo
            if( $_id_tipo_activo_fijo == $res->id_tipo_activos_fijos ){
                $_id_tipo_activo_fijo = $res->id_tipo_activos_fijos;
                $_sumatoria_valor = 0;
                echo 'nevo valor';
            }else{
                $_sumatoria_valor += $res->valor_depreciacion;
                echo $_sumatoria_valor;
            }            
            
        }
        
        die();
        
        print_r($rsActivos) ; die();
	    
	  
	    
	    
	    $query = "SELECT id_tipo_activos_fijos,nombre_tipo_activos_fijos FROM act_activos_fijos WHERE 1=1 ORDER BY id_activos_fijos";
	    $rsTipoActivos = $Activos->enviaquery($query);
	    if(!empty($rsTipoActivos)){
	        $idTipoActivo = 0;
	        foreach ( $rsTipoActivos as $res ){
	            $idTipoActivo = $res->id_tipo_activos_fijos;
	            $query="";
	        }
	    }
	    
	    //buscarTipoActivo
	    $queryConsulta = "SELECT id_tipo_activos_fijos,nombre_tipo_activos_fijos FROM tipo_activos_fijos; ";
	    echo $columnaMes; die();
	       
	   
	    
	}
	
	
	public function borrarId()
	{
	    
	    session_start();
	    $grupos=new GruposModel();
	    $nombre_controladores = "Grupos";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $grupos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer))
	    {
	        if(isset($_GET["id_grupos"]))
	        {
	            $id_grupos=(int)$_GET["id_grupos"];
	            
	            
	            
	            $grupos->deleteBy(" id_grupos",$id_grupos);
	            
	        }
	        
	        $this->redirect("Grupos", "index");
	        
	        
	    }
	    else
	    {
	        $this->view_Inventario("Error",array(
	            "resultado"=>"No tiene Permisos de Borrar Grupos"
	            
	        ));
	    }
	    
	}
	


	
	
	/**
	 * mod: admin
	 * title: cargar tablas de BD
	 * ajax: si
	 * dc:2019-04-15
	 */	
	public function cargaTablasBd(){
	    
	    $estados = null;
	    $estados = new EstadoModel();
	    
	    $query = " SELECT table_name FROM information_schema.tables 
            WHERE table_catalog = 'rp_capremci' AND table_schema = 'public' AND table_type = 'BASE TABLE'
            ORDER BY table_name";
	    
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
	
	
}
?>