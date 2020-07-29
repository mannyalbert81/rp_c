<?php

class PagosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        
        $entidad = new CoreEntidadPatronalModel();
        
        session_start();
        
        if( empty( $_SESSION['usuario_usuarios'] ) )
        {            
            $this->redirect("Usuarios","sesion_caducada");
            exit();
        }else
        {            
            $nombre_controladores = "PagosCXP";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $entidad->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );            
            if ( empty( $resultPer ) )
            {                
                $this->view("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso Pagos"
                    
                ));
                exit();
                
            }else
            {                
                $rsEntidad = $entidad->getBy(" 1 = 1 ");                
                
                $this->view_tesoreria("Pagos",array(
                    "resultSet"=>$rsEntidad
                    
                ));
                exit();                
            }
            
        }
        
        
    }
       
    function validaMetodoPago(){
        
        $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
        
    }
    
    public function ReporteIndex(){
        
        $pagos = new PagosModel();
        
        session_start();
        
        if(empty( $_SESSION)){
            
            $this->redirect("Usuarios","sesion_caducada");
            return;
        }
        
        $nombre_controladores = "cxpAplicadas";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $pagos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (empty($resultPer)){
            
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos de Acceso Reporte de Cuentas Pagar"
                
            ));
            exit();
        }
        
        $this->view_tesoreria("CuentasPagarAplicadas",array( ));
        
    }
    
    public function dtListarCuentasPagarAplicadas()
    {
        if( !isset( $_SESSION ) ){
            session_start();
        }
        
        try{
            
            ob_start();
            
            $cpagar = new CuentasPagarModel();
            
            //dato que viene de parte del plugin DataTable
            $requestData = $_REQUEST;
            $searchDataTable   = $requestData['search']['value'];
            
            $buscador  = $_POST['input_search'];
            
            /** buscar por el usuario que se encuentra logueado */
            //$_usuario_logueado = $_SESSION['usuario_usuarios'];
            
            $columnas1 = " aa.id_pagos, aa.fecha_pagos, aa.usuario_usuarios, cc.identificacion_proveedores, cc.razon_social_proveedores, aa.metodo_pagos, aa.valor_pagos,
                bb.concepto_ccomprobantes, dd.id_bancos, dd.nombre_bancos, cc.nombre_proveedores, aa.id_ccomprobantes, aa.id_cuentas_pagar ";
            $tablas1   = " tes_pagos aa
                INNER JOIN ccomprobantes bb ON bb.id_ccomprobantes = aa.id_ccomprobantes
                INNER JOIN proveedores cc ON cc.id_proveedores = aa.id_proveedores
                LEFT JOIN tes_bancos dd on dd.id_bancos = aa.id_bancos";
            $where1    = "1 = 1 ";       
            
            //aqui poner para filtrar si es por session
            
            /* PARA FILTROS DE CONSULTA */
            if( strlen( $searchDataTable ) > 0 ){
                              
                $where1    .= " AND ( cc.identificacion_proveedores = '$searchDataTable' ";
                $where1    .= " OR cc.razon_social_proveedores ILIKE '%$searchDataTable%' ";
                $where1    .= " OR cc.nombre_proveedores ILIKE '%$searchDataTable%' ";
                $where1    .= " OR TO_CHAR( aa.fecha_pagos, 'YYYY-MM-DD') ILIKE '%$searchDataTable%' ";
                //$where1    .= " OR aa.numero_documento_cuentas_pagar = '$searchDataTable' "; incluid para cuentas pagar
                $where1    .= " OR aa.metodo_pagos ILIKE '$searchDataTable%' ";
                $where1    .= " OR aa.usuario_usuarios ILIKE '$searchDataTable%' ";
                $where1    .= " OR dd.nombre_bancos ILIKE '$searchDataTable%' ";
                $where1    .= " OR bb.concepto_ccomprobantes ILIKE '$searchDataTable%' ";
                
                $where1    .= " ) ";
            
            }
            
            $rsCantidad    = $cpagar->getCantidad("*", $tablas1, $where1);
            $cantidadBusqueda = (int)$rsCantidad[0]->total;
            
            /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
            
            // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
            $columns = array(
                0 => 'aa.id_pagos',
                1 => 'aa.fecha_pagos',
                2 => 'aa.usuario_usuarios',
                3 => 'cc.identificacion_proveedores',
                4 => 'cc.razon_social_proveedores',
                5 => 'aa.metodo_pagos',
                6 => '1',
                7 => 'aa.valor_pagos',
                8 => 'bb.concepto_ccomprobantes'
            );
            
            $orderby   = $columns[$requestData['order'][0]['column']];
            $orderdir  = $requestData['order'][0]['dir'];
            $orderdir  = strtoupper($orderdir);
            /**PAGINACION QUE VIEN DESDE DATATABLE**/
            $per_page  = $requestData['length'];
            $offset    = $requestData['start'];
            
            //para validar que consulte todos
            $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
            
            $limit = " ORDER BY $orderby $orderdir LIMIT $per_page OFFSET '$offset'";
                        
            $resultSet = $cpagar->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
            
            //$sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
            $sql = "";
            //$cantidadBusquedaFiltrada = sizeof($resultSet);
            
            /** crear el array data que contiene columnas en plugins **/
            $data = array();
            $dataFila = array();
            $columnIndex = 0;
            foreach ( $resultSet as $res){
                $columnIndex++;
                
                $metodo_pago = $res->metodo_pagos;
                $banco_beneficiario = $res->nombre_bancos;                
                $opcionesCheque = "";
                $id_pagos = $res->id_pagos;
                
                if( $metodo_pago == "CHEQUE" )
                {
                    
                    $banco_beneficiario = "";                    
                    $opcionesCheque = '<span >
                                <a class="btn btn-default input-sm showpdfcheque" data-id_pagos ="'.$id_pagos.'"  data-toogle="tooltip"  href="#" title="Cheque"> <i class="fa fa-file-pdf-o" aria-hidden="true" ></i></a>
                            </span>';
                }
                
                $nombre_benficiario = $res->razon_social_proveedores;
                if( !strlen( $nombre_benficiario ) )
                {
                    $nombre_benficiario = $res->nombre_proveedores;
                }
                
                
               /* $opciones = ""; //variable donde guardare los datos creados automaticamente
                $opciones = '<div class="pull-right ">
                            <span >
                                <a class="btn btn-default input-sm showpdf" data-id="'.$res->id_ccomprobantes.'" data-toogle="tooltip"  href="#" title="Comprobante"> <i class="fa fa-file-pdf-o" aria-hidden="true" ></i></a>
                            </span>'.
                            $opcionesCheque.'
                        </div>';*/
                
                
                $opciones = ""; //variable donde guardare los datos creados automaticamente
                $opciones = '<span >
                                <a class="btn btn-default input-sm showpdf" data-id="'.$res->id_ccomprobantes.'" data-toogle="tooltip"  href="#" title="Comprobante"> <i class="fa fa-file-pdf-o" aria-hidden="true" ></i></a>
                            </span>';
                
                                                
                $dataFila['numfila']    = $columnIndex;
                $dataFila['fecha']      = $res->fecha_pagos;
                $dataFila['usuario']    = $res->usuario_usuarios;
                $dataFila['identificacion_bene']   = $res->identificacion_proveedores;
                $dataFila['nombre_bene']           = $nombre_benficiario;
                $dataFila['metodo_pago']   = $metodo_pago;
                $dataFila['banco_bene']    = $banco_beneficiario;
                $dataFila['valor_pago']    = $res->valor_pagos;
                $dataFila['descripcion']        = $res->concepto_ccomprobantes;
                $dataFila['opciones']           = $opciones; //esta comentado hast definir que opciones darle al reporte de cuentas pagar aplicadas
                $dataFila['cheque']           = $opcionesCheque;
                //$dataFila['id_cabeza']         = '12345';
                
                $data[] = $dataFila;               
                
                
            }
            
            
            $salida = ob_get_clean();
            
            if( !empty($salida) )
                throw new Exception($salida);
                
                $json_data = array(
                    "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                    "recordsTotal" => intval($cantidadBusqueda),  // total number of records
                    "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
                    "data" => $data,   // total data array
                    "sql" => $sql
                );
                
        } catch (Exception $e) {
            
            $json_data = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval("0"),  // total number of records
                "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => array(),   // total data array
                "sql" => "",
                "buffer" => error_get_last(),
                "ERRORDATATABLE" => $e->getMessage()
            );
        }
        
        
        echo json_encode($json_data);
        
        
    }
    
    public function dtListarCuentasPagarPendientes()
    {
        if( !isset( $_SESSION ) )
        {
            session_start();
        }
        
        try{
            
            ob_start();
            
            $cpagar = new CuentasPagarModel();
            
            //dato que viene de parte del plugin DataTable
            $requestData = $_REQUEST;
            $searchDataTable   = $requestData['search']['value'];            
            
            /** buscar por el usuario que se encuentra logueado */
            //$_usuario_logueado = $_SESSION['usuario_usuarios'];
            
            $columnas1 = " aa.id_cuentas_pagar, aa.descripcion_cuentas_pagar, aa.fecha_cuentas_pagar, aa.origen_cuentas_pagar, 
                aa.total_cuentas_pagar, aa.saldo_cuenta_cuentas_pagar, bb.id_lote, bb.nombre_lote, cc.id_proveedores,
                cc.nombre_proveedores, cc.identificacion_proveedores, ee.id_usuarios, ee.nombre_usuarios, ff.id_forma_pago, 
                ff.nombre_forma_pago, cc.razon_social_proveedores, ee.usuario_usuarios ";
            $tablas1   = " tes_cuentas_pagar aa
                INNER JOIN tes_lote bb ON aa.id_lote = bb.id_lote
                INNER JOIN proveedores cc ON aa.id_proveedor = cc.id_proveedores
                INNER JOIN estado dd ON aa.id_estado = dd.id_estado
                INNER JOIN usuarios ee ON bb.id_usuarios = ee.id_usuarios
                LEFT JOIN forma_pago ff ON aa.id_forma_pago = ff.id_forma_pago";
            $where1    = " dd.nombre_estado in ('GENERADO','PARCIAL') AND aa.origen_cuentas_pagar = 'MANUAL' ";
           
            //aqui poner para filtrar si es por session
            
            /* PARA FILTROS DE CONSULTA */
            if( strlen( $searchDataTable ) > 0 ){
                
                $where1    .= " AND ( cc.identificacion_proveedores = '$searchDataTable' ";
                $where1    .= " OR cc.razon_social_proveedores ILIKE '%$searchDataTable%' ";
                $where1    .= " OR cc.nombre_proveedores ILIKE '%$searchDataTable%' ";
                $where1    .= " OR TO_CHAR( aa.fecha_cuentas_pagar, 'YYYY-MM-DD') ILIKE '%$searchDataTable%' ";
                $where1    .= " OR aa.numero_documento_cuentas_pagar ILIKE '%$searchDataTable%' "; 
                $where1    .= " OR ee.usuario_usuarios ILIKE '$searchDataTable%' ";
                $where1    .= " OR aa.descripcion_cuentas_pagar ILIKE '$searchDataTable%' ";                
                $where1    .= " ) ";
                
            }
            
            $rsCantidad    = $cpagar->getCantidad("*", $tablas1, $where1);
            $cantidadBusqueda = (int)$rsCantidad[0]->total;
            
            /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
            
            // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
            $columns = array(
                0 => 'aa.id_cuentas_pagar',
                1 => 'aa.id_cuentas_pagar',
                2 => 'aa.id_cuentas_pagar',
                3 => 'ee.usuario_usuarios',
                4 => 'aa.id_cuentas_pagar',
                5 => 'aa.fecha_cuentas_pagar',
                6 => 'cc.nombre_proveedores',
                7 => 'aa.total_cuentas_pagar',
                8 => 'aa.id_cuentas_pagar',
                9 => 'aa.id_cuentas_pagar'
            );
            
            $orderby   = $columns[$requestData['order'][0]['column']];
            $orderdir  = $requestData['order'][0]['dir'];
            $orderdir  = strtoupper($orderdir);
            /**PAGINACION QUE VIEN DESDE DATATABLE**/
            $per_page  = $requestData['length'];
            $offset    = $requestData['start'];
            
            //para validar que consulte todos
            $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
            
            $limit = " ORDER BY $orderby $orderdir LIMIT $per_page OFFSET '$offset'";
            
            $resultSet = $cpagar->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
            
            //$sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
            $sql = "";
            
            /** crear el array data que contiene columnas en plugins **/
            $data = array();
            $dataFila = array();
            $columnIndex = 0;
            foreach ( $resultSet as $res){
                $columnIndex++;
                
                $htmlcheque = '';
                $htmltransferencia  = '';
                
                if( $res->id_forma_pago == null || $res->id_forma_pago == "" )
                {
                    $htmlcheque   .= '<span >
                    <a  id=""  href="index.php?controller=GenerarCheque&action=indexCheque&id_cuentas_pagar='.$res->id_cuentas_pagar.'" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Generar Cheque"> <i class="fa fa-cc-diners-club fa-2x fa-fw" aria-hidden="true" ></i>
                    </a> </span>';
                    
                    $htmltransferencia   .= '<span >
                    <a  id=""  href="index.php?controller=Transferencias&action=index&id_cuentas_pagar='.$res->id_cuentas_pagar.'" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Realizar Transferencia"> <i class="fa fa-exchange fa-2x fa-fw" aria-hidden="true" ></i>
                    </a> </span>'; 
                   
                }else
                {                    
                    if($res->nombre_forma_pago != 'TRANSFERENCIA')
                    {                        
                        $htmlcheque   .= '<span >
                    <a  id=""  href="index.php?controller=GenerarCheque&action=indexCheque&id_cuentas_pagar='.$res->id_cuentas_pagar.'" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Generar Cheque"> <i class="fa fa-cc-diners-club fa-2x fa-fw" aria-hidden="true" ></i>
                    </a> </span>';
                    }elseif ( $res->nombre_forma_pago == 'TRANSFERENCIA' )
                    {
                        $htmltransferencia   .= '<span >
                    <a  id=""  href="index.php?controller=Transferencias&action=index&id_cuentas_pagar='.$res->id_cuentas_pagar.'" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Realizar Transferencia"> <i class="fa fa-exchange fa-2x fa-fw" aria-hidden="true" ></i>
                    </a> </span>'; 
                    }else
                    {
                        //no action in this 
                    }
                }
                
                $nombre_benficiario = $res->razon_social_proveedores;
                if( !strlen( $nombre_benficiario ) )
                {
                    $nombre_benficiario = $res->nombre_proveedores;
                }
                                
                $dataFila['numfila']   = $columnIndex;
                $dataFila['lote']      = $res->nombre_lote;
                $dataFila['origen']    = $res->origen_cuentas_pagar;
                $dataFila['generado_por']   = $res->usuario_usuarios;
                $dataFila['descripcion']    = $res->descripcion_cuentas_pagar;
                $dataFila['fecha']     = $res->fecha_cuentas_pagar;
                $dataFila['beneficiario']   = $nombre_benficiario;
                $dataFila['valor_documento']    = $res->total_cuentas_pagar;
                $dataFila['saldo_documento']    = $res->saldo_cuenta_cuentas_pagar;
                $dataFila['cheque']           = $htmlcheque;   
                $dataFila['transferencia']    = $htmltransferencia;
                               
                $data[] = $dataFila;                
                
            }            
            
            $salida = ob_get_clean();
            
            if( !empty($salida) )
                throw new Exception($salida);
                
                $json_data = array(
                    "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                    "recordsTotal" => intval($cantidadBusqueda),  // total number of records
                    "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
                    "data" => $data,   // total data array
                    "sql" => $sql
                );
                
        } catch (Exception $e) {
            
            $json_data = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval("0"),  // total number of records
                "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => array(),   // total data array
                "sql" => "",
                "buffer" => error_get_last(),
                "ERRORDATATABLE" => $e->getMessage()
            );
        }
        
        
        echo json_encode($json_data);
        
        
    }
   
    
   
}
    

?>