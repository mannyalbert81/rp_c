<?php

class PrincipalBusquedasRecaudacionesController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}
		  
    
	public function index(){
	
	    session_start();
		
     	$EntidadPatronal = new EntidadPatronalParticipesModel();
     		
		if( isset(  $_SESSION['nombre_usuarios'] ) ){

			$nombre_controladores = "PrincipalBusquedasRecaudaciones";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $EntidadPatronal->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer)){
			    
			    $queryEntidad = "SELECT * FROM core_entidad_patronal ORDER BY nombre_entidad_patronal";			    
			    $rsEntidadPatronal = $EntidadPatronal->enviaquery($queryEntidad);
			
			    $this->view_Recaudaciones("PrincipalBusquedasRecaudaciones",array(
			        'rsEntidadPatronal' => $rsEntidadPatronal
			    ));
				
			}else{
			    
			    $this->view("Error",array(
			        "resultado"=>"No tiene Permisoss"
			        
			    ));
			    
			    exit();				    
			}
				
		}else{
       	
		    $this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function cargaEntidadPatronal(){
	    
	    $entidad_patronal = null;
	    $entidad_patronal = new EntidadPatronalParticipesModel();
	    
	    $query = "SELECT id_entidad_patronal,nombre_entidad_patronal FROM core_entidad_patronal WHERE 1=1 ORDER BY nombre_entidad_patronal ";
	    
	    $resulset = $entidad_patronal->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	public function dtIngresoBancos()
	
	{
	    if( !isset( $_SESSION ) ){
	        session_start();
	    }
	    
	    try {
	        ob_start();
	        
	        $ingreso_bancos = new RecaudacionesModel();
	        
	        //dato que viene de parte del plugin DataTable
	        $requestData = $_REQUEST;
	        $searchDataTable   = $requestData['search']['value'];
	        
	        /** buscar por el usuario que se encuentra logueado */
	         
	        $id_entidad_patronal = $_POST['id_entidad_patronal'];
	        $anio_ingreso_bancos_cabeza = $_POST['anio_ingreso_bancos_cabeza'];
	        $mes_ingreso_bancos_cabeza = $_POST['mes_ingreso_bancos_cabeza'];
	        
	      $columnas1 = "id_ingreso_bancos_cabeza,
                        id_entidad_patronal,
                        id_bancos,
                        mes_ingreso_bancos_cabeza,
                        anio_ingreso_bancos_cabeza,
                        fecha_deposito_ingreso_bancos_cabeza,
                        fecha_coleccion_ingreso_bancos_cabeza,
                        numero_referencia_ingreso_bancos_cabeza,
                        valor_transaccion_ingreso_bancos_cabeza,
                        diferencia_ingreso_bancos_cabeza,
                        comentario_ingreso_bancos_cabeza,
                        id_ccomprobantes,
                        id_ccomprobantes_reverso,
                        fecha_servidor_ingreso_bancos_cabeza,
                        usuario_usuarios,
                        es_banco_entrada_ingreso_bancos_cabeza,
                        id_estatus";
	        $tablas1   = "core_ingreso_bancos_cabeza";
	        $where1    = "id_entidad_patronal = $id_entidad_patronal
                        and anio_ingreso_bancos_cabeza = $anio_ingreso_bancos_cabeza
                        and mes_ingreso_bancos_cabeza = $mes_ingreso_bancos_cabeza
                        and id_estatus = 1";
	        
	        /* PARA FILTROS DE CONSULTA */
	        
	        if( strlen( $searchDataTable ) > 0 )
	        {
	            #$where1 .= " AND ( ";
	            #$where1 .= " id_entidad_patronal ILIKE '%$searchDataTable%' ";
	            #$where1 .= " OR TO_CHAR(anio_ingreso_bancos_cabeza,'9999') ilike '%$searchDataTable%' ";
	            #$where1 .= " ) ";
	            
	        }
	        
	        $rsCantidad    = $ingreso_bancos->getCantidad("*", $tablas1, $where1);
	        $cantidadBusqueda = (int)$rsCantidad[0]->total;
	        
	        /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
	        
	        // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	        $columns = array(
	            0 => '1',
	            1 => '1',
	            2 => '1',
	            3 => '1'
	        );
	        
	        $orderby   = $columns[$requestData['order'][0]['column']];
	        $orderdir  = $requestData['order'][0]['dir'];
	        $orderdir  = strtoupper($orderdir);
	        /**PAGINACION QUE VIEN DESDE DATATABLE**/
	        $per_page  = $requestData['length'];
	        $offset    = $requestData['start'];
	        
	        //para validar que consulte todos
	        $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
	        
	        $limit = " ORDER BY $orderby $orderdir LIMIT   $per_page OFFSET '$offset'";
	        
	        $sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
	        //$sql = "";
	        
	        $resultSet=$ingreso_bancos->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
	        
	        /** crear el array data que contiene columnas en plugins **/
	        $data = array();
	        $dataFila = array();
	        $columnIndex = 0;
	        foreach ( $resultSet as $res){
	            $columnIndex++;
	            
	            $opciones = "";
	            
	            $opciones = '<div class="pull-right ">
                            <span >
                                <a onclick="mostrar_detalle_modal(this)" id="" data-id_ingreso_bancos_cabeza="'.$res->id_ingreso_bancos_cabeza.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Ver Detalle Modal"> <i class="fa  fa-file-text-o fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                                    
                            </div>';
	            
	            $mes = "";
	            if($res->mes_ingreso_bancos_cabeza == 1){
	                $mes = 'Enero';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 2){
	                $mes = 'Febrero';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 3){
	                $mes = 'Marzo';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 4){
	                $mes = 'Abril';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 5){
	                $mes = 'Mayo';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 6){
	                $mes = 'Junio';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 7){
	                $mes = 'Julio';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 8){
	                $mes = 'Agosto';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 9){
	                $mes = 'Septiembre';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 10){
	                $mes = 'Octubre';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 11){
	                $mes = 'Noviembre';
	            };
	            if($res->mes_ingreso_bancos_cabeza == 12){
	                $mes = 'Diciembre';
	            };
	            
	            
	        
	            $dataFila['numfila'] = $columnIndex;
	            $dataFila['mes_ingreso_bancos_cabeza']  = $mes;
	            $dataFila['valor_ingreso_bancos_cabeza'] = $res->valor_transaccion_ingreso_bancos_cabeza;
	            $dataFila['diario_ingreso_bancos_cabeza'] = $res->diferencia_ingreso_bancos_cabeza;
	            $dataFila['opciones'] = $opciones;
	            
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
	                "sql" => "",//$sql
	            );
	            
	    } catch (Exception $e) {
	        
	        $json_data = array(
	            "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	            "recordsTotal" => intval("0"),  // total number of records
	            "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
	            "data" => array(),   // total data array
	            "sql" => $sql,
	            "buffer" => error_get_last(),
	            "ERRORDATATABLE" => $e->getMessage()
	        );
	    }
	    
	    
	    echo json_encode($json_data);
	}
}

	
?>