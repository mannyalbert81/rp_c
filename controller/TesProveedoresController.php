<?php

class TesProveedoresController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
	    
	    session_start();	
		//Creamos el objeto usuario
	    $proveedores=new ProveedoresModel();
		
		$resultSet = null;
			
		if (isset(  $_SESSION['nombre_usuarios']) ){

			$nombre_controladores = "Proveedores";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $proveedores->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer)){
				
				$this->view_tesoreria("TesProveedores",array(
				    "resultSet"=>$resultSet			
				));		
				
			}else{
			    
			    $this->view_tesoreria("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Proveedores"				
				));				
			}
				
		}else{
       	
       	    $this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function cargarProveedores(){
	    
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
	
	public function InsertaProveedores(){
			
		session_start();
		$proveedores=new ProveedoresModel();

		$nombre_controladores = "Proveedores";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $proveedores->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
		
		
		
			$resultado = null;
			$proveedores=new ProveedoresModel();
		
			if (isset ($_POST["nombre_proveedores"])   )
			{
			    $_id_proveedores =  $_POST["id_proveedores"];
			    $_nombre_proveedores = $_POST["nombre_proveedores"];
			    $_identificacion_proveedores = $_POST["identificacion_proveedores"];
			    $_contactos_proveedores = $_POST["contactos_proveedores"];
			    $_direccion_proveedores = $_POST["direccion_proveedores"];
			    $_telefono_proveedores = $_POST["telefono_proveedores"];
			    $_email_proveedores = $_POST["email_proveedores"];
			    $_fecha_nacimiento_proveedores = $_POST["fecha_nacimiento_proveedores"];
			   
			  
				
			    if($_id_proveedores > 0){
					
					$columnas = " nombre_proveedores = '$_nombre_proveedores',
                                  identificacion_proveedores = '$_identificacion_proveedores',
                                  contactos_proveedores = '$_contactos_proveedores',
                                    direccion_proveedores = '$_direccion_proveedores',
                                    telefono_proveedores = '$_telefono_proveedores',
                                    email_proveedores = '$_email_proveedores',
                                    fecha_nacimiento_proveedores = '$_fecha_nacimiento_proveedores'";
					$tabla = "proveedores";
					$where = "id_proveedores = '$_id_proveedores'";
					$resultado=$proveedores->UpdateBy($columnas, $tabla, $where);
					
				}else{
					
					$funcion = "ins_proveedores";
					$parametros = " '$_nombre_proveedores','$_identificacion_proveedores','$_contactos_proveedores','$_direccion_proveedores','$_telefono_proveedores','$_email_proveedores','$_fecha_nacimiento_proveedores'";
					$proveedores->setFuncion($funcion);
					$proveedores->setParametros($parametros);
					$resultado=$proveedores->Insert();
				}
				
				
				
		
			}
			$this->redirect("Proveedores", "index");

		}
		else
		{
		    $this->view_Inventario("Error",array(
					"resultado"=>"No tiene Permisos de Insertar Proveedores"
		
			));
		
		
		}
		
	}
	
	public function AgregaProveedores(){
	    
	    session_start();
	    $proveedores=new ProveedoresModel();
	    $respuesta = null;
	    
	    $nombre_controladores = "Proveedores";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $proveedores->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	       
	        echo '<message>No tiene permisos<message>';
	        die();
	    }
	    
	    $error = "";	    
	    try {
	        
	        $_id_proveedores               =  $_POST["id_proveedores"];
	        $_nombre_proveedores           = $_POST["nombre_proveedores"];
	        $_identificacion_proveedores   = $_POST["identificacion_proveedores"];
	        $_contactos_proveedores        = $_POST["contactos_proveedores"];
	        $_direccion_proveedores        = $_POST["direccion_proveedores"];
            $_telefono_proveedores         = $_POST["telefono_proveedores"];
	        $_email_proveedores            = $_POST["email_proveedores"];
	        $_fecha_nacimiento_proveedores = $_POST["fecha_nacimiento_proveedores"];
	        $_id_tipo_proveedores          = $_POST["id_tipo_proveedores"];
	        $_forma_pago                   = $_POST["forma_pago"];
	        $_id_bancos                    = $_POST["id_bancos"];
	        $_id_tipo_cuentas              = $_POST["id_tipo_cuentas"];
	        $_numero_cuenta_proveedores    = $_POST["numero_cuenta_proveedores"];
	        $_tipo_identificacion          = $_POST["tipo_identificacion"];
            $_razon_social                 = $_POST["razon_social_proveedores"];
            
            //$file_proveedores = $_FILES['imagen_registro'];
            
            
            
            //$file_proveedores = (isset($_FILES['imagen_registro'])&& $_FILES['imagen_registro'] !=NULL)?$_FILES['imagen_registro']:'';
            
	       	
	          
	        $archivo_proveedores   = '';
	        
	        if (isset($_FILES['imagen_registro']['tmp_name'])!="")
	        {
	            $directorio = $_SERVER['DOCUMENT_ROOT'].'/rp_c/DOCUMENTOS_GENERADOS/pdf_proveedores';
	            
	            $nombre    = $_FILES['imagen_registro']['name'];
	            //$tipo      = $_FILES['imagen_registro']['type'];
	            //$tamano    = $_FILES['imagen_registro']['size'];
	            
	            move_uploaded_file($_FILES['imagen_registro']['tmp_name'],$directorio.$nombre);
	            $data = file_get_contents($directorio.$nombre);
	            $archivo_proveedores = pg_escape_bytea($data);
	        }else{
	            
	            //$directorio = dirname(__FILE__).'\..\view\images\usuario.jpg';
	            //$imagen_registro   = is_file( $directorio ) ? pg_escape_bytea( file_get_contents( $directorio ) ) : "null";
	            $archivo_proveedores   = "";
	        } 
	        
	        $error = error_get_last();
	        
	        if(!empty($error)){
	            throw new Exception(" Variables no definidas ". $error['message'] );
	        }
	        
	        
            if( $_forma_pago == "cheque" ){
                $_id_bancos = 'null';
                $_id_tipo_cuentas = 'null';
                $_numero_cuenta_proveedores = '';
            }
            
            //$archivo_proveedores = ( $archivo_proveedores === 'null' ) ? $archivo_proveedores : "'$archivo_proveedores'";
	        
            if($_id_proveedores > 0){
                
                
                if(isset($_FILES['imagen_registro']['tmp_name'])!=""){
                    
                    
                    $columnas = " nombre_proveedores = '$_nombre_proveedores',
                              identificacion_proveedores = '$_identificacion_proveedores',
                              contactos_proveedores = '$_contactos_proveedores',
                              direccion_proveedores = '$_direccion_proveedores',
                              telefono_proveedores = '$_telefono_proveedores',
                              email_proveedores = '$_email_proveedores',
                              id_tipo_proveedores = '$_id_tipo_proveedores',
                              id_bancos = $_id_bancos,
                              id_tipo_cuentas = $_id_tipo_cuentas,
                              razon_social_proveedores = '$_razon_social',
                              tipo_identificacion_proveedores = '$_tipo_identificacion',
                              numero_cuenta_proveedores = '$_numero_cuenta_proveedores',
                              archivo_registro='$archivo_proveedores'";
                    
                    
                }else{
                    
                    
                    $columnas = " nombre_proveedores = '$_nombre_proveedores',
                              identificacion_proveedores = '$_identificacion_proveedores',
                              contactos_proveedores = '$_contactos_proveedores',
                              direccion_proveedores = '$_direccion_proveedores',
                              telefono_proveedores = '$_telefono_proveedores',
                              email_proveedores = '$_email_proveedores',
                              id_tipo_proveedores = '$_id_tipo_proveedores',
                              id_bancos = $_id_bancos,
                              id_tipo_cuentas = $_id_tipo_cuentas,
                              razon_social_proveedores = '$_razon_social',
                              tipo_identificacion_proveedores = '$_tipo_identificacion',
                              numero_cuenta_proveedores = '$_numero_cuenta_proveedores'";
                    
                }
                
                
                
                
                
                $tabla = "proveedores";
                $where = "id_proveedores = '$_id_proveedores'";
                
                $resultado=$proveedores->ActualizarBy($columnas, $tabla, $where);
                
                if( (int)$resultado < 0 )
                    throw new Exception("Error Actualizar Datos");
                    
                $respuesta['respuesta'] = 1;
                $respuesta['mensaje'] = "Datos Proveedor Actualizado";
                
            }else{
                
                $funcion = "ins_proveedores2";
                $parametros = " '$_nombre_proveedores','$_identificacion_proveedores','$_contactos_proveedores',
                                '$_direccion_proveedores','$_telefono_proveedores','$_email_proveedores',
                                '$_id_tipo_proveedores', $_id_bancos, $_id_tipo_cuentas, '$_numero_cuenta_proveedores',
                                '$_tipo_identificacion','$_razon_social', '$archivo_proveedores'";
                $proveedores->setFuncion($funcion);
                $proveedores->setParametros($parametros);
                $resultado = $proveedores->llamafuncionPG();
                $_error_pg = pg_last_error();
                if( is_null($resultado) || !empty($_error_pg) )
                    throw new Exception("Error Insertar Datos");
                    
                $respuesta['respuesta'] = 1;
                $respuesta['mensaje'] = "Proveedor Insertado";
            }
	        
            echo json_encode($respuesta);
	        
	        
	    }catch (Exception $ex){
	        
	        echo '<message> Error Proveedores \n'. $ex->getMessage().'<message>';
	        
	    }	    
	    
	}
	
	public function borrarId()
	{
	    
	    session_start();
	    $proveedores=new ProveedoresModel();
	    $nombre_controladores = "Proveedores";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $proveedores->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer))
	    {
	        if(isset($_GET["id_proveedores"]))
	        {
	            $id_proveedores=(int)$_GET["id_proveedores"];
	            
	            
	            
	            $proveedores->deleteBy("id_proveedores",$id_proveedores);
	            
	            
	        }
	        
	        $this->redirect("Proveedores", "index");
	        
	        
	    }
	    else
	    {
	        $this->view_Inventario("Error",array(
	            "resultado"=>"No tiene Permisos de Borrar Proveedores"
	            
	        ));
	    }
	    
	}
	
	public function paginate($reload, $page, $tpages, $adjacents,$funcion='') {
	    
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
	
	public function ins_proveedor(){
	    
	    session_start();
	    $proveedores=new ProveedoresModel();
	    
	    $nombre_controladores = "Proveedores";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $proveedores->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer))
	    {  
	        
	        $resultado = null;
	        $proveedores=new ProveedoresModel();
	        
	        if (isset ($_POST["nombre_proveedores"])   )
	        {
	            $_nombre_proveedores = $_POST["nombre_proveedores"];
	            $_identificacion_proveedores = $_POST["identificacion_proveedores"];
	            $_contactos_proveedores = $_POST["contactos_proveedores"];
	            $_direccion_proveedores = $_POST["direccion_proveedores"];
	            $_telefono_proveedores = $_POST["telefono_proveedores"];
	            $_email_proveedores = $_POST["email_proveedores"];
	              
                $funcion = "ins_proveedores";
                $parametros = " '$_nombre_proveedores','$_identificacion_proveedores','$_contactos_proveedores','$_direccion_proveedores','$_telefono_proveedores','$_email_proveedores'";
                $proveedores->setFuncion($funcion);
                $proveedores->setParametros($parametros);
                $resultado=$proveedores->llamafuncion();
	           
                $respuesta=0;
                
                //print_r($resultado);
                
                if(!empty($resultado) && count($resultado)>0)
                {
                    foreach ($resultado[0] as $k => $v)
                        $respuesta=$v;
                }
                
                if($respuesta==0){
                    echo json_encode(array('success'=>$respuesta,'mensaje'=>'Error al insertar proveedores'));
                    
                }else{
                    echo json_encode(array('success'=>$respuesta,'mensaje'=>'Proveedor ingresado con exito'));
                }
                
             }
	       
	        
	    }
	    else
	    {
	        echo json_encode(array('success'=>0,'mensaje'=>'Error de permisos'));
	    }
	    
	}
	
	
	/***
	 * dc 2019-04-18
	 * title: buscaProveedorByCedula
	 * mod: tesoreria
	 * return: json
	 */
	public function buscaProveedorByCedula(){
	    
	    $proveedores = new ProveedoresModel();
	    
	    if(isset($_GET['term'])){
	        
	        $cedula_proveedores = $_GET['term'];
	        
	        $rsProveedores=$proveedores->getBy("identificacion_proveedores ILIKE '$cedula_proveedores%'");
	        
	        $respuesta = array();
	        
	        if(!empty($rsProveedores) && count($rsProveedores) > 0 ){	            
	                
	            foreach ($rsProveedores as $res){
                    
                    $_cls_proveedores = new stdClass;
                    $_cls_proveedores->id = $res->id_proveedores;
                    $_cls_proveedores->value = $res->identificacion_proveedores;
                    $_cls_proveedores->label = $res->identificacion_proveedores.' - '.$res->nombre_proveedores;
                    $_cls_proveedores->nombre = $res->nombre_proveedores;
                    $_cls_proveedores->email = $res->email_proveedores;
                    
                    $respuesta[] = $_cls_proveedores;
                }
	                
	                echo json_encode($respuesta);
	            
	        }else{
	            echo '[{"id":"","value":"Proveedor No Encontrado"}]';
	        }
	        
	    }
	        
	    
	}
	
	/**
	 * mod: Contabilidad
	 * title: Cargar Bancos
	 * ajax: si
	 * dc:2019-07-09
	 */
	public function cargaBancos(){
	    
	    $estados = null;
	    $estados = new EstadoModel();
	    
	    $query = " SELECT id_bancos,nombre_bancos
                FROM tes_bancos ban INNER JOIN estado ON ban.id_estado = estado.id_estado
                WHERE estado.nombre_estado='ACTIVO' AND tabla_estado = 'tes_bancos'";
	    
	    $resulset = $estados->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	/**
	 * mod: Contabilidad
	 * title: Cargar Tipo Proveedor
	 * ajax: si
	 * dc: 2019-07-09
	 */
	public function cargaTipoProveedores(){
	    
	    $estados = null;
	    $estados = new EstadoModel();
	    
	    $query = " SELECT id_tipo_proveedores, nombre_tipo_proveedores
                FROM tes_tipo_proveedores";
	    
	    $resulset = $estados->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	/**
	 * mod: Contabilidad
	 * title: Cargar Tipo Cuenta
	 * ajax: si
	 * dc: 2019-07-09
	 */
	public function cargaTipoCuentas(){
	    
	    $estados = null;
	    $estados = new EstadoModel();
	    
	    $query = " SELECT id_tipo_cuentas, nombre_tipo_cuentas
                FROM core_tipo_cuentas";
	    
	    $resulset = $estados->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	/***
	 * dc 2019-07-09
	 * mod: Contabilidad
	 * desc: lista proveedores
	 */
	public function ListaProveedores(){	    
	   
	    $busqueda = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : "";
	    
	    if(!isset($_POST['peticion'])){
	        echo 'sin conexion';
	        return;
	    }
	    
	    $page = ( isset( $_REQUEST['page'] ) ) ? $_REQUEST['page'] : 1;
	    
	    $Proveedores = new ProveedoresModel();
	    
	    $columnas = "id_proveedores, nombre_proveedores, identificacion_proveedores, contactos_proveedores, direccion_proveedores,
                    telefono_proveedores, email_proveedores, archivo_registro";
	    
	    $tablas = "public.proveedores";
	    
	    $where = " 1=1 ";
	    
	    //para los parametros de where
	    if(!empty($busqueda)){
	        
	        $where .= "AND ( nombre_proveedores LIKE '$busqueda%' OR identificacion_proveedores LIKE '$busqueda%' )";
	    }
	    
	    $id = "nombre_proveedores";
	    
	    //para obtener cantidad
	    $rsResultado = $Proveedores->getCantidad("1", $tablas, $where, $id);
	    
	    $cantidad = 0;
	    $html = "";
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    if(!is_null($rsResultado) && !empty($rsResultado) && count($rsResultado)>0){
	        $cantidad = $rsResultado[0]->total;
	    }
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet = $Proveedores->getCondicionesPag( $columnas, $tablas, $where, $id, $limit);
	    
	    $tpages = ceil($cantidad/$per_page);
	    
	    if( $cantidad > 0 ){
	        
	        //$html.='<div class="pull-left" style="margin-left:11px;">';
	        //$html.='<span class="form-control"><strong>Registros: </strong>'.$cantidad.'</span>';
	        //$html.='<input type="hidden" value="'.$cantidad.'" id="total_query" name="total_query"/>' ;
	        //$html.='</div>';
	        //$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        //$html.='<section style="height:200px; overflow-y:scroll;">';
	        $html.= "<table id='tbl_tabla_proveedores' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">IDENTIFICACION</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">NOMBRE</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CONTACTO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">DIRECCION</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">TELEFONO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CORREO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">ARCHIVO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $i=0;
	        
	        foreach ($resultSet as $res){
	            
	            $i++;
	            $html.='<tr>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->identificacion_proveedores.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_proveedores.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->contactos_proveedores.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->direccion_proveedores.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->telefono_proveedores.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->email_proveedores.'</td>';
	            
	            if(!empty($res->archivo_registro)){
	                $html.='<td><a title="Archivo" target="_blank" href="view/DevuelvePDFView.php?id_valor='.$res->id_proveedores.'&id_nombre=id_proveedores&tabla=proveedores&campo=archivo_registro"><img src="view/images/logo_pdf.png" width="30" height="30"></a></td>';
	            }
	            else {
	                
	                $html.='<td></td>';
	            }
	            
	            $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right">';
	            $html.='<a title="Editar Proveedores" onclick="editarProveedores('.$res->id_proveedores.')" href="#" class="btn-sm btn-warning" style="font-size:65%;" data-toggle="tooltip" >';
	            $html.='<i class="fa  fa-edit" aria-hidden="true" ></i></a></td>';
	            $html.='</tr>';
	            
	        }
	        
	        
	        $html.='</tbody>';
	        $html.='</table>';
	        //$html.='</section></div>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate("index.php", $page, $tpages, $adjacents,"ListaProveedores").'';
	        $html.='</div>';
	        
	        
	        
	    }else{
	        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	        $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	        $html.='<h4>Aviso!!!</h4> <b> No hay cuentas por Pagar</b>';
	        $html.='</div>';
	        $html.='</div>';
	    }
	    
	    //array de datos
	    $respuesta = array();
	    $respuesta['tabladatos'] = $html;
	    $respuesta['valores'] = array('cantidad'=>$cantidad);
	    echo json_encode($respuesta);
	    
	}
	
	public function datosProveedoresEditar(){
	    
	    $proveedores = new ProveedoresModel();
	    
	    $_id_proveedores = $_POST['id_proveedores'];
	    $resp  = null; 
	    $columna1  = " * ";
	    $tabla1    = " proveedores";
	    $where1    = " id_proveedores = $_id_proveedores";
	    $rsConsulta1   = $proveedores->getCondicionesmenosid($columna1, $tabla1, $where1);
	    	    
	    if( !empty($rsConsulta1) ){
	        $resp['datos'] = $rsConsulta1[0];
	    }else{
	        $resp['datos'] = null;
	    }
	    
	    echo json_encode($resp);
	    
	}
	
}
?>