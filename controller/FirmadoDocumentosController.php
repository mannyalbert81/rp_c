<?php

class CabeceraController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        
        session_start();
        
        
        $contribucion_tipo=new ContribucionTipoModel();
        
        $columnas = " contribucion_tipo.id_contribucion_tipo,
                                       contribucion_categoria.nombre_contribucion_categoria,
                                      contribucion_tipo.nombre_contribucion_tipo,
                                      estado.nombre_estado,
                                      estatus.nombre_estatus";
        $tablas   = "    public.contribucion_categoria,
                          public.contribucion_tipo,
                          public.estatus,
                          public.estado";
        $where    = "  contribucion_categoria.id_contribucion_categoria = contribucion_tipo.id_contribucion_categoria AND
                      contribucion_tipo.id_estatus = estatus.id_estatus AND
                      estado.id_estado = contribucion_tipo.id_estado";
        $id       = "contribucion_tipo.id_contribucion_tipo";
        
        $resultSet = $contribucion_tipo->getCondiciones($columnas ,$tablas ,$where, $id);
        
        
        
        $resultEdit = "";
        
        $contribucion_categoria=new ContribucionCategoriaModel();
        $resultCat=$contribucion_categoria->getAll("nombre_contribucion_categoria");
        
        $estado = new EstadoModel();
        $whe_estado = "tabla_estado = 'contribucion_tipo'";
        $resultEst = $estado->getBy($whe_estado);
        
        $estatus=new EstatusModel();
        $resultEsta=$estatus->getAll("nombre_estatus");
        
        
        if (isset(  $_SESSION['usuario_usuarios']) )
        {
            $contribucion_tipo = new ContribucionTipoModel();
            
            $permisos_rol = new PermisosRolesModel();
            $nombre_controladores = "ContribucionTipo";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $contribucion_tipo->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                
                $this->view_Administracion("ContribucionTipo",array(
                    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultCat"=>$resultCat, "resultEst"=>$resultEst, "resultEsta"=>$resultEsta
                ));
                
            }
            else
            {
                $this->view_Contable("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a Contribucion Tipo"
                    
                ));
                
                exit();
            }
            
        }
        else
        {
            $this->redirect("Usuarios","sesion_caducada");
            
            
         die();
        }
        
    }
    
   
    
}
?>