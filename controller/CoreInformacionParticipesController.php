<?php
class CoreInformacionParticipesController extends ControladorBase{
    public function index(){
        
        $participes = new CoreInformacionParticipesModel();
        $mensaje="";
        $error="";
        session_start();
        
        if(empty( $_SESSION)){
            
            $this->redirect("Usuarios","sesion_caducada");
            return;
        }
        
        $nombre_controladores = "CoreInformacionParticipes";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $participes->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (empty($resultPer)){
            
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos de Acceso Información Participes"
                
            ));
            exit();
        }
        
        
        
        $this->view_Core("CoreInformacionParticipes",array(
            "mensaje"=>$mensaje,
            "error"=> $error
            
        ));
        
        
    }
    public function index_consulta_general(){
        
        session_start();
        
        $participes = new CoreInformacionParticipesModel();
        
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "CoreInformacionParticipes";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $participes->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                if (isset ($_GET["id_participes"])   )
                {
                    $_id_participes = $_GET["id_participes"];
                   
                    $columnas = "
                              core_participes_informacion_adicional.id_participes, 
                              core_participes.apellido_participes, 
                              core_participes.nombre_participes, 
                              core_participes.cedula_participes, 
                              core_participes.direccion_participes,
                              core_participes.telefono_participes, 
                              core_participes.celular_participes, 
                              core_participes.fecha_ingreso_participes,
                              core_participes.ocupacion_participes,
                              core_participes.fecha_nacimiento_participes,
                              core_estado_civill_participes.nombre_estado_civill_participes,
                              core_participes.fecha_defuncion_participes, 
                              core_participes.correo_participes, 
                              core_entidad_patronal.nombre_entidad_patronal, 
                              core_participes.nombre_conyugue_participes, 
                              core_participes.apellido_esposa_participes, 
                              core_participes.cedula_conyugue_participes, 
                              core_participes.numero_dependencias_participes, 
                              core_distritos.id_distritos, 
                              core_distritos.nombre_distritos, 
                              core_provincias.id_provincias, 
                              core_provincias.nombre_provincias, 
                              core_ciudades.id_ciudades, 
                              core_ciudades.nombre_ciudades, 
                              core_participes_informacion_adicional.parroquia_participes_informacion_adicional, 
                              core_participes_informacion_adicional.anios_residencia_participes_informacion_adicional, 
                              core_participes_informacion_adicional.nombre_propietario_participes_informacion_adicional, 
                              core_participes_informacion_adicional.telefono_propietario_participes_informacion_adicional, 
                              core_participes_informacion_adicional.direccion_referencia_participes_informacion_adicional, 
                              core_participes_informacion_adicional.vivienda_hipotecada_participes_informacion_adicional, 
                              core_participes_informacion_adicional.nombre_una_referencia_participes_informacion_adicional, 
                              core_parentesco.id_parentesco, 
                              core_parentesco.nombre_parentesco, 
                              core_participes_informacion_adicional.telefono_una_referencia_participes_informacion_adicional, 
                              core_participes_informacion_adicional.contrato_adhesion_participes_informacion_adicional,
                              core_estado_participes.nombre_estado_participes
                                                    
                                    ";
                    
                    $tablas   = "
                              public.core_participes, 
                              public.core_participes_informacion_adicional, 
                              public.core_distritos, 
                              public.core_provincias, 
                              public.core_ciudades, 
                              public.core_entidad_patronal, 
                              public.core_parentesco,
                              public.core_estado_civill_participes,
                              public.core_estado_participes

                              ";
                    $where    = " core_participes_informacion_adicional.id_participes = core_participes.id_participes AND
                                  core_distritos.id_distritos = core_participes_informacion_adicional.id_distritos AND
                                  core_provincias.id_provincias = core_participes_informacion_adicional.id_provincias AND
                                  core_ciudades.id_ciudades = core_participes_informacion_adicional.id_ciudades AND
                                  core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND
                                  core_parentesco.id_parentesco = core_participes_informacion_adicional.id_parentesco
                                  AND core_estado_civill_participes.id_estado_civill_participes = core_participes.id_estado_civil_participes 
                                  AND core_estado_participes.id_estado_participes = core_participes.id_estado_participes
                                  AND core_participes.id_participes = '$_id_participes'";
                    $id       = "core_participes.id_participes";
                    
                    $resultRep = $participes->getCondiciones($columnas ,$tablas ,$where, $id);
                    
                    if(!empty($resultRep)){
                        
                       

                    }
                 
                    
                    $this->view_Core("CoreConsultaGeneral",array(
                        "resultRep"=>$resultRep
                        
                        
                    ));
                }
                
                
                
            }
        }
        else
        {
            $this->view_Contable("Error",array(
                "resultado"=>"No tiene Permisos de Acceso a Consulta General"
                
            ));
            
            exit();
        }
        
        
    }
    

    
    public function consulta_informacion_participes(){
        
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $usuarios= new UsuariosModel();
        $participes = new CoreInformacionParticipesModel();
        
        $where_to="";
        $columnas = "
                      core_participes_informacion_adicional.id_participes, 
                      core_participes.apellido_participes, 
                      core_participes.nombre_participes, 
                      core_participes.cedula_participes, 
                      core_participes.direccion_participes, 
                      core_participes.correo_participes, 
                      core_entidad_patronal.nombre_entidad_patronal, 
                      core_participes.nombre_conyugue_participes, 
                      core_participes.apellido_esposa_participes, 
                      core_participes.cedula_conyugue_participes, 
                      core_participes.numero_dependencias_participes, 
                      core_distritos.id_distritos, 
                      core_distritos.nombre_distritos, 
                      core_provincias.id_provincias, 
                      core_provincias.nombre_provincias, 
                      core_ciudades.id_ciudades, 
                      core_ciudades.nombre_ciudades, 
                      core_participes_informacion_adicional.parroquia_participes_informacion_adicional, 
                      core_participes_informacion_adicional.anios_residencia_participes_informacion_adicional, 
                      core_participes_informacion_adicional.nombre_propietario_participes_informacion_adicional, 
                      core_participes_informacion_adicional.telefono_propietario_participes_informacion_adicional, 
                      core_participes_informacion_adicional.direccion_referencia_participes_informacion_adicional, 
                      core_participes_informacion_adicional.vivienda_hipotecada_participes_informacion_adicional, 
                      core_participes_informacion_adicional.nombre_una_referencia_participes_informacion_adicional, 
                      core_parentesco.id_parentesco, 
                      core_parentesco.nombre_parentesco, 
                      core_participes_informacion_adicional.telefono_una_referencia_participes_informacion_adicional, 
                      core_participes_informacion_adicional.contrato_adhesion_participes_informacion_adicional
            
                      ";
        $tablas   = "
                      public.core_participes, 
                      public.core_participes_informacion_adicional, 
                      public.core_distritos, 
                      public.core_provincias, 
                      public.core_ciudades, 
                      public.core_entidad_patronal, 
                      public.core_parentesco

                    ";
        $where    = " core_participes_informacion_adicional.id_participes = core_participes.id_participes AND
                      core_distritos.id_distritos = core_participes_informacion_adicional.id_distritos AND
                      core_provincias.id_provincias = core_participes_informacion_adicional.id_provincias AND
                      core_ciudades.id_ciudades = core_participes_informacion_adicional.id_ciudades AND
                      core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND
                      core_parentesco.id_parentesco = core_participes_informacion_adicional.id_parentesco";
        
        $id       = "core_participes.id_participes";
        
        
       
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            if(!empty($search)){
                
                
                $where1=" AND (nombre_participes LIKE '%".$search."%' OR cedula_participes LIKE '%".$search."%' OR apellido_participes LIKE '%".$search."%')
                ";
                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$usuarios->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$usuarios->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
            $count_query   = $cantidadResult;
            $total_pages = ceil($cantidadResult/$per_page);
            
            
            
            
            
            if($cantidadResult>0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:425px; overflow-y:scroll;">';
                $html.= "<table id='tabla_informacionparticipes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: center; font-size: 12px;"></th>';
                $html.='<th style="text-align: center;  font-size: 12px;">Nombre</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Apellido</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Cédula</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Dirección</th>';
                $html.='<th style="text-align: center;  font-size: 12px;">Correo</th>';
                //$html.='<th style="text-align: center; font-size: 12px;">Entidad Patronal</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Nombre Conyuge</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Apellido Conyuge</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Cédula Conyuge</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Dependencias</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Distrito</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Provincia</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Ciudad</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Parroquia</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Años Residencia</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Propietario</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Télefono Propietario</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Nombre Referencia</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Parentesco</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Teléfono</th>';
                
                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                $importe=0;
                $coreo_envidado="";
                foreach ($resultSet as $res)
                {
                    
                    
                    $i++;
                    $html.='<tr>';
                    $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right"><a href="index.php?controller=CoreInformacionParticipes&action=index_consulta_general&id_participes='.$res->id_participes.'" title="Consultar Participe"><img style=" height:30px; width:50px;" src="view/images/Search.png"></img></a></span></td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$i.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->direccion_participes.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->correo_participes.'</td>';
                    //$html.='<td style="font-size: 11px;">'.$res->nombre_entidad_patronal.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_conyugue_participes.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->apellido_esposa_participes.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->cedula_conyugue_participes.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->numero_dependencias_participes.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_distritos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_provincias.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_ciudades.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->parroquia_participes_informacion_adicional.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->anios_residencia_participes_informacion_adicional.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_propietario_participes_informacion_adicional.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->telefono_propietario_participes_informacion_adicional.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_una_referencia_participes_informacion_adicional.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_parentesco.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->telefono_una_referencia_participes_informacion_adicional.'</td>';
                    
                    
                    
                    
                    
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_informacion_participes("index.php", $page, $total_pages, $adjacents).'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay Participes registrados...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
        
    }
    
    
    public function paginate_informacion_participes($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_informacionparticipes(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_informacionparticipes(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_informacionparticipes(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_informacionparticipes(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_informacionparticipes(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_informacionparticipes($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_informacionparticipes(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }

    
}

?>