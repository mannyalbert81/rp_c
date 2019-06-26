<?php

class GenerarChequeController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		$entidad = new CoreEntidadPatronalModel();
				
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "GenerarCheque";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $entidad->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Empleo"
		        
		    ));
		    exit();
		}		    
			
		$rsEntidad = $entidad->getBy(" 1 = 1 ");
		
				
		$this->view_tesoreria("GenerarCheque",array(
		    "resultSet"=>$rsEntidad
	
		));
			
	
	}
	
	public function indexCheque(){
	    
	    session_start();
	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    $_id_usuarios = (isset($_SESSION['id_usuarios'])) ? $_SESSION['id_usuarios'] : null;
	    
	    if( !isset($_GET['id_cuentas_pagar']) ){
	        
	        $this->redirect("Pagos","index");
	        exit();
	    }
	    
	    $_id_cuentas_pagar = $_GET['id_cuentas_pagar'];
	    
	    $query = "SELECT l.id_lote, l.nombre_lote, cp.id_cuentas_pagar, cp.numero_cuentas_pagar, cp.descripcion_cuentas_pagar, cp.fecha_cuentas_pagar, 
                    cp.compras_cuentas_pagar, cp.total_cuentas_pagar, p.id_proveedores, p.nombre_proveedores, p.identificacion_proveedores,
                    b.id_bancos, b.nombre_bancos, m.id_moneda, m.signo_moneda || '-' || m.nombre_moneda AS moneda
                FROM tes_cuentas_pagar cp
                INNER JOIN tes_lote l        
                ON cp.id_lote = l.id_lote
                INNER JOIN proveedores p
                ON p.id_proveedores = cp.id_proveedor
                INNER JOIN tes_bancos b
                ON b.id_bancos = cp.id_banco
                INNER JOIN tes_moneda m
                ON m.id_moneda = cp.id_moneda
                WHERE 1 = 1
                AND cp.id_cuentas_pagar = $_id_cuentas_pagar ";
	    
	    $rsCuentasPagar = $cuentasPagar->enviaquery($query);
	    
	    // PARA BUSCAR CONSECUTIVO DE PAGO 
	    
	    $queryConsecutivo = "SELECT numero_consecutivos FROM consecutivos WHERE nombre_consecutivos = 'PAGOS' AND id_entidades = 1";
	    
	    $rsConsecutivos = $cuentasPagar->enviaquery($queryConsecutivo);
	    
	    $this->view_tesoreria("GenerarCheque",array(
	        "resultSet"=>$rsCuentasPagar,"rsConsecutivos"=>$rsConsecutivos
	    ));
	    
	}
	
	
	public function paginate($reload, $page, $tpages, $adjacents, $funcion = "") {
	    
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
	
	/***
	 * return: json
	 * title: editBancos
	 * fcha: 2019-04-22
	 */
	public function editEntidad(){
	    
	    session_start();
	    $entidad = new CoreEntidadPatronalModel();
	    $nombre_controladores = "CoreEntidadPatronal";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $entidad->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    	     
	    if (!empty($resultPer))
	    {
	        
	        
	        if(isset($_POST["id_entidad_patronal"])){
	            
	            $_id_entidad_patronal = (int)$_POST["id_entidad_patronal"];
	            
	            $query = "SELECT * FROM core_entidad_patronal WHERE id_entidad_patronal = $_id_entidad_patronal";

	            $resultado  = $entidad->enviaquery($query);	            
	           
	            echo json_encode(array('data'=>$resultado));	            
	            
	        }
	       	        
	        
	    }
	    else
	    {
	        echo "Usuario no tiene permisos-Editar";
	    }
	    
	}
	
	public function distribucionCheque(){
	    
	    /* se realiza la distribucion de pago
	     * se realiza insercion
	     * se realiza suma de valores
	     */
	    session_start();
	    
	    $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	    
	    echo "llego";
	}
	
	
	
	/***
	 * return: json
	 * title: delBancos
	 * fcha: 2019-04-22
	 */
	public function delEntidad(){
	    
	    session_start();
	    $entidad = new CoreEntidadPatronalModel();
	    $nombre_controladores = "CoreEntidadPatronal";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $entidad->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer)){	        
	        
	        if(isset($_POST["id_entidad_patronal"])){
	            
	            $id_entidad = (int)$_POST["id_entidad_patronal"];
	            
	            $resultado  = $entidad->eliminarBy(" id_entidad_patronal ",$id_entidad);
	           
	            if( $resultado > 0 ){
	                
	                echo json_encode(array('data'=>$resultado));
	                
	            }else{
	                
	                echo $resultado;
	            }
	            
	            
	            
	        }
	        
	        
	    }else{
	        
	        echo "Usuario no tiene permisos-Eliminar";
	    }
	    
	    
	    
	}
	
	
	public function consultaEntidad(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $entidad = new CoreEntidadPatronalModel();
	    
	    
	    $where_to="";
	    $columnas  = " id_entidad_patronal, nombre_entidad_patronal, ruc_entidad_patronal, codigo_entidad_patronal, tipo_entidad_patronal, acronimo_entidad_patronal, direccion_entidad_patronal ";
	    
	    $tablas    = "public.core_entidad_patronal";
	    
	    $where     = " 1 = 1";
	    
	    $id        = "core_entidad_patronal.nombre_entidad_patronal";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';	    
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND nombre_entidad_patronal ILIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$entidad->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$entidad->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);	        
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_entidad' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 15px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Entidad</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Ruc</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Código</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Tipo</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Acrónimo</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Dirección</th>';
	           
	            
	            /*para administracion definir administrador MenuOperaciones Edit - Eliminar*/
	                
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 14px;">'.$i.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->ruc_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->codigo_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->tipo_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->acronimo_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->direccion_entidad_patronal.'</td>';
	                
	                
	               
	                /*comentario up */
	                
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editEntidad('.$res->id_entidad_patronal.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                    $html.='<td style="font-size: 18px;">
                            <a onclick="delEntidad('.$res->id_entidad_patronal.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
	                    
	               
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaEntidad").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay entidades registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	       
	    }
	    
	     
	}
	
	
	
}
?>