<?php
class ReporteNominaController extends ControladorBase{
    public function index(){
        session_start();
        $this->view_Administracion("ReporteNomina",array(
            "resultSet"=>""           
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
        $fondosreserva=($salario+$horasextra50+$horasextra100)*0.0833;
        $sueldo14=$_POST['decimo_cuarto'];
        $sueldo13=$_POST['decimo_tercero'];
        $dctoavance=$_POST['anticipo_sueldo'];
        $aporteiess1=$_POST['aporte_iess'];
        $asocap=$_POST['asocap'];
        $quiroiess=$_POST['quiro_iess'];
        $hipoiess=$_POST['hipo_iess'];
        $dctosalario=$_POST['dcto_sueldo'];
        $periodo=$_POST['periodo'];
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
                                '$periodo'";
        $reportenomina->setFuncion($funcion);
        $reportenomina->setParametros($parametros);
        $resultado=$reportenomina->Insert();
    }
    
    public function GetReporte()
    {
        session_start();
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $reporte_nomina = new ReporteNominaEmpleadosModel();
        
        $periodo=$_POST['periodo'];
        $fechai=$_POST['fechai'];
        $fechaf=$_POST['fechaf'];
        
        $periodoactual=$fechai."-".$fechaf;
        
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
                       empleados.id_empleados,reporte_nomina_empleados.id_registro";
        
        $tablas= "public.reporte_nomina_empleados INNER JOIN public.empleados
            	   ON reporte_nomina_empleados.id_empleado = empleados.id_empleados
            	   INNER JOIN public.oficina
            	   ON empleados.id_oficina = oficina.id_oficina
            	   INNER JOIN public.cargos_empleados
            	   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        if($periodo=="1") $where="1=1";
        
        if($periodo=="2") $where="reporte_nomina_empleados.periodo_registro='".$periodoactual."'";
        
        $id="reporte_nomina_empleados.id_registro";
        
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if(!empty($search)){
            
            
            $where1=" AND (empleados.nombres_empleados ILIKE '".$search."%' OR oficina.nombre_oficina ILIKE '".$search."%'
            OR reporte_nomina_empleados.periodo_registro ILIKE '%".$search."')";
            
            $where.=$where1;
        }
        
        $resultSet = $reporte_nomina->getCondiciones($columnas, $tablas, $where, $id);
        
        
        $cantidadResult=sizeof($resultSet);
        
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $coloringresos1="#66CDAA";
        
        $coloringresos2="#AFEEEE";
        
        $colorInfo1="#A8CEF6";
        
        $colorInfo2="#ADD8E6";
        
        $coloregresos1="#F08080";
        
        $coloregresos2="#FFDEDE";
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$reporte_nomina->getCondicionesPag("*", $tablas, $where, $id, $limit);
        $total_pages = ceil($cantidadResult/$per_page);
        
        $html="";
        
        if (!(empty($resultSet)))
        {
            $html.='<div class="pull-left" style="margin-left:15px;">';
            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
            $html.='</div>';
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<section style="height:425px; overflow-y:scroll;">';
            $html.= "<table id='tabla_reporte' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";            
            $html.='<tr>';
              $html.='<td rowspan="2"></td>';
              $html.='<th colspan="';
              if ($periodo=='2') $html.='3';
              else $html.='2';
              $html.='" bgcolor="'.$colorInfo1.'" scope="colgroup">Informacion Empleado</th>';
              $html.='<th colspan="7" bgcolor="'.$coloringresos1.'" scope="colgroup">Ingresos</th>';
             $html.=' <th colspan="8" bgcolor="'.$coloregresos1.'" scope="colgroup">Egresos</th>';
             $html.='</tr>';
             $html.='<tr>';
             if($periodo=="2") $html.='<th bgcolor="'.$colorInfo1.'" style="text-align: left;  font-size: 14px;"></th>';
             $html.='<th bgcolor="'.$colorInfo1.'" style="text-align: left;  font-size: 14px;">Empleado</th>';
             $html.='<th bgcolor="'.$colorInfo1.'" style="text-align: left;  font-size: 14px;">Oficina</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">Salario</th>';             
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">Horas Extra 50%</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">Horas Extra 100%</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">Fondos de reserva</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">14to Sueldo</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">13ro Sueldo</th>';
             $html.='<th bgcolor="'.$coloringresos1.'" style="text-align: left;  font-size: 14px;">Total</th>';
             $html.='<th bgcolor="'.$coloregresos1.'" style="text-align: left;  font-size: 14px;">Anticipo</th>';
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
        if($periodo=="2")
        {
       $html.='<td bgcolor="'.$colorInfo2.'" style="font-size: 15px;">
       <button type="button" id="Print" name="Print" class="btn btn-primary" onclick="ImprimirReporteIndividual('.$res->id_registro.')"><i class="glyphicon glyphicon-print"></i></button>
       <button  type="button" class="btn btn-success" onclick="';
       $html.='EditarNomina(&quot;'.$res->nombres_empleados.'&quot,&quot;'.$res->nombre_oficina.'&quot,&quot;'.$res->salario_cargo.'&quot;,&quot;'.$res->horas_ext50.'&quot;';
       $html.=',&quot;'.$res->horas_ext100.'&quot;,&quot;'.$res->fondos_reserva.'&quot;,&quot;'.$res->dec_cuarto_sueldo.'&quot;';
       $html.=',&quot;'.$res->dec_tercero_sueldo.'&quot;,&quot;'.$res->anticipo_sueldo.'&quot;,&quot;'.$res->aporte_iess1.'&quot;';
       $html.=',&quot;'.$res->asocap.'&quot;,&quot;'.$resultDSE[0]->asuntos_sociales.'&quot;,&quot;'.$res->prest_quirog_iess.'&quot;,&quot;'.$res->prest_hipot_iess.'&quot;';
       $html.=',&quot;'.$res->dcto_salario.'&quot;,&quot;'.$res->periodo_registro.'&quot;,'.$res->id_empleados.')';
       $html.='"><i class="glyphicon glyphicon-edit"></i></button></td>';
        }   
       $html.='<td bgcolor="'.$colorInfo2.'" style="font-size: 15px;">'.$res->nombres_empleados.'</td>';
       $html.='<td bgcolor="'.$colorInfo2.'" style="font-size: 15px;">'.$res->nombre_oficina.'</td>';
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$res->salario_cargo.'</td>';
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$res->horas_ext50.'</td>';
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$res->horas_ext100.'</td>';
       $freserva=$res->fondos_reserva;
       $freserva=number_format((float)$freserva, 2, ',', '');
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$freserva.'</td>';
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$res->dec_cuarto_sueldo.'</td>';
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$res->dec_tercero_sueldo.'</td>';
       $totaling=$res->salario_cargo+$res->horas_ext50+$res->horas_ext100+$freserva+$res->dec_cuarto_sueldo+$res->dec_tercero_sueldo;
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$totaling.'</td>';
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;">'.$res->anticipo_sueldo.'</td>';
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;">'.$res->aporte_iess1.'</td>';
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;">'.$res->asocap.'</td>';
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;">'.$resultDSE[0]->asuntos_sociales.'</td>';
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;">'.$res->prest_quirog_iess.'</td>';
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;">'.$res->prest_hipot_iess.'</td>';
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;">'.$res->dcto_salario.'</td>';
       $totaleg=$res->anticipo_sueldo+$res->aporte_iess1+$res->asocap+$resultDSE[0]->asuntos_sociales+$res->prest_quirog_iess+$res->prest_hipot_iess+$res->dcto_salario;
       $html.='<td bgcolor="'.$coloregresos2.'" style="font-size: 15px;">'.$totaleg.'</td>';
       $total=$totaling-$totaleg;
        $html.='<td  style="font-size: 15px;">'.$total.'</td>';
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
       
       
       $periodoactual=(isset($_REQUEST['fecha'])&& $_REQUEST['fecha'] !=NULL)?$_REQUEST['fecha']:'';
       $periodo=(isset($_REQUEST['periodo'])&& $_REQUEST['periodo'] !=NULL)?$_REQUEST['periodo']:'';
       
     
       $elementos=explode("/", $periodoactual);
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
                       empleados.id_empleados";
       
       $tablas= "public.reporte_nomina_empleados INNER JOIN public.empleados
            	   ON reporte_nomina_empleados.id_empleado = empleados.id_empleados
            	   INNER JOIN public.oficina
            	   ON empleados.id_oficina = oficina.id_oficina
            	   INNER JOIN public.cargos_empleados
            	   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
       
       $where="reporte_nomina_empleados.periodo_registro='".$periodoactual."'";
       
       $id="reporte_nomina_empleados.id_registro";
       
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
          
       $headerfont="7px";
       $tdfont="9px";
      
       $color1="#DFE0E0";
       
       $datos_tabla.= '<table>';
       $datos_tabla.='<tr>';
       $datos_tabla.='<th width="6%" rowspan="2" style="text-align: center; font-size: '.$headerfont.';">CEDULA</th>';
       $datos_tabla.='<th width="15%" rowspan="2"  style="text-align: center;  font-size: '.$headerfont.';">APELLIDOS Y NOMBRES</th>';
       $datos_tabla.='<th width="10%" rowspan="2" style="text-align: center;  font-size: '.$headerfont.';">CARGO</th>';
       $datos_tabla.='<th bgcolor="'.$color1.'" colspan="7" scope="colgroup" style="text-align: center;  font-size: '.$headerfont.';">INGRESOS</th>';
       $datos_tabla.='<th bgcolor="'.$color1.'" colspan="8" scope="colgroup" style="text-align: center;  font-size: '.$headerfont.';">EGRESOS</th>';
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
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">ASOCAP</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">COMISION ASUNTOS SOCIALES</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">PREST. QUIROG. IESS</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">PREST.HIPOT. IESS</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">OTROS DCTOS.</th>';
       $datos_tabla.='<th  width="5%" style="text-align: center;  font-size: '.$headerfont.';">TOTAL</th>';
       $datos_tabla.='</tr>';
       foreach ($resultSet as $res)
       {
           $datos_tabla.='<tr>';
           $datos_tabla.='<td  style="text-align: center;  font-size: '.$tdfont.';">'.$res->numero_cedula_empleados.'</td>';
           $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$res->nombres_empleados.'</td>';
           $datos_tabla.='<td  style="text-align: center;  font-size: '.$tdfont.';">'.$res->nombre_cargo.'</td>';
           
           $h50="";
           if ($res->horas_ext50!="0") $h50=$res->horas_ext50;
           $horasextra50+=$res->horas_ext50;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$h50.'</td>';
           
           $h100="";
           if ($res->horas_ext100!="0") $h100=$res->horas_ext100;
           $horasextra100+=$res->horas_ext100;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$h100.'</td>';
           
           $sueldobasico+=$res->salario_cargo;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$res->salario_cargo.'</td>';
           
           $fondosreserva+=$res->fondos_reserva;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$res->fondos_reserva.'</td>';
           
           $d14="";
           if ($res->dec_cuarto_sueldo!="0") $d14=$res->dec_cuarto_sueldo;
           $sueldo14+=$res->dec_cuarto_sueldo;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$d14.'</td>';
           
           $d13="";
           if ($res->dec_tercero_sueldo!="0") $d13=$res->dec_tercero_sueldo;
           $sueldo13+=$res->dec_tercero_sueldo;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$d13.'</td>';
           
           $totaling=$res->horas_ext50+$res->horas_ext100+$res->salario_cargo+$res->fondos_reserva+$res->dec_cuarto_sueldo+$res->dec_tercero_sueldo;
           $totalingresos+=$totaling;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$totaling.'</td>';
           
           $ant="";
           if ($res->anticipo_sueldo!="0") $ant=$res->anticipo_sueldo;
           $dctoavance+=$res->anticipo_sueldo;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$ant.'</td>';
           
           $aporteiess1+=$res->aporte_iess1;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$res->aporte_iess1.'</td>';
           
           $aso="";
           if ($res->asocap!="0") $aso=$res->asocap;
           $asocap+=$res->asocap;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$aso.'</td>';
           
           $sociales+=$resultDSE[0]->asuntos_sociales;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$resultDSE[0]->asuntos_sociales.'</td>';
           
           $qiess="";
           if ($res->prest_quirog_iess!="0") $qiess=$res->prest_quirog_iess;
           $quiroiess+=$res->prest_quirog_iess;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$qiess.'</td>';
           
           $hiess="";
           if ($res->prest_hipot_iess!="0") $hiess=$res->prest_hipot_iess;
           $hipoiess+=$res->prest_hipot_iess;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$hiess.'</td>';
           
           $otrosdts="";
           if ($res->dcto_salario!="0") $otrosdts=$res->dcto_salario;
           $otrosdctos+=$res->dcto_salario;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$otrosdts.'</td>';
           
           $totaleg=$res->anticipo_sueldo+$res->aporte_iess1+$res->asocap+$resultDSE[0]->asuntos_sociales+$res->prest_quirog_iess+$res->prest_hipot_iess+$res->dcto_salario;
           $totalegresos+=$totaleg;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$totaleg.'</td>';
           
           $apagar=$totaling-$totaleg;
           $totalapagar+=$apagar;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$apagar.'</td>';
           $datos_tabla.='</tr>';
          
       }
       
       $datos_tabla.='<tr>';
       $datos_tabla.='<td colspan="3" style="text-align: center;  font-size: '.$tdfont.';">TOTALES</td>';
       $h50="-";
       if($horasextra50!="0") $h50=$horasextra50;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$h50.'</td>';
       
       $h100="-";
       if($horasextra100!="0") $h100=$horasextra100;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$h100.'</td>';
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$sueldobasico.'</td>';
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$fondosreserva.'</td>';
       
       $d14="-";
       if($sueldo14!="0") $d14=$sueldo14;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$d14.'</td>';
       
       $d13="-";
       if($sueldo13!="0") $d13=$sueldo13;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$d13.'</td>';       
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$totalingresos.'</td>';
       
       $ant="-";
       if($dctoavance!="0") $ant=$dctoavance;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$ant.'</td>';
       
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$aporteiess1.'</td>';
       
       $aso="-";
       if($asocap!="0") $aso=$asocap;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$aso.'</td>';
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$sociales.'</td>';
       
       $qiess="-";
       if($quiroiess!="0") $qiess=$quiroiess;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$qiess.'</td>';
       
       $hiess="-";
       if($hipoiess!="0") $hiess=$hipoiess;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$hiess.'</td>';
       
       $otrosdts="-";
       if ($otrosdctos!="0") $otrosdts=$otrosdctos;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$otrosdts.'</td>';
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$totalegresos.'</td>';
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$totalapagar.'</td>';
       $datos_tabla.='</tr>';
     
       $datos_tabla.= "</table>";
       $datos_tabla.= "<br>";
       $datos_tabla.= '<table class="firmas">';
       $datos_tabla.='<tr>';
       $datos_tabla.='<td   class="firmas"  width="6%"  style="text-align: left; font-size: '.$headerfont.';"></td>';
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
       $salario=number_format((float)$salario, 2, '.', '');
       $datos_reporte['SUELDO']=$salario;
       
       $ingresos+=$resultSet[0]->horas_ext50;
       $h50=$resultSet[0]->horas_ext50;
       $h50=number_format((float)$h50, 2, '.', '');
       $datos_reporte['EXTRA50']=$h50;
       
       $ingresos+=$resultSet[0]->horas_ext100;
       $h100=$resultSet[0]->horas_ext100;
       $h100=number_format((float)$h100, 2, '.', '');
       $datos_reporte['EXTRA100']=$h100;
       
       $ingresos+=$resultSet[0]->fondos_reserva;
       $frs=$resultSet[0]->fondos_reserva;
       $frs=number_format((float)$frs, 2, '.', '');
       $datos_reporte['RESERVA']=$frs;
       
       $ingresos+=$resultSet[0]->dec_cuarto_sueldo;
       $s14=$resultSet[0]->dec_cuarto_sueldo;
       $s14=number_format((float)$s14, 2, '.', '');
       $datos_reporte['SUELDO14']=$s14;
       
       $ingresos+=$resultSet[0]->dec_tercero_sueldo;
       $s13=$resultSet[0]->dec_tercero_sueldo;
       $s13=number_format((float)$s13, 2, '.', '');
       $datos_reporte['SUELDO13']=$s13;
       
       $ingresos=number_format((float)$ingresos, 2, '.', '');
       $datos_reporte['TOTALING']=$ingresos;
       
       $egresos+=$resultSet[0]->anticipo_sueldo;
       $asueldo=$resultSet[0]->anticipo_sueldo;
       $asueldo=number_format((float)$asueldo, 2, '.', '');
       $datos_reporte['ASUELDO']=$asueldo;
     
       $egresos+=$resultSet[0]->aporte_iess1;
       $aiess=$resultSet[0]->aporte_iess1;
       $aiess=number_format((float)$aiess, 2, '.', '');
       $datos_reporte['APIESS']=$aiess;
       $datos_reporte['AP']=$resultDSE[0]->descuento_iess1;
       
       $egresos+=$resultSet[0]->asocap;
       $asocap=$resultSet[0]->asocap;
       $asocap=number_format((float)$asocap, 2, '.', '');
       $datos_reporte['ASOCAP']=$asocap;
       
       $egresos+=$resultDSE[0]->asuntos_sociales;
       $social=$resultDSE[0]->asuntos_sociales;
       $social=number_format((float)$social, 2, '.', '');
       $datos_reporte['SOCIALES']=$social;
       
       $egresos+=$resultSet[0]->prest_quirog_iess;
       $qiess=$resultSet[0]->prest_quirog_iess;
       $qiess=number_format((float)$qiess, 2, '.', '');
       $datos_reporte['QUIROIESS']=$qiess;
       
       $egresos+=$resultSet[0]->prest_hipot_iess;
       $hiess=$resultSet[0]->prest_hipot_iess;
       $hiess=number_format((float)$hiess, 2, '.', '');
       $datos_reporte['HIPOIESS']=$hiess;
       
       $egresos+=$resultSet[0]->dcto_salario;
       $dcto=$resultSet[0]->dcto_salario;
       $dcto=number_format((float)$dcto, 2, '.', '');
       $datos_reporte['DCTO']=$dcto;
       
       $egresos=number_format((float)$egresos, 2, '.', '');
       $datos_reporte['TOTALEG']=$egresos;
       
       $total=$ingresos-$egresos;
       $total=number_format((float)$total, 2, '.', '');
       $datos_reporte['TOTAL A PAGAR']=$total;
       
       $datos_reporte['CEDULA']=$resultSet[0]->numero_cedula_empleados;
       
       
       /*$horasextra50=0;
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
       $totalegresos=0;
       $totalapagar=0;
       
       $headerfont="7px";
       $tdfont="9px";
       
       $color1="#DFE0E0";
       
       $datos_tabla.= '<table>';
       $datos_tabla.='<tr>';
       $datos_tabla.='<th width="6%" rowspan="2" style="text-align: center; font-size: '.$headerfont.';">CEDULA</th>';
       $datos_tabla.='<th width="15%" rowspan="2"  style="text-align: center;  font-size: '.$headerfont.';">APELLIDOS Y NOMBRES</th>';
       $datos_tabla.='<th width="10%" rowspan="2" style="text-align: center;  font-size: '.$headerfont.';">CARGO</th>';
       $datos_tabla.='<th bgcolor="'.$color1.'" colspan="7" scope="colgroup" style="text-align: center;  font-size: '.$headerfont.';">INGRESOS</th>';
       $datos_tabla.='<th bgcolor="'.$color1.'" colspan="7" scope="colgroup" style="text-align: center;  font-size: '.$headerfont.';">EGRESOS</th>';
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
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">ASOCAP</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">COMISION ASUNTOS SOCIALES</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">PREST. QUIROG. IESS</th>';
       $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">PREST.HIPOT. IESS</th>';
       $datos_tabla.='<th  width="5%" style="text-align: center;  font-size: '.$headerfont.';">TOTAL</th>';
       $datos_tabla.='</tr>';
       foreach ($resultSet as $res)
       {
           $datos_tabla.='<tr>';
           $datos_tabla.='<td  style="text-align: center;  font-size: '.$tdfont.';">'.$res->numero_cedula_empleados.'</td>';
           $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$res->nombres_empleados.'</td>';
           $datos_tabla.='<td  style="text-align: center;  font-size: '.$tdfont.';">'.$res->nombre_cargo.'</td>';
           
           $h50="";
           if ($res->horas_ext50!="0") $h50=$res->horas_ext50;
           $horasextra50+=$res->horas_ext50;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$h50.'</td>';
           
           $h100="";
           if ($res->horas_ext100!="0") $h100=$res->horas_ext100;
           $horasextra100+=$res->horas_ext100;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$h100.'</td>';
           
           $sueldobasico+=$res->salario_cargo;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$res->salario_cargo.'</td>';
           
           $fondosreserva+=$res->fondos_reserva;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$res->fondos_reserva.'</td>';
           
           $d14="";
           if ($res->dec_cuarto_sueldo!="0") $d14=$res->dec_cuarto_sueldo;
           $sueldo14+=$res->dec_cuarto_sueldo;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$d14.'</td>';
           
           $d13="";
           if ($res->dec_tercero_sueldo!="0") $d13=$res->dec_tercero_sueldo;
           $sueldo13+=$res->dec_tercero_sueldo;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$d13.'</td>';
           
           $totaling=$res->horas_ext50+$res->horas_ext100+$res->salario_cargo+$res->fondos_reserva+$res->dec_cuarto_sueldo+$res->dec_tercero_sueldo;
           $totalingresos+=$totaling;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$totaling.'</td>';
           
           $ant="";
           if ($res->anticipo_sueldo!="0") $ant=$res->anticipo_sueldo;
           $dctoavance+=$res->anticipo_sueldo;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$ant.'</td>';
           
           $aporteiess1+=$res->aporte_iess1;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$res->aporte_iess1.'</td>';
           
           $aso="";
           if ($res->asocap!="0") $aso=$res->asocap;
           $asocap+=$res->asocap;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$aso.'</td>';
           
           $sociales+=$resultDSE[0]->asuntos_sociales;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$resultDSE[0]->asuntos_sociales.'</td>';
           
           $qiess="";
           if ($res->prest_quirog_iess!="0") $qiess=$res->prest_quirog_iess;
           $quiroiess+=0;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$qiess.'</td>';
           
           $hiess="";
           if ($res->prest_hipot_iess!="0") $hiess=$res->prest_hipot_iess;
           $hipoiess+=0;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$hiess.'</td>';
           
           $totaleg=$res->anticipo_sueldo+$res->aporte_iess1+$res->asocap+$resultDSE[0]->asuntos_sociales+$res->prest_quirog_iess+$res->prest_hipot_iess;
           $totalegresos+=$totaleg;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$totaleg.'</td>';
           
           $apagar=$totaling-$totaleg;
           $totalapagar+=$apagar;
           $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$apagar.'</td>';
           $datos_tabla.='</tr>';
           
       }
       
       $datos_tabla.='<tr>';
       $datos_tabla.='<td colspan="3" style="text-align: center;  font-size: '.$tdfont.';">TOTALES</td>';
       $h50="-";
       if($horasextra50!="0") $h50=$horasextra50;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$h50.'</td>';
       
       $h100="-";
       if($horasextra100!="0") $h100=$horasextra100;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$h100.'</td>';
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$sueldobasico.'</td>';
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$fondosreserva.'</td>';
       
       $d14="-";
       if($sueldo14!="0") $d14=$sueldo14;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$d14.'</td>';
       
       $d13="-";
       if($sueldo13!="0") $d13=$sueldo13;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$d13.'</td>';
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$totalingresos.'</td>';
       
       $ant="-";
       if($dctoavance!="0") $ant=$dctoavance;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$ant.'</td>';
       
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$aporteiess1.'</td>';
       
       $aso="-";
       if($asocap!="0") $aso=$asocap;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$aso.'</td>';
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$sociales.'</td>';
       
       $qiess="-";
       if($quiroiess!="0") $qiess=$quiroiess;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$qiess.'</td>';
       
       $hiess="-";
       if($hipoiess!="0") $hiess=$hipoiess;
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$hiess.'</td>';
       
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$totalegresos.'</td>';
       $datos_tabla.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$totalapagar.'</td>';
       $datos_tabla.='</tr>';
       
       $datos_tabla.= "</table>";
       $datos_tabla.= "<br>";
       $datos_tabla.= '<table class="firmas">';
       $datos_tabla.='<tr>';
       $datos_tabla.='<td   class="firmas"  width="6%"  style="text-align: left; font-size: '.$headerfont.';"></td>';
       $datos_tabla.='<td   class="firmas" width="26%" style="text-align: left; font-size: '.$headerfont.';">Elaborado por:<br>Lcdo. Byron Bolaños<br>Jefe de RR-HH</td>';
       $datos_tabla.='<td   class="firmas" style="text-align: left;  font-size: '.$headerfont.';">Aprobado por:<br>Ing. Stephany Zurita<br>Representante Legal</td>';
       $datos_tabla.='</tr>';
       $datos_tabla.= "</table>";*/
       
       $datos_tabla="";
       
       $this->verReporte("ReporteNominaIndividual", array('datos_reporte'=>$datos_reporte
           ,'datos_tabla'=>$datos_tabla));
       
       
       
   }
}
?>