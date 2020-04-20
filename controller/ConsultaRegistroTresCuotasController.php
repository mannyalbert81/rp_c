	<?php

    class ConsultaRegistroTresCuotasController extends ControladorBase{
	public function __construct() {
		parent::__construct();
		
	}
	
	public function index(){
	    
	    session_start();
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        $controladores = new RegistroModel();
	        $nombre_controladores = "ConsultaRegistroTresCuotas";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $controladores->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (!empty($resultPer))
	        {
	            
	            
	            
	            $this->view_Credito("ConsultaRegistroTresCuotas",array(
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
	
	
	
		

	

	
	public function ConsultaRegistroTresCuotas(){
	    
	    session_start();
	    $registro = new RegistroModel();
	    
	    $where_to="";
	    $columnas  = "registro_tres_cuotas.id_registro_tres_cuotas, 
                      core_participes.id_participes, 
                      core_participes.apellido_participes, 
                      core_participes.nombre_participes, 
                      core_participes.cedula_participes, 
                      core_creditos.id_creditos, 
                      core_creditos.numero_creditos, 
                      core_creditos.monto_otorgado_creditos, 
                      core_creditos.saldo_actual_creditos, 
                      core_creditos.fecha_concesion_creditos, 
                      core_creditos.plazo_creditos, 
                      core_tipo_creditos.id_tipo_creditos, 
                      core_tipo_creditos.nombre_tipo_creditos, 
                      registro_tres_cuotas.pdf_registro_tres_cuotas, 
                      estado_registro_tres_cuotas.id_estado_registro_tres_cuotas, 
                      estado_registro_tres_cuotas.nombre_estado_registro_tres_cuotas, 
                      TO_CHAR(registro_tres_cuotas.creado,'YYYY-MM-DD HH:MI:SS') as \"creado\",
	                  registro_tres_cuotas.modificado,
	                  (
	                    select numero_pago_tabla_amortizacion
						from core_tabla_amortizacion 
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as numero_pago_tabla_amortizacion,
	                  (
	                    select  fecha_tabla_amortizacion
						from core_tabla_amortizacion 
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as fecha_tabla_amortizacion,
	                  (
	                    select  capital_tabla_amortizacion
						from core_tabla_amortizacion 
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as capital_tabla_amortizacion,
	                  (
	                    select  total_valor_tabla_amortizacion
						from core_tabla_amortizacion 
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as total_valor_tabla_amortizacion,
	                  (
	                    select  balance_tabla_amortizacion
						from core_tabla_amortizacion 
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as balance_tabla_amortizacion";
	    
	    $tablas =  "public.registro_tres_cuotas, 
                    public.core_participes, 
                    public.core_creditos, 
                    public.core_tipo_creditos, 
                    public.estado_registro_tres_cuotas";
	    
	    $where = "registro_tres_cuotas.id_participes = core_participes.id_participes AND
                  core_creditos.id_creditos = registro_tres_cuotas.id_creditos AND
                  core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos AND
                  estado_registro_tres_cuotas.id_estado_registro_tres_cuotas = registro_tres_cuotas.id_estado_registro_tres_cuotas AND
                  estado_registro_tres_cuotas.id_estado_registro_tres_cuotas = 1";
	    
	    $id = "registro_tres_cuotas.id_registro_tres_cuotas";
	    
	    
	   
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND core_participes.cedula_participes ILIKE '".$search."%'";
	            
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
	            $html.= "<table id='tabla_bancos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	            $html.='<th style="text-align: center;  font-size: 12px;">Acciones</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cédula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Participe</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Número Crédito</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo de Crédito</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha de Conseción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Monto</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Pago</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ultima Cuota Pagada</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cuota</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Saldo Capital</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Solicitud</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Amortización</th>';
	            
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                
	                if($res->id_estado_registro_tres_cuotas == "1"){
	                    
	                    $estado ="PENDIENTE";
	                    
	                }elseif($res->id_estado_registro_tres_cuotas == "2"){
	                    
	                    $estado ="APROBADO";
	                }
	                elseif($res->id_estado_registro_tres_cuotas == "3"){
	                    
	                    $estado ="NEGADO";
	                }
	                
	                
	                
	                $i++;
	                $html.='<tr>';
	               
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 14px;"><a onclick="AprobarRegistro('.$res->id_registro_tres_cuotas.')"   href="#" class="btn btn-success" style="font-size:65%;"data-toggle="tooltip" title="Aprobar"><i class="glyphicon glyphicon-plus"></i></a>
                    <a onclick="NegarRegistro('.$res->id_registro_tres_cuotas.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Negar"><i class="glyphicon glyphicon-remove"></i></a></td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes." ".$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_concesion_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->monto_otorgado_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_pago_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->total_valor_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->balance_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$estado.'</td>';
	                $html.='<td><a title="Solicitud" target="_blank" href="view/DevuelvePDFView.php?id_valor='.$res->id_registro_tres_cuotas.'&id_nombre=id_registro_tres_cuotas&tabla=registro_tres_cuotas&campo=pdf_registro_tres_cuotas"><img src="view/images/logo_pdf.png" width="30" height="30"></a></td>';
	                $html.='<td><a class="btn bg-blue" title="Tabla Amortización" href="index.php?controller=TablaAmortizacion&action=ReporteTablaAmortizacion&id_creditos='.$res->id_creditos.'" role="button" target="_blank"><i class="glyphicon glyphicon-list-alt"></i></a></font></td>';
	                
	   
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_ConsultaRegistroTresCuotas("index.php", $page, $total_pages, $adjacents,"ConsultaRegistroTresCuotas").'';
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

	public function paginate_ConsultaRegistroTresCuotas($reload, $page, $tpages, $adjacents, $funcion = "") {
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
	
	public function ConsultaRegistroTresCuotasAprobado(){
	    
	    session_start();
	    $registro = new RegistroModel();
	    
	    $where_to="";
	    $columnas  = "registro_tres_cuotas.id_registro_tres_cuotas,
                      core_participes.id_participes,
                      core_participes.apellido_participes,
                      core_participes.nombre_participes,
                      core_participes.cedula_participes,
                      core_creditos.id_creditos,
                      core_creditos.numero_creditos,
                      core_creditos.monto_otorgado_creditos,
                      core_creditos.saldo_actual_creditos,
                      core_creditos.fecha_concesion_creditos,
                      core_creditos.plazo_creditos,
                      core_tipo_creditos.id_tipo_creditos,
                      core_tipo_creditos.nombre_tipo_creditos,
                      registro_tres_cuotas.pdf_registro_tres_cuotas,
                      estado_registro_tres_cuotas.id_estado_registro_tres_cuotas,
                      estado_registro_tres_cuotas.nombre_estado_registro_tres_cuotas,
                      TO_CHAR(registro_tres_cuotas.creado,'YYYY-MM-DD HH:MI:SS') as \"creado\",
	                  registro_tres_cuotas.modificado,
	                  (
	                    select numero_pago_tabla_amortizacion
						from core_tabla_amortizacion
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as numero_pago_tabla_amortizacion,
	                  (
	                    select  fecha_tabla_amortizacion
						from core_tabla_amortizacion
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as fecha_tabla_amortizacion,
	                  (
	                    select  capital_tabla_amortizacion
						from core_tabla_amortizacion
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as capital_tabla_amortizacion,
	                  (
	                    select  total_valor_tabla_amortizacion
						from core_tabla_amortizacion
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as total_valor_tabla_amortizacion,
	                  (
	                    select  balance_tabla_amortizacion
						from core_tabla_amortizacion
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as balance_tabla_amortizacion";
	    
	    $tablas =  "public.registro_tres_cuotas,
                    public.core_participes,
                    public.core_creditos,
                    public.core_tipo_creditos,
                    public.estado_registro_tres_cuotas";
	    
	    $where = "registro_tres_cuotas.id_participes = core_participes.id_participes AND
                  core_creditos.id_creditos = registro_tres_cuotas.id_creditos AND
                  core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos AND
                  estado_registro_tres_cuotas.id_estado_registro_tres_cuotas = registro_tres_cuotas.id_estado_registro_tres_cuotas AND
                  estado_registro_tres_cuotas.id_estado_registro_tres_cuotas = 2";
	    
	    $id = "registro_tres_cuotas.id_registro_tres_cuotas";
	    
	    
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND core_participes.cedula_participes ILIKE '".$search."%'";
	            
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
	            $html.= "<table id='tabla_bancos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cédula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Participe</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Número Crédito</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo de Crédito</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha de Conseción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Monto</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Pago</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ultima Cuota Pagada</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cuota</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Saldo Capital</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Solicitud</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Amortización</th>';
	            
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                
	                if($res->id_estado_registro_tres_cuotas == "1"){
	                    
	                    $estado ="PENDIENTE";
	                    
	                }elseif($res->id_estado_registro_tres_cuotas == "2"){
	                    
	                    $estado ="APROBADO";
	                }
	                elseif($res->id_estado_registro_tres_cuotas == "3"){
	                    
	                    $estado ="NEGADO";
	                }
	                
	                
	                
	                $i++;
	                $html.='<tr>';
	                
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes." ".$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_concesion_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->monto_otorgado_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_pago_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->total_valor_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->balance_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$estado.'</td>';
	                $html.='<td><a title="Solicitud" target="_blank" href="view/DevuelvePDFView.php?id_valor='.$res->id_registro_tres_cuotas.'&id_nombre=id_registro_tres_cuotas&tabla=registro_tres_cuotas&campo=pdf_registro_tres_cuotas"><img src="view/images/logo_pdf.png" width="30" height="30"></a></td>';
	                $html.='<td><a class="btn bg-blue" title="Tabla Amortización" href="index.php?controller=TablaAmortizacion&action=ReporteTablaAmortizacion&id_creditos='.$res->id_creditos.'" role="button" target="_blank"><i class="glyphicon glyphicon-list-alt"></i></a></font></td>';
	                
	                
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_ConsultaRegistroTresCuotas("index.php", $page, $total_pages, $adjacents,"ConsultaRegistroTresCuotas").'';
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
	
	public function paginate_ConsultaRegistroTresCuotasAprobado($reload, $page, $tpages, $adjacents, $funcion = "") {
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
	
	public function ConsultaRegistroTresCuotasNegado(){
	    
	    session_start();
	    $registro = new RegistroModel();
	    
	    $where_to="";
	    $columnas  = "registro_tres_cuotas.id_registro_tres_cuotas,
                      core_participes.id_participes,
                      core_participes.apellido_participes,
                      core_participes.nombre_participes,
                      core_participes.cedula_participes,
                      core_creditos.id_creditos,
                      core_creditos.numero_creditos,
                      core_creditos.monto_otorgado_creditos,
                      core_creditos.saldo_actual_creditos,
                      core_creditos.fecha_concesion_creditos,
                      core_creditos.plazo_creditos,
                      core_tipo_creditos.id_tipo_creditos,
                      core_tipo_creditos.nombre_tipo_creditos,
                      registro_tres_cuotas.pdf_registro_tres_cuotas,
                      estado_registro_tres_cuotas.id_estado_registro_tres_cuotas,
                      estado_registro_tres_cuotas.nombre_estado_registro_tres_cuotas,
                      TO_CHAR(registro_tres_cuotas.creado,'YYYY-MM-DD HH:MI:SS') as \"creado\",
	                  registro_tres_cuotas.modificado,
	                  (
	                    select numero_pago_tabla_amortizacion
						from core_tabla_amortizacion
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as numero_pago_tabla_amortizacion,
	                  (
	                    select  fecha_tabla_amortizacion
						from core_tabla_amortizacion
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as fecha_tabla_amortizacion,
	                  (
	                    select  capital_tabla_amortizacion
						from core_tabla_amortizacion
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as capital_tabla_amortizacion,
	                  (
	                    select  total_valor_tabla_amortizacion
						from core_tabla_amortizacion
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as total_valor_tabla_amortizacion,
	                  (
	                    select  balance_tabla_amortizacion
						from core_tabla_amortizacion
						where id_creditos = registro_tres_cuotas.id_creditos and id_estado_tabla_amortizacion = 2 order by fecha_tabla_amortizacion desc limit 1
	                  ) as balance_tabla_amortizacion";
	    
	    $tablas =  "public.registro_tres_cuotas,
                    public.core_participes,
                    public.core_creditos,
                    public.core_tipo_creditos,
                    public.estado_registro_tres_cuotas";
	    
	    $where = "registro_tres_cuotas.id_participes = core_participes.id_participes AND
                  core_creditos.id_creditos = registro_tres_cuotas.id_creditos AND
                  core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos AND
                  estado_registro_tres_cuotas.id_estado_registro_tres_cuotas = registro_tres_cuotas.id_estado_registro_tres_cuotas AND
                  estado_registro_tres_cuotas.id_estado_registro_tres_cuotas = 3";
	    
	    $id = "registro_tres_cuotas.id_registro_tres_cuotas";
	    
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND core_participes.cedula_participes ILIKE '".$search."%'";
	            
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
	            $html.= "<table id='tabla_bancos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cédula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Participe</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Número Crédito</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo de Crédito</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha de Conseción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Monto</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Pago</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ultima Cuota Pagada</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cuota</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Saldo Capital</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Solicitud</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Amortización</th>';
	            
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                
	                if($res->id_estado_registro_tres_cuotas == "1"){
	                    
	                    $estado ="PENDIENTE";
	                    
	                }elseif($res->id_estado_registro_tres_cuotas == "2"){
	                    
	                    $estado ="APROBADO";
	                }
	                elseif($res->id_estado_registro_tres_cuotas == "3"){
	                    
	                    $estado ="NEGADO";
	                }
	                
	                
	                
	                $i++;
	                $html.='<tr>';
	                
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes." ".$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_concesion_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->monto_otorgado_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_pago_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->total_valor_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->balance_tabla_amortizacion.'</td>';
	                $html.='<td style="font-size: 11px;">'.$estado.'</td>';
	                $html.='<td><a title="Solicitud" target="_blank" href="view/DevuelvePDFView.php?id_valor='.$res->id_registro_tres_cuotas.'&id_nombre=id_registro_tres_cuotas&tabla=registro_tres_cuotas&campo=pdf_registro_tres_cuotas"><img src="view/images/logo_pdf.png" width="30" height="30"></a></td>';
	                $html.='<td><a class="btn bg-blue" title="Tabla Amortización" href="index.php?controller=TablaAmortizacion&action=ReporteTablaAmortizacion&id_creditos='.$res->id_creditos.'" role="button" target="_blank"><i class="glyphicon glyphicon-list-alt"></i></a></font></td>';
	                
	                
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_ConsultaRegistroTresCuotas("index.php", $page, $total_pages, $adjacents,"ConsultaRegistroTresCuotas").'';
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
	
	public function paginate_ConsultaRegistroTresCuotasNegado($reload, $page, $tpages, $adjacents, $funcion = "") {
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
	     
	        if(isset($_POST["id_registro_tres_cuotas"])){
	            
	            $id_registro_tres_cuotas = (int)$_POST["id_registro_tres_cuotas"];
	            
	            $columna = "id_estado_registro_tres_cuotas = 2";
	            $tablas = "registro_tres_cuotas";
	            $where= "id_registro_tres_cuotas = $id_registro_tres_cuotas";
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
	     
	        if(isset($_POST["id_registro_tres_cuotas"])){
	            
	            $id_registro_tres_cuotas = (int)$_POST["id_registro_tres_cuotas"];
	            
	            $columna = "id_estado_registro_tres_cuotas = 3";
	            $tablas = "registro_tres_cuotas";
	            $where= "id_registro_tres_cuotas = $id_registro_tres_cuotas";
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