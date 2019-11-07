<?php

require_once '../core/DB_FunctionsRfid.php';
$db = new DB_FunctionsRfid();


if(isset($_GET['action'])){
	
	if(isset($_GET['cargar'])){
			
		$cargar=$_GET["cargar"];
		
			if($cargar=='cargar')
			{
				
			    $cedula=$_GET["cedula"];
			    
				
				$columnas = " core_participes.apellido_participes, 
                          core_participes.nombre_participes, 
                          core_participes.cedula_participes, 
                          core_participes.id_participes, 
                          core_contribucion_tipo.nombre_contribucion_tipo, 
                          core_contribucion_tipo_participes.valor_contribucion_tipo_participes, 
                          core_contribucion_tipo_participes.sueldo_liquido_contribucion_tipo_participes, 
                          core_tipo_aportacion.id_tipo_aportacion, 
                          core_tipo_aportacion.nombre_tipo_aportacion, 
                          core_contribucion_tipo_participes.porcentaje_contribucion_tipo_participes,
                          estado.nombre_estado";
				
				$tablas   = " public.core_participes, 
                              public.core_contribucion_tipo_participes, 
                              public.core_contribucion_tipo, 
                              public.core_tipo_aportacion,
                              public.estado";
				
				$where    = " core_contribucion_tipo_participes.id_participes = core_participes.id_participes AND
                              core_contribucion_tipo_participes.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo AND
                              core_contribucion_tipo_participes.id_tipo_aportacion = core_tipo_aportacion.id_tipo_aportacion AND
                              estado.id_estado = core_contribucion_tipo_participes.id_estado AND 
                              core_contribucion_tipo_participes.id_estado=89 AND core_contribucion_tipo.id_contribucion_tipo=1
                              AND core_participes.cedula_participes='$cedula'";
				
		        $id       = "core_participes.cedula_participes";
				
				
					$html="";
				
					$resultSet=$db->getCondiciones($columnas, $tablas, $where, $id);
					 
					if(!empty($resultSet))
					{
					    
					
						$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
						$html.='<section style="height:auto; overflow-y:scroll;">';
						$html.= "<table id='tabla_particpes' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
						$html.= "<thead>";
						$html.= "<tr>";
						$html.='<th style="text-align: left;  font-size: 12px;">#</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">CONTRIBUCION</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">TIPO</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">VALOR</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">PORCENTAJE</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">ESTADO</th>';
						$html.='</tr>';
						$html.='</thead>';
						$html.='<tbody>';
						 
						$i=0;
				
						foreach ($resultSet as $res)
						{
								
							$i++;
							$html.='<tr>';
							$html.='<td style="font-size: 11px;">'.$i.'</td>';
							$html.='<td style="font-size: 11px;">'.$res->nombre_contribucion_tipo.'</td>';
							$html.='<td style="font-size: 11px;">'.$res->nombre_tipo_aportacion.'</td>';
							$html.='<td style="font-size: 11px;">'.$res->valor_contribucion_tipo_participes.'</td>';
							$html.='<td style="font-size: 11px;">'.$res->porcentaje_contribucion_tipo_participes.'</td>';
							$html.='<td style="font-size: 11px;">'.$res->nombre_estado.'</td>';
							$html.='</tr>';
						}
				
				
						$html.='</tbody>';
						$html.='</table>';
						$html.='</section></div>';
				
				
				
						 
					}else{
					    $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
					    $html.='<section style="height:auto; overflow-y:scroll;">';
					    $html.= "<table id='tabla_particpes' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
					    $html.= "<thead>";
					    $html.= "<tr>";
					    $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
					    $html.='<th style="text-align: left;  font-size: 12px;">CONTRIBUCION</th>';
					    $html.='<th style="text-align: left;  font-size: 12px;">TIPO</th>';
					    $html.='<th style="text-align: left;  font-size: 12px;">VALOR</th>';
					    $html.='<th style="text-align: left;  font-size: 12px;">PORCENTAJE</th>';
					    $html.='<th style="text-align: left;  font-size: 12px;">ESTADO</th>';
					    $html.='</tr>';
					    $html.='</thead>';
					    $html.='<tbody>';
					    $html.='<tr>';
					    $html.='<td colspan="6" style="text-align: center;  font-size: 12px;">Aporte No definido</th>';
					    $html.='</tr>';
					    $html.='</tbody>';
					    $html.='</table>';
					    $html.='</section></div>';
					}
					
				
				   $resultadosJson = json_encode($html);
				   echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';
				
			
			
			}
			
			
			
			if($cargar=='selec_tipo_aportaciones')
			{
			    
			    $columnas1 = "
                          core_tipo_aportacion.id_tipo_aportacion,
                          core_tipo_aportacion.nombre_tipo_aportacion
                          ";
			    
			    $tablas1   = "
                              public.core_tipo_aportacion";
			    
			    $where1    = " 1=1";
			    
			    $id1       = "core_tipo_aportacion.id_tipo_aportacion";
			    
			    
			    $html="";
			    
			    $resultSet1=$db->getCondiciones($columnas1, $tablas1, $where1, $id1);
			    
			    if(!empty($resultSet1))
			    {
			     		    
			        $resultadosJson1 = json_encode(array("data"=>$resultSet1));
			        echo $_GET['jsoncallback'] . '(' . $resultadosJson1 . ');';
			    
			        
			        
			    }
			    
			    
			    
			   /*
			    if(!empty($resultSet1) && count($resultSet1)>0){
			        
			        echo json_encode(array('data'=>$resultSet1));
			        
			        
			        
			        
			    }*/
			    
			}
			
			
			
			if($cargar=='cargar_cedula_participe')
			{
			    
			    
			   
			    $cedula=$_GET["cedula"];
			    
			    
			    
			    
			    $resultadosJson = json_encode($cedula);
			    echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';
			    
			    
			    
			}
			
				
		}		
			
			
	}
	
	
	if( isset($_GET['metodo']) && $_GET['metodo'] == "GUARDAR"){
	    
	    echo json_encode(array("respuesta"=>2));
	    
	    exit();
	}
	
	//echo $_SERVER['REQUEST_METHOD'];

	//header("HTTP/1.1 400 Bad Request");



	
?>