<?php

class BalanceComprobacionController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        session_start();
        $this->view_Contable('BalanceComprobacion',array());
    }
    
   
    public function buscaperiodo(){
        
        session_start();
        
        $_id_usuarios = $_SESSION['id_usuarios'];
        
        $usuarios = new UsuariosModel();
        
        $query = "SELECT id_periodo,anio_periodo,mes_periodo,nombre_estado
                FROM public.con_periodo 
                INNER JOIN public.estado ON estado.id_estado = con_periodo.id_estado";
        
        $resultSet = $usuarios->enviaquery($query);
        
        $respuesta=array();
        
        if(!empty($resultSet) && count($resultSet)>0){
            $respuesta=array('mensaje'=>'1');
            $datos = array();
            foreach ($resultSet as $res){
                $datos[] = $res;
            }
            $respuesta['datos']=$datos;
        }else{
            $respuesta=array('mensaje'=>'0');
           
        }
        
        echo json_encode($respuesta);
        //print_r($resultSet);
    }
    
    /***
     * desc: funcion que genera balance
     */
    public function generarbalance(){
        
        $usuarios = new UsuariosModel();
        
        
        if(isset($_POST['ajax'])){
            
            session_start();
            
            $id_usuarios = $_SESSION['id_usuarios'];
            
            $_anio_balance = $_POST['anio_balance'];
            $_mes_balance = $_POST['mes_balance'];
            
            //array que devuelve a la vista
            $respuesta = array();
                       
            //generacion balance
            //realiza llamada de funcion con devolucion de datos
            $queryfuncion = "SELECT ins_balance_comprobacion('$id_usuarios','$_anio_balance','$_mes_balance')";
            $resultfuncion = $usuarios->enviaquery($queryfuncion);
            
            $respuestafuncion = 0;
            
            if(!empty($resultfuncion) && count($resultfuncion)>0){
                foreach ($resultfuncion[0] as $k => $v){
                    $respuestafuncion=$v;
                }
            }
            
            if($respuestafuncion==1){
                
                $respuesta['mensaje']=1;
                
                $querycabecera = "SELECT entidades.id_entidades, 
                                	entidades.nombre_entidades, 
                                	con_cbalance_comprobacion.id_cbalance_comprobacion,
                                	con_cbalance_comprobacion.anio_cbalance_comprobacion,
                                	con_cbalance_comprobacion.mes_cbalance_comprobacion
                                FROM con_cbalance_comprobacion 
                                INNER JOIN entidades ON con_cbalance_comprobacion.id_entidades = entidades.id_entidades
                                WHERE con_cbalance_comprobacion.anio_cbalance_comprobacion = '$_anio_balance'
                                AND con_cbalance_comprobacion.mes_cbalance_comprobacion = $_mes_balance";
                
                $resultcabecera = $usuarios->enviaquery($querycabecera);
                
                $id_cabecera = 0;
                
                if(!empty($resultcabecera) && count($resultcabecera)>0){
                    
                    $respuesta['cabecera']=$resultcabecera[0];
                    
                    $id_cabecera= $resultcabecera[0]->id_cbalance_comprobacion;
                    
                }
                
               
                $querydetalle = "SELECT plan_cuentas.id_plan_cuentas,plan_cuentas.codigo_plan_cuentas,
                        plan_cuentas.nivel_plan_cuentas,plan_cuentas.nombre_plan_cuentas, 
                        id_dbalance_comprobacion, suma_debe_dbalance_comprobacion, 
                        suma_haber_dbalance_comprobacion, saldo_deudor_dbalance_comprobacion, saldo_acreedor_dbalance_comprobacion                        
                        FROM plan_cuentas 
                        INNER JOIN con_dbalance_comprobacion ON con_dbalance_comprobacion.id_plan_cuentas = plan_cuentas.id_plan_cuentas
                        WHERE id_cbalance_comprobacion = '$id_cabecera' ORDER BY plan_cuentas.codigo_plan_cuentas";
                
                $resultdetalle = $usuarios->enviaquery($querydetalle);
                
                if(!empty($resultdetalle) && count($resultdetalle)>0){
                    
                    $respuesta['detalle']=$resultdetalle;
                    
                    
                }
                
                $querytotales = 'SELECT SUM(suma_debe_dbalance_comprobacion) "totaldebe",
                        SUM(suma_haber_dbalance_comprobacion) "totalhaber",
                        SUM(saldo_acreedor_dbalance_comprobacion) "saldoa",
                        SUM(saldo_deudor_dbalance_comprobacion) "saldod"
                        FROM con_dbalance_comprobacion
                        WHERE id_cbalance_comprobacion ='.$id_cabecera;
                
                $resulttotales = $usuarios->enviaquery($querytotales);
                
                if(!empty($resulttotales) && count($resulttotales)>0){
                    
                    $respuesta['totales']=$resulttotales[0];
                    
                    
                }
                
            }
            
          echo json_encode($respuesta);
            
        }else{
            //$this->redirect('BalanceComprobacion','index'); 
            echo 'njo ajax';
        }
    }

    
  
    
    
    
    public function paginate_bodegas_inactivos($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_bodegas_inactivos(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_bodegas_inactivos(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_bodegas_inactivos(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_bodegas_inactivos(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_bodegas_inactivos(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_bodegas_inactivos($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_bodegas_inactivos(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    public function GenerarReporte()
    {
        session_start();
        
        $plan_cuentas= new PlanCuentasModel();
       
        
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
        $datos_tabla.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="center"><b>AL {FECHA}</b></td>';
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
        $datos_tabla2.='<td colspan="4" class="cabeza" style="font-size: 12px; padding-left : 8px;" align="center"><b>AL {FECHA}</b></td>';
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