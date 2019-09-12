<?php
class AnalisisCreditosController extends ControladorBase{
    public function index(){
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];
        
        $this->view_Credito("AnalisisCredito",array(
            "result" => ""
        ));
    }
    
}


?>