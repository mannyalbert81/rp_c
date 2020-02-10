<?php

class PresupuestosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}


	public function index(){
	    
	    $presupuestos = new PresupuestosModel();
	    
	   
	    session_start();
	    
	    if(empty( $_SESSION)){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        return;
	    }
	    
	    $nombre_controladores = "Presupuestos";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $presupuestos->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso Presupuestos"
	            
	        ));
	        exit();
	    }
	    
	    
	    $anio= array();
	
	    
	    $tiempo_anio=date("Y");;
	    
	    array_push($anio, $tiempo_anio);
	    
	   
	    
	   
	    $this->view_Contable("Presupuesto",array("anio"=>$anio,));
	    
	}
	

	
	public function AutocompleteCodigoCuentas(){
	    
	    session_start();
	    $_id_usuarios= $_SESSION['id_usuarios'];
	    $plan_cuentas = new PlanCuentasModel();
	    $codigo_plan_cuentas = $_GET['term'];
	    
	    $columnas ="plan_cuentas.codigo_plan_cuentas";
	    $tablas =" public.plan_cuentas";
	    $where ="plan_cuentas.codigo_plan_cuentas LIKE '$codigo_plan_cuentas%'";
	    $id ="plan_cuentas.codigo_plan_cuentas";
	    
	    
	    $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    
	    if(!empty($resultSet)){
	        
	        foreach ($resultSet as $res){
	            
	            $_respuesta[] = $res->codigo_plan_cuentas;
	        }
	        echo json_encode($_respuesta);
	    }
	    
	}
	
	public function DevuelveNombreCodigoCuentas(){
	    session_start();
	
	  
	    
	    $plan_cuentas = new PlanCuentasModel();
	    $codigo_plan_cuentas = $_POST['codigo_plan_cuentas'];
	    
	    
	    $columnas ="plan_cuentas.id_plan_cuentas,
				  plan_cuentas.nombre_plan_cuentas";
	    $tablas ="public.plan_cuentas";
	    $where ="plan_cuentas.codigo_plan_cuentas = '$codigo_plan_cuentas'";
	    $id ="plan_cuentas.codigo_plan_cuentas";
	    
	    
	    $resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    
	    $respuesta = new stdClass();
	    
	    if(!empty($resultSet)){
	        
	        $respuesta->nombre_plan_cuentas = $resultSet[0]->nombre_plan_cuentas;
	        $respuesta->id_plan_cuentas = $resultSet[0]->id_plan_cuentas;
	        
	        echo json_encode($respuesta);
	    }
	    
	}
	
	

	
	public function InsertarPresupuestos(){
	    
	    session_start();
	   
	    $presupuestos = new PresupuestosCabezaModel();
	    
	    $nombre_controladores = "Presupuestos";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $presupuestos->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    
	    
	    
	    $nombre_controladores = "Presupuestos";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $presupuestos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer)){
	        
	        
	        $_id_plan_cuentas =(isset($_POST['id_plan_cuentas'])) ? $_POST['id_plan_cuentas'] : 0;
	        $_usuario_usuarios=$_SESSION['nombre_usuarios'];
	        $_nombre_presupuestos_cabeza =(isset($_POST['nombre_presupuestos_cabeza'])) ? $_POST['nombre_presupuestos_cabeza'] : "";
	        $_id_presupuestos_cabeza =(isset($_POST['id_presupuestos_cabeza'])) ? $_POST['id_presupuestos_cabeza'] : 0;
	        $_valor_presupuestado_presupuestos_detalle =(isset($_POST['valor_presupuestado_presupuestos_detalle'])) ? $_POST['valor_presupuestado_presupuestos_detalle'] : "";
	        $_valor_ejecutado_presupuestos_detalle =(isset($_POST['valor_ejecutado_presupuestos_detalle'])) ? $_POST['valor_ejecutado_presupuestos_detalle'] : "";
	        $_mes_presupuestos_detalle =(isset($_POST['mes_presupuestos_detalle'])) ? $_POST['mes_presupuestos_detalle'] : "";
	        $_anio_presupuestos_detalle =(isset($_POST['anio_presupuestos_detalle'])) ? $_POST['anio_presupuestos_detalle'] : "";
	        $_total_presupuestos_cabeza =(isset($_POST['total_presupuestos_cabeza'])) ? $_POST['total_presupuestos_cabeza'] : 0.00;
	        
	        
	        
	        $funcion = "presupuestos";
	        $respuesta = 0 ;
	        $mensaje = "";
	        
	        if($_id_presupuestos_cabeza == 0){
	            
	            
	            
	            $parametros = " '$_id_plan_cuentas',
                                '$_nombre_presupuestos_cabeza',
                                '$_usuario_usuarios',
                                '$_total_presupuestos_cabeza',
                                '$_valor_presupuestado_presupuestos_detalle',
                                '$_valor_ejecutado_presupuestos_detalle',
                                '$_mes_presupuestos_detalle',
                                '$_anio_presupuestos_detalle'                                
                                 ";
	            
	            //echo $parametros; die();
	            
	            
	            $presupuestos->setFuncion($funcion);
	            $presupuestos->setParametros($parametros);
	            $resultado = $presupuestos->llamafuncionPG();
	            
	            
	            if(is_int((int)$resultado[0])){
	                
	                
	                $respuesta = $resultado[0];
	                $mensaje = "Presupuesto Ingresado Correctamente";
	            }
	            
   
	            
	        }elseif ($_id_presupuestos_cabeza > 0){
	            
	            
	            $columnas="
                        id_plan_cuentas='$_id_plan_cuentas',
                        nombre_presupuestos_cabeza='$_nombre_presupuestos_cabeza',
                        usuario_usuarios='$_usuario_usuarios',
						total_presupuestos_cabeza='$_total_presupuestos_cabeza',
						valor_presupuestado_presupuestos_detalle='$_valor_presupuestado_presupuestos_detalle',
						valor_ejecutado_presupuestos_detalle='$_valor_ejecutado_presupuestos_detalle',
						mes_presupuestos_detalle='$_mes_presupuestos_detalle',
						anio_presupuestos_detalle='$_anio_presupuestos_detalle'
						";
	            $tablas="public.presupuestos_cabeza, 
                        public.presupuestos_detalle";
	            $where="presupuestos_detalle.id_presupuestos_cabeza = presupuestos_cabeza.id_presupuestos_cabeza AND id_presupuestos_cabeza = '$_id_presupuestos_cabeza'";
	            $resultado2=$presupuestos->UpdateBy($columnas, $tablas, $where);
	            
	            
	            
	            if(is_int((int)$resultado2[0])){
	                $respuesta = $resultado2[0];
	                $mensaje = "Presupuesto Acrualizado Correctamente";
	            }
	            
	            
	        }
	        
	        
	        
	        if((int)$respuesta > 0 ){
	            
	            echo json_encode(array('respuesta'=>$respuesta,'mensaje'=>$mensaje));
	            exit();
	        }
	        
	        echo "Error al Ingresar la Solicitud";
	        exit();
	        
	    }
	    else
	    {
	        $this->view_Inventario("Error",array(
	            "resultado"=>"No tiene Permisos de Insertar Solicitudes"
	            
	        ));
	        
	        
	    }
	    
	}
	
	
	public function consulta_presupuestos(){
	
	session_start();

	
	$presupuesto= new PresupuestosCabezaModel();
	
	$where_to="";
	$columnas =
	
	" cc.anio_presupuestos_detalle,
          aa.codigo_plan_cuentas,
          aa.nombre_plan_cuentas,
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_ene from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='01'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_feb from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='02'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_mar from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='03'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_abr from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='04'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_may from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='05'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_jun from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='06'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_jul from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='07'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_ago from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='08'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_sep from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='09'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_oct from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='10'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_nov from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='11'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_dic from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='12'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as total from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle
          )
	    
        "
	    ;
	    $tablas   = "   public.plan_cuentas aa
                        inner join public.presupuestos_cabeza bb on bb.id_plan_cuentas = aa.id_plan_cuentas
                        inner join (select id_presupuestos_cabeza,anio_presupuestos_detalle
                                            from presupuestos_detalle
                                            where 1 = 1
                                            group by id_presupuestos_cabeza,anio_presupuestos_detalle) cc on cc.id_presupuestos_cabeza = bb.id_presupuestos_cabeza
";
	    $where    = " 1=1
                      ";
	    $id="aa.codigo_plan_cuentas";
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	$search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	
	
	if($action == 'ajax')
	{
	    
	    
	    if(!empty($search)){
	        
	        
	        $where1=" AND (nombre_presupuestos_cabeza LIKE '".$search."%' )";
	        
	        $where_to=$where.$where1;
	    }else{
	        
	        $where_to=$where;
	        
	    }
	    
	    $html="";
	    $resultSet=$presupuesto->getCantidad("*", $tablas, $where_to);
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre pÃ¡ginas despuÃ©s de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet=$presupuesto->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	    $count_query   = $cantidadResult;
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    
	    
	    
	    
	    if($cantidadResult>0)
	    {
	        
	        $html.='<div class="pull-left" style="margin-left:15px;">';
	        $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	        $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	        $html.='</div>';
	        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        $html.='<section style="height:425px; overflow-y:scroll;">';
	        $html.= "<table id='Solicitud Ingresada Correctamente' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Año</th>';
	        $html.='<th colspan="2" style=" text-align: center; font-size: 11px;">Código</th>';
	        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Nombre</th>';
	        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Total</th>';
	        
	        
	       
	        
	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        
	        $i=0;
	        
	        foreach ($resultSet as $res)
	        {
	            $i++;
	            $html.='<tr>';
	            $html.='<tr >';
	            $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->anio_presupuestos_detalle.'</td>';
	            $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->codigo_plan_cuentas.'</td>';
	            $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->nombre_plan_cuentas.'</td>';
	            $html.='<td colspan="2" style="text-align: right; font-size: 11px;">'.number_format($res->total, 2, ',', '.').'</td>';
	            
	          
	            
	            
	            $html.='</tr>';
	        }
	        
	        
	        
	        $html.='</tbody>';
	        $html.='</table>';
	        $html.='</section></div>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate_presupuestos("index.php", $page, $total_pages, $adjacents).'';
	        $html.='</div>';
	        
	        
	        
	    }else{
	        $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	        $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	        $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	        $html.='<h4>Aviso!!!</h4> <b>Actualmente No Existen Presupuestos...</b>';
	        $html.='</div>';
	        $html.='</div>';
	    }
	    
	    
	    echo $html;
	    die();
	    
	}
	}
	
	
	
	public function paginate_presupuestos($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_presupuestos(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_presupuestos(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_presupuestos(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_presupuestos(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_presupuestos(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_presupuestos($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_presupuestos(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	public function reporte_presupuestos(){
	    session_start();
	    $entidades = new EntidadesModel();
	    //PARA OBTENER DATOS DE LA EMPRESA
	    $datos_empresa = array();
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
	    
	    //NOTICE DATA
	    $datos_cabecera = array();
	    $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
	    $datos_cabecera['FECHA'] = date('Y/m/d');
	    $datos_cabecera['HORA'] = date('h:i:s');
	    
	    
	    $presupuestos=new PresupuestosCabezaModel();
	    $datos_reporte = array();
	    
	    //////retencion detalle
	    
	   
	    
	    
	    $columnas =
	    
	    " cc.anio_presupuestos_detalle,
          aa.codigo_plan_cuentas, 
          aa.nombre_plan_cuentas, 
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_ene from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='01'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_feb from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='02'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_mar from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='03'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_abr from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='04'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_may from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='05'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_jun from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='06'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_jul from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='07'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_ago from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='08'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_sep from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='09'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_oct from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='10'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_nov from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='11'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as valor_dic from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle and pd.mes_presupuestos_detalle='12'
          ),
          (select sum(valor_presupuestado_presupuestos_detalle) as total from presupuestos_detalle pd where pd.id_presupuestos_cabeza=bb.id_presupuestos_cabeza and pd.anio_presupuestos_detalle=cc.anio_presupuestos_detalle 
          )

        "
        ;
        $tablas   = "   public.plan_cuentas aa 
                        inner join public.presupuestos_cabeza bb on bb.id_plan_cuentas = aa.id_plan_cuentas
                        inner join (select id_presupuestos_cabeza,anio_presupuestos_detalle
                                            from presupuestos_detalle 
                                            where 1 = 1
                                            group by id_presupuestos_cabeza,anio_presupuestos_detalle) cc on cc.id_presupuestos_cabeza = bb.id_presupuestos_cabeza
";
        $where    = " 1=1
                      ";
	    $id="aa.codigo_plan_cuentas";
	    
	    $presupuestos_detalle = $presupuestos->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $html='';
	    
	    
	    $html.='<table class="12"  border=1>';
	    $html.='<tr>';
	    $html.='<th width="60px">Año</th>';
	    $html.='<th width="60px">Cuenta</th>';
	    $html.='<th width="300px">Estado de Pérdidas y Ganancias Acumulado</th>';
	    $html.='<th width="60px">Enero</th>';
	    $html.='<th width="60px">Febrero</th>';
	    $html.='<th width="60px">Marzo</th>';
	    $html.='<th width="60px">Abril</th>';
	    $html.='<th width="60px">Mayo</th>';
	    $html.='<th width="60px">Junio</th>';
	    $html.='<th width="60px">Julio</th>';
	    $html.='<th width="60px">Agosto</th>';
	    $html.='<th width="60px">Septiembre</th>';
	    $html.='<th width="60px">Octubre</th>';
	    $html.='<th width="60px">Noviembre</th>';
	    $html.='<th width="60px">Diciembre</th>';
	    $html.='<th width="60px">Total Acumulado</th>';
	    
	    $html.='</tr>';
	    
	  
	    
	    foreach ($presupuestos_detalle as $res)
	    {
	        
	        
	       
	        
	        
	        $html.='<tr >';
	        $html.='<td align="center" width="60px">'.$res->anio_presupuestos_detalle.'</td>';
	        $html.='<td width="60px">'.$res->codigo_plan_cuentas.'</td>';
	        $html.='<td align="left" width="300px">'.$res->nombre_plan_cuentas.'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_ene, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_feb, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_mar, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_abr, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_may, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_jun, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_jul, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_ago, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_sep, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_oct, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_nov, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->valor_dic, 2, ',', '.').'</td>';
	        $html.='<td align="right" width="60px">'.number_format($res->total, 2, ',', '.').'</td>';
	       
	        
	        
	        $html.='</td>';
	        $html.='</tr>';
	    }
	    
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_PRESUPUESTOS']= $html;
	    
	    $this->verReporte("ReportePresupuestos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
	    
	    
	}
	
	
	
}
?>