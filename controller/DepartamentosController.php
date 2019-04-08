<?php
class DepartamentosController extends ControladorBase{
    
    public function index(){
        session_start();
        $departamentos = new DepartamentosModel();
        $cargos = new CargosModel();
        $tablasptos= "public.estado";
        $wheredptos= "estado.tabla_estado='EMPLEADOS'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones("*", $tablaest, $whereest, $idest);
        
        $tabla= "public.grupo_empleados";
        $where= "1=1";
        $id = "grupo_empleados.id_grupo_empleados";
        
        $resultSet = $grupo_empleados->getCondiciones("*", $tabla, $where, $id);
        
        $tabla= "public.oficina";
        $where= "1=1";
        $id = "oficina.id_oficina";
        
        $resultOfic = $grupo_empleados->getCondiciones("*", $tabla, $where, $id);
        
        $this->view_Administracion("Empleados",array(
            "resultSet"=>$resultSet,
            "resultEst"=>$resultEst,
            "resultOfic"=>$resultOfic
        ));
    }
}