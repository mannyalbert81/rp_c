<?php
$id_valor = 0;
$id_nombre = 0;
$tabla = "";
$campo = "";

if (isset ($_GET["tabla"]))
{
	$tabla = $_GET["tabla"];

}
if (isset ($_GET["campo"]))
{
	$campo = $_GET["campo"];

}
if (isset ($_GET["id_nombre"]))
{
	$id_nombre = $_GET["id_nombre"];

}
if (isset ($_GET["id_valor"]))
{
	$id_valor = $_GET["id_valor"];

}
$image = "";
$conn  = pg_connect("user=postgres port=5432 password=Programadores2018 dbname=rp_capremci host=186.4.157.125");
if(!$conn)
{
	echo  "No se pudo conectar";
	
}
else 
{

		
		$res = pg_query($conn, "SELECT ".$campo." FROM ".$tabla." WHERE ".$id_nombre." = '$id_valor' ");
		
		
		if (!empty($res))
		{
				$raw = pg_fetch_result($res, $campo );
				
				
					header('Content-type: application/pdf');
					echo pg_unescape_bytea($raw);
				
				
			
		}
	
	pg_close($conn);
	
}



?>

