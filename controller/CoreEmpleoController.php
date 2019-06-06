<?php

class CoreEmpleoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		$empleo = new CoreEmpleoModel();
				
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "CoreEmpleo";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $empleo->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Empleo"
		        
		    ));
		    exit();
		}		    
			
		$rsEmpleo = $empleo->getBy(" 1 = 1 ");
		
				
		$this->view_Core("CoreEmpleo",array(
		    "resultSet"=>$rsEmpleo
	
		));
			
	
	}
	
	
	public function InsertaEmpleo(){
			
		session_start();
		
		$empleo = new CoreEmpleoModel();
		
		$nombre_controladores = "CoreEmpleo";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $empleo->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer)){	
		    
		    $_nombre_profesion = (isset($_POST["nombre_profesion"])) ? $_POST["nombre_profesion"] : "";
		    $_id_empleo = (isset($_POST["id_empleo"])) ? $_POST["id_empleo"] : 0 ;
		    
		    /*si es insertado enviar en cero el id_banco a la funcion*/							
			$funcion = "ins_core_empleo";
			$respuesta = 0 ;
			$mensaje = ""; 
			
			if($_id_empleo == 0){
			    
			    $parametros = " '$_nombre_profesion'";
			    $empleo->setFuncion($funcion);
			    $empleo->setParametros($parametros);
			    $resultado = $empleo->llamafuncion();
			    
			    if(!empty($resultado) && count($resultado) > 0 ){
			        
			        foreach ( $resultado[0] as $k => $v){
			            
			            $respuesta = $v;
			        }
			        
			        $mensaje = "Empleo Ingresado Correctamente";
			        
			    }
			}elseif ($_id_empleo > 0){
			    
			    $parametros = " '$_nombre_profesion'";
			    $empleo->setFuncion($funcion);
			    $empleo->setParametros($parametros);
			    $resultado = $empleo->llamafuncion();
			    
			    if(!empty($resultado) && count($resultado) > 0 ){
			        
			        foreach ( $resultado[0] as $k => $v){
			            
			            $respuesta = $v;
			        }
			        
			        $mensaje = "Empleo Actualizado Correctamente";
			        
			    }
			}
			
			
			if($respuesta > 0 ){
			    
			    echo json_encode(array('respuesta'=>$respuesta,'mensaje'=>$mensaje));
			    exit();
			}
			
			echo "Error al Ingresar Empleo";
			exit();
			
		}
		else
		{
		    $this->view_Inventario("Error",array(
					"resultado"=>"No tiene Permisos de Insertar Empleo"
		
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
	public function editEmpleo(){
	    
	    session_start();
	    $empleo = new CoreEmpleoModel();
	    $nombre_controladores = "CoreEmpleo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $empleo->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    	     
	    if (!empty($resultPer))
	    {
	        
	        
	        if(isset($_POST["id_empleo"])){
	            
	            $id_empleo = (int)$_POST["id_empleo"];
	            
	            $query = "SELECT * FROM core_empleo WHERE id_empleo = $id_empleo";

	            $resultado  = $empleo->enviaquery($query);	            
	           
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
	public function delEmpleo(){
	    
	    session_start();
	    $empleo = new CoreEmpleoModel();
	    $nombre_controladores = "CoreEmpleo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $empleo->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer)){	        
	        
	        if(isset($_POST["id_empleo"])){
	            
	            $id_empleo = (int)$_POST["id_empleo"];
	            
	            $resultado  = $empleo->eliminarBy(" id_empleo ",$id_empleo);
	           
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
	
	
	public function consultaEmpleo(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $empleo = new CoreEmpleoModel();
	    
	    $where_to="";
	    $columnas  = " id_empleo, nombre_profesion ";
	    
	    $tablas    = "public.core_empleo";
	    
	    $where     = " 1 = 1";
	    
	    $id        = "core_empleo.nombre_profesion";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';	    
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND nombre_profesion ILIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$empleo->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$empleo->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);	        
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_empleo' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 15px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Empleo</th>';
	           
	            
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
	                $html.='<td style="font-size: 14px;">'.$res->nombre_profesion.'</td>';
	                
	                
	               
	                /*comentario up */
	                
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editEmpleo('.$res->id_empleo.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                    $html.='<td style="font-size: 18px;">
                            <a onclick="delEmpleo('.$res->id_empleo.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
	                    
	               
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaEmpleo").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay empleos registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	       
	    }
	    
	     
	}
	
	
	
}
?>