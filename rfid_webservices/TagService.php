<?php

	require_once '../core/DB_Functions.php';
	$db = new DB_Functions();
	$resultado="";
	$accion=(isset($_POST['action']))?$_POST['action']:'';
	$_texto_tag  = (isset($_POST['texto_tag']))?$_POST['texto_tag']:'';	

	$da = new ConectarService();
	$conn = $da->conexion();
	
	
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
	
	
	




	