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
	    
	    $mayor = new MayorModel();	    
	    
	    if(!isset($_POST['action'])){
	        
	        echo 'sin datos';
	        return;
	    }
	    
	    
	    $this->verReporte("DiarioContable", array());
	   
	    
	    
	    
	       
	    
	    $id_plan_cuentas = (isset($_POST['id_cuenta']))?$_POST['id_cuenta']:'0';
	    
	    $columna = 'SELECT id_plan_cuentas,codigo_plan_cuentas,nombre_plan_cuentas';
	    
	    $tabla = ' FROM vw_diario_contable';
	    
	    $where = ' WHERE 1=1';
	    
	    $grupo = ' GROUP BY id_plan_cuentas,codigo_plan_cuentas,nombre_plan_cuentas';
	    
	    $orden = ' ORDER BY codigo_plan_cuentas';
	    
	    $where = ($id_plan_cuentas>0)?$where.' AND id_plan_cuentas='.$id_plan_cuentas:$where;
	    
	    $query=$columna.$tabla.$where.$grupo.$orden;
	    
	    $result = $mayor->enviaquery($query);
	    
	    $html='';
	    
	    if(!empty($result) && count($result)>0){
	        
	        $html.='<table class="table">';
	        
	        for($i=0;$i<count($result);$i++){
	           
	            $codigo = $result[$i]->id_plan_cuentas;
	            
	            $html.='<tr style="font-weight:bold; text-transform: uppercase;" class="active">';
	            $html.='<td>';
	            $html.= $result[$i]->codigo_plan_cuentas;
	            $html.='</td>';
	            $html.='<td colspan="3">';
	            $html.= $result[$i]->nombre_plan_cuentas;
	            $html.='</td>';
	            $html.='</tr>';
	            
	            $query="SELECT * FROM vw_diario_contable WHERE id_plan_cuentas = $codigo ORDER BY codigo_plan_cuentas";
	            
	            $resultdetalle = $mayor->enviaquery($query);
	            
	            if(!empty($resultdetalle) && count($result)>0){
	                
	                $j = 0;
	                foreach ($resultdetalle as $res){
	                    
	                    $j+=1;
	                    $html.='<tr>';
	                    $html.='<td>';
	                    $html.= $j;
	                    $html.='</td>';
	                    $html.='<td>';
	                    $html.= $res->fecha_mayor;
	                    $html.='</td>';
	                    $html.='<td>';
	                    $html.= $res->sumadebe;
	                    $html.='</td>';
	                    $html.='<td>';
	                    $html.= $res->sumahaber;
	                    $html.='</td>';	                   
	                    $html.='</tr>';
	                    
	                }
	            }
	            
	           
	        }
	        
	        $html.='</table>';
	    }
	    
	    echo $html;
	}
	
	
	
	
}
?>