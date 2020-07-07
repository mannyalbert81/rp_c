	<?php

    class ConsultaCandidatosController extends ControladorBase{
	public function __construct() {
		parent::__construct();
		
	}
	
	public function index(){
	    
	    session_start();
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        $controladores = new RegistroModel();
	        $nombre_controladores = "ConsultaCandidatos";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $controladores->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (!empty($resultPer))
	        {
	            
	            
	            
	            $this->view_Credito("ConsultaCandidatos",array(
	                ""=>""
	            ));
	            
	        }
	        else
	        {
	            $this->view("Error",array(
	                "resultado"=>"No tiene Permisos de Acceso"
	                
	            ));
	            
	        }
	        
	        
	    }
	    else
	    {
	        $error = TRUE;
	        $mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
	        
	        $this->view("Login",array(
	            "resultSet"=>"$mensaje", "error"=>$error
	        ));
	        
	        
	        die();
	        
	    }
	    
	}
	
	
	
		

	

	
	public function ConsultaCandidatos(){
	    
	    session_start();
	    $registro = new RegistroModel();
	    $id_rol= $_SESSION['id_rol'];
	    
	    $where_to="";

	    $columnas= "a.id_padron_electoral_representantes,
                    b.apellido_participes,
                    b.cedula_participes,
                    b.nombre_participes,
                    c.apellido_participes as apellido_suplente,
                    c.nombre_participes as nombre_suplente,
                    c.cedula_participes as cedula_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    case when a.acepto_representante_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_representante_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_representante,
                    case when a.acepto_suplente_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_suplente_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_suplente";
	    
	    $tablas =  "padron_electoral_representantes a 
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    left join 
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes 
                	 from padron_electoral_representantes a1 
                 	 inner join core_participes b1 on a1.id_suplente = b1.id_participes and b1.id_estatus = 1
                  	)c on a.id_padron_electoral_representantes = c.id_padron_electoral_representantes";
	    
	    $where = "1=1 and a.estado_candidato_padron_electoral_representantes = 0";
	    
	    $id = "a.id_padron_electoral_representantes";
	    
	    
	   
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND id_representante ILIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$registro->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$registro->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">Acciones</th>';
	            
	            
	               
	            $html.='<th style="text-align: left;  font-size: 13px;">Foto Representante</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Datos Representante</th>';
	            
	            $html.='<th style="text-align: left;  font-size: 13px;">Foto Suplente</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Datos Suplente</th>';
	            
	            $html.='<th style="text-align: left;  font-size: 12px;">Formulario</th>';
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                
	                
	                $i++;
	                $html.='<tr>';
	             
	                if($res->acepto_representante==-'Acepto' && $res->acepto_suplente=='Acepto'){
	                    
	                    $html.='<td style="font-size: 14px;"><a onclick="AprobarRegistro('.$res->id_padron_electoral_representantes.')"   href="JavaScript:void(0);" class="btn btn-success" style="font-size:65%;"data-toggle="tooltip" title="Aprobar"><i class="glyphicon glyphicon-plus"></i></a>
                    <a onclick="NegarRegistro('.$res->id_padron_electoral_representantes.')"   href="JavaScript:void(0);" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Negar"><i class="glyphicon glyphicon-remove"></i></a></td>';
	                }
	                else {
	                    $html.='<td style="font-size: 14px;"><a href="JavaScript:void(0);" class="btn btn-success" style="font-size:65%;"data-toggle="tooltip" title="Aprobar" disabled><i class="glyphicon glyphicon-plus"></i></a>
                    <a href="JavaScript:void(0);" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Negar" disabled><i class="glyphicon glyphicon-remove"></i></a></td>';
	                }
	                 $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="80" height="60"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_participes.'</br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'</br><b>CORREO: </b>'.$res->correo_representante.'</br><b>ACEPTO: </b>'.$res->acepto_representante.'</td>';
	              
	                $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_suplente" width="80" height="60"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_suplente.'</br><b>NOMBRE: </b>'.$res->apellido_suplente.' '.$res->nombre_suplente.'</br><b>CORREO: </b>'.$res->correo_suplente.'</br><b>ACEPTO: </b>'.$res->acepto_suplente.'</td>';
	                
	                if($res->acepto_representante==-'Acepto' && $res->acepto_suplente=='Acepto'){
	                    
	                    $html.='<td><a title="PDF" href="index.php?controller=CargarParticipes&action=ReporteCandidatos&id_padron_electoral_representantes='.$res->id_padron_electoral_representantes.'" role="button" target="_blank"><img src="view/images/logo_pdf.png" width="30" height="30"></a></font></td>';
	                }
	                else {
	                
	                }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_ConsultaCandidatos("index.php", $page, $total_pages, $adjacents,"ConsultaCandidatos").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay  Registros...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	    
	}

	public function paginate_ConsultaCandidatos($reload, $page, $tpages, $adjacents, $funcion = "") {
	 //Steven   
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
	
	public function ConsultaCandidatosAprobado(){
	    
	    session_start();
	    $registro = new RegistroModel();
	    $id_rol= $_SESSION['id_rol'];
	    
	    $where_to="";
	    
	    $columnas= "a.id_padron_electoral_representantes,
                    b.apellido_participes,
                    b.cedula_participes,
                    b.nombre_participes,
                    c.apellido_participes as apellido_suplente,
                    c.nombre_participes as nombre_suplente,
                    c.cedula_participes as cedula_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    case when a.acepto_representante_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_representante_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_representante,
                    case when a.acepto_suplente_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_suplente_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_suplente";
	    
	    $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes
                	 from padron_electoral_representantes a1
                 	 inner join core_participes b1 on a1.id_suplente = b1.id_participes and b1.id_estatus = 1
                  	)c on a.id_padron_electoral_representantes = c.id_padron_electoral_representantes";
	    
	    $where = "1=1 and a.estado_candidato_padron_electoral_representantes = 1";
	    
	    $id = "a.id_padron_electoral_representantes";
	    
	    
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND id_representante ILIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$registro->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$registro->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	        
	            $html.='<th style="text-align: left;  font-size: 13px;">Foto Representante</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Datos Representante</th>';
	            
	            $html.='<th style="text-align: left;  font-size: 13px;">Foto Suplente</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Datos Suplente</th>';
	          
	            $html.='<th style="text-align: left;  font-size: 12px;">Formulario</th>';
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                
	                
	                $i++;
	                $html.='<tr>';
	                
	              
	               
	                $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="80" height="60"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_participes.'</br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'</br><b>CORREO: </b>'.$res->correo_representante.'</br><b>ACEPTO: </b>'.$res->acepto_representante.'</td>';
	                
	                $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_suplente" width="80" height="60"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_suplente.'</br><b>NOMBRE: </b>'.$res->apellido_suplente.' '.$res->nombre_suplente.'</br><b>CORREO: </b>'.$res->correo_suplente.'</br><b>ACEPTO: </b>'.$res->acepto_suplente.'</td>';
	                
	                if($res->acepto_representante==-'Acepto' && $res->acepto_suplente=='Acepto'){
	                    
	                    $html.='<td><a title="PDF" href="index.php?controller=CargarParticipes&action=ReporteCandidatos&id_padron_electoral_representantes='.$res->id_padron_electoral_representantes.'" role="button" target="_blank"><img src="view/images/logo_pdf.png" width="30" height="30"></a></font></td>';
	                }
	                else {
	                    
	                }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_ConsultaCandidatos("index.php", $page, $total_pages, $adjacents,"ConsultaCandidatos").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay  Registros...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	    
	}
	
	public function paginateConsultaCandidatosAprobado($reload, $page, $tpages, $adjacents, $funcion = "") {
	    //Steven
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
	
	public function ConsultaCandidatosNegado(){
	    
	    session_start();
	    $registro = new RegistroModel();
	    $id_rol= $_SESSION['id_rol'];
	    
	    $where_to="";
	    
	    $columnas= "a.id_padron_electoral_representantes,
                    b.apellido_participes,
                    b.cedula_participes,
                    b.nombre_participes,
                    c.apellido_participes as apellido_suplente,
                    c.nombre_participes as nombre_suplente,
                    c.cedula_participes as cedula_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    case when a.acepto_representante_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_representante_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_representante,
                    case when a.acepto_suplente_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_suplente_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_suplente";
	    
	    $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes
                	 from padron_electoral_representantes a1
                 	 inner join core_participes b1 on a1.id_suplente = b1.id_participes and b1.id_estatus = 1
                  	)c on a.id_padron_electoral_representantes = c.id_padron_electoral_representantes";
	    
	    $where = "1=1 and a.estado_candidato_padron_electoral_representantes = 2";
	    
	    $id = "a.id_padron_electoral_representantes";
	    
	    
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND id_representante ILIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$registro->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$registro->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            
	            $html.='<th style="text-align: left;  font-size: 13px;">Foto Representante</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Datos Representante</th>';
	            
	            $html.='<th style="text-align: left;  font-size: 13px;">Foto Suplente</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Datos Suplente</th>';
	            
	            $html.='<th style="text-align: left;  font-size: 12px;">Formulario</th>';
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                
	                
	                $i++;
	                $html.='<tr>';
	                
	                
	                
	                $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="80" height="60"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_participes.'</br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'</br><b>CORREO: </b>'.$res->correo_representante.'</br><b>ACEPTO: </b>'.$res->acepto_representante.'</td>';
	                
	                $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_suplente" width="80" height="60"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_suplente.'</br><b>NOMBRE: </b>'.$res->apellido_suplente.' '.$res->nombre_suplente.'</br><b>CORREO: </b>'.$res->correo_suplente.'</br><b>ACEPTO: </b>'.$res->acepto_suplente.'</td>';
	                
	                if($res->acepto_representante==-'Acepto' && $res->acepto_suplente=='Acepto'){
	                    
	                    $html.='<td><a title="PDF" href="index.php?controller=CargarParticipes&action=ReporteCandidatos&id_padron_electoral_representantes='.$res->id_padron_electoral_representantes.'" role="button" target="_blank"><img src="view/images/logo_pdf.png" width="30" height="30"></a></font></td>';
	                }
	                else {
	                    
	                }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_ConsultaCandidatos("index.php", $page, $total_pages, $adjacents,"ConsultaCandidatos").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay  Registros...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	    
	}
	
	public function paginate_ConsultaCandidatosNegado($reload, $page, $tpages, $adjacents, $funcion = "") {
	    //Steven
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
	
	
	public function AprobarRegistro(){
	    
	    session_start();
	    $registro = new RegistroModel();
	     
	        if(isset($_POST["id_padron_electoral_representantes"])){
	            
	            $id_padron_electoral_representantes = (int)$_POST["id_padron_electoral_representantes"];
	            
	            $columna = "estado_candidato_padron_electoral_representantes = 1";
	            $tablas = "padron_electoral_representantes";
	            $where= "id_padron_electoral_representantes = $id_padron_electoral_representantes";
	            $resultado= $registro -> ActualizarBy($columna, $tablas, $where);
	            
	            if( $resultado > 0 ){
	                
	                echo json_encode(array('data'=>$resultado));
	                
	            }else{
	                
	                echo $resultado;
	            }
	            
	            
	            
	        }
	        
	        
	   
	    
	    
	}
	
	public function NegarRegistro(){
	    
	    session_start();
	    $registro = new RegistroModel();
	     
	        if(isset($_POST["id_padron_electoral_representantes"])){
	            
	            $id_padron_electoral_representantes = (int)$_POST["id_padron_electoral_representantes"];
	            
	            $columna = "estado_candidato_padron_electoral_representantes = 2";
	            $tablas = "padron_electoral_representantes";
	            $where= "id_padron_electoral_representantes = $id_padron_electoral_representantes";
	            $resultado= $registro -> ActualizarBy($columna, $tablas, $where);
	            
	            if( $resultado > 0 ){
	                
	                echo json_encode(array('data'=>$resultado));
	                
	            }else{
	                
	                echo $resultado;
	            }
	            
	        }
	        
	        
	   
	    
	    
	}

	
	
	
    }
    
    ?>