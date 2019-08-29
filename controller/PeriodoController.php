<?php

class PeriodoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    $periodo = new PeriodoModel();
				
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "Periodo";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $periodo->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Periodo"
		        
		    ));
		    exit();
		}		    
			
		$rsBancos = $periodo->getBy(" 1 = 1 ");
		
				
		$this->view_Contable("Periodo",array(
		    "resultSet"=>$rsBancos
	
		));
			
	
	}
	

	
	
/*	public function InsertaPeriodo(){
	    
	    session_start();
		
		$periodo = new PeriodoModel();
		
		$nombre_controladores = "Periodo";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $periodo->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer)){	
		    
		    $_id_periodo = (isset($_POST["id_periodo"])) ? $_POST["id_periodo"] : "0";
		    $_year_periodo = (isset($_POST["year_periodo"])) ? $_POST["year_periodo"] : 0 ;
		    $_mes_periodo = (isset($_POST["mes_periodo"])) ? $_POST["mes_periodo"] : 0 ;
		    $_id_tipo_cierre = (isset($_POST["id_tipo_cierre"])) ? $_POST["id_tipo_cierre"] : 0 ;
		    $_id_estado = (isset($_POST["id_estado"])) ? $_POST["id_estado"] : 0 ;
		    

			$funcion = "ins_con_periodo";
			$respuesta = 0 ;
			$mensaje = ""; 
			
	
			
			if($_id_periodo == 0){
			    
			    $parametros = " '$_year_periodo','$_mes_periodo','$_id_tipo_cierre','$_id_estado'";
			    $periodo->setFuncion($funcion);
			    $periodo->setParametros($parametros);
			    $resultado = $periodo->llamafuncionPG();
			    
			    if(is_int((int)$resultado[0])){
			        $respuesta = $resultado[0];
			        $mensaje = "Periodo Ingresado Correctamente";
			    }	
			    
			
			    
			}elseif ($_id_periodo > 0){
			    
			    $parametros = " '$_year_periodo','$_mes_periodo','$_id_tipo_cierre','$_id_estado'";
			    $periodo->setFuncion($funcion);
			    $periodo->setParametros($parametros);
			    $resultado = $periodo->llamafuncionPG();
			    
			    if(is_int((int)$resultado[0])){
			        $respuesta = $resultado[0];
			        $mensaje = "Periodo Actualizado Correctamente";
			    }	
			    
			    
			}
			
			
	
			//print_r($respuesta);
			
	
			if(is_int((int)$respuesta)){
			    
			    echo json_encode(array('respuesta'=>$respuesta,'mensaje'=>$mensaje));
			    exit();
			}
			
			echo "Error al Ingresar Periodo";
			exit();
			
		}
		else
		{
		    $this->view_Inventario("Error",array(
					"resultado"=>"No tiene Permisos de Insertar Periodo"
		
			));
		
		
		}
		
	}
	*/
	
	public function AbrirPeriodo(){
	    
	    session_start();
	    
	    $periodo = new PeriodoModel();
	    
	    $nombre_controladores = "Periodo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $periodo->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	
	    
	    
	    if (!empty($resultPer)){
	        
	        
	        $columnaest = "con_periodo.id_periodo, con_periodo.id_estado ";
	        $tablaest= "con_periodo,estado";
	        $whereest= "con_periodo.id_estado = estado.id_estado
                        and estado.nombre_estado = 'ABIERTO'
                        and con_periodo.year_periodo = con_periodo.year_periodo
                        and con_periodo.mes_periodo = con_periodo.mes_periodo
                        and con_periodo.id_tipo_cierre = 1
                        ";
	        $idest = "estado.id_estado";
	        $resultEst = $periodo->getCondiciones($columnaest, $tablaest, $whereest, $idest);
	       
	        
	        
	        if (empty($resultEst)){
	        
	            $_id_periodo = (isset($_POST["id_periodo"])) ? $_POST["id_periodo"] : "0";
	            $_year_periodo = (isset($_POST["year_periodo"])) ? $_POST["year_periodo"] : 0 ;
	            $_mes_periodo = (isset($_POST["mes_periodo"])) ? $_POST["mes_periodo"] : 0 ;
	            $_id_tipo_cierre = (isset($_POST["id_tipo_cierre"])) ? $_POST["id_tipo_cierre"] : 0 ;
	            $_id_estado = (isset($_POST["id_estado"])) ? $_POST["id_estado"] : 0 ;
	            
	            
	            $funcion = "ins_con_periodo";
	            $respuesta = 0 ;
	            $mensaje = "";
	            
	             
	            if($_id_periodo == 0){
	                
	                $parametros = " '$_year_periodo','$_mes_periodo','$_id_tipo_cierre','101'";
	                $periodo->setFuncion($funcion);
	                $periodo->setParametros($parametros);
	                $resultado = $periodo->llamafuncionPG();
	                
	                if(is_int((int)$resultado[0])){
	                    $respuesta = $resultado[0];
	                    $mensaje = "Periodo Ingresado Correctamente";
	                }
	                
	                
	                
	            }elseif ($_id_periodo > 0){
	                
	             
	                
	                
	            }
	            
	        }
	        else 
	        {
	            $resultEst=$resultEst[0]->id_estado;
	            $respuesta = 0 ;
	            $mensaje = "Existe un Periodo Abierto"; 
	            
	        }
	   
	        
	        echo json_encode(array ("respuesta"=>$respuesta, "mensaje"=> $mensaje));
	        exit ();
	        
	       
	        
	      
	    }
	    else
	    {
	        $this->view_Inventario("Error",array(
	            "resultado"=>"No tiene Permisos de Insertar Periodo"
	            
	        ));
	        
	        
	    }
	    
	}
	
	public function CerrarPeriodo(){
	    
	    session_start();
	    
	    $periodo = new PeriodoModel();
	    
	    $nombre_controladores = "Periodo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $periodo->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	   	    
	    if (!empty($resultPer)){
	        
	        $_id_periodo = (isset($_POST["id_periodo"])) ? $_POST["id_periodo"] : "0";
	        
	        $respuesta = 0 ;
	        $mensaje = "";
	        
	        
	            
	            $columnaCre = "id_estado = '102'";
	            $tablasCre = "con_periodo";
	            $whereCre = "id_periodo = $_id_periodo";
	            $resultado= $periodo -> ActualizarBy($columnaCre, $tablasCre, $whereCre);           
	          
	            
	            if(is_int((int)$resultado)){
	                $respuesta = $resultado;
	                $mensaje = "Periodo Cerrado Correctamente";
	            }
	            
	            
	         
	        echo json_encode(array ("respuesta"=>$respuesta, "mensaje"=> $mensaje));
	        exit ();
	        
	        
	    }
	    else
	    {
	        $this->view_Inventario("Error",array(
	            "resultado"=>"No tiene Permisos de Insertar Periodo"
	            
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
	public function editPeriodo(){
	    
	    session_start();
	    $periodo = new PeriodoModel();
	    $nombre_controladores = "Periodo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $periodo->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    	     
	    if (!empty($resultPer))
	    {
	        
	        
	        if(isset($_POST["id_periodo"])){
	            
	            $id_periodo = (int)$_POST["id_periodo"];
	            
	            $query = "SELECT * FROM con_periodo WHERE id_periodo = $id_periodo";

	            $resultado  = $periodo->enviaquery($query);	            
	           
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
	public function delPeriodo(){
	    
	    session_start();
	    $periodo = new PeriodoModel();
	    $nombre_controladores = "Periodo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $periodo->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer)){	        
	        
	        if(isset($_POST["id_periodo"])){
	            
	            $id_periodo = (int)$_POST["id_periodo"];
	            
	            $resultado  = $periodo->eliminarBy(" id_periodo ",$id_periodo);
	           
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
	
	
	public function consultaPeriodo(){
	    
	    session_start();
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $id_rol=$_SESSION["id_rol"];
	    
	    $periodo = new PeriodoModel();
	    
	    $where_to="";
	    $columnas  = " id_periodo, year_periodo, mes_periodo, nombre_tipo_cierre, nombre_estado";
	    
	    $tablas    = "public.con_periodo INNER JOIN public.con_tipo_cierre ON con_tipo_cierre.id_tipo_cierre = con_periodo.id_tipo_cierre INNER JOIN public.estado ON estado.id_estado = con_periodo.id_estado";
	    
	    $where     = " 1 = 1";
	    
	    $id        = "con_periodo.id_periodo";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';	    
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND nombre_estado ILIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$periodo->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$periodo->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
	            $html.='<th style="text-align: left;  font-size: 15px;">Año</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Mes</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Tipo Cierre</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Estado</th>';
	            
	            /*para administracion definir administrador MenuOperaciones Edit - Eliminar*/
	                
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
	                $html.='<td style="font-size: 14px;">'.$res->year_periodo.'</td>';
	                $html.='<td style="font-size: 14px;">'.strtoupper($meses[$res->mes_periodo-1]).'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_tipo_cierre.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
	                
	               
	                /*comentario up */
	                
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editPeriodo('.$res->id_periodo.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                        
	               
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaPeriodo").'';
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
	
	/**
	 * mod: tesoreria
	 * title: cargar datos estado bancos
	 * ajax: si
	 * dc:2019-04-22
	 */
	public function cargaEstadoPeriodo(){
	    
	    $periodo = null;
	    $periodo = new PeriodoModel();
	    
	    $query = " SELECT id_estado,nombre_estado FROM estado WHERE tabla_estado = 'con_periodo' ORDER BY nombre_estado";
	    
	    $resulset = $periodo->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	public function cargaTipoCierre(){
	    
	    $tipo_cierre = null;
	 //   $periodo = new PeriodoModel();
	    $tipo_cierre = new TipoCierreModel();
	    
	    $query = " SELECT id_tipo_cierre,nombre_tipo_cierre FROM con_tipo_cierre WHERE 1 = 1 ";
	    
	    $resulset = $tipo_cierre->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	
}
?>