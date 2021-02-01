<?php
class EnvioMensajesTextoController extends ControladorBase{
  
    
    
    public function index(){
        
        session_start();

        if(isset($_SESSION["id_usuarios"])){
            
            $this->view_Administracion("EnvioMensajesTexto",array(
                ""=>""
                
            ));
        }
        else{
          
            $this->redirect("Usuarios","Loguear");
        }
        
       
    }
    
    
    
    
    public function autocompleteCedulaParticipes(){
        
        $planCuentas = new PlanCuentasModel();
        
        if(isset($_GET['term'])){
            
            $cedula_participes = $_GET['term'];
            
            $rsPlanCuentas = $planCuentas->getCondiciones("p.id_participes, (p.cedula_participes ||' ' || p.apellido_participes ||' ' || p.nombre_participes ||' ' || p.celular_participes) as datos_participes",
                "core_participes p",
                "  p.cedula_participes ILIKE '%$cedula_participes%' AND  p.id_estatus=1",
                "p.apellido_participes");
            
            $respuesta = array();
            
            if(!empty($rsPlanCuentas) ){
                
                foreach ($rsPlanCuentas as $res){
                    
                    $_cls_plan_cuentas = new stdClass;
                    $_cls_plan_cuentas->id = $res->id_participes;
                    $_cls_plan_cuentas->value = $res->datos_participes;
                    
                    $respuesta[] = $_cls_plan_cuentas;
                }
                
                echo json_encode($respuesta);
                
            }else{
                
                echo '[{"id":"","value":"Participe No Encontrado"}]';
            }
            
        }
    }
    
    
    
    
    public function Enviar(){
        
        session_start();
        $juicios = new ParticipesModel();
       
        $id_usuarios = $_SESSION["id_usuarios"];
        
        $_txt1  = (isset($_POST['txt_var1'])) ? $_POST['txt_var1'] : null;
        $_txt2 = (isset($_POST['txt_var2'])) ? $_POST['txt_var2'] : null;
        $_txt3 = (isset($_POST['txt_var3'])) ? $_POST['txt_var3'] : null;
        $_txt4 = (isset($_POST['txt_var4'])) ? $_POST['txt_var4'] : null;
        $_destinatarios = (isset($_POST['participes_to'])) ? $_POST['participes_to'] : null;
        $_extras = (isset($_POST['participes_to_celular'])) ? $_POST['participes_to_celular'] : null;
        
        
        if(!empty($_txt1)){
            
        }else{
            
            echo  json_encode(array('error'=>'Ingrese Parametros para el mensaje.'));
            exit();
        }
        
        
        if(!empty($_txt2)){
            
        }else{
            
            echo  json_encode(array('error'=>'Ingrese Parametros para el mensaje.'));
            exit();
        }
        
        if(!empty($_txt3)){
            
        }else{
            
            echo  json_encode(array('error'=>'Ingrese Parametros para el mensaje.'));
            exit();
        }
        
        if(!empty($_txt4)){
            
        }else{
            
            echo  json_encode(array('error'=>'Ingrese Parametros para el mensaje.'));
            exit();
        }
        
        
        if(!empty($_destinatarios) || !empty($_extras)){
            
            $_celular="";
            $_nombre="";
            $_id_participes="";
            $cadena_recortada="";
            $mensaje_retorna="";
            
            $enviado_correctamente=0;
            $despacho_en_cola=0;
            $estructura_no_valida=0;
            $metodo_no_existe=0;
            $parametros_incompletos=0;
            $cliente_no_existe=0;
            $mensaje_muy_grande=0;
            $cliente_no_tiene_servicio_online=0;
            $token_invalido=0;
            $sahrcode_no_disponible=0;
            $acceso_remoto_no_permitido=0;
            $telefono_destino_en_lista_negra=0;
            $mensaje_no_asignado=0;
            $data_variable_error_parametros=0;
            $telefono_incorrecto=0;
            $no_se_pudo_procesar=0;
            $error_desconocido=0;
            
            
              if(!empty($_destinatarios)){
                        
                    foreach($_destinatarios as $id  )
                    {
                       //$i++;
                        
                        $_id_participes = $id;
                        
                        if($_id_participes > 0){
                            
                            $resultUsuariosTo = $juicios->getBy("id_participes = '$_id_participes' AND id_estatus=1");
                            
                            if(!empty($resultUsuariosTo)){
                                
                                $_celular=$resultUsuariosTo[0]->celular_participes;
                                $_nombre=$resultUsuariosTo[0]->apellido_participes.'_'.$resultUsuariosTo[0]->nombre_participes;
                                
                                
                                        $cadena_recortada=$this->comsumir_mensaje_plus($_celular, $_nombre, $_txt1, $_txt2, $_txt3, $_txt4);
                                
                                
                                
                                if($cadena_recortada=='100'){
                                
                                    
                                    //$mensaje_retorna="Enviado Correctamente";
                                    $enviado_correctamente=$enviado_correctamente+1;
                                    
                                }else if ($cadena_recortada=='101'){
                                    
                                    //$mensaje_retorna="Despacho en Cola";
                                    $despacho_en_cola=$despacho_en_cola+1;
                                    
                                }else if ($cadena_recortada=='200'){
                                    
                                    //$mensaje_retorna="Estructura no Válida";
                                    $estructura_no_valida=$estructura_no_valida+1;
                                    
                                }else if ($cadena_recortada=='201'){
                                    
                                    //$mensaje_retorna="Método no Existe";
                                    $metodo_no_existe=$metodo_no_existe+1;
                                    
                                }else if ($cadena_recortada=='202'){
                                    
                                    //$mensaje_retorna="Parámetros Incompletos";
                                    $parametros_incompletos=$parametros_incompletos+1;
                                    
                                }else if ($cadena_recortada=='302'){
                                    
                                    //$mensaje_retorna="Cliente no Existe";
                                    $cliente_no_existe=$cliente_no_existe+1;
                                    
                                }else if ($cadena_recortada=='303'){
                                    
                                    //$mensaje_retorna="Mensaje muy Grande";
                                    $mensaje_muy_grande=$mensaje_muy_grande+1;
                                    
                                }else if ($cadena_recortada=='307'){
                                    
                                    //$mensaje_retorna="Cliente no tiene Servicio Online";
                                    $cliente_no_tiene_servicio_online=$cliente_no_tiene_servicio_online+1;
                                    
                                }else if ($cadena_recortada=='309'){
                                    
                                    //$mensaje_retorna="Token Inválido";
                                    $token_invalido=$token_invalido+1;
                                    
                                }else if ($cadena_recortada=='310'){
                                    
                                    //$mensaje_retorna="Shortcode no disponible para el Cliente";
                                    $sahrcode_no_disponible=$sahrcode_no_disponible+1;
                                    
                                }else if ($cadena_recortada=='311'){
                                    
                                    //$mensaje_retorna="Acceso Remoto no Permitido";
                                    $acceso_remoto_no_permitido=$acceso_remoto_no_permitido+1;
                                    
                                }else if ($cadena_recortada=='312'){
                                    
                                    //$mensaje_retorna="Teléfono Destino en Lista Negra";
                                    $telefono_destino_en_lista_negra=$telefono_destino_en_lista_negra+1;
                                    
                                }else if ($cadena_recortada=='313'){
                                    
                                    //$mensaje_retorna="Mensaje no Asignado";
                                    $mensaje_no_asignado=$mensaje_no_asignado+1;
                                    
                                }else if ($cadena_recortada=='314'){
                                    
                                    //$mensaje_retorna="Data Variable no coincide con parámetro enviados";
                                    $data_variable_error_parametros=$data_variable_error_parametros+1;
                                    
                                }else if ($cadena_recortada=='315'){
                                    
                                    //$mensaje_retorna="Teléfono Incorrecto";
                                    $telefono_incorrecto=$telefono_incorrecto+1;
                                    
                                }else if ($cadena_recortada=='400'){
                                    
                                   // $mensaje_retorna="No se pudo procesar";
                                    $no_se_pudo_procesar=$no_se_pudo_procesar+1;
                                    
                                }else{
                                    
                                   // $mensaje_retorna="Error Desconocido Vuelva a Intentarlo.";
                                    $error_desconocido=$error_desconocido+1;
                                }
                                
                                
                                
                            }
                            
                        }
                        
                        
                    }
                    
                }
            
                
                
                
                
                //para extras
                
                
                if(!empty($_extras)){
                    
                    foreach($_extras as $id  )
                    {
                        //$i++;
                        
                        $_celular = $id;
                         
                                
                                $cadena_recortada=$this->comsumir_mensaje_plus($_celular, "", $_txt1, $_txt2, $_txt3, $_txt4);
                                
                                
                                if($cadena_recortada=='100'){
                                    
                                    
                                    //$mensaje_retorna="Enviado Correctamente";
                                    $enviado_correctamente=$enviado_correctamente+1;
                                    
                                }else if ($cadena_recortada=='101'){
                                    
                                    //$mensaje_retorna="Despacho en Cola";
                                    $despacho_en_cola=$despacho_en_cola+1;
                                    
                                }else if ($cadena_recortada=='200'){
                                    
                                    //$mensaje_retorna="Estructura no Válida";
                                    $estructura_no_valida=$estructura_no_valida+1;
                                    
                                }else if ($cadena_recortada=='201'){
                                    
                                    //$mensaje_retorna="Método no Existe";
                                    $metodo_no_existe=$metodo_no_existe+1;
                                    
                                }else if ($cadena_recortada=='202'){
                                    
                                    //$mensaje_retorna="Parámetros Incompletos";
                                    $parametros_incompletos=$parametros_incompletos+1;
                                    
                                }else if ($cadena_recortada=='302'){
                                    
                                    //$mensaje_retorna="Cliente no Existe";
                                    $cliente_no_existe=$cliente_no_existe+1;
                                    
                                }else if ($cadena_recortada=='303'){
                                    
                                    //$mensaje_retorna="Mensaje muy Grande";
                                    $mensaje_muy_grande=$mensaje_muy_grande+1;
                                    
                                }else if ($cadena_recortada=='307'){
                                    
                                    //$mensaje_retorna="Cliente no tiene Servicio Online";
                                    $cliente_no_tiene_servicio_online=$cliente_no_tiene_servicio_online+1;
                                    
                                }else if ($cadena_recortada=='309'){
                                    
                                    //$mensaje_retorna="Token Inválido";
                                    $token_invalido=$token_invalido+1;
                                    
                                }else if ($cadena_recortada=='310'){
                                    
                                    //$mensaje_retorna="Shortcode no disponible para el Cliente";
                                    $sahrcode_no_disponible=$sahrcode_no_disponible+1;
                                    
                                }else if ($cadena_recortada=='311'){
                                    
                                    //$mensaje_retorna="Acceso Remoto no Permitido";
                                    $acceso_remoto_no_permitido=$acceso_remoto_no_permitido+1;
                                    
                                }else if ($cadena_recortada=='312'){
                                    
                                    //$mensaje_retorna="Teléfono Destino en Lista Negra";
                                    $telefono_destino_en_lista_negra=$telefono_destino_en_lista_negra+1;
                                    
                                }else if ($cadena_recortada=='313'){
                                    
                                    //$mensaje_retorna="Mensaje no Asignado";
                                    $mensaje_no_asignado=$mensaje_no_asignado+1;
                                    
                                }else if ($cadena_recortada=='314'){
                                    
                                    //$mensaje_retorna="Data Variable no coincide con parámetro enviados";
                                    $data_variable_error_parametros=$data_variable_error_parametros+1;
                                    
                                }else if ($cadena_recortada=='315'){
                                    
                                    //$mensaje_retorna="Teléfono Incorrecto";
                                    $telefono_incorrecto=$telefono_incorrecto+1;
                                    
                                }else if ($cadena_recortada=='400'){
                                    
                                    // $mensaje_retorna="No se pudo procesar";
                                    $no_se_pudo_procesar=$no_se_pudo_procesar+1;
                                    
                                }else{
                                    
                                    // $mensaje_retorna="Error Desconocido Vuelva a Intentarlo.";
                                    $error_desconocido=$error_desconocido+1;
                                }
                                
                        
                    }
                    
                }
                
            
            //resumen devuelto por message plus
            
            
            if($enviado_correctamente>0){
                $mensaje_retorna .=" Enviados Correctamente: ".$enviado_correctamente;
            }
            
            if ($despacho_en_cola>0){
                $mensaje_retorna .=" Despacho en Cola: ".$despacho_en_cola;
            }
            
            if ($estructura_no_valida>0){
                $mensaje_retorna .=" Estructura no Válida: ".$estructura_no_valida;
            }
            
            if ($metodo_no_existe>0){
                $mensaje_retorna .=" Método no Existe: ".$metodo_no_existe;
            } 
            
            if ($parametros_incompletos>0){
                $mensaje_retorna .=" Parámetros Incompletos: ".$parametros_incompletos;
            }
            
            if ($cliente_no_existe>0){
                $mensaje_retorna .=" Cliente no Existe: ".$cliente_no_existe;
            }
            
            if ($mensaje_muy_grande>0){
                
                $mensaje_retorna .=" Mensaje muy Grande: ".$mensaje_muy_grande;
            }
            
            if ($cliente_no_tiene_servicio_online>0){
                $mensaje_retorna .=" Cliente no tiene Servicio Online: ".$cliente_no_tiene_servicio_online;
            }
            
            if ($token_invalido>0){
                $mensaje_retorna .=" Token Inválido: ".$token_invalido;
            }
            
            if ($sahrcode_no_disponible>0){
                $mensaje_retorna .=" Shortcode no disponible para el Cliente: ".$sahrcode_no_disponible;
            }
            
            
            if ($acceso_remoto_no_permitido>0){
                $mensaje_retorna .=" Acceso Remoto no Permitido: ".$acceso_remoto_no_permitido;
             }
                
             if ($telefono_destino_en_lista_negra>0){
                
                 $mensaje_retorna .=" Teléfono Destino en Lista Negra: ".$telefono_destino_en_lista_negra;
               
            }
          if ($mensaje_no_asignado>0){
                
              $mensaje_retorna .=" Mensaje no Asignado: ".$mensaje_no_asignado;
                
            }
            
            
            if ($data_variable_error_parametros>0){
                
                $mensaje_retorna .=" Data Variable no coincide con parámetro enviados: ".$data_variable_error_parametros;
                
            }
            
            if ($telefono_incorrecto>0){
                
                $mensaje_retorna .=" Teléfono Incorrecto: ".$telefono_incorrecto;
                
            }
            
            if ($no_se_pudo_procesar>0){
                
                $mensaje_retorna .=" No se pudo procesar: ".$no_se_pudo_procesar;
                
            }
            
            if($error_desconocido>0)
            {
                
                $mensaje_retorna .=" Error Desconocido Vuelva a Intentarlo: ".$error_desconocido;
               
            }
                
            
            
            
            echo json_encode(array('respuesta'=>1,'mensaje'=>$mensaje_retorna));
            exit();
            
            
            
        }else{
            
            echo  json_encode(array('error'=>'Ingrese Destinatarios para el mensaje.'));
            exit();
        }
        
                
    }
    
    
    
    
    public function comsumir_mensaje_plus($celular, $nombres, $var1, $var2, $var3, $var4){
        
        $respuesta="";
        $nombres_final="";
        $var1_final="";
        $var2_final="";
        $var3_final="";
        $var4_final="";
        
        
        // quito el primero 0
        $celular_final=ltrim($celular, "0");
        
        // relleno espacios en blanco por _
        $nombres_final= str_replace(' ','_',$nombres);
        // $nombres_final= str_replace('Ñ','N',$nombres);
        // genero codigo de verificacion
        
        $var1_final= str_replace(' ','_',$var1);
        $var2_final= str_replace(' ','_',$var2);
        $var3_final= str_replace(' ','_',$var3);
        $var4_final= str_replace(' ','_',$var4);
       
        
        $variables="";
        $variables.="<pedido>";
        
        $variables.="<metodo>SMSEnvio</metodo>";
        $variables.="<id_cbm>767</id_cbm>";
        $variables.="<token>yPoJWsNjcThx2o0I</token>";
        $variables.="<id_transaccion>2002</id_transaccion>";
        $variables.="<telefono>$celular_final</telefono>";
        
        // poner el id_mensaje parametrizado en el sistema
        
        $variables.="<id_mensaje>22907</id_mensaje>";
        
        // poner 1 si va con variables
        // poner 0 si va sin variables y sin la etiquetas datos
        $variables.="<dt_variable>1</dt_variable>";
        $variables.="<datos>";
        
        
        /// el numero de valores va dependiendo del mensaje si usa 1 o 2 variables.
        $variables.="<valor>$var1_final</valor>";
        $variables.="<valor>$var2_final</valor>";
        $variables.="<valor>$var3_final</valor>";
        $variables.="<valor>$var4_final</valor>";
        $variables.="</datos>";
        $variables.="</pedido>";
        
        
        $SMSPlusUrl = "https://smsplus.net.ec/smsplus/ws/mensajeria.php?xml={$variables}";
        $ResponseData = file_get_contents($SMSPlusUrl);
        
        
        $xml = simplexml_load_string($ResponseData);
        
        //convert into json
        $json  = json_encode($xml);
        
        //convert into associative array
        $xmlArr = json_decode($json, true);
        
        $respuesta= $xmlArr['cod_respuesta'];
        
        return $respuesta;
        
        
        
    }
    
    
    
        
}

?>