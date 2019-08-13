<?php
class EntidadBase_128{
    private $table;
    private $db;
    private $conectar;
    
    
    public function __construct() {
        
        
        
        require_once 'Conectar_128.php';
        $this->conectar=new Conectar_128();
        $this->db=$this->conectar->conexion();

        $this->fluent=$this->getConetar()->startFluent();
        $this->con=$this->getConetar()->conexion();
     }
    
     
    public function fluent(){
    	return $this->fluent;
    }
    
    public function con(){
    	return $this->con;
    }
    
    
    public function getConetar(){
        return $this->conectar;
    }
    
    public function db(){
        return $this->db;
    }
    
    public function getNuevo($secuencia){
    
    	$query=pg_query($this->con, "SELECT NEXTVAL('$secuencia')");
    	 
    	$resultSet = array();
    	 
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    	return $resultSet;
    }
    
    public function getAll($id){
        
    	$query=pg_query($this->con, "SELECT * FROM $this->table ORDER BY $id ASC");
    	$resultSet = array();
    	
           while ($row = pg_fetch_object($query)) {
             $resultSet[]=$row;
           }
        return $resultSet;
    }
    
    
    
    
    public function getContador($contador){
    
    	$query=pg_query($this->con, "SELECT $contador FROM $this->table ");
    	$resultSet = array();
    	 
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    	return $resultSet;
    }
    
    public function getCantidad($columna,$tabla,$where){
    
    	//parametro $columna puede ser todo (*) o una columna especifica
    	$query=pg_query($this->con, "SELECT COUNT($columna) AS total FROM $tabla WHERE $where ");
    	$resultSet = array();
    
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    	return $resultSet;
    }    
    
    
    public function getById($id){
    	
    	$query=pg_query($this->con, "SELECT * FROM $this->table WHERE id=$id");
        $resultSet = array();
    	
           while ($row = pg_fetch_object($query)) {
             $resultSet[]=$row;
           }
        return $resultSet;
    }
    
    public function getBy($where){
    	
    	$query=pg_query($this->con, "SELECT * FROM $this->table WHERE   $where ");
        $resultSet = array();
    	
           while ($row = pg_fetch_object($query)) {
             $resultSet[]=$row;
           }
        return $resultSet;
    }
    
    public function deleteById($id){
    	
        $query=pg_query($this->con,"DELETE FROM $this->table WHERE $id"); 
        return $query;
    }
    
    public function deleteBy($column,$value){

    	try 
    	{
    		$query=pg_query($this->con,"DELETE FROM $this->table WHERE $column='$value' ");
    	}
    	catch (Exception $e)
    	{
    	    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    	} 
    	
        return $query;
    }
    

    public function getCondiciones($columnas ,$tablas , $where, $id){
    	
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where ORDER BY $id  ASC");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    
    public function getCondicionesValorMayor($columnas ,$tablas , $where){
    	 
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    
    
    
    public function getCondicionesDesc($columnas ,$tablas , $where, $id){
    	 
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where ORDER BY $id  DESC");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
   
    
    public function getCondiciones_grupo($columnas ,$tablas , $where, $grupo, $id){
    	 
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where GROUP BY $grupo ORDER BY $id  ASC");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
  
    public function getCondicionesPag($columnas ,$tablas , $where, $id, $limit){
    	 
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where ORDER BY $id  ASC  $limit");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    
    
    public function getCondicionesPagDesc($columnas ,$tablas , $where, $id, $limit){
    
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where ORDER BY $id  DESC  $limit");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    
    public function UpdateBy($colval ,$tabla , $where){
    	try 
    	{ 
    	     $query=pg_query($this->con, "UPDATE $tabla SET  $colval   WHERE $where ");
    	     
    	}
    	catch (Exception  $Ex)
    	{
    	    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    		
    	}
    }
    
    
    
    public function getByPDF($columnas, $tabla , $where){
    
    	if ($tabla == "")
    	{
    		$query=pg_query($this->con, "SELECT $columnas FROM $this->table WHERE   $where ");
    	}
    	else
    	{
    		$query=pg_query($this->con, "SELECT $columnas FROM $tabla WHERE   $where ");
    	}
    	
    	return $query;
    }
    
    public function getCondicionesPDF($columnas ,$tablas , $where, $id){
    	 
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where ORDER BY $id  ASC");
    
    	return $query;
    }
    
    
    
    /*
     * Aqui podemos montarnos un monton de métodos que nos ayuden
     * a hacer operaciones con la base de datos de la entidad
     */
    
    public function encriptar($cadena){
    	$key='rominajasonrosabal';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
    	$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
    	return $encrypted; //Devuelve el string encriptado
    
    }
    
    public function desencriptar($cadena){
    	$key='rominajasonrosabal';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
    	$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($cadena), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    	return $decrypted;  //Devuelve el string desencriptado
    }
    
    public function registrarSesion($id_usuario, $usuario_usuario, $id_rol, $nombre_usuario, $correo_usuario, $ip_usuario, $cedula_usuarios)
    {
    	session_start();
    	$_SESSION["cedula_usuarios"]=$cedula_usuarios;
    	$_SESSION["id_usuarios"]=$id_usuario;
    	$_SESSION["usuario_usuario"]=$usuario_usuario;
    	$_SESSION["id_rol"]=$id_rol;
    	$_SESSION["nombre_usuarios"]=$nombre_usuario;
    	$_SESSION["correo_usuarios"]=$correo_usuario;
    	$_SESSION["ip_usuarios"]=$ip_usuario; 	

    	if (substr($ip_usuario, 0, 3) == "192" )
    	{
    		$_SESSION["tipo_usuario"]="usuario_local";
    	}
    	else   ///usuarios externo 
    	{
    		
    		$_SESSION["tipo_usuario"]="usuario_externo";
    	}
    		
    	
    }
    
    
    
    public function registrarSesionParticipe($cedula_participe)
    {
    	
    	$_SESSION["cedula_participe"]=$cedula_participe;
    	
    	 
    }
    
    
    public function getPermisosVer($where){
    	 
    	$query=pg_query($this->con, "SELECT permisos_rol.ver_permisos_rol FROM public.controladores, public.permisos_rol WHERE  controladores.id_controladores = permisos_rol.id_controladores AND  ver_permisos_rol = 'TRUE'   AND   $where ");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }

    
    public function getPermisosEditar($where){
    
    	$query=pg_query($this->con, "SELECT permisos_rol.editar_permisos_rol FROM public.controladores, public.permisos_rol WHERE  controladores.id_controladores = permisos_rol.id_controladores AND  editar_permisos_rol = 'TRUE'   AND   $where ");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    

    public function getPermisosBorrar($where){
    
    	$query=pg_query($this->con, "SELECT permisos_rol.borrar_permisos_rol FROM public.controladores, public.permisos_rol WHERE  controladores.id_controladores = permisos_rol.id_controladores AND  borrar_permisos_rol = 'TRUE'   AND   $where ");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    
    
    
    
    public function  EnviarMailSolCred($correo_participe, $id_usuario, $_nombres_solicitante_datos_personales, $_apellidos_solicitante_datos_personales){
    	
    	$usuarios = new UsuariosModel();
    	$where = "id_usuarios = '$id_usuario'";
    	$resultUsu = $usuarios->getBy($where);
    	
    	if(!empty($resultUsu))
    	{
    	
    		foreach ($resultUsu as $res){
    	
    			$correo_usuario   =$res->correo_usuarios;
    			$nombre_usuario   = $res->nombre_usuarios;
    		}
    	
    		$cabeceras = "MIME-Version: 1.0 \r\n";
    		$cabeceras .= "Content-type: text/html; charset=utf-8 \r\n";
    		$cabeceras .= "From: $correo_usuario \r\n";
    		$destino="$correo_participe";
    		$asunto="Solicitud de Prestamo (Capremci)";
    		$fecha=date("d/m/y");
    		$hora=date("H:i:s");
    		
    		
    		$resumen="
    		<table rules='all'>
    		<tr><td WIDTH='1000' HEIGHT='50'><center><img src='http://186.4.157.125:80/webcapremci/view/images/bcaprem.png' WIDTH='300' HEIGHT='120'/></center></td></tr>
    		</tabla>
    		<p><table rules='all'></p>
    		<tr style='background: #FFFFFF;'><td  WIDTH='1000' align='center'><b>Estimado Participe $_apellidos_solicitante_datos_personales $_nombres_solicitante_datos_personales</b></td></tr></p>
    		<tr style='background: #FFFFFF;'><td  WIDTH='1000' align='justify'>Envieme la siguiente información para agilizar el proceso de su solicitud de prestamo.</td></tr>
    		</tabla>
    		
    		<p><table rules='all'></p>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>1.-</b> 3 últimos roles de pago firmados por su entidad pagadora.</td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>2.-</b> Certificado de tiempo de servicio.</td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>3.-</b> Copia de cédula y papeleta de votación (4 febrero 2018).</td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>4.-</b> Copia planilla de servicio básico (Actualizada).</td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>5.-</b> Copia de libreta de ahorros.</td></tr>
    		</tabla>
    		
    		
    		<p><table rules='all'></p>
    		<tr style='background: #FFFFFF'><td WIDTH='1000' align='center'><b> TU OFICIAL DE CRÉDITO ASIGNADO ES: </b></td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>Nombre:</b> $nombre_usuario</td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>Correo:</b> $correo_usuario </td></tr>
    		</tabla>
    		<p><table rules='all'></p>
    		<tr style='background:#1C1C1C'><td WIDTH='1000' HEIGHT='50' align='center'><font color='white'>Capremci - <a href='http://www.capremci.com.ec'><FONT COLOR='#7acb5a'>www.capremci.com.ec</FONT></a> - Copyright © 2018-</font></td></tr>
    		</table>
    		";
    		
    		mail("$destino","Solicitud de Prestamo (Capremci)","$resumen","$cabeceras");
    		
    			
    	}
    	
    	
    }
    
    
    
    
    
    public  function  SendMail($para, $titulo, $lista, $imagen, $asunto)
    {
    
    	 
    
    
    
    	// Varios destinatarios
    
    	$para  = 'maycol@masoft.net' . ', '; // atención a la coma
    	$para .= 'danny@masoft.net' . ', ';
		$para .= 'manuel@masoft.net' . ', ';
		$para .= 'steven@masoft.net';
		
    
    	//$para  = 'desarrollo@masoft.net' . ', '; // atención a la coma
    	//$para .= 'manuel@masoft.net';
    
    	 
    	 
    	// título
    	$título = $asunto;
    	 
    	// mensaje
    	$mensaje_cabeza = '
    
    <html>
		<head>
			<title>INCIDENCIAS</title>
    		<meta charset="UTF-8">
			    </head>
				     <body style="background-color:#d9e3e4">
				   <div style="background-color:#d93e1b">
				<rigth><img src="http://18.221.171.210:80/coactiva_liventy/FrameworkMVC/view/images/logo-coctiva.png" WIDTH="200" HEIGHT="80" /></rigth>
	             </div>
    			<h2><center><b>INCIDENCIAS</b></center></h2>
    
    			<TABLE rules="all" WIDTH="100%">
    
    			<TR>
									<TD WIDTH=200 bgcolor="#A4A4A4">
									  <h4><center><b>Nombre</b>
									</TD>
					
									<TD WIDTH=200 bgcolor="#A4A4A4">
									  <h4><center><b>Correo</b>
									</TD>
    
									<TD WIDTH=200 bgcolor="#A4A4A4">
									  <h4><center><b>Rol</b>
									</TD>
					
									<TD WIDTH=200 bgcolor="#A4A4A4">
									  <h4><center><b>Descripción</b>
									</TD>
    								<TD WIDTH=200 bgcolor="#A4A4A4">
									  <h4><center><b>Fecha</b>
									</TD>
    
									
								</TR>
            
								  ';
    
    	$mensaje_detalle = "";
    	
    	foreach($lista as $res)
    	{
    		
    		$mensaje_detalle .=  '<td><center>'. $res->nombre_usuarios .'   </td>' ;
    		$mensaje_detalle .=  '<td><center>'. $res->correo_usuarios .'   </td>' ;
    		$mensaje_detalle .=  '<td><center>'. $res->nombre_rol .'   </td>' ;
    		$mensaje_detalle .=  '<td><justify>'. $res->descripcion_incidencia .'   </td>' ;
    		$mensaje_detalle .=  '<td><center>'. $res->creado .'   </td>' ;
    		
    	}
    	
    	
    	
    
    	$mensaje_pie =  '</table>
    
				<br/>
				<br/>
				<br/>
    			
    			
				</body>
				</html>
				';
    
    	
    	$mensaje_cabeza1 = '
    	
    <html>
			<body>
				
			<TABLE rules="all" WIDTH="100%">
    	
    			
    	
								  ';
    	
    	$mensaje_detalle1 = "";
    	 
    	foreach($lista as $res)
    	{
    	$mensaje_detalle1 .=  '<td><center><input type="image" name="image" src="http://18.221.171.210:80/coactiva_liventy/FrameworkMVC/view/DevuelveImagen.php?id_valor='.$res->id_incidencia.'&id_nombre=id_incidencia&tabla=incidencia&campo=imagen_incidencia" alt="'.$res->id_incidencia.'" width="600" height="550"></td>' ;
    	
    	}
    	 
    	 
    	 
    	
    	$mensaje_pie1 =  '</table>
    	
				<br/>
				<br/>
				<br/>
    
    			<TABLE WIDTH="100%">
				<TR>
                <tr style="background:#1C1C1C"><td WIDTH="1000" HEIGHT="50" align="center"><font color="white">All Coercive - Desarrollado por <a href="http://www.masoft.net">www.masoft.net</a> - Copyright © 2016-</font></td></tr>
    	
				</TR>
                </TABLE>
    			<br/>
				<br/>
				<br/>
				<br/>
				</body>
				</html>
				';
    	
    	$mensaje = $mensaje_cabeza . $mensaje_detalle . $mensaje_pie . $mensaje_cabeza1 . $mensaje_detalle1 . $mensaje_pie1;
    	
    	// Para enviar un corre=o HTML, debe establecerse la cabecera Content-type
    	$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
    	$cabeceras .= 'Content-type: text/html; utf8_decode' . "\r\n";
    	
    	// Cabeceras adicionales
    	$cabeceras .= 'To: ' . "\r\n";
    	$cabeceras .= 'From: allcoercive <maycol@masoft.net>' . "\r\n";
    	
    	// Enviarlo
    	mail($para,utf8_decode($título),utf8_decode($mensaje),utf8_decode($cabeceras));
    	    	
    	
    }
    
    
    
    
    public  function  SendMailRespuesta($para, $titulo, $lista, $imagen, $asunto)
    {
    
    	$título = 'Respuesta a: '.$asunto;
    
    	// mensaje
    	$mensaje_cabeza = '
    
    <html>
		<head>
			<title>INCIDENCIAS</title>
    		<meta charset="UTF-8">
			    </head>
				     <body style="background-color:#d9e3e4">
				   <div style="background-color:#d93e1b">
				<rigth><img src="http://18.221.171.210:80/coactiva_liventy/FrameworkMVC/view/images/logo-coctiva.png" WIDTH="200" HEIGHT="80" /></rigth>
	             </div>
    			<h2><center><b>RESPUESTA INCIDENCIAS</b></center></h2>
    
    			<TABLE rules="all" WIDTH="100%">
    
    			<TR>
									<TD WIDTH=200 bgcolor="#A4A4A4">
									  <h4><center><b>Responsable</b>
									</TD>
			
									<TD WIDTH=200 bgcolor="#A4A4A4">
									  <h4><center><b>Problema</b>
									</TD>
    		
									<TD WIDTH=200 bgcolor="#A4A4A4">
									  <h4><center><b>Descripción Solución</b>
									</TD>
    								<TD WIDTH=200 bgcolor="#A4A4A4">
									  <h4><center><b>Fecha</b>
									</TD>
    
					
								</TR>
    
								  ';
    
    	$mensaje_detalle = "";
    	 
    	foreach($lista as $res)
    	{
    
    		$mensaje_detalle .=  '<td><center>'. $res->nombre_usuarios .'   </td>' ;
    		$mensaje_detalle .=  '<td><center>'. $asunto .'   </td>' ;
    		$mensaje_detalle .=  '<td><justify>'. $res->descripcion_respuesta .'   </td>' ;
    		$mensaje_detalle .=  '<td><center>'. $res->creado .'   </td>' ;
    
    	}
    	 
    	 
    	 
    
    	$mensaje_pie =  '</table>
    
				<br/>
				<br/>
				<br/>
    
    
				</body>
				</html>
				';
    
    	 
    	$mensaje_cabeza1 = '
   
    <html>
			<body>
    
			<TABLE rules="all" WIDTH="100%">
   
    
   
								  ';
    	 
    	$mensaje_detalle1 = "";
    
    	foreach($lista as $res)
    	{
    		$mensaje_detalle1 .=  '<td><center><input type="image" name="image" src="http://18.221.171.210:80/coactiva_liventy/FrameworkMVC/view/DevuelveImagen.php?id_valor='.$res->id_respuesta_incidencia.'&id_nombre=id_respuesta_incidencia&tabla=respuesta_incidencia&campo=image_respuesta" alt="'.$res->id_respuesta_incidencia.'" width="600" height="550"></td>' ;
    		 
    	}
    
    
    
    	 
    	$mensaje_pie1 =  '</table>
   
				<br/>
				<br/>
				<br/>
    
    			<TABLE WIDTH="100%">
				<TR>
                <tr style="background:#1C1C1C"><td WIDTH="1000" HEIGHT="50" align="center"><font color="white">All Coercive - Desarrollado por <a href="http://www.masoft.net">www.masoft.net</a> - Copyright © 2016-</font></td></tr>
   
				</TR>
                </TABLE>
    			<br/>
				<br/>
				<br/>
				<br/>
				</body>
				</html>
				';
    	 
    	$mensaje = $mensaje_cabeza . $mensaje_detalle . $mensaje_pie . $mensaje_cabeza1 . $mensaje_detalle1 . $mensaje_pie1;
    	 
    	// Para enviar un corre=o HTML, debe establecerse la cabecera Content-type
    	$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
    	$cabeceras .= 'Content-type: text/html; utf8_decode' . "\r\n";
    	 
    	// Cabeceras adicionales
    	$cabeceras .= 'To: ' . "\r\n";
    	$cabeceras .= 'From: allcoercive <maycol@masoft.net>' . "\r\n";
    	 
    	// Enviarlo
    	mail($para,utf8_decode($título),utf8_decode($mensaje),utf8_decode($cabeceras));
    
    	 
    }
    
    function getRealIP() {
    	if (!empty($_SERVER['HTTP_CLIENT_IP']))
    		return $_SERVER['HTTP_CLIENT_IP'];
    	 
    	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    		return $_SERVER['HTTP_X_FORWARDED_FOR'];
    	 
    	return $_SERVER['REMOTE_ADDR'];
    }
    
    
    
    
    
    
    
    public function  InsertaEntidades($nombre_entidades){
    	
    	$entidades = New EntidadesModel();
    	
    	$funcion = "ins_entidades_liventy";
    	$parametros = "'$nombre_entidades'";
    	$entidades->setFuncion($funcion);
    	$entidades->setParametros($parametros);
    	$resultadoT=$entidades->Insert();
    }
    
    
    
    
    
 
  

    
    
    function myFunctionErrorHandler($errno, $errstr, $errfile, $errline)
    {
    	/* Según el típo de error, lo procesamos */
    	switch ($errno) {
    		case E_WARNING:
    			echo "Hay un WARNING.<br />\n";
    			echo "El warning es: ". $errstr ."<br />\n";
    			echo "El fichero donde se ha producido el warning es: ". $errfile ."<br />\n";
    			echo "La línea donde se ha producido el warning es: ". $errline ."<br />\n";
    			/* No ejecutar el gestor de errores interno de PHP, hacemos que lo pueda procesar un try catch */
    			return true;
    			break;
    
    		case E_NOTICE:
    			echo "Hay un NOTICE:<br />\n";
    			/* No ejecutar el gestor de errores interno de PHP, hacemos que lo pueda procesar un try catch */
    			return true;
    			break;
    
    		default:
    			/* Ejecuta el gestor de errores interno de PHP */
    			return false;
    			break;
    	}
    }
    
    
    
    public function verMacAddress(){
    /*
    	
    	
    	ob_start();
    
    	system('ipconfig /all');
    
    	$mycomsys=ob_get_contents();
    
    	ob_clean();
    		
    	$macaddress="";
    	$find_mac = "Direcci";
    
    	$pmac = strpos($mycomsys, $find_mac);
    		
    	if ($pmac === false) {
    
    	} else {
    		$find_mac = "Fhysical";
    		$macaddress=substr($mycomsys,($pmac+36),17);
    
    	}
    		
    		
    	$macaddress=substr($mycomsys,($pmac+43),23);
    
    	return $macaddress;*/
    	return '1';
    }
    
    
    
    

    
    
    
    
    public function MenuDinamico($_id_rol)
    {
    	$resultPermisos=array();
    	$perimisos_rol = new PermisosRolesModel();
    	 
    	$columnas="controladores.nombre_controladores,
				  permisos_rol.id_rol,
				  permisos_rol.ver_permisos_rol";
    	 
    	$tablas="public.permisos_rol,
  				 public.controladores";
    	 
    	$where="controladores.id_controladores = permisos_rol.id_controladores
    	AND permisos_rol.ver_permisos_rol=TRUE AND permisos_rol.id_rol='$_id_rol'";
    	 
    	$id="controladores.id_controladores";
    	 
    	$resultPermisos = $perimisos_rol->getCondiciones($columnas, $tablas, $where, $id);
    	 
    	$_SESSION['controladores']=$resultPermisos;
    }
    
    //php PHPMailer class
    
   
    
}
?>
