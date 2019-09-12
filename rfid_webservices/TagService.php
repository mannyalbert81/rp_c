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
		
			
			$columnas=" act_activos_fijos.id_activos_fijos,
					oficina.nombre_oficina, 
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
    				    				
    				$rowfoto->id_activos_fijos = $res->id_activos_fijos;
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
	

	
	if($accion=="oficinas"){
	
	
				
			$columnas="  oficina.nombre_oficina, 
  						oficina.id_oficina";
	
			$tablas="   public.act_activos_fijos, 
  						public.oficina";
	
			$where=" oficina.id_oficina = act_activos_fijos.id_oficina GROUP BY oficina.nombre_oficina, oficina.id_oficina		";
	
			$id="oficina.nombre_oficina";
	
	
	
	
			$result=$db->getCondiciones($columnas, $tablas, $where, $id);
	
			$rowfoto = new stdClass();
			$rowfoto->nombre_oficina = "Seleccione una Oficina";
			$rowfoto->id_oficina = "0";
			$listUsr[]=$rowfoto;
	
			if ( !empty($result) )
			{
				
	
				foreach($result as $res)
				{
					$rowfoto = new stdClass();
					$rowfoto->nombre_oficina = $res->nombre_oficina;
					$rowfoto->id_oficina = $res->id_oficina;
					$listUsr[]=$rowfoto;
				}
				
				echo json_encode($listUsr);
				die();
	
			}else{
				// no existe el usuarios va vacio.
				$resultadosJson = "SIN DATOS";
				echo json_encode($resultadosJson);
				die();
			}
	
	
			
	}
	
	
	
	if($accion=="insertarTomaFisica"){
	
		//ins_act_tomafisica_cabeza(
		
		
		
		$_tipo_tabla  =(isset($_POST['tipo_tabla']))?$_POST['tipo_tabla']:'';
		
		
		if($_tipo_tabla =="cabeza" ){
			
			$_id_usuarios  = (isset($_POST['id_usuarios']))?$_POST['id_usuarios']:'';
			$_cantidad_item_tomafisica_cabeza = (isset($_POST['cantidad_item_tomafisica_cabeza']))?$_POST['cantidad_item_tomafisica_cabeza']:'';
			$_nombre_oficina =  (isset($_POST['nombre_oficina']))?$_POST['nombre_oficina']:'';
			$_id_oficina = 0;
			
			

			$columnas="  id_oficina";
			$tablas="  public.oficina";
			$where=" nombre_oficina = '$_nombre_oficina' ";
			$id="id_oficina";
			$result=$db->getCondiciones($columnas, $tablas, $where, $id);
			if ( !empty($result) )
			{
				foreach($result as $res)
				{
					$_id_oficina = $res->id_oficina;
				}
			
			}
			
			$sql="SELECT ins_act_tomafisica_cabeza('$_id_usuarios' , '$_cantidad_item_tomafisica_cabeza' , '$_id_oficina');";
			$query_new_insert = pg_query($conn,$sql);
			echo json_encode("OK");
			die();
	
		}
		
	
		if($_tipo_tabla =="detalle" ){
			
			$_id_oficina_toma_fisica = 0;
			$_id_oficina_pertenece = 0;
			
			//public.ins_act_tomafisica_detalle(
			$_id_act_activos_fijos = (isset($_POST['id_act_activos_fijos']))?$_POST['id_act_activos_fijos']:'';
			$_nombre_oficina =  (isset($_POST['nombre_oficina']))?$_POST['nombre_oficina']:'';
			
			
			
			///oficina de la toma fisica
			$columnas="  id_oficina";
			$tablas="  public.oficina";
			$where=" nombre_oficina = '$_nombre_oficina' ";
			$id="id_oficina";
			$result=$db->getCondiciones($columnas, $tablas, $where, $id);
			if ( !empty($result) )
			{
				foreach($result as $res)
				{
					$_id_oficina_pertenece = $res->id_oficina;
				}
					
			}
			
			///oficina al que pertenece
			$columnas="  id_oficina";
			$tablas="  act_activos_fijos";
			$where=" id_activos_fijos = '$_id_act_activos_fijos' ";
			$id="id_oficina";
			$result=$db->getCondiciones($columnas, $tablas, $where, $id);
			if ( !empty($result) )
			{
				foreach($result as $res)
				{
					$_id_oficina_toma_fisica = $res->id_oficina;
				}
					
			}
			
			//id de la cabeza
			$_id_act_tomafisica_cabeza = 0;
			$columnas="  id_tomafisica_cabeza";
			$tablas="  act_tomafisica_cabeza";
			$where=" 1 = 1 ";
			$id="id_tomafisica_cabeza";
			$result=$db->getCondiciones($columnas, $tablas, $where, $id);
			if ( !empty($result) )
			{
				foreach($result as $res)
				{
					$_id_act_tomafisica_cabeza = $res->id_tomafisica_cabeza;
				}
					
			}
		/*
			public.ins_act_tomafisica_detalle(
					_id_act_activos_fijos integer,
					_id_oficina_pertenece integer,
					_id_oficina_toma_fisica integer,
					_id_act_tomafisica_cabeza integer)
					*/
			$sql="SELECT ins_act_tomafisica_detalle('$_id_act_activos_fijos' , '$_id_oficina_pertenece' , '$_id_oficina_toma_fisica' , '$_id_act_tomafisica_cabeza' );";
			$query_new_insert = pg_query($conn,$sql);
			echo json_encode("OK");
			die();
		
		}
		
		
		
			
	}
	
	
	