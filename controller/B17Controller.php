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
	
	public function CargarReporte()
	{
	    session_start();
	    
	    $plan_cuentas= new PlanCuentasModel();
	    
	    
	    $columnas = "codigo_plan_cuentas, nombre_plan_cuentas, saldo_plan_cuentas, nivel_plan_cuentas";
	    
	    $tablas= "public.plan_cuentas INNER JOIN public.estado
                  ON plan_cuentas.id_estado_reporte = estado.id_estado";
	    
	    $where= "estado.nombre_estado = 'INCLUIDO'";
	    
	    $id= "plan_cuentas.codigo_plan_cuentas";
	    
	    $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $headerfont="16px";
	    $tdfont="14px";
	    $boldi="";
	    $boldf="";
	    
	    $colornivel1="#D6EAF8";
	    $colornivel2="#D1F2EB  ";
	    $colornivel3="#FCF3CF";
	    $colornivel4="#FDFEFE";
	    
	    
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
	    $i=0;
	    $sumatotal=0;
	    
	    
	    foreach ($resultSet as $res)
	    {
	        $i++;
	        $sumatotal+=$res->saldo_plan_cuentas;
	        $colorletra="black";
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
	        
	        
	            if ($res->nombre_plan_cuentas=="PASIVOS") $pasivos=$res->saldo_plan_cuentas;
	            if ($res->nombre_plan_cuentas=="PATRIMONIO") $patrimonio=$res->saldo_plan_cuentas;
	            if ($res->nombre_plan_cuentas=="ACTIVOS") $activos=$res->saldo_plan_cuentas;
	            if (sizeof($elementos_codigo)==1 || (sizeof($elementos_codigo)==2 && $elementos_codigo[1]==""))
	            {
	            $total=0;    
	            foreach ($resultSet as $res1)
	            {
	                $elementos1_codigo=explode(".", $res1->codigo_plan_cuentas);
	             if ($res->codigo_plan_cuentas!=$res1->codigo_plan_cuentas && $res1->nivel_plan_cuentas==2
	                 && $elementos1_codigo[0]==$elementos_codigo[0])
	                 {
	                  $total+=$res1->saldo_plan_cuentas;   
	                 }
	            }
	            
	            $datos_tabla.='<tr >';
	            $datos_tabla.='<td bgcolor="'.$colornivel1.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
	            $datos_tabla.='<td bgcolor="'.$colornivel1.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$elementos_codigo[0].'&quot;,&quot;trbt'.$elementos_codigo[0].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].'" class="fa fa-plus" name="boton"></i></button>'.$boldi.$res->nombre_plan_cuentas.$boldf.'
                </td>';
	            $total=number_format((float)$total, 2, ',', '.');
	            $datos_tabla.='<td  bgcolor="'.$colornivel1.'"style="text-align: center;  font-size: '.$tdfont.';"></td>';
	            $saldo=$res->saldo_plan_cuentas;
	            $saldo=number_format((float)$saldo, 2, ',', '.');
	            if ($total!=$saldo) $colorletra="red";
	            if ($saldo==0) $saldo="-";
	            $datos_tabla.='<td  bgcolor="'.$colornivel1.'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$saldo.$boldf.'</font></td>';
	            $datos_tabla.='</tr>';
	            }
	            else if (sizeof($elementos_codigo)==2 || (sizeof($elementos_codigo)==3 && $elementos_codigo[2]==""))
	            {
	                $total=0;
	                foreach ($resultSet as $res1)
	                {
	                    $elementos1_codigo=explode(".", $res1->codigo_plan_cuentas);
	                    if ($res->codigo_plan_cuentas!=$res1->codigo_plan_cuentas && $res1->nivel_plan_cuentas==3
	                        && $elementos1_codigo[0]==$elementos_codigo[0] && $elementos1_codigo[1]==$elementos_codigo[1])
	                    {
	                        $total+=$res1->saldo_plan_cuentas;
	                    }
	                }
	                $total=number_format((float)$total, 2, ',', '.');
	                $datos_tabla.='<tr  class="nivel'.$elementos_codigo[0].'" style="display:none">';
	                $datos_tabla.='<td bgcolor="'.$colornivel2.'"  style="  text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
	                if (sizeof($elementos_codigo)==3 && $elementos_codigo[2]=="")
	                {
	                $datos_tabla.='<td bgcolor="'.$colornivel2.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla2(&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].'&quot;,&quot;trbt'.$elementos_codigo[0].$elementos_codigo[1].'&quot;,&quot;nivel'.$elementos_codigo[0].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].$elementos_codigo[1].'" class="fa fa-plus" name="boton1"></i></button>'.$boldi.$res->nombre_plan_cuentas.$boldf.'
                    </td>';
	                }
	                else
	                {
	                    $datos_tabla.='<td bgcolor="'.$colornivel2.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->nombre_plan_cuentas.$boldf.'
                    </td>';
	                }
	                $datos_tabla.='<td  bgcolor="'.$colornivel2.'" width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
	                $saldo=$res->saldo_plan_cuentas;
	                $saldo=number_format((float)$saldo, 2, ',', '.');
	                if ($total!=$saldo) $colorletra="red";
	                if(sizeof($elementos_codigo)==2) $colorletra="black";
	                if ($saldo==0) $saldo="-";
	                $datos_tabla.='<td  bgcolor="'.$colornivel2.'"  style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$saldo.$boldf.'</font></td>';
	                $datos_tabla.='</tr>';
	            }
	            else if (sizeof($elementos_codigo)==3 || (sizeof($elementos_codigo)==4 && $elementos_codigo[3]==""))
	            {
	                $total=0;
	                foreach ($resultSet as $res1)
	                {
	                    $elementos1_codigo=explode(".", $res1->codigo_plan_cuentas);
	                    if ($res->codigo_plan_cuentas!=$res1->codigo_plan_cuentas && $res1->nivel_plan_cuentas==4
	                        && $elementos1_codigo[0]==$elementos_codigo[0] && $elementos1_codigo[1]==$elementos_codigo[1]
	                        && $elementos1_codigo[2]==$elementos_codigo[2])
	                    {
	                        $total+=$res1->saldo_plan_cuentas;
	                    }
	                }
	                $total=number_format((float)$total, 2, ',', '.');
	                $datos_tabla.='<tr  class="nivel'.$elementos_codigo[0].$elementos_codigo[1].'" style="display:none">';
	                $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
	                if (sizeof($elementos_codigo)==4 && $elementos_codigo[3]=="")
	                {
	                    $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla3(&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'&quot;,&quot;trbt'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'&quot;,&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'" class="fa fa-plus" name="boton2"></i></button>'.$boldi.$res->nombre_plan_cuentas.$boldf.'
                    </td>';
	                }
	                else
	                {
	                    $datos_tabla.='<td bgcolor="'.$colornivel3.'"  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->nombre_plan_cuentas.$boldf.'</td>';
	                }
	                $datos_tabla.='<td bgcolor="'.$colornivel3.'"  style="text-align: center;  font-size: '.$tdfont.';"></td>';
	                $saldo=$res->saldo_plan_cuentas;
	                $saldo=number_format((float)$saldo, 2, ',', '.');
	                if ($total!=$saldo) $colorletra="red";
	                if(sizeof($elementos_codigo)==3) $colorletra="black";
	                if ($saldo==0) $saldo="-";
	                $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$saldo.$boldf.'</font></td>';
	                $datos_tabla.='</tr>';
	            }
	            else if (sizeof($elementos_codigo)==4)
	            {
	                $datos_tabla.='<tr bgcolor="'.$colornivel4.'" class="nivel'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'" style="display:none">';
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
	
	    $datos_tabla.= "</table>";
	    $datos_tabla.= "<h4>".$i."</h4>";
	    $sumatotal=number_format((float)$sumatotal, 2, '.', '');
	    $datos_tabla.= "<h4>Suma Total: ".$sumatotal."</h4>";
	    
	    $datos_tabla.= '<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <a href="index.php?controller=B17&action=DescargarReporte" target="_blank"><i class="glyphicon glyphicon-print"></i></a>
                </div>
             </div>	    
            </div>';
	    
	    echo $datos_tabla;
	    
	
	}
	
	public function CargarReporte2()
	{
	    session_start();
	    
	    $plan_cuentas= new PlanCuentasModel();
	    
	    
	    $columnas = "codigo_plan_cuentas, nombre_plan_cuentas, saldo_plan_cuentas, nivel_plan_cuentas, id_plan_cuentas";
	    
	    $tablas= "public.plan_cuentas INNER JOIN public.estado
                  ON plan_cuentas.id_estado_reporte = estado.id_estado";
	    
	    $where= "estado.nombre_estado = 'INCLUIDO'";
	    
	    $id= "plan_cuentas.codigo_plan_cuentas";
	    
	    $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $columnas = "id_plan_cuentas, fecha_mayor, saldo_mayor";
	    
	    $tablas= "public.con_mayor";
	    
	    $where= "con_mayor.fecha_mayor BETWEEN '2019-03-01' AND '2019-03-31'";
	    
	    $id= "con_mayor.fecha_mayor";
	    
	    $resultMayor=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $headerfont="16px";
	    $tdfont="14px";
	    $boldi="";
	    $boldf="";
	    
	    $colornivel1="#D6EAF8";
	    $colornivel2="#D1F2EB  ";
	    $colornivel3="#FCF3CF";
	    $colornivel4="#FDFEFE";
	    
	    
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
	    $i=0;
	    $sumatotal=0;
	    
	    
	    foreach ($resultSet as $res)
	    {
	        $sumacon=0;
	        $i++;
	        $sumatotal+=$res->saldo_plan_cuentas;
	        $colorletra="black";
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
	        
	        
	        if ($res->nombre_plan_cuentas=="PASIVOS") $pasivos=$res->saldo_plan_cuentas;
	        if ($res->nombre_plan_cuentas=="PATRIMONIO") $patrimonio=$res->saldo_plan_cuentas;
	        if ($res->nombre_plan_cuentas=="ACTIVOS") $activos=$res->saldo_plan_cuentas;
	        if (sizeof($elementos_codigo)==1 || (sizeof($elementos_codigo)==2 && $elementos_codigo[1]==""))
	        {
	            $total=0;
	            foreach ($resultMayor as $res1)
	            {
	              if($res1->id_plan_cuentas==$res->id_plan_cuentas)
	              {
	                 $sumacon+=$res1->saldo_mayor; 
	              }
	               
	            }
	            
	            $datos_tabla.='<tr >';
	            $datos_tabla.='<td bgcolor="'.$colornivel1.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
	            $datos_tabla.='<td bgcolor="'.$colornivel1.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla(&quot;nivel'.$elementos_codigo[0].'&quot;,&quot;trbt'.$elementos_codigo[0].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].'" class="fa fa-plus" name="boton"></i></button>'.$boldi.$res->nombre_plan_cuentas.$boldf.'
                </td>';
	            $total=number_format((float)$total, 2, ',', '.');
	            $datos_tabla.='<td  bgcolor="'.$colornivel1.'"style="text-align: center;  font-size: '.$tdfont.';"></td>';
	            $saldo=$res->saldo_plan_cuentas;
	            $sumacon=number_format((float)$sumacon, 2, ',', '.');
	            //if ($total!=$saldo) $colorletra="red";
	            if ($sumacon==0) $sumacon="-";
	            $datos_tabla.='<td  bgcolor="'.$colornivel1.'" style="text-align: right;  font-size: '.$tdfont.';"><font color="'.$colorletra.'">'.$boldi.$sumacon.$boldf.'</font></td>';
	            $datos_tabla.='</tr>';
	        }
	        else if (sizeof($elementos_codigo)==2 || (sizeof($elementos_codigo)==3 && $elementos_codigo[2]==""))
	        {
	            $total=0;
	            foreach ($resultMayor as $res1)
	            {
	                if($res1->id_plan_cuentas==$res->id_plan_cuentas)
	                {
	                    $sumacon+=$res1->saldo_mayor;
	                }
	                
	            }
	            $total=number_format((float)$total, 2, ',', '.');
	            $datos_tabla.='<tr  class="nivel'.$elementos_codigo[0].'" style="display:none">';
	            $datos_tabla.='<td bgcolor="'.$colornivel2.'"  style="  text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
	            if (sizeof($elementos_codigo)==3 && $elementos_codigo[2]=="")
	            {
	                $datos_tabla.='<td bgcolor="'.$colornivel2.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla2(&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].'&quot;,&quot;trbt'.$elementos_codigo[0].$elementos_codigo[1].'&quot;,&quot;nivel'.$elementos_codigo[0].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].$elementos_codigo[1].'" class="fa fa-plus" name="boton1"></i></button>'.$boldi.$res->nombre_plan_cuentas.$boldf.'
                    </td>';
	            }
	            else
	            {
	                $datos_tabla.='<td bgcolor="'.$colornivel2.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->nombre_plan_cuentas.$boldf.'
                    </td>';
	            }
	            $datos_tabla.='<td  bgcolor="'.$colornivel2.'" width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
	            $saldo=$res->saldo_plan_cuentas;
	            //if ($total!=$saldo) $colorletra="red";
	            if(sizeof($elementos_codigo)==2) $colorletra="black";
	            $sumacon=number_format((float)$sumacon, 2, ',', '.');
	            if ($sumacon==0) $sumacon="-";
	            $datos_tabla.='<td width="15%" bgcolor="'.$colornivel2.'" style="text-align: right;  font-size: '.$tdfont.';">'.$boldi.$sumacon.$boldf.'</td>';$datos_tabla.='</tr>';
	        }
	        else if (sizeof($elementos_codigo)==3 || (sizeof($elementos_codigo)==4 && $elementos_codigo[3]==""))
	        {
	            $total=0;
	            foreach ($resultMayor as $res1)
	            {
	                if($res1->id_plan_cuentas==$res->id_plan_cuentas)
	                {
	                    $sumacon+=$res1->saldo_mayor;
	                }
	                
	            }
	            $total=number_format((float)$total, 2, ',', '.');
	            $datos_tabla.='<tr  class="nivel'.$elementos_codigo[0].$elementos_codigo[1].'" style="display:none">';
	            $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
	            if (sizeof($elementos_codigo)==4 && $elementos_codigo[3]=="")
	            {
	                $datos_tabla.='<td bgcolor="'.$colornivel3.'" style="text-align: left;  font-size: '.$tdfont.';"><button type="button" class="btn btn-box-tool" onclick="ExpandirTabla3(&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'&quot;,&quot;trbt'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'&quot;,&quot;nivel'.$elementos_codigo[0].$elementos_codigo[1].'&quot;)">
                  <i id="trbt'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'" class="fa fa-plus" name="boton2"></i></button>'.$boldi.$res->nombre_plan_cuentas.$boldf.'
                    </td>';
	            }
	            else
	            {
	                $datos_tabla.='<td bgcolor="'.$colornivel3.'"  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->nombre_plan_cuentas.$boldf.'</td>';
	            }
	            $datos_tabla.='<td bgcolor="'.$colornivel3.'"  style="text-align: center;  font-size: '.$tdfont.';"></td>';
	            $saldo=$res->saldo_plan_cuentas;
	            //if ($total!=$saldo) $colorletra="red";
	            if(sizeof($elementos_codigo)==3) $colorletra="black";
	            $sumacon=number_format((float)$sumacon, 2, ',', '.');
	            if ($sumacon==0) $sumacon="-";
	            $datos_tabla.='<td width="15%" bgcolor="'.$colornivel3.'" style="text-align: right;  font-size: '.$tdfont.';">'.$boldi.$sumacon.$boldf.'</td>';$datos_tabla.='</tr>';
	        }
	        else if (sizeof($elementos_codigo)==4)
	        {
	            foreach ($resultMayor as $res1)
	            {
	                if($res1->id_plan_cuentas==$res->id_plan_cuentas)
	                {
	                    $sumacon+=$res1->saldo_mayor;
	                }
	                
	            }
	            $datos_tabla.='<tr bgcolor="'.$colornivel4.'" class="nivel'.$elementos_codigo[0].$elementos_codigo[1].$elementos_codigo[2].'" style="display:none">';
	            $datos_tabla.='<td width="9%" style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->codigo_plan_cuentas.$boldf.'</td>';
	            $datos_tabla.='<td  style="text-align: left;  font-size: '.$tdfont.';">'.$boldi.$res->nombre_plan_cuentas.$boldf.'</td>';
	            $datos_tabla.='<td width="10%" style="text-align: center;  font-size: '.$tdfont.';"></td>';
	            $saldo=$res->saldo_plan_cuentas;
	            $sumacon=number_format((float)$sumacon, 2, ',', '.');
	            if ($sumacon==0) $sumacon="-";
	            $datos_tabla.='<td width="15%" style="text-align: right;  font-size: '.$tdfont.';">'.$boldi.$sumacon.$boldf.'</td>';
	            $datos_tabla.='</tr>';
	        }
	    }
	    
	    $datos_tabla.= "</table>";
	    $datos_tabla.= "<h4>".$i."</h4>";
	    $sumatotal=number_format((float)$sumatotal, 2, '.', '');
	    $datos_tabla.= "<h4>Suma Total: ".$sumatotal."</h4>";
	    
	    $datos_tabla.= '<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <a href="index.php?controller=B17&action=DescargarReporte" target="_blank"><i class="glyphicon glyphicon-print"></i></a>
                </div>
             </div>
            </div>';
	    
	    echo $datos_tabla;
	    
	    
	}

	public function DescargarReporte()
	{
	    session_start();
	    
	    $plan_cuentas= new PlanCuentasModel();
	    
	    
	    $columnas = "codigo_plan_cuentas, nombre_plan_cuentas, saldo_plan_cuentas, nivel_plan_cuentas";
	    
	    $tablas= "public.plan_cuentas INNER JOIN public.estado
                  ON plan_cuentas.id_estado_reporte = estado.id_estado";
	    
	    $where= "estado.nombre_estado = 'INCLUIDO'";
	    
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