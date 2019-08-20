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
        $columnas="core_creditos.numero_creditos,core_participes.cedula_participes, core_participes.apellido_participes, core_participes.nombre_participes,
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
                    $where="identificador_consecutivos='".$res->numero_creditos."'";
                $id_solicitud=$db->getCondiciones($columnas, $tabla, $where);
                $id_solicitud=$id_solicitud[0]->id_solicitud_prestamo;
           
               
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
                $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-primary" onclick="AgregarReporte('.$res->numero_creditos.')"><i class="glyphicon glyphicon-plus"></i></button></span></td>';
                $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->numero_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
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
        
            $columnas="id_creditos_trabajados_cabeza, anio_creditos_trabajados_cabeza, mes_creditos_trabajados_cabeza,
                         dia_creditos_trabajados_cabeza, oficina.nombre_oficina, estado.nombre_estado";
            $tablas="core_creditos_trabajados_cabeza INNER JOIN oficina
            		ON oficina.id_oficina=core_creditos_trabajados_cabeza.id_oficina
            		INNER JOIN estado
            		ON estado.id_estado=core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza";
           
            if($id_rol==58) $where="1=1";
            if($id_rol==51)$where="estado.nombre_estado='APROBADO CREDITOS'";
            if($id_rol==59)$where="estado.nombre_estado='APROBADO RECAUDACIONES'";
            if($id_rol==48)$where="estado.nombre_estado='APROBADO SISTEMAS'";
            if($id_rol==61)$where="estado.nombre_estado='APROBADO CONTADOR'";
            if($id_rol==53)$where="estado.nombre_estado='APROBADO GERENTE'";
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
        
        $columnas="id_creditos_trabajados_cabeza, anio_creditos_trabajados_cabeza, mes_creditos_trabajados_cabeza,
                         dia_creditos_trabajados_cabeza, oficina.nombre_oficina, estado.nombre_estado";
        $tablas="core_creditos_trabajados_cabeza INNER JOIN oficina
            		ON oficina.id_oficina=core_creditos_trabajados_cabeza.id_oficina
            		INNER JOIN estado
            		ON estado.id_estado=core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza";
        
        if($id_rol==58) $where="1=1";
        if($id_rol==51)$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza>=92 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";
        if($id_rol==59)$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza>=93 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";
        if($id_rol==48)$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza>=94 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";
        if($id_rol==61)$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza>=95 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";
        if($id_rol==53)$where="core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza>=96 AND NOT (core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza=98)";
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
            $columnas="core_creditos.numero_creditos,core_participes.cedula_participes, core_participes.apellido_participes, core_participes.nombre_participes,
                    core_creditos.monto_otorgado_creditos, core_creditos.plazo_creditos,
                    core_tipo_creditos.nombre_tipo_creditos, oficina.nombre_oficina";
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
                    $where="identificador_consecutivos='".$res->numero_creditos."'";
                    $id_solicitud=$db->getCondiciones($columnas, $tabla, $where);
                    $id_solicitud=$id_solicitud[0]->id_solicitud_prestamo;
                    
                    
                    $html.='<tr>';
                    $html.='<td style="font-size: 14px;">'.$res->numero_creditos.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->cedula_participes.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->apellido_participes.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_participes.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->monto_otorgado_creditos.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->plazo_creditos.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_tipo_creditos.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_oficina.'</td>';
                    $html.='<td style="font-size: 14px;"><span class="pull-right"><a href="index.php?controller=SolicitudPrestamo&action=print&id_solicitud_prestamo='.$id_solicitud.'" target="_blank" class="btn btn-warning" title="Ver Solicitud"><i class="glyphicon glyphicon-file"></i></a></span></td>';
                    
                    
                    if ($resultRol=="Jefe de crédito y prestaciones" && $estado_reporte=="ABIERTO")
                    {
                    $html.='<td style="font-size: 18px;"></td>';
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->numero_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                    }
                   
                $html.='</tr>';
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
    
    public function GetReportes()
    {
        session_start();
        
        $reportes = new PlanCuentasModel();
        $tablas="public.core_creditos_trabajados_cabeza";
        $where="1=1";
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
        $resultEst=$resultEst[0]->nombre_estado;
        
        if ($id_reporte==0)
        {
          $dia= date('d');
          $mes= date('m');
          $year= date('Y');
          $columnas="id_creditos_trabajados_cabeza, id_estado_creditos_trabajados_cabeza";
          $tablas="core_creditos_trabajados_cabeza";
          $where="anio_creditos_trabajados_cabeza = ".$year."
                	AND mes_creditos_trabajados_cabeza = ".$mes."
                	AND dia_creditos_trabajados_cabeza = ".$dia;
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
                     '$resultEst'";//remplazar por id de la tabla estados
             
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
                	AND dia_creditos_trabajados_cabeza = ".$dia;
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
            $tablas=" INNER JOIN estado
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
        $resultEst=$resultEst[0]->nombre_estado;
        
        
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
        
        $columnas="id_creditos";
        $tablas="core_creditos_trabajados_detalle";
        $where="id_cabeza_creditos_trabajados=".$id_reporte;
        $id="id_creditos";
        $resultSet=$reporte->getCondiciones($columnas, $tablas, $where, $id);
        $i=0;
        foreach ($resultSet as $res)
        {
            $i++;
            $where = "id_creditos=".$res->id_creditos;
            $tabla = "core_creditos";
            $colval = "id_estado_creditos=3";// cambiar valor
            $reporte->UpdateBy($colval, $tabla, $where);
            $mensaje=$this->ActivaCredito($res->id_creditos);
        }
        
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
    
    public function AprobarReporteSistemas()
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
        
    }
    
    public function AprobarReporteContador()
    {
        session_start();
        $id_reporte=$_POST['id_reporte'];
        $reporte = new PermisosEmpleadosModel();
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
        
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
        
        $id_creditos = $paramIdCredito;
        
        if(is_null($id_creditos)){
            echo '<message> parametros no recibidos <message>';
            return;
        }
        
        try {
            
            
            $Credito->beginTran();
            
            //creacion de lote
            $nombreLote = "CXP".$_SESSION['usuario_usuarios'];
            $descripcionLote = "GENERACION CREDITO";
            $id_frecuencia = 1;
            $id_usuarios = $_SESSION['id_usuarios'];
            $funcionLote = "tes_genera_lote";
            $paramLote = "'$nombreLote','$descripcionLote','$id_frecuencia','$id_usuarios'";
            $consultaLote = " SELECT ".$funcionLote." ( ".$paramLote." )";
            $ResultLote = $Credito->llamarconsultaPG($consultaLote);
            $_id_lote = 0;
            $error = "";
            $error = pg_last_error();
            if (!empty($error) || (int)$ResultLote[0] <= 0){
                throw new Exception('error ingresando lote');
            }
            
            $_id_lote = (int)$ResultLote[0];
            
            /*insertado de cuentas por pagar*/
            //busca consecutivo
            $queryConsecutivo = "SELECT id_consecutivos, LPAD(valor_consecutivos::TEXT,espacio_consecutivos,'0') AS numero_consecutivos FROM consecutivos
                WHERE id_entidades = 1 AND nombre_consecutivos='CxP'";
            $ResultConsecutivo= $Credito->enviaquery($queryConsecutivo);
            
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
            $_id_proveedor = 0;
            $queryProveedor = "SELECT 1,p.id_proveedores
                    FROM core_participes cp
                    INNER JOIN proveedores p
                    ON p.identificacion_proveedores = cp.cedula_participes
                    INNER JOIN core_creditos cc
                    ON cc.id_participes = cp.id_participes
                    WHERE cc.id_creditos = $id_creditos ";
            $ResultProveedor= $Credito->enviaquery($queryProveedor);
            
            if(empty($ResultProveedor)){
                
                $funcionProveedor = "ins_proveedores_participes";
                $parametrosProveedor = " '$id_creditos' ";
                $consultaProveedor = $Credito->getconsultaPG($funcionProveedor, $parametrosProveedor);
                $ResultadoProveedor= $Credito->llamarconsultaPG($consultaProveedor);
                $error = "";
                $error = pg_last_error();
                if (!empty($error) || (int)$ResultadoProveedor[0] <= 0){
                    throw new Exception('error proveedores');
                }
                $_id_proveedor = $ResultadoProveedor[0];
            }else{
                $_id_proveedor = $ResultProveedor[0]->id_proveedores;
            }
            
            //para datos de banco
            $_id_bancos = 2 ; //seteado para presentacion luego va con deacuerdo el credito
            
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
            
            $_descripcion_cuentas_pagar = "Cuenta x Pagar Credito CREDITO $numero_credito ";
            
            foreach ($ResultCredito as $res){
                $codigo_credito=$res->codigo_tipo_creditos;
                $monto_credito = $res->monto_otorgado_creditos;
                $monto_entregado_credito = $res->monto_neto_entregado_creditos;
                $id_tipo_credito = $res->id_tipo_creditos;
                
            }
            
            //valores de cuentas por pagar
            $_compra_cuentas_pagar = $monto_credito;
            $_total_cuentas_pagar = $_compra_cuentas_pagar - $_impuesto_cuentas_pagar;
            $_saldo_cuentas_pagar = $_compra_cuentas_pagar - $_impuesto_cuentas_pagar;
            
            /*inserccion de cuentas x pagar*/
            //generar cuentas contables de cuentas por pagar
            
            //DIFERENCIAR MONTO SOLICITADO MONTO ENTREGADO
            if($monto_credito != $monto_entregado_credito){
                //para monto en refinaciacion y otras
            }else{
                //para insertado normal
                $queryParametrizacion = "SELECT * FROM core_parametrizacion_cuentas
                                    WHERE id_principal_core_parametrizacion_cuentas = $id_tipo_credito";
                $ResultParametrizacion = $Credito -> enviaquery($queryParametrizacion);
                
                //buscar de tabla parametrizacion
                $iorden=0;
                foreach ($ResultParametrizacion as $res){
                    $iorden = 1;
                    $queryDistribucion = "INSERT INTO tes_distribucion_cuentas_pagar
                        (id_lote,id_plan_cuentas,tipo_distribucion_cuentas_pagar,debito_distribucion_cuentas_pagar,credito_distribucion_cuentas_pagar,ord_distribucion_cuentas_pagar,referencia_distribucion_cuentas_pagar)
                        VALUES ( '$_id_lote','$res->id_plan_cuentas_debe','COMPRA','0.00','$monto_entregado_credito','$iorden','$_descripcion_cuentas_pagar')";
                    
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
                        VALUES ( '$_id_lote','$res->id_plan_cuentas_haber','PAGOS','$monto_entregado_credito','0.00','$iorden','$_descripcion_cuentas_pagar')";
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
            
            $_id_usuarios = $id_usuarios;
            //datos de cuentas x pagar
            $funcionCuentasPagar = "tes_ins_cuentas_pagar";
            $paramCuentasPagar = "'$_id_lote', '$_id_consecutivos', '$_id_tipo_documento', '$_id_proveedor', '$_id_bancos',
            '$_id_moneda', '$_descripcion_cuentas_pagar', '$_fecha_cuentas_pagar', '$_condiciones_pago_cuentas_pagar', '$_num_documento_cuentas_pagar',
            '$_num_ord_compra','$_metodo_envio_cuentas_pagar', '$_compra_cuentas_pagar', '$_desc_comercial','$_flete_cuentas_pagar',
            '$_miscelaneos_cuentas_pagar','$_impuesto_cuentas_pagar', '$_total_cuentas_pagar','$_monto1099_cuentas_pagar','$_efectivo_cuentas_pagar',
            '$_cheque_cuentas_pagar', '$_tarjeta_credito_cuentas_pagar', '$_condonaciones_cuentas_pagar', '$_saldo_cuentas_pagar', '$_id_cuentas_pagar'";
            
            $consultaCuentasPagar = $Credito->getconsultaPG($funcionCuentasPagar, $paramCuentasPagar);
            $ResultCuentaPagar = $Credito -> llamarconsultaPG($consultaCuentasPagar);
            
            $error = "";
            $error = pg_last_error();
            if(!empty($error) || $ResultCuentaPagar[0] <= 0 )
                throw new Exception('error inserccion cuentas pagar');
                
                // secuencial de cuenta por pagar
                $_id_cuentas_pagar = $ResultCuentaPagar[0];
                
                $funcionComprobante = "tes_agrega_comprobante_cuentas_pagar";
                $parametrosComprobante = "
                    '$_id_usuarios',
                    '$_id_lote',
                    '$_id_proveedor',
                    '',
                    '$_descripcion_cuentas_pagar',
                    '$_total_cuentas_pagar',
                    'cero',
                    '$_fecha_cuentas_pagar',
                    null,
                    '',
                    '',
                    '',
                    ''
                    ";
                
                $consultaComprobante = $Credito ->getconsultaPG($funcionComprobante, $parametrosComprobante);
                $resultadComprobantes = $Credito->llamarconsultaPG($consultaComprobante);
                
                $error = "";
                $error = pg_last_error();
                if(!empty($error) || $resultadComprobantes[0] <= 0 )
                    throw new Exception('error inserccion cuentas pagar');
                    
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
                    
                    //para actualizar la forma de pago en cuentas por pagar
                    //--buscar
                    $queryBuscaFormaPago = "SELECT id_creditos,numero_creditos FROM core_creditos WHERE id_creditos = $id_creditos";
                    $RsCreditoPago = $Credito -> enviaquery($queryBuscaFormaPago);
                    $numero_de_credito = $RsCreditoPago[0]->numero_creditos;
                    
                    $columnas="id_solicitud_prestamo,nombre_banco_cuenta_bancaria,tipo_pago_cuenta_bancaria,numero_cuenta_cuenta_bancaria,tipo_cuenta_cuenta_bancaria";
                    $tabla="solicitud_prestamo";
                    $where="identificador_consecutivos='".$numero_de_credito."'";
                    $RsSolicitud = $db->getCondiciones($columnas, $tabla, $where);
                    $_sol_nombre_banco = $RsSolicitud[0]->nombre_banco_cuenta_bancaria;
                    $_sol_tipo_pago = $RsSolicitud[0]->tipo_pago_cuenta_bancaria;
                    $_sol_numero_cuenta = $RsSolicitud[0]->numero_cuenta_cuenta_bancaria;
                    $_sol_tipo_cuenta_banco = $RsSolicitud[0]->tipo_cuenta_cuenta_bancaria;
                    
                    $_id_forma_pago = null;
                    $queryFormaPago="";
                    switch ($_sol_tipo_pago){
                        case "Depósito":
                            $queryFormaPago = "SELECT * FROM forma_pago WHERE nombre_forma_pago = 'TRANSFERENCIA'";
                            break;
                        case "Retira Cheque":
                            $queryFormaPago = "SELECT * FROM forma_pago WHERE nombre_forma_pago = 'CHEQUE'";
                            break;
                    }
                    
                    $rsFormaPago = $Credito->enviaquery($queryFormaPago);
                    $_id_forma_pago = $rsFormaPago[0]->id_forma_pago;
                    
                    $columnaPago = "id_forma_pago = $_id_forma_pago ";
                    $tablasPago = "tes_cuentas_pagar";
                    $wherePago = "id_cuentas_pagar = $_id_cuentas_pagar";
                    $UpdateFormaPago= $Credito -> ActualizarBy($columnaPago, $tablasPago, $wherePago);
                    
                    //para realizar cambios de participe en proveedores
                    $_id_tipo_cuenta = "1";
                    if($_sol_tipo_cuenta_banco == "Corriente"){ $_id_tipo_cuenta = 1;}
                    if($_sol_tipo_cuenta_banco == "Ahorros"){ $_id_tipo_cuenta = 2;}
                    
                    $columnaProveedores = "numero_cuenta_proveedores ='$_sol_numero_cuenta', id_tipo_cuentas = '$_id_tipo_cuenta' ";
                    $tablasProveedores = "proveedores";
                    $whereProveedores = "id_proveedores = $_id_proveedor";
                    $UpdateProveedores = $Credito -> ActualizarBy($columnaProveedores, $tablasProveedores, $whereProveedores);
                    
                    
                    $Credito->endTran('COMMIT');
                    return 'OK';
                    
        } catch (Exception $e) {
            
            $Credito->endTran();
            return $e->getMessage();
        }
    }
    
    
    
    public function getUsuario()
    {
        session_start();
        $empleados = new EmpleadosModel();
        $cedula_usuario = $_SESSION["cedula_usuarios"];
        $columna = "empleados.numero_cedula_empleados,
					  empleados.nombres_empleados,
                      cargos_empleados.nombre_cargo,
                      departamentos.nombre_departamento";
        
        $tablas = "public.empleados INNER JOIN public.departamentos
                       ON empleados.id_departamento=departamentos.id_departamento
                       INNER JOIN public.cargos_empleados
                       ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        
        $where = "empleados.numero_cedula_empleados = $cedula_usuario";
        
        $resultSet=$empleados->getCondiciones($columna,$tablas,$where,"empleados.numero_cedula_empleados");
        
        $respuesta = new stdClass();
        
        if(!empty($resultSet)){
            
            $respuesta->numero_cedula_empleados = $resultSet[0]->numero_cedula_empleados;
            $nombres = (string)$resultSet[0]->nombres_empleados;
            $nombresep = explode(" ", $nombres);
            $respuesta->nombre_empleados = $nombresep[0].' '.$nombresep[2];
            $respuesta->cargo_empleados = $resultSet[0]->nombre_cargo;
            $respuesta->dpto_empleados = $resultSet[0]->nombre_departamento;
        }
        
        echo json_encode($respuesta);
        
    }
  
    public function AgregarSolicitud()
    {
        session_start();
        $funcion= "ins_solicitud_empleado";
        $permisos_empleados = new PermisosEmpleadosModel();
        $empleado= new EmpleadosModel();
        $tablas="public.empleados";
        $cedula = $_SESSION['cedula_usuarios'];
        $where = "empleados.numero_cedula_empleados =".$cedula;
        $id = "empleados.id_empleados";
        $result = $empleado->getCondiciones("*", $tablas, $where, $id);
        $id_empleado = $result[0]->id_empleados;
        $fecha_solicitud = $_POST['fecha_solicitud'];
        $hora_desde= $_POST['hora_desde'];
        $hora_hasta= $_POST['hora_hasta'];
        $id_causa= $_POST['id_causa'];
        $descripcion_causa= $_POST['descripcion_causa'];
        if (!(empty($descripcion_causa)))
        {
        $parametros = "'$id_empleado',
                     '$fecha_solicitud',
                     '$hora_desde',
                     '$hora_hasta',
                     '$id_causa',
                     '$descripcion_causa'";
        }
        else
        {
            $parametros = "'$id_empleado',
                     '$fecha_solicitud',
                     '$hora_desde',
                     '$hora_hasta',
                     '$id_causa',
                     NULL";
        }
        $permisos_empleados->setFuncion($funcion);
        $permisos_empleados->setParametros($parametros);
        $resultado=$permisos_empleados->Insert();
        echo 1;
    }
    
    public function GetHoras()
    {
        session_start();
        $horarios = new HorariosEmpleadosModel();
        $cedula_usuario = $_SESSION["cedula_usuarios"];
        $columna = "horarios_empleados.hora_entrada_empleados,
                    horarios_empleados.hora_salida_empleados";
        
        $tablas = "public.horarios_empleados INNER JOIN public.empleados
                       ON empleados.id_grupo_empleados=horarios_empleados.id_grupo_empleados";
                       
        
        $where = "empleados.numero_cedula_empleados = $cedula_usuario";
        
        $resultSet=$horarios->getCondiciones($columna,$tablas,$where,"empleados.numero_cedula_empleados");
                
        echo json_encode($resultSet);
    }
    
    public function consulta_solicitudes(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $cedula =$_SESSION["cedula_usuarios"];
        $id_estado = (isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado'] !=NULL)?$_REQUEST['id_estado']:'';
        $id_jefi=0;
        $id_rh=0;
        
        $permisos_empleados = new PermisosEmpleadosModel();
        $rol = new RolesModel();
        $departamento = new DepartamentoModel();
        
        $tablar = "public.rol";
        $wherer = "rol.nombre_rol='Gerente'";
        $idr = "rol.id_rol";
        $resultr = $rol->getCondiciones("*", $tablar, $wherer, $idr);
        $id_gerente = $resultr[0]->id_rol;
        if ($id_rol != $id_gerente)
        {
        $wherer = "rol.nombre_rol ILIKE '%Jefe de RR.HH'";
        $resultr = $rol->getCondiciones("*", $tablar, $wherer, $idr);
        $id_rh = $resultr[0]->id_rol;
        
        $columnadep = "departamentos.nombre_departamento";
        $tablasdep = "public.departamentos INNER JOIN public.cargos_empleados
                      ON departamentos.id_departamento = cargos_empleados.id_departamento
                      INNER JOIN public.empleados
                      ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        $wheredep= "empleados.numero_cedula_empleados =".$cedula;
        $iddep = "departamentos.id_departamento";
        $resultdep = $departamento->getCondiciones($columnadep, $tablasdep, $wheredep, $iddep);
        
        $tablar = "usuarios INNER JOIN empleados
                        ON usuarios.cedula_usuarios = CAST (empleados.numero_cedula_empleados AS TEXT)
                        INNER JOIN departamentos 
                        ON departamentos.id_departamento = empleados.id_departamento
                        INNER JOIN cargos_empleados
                        ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        $wherer = "departamentos.nombre_departamento='".$resultdep[0]->nombre_departamento."' AND cargos_empleados.nombre_cargo ILIKE 'Jefe%'";
        $idr = "usuarios.id_rol";
        $resultr = $rol->getCondiciones("*", $tablar, $wherer, $idr);
        $id_jefi = $resultr[0]->id_rol;
        $id_dpto_jefe = $resultr[0]->id_departamento;
        }
        $where_to="";
        $columnas = " empleados.nombres_empleados,
                      cargos_empleados.nombre_cargo,
                      departamentos.nombre_departamento,
                        departamentos.id_departamento,
                        permisos_empleados.id_permisos_empleados,
                      permisos_empleados.fecha_solicitud,
                        permisos_empleados.hora_desde,
                        permisos_empleados.hora_hasta,
                        causas_permisos.nombre_causa,
                        permisos_empleados.descripcion_causa,
                        estado.nombre_estado";
        
        $tablas = "public.permisos_empleados INNER JOIN public.empleados
                   ON permisos_empleados.id_empleado = empleados.id_empleados
                   INNER JOIN public.estado
                   ON permisos_empleados.id_estado = estado.id_estado
                   INNER JOIN public.causas_permisos
                   ON permisos_empleados.id_causa = causas_permisos.id_causa
                   INNER JOIN public.departamentos
                   ON departamentos.id_departamento = empleados.id_departamento
                   INNER JOIN public.cargos_empleados
                   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        
        if ($id_estado != "0")
        {   if ($id_rol != $id_gerente && $id_rol != $id_rh && $id_rol != $id_jefi )
            {
                $where    = "permisos_empleados.id_estado=".$id_estado." AND empleados.numero_cedula_empleados=".$cedula;
            }
            else {
                $where    = "permisos_empleados.id_estado=".$id_estado;
            }
            
        }
        else 
        {
            if ($id_rol != $id_gerente && $id_rol != $id_rh && $id_rol != $id_jefi )
            {
                $where    = " empleados.numero_cedula_empleados=".$cedula;
            }
            else {
                $where    = "1=1";
            }
        }
           
        
        $id       = "permisos_empleados.id_permisos_empleados";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND cargos_empleados.nombre_cargo  ILIKE '".$search."%' OR empleados.nombres_empleados ILIKE '".$search."%' OR causas_permisos.nombre_causa ILIKE'".$search."%' OR departamentos.nombre_departamento ILIKE '".$search."%'";

                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$permisos_empleados->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$permisos_empleados->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
                $html.= "<table id='tabla_solicitudes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 15px;"></th>';
                $html.='<th style="text-align: left;  font-size: 15px;"></th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Nombres</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Cargo</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Departamento</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Fecha</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Desde</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Hasta</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Causa</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Descripción</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Estado</th>';
                
                if($id_rol==$id_rh || $id_rol==$id_jefi || $id_rol==$id_gerente)
                {
                    
                    $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                    $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                    
                }
                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    $i++;
                    $html.='<tr>';
                    $html.='<td style="font-size: 14px;">'.$i.'</td>';
                    $html.='<td style="font-size: 18px;"><a href="index.php?controller=PermisosEmpleados&action=HojaPermiso&id_permiso='.$res->id_permisos_empleados.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombres_empleados.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_cargo.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_departamento.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->fecha_solicitud.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->hora_desde.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->hora_hasta.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_causa.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->descripcion_causa.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
                    if($id_rol==$id_rh || $id_rol==$id_jefi || $id_rol==$id_gerente)
                    {
                        if ($id_rol==$id_jefi && $res->nombre_estado=="EN REVISION" && $id_dpto_jefe == $res->id_departamento)
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot; )"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                        }
                        
                         else if ($id_rol==$id_rh && $res->nombre_estado=="VISTO BUENO")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                        }
                        
                        else if ($id_rol==$id_gerente && $res->nombre_estado=="APROBADO")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';                            
                        }
                        else if ($id_rol==$id_rh && $res->nombre_estado=="APROBADO GERENCIA" && $res->nombre_causa=="Enfermedad")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="SinCertificado('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                        }
                    }
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_solicitudes("index.php", $page, $total_pages, $adjacents,"load_solicitudes").'';
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
            die();
            
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
    
    public function VBSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'VISTO BUENO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function AprobarSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'APROBADO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
    }
    public function GerenciaSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'APROBADO GERENCIA'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
     
     echo 1;
    }
    
    public function CertificadoMedico()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'CERTIFICADO PRESENTADO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function SinCertificadoMedico()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'SIN CERTIFICADO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function NegarSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'NEGADO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function HojaPermiso()
    {
        session_start();
        
        $entidades = new EntidadesModel();
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
        
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
        $permisos = new PermisosEmpleadosModel();
        $id_permiso =  (isset($_REQUEST['id_permiso'])&& $_REQUEST['id_permiso'] !=NULL)?$_REQUEST['id_permiso']:'';
        
        $datos_reporte = array();
        
        $columnas = " empleados.nombres_empleados,
                      cargos_empleados.nombre_cargo,
                      departamentos.nombre_departamento,
                      permisos_empleados.fecha_solicitud,
                        permisos_empleados.hora_desde,
                        permisos_empleados.hora_hasta,
                        causas_permisos.nombre_causa,
                        permisos_empleados.descripcion_causa";
        
        $tablas = "public.permisos_empleados INNER JOIN public.empleados
                   ON permisos_empleados.id_empleado = empleados.id_empleados
                   INNER JOIN public.estado
                   ON permisos_empleados.id_estado = estado.id_estado
                   INNER JOIN public.causas_permisos
                   ON permisos_empleados.id_causa = causas_permisos.id_causa
                   INNER JOIN public.departamentos
                   ON departamentos.id_departamento = empleados.id_departamento
                   INNER JOIN public.cargos_empleados
                   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        $where= "permisos_empleados.id_permisos_empleados=".$id_permiso;
        $id="permisos_empleados.id_permisos_empleados";
        
        $rsdatos = $permisos->getCondiciones($columnas, $tablas, $where, $id);
        echo $rsdatos;
        $datos_reporte['NOMBREEMPLEADO']=$rsdatos[0]->nombres_empleados;
        $datos_reporte['CARGOEMPLEADO']=$rsdatos[0]->nombre_cargo;
        $datos_reporte['DPTOEMPLEADO']=$rsdatos[0]->nombre_departamento;
        $fechaelem = explode("-", $rsdatos[0]->fecha_solicitud);
        $ind = intval($fechaelem[1])-1;
        $datos_reporte['FECHASOLICITUD']=$fechaelem[2]." de ".$meses[$ind]." de ".$fechaelem[0];
        $datos_reporte['HORADESDE']=$rsdatos[0]->hora_desde;
        $datos_reporte['HORAHASTA']=$rsdatos[0]->hora_hasta;
        $datos_reporte['CAUSAPERMISO']=$rsdatos[0]->nombre_causa;
        if (!(empty($rsdatos[0]->descripcion_causa)))
        {
            $datos_reporte['DESCRIPCION']="Motivo: ".$rsdatos[0]->descripcion_causa;
        }
        else $datos_reporte['DESCRIPCION']="";
        
        $this->verReporte("HojaPermiso", array('datos_reporte'=>$datos_reporte,'datos_empresa'=>$datos_empresa));
        
        
            
    }
}

?>