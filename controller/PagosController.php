<?php

class PagosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        
        $entidad = new CoreEntidadPatronalModel();
        
        session_start();
        
        if(empty( $_SESSION['usuario_usuarios'])){
            
            $this->redirect("Usuarios","sesion_caducada");
            exit();
        }else{
            
            $nombre_controladores = "PagosCXP";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $entidad->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );            
            if (empty($resultPer)){
                
                $this->view("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso Pagos"
                    
                ));
                exit();
                
            }else{
                
                $rsEntidad = $entidad->getBy(" 1 = 1 ");                
                
                $this->view_tesoreria("Pagos",array(
                    "resultSet"=>$rsEntidad
                    
                ));
                exit();                
            }
            
        }
        
        
    }
    
    public function indexconsulta(){
        
        
        $busqueda = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : "";
        if(!isset($_POST['peticion'])){
            echo 'sin conexion';
            return;
        }
        
        $page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
        
        $cuentasPagar = new CuentasPagarModel();
        
        $columnas = "aa.id_cuentas_pagar, aa.descripcion_cuentas_pagar,aa.fecha_cuentas_pagar, aa.origen_cuentas_pagar, aa.total_cuentas_pagar, aa.saldo_cuenta_cuentas_pagar,
                	bb.id_lote, bb.nombre_lote, cc.id_proveedores,cc.nombre_proveedores, cc.identificacion_proveedores, ee.id_usuarios, ee.nombre_usuarios,
                	ff.id_forma_pago, ff.nombre_forma_pago";
        
        $tablas = "tes_cuentas_pagar aa
                INNER JOIN tes_lote bb
                ON aa.id_lote = bb.id_lote
                INNER JOIN proveedores cc
                ON aa.id_proveedor = cc.id_proveedores
                INNER JOIN estado dd
                ON aa.id_estado = dd.id_estado
                INNER JOIN usuarios ee
                ON bb.id_usuarios = ee.id_usuarios
                LEFT JOIN forma_pago ff
                ON aa.id_forma_pago = ff.id_forma_pago";
        
        $where = " 1=1 AND dd.nombre_estado = 'GENERADO' AND aa.origen_cuentas_pagar = 'MANUAL'";
        
        //para los parametros de where 
        if(!empty($busqueda)){
            
            $where .= "AND ( bb.nombre_lote = '$busqueda' OR cc.identificacion_proveedores like '$busqueda%' )";
        }
        
        $id = "aa.id_cuentas_pagar";
        
        //para obtener cantidad         
        $rsResultado = $cuentasPagar->getCantidad("1", $tablas, $where, $id);        
        
        $cantidad = 0;
        $html = "";
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        if(!is_null($rsResultado) && !empty($rsResultado) && count($rsResultado)>0){
            $cantidad = $rsResultado[0]->total;
        }
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet = $cuentasPagar->getCondicionesPagDesc( $columnas, $tablas, $where, $id, $limit);
        
        $tpages = ceil($cantidad/$per_page);
        $_nombre_tabla = "tbl_lista_cuentas_pagar";
        
        if( $cantidad > 0 ){
           
            $html.= "<table id='$_nombre_tabla' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">LOTE</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">ORIGEN</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">GENERADO POR</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">DESCRIPCION</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">FECHA</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">BENEFICIARIO</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">MONTO/VALOR</th>';
            $html.='<th >CHEQUE</th>';
            $html.='<th >TRANSFERENCIA</th>';            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            $i=0;
            
            //print_r($resultSet); die();
            
            foreach ($resultSet as $res)
            {
                $i++;
                $html.='<tr>';
                $html.='<td style="font-size: 11px;">'.$i.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_lote.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->origen_cuentas_pagar.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->descripcion_cuentas_pagar.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->fecha_cuentas_pagar.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_proveedores.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->saldo_cuenta_cuentas_pagar.'</td>';
                
                if($res->id_forma_pago == null || $res->id_forma_pago == "" ){
                    $html.='<td width="3%" style="font-size:80%;">';
                    $html.='<a class="btn btn-sm btn-info" title="Generar Cheque" href="index.php?controller=GenerarCheque&action=indexCheque&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                    $html.='<i class="fa fa-money"></i></a></td>';
                    $html.='<td width="3%" style="font-size:80%;">';
                    $html.='<a class="btn btn-sm btn-info" title="Realizar Transferencia"  href="index.php?controller=Transferencias&action=index&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                    $html.='<i class="glyphicon glyphicon-transfer"></i></a></td>';
                }else{
                
                    if($res->nombre_forma_pago != 'TRANSFERENCIA'){
                        
                        $html.='<td width="3%" style="font-size:80%;">';
                        $html.='<a class="btn btn-sm btn-info" title="Generar Cheque" href="index.php?controller=GenerarCheque&action=indexCheque&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                        $html.='<i class="fa fa-money"></i></a></td>';
                    }else{
                        $html.='<td width="3%" ></td>';
                    }
                    
                    if($res->nombre_forma_pago == 'TRANSFERENCIA'){
                        
                        $html.='<td width="3%" style="font-size:80%;">';
                        $html.='<a class="btn btn-sm btn-info" title="Realizar Transferencia"  href="index.php?controller=Transferencias&action=index&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                        $html.='<i class="glyphicon glyphicon-transfer"></i></a></td>';
                    }else{
                        $html.='<td width="3%" ></td>';
                    }
                }
                
                $html.='</tr>';
                
            }
            
            
            $html.='</tbody>';
            $html.='</table>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate("index.php", $page, $tpages, $adjacents,"buscaCuentasPagar").'';
            $html.='</div>';
            
            
            
        }else{
            
            $html.= "<table id='$_nombre_tabla' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">LOTE</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">ORIGEN</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">GENERADO POR</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">DESCRIPCION</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">FECHA</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">BENEFICIARIO</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">MONTO/VALOR</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">CHEQUE</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">TRANSFERENCIA</th>'; 
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $html.='</tbody>';
            $html.='</table>';
        }
        
        //array de datos
        $respuesta = array();
        $respuesta['tabla_datos'] = $html;
        $respuesta['valores'] = array('cantidad'=>$cantidad);
        $respuesta['nombre_tabla'] = $_nombre_tabla;
        echo json_encode($respuesta);
    }
    
    public function paginate($reload, $page, $tpages, $adjacents, $funcion = "") {
        
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
                $where1    .= " OR aa.metodo_pagos = '$searchDataTable%' ";
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
            
            $limit = " ORDER BY $orderby $orderdir LIMIT   '$per_page' OFFSET '$offset'";
                        
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
                $id_cuentas_pagar = 0;
                $id_comprobante_cheque  = 0;
                $opcionesCheque = "";
                if( $metodo_pago == "CHEQUE" )
                {
                    
                    $banco_beneficiario = "";
                    $id_cuentas_pagar   = $res->id_cuentas_pagar;
                    
                    $col    = " id_ccomprobantes";
                    $tab    = " public.tes_cuentas_pagar";
                    $whe    = " id_cuentas_pagar = $id_cuentas_pagar";
                    $rsConsulta = $cpagar->getCondicionesSinOrden( $col, $tab, $whe, "" );
                    
                    $id_comprobante_cheque  = $res->id_ccomprobantes;
                    
                    $opcionesCheque = '<span >
                                <a class="btn btn-default input-sm showpdfcheque" data-id_ccomprobantes ="'.$id_comprobante_cheque.'" data-id_cuentas_pagar ="'.$id_cuentas_pagar.'" data-toogle="tooltip"  href="#" title="Cheque"> <i class="fa fa-file-pdf-o" aria-hidden="true" ></i></a>
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
   
    
   
}
    

?>