<?php

class ReporteDescuentosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
     
    
    public function reporte_aportes(){
        session_start();
        $entidades = new EntidadesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
        
        
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
        
        
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
        
         
        $descuentosaportes=new DescuentosAportesModel();
        $datos_reporte = array();
        
        //////retencion detalle
        
        $id_descuentos_registrados_cabeza = $_GET['id_cabeza_descuentos'];
        
        
        $columnas1 = "a.id_entidad_patronal, a.year_descuentos_registrados_cabeza, a.mes_descuentos_registrados_cabeza, b.nombre_entidad_patronal";
        $tablas1   = "core_descuentos_registrados_cabeza a inner join core_entidad_patronal b on a.id_entidad_patronal = b.id_entidad_patronal";
        $where1    = "a.id_descuentos_registrados_cabeza = $id_descuentos_registrados_cabeza";
        $id1    = "a.id_descuentos_registrados_cabeza";
        $resultSet=$descuentosaportes->getCondiciones($columnas1, $tablas1, $where1, $id1);
        
        $id_entidad_patronal=$resultSet[0]->id_entidad_patronal;
        $anio=$resultSet[0]->year_descuentos_registrados_cabeza;
        $mes=$resultSet[0]->mes_descuentos_registrados_cabeza;
        $nombre_entidad_patronal=$resultSet[0]->nombre_entidad_patronal;
        
        $mes_reporte=$meses[($mes)-1];
         
        
      $query = "
        select pp.cedula, pp.nombre, pp.aporte_personal, pp.aporte_patronal,
        pp.rmu, pp.liquido, pp.multas, pp.antiguedad, pp.procesado

		from (
	
		select b.cedula_participes as cedula, b.apellido_participes || ' ' || b.nombre_participes as nombre, 
		a.aporte_personal_descuentos_registrados_detalle_aportes aporte_personal, 
		a.aporte_patronal_descuentos_registrados_detalle_aportes aporte_patronal,
		a.rmu_descuentos_registrados_detalle_aportes rmu, 
		a.liquido_descuentos_registrados_detalle_aportes liquido, 
		a.multas_descuentos_registrados_detalle_aportes multas,
		a.antiguedad_descuentos_registrados_detalle_aportes antiguedad,
		a.procesado_descuentos_registrados_detalle_aportes procesado
		from core_descuentos_registrados_detalle_aportes a 
		inner join core_participes b 
		on a.id_participes = b.id_participes and b.id_estatus <>0
		where 
		
		a.id_entidad_patronal = $id_entidad_patronal
		and 
		year_descuentos_registrados_detalle_aportes = $anio and mes_descuentos_registrados_detalle_aportes = $mes
		and id_descuentos_registrados_cabeza = $id_descuentos_registrados_cabeza
		
		union 
		
		select pt.cedula_participes as cedula, 
		rtrim(ltrim(pt.apellido_participes)) || ' ' || rtrim(ltrim(pt.nombre_participes)) as nombre,
		coalesce (ct.valor_personal_contribucion, 0) aporte_personal, 
		coalesce (ct.valor_patronal_contribucion, 0) aporte_patronal,
		'0' rmu, 
		'0' liquido,
		'0' multas,
		'0' antiguedad,
		'1' procesado
		from core_participes pt
		inner join core_contribucion ct 
		on pt.id_participes = ct.id_participes and ct.id_estado_contribucion <>0 and ct.id_estatus <>0
		where pt.id_entidad_patronal = $id_entidad_patronal and to_char (ct.fecha_registro_contribucion,'YYYY') = '$anio' 
		and to_char (ct.fecha_registro_contribucion,'MM') = '$mes' and
		ct.descripcion_contribucion like 'CXC_EntPat.:%'
		) pp 
        order by pp.nombre asc
        ";      
      $desceuntos_aportes_personales = $descuentosaportes->enviaquery($query);
      
      //print_r($desceuntos_aportes_personales); exit();
       
        $datos_reporte['ENTIDAD_PATRONAL']=$nombre_entidad_patronal;
        $datos_reporte['ANIO']=$anio;
        $datos_reporte['MES']=$mes_reporte;
        
        $html='';
        
        
        $html.='<table class="1" border=1>';
        $html.='<tr>';
        $html.='<th>N°</th>';
        $html.='<th>Cédula</th>';
        $html.='<th>Nombre</th>';
        $html.='<th>Aporte Personal</th>';
        $html.='<th>Aporte Patronal</th>';
        $html.='<th>RMU</th>';
        $html.='<th>Líquido</th>';
        $html.='<th>Multas</th>';
        $html.='<th>Antiguedad</th>';
        $html.='<th>Procesado</th>';
       
        $html.='</tr>';
        
        $procesado_total = "";
        $aporte_personal_total = "";
        $aporte_patronal_total = "";
        $aporte_rmu_total = "";
        $aporte_liquido_total = "";
        $aporte_multas_total = "";
        $aporte_antiguedad_total = "";
        
        $i=0;
        foreach ($desceuntos_aportes_personales as $res)
        {
           
            $aporte_personal_total = $aporte_personal_total+$res->aporte_personal;
            $aporte_patronal_total = $aporte_patronal_total+$res->aporte_patronal;
            $aporte_rmu_total = $aporte_rmu_total+$res->rmu;
            $aporte_liquido_total = $aporte_liquido_total+$res->liquido;
            $aporte_multas_total = $aporte_multas_total+$res->multas;
            $aporte_antiguedad_total = $aporte_antiguedad_total+$res->antiguedad;
            
            
            if($res->procesado == 't'){
                
                $procesado_total = 'SI';
            }
            else {
                
                $procesado_total = 'NO';
            }
            
            
            $i++;
            $html.='<tr >';
            $html.='<td>'.$i.'</td>';
            $html.='<td align="left";>'.$res->cedula.'</td>';
            $html.='<td align="left";>'.$res->nombre.'</td>';
            $html.='<td align="right";>'.$res->aporte_personal.'</td>';
            $html.='<td align="right";>'.$res->aporte_patronal.'</td>';
            $html.='<td align="right";>'.$res->rmu.'</td>';
            $html.='<td align="right";>'.$res->liquido.'</td>';
            $html.='<td align="right";>'.$res->multas.'</td>';
            $html.='<td align="right";>'.$res->antiguedad.'</td>';
            $html.='<td align="center";>'.$procesado_total.'</td>';
            
            
            $html.='</td>';
            $html.='</tr>';
        }
        
        $aporte_personal_total = $aporte_personal_total+$res->aporte_personal;
        $aporte_patronal_total = $aporte_patronal_total+$res->aporte_patronal;
        $aporte_rmu_total = $aporte_rmu_total+$res->rmu;
        $aporte_liquido_total = $aporte_liquido_total+$res->liquido;
        $aporte_multas_total = $aporte_multas_total+$res->multas;
        $aporte_antiguedad_total = $aporte_antiguedad_total+$res->antiguedad;
        
        
        
        $html.='<tr >';
        $html.='<td></td>';
        $html.='<td align="left";></td>';
        $html.='<td align="right";><b>TOTAL</b></td>';
        $html.='<td align="right";><b>'.number_format($aporte_personal_total, 2, ",", ".").'</b></td>';
        $html.='<td align="right";><b>'.number_format($aporte_patronal_total, 2, ",", ".").'</b></td>';
        $html.='<td align="right";><b>'.number_format($aporte_rmu_total, 2, ",", ".").'</b></td>';
        $html.='<td align="right";><b>'.number_format($aporte_liquido_total, 2, ",", ".").'</b></td>';
        $html.='<td align="right";><b>'.number_format($aporte_multas_total, 2, ",", ".").'</b></td>';
        $html.='<td align="right";><b>'.number_format($aporte_antiguedad_total, 2, ",", ".").'</b></td>';
        $html.='<td align="left";></td>';
        
        
        
        $html.='</td>';
        $html.='</tr>';
        
        
        
        
        $html.='</table>'; 
        
        $datos_reporte['DETALLE_DESCUENTOS_APORTES']= $html;
        
        
        
        $this->verReporte("ReporteAportesRecibidos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
        
        
    }
    
    
    public function reporte_creditos(){
        session_start();
        $entidades = new EntidadesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
        
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
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
        
         
        $descuentosaportes=new DescuentosAportesModel();
        $datos_reporte = array();
        
        //////retencion detalle
        $id_descuentos_registrados_cabeza = $_GET['id_cabeza_descuentos'];
        
        
        $columnas1 = "a.id_entidad_patronal, a.year_descuentos_registrados_cabeza, a.mes_descuentos_registrados_cabeza, b.nombre_entidad_patronal";
        $tablas1   = "core_descuentos_registrados_cabeza a inner join core_entidad_patronal b on a.id_entidad_patronal = b.id_entidad_patronal";
        $where1    = "a.id_descuentos_registrados_cabeza = $id_descuentos_registrados_cabeza";
        $id1    = "a.id_descuentos_registrados_cabeza";
        $resultSet=$descuentosaportes->getCondiciones($columnas1, $tablas1, $where1, $id1);
        
        $id_entidad_patronal=$resultSet[0]->id_entidad_patronal;
        $anio=$resultSet[0]->year_descuentos_registrados_cabeza;
        $mes=$resultSet[0]->mes_descuentos_registrados_cabeza;
        $nombre_entidad_patronal=$resultSet[0]->nombre_entidad_patronal;
        
        $mes_reporte=$meses[($mes)-1];
        
        
        $query = "select 1 as id,
pcarga.cedula_participes as cedula,
pcarga.apellido_participes || ' ' || pcarga.nombre_participes as nombre,
coalesce(case when b.cedula_participes!=pcarga.cedula_participes then b.cedula_participes else '' end,''  ) as cedula_pago_otro,
coalesce(case when b.apellido_participes!=pcarga.apellido_participes then b.apellido_participes else '' end,'' ) || '' || coalesce (case when b.nombre_participes!=pcarga.nombre_participes then b.nombre_participes  else '' end ,'' )as nombre_pago_otro,
null as id_tipo_descuento,
null as nombre_tipo_descuento,
a.cuota_descuentos_registrados_detalle_creditos, 
a.year_descuentos_registrados_detalle_creditos,
a.mes_descuentos_registrados_detalle_creditos,
a.procesado_descuentos_registrados_detalle_creditos,
case when cty.nombre_tipo_creditos is null then 'INDEBIDO' else upper (cty.nombre_tipo_creditos) end as nombre_credito,
sum (coalesce(ctd.valor_transaccion_detalle, coalesce(drct.valor_cxp_descuentos_registrados_detalle_creditos_trans,0))) valor_nombre_credito,
drct.id_descuentos_registrados_detalle_creditos,
c.id_tipo_creditos,
cty.nombre_tipo_creditos,
ata.descripcion_tabla_amortizacion_parametrizacion as item_name,
drct.cxp_voucher_descuentos_registrados_detalle_creditos_trans,
'' as estado_cuenta_x_pagar,
drct.observacion_descuentos_registrados_detalle_creditos_trans,
case  when a.procesado_descuentos_registrados_detalle_creditos = 't' then 'SI' when a.procesado_descuentos_registrados_detalle_creditos = 'f' then 'NO' else '' end estado_procesado,
ata.id_tabla_amortizacion_parametrizacion
from core_descuentos_registrados_detalle_creditos_trans drct 
left outer join core_transacciones ct on drct.id_transacciones = ct.id_transacciones and ct.id_status = 1 and ct.id_estado_transacciones = 1
left outer join core_transacciones_detalle ctd on ctd.id_transacciones = ct.id_transacciones and ctd.id_status = 1
left outer join core_tabla_amortizacion_pagos aata on aata.id_tabla_amortizacion_pagos = ctd.id_tabla_amortizacion_pago
left outer join core_tabla_amortizacion_parametrizacion ata on aata.id_tabla_amortizacion_parametrizacion = ata.id_tabla_amortizacion_parametrizacion
left outer join core_creditos c on ct.id_creditos = c.id_creditos and c.id_estatus = 1
left outer join core_tipo_creditos cty on c.id_tipo_creditos = cty.id_tipo_creditos and cty.id_estatus = 1
left outer join core_descuentos_registrados_detalle_creditos a on a.id_descuentos_registrados_detalle_creditos = drct.id_descuentos_registrados_detalle_creditos
left outer join core_participes b on c.id_participes = b.id_participes
left outer join core_participes pcarga on pcarga.id_participes = a.id_participes
where a.id_descuentos_registrados_cabeza = $id_descuentos_registrados_cabeza
group by pcarga.nombre_participes, pcarga.apellido_participes, pcarga.cedula_participes, b.cedula_participes, b.apellido_participes, b.nombre_participes, a.cuota_descuentos_registrados_detalle_creditos, a.year_descuentos_registrados_detalle_creditos, a.mes_descuentos_registrados_detalle_creditos, a.procesado_descuentos_registrados_detalle_creditos,
cty.nombre_tipo_creditos, a.id_descuentos_registrados_detalle_creditos, c.id_tipo_creditos, drct.cxp_voucher_descuentos_registrados_detalle_creditos_trans,
drct.id_descuentos_registrados_detalle_creditos, c.id_tipo_creditos, drct.observacion_descuentos_registrados_detalle_creditos_trans,
ata.id_tabla_amortizacion_parametrizacion,  ata.descripcion_tabla_amortizacion_parametrizacion,
c.id_participes
union
select 1 as id, 
b.cedula_participes as cedula,
b.apellido_participes || ' ' || b.nombre_participes as nombre,
coalesce(case when b.cedula_participes != b.cedula_participes then b.cedula_participes else '' end, '') as cedula_pago_otro,
coalesce(case when b.apellido_participes != b.apellido_participes then b.apellido_participes else '' end, '') || ' ' || coalesce(case when b.nombre_participes != b.nombre_participes then b.nombre_participes else '' end, '') as nombre_pago_otro,
null as id_tipo_descuento, 
null as nombre_tipo_descuento, 
0 as cuota_descuentos_registrados_detalle_creditos, 
0 as year_descuentos_registrados_detalle_creditos,
0 as mes_descuentos_registrados_detalle_creditos,
't' as procesado_descuentos_registrados_detalle_creditos,
case when cty.nombre_tipo_creditos is null then 'INDEBIDO' else upper (cty.nombre_tipo_creditos) end as nombre_credito,
sum (coalesce (ctd.valor_transaccion_detalle,0)) as valor_nombre_credito,
0 as id_descuentos_registrados_detalle_creditos, 
c.id_tipo_creditos, 
cty.nombre_tipo_creditos, 
ata.descripcion_tabla_amortizacion_parametrizacion as item_name, 
'' as cxp_voucher_descuentos_registrados_detalle_creditos_trans,
'' as estado_cuenta_x_pagar,
'' as observacion_descuentos_registrados_detalle_creditos_trans,
'SI' estado_procesado,
 ata.id_tabla_amortizacion_parametrizacion
from core_transacciones ct 
left outer join core_transacciones_detalle ctd on ctd.id_transacciones = ct.id_transacciones and ctd.id_status = 1
left outer join core_tabla_amortizacion_pagos aata on aata.id_tabla_amortizacion_pagos = ctd.id_tabla_amortizacion_pago
left outer join core_tabla_amortizacion_parametrizacion ata on aata.id_tabla_amortizacion_parametrizacion = ata.id_tabla_amortizacion_parametrizacion
left outer join core_creditos c on ct.id_creditos = c.id_creditos and c.id_estatus = 1
left outer join core_tipo_creditos  cty on c.id_tipo_creditos = c.id_tipo_creditos and cty.id_estatus = 1
left outer join core_participes b on c.id_participes = b.id_participes
where b.id_entidad_patronal = $id_entidad_patronal and to_char (ct.fecha_transacciones,'YYYY') = '$anio' and to_char(ct.fecha_transacciones,'MM') = '$mes' 
and ct.id_modo_pago = 24 and ct.id_status  =1 and ct.id_estado_transacciones =1
group by b.cedula_participes, b.apellido_participes, b.nombre_participes,
cty.nombre_tipo_creditos, c.id_tipo_creditos,
ata.tipo_tabla_amortizacion_parametrizacion, ata.descripcion_tabla_amortizacion_parametrizacion,
c.id_participes, ata.id_tabla_amortizacion_parametrizacion
order by
nombre";
        

        
        $desceuntos_aportes_personales = $descuentosaportes->enviaquery($query);
        
        //var_dump( $desceuntos_aportes_personales ); die();
        
        
        
        $datos_reporte['ENTIDAD_PATRONAL']=$nombre_entidad_patronal;
        $datos_reporte['ANIO']=$anio;
        $datos_reporte['MES']=$mes_reporte;
        
        $html='';
        
        
        $html.='<table class="1" border=1>';
        $html.='<tr>';
        $html.='<th>N°</th>';
        $html.='<th>Cédula</th>';
        $html.='<th>Nombre</th>';
        $html.='<th>Garantizado</th>';
        $html.='<th>TIPO CREDITO</th>';
        $html.='<th>Capital</th>';
        $html.='<th>Interes</th>';
        $html.='<th>Mora</th>';
        $html.='<th>Seg. Degrav.</th>';
        $html.='<th>Total</th>';
        $html.='<th>CUOTA CARGARDA</th>';
        $html.='<th>CUOTA ARCHIVO</th>';
        $html.='<th>ID_CXP</th>';
        $html.='<th>ESTADO CXP</th>';
        $html.='<th>Procesado</th>';
        $html.='<th>Observ.</th>';
        $html.='</tr>';
        
       
        
        
        
        $i=0;
        foreach ($desceuntos_aportes_personales as $res)
        {
            
           
            
            
            $i++;
            $html.='<tr >';
            $html.='<td>'.$i.'</td>';
            $html.='<td align="left";>'.$res->cedula.'</td>';
            $html.='<td align="left";>'.$res->nombre.'</td>';
            $html.='<td align="right";>'.$res->nombre_pago_otro.'</td>';
            $html.='<td align="right";>'.$res->nombre_tipo_creditos.'</td>';
            $html.='<td align="right";></td>';
            $html.='<td align="right";></td>';
            $html.='<td align="right";></td>';
            $html.='<td align="right";></td>';
            $html.='<td align="right";></td>';
            $html.='<td align="right";>'.$res->cuota_descuentos_registrados_detalle_creditos.'</td>';
            $html.='<td align="right";>'.$res->cxp_voucher_descuentos_registrados_detalle_creditos_trans.'</td>';
            $html.='<td align="right";></td>';
            $html.='<td align="right";>'.$res->estado_procesado.'</td>';
            $html.='<td align="right";>'.$res->estado_procesado.'</td>';
            $html.='<td align="right";>'.$res->observacion_descuentos_registrados_detalle_creditos_trans.'</td>';
            $html.='</td>';
            $html.='</tr>';
        }
        
        
    
        
        
        $html.='</table>';
        
        $datos_reporte['DETALLE_DESCUENTOS_APORTES']= $html;
        
        
        
        $this->verReporte("ReporteDescuentosRecibidos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
        
        
    }
    
    
   
}
?>