	<?php

    class ReporteCierreCreditosController extends ControladorBase{
	public function __construct() {
		parent::__construct();
		
	}
	
	public function index5(){
	    
	    session_start();
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        $controladores = new ControladoresModel();
	        $nombre_controladores = "ReporteCierreCreditos";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $controladores->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (!empty($resultPer))
	        {
	            
	            
	            
	            $this->view_Credito("ReporteCierreCreditos",array(
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
	
	
	
		

	

	
	public function ConsultaReporteCierreCreditos(){
	    
	    session_start();
	    
	    
	    $reporte_cierre_creditos = new ReporteCierreCreditosModel();
	    
	    $where_to="";
	    $columnas  = "core_documentos_hipotecario.id_core_documentos_hipotecario, 
                      core_documentos_hipotecario.subidos_core_documentos_hipotecario, 
                      core_documentos_hipotecario.estado_escrituras_core_documentos_hipotecario, 
                      core_documentos_hipotecario.archivo_escritura_core_documentos_hipotecario, 
                      core_documentos_hipotecario.estado_certificado_core_documentos_hipotecario, 
                      core_documentos_hipotecario.archivo_cretificado_core_documentos_hipotecario, 
                      core_documentos_hipotecario.estado_impuesto_core_documentos_hipotecario, 
                      core_documentos_hipotecario.archivo_impuesto_core_documentos_hipotecario, 
                      core_documentos_hipotecario.estado_avaluo_core_documentos_hipotecario, 
                      core_documentos_hipotecario.archivo_avaluo_core_documentos_hipotecario, 
                      core_documentos_hipotecario.valor_avaluo_core_documentos_hipotecario, 
                      core_creditos.id_creditos, 
                      core_creditos.numero_creditos, 
                      core_creditos.monto_otorgado_creditos, 
                      core_creditos.saldo_actual_creditos, 
                      core_creditos.monto_neto_entregado_creditos, 
                      core_creditos.numero_solicitud_creditos";
	    
	    $tablas    = "public.core_documentos_hipotecario, 
                      public.core_creditos";
	    
	    $where     = "core_documentos_hipotecario.id_creditos = core_creditos.id_creditos";
	    
	    $id        = "core_documentos_hipotecario.id_core_documentos_hipotecario";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND numero_creditos ILIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$reporte_cierre_creditos->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$reporte_cierre_creditos->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
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
	            $html.='<th style="text-align: left;  font-size: 12px;">Número Crédito</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Subido</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado Escritura</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado Certificado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado Impuesto</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado Avaluo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor Avaluo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Monto</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Saldo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Monto Neto</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Número Solicitud</th>';	                
	          
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                
	                $_id_core_documentos_hipotecario = $res->id_core_documentos_hipotecario;
	                
	                
	                $i++;
	                $html.='<tr>';
	                       
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->subidos_core_documentos_hipotecario.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->estado_escrituras_core_documentos_hipotecario.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->estado_certificado_core_documentos_hipotecario.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->estado_impuesto_core_documentos_hipotecario.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->estado_avaluo_core_documentos_hipotecario.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->valor_avaluo_core_documentos_hipotecario.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->monto_otorgado_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->saldo_actual_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->monto_neto_entregado_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_solicitud_creditos.'</td>';          
	                   
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_consulta_cierre_creditos("index.php", $page, $total_pages, $adjacents,"ConsultaReporteCierreCreditos").'';
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

	public function paginate_consulta_cierre_creditos($reload, $page, $tpages, $adjacents, $funcion = "") {
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

	
    }
    
    
    
    
    ?>