	<?php

    class ReporteCierreMesController extends ControladorBase{
	public function __construct() {
		parent::__construct();
		
	}
	
	public function index5(){
	    
	    session_start();
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        $controladores = new ControladoresModel();
	        $nombre_controladores = "ReporteCierreMes";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $controladores->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (!empty($resultPer))
	        {
	            
	            
	            
	            $this->view_Credito("ReporteCierreMes",array(
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
	
	
	
		

	

	
	public function ConsultaReporteCierreMes(){
	    
	    session_start();
	    
	    
	    $reporte_cierre_mes = new ReporteCierreMesModel();
	    
	    $where_to="";
	    $columnas = " core_creditos_cierre_mes.id_creditos_cierre_mes,
	    cc.id_creditos,
	    cc.numero_creditos,
	    cc.monto_otorgado_creditos,
	    cc.saldo_actual_creditos,
	    cc.fecha_concesion_creditos,
	    core_estado_creditos.id_estado_creditos,
	    core_estado_creditos.nombre_estado_creditos,
	    cc.plazo_creditos,
	    cc.monto_neto_entregado_creditos,
	    core_participes.apellido_participes,
	    core_participes.nombre_participes,
	    core_participes.cedula_participes,
	    core_creditos_cierre_mes.fecha_ultimo_pago_capital,
	    core_creditos_cierre_mes.estado_credito_sbs,
	    ABS(core_creditos_cierre_mes.dias_vencidos_sbs) as dias_mora,
	    ( select sum( am1.mora_tabla_amortizacion )
	        from core_tabla_amortizacion am1
	        where am1.id_estatus = 1
	        and coalesce(am1.mora_tabla_amortizacion,0) > 0
	        and am1.id_creditos = cc.id_creditos
	        ) as total_mora";
	    
	    $tablas =  "  public.core_creditos_cierre_mes,
	        public.core_creditos cc,
	        public.core_participes,
	        public.core_estado_creditos";
	    $where= "      core_creditos_cierre_mes.id_participes = core_participes.id_participes AND
	        cc.id_creditos = core_creditos_cierre_mes.id_creditos AND
	        core_estado_creditos.id_estado_creditos = cc.id_estado_creditos AND core_creditos_cierre_mes.dias_vencidos_sbs < 0";
	    $id="core_participes.apellido_participes";
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            $where1=" AND cedula_participes ILIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$reporte_cierre_mes->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$reporte_cierre_mes->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">N° Crédito</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Monto</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ultimo Pago</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Dias Mora</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor Mora</th>';
	           
	            
	            
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
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">$ '.number_format($res->monto_otorgado_creditos, 2, ',', '.').'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado_creditos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_ultimo_pago_capital.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->dias_mora.'</td>';
	                $html.='<td style="font-size: 11px;">$ '.number_format($res->total_mora, 2, ',', '.').'</td>';
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<td><a class="btn bg-blue" title="Reporte" href="index.php?controller=ReporteCierreMes&action=ReporteCierreMesPDF" role="button" target="_blank"><i class="glyphicon glyphicon-list-alt"></i></a></font></td>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_consulta_cierre_mes("index.php", $page, $total_pages, $adjacents,"ConsultaReporteCierreMes").'';
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

	public function paginate_consulta_cierre_mes($reload, $page, $tpages, $adjacents, $funcion = "") {
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
	
	public function Exportar_usuariosExcel()
	{
	    session_start();
	     
	    $reporte_cierre_mes = new ReporteCierreMesModel();
	    
	      $where_to="";
	      $columnas = " core_creditos_cierre_mes.id_creditos_cierre_mes,
	    cc.id_creditos,
	    cc.numero_creditos,
	    cc.monto_otorgado_creditos,
	    cc.saldo_actual_creditos,
	    cc.fecha_concesion_creditos,
	    core_estado_creditos.id_estado_creditos,
	    core_estado_creditos.nombre_estado_creditos,
	    cc.plazo_creditos,
	    cc.monto_neto_entregado_creditos,
	    core_participes.apellido_participes,
	    core_participes.nombre_participes,
	    core_participes.cedula_participes,
	    core_creditos_cierre_mes.fecha_ultimo_pago_capital,
	    core_creditos_cierre_mes.estado_credito_sbs,
	    ABS(core_creditos_cierre_mes.dias_vencidos_sbs) as dias_mora,
	    ( select sum( am1.mora_tabla_amortizacion )
	        from core_tabla_amortizacion am1
	        where am1.id_estatus = 1
	        and coalesce(am1.mora_tabla_amortizacion,0) > 0
	        and am1.id_creditos = cc.id_creditos
	        ) as total_mora";
	      
	      $tablas =  "  public.core_creditos_cierre_mes,
	        public.core_creditos cc,
	        public.core_participes,
	        public.core_estado_creditos";
	      $where= "      core_creditos_cierre_mes.id_participes = core_participes.id_participes AND
	        cc.id_creditos = core_creditos_cierre_mes.id_creditos AND
	        core_estado_creditos.id_estado_creditos = cc.id_estado_creditos AND core_creditos_cierre_mes.dias_vencidos_sbs < 0";
	      $id="core_participes.apellido_participes";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (core_creditos.numero_creditos LIKE '".$search."%' OR core_participes.cedula_participes LIKE '".$search."%' OR core_participes.apellido_participes LIKE '".$search."%' OR core_participes.nombre_participes LIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        
	        $resultSet=$reporte_cierre_mes->getCondiciones($columnas, $tablas, $where_to, $id);
	        $_respuesta=array();
	        
	        array_push($_respuesta, 'CEDULA', 'APELLIDO', 'NOMBRE','N° CREDITOS','MONTO','ESTADO','ULTIMO PAGO','DIAS MORA','VALOR MORA');
	        
	        if(!empty($resultSet)){
	            
	            foreach ($resultSet as $res){
	                
	                array_push($_respuesta,       
        	                    $res->cedula_participes,
        	                    $res->apellido_participes,
        	                    $res->nombre_participes,
        	                    $res->numero_creditos,
        	                    $res->monto_otorgado_creditos,
        	                    $res->nombre_estado_creditos,
        	                    $res->fecha_ultimo_pago_capital,
        	                    $res->dias_mora,
	                            $res->total_mora );
        	            }
	            echo json_encode($_respuesta);
	        }
	    }
	    
	}
	
	
	
	
	public function ReporteCierreMesPDF(){
	    session_start();
	    
	    $entidades = new EntidadesModel();
	    //PARA OBTENER DATOS DE LA EMPRESA
	    $datos_empresa = array();
	    $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
	    
	    if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0){
	        //llenar nombres con variables que va en html de reporte
	        $datos_empresa['NOMBREEMPRESA']=$rsdatosEmpresa[0]->nombre_entidades;
	        $datos_empresa['DIRECCIONEMPRESA']=$rsdatosEmpresa[0]->direccion_entidades;
	        $datos_empresa['TELEFONOEMPRESA']=$rsdatosEmpresa[0]->telefono_entidades;
	        $datos_empresa['RUCEMPRESA']=$rsdatosEmpresa[0]->ruc_entidades;
	        $datos_empresa['FECHAEMPRESA']=date('Y-m-d H:i');
	        $datos_empresa['USUARIOEMPRESA']=(isset($_SESSION['usuario_usuarios']))?$_SESSION['usuario_usuarios']:'';
	    }
	   
	    
	    
	    $reporte_cierre_mes = new ReporteCierreMesModel();

	    $columnas = " core_creditos_cierre_mes.id_creditos_cierre_mes,
	    cc.id_creditos,
	    cc.numero_creditos,
	    cc.monto_otorgado_creditos,
	    cc.saldo_actual_creditos,
	    cc.fecha_concesion_creditos,
	    core_estado_creditos.id_estado_creditos,
	    core_estado_creditos.nombre_estado_creditos,
	    cc.plazo_creditos,
	    cc.monto_neto_entregado_creditos,
	    core_participes.apellido_participes,
	    core_participes.nombre_participes,
	    core_participes.cedula_participes,
	    core_creditos_cierre_mes.fecha_ultimo_pago_capital,
	    core_creditos_cierre_mes.estado_credito_sbs,
	    ABS(core_creditos_cierre_mes.dias_vencidos_sbs) as dias_mora,
	    ( select sum( am1.mora_tabla_amortizacion )
	        from core_tabla_amortizacion am1
	        where am1.id_estatus = 1
	        and coalesce(am1.mora_tabla_amortizacion,0) > 0
	        and am1.id_creditos = cc.id_creditos
	        ) as total_mora";	    
	    
	    $tablas =  "  public.core_creditos_cierre_mes,
	        public.core_creditos cc,
	        public.core_participes,
	        public.core_estado_creditos";
	    $where= "      core_creditos_cierre_mes.id_participes = core_participes.id_participes AND
	        cc.id_creditos = core_creditos_cierre_mes.id_creditos AND
	        core_estado_creditos.id_estado_creditos = cc.id_estado_creditos AND core_creditos_cierre_mes.dias_vencidos_sbs < 0";
	    $id="core_participes.apellido_participes";

	    //$id=" ORDER BY core_creditos_cierre_mes.id_creditos_cierre_mes LIMIT 100";
	    $rsdatos = $reporte_cierre_mes->getCondiciones($columnas, $tablas, $where, $id);
	    //$rsdatos = $reporte_cierre_mes->getCondicionesSinOrden($columnas, $tablas, $where, $id);
	    
	   //var_dump($rsdatos); die();
	 
	    $data_detalle = array();
	    
	    $html  = "";
	    $html.='<table class="12" style="width:98px;" border=1>';
	    $html  .= "<tr>";
	    $html.='<th colspan="2" style="text-align: center; font-size: 11px;">#</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 11px;">CÉDULA</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 11px;">APELLIDO</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 11px;">NOMBRE</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 11px;">N° CRÉDITO</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 11px;">MONTO</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 11px;">ESTADO</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 11px;">ULTIMO PAGO</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 11px;">DIAS MORA</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 11px;">VALOR MORA</th>';
	    
	    $html  .= "</tr>";
	    
	    
	    foreach ( $rsdatos as $res ){
	       
	        $i++;
	        $html.='<tr>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 9px;" align="center">'.$i.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 9px;" align="center">'.$res->cedula_participes.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 9px;" align="center">'.$res->apellido_participes.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 9px;" align="center">'.$res->nombre_participes.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 9px;" align="center">'.$res->numero_creditos.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 9px;" align="right">$ '.number_format($res->monto_otorgado_creditos, 2, ',', '.').'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 9px;" align="center">'.$res->nombre_estado_creditos.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 9px;" align="center">'.$res->fecha_ultimo_pago_capital.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 9px;" align="center">'.$res->dias_mora.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 9px;" align="right">$ '.number_format($res->total_mora, 2, ',', '.').'</td>';
	        
	        
	        
	        
	         
	        $html  .= "</tr>";
	        
	    }
	    
	    $html  .= "</table>";
	    
	    
	    $data_detalle['TABLA_REPORTES'] = $html;
	    
	    
	        
	    $this->verReporte("ReporteCierreMesPDF", array('datos_empresa'=>$datos_empresa, 'data_detalle'=>$data_detalle));
	        
	        
	}
	
	
	
   }
    
    
    
    
    ?>