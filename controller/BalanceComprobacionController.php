<?php

class BalanceComprobacionController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        session_start();
        $this->view_Contable('BalanceComprobacion',array());
    }
    
   
    public function buscaperiodo(){
        
        session_start();
        
        $_id_usuarios = $_SESSION['id_usuarios'];
        
        $usuarios = new UsuariosModel();
        
        $query = "SELECT id_periodo,anio_periodo,mes_periodo,nombre_estado
                FROM public.con_periodo 
                INNER JOIN public.estado ON estado.id_estado = con_periodo.id_estado";
        
        $resultSet = $usuarios->enviaquery($query);
        
        $respuesta=array();
        
        if(!empty($resultSet) && count($resultSet)>0){
            $respuesta=array('mensaje'=>'1');
            $datos = array();
            foreach ($resultSet as $res){
                $datos[] = $res;
            }
            $respuesta['datos']=$datos;
        }else{
            $respuesta=array('mensaje'=>'0');
           
        }
        
        echo json_encode($respuesta);
        //print_r($resultSet);
    }
    
    /***
     * desc: funcion que genera balance
     */
    public function generarbalance(){
        
        $usuarios = new UsuariosModel();
        
        
        if(isset($_POST['ajax'])){
            
            session_start();
            
            $id_usuarios = $_SESSION['id_usuarios'];
            
            $_anio_balance = $_POST['anio_balance'];
            $_mes_balance = $_POST['mes_balance'];
            
            //array que devuelve a la vista
            $respuesta = array();
                       
            //generacion balance
            //realiza llamada de funcion con devolucion de datos
            $queryfuncion = "SELECT ins_balance_comprobacion('$id_usuarios','$_anio_balance','$_mes_balance')";
            $resultfuncion = $usuarios->enviaquery($queryfuncion);
            
            $respuestafuncion = 0;
            
            if(!empty($resultfuncion) && count($resultfuncion)>0){
                foreach ($resultfuncion[0] as $k => $v){
                    $respuestafuncion=$v;
                }
            }
            
            if($respuestafuncion==1){
                
                $respuesta['mensaje']=1;
                
                $querycabecera = "SELECT entidades.id_entidades, 
                                	entidades.nombre_entidades, 
                                	con_cbalance_comprobacion.id_cbalance_comprobacion,
                                	con_cbalance_comprobacion.anio_cbalance_comprobacion,
                                	con_cbalance_comprobacion.mes_cbalance_comprobacion
                                FROM con_cbalance_comprobacion 
                                INNER JOIN entidades ON con_cbalance_comprobacion.id_entidades = entidades.id_entidades
                                WHERE con_cbalance_comprobacion.anio_cbalance_comprobacion = '$_anio_balance'
                                AND con_cbalance_comprobacion.mes_cbalance_comprobacion = $_mes_balance";
                
                $resultcabecera = $usuarios->enviaquery($querycabecera);
                
                $id_cabecera = 0;
                
                if(!empty($resultcabecera) && count($resultcabecera)>0){
                    
                    $respuesta['cabecera']=$resultcabecera[0];
                    
                    $id_cabecera= $resultcabecera[0]->id_cbalance_comprobacion;
                    
                }
                
               
                $querydetalle = "SELECT plan_cuentas.id_plan_cuentas,plan_cuentas.codigo_plan_cuentas,
                        plan_cuentas.nivel_plan_cuentas,plan_cuentas.nombre_plan_cuentas, 
                        id_dbalance_comprobacion, suma_debe_dbalance_comprobacion, 
                        suma_haber_dbalance_comprobacion, saldo_deudor_dbalance_comprobacion, saldo_acreedor_dbalance_comprobacion                        
                        FROM plan_cuentas 
                        INNER JOIN con_dbalance_comprobacion ON con_dbalance_comprobacion.id_plan_cuentas = plan_cuentas.id_plan_cuentas
                        WHERE id_cbalance_comprobacion = '$id_cabecera' ORDER BY plan_cuentas.codigo_plan_cuentas";
                
                $resultdetalle = $usuarios->enviaquery($querydetalle);
                
                if(!empty($resultdetalle) && count($resultdetalle)>0){
                    
                    $respuesta['detalle']=$resultdetalle;
                    
                    
                }
                
                $querytotales = 'SELECT SUM(suma_debe_dbalance_comprobacion) "totaldebe",
                        SUM(suma_haber_dbalance_comprobacion) "totalhaber",
                        SUM(saldo_acreedor_dbalance_comprobacion) "saldoa",
                        SUM(saldo_deudor_dbalance_comprobacion) "saldod"
                        FROM con_dbalance_comprobacion
                        WHERE id_cbalance_comprobacion ='.$id_cabecera;
                
                $resulttotales = $usuarios->enviaquery($querytotales);
                
                if(!empty($resulttotales) && count($resulttotales)>0){
                    
                    $respuesta['totales']=$resulttotales[0];
                    
                    
                }
                
            }
            
          echo json_encode($respuesta);
            
        }else{
            //$this->redirect('BalanceComprobacion','index'); 
            echo 'njo ajax';
        }
    }

    
  
    
    
    
    public function paginate_bodegas_inactivos($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_bodegas_inactivos(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_bodegas_inactivos(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_bodegas_inactivos(1)'>1</a></li>";
        }
        // interval
        if($page>($adjacents+2)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // pages
        
        $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
        $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
        for($i=$pmin; $i<=$pmax; $i++) {
            if($i==$page) {
                $out.= "<li class='active'><a>$i</a></li>";
            }else if($i==1) {
                $out.= "<li><a href='javascript:void(0);' onclick='load_bodegas_inactivos(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_bodegas_inactivos(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_bodegas_inactivos($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_bodegas_inactivos(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
   
    
    
}
?>