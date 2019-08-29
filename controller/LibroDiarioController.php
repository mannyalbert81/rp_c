<?php

class LibroDiarioController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
	    $proveedores=new ProveedoresModel();
					//Conseguimos todos los usuarios
	    $resultSet=$proveedores->getAll("id_proveedores");
				
		$resultEdit = "";
		
	
		
		session_start();
        
	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "ReporteMayor";
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
					//hola
					    $_id_proveedores = $_GET["id_proveedores"];
						$columnas = " id_proveedores, nombre_proveedores, identificacion_proveedores, contactos_proveedores, direccion_proveedores, telefono_proveedores, email_proveedores, fecha_nacimiento_proveedores ";
						$tablas   = "proveedores";
						$where    = "id_proveedores = '$_id_proveedores' "; 
						$id       = "nombre_proveedores";
							
						$resultEdit = $proveedores->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
					    $this->view_Contable("Error",array(
								"resultado"=>"No tiene Permisos de Editar Proveedores"
					
						));
					
					
					}
					
				}
		
				
				$this->view_Contable("LibroDiario",array(
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit
			
				));
		
				
				
			}
			else
			{
			    $this->view_Contable("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Proveedores"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
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
	
//hola
	
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

	

	
	public function AutocompleteCodigo(){
	    
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    
	    $usuarios = new UsuariosModel();
	    $plan_cuentas = new PlanCuentasModel();
	    
	    if(isset($_GET['term'])){
	        
	        $codigo_plan_cuentas = $_GET['term'];
	        
	        $columnas ="plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	        $where ="plan_cuentas.codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
	        $id ="plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	              
	            foreach ($resultSet as $res){
	                    
	                    $_cuenta = new stdClass;
	                    $_cuenta->id=$res->id_plan_cuentas;
	                    $_cuenta->value=$res->codigo_plan_cuentas;
	                    $_cuenta->label=$res->codigo_plan_cuentas;
	                    $_cuenta->nombre=$res->nombre_plan_cuentas;
	                    
	                    $respuesta[] = $_cuenta;
	                }
	                
	                echo json_encode($respuesta);
	           
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }else{
	        
	        $codigo_plan_cuentas = (isset($_POST['term']))?$_POST['term']:'';
	        
	        $columnas ="plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	        $where ="plan_cuentas.codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
	        $id ="plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	            
	            foreach ($resultSet as $res){
	                
	                $_cuenta = new stdClass;
	                $_cuenta->id=$res->id_plan_cuentas;
	                $_cuenta->value=$res->codigo_plan_cuentas;
	                $_cuenta->label=$res->codigo_plan_cuentas;
	                $_cuenta->nombre_cuenta=$res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cuenta;
	            }
	            
	            echo json_encode($respuesta);
	            
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }
	    
	    
	    
	}
	
	public function AutocompleteNombre(){
	    
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    
	    $plan_cuentas = new PlanCuentasModel();
	    
	    if(isset($_GET['term'])){
	        
	        $nombre_plan_cuentas = $_GET['term'];
	        
	        $columnas  = " plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas    = " public.usuarios,
        				   public.entidades,
        				   public.plan_cuentas";
	        $where     = "plan_cuentas.nombre_plan_cuentas LIKE '$nombre_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				           plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
	        $id        = "plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	            
	            foreach ($resultSet as $res){
	                
	                $_cuenta = new stdClass;
	                $_cuenta->id=$res->id_plan_cuentas;
	                $_cuenta->value=$res->nombre_plan_cuentas;
	                $_cuenta->label=$res->nombre_plan_cuentas;
	                $_cuenta->nombre=$res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cuenta;
	            }
	            
	            echo json_encode($respuesta);
	            
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }else{
	        
	        $nombre_plan_cuentas = (isset($_POST['term']))?$_POST['term']:'';
	        
	        $columnas ="plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	        $where ="plan_cuentas.nombre_plan_cuentas LIKE '$nombre_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
	        $id ="plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	            
	            foreach ($resultSet as $res){
	                
	                $_cuenta = new stdClass;
	                $_cuenta->id=$res->id_plan_cuentas;
	                $_cuenta->value=$res->codigo_plan_cuentas;
	                $_cuenta->label=$res->codigo_plan_cuentas;
	                $_cuenta->nombre_cuenta=$res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cuenta;
	            }
	            
	            echo json_encode($respuesta);
	            
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }
	    
	    
	    
	}
	
	
	public function diarioContable(){
	    
	    session_start();
	    
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
	    
	    $datos_empresa = array();
	    $datos_detalle = array();
	    
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
	    
	    $query_detalle = "SELECT * FROM vw_diariocontable WHERE 1=1 ";
	    
	    //tomar datos vista
	    $_fecha_desde = (isset($_POST['desde_diario']))?$_POST['desde_diario']:'';
	    $_fecha_hasta = (isset($_POST['hasta_diario']))?$_POST['hasta_diario']:'';
	    $_id_cuenta = (isset($_POST['id_cuenta']))?$_POST['id_cuenta']:0;
	    
	    $_fecha = date('d-m-Y');
	    
	    if($_fecha_desde != '' || $_fecha_hasta != ''){
	        if($_fecha_desde != '' && $_fecha_hasta != ''){
	            $query_detalle.=' AND ( fecha_ccomprobantes BETWEEN \''.$_fecha_desde.'\' AND \''.$_fecha_hasta.'\' ) ';
	        }
	        if($_fecha_desde != '' && $_fecha_hasta == ''){
	            $query_detalle .= " AND fecha_ccomprobantes >= '$_fecha_desde'";
	        }
	        if($_fecha_desde == '' && $_fecha_hasta != ''){
	            $query_detalle .= ' AND fecha_ccomprobantes <= \''.$_fecha_hasta.'\' ';
	        }
	    }
	    
	    if($_id_cuenta>0){
	        
	        $query_detalle.=' AND id_ccomprobantes IN ( SELECT ccomprobantes.id_ccomprobantes
                                                        FROM ccomprobantes
                                                        INNER JOIN dcomprobantes
                                                        ON ccomprobantes.id_ccomprobantes = dcomprobantes.id_ccomprobantes
                                                        WHERE dcomprobantes.id_plan_cuentas = '.$_id_cuenta.'
                                                        AND ccomprobantes.aprobado_ccomprobantes = true
                                                        GROUP BY ccomprobantes.id_ccomprobantes
                                                        ORDER BY ccomprobantes.id_ccomprobantes )';
	       
	    }
	    
	    $query_detalle.= ' ORDER BY id_ccomprobantes';
	    
	    $rsdetalle = $entidades->enviaquery($query_detalle);
	    
	    if(!empty($rsdetalle) && count($rsdetalle)>0){
	        
	        $datos_detalle=$rsdetalle;
	    }
	    
	   
	    $this->verReporte("DiarioContable", array('datos_empresa'=>$datos_empresa,'datos_detalle'=>$datos_detalle, 'datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera,));
	   
	    
	    
	}
	
	
	/***
	 * title: validafecha
	 * mod: contable
	 * return: bolean
	 */
	public function validafecha($fecha=null){
	    if(is_null($fecha) || empty($fecha)){
	        return false;
	    }
	    
	}
	
	public function dataToExcel(){
	    session_start();
	    
	    $entidades = new EntidadesModel();
	   
	    if(!isset($_POST['peticion'])){
	    
	    echo 'sin datos';
	    return;
	    }
	    
	    //llenado de datos
	    
	    $datos_empresa = array();
	    $datos_detalle = array();
	    
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
	    
	    $query_detalle = "SELECT * FROM vw_diariocontable WHERE 1=1 ";
	    
	    //tomar datos vista
	    $_fecha_desde = (isset($_POST['desde_diario']))?$_POST['desde_diario']:'';
	    $_fecha_hasta = (isset($_POST['hasta_diario']))?$_POST['hasta_diario']:'';
	    $_id_cuenta = (isset($_POST['id_cuenta']))?$_POST['id_cuenta']:0;
	    
	    if($_fecha_desde != '' || $_fecha_hasta != ''){
	        if($_fecha_desde != '' && $_fecha_hasta != ''){
	            $query_detalle.=' AND ( fecha_ccomprobantes BETWEEN \''.$_fecha_desde.'\' AND \''.$_fecha_hasta.'\' ) ';
	        }
	        if($_fecha_desde != '' && $_fecha_hasta == ''){
	            $query_detalle .= ' AND fecha_ccomprobantes >= \''.$_fecha_desde.'\'';
	        }
	        if($_fecha_desde == '' && $_fecha_hasta != ''){
	            $query_detalle .= ' AND fecha_ccomprobantes <= \''.$_fecha_hasta.'\' ';
	        }
	    }
	    
	    if($_id_cuenta>0){
	        
	        $query_detalle.=' AND id_ccomprobantes IN ( SELECT ccomprobantes.id_ccomprobantes
                                                        FROM ccomprobantes
                                                        INNER JOIN dcomprobantes
                                                        ON ccomprobantes.id_ccomprobantes = dcomprobantes.id_ccomprobantes
                                                        WHERE dcomprobantes.id_plan_cuentas = '.$_id_cuenta.'
                                                        GROUP BY ccomprobantes.id_ccomprobantes
                                                        ORDER BY ccomprobantes.id_ccomprobantes )';
	        
	    }
	    
	    $query_detalle.= ' ORDER BY id_ccomprobantes';
	    
	    //print_r($query_detalle); die();
	    
	    $rsdetalle = $entidades->enviaquery($query_detalle);
	    
	    /* se dibuja la hoja */
	    $datos_detalle = array();
	    
	    array_push($datos_detalle,'#','Fecha','Comprobante','Codigo','Cuenta','Detalle','Debe','Haber');
	    
	    if(!empty($rsdetalle) && count($rsdetalle)>0){
	           
	            $i=0;
	            $iTmp=0;
	            $variable=0;
	            
	            foreach ($rsdetalle as $res){
	                $i+=1;
	                
	                if($res->id_ccomprobantes!=$variable){
	                    if($i!=1){
	                        array_push($datos_detalle, ' ',' ',' ',' ',' ',' ',' ',' ');
	                    }
	                    
	                    $iTmp +=1;
	                    
	                    $variable=$res->id_ccomprobantes;
	                    
	                    array_push($datos_detalle,
	                        $iTmp,$res->fecha_ccomprobantes,
	                            $res->tipo_comprobantes.' - '.$res->numero_ccomprobantes,
	                            $res->codigo_plan_cuentas,
	                            $res->nombre_plan_cuentas,
	                            $res->descripcion_dcomprobantes,
	                            $res->debe_dcomprobantes,
	                            $res->haber_dcomprobantes
	                        );
	                    
	                        
	                   
	                }else{
	                    
	                    array_push($datos_detalle,
	                        ' ',' ',
	                            ' ',
	                            $res->codigo_plan_cuentas,
	                            $res->nombre_plan_cuentas,
	                            $res->descripcion_dcomprobantes,
	                           $res->debe_dcomprobantes,
	                           $res->haber_dcomprobantes
	                            
	                        );
	                    
	                }
	                
	            }
	       
	    }
	    
	    $cantidad = count($datos_detalle);
	    
	    
	    echo json_encode(array('cantidad'=>$cantidad,'datos_empresa'=>$datos_empresa,'datos_detalle'=>$datos_detalle));
	}
	
}
?>