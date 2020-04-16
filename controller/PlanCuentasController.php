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
       //$_array_plan_cuentas           = array();
       
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
           //$_linea_llena = 0;
           while(!feof($file_abierto))
           {
               $_fila = fgets($file_abierto);
               $_fila = trim($_fila);
               $_fila = trim($_fila,"\n");
               //echo "\n'",var_dump($_fila),"'";
               if( $_linea > 0 && $_fila != "" ){
                   
                  echo "\n",$_fila;
                   $_array_fila   = explode("\t", $_fila);
                   if( is_array($_array_fila) && sizeof( $_array_fila ) == 5 ){
                       
                       $_codigo_plan_cuentas    = $_array_fila[0];
                       $_nombre_plan_cuentas    = $_array_fila[1];
                       $_saldo_inicial      = $_array_fila[2];
                       $_movimiento_mes     = $_array_fila[3];
                       $_saldo_final        = $_array_fila[4];
                       
                       /** dar formato para la base a las columnas **/
                       $_codigo_plan_cuentas    = trim($_codigo_plan_cuentas," ");
                       $_codigo_plan_cuentas    = str_replace('-', '.', $_codigo_plan_cuentas);
                       $_array_codigo = null;
                       $_array_codigo = $_array_fila   = explode(".", $_codigo_plan_cuentas);
                       $_codigo_plan_cuentas = "";
                       for( $i = 0; $i<sizeof($_array_codigo); $i++){
                           if( (int)$_array_codigo[$i] > 0 ){
                               $_codigo_plan_cuentas .= $_array_codigo[$i].".";                               
                                                            
                           }
                           
                       }
                       $_codigo_plan_cuentas = trim($_codigo_plan_cuentas,".");
                       
                       $_nombre_plan_cuentas = preg_replace("/\"/","",$_nombre_plan_cuentas);
                       $_nombre_plan_cuentas = trim($_nombre_plan_cuentas," ");
                       $_nombre_plan_cuentas = utf8_encode ($_nombre_plan_cuentas );
                       
                       //para el saldo inicial
                       $_saldo_inicial = preg_replace("/\"/","",$_saldo_inicial);
                       $_saldo_inicial  = trim($_saldo_inicial," ");
                       $_saldo_inicial  = str_replace(",", "", $_saldo_inicial);
                       $_saldo_inicial = $_saldo_inicial == "-" ? 0 : $_saldo_inicial;
                       
                       if( !is_numeric( $_saldo_inicial ) ){
                           echo "\n \t linea no tiene valor saldo inicial  ",$_linea;
                       }
                       
                       //para el movimiento
                       $_movimiento_mes = preg_replace("/\"/","",$_movimiento_mes);
                       $_movimiento_mes  = trim($_movimiento_mes," ");
                       $_movimiento_mes  = str_replace(",", "", $_movimiento_mes);
                       $_movimiento_mes = $_movimiento_mes == "-" ? 0 : $_movimiento_mes;
                       
                       if( !is_numeric( $_movimiento_mes ) ){
                           echo "\n \t linea movimiento no es numeric ",$_linea;
                       }
                       
                       
                       //para el saldo inicial
                       $_saldo_final = preg_replace("/\"/","",$_saldo_final);
                       $_saldo_final  = trim($_saldo_final," ");
                       $_saldo_final  = str_replace(",", "", $_saldo_final);
                       $_saldo_final = $_saldo_final == "-" ? 0 : $_saldo_final;
                                              
                       if( !is_numeric( $_saldo_final ) ){
                           echo "\n \t linea no tiene valor saldo final ",$_linea;
                       }
                       
                       echo "\n linea".$_linea. " a guardar  ",$_codigo_plan_cuentas,"\t",$_nombre_plan_cuentas,"\t",$_saldo_inicial,"\t",$_movimiento_mes,"\t",$_saldo_final,"\t";
                       
                       /** paso validacion a insertar **/
                       
                       $codigo_buscar = $_codigo_plan_cuentas;
                       // primero una consulta 
                       $columnas1   = " 1 existe,id_plan_cuentas";
                       $tablas1     = " public.plan_cuentas";
                       $where1      = " codigo_plan_cuentas = '$codigo_buscar' OR codigo_plan_cuentas = '".$codigo_buscar.".'";
                       $id1         = " id_plan_cuentas";
                       $rsConsulta  = $PlanCuentas->getCondiciones($columnas1, $tablas1, $where1, $id1);
                       
                       if( empty($rsConsulta) ){
                           
                          
                           $_array_nivel_plan_cuentas = explode(".", $_codigo_plan_cuentas);
                           $_nivel_plan_cuentas = sizeof($_array_nivel_plan_cuentas);
                           /** varaibles de insertado */
                           $_columnas  = " INSERT INTO public.plan_cuentas(".
                               "id_entidades, codigo_plan_cuentas, nombre_plan_cuentas, id_modenas, n_plan_cuentas, id_centro_costos,".
                               "nivel_plan_cuentas, saldo_plan_cuentas, saldo_fin_plan_cuentas)";
                           $_valores    = " VALUES ( 1,'$codigo_buscar','$_nombre_plan_cuentas', 1,'A',1,'$_nivel_plan_cuentas',$_saldo_inicial,$_saldo_final)";
                           $_retorno    = " RETURNING id_plan_cuentas";
                           $_queryInsert= $_columnas.$_valores.$_retorno;
                           $_id_comprobantes = $PlanCuentas->executeInsertQuery($_queryInsert);
                           
              
                           
                       }else{
                          
                           $_id_plan_cuentas = $rsConsulta[0]->id_plan_cuentas;
                           
                           $_columnas_upd = " saldo_plan_cuentas = $_saldo_inicial, saldo_fin_plan_cuentas = $_saldo_final,nombre_plan_cuentas='$_nombre_plan_cuentas'";
                           $_tablas_upd = " plan_cuentas";
                           $_where_upd = " id_plan_cuentas = $_id_plan_cuentas";
                           
                           $PlanCuentas->ActualizarBy($_columnas_upd, $_tablas_upd, $_where_upd);
                                                     
                       }
                       
                      
                       
                   }else{
                       
                       echo "\n","linea--**--",$_linea,"--**--","no es array";
                       //array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"cantidad columnas no encontradas","cantidad"=>0));
                   }
                   
               }else if( $_linea > 0 && $_fila == "" ){
                   
                   echo "\n","linea--**--",$_linea,"--**--","linea vacia";
                   //array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"linea se encuentra vacia","cantidad"=>0));
               }
               $_linea++;
           }
           
           fclose($file_abierto);
           
       }
      
       
       
   }
   
   
   public function cargaFinanciero(){
       
       
       $PlanCuentas = new PlanCuentasModel();
       
       /** variables funcion */
       //$_array_plan_cuentas           = array();
       
       /**
        ****************************************************** validar si el archivo esta en el directorio ****************************************************
        **/
       $nombreArchivo   = "FINANCIEROOCT.txt";
       $rutaProcesar    = 'docs\\'.$nombreArchivo;
       
       if(file_exists($rutaProcesar)){
           
           /** comienza lectura de archivos **/
           $file_abierto   = fopen($rutaProcesar, "r");
           
           //var_dump($file_abierto); exit();
           
           /** AQUI VALIDACION DE FORMATOS */
           $_linea = 0;
           //$_linea_llena = 0;
           while(!feof($file_abierto))
           {
               $_fila = fgets($file_abierto);
               $_fila = trim($_fila);
               $_fila = trim($_fila,"\n");
               $_fila = trim($_fila,"\t");
               //echo "\n'",var_dump($_fila),"'";
               if( $_linea > 0 && $_fila != "" ){
                   
                   echo "\n",$_fila;
                   $_array_fila   = explode("\t", $_fila);
                   if( is_array($_array_fila) && sizeof( $_array_fila ) == 4){
                       
                       $_codigo_plan_cuentas    = $_array_fila[0];
                       $_nombre_plan_cuentas    = $_array_fila[1];
                       $_notas_archivo     = $_array_fila[2];
                       $_saldo_final        = $_array_fila[3];
                       
                       /** dar formato para la base a las columnas **/
                       $_codigo_plan_cuentas    = trim($_codigo_plan_cuentas," ");
                       $_codigo_final_plan_cuentas = "";
                       $i_string = 0;
                       //echo "*******lenght*****   ", strlen($_codigo_plan_cuentas);
                       while( strlen($_codigo_plan_cuentas) > 0 ){
                           $_string_cut = "";
                           
                           if( $i_string == 0 || $i_string == 1 ){
                               $_string_cut = substr($_codigo_plan_cuentas, 0, 1);
                               $_codigo_plan_cuentas = substr($_codigo_plan_cuentas, 1);
                           }else{                              
                                   
                               $_string_cut = substr($_codigo_plan_cuentas, 0, 2);
                               $_codigo_plan_cuentas = substr($_codigo_plan_cuentas, 2);
                           }
                           $_codigo_final_plan_cuentas.= $_string_cut.".";
                           $i_string++;
                       }
                       
                       $_codigo_final_plan_cuentas = trim($_codigo_final_plan_cuentas,".");
                       
                       $_nombre_plan_cuentas = preg_replace("/\"/","",$_nombre_plan_cuentas);
                       $_nombre_plan_cuentas = trim($_nombre_plan_cuentas," ");
                       $_nombre_plan_cuentas = utf8_encode ($_nombre_plan_cuentas );
                       
                       $_notas_archivo = preg_replace("/\"/","",$_notas_archivo);
                       $_notas_archivo = trim($_notas_archivo," ");
                       $_notas_archivo = utf8_encode ($_notas_archivo );                       
                       
                       //para el saldo final
                       $_saldo_final = preg_replace("/\"/","",$_saldo_final);
                       $_saldo_final  = trim($_saldo_final," ");
                       $_saldo_final  = str_replace(",", "", $_saldo_final);
                       $_saldo_final = $_saldo_final == "-" ? 0 : $_saldo_final;
                       $_saldo_final = $_saldo_final == "" ? 0 : $_saldo_final;
                       
                       if( !is_numeric( $_saldo_final ) ){
                           echo "\n \t linea no tiene valor saldo final ",$_linea;
                       }
                       
                       echo "\n linea".$_linea. " a guardar  ",$_codigo_final_plan_cuentas,"\t",$_nombre_plan_cuentas,"\t",$_saldo_final;
                       
                       /** paso validacion a insertar **/
                       
                       $codigo_buscar = $_codigo_final_plan_cuentas;
                       // primero una consulta
                       $columnas1   = " 1 existe,id_plan_cuentas";
                       $tablas1     = " public.plan_cuentas";
                       $where1      = " codigo_plan_cuentas = '$codigo_buscar' OR codigo_plan_cuentas = '".$codigo_buscar.".'";
                       $id1         = " id_plan_cuentas";
                       $rsConsulta  = $PlanCuentas->getCondiciones($columnas1, $tablas1, $where1, $id1);
                       
                       if( empty($rsConsulta) ){
                           
                           
                           $_array_nivel_plan_cuentas = explode(".", $_codigo_final_plan_cuentas);
                           $_nivel_plan_cuentas = sizeof($_array_nivel_plan_cuentas);
                           // varaibles de insertado 
                           /*$_columnas  = " INSERT INTO public.plan_cuentas(".
                               "id_entidades, codigo_plan_cuentas, nombre_plan_cuentas, id_modenas, n_plan_cuentas, id_centro_costos,".
                               "nivel_plan_cuentas, saldo_plan_cuentas, saldo_fin_plan_cuentas)";
                           $_valores    = " VALUES ( 1,'$codigo_buscar','$_nombre_plan_cuentas', 1,'A',1,'$_nivel_plan_cuentas',0,$_saldo_final)";
                           $_retorno    = " RETURNING id_plan_cuentas";
                           $_queryInsert= $_columnas.$_valores.$_retorno;
                           $_id_comprobantes = $PlanCuentas->executeInsertQuery($_queryInsert);*/
                           
                           echo "INSERT "," -> ",$codigo_buscar," <- de nivel ", $_nivel_plan_cuentas;
                           
                           
                       }else{
                           
                           $_id_plan_cuentas = $rsConsulta[0]->id_plan_cuentas;
                           
                           $_columnas_upd = " saldo_fin_plan_cuentas = $_saldo_final,nombre_plan_cuentas='$_nombre_plan_cuentas'";
                           $_tablas_upd = " plan_cuentas";
                           $_where_upd = " id_plan_cuentas = $_id_plan_cuentas";
                           
                           /*$PlanCuentas->ActualizarBy($_columnas_upd, $_tablas_upd, $_where_upd);*/
                           
                           echo "UPDATE "," -> ",$codigo_buscar," <- de nivel ", $_nivel_plan_cuentas;
                           
                       }
                      
                       
                       
                   }else{
                       
                       echo "\n","linea--**--",$_linea,"--**--","no es array";
                       //array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"cantidad columnas no encontradas","cantidad"=>0));
                   }
                   
               }else if( $_linea > 0 && $_fila == "" ){
                   
                   echo "\n","linea--**--",$_linea,"--**--","linea vacia";
                   //array_push($_archivo_errores, array("linea"=>$_linea,"error"=>"linea se encuentra vacia","cantidad"=>0));
               }
               $_linea++;
           }
           
           fclose($file_abierto);
           
       }
       
       
       
       
   }
   
   public function CuadrarCuentas(){
       
       $PlanCuentas = new PlanCuentasModel();
       
       $columnas1   = " id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas, nivel_plan_cuentas, saldo_fin_plan_cuentas";
       $tablas1     = " public.plan_cuentas";
       $where1      = " 1=1 AND nivel_plan_cuentas > 3";
       $id1         = " nivel_plan_cuentas desc, codigo_plan_cuentas";
              
       $rsConsulta1 = $PlanCuentas->getCondiciones($columnas1, $tablas1, $where1, $id1);
       
       if( !empty($rsConsulta1) ){
           
           $funcion = "fn_cuadra_plan_cuentas";
           $parametros = "";
           //
           
           foreach ( $rsConsulta1 as $res ){
               
               $parametros = "$res->id_plan_cuentas";
               $queryActualizacion = $PlanCuentas->getconsultaPG($funcion, $parametros);
               $PlanCuentas->llamarconsultaPG($queryActualizacion);
               
               $error = pg_last_error();
               if( !empty($error) ){
                   
                   echo "\n error en codigo -> ",$res->codigo_plan_cuentas;
               }
               
           }
       }
       
       
       
   }
   
   public function ActualizarCuentas(){
       
       $PlanCuentas = new PlanCuentasModel();
       
       $columnas1   = " codigo_plan_cuentas, nombre_plan_cuentas, saldo_plan_cuentas, nivel_plan_cuentas, id_plan_cuentas, n_plan_cuentas";
       $tablas1     = " public.plan_cuentas";
       $where1      = " 1=1";
       $id1         = " codigo_plan_cuentas";
       
       $rsConsulta1 = $PlanCuentas->getCondiciones($columnas1, $tablas1, $where1, $id1);
       
       if( !empty($rsConsulta1) ){           
                     
           foreach ( $rsConsulta1 as $res ){
               
               $_codigo_plan_cuentas = $res->codigo_plan_cuentas;
               $_codigo_plan_cuentas = trim($_codigo_plan_cuentas,".");
               $_id_plan_cuentas     = $res->id_plan_cuentas;
               
               $columnas = " codigo_plan_cuentas = '$_codigo_plan_cuentas'";
               $tablas   = " public.plan_cuentas";
               $where    = " id_plan_cuentas = $_id_plan_cuentas";
               
               $PlanCuentas->ActualizarBy($columnas, $tablas, $where);
            
               $error = pg_last_error();
               if( !empty($error) ){
                   
                   echo "\n error en codigo -> ",$res->codigo_plan_cuentas;
               }
               
           }
       }
       
   }
   
   public function mayorizaComprobantes(){
       
       
       
   }
   
   
   /***************************************************************** FUNCIONES DC 20200415 *******************************************************/
   /*********************** CREACION DE ARBOL DE CREACION PLANCUENTAS *****************************************************************************/
   
   public function index2(){
       
       session_start();
       $this->view_Contable("arbolPlanCuentas", array());
   }
   
   public function DrawTableCuentas(){
       
       $planCuentas = new PlanCuentasModel();
       
       $resp = null;
       
       $col1 = " id_plan_cuentas,nivel_plan_cuentas,codigo_plan_cuentas,nombre_plan_cuentas ";
       $tab1 = " public.plan_cuentas ";
       $whe1 = " id_entidades = 1 ";
       $id1  = " codigo_plan_cuentas ";
       
       $rsConsulta1 = $planCuentas->getCondiciones($col1, $tab1, $whe1, $id1);
       
       if( empty( $rsConsulta1 ) ){
           $resp['data']    = null;
           json_encode($resp);
           return;
       }
       
       /**
        * 
        * <div id="jstree">
                <!-- in this example the tree is populated from inline HTML -->
                <ul>
                  <li>1. ACTIVOS
                    <ul>
                      <li id="child_node_1">1.1. FONDOS DISPONIBLES
                      	<ul>
                      		<li id="nieto1">1.1.01. Caja</li>
                      		<li id="nieto2">1.1.02. Bancos e Intituciones</li>
                      	</ul>
                      </li>
                      <li>1.2. INVERSIONES NO PRIVATIVAS</li>
                    </ul>
                  </li>
                  <li data-jstree='{"icon":"glyphicon glyphicon-leaf"}'>2. PASIVOS</li>
                </ul>
              </div>
              <button id="btnArbol" >demo button</button>
        </div> 
        */
       
       $next    = 0;
       $prev    = 0;
       $point   = 0;
       $rssize  = sizeof($rsConsulta1);
       $auxArray= $rsConsulta1;
       $arbol   = '<div id="jstree">';
       for ($i = 0; $i < $rssize; $i++) {
           
           $next    = ($rssize == $i ) ? $i : $i+1;
           $prev    = ($i == 0) ? 0 : $i-1;
           $point   = $i;
           ;
           $arbol   .= "<ul>";
                      
       }
       
       $arbol   .= "</div>";
       
   }
   
   public function drawArbolCuentas($nivel, $resultset, $limit, $codigo){
       
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
       
       if ($codigo==""){
           
           $arbol   = ""; //variable donde se guarda el html
           $sumatoria="";
           foreach($resultset as $res){
               
               $verif1="";
               $elementos1_codigo=explode(".", $res->codigo_plan_cuentas);
               if (sizeof($elementos1_codigo)>=$nivel){
                   
                   $verif1 = join($elementos1_codigo);                 
               }
                   
               if( $res->nivel_plan_cuentas == $nivel ){
                   
                   if( $nivel <= $limit ){ 
                       
                       $nivel++;
                       $nivelclase=$nivel-1;
                       $color=$nivel-2;
                       
                       if( $color > 5 ){ $color=5; }
                       
                       $codigoPlanCuentas   = $res->codigo_plan_cuentas;
                       $nombrePlanCuentas   = $res->nombre_plan_cuentas;
                       
                       $tag = ""; //variable donde se guarda el texto del lista
                       $tag = $codigoPlanCuentas."  ".$nombrePlanCuentas;
                       $arbol   .= '<li id="cod'.$verif1.'">'.$tag; //etiqueta para inicializar la lista
                       
                       $haveSon = $this->isthereSub( $nivel, $codigoPlanCuentas, $resultset );
                                              
                       if( $haveSon ){
                           $arbol   .= "<ul>";
                           $arbol   .= $this->drawArbolCuentas( $nivel, $resultset, $limit, $codigoPlanCuentas);
                           $arbol   .= "</ul>";
                       }
                       $arbol   .= "</li>";
                       $nivel--; 
                      
                   } //end if
               }//end if
           } // end foreach
           
           //termina if si el codigo es nulo o vacio
       }else{
           
           $arbol   = ""; //variable donde guarda el html de la lista
           $sumatoria="";
           $elementos_codigo=explode(".", $codigo);
           $nivel1  = $nivel;
           $nivel1--;
           $verif   = "";
           $verif   = join( $elementos_codigo );
                      
           foreach($resultset as $res){
               
               $verif1  ="";
               $verif2  ="";
               $codigoPlanCuentas   = $res->codigo_plan_cuentas;
               $nombrePlanCuentas   = $res->nombre_plan_cuentas;
               $elementos1_codigo=explode(".", $codigoPlanCuentas );
               $verif2  = join( $elementos1_codigo );
               
               if (sizeof($elementos1_codigo)>=$nivel1){
                   for ($i=0; $i<$nivel1; $i++)
                   {
                       $verif1.=$elementos1_codigo[$i];
                   }
               }
                                             
               if( $res->nivel_plan_cuentas == $nivel && $verif == $verif1 ){
                                      
                   if(  $nivel <= $limit ){
                       $nivel++;
                       $nivelclase  = $nivel-1;
                       $color   = $nivel-2;
                       if ($color>5) { $color=5; }
                       
                       $tag = ""; //variable donde se guarda el texto del lista
                       $tag = $codigoPlanCuentas."  ".$nombrePlanCuentas;
                       $arbol   .= '<li id="cod'.$verif2.'">'.$tag; //etiqueta para inicializar la lista
                       
                       $haveSon = $this->isthereSub( $nivel, $codigoPlanCuentas, $resultset);
                       
                       if( $res->nivel_plan_cuentas > 1 ){
                           //aqui va ver si toca poner el btn para opciones
                           /**
                           $sumatoria.='<button  type="button" class="btn btn-box-tool pull-right" style="color:#5499C7" data-toggle="modal" data-target="#myModalAgregar" onclick="AgregarCuenta(&quot;'.$res->codigo_plan_cuentas.'&quot;,'.$res->id_entidades.','.$res->id_modenas.',&quot;'.$res->n_plan_cuentas.'&quot;,'.$res->id_centro_costos.','.$res->nivel_plan_cuentas.')"><i class="glyphicon glyphicon-plus"></i></button>';
                           if($res->nivel_plan_cuentas>2)
                           {
                               $sumatoria.='<button  type="button" class="btn btn btn-box-tool pull-right" style="color:#229954" data-toggle="modal" data-target="#myModalEdit" onclick="EditarCuenta('.$res->id_plan_cuentas.',&quot;'.$res->codigo_plan_cuentas.'&quot;,&quot;'.$res->nombre_plan_cuentas.'&quot;)"><i class="glyphicon glyphicon-pencil"></i></button>';
                           }
                            */
                       }
                       
                       if( $haveSon ){
                           
                           $arbol   .= "<ul>";
                           $arbol   .= $this->drawArbolCuentas( $nivel, $resultset, $limit, $codigoPlanCuentas );
                           $arbol   .= "</ul>";
                       }
                       
                       $arbol   .= "</li>";
                       
                       $nivel--;                   
                   
                   }
               }
           }
       }
       return $arbol;
   }
   
   public function isthereSub($nivel, $codigo, $resultado){
       
       $elementos_codigo=explode(".", $codigo);
       $nivel1  =   $nivel;
       $nivel1--;
       $verif="";
       
       $verif   .= join($elementos_codigo);       
       
       foreach ($resultado as $res){
       
           $verif1="";
           $elementos1_codigo=explode(".", $res->codigo_plan_cuentas);
           
           if (sizeof($elementos1_codigo)>=$nivel1){
               
               for ($i=0; $i<$nivel1; $i++)
               {
                   $verif1.=$elementos1_codigo[$i];
               }
           }          
           
           if( $res->nivel_plan_cuentas ==  $nivel && $verif    ==  $verif1 ){
               return true;
           }
       }
       return false;
   }
   
   public function index3(){
       
       $planCuentas = new PlanCuentasModel();
       
       $col1    = " id_plan_cuentas,nivel_plan_cuentas,codigo_plan_cuentas,nombre_plan_cuentas ";
       $tab1    = " public.plan_cuentas ";
       $whe1    = " id_entidades = 1 ";
       $id1     = " codigo_plan_cuentas";
       $rsConsulta1 = $planCuentas->getCondiciones($col1, $tab1, $whe1, $id1);
            
       
       $col2    = " MAX(nivel_plan_cuentas) maximo";
       $tab2    = " public.plan_cuentas";
       $whe2    = " 1 = 1 ";
       $rsConsulta2 = $planCuentas->getCondicionesSinOrden($col2, $tab2, $whe2, "");
       
       $max = $rsConsulta2[0]->maximo;
       $data    = $rsConsulta1;
       
       echo $max;
       
       $html    = '<div id="arbolplancuentas" >';
       $html    .= $this->drawArbolCuentas(1, $data, $max, "");
       $html    .= '</div>';
       
       echo $html;
       
       
   }
   
   public function index4(){
       
       $planCuentas = new PlanCuentasModel();
       $resp  = null;
              
       try {
           
           $col1    = " id_plan_cuentas,nivel_plan_cuentas,codigo_plan_cuentas,nombre_plan_cuentas ";
           $tab1    = " public.plan_cuentas ";
           $whe1    = " id_entidades = 1 ";
           $id1     = " codigo_plan_cuentas";
           $rsConsulta1 = $planCuentas->getCondiciones($col1, $tab1, $whe1, $id1);           
           
           $col2    = " MAX(nivel_plan_cuentas) maximo";
           $tab2    = " public.plan_cuentas";
           $whe2    = " 1 = 1 ";
           $rsConsulta2 = $planCuentas->getCondicionesSinOrden($col2, $tab2, $whe2, "");
           
           $max = $rsConsulta2[0]->maximo;
           $data    = $rsConsulta1;
           
           if( !empty( error_get_last() ) || !empty( pg_last_error() ) ){
               throw new Exception("REVISAR CONSULTAS A LA BASE");
           }
           $html    = "<ul>";
           $html    .= $this->drawArbolCuentas(1, $data, $max, "");
           $html    .= "</ul>";
          
           if( !empty( error_get_last() ) ){
               throw new Exception("ERROR EN LA GRAFICACION DEL PLAN CUENTAS");
           }
           
           $resp['data']=$html;
           $resp['estatus']="OK";         
           
       } catch (Exception $e) {
           $buffer =  error_get_last();
           $resp['icon'] = isset($resp['icon']) ? $resp['icon'] : "error";
           $resp['mensaje'] = $e->getMessage();
           $resp['msgServer'] = $buffer; //buscar guardar buffer y guaradr en variable
           $resp['estatus'] = "ERROR";
       }
       
       error_clear_last();
       if (ob_get_contents()) ob_end_clean();
       
       echo json_encode($resp);
   }
   
  
 }
?>