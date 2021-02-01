<?php
class TributarioImpuestoSuperavitController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
    
    session_start();
	
	if (isset($_SESSION['id_usuarios']) )
	{
		
		$usuarios = new UsuariosModel();

		$nombre_controladores = "TributarioImpuestoSuperavit";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $usuarios->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
		   	
		    $this->view_tributario("ImpuestoSuperavit",array(
				    ""=>""
			
				));
			
		}
		else
		{
		    $this->view("Error",array(
					"resultado"=>"No tiene Permisos de Acceso a Usuarios"
		
			));
		
		}
		
	
    	}
    	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
		
	}
	
	
	
	
	
	public function consulta_superavit_personal(){
	    
	    session_start();
	   
	    $contribucion = new CoreContribucionModel();
	    $where_to="";

	    
	    
	    
	    $columnas = "pt.id_participes, pt.cedula_participes, pt.apellido_participes, pt.nombre_participes,
	    '05' as tipo_identificacion,
	    pt.correo_participes, pt.celular_participes,  RTRIM(LTRIM(pt.direccion_participes)) as direccion_participes, cc.baseimponible, cc.c, cci.id_contribucion, coalesce(cci.valor_personal,0) as valor_personal";
	    $tablas = "(
	        select c.id_participes, coalesce(sum(valor_personal_contribucion),0) as baseimponible, coalesce(sum(valor_patronal_contribucion),0) as c from core_contribucion c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (49 , 50) and c.id_estatus=1 group by c.id_participes having sum(valor_personal_contribucion)>0
	        ) cc
            inner join core_participes pt on cc.id_participes=pt.id_participes
	        left join(
	            select c.id_participes, c.id_contribucion, coalesce(round(sum(valor_personal_contribucion)*(-1),2),0) as valor_personal  from core_contribucion c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (10 , 12) and c.id_estatus=1 group by c.id_participes, c.id_contribucion
	            ) cci
	            on cc.id_participes=cci.id_participes";
	    $where = "pt.retencion_generada_impuestos_cta_ind='FALSE'";
	    
	    $id       = "pt.cedula_participes";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	       	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (pt.cedula_participes LIKE '%".$search."%' OR pt.nombre_participes LIKE '%".$search."%' OR pt.apellido_participes LIKE '%".$search."%')";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$contribucion->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	       
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$contribucion->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
	            $html.= "<table id='tabla_personal' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor</th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            foreach ($resultSet as $res)
	            {
	                
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->valor_personal.'</td>';
	                
	                
	                $html.='</tr>';
	                
	                
	            }
	            
	         
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="pull-left"><button id="btn_personal" class="btn btn-primary" type="button" data-toggle="modal" data-target="#mod_personal" ><i class="glyphicon glyphicon-plus"> Procesar</i></button></div>';
	            
	            
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_superavit("index.php", $page, $total_pages, $adjacents,"load_personal").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay Impuestos Superavit Personal registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
	public function consulta_superavit_patronal(){
	    
	   session_start();
	   $contribucion_pagada = new CoreContribucionPagadaModel();
	   
	   $where_to="";
	    
	   
	   
	   $columnas = "pt.id_participes, pt.cedula_participes, pt.apellido_participes, pt.nombre_participes,
	    '05' as tipo_identificacion,
	    pt.correo_participes, pt.celular_participes,  RTRIM(LTRIM(pt.direccion_participes)) as direccion_participes, cc.baseimponible, cc.c, cci.id_contribucion_pagada, coalesce(cci.valor_patronal,0) as valor_patronal";
	   $tablas = "(
	        select c.id_participes, coalesce(sum(valor_personal_contribucion_pagada),0) as baseimponible, coalesce(sum(valor_patronal_contribucion_pagada),0) as c from core_contribucion_pagada c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (49 , 50) and c.id_estatus=1 group by c.id_participes having sum(valor_personal_contribucion_pagada)>0
	        ) cc
            inner join core_participes pt on cc.id_participes=pt.id_participes
	        left join(
	            select c.id_participes, c.id_contribucion_pagada, coalesce(round(sum(valor_personal_contribucion_pagada)*(-1),2),0) as valor_patronal  from core_contribucion_pagada c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (10 , 12) and c.id_estatus=1 group by c.id_participes, c.id_contribucion_pagada
	            ) cci
	            on cc.id_participes=cci.id_participes";
	   $where = "pt.retencion_generada_impuestos_cta_des='FALSE'";
	   
	   $id       = "pt.cedula_participes";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	       
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (pt.cedula_participes LIKE '%".$search."%' OR pt.nombre_participes LIKE '%".$search."%' OR pt.apellido_participes LIKE '%".$search."%' )";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$contribucion_pagada->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$contribucion_pagada->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
	            $html.= "<table id='tabla_patronal' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor</th>';
	             $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->valor_patronal.'</td>';
	                
	                $html.='</tr>';
	            }
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            
	            $html.='<div class="pull-left"><button id="btn_patronal" class="btn btn-primary" type="button" data-toggle="modal" data-target="#mod_patronal" ><i class="glyphicon glyphicon-plus"> Procesar</i></button></div>';
	            
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_superavit("index.php", $page, $total_pages, $adjacents,"load_patronal").'';
	            $html.='</div>';
	            
	            
	            
	            
	            
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay Impuestos Superavit Personal registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
	
	
	
	
	public function consulta_superavit_cesantes(){
	    
	    session_start();
	    $superavit_pagos = new CoreSuperavitPagosModel();
	    
	    $where_to="";
	    
	    
	    
	    $columnas = "pt.id_participes, '05' as tipo_identificacion,
                    (select pt1.cedula_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as cedula_participes,
                    (select pt1.apellido_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as apellido_participes,
                    (select pt1.nombre_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as nombre_participes,
                    (select pt1.correo_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as correo_participes,
                    (select pt1.celular_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as celular_participes,
                    (select RTRIM(LTRIM(pt1.direccion_participes)) from core_participes pt1 where pt1.id_participes=pt.id_participes) as direccion_participes,
                    coalesce(round(sum(cc.ir_patronal_cobrado_ctaind_superavit_pagos+cc.ir_patronal_cobrado_cxpdf_superavit_pagos)*(-1),2),0) as valor_patronal,
                    coalesce(sum(cc.ctaind_patronal_superavit_pagos+cc.cxpdf_patronal_superavit_pagos),0) as baseimponible
                    ";
	    $tablas = "core_participes pt
                    inner join core_superavit_pagos cc on pt.id_participes=cc.id_participes
                    ";
	    $where = "cc.diario_origen_superavit_pagos in (-20202020) and pt.retencion_generada_impuestos_cta_ces='FALSE' and cc.id_estatus=1";
	    
	    $grupo = "pt.id_participes";
	    $condicion_grupo = "sum(cc.ctaind_patronal_superavit_pagos+cc.cxpdf_patronal_superavit_pagos)>0";
	    $id       = "pt.id_participes";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (cedula_participes LIKE '%".$search."%' OR nombre_participes LIKE '%".$search."%' OR apellido_participes LIKE '%".$search."%' )";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$superavit_pagos->getCondiciones_grupo($columnas, $tablas, $where_to, $grupo.' HAVING '.$condicion_grupo, $id);
	        $cantidadResult=count($resultSet);
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$superavit_pagos->getCondicionesGrupCondiOrderPag($columnas, $tablas, $where_to, $grupo, $condicion_grupo, $id, $limit);
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
	            $html.= "<table id='tabla_cesantes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Base Imponible</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor Retención</th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->baseimponible.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->valor_patronal.'</td>';
	                
	                $html.='</tr>';
	            }
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            
	            $html.='<div class="pull-left"><button id="btn_cesantes" class="btn btn-primary" type="button" data-toggle="modal" data-target="#mod_cesantes" ><i class="glyphicon glyphicon-plus"> Procesar</i></button></div>';
	            
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_superavit("index.php", $page, $total_pages, $adjacents,"load_cesantes").'';
	            $html.='</div>';
	        
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay Impuestos Superavit Patronal registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
	
	
	
	
	public function consulta_cesantias_patronales(){
	    
	    session_start();
	    $superavit_pagos = new CoreSuperavitPagosModel();
	    
	    $base_calcuo_liquidacion_detalle = 0;
	    $valor_liquidacion_detalle = 0;
	    
	    $where_to="";
	    
	    
	    $columnas = "clc.id_liquidacion_cabeza,     cp.id_participes, clc.fecha_pago_carpeta_liquidacion_cabeza, cp.cedula_participes ,
	                 cp.apellido_participes , cp.nombre_participes , cld.motivo_liquidacion_detalle ,
	                  cld.porcentaje_liquidacion_detalle , cld.base_calcuo_liquidacion_detalle , cld.valor_liquidacion_detalle * -1 as valor_liquidacion_detalle";
	    $tablas = "core_participes cp , core_liquidacion_cabeza clc ,
	    core_liquidacion_detalle cld";
	    $where = "clc.id_participes = cp.id_participes
            	    and clc.id_liquidacion_cabeza = cld.id_liquidacion_cabeza
            	      and clc.retencion_liquidacion_cabeza='false'
            	    and clc.id_estado_prestaciones in (3,4)
            	    and clc.id_tipo_prestaciones not in (3)
            	    and cld.id_tipo_pago_liquidacion = 8
            	    and (cld.motivo_liquidacion_detalle LIKE '%Patronal%' or cld.motivo_liquidacion_detalle LIKE '%PATRONAL%' )";
	    
	    //$grupo = "cp.id_participes";
	    //$condicion_grupo = "sum(cld.base_calcuo_liquidacion_detalle+cld.valor_liquidacion_detalle)>0";
	    $id       = "clc.fecha_pago_carpeta_liquidacion_cabeza";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    $search_fechadesde =  (isset($_REQUEST['search_fechadesde'])&& $_REQUEST['search_fechadesde'] !=NULL)?$_REQUEST['search_fechadesde']:'';
	    $search_fechahasta =  (isset($_REQUEST['search_fechahasta'])&& $_REQUEST['search_fechahasta'] !=NULL)?$_REQUEST['search_fechahasta']:'';
	    
	    
	    
	    if($action == 'ajax')
	    {
	        
	        $where1="";
	        if(!empty($search_fechadesde)  && !empty($search_fechahasta)    ){
	            
	                
	            $where1.=" AND  clc.fecha_pago_carpeta_liquidacion_cabeza BETWEEN '$search_fechadesde' AND  '$search_fechahasta'  ";
       
	            
	        }
	        
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1.=" AND (cp.cedula_participes LIKE '%".$search."%' OR cp.nombre_participes LIKE '%".$search."%' OR cp.apellido_participes LIKE '%".$search."%' ) ";
	            
	            
	        }
	        
	        
	        
	        $where_to=$where.$where1;
	        
	        $html="";
	        $resultSet=$superavit_pagos->getCondiciones($columnas, $tablas, $where_to, $id);
	        $cantidadResult=count($resultSet);
	        
	    
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 100; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$superavit_pagos->getCondicionesPag($columnas, $tablas, $where_to,  $id, $limit);
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
	            $html.= "<table id='tabla_cesantes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Id</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha Pago</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Motivo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Base Imponible</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Porcentaje</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor Retención</th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->id_liquidacion_cabeza.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_pago_carpeta_liquidacion_cabeza.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->motivo_liquidacion_detalle.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->base_calcuo_liquidacion_detalle.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->porcentaje_liquidacion_detalle.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->valor_liquidacion_detalle .'</td>';
	                
	                $html.='</tr>';
	                
	                
	                $base_calcuo_liquidacion_detalle =  $base_calcuo_liquidacion_detalle + $res->base_calcuo_liquidacion_detalle;
	                $valor_liquidacion_detalle       =  $valor_liquidacion_detalle + $res->valor_liquidacion_detalle;
	                
	            }
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="pull-left"><button id="btn_cesantias_patronales" class="btn btn-primary" type="button" data-toggle="modal" data-target="#mod_cesantias_patronales" ><i class="glyphicon glyphicon-plus"> Procesar</i></button></div>';
	            $html.='<div class="pull-left"><label  class="col-sm-3 col-form-label">Total Base: '. $base_calcuo_liquidacion_detalle . '</label> </div>';
	            $html.='<div class="pull-left"><label  class="col-sm-3 col-form-label">Total Ret: '.  $valor_liquidacion_detalle. '</label> </div>';
	            
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_superavit("index.php", $page, $total_pages, $adjacents,"load_cesantes").'';
	            
	            $html.='</div>';
	            
	            
	            
	            
	            
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay Impuestos Superavit Patronal registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
	
	
	
	
	public function paginate_superavit($reload, $page, $tpages, $adjacents,$funcion='') {
	    
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
	
		
	
	
	
	
	public function cargar_personal_a_procesar(){
	
	session_start();
	
	$contribucion = new CoreContribucionModel();
	
	
	$cantidad_personal=  (isset($_REQUEST['cantidad_personal'])&& $_REQUEST['cantidad_personal'] !=NULL)?$_REQUEST['cantidad_personal']:0;
	$html="";
	
	if($cantidad_personal>0){
	    
	    
	    
	    
	    $columnas = "cci.id_contribucion, pt.id_participes";
	    $tablas = "(
	        select c.id_participes, coalesce(sum(valor_personal_contribucion),0) as baseimponible, coalesce(sum(valor_patronal_contribucion),0) as c from core_contribucion c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (49 , 50) and c.id_estatus=1 group by c.id_participes having sum(valor_personal_contribucion)>0
	        ) cc
            inner join core_participes pt on cc.id_participes=pt.id_participes
	        left join(
	            select c.id_participes, c.id_contribucion, coalesce(round(sum(valor_personal_contribucion)*(-1),2),0) as valor_personal  from core_contribucion c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (10 , 12) and c.id_estatus=1 group by c.id_participes, c.id_contribucion
	            ) cci
	            on cc.id_participes=cci.id_participes";
	    $where = "pt.retencion_generada_impuestos_cta_ind='FALSE'";
	    
	    $id       = "pt.cedula_participes";
	    $limit = "limit ".$cantidad_personal;
	    
	    $resultSet=$contribucion->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
	    
	   
	    
	    if(!empty($resultSet)){
	        
	        $i=0;
	        
	        
	        $html.='<div class="box">';
	        $html.='<div class="box-body table-responsive pad">';
	        $html.='<table class="table table-bordered">';
	        $html.='<tr>';
	        $html.='<td>';
	        $html.='<div class="btn-group">';
	        foreach ($resultSet as $res){
	            $i++;
	            
	            $html.='<button type="button" class="btn btn-default" id="id_'.$res->id_contribucion.'" value="'.$res->id_contribucion.'">'.str_pad($i, 2, "0", STR_PAD_LEFT).'</button>';
	            
	        }
	        
	        $html.='</div>';
	        $html.='</td>';
	        $html.='</tr>';
	        $html.='</table>';
	        $html.='</div>';
	        $html.='</div>';
	        
	        
	        
	        $resultado=array();
	        
	        array_push($resultado, $resultSet, $html);
	        
	        echo json_encode($resultado);
	        
	    }
	    
	}
	
}
	
	
	
	


public function Procesar_Personal(){
    
  
    $contribucion = new CoreContribucionModel();
    $cuentasPagar  = new CuentasPagarModel();
    $respuesta = array();
   
   
    $_cantidad_personal =(isset($_POST['cantidad_personal'])) ? $_POST['cantidad_personal'] : 0;
    $_array_procesar_personal =(isset($_POST['array_procesar_personal'])) ? $_POST['array_procesar_personal'] : 0;
    
    $html="";
    $correcto=0;
    $incorrecto=0;
    $xml_error=0;
    if(!empty($_cantidad_personal) && !empty($_array_procesar_personal) ){
        
        
        $i=0;
        $errorXml = false;
        foreach ($_array_procesar_personal as $value) {
           
            $i++;
            $_id_contribucion=  $value['id_contribucion'];
            $_id_participes = $value['id_participes'];
          
       
        $resp = $this->genXmlRetencion($_id_contribucion, $_id_participes);
        
        
        
       
        
        $respuesta['xml'] = '';
        $respuesta['file']= $resp;
        
      
        
        
        /// significa que hubo un erro al generar el xml
        if( $resp['error'] === true ){
           
            
            $xml_error=$xml_error+1;
            
            $errorXml = true;
            if( array_key_exists('mensaje', $resp) && $resp['mensaje'] == "XML NO GENERADO" ){
               
                $respuesta['xml'] = 'XML NO GENERADO';
            }
            
            
            if (array_key_exists('claveAcceso', $resp) && strlen( $resp['claveAcceso'] ) == 49 ) {
                
                $respuesta['xml'] = 'DATOS xml EN BD No fueron ingresados';
                
                $claveAcceso = $resp['claveAcceso'];
                $_columnaActualizar = " autorizado_retenciones = false ";
                $_tablaActualizar   = " tri_retenciones";
                $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                
                $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
            }
            
            
            
        }else{
            
            $respuesta['xml'] = " ARCHIVO ENTRO XML";
            
            if( array_key_exists('mensaje', $resp) && $resp['mensaje'] == "XML GENERADO" ){
               
                $errorXml = false;
                
                $respuesta['xml'] = " ARCHIVO ENTRO XML IF";
                
                $clave = ( array_key_exists('claveAcceso', $resp) ) ? $resp['claveAcceso'] : '' ;
                
                require_once __DIR__ . '/../vendor/autoload.php';
                
                $config = $this->getConfigXml();
                
                $comprobante = new \Shara\ComprobantesController($config); 
               
                $xml = file_get_contents($config['generados'] . DIRECTORY_SEPARATOR . $clave.'.xml', FILE_USE_INCLUDE_PATH);
                
                $aux = $comprobante->validarFirmarXml($xml, $clave);
                
             
                
                $respuesta['Archivo'] = "";
                $respuesta['xml'] = $aux;
                
                if($aux['error'] === false){
                    
                    $Envioresp = $comprobante->enviarXml($clave);
                    //$aux['recibido'] = true; //para pruebas
                    
                    if($Envioresp['recibido'] === true){
                        
                        $respuesta['xml'] = " Archivo Xml RECIBIDO";
                        
                        $finalresp = $comprobante->autorizacionXml($clave);
                        //$finalresp = null;. //para pruebas
                        //$finalresp['error'] = false; //para pruebas
                        if($finalresp['error'] === true ){
                           
                            $respuesta['xml'] = " Archivo Xml RECIBIDO NO AUTORIZADO";
                            $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;
                            $errorXml = true;
                        }else{
                            
                            $respuesta['xml'] = " Archivo Xml RECIBIDO AUTORIZADO";
                            $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;
                            
                            $fechaAutorizado = $finalresp['fecauto'];
                            
                            
                        }
                        
                        
                    }else{
                        
                        $respuesta['xml'] = " Archivo Xml NO RECIBIDO";
                        $respuesta['Archivo'] = ( array_key_exists('mensaje', $Envioresp) ) ? $Envioresp['mensaje'] : '' ;
                        $errorXml = true;
                    }
                    
                }else{
                    
                    $respuesta['xml'] = " Archivo Xml NO FIRMADO";
                    $respuesta['Archivo'] = ( array_key_exists('mensaje', $aux) ) ? $aux['mensaje'] : '' ;
                    $errorXml = true;
                }
                
               
                if( $errorXml ){
                    
                    $claveAcceso = $resp['claveAcceso'];
                    $_columnaActualizar = " autorizado_retenciones = false ";
                    $_tablaActualizar   = " tri_retenciones";
                    $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                    $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                    
                    
                }
                
            }else{
                $respuesta['xml'] = " ARCHIVO NO ENTRO XML";
            }
        }
        
        
        
        
        if( $errorXml ){
            
            /*
            $respuesta['icon'] = 'warning';
            $respuesta['mensaje'] = "Retención Rechazada";
            $respuesta['estatus'] = 'ERROR';
            echo json_encode($respuesta);
            */
            $incorrecto=$incorrecto+1;
            
            
            
            
            
            //actualizar el codigo de retencion
            $_actCol = " valor_consecutivos = valor_consecutivos - 1, numero_consecutivos = LPAD( ( valor_consecutivos - 1)::TEXT,espacio_consecutivos,'0')";
            $_actTab = " consecutivos ";
            $_actWhe = " nombre_consecutivos = 'RETENCION' ";
            $resultadoAct =  $contribucion->ActualizarBy($_actCol, $_actTab, $_actWhe);
            
            if( $resultadoAct == -1 ){
                return array('error' => true, 'mensaje' => 'Numero Retencion no actualizada');
            }
            
            
            
            
            
            $_triCol1  = " id_tri_retenciones";
            $_triTab1  = " tri_retenciones";
            $_triWhe1  = " infotributaria_claveacceso = '$claveAcceso'";
            $_rstriConsulta1   = $cuentasPagar->getCondicionesSinOrden($_triCol1, $_triTab1, $_triWhe1, "");
            
            if( !empty($_rstriConsulta1) ){
                
                $_id_tri_retenciones       = $_rstriConsulta1[0]->id_tri_retenciones;
                
                
                $resuldelte=$cuentasPagar->eliminarFila("tri_retenciones_detalle", "id_tri_retenciones='$_id_tri_retenciones'");
                $resuldelte1=$cuentasPagar->eliminarFila("tri_retenciones", "id_tri_retenciones='$_id_tri_retenciones'");
                
                
                
                
            }
            
            
            
            
            
            
        }else{
            
            
            //procesado correctamente
            
            $claveAcceso = $resp['claveAcceso'];
            $_columnaActualizar = " fecha_autorizacion = '$fechaAutorizado' ";
            $_tablaActualizar   = " tri_retenciones";
            $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
            $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
            
            
            
          
            $_columnaActualizar1 = " retencion_generada_impuestos_cta_ind = 'TRUE' ";
            $_tablaActualizar1   = " core_participes";
            $_whereActualizar1   = " id_participes='$_id_participes'";
            $cuentasPagar->ActualizarBy($_columnaActualizar1, $_tablaActualizar1, $_whereActualizar1);
            
            
            
            $correcto=$correcto+1;
            /*
            $respuesta['icon'] = 'success';
            $respuesta['mensaje'] = "Retención Generada Correctamente";
            $respuesta['estatus'] = 'OK';
            
            echo json_encode($respuesta);
            
            */
        }
      
        
        
            
            
        }
            
            
        
        
        //aqui envio respuesta
        
      
        foreach ($_array_procesar_personal as $value1) {
            
            $_id_contribucion=  $value1['id_contribucion'];
            $_id_participes = $value1['id_participes'];
            
        
            $columnas = "c.id_contribucion, c.id_participes, p.retencion_generada_impuestos_cta_ind";
            $tablas = "core_contribucion c
	    inner join core_participes p on c.id_participes=p.id_participes";
            $where = "c.id_contribucion_tipo in (10,12) and c.id_estatus=1
	    and c.id_liquidacion=0 and c.id_distribucion in (111,
                                                        112,
                                                        113,
                                                        114,
                                                        115,
                                                        116,
                                                        117,
                                                        118,
                                                        119,
                                                        120,
                                                        121,
                                                        122)
       AND  c.id_participes='$_id_participes'";
            
            $id       = "p.cedula_participes";
           
            
            $resultSet=$contribucion->getCondiciones($columnas, $tablas, $where, $id);
            
            
            
            if(!empty($resultSet)){
                
                $i1=0;
                
                
                $html.='<div class="box">';
                $html.='<div class="box-body table-responsive pad">';
                $html.='<table class="table table-bordered">';
                $html.='<tr>';
                $html.='<td>';
                $html.='<div class="btn-group">';
                foreach ($resultSet as $res){
                    $i1++;
                    
                    if($res->retencion_generada_impuestos_cta_ind=='t'){
                        
                        $html.='<button type="button" class="btn btn-success" id="id_'.$res->id_contribucion.'" value="'.$res->id_contribucion.'">'.str_pad($i1, 2, "0", STR_PAD_LEFT).'</button>';
                        
                    }else{
                        
                        $html.='<button type="button" class="btn btn-danger" id="id_'.$res->id_contribucion.'" value="'.$res->id_contribucion.'">'.str_pad($i1, 2, "0", STR_PAD_LEFT).'</button>';
                        
                    }
                }
                    
                    
                    
                }
                
                $html.='</div>';
                $html.='</td>';
                $html.='</tr>';
                $html.='</table>';
                $html.='</div>';
                $html.='</div>';
                
            
            
        
        }
        
        
        
        $respuesta['icon'] = 'success';
        $respuesta['mensaje'] = "Retención Generada Correctamente";
        $respuesta['estatus'] = 'OK';
        $respuesta['html'] = $html;
        $respuesta['correcto'] = $correcto;
        $respuesta['incorrecto'] = $incorrecto;
        $respuesta['xml_error'] = $xml_error;
        
        
        echo json_encode($respuesta);
        
        
        
        
        
    }else{
        
        
        $respuesta['icon'] = 'warning';
        $respuesta['mensaje'] = "No se pudo procesar";
        $respuesta['estatus'] = 'ERROR';
        $respuesta['html'] = $html;
        $respuesta['correcto'] = $correcto;
        $respuesta['incorrecto'] = $incorrecto;
        $respuesta['xml_error'] = $xml_error;
        echo json_encode($respuesta);
        
    }
    
    
    
    
    
    
    
    
    
    
 
    
}

	





public function genXmlRetencion($_id_contribucion, $_id_participes){
    
   // session_start();
    $contribucion = new CoreContribucionModel();
    $cuentasPagar  = new CuentasPagarModel();
    
  
    //codigo 323
    
        //impuestos de tipo retencion
         
    $col1 = "pt.id_participes, pt.cedula_participes, concat( RTRIM(LTRIM(pt.apellido_participes)),' ', RTRIM(LTRIM(pt.nombre_participes))) as nombres_participes,
	    '05' as tipo_identificacion,
	    pt.correo_participes, pt.celular_participes,  RTRIM(LTRIM(pt.direccion_participes)) as direccion_participes, cc.baseimponible, cc.c, cci.id_contribucion, coalesce(cci.valor_personal,0) as valor_personal";
    $tab1 = "(
	        select c.id_participes, coalesce(sum(valor_personal_contribucion),0) as baseimponible, coalesce(sum(valor_patronal_contribucion),0) as c from core_contribucion c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (49 , 50) and c.id_estatus=1  group by c.id_participes having sum(valor_personal_contribucion)>0
	        ) cc
            inner join core_participes pt on cc.id_participes=pt.id_participes
	        left join(
	            select c.id_participes, c.id_contribucion, coalesce(round(sum(valor_personal_contribucion)*(-1),2),0) as valor_personal  from core_contribucion c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (10 , 12) and c.id_estatus=1 group by c.id_participes, c.id_contribucion
	            ) cci
	            on cc.id_participes=cci.id_participes";
    $whe1 = "pt.id_participes= '$_id_participes' and pt.retencion_generada_impuestos_cta_ind='FALSE'";
        
    $id1       = "pt.cedula_participes";
        
        
        
        
        
        $rsConsulta1   = $contribucion->getCondiciones($col1, $tab1, $whe1, $id1); //array de impuestos
        
        
        
        
      
        
       
        //datos de la empresa
        $col3  = " id_entidades, ruc_entidades, nombre_entidades, telefono_entidades, direccion_entidades, ciudad_entidades, razon_social_entidades";
        $tab3  = " entidades";
        $whe3  = " 1 = 1
               AND nombre_entidades = 'CAPREMCI'";
        $id3   = " creado";
        $rsConsulta3   = $contribucion->getCondiciones($col3, $tab3, $whe3, $id3); //array de empresa
        
       
        
        
        //datos de consecutivo
        $col4  = " LPAD( valor_consecutivos::TEXT,espacio_consecutivos,'0') secuencial";
        $tab4  = " consecutivos";
        $whe4  = " 1 = 1
               AND nombre_consecutivos = 'RETENCION'";
        $id4   = " creado";
        $rsConsulta4   = $contribucion->getCondiciones($col4, $tab4, $whe4, $id4); //array de empresa
        
       
        
        //actualizar el codigo de retencion
        $_actCol = " valor_consecutivos = valor_consecutivos + 1, numero_consecutivos = LPAD( ( valor_consecutivos + 1)::TEXT,espacio_consecutivos,'0')";
        $_actTab = " consecutivos ";
        $_actWhe = " nombre_consecutivos = 'RETENCION' ";
        $resultadoAct =  $contribucion->ActualizarBy($_actCol, $_actTab, $_actWhe);
        
        if( $resultadoAct == -1 ){
            return array('error' => true, 'mensaje' => 'Numero Retencion no actualizada');
        }
        
        
        /** validacion de parametros **/
        if( empty($rsConsulta1) || empty($rsConsulta3) || empty($rsConsulta4) ){
            //echo "Error validacion llego ";
            return array('error' => true, 'mensaje' => 'Consultas no contiene todos los datos');
        }
        
        
        
        
        
        
        /** AUX de VARIABLES **/
     
        
        $_fechaDocumento =    "28".'/'."02".'/'."2020";
        
        /** VARIABLES DE XML **/
        $_ambiente = 2; //1 pruebas  2 produccion
        $_tipoEmision = 1; //1 emision normal deacuerdo a la tabla 2 SRI
        $_rucEmisor  = $rsConsulta3[0]->ruc_entidades;
        $_razonSocial = $rsConsulta3[0]->razon_social_entidades;
        $_nomComercial= $rsConsulta3[0]->nombre_entidades;
        $_codDocumento= "07"; // referenciado a la tabla 4 del sri
        $_establecimiento = "001"; //definir de la estructura  001-001-000000 -- factura !!!!------>NOTA
        $_puntoEmision    = "001"; //solo existe un establecimiento
        $_secuencial      = $rsConsulta4[0]->secuencial;   // es un secuencial tiene que definirse
        $_dirMatriz       = $rsConsulta3[0]->direccion_entidades;
        $_fechaEmision    =  $_fechaDocumento;//definir la fecha
        $_dirEstablecimiento   = $rsConsulta3[0]->direccion_entidades;
        
        // /** informacion rtencion **/ //datos obtener de la tabla proveedores
        $_contriEspecial  = "624";  //numero definir para otra empresa !!!!------>NOTA ----- OJO -- tomara de la tabla entidades
        $_obligadoContabilidad = "SI"; //TEXTO definir para otra empresa !!!!------>NOTA ----- OJO --tomara de la tabla entidades
        $_tipoIdentificacionRetenido   = $rsConsulta1[0]->tipo_identificacion; // deacuerdo a la tabla 7 --> ruc 04
        $_razonSocialRetenido  = $rsConsulta1[0]->nombres_participes;
        $_identificacionSujetoRetenido = $rsConsulta1[0]->cedula_participes;
        $_periodoFiscal        = "02".'/'."2020";
        
        $_claveAcceso = $this->genClaveAcceso($_fechaEmision, $_rucEmisor, $_ambiente, $_establecimiento, $_puntoEmision, $_secuencial, $_tipoEmision);
        
       
        
        
        
        if( $_claveAcceso == "" || strlen($_claveAcceso) != 49 ){
            return array('error' => true, 'mensaje' => 'Clave de acceso no generada');
        }
        
        $texto = "";
        $texto .='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $texto .= '<comprobanteRetencion id="comprobante" version="1.0.0">';
        $texto .= '<infoTributaria>';
        $texto .= '<ambiente>'.$_ambiente.'</ambiente>'; //conforme a la tabla 4
        $texto .= '<tipoEmision>'.$_tipoEmision.'</tipoEmision>'; //conforme a la tabla 2
        $texto .= '<razonSocial>'.htmlspecialchars($_razonSocial).'</razonSocial>';
        $texto .= '<nombreComercial>'.htmlspecialchars($_nomComercial).'</nombreComercial>';
        $texto .= '<ruc>'.$_rucEmisor.'</ruc>';
        $texto .= '<claveAcceso>'.$_claveAcceso.'</claveAcceso>'; //conforme a la tabla 1
        $texto .= '<codDoc>'.$_codDocumento.'</codDoc>'; //conforme a la tabla 3
        $texto .= '<estab>'.$_establecimiento.'</estab>';
        $texto .= '<ptoEmi>'.$_puntoEmision.'</ptoEmi>';
        $texto .= '<secuencial>'.$_secuencial.'</secuencial>';
        $texto .= '<dirMatriz>'.$_dirMatriz.'</dirMatriz>';
        $texto .= '</infoTributaria>';
        
        $texto .= '<infoCompRetencion>';
        $texto .= '<fechaEmision>'.$_fechaEmision.'</fechaEmision>'; //conforme al formato -- dd/mm/aaaa
        $texto .= '<dirEstablecimiento>'.$_dirEstablecimiento.'</dirEstablecimiento>';
        $texto .= '<contribuyenteEspecial>'.$_contriEspecial.'</contribuyenteEspecial>';
        $texto .= '<obligadoContabilidad>'.$_obligadoContabilidad.'</obligadoContabilidad>';
        $texto .= '<tipoIdentificacionSujetoRetenido>'.$_tipoIdentificacionRetenido.'</tipoIdentificacionSujetoRetenido>'; // conforme a la tabla 6
        $texto .= '<razonSocialSujetoRetenido>'.$_razonSocialRetenido.'</razonSocialSujetoRetenido>';
        $texto .= '<identificacionSujetoRetenido>'.$_identificacionSujetoRetenido.'</identificacionSujetoRetenido>';
        $texto .= '<periodoFiscal>'.$_periodoFiscal.'</periodoFiscal>'; //conforme a formato mm/aaaa
        $texto .= '</infoCompRetencion>';
        
        $texto .= '<impuestos>'; //aqui comienza el foreach de impuestos
        
        /** VARIABLES PARA CADA IMPUESTO **/
        $_impCodigo = "";
        $_impCodRetencion = "";
        $_impBaseImponible = "";
        $_impPorcetajeRet  = "";
        $_impValorRet      = "";
        $_impCodDocumentoSustentoRet = "12"; //!NOTA
        $_impNumDocumentoSustentoRet = "";
        $_impfechaEmisionRet   = $_fechaEmision;
        
        $_impNumDocumentoSustentoRet = "999999999999999";
        //$_impNumDocumentoSustentoRet = str_replace("-", "", $_impNumDocumentoSustentoRet);
        
        
            
            $_impCodigo = 1;
            $_impCodRetencion = 323;
            $_impBaseImponible = $rsConsulta1[0]->baseimponible;
            $_impPorcetajeRet = -2.00;
            $_impValorRet = $rsConsulta1[0]->valor_personal;
            
            $texto .= '<impuesto>';
            $texto .= '<codigo>'.$_impCodigo.'</codigo>'; //conforme a la tabla 20
            $texto .= '<codigoRetencion>'.$_impCodRetencion.'</codigoRetencion>'; //conforme a la tabla 21
            $texto .= '<baseImponible>'.round($_impBaseImponible,2).'</baseImponible>';
            $texto .= '<porcentajeRetener>'.round(abs($_impPorcetajeRet),2).'</porcentajeRetener>';//conforme a la tabla 21
            $texto .= '<valorRetenido>'.round(abs($_impValorRet),2).'</valorRetenido>';
            $texto .= '<codDocSustento>'.$_impCodDocumentoSustentoRet.'</codDocSustento>';
            $texto .= '<numDocSustento>'.$_impNumDocumentoSustentoRet.'</numDocSustento>'; //num documento soporte sin '-'
            $texto .= '<fechaEmisionDocSustento>'.$_impfechaEmisionRet.'</fechaEmisionDocSustento>'; //obligatorio cuando corresponda **formato dd/mm/aaaa
            $texto .= '</impuesto>';
           
        $texto .= '</impuestos>';
        
        /** obligatorio cuando corresponda **/
        // se toma datos de proveedor -- Direccion. Telefono. Correo
        /**CAMPOS ADICIONALES **/
        $_adicional1 = $rsConsulta1[0]->direccion_participes;
        if(!empty($_adicional1)){
           
        }else{
            
            $_adicional1="Ninguna";
        }
        
        $_adicional2 = $rsConsulta1[0]->celular_participes;
        
        if(!empty($_adicional2)){
            
        }else{
            
            $_adicional2="0999999999";
        }
        
        $_adicional3 = $rsConsulta1[0]->correo_participes;
        
        if(!empty($_adicional3)){
            
        }else{
            
            $_adicional3="ninguno@capremci.com.ec";
        }
        
        $texto .= '<infoAdicional>';
        $texto .= '<campoAdicional nombre="Dirección">'.$_adicional1.'</campoAdicional>';
        $texto .= '<campoAdicional nombre="Teléfono">'.$_adicional2.'</campoAdicional>';
        $texto .= '<campoAdicional nombre="Email">'.$_adicional3.'</campoAdicional>';
        $texto .= '</infoAdicional>';
        /** termina obligatorio cuando corresponda **/
        
        $texto .= '</comprobanteRetencion>';
        
        $resp = null;
        
        
        
        
        try {
            
            $nombre_archivo = $_claveAcceso.".xml";
            $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\DOCUMENTOSELECTRONICOS\\docGenerados\\";
            $ubicacion = $ubicacionServer.$nombre_archivo;
            
            $textoXML = mb_convert_encoding($texto, "UTF-8");
            
            $gestor = fopen($ubicacionServer.$nombre_archivo, 'w');
            fwrite($gestor, $textoXML);
            fclose($gestor);
            
            if( file_exists( $ubicacion ) ){
                //echo "archivo existe";
                /** SE GENERA UN INSERT A LA TABLA tri_retenciones con la columnName autorizado_retenciones en true **/
                
                $_trifuncion = "ins_tri_retenciones";
                $_triparametros =  "$_ambiente,$_tipoEmision,'$_razonSocial','$_nomComercial','$_rucEmisor','$_claveAcceso','$_codDocumento','$_establecimiento',";
                $_triparametros .= "'$_puntoEmision','$_secuencial','$_dirMatriz','$_fechaEmision','$_dirEstablecimiento',$_contriEspecial,'$_obligadoContabilidad',";
                $_triparametros .= "'$_tipoIdentificacionRetenido','$_razonSocialRetenido','$_identificacionSujetoRetenido','$_periodoFiscal',0,0,0.00,0.00,0.00,";
                $_triparametros .= "'','','$_fechaEmision',0,0,0.00,0.00,0.00,'','','$_fechaEmision','$_adicional1','$_adicional2','$_adicional3','$_fechaEmision'";
                
                $_qryTriRetenciones    = $cuentasPagar->getconsultaPG($_trifuncion, $_triparametros);
                $resultado     = $cuentasPagar->llamarconsultaPG($_qryTriRetenciones);
                
                $error = pg_last_error();
                if( !empty($error) ){
                    throw new Exception('Error al guardar datos Xml en BD');
                }
                
                if( $resultado[0] == 1 ){
                    /** SE GENERA INSERTADO DEL DETALLE DEL ARCHIVO XML **/
                    $_triCol1  = " id_tri_retenciones ";
                    $_triTab1  = " tri_retenciones ";
                    $_triWhe1  = " infotributaria_claveacceso = '$_claveAcceso'";
                    $_rstriConsulta1   = $cuentasPagar->getCondicionesSinOrden($_triCol1, $_triTab1, $_triWhe1, "");
                    
                    if( !empty($_rstriConsulta1) ){
                        
                        $_tri_detallefuncion       = "ins_tri_retenciones_detalle";
                        $_id_tri_retenciones       = $_rstriConsulta1[0]->id_tri_retenciones;
                        
                      
                            
                            $_tri_detalleparametros    = "";
                           
                          
                            $_impPorcetajeRet = abs(-2.00);
                           
                            
                            $_tri_detalleparametros    .= "$_id_tri_retenciones,$_impCodigo,'$_impCodRetencion',$_impBaseImponible,$_impPorcetajeRet,$_impValorRet,";
                            $_tri_detalleparametros    .= "'$_impCodDocumentoSustentoRet','$_impNumDocumentoSustentoRet','$_impfechaEmisionRet'";
                            $_qryTriDetalleRetenciones = $cuentasPagar->getconsultaPG($_tri_detallefuncion, $_tri_detalleparametros);
                            
                            $resultadoDetalle  = $cuentasPagar->llamarconsultaPG($_qryTriDetalleRetenciones); /** insertado del detalle de retenciones **/
                            
                            
                            
                            
                            
                            
                            $error = pg_last_error();
                            if( !empty($error) ){
                                $ins_detalle = false;
                                
                            }
                       
                        
                        if( !empty($error) && isset($ins_detalle) && !$ins_detalle){
                            throw new Exception('Error al guardar Detalles Impuestos datos Xml en BD');
                        }
                        
                    }
                    
                }
                
                $resp['error'] = false;
                $resp['mensaje'] = 'XML GENERADO';
                $resp['claveAcceso'] = $_claveAcceso;
                
            }else{
                throw new Exception('XML NO GENERADO');
                
            }
            
        } catch (Exception $e) {
            
            $resp['error'] = true;
            $resp['mensaje'] = $e->getMessage();
            $resp['claveAcceso'] = $_claveAcceso;
        }
        
        return $resp;
       
        
}


public function genClaveAcceso($_fechaEmision,$_identificacionRet,$_tipoAmbiente,$_sec1,$_sec2,$sec_3,$_tipoEmision){
    
    $_tipoDocumento = "07"; //de acuerdo con la tabla Sri 4 --comprobanteRetencion
    $_digitoVerificador = "";
    $_codNumerico = "12345678";
    
    $_fechaEmision = str_replace( array( '/', '-' ), '' , $_fechaEmision );
    
    $_strClaveAcceso = $_fechaEmision.$_tipoDocumento.$_identificacionRet.$_tipoAmbiente.$_sec1.$_sec2.$sec_3.$_codNumerico.$_tipoEmision;
    
        
    if( strlen( $_strClaveAcceso ) != 48 ){
        //echo "longitud de caracteres  para ver digito verificador no cumplida";
              
        return "";
    }
        
    $_digitoVerificador = $this->getDigVerificador($_strClaveAcceso);
        
    if( (int)$_digitoVerificador < 0 ){
        return "";
    
    }        
  
    $_strClaveAcceso = $_strClaveAcceso.$_digitoVerificador;
        
    return $_strClaveAcceso;
}


function getDigVerificador( $num = "" ){
    
    if( $num == "" )
        return "";
        /* --------------------------------------------------------------------------------------- */
        $digits = str_replace( array( '.', ',' ), array( ''.'' ), strrev($num ) );
        
        
        if ( ! ctype_digit( $digits ) ){
            return "";
        }
        
        $sum = 0;
        $factor = 2;
        
        for( $i=0;$i<strlen( $digits ); $i++ ){
            $sum += substr( $digits,$i,1 ) * $factor;
            if ( $factor == 7 ){
                $factor = 2;
            }else{
                $factor++;
            }
        }
        
        $dv = 11 - ($sum % 11);
        
      
        
        if ( $dv < 10 )
            return $dv;
            
            if ( $dv == 10 )
                return 1;
                
                if ( $dv == 11 )
                    return 0;
                    
                    return  "";
}







public function getConfigXml(){
    $configuracionesPath = array(
        'url_pruebas' => 'https://celcer.sri.gob.ec',
        'url_produccion' => 'https://cel.sri.gob.ec',
        'firmados' => 'DOCUMENTOSELECTRONICOS/docFirmados',
        'autorizados' => 'DOCUMENTOSELECTRONICOS/docAutorizados',
        'noautorizados' => 'DOCUMENTOSELECTRONICOS/docNoAutorizados',
        'generados' => 'DOCUMENTOSELECTRONICOS/docGenerados',
        'pdf' => 'DOCUMENTOSELECTRONICOS/docPdf',
        'logo' => 'DOCUMENTOSELECTRONICOS/logo.png1',
        'xsd' => 'DOCUMENTOSELECTRONICOS/docXsd',
        'pathFirma' => 'firma/byron_stalin_bolanos_palma.p12',
        'passFirma' => 'BPbs1715'
    );
    return $configuracionesPath;
}























//desde aqui para patronal




public function cargar_patronal_a_procesar(){
    
    session_start();
    
    $contribucion = new CoreContribucionPagadaModel();
    
    
    $cantidad_patronal=  (isset($_REQUEST['cantidad_patronal'])&& $_REQUEST['cantidad_patronal'] !=NULL)?$_REQUEST['cantidad_patronal']:0;
    $html="";
    
    if($cantidad_patronal>0){
        
        
        
        
        
        $columnas = "cci.id_contribucion_pagada, pt.id_participes";
        $tablas = "(
	        select c.id_participes, coalesce(sum(valor_personal_contribucion_pagada),0) as baseimponible, coalesce(sum(valor_patronal_contribucion_pagada),0) as c from core_contribucion_pagada c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (49 , 50) and c.id_estatus=1 group by c.id_participes having sum(valor_personal_contribucion_pagada)>0
	        ) cc
            inner join core_participes pt on cc.id_participes=pt.id_participes
	        left join(
	            select c.id_participes, c.id_contribucion_pagada, coalesce(round(sum(valor_personal_contribucion_pagada)*(-1),2),0) as valor_patronal  from core_contribucion_pagada c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (10 , 12) and c.id_estatus=1 group by c.id_participes, c.id_contribucion_pagada
	            ) cci
	            on cc.id_participes=cci.id_participes";
        $where = "pt.retencion_generada_impuestos_cta_des='FALSE'";
        
        $id       = "pt.cedula_participes";
        
        
        $limit = "limit ".$cantidad_patronal;
        
        $resultSet=$contribucion->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
        
        
        
        if(!empty($resultSet)){
            
            $i=0;
            
            
            $html.='<div class="box">';
            $html.='<div class="box-body table-responsive pad">';
            $html.='<table class="table table-bordered">';
            $html.='<tr>';
            $html.='<td>';
            $html.='<div class="btn-group">';
            foreach ($resultSet as $res){
                $i++;
                
                $html.='<button type="button" class="btn btn-default" id="id_'.$res->id_contribucion_pagada.'" value="'.$res->id_contribucion_pagada.'">'.str_pad($i, 2, "0", STR_PAD_LEFT).'</button>';
                
            }
            
            $html.='</div>';
            $html.='</td>';
            $html.='</tr>';
            $html.='</table>';
            $html.='</div>';
            $html.='</div>';
            
            
            
            $resultado=array();
            
            array_push($resultado, $resultSet, $html);
            
            echo json_encode($resultado);
            
        }
        
    }
    
}













public function Procesar_Patronal(){
    
    
    $contribucion = new CoreContribucionPagadaModel();
    $cuentasPagar  = new CuentasPagarModel();
    $respuesta = array();
    
    
    $_cantidad_patronal =(isset($_POST['cantidad_patronal'])) ? $_POST['cantidad_patronal'] : 0;
    $_array_procesar_patronal =(isset($_POST['array_procesar_patronal'])) ? $_POST['array_procesar_patronal'] : 0;
    
    $html="";
    $correcto=0;
    $incorrecto=0;
    $xml_error=0;
    if(!empty($_cantidad_patronal) && !empty($_array_procesar_patronal) ){
        
        
        $i=0;
        $errorXml = false;
        
        foreach ($_array_procesar_patronal as $value) {
            
            $i++;
            $_id_contribucion=  $value['id_contribucion_pagada'];
            $_id_participes = $value['id_participes'];
            
            
           
            
            
            
            $resp = $this->genXmlRetencionPatronal($_id_contribucion, $_id_participes);
            
            
            
            
            
            $respuesta['xml'] = '';
            $respuesta['file']= $resp;
            
        
            
            
            /// significa que hubo un erro al generar el xml
            if( $resp['error'] === true ){
                
                
                $xml_error=$xml_error+1;
                
                $errorXml = true;
                if( array_key_exists('mensaje', $resp) && $resp['mensaje'] == "XML NO GENERADO" ){
                    
                    $respuesta['xml'] = 'XML NO GENERADO';
                }
                
                
                if (array_key_exists('claveAcceso', $resp) && strlen( $resp['claveAcceso'] ) == 49 ) {
                    
                    $respuesta['xml'] = 'DATOS xml EN BD No fueron ingresados';
                    
                    $claveAcceso = $resp['claveAcceso'];
                    $_columnaActualizar = " autorizado_retenciones = false ";
                    $_tablaActualizar   = " tri_retenciones";
                    $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                    
                    $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                }
                
                
                
            }else{
                
                $respuesta['xml'] = " ARCHIVO ENTRO XML";
                
                if( array_key_exists('mensaje', $resp) && $resp['mensaje'] == "XML GENERADO" ){
                    
                    $errorXml = false;
                    
                    $respuesta['xml'] = " ARCHIVO ENTRO XML IF";
                    
                    $clave = ( array_key_exists('claveAcceso', $resp) ) ? $resp['claveAcceso'] : '' ;
                    
                    require_once __DIR__ . '/../vendor/autoload.php';
                    
                    $config = $this->getConfigXml();
                    
                    $comprobante = new \Shara\ComprobantesController($config);
                    
                    $xml = file_get_contents($config['generados'] . DIRECTORY_SEPARATOR . $clave.'.xml', FILE_USE_INCLUDE_PATH);
                    
                    $aux = $comprobante->validarFirmarXml($xml, $clave);
                    
                    
                    
                    $respuesta['Archivo'] = "";
                    $respuesta['xml'] = $aux;
                    
                    if($aux['error'] === false){
                        
                        $Envioresp = $comprobante->enviarXml($clave);
                        //$aux['recibido'] = true; //para pruebas
                        
                        
                        if($Envioresp['recibido'] === true){
                            
                            $respuesta['xml'] = " Archivo Xml RECIBIDO";
                            
                            $finalresp = $comprobante->autorizacionXml($clave);
                            
                            //$finalresp = null;. //para pruebas
                            //$finalresp['error'] = false; //para pruebas
                            if($finalresp['error'] === true ){
                                
                                $respuesta['xml'] = " Archivo Xml RECIBIDO NO AUTORIZADO";
                                $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;
                                $errorXml = true;
                            }else{
                                
                                $respuesta['xml'] = " Archivo Xml RECIBIDO AUTORIZADO";
                                $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;
                                
                                $fechaAutorizado = $finalresp['fecauto'];
                                
                                
                            }
                            
                            
                        }else{
                            
                            $respuesta['xml'] = " Archivo Xml NO RECIBIDO";
                            $respuesta['Archivo'] = ( array_key_exists('mensaje', $Envioresp) ) ? $Envioresp['mensaje'] : '' ;
                            $errorXml = true;
                        }
                        
                    }else{
                        
                        $respuesta['xml'] = " Archivo Xml NO FIRMADO";
                        $respuesta['Archivo'] = ( array_key_exists('mensaje', $aux) ) ? $aux['mensaje'] : '' ;
                        $errorXml = true;
                    }
                    
                    
                    if( $errorXml ){
                        
                        $claveAcceso = $resp['claveAcceso'];
                        $_columnaActualizar = " autorizado_retenciones = false ";
                        $_tablaActualizar   = " tri_retenciones";
                        $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                        $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                        
                        
                    }
                    
                }else{
                    $respuesta['xml'] = " ARCHIVO NO ENTRO XML";
                }
            }
            
            
            
            
            if( $errorXml ){
                
                /*
                 $respuesta['icon'] = 'warning';
                 $respuesta['mensaje'] = "Retención Rechazada";
                 $respuesta['estatus'] = 'ERROR';
                 echo json_encode($respuesta);
                 */
                $incorrecto=$incorrecto+1;
               
                
                
                
                
                //actualizar el codigo de retencion
                $_actCol = " valor_consecutivos = valor_consecutivos - 1, numero_consecutivos = LPAD( ( valor_consecutivos - 1)::TEXT,espacio_consecutivos,'0')";
                $_actTab = " consecutivos ";
                $_actWhe = " nombre_consecutivos = 'RETENCION' ";
                $resultadoAct =  $contribucion->ActualizarBy($_actCol, $_actTab, $_actWhe);
                
                if( $resultadoAct == -1 ){
                    return array('error' => true, 'mensaje' => 'Numero Retencion no actualizada');
                }
                
                
                
                
                
                $_triCol1  = " id_tri_retenciones";
                $_triTab1  = " tri_retenciones";
                $_triWhe1  = " infotributaria_claveacceso = '$claveAcceso'";
                $_rstriConsulta1   = $cuentasPagar->getCondicionesSinOrden($_triCol1, $_triTab1, $_triWhe1, "");
                
                if( !empty($_rstriConsulta1) ){
                    
                    $_id_tri_retenciones       = $_rstriConsulta1[0]->id_tri_retenciones;
                    
                    
                    $resuldelte=$cuentasPagar->eliminarFila("tri_retenciones_detalle", "id_tri_retenciones='$_id_tri_retenciones'");
                    $resuldelte1=$cuentasPagar->eliminarFila("tri_retenciones", "id_tri_retenciones='$_id_tri_retenciones'");
                    
                    
                    
                    
                }
                
                
                
                
                
            }else{
                
                
                //procesado correctamente
                
                $claveAcceso = $resp['claveAcceso'];
                $_columnaActualizar = " fecha_autorizacion = '$fechaAutorizado' ";
                $_tablaActualizar   = " tri_retenciones";
                $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                
                
                
                
                $_columnaActualizar1 = " retencion_generada_impuestos_cta_des = 'TRUE' ";
                $_tablaActualizar1   = " core_participes";
                $_whereActualizar1   = " id_participes='$_id_participes'";
                $cuentasPagar->ActualizarBy($_columnaActualizar1, $_tablaActualizar1, $_whereActualizar1);
                
                
                
                $correcto=$correcto+1;
                /*
                 $respuesta['icon'] = 'success';
                 $respuesta['mensaje'] = "Retención Generada Correctamente";
                 $respuesta['estatus'] = 'OK';
                 
                 echo json_encode($respuesta);
                 
                 */
                
               
            }
            
            
            
        }
        
        
        
        
        //aqui envio respuesta
        
        
        foreach ($_array_procesar_patronal as $value1) {
            
            $_id_contribucion=  $value1['id_contribucion_pagada'];
            $_id_participes = $value1['id_participes'];
            
            $columnas = "c.id_contribucion_pagada, c.id_participes, p.retencion_generada_impuestos_cta_des";
            $tablas = "core_contribucion_pagada c
                    inner join core_participes p on c.id_participes=p.id_participes";
            $where    = "c.id_contribucion_tipo in (10,12) and c.id_estatus=1
                    and c.id_liquidacion=0 and c.id_distribucion in (111,
                                                                    112,
                                                                    113,
                                                                    114,
                                                                    115,
                                                                    116,
                                                                    117,
                                                                    118,
                                                                    119,
                                                                    120,
                                                                    121,
                                                                    122)

                    AND c.id_participes='$_id_participes'";
            
            $id       = "p.cedula_participes";
            
            $resultSet=$contribucion->getCondiciones($columnas, $tablas, $where, $id);
            
            
            
            if(!empty($resultSet)){
                
                $i1=0;
                
                
                $html.='<div class="box">';
                $html.='<div class="box-body table-responsive pad">';
                $html.='<table class="table table-bordered">';
                $html.='<tr>';
                $html.='<td>';
                $html.='<div class="btn-group">';
                foreach ($resultSet as $res){
                    $i1++;
                    
                    if($res->retencion_generada_impuestos_cta_des=='t'){
                        
                        $html.='<button type="button" class="btn btn-success" id="id_'.$res->id_contribucion_pagada.'" value="'.$res->id_contribucion_pagada.'">'.str_pad($i1, 2, "0", STR_PAD_LEFT).'</button>';
                        
                    }else{
                        
                        $html.='<button type="button" class="btn btn-danger" id="id_'.$res->id_contribucion_pagada.'" value="'.$res->id_contribucion_pagada.'">'.str_pad($i1, 2, "0", STR_PAD_LEFT).'</button>';
                        
                    }
                }
                
                
                
            }
            
            $html.='</div>';
            $html.='</td>';
            $html.='</tr>';
            $html.='</table>';
            $html.='</div>';
            $html.='</div>';
            
            
            
            
        }
        
        
        
        $respuesta['icon'] = 'success';
        $respuesta['mensaje'] = "Retención Generada Correctamente";
        $respuesta['estatus'] = 'OK';
        $respuesta['html'] = $html;
        $respuesta['correcto'] = $correcto;
        $respuesta['incorrecto'] = $incorrecto;
        $respuesta['xml_error'] = $xml_error;
        
        
        echo json_encode($respuesta);
        
        
        
        
        
    }else{
        
        
        $respuesta['icon'] = 'warning';
        $respuesta['mensaje'] = "No se pudo procesar";
        $respuesta['estatus'] = 'ERROR';
        $respuesta['html'] = $html;
        $respuesta['correcto'] = $correcto;
        $respuesta['incorrecto'] = $incorrecto;
        $respuesta['xml_error'] = $xml_error;
        
        echo json_encode($respuesta);
        
    }
    
    
    
}








public function genXmlRetencionPatronal($_id_contribucion, $_id_participes){
    
    // session_start();
    $contribucion = new CoreContribucionPagadaModel();
    $cuentasPagar  = new CuentasPagarModel();
    
    
    //codigo 323
    
    //impuestos de tipo retencion
    
    
    $col1 = "pt.id_participes, pt.cedula_participes, concat( RTRIM(LTRIM(pt.apellido_participes)),' ', RTRIM(LTRIM(pt.nombre_participes))) as nombres_participes,
	    '05' as tipo_identificacion,
	    pt.correo_participes, pt.celular_participes,  RTRIM(LTRIM(pt.direccion_participes)) as direccion_participes, cc.baseimponible, cc.c, cci.id_contribucion_pagada, coalesce(cci.valor_patronal,0) as valor_patronal";
    $tab1 = "(
	        select c.id_participes, coalesce(sum(valor_personal_contribucion_pagada),0) as baseimponible, coalesce(sum(valor_patronal_contribucion_pagada),0) as c from core_contribucion_pagada c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (49 , 50) and c.id_estatus=1 group by c.id_participes having sum(valor_personal_contribucion_pagada)>0
	        ) cc
            inner join core_participes pt on cc.id_participes=pt.id_participes
	        left join(
	            select c.id_participes, c.id_contribucion_pagada, coalesce(round(sum(valor_personal_contribucion_pagada)*(-1),2),0) as valor_patronal  from core_contribucion_pagada c where c.id_distribucion in (111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) and c.id_contribucion_tipo in (10 , 12) and c.id_estatus=1 group by c.id_participes, c.id_contribucion_pagada
	            ) cci
	            on cc.id_participes=cci.id_participes";
    $whe1 = " pt.id_participes= '$_id_participes' and pt.retencion_generada_impuestos_cta_des='FALSE'";
    
    $id1       = "pt.cedula_participes";
    
          
  
    
    
    $rsConsulta1   = $contribucion->getCondiciones($col1, $tab1, $whe1, $id1); //array de impuestos
    
    
    
    //datos de la empresa
    $col3  = " id_entidades, ruc_entidades, nombre_entidades, telefono_entidades, direccion_entidades, ciudad_entidades, razon_social_entidades";
    $tab3  = " entidades";
    $whe3  = " 1 = 1
               AND nombre_entidades = 'CAPREMCI'";
    $id3   = " creado";
    $rsConsulta3   = $contribucion->getCondiciones($col3, $tab3, $whe3, $id3); //array de empresa
    
    
    
    
    //datos de consecutivo
    $col4  = " LPAD( valor_consecutivos::TEXT,espacio_consecutivos,'0') secuencial";
    $tab4  = " consecutivos";
    $whe4  = " 1 = 1
               AND nombre_consecutivos = 'RETENCION'";
    $id4   = " creado";
    $rsConsulta4   = $contribucion->getCondiciones($col4, $tab4, $whe4, $id4); //array de empresa
    
   
    
    
    //actualizar el codigo de retencion
    $_actCol = " valor_consecutivos = valor_consecutivos + 1, numero_consecutivos = LPAD( ( valor_consecutivos + 1)::TEXT,espacio_consecutivos,'0')";
    $_actTab = " consecutivos ";
    $_actWhe = " nombre_consecutivos = 'RETENCION' ";
    $resultadoAct =  $contribucion->ActualizarBy($_actCol, $_actTab, $_actWhe);
    
    if( $resultadoAct == -1 ){
        return array('error' => true, 'mensaje' => 'Numero Retencion no actualizada');
    }
    
    
    
    
    /** validacion de parametros **/
    if( empty($rsConsulta1) || empty($rsConsulta3) || empty($rsConsulta4) ){
        //echo "Error validacion llego ";
        return array('error' => true, 'mensaje' => 'Consultas no contiene todos los datos');
    }
    
    
    
    
    
    
    /** AUX de VARIABLES **/
    
    
    $_fechaDocumento =    "28".'/'."02".'/'."2020";
    
    /** VARIABLES DE XML **/
    $_ambiente = 2; //1 pruebas  2 produccion
    $_tipoEmision = 1; //1 emision normal deacuerdo a la tabla 2 SRI
    $_rucEmisor  = $rsConsulta3[0]->ruc_entidades;
    $_razonSocial = $rsConsulta3[0]->razon_social_entidades;
    $_nomComercial= $rsConsulta3[0]->nombre_entidades;
    $_codDocumento= "07"; // referenciado a la tabla 4 del sri
    $_establecimiento = "001"; //definir de la estructura  001-001-000000 -- factura !!!!------>NOTA
    $_puntoEmision    = "001"; //solo existe un establecimiento
    $_secuencial      = $rsConsulta4[0]->secuencial;   // es un secuencial tiene que definirse
    $_dirMatriz       = $rsConsulta3[0]->direccion_entidades;
    $_fechaEmision    =  $_fechaDocumento;//definir la fecha
    $_dirEstablecimiento   = $rsConsulta3[0]->direccion_entidades;
    
    // /** informacion rtencion **/ //datos obtener de la tabla proveedores
    $_contriEspecial  = "624";  //numero definir para otra empresa !!!!------>NOTA ----- OJO -- tomara de la tabla entidades
    $_obligadoContabilidad = "SI"; //TEXTO definir para otra empresa !!!!------>NOTA ----- OJO --tomara de la tabla entidades
    $_tipoIdentificacionRetenido   = $rsConsulta1[0]->tipo_identificacion; // deacuerdo a la tabla 7 --> ruc 04
    $_razonSocialRetenido  = $rsConsulta1[0]->nombres_participes;
    $_identificacionSujetoRetenido = $rsConsulta1[0]->cedula_participes;
    $_periodoFiscal        = "02".'/'."2020";
    
    $_claveAcceso = $this->genClaveAcceso($_fechaEmision, $_rucEmisor, $_ambiente, $_establecimiento, $_puntoEmision, $_secuencial, $_tipoEmision);
        
    if( $_claveAcceso == "" || strlen($_claveAcceso) != 49 ){
        return array('error' => true, 'mensaje' => 'Clave de acceso no generada','ErrClave'=>$_claveAcceso);
    }
    
    $texto = "";
    $texto .='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    $texto .= '<comprobanteRetencion id="comprobante" version="1.0.0">';
    $texto .= '<infoTributaria>';
    $texto .= '<ambiente>'.$_ambiente.'</ambiente>'; //conforme a la tabla 4
    $texto .= '<tipoEmision>'.$_tipoEmision.'</tipoEmision>'; //conforme a la tabla 2
    $texto .= '<razonSocial>'.htmlspecialchars($_razonSocial).'</razonSocial>';
    $texto .= '<nombreComercial>'.htmlspecialchars($_nomComercial).'</nombreComercial>';
    $texto .= '<ruc>'.$_rucEmisor.'</ruc>';
    $texto .= '<claveAcceso>'.$_claveAcceso.'</claveAcceso>'; //conforme a la tabla 1
    $texto .= '<codDoc>'.$_codDocumento.'</codDoc>'; //conforme a la tabla 3
    $texto .= '<estab>'.$_establecimiento.'</estab>';
    $texto .= '<ptoEmi>'.$_puntoEmision.'</ptoEmi>';
    $texto .= '<secuencial>'.$_secuencial.'</secuencial>';
    $texto .= '<dirMatriz>'.$_dirMatriz.'</dirMatriz>';
    $texto .= '</infoTributaria>';
    
    $texto .= '<infoCompRetencion>';
    $texto .= '<fechaEmision>'.$_fechaEmision.'</fechaEmision>'; //conforme al formato -- dd/mm/aaaa
    $texto .= '<dirEstablecimiento>'.$_dirEstablecimiento.'</dirEstablecimiento>';
    $texto .= '<contribuyenteEspecial>'.$_contriEspecial.'</contribuyenteEspecial>';
    $texto .= '<obligadoContabilidad>'.$_obligadoContabilidad.'</obligadoContabilidad>';
    $texto .= '<tipoIdentificacionSujetoRetenido>'.$_tipoIdentificacionRetenido.'</tipoIdentificacionSujetoRetenido>'; // conforme a la tabla 6
    $texto .= '<razonSocialSujetoRetenido>'.$_razonSocialRetenido.'</razonSocialSujetoRetenido>';
    $texto .= '<identificacionSujetoRetenido>'.$_identificacionSujetoRetenido.'</identificacionSujetoRetenido>';
    $texto .= '<periodoFiscal>'.$_periodoFiscal.'</periodoFiscal>'; //conforme a formato mm/aaaa
    $texto .= '</infoCompRetencion>';
    
    $texto .= '<impuestos>'; //aqui comienza el foreach de impuestos
    
    /** VARIABLES PARA CADA IMPUESTO **/
    $_impCodigo = "";
    $_impCodRetencion = "";
    $_impBaseImponible = "";
    $_impPorcetajeRet  = "";
    $_impValorRet      = "";
    $_impCodDocumentoSustentoRet = "12"; //!NOTA
    $_impNumDocumentoSustentoRet = "";
    $_impfechaEmisionRet   = $_fechaEmision;
    
    $_impNumDocumentoSustentoRet = "999999999999999";
    //$_impNumDocumentoSustentoRet = str_replace("-", "", $_impNumDocumentoSustentoRet);
    
    
    
    $_impCodigo = 1;
    $_impCodRetencion = 323;
    $_impBaseImponible = $rsConsulta1[0]->baseimponible;
    $_impPorcetajeRet = -2.00;
    $_impValorRet = $rsConsulta1[0]->valor_patronal;
    
    $texto .= '<impuesto>';
    $texto .= '<codigo>'.$_impCodigo.'</codigo>'; //conforme a la tabla 20
    $texto .= '<codigoRetencion>'.$_impCodRetencion.'</codigoRetencion>'; //conforme a la tabla 21
    $texto .= '<baseImponible>'.round($_impBaseImponible,2).'</baseImponible>';
    $texto .= '<porcentajeRetener>'.round(abs($_impPorcetajeRet),2).'</porcentajeRetener>';//conforme a la tabla 21
    $texto .= '<valorRetenido>'.round(abs($_impValorRet),2).'</valorRetenido>';
    $texto .= '<codDocSustento>'.$_impCodDocumentoSustentoRet.'</codDocSustento>';
    $texto .= '<numDocSustento>'.$_impNumDocumentoSustentoRet.'</numDocSustento>'; //num documento soporte sin '-'
    $texto .= '<fechaEmisionDocSustento>'.$_impfechaEmisionRet.'</fechaEmisionDocSustento>'; //obligatorio cuando corresponda **formato dd/mm/aaaa
    $texto .= '</impuesto>';
    
    $texto .= '</impuestos>';
    
    /** obligatorio cuando corresponda **/
    // se toma datos de proveedor -- Direccion. Telefono. Correo
    /**CAMPOS ADICIONALES **/
    $_adicional1 = $rsConsulta1[0]->direccion_participes;
    if(!empty($_adicional1)){
        
    }else{
        
        $_adicional1="Ninguna";
    }
    
    $_adicional2 = $rsConsulta1[0]->celular_participes;
    
    if(!empty($_adicional2)){
        
    }else{
        
        $_adicional2="0999999999";
    }
    
    $_adicional3 = $rsConsulta1[0]->correo_participes;
    
    if(!empty($_adicional3)){
        
    }else{
        
        $_adicional3="ninguno@capremci.com.ec";
    }
    
    $texto .= '<infoAdicional>';
    $texto .= '<campoAdicional nombre="Dirección">'.$_adicional1.'</campoAdicional>';
    $texto .= '<campoAdicional nombre="Teléfono">'.$_adicional2.'</campoAdicional>';
    $texto .= '<campoAdicional nombre="Email">'.$_adicional3.'</campoAdicional>';
    $texto .= '</infoAdicional>';
    /** termina obligatorio cuando corresponda **/
    
    $texto .= '</comprobanteRetencion>';
    
    $resp = null;
    
        
    
    try {
    
    
        $nombre_archivo = $_claveAcceso.".xml";
        $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\DOCUMENTOSELECTRONICOS\\docGenerados\\";
        $ubicacion = $ubicacionServer.$nombre_archivo;
        
        $textoXML = mb_convert_encoding($texto, "UTF-8");
        
        $gestor = fopen($ubicacionServer.$nombre_archivo, 'w');
        fwrite($gestor, $textoXML);
        fclose($gestor);
        
        if( file_exists( $ubicacion ) ){
            //echo "archivo existe";
            /** SE GENERA UN INSERT A LA TABLA tri_retenciones con la columnName autorizado_retenciones en true **/
            
            $_trifuncion = "ins_tri_retenciones";
            $_triparametros =  "$_ambiente,$_tipoEmision,'$_razonSocial','$_nomComercial','$_rucEmisor','$_claveAcceso','$_codDocumento','$_establecimiento',";
            $_triparametros .= "'$_puntoEmision','$_secuencial','$_dirMatriz','$_fechaEmision','$_dirEstablecimiento',$_contriEspecial,'$_obligadoContabilidad',";
            $_triparametros .= "'$_tipoIdentificacionRetenido','$_razonSocialRetenido','$_identificacionSujetoRetenido','$_periodoFiscal',0,0,0.00,0.00,0.00,";
            $_triparametros .= "'','','$_fechaEmision',0,0,0.00,0.00,0.00,'','','$_fechaEmision','$_adicional1','$_adicional2','$_adicional3','$_fechaEmision'";
            
            $_qryTriRetenciones    = $cuentasPagar->getconsultaPG($_trifuncion, $_triparametros);
            $resultado     = $cuentasPagar->llamarconsultaPG($_qryTriRetenciones);
            
            $error = pg_last_error();
            if( !empty($error) ){
                throw new Exception('Error al guardar datos Xml en BD');
            }
            
            if( $resultado[0] == 1 ){
                /** SE GENERA INSERTADO DEL DETALLE DEL ARCHIVO XML **/
                $_triCol1  = " id_tri_retenciones ";
                $_triTab1  = " tri_retenciones ";
                $_triWhe1  = " infotributaria_claveacceso = '$_claveAcceso'";
                $_rstriConsulta1   = $cuentasPagar->getCondicionesSinOrden($_triCol1, $_triTab1, $_triWhe1, "");
                
                if( !empty($_rstriConsulta1) ){
                    
                    $_tri_detallefuncion       = "ins_tri_retenciones_detalle";
                    $_id_tri_retenciones       = $_rstriConsulta1[0]->id_tri_retenciones;
                    
                    
                    
                    $_tri_detalleparametros    = "";
                    
                    
                    $_impPorcetajeRet = abs(-2.00);
                    
                    
                    $_tri_detalleparametros    .= "$_id_tri_retenciones,$_impCodigo,'$_impCodRetencion',$_impBaseImponible,$_impPorcetajeRet,$_impValorRet,";
                    $_tri_detalleparametros    .= "'$_impCodDocumentoSustentoRet','$_impNumDocumentoSustentoRet','$_impfechaEmisionRet'";
                    $_qryTriDetalleRetenciones = $cuentasPagar->getconsultaPG($_tri_detallefuncion, $_tri_detalleparametros);
                    
                    $resultadoDetalle  = $cuentasPagar->llamarconsultaPG($_qryTriDetalleRetenciones); /** insertado del detalle de retenciones **/
                    
                    
                    
                  
                    
                    
                    $error = pg_last_error();
                    if( !empty($error) ){
                        $ins_detalle = false;
                        
                    }
                    
                    
                    if( !empty($error) && isset($ins_detalle) && !$ins_detalle){
                        throw new Exception('Error al guardar Detalles Impuestos datos Xml en BD');
                    }
                    
                }
                
            }
            
            $resp['error'] = false;
            $resp['mensaje'] = 'XML GENERADO';
            $resp['claveAcceso'] = $_claveAcceso;
            
        }else{
            throw new Exception('XML NO GENERADO');
            
        }
        
    } catch (Exception $e) {
        
        $resp['error'] = true;
        $resp['mensaje'] = $e->getMessage();
        $resp['claveAcceso'] = $_claveAcceso;
    }
   
    
    return $resp;
    
    
}



// para cesantes

public function cargar_cesantes_a_procesar(){
    
    session_start();
    
    $contribucion = new CoreSuperavitPagosModel();
    
    
    
    $cantidad_cesantes=  (isset($_REQUEST['cantidad_cesantes'])&& $_REQUEST['cantidad_cesantes'] !=NULL)?$_REQUEST['cantidad_cesantes']:0;
    $html="";
    
    if($cantidad_cesantes>0){
        
        $columnas = "pt.id_participes, '05' as tipo_identificacion,
                    (select pt1.cedula_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as cedula_participes,
                    (select pt1.apellido_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as apellido_participes,
                    (select pt1.nombre_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as nombre_participes,
                    (select pt1.correo_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as correo_participes,
                    (select pt1.celular_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as celular_participes,
                    (select RTRIM(LTRIM(pt1.direccion_participes)) from core_participes pt1 where pt1.id_participes=pt.id_participes) as direccion_participes,
                    coalesce(round(sum(cc.ir_patronal_cobrado_ctaind_superavit_pagos+cc.ir_patronal_cobrado_cxpdf_superavit_pagos)*(-1),2),0) as valor_patronal,
                    coalesce(sum(cc.ctaind_patronal_superavit_pagos+cc.cxpdf_patronal_superavit_pagos),0) as baseimponible
                    ";
        $tablas = "core_participes pt
                    inner join core_superavit_pagos cc on pt.id_participes=cc.id_participes
                    ";
        $where = "cc.diario_origen_superavit_pagos in (-20202020) and pt.retencion_generada_impuestos_cta_ces='FALSE' and cc.id_estatus=1";
        
        $grupo = "pt.id_participes";
        $condicion_grupo = "sum(cc.ctaind_patronal_superavit_pagos+cc.cxpdf_patronal_superavit_pagos)>0";
        $id       = "pt.id_participes";
        
        $limit = "limit ".$cantidad_cesantes;
        
        $resultSet=$contribucion->getCondicionesGrupCondiOrderPag($columnas, $tablas, $where, $grupo, $condicion_grupo, $id, $limit);
        
        
        
        if(!empty($resultSet)){
            
            $i=0;
            
            
            $html.='<div class="box">';
            $html.='<div class="box-body table-responsive pad">';
            $html.='<table class="table table-bordered">';
            $html.='<tr>';
            $html.='<td>';
            $html.='<div class="btn-group">';
            foreach ($resultSet as $res){
                $i++;
                
                $html.='<button type="button" class="btn btn-default" id="id_'.$res->id_participes.'" value="'.$res->id_participes.'">'.str_pad($i, 2, "0", STR_PAD_LEFT).'</button>';
                
            }
            
            $html.='</div>';
            $html.='</td>';
            $html.='</tr>';
            $html.='</table>';
            $html.='</div>';
            $html.='</div>';
            
            
            
            $resultado=array();
            
            array_push($resultado, $resultSet, $html);
            
            echo json_encode($resultado);
            
        }
        
    }
    
}





public function Procesar_Cesantes(){
    
    
    $contribucion = new CoreSuperavitPagosModel();
    $cuentasPagar  = new CuentasPagarModel();
    $respuesta = array();
    
    
    $_cantidad_cesantes =(isset($_POST['cantidad_cesantes'])) ? $_POST['cantidad_cesantes'] : 0;
    $_array_procesar_cesantes =(isset($_POST['array_procesar_cesantes'])) ? $_POST['array_procesar_cesantes'] : 0;
    
    $html="";
    $correcto=0;
    $incorrecto=0;
    $xml_error=0;
    if(!empty($_cantidad_cesantes) && !empty($_array_procesar_cesantes) ){
        
        
        $i=0;
        $errorXml = false;
        
        foreach ($_array_procesar_cesantes as $value) {
            
            $i++;
            //$_id_contribucion=  $value['id_contribucion_pagada'];
            $_id_participes = $value['id_participes'];
            
            
            
            $resp = $this->genXmlRetencionCesantes($_id_participes);
            
            
            
            
            
            $respuesta['xml'] = '';
            $respuesta['file']= $resp;
            
            
            
            
            /// significa que hubo un erro al generar el xml
            if( $resp['error'] === true ){
                
                
                $xml_error=$xml_error+1;
                
                $errorXml = true;
                if( array_key_exists('mensaje', $resp) && $resp['mensaje'] == "XML NO GENERADO" ){
                    
                    $respuesta['xml'] = 'XML NO GENERADO';
                }
                
                
                if (array_key_exists('claveAcceso', $resp) && strlen( $resp['claveAcceso'] ) == 49 ) {
                    
                    $respuesta['xml'] = 'DATOS xml EN BD No fueron ingresados';
                    
                    $claveAcceso = $resp['claveAcceso'];
                    $_columnaActualizar = " autorizado_retenciones = false ";
                    $_tablaActualizar   = " tri_retenciones";
                    $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                    
                    $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                }
                
                
                
            }else{
                
                $respuesta['xml'] = " ARCHIVO ENTRO XML";
                
                if( array_key_exists('mensaje', $resp) && $resp['mensaje'] == "XML GENERADO" ){
                    
                    $errorXml = false;
                    
                    $respuesta['xml'] = " ARCHIVO ENTRO XML IF";
                    
                    $clave = ( array_key_exists('claveAcceso', $resp) ) ? $resp['claveAcceso'] : '' ;
                    
                    require_once __DIR__ . '/../vendor/autoload.php';
                    
                    $config = $this->getConfigXml();
                    
                    $comprobante = new \Shara\ComprobantesController($config);
                    
                    $xml = file_get_contents($config['generados'] . DIRECTORY_SEPARATOR . $clave.'.xml', FILE_USE_INCLUDE_PATH);
                    
                    $aux = $comprobante->validarFirmarXml($xml, $clave);
                    
                    
                    
                    $respuesta['Archivo'] = "";
                    $respuesta['xml'] = $aux;
                    
                    if($aux['error'] === false){
                        
                        $Envioresp = $comprobante->enviarXml($clave);
                        //$aux['recibido'] = true; //para pruebas
                        
                        
                        if($Envioresp['recibido'] === true){
                            
                            $respuesta['xml'] = " Archivo Xml RECIBIDO";
                            
                            $finalresp = $comprobante->autorizacionXml($clave);
                            
                            //$finalresp = null;. //para pruebas
                            //$finalresp['error'] = false; //para pruebas
                            if($finalresp['error'] === true ){
                                
                                $respuesta['xml'] = " Archivo Xml RECIBIDO NO AUTORIZADO";
                                $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;
                                $errorXml = true;
                            }else{
                                
                                $respuesta['xml'] = " Archivo Xml RECIBIDO AUTORIZADO";
                                $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;
                                
                                $fechaAutorizado = $finalresp['fecauto'];
                                
                                
                            }
                            
                            
                        }else{
                            
                            $respuesta['xml'] = " Archivo Xml NO RECIBIDO";
                            $respuesta['Archivo'] = ( array_key_exists('mensaje', $Envioresp) ) ? $Envioresp['mensaje'] : '' ;
                            $errorXml = true;
                        }
                        
                    }else{
                        
                        $respuesta['xml'] = " Archivo Xml NO FIRMADO";
                        $respuesta['Archivo'] = ( array_key_exists('mensaje', $aux) ) ? $aux['mensaje'] : '' ;
                        $errorXml = true;
                    }
                    
                    
                    if( $errorXml ){
                        
                        $claveAcceso = $resp['claveAcceso'];
                        $_columnaActualizar = " autorizado_retenciones = false ";
                        $_tablaActualizar   = " tri_retenciones";
                        $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                        $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                        
                        
                    }
                    
                }else{
                    $respuesta['xml'] = " ARCHIVO NO ENTRO XML";
                }
            }
            
            
            
            
            if( $errorXml ){
                
                /*
                 $respuesta['icon'] = 'warning';
                 $respuesta['mensaje'] = "Retención Rechazada";
                 $respuesta['estatus'] = 'ERROR';
                 echo json_encode($respuesta);
                 */
                $incorrecto=$incorrecto+1;
                
                
                
                
                
                //actualizar el codigo de retencion
                $_actCol = " valor_consecutivos = valor_consecutivos - 1, numero_consecutivos = LPAD( ( valor_consecutivos - 1)::TEXT,espacio_consecutivos,'0')";
                $_actTab = " consecutivos ";
                $_actWhe = " nombre_consecutivos = 'RETENCION' ";
                $resultadoAct =  $contribucion->ActualizarBy($_actCol, $_actTab, $_actWhe);
                
                if( $resultadoAct == -1 ){
                    return array('error' => true, 'mensaje' => 'Numero Retencion no actualizada');
                }
                
                
                
                
                
                $_triCol1  = " id_tri_retenciones";
                $_triTab1  = " tri_retenciones";
                $_triWhe1  = " infotributaria_claveacceso = '$claveAcceso'";
                $_rstriConsulta1   = $cuentasPagar->getCondicionesSinOrden($_triCol1, $_triTab1, $_triWhe1, "");
                
                if( !empty($_rstriConsulta1) ){
                    
                    $_id_tri_retenciones       = $_rstriConsulta1[0]->id_tri_retenciones;
                    
                    
                    $resuldelte=$cuentasPagar->eliminarFila("tri_retenciones_detalle", "id_tri_retenciones='$_id_tri_retenciones'");
                    $resuldelte1=$cuentasPagar->eliminarFila("tri_retenciones", "id_tri_retenciones='$_id_tri_retenciones'");
                    
                    
                    
                    
                }
                
                
                
                
                
            }else{
                
                
                //procesado correctamente
                
                $claveAcceso = $resp['claveAcceso'];
                $_columnaActualizar = " fecha_autorizacion = '$fechaAutorizado' ";
                $_tablaActualizar   = " tri_retenciones";
                $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                
                
                
                
                $_columnaActualizar1 = " retencion_generada_impuestos_cta_ces = 'TRUE' ";
                $_tablaActualizar1   = " core_participes";
                $_whereActualizar1   = " id_participes='$_id_participes'";
                $cuentasPagar->ActualizarBy($_columnaActualizar1, $_tablaActualizar1, $_whereActualizar1);
                
                
                
                $correcto=$correcto+1;
                /*
                 $respuesta['icon'] = 'success';
                 $respuesta['mensaje'] = "Retención Generada Correctamente";
                 $respuesta['estatus'] = 'OK';
                 
                 echo json_encode($respuesta);
                 
                 */
                
                
            }
            
            
            
        }
        
        
        
        
        //aqui envio respuesta
        
        $html="";
        
        $respuesta['icon'] = 'success';
        $respuesta['mensaje'] = "Retención Generada Correctamente";
        $respuesta['estatus'] = 'OK';
        $respuesta['html'] = $html;
        $respuesta['correcto'] = $correcto;
        $respuesta['incorrecto'] = $incorrecto;
        $respuesta['xml_error'] = $xml_error;
        
        
        echo json_encode($respuesta);
        
        
        
        
        
    }else{
        
        
        $respuesta['icon'] = 'warning';
        $respuesta['mensaje'] = "No se pudo procesar";
        $respuesta['estatus'] = 'ERROR';
        $respuesta['html'] = $html;
        $respuesta['correcto'] = $correcto;
        $respuesta['incorrecto'] = $incorrecto;
        $respuesta['xml_error'] = $xml_error;
        
        echo json_encode($respuesta);
        
    }
    
    
    
}




public function cargar_cesantias_patronales_a_procesar(){
    
    session_start();
    
    $contribucion = new CoreSuperavitPagosModel();
    
    
    
    $cantidad_cesantes= (isset($_REQUEST['cantidad_cesantias_patronales'])&& $_REQUEST['cantidad_cesantias_patronales'] !=NULL)?$_REQUEST['cantidad_cesantias_patronales']:0;
    
    
    $_search_fechadesde =(isset($_REQUEST['search_fechadesde'])&& $_REQUEST['search_fechadesde'] !=NULL)?$_REQUEST['search_fechadesde']:0;
    $_search_fechahasta =(isset($_REQUEST['search_fechahasta'])&& $_REQUEST['search_fechahasta'] !=NULL)?$_REQUEST['search_fechahasta']:0;
    
    
    /*  
    $resultado = $cantidad_cesantes ;
    echo json_encode($resultado);
    die();
    */
    
    $html="";
    
    if($cantidad_cesantes>0){
        
        
        
        
        $columnas = "clc.id_liquidacion_cabeza,   cp.id_participes, clc.fecha_pago_carpeta_liquidacion_cabeza, cp.cedula_participes ,
	                 cp.apellido_participes , cp.nombre_participes , cld.motivo_liquidacion_detalle ,
	                  cld.porcentaje_liquidacion_detalle , cld.base_calcuo_liquidacion_detalle , cld.valor_liquidacion_detalle* -1 as valor_liquidacion_detalle ";
        $tablas = "core_participes cp , core_liquidacion_cabeza clc ,  core_liquidacion_detalle cld";
        $where = "clc.id_participes = cp.id_participes
                    AND  clc.fecha_pago_carpeta_liquidacion_cabeza BETWEEN '$_search_fechadesde' AND  '$_search_fechahasta'
                    and clc.retencion_liquidacion_cabeza='false'  
            	    and clc.id_liquidacion_cabeza = cld.id_liquidacion_cabeza
            	    and clc.id_estado_prestaciones in (3,4)
            	    and clc.id_tipo_prestaciones not in (3)
            	    and cld.id_tipo_pago_liquidacion = 8
            	    and (cld.motivo_liquidacion_detalle LIKE '%Patronal%' or cld.motivo_liquidacion_detalle LIKE '%PATRONAL%' )";
        
        $id       = "clc.fecha_pago_carpeta_liquidacion_cabeza";
        
        
        $limit = "limit ".$cantidad_cesantes;
        
        $resultSet=$contribucion->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
        
        
        
        if(!empty($resultSet)){
            
            $i=0;
            
            
            $html.='<div class="box">';
            $html.='<div class="box-body table-responsive pad">';
            $html.='<table class="table table-bordered">';
            $html.='<tr>';
            $html.='<td>';
            $html.='<div class="btn-group">';
            foreach ($resultSet as $res){
                $i++;
                
                $html.='<button type="button" class="btn btn-default" id="id_'.$res->id_participes.'" value="'.$res->id_participes.'">'.str_pad($i, 2, "0", STR_PAD_LEFT).'</button>';
                
            }
            
            $html.='</div>';
            $html.='</td>';
            $html.='</tr>';
            $html.='</table>';
            $html.='</div>';
            $html.='</div>';
            
            
            
            $resultado=array();
            
            array_push($resultado, $resultSet, $html);
            
            echo json_encode($resultado);
            
        }
        
    }
    
}








public function Procesar_Cesantias_Patronales(){
    
    
    $contribucion = new CoreSuperavitPagosModel();
    $cuentasPagar  = new CuentasPagarModel();
    $respuesta = array();
    
    
    $_cantidad_cesantes = (isset($_POST['cantidad_cesantias_patronales'])) ? $_POST['cantidad_cesantias_patronales'] : 0;
    $_array_procesar_cesantias_patronales =(isset($_POST['array_procesar_cesantias_patronales'])) ? $_POST['array_procesar_cesantias_patronales'] : 0;
    
    
    
    
    
    $html="";
    $correcto=0;
    $incorrecto=0;
    $xml_error=0;
    if(!empty($_cantidad_cesantes) && !empty($_array_procesar_cesantias_patronales) ){
        
        
        $i=0;
        $errorXml = false;
        
        foreach ($_array_procesar_cesantias_patronales as $value) {
            
            $i++;
            //$_id_contribucion=  $value['id_contribucion_pagada'];
            
            $_id_liquidacion_cabeza = $value['id_liquidacion_cabeza'];
            
            
            
            
        
            $resp = $this->genXmlRetencionCesantiasPatronales($_id_liquidacion_cabeza);
            
            
            
            /*
            $html= $resp; 
        
            
            $respuesta['estatus'] = 'PRUEBA';
            $respuesta['html'] = $html;
            
            
            echo json_encode($respuesta);
            die();
            */
            
            
            
            
            
            
            
            
            
            
            
            $respuesta['xml'] = '';
            $respuesta['file']= $resp;
            
            
            
            
            /// significa que hubo un erro al generar el xml
            if( $resp['error'] === true ){
                
                
                $xml_error=$xml_error+1;
                
                $errorXml = true;
                if( array_key_exists('mensaje', $resp) && $resp['mensaje'] == "XML NO GENERADO" ){
                    
                    $respuesta['xml'] = 'XML NO GENERADO';
                }
                
                
                if (array_key_exists('claveAcceso', $resp) && strlen( $resp['claveAcceso'] ) == 49 ) {
                    
                    $respuesta['xml'] = 'DATOS xml EN BD No fueron ingresados';
                    
                    $claveAcceso = $resp['claveAcceso'];
                    $_columnaActualizar = " autorizado_retenciones = false ";
                    $_tablaActualizar   = " tri_retenciones";
                    $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                    
                    $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                }
                
                
                
            }else{
                
                $respuesta['xml'] = " ARCHIVO ENTRO XML";
                
                if( array_key_exists('mensaje', $resp) && $resp['mensaje'] == "XML GENERADO" ){
                    
                    $errorXml = false;
                    
                    $respuesta['xml'] = " ARCHIVO ENTRO XML IF";
                    
                    $clave = ( array_key_exists('claveAcceso', $resp) ) ? $resp['claveAcceso'] : '' ;
                    
                    require_once __DIR__ . '/../vendor/autoload.php';
                    
                    $config = $this->getConfigXml();
                    
                    $comprobante = new \Shara\ComprobantesController($config);
                    
                    $xml = file_get_contents($config['generados'] . DIRECTORY_SEPARATOR . $clave.'.xml', FILE_USE_INCLUDE_PATH);
                    
                    $aux = $comprobante->validarFirmarXml($xml, $clave);
                    
                    
                    
                    $respuesta['Archivo'] = "";
                    $respuesta['xml'] = $aux;
                    
                    if($aux['error'] === false){
                        
                        $Envioresp = $comprobante->enviarXml($clave);
                        //$aux['recibido'] = true; //para pruebas
                        
                        
                        if($Envioresp['recibido'] === true){
                            
                            $respuesta['xml'] = " Archivo Xml RECIBIDO";
                            
                            $finalresp = $comprobante->autorizacionXml($clave);
                            
                            //$finalresp = null;. //para pruebas
                            //$finalresp['error'] = false; //para pruebas
                            if($finalresp['error'] === true ){
                                
                                $respuesta['xml'] = " Archivo Xml RECIBIDO NO AUTORIZADO";
                                $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;
                                $errorXml = true;
                            }else{
                                
                                $respuesta['xml'] = " Archivo Xml RECIBIDO AUTORIZADO";
                                $respuesta['Archivo'] = ( array_key_exists('mensaje', $finalresp) ) ? $finalresp['mensaje'] : '' ;
                                
                                $fechaAutorizado = $finalresp['fecauto'];
                                
                                
                            }
                            
                            
                        }else{
                            
                            $respuesta['xml'] = " Archivo Xml NO RECIBIDO";
                            $respuesta['Archivo'] = ( array_key_exists('mensaje', $Envioresp) ) ? $Envioresp['mensaje'] : '' ;
                            $errorXml = true;
                        }
                        
                    }else{
                        
                        $respuesta['xml'] = " Archivo Xml NO FIRMADO";
                        $respuesta['Archivo'] = ( array_key_exists('mensaje', $aux) ) ? $aux['mensaje'] : '' ;
                        $errorXml = true;
                    }
                    
                    
                    if( $errorXml ){
                        
                        $claveAcceso = $resp['claveAcceso'];
                        $_columnaActualizar = " autorizado_retenciones = false ";
                        $_tablaActualizar   = " tri_retenciones";
                        $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                        $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                        
                        
                    }
                    
                }else{
                    $respuesta['xml'] = " ARCHIVO NO ENTRO XML";
                }
            }
            
            
            
            
            if( $errorXml ){
                
                /*
                 $respuesta['icon'] = 'warning';
                 $respuesta['mensaje'] = "Retención Rechazada";
                 $respuesta['estatus'] = 'ERROR';
                 echo json_encode($respuesta);
                 */
                $incorrecto=$incorrecto+1;
                
                
                
                
                
                //actualizar el codigo de retencion
                $_actCol = " valor_consecutivos = valor_consecutivos - 1, numero_consecutivos = LPAD( ( valor_consecutivos - 1)::TEXT,espacio_consecutivos,'0')";
                $_actTab = " consecutivos ";
                $_actWhe = " nombre_consecutivos = 'RETENCION' ";
                $resultadoAct =  $contribucion->ActualizarBy($_actCol, $_actTab, $_actWhe);
                
                if( $resultadoAct == -1 ){
                    return array('error' => true, 'mensaje' => 'Numero Retencion no actualizada');
                }
                
                
                
                
                
                $_triCol1  = " id_tri_retenciones";
                $_triTab1  = " tri_retenciones";
                $_triWhe1  = " infotributaria_claveacceso = '$claveAcceso'";
                $_rstriConsulta1   = $cuentasPagar->getCondicionesSinOrden($_triCol1, $_triTab1, $_triWhe1, "");
                
                if( !empty($_rstriConsulta1) ){
                    
                    $_id_tri_retenciones       = $_rstriConsulta1[0]->id_tri_retenciones;
                    
                    
                    $resuldelte=$cuentasPagar->eliminarFila("tri_retenciones_detalle", "id_tri_retenciones='$_id_tri_retenciones'");
                    $resuldelte1=$cuentasPagar->eliminarFila("tri_retenciones", "id_tri_retenciones='$_id_tri_retenciones'");
                    
                    
                    
                    
                }
                
                
                
                
                
            }else{
                
                
                //procesado correctamente
                
                $claveAcceso = $resp['claveAcceso'];
                $_columnaActualizar = " fecha_autorizacion = '$fechaAutorizado' ";
                $_tablaActualizar   = " tri_retenciones";
                $_whereActualizar   = " infotributaria_claveacceso = '$claveAcceso'";
                $cuentasPagar->ActualizarBy($_columnaActualizar, $_tablaActualizar, $_whereActualizar);
                
                
                
                
                $_columnaActualizar1 = " retencion_liquidacion_cabeza = 'TRUE' ";
                $_tablaActualizar1   = " core_liquidacion_cabeza";
                $_whereActualizar1   = " id_liquidacion_cabeza='$_id_liquidacion_cabeza'";
                $cuentasPagar->ActualizarBy($_columnaActualizar1, $_tablaActualizar1, $_whereActualizar1);
                
                
                
                $correcto=$correcto+1;
                /*
                 $respuesta['icon'] = 'success';
                 $respuesta['mensaje'] = "Retención Generada Correctamente";
                 $respuesta['estatus'] = 'OK';
                 
                 echo json_encode($respuesta);
                 
                 */
                
                
            }
            
            
            
        }
        
        
        
        
        //aqui envio respuesta
        
        $html="";
        
        $respuesta['icon'] = 'success';
        $respuesta['mensaje'] = "Retención Generada Correctamente";
        $respuesta['estatus'] = 'OK';
        $respuesta['html'] = $html;
        $respuesta['correcto'] = $correcto;
        $respuesta['incorrecto'] = $incorrecto;
        $respuesta['xml_error'] = $xml_error;
        
        
        echo json_encode($respuesta);
        
        
        
        
        
    }else{
        
        
        $respuesta['icon'] = 'warning';
        $respuesta['mensaje'] = "No se pudo procesar";
        $respuesta['estatus'] = 'ERROR';
        $respuesta['html'] = $html;
        $respuesta['correcto'] = $correcto;
        $respuesta['incorrecto'] = $incorrecto;
        $respuesta['xml_error'] = $xml_error;
        
        echo json_encode($respuesta);
        
    }
    
    
    
}



















public function genXmlRetencionCesantes($_id_participes){
    
    // session_start();
    $contribucion = new CoreSuperavitPagosModel();
    $cuentasPagar  = new CuentasPagarModel();
    
    
    //codigo 323
    
    //impuestos de tipo retencion
    
    
    $columnas = "pt.id_participes, '05' as tipo_identificacion,
                    (select pt1.cedula_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as cedula_participes,
                    (select concat( RTRIM(LTRIM(pt.apellido_participes)),' ', RTRIM(LTRIM(pt.nombre_participes))) from core_participes pt1 where pt1.id_participes=pt.id_participes) as nombres_participes,
                    (select pt1.apellido_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as apellido_participes,
                    (select pt1.nombre_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as nombre_participes,
                    (select pt1.correo_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as correo_participes,
                    (select pt1.celular_participes from core_participes pt1 where pt1.id_participes=pt.id_participes) as celular_participes,
                    (select RTRIM(LTRIM(pt1.direccion_participes)) from core_participes pt1 where pt1.id_participes=pt.id_participes) as direccion_participes,
                    coalesce(round(sum(cc.ir_patronal_cobrado_ctaind_superavit_pagos+cc.ir_patronal_cobrado_cxpdf_superavit_pagos)*(-1),2),0) as valor_patronal,
                    coalesce(sum(cc.ctaind_patronal_superavit_pagos+cc.cxpdf_patronal_superavit_pagos),0) as baseimponible
                    ";
    $tablas = "core_participes pt
                    inner join core_superavit_pagos cc on pt.id_participes=cc.id_participes
                    ";
    $where = "cc.diario_origen_superavit_pagos in (-20202020) and pt.retencion_generada_impuestos_cta_ces='FALSE' and cc.id_estatus=1 and pt.id_participes= '$_id_participes'";
    
    $grupo = "pt.id_participes";
    $condicion_grupo = "sum(cc.ctaind_patronal_superavit_pagos+cc.cxpdf_patronal_superavit_pagos)>0";
    $id       = "pt.id_participes";
    
    
    $rsConsulta1=$contribucion->getCondiciones_grupo($columnas, $tablas, $where, $grupo.' HAVING '.$condicion_grupo, $id);
    
    
    
    
    //datos de la empresa
    $col3  = " id_entidades, ruc_entidades, nombre_entidades, telefono_entidades, direccion_entidades, ciudad_entidades, razon_social_entidades";
    $tab3  = " entidades";
    $whe3  = " 1 = 1
               AND nombre_entidades = 'CAPREMCI'";
    $id3   = " creado";
    $rsConsulta3   = $contribucion->getCondiciones($col3, $tab3, $whe3, $id3); //array de empresa
    
    
    
    
    //datos de consecutivo
    $col4  = " LPAD( valor_consecutivos::TEXT,espacio_consecutivos,'0') secuencial";
    $tab4  = " consecutivos";
    $whe4  = " 1 = 1
               AND nombre_consecutivos = 'RETENCION'";
    $id4   = " creado";
    $rsConsulta4   = $contribucion->getCondiciones($col4, $tab4, $whe4, $id4); //array de empresa
    
    
    
    
    //actualizar el codigo de retencion
    $_actCol = " valor_consecutivos = valor_consecutivos + 1, numero_consecutivos = LPAD( ( valor_consecutivos + 1)::TEXT,espacio_consecutivos,'0')";
    $_actTab = " consecutivos ";
    $_actWhe = " nombre_consecutivos = 'RETENCION' ";
    $resultadoAct =  $contribucion->ActualizarBy($_actCol, $_actTab, $_actWhe);
    
    if( $resultadoAct == -1 ){
        return array('error' => true, 'mensaje' => 'Numero Retencion no actualizada');
    }
    
    
    
    
    /** validacion de parametros **/
    if( empty($rsConsulta1) || empty($rsConsulta3) || empty($rsConsulta4) ){
        //echo "Error validacion llego ";
        return array('error' => true, 'mensaje' => 'Consultas no contiene todos los datos');
    }
    
    
    
    
    
    
    /** AUX de VARIABLES **/
    
    
    $_fechaDocumento =    "31".'/'."01".'/'."2021";
    
    /** VARIABLES DE XML **/
    $_ambiente = 2; //1 pruebas  2 produccion
    $_tipoEmision = 1; //1 emision normal deacuerdo a la tabla 2 SRI
    $_rucEmisor  = $rsConsulta3[0]->ruc_entidades;
    $_razonSocial = $rsConsulta3[0]->razon_social_entidades;
    $_nomComercial= $rsConsulta3[0]->nombre_entidades;
    $_codDocumento= "07"; // referenciado a la tabla 4 del sri
    $_establecimiento = "001"; //definir de la estructura  001-001-000000 -- factura !!!!------>NOTA
    $_puntoEmision    = "001"; //solo existe un establecimiento
    $_secuencial      = $rsConsulta4[0]->secuencial;   // es un secuencial tiene que definirse
    $_dirMatriz       = $rsConsulta3[0]->direccion_entidades;
    $_fechaEmision    =  $_fechaDocumento;//definir la fecha
    $_dirEstablecimiento   = $rsConsulta3[0]->direccion_entidades;
    
    // /** informacion rtencion **/ //datos obtener de la tabla proveedores
    $_contriEspecial  = "624";  //numero definir para otra empresa !!!!------>NOTA ----- OJO -- tomara de la tabla entidades
    $_obligadoContabilidad = "SI"; //TEXTO definir para otra empresa !!!!------>NOTA ----- OJO --tomara de la tabla entidades
    $_tipoIdentificacionRetenido   = $rsConsulta1[0]->tipo_identificacion; // deacuerdo a la tabla 7 --> ruc 04
    $_razonSocialRetenido  = $rsConsulta1[0]->nombres_participes;
    $_identificacionSujetoRetenido = $rsConsulta1[0]->cedula_participes;
    $_periodoFiscal        = "31".'/'."2021";
    
    $_claveAcceso = $this->genClaveAcceso($_fechaEmision, $_rucEmisor, $_ambiente, $_establecimiento, $_puntoEmision, $_secuencial, $_tipoEmision);
    
    if( $_claveAcceso == "" || strlen($_claveAcceso) != 49 ){
        return array('error' => true, 'mensaje' => 'Clave de acceso no generada','ErrClave'=>$_claveAcceso);
    }
    
    $texto = "";
    $texto .='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    $texto .= '<comprobanteRetencion id="comprobante" version="1.0.0">';
    $texto .= '<infoTributaria>';
    $texto .= '<ambiente>'.$_ambiente.'</ambiente>'; //conforme a la tabla 4
    $texto .= '<tipoEmision>'.$_tipoEmision.'</tipoEmision>'; //conforme a la tabla 2
    $texto .= '<razonSocial>'.htmlspecialchars($_razonSocial).'</razonSocial>';
    $texto .= '<nombreComercial>'.htmlspecialchars($_nomComercial).'</nombreComercial>';
    $texto .= '<ruc>'.$_rucEmisor.'</ruc>';
    $texto .= '<claveAcceso>'.$_claveAcceso.'</claveAcceso>'; //conforme a la tabla 1
    $texto .= '<codDoc>'.$_codDocumento.'</codDoc>'; //conforme a la tabla 3
    $texto .= '<estab>'.$_establecimiento.'</estab>';
    $texto .= '<ptoEmi>'.$_puntoEmision.'</ptoEmi>';
    $texto .= '<secuencial>'.$_secuencial.'</secuencial>';
    $texto .= '<dirMatriz>'.$_dirMatriz.'</dirMatriz>';
    $texto .= '</infoTributaria>';
    
    $texto .= '<infoCompRetencion>';
    $texto .= '<fechaEmision>'.$_fechaEmision.'</fechaEmision>'; //conforme al formato -- dd/mm/aaaa
    $texto .= '<dirEstablecimiento>'.$_dirEstablecimiento.'</dirEstablecimiento>';
    $texto .= '<contribuyenteEspecial>'.$_contriEspecial.'</contribuyenteEspecial>';
    $texto .= '<obligadoContabilidad>'.$_obligadoContabilidad.'</obligadoContabilidad>';
    $texto .= '<tipoIdentificacionSujetoRetenido>'.$_tipoIdentificacionRetenido.'</tipoIdentificacionSujetoRetenido>'; // conforme a la tabla 6
    $texto .= '<razonSocialSujetoRetenido>'.$_razonSocialRetenido.'</razonSocialSujetoRetenido>';
    $texto .= '<identificacionSujetoRetenido>'.$_identificacionSujetoRetenido.'</identificacionSujetoRetenido>';
    $texto .= '<periodoFiscal>'.$_periodoFiscal.'</periodoFiscal>'; //conforme a formato mm/aaaa
    $texto .= '</infoCompRetencion>';
    
    $texto .= '<impuestos>'; //aqui comienza el foreach de impuestos
    
    /** VARIABLES PARA CADA IMPUESTO **/
    $_impCodigo = "";
    $_impCodRetencion = "";
    $_impBaseImponible = "";
    $_impPorcetajeRet  = "";
    $_impValorRet      = "";
    $_impCodDocumentoSustentoRet = "12"; //!NOTA
    $_impNumDocumentoSustentoRet = "";
    $_impfechaEmisionRet   = $_fechaEmision;
    
    $_impNumDocumentoSustentoRet = "999999999999999";
    //$_impNumDocumentoSustentoRet = str_replace("-", "", $_impNumDocumentoSustentoRet);
    
    
    
    $_impCodigo = 1;
    $_impCodRetencion = 323;
    $_impBaseImponible = $rsConsulta1[0]->baseimponible;
    $_impPorcetajeRet = -2.00;
    $_impValorRet = $rsConsulta1[0]->valor_patronal;
    
    $texto .= '<impuesto>';
    $texto .= '<codigo>'.$_impCodigo.'</codigo>'; //conforme a la tabla 20
    $texto .= '<codigoRetencion>'.$_impCodRetencion.'</codigoRetencion>'; //conforme a la tabla 21
    $texto .= '<baseImponible>'.round($_impBaseImponible,2).'</baseImponible>';
    $texto .= '<porcentajeRetener>'.round(abs($_impPorcetajeRet),2).'</porcentajeRetener>';//conforme a la tabla 21
    $texto .= '<valorRetenido>'.round(abs($_impValorRet),2).'</valorRetenido>';
    $texto .= '<codDocSustento>'.$_impCodDocumentoSustentoRet.'</codDocSustento>';
    $texto .= '<numDocSustento>'.$_impNumDocumentoSustentoRet.'</numDocSustento>'; //num documento soporte sin '-'
    $texto .= '<fechaEmisionDocSustento>'.$_impfechaEmisionRet.'</fechaEmisionDocSustento>'; //obligatorio cuando corresponda **formato dd/mm/aaaa
    $texto .= '</impuesto>';
    
    $texto .= '</impuestos>';
    
    /** obligatorio cuando corresponda **/
    // se toma datos de proveedor -- Direccion. Telefono. Correo
    /**CAMPOS ADICIONALES **/
    $_adicional1 = $rsConsulta1[0]->direccion_participes;
    if(!empty($_adicional1)){
        
    }else{
        
        $_adicional1="Ninguna";
    }
    
    $_adicional2 = $rsConsulta1[0]->celular_participes;
    
    if(!empty($_adicional2)){
        
    }else{
        
        $_adicional2="0999999999";
    }
    
    $_adicional3 = $rsConsulta1[0]->correo_participes;
    
    if(!empty($_adicional3)){
        
    }else{
        
        $_adicional3="ninguno@capremci.com.ec";
    }
    
    $texto .= '<infoAdicional>';
    $texto .= '<campoAdicional nombre="Dirección">'.$_adicional1.'</campoAdicional>';
    $texto .= '<campoAdicional nombre="Teléfono">'.$_adicional2.'</campoAdicional>';
    $texto .= '<campoAdicional nombre="Email">'.$_adicional3.'</campoAdicional>';
    $texto .= '</infoAdicional>';
    /** termina obligatorio cuando corresponda **/
    
    $texto .= '</comprobanteRetencion>';
    
    $resp = null;
    
    
    
    try {
        
        
        $nombre_archivo = $_claveAcceso.".xml";
        $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\DOCUMENTOSELECTRONICOS\\docGenerados\\";
        $ubicacion = $ubicacionServer.$nombre_archivo;
        
        $textoXML = mb_convert_encoding($texto, "UTF-8");
        
        $gestor = fopen($ubicacionServer.$nombre_archivo, 'w');
        fwrite($gestor, $textoXML);
        fclose($gestor);
        
        if( file_exists( $ubicacion ) ){
            //echo "archivo existe";
            /** SE GENERA UN INSERT A LA TABLA tri_retenciones con la columnName autorizado_retenciones en true **/
            
            $_trifuncion = "ins_tri_retenciones";
            $_triparametros =  "$_ambiente,$_tipoEmision,'$_razonSocial','$_nomComercial','$_rucEmisor','$_claveAcceso','$_codDocumento','$_establecimiento',";
            $_triparametros .= "'$_puntoEmision','$_secuencial','$_dirMatriz','$_fechaEmision','$_dirEstablecimiento',$_contriEspecial,'$_obligadoContabilidad',";
            $_triparametros .= "'$_tipoIdentificacionRetenido','$_razonSocialRetenido','$_identificacionSujetoRetenido','$_periodoFiscal',0,0,0.00,0.00,0.00,";
            $_triparametros .= "'','','$_fechaEmision',0,0,0.00,0.00,0.00,'','','$_fechaEmision','$_adicional1','$_adicional2','$_adicional3','$_fechaEmision'";
            
            $_qryTriRetenciones    = $cuentasPagar->getconsultaPG($_trifuncion, $_triparametros);
            $resultado     = $cuentasPagar->llamarconsultaPG($_qryTriRetenciones);
            
            $error = pg_last_error();
            if( !empty($error) ){
                throw new Exception('Error al guardar datos Xml en BD');
            }
            
            if( $resultado[0] == 1 ){
                /** SE GENERA INSERTADO DEL DETALLE DEL ARCHIVO XML **/
                $_triCol1  = " id_tri_retenciones ";
                $_triTab1  = " tri_retenciones ";
                $_triWhe1  = " infotributaria_claveacceso = '$_claveAcceso'";
                $_rstriConsulta1   = $cuentasPagar->getCondicionesSinOrden($_triCol1, $_triTab1, $_triWhe1, "");
                
                if( !empty($_rstriConsulta1) ){
                    
                    $_tri_detallefuncion       = "ins_tri_retenciones_detalle";
                    $_id_tri_retenciones       = $_rstriConsulta1[0]->id_tri_retenciones;
                    
                    
                    
                    $_tri_detalleparametros    = "";
                    
                    
                    $_impPorcetajeRet = abs(-2.00);
                    
                    
                    $_tri_detalleparametros    .= "$_id_tri_retenciones,$_impCodigo,'$_impCodRetencion',$_impBaseImponible,$_impPorcetajeRet,$_impValorRet,";
                    $_tri_detalleparametros    .= "'$_impCodDocumentoSustentoRet','$_impNumDocumentoSustentoRet','$_impfechaEmisionRet'";
                    $_qryTriDetalleRetenciones = $cuentasPagar->getconsultaPG($_tri_detallefuncion, $_tri_detalleparametros);
                    
                    $resultadoDetalle  = $cuentasPagar->llamarconsultaPG($_qryTriDetalleRetenciones); /** insertado del detalle de retenciones **/
                    
                    
                    
                    
                    
                    
                    $error = pg_last_error();
                    if( !empty($error) ){
                        $ins_detalle = false;
                        
                    }
                    
                    
                    if( !empty($error) && isset($ins_detalle) && !$ins_detalle){
                        throw new Exception('Error al guardar Detalles Impuestos datos Xml en BD');
                    }
                    
                }
                
            }
            
            $resp['error'] = false;
            $resp['mensaje'] = 'XML GENERADO';
            $resp['claveAcceso'] = $_claveAcceso;
            
        }else{
            throw new Exception('XML NO GENERADO');
            
        }
        
    } catch (Exception $e) {
        
        $resp['error'] = true;
        $resp['mensaje'] = $e->getMessage();
        $resp['claveAcceso'] = $_claveAcceso;
    }
    
    
    return $resp;
    
    
}






//AQUI CAMBIAR LAS FECHAS 

public function genXmlRetencionCesantiasPatronales($_id_liquidacion_cabeza){
    
    // session_start();
    $contribucion = new CoreSuperavitPagosModel();
    $cuentasPagar  = new CuentasPagarModel();
    
    
    //codigo 323
    
    //impuestos de tipo retencion
    
    
    
    
    $columnas = " '05' as tipo_identificacion, cp.id_participes, clc.fecha_pago_carpeta_liquidacion_cabeza, cp.cedula_participes ,
	                 cp.apellido_participes , cp.nombre_participes , cld.motivo_liquidacion_detalle ,
	                  cld.porcentaje_liquidacion_detalle , cld.base_calcuo_liquidacion_detalle , cld.valor_liquidacion_detalle * -1 as valor_liquidacion_detalle , 
                      cp.direccion_participes , cp.celular_participes , cp.correo_participes ";
    $tablas = "core_participes cp , core_liquidacion_cabeza clc , core_liquidacion_detalle cld";
    $where = "clc.id_participes = cp.id_participes
                     and clc.retencion_liquidacion_cabeza='false'
                    AND  clc.id_liquidacion_cabeza =   '$_id_liquidacion_cabeza'
                    and clc.id_liquidacion_cabeza = cld.id_liquidacion_cabeza
            	    and clc.id_estado_prestaciones in (3,4)
            	    and clc.id_tipo_prestaciones not in (3)
            	    and cld.id_tipo_pago_liquidacion = 8
            	    and (cld.motivo_liquidacion_detalle LIKE '%Patronal%' or cld.motivo_liquidacion_detalle LIKE '%PATRONAL%' ) ";
    
    $id       = "clc.fecha_pago_carpeta_liquidacion_cabeza";
    
    
    
    
    $rsConsulta1=$contribucion->getCondiciones($columnas, $tablas, $where, $id);
    
    
    
    
    //datos de la empresa
    $col3  = " id_entidades, ruc_entidades, nombre_entidades, telefono_entidades, direccion_entidades, ciudad_entidades, razon_social_entidades";
    $tab3  = " entidades";
    $whe3  = " 1 = 1
               AND nombre_entidades = 'CAPREMCI'";
    $id3   = " creado";
    $rsConsulta3   = $contribucion->getCondiciones($col3, $tab3, $whe3, $id3); //array de empresa
    
    
    
    
    //datos de consecutivo
    $col4  = " LPAD( valor_consecutivos::TEXT,espacio_consecutivos,'0') secuencial";
    $tab4  = " consecutivos";
    $whe4  = " 1 = 1
               AND nombre_consecutivos = 'RETENCION'";
    $id4   = " creado";
    $rsConsulta4   = $contribucion->getCondiciones($col4, $tab4, $whe4, $id4); //array de empresa
    
    
    
    
    //actualizar el codigo de retencion
    $_actCol = " valor_consecutivos = valor_consecutivos + 1, numero_consecutivos = LPAD( ( valor_consecutivos + 1)::TEXT,espacio_consecutivos,'0')";
    $_actTab = " consecutivos ";
    $_actWhe = " nombre_consecutivos = 'RETENCION' ";
    $resultadoAct =  $contribucion->ActualizarBy($_actCol, $_actTab, $_actWhe);
    
    if( $resultadoAct == -1 ){
        return array('error' => true, 'mensaje' => 'Numero Retencion no actualizada');
    }
    
    
    
    
    /** validacion de parametros **/
    if( empty($rsConsulta1) || empty($rsConsulta3) || empty($rsConsulta4) ){
        //echo "Error validacion llego ";
        return array('error' => true, 'mensaje' => 'Consultas no contiene todos los datos');
    }
    
    
    
    
    
    
    /** AUX de VARIABLES **/
    
    
    $_fechaDocumento =    "31".'/'."10".'/'."2020";
    
    /** VARIABLES DE XML **/        
    $_ambiente = 2; //1 pruebas  2 produccion
    $_tipoEmision = 1; //1 emision normal deacuerdo a la tabla 2 SRI
    $_rucEmisor  = $rsConsulta3[0]->ruc_entidades;
    $_razonSocial = $rsConsulta3[0]->razon_social_entidades;
    $_nomComercial= $rsConsulta3[0]->nombre_entidades;
    $_codDocumento= "07"; // referenciado a la tabla 4 del sri
    $_establecimiento = "001"; //definir de la estructura  001-001-000000 -- factura !!!!------>NOTA
    $_puntoEmision    = "001"; //solo existe un establecimiento
    $_secuencial      = $rsConsulta4[0]->secuencial;   // es un secuencial tiene que definirse
    $_dirMatriz       = $rsConsulta3[0]->direccion_entidades;
    $_fechaEmision    =  $_fechaDocumento;//definir la fecha
    $_dirEstablecimiento   = $rsConsulta3[0]->direccion_entidades;
    
    // /** informacion rtencion **/ //datos obtener de la tabla proveedores
    $_contriEspecial  = "624";  //numero definir para otra empresa !!!!------>NOTA ----- OJO -- tomara de la tabla entidades
    $_obligadoContabilidad = "SI"; //TEXTO definir para otra empresa !!!!------>NOTA ----- OJO --tomara de la tabla entidades
    $_tipoIdentificacionRetenido   = $rsConsulta1[0]->tipo_identificacion; // deacuerdo a la tabla 7 --> ruc 04
    $_razonSocialRetenido  = $rsConsulta1[0]->nombre_participes . " ". $rsConsulta1[0]->apellido_participes;
    $_identificacionSujetoRetenido = $rsConsulta1[0]->cedula_participes;
    $_periodoFiscal        = "10".'/'."2020";
    
    $_claveAcceso = $this->genClaveAcceso($_fechaEmision, $_rucEmisor, $_ambiente, $_establecimiento, $_puntoEmision, $_secuencial, $_tipoEmision);
    
    if( $_claveAcceso == "" || strlen($_claveAcceso) != 49 ){
        return array('error' => true, 'mensaje' => 'Clave de acceso no generada','ErrClave'=>$_claveAcceso);
    }
    
    $texto = "";
    $texto .='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    $texto .= '<comprobanteRetencion id="comprobante" version="1.0.0">';
    $texto .= '<infoTributaria>';
    $texto .= '<ambiente>'.$_ambiente.'</ambiente>'; //conforme a la tabla 4
    $texto .= '<tipoEmision>'.$_tipoEmision.'</tipoEmision>'; //conforme a la tabla 2
    $texto .= '<razonSocial>'.htmlspecialchars($_razonSocial).'</razonSocial>';
    $texto .= '<nombreComercial>'.htmlspecialchars($_nomComercial).'</nombreComercial>';
    $texto .= '<ruc>'.$_rucEmisor.'</ruc>';
    $texto .= '<claveAcceso>'.$_claveAcceso.'</claveAcceso>'; //conforme a la tabla 1
    $texto .= '<codDoc>'.$_codDocumento.'</codDoc>'; //conforme a la tabla 3
    $texto .= '<estab>'.$_establecimiento.'</estab>';
    $texto .= '<ptoEmi>'.$_puntoEmision.'</ptoEmi>';
    $texto .= '<secuencial>'.$_secuencial.'</secuencial>';
    $texto .= '<dirMatriz>'.$_dirMatriz.'</dirMatriz>';
    $texto .= '</infoTributaria>';
    
    $texto .= '<infoCompRetencion>';
    $texto .= '<fechaEmision>'.$_fechaEmision.'</fechaEmision>'; //conforme al formato -- dd/mm/aaaa
    $texto .= '<dirEstablecimiento>'.$_dirEstablecimiento.'</dirEstablecimiento>';
    $texto .= '<contribuyenteEspecial>'.$_contriEspecial.'</contribuyenteEspecial>';
    $texto .= '<obligadoContabilidad>'.$_obligadoContabilidad.'</obligadoContabilidad>';
    $texto .= '<tipoIdentificacionSujetoRetenido>'.$_tipoIdentificacionRetenido.'</tipoIdentificacionSujetoRetenido>'; // conforme a la tabla 6
    $texto .= '<razonSocialSujetoRetenido>'.$_razonSocialRetenido.'</razonSocialSujetoRetenido>';
    $texto .= '<identificacionSujetoRetenido>'.$_identificacionSujetoRetenido.'</identificacionSujetoRetenido>';
    $texto .= '<periodoFiscal>'.$_periodoFiscal.'</periodoFiscal>'; //conforme a formato mm/aaaa
    $texto .= '</infoCompRetencion>';
    
    $texto .= '<impuestos>'; //aqui comienza el foreach de impuestos
    
    /** VARIABLES PARA CADA IMPUESTO **/
    $_impCodigo = "";
    $_impCodRetencion = "";
    $_impBaseImponible = "";
    $_impPorcetajeRet  = "";
    $_impValorRet      = "";
    $_impCodDocumentoSustentoRet = "12"; //!NOTA
    $_impNumDocumentoSustentoRet = "";
    $_impfechaEmisionRet   = $_fechaEmision;
    
    $_impNumDocumentoSustentoRet = "999999999999999";
    //$_impNumDocumentoSustentoRet = str_replace("-", "", $_impNumDocumentoSustentoRet);
    
    
    
    $_impCodigo = 1;
    $_impCodRetencion = 323;
    $_impBaseImponible = $rsConsulta1[0]->base_calcuo_liquidacion_detalle;
    $_impPorcetajeRet = -2.00;
    $_impValorRet = $rsConsulta1[0]->valor_liquidacion_detalle;
    
    $texto .= '<impuesto>';
    $texto .= '<codigo>'.$_impCodigo.'</codigo>'; //conforme a la tabla 20
    $texto .= '<codigoRetencion>'.$_impCodRetencion.'</codigoRetencion>'; //conforme a la tabla 21
    $texto .= '<baseImponible>'.round($_impBaseImponible,2).'</baseImponible>';
    $texto .= '<porcentajeRetener>'.round(abs($_impPorcetajeRet),2).'</porcentajeRetener>';//conforme a la tabla 21
    $texto .= '<valorRetenido>'.round(abs($_impValorRet),2).'</valorRetenido>';
    $texto .= '<codDocSustento>'.$_impCodDocumentoSustentoRet.'</codDocSustento>';
    $texto .= '<numDocSustento>'.$_impNumDocumentoSustentoRet.'</numDocSustento>'; //num documento soporte sin '-'
    $texto .= '<fechaEmisionDocSustento>'.$_impfechaEmisionRet.'</fechaEmisionDocSustento>'; //obligatorio cuando corresponda **formato dd/mm/aaaa
    $texto .= '</impuesto>';
    
    $texto .= '</impuestos>';
    
    /** obligatorio cuando corresponda **/
    // se toma datos de proveedor -- Direccion. Telefono. Correo
    /**CAMPOS ADICIONALES **/
    $_adicional1 = $rsConsulta1[0]->direccion_participes;
    if(!empty($_adicional1)){
        
    }else{
        
        $_adicional1="Ninguna";
    }
    
    $_adicional2 = $rsConsulta1[0]->celular_participes;
    
    if(!empty($_adicional2)){
        
    }else{
        
        $_adicional2="0999999999";
    }
    
    $_adicional3 = $rsConsulta1[0]->correo_participes;
    
    if(!empty($_adicional3)){
        
    }else{
        
        $_adicional3="ninguno@capremci.com.ec";
    }
    
    $texto .= '<infoAdicional>';
    $texto .= '<campoAdicional nombre="Dirección">'.$_adicional1.'</campoAdicional>';
    $texto .= '<campoAdicional nombre="Teléfono">'.$_adicional2.'</campoAdicional>';
    $texto .= '<campoAdicional nombre="Email">'.$_adicional3.'</campoAdicional>';
    $texto .= '</infoAdicional>';
    /** termina obligatorio cuando corresponda **/
    
    $texto .= '</comprobanteRetencion>';
    
    $resp = null;
    
    
    
    try {
        
        
        $nombre_archivo = $_claveAcceso.".xml";
        $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\DOCUMENTOSELECTRONICOS\\docGenerados\\";
        $ubicacion = $ubicacionServer.$nombre_archivo;
        
        $textoXML = mb_convert_encoding($texto, "UTF-8");
        
        $gestor = fopen($ubicacionServer.$nombre_archivo, 'w');
        fwrite($gestor, $textoXML);
        fclose($gestor);
        
        if( file_exists( $ubicacion ) ){
            //echo "archivo existe";
            /** SE GENERA UN INSERT A LA TABLA tri_retenciones con la columnName autorizado_retenciones en true **/
            
            $_trifuncion = "ins_tri_retenciones";
            $_triparametros =  "$_ambiente,$_tipoEmision,'$_razonSocial','$_nomComercial','$_rucEmisor','$_claveAcceso','$_codDocumento','$_establecimiento',";
            $_triparametros .= "'$_puntoEmision','$_secuencial','$_dirMatriz','$_fechaEmision','$_dirEstablecimiento',$_contriEspecial,'$_obligadoContabilidad',";
            $_triparametros .= "'$_tipoIdentificacionRetenido','$_razonSocialRetenido','$_identificacionSujetoRetenido','$_periodoFiscal',0,0,0.00,0.00,0.00,";
            $_triparametros .= "'','','$_fechaEmision',0,0,0.00,0.00,0.00,'','','$_fechaEmision','$_adicional1','$_adicional2','$_adicional3','$_fechaEmision'";
            
            $_qryTriRetenciones    = $cuentasPagar->getconsultaPG($_trifuncion, $_triparametros);
            $resultado     = $cuentasPagar->llamarconsultaPG($_qryTriRetenciones);
            
            $error = pg_last_error();
            if( !empty($error) ){
                throw new Exception('Error al guardar datos Xml en BD');
            }
            
            if( $resultado[0] == 1 ){
                
                    
                /** SE GENERA INSERTADO DEL DETALLE DEL ARCHIVO XML **/
                $_triCol1  = " id_tri_retenciones ";
                $_triTab1  = " tri_retenciones ";
                $_triWhe1  = " infotributaria_claveacceso = '$_claveAcceso'";
                $_rstriConsulta1   = $cuentasPagar->getCondicionesSinOrden($_triCol1, $_triTab1, $_triWhe1, "");
                
                if( !empty($_rstriConsulta1) ){
                    
                    $_tri_detallefuncion       = "ins_tri_retenciones_detalle";
                    $_id_tri_retenciones       = $_rstriConsulta1[0]->id_tri_retenciones;
                    
                    
                    
                    $_tri_detalleparametros    = "";
                    
                    
                    $_impPorcetajeRet = abs(-2.00);
                    
                    
                    $_tri_detalleparametros    .= "$_id_tri_retenciones,$_impCodigo,'$_impCodRetencion',$_impBaseImponible,$_impPorcetajeRet,$_impValorRet,";
                    $_tri_detalleparametros    .= "'$_impCodDocumentoSustentoRet','$_impNumDocumentoSustentoRet','$_impfechaEmisionRet'";
                    $_qryTriDetalleRetenciones = $cuentasPagar->getconsultaPG($_tri_detallefuncion, $_tri_detalleparametros);
                    
                    $resultadoDetalle  = $cuentasPagar->llamarconsultaPG($_qryTriDetalleRetenciones); /** insertado del detalle de retenciones **/
                    
                    
                    
                    
                    
                    
                    $error = pg_last_error();
                    if( !empty($error) ){
                        $ins_detalle = false;
                        
                    }
                    
                    
                    if( !empty($error) && isset($ins_detalle) && !$ins_detalle){
                        throw new Exception('Error al guardar Detalles Impuestos datos Xml en BD');
                    }
                    
                }
                
            }
            
            $resp['error'] = false;
            $resp['mensaje'] = 'XML GENERADO';
            $resp['claveAcceso'] = $_claveAcceso;
            
        }else{
            throw new Exception('XML NO GENERADO');
            
        }
        
    } catch (Exception $e) {
        
        $resp['error'] = true;
        $resp['mensaje'] = $e->getMessage();
        $resp['claveAcceso'] = $_claveAcceso;
    }
    
    
    return $resp;
    
    
}



















	
	
}


?>
