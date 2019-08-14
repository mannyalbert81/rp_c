<?php

class TransferenciasController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		$entidad = new CoreEntidadPatronalModel();
				
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    exit();
		}
		
		if( !isset($_GET['id_cuentas_pagar']) ){
		    
		    $this->redirect("Pagos","index");
		    exit();
		}
		
		$nombre_controladores = "GenerarTranferencias";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $entidad->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Empleo"
		        
		    ));
		    exit();
		}
		
		$_id_cuentas_pagar = $_GET['id_cuentas_pagar'];
		
		$datos=null;
		$datos['id_cuentas_pagar'] = $_id_cuentas_pagar;
		
		$query = "SELECT l.id_lote, l.nombre_lote, cp.id_cuentas_pagar, cp.numero_cuentas_pagar, cp.descripcion_cuentas_pagar, cp.fecha_cuentas_pagar,
                    cp.compras_cuentas_pagar, cp.total_cuentas_pagar, p.id_proveedores, p.nombre_proveedores, p.identificacion_proveedores,
                    b.id_bancos, b.nombre_bancos, m.id_moneda, m.signo_moneda || '-' || m.nombre_moneda AS moneda
                FROM tes_cuentas_pagar cp
                INNER JOIN tes_lote l
                ON cp.id_lote = l.id_lote
                INNER JOIN proveedores p
                ON p.id_proveedores = cp.id_proveedor
                INNER JOIN tes_bancos b
                ON b.id_bancos = cp.id_banco
                INNER JOIN tes_moneda m
                ON m.id_moneda = cp.id_moneda
                WHERE 1 = 1
                AND cp.id_cuentas_pagar = $_id_cuentas_pagar ";
		
		$rsCuentasPagar = $cuentasPagar->enviaquery($query);
		
		// PARA BUSCAR CONSECUTIVO DE PAGO
		
		$queryConsecutivo = "SELECT numero_consecutivos FROM consecutivos WHERE nombre_consecutivos = 'PAGOS' AND id_entidades = 1";
		
		$rsConsecutivos = $cuentasPagar->enviaquery($queryConsecutivo);
		
		//para buscar cheque
		$queryBanco = "SELECT id_bancos, lpad(index_bancos::text,espacio_bancos,'0') numero_cheque
                FROM tes_bancos ban
                INNER JOIN tes_cuentas_pagar cp
                ON ban.id_bancos = cp.id_banco
                WHERE id_cuentas_pagar = $_id_cuentas_pagar";
		
		$rsBanco= $cuentasPagar->enviaquery($queryBanco);
		
		$this->view_tesoreria("GenerarCheque",array(
		    "resultSet"=>$rsCuentasPagar,"rsConsecutivos"=>$rsConsecutivos,"datos"=>$datos,"rsBanco"=>$rsBanco
		));
			
		$rsEntidad = $entidad->getBy(" 1 = 1 ");
		
				
		$this->view_tesoreria("Transferencias",array(
		    "resultSet"=>$rsEntidad
	
		));
			
	
	}
	
	public function indexCheque(){
	    
	    session_start();
	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    $_id_usuarios = (isset($_SESSION['id_usuarios'])) ? $_SESSION['id_usuarios'] : null;
	    
	    if( !isset($_GET['id_cuentas_pagar']) ){
	        
	        $this->redirect("Pagos","index");
	        exit();
	    }
	    	    
	    $_id_cuentas_pagar = $_GET['id_cuentas_pagar'];
	    
	    $datos=null;
	    $datos['id_cuentas_pagar'] = $_id_cuentas_pagar;
	    
	    $query = "SELECT l.id_lote, l.nombre_lote, cp.id_cuentas_pagar, cp.numero_cuentas_pagar, cp.descripcion_cuentas_pagar, cp.fecha_cuentas_pagar, 
                    cp.compras_cuentas_pagar, cp.total_cuentas_pagar, p.id_proveedores, p.nombre_proveedores, p.identificacion_proveedores,
                    b.id_bancos, b.nombre_bancos, m.id_moneda, m.signo_moneda || '-' || m.nombre_moneda AS moneda
                FROM tes_cuentas_pagar cp
                INNER JOIN tes_lote l        
                ON cp.id_lote = l.id_lote
                INNER JOIN proveedores p
                ON p.id_proveedores = cp.id_proveedor
                INNER JOIN tes_bancos b
                ON b.id_bancos = cp.id_banco
                INNER JOIN tes_moneda m
                ON m.id_moneda = cp.id_moneda
                WHERE 1 = 1
                AND cp.id_cuentas_pagar = $_id_cuentas_pagar ";
	    
	    $rsCuentasPagar = $cuentasPagar->enviaquery($query);
	    
	    // PARA BUSCAR CONSECUTIVO DE PAGO 
	    
	    $queryConsecutivo = "SELECT numero_consecutivos FROM consecutivos WHERE nombre_consecutivos = 'PAGOS' AND id_entidades = 1";
	    
	    $rsConsecutivos = $cuentasPagar->enviaquery($queryConsecutivo);
	    
	    //para buscar cheque
	    $queryBanco = "SELECT id_bancos, lpad(index_bancos::text,espacio_bancos,'0') numero_cheque 
                FROM tes_bancos ban
                INNER JOIN tes_cuentas_pagar cp
                ON ban.id_bancos = cp.id_banco
                WHERE id_cuentas_pagar = $_id_cuentas_pagar";
        
	    $rsBanco= $cuentasPagar->enviaquery($queryBanco);
	    
	    $this->view_tesoreria("GenerarCheque",array(
	        "resultSet"=>$rsCuentasPagar,"rsConsecutivos"=>$rsConsecutivos,"datos"=>$datos,"rsBanco"=>$rsBanco
	    ));
	    
	}
	
	
	public function paginate($reload, $page, $tpages, $adjacents, $funcion = "") {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='$funcion(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='$funcion($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	/***
	 * return: json
	 * title: editBancos
	 * fcha: 2019-04-22
	 */
	public function editEntidad(){
	    
	    session_start();
	    $entidad = new CoreEntidadPatronalModel();
	    $nombre_controladores = "CoreEntidadPatronal";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $entidad->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    	     
	    if (!empty($resultPer))
	    {
	        
	        
	        if(isset($_POST["id_entidad_patronal"])){
	            
	            $_id_entidad_patronal = (int)$_POST["id_entidad_patronal"];
	            
	            $query = "SELECT * FROM core_entidad_patronal WHERE id_entidad_patronal = $_id_entidad_patronal";

	            $resultado  = $entidad->enviaquery($query);	            
	           
	            echo json_encode(array('data'=>$resultado));	            
	            
	        }
	       	        
	        
	    }
	    else
	    {
	        echo "Usuario no tiene permisos-Editar";
	    }
	    
	}
	
	public function generaTxt(){
	    
	    $fecha = date('my');
	    $nombreArchivo = "CASH_PAGOS_".$fecha."txt";
	    $archivo = __DIR__.'\\..\\view\\tesoreria\\documentos\\transferencias\\'.$nombreArchivo;
	    //validar archivo si existe en directorio
	    
	    $CuentasPagar = new CuentasPagarModel();
	    $query = "SELECT * FROM public.tes_cuentas_pagar";
	    $rsCuentasPagar = $CuentasPagar->enviaquery($query);
	    if( file_exists($archivo)){
	        
	        if(!empty($rsCuentasPagar)){
	            
	            $file = fopen($archivo, "a");
	            
	            foreach ($rsCuentasPagar as $res){
	                fwrite($file, $res->id_cuentas_pagar ."\t");
	                fwrite($file, number_format((float)$res->total_cuentas_pagar, 2, '', '')."\t");
	                //fwrite($file, "Esto es una nueva linea de texto" ."\t");
	                fwrite($file, PHP_EOL);
	            }
	            
	            fclose($file);
	        }
	        
	        $file = fopen($archivo, "a");
	        
	        fwrite($file, "Esto es una nueva linea de texto" ."\t");
	        
	        fwrite($file, "Otra más" . PHP_EOL);
	        
	        fclose($file);
	        
	        $file = fopen($archivo, "r");
	        
	        while(!feof($file)) {
	            
	            echo fgets($file). "<br />";
	            
	        }
	        
	        fclose($file);
	        
	    }else{
	        
	        if(!empty($rsCuentasPagar)){
	            
	            $file = fopen($archivo, "a");
	            
	            foreach ($rsCuentasPagar as $res){
	                fwrite($file, $res->id_cuentas_pagar ."\t");
	                fwrite($file, number_format((float)$res->total_cuentas_pagar, 2, '', '')."\t");
	                //fwrite($file, "Esto es una nueva linea de texto" ."\t");
	                fwrite($file, PHP_EOL);
	            }
	            
	            fclose($file);
	        }
	        
	        $file = fopen($archivo, "a");
	        
	        fwrite($file, "Esto es una nueva linea de texto" ."\t");
	        
	        fwrite($file, "Otra más" . PHP_EOL);
	        
	        fclose($file);
	        
	        $file = fopen($archivo, "r");
	        
	        while(!feof($file)) {
	            
	            echo fgets($file). "<br />";
	            
	        }
	        
	        fclose($file);
	    }
	        
        
       
	}
	
	public function DevuelveConsecutivos(){
	    
	    $Consecutivos = new ConsecutivosModel();
	    
	    $query = "SELECT LPAD(valor_consecutivos::text,espacio_consecutivos,'0') numero_consecutivos, id_consecutivos 
                FROM public.consecutivos WHERE nombre_consecutivos = 'PAGOS'";
	    
	    $rsConsecutivos = $Consecutivos->enviaquery($query);
	    
	    $respuesta = array();
	    
	    $respuesta['pagos'] = array('id'=>$rsConsecutivos[0]->id_consecutivos,'numero'=>$rsConsecutivos[0]->numero_consecutivos);
	    
	    echo json_encode($respuesta);
	}
}
?>