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
        $id_rol=$_SESSION["id_rol"];
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
        $creditos=new PlanCuentasModel();       
        $fecha_concesion=$_POST['fecha_concesion'];
        $search=$_POST['search'];
        if ($id_rol==58)
        {
        $columnas=" core_creditos.id_creditos, core_creditos.numero_creditos,core_participes.cedula_participes, core_participes.apellido_participes, core_participes.nombre_participes,
                    core_creditos.monto_otorgado_creditos, core_creditos.plazo_creditos,
                    core_tipo_creditos.nombre_tipo_creditos, oficina.nombre_oficina";
        $tablas="core_creditos INNER JOIN core_estado_creditos
                ON core_creditos.id_estado_creditos=core_estado_creditos.id_estado_creditos
                INNER JOIN core_participes
                ON core_participes.id_participes = core_creditos.id_participes
                INNER JOIN core_tipo_creditos
                ON core_tipo_creditos.id_tipo_creditos=core_creditos.id_tipo_creditos
                INNER JOIN usuarios
                ON core_creditos.receptor_solicitud_creditos = usuarios.usuario_usuarios
                INNER JOIN oficina
                ON oficina.id_oficina=usuarios.id_oficina";
        $where="core_estado_creditos.id_estado_creditos=2 AND core_creditos.incluido_reporte_creditos IS NULL";
        if(!(empty($fecha_concesion)))
        {
            $where.=" AND fecha_concesion_creditos='".$fecha_concesion."'";
        }
        if(!(empty($search)))
        {
            $where.=" AND (core_participes.cedula_participes LIKE '".$search."%' OR core_participes.nombre_participes ILIKE '".$search."%'
                       OR core_participes.apellido_participes ILIKE '".$search."%')";
        }
        
        $id="core_creditos.numero_creditos";
        $html="";
        $resultSet=$creditos->getCantidad("*", $tablas, $where);
        $cantidadResult=(int)$resultSet[0]->total;
        
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$creditos->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
        $count_query   = $cantidadResult;
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
                $html.='<td style="font-size: 14px;"><span class="pull-right"><a href="index.php?controller=SolicitudPrestamo&action=print&id_solicitud_prestamo='.$id_solicitud.'" target="_blank" class="btn btn-warning" title="Imprimir"><i class="glyphicon glyphicon-file"></i></a></span></td>';
                $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-primary" onclick="AgregarReporte('.$res->id_creditos.')"><i class="glyphicon glyphicon-plus"></i></button></span></td>';
                $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
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
            
            $resultSet=$reportes->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
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
                $html.= "<table id='tabla_reportes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
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
                    $html.='<td style="font-size: 14px;"><button  type="button" class="btn btn-warning" onclick="AbrirReporte('.$res->id_creditos_trabajados_cabeza.')"><i class="glyphicon glyphicon-open-file"></i></button></span></td>';
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
        $tablas="core_creditos_trabajados_cabeza INNER JOIN oficina
            		ON oficina.id_oficina=core_creditos_trabajados_cabeza.id_oficina
            		INNER JOIN estado
            		ON estado.id_estado=core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza";
        
        if($resultRol=="Jefe de crédito y prestaciones") $where="1=1";
        if($resultRol=="Jefe de recaudaciones")$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza>=92 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";;
        if($resultRol=="Contador / Jefe de RR.HH")$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza>=93 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";
        if($resultRol=="Gerente")$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza>=95 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";
        if($resultRol=="Jefe de tesorería")$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza>=96 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";
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
        
        $resultSet=$reportes->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
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
                $html.='<td style="font-size: 14px;"><button  type="button" class="btn btn-warning" onclick="AbrirReporte('.$res->id_creditos_trabajados_cabeza.')"><i class="glyphicon glyphicon-open-file"></i></button></span></td>';
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
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
        $creditos=new PlanCuentasModel();
         $columnas="estado.nombre_estado";
         $tablas="core_creditos_trabajados_cabeza INNER JOIN estado
                  ON core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza = estado.id_estado";
         $where="id_creditos_trabajados_cabeza=".$id_reporte;
         $id="id_estado_creditos_trabajados_cabeza";
         $estado_reporte=$creditos->getCondiciones($columnas, $tablas, $where, $id);
         $estado_reporte=$estado_reporte[0]->nombre_estado;
            $columnas="core_creditos.id_creditos,core_creditos.numero_creditos,core_participes.cedula_participes, core_participes.apellido_participes, core_participes.nombre_participes,
                    core_creditos.monto_otorgado_creditos, core_creditos.monto_neto_entregado_creditos, core_creditos.plazo_creditos,
                    core_tipo_creditos.nombre_tipo_creditos, oficina.nombre_oficina, core_creditos_trabajados_detalle.observacion_detalle_creditos_trabajados,
                    core_creditos.id_ccomprobantes";
            $tablas="core_creditos_trabajados_detalle INNER JOIN core_creditos
                ON core_creditos_trabajados_detalle.id_creditos = core_creditos.id_creditos
                INNER JOIN core_estado_creditos
                ON core_creditos.id_estado_creditos=core_estado_creditos.id_estado_creditos
                INNER JOIN core_participes
                ON core_participes.id_participes = core_creditos.id_participes
                INNER JOIN core_tipo_creditos
                ON core_tipo_creditos.id_tipo_creditos=core_creditos.id_tipo_creditos
                INNER JOIN usuarios
                ON core_creditos.receptor_solicitud_creditos = usuarios.usuario_usuarios
                INNER JOIN oficina
                ON oficina.id_oficina=usuarios.id_oficina";
            $where="core_creditos_trabajados_detalle.id_cabeza_creditos_trabajados=".$id_reporte;
            
            $id="core_creditos.numero_creditos";
            $html="";
            $resultSet=$creditos->getCondiciones($columnas, $tablas, $where, $id);
            $columnas="nombre_rol";
            $tablas="rol";
            $where="id_rol=".$id_rol;
            
            $id="id_rol";
            $resultRol=$creditos->getCondiciones($columnas, $tablas, $where, $id);
            $resultRol=$resultRol[0]->nombre_rol;
            $cantidadResult=sizeof($resultSet);
            if($cantidadResult>0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:570px; overflow-y:scroll;">';
                $html.= "<table id='tabla_creditos_reporte' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
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
                
                foreach ($resultSet as $res)
                {
                    
                    $columnas="id_solicitud_prestamo";
                    $tabla="solicitud_prestamo";
                    $where="numero_creditos='".$res->numero_creditos."'";
                    $id_solicitud=$db->getCondiciones($columnas, $tabla, $where);
                    $id_solicitud=$id_solicitud[0]->id_solicitud_prestamo;
                    
                    
                    $html.='<tr>';
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
                    $html.='<td style="font-size: 14px;"><span class="pull-right"><a href="index.php?controller=SolicitudPrestamo&action=print&id_solicitud_prestamo='.$id_solicitud.'" target="_blank" class="btn btn-warning" title="Ver Solicitud"><i class="glyphicon glyphicon-file"></i></a></span></td>';
                    
                    
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
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$id_reporte.','.$res->id_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                    }
                    if ($resultRol=="Jefe de tesorería" && $estado_reporte=="APROBADO GERENTE")
                    {
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$id_reporte.','.$res->id_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                    }
                   
                $html.='</tr>';
                if ($estado_reporte=="DEVUELTO A REVISION")
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
            if ($resultRol=="Jefe de crédito y prestaciones" && $estado_reporte=="ABIERTO")
            {
                $html.='<span class="pull-right"><button  type="button" class="btn btn-success" onclick="AprobarJefeCreditos('.$id_reporte.')">APROBAR <i class="glyphicon glyphicon-ok"></i></button></span>';
            }
            if ($resultRol=="Jefe de recaudaciones" && $estado_reporte=="APROBADO CREDITOS")
            {
                $html.='<span class="pull-right"><button  type="button" class="btn btn-success" onclick="AprobarJefeRecaudaciones('.$id_reporte.')">APROBAR <i class="glyphicon glyphicon-ok"></i></button></span>';
            } 
            if ($resultRol=="Contador / Jefe de RR.HH" && $estado_reporte=="APROBADO RECAUDACIONES")
            {
                $html.='<span class="pull-right"><button  type="button" class="btn btn-success" onclick="AprobarContador('.$id_reporte.')">APROBAR <i class="glyphicon glyphicon-ok"></i></button></span>';
            }
            if ($resultRol=="Gerente" && $estado_reporte=="APROBADO CONTADOR")
            {
                $html.='<span class="pull-right"><button  type="button" class="btn btn-success" onclick="AprobarGerente('.$id_reporte.')">APROBAR <i class="glyphicon glyphicon-ok"></i></button></span>';
            }
            if ($resultRol=="Jefe de tesorería" && $estado_reporte=="APROBADO GERENTE")
            {
                $html.='<span class="pull-right"><button  type="button" class="btn btn-success" onclick="AprobarTesoreria('.$id_reporte.')">APROBAR <i class="glyphicon glyphicon-ok"></i></button></span>';
            }
            if ($resultRol=="Jefe de crédito y prestaciones" && $estado_reporte=="APROBADO TESORERIA")
            {
                $html.='<span class="pull-right"><a class="btn btn-primary" href="index.php?controller=RevisionCreditos&action=ReporteCreditosaTransferir&id_reporte='.$id_reporte.'" role="button" target="_blank">IMPRIMIR <i class="glyphicon glyphicon-print"></i></a></span>';
            }
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
        $tablas="public.core_creditos_trabajados_cabeza INNER JOIN estado
             ON estado.id_estado=core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza";
        $where="nombre_estado='ABIERTO'";
        $id="core_creditos_trabajados_cabeza.id_creditos_trabajados_cabeza";
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
        $html.='</select>
       <div id="mensaje_cuotas_credito" class="errores"></div>';
        
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
        
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'ABIERTO'";
        $idest = "estado.id_estado";
        $resultEst = $reportes->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        $resultEst=$resultEst[0]->id_estado;
        
        if ($id_reporte==0)
        {
          $dia= date('d');
          $mes= date('m');
          $year= date('Y');
          $columnas="id_creditos_trabajados_cabeza, id_estado_creditos_trabajados_cabeza";
          $tablas="core_creditos_trabajados_cabeza INNER JOIN estado
                        ON estado.id_estado = core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza";
          $where="anio_creditos_trabajados_cabeza = ".$year."
                	AND mes_creditos_trabajados_cabeza = ".$mes."
                	AND dia_creditos_trabajados_cabeza = ".$dia."
                    AND NOT (nombre_estado='DEVUELTO A REVISION')";
          $id="id_creditos_trabajados_cabeza";
          $resultRpts=$reportes->getCondiciones($columnas, $tablas, $where, $id);
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
             
              $reportes->setFuncion($funcion);
              $reportes->setParametros($parametros);
              $resultado=$reportes->Insert();
              
              $resultRpts=$reportes->getCondiciones($columnas, $tablas, $where, $id);
              $id_reporte=$resultRpts[0]->id_creditos_trabajados_cabeza;
              $inserta_credito=$this->AddCreditosToReport($id_reporte, $id_credito);
              $inserta_credito=trim($inserta_credito);
              $where = "id_creditos=".$id_credito;
              $tabla = "core_creditos";
              $colval = "incluido_reporte_creditos=1";
              
              if (empty($inserta_credito))  $reportes->UpdateBy($colval, $tabla, $where);
          }
          else
          {
              
              $columnas="id_creditos_trabajados_cabeza, nombre_estado";
              $tablas="core_creditos_trabajados_cabeza INNER JOIN estado
                        ON estado.id_estado = core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza";
              $where="anio_creditos_trabajados_cabeza = ".$year."
                	AND mes_creditos_trabajados_cabeza = ".$mes."
                	AND dia_creditos_trabajados_cabeza = ".$dia."
                    AND NOT (nombre_estado='DEVUELTO A REVISION')";
              $id="id_creditos_trabajados_cabeza";
              $resultRpts=$reportes->getCondiciones($columnas, $tablas, $where, $id);
              $id_estado=$resultRpts[0]->nombre_estado;
              if($id_estado!="ABIERTO")
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
              }
             
          }
        }
        else
        {
            $dia= date('d');
            $mes= date('m');
            $year= date('Y');
            $columnas="id_creditos_trabajados_cabeza, nombre_estado";
            $tablas="core_creditos_trabajados_cabeza INNER JOIN estado
                        ON estado.id_estado = core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza";
            $where="id_creditos_trabajados_cabeza = ".$id_reporte;
            $id="id_creditos_trabajados_cabeza";
            $resultRpts=$reportes->getCondiciones($columnas, $tablas, $where, $id);
            $id_estado=$resultRpts[0]->nombre_estado;
            if($id_estado!="ABIERTO")
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
            }
            
        }
        
    }
    
    public function AddCreditosToReport($id_reporte, $id_credito)
    {
        
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
        $id_reporte=$_POST['id_reporte'];
        $reporte = new PermisosEmpleadosModel();
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
            echo $mensaje."---|id_credito->".$res->id_creditos."||\n";
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
        $id_reporte=$_POST['id_reporte'];
        $reporte = new PermisosEmpleadosModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'APROBADO TESORERIA'";
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
    

    
    
    public function ReporteCreditosaTransferir($param) {
        
        
        session_start();
        $entidades = new EntidadesModel();
        $ccomprobantes = new CComprobantesModel();
        $dcomprobantes = new DComprobantesModel();
        $tipo_comprobantes = new TipoComprobantesModel();
        $entidades = new EntidadesModel();
        $tipo_comprobante=new TipoComprobantesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
        $rp_capremci= new PlanCuentasModel();
        $id_reporte=$_GET['id_reporte'];
        
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
        
        $Usuarios1= new UsuariosModel();
        //contador
        $datos_rol_contador = array();
        
        
        $columnas="  usuarios.id_usuarios, 
                     usuarios.nombre_usuarios, 
                     usuarios.apellidos_usuarios, 
                     rol.id_rol, 
                     rol.nombre_rol";
        $tablas="    public.usuarios, 
                     public.rol";
        $where="rol.id_rol = usuarios.id_rol AND rol.nombre_rol='Contador / Jefe de RR.HH'";
        $id="rol.nombre_rol";
        
        $resultCont=$Usuarios1->getCondiciones($columnas, $tablas, $where, $id);
        
        
        $datos_rol_contador['NOMCONTA']=ucfirst($resultCont[0]->nombre_usuarios);
        $datos_rol_contador['APECONTA']=ucfirst($resultCont[0]->apellidos_usuarios);
        
        //Gerente
        $datos_rol_gerente= array();
        
        
        $columnas="  usuarios.id_usuarios,
                     usuarios.nombre_usuarios,
                     usuarios.apellidos_usuarios,
                     rol.id_rol,
                     rol.nombre_rol";
        $tablas="    public.usuarios,
                     public.rol";
        $where="rol.id_rol = usuarios.id_rol AND rol.nombre_rol='Gerente'";
        $id="rol.nombre_rol";
        
        $resultGer=$Usuarios1->getCondiciones($columnas, $tablas, $where, $id);
        
        
        $datos_rol_gerente['NOMGER']=ucfirst($resultGer[0]->nombre_usuarios);
        $datos_rol_gerente['APEGER']=ucfirst($resultGer[0]->apellidos_usuarios);
        
        //Jefe Credito
        
        $datos_rol_jcredito= array();

            $datos_rol_jcredito['NOMJCRE']=(isset($_SESSION['nombre_usuarios']))?$_SESSION['nombre_usuarios']:'';
            $datos_rol_jcredito['APEJCRE']=(isset($_SESSION['apellidos_usuarios']))?$_SESSION['apellidos_usuarios']:'';
            
        
        //TABLA
        
        
        $datos_reporte = array();
        
        $columnas="core_creditos.numero_creditos,core_participes.cedula_participes,core_participes.apellido_participes,core_participes.nombre_participes,
                    core_creditos.monto_otorgado_creditos, core_creditos.plazo_creditos, core_creditos.monto_neto_entregado_creditos,
                    core_tipo_creditos.nombre_tipo_creditos, core_estado_creditos.nombre_estado_creditos, forma_pago.nombre_forma_pago,
                    tes_bancos.nombre_bancos, core_tipo_cuentas.nombre_tipo_cuentas,core_participes_cuentas.numero_participes_cuentas, core_participes.celular_participes,
                    core_creditos_retenciones.monto_creditos_retenciones";
        $tablas="core_creditos INNER JOIN core_participes
                    ON core_creditos.id_participes = core_participes.id_participes
                    INNER JOIN core_creditos_trabajados_detalle
                    ON core_creditos.id_creditos=core_creditos_trabajados_detalle.id_creditos
                    INNER JOIN core_creditos_trabajados_cabeza
                    ON core_creditos_trabajados_cabeza.id_creditos_trabajados_cabeza=core_creditos_trabajados_detalle.id_cabeza_creditos_trabajados
                    INNER JOIN core_tipo_creditos
                    ON core_tipo_creditos.id_tipo_creditos=core_creditos.id_tipo_creditos
                    INNER JOIN core_estado_creditos
                    ON core_estado_creditos.id_estado_creditos=core_creditos.id_estado_creditos
                    INNER JOIN forma_pago
                    ON forma_pago.id_forma_pago=core_creditos.id_forma_pago
                    INNER JOIN core_participes_cuentas
                    ON core_participes_cuentas.id_participes = core_participes.id_participes
                    INNER JOIN tes_bancos
                    ON tes_bancos.id_bancos = core_participes_cuentas.id_bancos
                    INNER JOIN core_tipo_cuentas
                    ON core_tipo_cuentas.id_tipo_cuentas=core_participes_cuentas.id_tipo_cuentas
                    INNER JOIN core_creditos_retenciones
                    ON core_creditos_retenciones.id_creditos = core_creditos.id_creditos";
        $where="id_creditos_trabajados_cabeza=".$id_reporte."AND cuenta_principal=true";
        $id="tes_bancos.nombre_bancos";
        
        $resultSet=$rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
        
        $columnas="core_creditos.numero_creditos,core_participes.cedula_participes,core_participes.apellido_participes,core_participes.nombre_participes,
                    core_creditos.monto_otorgado_creditos, core_creditos.plazo_creditos, core_creditos.monto_neto_entregado_creditos,
                    core_tipo_creditos.nombre_tipo_creditos, core_estado_creditos.nombre_estado_creditos, forma_pago.nombre_forma_pago,
					core_participes.celular_participes,
                    core_creditos_retenciones.monto_creditos_retenciones";
        $tablas="core_creditos INNER JOIN core_participes
                    ON core_creditos.id_participes = core_participes.id_participes
                    INNER JOIN core_creditos_trabajados_detalle
                    ON core_creditos.id_creditos=core_creditos_trabajados_detalle.id_creditos
                    INNER JOIN core_creditos_trabajados_cabeza
                    ON core_creditos_trabajados_cabeza.id_creditos_trabajados_cabeza=core_creditos_trabajados_detalle.id_cabeza_creditos_trabajados
                    INNER JOIN core_tipo_creditos
                    ON core_tipo_creditos.id_tipo_creditos=core_creditos.id_tipo_creditos
                    INNER JOIN core_estado_creditos
                    ON core_estado_creditos.id_estado_creditos=core_creditos.id_estado_creditos
                    INNER JOIN forma_pago
                    ON forma_pago.id_forma_pago=core_creditos.id_forma_pago
                    INNER JOIN core_creditos_retenciones
                    ON core_creditos_retenciones.id_creditos = core_creditos.id_creditos";
        $where="id_creditos_trabajados_cabeza=".$id_reporte." AND nombre_forma_pago='CHEQUE'";
        $id="core_creditos.numero_creditos";
        
        $resultSetCheques=$rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
        
        $html='';
        
            $html.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
            $html.= "<tr>";
            $html.='<th style="text-align: center; font-size: 11px;">No.</th>';
            $html.='<th style="text-align: center; font-size: 11px;">No.PRESTAMO</th>';
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
        
        for($i=0; $i<$ultimo_cheques; $i++)
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
        
        
       if($pagos_cheque>0)
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
        $id_reporte=$_POST['id_reporte'];
        $numero_credito=$_POST['numero_credito'];
        $observacion_credito=$_POST['observacion_credito'];
        $id_rol=$_SESSION['id_rol'];
        $reporte = new PermisosEmpleadosModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='core_creditos_trabajados_detalle' AND estado.nombre_estado = 'DEVUELTO A REVISION'";
        $idest = "estado.id_estado";
        $resultEst = $reporte->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        $reporte->beginTran();
        $where = "id_creditos_trabajados_cabeza=".$id_reporte;
        $tabla = "core_creditos_trabajados_cabeza";
        $colval = "id_estado_creditos_trabajados_cabeza=".$resultEst[0]->id_estado;
        $reporte->UpdateBy($colval, $tabla, $where);
        
        $where = "id_cabeza_creditos_trabajados=".$id_reporte;
        $tabla = "core_creditos_trabajados_detalle";
        $colval = "id_estado_detalle_creditos_trabajados=".$resultEst[0]->id_estado;
        $reporte->UpdateBy($colval, $tabla, $where);
        
        $where = "id_creditos=".$numero_credito;
        $tabla = "core_creditos_trabajados_detalle";
        $colval = "observacion_detalle_creditos_trabajados='".$observacion_credito."'";
        $reporte->UpdateBy($colval, $tabla, $where);
        
        $columnas="id_creditos";
        $tablas="core_creditos_trabajados_detalle";
        $where="id_cabeza_creditos_trabajados=".$id_reporte;
        $id="id_creditos";
        $resultSet=$reporte->getCondiciones($columnas, $tablas, $where, $id);
        
        $columnas="id_estado_creditos";
        $tablas="core_estado_creditos";
        $where="nombre_estado_creditos='Pre Aprobado'";
        $id="id_estado_creditos";
        $id_estado_creditos=$reporte->getCondiciones($columnas, $tablas, $where, $id);
        $id_estado_creditos=$id_estado_creditos[0]->id_estado_creditos;
        
        
        if ($id_rol==53 || $id_rol==61)
        {
            require_once 'controller/CreditosController.php';
            
            $ctr_creditos= new CreditosController();
            
            $desmayorizacion=$ctr_creditos->EliminarReporteCredito($numero_credito);
        }
        
        if ($desmayorizacion=="OK")
        {
            foreach ($resultSet as $res)
            {
                $where = "id_creditos=".$res->id_creditos;
                $tabla = "core_creditos";
                $colval = "id_estado_creditos=".$id_estado_creditos.", incluido_reporte_creditos=null";
                $reporte->UpdateBy($colval, $tabla, $where);
            }
            
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
        }
        else
        {
            $reporte->endTran('ROLLBACK');
            $mensaje="ERROR".$desmayorizacion;
        }
        
        
       
        echo $mensaje;
    }
    
    public function QuitarCredito()
    {
        session_start();
        ob_start();
        $id_reporte=$_POST['id_reporte'];
        $numero_credito=$_POST['numero_credito'];
        $reporte = new PermisosEmpleadosModel();
        $creditos_detalle= new CreditosTrabajadosDetalleModel();
        
        $query="DELETE FROM core_creditos_trabajados_detalle
                WHERE  id_cabeza_creditos_trabajados=".$id_reporte." AND id_creditos=".$numero_credito;        
        $delete=$creditos_detalle->executeNonQuery($query);
        
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

}

?>