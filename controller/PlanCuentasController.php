<?php

class PlanCuentasController extends ControladorBase{
    
    

	public function __construct() {
		parent::__construct();
		
	}


	public function index(){
	
		session_start();
		
		
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		    
		    $_id_usuarios= $_SESSION['id_usuarios'];
		    
		    $resultSet="";
		    $resultSet2 = "";
		    
		    $plan_cuentas = new PlanCuentasModel();
		    
		    $columnas = "DISTINCT plan_cuentas.nivel_plan_cuentas";
		    $columnas1 = "DISTINCT plan_cuentas.n_plan_cuentas";
		  
		    
		    $tablas=" public.plan_cuentas";
		    
		    $where="1=1";
		    
		    $id = "plan_cuentas.nivel_plan_cuentas";
		    $id2 = "plan_cuentas.n_plan_cuentas";
		   
		    
		    $resultSet=$plan_cuentas->getCondiciones($columnas ,$tablas ,$where, $id);
		    $resultSet2=$plan_cuentas->getCondiciones($columnas1 ,$tablas ,$where, $id2);
		
		    
		    
	
			if (!empty($resultSet))
			{
				
			    
			    
					
				$this->view("PlanCuentas",array(
				    "resultSet"=>$resultSet, "resultSet2"=>$resultSet2,
				));
			
			
			}else{
				
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Consultar Comprobantes"
				
					
				));
				exit();
			}
			
			
		}
		else
		{
	
		    $this->redirect("Usuarios","sesion_caducada");
		}

	    
	    
	    
	    
	    
	}
	
	
	
	
	public function  Consulta()
	{
	           
	        
	    session_start();
	    
	    
	    $plan_cuentas = new PlanCuentasModel();
	    $columnas = "plan_cuentas.id_plan_cuentas,
                              plan_cuentas.id_entidades,
                              plan_cuentas.codigo_plan_cuentas,
                              plan_cuentas.nombre_plan_cuentas,
                              plan_cuentas.id_modenas,
                              plan_cuentas.n_plan_cuentas,
                              plan_cuentas.t_plan_cuentas,
                              plan_cuentas.id_centro_costos,
                              plan_cuentas.nivel_plan_cuentas,
                              plan_cuentas.creado,
                              plan_cuentas.modificado,
                              plan_cuentas.fecha_ini_plan_cuentas,
                              plan_cuentas.saldo_plan_cuentas,
                              plan_cuentas.saldo_fin_plan_cuentas,
                              plan_cuentas.fecha_fin_plan_cuentas";
	    
	    $tablas=" public.plan_cuentas";
	    
	    $where="1=1";
	    
	    $id="plan_cuentas.id_plan_cuentas";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    $_codigo_plan_cuentas=$_POST['codigo_plan_cuentas'];
	    $_nombre_plan_cuentas=$_POST['nombre_cuenta'];
	    $_nivel_plan_cuentas=$_POST['nivel_cuenta'];
	    $_n_de_cuenta=$_POST['n_cuenta'];

        if($action == 'ajax')
	    {
	       
	        if(!empty($_codigo_plan_cuentas) or !empty($_nombre_plan_cuentas) or !empty($_nivel_plan_cuentas) or !empty($_n_de_cuenta))
	        {
	         $where_to=$where;
	         
	        if(!empty($_codigo_plan_cuentas)){
	            
	            
	            $where1=" AND plan_cuentas.codigo_plan_cuentas LIKE '".$_codigo_plan_cuentas."%'";
	            
	            $where_to.=$where1;
	        
	        }
	        if(!empty($_nombre_plan_cuentas))
	        {
	         $where2="AND plan_cuentas.nombre_plan_cuentas LIKE  '".$_nombre_plan_cuentas."%'";  
	         
	         $where_to.=$where2;
	         
	        }
	        if(!empty($_nivel_plan_cuentas)){
	            
	           
	            $where3=" AND plan_cuentas.nivel_plan_cuentas ='$_nivel_plan_cuentas'";
	            
	            $where_to.=$where3;
	            
	          }
	          if(!empty($_n_de_cuenta)){
	              
	              
	              $where4="AND plan_cuentas.n_plan_cuentas LIKE'".$_n_de_cuenta."%'"; 
	              
	              $where_to.=$where4;
	              
	          }
	        }
	        else{
	            
	            $where_to=$where;
	            
	            
	        }
	        
	           
	        
	        $html="";
	        $resultSet=$plan_cuentas->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        
	  
	        
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$plan_cuentas->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        
	        
	        
	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:425px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_plan_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 14px;">Codigo</th>';
	            $html.='<th style="text-align: left;  font-size: 14px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 14px;">Saldo</th>';
	          
	          
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 12px;">'.$res->codigo_plan_cuentas.'</td>';
	                $html.='<td style="font-size: 12px;">'.$res->nombre_plan_cuentas.'</td>';
	                $html.='<td style="font-size: 12px;">'.$res->saldo_fin_plan_cuentas.'</td>';
	                
	               
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_plan_cuentas("index.php", $page, $total_pages, $adjacents,"load_planes_cuenta").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay plan de cuentas registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	    
	    
	    
	}
	
	public function AutocompleteCodigoCuentas(){
	    
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    $plan_cuentas = new PlanCuentasModel();
	    $codigo_plan_cuentas = $_GET['term'];
	    
	    $columnas ="plan_cuentas.codigo_plan_cuentas";
	    $tablas =" public.plan_cuentas";
	    $where ="plan_cuentas.codigo_plan_cuentas LIKE '$codigo_plan_cuentas%'";
	    $id ="plan_cuentas.codigo_plan_cuentas";
	    
	    
	    $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    
	    if(!empty($resultSet)){
	        
	        foreach ($resultSet as $res){
	            
	            $_respuesta[] = $res->codigo_plan_cuentas;
	        }
	        echo json_encode($_respuesta);
	    }
	    
	}
	
	public function AutocompleteCodigoDevuelveNombre(){
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    
	    
	    $plan_cuentas = new PlanCuentasModel();
	    $codigo_plan_cuentas = $_POST['codigo_plan_cuentas'];
	    
	    
	    $columnas ="plan_cuentas.codigo_plan_cuentas,
				  plan_cuentas.nombre_plan_cuentas";
	    $tablas ="public.plan_cuentas";
	    $where ="plan_cuentas.codigo_plan_cuentas = '$codigo_plan_cuentas'";
	    $id ="plan_cuentas.codigo_plan_cuentas";
	    
	    
	    $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    
	    $respuesta = new stdClass();
	    
	    if(!empty($resultSet)){
	        
	        $respuesta->nombre_plan_cuentas = $resultSet[0]->nombre_plan_cuentas;
	        	        
	        echo json_encode($respuesta);
	    }
	    
	}
	
	
	public function AutocompleteNombreCuentas(){
	    
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    $plan_cuentas = new PlanCuentasModel();
	    $nombre_plan_cuentas = $_GET['term'];
	    
	    $columnas ="plan_cuentas.nombre_plan_cuentas";
	    $tablas =" public.plan_cuentas";
	    $where ="plan_cuentas.nombre_plan_cuentas ILIKE '$nombre_plan_cuentas%'";
	    $id ="plan_cuentas.nombre_plan_cuentas";
	    
	    
	    $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    
	    if(!empty($resultSet)){
	        
	        foreach ($resultSet as $res){
	            
	            $_respuesta[] = $res->nombre_plan_cuentas;
	        }
	        echo json_encode($_respuesta);
	    }
	    
	}
	
	
	public function AutocompleteNombreDevuelveCodigo(){
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    
	    
	    $plan_cuentas = new PlanCuentasModel();
	    $nombre_plan_cuentas = $_POST['nombre_plan_cuentas'];
	    
	    
	    
	    $columnas ="plan_cuentas.codigo_plan_cuentas,
				  plan_cuentas.nombre_plan_cuentas";
	    $tablas ="public.plan_cuentas";
	    $where ="plan_cuentas.nombre_plan_cuentas = '$nombre_plan_cuentas'";
	    $id ="plan_cuentas.nombre_plan_cuentas";
	    
	    
	    $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    
	    $respuesta = new stdClass();
	    
	    if(!empty($resultSet)){
	        
	        
	        
	        $respuesta->codigo_plan_cuentas = $resultSet[0]->codigo_plan_cuentas;
	        
	        
	        echo json_encode($respuesta);
	    }
	    
	}
	
	
	public function paginate_plan_cuentas($reload, $page, $tpages, $adjacents,$funcion='') {
	    
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
	
	
	
	public function  generar_reporte_Pcuentas(){
	    
	    session_start();
	    $plan_cuentas = new PlanCuentasModel();
	   
	    
	    $html="";
	    $cedula_usuarios = $_SESSION["cedula_usuarios"];

	    
	 
	    
	    $fechaactual = getdate();
	    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	    
	    $directorio = $_SERVER ['DOCUMENT_ROOT'] . '/rp_c';
	    $dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
	    $domLogo=$directorio.'/view/images/logo.png';
	    $logo = '<img src="'.$domLogo.'" alt="Responsive image" width="130" height="70">';
	    
	    
	    $_codigo_plan_cuentas=$_POST['codigo_plan_cuentas'];
	    $_nombre_plan_cuentas=$_POST['nombre_plan_cuentas'];
	    $_nivel_plan_cuentas=$_POST['nivel_plan_cuentas'];
	    $_n_de_cuenta=$_POST['n_plan_cuentas'];
	    
	    
	    if(!empty($cedula_usuarios)){
	        
	            
	         
	            
	            
	            $columnas = "plan_cuentas.id_plan_cuentas, 
                              plan_cuentas.id_entidades, 
                              plan_cuentas.codigo_plan_cuentas, 
                              plan_cuentas.nombre_plan_cuentas, 
                              plan_cuentas.id_modenas, 
                              plan_cuentas.n_plan_cuentas, 
                              plan_cuentas.t_plan_cuentas, 
                              plan_cuentas.id_centro_costos, 
                              plan_cuentas.nivel_plan_cuentas, 
                              plan_cuentas.creado, 
                              plan_cuentas.modificado, 
                              plan_cuentas.fecha_ini_plan_cuentas, 
                              plan_cuentas.saldo_plan_cuentas, 
                              plan_cuentas.saldo_fin_plan_cuentas, 
                              plan_cuentas.fecha_fin_plan_cuentas";
	            
	            $tablas=" public.plan_cuentas";

	             $where="1=1";
	             
	            
	                 
	             if(!empty($_codigo_plan_cuentas) or !empty($_nombre_plan_cuentas) or !empty($_nivel_plan_cuentas) or !empty($_n_de_cuenta))
	                 {
	                     $where_to=$where;
	                     
	                     if(!empty($_codigo_plan_cuentas)){
	                         
	                         
	                         $where1=" AND plan_cuentas.codigo_plan_cuentas LIKE '".$_codigo_plan_cuentas."%'";
	                         
	                         $where_to.=$where1;
	                         
	                     }
	                     if(!empty($_nombre_plan_cuentas))
	                     {
	                         $where2="AND plan_cuentas.nombre_plan_cuentas LIKE  '".$_nombre_plan_cuentas."%'";
	                         
	                         $where_to.=$where2;
	                         
	                     }
	                     if(!empty($_nivel_plan_cuentas)){
	                         
	                         
	                         $where3=" AND plan_cuentas.nivel_plan_cuentas ='$_nivel_plan_cuentas'";
	                         
	                         $where_to.=$where3;
	                         
	                     }
	                     if(!empty($_n_de_cuenta)){
	                         
	                         
	                         $where4=" AND plan_cuentas.n_plan_cuentas LIKE '".$_n_de_cuenta."%'";
	                         
	                         $where_to.=$where4;
	                         
	                     }
	                     
	                 }
	                 else{
	                     
	                     $where_to=$where;
	                 }
	             
	
	            $id="plan_cuentas.id_plan_cuentas";
	            
	            $resultSetCabeza=$plan_cuentas->getCondiciones($columnas, $tablas, $where_to, $id);

	            $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=3>";
	            $html.= "<tr>";
	            $html.='<th bgcolor="#cccccc" colspan="6" style="text-align: center; font-size: 24px;">CAPREMCI</b></br>';
	            $html.='<p style="text-align: center; font-size: 13px; ">Baquerizo Moreno E-978 y Leonidas Plaza ';
	            $html.='<br style="text-align: center; ">Teléfono: 02-3828870 ';
	            $html.='<h1 style="text-align: right; font-size: 12px;">Fecha: '.$fechaactual.'</h1>';
	            
	            $html.='</tr>';
	            $html.= "<tr>";
	            $html.='<th colspan="6" style="text-align: center; font-size: 18px;">Reporte de Plan de Cuentas</th>';
	            $html.='</tr>';

	            if(!empty($resultSetCabeza)){
	                
	                $html.='<tr>';
	                $html.='<th colspan="2" style="text-align: center; font-size: 13px;"><font>Código</font></th>';
	                $html.='<th colspan="2" style="text-align: center; font-size: 13px;"><font>Nombre</font></th>';
	                $html.='<th colspan="2" style="text-align: center; font-size: 13px;"><font>Saldo</font></th>';
	                $html.='</tr>';
	                foreach ($resultSetCabeza as $res){
	                    
	                    $_codigo_plan_cuentas     =$res->codigo_plan_cuentas;
	                    $_nombre_plan_cuentas     =$res->nombre_plan_cuentas;
	                    $_saldo_fin_plan_cuentas  =$res->saldo_fin_plan_cuentas;
	                    
	                    
	                    $html.= "<tr>";
	                    $html.='<td colspan="2" style="text-align: left; font-size: 12px;">'.$_codigo_plan_cuentas.'</td>';
	                    $html.='<td colspan="2" style="text-align: left; font-size: 12px;">'.$_nombre_plan_cuentas.'</td>';
	                    $html.='<td colspan="2" style="text-align: left; font-size: 12px;">'.$_saldo_fin_plan_cuentas.'</td>';
	                    $html.='</tr>';  
	                }
	                $html.='</table>'; 
	            }
	            $this->report("PlanCuentas",array( "resultSet"=>$html));
	        
	            die();   
	    }else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        
	    }
	}
	
	public function generar_Excel_planCuentas ()
	{
	    session_start();
	    
	    $plan_cuentas = new PlanCuentasModel();
        $columnas = "plan_cuentas.codigo_plan_cuentas,
              plan_cuentas.nombre_plan_cuentas,
              plan_cuentas.saldo_fin_plan_cuentas";
	    
	    $tablas=" public.plan_cuentas";
	    
	    $where="1=1";
	    
	    $id="plan_cuentas.id_plan_cuentas";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    $_codigo_plan_cuentas=$_POST['codigo_plan_cuentas'];
	    $_nombre_plan_cuentas=$_POST['nombre_cuenta'];
	    $_nivel_plan_cuentas=$_POST['nivel_cuenta'];
	    $_n_de_cuenta=$_POST['n_cuenta'];
	    
	    if($action == 'ajax')
	    {
	        
	        if(!empty($_codigo_plan_cuentas) or !empty($_nombre_plan_cuentas) or !empty($_nivel_plan_cuentas) or !empty($_n_de_cuenta))
	        {
	            $where_to=$where;
	            
	            if(!empty($_codigo_plan_cuentas)){
	                
	                
	                $where1=" AND plan_cuentas.codigo_plan_cuentas LIKE '".$_codigo_plan_cuentas."%'";
	                
	                $where_to.=$where1;
	                
	            }
	            if(!empty($_nombre_plan_cuentas))
	            {
	                $where2="AND plan_cuentas.nombre_plan_cuentas LIKE  '".$_nombre_plan_cuentas."%'";
	                
	                $where_to.=$where2;
	                
	            }
	            if(!empty($_nivel_plan_cuentas)){
	                
	                
	                $where3=" AND plan_cuentas.nivel_plan_cuentas ='$_nivel_plan_cuentas'";
	                
	                $where_to.=$where3;
	                
	            }
	            if(!empty($_n_de_cuenta)){
	                
	                
	                $where4="AND plan_cuentas.n_plan_cuentas LIKE'".$_n_de_cuenta."%'";
	                
	                $where_to.=$where4;
	                
	            }
	        }
	        else{
	            
	            $where_to=$where;
    
	        }
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where_to, $id);
	        $_respuesta=array();
	        
	        array_push($_respuesta, 'Código', 'Nombre', 'Saldo');
	        
	        if(!empty($resultSet)){
	            
	            foreach ($resultSet as $res){
	                
	                array_push($_respuesta, $res->codigo_plan_cuentas, $res->nombre_plan_cuentas, $res->saldo_fin_plan_cuentas);
	            }
	            echo json_encode($_respuesta);
	        }
	          

	}
	
   }
	
	
		
 }
?>