<?php

class FormularioReclamosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
	    
	    $this->view_Administracion("FormularioReclamos",array());
	    
	}
	
	public function AgregaReclamos(){
	   
	    
	    $respuesta = null;
	    $reclamos=new ReclamosModel();
	    
	    
	    
	    $error = "";	    
	    try {
	        
	        $_id_form_reclamos =  $_POST["id_form_reclamos"];
	        $_nombres_form_reclamos = $_POST["nombres_form_reclamos"];
	        $_apellidos_form_reclamos = $_POST["apellidos_form_reclamos"];
	        $_edad_form_reclamos = $_POST["edad_form_reclamos"];
	        $_teleono_form_reclamos = $_POST["teleono_form_reclamos"];
	        $_celular_form_reclamos = $_POST["celular_form_reclamos"];
	        $_nacionali_form_reclamos = $_POST["nacionali_form_reclamos"];
	        $_email_form_reclamos = $_POST["email_form_reclamos"];
	        $_direccion_form_reclamos = $_POST["direccion_form_reclamos"];
	        $_detalle_form_reclamos = $_POST["detalle_form_reclamos"];
	        
	        
	        $fecha_hoy = date('Y-m-d');
	        
	        
	        $error = error_get_last();
	        
	        if(!empty($error)){
	            throw new Exception(" Variables no definidas ". $error['message'] );
	        }
	        
	        
	        if($_id_form_reclamos > 0){
                
                
               
                    
                    
                    $columnas = " nombres_form_reclamos = '$_nombres_form_reclamos',
                                 apellidos_form_reclamos = '$_apellidos_form_reclamos',
                                 edad_form_reclamos = '$_edad_form_reclamos',
                                 teleono_form_reclamos = '$_teleono_form_reclamos',
                                 celular_form_reclamos = '$_celular_form_reclamos',
                                 nacionali_form_reclamos = '$_nacionali_form_reclamos',
					             email_form_reclamos = '$_email_form_reclamos'
                                 direccion_form_reclamos = '$_direccion_form_reclamos',
                                 detalle_form_reclamos = '$_detalle_form_reclamos'";
                    
                
                
               	$tabla = "formulario_reclamos";
					$where = "id_form_reclamos = '$_id_form_reclamos'";
					$resultado=$reclamos->UpdateBy($columnas, $tabla, $where);
					
                if( (int)$resultado < 0 )
                    throw new Exception("Error Actualizar Datos");
                    
                $respuesta['respuesta'] = 1;
                $respuesta['mensaje'] = "Datos Reclamos Actualizado";
                
            }else{
                
                $funcion = "ins_formulario_reclamos";
                $parametros = " '$_id_form_reclamos','$_nombres_form_reclamos','$_apellidos_form_reclamos','$_edad_form_reclamos','$_teleono_form_reclamos','$_celular_form_reclamos','$_nacionali_form_reclamos','$_email_form_reclamos','$_direccion_form_reclamos','$_detalle_form_reclamos','$fecha_hoy'";
                $reclamos->setFuncion($funcion);
                $reclamos->setParametros($parametros);
                $resultado = $reclamos->llamafuncionPG();
                $_error_pg = pg_last_error();
                if( is_null($resultado) || !empty($_error_pg) )
                    throw new Exception("Error Insertar Datos");
                    
                $respuesta['respuesta'] = 1;
                $respuesta['identificador'] = $resultado[0];
                $respuesta['mensaje'] = "Proveedor Insertado";
            }
	        
            echo json_encode($respuesta);
            
           
	    }catch (Exception $ex){
	        
	        echo '<message> Error Reclamos \n'. $ex->getMessage().'<message>';
	        
	    }	    
	    
	}
	

	
	public function ReporteReclamos(){
	    
	    
	    $entidades = new EntidadesModel();
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
	    
	    
	    
	    
	    
	    
	    $reclamos=new ReclamosModel();
	    $id_form_reclamos =  (isset($_REQUEST['id_form_reclamos'])&& $_REQUEST['id_form_reclamos'] !=NULL)?$_REQUEST['id_form_reclamos']:'';
	    
	    $datos_reporte = array();
	    
	    $columnas = " formulario_reclamos.id_form_reclamos,
                      formulario_reclamos.nombres_form_reclamos,
                      formulario_reclamos.apellidos_form_reclamos,
                      formulario_reclamos.edad_form_reclamos,
                      formulario_reclamos.teleono_form_reclamos,
                      formulario_reclamos.celular_form_reclamos,
                      formulario_reclamos.nacionali_form_reclamos,
                      formulario_reclamos.email_form_reclamos,
                      formulario_reclamos.direccion_form_reclamos,
                      formulario_reclamos.detalle_form_reclamos,
                      formulario_reclamos.fecha_form_reclamos";
	    
	    $tablas = "  public.formulario_reclamos ";
	    $where= "    formulario_reclamos.id_form_reclamos='$id_form_reclamos'";
	    $id="formulario_reclamos.nombres_form_reclamos";
	    
	    $rsdatos = $reclamos->getCondiciones($columnas, $tablas, $where, $id);
	    
	    
	  
	    $datos_reporte['NOMBRES_RECLAMOS']=$rsdatos[0]->nombres_form_reclamos;
	    $datos_reporte['APELLIDOS_RECLAMOS']=$rsdatos[0]->apellidos_form_reclamos;
	    $datos_reporte['EDAD_RECLAMOS']=$rsdatos[0]->edad_form_reclamos;
	    $datos_reporte['TELEFONO_RECLAMOS']=$rsdatos[0]->teleono_form_reclamos;
	    $datos_reporte['CELULAR_RECLAMOS']=$rsdatos[0]->celular_form_reclamos;
	    $datos_reporte['NACIONALIDAD_RECLAMOS']=$rsdatos[0]->nacionali_form_reclamos;
	    $datos_reporte['EMAIL_RECLAMOS']=$rsdatos[0]->email_form_reclamos;
	    $datos_reporte['DIRECCION_RECLAMOS']=$rsdatos[0]->direccion_form_reclamos;
	    $datos_reporte['DETALLE_RECLAMOS']=$rsdatos[0]->detalle_form_reclamos;
	    $datos_reporte['FECHA_RECLAMOS']=$rsdatos[0]->fecha_form_reclamos;
	    
	    
	    
	    
	    $this->verReporte("ReporteReclamos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte ));
	    
	    
	    
	}
	
	


	public function ListaProveedores(){	    
	   
	    $busqueda = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : "";
	    
	    if(!isset($_POST['peticion'])){
	        echo 'sin conexion';
	        return;
	    }
	    
	    $page = ( isset( $_REQUEST['page'] ) ) ? $_REQUEST['page'] : 1;
	    
	    $Proveedores = new ProveedoresModel();
	    
	    $columnas = "id_proveedores, nombre_proveedores, identificacion_proveedores, contactos_proveedores, direccion_proveedores,
                    telefono_proveedores, email_proveedores, archivo_registro";
	    
	    $tablas = "public.proveedores";
	    
	    $where = " 1=1 ";
	    
	    //para los parametros de where
	    if(!empty($busqueda)){
	        
	        $where .= "AND ( nombre_proveedores LIKE '$busqueda%' OR identificacion_proveedores LIKE '$busqueda%' )";
	    }
	    
	    $id = "nombre_proveedores";
	    
	    //para obtener cantidad
	    $rsResultado = $Proveedores->getCantidad("1", $tablas, $where, $id);
	    
	    $cantidad = 0;
	    $html = "";
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    if(!is_null($rsResultado) && !empty($rsResultado) && count($rsResultado)>0){
	        $cantidad = $rsResultado[0]->total;
	    }
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet = $Proveedores->getCondicionesPag( $columnas, $tablas, $where, $id, $limit);
	    
	    $tpages = ceil($cantidad/$per_page);
	    
	    if( $cantidad > 0 ){
	        
	        //$html.='<div class="pull-left" style="margin-left:11px;">';
	        //$html.='<span class="form-control"><strong>Registros: </strong>'.$cantidad.'</span>';
	        //$html.='<input type="hidden" value="'.$cantidad.'" id="total_query" name="total_query"/>' ;
	        //$html.='</div>';
	        //$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        //$html.='<section style="height:200px; overflow-y:scroll;">';
	        $html.= "<table id='tbl_tabla_proveedores' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">IDENTIFICACION</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">NOMBRE</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CONTACTO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">DIRECCION</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">TELEFONO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CORREO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">ARCHIVO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $i=0;
	        
	        foreach ($resultSet as $res){
	            
	            $i++;
	            $html.='<tr>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->identificacion_proveedores.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_proveedores.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->contactos_proveedores.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->direccion_proveedores.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->telefono_proveedores.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->email_proveedores.'</td>';
	            
	            if(!empty($res->archivo_registro)){
	                $html.='<td><a title="Archivo" target="_blank" href="view/DevuelvePDFView.php?id_valor='.$res->id_proveedores.'&id_nombre=id_proveedores&tabla=proveedores&campo=archivo_registro"><img src="view/images/logo_pdf.png" width="30" height="30"></a></td>';
	            }
	            else {
	                
	                $html.='<td></td>';
	            }
	            
	            $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right">';
	            $html.='<a title="Editar Proveedores" onclick="editarProveedores('.$res->id_proveedores.')" href="#" class="btn-sm btn-warning" style="font-size:65%;" data-toggle="tooltip" >';
	            $html.='<i class="fa  fa-edit" aria-hidden="true" ></i></a></td>';
	            $html.='</tr>';
	            
	        }
	        
	        
	        $html.='</tbody>';
	        $html.='</table>';
	        //$html.='</section></div>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate("index.php", $page, $tpages, $adjacents,"ListaProveedores").'';
	        $html.='</div>';
	        
	        
	        
	    }else{
	        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	        $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	        $html.='<h4>Aviso!!!</h4> <b> No hay cuentas por Pagar</b>';
	        $html.='</div>';
	        $html.='</div>';
	    }
	    
	    //array de datos
	    $respuesta = array();
	    $respuesta['tabladatos'] = $html;
	    $respuesta['valores'] = array('cantidad'=>$cantidad);
	    echo json_encode($respuesta);
	    
	}
	

}
?>