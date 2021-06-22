
<?php


class IniciarController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}


	public function index(){}
	
	
	
	
	
	public function index2(){
        
            $entidad = new EntidadPatronalParticipesModel();
			$resultEnt= $entidad->getAll("nombre_entidad_patronal");
				
			$bancos = new BancosModel();
			$resultBan= $bancos->getAll("nombre_bancos");
			
            $this->view_ServiciosOnline("NuevaAfiliacionOnline",array(
              "resultEnt"=>$resultEnt, "resultBan"=>$resultBan
                
            ));
        
    }
	
	
	
		public function NuevaAfiliacion(){
			
		$resultado = null;
		$usuarios=new UsuariosModel();
		

    		if (isset ($_POST["cedula_afiliados"]))
    		{
 
    			$_cedula_afiliados     = $_POST["cedula_afiliados"];
    			$_nombre_afiliados     = mb_strtoupper ($_POST["nombre_afiliados"]);
    			$_apellidos_afiliados     = mb_strtoupper ($_POST["apellidos_afiliados"]);
    			$_correo_afiliados = $_POST['correo_afiliados'];
    			$_celular_afiliados    = $_POST['celular_afiliados'];
    			$_telefono_afiliados      = $_POST["telefono_afiliados"];
    			$_fecha_nacimiento_afiliados    = $_POST["fecha_nacimiento_afiliados"];
    			$_id_entidad_patronal     = $_POST["id_entidad_patronal"];
    			$_id_entidad_patronal_coordinaciones = $_POST['id_entidad_patronal_coordinaciones'];
    			$_nombre_otra_coordinacion    = mb_strtoupper ($_POST['nombre_otra_coordinacion']);
				
    		    $_id_bancos    = $_POST["id_bancos"];
    			$_tipo_cuenta_afiliados     = $_POST["tipo_cuenta_afiliados"];
    			$_numero_cuenta_afiliados = $_POST['numero_cuenta_afiliados'];
    			
    		    
    		        
    		        $funcion = "ins_nueva_afiliacion";
    		        $parametros = "'$_cedula_afiliados',
    		    				   '$_nombre_afiliados',
                                   '$_apellidos_afiliados',
                                   '$_correo_afiliados',
                                   '$_celular_afiliados',
    		    	               '$_telefono_afiliados',
    		    	               '$_fecha_nacimiento_afiliados',
    		    	               '$_id_entidad_patronal',
    		    	               '$_id_entidad_patronal_coordinaciones',
    		    	               '$_nombre_otra_coordinacion',
                                   '$_id_bancos',
                                   '$_tipo_cuenta_afiliados',
                                   '$_numero_cuenta_afiliados'";
    		        $usuarios->setFuncion($funcion);
    		        $usuarios->setParametros($parametros);
    		        
    		        
    		        $resultado=$usuarios->llamafuncion();
    		        
    		        $respuesta = '';
    		        
    		        if(!empty($resultado) && count($resultado)){
    		            
    		            foreach ($resultado[0] as $k => $v)
    		            {
    		                $respuesta=$v;
    		            }
    		            
    		            if (strpos($respuesta, 'OK') !== false) {
    		               
						   
						    $query = "select id_afiliados
									from solicitud_nuevos_afiliados
									where cedula_afiliados='$_cedula_afiliados'";
							$resultado1  = $usuarios->enviaquery($query);
							
                            if(!empty($resultado1)){
								
								$id_afiliados=$resultado1[0]->id_afiliados;
								echo json_encode(array('success'=>1,'mensaje'=>$respuesta,'id'=>$id_afiliados));
							}
			
    		               
    		            }else{
    		                echo json_encode(array('success'=>0,'mensaje'=>$respuesta));
    		            }
    		            
    		        }
    		            
    		    }		    
    		
	}
	

    
    
    
    
	
	
	
}
?>