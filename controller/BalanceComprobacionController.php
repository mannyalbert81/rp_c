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
        $sumatoria=array();
        
        if ($codigo=="")
        {
            $sumatoria[0]="";
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
                    $nivelclase=$nivel-1;
                    if ($nivelclase<4)
                    {
                        $boldi="<b>";
                        $boldf="</b>";
                    }
                    $color=$nivel-2;
                    if ($color>5) $color=5;
                    $sumatoria[0].='<tr id="cod'.$verif1.'">';
                    $sumatoria[0].='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$elementos_saldo[0].$boldf.'</td>';
                    $sumatoria[0].='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">';
                    if ($this->tieneHijoA($nivel,$elementos_saldo[0], $resultset) && $nivelclase!=$limit)
                    {
                        $sumatoria[0].='<button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$verif1.'&quot;,&quot;trbt'.$verif1.'&quot;)">
                    <i id="trbt'.$verif1.'" class="fa fa-angle-double-right" name="boton"></i></button>';
                    }
                    $sumatoria[0].=$boldi.$elementos_saldo[1].$boldf.'</td>';
                    $shijo=$this->SumaSaldoHijo($nivel, $elementos_saldo[0], $resultset);
                    $shijo=number_format((float)$shijo, 2, ',', '.');
                    $elementos_saldo[2]=number_format((float)$elementos_saldo[2], 2, ',', '.');
                    if($elementos_saldo[2]!=$shijo && $this->tieneHijoA($nivel,$elementos_saldo[0], $resultset)) {
                        $colorletra="red";
                        array_push($sumatoria, $elementos_saldo[0]);}
                        else {
                            $colorletra="black";
                        }
                        $sumatoria[0].='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$elementos_saldo[2].$boldf.'</font></td>';
                        $sumatoria[0].='</tr>';
                    if ($this->tieneHijoA($nivel,$elementos_saldo[0], $resultset))
                    {
                        
                        $sumatoria[0].=$this->Balance($nivel, $resultset, $limit, $elementos_saldo[0]);
                        
                    }
                    
                    $nivel--;
                    }
                }
            }
        }
        else
        {
            
            $sumatoria[0]="";
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
                    $sumatoria[0].='<tr class="nivel'.$verif1.'" id="cod'.$verif2.'" style="display:none">';
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$elementos_saldo[0].$boldf.'</td>';
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';">';
                    if ($this->tieneHijoA($nivel,$elementos_saldo[0], $resultset) && $nivelclase!=$limit)
                    {
                        $sumatoria.='<button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$verif2.'&quot;,&quot;trbt'.$verif2.'&quot;)">
                    <i id="trbt'.$verif2.'" class="fa fa-angle-double-right" name="boton"></i></button>';
                    }
                    $sumatoria.=$boldi.$elementos_saldo[1].$boldf.'</td>';
                    $shijo=$this->SumaSaldoHijo($nivel, $elementos_saldo[0], $resultset);
                    $shijo=number_format((float)$shijo, 2, ',', '.');
                    $elementos_saldo[2]=number_format((float)$elementos_saldo[2], 2, ',', '.');
                    if($elementos_saldo[2]!=$shijo  && $this->tieneHijoA($nivel,$elementos_saldo[0], $resultset)) {
                        $colorletra="red";}
                        else {
                            $colorletra="black";
                        }
                    $sumatoria.='<td bgcolor="'.$colores[$color].'" style="text-align: left;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$elementos_saldo[2].$boldf.'</font></td>';
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
        
        $mes_reporte1=$mes+1;
        if($mes<10) $mes_reporte="0".$mes;
        if($mes_reporte1<10) $mes_reporte1="0".$mes_reporte1;
        
        $fecha_inicio=$anio."-".$mes_reporte."-01";
        
        
        $lastday = date('t',strtotime($fecha_inicio));
        
        $fecha_fin=$anio."-".$mes."-".$lastday;
        
        $columnas = "plan_cuentas.codigo_plan_cuentas, con_mayor.fecha_mayor, con_mayor.debe_mayor,
	  	con_mayor.haber_mayor, con_mayor.saldo_ini_mayor, con_mayor.saldo_mayor, plan_cuentas.n_plan_cuentas";
        
        $tablas= "public.con_mayor INNER JOIN public.plan_cuentas
		ON con_mayor.id_plan_cuentas = plan_cuentas.id_plan_cuentas";
        
        $where= "con_mayor.fecha_mayor BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        
        $id= "plan_cuentas.codigo_plan_cuentas, con_mayor.creado";
        
        $resultMayor=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
        
        $Saldos=array();
        
        foreach ($resultSet as $res)
        {
            $saldoini="vacio";
            
            $totaldebe=0;
            
            $totalhaber=0;
            
            $saldomayor=0;
            
            $fila="";
        
        foreach ($resultMayor as $resM)
        {
            if ($resM->codigo_plan_cuentas == $res->codigo_plan_cuentas)
            {
                if($saldoini=="vacio") $saldoini=$resM->saldo_ini_mayor;
                $totaldebe+=$resM->debe_mayor;
                $totalhaber+=$resM->haber_mayor;
                $saldomayor=$resM->saldo_mayor;
            }
        }
        if($saldoini!="vacio")
        {
            
            $saldoini=$saldoini+$totaldebe;
            $saldoini=$saldoini-$totalhaber;
            
            $comp="";
            $saldoini=number_format((float)$saldoini, 2, ',', '.');
            $saldomayor=number_format((float)$saldomayor, 2, ',', '.');
            if ($saldoini!=$saldomayor)
            {
                $comp="ERROR";
            }
            else $comp="OK";
            
            $fila=$res->codigo_plan_cuentas."|".$res->nombre_plan_cuentas."|".$saldomayor."|".$comp."|".$totaldebe."|".$totalhaber."|".$res->nivel_plan_cuentas;
        }
        else
        {
            $columnas = "plan_cuentas.codigo_plan_cuentas,  con_mayor.saldo_ini_mayor, con_mayor.saldo_mayor, plan_cuentas.n_plan_cuentas";
            
            $tablas= "public.con_mayor INNER JOIN public.plan_cuentas
		      ON con_mayor.id_plan_cuentas = plan_cuentas.id_plan_cuentas";
            
            if($mes_reporte1==13)
            {$mes_reporte1="01";
            $anio++;
            }
            $fecha_inicio=$anio."-".$mes_reporte1."-01";
            
            $lastday = date('t',strtotime($fecha_inicio));
            
            $fecha_fin=$anio."-".$mes_reporte1."-".$lastday;
            
            $where= "con_mayor.fecha_mayor BETWEEN '$fecha_inicio' AND '$fecha_fin' AND plan_cuentas.codigo_plan_cuentas='".$res->codigo_plan_cuentas."'";
            
            $id= "con_mayor.fecha_mayor";
            
            $resultSI=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
            
            if(!(empty($resultSI)))
            {
                $fila=$res->codigo_plan_cuentas."|".$res->nombre_plan_cuentas."|".$saldomayor."|OK|".$totaldebe."|".$totalhaber."|".$res->nivel_plan_cuentas;
            }
            else
            {
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
            
            $columnas = "codigo_plan_cuentas, nombre_plan_cuentas, saldo_plan_cuentas, nivel_plan_cuentas, id_plan_cuentas, n_plan_cuentas";
            
            $tablas= "public.plan_cuentas";
            
            $where= "1=1";
            
            $id= "plan_cuentas.codigo_plan_cuentas";
            
            $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
            
            $datos=$this->generarDetalleReporte($mes_reporte, $anio_reporte, $resultSet);
            
            $headerfont="16px";
            $tdfont="14px";
            $boldi="";
            $boldf="";
            
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
            
            $datos_tabla.=$this->Balance(1, $datos, $max_nivel_balance, "");
            
            $datos_tabla.= "</table>";
            
            echo $datos_tabla;
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
        
        $mesbalance = (isset($_REQUEST['mes'])&& $_REQUEST['mes'] !=NULL)?$_REQUEST['mes']:'';
        $aniobalance = (isset($_REQUEST['anio'])&& $_REQUEST['anio'] !=NULL)?$_REQUEST['anio']:'';
        
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
        
        $datos_tabla.= '<table>';
        $datos_tabla.='<thead>';
        $datos_tabla.='<tr class="cabeza">';
        $datos_tabla.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="center"><b>FONDO COMPLEMENTARIO PREVISIONAL CERRADO DE CESANTÍA</b></td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr class="cabeza">';
        $datos_tabla.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="center"><b>DE SERVIDORES Y TRABAJADORES PÚBLICOS DE FUERZAS ARMADAS CAPREMCI</b></td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr class="cabeza">';
        $datos_tabla.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="center"><b>ESTADO DE SITUACIÓN FINANCIERA</b></td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr class="cabeza">';
        $datos_tabla.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="center"><b>AL '.$lastday.' DE '.$meses[$mesbalance-1].' DE '.$aniobalance.'</b></td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr class="cabeza">';
        $datos_tabla.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="right"><b>CÓDIGO: 17</b></td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr class="cabezafin">';
        $datos_tabla.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="right">&nbsp;</td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='</thead>';
        $datos_tabla.='<tr>';
        $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">CÓDIGO</th>';
        $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">CUENTA</th>';
        $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">NOTAS</th>';
        $datos_tabla.='<th  style="text-align: center;  font-size: '.$headerfont.';">SALDO</th>';
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
                $datos_tabla.='<tr>';
                $datos_tabla.='<td width="9%" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
                $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->nombre_plan_cuentas.$boldf.'</td>';
                $datos_tabla.='<td width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
                $saldo=$res->saldo_plan_cuentas;
                $saldo=number_format((float)$saldo, 2, ',', '.');
                if ($saldo==0) $saldo="-";
                $datos_tabla.='<td width="15%" style="text-align: right;  font-size: '.$tdfont.';">'.$boldi.$saldo.$boldf.'</td>';
                $datos_tabla.='</tr>';
            }
        }
        $datos_tabla.='<tr>';
        $datos_tabla.='<td width="9%" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.'2 + 3'.$boldf.'</td>';
        $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.'PASIVO + PATRIMONIO'.$boldf.'</td>';
        $datos_tabla.='<td width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
        $pasivo_patrimonio=$pasivos+$patrimonio;
        $diferencia=$pasivo_patrimonio-$activos;
        $pasivo_patrimonio=number_format((float)$pasivo_patrimonio, 2, ',', '.');
        if ($saldo==0) $saldo="-";
        $datos_tabla.='<td width="15%" style="text-align: right;  font-size: '.$tdfont.';">'.$boldi.$pasivo_patrimonio.$boldf.'</td>';
        $datos_tabla.='</tr>';
        $datos_tabla.='<tr>';
        $datos_tabla.='<td width="9%" style="text-align: left;  font-size: '.$tdfont.';"></td>';
        $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.'DIFERENCIA'.$boldf.'</td>';
        $datos_tabla.='<td width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
        
        $diferencia=number_format((float)$diferencia, 2, ',', '.');
        $datos_tabla.='<td width="15%" style="text-align: right;  font-size: '.$tdfont.';">'.$boldi.$diferencia.$boldf.'</td>';
        $datos_tabla.='</tr>';
        $datos_tabla.= "</table>";
        
        $datos_tabla.= "<br>";
        $datos_tabla.= '<table class="firmas">';
        $datos_tabla.='<tr>';
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
        
        $datos_tabla2.= '<table>';
        $datos_tabla2.='<thead>';
        $datos_tabla2.='<tr class="cabeza">';
        $datos_tabla2.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="center"><b>FONDO COMPLEMENTARIO PREVISIONAL CERRADO DE CESANTÍA</b></td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr class="cabeza">';
        $datos_tabla2.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="center"><b>DE SERVIDORES Y TRABAJADORES PÚBLICOS DE FUERZAS ARMADAS CAPREMCI</b></td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr class="cabeza">';
        $datos_tabla2.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="center"><b>ESTADO DE RESULTADO INTEGRAL</b></td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr class="cabeza">';
        $datos_tabla2.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="center"><b>AL '.$lastday.' DE '.$meses[$mesbalance-1].' DE '.$aniobalance.'</b></td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr class="cabeza">';
        $datos_tabla2.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="right"><b>CÓDIGO: 17</b></td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='<tr class="cabezafin">';
        $datos_tabla2.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="right">&nbsp;</td>';
        $datos_tabla2.='</tr>';
        $datos_tabla2.='</thead>';
        $datos_tabla2.='<tr>';
        $datos_tabla2.='<th  style="text-align: center;  font-size: '.$headerfont.';">CÓDIGO</th>';
        $datos_tabla2.='<th  style="text-align: center;  font-size: '.$headerfont.';">CUENTA</th>';
        $datos_tabla2.='<th  style="text-align: center;  font-size: '.$headerfont.';">NOTAS</th>';
        $datos_tabla2.='<th  style="text-align: center;  font-size: '.$headerfont.';">SALDO</th>';
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
                $datos_tabla2.='<tr>';
                $datos_tabla2.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
                $datos_tabla2.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->nombre_plan_cuentas.$boldf.'</td>';
                $datos_tabla2.='<td  style="text-align: center;  font-size: '.$tdfont.';"></td>';
                $saldo=$res->saldo_plan_cuentas;
                $saldo=number_format((float)$saldo, 2, ',', '.');
                if ($saldo==0) $saldo="-";
                $datos_tabla2.='<td  style="text-align: right;  font-size: '.$tdfont.';">'.$boldi.$saldo.$boldf.'</td>';
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
        
        $this->verReporte("ReporteBalanceComprobacion", array('datos_tabla'=>$datos_tabla, 'datos_tabla2'=>$datos_tabla2));
    }
       
}
?>