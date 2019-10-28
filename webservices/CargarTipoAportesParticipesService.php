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
                          core_contribucion_tipo_participes.porcentaje_contribucion_tipo_participes";
				
				$tablas   = " public.core_participes, 
                              public.core_contribucion_tipo_participes, 
                              public.core_contribucion_tipo, 
                              public.core_tipo_aportacion";
				
				$where    = " core_contribucion_tipo_participes.id_participes = core_participes.id_participes AND
                              core_contribucion_tipo_participes.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo AND
                              core_contribucion_tipo_participes.id_tipo_aportacion = core_tipo_aportacion.id_tipo_aportacion AND 
                              core_contribucion_tipo_participes.id_estado=89 AND core_contribucion_tipo.id_contribucion_tipo=1
                              AND core_participes.cedula_participes='$cedula'";
				
		        $id       = "core_participes.cedula_participes";
				
				
					$html="";
				
					$resultSet=$db->getCondiciones($columnas, $tablas, $where, $id);
					 
					if(!empty($resultSet))
					{
					    
					
						$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
						$html.='<section style="height:380px; overflow-y:scroll;">';
						$html.= "<table id='tabla_particpes' class=''>";
						$html.= "<thead>";
						$html.= "<tr>";
						$html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
						$html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
						$html.='</tr>';
						$html.='</thead>';
						$html.='<tbody>';
						 
						$i=0;
				
						foreach ($resultSet as $res)
						{
								
							
							$html.='<tr>';
							$html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
							$html.='<td style="font-size: 11px;">'.$res->apellido_participes.' '.$res->nombre_participes.'</td>';
							$html.='</tr>';
						}
				
				
						$html.='</tbody>';
						$html.='</table>';
						$html.='</section></div>';
				
				
				
						 
					}else{
						$html.='<div class="col-lg-6 col-md-6 col-xs-12">';
						$html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
						$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
						$html.='<h4>Aviso!!!</h4> <b>Actualmente no tiene registrado tipo de aporte...</b>';
						$html.='</div>';
						$html.='</div>';
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
			
			
				
		}		
			
			
	}
	
	





	
?>