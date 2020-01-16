<?php

class TesCuentasPagarController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	    
	    $Productos = new ProductosModel();
	
		$bancos = new BancosModel();
				
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "compras";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $Productos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Bancos"
		        
		    ));
		    exit();
		}		
		
		$this->view_tesoreria("IngresoTransacciones",array(
		));
			
	
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
	
	public function buscaProveedores(){
	    
	    $Proveedores = new ProveedoresModel();
	    $respuesta   = array(); 
	    
	    $busqueda = ( isset($_POST['buscador']) ) ? $_POST['buscador'] : "";
	    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	    
	    $columnas1 = " aa.id_tipo_proveedores,aa.id_proveedores, aa.nombre_proveedores, aa.identificacion_proveedores, aa.direccion_proveedores, aa.celular_proveedores";
	    $tablas1   = " proveedores aa
    	    INNER JOIN tes_tipo_proveedores bb ON aa.id_tipo_proveedores = bb.id_tipo_proveedores";
	    $where1    = " bb.nombre_tipo_proveedores = 'PAGO PROVEEDORES'";
	    $id1       = " aa.nombre_proveedores";
	    
	    if( strlen($busqueda) > 0 ){
	        $where1 .= " AND ( aa.identificacion_proveedores ILIKE '$busqueda%' OR aa.nombre_proveedores ILIKE '$busqueda%' ) ";
	    }
	    
	    $resultSet = $Proveedores->getCantidad("*", $tablas1, $where1);
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet = $Proveedores->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    	    
	    $error = error_get_last();
	    if( !empty($error) ){
	        echo $error['message'];
	        exit();
	    }
	    $htmlTr = "";
	    $i = 0;
	    foreach ($resultSet as $res){
	        $i++;
	        $btonSelect = "<button onclick=\"SelecionarProveedor(this)\" value=\"$res->id_proveedores\" class=\"btn btn-default\"> 
                        <i aria-hidden=\"true\" class=\"fa fa-external-link\"></i> </button>";
	        $htmlTr    .= "<tr>";
	        $htmlTr    .= "<td>" . $i . "</td>";
	        $htmlTr    .= "<td>" . $res->identificacion_proveedores . "</td>";
	        $htmlTr    .= "<td>" . $res->nombre_proveedores . "</td>";	        
	        $htmlTr    .= "<td>" . $btonSelect . "</td>";
	        $htmlTr    .= "</tr>";	        
	        	        
	    }
	    
	    $respuesta['filas']    = $htmlTr;
	    
	    $htmlPaginacion  = '<div class="table-pagination pull-right">';
	    $htmlPaginacion .= ''. $this->paginate("index.php", $page, $total_pages, $adjacents,"loadProveedores").'';
	    $htmlPaginacion .= '</div>';
	    
	    $respuesta['paginacion'] = $htmlPaginacion;
	    $respuesta['cantidadDatos'] = $cantidadResult;
	    
	    echo json_encode( $respuesta );
	}

	
	/** INGRESAR UN NUEVO LOTE **/
	public function RegistrarLote(){
	    	    
	    if( empty( $_SESSION ) ){
	        session_start();
	    }
	    	    
	    $lote = new LoteModel();
	    
	    $respuesta = array();
	    	   
	    try {
	        
	        $_id_usuarios = $_SESSION['id_usuarios'];
	        
	        $_id_lote      = ( isset($_POST['id_lote']) ) ? $_POST['id_lote'] : 0;
	        $_nombre_lote  = ( isset($_POST['nombre_lote']) ) ? $_POST['nombre_lote'] : "";
	        $_descripcion_lote = "Mod Cuentas Pagar";
	        $_id_frecuencia = 1; //cambiara si en la tabla frecuencia lote cambia 
	        
	        $funcion = "tes_genera_lote";
	        $parametros = "'$_nombre_lote','$_descripcion_lote', $_id_frecuencia , $_id_usuarios";
	        $queryFuncion  = $lote->getconsultaPG($funcion, $parametros);
	        
	        $resultado = $lote->llamarconsultaPG($queryFuncion);
	        
	        $pgError = pg_last_error();
	        
	        if( !empty($pgError) ){ throw new Exception($pgError); }
	        
	        $_id_lote  = $resultado[0];
	        $respuesta['icon']     = "success";
	        $respuesta['respuesta']= "OK";
	        $respuesta['mensaje']  = "lote generado";
	        $respuesta['id_lote']  = $_id_lote;
	        
	        echo json_encode($respuesta);	        
	        
	    } catch (Exception $e) {
	        
	        $respuesta['icon']     = "error";
	        $respuesta['respuesta']  = "ERROR";
	        $respuesta['mensaje']  = $e->getMessage();
	        echo json_encode($respuesta);
	    }	    
	   
	}
	
	/**
	 * funcion que permite seleci9onar el proveedor en el modal 
	 */
	public function SelecionarProveedor(){
	    
	    $Proveedores =  new ProveedoresModel();
	    
	    $_id_proveedor = $_POST['id_proveedores'];	    
	   	    
	    $error = error_get_last();
	    if(!empty($error)){ echo "proveedor no seleccionado"; exit();}
	    
	    $columnas1 = " id_proveedores, nombre_proveedores, identificacion_proveedores, direccion_proveedores, celular_proveedores";
	    $tablas1   = " proveedores";
	    $where1    = " id_proveedores = $_id_proveedor";
	    $id1       = " id_proveedores";
	    
	    $consulta1 = $Proveedores->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    echo json_encode(array("data"=>$consulta1));
	}
	
	/**
	 * funcion que envia el secuencial de documento ..consecutivos
	 */
	public function getSecuencialDocumento(){
	    
	    $Consecutivos = new ConsecutivosModel();
	    	    
	    $query = "SELECT id_consecutivos, LPAD(valor_consecutivos::TEXT,espacio_consecutivos,'0') AS secuencial FROM consecutivos
                WHERE id_entidades = 1 AND nombre_consecutivos='CxP'";
	    
	    $resulset = $Consecutivos->enviaquery($query);
	    
	    echo json_encode(array('data'=>$resulset));	        
	   
	}
	
	/**
	 * mod: tesoreria
	 * title: cargaTipoDocumento
	 * ajax: si
	 * dc:2019-05-09
	 * desc: carga todos los tipos de documento
	 */
	public function getTipoDocumento(){
	    
	    $tipoDocumento = null;
	    $tipoDocumento = new TipoDocumentoModel();
	    
	    $query = " SELECT id_tipo_documento, abreviacion_tipo_documento, nombre_tipo_documento
                FROM public.tes_tipo_documento
                WHERE 1 = 1";
	    
	    $resulset = $tipoDocumento->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
}
?>