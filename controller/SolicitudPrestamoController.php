<?php

class SolicitudPrestamoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
		
	}
	

	
	
	
	
	public function print_hipotecario()
	{
	    
	    session_start();
	   
	    
	    require_once 'core/DB_Functions.php';
	    $db = new DB_Functions();
	    
	    
	    $html="";
	    
	    $id_usuarios = $_SESSION["id_usuarios"];
	    $fechaactual = getdate();
	    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	    
	    $directorio = $_SERVER ['DOCUMENT_ROOT'] . '/rp_c';
	    $dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
	    $domLogo=$directorio.'/view/images/logo_contrato_adhesion.jpg';
	    $logo = '<img src="'.$domLogo.'" width="100%">';
	    
	    
	    if(!empty($id_usuarios)){
	        
	        if(isset($_GET["id_solicitud_prestamo"])){
	            
	            $id_solicitud_prestamo=$_GET["id_solicitud_prestamo"];
	            
	            $columnas="";
	            $tablas=" ";
	            $where="";
	            $id="";
	            
	            $resultSoli=$db->getCondicionesDesc($columnas, $tablas, $where, $id);
	            
	            if(empty($resultSoli)){
	                
	                // DATOS DEL PRESTAMO
	               // $_id_solicitud_prestamo       					=$resultSoli[0]->id_solicitud_prestamo;
	                    
	                    
	                    
	                    $html.='<table style="width: 100%;"  border=1 cellspacing=0.0001 >';
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 15px;"><b>FONDO COMPLEMENTARIO PREVISIONAL CERRADO DE CESANTÍA DE SERVIDORES Y TRABAJADORES PÚBLICOS DE FUERZAS ARMADAS CAPREMCI<br><br>SOLICITUD DE CRÉDITO HIPOTECARIO<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;"><b>DATOS DEL CRÉDITO<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;"><b>Valor en dólares<b></th>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;"><b>Plazo en meses<b></th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;"><b>Destino del dinero<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="text-align:center; font-size: 13px;"> </th>';
	                    $html.='<td colspan="3" style="text-align:center; font-size: 13px;"> </th>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"> </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;"><b>DATOS PERSONALES DEL AFILIADO SOLICITANTE<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;"><b>Apellidos y Nombres<b></th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">No. de Cédula<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"> </th>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"> </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Género</th>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;">Fecha de nacimiento</th>';
	                    $html.='<th colspan="5" style="text-align:center; font-size: 13px;">Estado civil</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    if($_nombre_sexo=='MASCULINO'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">FEMENINO</td>';
	                    }
	                    else{
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">MASCULINO</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                    }
	                    
	                    
	                    $html.='<td colspan="3" style="text-align:center; font-size: 13px;">     </th>';
	                    
	                    if('Soltero'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Casado'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Viudo'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Divorciado'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Otro'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                    }
	                    
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Separación de bienes</th>';
	                    $html.='<th colspan="8" style="text-align:center; font-size: 13px;">Cargas familiares:</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    
	                    if('SI'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">NO</td>';
	                    }
	                    else{
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">SI</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                    }
	                    
	                    if('SI'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">NO</td>';
	                    }
	                    else{
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">SI</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                    }
	                    $html.='<th colspan="4" style="font-size: 13px;">Cuántas?   </th>';
	                    
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">Dirección exacta del domicilio del afiliado</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Provincia</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cantón</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Parroquia</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Barrio y/o sector</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Ciudadela y/o conjunto / Etapa / Manzana</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Calle</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Número</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Intersección</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="8" style="text-align:center; font-size: 13px;">Vivienda:</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Su vivienda esta hipotecada:</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    if('Propia'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>  </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                    }
	                    elseif('Arrendada'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                    }
	                    elseif('Anticresis'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                    }
	                    elseif('Vive con Familiares'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                    }
	                    elseif('Otra'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"></td<u>  </u>>';
	                    }
	                    
	                    if('SI'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>  </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">NO</td>';
	                    }
	                    else{
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">SI</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                    }
	                    
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Tiempo de residencia</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Si no tiene vivienda propia y número telefónico del propietario:</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">   </th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="12" style="font-size: 13px;"><b>Referencia de la dirección del domicilio:   </b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="font-size: 13px;">Números telefónicos:</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;"><b>Casa: </b>   </th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"><b>Celular: </b>  </th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"><b>Trabajo: </b>   </th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"><b>Ext. </b>  </th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"><b>Mode: </b>  </th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Dirección electrónica</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Nivel educativo</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">  </th>';
	                    
	                    if('Primario'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    elseif('Secundario'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u><b>   <b></u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    elseif('Técnico'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    elseif('Universitario'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   /u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    elseif('Postgrado'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                    }
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Referencia familiar que no viva con Ud. </th>';
	                    $html.='<th colspan="9" style="text-align:center; font-size: 13px;">Parentesco</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">Dos números telefónicos</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">   </th>';
	                    
	                    if('Primo'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Tío'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Hermano'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Sobrino'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Abuelo'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Hijo'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Madre'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Padre'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Otro'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                    }
	                    
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px;">  </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;">Referencia personal</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Parentesco</th>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;">Dos números telefónicos</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="text-align:center; font-size: 13px;">   </th>';
	                    
	                    if('Amigo'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Comadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compañero Laboral(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Jefe(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Compadre'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Amigo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Comadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compañero Laboral(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Jefe(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Comadre'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Amigo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>  </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compañero Laboral(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Jefe(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Compañero Laboral(a)'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Amigo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Comadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Jefe(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Jefe(a)'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Amigo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Comadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compañero Laboral(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Otro'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Amigo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Comadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compañero Laboral(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Jefe(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u></u></td>';
	                    }
	                    
	                    $html.='<td colspan="3" style="text-align:center; font-size: 13px;">  </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">DATOS LABOLARES DEL AFILIADO SOLICIENTANTE</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Fuerza o entiedad patronal</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Reparto / Unidad</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Sección</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="12" style="font-size: 13px;"><b>Nombre y número telefónico del Jefe Inmediato:<b>    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Provincia</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cantón</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Parroquia</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Calle</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Número</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Intersección</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="12" style="text-align:center; font-size: 13px;"><b>Referencia de la direción de trabajo:<b>    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Cargo Actual</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Años de Servicio</th>';
	                    $html.='</tr>';
	                    
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">DATOS DEL CONYUGE DEL AFILIADO</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;"><b>Apellidos y Nombres<b></th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">No. de Cédula<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"> </th>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"> </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Género</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Fecha de nacimiento</th>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Vive en la residencia del afiliado</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Números telefónicos</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    if('MASCULINO'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">FEMENINO</td>';
	                    }
	                    else{
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">MASCULINO</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                    }
	                    
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">     </th>';
	                    
	                    if('SI'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">NO</td>';
	                    }
	                    else{
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">SI</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                    }
	                    
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">     </th>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">Si no vive con su cónyuge su dirección exacta</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Provincia</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cantón</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Parroquia</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Barrio y/o sector</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Ciudadela y/o conjunto / Etapa / Manzana</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Calle</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Número</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Intersección</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">Actividad económica del conyuge</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    if('Ninguna'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado público</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado privado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Libre ejercicio profesional</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Independiente</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Jubilado</td>';
	                    }
	                    elseif('Empleado público'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Ninguna</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>  </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado privado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Libre ejercicio profesional</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Independiente</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Jubilado</td>';
	                    }
	                    elseif('Empleado privado'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Ninguna</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado público</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Libre ejercicio profesional</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Independiente</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Jubilado</td>';
	                    }
	                    elseif('Libre ejercicio profesional'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Ninguna</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado público </td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado privado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Independiente</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Jubilado</td>';
	                    }
	                    elseif('Independiente'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Ninguna</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado público </td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado privado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Libre ejercicio profesional</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Jubilado</td>';
	                    }
	                    elseif('Jubilado'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Ninguna</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado público </td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado privado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Libre ejericio profesional</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Independiente</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                    }
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Empresa / Institución</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Naturaleza del negocio</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cargo</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">Tipo de contrato</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    if('Nombramiento'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Término fijo</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Término indefinido</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Prestación de servicios</td>';
	                    }
	                    elseif('Término fijo'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Nombramiento</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Término indefinido</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Prestación de servicios</td>';
	                    }
	                    elseif('Término indefinido'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Nombramiento</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Término fijo</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>  </u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Prestación de servicios</td>';
	                    }
	                    elseif('Prestación de servicios'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Nombramiento</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Término fijo</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Término indefinido</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                    }
	                    
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">Tiempo laborado:    </th>';
	                   
	                    
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="12" style="text-align:center; font-size: 13px;"><b>Nombre y número telefónico del Jefe Inmediato:</b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Provincia</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cantón</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Parroquia</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                   
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Calle</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Número</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Intersección</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="12" style="font-size: 13px;"><b>Referencia de la dirección del trabajo:</b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">Datos de la actividad para independencia o Detalle de Proyecto</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Actividad principal</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">RUC</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="font-size: 13px;"><b>Dirección completa:</b>   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="font-size: 13px;"><b>Detalle las actividades e Ingresos y Egresos:</b>   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Local:</th>';
	                    if('Propio'){
	                        $html.='<td colspan="3" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                        $html.='<td colspan="3" style="text-align:center; font-size: 13px;">Arrendado</td>';
	                        $html.='<td colspan="3" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Arrendado'){
	                        $html.='<td colspan="3" style="text-align:center; font-size: 13px;">Propio</td>';
	                        $html.='<td colspan="3" style="text-align:center; font-size: 13px;"><u>    </u></td>';
	                        $html.='<td colspan="3" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif('Otro'){
	                        $html.='<td colspan="3" style="text-align:center; font-size: 13px;">Propio</td>';
	                        $html.='<td colspan="3" style="text-align:center; font-size: 13px;">Arrendado</td>';
	                        $html.='<td colspan="3" style="text-align:center; font-size: 13px;"><u>   </u></td>';
	                    }
	                    
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="font-size: 13px;"><b>Nombre y número telefónico del propietario:</b>   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Tiempo de funcionamiento</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Número patronal</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Número de empleados</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="font-size: 13px;">Datos económicos y financieros</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="font-size: 13px;">Referencias bancarias/th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Institución</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Tipo de cuenta o producto(tarjeta)</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Número de cuenta / tarjeta</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="font-size: 13px;">Referencia comercial y/o proveedores</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Empresa / Proveedor</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Dirección</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Número telefónico</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">    </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="font-size: 13px;">Estado de Situación Patrimonial</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Activos</th>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Valores en dólares</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Pasivos</th>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Valores en dólares</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">Activos Corrietes</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">   </th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">Pasivos Corrientes</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Efecivo</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Préstamo CAPREMCI(menor a 1 año)</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Bancos / Cooperativas</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Préstamo emergente CAPREMCI</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Cuentas por cobrar</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Cuentas por pagar</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Inversiones</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Proveedores</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Inversiones / Mercaderías</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Otras obligaciones menores a 1 año</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Muebles y enseres</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Con Banco</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Otros Activos Corrientes (detalle)</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Con Cooperativas</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;">   </th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Total Pasivos Corrientes</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Total Activos Corrientes</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center;font-size: 13px;">Activos Fijos</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="text-align:center;font-size: 13px;">Pasivos a Largo Plazo</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Terreno</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Préstamo CAPREMCI (mayor a 1 año)</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Vivienda</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Otras obligaciones mayores a 1 año</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Vehículo</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Con Banco</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Maquinaria</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Con Cooperativas</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Otros Activos Fijos (detalle) </th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Otros pasivos a largo plazo (detalle)</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Total de activos fijos</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Total de pasivos a largo plazo</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Activos Intangibles</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Total de Pasivos </th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Valor de su Prestación en CAPREMCI</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Patrimonio </th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Total de Activos</th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;">Garantías de CAPREMCI </th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="4" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-aling-center; font-size: 13px;">Detalle de activos</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">Bancos / Cooperativas/ Inversiones</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Tipo / Producto</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Valor / Saldo</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Plazo</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">Muebles / Inmuebles / Enseres</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Dirección</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Valor</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"Está hipotecado?</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">Vehículos / Marca / Placa / Año</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Valor</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"Uso></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Está Asegurado?</th>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">Otros Activos</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Valor</th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">Observación</th>';
	                    $html.='</tr>';
	                   
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="12" style="text-align:center; font-size: 13px;">Detalle de pasivos</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Institución</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Valor</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Destino</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Garantía</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Plazo</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Saldo</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">Ingresos y Gastos Mensuales</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;">Ingresos</th>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;">Valor en dólares</th>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;">Gastos</th>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;">Valor en dólares</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Sueldo del afiliado</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Alimentación</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Sueldo del cónyuge</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Arriendos</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Comisiones</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Educación</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Arriendos</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Vestuarios</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Dividendos</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Servicios públicos</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Ingresos del negocio</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Movilizacion / Transporte</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Pensiones</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Ahorros cooperativas</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Otros ingresos (detalle)</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Cuotas tarjetas de crédito</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Cuotas de préstamos</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Otros gastos (detalle)</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Total</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<th colspan="3" style="font-size: 13px;">Total</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;">Disponible</th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='<td colspan="3" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">Croquis del domicilio del afiliado</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="12" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">Croquis del negocio / otro</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="12" style="font-size: 13px;"></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">Resumen de condiciones generales</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="12" style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 10px;"><b>1 Solicitud de crédito:</b> La solicitud de crédito debe
                        estar correcta y completamente llena. La información proporcionada es verdadera, refleja adecuadamente mi (nuestra) situación 
                        y es de mi (nuestra) exclusiva responsabilidad y esta sujeta a verificación por parte del Fondo. En caso de omisión, falsedad o 
                        alteración acepto (amos) que sea motivo de negar o rechazar la misma.</p>
                        <p><b>2 Verificación de información y documentación</b> Autorizo (amos) expresamente al Fondo Complementario Previsional Cerrado de 
                        Cesantía de Servidores Y Trabajadores Públicos y de Fuerzas Armanadas -CAPREMCI- a obtener de cualquier fuente la información
                        incluida la Central de Riesgos, referencias relativas a mi (nosotros), a mi (nuestro) trabajo, a mi (nuestro) comportamiento 
                        crediticio, manejo de mi (s)(nuestras) cuenta (s), corriente (s), de ahorro, tarjeta (s) de crédito, etc., en general al 
                        cumplimiento de mi (nuestras) obligaciones.</p>
                        <p><b>3 Información para terceros:</b>Fondo Complementario Previsional Cerrado de Cesantía de Servidores Y Trabajadores Públicos y de
                        Fuerzas Armanadas -CAPREMCI- queda expresamente autorizado para que pueda utilizar, trasferir o entregar dicha información a autoridades
                        competentes, organismos de control, Burós de información Crediticia y otras instituciones o personas jurídicas, legas o reglamentariamente
                        facultadas, así como para que pueda hacer público mi (nuestro) comportamiento crediticio.</p>
                        <p><b>4 Declaración de licitud de fondos:</b> Declaro expresamente que el origen y procedencia de los bienes que poseo son lícitos, además
                        que los fondos recibidos en calidad de préstamo no serán destinados a ninguna actividad ilícita ni relacionada con la producción,
                        consumo ni comercialización de sustancias y/o psicotrópicas.</p></th>';
	                    $html.='<br>';
	                    $html.='<br>';
	                    $html.='<br>';
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 11px;">Firma del afiliado deudor</p>';
	                   
	                    
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="font-size: 13px;"><b>Recibido por:</b></th>';
	                    $html.='<td colspan="6" style="font-size: 13px;"><b>Fecha:</b></th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='</table>';
	                    
	                    
	                    
	            }
	            
	            $this->report("SolicitudPrestamo",array("resultSet"=>$html));
	            die();
	            
	        }
	        
	    }else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	    }
	    
	}
	
	
	
	public function print()
	{
	    
	    session_start();
	    
	    require_once 'core/DB_Functions.php';
	    $db = new DB_Functions();
	    
	    
	    $html="";
	    
	    $id_usuarios = $_SESSION["id_usuarios"];
	    $fechaactual = getdate();
	    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	    
	    $directorio = $_SERVER ['DOCUMENT_ROOT'] . '/rp_c';
	    $dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
	    $domLogo=$directorio.'/view/images/logo_contrato_adhesion.jpg';
	    $logo = '<img src="'.$domLogo.'" width="100%">';
	    
	    
	    if(!empty($id_usuarios)){
	        
	        if(isset($_GET["id_solicitud_prestamo"])){
	            
	            $id_solicitud_prestamo=$_GET["id_solicitud_prestamo"];
	            
	            $columnas="solicitud_prestamo.id_solicitud_prestamo,
						  solicitud_prestamo.tipo_participe_datos_prestamo,
						  solicitud_prestamo.monto_datos_prestamo,
						  solicitud_prestamo.plazo_datos_prestamo,
						  solicitud_prestamo.destino_dinero_datos_prestamo,
						  solicitud_prestamo.nombre_banco_cuenta_bancaria,
						  solicitud_prestamo.tipo_cuenta_cuenta_bancaria,
						  solicitud_prestamo.numero_cuenta_cuenta_bancaria,
						  solicitud_prestamo.numero_cedula_datos_personales,
						  solicitud_prestamo.apellidos_solicitante_datos_personales,
						  solicitud_prestamo.nombres_solicitante_datos_personales,
						  solicitud_prestamo.correo_solicitante_datos_personales,
						  sexo.nombre_sexo,
						  solicitud_prestamo.fecha_nacimiento_datos_personales,
						  estado_civil.nombre_estado_civil,
						  solicitud_prestamo.separacion_bienes_datos_personales,
						  solicitud_prestamo.cargas_familiares_datos_personales,
						  solicitud_prestamo.numero_hijos_datos_personales,
						  solicitud_prestamo.nivel_educativo_datos_personales,
						  provincias.nombre_provincias,
						  cantones.nombre_cantones,
						  parroquias.nombre_parroquias,
						  solicitud_prestamo.barrio_sector_vivienda,
						  solicitud_prestamo.ciudadela_conjunto_etapa_manzana_vivienda,
						  solicitud_prestamo.calle_vivienda,
						  solicitud_prestamo.numero_calle_vivienda,
						  solicitud_prestamo.intersecion_vivienda,
						  solicitud_prestamo.tipo_vivienda,
						  solicitud_prestamo.vivienda_hipotecada_vivienda,
						  solicitud_prestamo.tiempo_residencia_vivienda,
						  solicitud_prestamo.nombre_propietario_vivienda,
						  solicitud_prestamo.celular_propietario_vivienda,
						  solicitud_prestamo.referencia_direccion_domicilio_vivienda,
						  solicitud_prestamo.numero_casa_solicitante,
						  solicitud_prestamo.numero_celular_solicitante,
						  solicitud_prestamo.numero_trabajo_solicitante,
						  solicitud_prestamo.extension_solicitante,
						  solicitud_prestamo.mode_solicitante,
						  solicitud_prestamo.apellidos_referencia_personal,
						  solicitud_prestamo.nombres_referencia_personal,
						  solicitud_prestamo.relacion_referencia_personal,
						  solicitud_prestamo.numero_telefonico_referencia_personal,
						  solicitud_prestamo.apellidos_referencia_familiar,
						  solicitud_prestamo.nombres_referencia_familiar,
						  solicitud_prestamo.parentesco_referencia_familiar,
						  solicitud_prestamo.numero_telefonico_referencia_familiar,
						  entidades.nombre_entidades,
						  solicitud_prestamo.id_provincias_asignacion,
						  solicitud_prestamo.id_cantones_asignacion,
						  solicitud_prestamo.id_parroquias_asignacion,
						  solicitud_prestamo.numero_telefonico_datos_laborales,
						  solicitud_prestamo.interseccion_datos_laborales,
						  solicitud_prestamo.calle_datos_laborales,
						  solicitud_prestamo.cargo_actual_datos_laborales,
						  solicitud_prestamo.sueldo_total_info_economica,
						  solicitud_prestamo.cuota_prestamo_ordinario_info_economica,
						  solicitud_prestamo.arriendos_info_economica,
						  solicitud_prestamo.cuota_prestamo_emergente_info_economica,
						  solicitud_prestamo.honorarios_profesionales_info_economica,
						  solicitud_prestamo.cuota_otros_prestamos_info_economica,
						  solicitud_prestamo.comisiones_info_economica,
						  solicitud_prestamo.cuota_prestamo_iess_info_economica,
						  solicitud_prestamo.horas_suplementarias_info_economica,
						  solicitud_prestamo.arriendos_egre_info_economica,
						  solicitud_prestamo.alimentacion_info_economica,
						  solicitud_prestamo.otros_ingresos_1_info_economica,
						  solicitud_prestamo.valor_ingresos_1_info_economica,
						  solicitud_prestamo.estudios_info_economica,
						  solicitud_prestamo.otros_ingresos_2_info_economica,
						  solicitud_prestamo.valor_ingresos_2_info_economica,
						  solicitud_prestamo.pago_servicios_basicos_info_economica,
						  solicitud_prestamo.otros_ingresos_3_info_economica,
						  solicitud_prestamo.valor_ingresos_3_info_economica,
						  solicitud_prestamo.pago_tarjetas_credito_info_economica,
						  solicitud_prestamo.otros_ingresos_4_info_economica,
						  solicitud_prestamo.valor_ingresos_4_info_economica,
						  solicitud_prestamo.afiliacion_cooperativas_info_economica,
						  solicitud_prestamo.otros_ingresos_5_info_economica,
						  solicitud_prestamo.valor_ingresos_5_info_economica,
						  solicitud_prestamo.ahorro_info_economica,
						  solicitud_prestamo.otros_ingresos_6_info_economica,
						  solicitud_prestamo.valor_ingresos_6_info_economica,
						  solicitud_prestamo.impuesto_renta_info_economica,
						  solicitud_prestamo.otros_ingresos_7_info_economica,
						  solicitud_prestamo.valor_ingresos_7_info_economica,
						  solicitud_prestamo.otros_ingresos_8_info_economica,
						  solicitud_prestamo.valor_ingresos_8_info_economica,
						  solicitud_prestamo.otros_egresos_1_info_economica,
						  solicitud_prestamo.valor_egresos_1_info_economica,
						  solicitud_prestamo.total_ingresos_mensuales,
						  solicitud_prestamo.total_egresos_mensuales,
						  solicitud_prestamo.numero_cedula_conyuge,
						  solicitud_prestamo.apellidos_conyuge,
						  solicitud_prestamo.nombres_conyuge,
						  solicitud_prestamo.id_sexo_conyuge,
						  solicitud_prestamo.fecha_nacimiento_conyuge,
						  solicitud_prestamo.convive_afiliado_conyuge,
						  solicitud_prestamo.numero_telefonico_conyuge,
						  solicitud_prestamo.actividad_economica_conyuge,
						  solicitud_prestamo.fecha_presentacion,
                          solicitud_prestamo.fecha_aprobacion,
						  solicitud_prestamo.id_usuarios_registra,
						  solicitud_prestamo.identificador_consecutivos,
						  solicitud_prestamo.tipo_pago_cuenta_bancaria,
						  tipo_creditos.nombre_tipo_creditos,
						  solicitud_prestamo.id_sucursales,
						  solicitud_prestamo.porcentaje_aportacion,
                          usuarios.nombre_usuarios";
	            $tablas=" public.solicitud_prestamo,
						  public.tipo_creditos,
						  public.provincias,
						  public.sexo,
						  public.estado_civil,
						  public.cantones,
						  public.parroquias,
						  public.entidades,
                          public.usuarios";
	            $where="tipo_creditos.id_tipo_creditos=solicitud_prestamo.id_tipo_creditos AND
					  solicitud_prestamo.id_sexo_datos_personales = sexo.id_sexo AND
					  solicitud_prestamo.id_estado_civil_datos_personales = estado_civil.id_estado_civil AND
					  solicitud_prestamo.id_provincias_vivienda = provincias.id_provincias AND
					  solicitud_prestamo.id_cantones_vivienda = cantones.id_cantones AND
					  solicitud_prestamo.id_parroquias_vivienda = parroquias.id_parroquias AND
                      usuarios.id_usuarios = solicitud_prestamo.id_usuarios_oficial_credito_aprueba AND
					  entidades.id_entidades = solicitud_prestamo.id_entidades AND solicitud_prestamo.id_solicitud_prestamo='$id_solicitud_prestamo'";
	            $id="solicitud_prestamo.id_solicitud_prestamo";
	            
	            $resultSoli=$db->getCondicionesDesc($columnas, $tablas, $where, $id);
	            
	            
	            
	            if(!empty($resultSoli)){
	                
	                // DATOS DEL PRESTAMO
	                $_id_solicitud_prestamo       					=$resultSoli[0]->id_solicitud_prestamo;
	                $_tipo_participe_datos_prestamo       			=$resultSoli[0]->tipo_participe_datos_prestamo;
	                $_monto_datos_prestamo       					=$resultSoli[0]->monto_datos_prestamo;
	                $_plazo_datos_prestamo       					=$resultSoli[0]->plazo_datos_prestamo;
	                $_destino_dinero_datos_prestamo       			=$resultSoli[0]->destino_dinero_datos_prestamo;
	                
	                
	                $_nombre_tipo_creditos                          =$resultSoli[0]->nombre_tipo_creditos;
	                $_tipo_pago_cuenta_bancaria                     =$resultSoli[0]->tipo_pago_cuenta_bancaria;
	                $_nombre_bancos_datos_prestamo       			=$resultSoli[0]->nombre_banco_cuenta_bancaria;
	                
	                $_abreviaciones_bancos="";
	                $resultBank= $db->getBy("bancos","nombre_bancos='$_nombre_bancos_datos_prestamo'");
	                if(!empty($resultBank)){
	                    $_abreviaciones_bancos       			=$resultBank[0]->abreviaciones_bancos;
	                }else{
	                    $_abreviaciones_bancos="";
	                }
	                
	                
	                
	                
	                
	                $_tipo_cuenta_cuenta_bancaria       			=$resultSoli[0]->tipo_cuenta_cuenta_bancaria;
	                $_numero_cuenta_cuenta_bancaria       			=$resultSoli[0]->numero_cuenta_cuenta_bancaria;
	                $_numero_cedula_datos_personales       			=$resultSoli[0]->numero_cedula_datos_personales;
	                
	                // DATOS PERSONALES
	                $_apellidos_solicitante_datos_personales        =$resultSoli[0]->apellidos_solicitante_datos_personales;
	                $_nombres_solicitante_datos_personales       	=$resultSoli[0]->nombres_solicitante_datos_personales;
	                $_correo_solicitante_datos_personales       	=$resultSoli[0]->correo_solicitante_datos_personales;
	                $_nombre_sexo_solicitante_datos_personales      =$resultSoli[0]->nombre_sexo;
	                $_fecha_nacimiento_datos_personales       		=$resultSoli[0]->fecha_nacimiento_datos_personales;
	                $_nombre_estado_civil_solicitante_datos_personales  =$resultSoli[0]->nombre_estado_civil;
	                $_separacion_bienes_datos_personales       		=$resultSoli[0]->separacion_bienes_datos_personales;
	                $_cargas_familiares_datos_personales       		=$resultSoli[0]->cargas_familiares_datos_personales;
	                $_numero_hijos_datos_personales       			=$resultSoli[0]->numero_hijos_datos_personales;
	                $_nivel_educativo_datos_personales       		=$resultSoli[0]->nivel_educativo_datos_personales;
	                
	                //DIRECCIÓN EXACTA DEL DOMICILIO
	                $_nombre_provincias_vivienda       				=$resultSoli[0]->nombre_provincias;
	                $_nombre_cantones_vivienda       				=$resultSoli[0]->nombre_cantones;
	                $_nombre_parroquias_vivienda       				=$resultSoli[0]->nombre_parroquias;
	                $_barrio_sector_vivienda       					=$resultSoli[0]->barrio_sector_vivienda;
	                $_ciudadela_conjunto_etapa_manzana_vivienda     =$resultSoli[0]->ciudadela_conjunto_etapa_manzana_vivienda;
	                $_calle_vivienda       							=$resultSoli[0]->calle_vivienda;
	                $_numero_calle_vivienda       					=$resultSoli[0]->numero_calle_vivienda;
	                $_intersecion_vivienda       					=$resultSoli[0]->intersecion_vivienda;
	                $_tipo_vivienda       							=$resultSoli[0]->tipo_vivienda;
	                $_vivienda_hipotecada_vivienda       			=$resultSoli[0]->vivienda_hipotecada_vivienda;
	                $_tiempo_residencia_vivienda       				=$resultSoli[0]->tiempo_residencia_vivienda;
	                $_nombre_propietario_vivienda       			=$resultSoli[0]->nombre_propietario_vivienda;
	                $_celular_propietario_vivienda       			=$resultSoli[0]->celular_propietario_vivienda;
	                $_referencia_direccion_domicilio_vivienda       =$resultSoli[0]->referencia_direccion_domicilio_vivienda;
	                $_numero_casa_solicitante       				=$resultSoli[0]->numero_casa_solicitante;
	                $_numero_celular_solicitante       				=$resultSoli[0]->numero_celular_solicitante;
	                $_numero_trabajo_solicitante       				=$resultSoli[0]->numero_trabajo_solicitante;
	                $_extension_solicitante       					=$resultSoli[0]->extension_solicitante;
	                $_mode_solicitante       						=$resultSoli[0]->mode_solicitante;
	                $_apellidos_referencia_personal       			=$resultSoli[0]->apellidos_referencia_personal;
	                $_nombres_referencia_personal       			=$resultSoli[0]->nombres_referencia_personal;
	                $_relacion_referencia_personal       			=$resultSoli[0]->relacion_referencia_personal;
	                $_numero_telefonico_referencia_personal       	=$resultSoli[0]->numero_telefonico_referencia_personal;
	                $_apellidos_referencia_familiar       			=$resultSoli[0]->apellidos_referencia_familiar;
	                $_nombres_referencia_familiar       			=$resultSoli[0]->nombres_referencia_familiar;
	                $_parentesco_referencia_familiar       			=$resultSoli[0]->parentesco_referencia_familiar;
	                $_numero_telefonico_referencia_familiar         =$resultSoli[0]->numero_telefonico_referencia_familiar;
	                
	                // DATOS LABORALES
	                $_nombre_entidades       						=$resultSoli[0]->nombre_entidades;
	                $_id_provincias_asignacion       				=$resultSoli[0]->id_provincias_asignacion;
	         
	                $resultProvincias= $db->getBy("provincias","id_provincias='$_id_provincias_asignacion'");
	                $_nombre_provincias_asignacion       				=$resultProvincias[0]->nombre_provincias;
	                
	                
	                $_id_cantones_asignacion       					=$resultSoli[0]->id_cantones_asignacion;
	                $resultCantones= $db->getBy("cantones","id_cantones='$_id_cantones_asignacion'");
	                $_nombre_cantones_asignacion       				=$resultCantones[0]->nombre_cantones;
	                
	                
	                $_id_parroquias_asignacion       				=$resultSoli[0]->id_parroquias_asignacion;
	                $resultParroquias= $db->getBy("parroquias","id_parroquias='$_id_parroquias_asignacion'");
	                $_nombre_parroquias_asignacion       				=$resultParroquias[0]->nombre_parroquias;
	                
	                
	                $_numero_telefonico_datos_laborales       		=$resultSoli[0]->numero_telefonico_datos_laborales;
	                $_interseccion_datos_laborales       			=$resultSoli[0]->interseccion_datos_laborales;
	                $_calle_datos_laborales       					=$resultSoli[0]->calle_datos_laborales;
	                $_cargo_actual_datos_laborales       			=$resultSoli[0]->cargo_actual_datos_laborales;
	                
	                // INFORMACIÓN ECONONÓMICA
	                $_sueldo_total_info_economica       			=$resultSoli[0]->sueldo_total_info_economica;
	                $_cuota_prestamo_ordinario_info_economica       =$resultSoli[0]->cuota_prestamo_ordinario_info_economica;
	                $_arriendos_info_economica       				=$resultSoli[0]->arriendos_info_economica;
	                $_cuota_prestamo_emergente_info_economica       =$resultSoli[0]->cuota_prestamo_emergente_info_economica;
	                $_honorarios_profesionales_info_economica       =$resultSoli[0]->honorarios_profesionales_info_economica;
	                $_cuota_otros_prestamos_info_economica       	=$resultSoli[0]->cuota_otros_prestamos_info_economica;
	                $_comisiones_info_economica       				=$resultSoli[0]->comisiones_info_economica;
	                $_cuota_prestamo_iess_info_economica       		=$resultSoli[0]->cuota_prestamo_iess_info_economica;
	                $_horas_suplementarias_info_economica       	=$resultSoli[0]->horas_suplementarias_info_economica;
	                $_arriendos_egre_info_economica       			=$resultSoli[0]->arriendos_egre_info_economica;
	                $_alimentacion_info_economica       			=$resultSoli[0]->alimentacion_info_economica;
	                $_otros_ingresos_1_info_economica       		=$resultSoli[0]->otros_ingresos_1_info_economica;
	                $_valor_ingresos_1_info_economica       		=$resultSoli[0]->valor_ingresos_1_info_economica;
	                $_estudios_info_economica       				=$resultSoli[0]->estudios_info_economica;
	                $_otros_ingresos_2_info_economica       		=$resultSoli[0]->otros_ingresos_2_info_economica;
	                $_valor_ingresos_2_info_economica       		=$resultSoli[0]->valor_ingresos_2_info_economica;
	                $_pago_servicios_basicos_info_economica       	=$resultSoli[0]->pago_servicios_basicos_info_economica;
	                $_otros_ingresos_3_info_economica       		=$resultSoli[0]->otros_ingresos_3_info_economica;
	                $_valor_ingresos_3_info_economica       		=$resultSoli[0]->valor_ingresos_3_info_economica;
	                $_pago_tarjetas_credito_info_economica       	=$resultSoli[0]->pago_tarjetas_credito_info_economica;
	                $_otros_ingresos_4_info_economica       		=$resultSoli[0]->otros_ingresos_4_info_economica;
	                $_valor_ingresos_4_info_economica       		=$resultSoli[0]->valor_ingresos_4_info_economica;
	                $_afiliacion_cooperativas_info_economica        =$resultSoli[0]->afiliacion_cooperativas_info_economica;
	                $_otros_ingresos_5_info_economica       		=$resultSoli[0]->otros_ingresos_5_info_economica;
	                $_valor_ingresos_5_info_economica       		=$resultSoli[0]->valor_ingresos_5_info_economica;
	                $_ahorro_info_economica       					=$resultSoli[0]->ahorro_info_economica;
	                $_otros_ingresos_6_info_economica       		=$resultSoli[0]->otros_ingresos_6_info_economica;
	                $_valor_ingresos_6_info_economica       		=$resultSoli[0]->valor_ingresos_6_info_economica;
	                $_impuesto_renta_info_economica       			=$resultSoli[0]->impuesto_renta_info_economica;
	                $_otros_ingresos_7_info_economica       		=$resultSoli[0]->otros_ingresos_7_info_economica;
	                $_valor_ingresos_7_info_economica       		=$resultSoli[0]->valor_ingresos_7_info_economica;
	                $_otros_ingresos_8_info_economica       		=$resultSoli[0]->otros_ingresos_8_info_economica;
	                $_valor_ingresos_8_info_economica       		=$resultSoli[0]->valor_ingresos_8_info_economica;
	                $_otros_egresos_1_info_economica       			=$resultSoli[0]->otros_egresos_1_info_economica;
	                $_valor_egresos_1_info_economica       			=$resultSoli[0]->valor_egresos_1_info_economica;
	                $_total_ingresos_mensuales       				=$resultSoli[0]->total_ingresos_mensuales;
	                $_total_egresos_mensuales       				=$resultSoli[0]->total_egresos_mensuales;
	                
	                // DATOS DEL CONYUGE
	                $_numero_cedula_conyuge       					=$resultSoli[0]->numero_cedula_conyuge;
	                $_apellidos_conyuge       						=$resultSoli[0]->apellidos_conyuge;
	                $_nombres_conyuge       						=$resultSoli[0]->nombres_conyuge;
	                
	                $_id_sexo_conyuge       						=$resultSoli[0]->id_sexo_conyuge;
	                
	                if($_id_sexo_conyuge>0){
	                    $resultSexo = $db->getBy("sexo","id_sexo='$_id_sexo_conyuge'");
	                    $_nombre_sexo_conyuge       				    =$resultSexo[0]->nombre_sexo;
	                }else{
	                    $_nombre_sexo_conyuge="";
	                }
	                
	                $_fecha_nacimiento_conyuge       				=$resultSoli[0]->fecha_nacimiento_conyuge;
	                $_convive_afiliado_conyuge       				=$resultSoli[0]->convive_afiliado_conyuge;
	                $_numero_telefonico_conyuge       				=$resultSoli[0]->numero_telefonico_conyuge;
	                $_actividad_economica_conyuge       			=$resultSoli[0]->actividad_economica_conyuge;
	                
	                
	                // FECHA DE PRESENTACION
	                $_fecha_presentacion       						=$resultSoli[0]->fecha_presentacion;
	                $_fecha_aprobacion       						=$resultSoli[0]->fecha_aprobacion;
	                
	                
	                
	                /*
	                 if(!empty($_fecha_aprobacion)){
	                 
	                 
	                 }else{
	                 
	                 
	                 date_default_timezone_set('America/Guayaquil');
	                 $_fecha_aprobacion = date('Y-m-d');
	                 
	                 }
	                 */
	                
	                
	                // DATOS USUARIO
	                $_id_usuarios_registra       					=$resultSoli[0]->id_usuarios_registra;
	                $_identificador_consecutivos     				=$resultSoli[0]->identificador_consecutivos;
	                
	                $creado                                          ="a los <b>".date('d',strtotime($_fecha_aprobacion))."</b> días del mes de <b>".$meses[date('n',strtotime($_fecha_aprobacion))-1]. "</b> del <b>".date('Y',strtotime($_fecha_aprobacion))."</b>";
	                
	                $_id_sucursales                                 =$resultSoli[0]->id_sucursales;
	                
	                if($_id_sucursales>0){
	                    $resultSucursales = $db->getBy("sucursales","id_sucursales='$_id_sucursales'");
	                    $_nombre_sucursales       				    =$resultSucursales[0]->nombre_sucursales;
	                }else{
	                    $_nombre_sucursales="";
	                }
	                
	                $_porcentaje_aportacion                     =$resultSoli[0]->porcentaje_aportacion;
	                
	                $_nombre_oficial_credito                     =$resultSoli[0]->nombre_usuarios;
	                
	                
	                
	                
	                
	                /*
	                 // PARA GENERAR CODIGO DE BARRAS
	                 include dirname(__FILE__).'\barcode.php';
	                 
	                 
	                 $ubicacion =   dirname(__FILE__).'\..\barcode_participes'.'\\'.$_numero_cedula_datos_personales.'.png';
	                 barcode($ubicacion, $_numero_cedula_datos_personales, 20, 'horizontal', 'code128', FALSE);
	                 */
	                
	                
	                
	                
	                // PARA GENERAR QR
	                require dirname(__FILE__)."\phpqrcode\qrlib.php";
	                
	                $ubicacion = dirname(__FILE__).'\..\barcode_participes\\';
	                
	                //Si no existe la carpeta la creamos
	                if (!file_exists($ubicacion))
	                    mkdir($ubicacion);
	                    
	                    $i++;
	                    $filename = $ubicacion.$_numero_cedula_datos_personales.'.png';
	                    
	                    //Parametros de Condiguracion
	                    
	                    $tamaño = 5; //Tama�o de Pixel
	                    $level = 'L'; //Precisi�n Baja
	                    $framSize = 3; //Tama�o en blanco
	                    $contenido = $_numero_cedula_datos_personales; //Texto
	                    
	                    //Enviamos los parametros a la Funci�n para generar c�digo QR
	                    QRcode::png($contenido, $filename, $level, $tamaño, $framSize);
	                    
	                    $qr_participes = '<img src="'.$filename.'">';
	                    
	                    
	                    
	                    
	                    $html.='<table style="width: 100%;"  border=1 cellspacing=0.0001 >';
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 18px;"><b>FONDO COMPLEMENTARIO PREVISIONAL CERRADO DE CESANTÍA DE SERVIDORES Y TRABAJADORES PÚBLICOS DE FUERZAS ARMADAS CAPREMCI<br>CRÉDITO '.$_nombre_tipo_creditos.'<br>SOLICITUD DE PRESTAMO N.-<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:right; font-size: 13px;"><b>Fecha de Presentación: <b> '.$_fecha_aprobacion.'</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 16px;"><b>DATOS DEL PRESTAMO<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    if($_tipo_participe_datos_prestamo=='Deudor'){
	                        $html.='<td colspan="6" style="text-align:center; font-size: 13px;"><u><b>DEUDOR</b></u></td>';
	                        $html.='<td colspan="6" style="text-align:center; font-size: 13px;"><b>GARANTE</b></td>';
	                    }else{
	                        $html.='<td colspan="6" style="text-align:center; font-size: 13px;"><b>DEUDOR</b></td>';
	                        $html.='<td colspan="6" style="text-align:center; font-size: 13px;"><u><b>GARANTE</b></u></td>';
	                    }
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">Monto en dólares</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">Plazo en meses</th>';
	                    $html.='<th colspan="10" style="text-align:center; font-size: 13px;">Destino del dinero</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px;">'.$_monto_datos_prestamo.'</td>';
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px;">'.$_plazo_datos_prestamo.'</td>';
	                    
	                    if($_destino_dinero_datos_prestamo=='Estudios'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_destino_dinero_datos_prestamo.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vivienda</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vehículo</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Consumo</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }elseif($_destino_dinero_datos_prestamo=='Vivienda'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Estudios</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_destino_dinero_datos_prestamo.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vehículo</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Consumo</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_destino_dinero_datos_prestamo=='Vehículo'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Estudios</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vivienda</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_destino_dinero_datos_prestamo.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Consumo</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_destino_dinero_datos_prestamo=='Consumo'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Estudios</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vivienda</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vehículo</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_destino_dinero_datos_prestamo.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_destino_dinero_datos_prestamo=='Otro'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Estudios</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vivienda</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vehículo</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Consumo</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_destino_dinero_datos_prestamo.'</u></td>';
	                        
	                    }
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Para depósito en mi cuenta del</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cuenta Actualizada Ahorros #</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cuenta Actualizada Corriente #</th>';
	                    $html.='</tr>';
	                    
	                    
	                    if($_tipo_pago_cuenta_bancaria=='Depósito'){
	                        
	                        
	                        $html.='<tr>';
	                        if($_tipo_cuenta_cuenta_bancaria=='Ahorros'){
	                            
	                            $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_nombre_bancos_datos_prestamo.'</td>';
	                            $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_numero_cuenta_cuenta_bancaria.'</td>';
	                            $html.='<td colspan="4" style="text-align:center; font-size: 13px;">N/A</td>';
	                            
	                        }else{
	                            
	                            $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_nombre_bancos_datos_prestamo.'</td>';
	                            $html.='<td colspan="4" style="text-align:center; font-size: 13px;">N/A</td>';
	                            $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_numero_cuenta_cuenta_bancaria.'</td>';
	                            
	                        }
	                        $html.='</tr>';
	                        
	                        $html.='<tr>';
	                        $html.='<td colspan="12" style="text-align:left; font-size: 13px;"><b>Retira Cheque:</b> <u>No</u></td>';
	                        $html.='</tr>';
	                        
	                    }else{
	                        
	                        $html.='<tr>';
	                        if($_tipo_cuenta_cuenta_bancaria=='Ahorros'){
	                            
	                            $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_nombre_bancos_datos_prestamo.'</td>';
	                            $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_numero_cuenta_cuenta_bancaria.'</td>';
	                            $html.='<td colspan="4" style="text-align:center; font-size: 13px;">N/A</td>';
	                            
	                        }else{
	                            
	                            $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_nombre_bancos_datos_prestamo.'</td>';
	                            $html.='<td colspan="4" style="text-align:center; font-size: 13px;">N/A</td>';
	                            $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_numero_cuenta_cuenta_bancaria.'</td>';
	                            
	                        }
	                        $html.='</tr>';
	                        
	                        
	                        $html.='<tr>';
	                        $html.='<td colspan="12" style="text-align:left; font-size: 13px;"><b>Retira Cheque:</b> <u>Si</u></td>';
	                        $html.='</tr>';
	                    }
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 16px;"><b>DATOS PERSONALES<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="9" style="text-align:left; font-size: 13px;">Apellidos y Nombres Completos</th>';
	                    $html.='<th colspan="3" style="text-align:left; font-size: 13px;">No. de Cédula</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="9" style="text-align:left; font-size: 13px;">'.$_apellidos_solicitante_datos_personales.' '.$_nombres_solicitante_datos_personales.'</td>';
	                    $html.='<td colspan="3" style="text-align:left; font-size: 13px;">'.$_numero_cedula_datos_personales.'</td>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Dirección Electrónica</th>';
	                    $html.='<th colspan="10" style="text-align:center; font-size: 13px;">Nivel Educativo</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="2" style="text-align:left; font-size: 13px;">'.$_correo_solicitante_datos_personales.'</td>';
	                    
	                    if($_nivel_educativo_datos_personales=='Primario'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nivel_educativo_datos_personales.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    elseif($_nivel_educativo_datos_personales=='Secundario'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nivel_educativo_datos_personales.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    elseif($_nivel_educativo_datos_personales=='Técnico'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nivel_educativo_datos_personales.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    
	                    elseif($_nivel_educativo_datos_personales=='Universitario'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nivel_educativo_datos_personales.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    elseif($_nivel_educativo_datos_personales=='Postgrado'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nivel_educativo_datos_personales.'</u></td>';
	                        
	                    }
	                    
	                    
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">Género</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">Fecha Nacimiento</th>';
	                    $html.='<th colspan="10" style="text-align:center; font-size: 13px;">Estado Civil</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="1" style="text-align:left; font-size: 13px;">'.$_nombre_sexo_solicitante_datos_personales.'</td>';
	                    $html.='<td colspan="1" style="text-align:left; font-size: 13px;">'.$_fecha_nacimiento_datos_personales.'</td>';
	                    
	                    if($_nombre_estado_civil_solicitante_datos_personales=='Soltero'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nombre_estado_civil_solicitante_datos_personales.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Union Libre</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        
	                    }elseif($_nombre_estado_civil_solicitante_datos_personales=='Casado'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nombre_estado_civil_solicitante_datos_personales.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Union Libre</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        
	                    }elseif($_nombre_estado_civil_solicitante_datos_personales=='Divorciado'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nombre_estado_civil_solicitante_datos_personales.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Union Libre</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        
	                    }elseif($_nombre_estado_civil_solicitante_datos_personales=='Union Libre'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nombre_estado_civil_solicitante_datos_personales.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        
	                    }elseif($_nombre_estado_civil_solicitante_datos_personales=='Viudo'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Union Libre</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nombre_estado_civil_solicitante_datos_personales.'</u></td>';
	                    }
	                    
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Separación de bienes</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cargas familiares</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Número de cargas familiares</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    if($_separacion_bienes_datos_personales=='Si'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_separacion_bienes_datos_personales.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">No</td>';
	                        
	                    }else{
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Si</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_separacion_bienes_datos_personales.'</u></td>';
	                    }
	                    
	                    if($_cargas_familiares_datos_personales=='Si'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_cargas_familiares_datos_personales.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">No</td>';
	                        
	                    }else{
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Si</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_cargas_familiares_datos_personales.'</u></td>';
	                    }
	                    
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_numero_hijos_datos_personales.'</td>';
	                    $html.='</tr>';
	                    
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 16px;"><b>DIRECCIÓN EXACTA DEL DOMICILIO<b></th>';
	                    $html.='</tr>';
	                    
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Provincia</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cantón</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Parroquia</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_nombre_provincias_vivienda.'</td>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_nombre_cantones_vivienda.'</td>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_nombre_parroquias_vivienda.'</td>';
	                    $html.='</tr>';
	                    
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:left; font-size: 13px;">Barrio y/o sector</th>';
	                    $html.='<th colspan="6" style="text-align:left; font-size: 13px;">Ciudadela y/o conjunto / Etapa / Manzana</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    
	                    if($_barrio_sector_vivienda!=""){
	                        $html.='<td colspan="6" style="text-align:justify; font-size: 13px;">'.$_barrio_sector_vivienda.'</td>';
	                        
	                    }else{
	                        $html.='<td colspan="6" style="text-align:justify; font-size: 13px;">N/A</td>';
	                        
	                    }
	                    
	                    
	                    if($_ciudadela_conjunto_etapa_manzana_vivienda!=""){
	                        $html.='<td colspan="6" style="text-align:justify; font-size: 13px;">'.$_ciudadela_conjunto_etapa_manzana_vivienda.'</td>';
	                        
	                        
	                    }else{
	                        $html.='<td colspan="6" style="text-align:justify; font-size: 13px;">N/A</td>';
	                        
	                        
	                    }
	                    
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Calle</th>';
	                    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Número</th>';
	                    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Intersección</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    
	                    if($_calle_vivienda!=""){
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_calle_vivienda.'</td>';
	                    }else{
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">N/A</td>';
	                    }
	                    
	                    if($_numero_calle_vivienda!=""){
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_numero_calle_vivienda.'</td>';
	                    }else{
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">N/A</td>';
	                    }
	                    
	                    if($_intersecion_vivienda!=""){
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_intersecion_vivienda.'</td>';
	                    }else{
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">N/A</td>';
	                    }
	                    
	                    
	                    
	                    
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="8" style="text-align:center; font-size: 13px;">Vivienda</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Su vivienda está hipotecada</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    if($_tipo_vivienda=='Propia'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_tipo_vivienda.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                        
	                    }elseif($_tipo_vivienda=='Arrendada'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_tipo_vivienda.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                        
	                    }elseif($_tipo_vivienda=='Anticresis'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_tipo_vivienda.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                        
	                    }
	                    elseif($_tipo_vivienda=='Vive con Familiares'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_tipo_vivienda.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                        
	                    }elseif($_tipo_vivienda=='Otra'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_tipo_vivienda.'</u></td>';
	                    }
	                    
	                    
	                    if($_vivienda_hipotecada_vivienda=='Si'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_vivienda_hipotecada_vivienda.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">No</td>';
	                        
	                    }else{
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Si</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_vivienda_hipotecada_vivienda.'</u></td>';
	                    }
	                    
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Tiempo de residencia</th>';
	                    $html.='<th colspan="10" style="text-align:left; font-size: 13px;">Si no tiene vivienda propia escriba el nombre y número telefónico del propietario</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_tiempo_residencia_vivienda.'</td>';
	                    
	                    if($_nombre_propietario_vivienda!=""){
	                        $html.='<td colspan="8" style="text-align:justify; font-size: 13px;">'.$_nombre_propietario_vivienda.'</td>';
	                        
	                    }else{
	                        $html.='<td colspan="8" style="text-align:justify; font-size: 13px;">N/A</td>';
	                        
	                    }
	                    
	                    
	                    if($_celular_propietario_vivienda!=""){
	                        $html.='<td colspan="2" style="text-align:justify; font-size: 13px;">'.$_celular_propietario_vivienda.'</td>';
	                        
	                    }else{
	                        $html.='<td colspan="2" style="text-align:justify; font-size: 13px;">N/A</td>';
	                        
	                    }
	                    
	                    
	                    
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:left; font-size: 13px;">Referencia de la dirección del domicilio</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="12" style="text-align:left; font-size: 13px;">'.$_referencia_direccion_domicilio_vivienda.'</td>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="2" style="text-align:left; font-size: 13px;">Casa</th>';
	                    $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Celular</th>';
	                    $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Trabajo</th>';
	                    $html.='<th colspan="2" style="text-align:left; font-size: 13px;">Ext</th>';
	                    $html.='<th colspan="2" style="text-align:left; font-size: 13px;">Mode</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="2" style="text-align:left; font-size: 13px;">'.$_numero_casa_solicitante.'</td>';
	                    $html.='<td colspan="3" style="text-align:left; font-size: 13px;">'.$_numero_celular_solicitante.'</td>';
	                    
	                    
	                    if($_numero_trabajo_solicitante!=""){
	                        $html.='<td colspan="3" style="text-align:left; font-size: 13px;">'.$_numero_trabajo_solicitante.'</td>';
	                    }else{
	                        $html.='<td colspan="3" style="text-align:left; font-size: 13px;">N/A</td>';
	                    }
	                    
	                    if($_extension_solicitante!=""){
	                        $html.='<td colspan="2" style="text-align:left; font-size: 13px;">'.$_extension_solicitante.'</td>';
	                    }else{
	                        $html.='<td colspan="2" style="text-align:left; font-size: 13px;">N/A</td>';
	                    }
	                    
	                    if($_mode_solicitante!=""){
	                        $html.='<td colspan="2" style="text-align:left; font-size: 13px;">'.$_mode_solicitante.'</td>';
	                    }else{
	                        $html.='<td colspan="2" style="text-align:left; font-size: 13px;">N/A</td>';
	                    }
	                    
	                    
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:left; font-size: 13px;">Referencia Familiar que no viva con Ud</th>';
	                    $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Parentesco</th>';
	                    $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Número Telefónico</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:left; font-size: 13px;">'.$_apellidos_referencia_familiar.' '.$_nombres_referencia_familiar.'</td>';
	                    $html.='<td colspan="3" style="text-align:left; font-size: 13px;">'.$_parentesco_referencia_familiar.'</td>';
	                    $html.='<td colspan="3" style="text-align:left; font-size: 13px;">'.$_numero_telefonico_referencia_familiar.'</td>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:left; font-size: 13px;">Referencia Personal</th>';
	                    $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Relación</th>';
	                    $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Número Telefónico</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:left; font-size: 13px;">'.$_apellidos_referencia_personal.' '.$_nombres_referencia_personal.'</td>';
	                    $html.='<td colspan="3" style="text-align:left; font-size: 13px;">'.$_relacion_referencia_personal.'</td>';
	                    $html.='<td colspan="3" style="text-align:left; font-size: 13px;">'.$_numero_telefonico_referencia_personal.'</td>';
	                    $html.='</tr>';
	                    
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 16px;"><b>DATOS LABORALES<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Institución o Empresa</th>';
	                    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Provincia</th>';
	                    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Cantón</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_nombre_entidades.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_nombre_provincias_asignacion.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_nombre_cantones_asignacion.'</td>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Parroquia</th>';
	                    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Número</th>';
	                    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Intersección</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_nombre_parroquias_asignacion.'</td>';
	                    if($_numero_telefonico_datos_laborales!=""){
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_numero_telefonico_datos_laborales.'</td>';
	                        
	                    }else{
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">N/A</td>';
	                        
	                    }
	                    
	                    if($_interseccion_datos_laborales!=""){
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_interseccion_datos_laborales.'</td>';
	                        
	                    }else{
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">N/A</td>';
	                    }
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:left; font-size: 13px;">Calle</th>';
	                    $html.='<th colspan="6" style="text-align:left; font-size: 13px;">Cargo Actual</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    if($_calle_datos_laborales!=""){
	                        $html.='<td colspan="6" style="text-align:left; font-size: 13px;">'.$_calle_datos_laborales.'</td>';
	                        
	                    }else{
	                        $html.='<td colspan="6" style="text-align:left; font-size: 13px;">N/A</td>';
	                        
	                    }
	                    
	                    if($_cargo_actual_datos_laborales!=""){
	                        $html.='<td colspan="6" style="text-align:left; font-size: 13px;">'.$_cargo_actual_datos_laborales.'</td>';
	                        
	                    }else{
	                        $html.='<td colspan="6" style="text-align:left; font-size: 13px;">N/A</td>';
	                    }
	                    
	                    $html.='</tr>';
	                    
	                    
	                    //$html.='</table>';
	                    
	                    //$html.='<table style="page-break-after:always; width: 100%;"  border=1 cellspacing=0.0001 >';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 16px;"><b>INFORMACIÓN ECONÓMICA<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Ingresos Mensuales</th>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Valor en dólares</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Gastos Mensuales</th>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Valor en dólares</th>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Sueldo Total</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_sueldo_total_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Cuota del Préstamo Ordinario CAPREMCI</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_cuota_prestamo_ordinario_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Arriendos</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_arriendos_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Cuota del Préstamo Emergente CAPREMCI</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_cuota_prestamo_emergente_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Honorarios Profesionales</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_honorarios_profesionales_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Cuotas de Otros Préstamos</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_cuota_otros_prestamos_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Comisiones</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_comisiones_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Cuotas de Prestamos con el IESS</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_cuota_prestamo_iess_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Horas Suplementarias</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_horas_suplementarias_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Arriendo</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_arriendos_egre_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"><b>OTROS INGRESOS (detalle)</b></td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Alimentación</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_alimentacion_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_otros_ingresos_1_info_economica.'</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_valor_ingresos_1_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Estudios</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_estudios_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_otros_ingresos_2_info_economica.'</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_valor_ingresos_2_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Pago Servicios Básicos</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_pago_servicios_basicos_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_otros_ingresos_3_info_economica.'</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_valor_ingresos_3_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Pago Tarjetas de Crédito</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_pago_tarjetas_credito_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_otros_ingresos_4_info_economica.'</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_valor_ingresos_4_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Afiliación a Cooperativas</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_afiliacion_cooperativas_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_otros_ingresos_5_info_economica.'</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_valor_ingresos_5_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Ahorro</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_ahorro_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_otros_ingresos_6_info_economica.'</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_valor_ingresos_6_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">Impuesto a la Renta</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_impuesto_renta_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_otros_ingresos_7_info_economica.'</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_valor_ingresos_7_info_economica.'</td>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"><b>OTROS GASTOS (detalle)</b></td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_otros_ingresos_8_info_economica.'</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_valor_ingresos_8_info_economica.'</td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_otros_egresos_1_info_economica.'</td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_valor_egresos_1_info_economica.'</td>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;"><b>Total de Ingresos Mensuales</b></td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><b>'.$_total_ingresos_mensuales.'</b></td>';
	                    $html.='<td colspan="4" style="text-align:left; font-size: 13px;"><b>Total de Gastos Mensuales</b></td>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><b>'.$_total_egresos_mensuales.'</b></td>';
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 16px;"><b>DATOS DEL CÓNYUGE O PAREJA<b></th>';
	                    $html.='</tr>';
	                    
	                    if($_apellidos_conyuge!="" && $_nombres_conyuge!=""){
	                        $html.='<tr>';
	                        $html.='<th colspan="8" style="text-align:left; font-size: 13px;">Apellidos y Nombres Completos</th>';
	                        $html.='<th colspan="4" style="text-align:left; font-size: 13px;">No. de Cédula</th>';
	                        $html.='</tr>';
	                        
	                        $html.='<tr>';
	                        $html.='<td colspan="8" style="text-align:left; font-size: 13px;">'.$_apellidos_conyuge.' '.$_nombres_conyuge.'</td>';
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_numero_cedula_conyuge.'</td>';
	                        $html.='</tr>';
	                        
	                        $html.='<tr>';
	                        $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Género</th>';
	                        $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Fecha Nacimiento</th>';
	                        $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Vive en la residencia del afiliado</th>';
	                        $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Número telefónico</th>';
	                        $html.='</tr>';
	                        
	                        
	                        $html.='<tr>';
	                        $html.='<td colspan="3" style="text-align:left; font-size: 13px;">'.$_nombre_sexo_conyuge.'</td>';
	                        $html.='<td colspan="3" style="text-align:left; font-size: 13px;">'.$_fecha_nacimiento_conyuge.'</td>';
	                        $html.='<td colspan="3" style="text-align:center; font-size: 13px;">'.$_convive_afiliado_conyuge.'</td>';
	                        $html.='<td colspan="3" style="text-align:left; font-size: 13px;">'.$_numero_telefonico_conyuge.'</td>';
	                        $html.='</tr>';
	                        
	                    }else{
	                        
	                        $html.='<tr>';
	                        $html.='<th colspan="8" style="text-align:left; font-size: 13px;">Apellidos y Nombres Completos</th>';
	                        $html.='<th colspan="4" style="text-align:left; font-size: 13px;">No. de Cédula</th>';
	                        $html.='</tr>';
	                        
	                        $html.='<tr>';
	                        $html.='<td colspan="8" style="text-align:left; font-size: 13px;">N/A</td>';
	                        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">N/A</td>';
	                        $html.='</tr>';
	                        
	                        $html.='<tr>';
	                        $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Género</th>';
	                        $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Fecha Nacimiento</th>';
	                        $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Vive en la residencia del afiliado</th>';
	                        $html.='<th colspan="3" style="text-align:left; font-size: 13px;">Número telefónico</th>';
	                        $html.='</tr>';
	                        
	                        
	                        $html.='<tr>';
	                        $html.='<td colspan="3" style="text-align:left; font-size: 13px;">N/A</td>';
	                        $html.='<td colspan="3" style="text-align:left; font-size: 13px;">N/A</td>';
	                        $html.='<td colspan="3" style="text-align:center; font-size: 13px;">N/A</td>';
	                        $html.='<td colspan="3" style="text-align:left; font-size: 13px;">N/A</td>';
	                        $html.='</tr>';
	                    }
	                    
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 14px;"><b>Actividad económica del cónyuge<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    if($_actividad_economica_conyuge=='Ama de Casa'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_actividad_economica_conyuge.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado Público</td>';
	                        $html.='<td colspan="4" style="text-align:center; font-size: 13px;">Libre Ejercicio Profesional</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Independiente</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Jubilado</td>';
	                    }elseif ($_actividad_economica_conyuge=='Empleado Público'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Ama de Casa</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_actividad_economica_conyuge.'</u></td>';
	                        $html.='<td colspan="4" style="text-align:center; font-size: 13px;">Libre Ejercicio Profesional</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Independiente</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Jubilado</td>';
	                        
	                    }elseif ($_actividad_economica_conyuge=='Libre Ejercicio Profesional'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Ama de Casa</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado Público</td>';
	                        $html.='<td colspan="4" style="text-align:center; font-size: 13px;"><u>'.$_actividad_economica_conyuge.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Independiente</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Jubilado</td>';
	                        
	                    }elseif ($_actividad_economica_conyuge=='Independiente'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Ama de Casa</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado Público</td>';
	                        $html.='<td colspan="4" style="text-align:center; font-size: 13px;">Libre Ejercicio Profesional</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_actividad_economica_conyuge.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Jubilado</td>';
	                        
	                    }elseif ($_actividad_economica_conyuge=='Jubilado'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Ama de Casa</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado Público</td>';
	                        $html.='<td colspan="4" style="text-align:center; font-size: 13px;">Libre Ejercicio Profesional</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Independiente</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_actividad_economica_conyuge.'</u></td>';
	                        
	                    }else {
	                        
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Ama de Casa</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Empleado Público</td>';
	                        $html.='<td colspan="4" style="text-align:center; font-size: 13px;">Libre Ejercicio Profesional</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Independiente</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Jubilado</td>';
	                        
	                    }
	                    
	                    $html.='</tr>';
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:justify; font-size: 10px;">"Declaro y me responsabilizo que toda la información en esta solicitud es correcta. Así mismo, expresamente autorizo que se obtenga de cualquier fuente de
																						información referencias relativas a mi comportamiento crediticio, manejo de mi(s) tarjeta(s) de crédito, etc., y ,en general al cumplimiento de mis obligaciones,
																						así como confiero mi autorización expresa para obtener, procesar, reportar y suministrar cualquier información de carácter crediticio, financiero y comercial a
																						cualquier central de información debidamente constituida. Adicionalmente autorizo que se proporcione y obtenga cualquier información de carácter crediticio,
																						financiero y comercial que requiera un tercero interesado en adquirir cartera respecto a la cual sea (nos) obligados principales o garantes. Los valores que estoy
																						(amos) solicitando sean financiados, van a tener un destino lícito y no serán utilizados en ninguna actividad que esté relacionada con el cultivo, producción,
																						transporte, tráfico, etc., de estupefacientes o sustancias psicotrópicas.
																						Autorizo a ustedes y a las autoridades competentes para que se realice la verificación de esta información (Circular SB-91-336). Declaro(amos) bajo juramento que
																						los fondos utilizados para pagar la obligación crediticia tienen origen licito, no provienen ni provendrán de ninguna actividad prohibida por la ley, ni son fruto
																						del tráfico de sustancias estupefacientes y psicotrópicas, ni de ninguna actividad relacionada con el lavado de activos. En cconsecuencia asumimos cualquier tipo
																						de responsabilidad civil y penal por la veracidad de esta declaración."</th>';
	                    $html.='</tr>';
	                    
	                    
	                    if($_tipo_participe_datos_prestamo=='Deudor'){
	                        $html.='<tr>';
	                        $html.='<th colspan="6" style="text-align:left; font-size: 13px;">Nombres del Solicitante</th>';
	                        $html.='<th colspan="6" style="text-align:left; font-size: 13px;">Firma</th>';
	                        $html.='</tr>';
	                    }else{
	                        $html.='<tr>';
	                        $html.='<th colspan="6" style="text-align:left; font-size: 13px;">Nombres del Garante</th>';
	                        $html.='<th colspan="6" style="text-align:left; font-size: 13px;">Firma</th>';
	                        $html.='</tr>';
	                    }
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" rowspan="6" style="text-align:left; font-size: 13px;">'.$_apellidos_solicitante_datos_personales.' '.$_nombres_solicitante_datos_personales.'</td>';
	                    $html.='<td colspan="6" rowspan="6" style="text-align:left; font-size: 13px;"></td>';
	                    $html.='</tr>';
	                    
	                    $html.='</table>';
	                    
	                    
	                    
	                    
	                    
	                    $html.='<div style="page-break-after:always;"></div>';
	                    
	                    
	                    $html.='<div style="margin-left: 25px; margin-right: 25px; text-align:center;">'.$logo.'</div>';
	                    
	                    $html.='<table style="width: 100%; margin-top:30px;" >';
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 18px;"><b>REGISTRO DE FIRMAS<b></th>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 18px;"></th>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:left; font-size: 15px; font-weight: normal;"><b>No. de Cedula:</b> '.$_numero_cedula_datos_personales.'</th>';
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:left; font-size: 15px; font-weight: normal;"><b>Apellidos y Nombres:</b> '.$_apellidos_solicitante_datos_personales.' '.$_nombres_solicitante_datos_personales.'</th>';
	                    $html.='</tr>';
	                    
	                    $html.='</table>';
	                    
	                    
	                    $html.='<div style="margin-left: 25px; margin-right: 25px; text-align:center;">'.$qr_participes.'</div>';
	                    
	                    $html.='<table style="width: 100%;">';
	                    $html.='<tr>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 18px;"></th>';
	                    $html.='<td colspan="8" style="text-align:center; font-size: 12px;"><b>Revisado por:</b> '.$_nombre_oficial_credito.'</th>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 18px;"></th>';
	                    $html.='</tr>';
	                    $html.='</table>';
	                    
	                    $html.='<table style="width: 100%;">';
	                    $html.='<tr>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 18px;"></th>';
	                    $html.='<th colspan="8" style="text-align:center; font-size: 18px;"><div style="width: 100%; height: 150px; border: 1px solid;"></div></th>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 18px;"></th>';
	                    $html.='</tr>';
	                    $html.='</table>';
	                    
	                    $html.='<table style="width: 100%;">';
	                    $html.='<tr>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 18px;"></th>';
	                    $html.='<th colspan="8" style="text-align:left; font-size: 14px; font-weight: normal;"><b>Nota: </b>Debe firmar dentro del recuadro sin colocar marcas fuera del mismo. La firma debe ser igual a la de la cédula.</th>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 18px;"></th>';
	                    $html.='</tr>';
	                    $html.='</table>';
	                    
	                    
	                    
	                    
	                    
	                    
	                    
	                    
	                    $html.='<div style="page-break-after:always;"></div>';
	                    
	                    
	                    $html.='<div style="margin-left: 25px; margin-right: 25px; text-align:center;">'.$logo.'</div>';
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:center; font-size: 16px;"><b>AUTORIZACIÓN DE DESCUENTO DE ROL DE PAGOS<b></p><br>';
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Yo, <b>'.$_apellidos_solicitante_datos_personales.' '.$_nombres_solicitante_datos_personales.'</b>, con cédula de ciudadanía No. <b>'.$_numero_cedula_datos_personales.'</b>, en mi calidad de Servidor
				Público, Funcionario, Empleado, Trabajador u Otro, de Fuerzas Armadas en: Fuerza o Entidad Patronal: <b>'.$_nombre_entidades.'</b>.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Declaro expresamente que estoy afiliado al Fondo Complementario Previsional Cerrado
					de Cesantía de Servidores y Trabajadores Públicos de Fuerzas Armadas "CAPREMCI",
					y como tal he venido recibiendo los beneficios que el Fondo otorga.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Por lo tanto, AUTORIZO e INSTRUYO expresa, irrevocable e indefinidamente, a mi
				empleador <b>'.$_nombre_entidades.'</b> que proceda con
				el descuento de mi Remuneración u Otros Ingresos, los valores correspondientes a las
				Aportaciones, Cuotas de Préstamos, Intereses de mora, Acreditaciones Indebidas,
				Prestaciones, Servicios Recibidos, o por cualquier otra obligación que mantengo (a) con
				el Fondo Complementario Previsional Cerrado de Cesantía de Servidores y Trabajadores
				Públicos de Fuerzas Armadas "CAPREMCI", hasta su total cancelación, sea en calidad de
				afiliado, deudor y o garante solidario.</p>';
	                    
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Acepto expresa e irrevocablemente que cualquier variación al porcentaje de aportación
					me será comunicada a través de los Delegados a la Asamblea General, o por cualquier
					otro medio que el Fondo defina para el efecto.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">En <b>'.$_nombre_sucursales.'</b>, '.$creado.'.</p>';
	                    
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Declaro que la firma que estampo en este documento es la mía propia y que la utilizo
					en todo acto público o privado.</p>';
	                    
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:left; font-size: 15px;">Atentamente,</p><br><br>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:left; font-size: 15px;">...................................<br>Firma afiliado</p>';
	                    
	                    
	                    $html.='<div style="page-break-after:always;"></div>';
	                    $html.='<div style="margin-left: 25px; margin-right: 25px; text-align:center;">'.$logo.'</div>';
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:center; font-size: 16px;"><b>AUTORIZACIÓN DE DÉBITOS AUTOMÁTICOS<b></p>';
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Señores: Fondo Complementario Previsional Cerrado de Cesantía de Servidores y Trabajadores Públicos de Fuerzas Armadas "CAPREMCI".</p>';
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Yo, <b>'.$_apellidos_solicitante_datos_personales.' '.$_nombres_solicitante_datos_personales.'</b>, con cédula de ciudadanía No. <b>'.$_numero_cedula_datos_personales.'</b>. AUTORIZO E INSTRUYO expresa, irrevocable e indefinidamente a ustedes a ordenar, en mi
						nombre y representación, el (los) débito (s) de mi (s) cuenta (s):</p>';
	                    
	                    if($_tipo_pago_cuenta_bancaria=='Depósito' || $_tipo_pago_cuenta_bancaria=='Retira Cheque'){
	                        
	                        if($_tipo_cuenta_cuenta_bancaria=='Ahorros'){
	                            
	                            $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:left; font-size: 15px;">Cuenta Ahorros No: <b>'.$_numero_cuenta_cuenta_bancaria.'</b></p>';
	                            
	                        }else{
	                            $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:left; font-size: 15px;">Cuenta Corriente No: <b>'.$_numero_cuenta_cuenta_bancaria.'</b></p>';
	                        }
	                        
	                    }else{
	                        $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:left; font-size: 15px;">Cuenta Ahorros No: ___________________<br>Cuenta Corriente No: __________________</p>';
	                    }
	                    
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">que mantengo en '.$_abreviaciones_bancos.' <b>'.$_nombre_bancos_datos_prestamo.'</b>, en adelante simplemente denominado "el
						Banco", por aportaciones, cuotas de préstamos, intereses de mora, acreditaciones indebidas,
						prestaciones, servicios recibidos, o por cualquier otra obligación que mantengo (a) con el Fondo
						Complementario Previsional Cerrado de Cesantía de Servidores y Trabajadores Públicos de
						Fuerzas Armadas "CAPREMCI", hasta su total cancelación, sea en calidad de afiliado, deudor y
						o garante solidario.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Los valores correspondientes a las obligaciones que mantengo (a) con el Fondo serán debitados
						hasta la total cancelación de las mismas, y acreditados a la cuenta que el Fondo Complementario
						Previsional Cerrado de Cesantía de Servidores y Trabajadores Públicos de Fuerzas Armadas
						"CAPREMCI" designe.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Me comprometo a mantener los fondos suficientes en mi (s) cuenta (s) referida (s), a fin de cubrir
						los valores cuyos débitos autorizo a través de este instrumento, y autorizo a debitar de mi cuenta
						la comisión o costo que '.$_abreviaciones_bancos.' <b>'.$_nombre_bancos_datos_prestamo.'</b> estipule en sus tarifarios
						vigentes por efecto de la prestación de servicio de intermediación de cobranza, así como también
						el valor resultante por cualquier modificación que a futuro se estableciere a dicho costo y que se
						incluya en el respectivo tarifario, valores que me obligo a pagar al Banco y autorizo debitar de mi
						cuenta corriente o de ahorros antes referida, durante todo el tiempo que subsista la prestación
						del mencionado servicio, y asumir cualquier tipo de impuesto que secausare.
						Cualquier instrucción tendiente a revocar esta autorización de débito, me obligo a presentarla al
						Fondo Complementario Previsional Cerrado de Cesantía de Servidores y Trabajadores Públicos
						de Fuerzas Armadas "CAPREMCI" con al menos 30 días calendario de anticipación, y autorizo a
						este para que lo trámite ante el Banco, siempre y cuando me encuentre al día en mis obligaciones
						para con el Fondo.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">De igual manera dejo constancia que este procedimiento no constituye embargo ni retención
						arbitraria alguna, por obedecer a mi expreso consentimiento para el fiel cumplimiento de mis
						obligaciones con el Fondo.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Eximo al Banco de toda responsabilidad por los pagos que efectúe al Fondo Complementario
						Previsional Cerrado de Cesantía de Servidores y Trabajadores Públicos de Fuerzas Armadas
						"CAPREMCI" en virtud de la presente Autorización de Débito, por lo que renuncio a presentar,
						por este concepto, cualquier acción legal, jurídica o extrajudicial en contra del Banco.</p>';
	                    
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">En <b>'.$_nombre_sucursales.'</b>, '.$creado.'.</p><br>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:left; font-size: 15px;">...................................<br>Firma afiliado</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Por el Fondo Complementario Previsional Cerrado de Cesantía de Servidores y Trabajadores
						Públicos de Fuerzas Armadas "CAPREMCI".</p>';
	                    
	                    $html.='<div style="page-break-after:always;"></div>';
	                    $html.='<div style="margin-left: 25px; margin-right: 25px; text-align:center;">'.$logo.'</div>';
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:center; font-size: 16px;"><b>CONTRATO DE ADHESIÓN AL FONDO COMPLEMENTARIO PREVISIONAL CERRADO DE CESANTÍA DE SERVIDORES Y TRABAJAORES PUBLICOS DE FUERZAS ARMADAS - CAPREMCI.<b></p><br>';
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>PRIMERA.- COMPARECIENTES.-</b> Comparecen a la suscripción del presente CONTRATO DE ADHESIÓN por una parte, el señor(a) <b>'.$_apellidos_solicitante_datos_personales.' '.$_nombres_solicitante_datos_personales.'</b>, por sus propios y personales derechos, en adelante se le denominará como el PARTÍCIPE, y por otra parte, el Fondo Complementario Previsional Cerrado de Cesantía de Servidores y Trabajadores Públicos de Fuerzas Armadas - "CAPREMCI" representado legalmente por su Representante Legal, en adelante se le denominará como el FONDO.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>SEGUNDA.- ANTECEDENTES.-</b><br><b>2.1.</b> El Fondo es una entidad de derecho privado, sin fines de lucro y de beneficio social, regulado por la Ley de Seguridad Social y controlado por la Superintendencia de Bancos, administrado bajo el régimen de contribución definida con un sistema de financiamiento de capitalización, en el cual cada uno de los partícipes tiene su cuenta individual.<br><b>2.2.</b> El PARTÍCIPE es una persona natural, dependiente civil de una de las Entidades Patronales relacionadas con las Fuerzas Armadas del Ecuador, capaz de afiliarse y ser parte del FONDO.</p>';
	                    
	                    $_var_procentajes="";
	                    if($_porcentaje_aportacion=='7%'){
	                        
	                        $_var_procentajes="<u><b>$_porcentaje_aportacion</b></u> o 9.1%";
	                    }else{
	                        $_var_procentajes="7% o <u><b>$_porcentaje_aportacion</b></u>";
	                    }
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>TERCERA.- ADHESIÓN.-</b> Con los antecedentes expuestos, el PARTÍCIPE se adhiere voluntaria y expresamente al FONDO y por lo tanto, dispone expresa, voluntaria e irrevocablemente que el aporte personal para la constitución de su Cuenta Individual sea el '.$_var_procentajes.' de su Remuneración Mensual Unificada (RMU). El PARTÍCIPE autoriza e instruye al FONDO que este valor sea debitado mensual y prioritariamente de su rol de pagos, o de la cuenta de ahorros de '.$_abreviaciones_bancos.' <b>'.$_nombre_bancos_datos_prestamo.'</b> N° <b>'.$_numero_cuenta_cuenta_bancaria.'</b> valor que debe ser recaudado y transferido, inmediatamente a la cuenta corriente del Banco General Rumiñahui Nº 8000589904 del FONDO.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Por su parte, el FONDO acepta la adhesión del PARTÍCIPE y se compromete en otorgar los beneficios y servicios que éste oferta, en las mismas condiciones que el resto de PARTÍCIPES del FONDO, siempre y cuando el PARTÍCIPE se encuentre al día en el cumplimiento de sus obligaciones económicas para con el FONDO.</p>';
	                    
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>CUARTA.- DERECHOS Y OBLIGACIONES DEL PARTÍCIPE.-</b> Se establecen en los artículos 8 y 9 del Estatuto Vigente.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>QUINTA.- CONFORMACIÓN DE LA CUENTA INDIVIDUAL.-</b> Las cuentas de capitalización individual están conformadas por el aporte personal del PARTÍCIPE más sus rendimientos; el voluntario adicional, de ser el caso y sus rendimientos; y el aporte patronal y sus rendimientos alcanzados por las inversiones privativas y no privativas del FONDO en su conjunto.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>SEXTA.- RECONOCIMIENTO DE RENDIMIENTOS.-</b> Los rendimientos que correspondan a la cuenta de la capitalización de los PARTÍCIPES serán registrados después del cierre del periodo fiscal.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>SÉPTIMA.- RECONOCIMIENTO DE LA CESANTÍA.-</b> La CESANTÍA que el FONDO ofrece, será entregada a favor del PARTÍCIPE, cuando se cumplan una de las siguientes condiciones:<br><b>7.1</b> Haber cesado en sus funciones laborales definitivamente en las Entidades dependientes y adscritas de las Fuerzas Armadas a las que pertenezcan;<br><b>7.2</b> Fallecimiento;</p>';
	                    
	                    $html.='<div style="page-break-after:always;"></div>';
	                    $html.='<div style="margin-left: 25px; margin-right: 25px; text-align:center;">'.$logo.'</div>';
	                    
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>OCTAVA.- PROCEDIMIENTO PARA LA LIQUIDACIÓN DE LA CESANTÍA.-</b> Será aquel determinado en el Estatuto vigente y en los respectivos reglamentos del FONDO.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>NOVENA.- INFORMACIÓN DE CUENTA INDIVIDUAL.-</b> Toda la información financiera relativa al Fondo y a la situación de cada cuenta individual se encuentra a disposición del afiliado en la página web institucional y excepcionalmente por escrito.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>DÉCIMA.- DE LA DESAFILIACIÓN VOLUNTARIA.-</b> Es un derecho del PARTÍCIPE y se sujetará a los requisitos y condiciones previstos en el Estatuto vigente y en los respectivos reglamentos del FONDO y a las normas expedidas por la Junta de Política y Regulación Monetaria y Financiera.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>DÉCIMA PRIMERA.- DECLARACIÓN DE CONOCIMIENTO Y ACEPTACIÓN DEL ESTATUTO DEL FONDO.-</b> El PARTÍCIPE declara que conoce, y entiende las disposiciones establecidas en el Estatuto y Reglamentos del FONDO, sus derechos y obligaciones, y por lo tanto las ACEPTA expresa e incondicionalmente.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;"><b>DÉCIMA SEGUNDA.- DE LA SOLUCIÓN DE CONTROVERSIAS.-</b></p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">Las controversias que se generen entre los partícipes y la administración del Fondo, relacionadas a derechos y obligaciones, se someterán a la resolución de la Asamblea General de Representantes.</p>';
	                    
	                    $html.='<p style="margin-left: 25px; margin-right: 25px; text-align:justify; font-size: 15px;">En la ciudad de <b>'.$_nombre_sucursales.'</b>, '.$creado.', las partes suscriben el presente Contrato por duplicado.</p>';
	                    
	                    
	                    $html.= "<table style='margin-left: 15px; margin-right: 15px; width: 100%; margin-top:50px;'>";
	                    $html.= '<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 15px; font-weight: normal;">________________________<br>Firma Participe<br>C.C. '.$_numero_cedula_datos_personales.'</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 15px; font-weight: normal;"><br>________________________<br>Ing. Stephany Zurita Cedeño<br>Representante Legal<br>"Capremci"</th>';
	                    $html.= '<tr>';
	                    $html.='</table>';
	                    
	                    
	                    
	            }
	            
	            $this->report("SolicitudPrestamo",array("resultSet"=>$html));
	            die();
	            
	        }
	        
	    }else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	    }
	    
	}
	
	
	
	
	
	
	public function print_desafiliacion()
	{
	    
	    session_start();
	    
	    require_once 'core/DB_Functions.php';
	    $db = new DB_Functions();
	    
	    
	    $html="";
	    
	    $id_usuarios = $_SESSION["id_usuarios"];
	    $fechaactual = getdate();
	    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	    
	    $directorio = $_SERVER ['DOCUMENT_ROOT'] . '/rp_c';
	    $dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
	    $domLogo=$directorio.'/view/images/logo_contrato_adhesion.jpg';
	    $logo = '<img src="'.$domLogo.'" width="100%">';
	    
	    
	    if(!empty($id_usuarios)){
	        
	        if(isset($_GET["id_solicitud_prestaciones"])){
	            
	            $id_solicitud_prestaciones=$_GET["id_solicitud_prestaciones"];
	            
	            $columnas="solicitud_prestaciones.id_solicitud_prestaciones, 
                          solicitud_prestaciones.apellidos_solicitud_prestaciones, 
                          solicitud_prestaciones.nombres_solicitud_prestaciones, 
                          solicitud_prestaciones.cedula_participes, 
                          sexo.id_sexo, 
                          sexo.nombre_sexo, 
                          solicitud_prestaciones.fecha_nacimiento_solicitud_prestaciones, 
                          estado_civil.id_estado_civil, 
                          estado_civil.nombre_estado_civil, 
                          provincias.id_provincias, 
                          provincias.nombre_provincias, 
                          cantones.id_cantones, 
                          cantones.nombre_cantones, 
                          parroquias.id_parroquias, 
                          parroquias.nombre_parroquias, 
                          solicitud_prestaciones.barrio_solicitud_prestaciones, 
                          solicitud_prestaciones.ciudadela_solicitud_prestaciones, 
                          solicitud_prestaciones.calle_solicitud_prestaciones, 
                          solicitud_prestaciones.numero_calle_solicitud_prestaciones, 
                          solicitud_prestaciones.interseccion_solicitud_prestaciones, 
                          solicitud_prestaciones.tipo_vivienda_solicitud_prestaciones, 
                          solicitud_prestaciones.vivienda_hipotecada_solicitud_prestaciones, 
                          solicitud_prestaciones.referencia_dir_solicitud_prestaciones, 
                          solicitud_prestaciones.telefono_solicitud_prestaciones, 
                          solicitud_prestaciones.celular_solicitud_prestaciones, 
                          solicitud_prestaciones.correo_solicitud_prestaciones, 
                          solicitud_prestaciones.nivel_educativo_solicitud_prestaciones, 
                          solicitud_prestaciones.nombres_referencia_familiar, 
                          solicitud_prestaciones.apellidos_referencia_familiar, 
                          solicitud_prestaciones.parentesco_referencia_familiar, 
                          solicitud_prestaciones.primer_telefono_referencia_familiar, 
                          solicitud_prestaciones.segundo_telefono_referencia_familiar, 
                          solicitud_prestaciones.nombres_referencia_personal, 
                          solicitud_prestaciones.apellidos_referencia_personal, 
                          solicitud_prestaciones.parentesco_referencia_personal, 
                          solicitud_prestaciones.primer_telefono_referencia_personal, 
                          solicitud_prestaciones.segundo_telefono_referencia_personal, 
                          solicitud_prestaciones.ultimo_cargo_solicitud_prestaciones, 
                          solicitud_prestaciones.fecha_salida_solicitud_prestaciones, 
                          bancos.id_bancos, 
                          bancos.nombre_bancos, 
                          solicitud_prestaciones.numero_cuenta_ahorros_bancaria, 
                          solicitud_prestaciones.numero_cuenta_corriente_bancaria, 
                          solicitud_prestaciones.identificador_consecutivos, 
                          solicitud_prestaciones.creado";
	            $tablas=" public.solicitud_prestaciones, 
                          public.sexo, 
                          public.estado_civil, 
                          public.provincias, 
                          public.cantones, 
                          public.parroquias, 
                          public.bancos";
	            $where="solicitud_prestaciones.id_sexo = sexo.id_sexo AND
                          solicitud_prestaciones.id_provincias = provincias.id_provincias AND
                          estado_civil.id_estado_civil = solicitud_prestaciones.id_estado_civil AND
                          cantones.id_cantones = solicitud_prestaciones.id_cantones AND
                          parroquias.id_parroquias = solicitud_prestaciones.id_parroquias AND
                          bancos.id_bancos = solicitud_prestaciones.id_bancos AND solicitud_prestaciones.id_solicitud_prestaciones='$id_solicitud_prestaciones'";
	            $id="solicitud_prestaciones.id_solicitud_prestaciones";
	            
	            $resultSoli=$db->getCondicionesDesc($columnas, $tablas, $where, $id);
	            
	            
	           
	            
	            
	            if(!empty($resultSoli)){
	                
	                
	                
	                
	                // DATOS DEL PRESTAMO
	                $_id_solicitud_prestaciones       					       =$resultSoli[0]->id_solicitud_prestaciones;
	                $_apellidos_solicitud_prestaciones       				   =$resultSoli[0]->apellidos_solicitud_prestaciones;
	                $_nombres_solicitud_prestaciones       					   =$resultSoli[0]->nombres_solicitud_prestaciones;
	                $_cedula_participes       					               =$resultSoli[0]->cedula_participes ;
	                $_id_sexo       					                       =$resultSoli[0]->id_sexo;
	                $_nombre_sexo        					                   =$resultSoli[0]->nombre_sexo;
	                $_fecha_nacimiento_solicitud_prestaciones       		   =$resultSoli[0]->fecha_nacimiento_solicitud_prestaciones;
	                $_id_estado_civil       					               =$resultSoli[0]->id_estado_civil ;
	                $_nombre_estado_civil       			            	   =$resultSoli[0]->nombre_estado_civil ;
	                $_id_provincias       					                   =$resultSoli[0]->id_provincias ;
	                $_nombre_provincias       				                   =$resultSoli[0]->nombre_provincias ;
	                $_id_cantones       					                   =$resultSoli[0]->id_cantones;
	                $_nombre_cantones       					               =$resultSoli[0]->nombre_cantones;
	                $_id_parroquias       					                   =$resultSoli[0]->id_parroquias ;
	                $_nombre_parroquias       			                  	   =$resultSoli[0]->nombre_parroquias;
	                $_barrio_solicitud_prestaciones        					   =$resultSoli[0]->barrio_solicitud_prestaciones ;
	                $_ciudadela_solicitud_prestaciones       				   =$resultSoli[0]->ciudadela_solicitud_prestaciones;
	                $_calle_solicitud_prestaciones       					   =$resultSoli[0]->calle_solicitud_prestaciones ;
	                $_numero_calle_solicitud_prestaciones        			   =$resultSoli[0]->numero_calle_solicitud_prestaciones ; 
	                $_interseccion_solicitud_prestaciones        			   =$resultSoli[0]->interseccion_solicitud_prestaciones ;
	                $_tipo_vivienda_solicitud_prestaciones        			   =$resultSoli[0]->tipo_vivienda_solicitud_prestaciones  ;
	                $_vivienda_hipotecada_solicitud_prestaciones        	   =$resultSoli[0]->vivienda_hipotecada_solicitud_prestaciones  ; 
	                $_referencia_dir_solicitud_prestaciones        			   =$resultSoli[0]->referencia_dir_solicitud_prestaciones  ;
	                $_telefono_solicitud_prestaciones        				   =$resultSoli[0]->telefono_solicitud_prestaciones;
	                $_celular_solicitud_prestaciones        				   =$resultSoli[0]->celular_solicitud_prestaciones ; 
	                $_correo_solicitud_prestaciones        					   =$resultSoli[0]->correo_solicitud_prestaciones;
	                $_nivel_educativo_solicitud_prestaciones        		   =$resultSoli[0]->nivel_educativo_solicitud_prestaciones; 
	                $_nombres_referencia_familiar        					   =$resultSoli[0]->nombres_referencia_familiar;
	                $_apellidos_referencia_familiar        					   =$resultSoli[0]->apellidos_referencia_familiar;
	                $_parentesco_referencia_familiar        				   =$resultSoli[0]->parentesco_referencia_familiar ; 
	                $_primer_telefono_referencia_familiar        			   =$resultSoli[0]->primer_telefono_referencia_familiar ;
	                $_segundo_telefono_referencia_familiar        			   =$resultSoli[0]->segundo_telefono_referencia_familiar  ;
	                $_nombres_referencia_personal        					   =$resultSoli[0]->nombres_referencia_personal;
	                $_apellidos_referencia_personal        					   =$resultSoli[0]->apellidos_referencia_personal ;
	                $_parentesco_referencia_personal        				   =$resultSoli[0]->parentesco_referencia_personal ;
	                $_primer_telefono_referencia_personal        			   =$resultSoli[0]->primer_telefono_referencia_personal;
	                $_segundo_telefono_referencia_personal        			   =$resultSoli[0]->segundo_telefono_referencia_personal ;
	                $_ultimo_cargo_solicitud_prestaciones        			   =$resultSoli[0]->ultimo_cargo_solicitud_prestaciones;
	                $_fecha_salida_solicitud_prestaciones       			   =$resultSoli[0]->fecha_salida_solicitud_prestaciones;
	                $_id_bancos        					                       =$resultSoli[0]->id_bancos;
	                $_nombre_bancos         				                   =$resultSoli[0]->nombre_bancos;
	                $_numero_cuenta_ahorros_bancaria         				   =$resultSoli[0]->numero_cuenta_ahorros_bancaria ;
	                $_numero_cuenta_corriente_bancaria         				   =$resultSoli[0]->numero_cuenta_corriente_bancaria;
	                $_identificador_consecutivos         					   =$resultSoli[0]->identificador_consecutivos;
	                $_creado         					                       =$resultSoli[0]->creado ;
	                
	                
	                    
	                    $html.='<table style="width: 100%;"  border=0 cellspacing=0.0001 >';
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 16px;"><b>FONDO COMPLEMENTARIO PREVISIONAL CERRADO DE CESANTÍA DE SERVIDORES Y TRABAJADORES PÚBLICOS DE FUERZAS ARMADAS <br>-CAPREMCI-<br><b></th>';
	                    $html.='</tr>';
	                    $html.='</table>';
	                    
	                    $html.='<br>';

	                    $html.='<table style="width: 100%;"  border=1 cellspacing=0.0001 >';
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 15px;"><b><br>SOLICITUD DE DESAFILICACIÓN VOLUNTARIA Y DEVOLUCIÓN DE APORTES POR CESANTÍA<br><br><b></th>';
	                    $html.='</tr>';
	                   
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Nombres y Apellidos</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">No. de Cédula</th>';
	                    $html.='</tr>';
	                   
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">'.$_apellidos_solicitud_prestaciones.' '.$_nombres_solicitud_prestaciones.'</th>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">'.$_cedula_participes.'</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Género</th>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;">Fecha de nacimiento</th>';
	                    $html.='<th colspan="5" style="text-align:center; font-size: 13px;">Estado civil</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    if($_nombre_sexo=='MASCULINO'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nombre_sexo.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">FEMENINO</td>';
	                    }
	                    else{
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Hombre</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nombre_sexo.'</u></td>';
	                    }
	                    
	                    
	                    $html.='<td colspan="3" style="text-align:center; font-size: 13px;">'.$_fecha_nacimiento_solicitud_prestaciones.'</th>';
	                    
	                    if($_nombre_estado_civil=='Soltero'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_nombre_estado_civil.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_nombre_estado_civil=='Casado'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_nombre_estado_civil.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_nombre_estado_civil=='Viudo'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_nombre_estado_civil.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_nombre_estado_civil=='Divorciado'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_nombre_estado_civil.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_nombre_estado_civil=='Otro'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Soltero</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Casado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Viudo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Divorciado</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_nombre_estado_civil.'</u></td>';
	                    }
	                   
	                   
	                    
	                    $html.='</tr>';
	                   
	                   
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;">Dirección exacta del domicilio del afiliado</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Provincia</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cantón</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Parroquia</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_nombre_provincias.'</th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_nombre_cantones.'</th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_nombre_parroquias.'</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Barrio y/o sector</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Ciudadela y/o conjunto / Etapa / Manzana</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">'.$_barrio_solicitud_prestaciones.'</th>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">'.$_ciudadela_solicitud_prestaciones.'</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Calle</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Número</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Intersección</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_calle_solicitud_prestaciones.'</th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_numero_calle_solicitud_prestaciones.'</th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_interseccion_solicitud_prestaciones.'</th>';
	                    $html.='</tr>';
	                    
	                   
	                    $html.='<tr>';
	                    $html.='<th colspan="8" style="text-align:center; font-size: 13px;">Vivienda:</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Su vivienda esta hipotecada:</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    if($_tipo_vivienda_solicitud_prestaciones=='Propia'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_tipo_vivienda_solicitud_prestaciones.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                    }
	                    elseif($_tipo_vivienda_solicitud_prestaciones=='Arrendada'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_tipo_vivienda_solicitud_prestaciones.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                    }
	                    elseif($_tipo_vivienda_solicitud_prestaciones=='Anticresis'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_tipo_vivienda_solicitud_prestaciones.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                    }
	                    elseif($_tipo_vivienda_solicitud_prestaciones=='Vive con Familiares'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_tipo_vivienda_solicitud_prestaciones.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otra</td>';
	                    }
	                    elseif($_tipo_vivienda_solicitud_prestaciones=='Otra'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Propia</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Arrendada</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Anticresis</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Vive con Familiares</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"></td<u>'.$_tipo_vivienda_solicitud_prestaciones.'</u>>';
	                    }
	                    
	                    
	                    
	                    if($_vivienda_hipotecada_solicitud_prestaciones=='SI'){
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_vivienda_hipotecada_solicitud_prestaciones.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">NO</td>';
	                        
	                    }
	                    else{
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">SI</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_vivienda_hipotecada_solicitud_prestaciones.'</u></td>';
	                        
	                    }
	                    $html.='</tr>';
	                    
	                    
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="12" style="font-size: 13px;"><b>Referencia de la dirección del domicilio: </b>'.$_referencia_dir_solicitud_prestaciones.'</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="font-size: 13px;">Números telefónicos:</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"><b>Casa: </b>'.$_telefono_solicitud_prestaciones.'</th>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"><b>Celular: </b>'. $_celular_solicitud_prestaciones.'</th>';
	                    $html.='</tr>';
	                    
	                   
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Dirección electrónica</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Nivel educativo</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">'.$_correo_solicitud_prestaciones.'</th>';
	                    
	                    if($_nivel_educativo_solicitud_prestaciones=='Primario'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_nivel_educativo_solicitud_prestaciones.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    elseif($_nivel_educativo_solicitud_prestaciones=='Secundario'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u><b>'.$_nivel_educativo_solicitud_prestaciones.'<b></u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    elseif($_nivel_educativo_solicitud_prestaciones=='Técnico'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_nivel_educativo_solicitud_prestaciones.'</u></td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    elseif($_nivel_educativo_solicitud_prestaciones=='Universitario'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;"><u>'.$_nivel_educativo_solicitud_prestaciones.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Postgrado</td>';
	                    }
	                    elseif($_nivel_educativo_solicitud_prestaciones=='Postgrado'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Secundario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Técnico</td>';
	                        $html.='<td colspan="2" style="text-align:center; font-size: 13px;">Universitario</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_nivel_educativo_solicitud_prestaciones.'</u></td>';
	                    }
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Referencia familiar que no viva con Ud </th>';
	                    $html.='<th colspan="9" style="text-align:center; font-size: 13px;">Parentesco</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">Dos números telefónicos</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="2" style="text-align:center; font-size: 13px;">'.$_nombres_referencia_familiar.' '.$_apellidos_referencia_familiar.'</th>';
	                    
	                    if($_parentesco_referencia_familiar=='Primo'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_familiar.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_familiar=='Tío'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_familiar.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_familiar=='Hermano'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_familiar.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_familiar=='Sobrino'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_familiar.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_familiar=='Abuelo'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_familiar.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_familiar=='Hijo'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_familiar.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_familiar=='Madre'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_familiar.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_familiar=='Padre'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_familiar.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_familiar=='Otro'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Primo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Tío</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hermano</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Sobrino</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Abuelo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Hijo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Madre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Padre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_familiar.'</u></td>';
	                    }
	                    
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px;">'.$_primer_telefono_referencia_familiar.' - '.$_segundo_telefono_referencia_familiar.'</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;">Referencia personal</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Parentesco</th>';
	                    $html.='<th colspan="3" style="text-align:center; font-size: 13px;">Dos números telefónicos</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="3" style="text-align:center; font-size: 13px;">'.$_nombres_referencia_personal.'</th>';
	                    
	                    if($_parentesco_referencia_personal=='Amigo'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_personal.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Comadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compañero Laboral(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Jefe(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_personal=='Compadre'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Amigo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_personal.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Comadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compañero Laboral(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Jefe(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_personal=='Comadre'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Amigo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_personal.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compañero Laboral(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Jefe(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_personal=='Compañero Laboral(a)'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Amigo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Comadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_personal.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Jefe(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_personal=='Jefe(a)'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Amigo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Comadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compañero Laboral(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_personal.'</u></td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Otro</td>';
	                    }
	                    elseif($_parentesco_referencia_personal=='Otro'){
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Amigo</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Comadre</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Compañero Laboral(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;">Jefe(a)</td>';
	                        $html.='<td colspan="1" style="text-align:center; font-size: 13px;"><u>'.$_parentesco_referencia_personal.'</u></td>';
	                    }
	                    
	                    $html.='<td colspan="3" style="text-align:center; font-size: 13px;">'.$_primer_telefono_referencia_personal.' - '.$_segundo_telefono_referencia_personal.'</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Último cargo</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Fecha de salida</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">'.$_ultimo_cargo_solicitud_prestaciones.'</th>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">'.$_fecha_salida_solicitud_prestaciones.'</th>';
	                    $html.='</tr>';
	                    
	                    $html.='</table>';
	                    
	                    $html.='<br>';
	                   
	                    $html.='<table style="width: 100%;"  border=1 cellspacing=0.0001 >';
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="text-align:center; font-size: 15px;"><b>DATOS DE LA SOLICITUD<b></th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Presentada por</th>';
	                    $html.='<th colspan="6" style="text-align:center; font-size: 13px;">Fecha</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">'.$_apellidos_solicitud_prestaciones.' '.$_nombres_solicitud_prestaciones.'</th>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;">'.$_fecha_salida_solicitud_prestaciones.'</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="font-size: 13px;">Sres. F.C.P.C. de los Empleados Civiles de las Fuerzas Armadas por la presente solicito que el valor que me corresponde en mi requerimiendo sea depositado en:<b></th>';
	                    $html.='</tr>';
	                  
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Mi cuenta del banco</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cuenta de ahorros número</th>';
	                    $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Cuenta corriente número</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_nombre_bancos.'</th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_numero_cuenta_ahorros_bancaria.'</th>';
	                    $html.='<td colspan="4" style="text-align:center; font-size: 13px;">'.$_numero_cuenta_corriente_bancaria.'</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<th colspan="12" style="font-size: 13px;">Declaro que la información consignada es verdadera y los documentos anexados a ésta solicitud son auténticos</th>';
	                    $html.='</tr>';
	                    
	                    $html.='<tr>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"><b>Firma del Participe desafiliado y/o Cesante:</b><br><br><br>..........................................</th>';
	                    $html.='<td colspan="6" style="text-align:center; font-size: 13px;"><b>Número C.C.</b><br><br><br>'.$_cedula_participes.'</th>';
	                    $html.='</tr>';
	                   
	                    $html.='</table>';
	                    
	                   
	            }
	            
	            $this->report("SolicitudDesafiliacion",array("resultSet"=>$html));
	            die();
	            
	        }
	        
	    }else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	    }
	    
	}
	
	
	
	
	

	public function paginate_load_solicitud_prestamos_registrados($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_solicitud_prestamos_registrados(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_solicitud_prestamos_registrados(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_prestamos_registrados(1)'>1</a></li>";
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
				$out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_prestamos_registrados(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_prestamos_registrados(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_prestamos_registrados($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_solicitud_prestamos_registrados(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	
	public function load_solicitud_garantias_registrados($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_solicitud_garantias_registrados(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_solicitud_garantias_registrados(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_garantias_registrados(1)'>1</a></li>";
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
				$out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_garantias_registrados(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_garantias_registrados(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_garantias_registrados($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_solicitud_garantias_registrados(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	
	///////////////////////////////////////////////////////// ADMINISTRADOR///////////////////////////////////////////////////////
	
	
	
	
	
	public function index5(){
	
		session_start();
		if (isset(  $_SESSION['nombre_usuarios']) )
		{
			$controladores = new ControladoresModel();
			$nombre_controladores = "SolicitudPrestamo";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $controladores->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	
			if (!empty($resultPer))
			{
	
			    
			   
				$this->view_Core("ConsultaSolicitudPrestamoSuperAdmin",array(
						""=>""
				));  
	
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a consultar una solicitud de prestamo."
	
				));
					
			}
	
	
		}
		else
		{
			$error = TRUE;
			$mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
	
			$this->view("Login",array(
					"resultSet"=>"$mensaje", "error"=>$error
			));
	
	
			die();
	
		}
	
	}
	
	
	public function searchadminsuper_deudor(){
	
		session_start();
		
		require_once 'core/DB_Functions.php';
		$db = new DB_Functions();
		
		
		
	
		$where_to="";
		$columnas = "solicitud_prestamo.id_solicitud_prestamo,
					  solicitud_prestamo.tipo_participe_datos_prestamo,
					  solicitud_prestamo.monto_datos_prestamo,
					  solicitud_prestamo.plazo_datos_prestamo,
					  solicitud_prestamo.destino_dinero_datos_prestamo,
					  solicitud_prestamo.nombre_banco_cuenta_bancaria,
					  solicitud_prestamo.tipo_cuenta_cuenta_bancaria,
					  solicitud_prestamo.numero_cuenta_cuenta_bancaria,
					  solicitud_prestamo.numero_cedula_datos_personales,
					  solicitud_prestamo.apellidos_solicitante_datos_personales,
					  solicitud_prestamo.nombres_solicitante_datos_personales,
					  solicitud_prestamo.correo_solicitante_datos_personales,
					  sexo.nombre_sexo,
					  solicitud_prestamo.fecha_nacimiento_datos_personales,
					  estado_civil.nombre_estado_civil,
					  solicitud_prestamo.fecha_presentacion,
					  solicitud_prestamo.fecha_aprobacion,
					  solicitud_prestamo.id_estado_tramites,
					  solicitud_prestamo.identificador_consecutivos,
				      solicitud_prestamo.tipo_pago_cuenta_bancaria,
				      tipo_creditos.nombre_tipo_creditos,
				      usuarios.nombre_usuarios,
				      usuarios.correo_usuarios,
				      solicitud_prestamo.id_usuarios_oficial_credito_aprueba,
				      estado_tramites.nombre_estado_tramites_solicitud_prestamos";
	
		$tablas   = "public.solicitud_prestamo,
					  public.entidades,
					  public.sexo,
					  public.estado_civil,
				      public.tipo_creditos,
				public.usuarios,
				      public.estado_tramites";
			
		$where    = "solicitud_prestamo.id_estado_tramites= estado_tramites.id_estado_tramites AND solicitud_prestamo.id_usuarios_oficial_credito_aprueba=usuarios.id_usuarios AND tipo_creditos.id_tipo_creditos=solicitud_prestamo.id_tipo_creditos AND
		solicitud_prestamo.id_estado_civil_datos_personales = estado_civil.id_estado_civil AND
		entidades.id_entidades = solicitud_prestamo.id_entidades AND
		sexo.id_sexo = solicitud_prestamo.id_sexo_datos_personales AND solicitud_prestamo.tipo_participe_datos_prestamo='Deudor'
        AND solicitud_prestamo.monto_datos_prestamo =0 AND solicitud_prestamo.plazo_datos_prestamo=0 AND usuarios.nombre_usuarios='DIANA NAVARRETE'";
	
		$id       = "solicitud_prestamo.id_solicitud_prestamo";
	
			
		//$where_to=$where;
			
			
		$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
		$search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
			
		if($action == 'ajax')
		{
				
			if(!empty($search)){
					
					
				$where1=" AND (solicitud_prestamo.numero_cedula_datos_personales LIKE '".$search."%' OR solicitud_prestamo.apellidos_solicitante_datos_personales LIKE '".$search."%' OR solicitud_prestamo.nombres_solicitante_datos_personales LIKE '".$search."%' OR tipo_creditos.nombre_tipo_creditos LIKE '".$search."%'  OR usuarios.nombre_usuarios LIKE '".$search."%' OR  estado_tramites.nombre_estado_tramites_solicitud_prestamos LIKE '".$search."%')";
					
				$where_to=$where.$where1;
			}else{
					
				$where_to=$where;
					
			}
				
				
			$html="";
			$resultSet=$db->getCantidad("*", $tablas, $where_to);
			$cantidadResult=(int)$resultSet[0]->total;
	
			$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	
			$per_page = 10; //la cantidad de registros que desea mostrar
			$adjacents  = 9; //brecha entre páginas después de varios adyacentes
			$offset = ($page - 1) * $per_page;
	
			$limit = " LIMIT   '$per_page' OFFSET '$offset'";
	
			$resultSet=$db->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
			$count_query   = $cantidadResult;
			$total_pages = ceil($cantidadResult/$per_page);
	
			if($cantidadResult>0)
			{
	
				$html.='<div class="pull-left" style="margin-left:11px;">';
				$html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
				$html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
				$html.='</div>';
				$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
				$html.='<section style="height:350px; overflow-y:scroll;">';
				$html.= "<table id='tabla_solicitud_prestamos_registrados' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
				$html.= "<thead>";
				$html.= "<tr>";
	
				$html.='<th style="text-align: left;  font-size: 11px;">Cedula</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Apellidos</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Nombres</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Crédito</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Tipo</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Monto</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Plazo</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Transacción</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Presentación</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Trámite</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Fecha T</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Oficial C</th>';
				$html.='<th style="text-align: right;  font-size: 11px;"></th>';
				$html.='<th style="text-align: right;  font-size: 11px;"></th>';
				$html.='</tr>';
				$html.='</thead>';
				$html.='<tbody>';
					
				$i=0;
	
				foreach ($resultSet as $res)
				{
	
					$aprobado_oficial_credito=$res->id_estado_tramites;
					if($aprobado_oficial_credito==2){
						$estado_tramite='Guardado';
	
					}elseif($aprobado_oficial_credito==1){
						$estado_tramite='Pendiente';
					}
					elseif($aprobado_oficial_credito==3){
						$estado_tramite='Rechazado';
	
					}elseif($aprobado_oficial_credito==4){
						$estado_tramite='Revisado';
						
					}
	
					$html.='<tr>';
	
					$html.='<td style="font-size: 11px;">'.$res->numero_cedula_datos_personales.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->apellidos_solicitante_datos_personales.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->nombres_solicitante_datos_personales.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->nombre_tipo_creditos.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->tipo_participe_datos_prestamo.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->monto_datos_prestamo.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->plazo_datos_prestamo.' meses</td>';
					$html.='<td style="font-size: 11px;">'.$res->tipo_pago_cuenta_bancaria.'</td>';
					$html.='<td style="font-size: 11px;">'.date("d/m/Y", strtotime($res->fecha_presentacion)).'</td>';
					$html.='<td style="font-size: 11px;">'.$estado_tramite.'</td>';
					if($aprobado_oficial_credito==1 || $aprobado_oficial_credito==4){
						$html.='<td style="font-size: 11px;"></td>';
						$html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
	
					}else{
					    
					    if(!empty($res->fecha_aprobacion)){
					        
					        $html.='<td style="font-size: 11px;">'.date("d/m/Y", strtotime($res->fecha_aprobacion)).'</td>';
					        
					    }else{
					        
					        $html.='<td style="font-size: 11px;"></td>';
					    }
					    
						$html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
						
					}
					
					/*
					if($aprobado_oficial_credito==1 || $aprobado_oficial_credito==4){
					   
					    $html.='<td style="font-size: 15px;"><span class="pull-right"><button id="btn_abrir" class="btn btn-success" type="button" data-toggle="modal" data-target="#mod_reasignar" data-id="'.$res->id_solicitud_prestamo.'" data-cedu="'.$res->numero_cedula_datos_personales.'" data-nombre="'.$res->apellidos_solicitante_datos_personales.' '.$res->nombres_solicitante_datos_personales.'" data-credito="'.$res->nombre_tipo_creditos.'" data-usuario="'.$res->nombre_usuarios.'"  title="Reasignar" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></button></span></td>';
					    
					     
					}else{
					    $html.='<td style="font-size: 15px;"><span class="pull-right"><a href="javascript:void(0);" target="_blank" class="btn btn-success" style="font-size:65%;" title="Reasignar" disabled><i class="glyphicon glyphicon-edit"></i></a></span></td>';
					    
					}
					
					*/
					
					
					
					
					
					//$html.='<td style="font-size: 15px;"><span class="pull-right"><a href="index.php?controller=SolicitudPrestamo&action=print&id_solicitud_prestamo='.$res->id_solicitud_prestamo.'" target="_blank" class="btn btn-warning" title="Imprimir"><i class="glyphicon glyphicon-print"></i></a></span>';
					$html.='<td style="font-size: 15px;"><span class="pull-right"><a href="index.php?controller=SolicitudPrestamo&action=print_hipotecario&id_solicitud_prestamo='.$res->id_solicitud_prestamo.'" target="_blank" class="btn btn-warning" title="Imprimir"><i class="glyphicon glyphicon-print"></i></a></span>';
					
					
					
					$html.='</td>';
					$html.='<td style="font-size: 15px;">';
					if($aprobado_oficial_credito==1 && $res->nombre_tipo_creditos!="HIPOTECARIO"){
					    $html.='<button class="btn btn-primary pull-right" title="Registrar crédito"  onclick="EnviarInfo(&quot;'.$res->numero_cedula_datos_personales.'&quot;,'.$res->id_solicitud_prestamo.')"><i class="glyphicon glyphicon-import"></i></button>';
					}
					else if($aprobado_oficial_credito==1 && $res->nombre_tipo_creditos=="HIPOTECARIO")
					{
					    $rp_capremci= new PlanCuentasModel();
					    $columnas="estado_escrituras_core_documentos_hipotecario, 
                                    estado_certificado_core_documentos_hipotecario,
                                    estado_impuesto_core_documentos_hipotecario,
                                    estado_avaluo_core_documentos_hipotecario";
					    $tablas="core_documentos_hipotecario";
					    $where="id_solicitud_credito=".$res->id_solicitud_prestamo;
					    $documentos_solicitud=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
					    if(!empty($documentos_solicitud))
					    {
					        if($documentos_solicitud[0]->estado_escrituras_core_documentos_hipotecario=="t" &&
					            $documentos_solicitud[0]->estado_certificado_core_documentos_hipotecario=="t" &&
					            $documentos_solicitud[0]->estado_impuesto_core_documentos_hipotecario=="t" &&
					            $documentos_solicitud[0]->estado_avaluo_core_documentos_hipotecario=="t")
					        {
					            $html.='<button class="btn btn-primary pull-right" title="Registrar crédito"  onclick="EnviarInfo(&quot;'.$res->numero_cedula_datos_personales.'&quot;,'.$res->id_solicitud_prestamo.')"><i class="glyphicon glyphicon-import"></i></button>';
					        }
					    }
					    				    
					}
					
					$html.='</td>';
					
					$html.='</tr>';
	
				}
	
				$html.='</tbody>';
				$html.='</table>';
				$html.='</section></div>';
				$html.='<div class="table-pagination pull-right">';
				$html.=''. $this->paginate_load_solicitud_prestamos_registrados("index.php", $page, $total_pages, $adjacents).'';
				$html.='</div>';
	
	
			}else{
				$html.='<div class="col-lg-6 col-md-6 col-xs-12">';
				$html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
				$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				$html.='<h4>Aviso!!!</h4> <b>Actualmente no hay solicitud de prestamos registrados...</b>';
				$html.='</div>';
				$html.='</div>';
			}
	
			echo $html;
			die();
	
		}
	
	}
	
	
	
	
	
	
	public function searchadminsuper_garantes(){
	
		session_start();
		require_once 'core/DB_Functions.php';
		$db = new DB_Functions();
		
	
		$where_to="";
		$columnas = "solicitud_prestamo.id_solicitud_prestamo,
					  solicitud_prestamo.tipo_participe_datos_prestamo,
					  solicitud_prestamo.monto_datos_prestamo,
					  solicitud_prestamo.plazo_datos_prestamo,
					  solicitud_prestamo.destino_dinero_datos_prestamo,
					  solicitud_prestamo.nombre_banco_cuenta_bancaria,
					  solicitud_prestamo.tipo_cuenta_cuenta_bancaria,
					  solicitud_prestamo.numero_cuenta_cuenta_bancaria,
					  solicitud_prestamo.numero_cedula_datos_personales,
					  solicitud_prestamo.apellidos_solicitante_datos_personales,
					  solicitud_prestamo.nombres_solicitante_datos_personales,
					  solicitud_prestamo.correo_solicitante_datos_personales,
					  sexo.nombre_sexo,
					  solicitud_prestamo.fecha_nacimiento_datos_personales,
					  estado_civil.nombre_estado_civil,
					  solicitud_prestamo.fecha_presentacion,
					  solicitud_prestamo.fecha_aprobacion,
					  solicitud_prestamo.id_estado_tramites,
					  solicitud_prestamo.identificador_consecutivos,
				      solicitud_prestamo.tipo_pago_cuenta_bancaria,
				      tipo_creditos.nombre_tipo_creditos,
				      usuarios.nombre_usuarios,
				      usuarios.correo_usuarios,
				      solicitud_prestamo.id_usuarios_oficial_credito_aprueba,
				      solicitud_prestamo.cedula_deudor_a_garantizar,
				      solicitud_prestamo.nombre_deudor_a_garantizar,
				      estado_tramites.nombre_estado_tramites_solicitud_prestamos";
	
		$tablas   = "public.solicitud_prestamo,
					  public.entidades,
					  public.sexo,
					  public.estado_civil,
				      public.tipo_creditos,
				public.usuarios,
				      public.estado_tramites";
			
		$where    = "solicitud_prestamo.id_estado_tramites= estado_tramites.id_estado_tramites AND solicitud_prestamo.id_usuarios_oficial_credito_aprueba=usuarios.id_usuarios AND tipo_creditos.id_tipo_creditos=solicitud_prestamo.id_tipo_creditos AND
		solicitud_prestamo.id_estado_civil_datos_personales = estado_civil.id_estado_civil AND
		entidades.id_entidades = solicitud_prestamo.id_entidades AND
		sexo.id_sexo = solicitud_prestamo.id_sexo_datos_personales  AND solicitud_prestamo.tipo_participe_datos_prestamo='Garante'";
	
		$id       = "solicitud_prestamo.id_solicitud_prestamo";
	
			
		//$where_to=$where;
			
			
		$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
		$search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
			
		if($action == 'ajax')
		{
			if(!empty($search)){
					
				$where1=" AND (solicitud_prestamo.numero_cedula_datos_personales LIKE '".$search."%' OR solicitud_prestamo.apellidos_solicitante_datos_personales LIKE '".$search."%' OR solicitud_prestamo.nombres_solicitante_datos_personales LIKE '".$search."%' OR tipo_creditos.nombre_tipo_creditos LIKE '".$search."%' OR solicitud_prestamo.cedula_deudor_a_garantizar LIKE '".$search."%'  OR usuarios.nombre_usuarios LIKE '".$search."%' OR  estado_tramites.nombre_estado_tramites_solicitud_prestamos LIKE '".$search."%')";
					
				$where_to=$where.$where1;
			}else{
					
				$where_to=$where;
			}
				
			$html="";
			$resultSet=$db->getCantidad("*", $tablas, $where_to);
			$cantidadResult=(int)$resultSet[0]->total;
	
			$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	
			$per_page = 10; //la cantidad de registros que desea mostrar
			$adjacents  = 9; //brecha entre páginas después de varios adyacentes
			$offset = ($page - 1) * $per_page;
			$limit = " LIMIT   '$per_page' OFFSET '$offset'";
			$resultSet=$db->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
			$count_query   = $cantidadResult;
			$total_pages = ceil($cantidadResult/$per_page);
	
			if($cantidadResult>0)
			{
				$html.='<div class="pull-left" style="margin-left:11px;">';
				$html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
				$html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
				$html.='</div>';
				$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
				$html.='<section style="height:350px; overflow-y:scroll;">';
				$html.= "<table id='tabla_solicitud_prestamos_registrados' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
				$html.= "<thead>";
				$html.= "<tr>";
				$html.='<th style="text-align: left;  font-size: 11px;">Cedula</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Apellidos</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Nombres</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Crédito</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Tipo</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Monto</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Plazo</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Cedula Deudor</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Nombre Deudor</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Trámite</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Fecha T</th>';
				$html.='<th style="text-align: left;  font-size: 11px;">Oficial C</th>';
				$html.='<th style="text-align: right;  font-size: 11px;"></th>';
				$html.='</tr>';
				$html.='</thead>';
				$html.='<tbody>';
					
				$i=0;
	
				foreach ($resultSet as $res)
				{
	
					$aprobado_oficial_credito=$res->id_estado_tramites;
					if($aprobado_oficial_credito==2){
						$estado_tramite='Guardado';
	
					}elseif($aprobado_oficial_credito==1){
						$estado_tramite='Pendiente';
					}
					elseif($aprobado_oficial_credito==3){
						$estado_tramite='Rechazado';
					}elseif($aprobado_oficial_credito==4){
						$estado_tramite='Revisado';
						
					}
	
					$html.='<tr>';
	
					$html.='<td style="font-size: 11px;">'.$res->numero_cedula_datos_personales.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->apellidos_solicitante_datos_personales.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->nombres_solicitante_datos_personales.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->nombre_tipo_creditos.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->tipo_participe_datos_prestamo.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->monto_datos_prestamo.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->plazo_datos_prestamo.' meses</td>';
					$html.='<td style="font-size: 11px;">'.$res->cedula_deudor_a_garantizar.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->nombre_deudor_a_garantizar.'</td>';
					$html.='<td style="font-size: 11px;">'.$estado_tramite.'</td>';
					if($aprobado_oficial_credito==1 || $aprobado_oficial_credito==4){
						$html.='<td style="font-size: 11px;"></td>';
						$html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
	
					}else{
						$html.='<td style="font-size: 11px;">'.date("d/m/Y", strtotime($res->fecha_aprobacion)).'</td>';
						$html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
						
					}
					$html.='<td style="font-size: 15px;"><span class="pull-right"><a href="index.php?controller=SolicitudPrestamo&action=print&id_solicitud_prestamo='.$res->id_solicitud_prestamo.'" target="_blank" class="btn btn-warning" style="font-size:65%;" title="Imprimir"><i class="glyphicon glyphicon-print"></i></a></span></td>';
					$html.='</tr>';
	
				}
	
				$html.='</tbody>';
				$html.='</table>';
				$html.='</section></div>';
				$html.='<div class="table-pagination pull-right">';
				$html.=''. $this->load_solicitud_garantias_registrados("index.php", $page, $total_pages, $adjacents).'';
				$html.='</div>';
	
			}else{
				$html.='<div class="col-lg-6 col-md-6 col-xs-12">';
				$html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
				$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				$html.='<h4>Aviso!!!</h4> <b>Actualmente no hay solicitud de garantías de prestamos registrados...</b>';
				$html.='</div>';
				$html.='</div>';
			}
	
			echo $html;
			die();
	
		}
	
	}
	
	
	
	public function searchadminsuper_desafiliacion(){
	    
	    session_start();
	    require_once 'core/DB_Functions.php';
	    $db = new DB_Functions();
	    
	    
	    $where_to="";
	    $columnas = " solicitud_prestaciones.id_solicitud_prestaciones, 
                      solicitud_prestaciones.apellidos_solicitud_prestaciones, 
                      solicitud_prestaciones.nombres_solicitud_prestaciones, 
                      solicitud_prestaciones.cedula_participes, 
                      solicitud_prestaciones.id_sexo, 
                      sexo.nombre_sexo, 
                      solicitud_prestaciones.fecha_nacimiento_solicitud_prestaciones, 
                      solicitud_prestaciones.id_estado_civil, 
                      estado_civil.nombre_estado_civil, 
                      solicitud_prestaciones.id_provincias, 
                      provincias.nombre_provincias, 
                      solicitud_prestaciones.id_cantones, 
                      cantones.nombre_cantones, 
                      solicitud_prestaciones.id_parroquias, 
                      parroquias.nombre_parroquias, 
                      solicitud_prestaciones.barrio_solicitud_prestaciones, 
                      solicitud_prestaciones.ciudadela_solicitud_prestaciones, 
                      solicitud_prestaciones.calle_solicitud_prestaciones, 
                      solicitud_prestaciones.numero_calle_solicitud_prestaciones, 
                      solicitud_prestaciones.interseccion_solicitud_prestaciones, 
                      solicitud_prestaciones.tipo_vivienda_solicitud_prestaciones, 
                      solicitud_prestaciones.vivienda_hipotecada_solicitud_prestaciones, 
                      solicitud_prestaciones.referencia_dir_solicitud_prestaciones, 
                      solicitud_prestaciones.telefono_solicitud_prestaciones, 
                      solicitud_prestaciones.celular_solicitud_prestaciones, 
                      solicitud_prestaciones.correo_solicitud_prestaciones, 
                      solicitud_prestaciones.nivel_educativo_solicitud_prestaciones, 
                      solicitud_prestaciones.nombres_referencia_familiar, 
                      solicitud_prestaciones.apellidos_referencia_familiar, 
                      solicitud_prestaciones.parentesco_referencia_familiar, 
                      solicitud_prestaciones.primer_telefono_referencia_familiar, 
                      solicitud_prestaciones.segundo_telefono_referencia_familiar, 
                      solicitud_prestaciones.nombres_referencia_personal, 
                      solicitud_prestaciones.apellidos_referencia_personal, 
                      solicitud_prestaciones.parentesco_referencia_personal, 
                      solicitud_prestaciones.primer_telefono_referencia_personal, 
                      solicitud_prestaciones.segundo_telefono_referencia_personal, 
                      solicitud_prestaciones.ultimo_cargo_solicitud_prestaciones, 
                      solicitud_prestaciones.fecha_salida_solicitud_prestaciones, 
                      solicitud_prestaciones.id_bancos, 
                      bancos.nombre_bancos, 
                      solicitud_prestaciones.numero_cuenta_ahorros_bancaria, 
                      solicitud_prestaciones.numero_cuenta_corriente_bancaria, 
                      solicitud_prestaciones.identificador_consecutivos, 
                      solicitud_prestaciones.creado, 
                      solicitud_prestaciones.modificado";
	    
	    $tablas   = " public.solicitud_prestaciones, 
                      public.sexo, 
                      public.estado_civil, 
                      public.provincias, 
                      public.cantones, 
                      public.parroquias, 
                      public.bancos";
	    
	    $where    = " sexo.id_sexo = solicitud_prestaciones.id_sexo AND
                      estado_civil.id_estado_civil = solicitud_prestaciones.id_estado_civil AND
                      provincias.id_provincias = solicitud_prestaciones.id_provincias AND
                      cantones.id_cantones = solicitud_prestaciones.id_cantones AND
                      parroquias.id_parroquias = solicitud_prestaciones.id_parroquias AND
                      bancos.id_bancos = solicitud_prestaciones.id_bancos";
	    
	    $id       = "solicitud_prestaciones.id_solicitud_prestaciones";
	    
	    
	    //$where_to=$where;
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        if(!empty($search)){
	            
	            $where1=" AND (solicitud_prestaciones.cedula_participes LIKE '".$search."%' OR solicitud_prestaciones.nombres_solicitud_prestaciones LIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	        }
	        
	        $html="";
	        $resultSet=$db->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        $resultSet=$db->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult>0)
	        {
	            $html.='<div class="pull-left" style="margin-left:11px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:350px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_solicitud_prestamos_registrados' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 11px;">Cedula</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Apellidos</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Nombres</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Estado Civil</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Parroquia</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Teléfono</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Celular</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Número Cuenta</th>';
	            $html.='<th style="text-align: left;  font-size: 11px;">Banco</th>';
	            $html.='<th style="text-align: right;  font-size: 11px;"></th>';
	            $html.='<th style="text-align: right;  font-size: 11px;"></th>';
	            $html.='<th style="text-align: right;  font-size: 11px;"></th>';
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	
	            foreach ($resultSet as $res)
	            {
	                
	                $html.='<tr>';
	                
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellidos_solicitud_prestaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombres_solicitud_prestaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado_civil.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_parroquias.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->telefono_solicitud_prestaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->celular_solicitud_prestaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_cuenta_ahorros_bancaria.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_bancos.'</td>';
	                $html.='<td style="font-size: 15px;"><span class="pull-right"><a href="index.php?controller=SolicitudPrestamo&action=print_desafiliacion&id_solicitud_prestaciones='.$res->id_solicitud_prestaciones.'" target="_blank" class="btn btn-warning" title="Imprimir"><i class="glyphicon glyphicon-print"></i></a></span>';
	                $html.='<td style="font-size: 15px;"><button class="btn btn-primary pull-right" title="Registrar crédito"  onclick="EnviarInfoDasafiliacion(&quot;'.$res->cedula_participes.'&quot;,'.$res->id_solicitud_prestaciones.')"><i class="glyphicon glyphicon-import"></i></button>';
	                
	                $html.='</tr>';
	                
	            }
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->load_solicitud_garantias_registrados("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	        }else{
	            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay solicitud de desafiliaciones registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
	
	public function load_solicitud_cesantes_registrados($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_solicitud_cesantes_registrados(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_solicitud_cesantes_registrados(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_cesantes_registrados(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_cesantes_registrados(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_cesantes_registrados(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_solicitud_cesantes_registrados($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_solicitud_cesantes_registrados(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	
	
	
	
	
}
?>