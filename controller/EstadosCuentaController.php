<?php

class EstadosCuentaController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
     	$estados=new EstadoModel();
					//Conseguimos todos los usuarios
     	$resultEdit = "";
	
		session_start();
        
	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "Estados";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $estados->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				
				$bancos= new BancosModel();
				$columnas="tes_bancos.id_bancos, tes_bancos.nombre_bancos";
				$tablas="public.tes_bancos";
				$where="tes_bancos.nombre_bancos='PICHINCHA' OR tes_bancos.nombre_bancos='RUMIÑAHUI' OR tes_bancos.nombre_bancos='INTERNACIONAL'";
				$id="tes_bancos.id_bancos";
				$resultBancos=$bancos->getCondiciones($columnas, $tablas, $where, $id);
				
				$columnas="DISTINCT tes_log_estado_cuentas.anio_archivo_estado_cuenta";
				$tablas="public.tes_estado_cuenta_bancos INNER JOIN public.tes_bancos 
                        ON  tes_estado_cuenta_bancos.id_bancos = tes_bancos.id_bancos
                        INNER JOIN public.tes_log_estado_cuentas
                        ON tes_estado_cuenta_bancos.id_log_estado_cuentas = tes_log_estado_cuentas.id_log_estado_cuentas";
				$where="1=1";
				$id="tes_log_estado_cuentas.anio_archivo_estado_cuenta";
				$resultAnios=$bancos->getCondiciones($columnas, $tablas, $where, $id);
				
				$this->view_tesoreria("EstadosCuenta",array(
				    "resultBancos" =>$resultBancos, "resultAnios"=>$resultAnios
			
				));
		
				
				
			}
			else
			{
			    $this->view_Administracion("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Grupos"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function SubirEstadoCuenta()
	{
	    session_start();
	    
	    $estado_cuentas=new EstadoCuentasModel();
	  $usuario=$_SESSION['usuario_usuarios'];
	    if ($_FILES['file']['tmp_name']!="")
	    {
	        $directorio = $_SERVER['DOCUMENT_ROOT'].'/rp_c/tes_documento_estado_cuentas/';
	        
	        $mes=$_POST['mes'];
	        $anio=$_POST['anio'];
	        $id_banco=$_POST['id_banco'];
	     	        
	        $nombre = $_FILES['file']['name'];
	        $tipo = $_FILES['file']['type'];
	        $tamano = $_FILES['file']['size'];
	        
	        move_uploaded_file($_FILES['file']['tmp_name'],$directorio.$nombre);
	        
	        $funcion="ins_log_estado_cuentas";
	        
	       $parametros = "'$nombre',
                     '$directorio.$nombre',
                     '$usuario',
                     $id_banco,
                     $mes,
                     $anio";
	        $estado_cuentas->setFuncion($funcion);
	        $estado_cuentas->setParametros($parametros);
	        $resultado=$estado_cuentas->Insert();
	        
	        $where="id_bancos=".$id_banco." AND mes_archivo_estado_cuenta=".$mes." AND anio_archivo_estado_cuenta=".$anio;
	        
	        $id_log=$estado_cuentas->getCondicionesDesc("id_log_estado_cuentas", "tes_log_estado_cuentas", $where, "id_log_estado_cuentas");
	        
	        echo $id_log[0]->id_log_estado_cuentas;
	    }
	}
	
	public function CtasProcesadas($totctas, $proctas, $id_log)
	{
	    $estado_cuentas=new EstadoCuentasModel();
	    $funcion="ins_log_estado_cuentas_procesadas";
	    $parametros="$id_log,
                     $totctas,
                     $proctas";
        $estado_cuentas->setFuncion($funcion);
        $estado_cuentas->setParametros($parametros);
        $resultado=$estado_cuentas->Insert();

	}
	
	public function SubirDatosBase()
	{
	    session_start();
	    $id_usuarios=$_SESSION['id_usuarios'];
	    $estado_cuentas=new EstadoCuentasModel();
	    $estado_cuentas->beginTran();
	    $tipo=$_POST['tipo'];
	    $id_bancos=$_POST['id_bancos'];
	    $total_ctas=$_POST['total_cuentas'];
	    $id_log=$_POST['id_log'];
	    $datos = json_decode($_POST['estado_cuentas'],true);
	    $funcion="ins_tes_estado_cuenta_bancos";
	    $len=sizeof($datos);
	    $respuesta=true;
	    $ctas_proc=0;
	    $i=0;
	    if ($tipo==0)
	    {
	        $fecha=explode("/", $datos[1][0]);
	        $fecha_inicio=$fecha[2]."-".$fecha[1]."-01";
	        $lastday = date('t',strtotime($fecha_inicio));
	        $fecha_fin=$fecha[2]."-".$fecha[1]."-".$lastday;
	        $estado_cuentas->deleteByWhere("id_bancos=".$id_bancos." AND fecha_estado_cuenta_bancos BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
	        $i=1;
	    }
	    else
	    {
	        $fecha=explode("-", $datos[4][0]);
	        $fecha_inicio=$fecha[0]."-".$fecha[1]."-01";
	        $lastday = date('t',strtotime($fecha_inicio));
	        $fecha_fin=$fecha[0]."-".$fecha[1]."-".$lastday;
	        $estado_cuentas->deleteByWhere("id_bancos=".$id_bancos." AND fecha_estado_cuenta_bancos BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
	        $i=4;
	    }
	    
	    for ($i; $i<$len-1; $i++)
	    {
	        if ($tipo==0)
	        {
	            $fecha=$datos[$i][0];
	            $id_tipo_mov=$estado_cuentas->getCondiciones("id_tipo_movimientos", "tes_tipo_movimientos", "codigo_tipo_movimientos='".$datos[$i][3]."'", "id_tipo_movimientos");
	            $id_tipo_mov=$id_tipo_mov[0]->id_tipo_movimientos;
	            $concepto=$datos[$i][2];
	            $numero_doc=$datos[$i][4];
	            $datos[$i][6]=number_format((float)$datos[$i][6], 2, '', '.');
	            if ($datos[$i][3]=="D")
	            {
	             $debito=$datos[$i][6];
	             $credito=0;
	            }
	            else
	            {
	                $debito=0;
	                $credito=$datos[$i][6];
	            }
	            $saldo=number_format((float)$datos[$i][7], 2, '', '.');
	            $parametros = "$id_bancos,
                     '$fecha',
                     $id_tipo_mov,
                     '$concepto',
                     '$numero_doc',
                     '$debito',
                     '$credito',
                     '$saldo',
                     $id_usuarios,
                      NULL,
                      NULL,
                      $id_log";
	        }
	        else
	        {
	            $fecha=$datos[$i][0];
	            $id_tipo_mov=$estado_cuentas->getCondiciones("id_tipo_movimientos", "tes_tipo_movimientos", "codigo_tipo_movimientos='".$datos[$i][1]."'", "id_tipo_movimientos");
	            $id_tipo_mov=$id_tipo_mov[0]->id_tipo_movimientos;
	            $concepto=$datos[$i][2];
	            $debito=number_format((float)$datos[$i][6], 2, '', '.');
	            $credito=number_format((float)$datos[$i][7], 2, '', '.');
	            $saldo=number_format((float)$datos[$i][8], 2, '', '.');
	            $desc_ad=$datos[$i][4];
	            $ciudad=$datos[$i][9];
	            $parametros = "$id_bancos,
                     '$fecha',
                     $id_tipo_mov,
                     '$concepto',
                     NULL,
                     '$debito',
                     '$credito',
                     '$saldo',
                     $id_usuarios,
                      '$desc_ad',
                      '$ciudad',
                      $id_log";
	        }
	        $estado_cuentas->setFuncion($funcion);
	        $estado_cuentas->setParametros($parametros);
	        $resultado=$estado_cuentas->Insert();
	        echo $resultado;
	        if($resultado!="Insertado Correctamente"){
	            $respuesta = false;
	            $estado_cuentas->endTran('ROLLBACK');
	            $this->CtasProcesadas($total_ctas, $ctas_proc, $id_log);
	            break;
	            
	        }
	        else
	        {$ctas_proc++;}
	    }
	    
	    
	    if($respuesta){
	        $estado_cuentas->endTran('COMMIT');
	        $this->CtasProcesadas($total_ctas, $ctas_proc, $id_log);
	    }
	}
	
	
	public function consulta_documentos(){
	    
	    session_start();
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");	    
	    $mes_archivo=$_POST['mes_archivo'];
	    $anio_archivo = $_POST['anio_archivo'];
	    $id_banco= $_POST['id_banco'];
	    $estado_cuenta = new EstadoCuentasModel();
	    $columnas = "DISTINCT tes_bancos.id_bancos, tes_bancos.nombre_bancos, 
                        tes_log_estado_cuentas.nombre_archivo_estado_cuentas,
                        tes_log_estado_cuentas.mes_archivo_estado_cuenta,
                        tes_log_estado_cuentas.anio_archivo_estado_cuenta,
                        tes_log_estado_cuentas.id_log_estado_cuentas";
	    
	    $tablas = "tes_estado_cuenta_bancos INNER JOIN public.tes_bancos 
                    ON  tes_estado_cuenta_bancos.id_bancos = tes_bancos.id_bancos
                    INNER JOIN public.tes_log_estado_cuentas
                    ON tes_estado_cuenta_bancos.id_log_estado_cuentas = tes_log_estado_cuentas.id_log_estado_cuentas";
	    
	    
	    $where    = "1=1";
	    
	    if ($id_banco!=0)
	    {
	        $where.= " AND tes_bancos.id_bancos =".$id_banco;
	    }
	    if ($mes_archivo!="mes")
	    {
	        $mes_archivo++;
	        $where.= " AND tes_log_estado_cuentas.mes_archivo_estado_cuenta =".$mes_archivo;
	    }
	    if($anio_archivo!=0)
	    {
	        $where.=" AND tes_log_estado_cuentas.anio_archivo_estado_cuenta =".$anio_archivo;
	    }
	    
	    $id       = "tes_log_estado_cuentas.mes_archivo_estado_cuenta,
                     tes_log_estado_cuentas.anio_archivo_estado_cuenta, tes_bancos.id_bancos";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        
	        $html="";
	        $resultSet=$estado_cuenta->getCantidad("DISTINCT tes_bancos.id_bancos", $tablas, $where);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$estado_cuenta->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:425px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_archivos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 16px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 16px;">Banco</th>';
	            $html.='<th style="text-align: left;  font-size: 16px;">Archivo</th>';
	            $html.='<th style="text-align: left;  font-size: 16px;">Mes</th>';
	            $html.='<th style="text-align: left;  font-size: 16px;">Año</th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 15px;">'.$i.'</td>';
	                $html.='<td style="font-size: 15px;">'.$res->nombre_bancos.'</td>';
	                $html.='<td style="font-size: 15px;"><a href="javascript:VisualizarDocumentos('.$res->id_log_estado_cuentas.')">'.$res->nombre_archivo_estado_cuentas.'</a></td>';
	                $html.='<td style="font-size: 15px;">'.$meses[$res->mes_archivo_estado_cuenta-1].'</td>';
	                $html.='<td style="font-size: 15px;">'.$res->anio_archivo_estado_cuenta.'</td>';
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_documentos("index.php", $page, $total_pages, $adjacents,"MostrarDocumentos").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registros de reloj...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
	public function paginate_documentos($reload, $page, $tpages, $adjacents,$funcion='') {
	    
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
	
	public function MostrarPR($resultSet, $banco, $periodo, $cantc, $cantd)
	{
	    $i=0;
	    $totalmovimientos=sizeof($resultSet);
	    $imagen="";
	    
	    if($banco=="PICHINCHA") $imagen="<img  style=\"width: 100%;  height:50px; width:239px; float:left; margin-bottom: 12px \" align=\"right\" src=\"view/images/banco-pichincha-logo.png\">";
	    if($banco=="RUMIÑAHUI") $imagen="<img  style=\"width: 100%;  height:75px; width:120px; float:left; margin-bottom: 12px \" align=\"right\" src=\"view/images/bgr-logo.jpg\">";
	    $tabla="<h4>".$imagen."</h4>";
	    $tabla.="<table align=\"right\" class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>".
	    "<tr>".
	    "<td align=\"right\" colspan=\"3\" style=\"font-size:20px\"><b>".$periodo."</b></td>".
	    "</tr>".
	    "<tr>".
	    "<td align=\"left\" style=\"vertical-align: top; font-size:18px; padding-right: 12px; \" rowspan=\"3\" ><b>Movimientos</b></td>".
	    "<td align=\"left\" style=\"font-size:16px\"><b>Crédito:</b></td>".
	    "<td align=\"right\" style=\"font-size:16px\">".$cantc."</td>".
	    "</tr>".
	    "<tr>".
	    "<td align=\"left\" style=\"font-size:16px\"><b>Débito:</b></td>".
	    "<td align=\"right\" style=\"font-size:16px\">".$cantd."</td>".
	    "</tr>".
	    "<tr>".
	    "<td align=\"left\" style=\"font-size:16px\"><b>Total:</b></td>".
	    "<td align=\"right\" style=\"font-size:16px\">".$totalmovimientos."</td>".
	    "</tr>".
	    "</table>";
	    $tabla.="<table id='tabla_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	    $tabla.="<tr>";
	    $tabla.="<th>N</th>";
	    $tabla.="<th>Fecha</th>";
	    $tabla.="<th>Concepto</th>";
	    $tabla.="<th>Tipo</th>";
	    $tabla.="<th>Documento</th>";
	    $tabla.="<th>Débito</th>";
	    $tabla.="<th>Crédito</th>";
	    $tabla.="<th>Saldo</th>";
	    $tabla.="</tr>";
	    foreach ($resultSet as $res)
	    {
	        $i++;
	        $tabla.="<tr>";
	        $tabla.="<td>".$i."</td>";
	        $tabla.="<td>".$res->fecha_estado_cuenta_bancos."</td>";
	        $tabla.="<td>".$res->concepto_estado_cuenta_bancos."</td>";
	        $tabla.="<td>".$res->codigo_tipo_movimientos."</td>";
	        $tabla.="<td>".$res->numero_documento_estado_cuenta_bancos."</td>";
	        $tabla.="<td>".$res->debito_estado_cuenta_bancos."</td>";
	        $tabla.="<td>".$res->credito_estado_cuenta_bancos."</td>";
	        $tabla.="<td>".$res->saldo_estado_cuenta_bancos."</td>";
	        $tabla.="</tr>";
	    }
	    $tabla.="</table>";
	    echo $tabla;
	}
	
	public function MostrarINTL($resultSet, $banco, $periodo, $cantMC, $cantDP, $cantMD,$cantCO, $cant3O)
	{
	    $i=0;
	    $totalmovimientos=sizeof($resultSet);
	    $imagen="<img  style=\"width: 100%;  height:50px; width:239px; float:left;margin-bottom: 12px \" align=\"right\" src=\"view/images/banco-internacional-logo.png\">";
	    $tabla="<h4>".$imagen."</h4>";
	    $tabla.="<table align=\"right\" class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>".
	   	    "<tr>".
	   	    "<td align=\"right\" colspan=\"7\" style=\"font-size:20px\"><b>".$periodo."</b></td>".
	   	    "</tr>".
	   	    "<tr>".
	   	    "<td align=\"left\" style=\"vertical-align: top; font-size:18px; padding-right: 12px; \" rowspan=\"3\" ><b>Movimientos</b></td>".
	   	    "<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b>Movimientos Crédito:</b></td>".
	   	    "<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\">".$cantMC."</td>".
	   	    "<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b>Mendiante Débito:</b></td>".
	   	    "<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\">".$cantMD."</td>".
	   	    "<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b>Depósitos:</b></td>".
	   	    "<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\">".$cantDP."</td>".
	   	    "</tr>".
	   	    "<tr>".
	   	    "<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b></b></td>".
	   	    "<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\"></td>".
	   	    "<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b>Costos de Operación:</b></td>".
	   	    "<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\">".$cantCO."</td>".
	   	    "<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b>Interes:</b></td>".
	   	    "<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\">".$cant3O."</td>".
	   	    "</tr>".
	   	    "<tr>".
	   	    "<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b></b></td>".
	   	    "<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\"></td>".
	   	    "<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b></b></td>".
	   	    "<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\"></td>".
	   	    "<td align=\"left\" style=\"font-size:16px\"><b>Total:</b></td>".
	   	    "<td align=\"right\" style=\"font-size:16px\">".$totalmovimientos."</td>".
	   	    "</tr>".
	   	    "</table>";
	    $tabla.="<table id='tabla_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	    $tabla.="<tr>";
	    $tabla.="<th>N</th>";
	    $tabla.="<th>Fecha</th>";
	    $tabla.="<th>Concepto</th>";
	    $tabla.="<th>Tipo</th>";
	    $tabla.="<th>Documento</th>";
	    $tabla.="<th>Débito</th>";
	    $tabla.="<th>Crédito</th>";
	    $tabla.="<th>Saldo</th>";
	    $tabla.="</tr>";
	    foreach ($resultSet as $res)
	    {
	        $i++;
	        $tabla.="<tr>";
	        $tabla.="<td>".$i."</td>";
	        $tabla.="<td>".$res->fecha_estado_cuenta_bancos."</td>";
	        $tabla.="<td>".$res->concepto_estado_cuenta_bancos."</td>";
	        $tabla.="<td>".$res->codigo_tipo_movimientos."</td>";
	        $tabla.="<td>".$res->numero_documento_estado_cuenta_bancos."</td>";
	        $tabla.="<td>".$res->debito_estado_cuenta_bancos."</td>";
	        $tabla.="<td>".$res->credito_estado_cuenta_bancos."</td>";
	        $tabla.="<td>".$res->saldo_estado_cuenta_bancos."</td>";
	        $tabla.="</tr>";
	    }
	    $tabla.="</table>";
	    echo $tabla;
	}
	
	public function mostrar_documentos(){
	    
	    session_start();
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $id_log=$_POST['id_log'];
	    $estado_cuenta = new EstadoCuentasModel();
	    $cantc=0;
	    $cantd=0;
	    $cantMC=0;
	    $cantDP=0;
	    $cantMD=0;
	    $cantCO=0;
	    $cant3O=0;
	    $columnas = "tes_bancos.nombre_bancos";
	    
	    $tablas = "public.tes_bancos  INNER JOIN public.tes_log_estado_cuentas
                    ON tes_bancos.id_bancos = tes_log_estado_cuentas.id_bancos";
	    
	    $where    = "tes_log_estado_cuentas.id_log_estado_cuentas=".$id_log;
	    
	    $id       = "tes_bancos.nombre_bancos";
	    
	    $banco=$estado_cuenta->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $banco=$banco[0]->nombre_bancos;
	    
	    $columnas = "tes_estado_cuenta_bancos.fecha_estado_cuenta_bancos,
	                 tes_tipo_movimientos.codigo_tipo_movimientos,
                	 tes_estado_cuenta_bancos.concepto_estado_cuenta_bancos, 
                	 tes_estado_cuenta_bancos.numero_documento_estado_cuenta_bancos,
                	 tes_estado_cuenta_bancos.debito_estado_cuenta_bancos,
                	 tes_estado_cuenta_bancos.credito_estado_cuenta_bancos,
                	 tes_estado_cuenta_bancos.saldo_estado_cuenta_bancos,
                	 tes_estado_cuenta_bancos.desc_adic_cuenta_bancos,
                	 tes_estado_cuenta_bancos.ciudad_estado_cuenta_bancos,
                     tes_bancos.nombre_bancos";
	    
	    $tablas = "tes_estado_cuenta_bancos INNER JOIN public.tes_bancos
                    ON  tes_estado_cuenta_bancos.id_bancos = tes_bancos.id_bancos
                    INNER JOIN public.tes_log_estado_cuentas
                    ON tes_estado_cuenta_bancos.id_log_estado_cuentas = tes_log_estado_cuentas.id_log_estado_cuentas
                    INNER JOIN tes_tipo_movimientos
                    ON tes_tipo_movimientos.id_tipo_movimientos = tes_estado_cuenta_bancos.id_tipo_movimientos";
	    
	    
	    $where    = "tes_estado_cuenta_bancos.id_log_estado_cuentas=".$id_log;
	 
	    $id       = "tes_log_estado_cuentas.mes_archivo_estado_cuenta,
                     tes_log_estado_cuentas.anio_archivo_estado_cuenta, tes_bancos.id_bancos";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        
	        $html="";
	        
	        $resultSet=$estado_cuenta->getCondiciones($columnas, $tablas, $where, $id);
	        $elem=explode("-",$resultSet[0]->fecha_estado_cuenta_bancos);
	        $periodo=$meses[$elem[1]-1]." del ".$elem[0];
	        
	        
	        
	        if(!(empty($resultSet)))
	        {   
	            
	            if($banco=="PICHINCHA" || $banco=="RUMIÑAHUI") 
	            {
	                foreach ($resultSet as $res)
	                {
	                    if($res->codigo_tipo_movimientos=="D") $cantd++;
	                    if($res->codigo_tipo_movimientos=="C") $cantc++;
	                }
	                $html=$this->MostrarPR($resultSet, $banco, $periodo, $cantc, $cantd);
	            }
	            else
	            {
	                foreach ($resultSet as $res)
	                {
	                    if($res->codigo_tipo_movimientos=="MC") $cantMC++;
	                    if($res->codigo_tipo_movimientos=="DP") $cantDP++;
	                    if($res->codigo_tipo_movimientos=="MD") $cantMD++;
	                    if($res->codigo_tipo_movimientos=="CO") $cantCO++;
	                    if($res->codigo_tipo_movimientos=="3O") $cant3O++;
	                }
	                $html=$this->MostrarINTL($resultSet, $banco, $periodo, $cantMC, $cantDP, $cantMD,$cantCO, $cant3O);
	            }
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
}
?>