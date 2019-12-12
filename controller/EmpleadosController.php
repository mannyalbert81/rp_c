<?php
class EmpleadosController extends ControladorBase{
    public function index(){
        session_start();
        $grupo_empleados = new GrupoEmpleadosModel();
        $estado = new EstadoModel();
        $departamentos = new DepartamentoModel();
        
        $tabladpto ="public.departamentos INNER JOIN public.estado
                     ON departamentos.id_estado = estado.id_estado";
        $wheredpto = "estado.nombre_estado = 'ACTIVO'";
        $iddpto = "departamentos.nombre_departamento";
        $resultdpto = $departamentos->getCondiciones("*", $tabladpto, $wheredpto, $iddpto);
        
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='empleados'";
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
        
        $tabla= "public.metodo_pago_empleados";
        $where= "1=1";
        $id = "metodo_pago_empleados.id_metodo_pago";
        
        $resultMet = $grupo_empleados->getCondiciones("*", $tabla, $where, $id);
        
        $this->view_Administracion("Empleados",array(
            "resultSet"=>$resultSet,
            "resultEst"=>$resultEst,
            "resultOfic"=>$resultOfic,
            "resultdpto"=>$resultdpto,
            "resultMet" =>$resultMet
        ));
    }
    
    public function GetGrupos()
    {
        session_start();
        $grupo_empleados = new GrupoEmpleadosModel();
        $tabla= "public.grupo_empleados INNER JOIN public.estado
                 ON grupo_empleados.id_estado_grupo_empleados = estado.id_estado
                 INNER JOIN public.oficina ON oficina.id_oficina = grupo_empleados.id_oficina";
        $where= "estado.nombre_estado='ACTIVO'";
        $id = "grupo_empleados.id_grupo_empleados";
        
        $resultSet = $grupo_empleados->getCondiciones("*", $tabla, $where, $id);
        
        echo json_encode($resultSet);
    }
    
    public function GetCargos()
    {
        session_start();
        $cargos = new CargosModel();
        $tabla= "public.cargos_empleados INNER JOIN public.estado
                 ON cargos_empleados.id_estado = estado.id_estado";
        $where= "estado.nombre_estado='ACTIVO'";
        $id = "cargos_empleados.nombre_cargo";
        
        $resultSet = $cargos->getCondiciones("*", $tabla, $where, $id);
        
        echo json_encode($resultSet);
    }
   
    
    public function AgregarEmpleado()
    {
      session_start();
      $empleados = new EmpleadosModel();
      $numero_cedula=$_POST['numero_cedula'];
      $cargo=$_POST['cargo'];
      $dpto=$_POST['dpto'];
      $nombre_empleado=$_POST['nombre_empleado'];
      $id_grupo=$_POST['id_grupo'];
      $estado=$_POST['estado'];
      $id_oficina=$_POST['id_oficina'];
      $id_metodo=$_POST['id_metodo'];
      $m_13=$_POST['m_13'];
      $m_14=$_POST['m_14'];
      $m_fr=$_POST['m_fr'];
      $funcion = "ins_empleado";
      $parametros = "'$cargo',
                     '$dpto',
                     '$numero_cedula',
                     '$nombre_empleado',
                     '$id_grupo',
                     '$estado',
                     '$id_oficina',
                     '$id_metodo',
                     '$m_14',
                     '$m_13',
                     '$m_fr'";
      $empleados->setFuncion($funcion);
      $empleados->setParametros($parametros);
      $resultado=$empleados->Insert();
      echo 1;
    }
    
    
    public function consulta_empleados(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        
        $id_estado = (isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado'] !=NULL)?$_REQUEST['id_estado']:'';
       
        $empleados = new EmpleadosModel();
                
        $where_to="";
        $columnas = " empleados.numero_cedula_empleados,
					  empleados.nombres_empleados,
                      empleados.id_cargo_empleado,
                      empleados.id_departamento,
                      grupo_empleados.nombre_grupo_empleados,
                      empleados.id_grupo_empleados,
                      empleados.id_estado,
                      estado.nombre_estado,
                      oficina.id_oficina,
                      oficina.nombre_oficina,
                      departamentos.nombre_departamento,
                      cargos_empleados.nombre_cargo,
                      empleados.id_metodo_pago,
                      metodo_pago_empleados.nombre_metodo_pago,
                      empleados.mensualizado_decimo_cuarto_empleados,
			          empleados.mensualizado_decimo_tercero_empleados,
			          empleados.mensualizado_fondos_de_reserva_empleados";
        
        $tablas = "public.empleados INNER JOIN public.grupo_empleados
                   ON grupo_empleados.id_grupo_empleados = empleados.id_grupo_empleados
                   INNER JOIN public.estado 
                   ON empleados.id_estado = estado.id_estado
                   INNER JOIN public.oficina
                   ON oficina.id_oficina = empleados.id_oficina
                   INNER JOIN public.departamentos
                   ON empleados.id_departamento = departamentos.id_departamento
                   INNER JOIN public.cargos_empleados
                   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo
                   INNER JOIN public.metodo_pago_empleados
                   ON metodo_pago_empleados.id_metodo_pago = empleados.id_metodo_pago";
        
        
        $where    = "empleados.id_estado=".$id_estado;
        
        $id       = "empleados.id_empleados";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND (empleados.numero_cedula_empleados LIKE '".$search."%' OR empleados.nombres_empleados ILIKE '".$search."%' OR cargos_empleados.nombre_cargo ILIKE '".$search."%' OR departamentos.nombre_departamento ILIKE '".$search."%' 
                 OR grupo_empleados.nombre_grupo_empleados ILIKE '".$search."%')";
                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$empleados->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$empleados->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
            $count_query   = $cantidadResult;
            $total_pages = ceil($cantidadResult/$per_page);
            
            
            if($cantidadResult>0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:570px; overflow-y:scroll;">';
                $html.= "<table id='tabla_empleados' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 15px;"></th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Cédula</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Nombres</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Cargo</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Departamento</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Oficina</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Grupo</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Metodo Pago</th>';
                
                
                if($id_rol==48){
                    
                    $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                    $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                    
                }
                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    $i++;
                    $html.='<tr>';
                    $html.='<td style="font-size: 14px;">'.$i.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->numero_cedula_empleados.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombres_empleados.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_cargo.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_departamento.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_oficina.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_grupo_empleados.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_metodo_pago.'</td>';
                    if($id_rol==48){
                        
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" 
                        onclick="EditarEmpleado(&quot;'.$res->numero_cedula_empleados.'&quot;,&quot;'.$res->nombres_empleados.'&quot;,'.$res->id_cargo_empleado.',
                        '.$res->id_departamento.',&quot;'.$res->id_grupo_empleados.'&quot; , '.$res->id_estado.', '.$res->id_oficina.',
                        '.$res->id_metodo_pago.',&quot;'.$res->mensualizado_decimo_cuarto_empleados.'&quot; ,&quot;'.$res->mensualizado_decimo_tercero_empleados.'&quot; ,
                        &quot;'.$res->mensualizado_fondos_de_reserva_empleados.'&quot;)"><i class="glyphicon glyphicon-edit"></i></button></span></td>';
                    if($res->nombre_estado=="ACTIVO")
                    {
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="EliminarEmpleado('.$res->numero_cedula_empleados.')"><i class="glyphicon glyphicon-trash"></i></button></span></td>';
                    }
                    }
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_empleados("index.php", $page, $total_pages, $adjacents,"load_empleados").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay empleados registrados...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
    }
    
    public function paginate_empleados($reload, $page, $tpages, $adjacents,$funcion='') {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='$funcion(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$funcion($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    public function AutocompleteCedula(){
        
        $empleados = new EmpleadosModel();
        
        if(isset($_GET['term'])){
            
            $cedula_empleado = $_GET['term'];
            
            $resultSet=$empleados->getBy("empleados.numero_cedula_empleados LIKE '$cedula_empleado%'");
            
            $respuesta = array();
            
            if(!empty($resultSet)){
                
                if(count($resultSet)>0){
                    
                    foreach ($resultSet as $res){
                        
                        $_cls_usuarios = new stdClass;
                        $_cls_usuarios->value=$res->numero_cedula_empleados;
                        $nombres= (string)$res->nombres_empleados;
                        $nombresep = explode(" ", $nombres);
                        $_cls_usuarios->label=$res->numero_cedula_empleados.' - '.$nombresep[0].' '.$nombresep[2];
                        $_cls_usuarios->nombre=$nombresep[0].' '.$nombresep[1];
                        
                        $respuesta[] = $_cls_usuarios;
                    }
                    
                    echo json_encode($respuesta);
                }
                
            }else{
                echo '[{"id":0,"value":"sin datos"}]';
            }
            
        }else{
            
            $cedula_usuarios = (isset($_POST['term']))?$_POST['term']:'';
            
            $columna = "empleados.numero_cedula_empleados,
					  empleados.nombres_empleados,
                      empleados.id_cargo_empleado,
                      empleados.id_departamento,
                      empleados.id_grupo_empleados,
                      empleados.id_estado,
                      empleados.id_oficina";
            
            $tablas = "public.empleados";
            
            $where = "empleados.numero_cedula_empleados = '$cedula_usuarios'";
            
            $resultSet=$empleados->getCondiciones($columna,$tablas,$where,"empleados.numero_cedula_empleados");
            
            $respuesta = new stdClass();
            
            if(!empty($resultSet)){
                
                $respuesta->numero_cedula_empleados = $resultSet[0]->numero_cedula_empleados;
                $nombres = (string)$resultSet[0]->nombres_empleados;
                $nombresep = explode(" ", $nombres);
                $respuesta->nombre_empleados = $nombresep[0].' '.$nombresep[1];
                $respuesta->apellidos_empleados = $nombresep[2].' '.$nombresep[3];;
                $respuesta->cargo_empleados = $resultSet[0]->id_cargo_empleado;
                $respuesta->dpto_empleados = $resultSet[0]->id_departamento;
                $respuesta->id_grupo_empleados = $resultSet[0]->id_grupo_empleados;
                $respuesta->id_estado = $resultSet[0]->id_estado;
                $respuesta->id_oficina = $resultSet[0]->id_oficina;
                
            }
            
            echo json_encode($respuesta);
            
        }
        
    }
    
    public function EliminarValor()
    {
        session_start();
        $empleados = new EmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='empleados' AND estado.nombre_estado = 'INACTIVO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $numero_cedula = $_POST['numero_cedula'];
        $where = "numero_cedula_empleados='".$numero_cedula."'";
        $tabla = "empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $empleados->UpdateBy($colval, $tabla, $where);
    }
    
    
    /************************ BEGIN GENERACION DE CUENTAS CONTABLES ANTICIPOS *********************************************/
    public function index1(){
        
        session_start();
        
        $Empleado = new EmpleadosModel();
        
        $id_rol   = $_SESSION['id_rol'];        
        
        if(empty( $_SESSION['usuario_usuarios'])){
            
            $this->redirect("Usuarios","sesion_caducada");
            exit();
        }else{
            
            $nombre_controladores = "NominaAnticiposCuentas";
            $resultPer = $Empleado->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            if (empty($resultPer)){
                
                $this->view("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso Pagos"
                    
                ));
                exit();
                
            }else{
                
                $this->view_Contable("AnticipoCuentas",array(                    
                    
                ));
                exit();
            }
            
        }
        
    }
    
    /***
     * 
     */
    public function ListarEmpleadosCuentas(){
        
        $Empleado = new EmpleadosModel();
        $respuesta= array();
        $columnas1  = "aa.id_empleados, aa.nombres_empleados";
        $tablas1    = "empleados aa
                    INNER JOIN estado bb
                    ON bb.id_estado = aa.id_estado";
        $where1     = "bb.tabla_estado = 'empleados'
                    AND bb.nombre_estado = 'ACTIVO'";
        $id1        = "aa.nombres_empleados";
        
        $rsConsulta1    = $Empleado->getCondiciones($columnas1, $tablas1, $where1, $id1);
        
        if(!empty($rsConsulta1)){
            $respuesta['datos'] = $rsConsulta1;
        }
        
        echo json_encode($respuesta);
        
    }
    
    public function BuscarEmpleadoCuentas(){
        
        $_id_empleados = $_POST['id_empleados'];
        $_codigo_plan_cuentas = $_POST['codigo_cuenta'];
        $Empleado  = new EmpleadosModel();
        $Plancuentas   = new PlanCuentasModel();
        $respuesta = array();
        
        $columnasCuentas    = " 1 existe";
        $tablasCuentas      = " public.empleados_cuentas_contables";
        $whereCuentas       = " id_empleados = $_id_empleados";
        
        $rsConsultaCuentas  = $Empleado->getCondicionesSinOrden($columnasCuentas, $tablasCuentas, $whereCuentas, "");
        if( !empty($rsConsultaCuentas) ){
            
            $respuesta['existe'] = 'EXISTE';
            
        }else{
            
            $columnas1  = " aa.id_empleados, aa.numero_cedula_empleados,
                     aa.nombres_empleados,bb.nombre_cargo";
            $tablas1    = " empleados aa
                    INNER JOIN cargos_empleados bb
                    ON bb.id_cargo = aa.id_cargo_empleado";
            $where1     = "1 = 1
                    AND aa.id_empleados = $_id_empleados";
            $limit1     = "";
            
            $rsConsulta1= $Empleado->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit1);
            
            if( !empty($rsConsulta1) ){
                $respuesta['empleado'] = $rsConsulta1;
            }
            
            $columnas2  = " id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas";
            $tablas2    = " public.plan_cuentas";
            $where2     = " id_entidades = 1
                    AND nivel_plan_cuentas = 5
                    AND codigo_plan_cuentas LIKE '$_codigo_plan_cuentas%'";
            $id2     = "codigo_plan_cuentas";
            
            $rsConsulta2 = $Plancuentas->getCondiciones($columnas2, $tablas2, $where2, $id2);
            
            if( !empty($rsConsulta2) ){
                /* tomar el valor siguiente para la cuenta contable*/
                $cantidad = sizeof($rsConsulta2);
                /* recuperamos la ultima fila */
                $fila = $rsConsulta2[$cantidad-1];
                /* convertimos el codigo consultado */
                $codigo_consulta    = $fila->codigo_plan_cuentas;
                $codigo_array   = explode(".", trim($codigo_consulta,"."));
                $index_codigo   = $codigo_array[sizeof($codigo_array)-1];
                $index_codigo   = (int)$index_codigo + 1;
                $codigo_a_generar = $_codigo_plan_cuentas.".".$index_codigo;
                $array_cuenta   = array('codigo'=>$codigo_a_generar);
                $respuesta['cuenta'] = $array_cuenta;
                
            }
        }
                       
        echo json_encode($respuesta);
        
    }
    
    public function IngresarCuenta(){
        
        $_id_empleados = $_POST['id_empleados'];
        $_codigo_cuenta = $_POST['cuenta_registrar'];
        
        $Empleado  = new EmpleadosModel();
        
        $_funcion = "ins_con_empleados_cuenta_contable";
        $_parametros = "$_id_empleados,'$_codigo_cuenta'";
        $_queryFuncion = $Empleado->getconsultaPG($_funcion, $_parametros);
        
        $Empleado->llamarconsultaPG($_queryFuncion);
        
        $error = pg_last_error();
        if( !empty($error) ){
            
            echo "<message>Error al ingresar Cuenta Contable <message>";
        }
        
        echo json_encode(array("respuesta"=>"OK"));
        
    }
    
    /************************ END GENERACION DE CUENTAS CONTABLES ANTICIPOS *********************************************/
        
}

?>