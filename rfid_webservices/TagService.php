<?php


	require_once '../core/DB_FunctionsRfid.php';
	$db = new DB_FunctionsRfid();
	
	$resultado="";
	$accion=(isset($_POST['action']))?$_POST['action']:'';
	$_texto_tag  = (isset($_POST['texto_tag']))?$_POST['texto_tag']:'';	
	$da = new ConectarServiceRfid();
	$conn = $da->conexion();
	
	$_nombre_oficina				= "";
	$_nombre_tipo_activos_fijos				= "";
	$_nombre_activos_fijos				= "";
	$_codigo_activos_fijos				= "";
	$_fecha_activos_fijos				= "";
	$_imagen_activos_fijos				= "";
	$_nombres_empleados				= "";
	$_numero_rfid_tag				= "";
	
	
	if($accion=="insertar"){
		
		
		if($_texto_tag!="" ){
		
			
			$sql="SELECT ins_rfid_tag('$_texto_tag');";
		
			
			$query_new_insert = pg_query($conn,$sql);
			 
			
	    	echo json_encode("OK");
	    	die();
    		
		}else{
			// no vienen los datos
			$resultadosJson = "";
			die();
		}
	   
			
	}
	
	if($accion=="inventario"){
		
		
		if($_texto_tag!="" ){
		
			
			$columnas=" oficina.nombre_oficina, 
					  tipo_activos_fijos.nombre_tipo_activos_fijos, 
					  act_activos_fijos.nombre_activos_fijos, 
					  act_activos_fijos.codigo_activos_fijos, 
					  act_activos_fijos.fecha_activos_fijos, 
					  act_activos_fijos.imagen_activos_fijos, 
					  empleados.nombres_empleados, 
					  rfid_tag.numero_rfid_tag";
    		
    		$tablas="  public.act_activos_fijos, 
					  public.empleados, 
					  public.oficina, 
					  public.tipo_activos_fijos, 
					  public.rfid_tag";
    		
    		$where=" empleados.id_empleados = act_activos_fijos.id_empleados AND
  oficina.id_oficina = act_activos_fijos.id_oficina AND
  tipo_activos_fijos.id_tipo_activos_fijos = act_activos_fijos.id_tipo_activos_fijos AND
  rfid_tag.id_rfid_tag = act_activos_fijos.id_rfid_tag AND rfid_tag.numero_rfid_tag ='$_texto_tag'";
    		
    		$id="oficina.nombre_oficina, act_activos_fijos.nombre_activos_fijos";
    		
    		

    		
    		$result=$db->getCondiciones($columnas, $tablas, $where, $id);
    		
    		
    		$rowfoto = new stdClass();
    		
    		if ( !empty($result) )
    		{ 
    		
    		
    			foreach($result as $res) 
    			{
    				    				
	    			$rowfoto->nombre_oficina = $res->nombre_oficina;
	    			$rowfoto->nombre_tipo_activos_fijos = $res->nombre_tipo_activos_fijos;
	    			$rowfoto->nombre_activos_fijos = $res->nombre_activos_fijos;
	    			$rowfoto->codigo_activos_fijos = $res->codigo_activos_fijos;
	    			$rowfoto->fecha_activos_fijos = $res->fecha_activos_fijos;
	    			$rowfoto->nombres_empleados = $res->nombres_empleados;
	    			$rowfoto->numero_rfid_tag = $res->numero_rfid_tag;
	    			$rowfoto->imagen_activos_fijos=base64_encode(pg_unescape_bytea($res->imagen_activos_fijos));//$res->foto_fichas_fotos;
	    			$listUsr[]=$rowfoto;
	    		
	    			echo json_encode($listUsr);
	    			die();
	    			
    			}	
					
				
			}else{
				// no existe el usuarios va vacio.
				$resultadosJson = "SIN DATOS";
				echo json_encode($resultadosJson);
				die();
			}
    		
		}else{
			// no vienen los datos
			$resultadosJson = "";
			die();
		}
	   
			
	}
	
	