<?php
class ConsultaVotosEleccionesController extends ControladorBase{
  
    
    
    public function index(){
        session_start();

        $this->view_Elecciones("ConsultaVotosElecciones",array(
            ""=>""
           
        ));
    }
    
    
    
      
}

?>