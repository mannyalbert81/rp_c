<?php

class ChequeController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		$bancos = new BancosModel();
				
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "Bancos";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $bancos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Bancos"
		        
		    ));
		    exit();
		}		    
			
		$rsBancos = $bancos->getBy(" 1 = 1 ");
		
				
		$this->view_tesoreria("GenerarCheques",array(
		    "resultSet"=>$rsBancos
	
		));
			
	
	}
	
	
	
	public function generar_reporte_productos () {
	    
	    
	    session_start();
	    
	    $html="";
	    $cedula_usuarios = $_SESSION["cedula_usuarios"];
	    $fechaactual = getdate();
	    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	    
	    $directorio = $_SERVER ['DOCUMENT_ROOT'] . '/rp_c';
	    $dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
	    $domLogo=$directorio.'/view/images/logoCapremci.png';
	    $logo = '<img src="'.$domLogo.'" alt="Responsive image" width="150" height="35">';
	    
	    $cheques= new ProductosModel();
	    
	    if(!empty($cedula_usuarios)){
	        
	        $cliente2 ="OSCAR CORO";
	        $cliente ="COOP.DE TRANSPORTE DE PASAJEROS EN TAXIS ROCHDALE";
	        $US = "2222611.49";
	        
	        $monto_letras=$cheques->numtoletras($US);
	        
	        echo $monto_letras;
	        die();
	        
	        $valorenletras ="SEICIENTOS ONCE CON 49/100";
	        $ciudad="QUITO";
	        $fecha=" 2019 abril 24";
	        $concepto="SERVICIO EN TAXIS PERS.MAR2019";
	        $formap="CHEQUE";
	        $numeroch="038141";
	        $cuenta1="1-1-02-05-00-00-00";
	        $denominacion1="Bancos e Instituciones financieras locales";
	        $cuenta2="1-1-02-05-01-00-00"; 
	        $denominacion2="Banco General Rumiñahui";
	        $debe2="0.00";
	        $haber2="611.49";
	        $Cuenta3="2-3-90-10-00-00-00";
	        $denominacion3="Proveedores";
	        $cuenta4="2-3-90-10-03-00-00";
	        $denominacion4="Valores por liquidar proveedores";
	        $debe4="611.19";
	        $haber4="0.00";
	        $total1=($debe2)+($debe4);
	        $total2=($haber2)+($haber4);
	        
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:130px;">&nbsp;</td>';
	        $html.='<td style="width:250px;font-size: 13px;">'.$cliente.'</td>';
	        $html.='<td style="width:10px;font-size: 13px;">'.$US.'</td>';
	        $html.="</tr>";
	        $html.= "<tr>";
	        $html.='<td style="width:130px;">&nbsp;</td>';
	        $html.='<td style="width:200px;font-size: 13px;">'.$valorenletras.'</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.='<td style="width:50px;font-size: 13px;">'.$ciudad.'</td>';
	        $html.='<td style="width:200px;font-size: 13px;">'.$fecha.'</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.='<table>';
	        $html.="<tr>";
	        $html.='<td style="width:70px;">&nbsp;</td>';
	        $html.='<td style="width:300px;font-size: 11px;">'.$concepto.'</td>';
	        $html.='<td style="width:80px;font-size: 11px;">'.$formap.'</td>';
	        $html.='<td style="width:110px;font-size: 11px;"><b>No.</b></td>';
	        $html.='<td style="width:10px;font-size: 11px;">'.$numeroch.'</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:70px;">&nbsp;</td>';
	        $html.='<td style="width:300px;font-size: 11px;">'.$cliente.'</td>';
	        $html.='<td style="width:80px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:110px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:10px;font-size: 11px;">'.$US.'</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        
	        $html.='<table>';
	        $html.="<tr>";
	        $html.='<td style="width:50px;">&nbsp;</td>';
	        $html.='<td style="width:300px;font-size: 11px;">'.$concepto.'; &nbsp;'.$cliente.'</td>';
	        $html.='<td style="width:80px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:70px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:10px;font-size: 11px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$cuenta1.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; ">'.$denominacion1.'</td>';
	        $html.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$cuenta2.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; ">'.$denominacion2.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$debe2.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$haber2.'</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px;">'.$concepto.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$Cuenta3.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; ">'.$denominacion3.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$cuenta4.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; ">'.$denominacion4.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$debe4.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$haber4.'</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px;">'.$concepto.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="</table>";
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";    
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;"><b>Fecha:&nbsp; &nbsp;</b>'.$fecha.'</td>';
	        $html.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;"><b>Total General:</b></td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$total1.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$total2.'</td>';
	        $html.="</tr>";
	        
	        $html.="</table>";
	        
	      
	                
	            }
	            
	            
	            
	            $this->report("Cheque",array( "resultSet"=>$html));
	            die();
	            
	        }
	        
	        
	        public function reporte_cheque(){
	            session_start();
	            
	            $tab_amortizacion= new TablaAmortizacionModel();
	            $id_creditos =  (isset($_REQUEST['id_creditos'])&& $_REQUEST['id_creditos'] !=NULL)?$_REQUEST['id_creditos']:'';
	            
	            
	            $datos_reporte = array();
	            
	            $columnas = " core_creditos.id_creditos,
                      core_tipo_creditos.codigo_tipo_creditos,
                      core_creditos.numero_creditos,
                      core_creditos.fecha_concesion_creditos,
                      core_participes.id_participes,
                      core_participes.apellido_participes,
                      core_participes.nombre_participes,
                      core_participes.cedula_participes,
                      core_participes.direccion_participes,
                      core_participes.telefono_participes,
                      core_entidad_patronal.id_entidad_patronal,
                      core_entidad_patronal.nombre_entidad_patronal,
                      core_tipo_creditos.nombre_tipo_creditos,
                      core_creditos.plazo_creditos,
                      core_estado_creditos.nombre_estado_creditos,
                      core_creditos.monto_otorgado_creditos,
                      core_creditos.saldo_actual_creditos,
                      core_creditos.monto_neto_entregado_creditos,
                      core_creditos.fecha_servidor_creditos,
                      core_creditos.interes_creditos";
	            
	            $tablas = "   public.core_tipo_creditos,
                      public.core_creditos,
                      public.core_participes,
                      public.core_estado_creditos,
                      public.core_entidad_patronal";
	            $where= "     core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos AND
                      core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos AND
                      core_participes.id_participes = core_creditos.id_participes AND
                      core_participes.id_entidad_patronal = core_entidad_patronal.id_entidad_patronal
                      AND core_creditos.id_creditos ='$id_creditos'";
	            $id="core_creditos.id_creditos";
	            
	            $rsdatos = $tab_amortizacion->getCondiciones($columnas, $tablas, $where, $id);
	            
	            $datos_reporte['CODCREDITO']=$rsdatos[0]->codigo_tipo_creditos;
	            $datos_reporte['NUMCREDITO']=$rsdatos[0]->numero_creditos;
	            $datos_reporte['FECHACONCRED']=$rsdatos[0]->fecha_concesion_creditos;
	            $datos_reporte['APELLPARTICIPE']=$rsdatos[0]->apellido_participes;
	            $datos_reporte['NOMPARICIPE']=$rsdatos[0]->nombre_participes;
	            $datos_reporte['CEDPARTICIPE']=$rsdatos[0]->cedula_participes;
	            $datos_reporte['DIRPARTICIPE']=$rsdatos[0]->direccion_participes;
	            $datos_reporte['TELEFPARTICIPE']=$rsdatos[0]->telefono_participes;
	            $datos_reporte['ENTIDADPATRON']=$rsdatos[0]->nombre_entidad_patronal;
	            $datos_reporte['TIPOPRESTAMO']=$rsdatos[0]->nombre_tipo_creditos;
	            $datos_reporte['PLAZO']=$rsdatos[0]->plazo_creditos;
	            $datos_reporte['TAZA']=$rsdatos[0]->interes_creditos;
	            $datos_reporte['ESTADO']=$rsdatos[0]->nombre_estado_creditos;
	            $datos_reporte['MONTO']=$rsdatos[0]->monto_otorgado_creditos;
	            $datos_reporte['SALDO']=$rsdatos[0]->saldo_actual_creditos;
	            $datos_reporte['MONTORECIBIR']=$rsdatos[0]->monto_neto_entregado_creditos;
	            
	            
	            
	            //////retencion detalle
	            
	            $columnas = " core_creditos.id_creditos,
                      core_tabla_amortizacion.fecha_tabla_amortizacion,
                      core_tabla_amortizacion.capital_tabla_amortizacion,
                      core_tabla_amortizacion.seguro_desgravamen_tabla_amortizacion,
                      core_tabla_amortizacion.interes_tabla_amortizacion,
                      core_tabla_amortizacion.total_valor_tabla_amortizacion,
                      core_tabla_amortizacion.mora_tabla_amortizacion,
                      core_tabla_amortizacion.balance_tabla_amortizacion,
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
                      ) as \"totalcuota\"
                    ";
	            
	            $tablas = "   public.core_creditos,
                      public.core_tabla_amortizacion,
                      public.core_estado_tabla_amortizacion";
	            $where= "   core_tabla_amortizacion.id_creditos = core_creditos.id_creditos AND
                    core_estado_tabla_amortizacion.id_estado_tabla_amortizacion = core_tabla_amortizacion.id_estado_tabla_amortizacion
                    AND core_creditos.id_creditos ='$id_creditos'";
	            $id="core_tabla_amortizacion.numero_pago_tabla_amortizacion";
	            
	            $amortizacion_detalle = $tab_amortizacion->getCondiciones($columnas, $tablas, $where, $id);
	            
	            
	            
	            
	            
	            $html='';
	            
	            
	            $html.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
	            $html.='<tr>';
	            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Fecha</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Capital</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Intereses</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Seg. Desgrav.</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Cuota</th>';
	            $html.='<th style="text-align: center; font-size: 11px;">Saldo</th>';
	            
	            $html.='</tr>';
	            
	            
	            $i=0;
	            
	            foreach ($amortizacion_detalle as $res)
	            {
	                
	                
	                $i++;
	                $html.='<tr >';
	                $html.='<td style="font-size: 11px;"align="center">'.$res->numero_pago_tabla_amortizacion.'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;">'.$res->fecha_tabla_amortizacion.'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->capital_tabla_amortizacion, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->interes_tabla_amortizacion, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->seguro_desgravamen_tabla_amortizacion, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->total_valor_tabla_amortizacion, 2, ",", ".").'</td>';
	                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->total_balance_tabla_amortizacion, 2, ",", ".").'</td>';
	                
	                
	                
	                $html.='</td>';
	                $html.='</tr>';
	                
	            }
	            
	            $html.='<tr>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Totales</th>';
	            $html.='<th style="text-align: center; font-size: 11px;"></th>';
	            $html.='<th style="font-size: 11px;" align="right">'.number_format($res->totalcapital, 2, ",", ".").'</th>';
	            $html.='<th style="font-size: 11px;" align="right">'.number_format($res->totalintereses, 2, ",", ".").'</th>';
	            $html.='<th style="font-size: 11px;" align="right">'.number_format($res->totalseguro, 2, ",", ".").'</th>';
	            $html.='<th style="font-size: 11px;" align="right">'.number_format($res->totalcuota, 2, ",", ".").'</th>';
	            $html.='<th style="text-align: center; font-size: 11px;"></th>';
	            
	            $html.='</tr>';
	            $html.='</table>';
	            
	            $datos_reporte['DETALLE_AMORTIZACION']= $html;
	            
	            
	            
	            
	            
	            $this->verReporte("ReporteCheque", array('datos_reporte'=>$datos_reporte));
	            
	            
	        }
	        
	        public function reporte_cheque_cuentas(){
	            session_start();
	            
	            try{
	                //toma de datos
	               // @$_id_comprobante =  $_POST['id_comprobante'];
	               // @$_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	                
	                @$_id_cuentas_pagar = $_GET['id_cuentas_pagar'];
	                @$_id_comprobante =  $_GET['id_comprobante'];
	                
	                //para pruebas
	                //$_id_comprobante =  68;
	                //$_id_cuentas_pagar = 3;
	                
	                if( $_id_comprobante == null || $_id_cuentas_pagar == null )
	                    throw new Exception("Parametros No Recibidos");
	                    
	            }catch (Exception $ex){
	                
	                echo $ex->getMessage();
	                die();
	                
	            }
	            
	            
	            $id_usuarios = $_SESSION["id_usuarios"];
	            //$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	            //$fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	            
	            $datos_reporte = array();
	            $CuentasPagar = new CuentasPagarModel();
	            
	            if(!empty($id_usuarios)){
	               // echo $id_usuarios; die();
	                /* DATOS CUENTAS PAGAR */
	                $colCuentas = "cp.id_cuentas_pagar, cp.descripcion_cuentas_pagar, p.nombre_proveedores, cp.total_cuentas_pagar";
	                $tabCuentas = "tes_cuentas_pagar cp
            	       INNER JOIN proveedores p
            	        ON cp.id_proveedor = p.id_proveedores
            	        INNER JOIN tes_bancos b
            	        ON b.id_bancos = cp.id_banco";
	                $wheCuentas = "1=1 AND cp.id_cuentas_pagar = $_id_cuentas_pagar ";
	                $idCuentas = "id_cuentas_pagar";
	                $rscuentasPagar = $CuentasPagar->getCondiciones($colCuentas, $tabCuentas, $wheCuentas, $idCuentas);
	                
	                $datos_reporte['portadorCheque']=$rscuentasPagar[0]->nombre_proveedores;
	                $datos_reporte['valorLetrasCheque']=$CuentasPagar->numtoletras($rscuentasPagar[0]->total_cuentas_pagar);
	                $datos_reporte['valorCheque']=number_format((float)$rscuentasPagar[0]->total_cuentas_pagar, 2, '.', ',');
	                $datos_reporte['proveedor']=$rscuentasPagar[0]->nombre_proveedores;
	               
	                $oficina = new OficinaModel();
	                $columnas = " oficina.nombre_oficina";
	                
	                $tablas = " public.oficina, public.usuarios";
	                $where= " usuarios.id_oficina = oficina.id_oficina
                    AND usuarios.id_usuarios ='$id_usuarios'";
	                $id="usuarios.id_usuarios";
	                
	                $rsoficina = $oficina->getCondiciones($columnas, $tablas, $where, $id);
	                
	                $datos_reporte['CIUDAD']=$rsoficina[0]->nombre_oficina;
	            
	            
	                $colComprobante = "id_ccomprobantes, valor_ccomprobantes, concepto_ccomprobantes, fecha_ccomprobantes, numero_cheque_ccomprobantes,
                                transaccion_ccomprobantes";
	                $tabComprobante = "public.ccomprobantes";
	                $wheComprobante = "1=1 AND id_ccomprobantes = $_id_comprobante ";
	                $idComprobante = "id_ccomprobantes";
	                $rsComprobante = $CuentasPagar->getCondiciones($colComprobante, $tabComprobante, $wheComprobante, $idComprobante);
	                
	                $datos_reporte['conceptoCheque']=$rsComprobante[0]->concepto_ccomprobantes;
	                $datos_reporte['numeroCheque']=$rsComprobante[0]->numero_cheque_ccomprobantes;
	                $datos_reporte['transaccion']=$rsComprobante[0]->transaccion_ccomprobantes;
	                $datos_reporte['fechaComprobante']=$rsComprobante[0]->fecha_ccomprobantes;
	                
	                $datos_reporte['fecha']=strtotime($rsComprobante[0]->fecha_ccomprobantes);
	                
	                $datos_reporte['fechaCheque']=date('Y',($datos_reporte['fecha'])).' '.strtoupper($meses[date('n',($datos_reporte['fecha']))-1]).' '.date('d',($datos_reporte['fecha']));
	                $datos_reporte['fechaFooter']=date('d',($datos_reporte['fecha'])).' DE '.strtoupper($meses[date('n',($datos_reporte['fecha']))-1]).' DEL '.date('Y',($datos_reporte['fecha']));
	            
	            
	                /* DATOS CONTABLES */
	                $CuentasPagar = new CuentasPagarModel();
	                $colCuentas = "dc.id_dcomprobantes, dc.id_plan_cuentas, pc.codigo_plan_cuentas, pc.nombre_plan_cuentas, dc.debe_dcomprobantes, dc.haber_dcomprobantes,
                            pc.nivel_plan_cuentas";
	                $tabCuentas = "dcomprobantes dc
            	        INNER JOIN plan_cuentas pc
            	        ON pc.id_plan_cuentas = dc.id_plan_cuentas";
	                $wheCuentas = "1=1 AND dc.id_ccomprobantes = $_id_comprobante ";
	                $idCuentas = "id_dcomprobantes";
	                $rsCuentas = $CuentasPagar->getCondiciones($colCuentas, $tabCuentas, $wheCuentas, $idCuentas);
	                
	                
	                //traer detalles contables
	                $htmlTabla = '<table class="fil1" style="width: 100%; margin-top:10px;" border=hidden cellspacing=0>';
	                if(!empty($rsCuentas)){
	                    $conceptoCheque = $rsComprobante[0]->concepto_ccomprobantes;
	                    $nivelCuenta=0;
	                    $codigoCuenta='';
	                    $codigoPadre='';
	                    $arraycodigo = array();
	                    foreach ($rsCuentas as $res){
	                        //buscar cuenta nivel mayor
	                        $codigoCuenta= $res->codigo_plan_cuentas;
	                        $codigoCuenta= trim($codigoCuenta,'.');
	                        $nivelCuenta = $res->nivel_plan_cuentas;
	                        $arraycodigo = explode('.', $codigoCuenta);
	                        for($i=0; $i < (count($arraycodigo)-1); $i++){
	                            $codigoPadre.=$arraycodigo[$i].'.';
	                        }
	                        $nivelCuenta = $nivelCuenta-1;
	                        $codigoPadre = trim($codigoPadre,'.');
	                        $queryCuentaPadre = "SELECT codigo_plan_cuentas, nombre_plan_cuentas FROM public.plan_cuentas
                            WHERE codigo_plan_cuentas LIKE '$codigoPadre%' AND nivel_plan_cuentas = '$nivelCuenta'";
	                        $rsCuentaPadre = $CuentasPagar -> enviaquery($queryCuentaPadre);
	                        
	                        if(!empty($rsCuentaPadre)){
	                            $htmlTabla.="<tr>";
	                            $htmlTabla.='<td class="13">&nbsp;</td>';
	                            $htmlTabla.='<td class="14">'.$rsCuentaPadre[0]->codigo_plan_cuentas.'</td>';
	                            $htmlTabla.='<td class="15">'.$rsCuentaPadre[0]->nombre_plan_cuentas.'</td>';
	                            $htmlTabla.='<td class="16">&nbsp;</td>';
	                            
	                            $htmlTabla.="</tr>";
	                        }
	                        
	                        $htmlTabla.="<tr>";
	                        $htmlTabla.='<td class="13">&nbsp;</td>';
	                        $htmlTabla.='<td class="15">'.$res->codigo_plan_cuentas.'</td>';
	                        $htmlTabla.='<td class="15">'.$res->nombre_plan_cuentas.'</td>';
	                        $htmlTabla.='<td class="14" align="right">'.$res->debe_dcomprobantes.'</td>';
	                        $htmlTabla.='<td class="14" align="right">'.$res->haber_dcomprobantes.'</td>';
	                        $htmlTabla.='<td class="16">&nbsp;</td>';
	                        $htmlTabla.='<td class="26">&nbsp;</td>';
	                        $htmlTabla.="</tr>";
	                        $htmlTabla.="<tr>";
	                        $htmlTabla.='<td class="13">&nbsp;</td>';
	                        $htmlTabla.='<td class="14">&nbsp;</td>';
	                        $htmlTabla.='<td class="15">'.$conceptoCheque.'</td>';
	                        $htmlTabla.='<td class="14">&nbsp;</td>';
	                        $htmlTabla.='<td class="14">&nbsp;</td>';
	                        $htmlTabla.='<td class="16">&nbsp;</td>';
	                        $htmlTabla.='<td class="26">&nbsp;</td>';
	                        
	                        $htmlTabla.="</tr>";
	                        
	                        
	                        
	                        $codigoPadre='';
	                    }
	                }
	                $htmlTabla .= '</table>';
	                
	                
	        
	            
	            
	            $datos_reporte['DETALLE_CUENTA_X_PAGAR']= $htmlTabla;
	            
	            
	            
	            }
	            
	            $this->verReporte("ReporteCheque", array('datos_reporte'=>$datos_reporte));
	            
	            
	        }
	        

	
	
}
?>