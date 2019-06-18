<?php

class B17Controller extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
     	
		session_start();
        
	
	
				
				$this->view_Administracion("B17",array());
			
		
	}
	
	public function CargarReporte2()
	{
	    session_start();
	    
	    $plan_cuentas= new PlanCuentasModel();
	    $id_usuarios=$_SESSION['id_usuarios'];
	    $mes_reporte=$_POST['mes_reporte'];
	   
	    $anio_reporte=$_POST['anio_reporte'];
	    $mes_reporte++;
	    $mes_reporte1=$mes_reporte+1;
	    if($mes_reporte<10) $mes_reporte="0".$mes_reporte;
	    if($mes_reporte1<10) $mes_reporte1="0".$mes_reporte1;
	    
	    $fecha_inicio=$anio_reporte."-".$mes_reporte."-01";
	    
	    $lastday = date('t',strtotime($fecha_inicio));
	    
	    $fecha_fin=$anio_reporte."-".$mes_reporte."-".$lastday;
	    
	    $columnas = "codigo_plan_cuentas, nombre_plan_cuentas, saldo_plan_cuentas, nivel_plan_cuentas, id_plan_cuentas, n_plan_cuentas";
	    
	    $tablas= "public.plan_cuentas";
	    
	    $where= "plan_cuentas.nivel_plan_cuentas <= 4";
	    
	    $id= "plan_cuentas.codigo_plan_cuentas";
	    
	    $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $columnas = "plan_cuentas.codigo_plan_cuentas, con_mayor.fecha_mayor, con_mayor.debe_mayor,
	  	con_mayor.haber_mayor, con_mayor.saldo_ini_mayor, con_mayor.saldo_mayor, plan_cuentas.n_plan_cuentas";
	    
	    $tablas= "public.con_mayor INNER JOIN public.plan_cuentas
		ON con_mayor.id_plan_cuentas = plan_cuentas.id_plan_cuentas";
	    
	    $where= "con_mayor.fecha_mayor BETWEEN '$fecha_inicio' AND '$fecha_fin'";
	    
	    $id= "plan_cuentas.codigo_plan_cuentas, con_mayor.creado";
	    
	    $resultMayor=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $columnas = "con_cbalance_comprobacion.id_cbalance_comprobacion";
	    
	    $tablas= "public.con_cbalance_comprobacion";
	    
	    $where= "con_cbalance_comprobacion.mes_cbalance_comprobacion=".$mes_reporte."AND con_cbalance_comprobacion.anio_cbalance_comprobacion=".$anio_reporte;
	    
	    $id= "con_cbalance_comprobacion.id_cbalance_comprobacion";
	    
	    $resultCabeza=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $Saldos=array();
	    
	    $cuentaserror=array();
	    
	    $error=false;
	    
	    $headerfont="16px";
	    $tdfont="14px";
	    $boldi="";
	    $boldf="";
	    
	    $colornivel1="#D6EAF8";
	    $colornivel2="#D1F2EB  ";
	    $colornivel3="#FCF3CF";
	    $colornivel4="#FDFEFE";
	    
	    if(!(empty($resultCabeza)))
	    {
	        $columnas="plan_cuentas.codigo_plan_cuentas, plan_cuentas.nombre_plan_cuentas, 
                      (con_dbalance_comprobacion.saldo_acreedor_dbalance_comprobacion+con_dbalance_comprobacion.saldo_deudor_dbalance_comprobacion) AS saldo_plan_cuentas,
                       plan_cuentas.nivel_plan_cuentas";
	        
	        $tablas= "public.con_dbalance_comprobacion INNER JOIN public.plan_cuentas
                      ON con_dbalance_comprobacion.id_plan_cuentas=plan_cuentas.id_plan_cuentas";
	        
	        $where= "con_dbalance_comprobacion.id_cbalance_comprobacion=".$resultCabeza[0]->id_cbalance_comprobacion;
	        
	        $id= "con_dbalance_comprobacion.id_plan_cuentas";
	        
	        $resultDetalle=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        foreach ($resultSet as $res)
	        {
	            $saldoini="vacio";
	            
	            $totaldebe=0;
	            
	            $totalhaber=0;
	            
	            $saldomayor=0;
	            
	            $fila="";
	            foreach ($resultDetalle as $resD)
	            {
	                if ($resD->codigo_plan_cuentas == $res->codigo_plan_cuentas)
	                {
	                    $fila=$res->codigo_plan_cuentas."|".$res->nombre_plan_cuentas."|".$res->saldo_plan_cuentas."|OK";
	                    array_push($Saldos, $fila);
	                }
	                
	            }
	            
	       }
	       
	       $datos_tabla= "<table id='tabla_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	       $datos_tabla.='<tr  bgcolor="'.$colornivel1.'">';
	       $datos_tabla.='<th width="1%"  style="width:130px; text-align: center;  font-size: '.$headerfont.';">CÓDIGO</th>';
	       $datos_tabla.='<th width="83%" style="text-align: center;  font-size: '.$headerfont.';">CUENTA</th>';
	       $datos_tabla.='<th width="1%" style="text-align: center;  font-size: '.$headerfont.';">NOTAS</th>';
	       $datos_tabla.='<th width="15%" style="text-align: center;  font-size: '.$headerfont.';">SALDO</th>';
	       $datos_tabla.='</tr>';
	       $pasivos=0;
	       $patrimonio=0;
	       $activos=0;
	       $sumatotal=0;
	       $cerror=0;
	       
	       foreach ($Saldos as $res)
	       {
	           
	           $infosaldos=explode("|", $res);
	           $colorletra="black";
	           $elementos_codigo=explode(".", $infosaldos[0]);
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
	           
	           
	           if ($infosaldos[1]=="PASIVOS") $pasivos=$infosaldos[2];
	           if ($infosaldos[1]=="PATRIMONIO") $patrimonio=$infosaldos[2];
	           if ($infosaldos[1]=="ACTIVOS") $activos=$infosaldos[2];
	           if (sizeof($elementos_codigo)==1 || (sizeof($elementos_codigo)==2 && $elementos_codigo[1]==""))
	           {
	               $total=0;
	               foreach ($Saldos as $res1)
	               {
	                   $infosaldos1=explode("|", $res1);
	                   $elementos1_codigo=explode(".", $infosaldos1[0]);
	                   if ($infosaldos[0]!=$infosaldos1[0] && ((sizeof($elementos1_codigo)==3 && $elementos1_codigo[2]=="") || sizeof($elementos1_codigo)==2)
	                       && $elementos1_codigo[0]==$elementos_codigo[0])
	                   {
	                       $total+=$infosaldos1[2];
	                   }
	               }
	               
	               $datos_tabla.='<tr >';
	               $datos_tabla.='<td bgcolor="'.$colornivel1.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	               $datos_tabla.='<td bgcolor="'.$colornivel1.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$elementos_codigo[0].'&quot;,&quot;trbt'.$elementos_codigo[0].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].'" class="fa fa-plus" name="boton"></i></button>'.$boldi.$infosaldos[1].$boldf.'
                </td>';
	               $total=number_format((float)$total, 2, ',', '.');
	               $datos_tabla.='<td  bgcolor="'.$colornivel1.'"style="text-align: center;  font-size: '.$tdfont.';"></td>';
	               $saldo=$infosaldos[2];
	               $saldo=number_format((float)$saldo, 2, ',', '.');
	               if ($total!=$saldo) {
	                   $colorletra="red";
	                   array_push($cuentaserror, $infosaldos[0]);
	                   $cerror++;
	               }
	               if ($saldo==0) $saldo="-";
	               $datos_tabla.='<td  bgcolor="'.$colornivel1.'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$saldo.$boldf.'</font></td>';
	               $datos_tabla.='</tr>';
	           }
	           else if (sizeof($elementos_codigo)==2 || (sizeof($elementos_codigo)==3 && $elementos_codigo[2]==""))
	           {
	               $total=0;
	               foreach ($Saldos as $res1)
	               {
	                   $infosaldos1=explode("|", $res1);
	                   $elementos1_codigo=explode(".", $infosaldos1[0]);
	                   if ($infosaldos[0]!=$infosaldos1[0] && ((sizeof($elementos1_codigo)==4 && $elementos1_codigo[3]=="") || sizeof($elementos1_codigo)==3)
	                       && $elementos1_codigo[0]==$elementos_codigo[0] && $elementos1_codigo[1]==$elementos_codigo[1])
	                   {
	                       $total+=$infosaldos1[2];
	                   }
	               }
	               $total=number_format((float)$total, 2, ',', '.');
	               $datos_tabla.='<tr  class="nivel'.$elementos_codigo[0].'" style="display:none">';
	               $datos_tabla.='<td bgcolor="'.$colornivel2.'"  style="  text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	               if (sizeof($elementos_codigo)==3 && $elementos_codigo[2]=="")
	               {
	                   $datos_tabla.='<td bgcolor="'.$colornivel2.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla2(&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].'&quot;,&quot;trbt'.$elementos_codigo[0].$elementos_codigo[1].'&quot;,&quot;nivel'.$elementos_codigo[0].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].$elementos_codigo[1].'" class="fa fa-plus" name="boton1"></i></button>'.$boldi.$infosaldos[1].$boldf.'
                    </td>';
	               }
	               else
	               {
	                   $datos_tabla.='<td bgcolor="'.$colornivel2.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[1].$boldf.'
                    </td>';
	               }
	               $datos_tabla.='<td  bgcolor="'.$colornivel2.'" width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
	               $saldo=$infosaldos[2];
	               $saldo=number_format((float)$saldo, 2, ',', '.');
	               if ($total!=$saldo) {
	                   $colorletra="red";
	                   array_push($cuentaserror, $infosaldos[0]);
	                   $cerror++;
	               }
	               if(sizeof($elementos_codigo)==2){
	                   $colorletra="black";
	                   array_pop($cuentaserror);
	                   $cerror--;
	               }
	               if ($saldo==0) $saldo="-";
	               $datos_tabla.='<td  bgcolor="'.$colornivel2.'"  style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$saldo.$boldf.'</font></td>';
	               $datos_tabla.='</tr>';
	           }
	           else if (sizeof($elementos_codigo)==3 || (sizeof($elementos_codigo)==4 && $elementos_codigo[3]==""))
	           {
	               $total=0;
	               foreach ($Saldos as $res1)
	               {
	                   $infosaldos1=explode("|",$res1);
	                   $elementos1_codigo=explode(".", $infosaldos1[0]);
	                   if ($infosaldos[0]!=$infosaldos1[0] && ((sizeof($elementos1_codigo)==5 && $elementos1_codigo[4]=="") || sizeof($elementos1_codigo)==4)
	                       && $elementos1_codigo[0]==$elementos_codigo[0] && $elementos1_codigo[1]==$elementos_codigo[1]
	                       && $elementos1_codigo[2]==$elementos_codigo[2])
	                   {
	                       $total+=$infosaldos1[2];
	                   }
	               }
	               $total=number_format((float)$total, 2, ',', '.');
	               $datos_tabla.='<tr  class="nivel'.$elementos_codigo[0].$elementos_codigo[1].'" style="display:none">';
	               $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	               if (sizeof($elementos_codigo)==4 && $elementos_codigo[3]=="")
	               {
	                   $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla3(&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'&quot;,&quot;trbt'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'&quot;,&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'" class="fa fa-plus" name="boton2"></i></button>'.$boldi.$infosaldos[1].$boldf.'
                    </td>';
	               }
	               else
	               {
	                   $datos_tabla.='<td bgcolor="'.$colornivel3.'"  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[1].$boldf.'</td>';
	               }
	               $datos_tabla.='<td bgcolor="'.$colornivel3.'"  style="text-align: center;  font-size: '.$tdfont.';"></td>';
	               $saldo=$infosaldos[2];
	               $saldo=number_format((float)$saldo, 2, ',', '.');
	               if ($total!=$saldo) {
	                   $colorletra="red";
	                   $cerror++;
	                   array_push($cuentaserror, $infosaldos[0]);
	               }
	               if(sizeof($elementos_codigo)==3){
	                   $colorletra="black";
	               }
	               if ($saldo==0) $saldo="-";
	               $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$saldo.$boldf.'</font></td>';
	               $datos_tabla.='</tr>';
	           }
	           else if (sizeof($elementos_codigo)==4)
	           {
	               $datos_tabla.='<tr bgcolor="'.$colornivel4.'" class="nivel'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'" style="display:none">';
	               $datos_tabla.='<td width="9%" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	               $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[1].$boldf.'</td>';
	               $datos_tabla.='<td width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
	               $saldo=$infosaldos[2];
	               $saldo=number_format((float)$saldo, 2, ',', '.');
	               if ($saldo==0) $saldo="-";
	               $datos_tabla.='<td width="15%" style="text-align: right;  font-size: '.$tdfont.';">'.$boldi.$saldo.$boldf.'</td>';
	               $datos_tabla.='</tr>';
	           }
	       }
	       
	      $datos_tabla.= "</table>";
	       $datos_tabla.= '<div class="row">
	           
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <a href="index.php?controller=B17&action=DescargarReporte&id_cabecera='.$resultCabeza[0]->id_cbalance_comprobacion.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a>
                </div>
             </div>
            </div>';
	       
	       echo $datos_tabla;
	    }
	    else
	    {
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
	                    $error=true;
	                    array_push($cuentaserror, $res->codigo_plan_cuentas);
	                }
	                else $comp="OK";
	                
	                $fila=$res->codigo_plan_cuentas."|".$res->nombre_plan_cuentas."|".$saldomayor."|".$comp;
	            }
	            else
	            {
	                $columnas = "plan_cuentas.codigo_plan_cuentas,  con_mayor.saldo_ini_mayor, con_mayor.saldo_mayor, plan_cuentas.n_plan_cuentas";
	                
	                $tablas= "public.con_mayor INNER JOIN public.plan_cuentas
		      ON con_mayor.id_plan_cuentas = plan_cuentas.id_plan_cuentas";
	                
	                if($mes_reporte1==13)
	                {$mes_reporte1="01";
	                $anio_reporte++;
	                }
	                $fecha_inicio=$anio_reporte."-".$mes_reporte1."-01";
	                
	                $lastday = date('t',strtotime($fecha_inicio));
	                
	                $fecha_fin=$anio_reporte."-".$mes_reporte1."-".$lastday;
	                
	                $where= "con_mayor.fecha_mayor BETWEEN '$fecha_inicio' AND '$fecha_fin' AND plan_cuentas.codigo_plan_cuentas='".$res->codigo_plan_cuentas."'";
	                
	                $id= "con_mayor.fecha_mayor";
	                
	                $resultSI=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	                
	                if(!(empty($resultSI)))
	                {
	                    $fila=$res->codigo_plan_cuentas."|".$res->nombre_plan_cuentas."|".$resultSI[0]->saldo_ini_mayor."|OK";
	                }
	                else $fila=$res->codigo_plan_cuentas."|".$res->nombre_plan_cuentas."|".$res->saldo_plan_cuentas."|OK";
	            }
	            array_push($Saldos, $fila);
	        }
	        if ($error)
	        {
	            $datos_tabla= "<table id='tabla_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $datos_tabla.='<tr  bgcolor="'.$colornivel1.'">';
	            $datos_tabla.='<th width="1%"  style="width:130px; text-align: center;  font-size: '.$headerfont.';">CÓDIGO</th>';
	            $datos_tabla.='<th width="83%" style="text-align: center;  font-size: '.$headerfont.';">CUENTA</th>';
	            $datos_tabla.='<th width="1%" style="text-align: center;  font-size: '.$headerfont.';">NOTAS</th>';
	            $datos_tabla.='<th width="15%" style="text-align: center;  font-size: '.$headerfont.';">SALDO</th>';
	            $datos_tabla.='</tr>';
	            
	            foreach ($Saldos as $res)
	            {
	                $infosaldos=explode("|", $res);
	                $sumacon=0;
	                $colorletra="black";
	                $elementos_codigo=explode(".", $infosaldos[0]);
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
	                
	                
	                if ($infosaldos[1]=="PASIVOS") $pasivos=$infosaldos[2];
	                if ($infosaldos[1]=="PATRIMONIO") $patrimonio=$infosaldos[2];
	                if ($infosaldos[1]=="ACTIVOS") $activos=$infosaldos[2];
	                if (sizeof($elementos_codigo)==1 || (sizeof($elementos_codigo)==2 && $elementos_codigo[1]==""))
	                {
	                    $datos_tabla.='<tr >';
	                    $datos_tabla.='<td bgcolor="'.$colornivel1.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	                    $datos_tabla.='<td bgcolor="'.$colornivel1.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$elementos_codigo[0].'&quot;,&quot;trbt'.$elementos_codigo[0].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].'" class="fa fa-plus" name="boton"></i></button>'.$boldi.$infosaldos[1].$boldf.'
                </td>';
	                    $datos_tabla.='<td  bgcolor="'.$colornivel1.'"style="text-align: center;  font-size: '.$tdfont.';"></td>';
	                    
	                    if ($infosaldos[3]=="ERROR") $colorletra="red";
	                    $sumacon=$infosaldos[2];
	                    $sumacon=number_format((float)$sumacon, 2, ',', '.');
	                    if ($sumacon==0) $sumacon="-";
	                    $datos_tabla.='<td  bgcolor="'.$colornivel1.'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$sumacon.$boldf.'</font></td>';
	                    $datos_tabla.='</tr>';
	                }
	                else if (sizeof($elementos_codigo)==2 || (sizeof($elementos_codigo)==3 && $elementos_codigo[2]==""))
	                {
	                    $datos_tabla.='<tr  class="nivel'.$elementos_codigo[0].'" style="display:none">';
	                    $datos_tabla.='<td bgcolor="'.$colornivel2.'"  style="  text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	                    if (sizeof($elementos_codigo)==3 && $elementos_codigo[2]=="")
	                    {
	                        $datos_tabla.='<td bgcolor="'.$colornivel2.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla2(&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].'&quot;,&quot;trbt'.$elementos_codigo[0].$elementos_codigo[1].'&quot;,&quot;nivel'.$elementos_codigo[0].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].$elementos_codigo[1].'" class="fa fa-plus" name="boton1"></i></button>'.$boldi.$infosaldos[1].$boldf.'
                    </td>';
	                    }
	                    else
	                    {
	                        $datos_tabla.='<td bgcolor="'.$colornivel2.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[1].$boldf.'
                    </td>';
	                    }
	                    $datos_tabla.='<td  bgcolor="'.$colornivel2.'" width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
	                    if ($infosaldos[3]=="ERROR") $colorletra="red";
	                    $sumacon=$infosaldos[2];
	                    $sumacon=number_format((float)$sumacon, 2, ',', '.');
	                    if ($sumacon==0) $sumacon="-";
	                    $datos_tabla.='<td width="15%" bgcolor="'.$colornivel2.'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$sumacon.$boldf.'</font></td>';$datos_tabla.='</tr>';
	                }
	                else if (sizeof($elementos_codigo)==3 || (sizeof($elementos_codigo)==4 && $elementos_codigo[3]==""))
	                {
	                    $datos_tabla.='<tr  class="nivel'.$elementos_codigo[0].$elementos_codigo[1].'" style="display:none">';
	                    $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	                    if (sizeof($elementos_codigo)==4 && $elementos_codigo[3]=="")
	                    {
	                        $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla3(&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'&quot;,&quot;trbt'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'&quot;,&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'" class="fa fa-plus" name="boton2"></i></button>'.$boldi.$infosaldos[1].$boldf.'
                    </td>';
	                    }
	                    else
	                    {
	                        $datos_tabla.='<td bgcolor="'.$colornivel3.'"  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[1].$boldf.'</td>';
	                    }
	                    $datos_tabla.='<td bgcolor="'.$colornivel3.'"  style="text-align: center;  font-size: '.$tdfont.';"></td>';
	                    if ($infosaldos[3]=="ERROR") $colorletra="red";
	                    $sumacon=$infosaldos[2];
	                    $sumacon=number_format((float)$sumacon, 2, ',', '.');
	                    if ($sumacon==0) $sumacon="-";
	                    $datos_tabla.='<td width="15%" bgcolor="'.$colornivel3.'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$sumacon.$boldf.'</font></td>';$datos_tabla.='</tr>';
	                }
	                else if (sizeof($elementos_codigo)==4)
	                {
	                    $datos_tabla.='<tr bgcolor="'.$colornivel4.'" class="nivel'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'" style="display:none">';
	                    $datos_tabla.='<td width="9%" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	                    $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[1].$boldf.'</td>';
	                    $datos_tabla.='<td width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
	                    if ($infosaldos[3]=="ERROR") $colorletra="red";
	                    $saldo=$infosaldos[2];
	                    $saldo=number_format((float)$saldo, 2, ',', '.');
	                    if ($saldo==0) $saldo="-";
	                    $datos_tabla.='<td width="15%" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$saldo.$boldf.'</font></td>';
	                    $datos_tabla.='</tr>';
	                }
	            }
	            
	            $datos_tabla.= "</table>";
	            $usu="";
	            if(sizeof($cuentaserror)>1)
	            {
	                $usu="cuentas";
	            }
	            else
	            {
	                $usu="cuenta";
	            }
	            
	            $datos_tabla.='<li class="dropdown messages-menu">';
	            $datos_tabla.='<button type="button" class="btn btn-warning" data-toggle="dropdown">';
	            $datos_tabla.='<i class="glyphicon glyphicon-list"></i>';
	            $datos_tabla.='</button>';
	            $datos_tabla.='<span class="label label-danger">'.sizeof($cuentaserror).'</span>';
	            $datos_tabla.='<ul class="dropdown-menu scrollable-menu">';
	            $datos_tabla.='<li  class="header">Hay '.sizeof($cuentaserror).' '.$usu.' con advertencias.</li>';
	            $datos_tabla.='<li>';
	            $datos_tabla.= '<table style = "width:100%; border-collapse: collapse;" border="1">';
	            $datos_tabla.='<tbody>';
	            foreach ($cuentaserror as $us)
	            {
	                
	                
	                $datos_tabla.='<tr height = "25">';
	                $datos_tabla.='<td bgcolor="#F5F5F5" style="font-size: 16px; text-align:center;">'.$us.'</td>';
	                $datos_tabla.='</tr>';
	                
	            }
	            $datos_tabla.='</tbody>';
	            $datos_tabla.='</table>';
	            $datos_tabla.='</ul>';
	            $datos_tabla.='</li>';
	            
	            $datos_tabla.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $datos_tabla.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $datos_tabla.='<h4>Aviso!!!</h4> <b>Actualmente no se puede generar un reporte debido a errores en el balance de cuentas...</b>';
	            $datos_tabla.='</div>';
	            
	            
	            
	            echo $datos_tabla;
	        }
	        else
	        {
	            $cuentaserror=array();
	            $datos_tabla= "<table id='tabla_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $datos_tabla.='<tr  bgcolor="'.$colornivel1.'">';
	            $datos_tabla.='<th width="1%"  style="width:130px; text-align: center;  font-size: '.$headerfont.';">CÓDIGO</th>';
	            $datos_tabla.='<th width="83%" style="text-align: center;  font-size: '.$headerfont.';">CUENTA</th>';
	            $datos_tabla.='<th width="1%" style="text-align: center;  font-size: '.$headerfont.';">NOTAS</th>';
	            $datos_tabla.='<th width="15%" style="text-align: center;  font-size: '.$headerfont.';">SALDO</th>';
	            $datos_tabla.='</tr>';
	            $pasivos=0;
	            $patrimonio=0;
	            $activos=0;
	            $sumatotal=0;
	            $cerror=0;
	            
	            foreach ($Saldos as $res)
	            {
	                $infosaldos=explode("|", $res);
	                $colorletra="black";
	                $elementos_codigo=explode(".", $infosaldos[0]);
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
	                
	                
	                if ($infosaldos[1]=="PASIVOS") $pasivos=$infosaldos[2];
	                if ($infosaldos[1]=="PATRIMONIO") $patrimonio=$infosaldos[2];
	                if ($infosaldos[1]=="ACTIVOS") $activos=$infosaldos[2];
	                if (sizeof($elementos_codigo)==1 || (sizeof($elementos_codigo)==2 && $elementos_codigo[1]==""))
	                {
	                    $total=0;
	                    foreach ($Saldos as $res1)
	                    {
	                        $infosaldos1=explode("|", $res1);
	                        $elementos1_codigo=explode(".", $infosaldos1[0]);
	                        if ($infosaldos[0]!=$infosaldos1[0] && ((sizeof($elementos1_codigo)==3 && $elementos1_codigo[2]=="") || sizeof($elementos1_codigo)==2)
	                            && $elementos1_codigo[0]==$elementos_codigo[0])
	                        {
	                            $total+=$infosaldos1[2];
	                        }
	                    }
	                    
	                    $datos_tabla.='<tr >';
	                    $datos_tabla.='<td bgcolor="'.$colornivel1.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	                    $datos_tabla.='<td bgcolor="'.$colornivel1.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$elementos_codigo[0].'&quot;,&quot;trbt'.$elementos_codigo[0].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].'" class="fa fa-plus" name="boton"></i></button>'.$boldi.$infosaldos[1].$boldf.'
                </td>';
	                    $total=number_format((float)$total, 2, ',', '.');
	                    $datos_tabla.='<td  bgcolor="'.$colornivel1.'"style="text-align: center;  font-size: '.$tdfont.';"></td>';
	                    $saldo=$infosaldos[2];
	                    $saldo=number_format((float)$saldo, 2, ',', '.');
	                    if ($total!=$saldo) {
	                        $colorletra="red";
	                        array_push($cuentaserror, $infosaldos[0]);
	                        $cerror++;
	                    }
	                    if ($saldo==0) $saldo="-";
	                    $datos_tabla.='<td  bgcolor="'.$colornivel1.'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$saldo.$boldf.'</font></td>';
	                    $datos_tabla.='</tr>';
	                }
	                else if (sizeof($elementos_codigo)==2 || (sizeof($elementos_codigo)==3 && $elementos_codigo[2]==""))
	                {
	                    $total=0;
	                    foreach ($Saldos as $res1)
	                    {
	                        $infosaldos1=explode("|", $res1);
	                        $elementos1_codigo=explode(".", $infosaldos1[0]);
	                        if ($infosaldos[0]!=$infosaldos1[0] && ((sizeof($elementos1_codigo)==4 && $elementos1_codigo[3]=="") || sizeof($elementos1_codigo)==3)
	                            && $elementos1_codigo[0]==$elementos_codigo[0] && $elementos1_codigo[1]==$elementos_codigo[1])
	                        {
	                            $total+=$infosaldos1[2];
	                        }
	                    }
	                    $total=number_format((float)$total, 2, ',', '.');
	                    $datos_tabla.='<tr  class="nivel'.$elementos_codigo[0].'" style="display:none">';
	                    $datos_tabla.='<td bgcolor="'.$colornivel2.'"  style="  text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	                    if (sizeof($elementos_codigo)==3 && $elementos_codigo[2]=="")
	                    {
	                        $datos_tabla.='<td bgcolor="'.$colornivel2.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla2(&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].'&quot;,&quot;trbt'.$elementos_codigo[0].$elementos_codigo[1].'&quot;,&quot;nivel'.$elementos_codigo[0].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].$elementos_codigo[1].'" class="fa fa-plus" name="boton1"></i></button>'.$boldi.$infosaldos[1].$boldf.'
                    </td>';
	                    }
	                    else
	                    {
	                        $datos_tabla.='<td bgcolor="'.$colornivel2.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[1].$boldf.'
                    </td>';
	                    }
	                    $datos_tabla.='<td  bgcolor="'.$colornivel2.'" width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
	                    $saldo=$infosaldos[2];
	                    $saldo=number_format((float)$saldo, 2, ',', '.');
	                    if ($total!=$saldo) {
	                        $colorletra="red";
	                        array_push($cuentaserror, $infosaldos[0]);
	                        $cerror++;
	                    }
	                    if(sizeof($elementos_codigo)==2){
	                        $colorletra="black";
	                        array_pop($cuentaserror);
	                        $cerror--;
	                    }
	                    if ($saldo==0) $saldo="-";
	                    $datos_tabla.='<td  bgcolor="'.$colornivel2.'"  style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$saldo.$boldf.'</font></td>';
	                    $datos_tabla.='</tr>';
	                }
	                else if (sizeof($elementos_codigo)==3 || (sizeof($elementos_codigo)==4 && $elementos_codigo[3]==""))
	                {
	                    $total=0;
	                    foreach ($Saldos as $res1)
	                    {
	                        $infosaldos1=explode("|",$res1);
	                        $elementos1_codigo=explode(".", $infosaldos1[0]);
	                        if ($infosaldos[0]!=$infosaldos1[0] && ((sizeof($elementos1_codigo)==5 && $elementos1_codigo[4]=="") || sizeof($elementos1_codigo)==4)
	                            && $elementos1_codigo[0]==$elementos_codigo[0] && $elementos1_codigo[1]==$elementos_codigo[1]
	                            && $elementos1_codigo[2]==$elementos_codigo[2])
	                        {
	                            $total+=$infosaldos1[2];
	                        }
	                    }
	                    $total=number_format((float)$total, 2, ',', '.');
	                    $datos_tabla.='<tr  class="nivel'.$elementos_codigo[0].$elementos_codigo[1].'" style="display:none">';
	                    $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	                    if (sizeof($elementos_codigo)==4 && $elementos_codigo[3]=="")
	                    {
	                        $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla3(&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'&quot;,&quot;trbt'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'&quot;,&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'" class="fa fa-plus" name="boton2"></i></button>'.$boldi.$infosaldos[1].$boldf.'
                    </td>';
	                    }
	                    else
	                    {
	                        $datos_tabla.='<td bgcolor="'.$colornivel3.'"  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[1].$boldf.'</td>';
	                    }
	                    $datos_tabla.='<td bgcolor="'.$colornivel3.'"  style="text-align: center;  font-size: '.$tdfont.';"></td>';
	                    $saldo=$infosaldos[2];
	                    $saldo=number_format((float)$saldo, 2, ',', '.');
	                    if ($total!=$saldo) {
	                        $colorletra="red";
	                        $cerror++;
	                        array_push($cuentaserror, $infosaldos[0]);
	                    }
	                    if(sizeof($elementos_codigo)==3){
	                        $colorletra="black";
	                    }
	                    if ($saldo==0) $saldo="-";
	                    $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$saldo.$boldf.'</font></td>';
	                    $datos_tabla.='</tr>';
	                }
	                else if (sizeof($elementos_codigo)==4)
	                {
	                    $datos_tabla.='<tr bgcolor="'.$colornivel4.'" class="nivel'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'" style="display:none">';
	                    $datos_tabla.='<td width="9%" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[0].$boldf.'</td>';
	                    $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$infosaldos[1].$boldf.'</td>';
	                    $datos_tabla.='<td width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
	                    $saldo=$infosaldos[2];
	                    $saldo=number_format((float)$saldo, 2, ',', '.');
	                    if ($saldo==0) $saldo="-";
	                    $datos_tabla.='<td width="15%" style="text-align: right;  font-size: '.$tdfont.';">'.$boldi.$saldo.$boldf.'</td>';
	                    $datos_tabla.='</tr>';
	                }
	            }
	            
	            $datos_tabla.= "</table>";
	            if($cerror>0)
	            {
	                $usu="";
	                if(sizeof($cuentaserror)>1)
	                {
	                    $usu="cuentas";
	                }
	                else
	                {
	                    $usu="cuenta";
	                }
	                
	                $datos_tabla.='<li class="dropdown messages-menu">';
	                $datos_tabla.='<button type="button" class="btn btn-warning" data-toggle="dropdown">';
	                $datos_tabla.='<i class="glyphicon glyphicon-list"></i>';
	                $datos_tabla.='</button>';
	                $datos_tabla.='<span class="label label-danger">'.sizeof($cuentaserror).'</span>';
	                $datos_tabla.='<ul class="dropdown-menu scrollable-menu">';
	                $datos_tabla.='<li  class="header">Hay '.sizeof($cuentaserror).' '.$usu.' con advertencias.</li>';
	                $datos_tabla.='<li>';
	                $datos_tabla.= '<table style = "width:100%; border-collapse: collapse;" border="1">';
	                $datos_tabla.='<tbody>';
	                foreach ($cuentaserror as $us)
	                {
	                    
	                    
	                    $datos_tabla.='<tr height = "25">';
	                    $datos_tabla.='<td bgcolor="#F5F5F5" style="font-size: 16px; text-align:center;">'.$us.'</td>';
	                    $datos_tabla.='</tr>';
	                    
	                }
	                $datos_tabla.='</tbody>';
	                $datos_tabla.='</table>';
	                $datos_tabla.='</ul>';
	                $datos_tabla.='</li>';
	                
	                $datos_tabla.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	                $datos_tabla.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	                $datos_tabla.='<h4>Aviso!!!</h4> <b>Actualmente no se puede generar un reporte debido a errores en el balance de cuentas...</b>';
	                $datos_tabla.='</div>';
	                
	            }
	            else
	            {
	                
	                $datos_tabla.= '<div class="row">
	                    
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <a href="index.php?controller=B17&action=DescargarReporte&id_cabecera=" target="_blank"><i class="glyphicon glyphicon-print"></i></a>
                </div>
             </div>
            </div>';
	            }
	       }
	       echo $datos_tabla;
	      }
	    
	}

	public function DescargarReporte()
	{
	    session_start();
	    
	    $plan_cuentas= new PlanCuentasModel();
	    
	    
	    $columnas = "plan_cuentas.codigo_plan_cuentas, ";
	    
	    $tablas= "public.plan_cuentas INNER JOIN public.estado
                   ON plan_cuentas.id_estado_reporte = estado.id_estado";
	    
	    $where= "estado.nombre_estado='INCLUIDO'";
	    
	    $id= "plan_cuentas.codigo_plan_cuentas";
	    
	    $my_file = 'B17M344.txt';
	    header('Content-type: text/plain');
	    header('Content-Length: '.filesize($my_file));
	    header('Content-Disposition: attachment; filename='.$my_file);
	    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
	    
	    
	    $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $total=0;
	    
	    $i=0;
	    
	    foreach($resultSet as $res)
	    {
	     $total+=$res->saldo_plan_cuentas;
	     $i++;
	    }
	    
	    $total=number_format((float)$total, 2, '.', '');
	    $data = 'B17'."\t".'3441'."\t".'30/04/2019'."\t".$i."\t".$total.PHP_EOL;
	    
	    foreach($resultSet as $res)
	    {
	        $elementos= explode(".",$res->codigo_plan_cuentas);
	        $codigo="";
	        foreach ($elementos as $elem)
	        {
	         $codigo.=$elem;   
	        }
	        $saldo=number_format((float)$res->saldo_plan_cuentas, 2, '.', '');
	        $data.=$codigo."\t".$saldo.PHP_EOL;
	    }
	    
	    fwrite($handle, $data);
	    
	    if (file_exists($my_file)) {
	        header('Content-Description: File Transfer');
	        header('Content-Type: application/octet-stream');
	        header('Content-Disposition: attachment; filename="'.basename($my_file).'"');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate');
	        header('Pragma: public');
	        header('Content-Length: ' . filesize($my_file));
	        readfile($my_file);
	        exit;  
	    }
	}
	
	
}
?>