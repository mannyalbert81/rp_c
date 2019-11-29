<?php

class BalanceComprobacionController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
   public function index(){
        session_start();
        $plan_cuentas = new PlanCuentasModel();
        
        $tablas= "public.plan_cuentas";
        
        $where= "1=1";
        
        $id= "max";
        
        $resultMAX=$plan_cuentas->getCondiciones("MAX(nivel_plan_cuentas)", $tablas, $where, $id);
        $this->view_Contable('BalanceComprobacion',array("resultMAX"=>$resultMAX));
    }
   
 
    public function tieneHijo($nivel, $codigo, $resultado)
    {
        $elementos_codigo=explode(".", $codigo);
        $nivel1=$nivel;
        $nivel1--;
        $verif="";
        for ($i=0; $i<$nivel1; $i++)
        {
            $verif.=$elementos_codigo[$i];
        }
        
        foreach ($resultado as $res)
        {
            $verif1="";
            $elementos1_codigo=explode(".", $res->codigo_plan_cuentas);
            if (sizeof($elementos1_codigo)>=$nivel1)
                
                for ($i=0; $i<$nivel1; $i++)
                {
                    $verif1.=$elementos1_codigo[$i];
                }
            
            
            if ($res->nivel_plan_cuentas==$nivel && $verif==$verif1)
            {
                return true;
            }
        }
        return false;
    }
    
    public function tieneHijoA($nivel, $codigo, $resultado)
    {
        $elementos_codigo=explode(".", $codigo);
        $nivel1=$nivel;
        $nivel1--;
        $verif="";
        for ($i=0; $i<$nivel1; $i++)
        {
            $verif.=$elementos_codigo[$i];
        }
        
        foreach ($resultado as $res)
        {
            $verif1="";
            $elementos_saldo=explode("|", $res);
            $elementos1_codigo=explode(".", $elementos_saldo[0]);
            if (sizeof($elementos1_codigo)>=$nivel1)
                
                for ($i=0; $i<$nivel1; $i++)
                {
                    $verif1.=$elementos1_codigo[$i];
                }
            
            
            if ($elementos_saldo[6]==$nivel && $verif==$verif1)
            {
                return true;
            }
        }
        return false;
    }
    
    public function SumaSaldoHijo($nivel, $codigo, $resultado)
    {
        $elementos_codigo=explode(".", $codigo);
        $nivel1=$nivel;
        $nivel1--;
        $verif="";
        $suma_saldo=0;
        for ($i=0; $i<$nivel1; $i++)
        {
            $verif.=$elementos_codigo[$i];
        }
        
        foreach ($resultado as $res)
        {
            $verif1="";
            $elementos_saldo=explode("|", $res);
            $elementos1_codigo=explode(".", $elementos_saldo[0]);
            if (sizeof($elementos1_codigo)>=$nivel1)
                
                for ($i=0; $i<$nivel1; $i++)
                {
                    $verif1.=$elementos1_codigo[$i];
                }
            
            
            if ($elementos_saldo[6]==$nivel && $verif==$verif1)
            {
                /* dc 2019-11-27 -*/
                // transformacion de saldo formato adecuado para sumar
                /*$_valor_a_sumar = str_replace(".", "", $elementos_saldo[2]);
                $_valor_a_sumar = str_replace(",", ".", $elementos_saldo[2]);*/
                
                $suma_saldo+=$elementos_saldo[2];
                
            }
        }
        return $suma_saldo;
    }
    
    public function BalanceDBalance($nivel, $resultset, $limit, $codigo)
    {
        $headerfont="16px";
        $tdfont="14px";
        $boldi="";
        $boldf="";
        
        $colores= array();
        $colores[0]="#D6EAF8";
        $colores[1]="#D1F2EB";
        $colores[2]="#F6DDCC";
        $colores[3]="#FAD7A0";
        $colores[4]="#FCF3CF";
        $colores[5]="#FDFEFE";
        
        if ($codigo=="")
        {
            $sumatoria="";
            foreach($resultset as $res)
            {
                $verif1="";
                $elementos1_codigo=explode(".", $res->codigo_plan_cuentas);
                if (sizeof($elementos1_codigo)>=$nivel)
                    for ($i=0; $i<$nivel; $i++)
                    {
                        $verif1.=$elementos1_codigo[$i];
                    }
                if ($res->nivel_plan_cuentas == $nivel)
                {
                    
                    if($nivel<=$limit)
                    {$nivel++;
                    $nivelclase=$nivel-1;
                    if ($nivelclase<4)
                    {
                        $boldi="<b>";
                        $boldf="</b>";
                    }
                    $color=$nivel-2;
                    if ($color>5) $color=5;
                    $sumatoria.='<tr id="cod'.$verif1.'">';
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">';
                    if ($this->tieneHijo($nivel,$res->codigo_plan_cuentas, $resultset) && $nivelclase!=$limit)
                    {
                        $sumatoria.='<button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$verif1.'&quot;,&quot;trbt'.$verif1.'&quot;)">
                    <i id="trbt'.$verif1.'" class="fa fa-angle-double-right" name="boton"></i></button>';
                    }
                    $sumatoria.=$boldi.$res->nombre_plan_cuentas.$boldf.'</td>';
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->saldo_plan_cuentas.$boldf.'</td>';
                    $sumatoria.='</tr>';
                    if ($this->tieneHijo($nivel,$res->codigo_plan_cuentas, $resultset))
                    {
                        
                        $sumatoria.=$this->BalanceDBalance($nivel, $resultset, $limit, $res->codigo_plan_cuentas);
                        
                    }
                    
                    $nivel--;
                    }
                }
            }
        }
        else
        {
            
            $sumatoria="";
            $elementos_codigo=explode(".", $codigo);
            $nivel1=$nivel;
            $nivel1--;
            $verif="";
            for ($i=0; $i<$nivel1; $i++)
            {
                $verif.=$elementos_codigo[$i];
            }
            foreach($resultset as $res)
            {
                $verif1="";
                $verif2="";
                $elementos1_codigo=explode(".", $res->codigo_plan_cuentas);
                for ($i=0; $i<sizeof($elementos1_codigo); $i++)
                {
                    $verif2.=$elementos1_codigo[$i];
                }
                if (sizeof($elementos1_codigo)>=$nivel1)
                    for ($i=0; $i<$nivel1; $i++)
                    {
                        $verif1.=$elementos1_codigo[$i];
                    }
                
                if ($res->nivel_plan_cuentas == $nivel && $verif==$verif1)
                {
                    
                    
                    if($nivel<=$limit)
                    {$nivel++;
                    $nivelclase=$nivel-1;
                    if ($nivelclase<4)
                    {
                        $boldi="<b>";
                        $boldf="</b>";
                    }
                    $color=$nivel-2;
                    if ($color>5) $color=5;
                    $sumatoria.='<tr class="nivel'.$verif1.'" id="cod'.$verif2.'" style="display:none">';
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">';
                    if ($this->tieneHijo($nivel,$res->codigo_plan_cuentas, $resultset) && $nivelclase!=$limit)
                    {
                        $sumatoria.='<button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$verif2.'&quot;,&quot;trbt'.$verif2.'&quot;)">
                    <i id="trbt'.$verif2.'" class="fa fa-angle-double-right" name="boton"></i></button>';
                    }
                    $sumatoria.=$boldi.$res->nombre_plan_cuentas.$boldf;
                    $sumatoria.='</td>';
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->saldo_plan_cuentas.$boldf.'</td>';
                    $sumatoria.='</tr>';
                    if ($this->tieneHijo($nivel,$res->codigo_plan_cuentas, $resultset))
                    {
                        
                        $sumatoria.=$this->BalanceDBalance($nivel, $resultset, $limit, $res->codigo_plan_cuentas);
                    }
                    $nivel--;
                    }
                }
            }
        }
        return $sumatoria;
    }
  
    
    public function Balance($nivel, $resultset, $limit, $codigo)
    {
        $headerfont="16px";
        $tdfont="14px";
        $boldi="";
        $boldf="";
        
        $colores= array();
        $colores[0]="#D6EAF8";
        $colores[1]="#D1F2EB";
        $colores[2]="#F6DDCC";
        $colores[3]="#FAD7A0";
        $colores[4]="#FCF3CF";
        $colores[5]="#FDFEFE";
        $colorletra="black";
        
        if ($codigo=="")
        {
            $sumatoria="";
            foreach($resultset as $res)
            {
                
                $verif1="";
                $elementos_saldo=explode("|", $res);
                $elementos1_codigo=explode(".", $elementos_saldo[0]);
                //print_r($elementos_saldo);
                if (sizeof($elementos1_codigo)>=$nivel)
                    for ($i=0; $i<$nivel; $i++)
                    {
                        $verif1.=$elementos1_codigo[$i];
                    }
                if ($elementos_saldo[6] == $nivel)
                {
                    
                    if($nivel<=$limit)
                    {   
                        $nivel++;
                        $nivelclase=$nivel-1;
                        if ($nivelclase<4)
                        {
                            $boldi="<b>";
                            $boldf="</b>";
                        }
                        $color=$nivel-2;
                        if ($color>5) $color=5;
                        $sumatoria.='<tr id="cod'.$verif1.'">';
                        $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$elementos_saldo[0].$boldf.'</td>';
                        $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">';
                        if ($this->tieneHijoA($nivel,$elementos_saldo[0], $resultset) && $nivelclase!=$limit)
                        {
                            $sumatoria.='<button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$verif1.'&quot;,&quot;trbt'.$verif1.'&quot;)">
                        <i id="trbt'.$verif1.'" class="fa fa-angle-double-right" name="boton"></i></button>';
                        }
                        $sumatoria.=$boldi.$elementos_saldo[1].$boldf.'</td>';
                        $shijo=$this->SumaSaldoHijo($nivel, $elementos_saldo[0], $resultset);
                                               
                        $shijo=number_format((float)$shijo, 2, ',', '.');
                        $elementos_saldo[2]=number_format((float)$elementos_saldo[2], 2, ',', '.');
                        if($elementos_saldo[2]!=$shijo && $this->tieneHijoA($nivel,$elementos_saldo[0], $resultset)) {
                            $colorletra="red";
                        }else{
                                $colorletra="black";
                        }
                        $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$elementos_saldo[2].$boldf.'</font></td>';
                        $sumatoria.='</tr>';
                        if ($this->tieneHijoA($nivel,$elementos_saldo[0], $resultset))
                        {
                            
                            $sumatoria.=$this->Balance($nivel, $resultset, $limit, $elementos_saldo[0]);
                            
                        }
                    
                    $nivel--;
                    }
                }
            }
        }
        else
        {
            
            $sumatoria="";
            $elementos_codigo=explode(".", $codigo);
            $nivel1=$nivel;
            $nivel1--;
            $verif="";
            for ($i=0; $i<$nivel1; $i++)
            {
                $verif.=$elementos_codigo[$i];
            }
            foreach($resultset as $res)
            {
                $elementos_saldo=explode("|", $res);
                $verif1="";
                $verif2="";
                $elementos1_codigo=explode(".", $elementos_saldo[0]);
                for ($i=0; $i<sizeof($elementos1_codigo); $i++)
                {
                    $verif2.=$elementos1_codigo[$i];
                }
                if (sizeof($elementos1_codigo)>=$nivel1)
                    for ($i=0; $i<$nivel1; $i++)
                    {
                        $verif1.=$elementos1_codigo[$i];
                    }
                
                if ($elementos_saldo[6] == $nivel && $verif==$verif1)
                {
                    if($nivel<=$limit)
                    {$nivel++;
                    $nivelclase=$nivel-1;
                    if ($nivelclase<4)
                    {
                        $boldi="<b>";
                        $boldf="</b>";
                    }
                    $color=$nivel-2;
                    if ($color>5) $color=5;
                    $sumatoria.='<tr class="nivel'.$verif1.'" id="cod'.$verif2.'" style="display:none">';
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$elementos_saldo[0].$boldf.'</td>';
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">';
                    if ($this->tieneHijoA($nivel,$elementos_saldo[0], $resultset) && $nivelclase!=$limit)
                    {
                        $sumatoria.='<button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$verif2.'&quot;,&quot;trbt'.$verif2.'&quot;)">
                    <i id="trbt'.$verif2.'" class="fa fa-angle-double-right" name="boton"></i></button>';
                    }
                    $sumatoria.=$boldi.$elementos_saldo[1].$boldf.'</td>';
                    $shijo=$this->SumaSaldoHijo($nivel, $elementos_saldo[0], $resultset);
                    //echo "$elementos_saldo[0] ",$shijo," ****";
                    $shijo=number_format((float)$shijo, 2, ',', '.');
                    $elementos_saldo[2]=number_format((float)$elementos_saldo[2], 2, ',', '.');
                    if($elementos_saldo[2]!=$shijo  && $this->tieneHijoA($nivel,$elementos_saldo[0], $resultset)) {
                        $colorletra="red";
                    }else{
                        $colorletra="black";
                    }
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$elementos_saldo[2].$boldf.'</font></td>';
                    $sumatoria.='</tr>';
                    if ($this->tieneHijoA($nivel,$elementos_saldo[0], $resultset))
                    {
                        $sumatoria.=$this->Balance($nivel, $resultset, $limit, $elementos_saldo[0]);
                    }
                    $nivel--;
                    }
                }
            }
        }
        return $sumatoria;
    }
    
    public function BalanceErrores($nivel, $resultset, $limit, $codigo)
    {
        if ($codigo=="")
        {
            $sumatoria="";
            foreach($resultset as $res)
            {
                
                $verif1="";
                $elementos_saldo=explode("|", $res);
                $elementos1_codigo=explode(".", $elementos_saldo[0]);
                if (sizeof($elementos1_codigo)>=$nivel)
                    for ($i=0; $i<$nivel; $i++)
                    {
                        $verif1.=$elementos1_codigo[$i];
                    }
                if ($elementos_saldo[6] == $nivel)
                {
                    
                    if($nivel<=$limit)
                    {$nivel++;
                    $shijo=$this->SumaSaldoHijo($nivel, $elementos_saldo[0], $resultset);
                    $shijo=number_format((float)$shijo, 2, ',', '.');
                    $elementos_saldo[2]=number_format((float)$elementos_saldo[2], 2, ',', '.');
                    if($elementos_saldo[2]!=$shijo && $this->tieneHijoA($nivel,$elementos_saldo[0], $resultset)) {
                        $sumatoria.=$elementos_saldo[0]."|";
                    }
                    
                    if ($this->tieneHijoA($nivel,$elementos_saldo[0], $resultset))
                    {
                        $sumatoria.=$this->BalanceErrores($nivel, $resultset, $limit, $elementos_saldo[0]);
                    }
                    $nivel--;
                    }
                }
            }
        }
        else
        {
            
            $sumatoria="";
            $elementos_codigo=explode(".", $codigo);
            $nivel1=$nivel;
            $nivel1--;
            $verif="";
            for ($i=0; $i<$nivel1; $i++)
            {
                $verif.=$elementos_codigo[$i];
            }
            foreach($resultset as $res)
            {
                $elementos_saldo=explode("|", $res);
                $verif1="";
                $verif2="";
                $elementos1_codigo=explode(".", $elementos_saldo[0]);
                for ($i=0; $i<sizeof($elementos1_codigo); $i++)
                {
                    $verif2.=$elementos1_codigo[$i];
                }
                if (sizeof($elementos1_codigo)>=$nivel1)
                    for ($i=0; $i<$nivel1; $i++)
                    {
                        $verif1.=$elementos1_codigo[$i];
                    }
                
                if ($elementos_saldo[6] == $nivel && $verif==$verif1)
                {
                    if($nivel<=$limit)
                    {$nivel++;
                    $shijo=$this->SumaSaldoHijo($nivel, $elementos_saldo[0], $resultset);
                    $shijo=number_format((float)$shijo, 2, ',', '.');
                    $elementos_saldo[2]=number_format((float)$elementos_saldo[2], 2, ',', '.');
                    if($elementos_saldo[2]!=$shijo  && $this->tieneHijoA($nivel,$elementos_saldo[0], $resultset)) {
                        $sumatoria.=$elementos_saldo[0]."|";
                    }
                    if ($this->tieneHijoA($nivel,$elementos_saldo[0], $resultset))
                    {
                        $sumatoria.=$this->BalanceErrores($nivel, $resultset, $limit, $elementos_saldo[0]);
                    }
                    $nivel--;
                    }
                }
            }
        }
        return $sumatoria;
    }
    
    public function mostrarDescarga($cabecera, $mes_reporte, $anio_reporte)
    {
        $datos_tabla='<div class="alert alert-success alert-dismissable" style="margin-top:40px;">';
        
        $datos_tabla.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        $datos_tabla.='<h4>Aviso!</h4>';
        $datos_tabla.='<b>El reporte se encuentra listo para descarga</b>';
        $datos_tabla.= '<a href="index.php?controller=BalanceComprobacion&action=DescargarReporte&id_cabecera='.$cabecera.'&mes_reporte='.$mes_reporte.'&anio_reporte='.$anio_reporte.'" target="_blank"><button class="btn btn-primary">Descargar reporte <i class="far fa-file-pdf"></i></button></a>';
        $datos_tabla.='</div>';
        
        echo $datos_tabla;
    }
    
    public function mostrarErrores($errores)
    {
        $cuentas_error=explode("|", $errores);
      
            $usu="";
            if(sizeof($cuentas_error)>1)
            {
                $usu="cuentas";
            }
            else
            {
                $usu="cuenta";
            }
            
            $datos_tabla='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            
            $datos_tabla.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $datos_tabla.='<h4>Aviso!!!</h4>';
            $datos_tabla.='<li class="dropdown messages-menu">';
            $datos_tabla.='<button type="button" class="btn btn-warning" data-toggle="dropdown">';
            $datos_tabla.='<i class="glyphicon glyphicon-list"></i>';
            $datos_tabla.='</button>';
            $datos_tabla.='<span class="label label-danger">'.(sizeof($cuentas_error)-1).'</span>';
            $datos_tabla.='<ul class="dropdown-menu scrollable-menu">';
            $datos_tabla.='<li  class="header"><font color="black">Hay '.(sizeof($cuentas_error)-1).' '.$usu.' con advertencias.</font></li>';
            $datos_tabla.='<li>';
            $datos_tabla.= '<table style = "width:100%; border-collapse: collapse;" border="1">';
            $datos_tabla.='<tbody>';
            foreach ($cuentas_error as $us)
            {
                
                if(!(empty($us)))
                {
                    $datos_tabla.='<tr height = "25">';
                    $datos_tabla.='<td bgcolor="#F5F5F5" style="font-size: 16px; text-align:center;"><font color="black">'.$us.'</font></td>';
                    $datos_tabla.='</tr>';
                }
            }
            $datos_tabla.='</tbody>';
            $datos_tabla.='</table>';
            $datos_tabla.='</ul>';
            $datos_tabla.='</li>';
            $datos_tabla.='<b>Actualmente no se puede generar un reporte debido a errores en el balance de cuentas...</b>';
            $datos_tabla.='</div>';
            
            
            
            
       
        echo $datos_tabla;
    }
       
    
    public function buscarCabecera($mes, $anio)
    {
        
        $plan_cuentas= new PlanCuentasModel();
        
        $columnas = "con_cbalance_comprobacion.id_cbalance_comprobacion";
        
        $tablas= "public.con_cbalance_comprobacion";
        
        $where= "con_cbalance_comprobacion.mes_cbalance_comprobacion=".$mes."AND con_cbalance_comprobacion.anio_cbalance_comprobacion=".$anio;
        
        $id= "con_cbalance_comprobacion.id_cbalance_comprobacion";
        
        $resultCabeza=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
        
        if (!(empty($resultCabeza)))
        {
           return $resultCabeza[0]->id_cbalance_comprobacion; 
        }
        else return 0;
    }
    
    public function getDetalles($id_cabecera, $resultSet)
    {
        $plan_cuentas= new PlanCuentasModel();
        
        $columnas="plan_cuentas.codigo_plan_cuentas, plan_cuentas.nombre_plan_cuentas,
                      (con_dbalance_comprobacion.saldo_acreedor_dbalance_comprobacion+con_dbalance_comprobacion.saldo_deudor_dbalance_comprobacion) AS saldo_plan_cuentas,
                       plan_cuentas.nivel_plan_cuentas";
        
        $tablas= "public.con_dbalance_comprobacion INNER JOIN public.plan_cuentas
                      ON con_dbalance_comprobacion.id_plan_cuentas=plan_cuentas.id_plan_cuentas";
        
        $where= "con_dbalance_comprobacion.id_cbalance_comprobacion=".$id_cabecera;
        
        $id= "con_dbalance_comprobacion.id_plan_cuentas";
        
        $resultDetalle=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
        
        return $resultDetalle;
    }
    
    public function generarDetalleReporte($mes, $anio, $resultSet)
    {
        $plan_cuentas= new PlanCuentasModel();
        $saldoini="vacio";
        
        $mes_reporte = $mes;
        $mes_reporte1=$mes+1;
        if($mes<10) $mes_reporte="0".$mes;
        if($mes_reporte1<10) $mes_reporte1="0".$mes_reporte1;
        
        $fecha_inicio   = $anio."-".$mes_reporte."-01";       
        $lastday        = date('t',strtotime($fecha_inicio));        
        $fecha_fin      = $anio."-".$mes."-".$lastday;
        
        //echo "AQUI LA FECHJA DE INICIIO ",$fecha_inicio," \n AQUI LA FECHA FIN ",$fecha_fin; die();
        
        $columnas = "plan_cuentas.codigo_plan_cuentas, con_mayor.fecha_mayor, con_mayor.debe_mayor,
	  	      con_mayor.haber_mayor, con_mayor.saldo_ini_mayor, con_mayor.saldo_mayor, plan_cuentas.n_plan_cuentas";        
        $tablas= "public.con_mayor INNER JOIN public.plan_cuentas
		      ON con_mayor.id_plan_cuentas = plan_cuentas.id_plan_cuentas";        
        $where= "con_mayor.fecha_mayor BETWEEN '$fecha_inicio' AND '$fecha_fin'";        
        $id= "plan_cuentas.codigo_plan_cuentas, con_mayor.creado";
        
        $resultMayor=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
        
        $Saldos=array();
        
        foreach ($resultSet as $res){
            
            $saldoini="vacio";
            
            $totaldebe=0;            
            $totalhaber=0;            
            $saldomayor=0;
            
            $fila="";
        
            foreach ($resultMayor as $resM){
                
                if ($resM->codigo_plan_cuentas == $res->codigo_plan_cuentas){
                    
                    if($saldoini=="vacio") $saldoini=$resM->saldo_ini_mayor;
                    
                    $totaldebe+=$resM->debe_mayor;
                    $totalhaber+=$resM->haber_mayor;
                    $saldomayor=$resM->saldo_mayor;
                }
            }
            
            if($saldoini!="vacio"){
                
                $saldoini=$saldoini+$totaldebe;
                $saldoini=$saldoini-$totalhaber;
                
                $comp="";
                $saldoini=number_format((float)$saldoini, 2, ',', '.');
                $saldomayor=number_format((float)$saldomayor, 2, ',', '.');
                
                //para validacion que la suma de debe y haber en mayor coincida con el saldo final de la cuenta
                $comp = ($saldoini == $saldomayor) ? "OK" : "ERROR";                 
                                
                $fila=$res->codigo_plan_cuentas."|".$res->nombre_plan_cuentas."|".$saldomayor."|".$comp."|".$totaldebe."|".$totalhaber."|".$res->nivel_plan_cuentas;
                                
                //1|ACTIVOS|71901447.0400|OK|0|0|1 
                
            }else{
                
                if($mes_reporte1==13){
                    $mes_reporte1="01";
                    $anio++;
                }
                $fecha_inicio=$anio."-".$mes_reporte1."-01";
                $lastday = date('t',strtotime($fecha_inicio));
                $fecha_fin=$anio."-".$mes_reporte1."-".$lastday; 
                
                //echo $fecha_fin;  
                
                $columnas = "plan_cuentas.codigo_plan_cuentas,  con_mayor.saldo_ini_mayor, con_mayor.saldo_mayor, plan_cuentas.n_plan_cuentas";                
                $tablas= "public.con_mayor INNER JOIN public.plan_cuentas
    		      ON con_mayor.id_plan_cuentas = plan_cuentas.id_plan_cuentas";                              
                $where= "con_mayor.fecha_mayor BETWEEN '$fecha_inicio' AND '$fecha_fin' AND plan_cuentas.codigo_plan_cuentas='".$res->codigo_plan_cuentas."'";                
                $id= "con_mayor.fecha_mayor";
                
                $resultSI=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
                              
                if(!(empty($resultSI))){
                    
                    $fila=$res->codigo_plan_cuentas."|".$res->nombre_plan_cuentas."|".$saldomayor."|OK|".$totaldebe."|".$totalhaber."|".$res->nivel_plan_cuentas;
                }else{
                    
                    $fila=$res->codigo_plan_cuentas."|".$res->nombre_plan_cuentas."|".$res->saldo_plan_cuentas."|OK|".$totaldebe."|".$totalhaber."|".$res->nivel_plan_cuentas;
                }
            }
            array_push($Saldos, $fila);
        }
        return $Saldos;
    }
    
    public function GenerarReporte()
    {
        session_start();
        if (isset(  $_SESSION['usuario_usuarios']) )
        {
            $mes_reporte=$_POST['mes'];
            $anio_reporte=$_POST['anio'];
            $max_nivel_balance=$_POST['max_nivel_balance'];
            $id_usuarios=$_SESSION['id_usuarios'];
            $plan_cuentas=new PlanCuentasModel();
            $tabla_reporte="";
            $resultCabeza=$this->buscarCabecera($mes_reporte, $anio_reporte);
            
            
            $columnas = "codigo_plan_cuentas, nombre_plan_cuentas, saldo_plan_cuentas, saldo_fin_plan_cuentas, nivel_plan_cuentas, id_plan_cuentas, n_plan_cuentas";            
            $tablas= "public.plan_cuentas";            
            $where= "1=1";            
            $id= "plan_cuentas.codigo_plan_cuentas";
            
            $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
            
            if ($resultCabeza!=0)
            {
                $columnas="plan_cuentas.codigo_plan_cuentas, plan_cuentas.nombre_plan_cuentas,
                      (con_dbalance_comprobacion.saldo_acreedor_dbalance_comprobacion+con_dbalance_comprobacion.saldo_deudor_dbalance_comprobacion) AS saldo_plan_cuentas,
                       plan_cuentas.nivel_plan_cuentas";
                
                $tablas= "public.con_dbalance_comprobacion INNER JOIN public.plan_cuentas
                      ON con_dbalance_comprobacion.id_plan_cuentas=plan_cuentas.id_plan_cuentas";
                
                $where= "con_dbalance_comprobacion.id_cbalance_comprobacion=".$resultCabeza;
                
                $id= "con_dbalance_comprobacion.id_plan_cuentas";
                
                $resultDetalle=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
                
                $tabla_reporte=$this->BalanceDBalance(1, $resultDetalle, $max_nivel_balance, "");
                
            }
            else 
            {
                $datos=$this->generarDetalleReporte($mes_reporte, $anio_reporte, $resultSet);
                
                /*for ($i = 0; $i < sizeof($datos); $i++) {
                    echo $datos[$i],"<br>";    
                }
                die();*/
                $tabla_reporte=$this->Balance(1, $datos, $max_nivel_balance, "");
                $errores=$this->BalanceErrores(1, $datos, $max_nivel_balance, "");
                
                //print_r($errores);
            }
            
            
            $headerfont="16px";
            $notificacion="";
            
            $colores= array();
            $colores[0]="#D6EAF8";
            $colores[1]="#D1F2EB";
            $colores[2]="#FCF3CF";
            $colores[3]="#F8C471";
            $colores[4]="#EDBB99";
            $colores[5]="#FDFEFE";
            
            $datos_tabla= "<table id='tabla_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $datos_tabla.='<tr  bgcolor="'.$colores[0].'">';
            $datos_tabla.='<th bgcolor="'.$colores[0].'" width="1%"  style="width:130px; text-align: center;  font-size: '.$headerfont.';">CÓDIGO</th>';
            $datos_tabla.='<th bgcolor="'.$colores[0].'" width="83%" style="text-align: center;  font-size: '.$headerfont.';">CUENTA</th>';
            $datos_tabla.='<th bgcolor="'.$colores[0].'" width="83%" style="text-align: center;  font-size: '.$headerfont.';">SALDO</th>';
            $datos_tabla.='</tr>';
            
            $datos_tabla.=$tabla_reporte;
            
            $datos_tabla.= "</table>";
            
            
            
            if (!(empty($errores)))
            {
                $notificacion=$this->mostrarErrores($errores);
            }
            $mostrar_reporte=$notificacion.$datos_tabla;
            echo $mostrar_reporte;
        }
        else
        {
            
            $this->redirect("Usuarios","sesion_caducada");
        }
           
    }
    
    public function DescargarReporte()
    {
        session_start();
        
        $plan_cuentas= new PlanCuentasModel();
        
        $meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
        
        $mesbalance = (isset($_REQUEST['mes_reporte'])&& $_REQUEST['mes_reporte'] !=NULL)?$_REQUEST['mes_reporte']:'';
        $aniobalance = (isset($_REQUEST['anio_reporte'])&& $_REQUEST['anio_reporte'] !=NULL)?$_REQUEST['anio_reporte']:'';
        $id_cabecera = (isset($_REQUEST['id_cabecera'])&& $_REQUEST['id_cabecera'] !=NULL)?$_REQUEST['id_cabecera']:'';
        $mesbalance = (isset($_REQUEST['mes'])&& $_REQUEST['mes'] !=NULL)?$_REQUEST['mes']:'01';
        $aniobalance = (isset($_REQUEST['anio'])&& $_REQUEST['anio'] !=NULL)?$_REQUEST['anio']:'2019';   
        $dateToTest = $aniobalance."-".$mesbalance."-01";
        $lastday = date('t',strtotime($dateToTest));
        
        $columnas = "codigo_plan_cuentas, nombre_plan_cuentas, saldo_plan_cuentas";
        
        $tablas= "public.plan_cuentas INNER JOIN public.estado
                  ON plan_cuentas.id_estado_reporte = estado.id_estado";
        
        $where= "estado.nombre_estado = 'INCLUIDO'";
        
        $id= "plan_cuentas.codigo_plan_cuentas";
        
        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
        
        $headerfont="10px";
        $tdfont="11px";
        $firmafont="12px";
        $boldi="";
        $boldf="";
        
        $datos_tabla="";
        
        $datos_tabla.= '<table class="table1">';
        $datos_tabla.='<thead>';
        $datos_tabla.='<tr >';
        $datos_tabla.='<td colspan="4" class="cabeza" ><b>FONDO COMPLEMENTARIO PREVISIONAL CERRADO DE CESANTÍA</b></td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr >';
        $datos_tabla.='<td colspan="4" class="cabeza" ><b>DE SERVIDORES Y TRABAJADORES PÚBLICOS DE FUERZAS ARMADAS CAPREMCI</b></td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr >';
        $datos_tabla.='<td colspan="4" class="cabeza" ><b>ESTADO DE SITUACIÓN FINANCIERA</b></td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr >';
        $datos_tabla.='<td colspan="4" class="cabeza" ><b>AL '.$lastday.' DE '.$meses[$mesbalance-1].' DE '.$aniobalance.'</b></td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr >';
        $datos_tabla.='<td colspan="4" class="cabezafin" ><b>CÓDIGO: 17</b></td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr >';
        $datos_tabla.='<td colspan="4" class="cabezaespacio" >&nbsp;</td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='</thead>';
        $datos_tabla.='<tr class="iniciotabla" >';
        $datos_tabla.='<th  > CÓDIGO</th>';
        $datos_tabla.='<th  > CUENTA</th>';
        $datos_tabla.='<th  > NOTAS</th>';
        $datos_tabla.='<th  > SALDO</th>';
        $datos_tabla.='</tr>';
        $pasivos=0;
        $patrimonio=0;
        $activos=0;
        
        foreach ($resultSet as $res)
        {
            $elementos_codigo=explode(".", $res->codigo_plan_cuentas);
            if (sizeof($elementos_codigo)<4 || (sizeof($elementos_codigo)==4 && $elementos_codigo[3]==""))
            {
                $boldi="<b>";
                $boldf="</b>";
            }
            else
            {
                $boldi="";
                $boldf="";
            }
            if($elementos_codigo[0]<4)
            {
                if ($res->nombre_plan_cuentas=="PASIVOS") $pasivos=$res->saldo_plan_cuentas;
                if ($res->nombre_plan_cuentas=="PATRIMONIO") $patrimonio=$res->saldo_plan_cuentas;
                if ($res->nombre_plan_cuentas=="ACTIVOS") $activos=$res->saldo_plan_cuentas;
                $datos_tabla.='<tr class="conlineas">';
                $datos_tabla.='<td width="9%" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
                $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->nombre_plan_cuentas.$boldf.'</td>';
                $datos_tabla.='<td width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
                $saldo=$res->saldo_plan_cuentas;
                $saldo=number_format((float)$saldo, 2, ',', '.');
                if ($saldo==0) $saldo="-";
                $datos_tabla.='<td width="15%" class="decimales" >'.$boldi.$saldo.$boldf.'</td>';
                $datos_tabla.='</tr>';
            }
        }
        $datos_tabla.='<tr class="conlineas">';
        $datos_tabla.='<td width="9%" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.'2 + 3'.$boldf.'</td>';
        $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.'PASIVO + PATRIMONIO'.$boldf.'</td>';
        $datos_tabla.='<td width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
        $pasivo_patrimonio=$pasivos+$patrimonio;
        $diferencia=$pasivo_patrimonio-$activos;
        $pasivo_patrimonio=number_format((float)$pasivo_patrimonio, 2, ',', '.');
        if ($saldo==0) $saldo="-";
        $datos_tabla.='<td width="15%" class="decimales" >'.$boldi.$pasivo_patrimonio.$boldf.'</td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr class="conlineas">';
        $datos_tabla.='<td width="9%" style="text-align: left;  font-size: '.$tdfont.';"></td>';
        $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.'DIFERENCIA'.$boldf.'</td>';
        $datos_tabla.='<td width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
        
        $diferencia=number_format((float)$diferencia, 2, ',', '.');
        $datos_tabla.='<td width="15%" class="decimales" >'.$boldi.$diferencia.$boldf.'</td>';
        $datos_tabla.='</tr>';
        $datos_tabla.= "</table>";
        
        $datos_tabla.= "<br>";
        $datos_tabla.= '<table class="firmas">';
        $datos_tabla.='<tr >';
        $datos_tabla.='<td   class="firmas"  width="6%"  style="text-align: left; font-size: '.$headerfont.';">&nbsp;</td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr>';
        $datos_tabla.='<td   class="firmas"  width="6%"  style="text-align: left; font-size: '.$headerfont.';">&nbsp;</td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr>';
        $datos_tabla.='<td   class="firmas"  width="6%"  style="text-align: left; font-size: '.$headerfont.';">&nbsp;</td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr>';
        $datos_tabla.='<td   class="firmas" style="text-align: center;  font-size: '.$firmafont.';"><b>ING. STEPHANY ZURITA<br>REPRESENTANTE LEGAL</b></td>';
        $datos_tabla.='<td   class="firmas" width="26%" style="text-align: center; font-size: '.$firmafont.';"><b>LCDO. BYRON BOLAÑOS<br>CONTADOR</b></td>';
        $datos_tabla.='</tr>';
        $datos_tabla.= "</table>";
        
        $datos_tabla2= '';        
        $datos_tabla2.= '<table class="table2">';
        $datos_tabla2.='<thead>';
        $datos_tabla2.='<tr >';
        $datos_tabla2.='<td colspan="4" class="cabeza" ><b>FONDO COMPLEMENTARIO PREVISIONAL CERRADO DE CESANTÍA</b></td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr >';
        $datos_tabla2.='<td colspan="4" class="cabeza" ><b>DE SERVIDORES Y TRABAJADORES PÚBLICOS DE FUERZAS ARMADAS CAPREMCI</b></td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr >';
        $datos_tabla2.='<td colspan="4" class="cabeza" ><b>ESTADO DE RESULTADO INTEGRAL</b></td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr >';
        $datos_tabla2.='<td colspan="4" class="cabeza" ><b>AL '.$lastday.' DE '.$meses[$mesbalance-1].' DE '.$aniobalance.'</b></td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr >';
        $datos_tabla2.='<td colspan="4" class="cabezafin" ><b>CÓDIGO: 17</b></td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr >';
        $datos_tabla2.='<td colspan="4" class="cabezaespacio">&nbsp;</td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='</thead>';
        $datos_tabla2.='<tr class="iniciotabla" >';
        $datos_tabla2.='<th >CÓDIGO</th>';
        $datos_tabla2.='<th >CUENTA</th>';
        $datos_tabla2.='<th >NOTAS</th>';
        $datos_tabla2.='<th >SALDO</th>';
        $datos_tabla2.='</tr>';
        
        
        foreach ($resultSet as $res)
        {
            $elementos_codigo=explode(".", $res->codigo_plan_cuentas);
            $siaze=sizeof($elementos_codigo);
            if (sizeof($elementos_codigo)<4 || (sizeof($elementos_codigo)==4 && $elementos_codigo[3]==""))
            {
                $boldi="<b>";
                $boldf="</b>";
            }
            else
            {
                $boldi="";
                $boldf="";
            }
            if($elementos_codigo[0]>3)
            {
                $datos_tabla2.='<tr class="conlineas">';
                $datos_tabla2.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
                $datos_tabla2.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->nombre_plan_cuentas.$boldf.'</td>';
                $datos_tabla2.='<td  style="text-align: center;  font-size: '.$tdfont.';"></td>';
                $saldo=$res->saldo_plan_cuentas;
                $saldo=number_format((float)$saldo, 2, ',', '.');
                if ($saldo==0) $saldo="-";
                $datos_tabla2.='<td  class="decimales" >'.$boldi.$saldo.$boldf.'</td>';
                $datos_tabla2.='</tr>';
            }
        }
        
        $datos_tabla2.= "</table>";
        
        $datos_tabla2.= "<br>";
        $datos_tabla2.= '<table class="firmas">';
        $datos_tabla2.='<tr>';
        $datos_tabla2.='<td   class="firmas"  width="6%"  style="text-align: left; font-size: '.$headerfont.';">&nbsp;</td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr>';
        $datos_tabla2.='<td   class="firmas"  width="6%"  style="text-align: left; font-size: '.$headerfont.';">&nbsp;</td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr>';
        $datos_tabla2.='<td   class="firmas"  width="6%"  style="text-align: left; font-size: '.$headerfont.';">&nbsp;</td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr>';
        $datos_tabla2.='<td   class="firmas" style="text-align: center;  font-size: '.$firmafont.';"><b>ING. STEPHANY ZURITA<br>REPRESENTANTE LEGAL</b></td>';
        $datos_tabla2.='<td   class="firmas" width="26%" style="text-align: center; font-size: '.$firmafont.';"><b>LCDO. BYRON BOLAÑOS<br>CONTADOR</b></td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.= "</table>";
        
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $entidades = new EntidadesModel();
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
        
        $this->verReporte("ReporteBalanceComprobacion", array('datos_tabla'=>$datos_tabla, 'datos_tabla2'=>$datos_tabla2,'datos_empresa'=>$datos_empresa) );
    }
    
    
    /*** CAMBIOS PARA GENERCAION DE BALANCE DE COMPROBACION EN REPORTE 25/11/2019 **/
    public function obtenerBalance(){
        
        if(!isset($_SESSION)){
            session_start();
        }
        
        if( !isset( $_SESSION['usuario_usuarios'])){
            echo 'Error Sesion de usuario caducada';
            exit();
        }
        
        // se toma datos de la vista
        $_mes_reporte = $_POST['mes'];
        $_anio_reporte = $_POST['anio'];
        $max_nivel_balance=$_POST['max_nivel_balance'];
        $id_usuarios=$_SESSION['id_usuarios'];
        
        /** para pruebas **/
        //$_mes_reporte = 11;
        //$_anio_reporte = 2019;
        //$max_nivel_balance = 6;
        /** termina pruebas **/
        
        
        //generar variables para busqueda
        if($_mes_reporte<10) $_mes_reporte="0".(int)$_mes_reporte;
        //if($mes_reporte1<10) $mes_reporte1="0".$mes_reporte1;
        
        $fecha_inicio   = $_anio_reporte."-".$_mes_reporte."-01";
        $lastday        = date('t',strtotime($fecha_inicio));
        $fecha_fin      = $_anio_reporte."-".$_mes_reporte."-".$lastday;
        
        // instanciamos un objeto 
        $plan_cuentas=new PlanCuentasModel();
        
        //variables locales
        $tabla_reporte = "";
        $tabla_errores = "";
        $_id_cabecera_balance = 0;
        $ArrayBalance   = array();
        $ArrayErrores   = array();
        $respuesta      = array();
        
        $_id_cabecera_balance   = $this->buscarCabecera($_mes_reporte, $_anio_reporte);
       
        $columnas1  = " codigo_plan_cuentas, nombre_plan_cuentas, saldo_plan_cuentas, saldo_fin_plan_cuentas, nivel_plan_cuentas, id_plan_cuentas, n_plan_cuentas";
        $tablas1    = " public.plan_cuentas";
        $where1     = " 1=1 ";
        $id1        = " codigo_plan_cuentas";
        $rsConsulta1    = $plan_cuentas->getCondiciones($columnas1, $tablas1, $where1, $id1);
        
        if( empty($rsConsulta1) ) {
            
            echo " Revisar plan de cuentas";
            exit();
        }
        
        
        if( $_id_cabecera_balance != 0 ){
            
            // aqui se saca solo del balance ya creado o cerrado el mes 
            
        }else{
            //
            // aqui realizar el proceso para el balance 
            $columnas2  = " id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas, nivel_plan_cuentas, saldo_plan_cuentas, saldo_fin_plan_cuentas";
            $tablas2    = " plan_cuentas";
            $where2     = " 1 = 1 AND nivel_plan_cuentas <= $max_nivel_balance ";
            $id2        = " nivel_plan_cuentas DESC, codigo_plan_cuentas";
            $rsConsulta2= $plan_cuentas->getCondiciones($columnas2, $tablas2, $where2, $id2);
            
            if( empty($rsConsulta2) ){
                echo "Datos no encontrados del plan de cuentas.. LLamar Al administrador";
                exit();
            }
            
           
            
            $AuxArray   = array();
            $filaArray  = array();
            $filaError  = array();
            
            $_id_plan_cuentas   = 0; 
            $_id_plan_cuentas_mayor = 0;
            $_saldo_ini     = 0.00;
            $_saldo_fin     = 0.00;
            $_nivel_cuenta  = 0;
            $_codigo_cuenta = "";
            $_nombre_cuenta = "";
            $_diferencia    = 0; 
            
            foreach ( $rsConsulta2 as $res ){
                
                $_suma_hijo     = 0.00;
                $_was_mayor     = false;
                $_tiene_hijo    = false;
                
                // establecer valores a considerar
                $_saldo_ini = $res->saldo_plan_cuentas;
                $_saldo_fin = $res->saldo_fin_plan_cuentas;
                $_id_plan_cuentas   = $res->id_plan_cuentas;
                $_nivel_cuenta  = $res->nivel_plan_cuentas;
                $_codigo_cuenta = $res->codigo_plan_cuentas;
                $_nombre_cuenta = $res->nombre_plan_cuentas;
                
                //buscar valores del mayor 
                $columnas3  = " plan_cuentas.id_plan_cuentas, plan_cuentas.codigo_plan_cuentas, con_mayor.fecha_mayor, con_mayor.debe_mayor,
	  	                con_mayor.haber_mayor, con_mayor.saldo_ini_mayor, con_mayor.saldo_mayor, plan_cuentas.n_plan_cuentas";
                $tablas3    = " public.con_mayor INNER JOIN public.plan_cuentas
		                ON con_mayor.id_plan_cuentas = plan_cuentas.id_plan_cuentas";
                $where3     = " con_mayor.fecha_mayor BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                $id3        = " plan_cuentas.codigo_plan_cuentas, con_mayor.creado";
                $rsMayor    = $plan_cuentas->getCondiciones($columnas3, $tablas3, $where3, $id3);
                
                //$ERROR = pg_last_error(); if(!empty($ERROR)){ echo "hay error linea 1080"; die();}
               
                $_suma_debe     = 0.00;
                $_suma_haber    = 0.00;                
                $_saldo_ini_m   = 0.00;
                $_saldo_fin_m   = 0.00;                
                $_var_j = 0;
                
                foreach ( $rsMayor as $resM ){                    
                    
                    $_id_plan_cuentas_mayor = $resM->id_plan_cuentas;
                    if( $_id_plan_cuentas == $_id_plan_cuentas_mayor ){
                        //unset($_array_buscar_cedulas[$j]);
                        $_was_mayor = true;
                        $_suma_debe     += $resM->debe_mayor;
                        $_suma_haber    += $resM->haber_mayor;
                        
                    }
                    
                }
                
                $_saldo_fin_m = $_saldo_ini + ( $_suma_debe - $_suma_haber );
                
                
                $_nivel_cuenta_buscar = $_nivel_cuenta + 1;
                //buscar datos del plan de cuentas
                $columnas4  = " saldo_plan_cuentas, saldo_fin_plan_cuentas";
                $tablas4    = " plan_cuentas";
                $where4     = " nivel_plan_cuentas = '$_nivel_cuenta_buscar' AND codigo_plan_cuentas like '$_codigo_cuenta%'";
                $id4        = " codigo_plan_cuentas ";
                $rsHijo    = $plan_cuentas->getCondiciones($columnas4, $tablas4, $where4, $id4);
                
                if( empty($rsHijo) ){
                    
                   $_suma_hijo = $_saldo_fin; 
                   
                }else{
                    
                    $_tiene_hijo = true;
                    
                    foreach ($rsHijo as $resH) {
                        
                        $_suma_hijo += $resH->saldo_fin_plan_cuentas;
                    }
                
                }
                
                $filaArray['codigo_plan_cuentas']   =  $_codigo_cuenta;
                $filaArray['nombre_plan_cuentas']   =  $_nombre_cuenta;
                $filaArray['nivel_plan_cuentas']    =  $_nivel_cuenta;
                $filaArray['saldo_plan_cuentas']    =  $_saldo_fin;
                $filaArray['subnivel']  = ($_tiene_hijo) ? 1 : 0; 
                
                if( $_was_mayor ){
                    
                    $_suma_hijo = round($_suma_hijo, 4);
                    $_saldo_fin = round($_saldo_fin, 4);
                    $_saldo_fin_m = round($_saldo_fin_m, 4);
                    
                    if( $_suma_hijo == $_saldo_fin ){
                        //quiere decir que sus predecesores si estan cuadrados 
                        
                        if( $_saldo_fin == $_saldo_fin_m){
                            //quiere decir que la suma en mayor coincide con el valor de la cuenta
                            
                            $filaArray['estado'] =  "OK";                            
                            $filaArray['descripcion']   =  "";
                            $filaArray['diferencia']    =  "";
                            
                        }else{
                            
                            $filaArray['estado'] =  "ERROR";
                            $filaArray['descripcion']   =  "Revise los saldos de mayores";
                            $filaArray['diferencia']    =  $_saldo_fin_m;
                            
                            $filaError = array("id_plan_cuentas"=>$res->id_plan_cuentas,
                                "codigo_plan_cuentas"=>$res->codigo_plan_cuentas,
                                "descripcion"=>"Revise los saldos de mayores"
                            );
                        }
                        
                    }else{
                        
                        $filaArray['estado'] =  "ERROR";
                        $filaArray['descripcion']   =  "Revise los saldos subcuentas";
                        $filaArray['diferencia']    =  $_suma_hijo;
                        
                        $filaError = array("id_plan_cuentas"=>$res->id_plan_cuentas,
                            "codigo_plan_cuentas"=>$res->codigo_plan_cuentas,
                            "descripcion"=>"Revise los saldos subcuentas"
                        );
                    }
                    
                }else{
                                        
                    $_suma_hijo = round($_suma_hijo, 4);
                    $_saldo_fin = round($_saldo_fin, 4);
                    
                    if( $_suma_hijo == $_saldo_fin ){
                        //quiere decir que sus predecesores si estan cuadrados
                        //echo "OK-->"."codigo|**".$_codigo_cuenta."**|nivel|**".$_nivel_cuenta."**|saldo|**".$_saldo_fin."<br>";
                        $filaArray['estado'] =  "OK";
                        $filaArray['descripcion']   =  "";
                        $filaArray['diferencia']    =  "";
                        
                        
                    }else{
                        
                        //echo "ERROR-->|No-MAYOR|**Revise los saldos subcuentas "."codigo|**".$_codigo_cuenta."**|nivel|**".$_nivel_cuenta."**|saldo|**".$_saldo_fin."**|sumaHijo|**".$_suma_hijo."<br>";
                        $filaArray['estado'] =  "ERROR";
                        $filaArray['descripcion']   =  "Revise los saldos subcuentas";
                        $filaArray['diferencia']    =  $_suma_hijo;
                        
                        $filaError = array("id_plan_cuentas"=>$res->id_plan_cuentas,
                            "codigo_plan_cuentas"=>$res->codigo_plan_cuentas,
                            "descripcion"=>"Revise los saldos subcuentas"
                        );
                        
                    }
                    
                }
                
                //reset variable que va por cada registro
                $_was_mayor = false;
                $_suma_hijo = 0.00;
                $_suma_debe     = 0.00;
                $_suma_haber    = 0.00;
                $_saldo_fin_m   = 0.00;
                
                array_push($ArrayBalance, $filaArray);
                
                if(!empty($filaError)){
                    array_push($ArrayErrores, $filaError);
                }
                $filaError = array();
                
            }
            
           
        }
        
        
        /** TRABAJAR CON EL ARRAY GENERADO **/
        
        //ordenar el array generado
        $AuxCodigo = array();        
        foreach ($ArrayBalance as $key => $row) {
            $AuxCodigo[$key] = $row['codigo_plan_cuentas'];
        }
        
        array_multisort($AuxCodigo, SORT_ASC, $ArrayBalance);
        //termina ordenacion del array
        
        /*** GENERACION DE VISTA PARA BALANCE ***/
        
        $colores= array("#6f42c1","#D6EAF8","#D1F2EB","#F6DDCC","#FAD7A0","#FCF3CF","#FDFEFE");      
        $sizeFont= array("16","15","14","11","10","9","8");
        $colorletra="black";
                
        $htmlFila       = "";
        
        foreach ($ArrayBalance as $key => $row) {
            
            /** obtener color **/
            $_in_codigo = $row['codigo_plan_cuentas'];
            $_in_nombre = $row['nombre_plan_cuentas'];
            $_in_nivel  = $row['nivel_plan_cuentas'];
            $_in_color  = $colores[((int)$_in_nivel )];
            $_in_size   = $sizeFont[((int)$_in_nivel - 1)];
            $_in_status = $row['estado'];
            $_in_saldo  = $row['saldo_plan_cuentas'];
            $_have_subcuenta    = $row['subnivel'];
            $_cab_tr    = "";
            $_codigoVista   = str_replace(".", "", $_in_codigo);
            $_claseVista    = "";
            $boldi="";
            $boldf="";
            $_html_button   = "";
            
            /** para obtener la clase de fila **/
            $arrayCodigo = explode(".", $_in_codigo);
            
            if( $_in_nivel == 1 ){
                
                $_cab_tr = "id=\"cod".$_codigoVista."\"";
            }else{
               
                $_claseVista = "";
                for ($i = 0; $i < ($_in_nivel - 1); $i++) {
                    $_claseVista.=$arrayCodigo[$i];
                }
                $_cab_tr = "id=\"cod".$_codigoVista."\" class=\"nivel".$_claseVista."\" style=\"display:none\"";
                
            }
            
            if ($_in_nivel<4){
                $boldi="<b>";
                $boldf="</b>";
            }
            
            if( $_have_subcuenta == 1){
                $_html_button = "<button type=\"button\" class=\"btn btn-box-tool\" onclick=\"";
                $_html_button .= "ExpandirTabla(&quot;nivel".$_codigoVista."&quot;,&quot;trbt".$_codigoVista."&quot;)\">";
                $_html_button .= "<i id=\"trbt".$_codigoVista."\" class=\"fa fa-angle-double-right\" name=\"boton\"></i></button>";
            }
            
            if( $_in_status == "ERROR" ){
                $colorletra="text-danger";
            }
            else {
                $colorletra="text-default";
            }
            
            
            $filaArray['descripcion']   = 
            $filaArray['diferencia']    =  ""; 
                        
            $htmlFila .= "<tr $_cab_tr >";
            $htmlFila .= "<td bgcolor=\"".$_in_color."\" style=\"text-align: left;  font-size:".$_in_size.";\">".$boldi.$_in_codigo.$boldf."</td>";
            $htmlFila .= "<td bgcolor=\"".$_in_color."\" style=\"text-align: left;  font-size:".$_in_size.";\">";
            $htmlFila .= $_html_button.$boldi.$_in_nombre.$boldf.'</td>';
            //falta dar color a la fila
            $htmlFila .= "<td bgcolor=\"".$_in_color."\" class=\"$colorletra\" style=\"text-align: right;  font-size:".$_in_size.";\">".$boldi.$_in_saldo.$boldf."</td>";
            $htmlFila .= "</tr>";
            
        }
        
       
        $htmlBalance    = "<table id='balance_pruebas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
        $htmlBalance    .="<thead>";
        $htmlBalance    .='<tr  bgcolor="'.$colores[0].'">';
        $htmlBalance    .='<th bgcolor="'.$colores[0].'" width="1%"  style="color:white; width:130px; text-align: center;  font-size: '.$sizeFont[0].';">CÓDIGO</th>';
        $htmlBalance    .='<th bgcolor="'.$colores[0].'" width="83%" style="color:white; text-align: center;  font-size: '.$sizeFont[0].';">CUENTA</th>';
        $htmlBalance    .='<th bgcolor="'.$colores[0].'" width="83%" style="color:white; text-align: center;  font-size: '.$sizeFont[0].';">SALDO</th>';
        $htmlBalance    .='</tr>';
        $htmlBalance    .="</thead>";
        $htmlBalance    .= "<tbody>"; 
        $htmlBalance    .= $htmlFila;
        $htmlBalance    .= "</tbody>"; 
        
        $htmlErrores    = "";
        $htmlFilaError  = "";
        $_cantidad_errores = 0;
        if( !empty($ArrayErrores)){
            
            $_cantidad_errores = sizeof($ArrayErrores);
            
                        
            foreach ($ArrayErrores as $key => $row) {
                
                $htmlFilaError.='<li class="text-center">';
                $htmlFilaError.=$row['codigo_plan_cuentas'];
                $htmlFilaError.='</li>';
            }
            
            
            $htmlErrores    .= $htmlFilaError;
           
        }
        
        $error = error_get_last();
        if(!empty($error)){
            
            echo $error['message'];
            exit();
        }
        
        $respuesta['errores']= $htmlErrores;
        $respuesta['cantidaderrores']= $_cantidad_errores;
        $respuesta['balance']= $htmlBalance;
        
        echo json_encode($respuesta);
        
    }
    
    
    public function pruebaOrden(){
        
        $arrayOrden = array("1.1.01.01","1.1.01.01.02","1.1","1.1.02.01","1.2.01.01","1.3.01.10.01");
        
        $multiArrayOrden = array(array("1.1.01.01","caja"),array("1.1.01.01.02","caja001"),array("1.1","caja002"),
            array("1.1.02.01","caja003"),array("1.2.01.01","caja004"),array("1.3.01.10.01","caja005"));
        
        sort($arrayOrden);
        //asort($arrayOrden);
        //natcasesort($arrayOrden);
        
        for($i=0;$i<sizeof($arrayOrden);$i++){
            
            echo $arrayOrden[$i]."\n";
        }
        
        
        echo "AQUI VALIDACION"."\n";
        
        $pcuentas = new PlanCuentasModel();
        $columnas4  = " SUM( saldo_fin_plan_cuentas) suma ";
        $tablas4    = " plan_cuentas";
        $where4     = " nivel_plan_cuentas = '8' AND codigo_plan_cuentas like '8.3.4%'";
        $id4        = " ";
        $rsHijo    = $pcuentas->getCondicionesSinOrden($columnas4, $tablas4, $where4, $id4);
        
        var_dump($rsHijo);
        
        if( empty($rsHijo) ){
            echo "respuesta vacio la consulta "."\n";
        }else{
            $respuesta = is_null($rsHijo[0]->suma) ? "NULO" : "HAY DATOS";
            echo "tiene valor ".$respuesta."  \n";
        }
        
        var_dump(empty($rsHijo));
        
        /*****/
        echo "aqui se forma tu array \n";
        $arrayA = array();
        
        for ($i = 0; $i < 5; $i++) {
            $arrayA[] = $i;
            
        }
        
        $stringArray = join( ",", $arrayA);
        echo  "where in ( ".$stringArray.")";
        
    }
    
    public function verBalanceFN(){
        $arrayprueba = array("2.3.90.10.03|Valores por Liquidar Proveedores|-42.665,16|OK|0|10|5");
        
        echo $this->Balance(1, $arrayprueba, 5, "2.3.90.10.03");
    }
    
    
       
}
?>