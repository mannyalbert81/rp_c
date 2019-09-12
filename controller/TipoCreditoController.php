<?php

class TipoCreditoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    $tipo_credito_renovacion = new TipoCreditoRenovacionModel();
	    
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "TipoCredito";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $tipo_credito_renovacion->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Tipo Credito"
		        
		    ));
		    exit();
		}		    
			
		$rsTipoCredito = $tipo_credito_renovacion->getBy(" 1 = 1 ");
		
				
		$this->view_Credito("TipoCredito",array(
		    "resultSet"=>$rsTipoCredito
	
		));
			
	
	}
	

	
	public function InsertaTipoCredito(){
	    
	    session_start();
		
		$tipo_credito_renovacion = new TipoCreditoRenovacionModel();
		
		$nombre_controladores = "TipoCredito";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $tipo_credito_renovacion->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer)){	
		    
		    $_id_tipo_creditos_renovacion = (isset($_POST["id_tipo_creditos_renovacion"])) ? $_POST["id_tipo_creditos_renovacion"] : "0";
		    $_id_tipo_creditos = (isset($_POST["id_tipo_creditos"])) ? $_POST["id_tipo_creditos"] : "0";
		    $_id_tipo_creditos_a_renovar = (isset($_POST["id_tipo_creditos_a_renovar"])) ? $_POST["id_tipo_creditos_a_renovar"] : "0";
		    $_id_estado = (isset($_POST["id_estado"])) ? $_POST["id_estado"] : 0 ;
		    

			$funcion = "ins_core_tipo_creditos_renovacion";
			$respuesta = 0 ;
			$mensaje = ""; 
			
	
			
			if($_id_tipo_creditos_renovacion == 0){
			    
			    $parametros = "'$_id_tipo_creditos','$_id_tipo_creditos_a_renovar','$_id_estado',$_id_tipo_creditos_renovacion";
			    $tipo_credito_renovacion->setFuncion($funcion);
			    $tipo_credito_renovacion->setParametros($parametros);
			    $resultado = $tipo_credito_renovacion->llamafuncionPG();
			    
			    if(is_int((int)$resultado[0])){
			        $respuesta = $resultado[0];
			        $mensaje = "Tipo Credito Renovacion Ingresado Correctamente";
			    }	
			    
			
			    
			}elseif ($_id_tipo_creditos_renovacion > 0){
			    
			    $parametros = "'$_id_tipo_creditos','$_id_tipo_creditos_a_renovar','$_id_estado',$_id_tipo_creditos_renovacion";
			    $tipo_credito_renovacion->setFuncion($funcion);
			    $tipo_credito_renovacion->setParametros($parametros);
			    $resultado = $tipo_credito_renovacion->llamafuncionPG();
			    
			    if(is_int((int)$resultado[0])){
			        $respuesta = $resultado[0];
			        $mensaje = "Tipo Credito Renovacion Actualizado Correctamente";
			    }	
			    
			    
			}
			
	
			if(is_int((int)$respuesta)){
			    
			    echo json_encode(array('respuesta'=>$respuesta,'mensaje'=>$mensaje));
			    exit();
			}
			
			echo "Error al Ingresar Tipo Credito Renovacion";
			exit();
			
		}
		else
		{
		    $this->view_Inventario("Error",array(
					"resultado"=>"No tiene Permisos de Insertar Tipo Credito Renovacion"
		
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
	

	public function editTipoCredito(){
	    
	    session_start();
	    $tipo_credito_renovacion = new TipoCreditoRenovacionModel();
	    $nombre_controladores = "TipoCredito";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $tipo_credito_renovacion->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    	     
	    if (!empty($resultPer))
	    {
	        
	        
	        if(isset($_POST["id_tipo_creditos_renovacion"])){
	            
	            $id_tipo_creditos_renovacion = (int)$_POST["id_tipo_creditos_renovacion"];
	            
	            $query = "SELECT * FROM core_tipo_creditos_renovacion WHERE id_tipo_creditos_renovacion = $id_tipo_creditos_renovacion";

	            $resultado  = $tipo_credito_renovacion->enviaquery($query);	            
	           
	            echo json_encode(array('data'=>$resultado));	            
	            
	        }
	       	        
	        
	    }
	    else
	    {
	        echo "Usuario no tiene permisos-Editar";
	    }
	    
	}
	

	public function delTipoCredito(){
	    
	    session_start();
	    $tipo_credito_renovacion = new TipoCreditoRenovacionModel();
	    $nombre_controladores = "TipoCredito";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $tipo_credito_renovacion->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer)){	        
	        
	        if(isset($_POST["id_tipo_creditos_renovacion"])){
	            
	            $id_tipo_creditos_renovacion = (int)$_POST["id_tipo_creditos_renovacion"];
	            
	            $resultado  = $tipo_credito_renovacion->eliminarBy("id_tipo_creditos_renovacion",$id_tipo_creditos_renovacion);
	           
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
	
	
	public function consultaTipoCredito(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	
	    $tipo_credito_renovacion = new TipoCreditoRenovacionModel();
	    
	    $where_to="";
	    $columnas  = "aa.id_tipo_creditos_renovacion, 
                    	aa.id_tipo_creditos, 
                    	bb.nombre_tipo_creditos, 
                    	aa.id_tipo_creditos_a_renovar,
                    	cc.nombre_tipo_creditos \"nombre_tipo_creditos_renovar\",                       
                    	dd.id_estado, 
                    	dd.nombre_estado";
                    	    
	    $tablas    = "public.core_tipo_creditos_renovacion aa
                        inner join public.core_tipo_creditos bb
                        on bb.id_tipo_creditos = aa.id_tipo_creditos
                        inner join public.core_tipo_creditos cc
                        on cc.id_tipo_creditos = aa.id_tipo_creditos_a_renovar
                        inner join public.estado dd
                        on dd.id_estado = aa.id_estado";
	    
	    $where     = "1=1";
	    
	    $id        = "aa.id_tipo_creditos_renovacion";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';	    
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND nombre_tipo_creditos LIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$tipo_credito_renovacion->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$tipo_credito_renovacion->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);	        
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_bancos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 15px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Tipo Crédito</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">A Renovar</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Estado</th>';
	            
	                
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
	                $html.='<td style="font-size: 14px;">'.$res->nombre_tipo_creditos.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_tipo_creditos_renovar.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
	                
	               
	                /*comentario up */
	                
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editTipoCredito('.$res->id_tipo_creditos_renovacion.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                    $html.='<td style="font-size: 18px;">
                            <a onclick="delTipoCredito('.$res->id_tipo_creditos_renovacion.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
                    
	               
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaTipoCredito").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	       
	    }
	    
	     
	}
	

	public function cargaEstadoTipoCredito(){
	    
	    $tipo_credito_renovacion = null;
	    $tipo_credito_renovacion = new TipoCreditoRenovacionModel();
	    
	    
	    $query = " SELECT id_estado,nombre_estado FROM estado WHERE tabla_estado = 'core_tipo_creditos_renovacion' ORDER BY nombre_estado";
	    
	    $resulset = $tipo_credito_renovacion->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	public function cargaTipoCredito(){
	    
	    $tipo_credito = null;
	    $tipo_credito = new TipoCreditoModel();
	    
	    $query = " SELECT id_tipo_creditos,nombre_tipo_creditos FROM core_tipo_creditos WHERE 1 = 1 ";
	    
	    $resulset = $tipo_credito->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	public function cargaTipoCreditoRenovacion(){
	    
	    $tipo_credito = null;
	    $tipo_credito = new TipoCreditoModel();
	    
	    $query = " SELECT id_tipo_creditos,nombre_tipo_creditos FROM core_tipo_creditos WHERE 1 = 1 ";
	    
	    $resulset = $tipo_credito->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	
}
?>