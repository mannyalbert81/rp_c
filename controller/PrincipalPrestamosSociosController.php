<?php

class PrincipalPrestamosSociosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
	
	    session_start();
	    
	    $busquedas = new PrincipalPrestamosModel();
	    
	    if( empty( $_SESSION['usuario_usuarios'] ) ){
	        $this->redirect("Usuarios","sesion_caducada");
	        exit();
	    }
	    
	    $nombre_controladores = "PrincipalPrestamosSocios";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $busquedas->getPermisosVer(" controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso al proceso de Generar el Archivo de pago"
	            
	        ));
	        exit();
	    }
	    //buscar el estado civil
	    $colCivil    = " id_estado_civil_participes,nombre_estado_civil_participes ";
	    $tabCivil    = " public.core_estado_civil_participes ";
	    $wheCivil    = " 1 = 1";
	    $rsCivil     = $busquedas->getCondicionesSinOrden($colCivil, $tabCivil, $wheCivil, "");
	    
	    //buscar el Genero
	    $colGen    = " id_genero_participes, nombre_genero_participes ";
	    $tabGen    = " public.core_genero_participes ";
	    $wheGen    = " 1 = 1";
	    $rsGen     = $busquedas->getCondicionesSinOrden($colGen, $tabGen, $wheGen, "");
	    
	    $datos = null;
	    $datos['rsCivil'] = $rsCivil;
	    $datos['rsGen']   = $rsGen;
	    
	    $this->view_principal("PrincipalPrestamosSocios",$datos);
	    
	
	}
	
	public function CargaDatosParticipePrestamos(){
	    
	    $busquedas = new PrincipalPrestamosModel();
	    
	    $resp  = null;
	    
	    try {
	        
	        $id_creditos = $_POST['id_creditos'];
	        
	        if( !empty( error_get_last() ) ){ throw new Exception("Variable no recibida"); }
	        
	        $col1  = "a.id_creditos, 
                      a.numero_creditos,
                      a.fecha_concesion_creditos,
                      a.monto_otorgado_creditos,
                      a.plazo_creditos,
                      a.monto_neto_entregado_creditos,
                      a.cuota_creditos,
                      b.id_participes, 
                      b.apellido_participes, 
                      b.nombre_participes, 
                      b.cedula_participes, 
                      c.id_entidad_patronal, 
                      c.nombre_entidad_patronal, 
                      c.ruc_entidad_patronal, 
                      c.tipo_entidad_patronal,
                      d.id_estado_creditos, 
                      d.nombre_estado_creditos,
                      e.id_tipo_creditos, 
                      e.nombre_tipo_creditos,
                      e.interes_tipo_creditos,
                      a.receptor_solicitud_creditos,                      
                      e.plazo_maximo_tipo_creditos,
                      f.apellido_participes as apellido_participes_garantes,
        		      f.nombre_participes as nombre_participes_garantes,
        		      f.cedula_participes as cedula_participes_garantes";
	        
	        $tab1  = "core_creditos a 
                        INNER JOIN core_participes b ON a.id_participes = b.id_participes 
                        INNER JOIN core_entidad_patronal c ON b.id_entidad_patronal = c.id_entidad_patronal
                        INNER JOIN core_estado_creditos d ON a.id_estado_creditos = d.id_estado_creditos
                        INNER JOIN core_tipo_creditos e ON a.id_tipo_creditos = e.id_tipo_creditos
            			LEFT JOIN (
            			SELECT b.apellido_participes,
            			b.nombre_participes,
            			b.cedula_participes,
            			a.id_creditos
            			FROM core_creditos_garantias a 
            			INNER JOIN core_participes b ON a.id_participes = b.id_participes   
            			) f ON a.id_creditos = f.id_creditos";
	        
	        $whe1  = " a.id_creditos = $id_creditos ";
	        
	        $rsConsulta1   = $busquedas->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	        
	        $resp['dataParticipePrestamos'] = ( empty($rsConsulta1) ) ? null : $rsConsulta1;
	        
	        $error_pg = pg_last_error();
	        if( !empty($error_pg) ){
	            throw new Exception( $error_pg );
	        }        
	        	        
	    } catch (Exception $e) {
	        $buffer =  error_get_last();
	        $resp['icon'] = isset($resp['icon']) ? $resp['icon'] : "error";
	        $resp['mensaje'] = $e->getMessage();
	        $resp['msgServer'] = $buffer; 
	        $resp['estatus'] = "ERROR";
	    }
	    
	    error_clear_last();
	    if (ob_get_contents()) ob_end_clean();
	    
	    echo json_encode($resp);
	}
	
	public function TablaAmortizacion()
	{
	    
	    session_start();
	    $registro= new TablaAmortizacionModel();
	    $id_creditos =  (isset($_POST['id_creditos'])&& $_POST['id_creditos'] !=NULL)?$_POST['id_creditos']:'';
	    
	    $where_to="";
	    $columnas = " core_creditos.id_creditos,
                      core_tabla_amortizacion.fecha_tabla_amortizacion,
                      core_tabla_amortizacion.capital_tabla_amortizacion,
                      core_tabla_amortizacion.interes_tabla_amortizacion,
                      core_tabla_amortizacion.total_valor_tabla_amortizacion,
                      core_tabla_amortizacion.mora_tabla_amortizacion,
                      core_tabla_amortizacion.balance_tabla_amortizacion,
                      core_tabla_amortizacion.id_estado_tabla_amortizacion,
                      core_tabla_amortizacion.numero_pago_tabla_amortizacion,
                      core_tabla_amortizacion.total_balance_tabla_amortizacion,
                      core_estado_tabla_amortizacion.nombre_estado_tabla_amortizacion,
                      core_tabla_amortizacion.total_valor_tabla_amortizacion,
                      (select sum(c1.capital_tabla_amortizacion)
                      from core_tabla_amortizacion c1 where id_creditos = '$id_creditos' and id_estatus=1 limit 1
                      ) as \"totalcapital\",
                      (select sum(c1.seguro_desgravamen_tabla_amortizacion)
                      from core_tabla_amortizacion c1 where id_creditos = '$id_creditos' and id_estatus=1 limit 1
                      ) as \"totalseguro\",
                      (select sum(c1.interes_tabla_amortizacion)
                      from core_tabla_amortizacion c1 where id_creditos = '$id_creditos' and id_estatus=1 limit 1
                      ) as \"totalintereses\",
                      (select sum(c1.total_valor_tabla_amortizacion)
                      from core_tabla_amortizacion c1 where id_creditos = '$id_creditos' and id_estatus=1 limit 1
                      ) as \"totalcuota\",
                      (select sum(c1.mora_tabla_amortizacion)
                      from core_tabla_amortizacion c1 where id_creditos = '$id_creditos' and id_estatus=1 limit 1
                      ) as \"totalmora\",
                                   (
	                    select COALESCE(SUM (r.valor_pago_tabla_amortizacion_pagos),0)
						from core_tabla_amortizacion_pagos r INNER JOIN core_tabla_amortizacion_parametrizacion p ON r.id_tabla_amortizacion_parametrizacion = p.id_tabla_amortizacion_parametrizacion
						where r.id_tabla_amortizacion = core_tabla_amortizacion.id_tabla_amortizacion AND p.tipo_tabla_amortizacion_parametrizacion = 8) as seguro_desgravamen_final,
            	    (
            	    select COALESCE(SUM (r.saldo_cuota_tabla_amortizacion_pagos),0)
            	    from core_tabla_amortizacion_pagos r INNER JOIN core_tabla_amortizacion_parametrizacion p ON r.id_tabla_amortizacion_parametrizacion = p.id_tabla_amortizacion_parametrizacion
            	    where r.id_tabla_amortizacion = core_tabla_amortizacion.id_tabla_amortizacion) as saldo_final";
	    
	    
	    $tablas = "   public.core_creditos,
                      public.core_tabla_amortizacion,
                      public.core_estado_tabla_amortizacion";
	    $where= "   core_tabla_amortizacion.id_creditos = core_creditos.id_creditos AND
                    core_estado_tabla_amortizacion.id_estado_tabla_amortizacion = core_tabla_amortizacion.id_estado_tabla_amortizacion
                    AND core_creditos.id_creditos ='$id_creditos' AND core_tabla_amortizacion.id_estatus=1";
	    $id="core_tabla_amortizacion.numero_pago_tabla_amortizacion";
	    
	     
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND core_estado_tabla_amortizacion.nombre_estado_tabla_amortizacion ILIKE '".$search."%'";
	            
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
	        
	        $resultSet=$registro->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:235px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">Número Pago</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Fecha</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Capital</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Intereses</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Seg. Desgrav.</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Mora</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Cuota</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Saldo Cuota</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Saldo Capital</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Estado</th>';
	          
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                         $i++;
	                $html.='<tr>';
	                
	                
	                
	                $html.='<td style="font-size: 11px;"align="center">'.$res->numero_pago_tabla_amortizacion.'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;">'.$res->fecha_tabla_amortizacion.'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->capital_tabla_amortizacion, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->interes_tabla_amortizacion, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->seguro_desgravamen_final, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->mora_tabla_amortizacion, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->total_valor_tabla_amortizacion, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->saldo_final, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->balance_tabla_amortizacion, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="center">'.$res->nombre_estado_tabla_amortizacion.'</td>';
	                
	                 $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_TablaAmortizacion("index.php", $page, $total_pages, $adjacents,"TablaAmortizacion").'';
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
	
	
	
	public function paginate_TablaAmortizacion($reload, $page, $tpages, $adjacents, $funcion = "") {
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
	

	
	public function ReportePagare(){
	    session_start();
	    $participes = new CreditosModel();
	    $fechaactual = getdate();
	    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
	    
	    $id_creditos =  (isset($_REQUEST['id_creditos'])&& $_REQUEST['id_creditos'] !=NULL)?$_REQUEST['id_creditos']:'';
	    
	    $datos_reporte = array();
	    $columnas =  "core_creditos.id_creditos, 
                      core_creditos.numero_creditos, 
                      core_participes.id_participes, 
                      core_participes.nombre_participes, 
                      core_participes.apellido_participes, 
                      core_participes.cedula_participes, 
                      core_participes.fecha_nacimiento_participes, 
                      core_creditos.fecha_concesion_creditos, 
                      core_tipo_creditos.id_tipo_creditos, 
                      core_tipo_creditos.nombre_tipo_creditos";
	    $tablas ="public.core_creditos, 
                  public.core_participes, 
                  public.core_tipo_creditos";
	    $where= "core_participes.id_participes = core_creditos.id_participes AND
                 core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos AND core_creditos.id_creditos = '$id_creditos'";
	    $id="core_creditos.id_creditos";
	    
	    $rsdatos = $participes->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $d = $rsdatos[0]->fecha_concesion_creditos;
	    $fecha = strftime("%d de %B de %Y", strtotime($d));
	    $datos_reporte['FECHA']=$fechaactual;
	    $datos_reporte['NOMBRE_PARTICIPES']=$rsdatos[0]->nombre_participes;
	    $datos_reporte['APELLIDO_PARTICIPES']=$rsdatos[0]->apellido_participes;
	    $datos_reporte['CEDULA_PARTICIPES']=$rsdatos[0]->cedula_participes;
	    $datos_reporte['TIPO_CREDITOS']=$rsdatos[0]->nombre_tipo_creditos;
	    $datos_reporte['NUMERO_CREDITOS']=$rsdatos[0]->numero_creditos;
	    $datos_reporte['FECHA_CONSECION']=$rsdatos[0]->fecha_concesion_creditos;
	    $datos_reporte['FECHA_CONSECION']=$fecha;
	    
	    $this->verReporte("ReportePagare", array('datos_reporte'=>$datos_reporte ));
	    
	    
	    
	}
	
	
	public function ReporteRecibo(){
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
	    
	    //NOTICE DATA
	    $datos_cabecera = array();
	    $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
	    $datos_cabecera['FECHA'] = date('Y/m/d');
	    $datos_cabecera['HORA'] = date('h:i:s');
	    
	    
	    
	    $participes = new CreditosModel();
	    $id_creditos =  (isset($_REQUEST['id_creditos'])&& $_REQUEST['id_creditos'] !=NULL)?$_REQUEST['id_creditos']:'';
	    $datos_reporte = array();
	    $columnas =  "solicitud1, nombre_solicitante1, identificacion1, recibido_por1, tipo_cuenta1, banco1, num_cuenta1, monto1, plazo1, cuota1, cuenta_individual, tipo_prestamo1,
	                  TO_CHAR(fecha_hora1,'YYYY-MM-DD HH:MI:SS') as fecha_hora1,
	                  estado1, tipo_encaje1, id_tipo_prestamos1, tipo_bien1, tipo_codigo1, monto_neto1, balance_disponible_garantias1, salario_liquido1, cuota_otros_creditos1, observacion1, ultimos_aportes1";
	    $tablas ="fc_participes_recibo_presentacion ($id_creditos)";
	    $rsdatos = $participes->getCondicionesFunciones($columnas, $tablas);
	    
	    if(!empty ($rsdatos)){
	        
	        $datos_reporte['SOLICITUD']=$rsdatos[0]->tipo_codigo1.'-'.$rsdatos[0]->solicitud1;
	        $datos_reporte['NOMBRE_SOLICITANTE']=$rsdatos[0]->nombre_solicitante1;
	        $datos_reporte['CEDULA']=$rsdatos[0]->identificacion1;
	        $datos_reporte['TIPO_ENCAJE']=$rsdatos[0]->tipo_encaje1.' '.$rsdatos[0]->ultimos_aportes1;
	        $datos_reporte['RECIBIDO_POR']=$rsdatos[0]->recibido_por1;
	        $datos_reporte['NUMERO_CUENTA']=$rsdatos[0]->num_cuenta1;
	        $datos_reporte['BANCO']=$rsdatos[0]->banco1;
	        $datos_reporte['TIPO_CUENTA']=$rsdatos[0]->tipo_cuenta1;
	        $datos_reporte['TOTAL_BENEFICIO_CUENTA_INDIVIDUAL']=$rsdatos[0]->cuenta_individual;
	        $datos_reporte['MONTO_SOLICITADO']=$rsdatos[0]->monto1;
	        $datos_reporte['PLAZO']=$rsdatos[0]->plazo1;
	        $datos_reporte['CUOTA']=$rsdatos[0]->cuota1;
	        $datos_reporte['LIQUIDO_RECIBIR']=$rsdatos[0]->monto_neto1;
	        $datos_reporte['OBSERVACIONES']=$rsdatos[0]->observacion1;
	        $datos_reporte['FECHA_HORA']=$rsdatos[0]->fecha_hora1;
	        $datos_reporte['TIPO_PRESTAMO']=$rsdatos[0]->tipo_prestamo1;
	    }else {
	        
	        $datos_reporte['SOLICITUD']='';
	        $datos_reporte['NOMBRE_SOLICITANTE']='';
	        $datos_reporte['CEDULA']='';
	        $datos_reporte['TIPO_ENCAJE']='';
	        $datos_reporte['RECIBIDO_POR']='';
	        $datos_reporte['NUMERO_CUENTA']='';
	        $datos_reporte['TOTAL_BENEFICIO_CUENTA_INDIVIDUAL']='0.00';
	        $datos_reporte['MONTO_SOLICITADO']='0.00';
	        $datos_reporte['PLAZO']='';
	        $datos_reporte['CUOTA']='';
	        $datos_reporte['LIQUIDO_RECIBIR']='0.00';
	        $datos_reporte['OBSERVACIONES']='';
	        $datos_reporte['FECHA_HORA']='';
	        $datos_reporte['TIPO_PRESTAMO']='';
	    }
	    
	    
	    //PARA SACAR RETENCION PRIMERA CUOTA
	    $columnas1 =  "total_retencion";
	    $tablas1 ="fc_creditos_obtener_total_retencion_primera_cuota ($id_creditos)";
	    $rsdatos1 = $participes->getCondicionesFunciones($columnas1, $tablas1);
	    
	    if(!empty ($rsdatos1) && ($rsdatos1[0]->total_retencion)>0){
	        
	        $datos_reporte['RETENCION_PRIMERA_CUOTA']=$rsdatos1[0]->total_retencion;
	        
	    }else {
	        
	        $datos_reporte['RETENCION_PRIMERA_CUOTA']='0.00';
	        
	    }
	    
	    //PARA SACAR RETENCION APORTES
	    $columnas2 =  "total_retencion_aportes";
	    $tablas2 ="fc_creditos_obtener_total_retencion_aportes ($id_creditos)";
	    $rsdatos2 = $participes->getCondicionesFunciones($columnas2, $tablas2);
	
	    if(!empty ($rsdatos2) && ($rsdatos2[0]->total_retencion_aportes)>0){
	        
	        $datos_reporte['RETENCION_APORTES']=$rsdatos2[0]->total_retencion_aportes;
	        
	    }else {
	        
	        $datos_reporte['RETENCION_APORTES']='0.00';
	        
	    }
	    
	   
	  
	    //PARA SACAR CREDITOS RENOVADOS
	    $saldo_nombres = "";
	    $saldo_valores = "";
	    $texto = "Saldo Anterior: ";
	    
	    $columnas4=  "nombre_tipo_creditos, id_creditos_renovaciones, monto_otorgado_creditos, saldo_a_la_fecha_creditos_a_pagar_renovaciones";
	    $tablas4 ="fc_creditos_informar_solicitud_de_otro_credito ($id_creditos)";
	    $rsdatos4 = $participes->getCondicionesFunciones($columnas4, $tablas4);
	    
	    if(!empty($rsdatos4)){
	        
	        $html4="";
	        $html4.='<table class="1">';
	        
	        $html4.='<tr>';
	        $html4.='<td><b>Créditos Anteriores<b></td>';
	        $html4.='<td><b>No. Solicitud</b></td>';
	        $html4.='<td><b>Monto Otorgado</b></td>';
	        $html4.='<td><b>Saldo a la Fecha</b></td>';
	        $html4.='</tr>';
	        $i = count($rsdatos4);
	        $y = 0;
	        foreach ($rsdatos4 as $res)
	        
	        {
	            $y++;
	            if($y == $i){
	                $saldo_nombres = $saldo_nombres.$texto.$res->nombre_tipo_creditos;
	                $saldo_valores = $saldo_valores.$res->saldo_a_la_fecha_creditos_a_pagar_renovaciones;
	            }
	            else {
	                $saldo_nombres = $saldo_nombres.$texto.$res->nombre_tipo_creditos.'<br>';
	                $saldo_valores = $saldo_valores.$res->saldo_a_la_fecha_creditos_a_pagar_renovaciones.'<br>';
	                }
	            $html4.='<tr >';
	            $html4.='<td>'.$res->nombre_tipo_creditos.'</td>';
	            $html4.='<td>'.$res->id_creditos_renovaciones.'</td>';
	            $html4.='<td>'.$res->monto_otorgado_creditos.'</td>';
	            $html4.='<td>'.$res->saldo_a_la_fecha_creditos_a_pagar_renovaciones.'</td>';
	            $html4.='</tr>';
	    }
	    }else {
	        
	        
	        //PARA CREDITOS PREAPROBADOS
	        $columnasP=  "a_nombre_tipo_creditos_tmp AS nombre_tipo_creditos, a_id_creditos_tmp AS id_creditos_renovaciones, a_monto_otorgado_creditos_tmp AS  monto_otorgado_creditos, a_saldo_estimado AS saldo_a_la_fecha_creditos_a_pagar_renovaciones";
	        $tablasP ="fc_creditos_recibo_presentacion_nuevos ($id_creditos)";
	        $rsdatosP = $participes->getCondicionesFunciones($columnasP, $tablasP);
	        
	        if(!empty($rsdatosP)){
	            
	            $html4="";
	            $html4.='<table class="1">';
	            
	            $html4.='<tr>';
	            $html4.='<td><b>Créditos Anteriores<b></td>';
	            $html4.='<td><b>No. Solicitud</b></td>';
	            $html4.='<td><b>Monto Otorgado</b></td>';
	            $html4.='<td><b>Saldo a la Fecha</b></td>';
	            $html4.='</tr>';
	            $i = count($rsdatosP);
	            $y = 0;
	            foreach ($rsdatosP as $res)
	            
	            {
	                $y++;
	                if($y == $i){
	                    $saldo_nombres = $saldo_nombres.$texto.$res->nombre_tipo_creditos;
	                    $saldo_valores = $saldo_valores.$res->saldo_a_la_fecha_creditos_a_pagar_renovaciones;
	                }
	                else {
	                    $saldo_nombres = $saldo_nombres.$texto.$res->nombre_tipo_creditos.'<br>';
	                    $saldo_valores = $saldo_valores.$res->saldo_a_la_fecha_creditos_a_pagar_renovaciones.'<br>';
	                }
	                $html4.='<tr >';
	                $html4.='<td>'.$res->nombre_tipo_creditos.'</td>';
	                $html4.='<td>'.$res->id_creditos_renovaciones.'</td>';
	                $html4.='<td>'.$res->monto_otorgado_creditos.'</td>';
	                $html4.='<td>'.$res->saldo_a_la_fecha_creditos_a_pagar_renovaciones.'</td>';
	                $html4.='</tr>';
	            }
	        }else {
	        
	        
	        
	        $saldo_nombres = $texto;
	        $saldo_valores = '0.00';
	        
	        }
	        
	    }
	    $html4.='</table>';
	    $datos_reporte['TABLA_CREDITOS_ANTERIORES']=$html4;
	    $datos_reporte['SALDO_VALORES']=$saldo_valores;
	    $datos_reporte['SALDO_NOMBRES']=$saldo_nombres;
	    
	    //PARA SACAR SALDO ANTERIOR
        
	  
	
	    //PARA SACAR RESPONSABLES
	    $columnas5 =  "nivel_r1, usuario_usuarios1, 
                       TO_CHAR(fecha_registro_creditos_flujo_trabajo1,'YYYY-MM-DD HH:MI:SS') as fecha_registro_creditos_flujo_trabajo1,
	                   nivel_r2, usuario_usuarios2,
                       TO_CHAR(fecha_registro_creditos_flujo_trabajo2,'YYYY-MM-DD HH:MI:SS') as fecha_registro_creditos_flujo_trabajo2";
	    $tablas5 ="fc_responsables_solicitud_recibo_presentacion ($id_creditos)";
	    $rsdatos5 = $participes->getCondicionesFunciones($columnas5, $tablas5);
	    
	    if(!empty ($rsdatos5)){
	        
	        $datos_reporte['NIVER_R1']=$rsdatos5[0]->nivel_r1;
	        $datos_reporte['USUARIO_1']=$rsdatos5[0]->usuario_usuarios1;
	        $datos_reporte['FECHA_REGISTRO_FLUJO_1']=$rsdatos5[0]->fecha_registro_creditos_flujo_trabajo1;
	        $datos_reporte['NIVER_R2']=$rsdatos5[0]->nivel_r2;
	        $datos_reporte['USUARIO_2']=$rsdatos5[0]->usuario_usuarios2;
	        $datos_reporte['FECHA_REGISTRO_FLUJO_2']=$rsdatos5[0]->fecha_registro_creditos_flujo_trabajo2;
	        
	    }else {
	        
	        $datos_reporte['NIVER_R1']='';
	        $datos_reporte['USUARIO_1']='';
	        $datos_reporte['FECHA_REGISTRO_FLUJO_1']='';
	        $datos_reporte['NIVER_R2']='';
	        $datos_reporte['USUARIO_2']='';
	        $datos_reporte['FECHA_REGISTRO_FLUJO_2']='';
	        
	    }
	    
	    
	    
	    $datos = array();
	    
	    $cedula_capremci = $rsdatos[0]->cedula_participes;
	    $numero_credito = $rsdatos[0]->numero_creditos;
	    $tipo_documento="RECIBO DE PRESENTACION DE SOLICITUD";
	    
	    require dirname(__FILE__)."\phpqrcode\qrlib.php";
	    
	    $ubicacion = dirname(__FILE__).'\..\barcode_participes\\';
	    
	    //Si no existe la carpeta la creamos
	    if (!file_exists($ubicacion))
	        mkdir($ubicacion);
	        
	        $i++;
	        $filename = $ubicacion.$numero_credito.'.png';
	        
	        //Parametros de Condiguracion
	        
	        $tamaño = 2.5; //Tama�o de Pixel
	        $level = 'L'; //Precisi�n Baja
	        $framSize = 3; //Tama�o en blanco
	        $contenido = $tipo_documento.';'.$numero_credito.';'.$cedula_capremci; //Texto
	        
	        //Enviamos los parametros a la Funci�n para generar c�digo QR
	        QRcode::png($contenido, $filename, $level, $tamaño, $framSize);
	        
	        $qr_participes = '<img src="'.$filename.'">';
	        
	        
	        $datos['CODIGO_QR']= $qr_participes;
	        $datos_empresa['CODIGO_QR']= $qr_participes;
	    
	        $this->verReporte("ReporteRecibo", array('datos_reporte'=>$datos_reporte, 'datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos'=>$datos));
	     
	    
	    
	}
	
	public function Transacciones()
	{
	    
	    session_start();
	    $registro= new TransaccionesModel();
	    $id_creditos =  (isset($_POST['id_creditos'])&& $_POST['id_creditos'] !=NULL)?$_POST['id_creditos']:'';
	    
	    $where_to="";

	    $columnas = " a.id_transacciones,
                      a.id_creditos,
                      a.id_participes,
                      a.id_creditos_tipo_pago,
	                  c.id_creditos_tipo_pago,
                      c.nombre_creditos_tipo_pago,
                      TO_CHAR(a.fecha_transacciones,'YYYY-MM-DD') as \"fecha_transacciones\",
                      a.valor_transacciones,
                      a.observacion_transacciones,
	                  a.usuario_usuarios,
                      TO_CHAR(a.fecha_contable_core_transacciones,'YYYY-MM-DD') as \"fecha_contable_core_transacciones\",
                      a.id_ccomprobantes_ant,
                      b.id_modo_pago, 
                      b.nombre_modo_pago,
                      e.nombre_estado_transacciones";
	    
	    $tablas = "   core_transacciones a
	    inner join core_modo_pago b on a.id_modo_pago=b.id_modo_pago
	    inner join core_creditos_tipo_pago c on a.id_creditos_tipo_pago=c.id_creditos_tipo_pago
	    inner join core_estado_transacciones e on a.id_estado_transacciones=e.id_estado_transacciones";
	    
	    $where= "   a.id_creditos='$id_creditos' and a.id_status=1";
	    
	    $id="a.id_transacciones";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND c.nombre_creditos_tipo_pago ILIKE '".$search."%'";
	            
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
	        
	        $resultSet=$registro->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:235px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: center; font-size: 11px;">Recibo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Pago</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Fecha</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Valor</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Observaciones</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Usuario</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Fecha Contable</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Modo Pago</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Estado</th>';
	            
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                $i++;
	                $html.='<tr>';
	                
	                $html.='<td><a class="btn bg-blue" title="Recibo" href="index.php?controller=PrincipalPrestamosSocios&action=ReporteReciboTransacciones&id_transacciones='.$res->id_transacciones.'" role="button" target="_blank"><i class="glyphicon glyphicon-list-alt"></i></a></font></td>';
	                $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_creditos_tipo_pago.'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;">'.$res->fecha_transacciones.'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->valor_transacciones, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;">'.$res->observacion_transacciones.'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;">'.$res->usuario_usuarios.'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;">'.$res->fecha_contable_core_transacciones.'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_modo_pago.'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_estado_transacciones.'</td>';
	               
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_Transacciones("index.php", $page, $total_pages, $adjacents,"Transacciones").'';
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
	
	
	
	public function paginate_Transacciones($reload, $page, $tpages, $adjacents, $funcion = "") {
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
	
	public function ReporteReciboTransacciones(){
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
	    
	    //NOTICE DATA
	    $datos_cabecera = array();
	    $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
	    $datos_cabecera['FECHA'] = date('Y/m/d');
	    $datos_cabecera['HORA'] = date('h:i:s');
	    $participes = new TransaccionesModel();
	
	    $id_transacciones =  (isset($_REQUEST['id_transacciones'])&& $_REQUEST['id_transacciones'] !=NULL)?$_REQUEST['id_transacciones']:'';
	      $datos_reporte = array();
	     
	      $columnas =   "fvalue,
                         fobservation,
                         fname,
                         fcredit_name,
                         credit_payment_mode,
                         fcredittransactionid,
                         fidentificacion,
                         fjournalid,
                         fecha_creditos_pagos,
                         valor_creditos_pagos,
                         numero_referencia_creditos_pagos,
                         nombre_bancos_creditos_pagos,
                         cuenta_creditos_pagos,
                         descripcion_creditos_tipo_pagos_transacciones";
	      $tablas ="fc_creditos_reporte_transacciones ($id_transacciones)";
	      
	      
	      $rsdatos = $participes->getCondicionesFunciones($columnas, $tablas);
	      
	      $datos_reporte['NOMBRE_PARTICIPES']=$rsdatos[0]->fname;
	      $datos_reporte['IDENTIFICACION_PARTICIPES']=$rsdatos[0]->fidentificacion;
	      $datos_reporte['CANTIDAD']=number_format($rsdatos[0]->fvalue, 2, ",", ".");
	      $datos_reporte['CONCEPTO']=$rsdatos[0]->fobservation;
	      $datos_reporte['CREDITO']=$rsdatos[0]->fcredit_name;
	      $datos_reporte['NUMERO_TRANSACCION']=$rsdatos[0]->fcredittransactionid;
	      $datos_reporte['NUMERO_ASIENTO']=$rsdatos[0]->fjournalid;
	      $datos_reporte['FORMA_DE_PAGO']=$rsdatos[0]->descripcion_creditos_tipo_pagos_transacciones;
	      $datos_reporte['FECHA_PAGO']=$rsdatos[0]->fecha_creditos_pagos;
	      $datos_reporte['VALOR']=$rsdatos[0]->valor_creditos_pagos;
	      $datos_reporte['BANCO']=$rsdatos[0]->nombre_bancos_creditos_pagos;
	      $datos_reporte['DOCUMENTO']=$rsdatos[0]->numero_referencia_creditos_pagos;
	      $datos_reporte['MOTIVO_DE_PAGO']=$rsdatos[0]->credit_payment_mode;
	    
	    $query = "select atav.tipo_tabla_amortizacion_parametrizacion, atav.descripcion_tabla_amortizacion_parametrizacion, sum(ctd.valor_transaccion_detalle) as value, ct.fecha_transacciones
	    from core_transacciones ct
	    inner join core_transacciones_detalle ctd on ct.id_transacciones = ctd.id_transacciones
	    inner join core_tabla_amortizacion_pagos aatv on ctd.id_tabla_amortizacion_pago = aatv.id_tabla_amortizacion_pagos
	    inner join core_tabla_amortizacion_parametrizacion atav on atav.id_tabla_amortizacion_parametrizacion = aatv.id_tabla_amortizacion_parametrizacion
	    where aatv.id_estatus = 1 and ctd.id_status = 1 and ctd.id_estado_transacciones = 1
	    and ct.id_transacciones = $id_transacciones
	    group by atav.tipo_tabla_amortizacion_parametrizacion, atav.descripcion_tabla_amortizacion_parametrizacion, ct.fecha_transacciones";
	    
	    $rsdatos1 = $participes->enviaquery($query);
	    $html1="";
	    
	    if(!empty($rsdatos1)){
	        
	        $html1.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
	        $html1.='<tr>';
	        $html1.='<th style="font-size: 11px; "align="left">DESGLOSE DEL PAGO</th>';
	        $html1.='<th style="font-size: 11px; "align="right">VALOR</th>';
	        $html1.='</tr>';
	        
	        $valor_total = "";
	        
	        foreach ($rsdatos1 as $res) {
	           
	            $html1.='<tr>';
	            $html1.='<td style="font-size: 11px; "align="left">'.$res->descripcion_tabla_amortizacion_parametrizacion.'</td>';
	            $html1.='<td style="font-size: 11px; "align="right">'.number_format($res->value, 2, ",", ".").'</td>';
	            $html1.='</tr>';
	            
	            
	        }
	        
	        $valor_total = $valor_total+$res->value;
	        
	        
	        $html1.='<tr >';
	        $html1.='<td align="right";><b>TOTAL</b></td>';
	        $html1.='<td style="font-size: 11px; "align="right">'.number_format($valor_total, 2, ",", ".").'</td>';
	        $html1.='</tr>';
	        $html1.='</table>';
	        
	        
	    }
	    
	    
	    $datos_reporte['TABLA_VALORES']=$html1;
	    
	   
	    
	    
	    $cedula_capremci = $rsdatos[0]->cedula_participes;
	    $numero_credito = $rsdatos[0]->numero_creditos;
	    $tipo_documento="RECIBO DE PRESENTACION DE SOLICITUD";
	    
	    
	    
	    require dirname(__FILE__)."\phpqrcode\qrlib.php";
	    
	    $ubicacion = dirname(__FILE__).'\..\barcode_participes\\';
	    
	    //Si no existe la carpeta la creamos
	    if (!file_exists($ubicacion))
	        mkdir($ubicacion);
	        
	        $i++;
	        $filename = $ubicacion.$numero_credito.'.png';
	        
	        //Parametros de Condiguracion
	        
	        $tamaño = 2.5; //Tama�o de Pixel
	        $level = 'L'; //Precisi�n Baja
	        $framSize = 3; //Tama�o en blanco
	        $contenido = $tipo_documento.';'.$numero_credito.';'.$cedula_capremci; //Texto
	        
	        //Enviamos los parametros a la Funci�n para generar c�digo QR
	        QRcode::png($contenido, $filename, $level, $tamaño, $framSize);
	        
	        $qr_participes = '<img src="'.$filename.'">';
	        
	        
	        $datos['CODIGO_QR']= $qr_participes;
	        $datos_empresa['CODIGO_QR']= $qr_participes;
	        
	        $this->verReporte("ReporteReciboTransacciones", array('datos_reporte'=>$datos_reporte, 'datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos'=>$datos));
	        
	        
	}
	
	/* dc 2020-08-18 */
	public function ObtenerSaldosCredito()
	{
	    ob_start();
	    $resp  = array();
	    $html  = "";
	    try {
	        
	        $creditos  = new CreditosModel();
	        
	        if( !isset($_SESSION) )
	        {
	            session_start();
	        }
	        
	        $id_creditos   = $_POST['id_creditos'];
	        $fecha_reporte = $_POST['fecha_reporte'];
	        if( !empty( error_get_last() ) )
	            throw new Exception("variables no Recibidas");
	        
            $tipo_credito   = 0;
	        
	        $col1  = " id_creditos, id_tipo_creditos";
	        $tab1  = " public.core_creditos";
	        $whe1  = " id_estado_creditos = 4 AND id_estatus = 1 AND id_creditos = $id_creditos";
	        
	        $rs_Consulta1  = $creditos->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	        
	        if( empty($rs_Consulta1) )
	            throw  new Exception("Credito no cumple parametros");
	        
            $tipo_credito = $rs_Consulta1[0]->id_tipo_creditos;
	        
	        $paramsQuery   = " $id_creditos, '$fecha_reporte', 1000000 ";
            $functionquery = " SELECT id_tabla_amortizacion_parametrizacion_out, valor_out FROM fc_simular_pago_credito_por_fecha($paramsQuery)";
	        
            $rs_function   = $creditos->enviaquery( $functionquery );
            
            if( empty($rs_function) )
                throw new Exception("Datos no obtenidos");
            
            $col2  = " id_tabla_amortizacion_parametrizacion, descripcion_tabla_amortizacion_parametrizacion,orden_tabla_amortizacion_parametrizacion";
            $tab2  = " public.core_tabla_amortizacion_parametrizacion";
            $whe2  = " id_tipo_creditos = $tipo_credito";
            
            $rs_Consulta2  = $creditos->getCondicionesSinOrden($col2, $tab2, $whe2, "");
            
            $suma_valor = 0;
            //dibujar html
            $html .= ' <div>';
            $html .= ' <div class="box-footer no-padding ">';
            
            foreach ( $rs_function as $fun)
            {
                $id = $fun->id_tabla_amortizacion_parametrizacion_out;
                $valor  = $fun->valor_out;
                $descripcion    = "";
                $encontrado = false;
                foreach ( $rs_Consulta2 as $res )
                {
                    if( $res->id_tabla_amortizacion_parametrizacion == $id ){
                        $descripcion  = $res->descripcion_tabla_amortizacion_parametrizacion;
                        $encontrado = true;
                        $suma_valor += $valor;
                    }
                }
                
                $valor  = number_format( $valor,2,".",",");
                
                if( $encontrado )
                    $html .= '<div class="bio-row"><p><span class="tab2">'.$descripcion.'</span>:&nbsp; &nbsp;' . $valor . '</p></div>'; 
               
            }
            
            $html .= '<div class="bio-row"><p><span class="tab2">_________________________</span>________</p></div>';
            $html .= '<div class="bio-row"><p><span class="tab2">TOTAL</span>:&nbsp; &nbsp;' . number_format($suma_valor,2,".",",") . '</p></div>';
            
            $html .= ' </div>';
            $html .= ' </div>';
            
            $resp['estatus']    = "OK";
            $resp['mensaje']    = "";
            $resp['html']   = $html;
                      
	        
	    } catch (Exception $e) {
	        $resp['estatus']    = "ERROR";
	        $resp['mensaje']    = $e->getMessage();
	        $resp['html']   = $html;
	    }
	    
	    $salida = ob_get_clean();
	    
	    if( !empty($salida) ){
	        echo "Existen valores en Buffer de salida";
	    }else{
	        echo json_encode($resp);
	    }
	        
	}
	
	
}
?>