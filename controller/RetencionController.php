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
        
        
        
        $this->view_tesoreria("GenerarRetencion",array(
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
        if (  $datos_reporte['CODSUSTENTO'] =="01"){
            
            $datos_reporte['CODSUSTENTO']="FACTURA";
            
        }
        if (  $datos_reporte['CODSUSTDOS'] =="01"){
            
            $datos_reporte['CODSUSTDOS']="FACTURA";
            
        }
        
        if (  $datos_reporte['CODSUSTENTO'] ==""){
            
            $datos_reporte['CODSUSTENTO']="-";
            $datos_reporte['NUMDOCSUST']="-";
            $datos_reporte['FECHEMDOCSUST']="-";
            $datos_reporte['IMPBASIMPONIBLE']="-";
            $datos_reporte['PERIODOFISCAL']="-";
            $datos_reporte['IMPCODIGO']="-";
            $datos_reporte['IMPPORCATENER']="-";
            $datos_reporte['VALRETENIDO']="-";
            
        }
        if (  $datos_reporte['CODSUSTDOS'] ==""){
            
            $datos_reporte['CODSUSTDOS']="-";
            $datos_reporte['NUMSUSTDOS']="-";
            $datos_reporte['FECHEMISIONDOS']="-";
            $datos_reporte['BASEIMPDOS']="-";
            $datos_reporte['PERIODOFISCALDOS']="-";
            $datos_reporte['CODIGODOS']="-";
            $datos_reporte['IMPPORCDOS']="-";
            $datos_reporte['VALRETDOS']="-";
            
        }
        
        //para imagen codigo barras
       
        
        
        $this->verReporte("Retencion", array('datos_reporte'=>$datos_reporte));
        
        
            
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
        
        $id       = "tri_retenciones.id_tri_retenciones";
        
        
       
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            if(!empty($search)){
                
                
                $where1=" AND (infocompretencion_razonsocialsujetoretenido LIKE '".$search."%' OR infocompretencion_identificacionsujetoretenido LIKE '".$search."%' OR infotributaria_secuencial LIKE '%".$search."%')";
                
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
            
            $resultSet=$usuarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
        
       
        session_start();
       
        $error=FALSE;
       
        
        $retenciones = new RetencionesModel();
        
       
        
        
        if(isset($_GET["id_tri_retenciones"])){
        
            
            $id_tri_retenciones=(int)$_GET["id_tri_retenciones"];
            
            
            
            
            
            $reultSet=$retenciones->getBy("id_tri_retenciones='$id_tri_retenciones'");
            
            
            if(!empty($reultSet)){
                
                
                $infoadicional_campoadicional_tres_correo = $reultSet[0]->infoadicional_campoadicional_tres;
                
                
                
                
                
                
                $cabeceras = "MIME-Version: 1.0 \r\n";
                $cabeceras .= "Content-type: text/html; charset=utf-8 \r\n";
                $cabeceras.= "From: documentoselectronicos@capremci.com.ec \r\n";
                $destino = $infoadicional_campoadicional_tres_correo. ', ';
                $destino .= 'documentoselectronicos@capremci.com.ec' . ', ';
                $destino .= 'bbolanos@capremci.com.ec';
                
                
                $asunto="Comprobante de Retención";
                $fecha=date("d/m/y");
                $hora=date("H:i:s");
                
                
                $resumen="
                            <table rules='all'>
                           <tr><td WIDTH='1000' HEIGHT='50'><center><img src='http://186.4.157.125:80/webcapremci/view/images/bcaprem.png' WIDTH='300' HEIGHT='120'/></center></td></tr>
                           </tabla>
                           <p><table rules='all'></p>
                           <tr style='background: #FFFFFF;'><td  WIDTH='1000' align='center'><b> BIENVENIDO A CAPREMCI </b></td></tr></p>
                           <tr style='background: #FFFFFF;'><td  WIDTH='1000' align='justify'>Somos un Fondo Previsional orientado a asegurar el futuro de sus partícipes, prestando servicios complementarios para satisfacer sus necesidades; con infraestructura tecnológica – operativa de vanguardia y talento humano competitivo.</td></tr>
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
                
                
                
                
                
                if(mail("$destino","Comprobante de Retención","$resumen","$cabeceras"))
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
        
        
        
       
        
       
        
        
        $this->view_tesoreria("GenerarRetencion",array(
            "mensaje"=>$mensaje, 
            "error"=> $error
            
            
        ));
        
        
        
        
        
    }
    
    
    
  
    
}

?>