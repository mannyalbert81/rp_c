<?php

class LibroMayorController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
	    $proveedores=new ProveedoresModel();
					//Conseguimos todos los usuarios
	    $resultSet=$proveedores->getAll("id_proveedores");
		
		session_start();
        
	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "ReporteMayor";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $proveedores->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer)){
				
				$this->view_Contable("LibroMayor",array(
				    "resultSet"=>$resultSet
			
				));
		
				
			}else{
			    
			    $this->view_Contable("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Proveedores"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	

	
	public function paginate_grupos($reload, $page, $tpages, $adjacents,$funcion='') {
	    
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


	
	public function AutocompleteCodigo(){
	    
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    
	    $usuarios = new UsuariosModel();
	    $plan_cuentas = new PlanCuentasModel();
	    
	    if(isset($_GET['term'])){
	        
	        $codigo_plan_cuentas = $_GET['term'];
	        
	        $columnas ="plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	        $where ="plan_cuentas.codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
	        $id ="plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	              
	            foreach ($resultSet as $res){
	                    
	                    $_cuenta = new stdClass;
	                    $_cuenta->id=$res->id_plan_cuentas;
	                    $_cuenta->value=$res->codigo_plan_cuentas;
	                    $_cuenta->label=$res->codigo_plan_cuentas;
	                    $_cuenta->nombre=$res->nombre_plan_cuentas;
	                    
	                    $respuesta[] = $_cuenta;
	                }
	                
	                echo json_encode($respuesta);
	           
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }else{
	        
	        $codigo_plan_cuentas = (isset($_POST['term']))?$_POST['term']:'';
	        
	        $columnas ="plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	        $where ="plan_cuentas.codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
	        $id ="plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	            
	            foreach ($resultSet as $res){
	                
	                $_cuenta = new stdClass;
	                $_cuenta->id=$res->id_plan_cuentas;
	                $_cuenta->value=$res->codigo_plan_cuentas;
	                $_cuenta->label=$res->codigo_plan_cuentas;
	                $_cuenta->nombre_cuenta=$res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cuenta;
	            }
	            
	            echo json_encode($respuesta);
	            
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }
	    
	    
	    
	}
	
	public function AutocompleteNombre(){
	    
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    
	    $plan_cuentas = new PlanCuentasModel();
	    
	    if(isset($_GET['term'])){
	        
	        $nombre_plan_cuentas = $_GET['term'];
	        
	        $columnas ="plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	        $where ="plan_cuentas.nombre_plan_cuentas LIKE '$nombre_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
	        $id ="plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	            
	            foreach ($resultSet as $res){
	                
	                $_cuenta = new stdClass;
	                $_cuenta->id=$res->id_plan_cuentas;
	                $_cuenta->value=$res->nombre_plan_cuentas;
	                $_cuenta->label=$res->nombre_plan_cuentas;
	                $_cuenta->nombre=$res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cuenta;
	            }
	            
	            echo json_encode($respuesta);
	            
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }else{
	        
	        $nombre_plan_cuentas = (isset($_POST['term']))?$_POST['term']:'';
	        
	        $columnas ="plan_cuentas.id_plan_cuentas,plan_cuentas.nombre_plan_cuentas,plan_cuentas.codigo_plan_cuentas";
	        $tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
	        $where ="plan_cuentas.nombre_plan_cuentas LIKE '$nombre_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
	        $id ="plan_cuentas.codigo_plan_cuentas";
	        
	        
	        $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet) && count($resultSet)>0){
	            
	            foreach ($resultSet as $res){
	                
	                $_cuenta = new stdClass;
	                $_cuenta->id=$res->id_plan_cuentas;
	                $_cuenta->value=$res->codigo_plan_cuentas;
	                $_cuenta->label=$res->codigo_plan_cuentas;
	                $_cuenta->nombre_cuenta=$res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cuenta;
	            }
	            
	            echo json_encode($respuesta);
	            
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }
	    
	    
	    
	}
	
	
	public function mayorContable(){
	    
	    session_start();
	    
	    $entidades = new EntidadesModel();
	    
	    $datos_empresa = array();
	    $datos_detalle = array();
	    
	    $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
	    
	    if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0){
	        //llenar nombres con variables que va en html de reporte
	        $datos_empresa['NOMBREEMPRESA']=$rsdatosEmpresa[0]->nombre_entidades;
	        $datos_empresa['DIRECCIONEMPRESA']=$rsdatosEmpresa[0]->direccion_entidades;
	        $datos_empresa['TELEFONOEMPRESA']=$rsdatosEmpresa[0]->telefono_entidades;
	        $datos_empresa['RUCEMPRESA']=$rsdatosEmpresa[0]->ruc_entidades;
	        $datos_empresa['FECHAEMPRESA']=date('Y-m-d H:i');
	        $datos_empresa['USUARIOEMPRESA']=(isset($_SESSION['usuario_usuarios']))?$_SESSION['usuario_usuarios']:'';
	    }
	    
	    /*consulta para traer las cuentas*/
	    
	    $query_columnas = "SELECT id_plan_cuentas,nombre_plan_cuentas,codigo_plan_cuentas  ";
	    $query_from = " FROM vw_mayor_contable";
	    $query_where = " WHERE 1 = 1";
	    $query_group = " GROUP BY id_plan_cuentas,nombre_plan_cuentas,codigo_plan_cuentas";
	    $query_orden = " ORDER BY id_plan_cuentas";
	    
	    /** tomar datos de la vista **/
	    $_fecha_desde = $_POST['fecha_desde'];
	    $_fecha_hasta = $_POST['fecha_hasta'];
	    //tomar datos vista
	    //$_anio = (isset($_POST['anio_l_mayor']) && ((int)$_POST['anio_l_mayor']) > 0 )?$_POST['anio_l_mayor']: date('Y');
	    $_mes = (isset($_POST['mes_l_mayor']) &&  ((int)$_POST['mes_l_mayor'] > 0) )?$_POST['mes_l_mayor']: 0;
	    $_id_cuenta = (isset($_POST['id_cuenta']))?$_POST['id_cuenta']:0;
	   
	    if($_id_cuenta > 0){
	        $query_where.=" AND id_plan_cuentas = $_id_cuenta";
	    }
	    
	    if($_mes > 0){
	        
	        $query_where .= " AND  mes = $_mes ";
	        
	    }
	    
	    if( !empty($_fecha_desde) &&  !empty($_fecha_hasta) ){
	        $query_where .= " AND date(fecha_mayor) BETWEEN '$_fecha_desde'  AND  '$_fecha_hasta' ";
	    }
	    
	    /* para where parametros */
	    //$query_where .= " AND  anio = $_anio ";
	    
	    /*genera consulta*/
	    $query = "";
	    $query = $query_columnas.$query_from.$query_where.$query_group.$query_orden;
	    	    
	    $rscuentas = $entidades->enviaquery($query);
	    	    
	    /*para dibujar tabla*/
	    $tablaMayor = "<table>";
	    
	    /*para consulta de id_pln_cuentas*/
	    $query_columnas = " SELECT * ";
	    
	    if(!empty( $rscuentas ) && count($rscuentas)>0){
	        
	        foreach ( $rscuentas as $res){
	            
	            $wherexcuentas = " AND id_plan_cuentas = ".$res->id_plan_cuentas;
	            $queryxcuenta = " SELECT * FROM  vw_mayor_contable ".$query_where.$wherexcuentas." ORDER BY fecha_mayor";
	            
	            $rsDetalle = $entidades->enviaquery($queryxcuenta);
	            
	            $tablaMayor .= "<tr class=\"grupocuenta\">";
	            $tablaMayor .= "<td colspan=\"2\">Codigo: $res->codigo_plan_cuentas</td>";
	            $tablaMayor .= "<td colspan=\"2\">Cuenta: $res->nombre_plan_cuentas</td>";
	            $tablaMayor .= "<td colspan=\"4\"></td>";
	            $tablaMayor .= "</tr>";
	            $tablaMayor .= "<tr class=\"titulos\">";
	            $tablaMayor .= "<td>Fecha:</td>";
	            $tablaMayor .= "<td>Num Comprobante</td>";
	            $tablaMayor .= "<td>Concepto:</td>";
	            $tablaMayor .= "<td>Descripcion</td>";
	            $tablaMayor .= "<td>Referencia/Doc</td>";
	            $tablaMayor .= "<td>Debe</td>";
	            $tablaMayor .= "<td>Haber</td>";
	            $tablaMayor .= "<td>Saldo</td>";
	            $tablaMayor .= "</tr>"; 
	            
	            /*variables operaciones*/
	            $mesvariable = 0;
	            $indexDetalle = 0;
	            $totalDetalle = count($rsDetalle);
	            
	            /*operaciones*/
	            $sumaDebe = 0.0;
	            $sumaHaber = 0.0;
	            $sumaTotalDebe = 0.0;
	            $sumaTotalHaber = 0.0;
	            
	            foreach($rsDetalle as $resDet){
	                
	                $indexDetalle +=1;
	                
	                $sumaDebe += $resDet->debe_mayor;
	                $sumaHaber += $resDet->haber_mayor;
	                
	                if($mesvariable != $resDet->mes){
	                    
	                    $mesvariable = $resDet->mes;
	                    
	                    $tablaMayor .= "<tr>";
	                    $tablaMayor .= "<td>$resDet->fecha_mayor</td>";
	                    $tablaMayor .= "<td>$resDet->numero_ccomprobantes</td>";
	                    $tablaMayor .= "<td>$resDet->concepto_ccomprobantes</td>";
	                    $tablaMayor .= "<td>$resDet->descripcion_dcomprobantes</td>";
	                    $tablaMayor .= "<td>$resDet->referencia_doc_ccomprobantes</td>";
	                    $tablaMayor .= "<td class=\"numero\" >$ ". number_format($resDet->debe_mayor, 2, ',', ' ')."</td>";
	                    $tablaMayor .= "<td class=\"numero\" >$ ". number_format($resDet->haber_mayor, 2, ',', ' ')."</td>";
	                    $tablaMayor .= "<td class=\"numero\" >$ ". number_format($resDet->saldo_mayor, 2, ',', ' ')."</td>";
	                    $tablaMayor .= "</tr>";
	                    
	                    if( $indexDetalle < $totalDetalle ){
	                        
	                        $messiguiente = $rsDetalle[$indexDetalle]->mes;
	                        
	                        if( $mesvariable != $messiguiente ){
	                            
	                            $tituloMes = $resDet->mes.'/'.$resDet->anio;
	                            
	                            $tablaMayor .= "<tr class=\"grupototales\">";
	                            $tablaMayor .= "<td colspan=\"4\" align=\"right\">$tituloMes</td>";
	                            $tablaMayor .= "<td colspan=\"1\" class=\" ul\">Total:</td>";
	                            $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($sumaDebe, 2, ',', ' ')."</td>";
	                            $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($sumaHaber, 2, ',', ' ')."</td>";
	                            $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($resDet->saldo_mayor, 2, ',', ' ')."</td>";
	                            $tablaMayor .= "</tr>";
	                            
	                            $sumaTotalDebe += $sumaDebe;
	                            $sumaTotalHaber += $sumaHaber;
	                            $sumaHaber = 0.0;
	                            $sumaHaber = 0.0;
	                            
	                        }
	                    }else{
	                        
	                        $tituloMes = $resDet->mes.'/'.$resDet->anio;
	                        
	                        $tablaMayor .= "<tr class=\"grupototales\">";
	                        $tablaMayor .= "<td colspan=\"4\" align=\"right\">$tituloMes</td>";
	                        $tablaMayor .= "<td colspan=\"1\" class=\" ul\">Total:</td>";
	                        $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($sumaDebe, 2, ',', ' ')."</td>";
	                        $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($sumaHaber, 2, ',', ' ')."</td>";
	                        $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($resDet->saldo_mayor, 2, ',', ' ')."</td>";
	                        $tablaMayor .= "</tr>";
	                        
	                        $sumaTotalDebe += $sumaDebe;
	                        $sumaTotalHaber += $sumaHaber;
	                        $sumaHaber = 0.0;
	                        $sumaHaber = 0.0;
	                    }
	                       
	                   
	                    
	                }else{
	                    
	                    $tablaMayor .= "<tr>";
	                    $tablaMayor .= "<td>$resDet->fecha_mayor</td>";
	                    $tablaMayor .= "<td>$resDet->numero_ccomprobantes</td>";
	                    $tablaMayor .= "<td>$resDet->concepto_ccomprobantes</td>";
	                    $tablaMayor .= "<td>$resDet->descripcion_dcomprobantes</td>";
	                    $tablaMayor .= "<td>$resDet->referencia_doc_ccomprobantes</td>";
	                    $tablaMayor .= "<td class=\"numero\" >$&nbsp;". number_format($resDet->debe_mayor, 2, ',', ' ')."</td>";
	                    $tablaMayor .= "<td class=\"numero\" >$&nbsp;". number_format($resDet->haber_mayor, 2, ',', ' ')."</td>";
	                    $tablaMayor .= "<td class=\"numero\" >$&nbsp;". number_format($resDet->saldo_mayor, 2, ',', ' ')."</td>";
	                    $tablaMayor .= "</tr>";
	                    
	                    if( $indexDetalle < $totalDetalle ){
	                        
	                        $messiguiente = $rsDetalle[$indexDetalle]->mes;
	                        
	                        if( $mesvariable != $messiguiente ){
	                            
	                            $tituloMes = $resDet->mes.'/'.$resDet->anio;
	                            
	                            $tablaMayor .= "<tr class=\"grupototales\">";
	                            $tablaMayor .= "<td colspan=\"4\" align=\"right\">$tituloMes</td>";
	                            $tablaMayor .= "<td colspan=\"1\" class=\" ul\">Total:</td>";
	                            $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($sumaDebe, 2, ',', ' ')."</td>";
	                            $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($sumaHaber, 2, ',', ' ')."</td>";
	                            $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($resDet->saldo_mayor, 2, ',', ' ')."</td>";
	                            $tablaMayor .= "</tr>";
	                            
	                            $sumaTotalDebe += $sumaDebe;
	                            $sumaTotalHaber += $sumaHaber;
	                            $sumaHaber = 0.0;
	                            $sumaHaber = 0.0;
	                            
	                        }
	                    }else{
	                        
	                        $tituloMes = $resDet->mes.'/'.$resDet->anio;
	                        
	                        $tablaMayor .= "<tr class=\"grupototales\">";
	                        $tablaMayor .= "<td colspan=\"4\" align=\"right\">$tituloMes</td>";
	                        $tablaMayor .= "<td colspan=\"1\" class=\" ul\" >Total:</td>";
	                        $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($sumaDebe, 2, ',', ' ')."</td>";
	                        $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($sumaHaber, 2, ',', ' ')."</td>";
	                        $tablaMayor .= "<td class=\"numero ul\" >$&nbsp;". number_format($resDet->saldo_mayor, 2, ',', ' ')."</td>";
	                        $tablaMayor .= "</tr>";
	                        
	                        $sumaTotalDebe += $sumaDebe;
	                        $sumaTotalHaber += $sumaHaber;
	                        $sumaHaber = 0.0;
	                        $sumaHaber = 0.0;
	                    }
	                    
	                }
	                
	                /*para final sumatoria*/
	                if($indexDetalle == $totalDetalle){
	                    
	                    $tablaMayor .= "<tr class=\"grupototales\">";
	                    $tablaMayor .= "<td colspan=\"4\" align=\"right\"></td>";
	                    $tablaMayor .= "<td colspan=\"1\" class=\" ul\" >Saldo Total:</td>";
	                    $tablaMayor .= "<td class=\"numero ul \" >$&nbsp;". number_format($sumaTotalDebe, 2, ',', ' ')."</td>";
	                    $tablaMayor .= "<td class=\"numero ul \" >$&nbsp;". number_format($sumaTotalHaber, 2, ',', ' ')."</td>";
	                    $tablaMayor .= "<td class=\"numero ul \" >$&nbsp;". number_format($resDet->saldo_mayor, 2, ',', ' ')."</td>";
	                    $tablaMayor .= "</tr>";
	                    
	                }
	            }
	            
	        }
	    }
	    
	    $tablaMayor .= "</table>";
	    
	   /*termina graficacion de la tabla*/
	    
	    /*datos para detalle de reporte*/
	    //por implementar
	    
	   	    
	    $datos_detalle = $tablaMayor;
	   
	    	    
	    $this->verReporte("MayorContable", array('datos_empresa'=>$datos_empresa,'datos_detalle'=>$datos_detalle));
	    
	    
	    
	}
	
	
	
	
	
}
?>