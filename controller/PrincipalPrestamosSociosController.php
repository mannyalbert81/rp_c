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
	        
	        $col1  = "core_creditos.id_creditos, 
                      core_creditos.numero_creditos,
                      core_creditos.fecha_concesion_creditos,
                      core_creditos.monto_otorgado_creditos,
                      core_creditos.plazo_creditos,
                      core_creditos.monto_neto_entregado_creditos,
                      core_creditos.cuota_creditos,
                      core_participes.id_participes, 
                      core_participes.apellido_participes, 
                      core_participes.nombre_participes, 
                      core_participes.cedula_participes, 
                      core_entidad_patronal.id_entidad_patronal, 
                      core_entidad_patronal.nombre_entidad_patronal, 
                      core_entidad_patronal.ruc_entidad_patronal, 
                      core_entidad_patronal.tipo_entidad_patronal,
                      core_estado_creditos.id_estado_creditos, 
                      core_estado_creditos.nombre_estado_creditos,
                      core_tipo_creditos.id_tipo_creditos, 
                      core_tipo_creditos.nombre_tipo_creditos,
                      core_tipo_creditos.interes_tipo_creditos,
                      core_creditos.receptor_solicitud_creditos,                      
                      core_tipo_creditos.plazo_maximo_tipo_creditos";
	        $tab1  = " public.core_creditos, 
                        public.core_participes, 
                        public.core_entidad_patronal,
                        public.core_estado_creditos,
                        public.core_tipo_creditos";
	        $whe1  = " core_participes.id_participes = core_creditos.id_participes AND
                        core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND 
                        core_estado_creditos.id_estado_creditos = core_creditos.id_estado_creditos AND 
                        core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos AND
                        core_creditos.id_creditos = $id_creditos ";
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
                      core_tabla_amortizacion.seguro_desgravamen_tabla_amortizacion,
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
                      (select sum(c1.interes_tabla_amortizacion)
                      from core_tabla_amortizacion c1 where id_creditos = '$id_creditos' and id_estatus=1 limit 1
                      ) as \"totalintereses\",
                      (select sum(c1.seguro_desgravamen_tabla_amortizacion)
                      from core_tabla_amortizacion c1 where id_creditos = '$id_creditos' and id_estatus=1 limit 1
                      ) as \"totalseguro\",
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
	    $nombre_usuarios= $_SESSION['nombre_usuarios'];
	    $apellido_usuarios= $_SESSION['apellido_usuarios'];
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
                      core_tipo_creditos.nombre_tipo_creditos,
                      core_creditos.receptor_solicitud_creditos";
	    $tablas ="public.core_creditos,
                  public.core_participes,
                  public.core_tipo_creditos";
	    $where= "core_participes.id_participes = core_creditos.id_participes AND
                 core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos AND core_creditos.id_creditos = '$id_creditos'";
	    $id="core_creditos.id_creditos";
	  
	    $rsdatos = $participes->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $datos_reporte['NOMBRE_PARTICIPES']=$rsdatos[0]->nombre_participes;
	    $datos_reporte['APELLIDO_PARTICIPES']=$rsdatos[0]->apellido_participes;
	    $datos_reporte['CEDULA_PARTICIPES']=$rsdatos[0]->cedula_participes;
	    $datos_reporte['TIPO_CREDITOS']=$rsdatos[0]->nombre_tipo_creditos;
	    $datos_reporte['NUMERO_CREDITOS']=$rsdatos[0]->numero_creditos;
	    $datos_reporte['FECHA_CONSECION']=$rsdatos[0]->fecha_concesion_creditos;
	    $datos_reporte['RECEPTOR_SOLICITUD']=$rsdatos[0]->receptor_solicitud_creditos;
	    $datos_reporte['APELLIDO_USUARIO']=$apellido_usuarios;
	    $datos_reporte['NOMBRE_USUARIO']=$nombre_usuarios;
	    
	    
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
	       //$this->verReporte("ReporteTablaAmortizacion", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte, 'datos_garante'=>$datos_garante, 'datos'=>$datos));
	    
	    
	    
	}
	
	
}
?>