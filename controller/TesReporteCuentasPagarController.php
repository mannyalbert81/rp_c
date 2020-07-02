<?php

class TesReporteCuentasPagarController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	public function Index(){
	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    session_start();
	    
	    if(empty( $_SESSION)){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        return;
	    }
	    
	    $nombre_controladores = "ReportesTesoreria";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $cuentasPagar->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso Reporte de Cuentas Pagar"
	            
	        ));
	        exit();
	    }
	    
	    $this->view_tesoreria("CuentasPagar",array( ));
	    
	}
	
	public function dtListarCuentasPagar()
	{   
	    if( !isset( $_SESSION ) ){
	        session_start();
	    }
	    
	    try {
	        ob_start();
	        
	        $cpagar = new CuentasPagarModel();
	        
	        //dato que viene de parte del plugin DataTable
	        $requestData = $_REQUEST;
	        $searchDataTable   = $requestData['search']['value'];
	        
	        $buscador  = $_POST['input_search'];
	        
	        /** buscar por el usuario que se encuentra logueado */
	        //$_usuario_logueado = $_SESSION['usuario_usuarios'];
	        	        
	        $columnas1 = " aa.id_cuentas_pagar, aa.id_proveedor, aa.id_estado, aa.fecha_cuentas_pagar, aa.id_tipo_documento, bb.identificacion_proveedores, 
                bb.razon_social_proveedores, bb.nombre_proveedores, aa.numero_documento_cuentas_pagar, aa.total_cuentas_pagar, aa.descripcion_cuentas_pagar";	        
	        $tablas1   = " tes_cuentas_pagar aa
	            INNER JOIN proveedores bb ON bb.id_proveedores = aa.id_proveedor";	        
	        $where1    = "1 = 1 ";
	        
	        //aqui poner para filtrar si es por session
	        
	        /* PARA FILTROS DE CONSULTA */
	        if( strlen( $searchDataTable ) > 0 ){
	            
	            //if( sizeof( explode( "-", $searchDataTable ) ) == 2 && strlen( $searchDataTable ) == 10 )
	            //{
	            //    $where1    .= " AND aa.fecha_cuentas_pagar = $searchDataTable ";
	            //}
	            
	            $where1    .= " AND ( bb.identificacion_proveedores = '$searchDataTable' ";
	            $where1    .= " OR bb.razon_social_proveedores ILIKE '%$searchDataTable%' ";
	            $where1    .= " OR TO_CHAR( aa.fecha_cuentas_pagar, 'YYYY-MM-DD') = '$searchDataTable' ";
	            $where1    .= " OR aa.numero_documento_cuentas_pagar = '$searchDataTable' ";
	            $where1    .= " ) ";
	            
	        }
	        
	        $rsCantidad    = $cpagar->getCantidad("*", $tablas1, $where1);
	        $cantidadBusqueda = (int)$rsCantidad[0]->total;
	        
	        /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
	        
	        // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	        $columns = array(
	            0 => 'aa.id_cuentas_pagar',
	            1 => 'aa.fecha_cuentas_pagar',
	            2 => 'bb.identificacion_proveedores',
	            3 => 'bb.razon_social_proveedores',
	            4 => 'aa.numero_documento_cuentas_pagar',
	            5 => 'aa.total_cuentas_pagar',
	            6 => 'aa.descripcion_cuentas_pagar',
	            7 => 'aa.id_cuentas_pagar'	            
	        );
	        	        
	        $orderby   = $columns[$requestData['order'][0]['column']];
	        $orderdir  = $requestData['order'][0]['dir'];
	        $orderdir  = strtoupper($orderdir);
	        /**PAGINACION QUE VIEN DESDE DATATABLE**/
	        $per_page  = $requestData['length'];
	        $offset    = $requestData['start'];
	        
	        $limit = " ORDER BY $orderby $orderdir LIMIT   '$per_page' OFFSET '$offset'";
	        
	        //$sql = " SELECT $col1 FROM $tab1 WHERE $whe1  $limit ";
	        $sql = "";
	        
	        $resultSet = $cpagar->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
	        
	        //$cantidadBusquedaFiltrada = sizeof($resultSet);
	        
	        /** crear el array data que contiene columnas en plugins **/
	        $data = array();
	        $dataFila = array();
	        $columnIndex = 0;
	        foreach ( $resultSet as $res){
	            $columnIndex++;
	           
	            $opciones = ""; //variable donde guardare los datos creados automaticamente	            
	            $opciones = '<div class="pull-right ">
                            <span >
                                <a class="btn btn-default input-sm showpdf" data-id="'.$res->id_cuentas_pagar.'" data-toogle="tooltip"  href="#" title="Ver PDF"> <i class="fa fa-file-pdf-o" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            </div>';
	           
	            
	            /*$html.='<span class="pull-right ">
	             <a onclick="ValidarEdicionGenerados(this)" id="" data-idarchivo="'.$res->id_descuentos_registrados_cabeza.'"
	             href="#" class="btn btn-sm btn-default label " data-toggle="tooltip" data-placement="top" title="Editar">
	             <i class="fa fa-edit text-warning" aria-hidden="true" ></i>
	             </a></span></td>';
	             $html.='<td style="font-size: 18px;">';*/
	            /*$html.='<span class="pull-right ">
	             <a onclick="eliminarRegistro(this)" id="" data-idarchivo="'.$res->id_descuentos_registrados_cabeza.'"
	             href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Eliminar">
	             <i class="fa fa-trash text-danger" aria-hidden="true" ></i>
	             </a></span>';
	             $html.='</td>';*/
	            
	            /*$html.='<td style="font-size: 18px;">';
	             $html.='<span class="pull-right ">
	             <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones_detalle.'"
	             href="#" class="btn btn-sm btn-default label label-warning">
	             <i class="fa fa-edit" aria-hidden="true" ></i>
	             </a></span></td>';*/
	            
	            $dataFila['numfila'] = $columnIndex;
	            $dataFila['fecha']  = $res->fecha_cuentas_pagar;
	            $dataFila['cedula_proveedor']   = $res->identificacion_proveedores;
	            $dataFila['nombre_proveedor']   = $res->razon_social_proveedores;
	            $dataFila['numero_documento']   = $res->numero_documento_cuentas_pagar;
	            $dataFila['valor_documento']   = $res->total_cuentas_pagar;
	            $dataFila['descripcion']    = $res->descripcion_cuentas_pagar;
	            $dataFila['opciones']          = $opciones;
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