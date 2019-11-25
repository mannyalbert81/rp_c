<?php

class ComprobanteContableController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	
	
	public function consulta_temp_comprobantes()
	{
	    
	    session_start();
	    $_id_usuarios = $_SESSION["id_usuarios"];
	    $temp_comprobantes = new ComprobantesTemporalModel();
	    $where_to="";
	  
	    $columnas = " temp_comprobantes.id_temp_comprobantes,
				          plan_cuentas.id_plan_cuentas,
		    		      plan_cuentas.codigo_plan_cuentas,
						  plan_cuentas.nombre_plan_cuentas,
						  temp_comprobantes.observacion_temp_comprobantes,
						  temp_comprobantes.debe_temp_comprobantes,
						  temp_comprobantes.haber_temp_comprobantes";
	    $tablas ="public.temp_comprobantes,
						  public.usuarios,
						  public.plan_cuentas,
						  public.entidades";
	    $where ="temp_comprobantes.id_plan_cuentas = plan_cuentas.id_plan_cuentas AND
				usuarios.id_usuarios = temp_comprobantes.id_usuario_registra AND
				usuarios.id_entidades = entidades.id_entidades AND
				entidades.id_entidades = plan_cuentas.id_entidades AND usuarios.id_usuarios='$_id_usuarios'";
	    $id="temp_comprobantes.id_temp_comprobantes";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        
	        if(!empty($search)){
	            
	            $where1=" AND (plan_cuentas.codigo_plan_cuentas LIKE '".$search."%' OR plan_cuentas.nombre_plan_cuentas LIKE '".$search."%' OR temp_comprobantes.observacion_temp_comprobantes LIKE '".$search."%')";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	       
	        }
	        
	        
	        $html="";
	        $resultSet=$temp_comprobantes->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$temp_comprobantes->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:250px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_temp_comprobantes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 13px;">Cuenta</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Nombre de la Cuenta</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Descripción</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Debe</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Haber</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;"></th>';
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody >';
	            
	            $i=0;
	            
	            $sumador_debe_total=0;
	            $sumador_haber_total=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $suma_debe= $res->debe_temp_comprobantes;
	                $suma_debe_f=number_format($suma_debe,2);
	                $suma_debe_r=str_replace(",","",$suma_debe_f);
	                $sumador_debe_total+=$suma_debe_r;
	                
	                $suma_haber= $res->haber_temp_comprobantes;
	                $suma_haber_f=number_format($suma_haber,2);
	                $suma_haber_r=str_replace(",","",$suma_haber_f);
	                $sumador_haber_total+=$suma_haber_r;
	                
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 12px;">'.$res->codigo_plan_cuentas.'</td>';
	                $html.='<td style="font-size: 12px;">'.$res->nombre_plan_cuentas.'</td>';
	                $html.='<td style="font-size: 12px;">'.$res->observacion_temp_comprobantes.'</td>';
	                $html.='<td style="font-size: 12px;">'.$res->debe_temp_comprobantes.'</td>';
	                $html.='<td style="font-size: 12px;">'.$res->haber_temp_comprobantes.'</td>';
	                $html.='<td style="font-size: 16px;"><a href="#" data-toggle="tooltip" title="Eliminar" onclick="eliminar_temp_comprobantes('.$res->id_temp_comprobantes.')"><i class="glyphicon glyphicon-trash"></i></a></td>';
	                $html.='</tr>';
	            }
	            
	            $subtotal_debe=number_format($sumador_debe_total,2,'.','');
	            $subtotal_haber=number_format($sumador_haber_total,2,'.','');
	            
	            //para manejar igualdades en debe y haber si es cero las cantidades no coiciden
	            //caso scontrario si vale 1 las cantidades coinciden "CUDRADO"
	            $valor_temporal=0;
	            if($subtotal_debe==$subtotal_haber){
	                $valor_temporal=1;
	            }
	            
	            $letras = $temp_comprobantes->numtoletras($subtotal_debe);
	            
	            $html.='<tr>';
	            $html.='<td style="font-size: 12px;" class="text-right" colspan=1>TOTAL $</td>';
	            $html.='<td style="font-size: 12px;" colspan=2><input type="text" class="form-control" id="valor_letras" name="valor_letras" value="'.$letras.'" readonly>
                        <input type="hidden" id="valor_total_temp" name="valor_total_temp" value="'.$valor_temporal.'"/> </td>';
	            $html.='<td style="font-size: 12px;" class="text-left">'.$subtotal_debe.'</td>';
	            $html.='<td style="font-size: 12px;" class="text-left">'.$subtotal_haber.'</td>';
	            $html.='</tr>';
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_temp_comprobantes("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	        }else{
	            
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:100px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_temp_comprobantes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 13px;">Cuenta</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Nombre de la Cuenta</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Descripción</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Debe</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;">Haber</th>';
	            $html.='<th style="text-align: left;  font-size: 13px;"></th>';	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody >';
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	    
	}
	
	
	
	public function consulta_plan_cuentas(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $usuarios = new UsuariosModel();
	    $catalogo = null; $catalogo = new CatalogoModel();
	    $where_to="";
	    $columnas = "  plan_cuentas.codigo_plan_cuentas, 
                        plan_cuentas.nombre_plan_cuentas";
	    
	    $tablas = "public.plan_cuentas";
	    
	    
	    $where    = " 1=1";
	    
	    $id       = "plan_cuentas.id_plan_cuentas";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        //estado_usuario
	         
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (plan_cuentas.codigo_plan_cuentas LIKE '".$search."%' OR plan_cuentas.nombre_plan_cuentas LIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$usuarios->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$usuarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
	            $html.= "<table id='tabla_plan_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;">Nº</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Código Cuenta</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre Cuenta</th>';
	  
	            
	            if($id_rol==1){
	                
	               }
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->codigo_plan_cuentas.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_plan_cuentas.'</td>';
	                
	                
	                
	                if($id_rol==1){
	                    
	                    }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_plan_cuentas("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
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
	
	
	
	
	
	public function paginate_temp_comprobantes($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_temp_comprobantes(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_temp_comprobantes(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_temp_comprobantes(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_temp_comprobantes(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_temp_comprobantes(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_temp_comprobantes($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_temp_comprobantes(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	
	public function paginate_plan_cuentas($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_plan_cuentas(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_plan_cuentas(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_plan_cuentas(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_plan_cuentas(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_plan_cuentas(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_plan_cuentas($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_plan_cuentas(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	
	public function  consulta_consecutivos(){
	    
	    
	    session_start();
	    $_id_usuarios = $_SESSION["id_usuarios"];
	    $d_comprobantes = new DComprobantesModel();
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $id_tipo_comprobantes =  (isset($_REQUEST['id_tipo_comprobantes'])&& $_REQUEST['id_tipo_comprobantes'] !=NULL)?$_REQUEST['id_tipo_comprobantes']:0;
	    
	    if($action == 'ajax' && $_id_usuarios>0 && $id_tipo_comprobantes > 0)
	    {
	        
    	    $columnas_enc = "entidades.id_entidades,
      							entidades.nombre_entidades,
    		    		        consecutivos.numero_consecutivos,
                                consecutivos.nombre_consecutivos";
    	    $tablas_enc ="public.usuarios,
    						  public.entidades,
    		    		      public.consecutivos";
    	    $where_enc ="consecutivos.id_entidades = entidades.id_entidades AND entidades.id_entidades = usuarios.id_entidades 
                         AND consecutivos.id_tipo_comprobantes='$id_tipo_comprobantes' 
                         AND usuarios.id_usuarios='$_id_usuarios'";
    	    $id_enc="entidades.nombre_entidades";
    	    $resultSet=$d_comprobantes->getCondiciones($columnas_enc ,$tablas_enc ,$where_enc, $id_enc);
    	    
    	    if(!empty($resultSet)){
    	        
    	        $_numero               = $resultSet[0]->numero_consecutivos;    	        
    	        $_nombre_consecutivo   = $resultSet[0]->nombre_consecutivos;
    	        
    	        echo json_encode(array('numero'=>$_numero,'nombre'=>$_nombre_consecutivo));
    	        //echo '{"numero":"'.$_numero.'","nombre":"'.$_nombre_consecutivo.'"}';
    	    }
    	    
	    
	   }
	    
	}

	
	
	
	public function index(){
	
		session_start();
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		      
			$temp_comprobantes=new ComprobantesTemporalModel();
			
			$tipo_comprobante=new TipoComprobantesModel();
			$resultTipCom = $tipo_comprobante->getAll("nombre_tipo_comprobantes");
		
			$forma_pago=new FormaPagoModel();
			$resultFormaPago = $forma_pago->getAll("nombre_forma_pago");
			
			/** buscar tipo comprobantes **/
			$TipoComprobantes = new TipoComprobantesModel();
			$columnas1 = " id_tipo_comprobantes,nombre_tipo_comprobantes";
			$tablas1 = " tipo_comprobantes";
			$where1 = " 1 = 1 ";
			$id1 = "id_tipo_comprobantes";
			$rsConsulta1 = $TipoComprobantes->getCondiciones($columnas1, $tablas1, $where1, $id1);
									
			$nombre_controladores = "ComprobanteContable";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $temp_comprobantes->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
				
			if (!empty($resultPer))
			{
			    	
				$this->view_Contable("ComprobanteContable",array(
				    "resultTipCom"=>$resultTipCom , "resultFormaPago"=>$resultFormaPago, "resultTipoComprobantes"=>$rsConsulta1
					));
			
			
			}else{
				
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Generar Comprobantes"
				
					
				));
				exit();
			}
			
			
		}
		else
		{
	
		    $this->redirect("Usuarios","sesion_caducada");
		}
	
	}
	 
	
	
   public function InsertaComprobanteContable(){
   
   	session_start();
   	$resultado = null;
   	$permisos_rol=new PermisosRolesModel();
   	$plan_cuentas= new PlanCuentasModel();
   	$forma_pago = new FormaPagoModel();
   	$consecutivos = new ConsecutivosModel();
    $ccomprobantes = new CComprobantesModel();
   	$dcomprobantes = new DComprobantesModel();
   	$tem_comprobantes = new ComprobantesTemporalModel();
   	$tipo_comprobantes = new TipoComprobantesModel();
   
   
   	$nombre_controladores = "ComprobanteContable";
   	$id_rol= $_SESSION['id_rol'];
   	$resultPer = $ccomprobantes->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
   
   	if (!empty($resultPer))
   	{
   		
   		if (isset ($_POST["id_entidades"]))
   		{
   
   			$_id_usuarios = $_SESSION['id_usuarios'];
   			
   			$where =  "id_usuario_registra= '$_id_usuarios' ";
   			$resultCom =  $tem_comprobantes->getBy($where);
   			
   			
   			$_id_entidades =$_POST['id_entidades'];
   			$_id_tipo_comprobantes =$_POST['id_tipo_comprobantes'];
   			$resultConsecutivos = $consecutivos->getBy("nombre_consecutivos LIKE '%CONTABLE%' AND id_entidades='$_id_entidades' AND id_tipo_comprobantes='$_id_tipo_comprobantes'");
   			$_id_consecutivos=$resultConsecutivos[0]->id_consecutivos;
   			$_numero_consecutivos=$resultConsecutivos[0]->numero_consecutivos;
   			$_update_numero_consecutivo=((int)$_numero_consecutivos)+1;
   			$_update_numero_consecutivo=str_pad($_update_numero_consecutivo,6,"0",STR_PAD_LEFT);
   			$_ruc_ccomprobantes ="ruc_ccomprobantes";
   			$_nombres_ccomprobantes ="nombres_ccomprobantes";
   			$_retencion_ccomprobantes ="retencion_ccomprobantes";
   			$_valor_ccomprobantes =$_POST['valor_ccomprobantes'];
   			$_concepto_ccomprobantes =$_POST['concepto_ccomprobantes'];
   			$_id_usuario_creador=$_SESSION['id_usuarios'];
   			$_valor_letras =$_POST['valor_letras'];
            $_fecha_ccomprobantes = $_POST['fecha_ccomprobantes']; 
            $_referencia_doc_ccomprobantes ="referencia_doc_ccomprobantes";
            $resultFormaPago = $forma_pago->getBy("nombre_forma_pago LIKE '%NINGUNA%'");
            $_id_forma_pago=$resultFormaPago[0]->id_forma_pago;
            $_numero_cuenta_banco_ccomprobantes="numero_cuenta_banco_ccomprobantes";
            $_numero_cheque_ccomprobantes="numero_cheque_ccomprobantes";
            $_observaciones_ccomprobantes="observaciones_ccomprobantes";
            
            
            
            
   			
   			
   			///PRIMERO INSERTAMOS LA CABEZA DEL COMPROBANTE
   			try
   			{
   					
   				 $funcion = "ins_ccomprobantes";
   				 $parametros = "'$_id_entidades',
                '$_id_tipo_comprobantes',
                '$_numero_consecutivos',
                '$_ruc_ccomprobantes',
                '$_nombres_ccomprobantes',
                '$_retencion_ccomprobantes',
                '$_valor_ccomprobantes',
                '$_concepto_ccomprobantes',
                '$_id_usuario_creador',
                '$_valor_letras',
                '$_fecha_ccomprobantes',
                '$_id_forma_pago',
                '$_referencia_doc_ccomprobantes',
                '$_numero_cuenta_banco_ccomprobantes',
                '$_numero_cheque_ccomprobantes',
                '$_observaciones_ccomprobantes' ";
   				 
   				 
   				 
   				$ccomprobantes->setFuncion($funcion);
   				$ccomprobantes->setParametros($parametros);
   				$resultado=$ccomprobantes->Insert();
   				
   				
   				$resultConsecutivo=$consecutivos->UpdateBy("numero_consecutivos='$_update_numero_consecutivo'", "consecutivos", "id_consecutivos='$_id_consecutivos'");
   
   				
   				///INSERTAMOS DETALLE  DEL MOVIMIENTO
   					
   				foreach($resultCom as $res)
   				{
   					
   					try
   					{
   						$_id_plan_cuentas = $res->id_plan_cuentas;
   						$_descripcion_dcomprobantes = $res->observacion_temp_comprobantes;
   						$_debe_dcomprobantes = $res->debe_temp_comprobantes;
   						$_haber_dcomprobantes = $res->haber_temp_comprobantes;
   
   						$resultComprobantes = $ccomprobantes->getBy("numero_ccomprobantes ='$_numero_consecutivos' AND id_entidades ='$_id_entidades' AND id_tipo_comprobantes='$_id_tipo_comprobantes'");
   						$_id_ccomprobantes=$resultComprobantes[0]->id_ccomprobantes;
   						
   						
   						$funcion = "ins_dcomprobantes";
   						$parametros = "'$_id_ccomprobantes','$_numero_consecutivos','$_id_plan_cuentas', '$_descripcion_dcomprobantes', '$_debe_dcomprobantes', '$_haber_dcomprobantes'";
   						$dcomprobantes->setFuncion($funcion);
   						$dcomprobantes->setParametros($parametros);
   						$resultado=$dcomprobantes->Insert();
   						
   						$resultSaldoIni = $plan_cuentas->getBy("id_plan_cuentas ='$_id_plan_cuentas' AND id_entidades ='$_id_entidades'");
   						$_saldo_ini=$resultSaldoIni[0]->saldo_fin_plan_cuentas;
   						
   						$_fecha_mayor = getdate();
   						$_fecha_año=$_fecha_mayor['year'];
   						$_fecha_mes=$_fecha_mayor['mon'];
   						$_fecha_dia=$_fecha_mayor['mday'];
   							
   						$_fecha_actual=$_fecha_año.'-'.$_fecha_mes.'-'.$_fecha_dia;
   							
   						////llamas a la funcion mayoriza();
   						//$resul = $dcomprobantes->Mayoriza($_id_plan_cuentas, $_id_ccomprobantes, $_fecha_actual, $_debe_dcomprobantes, $_haber_dcomprobantes, $_saldo_ini);
   						//$_cadena = $_id_plan_cuentas .'-'. $_id_ccomprobantes .'-'. $_fecha_actual .'-'. $_debe_dcomprobantes .'-'. $_haber_dcomprobantes .'-'. $_saldo_ini;
   							
   							
   						///borro de las solicitudes el carton
   						$where_del = "id_usuario_registra= '$_id_usuarios'";
   						$tem_comprobantes->deleteByWhere($where_del);
   							
   					   
   					} catch (Exception $e)
   					{
   						$this->view("Error",array(
   								"resultado"=>"Eror al Insertar Comprobante Contable ->". $id
   						));
   						exit();
   					}
   						
   				}					
   					
   			}
   			catch (Exception $e)
   			{
   
   			}
   
   		}	
   		
   		$this->redirect("ComprobanteContable","index")	;
   	}
   	else
   	{
   		$this->view("Error",array(
   				"resultado"=>"No tiene Permisos de Guardar Comprobante Contable"
   
   		));
   
   	}
   
   }
   
    
   
       public function eliminar_temp_comprobantes(){
           
           session_start();
           
           $_id_usuarios = $_SESSION['id_usuarios'];
           
           $id_temp_comprobantes = (isset($_REQUEST['id_temp_comprobantes'])&& $_REQUEST['id_temp_comprobantes'] !=NULL)?$_REQUEST['id_temp_comprobantes']:0;
           
           if($_id_usuarios!='' && $id_temp_comprobantes>0){
               
               $temp_comprobantes = new ComprobantesTemporalModel();
               
               $where = "id_temp_comprobantes = $id_temp_comprobantes AND id_usuario_registra = $_id_usuarios ";
               $resultado=$temp_comprobantes->deleteById($where);
               
               echo "1";
              
           }
       }
       
       
       
       
       public function insertar_temp_comprobantes(){
           
           session_start();
           $_id_usuarios = $_SESSION['id_usuarios'];
           $_id_plan_cuentas = (isset($_REQUEST['plan_cuentas'])&& $_REQUEST['plan_cuentas'] !=NULL)?$_REQUEST['plan_cuentas']:0;
           $_descripcion_dcomprobantes = (isset($_REQUEST['descripcion_dcomprobantes'])&& $_REQUEST['descripcion_dcomprobantes'] !=NULL)?$_REQUEST['descripcion_dcomprobantes']:'';
           $_debe_dcomprobantes = (isset($_REQUEST['debe_dcomprobantes'])&& $_REQUEST['debe_dcomprobantes'] !=NULL)?$_REQUEST['debe_dcomprobantes']:0;
           $_haber_dcomprobantes = (isset($_REQUEST['haber_dcomprobantes'])&& $_REQUEST['haber_dcomprobantes'] !=NULL)?$_REQUEST['haber_dcomprobantes']:0;
           
           $temp_comprobantes = new ComprobantesTemporalModel();
           
           if($_id_usuarios!='' && $_id_plan_cuentas>0){
                       
                       if ($_debe_dcomprobantes=="")
                       {
                           $_debe_dcomprobantes=0;
                           
                       }
                      
                       if ($_haber_dcomprobantes=="")
                       {
                           $_haber_dcomprobantes=0;
                           
                       }
           
           $_debe_dcomprobantes= str_replace(',', '', $_debe_dcomprobantes);
           $_haber_dcomprobantes= str_replace(',', '', $_haber_dcomprobantes);
           
                       $funcion = "ins_temp_comprobantes";
                       $parametros = "'$_id_usuarios','$_id_plan_cuentas','$_descripcion_dcomprobantes','$_debe_dcomprobantes','$_haber_dcomprobantes'";
                       $temp_comprobantes->setFuncion($funcion);
                       $temp_comprobantes->setParametros($parametros);
                       $resultado=$temp_comprobantes->Insert();
                   
                       echo "1";
               
           }
           
       }
       
       
		
	
	public function AutocompleteComprobantesCodigo(){
		
		session_start();
		$_id_usuarios= $_SESSION['id_usuarios'];
		$plan_cuentas = new PlanCuentasModel();
	    $codigo_plan_cuentas = $_GET['term'];
	
	    $columnas ="plan_cuentas.codigo_plan_cuentas";
		$tablas =" public.usuarios, 
				  public.entidades, 
				  public.plan_cuentas";
		$where ="plan_cuentas.codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
 				 plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
		$id ="plan_cuentas.codigo_plan_cuentas";
		
		
		$resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
	
	
		if(!empty($resultSet)){
				
			foreach ($resultSet as $res){
	
			    $_respuesta[] = $res->codigo_plan_cuentas;
			}
			echo json_encode($_respuesta);
		}
	
	}
	
	
	
	
	public function AutocompleteComprobantesDevuelveNombre(){
		session_start();
		$_id_usuarios= $_SESSION['id_usuarios'];
		
		
		$plan_cuentas = new PlanCuentasModel();
		$codigo_plan_cuentas = $_POST['codigo_plan_cuentas'];
		
		
		$columnas ="plan_cuentas.codigo_plan_cuentas,
				  plan_cuentas.nombre_plan_cuentas,
				  plan_cuentas.id_plan_cuentas";
		$tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
		$where ="plan_cuentas.codigo_plan_cuentas = '$codigo_plan_cuentas' AND entidades.id_entidades = usuarios.id_entidades AND
		plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
		$id ="plan_cuentas.codigo_plan_cuentas";
		
		
		$resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
		
	
		$respuesta = new stdClass();
	
		if(!empty($resultSet)){
				
			$respuesta->nombre_plan_cuentas = $resultSet[0]->nombre_plan_cuentas;
			$respuesta->id_plan_cuentas = $resultSet[0]->id_plan_cuentas;
				
			echo json_encode($respuesta);
		}
	
	}
	
	
	
	
	public function AutocompleteComprobantesNombre(){
	
		session_start();
		$_id_usuarios= $_SESSION['id_usuarios'];
		$plan_cuentas = new PlanCuentasModel();
		$nombre_plan_cuentas = $_GET['term'];
	
		//$resultSet=$plan_cuentas->getBy("codigo_plan_cuentas LIKE '$codigo_plan_cuentas%'");
		 
		 
		 
		$columnas ="plan_cuentas.codigo_plan_cuentas,
				  plan_cuentas.nombre_plan_cuentas,
				  plan_cuentas.id_plan_cuentas";
		$tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
		$where ="plan_cuentas.nombre_plan_cuentas LIKE '$nombre_plan_cuentas%' AND entidades.id_entidades = usuarios.id_entidades AND
		plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
		$id ="plan_cuentas.codigo_plan_cuentas";
	
	
		$resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
		$_nombre_plan_cuentas = array();
	
		if(!empty($resultSet)){
	
			foreach ($resultSet as $res){
	
				$_nombre_plan_cuentas[] = $res->nombre_plan_cuentas;
			}
			echo json_encode($_nombre_plan_cuentas);
		}
	
	}
	
	
	
	
	public function AutocompleteComprobantesDevuelveCodigo(){
	
		session_start();
		$_id_usuarios= $_SESSION['id_usuarios'];
		
		$plan_cuentas = new PlanCuentasModel();
	
		$nombre_plan_cuentas = $_POST['nombre_plan_cuentas'];
	

		$columnas ="plan_cuentas.codigo_plan_cuentas,
				  plan_cuentas.nombre_plan_cuentas,
				  plan_cuentas.id_plan_cuentas";
		$tablas =" public.usuarios,
				  public.entidades,
				  public.plan_cuentas";
		$where ="plan_cuentas.nombre_plan_cuentas = '$nombre_plan_cuentas' AND entidades.id_entidades = usuarios.id_entidades AND
		plan_cuentas.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND plan_cuentas.nivel_plan_cuentas in ('4', '5')";
		$id ="plan_cuentas.codigo_plan_cuentas";
		
		
		$resultSet=$plan_cuentas->getCondiciones($columnas, $tablas, $where, $id);
		
	
		$respuesta = new stdClass();
	
		if(!empty($resultSet)){
	
			$respuesta->codigo_plan_cuentas = $resultSet[0]->codigo_plan_cuentas;
			$respuesta->id_plan_cuentas = $resultSet[0]->id_plan_cuentas;
	
			echo json_encode($respuesta);
		}
	
	}
	
	
	
	public function insertacomprobante(){
	    
	    session_start();
	    $resultado = null;
	    
	    $ccomprobantes = new CComprobantesModel();    
	    
	    $nombre_controladores = "ComprobanteContable";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $ccomprobantes->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
	    
	    
	    if (!empty($resultPer))
	    {
	        
	        if(isset($_POST['action']) && $_POST['action']=='ajax'){
	            
	            //datos de la vista
	            $_id_tipo_comprobantes         = $_POST['id_tipo_comprobantes'];	            
	            $_id_proveedores               = $_POST['id_proveedores'];
	            $_retencion_ccomprobantes      = $_POST['retencion_proveedor']; 
	            $_valor_letras                 = $_POST['valor_letras'];	            
	            $_concepto_ccomprobantes       = $_POST['concepto_ccomprobantes'];	            
	            $_fecha_ccomprobantes          = $_POST['fecha_ccomprobantes'];	            
	            $_referencia_doc_ccomprobantes = $_POST['referencia_ccomprobantes'];
	            $_id_forma_pago                = $_POST['id_forma_pago'];
	            $_num_cuenta_ban_ccomprobantes = $_POST['num_cuenta_ccomprobantes'];
	            $_num_cheque_ccomprobantes     = $_POST['num_cheque_ccomprobantes'];
	            $_observacion_ccomprobantes    = $_POST['observacion_ccomprobantes'];
	            $_id_usuarios                  = $_SESSION['id_usuarios'];
	            //comienza insertado de comprobante
	            
	            $funcion = "fn_con_agrega_comprobante";
	            
	            $parametros = "'$_id_usuarios',
                '$_id_tipo_comprobantes', 
                '$_retencion_ccomprobantes',
                '$_concepto_ccomprobantes',
                '$_valor_letras',
                '$_fecha_ccomprobantes',
                '$_id_forma_pago',
                '$_referencia_doc_ccomprobantes',
                '$_num_cuenta_ban_ccomprobantes',
                '$_num_cheque_ccomprobantes',
                '$_observacion_ccomprobantes',
                '$_id_proveedores' ";
	            
	            //print $parametros;
	            //die();
	            $ccomprobantes->setFuncion($funcion);
	            $ccomprobantes->setParametros($parametros);
	            $resultado=$ccomprobantes->llamafuncion();	            
	            
	            //print_r($parametros);
	            $respuesta='';
	            
	            if(!empty($resultado) && count($resultado) > 0)  {
	                
	                foreach ($resultado[0] as $k => $v)
	                    $respuesta = $v;
	            }
	            
	            echo json_encode(array('success'=>0,'mensaje'=>$respuesta));
	           
	        }
	        
	    }
	    else
	    {
	            echo "No tiene Permisos de Guardar Comprobante Contable";
	       
	    }
	    
	}
	
	
	
	public function generapdf(){
	    
	        require dirname(__FILE__).'\..\view\fpdf\fpdf.php';
	        
	        
	        $pdf = new FPDF();
	        
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(true, 20);
            
            //para ubicaciones
            
            $y = $pdf->GetY();
            //$x = $pdf->GetX();
            $mid_x = $pdf->GetPageWidth() / 2;
            $octavo_y = $pdf->GetPageHeight()/3;
           
            $tama�o = 10; //Tama�o de Pixel
            $level = 'L'; //Precisi�n Baja
            $framSize = 3; //Tama�o en blanco
            $contenido = 'pasante'; //Texto
            
           
            
            $pdf->SetFont('Arial','',50);
            //$y = $pdf->GetY();
            $pdf->SetXY(20, $y+90);
            $pdf->MultiCell(0,30, utf8_decode($contenido) ,1,'L');
            
	        $pdf->Output();
	    
	    
	}
	

	/******* CAMBIOS DC 05-11-2019 ******/
	/************************************************************* BEGIN UTILITARIOS AJAX ********************************************************************/
	
	public function getTipoComprobantes(){
	    
	    $TipoComprobantes = new TipoComprobantesModel();	    
	    $respuesta = array();
	    $columnas1 = " id_tipo_comprobantes,nombre_tipo_comprobantes";
	    $tablas1 = " tipo_comprobantes";
	    $where1 = " 1 = 1 ";
	    $id1 = "id_tipo_comprobantes";
	    $rsConsulta1 = $TipoComprobantes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    if( !empty($rsConsulta1) ){
	        $respuesta['respuesta'] = 1;
	        $respuesta['data'] = $rsConsulta1;	        
	    }
	    echo json_encode($respuesta);
	}
	
	public function  getNumeroComprobante(){
	    
	    $TipoComprobantes = new TipoComprobantesModel();
	    
	    $id_tipo_comprobantes = isset($_POST['id_tipo_comprobantes']) ? $_POST['id_tipo_comprobantes'] : 0;
	    
	    $columnas1 = " lpad(bb.valor_consecutivos::text,bb.espacio_consecutivos,'0') numero_consecutivos, aa.nombre_tipo_comprobantes";
	    $tablas1   = " tipo_comprobantes aa
                       INNER JOIN consecutivos bb ON bb.id_tipo_comprobantes = aa.id_tipo_comprobantes";
	    $where1    = " aa.id_tipo_comprobantes='$id_tipo_comprobantes'";
	    $id1       = " aa.id_tipo_comprobantes";
	    $rsConsulta1   = $TipoComprobantes->getCondiciones($columnas1 ,$tablas1 ,$where1, $id1);
	    
	    if( !empty( $rsConsulta1 ) ){
	        echo json_encode(array('respuesta'=>'1','data'=>$rsConsulta1));
	    }
	    	    
	}
	
	/**
	 *  completar el input con codigo
	 */
	public function autompleteCodigo(){
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    if(isset($_GET['term'])){
	        
	        $codigo_plan_cuentas = $_GET['term'];
	        
	        $columnas = "id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas";
	        $tablas = "public.plan_cuentas";
	        $where = "codigo_plan_cuentas LIKE '$codigo_plan_cuentas%' AND nivel_plan_cuentas > 3";
	        $id = "codigo_plan_cuentas ";
	        $limit = "LIMIT 10";
	        
	        $rsPlanCuentas = $planCuentas->getCondicionesPag($columnas,$tablas,$where,$id,$limit);
	        
	        $respuesta = array();
	        
	        if(!empty($rsPlanCuentas) ){
	            
	            foreach ($rsPlanCuentas as $res){
	                
	                $_cls_plan_cuentas = new stdClass;
	                $_cls_plan_cuentas->id = $res->id_plan_cuentas;
	                $_cls_plan_cuentas->value = $res->codigo_plan_cuentas;
	                $_cls_plan_cuentas->label = $res->codigo_plan_cuentas.' | '.$res->nombre_plan_cuentas;
	                $_cls_plan_cuentas->nombre = $res->nombre_plan_cuentas;
	                
	                $respuesta[] = $_cls_plan_cuentas;
	            }
	            
	            echo json_encode($respuesta);
	            
	        }else{
	            
	            echo '[{"id":"","value":"Cuenta No Encontrada"}]';
	        }
	        
	    }
	}
	
	/**
	 *  completar el input con nombre plan cuentas
	 */
	public function autompleteNombrePlanCuentas(){
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    if(isset($_GET['term'])){
	        
	        $nombre_plan_cuentas = $_GET['term'];
	        
	        $columnas = "id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas";
	        $tablas = "public.plan_cuentas";
	        $where = "nombre_plan_cuentas ILIKE '$nombre_plan_cuentas%' AND nivel_plan_cuentas > 3";
	        $id = "codigo_plan_cuentas ";
	        $limit = "LIMIT 10";
	        
	        $rsPlanCuentas = $planCuentas->getCondicionesPag($columnas,$tablas,$where,$id,$limit);
	        
	        $respuesta = array();
	        
	        if(!empty($rsPlanCuentas) ){
	            
	            foreach ($rsPlanCuentas as $res){
	                
	                $_cls_plan_cuentas = new stdClass;
	                $_cls_plan_cuentas->id = $res->id_plan_cuentas;
	                $_cls_plan_cuentas->value = $res->nombre_plan_cuentas;
	                $_cls_plan_cuentas->label = $res->codigo_plan_cuentas.' | '.$res->nombre_plan_cuentas;
	                $_cls_plan_cuentas->nombre = $res->nombre_plan_cuentas;
	                $_cls_plan_cuentas->codigo = $res->codigo_plan_cuentas;
	                
	                $respuesta[] = $_cls_plan_cuentas;
	            }
	            
	            echo json_encode($respuesta);
	            
	        }else{
	            
	            echo '[{"id":"","value":"Cuenta No Encontrada"}]';
	        }
	        
	    }
	}
	
	/************************************************************* END UTILITARIOS AJAX ********************************************************************/
	
	
}
?>