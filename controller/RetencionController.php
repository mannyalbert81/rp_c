<?php
class RetencionController extends ControladorBase{
    public function index(){
        
        $retenciones = new RetencionesModel( );
        $mensaje="";
        $error="";
        session_start();
        
        if(empty( $_SESSION)){
            
            $this->redirect("Usuarios","sesion_caducada");
            return;
        }
        
        $nombre_controladores = "Retencion";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $retenciones->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (empty($resultPer)){
            
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos de Acceso Retenciones"
                
            ));
            exit();
        }
        
        
        
        $this->view_tributario("EnviarRetencion",array(
            "mensaje"=>$mensaje,
            "error"=> $error
            
        ));
        
        
    }

 public function Reporte_Retencion()
    {
        session_start();
        
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
        $retenciones = new RetencionesModel( );
        $id_tri_retenciones =  (isset($_REQUEST['id_tri_retenciones'])&& $_REQUEST['id_tri_retenciones'] !=NULL)?$_REQUEST['id_tri_retenciones']:'';
        
        $datos_reporte = array();
        
        $columnas = " tri_retenciones.id_tri_retenciones, 
                      tri_retenciones.infotributaria_ambiente, 
                      tri_retenciones.infotributaria_tipoemision, 
                      tri_retenciones.infotributaria_razonsocial, 
                      tri_retenciones.infotributaria_nombrecomercial, 
                      tri_retenciones.infotributaria_ruc, 
                      tri_retenciones.infotributaria_claveacceso, 
                      tri_retenciones.infotributaria_coddoc, 
                      tri_retenciones.infotributaria_estab, 
                      tri_retenciones.infotributaria_secuencial, 
                      tri_retenciones.infotributaria_dirmatriz, 
                      tri_retenciones.infocompretencion_fechaemision, 
                      tri_retenciones.infocompretencion_direstablecimiento, 
                      tri_retenciones.infocompretencion_contribuyenteespecial, 
                      tri_retenciones.infocompretencion_obligadocontabilidad, 
                      tri_retenciones.infocompretencion_tipoidentificacionsujetoretenido, 
                      tri_retenciones.infocompretencion_razonsocialsujetoretenido, 
                      tri_retenciones.infocompretencion_identificacionsujetoretenido, 
                      tri_retenciones.infocompretencion_periodofiscal, 
                      tri_retenciones.impuesto_codigo, 
                      tri_retenciones.impuesto_codigoretencion, 
                      tri_retenciones.impuestos_baseimponible, 
                      tri_retenciones.impuestos_porcentajeretener, 
                      tri_retenciones.impuestos_valorretenido, 
                      tri_retenciones.impuestos_coddocsustento, 
                      tri_retenciones.impuestos_numdocsustento, 
                      tri_retenciones.impuesto_fechaemisiondocsustento, 
                      tri_retenciones.impuesto_codigo_dos, 
                      tri_retenciones.impuesto_codigoretencion_dos, 
                      tri_retenciones.impuestos_baseimponible_dos, 
                      tri_retenciones.impuestos_porcentajeretener_dos, 
                      tri_retenciones.impuestos_valorretenido_dos, 
                      tri_retenciones.impuestos_coddocsustento_dos, 
                      tri_retenciones.impuestos_numdocsustento_dos, 
                      tri_retenciones.impuesto_fechaemisiondocsustento_dos, 
                      tri_retenciones.infoadicional_campoadicional, 
                      tri_retenciones.infoadicional_campoadicional_dos, 
                      tri_retenciones.infoadicional_campoadicional_tres,
                      tri_retenciones.fecha_autorizacion";
        
        $tablas = "  public.tri_retenciones";
        $where= "tri_retenciones.id_tri_retenciones='$id_tri_retenciones'";
        $id="tri_retenciones.id_tri_retenciones";
        
        $rsdatos = $retenciones->getCondiciones($columnas, $tablas, $where, $id);
       
		
        
        
        
       
        
        $datos_reporte['AMBIENTE']=$rsdatos[0]->infotributaria_ambiente;
        $datos_reporte['EMISION']=$rsdatos[0]->infotributaria_tipoemision;
        $datos_reporte['RAZONSOCIAL']=$rsdatos[0]->infotributaria_razonsocial;
        $datos_reporte['NOMBRECOMERCIAL']=$rsdatos[0]->infotributaria_nombrecomercial;
        $datos_reporte['RUC']=$rsdatos[0]->infotributaria_ruc;
       
        $datos_reporte['CLAVEACCESO']= $rsdatos[0]->infotributaria_claveacceso;
        
        include dirname(__FILE__).'\barcode.php';
        $nombreimagen = "codigoBarras";
        $code = $rsdatos[0]->infotributaria_claveacceso;
        $ubicacion =   dirname(__FILE__).'\..\view\images\barcode'.'\\'.$nombreimagen.'.png';
        barcode($ubicacion, $code, 50, 'horizontal', 'code128', true);
        
        $datos_reporte['IMGBARCODE']=$ubicacion;
        $datos_reporte['CODIGODOCUMENTO']=$rsdatos[0]->infotributaria_coddoc;
        $datos_reporte['ESTABLECIMIENTO']=$rsdatos[0]->infotributaria_estab;
        $datos_reporte['SECUENCIAL']=$rsdatos[0]->infotributaria_secuencial;
        $datos_reporte['DIRMATRIZ']=$rsdatos[0]->infotributaria_dirmatriz;
        $datos_reporte['FECHAEMISION']=$rsdatos[0]->infocompretencion_fechaemision;
        $datos_reporte['DIRESTABLECIMIENTO']=$rsdatos[0]->infocompretencion_direstablecimiento;
        $datos_reporte['CONTESPECIAL']=$rsdatos[0]->infocompretencion_contribuyenteespecial;
        $datos_reporte['OBCONTABILIDAD']=$rsdatos[0]->infocompretencion_obligadocontabilidad;
        $datos_reporte['TIPOIDENTIFICACION']=$rsdatos[0]->infocompretencion_tipoidentificacionsujetoretenido;
        $datos_reporte['RAZONSOCIALRETENIDO']=$rsdatos[0]->infocompretencion_razonsocialsujetoretenido;
        $datos_reporte['IDENTIFICACION']=$rsdatos[0]->infocompretencion_identificacionsujetoretenido;
        $datos_reporte['PERIODOFISCAL']=$rsdatos[0]->infocompretencion_periodofiscal;
        $datos_reporte['PERIODOFISCALDOS']=$rsdatos[0]->infocompretencion_periodofiscal;
        $datos_reporte['IMPCODIGO']=$rsdatos[0]->impuesto_codigo;
        $datos_reporte['IMPCODRETENCION']=$rsdatos[0]->impuesto_codigoretencion;
        $datos_reporte['IMPBASIMPONIBLE']=$rsdatos[0]->impuestos_baseimponible;
        $datos_reporte['IMPPORCATENER']=$rsdatos[0]->impuestos_porcentajeretener;
        $datos_reporte['VALRETENIDO']=$rsdatos[0]->impuestos_valorretenido;
        $datos_reporte['CODSUSTENTO']=$rsdatos[0]->impuestos_coddocsustento;
        $datos_reporte['NUMDOCSUST']=$rsdatos[0]->impuestos_numdocsustento;
        $datos_reporte['FECHEMDOCSUST']=$rsdatos[0]->impuesto_fechaemisiondocsustento;
        $datos_reporte['CODIGODOS']=$rsdatos[0]->impuesto_codigo_dos;
        $datos_reporte['CODRETDOS']=$rsdatos[0]->impuesto_codigoretencion_dos;
        $datos_reporte['BASEIMPDOS']=$rsdatos[0]->impuestos_baseimponible_dos;
        $datos_reporte['IMPPORCDOS']=$rsdatos[0]->impuestos_porcentajeretener_dos;
        $datos_reporte['VALRETDOS']=$rsdatos[0]->impuestos_valorretenido_dos;
        $datos_reporte['CODSUSTDOS']=$rsdatos[0]->impuestos_coddocsustento_dos;
        $datos_reporte['NUMSUSTDOS']=$rsdatos[0]->impuestos_numdocsustento_dos;
        $datos_reporte['FECHEMISIONDOS']=$rsdatos[0]->impuesto_fechaemisiondocsustento_dos;
        $datos_reporte['CAMPADICIONAL']=$rsdatos[0]->infoadicional_campoadicional;
        $datos_reporte['CAMPADICIONALDOS']=$rsdatos[0]->infoadicional_campoadicional_dos;
        $datos_reporte['CAMPADICIONALTRES']=$rsdatos[0]->infoadicional_campoadicional_tres;
       
        
        
        
        $datos_reporte['FECAUTORIZACION']=$rsdatos[0]->fecha_autorizacion;
        

        
        
        
        
        
        
        if (  $datos_reporte['AMBIENTE'] =="2"){
            
            $datos_reporte['AMBIENTE']="PRODUCCIÓN";
            
        }
        
        if (  $datos_reporte['EMISION'] =="1"){
            
            $datos_reporte['EMISION']="NORMAL";
            
        }
        
        if (  $datos_reporte['IMPCODIGO'] =="1"){
            
            $datos_reporte['IMPCODIGO']="RENTA";
            
        }
        
        if (  $datos_reporte['CODIGODOS'] =="2"){
            
            $datos_reporte['CODIGODOS']="IVA";
            
        }
        $_tipo_comprobante ="FACTURA";
        if (  $datos_reporte['CODSUSTENTO'] =="07"){
            
           $_tipo_comprobante="FACTURA";
            
        }
      
        
        //para imagen codigo barras
       
        
        //////retencion detalle
        
        $columnas = " tri_retenciones_detalle.impuesto_codigo,
					  tri_retenciones_detalle.impuesto_codigoretencion,
					  tri_retenciones_detalle.impuestos_baseimponible,
					  tri_retenciones_detalle.impuestos_porcentajeretener,
					  tri_retenciones_detalle.impuestos_valorretenido,
					  tri_retenciones_detalle.impuestos_coddocsustento,
					  tri_retenciones_detalle.impuestos_numdocsustento,
					  tri_retenciones_detalle.impuesto_fechaemisiondocsustento,
					  tri_retenciones_detalle.impuesto_codigo_dos,
					  tri_retenciones_detalle.id_tri_retenciones";
        
        $tablas = "  public.tri_retenciones,
  					public.tri_retenciones_detalle";
        $where= "tri_retenciones_detalle.id_tri_retenciones = tri_retenciones.id_tri_retenciones AND  tri_retenciones_detalle.id_tri_retenciones='$id_tri_retenciones'";
        $id="tri_retenciones_detalle.id_tri_retenciones_detalle";
        
        $retencion_detalle = $retenciones->getCondiciones($columnas, $tablas, $where, $id);
         
        
         $html='';
        
         
         $html.='<table class="info" style="width:98%;" border=1 >';
         $html.='<tr>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Comprobante</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Número</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Fecha Emisión</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Ejercicio Fiscal</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Base Imponible para la Retención</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Código Impuesto</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Porcentaje Retención</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Valor Retenido</th>';
         $html.='</tr>';
         
        
         
        foreach ($retencion_detalle as $res)
        {
        	
        	$html.='<tr >';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$_tipo_comprobante.'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->impuestos_numdocsustento.'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$datos_reporte['FECHAEMISION'].'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$datos_reporte['PERIODOFISCAL'].'</td>';
        	$html.='<td colspan="2" class="htexto3">$ '.$res->impuestos_baseimponible.'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->impuestos_coddocsustento.'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->impuestos_porcentajeretener.'</td>';
        	$html.='<td colspan="2" class="htexto3">$ '.$res->impuestos_valorretenido.'</td>';        	
        	$html.='</td>';
        	$html.='</tr>';
        }
        
        $html.='</table>';	
        
        $datos_reporte['DETALLE_RETENCION']= $html;
     
        
        
        
        $this->verReporte("Retencion", array('datos_reporte'=>$datos_reporte ));
        
        
            
    }
    
    
    public function consulta_retencion(){
        
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $usuarios = new UsuariosModel();
        $retenciones = new RetencionesModel();
        
        $where_to="";
        $columnas = "
                      tri_retenciones.id_tri_retenciones, 
                      tri_retenciones.infotributaria_ambiente, 
                      tri_retenciones.infotributaria_tipoemision, 
                      tri_retenciones.infotributaria_razonsocial, 
                      tri_retenciones.infotributaria_nombrecomercial, 
                      tri_retenciones.infotributaria_ruc, 
                      tri_retenciones.infotributaria_claveacceso, 
                      tri_retenciones.infotributaria_coddoc, 
                      tri_retenciones.infotributaria_ptoemi, 
                      tri_retenciones.infotributaria_estab, 
                      tri_retenciones.infotributaria_secuencial, 
                      tri_retenciones.infotributaria_dirmatriz, 
                      tri_retenciones.infocompretencion_fechaemision, 
                      tri_retenciones.infocompretencion_direstablecimiento, 
                      tri_retenciones.infocompretencion_contribuyenteespecial, 
                      tri_retenciones.infocompretencion_obligadocontabilidad, 
                      tri_retenciones.infocompretencion_tipoidentificacionsujetoretenido, 
                      tri_retenciones.infocompretencion_razonsocialsujetoretenido, 
                      tri_retenciones.infocompretencion_identificacionsujetoretenido, 
                      tri_retenciones.infocompretencion_periodofiscal, 
                      tri_retenciones.impuesto_codigo, 
                      tri_retenciones.impuesto_codigoretencion, 
                      tri_retenciones.impuestos_baseimponible, 
                      tri_retenciones.impuestos_porcentajeretener, 
                      tri_retenciones.impuestos_valorretenido, 
                      tri_retenciones.impuestos_coddocsustento, 
                      tri_retenciones.impuestos_numdocsustento, 
                      tri_retenciones.impuesto_fechaemisiondocsustento, 
                      tri_retenciones.impuesto_codigo_dos, 
                      tri_retenciones.impuesto_codigoretencion_dos, 
                      tri_retenciones.impuestos_baseimponible_dos, 
                      tri_retenciones.impuestos_porcentajeretener_dos, 
                      tri_retenciones.impuestos_valorretenido_dos, 
                      tri_retenciones.impuestos_coddocsustento_dos, 
                      tri_retenciones.impuestos_numdocsustento_dos, 
                      tri_retenciones.impuesto_fechaemisiondocsustento_dos, 
                      tri_retenciones.infoadicional_campoadicional, 
                      tri_retenciones.infoadicional_campoadicional_dos, 
                      tri_retenciones.infoadicional_campoadicional_tres, 
                      tri_retenciones.creado, 
                      tri_retenciones.modificado,
                      tri_retenciones.enviado_correo_electronico,
                      tri_retenciones.fecha_autorizacion
            
                      ";
        $tablas   = "
                     public.tri_retenciones

                    ";
        $where    = "tri_retenciones.id_tri_retenciones=id_tri_retenciones";
        
        $id       = "tri_retenciones.fecha_autorizacion";
        
        
       
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            if(!empty($search)){
                
                
                $where1=" AND (infocompretencion_razonsocialsujetoretenido LIKE '%".$search."%' OR infocompretencion_identificacionsujetoretenido LIKE '%".$search."%' OR infotributaria_secuencial LIKE '%".$search."%')
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
                $html.= "<table id='tabla_retencion' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: center;  font-size: 12px;">Número de Comprobante</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Contribuyente Nro.</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombres y Apellidos</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Identificación</th>';
                $html.='<th style="text-align: center;  font-size: 12px;">Fecha de Emisión</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Enviado Mail</th>';
                $html.='<th style="text-align: center; font-size: 12px;"></th>';
               
                
                
                
                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                $importe=0;
                $coreo_envidado="";
                foreach ($resultSet as $res)
                {
                    
                    
                    $correo = $res->enviado_correo_electronico;
                    
                    
                    if ($correo=='t'){
                        
                        $coreo_envidado="Si";
                        
                    }else {
                        
                        $coreo_envidado="No";
                        
                    }
                    
                    $i++;
                    $html.='<tr>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$i.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">001-001-'.$res->infotributaria_secuencial.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->infocompretencion_contribuyenteespecial.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->infocompretencion_razonsocialsujetoretenido.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->infocompretencion_identificacionsujetoretenido.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->infocompretencion_fechaemision.'</td>';
                    
                    $html.='<td style="text-align: center; font-size: 11px;">'.$coreo_envidado.'</td>';
                    
                    
                    $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right"><a href="index.php?controller=Retencion&action=Reporte_Retencion&id_tri_retenciones='.$res->id_tri_retenciones.'" target="_blank" title="Generar Comprobante"><i class="glyphicon glyphicon-print"></i></a></span></td>';
                    
                    if($coreo_envidado=="No"){
                    
                    $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right"><a href="index.php?controller=Retencion&action=Enviar_Correo&id_tri_retenciones='.$res->id_tri_retenciones.'" title="Enviar Email"><img style=" height:15px; width:15px;" src="view/images/enviar.png"></img></a></span></td>';
                    
                    }else{
                        
                        $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right"><a href="Javascriptvoid:0;" title="Enviar Email" disabled><img style=" height:15px; width:15px;" src="view/images/enviar.png"></img></a></span></td>';
                        
                    }
                    
                    
                    
                    
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_retencion("index.php", $page, $total_pages, $adjacents).'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay Retenciones registradas...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
        
    }
    
    
    public function paginate_retencion($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_retencion(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_retencion(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_retencion(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_retencion(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_retencion(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_retencion($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_retencion(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    public function Enviar_Correo(){
        
    	include('view/pdf.php');
    	$file_name = md5(rand()) . '.pdf';
    	$html_code = '<link rel="stylesheet" href="bootstrap.min.css">';
    	//$html_code .= fetch_customer_data($connect);
    	
    	$_nombre_archivo = "";
    	
    	$pdf = new Pdf();
    	$pdf->load_html($html_code);
    	$pdf->render();
    	$file = $pdf->output();
    	file_put_contents($file_name, $file);
    	
    	
    	$retenciones = new RetencionesModel();
    	
    	if(isset($_GET["id_tri_retenciones"])){
    	$id_tri_retenciones=(int)$_GET["id_tri_retenciones"];
    		//BUSCAR EL ARCHIVO pdf Y xml
    	
    	
    	
    	///////CORREOS
    	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    	
    	$retenciones = new RetencionesModel( );
    	$id_tri_retenciones =  (isset($_REQUEST['id_tri_retenciones'])&& $_REQUEST['id_tri_retenciones'] !=NULL)?$_REQUEST['id_tri_retenciones']:'';
    	
    	$datos_reporte = array();
    	
    	
        
        $columnas = " tri_retenciones.id_tri_retenciones, 
                      tri_retenciones.infotributaria_ambiente, 
                      tri_retenciones.infotributaria_tipoemision, 
                      tri_retenciones.infotributaria_razonsocial, 
                      tri_retenciones.infotributaria_nombrecomercial, 
                      tri_retenciones.infotributaria_ruc, 
                      tri_retenciones.infotributaria_claveacceso, 
                      tri_retenciones.infotributaria_coddoc, 
                      tri_retenciones.infotributaria_estab, 
                      tri_retenciones.infotributaria_secuencial, 
                      tri_retenciones.infotributaria_dirmatriz, 
                      tri_retenciones.infocompretencion_fechaemision, 
                      tri_retenciones.infocompretencion_direstablecimiento, 
                      tri_retenciones.infocompretencion_contribuyenteespecial, 
                      tri_retenciones.infocompretencion_obligadocontabilidad, 
                      tri_retenciones.infocompretencion_tipoidentificacionsujetoretenido, 
                      tri_retenciones.infocompretencion_razonsocialsujetoretenido, 
                      tri_retenciones.infocompretencion_identificacionsujetoretenido, 
                      tri_retenciones.infocompretencion_periodofiscal, 
                      tri_retenciones.impuesto_codigo, 
                      tri_retenciones.impuesto_codigoretencion, 
                      tri_retenciones.impuestos_baseimponible, 
                      tri_retenciones.impuestos_porcentajeretener, 
                      tri_retenciones.impuestos_valorretenido, 
                      tri_retenciones.impuestos_coddocsustento, 
                      tri_retenciones.impuestos_numdocsustento, 
                      tri_retenciones.impuesto_fechaemisiondocsustento, 
                      tri_retenciones.impuesto_codigo_dos, 
                      tri_retenciones.impuesto_codigoretencion_dos, 
                      tri_retenciones.impuestos_baseimponible_dos, 
                      tri_retenciones.impuestos_porcentajeretener_dos, 
                      tri_retenciones.impuestos_valorretenido_dos, 
                      tri_retenciones.impuestos_coddocsustento_dos, 
                      tri_retenciones.impuestos_numdocsustento_dos, 
                      tri_retenciones.impuesto_fechaemisiondocsustento_dos, 
                      tri_retenciones.infoadicional_campoadicional, 
                      tri_retenciones.infoadicional_campoadicional_dos, 
                      tri_retenciones.infoadicional_campoadicional_tres,
                      tri_retenciones.fecha_autorizacion";
        
        $tablas = "  public.tri_retenciones";
        $where= "tri_retenciones.id_tri_retenciones='$id_tri_retenciones'";
        $id="tri_retenciones.id_tri_retenciones";
        
        $rsdatos = $retenciones->getCondiciones($columnas, $tablas, $where, $id);
       
		
        
        
        
       
        
        $datos_reporte['AMBIENTE']=$rsdatos[0]->infotributaria_ambiente;
        $datos_reporte['EMISION']=$rsdatos[0]->infotributaria_tipoemision;
        $datos_reporte['RAZONSOCIAL']=$rsdatos[0]->infotributaria_razonsocial;
        $datos_reporte['NOMBRECOMERCIAL']=$rsdatos[0]->infotributaria_nombrecomercial;
        $datos_reporte['RUC']=$rsdatos[0]->infotributaria_ruc;
       
        $datos_reporte['CLAVEACCESO']= $rsdatos[0]->infotributaria_claveacceso;
        
        include dirname(__FILE__).'\barcode.php';
        $nombreimagen = "codigoBarras";
        $code = $rsdatos[0]->infotributaria_claveacceso;
        $ubicacion =   dirname(__FILE__).'\..\view\images\barcode'.'\\'.$nombreimagen.'.png';
        barcode($ubicacion, $code, 50, 'horizontal', 'code128', true);
        
        $datos_reporte['IMGBARCODE']=$ubicacion;
        $datos_reporte['CODIGODOCUMENTO']=$rsdatos[0]->infotributaria_coddoc;
        $datos_reporte['ESTABLECIMIENTO']=$rsdatos[0]->infotributaria_estab;
        $datos_reporte['SECUENCIAL']=$rsdatos[0]->infotributaria_secuencial;
        $datos_reporte['DIRMATRIZ']=$rsdatos[0]->infotributaria_dirmatriz;
        $datos_reporte['FECHAEMISION']=$rsdatos[0]->infocompretencion_fechaemision;
        $datos_reporte['DIRESTABLECIMIENTO']=$rsdatos[0]->infocompretencion_direstablecimiento;
        $datos_reporte['CONTESPECIAL']=$rsdatos[0]->infocompretencion_contribuyenteespecial;
        $datos_reporte['OBCONTABILIDAD']=$rsdatos[0]->infocompretencion_obligadocontabilidad;
        $datos_reporte['TIPOIDENTIFICACION']=$rsdatos[0]->infocompretencion_tipoidentificacionsujetoretenido;
        $datos_reporte['RAZONSOCIALRETENIDO']=$rsdatos[0]->infocompretencion_razonsocialsujetoretenido;
        $datos_reporte['IDENTIFICACION']=$rsdatos[0]->infocompretencion_identificacionsujetoretenido;
        $datos_reporte['PERIODOFISCAL']=$rsdatos[0]->infocompretencion_periodofiscal;
        $datos_reporte['PERIODOFISCALDOS']=$rsdatos[0]->infocompretencion_periodofiscal;
        $datos_reporte['IMPCODIGO']=$rsdatos[0]->impuesto_codigo;
        $datos_reporte['IMPCODRETENCION']=$rsdatos[0]->impuesto_codigoretencion;
        $datos_reporte['IMPBASIMPONIBLE']=$rsdatos[0]->impuestos_baseimponible;
        $datos_reporte['IMPPORCATENER']=$rsdatos[0]->impuestos_porcentajeretener;
        $datos_reporte['VALRETENIDO']=$rsdatos[0]->impuestos_valorretenido;
        $datos_reporte['CODSUSTENTO']=$rsdatos[0]->impuestos_coddocsustento;
        $datos_reporte['NUMDOCSUST']=$rsdatos[0]->impuestos_numdocsustento;
        $datos_reporte['FECHEMDOCSUST']=$rsdatos[0]->impuesto_fechaemisiondocsustento;
        $datos_reporte['CODIGODOS']=$rsdatos[0]->impuesto_codigo_dos;
        $datos_reporte['CODRETDOS']=$rsdatos[0]->impuesto_codigoretencion_dos;
        $datos_reporte['BASEIMPDOS']=$rsdatos[0]->impuestos_baseimponible_dos;
        $datos_reporte['IMPPORCDOS']=$rsdatos[0]->impuestos_porcentajeretener_dos;
        $datos_reporte['VALRETDOS']=$rsdatos[0]->impuestos_valorretenido_dos;
        $datos_reporte['CODSUSTDOS']=$rsdatos[0]->impuestos_coddocsustento_dos;
        $datos_reporte['NUMSUSTDOS']=$rsdatos[0]->impuestos_numdocsustento_dos;
        $datos_reporte['FECHEMISIONDOS']=$rsdatos[0]->impuesto_fechaemisiondocsustento_dos;
        $datos_reporte['CAMPADICIONAL']=$rsdatos[0]->infoadicional_campoadicional;
        $datos_reporte['CAMPADICIONALDOS']=$rsdatos[0]->infoadicional_campoadicional_dos;
        $datos_reporte['CAMPADICIONALTRES']=$rsdatos[0]->infoadicional_campoadicional_tres;
       
        
        
        
        $datos_reporte['FECAUTORIZACION']=$rsdatos[0]->fecha_autorizacion;
        

        
        
        
        
        
        
        if (  $datos_reporte['AMBIENTE'] =="2"){
            
            $datos_reporte['AMBIENTE']="PRODUCCIÓN";
            
        }
        
        if (  $datos_reporte['EMISION'] =="1"){
            
            $datos_reporte['EMISION']="NORMAL";
            
        }
        
        if (  $datos_reporte['IMPCODIGO'] =="1"){
            
            $datos_reporte['IMPCODIGO']="RENTA";
            
        }
        
        if (  $datos_reporte['CODIGODOS'] =="2"){
            
            $datos_reporte['CODIGODOS']="IVA";
            
        }
        $_tipo_comprobante ="FACTURA";
        if (  $datos_reporte['CODSUSTENTO'] =="07"){
            
           $_tipo_comprobante="FACTURA";
            
        }
      
        
        //para imagen codigo barras
       
        
        //////retencion detalle
        
        $columnas = " tri_retenciones_detalle.impuesto_codigo,
					  tri_retenciones_detalle.impuesto_codigoretencion,
					  tri_retenciones_detalle.impuestos_baseimponible,
					  tri_retenciones_detalle.impuestos_porcentajeretener,
					  tri_retenciones_detalle.impuestos_valorretenido,
					  tri_retenciones_detalle.impuestos_coddocsustento,
					  tri_retenciones_detalle.impuestos_numdocsustento,
					  tri_retenciones_detalle.impuesto_fechaemisiondocsustento,
					  tri_retenciones_detalle.impuesto_codigo_dos,
					  tri_retenciones_detalle.id_tri_retenciones";
        
        $tablas = "  public.tri_retenciones,
  					public.tri_retenciones_detalle";
        $where= "tri_retenciones_detalle.id_tri_retenciones = tri_retenciones.id_tri_retenciones AND  tri_retenciones_detalle.id_tri_retenciones='$id_tri_retenciones'";
        $id="tri_retenciones_detalle.id_tri_retenciones_detalle";
        
        $retencion_detalle = $retenciones->getCondiciones($columnas, $tablas, $where, $id);
         
        
        $reultRet=$retenciones->getBy("id_tri_retenciones='$id_tri_retenciones'");
        $camino_nombre_xml = "";
        if(!empty($reultRet)){
        	 
        	$infotributaria_claveacceso = $reultRet[0]->infotributaria_claveacceso;
        	$camino_nombre_xml = "DOCUMENTOS_ELECTRONICOS/COMPROBANTES AUTORIZADOS/".$infotributaria_claveacceso	 . ".XML";
        	$_nombre_archivo = "DOCUMENTOS_GENERADOS/RETENCIONES/".$infotributaria_claveacceso	 . ".PDF";
        }
         
        
        
         $html='';
        
        
          
         
         $html.='<table class="info" style="width:98%;" border=1 >';
         $html.='<tr>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Comprobante</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Número</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Fecha Emisión</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Ejercicio Fiscal</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Base Imponible para la Retención</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Código Impuesto</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Porcentaje Retención</th>';
         $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Valor Retenido</th>';
         $html.='</tr>';
         
        
        
         
        foreach ($retencion_detalle as $res)
        {
        	
        	$html.='<tr >';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'. $_tipo_comprobante.'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->impuestos_numdocsustento.'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$datos_reporte['FECHAEMISION'].'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$datos_reporte['PERIODOFISCAL'].'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->impuestos_baseimponible.'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->impuestos_coddocsustento.'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->impuestos_porcentajeretener.'</td>';
        	$html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->impuestos_valorretenido.'</td>';
        		
        	
        	$html.='</td>';
        	$html.='</tr>';
        }
        
        $html.='</table>';	
        
        $datos_reporte['DETALLE_RETENCION']= $html;
     
        
        //$_nombre_archivo = "DOCUMENTOS_GENERADOS/RETENCIONES/" . $rsdatos[0]->infotributaria_claveacceso . ".PDF";
        
    	
    	$this->verReporte("Retencion", array('datos_reporte'=>$datos_reporte ,'_nombre_archivo'=>$_nombre_archivo));
    	 
    	 
    	/*
    	echo file_get_contents($camino_nombre_xml);
    	die();
    	*/
    	$reultSet=$retenciones->getBy("id_tri_retenciones='$id_tri_retenciones'");
    	
    	
    	
    	
    	if(!empty($reultSet)){
    	   	
    			$infoadicional_campoadicional_tres_correo = $reultSet[0]->infoadicional_campoadicional_tres;
    		
    			//$cabeceras .= "Content-type: text/html; charset=utf-8 \r\n";
    		
    	
    			$cuerpo=" 
    			 
    			<table rules='all'>
    			<tr><td WIDTH='800' HEIGHT='50'><center><img src='http://186.4.157.125:80/webcapremci/view/images/bcaprem.png' WIDTH='300' HEIGHT='120'/></center></td></tr>
    			</tabla>
    			<p><table rules='all'></p>
    			<tr style='background: #FFFFFF;'><td  WIDTH='1000' align='center'><b> BIENVENIDO A CAPREMCI </b></td></tr></p>
    			<tr style='background: #FFFFFF;'><td  WIDTH='1000' align='justify'>Somos un Fondo Previsional orientado a asegurar el futuro de sus partícipes, prestando servicios complementarios para satisfacer sus necesidades; con infraestructura tecnológica – operativa de vanguardia y talento humano competitivo.</td></tr>
    			 
    			<tr style='background: #FFFFFF;'><td  WIDTH='1000' align='justify'>Le  informamos que adjunto a este correo se encuentra su documento electrónico en formato XML, así como su interpretación en formato PDF.</td></tr>
    			 
    			</tabla>
    			<p><table rules='all'></p>
    		    <tr style='background: #FFFFFF;'>
    			</tabla>
    			<p><table rules='all'></p>
    			<tr style='background:#1C1C1C'><td WIDTH='1000' HEIGHT='50' align='center'><font color='white'>Capremci - <a href='http://www.capremci.com.ec'><FONT COLOR='#7acb5a'>www.capremci.com.ec</FONT></a> - Copyright © 2018-</font></td></tr>
    			</table>
    			";
    	
    			require 'clases/email/class.phpmailer.php';
    			$mail = new PHPMailer;
    			$mail->IsSMTP();								//Sets Mailer to send message using SMTP
    			$mail->Host = 'mail.capremci.com.ec';		//Sets the SMTP hosts of your Email hosting, this for Godaddy
    			$mail->Port = '587';								//Sets the default SMTP server port
    			$mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
    			$mail->Username = 'info@capremci.com.ec';					//Sets SMTP username
    			$mail->Password = 'info/*-+2018';					//Sets SMTP password
    			$mail->SMTPSecure = '';					
    			$mail->CharSet = 'UTF-8';
    			$mail->FromName = mb_convert_encoding($header, "UTF-8", "auto");//Sets connection prefix. Options are "", "ssl" or "tls"
    			$mail->From = 'info@capremci.com.ec';			//Sets the From email address for the message
    			$mail->FromName = 'Documentos Electronicos Capremci';			//Sets the From name of the message
    			$mail->AddAddress( $infoadicional_campoadicional_tres_correo,'');		//Adds a "To" address
    			$mail->AddAddress( 'documentoselectronicos@capremci.com.ec','');		//Adds a "To" address
    			
    			$mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
    			$mail->IsHTML(true);							//Sets message type to HTML
    			$mail->AddAttachment($camino_nombre_xml);     				//Adds an attachment from a path on the filesystem
    			$mail->AddAttachment($_nombre_archivo);     				//Adds an attachment from a path on the filesystem
    			
    			$mail->Subject = 'Documentos Electrónico Generado';			//Sets the Subject of the message
    			$mail->Body = $cuerpo;				//An HTML or plain text message body
    			if($mail->Send())								//Send an Email. Return true on success or false on error
    			{
    				$colval="enviado_correo_electronico='TRUE'";
    				$tabla="tri_retenciones";
    				$where="id_tri_retenciones='$id_tri_retenciones'";
    				 
    				 
    				$resultado = $retenciones->UpdateBy($colval, $tabla, $where);
    				 
    			}
    			$message = '<label class="text-success">Customer Details has been send successfully...</label>';
    			}
    			unlink($file_name);

    			
    	
    		}
    	
    	
    	
    	
    		  $this->view_tributario("EnviarRetencion",array(
    		      "mensaje"=>$mensaje,
    		      "error"=> $error
    	
    	
    		  		));
    	
    	
    	
    	
    	
    	
    	
       /*
        session_start();
       
        $error=FALSE;
       
        
        $retenciones = new RetencionesModel();
        
       
        
        
        if(isset($_GET["id_tri_retenciones"])){
        
            
            $id_tri_retenciones=(int)$_GET["id_tri_retenciones"];
            
            
            
            //BUSCAR EL ARCHIVO pdf Y xml
            
            $reultRet=$retenciones->getBy("id_tri_retenciones='$id_tri_retenciones'");
            $camino_nombre_xml = "";
            if(!empty($reultRet)){
            	 
            	$infotributaria_claveacceso = $reultRet[0]->infotributaria_claveacceso;
            	$camino_nombre_xml = "/DOCUMENTOS_ELECTRONICOS/COMPROBANTES AUTORIZADOS/".$infotributaria_claveacceso	 . ".XML";
            }
            
            
            
             
         
            
            
            $reultSet=$retenciones->getBy("id_tri_retenciones='$id_tri_retenciones'");
            
            
            if(!empty($reultSet)){
                
                
                $infoadicional_campoadicional_tres_correo = $reultSet[0]->infoadicional_campoadicional_tres;
              
                
                
                $cabeceras = "MIME-Version: 1.0 \r\n";
                $headers .= "Content-Type: multipart/mixed; boundary=\"=A=G=R=O=\"\r\n\r\n";
                
                //$cabeceras .= "Content-type: text/html; charset=utf-8 \r\n";
                $cabeceras.= "From: documentoselectronicos@capremci.com.ec \r\n";
                $destino = 'mrosabal@capremci.com.ec'. ', '; //$infoadicional_campoadicional_tres_correo. ', ';
                $destino .= 'documentoselectronicos@capremci.com.ec' . ', ';
                $destino .= 'mrosabal@capremci.com.ec';
                
                
                $asunto="Comprobante de Retención";
                $fecha=date("d/m/y");
                $hora=date("H:i:s");
                
                
                $cuerpo="
                            <table rules='all'>
                           <tr><td WIDTH='1000' HEIGHT='50'><center><img src='http://186.4.157.125:80/webcapremci/view/images/bcaprem.png' WIDTH='300' HEIGHT='120'/></center></td></tr>
                           </tabla>
                           <p><table rules='all'></p>
                           <tr style='background: #FFFFFF;'><td  WIDTH='1000' align='center'><b> BIENVENIDO A CAPREMCI </b></td></tr></p>
                           <tr style='background: #FFFFFF;'><td  WIDTH='1000' align='justify'>Somos un Fondo Previsional orientado a asegurar el futuro de sus partícipes, prestando servicios complementarios para satisfacer sus necesidades; con infraestructura tecnológica – operativa de vanguardia y talento humano competitivo.</td></tr>
                           
                           <tr style='background: #FFFFFF;'><td  WIDTH='1000' align='justify'>Le  informamos que adjunto a este correo se encuentra su documento electrónico en formato XML, así como su interpretación en formato PDF.</td></tr>
                             
                           </tabla>
                           <p><table rules='all'></p>
                            <tr style='background: #FFFFFF;'><td  WIDTH='1000' align='center'><b> REPORTE DE RETENCIÓN </b></td></tr></p>
                            
                           <tr style='background: #FFFFFF'><td WIDTH='1000' align='center'><b> Descargar</b></td></tr>
                           <tr style='background: #FFFFFF'><td WIDTH='1000' align='center'><a href='http://186.4.157.125:80/rp_c/index.php?controller=Retencion&action=Reporte_Retencion&id_tri_retenciones=$id_tri_retenciones' target='_blank'><img style=' height:10px; width:10px;' src='http://192.168.1.222:4000/rp_c/view/images/pdf-icon.png'></img></a></td></tr>
                           <tr style='background: #FFFFFF;'>
                           </tabla>
                           <p><table rules='all'></p>
                           <tr style='background:#1C1C1C'><td WIDTH='1000' HEIGHT='50' align='center'><font color='white'>Capremci - <a href='http://www.capremci.com.ec'><FONT COLOR='#7acb5a'>www.capremci.com.ec</FONT></a> - Copyright © 2018-</font></td></tr>
                           </table>               
        ";
                
                // -> Segunda parte del mensaje (archivo adjunto)
                // -> encabezado de la parte
                $cuerpo .= "--=C=T=E=C=\r\n";
                $cuerpo .= "Content-Type: application/octet-stream; ";
                $cuerpo .= "name=" .$nameFile. "\r\n";
                $cuerpo .= "Content-Transfer-Encoding: base64\r\n";
                $cuerpo .= "Content-Disposition: attachment; ";
                $cuerpo .= "filename= " .$nameFile. "\r\n";
                $cuerpo .= "\r\n"; //línea vacía
                
                $fp = fopen($tempFile, "rb");
                $file = fread($fp, $sizeFile);
                $file = chunk_split(base64_encode($file));
                
                $cuerpo .= "$file\r\n";
                $cuerpo .= "\r\n"; //linea vacia
                //Delimitador de final del mensaje.
                $cuerpo .= "--=C=T=E=C=--\r\n";
                
                
                
               // if(mail("$destino","Comprobante de Retención","$resumen","$cabeceras"))
               if(mail("manuel@masoft.net","Comprobante de Retención","$cuerpo","$cabeceras"))
                {
                    $mensaje = "Correo enviado a $infoadicional_campoadicional_tres_correo correctamente.";
                    $error=FALSE;
                    
                    
                    
                    $colval="enviado_correo_electronico='TRUE'";
                    $tabla="tri_retenciones";
                    $where="id_tri_retenciones='$id_tri_retenciones'";
                    
                    
                    $resultado = $retenciones->UpdateBy($colval, $tabla, $where);
                    
                    //UPDATE
                    
                }else{
                    $mensaje = "No se pudo enviar el correo con la información. Intentelo nuevamente.";
                    $error = TRUE;
                    
                }
                
                
            }
            
        }
      
      //  $this->view_tributario("EnviarRetencion",array(
      //      "mensaje"=>$mensaje, 
      //      "error"=> $error
            
            
      //  ));
        
        
        
        
       */ 
    }
    
}

?>