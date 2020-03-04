	<?php

    class ReporteCuentasPagarController extends ControladorBase{
	public function __construct() {
		parent::__construct();
		
	}
	
	public function index5(){
	    
	    session_start();
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        $controladores = new ControladoresModel();
	        $nombre_controladores = "ReporteCuentasPagar";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $controladores->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (!empty($resultPer))
	        {
	            
	            
	            
	            $this->view_tesoreria("ReporteCuentasPagar",array(
	                ""=>""
	            ));
	            
	        }
	        else
	        {
	            $this->view("Error",array(
	                "resultado"=>"No tiene Permisos de Acceso"
	                
	            ));
	            
	        }
	        
	        
	    }
	    else
	    {
	        $error = TRUE;
	        $mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
	        
	        $this->view("Login",array(
	            "resultSet"=>"$mensaje", "error"=>$error
	        ));
	        
	        
	        die();
	        
	    }
	    
	}
	
	
	
		

	

	
	public function ConsultaReporteCuentasPagar(){
	    
	    session_start();
	    
	    
	    $reporte_cuentas_pagar = new ReporteCuentasPagarModel();
	    
	    $where_to="";
	    
	    $columnas  =   "p.id_proveedores,
                        p.nombre_proveedores,
                        p.identificacion_proveedores,
                        p.telefono_proveedores,
                        p.email_proveedores
        ";
	    
	    $tablas =  "proveedores p";
	    
	    $where     = "p.id_proveedores in (SELECT proveedores.id_proveedores FROM public.proveedores, public.tes_cuentas_pagar, public.estado WHERE tes_cuentas_pagar.id_proveedor = proveedores.id_proveedores)";
	    
	    $id        = "p.id_proveedores";
	    
	 //   echo $columnas,$tablas,$where,$id;
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (identificacion_proveedores ILIKE '".$search."%' OR nombre_proveedores ILIKE '".$search."%')";	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$reporte_cuentas_pagar->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$reporte_cuentas_pagar->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_cuentas_pagar' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	
	            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Identificación</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Teléfono</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Correo</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Reporte</th>';
	            
	                
	          
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                
	                $_id_proveedores = $res->id_proveedores;
	                
	                
	                $i++;
	                $html.='<tr>';
	               
	            
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_proveedores.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->identificacion_proveedores.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->telefono_proveedores.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->email_proveedores.'</td>';
	                $html.='<td style="font-size: 15px;">
                            <a data-id="'.$res->id_proveedores.'"   href="#" class="btn btn-default input-sm showpdf" style="font-size:65%;" data-toggle="tooltip" title="Ver pdf"><i class="glyphicon glyphicon-print"></i></a></td>';
	                
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_cuentas_pagar("index.php", $page, $total_pages, $adjacents,"ConsultaReporteCuentasPagar").'';
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

	public function paginate_cuentas_pagar($reload, $page, $tpages, $adjacents, $funcion = "") {
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
	
	
	Public function Reporte_Cuentas_Por_Proveedor(){
	    
	    $proveedores = new ProveedoresModel();
	    $entidades = new EntidadesModel();
	    
	    session_start();
	    
	    
	    $_id_cuentas_pagar = (isset($_GET['id_cuentas_pagar'])) ? $_GET['id_cuentas_pagar'] : null;
	    
	    if(!is_null($_id_lote)){
	        
	        $query = "SELECT id_cuentas_pagar FROM public.tes_cuentas_pagar where id_lote = $_id_lote LIMIT 1";
	        
	        $rs_Cuentas_pagar = $cuentasPagar->enviaquery($query);
	        
	        if(!empty($rs_Cuentas_pagar))
	            $_id_cuentas_pagar = $rs_Cuentas_pagar[0]->id_cuentas_pagar;
	    }
	    
	    if(is_null($_id_cuentas_pagar) ){
	        
	        $this->nodatapdf();
	        
	        exit();
	    }
	    
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
	    
	    $datos_cuentas_pagar = array();
	    
	    $columnascxp = "id_cuentas_pagar, numero_cuentas_pagar, descripcion_cuentas_pagar, fecha_cuentas_pagar,
                  numero_documento_cuentas_pagar, compras_cuentas_pagar, condonaciones_cuentas_pagar,
                  saldo_cuenta_cuentas_pagar, descuento_comercial_cuentas_pagar, flete_cuentas_pagar,
                  miscelaneos_cuentas_pagar,impuesto_cuentas_pagar, cp.id_tipo_documento, td.abreviacion_tipo_documento,
                  lo.id_lote, lo.nombre_lote, lo.descripcion_lote, lo.numero_lote, fre.nombre_frecuencia_lote,
                  cp.id_proveedor, pro.nombre_proveedores, pro.identificacion_proveedores";
	    
	    $tablascxp = "public.tes_cuentas_pagar cp
                INNER JOIN tes_tipo_documento td
                ON cp.id_tipo_documento = td.id_tipo_documento
                INNER JOIN tes_lote lo
                ON lo.id_lote = cp.id_lote
                INNER JOIN proveedores pro
                ON pro.id_proveedores = cp.id_proveedor
                INNER JOIN tes_frecuencia_lote fre
                ON fre.id_frecuencia_lote = lo.id_frecuencia";
	    
	    $wherecxp = "cp.id_cuentas_pagar = $_id_cuentas_pagar ";
	    
	    $idcxp = "cp.id_cuentas_pagar";
	    
	    $rsDatosCxp = $cuentasPagar->getCondiciones($columnascxp, $tablascxp, $wherecxp, $idcxp);
	    
	    if(empty($rsDatosCxp)){
	        
	        $this->nodatapdf("Cuenta x pagar no encontrada");
	        exit();
	    }
	    
	    $datos_cuentas_pagar['NOMBRELOTE'] = $rsDatosCxp[0]->nombre_lote;
	    $datos_cuentas_pagar['DESCLOTE'] = $rsDatosCxp[0]->descripcion_lote;
	    $datos_cuentas_pagar['FRECUENCIA'] = $rsDatosCxp[0]->nombre_frecuencia_lote;
	    $datos_cuentas_pagar['NUMEROLOTE'] = $rsDatosCxp[0]->numero_lote;
	    $datos_cuentas_pagar['TIPODOCUMENTO'] = $rsDatosCxp[0]->abreviacion_tipo_documento;
	    $datos_cuentas_pagar['NUMEROCOMPROBANTE'] = $rsDatosCxp[0]->numero_cuentas_pagar;
	    $datos_cuentas_pagar['NUMERODOCUMENTO'] = $rsDatosCxp[0]->numero_documento_cuentas_pagar;
	    $datos_cuentas_pagar['FECHADOCUMENTO'] = $rsDatosCxp[0]->fecha_cuentas_pagar;
	    $datos_cuentas_pagar['IDEPROVEEDOR'] = $rsDatosCxp[0]->identificacion_proveedores;
	    $datos_cuentas_pagar['NOMBREPROVEEDOR'] = $rsDatosCxp[0]->nombre_proveedores;
	    $datos_cuentas_pagar['CONDONACIONES'] = $rsDatosCxp[0]->condonaciones_cuentas_pagar;
	    $datos_cuentas_pagar['SALDOCUENTA'] =number_format((float)$rsDatosCxp[0]->saldo_cuenta_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['COMPRAS'] = number_format((float)$rsDatosCxp[0]->compras_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['DESCCOMERCIAL'] = number_format((float)$rsDatosCxp[0]->descuento_comercial_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['FLETE'] = number_format((float)$rsDatosCxp[0]->flete_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['MISCELANEOS'] = number_format((float)$rsDatosCxp[0]->miscelaneos_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['IMPUESTO'] = number_format((float)$rsDatosCxp[0]->impuesto_cuentas_pagar, 2, ',', '.');
	    $datos_cuentas_pagar['DF'] = $rsDatosCxp[0]->descripcion_lote;
	    $datos_cuentas_pagar['GH'] = $rsDatosCxp[0]->nombre_frecuencia_lote;
	    $datos_cuentas_pagar['KL'] = $rsDatosCxp[0]->numero_lote;
	    
	    //DISTRIBUCION DE CONTABILIDAD
	    
	    $id_lote = $rsDatosCxp[0]->id_lote;
	    
	    $columnasDistribucion= "id_distribucion_cuentas_pagar, id_lote, pc.id_plan_cuentas, pc.codigo_plan_cuentas,
    		pc.nombre_plan_cuentas, tipo_distribucion_cuentas_pagar,
    		debito_distribucion_cuentas_pagar,  credito_distribucion_cuentas_pagar";
	    
	    $tablasDistribucion = "tes_distribucion_cuentas_pagar dis
            inner join plan_cuentas pc
            on dis.id_plan_cuentas = pc.id_plan_cuentas";
	    
	    $whereDistribucion = " dis.id_lote = $id_lote ";
	    
	    $idDistribucion = " dis.ord_distribucion_cuentas_pagar ";
	    
	    $rsdatosDistribucion = $cuentasPagar->getCondiciones($columnasDistribucion, $tablasDistribucion, $whereDistribucion, $idDistribucion);
	    
	    if( empty($rsdatosDistribucion) ){
	        
	        $this->nodatapdf("Distribucion No Realizada");
	        exit();
	        
	    }
	    
	    if(!empty($rsdatosDistribucion)){
	        
	        $tabladistribucion = "<table class=\"tab3datos\"> <caption> Distribuciones de Contabilidad </caption> ";
	        $sumaDebito = 0.00;
	        $sumaCredito = 0.00;
	        $tabladistribucion .= "<tr>";
	        $tabladistribucion .= "<th>Cuenta</th>";
	        $tabladistribucion .= "<th>Descripción Cuenta</th>";
	        $tabladistribucion .= "<th>Tipo de Cuenta</th>";
	        $tabladistribucion .= "<th>Monto débito</th>";
	        $tabladistribucion .= "<th>Monto crédito</th>";
	        $tabladistribucion .= "</tr>";
	        
	        foreach ($rsdatosDistribucion as $res){
	            $tabladistribucion .= "<tr>";
	            $tabladistribucion .= "<td>".$res->codigo_plan_cuentas."</td>";
	            $tabladistribucion .= "<td>".$res->nombre_plan_cuentas."</td>";
	            $tabladistribucion .= "<td>".$res->tipo_distribucion_cuentas_pagar."</td>";
	            $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$res->debito_distribucion_cuentas_pagar, 2, ',', '.')."</td>";
	            $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$res->credito_distribucion_cuentas_pagar, 2, ',', '.')."</td>";
	            $tabladistribucion .= "</tr>";
	            
	            $sumaCredito += $res->credito_distribucion_cuentas_pagar;
	            $sumaDebito += $res->debito_distribucion_cuentas_pagar;
	        }
	        
	        $tabladistribucion .= "<tr>";
	        $tabladistribucion .= "<td colspan=\"3\"></td>";
	        $tabladistribucion .= "<td class=\"decimales\" >----------------</td>";
	        $tabladistribucion .= "<td class=\"decimales\" >----------------</td>";
	        $tabladistribucion .= "</tr>";
	        
	        $tabladistribucion .= "<tr>";
	        $tabladistribucion .= "<td colspan=\"3\"></td>";
	        $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$sumaDebito, 2, ',', '.')."</td>";
	        $tabladistribucion .= "<td class=\"decimales\" >$ ".number_format((float)$sumaCredito, 2, ',', '.')."</td>";
	        $tabladistribucion .= "</tr>";
	        
	        $tabladistribucion .= "</table>";
	    }
	    
	    $datos_cuentas_pagar['TABLADISTRIBUCION'] = $tabladistribucion;
	    
	    //DISTRIBUCION DETALLE IMPUESTOS
	    
	    $columnasImpuestos= "imp.id_impuestos, imp.nombre_impuestos, id_lote, base_cuentas_pagar_impuestos, valor_cuentas_pagar_impuestos";
	    
	    $tablasImpuestos = "public.tes_cuentas_pagar_impuestos icp
                    INNER JOIN public.tes_impuestos imp
                    ON icp.id_impuestos = imp.id_impuestos";
	    
	    $whereImpuestos = " icp.id_lote = $id_lote ";
	    
	    $idImpuestos = " imp.id_impuestos ";
	    
	    $rsdatosImpuestos = $cuentasPagar->getCondiciones($columnasImpuestos, $tablasImpuestos, $whereImpuestos, $idImpuestos);
	    
	    if(!empty($rsdatosImpuestos)){
	        
	        $tablaImpuesto = "<table class=\"tab3datos\"> <caption> Distribuciones de detalle de impuestos </caption> ";
	        $sumaImpuesto = 0.00;
	        $tablaImpuesto .= "<tr>";
	        $tablaImpuesto .= "<th>Id. detalle impuesto</th>";
	        $tablaImpuesto .= "<th>Descripción detalle impuesto</th>";
	        $tablaImpuesto .= "<th>Monto impuesto</th>";
	        $tablaImpuesto .= "</tr>";
	        
	        foreach ($rsdatosImpuestos as $res){
	            $tablaImpuesto .= "<tr>";
	            $tablaImpuesto .= "<td>".$res->id_impuestos."</td>";
	            $tablaImpuesto .= "<td>".$res->nombre_impuestos."</td>";
	            $tablaImpuesto .= "<td class=\"decimales\" >$ ".number_format((float)$res->valor_cuentas_pagar_impuestos, 2, ',', '.')."</td>";
	            $tablaImpuesto .= "</tr>";
	            
	            $sumaImpuesto += $res->valor_cuentas_pagar_impuestos;
	        }
	        
	        $tablaImpuesto .= "<tr>";
	        $tablaImpuesto .= "<td colspan=\"2\"></td>";
	        $tablaImpuesto .= "<td class=\"decimales\" >----------------</td>";
	        $tablaImpuesto .= "</tr>";
	        
	        $tablaImpuesto .= "<tr>";
	        $tablaImpuesto .= "<td colspan=\"2\"></td>";
	        $tablaImpuesto .= "<td class=\"decimales\" >$ ".number_format((float)$sumaImpuesto, 2, ',', '.')."</td>";
	        $tablaImpuesto .= "</tr>";
	        
	        $tablaImpuesto .= "</table>";
	    }
	    
	    $datos_cuentas_pagar['TABLAIMPUESTOS'] = $tablaImpuesto;
	    
	    
	    $this->verReporte("CuentasPagar", array('datos_cuentas_pagar'=>$datos_cuentas_pagar,'datos_empresa'=>$datos_empresa,'datos_cabecera'=>$datos_cabecera));
	    
	}
    }
    
    
    
    
    ?>