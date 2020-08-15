<?php
class ReporteNominaController extends ControladorBase{
    public function index(){
        session_start();
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $reportenomina  = new ReporteNominaEmpleadosModel();
       
        $columnas="DISTINCT substring( periodo_registro from 16 for 4) as anio";
        $tablas="public.reporte_nomina_empleados";
        $where="1=1";
        $id="anio";
        $resultAnios=$reportenomina->getCondiciones($columnas, $tablas, $where, $id);
        
        $this->view_Administracion("ReporteNomina",array(
            "resultAnios"=> $resultAnios           
        ));
    }
    
   
    
   
    
    public function FormatoFecha($fecha)
    {
     $datos= explode("/", $fecha);
     if($datos[1]<10)
     {
         $datos[1]="0".$datos[1];
     }
     return $datos[2]."-".$datos[1]."-".$datos[0];
    }
    
    public function ActualizarRegistros()
    {
        session_start();
        $reportenomina  = new ReporteNominaEmpleadosModel();
        $salario=$_POST['salario'];
        $id_empleado=$_POST['id_empleado'];
        $horasextra50=$_POST['h50'];
        $horasextra100=$_POST['h100'];
        $fondosreserva=$_POST['reserva'];
        $sueldo14=$_POST['decimo_cuarto'];
        $sueldo13=$_POST['decimo_tercero'];
        $dctoavance=$_POST['anticipo_sueldo'];
        $aporteiess1=$_POST['aporte_iess'];
        $asocap=$_POST['asocap'];
        $quiroiess=$_POST['quiro_iess'];
        $hipoiess=$_POST['hipo_iess'];
        $dctosalario=$_POST['dcto_sueldo'];
        $periodo=$_POST['periodo'];
        $sociales=$_POST['sociales'];
        $funcion = "ins_reporte_nomina_empleado";
        $parametros = "'$id_empleado',
                                '$horasextra50',
                                '$horasextra100',
                                '$fondosreserva',
                                '$sueldo14',
                                '$sueldo13',
                                '$dctoavance',
                                '$aporteiess1',
                                '$asocap',
                                '$quiroiess',
                                '$hipoiess',
                                '$dctosalario',
                                '$sociales',                
                                '$periodo'";
        $reportenomina->setFuncion($funcion);
        $reportenomina->setParametros($parametros);
        $resultado=$reportenomina->Insert();
    }
    
    public function TotalesReporte($resultSet, $resultDSE, $coloringresos1, $coloringresos2, $colorInfo1, $colorInfo2, $coloregresos1, $coloregresos2)
    {
        
        $totalsalarios=0;
        $totalh50=0;
        $totalh10=0;
        $totalreservas=0;
        $total14=0;
        $total13=0;
        $totalingresos=0;
        $totalanticipo=0;
        $totaliess=0;
        $totalAsocap=0;
        $totalsociales=0;
        $totalqiess=0;
        $totalhiess=0;
        $totaldctos=0;
        $totalegresos=0;
        $totalapagar=0;
        
        foreach ($resultSet as $res)
        {
            
            $freserva=$res->fondos_reserva;
            $totalreservas+=$freserva;
            $totaleg=$res->salario_cargo+$res->horas_ext50+$res->horas_ext100+$freserva+$res->dec_cuarto_sueldo+$res->dec_tercero_sueldo;
            $totalegresos+=$totaleg;
            $totaling=$res->anticipo_sueldo+$res->aporte_iess1+$res->asocap+$res->comision_asuntos_sociales+$res->prest_quirog_iess+$res->prest_hipot_iess+$res->dcto_salario;
            $totalingresos+=$totaling;
            $total=$totaleg-$totaling;
            $totalapagar+=$total;
 
            $totalsalarios+=$res->salario_cargo;
            $totalh50+=$res->horas_ext50;
            $totalh10+=$res->horas_ext100;
            $total14+=$res->dec_cuarto_sueldo;
            $total13+=$res->dec_tercero_sueldo;
            $totalanticipo+=$res->anticipo_sueldo;
            $totaliess+=$res->aporte_iess1;
            $totalAsocap+=$res->asocap;
            $totalsociales+=$res->comision_asuntos_sociales;
            $totalqiess+=$res->prest_quirog_iess;
            $totalhiess+=$res->prest_hipot_iess;
            $totaldctos+=$res->dcto_salario;
           }
           
           
           /*<div class="col-md-4">
           <p class="text-center">
           <strong>Goal Completion</strong>
           </p>
           
           
           
           <div class="progress sm">
           <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
           </div>
           </div>
           <!-- /.progress-group -->
           <div class="progress-group">
           <span class="progress-text">Complete Purchase</span>
           <span class="progress-number"><b>310</b>/400</span>
           
           <div class="progress sm">
           <div class="progress-bar progress-bar-red" style="width: 80%"></div>
           </div>
           </div>
           <!-- /.progress-group -->
           <div class="progress-group">
           <span class="progress-text">Visit Premium Page</span>
           <span class="progress-number"><b>480</b>/800</span>
           
           <div class="progress sm">
           <div class="progress-bar progress-bar-green" style="width: 80%"></div>
           </div>
           </div>
           <!-- /.progress-group -->
           <div class="progress-group">
           <span class="progress-text">Send Inquiries</span>
           <span class="progress-number"><b>250</b>/500</span>
           
           <div class="progress sm">
           <div class="progress-bar progress-bar-yellow" style="width: 80%"></div>
           </div>
           </div>
           <!-- /.progress-group -->
           </div>*/
           $porcentaje_egresos_salario=($totalsalarios*100)/$totalegresos;
           $porcentaje_egresos_h50=($totalh50*100)/$totalegresos;
           $porcentaje_egresos_h10=($totalh10*100)/$totalegresos;
           $porcentaje_egresos_fr=($totalreservas*100)/$totalegresos;
           $porcentaje_egresos_13=($total13*100)/$totalegresos;
           $porcentaje_egresos_14=($total14*100)/$totalegresos;
           $totalegresos=number_format((float)$totalegresos, 2, ',', '.');
           $totalsalarios=number_format((float)$totalsalarios, 2, ',', '.');
        $html='<div class="col-lg-12 col-md-12 col-xs-12">';
        $html.='<div class="box-body bg-silver">';
        $html.='<div class="col-lg-6 col-md-6 col-xs-6 ">';
        $html.= '<h3 align="center">
           <strong>Total Gastos: '.$totalegresos.'$</strong>
           </h3>
            <div class="progress-group">
           <span class="progress-text">Salarios</span>
           <span class="progress-number"><b>'.$totalsalarios.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
         $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_salario.'%"></div>
           </div>
           </div>';
        $totalh50=number_format((float)$totalh50, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">Horas Extra 50%</span>
           <span class="progress-number"><b>'.$totalh50.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_h50.'%"></div>
           </div>
           </div>';
        
        $totalh10=number_format((float)$totalh10, 2, ',', '.');
            
        $html.= '<div class="progress-group">
           <span class="progress-text">Horas Extra 100%</span>
           <span class="progress-number"><b>'.$totalh10.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_h10.'%"></div>
           </div>
           </div>';
        
        $totalreservas=number_format((float)$totalreservas, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">Fondos de reserva</span>
           <span class="progress-number"><b>'.$totalreservas.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_fr.'%"></div>
           </div>
           </div>';
        
        $total13=number_format((float)$total13, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">13ro Sueldo</span>
           <span class="progress-number"><b>'.$total13.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_13.'%"></div>
           </div>
           </div>';
        $total14=number_format((float)$total14, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">14to Sueldo</span>
           <span class="progress-number"><b>'.$total14.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_14.'%"></div>
           </div>
           </div>';
       
        $html.='</div>';
        
        $porcentaje_ingresos_anticipo=($totalanticipo*100)/$totalingresos;
        $porcentaje_ingresos_Apiess=($totaliess*100)/$totalingresos;
        $porcentaje_ingresos_ASOCAP=($totalAsocap*100)/$totalingresos;
        $porcentaje_ingresos_sociales=($totalsociales*100)/$totalingresos;
        $porcentaje_ingresos_quiro=($totalqiess*100)/$totalingresos;
        $porcentaje_ingresos_hipo=($totalhiess*100)/$totalingresos;
        $porcentaje_ingresos_dctos=($totaldctos*100)/$totalingresos;
       
        $totalingresos=number_format((float)$totalingresos, 2, ',', '.');
        $totalanticipo=number_format((float)$totalanticipo, 2, ',', '.');
        
        $html.='<div class="col-lg-6 col-md-6 col-xs-6">';
        $html.= '<h3 align="center">
           <strong>Total Cuentas por pagar a terceros: '.$totalingresos.'$</strong>
           </h3>
            <div class="progress-group">
           <span class="progress-text">Anticipo de sueldo</span>
           <span class="progress-number"><b>'.$totalanticipo.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_anticipo.'%"></div>
           </div>
           </div>';
        $totaliess=number_format((float)$totaliess, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">Aporte IESS</span>
           <span class="progress-number"><b>'.$totaliess.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_Apiess.'%"></div>
           </div>
           </div>';
        
        $totalAsocap=number_format((float)$totalAsocap, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">ASOCAP</span>
           <span class="progress-number"><b>'.$totalAsocap.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_ASOCAP.'%"></div>
           </div>
           </div>';
        
        $totalsociales=number_format((float)$totalsociales, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">Comision Asuntos sociales</span>
           <span class="progress-number"><b>'.$totalsociales.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_sociales.'%"></div>
           </div>
           </div>';
        
        $totalqiess=number_format((float)$totalqiess, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">PREST.QUROG. IESS</span>
           <span class="progress-number"><b>'.$totalqiess.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_quiro.'%"></div>
           </div>
           </div>';
        $totalhiess=number_format((float)$totalhiess, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">PREST. HIPOT. IESS</span>
           <span class="progress-number"><b>'.$totalhiess.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_hipo.'%"></div>
           </div>
           </div>';
        
        $totaldctos=number_format((float)$totaldctos, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">Dcto salario</span>
           <span class="progress-number"><b>'.$totaldctos.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_dctos.'%"></div>
           </div>
           </div>';
        
        $html.='</div>';
        
        $totalapagar=number_format((float)$totalapagar, 2, ',', '.');
        $html.='<h3 align="center">
        <strong>A Pagar: '.$totalapagar.'$</strong>
        </h3>';
        $html.='</div>';
        $html.='</div>';
        
        return $html;
    }
    
    public function GetReporte()
    {
        session_start();
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $reporte_nomina = new ReporteNominaEmpleadosModel();
        $mes_inicio=0;
        $mes_fin=0;
       
        $periodo=$_POST['periodo'];
        
        $hoy = getdate();
        
        $dia_hoy=$hoy['mday'];
        $mes=$hoy['mon'];        
        $anio=$hoy['year'];
        $anio_inicio=$anio;
        $anio_fin=$anio;
        
        if($dia_hoy<=21)
        {
            $mes_inicio=$mes-2;
            $mes_fin=$mes-1;
            if ($mes_inicio<1)
            {
                $mes_inicio=12;
                $anio_inicio=$anio-1;
            }
        }
        else
        {
            $mes_inicio=$mes;
            $mes_fin=$mes+1;
            if ($mes_fin==13)
            {
                $mes_fin=1;
                $anio_fin=$anio+1;
            }
        }
        
                     
        
        
        $periodoactual='22/'.$mes_inicio.'/'.$anio_inicio.'-21/'.$mes_fin.'/'.$anio_fin;
        $tablas = "public.descuentos_salarios_empleados";
        $where = "1=1";
        
        $id = "descuentos_salarios_empleados.id_descuento";
        
        $resultDSE= $reporte_nomina->getCondiciones("*", $tablas, $where, $id);
        
        $columnas=    "empleados.nombres_empleados, oficina.nombre_oficina, cargos_empleados.salario_cargo,
                	   reporte_nomina_empleados.horas_ext50, reporte_nomina_empleados.horas_ext100,
                	   reporte_nomina_empleados.fondos_reserva, reporte_nomina_empleados.dec_cuarto_sueldo,
                	   reporte_nomina_empleados.dec_tercero_sueldo, reporte_nomina_empleados.anticipo_sueldo,
                	   reporte_nomina_empleados.aporte_iess1, reporte_nomina_empleados.asocap,
                	   reporte_nomina_empleados.prest_quirog_iess, reporte_nomina_empleados.prest_hipot_iess,
                	   reporte_nomina_empleados.dcto_salario, reporte_nomina_empleados.periodo_registro,
                       reporte_nomina_empleados.comision_asuntos_sociales,
                       empleados.id_empleados,reporte_nomina_empleados.id_registro";
        
        $tablas= "public.reporte_nomina_empleados INNER JOIN public.empleados
            	   ON reporte_nomina_empleados.id_empleado = empleados.id_empleados
            	   INNER JOIN public.oficina
            	   ON empleados.id_oficina = oficina.id_oficina
            	   INNER JOIN public.cargos_empleados
            	   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        
        
        $where="reporte_nomina_empleados.periodo_registro='".$periodo."'";
        
        $id="reporte_nomina_empleados.id_registro";
        
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if(!empty($search)){
            
            
            $where1=" AND (empleados.nombres_empleados ILIKE '".$search."%' OR oficina.nombre_oficina ILIKE '".$search."%'
            OR reporte_nomina_empleados.periodo_registro ILIKE '%".$search."')";
            
            $where.=$where1;
        }
        
        $resultSetT = $reporte_nomina->getCondiciones($columnas, $tablas, $where, $id);
        
        
        $cantidadResult=sizeof($resultSetT);
        
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $coloringresos1="#F5B041";
        
        $coloringresos2="#FAD7A0";
        
        $colorInfo1="#A8CEF6";
        
        $colorInfo2="#ADD8E6";
        
        $coloregresos1="#F1C40F";
        
        $coloregresos2="#F9E79F";
        
        
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$reporte_nomina->getCondicionesPag("*", $tablas, $where, $id, $limit);
        $total_pages = ceil($cantidadResult/$per_page);
        
        
        
        $html="";
        
        if (!(empty($resultSet)))
        {
            $totales=$this->TotalesReporte($resultSetT, $resultDSE, $coloringresos1, $coloringresos2, $colorInfo1, $colorInfo2, $coloregresos1, $coloregresos2);
            $html.='<div class="pull-left" style="margin-left:15px;">';
            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>';
            $html.='</div>';
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<section style="height:425px; overflow-y:scroll;">';
            $html.= "<table id='tabla_reporte' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.='<tr>';
            $html.='<th colspan="2"  style="text-align: left;  font-size: 14px;"></th>';
            $html.='<th  style="text-align: left;  font-size: 14px;">Empleado</th>';
            $html.='<th  style="text-align: left;  font-size: 14px;">Oficina</th>';
            $html.='<th  style="text-align: left;  font-size: 14px;">Salario</th>';
            $html.='<th style="text-align: left;  font-size: 14px;">A Pagar</th>';
            $html.='<th style="text-align: left;  font-size: 14px;">Periodo</th>';
            $html.='<th style="text-align: left;  font-size: 14px;"></th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $i=0;
            
            foreach ($resultSet as $res)
            {
                $i++;
                $html.='<tr>';
                $html.='<td style="font-size: 15px;">'.$i.'</td>';
                $html.='<td  style="font-size: 15px;">
       <button type="button" id="Print" name="Print" class="btn btn-primary" onclick="ImprimirReporteIndividual('.$res->id_registro.')"><i class="glyphicon glyphicon-print"></i></button>';
                if($periodo==$periodoactual)
                {
                    $html.='<button  type="button" class="btn btn-success" onclick="';
                    $html.='EditarNomina(&quot;'.$res->nombres_empleados.'&quot,&quot;'.$res->nombre_oficina.'&quot,&quot;'.$res->salario_cargo.'&quot;,&quot;'.$res->horas_ext50.'&quot;';
                    $html.=',&quot;'.$res->horas_ext100.'&quot;,&quot;'.$res->fondos_reserva.'&quot;,&quot;'.$res->dec_cuarto_sueldo.'&quot;';
                    $html.=',&quot;'.$res->dec_tercero_sueldo.'&quot;,&quot;'.$res->anticipo_sueldo.'&quot;,&quot;'.$res->aporte_iess1.'&quot;';
                    $html.=',&quot;'.$res->asocap.'&quot;,&quot;'.$res->comision_asuntos_sociales.'&quot;,&quot;'.$res->prest_quirog_iess.'&quot;,&quot;'.$res->prest_hipot_iess.'&quot;';
                    $html.=',&quot;'.$res->dcto_salario.'&quot;,&quot;'.$res->periodo_registro.'&quot;,'.$res->id_empleados.')';
                    $html.='"><i class="glyphicon glyphicon-edit"></i></button>';
                }
                $html.='</td>';
                $freserva=$res->fondos_reserva;
                $totaling=$res->salario_cargo+$res->horas_ext50+$res->horas_ext100+$freserva+$res->dec_cuarto_sueldo+$res->dec_tercero_sueldo;
                $totaleg=$res->anticipo_sueldo+$res->aporte_iess1+$res->asocap+$resultDSE[0]->asuntos_sociales+$res->prest_quirog_iess+$res->prest_hipot_iess+$res->dcto_salario;
                $total=$totaling-$totaleg;
                $html.='<td  style="font-size: 15px;">'.$res->nombres_empleados.'</td>';
                $html.='<td  style="font-size: 15px;">'.$res->nombre_oficina.'</td>';
                $res->salario_cargo=number_format((float)$res->salario_cargo, 2, ',', '.');
                $html.='<td  style="font-size: 15px;" align="right">'.$res->salario_cargo.'</td>';
                $total=number_format((float)$total, 2, ',', '.');
                $html.='<td  style="font-size: 15px;" align="right">'.$total.'</td>';
                $elementos=explode("/", $res->periodo_registro);
                $periodonomina=$meses[($elementos[3]-1)]." ".$elementos[4];
                $html.='<td style="font-size: 15px;">'.$periodonomina.'</td>';
                $html.='<td  style="font-size: 15px;">
                        <button type="button" id="Print" name="Print" class="btn btn-default" onclick="VerReporteIndividual('.$res->id_registro.')"><i class="glyphicon glyphicon-eye-open"></i></button>
                         </td>';
                $html.='</tr>';
                
                
            }
            
            $html.='</tbody>';
            $html.='</table>';
            
            
            $html.='</section></div>';
            
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate_reporte("index.php", $page, $total_pages, $adjacents,"ReporteNomina").'';
            $html.='</div>';
            $html.=$totales;
            $html.='<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                
            	 <button type="button" id="Print" name="Print" class="btn btn-primary" onclick="ImprimirReporte()"><i class="glyphicon glyphicon-print"></i>  Reporte</button>
                </div>
             </div>
            </div>';
            
            
        }
        else {
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registros de reloj para el periodo actual...</b>';
            $html.='</div>';
            $html.='</div>';
        }
        echo $html;
    }
    
    public function VerReporteIndividual()
    {
        session_start();
        $reporte_nomina = new ReporteNominaEmpleadosModel();
        
        $totalsalarios=0;
        $totalh50=0;
        $totalh10=0;
        $totalreservas=0;
        $total14=0;
        $total13=0;
        $totalingresos=0;
        $totalanticipo=0;
        $totaliess=0;
        $totalAsocap=0;
        $totalsociales=0;
        $totalqiess=0;
        $totalhiess=0;
        $totaldctos=0;
        $totalegresos=0;
        $totalapagar=0;
        $id_registro=$_POST['id_registro'];
     
        
        $columnas=    "empleados.nombres_empleados,empleados.numero_cedula_empleados,
                        oficina.nombre_oficina, cargos_empleados.salario_cargo, cargos_empleados.nombre_cargo,
                	   reporte_nomina_empleados.horas_ext50, reporte_nomina_empleados.horas_ext100,
                	   reporte_nomina_empleados.fondos_reserva, reporte_nomina_empleados.dec_cuarto_sueldo,
                	   reporte_nomina_empleados.dec_tercero_sueldo, reporte_nomina_empleados.anticipo_sueldo,
                	   reporte_nomina_empleados.aporte_iess1, reporte_nomina_empleados.asocap,
                	   reporte_nomina_empleados.prest_quirog_iess, reporte_nomina_empleados.prest_hipot_iess,
                	   reporte_nomina_empleados.dcto_salario, reporte_nomina_empleados.periodo_registro,
                        reporte_nomina_empleados.comision_asuntos_sociales,
                       empleados.id_empleados, departamentos.nombre_departamento";
        
        $tablas= "public.reporte_nomina_empleados INNER JOIN public.empleados
            	   ON reporte_nomina_empleados.id_empleado = empleados.id_empleados
            	   INNER JOIN public.oficina
            	   ON empleados.id_oficina = oficina.id_oficina
            	   INNER JOIN public.cargos_empleados
            	   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo
                   INNER JOIN public.departamentos
                   ON cargos_empleados.id_departamento = departamentos.id_departamento";
        
        $where="reporte_nomina_empleados.id_registro='".$id_registro."'";
        
        $id="reporte_nomina_empleados.id_registro";
        
        $resultSet = $reporte_nomina->getCondiciones($columnas, $tablas, $where, $id);
        
        foreach ($resultSet as $res)
        {
            
            $freserva=$res->fondos_reserva;
            $totalreservas+=$freserva;
            $totaleg=$res->salario_cargo+$res->horas_ext50+$res->horas_ext100+$freserva+$res->dec_cuarto_sueldo+$res->dec_tercero_sueldo;
            $totalegresos+=$totaleg;
            $totaling=$res->anticipo_sueldo+$res->aporte_iess1+$res->asocap+$res->comision_asuntos_sociales+$res->prest_quirog_iess+$res->prest_hipot_iess+$res->dcto_salario;
            $totalingresos+=$totaling;
            $total=$totaleg-$totaling;
            $totalapagar+=$total;
            
            $totalsalarios+=$res->salario_cargo;
            $totalh50+=$res->horas_ext50;
            $totalh10+=$res->horas_ext100;
            $total14+=$res->dec_cuarto_sueldo;
            $total13+=$res->dec_tercero_sueldo;
            $totalanticipo+=$res->anticipo_sueldo;
            $totaliess+=$res->aporte_iess1;
            $totalAsocap+=$res->asocap;
            $totalsociales+=$res->comision_asuntos_sociales;
            $totalqiess+=$res->prest_quirog_iess;
            $totalhiess+=$res->prest_hipot_iess;
            $totaldctos+=$res->dcto_salario;
        }
        
        $porcentaje_egresos_salario=($totalsalarios*100)/$totalegresos;
        $porcentaje_egresos_h50=($totalh50*100)/$totalegresos;
        $porcentaje_egresos_h10=($totalh10*100)/$totalegresos;
        $porcentaje_egresos_fr=($totalreservas*100)/$totalegresos;
        $porcentaje_egresos_13=($total13*100)/$totalegresos;
        $porcentaje_egresos_14=($total14*100)/$totalegresos;
        $totalegresos=number_format((float)$totalegresos, 2, ',', '.');
        $totalsalarios=number_format((float)$totalsalarios, 2, ',', '.');
        $html='';
        $html.='<div class="box-body bg-silver">';
        $html.= '<h3 align="center">
           <strong>'.$res->nombres_empleados.'</strong>
           </h3>';
        $html.='<div class="col-lg-6 col-md-6 col-xs-6 bg-silver">';
        $html.= '<h3 align="center">
           <strong>Total Ingresos empleado: '.$totalegresos.'$</strong>
           </h3>
            <div class="progress-group">
           <span class="progress-text">Salario</span>
           <span class="progress-number"><b>'.$totalsalarios.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_salario.'%"></div>
           </div>
           </div>';
        $totalh50=number_format((float)$totalh50, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">Horas Extra 50%</span>
           <span class="progress-number"><b>'.$totalh50.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_h50.'%"></div>
           </div>
           </div>';
        
        $totalh10=number_format((float)$totalh10, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">Horas Extra 100%</span>
           <span class="progress-number"><b>'.$totalh10.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_h10.'%"></div>
           </div>
           </div>';
        
        $totalreservas=number_format((float)$totalreservas, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">Fondos de reserva</span>
           <span class="progress-number"><b>'.$totalreservas.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_fr.'%"></div>
           </div>
           </div>';
        
        $total13=number_format((float)$total13, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">13ro Sueldo</span>
           <span class="progress-number"><b>'.$total13.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_13.'%"></div>
           </div>
           </div>';
        $total14=number_format((float)$total14, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">14to Sueldo</span>
           <span class="progress-number"><b>'.$total14.'</b>/'.$totalegresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-orange" style="width: '.$porcentaje_egresos_14.'%"></div>
           </div>
           </div>';
        
        $html.='</div>';
        
        $porcentaje_ingresos_anticipo=($totalanticipo*100)/$totalingresos;
        $porcentaje_ingresos_Apiess=($totaliess*100)/$totalingresos;
        $porcentaje_ingresos_ASOCAP=($totalAsocap*100)/$totalingresos;
        $porcentaje_ingresos_sociales=($totalsociales*100)/$totalingresos;
        $porcentaje_ingresos_quiro=($totalqiess*100)/$totalingresos;
        $porcentaje_ingresos_hipo=($totalhiess*100)/$totalingresos;
        $porcentaje_ingresos_dctos=($totaldctos*100)/$totalingresos;
        
        $totalingresos=number_format((float)$totalingresos, 2, ',', '.');
        $totalanticipo=number_format((float)$totalanticipo, 2, ',', '.');
        
        $html.='<div class="col-lg-6 col-md-6 col-xs-6">';
        $html.= '<h3 align="center">
           <strong>Total Egresos empleado: '.$totalingresos.'$</strong>
           </h3>
            <div class="progress-group">
           <span class="progress-text">Anticipo de sueldo</span>
           <span class="progress-number"><b>'.$totalanticipo.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_anticipo.'%"></div>
           </div>
           </div>';
        $totaliess=number_format((float)$totaliess, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">Aporte IESS</span>
           <span class="progress-number"><b>'.$totaliess.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_Apiess.'%"></div>
           </div>
           </div>';
        
        $totalAsocap=number_format((float)$totalAsocap, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">ASOCAP</span>
           <span class="progress-number"><b>'.$totalAsocap.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_ASOCAP.'%"></div>
           </div>
           </div>';
        
        $totalsociales=number_format((float)$totalsociales, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">Comision Asuntos sociales</span>
           <span class="progress-number"><b>'.$totalsociales.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_sociales.'%"></div>
           </div>
           </div>';
        
        $totalqiess=number_format((float)$totalqiess, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">PREST.QUROG. IESS</span>
           <span class="progress-number"><b>'.$totalqiess.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_quiro.'%"></div>
           </div>
           </div>';
        $totalhiess=number_format((float)$totalhiess, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">PREST. HIPOT. IESS</span>
           <span class="progress-number"><b>'.$totalhiess.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_hipo.'%"></div>
           </div>
           </div>';
        
        $totaldctos=number_format((float)$totaldctos, 2, ',', '.');
        
        $html.= '<div class="progress-group">
           <span class="progress-text">Dcto salario</span>
           <span class="progress-number"><b>'.$totaldctos.'</b>/'.$totalingresos.'</span>
           <div class="progress sm">';
        
        $html.= ' <div class="progress-bar bg-yellow" style="width: '.$porcentaje_ingresos_dctos.'%"></div>
           </div>
           </div>';
        
        $html.='</div>';
        
        $totalapagar=number_format((float)$totalapagar, 2, ',', '.');
        $html.='<h3 align="center">
        <strong>A Pagar: '.$totalapagar.'$</strong>
        </h3>';
        $html.='</div>';
            
        echo $html;
    }
    
    /*public function GetReporte()
    {
        session_start();
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $reporte_nomina = new ReporteNominaEmpleadosModel();
        
        $periodo=$_POST['periodo'];
        
        $hoy = getdate();
        
        $mes=$hoy['mon'];
        
        $anio=$hoy['year'];
        
        $anioinicio=$hoy['year'];
        
        $mesinicio=$mes-2;
        
        if ($mesinicio<1)
        {
            $mesinicio=12;
            $anioinicio--;
        }
        $mes--;
        
        $periodoactual='22/'.$mesinicio.'/'.$anioinicio.'-21/'.$mes.'/'.$anio;
        $tablas = "public.descuentos_salarios_empleados";
        $where = "1=1";
        
        $id = "descuentos_salarios_empleados.id_descuento";
        
        $resultDSE= $reporte_nomina->getCondiciones("*", $tablas, $where, $id);
        
        $columnas=    "empleados.nombres_empleados, oficina.nombre_oficina, cargos_empleados.salario_cargo,
                	   reporte_nomina_empleados.horas_ext50, reporte_nomina_empleados.horas_ext100,
                	   reporte_nomina_empleados.fondos_reserva, reporte_nomina_empleados.dec_cuarto_sueldo,
                	   reporte_nomina_empleados.dec_tercero_sueldo, reporte_nomina_empleados.anticipo_sueldo,
                	   reporte_nomina_empleados.aporte_iess1, reporte_nomina_empleados.asocap,
                	   reporte_nomina_empleados.prest_quirog_iess, reporte_nomina_empleados.prest_hipot_iess,
                	   reporte_nomina_empleados.dcto_salario, reporte_nomina_empleados.periodo_registro,
                       reporte_nomina_empleados.comision_asuntos_sociales,
                       empleados.id_empleados,reporte_nomina_empleados.id_registro";
        
        $tablas= "public.reporte_nomina_empleados INNER JOIN public.empleados
            	   ON reporte_nomina_empleados.id_empleado = empleados.id_empleados
            	   INNER JOIN public.oficina
            	   ON empleados.id_oficina = oficina.id_oficina
            	   INNER JOIN public.cargos_empleados
            	   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
       
        
        $where="reporte_nomina_empleados.periodo_registro='".$periodo."'";
        
        $id="reporte_nomina_empleados.id_registro";
        
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if(!empty($search)){
            
            
            $where1=" AND (empleados.nombres_empleados ILIKE '".$search."%' OR oficina.nombre_oficina ILIKE '".$search."%'
            OR reporte_nomina_empleados.periodo_registro ILIKE '%".$search."')";
            
            $where.=$where1;
        }
        
        $resultSetT = $reporte_nomina->getCondiciones($columnas, $tablas, $where, $id);
        
        
        $cantidadResult=sizeof($resultSetT);
        
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $coloringresos1="#F5B041";
        
        $coloringresos2="#FAD7A0";
        
        $colorInfo1="#A8CEF6";
        
        $colorInfo2="#ADD8E6";
        
        $coloregresos1="#F1C40F";
        
        $coloregresos2="#F9E79F";
        
        
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$reporte_nomina->getCondicionesPag("*", $tablas, $where, $id, $limit);
        $total_pages = ceil($cantidadResult/$per_page);
        
        
        
        $html="";
        
        if (!(empty($resultSet)))
        {
            $totales=$this->TotalesReporte($resultSetT, $resultDSE, $coloringresos1, $coloringresos2, $colorInfo1, $colorInfo2, $coloregresos1, $coloregresos2);
            $html.='<div class="pull-left" style="margin-left:15px;">';
            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>';            
            $html.='</div>';
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<section style="height:425px; overflow-y:scroll;">';
            $html.= "<table id='tabla_reporte' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";            
            $html.='<tr>';
              $html.='<td rowspan="2"></td>';
              $html.='<th colspan="3" bgcolor="'.$colorInfo1.'" scope="colgroup">Informacion Empleado</th>';
              $html.='<th colspan="7" bgcolor="'.$coloringresos1.'" scope="colgroup">Gastos</th>';
             $html.=' <th colspan="8" bgcolor="'.$coloregresos1.'" scope="colgroup">Cuentas por pagar a terceros</th>';
             $html.='</tr>';
             $html.='<tr>';
             $html.='<th bgcolor="'.$colorInfo1.'" style="text-align: left;  font-size: 14px;"></th>';
             $html.='<th bgcolor="'.$colorInfo1.'" style="text-align: left;  font-size: 14px;">Empleado</th>';
             $html.='<th bgcolor="'.$colorInfo1.'" style="text-align: left;  font-size: 14px;">Oficina</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">Salario</th>';             
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">Horas Extra 50%</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">Horas Extra 100%</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">Fondos de reserva</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">14to Sueldo</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">13ro Sueldo</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">Total</th>';
             $html.='<th bgcolor="'.$coloregresos1.'" style="text-align: left;  font-size: 14px;">Anticipo de sueldo</th>';
             $html.='<th bgcolor="'.$coloregresos1.'" style="text-align: left;  font-size: 14px;">Aporte IESS '.$resultDSE[0]->descuento_iess1.'%</th>';
             $html.='<th bgcolor="'.$coloregresos1.'" style="text-align: left;  font-size: 14px;">ASOCAP</th>';
             $html.='<th bgcolor="'.$coloregresos1.'" style="text-align: left;  font-size: 14px;">Comision Asuntos sociales</th>';
             $html.='<th bgcolor="'.$coloregresos1.'" style="text-align: left;  font-size: 14px;">PREST.QUROG. IESS</th>';
             $html.='<th bgcolor="'.$coloregresos1.'" style="text-align: left;  font-size: 14px;">PREST. HIPOT. IESS</th>';
            $html.='<th bgcolor="'.$coloregresos1.'" style="text-align: left;  font-size: 14px;">Dcto salario</th>';
            $html.='<th bgcolor="'.$coloregresos1.'" style="text-align: left;  font-size: 14px;">Total</th>';
            $html.='<th style="text-align: left;  font-size: 14px;">A Pagar</th>';
            $html.='<th style="text-align: left;  font-size: 14px;">Periodo</th>';
            
          
            
            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $i=0;
        
       foreach ($resultSet as $res)
       {
       $i++;
       $html.='<tr>';
       $html.='<td style="font-size: 15px;">'.$i.'</td>';
       $html.='<td bgcolor="'.$colorInfo2.'" style="font-size: 15px;">
       <button type="button" id="Print" name="Print" class="btn btn-primary" onclick="ImprimirReporteIndividual('.$res->id_registro.')"><i class="glyphicon glyphicon-print"></i></button>';
        if($periodo==$periodoactual)
        {
       $html.='<button  type="button" class="btn btn-success" onclick="';
       $html.='EditarNomina(&quot;'.$res->nombres_empleados.'&quot,&quot;'.$res->nombre_oficina.'&quot,&quot;'.$res->salario_cargo.'&quot;,&quot;'.$res->horas_ext50.'&quot;';
       $html.=',&quot;'.$res->horas_ext100.'&quot;,&quot;'.$res->fondos_reserva.'&quot;,&quot;'.$res->dec_cuarto_sueldo.'&quot;';
       $html.=',&quot;'.$res->dec_tercero_sueldo.'&quot;,&quot;'.$res->anticipo_sueldo.'&quot;,&quot;'.$res->aporte_iess1.'&quot;';
       $html.=',&quot;'.$res->asocap.'&quot;,&quot;'.$res->comision_asuntos_sociales.'&quot;,&quot;'.$res->prest_quirog_iess.'&quot;,&quot;'.$res->prest_hipot_iess.'&quot;';
       $html.=',&quot;'.$res->dcto_salario.'&quot;,&quot;'.$res->periodo_registro.'&quot;,'.$res->id_empleados.')';
       $html.='"><i class="glyphicon glyphicon-edit"></i></button>';
        }
        $html.='</td>';
        $freserva=$res->fondos_reserva;
        $totaling=$res->salario_cargo+$res->horas_ext50+$res->horas_ext100+$freserva+$res->dec_cuarto_sueldo+$res->dec_tercero_sueldo;
        $totaleg=$res->anticipo_sueldo+$res->aporte_iess1+$res->asocap+$resultDSE[0]->asuntos_sociales+$res->prest_quirog_iess+$res->prest_hipot_iess+$res->dcto_salario;
        $total=$totaling-$totaleg;
       $html.='<td bgcolor="'.$colorInfo2.'" style="font-size: 15px;">'.$res->nombres_empleados.'</td>';
       $html.='<td bgcolor="'.$colorInfo2.'" style="font-size: 15px;">'.$res->nombre_oficina.'</td>';
       $res->salario_cargo=number_format((float)$res->salario_cargo, 2, ',', '.');
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;" align="right">'.$res->salario_cargo.'</td>';
       $res->horas_ext50=number_format((float)$res->horas_ext50, 2, ',', '.');
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;" align="right">'.$res->horas_ext50.'</td>';
       $res->horas_ext100=number_format((float)$res->horas_ext100, 2, ',', '.');
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;" align="right">'.$res->horas_ext100.'</td>';
       
       $freserva=number_format((float)$freserva, 2, ',', '.');
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;" align="right">'.$freserva.'</td>';
       $res->dec_cuarto_sueldo=number_format((float)$res->dec_cuarto_sueldo, 2, ',', '.');
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;" align="right">'.$res->dec_cuarto_sueldo.'</td>';
       $res->dec_tercero_sueldo=number_format((float)$res->dec_tercero_sueldo, 2, ',', '.');
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;" align="right">'.$res->dec_tercero_sueldo.'</td>';
       $totaling=number_format((float)$totaling, 2, ',', '.');
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;" align="right">'.$totaling.'</td>';
      
       $res->anticipo_sueldo=number_format((float)$res->anticipo_sueldo, 2, ',', '.');
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;" align="right">'.$res->anticipo_sueldo.'</td>';
       $res->aporte_iess1=number_format((float)$res->aporte_iess1, 2, ',', '.');
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;" align="right">'.$res->aporte_iess1.'</td>';
       $res->asocap=number_format((float)$res->asocap, 2, ',', '.');
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;" align="right">'.$res->asocap.'</td>';
       $sociales=$res->comision_asuntos_sociales;
       $sociales=number_format((float)$sociales, 2, ',', '.');
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;" align="right">'.$sociales.'</td>';
       $res->prest_quirog_iess=number_format((float)$res->prest_quirog_iess, 2, ',', '.');
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;" align="right">'.$res->prest_quirog_iess.'</td>';
       $res->prest_hipot_iess=number_format((float)$res->prest_hipot_iess, 2, ',', '.');
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;" align="right">'.$res->prest_hipot_iess.'</td>';
       $res->dcto_salario=number_format((float)$res->dcto_salario, 2, ',', '.');
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;" align="right">'.$res->dcto_salario.'</td>';
       $totaleg=number_format((float)$totaleg, 2, ',', '.');
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;" align="right">'.$totaleg.'</td>';
       $total=number_format((float)$total, 2, ',', '.');
        $html.='<td  style="font-size: 15px;" align="right">'.$total.'</td>';
        $elementos=explode("/", $res->periodo_registro);
        $periodonomina=$meses[($elementos[3]-1)]." ".$elementos[4];
        $html.='<td style="font-size: 15px;">'.$periodonomina.'</td>';
       $html.='</tr>';
       

     }
    
     $html.='</tbody>';
     $html.='</table>';
     
     
     $html.='</section></div>';
     
     $html.='<div class="table-pagination pull-right">';
     $html.=''. $this->paginate_reporte("index.php", $page, $total_pages, $adjacents,"ReporteNomina").'';
     $html.='</div>';
     $html.=$totales;
     $html.='<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
            	
            	 <button type="button" id="Print" name="Print" class="btn btn-primary" onclick="ImprimirReporte()"><i class="glyphicon glyphicon-print"></i>  Reporte</button>
                </div>
             </div>	    
            </div>';
     
     
    }
    else {
        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
        $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
        $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registros de reloj para el periodo actual...</b>';
        $html.='</div>';
        $html.='</div>';
    }
    echo $html;
   }*/
   
   public function paginate_reporte($reload, $page, $tpages, $adjacents,$funcion='') {
       
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
    
   public function ImprimirReporte()
   {
       session_start();
       
       $meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
       
       

       $periodo=(isset($_REQUEST['periodo'])&& $_REQUEST['periodo'] !=NULL)?$_REQUEST['periodo']:'';
       
     
       $elementos=explode("/", $periodo);
       $periodonomina=$meses[($elementos[3]-1)]." DE ".$elementos[4];
       
       $reporte_nomina = new ReporteNominaEmpleadosModel();
       
       $datos_reporte = array();
       $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
       
       $tablas = "public.descuentos_salarios_empleados";
       $where = "1=1";
       
       $id = "descuentos_salarios_empleados.id_descuento";
       
       $resultDSE= $reporte_nomina->getCondiciones("*", $tablas, $where, $id);
       
       $columnas=    "empleados.nombres_empleados,empleados.numero_cedula_empleados,
                        oficina.nombre_oficina, cargos_empleados.salario_cargo, cargos_empleados.nombre_cargo,
                	   reporte_nomina_empleados.horas_ext50, reporte_nomina_empleados.horas_ext100,
                	   reporte_nomina_empleados.fondos_reserva, reporte_nomina_empleados.dec_cuarto_sueldo,
                	   reporte_nomina_empleados.dec_tercero_sueldo, reporte_nomina_empleados.anticipo_sueldo,
                	   reporte_nomina_empleados.aporte_iess1, reporte_nomina_empleados.asocap,
                	   reporte_nomina_empleados.prest_quirog_iess, reporte_nomina_empleados.prest_hipot_iess,
                	   reporte_nomina_empleados.dcto_salario, reporte_nomina_empleados.periodo_registro,
                        reporte_nomina_empleados.comision_asuntos_sociales,
                       empleados.id_empleados";
       
       $tablas= "public.reporte_nomina_empleados INNER JOIN public.empleados
            	   ON reporte_nomina_empleados.id_empleado = empleados.id_empleados
            	   INNER JOIN public.oficina
            	   ON empleados.id_oficina = oficina.id_oficina
            	   INNER JOIN public.cargos_empleados
            	   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
       
       $where="reporte_nomina_empleados.periodo_registro='".$periodo."' AND NOT (empleados.numero_cedula_empleados='1710504786')";
       
       $id="split_part(empleados.nombres_empleados, ' ', 3)";
       
       $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
       
       if(!empty($search)){
           
           
           $where1=" AND (empleados.nombres_empleados ILIKE '".$search."%' OR oficina.nombre_oficina ILIKE '".$search."%'
            OR reporte_nomina_empleados.periodo_registro ILIKE '%".$search."')";
           
           $where.=$where1;
       }
       
       $resultSet = $reporte_nomina->getCondiciones($columnas, $tablas, $where, $id);
       
       $datos_reporte['PERIODO']=$periodonomina;
       
       $horasextra50=0;
       $horasextra100=0;
       $sueldobasico=0;
       $fondosreserva=0;
       $sueldo14=0;
       $sueldo13=0;
       $totalingresos=0;
       $dctoavance=0;
       $aporteiess1=0;
       $asocap=0;
       $sociales=0;
       $quiroiess=0;
       $hipoiess=0;
       $otrosdctos=0;
       $totalegresos=0;
       $totalapagar=0;
          
       $headerfont="1px";
       $tdfont="6px";
      
       $color1="#DFE0E0";
       
       $datos_tabla.= '<table>';
       $datos_tabla.='<tr>';
       $datos_tabla.='<th width="6%" rowspan="2" style="text-align: center; font-size: '.$headerfont.';">CEDULA</th>';
       $datos_tabla.='<th width="15%" rowspan="2"  style="text-align: center;  font-size: '.$headerfont.';">APELLIDOS Y NOMBRES</th>';
       $datos_tabla.='<th width="18%" rowspan="2" style="text-align: center;  font-size: '.$headerfont.';">CARGO</th>';
       $datos_tabla.='<th bgcolor="'.$color1.'" colspan="7" scope="colgroup" style="text-align: center;  font-size: '.$headerfont.';">INGRESOS</th>';
       $datos_tabla.='<th bgcolor="'.$color1.'" colspan="5" scope="colgroup" style="text-align: center;  font-size: '.$headerfont.';">EGRESOS</th>';
       $datos_tabla.='<th  width="5%" rowspan="2" style="text-align: center;  font-size: '.$headerfont.';">A PAGAR</th>';
       $datos_tabla.='</tr>';
       $datos_tabla.='<tr>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">HORAS EXTRA 50%</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">HORAS EXTRA 100%</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">SUELDO BASICO</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">FONDOS DE RESERVA</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">14TO SUELDO</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">13RO SUELDO</th>';
       $datos_tabla.='<th  width="5%" style="text-align: center;  font-size: '.$headerfont.';">TOTAL</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">ANTICIPO SUELDOS</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">APORTE IESS '.$resultDSE[0]->descuento_iess1.'%</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">PREST. QUIROG. IESS</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">PREST.HIPOT. IESS</th>';
       $datos_tabla.='<th  width="5%" style="text-align: center;  font-size: '.$headerfont.';">TOTAL</th>';
       $datos_tabla.='</tr>';
       foreach ($resultSet as $res)
       {
           $datos_tabla.='<tr>';
           $datos_tabla.='<td  class="cargo">'.$res->numero_cedula_empleados.'</td>';
           $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$res->nombres_empleados.'</td>';
           $datos_tabla.='<td  class="cargo">'.$res->nombre_cargo.'</td>';
           
           $h50="";
           if ($res->horas_ext50!="0") 
           {
               $h50=$res->horas_ext50;
               $h50=number_format((float)$h50, 2, ',', '.');
           }
           $horasextra50+=$res->horas_ext50;
           
           $datos_tabla.='<td  class="numero">'.$h50.'</td>';
           
           $h100="";
           if ($res->horas_ext100!="0") 
           {
               $h100=$res->horas_ext100;
           $h100=number_format((float)$h100, 2, ',', '.');
           }
           $horasextra100+=$res->horas_ext100;
           $datos_tabla.='<td  class="numero">'.$h100.'</td>';
           
           $sueldobasico+=$res->salario_cargo;
           $salario_cargo=$res->salario_cargo;
           $salario_cargo=number_format((float)$salario_cargo, 2, ',', '.');
           $datos_tabla.='<td  class="numero">'.$salario_cargo.'</td>';
           
           $fondosreserva+=$res->fondos_reserva;
           $fdr=$res->fondos_reserva;
           $fdr=number_format((float)$fdr, 2, ',', '.');
           $datos_tabla.='<td  class="numero">'.$fdr.'</td>';
           
           $d14="";
           if ($res->dec_cuarto_sueldo!="0") 
           {
               $d14=$res->dec_cuarto_sueldo;
               $d14=number_format((float)$d14, 2, ',', '.');
           }
           $sueldo14+=$res->dec_cuarto_sueldo;
           $datos_tabla.='<td  class="numero">'.$d14.'</td>';
           
           $d13="";
           if ($res->dec_tercero_sueldo!="0") 
           {
               $d13=$res->dec_tercero_sueldo;
               $d13=number_format((float)$d13, 2, ',', '.');
           }
           $sueldo13+=$res->dec_tercero_sueldo;
           $datos_tabla.='<td  class="numero">'.$d13.'</td>';
           
           $totaling=$res->horas_ext50+$res->horas_ext100+$res->salario_cargo+$res->fondos_reserva+$res->dec_cuarto_sueldo+$res->dec_tercero_sueldo;
           $totalingresos+=$totaling;
           $total_ingresos=$totaling;
           $total_ingresos=number_format((float)$total_ingresos, 2, ',', '.');
           $datos_tabla.='<td  class="numero">'.$total_ingresos.'</td>';
           
           $ant="";
           if ($res->anticipo_sueldo!="0") 
           {
               $ant=$res->anticipo_sueldo;
               $ant=number_format((float)$ant, 2, ',', '.');
           }
           $dctoavance+=$res->anticipo_sueldo;
           $datos_tabla.='<td  class="numero">'.$ant.'</td>';
           
           $api1=$res->aporte_iess1;
           $api1=number_format((float)$api1, 2, '.', '');
           $aporteiess1+=$api1;
           $apiess1=$res->aporte_iess1;
           $apiess1=number_format((float)$apiess1, 2, ',', '.');
           $datos_tabla.='<td  class="numero">'.$apiess1.'</td>';
           
           $qiess="";
           if ($res->prest_quirog_iess!="0") {
               $qiess=$res->prest_quirog_iess;
               $qiess=number_format((float)$qiess, 2, ',', '.');
           }
           $quiroiess+=$res->prest_quirog_iess;
           $datos_tabla.='<td  class="numero">'.$qiess.'</td>';
           
           $hiess="";
           if ($res->prest_hipot_iess!="0") {
               
               $hiess=$res->prest_hipot_iess;
               $hiess=number_format((float)$hiess, 2, ',', '.');
               
           }
           $hipoiess+=$res->prest_hipot_iess;
           $datos_tabla.='<td  class="numero">'.$hiess.'</td>';
                      
           $totaleg=$res->anticipo_sueldo+$res->aporte_iess1+$res->asocap+$resultDSE[0]->asuntos_sociales+$res->prest_quirog_iess+$res->prest_hipot_iess+$res->dcto_salario;
           $totalegresos+=$totaleg;
           $total_egresos=$totaleg;
           $total_egresos=number_format((float)$total_egresos, 2, ',', '.');
           $datos_tabla.='<td  class="numero">'.$total_egresos.'</td>';
           
           $apagar=$totaling-$totaleg;
           $totalapagar+=$apagar;
           $a_pagar=$apagar;
           $a_pagar=number_format((float)$a_pagar, 2, ',', '.');
           $datos_tabla.='<td  class="numero">'.$a_pagar.'</td>';
           $datos_tabla.='</tr>';
          
       }
       
       $datos_tabla.='<tr>';
       $datos_tabla.='<td colspan="3" style="text-align: center;  font-size: '.$tdfont.';">TOTALES</td>';
       $h50="-";
       if($horasextra50!="0") {
           $h50=$horasextra50;
           $h50=number_format((float)$h50, 2, ',', '.');
       }
       $datos_tabla.='<td  class="numero">'.$h50.'</td>';
       
       $h100="-";
       if($horasextra100!="0")
       {
           $h100=$horasextra100;
           $h100=number_format((float)$h100, 2, ',', '.');
           
       }
       $datos_tabla.='<td  class="numero">'.$h100.'</td>';
       
       $sueldobasico=number_format((float)$sueldobasico, 2, ',', '.');
       $datos_tabla.='<td  class="numero">'.$sueldobasico.'</td>';
       
       $fondosreserva=number_format((float)$fondosreserva, 2, ',', '.');
       $datos_tabla.='<td  class="numero">'.$fondosreserva.'</td>';
       
       $d14="-";
       if($sueldo14!="0") {
           $d14=$sueldo14;
           $d14=number_format((float)$d14, 2, ',', '.');
       }
       $datos_tabla.='<td  class="numero">'.$d14.'</td>';
       
       $d13="-";
       if($sueldo13!="0") 
       {
           $d13=$sueldo13;
           $d13=number_format((float)$d13, 2, ',', '.');
       }
       $datos_tabla.='<td  class="numero">'.$d13.'</td>';       
       
       $totalingresos=number_format((float)$totalingresos, 2, ',', '.');
       $datos_tabla.='<td  class="numero">'.$totalingresos.'</td>';
       
       $ant="-";
       if($dctoavance!="0") 
       {
           $ant=$dctoavance;
           $ant=number_format((float)$ant, 2, ',', '.');
       }
       $datos_tabla.='<td  class="numero">'.$ant.'</td>';
       
       $aporteiess1=number_format((float)$aporteiess1, 2, ',', '.');
       $datos_tabla.='<td  class="numero">'.$aporteiess1.'</td>';
                 
       $qiess="-";
       if($quiroiess!="0") 
       {
           $qiess=$quiroiess;
           $qiess=number_format((float)$qiess, 2, ',', '.');
       }
       $datos_tabla.='<td  class="numero">'.$qiess.'</td>';
       
       $hiess="-";
       if($hipoiess!="0") 
       {
           $hiess=$hipoiess;
           $hiess=number_format((float)$hiess, 2, ',', '.');
       }
       $datos_tabla.='<td  class="numero">'.$hiess.'</td>';
       
       $totalegresos=number_format((float)$totalegresos, 2, ',', '.');
       
       $datos_tabla.='<td  class="numero">'.$totalegresos.'</td>';
       $totalapagar=number_format((float)$totalapagar, 2, ',', '.');
       $datos_tabla.='<td  class="numero">'.$totalapagar.'</td>';
       $datos_tabla.='</tr>';
     
       $datos_tabla.= "</table>";
       $datos_tabla.= "<br>";
       $datos_tabla.= "<br>";
       $datos_tabla.= "<br>";
       $datos_tabla.= "<br>";
       $datos_tabla.= '<table class="firmas">';
       $datos_tabla.='<tr>';
       //$datos_tabla.='<td   class="firmas"  width="6%"  style="text-align: left; font-size: '.$headerfont.';"></td>';
       $datos_tabla.='<td   class="firmas" width="26%" style="text-align: left; font-size: '.$headerfont.';">Elaborado por:<br>Lcdo. Byron Bolaños<br>Jefe de RR-HH</td>';
       $datos_tabla.='<td   class="firmas" style="text-align: left;  font-size: '.$headerfont.';">Aprobado por:<br>Ing. Stephany Zurita<br>Representante Legal</td>';
       $datos_tabla.='</tr>';
       $datos_tabla.= "</table>";
       
       $this->verReporte("ReporteNomina", array('datos_reporte'=>$datos_reporte
           ,'datos_tabla'=>$datos_tabla));
     
   }
   
   public function ImprimirReporteIndividual()
   {
       session_start();
       
       $meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
       
       
       
       
       
       
       
       $reporte_nomina = new ReporteNominaEmpleadosModel();
       
       
       
       $id_registro =  (isset($_REQUEST['id_registro'])&& $_REQUEST['id_registro'] !=NULL)?$_REQUEST['id_registro']:'';
       
       $tablas = "public.descuentos_salarios_empleados";
       $where = "1=1";
       
       $id = "descuentos_salarios_empleados.id_descuento";
       
       $resultDSE= $reporte_nomina->getCondiciones("*", $tablas, $where, $id);
       
       $columnas=    "empleados.nombres_empleados,empleados.numero_cedula_empleados,
                        oficina.nombre_oficina, cargos_empleados.salario_cargo, cargos_empleados.nombre_cargo,
                	   reporte_nomina_empleados.horas_ext50, reporte_nomina_empleados.horas_ext100,
                	   reporte_nomina_empleados.fondos_reserva, reporte_nomina_empleados.dec_cuarto_sueldo,
                	   reporte_nomina_empleados.dec_tercero_sueldo, reporte_nomina_empleados.anticipo_sueldo,
                	   reporte_nomina_empleados.aporte_iess1, reporte_nomina_empleados.asocap,
                	   reporte_nomina_empleados.prest_quirog_iess, reporte_nomina_empleados.prest_hipot_iess,
                	   reporte_nomina_empleados.dcto_salario, reporte_nomina_empleados.periodo_registro,
                        reporte_nomina_empleados.comision_asuntos_sociales,
                       empleados.id_empleados, departamentos.nombre_departamento";
       
       $tablas= "public.reporte_nomina_empleados INNER JOIN public.empleados
            	   ON reporte_nomina_empleados.id_empleado = empleados.id_empleados
            	   INNER JOIN public.oficina
            	   ON empleados.id_oficina = oficina.id_oficina
            	   INNER JOIN public.cargos_empleados
            	   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo
                   INNER JOIN public.departamentos
                   ON cargos_empleados.id_departamento = departamentos.id_departamento";
       
       $where="reporte_nomina_empleados.id_registro='".$id_registro."'";
       
       $id="reporte_nomina_empleados.id_registro";
       
       $resultSet = $reporte_nomina->getCondiciones($columnas, $tablas, $where, $id);
       
       $elementos=explode("/", $resultSet[0]->periodo_registro);
       $periodonomina=$meses[($elementos[3]-1)]." DE ".$elementos[4];
       $datos_reporte = array();
       
       $datos_reporte['FECHA']=$periodonomina;
       $datos_reporte['NOMBREEMPLEADO']=$resultSet[0]->nombres_empleados;
       $datos_reporte['CARGOEMPLEADO']=$resultSet[0]->nombre_cargo;
       $datos_reporte['DPTOEMPLEADO']=$resultSet[0]->nombre_departamento;
       
       $ingresos=0;
       $egresos=0;
       
       $ingresos+=$resultSet[0]->salario_cargo;
       $salario=$resultSet[0]->salario_cargo;
       $salario=number_format((float)$salario, 2, '.', ',');
       $datos_reporte['SUELDO']=$salario;
       
       $ingresos+=$resultSet[0]->horas_ext50;
       $h50=$resultSet[0]->horas_ext50;
       $h50=number_format((float)$h50, 2, '.', ',');
       $datos_reporte['EXTRA50']=$h50;
       
       $ingresos+=$resultSet[0]->horas_ext100;
       $h100=$resultSet[0]->horas_ext100;
       $h100=number_format((float)$h100, 2, '.', ',');
       $datos_reporte['EXTRA100']=$h100;
       
       $ingresos+=$resultSet[0]->fondos_reserva;
       $frs=$resultSet[0]->fondos_reserva;
       $frs=number_format((float)$frs, 2, '.', ',');
       $datos_reporte['RESERVA']=$frs;
       
       $ingresos+=$resultSet[0]->dec_cuarto_sueldo;
       $s14=$resultSet[0]->dec_cuarto_sueldo;
       $s14=number_format((float)$s14, 2, '.', ',');
       $datos_reporte['SUELDO14']=$s14;
       
       $ingresos+=$resultSet[0]->dec_tercero_sueldo;
       $s13=$resultSet[0]->dec_tercero_sueldo;
       $s13=number_format((float)$s13, 2, '.', ',');
       $datos_reporte['SUELDO13']=$s13;
       
       $total=$ingresos;
       
       $ingresos=number_format((float)$ingresos, 2, '.', ',');
       $datos_reporte['TOTALING']=$ingresos;
       
       $egresos+=$resultSet[0]->anticipo_sueldo;
       $asueldo=$resultSet[0]->anticipo_sueldo;
       $asueldo=number_format((float)$asueldo, 2, '.', ',');
       $datos_reporte['ASUELDO']=$asueldo;
     
       $egresos+=$resultSet[0]->aporte_iess1;
       $aiess=$resultSet[0]->aporte_iess1;
       $aiess=number_format((float)$aiess, 2, '.', ',');
       $datos_reporte['APIESS']=$aiess;
       $datos_reporte['AP']=$resultDSE[0]->descuento_iess1;
       
       $egresos+=$resultSet[0]->asocap;
       $asocap=$resultSet[0]->asocap;
       $asocap=number_format((float)$asocap, 2, '.', ',');
       $datos_reporte['ASOCAP']=$asocap;
       
       $egresos+=$resultSet[0]->comision_asuntos_sociales;
       $social=$resultSet[0]->comision_asuntos_sociales;
       $social=number_format((float)$social, 2, '.', ',');
       $datos_reporte['SOCIALES']=$social;
       
       $egresos+=$resultSet[0]->prest_quirog_iess;
       $qiess=$resultSet[0]->prest_quirog_iess;
       $qiess=number_format((float)$qiess, 2, '.', ',');
       $datos_reporte['QUIROIESS']=$qiess;
       
       $egresos+=$resultSet[0]->prest_hipot_iess;
       $hiess=$resultSet[0]->prest_hipot_iess;
       $hiess=number_format((float)$hiess, 2, '.', ',');
       $datos_reporte['HIPOIESS']=$hiess;
       
       $egresos+=$resultSet[0]->dcto_salario;
       $dcto=$resultSet[0]->dcto_salario;
       $dcto=number_format((float)$dcto, 2, '.', ',');
       $datos_reporte['DCTO']=$dcto;
       $total=$total-$egresos;
       $egresos=number_format((float)$egresos, 2, '.', ',');
       $datos_reporte['TOTALEG']=$egresos;
       
      
       $total=number_format((float)$total, 2, '.', ',');
       $datos_reporte['TOTAL A PAGAR']=$total;
       
       $datos_reporte['CEDULA']=$resultSet[0]->numero_cedula_empleados;
             
       $datos_tabla="";
       
       $this->verReporte("ReporteNominaIndividual", array('datos_reporte'=>$datos_reporte
           ,'datos_tabla'=>$datos_tabla));
       
       
       
   }
}
?>