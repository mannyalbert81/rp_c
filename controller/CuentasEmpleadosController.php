<?php
class CuentasEmpleadosController extends ControladorBase{
    public function index(){
        session_start();
        $empleados = new EmpleadosModel();
        
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='CUENTAS_EMPLEADOS'";
        $idest = "estado.id_estado";
        $resultEst = $empleados->getCondiciones("*", $tablaest, $whereest, $idest);
        
        $tabla= "public.empleados INNER JOIN public.metodo_pago_empleados
                ON empleados.id_metodo_pago = metodo_pago_empleados.id_metodo_pago";
        $where= "metodo_pago_empleados.nombre_metodo_pago = 'CTA. BANCO'";
        $id = "empleados.nombres_empleados";
        
        $resultEmp = $empleados->getCondiciones("*", $tabla, $where, $id);
        
        $tabla= "public.tes_bancos";
        $where= "1=1";
        $id = "tes_bancos.id_bancos";
        
        $resultBc = $empleados->getCondiciones("*", $tabla, $where, $id);
        
        $this->view_Administracion("CuentasEmpleados",array(
            "resultEmp"=>$resultEmp,
            "resultEst"=>$resultEst,
            "resultBc"=>$resultBc
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
   
    
    public function AgregarCuenta()
    {
      session_start();
      $cuentas = new CuentasBancariasModel();
      $id_empleado=$_POST['id_empleado'];
      $nombre_banco=$_POST['nombre_banco'];
      $tipo_cuenta=$_POST['tipo_cuenta'];
      $numero_cuenta=$_POST['numero_cuenta'];
      $funcion = "ins_cuenta_bancaria";
      $parametros = "'$id_empleado',
                     '$nombre_banco',
                     '$tipo_cuenta',
                     '$numero_cuenta'";
      $cuentas->setFuncion($funcion);
      $cuentas->setParametros($parametros);
      $resultado=$cuentas->Insert();
      echo 1;
    }
    
    public function consulta_cuentas(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $id_estado = (isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado'] !=NULL)?$_REQUEST['id_estado']:'';
        
        $empleados = new EmpleadosModel();
                
        $where_to="";
        $columnas = " empleados.nombres_empleados,
                      empleados.id_empleados,
                      tes_bancos.nombre_bancos,
                      tes_bancos.id_bancos,
                      cuentas_bancarias_empleados.tipo_cuenta,
                      cuentas_bancarias_empleados.numero_cuenta,
                      cuentas_bancarias_empleados.id_cuenta,
                      estado.nombre_estado";
        
        $tablas = "public.empleados INNER JOIN public.cuentas_bancarias_empleados
                   ON empleados.id_empleados = cuentas_bancarias_empleados.id_empleado
                   INNER JOIN public.estado
                   ON cuentas_bancarias_empleados.id_estado = estado.id_estado
                   INNER JOIN public.tes_bancos
                   ON cuentas_bancarias_empleados.id_bancos = tes_bancos.id_bancos";
        
        
        $where    = "cuentas_bancarias_empleados.id_estado=".$id_estado;
        
        $id       = "empleados.nombres_empleados";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND (CAST(empleados.numero_cedula_empleados AS TEXT) LIKE '".$search."%' OR empleados.nombres_empleados ILIKE '".$search."%' OR cuentas_bancarias_empleados.nombre_banco ILIKE '".$search."%' OR cuentas_bancarias_empleados.tipo_cuenta ILIKE '".$search."%' 
                 OR cuentas_bancarias_empleados.numero_cuenta ILIKE '".$search."%')";
                
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
                $html.='<th style="text-align: left;  font-size: 15px;">Empleado</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Banco</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Cuenta</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Número</th>';
                
                if($id_rol==1){
                    
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
                    $html.='<td style="font-size: 14px;">'.$res->nombres_empleados.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_bancos.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->tipo_cuenta.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->numero_cuenta.'</td>';
                    if($id_rol==1){
                        
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="EditarCuenta('.$res->id_empleados.',&quot;'.$res->id_bancos.'&quot;,&quot;'.$res->tipo_cuenta.'&quot; ,&quot;'.$res->numero_cuenta.'&quot;)"><i class="glyphicon glyphicon-edit"></i></button></span></td>';
                    if($res->nombre_estado=="ACTIVO")
                    {
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="EliminarCuenta('.$res->id_cuenta.')"><i class="glyphicon glyphicon-trash"></i></button></span></td>';
                    }
                    }
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_cuentas("index.php", $page, $total_pages, $adjacents,"load_cuentas").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay cuentas registradas...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
    }
    
    public function paginate_cuentas($reload, $page, $tpages, $adjacents,$funcion='') {
        
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
            
           $resultSet=$empleados->getBy("CAST(empleados.numero_cedula_empleados AS TEXT) LIKE '$cedula_empleado%'");
            
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
            
            $where = "empleados.numero_cedula_empleados = $cedula_usuarios";
            
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
        $cuentas = new CuentasBancariasModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='CUENTAS_EMPLEADOS' AND estado.nombre_estado = 'INACTIVO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $id_cuenta = $_POST['id_cuenta'];
        $where = "id_cuenta=".$id_cuenta;
        $tabla = "cuentas_bancarias_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $cuentas->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
        
}

?>