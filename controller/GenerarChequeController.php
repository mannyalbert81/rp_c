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
	    
	    $datos=null;
	    $datos['id_cuentas_pagar'] = $_id_cuentas_pagar;
	    
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
	        "resultSet"=>$rsCuentasPagar,"rsConsecutivos"=>$rsConsecutivos,"datos"=>$datos
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
	     * se consulta diario enlazado
	     * se realiza insercion
	     * se realiza suma de valores
	     */
	    session_start();
	    
	    $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	    
	    $nombreProcesos = "Pago Cheque CxP";
	    $modulo = "TESORERIA";
	    
	    $respuesta = array();
	    
	    $_diarioTipo = new CoreDiarioTipoCabezaModel();
	    
	    $queryCxP = "SELECT id_cuentas_pagar, id_lote, fecha_cuentas_pagar, compras_cuentas_pagar, impuesto_cuentas_pagar, total_cuentas_pagar
            FROM tes_cuentas_pagar
            WHERE id_cuentas_pagar = $_id_cuentas_pagar";
        
	    $rsCuentasPagar = $_diarioTipo->enviaquery($queryCxP);
	    
	    $total_cuentas_pagar = $rsCuentasPagar[0]->total_cuentas_pagar;
	    
	    $queryCabezaDiario = "SELECT cdtc.id_diario_tipo_cabeza
            FROM core_diario_tipo_cabeza cdtc
            INNER JOIN modulos m
            ON cdtc.id_modulos = m.id_modulos
            INNER JOIN core_tipo_procesos ctp
            ON ctp.id_modulos = m.id_modulos
            WHERE 1 = 1
            AND m.nombre_modulos = '$modulo'
            AND ctp.nombre_tipo_procesos = '$nombreProcesos'";
	    
	    $rsCabezaDiario = $_diarioTipo -> enviaquery($queryCabezaDiario);
	    
	    $_id_diario_tipo = $rsCabezaDiario[0]->id_diario_tipo_cabeza;
	    
	    $queryDiarioDetalle = "SELECT id_diario_tipo_detalle, pc.id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas, destino_diario_tipo_detalle, e.nombre_entidades
            FROM public.core_diario_tipo_detalle cdtd
            INNER JOIN public.plan_cuentas pc
            ON pc.id_plan_cuentas = cdtd.id_plan_cuentas
            INNER JOIN public.entidades e
            ON e.id_entidades = pc.id_entidades
            WHERE id_diario_tipo_cabeza = $_id_diario_tipo";
	    
	    $rsDiarioDetalle = $_diarioTipo -> enviaquery($queryDiarioDetalle); 
	    
	    $htmlTabla="";
	    if(!empty($rsDiarioDetalle)){
	        
	        //dibujar tabla de distribucion cheque
	        $htmlTabla.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        $htmlTabla.='<section style="height:150px; overflow-y:scroll;">';
	        $htmlTabla.= "<table id='tabla_productos' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $htmlTabla.= "<thead>";
	        $htmlTabla.= "<tr>";
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Referencia</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Cuenta</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Descripcion</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Debito</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Credito</th>';
	        
	        $htmlTabla.='</tr>';
	        $htmlTabla.='</thead>';
	        $htmlTabla.='<tbody>';
	        $i=0;
	        $valor_credito = "0,00";
	        $valor_debito = "0,00";
	        foreach ($rsDiarioDetalle as $res){
	            
	            $i++;
	            $htmlTabla.='<tr>';
	            $htmlTabla.='<td style="font-size: 11px;">'.$i.'</td>';
	            $htmlTabla.='<td style="font-size: 11px;">'.$res->nombre_entidades.'</td>';
	            $htmlTabla.='<td style="font-size: 11px;"><input type="text" class="form-control input-sm distribucion" name="mod_dis_referencia" value=""></td>';
	            $htmlTabla.='<td style="font-size: 11px;">'.$res->codigo_plan_cuentas.'</td>';
	            $htmlTabla.='<td style="font-size: 11px;">'.$res->nombre_plan_cuentas.'</td>';
	            if( strtoupper($res->destino_diario_tipo_detalle)  == "DEBE"){
	                $valor_debito = number_format((float)$total_cuentas_pagar, 2, ',', '.');
	                $valor_credito = "0,00";
	            }
	            if( strtoupper($res->destino_diario_tipo_detalle)  == "HABER"){
	                $valor_credito = number_format((float)$total_cuentas_pagar, 2, ',', '.');
	                $valor_debito = "0,00";
	            }
	            $htmlTabla.='<td style="font-size: 11px; text-align:right">'.$valor_debito.'</td>';
	            $htmlTabla.='<td style="font-size: 11px; text-align:right">'.$valor_credito.'</td>';
	           
	            $htmlTabla.='</tr>';
	        }
	        $htmlTabla.='</tbody>';
	        $htmlTabla.='</table>';
	        $htmlTabla.='</section></div>';
	        
	    }
	    
	    $respuesta['tabla'] = (!empty($htmlTabla)) ? $htmlTabla : null;
	    $respuesta['cuentas_pagar'] = $rsCuentasPagar;
	    $respuesta['cxp'] = $total_cuentas_pagar;
	    $respuesta['detallediario'] = $rsDiarioDetalle;
	    
	    echo json_encode($respuesta);
	}
	
	
	
	public function generaCheque(){
	    
	    try{
	        
	        $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	        
	        $_diarioTipo = new CoreDiarioTipoCabezaModel();
	        
	        $nombreProcesos = "Pago Cheque CxP";
	        $modulo = "TESORERIA";
	        
	        $queryCxP = "SELECT id_cuentas_pagar, id_lote, fecha_cuentas_pagar, compras_cuentas_pagar, impuesto_cuentas_pagar, total_cuentas_pagar
                FROM tes_cuentas_pagar
                WHERE id_cuentas_pagar = $_id_cuentas_pagar";
	        
	        $rsCuentasPagar = $_diarioTipo->enviaquery($queryCxP);
	        
	        $total_cuentas_pagar = $rsCuentasPagar[0]->total_cuentas_pagar;
	        
	        $queryCabezaDiario = "SELECT cdtc.id_diario_tipo_cabeza
                    FROM core_diario_tipo_cabeza cdtc
                    INNER JOIN modulos m
                    ON cdtc.id_modulos = m.id_modulos
                    INNER JOIN core_tipo_procesos ctp
                    ON ctp.id_modulos = m.id_modulos
                    WHERE 1 = 1
                    AND m.nombre_modulos = '$modulo'
                    AND ctp.nombre_tipo_procesos = '$nombreProcesos'";
	        
	        $rsCabezaDiario = $_diarioTipo -> enviaquery($queryCabezaDiario);
	        
	        $_id_diario_tipo = $rsCabezaDiario[0]->id_diario_tipo_cabeza;
	        
	        $queryDiarioDetalle = "SELECT id_diario_tipo_detalle, pc.id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas, destino_diario_tipo_detalle, e.nombre_entidades
                    FROM public.core_diario_tipo_detalle cdtd
                    INNER JOIN public.plan_cuentas pc
                    ON pc.id_plan_cuentas = cdtd.id_plan_cuentas
                    INNER JOIN public.entidades e
                    ON e.id_entidades = pc.id_entidades
                    WHERE id_diario_tipo_cabeza = $_id_diario_tipo";
	        
	        $rsDiarioDetalle = $_diarioTipo -> enviaquery($queryDiarioDetalle); 
	        
	        //insertar en comprobantes 
	        //en cabecera luego en el detalle 
	        
	        //buscar datos de cabecera comprobante
	        
	        
	        $var = 0;
	        
	        print_r($_POST);
	        
	        if($var==1)
	            throw new Exception( "var es 2"); 
	        
	    }catch (Exception $ex){
	        
	        echo "<message>error generando cheque<message>";
	        
	    }
	    
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
	    
	     
	    //para buscar cheque
	    $queryBanco = "SELECT id_bancos, lpad(index_bancos::text,espacio_bancos,'0') numero_cheque 
                FROM tes_bancos ban
                INNER JOIN tes_cuentas_pagar cp
                ON ban.id_bancos = cp.id_banco
                WHERE id_cuentas_pagar = $_id_cuentas_pagar";
        
	    $rsBanco= $cuentasPagar->enviaquery($queryBanco);
	    
	    $this->view_tesoreria("GenerarCheque",array(
	        "resultSet"=>$rsCuentasPagar,"rsConsecutivos"=>$rsConsecutivos,"datos"=>$datos,"rsBanco"=>$rsBanco
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
	     * se consulta diario enlazado
	     * se realiza insercion
	     * se realiza suma de valores
	     */
	    session_start();
	    
	    $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	    
	    $nombreProcesos = "Pago Cheque CxP";
	    $modulo = "TESORERIA";
	    
	    $respuesta = array();
	    
	    $_diarioTipo = new CoreDiarioTipoCabezaModel();
	    
	    $queryCxP = "SELECT id_cuentas_pagar, id_lote, fecha_cuentas_pagar, compras_cuentas_pagar, impuesto_cuentas_pagar, total_cuentas_pagar
            FROM tes_cuentas_pagar
            WHERE id_cuentas_pagar = $_id_cuentas_pagar";
        
	    $rsCuentasPagar = $_diarioTipo->enviaquery($queryCxP);
	    
	    $total_cuentas_pagar = $rsCuentasPagar[0]->total_cuentas_pagar;
	    
	    $queryCabezaDiario = "SELECT cdtc.id_diario_tipo_cabeza
            FROM core_diario_tipo_cabeza cdtc
            INNER JOIN modulos m
            ON cdtc.id_modulos = m.id_modulos
            INNER JOIN core_tipo_procesos ctp
            ON ctp.id_modulos = m.id_modulos
            WHERE 1 = 1
            AND m.nombre_modulos = '$modulo'
            AND ctp.nombre_tipo_procesos = '$nombreProcesos'";
	    
	    $rsCabezaDiario = $_diarioTipo -> enviaquery($queryCabezaDiario);
	    
	    $_id_diario_tipo = $rsCabezaDiario[0]->id_diario_tipo_cabeza;
	    
	    $queryDiarioDetalle = "SELECT id_diario_tipo_detalle, pc.id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas, destino_diario_tipo_detalle, e.nombre_entidades
            FROM public.core_diario_tipo_detalle cdtd
            INNER JOIN public.plan_cuentas pc
            ON pc.id_plan_cuentas = cdtd.id_plan_cuentas
            INNER JOIN public.entidades e
            ON e.id_entidades = pc.id_entidades
            WHERE id_diario_tipo_cabeza = $_id_diario_tipo";
	    
	    $rsDiarioDetalle = $_diarioTipo -> enviaquery($queryDiarioDetalle); 
	    
	    $htmlTabla="";
	    if(!empty($rsDiarioDetalle)){
	        
	        //dibujar tabla de distribucion cheque
	        $htmlTabla.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        $htmlTabla.='<section style="height:150px; overflow-y:scroll;">';
	        $htmlTabla.= "<table id='tabla_productos' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $htmlTabla.= "<thead>";
	        $htmlTabla.= "<tr>";
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Referencia</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Cuenta</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Descripcion</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Debito</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Credito</th>';
	        
	        $htmlTabla.='</tr>';
	        $htmlTabla.='</thead>';
	        $htmlTabla.='<tbody>';
	        $i=0;
	        $valor_credito = "0,00";
	        $valor_debito = "0,00";
	        foreach ($rsDiarioDetalle as $res){
	            
	            $i++;
	            $htmlTabla.='<tr>';
	            $htmlTabla.='<td style="font-size: 11px;">'.$i.'</td>';
	            $htmlTabla.='<td style="font-size: 11px;">'.$res->nombre_entidades.'</td>';
	            $htmlTabla.='<td style="font-size: 11px;"><input type="text" class="form-control input-sm distribucion" name="mod_dis_referencia" value=""></td>';
	            $htmlTabla.='<td style="font-size: 11px;">'.$res->codigo_plan_cuentas.'</td>';
	            $htmlTabla.='<td style="font-size: 11px;">'.$res->nombre_plan_cuentas.'</td>';
	            if( strtoupper($res->destino_diario_tipo_detalle)  == "DEBE"){
	                $valor_debito = number_format((float)$total_cuentas_pagar, 2, ',', '.');
	                $valor_credito = "0,00";
	            }
	            if( strtoupper($res->destino_diario_tipo_detalle)  == "HABER"){
	                $valor_credito = number_format((float)$total_cuentas_pagar, 2, ',', '.');
	                $valor_debito = "0,00";
	            }
	            $htmlTabla.='<td style="font-size: 11px; text-align:right">'.$valor_debito.'</td>';
	            $htmlTabla.='<td style="font-size: 11px; text-align:right">'.$valor_credito.'</td>';
	           
	            $htmlTabla.='</tr>';
	        }
	        $htmlTabla.='</tbody>';
	        $htmlTabla.='</table>';
	        $htmlTabla.='</section></div>';
	        
	    }
	    
	    $respuesta['tabla'] = (!empty($htmlTabla)) ? $htmlTabla : null;
	    $respuesta['cuentas_pagar'] = $rsCuentasPagar;
	    $respuesta['cxp'] = $total_cuentas_pagar;
	    $respuesta['detallediario'] = $rsDiarioDetalle;
	    
	    echo json_encode($respuesta);
	}
	
	
	
	public function generaCheque(){
	    
	    session_start();
	   
	    try{
	        
	        $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	        $_id_usuarios = $_SESSION['id_usuarios'];
	        $_id_proveedores = 0;
	        $_numero_cheque = trim($_POST['numero_cheque']);
	        $_fecha_cheque = $_POST['fecha_cheque'];
	        $_numero_cuenta_banco = "";
	        $_observaciones = "PAGO CON CHEQUE";
	        $_transaccion = "CHEQUE";
	        $_retencion = "";
	        $_concepto = $_POST['comentario_cheque'];
	        $_id_bancos = $_POST['id_bancos'];
	        
	        
	        $CuentasPagar = new CuentasPagarModel();
	        
	        $respuesta = array();
	       
	        $nombreProcesos = "Pago Cheque CxP";
	        $modulo = "TESORERIA";
	        
	        $queryCxP = "SELECT id_cuentas_pagar, id_proveedor, id_lote, fecha_cuentas_pagar, compras_cuentas_pagar, impuesto_cuentas_pagar, total_cuentas_pagar
                FROM tes_cuentas_pagar
                WHERE id_cuentas_pagar = $_id_cuentas_pagar";
	        
	        $rsCuentasPagar = $CuentasPagar->enviaquery($queryCxP);
	         
	        $_total_cuentas_pagar = $rsCuentasPagar[0]->total_cuentas_pagar;
	        $_id_proveedores = $rsCuentasPagar[0]->id_proveedor;
	        $_total_en_letras = $CuentasPagar->numtoletras($_total_cuentas_pagar);
	        
	        $queryFormaPago = "SELECT * FROM forma_pago WHERE nombre_forma_pago = 'CHEQUE' LIMIT 1";
	        $rsFormaPago = $CuentasPagar->enviaquery($queryFormaPago);
	        
	        $_id_formadePago = $rsFormaPago[0]->id_forma_pago;
	        
	        //para buscar el diario
	        $queryCabezaDiario = "SELECT cdtc.id_diario_tipo_cabeza
                    FROM core_diario_tipo_cabeza cdtc
                    INNER JOIN modulos m
                    ON cdtc.id_modulos = m.id_modulos
                    INNER JOIN core_tipo_procesos ctp
                    ON ctp.id_modulos = m.id_modulos
                    WHERE 1 = 1
                    AND m.nombre_modulos = '$modulo'
                    AND ctp.nombre_tipo_procesos = '$nombreProcesos'";
	        
	        $rsCabezaDiario = $CuentasPagar -> enviaquery($queryCabezaDiario);
	        
	        if(empty($rsCabezaDiario))
	            throw new Exception("No se puede identificar DIARIO CONTABLE de Pago");
	        
	        $_id_diario_tipo = $rsCabezaDiario[0]->id_diario_tipo_cabeza;
	        
	        
	        $funcion = "tes_agrega_comprobante_pago_cheque";
	        $parametros = "$_id_usuarios,$_id_bancos,$_id_cuentas_pagar,$_id_proveedores,$_id_formadePago,$_id_diario_tipo,$_total_cuentas_pagar,
                           '$_total_en_letras','$_fecha_cheque','$_numero_cheque','$_numero_cuenta_banco','$_numero_cheque',
                           '$_observaciones','$_transaccion','$_retencion','$_concepto'";
	        
	        $queryFuncion = "SELECT $funcion ( $parametros )";
	        	        
	        $rsComprobante = $CuentasPagar->llamarconsultaPG($queryFuncion);
	        
	        if(is_null($rsComprobante))
	            throw new Exception( " No se ingresaron los Datos "); 
	        
            if( !is_null($rsComprobante) ){
                
                if( $rsComprobante[0] > 0 ){
                    $respuesta['comprobante']['mensaje']="CHEQUE REGISTRADO CORRECTAMENTE";
                    $respuesta['comprobante']['valor'] = 1;
                    $respuesta['comprobante']['id_comprobante'] = $rsComprobante[0];
                    $respuesta['cuentaspagar']['id_cuentas_pagar'] = $_id_cuentas_pagar;
                }
                
                if( $rsComprobante[0] == 0 ){
                    $respuesta['comprobante']['mensaje']="NO SE REGISTRO CHEQUE";
                    $respuesta['comprobante']['valor'] = 0;
                    $respuesta['comprobante']['id_comprobante'] = $rsComprobante[0];
                }
                
                if( $rsComprobante[0] == -1 ){
                    $respuesta['comprobante']['mensaje']="REVISAR NUMERO DE CHEQUE";
                    $respuesta['comprobante']['valor'] = 0;
                    $respuesta['comprobante']['id_comprobante'] = 0;
                }
                
                /*actualizacion de Cuenta por pagar*/
                //buscar estado de cuentas por pagar
                $queryEstado = "SELECT id_estado FROM estado WHERE tabla_estado='tes_cuentas_pagar' AND nombre_estado = 'APLICADO'";
                $rsEstado = $CuentasPagar -> enviaquery($queryEstado);
                $_id_estado = $rsEstado[0]->id_estado;
                $rsActualizacion = $CuentasPagar->ActualizarBy("id_estado = $_id_estado", "tes_cuentas_pagar", "id_cuentas_pagar = $_id_cuentas_pagar");
                
            }
	        	        
            echo json_encode($respuesta);
	        
	    }catch (Exception $ex){
	        
	        echo "<message>Error generando cheque ".$ex->getMessage()."<message>";
	        
	       // $ex->getMessage();
	        
	    }
	    
	}
	
	public function generaReporteCheque(){
	    
	    
	    session_start();
	    
	    try{
	        //toma de datos
	        @$_id_comprobante =  $_POST['id_comprobante'];
	        @$_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	        
	        //para pruebas
	        //$_id_comprobante =  68;
	        //$_id_cuentas_pagar = 3;
	        
	        if( $_id_comprobante == null || $_id_cuentas_pagar == null )
	            throw new Exception("Parametros No Recibidos");
	        
	    }catch (Exception $ex){
	        
	        echo $ex->getMessage();
	        die();
	        
	    }
	    
	    $html="";
	    $cedula_usuarios = $_SESSION["cedula_usuarios"];
	    //$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    //$fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	    
	    
	    $CuentasPagar = new CuentasPagarModel();
	    
	    if(!empty($cedula_usuarios)){
	        
	        /* DATOS CUENTAS PAGAR */
	        $colCuentas = "cp.id_cuentas_pagar, cp.descripcion_cuentas_pagar, p.nombre_proveedores, cp.total_cuentas_pagar";
	        $tabCuentas = "tes_cuentas_pagar cp
            	       INNER JOIN proveedores p
            	        ON cp.id_proveedor = p.id_proveedores
            	        INNER JOIN tes_bancos b
            	        ON b.id_bancos = cp.id_banco";
	        $wheCuentas = "1=1 AND cp.id_cuentas_pagar = $_id_cuentas_pagar ";
	        $idCuentas = "id_cuentas_pagar";
	        $rscuentasPagar = $CuentasPagar->getCondiciones($colCuentas, $tabCuentas, $wheCuentas, $idCuentas);
	        	        
	        //traer datos de cheque
	        $portadorCheque = $rscuentasPagar[0]->nombre_proveedores;
	        $valorLetrasCheque = $CuentasPagar->numtoletras($rscuentasPagar[0]->total_cuentas_pagar);
	        $valorCheque = number_format((float)$rscuentasPagar[0]->total_cuentas_pagar, 2, '.', ',');
	        $proveedor = $rscuentasPagar[0]->nombre_proveedores;
	        //$totalCuentaPagar = $rscuentasPagar[0]->total_cuentas_pagar;
	        
	        /* DATOS COMPROBNTE*/
	        $colComprobante = "id_ccomprobantes, valor_ccomprobantes, concepto_ccomprobantes, fecha_ccomprobantes, numero_cheque_ccomprobantes,
                                transaccion_ccomprobantes";
	        $tabComprobante = "public.ccomprobantes";
	        $wheComprobante = "1=1 AND id_ccomprobantes = $_id_comprobante ";
	        $idComprobante = "id_ccomprobantes";
	        $rsComprobante = $CuentasPagar->getCondiciones($colComprobante, $tabComprobante, $wheComprobante, $idComprobante);
	        
	        //traer detalles cheque
	        $conceptoCheque = $rsComprobante[0]->concepto_ccomprobantes;
	        $numeroCheque = $rsComprobante[0]->numero_cheque_ccomprobantes;
	        $transaccion = $rsComprobante[0]->transaccion_ccomprobantes;
	        $fechaComprobante = $rsComprobante[0]->fecha_ccomprobantes;
	        
	        $fecha = strtotime( $fechaComprobante);
	        
	        //$fechaCheque = date('Y',$fechaFooter).' ' $dias[date('w',$fechaFooter)]." ".date('d',$fechaFooter)." de ".$meses[date('n',$fechaFooter)-1]. " del ". ;
	        $fechaCheque = date('Y',$fecha).' '.strtoupper($meses[date('n',$fecha)-1]).' '.date('d',$fecha);
	        $fechaFooter = date('d',$fecha).' DE '.strtoupper($meses[date('n',$fecha)-1]).' DEL '.date('Y',$fecha);
	        
	        
	        /* DATOS CONTABLES */
	        $colCuentas = "dc.id_dcomprobantes, dc.id_plan_cuentas, pc.codigo_plan_cuentas, pc.nombre_plan_cuentas, dc.debe_dcomprobantes, dc.haber_dcomprobantes,
                            pc.nivel_plan_cuentas";
	        $tabCuentas = "dcomprobantes dc
            	        INNER JOIN plan_cuentas pc
            	        ON pc.id_plan_cuentas = dc.id_plan_cuentas";
	        $wheCuentas = "1=1 AND dc.id_ccomprobantes = $_id_comprobante ";
	        $idCuentas = "id_dcomprobantes";
	        $rsCuentas = $CuentasPagar->getCondiciones($colCuentas, $tabCuentas, $wheCuentas, $idCuentas);
	      
	        //traer detalles contables
	        $htmlTabla = '<table style="width: 100%; margin-top:10px;" border=hidden cellspacing=0>';	      
	        if(!empty($rsCuentas)){
	            $nivelCuenta=0;
	            $codigoCuenta='';
	            $codigoPadre='';
	            $arraycodigo = array();
	            foreach ($rsCuentas as $res){
	                //buscar cuenta nivel mayor
	                $codigoCuenta= $res->codigo_plan_cuentas;
	                $codigoCuenta= trim($codigoCuenta,'.');
	                $nivelCuenta = $res->nivel_plan_cuentas;
	                $arraycodigo = explode('.', $codigoCuenta);
	                for($i=0; $i < (count($arraycodigo)-1); $i++){	                    
                        $codigoPadre.=$arraycodigo[$i].'.';	                    
	                }
	                $nivelCuenta = $nivelCuenta-1;
	                $codigoPadre = trim($codigoPadre,'.');
	                $queryCuentaPadre = "SELECT codigo_plan_cuentas, nombre_plan_cuentas FROM public.plan_cuentas 
                            WHERE codigo_plan_cuentas LIKE '$codigoPadre%' AND nivel_plan_cuentas = '$nivelCuenta'";
	                $rsCuentaPadre = $CuentasPagar -> enviaquery($queryCuentaPadre);
	              
	                if(!empty($rsCuentaPadre)){
	                    $htmlTabla.="<tr>";
	                    $htmlTabla.='<td style="width:100px;font-size: 11px; ">'.$rsCuentaPadre[0]->codigo_plan_cuentas.'</td>';
	                    $htmlTabla.='<td style="width:100px;font-size: 11px; ">'.$rsCuentaPadre[0]->nombre_plan_cuentas.'</td>';
	                    $htmlTabla.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	                    $htmlTabla.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	                    $htmlTabla.="</tr>";
	                }
	               
    	            $htmlTabla.="<tr>";
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; ">'.$res->codigo_plan_cuentas.'</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; ">'.$res->nombre_plan_cuentas.'</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; text-align: center;">'.$res->debe_dcomprobantes.'</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; text-align: center;">'.$res->haber_dcomprobantes.'</td>';
    	            $htmlTabla.="</tr>";
    	            $htmlTabla.="<tr>";
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px;">'.$conceptoCheque.'</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
    	            $htmlTabla.="</tr>";
    	            
    	            $codigoPadre='';
	           }
	        }
	        $htmlTabla .= '</table>';
	        
	        $ciudad="QUITO";
	       
	        
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:130px;">&nbsp;</td>';
	        $html.='<td style="width:250px;font-size: 13px;">'.$portadorCheque.'</td>';
	        $html.='<td style="width:10px;font-size: 13px;">'.$valorCheque.'</td>';
	        $html.="</tr>";
	        $html.= "<tr>";
	        $html.='<td style="width:130px;">&nbsp;</td>';
	        $html.='<td style="width:200px;font-size: 13px;">'.$valorLetrasCheque.'</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.='<td style="width:50px;font-size: 13px;">'.$ciudad.',</td>';
	        $html.='<td style="width:200px;font-size: 13px;">'.$fechaCheque.'</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.='<table>';
	        $html.="<tr>";
	        $html.='<td style="width:70px;">&nbsp;</td>';
	        $html.='<td style="width:300px;font-size: 11px;">'.$conceptoCheque.'</td>';
	        $html.='<td style="width:80px;font-size: 11px;">'.$transaccion.'</td>';
	        $html.='<td style="width:110px;font-size: 11px;"><b>No.</b></td>';
	        $html.='<td style="width:10px;font-size: 11px;">'.$numeroCheque.'</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:70px;">&nbsp;</td>';
	        $html.='<td style="width:300px;font-size: 11px;">'.$proveedor.'</td>';
	        $html.='<td style="width:80px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:110px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:10px;font-size: 11px;">'.$valorCheque.'</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        
	        $html.='<table>';
	        $html.="<tr>";
	        $html.='<td style="width:50px;">&nbsp;</td>';
	        $html.='<td style="width:300px;font-size: 11px;"> '.$conceptoCheque.'; &nbsp;'.$proveedor.'</td>';
	        $html.='<td style="width:80px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:70px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:10px;font-size: 11px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        
	        //para tabla de detalle de cuentas
	        $html.= $htmlTabla;
	        
	        
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;"><b>Fecha:&nbsp; &nbsp;</b>'.$fechaFooter.'</td>';
	        $html.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;"><b>Total General:</b></td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$valorCheque.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$valorCheque.'</td>';
	        $html.="</tr>";
	        
	        $html.="</table>";
	        
	        
	        
	    }
	    
	    $this->report("Cheque",array( "resultSet"=>$html));
	    die();
	}
	
	
	
}
?>