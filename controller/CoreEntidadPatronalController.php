<?php

class CoreEntidadPatronalController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		$entidad = new CoreEntidadPatronalModel();
				
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "CoreEntidadPatronal";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $entidad->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Empleo"
		        
		    ));
		    exit();
		}		    
			
		$rsEntidad = $entidad->getBy(" 1 = 1 ");
		
				
		$this->view_Core("CoreEntidadPatronal",array(
		    "resultSet"=>$rsEntidad
	
		));
			
	
	}
	
	
	public function InsertaEntidad(){
			
		session_start();
		
		$entidad = new CoreEntidadPatronalModel();
		
		$nombre_controladores = "CoreEntidadPatronal";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $entidad->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer)){	
		    
		    $_nombre_entidad_patronal = (isset($_POST["nombre_entidad_patronal"])) ? $_POST["nombre_entidad_patronal"] : "";
		    $_ruc_entidad_patronal = (isset($_POST["ruc_entidad_patronal"])) ? $_POST["ruc_entidad_patronal"] : "";
		    $_codigo_entidad_patronal = (isset($_POST["codigo_entidad_patronal"])) ? $_POST["codigo_entidad_patronal"] : "";
		    $_tipo_entidad_patronal = (isset($_POST["tipo_entidad_patronal"])) ? $_POST["tipo_entidad_patronal"] : "";
		    $_acronimo_entidad_patronal = (isset($_POST["acronimo_entidad_patronal"])) ? $_POST["acronimo_entidad_patronal"] : "";
		    $_direccion_entidad_patronal = (isset($_POST["direccion_entidad_patronal"])) ? $_POST["direccion_entidad_patronal"] : "";
		    $_id_entidad_patronal = (isset($_POST["id_entidad_patronal"])) ? $_POST["id_entidad_patronal"] : 0 ;
		    
		    /*si es insertado enviar en cero el id_banco a la funcion*/							
			$funcion = "ins_core_entidad_patronal";
			$respuesta = 0 ;
			$mensaje = ""; 
			
			if($_id_entidad_patronal == 0){
			    
			    $parametros = " '$_nombre_entidad_patronal', '$_ruc_entidad_patronal', '$_codigo_entidad_patronal', '$_tipo_entidad_patronal', '$_acronimo_entidad_patronal', '$_direccion_entidad_patronal'";
			    $entidad->setFuncion($funcion);
			    $entidad->setParametros($parametros);
			    $resultado = $entidad->llamafuncion();
			    
			    if(!empty($resultado) && count($resultado) > 0 ){
			        
			        foreach ( $resultado[0] as $k => $v){
			            
			            $respuesta = $v;
			        }
			        
			        $mensaje = "Entidad Ingresado Correctamente";
			        
			    }
			}elseif ($_id_entidad_patronal > 0){
			    
			    $parametros = " '$_nombre_entidad_patronal', '$_ruc_entidad_patronal', '$_codigo_entidad_patronal', '$_tipo_entidad_patronal', '$_acronimo_entidad_patronal', '$_direccion_entidad_patronal'";
			    $entidad->setFuncion($funcion);
			    $entidad->setParametros($parametros);
			    $resultado = $entidad->llamafuncion();
			    
			    if(!empty($resultado) && count($resultado) > 0 ){
			        
			        foreach ( $resultado[0] as $k => $v){
			            
			            $respuesta = $v;
			        }
			        
			        $mensaje = "Entidad Actualizada Correctamente";
			        
			    }
			}
			
			
			if($respuesta > 0 ){
			    
			    echo json_encode(array('respuesta'=>$respuesta,'mensaje'=>$mensaje));
			    exit();
			}
			
			echo "Error al Ingresar Entidad";
			exit();
			
		}
		else
		{
		    $this->view_Inventario("Error",array(
					"resultado"=>"No tiene Permisos de Insertar Entidad"
		
			));
		
		
		}
		
	}
	
	
	
	public function paginate($reload, $page, $tpages, $adjacents, $funcion = "") {
	    
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
	
	/***
	 * return: json
	 * title: editBancos
	 * fcha: 2019-04-22
	 */
	public function editEntidad(){
	    
	    session_start();
	    $entidad = new CoreEntidadPatronalModel();
	    $nombre_controladores = "CoreEntidadPatronal";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $entidad->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    	     
	    if (!empty($resultPer))
	    {
	        
	        
	        if(isset($_POST["id_entidad_patronal"])){
	            
	            $_id_entidad_patronal = (int)$_POST["id_entidad_patronal"];
	            
	            $query = "SELECT * FROM core_entidad_patronal WHERE id_entidad_patronal = $_id_entidad_patronal";

	            $resultado  = $entidad->enviaquery($query);	            
	           
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
	 * title: delBancos
	 * fcha: 2019-04-22
	 */
	public function delEntidad(){
	    
	    session_start();
	    $entidad = new CoreEntidadPatronalModel();
	    $nombre_controladores = "CoreEntidadPatronal";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $entidad->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer)){	        
	        
	        if(isset($_POST["id_entidad_patronal"])){
	            
	            $id_entidad = (int)$_POST["id_entidad_patronal"];
	            
	            $resultado  = $entidad->eliminarBy(" id_entidad_patronal ",$id_entidad);
	           
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
	
	
	public function consultaEntidad(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $entidad = new CoreEntidadPatronalModel();
	    
	    
	    $where_to="";
	    $columnas  = " id_entidad_patronal, nombre_entidad_patronal, ruc_entidad_patronal, codigo_entidad_patronal, tipo_entidad_patronal, acronimo_entidad_patronal, direccion_entidad_patronal ";
	    
	    $tablas    = "public.core_entidad_patronal";
	    
	    $where     = " 1 = 1";
	    
	    $id        = "core_entidad_patronal.nombre_entidad_patronal";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';	    
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND nombre_entidad_patronal ILIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$entidad->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$entidad->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);	        
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_entidad' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 15px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Entidad</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Ruc</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Código</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Tipo</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Acrónimo</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Dirección</th>';
	           
	            
	            /*para administracion definir administrador MenuOperaciones Edit - Eliminar*/
	                
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
	                $html.='<td style="font-size: 14px;">'.$res->ruc_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->codigo_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->tipo_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->acronimo_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->direccion_entidad_patronal.'</td>';
	                
	                
	               
	                /*comentario up */
	                
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editEntidad('.$res->id_entidad_patronal.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                    $html.='<td style="font-size: 18px;">
                            <a onclick="delEntidad('.$res->id_entidad_patronal.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
	                    
	               
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaEntidad").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay entidades registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	       
	    }
	    
	     
	}
	
	
	
}
?>