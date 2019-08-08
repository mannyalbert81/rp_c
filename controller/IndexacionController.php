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
        
        session_start();
        $_id_usuarios= $_SESSION['id_usuarios'];
        require_once 'core/EntidadBase_128.php';
        $db = new EntidadBase_128();
     
        $numero_credito = $_GET['term'];
        
        $columnas ="  capremci_creditos.id_capremci, 
                      capremci_creditos.numero_credito, 
                      capremci_creditos.cedula_capremci, 
                      capremci_creditos.nombres_capremci, 
                      capremci_creditos.creado, 
                      capremci_creditos.modificado";
        $tablas ="
                  public.capremci_creditos";
        $where ="capremci_creditos.numero_credito LIKE '$numero_credito%'";
       
        
        
        $resultSet = $db->getBy($columnas,$tablas,$where);
        
        
        if(!empty($resultSet)){
            
            foreach ($resultSet as $res){
                
                $_respuesta[] = $res->codigo_activos_fijos;
            }
            echo json_encode($_respuesta);
        }
        
        
    }
    
    public function DevuelveNombre(){
        session_start();
        $_id_usuarios= $_SESSION['id_usuarios'];
        require_once 'core/EntidadBase_128.php';
        $db = new EntidadBase_128();
       
        $nombres_credito = $_POST['nombres_capremci'];
        
        
        $columnas ="  capremci_creditos.id_capremci,
                      capremci_creditos.numero_credito,
                      capremci_creditos.cedula_capremci,
                      capremci_creditos.nombres_capremci,
                      capremci_creditos.creado,
                      capremci_creditos.modificado";
        $tablas ="
                  public.capremci_creditos";
        $where ="capremci_creditos.numero_credito LIKE '$nombres_credito%'";
        $resultSet = $db->getBy($columnas,$tablas,$where);
    
        
        $respuesta = new stdClass();
        
        if(!empty($resultSet)){
            
            $respuesta->nombres_capremci = $resultSet[0]->nombres_capremci;
            $respuesta->numero_credito = $resultSet[0]->numero_credito;
            $respuesta->id_capremci = $resultSet[0]->id_capremci;
            
            echo json_encode($respuesta);
        }
        
    }
    
    
    
}
?>