<<?php

class BodegasController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
      
        
        session_start();
        
        //Creamos el objeto usuario
        $bodegas=new BodegasModel();
        //Conseguimos todos los usuarios
        
        
        $columnas = "
                                      bodegas.id_bodegas,
                                      provincias.id_provincias,
                                      provincias.nombre_provincias,
                                      cantones.id_cantones,
                                      cantones.nombre_cantones,
                                      parroquias.id_parroquias,
                                      parroquias.nombre_parroquias,
                                      bodegas.nombre_bodegas";
        $tablas   = " public.bodegas,
                                      public.provincias,
                                      public.cantones,
                                      public.parroquias";
        $where    = "   provincias.id_provincias = bodegas.id_provincias AND
                                        cantones.id_cantones = bodegas.id_cantones AND
                                        parroquias.id_parroquias = bodegas.id_parroquias";
        $id       = "bodegas.id_bodegas";
        
        
        $resultSet=$bodegas->getCondiciones($columnas ,$tablas ,$where, $id);
        
        $provincias=new ProvinciasModel();
        $resultProv=$provincias->getAll("nombre_provincias");
        
        $cantones=new CantonesModel();
        $resultCant=$cantones->getAll("nombre_cantones");
        
        $Parroquias=new ParroquiasModel();
        $resultParr=$Parroquias->getAll("nombre_parroquias");
            
        $resultEdit = "";
        
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "Bodegas";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $bodegas->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                if (isset ($_GET["id_bodegas"])   )
                {
                    
                  
                        
                    $_id_bodegas = $_GET["id_bodegas"];
                        $columnas = "
                                      bodegas.id_bodegas, 
                                      provincias.id_provincias, 
                                      provincias.nombre_provincias, 
                                      cantones.id_cantones, 
                                      cantones.nombre_cantones, 
                                      parroquias.id_parroquias, 
                                      parroquias.nombre_parroquias, 
                                      bodegas.nombre_bodegas";
                        $tablas   = " public.bodegas, 
                                      public.provincias, 
                                      public.cantones, 
                                      public.parroquias";
                        $where    = "   provincias.id_provincias = bodegas.id_provincias AND
                                        cantones.id_cantones = bodegas.id_cantones AND
                                        parroquias.id_parroquias = bodegas.id_parroquias AND bodegas.id_bodegas = '$_id_bodegas'";
                        $id       = "bodegas.id_bodegas";
                        
                        $resultEdit = $bodegas->getCondiciones($columnas ,$tablas ,$where, $id);
                        
                    
                    
                }
                
                
                $this->view("Bodegas",array(
                    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultProv"=>$resultProv, "resultCant"=>$resultCant, "resultParr"=>$resultParr
                    
                ));
                
                
                
            }
            else
            {
                $this->view("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a Bodegas"
                    
                ));
                
                exit();
            }
            
        }
        else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
        
    }
    
    public function InsertaBodegas(){
        
        session_start();
        
        $resultado = null;
        $bodegas=new BodegasModel();
        
        
        $nombre_controladores = "Bodegas";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bodegas->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            
            if ( isset ($_POST["nombre_bodegas"]))
            
            {
                //die('llego');
                
                
                $_id_provincias = $_POST["id_provincias"];
                $_id_cantones = $_POST["id_cantones"];
                $_id_parroquias = $_POST["id_parroquias"];
                $_nombre_bodegas = $_POST["nombre_bodegas"];
                
                                
                
                if($_id_bodegas > 0){
                    
                    $columnas = "
                              
							  id_provincias ='$_id_provincias',
							  id_cantones = '$_id_cantones',
                              id_parroquias = '$_id_parroquias',
                              nombre_bodegas = '$_nombre_bodegas'
							  
							  ";
                    $tabla = "    public.bodegas, 
                                  public.provincias, 
                                  public.cantones, 
                                  public.ciudad";
                    $where = "  provincias.id_provincias = bodegas.id_provincias AND
                                cantones.id_cantones = bodegas.id_cantones AND
                                parroquias.id_parroquias = bodegas.id_parroquias AND bodegas.id_bodegas = '$_id_bodegas'";
                    $resultado=$bodegas->UpdateBy($columnas, $tabla, $where);
                    
                }else{
                    
                    $funcion = "ins_bodegas";
                    $parametros = "'$_id_provincias', '$_id_cantones', '$_id_parroquias', '$_nombre_bodegas'";
                    $bodegas->setFuncion($funcion);
                    $bodegas->setParametros($parametros);
                    $resultado=$bodegas->Insert();
                }
                
            }
            
            $this->redirect("Bodegas", "index");
        }
        else
        {
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos Para Crear Bodegas"
                
            ));
            
            
        }
        
        
        
    }
    
    public function borrarId()
    {
        
        session_start();
        $bodegas=new BodegasModel();
        $nombre_controladores = "Bodegas";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bodegas->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            if(isset($_GET["id_bodegas"]))
            {
                $id_bodegas=(int)$_GET["id_bodegas"];
                
                
                
                $bodegas->deleteBy(" id_bodegas",$id_bodegas);
                
                
            }
            
            $this->redirect("Bodegas", "index");
            
            
        }
        else
        {
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos de Borrar Bodegas"
                
            ));
        }
        
    }
    
    
    public function devuelveCanton()
    {
        session_start();
        $resultCan = array();
        
        
        if(isset($_POST["id_provincias_vivienda"]))
        {
            
            $id_provincias=(int)$_POST["id_provincias_vivienda"];
            
            $cantones=new CantonesModel();
            
            $resultCan = $cantones->getBy(" id_provincias = '$id_provincias'  ");
            
            
        }
        
        if(isset($_POST["id_provincias_asignacion"]))
        {
            
            $id_provincias=(int)$_POST["id_provincias_asignacion"];
            
            $cantones=new CantonesModel();
            
            $resultCan = $cantones->getBy(" id_provincias = '$id_provincias'  ");
            
            
        }
        
        echo json_encode($resultCan);
        
    }
    
    
    
    
    
    
    
    public function devuelveParroquias()
    {
        session_start();
        $resultParr = array();
        
        
        if(isset($_POST["id_cantones_vivienda"]))
        {
            
            $id_cantones_vivienda=(int)$_POST["id_cantones_vivienda"];
            
            $parroquias=new ParroquiasModel();
            
            $resultParr = $parroquias->getBy(" id_cantones = '$id_cantones_vivienda'  ");
            
            
        }
        if(isset($_POST["id_cantones_asignacion"]))
        {
            
            $id_cantones_vivienda=(int)$_POST["id_cantones_asignacion"];
            
            $parroquias=new ParroquiasModel();
            
            $resultParr = $parroquias->getBy(" id_cantones = '$id_cantones_vivienda'  ");
            
            
        }
        
        echo json_encode($resultParr);
        
    }
    
    
    

    
    
    
}
?>