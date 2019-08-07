<?php

class ProveedoresController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
	    $proveedores=new ProveedoresModel();
					//Conseguimos todos los usuarios
	    $resultSet=$proveedores->getAll("id_proveedores");
				
		$resultEdit = "";
		
		$resultSet = null;
		
		session_start();
        
	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "Proveedores";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $proveedores->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_proveedores"])   )
				{

					$nombre_controladores = "Proveedores";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $proveedores->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_proveedores = $_GET["id_proveedores"];
						$columnas = " id_proveedores, nombre_proveedores, identificacion_proveedores, contactos_proveedores, direccion_proveedores, telefono_proveedores,
                                     email_proveedores, id_tipo_proveedores, id_bancos, id_tipo_cuentas, numero_cuenta_proveedores";
						
						$tablas   = "proveedores";
						$where    = "id_proveedores = '$_id_proveedores' "; 
						$id       = "nombre_proveedores";
							
						$resultEdit = $proveedores->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
					    $this->view_Inventario("Error",array(
								"resultado"=>"No tiene Permisos de Editar Proveedores"
					
						));
					
					
					}
					
				}
		
				
				$this->view_Inventario("Proveedores",array(
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit
			
				));
		
				
				
			}
			else
			{
			    $this->view_Inventario("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Proveedores"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
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
	    $respuesta = array();
	    
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
	        $_id_bancos                    = $_POST["id_bancos"];
	        $_id_tipo_cuentas              = $_POST["id_tipo_cuentas"];
	        $_numero_cuenta_proveedores    = $_POST["numero_cuenta_proveedores"];
	       	        
	        $error = error_get_last();
	        
	        if(!empty($error))
	            throw new Exception(" Variables no definidas ". $error['message'] );
	        
            if($_id_proveedores > 0){
                
                $columnas = " nombre_proveedores = '$_nombre_proveedores',
                              identificacion_proveedores = '$_identificacion_proveedores',
                              contactos_proveedores = '$_contactos_proveedores',
                              direccion_proveedores = '$_direccion_proveedores',
                              telefono_proveedores = '$_telefono_proveedores',
                              email_proveedores = '$_email_proveedores',
                              id_tipo_proveedores = '$_id_tipo_proveedores',
                              id_bancos = '$_id_bancos',
                              id_tipo_cuentas = '$_id_tipo_cuentas',
                              numero_cuenta_proveedores = '$_numero_cuenta_proveedores'";
                
                $tabla = "proveedores";
                $where = "id_proveedores = '$_id_proveedores'";
                
                $resultado=$proveedores->ActualizarBy($columnas, $tabla, $where);
                
                if( (int)$resultado < 0 )
                    throw new Exception("Error Actualizar Datos");
                    
                $respuesta['respuesta'] = 1;
                $respuesta['mensaje'] = "Proveedor Actualizado";
                
            }else{
                
                $funcion = "ins_proveedores";
                $parametros = " '$_nombre_proveedores','$_identificacion_proveedores','$_contactos_proveedores',
                                '$_direccion_proveedores','$_telefono_proveedores','$_email_proveedores',
                                '$_id_tipo_proveedores', '$_id_bancos', '$_id_tipo_cuentas', '$_numero_cuenta_proveedores'";
                $proveedores->setFuncion($funcion);
                $proveedores->setParametros($parametros);
                $resultado=$proveedores->llamafuncionPG();
                if( is_null($resultado) )
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
	

	
	public function paginate_grupos($reload, $page, $tpages, $adjacents,$funcion='') {
	    
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
	    
	    $page = (isset($_REQUEST['page']))?isset($_REQUEST['page']):1;
	    
	    $Proveedores = new ProveedoresModel();
	    
	    $columnas = "id_proveedores, nombre_proveedores, identificacion_proveedores, contactos_proveedores, direccion_proveedores,
                    telefono_proveedores, email_proveedores";
	    
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
	            $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right">';
	            $html.='<a title="Editar Proveedores" href="index.php?controller=Proveedores&action=index&id_proveedores='.$res->id_proveedores.'" class="btn-sm btn-warning" style="font-size:65%;"data-toggle="tooltip" >';
	            $html.='<i class="fa  fa-edit" aria-hidden="true" ></i></a></td>';
	            $html.='</tr>';
	            
	        }
	        
	        
	        $html.='</tbody>';
	        $html.='</table>';
	        //$html.='</section></div>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate("index.php", $page, $tpages, $adjacents,"").'';
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
	
}
?>