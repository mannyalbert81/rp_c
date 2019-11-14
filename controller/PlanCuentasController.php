<?php

class PlanCuentasController extends ControladorBase{
    
    

	public function __construct() {
		parent::__construct();
		
	}
	
	public function indexAdmin()
	{
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
	            $this->view_Contable("PlanCuentasAdmin",array(
	                "resultSet"=>$resultSet, "resultSet2"=>$resultSet2,
	                
	            ));
	            
	            
	        }else{
	            
	            $this->view_Contable("Error",array(
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
				
			    
			    
					
			    $this->view_Contable("PlanCuentas",array(
				    "resultSet"=>$resultSet, "resultSet2"=>$resultSet2,
				));
			
			
			}else{
				
			    $this->view_Contable("Error",array(
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
	
	public function tieneHijo($nivel, $codigo, $resultado)
	{
	    $elementos_codigo=explode(".", $codigo);
	    $nivel1=$nivel;
	    $nivel1--;
	    $verif="";
	    for ($i=0; $i<$nivel1; $i++)
	    {
	        $verif.=$elementos_codigo[$i];
	    }
	    
	    foreach ($resultado as $res)
	    {
	        $verif1="";
	        $elementos1_codigo=explode(".", $res->codigo_plan_cuentas);
	        if (sizeof($elementos1_codigo)>=$nivel1)
	            
	            for ($i=0; $i<$nivel1; $i++)
	            {
	                $verif1.=$elementos1_codigo[$i];
	            }
	        
	        
	        if ($res->nivel_plan_cuentas==$nivel && $verif==$verif1)
	        {
	            return true;
	        }
	    }
	    return false;
	}
	
	public function Balance($nivel, $resultset, $limit, $codigo)
	{
	    $headerfont="16px";
	    $tdfont="14px";
	    $boldi="";
	    $boldf="";
	   
	    $colores= array();
	    $colores[0]="#D6EAF8";
	    $colores[1]="#D1F2EB";
	    $colores[2]="#F6DDCC";
	    $colores[3]="#FAD7A0";
	    $colores[4]="#FCF3CF";
	    $colores[5]="#FDFEFE";
	    
	    if ($codigo=="")
	    {
	        $sumatoria="";
	        foreach($resultset as $res)
	        {
	            $verif1="";
	            $elementos1_codigo=explode(".", $res->codigo_plan_cuentas);
	            if (sizeof($elementos1_codigo)>=$nivel)
	                for ($i=0; $i<$nivel; $i++)
	                {
	                    $verif1.=$elementos1_codigo[$i];
	                }
	            if ($res->nivel_plan_cuentas == $nivel)
	            {
	                
	                if($nivel<=$limit)
	                {$nivel++;
	                $nivelclase=$nivel-1;
	                $color=$nivel-2;
	                if ($color>5) $color=5;
	                $sumatoria.='<tr id="cod'.$verif1.'">';
	                $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
	                $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">';
	                if ($this->tieneHijo($nivel,$res->codigo_plan_cuentas, $resultset))
	                {
	                    $sumatoria.='<button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$verif1.'&quot;,&quot;trbt'.$verif1.'&quot;)">
                    <i id="trbt'.$verif1.'" class="fa fa-angle-double-right" name="boton"></i></button>';
	                }
	                $sumatoria.=$boldi.$res->nombre_plan_cuentas.$boldf.'</td>';
	                $sumatoria.='</tr>';
	                if ($this->tieneHijo($nivel,$res->codigo_plan_cuentas, $resultset))
	                {
	                    
	                    $sumatoria.=$this->Balance($nivel, $resultset, $limit, $res->codigo_plan_cuentas);
	                    
	                }
	                
	                $nivel--;
	                }
	            }
	        }
	    }
	    else
	    {
	        
	        $sumatoria="";
	        $elementos_codigo=explode(".", $codigo);
	        $nivel1=$nivel;
	        $nivel1--;
	        $verif="";
	        for ($i=0; $i<$nivel1; $i++)
	        {
	            $verif.=$elementos_codigo[$i];
	        }
	        foreach($resultset as $res)
	        {
	            $verif1="";
	            $verif2="";
	            $elementos1_codigo=explode(".", $res->codigo_plan_cuentas);
	            for ($i=0; $i<sizeof($elementos1_codigo); $i++)
	            {
	                $verif2.=$elementos1_codigo[$i];
	            }
	            if (sizeof($elementos1_codigo)>=$nivel1)
	                for ($i=0; $i<$nivel1; $i++)
	                {
	                    $verif1.=$elementos1_codigo[$i];
	                }
	          
	            if ($res->nivel_plan_cuentas == $nivel && $verif==$verif1)
	            {
	                
	                
	                if($nivel<=$limit)
	                {$nivel++;
	                $nivelclase=$nivel-1;
	                $color=$nivel-2;
	                if ($color>5) $color=5;
	                $sumatoria.='<tr class="nivel'.$verif1.'" id="cod'.$verif2.'" style="display:none">';
	                $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
	                $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">';
	                if ($this->tieneHijo($nivel,$res->codigo_plan_cuentas, $resultset))
	                {
	                    $sumatoria.='<button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$verif2.'&quot;,&quot;trbt'.$verif2.'&quot;)">
                    <i id="trbt'.$verif2.'" class="fa fa-angle-double-right" name="boton"></i></button>';
	                }
	                $sumatoria.=$boldi.$res->nombre_plan_cuentas.$boldf;
	                
	                if ($res->nivel_plan_cuentas>1)
	                {
	                    $sumatoria.='<button  type="button" class="btn btn-box-tool pull-right" style="color:#5499C7" data-toggle="modal" data-target="#myModalAgregar" onclick="AgregarCuenta(&quot;'.$res->codigo_plan_cuentas.'&quot;,'.$res->id_entidades.','.$res->id_modenas.',&quot;'.$res->n_plan_cuentas.'&quot;,'.$res->id_centro_costos.','.$res->nivel_plan_cuentas.')"><i class="glyphicon glyphicon-plus"></i></button>';
	                if($res->nivel_plan_cuentas>2)
	                {
                    $sumatoria.='<button  type="button" class="btn btn btn-box-tool pull-right" style="color:#229954" data-toggle="modal" data-target="#myModalEdit" onclick="EditarCuenta('.$res->id_plan_cuentas.',&quot;'.$res->codigo_plan_cuentas.'&quot;,&quot;'.$res->nombre_plan_cuentas.'&quot;)"><i class="glyphicon glyphicon-pencil"></i></button>';
	                }
	                }
                    $sumatoria.='</td>';
	                $sumatoria.='</tr>';
	                if ($this->tieneHijo($nivel,$res->codigo_plan_cuentas, $resultset))
	                {
	                    
	                    $sumatoria.=$this->Balance($nivel, $resultset, $limit, $res->codigo_plan_cuentas);
	                }
	                $nivel--;
	                }
	            }
	        }
	    }
	    return $sumatoria;
	}
	
	public function TablaPlanCuentas()
	{
	    
	    session_start();
	    
	    
	    if (isset(  $_SESSION['usuario_usuarios']) )
	    {
	        $plan_cuentas= new PlanCuentasModel();
	        	        
	        $tablas= "public.plan_cuentas";
	        
	        $where= "1=1";
	        
	        $id= "plan_cuentas.codigo_plan_cuentas";
	        
	        $resultSet=$plan_cuentas->getCondiciones("*", $tablas, $where, $id);
	        
	        $tablas= "public.plan_cuentas";
	        
	        $where= "1=1";
	        
	        $id= "max";
	        
	        $resultMAX=$plan_cuentas->getCondiciones("MAX(nivel_plan_cuentas)", $tablas, $where, $id);
	        
	        $headerfont="16px";
	        $tdfont="14px";
	        $boldi="";
	        $boldf="";
	        
	        $colores= array();
	        $colores[0]="#D6EAF8";
	        $colores[1]="#D1F2EB";
	        $colores[2]="#FCF3CF";
	        $colores[3]="#F8C471";
	        $colores[4]="#EDBB99";
	        $colores[5]="#FDFEFE";
	        
	        $datos_tabla= "<table id='tabla_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	        $datos_tabla.='<tr  bgcolor="'.$colores[0].'">';
	        $datos_tabla.='<th bgcolor="'.$colores[0].'" width="1%"  style="width:130px; text-align: center;  font-size: '.$headerfont.';">CÓDIGO</th>';
	        $datos_tabla.='<th bgcolor="'.$colores[0].'" width="83%" style="text-align: center;  font-size: '.$headerfont.';">CUENTA</th>';
	        $datos_tabla.='</tr>';
	        
	        $datos_tabla.=$this->Balance(1, $resultSet, $resultMAX[0]->max, "");
	        
	        $datos_tabla.= "</table>";
	        
	        echo $datos_tabla;
	    }
	    else
	    {
	        
	        $this->redirect("Usuarios","sesion_caducada");
	    }
	    
	    
	}
	
	public function EditarNombreCuenta()
	{
	    session_start();
	    $plan_cuentas = new PlanCuentasModel();
	    
	    $id_plan_cuentas=$_POST['id_plan_cuentas'];
	    $nombre_plan_cuentas=$_POST['nombre_plan_cuentas'];
	    
	    $colval="nombre_plan_cuentas='".$nombre_plan_cuentas."'";
	    $tabla="plan_cuentas";
	    $where="id_plan_cuentas=".$id_plan_cuentas;
	    $plan_cuentas->UpdateBy($colval, $tabla, $where);
	}
	
	public function AgregarNuevaCuenta()
	{
	    session_start();
	    $plan_cuentas= new PlanCuentasModel();
	    $funcion = "ins_nueva_cuenta";
        $codigo_plan_cuentas=$_POST['codigo_plan_cuentas'];
    	$nombre_plan_cuentas=$_POST['nombre_plan_cuentas'];
    	$id_entidades=$_POST['id_entidades'];
    	$id_modenas=$_POST['id_modenas'];
    	$n_plan_cuentas=$_POST['n_plan_cuentas'];
    	$id_centro_costos=$_POST['id_centro_costos'];
    	$nivel_plan_cuentas=$_POST['nivel_plan_cuentas'];
    	$parametros="'$nombre_plan_cuentas',
                     '$codigo_plan_cuentas',
                     '$id_entidades',
                     '$id_modenas',
                     '$n_plan_cuentas',
                     '$id_centro_costos',
                     '$nivel_plan_cuentas'";
    	$plan_cuentas->setFuncion($funcion);
    	$plan_cuentas->setParametros($parametros);
    	$resultado=$plan_cuentas->Insert();
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
	    
	    $entidades = new EntidadesModel();
	    //PARA OBTENER DATOS DE LA EMPRESA
	    $datos_empresa = array();
	    $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
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
	            
	            
	            if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0){
	                //llenar nombres con variables que va en html de reporte
	                $datos_empresa['NOMBREEMPRESA']=$rsdatosEmpresa[0]->nombre_entidades;
	                $datos_empresa['DIRECCIONEMPRESA']=$rsdatosEmpresa[0]->direccion_entidades;
	                $datos_empresa['TELEFONOEMPRESA']=$rsdatosEmpresa[0]->telefono_entidades;
	                $datos_empresa['RUCEMPRESA']=$rsdatosEmpresa[0]->ruc_entidades;
	                $datos_empresa['FECHAEMPRESA']=date('Y-m-d H:i');
	                $datos_empresa['USUARIOEMPRESA']=(isset($_SESSION['usuario_usuarios']))?$_SESSION['usuario_usuarios']:'';
	            }
	            
	            //NOTICE DATA
	            $datos_cabecera = array();
	            $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
	            $datos_cabecera['FECHA'] = date('Y/m/d');
	            $datos_cabecera['HORA'] = date('h:i:s');
	            
	            
	            $datos_reporte = array();

	            if(!empty($resultSetCabeza)){
	                
	                $html.='<table cellspacing="0" style="width:100px;" border="1" >';
	                
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
	                    $html.='<td colspan="2" style="text-align: left;  font-size: 12px;">'.$_codigo_plan_cuentas.'</td>';
	                    $html.='<td colspan="2" style="text-align: left;  font-size: 12px;">'.$_nombre_plan_cuentas.'</td>';
	                    $html.='<td colspan="2" class="htexto3">$ '.$_saldo_fin_plan_cuentas.'</td>';
	                    $html.='</tr>';  
	                }
	                $html.='</table>'; 
	            }
	            
	            $datos_reporte['DETALLE_PLAN_CUENTAS']= $html;
	            
	            $this->verReporte("PlanDeCuentas", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
	            
	        
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

   public function cargaMovimientos(){
       
       $PlanCuentas = new PlanCuentasModel();
       
       
       
       /** variables funcion */
       $_id_entidad_patronal  = 0;
       $_anio_carga_recaudaciones     = 0;
       $_mes_carga_recaudaciones      = 0;
       $_formato_carga_recaudaciones  = 0;
       $_usuario_usuarios             = "";
       $_arhivo_carga_datos           = array();
       $_archivo_procesar             = "";
       $_error_cabecera               = "ARCHIVO NO FUE PROCESADO";
       $_archivo_errores              = array();
       $_numero_lineas_archivo        = 0;
       $_suma_total_archivo           = 0.00;
       $_nombre_archivo_guardar       = "";
       $_ruta_archivo_guardar         = "";
       $_filas_archivo                = array();
       $_array_plan_cuentas           = array();
       
       /**
        ****************************************************** validar si el archivo esta en el directorio ****************************************************
        **/
       $nombreArchivo   = "MOVIMIENTOSOCT.txt";
       $rutaProcesar    = 'docs\\'.$nombreArchivo;
       
       if(file_exists($rutaProcesar)){           
           
           /** comienza lectura de archivos **/
           $file_abierto   = fopen($rutaProcesar, "r");
           
           //var_dump($file_abierto); exit();
           
           /** AQUI VALIDACION DE FORMATOS */
           $_linea = 0;
           $_linea_llena = 0;
           while(!feof($file_abierto))
           {
               $_fila = fgets($file_abierto);
               $_fila = trim($_fila);
               $_fila = trim($_fila,"\n");
               //echo "\n'",var_dump($_fila),"'";
               if( $_linea > 0 && $_fila != "" ){
                   
                   $_array_fila   = explode("\t", $_fila);
                   if( is_array($_array_fila) && sizeof( $_array_fila ) == 5 ){
                       
                       $_codigo_plan_cuentas    = $_array_fila[0];
                       $_nombre_plan_cuentas    = $_array_fila[1];
                       $_saldo_inicial      = $_array_fila[2];
                       $_movimiento_mes     = $_array_fila[3];
                       $_saldo_final        = $_array_fila[4];
                       
                       /** dar formato para la base a las columnas **/
                       $_codigo_plan_cuentas    = 0;
                       if( strlen($_cedula) != 10 ){
                           // se valida el formato de cedula esten 10
                           array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"cedula no tiene formato correcto","cantidad"=>0));
                       }
                       if( !is_numeric( $_valor ) ){
                           // se valida si el valor es numerico
                           array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"valor no tiene formato numerico","cantidad"=>0));
                       }
                       
                       array_push($_archivo_cedulas, array("linea"=>$_linea,"cedula"=>$_cedula));
                       array_push($_filas_archivo, array("linea"=>$_linea,"cedula"=>$_cedula,"valor"=>$_valor));
                       
                       $_linea_llena++;
                       $_suma_total_archivo = $_suma_total_archivo + (float)$_valor;
                       
                   }else{
                       
                       //echo "\n","linea--**--",$_linea,"--**--","no es array";
                       array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"cantidad columnas no encontradas","cantidad"=>0));
                   }
                   
               }else if( $_linea > 0 && $_fila == "" ){
                   
                   //echo "\n","linea--**--",$_linea,"--**--","linea vacia";
                   array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"linea se encuentra vacia","cantidad"=>0));
               }
               $_linea++;
           }
           
           fclose($file_abierto);
           
       }
      
       
       die();
       
     
       
       /**
        ****************************************************** validar formato del archivo ***********************************************************
        **/
       try {
           
           /** variables para la lectura del archivo **/
           $_archivo_cedulas = array();
           
           /** comienza lectura de archivos **/
           $file_abierto   = fopen($_archivo_procesar, "r");
           if( !$file_abierto ){ throw new Exception("Archivo no fue posible leer"); }
           
           /** AQUI VALIDACION DE FORMATOS */
           $_linea = 0;
           $_linea_llena = 0;
           while(!feof($file_abierto))
           {
               //echo "\n",$_linea,"--**--";
               $_valor     = 0.00;
               $_fila = fgets($file_abierto);
               $_fila = trim($_fila);
               $_fila = trim($_fila,"\n");
               //echo "\n'",var_dump($_fila),"'";
               if( $_linea > 0 && $_fila != "" ){
                   
                   $_array_fila   = explode(";", $_fila);
                   if( is_array($_array_fila) && sizeof( $_array_fila ) == 3 ){
                       
                       $_cedula    = $_array_fila[0];
                       $_valor     = $_array_fila[2];
                       if( strlen($_cedula) != 10 ){
                           // se valida el formato de cedula esten 10
                           array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"cedula no tiene formato correcto","cantidad"=>0));
                       }
                       if( !is_numeric( $_valor ) ){
                           // se valida si el valor es numerico
                           array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"valor no tiene formato numerico","cantidad"=>0));
                       }
                       
                       array_push($_archivo_cedulas, array("linea"=>$_linea,"cedula"=>$_cedula));
                       array_push($_filas_archivo, array("linea"=>$_linea,"cedula"=>$_cedula,"valor"=>$_valor));
                       
                       $_linea_llena++;
                       $_suma_total_archivo = $_suma_total_archivo + (float)$_valor;
                       
                   }else{
                       
                       //echo "\n","linea--**--",$_linea,"--**--","no es array";
                       array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"cantidad columnas no encontradas","cantidad"=>0));
                   }
                   
               }else if( $_linea > 0 && $_fila == "" ){
                   
                   //echo "\n","linea--**--",$_linea,"--**--","linea vacia";
                   array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"linea se encuentra vacia","cantidad"=>0));
               }
               $_linea++;
           }
           fclose($file_abierto);
           
           //echo $_linea_llena;
           $_numero_lineas_archivo = $_linea_llena;
           /** validaciones del archivo */
           if( $_linea_llena < 1 ){
               array_push($_archivo_errores, array("linea"=>"","error"=>"Archivo se encuentra vacio","cantidad"=>0));
               echo json_encode(array("cabecera"=>"$_error_cabecera","dataerror"=>$_archivo_errores) );
               throw new Exception(); //genera exepcion y sale del metodo
           }else{
               
               if( sizeof($_archivo_errores) > 0 ){
                   
                   echo json_encode(array("cabecera"=>"$_error_cabecera","dataerror"=>$_archivo_errores) );
                   throw new Exception(); //genera exepcion y sale del metodo
                   
               }else{
                   
                   $_array_cedulas_repetidas   = array(); //variable para registrar la repeticion de las cedulas
                   if( sizeof($_archivo_cedulas) > 0 ){
                       
                       $_cantidad_repeticiones_cedulas = 0;
                       $_array_buscar_cedulas  = $_archivo_cedulas;
                       $_i = 0;
                       // se recorre el array de cedulas generadas en el primera validacion
                       foreach ( $_archivo_cedulas as $res_i ){
                           
                           $cedula_main = $res_i['cedula']; //cedula a validar
                           $j = 0;
                           $_array_disponibles = array(); // variable para reorganizar nuevamente el array copia de cedulas recogidas en el primer recorrido del archivo
                           $_linea_error="[";
                           
                           foreach ( $_array_buscar_cedulas as $res_j ){
                               $_linea_error .= $res_j['linea'].", "; //coleccionadndo en las filas donde ese encuentra repetida la cedula
                               if( $cedula_main == $res_j['cedula'] ){
                                   $_cantidad_repeticiones_cedulas ++; //se suma las cantidades de veces de repeticiones
                                   unset($_array_buscar_cedulas[$j]); //eliminar el alemento del array ya encontrado
                               }else{
                                   $_array_disponibles[] = $_array_buscar_cedulas[$j]; //se agrega al array para resetear los indices
                               }
                               $j++;
                           }
                           $_linea_error.="]";
                           if( $_cantidad_repeticiones_cedulas > 1 ){
                               array_push($_array_cedulas_repetidas, array("linea"=>$_linea_error,"error"=>"cedula repetida - $cedula_main","repeticiones"=>$_cantidad_repeticiones_cedulas));
                           }
                           
                           $_array_buscar_cedulas = $_array_disponibles; //variable seteada
                           
                           
                           $_cantidad_repeticiones_cedulas = 0;
                           $_i ++;
                       }
                       
                   }
                   
                   /**validacion cedulas repetidas */
                   if( sizeof($_array_cedulas_repetidas) > 0 ) {
                       
                       echo json_encode(array("cabecera"=>"$_error_cabecera","dataerror"=>$_archivo_errores) );
                       throw new Exception(); //genera exepcion y sale del metodo
                       
                   }else{
                       
                       //si no existe cedulas repetidas se valida en BD
                       
                       $_array_cedulas_bd = array();
                       /** recorrer cedula validas contra BD **/
                       $columnas1  = " aa.id_participes, aa.cedula_participes";
                       $tablas1    = " core_participes aa
                            INNER JOIN core_entidad_patronal bb ON bb.id_entidad_patronal = aa.id_entidad_patronal";
                       $where1     = " 1 = 1 AND bb.id_entidad_patronal = $_id_entidad_patronal";
                       $id1        = "aa.id_participes";
                       
                       $_bd = true;
                       foreach($_archivo_cedulas as $res){
                           
                           $_cedula_archivo = $res['cedula'];
                           $where1 .= "AND aa.cedula_participes = '$_cedula_archivo'";
                           $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
                           
                           if( empty($rsConsulta1) ){
                               $_bd = false;
                               $_linea_archivo = $res['linea'];
                               $_error_bd      = "cedula [$_cedula_archivo] no pertenece a la Entidad Patronal";
                               array_push($_array_cedulas_bd, array("linea"=>$_linea_archivo,"error"=>$_error_bd,"cantidad"=>0));
                           }
                           
                       }
                       
                       if( !$_bd ){
                           
                           echo json_encode( array("cabecera"=>$_error_cabecera,"dataerror"=>$_array_cedulas_bd) );
                           throw new Exception(); //genera exepcion y sale del metodo
                       }
                       
                   }
                   
                   
               }// "end if" array errores
               
               
           }// end if .validacion numero de lineas
           
           
           
       } catch (Exception $e) {
           //echo "<message>".$e->getMessage()."<message>";
           exit();
       }
       
       /**
        ****************************************************** insercion del archivo en tabla ***********************************************************
        **/
       try{
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
           foreach ( $_filas_archivo as $res ){
               
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
           
           $respuesta['mensaje']   = "Carga Generada";
           $respuesta['respuesta'] = 1;
           $respuesta['id_archivo']= $_id_carga_recaudaciones;
           echo json_encode( $respuesta);
           $Contribucion->endTran('COMMIT');
           
       } catch (Exception $ex) {
           $Contribucion->endTran();
           echo '<message> Error Carga Archivo Recaudacion '.$ex->getMessage().' <message>';
       }
       
   }
   	
 }
?>