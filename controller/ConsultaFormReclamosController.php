<?php
class ConsultaFormReclamosController extends ControladorBase{
    public function index(){
        
        $reclamos = new ReclamosModel();
        $mensaje="";
        $error="";
        session_start();
        
        if(empty( $_SESSION)){
            
            $this->redirect("Usuarios","sesion_caducada");
            return;
        }
        
        $nombre_controladores = "ConsultaFormReclamos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $reclamos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (empty($resultPer)){
            
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos de Acceso a Reclamos"
                
            ));
            exit();
        }
        
        
        
        $this->view_Administracion("ConsultaFormReclamos",array(
            "mensaje"=>$mensaje,
            "error"=> $error
            
        ));
        
        
    }

    public function Reporte_Reclamos()
    {
        
        
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
        
        //NOTICE DATA
        $datos_cabecera = array();
        $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
        $datos_cabecera['FECHA'] = date('Y/m/d');
        $datos_cabecera['HORA'] = date('h:i:s');
        
        
        
        
        
        
        $reclamos=new ReclamosModel();
        $id_form_reclamos =  (isset($_REQUEST['id_form_reclamos'])&& $_REQUEST['id_form_reclamos'] !=NULL)?$_REQUEST['id_form_reclamos']:'';
        
        $datos_reporte = array();
        
        $columnas = " formulario_reclamos.id_form_reclamos,
                      formulario_reclamos.nombres_form_reclamos,
                      formulario_reclamos.apellidos_form_reclamos,
                      formulario_reclamos.edad_form_reclamos,
                      formulario_reclamos.teleono_form_reclamos,
                      formulario_reclamos.celular_form_reclamos,
                      formulario_reclamos.nacionali_form_reclamos,
                      formulario_reclamos.email_form_reclamos,
                      formulario_reclamos.direccion_form_reclamos,
                      formulario_reclamos.detalle_form_reclamos,
                      formulario_reclamos.fecha_form_reclamos";
        
        $tablas = "  public.formulario_reclamos ";
        $where= "    formulario_reclamos.id_form_reclamos='$id_form_reclamos'";
        $id="formulario_reclamos.nombres_form_reclamos";
        
        $rsdatos = $reclamos->getCondiciones($columnas, $tablas, $where, $id);
        
        
        
        $datos_reporte['NOMBRES_RECLAMOS']=$rsdatos[0]->nombres_form_reclamos;
        $datos_reporte['APELLIDOS_RECLAMOS']=$rsdatos[0]->apellidos_form_reclamos;
        $datos_reporte['EDAD_RECLAMOS']=$rsdatos[0]->edad_form_reclamos;
        $datos_reporte['TELEFONO_RECLAMOS']=$rsdatos[0]->teleono_form_reclamos;
        $datos_reporte['CELULAR_RECLAMOS']=$rsdatos[0]->celular_form_reclamos;
        $datos_reporte['NACIONALIDAD_RECLAMOS']=$rsdatos[0]->nacionali_form_reclamos;
        $datos_reporte['EMAIL_RECLAMOS']=$rsdatos[0]->email_form_reclamos;
        $datos_reporte['DIRECCION_RECLAMOS']=$rsdatos[0]->direccion_form_reclamos;
        $datos_reporte['DETALLE_RECLAMOS']=$rsdatos[0]->detalle_form_reclamos;
        $datos_reporte['FECHA_RECLAMOS']=$rsdatos[0]->fecha_form_reclamos;
        
        
        
        
        $this->verReporte("ReporteReclamos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte ));
        
        
        
    }
    
    
    public function consulta_reclamos(){
        
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $usuarios = new UsuariosModel();
        $reclamos = new ReclamosModel();
        
        $where_to="";
        $columnas = "formulario_reclamos.id_form_reclamos,
                      formulario_reclamos.nombres_form_reclamos,
                      formulario_reclamos.apellidos_form_reclamos,
                      formulario_reclamos.edad_form_reclamos,
                      formulario_reclamos.teleono_form_reclamos,
                      formulario_reclamos.celular_form_reclamos,
                      formulario_reclamos.nacionali_form_reclamos,
                      formulario_reclamos.email_form_reclamos,
                      formulario_reclamos.direccion_form_reclamos,
                      formulario_reclamos.detalle_form_reclamos,
                      formulario_reclamos.fecha_form_reclamos
                      ";
        $tablas   = "
                     public.formulario_reclamos

                    ";
        $where    = "1=1";
        
        $id       = "formulario_reclamos.id_form_reclamos";
        
        
       
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            if(!empty($search)){
                
                
                $where1=" AND (nombres_form_reclamos LIKE '%".$search."%')
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
                $html.= "<table id='tabla_reclamos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombres</th>';
                $html.='<th style="text-align: center;  font-size: 12px;">Apellidos</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Edad</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nacionalidad</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Teléfono</th>';
                $html.='<th style="text-align: center;  font-size: 12px;">E-Mail</th>';
                $html.='<th style="text-align: center; font-size: 12px;"></th>';
               
                
                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                $i=0;
                foreach ($resultSet as $res)
                {
                    
                  
                    
                    $i++;
                    $html.='<tr>'; 
                    $html.='<td style="text-align: center; font-size: 11px;">'.$i.'</td>';
                    $html.='<td style="text-align: left; font-size: 11px;">'.$res->nombres_form_reclamos.'</td>';
                    $html.='<td style="text-align: left; font-size: 11px;">'.$res->apellidos_form_reclamos.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->edad_form_reclamos.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->nacionali_form_reclamos.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->celular_form_reclamos.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->email_form_reclamos.'</td>';
                    
                    $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right"><a href="index.php?controller=ConsultaFormReclamos&action=Reporte_Reclamos&id_form_reclamos='.$res->id_form_reclamos.'" target="_blank" title="Generar Reclamo"><i class="glyphicon glyphicon-print"></i></a></span></td>';
                    
                   
                    
                    
                    
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_reclamos("index.php", $page, $total_pages, $adjacents).'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay Reclamos registrados...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
        
    }
    
    
    public function paginate_reclamos($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_reclamos(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_reclamos(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_reclamos(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_reclamos(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_reclamos(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_reclamos($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_reclamos(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    

    
    
    
    
    
    
    
}

?>