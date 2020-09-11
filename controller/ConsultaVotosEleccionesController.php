<?php
class ConsultaVotosEleccionesController extends ControladorBase{
  
    
    
    public function index(){
        session_start();

        $this->view_Elecciones("ConsultaVotosElecciones",array(
            ""=>""
           
        ));
    }
    //MAYCOL
    
    //steven
    public function ConsultaCandidatosFuerzaAerea(){
        
        
        session_start();
        $registro = new RegistroModel();
        
        $where_to="";
        
        $columnas= "a.id_padron_electoral_representantes,
                    b.apellido_participes,
                    b.cedula_participes,
                    b.nombre_participes,
                    c.apellido_participes as apellido_suplente,
                    c.nombre_participes as nombre_suplente,
                    c.cedula_participes as cedula_suplente,
                    c.telefono_participes as telefono_suplente,
                    c.celular_participes as celular_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    b.telefono_participes,
                    b.celular_participes,
                    d.nombre_entidad_mayor_patronal,
                    a.voto_padron_electoral_representantes";
        $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    inner join core_entidad_mayor_patronal d on d.id_entidad_mayor_patronal = b.id_entidad_mayor_patronal
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes, b1.telefono_participes, b1.celular_participes
                	 from padron_electoral_representantes a1
                 	 inner join core_participes b1 on a1.id_suplente = b1.id_participes and b1.id_estatus = 1
                  	)c on a.id_padron_electoral_representantes = c.id_padron_electoral_representantes";
        $where = "1=1 and d.id_entidad_mayor_patronal = 7";
        $id = "a.voto_padron_electoral_representantes";
        
        
        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND b.cedula_participes ILIKE '".$search."%'";
                
                $where_to=$where.$where1;
                
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$registro->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$registro->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            if($cantidadResult > 0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:400px; overflow-y:scroll;">';
                $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;   font-size: 13px;">Foto</th>';
                $html.='<th style="text-align: left; width:450px; font-size: 13px;">Datos Representante</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Votos</th>';
                $html.='</tr>';
                
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    
                    
                    
                    $i++;
                    $html.='<tr>';
                    $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="120" height="100"></td>';
                    $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_participes.'</br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'</br><b>CORREO: </b>'.$res->correo_representante.'</br><b>TELÉFONO: </b>'.$res->telefono_participes.'</br><b>CELULAR: </b>'.$res->celular_participes.'</td>';
                    $html.="<td style='font-size: 12px;'><b>CANTIDAD VOTOS: </b>";
                    $html.='<div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" aria-valuenow="'.$res->voto_padron_electoral_representantes.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$res->voto_padron_electoral_representantes.'%">'.$res->voto_padron_electoral_representantes.'</div>
                            </div>';
                    $html.='</td>';
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_ConsultaCandidatosFuerzaAerea("index.php", $page, $total_pages, $adjacents,"ConsultaCandidatosFuerzaAerea").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay  Registros...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            
        }
        
    }
    
    
    
    
    
    
    public function paginate_ConsultaCandidatosFuerzaAerea($reload, $page, $tpages, $adjacents, $funcion = "") {
        //Steven
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
    
    public function ConsultaCandidatosComandoConjunto(){
        
        
        session_start();
        $registro = new RegistroModel();
        
        $where_to="";
        
        $columnas= "a.id_padron_electoral_representantes,
                    b.apellido_participes,
                    b.cedula_participes,
                    b.nombre_participes,
                    c.apellido_participes as apellido_suplente,
                    c.nombre_participes as nombre_suplente,
                    c.cedula_participes as cedula_suplente,
                    c.telefono_participes as telefono_suplente,
                    c.celular_participes as celular_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    b.telefono_participes,
                    b.celular_participes,
                    d.nombre_entidad_mayor_patronal,
                    a.voto_padron_electoral_representantes";
        $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    inner join core_entidad_mayor_patronal d on d.id_entidad_mayor_patronal = b.id_entidad_mayor_patronal 
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes, b1.telefono_participes, b1.celular_participes
                	 from padron_electoral_representantes a1
                 	 inner join core_participes b1 on a1.id_suplente = b1.id_participes and b1.id_estatus = 1
                  	)c on a.id_padron_electoral_representantes = c.id_padron_electoral_representantes";
        $where = "1=1 and d.id_entidad_mayor_patronal = 8";
        $id = "a.voto_padron_electoral_representantes";
        
        
        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND b.cedula_participes ILIKE '".$search."%'";
                
                $where_to=$where.$where1;
                
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$registro->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$registro->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            if($cantidadResult > 0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:400px; overflow-y:scroll;">';
                $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
               
                $html.='<th style="text-align: left;  font-size: 13px;">Foto</th>';
                $html.='<th style="text-align: left; width:450px; font-size: 13px;">Datos Representante</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Votos</th>';
                $html.='</tr>';
                
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    
                    
                    
                    $i++;
                    $html.='<tr>';
                    
                    $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="120" height="100"></td>';
                    $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_participes.'</br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'</br><b>CORREO: </b>'.$res->correo_representante.'</br><b>TELÉFONO: </b>'.$res->telefono_participes.'</br><b>CELULAR: </b>'.$res->celular_participes.'</td>';
                    $html.="<td style='font-size: 12px;'><b>CANTIDAD VOTOS: </b>";
                    $html.='<div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" aria-valuenow="'.$res->voto_padron_electoral_representantes.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$res->voto_padron_electoral_representantes.'%">'.$res->voto_padron_electoral_representantes.'</div>
                            </div>';
                    $html.='</td>';
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_ConsultaCandidatosComandoConjunto("index.php", $page, $total_pages, $adjacents,"ConsultaCandidatosComandoConjunto").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay  Registros...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            
        }
        
    }
    
    
    
    
    
    
    public function paginate_ConsultaCandidatosComandoConjunto($reload, $page, $tpages, $adjacents, $funcion = "") {
        //Steven
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
    
    public function ConsultaCandidatosMinisterioDefensa(){
        
        
        session_start();
        $registro = new RegistroModel();
        
        $where_to="";
        
        $columnas= "a.id_padron_electoral_representantes,
                    b.apellido_participes,
                    b.cedula_participes,
                    b.nombre_participes,
                    c.apellido_participes as apellido_suplente,
                    c.nombre_participes as nombre_suplente,
                    c.cedula_participes as cedula_suplente,
                    c.telefono_participes as telefono_suplente,
                    c.celular_participes as celular_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    b.telefono_participes,
                    b.celular_participes,
                    d.nombre_entidad_mayor_patronal,
                    a.voto_padron_electoral_representantes";
        $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    inner join core_entidad_mayor_patronal d on d.id_entidad_mayor_patronal = b.id_entidad_mayor_patronal
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes, b1.telefono_participes, b1.celular_participes
                	 from padron_electoral_representantes a1
                 	 inner join core_participes b1 on a1.id_suplente = b1.id_participes and b1.id_estatus = 1
                  	)c on a.id_padron_electoral_representantes = c.id_padron_electoral_representantes";
        $where = "1=1 and d.id_entidad_mayor_patronal = 9";
        $id = "a.voto_padron_electoral_representantes";
        
        
        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND b.cedula_participes ILIKE '".$search."%'";
                
                $where_to=$where.$where1;
                
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$registro->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$registro->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            if($cantidadResult > 0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:400px; overflow-y:scroll;">';
                $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                
                $html.='<th style="text-align: left;  font-size: 13px;">Foto</th>';
                $html.='<th style="text-align: left; width:450px; font-size: 13px;">Datos Representante</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Votos</th>';
                $html.='</tr>';
                
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    
                    
                    
                    $i++;
                    $html.='<tr>';
                    
                    $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="120" height="100"></td>';
                    $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_participes.'</br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'</br><b>CORREO: </b>'.$res->correo_representante.'</br><b>TELÉFONO: </b>'.$res->telefono_participes.'</br><b>CELULAR: </b>'.$res->celular_participes.'</td>';
                    $html.="<td style='font-size: 12px;'><b>CANTIDAD VOTOS: </b>";
                    $html.='<div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" aria-valuenow="'.$res->voto_padron_electoral_representantes.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$res->voto_padron_electoral_representantes.'%">'.$res->voto_padron_electoral_representantes.'</div>
                            </div>';
                    $html.='</td>';
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_ConsultaCandidatosMinisterioDefensa("index.php", $page, $total_pages, $adjacents,"ConsultaCandidatosMinisterioDefensa").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay  Registros...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            
        }
        
    }
    
    
    
    
    
    
    public function paginate_ConsultaCandidatosMinisterioDefensa($reload, $page, $tpages, $adjacents, $funcion = "") {
        //Steven
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
    
    public function ConsultaCandidatosFuerzaNaval(){
        
        
        session_start();
        $registro = new RegistroModel();
        
        $where_to="";
        
        $columnas= "a.id_padron_electoral_representantes,
                    b.apellido_participes,
                    b.cedula_participes,
                    b.nombre_participes,
                    c.apellido_participes as apellido_suplente,
                    c.nombre_participes as nombre_suplente,
                    c.cedula_participes as cedula_suplente,
                    c.telefono_participes as telefono_suplente,
                    c.celular_participes as celular_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    b.telefono_participes,
                    b.celular_participes,
                    d.nombre_entidad_mayor_patronal,
                    a.voto_padron_electoral_representantes";
        $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    inner join core_entidad_mayor_patronal d on d.id_entidad_mayor_patronal = b.id_entidad_mayor_patronal
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes, b1.telefono_participes, b1.celular_participes
                	 from padron_electoral_representantes a1
                 	 inner join core_participes b1 on a1.id_suplente = b1.id_participes and b1.id_estatus = 1
                  	)c on a.id_padron_electoral_representantes = c.id_padron_electoral_representantes";
        $where = "1=1 and d.id_entidad_mayor_patronal = 10";
        $id = "a.voto_padron_electoral_representantes";
        
        
        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND b.cedula_participes ILIKE '".$search."%'";
                
                $where_to=$where.$where1;
                
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$registro->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$registro->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            if($cantidadResult > 0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:400px; overflow-y:scroll;">';
                $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                
                $html.='<th style="text-align: left;  font-size: 13px;">Foto</th>';
                $html.='<th style="text-align: left; width:450px; font-size: 13px;">Datos Representante</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Votos</th>';
                $html.='</tr>';
                
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    
                    
                    
                    $i++;
                    $html.='<tr>';
                    $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="120" height="100"></td>';
                    $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_participes.'</br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'</br><b>CORREO: </b>'.$res->correo_representante.'</br><b>TELÉFONO: </b>'.$res->telefono_participes.'</br><b>CELULAR: </b>'.$res->celular_participes.'</td>';
                    $html.="<td style='font-size: 12px;'><b>CANTIDAD VOTOS: </b>";
                    $html.='<div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" aria-valuenow="'.$res->voto_padron_electoral_representantes.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$res->voto_padron_electoral_representantes.'%">'.$res->voto_padron_electoral_representantes.'</div>
                            </div>';
                    $html.='</td>';
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_ConsultaCandidatosFuerzaNaval("index.php", $page, $total_pages, $adjacents,"ConsultaCandidatosFuerzaNaval").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay  Registros...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            
        }
        
    }
    
    
    
    
    
    
    public function paginate_ConsultaCandidatosFuerzaNaval($reload, $page, $tpages, $adjacents, $funcion = "") {
        //Steven
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
   
    public function ConsultaCandidatosFuerzaTerrestre(){
        
        
        session_start();
        $registro = new RegistroModel();
        
        $where_to="";
        
        $columnas= "a.id_padron_electoral_representantes,
                    b.apellido_participes,
                    b.cedula_participes,
                    b.nombre_participes,
                    c.apellido_participes as apellido_suplente,
                    c.nombre_participes as nombre_suplente,
                    c.cedula_participes as cedula_suplente,
                    c.telefono_participes as telefono_suplente,
                    c.celular_participes as celular_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    b.telefono_participes,
                    b.celular_participes,
                    d.nombre_entidad_mayor_patronal,
                    a.voto_padron_electoral_representantes";
        $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    inner join core_entidad_mayor_patronal d on d.id_entidad_mayor_patronal = b.id_entidad_mayor_patronal
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes, b1.telefono_participes, b1.celular_participes
                	 from padron_electoral_representantes a1
                 	 inner join core_participes b1 on a1.id_suplente = b1.id_participes and b1.id_estatus = 1
                  	)c on a.id_padron_electoral_representantes = c.id_padron_electoral_representantes";
        $where = "1=1 and d.id_entidad_mayor_patronal = 11";
        $id = "a.voto_padron_electoral_representantes";
        
        
        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND b.cedula_participes ILIKE '".$search."%'";
                
                $where_to=$where.$where1;
                
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$registro->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$registro->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            if($cantidadResult > 0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:400px; overflow-y:scroll;">';
                $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                
                $html.='<th style="text-align: left;  font-size: 13px;">Foto</th>';
                $html.='<th style="text-align: left; width:450px; font-size: 13px;">Datos Representante</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Votos</th>';
                $html.='</tr>';
                
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    
                    
                    
                    $i++;
                    $html.='<tr>';
               
                    $html.='<td style="font-size: 11px; width:15px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="120" height="100"></td>';
                    $html.='<td style="font-size: 12px;"><b>CÉDULA: </b>'.$res->cedula_participes.'</br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'</br><b>CORREO: </b>'.$res->correo_representante.'</br><b>TELÉFONO: </b>'.$res->telefono_participes.'</br><b>CELULAR: </b>'.$res->celular_participes.'</td>';
                    $html.="<td style='font-size: 12px;'><b>CANTIDAD VOTOS: </b>";
                    $html.='<div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" aria-valuenow="'.$res->voto_padron_electoral_representantes.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$res->voto_padron_electoral_representantes.'%">'.$res->voto_padron_electoral_representantes.'</div>
                            </div>';
                    $html.='</td>';
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_ConsultaCandidatosFuerzaTerrestre("index.php", $page, $total_pages, $adjacents,"ConsultaCandidatosFuerzaTerrestre").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay  Registros...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            
        }
        
    }
    
    
    
    
    
    
    public function paginate_ConsultaCandidatosFuerzaTerrestre($reload, $page, $tpages, $adjacents, $funcion = "") {
        //Steven
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
    
    public function index2(){
        session_start();
        
        $this->view_Elecciones("ConsultaFaltantesVotar",array(
            ""=>""
            
            
        ));
    }
    
    
    public function ConsultaVotosRealizados(){
        
        
        session_start();
        $registro = new RegistroModel();
        $id_rol= $_SESSION['id_rol'];
        
        
        $where_to="";
        
        $columnas= "g.id_entidad_mayor_patronal, d.nombre_entidad_mayor_patronal, d.total_votantes, coalesce(f.total_votos_realizados,0) as total_votos_realizados, (d.total_votantes-coalesce(f.total_votos_realizados,0)) as total_votos_faltantes, ((cast(coalesce(f.total_votos_realizados,0) as decimal) / cast(d.total_votantes as decimal) )*100) as total_procentaje";
        $tablas =  "( 
                	select c.nombre_entidad_mayor_patronal, count(a.id_participes) as total_votantes from padron_electroal a
                	inner join core_participes b on a.id_participes=b.id_participes
                	inner join core_entidad_mayor_patronal c on b.id_entidad_mayor_patronal=c.id_entidad_mayor_patronal
                	where a.tipo_padron_electoral=1
                	group by c.nombre_entidad_mayor_patronal
                )d 
                left join (
                	select cc.nombre_entidad_mayor_patronal, count(aa.id_participe_vota) as total_votos_realizados from padron_electoral_traza_votos aa
                		inner join core_participes bb on aa.id_participe_vota=bb.id_participes
                		inner join core_entidad_mayor_patronal cc on bb.id_entidad_mayor_patronal=cc.id_entidad_mayor_patronal
                		where 1=1
                		group by cc.nombre_entidad_mayor_patronal
                )f on d.nombre_entidad_mayor_patronal=f.nombre_entidad_mayor_patronal
                left join (
                	select aaa.id_entidad_mayor_patronal, aaa.nombre_entidad_mayor_patronal from core_entidad_mayor_patronal aaa where 1=1
                )g on d.nombre_entidad_mayor_patronal=g.nombre_entidad_mayor_patronal";
        $where = "1=1";
        $id = "total_votos_realizados";
        
        
        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND d.nombre_entidad_mayor_patronal ILIKE '".$search."%'";
                
                $where_to=$where.$where1;
                
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$registro->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$registro->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            if($cantidadResult > 0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:400px; overflow-y:scroll;">';
                $html.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                if($id_rol == "1" || $id_rol == "65"){
                    $html.='<th style="text-align: left;  font-size: 13px;">Acciones</th>';
                }
                $html.='<th style="text-align: left;  font-size: 13px;">Nombre Entidad Patronal</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Total Votantes</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Votos Faltantes</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Cantidad Votos</th>';
                $html.='<th style="text-align: left;  font-size: 13px;">Porcentaje</th>';
                
                $html.='</tr>';
                
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    
                    
                    
                    $i++;
                    $html.='<tr>';
                    if($id_rol == "1" || $id_rol == "65"){
                        $html.='<td><a title="Ver Detalle" href="index.php?controller=ConsultaVotosElecciones&action=ReporteDetalleVotos&id_entidad_mayor_patronal='.$res->id_entidad_mayor_patronal.'" role="button" target="_blank"><img src="view/images/logo_pdf.png" width="30" height="30"></a></font></td>';
                    }
                    $html.='<td style="font-size: 12px;">'.$res->nombre_entidad_mayor_patronal.'</td>';
                    $html.='<td style="font-size: 12px;">'.$res->total_votantes.'</td>';
                    $html.='<td style="font-size: 12px;">'.$res->total_votos_faltantes.'</td>';
                    $html.='<td style="font-size: 12px;">'.$res->total_votos_realizados.'</td>';
                    $html.="<td>";
                    $html.='<div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" aria-valuenow="'.$res->total_procentaje.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$res->total_procentaje.'%">'.$res->total_votos_realizados.'</div>
                            </div>';
                    $html.='</td>';
                    $html.='</tr>';
                    
                    
                    
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_ConsultaVotosRealizados("index.php", $page, $total_pages, $adjacents,"ConsultaVotosRealizados").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay  Registros...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            
        }
        
    }
    
    
    
    
    
    
    public function paginate_ConsultaVotosRealizados($reload, $page, $tpages, $adjacents, $funcion = "") {
        //Steven
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
    
    public function ReporteConsultaVotosRealizados(){
        
        session_start();
        
        $participes = new ParticipesModel();
        
        $datos_reporte = array();
        $columnas= "g.id_entidad_mayor_patronal, d.nombre_entidad_mayor_patronal, d.total_votantes, coalesce(f.total_votos_realizados,0) as total_votos_realizados, (d.total_votantes-coalesce(f.total_votos_realizados,0)) as total_votos_faltantes, ((cast(coalesce(f.total_votos_realizados,0) as decimal) / cast(d.total_votantes as decimal) )*100) as total_procentaje";
        $tablas =  "(
                	select c.nombre_entidad_mayor_patronal, count(a.id_participes) as total_votantes from padron_electroal a
                	inner join core_participes b on a.id_participes=b.id_participes
                	inner join core_entidad_mayor_patronal c on b.id_entidad_mayor_patronal=c.id_entidad_mayor_patronal
                	where a.tipo_padron_electoral=1
                	group by c.nombre_entidad_mayor_patronal
                )d
                left join (
                	select cc.nombre_entidad_mayor_patronal, count(aa.id_participe_vota) as total_votos_realizados from padron_electoral_traza_votos aa
                		inner join core_participes bb on aa.id_participe_vota=bb.id_participes
                		inner join core_entidad_mayor_patronal cc on bb.id_entidad_mayor_patronal=cc.id_entidad_mayor_patronal
                		where 1=1
                		group by cc.nombre_entidad_mayor_patronal
                )f on d.nombre_entidad_mayor_patronal=f.nombre_entidad_mayor_patronal
                left join (
                	select aaa.id_entidad_mayor_patronal, aaa.nombre_entidad_mayor_patronal from core_entidad_mayor_patronal aaa where 1=1
                )g on d.nombre_entidad_mayor_patronal=g.nombre_entidad_mayor_patronal";
        $where = "1=1";
        $id = "total_votos_realizados";
        $rsdatos = $participes->getCondicionesDesc($columnas, $tablas, $where, $id);
        
        
        
        $html="";
        $total_total_votantes = 0;
        $total_total_votos_faltantes = 0;
        $total_total_votos_realizados = 0;
        
        
        if(!empty($rsdatos)){
            
            $html.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
            $html.= '<tr>';
            $html.='<th style="text-align: left;  font-size: 13px;">Nombre Entidad Patronal</th>';
            $html.='<th style="text-align: left;  font-size: 13px;">Total Votantes</th>';
            $html.='<th style="text-align: left;  font-size: 13px;">Votos Faltantes</th>';
            $html.='<th style="text-align: left;  font-size: 13px;">Votos Recibidos</th>';
            $html.= '</tr>';
            
            
            
            foreach ($rsdatos as $res) {
                $total_total_votantes = $total_total_votantes+$res->total_votantes;
                $total_total_votos_faltantes = $total_total_votos_faltantes+$res->total_votos_faltantes;
                $total_total_votos_realizados = $total_total_votos_realizados+$res->total_votos_realizados;
                
            $html.= '<tr>';
            $html.='<td style="font-size: 12px;" align="left">'.$res->nombre_entidad_mayor_patronal.'</td>';
            $html.='<td style="font-size: 12px;" align="right">'.$res->total_votantes.'</td>';
            $html.='<td style="font-size: 12px;" align="right">'.$res->total_votos_faltantes.'</td>';
            $html.='<td style="font-size: 12px;" align="right">'.$res->total_votos_realizados.'</td>';
            $html.= '</tr>';
            
                
            }
            $html.= '<tr>';
            $html.='<td style="font-size: 12px;" align="right"><strong>TOTAL</strong></td>';
            $html.='<td style="font-size: 12px;" align="right"><strong>'.$total_total_votantes.'</strong></td>';
            $html.='<td style="font-size: 12px;" align="right"><strong>'.$total_total_votos_faltantes.'</strong></td>';
            $html.='<td style="font-size: 12px;" align="right"><strong>'.$total_total_votos_realizados.'</strong></td>';
            $html.= '</tr>';
            
            $html.= '</table>';
            
         }
         $datos_reporte['FECHAIMPRESION']=date('Y-m-d H:i');
         $datos_reporte['TABLA_VALORES']=$html;
  
         $this->verReporte("ReporteConsultaVotosRealizados", array('datos_reporte'=>$datos_reporte ));
         
            
            
            
    }
    
    public function ReporteDetalleVotos(){
        
        session_start();
        $id_entidad_mayor_patronal = (isset($_REQUEST['id_entidad_mayor_patronal'])&& $_REQUEST['id_entidad_mayor_patronal'] !=NULL)?$_REQUEST['id_entidad_mayor_patronal']:'0';
        $participes = new ParticipesModel();
        
        if($id_entidad_mayor_patronal>0){
       
            
              
        $datos_reporte = array();
        $columnas= "a.cedula_participes, a.nombre_participes, a.apellido_participes, a.celular_participes, a.correo_participes, b.nombre_entidad_mayor_patronal, c.id_padron_electoral_traza_votos, to_char(c.creado, 'YYYY-MM-DD HH:MM') as creado";
        $tablas =  "core_participes a
            inner join core_entidad_mayor_patronal b on a.id_entidad_mayor_patronal=b.id_entidad_mayor_patronal
            inner join padron_electoral_traza_votos c on a.id_participes=c.id_participe_vota";
        $where = "a.id_estatus=1 and b.id_entidad_mayor_patronal = '$id_entidad_mayor_patronal'";
        $id = "a.apellido_participes";
        $rsdatos = $participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $columnas1= "nombre_entidad_mayor_patronal";
        $tablas1 =  "core_entidad_mayor_patronal";
        $where1 = "id_entidad_mayor_patronal = '$id_entidad_mayor_patronal'";
        $id1 = "nombre_entidad_mayor_patronal";
        $rsdatos1 = $participes->getCondiciones($columnas1, $tablas1, $where1, $id1);
        
        $nombre_entidad = "";
        
        if(!empty($rsdatos1)){
            
            $nombre_entidad=$rsdatos1[0]->nombre_entidad_mayor_patronal;
        
        }
        $html="";
         
        
        if(!empty($rsdatos)){
            
            
            $html.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
            $html.= '<tr>';
            $html.='<th style="text-align: left;  font-size: 13px;">#</th>';
            $html.='<th style="text-align: left;  font-size: 13px;">Cedula</th>';
            $html.='<th style="text-align: left;  font-size: 13px;">Datos Participe</th>';
            $html.='<th style="text-align: left;  font-size: 13px;">Celular</th>';
            $html.='<th style="text-align: left;  font-size: 13px;">Correo</th>';
            $html.='<th style="text-align: left;  font-size: 13px;">Fecha Sufragio</th>';
            
            $html.= '</tr>';
            
            $i=0;
            
            foreach ($rsdatos as $res) {
                 $i++;
                $html.= '<tr>';
                $html.='<td style="font-size: 12px;" align="left">'.$i.'</td>';
                $html.='<td style="font-size: 12px;" align="left">'.$res->cedula_participes.'</td>';
                $html.='<td style="font-size: 12px;" align="left">'.$res->apellido_participes.' '.$res->nombre_participes.'</td>';
                $html.='<td style="font-size: 12px;" align="left">'.$res->celular_participes.'</td>';
                $html.='<td style="font-size: 12px;" align="left">'.$res->correo_participes.'</td>';
                $html.='<td style="font-size: 12px;" align="left">'.$res->creado.'</td>';
                $html.= '</tr>';
                
                
            }
               
            $html.= '</table>';
            
        }
        $datos_reporte['FECHAIMPRESION']=date('Y-m-d H:i');
        $datos_reporte['TABLA_VALORES']=$html;
        $datos_reporte['ENTIDAD']=$nombre_entidad;
        
        
        $this->verReporte("ReporteDetalleVotos", array('datos_reporte'=>$datos_reporte ));
        
    }

    }

    public function ReporteConsultaVotosFuerza(){
        
        session_start();
        $id_entidad_mayor_patronal = (isset($_REQUEST['id_entidad_mayor_patronal'])&& $_REQUEST['id_entidad_mayor_patronal'] !=NULL)?$_REQUEST['id_entidad_mayor_patronal']:'0';
        $participes = new ParticipesModel();
        
        if($id_entidad_mayor_patronal>0){
            
            
            
            $datos_reporte = array();
            $columnas= "a.id_padron_electoral_representantes,
                    b.apellido_participes,
                    b.cedula_participes,
                    b.nombre_participes,
                    c.apellido_participes as apellido_suplente,
                    c.nombre_participes as nombre_suplente,
                    c.cedula_participes as cedula_suplente,
                    c.telefono_participes as telefono_suplente,
                    c.celular_participes as celular_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    b.telefono_participes,
                    b.celular_participes,
                    d.nombre_entidad_mayor_patronal,
                    a.voto_padron_electoral_representantes";
            $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    inner join core_entidad_mayor_patronal d on d.id_entidad_mayor_patronal = b.id_entidad_mayor_patronal
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes, b1.telefono_participes, b1.celular_participes
                	 from padron_electoral_representantes a1
                 	 inner join core_participes b1 on a1.id_suplente = b1.id_participes and b1.id_estatus = 1
                  	)c on a.id_padron_electoral_representantes = c.id_padron_electoral_representantes";
            $where = "1=1 and d.id_entidad_mayor_patronal = '$id_entidad_mayor_patronal'";
            $id = "a.voto_padron_electoral_representantes";
            $rsdatos = $participes->getCondicionesDesc($columnas, $tablas, $where, $id);
            
            $columnas1= "nombre_entidad_mayor_patronal";
            $tablas1 =  "core_entidad_mayor_patronal";
            $where1 = "id_entidad_mayor_patronal = '$id_entidad_mayor_patronal'";
            $id1 = "nombre_entidad_mayor_patronal";
            $rsdatos1 = $participes->getCondiciones($columnas1, $tablas1, $where1, $id1);
            
            $nombre_entidad = "";
            
            if(!empty($rsdatos1)){
                
                $nombre_entidad=$rsdatos1[0]->nombre_entidad_mayor_patronal;
                
            }
            $html="";
           
            if(!empty($rsdatos)){
            
                $html.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
                $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;   font-size: 13px;">#</th>';
            $html.='<th style="text-align: left;   font-size: 13px;">Foto</th>';
            $html.='<th style="text-align: left; width:450px; font-size: 13px;">Datos Representante</th>';
            $html.='<th style="text-align: left;  font-size: 13px;">Votos Recibidos</th>';
            $html.='</tr>';
            
            $html.='</thead>';
            $html.='<tbody>';
            
            
            $i=0;
            
            foreach ($rsdatos as $res)
            {
                
                
                
                $i++;
                $html.='<tr>';
                $html.='<td style="font-size: 12px;" align="left">'.$i.'</td>';
                $html.='<td style="font-size: 11px; width:15px;" width="150"><center><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="120" height="100"></center></td>';
                $html.='<td><div class = "3"><b>CÉDULA: </b>'.$res->cedula_participes.'<br><b>NOMBRE: </b>'.$res->apellido_participes.' '.$res->nombre_participes.'<br><b>CORREO: </b>'.$res->correo_representante.'<br><b>TELÉFONO: </b>'.$res->telefono_participes.'<br><b>CELULAR: </b>'.$res->celular_participes.'</div></td>';
                $html.='<td class = "2">'.$res->voto_padron_electoral_representantes.'</td>';
                $html.='</tr>';
            }
            
            
            
            $html.='</tbody>';
            $html.='</table>';
           
            }
            
            
            
            $datos_reporte['FECHAIMPRESION']=date('Y-m-d H:i');
            $datos_reporte['TABLA_VALORES']=$html;
            $datos_reporte['ENTIDAD']=$nombre_entidad;
            
            
            $this->verReporte("ReporteConsultaVotosFuerza", array('datos_reporte'=>$datos_reporte ));
            
        }
        
    }
    
    
}

?>