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
    
    public function cuotaParticipe(){
        session_start();
        $creditos = new EstadoModel();
        $cedula_participe=$_POST['cedula_participe'];
        $columnas='core_creditos.id_creditos';
        $tablas='core_creditos INNER JOIN core_participes
        ON core_creditos.id_participes = core_participes.id_participes';
        $where="core_participes.cedula_participes='$cedula_participe' AND core_creditos.id_estado_creditos=4
        AND core_creditos.id_estatus=1";
        $id_credito=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "LIMIT 1");
        $id_credito=$id_credito[0]->id_creditos;
        $columnas='total_valor_tabla_amortizacion';
        $tablas='core_tabla_amortizacion';
        $where="id_estado_tabla_amortizacion=3 AND id_creditos=".$id_credito;
        $id='ORDER BY numero_pago_tabla_amortizacion LIMIT 1';
        $cuota_credito=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, $id);
        $cuota_credito=$cuota_credito[0]->total_valor_tabla_amortizacion;
        echo $cuota_credito;
    }    
    
}


?>