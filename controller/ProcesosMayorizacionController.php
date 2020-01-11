<?php

class ProcesosMayorizacionController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
	
     	$estados=new EstadoModel();     	
					
     	$resultEdit = "";
	
		session_start();
        
	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "ProcesosMayorizacion";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $estados->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
			    $colModulos = " modulos.id_modulos, modulos.nombre_modulos";
			    $tabModulos = " public.modulos
        			    INNER JOIN public.core_tipo_procesos
        			    ON core_tipo_procesos.id_modulos = modulos.id_modulos ";
			    $wheModulos = " 1 = 1 AND diarios_tipo_procesos = 't' ";
			    $gruModulos = " modulos.id_modulos, modulos.nombre_modulos ";
			    $idModulos = " modulos.nombre_modulos ";
			    
			    
			    $rsModulos = $estados->getCondiciones_grupo($colModulos,$tabModulos,$wheModulos,$gruModulos,$idModulos);
			    
				
				
				$this->view_Contable("ProcesosMayorizacion",array(
				    "resultEdit" =>$resultEdit,"rsModulos"=>$rsModulos
			
				));
		
				
				
			}
			else
			{
			    $this->view_Contable("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Grupos"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	//para consultas con js
	//dc 2019-07-30
	public function consultaTipoProcesos(){
	    
	    $modulos = new ModulosModel();
	    
	    $_id_modulos = (isset($_POST['id_modulos'])) ? $_POST['id_modulos'] : 0;
	    
	    $columnas = "id_tipo_procesos, nombre_tipo_procesos";
	    $tablas = "core_tipo_procesos";
	    $where = " diarios_tipo_procesos = 't' AND id_modulos = $_id_modulos ";
	    $id = "id_modulos";
	    
	    $rsTipoProceso = $modulos->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $cantidad = count($rsTipoProceso);
	    
	    echo json_encode(array('cantidad'=>$cantidad, 'data'=>$rsTipoProceso));
	}
	
	
	/***
	 * dc 2019-07-29
	 * mod: Contabilidad
	 * desc: lista los diario tipo detalle
	 */
	public function detallesDiarioTipo(){
	    
	    session_start();
	    
	    $id_usuarios = $_SESSION['id_usuarios'];
	    $usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $Participes = new ParticipesModel();
	    
	    $idTipoProcesos = (isset($_POST['id_tipo_procesos'])) ? $_POST['id_tipo_procesos'] : "";
	    $anioDiario = (isset($_POST['anio_procesos'])) ? $_POST['anio_procesos'] : "";
	    $mesDiario = (isset($_POST['mes_procesos'])) ? $_POST['mes_procesos'] : "";
	    $tipoPeticion = (isset($_POST['peticion'])) ? $_POST['peticion'] : "";	
	    $idModulos = (isset($_POST['id_modulos'])) ? $_POST['id_modulos'] : null;
	    
	    //$mes_letras = date('n')
	   
	    if(empty($idTipoProcesos)){
	        echo '<message>Datos no recibidos<message>';
	        return;
	    }
	    //validar los campos recibidos para generar diario 
	    $colHistorial = "1 existe, id_ccomprobantes";
	    $tabHistorial = "public.core_historial_diarios_tipo";
	    $wheHistorial = "id_tipo_procesos = $idTipoProcesos
                AND id_estatus = 1 
                AND anio_historial_diarios_tipo = $anioDiario
                AND mes_historial_diarios_tipo = $mesDiario";
	    $idHistorial = "id_historial_diarios_tipo"; 
	    $rsHistorial = $Participes->getCondiciones($colHistorial, $tabHistorial, $wheHistorial, $idHistorial);
	    	    
	    if(empty($rsHistorial)){
	        	        
	        $arrayTabla = array();
	        $cantidad = 0;
	        $conceptoDiario = "Proceso Mensual";
	        
	        //se genera el array a insertar
	        switch ($idTipoProcesos){
	            case "1":
	                
	                break;
	            case "8":
	                $arrayTabla = $this->generaDiarioProvisionesMensuales($idTipoProcesos,$anioDiario,$mesDiario);
	                $cantidad = sizeof($arrayTabla);
	                $conceptoDiario.=" Provisiones";
	                break;
	            case "9":
	                $arrayTabla = $this->generaDiarioActivos($idTipoProcesos,$anioDiario,$mesDiario);
	                $cantidad = sizeof($arrayTabla);
	                $conceptoDiario.=" Activos Fijos";
	                break;
	           /* case "10":
	                $arrayTabla = $this->generaDiarioActivos($idTipoProcesos,$anioDiario,$mesDiario);
	                $cantidad = sizeof($arrayTabla);
	                $conceptoDiario.=" Activos Fijos";
	                break;*/
	            case "13":
	                $arrayTabla = $this->generaDiarioMaterialesSuministros($idTipoProcesos,$anioDiario,$mesDiario);
	                $cantidad = sizeof($arrayTabla);
	                $conceptoDiario.=" Inventario y Materiales";
	                break;
	            default:
	                break;
	        }
	        
	        //validar tipo de accion simular o generar
	        if($tipoPeticion == 'simulacion'){
	            
	            //array de datos
	            $respuesta = array();
	            $respuesta['tabladatos'] = $this->graficaDiario($arrayTabla);
	            $respuesta['valores'] = array('cantidad'=>$cantidad);
	            echo json_encode($respuesta);
	            die();	    
	            
	        }else if( $tipoPeticion == 'generar' ){
	            //obtener datos de array
	            	           	            
	            $sumaDebe = 0;
	            $sumaHaber = 0;
	            $valorLetras = "";
	            $fechaDiario = date("Y-m-d");
	            
	            foreach ($arrayTabla as $res){
	                $sumaDebe = $sumaDebe + $res['valor_debe'];
	                $sumaHaber = $sumaHaber + $res['valor_debe'];
	            }
	            
	            if( $sumaDebe != $sumaHaber ){
	                echo '<message> Valor comprobante no coincide <message>'; die();	                
	            }
	            
	            try {
	                
	                $valorLetras = $Participes->numtoletras($sumaDebe);
	                $observacionDiario = "";
	                
	                //relizar un begin
	                $Participes->beginTran();
	                
	                //aqui genera el comprobante
	                $funcion = "ins_ccomprobantes_procesos";
	                $parametros = "'$sumaDebe', '$conceptoDiario', '$id_usuarios', '$valorLetras', '$fechaDiario', '$observacionDiario'";
	                $consultaPG = "SELECT ". $funcion." ( ".$parametros." )";
	                //echo $consultaPG;
	                //generar primero el comprobante
	                // insert individual .
	                $ResultComprobante = $Participes->llamarconsultaPG($consultaPG);
	                
	                $id_comprobante = (int)$ResultComprobante[0];
	                
	                //para ingresar el detalle. se realiza con un ciclo
	                $_id_plan_cuentas = 0;
	                $_debe_comprobante = 0;
	                $_haber_comprobante = 0;
	                $funcionDet ="ins_dcomprobantes_procesos";
	               
	                foreach ($arrayTabla as $res){
	                    $_id_plan_cuentas = $res['id_plan_cuentas'];
	                    $_debe_comprobante = $res['valor_debe'];
	                    $_haber_comprobante = $res['valor_haber'];
	                    $paramDet = "'$id_comprobante','$_id_plan_cuentas','$conceptoDiario','$_debe_comprobante','$_haber_comprobante'";	
	                    //query diario
	                    $queryDetalle = "SELECT ".$funcionDet." ( ".$paramDet." )";
	                    //insert diario 
	                    $error = "";
	                    $ResultDet = $Participes->llamarconsultaPG($queryDetalle);
	                    $error = pg_last_error();
	                    if( !empty($error) || (int)$ResultDet[0] <= 0){
	                        $Participes->endTran();
	                        throw new Exception("Error ingresando detalle");
	                    }
	                }
	                
	                
	                //aqui para la mayorizacion	                
	                $funcionMayoriza = "con_ins_mayoriza";
	                $parametrosMayoriza = "'$id_comprobante','$fechaDiario'";
	                $consultaPG = $Participes->getconsultaPG($funcionMayoriza, $parametrosMayoriza);
	                $ResultMayoriza = $Participes->llamarconsultaPG($consultaPG);
	                $error = "";	                
	                $error = pg_last_error();
	                if( !empty($error) || (int)$ResultMayoriza[0] <= 0){
	                    $Participes->endTran();
	                    throw new Exception("Error Mayorizacion");
	                }
	                	                
	                //para el cuadre plan_cuentas
	                $funcionMayor = "fn_cuadra_plan_cuentas";
	                foreach ($arrayTabla as $res){
	                    $_id_plan_cuentas = $res['id_plan_cuentas'];	                   
	                    $paramMayorizar = "'$_id_plan_cuentas'";
	                    //query diario
	                    $queryDetalle = "SELECT ".$funcionMayor." ( ".$paramMayorizar." )";
	                    //realizar mayorizacion
	                    $error = "";
	                    $ResultMayorizar = $Participes->llamarconsultaPG($queryDetalle);
	                    $error = pg_last_error();
	                    if( !empty($error) || (int)$ResultMayorizar[0] <= 0){
	                        $Participes->endTran();
	                        throw new Exception("Error Mayorizando comprobante");
	                    }
	                }	                
	               
	                //inserta en historial
	                $funcionHist = "ins_core_historial_diario";
	                $paramHist = "'$idTipoProcesos','$idModulos','$id_comprobante','$usuario_usuarios','$anioDiario','$mesDiario'";
	                $queryHist = "SELECT ".$funcionHist." ( ".$paramHist." )";
	                $ResultHistorial = $Participes->llamarconsultaPG($queryHist);
	                
	                $Participes->endTran("COMMIT");
	                
	                echo json_encode( array('valor'=>1,'mensaje'=>"Proceso generado Correctamente"));
	                
	            } catch (Exception $ex) {
	                
	               
	                echo '<message> Error Procesos '.$ex->getMessage().' <message>';
	                
	            }	            
	            
	            die();
	        }else{
	            
	            echo 'peticion no solicitada';
	            die();
	        }
	        
	            
	    }else{
	        
	        $_id_comprobante_consulta = $rsHistorial[0]->id_ccomprobantes;          
	        
	        echo json_encode(array('valor'=>2,'id_ccomprobantes'=>$_id_comprobante_consulta)); die();
	    }
	    
	}
	
	/***
	 * dc 2019-08-06 
	 * void grafica tabla
	 */
	function graficaDiarioExistente(){
	   
	    $Participes = new ParticipesModel();
	    
	    $_id_ccomprobantes = (isset($_POST['id_ccomprobantes'])) ? $_POST['id_ccomprobantes'] : 0;
	    
	    //consulta comprobante de historial	    
	    $colComprobante = "dcomprobantes.id_ccomprobantes,
            	        dcomprobantes.debe_dcomprobantes,
            	        dcomprobantes.haber_dcomprobantes,
            	        plan_cuentas.id_plan_cuentas,
            	        plan_cuentas.codigo_plan_cuentas,
            	        plan_cuentas.nombre_plan_cuentas";
	    $tabComprobante = " public.dcomprobantes
            	        INNER JOIN public.plan_cuentas
            	        ON dcomprobantes.id_plan_cuentas = plan_cuentas.id_plan_cuentas";
	    $wheComprobante = "dcomprobantes.id_ccomprobantes = $_id_ccomprobantes";
	    
	    $idComprobante = "dcomprobantes.id_dcomprobantes";
	    
	    $rsComprobante = $Participes->getCondiciones($colComprobante, $tabComprobante, $wheComprobante, $idComprobante);
	    
	    //genera array para enviar datos y se grafique	    
	   
	    $arrayRespuesta = array();
	    $fila = array();
	    foreach ($rsComprobante as $res){
	        
	        $fila=array('id_diario_tipo_detalle'=>0,
	            'id_plan_cuentas'=>$res->id_plan_cuentas,
	            'codigo'=>$res->codigo_plan_cuentas,
	            'nombre'=>$res->nombre_plan_cuentas,
	            'valor_debe'=>$res->debe_dcomprobantes,
	            'valor_haber'=>$res->haber_dcomprobantes
	        );
	        array_push( $arrayRespuesta, $fila);
	    }
	    $cantidad = sizeof($arrayRespuesta);
	    //array de datos
	    $respuesta = array();
	    $respuesta['tabladatos'] = $this->graficaDiario($arrayRespuesta);
	    $respuesta['valores'] = array('cantidad'=>$cantidad);
	    echo json_encode($respuesta);
	    die();
	    
	}
	
	/***
	 * dc 2019-08-02
	 * @param array $paramArrayDatos
	 * @return string
	 */
	function graficaDiario( $paramArrayDatos){
	    
	    $cantidad = sizeof($paramArrayDatos);
	    $html = "";
	    if( $cantidad > 0 ){
	        
	        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CODIGO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">NOMBRE</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">DEBITO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CREDITO</th>';
	        //$html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $i=0;
	        
	        foreach ($paramArrayDatos as $res){
	            
	            $i++;
	            $html.='<tr>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res['codigo'].'</td>';
	            $html.='<td style="font-size: 11px;">'.$res['nombre'].'</td>';
	            $html.='<td style="font-size: 11px;">'.$res['valor_debe'].'</td>';
	            $html.='<td style="font-size: 11px;">'.$res['valor_haber'].'</td>';	            
	            $html.='</tr>';
	            
	        }
	        
	        
	        $html.='</tbody>';
	        $html.='</table>';
	        
	        $html.='<div class="table-pagination pull-right">';
	        $html.='</div>';
	        
	        
	        
	    }else{
	        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CODIGO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">NOMBRE</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">DEBITO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CREDITO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='</table>';
	    }
	    
	    return $html;
	    
	}
	
	/***
	 * dc 2019-07-09
	 * mod: Contabilidad
	 * desc: lista los diario tipo detalle
	 */
	function generaDiarioActivos($idTipoProceso=9,$paramAnio=2019,$paramMes=7){
	  	    
	    $Activos = new ActivosFijosModel();
	    $arrayDiarioTipo = array();
	    $arrayRespuesta = array();
	    
	    $arrayColDepreciacion = array("enero_depreciacion","febrero_depreciacion","marzo_depreciacion","abril_depreciacion","mayo_depreciacion",
	        "junio_depreciacion","julio_depreciacion","agosto_depreciacion","septiembre_depreciacion","octubre_depreciacion","noviembre_depreciacion",
	        "diciembre_depreciacion");	
	    //obtener columna de busqueda
	    for($i=0; $i<sizeof($arrayColDepreciacion);$i++){
	        if(($i+1) == $paramMes){
	            $columnaMes = $arrayColDepreciacion[$i];
	        }
	    }
	    
	    
	    //traer activos para depreciacion 
	    $columnas ="aaf.id_tipo_activos_fijos,taf.nombre_tipo_activos_fijos,sum(ad.$columnaMes) \"valor_depreciacion\"";
	    $tablas ="act_activos_fijos aaf
                inner join tipo_activos_fijos taf
                on taf.id_tipo_activos_fijos = aaf.id_tipo_activos_fijos
                inner join act_depreciacion ad
                on aaf.id_activos_fijos = ad.id_activos_fijos";
	    $where = " 1=1
	           AND ad.anio_depreciacion = $paramAnio";
	    $grupo = "aaf.id_tipo_activos_fijos,taf.nombre_tipo_activos_fijos";
        $id="aaf.id_tipo_activos_fijos";
        
        $rsActivos = $Activos->getCondiciones_grupo($columnas, $tablas, $where, $grupo, $id);
        
               
        //paradatos de diario tipo 
        $colDiarioTipo = "cdtd.id_diario_tipo_detalle,
                    pc.id_plan_cuentas,pc.codigo_plan_cuentas,
                    pc.nombre_plan_cuentas,
                    destino_diario_tipo_detalle,
                    0.00 \"debito\", 0.00 \"credito\"";
        $tabDiarioTipo = "core_diario_tipo_detalle cdtd
        	    INNER JOIN core_diario_tipo_cabeza cdtc
        	    ON cdtc.id_diario_tipo_cabeza = cdtd.id_diario_tipo_cabeza
        	    INNER JOIN plan_cuentas pc
        	    ON pc.id_plan_cuentas = cdtd.id_plan_cuentas";
        $wheDiarioTipo = "cdtc.id_tipo_procesos = '$idTipoProceso'";
        $idDiarioTipo = "cdtd.id_diario_tipo_detalle";        
        $rsDiarioTipo = $Activos->getCondiciones($colDiarioTipo, $tabDiarioTipo, $wheDiarioTipo, $idDiarioTipo);
        
        //consulta tipo de activos
        $queryTipoActivo = "SELECT * FROM tipo_activos_fijos ";
        $rsTipoActivos = $Activos->enviaquery($queryTipoActivo);
       
        foreach ($rsDiarioTipo as $resDiario){
            $_id_plan_cuenta_diario = $resDiario->id_plan_cuentas;
            $_id_tipo_activo = 0;
            $_sumatoriaDepreciacion = 0;
            $_valor_debe = 0;
            $_valor_haber = 0;      
              
            if( $resDiario->destino_diario_tipo_detalle == 'DEBE'){                
                foreach ($rsTipoActivos as $resTipoActivo){
                    if($resTipoActivo->debe_id_plan_cuentas == $_id_plan_cuenta_diario ){
                        $_id_tipo_activo = $resTipoActivo->id_tipo_activos_fijos;                        
                        break;
                    }                    
                }
                foreach ($rsActivos as $resActivos){
                    if($resActivos->id_tipo_activos_fijos == $_id_tipo_activo ){
                        $_sumatoriaDepreciacion = $resActivos->valor_depreciacion;
                        break;
                    }
                }
                $_valor_debe = $_sumatoriaDepreciacion;
            }
            if( $resDiario->destino_diario_tipo_detalle == 'HABER'){
                foreach ($rsTipoActivos as $resTipoActivo){
                    if($resTipoActivo->haber_id_plan_cuentas == $_id_plan_cuenta_diario ){
                        $_id_tipo_activo = $resTipoActivo->id_tipo_activos_fijos;
                        break;
                    }
                    
                }
                foreach ($rsActivos as $resActivos){
                    if($resActivos->id_tipo_activos_fijos == $_id_tipo_activo ){
                        $_sumatoriaDepreciacion = $resActivos->valor_depreciacion;
                        break;
                    }
                }
                $_valor_haber = $_sumatoriaDepreciacion;                
            }
                         
            $arrayFila = array('id_diario_detalle'=>$resDiario->id_diario_tipo_detalle,'valor_debe'=>$_valor_debe,'valor_haber'=>$_valor_haber);
            array_push( $arrayDiarioTipo, $arrayFila);
            
        }
        
        //para generar el array de respuesta 
        $fila = array();
        foreach ($rsDiarioTipo as $resDiario){
            foreach ($arrayDiarioTipo as $res){
                if( $resDiario->id_diario_tipo_detalle == $res['id_diario_detalle']){
                    $fila=array('id_diario_tipo_detalle'=>$resDiario->id_diario_tipo_detalle,'id_plan_cuentas'=>$resDiario->id_plan_cuentas,
                        'codigo'=>$resDiario->codigo_plan_cuentas,'nombre'=>$resDiario->nombre_plan_cuentas,'valor_debe'=>$res['valor_debe'],
                        'valor_haber'=>$res['valor_haber']
                    );
                    array_push( $arrayRespuesta, $fila);
                    break;
                }
            }
           
          
        }
        
        return $arrayRespuesta;
        
	}
	
	function generaDiarioProvisionesMensuales($idTipoProceso,$paramAnio,$paramMes){
	    
	    $mes = $paramMes;
	    $year = $paramAnio;
	    $mes--;
	    if ($mes==0){
	         $mes=12;  $year--;
	    }
	    $diainicio = 22;
	    $diafinal = 21;
	    $fechai = $diainicio."/".$mes."/".$year;
	    $mes++;
	    if ($mes>12){
	        $mes=1;
	        $year++;
	        $fechaf = $diafinal."/".$mes."/".$year;
	    }else{
	        $fechaf = $diafinal."/".$mes."/".$year;
	    }
	    
	    $periodo=$fechai."-".$fechaf;
	    //codigo aneterior genera fecha para buscar el periodo
	    
	    $Participes = new ParticipesModel();
	    
	    //paradatos de diario tipo
	    $colDiarioTipo = "cdtd.id_diario_tipo_detalle,
                    pc.id_plan_cuentas,pc.codigo_plan_cuentas,
                    pc.nombre_plan_cuentas,
                    destino_diario_tipo_detalle,
                    0.00 \"debito\", 0.00 \"credito\"";
	    $tabDiarioTipo = "core_diario_tipo_detalle cdtd
        	    INNER JOIN core_diario_tipo_cabeza cdtc
        	    ON cdtc.id_diario_tipo_cabeza = cdtd.id_diario_tipo_cabeza
        	    INNER JOIN plan_cuentas pc
        	    ON pc.id_plan_cuentas = cdtd.id_plan_cuentas";
	    $wheDiarioTipo = "cdtc.id_tipo_procesos = '$idTipoProceso'";
	    $idDiarioTipo = "cdtd.id_diario_tipo_detalle";
	    $rsDiarioTipo = $Participes->getCondiciones($colDiarioTipo, $tabDiarioTipo, $wheDiarioTipo, $idDiarioTipo);
	    
	    //buscar provisiones mensuales	    
	    $queryProvisiones = "SELECT id_provisiones_nomina, fondos_reserva, dec_tercero_sueldo, dec_cuarto_sueldo,aporte_iess_2,periodo 
                            FROM provisiones_nomina_empleados WHERE periodo = '$periodo'";	    
	    $rsProvisiones = $Participes->enviaquery($queryProvisiones);
	    	    
	    $_sumAporteIESS = 0;
	    $_sumaDecimo13 = 0;
	    $_sumaDecimo14 = 0;
	    $_sumaFondo = 0;
	    
	    foreach ($rsProvisiones as $res){
	        $_sumAporteIESS = $_sumAporteIESS + $res->aporte_iess_2;
	        $_sumaDecimo13 = $_sumaDecimo13 + $res->dec_tercero_sueldo;
	        $_sumaDecimo14 = $_sumaDecimo14 + $res->dec_cuarto_sueldo;
	        $_sumaFondo = $_sumaFondo + $res->fondos_reserva;
	    }	    
	   
	    $arrayRespuesta = array();
	    $arrayDiarioTipo = array();
	    $_valor_debe = 0;
	    $_valor_haber = 0;
	    $fila = array();
	    foreach ($rsDiarioTipo as $resDiario){
	        $_codigo_plan_cuentas = $resDiario->codigo_plan_cuentas;
	        $_codigo_plan_cuentas = trim($_codigo_plan_cuentas,'.');
	        $_valor_debe = 0;
	        $_valor_haber = 0;
	        //echo $_codigo_plan_cuentas.'<br>';
	        switch ($_codigo_plan_cuentas){
	            case "4.3.01.20" :
	                $_valor_debe = $_sumAporteIESS;
	            break;
	            case "4.3.01.15.02":
	                $_valor_debe = $_sumaDecimo14;
	            break;
	            case "4.3.01.15.01" :
	                $_valor_debe = $_sumaDecimo13;
	                break;
	            case "4.3.01.25":
	                $_valor_debe = $_sumaFondo;
	                break;
	            case "2.5.03.06" :
	                $_valor_haber = $_sumAporteIESS;
	                break;
	            case "2.5.02.02":
	                $_valor_haber = $_sumaDecimo14;
	                break;
	            case "2.5.02.01" :
	                $_valor_haber = $_sumaDecimo13;
	                break;
	            case "2.5.04.01":
	                $_valor_haber = $_sumaFondo;
	                break;
	            default:
	                $_valor_debe = 0;
	                $_valor_haber = 0;
	                break;
	        }
	       
	        $arrayFila = array('id_diario_detalle'=>$resDiario->id_diario_tipo_detalle,'valor_debe'=>$_valor_debe,'valor_haber'=>$_valor_haber);
	        array_push( $arrayDiarioTipo, $arrayFila);
	        
	    }
	    //die();
	    //para generar el array de respuesta
	    $fila = array();
	    foreach ($rsDiarioTipo as $resDiario){
	        foreach ($arrayDiarioTipo as $res){
	            if( $resDiario->id_diario_tipo_detalle == $res['id_diario_detalle']){
	                $fila=array('id_diario_tipo_detalle'=>$resDiario->id_diario_tipo_detalle,'id_plan_cuentas'=>$resDiario->id_plan_cuentas,
	                    'codigo'=>$resDiario->codigo_plan_cuentas,'nombre'=>$resDiario->nombre_plan_cuentas,'valor_debe'=>$res['valor_debe'],
	                    'valor_haber'=>$res['valor_haber']
	                );
	                array_push( $arrayRespuesta, $fila);
	                break;
	            }
	        }
	        
	        
	    }
	    
	    return $arrayRespuesta;
	    
	    
	}
	
	public function generaDiarioMaterialesSuministros($idTipoProceso,$año,$mes){
	    
	    $productos = new ProductosModel();
	    
	    $arrayDiarioTipo = array();
	    $arrayRespuesta = array();
	    
	    // OBTENER DATOS DEL DIARIO TIPO
	    $columnas1 = " cdtd.id_diario_tipo_detalle,
                    pc.id_plan_cuentas,pc.codigo_plan_cuentas,
                    pc.nombre_plan_cuentas,
                    destino_diario_tipo_detalle,
                    0.00 \"debito\", 0.00 \"credito\" ";
	    $tablas1   = " core_diario_tipo_detalle cdtd
        	    INNER JOIN core_diario_tipo_cabeza cdtc
        	    ON cdtc.id_diario_tipo_cabeza = cdtd.id_diario_tipo_cabeza
        	    INNER JOIN plan_cuentas pc
        	    ON pc.id_plan_cuentas = cdtd.id_plan_cuentas";
	    $where1    = " cdtc.id_tipo_procesos = '$idTipoProceso' ";
	    $id1       = " cdtd.id_diario_tipo_detalle";
	    $rsConsulta1   = $productos->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    if(empty($rsConsulta1)){
	        echo "AQUI ERROR";
	        exit();
	    }
	    
	    // OBTENER SUMA DE MATERIALES EN SALIDA
	    $columnas2 = " bb.valor_movimientos_inv_detalle";
	    $tablas2   = " movimientos_inv_cabeza aa
	               INNER JOIN movimientos_inv_detalle bb ON bb.id_movimientos_inv_cabeza = aa.id_movimientos_inv_cabeza
                   INNER JOIN consecutivos cc ON cc.id_consecutivos = aa.id_consecutivos";
	    $where2    = " TO_CHAR(aa.creado,'YYYY') = '$año'
	               AND TO_CHAR(aa.creado,'MM')	= '$mes'
                   AND cc.nombre_consecutivos = 'SALIDA'
	               AND aa.estado_movimientos_inv_cabeza = 'APROBADA'
	               AND upper(aa.razon_movimientos_inv_cabeza) = 'SALIDA PRODUCTOS'";
	    $id2       = " aa.creado";
	    $rsConsulta2   = $productos->getCondiciones($columnas2, $tablas2, $where2, $id2);
	    
	    $_sumatoria_valores = 0.00;	    
	    
	    if(empty($rsConsulta2)){
	        return $arrayRespuesta;
	        exit();
	    }else{
	        foreach ($rsConsulta2 as $res) {
	            $_sumatoria_valores += (float)$res->valor_movimientos_inv_detalle;
	        }
	    }
	    	    
	    foreach ($rsConsulta1 as $resDiario) {
	        
	        $_valores_debe     = 0.00;
	        $_valores_haber    = 0.00;
	        if( $resDiario->destino_diario_tipo_detalle == "DEBE" ){
	            $_valores_debe     = $_sumatoria_valores;
	            $_valores_haber    = 0.00;
	        }else{
	            $_valores_debe     = 0.00;
	            $_valores_haber    = $_sumatoria_valores;
	        }
	        $arrayFila = array('id_diario_detalle'=>$resDiario->id_diario_tipo_detalle,'valor_debe'=>$_valores_debe,'valor_haber'=>$_valores_haber);
	        array_push( $arrayDiarioTipo, $arrayFila);
	    }
	    
	    // PARA GENERAR ARRAY DE RESPUESTA
	    $fila = array();
	    foreach ($rsConsulta1 as $resDiario){
	        foreach ($arrayDiarioTipo as $res){
	            if( $resDiario->id_diario_tipo_detalle == $res['id_diario_detalle']){
	                $fila=array('id_diario_tipo_detalle'=>$resDiario->id_diario_tipo_detalle,'id_plan_cuentas'=>$resDiario->id_plan_cuentas,
	                    'codigo'=>$resDiario->codigo_plan_cuentas,'nombre'=>$resDiario->nombre_plan_cuentas,'valor_debe'=>$res['valor_debe'],
	                    'valor_haber'=>$res['valor_haber']
	                );
	                array_push( $arrayRespuesta, $fila);
	                break;
	            }
	        }
	        
	        
	    }
	    
	    return $arrayRespuesta;

	}
			
	public function paginate($reload, $page, $tpages, $adjacents, $funcion) {
	    
	    
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
			
	/**
	 * @param number $paramIdCredito
	 * devuelve string OK;
	 */
	public function ActivaCredito($paramIdCredito){
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    $Credito = new CreditosModel();
	    $Consecutivos = new ConsecutivosModel();
	    $TipoComprobantes = new TipoComprobantesModel();
	   	    
	    $id_creditos = $paramIdCredito;
	    
	    if(is_null($id_creditos)){
	        echo '<message> parametros no recibidos <message>';
	        return;
	    }
	    
	    try {
	        
	        
	        $Credito->beginTran();
	        
	        //creacion de lote
	        $nombreLote = "CxP-Creditos";
	        $descripcionLote = "GENERACION CREDITO";
	        $id_frecuencia = 1;
	        $id_usuarios = $_SESSION['id_usuarios'];
	        $usuario_usuarios = $_SESSION['usuario_usuarios'];
	        $funcionLote = "tes_genera_lote";
	        $paramLote = "'$nombreLote','$descripcionLote','$id_frecuencia','$id_usuarios'";
	        $consultaLote = $Credito->getconsultaPG($funcionLote, $paramLote);
	        $ResultLote = $Credito->llamarconsultaPG($consultaLote);
	        $_id_lote = 0; // es cero para que la funcion reconosca como un ingreso de nuevo lote
	        $error = "";
	        $error = pg_last_error();
	        if (!empty($error) || (int)$ResultLote[0] <= 0){
	            throw new Exception('error ingresando lote');
	        }
	        
	        $_id_lote = (int)$ResultLote[0];
	        
	        /*insertado de cuentas por pagar*/
	        //busca consecutivo	       
	        $ResultConsecutivo= $Consecutivos->getConsecutivoByNombre("CxP");	        
	        $_id_consecutivos = $ResultConsecutivo[0]->id_consecutivos;
	        
	        //busca tipo documento
	        $queryTipoDoc = "SELECT id_tipo_documento, nombre_tipo_documento FROM tes_tipo_documento
                WHERE abreviacion_tipo_documento = 'MIS' LIMIT 1";
	        $ResultTipoDoc= $Credito->enviaquery($queryTipoDoc);
	        $_id_tipo_documento = $ResultTipoDoc[0]->id_tipo_documento;
	        
	        //busca tipo moneda
	        $queryMoneda = "SELECT id_moneda, nombre_moneda FROM tes_moneda
                WHERE nombre_moneda = 'DOLAR' LIMIT 1";
	        $ResultMoneda= $Credito->enviaquery($queryMoneda);
	        $_id_moneda = $ResultMoneda[0]->id_moneda;
	        
	        //datos de participes
	        $funcionProveedor = "ins_proveedores_participes";
	        $parametrosProveedor = " '$id_creditos' ";
	        $consultaProveedor = $Credito->getconsultaPG($funcionProveedor, $parametrosProveedor);
	        $ResultadoProveedor= $Credito->llamarconsultaPG($consultaProveedor);
	        $error = "";
	        $error = pg_last_error();
	        if (!empty($error) ){
	            throw new Exception('error proveedores');
	        }
	        $_id_proveedor = 0;
	        if( (int)$ResultadoProveedor[0] > 0 ){
	            $_id_proveedor = $ResultadoProveedor[0];
	        }else{
	            throw new Exception("Error en proveedor-participe");
	        }
	        
	        //para datos de banco
	        $_id_bancos = 0 ; //mas adelenate se modifica con la solicitud del participe
	        
	        //datos Cuenta por pagar
	        $_descripcion_cuentas_pagar = ""; //se llena mas adelante
	        $_fecha_cuentas_pagar = date('Y-m-d');
	        $_condiciones_pago_cuentas_pagar = "";
	        $_num_documento_cuentas_pagar = "";
	        $_num_ord_compra = "";
	        $_metodo_envio_cuentas_pagar = "";
	        $_compra_cuentas_pagar = ""; //valor de credito
	        $_desc_comercial = 0.00;
	        $_flete_cuentas_pagar = 0.00;
	        $_miscelaneos_cuentas_pagar = 0.00;
	        $_impuesto_cuentas_pagar = 0.00;
	        $_total_cuentas_pagar = 0.00;
	        $_monto1099_cuentas_pagar = 0.00;
	        $_efectivo_cuentas_pagar = 0.00;
	        $_cheque_cuentas_pagar = 0.00;
	        $_tarjeta_credito_cuentas_pagar = 0.00;
	        $_condonaciones_cuentas_pagar = 0.00;
	        $_saldo_cuentas_pagar = 0.00;
	        $_id_cuentas_pagar = 0;
	        
	        /*valores para cuenta por pagar*/
	        //busca datos de credito
	        $queryCredito = "SELECT cc.id_creditos, cc.monto_otorgado_creditos, cc.monto_neto_entregado_creditos,
                      cc.saldo_actual_creditos, cc.numero_creditos,
		              ctc.id_tipo_creditos, ctc.nombre_tipo_creditos, ctc.codigo_tipo_creditos
                    FROM core_creditos cc
                    INNER JOIN core_tipo_creditos ctc
                    ON cc.id_tipo_creditos = ctc.id_tipo_creditos
                    WHERE cc.id_creditos = $id_creditos ";
	        $ResultCredito= $Credito->enviaquery($queryCredito);
	        
	        $codigo_credito = "";
	        $monto_credito = 0;
	        $monto_entregado_credito = 0;
	        $id_tipo_credito = 0;
	        $numero_credito = !empty($ResultCredito) ? $ResultCredito[0]->numero_creditos : 0 ;
	        
	        $_descripcion_cuentas_pagar = "Cuenta x Pagar Credito $numero_credito ";
	        
	        foreach ($ResultCredito as $res){
	            $codigo_credito=$res->codigo_tipo_creditos;
	            $monto_credito = $res->monto_otorgado_creditos;
	            $monto_entregado_credito = $res->monto_neto_entregado_creditos;
	            $id_tipo_credito = $res->id_tipo_creditos;
	            
	        }
	        
	        //valores de cuentas por pagar
	        $_compra_cuentas_pagar = $monto_credito;
	        $_total_cuentas_pagar = $_compra_cuentas_pagar;
	        $_saldo_cuentas_pagar = $_compra_cuentas_pagar - $_impuesto_cuentas_pagar;
	        
	        /*inserccion de cuentas x pagar*/
	        //generar cuentas contables de cuentas por pagar
	        
	        //DIFERENCIAR MONTO SOLICITADO MONTO ENTREGADO
	        if($monto_credito != $monto_entregado_credito){
	            //para monto en refinaciacion y otras
	        }else{
	            //para insertado normal
	            $queryParametrizacion = "SELECT * FROM core_parametrizacion_cuentas
                                    WHERE id_principal_parametrizacion_cuentas = $id_tipo_credito";
	            $ResultParametrizacion = $Credito -> enviaquery($queryParametrizacion);
	            
	            //buscar de tabla parametrizacion
	            $iorden=0;
	            foreach ($ResultParametrizacion as $res){
	                $iorden = 1;
	                $queryDistribucion = "INSERT INTO tes_distribucion_cuentas_pagar
                        (id_lote,id_plan_cuentas,tipo_distribucion_cuentas_pagar,debito_distribucion_cuentas_pagar,credito_distribucion_cuentas_pagar,ord_distribucion_cuentas_pagar,referencia_distribucion_cuentas_pagar)
                        VALUES ( '$_id_lote','$res->id_plan_cuentas_debe','COMPRA','0.00','$monto_credito','$iorden','$_descripcion_cuentas_pagar')";
	                
	                $iorden = $iorden + 2;
	                $ResultDistribucion = $Credito -> executeNonQuery($queryDistribucion);
	                $error = "";
	                $error ="";
	                $error = pg_last_error();
	                if(!empty($error) || $ResultDistribucion <= 0 )
	                    throw new Exception('error distribucion cuentas pagar debe   '.$error);
	            }
	            
	            foreach ($ResultParametrizacion as $res){
	                $iorden = 2;
	                $queryDistribucion = "INSERT INTO tes_distribucion_cuentas_pagar
                        (id_lote,id_plan_cuentas,tipo_distribucion_cuentas_pagar,debito_distribucion_cuentas_pagar,credito_distribucion_cuentas_pagar,ord_distribucion_cuentas_pagar,referencia_distribucion_cuentas_pagar)
                        VALUES ( '$_id_lote','$res->id_plan_cuentas_haber','PAGOS','$monto_credito','0.00','$iorden','$_descripcion_cuentas_pagar')";
	                $iorden = $iorden + 2;
	                $ResultDistribucion = $Credito -> executeNonQuery($queryDistribucion);
	                $error = "";
	                $error = pg_last_error();
	                if(!empty($error) || $ResultDistribucion <= 0 )
	                    throw new Exception('error distribucion cuentas pagar haber');
	            }
	            
	            
	            
	            
	            switch ($codigo_credito){
	                case "EME":
	                    $_descripcion_cuentas_pagar .= " Tipo EMERGENTE";
	                    
	                    break;
	                case "ORD":
	                    
	                    $_descripcion_cuentas_pagar .= "Tipo ORDINARIO";
	                    break;
	            }
	        }
	        
	        
	        $_origen_cuentas_pagar = "CREDITOS";
	        $_id_usuarios = $id_usuarios;
	        //datos de cuentas x pagar
	        $funcionCuentasPagar = "tes_ins_cuentas_pagar";
	        $paramCuentasPagar = "'$_id_lote', '$_id_consecutivos', '$_id_tipo_documento', '$_id_proveedor', '$_id_bancos',
            '$_id_moneda', '$_descripcion_cuentas_pagar', '$_fecha_cuentas_pagar', '$_condiciones_pago_cuentas_pagar', '$_num_documento_cuentas_pagar',
            '$_num_ord_compra','$_metodo_envio_cuentas_pagar', '$_compra_cuentas_pagar', '$_desc_comercial','$_flete_cuentas_pagar',
            '$_miscelaneos_cuentas_pagar','$_impuesto_cuentas_pagar', '$_total_cuentas_pagar','$_monto1099_cuentas_pagar','$_efectivo_cuentas_pagar',
            '$_cheque_cuentas_pagar', '$_tarjeta_credito_cuentas_pagar', '$_condonaciones_cuentas_pagar', '$_saldo_cuentas_pagar', '$_origen_cuentas_pagar', '$_id_cuentas_pagar'";
	        
	        
	        $consultaCuentasPagar = $Credito->getconsultaPG($funcionCuentasPagar, $paramCuentasPagar);
	        $ResultCuentaPagar = $Credito -> llamarconsultaPG($consultaCuentasPagar);
	        
	        $error = "";
	        $error = pg_last_error();
	        if(!empty($error) || $ResultCuentaPagar[0] <= 0 ){ throw new Exception('error inserccion cuentas pagar');}
	            
            // secuencial de cuenta por pagar
            $_id_cuentas_pagar = $ResultCuentaPagar[0];
	        
	        //para actualizar la forma de pago y el banco en cuentas por pagar
	        //--buscar
	        $columnas1 = "aa.id_creditos, bb.id_forma_pago, bb.nombre_forma_pago,cc.id_bancos";
	        $tabla1 = "core_creditos aa
                    INNER JOIN forma_pago bb
                    ON aa.id_forma_pago = bb.id_forma_pago
                    INNER JOIN core_participes_cuentas cc
                    ON cc.id_participes = aa.id_participes
                    AND cc.cuenta_principal = true";
	        $where1 = "aa.id_estatus = 1 AND aa.id_creditos = $id_creditos";
	        $id1 = "aa.id_creditos";
	        $rsFormaPago = $Credito->getCondiciones($columnas1, $tabla1, $where1, $id1);
	        $_id_forma_pago = $rsFormaPago[0]->id_forma_pago;
	        $_id_bancos = $rsFormaPago[0]->id_bancos;
	        
	        $columnaPago = "id_forma_pago = $_id_forma_pago , id_banco = $_id_bancos ";
	        $tablasPago = "tes_cuentas_pagar";
	        $wherePago = "id_cuentas_pagar = $_id_cuentas_pagar";
	        $UpdateFormaPago = $Credito -> ActualizarBy($columnaPago, $tablasPago, $wherePago);
	        
	        //buscar tipo de comprobante
	        $rsTipoComprobantes = $TipoComprobantes->getTipoComprobanteByNombre("CONTABLE");
	        $_id_tipo_comprobantes = (!empty($rsTipoComprobantes)) ? $rsTipoComprobantes[0]->id_tipo_comprobantes : null;
	            
            $funcionComprobante     = "core_ins_ccomprobantes_activacion_credito";
            $valor_letras           = $Credito->numtoletras($_total_cuentas_pagar);
            $_concepto_comprobantes = "Consecion Creditos Sol:$numero_credito"; 
            //para parametros hay valores seteados
            $parametrosComprobante = "
                1,
                $_id_tipo_comprobantes,
                '',
                '',
                '',
                '$_total_cuentas_pagar',
                '$_concepto_comprobantes',
                '$_id_usuarios',
                '$valor_letras',
                '$_fecha_cuentas_pagar',
                '$_id_forma_pago',
                null,
                null,
                null,
                null,
                '$_id_proveedor',
                'cxp',
                '$usuario_usuarios',
                'credito',
                '$_id_lote'
                ";
	            
            $consultaComprobante = $Credito ->getconsultaPG($funcionComprobante, $parametrosComprobante);
            $resultadComprobantes = $Credito->llamarconsultaPG($consultaComprobante);
	            
            $error = "";
            $error = pg_last_error();
            if(!empty($error) || $resultadComprobantes[0] <= 0 ){   throw new Exception('error insercion comprobante contable '); }
	                
            // secuencial de comprobante
            $_id_ccomprobantes = $resultadComprobantes[0];
                        
            //se actualiza la cuenta por pagar con la relacion al comprobante
            $columnaCxP = "id_ccomprobantes = $_id_ccomprobantes ";
            $tablasCxP = "tes_cuentas_pagar";
            $whereCxP = "id_cuentas_pagar = $_id_cuentas_pagar";
            $UpdateCuentasPagar = $Credito -> ActualizarBy($columnaCxP, $tablasCxP, $whereCxP);
            
            //se actualiza el credito con su comprobante
            $columnaCre = "id_ccomprobantes = $_id_ccomprobantes ";
            $tablasCre = "core_creditos";
            $whereCre = "id_creditos = $id_creditos";
            $UpdateCredito= $Credito -> ActualizarBy($columnaCre, $tablasCre, $whereCre);                
                
            $Credito->endTran('COMMIT');
            return 'OK';
	                
	    } catch (Exception $e) {
	        
	        $Credito->endTran();
	        return $e->getMessage();
	    }
	}
	
	public function MayorizaComprobanteCredito($id_credito){
	    
	    $Credito = new CreditosModel();
	    
	    try {
	        $Credito->beginTran();
	        
	        $columas1  = "id_creditos, numero_creditos, id_ccomprobantes";
	        $tablas1   = "core_creditos";
	        $where1    = "id_creditos = $id_credito ";
	        $id1       = "id_creditos";
	        $rsConsulta1 = $Credito->getCondiciones($columas1, $tablas1, $where1, $id1);
	        
	        if(empty($rsConsulta1)){ throw new Exception('credito no encontrado');}
	        
	        $_id_comprobante       = $rsConsulta1[0]->id_ccomprobantes;
	        $funcionMayoriza       = "core_ins_mayoriza_activa_credito";
	        $parametrosMayoriza    = "$_id_comprobante,null";
	        $consultaMayoriza      = $Credito->getconsultaPG($funcionMayoriza, $parametrosMayoriza);
	        $Credito->llamarconsultaPG($consultaMayoriza);
	        
	        $error = "";
	        $error = pg_last_error();
	        if(!empty($error)){ throw new Exception('mayoriza comprobante credito');}
	        
	        $Credito->endTran('COMMIT');
	        return 'OK';
	        
	    } catch (Exception $e) {
	        $Credito->endTran();
	        return "ERROR".$e->getMessage();
	    }	    
	   
	}
	
	
	/** CAMBIOS CON TABLA TEMPORAL DC 20191218 **/
	
	public function insertaDiarioMensual(){
	    
	    session_start();
	    
	    $comprobantes = new CComprobantesModel();
	    
	    $id_usuarios = $_SESSION['id_usuarios'];
	    //$usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $idTipoProcesos = (isset($_POST['id_tipo_procesos'])) ? $_POST['id_tipo_procesos'] : "";
	    $anioDiario = (isset($_POST['anio_procesos'])) ? $_POST['anio_procesos'] : "";
	    $mesDiario = (isset($_POST['mes_procesos'])) ? $_POST['mes_procesos'] : "";
	    
	    $fecha_proceso = date('Y-m-d');
	    $respuesta = array();
	    
	    try {
	        
	        $comprobantes->beginTran();
	        
	        if( $idTipoProcesos == "" || $anioDiario == "" || $mesDiario == "" ){
	            throw new Exception("Error al recibir variables");
	        }
	        
	        $_funcion = "con_ins_procesos_mensuales";
	        $_parametros = "$id_usuarios,$idTipoProcesos,$anioDiario,$mesDiario,'$fecha_proceso'";
	        
	        
	        $_consulta1 = $comprobantes->getconsultaPG($_funcion, $_parametros);
	        $comprobantes->llamarconsultaPG($_consulta1);
	        
	        $error = pg_last_error();
	        
	        if( !empty($error) ){
	            throw new Exception("Proceso no generado Revisar");
	        }
	        
	        $respuesta['status']   = "OK";
	        $respuesta['mensaje']  = "PROCESO GENERADO";	        
	        $respuesta['icon']     = "success";
	        
	        $comprobantes->endTran('COMMIT');
	        echo json_encode($respuesta);
	        
	    } catch (Exception $e) {
	        
	        $respuesta['mensaje']    = $e->getMessage();
	        $respuesta['status']   = "ERRROR";
	        $respuesta['icon']     = "error";
	        
	        $comprobantes->endTran();
	        echo json_encode($respuesta);
	        
	    }
	    
	    
	}
	
	public function generaDiarioMensual(){
	    
	    session_start();
	    
	    $comprobantes = new CComprobantesModel();
	    
	    $id_usuarios = $_SESSION['id_usuarios'];
	    //$usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $idTipoProcesos = (isset($_POST['id_tipo_procesos'])) ? $_POST['id_tipo_procesos'] : "";
	    $anioDiario = (isset($_POST['anio_procesos'])) ? $_POST['anio_procesos'] : "";
	    $mesDiario = (isset($_POST['mes_procesos'])) ? $_POST['mes_procesos'] : "";
	    //$tipoPeticion = (isset($_POST['peticion'])) ? $_POST['peticion'] : "";
	    //$idModulos = (isset($_POST['id_modulos'])) ? $_POST['id_modulos'] : null;
	    
	    // variables que son para enviar a la vista
	    $_respuesta = array();
	    
	    try {
	        
	        /** consulta para validar si ya esta ingresado **/
	        $columnas1 = " 1 existe, id_ccomprobantes";
	        $tablas1   = " public.core_historial_diarios_tipo";
	        $where1    = " id_tipo_procesos = $idTipoProcesos
                AND id_estatus = 1
                AND anio_historial_diarios_tipo = $anioDiario
                AND mes_historial_diarios_tipo = $mesDiario";
	        $id1       = " id_historial_diarios_tipo";
	        $rsConsulta1   = $comprobantes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        
	        if( !empty($rsConsulta1) ){
	           throw new Exception("Procesos se encuentra Generado"); 
	        }
	        
	        /** consulta para buscar **/
	        $columnas2 = " aa.id_temp_procesos_mensuales, aa.id_usuarios, aa.id_tipo_procesos, aa.id_plan_cuentas,
    	        cc.codigo_plan_cuentas, cc.nombre_plan_cuentas, aa.valor_debe_temp_procesos_mensuales,
    	        aa.valor_haber_temp_procesos_mensuales";
	        $tablas2   = " con_temp_procesos_mensuales aa
    	        INNER JOIN estado bb ON bb.id_estado = aa.id_estado
    	        INNER JOIN plan_cuentas cc ON cc.id_plan_cuentas = aa.id_plan_cuentas";
	        $where2    = " aa.id_tipo_procesos = $idTipoProcesos
    	        AND aa.anio_temp_procesos_mensuales = $anioDiario
    	        AND aa.mes_temp_procesos_mensuales  = $mesDiario
    	        AND aa.id_usuarios	= $id_usuarios
    	        AND bb.nombre_estado='PENDIENTE'
                AND bb.tabla_estado='con_temp_procesos_mensuales'";
	        $id2       = " aa.id_temp_procesos_mensuales";
	        $rsConsulta2   = $comprobantes->getCondiciones($columnas2, $tablas2, $where2, $id2);
	        
	        if( empty($rsConsulta2) ){
	            
	            //generar el diario temporal
	            /** AQUI TENER EN CUENTA QUE SE TOMA DE ACUERDO AL TIPO DE PROCESO A GENERAR EL DIARIO**/
                switch ((int)$idTipoProcesos){
                    case 13 :
                        $this->genProcesoInventarioMateriales($idTipoProcesos,$anioDiario, $mesDiario);
                        break;
                    case 9 :
                        $this->genProcesoActivos($idTipoProcesos, $anioDiario, $mesDiario);
                        break;
                    case 8 :
                        //echo "llego por aca";
                        $this->genProcesoProvisiones($idTipoProcesos, $anioDiario, $mesDiario);
                        break;
                    default:
                        print_r($_POST);
                        exit();
                        break;
                }	               
	            
	            //se vuelve a relizar la consulta
	            $rsConsulta2   = $comprobantes->getCondiciones($columnas2, $tablas2, $where2, $id2);
	            
	        }
	        
	        $html = "";
	        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CODIGO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">NOMBRE</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">DEBITO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CREDITO</th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        /** aqui se genera la tabla que mostrara en la vista **/
	        $i = 0;
	        foreach ($rsConsulta2 as $res) {
	            $i++;
	            $html.='<tr>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->codigo_plan_cuentas.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_plan_cuentas.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->valor_debe_temp_procesos_mensuales.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->valor_haber_temp_procesos_mensuales.'</td>';
	            $html.='</tr>';
	        }
	        
	        $html.='</tbody>';
	        $html.='</table>';
	        
	        $_respuesta['status']     = "OK";
	        $_respuesta['mensaje']     = "Revisar Datos de Diario Contable";
	        $_respuesta['htmlTabla']   = $html;
	        $_respuesta['icon']        = "success";
	        echo json_encode( $_respuesta );
	        
	    } catch (Exception $ex) {
		    
	        $_respuesta['status']     = "ERROR";
	        $_respuesta['mensaje']   =$ex->getMessage();
	        $_respuesta['icon']    ="error";
	        echo json_encode( $_respuesta );
	    }
	    
	}
	
	private function genProcesoInventarioMateriales($_id_tipo_procesos,$_anio_proceso, $_mes_proceso){
	    
	    if( !isset($_SESSION) ){
	        session_start();
	    }
	    
	    $productos = new ProductosModel();
	    $_id_usuarios = $_SESSION['id_usuarios'];
	    
	    $arrayDiarioTipo = array();
	    
	    // OBTENER DATOS DEL DIARIO TIPO
	    $columnas1 = " cdtd.id_diario_tipo_detalle,
                    pc.id_plan_cuentas,pc.codigo_plan_cuentas,
                    pc.nombre_plan_cuentas,
                    destino_diario_tipo_detalle,
                    0.00 \"debito\", 0.00 \"credito\" ";
	    $tablas1   = " core_diario_tipo_detalle cdtd
        	    INNER JOIN core_diario_tipo_cabeza cdtc
        	    ON cdtc.id_diario_tipo_cabeza = cdtd.id_diario_tipo_cabeza
        	    INNER JOIN plan_cuentas pc
        	    ON pc.id_plan_cuentas = cdtd.id_plan_cuentas";
	    $where1    = " cdtc.id_tipo_procesos = '$_id_tipo_procesos' ";
	    $id1       = " cdtd.id_diario_tipo_detalle";
	    $rsConsulta1   = $productos->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    if(empty($rsConsulta1)){
	        echo "AQUI ERROR";
	        exit();
	    }
	    
	    // OBTENER SUMA DE MATERIALES EN SALIDA
	    $columnas2 = " bb.valor_movimientos_inv_detalle";
	    $tablas2   = " movimientos_inv_cabeza aa
	               INNER JOIN movimientos_inv_detalle bb ON bb.id_movimientos_inv_cabeza = aa.id_movimientos_inv_cabeza
                   INNER JOIN consecutivos cc ON cc.id_consecutivos = aa.id_consecutivos";
	    $where2    = " TO_CHAR(aa.creado,'YYYY') = '$_anio_proceso'
	               AND TO_CHAR(aa.creado,'MM')	= '$_mes_proceso'
                   AND cc.nombre_consecutivos = 'SALIDA'
	               AND aa.estado_movimientos_inv_cabeza = 'APROBADA'
	               AND upper(aa.razon_movimientos_inv_cabeza) = 'SALIDA PRODUCTOS'";
	    $id2       = " aa.creado";
	    $rsConsulta2   = $productos->getCondiciones($columnas2, $tablas2, $where2, $id2);
	    
	    //VARIABLES PARA LA FUNCION
	    $_sumatoria_valores = 0.00;
	    $arrayFila = array();
	    $arrayDiarioTipo   = array();
	    
	    if(!empty($rsConsulta2)){
	        foreach ($rsConsulta2 as $res) {
	            $_sumatoria_valores += (float)$res->valor_movimientos_inv_detalle;
	        }
	    }
	    
	    foreach ($rsConsulta1 as $resDiario) {
	        
	        $_valores_debe     = 0.00;
	        $_valores_haber    = 0.00;
	        if( $resDiario->destino_diario_tipo_detalle == "DEBE" ){
	            $_valores_debe     = $_sumatoria_valores;
	            $_valores_haber    = 0.00;
	        }else{
	            $_valores_debe     = 0.00;
	            $_valores_haber    = $_sumatoria_valores;
	        }
	        $arrayFila = array('id_plan_cuentas'=>$resDiario->id_plan_cuentas,'id_diario_detalle'=>$resDiario->id_diario_tipo_detalle,'valor_debe'=>$_valores_debe,'valor_haber'=>$_valores_haber);
	        array_push( $arrayDiarioTipo, $arrayFila);
	    }
	    
	    /** buscar id del estado en pediente  de una funcion*/	    
	    $id_estado = $this->getIdEstado();
	    
	    $_funcion  = "con_ins_temp_procesos_mensuales";
	    $_parametros = "";	    
	   
	    // PARA INGRESAR EN TEMP PROCESOS
	    foreach ($arrayDiarioTipo as $res){
	        // variables para la funcion de inse4rt en temp 
	        $_id_plan_cuentas = $res['id_plan_cuentas'];
	        $_valor_debe   = $res['valor_debe'];
	        $_valor_haber  = $res['valor_haber'];
	        $_parametros = "$_id_usuarios,$_id_tipo_procesos,$_id_plan_cuentas,$_anio_proceso,$_mes_proceso,'$_valor_debe','$_valor_haber',$id_estado";
	        
	        $consultapg    = $productos->getconsultaPG($_funcion, $_parametros);
	        $productos->llamarconsultaPG($consultapg);
	    } 
	    
	    $error = pg_last_error();
	    if( !empty($error) ){
	        throw new Exception("Revisar los procesos en Inventario Y materiales");
	    }
	        
	   
	    
	}
	
	private function genProcesoActivos($_id_tipo_procesos,$_anio_proceso, $_mes_proceso){
	    
	    if( !isset($_SESSION) ){
	        session_start();
	    }
	    
	    $_id_usuarios = $_SESSION['id_usuarios'];
	    
	    $Activos = new ActivosFijosModel();
	    $arrayDiarioTipo = array();
	    
	    $arrayColDepreciacion = array("enero_depreciacion","febrero_depreciacion","marzo_depreciacion","abril_depreciacion","mayo_depreciacion",
	        "junio_depreciacion","julio_depreciacion","agosto_depreciacion","septiembre_depreciacion","octubre_depreciacion","noviembre_depreciacion",
	        "diciembre_depreciacion");
	    //obtener columna de busqueda
	    $columnaMes = $arrayColDepreciacion[ ($_mes_proceso - 1)];
	    	    
	    //traer activos para depreciacion
	    $columnas ="aaf.id_tipo_activos_fijos,taf.nombre_tipo_activos_fijos,sum(ad.$columnaMes) \"valor_depreciacion\"";
	    $tablas ="act_activos_fijos aaf
                inner join tipo_activos_fijos taf
                on taf.id_tipo_activos_fijos = aaf.id_tipo_activos_fijos
                inner join act_depreciacion ad
                on aaf.id_activos_fijos = ad.id_activos_fijos";
	    $where = " 1=1
	           AND ad.anio_depreciacion = $_anio_proceso";
	    $grupo = "aaf.id_tipo_activos_fijos,taf.nombre_tipo_activos_fijos";
	    $id="aaf.id_tipo_activos_fijos";
	    
	    $rsActivos = $Activos->getCondiciones_grupo($columnas, $tablas, $where, $grupo, $id);
	    
	    
	    //paradatos de diario tipo
	    $colDiarioTipo = "cdtd.id_diario_tipo_detalle,
                    pc.id_plan_cuentas,pc.codigo_plan_cuentas,
                    pc.nombre_plan_cuentas,
                    destino_diario_tipo_detalle,
                    0.00 \"debito\", 0.00 \"credito\"";
	    $tabDiarioTipo = "core_diario_tipo_detalle cdtd
        	    INNER JOIN core_diario_tipo_cabeza cdtc
        	    ON cdtc.id_diario_tipo_cabeza = cdtd.id_diario_tipo_cabeza
        	    INNER JOIN plan_cuentas pc
        	    ON pc.id_plan_cuentas = cdtd.id_plan_cuentas";
	    $wheDiarioTipo = "cdtc.id_tipo_procesos = '$_id_tipo_procesos'";
	    $idDiarioTipo = "cdtd.id_diario_tipo_detalle";
	    $rsDiarioTipo = $Activos->getCondiciones($colDiarioTipo, $tabDiarioTipo, $wheDiarioTipo, $idDiarioTipo);
	    
	    //consulta tipo de activos
	    $queryTipoActivo = "SELECT * FROM tipo_activos_fijos ";
	    $rsTipoActivos = $Activos->enviaquery($queryTipoActivo);
	    
	    foreach ($rsDiarioTipo as $resDiario){
	        $_id_plan_cuenta_diario = $resDiario->id_plan_cuentas;
	        $_id_tipo_activo = 0;
	        $_sumatoriaDepreciacion = 0;
	        $_valor_debe = 0;
	        $_valor_haber = 0;
	        
	        if( $resDiario->destino_diario_tipo_detalle == 'DEBE'){
	            foreach ($rsTipoActivos as $resTipoActivo){
	                if($resTipoActivo->debe_id_plan_cuentas == $_id_plan_cuenta_diario ){
	                    $_id_tipo_activo = $resTipoActivo->id_tipo_activos_fijos;
	                    break;
	                }
	            }
	            foreach ($rsActivos as $resActivos){
	                if($resActivos->id_tipo_activos_fijos == $_id_tipo_activo ){
	                    $_sumatoriaDepreciacion = $resActivos->valor_depreciacion;
	                    break;
	                }
	            }
	            $_valor_debe = $_sumatoriaDepreciacion;
	        }
	        if( $resDiario->destino_diario_tipo_detalle == 'HABER'){
	            foreach ($rsTipoActivos as $resTipoActivo){
	                if($resTipoActivo->haber_id_plan_cuentas == $_id_plan_cuenta_diario ){
	                    $_id_tipo_activo = $resTipoActivo->id_tipo_activos_fijos;
	                    break;
	                }
	                
	            }
	            foreach ($rsActivos as $resActivos){
	                if($resActivos->id_tipo_activos_fijos == $_id_tipo_activo ){
	                    $_sumatoriaDepreciacion = $resActivos->valor_depreciacion;
	                    break;
	                }
	            }
	            $_valor_haber = $_sumatoriaDepreciacion;
	        }
	        
	        $arrayFila = array('id_diario_detalle'=>$resDiario->id_diario_tipo_detalle,'id_plan_cuentas'=>$resDiario->id_plan_cuentas,'valor_debe'=>$_valor_debe,'valor_haber'=>$_valor_haber);
	        array_push( $arrayDiarioTipo, $arrayFila);
	        
	    }
	    /** buscar id del estado en pediente  de una funcion*/
	    $id_estado = $this->getIdEstado();
	    
	    $_funcion  = "con_ins_temp_procesos_mensuales";
	    $_parametros = "";	
	    // PARA INGRESAR EN TEMP PROCESOS
	    foreach ($arrayDiarioTipo as $res){
	        // variables para la funcion de inse4rt en temp
	        $_id_plan_cuentas = $res['id_plan_cuentas'];
	        $_valor_debe   = $res['valor_debe'];
	        $_valor_haber  = $res['valor_haber'];
	        $_parametros = "$_id_usuarios,$_id_tipo_procesos,$_id_plan_cuentas,$_anio_proceso,$_mes_proceso,'$_valor_debe','$_valor_haber',$id_estado";
	        
	        $consultapg    = $Activos->getconsultaPG($_funcion, $_parametros);
	        $Activos->llamarconsultaPG($consultapg);
	    }
	    
	    $error = pg_last_error();
	    if( !empty($error) ){
	        throw new Exception("Revisar los procesos en Activos Fijos");
	    }
	    	    	    
	}
	
	private function genProcesoProvisiones($_id_tipo_procesos,$_anio_proceso, $_mes_proceso ){
	    
	    if( !isset($_SESSION) ){
	        session_start();
	    }
	    
	    $_id_usuarios = $_SESSION['id_usuarios'];
	        
	    $mes = $_mes_proceso;
        $year = $_anio_proceso;
        $mes--;
        if ($mes==0){
            $mes=12;  $year--;
        }
        $diainicio = 22;
        $diafinal = 21;
        $fechai = $diainicio."/".$mes."/".$year;
        $mes++;
        if ($mes>12){
            $mes=1;
            $year++;
            $fechaf = $diafinal."/".$mes."/".$year;
        }else{
            $fechaf = $diafinal."/".$mes."/".$year;
        }
	        
        $periodo=$fechai."-".$fechaf;
        //codigo aneterior genera fecha para buscar el periodo
        
        $Participes = new ParticipesModel();
        
        //paradatos de diario tipo
        $colDiarioTipo = "cdtd.id_diario_tipo_detalle,
                pc.id_plan_cuentas,pc.codigo_plan_cuentas,
                pc.nombre_plan_cuentas,
                destino_diario_tipo_detalle,
                0.00 \"debito\", 0.00 \"credito\"";
        $tabDiarioTipo = "core_diario_tipo_detalle cdtd
    	    INNER JOIN core_diario_tipo_cabeza cdtc
    	    ON cdtc.id_diario_tipo_cabeza = cdtd.id_diario_tipo_cabeza
    	    INNER JOIN plan_cuentas pc
    	    ON pc.id_plan_cuentas = cdtd.id_plan_cuentas";
        $wheDiarioTipo = "cdtc.id_tipo_procesos = '$_id_tipo_procesos'";
        $idDiarioTipo = "cdtd.id_diario_tipo_detalle";
        $rsDiarioTipo = $Participes->getCondiciones($colDiarioTipo, $tabDiarioTipo, $wheDiarioTipo, $idDiarioTipo);
        
        //buscar provisiones mensuales
        $queryProvisiones = "SELECT id_provisiones_nomina, fondos_reserva, dec_tercero_sueldo, dec_cuarto_sueldo,aporte_iess_2,periodo
                        FROM provisiones_nomina_empleados WHERE periodo = '$periodo'";
        $rsProvisiones = $Participes->enviaquery($queryProvisiones);
        
        if( empty($rsProvisiones) ){
            throw new Exception("Proceso No Generado. Revisar el modulo de nomina ");
        }
        
        $_sumAporteIESS = 0;
        $_sumaDecimo13 = 0;
        $_sumaDecimo14 = 0;
        $_sumaFondo = 0;
        
        foreach ($rsProvisiones as $res){
            $_sumAporteIESS = $_sumAporteIESS + $res->aporte_iess_2;
            $_sumaDecimo13 = $_sumaDecimo13 + $res->dec_tercero_sueldo;
            $_sumaDecimo14 = $_sumaDecimo14 + $res->dec_cuarto_sueldo;
            $_sumaFondo = $_sumaFondo + $res->fondos_reserva;
        }
	        
        $arrayDiarioTipo = array();
        $_valor_debe = 0;
        $_valor_haber = 0;
        
        foreach ($rsDiarioTipo as $resDiario){
            $_codigo_plan_cuentas = $resDiario->codigo_plan_cuentas;
            $_codigo_plan_cuentas = trim($_codigo_plan_cuentas,'.');
            $_valor_debe = 0;
            $_valor_haber = 0;
            //echo $_codigo_plan_cuentas.'<br>';
            switch ($_codigo_plan_cuentas){
                case "4.3.01.20" :
                    $_valor_debe = $_sumAporteIESS;
                    break;
                case "4.3.01.15.02":
                    $_valor_debe = $_sumaDecimo14;
                    break;
                case "4.3.01.15.01" :
                    $_valor_debe = $_sumaDecimo13;
                    break;
                case "4.3.01.25":
                    $_valor_debe = $_sumaFondo;
                    break;
                case "2.5.03.06" :
                    $_valor_haber = $_sumAporteIESS;
                    break;
                case "2.5.02.02":
                    $_valor_haber = $_sumaDecimo14;
                    break;
                case "2.5.02.01" :
                    $_valor_haber = $_sumaDecimo13;
                    break;
                case "2.5.04.01":
                    $_valor_haber = $_sumaFondo;
                    break;
                default:
                    $_valor_debe = 0;
                    $_valor_haber = 0;
                    break;
            }
	            
            $arrayFila = array('id_diario_detalle'=>$resDiario->id_diario_tipo_detalle,'id_plan_cuentas'=>$resDiario->id_plan_cuentas,'valor_debe'=>$_valor_debe,'valor_haber'=>$_valor_haber);
	            array_push( $arrayDiarioTipo, $arrayFila);
	            
        }
        
        /** buscar id del estado en pediente  de una funcion*/
        $id_estado = $this->getIdEstado();
        
        $_funcion  = "con_ins_temp_procesos_mensuales";
        $_parametros = "";
        // PARA INGRESAR EN TEMP PROCESOS
        foreach ($arrayDiarioTipo as $res){
            // variables para la funcion de inse4rt en temp
            $_id_plan_cuentas = $res['id_plan_cuentas'];
            $_valor_debe   = $res['valor_debe'];
            $_valor_haber  = $res['valor_haber'];
            $_parametros = "$_id_usuarios,$_id_tipo_procesos,$_id_plan_cuentas,$_anio_proceso,$_mes_proceso,'$_valor_debe','$_valor_haber',$id_estado";
            
            $consultapg    = $Participes->getconsultaPG($_funcion, $_parametros);
            $Participes->llamarconsultaPG($consultapg);
        }
        
        $error = pg_last_error();
        if( !empty($error) ){
            throw new Exception("Revisar los procesos en Activos Fijos");
        }
	}
	
	
	/** FUNCIONES PARA MODAL EN VISTA ProcesosMayorizacionView **/
	public function listaProcesosMensuales(){
	    
	    $comprobantes = new CComprobantesModel();
	    
	    $page = ( isset($_REQUEST['page'] ) && !empty( $_REQUEST['page'] ) ) ? $_REQUEST['page'] : 1;
	    $_id_modulos   = isset( $_POST['id_modulos'] ) ?  $_POST['id_modulos'] : 0;
	    $_anio_procesos= isset( $_POST['anio_procesos'] ) ?  $_POST['anio_procesos'] : 0;
	    $_mes_procesos = isset( $_POST['mes_procesos'] ) ?  $_POST['mes_procesos'] : 0;
	    
	    $columnas1     = " aa.id_historial_diarios_tipo, aa.anio_historial_diarios_tipo, aa.mes_historial_diarios_tipo,
        	    bb.id_tipo_procesos, bb.nombre_tipo_procesos, cc.id_modulos, cc.nombre_modulos, dd.id_ccomprobantes,
        	    dd.numero_ccomprobantes";
	    $tablas1       = " core_historial_diarios_tipo aa
        	    INNER JOIN core_tipo_procesos bb ON bb.id_tipo_procesos = aa.id_tipo_procesos
        	    INNER JOIN modulos cc ON cc.id_modulos = bb.id_modulos
        	    INNER JOIN ccomprobantes dd ON dd.id_ccomprobantes = aa.id_ccomprobantes";
	    $where1        = " 1 = 1 ";
	    $id1           = " aa.anio_historial_diarios_tipo DESC, aa.mes_historial_diarios_tipo ";	
	    
	    /** VALIDACIONES PARA WHERE **/
	    
	    $where1 .=  $_id_modulos != 0 ? " AND cc.id_modulos = $_id_modulos " : "";
	    $where1 .=  $_anio_procesos != 0 ? " AND aa.anio_historial_diarios_tipo = $_anio_procesos " : "";
	    $where1 .=  $_mes_procesos != 0 ? " AND aa.mes_historial_diarios_tipo = $_mes_procesos " : "";
	    
	    
	    $resultSet=$comprobantes->getCantidad("*", $tablas1, $where1);
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet = $comprobantes->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    $trHtml = "";
	    $paginacion = "";
	    $respuesta = array();
	    
	    if( sizeof($resultSet) > 0 ){
	        $i = 0;
	        foreach ( $resultSet as $res) {
	            $i++;
	            $trHtml .= "<tr>";
	            $trHtml .= "<td>$i</td>";
	            $trHtml .= "<td>$res->nombre_modulos</td>";
	            $trHtml .= "<td>$res->nombre_tipo_procesos</td>";
	            $trHtml .= "<td>$res->anio_historial_diarios_tipo</td>";
	            $trHtml .= "<td>$res->mes_historial_diarios_tipo</td>";
	            $trHtml .= "<td>$res->numero_ccomprobantes</td>";
	            $trHtml .= "</tr>";
	        }        
	        //columnas en vista
	        // # MOdulo - nombre proceso - anio - mes - numero de comprobante
	        $paginacion = $this->paginate("index.php", $page, $total_pages, $adjacents, "fn1");
	    }
	   
	    $respuesta['dataFilas'] = $trHtml;
	    $respuesta['dataPaginacion'] = $paginacion;
        
	    echo json_encode($respuesta);
	}
	
	public function getModulosModal(){
	    
	    $modulos = new ModulosModel();
	    
	    $colModulos = " modulos.id_modulos, modulos.nombre_modulos";
	    $tabModulos = " public.modulos
        			    INNER JOIN public.core_tipo_procesos
        			    ON core_tipo_procesos.id_modulos = modulos.id_modulos ";
	    $wheModulos = " 1 = 1 AND diarios_tipo_procesos = 't' ";
	    $gruModulos = " modulos.id_modulos, modulos.nombre_modulos ";
	    $idModulos = " modulos.nombre_modulos ";
	    
	    $rsModulos = $modulos->getCondiciones_grupo($colModulos,$tabModulos,$wheModulos,$gruModulos,$idModulos);
	    
	    if( !empty($rsModulos) ){
	        echo json_encode( array("dataModulos"=>$rsModulos) );
	    }
	    
	}
	
	private function getIdEstado(){
	    /** buscar id del estado en pediente */
	    $Estado = new EstadoModel();
	    $rsEstado = $Estado->getBy("tabla_estado='con_temp_procesos_mensuales' AND nombre_estado ='PENDIENTE'");
	    $id_estado = ( !empty($rsEstado) ) ? $rsEstado[0]->id_estado : "null";
	    return $id_estado;
	}
	
	public function fnDatos(){
	    
	    echo date('Y-m-d');
	}
	
}
?>