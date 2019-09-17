<?php

class IndexacionController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
        $bancos = new BancosModel();
        
        require_once 'core/EntidadBase_128.php';
        $db = new EntidadBase_128();
        
        
        session_start();
        
        if(empty( $_SESSION)){
            
            $this->redirect("Usuarios","sesion_caducada");
            return;
        }
        
        $nombre_controladores = "Indexacion";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bancos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (empty($resultPer)){
            
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos de Acceso Indexacion"
                
            ));
            exit();
        }
        
        $rsBancos = $bancos->getBy(" 1 = 1 ");
        
        
        $this->view_GestionDocumental("Indexacion",array(
            "resultSet"=>$rsBancos
            
        ));
        
        
    }
  
    public function cargaBancos(){
    
    	require_once 'core/EntidadBase_128.php';
    	$db = new EntidadBase_128();
    
    	$columnas="id_bancos, nombre_bancos";
    	$tabla = "tes_bancos";
    	$where = "local_bancos = 'TRUE' ";
    	$id="nombre_bancos";
    	$resulset = $db->getCondiciones($columnas,$tabla,$where,$id);
    
    	if(!empty($resulset) && count($resulset)>0){
    
    		echo json_encode(array('data'=>$resulset));
    
    	}
    }
    

    public function cargaCartonDocumentos(){
    
    	require_once 'core/EntidadBase_128.php';
    	$db = new EntidadBase_128();
    
    	$columnas="id_carton_documentos, numero_carton_documentos";
    	$tabla = "carton_documentos";
    	$where = "estado_carton_documentos = 'FALSE'";
    	$id="numero_carton_documentos";
    	$resulset = $db->getCondiciones($columnas,$tabla,$where,$id);
    
    	if(!empty($resulset) && count($resulset)>0){
    
    		echo json_encode(array('data'=>$resulset));
    
    	}
    }
    
    public function cargaTipoDocumentos(){
    
    	require_once 'core/EntidadBase_128.php';
    	$db = new EntidadBase_128();
    
    	$columnas="id_tipo_documentos, nombre_tipo_documentos";
    	$tabla = "tipo_documentos";
    	$where = "1=1";
    	$id="nombre_tipo_documentos";
    	$resulset = $db->getCondiciones($columnas,$tabla,$where,$id);
    
    	if(!empty($resulset) && count($resulset)>0){
    
    		echo json_encode(array('data'=>$resulset));
    
    	}
    	
    	
    }
    
    
  
    public function cargaCategoria(){
        
        require_once 'core/EntidadBase_128.php';
        $db = new EntidadBase_128();
        
        $columnas="id_categorias, nombre_categorias";
        $tabla = "categorias";
        $where = "1=1";
        $id="id_categorias";
        $resulset = $db->getCondiciones($columnas,$tabla,$where,$id);
        
        if(!empty($resulset) && count($resulset)>0){
            
            echo json_encode(array('data'=>$resulset));
            
        }
    }
    
    
    
    public function cargaSubCategoria(){
        
        require_once 'core/EntidadBase_128.php';
        $db = new EntidadBase_128();
        
        
        $id_categorias = (isset($_POST['id_categorias'])) ? $_POST['id_categorias'] : 0;
        
        if($id_categorias > 0){
            $columnas="id_subcategorias, nombre_subcategorias";
            $tabla = "subcategorias";
            $where = "id_categorias='$id_categorias'";
            $id="id_subcategorias";
            $resulset = $db->getCondiciones($columnas,$tabla,$where,$id);
            
            if(!empty($resulset) && count($resulset)>0){
                
                echo json_encode(array('data'=>$resulset));
                
            }
        }
       
    }
    
    

    public function AutocompleteNumeroCredito(){
        
        require_once 'core/EntidadBaseSQL.php';
        $db = new EntidadBaseSQL();
        
      
        if(isset($_GET['term'])){
            
            $cedula = $_GET['term'];
            
            
            $columnas = "c.CREDIT_ID, p.IDENTITY_CARD, p.LAST_NAME  AS NOMBRES";
            
            $tablas = "one.CREDIT c inner join one.PARTNER p on c.PARTNER_ID = p.PARTNER_ID and c.STATUS <> 0";
            
            $where = "p.IDENTITY_CARD LIKE '$cedula%'";
                                    
            $resultSet = $db->getCondiciones_SQL($columnas,$tablas,$where);
            
            print_r($resultSet);
            
            $respuesta = array();
            
            if(!empty($resultSet)){
                
                if(count($resultSet)>0){
                    
                    foreach ($resultSet as $res){
                        
                        $_cls_numero = new stdClass;
                        $_cls_numero->id=$res->CREDIT_ID;
                        $_cls_numero->value=$res->IDENTITY_CARD;
                        $_cls_numero->label= $res->CREDIT_ID.' | '.$res->NOMBRES;
                        $_cls_numero->nombre=$res->NOMBRES;
                        $_cls_numero->cedula=$res->IDENTITY_CARD;
                        
                        $respuesta[] = $_cls_numero;
                    }
                    
                    echo json_encode($respuesta);
                }
                
            }else{
                echo '[{"id":0,"value":"sin datos"}]';
            }
            
        }else{
            
            $numero_credito = (isset($_POST['term']))?$_POST['term']:'';
            
            $columnas = "capremci_creditos.id_capremci, 
                      capremci_creditos.numero_credito, 
                      capremci_creditos.cedula_capremci, 
                      capremci_creditos.nombres_capremci, 
                      capremci_creditos.creado, 
                      capremci_creditos.modificado";
            
            $tablas = "public.capremci_creditos";
            
            $where = " capremci_creditos.numero_credito= '$numero_credito'";
            
            $resultSet = $db->getBy($columnas,$tablas,$where);
            
            $respuesta = new stdClass();
            
           
            
            echo json_encode($respuesta);
            
        }
        
        
        
    }
    
    
    public function GenerarReporte(){
        
        require_once 'core/EntidadBase_128.php';
        $db = new EntidadBase_128();
        
        session_start();
        $entidades = new EntidadesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
        
        if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0){
            //llenar nombres con variables que va en html de reporte
            $datos_empresa['NOMBREEMPRESA']=$rsdatosEmpresa[0]->nombre_entidades;
            $datos_empresa['DIRECCIONEMPRESA']=$rsdatosEmpresa[0]->direccion_entidades;
            $datos_empresa['TELEFONOEMPRESA']=$rsdatosEmpresa[0]->telefono_entidades;
            $datos_empresa['RUCEMPRESA']=$rsdatosEmpresa[0]->ruc_entidades;
            $datos_empresa['FECHAEMPRESA']=date('Y-m-d H:i');
            $datos_empresa['USUARIOEMPRESA']=(isset($_SESSION['usuario_usuarios']))?$_SESSION['usuario_usuarios']:'';
        }
        
        //NOTICE DATA
        $datos_cabecera = array();
        $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
        $datos_cabecera['FECHA'] = date('Y/m/d');
        $datos_cabecera['HORA'] = date('h:i:s');
        
     
        $datos = array();
        
        $id_categorias = (isset($_POST['id_categorias'])) ? $_POST['id_categorias'] : 0;
        $id_subcategorias = (isset($_POST['id_subcategorias'])) ? $_POST['id_subcategorias'] : 0;
        $cedula_capremci = (isset($_POST['cedula_capremci'])) ? $_POST['cedula_capremci'] : '';
        $nombres_capremci = (isset($_POST['nombres_capremci'])) ? $_POST['nombres_capremci'] : '';
        $numero_credito = (isset($_POST['numero_credito'])) ? $_POST['numero_credito'] : '';
        $nombre_tipo_documentos = (isset($_POST['nombre_tipo_documentos'])) ? $_POST['nombre_tipo_documentos'] : '';
        $fecha_documento_legal = (isset($_POST['fecha_documento_legal'])) ? $_POST['fecha_documento_legal'] : '';
        $numero_carton_documentos = (isset($_POST['id_carton_documentos'])) ? $_POST['id_carton_documentos'] : '';
        
        $id_bancos = (isset($_POST['id_bancos'])) ? $_POST['id_bancos'] : '';
        $monto_documento = (isset($_POST['monto_documento'])) ? $_POST['monto_documento'] : '';
        $asunto_documento = (isset($_POST['asunto_documento'])) ? $_POST['asunto_documento'] : '';
        
        $datos_reporte = array();
        
        $datos_reporte['CATEGORIA']=(isset($_POST['id_categorias'])) ? $_POST['id_categorias']:0;
        $datos_reporte['SUBCATEGORIA']=(isset($_POST['id_subcategorias'])) ? $_POST['id_subcategorias']:0;
        $datos_reporte['CEDULA']=(isset($_POST['cedula_capremci'])) ? $_POST['cedula_capremci']:'';
        $datos_reporte['NOMBRES']=(isset($_POST['nombres_capremci'])) ? $_POST['nombres_capremci']:'';
        $datos_reporte['NUMCREDITO']=(isset($_POST['numero_credito'])) ? $_POST['numero_credito']:'';
        
        
        
        if ($cedula_capremci=='')
        {
        	$cedula_capremci='0';
        }
        if ($nombres_capremci=='')
        {
        	$nombres_capremci='0';
        }
        if ($numero_credito=='')
        {
        	$numero_credito='0';
        }
        if ($nombre_tipo_documentos=='')
        {
        	$nombre_tipo_documentos='0';
        }
        if ($nombre_tipo_documentos=='')
        {
        	$nombre_tipo_documentos='0';
        }
        if ($id_bancos=='')
        {
        	$id_bancos='0';
        }
        if ($monto_documento=='')
        {
        	$monto_documento='0';
        }
        if ($asunto_documento=='')
        {
        	$asunto_documento='0';
        }
        
        require dirname(__FILE__)."\phpqrcode\qrlib.php";
        
        $ubicacion = dirname(__FILE__).'\..\barcode_participes\\';
        
        //Si no existe la carpeta la creamos
        if (!file_exists($ubicacion))
            mkdir($ubicacion);
            
            $i++;
            $filename = $ubicacion.$id_categorias.'.png';
            
            //Parametros de Condiguracion
            
            $tamaño = 5; //Tama�o de Pixel
            $level = 'L'; //Precisi�n Baja
            $framSize = 3; //Tama�o en blanco
            $contenido = $id_categorias.';'. $id_subcategorias.';'. $cedula_capremci.';'. $nombres_capremci.';'. $numero_credito.';'. $nombre_tipo_documentos.';'. $fecha_documento_legal.';'. $numero_carton_documentos.';'. $id_bancos.';'. $monto_documento.';'. $asunto_documento;    
            
            //Enviamos los parametros a la Funci�n para generar c�digo QR
            QRcode::png($contenido, $filename, $level, $tamaño, $framSize);
            
            $qr_participes = '<img src="'.$filename.'">';
            
            
        
        
        
            $datos['CODIGO_QR']= $qr_participes;
        
            $this->verReporte("Indexacion", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos'=>$datos, 'datos_reporte'=>$datos_reporte));
        
        
        
        
        
    }
    
    
}
?>