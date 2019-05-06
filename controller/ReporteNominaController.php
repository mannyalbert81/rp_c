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
        $empleados = new EmpleadosModel();
        $reportenomina  = new ReporteNominaEmpleadosModel();
        $horasextra50=$_POST['h50'];
        $horasextra100=$_POST['h100'];
        $fondosreserva=$_POST['fondos_reserva'];
        $sueldo14=$_POST['decimo_cuarto'];
        $sueldo13=$_POST['decimo_tercero'];
        $dctoavance=$_POST['anticipo_sueldo'];
        $aporteiess1=$_POST['aporte_iess'];
        $asocap=$_POST['asocap'];
        $quiroiess=$_POST['quiro_iess'];
        $hipoiess=$_POST['hipo_iess'];
        $dctosalario=$_POST['dcto_sueldo'];
        $funcion = "ins_reporte_nomina_empleado";
        $parametros = "'$emp->id_empleados',
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
                	   reporte_nomina_empleados.dcto_salario, reporte_nomina_empleados.periodo_registro";
        
        $tablas= "public.reporte_nomina_empleados INNER JOIN public.empleados
            	   ON reporte_nomina_empleados.id_empleado = empleados.id_empleados
            	   INNER JOIN public.oficina
            	   ON empleados.id_oficina = oficina.id_oficina
            	   INNER JOIN public.cargos_empleados
            	   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        
        $where="1=1";
        
        $id="reporte_nomina_empleados.id_registro";
        
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
              $html.='<th colspan="2" bgcolor="'.$colorInfo1.'" scope="colgroup">Informacion Empleado</th>';
              $html.='<th colspan="7" bgcolor="'.$coloringresos1.'" scope="colgroup">Ingresos</th>';
             $html.=' <th colspan="8" bgcolor="'.$coloregresos1.'" scope="colgroup">Egresos</th>';
             $html.='</tr>';
             $html.='<tr>';
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
       $html.='<td bgcolor="'.$colorInfo2.'" style="font-size: 15px;"><button  type="button" class="btn btn-success" onclick="';
       $html.='EditarNomina(&quot;'.$res->nombres_empleados.'&quot,&quot;'.$res->nombre_oficina.'&quot,&quot;'.$res->salario_cargo.'&quot;,&quot;'.$res->horas_ext50.'&quot;';
       $html.=',&quot;'.$res->horas_ext100.'&quot;,&quot;'.$res->fondos_reserva.'&quot;,&quot;'.$res->dec_cuarto_sueldo.'&quot;';
       $html.=',&quot;'.$res->dec_tercero_sueldo.'&quot;,&quot;'.$res->anticipo_sueldo.'&quot;,&quot;'.$res->aporte_iess1.'&quot;';
       $html.=',&quot;'.$res->asocap.'&quot;,&quot;'.$resultDSE[0]->asuntos_sociales.'&quot;,&quot;'.$res->prest_quirog_iess.'&quot;,&quot;'.$res->prest_hipot_iess.'&quot;';
       $html.=',&quot;'.$res->dcto_salario.'&quot;,&quot;'.$res->periodo_registro.'&quot;)';
       $html.='"><i class="glyphicon glyphicon-edit"></i></button>'.$res->nombres_empleados.'</td>';
       $html.='<td bgcolor="'.$colorInfo2.'" style="font-size: 15px;">'.$res->nombre_oficina.'</td>';
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$res->salario_cargo.'</td>';
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$res->horas_ext50.'</td>';
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$res->horas_ext100.'</td>';
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$res->fondos_reserva.'</td>';
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$res->dec_cuarto_sueldo.'</td>';
       $html.='<td bgcolor="'.$coloringresos2.'" style="font-size: 15px;">'.$res->dec_tercero_sueldo.'</td>';
       $totaling=$res->salario_cargo+$res->horas_ext50+$res->horas_ext100+$res->fondos_reserva+$res->dec_cuarto_sueldo+$res->dec_tercero_sueldo;
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
    
}
?>