<?php

class PresupuestosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}


	public function index(){
	    
	    $presupuestos = new PresupuestosModel();
	    
	   
	    session_start();
	    
	    if(empty( $_SESSION)){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        return;
	    }
	    
	    $nombre_controladores = "Presupuestos";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $presupuestos->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso Presupuestos"
	            
	        ));
	        exit();
	    }
	   
	    $this->view_Contable("Presupuesto",array());
	    
	}
	
	public function AutocompleteComprobantesCodigo(){
	    
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    $plan_cuentas = new PlanCuentasModel();
	    $codigo_plan_cuentas = $_GET['term'];
	    
	    $columnas ="plan_cuentas.codigo_plan_cuentas";
	    $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	    $where ="plan_cuentas.codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
	    $id ="plan_cuentas.codigo_plan_cuentas";
	    
	    
	    $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    
	    if(!empty($resultSet)){
	        
	        foreach ($resultSet as $res){
	            
	            $_respuesta[] = $res->codigo_plan_cuentas;
	        }
	        echo json_encode($_respuesta);
	    }
	    
	}
	
	public function InsertarPresupuestos(){
	    
	    session_start();
	    
	    $presupuestos = new PresupuestosModel();
	    
	    $nombre_controladores = "Presupuestos";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $presupuestos->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    
	    
	        $_id_plan_cuentas = (isset($_POST["id_plan_cuentas"])) ? $_POST["id_plan_cuentas"] : "" ;
	        $_nombre_presupuestos = (isset($_POST["nombre_presupuestos"])) ? $_POST["nombre_presupuestos"] : 0 ;
	        $_valor_procesado = (isset($_POST["valor_procesado"])) ? $_POST["valor_procesado"] : 0 ;
	        $_valor_ejecutado = (isset($_POST["valor_ejecutado"])) ? $_POST["valor_ejecutado"] : 0 ;
	        
	        $_id_estado =null;
	        $_columnas = "id_estado, nombre_estado";
	        $_tablas ="estado";
	        $_where ="tabla_estado='presupuestos' AND nombre_estado='ACTIVO'";
	        $_id ="id_estado";
	        
	        $_rs_consulta = $presupuestos->getCondiciones($_columnas, $_tablas, $_where, $_id);
	        if(!empty($_rs_consulta)){
	            $_id_estado = $_rs_consulta[0]->id_estado;
	        }
	        else{
	            echo "no se encontro estado";
	            exit();
	        }
	        
	        $funcion = "ins_presupuestos";
	        $parametros = "'$_id_plan_cuentas','$_nombre_presupuestos','$_valor_procesado', '$_valor_ejecutado','$_id_estado'";
	        $consulta = $presupuestos->getconsultaPG($funcion, $parametros);
	        
	       
	        
	        $ResultPresupuestos = $presupuestos->llamarconsultaPG($consulta);
	        $error = pg_last_error();
	        
	        if(!empty($error) ){ 
	            echo "Presupuestos no ingresada";
	            exit();
	        }
	
	        $respuesta=array();
	        $respuesta['mensaje']=1;
	        $respuesta['respuesta']='';
	        
	        echo json_encode($respuesta);	
	}
	
	
	
}
?>