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
	
		
	
	public function indexPapeleta(){
	    
	    $participes = new ParticipesModel();
	   
	    $callBack = $_GET['jsoncallback'];
	    
	    $_cedula_participes =(isset($_GET['cedula_participes'])) ? $_GET['cedula_participes'] : 0;
	    
	    
	    
	    $columnasP = "id_participes, apellido_participes, nombre_participes, cedula_participes, 
                    id_entidad_patronal, id_entidad_mayor_patronal";
	    $tablasP = "public.core_participes";
	    $whereP = "cedula_participes = '$_cedula_participes'";
	    $idP = "id_participes";
	    
	    
	    //consultar si voto
	    $resultSetP= $participes->getCondiciones($columnasP, $tablasP, $whereP, $idP);
	    
	    $ip_usuarios = $participes->getRealIP();
	    
	    
	    
	    $iP=count($resultSetP);
	    
	    
	    if($iP>0)
	    {
	        
	        foreach ($resultSetP as $res)
	        {
	            session_start();
	            $_SESSION["ele_id_participes"] =  $res->id_participes;
	            $_SESSION["ele_cedula_participes"] =  $res->cedula_participes;
	            $_SESSION["ele_id_entidad_patronal"] =  $res->id_entidad_patronal;
	            $_SESSION["ele_id_entidad_mayor_patronal"] =  $res->id_entidad_mayor_patronal;
	            $_SESSION["ele_ip_usuarios"] =  $ip_usuarios;
	            
	            
	            $this->view_Elecciones("PapeletaCandidatos",array(
	                "res_cedula_participes"=>$_cedula_participes
	            ));
	            
	            
	        }
	        
	    }
	    else
	    {
	        echo "Usted no tiene derecho al voto";
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
                    c.telefono_participes as telefono_suplente,
                    c.celular_participes as celular_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    b.telefono_participes,
                    b.celular_participes,
                    case when a.acepto_representante_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_representante_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_representante,
                    case when a.acepto_suplente_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_suplente_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_suplente";
	    
	    $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes, b1.telefono_participes, b1.celular_participes
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
	            
	            
	            $where1=" AND b.cedula_participes ILIKE '".$search."%'";
	            
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
	                $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="120" height="100"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_participes.'</br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'</br><b>CORREO: </b>'.$res->correo_representante.'</br><b>TELÉFONO: </b>'.$res->telefono_participes.'</br><b>CELULAR: </b>'.$res->celular_participes.'</br><b>ACEPTO: </b>'.$res->acepto_representante.'</td>';
	                
	                $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_suplente" width="120" height="100"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_suplente.'</br><b>NOMBRE: </b>'.$res->apellido_suplente.' '.$res->nombre_suplente.'</br><b>CORREO: </b>'.$res->correo_suplente.'</br><b>TELÉFONO: </b>'.$res->telefono_suplente.'</br><b>CELULAR: </b>'.$res->celular_suplente.'</br><b>ACEPTO: </b>'.$res->acepto_suplente.'</td>';
	                
	                
	                if($res->acepto_representante==-'Acepto' && $res->acepto_suplente=='Acepto'){
	                    
	                    $html.='<td><a title="PDF" href="index.php?controller=CargarParticipes&action=ReporteCandidatos&id_padron_electoral_representantes='.$res->id_padron_electoral_representantes.'" role="button" target="_blank"><img src="view/images/logo_pdf.png" width="60" height="60"></a></font></td>';
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
                    c.telefono_participes as telefono_suplente,
                    c.celular_participes as celular_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    b.telefono_participes,
                    b.celular_participes,
                    case when a.acepto_representante_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_representante_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_representante,
                    case when a.acepto_suplente_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_suplente_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_suplente";
	    
	    $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes, b1.telefono_participes, b1.celular_participes
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
	            
	            
	            $where1=" AND b.cedula_participes ILIKE '".$search."%'";
	            
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
	                $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="120" height="100"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_participes.'</br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'</br><b>CORREO: </b>'.$res->correo_representante.'</br><b>TELÉFONO: </b>'.$res->telefono_participes.'</br><b>CELULAR: </b>'.$res->celular_participes.'</br><b>ACEPTO: </b>'.$res->acepto_representante.'</td>';
	                
	                $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_suplente" width="120" height="100"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_suplente.'</br><b>NOMBRE: </b>'.$res->apellido_suplente.' '.$res->nombre_suplente.'</br><b>CORREO: </b>'.$res->correo_suplente.'</br><b>TELÉFONO: </b>'.$res->telefono_suplente.'</br><b>CELULAR: </b>'.$res->celular_suplente.'</br><b>ACEPTO: </b>'.$res->acepto_suplente.'</td>';
	                
	                
	                if($res->acepto_representante==-'Acepto' && $res->acepto_suplente=='Acepto'){
	                    
	                    $html.='<td><a title="PDF" href="index.php?controller=CargarParticipes&action=ReporteCandidatos&id_padron_electoral_representantes='.$res->id_padron_electoral_representantes.'" role="button" target="_blank"><img src="view/images/logo_pdf.png" width="60" height="60"></a></font></td>';
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
                    c.telefono_participes as telefono_suplente,
                    c.celular_participes as celular_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    b.telefono_participes,
                    b.celular_participes,
                    case when a.acepto_representante_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_representante_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_representante,
                    case when a.acepto_suplente_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_suplente_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_suplente";
	    
	    $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes, b1.telefono_participes, b1.celular_participes
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
	                
	                
	                
	                $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="120" height="100"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_participes.'</br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'</br><b>CORREO: </b>'.$res->correo_representante.'</br><b>TELÉFONO: </b>'.$res->telefono_participes.'</br><b>CELULAR: </b>'.$res->celular_participes.'</br><b>ACEPTO: </b>'.$res->acepto_representante.'</td>';
	                
	                $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_suplente" width="120" height="100"></td>';
	                $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_suplente.'</br><b>NOMBRE: </b>'.$res->apellido_suplente.' '.$res->nombre_suplente.'</br><b>CORREO: </b>'.$res->correo_suplente.'</br><b>TELÉFONO: </b>'.$res->telefono_suplente.'</br><b>CELULAR: </b>'.$res->celular_suplente.'</br><b>ACEPTO: </b>'.$res->acepto_suplente.'</td>';
	                
	                if($res->acepto_representante==-'Acepto' && $res->acepto_suplente=='Acepto'){
	                    
	                    $html.='<td><a title="PDF" href="index.php?controller=CargarParticipes&action=ReporteCandidatos&id_padron_electoral_representantes='.$res->id_padron_electoral_representantes.'" role="button" target="_blank"><img src="view/images/logo_pdf.png" width="60" height="60"></a></font></td>';
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

	
	
	
	///coienza lo de las elecciones
	
	public function DevolverCodigo(){
	    
	    session_start();
	    $registro = new RegistroModel();
	    
	    if(isset($_POST["cedula_participes"])){
	        
	        $_cedula_participes = $_POST["cedula_participes"];
	        
	        $columna = "padron_electroal.id_padron_electroal, core_participes.id_participes";
	        $tablas = " public.padron_electroal,
	                    public.core_participes";
	        $where= "padron_electroal.id_participes = core_participes.id_participes
	                   AND padron_electroal.tipo_padron_electoral = 1
	                   AND  core_participes.cedula_participes = $_cedula_participes   ";
	        $id = "padron_electroal.id_padron_electroal";
	        $resultado= $registro->getCondiciones($columnas, $tablas, $where, $id);
	        
	        if( $resultado > 0 ){
	            
	            //estoy en el padron
	            
	            
	            //echo json_encode(array('data'=>$resultado));
	            
	        }else{
	            
	            echo $resultado;
	        }
	        
	    }
	    
	    
	    
	    
	    
	}
	
	
	
	
	public function verifica_derecho_voto(){
	    
	    $callBack = $_GET['jsoncallback'];
	    $html ='';
	    
	    $i=0;
	    $registro = new RegistroModel();
	    $participes = new ParentescoModel();
	    $cedula_usuarios = $_GET["cedula"];
	    
	    if(isset($_GET["cedula"])){
	       
	        
	        $columnasV = "padron_electroal.id_padron_electroal, core_participes.id_participes,
                          core_participes.celular_participes,
                          core_participes.correo_participes";
	        $tablasV = " public.padron_electroal,
	                    public.core_participes";
	        $whereV= "padron_electroal.id_participes = core_participes.id_participes
	                   AND padron_electroal.voto_padron_electroal = 'true'
	                   AND  core_participes.cedula_participes = '$cedula_usuarios'   ";
	        $idV = "padron_electroal.id_padron_electroal";
	        $resultSetV= $registro->getCondiciones($columnasV, $tablasV, $whereV, $idV);
	        $iV=count($resultSetV);
	        
	        if($iV>0)
	        {
	            ///ya voto
                $html .= '<h1 style="color: blue; class="display-4">Estamos en Elecciones!</h1> ';
	            $html .= '<p style="color: blue;"  class="lead">ESTIMADO PARTICIPE USTED YA HA SUFRAGADO.</p>';
	            $html .= '<hr class="my-4">';
	            $html .= '<div class="form-group row col-md-6 col-sm-12 col-lg-6" >';
	            $html .= '<div class="col-md-12 col-sm-12 col-lg-12">';
	            $html .= '</div>';
	            $html .= '</div>';
	            $html .= '<div class="form-group row col-md-6 col-sm-12 col-lg-6">';
	            $html .= '<div class="col-md-12 col-sm-12 col-lg-12">';
	            $html .= '</div>';
	            $html .= '</div>';
	            $html .= '<div style="text-align: center;" class="form-group row col-md-12 col-sm-12 col-lg-12">';
	            $html .= '</div>';
	            $html .= '<br>';
	            $html .= ' <br>';
	            $html .= '<br>';
	            $html .= '<br>';
	            
	            
	        }
	        else 
	        {
	            ////revisemos si tiene pin
	            
	            
	            
	            $columnasP = "padron_electroal.tipo_padron_electoral,
	            padron_electroal.id_participes,
	            padron_electroal.voto_padron_electroal,
	            padron_electroal.celular_padron_electroal,
	            padron_electroal.correo_padron_electroal,
	            padron_electroal.pin_padron_electroal";
	            $tablasP = "public.padron_electroal,
	            public.core_participes";
	            $whereP = "padron_electroal.tipo_padron_electoral = 1 AND
	            padron_electroal.id_participes = core_participes.id_participes  AND
	            core_participes.cedula_participes = '$cedula_usuarios'";
	            $idP = "padron_electroal.id_padron_electroal";
	            
	            
	            //consultar si voto
	            $resultSetP= $participes->getCondiciones($columnasP, $tablasP, $whereP, $idP);
	            
	            
	            $iP=count($resultSetP);
	            
	            if($iP>0)
	            {
	            
	            
    	            foreach ($resultSetP as $res)
    	            {
    	            
    	               $_voto_padron_electroal =  $res->voto_padron_electroal;
    	               $_pin_padron_electroal  =   $res->pin_padron_electroal;
    	            
    	               if ($_pin_padron_electroal !="")  //tiene pin
    	               {
            	            //no ha votado
            	            $html .= '<h1 style="color: blue; class="display-4">Estamos en Elecciones!</h1> ';
            	            $html .= '<p style="color: blue;"  class="lead">ESTIMADO PARTICIPE INGRESE EL PIN ENVIADO A TELÉFONO Y AL CORREO ELECTRÓNICO.</p>';
            	            $html .= '<hr class="my-4">';
            	            $html .= '<p>Para validar su voto es necesario ingrese el PIN mediante SMS y email al teléfono y correo:</p>';
            	            $html .= '<div class="form-group row col-md-6 col-sm-12 col-lg-6" >';
            	            $html .= '<label for="staticEmail" class="col-md-12 col-sm-12 col-lg-12 col-form-label">PIN</label>';
            	            $html .= '<div class="col-md-12 col-sm-12 col-lg-12">';
            	            $html .= '<input type="text"  class="form-control" id="pin_padron_electoral" placeholder="Escriba aqui el pin recibido" >';
            	            $html .= '</div>';
            	            $html .= '</div>';
            	            $html .= '<div style="text-align: center;" class="form-group row col-md-12 col-sm-12 col-lg-12">';
            	            
            	            $html .= '<a class="btn btn-primary id="btn_validar_pin"  onclick="VerificaCodigo(this)"  role="button">Validar PIN</a>';
            	            
            	            $html .= '</div>';
            	            $html .= '<br>';
            	            $html .= ' <br>';
            	            $html .= '<br>';
            	            $html .= '<br>';
    	               }
    	               else   //no tiene pin
    	               {
    	                   $columnas = "padron_electroal.id_padron_electroal, core_participes.id_participes,
                                  core_participes.celular_participes,
                                  core_participes.correo_participes";
    	                   $tablas = " public.padron_electroal,
        	                    public.core_participes";
    	                   $where= "padron_electroal.id_participes = core_participes.id_participes
        	                   AND padron_electroal.tipo_padron_electoral = 1
        	                   AND  core_participes.cedula_participes = '$cedula_usuarios'   ";
    	                   $id = "padron_electroal.id_padron_electroal";
    	                   $resultSet= $registro->getCondiciones($columnas, $tablas, $where, $id);
    	                   
    	                   
    	                   $i=count($resultSet);
    	                   
    	                   if($i>0)
    	                   {
    	                       
    	                       
    	                       foreach ($resultSet as $res)
    	                       {
    	                           $i++;
    	                           $html .= '<h1 style="color: blue; class="display-4">Estamos en Elecciones!</h1> ';
    	                           $html .= '<p style="color: blue;"  class="lead">ESTIMADO PARTICIPE USTED TIENE DERECHO AL VOTO.</p>';
    	                           $html .= '<hr class="my-4">';
    	                           $html .= '<p>Para validar su voto le enviaremos un PIN mediante SMS y email al teléfono y correo que aparecen a continuación:</p>';
    	                           $html .= '<div class="form-group row col-md-6 col-sm-12 col-lg-6" >';
    	                           $html .= '<label for="staticEmail" class="col-md-12 col-sm-12 col-lg-12 col-form-label">Correo Electrónico</label>';
    	                           $html .= '<div class="col-md-12 col-sm-12 col-lg-12">';
    	                           $html .= '<input type="text"  class="form-control" id="correo_participes" value=" '.$res->correo_participes.' ">';
    	                           $html .= '</div>';
    	                           $html .= '</div>';
    	                           $html .= '<div class="form-group row col-md-6 col-sm-12 col-lg-6">';
    	                           $html .= '<label for="inputPassword" class="col-md-12 col-sm-12 col-lg-12 col-form-label">Teléfono Celular</label>';
    	                           $html .= '<div class="col-md-12 col-sm-12 col-lg-12">';
    	                           $html .= '<input type="text" class="form-control" id="celular_participes" value="'.$res->celular_participes.'">';
    	                           $html .= '</div>';
    	                           $html .= '</div>';
    	                           $html .= '<div style="text-align: center;" class="form-group row col-md-12 col-sm-12 col-lg-12">';
    	                           
    	                           $html .= '<a class="btn btn-primary id="btn_generar_codigo"  onclick="RegistraEnviaCodigo(this)"  role="button">Guardar y Solicitar PIN</a>';
    	                           
    	                           $html .= '</div>';
    	                           $html .= '<br>';
    	                           $html .= ' <br>';
    	                           $html .= '<br>';
    	                           $html .= '<br>';
    	                           
    	                           
    	                           
    	                           
    	                       }
    	                       
    	                       
    	                   }else{
    	                       
    	                       //NO TIENE DERECHO AL VOTO
    	                       
    	                       
    	                       $html .= '<h1 style="color: blue; class="display-4">Estamos en Elecciones!</h1> ';
    	                       $html .= '<p style="color: blue;"  class="lead">ESTIMADO PARTICIPE USTED NO TIENE DERECHO AL VOTO.</p>';
    	                       $html .= '<hr class="my-4">';
    	                       $html .= '<div class="form-group row col-md-6 col-sm-12 col-lg-6" >';
    	                       $html .= '<div class="col-md-12 col-sm-12 col-lg-12">';
    	                       $html .= '</div>';
    	                       $html .= '</div>';
    	                       $html .= '<div class="form-group row col-md-6 col-sm-12 col-lg-6">';
    	                       $html .= '<div class="col-md-12 col-sm-12 col-lg-12">';
    	                       $html .= '</div>';
    	                       $html .= '</div>';
    	                       $html .= '<div style="text-align: center;" class="form-group row col-md-12 col-sm-12 col-lg-12">';
    	                       $html .= '</div>';
    	                       $html .= '<br>';
    	                       $html .= ' <br>';
    	                       $html .= '<br>';
    	                       $html .= '<br>';
    	                       
    	                       
    	                       
    	                   }
    	                   
    	                   
    	               }
    	               
    	               
    	            }
            
	        }
	        
	        }
	    }
	        
	        $respuesta	= json_encode( array('mensaje_modal' => $html) );
	        echo $callBack."(".$respuesta.");";
	        die();
	        
	    
	    
	}
	
	
	
	public function verificaCodigo(){
	    
	    $callBack = $_POST['jsoncallback'];
	    $html ='';
	    
	    
	   
	    
	    $participes = new ParentescoModel();
	    $cedula_usuarios = $_POST["cedula_participes"];
	    $pin_ingresado = $_POST["pin_ingresado"];
	    
	    if(isset($_POST["cedula_participes"])){
	        
	       
	        
	        
	        
	        $columnasP = "padron_electroal.tipo_padron_electoral,
	            padron_electroal.id_participes,
	            padron_electroal.voto_padron_electroal,
	            padron_electroal.celular_padron_electroal,
	            padron_electroal.correo_padron_electroal,
	            padron_electroal.pin_padron_electroal";
	        $tablasP = "public.padron_electroal,
	            public.core_participes";
	        $whereP = "padron_electroal.tipo_padron_electoral = 1 AND
	            padron_electroal.id_participes = core_participes.id_participes  AND
	            core_participes.cedula_participes = '$cedula_usuarios'";
	        $idP = "padron_electroal.id_padron_electroal";
	        
	        
	        //consultar si voto
	        $resultSetP= $participes->getCondiciones($columnasP, $tablasP, $whereP, $idP);
	        
	        $iP=count($resultSetP);
	        
	        
	        if($iP>0)
	        {
	            
	            
	            
	            foreach ($resultSetP as $res)
	            {
	                
	                
	                $_pin_padron_electroal  =   $res->pin_padron_electroal;
	                
	                if ($pin_ingresado == $_pin_padron_electroal)
	                {
	                   //ok    
	                    
	                    $html ='SICORRECTO';
	                    
	                    $columna = "verificado_pin_padron_electroal='true' ";
	                    $tablas = "padron_electroal";
	                    $where= "tipo_padron_electoral = 1 AND
                    	id_participes =
                    	(SELECT id_participes FROM core_participes WHERE
                    	cedula_participes = '$cedula_usuarios')";
	                    
	                    
	                    try
	                    {
	                       $participes -> ActualizarBy($columna, $tablas, $where);
	                        
	                        
	                        
	                    }
	                    catch (Exception $e)
	                    {
	                        $res =  "Captured Error: " . $e->getMessage();
	                    }
	                    
	                }
	                else 
	                {
	                    
	                    //mal
	                    
	                    $html ='NOCORRECTO';
	                    
	                }
	                
	                
	                
	            }
	            
	        }
	    }
	    
	    $respuesta	= json_encode( array('mensaje_modal' => $html) );
	    echo $callBack."(".$respuesta.");";
	    die();
	    
	    
	    
	}
	
	
	
	
	
	public function RegistraEnviaCodigo()
	{
	    
	    $callBack = $_POST['jsoncallback'];
	    
	    $_cedula_participes =(isset($_POST['cedula_participes'])) ? $_POST['cedula_participes'] : 0;
	    $_celular_participes =(isset($_POST['celular_participes'])) ? $_POST['celular_participes'] : 0;
	    $_correo_participes =(isset($_POST['correo_participes'])) ? $_POST['correo_participes'] : 0;
	    
	    
	    $_id_participes;
	    $_voto_padron_electroal;
	    $_celular_padron_electroal;
	    $_correo_padron_electroal;
	    $_pin_padron_electroal;
	    
	    
	    $participes= new ParticipesModel();
	    $respuesta= array();
	    
	    
	    $res = "Sin Ejecutar";
	    
	    
	    
	    if($_cedula_participes != ""  )
	    {
	        $res = "Ejecutando";
	        $html = '';
	        $_pin_padron_electroal =  mt_rand(1000,9999);
	        
	        
	        $columna = "celular_padron_electroal='$_celular_participes', 
	                   correo_padron_electroal='$_correo_participes', 
	                   pin_padron_electroal='$_pin_padron_electroal'";
	        $tablas = "padron_electroal";
	        $where= "tipo_padron_electoral = 1 AND
                    	id_participes =
                    	(SELECT id_participes FROM core_participes WHERE
                    	cedula_participes = '$_cedula_participes')";
	        
	        
	        
	        
	        
	        
	        
	        try
	        {
	            $res= $participes -> ActualizarBy($columna, $tablas, $where);
	            
	            $res = $this->EnviarSMS($_cedula_participes, '0987968467', $_pin_padron_electroal);
	            
	             $res = $this->enviar_email('manuel@masoft.net', $_cedula_participes, $_pin_padron_electroal);
	            
	            ///todo enviado
	           
	                     
	            
	        }
	        catch (Exception $e)
	        {
	            $res =  "Captured Error: " . $e->getMessage();
	        }
	        
	    }
	    
	    
	    $respuesta	= json_encode( array('mensaje_modal' => $html)) ;
	    echo $callBack."(".$respuesta.");";
	    die();
	    
	    
	    
	}
	
	
	
	
	
	public function EnviarSMS($cedula, $celular, $codigo){
	    
	    session_start();
	    //$solicitud_prestamo = new SolicitudPrestamoModel();
	    
	    $participes = new ParticipesModel();
	    $resultado=2;
	    $cadena_recortada="";
	    //$codigo_verificacion = new CodigoVerificacionModel();
	    $mensaje_retorna="";
	    
	    if(!isset($_SESSION['id_usuarios'])){
	        echo 'Session Caducada';
	        exit();
	    }
	    
	    
	    
	    
	    if(!empty($celular)){
	        
	        
	        
	        $resulset=$participes->getBy("cedula_participes='$cedula'");
	        
	        if(!empty($resulset)){
	            
	            
	            $nombre_usuarios = $resulset[0]->nombre_participes .' ' .$resulset[0]->apellido_participes;
	            
	            
	            if(!empty($nombre_usuarios)){
	                
	                
	                
	                $cadena_recortada=$this->comsumir_mensaje_plus($celular, $nombre_usuarios, $codigo);
	                
	                
	                
	                if($cadena_recortada=='100'){
	                    
	                    
	                    $mensaje_retorna="Enviado Correctamente";
	                    
	                }else if ($cadena_recortada=='101'){
	                    
	                    
	                    $mensaje_retorna="Despacho en Cola";
	                    
	                }else if ($cadena_recortada=='200'){
	                    
	                    $mensaje_retorna="Estructura no Válida";
	                    
	                }else if ($cadena_recortada=='201'){
	                    
	                    $mensaje_retorna="Método no Existe";
	                    
	                }else if ($cadena_recortada=='202'){
	                    
	                    $mensaje_retorna="Parámetros Incompletos";
	                    
	                }else if ($cadena_recortada=='302'){
	                    
	                    $mensaje_retorna="Cliente no Existe";
	                    
	                }else if ($cadena_recortada=='303'){
	                    
	                    $mensaje_retorna="Mensaje muy Grande";
	                    
	                }else if ($cadena_recortada=='307'){
	                    
	                    $mensaje_retorna="Cliente no tiene Servicio Online";
	                    
	                }else if ($cadena_recortada=='309'){
	                    
	                    $mensaje_retorna="Token Inválido";
	                    
	                }else if ($cadena_recortada=='310'){
	                    
	                    $mensaje_retorna="Shortcode no disponible para el Cliente";
	                    
	                }else if ($cadena_recortada=='311'){
	                    
	                    $mensaje_retorna="Acceso Remoto no Permitido";
	                    
	                }else if ($cadena_recortada=='312'){
	                    
	                    $mensaje_retorna="Teléfono Destino en Lista Negra";
	                    
	                }else if ($cadena_recortada=='313'){
	                    
	                    $mensaje_retorna="Mensaje no Asignado";
	                    
	                }else if ($cadena_recortada=='314'){
	                    
	                    $mensaje_retorna="Data Variable no coincide con parámetro enviados";
	                    
	                }else if ($cadena_recortada=='315'){
	                    
	                    $mensaje_retorna="Teléfono Incorrecto";
	                    
	                }else if ($cadena_recortada=='400'){
	                    
	                    $mensaje_retorna="No se pudo procesar";
	                    
	                }else{
	                    
	                    $mensaje_retorna="Error Desconocido Vuelva a Intentarlo.";
	                }
	                
	                
	                
	                if((int)$resultado > 0){
	                    
	                    echo json_encode(array('valor' => $resultado, 'mensaje'=>$mensaje_retorna));
	                   // die();
	                    
	                }
	                
	                
	            }
	            
	            
	        }
	        
	        
	    }
	    
	    $pgError = pg_last_error();
	    
	    echo "no se envio sms. ".$pgError;
	    
	}
	
	
	
	
	
	
	
	public function comsumir_mensaje_plus($celular, $nombres, $codigo){
	    
	    
	    
	    $respuesta="";
	    $nombres_final="";
	    
	    // quito el primero 0
	    $celular_final=ltrim($celular, "0");
	    
	    // relleno espacios en blanco por _
	    $nombres_final= str_replace(' ','_',$nombres);
	    // $nombres_final= str_replace('Ñ','N',$nombres);
	    // genero codigo de verificacion
	    
	    
	    $variables="";
	    $variables.="<pedido>";
	    
	    $variables.="<metodo>SMSEnvio</metodo>";
	    $variables.="<id_cbm>767</id_cbm>";
	    $variables.="<token>yPoJWsNjcThx2o0I</token>";
	    $variables.="<id_transaccion>2002</id_transaccion>";
	    $variables.="<telefono>$celular_final</telefono>";
	    
	    // poner el id_mensaje parametrizado en el sistema
	    
	    $variables.="<id_mensaje>22442</id_mensaje>";
	    
	    // poner 1 si va con variables
	    // poner 0 si va sin variables y sin la etiquetas datos
	    $variables.="<dt_variable>1</dt_variable>";
	    $variables.="<datos>";
	    
	    
	    /// el numero de valores va dependiendo del mensaje si usa 1 o 2 variables.
	    $variables.="<valor>$nombres_final</valor>";
	    $variables.="<valor>$codigo</valor>";
	    $variables.="</datos>";
	    $variables.="</pedido>";
	    
	    
	    $SMSPlusUrl = "https://smsplus.net.ec/smsplus/ws/mensajeria.php?xml={$variables}";
	    $ResponseData = file_get_contents($SMSPlusUrl);
	    
	    
	    $xml = simplexml_load_string($ResponseData);
	    
	    //convert into json
	    $json  = json_encode($xml);
	    
	    //convert into associative array
	    $xmlArr = json_decode($json, true);
	    
	    $respuesta= $xmlArr['cod_respuesta'];
	    
	    return $respuesta;
	    
	    
	    
	}
	
	
	
	public function enviar_email($correo, $cedula, $codigo){
	
	    require 'clases/email/class.phpmailer.php';
	    $participes = new ParticipesModel();
	    
	    $header= "Content-Type: multipart/mixed; boundary=\"=A=G=R=O=\"\r\n\r\n";
	    
	    $cabeceras = "MIME-Version: 1.0 \r\n";
	    $cabeceras .= "Content-type: text/html; charset=utf-8 \r\n";
	    $cabeceras .= "From: info@masoft.net \r\n";
	    
	    
	    
	    $resulset=$participes->getBy("cedula_participes='$cedula' ");
	    
	    
	    
	    
	    if(!empty($resulset)){
	       
	        
	           
	        
	        
	        $nombre_usuarios = $resulset[0]->nombre_participes .' ' .$resulset[0]->apellido_participes;
	       
	        
	        
	        if(!empty($nombre_usuarios)){
	            
	    
            	    $cuerpo="
            	        
                			<table rules='all'>
                			<tr><td WIDTH='800' HEIGHT='50'><center><img src='http://186.4.157.125:80/webcapremci/view/images/bcaprem.png' WIDTH='300' HEIGHT='120'/></center></td></tr>
                			</tabla>
                			<p><table rules='all'></p>
                			<tr style='background: #FFFFFF;'><td  WIDTH='1000' align='center'><b> CAPREMCI - JUNTA GENERAL ELECTORAL </b></td></tr></p>
                			<tr style='background: #FFFFFF;'><td  WIDTH='1000' align='justify'>Estimado/a <b>$nombre_usuarios </b> . A Continuación Encontrarás el Código de Verificación para Ejercer su Derecho  al Voto:</td></tr>
            	        
                			<tr style=' text-align: center; font-size: 24px; background: #FFFFFF;'><td  WIDTH='1000' ><b>  $codigo  </b></td></tr>
            	        
                			</tabla>
                			<p><table rules='all'></p>
                		    <tr style='background: #FFFFFF;'>
                			</tabla>
                			<p><table rules='all'></p>
                			<tr style='background:#1C1C1C'><td WIDTH='1000' HEIGHT='50' align='center'><font color='white'>Capremci - <a href='http://www.capremci.com.ec'><FONT COLOR='#7acb5a'>www.capremci.com.ec</FONT></a> - Copyright © 2020-</font></td></tr>
                			</table>
                			";
            	   	$mail = new PHPMailer;
                	$mail->IsSMTP();								//Sets Mailer to send message using SMTP
                	$mail->Host = 'mail.capremci.com.ec';		//Sets the SMTP hosts of your Email hosting, this for Godaddy
                	$mail->Port = '587';								//Sets the default SMTP server port
                	$mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
                	$mail->Username = 'info@capremci.com.ec';					//Sets SMTP username
                	$mail->Password = '@Soporte2020';					//Sets SMTP password
                	$mail->SMTPSecure = '';
                	$mail->CharSet = 'UTF-8';
                	$mail->FromName = mb_convert_encoding($header, "UTF-8", "auto");//Sets connection prefix. Options are "", "ssl" or "tls"
                	$mail->From = 'info@capremci.com.ec';			//Sets the From email address for the message
                	$mail->FromName = 'CAPREMCI Junta General Electoral';			//Sets the From name of the message
                	$mail->AddAddress( $correo,'');		//Adds a "To" address
                //	$mail->AddAddress( 'documentoselectronicos@capremci.com.ec','');		//Adds a "To" address
                	
                	$mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
                	$mail->IsHTML(true);							//Sets message type to HTML
                //	$mail->AddAttachment($camino_nombre_xml);     				//Adds an attachment from a path on the filesystem
                //	$mail->AddAttachment($_nombre_archivo);     				//Adds an attachment from a path on the filesystem
                	
                	$mail->Subject = 'CAPREMCI: Código de Verificación para Votar ';			//Sets the Subject of the message
                	$mail->Body = $cuerpo;				//An HTML or plain text message body
                	
                	
                	if($mail->Send())								//Send an Email. Return true on success or false on error
                	{
                	   
                	    return  "Aqui en Email: " . $nombre_usuarios  ;
                	    
                	    
                	}
                	else 
                	{
                	    $error = $mail->Send()->__toString();
                	    return  "Error al enviar Email: " . $error  ;
                	}
                	
                	
              }
                	
	    }
    }
    
    
    
    
    
    public function PapeletaCandidatos(){
        
        
        session_start();
        //$solicitud_prestamo = new SolicitudPrestamoModel();
        
        
        if(!isset($_SESSION['ele_id_participes'])){
            echo 'Session Caducada';
            exit();
        }
        
        
        $_id_entidad_mayor_patronal = $_SESSION["ele_id_entidad_mayor_patronal"];
        
        /*
        $_SESSION["ele_id_participes"] =  $res->id_participes;
        $_SESSION["ele_cedula_participes"] =  $res->cedula_participes;
        $_SESSION["ele_id_entidad_patronal"] =  $res->id_entidad_patronal;
        $_SESSION["ele_id_entidad_mayor_patronal"] =  $res->id_entidad_mayor_patronal;
        */
        
        
        $registro = new RegistroModel();
        
        $where_to="";
        
        $columnas= "padron_electoral_representantes.id_padron_electoral_representantes,
                    padron_electoral_representantes.foto_representante,
                    core_participes.cedula_participes,
                    core_participes.id_entidad_mayor_patronal,
                    core_participes.apellido_participes,
                    core_participes.nombre_participes,
                    core_entidad_patronal.nombre_entidad_patronal,
                    core_entidad_mayor_patronal.nombre_entidad_mayor_patronal,
                    core_provincias.nombre_provincias,
                    core_ciudades.nombre_ciudades";
        
        $tablas =  "core_participes,
                    core_entidad_patronal,
                    core_entidad_mayor_patronal,
                    core_provincias,
                    core_ciudades,
                    core_participes_informacion_adicional,
                    padron_electoral_representantes";
        
        $where = "core_participes.id_entidad_patronal = core_entidad_patronal.id_entidad_patronal AND
                    core_participes.id_participes = core_participes_informacion_adicional.id_participes AND
                    core_participes_informacion_adicional.id_provincias = core_provincias.id_provincias AND
                    core_participes_informacion_adicional.id_ciudades = core_ciudades.id_ciudades AND
                    core_participes.id_participes = padron_electoral_representantes.id_representante AND
                    core_participes.id_entidad_mayor_patronal = core_entidad_mayor_patronal.id_entidad_mayor_patronal AND
                    estado_candidato_padron_electoral_representantes = 0 AND
                    core_entidad_mayor_patronal.id_entidad_mayor_patronal = '$_id_entidad_mayor_patronal'  ";
        
        $id = "core_participes.apellido_participes ";
        
        
        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND core_participes.apellido_participes ILIKE '%".$search."%'   OR core_participes.nombre_participes ILIKE '%".$search."%'        ";
                
                $where_to=$where.$where1;
                
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$registro->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 15; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$registro->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            if($cantidadResult > 0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Candidatos Habilitados: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:400px; overflow-y:scroll;">';
                $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;">Acciones</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Ordinal</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Foto Candidato</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Candidato Pincipal</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Entidad Patronal</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Entidad Mayor Patronal</th>';
                
                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    
                    
                    
                    $i++;
                    $html.='<tr>';
                    
                    
                    $html.='<td style="font-size: 14px;"><a onclick="ConfirmarVoto('.$res->id_padron_electoral_representantes.')"
                                href="JavaScript:void(0);" class="btn btn-success" style="font-size:65%;"data-toggle="tooltip"
                                title="Aprobar"><i class="glyphicon glyphicon-ok"> VOTAR</i></a></td>';
                    
                    $html.='<td style="font-size: 12px;"><b>'.$i.'</b></td>';
                    $html.='<td style="font-size: 11px; width:15px;">
                                <img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante"
                              width="80" height="60"></td>';
                    $html.='<td style="font-size: 12px;">'.$res->apellido_participes.' '.$res->nombre_participes.'</td>';
                    
                    $html.='<td style="font-size: 12px;">'.$res->nombre_entidad_patronal .'</br></td>';
                    $html.='<td style="font-size: 12px;">'.$res->nombre_entidad_mayor_patronal .'</td>';
                    
                    
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
    
    
    
    
    
    
    public function ConfirmarVoto(){
        
        
        session_start();
        //$solicitud_prestamo = new SolicitudPrestamoModel();
        
        
        if(!isset($_SESSION['ele_id_participes'])){
            echo 'Session Caducada';
            exit();
        }
        
        
        
        
        $participes = new ParticipesModel();
        $html="";
        $resultado = "";
        
        if(isset($_POST["id_padron_electoral_representantes"])){
            
            $id_padron_electoral_representantes = (int)$_POST["id_padron_electoral_representantes"];
            
            
            $columnas= "padron_electoral_representantes.id_padron_electoral_representantes,
                    padron_electoral_representantes.foto_representante,
                    core_participes.cedula_participes,
                    core_participes.id_entidad_mayor_patronal,
                    core_participes.apellido_participes,
                    core_participes.nombre_participes,
                    core_entidad_patronal.nombre_entidad_patronal,
                    core_entidad_mayor_patronal.nombre_entidad_mayor_patronal,
                    core_provincias.nombre_provincias,
                    core_ciudades.nombre_ciudades";
            
            $tablas =  "core_participes,
                    core_entidad_patronal,
                    core_entidad_mayor_patronal,
                    core_provincias,
                    core_ciudades,
                    core_participes_informacion_adicional,
                    padron_electoral_representantes";
            
            $where = "core_participes.id_entidad_patronal = core_entidad_patronal.id_entidad_patronal AND
                    core_participes.id_participes = core_participes_informacion_adicional.id_participes AND
                    core_participes_informacion_adicional.id_provincias = core_provincias.id_provincias AND
                    core_participes_informacion_adicional.id_ciudades = core_ciudades.id_ciudades AND
                    core_participes.id_participes = padron_electoral_representantes.id_representante AND
                    core_participes.id_entidad_mayor_patronal = core_entidad_mayor_patronal.id_entidad_mayor_patronal AND
                    estado_candidato_padron_electoral_representantes = 0 AND
                    padron_electoral_representantes.id_padron_electoral_representantes = '$id_padron_electoral_representantes'  ";
            
            $id = "core_participes.apellido_participes ";
            
            
            
            $resultSet=$participes->getCondiciones($columnas, $tablas, $where, $id);
            
            
            if(!empty($resultSet)){
                
                
                foreach ($resultSet as $res)
                {
                    
                    $html.='<div class="modal" id="modal_confirmacion_voto" tabindex="-1" role="dialog"> ';
                    $html.='<div class="modal-dialog" role="document">';
                    $html.='<div class="modal-content">';
                    $html.='<div class="modal-header">';
                    $html.='<h4 class="modal-title">CONFIRMAR VOTO</h4>';
                    $html.='<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    $html.='<span aria-hidden="true">&times;</span>';
                    $html.='</button>';
                    $html.='</div>';
                    $html.='<div class="modal-body">';
                    $html.='<p>Confirme que su voto es para:</p>';
                    $html.='<div style="text-align: center;">';
                    $html.='<br>';
                    $html.='<img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante"
                    width="160" height="150">';
                    $html.='<br>';
                    $html.='<h4>'.$res->apellido_participes.' '.$res->nombre_participes.'</h4>';
                    $html.='</div>';
                    
                    
                    $html.='</div>';
                    $html.='<div class="modal-footer">';
                    $html.='<a onclick="Votar('.$res->id_padron_electoral_representantes.')"
                                href="JavaScript:void(0);" type="button" class="btn btn-success">CONFIRMAR VOTO</a>';
                    $html.='<button type="button" class="btn btn-danger" data-dismiss="modal">CANVELAR</button>';
                    $html.='</div>';
                    $html.='</div>';
                    $html.='</div>';
                    $html.='</div>';
                    
                    
                    
                    
                }
                
                /*
                
                $html.='<td style="font-size: 14px;"><a onclick="ConfirmarVoto('.$res->id_padron_electoral_representantes.')"
                                href="JavaScript:void(0);" class="btn btn-success" style="font-size:65%;"data-toggle="tooltip"
                                title="Aprobar"><i class="glyphicon glyphicon-ok"> VOTAR</i></a></td>';
                
                $html.='<td style="font-size: 12px;"><b>'.$i.'</b></td>';
                $html.='<td style="font-size: 11px; width:15px;">
                                <img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante"
                              width="80" height="60"></td>';
                $html.='<td style="font-size: 12px;">'.$res->apellido_participes.' '.$res->nombre_participes.'</td>';
                
                $html.='<td style="font-size: 12px;">'.$res->nombre_entidad_patronal .'</br></td>';
                $html.='<td style="font-size: 12px;">'.$res->nombre_entidad_mayor_patronal .'</td>';
                
                
                $html.='</tr>';
                
                
                */
                
                
                
                
                
                
                
                echo $html;
                
            }else{
                
                echo $resultado;
            }
            
            
            
        }
        
        
        
        
        
    }
    
    
    
    
    
    public function Votar(){
        
        
        session_start();
        //$solicitud_prestamo = new SolicitudPrestamoModel();
        
        
        if(!isset($_SESSION['ele_id_participes'])){
            echo 'Session Caducada';
            exit();
        }
        $participes = new ParticipesModel();
        $html="";
        $resultado = "";
        
        if(isset($_POST["id_padron_electoral_representantes"])){
            
                $_id_padron_electoral_representantes = (int)$_POST["id_padron_electoral_representantes"];
                
                $_id_participes_vota = $_SESSION["ele_id_participes"] ;
                $_ip_padron_electoral_traza_votos    =  $_SESSION["ele_ip_usuarios"] ;
                
                
                ///inserto voto
                
                $funcion = "ins_padron_electoral_traza_votos";
                $parametros = "'$_id_padron_electoral_representantes',
        		    				   '$_id_participes_vota',
                                       '$_ip_padron_electoral_traza_votos'";
                $participes->setFuncion($funcion);
                $participes->setParametros($parametros);
                
                
                //actualizo estado voto
                
                $columna = "voto_padron_electroal='true' ";
                $tablas = "padron_electroal";
                $where= "tipo_padron_electoral = 1 AND
                        	id_participes = '$_id_participes_vota' ";
                
                ///contabilizo voto
                
                $columnaR = "voto_padron_electoral_representantes = voto_padron_electoral_representantes + 1 ";
                $tablasR = "padron_electoral_representantes";
                $whereR = "id_padron_electoral_representantes = '$_id_padron_electoral_representantes'";
                
                
                
                try {
                    
                    if ( $this->VerificaSiVoto($_id_participes_vota))
                    {
                        
                        echo 'ya voto';
                    }
                    else
                    {
                       
                        $resultado=$participes->Insert();
                        
                        
                        $res= $participes -> ActualizarBy($columnaR, $tablasR, $whereR);
                        
                        $res= $participes -> ActualizarBy($columna, $tablas, $where);
                        
                    }
                    
                    
                    
                        
                        
                    
                } catch (Exception $e) 
                {
                    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                }
                
                
                    
                 echo $html;
        
             
        }else{
            
            echo $resultado;
    
            
        }
        
        
        
        
        
    }
    
    
    
    public function VerificaSiVoto($_id_participes_vota){
        
        
        
        $resultado = false;
        
        if(!isset($_SESSION['ele_id_participes'])){
            echo 'Session Caducada';
            exit();
        }
        $participes = new ParticipesModel();
        $html="";
        
            
            
            
            
            //actualizo estado voto
            $columna = "voto_padron_electroal='true' ";
            $tablas = "padron_electroal";
            $where= "tipo_padron_electoral = 1 AND
                        	id_participes = '$_id_participes_vota' ";
            
            ///contabilizo voto
            
            $columna = "voto_padron_electroal ";
            $tablas = "padron_electroal";
            $where = "voto_padron_electroal = true AND tipo_padron_electoral = 1 AND id_participes = '$_id_participes_vota'";
            $id = "voto_padron_electroal ";
            
            
            try {
            
                $resultSet=$participes->getCondiciones($columna, $tablas, $where, $id);
                
                
                if(!empty($resultSet)){
                    
                    
                    foreach ($resultSet as $res)
                    {
                        
                        $resultado = true;       
                    }
                }
            
                
                
            } catch (Exception $e)
            {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            }
            
            
          
            return $resultado;
        
            
            
    }
        
        
    
    
    
    
    public function ReporteCertificadoVotacion(){
        
        session_start();
        
        $id_participes =1922 ; // $_SESSION['ele_id_participes'];
        $participes = new ParticipesModel();
        //$id_participes =  $_id_participes;//(isset($_REQUEST['id_participes'])&& $_REQUEST['id_participes'] !=NULL)?$_REQUEST['id_participes']:'';
        
        $datos_reporte = array();
        $columnas = "lpad(d.id_padron_electoral_traza_votos::text ,5,'0') as consecutivo,
                     a.cedula_participes,
                     a.apellido_participes,
                     a.nombre_participes,
                     b.nombre_entidad_patronal,
                     c.nombre_entidad_mayor_patronal,
                     a.id_genero_participes";
        $tablas =  "core_participes a
                    inner join core_entidad_patronal b on b.id_entidad_patronal = a.id_entidad_patronal
                    inner join core_entidad_mayor_patronal c on c.id_entidad_mayor_patronal = a.id_entidad_mayor_patronal
                    inner join padron_electoral_traza_votos d on d.id_participe_vota = a.id_participes ";
        $where= "a.id_participes = '$id_participes'";
        $id="a.id_participes";
        $rsdatos = $participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $genero = "";
        
        if($rsdatos[0]->id_genero_participes == 1){
            
            $genero = "ESTIMADO";
            
        }else if ($rsdatos[0]->id_genero_participes == 2){
            
            $genero = "ESTIMADA";
        }
        
        $datos_reporte['NOMBRE_PARTICIPES']=$rsdatos[0]->nombre_participes;
        $datos_reporte['APELLIDO_PARTICIPES']=$rsdatos[0]->apellido_participes;
        $datos_reporte['CEDULA_PARTICIPES']=$rsdatos[0]->cedula_participes;
        $datos_reporte['ENTIDAD_PATRONAL']=$rsdatos[0]->nombre_entidad_patronal;
        $datos_reporte['ENTIDAD_MAYOR_PATRONAL']=$rsdatos[0]->nombre_entidad_mayor_patronal;
        $datos_reporte['GENERO']=$genero;
        $datos_reporte['CONSECUTIVO']=$rsdatos[0]->consecutivo;
        
        
        $cedula_capremci = $rsdatos[0]->cedula_participes;
        $consecutivo = $rsdatos[0]->consecutivo;
        
        $tipo_documento="CERTIFICADO VALIDO DE VOTACIÓN NUMERO: ";
        
        $datos = "";
        require dirname(__FILE__)."\phpqrcode\qrlib.php";
        
        $ubicacion = dirname(__FILE__).'\..\barcode_participes\\';
        
        //Si no existe la carpeta la creamos
        if (!file_exists($ubicacion))
            mkdir($ubicacion);
            
            $filename = $ubicacion.$cedula_capremci.'.png';
            
            
            
            //Parametros de Condiguracion
            
            $tamaño = 2.5; //Tama�o de Pixel
            $level = 'L'; //Precisi�n Baja
            $framSize = 3; //Tama�o en blanco
            $contenido = $tipo_documento.''.$consecutivo; //Texto
            
            //Enviamos los parametros a la Funci�n para generar c�digo QR
            QRcode::png($contenido, $filename, $level, $tamaño, $framSize);
            
            $qr_participes = '<img src="'.$filename.'">';
            
            
            
            $datos['CODIGO_QR']=  $qr_participes;
            
            
            
            
            $this->verReporte("ReporteCertificadoVotacion", array('datos_reporte'=>$datos_reporte, 'datos'=>$datos ));
            
            
            
    }
    
    
    
    
    
    
    
    
    
    
    
    }
    
    ?>