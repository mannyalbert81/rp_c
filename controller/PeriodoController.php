<?php

class PeriodoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
	
	    $periodo = new PeriodoModel();
				
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "Periodo";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $periodo->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Periodo"
		        
		    ));
		    exit();
		}		    
			
		$rsBancos = $periodo->getBy(" 1 = 1 ");
		
				
		$this->view_Contable("Periodo",array(
		    "resultSet"=>$rsBancos
	
		));
			
	
	}
	


	public function AbrirPeriodo(){
	    
	    session_start();
	    
	    $periodo = new PeriodoModel();
	    
	    $nombre_controladores = "Periodo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $periodo->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	
	    
	    
	    if (!empty($resultPer)){
	        
	        
	        $columnaest = "con_periodo.id_periodo, con_periodo.id_estado ";
	        $tablaest= "con_periodo,estado";
	        $whereest= "con_periodo.id_estado = estado.id_estado
                        and estado.nombre_estado = 'ABIERTO'
                        and con_periodo.anio_periodo = con_periodo.anio_periodo
                        and con_periodo.mes_periodo = con_periodo.mes_periodo
                        and con_periodo.id_tipo_cierre = 1
                        ";
	        $idest = "estado.id_estado";
	        $resultEst = $periodo->getCondiciones($columnaest, $tablaest, $whereest, $idest);
	       
	        
	        
	        if (empty($resultEst)){
	        
	            $_id_periodo = (isset($_POST["id_periodo"])) ? $_POST["id_periodo"] : "0";
	            $_anio_periodo = (isset($_POST["anio_periodo"])) ? $_POST["anio_periodo"] : 0 ;
	            $_mes_periodo = (isset($_POST["mes_periodo"])) ? $_POST["mes_periodo"] : 0 ;
	            $_id_tipo_cierre = (isset($_POST["id_tipo_cierre"])) ? $_POST["id_tipo_cierre"] : 0 ;
	            $_id_estado = (isset($_POST["id_estado"])) ? $_POST["id_estado"] : 0 ;
	            
	            
	            $funcion = "ins_con_periodo";
	            $respuesta = 0 ;
	            $mensaje = "";
	            
	             
	            if($_id_periodo == 0){
	                
	                $parametros = " '$_anio_periodo','$_mes_periodo','$_id_tipo_cierre','101'";
	                $periodo->setFuncion($funcion);
	                $periodo->setParametros($parametros);
	                $resultado = $periodo->llamafuncionPG();
	                
	                if(is_int((int)$resultado[0])){
	                    $respuesta = $resultado[0];
	                    $mensaje = "Periodo Ingresado Correctamente";
	                }
	                
	                
	                
	            }elseif ($_id_periodo > 0){
	                
	             
	                
	                
	            }
	            
	        }
	        else 
	        {
	            $resultEst=$resultEst[0]->id_estado;
	            $respuesta = 0 ;
	            $mensaje = "Existe un Periodo Abierto"; 
	            
	        }
	   
	        
	        echo json_encode(array ("respuesta"=>$respuesta, "mensaje"=> $mensaje));
	        exit ();
	        
	       
	        
	      
	    }
	    else
	    {
	        $this->view_Inventario("Error",array(
	            "resultado"=>"No tiene Permisos de Insertar Periodo"
	            
	        ));
	        
	        
	    }
	    
	}
	
	public function CerrarPeriodo(){
	    
	    session_start();
	    
	    $periodo = new PeriodoModel();
	    
	    $nombre_controladores = "Periodo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $periodo->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	   	    
	    if (!empty($resultPer)){
	        
	        $_id_periodo = (isset($_POST["id_periodo"])) ? $_POST["id_periodo"] : "0";
	        
	        $respuesta = 0 ;
	        $mensaje = "";
	        
	        
	            
	            $columnaCre = "id_estado = '102'";
	            $tablasCre = "con_periodo";
	            $whereCre = "id_periodo = $_id_periodo";
	            $resultado= $periodo -> ActualizarBy($columnaCre, $tablasCre, $whereCre);           
	          
	            
	            if(is_int((int)$resultado)){
	                $respuesta = $resultado;
	                $mensaje = "Periodo Cerrado Correctamente";
	            }
	            
	            
	         
	        echo json_encode(array ("respuesta"=>$respuesta, "mensaje"=> $mensaje));
	        exit ();
	        
	        
	    }
	    else
	    {
	        $this->view_Inventario("Error",array(
	            "resultado"=>"No tiene Permisos de Insertar Periodo"
	            
	        ));
	        
	        
	    }
	  
	    
	    
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
	public function editPeriodo(){
	    
	    session_start();
	    $periodo = new PeriodoModel();
	    $nombre_controladores = "Periodo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $periodo->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    	     
	    if (!empty($resultPer))
	    {
	        
	        
	        if(isset($_POST["id_periodo"])){
	            
	            $id_periodo = (int)$_POST["id_periodo"];
	            
	            $query = "SELECT * FROM con_periodo WHERE id_periodo = $id_periodo";

	            $resultado  = $periodo->enviaquery($query);	            
	           
	            echo json_encode(array('data'=>$resultado));	            
	            
	        }
	       	        
	        
	    }
	    else
	    {
	        echo "Usuario no tiene permisos-Editar";
	    }
	    
	}
	
	
	/***
	 * return: json
	 * title: delBancos
	 * fcha: 2019-04-22
	 */
	public function delPeriodo(){
	    
	    session_start();
	    $periodo = new PeriodoModel();
	    $nombre_controladores = "Periodo";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $periodo->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer)){	        
	        
	        if(isset($_POST["id_periodo"])){
	            
	            $id_periodo = (int)$_POST["id_periodo"];
	            
	            $resultado  = $periodo->eliminarBy(" id_periodo ",$id_periodo);
	           
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
	
	
	public function consultaPeriodo(){
	    
	    session_start();
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $id_rol=$_SESSION["id_rol"];
	    
	    $periodo = new PeriodoModel();
	    
	    $where_to="";
	    $columnas  = " id_periodo, anio_periodo, mes_periodo, nombre_tipo_cierre, nombre_estado";
	    
	    $tablas    = "public.con_periodo INNER JOIN public.con_tipo_cierre ON con_tipo_cierre.id_tipo_cierre = con_periodo.id_tipo_cierre INNER JOIN public.estado ON estado.id_estado = con_periodo.id_estado";
	    
	    $where     = " 1 = 1";
	    
	    $id        = "con_periodo.id_periodo";
	    
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';	    
	    
	    if($action == 'ajax')
	    {
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND nombre_estado ILIKE '".$search."%'";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$periodo->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$periodo->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);	        
	        
	        if($cantidadResult > 0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:400px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_bancos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 15px;">#</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Año</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Mes</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Tipo Cierre</th>';
	            $html.='<th style="text-align: left;  font-size: 15px;">Estado</th>';
	            
	            /*para administracion definir administrador MenuOperaciones Edit - Eliminar*/
	                
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
	                $html.='<td style="font-size: 14px;">'.$res->anio_periodo.'</td>';
	                $html.='<td style="font-size: 14px;">'.strtoupper($meses[$res->mes_periodo-1]).'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_tipo_cierre.'</td>';
	                $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
	                
	               
	                /*comentario up */
	                
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editPeriodo('.$res->id_periodo.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                        
	               
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaPeriodo").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	       
	    }
	    
	     
	}
	
	/**
	 * mod: tesoreria
	 * title: cargar datos estado bancos
	 * ajax: si
	 * dc:2019-04-22
	 */
	public function cargaEstadoPeriodo(){
	    
	    $periodo = null;
	    $periodo = new PeriodoModel();
	    
	    $query = " SELECT id_estado,nombre_estado FROM estado WHERE tabla_estado = 'con_periodo' ORDER BY nombre_estado";
	    
	    $resulset = $periodo->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	public function cargaTipoCierre(){
	    
	    $tipo_cierre = null;
	 //   $periodo = new PeriodoModel();
	    $tipo_cierre = new TipoCierreModel();
	    
	    $query = " SELECT id_tipo_cierre,nombre_tipo_cierre FROM con_tipo_cierre WHERE 1 = 1 ";
	    
	    $resulset = $tipo_cierre->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	
	/** CAMBIOS dc 2019-12-03 para vista de cierre **/
	
	public function indexApertura(){
	    
	    $periodo = new PeriodoModel();
	    
	    session_start();
	    
	    if(empty( $_SESSION)){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        return;
	    }
	    
	    $nombre_controladores = "AperturaMes";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $periodo->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso 'APERTURA DE MES'"
	            
	        ));
	        exit();
	    }
	        
	    
	    $this->view_Contable("AperturaMes",array());
	    
	}
	
	public function RegistrarDetallesPeriodo(){
	    
	    $periodo = new PeriodoModel();
	    
	    if( !isset( $_SESSION ) ){
	        session_start();
	    }
	    
	    $_anio_periodo = (isset( $_POST['anio_periodo'])) ? $_POST['anio_periodo'] : 0 ;
	    
	    $vMeses = ["ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE"];
	    
	    $columnas1 = " aa.id_periodo, aa.anio_periodo, aa.mes_periodo, aa.id_tipo_cierre, cc.nombre_tipo_cierre, aa.id_estado, bb.nombre_estado";
	    $tablas1   = " con_periodo aa
    	    INNER JOIN estado bb ON bb.id_estado = aa.id_estado
    	    INNER JOIN con_tipo_cierre cc ON cc.id_tipo_cierre = aa.id_tipo_cierre";
	    $where1    = " aa.anio_periodo = '$_anio_periodo' ";
	    $id1       = " aa.mes_periodo";
	    $rsConsulta1 = $periodo->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    if( empty($rsConsulta1) ){
	        
	        $periodo->beginTran();
	        
	        // aqui comienza validacion para insertar 
	        
	        //buscar datos
	        $columnas2 = " id_entidades, nombre_entidades";
	        $tablas2   = " entidades";
	        $where2    = " nombre_entidades = 'CAPREMCI'";
	        $id2       = " id_entidades";
	        $rsConsulta2   = $periodo->getCondiciones($columnas2, $tablas2, $where2, $id2);
	        $_id_entidades = ( !empty( $rsConsulta2 ) ) ? $rsConsulta2[0]->id_entidades : 'null';
	        
	        $columnas3 = " id_tipo_cierre, nombre_tipo_cierre";
	        $tablas3   = " con_tipo_cierre";
	        $where3    = " nombre_tipo_cierre = 'MENSUAL'";
	        $id3       = " id_tipo_cierre";
	        $rsConsulta3   = $periodo->getCondiciones($columnas3, $tablas3, $where3, $id3);
	        $_id_tipo_cierre   = ( !empty( $rsConsulta3 ) ) ? $rsConsulta3[0]->id_tipo_cierre : 'null';
	        
	        $_id_usuarios = $_SESSION['id_usuarios'];
	        $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	        
	        $columnas4 = " id_estado, nombre_estado";
	        $tablas4   = " estado";
	        $where4    = " nombre_estado = 'CERRADO' AND tabla_estado = 'con_periodo' ";
	        $id4       = " id_estado";
	        $rsConsulta4   = $periodo->getCondiciones($columnas4, $tablas4, $where4, $id4);
	        $_id_estado   = ( !empty( $rsConsulta4 ) ) ? $rsConsulta4[0]->id_estado : 'null';
	        
	        $_funcion  = "ins_con_periodo";
	        $_parametros   = "";
            
	        for ($i = 1; $i <= 12; $i++) {
	            
	            $_parametros   = "$_id_entidades,$_id_usuarios,$_id_tipo_cierre,$_id_estado,'$_usuario_usuarios',$_anio_periodo,$i";
	            $_consulta1 = $periodo->getconsultaPG($_funcion, $_parametros);
	            $periodo->llamarconsultaPG($_consulta1);
	           	            
	        }
	        
	        $error_pg  = pg_last_error();
	        $error     = error_get_last();
	        
	        if( !empty($error_pg) || !empty($error) ){
	            
	            $periodo->endTran();
	        }
	        
	        $periodo->endTran('COMMIT');
	        
	        $columnas1 = " aa.id_periodo, aa.anio_periodo, aa.mes_periodo, aa.id_tipo_cierre, cc.nombre_tipo_cierre, aa.id_estado, bb.nombre_estado";
	        $tablas1   = " con_periodo aa
    	    INNER JOIN estado bb ON bb.id_estado = aa.id_estado
    	    INNER JOIN con_tipo_cierre cc ON cc.id_tipo_cierre = aa.id_tipo_cierre";
	        $where1    = " aa.anio_periodo = '$_anio_periodo' ";
	        $id1       = " aa.mes_periodo";
	        $rsConsulta1 = $periodo->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        
	        	        
	    }
	    
	    //trabajar para enviar a la vista el resultado en filas
	    $htmlFilas = "";
	    $botonAction   = "";
	    $classColorIcon= ""; 
	    
	    $i = 0;
	    foreach ($rsConsulta1 as $res) {
	        //para la vista se necesita 5 columnas
	        $i++;
	        $nombreMes = $vMeses[ ($res->mes_periodo) - 1 ];
	        $classIconEstado   = ( $res->nombre_estado == "CERRADO" ) ? " fa-lock" : "fa-unlock-alt";
	        $spanEstado    = "<span><i class=\"fa $classIconEstado \"></i></span>";
	        //$botonAction = "<button class=\" btn btn-warning \" data-periodoid=\""+value.id_periodo+"\" onclick=\"fnAperturaMes(this)\" ><i class=\"fa fa-exchange\" aria-hidden=\"true\"></i></button>";
	        
	        if( $res->nombre_estado == "CERRADO" ){
	            $botonAction = "<button class=\"btn btn-sm btn-warning\" data-periodo_id=\"$res->id_periodo\"  onclick=\"fnAbrirPeriodo(this)\">"
	   	            ."<i class=\"fa fa-exchange\"></i>"
                    ."</button>";
                $classColorIcon = "text-danger";
	        }else{
	            $botonAction = "<button class=\"btn btn-sm btn-warning\" data-periodo_id=\"$res->id_periodo\"  onclick=\"fnCerrarPeriodo(this)\">"
	            ."<i class=\"fa fa-exchange\"></i>"
	                ."</button>";
                $classColorIcon = "text-success";
	        }
	        $htmlFilas .= "<tr style=\"line-height: 5px;\" class=\"\">";
	        $htmlFilas .= "<td>".$i."</td>";
	        $htmlFilas .= "<td>".$res->anio_periodo."</td>";
	        $htmlFilas .= "<td>".$nombreMes."</td>";
	        $htmlFilas .= "<td class=\"text-center $classColorIcon \">".$spanEstado."</td>";
	        $htmlFilas .= "<td class=\"text-center \">".$botonAction."</td>";
	        $htmlFilas .= "</tr>";
	    }
	    
	    echo json_encode(array("dataFilas"=>$htmlFilas));	   	    
	    
	}
	
	public function OpenPeriodo(){
	    
	    $_id_periodo = $_POST['identificador'];
	    
	    // obtener datos de servidor para periodo
	    $_anio_servidor = date('Y');
	    $_mes_servidor = date('m');
	    
	    $periodo = new PeriodoModel();
	    
	    //datos de periodo solicitado
	    $_anio_periodo = "";
	    $_mes_periodo  = "";
	    
	    $columnas1 = " aa.id_periodo, aa.anio_periodo, aa.mes_periodo, aa.id_tipo_cierre, cc.nombre_tipo_cierre, aa.id_estado, bb.nombre_estado";
	    $tablas1   = " con_periodo aa
    	    INNER JOIN estado bb ON bb.id_estado = aa.id_estado
    	    INNER JOIN con_tipo_cierre cc ON cc.id_tipo_cierre = aa.id_tipo_cierre";
	    $where1    = " aa.id_periodo = '$_id_periodo' ";
	    $id1       = " aa.mes_periodo";
	    $rsConsulta1 = $periodo->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    $_anio_periodo = $rsConsulta1[0]->anio_periodo;
	    $_mes_periodo  = $rsConsulta1[0]->mes_periodo;
	    
	    if( $_anio_servidor == $_anio_periodo ){
	        
	        if( (int)$_mes_periodo < (int)$_mes_servidor ){
	            echo json_encode(array("respuesta"=>"ERROR","mensaje"=>"Periodo no Disponible para Abrir"));
	            exit();
	        }
	    }
	    
	    if( $_anio_servidor < $_anio_periodo ){
	        
	        echo json_encode(array("respuesta"=>"ERROR","mensaje"=>"Periodo no Disponible"));
	        exit();
	    }
	    
	    $columnas2 = " aa.id_periodo, aa.anio_periodo, aa.mes_periodo, aa.id_tipo_cierre, cc.nombre_tipo_cierre, aa.id_estado, bb.nombre_estado";
	    $tablas2   = " con_periodo aa
    	    INNER JOIN estado bb ON bb.id_estado = aa.id_estado
    	    INNER JOIN con_tipo_cierre cc ON cc.id_tipo_cierre = aa.id_tipo_cierre";
	    $where2    = " aa.anio_periodo = '$_anio_periodo' AND bb.nombre_estado ='ABIERTO'";
	    $id2       = " aa.mes_periodo";
	    $rsConsulta2 = $periodo->getCondiciones($columnas2, $tablas2, $where2, $id2);
	    	    
	    if( !empty($rsConsulta2) ){
	        
	        echo json_encode(array("respuesta"=>"ERROR","mensaje"=>"No puede tener dos periodos abiertos"));
	        exit();
	    }
	    
	    //buscar estado de periodo 
	    $columnas3 = " id_estado, nombre_estado";
	    $tablas3   = " public.estado";
	    $where3    = " tabla_estado = 'con_periodo' AND nombre_estado = 'ABIERTO'";
	    $id3       = " id_estado";
	    $rsConsulta3 = $periodo->getCondiciones($columnas3, $tablas3, $where3, $id3);
	    
	    if( empty($rsConsulta3) ){
	        echo json_encode(array("respuesta"=>"ERROR","mensaje"=>"Revisar Tabla Estado del Periodo"));
	        exit();
	    }
	    
	    $_id_estado = $rsConsulta3[0]->id_estado;
	    
	    //actualizar periodo
	    $col   = " id_estado = $_id_estado " ;
	    $tab   = " con_periodo ";
	    $whe   = " id_periodo = $_id_periodo ";
	    
	    $periodo->ActualizarBy($col, $tab, $whe);
	    
	    $error = pg_last_error();
	    if( !empty($error) ){
	        
	        echo json_encode(array("respuesta"=>"ERROR","mensaje"=>"Problemas con los datos solicitados"));
	        exit();
	        
	    }else{
	        echo json_encode(array("respuesta"=>"OK","mensaje"=>"PERIODO CONTABLE ABIERTO"));
	        exit();
	    }
	    
	}
	
	public function ClosePeriodo(){
	    
	    $periodo = new PeriodoModel();
	    $_id_periodo = $_POST['identificador'];	    
	    
	    $columnas1 = " aa.id_periodo, aa.anio_periodo, aa.mes_periodo, aa.id_tipo_cierre, cc.nombre_tipo_cierre, aa.id_estado, bb.nombre_estado";
	    $tablas1   = " con_periodo aa
    	    INNER JOIN estado bb ON bb.id_estado = aa.id_estado
    	    INNER JOIN con_tipo_cierre cc ON cc.id_tipo_cierre = aa.id_tipo_cierre";
	    $where1    = " aa.id_periodo = '$_id_periodo' ";
	    $id1       = " aa.mes_periodo";
	    $rsConsulta1 = $periodo->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    $estado_periodo    = $rsConsulta1[0]->nombre_estado;
	    
	    if( $estado_periodo != "ABIERTO" ){
	        
	        echo json_encode(array("respuesta"=>"ERROR","mensaje"=>"Periodo no se encuentra Abierto"));
	        exit();
	    }
	    
	    //buscar estado de periodo
	    $columnas2 = " id_estado, nombre_estado";
	    $tablas2   = " public.estado";
	    $where2    = " tabla_estado = 'con_periodo' AND nombre_estado = 'CERRADO'";
	    $id2       = " id_estado";
	    $rsConsulta2 = $periodo->getCondiciones($columnas2, $tablas2, $where2, $id2);
	    
	    if( empty($rsConsulta2) ){
	        echo json_encode(array("respuesta"=>"ERROR","mensaje"=>"Revisar Tabla Estado del Periodo"));
	        exit();
	    }
	    
	    $_id_estado = $rsConsulta2[0]->id_estado;
	    
	    //actualizar periodo
	    $col   = " id_estado = $_id_estado " ;
	    $tab   = " con_periodo ";
	    $whe   = " id_periodo = $_id_periodo ";
	    
	    $periodo->ActualizarBy($col, $tab, $whe);
	    
	    $error = pg_last_error();
	    if( !empty($error) ){
	        
	        echo json_encode(array("respuesta"=>"ERROR","mensaje"=>"Problemas con los datos solicitados"));
	        exit();
	        
	    }else{
	        echo json_encode(array("respuesta"=>"OK","mensaje"=>"PERIODO CONTABLE CERRADO"));
	        exit();
	    }
	    
	}
	
	
}
?>