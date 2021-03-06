<?php
class RevisionCreditosController extends ControladorBase{
    public function index(){
        session_start();
      
        $this->view_Credito("RevisionCreditos",array(
        ));
    }
    
    public function getCreditosRegistrados()
    {
        session_start();
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
        $creditos=new PlanCuentasModel();  
        
        $id_rol=$_SESSION["id_rol"];        
            
        $fecha_concesion=$_POST['fecha_concesion'];
        $search=$_POST['search'];
        
        if( $id_rol==58 ) //ingresa si es jefe de creditos y prestaciones
        { 
            
            $columnas=" aa.id_creditos, aa.numero_creditos, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes,
                aa.monto_otorgado_creditos, aa.plazo_creditos, dd.nombre_tipo_creditos, ee.usuario_usuarios, ff.nombre_oficina";
            $tablas="core_creditos aa
                INNER JOIN core_estado_creditos bb ON bb.id_estado_creditos = aa.id_estado_creditos
                INNER JOIN core_participes cc ON cc.id_participes = aa.id_participes
                INNER JOIN core_tipo_creditos dd ON dd.id_tipo_creditos = aa.id_tipo_creditos
                LEFT JOIN usuarios ee ON ee.usuario_usuarios = aa.receptor_solicitud_creditos
                LEFT JOIN oficina ff ON ff.id_oficina = ee.id_oficina";
            $where="aa.id_estado_creditos = 2 AND aa.incluido_reporte_creditos IS NULL";
            
            
            if(!(empty($fecha_concesion)))
            {
                $where.=" AND aa.fecha_concesion_creditos='".$fecha_concesion."'";
            }
            
            if(!(empty($search)))
            {
                $where.=" AND (cc.cedula_participes LIKE '".$search."%' OR cc.nombre_participes ILIKE '".$search."%'
                           OR cc.apellido_participes ILIKE '".$search."%')";
            }
                    
            $id="aa.numero_creditos";
            $html="";
            $resultSet=$creditos->getCantidad("*", $tablas, $where);
            $cantidadResult=(int)$resultSet[0]->total;
        
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
            $resultSet=$creditos->getCondicionesPagDesc($columnas, $tablas, $where, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            if($cantidadResult>0)
            {
            
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:570px; overflow-y:scroll;">';
                $html.= "<table id='tabla_creditos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 15px;">Crédito</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Cédula</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Apellidos</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Nombres</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Monto</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Plazo</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Tipo</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Ciudad</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Receptor</th>';
                $html.='<th style="text-align: left;  font-size: 15px;"></th>';
                $html.='<th style="text-align: left;  font-size: 15px;"></th>';
                $html.='<th style="text-align: left;  font-size: 15px;"></th>';
                $html.='<th style="text-align: left;  font-size: 15px;"></th>';
               
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
            
                foreach ($resultSet as $res)
                {
                    
                    $columnas="id_solicitud_prestamo";
                    $tabla="solicitud_prestamo";
                    $where="numero_creditos='".$res->numero_creditos."'";
                    $id_solicitud=$db->getCondiciones($columnas, $tabla, $where);
                    
                    if(!(empty($id_solicitud))) $id_solicitud=$id_solicitud[0]->id_solicitud_prestamo;
               
                    else $id_solicitud=0;
                   
                    $html.='<tr>';
                    $html.='<td style="font-size: 14px;">'.$res->numero_creditos.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->cedula_participes.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->apellido_participes.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_participes.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->monto_otorgado_creditos.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->plazo_creditos.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_tipo_creditos.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_oficina.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->usuario_usuarios.'</td>';
                    
                    $html.='<td style="font-size: 14px;"><span class="pull-right"><a href="index.php?controller=TablaAmortizacion&action=ReporteTablaAmortizacion&id_creditos='.$res->id_creditos.'" target="_blank" class="btn btn-default" title="Amortizacion"><i class="glyphicon glyphicon-list"></i></a></span></td>';
                    $html.='<td style="font-size: 14px;"><span class="pull-right"><a href="index.php?controller=SolicitudPrestamo&action=print&id_solicitud_prestamo='.$id_solicitud.'" target="_blank" class="btn btn-warning" title="Solicitud"><i class="glyphicon glyphicon-folder-open"></i></a></span></td>';
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-primary" onclick="AgregarReporte('.$res->id_creditos.')"><i class="glyphicon glyphicon-plus"></i></button></span></td>';
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="AnularCredito('.$res->id_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                }
                $html.='</tr>';
                     
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_creditos("index.php", $page, $total_pages, $adjacents,"load_creditos").'';
                $html.='</div>';            
            
            
            }else{
                
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay solicitudes registradas...</b>';
                $html.='</div>';
                $html.='</div>';
            }        
        
            echo $html;
        }
        else
        {
            echo "NO MOSTRAR CREDITOS";   
        }
        
    }
    
    public function getReportesRegistrados()
    {
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $fecha_reporte=$_POST['fecha_reporte'];
        $reportes=new PlanCuentasModel();
        
        $columnas="nombre_rol";
        $tablas="rol";
        $where="id_rol=".$id_rol;
        $id="id_rol";
        $resultRol=$reportes->getCondiciones($columnas, $tablas, $where, $id);
        $resultRol=$resultRol[0]->nombre_rol;
        
        
        $columnas="id_creditos_trabajados_cabeza, anio_creditos_trabajados_cabeza, mes_creditos_trabajados_cabeza,
                     dia_creditos_trabajados_cabeza, oficina.nombre_oficina, estado.nombre_estado";
        $tablas="core_creditos_trabajados_cabeza INNER JOIN oficina
        		ON oficina.id_oficina=core_creditos_trabajados_cabeza.id_oficina
        		INNER JOIN estado
        		ON estado.id_estado=core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza";
           
        if($resultRol=="Jefe de crédito y prestaciones") $where="1=1";
        if($resultRol=="Jefe de recaudaciones")$where="estado.nombre_estado='APROBADO CREDITOS'";
        if($resultRol=="Contador / Jefe de RR.HH")$where="estado.nombre_estado='APROBADO RECAUDACIONES'";
        if($resultRol=="Gerente")$where="estado.nombre_estado='APROBADO CONTADOR'";
        if($resultRol=="Jefe de tesorería")$where="estado.nombre_estado='APROBADO GERENTE'";
        
        if(!(empty($fecha_reporte)))
        {
            $elementos_fecha=explode("-",$fecha_reporte);
            $where.=" AND anio_creditos_trabajados_cabeza=".$elementos_fecha[0]." AND  mes_creditos_trabajados_cabeza=".$elementos_fecha[1]." AND
                     dia_creditos_trabajados_cabeza=".$elementos_fecha[2];
        }
        
        $id="id_creditos_trabajados_cabeza";
        
        $html="";
        $resultSet=$reportes->getCantidad("*", $tablas, $where);
        $cantidadResult=(int)$resultSet[0]->total;
        
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$reportes->getCondicionesPagDesc($columnas, $tablas, $where, $id, $limit);
        
        $total_pages = ceil($cantidadResult/$per_page);
        if($cantidadResult>0)
        {
            
            $html.='<div class="pull-left" style="margin-left:15px;">';
            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
            $html.='</div>';
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<section style="height:300px; overflow-y:scroll;">';
            $html.= "<table id='tabla_reportes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 15px;">Fecha</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Oficina</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Estado</th>';
            $html.='<th style="text-align: left;  font-size: 15px;"></th>';  
            $html.='<th style="text-align: left;  font-size: 15px;"></th>';  
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            foreach ($resultSet as $res)
            {
                
                                  
                $fecha=$res->dia_creditos_trabajados_cabeza."/".$res->mes_creditos_trabajados_cabeza."/".$res->anio_creditos_trabajados_cabeza;
                $html.='<tr>';
                $html.='<td style="font-size: 14px;">'.$fecha.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->nombre_oficina.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
                $html.='<td style="font-size: 14px;"><button  type="button" class="btn btn-warning" onclick="AbrirReporte('.$res->id_creditos_trabajados_cabeza.')"><i class="glyphicon glyphicon-open-file"></i></button></span></td>';
                
                if( $resultRol=="Jefe de crédito y prestaciones" && $res->nombre_estado == "DEVUELTO A REVISION"  )
                {
                    $html.='<td style="font-size: 14px;"><button  type="button" class=" btn btn-primary" title="Abrir Reporte" onclick="ActivarReporte('.$res->id_creditos_trabajados_cabeza.')"><i class="fa fa-undo" aria-hidden="true"></i></button></td>';
                }else
                {
                    $html.='<td style="font-size: 14px;"></td>';
                }
                               
            }
            
            $html.='</tr>';        
                                
            $html.='</tbody>';
            $html.='</table>';
            $html.='</section></div>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate_creditos("index.php", $page, $total_pages, $adjacents,"load_reportes").'';
            $html.='</div>';
            
            
            
        }else{
            
            $html='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>Actualmente no existe reportes registrados...</b>';
            $html.='</div>';
            $html.='</div>';
        }
        
        
        echo $html;
        
    }
    
    public function getReportesAprobados()
    {
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $fecha_reporte=$_POST['fecha_reporte'];
        $reportes=new PlanCuentasModel();
       
        $columnas="nombre_rol";
        $tablas="rol";
        $where="id_rol=".$id_rol;
        $id="id_rol";
        $resultRol=$reportes->getCondiciones($columnas, $tablas, $where, $id);
        $resultRol=$resultRol[0]->nombre_rol;
        
        $columnas="id_creditos_trabajados_cabeza, anio_creditos_trabajados_cabeza, mes_creditos_trabajados_cabeza,
                         dia_creditos_trabajados_cabeza, oficina.nombre_oficina, estado.nombre_estado";
        $tablas="core_creditos_trabajados_cabeza 
            INNER JOIN oficina ON oficina.id_oficina=core_creditos_trabajados_cabeza.id_oficina
            INNER JOIN estado ON estado.id_estado=core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza";
        
        if($resultRol=="Jefe de crédito y prestaciones") $where="1=1";
        if($resultRol=="Jefe de recaudaciones")$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza >= 92 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";;
        if($resultRol=="Contador / Jefe de RR.HH")$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza >= 93 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";
        if($resultRol=="Gerente")$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza>=95 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";
        //if($resultRol=="Jefe de tesorería")$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza>=96 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";
        if($resultRol=="Jefe de tesorería")$where=" NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98) AND ( SELECT COUNT(1) FROM core_creditos_trabajados_detalle WHERE id_estado_detalle_creditos_trabajados = 96) > 0";
        
        if(!(empty($fecha_reporte)))
        {
            $elementos_fecha=explode("-",$fecha_reporte);
            $where.=" AND anio_creditos_trabajados_cabeza=".$elementos_fecha[0]." AND  mes_creditos_trabajados_cabeza=".$elementos_fecha[1]." AND
                         dia_creditos_trabajados_cabeza=".$elementos_fecha[2];
        }
        $id="core_creditos_trabajados_cabeza.id_creditos_trabajados_cabeza";
        
        $html="";
        $resultSet=$reportes->getCantidad("*", $tablas, $where);
        $cantidadResult=(int)$resultSet[0]->total;
        
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        //$limit = " LIMIT   '$per_page' OFFSET '$offset'";
        $limit  = " ORDER BY anio_creditos_trabajados_cabeza DESC, mes_creditos_trabajados_cabeza DESC, dia_creditos_trabajados_cabeza DESC LIMIT   '$per_page' OFFSET '$offset'";
        //$resultSet=$reportes->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
        $resultSet  = $reportes->getCondicionesSinOrden($columnas, $tablas, $where, $limit);
        $count_query   = $cantidadResult;
        $total_pages = ceil($cantidadResult/$per_page);
        if($cantidadResult>0)
        {
            
            $html.='<div class="pull-left" style="margin-left:15px;">';
            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
            $html.='</div>';
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<section style="height:300px; overflow-y:scroll;">';
            $html.= "<table id='tabla_reportes_aprobados' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 15px;">Fecha</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Oficina</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Estado</th>';
            $html.='<th style="text-align: left;  font-size: 15px;"></th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            foreach ($resultSet as $res)
            {
                
                $fecha=$res->dia_creditos_trabajados_cabeza."/".$res->mes_creditos_trabajados_cabeza."/".$res->anio_creditos_trabajados_cabeza;
                $html.='<tr>';
                $html.='<td style="font-size: 14px;">'.$fecha.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->nombre_oficina.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
                $html.='<td style="font-size: 14px;"> <button  type="button" class="btn btn-warning"  onclick="AbrirReporteListado('.$res->id_creditos_trabajados_cabeza.')"><i class="glyphicon glyphicon-open-file"></i></button> </td>';
                                
            }
            
            $html.='</tr>';
            
            
            
            
            $html.='</tbody>';
            $html.='</table>';
            $html.='</section></div>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate_creditos("index.php", $page, $total_pages, $adjacents,"load_reportes").'';
            $html.='</div>';
            
            
            
        }else{
            $html='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay reportes registrados...</b>';
            $html.='</div>';
            $html.='</div>';
        }
        
        
        echo $html;
        
    }
    
    public function getInfoReporte()
    {
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $id_reporte=$_POST["id_reporte"];
        
        $detalle = $_POST['detalle'] ?? 0; #variable para obtener detalle cuando se paga creditos por separado only Rol Tesoreria
        
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
        
        $creditos=new PlanCuentasModel();
        
        //buscar nombre de estado de Reporte
        $columnas   = "bb.nombre_estado, bb.id_estado";
        $tablas     = "core_creditos_trabajados_cabeza aa
            INNER JOIN estado bb ON aa.id_estado_creditos_trabajados_cabeza = bb.id_estado";
        $where      = " aa.id_creditos_trabajados_cabeza=".$id_reporte;
        $id         = "aa.id_estado_creditos_trabajados_cabeza";
        $rsReporte  = $creditos->getCondiciones($columnas, $tablas, $where, $id);
        $id_estado_reporte  = $rsReporte[0]->id_estado;
        $estado_reporte     = $rsReporte[0]->nombre_estado;
        
        //BUSCAR EL NOMBRE DEL ROL
        $columnas  = "nombre_rol";
        $tablas    = "rol";
        $where     = "id_rol=".$id_rol;
        $id        = "id_rol";
        $resultRol=$creditos->getCondiciones($columnas, $tablas, $where, $id);
        $resultRol=$resultRol[0]->nombre_rol;
        
        //BUSCAR EL DETALLE REPORTE DE CREDITOS
        $columnas   ="bb.id_creditos,
             bb.numero_creditos,
             dd.cedula_participes,
             dd.apellido_participes,
             dd.nombre_participes,
             bb.monto_otorgado_creditos,
             bb.monto_neto_entregado_creditos,
             bb.plazo_creditos,
             ee.nombre_tipo_creditos,
             gg.nombre_oficina,
             aa.observacion_detalle_creditos_trabajados,
             bb.id_ccomprobantes";
        $tablas     ="core_creditos_trabajados_detalle aa
             INNER JOIN core_creditos bb ON	bb.id_creditos = aa.id_creditos
             INNER JOIN core_estado_creditos cc ON cc.id_estado_creditos = bb.id_estado_creditos
             INNER JOIN core_participes dd ON	dd.id_participes = bb.id_participes
             INNER JOIN core_tipo_creditos ee ON ee.id_tipo_creditos = bb.id_tipo_creditos
             LEFT JOIN usuarios ff ON ff.usuario_usuarios = bb.receptor_solicitud_creditos
             INNER JOIN oficina gg ON gg.id_oficina = ff.id_oficina";
         $where     =" aa.id_cabeza_creditos_trabajados = ".$id_reporte;
         $id        =" bb.numero_creditos";  
         
         #Para buscar solo los creditos que tiene el mismo estado que el reporte de Cabecera -- TESORERIA
         if( $resultRol == "Jefe de tesorería" && $estado_reporte == "APROBADO GERENTE" )
         {
             if( $detalle == 1 ){
                 $where .= " AND aa.id_estado_detalle_creditos_trabajados = 96 ";
             }else
             {
                 $where .= " AND aa.id_estado_detalle_creditos_trabajados = $id_estado_reporte ";
             }
             
         }
         
         $resultSet=$creditos->getCondiciones($columnas, $tablas, $where, $id);
                  
         $html = "";
         $cantidadResult=sizeof($resultSet);
         if($cantidadResult>0)
         {                
            $html.='<div class="pull-left" style="margin-left:15px;">';
            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
            $html.='</div>';
            
           
            if( empty($detalle) ){
                $html.='<div class="pull-right" style="margin-right:25px;">';
                $html.=$this->drawButtonAprobar($resultRol, $estado_reporte, $id_reporte) ;
                $html.='</div>';
            }else{
                
                $html.='<div class="pull-right" style="margin-right:25px;">';
                $html.='<span class="pull-right"><a class="btn btn-primary" href="index.php?controller=RevisionCreditos&action=ReporteCreditosaTransferir&ui_activo=2&id_reporte='.$id_reporte.'" role="button" target="_blank">IMPRIMIR <i class="glyphicon glyphicon-print"></i></a></span>';
                $html.='</div>';
            }
            
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<section style="height:570px; overflow-y:scroll;">';
            $html.= "<table id='tabla_creditos_reporte' class='table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.= "<tr>";
            
            #Cambio Para poner columnas de Seleccionar un Solo Credito
            if( $resultRol == "Jefe de tesorería" && $estado_reporte == "APROBADO GERENTE" )
            {
                $html.='<th style="text-align: left;  font-size: 12px;"> <input type="checkbox" id="chk_creditos_all" value="0" /> </th>';
            }
            
            $html.='<th style="text-align: left;  font-size: 15px;">Crédito</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Cédula</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Apellidos</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Nombres</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Monto</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Monto a Recibir</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Plazo</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Tipo</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Ciudad</th>';
            $html.='<th style="text-align: left;  font-size: 15px;"></th>';
            $html.='<th style="text-align: left;  font-size: 15px;"></th>';
            $html.='<th style="text-align: left;  font-size: 15px;"></th>';            
            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            $index  = 0;
            foreach ($resultSet as $res)
            {
                
                $columnas="id_solicitud_prestamo";
                $tabla="solicitud_prestamo";
                $where="numero_creditos='".$res->numero_creditos."'";
                $rsSolicitud   = $db->getCondiciones($columnas, $tabla, $where);
                
                $id_solicitud = 0;
                if( !empty($rsSolicitud) ){
                    $id_solicitud   = $rsSolicitud[0]->id_solicitud_prestamo;
                }
                
                $html.='<tr>';
                
                $index++;
                #Cambio Para poner columnas de Seleccionar un Solo Credito
                if( $resultRol == "Jefe de tesorería" && $estado_reporte == "APROBADO GERENTE" )
                {
                    $html.='<td  style="text-align: center;  font-size: 12px;"><input class="chk_credito_seleccionado" type="checkbox" name="creditos" id="'.$index.'" value="'.$res->id_creditos.'"> </td>';
                }
                
                $html.='<td style="font-size: 14px;">'.$res->numero_creditos.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->cedula_participes.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->apellido_participes.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->nombre_participes.'</td>';
                $monto=number_format((float)$res->monto_otorgado_creditos,2,".",",");
                $html.='<td style="font-size: 14px;">'.$monto.'</td>';
                $monto_a_recibir=number_format((float)$res->monto_neto_entregado_creditos,2,".",",");
                $html.='<td style="font-size: 14px;">'.$monto_a_recibir.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->plazo_creditos.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->nombre_tipo_creditos.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->nombre_oficina.'</td>';
                $html.='<td style="font-size: 14px;"><span class="pull-right"><a href="index.php?controller=TablaAmortizacion&action=ReporteTablaAmortizacion&id_creditos='.$res->id_creditos.'" target="_blank" class="btn btn-default" title="Amortizacion"><i class="glyphicon glyphicon-list"></i></a></span></td>';
                $html.='<td style="font-size: 14px;"><span class="pull-right"><a href="index.php?controller=SolicitudPrestamo&action=print&id_solicitud_prestamo='.$id_solicitud.'" target="_blank" class="btn btn-warning" title="Ver Solicitud"><i class="glyphicon glyphicon-folder-open"></i></a></span></td>';
                
                if ($resultRol=="Jefe de crédito y prestaciones" && $estado_reporte=="ABIERTO")
                {
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Quitar('.$id_reporte.','.$res->id_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                }
                if ($resultRol=="Jefe de recaudaciones" && $estado_reporte=="APROBADO CREDITOS")
                {
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$id_reporte.','.$res->id_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                }
                if ($resultRol=="Contador / Jefe de RR.HH" && $estado_reporte=="APROBADO RECAUDACIONES")
                {
                    
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-primary" title="Ver Comprobantes" onclick="MostrarComprobantes('.$res->id_ccomprobantes.')"><i class="glyphicon glyphicon-paste"></i></button></span></td>';
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$id_reporte.','.$res->id_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                  
                }
                if ($resultRol=="Gerente" && $estado_reporte=="APROBADO CONTADOR")
                {
                    //$html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$id_reporte.','.$res->id_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                    $html.='<td style="font-size: 18px;"></td>';
                    
                }
                if ( $resultRol == "Jefe de tesorería" && $estado_reporte == "APROBADO GERENTE" ) 
                {
                    //$html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$id_reporte.','.$res->id_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                    $html.='<td style="font-size: 18px;"></td>';
                }                
                  
                #PARA BOTON DE DEVOLVER CREDITO -- A LISTADO
                if( $estado_reporte == "DEVUELTO A REVISION" && $resultRol=="Jefe de crédito y prestaciones" )
                {                    
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><button title="Devolver Listado" type="button" class="btn btn-danger" onclick="DevolverRevision('.$id_reporte.','.$res->id_creditos.')"><i class="glyphicon glyphicon-minus-sign"> </i> </button> </span> </td>';                    
                }
               
                $html.='</tr>';
                
                if ( $estado_reporte=="DEVUELTO A REVISION" )
                {
                    $html.='<tr>';
                    $html.='<th style="font-size: 15px;">Observación</th>';
                    $html.='<td colspan="9" style="font-size: 14px;">'.$res->observacion_detalle_creditos_trabajados.'</td>';
                    $html.='</tr>';
                }
                
            }            
            
            $html.='</tbody>';
            $html.='</table>';            
            $html.='</section>';
            $html.='</div>';
            
            
        }else{
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay solicitudes registradas...</b>';
            $html.='</div>';
            $html.='</div>';
        }
        
        
        echo $html;
        
    }
    
    public function MostrarComprobantes()
    {
        
        session_start();
        $id_ccomprobantes=$_POST["id_ccomprobantes"];
        $creditos=new PlanCuentasModel();
        $columnas="core_creditos.numero_creditos, 
                  ccomprobantes.numero_ccomprobantes,
                  ccomprobantes.concepto_ccomprobantes, 
                  dcomprobantes.id_dcomprobantes, 
                  plan_cuentas.id_plan_cuentas, 
                  plan_cuentas.codigo_plan_cuentas, 
                  plan_cuentas.nombre_plan_cuentas, 
                  dcomprobantes.debe_dcomprobantes, 
                  dcomprobantes.haber_dcomprobantes";
        $tablas="public.core_creditos, 
                  public.ccomprobantes, 
                  public.plan_cuentas, 
                  public.dcomprobantes";
        $where="core_creditos.id_ccomprobantes = ccomprobantes.id_ccomprobantes AND
                  plan_cuentas.id_plan_cuentas = dcomprobantes.id_plan_cuentas AND
                  dcomprobantes.id_ccomprobantes = ccomprobantes.id_ccomprobantes AND ccomprobantes.id_ccomprobantes=".$id_ccomprobantes;
        $id="ccomprobantes.id_ccomprobantes";
        $ResultComprobantes=$creditos->getCondiciones($columnas, $tablas, $where, $id);
        $cantidadResult=sizeof($ResultComprobantes);
        $html='';
        $total_debe=0;
        $total_haber=0;
        if($cantidadResult>0)
        {
            
            $html.='<div class="pull-left" style="margin-left:15px;">';
            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
            $html.='</div>';
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<section style="height:570px; overflow-y:scroll;">';
            $html.= "<table id='tabla_comprobantes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th colspan="5" style="text-align: left;  font-size: 15px;">'.$ResultComprobantes[0]->concepto_ccomprobantes.'</th>';
            $html.='</tr>';
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 15px;">Comprobante</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">N Cuenta</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Nombre Cuenta</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Debe</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Haber</th>';
           
            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            foreach ( $ResultComprobantes as $res)
            {                               
                $html.='<tr>';
                $html.='<td style="font-size: 14px;">'.$res->numero_ccomprobantes.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->codigo_plan_cuentas.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->nombre_plan_cuentas.'</td>';
                $debe=number_format((float)$res->debe_dcomprobantes,2,".",",");
                $haber=number_format((float)$res->haber_dcomprobantes,2,".",",");
                $html.='<td align="right" style="font-size: 14px;">'.$debe.'</td>';
                $total_debe+=$res->debe_dcomprobantes;
                $total_haber+=$res->haber_dcomprobantes;
                $html.='<td align="right" style="font-size: 14px;">'.$haber.'</td>';
                $html.='</tr>';
            }
            $total_debe=number_format((float)$total_debe,2,".",",");
            $total_haber=number_format((float)$total_haber,2,".",",");
            $html.='<tr>';
            $html.='<th colspan="3" style="text-align: right;font-size: 15px;">Total:</th>';
            $html.='<td align="right" style="font-size: 14px;">'.$total_debe.'</td>';
            $html.='<td align="right" style="font-size: 14px;">'.$total_haber.'</td>';
            $html.='</tr>';
              
            $html.='</tbody>';
            $html.='</table>';
            $html.='</section>';
            $html.='</div>';
            
        }else{
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay comprobantes registrados...</b>';
            $html.='</div>';
            $html.='</div>';
        }
        
        
        echo $html;
        
    }
    
    public function GetReportes()
    {
        session_start();
        
        $reportes = new PlanCuentasModel();
        
        $tablas="public.core_creditos_trabajados_cabeza  aa
            INNER JOIN estado bb ON bb.id_estado = aa.id_estado_creditos_trabajados_cabeza";
        $where="bb.nombre_estado='ABIERTO'";
        $id="aa.id_creditos_trabajados_cabeza";
        $resultSet=$reportes->getCondiciones("*", $tablas, $where, $id);
        
        $html='<label for="tipo_credito" class="control-label">Seleccionar Reporte:</label>
       <select class="reportes" name="reportes_creditos" id="reportes_creditos"  class="form-control">';
        $html.='<option value="0">Reporte con fecha actual</option>';
        if (!(empty($resultSet)))
        {
            foreach($resultSet as $res)
            {
                $fecha=$res->dia_creditos_trabajados_cabeza."/".$res->mes_creditos_trabajados_cabeza."/".$res->anio_creditos_trabajados_cabeza;
                $html.='<option value="'.$res->id_creditos_trabajados_cabeza.'">'.$fecha.'</option>';
            }
        }
        $html.='</select>';
        
        echo $html;
    }
    
    public function GenerarReportes()
    {
        session_start();
        
        $reportes = new PlanCuentasModel();
        $id_reporte=$_POST['id_reporte'];
        $id_credito=$_POST['id_credito'];
        $id_usuarios=$_SESSION['id_usuarios'];
        $usuario_usuarios=$_SESSION['usuario_usuarios'];
        
        $columnas="id_oficina";
        $tablas="usuarios";
        $where="id_usuarios=".$id_usuarios;
        $id="id_oficina";
        $id_oficina=$reportes->getCondiciones($columnas, $tablas, $where, $id);
        $id_oficina=$id_oficina[0]->id_oficina; 
        
        if( empty($id_oficina) ){ echo "Error Oficina - Usuario No Registrado"; die(); }
        
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'ABIERTO'";
        $idest = "estado.id_estado";
        $resultEst = $reportes->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        $resultEst=$resultEst[0]->id_estado;
        
        if( $id_reporte==0 )
        {            
            $dia= date('d');
            $mes= date('m');
            $year= date('Y');
            
            $col1   = " aa.id_creditos_trabajados_cabeza, aa.id_estado_creditos_trabajados_cabeza, bb.nombre_estado";
            $tab1   = " core_creditos_trabajados_cabeza aa
                INNER JOIN estado bb ON bb.id_estado = aa.id_estado_creditos_trabajados_cabeza";
            $whe1   = " aa.anio_creditos_trabajados_cabeza = ".$year."
            	AND aa.mes_creditos_trabajados_cabeza = ".$mes."
            	AND aa.dia_creditos_trabajados_cabeza = ".$dia."
                AND NOT ( bb.nombre_estado = 'DEVUELTO A REVISION')";
            $id1    = " aa.id_creditos_trabajados_cabeza";
            $resultRpts=$reportes->getCondiciones($col1, $tab1, $whe1, $id1);
            
            if (empty($resultRpts))
            {                
                $funcion= "ins_core_creditos_trabajados_cabeza";
                $parametros = "'$id_usuarios',
                     '$usuario_usuarios',
                     '$id_oficina',
                     '$mes',
                     '$year',
                     '$dia',
                     '$resultEst'";
                
                $queryInsert    = $reportes->getconsultaPG($funcion, $parametros);
                $resultado  = $reportes->llamarconsultaPG($queryInsert); 
                $resultado  = $resultado[0];
                
                $inserta_credito=$this->AddCreditosToReport($resultado, $id_credito);
                $inserta_credito=trim($inserta_credito);
                
                $where = "id_creditos=".$id_credito;
                $tabla = "core_creditos";
                $colval = "incluido_reporte_creditos=1";
                
                if (empty($inserta_credito)) { $reportes->UpdateBy($colval, $tabla, $where); }
                
                if( empty( error_get_last() ) ){
                    echo "OK Credito Ingresado al reporte";                    
                }
                  
            }else
            {
                
                $nombre_estado  = $resultRpts[0]->nombre_estado;
                
                if( $nombre_estado != "ABIERTO" )
                {
                  echo "REPORTE CERRADO";
                }
                else 
                {                                        
                    $id_reporte=$resultRpts[0]->id_creditos_trabajados_cabeza;
                    $inserta_credito=$this->AddCreditosToReport($id_reporte, $id_credito);
                    $inserta_credito=trim($inserta_credito);
                    
                    $where = "id_creditos=".$id_credito;
                    $tabla = "core_creditos";
                    $colval = "incluido_reporte_creditos=1";
                    
                    if (empty($inserta_credito)) { $reportes->UpdateBy($colval, $tabla, $where); }
                    
                    if( empty( error_get_last() ) ){
                        echo "OK Credito Ingresado al reporte";
                    }
                    
                }
                 
            }
        }else
        {
            $dia= date('d');
            $mes= date('m');
            $year= date('Y');
            
            $columnas   = "aa.id_creditos_trabajados_cabeza, bb.nombre_estado";
            $tablas     = "core_creditos_trabajados_cabeza aa
                INNER JOIN estado bb ON bb.id_estado = aa.id_estado_creditos_trabajados_cabeza";
            $where      = "aa.id_creditos_trabajados_cabeza = ".$id_reporte;
            $id         = "aa.id_creditos_trabajados_cabeza";
            $resultRpts=$reportes->getCondiciones($columnas, $tablas, $where, $id);
            $nombre_estado  =$resultRpts[0]->nombre_estado;
            
            if( $nombre_estado != "ABIERTO" )
            {
                echo "REPORTE CERRADO";
            }
            else
            {
                $id_reporte=$resultRpts[0]->id_creditos_trabajados_cabeza;
                $inserta_credito=$this->AddCreditosToReport($id_reporte, $id_credito);
                $inserta_credito=trim($inserta_credito);
                $where = "id_creditos=".$id_credito;
                $tabla = "core_creditos";
                $colval = "incluido_reporte_creditos=1";
                
                if (empty($inserta_credito))  $reportes->UpdateBy($colval, $tabla, $where);
                
                if( empty( error_get_last() ) ){
                    echo "OK Credito Ingresado al reporte";
                }
                
            }
            
        }
        
    }
    
    public function AddCreditosToReport($id_reporte, $id_credito)
    {
        ob_start();
        $reportes = new PlanCuentasModel();
        
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'ABIERTO'";
        $idest = "estado.id_estado";
        $resultEst = $reportes->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        $resultEst=$resultEst[0]->id_estado;        
        
        $funcion= "ins_core_creditos_trabajados_detalle";
        $parametros = "'$id_reporte',
                     '$id_credito',
                      '$resultEst'";
        
        $reportes->setFuncion($funcion);
        $reportes->setParametros($parametros);
        $resultado=$reportes->Insert();
        
        return ob_get_clean();
    }
    
       
    public function AprobarReporteCredito()
    {
        session_start();
        
        $reporte = new PermisosEmpleadosModel();
        
        $id_reporte=$_POST['id_reporte'];
        
        $reporte->beginTran();
        
        $columnas="id_estado_creditos";
        $tablas="core_estado_creditos";
        $where="nombre_estado_creditos='Aprobado'";
        $id="id_estado_creditos";
        $id_estado_creditos=$reporte->getCondiciones($columnas, $tablas, $where, $id);
        $id_estado_creditos=$id_estado_creditos[0]->id_estado_creditos;
                
        $columnas="id_creditos";
        $tablas="core_creditos_trabajados_detalle";
        $where="id_cabeza_creditos_trabajados=".$id_reporte;
        $id="id_creditos";
        $resultSet=$reporte->getCondiciones($columnas, $tablas, $where, $id);
        
        foreach ($resultSet as $res)
        {
            $where = "id_creditos=".$res->id_creditos;
            $tabla = "core_creditos";
            $colval = "id_estado_creditos=".$id_estado_creditos;
            $reporte->UpdateBy($colval, $tabla, $where);
            
            require_once 'controller/CreditosController.php';
            
            $ctr_creditos= new CreditosController();
            $mensaje=$ctr_creditos->ActivarCredito($res->id_creditos);
            
            if ($mensaje!='OK')
            {   
                echo $mensaje." ---|id_credito-> ".$res->id_creditos." ||\n";
                $mensaje="ERROR";
                $reporte->endTran("ROLLBACK");
                break;            
            }
            
            
        }
        
        if($mensaje!="ERROR")
        {
            $columnaest = "estado.id_estado";
            $tablaest= "public.estado";
            $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'APROBADO CREDITOS'";
            $idest = "estado.id_estado";
            $resultEst = $reporte->getCondiciones($columnaest, $tablaest, $whereest, $idest);
            
            $where = "id_creditos_trabajados_cabeza=".$id_reporte;
            $tabla = "core_creditos_trabajados_cabeza";
            $colval = "id_estado_creditos_trabajados_cabeza=".$resultEst[0]->id_estado;
            $reporte->UpdateBy($colval, $tabla, $where);
            
            $where = "id_cabeza_creditos_trabajados=".$id_reporte;
            $tabla = "core_creditos_trabajados_detalle";
            $colval = "id_estado_detalle_creditos_trabajados=".$resultEst[0]->id_estado;
            $reporte->UpdateBy($colval, $tabla, $where);
            
            $errores=ob_get_clean();
            
            $errores=trim($errores);
            if(empty($errores))
            {
                $reporte->endTran("COMMIT");
                $mensaje="OK";
            }
            else
            {
                $reporte->endTran("ROLLBACK");
                $mensaje="ERROR".$errores;
            }
        }
        echo $mensaje;
    }
    
    public function AprobarReporteRecaudaciones()
    {
        session_start();
        $id_reporte=$_POST['id_reporte'];
        $reporte = new PermisosEmpleadosModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'APROBADO RECAUDACIONES'";
        $idest = "estado.id_estado";
        $resultEst = $reporte->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_creditos_trabajados_cabeza=".$id_reporte;
        $tabla = "core_creditos_trabajados_cabeza";
        $colval = "id_estado_creditos_trabajados_cabeza=".$resultEst[0]->id_estado;
        $reporte->UpdateBy($colval, $tabla, $where);
        
        $where = "id_cabeza_creditos_trabajados=".$id_reporte;
        $tabla = "core_creditos_trabajados_detalle";
        $colval = "id_estado_detalle_creditos_trabajados=".$resultEst[0]->id_estado;
        $reporte->UpdateBy($colval, $tabla, $where);
          
    }
    
    /*public function AprobarReporteSistemas()
    {
        session_start();
        $id_reporte=$_POST['id_reporte'];
        $reporte = new PermisosEmpleadosModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'APROBADO SISTEMAS'";
        $idest = "estado.id_estado";
        $resultEst = $reporte->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_creditos_trabajados_cabeza=".$id_reporte;
        $tabla = "core_creditos_trabajados_cabeza";
        $colval = "id_estado_creditos_trabajados_cabeza=".$resultEst[0]->id_estado;
        $reporte->UpdateBy($colval, $tabla, $where);
        
        $where = "id_cabeza_creditos_trabajados=".$id_reporte;
        $tabla = "core_creditos_trabajados_detalle";
        $colval = "id_estado_detalle_creditos_trabajados=".$resultEst[0]->id_estado;
        $reporte->UpdateBy($colval, $tabla, $where);
        
    }*/
    
    public function AprobarReporteContador()
    {
        session_start();
        ob_start();
        $id_reporte=$_POST['id_reporte'];
        $reporte = new PermisosEmpleadosModel();
        
        $reporte->beginTran();
        
        require_once 'controller/CreditosController.php';
        
        $ctr_creditos= new CreditosController();
       
        $columnas="id_creditos";
        $tablas="core_creditos_trabajados_detalle";
        $where="id_cabeza_creditos_trabajados=".$id_reporte;
        $id="id_creditos";
        $resultSet=$reporte->getCondiciones($columnas, $tablas, $where, $id);
        foreach ($resultSet as $res)
        {
           $mensaje=$ctr_creditos->MayorizaComprobanteCredito($res->id_creditos);
            if ($mensaje!='OK')
            {
                $mensaje="ERROR";
                $reporte->endTran("ROLLBACK");
                break;
                
            }
        }
        if($mensaje!="ERROR")
        {
            $columnaest = "estado.id_estado";
            $tablaest= "public.estado";
            $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'APROBADO CONTADOR'";
            $idest = "estado.id_estado";
            $resultEst = $reporte->getCondiciones($columnaest, $tablaest, $whereest, $idest);
            
            $where = "id_creditos_trabajados_cabeza=".$id_reporte;
            $tabla = "core_creditos_trabajados_cabeza";
            $colval = "id_estado_creditos_trabajados_cabeza=".$resultEst[0]->id_estado;
            $reporte->UpdateBy($colval, $tabla, $where);
            
            $where = "id_cabeza_creditos_trabajados=".$id_reporte;
            $tabla = "core_creditos_trabajados_detalle";
            $colval = "id_estado_detalle_creditos_trabajados=".$resultEst[0]->id_estado;
            $reporte->UpdateBy($colval, $tabla, $where);
            
            $errores=ob_get_clean();
            $errores=trim($errores);
            if(empty($errores))
            {
                $reporte->endTran("COMMIT");
                $mensaje="OK";
            }
            else
            {
                $reporte->endTran("ROLLBACK");
                $mensaje="ERROR";
            }
        }
        
        
        echo  $mensaje;
    }
    
        
    public function AprobarReporteGerente()
    {
        session_start();
        $id_reporte=$_POST['id_reporte'];
        $reporte = new PermisosEmpleadosModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'APROBADO GERENTE'";
        $idest = "estado.id_estado";
        $resultEst = $reporte->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_creditos_trabajados_cabeza=".$id_reporte;
        $tabla = "core_creditos_trabajados_cabeza";
        $colval = "id_estado_creditos_trabajados_cabeza=".$resultEst[0]->id_estado;
        $reporte->UpdateBy($colval, $tabla, $where);
        
        $where = "id_cabeza_creditos_trabajados=".$id_reporte;
        $tabla = "core_creditos_trabajados_detalle";
        $colval = "id_estado_detalle_creditos_trabajados=".$resultEst[0]->id_estado;
        $reporte->UpdateBy($colval, $tabla, $where);
        
    }
    
    public function AprobarReporteTesoreria()
    {
        session_start();
        $id_reporte     = $_POST['id_reporte'];
        $listaCreditos  = $_POST['listacreditos'];
        $reporte = new PermisosEmpleadosModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'APROBADO TESORERIA'";
        $idest = "estado.id_estado";
        $resultEst = $reporte->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        #Obtengo los creditos seleccionados en array
        $listaCreditos = json_decode($listaCreditos);
        
        $reporte->beginTran();
        
        try {           
                        
            $id_usuarios    = $_SESSION['id_usuarios'];
            $usuario_usuarios   = $_SESSION['usuario_usuarios'];
            $fecha_registro = date('Y-m-d');
            $aprobado_detalle   = true;   
            $mensaje_detalle    = "";
            $resultado  = 0;
                   
            ##RECORRE LOS CREDITOS SELECCIONADOS POR USUARIO 
            foreach ( $listaCreditos as $res)
            {
                $id_creditos    = $res->id_creditos;
                $funcion1   = " cre_aprueba_tesoreria_reporte_credito ";
                $params1    = " $id_creditos, '$fecha_registro', '$usuario_usuarios', $id_usuarios";
                $Query      = $reporte->getconsultaPG($funcion1, $params1);
                $resultado  = $reporte->llamarconsultaPG($Query);
                $resultado  = $resultado[0];
                
                $where = "id_cabeza_creditos_trabajados=".$id_reporte." AND id_creditos = ".$id_creditos;
                $tabla = "core_creditos_trabajados_detalle";
                $colval = "id_estado_detalle_creditos_trabajados=".$resultEst[0]->id_estado;
                $reporte->UpdateBy($colval, $tabla, $where); 
                
                if( !empty(error_get_last()))
                {
                    $mensaje_detalle = "ERROR Credito identificador ".$id_creditos." \n ";
                    $aprobado_detalle   = false;
                    break;
                }
                
            }
            
            $id_estado_aprobado_tesoreria   = $resultEst[0]->id_estado;
            $queryBuscarSobrantes   = " SELECT	COUNT(*) - ( SELECT COUNT(*) FROM core_creditos_trabajados_detalle sa
                                                       WHERE sa.id_cabeza_creditos_trabajados = $id_reporte
                                                       AND sa.id_estado_detalle_creditos_trabajados = $id_estado_aprobado_tesoreria 
                                                        ) AS total
            FROM core_creditos_trabajados_cabeza aa
            INNER JOIN core_creditos_trabajados_detalle bb ON bb.id_cabeza_creditos_trabajados = aa.id_creditos_trabajados_cabeza
            WHERE	aa.id_creditos_trabajados_cabeza = $id_reporte";
            
            $rsConsulta1    = $reporte->enviaquery($queryBuscarSobrantes);
            
            if( !empty($rsConsulta1) )
            {
                if( (int)$rsConsulta1[0]->total <= 0 )
                {
                    $where = "id_creditos_trabajados_cabeza=".$id_reporte;
                    $tabla = "core_creditos_trabajados_cabeza";
                    $colval = "id_estado_creditos_trabajados_cabeza=".$id_estado_aprobado_tesoreria;
                    $reporte->UpdateBy($colval, $tabla, $where);
                }
            }
                                    
            ## validacion detalle reporte aprobados todos
            if( !$aprobado_detalle )
            {
                throw new Exception($mensaje_detalle);
            }
            
            echo "CREDITO ACTIVADO ".$resultado;
            $reporte->endTran('COMMIT');
            
        } catch (Exception $e) {
            $buffer = ob_get_clean();
            echo "ERROR ".$buffer;
            $reporte->endTran();
        }       
        
    }
    
    public function ActivaCredito($paramIdCredito){
        
        if(!isset($_SESSION)){
            session_start();
        }
        
        $Credito = new CreditosModel();
        $Consecutivos = new ConsecutivosModel();
        $TipoComprobantes = new TipoComprobantesModel();
        
        $id_creditos = $paramIdCredito;
        
        if(is_null($id_creditos)){
            echo '<message> parametros no recibidos <message>';
            return;
        }
        
        try {
            
            //$Credito->beginTran();
            
            //creacion de lote
            $nombreLote = "CxP-Creditos";
            $descripcionLote = "GENERACION CREDITO";
            $id_frecuencia = 1;
            $id_usuarios = $_SESSION['id_usuarios'];
            $usuario_usuarios = $_SESSION['usuario_usuarios'];
            $funcionLote = "tes_genera_lote";
            $paramLote = "'$nombreLote','$descripcionLote','$id_frecuencia','$id_usuarios'";
            $consultaLote = $Credito->getconsultaPG($funcionLote, $paramLote);
            $ResultLote = $Credito->llamarconsultaPG($consultaLote);
            $_id_lote = 0; // es cero para que la funcion reconosca como un ingreso de nuevo lote
            $error = "";
            $error = pg_last_error();
            if (!empty($error) || (int)$ResultLote[0] <= 0){
                throw new Exception('error ingresando lote');
            }
            
            $_id_lote = (int)$ResultLote[0];
            
            /*insertado de cuentas por pagar*/
            //busca consecutivo
            $ResultConsecutivo= $Consecutivos->getConsecutivoByNombre("CxP");
            $_id_consecutivos = $ResultConsecutivo[0]->id_consecutivos;
            
            //busca tipo documento
            $queryTipoDoc = "SELECT id_tipo_documento, nombre_tipo_documento FROM tes_tipo_documento
                WHERE abreviacion_tipo_documento = 'MIS' LIMIT 1";
            $ResultTipoDoc= $Credito->enviaquery($queryTipoDoc);
            $_id_tipo_documento = $ResultTipoDoc[0]->id_tipo_documento;
            
            //busca tipo moneda
            $queryMoneda = "SELECT id_moneda, nombre_moneda FROM tes_moneda
                WHERE nombre_moneda = 'DOLAR' LIMIT 1";
            $ResultMoneda= $Credito->enviaquery($queryMoneda);
            $_id_moneda = $ResultMoneda[0]->id_moneda;
            
            //datos de participes
            $funcionProveedor = "ins_proveedores_participes";
            $parametrosProveedor = " '$id_creditos' ";
            $consultaProveedor = $Credito->getconsultaPG($funcionProveedor, $parametrosProveedor);
            $ResultadoProveedor= $Credito->llamarconsultaPG($consultaProveedor);
            $error = "";
            $error = pg_last_error();
            if (!empty($error) ){
                throw new Exception('error proveedores');
            }
            $_id_proveedor = 0;
            if( (int)$ResultadoProveedor[0] > 0 ){
                $_id_proveedor = $ResultadoProveedor[0];
            }else{
                throw new Exception("Error en proveedor-participe");
            }
            
            //para datos de banco
            $_id_bancos = 0 ; //mas adelenate se modifica con la solicitud del participe
            
            //datos Cuenta por pagar
            $_descripcion_cuentas_pagar = ""; //se llena mas adelante
            $_fecha_cuentas_pagar = date('Y-m-d');
            $_condiciones_pago_cuentas_pagar = "";
            $_num_documento_cuentas_pagar = "";
            $_num_ord_compra = "";
            $_metodo_envio_cuentas_pagar = "";
            $_compra_cuentas_pagar = ""; //valor de credito
            $_desc_comercial = 0.00;
            $_flete_cuentas_pagar = 0.00;
            $_miscelaneos_cuentas_pagar = 0.00;
            $_impuesto_cuentas_pagar = 0.00;
            $_total_cuentas_pagar = 0.00;
            $_monto1099_cuentas_pagar = 0.00;
            $_efectivo_cuentas_pagar = 0.00;
            $_cheque_cuentas_pagar = 0.00;
            $_tarjeta_credito_cuentas_pagar = 0.00;
            $_condonaciones_cuentas_pagar = 0.00;
            $_saldo_cuentas_pagar = 0.00;
            $_id_cuentas_pagar = 0;
            
            /*valores para cuenta por pagar*/
            //busca datos de credito
            $queryCredito = "SELECT cc.id_creditos, cc.monto_otorgado_creditos, cc.monto_neto_entregado_creditos,
                      cc.saldo_actual_creditos, cc.numero_creditos,
		              ctc.id_tipo_creditos, ctc.nombre_tipo_creditos, ctc.codigo_tipo_creditos
                    FROM core_creditos cc
                    INNER JOIN core_tipo_creditos ctc
                    ON cc.id_tipo_creditos = ctc.id_tipo_creditos
                    WHERE cc.id_creditos = $id_creditos ";
            $ResultCredito= $Credito->enviaquery($queryCredito);
            
            $codigo_credito = "";
            $monto_credito = 0;
            $monto_entregado_credito = 0;
            $id_tipo_credito = 0;
            $numero_credito = !empty($ResultCredito) ? $ResultCredito[0]->numero_creditos : 0 ;
            
            $_descripcion_cuentas_pagar = "Cuenta x Pagar Credito $numero_credito ";
            
            foreach ($ResultCredito as $res){
                $codigo_credito=$res->codigo_tipo_creditos;
                $monto_credito = $res->monto_otorgado_creditos;
                $monto_entregado_credito = $res->monto_neto_entregado_creditos;
                $id_tipo_credito = $res->id_tipo_creditos;
                
            }
            
            //valores de cuentas por pagar
            $_compra_cuentas_pagar = $monto_credito;
            $_total_cuentas_pagar = $_compra_cuentas_pagar;
            $_saldo_cuentas_pagar = $_compra_cuentas_pagar - $_impuesto_cuentas_pagar;
            
            /*inserccion de cuentas x pagar*/
            //generar cuentas contables de cuentas por pagar
            
            //DIFERENCIAR MONTO SOLICITADO MONTO ENTREGADO
            if($monto_credito != $monto_entregado_credito){
                //para monto en refinaciacion y otras
            }else{
                //para insertado normal
                $queryParametrizacion = "SELECT * FROM core_parametrizacion_cuentas
                                    WHERE id_principal_parametrizacion_cuentas = $id_tipo_credito";
                $ResultParametrizacion = $Credito -> enviaquery($queryParametrizacion);
                
                //buscar de tabla parametrizacion
                $iorden=0;
                foreach ($ResultParametrizacion as $res){
                    $iorden = 1;
                    $queryDistribucion = "INSERT INTO tes_distribucion_cuentas_pagar
                        (id_lote,id_plan_cuentas,tipo_distribucion_cuentas_pagar,debito_distribucion_cuentas_pagar,credito_distribucion_cuentas_pagar,ord_distribucion_cuentas_pagar,referencia_distribucion_cuentas_pagar)
                        VALUES ( '$_id_lote','$res->id_plan_cuentas_debe','COMPRA','0.00','$monto_credito','$iorden','$_descripcion_cuentas_pagar')";
                    
                    $iorden = $iorden + 2;
                    $ResultDistribucion = $Credito -> executeNonQuery($queryDistribucion);
                    $error = "";
                    $error ="";
                    $error = pg_last_error();
                    if(!empty($error) || $ResultDistribucion <= 0 )
                        throw new Exception('error distribucion cuentas pagar debe   '.$error);
                }
                
                foreach ($ResultParametrizacion as $res){
                    $iorden = 2;
                    $queryDistribucion = "INSERT INTO tes_distribucion_cuentas_pagar
                        (id_lote,id_plan_cuentas,tipo_distribucion_cuentas_pagar,debito_distribucion_cuentas_pagar,credito_distribucion_cuentas_pagar,ord_distribucion_cuentas_pagar,referencia_distribucion_cuentas_pagar)
                        VALUES ( '$_id_lote','$res->id_plan_cuentas_haber','PAGOS','$monto_credito','0.00','$iorden','$_descripcion_cuentas_pagar')";
                    $iorden = $iorden + 2;
                    $ResultDistribucion = $Credito -> executeNonQuery($queryDistribucion);
                    $error = "";
                    $error = pg_last_error();
                    if(!empty($error) || $ResultDistribucion <= 0 )
                        throw new Exception('error distribucion cuentas pagar haber');
                }
                
                
                
                
                switch ($codigo_credito){
                    case "EME":
                        $_descripcion_cuentas_pagar .= " Tipo EMERGENTE";
                        
                        break;
                    case "ORD":
                        
                        $_descripcion_cuentas_pagar .= "Tipo ORDINARIO";
                        break;
                }
            }
            
            
            $_origen_cuentas_pagar = "CREDITOS";
            $_id_usuarios = $id_usuarios;
            //datos de cuentas x pagar
            $funcionCuentasPagar = "tes_ins_cuentas_pagar";
            $paramCuentasPagar = "'$_id_lote', '$_id_consecutivos', '$_id_tipo_documento', '$_id_proveedor', '$_id_bancos',
            '$_id_moneda', '$_descripcion_cuentas_pagar', '$_fecha_cuentas_pagar', '$_condiciones_pago_cuentas_pagar', '$_num_documento_cuentas_pagar',
            '$_num_ord_compra','$_metodo_envio_cuentas_pagar', '$_compra_cuentas_pagar', '$_desc_comercial','$_flete_cuentas_pagar',
            '$_miscelaneos_cuentas_pagar','$_impuesto_cuentas_pagar', '$_total_cuentas_pagar','$_monto1099_cuentas_pagar','$_efectivo_cuentas_pagar',
            '$_cheque_cuentas_pagar', '$_tarjeta_credito_cuentas_pagar', '$_condonaciones_cuentas_pagar', '$_saldo_cuentas_pagar', '$_origen_cuentas_pagar', '$_id_cuentas_pagar'";
            
            
            $consultaCuentasPagar = $Credito->getconsultaPG($funcionCuentasPagar, $paramCuentasPagar);
            $ResultCuentaPagar = $Credito -> llamarconsultaPG($consultaCuentasPagar);
            
            $error = "";
            $error = pg_last_error();
            if(!empty($error) || $ResultCuentaPagar[0] <= 0 ){ throw new Exception('error inserccion cuentas pagar');}
            
            // secuencial de cuenta por pagar
            $_id_cuentas_pagar = $ResultCuentaPagar[0];
            
            //para actualizar la forma de pago y el banco en cuentas por pagar
            //--buscar
            $columnas1 = "aa.id_creditos, bb.id_forma_pago, bb.nombre_forma_pago,cc.id_bancos";
            $tabla1 = "core_creditos aa
                    INNER JOIN forma_pago bb
                    ON aa.id_forma_pago = bb.id_forma_pago
                    INNER JOIN core_participes_cuentas cc
                    ON cc.id_participes = aa.id_participes
                    AND cc.cuenta_principal = true";
            $where1 = "aa.id_estatus = 1 AND aa.id_creditos = $id_creditos";
            $id1 = "aa.id_creditos";
            $rsFormaPago = $Credito->getCondiciones($columnas1, $tabla1, $where1, $id1);
            $_id_forma_pago = $rsFormaPago[0]->id_forma_pago;
            $_id_bancos = $rsFormaPago[0]->id_bancos;
            
            $columnaPago = "id_forma_pago = $_id_forma_pago , id_banco = $_id_bancos ";
            $tablasPago = "tes_cuentas_pagar";
            $wherePago = "id_cuentas_pagar = $_id_cuentas_pagar";
            $UpdateFormaPago = $Credito -> ActualizarBy($columnaPago, $tablasPago, $wherePago);
            
            //buscar tipo de comprobante
            $rsTipoComprobantes = $TipoComprobantes->getTipoComprobanteByNombre("CONTABLE");
            $_id_tipo_comprobantes = (!empty($rsTipoComprobantes)) ? $rsTipoComprobantes[0]->id_tipo_comprobantes : null;
            
            $funcionComprobante     = "core_ins_ccomprobantes_activacion_credito";
            $valor_letras           = $Credito->numtoletras($_total_cuentas_pagar);
            $_concepto_comprobantes = "Consecion Creditos Sol:$numero_credito";
            //para parametros hay valores seteados
            $parametrosComprobante = "
                1,
                $_id_tipo_comprobantes,
                '',
                '',
                '',
                '$_total_cuentas_pagar',
                '$_concepto_comprobantes',
                '$_id_usuarios',
                '$valor_letras',
                '$_fecha_cuentas_pagar',
                '$_id_forma_pago',
                null,
                null,
                null,
                null,
                '$_id_proveedor',
                'cxp',
                '$usuario_usuarios',
                'credito',
                '$_id_lote'
                ";
                
                $consultaComprobante = $Credito ->getconsultaPG($funcionComprobante, $parametrosComprobante);
                $resultadComprobantes = $Credito->llamarconsultaPG($consultaComprobante);
                
                $error = "";
                $error = pg_last_error();
                if(!empty($error) || $resultadComprobantes[0] <= 0 ){   throw new Exception('error insercion comprobante contable '); }
                
                // secuencial de comprobante
                $_id_ccomprobantes = $resultadComprobantes[0];
                
                //se actualiza la cuenta por pagar con la relacion al comprobante
                $columnaCxP = "id_ccomprobantes = $_id_ccomprobantes ";
                $tablasCxP = "tes_cuentas_pagar";
                $whereCxP = "id_cuentas_pagar = $_id_cuentas_pagar";
                $UpdateCuentasPagar = $Credito -> ActualizarBy($columnaCxP, $tablasCxP, $whereCxP);
                
                //se actualiza el credito con su comprobante
                $columnaCre = "id_ccomprobantes = $_id_ccomprobantes ";
                $tablasCre = "core_creditos";
                $whereCre = "id_creditos = $id_creditos";
                $UpdateCredito= $Credito -> ActualizarBy($columnaCre, $tablasCre, $whereCre);
                
                //$Credito->endTran('COMMIT');
                return 'OK';
                
        } catch (Exception $e) {
            
            //$Credito->endTran();
            return $e->getMessage();
        }
    }
    
    
    public function paginate_creditos($reload, $page, $tpages, $adjacents,$funcion='') {
        
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
    

    
    
    public function ReporteCreditosaTransferir($param = null) {
        
        
        session_start();
        $entidades = new EntidadesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
        $rp_capremci= new PlanCuentasModel();
        $id_reporte=$_GET['id_reporte'];
        
        $param_activo   = $_GET['ui_activo'] ?? null; #variable que permite obtener creditos pagados y pendientes
        
        if(is_null($param_activo)) die("Error en la estructura del enlace para obtener el reporte ");
        
        #INTRUCTIONS $param_activo  0->obtener todos  1->pendientes 2->activos
        
        if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0)
        {
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
        
        $Usuarios1= new UsuariosModel();
        
        // obtener datos de contador
        $datos_rol_contador = array();
        $columnas   = " aa.id_usuarios, aa.nombre_usuarios, aa.apellidos_usuarios, bb.id_rol, bb.nombre_rol";
        $tablas     = " public.usuarios aa
                    INNER JOIN public.rol bb ON bb.id_rol = aa.id_rol";
        $where      = " bb.nombre_rol = 'Contador / Jefe de RR.HH'";
        $id         = " bb.nombre_rol";       
       
        $resultCont=$Usuarios1->getCondiciones($columnas, $tablas, $where, $id);        
        
        $datos_rol_contador['NOMCONTA']=ucfirst($resultCont[0]->nombre_usuarios);
        $datos_rol_contador['APECONTA']=ucfirst($resultCont[0]->apellidos_usuarios);
        
        
        // obtener datos de GERENTE
        $datos_rol_gerente = array();
        $columnas   = " aa.id_usuarios, aa.nombre_usuarios, aa.apellidos_usuarios, bb.id_rol, bb.nombre_rol";
        $tablas     = " public.usuarios aa
                    INNER JOIN public.rol bb ON bb.id_rol = aa.id_rol";
        $where      = " bb.nombre_rol = 'Gerente'";
        $id         = " bb.nombre_rol";
        
        $resultGer=$Usuarios1->getCondiciones($columnas, $tablas, $where, $id);
        
        $datos_rol_gerente['NOMGER']=ucfirst($resultGer[0]->nombre_usuarios);
        $datos_rol_gerente['APEGER']=ucfirst($resultGer[0]->apellidos_usuarios);
        
        
        //datos de Jefe de creditos
        $datos_rol_jcredito = array();
        $columnas   = " aa.id_usuarios, aa.nombre_usuarios, aa.apellidos_usuarios, bb.id_rol, bb.nombre_rol";
        $tablas     = " public.usuarios aa
                    INNER JOIN public.rol bb ON bb.id_rol = aa.id_rol";
        $where      = " bb.nombre_rol = 'Jefe de crédito y prestaciones'";
        $id         = " bb.nombre_rol";
        
        $resultCredito  =$Usuarios1->getCondiciones($columnas, $tablas, $where, $id);
        
        $datos_rol_jcredito['NOMJCRE']=ucfirst( $resultCredito[0]->nombre_usuarios);
        $datos_rol_jcredito['APEJCRE']=ucfirst( $resultCredito[0]->apellidos_usuarios);
        
        //TABLA        
        $datos_reporte = array();
        
        $columnas   ="aa.id_creditos,aa.numero_creditos, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes, aa.monto_otorgado_creditos, aa.plazo_creditos,
                aa.monto_neto_entregado_creditos, ee.nombre_tipo_creditos, ff.nombre_estado_creditos, gg.nombre_forma_pago, ii.nombre_bancos, jj.nombre_tipo_cuentas,
                hh.numero_participes_cuentas, bb.celular_participes, kk.monto_creditos_retenciones";
        $tablas ="core_creditos aa
                INNER JOIN core_participes bb ON aa.id_participes = bb.id_participes
                INNER JOIN core_creditos_trabajados_detalle cc ON cc.id_creditos = aa.id_creditos
                INNER JOIN core_creditos_trabajados_cabeza dd ON dd.id_creditos_trabajados_cabeza = cc.id_cabeza_creditos_trabajados
                INNER JOIN core_tipo_creditos ee ON ee.id_tipo_creditos = aa.id_tipo_creditos
                INNER JOIN core_estado_creditos ff ON ff.id_estado_creditos = aa.id_estado_creditos
                INNER JOIN forma_pago gg ON gg.id_forma_pago = aa.id_forma_pago
                INNER JOIN core_participes_cuentas hh ON hh.id_participes = aa.id_participes
                INNER JOIN tes_bancos ii ON ii.id_bancos = hh.id_bancos
                INNER JOIN core_tipo_cuentas jj ON jj.id_tipo_cuentas = hh.id_tipo_cuentas
                LEFT JOIN core_creditos_retenciones kk ON kk.id_creditos = aa.id_creditos";
        $where  =" dd.id_creditos_trabajados_cabeza =".$id_reporte." AND hh.cuenta_principal = true";
        $id     =" ii.nombre_bancos";  
        
        #para modificar listado de reporte
        if( $param_activo == 1 ){ $where    .= " AND cc.id_estado_detalle_creditos_trabajados = 95 "; }
        if( $param_activo == 2 ){ $where    .= " AND cc.id_estado_detalle_creditos_trabajados = 96 "; }
                
        $resultSet=$rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
        
        $columnas   =" aa.numero_creditos,bb.cedula_participes,bb.apellido_participes,bb.nombre_participes, aa.monto_otorgado_creditos, aa.plazo_creditos,
            aa.monto_neto_entregado_creditos, ee.nombre_tipo_creditos, ff.nombre_estado_creditos, gg.nombre_forma_pago, bb.celular_participes,
            hh.monto_creditos_retenciones";
        $tablas     =" core_creditos aa
            INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes
            INNER JOIN core_creditos_trabajados_detalle cc ON cc.id_creditos = aa.id_creditos
            INNER JOIN core_creditos_trabajados_cabeza dd ON dd.id_creditos_trabajados_cabeza = cc.id_cabeza_creditos_trabajados
            INNER JOIN core_tipo_creditos ee ON ee.id_tipo_creditos = aa.id_tipo_creditos
            INNER JOIN core_estado_creditos ff ON ff.id_estado_creditos = aa.id_estado_creditos
            INNER JOIN forma_pago gg ON gg.id_forma_pago = aa.id_forma_pago
            LEFT JOIN core_creditos_retenciones hh ON hh.id_creditos = aa.id_creditos";
        $where      =" dd.id_creditos_trabajados_cabeza = ".$id_reporte." AND gg.nombre_forma_pago='CHEQUE' ";
        $id         =" aa.numero_creditos";
               
        $resultSetCheques=$rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
        
        $html='';
        
            $html.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
            $html.= "<tr>";
            $html.='<th style="text-align: center; font-size: 11px;"> # </th>';
            $html.='<th style="text-align: center; font-size: 11px;">No. PRESTAMO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">IDENTIFICACIÓN</th>';
            $html.='<th style="text-align: center; font-size: 11px;">APELLIDOS DEL AFILIADO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">NOMBRES DEL AFILIADO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">MONTO CONCEDIDO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">RETEN POR APORTE</th>';
            $html.='<th style="text-align: center; font-size: 11px;">CUENTA INDIVIDUAL</th>';
            $html.='<th style="text-align: center; font-size: 11px;">PLAZO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">VALOR RETENCION CREDITOS</th>';
            $html.='<th style="text-align: center; font-size: 11px;">LIQUIDO A RECIBIR</th>';
            $html.='<th style="text-align: center; font-size: 11px;">TIPO PRESTAMO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">ESTADO PRESTAMO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">FECHA DE PAGO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">FORMA DE PAGO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">NOMBRE DEL BANCO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">No. DE CUENTA</th>';
            $html.='<th style="text-align: center; font-size: 11px;">TIPO DE CUENTA</th>';
            $html.='<th style="text-align: center; font-size: 11px;">CELULAR</th>';
            $html.='</tr>';
            $i=0;
            foreach($resultSet as $res)
            {
                $i++;
                $html.= "<tr>";
                $html.='<td style="text-align: center; font-size: 11px;">'.$i.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->numero_creditos.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->cedula_participes.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->apellido_participes.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_participes.'</td>';
                $monto=number_format((float)$res->monto_otorgado_creditos,2,".",",");
                $html.='<td align="right" style="text-align: center; font-size: 11px;">'.$monto.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">-</td>';
                $columnas="SUM(valor_personal_contribucion)+SUM(valor_patronal_contribucion) AS total";
                $tablas="core_contribucion INNER JOIN core_participes
                ON core_contribucion.id_participes  = core_participes.id_participes";
                $where="core_participes.cedula_participes='".$res->cedula_participes."' AND core_contribucion.id_estatus=1";
                $totalCtaIndividual=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
                $totalCtaIndividual=$totalCtaIndividual[0]->total;
                $totalCtaIndividual=number_format((float)$totalCtaIndividual,2,".",",");
                $html.='<td align="right" style="text-align: center; font-size: 11px;">'.$totalCtaIndividual.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->plazo_creditos.'</td>';
                if(!(empty($res->monto_creditos_retenciones)))
                {
                    $retencion=$res->monto_creditos_retenciones;
                    $retencion=number_format((float)$retencion,2,".",",");
                    $html.='<td align="right" style="text-align: center; font-size: 11px;">'.$retencion.'</td>';
                }
                else
                $html.='<td style="text-align: center; font-size: 11px;">-</td>';
                $monto=number_format((float)$res->monto_neto_entregado_creditos,2,".",",");
                $html.='<td align="right" style="text-align: center; font-size: 11px;">'.$monto.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_tipo_creditos.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_estado_creditos.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">-</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_forma_pago.'</td>';
                if($res->nombre_forma_pago=="TRANSFERENCIA")
                {
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_bancos.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->numero_participes_cuentas.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_tipo_cuentas.'</td>';
                }
                else
                {
                    $html.='<td style="text-align: center; font-size: 11px;"></td>';
                    $html.='<td style="text-align: center; font-size: 11px;"></td>';
                    $html.='<td style="text-align: center; font-size: 11px;"></td>';
                }
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->celular_participes.'</td>';
                $html.='</tr>';
            }
            
            foreach($resultSetCheques as $res)
            {
                $i++;
                $html.= "<tr>";
                $html.='<td style="text-align: center; font-size: 11px;">'.$i.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->numero_creditos.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->cedula_participes.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->apellido_participes.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_participes.'</td>';
                $monto=number_format((float)$res->monto_otorgado_creditos,2,".",",");
                $html.='<td align="right" style="text-align: center; font-size: 11px;">'.$monto.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">-</td>';
                $columnas="SUM(valor_personal_contribucion)+SUM(valor_patronal_contribucion) AS total";
                $tablas="core_contribucion INNER JOIN core_participes
                ON core_contribucion.id_participes  = core_participes.id_participes";
                $where="core_participes.cedula_participes='".$res->cedula_participes."' AND core_contribucion.id_estatus=1";
                $totalCtaIndividual=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
                $totalCtaIndividual=$totalCtaIndividual[0]->total;
                $totalCtaIndividual=number_format((float)$totalCtaIndividual,2,".",",");
                $html.='<td align="right" style="text-align: center; font-size: 11px;">'.$totalCtaIndividual.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;">'.$res->plazo_creditos.'</td>';
                if(!(empty($res->monto_creditos_retenciones)))
                {
                    $retencion=$res->monto_creditos_retenciones;
                    $retencion=number_format((float)$retencion,2,".",",");
                    $html.='<td align="right" style="text-align: center; font-size: 11px;">'.$retencion.'</td>';
                }
                else
                    $html.='<td style="text-align: center; font-size: 11px;">-</td>';
                    $monto=number_format((float)$res->monto_neto_entregado_creditos,2,".",",");
                    $html.='<td align="right" style="text-align: center; font-size: 11px;">'.$monto.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_tipo_creditos.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_estado_creditos.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">-</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_forma_pago.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;"></td>';
                    $html.='<td style="text-align: center; font-size: 11px;"></td>';
                    $html.='<td style="text-align: center; font-size: 11px;"></td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->celular_participes.'</td>';
                    $html.='</tr>';
            }
            
        $html.='</table>';
        $banco="";
        $total_transfer=0;
        $total_transfer1=0;
        $cantidad=0;
        $pagos_cheque=0;
        $total_cheque=0;
        $total=0;
        $total_transacciones=0;
        $transferencias=array();
        $ultimo=sizeof($resultSet);
        $ultimo_cheques=sizeof($resultSetCheques);
        for($i=0; $i<$ultimo; $i++)
        {
                if($resultSet[$i]->nombre_bancos!=$banco)
                {
                    if($banco!="")
                    {
                        $resultado=array();
                        array_push($resultado, $banco, $cantidad, $total_transfer);
                        array_push($transferencias, $resultado);
                    }
                    $banco=$resultSet[$i]->nombre_bancos;
                    $cantidad=1;
                    $total_transfer=$resultSet[$i]->monto_neto_entregado_creditos;
                    
                    if($i==$ultimo-1)
                    {
                        $resultado=array();
                        array_push($resultado, $banco, $cantidad, $total_transfer);
                        array_push($transferencias, $resultado);
                    }
                    
                }
                else
                {
                    $cantidad++;
                    $total_transfer+=$resultSet[$i]->monto_neto_entregado_creditos;
                    if($i==$ultimo-1)
                    {
                        $resultado=array();
                        array_push($resultado, $banco, $cantidad, $total_transfer);
                        array_push($transferencias, $resultado);
                    }
                }
                $total_transfer1+=$resultSet[$i]->monto_neto_entregado_creditos;
                $total_transacciones++;
            
        }
        
        for( $i=0; $i < $ultimo_cheques; $i++)
        {
            $total_transacciones++;
            $total_cheque+=$resultSetCheques[$i]->monto_neto_entregado_creditos;
        }
        
        $pagos_cheque=$ultimo_cheques;
        
        
        $total=$total_cheque+$total_transfer1;
        
        
        $html.='<table  class="3" cellspacing="0" style="width:100px;" border="1" align="center">';
        $html.='<tr>';
        $html.='<td colspan="4"><strong>REPORTE CON VALORES DE CREDITOS A TRANSFERIR</strong></td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<td><strong>FORMA DE PAGO</strong></td>';
        $html.='<td><strong>BANCO</strong></td>';
        $html.='<td><strong>TOTAL TRANSACCIONES</strong></td>';
        $html.='<td><strong>TOTAL</strong></td>';
        $html.='</tr>';
        
        
        ///foreach
       if( $pagos_cheque > 0)
       {
           $html.='<tr>';
           $html.='<td>CHEQUE</td>';
           $html.='<td></td>';
           $html.='<td align="center">'.$pagos_cheque.'</td>';
           $total_cheque=number_format((float)$total_cheque,2,".",",");
           $html.='<td align="right">'.$total_cheque.'</td>';
           $html.='</tr>';
       }
       
       $num_transfer=sizeof($transferencias);
       
       if($num_transfer>0)
       {
           
           for($i=0; $i<$num_transfer; $i++)
           {
               if($i==0)
               {
                   $html.='<tr>';
                   $html.='<td rowspan="'.$num_transfer.'">TRANSFERENCIA</td>';
                   $html.='<td>'.$transferencias[$i][0].'</td>';
                   $html.='<td align="center">'.$transferencias[$i][1].'</td>';
                   $monto=number_format((float)$transferencias[$i][2],2,".",",");
                   $html.='<td align="right">'.$monto.'</td>';
                   $html.='</tr>';
               }
               else 
               {
                   $html.='<tr>';
                   $html.='<td>'.$transferencias[$i][0].'</td>';
                   $html.='<td align="center">'.$transferencias[$i][1].'</td>';
                   $monto=number_format((float)$transferencias[$i][2],2,".",",");
                   $html.='<td align="right">'.$monto.'</td>';
                   $html.='</tr>';
               }
               
           }
           
          
       }
        
        //despues del forech
        $html.='<tr>';
        $html.='<td><strong></strong></td>';
        $html.='<td><strong>TOTAL</strong></td>';
        $html.='<td align="center">'.$total_transacciones.'</td>';
        $total=number_format((float)$total,2,".",",");
        $html.='<td align="right"><strong>'.$total.'</strong></td>';
        $html.='</tr>';
        $html.='</table>';
        
    
        $datos_reporte['DETALLE_CREDITOS']= $html;
                
        $this->verReporte("ReporteCreditos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte, 'datos_rol_contador'=>$datos_rol_contador, 'datos_rol_gerente'=>$datos_rol_gerente, 'datos_rol_jcredito'=>$datos_rol_jcredito));
        
    }
            
    public function NegarCredito()
    {
        session_start();
        ob_start();
        
        $id_reporte     = $_POST['id_reporte'];
        $numero_credito = $_POST['numero_credito'];
        $observacion_credito    = $_POST['observacion_credito'];
        //$id_rol     = $_SESSION['id_rol'];
        $reporte    = new PermisosEmpleadosModel();
        
        $mensajeDatos   = "";
                
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'DEVUELTO A REVISION'";
        $idest = "estado.id_estado";
        $resultEst = $reporte->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        if( $id_reporte == 0 ){
            exit();
        }
        
        #CONSULTAMOS creditos dentro del reporte
        $col1   = " id_detalle_creditos_trabajados, id_creditos, id_estado_detalle_creditos_trabajados";
        $tab1   = " public.core_creditos_trabajados_detalle";
        $whe1   = " id_cabeza_creditos_trabajados = $id_reporte";
        $rsConsulta1    = $reporte->getCondicionesSinOrden($col1, $tab1, $whe1, "" );
        
        #CONSULTAMOS estado de credito preaprobado
        $columnas="id_estado_creditos";
        $tablas="core_estado_creditos";
        $where="nombre_estado_creditos='Pre Aprobado'";
        $id="id_estado_creditos";
        $rsConsulta_estado  = $reporte->getCondiciones($columnas, $tablas, $where, $id);
        $id_estado_creditos = $rsConsulta_estado[0]->id_estado_creditos;
        
        #INICIAMOS transaccionabilidad
        $reporte->beginTran();
        
        #VALIDAMOS LA EXISTENCIA DE LOS CREDITOS
        if( !empty($rsConsulta1) )
        {            
            #RECORREMOS el listado del reporte
            foreach ($rsConsulta1 as $res)
            {
                $id_creditos    = $res->id_creditos;
                
                #SINTAXERROR para validar por roles de tesosreia en caso de haber esta pendiente
                
                #BUSCAMOS DATOS DEL CREDITO
                $col01   = " aa.id_creditos, bb.id_ccomprobantes, cc.id_cuentas_pagar, cc.id_lote, dd.nombre_estado_creditos";
                $tab01   = " core_creditos aa
                    INNER JOIN ccomprobantes bb ON bb.id_ccomprobantes = aa.id_ccomprobantes
                    INNER JOIN tes_cuentas_pagar cc ON cc.id_ccomprobantes = bb.id_ccomprobantes
                    INNER JOIN core_estado_creditos dd ON dd.id_estado_creditos = aa.id_estado_creditos";
                $whe01   = " aa.id_creditos = $id_creditos";
                $rsConsulta01    = $reporte->getCondicionesSinOrden($col01, $tab01, $whe01, "");
                
                if( !empty($rsConsulta01) )
                {
                    #tomamos el id del comprobante contable
                    $id_comprobante = $rsConsulta01[0]->id_ccomprobantes;
                    
                    #buscar si existe en el mayor contable
                    $col02   = " aa.id_ccomprobantes, aa.aprobado_ccomprobantes";
                    $tab02   = " ccomprobantes aa
                        INNER JOIN con_mayor bb ON bb.id_ccomprobantes = aa.id_ccomprobantes";
                    $whe02   = " aa.id_ccomprobantes = $id_comprobante ";
                    $rsConsulta02    = $reporte->getCondicionesSinOrden($col02, $tab02, $whe02, "");
                    
                    if(  !empty($rsConsulta02) && $rsConsulta02[0]->aprobado_ccomprobantes == "f" )
                    {
                        #ingreso porq ya esta desmayorizado y solo resta quitar del reporte
                        #ya se encuentra desmayorizado y eliminado las cuentas por pagar
                        
//                         $EliminarReporte    = " DELETE FROM core_creditos_trabajados_detalle WHERE id_cabeza_creditos_trabajados = $id_reporte AND id_creditos = $id_creditos ";
                        
                        $UpdateCredito  = " UPDATE core_creditos SET id_estado_creditos = $id_estado_creditos WHERE id_creditos = $id_creditos";
                        
//                         $reporte->executeNonQuery($EliminarReporte);
                        $reporte->executeNonQuery($UpdateCredito);
                        
                        //$UpdateCredito  = " UPDATE core_creditos SET incluido_reporte_creditos = null, id_estado_creditos = 2 WHERE id_creditos = $id_creditos";                        
                        
                        $mensajeDatos   = "OK";
                        
                    }else if( empty($rsConsulta02) )
                    {
                        #ingresa y se anula los comprobantes y las cuentas por cobrar generadas
                        
                        //tomamos variables de la consulta $rsConsulta1
                        $id_cuentas_pagar   = $rsConsulta01[0]->id_cuentas_pagar;
                        $id_lote            = $rsConsulta01[0]->id_lote;
                        
                        //buscamos estado de anulado CuentasXPagar
                        $col03   = " id_estado,nombre_estado";
                        $tab03   = " public.estado";
                        $whe03   = " nombre_estado = 'ANULADO' AND tabla_estado = 'tes_cuentas_pagar'";
                        $rsConsulta03   = $reporte->getCondicionesSinOrden($col03, $tab03, $whe03, "LIMIT 1");
                        
                        $id_estado_CxP  = $rsConsulta03[0]->id_estado;
                        
                        $UpdateCXP  = " UPDATE tes_cuentas_pagar SET id_estado = $id_estado_CxP WHERE id_cuentas_pagar = $id_cuentas_pagar AND id_lote = $id_lote";
                        
                        $UpdateComprobante  = " UPDATE ccomprobantes SET aprobado_ccomprobantes = false WHERE id_ccomprobantes = $id_comprobante";
                        
//                         $UpdateCredito  = " UPDATE core_creditos SET incluido_reporte_creditos = null, id_estado_creditos = 2 WHERE id_creditos = $id_creditos";
                        
//                         $EliminarReporte    = " DELETE FROM core_creditos_trabajados_detalle WHERE id_cabeza_creditos_trabajados = $id_reporte AND id_creditos = $id_creditos ";

                        $UpdateCredito  = " UPDATE core_creditos SET id_estado_creditos = $id_estado_creditos WHERE id_creditos = $id_creditos";
                        
                        $reporte->executeNonQuery($UpdateCXP);
                        $reporte->executeNonQuery($UpdateComprobante);
                        $reporte->executeNonQuery($UpdateCredito);
//                         $reporte->executeNonQuery($EliminarReporte);
                        
                        $mensajeDatos   = "OK";
                    }
                    
                    
                }
                
                if( $id_creditos == $numero_credito )
                {
                    #ACTUALIZAMOS la observacion en el detalle de reporte
                    $where = "id_creditos=".$numero_credito;
                    $tabla = "core_creditos_trabajados_detalle";
                    $colval = "observacion_detalle_creditos_trabajados='".$observacion_credito."'";
                    $reporte->UpdateBy($colval, $tabla, $where);
                }else
                {
                    #ACTUALIZAMOS la observacion en el detalle de reporte
                    $where = "id_creditos=".$numero_credito;
                    $tabla = "core_creditos_trabajados_detalle";
                    $colval = "observacion_detalle_creditos_trabajados='Anulación por dependencia'";
                    $reporte->UpdateBy($colval, $tabla, $where);
                }
                
                #ACTUALIZAMOS 
                
                
            } #termina foreach
            
            #ACTUALIZAMOS datos de reporte de creditos
            $colval = "id_estado_creditos_trabajados_cabeza=".$resultEst[0]->id_estado;
            $tabla = "core_creditos_trabajados_cabeza";
            $where = "id_creditos_trabajados_cabeza=".$id_reporte;
            $reporte->UpdateBy($colval, $tabla, $where);
            
            $where = "id_cabeza_creditos_trabajados=".$id_reporte;
            $tabla = "core_creditos_trabajados_detalle";
            $colval = "id_estado_detalle_creditos_trabajados=".$resultEst[0]->id_estado;
            $reporte->UpdateBy($colval, $tabla, $where);           
            
        }
        
        $error  = error_get_last();
        $buffer = ob_get_clean();
                
        if( !empty($error) || !empty($buffer) )
        {            
            $reporte->endTran('ROLLBACK');   
            echo $error['message'];
            die();
        }else
        {            
            $reporte->endTran('COMMIT');
            echo $mensajeDatos;
            die();
        }
        
        # AQUI CODIGO para cuando vaya con roles de tesosreria poner dentro de foreach arriba mencionado
        //$desmayorizacion = "";
        
//         if ($id_rol==53 || $id_rol==61) //jefe de tesoreria anula los comprobantes generados al aprobar credito
//         {
//             require_once 'controller/CreditosController.php';
            
//             $ctr_creditos= new CreditosController();
            
//             $desmayorizacion=$ctr_creditos->EliminarReporteCredito($numero_credito);
//         }
        
//         if ($desmayorizacion=="OK")
//         {
//             foreach ($resultSet as $res)
//             {
//                 $where = "id_creditos=".$res->id_creditos;
//                 $tabla = "core_creditos";
//                 $colval = "id_estado_creditos=".$id_estado_creditos.", incluido_reporte_creditos=null";
//                 $reporte->UpdateBy($colval, $tabla, $where);
//             }
            
//             $errores=ob_get_clean();
//             $errores=trim($errores);
//             if(empty($errores))
//             {
//                 $reporte->endTran('COMMIT');
//                 $mensaje="OK";
//             }
//             else
//             {
//                 $reporte->endTran('ROLLBACK');
//                 $mensaje="ERROR".$errores;
//             }
//         }
//         else
//         {
//             if( empty($desmayorizacion) )
//             {
//                 $reporte->endTran('COMMIT');
//                 $mensaje = "OK";
//             }else{
//                 $reporte->endTran('ROLLBACK');
//                 $mensaje="ERROR".$desmayorizacion;
//             }
//         }
       
        
    }
    
    public function QuitarCredito()
    {
        session_start();
        ob_start();
        $id_reporte=$_POST['id_reporte'];
        $numero_credito=$_POST['numero_credito'];
        $reporte = new PermisosEmpleadosModel();
        $creditos_detalle= new CreditosTrabajadosDetalleModel();
        
        $reporte->beginTran(); 
        
        $query="DELETE FROM core_creditos_trabajados_detalle
                WHERE  id_cabeza_creditos_trabajados=".$id_reporte." AND id_creditos=".$numero_credito;        
        $creditos_detalle->executeNonQuery($query);
        
        $where = "numero_creditos='".$numero_credito."'";
        $tabla = "core_creditos";
        $colval = "incluido_reporte_creditos=null";
        $reporte->UpdateBy($colval, $tabla, $where);
        
        $errores=ob_get_clean();
        $errores=trim($errores);
        if(empty($errores))
        {
            $reporte->endTran('COMMIT');
            $mensaje="OK";
        }
        else
        {
            $reporte->endTran('ROLLBACK');
            $mensaje="ERROR".$errores;
        }
        echo $mensaje."-".$id_reporte."-".$numero_credito.$query;
    }
    
    private function drawButtonAprobar($nombre_rol,$estado_reporte,$id_reporte)
    {
        $btnHtml    = "";
                
        if( $nombre_rol == "Jefe de crédito y prestaciones" && $estado_reporte == "ABIERTO")
        {
            $btnHtml.='<span class="pull-right"><button  type="button" class="btn btn-success" onclick="AprobarJefeCreditos('.$id_reporte.')">APROBAR <i class="glyphicon glyphicon-ok"></i></button></span>';
        }        
        if( $nombre_rol == "Jefe de recaudaciones" && $estado_reporte == "APROBADO CREDITOS")
        {
            $btnHtml.='<span class="pull-right"><button  type="button" class="btn btn-success" onclick="AprobarJefeRecaudaciones('.$id_reporte.')">APROBAR <i class="glyphicon glyphicon-ok"></i></button></span>';
        }
        if( $nombre_rol == "Contador / Jefe de RR.HH" && $estado_reporte == "APROBADO RECAUDACIONES")
        {
            $btnHtml.='<span class="pull-right"><button  type="button" class="btn btn-success" onclick="AprobarContador('.$id_reporte.')">APROBAR <i class="glyphicon glyphicon-ok"></i></button></span>';
        }
        if( $nombre_rol=="Gerente" && $estado_reporte=="APROBADO CONTADOR")
        {
            $btnHtml.='<span class="pull-right"><button  type="button" class="btn btn-success" onclick="AprobarGerente('.$id_reporte.')">APROBAR <i class="glyphicon glyphicon-ok"></i></button></span>';
        }
        if( $nombre_rol=="Jefe de tesorería" && $estado_reporte=="APROBADO GERENTE")
        {
            $btnHtml.='<span class="pull-right"><button  type="button" class="btn btn-success" onclick="AprobarTesoreria('.$id_reporte.')">APROBAR <i class="glyphicon glyphicon-ok"></i></button></span>';
            $btnHtml.='<span class="pull-right"><a class="btn btn-primary" href="index.php?controller=RevisionCreditos&action=ReporteCreditosaTransferir&ui_activo=1&id_reporte='.$id_reporte.'" role="button" target="_blank">IMPRIMIR <i class="glyphicon glyphicon-print"></i></a></span>';
        }
        
        if ( $nombre_rol == "Jefe de crédito y prestaciones" && $estado_reporte == "APROBADO TESORERIA" )
        {
            $btnHtml.='<span class="pull-right"><a class="btn btn-primary" href="index.php?controller=RevisionCreditos&action=ReporteCreditosaTransferir&ui_activo=0&id_reporte='.$id_reporte.'" role="button" target="_blank">IMPRIMIR <i class="glyphicon glyphicon-print"></i></a></span>';
        }
        
        return $btnHtml;
    }
    
    /**dc 2020/09/03 **/
    public function devolverRevisionListado()
    {
        ob_start();
        $id_creditos    = $_POST['id_creditos'];
        $id_reporte     = $_POST['id_reporte'];
        session_start();
        
        $credito    = new CreditosModel();
        $mensajeDatos   = "";
        
        #CONSULTAMOS estado de credito Preaprobado
        $id_estado  = $credito->obtenerIdEstado("Pre Aprobado");
               
        #CONSULTAMOS si reporte esta en Rev
        $col1   = " bb.id_detalle_creditos_trabajados, bb.id_creditos";
        $tab1   = " core_creditos_trabajados_cabeza aa
            INNER JOIN core_creditos_trabajados_detalle bb ON bb.id_cabeza_creditos_trabajados = aa.id_creditos_trabajados_cabeza
            INNER JOIN estado cc ON cc.id_estado = aa.id_estado_creditos_trabajados_cabeza";
        $whe1   = " cc.nombre_estado = 'DEVUELTO A REVISION' ";
        $id1    = " bb.id_creditos ";
        $rsConsulta1    = $credito->getCondiciones($col1, $tab1, $whe1, $id1);
        
        if( !empty($rsConsulta1) )
        {
            foreach( $rsConsulta1 as $res )
            {
                $id_creditos_consulta = $res->id_creditos;
                
                if( $id_creditos_consulta == $id_creditos )
                {
                    $EliminarReporte    = " DELETE FROM core_creditos_trabajados_detalle WHERE id_cabeza_creditos_trabajados = $id_reporte AND id_creditos = $id_creditos ";
                    
                    $UpdateCredito  = " UPDATE core_creditos SET incluido_reporte_creditos = null, id_estado_creditos = $id_estado WHERE id_creditos = $id_creditos";
                    
                    $credito->executeNonQuery($EliminarReporte);
                    $credito->executeNonQuery($UpdateCredito);
                    
                    $mensajeDatos   = "OK";
                }
                
            }# termina foreach
                       
        }        
        
        $error  = error_get_last();
        $buffer  = ob_get_clean();
                
       
        if( !empty($buffer) || strpos($mensajeDatos, "ERROR") )
        {
            echo "ERROR al devolver crédito en revisión de reporte ",$error['message'];
        }else{
            
            if( !empty($mensajeDatos) && trim($mensajeDatos) == "OK" )
            {
                echo "OK";
            }else 
            {
                echo "No ingreso al metodo, Proceso no realizado";
            }           
            
        }
       
    }
    
    /** dc 2020/09/07 **/
    public function activarReporteCreditos()
    {
        $reporte    = new CreditosModel();
        
        $id_reporte = $_POST['id_reporte'];
        
        $resp   = array();
        
        $col1   = " *";
        $tab1   = " public.estado";
        $whe1   = " tabla_estado = 'core_creditos_trabajados_detalle' and nombre_estado = 'ABIERTO'";
        $rsConsulta1    = $reporte->getCondicionesSinOrden($col1, $tab1, $whe1, "");
        
        if( !empty($rsConsulta1) )
        {
            $id_estado  = $rsConsulta1[0]->id_estado;
            $qryUpdate  = "UPDATE core_creditos_trabajados_cabeza SET id_estado_creditos_trabajados_cabeza = $id_estado  WHERE id_creditos_trabajados_cabeza = $id_reporte ";
            
            $reporte->executeNonQuery($qryUpdate);
        }else
        {
            $resp['mensaje']    = "Nombre Estado no encontrado";
            $resp['estatus']    = "ERROR";
        }
                
        if( !empty(error_get_last() ) )
        {
            $resp = [];
            $resp['mensaje']    = error_get_last()['message'];
            $resp['estatus']    = "ERROR";
        }else 
        {
            $resp['mensaje']    = "";
            $resp['estatus']    = "OK";
        }
        
        echo json_encode($resp);
    }
    /** end dc 2020/09/07 **/
    
    /** dc 2020/09/07 **/
    public function buscarReportesRevision()
    {
        $reporte    = new CreditosModel();
        
        $fecha  = date('Y-m-d');
        $splitfecha = explode("-", $fecha);
        $col1   = " 1";
        $tab1   = " public.core_creditos_trabajados_cabeza aa
            INNER JOIN public.estado bb ON bb.id_estado = id_estado_creditos_trabajados_cabeza";
        $whe1   = " aa.dia_creditos_trabajados_cabeza = $splitfecha[2]
            AND aa.mes_creditos_trabajados_cabeza	= $splitfecha[1]
            AND aa.anio_creditos_trabajados_cabeza  = $splitfecha[0]
            AND bb.nombre_estado = 'DEVUELTO A REVISION'";
        $rsConsulta1    = $reporte->getCondicionesSinOrden($col1, $tab1, $whe1, "");
        
                
        if( !empty($rsConsulta1) )
        {
            echo json_encode(array("EXISTE"));
        }else
        {
           if( !empty(error_get_last()))
           {
               echo "error ".error_get_last()['message'];
           }else{
               //echo "proceda con ingreso de credito al reporte";
               echo json_encode(array("OK"));
           }
        }
       
    }
    /** end dc 2020/09/07 **/

    /** dc 2020/09/08 **/
    public function anularCredito()
    {
        ob_start();
        session_start();
        $credito    = new CreditosModel();
        $id_creditos    = $_POST['id_creditos']; ## prueba $id_creditos=-15;
        
        //$id_usuarios    = $_SESSION['id_usuarios'];
        $usuario_usuarios   = $_SESSION['usuario_usuarios'];
        $mensaje    = "";
        
        $fecha_hoy      = date('Y-m-d');
        
        $credito->beginTran();
        
        #Validamos parametros recibidos
        $col1   = " id_participes, numero_creditos, monto_otorgado_creditos, monto_neto_entregado_creditos";
        $tab1   = " public.core_creditos ";
        $whe1   = " id_creditos = $id_creditos";
        $rsConsulta1    = $credito->getCondicionesSinOrden($col1, $tab1, $whe1, "");
        
        if( empty($rsConsulta1) ){ echo "ERROR datos no validos"; die(); }
        
        #Asignamos valores en variables
        $numero_creditos    = $rsConsulta1[0]->numero_creditos;
        $id_participes      = $rsConsulta1[0]->id_participes;
        
        #BUSCAMOS si existe garantes
        $col2   = " id_participes, numero_creditos, monto_otorgado_creditos, monto_neto_entregado_creditos";
        $tab2   = " public.core_creditos ";
        $whe2   = " id_creditos = $id_creditos";
        $rsConsulta2    = $credito->getCondicionesSinOrden($col2, $tab2, $whe2, "");
        
        if( !empty($rsConsulta2) )
        {
            ## comnezamos el proceso de anular valores en garantias
            
            $col01  = " id_creditos_garantias, valor_creditos_garantias, cuenta_individual_creditos_garantias";
            $tab01  = " public.core_creditos_garantias";
            $whe01  = " id_estado = 1 AND id_creditos = $id_creditos";
            $rsConsulta01   = $credito->getCondicionesSinOrden($col01, $tab01, $whe01, "");
            
            if( !empty($rsConsulta01) )
            {
                $id_creditos_garantias  = $rsConsulta01[0]->id_creditos_garantias;
                $qUpdate    = "UPDATE core_creditos_garantias SET id_estado = 2 WHERE id_creditos_garantias = $id_creditos_garantias";
                $credito->executeNonQuery($qUpdate);
                
                $saldo_garantias= $rsConsulta01[0]->valor_creditos_garantias;
                $observacion    = " Garantía se libera por Anulación de Crédito Num: $id_creditos";
                
                $qInsert    = "INSERT INTO core_creditos_garantias_liberadas VALUES( $id_creditos_garantias, $fecha_hoy, $observacion, $saldo_garantias, $usuario_usuarios);";
                $credito->executeNonQuery($qInsert);
            }
        }
        
        #BUSCAMOS si existe renovaciones
        $col3   = " id_creditos, id_creditos_renovaciones";
        $tab3   = " public.core_creditos_a_pagar_renovaciones";
        $whe3   = " id_creditos = $id_creditos";
        $rsConsulta3    = $credito->getCondicionesSinOrden($col3, $tab3, $whe3, "");
        
        if( empty($rsConsulta3) )
        {
            $id_creditos_renovados = 0;
            foreach( $rsConsulta3 as $res )
            {
                $id_creditos_renovados  = $res->id_creditos_renovaciones;
                
                //SINTAXERROR -- Para realizar el proceso de recalculo de tabla de amortizacion
                
                $qUpdate    = " UPDATE core_creditos_a_pagar_renovaciones SET id_estatus = 2 WHERE id_creditos = $id_creditos AND id_creditos_renovaciones = $id_creditos_renovados";
                $credito->executeNonQuery($qUpdate);
                
            }
            
            $qUpdate    = "UPDATE core_creditos SET id_estado_creditos = 4 WHERE id_creditos = $id_creditos";
            $credito->executeNonQuery($qUpdate);
        }        
        
        $qUpdate    = "UPDATE core_creditos SET id_estado_creditos = 6 WHERE id_creditos = $id_creditos";
        $credito->executeNonQuery($qUpdate);
        
        $error = error_get_last();
        $buffer= ob_get_clean();
        
        if( !empty($buffer) || !empty($error) )
        {
            $mensaje = $error['message'];
            $respuesta  = array('estatus'=>"ERROR", 'mensaje'=>$mensaje);
            $credito->endTran('ROLLBACK');
            echo json_encode($respuesta);
            die();
        }else
        {
            $respuesta  = array('estatus'=>"OK", 'mensaje'=>"Credito Anulado");
            echo json_encode($respuesta);
            $credito->endTran('COMMIT');
            die();
        }
        
    }
    /** end dc 2020/09/08 **/
    
}

?>