<?php


	require_once '../core/DB_FunctionsRfid.php';
	$db = new DB_FunctionsRfid();
	
	
	$accion=(isset($_POST['action']))?$_POST['action']:'';
	$_cedula_usuarios  = (isset($_POST['cedula_usuarios']))?$_POST['cedula_usuarios']:'';	
	$_clave_usuarios  = (isset($_POST['clave_usuarios']))?$_POST['clave_usuarios']:'';

	
	if($accion=="consulta"){
		if($_cedula_usuarios!="" && $_clave_usuarios!=""){
			
		$_clave=$db->encriptar($_clave_usuarios);
			
		$columnas="usuarios.id_usuarios,
                      usuarios.cedula_usuarios, 
                      usuarios.nombre_usuarios, 
                      usuarios.apellidos_usuarios, 
                      usuarios.correo_usuarios, 
                      usuarios.celular_usuarios, 
                      usuarios.telefono_usuarios, 
                      usuarios.fecha_nacimiento_usuarios, 
                      usuarios.usuario_usuarios,
                      usuarios.id_estado,
                      claves.clave_claves, 
                      claves.estado_claves,
                      rol.id_rol,
                      estado.nombre_estado";
    		
    		$tablas="public.claves, 
                      public.usuarios,
                     public.rol,
                     public.estado";
    		
    		$where="usuarios.id_usuarios = claves.id_usuarios
                    AND rol.id_rol = usuarios.id_rol 
                    AND estado.id_estado = usuarios.id_estado
                    AND usuarios.id_estado=1
                    AND claves.estado_claves=1
                    AND usuarios.cedula_usuarios='$_cedula_usuarios' AND claves.clave_claves='$_clave' ";
    		
    		$id="usuarios.cedula_usuarios";
    	
    		$result=$db->getCondiciones($columnas, $tablas, $where, $id);
    		
    		
    		$rowfoto = new stdClass();
    		
    		if ( !empty($result) )
    		{ 
    		
    		
    			foreach($result as $res) 
    			{
	    			
	    			$rowfoto->id_usuarios = $res->id_usuarios;
	    			$rowfoto->cedula_usuarios = $res->cedula_usuarios;
	    			$rowfoto->nombre_usuarios = $res->nombre_usuarios;
	    			$rowfoto->correo_usuarios = $res->correo_usuarios;
	    			$rowfoto->id_rol = $res->id_rol;
	    			$rowfoto->id_estado = $res->id_estado;
	    			//$rowfoto->fotografia_usuarios=base64_encode(pg_unescape_bytea($res->fotografia_usuarios));//$res->foto_fichas_fotos;
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	?>
		