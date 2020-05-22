<?php

class EstadoCuentaController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    public function Estado_Cuenta(){
        session_start();
        $entidades = new EntidadesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
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
        
        //NOTICE DATA
        $datos_cabecera = array();
        $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
        $datos_cabecera['FECHA'] = date('Y/m/d');
        $datos_cabecera['HORA'] = date('h:i:s');
        
        
        
        $creditos = new CoreCreditoModel();
        $id_creditos =  (isset($_REQUEST['id_creditos'])&& $_REQUEST['id_creditos'] !=NULL)?$_REQUEST['id_creditos']:'';
        
        $datos_reporte = array();
        
        $columnas = " core_participes.id_participes, 
                      core_participes.apellido_participes, 
                      core_participes.nombre_participes, 
                      core_participes.cedula_participes, 
                      core_participes.direccion_participes, 
                      core_participes.telefono_participes, 
                      core_entidad_patronal.id_entidad_patronal, 
                      core_entidad_patronal.nombre_entidad_patronal, 
                      core_creditos.numero_creditos, 
                      core_creditos.monto_otorgado_creditos, 
                      core_creditos.plazo_creditos, 
                      core_creditos.fecha_concesion_creditos, 
                      core_creditos.monto_neto_entregado_creditos, 
                      core_creditos.interes_creditos, 
                      core_creditos.id_creditos";
        
        $tablas = "public.core_participes, 
                  public.core_creditos, 
                  public.core_entidad_patronal";
        $where= "   core_creditos.id_participes = core_participes.id_participes AND
  core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND core_creditos.id_creditos='$id_creditos'";
        $id="core_participes.id_participes";
        
        $rsdatos = $creditos->getCondiciones($columnas, $tablas, $where, $id);
        
        
        $datos_reporte['CEDULA_PARTICIPE']=$rsdatos[0]->cedula_participes;
        $datos_reporte['NOMBRE_PARTICIPE']=$rsdatos[0]->nombre_participes;
        $datos_reporte['APELLIDO_PARTICIPE']=$rsdatos[0]->apellido_participes;
        $datos_reporte['DIRECCION_PARTICIPE']=$rsdatos[0]->direccion_participes;
        $datos_reporte['TELEFONO_PARTICIPE']=$rsdatos[0]->telefono_participes;
        $datos_reporte['ENTIDAD_PATRONAL']=$rsdatos[0]->nombre_entidad_patronal;
        $datos_reporte['NUMERO_CREDITO']=$rsdatos[0]->numero_creditos;
        $datos_reporte['MONTO_CREDITO']=$rsdatos[0]->monto_otorgado_creditos;
        $datos_reporte['PLAZO_CREDITO']=intval($rsdatos[0]->plazo_creditos);
        $datos_reporte['FECHA_CREDITO']=$rsdatos[0]->fecha_concesion_creditos;
        $datos_reporte['MONTO_OTORGADO_CREDITO']=$rsdatos[0]->monto_neto_entregado_creditos;
        $datos_reporte['INTERES_CREDITO']=intval($rsdatos[0]->interes_creditos);
        
       
        
        //detalle Credito
        
        
        
        $queryDetalle="select  pr.id_participes, concat(pr.apellido_participes,' ',pr.nombre_participes) as cliente,
                    pr.cedula_participes, pr.celular_participes,
                    COALESCE((select concat(pai.calle_participes_informacion_adicional,' ',pai.numero_calle_participes_informacion_adicional,' ',pai.interseccion_participes_informacion_adicional) from core_participes_informacion_adicional pai where pr.id_participes=pai.id_participes limit 1),'') as direccion_completa_participes,
                    ee.nombre_entidad_patronal,
                    cr.monto_otorgado_creditos,
                    cr.monto_neto_entregado_creditos,
                    cr.numero_solicitud_creditos,
                    cr.plazo_creditos,
                    cr.id_creditos,
                    cstate.nombre_estado_creditos,
                    cr.interes_creditos,
                    ct.nombre_tipo_creditos,
                    at.numero_pago_tabla_amortizacion,
                    case(at.id_estado_tabla_amortizacion) when '1' then 'P'  when '2' then 'C'  when '3' then 'PA' else cstate.nombre_estado_creditos end as estado_pago_cuota,
                    at.fecha_tabla_amortizacion,
                    at.capital_tabla_amortizacion,
                    at.interes_tabla_amortizacion,
                    coalesce(sum(case when atav.descripcion_tabla_amortizacion_parametrizacion not ilike '%Mora%' 
                    and atav.descripcion_tabla_amortizacion_parametrizacion not ilike '%DESGRAVAMEN%' 
                    and atav.descripcion_tabla_amortizacion_parametrizacion not ilike '%Administrativo%' 
                    and atav.descripcion_tabla_amortizacion_parametrizacion not ilike '%Interes%' 
                    and atav.descripcion_tabla_amortizacion_parametrizacion not ilike '%Capital%' 
                    then aatav.valor_pago_tabla_amortizacion_pagos else 0 end),0) as otros_valores,
                    at.total_balance_tabla_amortizacion,
                    at.capital_tabla_amortizacion+at.interes_tabla_amortizacion as total_value,
                    at.balance_tabla_amortizacion,
                    to_char(cobros.fecha_pago, 'YYYY-MM-DD') as fecha_pago,
                    cobros.valor,
                    cobros.capital_pagado,
                    cobros.interes_pagado,
                    cobros.otro_valor_pagado,
                    cobros.id_ccomprobantes_ant
                    from core_creditos cr 
                    inner join core_tabla_amortizacion at on cr.id_creditos=at.id_creditos
                    inner join core_participes pr on cr.id_participes=pr.id_participes
                    inner join core_tipo_creditos ct on cr.id_tipo_creditos=ct.id_tipo_creditos and ct.id_estatus=1
                    inner join core_estado_creditos cstate on cr.id_estado_creditos=cstate.id_estado_creditos
                    left join 
                    (
                    select aatav.id_tabla_amortizacion, max(ctrans.fecha_transacciones) as fecha_pago,
                    ctrans.id_ccomprobantes_ant, coalesce(sum(ctransdet.valor_transaccion_detalle),0) as valor,
                    sum(case when atav.descripcion_tabla_amortizacion_parametrizacion ilike '%Capital%' then ctransdet.valor_transaccion_detalle else 0 END) as capital_pagado,
                    sum(case when atav.descripcion_tabla_amortizacion_parametrizacion ilike '%Interes%' then ctransdet.valor_transaccion_detalle else 0 END) as interes_pagado,
                    sum(case when atav.descripcion_tabla_amortizacion_parametrizacion not ilike '%Capital%' and atav.descripcion_tabla_amortizacion_parametrizacion not ilike '%Interes%' then ctransdet.valor_transaccion_detalle else 0 END) as otro_valor_pagado
                    from core_transacciones ctrans
                    inner join core_transacciones_detalle ctransdet on ctrans.id_transacciones=ctransdet.id_transacciones
                    inner join core_tabla_amortizacion_pagos aatav on ctransdet.id_tabla_amortizacion_pago=aatav.id_tabla_amortizacion_pagos
                    inner join core_tabla_amortizacion_parametrizacion atav on aatav.id_tabla_amortizacion_parametrizacion=atav.id_tabla_amortizacion_parametrizacion
                    where ctrans.id_status=1 and ctrans.id_estado_transacciones=1 and ctransdet.id_status=1 and aatav.id_estatus=1 and ctrans.id_creditos='$id_creditos' and ctrans.valor_transacciones>0
                    group by ctrans.id_creditos, aatav.id_tabla_amortizacion, ctrans.id_ccomprobantes_ant
                    having sum(ctransdet.valor_transaccion_detalle)>0
                    )as cobros on cobros.id_tabla_amortizacion=at.id_tabla_amortizacion
                    left join core_participes_informacion_adicional pai on pr.id_participes=pai.id_participes
                    inner join core_tabla_amortizacion_pagos aatav on at.id_tabla_amortizacion=aatav.id_tabla_amortizacion and aatav.id_estatus=1
                    inner join core_tabla_amortizacion_parametrizacion atav on aatav.id_tabla_amortizacion_parametrizacion=atav.id_tabla_amortizacion_parametrizacion
                    inner join core_entidad_patronal ee on pr.id_entidad_patronal=ee.id_entidad_patronal
                    where cr.id_estatus=1 and at.id_estatus=1
                    and cr.id_creditos='$id_creditos'
                    group by cr.id_creditos, 
                    at.numero_pago_tabla_amortizacion, 
                    pr.id_participes,
                    pr.nombre_participes,
                    pr.apellido_participes,
                    pr.cedula_participes,
                    pr.celular_participes,
                    cr.monto_otorgado_creditos,
                    cr.monto_neto_entregado_creditos,
                    cr.numero_solicitud_creditos,
                    cstate.nombre_estado_creditos,
                    cr.interes_creditos,
                    ct.nombre_tipo_creditos,
                    at.id_estado_tabla_amortizacion,
                    at.fecha_tabla_amortizacion, 
                    at.capital_tabla_amortizacion, 
                    at.interes_tabla_amortizacion,
                    ee.nombre_entidad_patronal,
                    at.total_balance_tabla_amortizacion,
                    at.balance_tabla_amortizacion,
                    cobros.fecha_pago,
                    cobros.valor,
                    cobros.capital_pagado,
                    cobros.interes_pagado,
                    cobros.otro_valor_pagado,
                    cobros.id_ccomprobantes_ant
                    order by cr.id_creditos, at.numero_pago_tabla_amortizacion, cobros.fecha_pago";
        
        $creditos_detalle = $creditos -> enviaquery($queryDetalle);
        
       
        
        $html='';
        
        
        $html.='<table class="2"  border=1>';
        $html.='<tr>';
        $html.='<th colspan="6" style=" text-align: center; font-size: 11px;">Comprometido</th>';
        $html.='<th colspan="10" style=" text-align: center; font-size: 11px;">Pagado</th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style=" text-align: center; font-size: 11px;">Cuota</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Estado</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Fecha Vencimiento</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Capital</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Interes</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Otros Rubros</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Deuda</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Fecha de Pago</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Recibo</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">R.Manual</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Capital Pagado</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Intereses Pagado</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Otros Rubros Pagados</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Total Pagado</th>';
       // $html.='<th style=" text-align: center; font-size: 11px;">Saldo Prestamo</th>';
        $html.='<th style=" text-align: center; font-size: 11px;">Saldo capital</th>';
        $html.='</tr>';
        
        $i=0;
        $capital_tabla_amortizacion_amort=0;
        $interes_tabla_amortizacion_amort=0;
        
        foreach ($creditos_detalle as $res)
        {
            
            
         
            $capital_tabla_amortizacion_amort=$capital_tabla_amortizacion_amort+$res->capital_tabla_amortizacion;
            $interes_tabla_amortizacion_amort=$interes_tabla_amortizacion_amort+$res->interes_tabla_amortizacion;
            $total_otros_valores=$total_otros_valores+$res->otros_valores;
            
            
            $capital_pagado=$capital_pagado+$res->capital_pagado;
            $interes_pagado=$interes_pagado+$res->interes_pagado;
            $total_rubros=$total_rubros+$res->otro_valor_pagado;
            $total_pagado=$total_pagado+$res->valor;
            
            
            
            
            $html.='<tr >';
            $html.='<td style="text-align: center; font-size: 11px;">'.$res->numero_pago_tabla_amortizacion.'</td>';
            $html.='<td class="4" style="text-align: center; font-size: 11px;">'.$res->estado_pago_cuota.'</td>';
            $html.='<td class="4" style="text-align: center; font-size: 11px;">'.$res->fecha_tabla_amortizacion.'</td>';
            $html.='<td class="5" style="text-align: center; font-size: 11px;">'.$res->capital_tabla_amortizacion.'</td>';
            $html.='<td class="5" style="text-align: center; font-size: 11px;">'.$res->interes_tabla_amortizacion.'</td>';
            $html.='<td class="5" style="text-align: center; font-size: 11px;">'.$res->otros_valores.'</td>';
            $html.='<td class="5" style="text-align: center; font-size: 11px;">'.$res->total_value.'</td>';
            $html.='<td class="4" style="text-align: center; font-size: 11px;">'.$res->fecha_pago.'</td>';
            $html.='<td class="4" style="text-align: center; font-size: 11px;">'.$res->id_ccomprobantes_ant.'</td>';
            $html.='<td class="4" style="text-align: center; font-size: 11px;">0</td>';
            //0=Por Descuento Archivo     1=Manual
            $html.='<td class="5" style="text-align: center; font-size: 11px;">'.$res->capital_pagado.'</td>';
            $html.='<td class="5" style="text-align: center; font-size: 11px;">'.$res->interes_pagado.'</td>';
            $html.='<td class="5" style="text-align: center; font-size: 11px;">'.$res->otro_valor_pagado.'</td>';
            $html.='<td class="5" style="text-align: center; font-size: 11px;">'.$res->valor.'</td>';
          
           
            if($res->valor>0){
                $html.='<td class="5" style="text-align: center; font-size: 11px;">'.$res->balance_tabla_amortizacion.'</td>';
                
            }else{
                $html.='<td class="5" style="text-align: center; font-size: 11px;"></td>';
                
            }
            
            
            
            
            $html.='</td>';
            $html.='</tr>';
        }
        
        
        $html.='<tr>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;"></td>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;"></td>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;"><strong>Total:</strong></td>';
        $html.='<td class="5" style="text-align: center; font-size: 11px;">$'.number_format($capital_tabla_amortizacion_amort, 2, ',', ' ').'</td>';
        $html.='<td class="5" style="text-align: center; font-size: 11px;">$'.number_format($interes_tabla_amortizacion_amort, 2, ',', ' ').'</td>';
        $html.='<td class="5" style="text-align: center; font-size: 11px;">$'.number_format($total_otros_valores, 2, ',', ' ').'</td>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;"></td>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;"></td>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;"></td>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;"></td>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;">$'.number_format($capital_pagado, 2, ',', ' ').'</td>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;">$'.number_format($interes_pagado, 2, ',', ' ').'</td>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;">$'.number_format($total_rubros, 2, ',', ' ').'</td>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;">$'.number_format($total_pagado, 2, ',', ' ').'</td>';
        $html.='<td class="4" style="text-align: center; font-size: 11px;"></td>';
        
        
        $html.='</tr>';
        
        
        
        $html.='</table>';
        
        $datos_reporte['DETALLE_CREDITOS']= $html;
        
        
        
        
        
        
      
        
        $this->verReporte("EstadoCuenta", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
        
        
    }
    
    
   
}
?>