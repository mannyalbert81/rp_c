<?php

class IndexacionController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
        $bancos = new BancosModel();
        
        require_once 'core/EntidadBase_128.php';
        $db = new EntidadBase_128();
        
        
        session_start();
        
        if(empty( $_SESSION)){
            
            $this->redirect("Usuarios","sesion_caducada");
            return;
        }
        
        $nombre_controladores = "Indexacion";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bancos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (empty($resultPer)){
            
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos de Acceso Bancos"
                
            ));
            exit();
        }
        
        $rsBancos = $bancos->getBy(" 1 = 1 ");
        
        
        $this->view_GestionDocumental("Indexacion",array(
            "resultSet"=>$rsBancos
            
        ));
        
        
    }
    
  
    public function cargaCategoria(){
        
        require_once 'core/EntidadBase_128.php';
        $db = new EntidadBase_128();
        
        $columnas="id_categorias, nombre_categorias";
        $tabla = "categorias";
        $where = "1=1";
        
        $resulset = $db->getBy($columnas,$tabla, $where);
        
        if(!empty($resulset) && count($resulset)>0){
            
            echo json_encode(array('data'=>$resulset));
            
        }
    }
    
    
    
    public function cargaSubCategoria(){
        
        require_once 'core/EntidadBase_128.php';
        $db = new EntidadBase_128();
        
        
        $id_categorias = (isset($_POST['id_categorias'])) ? $_POST['id_categorias'] : 0;
        
        if($id_categorias > 0){
            $columnas="id_subcategorias, nombre_subcategorias";
            $tabla = "subcategorias";
            $where = "id_categorias='$id_categorias'";
            
            $resulset = $db->getBy($columnas,$tabla, $where);
            
            if(!empty($resulset) && count($resulset)>0){
                
                echo json_encode(array('data'=>$resulset));
                
            }
        }
       
    }
    
    
    public function AutocompleteCedula(){
        
        require_once 'core/EntidadBase_128.php';
        $db = new EntidadBase_128();
        
        if(isset($_GET['term'])){
            
            $numero_credito = $_GET['term'];
            
            $resultSet=$db->getBy("numero_credito LIKE '$numero_credito%'");
            
            $respuesta = array();
            
            if(!empty($resultSet)){
                
                if(count($resultSet)>0){
                    
                    foreach ($resultSet as $res){
                        
                        $_cls_usuarios = new stdClass;
                        $_cls_usuarios->id=$res->id_usuarios;
                        $_cls_usuarios->value=$res->cedula_usuarios;
                        $_cls_usuarios->label=$res->cedula_usuarios.' | '.$res->nombre_usuarios;
                        $_cls_usuarios->nombre=$res->nombre_usuarios;
                        
                        $respuesta[] = $_cls_usuarios;
                    }
                    
                    echo json_encode($respuesta);
                }
                
            }else{
                echo '[{"id":0,"value":"sin datos"}]';
            }
            
        }else{
            
            $cedula_usuarios = (isset($_POST['term']))?$_POST['term']:'';
            
            $columna = "  usuarios.id_usuarios,
            	    usuarios.cedula_usuarios,
            	    usuarios.nombre_usuarios,
            	    usuarios.apellidos_usuarios,
                    usuarios.usuario_usuarios,
                    usuarios.fecha_nacimiento_usuarios,
            	    claves.clave_claves,
            	    claves.clave_n_claves,
                    claves.caduca_claves,
            	    usuarios.telefono_usuarios,
            	    usuarios.celular_usuarios,
            	    usuarios.correo_usuarios,
            	    rol.id_rol,
            	    rol.nombre_rol,
            	    usuarios.fotografia_usuarios,
            	    usuarios.creado,
                    usuarios.id_estado,
                    eu.nombre_estado";
            
            $tablas = " public.usuarios INNER JOIN public.claves ON claves.id_usuarios = usuarios.id_usuarios
                    INNER JOIN public.estado ON estado.id_estado = claves.id_estado
                    INNER JOIN public.estado eu ON eu.id_estado = usuarios.id_estado
                    LEFT JOIN public.rol ON rol.id_rol = usuarios.id_rol";
            
            $where = "estado.nombre_estado = 'ACTIVO'
                    AND eu.nombre_estado = 'ACTIVO'
                    AND usuarios.cedula_usuarios = '$cedula_usuarios'";
            
            $resultSet=$usuarios->getCondiciones($columna,$tablas,$where,"usuarios.cedula_usuarios");
            
            $respuesta = new stdClass();
            
            if(!empty($resultSet)){
                
                $respuesta->id_usuarios = $resultSet[0]->id_usuarios;
                $respuesta->cedula_usuarios = $resultSet[0]->cedula_usuarios;
                $respuesta->nombre_usuarios = $resultSet[0]->nombre_usuarios;
                $respuesta->apellidos_usuarios = $resultSet[0]->apellidos_usuarios;
                $respuesta->usuario_usuarios = $resultSet[0]->usuario_usuarios;
                $respuesta->fecha_nacimiento_usuarios = $resultSet[0]->fecha_nacimiento_usuarios;
                $respuesta->clave_claves = $resultSet[0]->clave_claves;
                $respuesta->clave_n_claves = $resultSet[0]->clave_n_claves;
                $respuesta->telefono_usuarios = $resultSet[0]->telefono_usuarios;
                $respuesta->celular_usuarios = $resultSet[0]->celular_usuarios;
                $respuesta->correo_usuarios = $resultSet[0]->correo_usuarios;
                $respuesta->caduca_claves = $resultSet[0]->caduca_claves;
                $respuesta->id_rol = $resultSet[0]->id_rol;
                $respuesta->nombre_rol = $resultSet[0]->nombre_rol;
                $respuesta->id_estado = $resultSet[0]->id_estado;
                $respuesta->fotografia_usuarios = $resultSet[0]->fotografia_usuarios;
                
            }
            
            echo json_encode($respuesta);
            
        }
        
        
        
    }
    
    
    
    
    
}
?>