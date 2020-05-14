<?php

class PrincipalSuperavitController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
        session_start();
        
        $busquedas = new PrincipalPrestamosModel();
        
        if( empty( $_SESSION['usuario_usuarios'] ) ){
            $this->redirect("Usuarios","sesion_caducada");
            exit();
        }
        
        $nombre_controladores = "PrincipalSuperavit";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $busquedas->getPermisosVer(" controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        if (empty($resultPer)){
            
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos de Acceso"
                
            ));
            exit();
        }
        
        
        $this->view_principal("PrincipalBusquedas");
        
    }
   
    public function CargaSuperavit(){
        
        $busquedas = new PrincipalSuperavitModel();
        $resp  = null;
        
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
        
        //varaibles de parametros de busqueda
        $cedula    = ( isset( $_POST['cedula'] ) ) ? $_POST['cedula'] : "";
        $codigo    = ( isset( $_POST['codigo'] ) ) ? $_POST['codigo'] : "";
        
        
        $columnas1 = "
                  core_participes.id_participes,
                  core_participes.cedula_participes, 
                  core_participes.nombre_participes, 
                  core_participes.apellido_participes, 
                  core_estado_participes.id_estado_participes, 
                  core_estado_participes.nombre_estado_participes, 
                  core_superavit_pagos_trabajados.documento_numero_superavit_pagos_trabajados, 
                  core_superavit_pagos_trabajados.tiene_credito_superavit_pagos_trabajados, 
                  core_superavit_pagos_trabajados.cruce_credito_superavit_pagos_trabajados, 
                  core_superavit_pagos.valor_pagar_superavit_pagos, 
                  core_superavit_estados.id_superavit_estados, 
                  core_superavit_estados.nombre_superavit_estados";
        $tablas1   = "public.core_participes, 
                  public.core_superavit_pagos, 
                  public.core_estado_participes, 
                  public.core_superavit_pagos_trabajados, 
                  public.core_superavit_estados";
        $where1    = "core_participes.id_estado_participes = core_estado_participes.id_estado_participes AND
                  core_superavit_pagos.id_participes = core_participes.id_participes AND
                  core_superavit_pagos_trabajados.id_superavit_pagos = core_superavit_pagos.id_superavit_pagos AND
                  core_superavit_pagos_trabajados.id_superavit_estados = core_superavit_estados.id_superavit_estados";
        $id1       = "core_participes.nombre_participes";
        
        
        
        if( strlen( trim( $cedula ) ) > 0 ){
            
            $where1   .= " AND core_participes.cedula_participes LIKE '$cedula%' ";
        }
        
        if( strlen( trim( $codigo ) ) > 0 ){
            
            $where1    .= " AND core_superavit_pagos_trabajados.documento_numero_superavit_pagos_trabajados ILIKE '%$codigo%' ";
        }
        
       
        
        $colCantidad = " COUNT(1) AS cantidad " ;
        $resultSet = $busquedas->getCondicionesSinOrden( $colCantidad , $tablas1, $where1,"");
        $cantidadResult=(int)$resultSet[0]->cantidad;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet = $busquedas->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
        $total_pages = ceil($cantidadResult/$per_page);
        
        $error = error_get_last();
        if( !empty($error) ){
            echo $error['message'];
            exit();
        }
        
        $htmlTr = "";
        $i = 0;
        
        $htmlHead = "";
        $htmlHead.= "<table id='tabla_registros_tres_cuotas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
        $htmlHead.= "<thead>";
        $htmlHead.= "<tr>";
        $htmlHead.='<th style="text-align: left;  font-size: 12px;">#</th>';
        $htmlHead.='<th style="text-align: left;  font-size: 12px;">CI</th>';
        $htmlHead.='<th style="text-align: left;  font-size: 12px;">Afiliado</th>';
        $htmlHead.='<th style="text-align: left;  font-size: 12px;">Estado Socio</th>';
        $htmlHead.='<th style="text-align: left;  font-size: 12px;">Créditos Activos</th>';
        $htmlHead.='<th style="text-align: left;  font-size: 12px;">Cruzo Créditos</th>';
        $htmlHead.='<th style="text-align: left;  font-size: 12px;">Valor</th>';
        $htmlHead.='<th style="text-align: left;  font-size: 12px;">Estado Proceso</th>';
        
        $htmlHead.='<th style="text-align: left;  font-size: 12px;">Acta de Liquidacion</th>';
        $htmlHead.='<th style="text-align: left;  font-size: 12px;">Hoja de Liquidacion</th>';
        
        $htmlHead.='</tr>';
        $htmlHead.='</thead>';
        
        
        //para los datos de la tabla
        $htmlBody = "<tbody>";
        foreach ($resultSet as $res){
            
            $tienecredito=$res->tiene_credito_superavit_pagos_trabajados;
            $cruceCredito=$res->cruce_credito_superavit_pagos_trabajados;
            
            
            if($tienecredito=="f"){
                
                $tienecredito="NO";
                
            }
            
            
            if($cruceCredito=="f"){
                
                $cruceCredito="NO";
                
            }
            
            $i++;
            $htmlBody.='<tr>';
            $htmlBody.='<td style="font-size: 11px;">'.$i.'</td>';
            $htmlBody.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
            $htmlBody.='<td style="font-size: 11px;">'.$res->apellido_participes." ".$res->nombre_participes.'</td>';
            $htmlBody.='<td style="font-size: 11px;">'.$res->nombre_estado_participes.'</td>';
            $htmlBody.='<td style="font-size: 11px;" align="center">'.$tienecredito.'</td>';
            $htmlBody.='<td style="font-size: 11px;" align="center">'.$cruceCredito.'</td>';
            $htmlBody.='<td style="font-size: 11px;" align="right">'.$res->valor_pagar_superavit_pagos.'</td>';
            $htmlBody.='<td style="font-size: 11px;">'.$res->nombre_superavit_estados.'</td>';
           
            $htmlBody.='<td style="color:#000000;font-size:80%;" align="center"><span class="pull-right"><a href="index.php?controller=PrincipalSuperavit&action=reporte_acta_liquidacion&id_participes='.$res->id_participes.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
            $htmlBody.='<td style="color:#000000;font-size:80%;" align="center"><span class="pull-right"><a href="index.php?controller=PrincipalSuperavit&action=reporte_hoja_liquidacion&id_participes='.$res->id_participes.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
            
            
            
            $htmlBody.='</tr>';
            
            
        }
        
        $htmlBody .= "</tbody>";
        
        $htmlFoot = "<tfoot>";
        $htmlFoot .= "<tr>";
        $htmlFoot .= "<th colspan=\"3\"></th>";
        $htmlFoot .= "</tr>";
        $htmlFoot .= "</tfoot>";
        
        $resp['tabla'] = $htmlHead.$htmlBody.$htmlFoot;
        
        $resp['filas'] = $htmlTr;
        $htmlHead.='</table>';
        $htmlPaginacion  = '<div class="table-pagination pull-right">';
        $htmlPaginacion .= ''. $busquedas->allpaginate("index.php", $page, $total_pages, $adjacents,"loadBusquedaSuperavit").'';
        $htmlPaginacion .= '</div>';
        
        $resp['paginacion'] = $htmlPaginacion;
        $resp['cantidadDatos'] = $cantidadResult;
        
        echo json_encode( $resp );
    }
    
    
    
    public function reporte_acta_liquidacion(){
        session_start();
        
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
        
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
        
        
        
        
        
        
        $productos = new ProductosModel();
        $id_productos =  (isset($_REQUEST['id_productos'])&& $_REQUEST['id_productos'] !=NULL)?$_REQUEST['id_productos']:'';
        
        $datos_reporte = array();
        
        $columnas = "   productos.id_productos,
                      productos.codigo_productos,
                      productos.marca_productos,
                      productos.nombre_productos";
        
        $tablas = "   public.productos";
        $where= "  productos.id_productos='$id_productos'";
        $id="productos.id_productos";
        
        $rsdatos = $productos->getCondiciones($columnas, $tablas, $where, $id);
        
        
        $datos_reporte['NOMBRE_PRODUCTOS']=$rsdatos[0]->nombre_productos;
        $datos_reporte['MARCA_PRODUCTOS']=$rsdatos[0]->marca_productos;
        $datos_reporte['CODIGO_PRODUCTO']=$rsdatos[0]->codigo_productos;
        
        
        
        $columnas = " productos.id_productos,
                      productos.codigo_productos,
                      productos.marca_productos,
                      productos.nombre_productos,
                      productos.ult_precio_productos,
                      productos.minimo_productos,
                      saldo_productos.entradas_f_saldo_productos,
                      saldo_productos.salidas_f_saldo_productos,
                      saldo_productos.saldos_f_saldo_productos";
        
        $tablas = "  public.productos,
                    public.saldo_productos";
        $where= "saldo_productos.id_productos = productos.id_productos AND productos.id_productos='$id_productos'";
        $id="productos.id_productos";
        
        $productos_detalle = $productos->getCondiciones($columnas, $tablas, $where, $id);
        
        $html='';
        
        
        $html.='<table class="12" style="width:98px;" border=1>';
        $html.='<tr>';
        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Entrada</th>';
        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Precio</th>';
        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Salida</th>';
        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Stock</th>';
        
        $html.='</tr>';
        
        
        
        
        foreach ($productos_detalle as $res)
        {
            
            
            $html.='<tr >';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;" align="center">'.intval($res->entradas_f_saldo_productos).'</td>';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;" align="right">'.$res->ult_precio_productos.'</td>';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;" align="center">'.intval($res->salidas_f_saldo_productos).'</td>';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;" align="center">'.intval($res->saldos_f_saldo_productos).'</td>';
            
            
            
            $html.='</td>';
            $html.='</tr>';
        }
        
        $html.='</table>';
        
        $datos_reporte['TABLA_MOVIMIENTOS']= $html;
        
        
        
        
        
        
        
        
        
        $this->verReporte("ReporteActaLiquidcion", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte ));
        
        
        
    }
    
    
    
    
    
    /************************************************************** FUNCIONES AUXILIARES DEL CONTROLADOR *************************************/
    /***
     *
     * @param string $fecha
     * @return array con fechaini fecha inicio -- fechafin fecha fin de busqueda
     * @exception si hay error se enviar null
     */
    function devuelveFecha($fecha){
        
        $resp = null;
        $afecha  = explode("-", $fecha);
        if( sizeof( $afecha ) != 2 ){
            return null;
        }
        
        $afechaini = explode("/", trim( $afecha[0] ) );
        $afechafin = explode("/", trim( $afecha[1] ) );
        
        if( sizeof( $afechaini ) != 3 || sizeof( $afechafin ) != 3 ){
            return null;
        }
        
        try {
            
            $dateini = new DateTime( trim( $afecha[0] ) );
            $datefin = new DateTime( trim( $afecha[1] ) );
            
            if( !empty( error_get_last() ) ){
                echo 'llego aqui';
                throw new Exception();
            }
            
            $resp['fechaini'] = $dateini->format('Y-m-d');
            $resp['fechafin'] = $datefin->format('Y-m-d');
            
        } catch (Exception $e) {
            error_clear_last();
            $resp = null;
        }
        
        return $resp;
        
    }
    
    /***
     * @desc funcion para dar un formato a la fecha que viene de Bd
     * @param $fechaString
     * @param $formato
     * @exception return vacio
     */
    function pgFormatFecha($StringFecha,$FormatFecha = "Y-m-d"){
        $strFecha = "";
        try {
            $ObjetoFecha = new DateTime($StringFecha);
            $strFecha = $ObjetoFecha->format($FormatFecha);
        } catch (Exception $e) {
            error_clear_last(); //limpia el error generado
            $strFecha;
        }
        return $strFecha;
    }
}
?>
