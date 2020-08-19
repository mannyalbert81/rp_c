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
        
        
        $query  = " SELECT
        	1 AS id,
        	pcarga.cedula_participes AS cedula,
        	pcarga.apellido_participes || ' ' || pcarga.nombre_participes AS nombre,
        	COALESCE(CASE WHEN b.cedula_participes != pcarga.cedula_participes THEN b.cedula_participes ELSE '' END, '' ) AS cedula_pago_otro,
        	COALESCE(CASE WHEN b.apellido_participes != pcarga.apellido_participes THEN b.apellido_participes ELSE '' END, '' ) || '' || COALESCE
        	(CASE
        		WHEN b.nombre_participes != pcarga.nombre_participes THEN b.nombre_participes
        		ELSE ''
        	END ,
        	'' ) AS nombre_pago_otro,
        	null AS id_tipo_descuento,
        	null AS nombre_tipo_descuento,
        	a.cuota_descuentos_registrados_detalle_creditos,
        	a.year_descuentos_registrados_detalle_creditos,
        	a.mes_descuentos_registrados_detalle_creditos,
        	a.procesado_descuentos_registrados_detalle_creditos,
        	CASE
        		WHEN cty.nombre_tipo_creditos IS NULL THEN 'INDEBIDO'
        		ELSE upper (cty.nombre_tipo_creditos)
        	END AS nombre_credito,
        	SUM (COALESCE(ctd.valor_transaccion_detalle, COALESCE(drct.valor_cxp_descuentos_registrados_detalle_creditos_trans, 0))) valor_nombre_credito,
        	drct.id_descuentos_registrados_detalle_creditos,
        	c.id_tipo_creditos,
        	cty.nombre_tipo_creditos,
        	ata.descripcion_tabla_amortizacion_parametrizacion AS item_name,
        	drct.cxp_voucher_descuentos_registrados_detalle_creditos_trans,
        	'' AS estado_cuenta_x_pagar,
        	drct.observacion_descuentos_registrados_detalle_creditos_trans,
        	CASE
        		WHEN a.procesado_descuentos_registrados_detalle_creditos = 't' THEN 'SI'
        		WHEN a.procesado_descuentos_registrados_detalle_creditos = 'f' THEN 'NO'
        		ELSE ''
        	END estado_procesado,
        	ata.id_tabla_amortizacion_parametrizacion
         FROM
        	core_descuentos_registrados_detalle_creditos_trans drct
        LEFT OUTER JOIN core_transacciones ct ON
        	drct.id_transacciones = ct.id_transacciones
        	AND ct.id_status = 1
        	AND ct.id_estado_transacciones = 1
        LEFT OUTER JOIN core_transacciones_detalle ctd ON
        	ctd.id_transacciones = ct.id_transacciones
        	AND ctd.id_status = 1
        LEFT OUTER JOIN core_tabla_amortizacion_pagos aata ON
        	aata.id_tabla_amortizacion_pagos = ctd.id_tabla_amortizacion_pago
        LEFT OUTER JOIN core_tabla_amortizacion_parametrizacion ata ON
        	aata.id_tabla_amortizacion_parametrizacion = ata.id_tabla_amortizacion_parametrizacion
        LEFT OUTER JOIN core_creditos c ON
        	ct.id_creditos = c.id_creditos
        	AND c.id_estatus = 1
        LEFT OUTER JOIN core_tipo_creditos cty ON
        	c.id_tipo_creditos = cty.id_tipo_creditos
        	AND cty.id_estatus = 1
        LEFT OUTER JOIN core_descuentos_registrados_detalle_creditos a ON
        	a.id_descuentos_registrados_detalle_creditos = drct.id_descuentos_registrados_detalle_creditos
        LEFT OUTER JOIN core_participes b ON
        	c.id_participes = b.id_participes
        LEFT OUTER JOIN core_participes pcarga ON
        	pcarga.id_participes = a.id_participes
        WHERE
        	a.id_descuentos_registrados_cabeza = $id_descuentos_registrados_cabeza
        GROUP BY
        	pcarga.nombre_participes,
        	pcarga.apellido_participes,
        	pcarga.cedula_participes,
        	b.cedula_participes,
        	b.apellido_participes,
        	b.nombre_participes,
        	a.cuota_descuentos_registrados_detalle_creditos,
        	a.year_descuentos_registrados_detalle_creditos,
        	a.mes_descuentos_registrados_detalle_creditos,
        	a.procesado_descuentos_registrados_detalle_creditos,
        	cty.nombre_tipo_creditos,
        	a.id_descuentos_registrados_detalle_creditos,
        	c.id_tipo_creditos,
        	drct.cxp_voucher_descuentos_registrados_detalle_creditos_trans,
        	drct.id_descuentos_registrados_detalle_creditos,
        	c.id_tipo_creditos,
        	drct.observacion_descuentos_registrados_detalle_creditos_trans,
        	ata.id_tabla_amortizacion_parametrizacion,
        	ata.descripcion_tabla_amortizacion_parametrizacion,
        	c.id_participes
        UNION
        SELECT
        	1 AS id,
        	b.cedula_participes AS cedula,
        	b.apellido_participes || ' ' || b.nombre_participes AS nombre,
        	COALESCE(CASE WHEN b.cedula_participes != b.cedula_participes THEN b.cedula_participes ELSE '' END, '') AS cedula_pago_otro,
        	COALESCE(CASE WHEN b.apellido_participes != b.apellido_participes THEN b.apellido_participes ELSE '' END, '') || ' ' || COALESCE(CASE WHEN b.nombre_participes != b.nombre_participes THEN b.nombre_participes ELSE '' END, '') AS nombre_pago_otro,
        	null AS id_tipo_descuento,
        	null AS nombre_tipo_descuento,
        	0 AS cuota_descuentos_registrados_detalle_creditos,
        	0 AS year_descuentos_registrados_detalle_creditos,
        	0 AS mes_descuentos_registrados_detalle_creditos,
        	't' AS procesado_descuentos_registrados_detalle_creditos,
        	CASE
        		WHEN cty.nombre_tipo_creditos IS NULL THEN 'INDEBIDO'
        		ELSE upper (cty.nombre_tipo_creditos)
        	END AS nombre_credito,
        	SUM ( COALESCE (ctd.valor_transaccion_detalle,0) ) AS valor_nombre_credito,
        	0 AS id_descuentos_registrados_detalle_creditos,
        	c.id_tipo_creditos,
        	cty.nombre_tipo_creditos,
        	ata.descripcion_tabla_amortizacion_parametrizacion AS item_name,
        	'' AS cxp_voucher_descuentos_registrados_detalle_creditos_trans,
        	'' AS estado_cuenta_x_pagar,
        	'' AS observacion_descuentos_registrados_detalle_creditos_trans,
        	'SI' estado_procesado,
        	ata.id_tabla_amortizacion_parametrizacion
        FROM
        	core_transacciones ct
        LEFT OUTER JOIN core_transacciones_detalle ctd ON
        	ctd.id_transacciones = ct.id_transacciones
        	AND ctd.id_status = 1
        LEFT OUTER JOIN core_tabla_amortizacion_pagos aata ON
        	aata.id_tabla_amortizacion_pagos = ctd.id_tabla_amortizacion_pago
        LEFT OUTER JOIN core_tabla_amortizacion_parametrizacion ata ON
        	aata.id_tabla_amortizacion_parametrizacion = ata.id_tabla_amortizacion_parametrizacion
        LEFT OUTER JOIN core_creditos c ON
        	ct.id_creditos = c.id_creditos
        	AND c.id_estatus = 1
        LEFT OUTER JOIN core_tipo_creditos cty ON
        	c.id_tipo_creditos = c.id_tipo_creditos
        	AND cty.id_estatus = 1
        LEFT OUTER JOIN core_participes b ON
        	c.id_participes = b.id_participes
        WHERE
        	b.id_entidad_patronal = $id_entidad_patronal
        	AND to_char (ct.fecha_transacciones,'YYYY') = '$anio'
        	AND to_char(ct.fecha_transacciones, 'MM') = '$mes'
        	AND ct.id_modo_pago = 24
        	AND ct.id_status = 1
        	AND ct.id_estado_transacciones = 1
        GROUP BY
        	b.cedula_participes,
        	b.apellido_participes,
        	b.nombre_participes,
        	cty.nombre_tipo_creditos,
        	c.id_tipo_creditos,
        	ata.tipo_tabla_amortizacion_parametrizacion,
        	ata.descripcion_tabla_amortizacion_parametrizacion,
        	c.id_participes,
        	ata.id_tabla_amortizacion_parametrizacion
        ORDER BY
        	cedula, nombre, id_tipo_creditos";
        

        
        $rsConsulta = $descuentosaportes->enviaquery($query);
        
        
        $datos_reporte['ENTIDAD_PATRONAL']=$nombre_entidad_patronal;
        $datos_reporte['ANIO']=$anio;
        $datos_reporte['MES']=$mes_reporte;
        
        $html='';
        
        
        $html.='<table class="1" border=1>';
        $html.='<tr>';
        $html.='<th>Cédula</th>';
        $html.='<th>Nombre</th>';
        $html.='<th>Garantizado</th>';
        $html.='<th>TIPO CREDITO</th>';
        $html.='<th>Capital</th>';
        $html.='<th>Interes</th>';
        $html.='<th>Mora</th>';
        $html.='<th>Seg. Degrav.</th>';
        $html.='<th>Seg. Incendios.</th>';
        $html.='<th>Total</th>';
        $html.='<th>CUOTA CARGARDA</th>';
        $html.='<th>CUOTA ARCHIVO</th>';
        $html.='<th>ID_CXP</th>';
        $html.='<th>ESTADO CXP</th>';
        $html.='<th>Procesado</th>';
        $html.='<th>Observ.</th>';
        $html.='</tr>';
        
        $resulset2 = json_decode( json_encode( $rsConsulta ), true );
        
        //items en el orden de reporte
        $aitems=array(0=>'CAPITAL',1=>'INTERES',2=>'MORA',3=>'SEGURO DE DESGRAVAMEN',4=>'SEGURO DE INCENDIOS');
        
        /** para sumas totales **/
        $sumaCapital = 0;
        $sumaInteres = 0;
        $sumaMora = 0;
        $sumaSegDesg = 0;
        $sumaSegInc = 0;
        $sumaTotal = 0;
        $sumaCuota = 0;
        /** end para sumas totales **/
        
        $i=0;
        $ini_cedula = "";
        $ini_nombre = "";
        $ini_tipo_credito = 0;
        foreach ( $rsConsulta as $res)
        {
            $cedula = $res->cedula;
            $nombre = $res->nombre;
            $tipo_credito = $res->id_tipo_creditos;
            $i++;
            
            if( $ini_cedula !== $cedula && $ini_nombre !== $nombre  ){
                
                $html.='<tr >';
                $html.='<td align="left";>'.$res->cedula.'</td>';
                $html.='<td align="left";>'.$res->nombre.'</td>';
                $html.='<td align="right";>'.$res->nombre_pago_otro.'</td>';
                $html.='<td align="right";>'.$res->nombre_tipo_creditos.'</td>';
                
                
                $rsValores = $this->devuelveArrayFiltrado($resulset2,$cedula,$tipo_credito);
                
                //variable para sumar valores
                $sumaItems = 0;
                //orden valores --> Capital	Interes	Mora	Seg. Degrav. Seg Inc
                if( !empty($rsValores) ){
                    foreach ( $aitems as $indice => $value ){
                        $rsItem = $this->devuelveItemFiltrado($rsValores, $value);
                        if( !empty($rsItem) ){
                            foreach ( $rsItem as $item){
                                $sumaItems += $item['valor_nombre_credito'];
                                $html.='<td align="right";>'.$item['valor_nombre_credito'].'</td>';          
                                
                                //para sumar valores para totalizar
                                switch ($indice){
                                    case 0: $sumaCapital    +=  $item['valor_nombre_credito']; break;
                                    case 1: $sumaInteres    +=  $item['valor_nombre_credito']; break;
                                    case 2: $sumaMora       +=  $item['valor_nombre_credito']; break;
                                    case 3: $sumaSegDesg    +=  $item['valor_nombre_credito']; break;
                                    case 4: $sumaSegInc     +=  $item['valor_nombre_credito']; break;
                                }                                
                            }
                        }else{
                            $html.='<td align="right";>0.00</td>';
                            $sumaItems += 0;
                        }
                        
                    }
                }
                
                $html.='<td align="right";>'.$sumaItems.'</td>';
                $html.='<td align="right";>'.$res->cuota_descuentos_registrados_detalle_creditos.'</td>';
                $html.='<td align="right";>'.$res->cxp_voucher_descuentos_registrados_detalle_creditos_trans.'</td>';
                $html.='<td align="right";></td>';
                $html.='<td align="right";>'.$res->estado_procesado.'</td>';
                $html.='<td align="right";>'.$res->estado_procesado.'</td>';
                $html.='<td align="right";>'.$res->observacion_descuentos_registrados_detalle_creditos_trans.'</td>';
                $html.='</td>';
                $html.='</tr>';
                
            }else{
                
                if( $ini_cedula === $cedula && $ini_nombre === $nombre && $ini_tipo_credito !== $tipo_credito )
                {
                    $html.='<tr >';
                    $html.='<td align="left";></td>';
                    $html.='<td align="left";></td>';
                    $html.='<td align="right";></td>';
                    $html.='<td align="right";></td>';
                    
                    $rsValores = $this->devuelveArrayFiltrado($resulset2,$cedula,$tipo_credito);
                    
                    //variable para sumar valores
                    $sumaItems = 0;
                    //orden valores --> Capital	Interes	Mora	Seg. Degrav. Seg Inc
                    if( !empty($rsValores) ){
                        foreach ( $aitems as $indice => $value ){
                            $rsItem = $this->devuelveItemFiltrado($rsValores, $value);
                            if( !empty($rsItem) ){
                                foreach ( $rsItem as $item){
                                    $sumaItems += $item['valor_nombre_credito'];
                                    $html.='<td align="right";>'.$item['valor_nombre_credito'].'</td>';
                                    
                                    //para sumar valores para totalizar
                                    switch ($indice){
                                        case 0: $sumaCapital    +=  $item['valor_nombre_credito']; break;
                                        case 1: $sumaInteres    +=  $item['valor_nombre_credito']; break;
                                        case 2: $sumaMora       +=  $item['valor_nombre_credito']; break;
                                        case 3: $sumaSegDesg    +=  $item['valor_nombre_credito']; break;
                                        case 4: $sumaSegInc     +=  $item['valor_nombre_credito']; break;
                                    } 
                                }
                            }else{
                                $html.='<td align="right";>0.00</td>';
                                $sumaItems += 0;
                            }
                            
                        }
                    }
                    
                    $html.='<td align="right";>'.$sumaItems.'</td>';
                    $html.='<td align="right";></td>';
                    $html.='<td align="right";></td>';
                    $html.='<td align="right";></td>';
                    $html.='<td align="right";></td>';
                    $html.='<td align="right";></td>';
                    $html.='<td align="right";></td>';
                    $html.='</td>';
                    $html.='</tr>';
                }
                
            }
                        
            $ini_cedula = $cedula;
            $ini_nombre = $nombre;
            $ini_tipo_credito = $tipo_credito;
            
        }
               
        $sumaTotal = $sumaCapital + $sumaInteres + $sumaMora + $sumaSegDesg + $sumaSegInc;
        
        $html.='<tr >';
        $html.='<td align="left";></td>';
        $html.='<td align="left";></td>';
        $html.='<td align="right";></td>';
        $html.='<td align="right";>TOTALES</td>';
        $html.='<td align="right";>'.$sumaCapital.'</td>';
        $html.='<td align="right";>'.$sumaInteres.'</td>';
        $html.='<td align="right";>'.$sumaMora.'</td>';
        $html.='<td align="right";>'.$sumaSegDesg.'</td>';
        $html.='<td align="right";>'.$sumaSegInc.'</td>';        
        $html.='<td align="right";>'.$sumaTotal.'</td>';
        $html.='<td align="right";>'.$sumaTotal.'</td>';
        $html.='<td align="right";></td>';
        $html.='<td align="right";></td>';
        $html.='<td align="right";></td>';
        $html.='<td align="right";></td>';
        $html.='<td align="right";></td>';
        $html.='</td>';
        $html.='</tr>';
        
        $html.='</table>';
        
        $datos_reporte['DETALLE_DESCUENTOS_APORTES']= $html;
        
        
        
        $this->verReporte("ReporteDescuentosRecibidos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
        
        
    }
    
    public function devuelveArrayFiltrado(array $resulset, $v1, $v2)
    {
        $arrayValores = array();
        $arrayValores  =  array_filter( $resulset, function($ar) use( $v1, $v2)  {
            return ( $ar['cedula'] == $v1 AND $ar['id_tipo_creditos'] == $v2 );
        });
            
            return $arrayValores;
    }
    
    public function devuelveItemFiltrado(array $resulset, $v1)
    {
        $arrayValores = array();
        $arrayValores  =  array_filter( $resulset, function($ar) use( $v1)  {
            return ( $ar['item_name'] == $v1);
        });
            
            return $arrayValores;
    }
   
}
?>