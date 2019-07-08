<?php
class BuscarParticipesController extends ControladorBase{
    public function index(){
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];
        
        $this->view_Credito("BuscarParticipes",array(
            "result" => ""
        ));
    }
    
    public function BuscarParticipe()
    {
        session_start();
        $columnas="core_estado_participes.nombre_estado_participes, core_participes";
        $tablas="public.core_participes INNER JOIN public.core_estado_participes
                    ON core_participes.id_estado_participes = core_estado_participes.id_estado_participes";
    }
    
    
}

?>